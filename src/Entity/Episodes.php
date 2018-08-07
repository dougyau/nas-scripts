<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EpisodesRepository")
 * @ORM\Table(name="episodes")
 */
class Episodes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @ORM\Column(type="string")
     */
    private $torrent;

    /**
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Series", inversedBy="episodes")
     * @ORM\JoinColumn(name="serie_id", referencedColumnName="id")
     */
    private $serie;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber(string $number)
    {
        $this->number = $number;

        return $this;
    }

    public function getTorrent()
    {
        return $this->torrent;
    }

    public function setTorrent(string $torrent)
    {
        $this->torrent = $torrent;

        return $this;
    }

    public function getProcessed()
    {
        return $this->processed;
    }

    public function setProcessed(bool $processed)
    {
        $this->processed = $processed;

        return $this;
    }

    public function getSerie()
    {
        return $this->serie;
    }

    public function setSerie(\App\Entity\Series $serie)
    {
        $this->serie = $serie;

        return $this;
    }
}