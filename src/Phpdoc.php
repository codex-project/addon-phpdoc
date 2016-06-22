<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Support\Extendable;
use Codex\Contracts\Codex;

class Phpdoc extends Extendable
{
    /**
     * Phpdoc constructor.
     *
     * @param \Codex\Contracts\Codex|\Codex\Codex $parent
     */
    public function __construct(Codex $parent)
    {
        $this->setCodex($parent);
        $this->setContainer($parent->getContainer());
    }


    public function addAssets()
    {
        $theme = $this->getCodex()->theme;
        if($theme->stylesheets()->has('phpdoc', false) === true){
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


}