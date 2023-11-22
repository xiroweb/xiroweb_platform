(function () {
  'use strict';

  /**
   * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
   * @license    GNU General Public License version 2 or later; see LICENSE.txt
   */
  (function () {
    document.addEventListener('DOMContentLoaded', function () {
      // Get the elements
      var elements = document.querySelectorAll('.select-link');

      for (var i = 0, l = elements.length; l > i; i += 1) {
        // Listen for click event
        elements[i].addEventListener('click', function (event) {
          event.preventDefault();
          var target = event.target;
          var functionName = target.getAttribute('data-function');

          if (functionName === 'jSelectModule') {
            // Used in xtd_contacts
            window[functionName](target.getAttribute('data-id'), target.getAttribute('data-title'), target.getAttribute('data-cat-id'), null, target.getAttribute('data-uri'), target.getAttribute('data-language'));
          } else {
            // Used in com_menus
            window.parent[functionName](target.getAttribute('data-id'), target.getAttribute('data-title'), target.getAttribute('data-cat-id'), null, target.getAttribute('data-uri'), target.getAttribute('data-language'));
          }

          if (window.parent.Joomla.Modal) {
            window.parent.Joomla.Modal.getCurrent().close();
          }
        });
      }
    });
  })();

}());
