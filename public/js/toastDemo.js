(function($) {
  showSuccessToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Success',
      text: msg,
      showHideTransition: 'slide',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'top-right'
    })
  };
  showInfoToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Info',
      text: msg,
      showHideTransition: 'slide',
      icon: 'info',
      loaderBg: '#46c35f',
      position: 'top-right'
    })
  };
  showWarningToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: msg,
      showHideTransition: 'slide',
      icon: 'warning',
      loaderBg: '#57c7d4',
      position: 'top-right'
    })
  };
  showDangerToast = function(msg) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Danger',
      text: msg,
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };
  resetToastPosition = function() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center');
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    });
  }
})(jQuery);