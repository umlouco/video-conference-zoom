jQuery(function ($) {

    var video_conferencing_zoom_api_public = {

        init: function () {
            this.cacheVariables();
            this.countDownTimerMoment();
        },

        cacheVariables: function () {
            this.$timer = $('#dpn-zvc-timer');
        },

        countDownTimerMoment: function () {
            var clock = this.$timer;
            var valueDate = clock.data('date');
            var mtgTimezone = clock.data('tz');

            // var dateFormat = moment(valueDate).format('MMM D, YYYY HH:mm:ss');

            //Converting Timezones to locals
            var source_timezone = moment.tz(valueDate, mtgTimezone).format();
            var converted_timezone = moment.tz(source_timezone, moment.tz.guess()).format('MMM D, YYYY HH:mm:ss');
            var convertedTimezonewithoutFormat = moment.tz(source_timezone, moment.tz.guess()).format();

            //Check Time Difference for Validations
            var currentTime = moment().unix();
            var eventTime = moment(convertedTimezonewithoutFormat).unix();
            var diffTime = eventTime - currentTime;

            var second = 1000,
                minute = second * 60,
                hour = minute * 60,
                day = hour * 24;

            // if time to countdown
            if (diffTime > 0) {
                var countDown = new Date(converted_timezone).getTime();
                var x = setInterval(function () {
                    var now = new Date().getTime();
                    var distance = countDown - now;

                    document.getElementById('dpn-zvc-timer-days').innerText = Math.floor(distance / (day));
                    document.getElementById('dpn-zvc-timer-hours').innerText = Math.floor((distance % (day)) / (hour));
                    document.getElementById('dpn-zvc-timer-minutes').innerText = Math.floor((distance % (hour)) / (minute));
                    document.getElementById('dpn-zvc-timer-seconds').innerText = Math.floor((distance % (minute)) / second);

                    if (distance < 0) {
                        clearInterval(x);
                        $(clock).html("<div class='dpn-zvc-meeting-ended'><h3>" + zvc_strings.meeting_starting + "</h3></div>");
                    }
                }, second);
            } else {
                $(clock).html("<div class='dpn-zvc-meeting-ended'><h3>" + zvc_strings.meeting_ended + "</h3></div>");
            }
        },
    };

    video_conferencing_zoom_api_public.init();
});