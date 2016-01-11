<?php

namespace Codex\Hooks\Phpdoc;

use Codex\Core\Project;
use Codex\Core\Traits\CodexProviderTrait;
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
    use CodexProviderTrait;

    protected $dir = __DIR__;

    protected $configFiles = [ 'codex.hooks.phpdoc' ];

    protected $viewDirs = [ 'views' => 'codex-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'codex-phpdoc' ];

    protected $providers = [
        Providers\RouteServiceProvider::class
    ];

    protected $bindings = [
        'codex.hooks.phpdoc.document' => PhpdocDocument::class
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {

        $app = parent::register();
        $this->codexRouteExclusion(config('codex.hooks.phpdoc.route_prefix'));
        $this->codexHook('factory:ready', FactoryHook::class);
        $this->codexHook('project:documents-menu', ProjectDocumentsMenuHook::class);
        $this->extendProject();
    }

    public function extendProject()
    {

        Project::extend('getPhpdocDocument', function () {
            /** @var Project $this */
            return app()->make('codex.hooks.phpdoc.document', [
                'project' => $this,
                'codex' => $this->getCodex()
            ]);
        });
    }
}
