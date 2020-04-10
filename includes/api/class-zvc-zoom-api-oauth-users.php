<?php

/**
 * Class ZoomUser
 */
class ZoomUser extends ZoomAuth {

	private static $instance = null;

	public $live_user = null;

	/**
	 * Returns the instance of the class
	 *
	 * @return object $instance
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Construct Function
	 */
	public function __construct() {

		parent::__construct();

		$this->set_live_user();

		$this->init_logic();

		// $this->refresh_access_token();
	}

	/**
	 * Sets wp current useer to live user property
	 */
	public function set_live_user() {

		$this->live_user = wp_get_current_user();
	}

	public function init_logic() {

		$stored = $this->get_stored_zoom_user_info();

		if ( '' == $stored['zoom_user_token_info'] ) {
			add_action( 'init', array( $this, 'grab_zoom_authorization_code' ) );
		}

		// check for any other params
		if ( isset( $_GET['revoke_access'] ) && $_GET['revoke_access'] == 'true' ) {

			$this->deauthorize( $this->live_user->ID );
			// also dont forget to remove the user token and user info from user meta
			$this->remove_stored_zoom_user_info( $this->live_user->ID );

		}

	}

	public function init_shortcodes() {
		add_shortcode( 'zoom_content', array( $this, 'display_zoom_content' ) );
	}

	/**
	 * Grabs the code from the URL parameter
	 *
	 * @return void
	 */
	public function grab_zoom_authorization_code() {

		if ( isset( $_GET['code'] ) && $_GET['code'] != '' ) {

			// $this->authorization_code = sanitize_text_field( $_GET['code'] );
			$this->set( $_GET['code'] );
			$response = $this->make_remote_post_request();

			if ( $response['success'] ) {

				$current_user = wp_get_current_user();
				$this->store_zoom_user_info( $current_user->ID, $response['zoom_user_info'] );
				$this->store_zoom_user_token_info( $current_user->ID, $response['zoom_user_token_info'] );

			} else {

				echo $response['message'];
			}
		} else {
			// do nothing.
		}
	}


	/**
	 * Stores the zoom user info into user meta
	 *
	 * Have put the update in one function previously, but there was a problem
	 * i.e. sometimes only one of them is to be updated and passing both params was less ideal
	 * Therefore, have separated these two functions store_zoom_user_info | store_zoom_user_token_info
	 *
	 * @param int $user_id current user id.
	 * @param array $zoom_user_info zoom user info to be stored.
	 *
	 * @return void
	 */
	public function store_zoom_user_info( $user_id, $zoom_user_info ) {

		update_user_meta( $user_id, 'zoom_user_info', $zoom_user_info ); // update zoom user info

	}

	/**
	 * Stores the zoom user token info into user meta
	 *
	 * @param int $user_id Current user ID.
	 * @param array $zoom_user_token_info Token info to be stored.
	 *
	 * @return void
	 */
	public function store_zoom_user_token_info( $user_id, $zoom_user_token_info ) {

		update_user_meta( $user_id, 'zoom_user_token_info', $zoom_user_token_info ); // update zoom user token info
	}

	/**
	 * Returns the zoom user's info stored in the user meta
	 *
	 * @return array $zoom_user_info
	 */
	public function get_stored_zoom_user_info() {

		$current_user = wp_get_current_user();

		$zoom_user_info       = get_user_meta( $current_user->ID, 'zoom_user_info', true );
		$zoom_user_token_info = get_user_meta( $current_user->ID, 'zoom_user_token_info', true );

		return array(
			'zoom_user_info'       => $zoom_user_info,
			'zoom_user_token_info' => $zoom_user_token_info,
		);
	}

	/**
	 * Removes the zoom user's info stored in the user meta
	 *
	 * @return void
	 */
	public function remove_stored_zoom_user_info( $user_id ) {

		delete_user_meta( $user_id, 'zoom_user_info' );
		delete_user_meta( $user_id, 'zoom_user_token_info' );
	}

	/**
	 * It is the logic behind the zoom_content shortcode
	 *
	 * @return void
	 */
	public function display_zoom_content() {

		if ( isset( $_GET['show'] ) && $_GET['show'] != '' ) {

			$case = sanitize_text_field( $_GET['show'] );

			switch ( $case ) {
				case 'meetings':
					$zoomUserMeetings = new ZoomUserMeetings();
					$zoomUserMeetings->display_user_meetings();
					break;

				default:
					$this->display_zoom_user_info();
					break;
			}
		} else {

			echo $this->display_zoom_user_info();
		}

	}

	/**
	 * Displays the user's Zoom info in a table for now
	 *
	 * @return void
	 */
	public function display_zoom_user_info() {

		// Check if the user is logged in or not
		// Got to have a user logged in to get the user info

		ob_start();

		if ( is_user_logged_in() ) {

			$stored = $this->get_stored_zoom_user_info();

			if ( '' == $stored['zoom_user_token_info'] ) { // seems like there is not token info of this user stored in database

				echo $this->render_zoom_button();

			} else { // token info found for this user

				$zoom_user_infos = $this->get_zoom_user_info_with_access_token( $stored['zoom_user_token_info']['token_type'], $stored['zoom_user_token_info']['access_token'] );

				if ( ! $zoom_user_infos['success'] ) { // some kind of failure happened

					if ( $zoom_user_infos['error_code'] === 124 ) { // $access_token is expired, so get a new one

						$refreshed_access_tokens = $this->refresh_access_token( $stored['zoom_user_token_info']['refresh_token'] );

						if ( $refreshed_access_tokens['success'] ) {

							// Immediately store the newly refreshed token info in the database else you will miss the window
							// If you miss one refresh then - old refresh token will be invalid and you will miss to save new refresh token
							// You will be in limbo
							// Also, convert to array from object before storing
							$refreshed_access_tokens_arr = json_decode( json_encode( $refreshed_access_tokens['zoom_user_token_info'] ), true );

							$this->store_zoom_user_token_info( $this->live_user->ID, $refreshed_access_tokens_arr );

							$stored = $this->get_stored_zoom_user_info();

							// now again try to get the zoom user infos with this new $refreshed access_token
							$zoom_user_info = $this->get_zoom_user_info_with_access_token( $stored['zoom_user_token_info']['token_type'], $stored['zoom_user_token_info']['access_token'] );

							if ( $zoom_user_infos['success'] ) {

								$zoom_user_info = json_decode( $zoom_user_infos['zoom_user_info'] );
								$this->render_user_info_in_table( $zoom_user_info );
							}
						}
					}
				} else { // direct success to get the zoom user info with stored access token

					$this->store_zoom_user_info( $this->live_user->ID, $zoom_user_infos['zoom_user_info'] );
					$this->render_user_info_in_table( $zoom_user_infos['zoom_user_info'] );

				}
			}
		} else { // else render Sign In button

			echo $this->render_zoom_button();
		}

		return ob_get_clean();

	}

	/**
	 * Renders the zoom user info in a table
	 *
	 * @param array $zoom_user_info Zoom user info to be displayed in table format.
	 */
	public function render_user_info_in_table( $zoom_user_info ) {

		global $wp;
		$current_page_url = home_url( $wp->request );

		$meeting_url = add_query_arg( array( 'show' => 'meetings' ), $current_page_url );
		$webinar_url = add_query_arg( array( 'show' => 'webinar' ), $current_page_url );

		?>

        <style>

            ul {
                list-style: none;
            }

            ul li {
                display: inline-block;
                margin: 5px 12px;
            }

            table {
                width: 95% !important;
                max-width: 1200px !important;
            }

            table th.col-key {
                width: 250px;
            }
        </style>

        <ul>
            <li><a href="<?php echo $this->revoke_uri; ?>">Revoke Access</a></li>
            <li><a href="<?php echo $meeting_url; ?>">Meetings</a></li>
            <li><a href="<?php echo $webinar_url; ?>">Webinar</a></li>
            <li><a href="<?php echo $meeting_url; ?>">Scopes</a></li>
            <li><a href="<?php echo $meeting_url; ?>">Settings</a></li>
        </ul>

        <table>

            <thead>
            <tr>
                <th class="col-key">Key</th>
                <th class="col-value">Values</th>
            </tr>
            </thead>

			<?php if ( $zoom_user_info ) : ?>

				<?php foreach ( $zoom_user_info as $key => $value ) : ?>

                    <tr>
                        <td><?php echo $key; ?></td>
                        <td>
							<?php
							if ( is_array( $value ) ) {
								foreach ( $value as $k => $v ) {
									echo $k . ' : ' . $v;
								}
							} else {
								echo $value;
							}
							?>
                        </td>
                    </tr>

				<?php endforeach; ?>

			<?php endif; ?>

        </table>

		<?php

	}
}