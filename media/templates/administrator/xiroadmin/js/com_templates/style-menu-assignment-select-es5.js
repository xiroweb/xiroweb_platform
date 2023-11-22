(function () {
  'use strict';

  /**
   * @copyright  (C) 2016 Open Source Matters, Inc. <https://www.joomla.org>
   * @license    GNU General Public License version 2 or later; see LICENSE.txt
   */
  (function (document) {
    document.addEventListener('DOMContentLoaded', function () {
      var linkedFieldElement = document.getElementById('jform_home');
      linkedFieldElement.addEventListener('change', function () {
        if (linkedFieldElement.value == '0') {
          if (document.getElementById('style-edit-menu-assignment-all')) {
            document.getElementById('style-edit-menu-assignment-all').classList.add('hidden');
          }

          if (document.getElementById('style-edit-menu-assignment-button')) {
            document.getElementById('style-edit-menu-assignment-button').classList.remove('hidden');
          }
        } else {
          if (document.getElementById('style-edit-menu-assignment-all')) {
            document.getElementById('style-edit-menu-assignment-all').classList.remove('hidden');
          }

          if (document.getElementById('style-edit-menu-assignment-button')) {
            document.getElementById('style-edit-menu-assignment-button').classList.add('hidden');
          }
        }
      });
    });
  })(document);

}());
