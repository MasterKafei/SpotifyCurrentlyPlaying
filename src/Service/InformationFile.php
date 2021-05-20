<?php

namespace App\Service;

use App\Entity\Album;
use App\Entity\Music;
use Symfony\Component\Yaml\Yaml;

class InformationFile
{
    private string $informationFilePath;

    private MusicFormatter $musicFormatter;

    public function __construct(MusicFormatter $musicFormatter, $informationFilePath)
    {
        $this->informationFilePath = $informationFilePath;
        $this->musicFormatter = $musicFormatter;
    }

    public function getConfiguration()
    {
        return Yaml::parseFile($this->informationFilePath)['files'];
    }

    public function save(Music $music)
    {
        $configuration = $this->getConfiguration();
        foreach ($configuration as $file) {
            ($file['album_image'] ?? false) ? $this->saveAlbumImage($music->getAlbum(), $file['path']) : $this->saveMusicInformation($music, $file['path'], $file['pattern'], $file['limit'] ?? null);
        }
    }

    public function saveAlbumImage(Album $album, $path)
    {
        file_put_contents($path, file_get_contents($album->getCoverUrl()));
    }

    public function saveMusicInformation(Music $music, $path, $pattern, $limit)
    {
        $content = $this->musicFormatter->format($music, $pattern);
        if ($limit !== null && $limit < strlen($content)) {
            $content = substr($content, 0, $limit);
            $content .= "...";
        }
        file_put_contents($path, $content);
    }
}
