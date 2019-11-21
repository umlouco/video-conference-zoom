<?php
/**
 * The template for displaying meeting countdown timer
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/fragments/countdown-timer.php.
 *
 * @author Deepen.
 * @created_on 11/19/19
 */

global $zoom;

if ( ! empty( $zoom['start_date'] ) ) {
	?>
    <div class="dpn-zvc-sidebar-box">
        <div class="dpn-zvc-timer" id="dpn-zvc-timer" data-date="<?php echo $zoom['start_date']; ?>"><?php _e( 'Loading...', 'video-conferencing-with-zoom-api' ); ?></div>
    </div>
	<?php
}