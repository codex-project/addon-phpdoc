<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Codex;
use Codex\Projects\Project;
use Sebwite\Filesystem\Filesystem;

class StructureRepository
{
    protected $path;

    /** @var Codex */
    protected $codex;

    /** @var Filesystem */
    protected $fs;


    /*
     * $path (storage/codex/phpdoc)
     * /shared/laravel/...
     * /shared/codex/...
     * /projects/codex/master/...
     */

    public function getProjectStructureXml($project, $ref = null)
    {
        if(!$project instanceof Project){
            $project = $this->codex->projects->get($project);
        }

        $ref = $ref === null ? $project->getRef() : $project->getDefaultRef();

        $xmlPath = path_join($ref, $project->config('phpdoc.xml_path'));
        return $project->getFiles()->get($xmlPath);
    }

    public function getSharedStructureXml($name)
    {
        return $this->fs->get(path_join(resource_path('phpdoc'), $name));
    }
}
