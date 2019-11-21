<?php
/**
 * @author Deepen.
 * @created_on 11/20/19
 */

/**
 * Function to check if a user is logged in or not
 * @author Deepen
 * @since 3.0.0
 */
if ( ! function_exists( 'video_conference_zoom_check_login' ) ) {
	function video_conference_zoom_check_login() {
		global $zoom;
		if ( ! empty( $zoom ) && ! empty( $zoom['site_option_logged_in'] ) ) {
			if ( is_user_logged_in() ) {
				return "loggedin";
			} else {
				return false;
			}
		} else {
			return "no_check";
		}
	}
}

/**
 * Function to view featured image on the post
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_featured_image() {
	vczapi_get_template( array( 'fragments/image.php' ), true );
}

/**
 * Function to view main content i.e title and main content
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_main_content() {
	vczapi_get_template( array( 'fragments/content.php' ), true );
}

/**
 * Function to add in the counter
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_countdown_timer() {
	vczapi_get_template( array( 'fragments/countdown-timer.php' ), true );
}

/**
 * Function to show meeting details
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_meeting_details() {
	vczapi_get_template( array( 'fragments/meeting-details.php' ), true );
}

/**
 * Function to show meeting join links
 *
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_meeting_join() {
	vczapi_get_template( array( 'fragments/join-links.php' ), true );
}

/**
 * Generate join links
 * @author Deepen
 * @since 3.0.0
 *
 * @param $zoom_meeting
 */
function video_conference_zoom_meeting_join_link( $zoom_meeting ) {
	global $vanity_enabled;

	if ( empty( $vanity_enabled ) ) {
		$browser_url = 'https://zoom.us/wc/join/';
	} else {
		$browser_url = trailingslashit( $vanity_enabled . '/wc/join/' );
	}

	if ( ! empty( $zoom_meeting->join_url ) ) {
		?>
        <p><a href="<?php echo esc_url( $zoom_meeting->join_url ); ?>" class="btn btn-join-link"><?php echo apply_filters( 'vczoom_join_meeting_via_app_text', __( 'Join Meeting via Zoom App', 'video-conferencing-with-zoom-api' ) ); ?></a></p>
		<?php
	}

	if ( ! empty( $zoom_meeting->id ) ) {
		?>
        <p><a href="<?php echo esc_url( $browser_url . $zoom_meeting->id ); ?>" class="btn btn-join-link"><?php echo apply_filters( 'vczoom_join_meeting_via_app_text', __( 'Join via Web Browser', 'video-conferencing-with-zoom-api' ) ); ?></a></p>
		<?php
	}
}

/**
 * Generate join links
 * @author Deepen
 * @since 3.0.0
 *
 * @param $zoom_meetings
 */
function video_conference_zoom_shortcode_join_link( $zoom_meetings ) {
	global $vanity_uri;

	/**
	 * @TO-DO
	 * 1. Sometimes zoom does not produce https://zoom.us/j uri by default
	 */
	if ( ! empty( $vanity_uri ) ) {
		$browser_url = trailingslashit( $vanity_uri . 'wc/join/' . $zoom_meetings->id );
		$join_uri    = trailingslashit( $vanity_uri . '/j/' . $zoom_meetings->id );
	} else {
		$browser_url = 'https://zoom.us/wc/join/' . $zoom_meetings->id;
		$join_uri    = 'https://zoom.us/j/' . $zoom_meetings->id;
	}

	$params['timezone']   = $zoom_meetings->timezone;
	$params['start_date'] = $zoom_meetings->start_time;
	if ( video_conference_zoom_meeting_check_valid_meeting( $params ) ) {
		?>
        <tr>
            <td colspan="2" class="dpn-zvc-mtglink-no-valid"><?php echo apply_filters( 'vczoom_shortcode_link_not_valid_anymore', __( 'This meeting is no longer valid and cannot be joined !', 'video-conferencing-with-zoom-api' ) ); ?></td>
        </tr>
	<?php } else { ?>
        <tr>
            <td><?php _e( 'Join via Zoom App', 'video-conferencing-with-zoom-api' ); ?></td>
            <td><a class="btn-join-link-shortcode" href="<?php echo $join_uri; ?>" title="<?php echo $zoom_meetings->topic; ?>"><?php _e( 'Join', 'video-conferencing-with-zoom-api' ); ?></a></td>
        </tr>
        <tr>
            <td><?php _e( 'Join via Web Browser', 'video-conferencing-with-zoom-api' ); ?></td>
            <td><a class="btn-join-link-shortcode" href="<?php echo $browser_url; ?>" title="<?php echo $zoom_meetings->topic; ?>"><?php _e( 'Join', 'video-conferencing-with-zoom-api' ); ?></a></td>
        </tr>
		<?php
	}
}

/**
 * Render Zoom Meeting ShortCode table in frontend
 * @since 3.0.0
 * @author Deepen
 *
 * @param $zoom_meetings
 */
function video_conference_zoom_shortcode_table( $zoom_meetings ) {
    ?>
    <table>
        <tr>
            <td>Meeting ID</td>
            <td><?php echo $zoom_meetings->id; ?></td>
        </tr>
        <tr>
            <td>Topic</td>
            <td><?php echo $zoom_meetings->topic; ?></td>
        </tr>
        <tr>
            <td>Meeting Status</td>
            <td>
				<?php echo $zoom_meetings->status; ?>
                <p class="small-description">Refresh is needed to change status.</p>
            </td>
        </tr>
        <tr>
            <td>Start Time</td>
            <td><?php echo date( 'F j, Y @ g:i a', strtotime( $zoom_meetings->start_time ) ); ?></td>
        </tr>
        <tr>
            <td>Duration</td>
            <td><?php echo $zoom_meetings->duration; ?></td>
        </tr>
        <tr>
            <td>Timezone</td>
            <td><?php echo $zoom_meetings->timezone; ?></td>
        </tr>
		<?php
		/**
		 * Hook: vczoom_meeting_shortcode_join_links
		 *
		 * @video_conference_zoom_shortcode_join_link - 10
		 *
		 */
		do_action( 'vczoom_meeting_shortcode_join_links', $zoom_meetings );
		?>
    </table>
	<?php
}

/**
 * Check if meeting is valid from current time for more 15 minutes
 *
 * @param $zoom
 *
 * @author Deepen
 * @since 3.0.0
 *
 * @return bool
 */
function video_conference_zoom_meeting_check_valid_meeting( $zoom ) {
	$meeting_timezone_time = new DateTime( 'now', new DateTimeZone( $zoom['timezone'] ) );
	$meeting_timezone_time->modify( "+15 minutes" );
	$current_time = $meeting_timezone_time->format( 'Y-m-d h:i a' );
	$meeting_time = date( 'Y-m-d h:i a', strtotime( $zoom['start_date'] ) );
	if ( $current_time > $meeting_time ) {
		return false;
	}

	return true;
}
