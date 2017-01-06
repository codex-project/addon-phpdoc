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
namespace Codex\Addon\Phpdoc\Serializer;

use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Context;
use JMS\Serializer\XmlDeserializationVisitor;
use SimpleXMLElement;

class Version extends \Codex\Support\Version
{
    /**
     * @Serializer\HandlerCallback("xml", direction="deserialization")
     * @param \JMS\Serializer\XmlDeserializationVisitor $visitor
     * @param \SimpleXMLElement                         $element
     * @param \JMS\Serializer\Context                   $context
     *
     * @return static
     */
    public function deserializeFromXml(XmlDeserializationVisitor $visitor, SimpleXMLElement $element, Context $context)
    {
        return static::importString(str_replace('v', '', $element->__toString()));
    }
}