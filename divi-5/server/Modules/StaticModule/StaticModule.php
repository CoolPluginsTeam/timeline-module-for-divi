<?php
/**
 * Static Module class.
 *
 * @package DTMC\Modules\StaticModule;
 */

namespace DTMC\Modules\StaticModule;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Packages\ModuleLibrary\ModuleRegistration;
use DTMC\Modules\StaticModule\StaticModuleTraits;

/**
 * Class StaticModule
 *
 * @package DTMC\Modules\StaticModule
 */
class StaticModule implements DependencyInterface {

  use StaticModuleTraits\RenderCallbackTrait;
  use StaticModuleTraits\ModuleClassnamesTrait;
  use StaticModuleTraits\ModuleStylesTrait;
  use StaticModuleTraits\ModuleScriptDataTrait;

  public function load() {
    $module_json_folder_path = dirname( __DIR__, 3 ) . '/visual-builder/src/modules/static-module';

    add_action(
      'init',
      function() use ( $module_json_folder_path ) {
        ModuleRegistration::register_module(
          $module_json_folder_path,
          [
            'render_callback' => [ StaticModule::class, 'render_callback' ],
          ]
        );
      }
    );
  }
}