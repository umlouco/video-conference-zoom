<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/includes
 * @author     Deepen Bajracharya <dpen.connectify@gmail.com>
 */
class Zoom_Video_Conference_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wp_version;

		$min_wp_version = '3.5.1';
		$exit_msg = sprintf( __( 'Zoom Video Conference %s or newer.', 'new-user-approve' ), $min_wp_version );
		if ( version_compare( $wp_version, $min_wp_version, '<' ) ) {
			exit( $exit_msg );
		}
	}

}
