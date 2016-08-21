document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  var wrappers = document.querySelectorAll('fieldset.block-grid-field-wrapper');

  if (!wrappers) {
    return;
  }

  function addEvent(el, type, handler) {
    if (el.attachEvent) el.attachEvent('on' + type, handler); else el.addEventListener(type, handler);
  }

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

  [].forEach.call(wrappers, function (wrapper) {
    var selects = wrapper.getElementsByTagName('select');
    var columnCountSelect = selects[0];
    var itemWidthSelect = selects[1];
    var list = wrapper.querySelector('.block-grid--indicator').firstElementChild;

    addEvent(columnCountSelect, 'change', function () {
      updateIndicatorColumns(list, this.value);
    });

    addEvent(itemWidthSelect, 'change', function () {
      updateIndicatorWidth(list, this.value);
    });
  });
});
