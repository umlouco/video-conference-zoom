<?php

namespace CodeManas\ZoomVideoConferencing\Elementor;

use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_ElementorMeetingsList;
use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_Elementor_Meetings;
use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_ElementorMeetingsHost;
use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_Elementor_Embed;
use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_Elementor_RecordingsByHost;
use CodeManas\ZoomVideoConferencing\Elementor\Widgets\Zoom_Video_Conferencing_Elementor_RecordingsByMeetingID;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Invoke Elementor Dependency Class
 *
 * Register new elementor widget.
 *
 * @since 3.4.0
 * @author CodeManas
 */
class Zoom_Video_Conferencing_Elementor {

	/**
	 * Constructor
	 *
	 * @since 3.4.0
	 * @author CodeManas
	 *
	 * @access public
	 */
	public function __construct() {
		$this->add_actions();
	}

	/**
	 * Add Actions
	 *
	 * @since 3.4.0
	 * @author CodeManas
	 *
	 * @access private
	 */
	private function add_actions() {
		// Register widget scripts.
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'widget_scripts' ] );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_categories' ] );
	}

	/**
	 * Widget Styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_enqueue_script( 'video-conferencing-zoom-elementor', ZVC_PLUGIN_ADMIN_ASSETS_URL . '/js/elementor.js', [ 'elementor-editor' ], ZVC_PLUGIN_VERSION, true );
	}

	/**
	 * On Widgets Registered
	 *
	 * @since 3.4.0
	 * @author CodeManas
	 *
	 * @access public
	 */
	public function on_widgets_registered() {
		$this->includes();
		$this->register_widget();
	}

	/**
	 * Register Widget Category
	 *
	 * @param $elements_manager
	 */
	public function widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'vczapi-elements',
			[
				'title'  => 'Zoom',
				'icon'   => 'fa fa-plug',
				'active' => true
			]
		);
	}

	/**
	 * Includes
	 *
	 * @since 3.4.0
	 * @author CodeManas
	 *
	 * @access private
	 */
	private function includes() {
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meetings.php';
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meeting-list.php';
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meeting-host.php';
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meeting-embed.php';
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meeting-recordingsbyhost.php';
		require ZVC_PLUGIN_INCLUDES_PATH . '/elementor/widgets/class-zvc-elementor-meeting-recordings-meeting.php';
	}

	/**
	 * Register Widget
	 *
	 * @since 3.4.0
	 * @author CodeManas
	 *
	 * @access private
	 */
	private function register_widget() {
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_Elementor_Meetings() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_ElementorMeetingsList() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_ElementorMeetingsHost() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_Elementor_Embed() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_Elementor_RecordingsByHost() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Zoom_Video_Conferencing_Elementor_RecordingsByMeetingID() );
	}
}

new Zoom_Video_Conferencing_Elementor();