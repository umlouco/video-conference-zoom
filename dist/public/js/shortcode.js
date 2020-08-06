(function ($) {
    var vczAPIListUserMeetings = {
        init: function () {
            this.cacheDOM();
            this.defaultActions();
        },
        cacheDOM: function () {
            this.$wrapper = $('.vczapi-user-meeting-list');
            if (this.$wrapper === undefined || this.$wrapper.length < 1) {
                return false;
            }
        },
        defaultActions: function () {
            this.$wrapper.DataTable({
                responsive: true
            });
        }
    };

    var vczAPIMeetingFilter = {
        init: function () {
            this.cacheDOM();
            this.evntHandlers();
        },
        cacheDOM: function () {
            this.$taxonomyOrder = $('.vczapi-taxonomy-ordering');
            this.$orderType = $('.vczapi-ordering');
        },
        evntHandlers: function () {
            this.$taxonomyOrder.on('change', this.taxOrdering.bind(this));
            this.$orderType.on('change', this.upcomingLatest.bind(this));
        },
        taxOrdering: function (e) {
            $(e.currentTarget).closest('form').submit();
        },
        upcomingLatest: function (e) {
            $(e.currentTarget).closest('form').submit();
        }
    };

    $(function () {
        vczAPIMeetingFilter.init();
        vczAPIListUserMeetings.init();
    });

})(jQuery);