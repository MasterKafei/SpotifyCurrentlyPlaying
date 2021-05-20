<?php

namespace App\Service;

use App\Entity\Music;

class MusicFormatter
{
    const FORMAT = [
        'ARTIST' => '%artist{n}%',
        'ARTISTS' => '%artists%',
        'TITLE' => '%title%',
        'ALBUM' => '%album%',
    ];

    public function format(Music $music, string $pattern)
    {
        $artists = array_map(function ($artist) {
            return $artist->name;
        }, $music->getArtists());

        $result = $pattern;
        foreach ($artists as $index => $artist) {
            $format = str_replace("{n}", $index + 1, self::FORMAT['ARTIST']);
            $result = str_replace($format, $artist, $result);
        }

        $result = str_replace(self::FORMAT['TITLE'], $music->getName(), $result);
        $result = str_replace(self::FORMAT['ALBUM'], $music->getAlbum()->getName(), $result);

        return str_replace(self::FORMAT['ARTISTS'], implode(' - ', $artists), $result);
    }
}
