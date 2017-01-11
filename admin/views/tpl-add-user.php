<div class="wrap">
  <h1><?php _e('Add a User', 'video-conferencing-with-zoom-api'); ?></h1>
  <?php if( !empty($message['success']) ) { ?>
  <div id="message" class="notice notice-success is-dismissible"><p><?php echo $message['success']; ?></p></div>
  <?php } else if(!empty($message['error'])) { ?>
  <div id="message" class="notice notice-error is-dismissible"><p><?php echo $message['error']; ?></p></div>
  <?php } ?>
  <form action="?page=zoom-video-conferencing-add-users" method="POST">
    <?php wp_nonce_field( '_zoom_add_user_nonce_action', '_zoom_add_user_nonce' ); ?>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="email"><?php _e('Email Address', 'video-conferencing-with-zoom-api'); ?></label></th>
          <td><input name="email" type="email" required  placeholder="john@doe.com" class="regular-text ltr">
            <p class="description" id="email-description"><?php _e('This address is used for zoom (Required).', 'video-conferencing-with-zoom-api'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="first_name"><?php _e('First Name', 'video-conferencing-with-zoom-api'); ?></label></th>
          <td>
            <input type="text" name="first_name" required id="first_name" class="regular-text" >
            <p class="description" id="first_name-description"><?php _e('First Name of the User (Required).', 'video-conferencing-with-zoom-api'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="last_name"><?php _e('Last Name', 'video-conferencing-with-zoom-api'); ?></label></th>
          <td><input type="text" name="last_name" required id="last_name" class="regular-text" ><p class="description" id="last_name-description"><?php _e('Last Name of the User (Required).', 'video-conferencing-with-zoom-api'); ?></p></td>
        </tr>
        <tr>
          <th scope="row"><label for="type"><?php _e('User Type (Required).', 'video-conferencing-with-zoom-api'); ?></label></th>
          <td>
            <select name="type" id="type">
              <option value="1"><?php _e('Basic User', 'video-conferencing-with-zoom-api'); ?></option>
              <option value="2"><?php _e('Pro User', 'video-conferencing-with-zoom-api'); ?></option>
            </select>
            <p class="description" id="type-description"><?php _e('Type of User (Required)', 'video-conferencing-with-zoom-api'); ?></p>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="dept"><?php _e('Department ', 'video-conferencing-with-zoom-api'); ?></label></th>
          <td><input type="text" name="dept" id="dept" class="regular-text" > <p class="description" id="dept-description"><?php _e('Department (Optional).', 'video-conferencing-with-zoom-api'); ?></p></td>
        </tr>
      </tbody>
    </table>
    <p class="submit"><input type="submit" name="add_zoom_user" class="button button-primary" value="Create User"></p>
  </form>
</div>