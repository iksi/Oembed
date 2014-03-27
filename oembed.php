<?php
    require_once('classes/Oembed.php');
    header('Content-type: application/json');

    $url = array_key_exists('url', $_GET)
        ? $_GET['url']
        : FALSE;

    $oembed = new Oembed;

    echo $oembed->fetch($url);