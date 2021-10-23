<?php

/**
 * "Game IT" autoloader
 *
 * @package    Modules
 * @subpackage GameIT
 * @author     Leonard Fischer <post@leonardfischer.de>
 * @copyright  2018 Leonard Fischer
 * @version    1.0
 * @license    MIT
 */
class isys_module_lfischer_gameit_autoload extends isys_module_manager_autoload
{
    /**
     * @param string $p_classname
     * @return bool
     */
    public static function init($p_classname)
    {
        $l_classlist = [
            'isys_auth_lfischer_gameit' => '/src/classes/modules/lfischer_gameit/auth/isys_auth_lfischer_gameit.class.php',
        ];

        if (isset($l_classlist[$p_classname]))
        {
            if (parent::include_file($l_classlist[$p_classname]))
            {
                isys_caching::factory('autoload')->set($p_classname, $l_classlist[$p_classname]);

                return true;
            }
        }

        return false;
    }
}
