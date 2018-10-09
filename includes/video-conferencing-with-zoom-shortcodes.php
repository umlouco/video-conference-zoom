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