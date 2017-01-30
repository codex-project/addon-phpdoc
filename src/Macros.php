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

class Macros
{

    public function methodSignature($isCloser = false, $query)
    {
//        return $isCloser ? '</div>' : '<div style="display:none">';
        return "<pd-method-signature query=\"{$query}\"></pd-method-signature>";

    }

    public function method($isCloser = false, $query)
    {
        return "<pd-method query=\"{$query}\"></pd-method>";
    }

    public function listMethod($isCloser = false, $query)
    {
        return "<pd-method-list query=\"{$query}\"></pd-method-list>";
    }

    public function listProperty($isCloser = false, $query)
    {
        return "<pd-property-list query=\"{$query}\"></pd-property-list>";
    }

    public function entity($isCloser = false, $query)
    {
        return "<pd-entity query=\"{$query}\"></pd-entity>";
    }
}