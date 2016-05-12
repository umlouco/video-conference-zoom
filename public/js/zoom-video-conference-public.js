jQuery(document).ready(function($) {
	var course_id;
	$('#publish_course').hide();
	$('#close_btn').hide();
		$('#create_zoom_video').on('click', function() {
			$("#course_title").val($("#course-title").html());
			$("#start_date").val($("#vibe_start_date").val());
			$("#meeting_duration").val($("#vibe_duration").val());
			course_id = $('#course_id').val();
		});

  $('.save_zoom_video').on('click', function() {
  	var course_title = $('#course_title').val();
    var start_date = $('#start_date').val();
    var start_time = $('#start_time').val();
    var timezone = $('#timezone').val();
    var meeting_duration = $('#meeting_duration').val();
    var join_before_host = $('#join_before_host').val();
    var participant_video = $('#participant_video').val();
    var save_zoom_video = $('.save_zoom_video').val();
    var userId = $('#userId').val();
    if(course_title == '' || start_date == '' || start_time == ''  || meeting_duration == '' ) {
    	alert("Required fields are Empty !!");
    } else {
	    $.ajax({
	      type: 'POST',
	      url: ajaxurl,
	      data: { course_id: course_id, course_title: course_title, start_time: start_time, start_date: start_date, timezone: timezone, meeting_duration: meeting_duration, join_before_host: join_before_host, participant_video: participant_video, save_zoom_video: save_zoom_video, userId: userId, action: 'zoom_add_video_frontend'  },
	      success:function(result) {
	        var json_string = $.parseJSON(result);
	        if(json_string.type == 'error') {
	        	$('.notification').html('<div class="alert alert-error" role="alert"><p>'+json_string.msg+'</p></div>');
	        } else {
	        	$('.notification').html('<div class="alert alert-sucess" role="alert"><p>Succesfully created Meeting.</p></div>');
	        	$('#create_zoom_video').hide();
	        	$('#publish_course').show();
	        	$('#close_btn').show();
	        }

	      },
	    });
	  }
    return false;
  });
});
