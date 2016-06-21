<?php
namespace Codex\Addon\Phpdoc;

use Codex\Contracts\Codex;
use Codex\Traits\CodexProviderTrait;
use Sebwite\Support\ServiceProvider;

class PhpdocServiceProvider extends ServiceProvider
{
    use CodexProviderTrait;

    protected $dir = __DIR__;

    protected $configFiles = [ 'codex-phpdoc' ];

    protected $viewDirs = [ 'views' => 'codex-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'codex-phpdoc' ];

    protected $providers = [
        Http\HttpServiceProvider::class,
    ];


    protected $commands = [
        Console\ClearCacheCommand::class,
        Console\CreateCacheCommand::class,
    ];

    protected $bindings = [
        'codex.phpdoc.project'  => PhpdocProject::class,
        'codex.phpdoc.document' => PhpdocDocument::class,
    ];

    public function register()
    {
        $app = parent::register();

        // This will disable `phpdoc` as project name so we can bind our own
        $this->codexIgnoreRoute(config('codex-phpdoc.route_prefix'));

        // This will merge into the codex.default_project_config. Either:
        // 1: Define a array to merge into codex.default_project_config
        // 2: Point to a existing configuration key (string) that has the array you want to merge into codex.default_project_config
        $this->codexProjectConfig('codex-phpdoc.default_project_config');

        // Register views like this. It gives other developers the chance to override them in the boot() phase
        $this->codexView('phpdoc', [
            'document' => 'codex-phpdoc::document',
            'entity'   => 'codex-phpdoc::entity',
        ]);

        $this->codexHook('constructed', function(Codex $codex){
            $codex->extend('phpdoc', Phpdoc::class);
        });

        return $app;
    }


}
