<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.09.18
 * Time: 17:25
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Slim\Container;

/** @var Container $container */
$container = require_once __DIR__ . '/bootstrap.php';

ConsoleRunner::run(
    ConsoleRunner::createHelperSet($container[EntityManager::class])
);