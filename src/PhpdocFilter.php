<?php
/**
 * asdfasdfasdfa
 * asfdsadf
 */
namespace Codex\Addon\Phpdoc;


use Codex\Core\Addons\Annotations\Filter;
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
    public $config = [];

    protected $phpdoc;

    /**
     * PhpdocFilter constructor.
     *
     * @param $phpdoc
     */
    public function __construct(Factory $phpdoc)
    {
        $this->phpdoc = $phpdoc;

    }

    public function handle(Document $document)
    {
        if($document->getProject()->config('phpdoc.enabled', false) !== true){
            return;
        }
        $pathName = $document->getProject()->config('phpdoc.document_slug', 'phpdoc');
        $url = $document->getProject()->url($pathName, $document->getProject()->getRef());
        $content = $document->getContent();

        $matches = [];
        preg_match_all('/"#phpdoc:(.*?)"/', $content, $matches);
        foreach($matches[0] as $i => $match){
            $class = $matches[1][$i];
            $content = str_replace($match, "{$url}#!/{$class}", $content);
        }

        $document->setContent($content);
    }
}