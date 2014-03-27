var Oembed = (function () {
    'use strict';
    var selector = '.embed',
        baseURI = '';

    var embed = function (element, html) {
        element.innerHTML = html;
        var iframe = element.querySelector('iframe');
        if (iframe.getAttribute('width') === '100%') {
            // Audio
            element.style.paddingTop = iframe.getAttribute('height') + 'px';
        } else {
            // Videos
            element.style.paddingTop = (100 * iframe.getAttribute('height') / iframe.getAttribute('width')) + '%';
        }
    };

    // Request
    var request = function (url, callback) {
        var req = new XMLHttpRequest();
        req.open('GET', url, true);
        req.onload = function () {
            if (req.status >= 200 && req.status < 400 && callback) {
                // Success!
                callback(JSON.parse(req.responseText));
            } else {
                // We reached our target server, but it returned an error
                console.log('response error');
            }
        };
        req.onerror = function () {
            // There was a connection error of some sort
            console.log('connection error');
        };
        req.send();
    };

    return {
        init: function () {
            // Cycle through all embeds
            [].forEach.call(document.querySelectorAll(selector), function (element) {
                var link = element.querySelector('a');
                if (link !== null) {
                    request(baseURI + 'oembed.php?url=' + link.getAttribute('href'), function (data) {
                        embed(element, data.html);
                    });
                    element.removeChild(link);
                }
            });
        }
    };

}());

Oembed.init();