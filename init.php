<?php

use idoit\Module\Lfischergameit\Component\PointServer;

if (isys_module_manager::instance()->is_active('lfischer_gameit')) {
    \idoit\Psr4AutoloaderClass::factory()->addNamespace('idoit\Module\Lfischergameit', __DIR__ . '/src/');

    include_once __DIR__ . '/isys_module_lfischer_gameit_autoload.class.php';

    spl_autoload_register('isys_module_lfischer_gameit_autoload::init');

    $container = isys_application::instance()->container;
    $pointServer = new PointServer();

    $container->get('signals')
        ->connect('mod.css.attachStylesheet', static function () { return __DIR__ . '/assets/css/gameit.css'; })
        ->connect('mod.cmdb.afterInsertObject', [$pointServer, 'objectCreate'])
        ->connect('mod.cmdb.afterCategoryEntrySave', [$pointServer, 'categorySave'])
        ->connect('mod.cmdb.afterCreateCategoryEntry', [$pointServer, 'categoryCreate']);

    if ($registry = $container->get('components.registry')) {
        $registry->register('menu_tree.config.lfischer_gameit.showMenu', false);
    }
}
