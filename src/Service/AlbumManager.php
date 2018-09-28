<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.09.18
 * Time: 9:00
 */
namespace Gallery\Service;
use Gallery\Entity\Album;
use Gallery\Entity\Author;
use Gallery\Entity\Photo;

class AlbumManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Директория для загрузки фотографий
     */
    private $uploadDir='./data/upload/';

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAlbum($data)
    {
        /*
         * Дано
         * input - данные из формы
         * Надо
         * Создать автора и альбом
         * Заполнить сушности данными input
         * Указать в альбоме автора
         * Сохраниться
         */
        //создаем новый альбом по данным формы и добавляем его в БД
        $author=new Author();
        $album=new Album();

        $author->setName($data['authorName']);
        $author->setEmail($data['email']);
        $author->setPhone($data['phone']);


        $album->setLabel($data['label']);
        $album->setNote($data['note']);
        $album->setAuthor($author);
        $album->setLastModifiedAt($album->getCreatedAt());

        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($author);
        $this->entityManager->persist($album);

        // Применяем изменения к БД.
        $this->entityManager->flush();
    }

    public function editAlbum($album,$data)
    {
        $album->setLabel($data['label']);
        $album->setNote($data['note']);
        $album->setLastModifiedAt(new \DateTime());
        $author=$album->getAuthor();
        $author->setName($data['authorName']);
        $author->setPhone($data['phone']);
        $author->setEmail($data['email']);

        // Применяем изменения к БД //persist не нужен, т.к. сущность уже существующая
        $this->entityManager->flush();
    }

    public function deleteAlbum($album)
    {
        //удаляем альбом и связанные с ним фотографии
        $photos=$album->getPhotos();
        foreach ($photos as $photo) {
            $this->entityManager->remove($photo);
        }
        $this->entityManager->remove($album);
        $this->entityManager->flush();
    }

    public function addPhoto($album,$data)
    {
        $photo=new Photo();
        $photo->setTitle($data['title']);
        $photo->setGeo($data['geo']);
        $photo->setFilepath('srccc');//?
        $photo->setAlbum($album);
        $filepath=ltrim($data['file']['tmp_name'], '.'); //удалим первую точку
        $photo->setFilepath($filepath);

        $album->setPhoto($photo);
        $album->setLastModifiedAt(new \DateTime());

        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($photo);

        // Применяем изменения к БД.
        $this->entityManager->flush();

    }

    public function editPhoto($photo,$data)
    {
        $photo->setTitle($data['title']);
        $photo->setGeo($data['geo']);

        // Применяем изменения к БД //persist не нужен, т.к. сущность уже существующая
        $this->entityManager->flush();
    }

    public function deletePhoto($photo)
    {
        //удаляем фото из альбома
        $album=$photo->getAlbum();
        $album->unsetPhoto($photo);
        //и удаляем само фото
        $this->entityManager->remove($photo);
        $this->entityManager->flush();
    }
}