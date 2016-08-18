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
namespace Codex\Addon\Phpdoc\Structure;

use Codex\Addon\Phpdoc\Util;
use Codex\Projects\Project;
use Codex\Support\Collection;
use Laradic\Filesystem\Filesystem;

class StructureFactory
{
    protected $path;

    /** @var Filesystem */
    protected $fs;

    /** @var Collection */
    protected $manifest;

    /** @var \Codex\Codex */
    protected $codex;


    public function getManifest()
    {
        if($this->manifest === null) {
            $this->manifest = new Collection($this->fs->getRequire(path_join($this->path, 'manifest.php')));
        }
    }

    /**
     * getEntity method
     *
     * @param $full_name
     *
     * @return Entity
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getEntity($full_name)
    {
        $filePath = path_join($this->path, Util::toFileName($full_name, '.dat'));
        $data = $this->fs->get($filePath);
        return unserialize($data);
    }


    protected function getProjectStructureXml($project, $ref = null)
    {
        if(!$project instanceof Project){
            $project = $this->codex->projects->get($project);
        }

        $ref = $ref === null ? $project->getRef() : $project->getDefaultRef();

        $xmlPath = path_join($ref, $project->config('phpdoc.xml_path'));
        return $project->getFiles()->get($xmlPath);
    }
}
