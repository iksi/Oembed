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
    protected $parameters;

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
        // Filter out empty values
        $parameters = array_filter($parameters);

        // Add default format to parameters
        $this->parameters = $parameters + array('format' => $this->format);
    }

    public function get()
    {
        $endpoint = $this->endpoint();

        if ($endpoint === false) {
            return;
        }

        $curlHandle = curl_init();
        
        $curlUrl = $endpoint . '?' . http_build_query($this->parameters());

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
        $host = parse_url($this->parameters('url'), PHP_URL_HOST);
        
        foreach ($this->endpoints as $pattern => $endpoint) {
            if (preg_match($pattern, $host)) {
                // Return the endpoint and possibly add the format
                return sprintf($endpoint, $this->parameters('format'));
            }
        }

        return false;
    }
    
    protected function parameters($key = false)
    {
        if ($key === false) {
            return $this->parameters;
        }

        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }
        
        return false;
    }
}
