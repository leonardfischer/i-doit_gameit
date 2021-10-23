<?php

namespace idoit\Module\Lfischergameit\Component;

use Countable;
use idoit\Module\Cmdb\Component\CategoryChanges\Changes;
use idoit\Module\Lfischergameit\Model\Dao;
use isys_application;
use isys_notify;

/**
 * "Game IT" point server component
 *
 * @package    Modules
 * @subpackage GameIT
 * @license    MIT
 */
class PointServer
{
    /**
     * @param $dao
     * @param $objectTypeId
     * @param $objectId
     *
     * @throws \isys_exception_dao
     */
    public function objectCreate($dao, $objectTypeId, $objectId): void
    {
        $this->addUserPoints(_L('LC__MODULE__GAMEIT__EVENT__CREATED_OBJECT'), 10);
    }

    /**
     * @param $dao
     * @param $categoryEntryId
     * @param $saveValue
     * @param $objectId
     * @param $postData
     * @param array|Changes $changes
     *
     * @throws \isys_exception_dao
     */
    public function categorySave($dao, $categoryEntryId, $saveValue, $objectId, $postData, $changes): void
    {
        if ($changes instanceof Changes) {
            $changes = $changes->getCurrentReformatedChanges();
        }

        if (is_array($changes) || $changes instanceof Countable) {
            $attributeCount = count($changes);

            if ($attributeCount > 0) {
                $this->addUserPoints(_L('LC__MODULE__GAMEIT__EVENT__UPDATED_CATEGORY_ENTRY', $attributeCount), ($attributeCount * 4));
            }
        }
    }

    /**
     * @param $categoryEntryId
     * @param $newId
     * @param $saveValue
     * @param $objectId
     * @param $dao
     * @param array|Changes $changes
     *
     * @throws \isys_exception_dao
     */
    public function categoryCreate($categoryEntryId, $newId, $saveValue, $objectId, $dao, $changes): void
    {
        if ($changes instanceof Changes) {
            $changes = $changes->getCurrentReformatedChanges();
        }

        if (is_array($changes) || $changes instanceof Countable) {
            $attributeCount = count($changes);

            if ($attributeCount > 0) {
                $this->addUserPoints(_L('LC__MODULE__GAMEIT__EVENT__CREATED_CATEGORY_ENTRY', $attributeCount), ($attributeCount * 6));
            }
        }
    }

    /**
     * Internal method to add points and notify the user.
     *
     * @param string $message
     * @param int    $points
     *
     * @throws \isys_exception_dao
     */
    private function addUserPoints(string $message, int $points): void
    {
        $database = isys_application::instance()->container->get('database');
        $language = isys_application::instance()->container->get('language');

        isys_notify::success($message . ' +' . $points . ' ' . $language->get('LC__MODULE__GAMEIT__POINTS'),
            ['life' => 5, 'header' => $language->get('LC__MODULE__GAMEIT__EVENT__NEW_POINTS'), 'className' => 'gameit-notify-yay']);

        (new Dao($database))->addUserPoints($points);
    }
}
