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

class Plugin
{
    public $project = [ ];
    // or
    public $project2 = 'codex-phpdoc.default_project_config';

    public $document = [ ];

    public $views = [
        'phpdoc.document' => 'codex-phpdoc::document',
        'phpdoc.entity'   => 'codex-phpdoc::entity',
    ];

    public $extend = [
        Codex::class   => Phpdoc::class,
        Project::class => PhpdocProject::class,
    ];

    public $routeIgnoreNames = [
        'project-name-to-ignore'
    ];
}
