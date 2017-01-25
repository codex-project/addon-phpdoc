<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2017 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Documents\Document;
use Codex\Projects\Project;
use Codex\Projects\Ref;
use Illuminate\Contracts\Cache\Repository;

/**
 * This is the class PhpdocDocument.
 *
 * @author Robin Radic
 */
class PhpdocDocument extends Document
{

    protected $phpdoc;

    public function __construct($codex, Project $project, Ref $ref, Repository $cache, $path, $pathName)
    {
        parent::__construct($codex, $project, $ref, $cache, $path, 'phpdoc');
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
        $this->getCodex()->theme->set('phpdoc.entities', $this->getRef()->phpdoc->getEntities()->toArray());
        $this->getCodex()->theme->set('phpdoc.tree', $this->getRef()->phpdoc->tree());
        $content = $this->getPhpdocContent($this->attr('default_class'));
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

    public function getPhpdocContent($query)
    {
        return "<pd-app query='{$query}'></pd-app>";
    }

}
