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
			$api = new ZoomAPI();
			$users = $api->listUsers();
			$users_result = json_decode($users, true);
			if( empty($users_result['error']) ) {
				if( empty($users_result['error']['code']) == 200 ) {
					add_submenu_page( 'list_meetings', 'Meeting', 'Add Meeting', 'manage_options', 'add_meeting', 'zoom_api_add_meeting' );
					add_submenu_page( 'list_meetings', 'Users', 'List Users', 'manage_options', 'zoom_users', 'zoom_api_users' );
					add_submenu_page( 'list_meetings', 'Add Users', 'Add Users', 'manage_options', 'add_zoom_users', 'zoom_api_add_users' );
					add_submenu_page( 'list_meetings', 'Reports', 'Reports', 'manage_options', 'zoom_reports', 'zoom_api_reports' );
				}
			}
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

function zoom_api_shortcode_video_link($atts, $content = null) {
	$atts = shortcode_atts( array(
		'meeting_id' => 'your_meeting_id',
		), $atts
	);
	$content .= $atts['meeting_id'] ? "https://zoom.us/j/". esc_html( $atts['meeting_id'] ) : false;
	return $content;
}
add_shortcode( 'zoom_api_video_uri', 'zoom_api_shortcode_video_link' );

/**
 * Pagination Function
 * @since 1.3.0
 * @author  Deepen <dpen.connectify@gmail.com>
 */
function pagination($totalposts,$p,$lpm1,$prev,$next) {
	$adjacents = 3;
	if($totalposts > 1)
	{
		$pagination = "<ul class='pagination'>";
        //previous button
		if ($p > 1)
			$pagination.= "<li><a href=\"?page=list_meetings&pg=$prev\"><<</a></li>";
		else
			$pagination.= "<li class=\"disabled\"><a href=\"javascript:void(0);\"><<</a></li>";
		if ($totalposts < 7 + ($adjacents * 2)){
			for ($counter = 1; $counter <= $totalposts; $counter++){
				if ($counter == $p)
					$pagination.= "<li><a class=\"active\" href=\"javascript:void(0);\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"?page=list_meetings&pg=$counter\">$counter</a></li>";}
			}elseif($totalposts > 5 + ($adjacents * 2)){
				if($p < 1 + ($adjacents * 2)){
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
						if ($counter == $p)
							$pagination.= "<li><a class=\"active\" href=\"javascript:void(0);\">$counter</a></li>";
						else
							$pagination.= "<li><a href=\"?page=list_meetings&pg=$counter\">$counter</a></li>";
					}
					$pagination.= " ... ";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=$totalposts\">$totalposts</a></li>";
				}
            //in middle; hide some front and some back
				elseif($totalposts - ($adjacents * 2) > $p && $p > ($adjacents * 2)){
					$pagination.= "<li><a href=\"?page=list_meetings&pg=1\">1</a></li>";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=2\">2</a></li>";
					$pagination.= " ... ";
					for ($counter = $p - $adjacents; $counter <= $p + $adjacents; $counter++){
						if ($counter == $p)
							$pagination.= "<li><a class=\"active\" href=\"javascript:void(0);\">$counter</a></li>";
						else
							$pagination.= "<li><a href=\"?page=list_meetings&pg=$counter\">$counter</a></li>";
					}
					$pagination.= " ... ";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=$lpm1\">$lpm1</a></li>";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=$totalposts\">$totalposts</a></li>";
				}else{
					$pagination.= "<li><a href=\"?page=list_meetings&pg=1\">1</a></li>";
					$pagination.= "<li><a href=\"?page=list_meetings&pg=2\">2</a></li>";
					$pagination.= " ... ";
					for ($counter = $totalposts - (2 + ($adjacents * 2)); $counter <= $totalposts; $counter++){
						if ($counter == $p)
							$pagination.= "<li><a class=\"active\" href=\"javascript:void(0);\">$counter</a></li>";
						else
							$pagination.= "<li><a href=\"?page=list_meetings&pg=$counter\">$counter</a></li>";
					}
				}
			}
			if ($p < $counter - 1)
				$pagination.= "<li><a href=\"?page=list_meetings&pg=$next\">>></a></li>";
			else
				$pagination.= "<li class=\"disabled\"><a href=\"javascript:void(0);\">>></a></li>";
			$pagination.= "</ul>";
		}
		return $pagination;
	}

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