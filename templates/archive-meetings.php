<?php
/**
 * The template for displaying archive of meetings
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/archive-meetings.php.
 *
 * @author Deepen
 * @since 3.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Before the LOOP archive page
 */
do_action( 'vczapi_before_main_archive_content' );
?>

    <div id="vczapi-primary" class="vczapi-primary container">
        <div class="vczapi-list-zoom-meetings">
            <div class="vczapi-list-zoom-meetings--items">
				<?php if ( have_posts() ) {
					// Start the Loop.
					while ( have_posts() ) {
						the_post();

						do_action( 'vczapi_main_archive_loop' );

						vczapi_get_template_part( 'content', 'meeting' );
					}
				} else {
					echo "<p>" . __( 'No Meetings found.', 'video-conferencing-with-zoom-api' ) . "</p>";
				}
				?>
            </div>
        </div>
    </div>

<?php
/**
 * After loop call
 */
do_action( 'vczapi_after_main_archive_content' );

get_footer();
