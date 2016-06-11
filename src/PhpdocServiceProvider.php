<?php
namespace Codex\Addon\Phpdoc;

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

        $this->codexIgnoreRoute(config('codex-phpdoc.route_prefix'));
        $this->codexProjectConfig('codex-phpdoc.default_project_config');
        $this->codexView('phpdoc', [
            'document' => 'codex-phpdoc::document',
            'entity'   => 'codex-phpdoc::entity',
        ]);
        return $app;
    }


}
