<?php

/**
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/admin/partials
 */

if(get_option('zoom_api_key') && get_option('zoom_api_secret')){
	$zoom = new ZoomAPI();
	$data = $zoom->listUsers();
	$result = json_decode($data, true);
	$count = 1;
	if($result['error']) {
		?>	
		<div id="message" class="notice notice-error is-dismissible">
			<p><?php echo $result['error']['message'] ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'zoom-video-conference'); ?></span></button>
		</div>
		<?php
	} 
	$pending_user_data = $zoom->listPendingUsers();
	$peding_usrs = json_decode($pending_user_data, true);
	$cnt=0;
	foreach($peding_usrs['users'] as $pending_usr) {
		$cnt++;
	}
	$url = $_SERVER['REQUEST_URI']; //GETTING THE URL
	$query_string = parse_url($url, PHP_URL_QUERY); //FILTERING THE QUERY VAR OF THE URL
	if($query_string == 'page=zoom_users'):
		?>
	<div class="wrap"><h1>Users List <a href="?page=add_zoom_users" class="page-title-action"><?php _e('Add a User', 'zoom-video-conference'); ?></a><a href="?page=zoom_users&zoom_status=pending" class="page-title-action"><?php _e('Add a User', 'zoom-video-conference'); ?>Pending Users (<?php echo $cnt; ?>)</a></h1>
		<div id="message" class="notice notice-success is-dismissible delete_success" style="display:none;">
			<p><?php _e('Successfully Deleted', 'zoom-video-conference'); ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'zoom-video-conference'); ?></span></button>
		</div>
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th width="4%" scope="col" id="sn" class="manage-column column-primary"><?php _e('SN', 'zoom-video-conference'); ?></th>
					<th scope="col" id="sn" class="manage-column column-primary"><?php _e('User ID', 'zoom-video-conference'); ?></th>
					<th scope="col" id="meeting_id" class="manage-column column-primary"><span><?php _e('Email', 'zoom-video-conference'); ?></span></th>
					<th scope="col" id="topic" class="manage-column column-primary"><span><?php _e('Name', 'zoom-video-conference'); ?></span></th>
					<th scope="col" id="created_on" class="manage-column column-primary"><span><?php _e('Created Date', 'zoom-video-conference'); ?></span></th>
					<th width="6%" scope="col" id="action" class="manage-column column-primary"><span><?php _e('Action', 'zoom-video-conference'); ?></span></th>
				</tr>
			</thead>
			<tbody id="the-list">	
				<?php if( count($result['users']) > 0 ): ?>
					<?php foreach($result['users'] as $result): ?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo $result['id']; ?></td>
							<td><?php echo $result['email']; ?></td>
							<td><?php echo $result['first_name'] . ' ' . $result['last_name']; ?></td>
							<td><?php echo $result['created_at']; ?></td>
							<td><a href="#" onclick="confirm_delete_user('<?php echo $result['id']; ?>')"><?php echo ($result['id'] == 'DWAZWFRDTo-XGz8IuTjlzw') ? '' : 'Trash'; ?></a></td>
						</tr>
					<?php endforeach; ?>

				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		function confirm_delete_user(id) {
			var r = confirm("Confirm Delete ?");
			if (r == true) {
				var data = { userId : id, action : 'delete_pending' };
				var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
				jQuery.post(ajax_url, data).done(function(result) {
					jQuery('.delete_success').show();
					var reload_me = function() {
						location.reload();
					};
					setTimeout(reload_me, 2000);
				});
			} else {
				return false;
			}

		}
	</script>
<?php elseif($query_string == 'page=zoom_users&zoom_status=pending'): ?>
	<?php
	$zoom = new ZoomAPI();
	$data = $zoom->listPendingUsers();
	$result = json_decode($data, true);
	$count = 1;

	if($result['error']) {
		?>	
		<div id="message" class="notice notice-error is-dismissible">
			<p><?php echo $result['error']['message'] ?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.', 'zoom-video-conference'); ?></span></button>
		</div>
		<?php
	} 
	?>
	<div class="wrap"><h1>Users List <a href="?page=zoom_users" class="page-title-action">Back</a></h1>
		<div id="message" class="notice notice-success is-dismissible delete_success" style="display:none;">
			<p>Successfully Deleted</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th width="4%" scope="col" id="sn" class="manage-column column-primary"><?php _e('SN', 'zoom-video-conference'); ?></th>
					<th scope="col" id="sn" class="manage-column column-primary"><?php _e('User ID', 'zoom-video-conference'); ?></th>
					<th scope="col" id="meeting_id" class="manage-column column-primary"><span><?php _e('Email', 'zoom-video-conference'); ?></span></th>
					<th scope="col" id="topic" class="manage-column column-primary"><span><?php _e('Name', 'zoom-video-conference'); ?></span></th>
					<th scope="col" id="created_on" class="manage-column column-primary"><span><?php _e('Created Date', 'zoom-video-conference'); ?></span></th>
					<th width="6%" scope="col" id="action" class="manage-column column-primary"><span><?php _e('Action', 'zoom-video-conference'); ?></span></th>
				</tr>
			</thead>
			<tbody id="the-list">	
				<?php if( count($result['users']) > 0 ): ?>
					<?php foreach($result['users'] as $result): ?>
						<tr>
							<td><?php echo $count++; ?></td>
							<td><?php echo $result['id']; ?></td>
							<td><?php echo $result['email']; ?></td>
							<td><?php echo $result['first_name'] . ' ' . $result['last_name']; ?></td>
							<td><?php echo $result['created_at']; ?></td>
							<td><a href="#" onclick="return confirm_delete('<?php echo $result['id']; ?>')">Trash</a></td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr><td colspan="9"><?php _e('No pending Users.', 'zoom-video-conference'); ?></td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
		function confirm_delete(id) {
			var r = confirm("Confirm Delete ?");
			if (r == true) {
				var data = { userId : id, action : 'delete_pending' };
				var ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
				jQuery.post(ajax_url, data).done(function(result) {
					jQuery('.delete_success').show();
					var reload_me = function() {
						location.reload();
					};
					setTimeout(reload_me, 2000);
				});
			} else {
				return false;
			}

		}
	</script>
<?php endif; ?>
<?php } else { ?>
<div class="wrap"><h1><?php _e('Users List', 'zoom-video-conference'); ?></a></h1>
	<div id="message" class="notice notice-error">
	<p><?php _e('The API Key seems to missing. <a href="?page=zoom_setting">Click Here</a> to Configure.', 'zoom-video-conference'); ?></p>
	</div>
</div>
<?php } ?>