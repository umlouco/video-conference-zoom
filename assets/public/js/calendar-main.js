document.addEventListener('DOMContentLoaded', function () {
  var meeting_list;
  jQuery.ajax({
    method: 'post',
    url: my_ajax_obj.ajax_url,
    data: { _ajax_nonce: my_ajax_obj.nonce, action: "get_free_meetings" },
    success: function (data) {
      meeting_list = data;
    },
    async: false
  });

  var initialTimeZone = 'Europe/Rome';
  var loadingEl = document.getElementById('loading');
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
   
    initialView: 'timeGridWeek',
    timeZone: initialTimeZone,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'timeGridWeek,timeGridDay,listWeek'
    },
    initialDate: new Date(),
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    selectable: true,
    dayMaxEvents: true, // allow "more" link when too many events
    events: meeting_list,
    loading: function (bool) {
      if (bool) {
        loadingEl.style.display = 'inline'; // show
      } else {
        loadingEl.style.display = 'none'; // hide
      }
    },
    eventClick: function (info) {
      addUserToMeeting(info.el.id);
    },
    eventDidMount: function (info) {
      jQuery(info.el).attr("id", info.event.id);
    }

  });

  calendar.render();
});

function addUserToMeeting(meetingId) {
  jQuery.fancybox.open({
    src: '#calendar-light-box',
    type: 'inline',
    autoSize: false,
    opts: {
      afterShow: function (instance, current) {
        jQuery.ajax({
          method: 'post',
          url: my_ajax_obj.ajax_url,
          data: {
            _ajax_nonce: my_ajax_obj.nonce,
            action: "reserve_meeting",
            meetingId: meetingId
          },
          success: function (data) {
            jQuery("#calendar-light-box").html(data);
          },
          async: false
        });
      }, 
      beforeClose: function(){
        window.location.reload(true); 
      }
    }
  });
}

jQuery(document).ready(function ($) {
  $('body').delegate("#calendar-login", 'click', function (e) {
    e.preventDefault();
    var username = $('input[name="calendar_username"]').val();
    var password = $('input[name="calendar_password"]').val();
    var meetingId = $('input[name="meetingId"]').val(); 
    if (username.length > 0 && password.length > 0) {
      $("#calendar-light-box").html('<img src="' + my_ajax_obj.loader + '">'); 
      jQuery.ajax({
        method: 'post',
        url: my_ajax_obj.ajax_url,
        data: {
          _ajax_nonce: my_ajax_obj.nonce,
          action: "calendarLoginUser",
          meetingId: meetingId, 
          username: username,
          password: password
        },
        success: function (data) {
          jQuery("#calendar-light-box").html(data);
        },
        async: false
      });
    }
  });


  
});