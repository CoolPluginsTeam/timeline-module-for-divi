<?php
/**
 * All modules.
 *
 * @package DTMC\Modules;
 */

namespace DTMC\Modules;

if ( ! defined( 'ABSPATH' ) ) {
  die( 'Direct access forbidden.' );
}

use DTMC\Modules\StaticModule\StaticModule;
use DTMC\Modules\TimeilneD5\TimeilneD5;
use DTMC\Modules\TimelineD5item\TimelineD5item;

add_action(
    'divi_module_library_modules_dependency_tree',
    function( $dependency_tree ) {
        $dependency_tree->add_dependency( new StaticModule() );
        $dependency_tree->add_dependency( new TimeilneD5() );
        $dependency_tree->add_dependency( new TimelineD5item() );
    }
);