<div class="wrap">
  <h1><?php _e('Edit a Meeting', 'video-conferencing-with-zoom-api'); ?></h1> <a href="?page=zoom-video-conferencing&host_id=<?php echo $meeting_info->host_id; ?>"><?php _e('Back to List', 'video-conferencing-with-zoom-api'); ?></a>
  <?php if( !empty($message['success']) ) { ?>
  <div id="message" class="notice notice-success is-dismissible"><p><?php echo $message['success']; ?></p></div>
  <?php } else if(!empty($message['error'])) { ?>
  <div id="message" class="notice notice-error is-dismissible"><p><?php echo $message['error']; ?></p></div>
  <?php } ?>
  <form action="?page=zoom-video-conferencing-add-meeting" method="POST">
    <?php wp_nonce_field( '_zoom_update_meeting_nonce_action', '_zoom_update_meeting_nonce' ); ?>
    <input type="hidden" name="meeting_id" value="<?php echo $meeting_info->id; ?>">
    <table class="form-table">
      <tbody>
       <tr>
        <th scope="row"><label for="meetingTopic"><?php _e('Meeting Topic *', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <input type="text" name="meetingTopic" size="100" class="regular-text" required value="<?php echo $meeting_info->topic ? $meeting_info->topic : NULL; ?>">
          <p class="description" id="meetingTopic-description"><?php _e('Meeting topic. (Required).', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="userId"><?php _e('Meeting Host *', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <select name="userId" required class="zvc-hacking-select">
            <option value=""><?php _e('Select a Host', 'video-conferencing-with-zoom-api'); ?></option>
            <?php foreach($users as $user):  ?>
              <option value="<?php echo $user->id; ?>" <?php echo $meeting_info->host_id == $user->id ? 'selected' : NULL; ?>><?php echo $user->first_name . ' ( '. $user->email.' )'; ?></option>
            <?php endforeach; ?>
          </select>
          <p class="description" id="userId-description"><?php _e('This is host ID for the meeting (Required).', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="start_date"><?php _e('Start Date/Time *', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <input type="text" name="start_date" id="datetimepicker" data-existingdate="<?php echo date( 'Y-m-d H:i:s', strtotime($meeting_info->start_time) ); ?>" required class="regular-text" >
          <p class="description" id="start_date-description"><?php _e('Starting Date and Time of the Meeting (Required).', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="timezone"><?php _e('Timezone', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <?php $tzlists = zvc_get_timezone_options(); ?>
          <select id="timezone" name="timezone" class="zvc-hacking-select">
          <?php foreach($tzlists as $k => $tzlist) { ?>
            <option value="<?php echo $k; ?>" <?php echo $meeting_info->timezone == $k ? 'selected' : NULL; ?>><?php echo $tzlist; ?></option>
            <?php } ?>
          </select>
          <p class="description" id="timezone-description"><?php _e('Meeting Timezone', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="duration"><?php _e('Duration', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <input type="number" name="duration" class="regular-text" value="<?php echo $meeting_info->duration ? $meeting_info->duration : NULL; ?>" >
          <p class="description" id="duration-description"><?php _e('Meeting duration (minutes). (optional)', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="join_before_host"><?php _e('Join Before Host', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="join_before_host-description"><input type="checkbox" <?php echo $meeting_info->option_jbh ? 'checked' : NULL; ?> name="join_before_host" value="1" class="regular-text" ><?php _e('Join meeting before host start the meeting. Only for scheduled or recurring meetings.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="option_host_video"><?php _e('Host join start', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="option_host_video-description"><input type="checkbox" <?php echo $meeting_info->option_host_video ? 'checked' : NULL; ?> name="option_host_video" value="1" class="regular-text" ><?php _e('Start video when host join meeting.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="option_participants_video"><?php _e('Start After Participants', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="option_participants_video-description"><input type="checkbox" <?php echo $meeting_info->option_participants_video ? 'checked' : NULL; ?> name="option_participants_video" value="1" class="regular-text" ><?php _e('Start video when participants join meeting.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="option_cn_meeting"><?php _e('Host meeting in China', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="option_cn_meeting-description"><input type="checkbox" <?php echo $meeting_info->option_cn_meeting ? 'checked' : NULL; ?> name="option_cn_meeting" value="1" class="regular-text" ><?php _e('Host meeting in China.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="option_in_meeting"><?php _e('Host meeting in India', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="option_in_meeting-description"><input type="checkbox" <?php echo $meeting_info->option_in_meeting ? 'checked' : NULL; ?> name="option_in_meeting" value="1" class="regular-text" ><?php _e('Host meeting in India.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
      <tr>
        <th scope="row"><label for="option_enforce_login"><?php _e('Enforce Login', 'video-conferencing-with-zoom-api'); ?></label></th>
        <td>
          <p class="description" id="option_enforce_login-description"><input type="checkbox"  <?php echo $meeting_info->option_enforce_login ? 'checked' : NULL; ?>  name="option_enforce_login" value="1" class="regular-text" ><?php _e('Only signed-in users can join this meeting.', 'video-conferencing-with-zoom-api'); ?></p>
        </td>
      </tr>
    </tbody>
  </table>
  <p class="submit"><input type="submit" name="update_meeting" class="button button-primary" value="Update Meeting"></p>
</form>
</div>