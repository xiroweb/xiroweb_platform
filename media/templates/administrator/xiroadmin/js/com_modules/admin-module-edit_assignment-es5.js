(function () {
  'use strict';

  /**
   * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
   * @license    GNU General Public License version 2 or later; see LICENSE.txt
   */
  (function () {
    var onChange = function onChange(value) {
      if (value === '-') {
        document.getElementById('menuselect-group').className = "control-group menuselect-input-check nopage";
      } else {
        switch (parseInt(value, 10)) {
          case 0:
            document.getElementById('menuselect-group').className = "control-group menuselect-input-check selectall";
            break;

          case 1:
            document.getElementById('menuselect-group').className = "control-group menuselect-input-check selectitem";
            break;

          case -1:
            document.getElementById('menuselect-group').className = "control-group menuselect-input-check selectiteminvert";
            break;
        }
      }
    };

    var onBoot = function onBoot() {
      var element = document.getElementById('jform_assignment');

      if (element) {
        // Initialise the state
        onChange(element.value); // Check for changes in the state

        element.addEventListener('change', function (_ref) {
          var target = _ref.target;
          onChange(target.value);
        });
      }

      document.removeEventListener('DOMContentLoaded', onBoot);
    };

    document.addEventListener('DOMContentLoaded', onBoot);
  })();

}());
