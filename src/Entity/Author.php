<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.09.18
 * Time: 12:17
 */

namespace Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой автора альбома
 * @ORM\Entity
 * @ORM\Table(name="author")
 */

class Author
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string",length=50)
     */
    protected $name;

    /**
     * @ORM\Column(name="email", type="string",length=40)
     */
    protected $email;

    /**
     * @ORM\Column(name="phone", type="string",length=18)
     */
    protected $phone;

    /**
     * One author has Many albums
     * @ORM\OneToMany(targetEntity="\Gallery\Entity\Album", mappedBy="author")
     */
    protected $albums;


    public function __construct()
    {
        $this->album=new Album();
        $this->albums=new ArrayCollection();
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAlbums()
    {
        return $this->albums;
    }

    /**
     * @param mixed $albums
     */
    public function setAlbum($album)
    {
        $this->albums[] = $album;
    }
}