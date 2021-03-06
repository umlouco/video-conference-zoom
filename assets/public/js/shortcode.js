"use strict";

(function ($) {
  var vczAPIListUserMeetings = {
    init: function init() {
      this.cacheDOM();
      this.defaultActions();
    },
    cacheDOM: function cacheDOM() {
      this.$wrapper = $('.vczapi-user-meeting-list');

      if (this.$wrapper === undefined || this.$wrapper.length < 1) {
        return false;
      }
    },
    defaultActions: function defaultActions() {
      this.$wrapper.DataTable({
        responsive: true
      });
    }
  };
  var vczAPIMeetingFilter = {
    init: function init() {
      this.cacheDOM();
      this.evntHandlers();
    },
    cacheDOM: function cacheDOM() {
      this.$taxonomyOrder = $('.vczapi-taxonomy-ordering');
      this.$orderType = $('.vczapi-ordering');
    },
    evntHandlers: function evntHandlers() {
      this.$taxonomyOrder.on('change', this.taxOrdering.bind(this));
      this.$orderType.on('change', this.upcomingLatest.bind(this));
    },
    taxOrdering: function taxOrdering(e) {
      $(e.currentTarget).closest('form').submit();
    },
    upcomingLatest: function upcomingLatest(e) {
      $(e.currentTarget).closest('form').submit();
    }
  };
  var vczAPIGenerateModal = {
    init: function init() {
      this.cacheDOM();
      this.evntHandlers();
    },
    cacheDOM: function cacheDOM() {
      this.$modal = $('.vczapi-modal');
      this.$modalContent = $('.vczapi-modal-content');
      this.$triggerModal = $('.vczapi-view-recording');
    },
    evntHandlers: function evntHandlers() {
      this.$triggerModal.on('click', this.openModal.bind(this));
      $(document).on('click', '.vczapi-modal-close', this.closeModal.bind(this));
    },
    closeModal: function closeModal(e) {
      e.preventDefault();
      $(this.$modalContent).remove();
      $(this.$modal).hide();
    },
    openModal: function openModal(e) {
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