<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\PhpdocXmlElement;

/**
 * Extracts/Transforms a PHPDocumentor XML file
 *
 * @package        App\Phpdoc
 * @author         CLI
 * @copyright      Copyright (c) 2015, CLI. All rights reserved
 */
class Extractor
{
    /** @var \Codex\Addon\Phpdoc\ProjectPhpdoc */
    protected $factory;

    /** @var PhpdocXmlElement */
    protected $doc;

    /** @var \Codex\Projects\Project  */
    protected $project;

    /** @var \Sebwite\Filesystem\Filesystem  */
    protected $fs;

    /**
     * The raw XML string
     * @var string
     */
    protected $raw;

    protected $manifest = [ ];

    protected $itemHandlers = [ 'handleCreateItem', 'handleManifestItem' ];

    public function __construct(ProjectPhpdoc $factory)
    {
        $this->factory = $factory;
        $this->project = $factory->getProject();
        $this->fs      = $factory->getFs();
    }


    public function run()
    {
        $this->init();
        $this->createInfoFile();
        $this->runItemLoop();
        $this->createManifest();
    }

    protected function init()
    {
        $this->fs->isDirectory($this->factory->getCachePath()) && $this->fs->deleteDirectory($this->factory->getCachePath());
        $this->fs->ensureDirectory($this->factory->getCachePath());

        $this->raw = $this->project->getFiles()->get(
            $this->project->refPath($this->project->config('phpdoc.path'))
        );
        $this->doc = PhpdocXmlElement::create($this->raw);
    }

    protected function runItemLoop()
    {
        foreach ( $this->doc->file as $i => $file ) {
            foreach ( PhpdocXmlElement::getTypes() as $type ) {
                if ( isset($file->{$type}) ) {
                    foreach ( $this->itemHandlers as $handler ) {
                        $this->$handler($file, $type);
                    }
                }
            }
        }
    }

    ## item handlers

    protected function handleCreateItem(PhpdocXmlElement $file, $type)
    {
        $typeInfo = $file->{$type};
        $file->addChild('type', $type);
        $fileName = Util::toFileName($typeInfo->full_name, '.xml');
        $this->writeCacheFile($fileName, $file->asXML());
    }

    protected function handleManifestItem(PhpdocXmlElement $file, $type)
    {

        $this->manifest[] = [
            'type'      => $type,
            'name'      => (string)$file->{$type}->name,
            'full_name' => (string)$file->{$type}->full_name,
            'namespace' => (string)$file->{$type}->getAttribute('namespace'),
        ];
    }

    ## php array file creators

    protected function createInfoFile()
    {
        $info                 = $this->doc->getAttributes();
        $info[ 'deprecated' ] = $this->doc->deprecated->getAttributes();

        $this->writeCacheFile('info.php', Util::exportArray($info));
    }

    protected function createManifest()
    {
        $this->writeCacheFile('manifest.php', Util::exportArray($this->manifest));
    }
    protected function writeCacheFile($fileName, $content)
    {
        $this->factory->getFs()->put($this->factory->getCacheFilePath($fileName), $content);
    }


}