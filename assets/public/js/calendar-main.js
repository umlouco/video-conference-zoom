document.addEventListener('DOMContentLoaded', function () {
  var meeting_list;
  jQuery.ajax({
    method: 'post',
    url: my_ajax_obj.ajax_url,
    data: {
      _ajax_nonce: my_ajax_obj.nonce,
      action: "get_free_meetings"
    },
    success: function (data) {
      meeting_list = data;
    },
    async: false
  });

  var initialTimeZone = 'Europe/Rome';
  var loadingEl = document.getElementById('loading');
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {

    //initialView: 'timeGridWeek',
    locale: 'it',
    timeZone: initialTimeZone,
    headerToolbar: {
      left: '',
      center: 'title',
      right: 'dayGridMonth',
    },
    buttonText: {
      today: 'today',
      month: 'indietro',
      week: 'week',
      day: 'day',
      list: 'list'

    },
    initialDate: new Date("2021-01-01"),
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    selectable: false,
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
      var dateString = info.event.start.toISOString().split("T")[0];
      jQuery('#calendar').find('.fc-daygrid-day[data-date="' + dateString + '"]').addClass('has-event');
    },
    navLinkDayClick: function (date, jsEvent) {
      var events = calendar.getEvents();
      var eventsCount = 0;
      for (var i = 0; i < events.length; i++) {
        if (date.toISOString().split("T")[0] == events[i].start.toISOString().split("T")[0])
          eventsCount++;
      }
      if (eventsCount == 0)
        return false;
      else {
        jQuery('.fc-dayGridMonth-button').off("click");
        jQuery('.fc-dayGridMonth-button').on("click", function () {
          jQuery('.fc-dayGridMonth-button').attr('style', 'display: none !important');
        })
        jQuery('.fc-dayGridMonth-button').attr('style', 'display: block !important');
        calendar.changeView('listDay', date.toISOString().split("T")[0]);
        jQuery('#calendar').find('.fc-list-event-title').html('Prenota!');
      }
    },
   


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
      beforeClose: function () {
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