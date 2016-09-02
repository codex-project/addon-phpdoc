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

use Codex\Addons\Annotations\Hook;
use Codex\Addons\Annotations\Plugin;
use Codex\Codex;
use Codex\Contracts\Documents\Documents;
use Codex\Exception\CodexException;
use Codex\Projects\Project;
use Codex\Support\Traits\CodexPluginTrait;
use Orchestra\Contracts\Foundation\Application;

/**
 * This is the class Plugin.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 * @Plugin("phpdoc")
 */
class PhpdocPlugin
{
    use CodexPluginTrait;

    /**
     * The Laravel Application instance
     * @var Application
     */
    public $app;

    /**
     * This will be merged into the default_project_config.phpdoc
     * @var array
     */
    public $project = [ ];

    // or
    //public $project = 'codex-phpdoc.default_project_config';

    /**
     * This will be merged into the default_document_attributes.phpdoc
     * @var array
     */
    public $document = [ ];

    /**
     * Define or overide views
     * @var array
     */
    public $views = [
        'phpdoc.document' => 'codex-phpdoc::document',
        'phpdoc.entity'   => 'codex-phpdoc::entity',
    ];

    /**
     * Shortcut to extend Extendable classes
     * @var array
     */
    public $extend = [
        Codex::class   => [ 'phpdoc' => Phpdoc::class ],
        Project::class => [ 'phpdoc' => PhpdocRef::class ],
    ];

    public $routeExclusions = [
        'project-name-to-ignore',
    ];

    public function register()
    {
    }


    public function boot()
    {
    }


    /**
     * registerCustomDocument method
     * @Hook("documents:constructed")
     *
     * @param \Codex\Documents\Documents|\Codex\Contracts\Documents\Documents $documents
     */
    public function registerCustomDocument(Documents $documents)
    {

        $project = $documents->getProject();
        $documents->addCustomDocument($project->config('phpdoc.document_slug', 'phpdoc'), function (Documents $documents) use ($project) {
            $path = $project->refPath($project->config('phpdoc.path'));
            $pfs  = $project->getFiles();
            if ( !$pfs->exists($path) ) {
                throw CodexException::documentNotFound('phpdoc');
            }
            return [ 'path' => $path, 'binding' => 'codex.phpdoc.document' ];
        });
    }


}
