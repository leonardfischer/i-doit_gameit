<?php

namespace idoit\Module\Lfischergameit\Model;

use idoit\Model\Dao\Base;

/**
 * "Game IT" Dao class
 *
 * @package    Modules
 * @subpackage GameIT
 * @license    MIT
 */
class Dao extends Base
{
    /**
     * @param integer $points
     * @param integer $userId
     *
     * @return  boolean
     * @throws  \isys_exception_dao
     */
    public function addUserPoints($points, $userId = null)
    {
        if ($userId === null) {
            $userId = \isys_application::instance()->container->get('session')->get_user_id();
        }

        $query = 'INSERT INTO gameit SET 
            gameit_points = ' . $this->convert_sql_int($points) . ',
            gameit_date = NOW(),
            gameit_user_id = ' . $this->convert_sql_id($userId) . ';';

        return $this->update($query) && $this->apply_update();
    }

    /**
     * @return  \isys_component_dao_result
     * @throws  \isys_exception_database
     */
    public function getTopTen()
    {
        $query = 'SELECT 
                isys_obj__id as objectId, 
                isys_obj__title as objectTitle, 
                SUM(gameit_points) as points 
            FROM gameit 
            INNER JOIN isys_obj ON isys_obj__id = gameit_user_id
            GROUP BY isys_obj__id
            ORDER BY points DESC
            LIMIT 10;';

        return $this->retrieve($query);
    }

    /**
     * @param integer|array $user
     *
     * @return  \isys_component_dao_result
     * @throws  \isys_exception_database
     */
    public function getDataByUser($user)
    {
        if (!is_array($user)) {
            $user = [$user];
        }

        $query = 'SELECT 
                gameit_user_id as objectId, 
                gameit_date as eventDate,
                gameit_points as points 
            FROM gameit
            WHERE gameit_user_id ' . $this->prepare_in_condition($user) . '
            ORDER BY gameit_date ASC;';

        return $this->retrieve($query);
    }
}
