<?php
/*
Plugin Name: Timeline Module For Divi
Plugin URI:  https://cooltimeline.com
Description: A timeline module for Divi
Version:     1.0.0
Author:      CoolPlugins
Author URI:  https://coolplugins.net
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: timeline-module-for-divi
Domain Path: /languages

Timeline Module For Divi is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Timeline Module For Divi is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Timeline Module For Divi. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

define('TM_DIVI_V', '1.0.0');
define('TM_DIVI_DIR', plugin_dir_path(__FILE__));
define('TM_DIVI_URL', plugin_dir_url(__FILE__));
define('TM_DIVI_MODULE_URL', plugin_dir_url(__FILE__) . 'includes/modules');
define('TM_DIVI_MODULE_DIR', plugin_dir_path(__FILE__) . 'includes/modules');

class Timeline_Module_For_Divi {

    public function __construct() {
        self::includes();
        add_action('divi_extensions_init', array($this, 'initialize_extension'));
        add_action( 'admin_init', array( $this, 'is_divi_theme_exist' ) );
    }


    public function is_divi_theme_exist(){
        if (!self::is_theme_activate('Divi')) {
            // Divi theme is not activated, display admin notice
            add_action('admin_notices', array($this, 'admin_notice_missing_divi_theme'));
        }   
    }
    /**
     * Initializes the extension.
     */
    public function initialize_extension() {
        require_once TM_DIVI_DIR . '/includes/TimelineModuleForDivi.php';
    }
    
    public static function includes(){
        require_once TM_DIVI_MODULE_DIR . '/assets-loader.php';
        new AssetsLoader();
    }

    public static function is_theme_activate($target){
        $theme = wp_get_theme();
        if ($theme->name == $target || stripos($theme->parent_theme, $target) !== false) {
            return true;
        }
        if (apply_filters('divi_ghoster_ghosted_theme', '') == $target) {
            return true;
        }
        return false;
    }

    public function admin_notice_missing_divi_theme(){
        $message = esc_html__(
            'Timeline Module For Divi requires Divi (Theme) to be installed and activated.',
            'timeline-module-for-divi'
        );
        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', esc_html( $message ) );
        deactivate_plugins(__FILE__);
    }    

}

new Timeline_Module_For_Divi();