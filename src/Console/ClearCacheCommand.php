<?php
/**
 * Created by IntelliJ IDEA.
 * User: radic
 * Date: 6/11/16
 * Time: 12:42 PM
 */

namespace Codex\Addon\Phpdoc\Console;


use Illuminate\Console\Command;

class ClearCacheCommand extends Command
{
    protected $signature = 'codex:phpdoc:clear';

    protected $description = 'Clear the PHPDoc caches';

    public function handle()
    {
        foreach ( codex()->projects->getPhpdocProjects() as $project ) {
            foreach($project->refs->all() as $ref){
                $ref->phpdoc->clearCache();
            }

            $this->comment("Cleared cache for [{$project->getName()}]");
        }
        $this->info('All done sire!');
    }
}
