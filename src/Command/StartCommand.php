<?php

namespace App\Command;

use App\Entity\Music;
use App\Service\InformationFile;
use App\Service\MusicFormatter;
use App\Service\Spotify;
use Psr\Log\LoggerInterface;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\SpotifyWebAPIException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartCommand extends Command
{
    protected static $defaultName = 'app:start';

    protected Spotify $spotify;

    protected InformationFile $informationFile;

    protected LoggerInterface $logger;

    public function __construct(Spotify $spotify, InformationFile $informationFile, LoggerInterface $logger)
    {
        parent::__construct();
        $this->spotify = $spotify;
        $this->informationFile = $informationFile;
        $this->logger = $logger;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Music $currentMusic */
        $currentMusic = null;

        while (true) {
            try {
                $this->spotify->refreshToken();
                $music = $this->spotify->getCurrentMusic();
                if (null === $currentMusic || $music->getName() !== $currentMusic->getName()) {
                    $currentMusic = $music;
                    $output->writeln("Currently playing : {$music->getName()}");
                    $this->informationFile->save($music);
                }
            } catch (SpotifyWebAPIException $exception) {
            } catch (\ErrorException $exception) {
            } catch (\Exception $exception) {
                $this->logger->alert(get_class($exception) . " : {$exception->getMessage()}");
            }
        }
    }
}
