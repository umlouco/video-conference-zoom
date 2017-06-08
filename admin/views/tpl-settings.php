<div class="wrap">
	<h1><?php _e('Settings', 'video-conferencing-with-zoom-api'); ?></h1>
	<div id="message" class="notice notice-warning hideme-zoom">
  <p><?php printf( __('You can call links using shortcode. Check %s for what is changed and removed from version 2.0.0. <br><br>Example: <strong>[zoom_api_link meeting_id="meeting_ID" class="your_class" id="your_id" title="Text of Link"]</strong>.<br><br>Here "meeting_ID" is the zoom link. Copy paste this snippet to any content editor where you want to display a specific video.', 'video-conferencing-with-zoom-api'), '<a href="https://wordpress.org/plugins/video-conferencing-with-zoom-api/changelog/">changelog</a>'); ?> </p>
</div>
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<form action="?page=zoom-video-conferencing-settings" method="POST">
				<?php wp_nonce_field( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' ); ?>
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
							<ul class="zvc-information-sec">
								<!-- <li><a target="_blank" href="https://drive.google.com/file/d/0Bw_pLxEfyUXWYVEyOFZZOFBtaHc/view"><?php _e('Documentation', 'video-conferencing-with-zoom-api'); ?></a></li> -->
								<li><a target="_blank" href="https://deepenbajracharya.com.np/say-hello/"><?php _e('Contact for additional Support', 'video-conferencing-with-zoom-api'); ?></a></li>
								<li><a target="_blank" href="https://deepenbajracharya.com.np"><?php _e('Developer', 'video-conferencing-with-zoom-api'); ?></a></li>
							</ul>
						</div>
					</div>
					<p class="creator_of_all"><?php printf( __('Plugin Version %s', 'video-conferencing-with-zoom-api'), ZOOM_VIDEO_CONFERENCE_VERSION); ?></p>
				</div>
				<div id="postbox-container-2" class="postbox-container">
					<div id="date-settings" class="postbox ">
						<h2 class="hndle ui-sortable-handle"><span><?php _e('API Settings', 'video-conferencing-with-zoom-api'); ?></span></h2>
						<div class="inside">
							<table>
								<tr>
									<th><?php _e('API Key :', 'video-conferencing-with-zoom-api'); ?></th>
									<td><input type="password" name="zoom_api_key" id="zoom_api_key" value="<?php echo (get_option('zoom_api_key')) ? get_option('zoom_api_key') : '' ; ?>"> <a href="javascript:void(0);" onclick="toggle_password_api('zoom_api_key');" id="showhide">Show</a></td>
								</tr>
								<tr>
									<th><?php _e('API Secret Key :', 'video-conferencing-with-zoom-api'); ?></th>
									<td><input type="password" name="zoom_api_secret" id="zoom_api_secret" value="<?php echo (get_option('zoom_api_secret')) ? get_option('zoom_api_secret') : '' ; ?>"> <a href="javascript:void(0);" onclick="toggle_password_secret('zoom_api_secret');" id="showhide1">Show</a></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>