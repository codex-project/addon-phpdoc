<?php
namespace Codex\Addon\Phpdoc;

use Codex\Core\Projects\Project;

class Factory
{
    /**
     * make method
     *
     * @param \Codex\Core\Projects\Project $project
     *
     * @return ProjectPhpdoc
     */
    public function make(Project $project)
    {
        return app('codex.phpdoc.project', compact('project'));
    }
}