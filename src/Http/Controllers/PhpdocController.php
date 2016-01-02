<?php
namespace Codex\Hooks\Phpdoc\Http\Controllers;

use Codex\Core\Http\Controllers\Controller;
use Codex\Hooks\Phpdoc\PhpdocDocument;

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
    /**
     * Render the documentation page for the given project and version.
     *
     * @param string   $projectSlug
     * @param string|null   $ref
     * @param string $path
     * @return $this
     */
    public function show($projectName, $ref = null)
    {
        $project = $this->codex->getProject($projectName);

        if (is_null($ref)) {
            $ref = $project->getDefaultRef();
        }

        $project->setRef($ref);
        /** @var PhpdocDocument $document */
        $document = $project->getPhpdocDocument();
        $content = $document->render();

        $this->view->composer($document->attr('view'), $this->codex->config('projects_menus_view_composer'));

        return $this->view->make($document->attr('view'), compact('project', 'document', 'content'));

    }
}
