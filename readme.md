# oEmbed

A PHP class to make a request to the oEmbed services of YouTube, Vimeo, SoundCloud, MixCloud and Spotify. When no format is specified json is returned.

## Usage

```PHP
$oembed = new Iksi\oEmbed;
$oembed->get(array(
    'url' => 'https://soundcloud.com/toroymoi/so-many-details-remix',
    'format' => 'xml'
));
```
