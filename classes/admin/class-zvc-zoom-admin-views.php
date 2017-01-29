<?php
/**
* Registering the Pages Here
*
* @since  2.0.0
* @author  Deepen
*/
class Zoom_Video_Conferencing_Admin_Views {

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'zoom_video_conference_menus' ) );
  }

  /**
   * Register Menus
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_menus() {
    add_menu_page( 'Zoom', 'Zoom Meetings', 'manage_options', 'zoom-video-conferencing', array( $this, 'zoom_video_conference_api_zoom_list_meetings' ), 'dashicons-video-alt2', 5 );
    if(get_option('zoom_api_key') && get_option('zoom_api_secret')) {
      $encoded_users = zoom_conference()->listUsers();
      if( empty(json_decode($encoded_users)->error) ) {
        add_submenu_page( 'zoom-video-conferencing', 'Meeting', __('Add Meeting', 'video-conferencing-with-zoom-api'), 'manage_options', 'zoom-video-conferencing-add-meeting', array( $this, 'zoom_video_conference_api_add_meeting' ) );
        add_submenu_page( 'zoom-video-conferencing', 'Users', __('Users', 'video-conferencing-with-zoom-api'), 'manage_options', 'zoom-video-conferencing-list-users', array( $this, 'zoom_video_conference_api_zoom_list_users' ) );
        add_submenu_page( 'zoom-video-conferencing', 'Add Users', __('Add Users', 'video-conferencing-with-zoom-api'), 'manage_options', 'zoom-video-conferencing-add-users' , array( $this, 'zoom_video_conference_api_add_zoom_users' ) );
        add_submenu_page( 'zoom-video-conferencing', 'Reports', __('Reports', 'video-conferencing-with-zoom-api'), 'manage_options', 'zoom-video-conferencing-reports', array( $this, 'zoom_video_conference_api_zoom_reports' ) );
      }
    }
    add_submenu_page( 'zoom-video-conferencing', 'Settings', __('Settings', 'video-conferencing-with-zoom-api'), 'manage_options', 'zoom-video-conferencing-settings', array( $this, 'zoom_video_conference_api_zoom_settings' ) );
  }

  /**
   * View list meetings page
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_api_zoom_list_meetings() {
    //Check if any transient by name is available
    $check_transient = get_transient('_zvc_user_lists');
    if( $check_transient ) {
      $users = $check_transient->users;
    } else {
       //Getting All Users
      $encoded_users = zoom_conference()->listUsers();
      if( !empty(json_decode($encoded_users)->error) ) {
        $error = json_decode($encoded_users)->error->message; 
      } else {
        //Decoding
        $decoded_users = json_decode($encoded_users);

        //storing data to transient and getting those data for fast load by setting to fetch every 15 minutes
        set_transient( '_zvc_user_lists', $decoded_users, 900 );
        $users = $decoded_users->users;
      }
    }

    if( isset($_GET['host_id']) ) {
      $encoded_meetings = zoom_conference()->listMeetings($_GET['host_id']);
      $decoded_meetings = json_decode($encoded_meetings);
      $meetings = $decoded_meetings->meetings;
    }

    //Get Template
    require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-list-meetings.php';
  }

  /**
   * View Add Meetings Page
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_api_add_meeting() {
    //Check if any transient by name is available
    $check_transient = get_transient('_zvc_user_lists');
    if( $check_transient ) {
      $users = $check_transient->users;
    } else {
      $encoded_users = zoom_conference()->listUsers();
      $decoded_users = json_decode($encoded_users);
        //storing data to transient and getting those data for fast load by setting to fetch every 15 minutes
      set_transient( '_zvc_user_lists', $decoded_users, 900 );
      $users = $decoded_users->users;
    }

    //Update a Meeting
    if( isset($_POST['update_meeting']) ) {
      check_admin_referer( '_zoom_update_meeting_nonce_action', '_zoom_update_meeting_nonce' );
      $update_meeting_arr = array(
        'meeting_id' => filter_input( INPUT_POST, 'meeting_id' ), 
        'host_id' => filter_input( INPUT_POST, 'userId' ), 
        'topic' => filter_input( INPUT_POST, 'meetingTopic' ),
        'start_date' => filter_input( INPUT_POST, 'start_date' ),
        'timezone' => filter_input( INPUT_POST, 'timezone' ),
        'duration' => filter_input( INPUT_POST, 'duration' ),
        'option_jbh' => filter_input( INPUT_POST, 'join_before_host' ),
        'option_host_video' => filter_input( INPUT_POST, 'option_host_video' ),
        'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
        'option_cn_meeting' => filter_input( INPUT_POST, 'option_cn_meeting' ),
        'option_in_meeting' => filter_input( INPUT_POST, 'option_in_meeting' ),
        'option_enforce_login' => filter_input( INPUT_POST, 'option_enforce_login' )
        );

      $meeting_updated = json_decode(zoom_conference()->updateMeetingInfo( $update_meeting_arr ));
      if( !empty($meeting_updated->error) ) {
        $message['error'] = $meeting_updated->error->message;
      } else {
        $this->_notice = sprintf(__("Updated meeting at %s with ID: %s", "video-conferencing-with-zoom-api"), $meeting_updated->updated_at, $meeting_updated->id );
        ZVC_notice()->zoom_video_conference_displaySuccess( $this->_notice );

        /**
         * Fires after meeting has been updated
         *
         * @since  2.0.1
         * @param meeting_id
         */
        do_action('zvc_after_update_meeting', $meeting_updated->id);

        zvc_redirect('?page=zoom-video-conferencing&host_id='.$update_meeting_arr['host_id']);
        exit;
      }
    }

    //Edit a Meeting
    if( isset($_GET['edit']) && isset($_GET['host_id']) ) {
      $meeting_info = json_decode(zoom_conference()->getMeetingInfo($_GET['edit'], $_GET['host_id']));
      //Get Editin Template
      require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-edit-meeting.php';
    } else {
      //Create a New Meeting
      if( isset($_POST['create_meeting']) ) {
        check_admin_referer( '_zoom_add_meeting_nonce_action', '_zoom_add_meeting_nonce' );
        $create_meeting_arr = array(
          'userId' => filter_input( INPUT_POST, 'userId' ), 
          'meetingTopic' => filter_input( INPUT_POST, 'meetingTopic' ),
          'start_date' => filter_input( INPUT_POST, 'start_date' ),
          'timezone' => filter_input( INPUT_POST, 'timezone' ),
          'password' => filter_input( INPUT_POST, 'password' ),
          'duration' => filter_input( INPUT_POST, 'duration' ),
          'join_before_host' => filter_input( INPUT_POST, 'join_before_host' ),
          'option_host_video' => filter_input( INPUT_POST, 'option_host_video' ),
          'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
          'option_cn_meeting' => filter_input( INPUT_POST, 'option_cn_meeting' ),
          'option_in_meeting' => filter_input( INPUT_POST, 'option_in_meeting' ),
          'option_enforce_login' => filter_input( INPUT_POST, 'option_enforce_login' )
          );

        $meeting_created = json_decode(zoom_conference()->createAMeeting( $create_meeting_arr ));
        if( !empty($meeting_created->error) ) {
          $message['error'] = $meeting_created->error->message;
        } else {
          $this->_notice = sprintf(__("Created meeting %s at %s. Join %s", "video-conferencing-with-zoom-api"), $meeting_created->topic, $meeting_created->created_at, "<a target='_blank' href='".$meeting_created->join_url."'>Here</a>" );
          ZVC_notice()->zoom_video_conference_displaySuccess( $this->_notice );

          /**
          * Fires after meeting has been Created
          *
          * @since  2.0.1
          * @param meeting_id, Host_id
          */
          do_action('zvc_after_create_meeting', $meeting_created->id, $meeting_created->host_id);

          zvc_redirect('?page=zoom-video-conferencing&host_id='.$meeting_created->host_id);
          exit;
        }
      }

      if( isset($_POST['create_meeting_add_more']) ) {
        check_admin_referer( '_zoom_add_meeting_nonce_action', '_zoom_add_meeting_nonce' );
        $create_meeting_arr = array(
          'userId' => filter_input( INPUT_POST, 'userId' ), 
          'meetingTopic' => filter_input( INPUT_POST, 'meetingTopic' ),
          'start_date' => filter_input( INPUT_POST, 'start_date' ),
          'timezone' => filter_input( INPUT_POST, 'timezone' ),
          'password' => filter_input( INPUT_POST, 'password' ),
          'duration' => filter_input( INPUT_POST, 'duration' ),
          'join_before_host' => filter_input( INPUT_POST, 'join_before_host' ),
          'option_host_video' => filter_input( INPUT_POST, 'option_host_video' ),
          'option_participants_video' => filter_input( INPUT_POST, 'option_participants_video' ),
          'option_cn_meeting' => filter_input( INPUT_POST, 'option_cn_meeting' ),
          'option_in_meeting' => filter_input( INPUT_POST, 'option_in_meeting' ),
          'option_enforce_login' => filter_input( INPUT_POST, 'option_enforce_login' )
          );

        $meeting_created = json_decode(zoom_conference()->createAMeeting( $create_meeting_arr ));
        if( !empty($meeting_created->error) ) {
          $message['error'] = $meeting_created->error->message;
        } else {
          $this->_notice = sprintf(__("Created meeting %s at %s. Join %s", "video-conferencing-with-zoom-api"), $meeting_created->topic, $meeting_created->created_at, "<a target='_blank' href='".$meeting_created->join_url."'>Here</a>" );
          ZVC_notice()->zoom_video_conference_displaySuccess( $this->_notice );

          /**
          * Fires after meeting has been Created
          *
          * @since  2.0.1
          * @param meeting_id, Host_id
          */
          do_action('zvc_after_create_meeting', $meeting_created->id, $meeting_created->host_id);

          zvc_redirect('?page=zoom-video-conferencing-add-meeting');
          exit;
        }
      }

      //Get Template
      require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-add-meetings.php';
    }
  }

  /**
   * List meetings page
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_api_zoom_list_users() {
    //Check if any transient by name is available
    $check_transient = get_transient('_zvc_user_lists');
    if( isset($_GET['flush']) == TRUE ) {
      if($check_transient) {
        delete_transient( '_zvc_user_lists' );
        $this->_notice = __("Flushed User Cache!", "video-conferencing-with-zoom-api");
        ZVC_notice()->zoom_video_conference_displaySuccess( $this->_notice );
        zvc_redirect('?page=zoom-video-conferencing-list-users');
      }
    }

    if( $check_transient ) {
      $users = $check_transient->users;
    } else {
      $encoded_users = zoom_conference()->listUsers();
      //Decoding
      $decoded_users = json_decode($encoded_users);

      //storing data to transient and getting those data for fast load by setting to fetch every 15 minutes
      set_transient( '_zvc_user_lists', $decoded_users, 900 );
      $users = $decoded_users->users;
    }

    //Get Template
    //Requiring Thickbox
    add_thickbox();
    require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-list-users.php';
  }

  /**
   * Add Zoom users view
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_api_add_zoom_users() {
    if(isset($_POST['add_zoom_user'])) {
      check_admin_referer( '_zoom_add_user_nonce_action', '_zoom_add_user_nonce' );
      $email = filter_input( INPUT_POST, 'email' );
      $first_name = filter_input( INPUT_POST, 'first_name' );
      $last_name = filter_input( INPUT_POST, 'last_name' );
      $type = filter_input( INPUT_POST, 'type' );
      $dept = filter_input( INPUT_POST, 'dept' );

      $created_user = zoom_conference()->createAUser($email, $first_name, $last_name, $type, $dept);
      $result = json_decode($created_user);
      if( !empty($result->error) ) {
        $message['error'] = $result->error->message;
      } else {
        $message['success'] = __("Created a User. Please check email for confirmation.", "video-conferencing-with-zoom-api");

        //After user has been created delete this transient in order to fetch latest Data.
        delete_transient('_zvc_user_lists');

        /**
        * Fires after a user has been Created
        *
        * @since  2.0.1
        * @param ID, EMAIL_ADDRESS
        */
        do_action( 'zvc_after_create_user', $created_user->id, $created_user->email );
      }
    }

    //Get Template
    require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-add-user.php';
  }

  /**
   * Zoom Rerports View
   *
   * @since  1.0.0
   * @changes in CodeBase
   * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
   */
  public function zoom_video_conference_api_zoom_reports() {
    if(isset($GET['tab'])) {
      $active_tab = $_GET[ 'tab' ];
    }
    $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'zoom_daily_report';

    //Get Template
    require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-reports.php';
  }

  /**
  * Zoom Settings View File
  *
  * @since  1.0.0
  * @changes in CodeBase
  * @author  Deepen Bajracharya <dpen.connectify@gmail.com>
  */
  public function zoom_video_conference_api_zoom_settings() {
    if(get_option('zoom_api_key') && get_option('zoom_api_secret')) {
      $encoded_users = zoom_conference()->listUsers();
      if( !empty(json_decode($encoded_users)->error) ) {
        ?>
        <div id="message" class="notice notice-error">
          <p><?php echo json_decode($encoded_users)->error->message; ?></p>
        </div>
        <?php
      }
    }

    if(isset($_POST['save_zoom_settings'])) {
      //Nonce
      check_admin_referer( '_zoom_settings_update_nonce_action', '_zoom_settings_nonce' );
      $zoom_api_key = filter_input( INPUT_POST, 'zoom_api_key' );
      $zoom_api_secret = filter_input( INPUT_POST, 'zoom_api_secret' );

      update_option( 'zoom_api_key', $zoom_api_key );
      update_option( 'zoom_api_secret', $zoom_api_secret, 'yes' );

      //After user has been created delete this transient in order to fetch latest Data.
      delete_transient('_zvc_user_lists');
      ?>
      <div id="message" class="notice notice-success is-dismissible">
        <p><?php _e('Successfully Updated. Please refresh this page.', 'video-conferencing-with-zoom-api'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'video-conferencing-with-zoom-api'); ?></span></button>
      </div>
      <?php
    }

    //Get Template
    require_once ZOOM_VIDEO_CONFERENCE_PLUGIN_ADMIN_PATH . '/views/tpl-settings.php';
  }
}

new Zoom_Video_Conferencing_Admin_Views();