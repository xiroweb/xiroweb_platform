/**
 * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
(() => {

  document.addEventListener('DOMContentLoaded', () => {
    
    // Get all links on the site
    const links = document.querySelectorAll('a');

    const currentUrl = window.location.href;
    const urlParams = new URLSearchParams(currentUrl);
    const functionquery = urlParams.get('function');

    links.forEach((link, i) => {

        // Get the href attribute of the link
        let href = links[i].getAttribute('href');
        // Check if link is an absolute URL
        if (!href.includes('http') && !href.includes('www') && !href.includes('javascript:void') ) {
    
            if (href.includes('?')) {
                href += '&';
            } else {
                href += '?';
            }
            // Append query string to href
            href += 'tp=1&positionmodal=1';

            if (functionquery !== null) {
                href += '&function=' + functionquery;
              }
            
            // Update the href attribute of the anchor element
            links[i].setAttribute('href', href);
        } 
        // stop link 
        if ((href.includes('http') || href.includes('www')) && !href.includes('javascript:void') ) {
            links[i].classList.add('disable-link-pointer-events');
          }

      });

  });
})();
