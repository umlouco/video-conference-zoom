<?php
/**
* @link              http://www.deepenbajracharya.com.np
* @since             1.0.0
* @package           Video Conferencing with Zoom API
*
* Plugin Name:       Video Conferencing with Zoom API
* Plugin URI:        http://www.deepenbajracharya.com.np
* Description:       Add, Handle Zoom meetings from WordPress Dashboard using API
* Version:           2.0.3
* Author:            Deepen Bajracharya
* Author URI:        http://www.deepenbajracharya.com.np
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       video-conferencing-with-zoom-api
* Domain Path:       /languages
*/

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die("Not Allowed Here !");
}

final class Video_Conferencing_With_Zoom {

	public $version = '2.0.3';

	public $required_wp_version = '4.5.2';

	private static $_instance = null;

	/**
	* Create only one instance so that it may not Repeat
	* @since 2.0.0
	*/
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	* Cloning is forbidden.
	* @since 2.0.0
	*/
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'video-conferencing-with-zoom-api' ), '2.0.0' );
	}

	/**
	* Unserializing instances of this class is forbidden.
	* @since 2.0.0
	*/
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'video-conferencing-with-zoom-api' ), '2.0.0' );
	}

	/**
	* Constructor method for loading the components
	* @since  2.0.0
	*/
	public function __construct() {
		$this->zoom_video_conference_define_them_constants();
		$this->zoom_video_conference_load_dependencies();
		$this->zoom_video_conference_init_hooks();

		add_action('admin_enqueue_scripts', array($this, 'zoom_video_conference_enqueue_scripts_backend'));
		add_action( 'init', array( $this, 'zoom_video_conference_load_plugin_textdomain' ) );
	}

	/**
	* Define constant if not already set.
	*
	* @param  string $name
	* @param  string|bool $value
	* @since  2.0.0
	*
	* @author  Deepen Bajracharya
	*/
	protected function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	* INitialize the hooks
	* @since 2.0.0
	* @author  Deepen Bajracharya
	*/
	protected function zoom_video_conference_init_hooks() {
		//class @Zoom_Video_Conferencing_Activator
		register_activation_hook( __FILE__, array('Zoom_Video_Conferencing_Activator', 'zoom_video_conference_activator') );

		//Load the Credentials
		ZOOM_CONFERENCE()->zoom_api_key = get_option('zoom_api_key');
		ZOOM_CONFERENCE()->zoom_api_secret = get_option('zoom_api_secret');
	}

	/**
	* Defining the Constants Here
	* @since 2.0.0
	* @author  Deepen Bajracharya
	*/
	public function zoom_video_conference_define_them_constants() {
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_FILE', __FILE__ );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_NAME', 'Zoom Video Conferencing' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG', 'video-conferencing-with-zoom-api' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH', plugin_dir_path( __FILE__ ) .'classes' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_LANGUAGE_PATH', trailingslashit( basename( plugin_dir_path( __FILE__ ) ) ) .'languages/' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH', plugin_dir_path( __FILE__ ) .'admin' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_IMAGES_PATH', plugin_dir_url( __FILE__ ) .'admin/images' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_INCLUDES_PATH', plugin_dir_path( __FILE__ ) .'includes' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_CSS_PATH', plugin_dir_url( __FILE__ ) .'admin/css' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_JS_PATH', plugin_dir_url( __FILE__ ) .'admin/js' );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_PLUGIN_URL_PATH', plugin_dir_url( __FILE__ ) );
		$this->define( 'ZOOM_VIDEO_CONFERENCE_VERSION', $this->version );
	}

	/**
	* Load the other class dependencies
	* @since 2.0.0
	*
	* @author  Deepen Bajracharya
	*/
	protected function zoom_video_conference_load_dependencies() {
		//Include the Main Class and Activators
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_INCLUDES_PATH . '/video-conferencing-with-zoom-activator.php';
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/class-zvc-zoom-api.php';
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/admin/class-zvc-zoom-admin-notices.php';

		//Loading Includes
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_INCLUDES_PATH . '/video-conferencing-with-zoom-helpers.php';

		//AJAX CALLS SCRIPTS
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/admin/class-zvc-zoom-admin-ajax-calls.php';

		//Admin Classes
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/admin/class-zvc-zoom-admin-views.php';
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/admin/class-zvc-zoom-admin-reports.php';
		require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_CLASSES_PATH . '/admin/class-zvc-zoom-admin-shortcode.php';
	}

	/**
	* Enqueuing Scripts and Styles for Admin
	* @since 2.0.0
	*
	* @author  Deepen Bajracharya
	*/
	public function zoom_video_conference_enqueue_scripts_backend($hook_suffix) {
		if( $hook_suffix == 'toplevel_page_zoom-video-conferencing' || $hook_suffix == 'zoom-meetings_page_zoom-video-conferencing-list-users' || $hook_suffix == 'zoom-meetings_page_zoom-video-conferencing-add-meeting' || $hook_suffix == 'zoom-meetings_page_zoom-video-conferencing-add-users' || $hook_suffix == 'zoom-meetings_page_zoom-video-conferencing-reports' || $hook_suffix == 'zoom-meetings_page_zoom-video-conferencing-settings' ) {

			//Loading Style stylesheet
			$stylesheets = array(
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG . '-timepick' => 'jquery.datetimepicker.css',
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG => 'video-conferencing-with-zoom-api.css',
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG . '-select2' => 'select2.min.css'
				);

			//Loading JS Scripts
			$js_scripts = array(
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-timepicker-js' => 'jquery.datetimepicker.full.min.js',
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-js' => 'video-conferencing-with-zoom-api.js',
				ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-select2-js' => 'select2.min.js'
				);

			$stylesheets[ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-datable'] = 'jquery.dataTables.min.css';

			foreach($stylesheets as $k => $stylesheet) {
				wp_enqueue_style( $k, ZOOM_VIDEO_CONFERENCE_PLUGIN_CSS_PATH . '/' . $stylesheet, false, time() );
			}

			$js_scripts[ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-datable-js'] = 'jquery.dataTables.min.js';

			foreach($js_scripts as $m => $js_script) {
				wp_enqueue_script( $m, ZOOM_VIDEO_CONFERENCE_PLUGIN_JS_PATH . '/' . $js_script, array('jquery'), time(), true );
			}

			wp_localize_script( ZOOM_VIDEO_CONFERENCE_PLUGIN_SLUG .'-js', 'zvc_ajax', array( 'ajaxurl' => admin_url('admin-ajax.php'), 'zvc_security' => wp_create_nonce( "_nonce_zvc_security" ) ));
		}
	}

	public function zoom_video_conference_load_plugin_textdomain() {
		$domain = 'video-conferencing-with-zoom-api';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_plugin_textdomain( $domain, FALSE, ZOOM_VIDEO_CONFERENCE_PLUGIN_LANGUAGE_PATH );
	}

}

add_action( 'plugins_loaded', array( 'Video_Conferencing_With_Zoom', 'instance' ) );