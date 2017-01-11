<div id="zvc-cover" style="display: none;"></div>
<div class="wrap">
	<h2><?php _e("Users", "video-conferencing-with-zoom-api"); ?></h2> <a href="?page=zoom-video-conferencing-list-users&flush=true"><?php _e('Flush User Cache', 'video-conferencing-with-zoom-api'); ?></a>
	<div id="message" style="display:none;" class="notice notice-success show_on_user_delete_success"><p></p></div>
	<?php if( !empty($error) ) { ?>
	<div id="message" class="notice notice-error"><p><?php echo $error; ?></p></div>
	<?php } else { ?>
	<div class="zvc_listing_table">
		<table id="zvc_users_list_table" class="display" width="100%">
			<thead>
				<tr>
					<th class="zvc-text-left"><?php _e('SN', 'video-conferencing-with-zoom-api'); ?></th>
					<th class="zvc-text-left"><?php _e('User ID', 'video-conferencing-with-zoom-api'); ?></th>
					<th class="zvc-text-left"><?php _e('Email', 'video-conferencing-with-zoom-api'); ?></th>
					<th class="zvc-text-left"><?php _e('Name', 'video-conferencing-with-zoom-api'); ?></th>
					<th class="zvc-text-left"><?php _e('Created On', 'video-conferencing-with-zoom-api'); ?></th>
					<th class="zvc-text-left"><?php _e('Action', 'video-conferencing-with-zoom-api'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php $count = 1; foreach($users as $user): ?>
				<tr>
					<td><?php echo $count++; ?></td>
					<td><?php echo $user->id; ?></td>
					<td><?php echo $user->email; ?></td>
					<td><?php echo $user->first_name . ' ' . $user->last_name; ?></td>
					<td><?php echo date('F j, Y, g:i a', strtotime($user->created_at)); ?></td>
					<div id="zvc_getting_user_info" style="display:none;">
						<div class="zvc_getting_user_info_content"></div>
					</div>
					<td><a href="javascript:void(0);" onclick="confirm_delete_user('<?php echo $user->id; ?>')"><?php _e("Trash", "video-conferencing-with-zoom-api"); ?></a> <a href="#TB_inline?width=600&inlineId=zvc_getting_user_info" class="thickbox" onclick="get_user_info('<?php echo $user->id; ?>')">View</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php } ?>
</div>
