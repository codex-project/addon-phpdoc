<?php
namespace Codex\Hooks\Phpdoc\Hooks;

use Codex\Core\Contracts\Hook;
use Codex\Core\Factory;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;

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
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new FilesystemFactoryHook instance
     *
     * @param  \Illuminate\Contracts\Config\Repository $config
     * @param  \Illuminate\Filesystem\Filesystem       $files
     */
    public function __construct(Repository $config, Filesystem $files)
    {
        $this->config = $config;
        $this->files  = $files;
    }

    /**
     * Handle the factory hook.
     *
     * @param  \Codex\Core\Factory $factory
     *
*@return void
     */
    public function handle(Factory $factory)
    {
        $factory->setConfig(
            array_replace_recursive(
                $factory->config(),
                $this->config->get('codex.hooks.phpdoc')
            )
        );
    }
}
