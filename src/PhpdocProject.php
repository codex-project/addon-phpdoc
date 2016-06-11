<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addon\Phpdoc\Tree\Node;
use Codex\Projects\Project;
use Codex\Support\Collection;
use Illuminate\Contracts\Cache\Repository;
use Sebwite\Filesystem\Filesystem;

class PhpdocProject
{
    /** @var  Project */
    protected $project;

    protected $cachePath;

    protected $elements = [ ];

    /** @var \Sebwite\Filesystem\Filesystem */
    protected $fs;

    /** @var \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\Cache\Store */
    protected $cache;

    protected $extractor;


    /**
     * Factory constructor.
     *
     * @param \Codex\Projects\Project                                                  $parent
     * @param \Sebwite\Filesystem\Filesystem                                           $fs
     * @param \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\Cache\Store $cache
     */
    public function __construct(Project $parent, Filesystem $fs, Repository $cache)
    {
        $this->fs        = $fs;
        $this->cache     = $cache;
        $this->extractor = new Extractor($this);
        $this->getExtractor()->setRawResolver(function (Extractor $extractor) {
            return $this->project->getFiles()->get(
                $this->project->refPath($this->project->config('phpdoc.path'))
            );
        });
        $this->project = $parent;
        $this->setCachePath(path_join(
            config('codex-phpdoc.cache_path'),
            $parent->getName(),
            $parent->getRef()
        ));
        $this->checkUpdate();
    }

    public function getCacheKey()
    {
        return "codex.phpdoc.project.{$this->project->getName()}.{$this->project->getRef()}";
    }

    public function clearCache()
    {
        file_cleanDirectory($this->getCachePath());
        $this->cache->forget($this->getCacheKey());
    }
    public function checkUpdate($forceUpdate = false)
    {
        $cachedLastModified = (int)$this->cache->get($this->getCacheKey(), 0);
        if ( $forceUpdate === true || $cachedLastModified !== $this->getLastModified() ) {
            $this->extractor->run();
            $this->cache->forever($this->getCacheKey(), $this->getLastModified());
        }
    }

    public function url($full_name = null)
    {
        $url = $this->project->url($this->project->config('phpdoc.document_slug', 'phpdoc'), $this->project->getRef());
        if ( $full_name ) {
            $url .= "#!/{$full_name}";
        }
        return $url;
    }

    public function getLastModified()
    {
        return (int)$this->project->getFiles()->lastModified(
            $this->project->refPath($this->project->config('phpdoc.path'))
        );
    }

    /**
     * getManifest method
     * @return Collection
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getManifest()
    {
        return new Collection($this->fs->getRequire($this->getCacheFilePath('manifest.php')));
    }

    public function getInfo()
    {
        return $this->fs->getRequire($this->getCacheFilePath('info.php'));
    }

    /**
     * Returns a Class, Trait or Interface
     *
     * @param $full_name
     *
     * @return Element
     */
    public function getElement($full_name)
    {
        if ( ! array_key_exists($full_name, $this->elements) ) {
            $filePath = $this->getCacheFilePath(Util::toFileName($full_name, '.xml'));
            if($this->fs->exists($filePath) === false){
                return null;
            }
            $file                         = $this->fs->get($filePath);
            $this->elements[ $full_name ] = Element::create($file);
        }
        return $this->elements[ $full_name ];
    }

    /**
     * getElements method
     *
     * @param bool $full
     *
     * @return \Codex\Support\Collection
     */
    public function getElements($full = false)
    {
        return
            $full ?
                $this->getManifest()->transform(function ($item) {
                    return $this->getElement($item[ 'full_name' ]);
                })
                :
                $this->getManifest();
    }

    public function hasElement($full_name)
    {
        return $this->fs->exists($this->getCacheFilePath(Util::toFileName($full_name, '.xml')));
    }

    public function makeTree($elements = [ ], $only = null)
    {
        $tree = [ ];
        $done = [ ];
        foreach ( $elements as $item ) {
            $tree2 = &$tree;
            if ( in_array($item[ 'full_name' ], $done, true) ) {
                continue;
            }
            $done[]   = $item[ 'full_name' ];
            $segments = explode('\\', $item[ 'full_name' ]);
            if ( $segments[ 0 ] === '' ) {
                array_shift($segments);
            }
            while ( count($segments) ) {
                $segment = array_shift($segments);
                $last    = count($segments) === 0;
                if ( ! $last ) {
                    is_array($tree2) && krsort($tree2);
                    $tree2 =& $tree2[ $segment ];
                } else {

                    if ( ! is_array($tree2) ) {
                        $tree2 = [ ];
                    }

                    if ( $only === null ) {
                        $tree2[] = $item;
                    } elseif ( is_array($only) ) {
                        $tree2[] = array_only($item, $only);
                    } elseif ( $only === true ) {
                        $tree2[] = $item[ 'name' ];
                    }
                }
            }
        }

        return $tree;
    }

    public function tree($full = false)
    {
        return $this->makeTree($this->getElements($full));
    }

    public function nodeTree()
    {
        $data = $this->makeTree($this->getElements());
        $root = new Node(key($data), Node::TYPE_NAMESPACE);
        return $this->buildNodeTree($data, $root);
    }

    protected function buildNodeTree($data, Node $parent)
    {
        foreach ( $data as $k => $v ) {
            if ( is_string($k) && is_array($v) ) {
                $node = new Node($k, Node::TYPE_NAMESPACE);
                $parent->addChild($node);
                $node->setParent($parent);
                $this->buildNodeTree($v, $node);
            } elseif ( is_int($k) ) {
                $node = new Node($v, $v[ 'type' ]);
                $parent->addChild($node);
                $node->setParent($parent);
            }
        }
        return $parent;
    }


    public function getCacheFilePath($fileName)
    {
        return path_join($this->cachePath, $fileName);
    }

    /**
     * @return mixed
     */
    public function getCachePath()
    {
        return $this->cachePath;
    }

    /**
     * @return Filesystem
     */
    public function getFs()
    {
        return $this->fs;
    }

    /**
     * @return \Codex\Addon\Phpdoc\Extractor
     */
    public function getExtractor()
    {
        return $this->extractor;
    }

    /**
     * Set the cachePath value
     *
     * @param mixed $cachePath
     */
    public function setCachePath($cachePath)
    {
        $this->cachePath = $cachePath;
    }

    /**
     * Set the extractor value
     *
     * @param \Codex\Addon\Phpdoc\Extractor $extractor
     */
    public function setExtractor($extractor)
    {
        $this->extractor = $extractor;
    }


    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set the project value
     *
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }


}
