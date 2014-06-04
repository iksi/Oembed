<?php

class Oembed {

    public function fetch($url, $autoplay)
    {
        $oembed_url = $this->_oembed_url($url, $autoplay);

        if ($oembed_url === FALSE)
        {
            return 'not found';
        }

        $ch = curl_init($oembed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $data = curl_exec($ch);
        curl_close($ch);

        return $this->_filter($data, $autoplay);
    }

    protected function _filter($data, $autoplay)
    {
        $values = json_decode($data, TRUE);

        // YouTube
        if ($values['provider_name'] === 'YouTube')
        {
            $params = array(
                'rel' => FALSE,
                'showinfo' => FALSE,
                'autohide' => TRUE,
                'autoplay' => ($autoplay === 'true')
            );

            $query = http_build_query($params);

            $values['html'] = str_replace('?feature=oembed', '?feature=oembed&'.$query, $values['html']);
        }

        // Vimeo, SoundCloud, Mixcloud

        return json_encode($values);
    }

    protected function _oembed_url($url, $autoplay)
    {
        if ( ! filter_var($url, FILTER_VALIDATE_URL))
        {
            return FALSE;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (preg_match('/youtube\.com$/', $host))
        {
            return 'https://www.youtube.com/oembed?url='.$url.'&autoplay='.$autoplay;
        }
        elseif (preg_match('/mixcloud\.com$/', $host))
        {
            return 'https://www.mixcloud.com/oembed/?url='.$url.'&autoplay='.$autoplay;
        }
        elseif (preg_match('/soundcloud\.com$/', $host))
        {
            return 'https://soundcloud.com/oembed.json?url='.$url.'&auto_play='.$autoplay;
        }
        elseif (preg_match('/vimeo\.com$/', $host))
        {
            return 'https://vimeo.com/api/oembed.json?url='.$url.'&autoplay='.$autoplay;
        }

        return FALSE;
    }

}