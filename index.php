<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();

// TODO: move index.html into the /views directory and
// point the templates to /views
$view = $app->view();
$view->setTemplatesDirectory('./');

// Define mysql connector
// TODO: pull user:password out of config file
$app->container->singleton('mysql', function () {
    return new PDO('mysql:host=127.0.0.1;dbname=BlueEconomics', 'user', 'password');
});

// main page
$app->get('/', function () use ($app) {
    $app->render('index.html');
});

// api example
$app->get('/api', function () use ($app) {
    $mysql = $app->mysql;
    $handler = $mysql->prepare("SELECT * FROM `filters` LIMIT 10");
    $handler->execute();
	$res = $handler->fetchAll(PDO::FETCH_OBJ);
 
    foreach($res as $row){
        echo $row->Name;
    };

});

$app->run();
?>