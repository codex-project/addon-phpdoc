<?php
namespace Codex\Addon\Phpdoc;

use Codex\Documents\Documents;
use Codex\Exception\CodexException;
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

    protected $shared = [
        'codex.phpdoc' => Factory::class,
    ];

    protected $bindings = [
        'codex.phpdoc.project'  => ProjectPhpdoc::class,
        'codex.phpdoc.document' => PhpdocDocument::class,
    ];

    public function register()
    {
        $app = parent::register();

        $this->codexIgnoreRoute(config('codex-phpdoc.route_prefix'));
        $this->codexProjectConfig('codex-phpdoc.default_project_config');

        $this->addCustomDocument();

        return $app;
    }

    protected function addCustomDocument()
    {
        $this->codexHook('documents:constructed', function (Documents $documents)
        {
            $project = $documents->getProject();
            $documents->addCustomDocument($project->config('phpdoc.document_slug', 'phpdoc'), function (Documents $documents) use ($project)
            {
                $path = $project->refPath($project->config('phpdoc.path'));
                $pfs  = $project->getFiles();
                if ( !$pfs->exists($path) )
                {
                    throw CodexException::documentNotFound('phpdoc');
                }
                return [ 'path' => $path, 'binding' => 'codex.phpdoc.document' ];
            });
        });
    }


}