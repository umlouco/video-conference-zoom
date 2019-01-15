<?php
/**
 * Shortcodes
 *
 * @author   Deepen
 * @modified 2.1.0
 * @since    2.0.0
 */

//Adding Shortcode
add_shortcode( 'zoom_api_link', 'video_conferencing_zoom_render_shortcode' );
add_action( 'admin_head', 'video_conferencing_zoom_button_render' );

/**
 * Rendering Shortcode Output
 */
function video_conferencing_zoom_render_shortcode( $atts, $content = null ) {
	ob_start();
	extract( shortcode_atts( array(
		'meeting_id' => '#',
		'title'      => 'Start Video',
		'id'         => 'zoom_video_uri',
		'class'      => 'zoom_video_uri',
		'target'     => '_self'
	), $atts
	) );

	$content = '<a id="' . esc_html( $id ) . '" class="' . esc_html( $class ) . '" target="' . $target . '" href="' . esc_html( $meeting_id ) . '">' . esc_html( $title ) . '</a>';
	$content .= ob_get_clean();

	return $content;
}

function video_conferencing_zoom_button_render() {
	//Abort early if the user will never see TinyMCE
	if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) && get_user_option( 'rich_editing' ) == 'true' ) {
		return;
	}

	//Add a callback to regiser our tinymce plugin
	add_filter( "mce_external_plugins", "video_conferencing_zoom_register_tinymce_scripts" );

	// Add a callback to add our button to the TinyMCE toolbar
	add_filter( 'mce_buttons', 'video_conferencing_zoom_add_btn_tinmyce' );
}

//This callback registers our plug-in
function video_conferencing_zoom_register_tinymce_scripts( $plugin_array ) {
	$plugin_array['zvc_shortcode_button'] = ZOOM_VIDEO_CONFERENCE_PLUGIN_JS_PATH . '/video-conferencing-with-zoom-api-shortcode.js';

	return $plugin_array;
}

//This callback adds our button to the toolbar
function video_conferencing_zoom_add_btn_tinmyce( $buttons ) {
	//Add the button ID to the $button array
	$buttons[] = "zvc_shortcode_button";

	return $buttons;
}
