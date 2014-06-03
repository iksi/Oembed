/**
 * Embed module
 */
var embed = (function () {
    'use strict';
    return function (element) {

        var request = function (url, callback) {
            var req = new XMLHttpRequest();
            req.open('GET', url, true);
            req.onload = function () {
                if (req.status >= 200 && req.status < 400 && callback) {
                    callback(JSON.parse(req.responseText));
                }
            };
            req.send();
        };

        var embed = function (data) {
            if (data.html) {
                element.innerHTML = data.html;
                var iframe = element.querySelector('iframe');
                if (iframe.getAttribute('width') === '100%') {
                    element.style.paddingTop = iframe.height + 'px'; // Audio
                } else {
                    element.style.paddingTop = (100 * iframe.height / iframe.width) + '%'; // Videos
                }
            }
        };

        return {
            init: function () {

                var link = element.querySelector('a'),
                    autoplay = element.getAttribute('data-autoplay');

                if (autoplay === null) {
                    autoplay = false;
                }

                if (link) {
                    request('oembed.php?url=' + encodeURIComponent(link.getAttribute('href')) + '&autoplay=' + autoplay, embed);
                    element.removeChild(link);
                }
            }
        };

    };
}());

var embeds = (function () {
    'use strict';

    return function (selector) {

        return {
            init: function () {
                var elements = document.querySelectorAll(selector);
                [].forEach.call(elements, function (element) {
                    embed(element).init();
                });
            }
        };
    };
}());

(function () {
    'use strict';
    embeds('.embed').init();
}());