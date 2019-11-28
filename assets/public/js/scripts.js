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
            var eventTime = moment(valueDate).unix();
            var currentTime = moment().unix();
            var diffTime = eventTime - currentTime;
            var duration = moment.duration(diffTime * 1000, 'milliseconds');
            var interval = 1000;

            // if time to countdown
            if (diffTime > 0) {

                var $d = $('<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number"><div class="days" ></div></div><div class="dpn-zvc-timer-cell-string">days</div></div>').appendTo(clock),
                    $h = $('<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number"><div class="hours" ></div></div><div class="dpn-zvc-timer-cell-string">hours</div></div>').appendTo(clock),
                    $m = $('<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number"><div class="minutes" ></div></div><div class="dpn-zvc-timer-cell-string">minutes</div></div>').appendTo(clock),
                    $s = $('<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number"><div class="seconds" ></div></div><div class="dpn-zvc-timer-cell-string">seconds</div></div>').appendTo(clock);

                setInterval(function () {

                    duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
                    if (duration.asMilliseconds() > 0) {
                        var d = moment.duration(duration).days(),
                            h = moment.duration(duration).hours(),
                            m = moment.duration(duration).minutes(),
                            s = moment.duration(duration).seconds();

                        d = $.trim(d).length === 1 ? '0' + d : d;
                        h = $.trim(h).length === 1 ? '0' + h : h;
                        m = $.trim(m).length === 1 ? '0' + m : m;
                        s = $.trim(s).length === 1 ? '0' + s : s;

                        // show how many hours, minutes and seconds are left
                        $d.html(d + '<div class="dpn-zvc-timer-cell-string">days</div>');
                        $h.html(h + '<div class="dpn-zvc-timer-cell-string">hours</div>');
                        $m.html(m + '<div class="dpn-zvc-timer-cell-string">minutes</div>');
                        $s.html(s + '<div class="dpn-zvc-timer-cell-string">seconds</div>');
                    } else {
                        $(clock).html("<div class='dpn-zvc-meeting-ended'><h3>Meeting is Starting..</h3></div>");
                        clearInterval(interval);
                    }

                }, interval);

            } else {
                clearInterval(interval);
                $(clock).html("<div class='dpn-zvc-meeting-ended'><h3>Meeting Has Started/Ended</h3></div>");
            }
        },
    };

    video_conferencing_zoom_api_public.init();
});