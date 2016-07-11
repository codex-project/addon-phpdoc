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

use Codex\Addon\Phpdoc\Structure\StructureFactory;
use Codex\Support\Collection;
use Sebwite\Filesystem\Filesystem;
use SimpleXMLElement;

class Compiler
{
    /** @var Filesystem */
    protected $fs;

    /**
     * StructureFactory constructor.
     *
     * @param \Sebwite\Filesystem\Filesystem $fs
     */
    public function __construct(Filesystem $fs)
    {
        $this->fs = $fs;
    }


    /**
     * transform method
     *
     * @param $xml
     *
     * @return \Codex\Support\Collection|File[]
     */
    protected function transform($xml)
    {
        if ( !$xml instanceof SimpleXMLElement ) {
            $xml = simplexml_load_string($xml);
        }

        $factory = new StructureFactory($this->fs);
        $files = new Collection();
        foreach ( $xml->file as $i => $file ) {
            /** @var SimpleXMLElement $file */
            $raw = json_decode(json_encode(simplexml_load_string($file->saveXML())), true);
            $files->add(new Structure\File($raw, $factory));
        }


        return $files;
    }

    public function compile($xml, $destinationPath)
    {
        $files = $this->transform($xml);
        $this->createFiles($destinationPath, $files);
        $this->createManifest(path_join($destinationPath, 'manifest.php'), $files);
    }

    /**
     * createManifest method
     *
     * @param                                  $path
     * @param \Codex\Support\Collection|File[] $files
     *
     * @return array
     */
    protected  function createManifest($path, $files)
    {
        $data = [ ];
        foreach ( $files as $file ) {
            $data[] = [
                'type'      => $file[ 'type' ],
                'name'      => $file[ 'entity' ][ 'name' ],
                'full_name' => $file[ 'entity' ][ 'full_name' ],
                'namespace' => $file[ 'entity' ][ 'namespace' ],
            ];
        }
        $this->fs->put($path, Util::exportArray($data));
        return $data;
    }


    /**
     * createCacheFiles method
     *
     * @param string                           $path
     * @param \Codex\Support\Collection|File[] $files
     */
    protected  function createFiles($path, $files)
    {

        $this->fs->ensureDirectory($path);
        foreach ( $files as $file ) {
            $fileName = Util::toFileName($file->entity[ 'full_name' ], '.dat');
            #$export   = Util::exportArray($file->toArray());
            $export = serialize($file);
            $this->fs->put(path_join($path, $fileName), $export);
        }
    }
}
