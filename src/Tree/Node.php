<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author    Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license   http://codex-project.ninja/license The MIT License
 */
namespace Codex\Addon\Phpdoc\Tree;

class Node extends \Tree\Node\Node
{
    const TYPE_NAMESPACE = 'namespace';
    const TYPE_CLASS = 'class';
    const TYPE_TRAIT = 'trait';
    const TYPE_INTERFACE = 'interface';

    protected $type;

    protected $name;

    public function __construct($value, $type)
    {
        parent::__construct($value, []);
        $this->type = $type;
        if($type === self::TYPE_NAMESPACE){
            $this->name = $value;
        } else {
            $this->name = $value['name'];
        }
    }


    /**
     * hasChildren
     *
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->getChildren()) > 0;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }
}