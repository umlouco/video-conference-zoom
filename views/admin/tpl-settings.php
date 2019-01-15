<div class="wrap">
    <h1><?php _e( 'Settings', 'video-conferencing-with-zoom-api' ); ?></h1>
    <div id="message" class="notice notice-warning">
        <h3><?php esc_html_e( 'Like this plugin ?', 'video-conferencing-with-zoom-api' ); ?></h3>
        <p>
			<?php
			printf( esc_html__( 'Please consider giving a %s if you found this useful at wordpress.org.', 'video-conferencing-with-zoom-api' ), '<a href="https://wordpress.org/support/plugin/video-conferencing-with-zoom-api/reviews/#new-post">5 star thumbs up</a>' );
			printf( esc_html__( 'You can call links using shortcode. Check %s for what is added from version 2.1.0.', 'video-conferencing-with-zoom-api' ), '<a href="https://wordpress.org/plugins/video-conferencing-with-zoom-api/#developers">changelog</a>' );
			?>
        </p>
    </div>

    <div id="message" class="error">
        <p><strong>Version 1 of the Zoom API is no longer supported. So, this plugin uses version 2 of the API.</strong></p>
    </div>

    <div class="zvc-position-floater-left">
        <form action="?page=zoom-video-conferencing-settings" method="POST">
			<?php wp_nonce_field( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' ); ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th><label><?php _e( 'API Key', 'video-conferencing-with-zoom-api' ); ?></label></th>
                    <td><input type="password" style="width: 400px;" name="zoom_api_key" id="zoom_api_key" value="<?php echo ( get_option( 'zoom_api_key' ) ) ? get_option( 'zoom_api_key' ) : ''; ?>"> <a href="javascript:void(0);" onclick="video_conferencing_zoom_toggle_password_api('zoom_api_key');" id="showhide">Show</a></td>
                </tr>
                <tr>
                    <th><label><?php _e( 'API Secret Key', 'video-conferencing-with-zoom-api' ); ?></label></th>
                    <td><input type="password" style="width: 400px;" name="zoom_api_secret" id="zoom_api_secret" value="<?php echo ( get_option( 'zoom_api_secret' ) ) ? get_option( 'zoom_api_secret' ) : ''; ?>"> <a href="javascript:void(0);" onclick="video_conferencing_zoom_toggle_password_secret('zoom_api_secret');" id="showhide1">Show</a></td>
                </tr>
                </tbody>
            </table>
            <p class="submit"><input type="submit" name="save_zoom_settings" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'inactive-logout' ); ?>"></p>
        </form>
    </div>
    <div class="zvc-position-floater-right">
        <ul class="zvc-information-sec">
            <li><a target="_blank" href="https://deepenbajracharya.com.np/zoom-conference-wp-plugin-documentation/"><?php _e( 'Documentation', 'video-conferencing-with-zoom-api' ); ?></a></li>
            <li><a target="_blank" href="https://deepenbajracharya.com.np/say-hello/"><?php _e( 'Contact for additional Support', 'video-conferencing-with-zoom-api' ); ?></a></li>
            <li><a target="_blank" href="https://deepenbajracharya.com.np"><?php _e( 'Developer', 'video-conferencing-with-zoom-api' ); ?></a></li>
        </ul>
    </div>
    <div class="zvc-position-clear"></div>
</div>
