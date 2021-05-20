<?php

namespace App\Entity;

class Music
{
    private string $name;

    private Album $album;

    private array $artists;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAlbum(): ?Album
    {
        return $this->album;
    }

    public function setAlbum(Album $album): self
    {
        $this->album = $album;
        return $this;
    }

    public function getArtists(): ?array
    {
        return $this->artists;
    }

    public function setArtists(array $artists): self
    {
        $this->artists = $artists;
        return $this;
    }
}
