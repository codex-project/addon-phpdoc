<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc;

use Codex\Support\Extendable;
use Codex\Contracts\Codex;

class Phpdoc extends Extendable
{
    /**
     * Phpdoc constructor.
     *
     * @param \Codex\Contracts\Codex|\Codex\Codex $parent
     */
    public function __construct(Codex $parent)
    {
        $this->setCodex($parent);
        $this->setContainer($parent->getContainer());
    }



}