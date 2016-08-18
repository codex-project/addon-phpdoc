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
namespace Codex\Addon\Phpdoc\Jobs;

use Codex\Support\Job;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laradic\Filesystem\Filesystem;

class CompileStructureJob extends Job implements ShouldQueue
{
    use InteractsWithQueue; //, SerializesModels;

    protected $xmlFilePath;

    protected $destDirPath;

    /**
     * CompileStructureJob constructor.
     *
     * @param $xmlFilePath
     * @param $destDirPath
     */
    public function __construct($xmlFilePath, $destDirPath)
    {
        $this->xmlFilePath = $xmlFilePath;
        $this->destDirPath = $destDirPath;
    }


    public function handle(Filesystem $fs)
    {
        $xml = $fs->get($this->xmlFilePath);

    }

}
