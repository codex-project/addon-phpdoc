<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author    Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license   http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Structure\File;
use Codex\Addon\Phpdoc\Tree\Node;
use Codex\Projects\Project;
use Codex\Projects\Ref;
use Codex\Support\Collection;
use Illuminate\Contracts\Cache\Repository;
use Laradic\Filesystem\Filesystem;

class PhpdocRef
{
    /** @var  Project */
    protected $project;

    protected $cachePath;

    protected $entities = [ ];

    /** @var \Laradic\Filesystem\Filesystem */
    protected $fs;

    /** @var \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\Cache\Store */
    protected $cache;

    protected $compiler;

    /** @var \Codex\Projects\Project|\Codex\Projects\Ref */
    protected $ref;


    /**
     * Factory constructor.
     *
     * @param \Codex\Projects\Project                                                  $parent
     * @param \Laradic\Filesystem\Filesystem                                           $fs
     * @param \Illuminate\Contracts\Cache\Repository|\Illuminate\Contracts\Cache\Store $cache
     */
    public function __construct(Ref $parent, Filesystem $fs, Repository $cache)
    {
        $this->fs       = $fs;
        $this->cache    = $cache;
        $this->ref      = $parent;
        $this->project  = $parent->getProject();
        $this->compiler = new Compiler($fs);
        $this->setCachePath(path_join(
            config('codex-phpdoc.cache_path'),
            $this->project->getName(),
            $parent->getName()
        ));
        #$this->checkUpdate();
        $this->parent = $parent;
    }

    public function getCacheKey()
    {
        return "codex.phpdoc.project.{$this->project}.{$this->ref}";
    }

    public function clearCache()
    {
        $this->cache->forget($this->getCacheKey());
    }

    public function checkUpdate($forceUpdate = false)
    {
        if(false === $this->hasXmlFile()){
            return;
        }
        $cachedLastModified = (int)$this->cache->get($this->getCacheKey(), 0);
        if ( $forceUpdate === true || $cachedLastModified !== $this->getLastModified() ) {

            $this->compiler->compile($this->getStructureXml(), $this->getCachePath());
            $this->cache->forever($this->getCacheKey(), $this->getLastModified());
        }
    }

    public function getLastModified()
    {
        return (int)$this->project->getFiles()->lastModified(
            $this->getXmlFilePath()
        );
    }

    public function getXmlFilePath()
    {
        return $this->ref->path($this->project->config('phpdoc.xml_path'));
    }

    public function hasXmlFile()
    {
        return $this->project->getFiles()->exists($this->getXmlFilePath());
    }

    public function getStructureXml()
    {
        return $this->project->getFiles()->get($this->getXmlFilePath());
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


    /**
     * Returns a Class, Trait or Interface
     *
     * @param $full_name
     *
     * @return File
     */
    public function getEntity($full_name)
    {
        if ( !array_key_exists($full_name, $this->entities) ) {
            $filePath = $this->getCacheFilePath(Util::toFileName($full_name, '.dat'));
            if ( $this->fs->exists($filePath) === false ) {
                return null;
            }
            $file = $this->fs->get($filePath);
            /** @var File $file */
            $file = $this->entities[ $full_name ] = unserialize($file);
            $file->getEntity();
        }
        return $this->entities[ $full_name ];
    }

    /**
     * getElements method
     *
     * @param bool $full
     *
     * @return \Codex\Support\Collection|File[]
     */
    public function getEntities($full = false)
    {
        return
            $full ?
                $this->getManifest()->transform(function ($item) {
                    return $this->getEntity($item[ 'full_name' ]);
                })
                :
                $this->getManifest();
    }

    public function hasEntity($full_name)
    {
        return $this->fs->exists($this->getCacheFilePath(Util::toFileName($full_name, '.dat')));
    }

    public function url($full_name = null)
    {
        $url = $this->project->url($this->project->config('phpdoc.document_slug', 'phpdoc'), $this->ref->getName());
        if ( $full_name ) {
            $url .= "#!/{$full_name}";
        }
        return $url;
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
        return $this->makeTree($this->getEntities($full));
    }

    public function nodeTree()
    {
        $data = $this->makeTree($this->getEntities());
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
    public function getCompiler()
    {
        return $this->compiler;
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
     * @param \Codex\Addon\Phpdoc\Extractor $compiler
     */
    public function setCompiler($compiler)
    {
        $this->compiler = $compiler;
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
