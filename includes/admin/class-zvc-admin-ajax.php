<?php
/**
 * Class for all the administration ajax calls
 *
 * @since   2.0.0
 * @author  Deepen
 */

class Zoom_Video_Conferencing_Admin_Ajax {

	public function __construct() {
		//Delete Meeting
		add_action( 'wp_ajax_zvc_delete_meeting', array( $this, 'delete_meeting' ) );
		add_action( 'wp_ajax_zvc_bulk_meetings_delete', array( $this, 'delete_bulk_meeting' ) );
		add_action( 'wp_ajax_zoom_dimiss_notice', array( $this, 'dismiss_notice' ) );
	}

	/**
	 * Delete a Meeting
	 *
	 * @author   Deepen
	 * @since    2.0.0
	 * @modified 2.1.0
	 */
	public function delete_meeting() {
		check_ajax_referer( '_nonce_zvc_security', 'security' );

		$meeting_id = $_POST['meeting_id'];
		$host_id    = $_POST['host_id'];
		if ( $meeting_id && $host_id ) {
			zoom_conference()->deleteAMeeting( $meeting_id, $host_id );
			wp_send_json( array( 'error' => 0, 'msg' => __( "Deleted meeting.", "video-conferencing-with-zoom-api" ) ) );
		} else {
			wp_send_json( array( 'error' => 1, 'msg' => __( "An error occured. Host ID and Meeting ID not defined properly.", "video-conferencing-with-zoom-api" ) ) );
		}

		wp_die();
	}

	/**
	 * Delete Meeting in Bulk
	 *
	 * @since    1.0.0
	 * @modified 2.1.0
	 */
	public function delete_bulk_meeting() {
		check_ajax_referer( '_nonce_zvc_security', 'security' );

		$deleted     = false;
		$meeting_ids = $_POST['meetings_id'];
		$host_id     = $_POST['host_id'];
		if ( $meeting_ids && $host_id ) {
			$meeting_count = count( $meeting_ids );
			foreach ( $meeting_ids as $meeting_id ) {
				json_decode( zoom_conference()->deleteAMeeting( $meeting_id, $host_id ) );
				$deleted = true;
			}

			if ( $deleted ) {
				wp_send_json( array( 'error' => 0, 'msg' => sprintf( __( "Deleted %d Meeting(s).", "video-conferencing-with-zoom-api" ), $meeting_count ) ) );
			}
		} else {
			wp_send_json( array( 'error' => 1, 'msg' => __( "You need to select a data in order to initiate this action." ) ) );
		}

		wp_die();
	}

	/**
	 * Dismiss admin notice
	 */
	public function dismiss_notice() {
		update_option( 'zoom_api_notice', 1 );
		wp_send_json( 1 );
		wp_die();
	}
}

new Zoom_Video_Conferencing_Admin_Ajax();
