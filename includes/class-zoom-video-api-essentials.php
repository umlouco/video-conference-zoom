<?php
/**
 * Class Containing all the essentail API calls
 *
 * @since  1.1
 */
if( !class_exists('Zoom_Video_Api_Essentials') ) {
	class Zoom_Video_Api_Essentials {

		public function __construct() {	}

		public static function zoom_api_dashboard_meetings() {
			$zoom_api_call =  new ZoomAPI();
			$test = $zoom_api_call->dashboard_get_meetings();
			#var_dump($test);
		}

		public static function zoom_api_add_conference_html() {
			$zoom = new ZoomAPI();
			if ( ! empty( $_POST ) && check_admin_referer( 'zoom_add_meeting_action', 'zoom_add_meeting_nonce' ) ) {
				if(isset($_POST['add_meeting'])) {

					if(isset($_POST['pwd_enabled']) == 'on') {
						$password = isset($_POST["zoom_password_field"]) ? $_POST["zoom_password_field"] : null;
						if (strlen($password) >= '11') {
							$passwordErr = sprintf( __("Your password cannot contain more than 10 characters !", "zoom-video-conference"));
						} else if( ! preg_match("/^[a-zA-Z0-9@_*-]+$/", $password ) ) {
							$passwordErr = sprintf( __("Your password field can only contain %s", "zoom-video-conference"), '<strong>a-z, A-Z, 0-9, @ - _ *</strong>' );
						} else {
							$passwordErr = NULL;
						}
					}

					if( ($_POST['meetingTopic'] != null) && ($_POST['userId'] != null) && ($_POST['start_date']!= null) && ($_POST['start_time']!= null) && ($_POST['duration']!= null) ) {
						$result = $zoom->createAMeeting();
						$data = json_decode($result, true);
						?>
						<div id="message" class="notice notice-success is-dismissible">
							<p><?php _e('Added New Meeting with ID: ', 'zoom-video-conference'); ?><?php echo '<strong>'.$data['id'].'</strong>'; ?></p>
							<p><?php _e('Join URL: ', 'zoom-video-conference'); ?><?php echo '<a href="'.$data['join_url'].'">'.$data['join_url'].'</a>'; ?></p>
							<p><?php _e('Meeting Start Time: ', 'zoom-video-conference'); ?><?php echo '<strong>'.$data['start_time'].'</strong>'; ?></p>
							<p><?php _e('Meeting TimeZone: ', 'zoom-video-conference'); ?><?php echo '<strong>'.$data['timezone'].'</strong>'; ?></p>
							<p><?php _e('Meeting Duration: ', 'zoom-video-conference'); ?><?php echo '<strong>'.$data['duration'].'</strong>'; ?></p>
							<p><?php _e('Thread Created On: ', 'zoom-video-conference'); ?><?php echo '<strong>'.$data['created_at'].'</strong>'; ?></p>
							<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'zoom-video-conference'); ?></span></button>
						</div>
						<script type="text/javascript">
							var reload = function() {
								location.reload();
							};
							setTimeout(reload, 5000);
						</script>
						<?php } else {
							if($passwordErr != NULL) {
								?>
								<div id="message" class="notice notice-error is-dismissible">
									<p><?php printf( __('%s', 'zoom-video-conference'), $passwordErr ); ?></p>
								</div>
								<?php
							}
							?>
							<div id="message" class="notice notice-error is-dismissible">
								<p><?php _e('Required Fields are Missing !!', 'zoom-video-conference'); ?></p>
							</div>
							<?php
						}
					}
				}

				$listUsers = $zoom->listUsers();
				$listingUsers = json_decode($listUsers, true);
				return $listingUsers;
			}

			public static function zoom_api_get_daily_report_html() {
				$months = array( 
					1 => 'January',
					2 => 'February',
					3 => 'March', 
					4 => 'April',  
					5 => 'May', 
					6 => 'June', 
					7 => 'July', 
					8 => 'August', 
					9 => 'September', 
					10 => 'October', 
					11 => 'November', 
					12 => 'December'
					);
				if(isset($_POST['zoom_check_month_year'])) {
					$zoom_monthyear = $_POST['zoom_month_year'];
					if( $zoom_monthyear == NULL || $zoom_monthyear == "" ) {
						$error = sprintf( __("Aww !! Date field cannot be Empty !!", "zoom-video-conference") );
						return $error;
					} else {
						$exploded_data = explode(' ', $zoom_monthyear);
						foreach( $months as $key => $month ) {
							if($exploded_data[0] == $month) {
								$month_int = $key;
							}
						}
						$year = $exploded_data[1];
						$zoom_request = new ZoomAPI();
						$result = $zoom_request->getDailyReport($month_int, $year);
						$decoded_data = json_decode($result);
						return $decoded_data;
					}
				}
			}


			public static function zoom_api_get_account_report_html() {
				if( isset($_POST['zoom_account_from']) && isset($_POST['zoom_account_to']) ) {
					$zoom_account_from = $_POST['zoom_account_from'];
					$zoom_account_to = $_POST['zoom_account_to'];
					if( $zoom_account_from == null || $zoom_account_to == null ) {
						$error = sprintf( __("Aww !! The fields cannot be Empty !!", "zoom-video-conference") );
						return $error;
					} else {
						$zoom_request = new ZoomAPI();
						$result = $zoom_request->getAccountReport($zoom_account_from, $zoom_account_to);
						$decoded_data = json_decode($result);
						return $decoded_data;
					}
				}
			}

		}

		new Zoom_Video_Api_Essentials();

	}

	?>