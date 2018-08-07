<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TorrentQueueCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'torrent:queue';
    private $em;

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        $episodes = $em->getRepository('App:Episodes')
            ->findByProcessed(false);

        foreach ($episodes as $episode) {
            $return_val = 0;
            $output = '';
            exec("/usr/bin/transmission-remote -w '/mnt/disk1/share/anime/{$episode->getSerie()->getName()}' -a '{$episode->getTorrent()}'", $output, $return_val);
            if ($return_val === 0) {
                $episode->setProcessed(true);
                $em->flush();
            } 
        }
    }
}
