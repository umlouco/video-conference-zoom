<?php

class ZoomUserMeetings extends ZoomUser {

	private static $instance = null;

	private $zoom_user_meeting_url = 'https://api.zoom.us/v2/users/me/meetings';


	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
	}

	public function display_user_meetings() {

		$stored = $this->get_stored_zoom_user_info();

		$header_args      = array(
			'headers' => array(
				'Authorization' => $stored['zoom_user_token_info']['token_type'] . ' ' . $stored['zoom_user_token_info']['access_token'],
			),
		);
		$meeting_response = wp_remote_get( $this->zoom_user_meeting_url, $header_args );

		$meetings_obj = json_decode( $meeting_response['body'] );

		// preint( $meetings_obj );

		?>

		<style>

			ul {
				list-style: none;
			}

			ul li {
				display: inline-block;
				margin: 5px 12px;
			}

			table {
				width: 95% !important;
				max-width: 1200px !important;
			}

			table th.col-key {
				width: 250px;
			}
		</style>

		<ul>
			<li><a href="<?php echo $this->redirect_uri; ?>">My Info</a></li>
			<li><a href="<?php echo $this->revoke_uri; ?>">Revoke Access</a></li>
			<li><a href="<?php echo $meeting_url; ?>">Meetings</a></li>
			<li><a href="<?php echo $webinar_url; ?>">Webinar</a></li>
			<li><a href="<?php echo $meeting_url; ?>">Scopes</a></li>
			<li><a href="<?php echo $meeting_url; ?>">Settings</a></li>
		</ul>

		<table>
			<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Date</th>
				<th>Timezone</th>
				<th>Join URL</th>
			</tr>
			</thead>

			<tbody>
			<?php foreach ( $meetings_obj->meetings as $meeting ) : ?>
				<tr>
					<td><?php echo $meeting->id; ?></td>
					<td><?php echo $meeting->topic; ?></td>
					<td><?php echo $meeting->start_time; ?></td>
					<td><?php echo $meeting->timezone; ?></td>
					<td><?php echo $meeting->join_url; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<?php

	}
}
