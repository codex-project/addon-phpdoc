<?php
namespace Codex\Addon\Phpdoc;

use Codex\Projects\Project;

class Factory
{
    /**
     * make method
     *
     * @param \Codex\Projects\Project $project
     *
     * @return ProjectPhpdoc
     */
    public function make(Project $project)
    {
        return app('codex.phpdoc.project', compact('project'));
    }

    public function project(Project $project)
    {
        return app('codex.phpdoc.project', compact('project'));
    }

    public function file($path)
    {
        return app('codex.phpdoc.file', compact('path'));
    }
}