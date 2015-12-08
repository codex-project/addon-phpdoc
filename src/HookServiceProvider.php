<?php

namespace Docit\Hooks\Phpdoc;

use Docit\Support\ServiceProvider;
use Docit\Core\Traits\DocitProviderTrait;
use Docit\Hooks\Phpdoc\Hooks\FactoryHook;
use Docit\Hooks\Phpdoc\Hooks\ProjectDocumentsMenuHook;
use Docit\Hooks\Phpdoc\Hooks\ProjectHook;

/**
 * The main service provider
 *
 * @author        Caffeinated
 * @copyright     Copyright (c) 2015, Caffeinated
 * @license       https://tldrlegal.com/license/mit-license MIT
 * @package       Docit\Hooks\Phpdoc
 */
class HookServiceProvider extends ServiceProvider
{
    use DocitProviderTrait;

    protected $dir = __DIR__;

    protected $configFiles = [ 'docit.hooks.phpdoc' ];

    protected $viewDirs = [ 'views' => 'docit-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'docit-phpdoc' ];

    protected $providers = [
        \Docit\Support\BeverageServiceProvider::class,
        Providers\RouteServiceProvider::class
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $app = parent::register();
        $this->addRouteProjectNameExclusions(config('docit.hooks.phpdoc.route_prefix'));
        $this->addDocitHook('factory:ready', FactoryHook::class);
        $this->addDocitHook('project:documents-menu', ProjectDocumentsMenuHook::class);
        $this->addDocitHook('project:ready', ProjectHook::class);
    }
}
