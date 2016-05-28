<?php
namespace Codex\Addon\Phpdoc\Http\Controllers\Api\V1;

use Codex\Addon\Phpdoc\Factory;
use Codex\Addon\Phpdoc\ProjectPhpdoc;
use Codex\Core\Http\Controllers\API\V1\ApiController;

class PhpdocApiController extends ApiController
{
    protected $factory;

    /**
     * PhpdocApiController constructor.
     *
     * @param $factory
     */
    public function __construct(Factory $factory)
    {
        parent::__construct();
        $this->factory = $factory;
    }

    protected function getDoc($projectSlug, $ref = null)
    {
        $doc = $this->factory->make($this->resolveProject($projectSlug, $ref));
        $doc->checkUpdate(true);
        return $doc;
    }

    protected function isFull()
    {
        return request()->get('full', false) === true;
    }

    public function getTree($projectSlug, $ref = null)
    {
        return $this->response($this->getDoc($projectSlug, $ref)->tree($this->isFull()));
    }

    public function getList($projectSlug, $ref = null)
    {
        #\Kint::dump($el = $this->getDoc($projectSlug, $ref)->getElement('Codex\\Core\\Codex'));
        #\Kint::dump($el->toArray());
        return $this->response($this->getDoc($projectSlug, $ref)->getElements($this->isFull())->toArray());
    }

    public function getEntity($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string) request()->get('entity');

        if(!$phpdoc->hasElement($entity)){
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity)->toArray();

        return $this->response($entity);
    }

    public function getSource($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string) request()->get('entity');
        if(!$phpdoc->hasElement($entity)){
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity);

        return $this->response($entity);
    }

    public function getDocPage($projectSlug, $ref = null)
    {
        $phpdoc = $this->getDoc($projectSlug, $ref);
        $entity = (string) request()->get('entity');
        if(!$phpdoc->hasElement($entity)){
            return $this->error('Entity does not exist');
        }
        $entity = $phpdoc->getElement($entity);

        return $this->response([
            'doc' => view('codex-phpdoc::doc', $entity->toArray())->with('phpdoc', $phpdoc)->render()
        ]);
    }

}