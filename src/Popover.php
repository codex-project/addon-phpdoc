<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\Element;
use Codex\Addon\Phpdoc\Elements\Method;
use Codex\Projects\Project;
use Codex\Support\Collection;
use Sebwite\Support\Str;

class Popover
{
    /** @var \Codex\Projects\Project */
    protected $project;

    /** @var \Codex\Addon\Phpdoc\ProjectPhpdoc */
    protected $phpdoc;

    protected $elements;

    /**
     * Popover constructor.
     *
     * @param \Codex\Projects\Project $project
     */
    public function __construct(Project $project)
    {
        $this->project  = $project;
        $this->phpdoc   = $project->phpdoc;
        $this->elements = new Collection($project->phpdoc->getElements()->toArray());
    }


    public static function make(Project $project)
    {
        return app(static::class, compact('project'));
    }

    public function generate($class, $method = null)
    {
        /** @var Element $class */
        $el = $this->phpdoc->getElement($fullName = Str::ensureLeft($class, '\\'));
        if ( !$el ) {
            return;
        }
        $data = $el->toArray();
        // title
        $type  = isset($method) ? 'method' : 'class';
        $title = "<span class=\"popover-phpdoc-type\">{$type}</span>" . view('codex-phpdoc::partials.type', [ 'type' => Str::ensureLeft($class, '\\'), 'phpdoc' => $this->phpdoc, 'typeFullName' => true ])->render();
        $title = str_replace('"', '\'', $title);
        if ( $method ) {
            $data = new Collection($data[ 'methods' ]);
            $data = $data->where('name', $method)->first();
            if ( !$data ) {
                return;
            }
            $title   = trim($title) . '&nbsp;::&nbsp;' . $method;
            $content = view('codex-phpdoc::partials.method', [ 'method' => $data, 'phpdoc' => $this->phpdoc, 'class' => 'fs-12' ])->render();
        } else {
            if ( isset($data[ 'extends' ]) && strlen($data[ 'extends' ]) > 0 ) {
                $title .= '&nbsp;<small class="fs-13">extends</small>&nbsp;' . view('codex-phpdoc::partials.type', [ 'type' => $data[ 'extends' ], 'phpdoc' => $this->phpdoc, 'class' => 'fs-13' ])->render();
            }
            $content = '';
        }

        if ( array_key_exists('extends', $data) ) {

            $this->handleExtends($content, $data);
        }

        if ( array_key_exists('implements', $data) && count($data[ 'implements' ]) > 0 ) {
            $this->handleImplements($content, $data);
        }
        $methods = '';
        if ( $method === 1 ) {
            $methods = '<h5>Methods:</h5>';
            $methods .= view('codex-phpdoc::partials.method', [
                'method' => $el->getOwnMethods()->toArray(),
                'phpdoc' => $this->phpdoc,
                'class'  => 'fs-10',
            ])->render();
        }

        $content .= <<<HTML
<div class="popover-phpdoc-description fs-10">
{$data['description']}
<br>
{$methods}
</div>
HTML;
        $title   = str_replace('"', '\'', $title);
        $content = str_replace('"', '\'', $content);
        return compact('title', 'content');
    }

    protected function handleImplements(&$content, $data)
    {
        foreach ( $data[ 'implements' ] as $className ) {
            $class = $this->phpdoc->getElement($className);
        }
    }

    protected function handleExtends(&$content, $data)
    {
        $extended = $this->phpdoc->getElement($data[ 'extends' ]);
        if ( $extended === null ) {
            return;
        }
        $class = $extended->toArray();
        $name  = $class[ 'name' ];
    }


}
