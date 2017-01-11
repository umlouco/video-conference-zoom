<?php
/**
* Class for Shortcodes
*
* @author  Deepen
* @since  2.0.0
*/
class Zoom_Video_Conferencing_Admin_Shortcode {

    private static $instance;

    protected function __construct() {
        add_action('admin_head', array( $this, 'zvc_tinymce_shortcode_button_init' ) );

        //Adding Shortcode
        add_shortcode( 'zoom_api_link', array($this, 'zvc_render_shortcode') );
    }

    static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function zvc_tinymce_shortcode_button_init() {

        //Abort early if the user will never see TinyMCE
        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
            return;

        //Add a callback to regiser our tinymce plugin
        add_filter("mce_external_plugins", array( $this, "zvc_register_tinymce_plugin") );

        // Add a callback to add our button to the TinyMCE toolbar
        add_filter('mce_buttons', array( $this, 'zvc_add_tinymce_button') );
    }

    //This callback registers our plug-in
    public function zvc_register_tinymce_plugin($plugin_array) {
        $plugin_array['wpse72394_button'] = ZOOM_VIDEO_CONFERENCE_PLUGIN_JS_PATH . '/zvc-shortcode.js';
        return $plugin_array;
    }

    //This callback adds our button to the toolbar
    public function zvc_add_tinymce_button($buttons) {
        //Add the button ID to the $button array
        $buttons[] = "wpse72394_button";
        return $buttons;
    }

    /**
     * Rendering Shortcode Output
     * @param  [type] $atts    [description]
     * @param  [type] $content [description]
     */
    function zvc_render_shortcode($atts, $content = null) {
        ob_start();
        extract(shortcode_atts( array(
            'meeting_id' => '#',
            'title' => 'Start Video',
            'id' => 'zoom_video_uri',
            'class' => 'zoom_video_uri',
            'target' => '_self'
            ), $atts
        ) );

        $content = '<a id="'.esc_html( $id ).'" class="'.esc_html( $class ).'" target="'.$target.'" href="'. esc_html( $meeting_id ).'">'. esc_html( $title ).'</a>';
        $content .= ob_get_clean();
        return $content;
    }

}

function ZVC_Shortcode() {
    return Zoom_Video_Conferencing_Admin_Shortcode::getInstance();
}

ZVC_Shortcode();