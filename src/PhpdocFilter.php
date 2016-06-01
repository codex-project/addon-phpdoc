<?php
/**
 * asdfasdfasdfa
 * asfdsadf
 */
namespace Codex\Addon\Phpdoc;


use Codex\Core\Addons\Annotations\Filter;
use Codex\Core\Contracts\Codex;
use Codex\Core\Documents\Document;

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
    /** @var \Codex\Core\Codex */
    protected $codex;

    /** @var \Codex\Core\Support\Collection */
    public $config = [];

    /** @var \Codex\Addon\Phpdoc\Factory  */
    protected $phpdoc;

    /** @var string */
    protected $url;

    /** @var string */
    protected $content;


    /**
     * PhpdocFilter constructor.
     *
     * @param \Codex\Core\Contracts\Codex $codex
     * @param \Codex\Addon\Phpdoc\Factory $phpdoc
     */
    public function __construct(Codex $codex, Factory $phpdoc)
    {
        $this->phpdoc = $phpdoc;
        $this->codex = $codex;
    }

    public function handle(Document $document)
    {
        if($document->getProject()->config('phpdoc.enabled', false) !== true){
            return;
        }
        $this->addAssets();
        $this->content = $document->getContent();
        $pathName = $document->getProject()->config('phpdoc.document_slug', 'phpdoc');
        $this->url = $document->getProject()->url($pathName, $document->getProject()->getRef());
        $this->replaceLinks();
        $this->replaceTooltipLinks();

        $document->setContent($this->content);
    }

    protected function replaceLinks()
    {
        $matches = $this->matches('/"#phpdoc:((?!tooltip).*?)"/');
        foreach($matches[0] as $i => $match){
            $this->replace($match, $matches[1][$i], 'phpdoc-link');
        }
    }
    protected function replaceTooltipLinks()
    {
        $matches = $this->matches('/"#phpdoc:tooltip:(.*?)"/');
        foreach($matches[0] as $i => $match){
            $this->replace($match, $matches[1][$i], 'phpdoc-tooltip-link');
        }
    }

    protected function matches($pattern)
    {
        $matches = [];
        preg_match_all($pattern, $this->content, $matches);
        return $matches;
    }

    protected function replace($match, $class, $cssClass = '')
    {
        $this->content = str_replace($match, "\"{$this->url}#!/{$class}\" class=\"{$cssClass}\"", $this->content);
    }

    protected function addAssets()
    {

    }
}