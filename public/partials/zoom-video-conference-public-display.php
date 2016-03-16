<?php

/**
 *
 * @link       http://www.deepenbajracharya.com.np
 * @since      1.0.0
 *
 * @package    Zoom_Video_Conference
 * @subpackage Zoom_Video_Conference/public/partials
 */

$zoom = new ZoomAPI();
$listUsers = $zoom->listUsers();
$listingUsers = json_decode($listUsers, true);
?>
<script type="text/javascript">
	jQuery(function($) {
		$( "#start_date" ).datepicker();
		$( "#start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		$('#start_time').timepicker({ timeFormat: 'H:i:s',  step: 15 });
	});
</script>
<?php $zoom_userId = get_user_meta( get_current_user_id(), 'zoom_meta_for_user', true ); ?>
<div class="modal bs-example-modal-lg" id="create-zoom" tabindex="10" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Add a Video</h4>
			</div>
			<div class="modal-body">
				<div class="notification"></div>
				<form action="#" id="save_zoom_video_form" method="post">
					<?php if($zoom_userId): ?>	
						<input type="hidden" value="<?php echo $zoom_userId; ?>" name="userId" id="userId">
					<?php endif; ?>
					<div class="form-group">
						<label for="start">Course Title</label>
						<input type="text" class="form-control" name="course_title" id="course_title" placeholder="Course Title">
					</div>
					<div class="form-group">
						<label for="start">Start Date/ Time</label>
						<input type="text" class="form-control" name="start_date" id="start_date" placeholder="Start Date">
						<input type="text" class="form-control" name="start_time" id="start_time" placeholder="Start Time">
					</div>
					<?php if(is_super_admin()): ?>
						<div class="form-group">
							<label for="userId">User</label>
							<select name="userId" id="userId" class="form-control">
								<?php foreach($listingUsers['users'] as $listingUser):  ?>
									<option value="<?php echo $listingUser['id']; ?>"><?php echo $listingUser['first_name'] . ' ( '. $listingUser['email'] .' )'; ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label for="timezone">Time Zone</label>
						<select class="form-control" name="timezone" id="timezone">
							<option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
							<option value="Pacific/Pago_Pago">(GMT-11:00) Pago Pago</option>
							<option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
							<option value="America/Anchorage">(GMT-9:00) Alaska</option>
							<option value="America/Vancouver">(GMT-8:00) Vancouver</option>
							<option value="America/Los_Angeles">(GMT-8:00) Pacific Time (US and Canada)</option>
							<option value="America/Tijuana">(GMT-8:00) Tijuana</option>
							<option value="America/Edmonton">(GMT-7:00) Edmonton</option>
							<option value="America/Denver">(GMT-7:00) Mountain Time (US and Canada)</option>
							<option value="America/Phoenix">(GMT-7:00) Arizona</option>
							<option value="America/Mazatlan">(GMT-7:00) Mazatlan</option>
							<option value="America/Winnipeg">(GMT-6:00) Winnipeg</option>
							<option value="America/Regina">(GMT-6:00) Saskatchewan</option>
							<option value="America/Chicago">(GMT-6:00) Central Time (US and Canada)</option>
							<option value="America/Mexico_City">(GMT-6:00) Mexico City</option>
							<option value="America/Guatemala">(GMT-6:00) Guatemala</option>
							<option value="America/El_Salvador">(GMT-6:00) El Salvador</option>
							<option value="America/Managua">(GMT-6:00) Managua</option>
							<option value="America/Costa_Rica">(GMT-6:00) Costa Rica</option>
							<option value="America/Tegucigalpa">(GMT-6:00) Tegucigalpa</option>
							<option value="America/Montreal">(GMT-5:00) Montreal</option>
							<option value="America/New_York">(GMT-5:00) Eastern Time (US and Canada)</option>
							<option value="America/Indianapolis">(GMT-5:00) Indiana (East)</option>
							<option value="America/Panama">(GMT-5:00) Panama</option>
							<option value="America/Bogota">(GMT-5:00) Bogota</option>
							<option value="America/Lima">(GMT-5:00) Lima</option>
							<option value="America/Caracas">(GMT-4:30) Caracas</option>
							<option value="America/Halifax">(GMT-4:00) Halifax</option>
							<option value="America/Puerto_Rico">(GMT-4:00) Puerto Rico</option>
							<option value="Canada/Atlantic">(GMT-4:00) Atlantic Time (Canada)</option>
							<option value="America/St_Johns">(GMT-3:30) Newfoundland and Labrador</option>
							<option value="America/Santiago">(GMT-3:00) Santiago</option>
							<option value="America/Araguaina">(GMT-3:00) Brasilia</option>
							<option value="America/Argentina/Buenos_Aires">(GMT-3:00) Buenos Aires, Georgetown</option>
							<option value="America/Godthab">(GMT-3:00) Greenland</option>
							<option value="America/Montevideo">(GMT-2:00) Montevideo</option>
							<option value="America/Sao_Paulo">(GMT-2:00) Sao Paulo</option>
							<option value="Atlantic/Azores">(GMT-1:00) Azores</option>
							<option value="Atlantic/Cape_Verde">(GMT-1:00) Cape Verde Islands</option>
							<option value="UTC">(GMT+0:00) Universal Time UTC</option>
							<option value="Etc/Greenwich">(GMT+0:00) Greenwich Mean Time</option>
							<option value="Atlantic/Reykjavik">(GMT+0:00) Reykjavik</option>
							<option value="Europe/Dublin">(GMT+0:00) Dublin</option>
							<option value="Europe/London">(GMT+0:00) London</option>
							<option value="Europe/Lisbon">(GMT+0:00) Lisbon</option>
							<option value="Africa/Casablanca">(GMT+0:00) Casablanca</option>
							<option value="Africa/Nouakchott">(GMT+0:00) Nouakchott</option>
							<option value="Europe/Belgrade">(GMT+1:00) Belgrade, Bratislava, Ljubljana</option>
							<option value="CET">(GMT+1:00) Sarajevo, Skopje, Zagreb</option>
							<option value="Europe/Oslo">(GMT+1:00) Oslo</option>
							<option value="Europe/Copenhagen">(GMT+1:00) Copenhagen</option>
							<option value="Europe/Brussels">(GMT+1:00) Brussels</option>
							<option value="Europe/Berlin">(GMT+1:00) Amsterdam, Berlin, Rome, Stockholm, Vienna</option>
							<option value="Europe/Amsterdam">(GMT+1:00) Amsterdam</option>
							<option value="Europe/Rome">(GMT+1:00) Rome</option>
							<option value="Europe/Stockholm">(GMT+1:00) Stockholm</option>
							<option value="Europe/Vienna">(GMT+1:00) Vienna</option>
							<option value="Europe/Luxembourg">(GMT+1:00) Luxembourg</option>
							<option value="Europe/Paris" selected="selected">(GMT+1:00) Paris</option>
							<option value="Europe/Zurich">(GMT+1:00) Zurich</option>
							<option value="Europe/Madrid">(GMT+1:00) Madrid</option>
							<option value="Africa/Bangui">(GMT+1:00) West Central Africa</option>
							<option value="Africa/Algiers">(GMT+1:00) Algiers</option>
							<option value="Africa/Tunis">(GMT+1:00) Tunis</option>
							<option value="Europe/Warsaw">(GMT+1:00) Warsaw</option>
							<option value="Europe/Prague">(GMT+1:00) Prague Bratislava</option>
							<option value="Europe/Budapest">(GMT+1:00) Budapest</option>
							<option value="Europe/Helsinki">(GMT+2:00) Helsinki</option>
							<option value="Africa/Harare">(GMT+2:00) Harare, Pretoria</option>
							<option value="Europe/Sofia">(GMT+2:00) Sofia</option>
							<option value="Europe/Istanbul">(GMT+2:00) Istanbul</option>
							<option value="Europe/Athens">(GMT+2:00) Athens</option>
							<option value="Europe/Bucharest">(GMT+2:00) Bucharest</option>
							<option value="Asia/Nicosia">(GMT+2:00) Nicosia</option>
							<option value="Asia/Beirut">(GMT+2:00) Beirut</option>
							<option value="Asia/Damascus">(GMT+2:00) Damascus</option>
							<option value="Asia/Jerusalem">(GMT+2:00) Jerusalem</option>
							<option value="Asia/Amman">(GMT+2:00) Amman</option>
							<option value="Africa/Tripoli">(GMT+2:00) Tripoli</option>
							<option value="Africa/Cairo">(GMT+2:00) Cairo</option>
							<option value="Africa/Johannesburg">(GMT+2:00) Johannesburg</option>
							<option value="Europe/Kiev">(GMT+2:00) Kiev</option>
							<option value="Africa/Nairobi">(GMT+3:00) Nairobi</option>
							<option value="Europe/Moscow">(GMT+3:00) Moscow</option>
							<option value="Asia/Baghdad">(GMT+3:00) Baghdad</option>
							<option value="Asia/Kuwait">(GMT+3:00) Kuwait</option>
							<option value="Asia/Riyadh">(GMT+3:00) Riyadh</option>
							<option value="Asia/Bahrain">(GMT+3:00) Bahrain</option>
							<option value="Asia/Qatar">(GMT+3:00) Qatar</option>
							<option value="Asia/Aden">(GMT+3:00) Aden</option>
							<option value="Africa/Khartoum">(GMT+3:00) Khartoum</option>
							<option value="Africa/Djibouti">(GMT+3:00) Djibouti</option>
							<option value="Africa/Mogadishu">(GMT+3:00) Mogadishu</option>
							<option value="Asia/Tehran">(GMT+3:30) Tehran</option>
							<option value="Asia/Dubai">(GMT+4:00) Dubai</option>
							<option value="Asia/Muscat">(GMT+4:00) Muscat</option>
							<option value="Asia/Baku">(GMT+4:00) Baku, Tbilisi, Yerevan</option>
							<option value="Asia/Kabul">(GMT+4:30) Kabul</option>
							<option value="Asia/Yekaterinburg">(GMT+5:00) Yekaterinburg</option>
							<option value="Asia/Tashkent">(GMT+5:00) Islamabad, Karachi, Tashkent</option>
							<option value="Asia/Calcutta">(GMT+5:30) India</option>
							<option value="Asia/Kolkata">(GMT+5:30) Mumbai, Kolkata, New Delhi</option>
							<option value="Asia/Kathmandu">(GMT+5:45) Kathmandu</option>
							<option value="Asia/Novosibirsk">(GMT+6:00) Novosibirsk</option>
							<option value="Asia/Almaty">(GMT+6:00) Almaty</option>
							<option value="Asia/Dacca">(GMT+6:00) Dacca</option>
							<option value="Asia/Dhaka">(GMT+6:00) Astana, Dhaka</option>
							<option value="Asia/Krasnoyarsk">(GMT+7:00) Krasnoyarsk</option>
							<option value="Asia/Bangkok">(GMT+7:00) Bangkok</option>
							<option value="Asia/Saigon">(GMT+7:00) Vietnam</option>
							<option value="Asia/Jakarta">(GMT+7:00) Jakarta</option>
							<option value="Asia/Irkutsk">(GMT+8:00) Irkutsk, Ulaanbaatar</option>
							<option value="Asia/Shanghai">(GMT+8:00) Beijing, Shanghai</option>
							<option value="Asia/Hong_Kong">(GMT+8:00) Hong Kong</option>
							<option value="Asia/Taipei">(GMT+8:00) Taipei</option>
							<option value="Asia/Kuala_Lumpur">(GMT+8:00) Kuala Lumpur</option>
							<option value="Asia/Singapore">(GMT+8:00) Singapore</option>
							<option value="Australia/Perth">(GMT+8:00) Perth</option>
							<option value="Asia/Yakutsk">(GMT+9:00) Yakutsk</option>
							<option value="Asia/Seoul">(GMT+9:00) Seoul</option>
							<option value="Asia/Tokyo">(GMT+9:00) Osaka, Sapporo, Tokyo</option>
							<option value="Australia/Darwin">(GMT+9:30) Darwin</option>
							<option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
							<option value="Pacific/Port_Moresby">(GMT+10:00) Guam, Port Moresby</option>
							<option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
							<option value="Asia/Magadan">(GMT+10:00) Magadan</option>
							<option value="Australia/Adelaide">(GMT+10:30) Adelaide</option>
							<option value="Australia/Sydney">(GMT+11:00) Canberra, Melbourne, Sydney</option>
							<option value="Australia/Hobart">(GMT+11:00) Hobart</option>
							<option value="SST">(GMT+11:00) Solomon Islands</option>
							<option value="Pacific/Noumea">(GMT+11:00) New Caledonia</option>
							<option value="Asia/Kamchatka">(GMT+12:00) Kamchatka</option>
							<option value="Pacific/Fiji">(GMT+13:00) Fiji Islands, Marshall Islands</option>
							<option value="Pacific/Auckland">(GMT+13:00) Auckland, Wellington</option>
						</select>
					</div>
					<div class="form-group">
						<label for="meeting_duration">Meeting Duration(in Minutes)</label>
						<input type="number" class="form-control" name="meeting_duration" id="meeting_duration" placeholder="Meeting Duration">
					</div>
					<div class="form-group">
					<label>Enable Join Before Host ?</label>
						<input type="radio" name="join_before_host" value="true"> Yes
         	 	<input type="radio" name="join_before_host" value="false" checked> No
					</div>
					<div class="form-group">
					<label>Enable Participant Video ?</label>
						<input type="radio" name="participant_video" value="true"> Yes
         	 <input type="radio" name="participant_video" value="false" checked> No
					</div>
			
					<div class="form-group">
						<input type="submit" class="save_zoom_video" name="save_zoom_video" class="btn btn-primary" value="Save">
						<button type="button" class="btn btn-default" id="close_btn" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
