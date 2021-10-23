<?php

namespace idoit\Module\Lfischergameit\Controller;

use idoit\Controller;
use idoit\Module\Lfischergameit\Model\Dao;
use idoit\Tree\Node;
use isys_application as Application;
use isys_component_tree as TreeComponent;
use isys_module as Module;
use isys_register as Register;

/**
 * "Game IT" Main controller
 *
 * @package    Modules
 * @subpackage GameIT
 * @license    MIT
 */
class Main extends Controller\Base implements \isys_controller
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @param Register    $request
     * @param Application $application
     */
    public function handle(Register $request, Application $application)
    {
        return new \idoit\Module\Lfischergameit\View\Gamecenter($request);
    }

    /**
     * @param Application $application
     *
     * @return Dao
     */
    public function dao(Application $application)
    {
        return Dao::instance($application->container->get('database'));
    }

    /**
     * @param Register      $request
     * @param Application   $application
     * @param TreeComponent $tree
     *
     * @return Node
     */
    public function tree(Register $request, Application $application, TreeComponent $tree)
    {
        return new Node(
            $application->container->get('language')->get('LC__MODULE__FLOORPLAN'),
            '',
            $request->get('BASE') . 'images/icons/silk/shape_group.png'
        );
    }

    /**
     * Main constructor.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }
}
