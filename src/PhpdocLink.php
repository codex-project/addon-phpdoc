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
    /** @var \Codex\Addon\Phpdoc\Phpdoc */
    protected $phpdoc;

    /** @var \Codex\Processors\Links\Action */
    protected $action;

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
        $this->action = $action;
        $ref       = $action->getRef();
        $el        = $action->getElement();

        // transform <a> attributes that wont matter modifiers
        $el->setAttribute('class', $el->getAttribute('class') . ' phpdoc-link');
        $el->setAttribute('href', $ref->phpdoc->url($action->param(0)));
        $el->setAttribute('target', '_blank');

        if ( $action->containsModifier('popover') ) {
            $this->popover();
        } elseif ( $action->containsModifier('modal') ) {
            $this->modal();
        }
    }

    protected function getComponent()
    {
        $action = $this->action;
        $name = 'entity';
        $params = '';
        if ( $action->containsModifier('signature') ) {
            $name = 'method-signature';
        }
        if ( $action->containsModifier('method-list') ) {
            $name = 'method-list';
        }
        if ( $action->containsModifier('property-list') ) {
            $name = 'property-list';
        }
        if ( $action->containsModifier('source') ) {
            $name = 'source';
        }
        if ( $action->containsModifier('app') ) {
            $name = 'app';
        }

        if ( stristr($action->param(0), '::') ) {
            $name = 'method';
        }

        $query = $this->getQuery();

        return "<pd-{$name} query=\"{$query}\" {$params}></pd-{$name}>";
    }

    protected function getQuery()
    {
        $query = str_ensure_left($this->action->param(0), '\\');
        return stristr($query, '::') ? str_ensure_right($query, '()') : $query;
    }

    protected function popover()
    {
        $action = $this->action;
        $el        = $action->getElement();
        $modifier  = $action->modifier('popover');
        $component = $this->getComponent();
        $trigger   = 'hover';
        if ( $modifier->hasParameters() ) {
            $trigger = $modifier->param(0);
        }

        $fd      = FluentDOM(<<<EOT
<c-popover placement="bottom" popover-class="popover-phpdoc" trigger="{$trigger}">
    {$el->saveXml()}
    <span slot="content">
        {$component}
    </span>
</c-popover>
EOT
        );
        $popover = $fd->find('//c-popover');
        $el->before($popover);
        $el->remove();
    }

    protected function modal()
    {
        $el = $this->action->getElement();
        $el->setAttribute('href', 'javascript:;');
        $component = $this->getComponent();
        $size = ':large="true"';
        if($this->action->containsModifier('modal-full')){
            $size = ':full="true"';
        }
        $fd      = FluentDOM(<<<EOT
<c-modal 
:cancel="true"  
cancel-text="Close" 
cancel-class="btn btn-primary"
modal-class="modal-phpdoc" 
{$size}>
    {$el->saveXml()}
    <span slot="content">
        {$component}
    </span>
</c-modal>
EOT
            , 'text/html');
        $popover = $fd->find('//c-modal');
        $el->before($popover);
        $el->remove();
    }


}
