<?php

namespace Codex\Hooks\Phpdoc;

use Codex\Core\Project;
use Codex\Core\Traits\ProvidesCodex;
use Codex\Hooks\Phpdoc\Hooks\FactoryHook;
use Codex\Hooks\Phpdoc\Hooks\ProjectDocumentsMenuHook;
use Sebwite\Support\ServiceProvider;

/**
 * The main service provider
 *
 * @author        Caffeinated
 * @copyright     Copyright (c) 2015, Caffeinated
 * @license       https://tldrlegal.com/license/mit-license MIT
 * @package       Codex\Hooks\Phpdoc
 */
class HookServiceProvider extends ServiceProvider
{
    use ProvidesCodex;

    protected $dir = __DIR__;

    protected $configFiles = [ 'codex.hooks.phpdoc' ];

    protected $viewDirs = [ 'views' => 'codex-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'codex-phpdoc' ];

    protected $providers = [
        Providers\RouteServiceProvider::class
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $app = parent::register();
        $this->addRouteProjectNameExclusions(config('codex.hooks.phpdoc.route_prefix'));
        $this->addCodexHook('factory:ready', FactoryHook::class);
        $this->addCodexHook('project:documents-menu', ProjectDocumentsMenuHook::class);

        Project::macro('getPhpdocDocument', function () {
            /** @var Project $this */
            return app()->make(PhpdocDocument::class, [
                'project' => $this,
                'factory' => $this->getCodex()
            ]);
        });
    }
}
