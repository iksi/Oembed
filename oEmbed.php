<?php

/**
 * oEmbed
 *
 * @author     Iksi <info@iksi.cc>
 * @copyright  (c) 2014-2015 Iksi
 * @license    MIT
 */

namespace Iksi;

class oEmbed
{
    protected $arguments = array();
    protected $providers = array();
    protected $provider = array();
    protected $endpoint = null;

    public function __construct($arguments = array())
    {
        $this->arguments = $arguments;

        // Set default format
        if ($this->getArgument('format') === null) {
            $this->setArgument('format', 'json');
        }

        // Providers
        if (is_readable(__DIR__ . DIRECTORY_SEPARATOR . 'providers.php')) {
            $this->providers = include(__DIR__ . DIRECTORY_SEPARATOR . 'providers.php');
        }

        $this->setProvider($this->getArgument('url'));
        $this->setEndpoint($this->getArgument('format'));
    }

    public function fetch()
    {
        if ($this->endpoint === null) {
            return 'No endpoint found';
        }

        $url = $this->endpoint . '?' . http_build_query($this->arguments);

        $handle = curl_init();

        curl_setopt_array($handle, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_USERAGENT      => $_SERVER['HTTP_USER_AGENT'],
            CURLOPT_ENCODING       => 'utf-8'
        ));

        $response = curl_exec($handle);
        $error = curl_error($handle);

        curl_close($handle);
        
        if (empty($error) === false) {
            return $error;
        }

        return $response;
    }

    protected function setEndpoint($format)
    {
        if (array_key_exists('endpoint', $this->provider)) {
            // Swap out <format> for the requested format
            $this->endpoint = str_replace('<format>', $format, $this->provider['endpoint']);
        }
    }

    public function getProvider($key)
    {
        if (array_key_exists($key, $this->provider)) {
            return $this->provider[$key];
        }

        return null;
    }

    protected function setProvider($url)
    {
        $host = parse_url($url, PHP_URL_HOST);

        // Set the provider based on the pattern match
        foreach ($this->providers as $provider) {
            if (array_key_exists('pattern', $provider) && preg_match($provider['pattern'], $host)) {
                $this->provider = $provider;
            }
        }
    }

    public function getArgument($key)
    {
        if (array_key_exists($key, $this->arguments)) {
            return $this->arguments[$key];
        }

        return null;
    }

    protected function setArgument($key, $value)
    {
        $this->arguments[$key] = $value;
    }
}
