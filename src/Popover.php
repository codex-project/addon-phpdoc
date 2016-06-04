<?php
namespace Codex\Addon\Phpdoc;

use Codex\Addon\Phpdoc\Elements\Element;
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
     * @param $phpdoc
     */
    public function __construct(ProjectPhpdoc $phpdoc)
    {
        $this->phpdoc   = $phpdoc;
        $this->project  = $phpdoc->getProject();
        $this->elements = new Collection($phpdoc->getElements()->toArray());
    }


    public static function make(ProjectPhpdoc $phpdoc)
    {
        return app(static::class, compact('phpdoc'));
    }

    public function generate($class, $method = null)
    {
        /** @var Element $class */
        //$data = $this->elements->where('full_name', $fullName = )->first();
        $data = $this->phpdoc->getElement($fullName = Str::ensureLeft($class, '\\'))->toArray();
        if ( !$data ) {
            return;
        }
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

        $content .= <<<HTML
<div class="popover-phpdoc-description fs-10">
{$data['description']}
</div>
HTML;
        $title   = str_replace('"', '\'', $title);
        $content = str_replace('"', '\'', $content);
        return compact('title', 'content');
    }
}