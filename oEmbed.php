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
    public function get($url)
    {
        $endPoint = $this->endPoint($url);

        if ($endPoint === false) {
            return $this->error('no valid endpoint found');
        }

        $curlHandle = curl_init();
        
        $curlUrl = $endPoint . '?url=' . urlencode($url);

        curl_setopt($curlHandle, CURLOPT_URL, $curlUrl);
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
    
    protected function error($error)
    {
        return json_encode(compact('error'));
    }

    protected function getProviderUrl($url)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        
        if (preg_match('/mixcloud\.com$/', $host)) {
            return 'https://www.mixcloud.com/oembed/';
        }
        
        if (preg_match('/(soundcloud\.com|snd\.sc)$/', $host)) {
            return 'https://soundcloud.com/oembed.json';
        }

        if (preg_match('/spotify\.com$/', $host)) {
            return 'https://embed.spotify.com/oembed/';
        }

        if (preg_match('/vimeo\.com$/', $host)) {
            return 'https://vimeo.com/api/oembed.json';
        }

        if (preg_match('/(youtube\.com|youtu\.be)$/', $host)) {
            return 'https://www.youtube.com/oembed/';
        }

        return false;
    }
}
