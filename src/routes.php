<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
//$this внутри - это контейнер

/*//example
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    //$response=$response->withHeader('Content-Type', 'application/json');
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

 //for example how to use $args
$app->get('/hello/{name}', function ($request, $response, $args) {
    $route = $request->getAttribute('route');
    //$name = $route->getName();
    //$arguments = $route->getArguments();
    //$methods = $route->getMethods();
    //$groups = $route->getGroups();
    //dump($name);
    //dump($arguments);
    //dump($methods);
    //dump($groups);
    return $this->renderer->render($response, 'index.phtml', [
        'name1' => $args['name']
    ]);
})->setName('hello');
*/
$app->get('/', function ($request, $response) {
    return $this->renderer->render($response, 'index.phtml');
})->setName('main-page');

$app->group('/albums', function(){
    $this->get('', 'albumController:indexAction')->setName('gallery');
    $this->map(['GET', 'POST'],'/create', 'albumController:createAction')->setName('create-album');
    $this->map(['GET', 'POST'],'/addphoto', 'albumController:addPhotoAction')->setName('add-photo');
});

$app->group('/albums/{idAlbum:[0-9]+}', function(){
    $this->get('', 'albumController:viewAction')->setname('view-ablum');
    $this->map(['GET', 'POST'],'/edit', 'albumController:editAction')->setname('edit-ablum');
    $this->get('/delete', 'albumController:deleteAction')->setname('delete-ablum');
    $this->map(['GET', 'POST'],'/newphoto','albumController:newPhotoAction')->setname('newPhoto-ablum');
});

$app->group('/albums/{idAlbum:[0-9]+}/photos/{idPhoto:[0-9]+}', function(){
    $this->get('','albumController:showPhotoAction')->setName('show-photo');
    $this->get('/delete','albumController:deletePhotoAction')->setName('delete-photo');
    $this->map(['GET', 'POST'],'/edit','albumController:editPhotoAction')->setName('edit-photo');
});

$app->any('/404', function ($request, $response) {
    $response=$response->withStatus(404);
    return $this->renderer->render($response, '404.phtml');
})->setName('404-page');

