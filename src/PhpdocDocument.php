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

use Codex\Documents\Document;
use Codex\Projects\Project;
use Codex\Projects\Ref;
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

    public function __construct($codex, Project $project, Ref $ref, Repository $cache, $path, $pathName)
    {
        $pathName = 'phpdoc';
        parent::__construct($codex, $project, $ref, $cache, $path, $pathName);
        $this->mergeAttributes($project->config('phpdoc'));
    }

    public function render()
    {

        $this->hookPoint('document:render');
        $prismPlugins = array_replace($this->attr('processors.prismjs.plugins', [ ]), [
            'line-numbers',
            'autolinker',
        ]);
        $this->setAttribute('processors.prismjs.plugins', $prismPlugins);
        $this->runProcessor('prismjs');
        #$content = "<phpdoc project='{$this->project->getName()}' ref='{$this->project->getRef()}' full-name='{$this->p'></phpdoc>";
        $content = '<c-phpdoc project-name="codex" project-ref="master" full-name="Codex\Codex"></c-phpdoc>';
        $this->hookPoint('document:rendered');
        return $content;
    }


    /**
     * getBreadcrumb
     */
    public function getBreadcrumb()
    {
        return $this->getCodex()->menus->get('sidebar')->getBreadcrumbToHref($this->url());
    }

    public function getLastModified()
    {
        return parent::getLastModified();
    }


}
