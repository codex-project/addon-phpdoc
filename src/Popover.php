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
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Structure\Entity ;
use Codex\Addon\Phpdoc\Structure\Method;
use Codex\Projects\Project;
use Codex\Projects\Ref;
use Codex\Support\Collection;
use Laradic\Support\Str;

class Popover
{
    /** @var \Codex\Projects\Project */
    protected $project;

    /** @var \Codex\Addon\Phpdoc\PhpdocRef */
    protected $phpdoc;

    /** @var \Codex\Projects\Ref  */
    protected $ref;

    protected $elements;

    /**
     * Popover constructor.
     *
     * @param \Codex\Projects\Project $project
     */
    public function __construct(Ref $ref)
    {
        $this->ref = $ref;
        $this->project  = $ref->getProject();
        $this->phpdoc   = $ref->phpdoc;
        $this->elements = new Collection($this->phpdoc->getEntities()->toArray());
    }


    public static function make(Ref $ref)
    {
        return app(static::class, compact('ref'));
    }

    public function generate($class, $method = null)
    {
        /** @var Entity $class */
        $file = $this->phpdoc->getEntity($fullName = Str::ensureLeft($class, '\\'));
        if ( !$file ) {
            return;
        }
        $entity = $file->getEntity();
        $data = $file->toArray();
        // title
        $type  = isset($method) ? 'method' : 'class';
        $title = "<span class=\"popover-phpdoc-type\">{$type}</span>" . view('codex-phpdoc::partials.type', [ 'type' => Str::ensureLeft($class, '\\'), 'phpdoc' => $this->phpdoc, 'typeFullName' => true ])->render();
        $title = str_replace('"', '\'', $title);
        if ( $method ) {
            $data = new Collection($entity[ 'methods' ]);
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
                'method' => $entity->getMethods()->where('inherited', false)->toArray(),
                'phpdoc' => $this->phpdoc,
                'class'  => 'fs-10',
            ])->render();
        }

        $content .= <<<HTML
<div class="popover-phpdoc-description fs-10">
{$entity['description']}
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
            $class = $this->phpdoc->getEntity($className);
        }
    }

    protected function handleExtends(&$content, $data)
    {
        $extended = $this->phpdoc->getEntity($data[ 'extends' ]);
        if ( $extended === null ) {
            return;
        }
        $class = $extended->toArray();
        $name  = $class[ 'name' ];
    }


}
