<?php
/**
 * The template for displaying shortcode
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/shortcode/zoom-shortcode.php.
 *
 * @author Deepen.
 * @created_on 11/20/19
 * @since 3.0.0
 */

global $zoom_meetings;
?>

<div class="dpn-zvc-shortcode-op-wrapper">
	<?php
	/**
	 * Hook: vczoom_meeting_before_shortcode
	 */
	do_action( 'vczoom_meeting_before_shortcode' );
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
</div>
