<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author    Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license   http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Addons\Annotations\Plugin;
use Codex\Addons\BasePlugin;
use Codex\Codex;
use Codex\Documents\Document;
use Codex\Documents\Documents;
use Codex\Exception\CodexException;
use Codex\Projects\Project;
use Codex\Projects\Projects;
use Codex\Projects\Ref;

/**
 * This is the class Plugin.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 * @Plugin("phpdoc")
 */
class PhpdocPlugin extends BasePlugin
{
    ## BasePlugin attributes

    public $project = 'codex-phpdoc.default_project_config';

    public $document = [];

    public $views = [
        'phpdoc.document' => 'codex-phpdoc::document',
        'phpdoc.entity'   => 'codex-phpdoc::entity',
    ];

    public $extend = [
        'codex'     => [ 'phpdoc' => Phpdoc::class ],
        'codex.ref' => [ 'phpdoc' => PhpdocRef::class ],
    ];

    ## ServiceProvider attributes

    protected $configFiles = [ 'codex-phpdoc' ];

    protected $viewDirs = [ 'views' => 'codex-phpdoc' ];

    protected $assetDirs = [ 'assets' => 'codex-phpdoc' ];

    protected $commands = [
        Console\ClearCacheCommand::class,
        Console\CreateCacheCommand::class,
    ];

    protected $bindings = [
        'codex.phpdoc.project'  => PhpdocRef::class,
        'codex.phpdoc.document' => PhpdocDocument::class,
    ];

    protected $shared = [ 'codex.phpdoc' => Phpdoc::class, ];

    public function boot()
    {
        parent::boot();

        $this->hook('controller:document', function ($controller, Document $document) {
            if($document->getPathName() !== $document->getProject()->config('phpdoc.document_slug')){
                return;
            }
            $this->codex()->theme
                ->addStylesheet('phpdoc', asset('/vendor/codex/styles/addon-phpdoc.css'), ['theme'])
                ->addJavascript('vue-resource', asset('/vendor/codex/vendor/vue-resource/vue-resource.js'), ['vue'])
                ->addJavascript('jstree', asset('/vendor/codex/vendor/jstree/jstree.js'), ['jquery'])
                ->addJavascript('phpdoc', asset('/vendor/codex/js/codex.phpdoc.js'), [ 'jstree', 'vue-resource', 'document' ])
                ->addScript('init', <<<EOT
Vue.codex.phpdoc.apiUrl = 'http://codex-project.dev/api/v1/';
var app = new codex.App({
    el: '#app',
    mounted(){
        this.closeSidebar();
    }
})
EOT
                );


        });
    }

    public function register()
    {
        $app = parent::register();

        if ( $app[ 'config' ]->get('codex.http.enabled', false) ) {
            $this->registerHttp();
        }

        // register link handler
        $app[ 'config' ]->set('codex.links.phpdoc', PhpdocLink::class . '@handle');

        // register custom document, this will handle showing the phpdoc
        $this->registerCustomDocument();

        Codex::registerExtension('codex.projects', 'getPhpdocProjects', function () {
            /** @var Projects $this */
            return $this->getItems()->filter(function (Project $project) {
                return $project->config('phpdoc.enabled', false) === true;
            });
        });


        return $app;
    }

    public function registerCustomDocument()
    {

        $this->hook('documents:constructed', function (Documents $documents) {
            /** @var \Codex\Contracts\Documents\Documents|\Codex\Documents\Documents $documents */
            $project = $documents->getProject();
            $documents->addCustomDocument($project->config('phpdoc.document_slug', 'phpdoc'), function (Documents $documents) use ($project) {
                $path = $documents->getRef()->path($project->config('phpdoc.path'));
                if ( !$documents->getFiles()->exists($path) ) {
                    throw CodexException::documentNotFound('phpdoc');
                }
                return [ 'path' => $path, 'binding' => 'codex.phpdoc.document' ];
            });
        });
    }

    protected function registerHttp()
    {
        $this->app->register(Http\HttpServiceProvider::class);
        $this->excludeRoute(config('codex-phpdoc.route_prefix'));
    }


}
