<?php
namespace Codex\Addon\Phpdoc\Elements;

use Codex\Support\Collection;

/**
 * This is the class Element.
 *
 * @package        App\Phpdoc
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 * @property Element $attributes
 * @property Element $docblock
 * @property Element $class
 * @property Element $trait
 * @property Element $interface
 * @property Element $parse_markers
 * @property string  $source
 */
class Element extends PhpdocXmlElement
{

    public function getType()
    {
        return (string)$this->type;
    }

    /** @return Element */
    public function obj()
    {
        $type = $this->getType();
        return $this->{$type};
    }

    public function getClassTags()
    {
        return Helper::docBlockTags($this->obj()->docblock);
    }

    public function getTags()
    {
        return Helper::docBlockTags($this->docblock);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {


        $arr = [
            'hash'             => $this->attr('hash'),
            'file_description' => (string)$this->docblock->description,
            'file_tags'        => $this->getTags(),
            'final'            => $this->obj()->attr('final') === 'true',
            'abstract'         => $this->obj()->attr('abstract') === 'true',
            'namespace'        => $this->obj()->attr('namespace'),
            'extends'          => (string)$this->obj()->extends,
            'implements'       => (array)$this->obj()->implements,
            'description'      => (string)$this->obj()->docblock->description,
            'long-description' => (string)$this->obj()->docblock[ 'long-description' ],
            'line'             => (int)$this->obj()->attr('line'),
            'name'             => (string)$this->obj()->name,
            'full_name'        => (string)$this->obj()->full_name,
            'type'             => $this->getType(),
            'markers'          => get_object_vars($this->parse_markers),
            'source'           => $this->getSourceCode(),
            'tags'             => $this->getClassTags(),
            'properties'       => $this->getProperties()->toArray(),
            'methods'          => $this->getMethods()->toArray(),
        ];

        # $arr = get_object_vars($this->class)['name'];
        $a = $this->class->property;

        return $arr;
    }

    public function getProperties()
    {
        $properties = [ ];
        foreach ( $this->obj()->property as $p ) {
            $properties [] = Property::create($p->asXML());
        }
        return collect($properties);
    }

    public function getMethods()
    {
        $methods = [ ];
        foreach ( $this->obj()->method as $p ) {
            $methods [] = Method::create($p->asXML());
        }
        return collect($methods);
    }

    /**
     * getOwnMethods method
     * @return Collection|Method[]
     */
    public function getOwnMethods()
    {
        return $this->getMethods()->filter(function(Method $method){
            return $method->getClassFullName() === (string) $this->obj()->full_name;
        });
    }

    /** @return Collection|Method[] */
    public function getInheritedMethods()
    {
        return $this->getMethods()->filter(function(Method $method){
            return $method->getClassFullName() !== (string) $this->obj()->full_name;
        });
    }

    /** @return Collection|Property[] */
    public function getOwnProperties()
    {
        return $this->getProperties()->filter(function(Property $property){
            return $property->getClassFullName() === (string) $this->obj()->full_name;
        });
    }

    /** @return Collection|Property[] */
    public function getInheritedProperties()
    {
        return $this->getProperties()->filter(function(Property $property){
            return $property->getClassFullName() !== (string) $this->obj()->full_name;
        });
    }

    public function isExtending()
    {
        return $this->obj()->extends !== '';
    }

    public function getExtendsElement()
    {
        return $this->extends;
    }

    /**
     * collect method
     * @return Collection
     */
    public function collect()
    {
        return Collection::make($this->toArray());
    }
}
