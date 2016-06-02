<?php
/**
 * asdfasdfasdfa
 * asfdsadf
 */
namespace Codex\Addon\Phpdoc;


use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addons\Annotations\Filter;
use Codex\Contracts\Codex;
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
    protected $codex;

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
    public function __construct(Codex $codex, Factory $phpdoc)
    {
        $this->factory = $phpdoc;
        $this->codex   = $codex;
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
        $this->replaceLinks();
        $this->replaceClassPopoverLinks();
        $this->replaceMethodPopoverLinks();
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

    protected function replaceMethodPopoverLinks()
    {
        $matches = $this->matches('/"#phpdoc:popover:(.*?)::(.*?)"/');
        foreach ( $matches[ 0 ] as $i => $match ) {
            $class  = $matches[ 1 ][ $i ];
            $method = $matches[ 2 ][ $i ];
            $data   = $this->elements->where('full_name', $fullName = Str::ensureLeft($class, '\\'))->first();
            if ( !$data ) {
                continue;
            }
            $data = new Collection($data[ 'methods' ]);
            $data = $data->where('name', $method)->first();
            if ( !$data ) {
                continue;
            }
            $content = view('codex-phpdoc::partials.method', [ 'method' => $data, 'phpdoc' => $this->phpdoc ])->render();
            $content .= <<<HTML
<div class="popover-phpdoc-description">
{$data['description']}
</div>
HTML;

            $content = str_replace('"', '\'', $content);
            $this->replace($match, "{$class}", 'phpdoc-popover-link', [
                'data-content' => $content,
            ]);
        }
    }

    protected function replaceClassPopoverLinks()
    {
        $matches = $this->matches('/"#phpdoc:popover:(.*?)"/');
        foreach ( $matches[ 0 ] as $i => $match ) {
            $class = $matches[ 1 ][ $i ];
            /** @var Element $class */
            $data = $this->elements->where('full_name', $fullName = Str::ensureLeft($class, '\\'))->first();
            if ( !$data ) {
                continue;
            }

            $title = view('codex-phpdoc::partials.type', [ 'type' => $fullName, 'phpdoc' => $this->phpdoc, 'typeFullName' => true ])->render();
            $title = str_replace('"', '\'', $title);

            $extends = '';
            if ( strlen($data[ 'extends' ]) > 0 ) {
                $extends = '<small class="pl-xs fs-13">extends</small>' . view('codex-phpdoc::partials.type', [ 'type' => $extends, 'class' => 'fs-13' ])->render();
            }

            $content = <<<HTML
{$extends}
<div class="popover-phpdoc-description">
{$data['description']}
</div>
HTML;

            $content = str_replace('"', '\'', $content);
            $this->replace($match, $title, 'phpdoc-popover-link', [
                'data-content' => $content,
            ]);
        }
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
        $attrs = array_merge([ 'class' => $cssClass, 'data-title' => $class ], $attrs);
        $attr  = '';
        foreach ( $attrs as $k => $v ) {
            $attr .= " {$k}=\"{$v}\"";
        }
        $this->content = str_replace($match, "\"{$this->url}#!/{$class}\"{$attr}", $this->content);
    }

    protected function addAssets()
    {
    }
}