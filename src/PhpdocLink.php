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

use Codex\Processors\Links\Action;
use Codex\Support\Collection;

class PhpdocLink
{
    /** @var Collection */
    protected $elements;

    /** @var string */
    protected $url;

    /** @var string */
    protected $class;

    /** @var Action */
    protected $action;

    /** @var bool */
    protected $hasMethod;

    /** @var string|null */
    protected $method;

    // link action: 'phpdoc' => 'Codex\Addon\Phpdoc\PhpdocLink@handle'
    public function handle(Action $action)
    {
        return;
        $project = $action->getProcessor()->project;
        if ( $project->hasEnabledAddon('phpdoc') !== true )
        {
            return;
        }
        $this->elements = new Collection($project->phpdoc->getElements()->toArray());
        $pathName       = $project->config('phpdoc.document_slug', 'phpdoc');
        $this->url      = $project->url($pathName, $project->getRef());
        $this->class     = urldecode($action->param(0));
        $this->action    = $action;
        $this->hasMethod = $action->hasParameter(1);
        $this->method    = $action->param(1);

        $theme = $project->getCodex()->theme;
        $theme->addStylesheet('phpdoc', 'vendor/codex-phpdoc/styles/phpdoc.css');
        $theme->addJavascript('phpdoc', 'vendor/codex-phpdoc/scripts/phpdoc.js', [ 'codex' ]);
        $theme->addScript('phpdoc', <<<JS
$(function(){
    $.phpdoc.initLinks();
})
JS
        );
        $a = 'a';
    }


    protected function replaceLinks()
    {
        $matches = $this->matches('/"#phpdoc:((?!popover).*?)"/');
        foreach ( $matches[ 0 ] as $i => $match )
        {
            $this->replace($match, $matches[ 1 ][ $i ], 'phpdoc-link');
        }
    }

    protected function replaceAttributedLinks()
    {
        $matches = $this->matches('/"#phpdoc:(.*?)(?:::|)(.*?)"/');
        foreach ( $matches[ 0 ] as $i => $match )
        {
            $full   = $matches[ 2 ][ $i ];
            $split  = explode('::', $full);
            $method = isset($split[ 1 ]) ? array_pop($split) : null;
            $attrs  = explode(':', $split[ 0 ]);
            $class  = array_pop($attrs);
            if ( count($attrs) > 0 )
            {
                $call = 'handle' . ucfirst(array_shift($attrs));
                call_user_func_array([ $this, $call ], array_merge([ $match, $class, $method ], $attrs));
            }
            $a = 'a';
        }
    }

    protected function handlePopover($match, $class, $method = null)
    {
        $popover = Popover::make($this->project)->generate($class, $method);
        $this->replace($match, $class, 'phpdoc-popover-link', [
            'data-title'   => $popover[ 'title' ],
            'data-content' => $popover[ 'content' ],
        ]);
    }


    protected function matches($pattern)
    {
        $matches = [ ];
        preg_match_all($pattern, $this->content, $matches);
        return $matches;
    }

    protected function replace($match, $class, $cssClass = '', array $attrs = [ ])
    {
        $cssClass = $this->config->get('link_attributes.class', '') . ' ' . $cssClass;
        $this->config->has('link_attributes.class') && $this->config->forget('link_attributes.class');
        $attrs = array_merge($this->config->get('link_attributes', [ ])->toArray(), [ 'class' => $cssClass, 'data-title' => $class ], $attrs);
        $attr  = '';
        foreach ( $attrs as $k => $v )
        {
            $attr .= " {$k}=\"{$v}\"";
        }
        $this->content = str_replace($match, "\"{$this->project->phpdoc->url($class)}\" {$attr}", $this->content);
    }

}