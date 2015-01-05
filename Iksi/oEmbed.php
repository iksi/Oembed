<?php

namespace Iksi;

define('DS', DIRECTORY_SEPARATOR);

class oEmbed {

    protected $config;

    public function __construct()
    {
        $this->config = require_once(__DIR__ . DS .'config.php');
    }

    public function request($url, $autoplay = NULL)
    {
        $api_url = $this->build_url($url);

        if ($api_url === FALSE)
        {
            return $this->error('something seems wrong with the url');
        }

        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $api_url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en-US; rv:1.9.2.13; ) Gecko/20101203');

        $response = curl_exec($curl_handle);

        if (curl_errno($curl_handle))
        {
            return $this->error(curl_error($curl_handle));
        }

        curl_close($curl_handle);

        return $this->filter($response, $autoplay);
    }

    protected function filter($response, $autoplay)
    {
        $data = json_decode($response, TRUE);

        $provider = strtolower($data['provider_name']);

        if (array_key_exists($provider, $this->config))
        {
            $parameters = $this->config[$provider];

            if ($autoplay !== NULL && $autoplay_key = current(preg_grep('/^auto_?play$/i', array_keys($parameters))))
            {
                $parameters[$autoplay_key] = $autoplay ? 'true' : 'false';
            }

            preg_match('/src="(?<url>[^"]+)"/', $data['html'], $matches);

            $url = preg_replace('/https?:\/\//', '//', $matches['url'])
                . (parse_url($matches['url'], PHP_URL_QUERY) ? '&' : '?')
                . http_build_query($parameters);

            $data['html'] = preg_replace('/src="([^"]+)"/', 'src="' . $url . '"', $data['html']);
        }

        return json_encode($data);
    }

    protected function build_url($url)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL))
        {
            return FALSE;
        }

        $host = parse_url($url, PHP_URL_HOST);
        
        if (preg_match('/mixcloud\.com$/', $host))
        {
            return 'https://www.mixcloud.com/oembed/?url=' . $url;
        }
        
        if (preg_match('/soundcloud\.com$/', $host))
        {
            return 'https://soundcloud.com/oembed.json?url=' . $url;
        }

        if (preg_match('/spotify\.com$/', $host))
        {
            return 'https://embed.spotify.com/oembed/?url=' . $url;
        }

        if (preg_match('/vimeo\.com$/', $host))
        {
            return 'https://vimeo.com/api/oembed.json?url=' . $url;
        }

        if (preg_match('/youtube\.com$/', $host))
        {
            return 'https://www.youtube.com/oembed?url=' . $url;
        }

        return FALSE;
    }

    protected function error($error)
    {
        return json_encode(array('error' => $error));
    }

}