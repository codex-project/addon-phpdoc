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
namespace Codex\Addon\Phpdoc\Structure;

use Codex\Support\Collection;

/**
 * This is the class Entity.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 *
 * @property boolean    $final
 * @property boolean    $abstract
 * @property string     $namespace
 * @property string     $full_name
 * @property string     $name
 * @property string     $extends
 * @property array      $implements
 * @property Method[]   $methods
 * @property Constant[] $constants
 * @property Property[] $properties
 * @property Tag[]      $tags
 *
 */
class Entity extends AbstractStructure
{

    protected function transform($data)
    {
        $items = [
            'final'            => $this->boolValue($data[ '@attributes.final' ]),
            'abstract'         => $this->boolValue($data[ '@attributes.abstract' ]),
            'namespace'        => $data[ '@attributes.namespace' ],
            'description'      => $this->createString($data[ 'docblock.description' ]),
            'long-description' => $this->createString($data[ 'docblock.long-description' ]),
        ];
        $this->copy([ 'extends', 'implements', 'name', 'full_name' ], $data, $items);


        // hash, file_Description, file_tags, extends, line,

        $this->items = $items;
        $methods     = $this->structures('method', Method::class, $data);
        $constants   = $this->structures('constant', Constant::class, $data);
        $properties  = $this->structures('property', Property::class, $data);
        $tags        = $this->structures('docblock.tag', Tag::class, $data);

        $items = array_merge($items, compact('methods', 'constants', 'properties', 'tags'));

        return $items;
    }

    public function mergeFile()
    {
    }


    public static function getTypes()
    {
        return [ 'class', 'trait', 'interface' ];
    }


    public function getType()
    {
        return $this[ 'type' ];
    }

    public function getUsedNamespaces()
    {
        return $this[ 'namespace-alias' ];
    }

    /** @return Collection|Method[] */
    public function getMethods()
    {
        return $this[ 'methods' ];
    }

    /** @return Collection|Constant[] */
    public function getConstants()
    {
        return $this[ 'contants' ];
    }

    /** @return Collection|Property[] */
    public function getProperties()
    {
        return $this[ 'properties' ];
    }

    /** @return Collection */
    public function getTags()
    {
        return $this[ 'tags' ];
    }

    #protected $children = ['methods', 'contants', 'properpties', 'tags'];

    public function unserialize($serialized)
    {
        parent::unserialize($serialized);
        foreach ( $this->getMethods() as $item ) {
            $item->setBelongsTo($this);
        }
//        foreach ( $this->getConstants() as $item ) {
//            $item->setBelongsTo($this);
//        }
        foreach ( $this->getProperties() as $item ) {
            $item->setBelongsTo($this);
        }
        foreach ( $this->getTags() as $item ) {
            $item->setBelongsTo($this);
        }
        return;
        foreach ( [ $this->getMethods(), $this->getProperties(), $this->getConstants(), $this->getTags() ] as $collection ) {
            /** @var Collection|AbstractStructure[] $collection */
            foreach ( $collection as $item ) {
                $item->setBelongsTo($this);
            }
        }
    }
}
