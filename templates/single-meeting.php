<?php
/**
 * The Template for displaying all single meetings
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/single-meetings.php.
 *
 * @package    Video Conferencing with Zoom API/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
?>
    <div id="dpn-zvc-primary" class="dpn-zvc-primary container">
		<?php
		while ( have_posts() ) {
			the_post();

			if ( video_conference_zoom_check_login() === "no_check" || video_conference_zoom_check_login() === "loggedin" ) {
				vczapi_get_template_part( 'content', 'single-meeting' );
			} else {
				echo "<h3>" . __( 'You do not have enough priviledge to access this page. Please login to continue or contact administrator.', 'video-conferencing-with-zoom-api' ) . "</h3>";
			}
		}
		?>
    </div>
<?php
get_footer();
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
