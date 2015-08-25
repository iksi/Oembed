# oEmbed

A simple PHP class to make an oEmbed request that I mainly made to overcome the same origin policy for cross domain requests to the original apis. Currently supported services:

- YouTube
- Vimeo
- SoundCloud
- MixCloud
- Spotify

It returns either json or xml depending on the format passed. If no format is specified json will be returned.

## Usage

```PHP
$arguments = array(
    'url'    => 'https://soundcloud.com/toroymoi/so-many-details-remix',
    'format' => 'xml'
);

$oembed   = new Iksi\oEmbed($arguments);
$response = $oembed->fetch();
```
