<?php
/**
 * The Template for displaying list of recordings via Host ID
 *
 * This template can be overridden by copying it to yourtheme/video-conferencing-zoom/shortcode/zoom-recordings.php.
 *
 * @package    Video Conferencing with Zoom API/Templates
 * @version     3.5.0
 */

global $zoom_recordings;
?>
    <table id="vczapi-recordings-list-table" class="vczapi-recordings-list-table vczapi-user-meeting-list">
        <thead>
        <tr>
            <th><?php _e( 'Meeting ID', 'video-conferencing-with-zoom-api' ); ?></th>
            <th><?php _e( 'Topic', 'video-conferencing-with-zoom-api' ); ?></th>
            <th><?php _e( 'Duration', 'video-conferencing-with-zoom-api' ); ?></th>
            <th><?php _e( 'Recorded', 'video-conferencing-with-zoom-api' ); ?></th>
            <th><?php _e( 'Size', 'video-conferencing-with-zoom-api' ); ?></th>
            <th><?php _e( 'Action', 'video-conferencing-with-zoom-api' ); ?></th>
        </tr>
        </thead>
        <tbody>
		<?php
		foreach ( $zoom_recordings->meetings as $recording ) {
			?>
            <tr>
                <td><?php echo $recording->id; ?></td>
                <td><?php echo $recording->topic; ?></td>
                <td><?php echo $recording->duration; ?></td>
                <td><?php echo date( 'F j, Y, g:i a', strtotime( $recording->start_time ) ); ?></td>
                <td><?php echo vczapi_filesize_converter( $recording->total_size ); ?></td>
                <td>
                    <a href="javascript:void(0);" class="vczapi-view-recording" data-recording-id="<?php echo $recording->id; ?>"><?php _e( 'View Recordings', 'video-conferencing-with-zoom-api' ); ?></a>
                    <div class="vczapi-modal"></div>
                </td>
            </tr>
			<?php
		}
		?>
        </tbody>
    </table>

<?php
if ( ! empty( $zoom_recordings ) ) {
	vczapi_zoom_api_paginator( $zoom_recordings, 'recordings' );
}
?>