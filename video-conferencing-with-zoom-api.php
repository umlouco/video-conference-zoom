<?php
/**
 * @link              http://www.deepenbajracharya.com.np
 * @since             1.0.0
 * @package           Video Conferencing with Zoom API
 *
 * Plugin Name:       Video Conferencing with Zoom API
 * Plugin URI:        http://www.deepenbajracharya.com.np
 * Description:       Add, Handle Zoom meetings from WordPress Dashboard using API
 * Version:           2.2.3
 * Author:            Deepen Bajracharya
 * Author URI:        http://www.deepenbajracharya.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       video-conferencing-with-zoom-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die( "Not Allowed Here !" );
}

// the main plugin class
require_once dirname( __FILE__ ) . '/includes/video-conferencing-with-zoom-init.php';

add_action( 'plugins_loaded', array( 'Video_Conferencing_With_Zoom', 'instance' ), 99 );
register_activation_hook( __FILE__, array( 'Video_Conferencing_With_Zoom', 'activator' ) );
