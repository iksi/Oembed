<?php

    require_once('classes/Oembed.php');
    header('Content-type: application/json');

    $vars = array('url', 'autoplay');
    $params = array();

    foreach ($vars as $var)
    {
        $params[$var] = array_key_exists($var, $_GET) ? $_GET[$var] : FALSE;
    }

    $oembed = new Oembed;

    echo $oembed->fetch($params['url'], $params['autoplay']);