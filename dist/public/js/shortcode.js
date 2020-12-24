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
        },
    };

    var vczAPIGenerateModal = {
        init: function () {
            this.cacheDOM();
            this.evntHandlers();
        },
        cacheDOM: function () {
            this.$modal = $('.vczapi-modal');
            this.$modalContent = $('.vczapi-modal-content');
            this.$triggerModal = $('.vczapi-view-recording');
        },
        evntHandlers: function () {
            this.$triggerModal.on('click', this.openModal.bind(this));
            $(document).on('click', '.vczapi-modal-close', this.closeModal.bind(this));
        },
        closeModal: function (e) {
            e.preventDefault();
            $(this.$modalContent).remove();
            $(this.$modal).hide();
        },
        openModal: function (e) {
            e.preventDefault();
            var that = this;
            var recording_id = $(e.currentTarget).data('recording-id');
            var postData = {
                recording_id: recording_id,
                action: 'get_recording',
                downlable: vczapi_ajax.downloadable
            };

            $(that.$modal).html('<p class="vczapi-modal-loader">' + vczapi_ajax.loading + '</p>').show();
            $.get(vczapi_ajax.ajaxurl, postData).done(function (response) {
                $(that.$modal).html(response.data).show();
            });
        }
    };

    $(function () {
        vczAPIMeetingFilter.init();
        vczAPIListUserMeetings.init();
        vczAPIGenerateModal.init();
    });

})(jQuery);