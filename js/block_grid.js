jQuery(function ($) {
  'use strict';

  function updateIndicatorWidth(listWrapper, className) {
    listWrapper.classList = className;
  }

  function updateIndicatorColumns(listWrapper, count) {
    var inner = '';

    for (var i = 0; i < count; i++) {
      inner += '<li><div>&nbsp;</div></li>';
    }

    listWrapper.innerHTML = inner;
  }

  function init() {
    var $wrappers = $('fieldset.block-grid-field-wrapper');

    if (!$wrappers.length) {
      return;
    }

    $wrappers.each(function () {
      var selects = this.getElementsByTagName('select');
      var list = this.querySelector('.block-grid--indicator').firstElementChild;

      $(selects[0]).on('change', function () {
        updateIndicatorColumns(list, this.value);
      });

      $(selects[1]).on('change', function () {
        updateIndicatorWidth(list, this.value);
      });
    });
  }

  init();

  $(document).ajaxComplete(init);
});
