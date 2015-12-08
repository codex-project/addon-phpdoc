<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Docit\Hooks\Phpdoc\Hooks;

use Docit\Core\Contracts\Hook;
use Docit\Core\Menus\Menu;
use Docit\Core\Project;

/**
 * This is the Hook.
 *
 * @package        Docit\Core
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ProjectDocumentsMenuHook implements Hook
{

    /**
     * handle
     *
     * @param \Docit\Core\Project $project
     */
    public function handle(Project $project, Menu $menu)
    {
        if (! $project->config('enable_phpdoc_hook', false)) {
            return;
        }
        $node = $menu->add('phpdoc', $project->config('phpdoc_hook_settings.menu_name'));
        $node->setMeta('icon', $project->config('phpdoc_hook_settings.menu_icon'));
        $node->setAttribute('href', route('docit.phpdoc', [
            'projectName' => $project->getName(),
            'ref'         => $project->getRef()
        ]));
    }
}
