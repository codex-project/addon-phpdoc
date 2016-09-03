<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Git\Syncer;
use Codex\Codex;
use Codex\Contracts\Documents\Documents;
use Codex\Exception\CodexException;
use Codex\Projects\Project;
use Codex\Projects\Projects;
use Codex\Projects\Ref;
use Codex\Support\Traits\CodexProviderTrait;
use Laradic\ServiceProvider\ServiceProvider;

class PhpdocServiceProvider extends ServiceProvider
{
    use CodexProviderTrait;

    protected $dir = __DIR__;

}
