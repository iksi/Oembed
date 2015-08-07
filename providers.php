<?php

// <format> will be replaced with the requested format
return array(
    array(
        'name'     => 'mixcloud',
        'pattern'  => '/mixcloud\.com$/',
        'endpoint' => 'https://www.mixcloud.com/oembed/'
    ),
    array(
        'name'     => 'soundcloud',
        'pattern'  => '/soundcloud\.com|snd\.sc$/',
        'endpoint' => 'https://soundcloud.com/oembed'
    ),
    array(
        'name'     => 'spotify',
        'pattern'  => '/spotify\.com|spoti\.fi$/',
        'endpoint' => 'https://embed.spotify.com/oembed/'
    ),
    array(
        'name'     => 'vimeo',
        'pattern'  => '/vimeo\.com$/',
        'endpoint' => 'https://vimeo.com/api/oembed.<format>'
    ),
    array(
        'name'     => 'youtube',
        'pattern'  => '/youtube\.com|youtu\.be$/',
        'endpoint' => 'https://www.youtube.com/oembed/'
    )
);
