<?php
/**
 * Shortcodes Controller
 *
 * @since   3.0.0
 * @author  Deepen
 */

class Zoom_Video_Conferencing_Shorcodes {

	public function __construct() {
		add_shortcode( 'zoom_api_link', array( $this, 'render_main' ) );
	}

	function render_main( $atts, $content = null ) {
		ob_start();

		extract( shortcode_atts( array(
			'meeting_id' => 'javascript:void(0);',
		), $atts ) );

		$vanity_uri               = get_option( 'zoom_vanity_url' );
		$GLOBALS['vanity_uri']    = $vanity_uri;
		$meeting                  = $this->fetch_meeting( $meeting_id );
		$GLOBALS['zoom_meetings'] = $meeting;
		if ( $meeting ) {
			wp_enqueue_style( 'video-conferencing-with-zoom-api' );
			//Get Template
			vczapi_get_template( array( 'shortcode/zoom-shortcode.php' ), true );
		} else {
			printf( __( 'Please try again ! Some error occured while trying to fetch meeting with id:  %d', 'video-conferencing-with-zoom-api' ), $meeting_id );
		}

		return ob_get_clean();
	}

	private function fetch_meeting( $meeting_id ) {
		$meeting = json_decode( zoom_conference()->getMeetingInfo( $meeting_id ) );
		if ( ! empty( $meeting->error ) ) {
			return false;
		}

		return $meeting;
	}

}

new Zoom_Video_Conferencing_Shorcodes();