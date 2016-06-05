/**
 * Script files for Extra Functionality @techies dpen
 *
 * @since  1.0.0
 */

 jQuery(document).ready(function($) {
 	$('#vibe_zoom_video').attr('disabled', 'disabled');

 	$('.create_user').click(function(e) {
 		e.preventDefault();
 		var instrutor_id = $('.email option:selected').attr('inst_id');
 		var email = $('.email').val();
 		var type = $('#type').val();
 		var first_name = $('#first_name').val();
 		var last_name = $('#last_name').val();
 		var dept = $('#dept').val();
 		if(first_name == null || first_name == "" && last_name == null || last_name == "") {
 			alert('Required Fields Missing !!');
 			return false;
 		}
 		$.ajax ({
 			type: 'POST',
 			url: ajaxurl,
 			data: {inst_id: instrutor_id, type: type, email: email, first_name: first_name, last_name: last_name, dept: dept, action: 'zoom_meta_for_user'},
 			success: function(res) {
 				$('.msg_contain').html(res);
 				setTimeout(function() {
 					location.reload();
 				}, 2000);
 			}
 		});
 	});

 	$( "#datepicker1" ).datepicker();
 	$( "#datepicker1" ).datepicker( "option", "dateFormat", "yy-mm-ddT" );
 	$('input.timepicker1').timepicker({ timeFormat: 'H:mm:ssZ',  interval: 15 });
 	$("#datepicker1").datepicker('setDate', $('.start_date_hidden').val());

 	$('.pwd_enabled').click(function() {
 		if(this.checked){
 			$(".password_zoom").show();
 			$(".password_zoom").css('visibility','visible');
 		} else {
 			$(".password_zoom").hide();
 			$(".password_zoom").css('visibility','hidden');
 		}
 	});

 	$( ".zoom_datepicker" ).datepicker();
 	$( ".zoom_datepicker" ).datepicker("option", "dateFormat", "yy-mm-dd");

 	$('.monthYearPicker').datepicker({
 		changeMonth: true,
 		changeYear: false,
 		showButtonPanel: true,
 		dateFormat: 'MM yy'
 	}).focus(function() {
 		var thisCalendar = $(this);
 		$('.ui-datepicker-calendar').detach();
 		$('.ui-datepicker-close').click(function() {
 			var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
 			var year = $("#ui-datepicker-div .ui-datepicker-year").html();
 			thisCalendar.datepicker('setDate', new Date(year, month, 1));
 		});
 	});
 	
 });

 /** FUNCTION FOR DELETING THE LINKED COURSE IN COURSE PAGE */
 function delete_linked_course(post_id) {
 	var r = confirm("Confirm Delete ?");
 	jQuery('.linked_result_diplay').append('<p class="wait">Please Wait... Deleting Data....</p>');
 	if (r == true) {
 		jQuery.post(ajaxurl + '?action=delete_linked_post', { post_id : post_id }).done(function(result) {
 			jQuery('.wait').remove();
 			jQuery('#title_'+post_id).remove();
 		});
 	} else {
 		return false;
 	}
 }