<?php
//Defining Varaibles
$zoom_api_key = get_option( 'zoom_api_key' );
$zoom_api_secret = get_option( 'zoom_api_secret' );
$zoom_url_enable = get_option( 'zoom_url_enable' );
$zoom_vanity_url = get_option( 'zoom_vanity_url' );
?>
<div class="wrap">
    <h1><?php _e( 'Settings', 'video-conferencing-with-zoom-api' ); ?></h1>
        
    <?php video_conferencing_zoom_api_show_like_popup(); ?>
    
    <div id="message" class="error">
        <p><strong>Version 1 of the Zoom API is no longer supported. So, this plugin uses version 2 of the API.</strong></p>
    </div>
    
    <div class="zvc-row">
        <div class="zvc-position-floater-left">
            <form action="?page=zoom-video-conferencing-settings" method="POST">
                <?php wp_nonce_field( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' ); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th><label><?php _e( 'API Key', 'video-conferencing-with-zoom-api' ); ?></label></th>
                            <td><input type="password" style="width: 400px;" name="zoom_api_key" id="zoom_api_key" value="<?php echo !empty( $zoom_api_key ) ? esc_html( $zoom_api_key ) : ''; ?>"> <a href="javascript:void(0);" class="toggle-api">Show</a></td>
                        </tr>
                        <tr>
                            <th><label><?php _e( 'API Secret Key', 'video-conferencing-with-zoom-api' ); ?></label></th>
                            <td><input type="password" style="width: 400px;" name="zoom_api_secret" id="zoom_api_secret" value="<?php echo !empty( $zoom_api_secret ) ? esc_html ( $zoom_api_secret ) : ''; ?>"> <a href="javascript:void(0);" class="toggle-secret">Show</a></td>
                        </tr>

                        <tr>
                            <th><label><?php _e( 'Use Vanity URL', 'video-conferencing-with-zoom-api' ); ?></label></th>
                            <td>
                                <input type="checkbox" class="regular-text vanity-url-enable" <?php checked( $zoom_url_enable ); ?> name="vanity_url_check" value="1"> Yes
                                <p class="description">Checking this option would mean you would have to format your shortcode a bit differently. See example section below.</p>
                            </td>
                        </tr>
                        <tr class="enabled-vanity-url" <?php echo !empty( $zoom_url_enable ) && $zoom_url_enable == 1 ? 'style=display:table-row' : 'style=display:none'; ?>>
                            <th><label><?php _e( 'Vanity URL', 'video-conferencing-with-zoom-api' ); ?></label></th>
                            <td>
                                <input type="url" name="vanity_url" class="regular-text" value="<?php echo ( $zoom_vanity_url ) ? esc_html( $zoom_vanity_url ) : ''; ?>" placeholder="https://example.zoom.us"> 
                                <p class="description">This URI will be used when you insert shortcodes.</p>
                                <p style="color:red;">Please note that vanity URL requires "Business, Education, or API plan"</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit"><input type="submit" name="save_zoom_settings" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'inactive-logout' ); ?>"></p>
            </form>

            <hr>
            <section class="zoom-api-example-section">
                <h3>Using Shortcode Example</h3>
                <p>Below are few examples of how you can add shortcodes manually into your posts.</p>

                <div class="zoom-api-basic-usage">
                    <h3>Basic Usage:</h3>
                    <code>[zoom_api_link meeting_id="https://zoom.us/j/123456789" class="zoom-meeting-url" id="zoom-meeting-url" target="_self" title="Start Meeting"]</code>
                    <div class="zoom-api-basic-usage-description">
                        <label>Parameters:</label>
                        <ul>
                            <li><strong>meeting_id</strong> : Your meeting link.</li>
                            <li><strong>class</strong> : CSS class</li>
                            <li><strong>id</strong> : CSS ID</li>
                            <li><strong>target</strong> : Open meeting link in same tab or new tab. Set to "_blank" to open in new tab.</li>
                            <li><strong>title</strong> : Title of the link.</li>
                        </ul>
                    </div>
                </div>
                <div class="zoom-api-vanityurl-usage">
                    <h3>Vanity URL Usage:</h3>
                    <p>Please note: For below shortcode to work you'll need to check <strong>"Use vanity URL"</strong> setting and add in your <strong>vanity URL or custom zoom url.</strong></p>
                    <code>[zoom_vanity_link meeting_id="123456789" class="zoom-meeting-url" id="zoom-meeting-url" target="_self" title="Start Meeting"]</code>
                    <div class="zoom-api-vanityurl-usage-description">
                        <label>Parameters:</label>
                        <ul>
                            <li><strong>meeting_id</strong> : Your meeting ID only. Not the full link of the meeting.</li>
                            <li><strong>class</strong> : CSS class</li>
                            <li><strong>id</strong> : CSS ID</li>
                            <li><strong>target</strong> : Open meeting link in same tab or new tab. Set to "_blank" to open in new tab.</li>
                            <li><strong>title</strong> : Title of the link.</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <div class="zvc-position-floater-right">
            <ul class="zvc-information-sec">
                <li><a target="_blank" href="https://deepenbajracharya.com.np/zoom-conference-wp-plugin-documentation/"><?php _e( 'Documentation', 'video-conferencing-with-zoom-api' ); ?></a></li>
                <li><a target="_blank" href="https://deepenbajracharya.com.np/say-hello/"><?php _e( 'Contact for additional Support', 'video-conferencing-with-zoom-api' ); ?></a></li>
                <li><a target="_blank" href="https://deepenbajracharya.com.np"><?php _e( 'Developer', 'video-conferencing-with-zoom-api' ); ?></a></li>
            </ul>
            <div class="zvc-information-sec">
                <p>Protect your WordPress users' sessions from shoulder surfers and snoopers!</p>
                <p>Use the Inactive Logout plugin to automatically terminate idle user sessions, thus protecting the site if the users leave unattended sessions.</p>
                <p><a target="_blank" href="https://wordpress.org/plugins/inactive-logout/"><?php _e( 'Try inactive logout', 'video-conferencing-with-zoom-api' ); ?></a>
            </div>
        </div>
    </div>
    <div class="zvc-position-clear"></div>
</div>
