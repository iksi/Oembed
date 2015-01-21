<?php

/**
 * oEmbed class
 *
 * @author     Iksi <info@iksi.cc>
 * @copyright  (c) 2014-2015 Iksi
 * @license    MIT
 */

namespace Iksi;

class oEmbed
{
    public function fetch($url)
    {
        $providerUrl = $this->getProviderUrl($url);

        if ($providerUrl === false) {
            return $this->error('something seems wrong with the url');
        }

        $curlHandle = curl_init();

        curl_setopt($curlHandle, CURLOPT_URL, $providerUrl);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $response = curl_exec($curlHandle);

        if (curl_errno($curlHandle)) {
            return $this->error(curl_error($curlHandle));
        }

        curl_close($curlHandle);

        return $response;
    }

    protected function getProviderUrl($url)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        
        if (preg_match('/mixcloud\.com$/', $host)) {
            return 'https://www.mixcloud.com/oembed/?url=' . $url;
        }
        
        if (preg_match('/soundcloud\.com$/', $host)) {
            return 'https://soundcloud.com/oembed.json?url=' . $url;
        }

        if (preg_match('/spotify\.com$/', $host)) {
            return 'https://embed.spotify.com/oembed/?url=' . $url;
        }

        if (preg_match('/vimeo\.com$/', $host)) {
            return 'https://vimeo.com/api/oembed.json?url=' . $url;
        }

        if (preg_match('/youtube\.com$/', $host)) {
            return 'https://www.youtube.com/oembed?url=' . $url;
        }

        return false;
    }

    protected function error($error)
    {
        $response = array(
            'error' => $error
        );

        return json_encode($response);
    }
}
