<?php

require ZOOM_VIDEO_CONFERENCE_PLUGIN_DIR_PATH . '/vendor/autoload.php';

use \Firebase\JWT\JWT;

/**
 * Class Connecting Zoom APi V2
 *
 * @since   2.0
 * @author  Deepen
 * @modifiedn
 */
if ( ! class_exists( 'Zoom_Video_Conferencing_Api' ) ) {

	class Zoom_Video_Conferencing_Api {

		public $zoom_api_key;

		public $zoom_api_secret;

		protected static $_instance;

		private $api_url = 'https://api.zoom.us/v2/';

		/**
		 * Create only one instance so that it may not Repeat
		 *
		 * @since 2.0.0
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct( $zoom_api_key = '', $zoom_api_secret = '' ) {
			$this->zoom_api_key    = $zoom_api_key;
			$this->zoom_api_secret = $zoom_api_secret;
		}

		protected function sendRequest( $calledFunction, $data, $request = "GET" ) {
			$request_url = $this->api_url . $calledFunction;
			$postFields  = "";
			if ( ! empty( $data ) ) {
				if ( $request == "GET" ) {
					$postFields  = http_build_query( $data );
					$request_url = $request_url . '?' . $postFields;
				} else {
					$postFields = json_encode( $data );
				}
			}

			/*Preparing Query...*/
			$ch = curl_init( $request_url );
//			curl_setopt( $ch, CURLOPT_URL, $request_url );
			if ( ! empty( $data ) ) {
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $request );
				curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			} else {
				curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $request );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			}

			curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer ' . $this->generateJWTKey(),
				'Content-Type: application/json'
			) );

			$response = curl_exec( $ch );

			if ( ! $response ) {
				return false;
			}

			return $response;
		}

		//function to generate JWT
		private function generateJWTKey() {
			$key    = $this->zoom_api_key;
			$secret = $this->zoom_api_secret;

			$token = array(
				"iss" => $key,
				"exp" => time() + 3600 //1  hour
			);

			return JWT::encode( $token, $secret );
		}

		/**
		 * Create a User
		 *
		 * @return Object
		 */
		public function createAUser( $action, $email, $first_name, $last_name, $type ) {
			$createAUserArray              = array();
			$createAUserArray['action']    = $action;
			$createAUserArray['user_info'] = array(
				'email'      => $email,
				'type'       => $type,
				'first_name' => $first_name,
				'last_name'  => $last_name
			);

			return $this->sendRequest( 'users', $createAUserArray, "POST" );
		}

		/**
		 * User Function to List
		 *
		 * @return Array
		 */
		public function listUsers() {
			$listUsersArray              = array();
			$listUsersArray['page_size'] = 300;

			return $this->sendRequest( 'users', $listUsersArray, "GET" );
		}

		/**
		 * Get A users info by user Id
		 *
		 * @return JSON DATA
		 */
		public function getUserInfo( $user_id ) {
			$getUserInfoArray = array();

			return $this->sendRequest( 'users/' . $user_id, $getUserInfoArray );
		}

		/**
		 * Delete a User
		 *
		 * @return Boolean
		 */
		public function deleteAUser( $userid ) {
			$deleteAUserArray       = array();
			$deleteAUserArray['id'] = $userid;

			return $this->sendRequest( 'user/delete', $deleteAUserArray );
		}

		/**
		 * Get Meetings
		 *
		 * @return ARRAY
		 */
		public function listMeetings( $host_id ) {
			$listMeetingsArray              = array();
			$listMeetingsArray['page_size'] = 300;

			return $this->sendRequest( 'users/' . $host_id . '/meetings', $listMeetingsArray, "GET" );
		}

		/**
		 * Create A meeting API
		 *
		 * @param  ARRAY $data
		 *
		 * @return ARRAY
		 */
		public function createAMeeting( $data = array() ) {
			$post_time  = $data['start_date'];
			$start_time = gmdate( "Y-m-d\TH:i:s\Z", strtotime( $post_time ) );

			$createAMeetingArray = array();

			if ( count( $data['alternative_host_ids'] ) > 1 ) {
				$alternative_host_ids = implode( ",", $data['alternative_host_ids'] );
			} else {
				$alternative_host_ids = $data['alternative_host_ids'][0];
			}

			$createAMeetingArray['topic']      = $data['meetingTopic'];
			$createAMeetingArray['agenda']     = $data['agenda'];
			$createAMeetingArray['type']       = ! empty( $data['type'] ) ? $data['type'] : 2; //Scheduled
			$createAMeetingArray['start_time'] = $start_time;
			$createAMeetingArray['timezone']   = $data['timezone'];
			$createAMeetingArray['password']   = $data['password'] ? $data['password'] : "";
			$createAMeetingArray['duration']   = $data['duration'];
			$createAMeetingArray['settings']   = array(
				'join_before_host'  => $data['join_before_host'] ? true : false,
				'host_video'        => $data['option_host_video'] ? true : false,
				'participant_video' => $data['option_participants_video'] ? true : false,
				'cn_meeting'        => $data['option_cn_meeting'] ? true : false,
				'in_meeting'        => $data['option_in_meeting'] ? true : false,
				'enforce_login'     => $data['option_enforce_login'] ? true : false,
				'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
			);

			return $this->sendRequest( 'users/' . $data['userId'] . '/meetings', $createAMeetingArray, "POST" );
		}

		/**
		 * Updating Meeting Info
		 *
		 * @param $update_data
		 *
		 * @return JSON
		 */
		public function updateMeetingInfo( $update_data = array() ) {
			$post_time  = $update_data['start_date'];
			$start_time = gmdate( "Y-m-d\TH:i:s\Z", strtotime( $post_time ) );

			$updateMeetingInfoArray = array();

			if ( count( $update_data['alternative_host_ids'] ) > 1 ) {
				$alternative_host_ids = implode( ",", $update_data['alternative_host_ids'] );
			} else {
				$alternative_host_ids = $update_data['alternative_host_ids'][0];
			}

			$updateMeetingInfoArray['topic']      = $update_data['topic'];
			$updateMeetingInfoArray['agenda']     = $update_data['agenda'];
			$updateMeetingInfoArray['type']       = ! empty( $update_data['type'] ) ? $update_data['type'] : 2; //Scheduled
			$updateMeetingInfoArray['start_time'] = $start_time;
			$updateMeetingInfoArray['timezone']   = $update_data['timezone'];
			$updateMeetingInfoArray['password']   = $update_data['password'] ? $update_data['password'] : "";
			$updateMeetingInfoArray['duration']   = $update_data['duration'];
			$updateMeetingInfoArray['settings']   = array(
				'join_before_host'  => $update_data['option_jbh'] ? true : false,
				'host_video'        => $update_data['option_host_video'] ? true : false,
				'participant_video' => $update_data['option_participants_video'] ? true : false,
				'cn_meeting'        => $update_data['option_cn_meeting'] ? true : false,
				'in_meeting'        => $update_data['option_in_meeting'] ? true : false,
				'enforce_login'     => $update_data['option_enforce_login'] ? true : false,
				'alternative_hosts' => isset( $alternative_host_ids ) ? $alternative_host_ids : ""
			);

			return $this->sendRequest( 'meetings/' . $update_data['meeting_id'], $updateMeetingInfoArray, "PATCH" );
		}

		/**
		 * Get a Meeting Info
		 *
		 * @param  [INT] $id
		 * @param  [STRING] $host_id
		 *
		 * @return JSON
		 */
		public function getMeetingInfo( $id ) {
			$getMeetingInfoArray = array();

			return $this->sendRequest( 'meetings/' . $id, $getMeetingInfoArray, "GET" );
		}

		/**
		 * Delete A Meeting
		 *
		 * @param $meeting_id [int]
		 * @param $host_id    [string]
		 *
		 * @return array
		 */
		public function deleteAMeeting( $meeting_id ) {
			$deleteAMeetingArray = array();

			return $this->sendRequest( 'meetings/' . $meeting_id, $deleteAMeetingArray, "DELETE" );
		}

		/*Functions for management of reports*/
		/**
		 * Get daily account reports by month
		 *
		 * @param $month
		 * @param $year
		 *
		 * @return bool|mixed
		 */
		public function getDailyReport( $month, $year ) {
			$getDailyReportArray          = array();
			$getDailyReportArray['year']  = $year;
			$getDailyReportArray['month'] = $month;

			return $this->sendRequest( 'report/daily', $getDailyReportArray, "GET" );
		}

		/**
		 * Get ACcount Reports
		 *
		 * @param $zoom_account_from
		 * @param $zoom_account_to
		 *
		 * @return array
		 */
		public function getAccountReport( $zoom_account_from, $zoom_account_to ) {
			$getAccountReportArray              = array();
			$getAccountReportArray['from']      = $zoom_account_from;
			$getAccountReportArray['to']        = $zoom_account_to;
			$getAccountReportArray['page_size'] = 300;

			return $this->sendRequest( 'report/users', $getAccountReportArray, "GET" );
		}

		public function registerWebinarParticipants( $webinar_id, $first_name, $last_name, $email ) {
			$postData               = array();
			$postData['first_name'] = $first_name;
			$postData['last_name']  = $last_name;
			$postData['email']      = $email;

			return $this->sendRequest( 'webinars/' . $webinar_id . '/registrants', $postData, "POST" );
		}

		/**
		 * List webinars
		 *
		 * @param $userId
		 *
		 * @return bool|mixed
		 */
		public function listWebinar( $userId ) {
			$postData              = array();
			$postData['page_size'] = 300;

			return $this->sendRequest( 'users/' . $userId . '/webinars', $postData, "GET" );
		}

		/**
		 * List Webinar Participants
		 *
		 * @param $webinarId
		 *
		 * @return bool|mixed
		 */
		public function listWebinarParticipants( $webinarId ) {
			$postData              = array();
			$postData['page_size'] = 300;

			return $this->sendRequest( 'webinars/' . $webinarId . '/registrants', $postData, "GET" );
		}

		/**
		 * Get recording by meeting ID
		 *
		 * @param $meetingId
		 *
		 * @return bool|mixed
		 */
		public function recordingsByMeeting( $meetingId ) {
			return $this->sendRequest( 'meetings/' . $meetingId . '/recordings', false, "GET" );
		}

		/**
		 * Get all recordings by USER ID
		 *
		 * @param $host_id
		 *
		 * @return bool|mixed
		 */
		public function listRecording( $host_id, $data = array() ) {
			$postData = array();
			$from     = date( 'Y-m-d', strtotime( '-1 year', time() ) );
			$to       = date( 'Y-m-d' );

			$postData['from'] = ! empty( $data['from'] ) ? $data['from'] : $from;
			$postData['to']   = ! empty( $data['to'] ) ? $data['to'] : $to;

			return $this->sendRequest( 'users/' . $host_id . '/recordings', $postData, "GET" );
		}
	}

	function zoom_conference() {
		return Zoom_Video_Conferencing_Api::instance();
	}

	zoom_conference();
}
