/**
 * Script files for Extra Functionality @techies dpen
 *
 * @since  1.0.0
 */

 jQuery(document).ready(function($) {
 	$('#vibe_zoom_video').attr('disabled', 'disabled');

 	$('.create_user').click(function(e) {
 		e.preventDefault();
 		var email = $('.email').val();
 		var type = $('#type').val();
 		var first_name = $('#first_name').val();
 		var last_name = $('#last_name').val();
 		var dept = $('#dept').val();
 		if( email == "" || first_name == null || first_name == "" && last_name == null || last_name == "") {
 			$('#message').show();
 			$('#message').html('<p>Required Fields Missing !!</p>');
 			return false;
 		}
 		if( !isValidEmailAddress( email ) )  { 
 			$('#message').show();
 			$('#message').html('<p>Incorrect Email Address.</p>');
 			return false;
 		}
 		$.ajax ({
 			type: 'POST',
 			url: ajaxurl,
 			data: { type: type, email: email, first_name: first_name, last_name: last_name, dept: dept, action: 'zoom_meta_for_user'},
 			success: function(res) {
 				$('.message_op').html(res);
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

 function isValidEmailAddress(emailAddress) {
 	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
 	return pattern.test(emailAddress);
 };

 //FOr the Password Hashing API
 function toggle_password_api(target){
 	var d = document;
 	var tag = d.getElementById(target);
 	var tag2 = d.getElementById("showhide");

 	if( tag2.innerHTML == 'Show' ){
 		tag.setAttribute('type', 'text');   
 		tag2.innerHTML = 'Hide';
 	} else {
 		tag.setAttribute('type', 'password');   
 		tag2.innerHTML = 'Show';
 	}
 }

 
//FOr the Password Hashing Secret
function toggle_password_secret(target){
	var d = document;
	var tag = d.getElementById(target);
	var tag2 = d.getElementById("showhide1");

	if( tag2.innerHTML == 'Show' ){
		tag.setAttribute('type', 'text');   
		tag2.innerHTML = 'Hide';
	} else {
		tag.setAttribute('type', 'password');   
		tag2.innerHTML = 'Show';
	}
}
