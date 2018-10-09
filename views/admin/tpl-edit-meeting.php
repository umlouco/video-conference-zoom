<?php
//Check if any transient by name is available
$users        = video_conferencing_zoom_api_get_user_transients();
$meeting_info = json_decode( zoom_conference()->getMeetingInfo( $_GET['edit'], $_GET['host_id'] ) );
if ( ! empty( self::$api_version ) && self::$api_version == 2 ) {
	$option_jbh                = $meeting_info->settings->join_before_host ? 'checked' : false;
	$option_host_video         = $meeting_info->settings->host_video ? 'checked' : false;
	$option_participants_video = $meeting_info->settings->participant_video ? 'checked' : false;
	$option_cn_meeting         = $meeting_info->settings->cn_meeting ? 'checked' : false;
	$option_in_meeting         = $meeting_info->settings->in_meeting ? 'checked' : false;
	$option_enforce_login      = $meeting_info->settings->enforce_login ? 'checked' : false;
	$option_alternative_hosts  = $meeting_info->settings->alternative_hosts ? $meeting_info->settings->alternative_hosts : false;
	if ( ! empty( $option_alternative_hosts ) ) {
		$option_alternative_hosts = explode( ', ', $option_alternative_hosts );
	}
} else {
	$option_jbh                = $meeting_info->option_jbh ? 'checked' : false;
	$option_host_video         = $meeting_info->option_host_video ? 'checked' : false;
	$option_participants_video = $meeting_info->option_participants_video ? 'checked' : false;
	$option_cn_meeting         = $meeting_info->option_cn_meeting ? 'checked' : false;
	$option_in_meeting         = $meeting_info->option_in_meeting ? 'checked' : false;
	$option_enforce_login      = $meeting_info->option_enforce_login ? 'checked' : false;
}
?>
<div class="wrap">
    <h1><?php _e( 'Edit a Meeting', 'video-conferencing-with-zoom-api' ); ?></h1> <a href="?page=zoom-video-conferencing&host_id=<?php echo $meeting_info->host_id; ?>"><?php _e( 'Back to List', 'video-conferencing-with-zoom-api' ); ?></a>
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
    <form action="?page=zoom-video-conferencing-add-meeting&edit=<?php echo $_GET['edit']; ?>&host_id=<?php echo $_GET['host_id']; ?>" method="POST" class="zvc-meetings-form">
		<?php wp_nonce_field( '_zoom_update_meeting_nonce_action', '_zoom_update_meeting_nonce' ); ?>
        <input type="hidden" name="meeting_id" value="<?php echo $meeting_info->id; ?>">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label for="meetingTopic"><?php _e( 'Meeting Topic *', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <input type="text" name="meetingTopic" size="100" class="regular-text" required value="<?php echo ! empty( $meeting_info->topic ) ? $meeting_info->topic : null; ?>">
                    <p class="description" id="meetingTopic-description"><?php _e( 'Meeting topic. (Required).', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
			<?php if ( ! empty( self::$api_version ) && self::$api_version == 2 ) { ?>
                <tr>
                    <th scope="row"><label for="meetingAgenda"><?php _e( 'Meeting Agenda', 'video-conferencing-with-zoom-api' ); ?></label></th>
                    <td>
                        <input type="text" name="agenda" class="regular-text" value="<?php echo ! empty( $meeting_info->agenda ) ? $meeting_info->agenda : null; ?>">
                        <p class="description" id="meetingTopic-description"><?php _e( 'Meeting Description.', 'video-conferencing-with-zoom-api' ); ?></p>
                    </td>
                </tr>
			<?php } ?>
            <tr>
                <th scope="row"><label for="userId"><?php _e( 'Meeting Host *', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <select name="userId" required class="zvc-hacking-select">
                        <option value=""><?php _e( 'Select a Host', 'video-conferencing-with-zoom-api' ); ?></option>
						<?php foreach ( $users as $user ): ?>
                            <option value="<?php echo $user->id; ?>" <?php echo $meeting_info->host_id == $user->id ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
						<?php endforeach; ?>
                    </select>
                    <p class="description" id="userId-description"><?php _e( 'This is host ID for the meeting (Required).', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="start_date"><?php _e( 'Start Date/Time *', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <input type="text" name="start_date" id="datetimepicker" data-existingdate="<?php echo date( 'Y-m-d H:i:s', strtotime( $meeting_info->start_time ) ); ?>" required class="regular-text">
                    <p class="description" id="start_date-description"><?php _e( 'Starting Date and Time of the Meeting (Required).', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="timezone"><?php _e( 'Timezone', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
					<?php $tzlists = zvc_get_timezone_options(); ?>
                    <select id="timezone" name="timezone" class="zvc-hacking-select">
						<?php foreach ( $tzlists as $k => $tzlist ) { ?>
                            <option value="<?php echo $k; ?>" <?php echo $meeting_info->timezone == $k ? 'selected' : null; ?>><?php echo $tzlist; ?></option>
						<?php } ?>
                    </select>
                    <p class="description" id="timezone-description"><?php _e( 'Meeting Timezone', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="duration"><?php _e( 'Duration', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <input type="number" name="duration" class="regular-text" value="<?php echo $meeting_info->duration ? $meeting_info->duration : null; ?>">
                    <p class="description" id="duration-description"><?php _e( 'Meeting duration (minutes). (optional)', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="password"><?php _e( 'Meeting Password', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <input type="text" name="password" class="regular-text" maxlength="10" data-maxlength="9" value="<?php echo $meeting_info->password ? $meeting_info->password : null; ?>">
                    <p class="description" id="email-description"><?php _e( 'Password to join the meeting. Password may only contain the following characters: [a-z A-Z 0-9]. Max of 10 characters.( Leave blank for no Password )', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="join_before_host"><?php _e( 'Join Before Host', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="join_before_host-description"><input type="checkbox" <?php echo $option_jbh; ?> name="join_before_host" value="1" class="regular-text"><?php _e( 'Join meeting before host start the meeting. Only for scheduled or recurring meetings.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_host_video"><?php _e( 'Host join start', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="option_host_video-description"><input type="checkbox" <?php echo $option_host_video; ?> name="option_host_video" value="1" class="regular-text"><?php _e( 'Start video when host join meeting.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_participants_video"><?php _e( 'Start After Participants', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="option_participants_video-description"><input type="checkbox" <?php echo $option_participants_video; ?> name="option_participants_video" value="1" class="regular-text"><?php _e( 'Start video when participants join meeting.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_cn_meeting"><?php _e( 'Host meeting in China', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="option_cn_meeting-description"><input type="checkbox" <?php echo $option_cn_meeting; ?> name="option_cn_meeting" value="1" class="regular-text"><?php _e( 'Host meeting in China.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_in_meeting"><?php _e( 'Host meeting in India', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="option_in_meeting-description"><input type="checkbox" <?php echo $option_in_meeting; ?> name="option_in_meeting" value="1" class="regular-text"><?php _e( 'Host meeting in India.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="option_enforce_login"><?php _e( 'Enforce Login', 'video-conferencing-with-zoom-api' ); ?></label></th>
                <td>
                    <p class="description" id="option_enforce_login-description"><input type="checkbox" <?php echo $option_enforce_login; ?> name="option_enforce_login" value="1" class="regular-text"><?php _e( 'Only signed-in users can join this meeting.', 'video-conferencing-with-zoom-api' ); ?></p>
                </td>
            </tr>
			<?php if ( ZOOM_VIDEO_CONFERENCE_APIVERSION == 2 ) { ?>
                <tr>
                    <th scope="row"><label for="settings_alternative_hosts"><?php _e( 'Alternative Hosts', 'video-conferencing-with-zoom-api' ); ?></label></th>
                    <td>
                        <select name="alternative_host_ids[]" multiple class="zvc-hacking-select">
                            <option value=""><?php _e( 'Select a Host', 'video-conferencing-with-zoom-api' ); ?></option>
							<?php foreach ( $users as $user ):
								$user_found = false;
								if ( in_array( $user->email, $option_alternative_hosts ) ) {
									$user_found = true;
								}
								?>
                                <option value="<?php echo $user->id; ?>" <?php echo $user_found ? 'selected' : null; ?>><?php echo $user->first_name . ' ( ' . $user->email . ' )'; ?></option>
							<?php endforeach; ?>
                        </select>
                        <p class="description" id="settings_alternative_hosts"><?php _e( 'Alternative hosts IDs. Multiple value separated by comma.', 'video-conferencing-with-zoom-api' ); ?></p>
                    </td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="update_meeting" class="button button-primary" value="Update Meeting"></p>
    </form>
</div>