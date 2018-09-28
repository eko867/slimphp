<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.09.18
 * Time: 9:05
 */

namespace Gallery\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Gallery\Entity\Photo;


class PhotoRepository extends EntityRepository
{
    public function findPhotosByAlbumId($idAlbum):Query
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder
            ->select('p')
            ->from(Photo::class,'p')
            ->where('p.album=:idAlbum')
            ->setParameter(':idAlbum', $idAlbum);

        return $queryBuilder->getQuery();
    }
}