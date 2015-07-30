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
    // Default format
    protected $format = 'json';
    protected $arguments;

    // Provider endpoints (%s is for the format that gets added)
    protected $endpoints = array(
        '/mixcloud\.com$/'           => 'https://www.mixcloud.com/oembed/',
        '/soundcloud\.com|snd\.sc$/' => 'https://soundcloud.com/oembed',
        '/spotify\.com|spoti\.fi$/'  => 'https://embed.spotify.com/oembed/',
        '/vimeo\.com$/'              => 'https://vimeo.com/api/oembed.%s',
        '/youtube\.com|youtu\.be$/'  => 'https://www.youtube.com/oembed/'
    );
    
    public function __construct($parameters = array())
    {
        // Default format
        $arguments = $arguments + array('format' => $this->format);
        
        // Filter out empty values
        $this->arguments = array_filter($arguments, function($value) {
            return strlen($value);
        });
    }

    public function get()
    {
        $endpoint = $this->endpoint();

        if ($endpoint === false) {
            return;
        }

        $curlHandle = curl_init();
        
        $curlUrl = $endpoint . '?' . http_build_query($this->arguments());

        curl_setopt($curlHandle, CURLOPT_URL, $curlUrl);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curlHandle, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

        $curlResponse = curl_exec($curlHandle);
        $curlError = curl_errno($curlHandle) ? curl_error($curlHandle) : false;

        curl_close($curlHandle);
        
        if ($curlError !== false) {
            return $curlError;
        }

        return $curlResponse;
    }

    protected function endpoint()
    {
        // Get the correct provider based on the url
        $host = parse_url($this->arguments('url'), PHP_URL_HOST);
        
        foreach ($this->endpoints as $pattern => $endpoint) {
            if (preg_match($pattern, $host)) {
                // Return the endpoint and possibly add the format
                return sprintf($endpoint, $this->arguments('format'));
            }
        }

        return false;
    }
    
    protected function arguments($key = false)
    {
        if ($key === false) {
            return $this->arguments;
        }

        if (array_key_exists($key, $this->arguments)) {
            return $this->arguments[$key];
        }
        
        return false;
    }
}
