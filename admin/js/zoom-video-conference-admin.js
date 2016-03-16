/**
 * Script files for Extra Functionality @techies dpen
 *
 * @since  1.0.0
 */

jQuery(document).ready(function($) {
	$('#vibe_zoom_video').attr('disabled', 'disabled');

	//AJAX CALL FOR ADDING THE COURSE LINK VALUE IN ZOOM COURSE PAGE
	$('#add_linkto_course').click(function(d) {
		d.preventDefault();
		var post_id_course = $('.zoom_api_courses').val();
		var userId = $('#userId').val();
		var meetingId = $('#meetingId').val();
		var title = $('.zoom_api_courses option:selected').data('title');

		$('.linked_result_diplay').append('<p class="wait">Please Wait... Loading....</p>');
		$.post(ajaxurl + '?action=addCourse_metaPost', {post_id_course : post_id_course, userId : userId, meetingId : meetingId}).done(function(result) {
			if(result == 1) {
				var page_html = '';
				page_html += '<p id="title_'+post_id_course+'">'+title+' <a href="#" onclick="delete_linked_course('+post_id_course+');">Delete</a></p>';
				$('.wait').remove();
				$('.linked_result_diplay').append(page_html);
			} else {
				$('.wait').remove();
				alert('The selected course is already in this Meeting or Another Meeting !!');
			}
			
		});
	});

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
	})
	
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