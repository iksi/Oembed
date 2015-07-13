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
    protected $endPoints = array(
        '/mixcloud\.com$/'             => 'https://www.mixcloud.com/oembed/',
        '/(soundcloud\.com|snd\.sc)$/' => 'https://soundcloud.com/oembed.json',
        '/spotify\.com$/'              => 'https://embed.spotify.com/oembed/',
        '/vimeo\.com$/'                => 'https://vimeo.com/api/oembed.json',
        '/(youtube\.com|youtu\.be)$/'  => 'https://www.youtube.com/oembed/'
    );

    public function get($url)
    {
        $endPoint = $this->endPoint($url);

        if ($endPoint === false) {
            return json_encode(
                array('error' => 'no valid endpoint found')
            );
        }

        $curlHandle = curl_init();
        
        $curlUrl = $endPoint . '?url=' . urlencode($url);

        curl_setopt($curlHandle, CURLOPT_URL, $curlUrl);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $response = curl_exec($curlHandle);

        if (curl_errno($curlHandle)) {
            $response = json_encode(
                array('error' => curl_error($curlHandle))
            );
        }

        curl_close($curlHandle);

        return $response;
    }

    protected function endPoint($url)
    {
        $host = parse_url($url, PHP_URL_HOST);
        
        foreach ($this->endPoints as $pattern => $endPoint) {
            if (preg_match($pattern, $host)) {
                return $endPoint;
            }
        }

        return false;
    }
}
