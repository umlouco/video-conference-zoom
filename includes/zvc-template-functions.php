<?php
/**
 * @author Deepen.
 * @created_on 11/20/19
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Function to check if a user is logged in or not
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_check_login() {
	global $zoom;
	if ( ! empty( $zoom ) && ! empty( $zoom['site_option_logged_in'] ) ) {
		if ( is_user_logged_in() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return true;
	}
}

/**
 * Function to view featured image on the post
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_featured_image() {
	vczapi_get_template( 'fragments/image.php', true );
}

/**
 * Function to view main content i.e title and main content
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_main_content() {
	vczapi_get_template( 'fragments/content.php', true );
}

/**
 * Function to add in the counter
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_countdown_timer() {
	vczapi_get_template( 'fragments/countdown-timer.php', true );
}

/**
 * Function to show meeting details
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_meeting_details() {
	vczapi_get_template( 'fragments/meeting-details.php', true );
}

/**
 * Control State of the meeting by author from frontend
 */
function video_conference_zoom_meeting_end_author() {
	global $post;
	$meeting = get_post_meta( $post->ID, '_meeting_zoom_details', true );
	$author  = vczapi_check_author( $post->ID );
	if ( ! $author ) {
		return;
	}

	$data = array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
		'zvc_security' => wp_create_nonce( "_nonce_zvc_security" ),
		'lang'         => array(
			'confirm_end' => __( "Are you sure you want to end this meeting ? Users won't be able to join this meeting shown from the shortcode.", "video-conferencing-with-zoom-api" )
		)
	);
	wp_localize_script( 'video-conferencing-with-zoom-api', 'vczapi_state', $data );
	?>
    <div class="dpn-zvc-sidebar-state">
		<?php if ( empty( $meeting->state ) ) { ?>
            <a href="javascript:void(0);" class="vczapi-meeting-state-change" data-type="post_type" data-state="end" data-postid="<?php echo $post->ID; ?>" data-id="<?php echo $meeting->id ?>"><?php _e( 'End Meeting ?', 'video-conferencing-with-zoom-api' ); ?></a>
		<?php } else { ?>
            <a href="javascript:void(0);" class="vczapi-meeting-state-change" data-type="post_type" data-state="resume" data-postid="<?php echo $post->ID; ?>" data-id="<?php echo $meeting->id ?>"><?php _e( 'Enable Meeting Join ?', 'video-conferencing-with-zoom-api' ); ?></a>
		<?php } ?>
        <p><?php _e( 'You are seeing this because you are the author of this post.', 'video-conferencing-with-zoom-api' ); ?></p>
    </div>
	<?php
}

/**
 * Function to show meeting join links
 *
 * @author Deepen
 * @since 3.0.0
 */
function video_conference_zoom_meeting_join() {
	global $zoom;

	if ( empty( $zoom['api']->state ) ) {
		$data = array(
			'ajaxurl'    => admin_url( 'admin-ajax.php' ),
			'start_date' => $zoom['start_date'],
			'timezone'   => $zoom['timezone'],
			'post_id'    => get_the_ID(),
			'page'       => 'single-meeting'
		);
		wp_localize_script( 'video-conferencing-with-zoom-api', 'mtg_data', $data );
	}
}

/**
 * Generate join links
 *
 * @param $zoom_meeting
 *
 * @since 3.0.0
 *
 * @author Deepen
 */
function video_conference_zoom_meeting_join_link( $zoom_meeting ) {
	$disable_app_join = apply_filters( 'vczoom_join_meeting_via_app_disable', false );
	if ( ! empty( $zoom_meeting->join_url ) && ! $disable_app_join ) {
		?>
        <a target="_blank" href="<?php echo esc_url( $zoom_meeting->join_url ); ?>" class="btn btn-join-link btn-join-via-app"><?php echo apply_filters( 'vczoom_join_meeting_via_app_text', __( 'Join Meeting via Zoom App', 'video-conferencing-with-zoom-api' ) ); ?></a>
		<?php
	}

	if ( wp_doing_ajax() ) {
		$post_id         = absint( filter_input( INPUT_POST, 'post_id' ) );
		$meeting_details = get_post_meta( $post_id, '_meeting_fields', true );
		if ( ! empty( $zoom_meeting->id ) && ! empty( $post_id ) && empty( $meeting_details['site_option_browser_join'] ) ) {
			if ( ! empty( $zoom_meeting->password ) ) {
				echo vczapi_get_browser_join_links( $post_id, $zoom_meeting->id, $zoom_meeting->password );
			} else {
				echo vczapi_get_browser_join_links( $post_id, $zoom_meeting->id );
			}
		}
	}
}

/**
 * Generate join links
 *
 * @param $zoom_meetings
 *
 * @throws Exception
 * @since 3.0.0
 *
 * @author Deepen
 */
function video_conference_zoom_shortcode_join_link( $zoom_meetings ) {
	global $vanity_uri;

	if ( empty( $zoom_meetings ) ) {
		echo "<p>" . __( 'Meeting is not defined. Try updating this meeting', 'video-conferencing-with-zoom-api' ) . "</p>";

		return;
	}

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

	$now               = new DateTime( 'now -1 hour', new DateTimeZone( $zoom_meetings->timezone ) );
	$closest_occurence = false;
	if ( ! empty( $zoom_meetings->type ) && $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
		foreach ( $zoom_meetings->occurrences as $occurrence ) {
			if ( $occurrence->status === "available" ) {
				$start_date = new DateTime( $occurrence->start_time, new DateTimeZone( $zoom_meetings->timezone ) );
				if ( $start_date >= $now ) {
					$closest_occurence = $occurrence->start_time;
					break;
				}
			}
		}
	}

	$start_time = ! empty( $closest_occurence ) ? $closest_occurence : $zoom_meetings->start_time;
	$start_time = new DateTime( $start_time, new DateTimeZone( $zoom_meetings->timezone ) );
	$start_time->setTimezone( new DateTimeZone( $zoom_meetings->timezone ) );
	if ( $now <= $start_time ) {
		unset( $GLOBALS['meetings'] );
		$GLOBALS['meetings'] = array(
			'join_uri'    => apply_filters( 'vczoom_join_meeting_via_app_shortcode', $join_uri, $zoom_meetings ),
			'browser_url' => apply_filters( 'vczoom_join_meeting_via_browser_shortcode', $browser_url, $zoom_meetings )
		);
		vczapi_get_template( 'shortcode/join-links.php', true, false );
	}
}

/**
 * Render Zoom Meeting ShortCode table in frontend
 *
 * @param $zoom_meetings
 *
 * @author Deepen
 *
 * @since 3.0.0
 */
if ( ! function_exists( 'video_conference_zoom_shortcode_table' ) ) {
	function video_conference_zoom_shortcode_table( $zoom_meetings ) {
		?>
        <table class="vczapi-shortcode-meeting-table">
            <tr class="vczapi-shortcode-meeting-table--row1">
                <td><?php _e( 'Meeting ID', 'video-conferencing-with-zoom-api' ); ?></td>
                <td><?php echo $zoom_meetings->id; ?></td>
            </tr>
            <tr class="vczapi-shortcode-meeting-table--row2">
                <td><?php _e( 'Topic', 'video-conferencing-with-zoom-api' ); ?></td>
                <td><?php echo $zoom_meetings->topic; ?></td>
            </tr>
            <tr class="vczapi-shortcode-meeting-table--row3">
                <td><?php _e( 'Meeting Status', 'video-conferencing-with-zoom-api' ); ?></td>
                <td>
					<?php echo $zoom_meetings->status; ?>
                    <p class="small-description"><?php _e( 'Refresh is needed to change status.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
			<?php
			if ( $zoom_meetings->type === 8 && ! empty( $zoom_meetings->occurrences ) ) {
				?>
                <tr class="vczapi-shortcode-meeting-table--row4">
                    <td><?php _e( 'Type', 'video-conferencing-with-zoom-api' ); ?></td>
                    <td><?php _e( 'Recurring Meeting', 'video-conferencing-with-zoom-api' ); ?></td>
                </tr>
                <tr class="vczapi-shortcode-meeting-table--row5">
                    <td><?php _e( 'Start Time', 'video-conferencing-with-zoom-api' ); ?></td>
                    <td>
                        <ul class="vczapi-occurrence-ul-listings">
							<?php
							foreach ( $zoom_meetings->occurrences as $occurence ) {
								if ( $occurence->status === "available" ) {
									?>
                                    <li><?php echo vczapi_dateConverter( $occurence->start_time, $zoom_meetings->timezone, 'F j, Y @ g:i a' ); ?></li>
									<?php
								}
							}
							?>
                        </ul>
                    </td>
                </tr>
				<?php
			} else {
				?>
                <tr class="vczapi-shortcode-meeting-table--row6">
                    <td><?php _e( 'Start Time', 'video-conferencing-with-zoom-api' ); ?></td>
                    <td><?php echo vczapi_dateConverter( $zoom_meetings->start_time, $zoom_meetings->timezone, 'F j, Y @ g:i a' ); ?></td>
                </tr>
			<?php } ?>
            <tr class="vczapi-shortcode-meeting-table--row7">
                <td><?php _e( 'Timezone', 'video-conferencing-with-zoom-api' ); ?></td>
                <td><?php echo $zoom_meetings->timezone; ?></td>
            </tr>
			<?php if ( ! empty( $zoom_meetings->duration ) ) { ?>
                <tr class="zvc-table-shortcode-duration">
                    <td><?php _e( 'Duration', 'video-conferencing-with-zoom-api' ); ?></td>
                    <td><?php echo $zoom_meetings->duration; ?></td>
                </tr>
				<?php
			}

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
}

if ( ! function_exists( 'video_conference_zoom_output_content_start' ) ) {
	function video_conference_zoom_output_content_start() {
		vczapi_get_template( 'global/wrap-start.php', true );
	}
}

if ( ! function_exists( 'video_conference_zoom_output_content_end' ) ) {
	function video_conference_zoom_output_content_end() {
		vczapi_get_template( 'global/wrap-end.php', true );
	}
}

/**
 * Get a slug identifying the current theme.
 *
 * @return string
 * @since 3.0.2
 */
function video_conference_zoom_get_current_theme_slug() {
	return apply_filters( 'video_conference_zoom_theme_slug_for_templates', get_option( 'template' ) );
}