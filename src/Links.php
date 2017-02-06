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

class Links
{
    /** @var \Codex\Processors\Links\Action */
    protected $action;

    /** @var string */
    protected $query;

    /**
     * PhpdocLink constructor.
     *
     * @param \Codex\Addon\Phpdoc\Phpdoc $phpdoc
     */
    public function __construct()
    {
    }


    // link action: 'phpdoc' => 'Codex\Addon\Phpdoc\PhpdocLink@handle'
    public function handle(Action $action)
    {
        $this->action = $action;
        $ref          = $action->getRef();
        $el           = $action->getElement();

        $this->query = str_ensure_left($action->param(0), '\\');

        // transform <a> attributes that wont matter modifiers
        $el->setAttribute('class', $el->getAttribute('class') . ' phpdoc-link');
        $el->setAttribute('href', $ref->phpdoc->url($action->param(0)));


        if ( $action->containsModifier('popover') ) {
            $this->popover();
        } elseif ( $action->containsModifier('modal') ) {
            $this->modal();
        } elseif ( $action->containsModifier('type') ) {
            $this->type();
        }
    }

    protected function getComponent()
    {
        $action = $this->action;
        $name   = 'entity';
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

        if ( false !== strpos($this->query, '::') ) {
            $name = 'method';
        }

        $query = $this->getQuery();

        return "<pd-{$name} query=\"{$query}\" {$params}></pd-{$name}>";
    }

    protected function getQuery()
    {
        $query = str_ensure_left($this->action->param(0), '\\');
        return strstr($query, '::') ? str_ensure_right($query, '()') : $query;
    }

    protected function popover()
    {
        $action    = $this->action;
        $modifier  = $action->modifier('popover');
        $component = $this->getComponent();
        $trigger   = 'hover';
        if ( $modifier->hasParameters() ) {
            $trigger = $modifier->param(0);
        }


        $this->replaceElementWithComponent('c-popover', <<<EOT
<c-popover placement="bottom" popover-class="popover-phpdoc" trigger="{$trigger}">
    {$this->getElementHtml()}
    <span slot="content">
        {$component}
    </span>
</c-popover>
EOT
        );
    }

    protected function getElementHtml()
    {
        $action = $this->action;
        $elHtml = $action->getElement()->saveHtml();
        // <a> target
        $target = '_blank';
        if ( $action->containsModifier('target') && $action->modifier('target')->hasParameters() ) {
            $target = $action->modifier('target')->param(0);
        }

        if($action->hasModifier('type')){
            $type = $action->modifier('type');
            $params = '';
            if($type->hasParameters()){
                if($type->param(0) ===false){
                    $params .= ' no-tooltip';
                }
            }
            return "<pd-type type='{$this->query}' target='{$target}' {$params}></pd-type>";
        }
        return $elHtml;
    }

    protected function modal()
    {
        $el = $this->action->getElement();
        $el->setAttribute('href', 'javascript:;');
        $component = $this->getComponent();
        $size      = ':large="true"';
        if ( $this->action->containsModifier('modal-full') ) {
            $size = ':full="true"';
        }

        $this->replaceElementWithComponent('c-modal', <<<EOT
<c-modal 
:cancel="true"  
cancel-text="Close" 
cancel-class="btn btn-primary"
modal-class="modal-phpdoc" 
{$size}>
    {$this->getElementHtml()}
    <span slot="content">
        {$component}
    </span>
</c-modal>
EOT
        );
    }

    protected function type()
    {
        $html     = "<pd-type type='{$this->query}'></pd-type>";
        $modifier = $this->action->modifier('type');
        if ( $modifier->hasParameters() ) {
        }
        $this->replaceElementWithComponent('pd-type', $html);
    }


    protected function replaceElementWithComponent($name, $html)
    {
        $el = $this->action->getElement();
        $fd = FluentDOM($html, 'text/html');
        $el->before($fd->find("//{$name}"));
        $el->remove();
    }

}
