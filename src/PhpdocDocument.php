<?php
/**
 * Part of the Caffeinated PHP packages.
 *
 * MIT License and copyright information bundled with this package in the LICENSE file
 */
namespace Codex\Addon\Phpdoc;

use Codex\Documents\Document;
use Codex\Projects\Project;

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

    public function __construct($codex, Project $project, $path, $pathName)
    {
        $pathName = 'phpdoc';
        parent::__construct($codex, $project, $path, $pathName);
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
        return '<div id="codex-phpdoc"></div>';
    }


    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->getProject()->getSidebarMenu()->getBreadcrumbToHref($this->url());
    }

}
