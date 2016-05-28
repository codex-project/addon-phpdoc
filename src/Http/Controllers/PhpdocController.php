<?php
namespace Codex\Addon\Phpdoc\Http\Controllers;

use Codex\Addon\Phpdoc\ProjectPhpdoc;
use Codex\Addon\Phpdoc\PhpdocDocument;
use Codex\Core\Http\Controllers\Controller;
use Codex\Core\Projects\Project;
use Illuminate\Http\Request;

/**
 * Class GithubController
 *
 * @package     Laradic\Codex\Http\Controllers
 * @author      Robin Radic
 * @license     MIT
 * @copyright   2011-2015, Robin Radic
 * @link        http://radic.mit-license.org
 */
class PhpdocController extends Controller
{
    protected $types = [ 'tree', 'list', 'entity' ];

    /** @var  Project */
    protected $project;

    /** @var  ProjectPhpdoc */
    protected $phpdoc;


    /**
     * Render the documentation page for the given project and version.
     *
     * @param string      $projectSlug
     * @param string|null $ref
     * @param string      $path
     *
     * @return $this
     */
    public function show($projectSlug, $ref, $type )
    {
        if ( !$this->codex->projects->has($projectSlug) ) {
            return response()->json('Project does not exist', 404);
        }
        $project = $this->codex->projects->get($projectSlug);

        if ( is_null($ref) ) {
            $ref = $project->getDefaultRef();
        }
        $project->setRef($ref);

        $this->project = $project;
        $this->phpdoc  = app('codex.phpdoc')->make($project);
        if ( !in_array($type, $this->types, true) ) {
            abort(500, 'type does not exist');
        }
       ## $response = call_user_func_array([ $this, 'get' . ucfirst($type) ], [ request() ]);

        return $response;
    }

}
