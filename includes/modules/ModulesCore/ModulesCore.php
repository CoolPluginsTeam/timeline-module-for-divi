<?php

class TMDIVI_Builder_Module extends ET_Builder_Module{

    public function __construc(){
        add_action('wp_enqueue_scripts', array($this, '_enqueue_scripts'));
    }

    /**
     * Render prop value
     * Some prop value needs to be parsed before can be used
     * This method is added to display how to parse certain saved value
     *
     */
    public function render_prop($value = '', $field_type = ''){
        $output = '';

        if ('' === $value) {
            return $output;
        }

        switch ($field_type) {
            case 'select_icons':
                $output = sprintf(
                    '<i style="font-family: ETmodules; font-style: normal;">%1$s</i>',
                    esc_attr(et_pb_process_font_icon($value))
                );
                break;

            default:
                $output = $value;
                break;
        }

        return $output;
    }

    /**
     * Configuring Advanced field for Divi builder.
     */
    public function get_advanced_fields_config(){
        return array(
            'text' => false,
            'fonts' => array(),
            'max_width' => false,
            'margin_padding' => false,
            'border' => false,
            'box_shadow' => false,
            'filters' => false,
            'transform' => false,
            'animation' => false,
            'background' => false
        );
    }

    public static function enqueue_google_font($font_family) {
        $font_parts = explode('|', $font_family);
        $font_family_name = $font_parts[0];
        if ($font_family_name) {
            wp_enqueue_style('tmdivi-gfonts-' . $font_family_name, "https://fonts.googleapis.com/css2?family=$font_family_name&display=swap", array(),TM_DIVI_V, null);
        }
    }

    public static function extractFontProperties($fontString) {
        $fontParts = explode('|', $fontString);
        $fontFamily = $fontParts[0];
        $fontWeight = $fontParts[1];
        $fontStyle = !empty($fontParts[2]) ? "italic" : 'normal'; 
    
        // Determine text transform
        if (!empty($fontParts[3])) {
            $textTransform = "uppercase";
        } elseif (!empty($fontParts[5])) {
            $textTransform = "capitalize";
        } else {
            $textTransform = "none";
        }
    
        // Determine text decoration
        if (!empty($fontParts[4]) && !empty($fontParts[6])) {
            $textDecoration = "line-through";
        } elseif (!empty($fontParts[4])) {
            $textDecoration = "underline";
        } elseif (!empty($fontParts[6])) {
            $textDecoration = "line-through";
        } else {
            $textDecoration = "none";
        }
    
        $textDecorationLineColor = (!empty($fontParts[7])) ? $fontParts[7] : ''; 
        $textDecorationStyle = (!empty($fontParts[8])) ? $fontParts[8] : ''; 

        return array(
            'fontFamily' => $fontFamily,
            'fontWeight' => $fontWeight,
            'fontStyle' => $fontStyle,
            'textTransform' => $textTransform,
            'textDecoration' => $textDecoration,
            'textDecorationLineColor' => $textDecorationLineColor,
            'textDecorationStyle' => $textDecorationStyle,
        );
    }
    /**
     *  Credit information for divi module
     */
    protected $module_credits = array(
        'module_uri' => 'https://coolplugins.net',
        'author' => 'Cool Plugins',
        'author_uri' => 'https://coolplugins.net',
    );

    public function _enqueue_scripts()
    {
        wp_enqueue_style('timeline-style', TM_DIVI_MODULE_URL . '/Timeline/style.css', array(''), TM_DIVI_V, true);
        wp_enqueue_style('timelineChild-style', TM_DIVI_MODULE_URL . '/TimelineChild/style.css', array(''), TM_DIVI_V, true);
    }
}
