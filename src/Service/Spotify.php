<?php

namespace App\Service;


use App\Entity\Album;
use App\Entity\Music;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Spotify
{
    const CACHE_ACCESS_TOKEN_KEY = 'CACHE_ACCESS_TOKEN';

    private Session $session;

    private SpotifyWebAPI $api;

    private FilesystemAdapter $cache;

    public function __construct(Session $session, SpotifyWebAPI $api)
    {
        $this->cache = new FilesystemAdapter();
        $this->session = $session;
        $this->api = $api;
        $this->loadAccessCodeFromCache();
    }

    public function loadAccessCodeFromCache()
    {
        $code = $this->cache->getItem(self::CACHE_ACCESS_TOKEN_KEY);
        if (null !== $code) {
            $this->session->setRefreshToken($code->get());
        }
    }

    public function setAccessCode(string $code)
    {
        $this->session->requestAccessToken($code);
        $this->session->refreshAccessToken();
        $accessToken = $this->cache->getItem(self::CACHE_ACCESS_TOKEN_KEY);
        $accessToken->set($this->session->getRefreshToken());
        $this->cache->save($accessToken);
    }

    public function getCurrentMusic(): Music
    {
        $currentTrack = $this->api->getMyCurrentTrack();

        $image = $this->getBestQualityImage($currentTrack->item->album->images);

        $music = new Music();
        $music
            ->setArtists($currentTrack->item->artists)
            ->setName($currentTrack->item->name)
            ->setAlbum(
                (new Album())
                    ->setName($currentTrack->item->album->name)
                    ->setCoverUrl($image->url)
            );


        return $music;
    }

    public function refreshToken()
    {
        $this->session->refreshAccessToken();
        $this->api->setAccessToken($this->session->getAccessToken());
    }

    public function askUserAuthorization()
    {
        $url = $this->session->getAuthorizeUrl([
            'scope' => [
                'user-read-email',
                'user-read-currently-playing',
            ],
        ]);

        exec("start \"\" \"$url\"");
    }

    protected function getBestQualityImage(iterable $images)
    {
        $maxImage = new \stdClass();
        $maxImage->height = 0;
        foreach ($images as $image) {
            if ($maxImage->height < $image->height) {
                $maxImage = $image;
            }
        }

        return $maxImage;
    }
}
