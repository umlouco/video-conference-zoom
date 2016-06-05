<?php

/**
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/admin
 * @author     Deepen Bajracharya <dpen.connectify@gmail.com>
 */
class Zoom_Video_Conference_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zoom_Video_Conference_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zoom_Video_Conference_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name.'-zoomAdmincss', plugin_dir_url( __FILE__ ) . 'css/zoom-video-conference-admin.css', array(), $this->version	);
		wp_enqueue_style( $this->plugin_name.'-zoomtimepickercss', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.css', array(), $this->version	);
		wp_enqueue_style( 'jquery-ui-datepicker-style' , 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Zoom_Video_Conference_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Zoom_Video_Conference_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name.'-zoomAdminJs', plugin_dir_url( __FILE__ ) . 'js/zoom-video-conference-admin.js', array( 'jquery', 'jquery-ui-datepicker' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'-ZoomAdmintimepickerjs', plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js', array( 'jquery' ), $this->version, true );
	}

	function zoom_api_add_menu() {
		add_menu_page( 'Zoom','Zoom', 'manage_options','list_meetings','zoom_api_list_meetings',	plugin_dir_url( __FILE__ ).'img/zoom.png',	5 );
		if(get_option('zoom_api_key') && get_option('zoom_api_secret')) {
			add_submenu_page( 'list_meetings', 'Meeting', 'Add Meeting', 'manage_options', 'add_meeting', 'zoom_api_add_meeting' );
			add_submenu_page( 'list_meetings', 'Users', 'List Users', 'manage_options', 'zoom_users', 'zoom_api_users' );
			add_submenu_page( 'list_meetings', 'Add Users', 'Add Users', 'manage_options', 'add_zoom_users', 'zoom_api_add_users' );
			add_submenu_page( 'list_meetings', 'Reports', 'Reports', 'manage_options', 'zoom_reports', 'zoom_api_reports' );
		}
		add_submenu_page( 'list_meetings', 'Settings', 'Settings', 'manage_options', 'zoom_setting', 'zoom_api_settings' );

	}

	function zoom_api_delete_meeting() {
		$zoom = new ZoomAPI();
		$zoom->deleteAMeeting();
	}

	function zoom_api_delete_pending() {
		$zoom = new ZoomAPI();
		$zoom->deleteAUser();
	}

	function zoom_api_addCourse_metaPost() {
		$exists_post_id = get_post_meta( $_POST['post_id_course'], 'conference_zoom_video', true );

		$zoom_post_arr = array();
		$zoom_post_arr['host_id'] = $_POST['userId'];
		$zoom_post_arr['meeting_id'] = $_POST['meetingId'];
		$zoom_post_arr['post_id'] = $_POST['post_id_course'];
		$store_val_json = json_encode($zoom_post_arr);

		if(!$exists_post_id) {
			$success = add_post_meta( $_POST['post_id_course'], 'conference_zoom_video', $store_val_json, true);
			if($success) {
				echo '1';
			}
		} else {
			echo '0';
		}
		wp_die();
	}

	function zoom_api_delete_linked_post() {
		delete_post_meta($_POST['post_id'], 'conference_zoom_video');
	}

	function zoom_api_zoom_meta_for_user() {
		$user_id = $_POST['inst_id'];

		$zoom = new ZoomAPI();
		$result = $zoom->createAUser();
		$data = json_decode($result, true);

		if($data['error']) {
			?>
			<div id="message" class="notice notice-error is-dismissible">
				<p><?php echo $data['error']['message'] ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'video-conferencing-with-zoom-api'); ?></span></button>
			</div>
			<?php
		}
		if($data) {
			?>
			<div id="message" class="notice notice-success is-dismissible">
				<p><?php _e('Added New User with ID: ', 'video-conferencing-with-zoom-api'); ?><?php echo '<strong>'.$data['id'].'</strong>'; ?></p>
				<p><?php _e('Name: ', 'video-conferencing-with-zoom-api'); ?><?php echo '<strong>'.$data['first_name'].' '.$data['last_name'].'</strong>'; ?></p>
				<p><?php _e('User Created On: ', 'video-conferencing-with-zoom-api'); ?><?php echo '<strong>'.$data['created_at'].'</strong>'; ?></p>
				<p color="red"><?php _e('Email Appoval Mail has been sent to the user.', 'video-conferencing-with-zoom-api'); ?><?php echo '<strong>'.$data['email'].'</strong>'; ?>.</p>
				<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'video-conferencing-with-zoom-api'); ?></span></button>
			</div>
			<?php
	    //Adding Meta Values
			add_user_meta( $user_id, 'zoom_meta_for_user', $data['id'], true );
		}

		wp_die();
	}

}

function zoom_api_shortcode_video($atts, $content = null) {
		$atts = shortcode_atts( array(
				'meeting_id' => '#',
				'title' => 'Start Video',
				'id' => 'zoom_video_uri',
				'class' => 'zoom_video_uri'
			), $atts
		);
		$content .= '<a id="'.esc_html( $atts['id'] ).'" class="'.esc_html( $atts['class'] ).'" href="https://zoom.us/j/'. esc_html( $atts['meeting_id'] ).'">'. esc_html( $atts['title'] ).'</a>';
		return $content;
	}
add_shortcode( 'zoom_api_link', 'zoom_api_shortcode_video' );

function zoom_api_add_meeting() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-add.php';
}

function zoom_api_list_meetings() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-listing.php';
}

function zoom_api_settings() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-settings.php';
}

function zoom_api_users() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-users.php';
}

function zoom_api_add_users() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-users-add.php';
}

function zoom_api_reports() {
	include ZOOM_API_PATH . '/admin/partials/zoom-video-conference-admin-reports.php';
}