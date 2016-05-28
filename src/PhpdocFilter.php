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
 * @Filter("phpdoc", config="config")
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
    }
}