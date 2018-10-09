<?php
$users = video_conferencing_zoom_api_get_user_transients();
?>
<div id="zvc-cover" style="display: none;"></div>
<div class="wrap">
    <h2><?php _e( "Users", "video-conferencing-with-zoom-api" ); ?></h2> <a href="?page=zoom-video-conferencing-list-users&flush=true"><?php _e( 'Flush User Cache', 'video-conferencing-with-zoom-api' ); ?></a>
    <div id="message" style="display:none;" class="notice notice-success show_on_user_delete_success"></div>
    <div class="message">
		<?php
		$message = self::get_message();
		if ( isset( $message ) && ! empty( $message ) ) {
			echo $message;
		}
		?>
    </div>

	<?php if ( ZOOM_VIDEO_CONFERENCE_APIVERSION == 1 ) { ?>
        <div id="message" class="error">
            <p><strong>Version 1 of the Zoom API is being sunset and will no longer be supported after November 1st, 2018. It is recommended that you select version 2 from <a href="<?php echo admin_url( '/admin.php?page=zoom-video-conferencing-settings' ); ?>">settings</a> page.</strong></p>
        </div>
	<?php } ?>
    <div class="zvc_listing_table">
        <table id="zvc_users_list_table" class="display" width="100%">
            <thead>
            <tr>
                <th class="zvc-text-left"><?php _e( 'SN', 'video-conferencing-with-zoom-api' ); ?></th>
                <th class="zvc-text-left"><?php _e( 'User ID', 'video-conferencing-with-zoom-api' ); ?></th>
                <th class="zvc-text-left"><?php _e( 'Email', 'video-conferencing-with-zoom-api' ); ?></th>
                <th class="zvc-text-left"><?php _e( 'Name', 'video-conferencing-with-zoom-api' ); ?></th>
                <th class="zvc-text-left"><?php _e( 'Created On', 'video-conferencing-with-zoom-api' ); ?></th>
	            <?php if ( self::$api_version == 2 ) { ?>
                    <th class="zvc-text-left"><?php _e( 'Last Login', 'video-conferencing-with-zoom-api' ); ?></th>
                    <th class="zvc-text-left"><?php _e( 'Last Client', 'video-conferencing-with-zoom-api' ); ?></th>
	            <?php } ?>
            </tr>
            </thead>
            <tbody>
			<?php $count = 1;
			foreach ( $users as $user ): ?>
                <tr>
                    <td><?php echo $count ++; ?></td>
                    <td><?php echo $user->id; ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
                    <td><?php echo date( 'F j, Y, g:i a', strtotime( $user->created_at ) ); ?></td>
                    <div id="zvc_getting_user_info" style="display:none;">
                        <div class="zvc_getting_user_info_content"></div>
                    </div>
	                <?php if ( self::$api_version == 2 ) { ?>
                        <td><?php echo date( 'F j, Y, g:i a', strtotime( $user->last_login_time ) ); ?></td>
                        <td><?php echo !empty($user->last_client_version) ? $user->last_client_version : "N/A"; ?></td>
                    <?php } ?>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
