<?php
namespace Codex\Addon\Phpdoc;

use Codex\Contracts\Codex;
use Codex\Contracts\Documents\Documents;
use Codex\Contracts\Projects\Projects;
use Codex\Exception\CodexException;
use Codex\Projects\Project;
use Codex\Traits\CodexProviderTrait;
use Sebwite\Support\ServiceProvider;

class PhpdocServiceProvider extends ServiceProvider
{
    use CodexProviderTrait;

    protected $dir = __DIR__;

    protected $configFiles = [ 'codex-phpdoc' ];

    protected $viewDirs = [ 'views' => 'codex-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'codex-phpdoc' ];

    protected $commands = [
        Console\ClearCacheCommand::class,
        Console\CreateCacheCommand::class,
    ];

    protected $bindings = [
        'codex.phpdoc.project'  => PhpdocProject::class,
        'codex.phpdoc.document' => PhpdocDocument::class,
    ];

    protected $shared = [
        'codex.phpdoc' => Phpdoc::class
    ];

    public function register()
    {
        $app = parent::register();
        if($app['config']['codex.http.enabled'])
        {
            $this->registerRoutes();
        }

        $this->registerConfig();
        $this->registerViews();
        $this->registerLink();
        $this->registerCodexExtension();
        $this->registerProjectsExtension();
        $this->registerProjectExtension();
        $this->registerCustomDocument();

        return $app;
    }

    public function registerLink()
    {
        $this->app['config']->set('codex.links.phpdoc', PhpdocLink::class . '@handle');
    }


    public function registerCodexExtension()
    {
        $this->codexHook('constructed', function (Codex $codex)
        {
            $codex->extend('phpdoc', Phpdoc::class);
        });
    }

    public function registerProjectsExtension()
    {
        $this->codexHook('projects:constructed', function (Projects $projects)
        {
            /** @var \Codex\Contracts\Projects\Projects|\Codex\Projects\Projects $projects */
            $projects->extend('getPhpdocProjects', function () use ($projects)
            {
                return $projects->query()->filter(function (Project $project)
                {
                    return $project->config('phpdoc.enabled', false) === true;
                });
            });
        });
    }

    public function registerProjectExtension()
    {
        $this->codexHook('project:constructed', function (Project $project)
        {
            $project->extend('phpdoc', PhpdocProject::class);
        });
    }

    public function registerCustomDocument()
    {

        $this->codexHook('documents:constructed', function (Documents $documents)
        {
            /** @var \Codex\Contracts\Documents\Documents|\Codex\Documents\Documents $documents */
            $project = $documents->getProject();
            $documents->addCustomDocument($project->config('phpdoc.document_slug', 'phpdoc'), function (Documents $documents) use ($project)
            {
                $path = $project->refPath($project->config('phpdoc.path'));
                $pfs  = $project->getFiles();
                if ( ! $pfs->exists($path) )
                {
                    throw CodexException::documentNotFound('phpdoc');
                }
                return [ 'path' => $path, 'binding' => 'codex.phpdoc.document' ];
            });
        });
    }

    protected function registerRoutes()
    {
        $this->app->register(Http\HttpServiceProvider::class);
        $this->codexIgnoreRoute(config('codex-phpdoc.route_prefix'));
    }

    protected function registerConfig()
    {
        // This will merge into the codex.default_project_config. Either:
        // 1: Define a array to merge into codex.default_project_config
        // 2: Point to a existing configuration key (string) that has the array you want to merge into codex.default_project_config
        $this->codexProjectConfig('codex-phpdoc.default_project_config');
    }

    protected function registerViews()
    {
        $this->codexView('phpdoc', [
            'document' => 'codex-phpdoc::document',
            'entity'   => 'codex-phpdoc::entity',
        ]);
    }
}
