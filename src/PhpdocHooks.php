<?php
/**
 * Created by IntelliJ IDEA.
 * User: radic
 * Date: 6/11/16
 * Time: 2:41 PM
 */

namespace Codex\Addon\Phpdoc;


use Codex\Addons\Annotations\Hook;
use Codex\Contracts\Documents\Documents;
use Codex\Contracts\Projects\Projects;
use Codex\Exception\CodexException;
use Codex\Projects\Project;

class PhpdocHooks
{

    /**
     * Adds the getPhpdocProjects() method to the Projects class
     * @Hook("projects:constructed")
     * @param \Codex\Contracts\Projects\Projects|\Codex\Projects\Projects $projects
     */
    public function projectsConstructed(Projects $projects)
    {
        $projects->extend('getPhpdocProjects', function () use ($projects) {
            return $projects->query()->filter(function (Project $project) {
                return $project->config('phpdoc.enabled', false) === true;
            });
        });
    }

    /**
     * Adds the $phpdoc property to the Project class
     * @Hook("project:constructed")
     * @param \Codex\Projects\Project $project
     */
    public function projectConstructed(Project $project)
    {
        $project->extend('phpdoc', PhpdocProject::class);
    }

    /**
     * Adds the PhpdocDocument as custom document
     * @Hook("documents:constructed")
     * @param \Codex\Contracts\Documents\Documents|\Codex\Documents\Documents $documents
     */
    public function documentsConstructed(Documents $documents)
    {
        $project = $documents->getProject();
        $documents->addCustomDocument($project->config('phpdoc.document_slug', 'phpdoc'), function (Documents $documents) use ($project) {
            $path = $project->refPath($project->config('phpdoc.path'));
            $pfs  = $project->getFiles();
            if ( ! $pfs->exists($path) ) {
                throw CodexException::documentNotFound('phpdoc');
            }
            return [ 'path' => $path, 'binding' => 'codex.phpdoc.document' ];
        });
    }
}
