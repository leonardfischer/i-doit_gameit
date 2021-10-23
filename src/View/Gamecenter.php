<?php

namespace idoit\Module\Lfischergameit\View;

use idoit\Model\Dao\Base as DaoBase;
use idoit\View\Base as ViewBase;
use idoit\View\Renderable;
use isys_component_template as Template;
use isys_module as Module;

/**
 * "Game IT" View class
 *
 * @package    Modules
 * @subpackage GameIT
 * @license    MIT
 */
class Gamecenter extends ViewBase implements Renderable
{
    /**
     * @param Module $module
     * @param Template $template
     * @param DaoBase $model
     * @return $this
     */
    public function process(Module $module, Template $template, DaoBase $model)
    {
        \isys_component_template_navbar::getInstance()->hide_all_buttons();

        $this->paths['contentbottomcontent'] = $module->getPath() . 'templates/gamecenter.tpl';

        $topScore = 0;
        $topThree = $topThreeData = $topThreeUsers = $topTen = [];
        $result = $model->getTopTen();
        $color = [
            '#f20',
            '#f80',
            '#fc0'
        ];

        while ($row = $result->get_row()) {
            $row['color'] = $color[count($topThree)];

            if (count($topThreeUsers) < 3) {
                $topThreeUsers[] = $row['objectId'];
                $topThree['o' . $row['objectId']] = $row;
            }

            $topTen[] = $row;


            if ($topScore < $row['points']) {
                $topScore = $row['points'];
            }
        }

        $firstDate = $lastDate = null;
        $result = $model->getDataByUser($topThreeUsers);

        while ($row = $result->get_row()) {
            if ($firstDate === null) {
                $firstDate = $row['eventDate'];
            }

            $row['points'] = (int)$row['points'];

            if (!isset($topThreeData[$row['objectId']])) {
                $topThreeData[$row['objectId']] = [];
            }

            $topThreeData[$row['objectId']][] = $row;
            $lastDate = $row['eventDate'];
        }

        $template
            ->activate_editmode()
            ->assign('firstDate', $firstDate)
            ->assign('lastDate', $lastDate)
            ->assign('topThree', $topThree)
            ->assign('topTen', $topTen)
            ->assign('topThreeData', array_values($topThreeData))
            ->assign('topScore', $topScore);

        return $this;
    }
}
