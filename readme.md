# oEmbed

A PHP class to make a request to the oembed services of YouTube, Vimeo, SoundCloud, MixCloud and Spotify. Parameters for each can be set in the `config.php` configuration file.

## Usage

````
$oembed = new Iksi\oEmbed();
$oembed->request('https://soundcloud.com/toroymoi/so-many-details-remix');
````

For autoplay add a second boolean parameter and make sure the correct autoplay is set in `config.php`. SoundCloud for instance uses `auto_play`.
