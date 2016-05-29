<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addon\Phpdoc\Tree\Node;
use Codex\Core\Projects\Project;
use Illuminate\Contracts\Cache\Repository;
use Sebwite\Filesystem\Filesystem;

class ProjectPhpdoc
{


    protected $cachePath;

    protected $elements = [ ];

    /** @var \Codex\Core\Projects\Project */
    private $project;

    /** @var \Sebwite\Filesystem\Filesystem */
    private $fs;

    /** @var \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\Cache\Store */
    private $cache;


    /**
     * Factory constructor.
     *
     * @param \Illuminate\Contracts\Cache\Store $cache
     * @param \Sebwite\Filesystem\Filesystem    $fs
     */
    public function __construct(Filesystem $fs, Repository $cache, Project $project)
    {
        $this->fs      = $fs;
        $this->project = $project;

        $this->cachePath = path_join(
            config('codex-phpdoc.cache_path'),
            $this->project->getName(),
            $this->project->getRef()
        );

        $this->extractor = new Extractor($this);
        $this->cache     = $cache;
        #   $this->checkUpdate();
    }

    public function checkUpdate($forceUpdate = false)
    {
        $cacheKey           = "codex.phpdoc.project.{$this->project->getName()}.{$this->project->getRef()}";
        $cachedLastModified = (int)$this->cache->get($cacheKey, 0);
        if ( $forceUpdate === true || $cachedLastModified !== $this->getLastModified() ) {
            $this->extractor->run();
            $this->cache->forever($cacheKey, $this->getLastModified());
        }
    }

    protected function getLastModified()
    {
        return (int)$this->project->getFiles()->lastModified(
            $this->project->refPath($this->project->config('phpdoc.path'))
        );
    }


    public function getManifest()
    {
        return collect($this->fs->getRequire($this->getCacheFilePath('manifest.php')));
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
        if ( !array_key_exists($full_name, $this->elements) ) {
            $file                         = $this->fs->get($this->getCacheFilePath(Util::toFileName($full_name, '.xml')));
            $this->elements[ $full_name ] = Element::create($file);
        }
        return $this->elements[ $full_name ];
    }

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
            if($segments[0] === '') {
                array_shift($segments);
            }
            while ( count($segments) ) {
                $segment = array_shift($segments);
                $last    = count($segments) === 0;
                if ( !$last ) {
                    is_array($tree2) && krsort($tree2);
                    $tree2 =& $tree2[ $segment ];
                } else {

                    if ( !is_array($tree2) ) {
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
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return Filesystem
     */
    public function getFs()
    {
        return $this->fs;
    }


}