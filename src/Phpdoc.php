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

use Codex\Contracts\Codex;
use Codex\Projects\Project;
use Codex\Support\Extendable;
use Illuminate\Contracts\Cache\Factory as Cache;

class Phpdoc extends Extendable
{
    /** @var \Illuminate\Contracts\Cache\Factory */
    protected $cache;


    /**
     * Phpdoc constructor.
     *
     * @param \Codex\Contracts\Codex|\Codex\Codex $parent
     * @param \Illuminate\Contracts\Cache\Factory $cache
     */
    public function __construct(Codex $parent, Cache $cache)
    {
        $this->setCodex($parent);
        $this->setContainer($parent->getContainer());
        $this->cache = $cache;
    }


    public function addAssets()
    {
        $theme = $this->getCodex()->theme;
        if ( $theme->stylesheets()->has('phpdoc', false) === true )
        {
            return;
        }
        $theme->addStylesheet('phpdoc', 'vendor/codex-phpdoc/styles/phpdoc.css');
        $theme->addJavascript('phpdoc', 'vendor/codex-phpdoc/scripts/phpdoc.js', [ 'codex' ]);
        $theme->addScript('phpdoc', <<<JS
$(function(){
    $.phpdoc.initLinks();
})
JS
        );
    }

    /**
     * getProjects method
     * @return Project[]
     */
    public function getProjects()
    {
        return $this->codex->projects->query()->filter(function (Project $project)
        {
            return $project->config('phpdoc.enabled', false) === true;
        });
    }


}