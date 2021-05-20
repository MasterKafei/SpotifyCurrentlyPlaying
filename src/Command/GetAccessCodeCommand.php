<?php

namespace App\Command;

use App\Service\Spotify;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetAccessCodeCommand extends Command
{
    protected static $defaultName = 'app:access_code';

    private Spotify $spotify;

    public function __construct(Spotify $spotify)
    {
        parent::__construct();
        $this->spotify = $spotify;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->spotify->askUserAuthorization();

        return Command::SUCCESS;
    }
}
