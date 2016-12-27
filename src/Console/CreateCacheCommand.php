<?php
/**
 * Part of the Codex Project packages.
 *
 * License and copyright information bundled with this package in the LICENSE file.
 *
 * @author    Robin Radic
 * @copyright Copyright 2016 (c) Codex Project
 * @license   http://codex-project.ninja/license The MIT License
 */

/**
 * Created by IntelliJ IDEA.
 * User: radic
 * Date: 6/11/16
 * Time: 3:44 PM
 */

namespace Codex\Addon\Phpdoc\Console;


use Illuminate\Console\Command;

class CreateCacheCommand extends Command
{
    protected $signature = 'codex:phpdoc:create';

    protected $description = 'Create the PHPDoc cache';

    public function handle()
    {
        foreach(codex()->projects->getPhpdocProjects() as $project){
            foreach($project->refs->all() as $ref){
                $ref->phpdoc->clearCache();
                $ref->phpdoc->checkUpdate(true);
            }

            $this->comment("Cache created for [{$project->getName()}]");
        }
        $this->info('All done sire!');
    }
}
