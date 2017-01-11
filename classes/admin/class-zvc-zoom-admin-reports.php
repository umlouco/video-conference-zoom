<?php
/**
 * Class for Rendering Admin Notices
 *
 * @author  Deepen
 * @since  2.0.0
 */
class Zoom_Video_Conferencing_Reports {

    private static $instance;

    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'zvc_enqueue_reports_scripts'));
    }

    private function __clone() {}
    private function __wakeup() {}

    static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function zoom_api_get_daily_report_html() {
        $months = array( 
            1 => 'January',
            2 => 'February',
            3 => 'March', 
            4 => 'April',  
            5 => 'May', 
            6 => 'June', 
            7 => 'July', 
            8 => 'August', 
            9 => 'September', 
            10 => 'October', 
            11 => 'November', 
            12 => 'December'
            );
        if(isset($_POST['zoom_check_month_year'])) {
            $zoom_monthyear = $_POST['zoom_month_year'];
            if( $zoom_monthyear == NULL || $zoom_monthyear == "" ) {
                $error = __("Date field cannot be Empty !!", "zoom-video-conference");
                return $error;
            } else {
                $exploded_data = explode(' ', $zoom_monthyear);
                foreach( $months as $key => $month ) {
                    if($exploded_data[0] == $month) {
                        $month_int = $key;
                    }
                }
                $year = $exploded_data[1];
                $result = zoom_conference()->getDailyReport($month_int, $year);
                $decoded_data = json_decode($result);
                return $decoded_data;
            }
        } else {
            return false;
        }
    }

    public function zoom_api_get_account_report_html() {
        if( isset($_POST['zoom_account_from']) && isset($_POST['zoom_account_to']) ) {
            $zoom_account_from = $_POST['zoom_account_from'];
            $zoom_account_to = $_POST['zoom_account_to'];
            if( $zoom_account_from == null || $zoom_account_to == null ) {
                $error = __("The fields cannot be Empty !!", "zoom-video-conference");
                return $error;
            } else {
                $result = zoom_conference()->getAccountReport($zoom_account_from, $zoom_account_to);
                $decoded_data = json_decode($result);
                return $decoded_data;
            }
        }
    }

    public function zvc_enqueue_reports_scripts() {
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_style( 'jquery-ui-datepicker-zvc' , 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css');
    }
}

function ZVC_Reports() {
    return Zoom_Video_Conferencing_Reports::getInstance();
}

ZVC_Reports();