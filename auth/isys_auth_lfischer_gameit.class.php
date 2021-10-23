<?php

/**
 * "Game IT" auth class
 *
 * @package    Modules
 * @subpackage GameIT
 * @license    MIT
 */
class isys_auth_lfischer_gameit extends isys_auth implements isys_auth_interface
{
    /**
     * @var null
     */
    private static $m_instance = null;

    /**
     * @return array
     */
    public function get_auth_methods()
    {
        return [
            'game' => [
                'title' => 'LC__MODULE__GAMEIT__AUTH',
                'type' => 'boolean',
                'rights' => [isys_auth::VIEW]
            ]
        ];
    }

    /**
     * @param $p_right
     * @return bool
     */
    public function visualization($p_right)
    {
        if (!$this->is_auth_active()) {
            return true;
        }

        return $this->generic_right($p_right, 'visualization', self::EMPTY_ID_PARAM, new isys_exception_auth(_L(' ### ')));
    }

    /**
     * @return int
     */
    public function get_module_id()
    {
        return C__MODULE__GAMEIT;
    }

    /**
     * @return string
     */
    public function get_module_title()
    {
        return 'LC__MODULE__GAMEIT';
    }

    /**
     * @return isys_auth_gameit|null
     */
    public static function instance()
    {
        if (self::$m_dao === null) {
            global $g_comp_database;

            self::$m_dao = new isys_auth_dao($g_comp_database);
        }

        if (self::$m_instance === null) {
            self::$m_instance = new self;
        }

        return self::$m_instance;
    }
}
