<?php 
if(isset($GET['tab'])) {
	$active_tab = $_GET[ 'tab' ];
}
$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'zoom_daily_report';
?>
<div class="wrap">
	<h1><?php _e('Report', 'video-conferencing-with-zoom-api'); ?></h1>
	<h2 class="nav-tab-wrapper">
		<a href="?page=zoom_reports&tab=zoom_daily_report" class="nav-tab <?php echo $active_tab == 'zoom_daily_report' ? 'nav-tab-active' : ''; ?>"><?php _e('1. Daily Report', 'video-conferencing-with-zoom-api'); ?></a>
		<a href="?page=zoom_reports&tab=zoom_acount_report" class="nav-tab <?php echo $active_tab == 'zoom_acount_report' ? 'nav-tab-active' : ''; ?>"><?php _e('2. Account Report', 'video-conferencing-with-zoom-api'); ?></a>
	<!-- 	<a href="?page=zoom_reports&tab=zoom_user_report" class="nav-tab <?php echo $active_tab == 'zoom_user_report' ? 'nav-tab-active' : ''; ?>">3. User Report</a> -->
	</h2>

	<?php if( $active_tab == 'zoom_daily_report' ): ?>
		<?php $result = Zoom_Video_Api_Essentials::zoom_api_get_daily_report_html();  
		if( !is_object($result) && isset($_POST['zoom_check_month_year'])) { ?>
			<div id="message" class="notice notice-error">
				<p><?php echo $result; ?></p>
			</div>
			<?php } ?>
			<div class="zoom_dateinput_field">
				<form action="?page=zoom_reports" method="POST">
					<label><?php _e('Enter the date to check:', 'video-conferencing-with-zoom-api'); ?></label>
					<input name="zoom_month_year" class="monthYearPicker" />
					<input type="submit" name="zoom_check_month_year" value="Check">
				</form>
			</div>
			<table class="wp-list-table widefat fixed striped posts">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-primary"><span><?php _e('Date', 'video-conferencing-with-zoom-api'); ?></span></th>
						<th scope="col" class="manage-column column-primary"><span><?php _e('Meetings', 'video-conferencing-with-zoom-api'); ?></span></th>
						<th scope="col" class="manage-column column-primary"><span><?php _e('New Users', 'video-conferencing-with-zoom-api'); ?></span></th>
						<th scope="col"  class="manage-column column-primary"><span><?php _e('Participants', 'video-conferencing-with-zoom-api'); ?></span></th>
						<th scope="col" class="manage-column column-primary"><span><?php _e('Meeting Minutes', 'video-conferencing-with-zoom-api'); ?></span></th>
					</tr>
				</thead>
				<tbody id="the-list">	
					<?php 
					if( isset( $result->dates ) ) {
						$count = count($result->dates);
						foreach( $result->dates as $date ) { ?>
							<tr>
								<td><?php echo date('F j, Y', strtotime($date->date)); ?></td>
								<td><?php echo ($date->meetings > 0) ? '<strong style="color: #4300FF; font-size: 16px;">'.$date->meetings.'</strong>' : '-'; ?></td>
								<td><?php echo ($date->new_user > 0) ? '<strong style="color:#00A1B5; font-size: 16px;">'.$date->new_user.'</strong>' : '-'; ?></td>
								<td><?php echo ($date->participants > 0) ? '<strong style="color:#00AF00; font-size: 16px;">'.$date->participants.'</strong>' : '-'; ?></td>
								<td><?php echo ($date->meeting_minutes > 0) ? '<strong style="color:red; font-size: 16px;">'.$date->meeting_minutes.'</strong>' : '-'; ?></td>
							</tr>
							<?php
						}
					} else {?>
						<tr>
							<td colspan="5"><?php _e("Enter a value to Check..."); ?></td>
						</tr>
						<?php 
					}
					?>
				</tbody>
			</table>
		<?php elseif( $active_tab == 'zoom_acount_report' ): 
		$result = Zoom_Video_Api_Essentials::zoom_api_get_account_report_html(); 
		if(isset($_POST['zoom_check_account_info'])):
			if( $_POST['zoom_account_from'] == null || $_POST['zoom_account_to'] == null ): ?>
		<div id="message" class="notice notice-error">
			<p><?php echo $result; ?></p>
		</div>
	<?php else: ?>
		<div id="message" class="notice notice-success">
			<ul class="zoom_acount_lists">
				<li><?php echo isset($result->from) ? __('Searching From: ', 'video-conferencing-with-zoom-api') . $result->from . ' - ': null ; ?><?php echo isset($result->to) ? $result->to : null ; ?></li>
				<li><?php echo isset($result->total_records) ? __('Total Records Found: ', 'video-conferencing-with-zoom-api') . $result->total_records : null ; ?></li>
				<li><?php echo isset($result->total_meetings) ? __('Total Meetings Held: ', 'video-conferencing-with-zoom-api') . $result->total_meetings : null ; ?></li>
				<li><?php echo isset($result->total_participants) ? __('Total Participants Involved: ', 'video-conferencing-with-zoom-api') . $result->total_participants : null ; ?></li>
				<li><?php echo isset($result->total_meeting_minutes) ? __('Total Meeting Minutes Combined: ', 'video-conferencing-with-zoom-api') . $result->total_meeting_minutes : null ; ?></li>
			</ul>
		</div>
	<?php endif; endif; ?>
	<div class="zoom_dateinput_field">
		<p><?php _e('Get account report for a specified period.', 'video-conferencing-with-zoom-api'); ?>  <a onclick="window.print();" href="javascript:void(0);">Print</a></p>
		<o><strong><?php _e('Note: Report a maximum of one month. For example, if "from" is set to "2015-08-05" and "to" is "2015-10-10" it will adjust "from" to "2015-09-10".', 'video-conferencing-with-zoom-api'); ?></strong></p>
			<form action="?page=zoom_reports&tab=zoom_acount_report" method="POST">
				<label><?php _e('From', 'video-conferencing-with-zoom-api'); ?></label>
				<input name="zoom_account_from" class="zoom_datepicker" />
				<label><?php _e('To', 'video-conferencing-with-zoom-api'); ?></label>
				<input name="zoom_account_to" class="zoom_datepicker" />
				<input type="submit" name="zoom_check_account_info" value="Check">
			</form>
		</div>
		<table class="wp-list-table widefat fixed striped posts">
			<thead>
				<tr>
					<th scope="col" class="manage-column column-primary"><span><?php _e('By', 'video-conferencing-with-zoom-api'); ?></span></th>
					<th scope="col" class="manage-column column-primary"><span><?php _e('Meetings Held', 'video-conferencing-with-zoom-api'); ?></span></th>
					<th scope="col"  class="manage-column column-primary"><span><?php _e('Total Participants', 'video-conferencing-with-zoom-api'); ?></span></th>
					<th scope="col" class="manage-column column-primary"><span><?php _e('Total Meeting Minutes', 'video-conferencing-with-zoom-api'); ?></span></th>
					<th scope="col" class="manage-column column-primary"><span><?php _e('Last Login Time', 'video-conferencing-with-zoom-api'); ?></span></th>
				</tr>
			</thead>
			<tbody id="the-list">	
				<?php 
				if( isset( $result->users ) ) {
					$count = count($result->users);
					if($count == 0) {
						echo '<tr colspan="5"><td>No Records Found..</td></tr>';
					} else {
						foreach( $result->users as $user ) { ?>
							<tr>
								<td><?php echo $user->email; ?></td>
								<td><?php echo $user->meetings; ?></td>
								<td><?php echo $user->participants; ?></td>
								<td><?php echo $user->meeting_minutes; ?></td>
								<td><?php echo date('F j, Y g:i a', strtotime($user->last_login_time)); ?></td>
							</tr>
							<?php
						}
					}
				} else {?>
					<tr>
						<td colspan="5"><?php _e("Enter a value to Check..."); ?></td>
					</tr>
					<?php 
				}
				?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
