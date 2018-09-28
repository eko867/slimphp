<?php
// DIC configuration

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Gallery\Service\AlbumManager;


$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//из bootstrap.php
$container['entityManager'] = function ($container) {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

$container['albumManager'] = function ($container) {
    return new Gallery\Service\AlbumManager($container['entityManager']);
};

$container['photoRepository'] = function ($container) {
    return new Gallery\Repository\PhotoRepository($container['entityManager'],Gallery\Entity\Photo::class);
};

$container['albumController'] = function($container) {
    $albumManager = $container['albumManager'];
    $entityManager = $container['entityManager'];
    $renderer=$container['renderer'];
    return new Gallery\Controller\AlbumController($entityManager, $albumManager,$renderer);
};