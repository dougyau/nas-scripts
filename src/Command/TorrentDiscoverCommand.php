<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TorrentDiscoverCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'torrent:discover';
    private $em;

    protected function configure()
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $targets = ['https://nyaa.si/?page=rss'];
        $this->em = $this->getContainer()
            ->get('doctrine')
            ->getManager();

        foreach ($targets as $target) {
            $this->parseFeed($target);
        }
    }

    private function parseFeed($url)
    {
        $feed = simplexml_load_file($url);

        foreach ($feed->channel->item as $item) {
            $matches = [];
            if (preg_match('/\[HorribleSubs\](.+)-(.+)\[1080p\]\.mkv/', (string)$item->title, $matches)) {
                $serie = $this->getSerie($matches);

                if ($serie->getDownload() || $serie->getNew())
                    $this->processEpisode($serie, $item, $matches);
            }
        }
    }

    private function getSerie($matches)
    {
        $serie = $this->em
            ->getRepository('App:Series')
            ->findOneByName(trim($matches[1]));

        if (empty($serie)) {
            $serie = new \App\Entity\Series();
            $serie->setName(trim($matches[1]))
                ->setNew(true);

            $this->em
                ->persist($serie);
            $this->em
                ->flush();
        }
        
        return $serie;
    }

    private function processEpisode($serie, $item, $matches)
    {
        $episode = $this->
            em->getRepository('App:Episodes')
            ->findOneByName(trim((string)($item->title)));

        if (empty($episode)) {
            $episode = new \App\Entity\Episodes();
            $episode->setNumber(trim($matches[2]))
                ->setName(trim((string)($item->title)))
                ->setTorrent(trim((string)($item->link)))
                ->setSerie($serie);

            $this->em
                ->persist($episode);
            $this->em
                ->flush();
        }
    }
}
