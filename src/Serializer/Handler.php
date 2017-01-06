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


use JMS\Serializer\Context;
use JMS\Serializer\XmlDeserializationVisitor;
use SimpleXMLElement;

class Handler extends AbstractHandler
{
    public static function getSubscribingMethods()
    {
//        static::dmethod('Codex\Support\Version', 'dsVersionXml');
        return parent::getSubscribingMethods();
    }

}