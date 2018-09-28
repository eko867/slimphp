<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.09.18
 * Time: 12:18
 */

namespace Gallery\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Этот класс представляет собой автора альбома
 * @ORM\Entity
 * @ORM\Table(name="album")
 */

class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(name="label", type="string", length=50)
     */
    protected $label;

    /**
     * @ORM\Column(name="note", type="string", length=200)
     */
    protected $note;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="last_modified_at", type="datetime")
     */
    protected $lastModifiedAt;

    /**
     * Many albums has one author
     * @ORM\ManyToOne(targetEntity="\Gallery\Entity\Author", inversedBy="albums")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * One album has many photos
     * @ORM\OneToMany(targetEntity="\Gallery\Entity\Photo", mappedBy="album")
     */
    protected $photos;


    public function __construct()
    {
        $this->createdAt=new \DateTime();
        $this->photos=new ArrayCollection();
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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getLastModifiedAt()
    {
        return $this->lastModifiedAt;
    }

    /**
     * @param mixed $lastModifiedAt
     */
    public function setLastModifiedAt($lastModifiedAt)
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * @param mixed $photos
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    public function setPhoto($photo)
    {
        $this->photos[]=$photo;
    }

    public function unsetPhoto($photo)
    {
        $this->photos->removeElement($photo);
    }


}