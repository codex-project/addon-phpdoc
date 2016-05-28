<?php
namespace Codex\Addon\Phpdoc\Elements;

use DOMDocument;
use DOMXPath;
use SimpleXMLElement;

/**
 * This is the class Element.
 *
 * @package        App\Phpdoc
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 *
 * @method PhpdocXmlElement[] __get($name)
 * @method PhpdocXmlElement[] attributes()
 * @method PhpdocXmlElement addChild($name, $value = null, $ns = null)
 * @method PhpdocXmlElement[] children()
 * @method PhpdocXmlElement[] xpath($query)
 * @property PhpdocXmlElement $attributes
 * @property PhpdocXmlElement $docblock
 * @property PhpdocXmlElement $class
 * @property PhpdocXmlElement $trait
 * @property PhpdocXmlElement $interface
 * @property PhpdocXmlElement $parse_markers
 * @property string           $source
 */
class PhpdocXmlElement extends SimpleXMLElement
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

    public static function getTypes()
    {
        return ['class', 'trait', 'interface'];
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


    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }

    public function toJson($opts = JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
    {
        return json_encode($this, $opts);
    }

    public function getSourceCode()
    {
        return gzuncompress(base64_decode($this->source));
    }
}