<?php

if( !defined('ABSPATH') ){
    exit;
}

class AssetsLoader{
    public function __construct(){
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts(){
        $js_path = TM_DIVI_URL . 'assets/js/';
        wp_register_script('tm-divi-vertical', $js_path . 'tm_divi_vertical.min.js', array('jquery'),TM_DIVI_V , true);    

        if (function_exists('et_core_is_fb_enabled') && et_core_is_fb_enabled()) {
          wp_enqueue_script('tm-divi-editor', $js_path . 'tm_editor.min.js', array('jquery'), TM_DIVI_V, true);
        }  
  }

}

