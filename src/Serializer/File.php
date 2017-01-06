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

class File
{
    /**
     * @Serializer\XmlElement()
     * @Serializer\Type("Codex\Addon\Phpdoc\Serializer\Docblock")
     * @var Docblock;
     */
    public $docblock;

    /**
     * @Serializer\XmlAttribute()
     * @Serializer\Type("string")
     * @var string
     */
    public $path;

    /**
     * @Serializer\XmlAttribute()
     * @Serializer\Type("string")
     * @var string
     */
    public $generatedPath;

    /**
     * @Serializer\XmlAttribute()
     * @Serializer\Type("string")
     * @var string
     */
    public $hash;
}