<?php
/**
 * Trigger this Function when your plugin is activated
 */
class Zoom_Video_Conferencing_Activator {

  /**
   * Fire on Activation
   * @return [type] [description]
   */
  public static function zoom_video_conference_activator() {
    global $wp_version;

    $min_wp_version = video_conferencing_zoom()->required_wp_version;
    $exit_msg = sprintf( __( '%s requires %s or newer.' ),ZOOM_VIDEO_CONFERENCE_PLUGIN_NAME, $min_wp_version );
    if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
      exit( $exit_msg );
    }

    //Comparing Version
    if( version_compare(PHP_VERSION, 5.4, "<") ) {
      $exit_msg = '<div class="error"><h3>' . __( 'Warning! It is not possible to activate this plugin as it requires above PHP 5.4 and on this server the PHP version installed is: ') . '<b>'.PHP_VERSION.'</b></h3><p>' . __( 'For security reasons we <b>suggest</b> that you contact your hosting provider and ask to update your PHP to latest stable version.' ). '</p><p>' . __( 'If they refuse for whatever reason we suggest you to <b>change provider as soon as possible</b>.' ). '</p></div>';
      exit( $exit_msg );
    }

  }
}