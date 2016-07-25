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

use Codex\Codex;
use Codex\Projects\Project;
use Codex\Traits\CodexPluginTrait;
use Orchestra\Contracts\Foundation\Application;

class Plugin
{
    #use CodexPluginTrait;

    /**
     * The Laravel Application instance
     * @var Application
     */
    public $app;

    /**
     * This will be merged into the default_project_config
     * @var array
     */
    public $project = [ ];
    // or
    //public $project = 'codex-phpdoc.default_project_config';

    /**
     * This will be merged into the default_document_attributes
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
        Codex::class   => ['phpdoc' => Phpdoc::class ],
        Project::class => ['phpdoc' => PhpdocProject::class ],
    ];

    public $routeIgnoreNames = [
        'project-name-to-ignore'
    ];

    public function register()
    {
        //$this->app;
    }


    public function boot()
    {

    }


}
