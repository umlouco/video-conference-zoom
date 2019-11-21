jQuery(function ($) {

    var video_conferencing_zoom_api_public = {

        init: function () {
            this.cacheVariables();
            this.countDownTimer();
        },

        cacheVariables: function () {
            this.$timer = $('.dpn-zvc-timer');
        },

        countDownTimer: function () {
            if (this.$timer.length > 0) {

                var valueDate = this.$timer.data('date');
                // Set the date we're counting down to
                var countDownDate = new Date(valueDate).getTime();

                // Update the count down every 1 second
                var x = setInterval(function () {

                    // Get today's date and time
                    var now = new Date().getTime();

                    // Find the distance between now and the count down date
                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Output the result in an element with id="demo"
                    var countdown = '<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number">' + days + '</div><div class="dpn-zvc-timer-cell-string">days</div></div>';
                    countdown += '<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number">' + hours + '</div><div class="dpn-zvc-timer-cell-string">hours</div></div>';
                    countdown += '<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number">' + minutes + '</div><div class="dpn-zvc-timer-cell-string">minutes</div></div>';
                    countdown += '<div class="dpn-zvc-timer-cell"><div class="dpn-zvc-timer-cell-number">' + seconds + '</div><div class="dpn-zvc-timer-cell-string">seconds</div></div>';

                    // If the count down is over, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        countdown = "<div class='dpn-zvc-meeting-ended'><h3>Meeting Has Ended</h3></div>";
                    }

                    document.getElementById("dpn-zvc-timer").innerHTML = countdown;
                }, 1000);
            }
        },
    };

    video_conferencing_zoom_api_public.init();
});