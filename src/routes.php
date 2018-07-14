<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->group('/smart-signature', function() use ($app) {
    $app->group('/api/v1', function() use ($app) {
        $app->post('/login', 'SmartSignature\SessionController:login');
        $app->get('/documents', 'SmartSignature\DocumentController:documents');
        $app->get('/documents/pending', 'SmartSignature\DocumentController:pending');
        $app->get('/documents/signed', 'SmartSignature\DocumentController:signed');
        $app->post('/documents/{id:[0-9]+}/sign', 'SmartSignature\DocumentController:sign');
    })->add(new Middleware\TokenAuth($app->getContainer()));
});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
