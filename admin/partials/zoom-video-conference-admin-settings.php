<?php

/**
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/admin/partials
 */
?>
<?php 
if(isset($_POST['save_zoom_settings'])) {
  add_option( 'zoom_api_key', $_POST['zoom_api_key'], '', 'yes' );
  add_option( 'zoom_api_secret', $_POST['zoom_api_secret'], '', 'yes' );
  update_option( 'zoom_api_key', $_POST['zoom_api_key'], 'yes' );
  update_option( 'zoom_api_secret', $_POST['zoom_api_secret'], 'yes' );
  ?>
  <div id="message" class="notice notice-success is-dismissible">
    <p><?php _e('Successfully Updated. Please Refresh the Page.', 'video-conferencing-with-zoom-api'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'video-conferencing-with-zoom-api'); ?></span></button>
  </div>
  <?php
}
?>
<div id="message" class="notice notice-warning">
  <p><?php _e('The Following Credentials are essential for connecting with ZOOM Account. These are confidential information.', 'video-conferencing-with-zoom-api'); ?></p>
</div>
<div id="message" class="notice notice-warning">
  <p><?php _e('You can call the video where you want to by using shortcodes. Example: <strong>[zoom_api_link meeting_id="meeting_ID" class="your_class" id="your_id" title="Text of Link"]</strong>. Here the number is the zoom video id. Copy paste this snippet to any content editor where you want to display a specific video.', 'video-conferencing-with-zoom-api'); ?> </p>
</div>
<div id="message" class="notice notice-warning">
  <p><?php _e('If your meetings are not listed then your crendentials might be incorrect !', 'video-conferencing-with-zoom-api'); ?> </p>
</div>
<div class="wrap">
  <h1><?php _e('Settings', 'video-conferencing-with-zoom-api'); ?></h1>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <form action="?page=zoom_setting" method="POST">
        <div id="postbox-container-1" class="postbox-container">
          <div id="submitdiv" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span><?php _e('Action', 'video-conferencing-with-zoom-api'); ?></span></h2>
            <div class="inside">
              <div id="major-publishing-actions">
                <input type="submit" name="save_zoom_settings" class="button button-primary button-large" value="Save Settings">
              </div>
            </div>
          </div>
          <div id="submitdiv" class="postbox">
              <h3 class="hndle"><span><?php _e('Information', 'video-conferencing-with-zoom-api'); ?></span></h3>
              <div class="inside">
                <ul class="tf-information-sec">
                  <li><a target="_blank" href="https://drive.google.com/file/d/0Bw_pLxEfyUXWYVEyOFZZOFBtaHc/view"><?php _e('Documentation', 'video-conferencing-with-zoom-api'); ?></a></li>
                  <li><a target="_blank" href="http://deepenbajracharya.com.np/donate-via-skrill/"><?php _e('Donate', 'video-conferencing-with-zoom-api'); ?></a></li>
                </ul>
              </div>
            </div>
          <p class="creator_of_all"><?php _e('Developed by <a target="_blank" href="http://deepenbajracharya.com.np">Deepen</a><br>Plugin Version '.ZOOM_API_VERSION); ?></p>
        </div>
        <div id="postbox-container-2" class="postbox-container">
          <div id="date-settings" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span><?php _e('API Settings', 'video-conferencing-with-zoom-api'); ?></span></h2>
            <div class="inside">
              <table>
                <tr>
                  <th><?php _e('API Key :', 'video-conferencing-with-zoom-api'); ?></th>
                  <td><input type="text" name="zoom_api_key" id="zoom_api_key" value="<?php echo (get_option('zoom_api_key')) ? get_option('zoom_api_key') : '' ; ?>"> <small>Required</small></td>
                </tr>
                <tr>
                  <th><?php _e('API Secret Key :', 'video-conferencing-with-zoom-api'); ?></th>
                  <td><input type="text" name="zoom_api_secret" id="zoom_api_secret" value="<?php echo (get_option('zoom_api_secret')) ? get_option('zoom_api_secret') : '' ; ?>"> <small>Required</small></td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>