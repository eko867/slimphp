<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.09.18
 * Time: 12:18
 */

namespace Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой автора альбома
 * @ORM\Entity(repositoryClass="\Gallery\Repository\PhotoRepository")
 * @ORM\Table(name="photo")
 */

class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue //PRIMARY KEY AUTO_INCREMENT
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="title", type="string",length=50)
     */
    protected $title;

    /**
     * @ORM\Column(name="geo", type="string",length=200)
     */
    protected $geo;

    /**
     * @ORM\Column(name="loaded_at", type="datetime")
     */
    protected $loadedAt;

    /**
     * @ORM\Column(name="filepath", type="string",length=200)
     */
    protected $filepath;

    /**
     * Many photos has one album
     * @ORM\ManyToOne(targetEntity="\Gallery\Entity\Album", inversedBy="photos")
     * @ORM\JoinColumn(name="album_id", referencedColumnName="id")
     */
    protected $album;

    public function __construct()
    {
        $this->loadedAt=new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getGeo()
    {
        return $this->geo;
    }

    /**
     * @param mixed $geo
     */
    public function setGeo($geo)
    {
        $this->geo = $geo;
    }

    /**
     * @return mixed
     */
    public function getLoadedAt()
    {
        return $this->loadedAt;
    }

    /**
     * @param mixed $loadedAt
     */
    public function setLoadedAt($loadedAt)
    {
        $this->loadedAt = $loadedAt;
    }

    /**
     * @return mixed
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * @param mixed $filepath
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    }

    /**
     * @return mixed
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param mixed $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
    }
}