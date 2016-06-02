<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/public
 * @author     Deepen Bajracharya <dpen.connectify@gmail.com>
 */
class Zoom_Video_Conference_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		#wp_enqueue_style( 'zoom-timepicker-css', plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.css', array(), $this->version	);

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		#wp_enqueue_script( 'zoom-frontend-js', plugin_dir_url( __FILE__ ) . 'js/zoom-video-conference-public.js', array( 'jquery' ), $this->version, false );
		#wp_enqueue_script( 'zoom-timepicker-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.8.9/jquery.timepicker.min.js', array( 'jquery' ), $this->version, false);

	}

	/**
	 * Create Video From Frontend .. !!
	 * @since 1.0.0
	 */
	function zoom_add_video_frontend() {
		$zoom = new ZoomAPI();
		if(isset($_POST['save_zoom_video'])) {
			$result = $zoom->createFronendMeeting();
			$data = json_decode($result, true);
			if($data['error']) {
				?>  
				<?php echo json_encode(array('type' => 'error', 'msg' => $data['error']['message'])); ?>
				<?php
			} else {

				$zoom_post_arr = array();
				$zoom_post_arr['host_id'] = $_POST['userId'];
				$zoom_post_arr['meeting_id'] = $data['id'];
				$zoom_post_arr['post_id'] = $_POST['course_id'];
				$store_val_json = json_encode($zoom_post_arr);

				if($_POST['course_id']) {
					add_post_meta( $_POST['course_id'], 'vibe_zoom_video', $store_val_json, true);
				}

				if(!empty($data)) {
					 echo json_encode(array('type' => 'success')); 
					}
				}
			}
			wp_die();
		}

}

	/**
	 * Adding Shortcode
	 * @since 1.0.0
	 */
function zoom_api_add_vdo_shortcode($atts, $content = null) {
		$zoom_user_exists = get_user_meta( get_current_user_id(), 'zoom_meta_for_user', true );
		if( $zoom_user_exists || is_super_admin() ) {
			$addvdo .= '<a href="#" id="create_zoom_video" class="button big hero" data-toggle="modal" data-target="#create-zoom">' .__( 'Schedule a Video', 'vibe' ).'</a>';
			include ZOOM_API_PATH . '/public/partials/zoom-video-conference-public-display.php';
			return $addvdo;
		}
	}

add_shortcode( 'zoom_add_btn', 'zoom_api_add_vdo_shortcode' );