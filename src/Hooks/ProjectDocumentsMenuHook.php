<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Hooks\Phpdoc\Hooks;

use Codex\Core\Contracts\Hook;
use Codex\Core\Menus\Menu;
use Codex\Core\Project;

/**
 * This is the Hook.
 *
 * @package        Codex\Core
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class ProjectDocumentsMenuHook implements Hook
{

    /**
     * handle
     *
     * @param \Codex\Core\Project $project
     */
    public function handle(Project $project, Menu $menu)
    {
        if (! $project->hasEnabledHook('phpdoc')) {
            return;
        }
        $node = $menu->add('phpdoc', $project->config('hooks.phpdoc.menu_name'));
        $node->setMeta('icon', $project->config('hooks.phpdoc.menu_icon'));
        $node->setAttribute('href', route('codex.phpdoc', [
            'projectName' => $project->getName(),
            'ref'         => $project->getRef()
        ]));
    }
}
