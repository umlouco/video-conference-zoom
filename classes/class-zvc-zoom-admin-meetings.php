<?php

/**
 * Meetings Controller
 *
 * @since   2.1.0
 * @author  Deepen
 */
class Zoom_Video_Conferencing_Admin_Meetings {

	public static $message = '';
	public $settings;
	public static $api_version = ZOOM_VIDEO_CONFERENCE_APIVERSION;

	public function __construct() {
	}

	/**
	 * View list meetings page
	 *
	 * @since   1.0.0
	 * @changes in CodeBase
	 * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
	 */
	public static function list_meetings() {
		wp_enqueue_script( 'video-conferencing-with-zoom-api-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-select2-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-datable-js' );

		wp_enqueue_style( 'video-conferencing-with-zoom-api' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api-select2' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api-datable' );

		//Get Template
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_VIEWS_PATH . '/admin/tpl-list-meetings.php';
	}

	/**
	 * Add Meetings Page
	 *
	 * @since    1.0.0
	 * @modified 2.1.0
	 * @author   Deepen Bajracharya <dpen.connectify@gmail.com>
	 */
	public static function add_meeting() {
		wp_enqueue_script( 'video-conferencing-with-zoom-api-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-select2-js' );
		wp_enqueue_script( 'video-conferencing-with-zoom-api-timepicker-js' );

		wp_enqueue_style( 'video-conferencing-with-zoom-api-timepick' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api' );
		wp_enqueue_style( 'video-conferencing-with-zoom-api-select2' );

		//Edit a Meeting
		if ( isset( $_GET['edit'] ) && isset( $_GET['host_id'] ) ) {
			if ( isset( $_POST['update_meeting'] ) ) {
				self::update_meeting();
			}

			//Get Editin Template
			require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_VIEWS_PATH . '/admin/tpl-edit-meeting.php';
		} else {
			if ( isset( $_POST['create_meeting'] ) ) {
				self::create_meeting();
			}

			//Get Template
			require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_VIEWS_PATH . '/admin/tpl-add-meetings.php';
		}
	}

	/**
	 * Update Meeting
	 *
	 * @since  2.1.0
	 * @author Deepen
	 */
	private static function update_meeting() {
		check_admin_referer( '_zoom_update_meeting_nonce_action', '_zoom_update_meeting_nonce' );

		if ( ! empty( self::$api_version ) && self::$api_version == 2 ) {
			$update_meeting_arr = array(
				'meeting_id'                => filter_input( INPUT_POST, 'meeting_id' ),
				'topic'                     => filter_input( INPUT_POST, 'meetingTopic' ),
				'agenda'                    => filter_input( INPUT_POST, 'agenda' ),
				'start_date'                => filter_input( INPUT_POST, 'start_date' ),
				'timezone'                  => filter_input( INPUT_POST, 'timezone' ),
				'password'                  => filter_input( INPUT_POST, 'password' ),
				'duration'                  => filter_input( INPUT_POST, 'duration' ),
				'option_jbh'                => filter_input( INPUT_POST, 'join_before_host' ),
				'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
				'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
				'option_cn_meeting'         => filter_input( INPUT_POST, 'option_cn_meeting' ),
				'option_in_meeting'         => filter_input( INPUT_POST, 'option_in_meeting' ),
				'option_enforce_login'      => filter_input( INPUT_POST, 'option_enforce_login' ),
				'alternative_host_ids'      => filter_input( INPUT_POST, 'alternative_host_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY )
			);
		} else {
			$update_meeting_arr = array(
				'meeting_id'                => filter_input( INPUT_POST, 'meeting_id' ),
				'host_id'                   => filter_input( INPUT_POST, 'userId' ),
				'topic'                     => filter_input( INPUT_POST, 'meetingTopic' ),
				'start_date'                => filter_input( INPUT_POST, 'start_date' ),
				'timezone'                  => filter_input( INPUT_POST, 'timezone' ),
				'duration'                  => filter_input( INPUT_POST, 'duration' ),
				'option_jbh'                => filter_input( INPUT_POST, 'join_before_host' ),
				'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
				'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
				'option_cn_meeting'         => filter_input( INPUT_POST, 'option_cn_meeting' ),
				'option_in_meeting'         => filter_input( INPUT_POST, 'option_in_meeting' ),
				'option_enforce_login'      => filter_input( INPUT_POST, 'option_enforce_login' )
			);
		}

		$meeting_updated = json_decode( zoom_conference()->updateMeetingInfo( $update_meeting_arr ) );
		if ( ! empty( $meeting_updated->error ) ) {
			self::set_message( 'error', $meeting_updated->error->message );
		} else {
			self::set_message( 'updated', __( "Updated meeting.", "video-conferencing-with-zoom-api" ) );

			/**
			 * Fires after meeting has been updated
			 *
			 * @since  2.0.1
			 *
			 * @param meeting_id
			 */
			do_action( 'zvc_after_update_meeting', $meeting_updated->id );

			#zvc_redirect( '?page=zoom-video-conferencing&host_id=' . $update_meeting_arr['host_id'] );
			#exit;
		}
	}

	/**
	 * Create a new Meeting
	 *
	 * @since  2.1.0
	 * @author Deepen
	 */
	private static function create_meeting() {
		check_admin_referer( '_zoom_add_meeting_nonce_action', '_zoom_add_meeting_nonce' );

		if ( ! empty( self::$api_version ) && self::$api_version == 2 ) {
			$create_meeting_arr = array(
				'userId'                    => filter_input( INPUT_POST, 'userId' ),
				'meetingTopic'              => filter_input( INPUT_POST, 'meetingTopic' ),
				'agenda'                    => filter_input( INPUT_POST, 'agenda' ),
				'start_date'                => filter_input( INPUT_POST, 'start_date' ),
				'timezone'                  => filter_input( INPUT_POST, 'timezone' ),
				'password'                  => filter_input( INPUT_POST, 'password' ),
				'duration'                  => filter_input( INPUT_POST, 'duration' ),
				'join_before_host'          => filter_input( INPUT_POST, 'join_before_host' ),
				'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
				'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
				'option_cn_meeting'         => filter_input( INPUT_POST, 'option_cn_meeting' ),
				'option_in_meeting'         => filter_input( INPUT_POST, 'option_in_meeting' ),
				'option_enforce_login'      => filter_input( INPUT_POST, 'option_enforce_login' ),
				'alternative_host_ids'      => filter_input( INPUT_POST, 'alternative_host_ids', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY )
			);
		} else {
			$create_meeting_arr = array(
				'userId'                    => filter_input( INPUT_POST, 'userId' ),
				'meetingTopic'              => filter_input( INPUT_POST, 'meetingTopic' ),
				'start_date'                => filter_input( INPUT_POST, 'start_date' ),
				'timezone'                  => filter_input( INPUT_POST, 'timezone' ),
				'password'                  => filter_input( INPUT_POST, 'password' ),
				'duration'                  => filter_input( INPUT_POST, 'duration' ),
				'join_before_host'          => filter_input( INPUT_POST, 'join_before_host' ),
				'option_host_video'         => filter_input( INPUT_POST, 'option_host_video' ),
				'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
				'option_cn_meeting'         => filter_input( INPUT_POST, 'option_cn_meeting' ),
				'option_in_meeting'         => filter_input( INPUT_POST, 'option_in_meeting' ),
				'option_enforce_login'      => filter_input( INPUT_POST, 'option_enforce_login' )
			);
		}


		$meeting_created = json_decode( zoom_conference()->createAMeeting( $create_meeting_arr ) );
		if ( ! empty( $meeting_created->error ) ) {
			self::set_message( 'error', $meeting_created->error->message );
		} else if ( ! empty( $meeting_created->code ) && $meeting_created->code == 1113 ) {
			self::set_message( 'error', $meeting_created->message );
		} else {
			self::set_message( 'updated', sprintf( __( "Created meeting %s at %s. Join %s", "video-conferencing-with-zoom-api" ), $meeting_created->topic, $meeting_created->created_at, "<a target='_blank' href='" . $meeting_created->join_url . "'>Here</a>" ) );

			/**
			 * Fires after meeting has been Created
			 *
			 * @since  2.0.1
			 *
			 * @param meeting_id , Host_id
			 */
			do_action( 'zvc_after_create_meeting', $meeting_created->id, $meeting_created->host_id );

			#zvc_redirect( '?page=zoom-video-conferencing&host_id=' . $meeting_created->host_id );
			#exit;
		}
	}

	static function get_message() {
		return self::$message;
	}

	static function set_message( $class, $message ) {
		self::$message = '<div class=' . $class . '><p>' . $message . '</p></div>';
	}
}

new Zoom_Video_Conferencing_Admin_Meetings();