# oEmbed

A PHP class to make an oEmbed request. If no format is specified json is returned. Supported services:

- YouTube
- Vimeo
- SoundCloud
- MixCloud
- Spotify

## Usage

```PHP
$arguments = array(
    'url' => 'https://soundcloud.com/toroymoi/so-many-details-remix',
    'format' => 'xml'
);

$oembed = new Iksi\oEmbed($arguments);
$response = $oembed->fetch();
```
