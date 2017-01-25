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

use Codex\Processors\Links\Action;
use Codex\Support\Collection;
use FluentDOM\Element;

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

    /** @var Phpdoc */
    protected $phpdoc;

    /**
     * PhpdocLink constructor.
     *
     * @param \Codex\Addon\Phpdoc\Phpdoc $phpdoc
     */
    public function __construct(\Codex\Addon\Phpdoc\Phpdoc $phpdoc)
    {
        $this->phpdoc = $phpdoc;
    }


    // link action: 'phpdoc' => 'Codex\Addon\Phpdoc\PhpdocLink@handle'
    public function handle(Action $action)
    {
        $this->phpdoc->addAssets();
        $ref       = $action->getRef();
        $project   = $action->getProject();
        $pathName  = $project->config('phpdoc.document_slug', 'phpdoc');
        $this->url = $project->url($pathName, $ref);
        $el        = $action->getElement();
        $params    = count($action->getParameters());
        if ( $action->param(0) === 'popover' ) {
            $componentName = $params === 3 ? 'method' : 'entity';
            $query      = str_ensure_left($action->param(1), '\\');

            if ( $params === 3 ) {
                $query .= '::' . $action->param(2) . '()';
            }

            $fd      = FluentDOM(<<<EOT
<c-popover placement="bottom" popover-class="phpdoc-popover" trigger="hover">
    {$el->saveXml()}
    <span slot="content">
        <pd-{$componentName} query="{$query}"></pd-{$componentName}>
    </span>
</c-popover>
EOT
            );

            $popover = $fd->find('//c-popover');
            $el->before($popover);
            //$popover->prependTo('//span', $el);
            $el->remove();

//            $popover = Popover::make($action->getRef())->generate($action->param(1), $action->param(2));
            $el->setAttribute('class', $el->getAttribute('class') . ' phpdoc-popover-link');
            $el->setAttribute('href', $ref->phpdoc->url($action->param(1)));
            $el->setAttribute('target', '_blank');
            $el->setAttribute('data-title', $popover[ 'title' ]);
            $el->setAttribute('data-content', $popover[ 'content' ]);
        } else {
            $el->setAttribute('class', $el->getAttribute('class') . ' phpdoc-link');
            $el->setAttribute('href', $ref->phpdoc->url($action->param(0)));
            $el->setAttribute('target', '_blank');
            $el->setAttribute('data-title', $action->param(0));
        }
    }


}
