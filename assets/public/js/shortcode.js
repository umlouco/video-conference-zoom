"use strict";

(function ($) {
  var vczAPIListUserMeetings = {
    init: function init($) {
      this.cacheDOM();

      if (this.$wrapper === undefined || this.$wrapper.length < 1) {
        return false;
      }

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
  $(function () {
    vczAPIListUserMeetings.init();
  });
})(jQuery);