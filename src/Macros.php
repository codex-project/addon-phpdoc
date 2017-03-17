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

use Codex\Addons\Annotations as CA;

class Macros
{

    /**
     * methodSignature method
     *
     * @CA\Macro(name="method:signature")
     * @param bool $isCloser
     * @param      $query
     *
     * @return string
     */
    public function methodSignature($isCloser = false, $query)
    {
//        return $isCloser ? '</div>' : '<div style="display:none">';
        return "<pd-method-signature query=\"{$query}\"></pd-method-signature>";
    }

    public function method($isCloser = false, $query, $hide = '')
    {
        $hide = $this->toJsonArray($hide);
        return "<pd-method class=\"boxed\" query=\"{$query}\" :hide='{$hide}'></pd-method>";
    }

    public function listMethod($isCloser = false, $query)
    {
        return "<pd-method-list query=\"{$query}\" no-click></pd-method-list>";
    }

    public function listProperty($isCloser = false, $query, $exclude = '', $only = '')
    {
        $exclude = $this->toJsonArray($exclude); // === '' ? '[]' : json_encode(array_map('trim', explode(',', $exclude)));
        $only    = $this->toJsonArray($only); // === '' ? '[]' : json_encode(array_map('trim', explode(',', $only)));
        return "<pd-property-list query=\"{$query}\" :exclude='{$exclude}' :only='{$only}'></pd-property-list>";
    }

    public function entity($isCloser = false, $query, $modifiers = '')
    {
        return "<pd-entity query=\"{$query}\" {$modifiers}></pd-entity>";
    }

    protected function toJsonArray($string, $delimiter = ',', $empty = '[]')
    {
        return $string === '' ? $empty : json_encode(array_map('trim', explode($delimiter, $string)));
    }
}
