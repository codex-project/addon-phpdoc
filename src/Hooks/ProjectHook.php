<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Docit\Hooks\Phpdoc\Hooks;

use Docit\Core\Contracts\Hook;
use Docit\Core\Project;
use Docit\Hooks\Phpdoc\PhpdocDocument;
use Illuminate\Contracts\Container\Container;

/**
 * This is the Hook.
 *
 * @package        Docit\Core
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ProjectHook implements Hook
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    public $container;

    /**
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }


    /**
     * handle
     *
     * @param \Docit\Core\Project $project
     */
    public function handle(Project $project)
    {
        $that = $this;
        Project::macro('getPhpdocDocument', function () use ($that) {
            /** @var Project $this */
            return $that->container->make(PhpdocDocument::class, [
                'project' => $this,
                'factory' => $this->getFactory()
            ]);
        });
    }
}
