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

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

class AbstractHandler implements SubscribingHandlerInterface
{


    /**
     * Return format:
     *
     *      array(
     *          array(
     *              'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
     *              'format' => 'json',
     *              'type' => 'DateTime',
     *              'method' => 'serializeDateTimeToJson',
     *          ),
     *      )
     *
     * The direction and method keys can be omitted.
     *
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return static::$subscribingMethods;
    }

    protected static $subscribingMethods = [];

    protected static function method($type, $method, $format, $direction)
    {
        static::$subscribingMethods[] = compact('type', 'method', 'format', 'direction');
    }

    protected static function dmethod($type, $method, $format = 'xml')
    {
        static::method($type, $method, $format, GraphNavigator::DIRECTION_DESERIALIZATION);
    }
}