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
  //add_option( 'zoom_user_id', $_POST['zoom_user_id'], '', 'yes' );
  add_option( 'zoom_api_key', $_POST['zoom_api_key'], '', 'yes' );
  add_option( 'zoom_api_secret', $_POST['zoom_api_secret'], '', 'yes' );

 // update_option( 'zoom_user_id', $_POST['zoom_user_id'], 'yes' );
  update_option( 'zoom_api_key', $_POST['zoom_api_key'], 'yes' );
  update_option( 'zoom_api_secret', $_POST['zoom_api_secret'], 'yes' );

  ?>
  <div id="message" class="notice notice-success is-dismissible">
    <p><?php _e('Successfully Updated. Please Refresh the Page.', 'zoom-video-conference'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'zoom-video-conference'); ?></span></button>
  </div>
  <?php
}
?>
<div id="message" class="notice notice-warning">
  <p><?php _e('The Following Credentials are essential for connecting with ZOOM Account. These are confidential information.', 'zoom-video-conference'); ?></p>
</div>
<div id="message" class="notice notice-warning">
  <p><?php _e('You can call the video where you want to by using shortcodes. Example: <strong>[zoom_api_link meeting_id="meeting_ID" class="your_class" id="your_id" title="Text of Link"]</strong>. Here the number is the zoom video id. Copy paste this snippet to any content editor where you want to display a specific video.', 'zoom-video-conference'); ?> </p>
</div>
<div class="wrap">
  <h1><?php _e('Settings', 'zoom-video-conference'); ?></h1>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <form action="?page=zoom_setting" method="POST">
        <div id="postbox-container-1" class="postbox-container">
          <div id="submitdiv" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span><?php _e('Action', 'zoom-video-conference'); ?></span></h2>
            <div class="inside">
              <div id="major-publishing-actions">
                <input type="submit" name="save_zoom_settings" class="button button-primary button-large" value="Save Settings">
              </div>
            </div>
          </div>
          <p class="creator_of_all"><?php _e('Developed by <a target="_blank" href="http://deepenbajracharya.com.np">Deepen</a><br>Plugin Version '.ZOOM_API_VERSION); ?></p>
        </div>
        <div id="postbox-container-2" class="postbox-container">
          <div id="date-settings" class="postbox ">
            <h2 class="hndle ui-sortable-handle"><span><?php _e('API Settings', 'zoom-video-conference'); ?></span></h2>
            <div class="inside">
              <table>
                <tr>
                  <th><?php _e('API Key :', 'zoom-video-conference'); ?></th>
                  <td><input type="text" name="zoom_api_key" id="zoom_api_key" value="<?php echo (get_option('zoom_api_key')) ? get_option('zoom_api_key') : '' ; ?>"> <small>Required</small></td>
                </tr>
                <tr>
                  <th><?php _e('API Secret Key :', 'zoom-video-conference'); ?></th>
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