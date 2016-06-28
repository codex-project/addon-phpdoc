<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Addon\Phpdoc;

use Codex\Documents\Document;
use Codex\Projects\Project;
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the PhpdocDocument.
 *
 * @package        Codex\Hooks
 * @author         Caffeinated Dev Team
 * @copyright      Copyright (c) 2015, Caffeinated
 * @license        https://tldrlegal.com/license/mit-license MIT License
 */
class PhpdocDocument extends Document
{

    protected $phpdoc;

    public function __construct($codex, Project $project, Repository $cache, $path, $pathName)
    {
        $pathName = 'phpdoc';
        config()->set('debugbar.enabled', false);
        app()->bound('debugbar') && app('debugbar')->disable();
        parent::__construct($codex, $project, $cache, $path, $pathName);
        $this->mergeAttributes($project->config('phpdoc'));
        $codex->theme->addJavascript('phpdoc', 'vendor/codex-phpdoc/scripts/phpdoc', [ 'codex' ]);
        $codex->theme->addStylesheet('phpdoc', 'vendor/codex-phpdoc/styles/phpdoc');
        $codex->theme->addBodyClass('sidebar-closed content-compact');
        $codex->theme->addScript('phpdoc', <<<JS
codex.phpdoc.init('#codex-phpdoc', {
    project: '{$project->getName()}',
    ref: '{$project->getRef()}',
    defaultClass: '{$project->config('phpdoc.default_class', null)}'
});
JS
        );
        
    }

    public function render()
    {

        $this->hookPoint('document:render');
        $this->runProcessor('prismjs');
        $content = '<div id="codex-phpdoc"></div>';
        $this->hookPoint('document:rendered');
        return $content;
    }


    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->getProject()->getSidebarMenu()->getBreadcrumbToHref($this->url());
    }

    public function getLastModified()
    {
        return parent::getLastModified();
    }


}
