<?php
namespace Codex\Addon\Phpdoc\Elements;

use ArrayAccess;
use DOMDocument;
use DOMXPath;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use SimpleXMLElement;

/**
 * This is the class AbstractXmlElement.
 *
 * @package        Codex\Addon
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 * @property string           name
 * @property string           full_name
 * @property string           extends
 * @property string[]         implements
 * @property boolean          default
 * @property PhpdocXmlElement docblock
 * @property PhpdocXmlElement docblock
 *
 *
 */
class PhpdocXmlElement extends SimpleXMLElement implements Arrayable, ArrayAccess, Jsonable
{

    /**
     * create method
     *
     * @param string|SimpleXMLElement $data
     * @param int                     $options
     *
     * @return static
     */
    public static function create($data, $options = 0)
    {
        $class = new static((string)$data, $options);

        if ( method_exists($class, 'init') ) {
            call_user_func([ $class, 'init' ]);
        }

        return $class;
    }

    public function toArray()
    {
        return [];
    }

    /**
     * getDoc method
     * @return \DOMDocument
     */
    public function getDom()
    {
        $doc = new DOMDocument;
        $doc->loadXML($this->asXML());
        return $doc;
    }

    /**
     * getDomXpath method
     *
     * @param \DOMDocument|null $doc Optional DOMDocument. If not given, it will use the internal one using $this->getDoc()
     *
     * @return \DOMXPath
     */
    public function getDomXpath($doc = null)
    {
        return new DOMXPath($doc === null ? $this->getDom() : $doc);
    }

    public static function getTypes()
    {
        return ['class', 'trait', 'interface'];
    }

    public function getAttributes()
    {
        $attrs = [ ];
        foreach ( $this->attributes() as $k => $v ) {
            $attrs[ $k ] = (string)$v;
        }

        return $attrs;
    }

    public function getAttribute($name, $default = null)
    {
        return array_get($this->getAttributes(), $name, $default);
    }

    public function attr($name, $default = null)
    {
        return $this->getAttribute($name, $default);
    }

    public function getSourceCode()
    {
        return gzuncompress(base64_decode($this->source));
    }

    public function toJson($opts = 0) // JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    {
        return json_encode($this->toArray(), $opts);
    }

    public function offsetExists($offset)
    {
        return array_has($this->toArray(), $offset);
    }

    public function offsetGet($offset)
    {
        return array_get($this->toArray(), $offset);
    }

    public function offsetSet($offset, $value)
    {
        if ( isset($this->{$offset}) ) {
            $this->{$offset} = $value;
        }
    }

    public function offsetUnset($offset)
    {
        if ( isset($this->{$offset}) ) {
            unset($this->{$offset});
        }
    }

}
