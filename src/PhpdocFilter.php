<?php
/**
 * asdfasdfasdfa
 * asfdsadf
 */
namespace Codex\Addon\Phpdoc;


use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addons\Annotations\Filter;
use Codex\Documents\Document;
use Codex\Support\Collection;
use Sebwite\Support\Str;

/**
 * This is the class PhpdocFilter.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 *
 * @Filter("phpdoc", config="config", priority=150)
 */
class PhpdocFilter
{
    /** @var \Codex\Codex */
    public $codex;

    /** @var \Codex\Support\Collection */
    public $config = [
        'tooltips'        => true,
        'classes'         => true,
        'methods'         => true,
        'link_attributes' => [
            'target' => '_blank',
        ],
    ];

    /** @var \Codex\Addon\Phpdoc\Factory */
    protected $factory;

    /** @var string */
    protected $url;

    /** @var string */
    protected $content;

    /** @var \Codex\Support\Collection */
    protected $elements;

    /** @var  ProjectPhpdoc */
    protected $phpdoc;

    /**
     * PhpdocFilter constructor.
     *
     * @param \Codex\Contracts\Codex      $codex
     * @param \Codex\Addon\Phpdoc\Factory $phpdoc
     */
    public function __construct(Factory $phpdoc)
    {
        $this->factory = $phpdoc;
    }

    public function handle(Document $document)
    {
        if ( $document->getProject()->config('phpdoc.enabled', false) !== true ) {
            return;
        }
        $this->phpdoc   = $this->factory->make($document->getProject());
        $this->elements = new Collection($this->phpdoc->getElements(true)->toArray());
        $this->content  = $document->getContent();
        $pathName       = $document->getProject()->config('phpdoc.document_slug', 'phpdoc');
        $this->url      = $document->getProject()->url($pathName, $document->getProject()->getRef());

        $this->replaceAttributedLinks();
        $this->replaceLinks();

        $theme = $this->codex->theme;
        $theme->addStylesheet('phpdoc', 'vendor/codex-phpdoc/styles/phpdoc.css');
        $theme->addJavascript('phpdoc', 'vendor/codex-phpdoc/scripts/phpdoc.js', [ 'codex' ]);
        $theme->addScript('phpdoc', <<<JS
$(function(){
    $.phpdoc.initLinks();
})
JS
        );
        $document->setContent($this->content);
    }

    protected function replaceLinks()
    {
        $matches = $this->matches('/"#phpdoc:((?!popover).*?)"/');
        foreach ( $matches[ 0 ] as $i => $match ) {
            $this->replace($match, $matches[ 1 ][ $i ], 'phpdoc-link');
        }
    }

    protected function replaceAttributedLinks()
    {
        $matches = $this->matches('/"#phpdoc:(.*?)(?:::|)(.*?)"/');
        foreach ( $matches[ 0 ] as $i => $match ) {
            $full   = $matches[ 2 ][ $i ];
            $split  = explode('::', $full);
            $method = isset($split[ 1 ]) ? array_pop($split) : null;
            $attrs  = explode(':', $split[ 0 ]);
            $class  = array_pop($attrs);
            if ( count($attrs) > 0 ) {
                $call = 'handle' . ucfirst(array_shift($attrs));
                call_user_func_array([ $this, $call ], array_merge([ $match, $class, $method ], $attrs));
            }
            $a = 'a';
        }
    }

    protected function handlePopover($match, $class, $method = null)
    {
        $popover = Popover::make($this->phpdoc)->generate($class, $method);
        $this->replace($match, $class, 'phpdoc-popover-link', [
            'data-title'   => $popover['title'],
            'data-content' => $popover['content'],
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
        foreach ( $attrs as $k => $v ) {
            $attr .= " {$k}=\"{$v}\"";
        }
        $this->content = str_replace($match, "\"{$this->phpdoc->url($class)}\" {$attr}", $this->content);
    }

    protected function addAssets()
    {
    }
}