<?php
namespace Codex\Hooks\Phpdoc\Hooks;

use Codex\Core\Contracts\Codex;
use Codex\Core\Contracts\Hook;

/**
 * Filesystem factory hook.
 *
 * @package   Codex\Filesystem-hook
 * @author    Codex Project Dev Team
 * @copyright Copyright (c) 2015, Codex Project
 * @license   https://tldrlegal.com/license/mit-license MIT License
 */
class FactoryHook implements Hook
{
    public function handle(Codex $codex)
    {
        $codex->mergeDefaultProjectConfig('codex.hooks.phpdoc.default_project_config');
    }
}
