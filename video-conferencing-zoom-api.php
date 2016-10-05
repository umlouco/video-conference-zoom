<?php

/**
 * @link              http://www.deepenbajracharya.com.np
 * @since             1.0.0
 * @package           Video Conferencing with Zoom API
 *
 * @wordpress-plugin
 * Plugin Name:       Video Conferencing with Zoom API
 * Plugin URI:        http://www.deepenbajracharya.com.np
 * Description:       Add, Handle Zoom meetings from WordPress Dashboard. Manage users, show meeting link using shortcode in frontend.
 * Version:           1.3.0
 * Author:            Deepen Bajracharya
 * Author URI:        http://www.deepenbajracharya.com.np
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       video-conferencing-with-zoom-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('ZOOM_API_PATH', plugin_dir_path( __FILE__ ));
define('ZOOM_URI_PATH', plugin_dir_url( __FILE__ ));
define('ZOOM_API_VERSION', '1.3.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-zoom-video-conference-activator.php
 */
function activate_zoom_video_conference() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zoom-video-conference-activator.php';
	Zoom_Video_Conference_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-zoom-video-conference-deactivator.php
 */
function deactivate_zoom_video_conference() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-zoom-video-conference-deactivator.php';
	Zoom_Video_Conference_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_zoom_video_conference' );
register_deactivation_hook( __FILE__, 'deactivate_zoom_video_conference' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-zoom-video-conference.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_zoom_video_conference() {

	$plugin = new Zoom_Video_Conference();
	$plugin->run();

}
run_zoom_video_conference();

