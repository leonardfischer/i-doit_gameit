<?php

/**
 * "Game IT" module class
 *
 * @package    Modules
 * @subpackage GameIT
 * @author     Leonard Fischer <post@leonardfischer.de>
 * @copyright  2018 Leonard Fischer
 * @version    1.0
 * @license    MIT
 */
class isys_module_lfischer_gameit extends isys_module implements \idoit\AddOn\AuthableInterface
{
    const DISPLAY_IN_MAIN_MENU   = true;
    const DISPLAY_IN_SYSTEM_MENU = false;
    const MAIN_MENU_REWRITE_LINK = true;

    /**
     * Initializes the module.
     *
     * @param isys_module_request $request
     */
    public function init(isys_module_request $request)
    {
    }

    /**
     * @return isys_auth_lfischer_gameit
     */
    public static function getAuth()
    {
        return isys_auth_lfischer_gameit::instance();
    }
}
