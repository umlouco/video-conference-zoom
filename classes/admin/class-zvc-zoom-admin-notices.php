<?php
/**
 * Class for Rendering Admin Notices
 *
 * @author  Deepen
 * @since  2.0.0
 */
class Zoom_Video_Conferencing_AdminNotice {

    private static $instance;

    const NOTICE_FIELD = 'zoom_video_admin_notice_message';

    protected function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    static function getInstance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function zoom_video_conference_render_admin_notice() {
        $option = get_option(self::NOTICE_FIELD);
        $message = isset($option['message']) ? $option['message'] : false;
        $noticeLevel = ! empty($option['notice-level']) ? $option['notice-level'] : 'notice-error';
        if ($message) {
            echo "<div class='notice {$noticeLevel} is-dismissible'><p>{$message}</p></div>";
            delete_option(self::NOTICE_FIELD);
        }
    }

    public function zoom_video_conference_displayError($message) {
        $this->zoom_video_conference_updateOption($message, 'notice-error');
    }

    public function zoom_video_conference_displayWarning($message) {
        $this->zoom_video_conference_updateOption($message, 'notice-warning');
    }

    public function zoom_video_conference_displayInfo($message) {
        $this->zoom_video_conference_updateOption($message, 'notice-info');
    }

    public function zoom_video_conference_displaySuccess($message) {
        $this->zoom_video_conference_updateOption($message, 'notice-success');
    }

    protected function zoom_video_conference_updateOption($message, $noticeLevel) {
        update_option(self::NOTICE_FIELD, [
            'message' => $message,
            'notice-level' => $noticeLevel
            ]);
    }
}

function ZVC_notice() {
    return Zoom_Video_Conferencing_AdminNotice::getInstance();
}

add_action( 'admin_notices', [ZVC_notice(), 'zoom_video_conference_render_admin_notice'] );