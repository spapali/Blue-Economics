<?php

require 'vendor/autoload.php';
//include '../mysql_config.php';
$app = new \Slim\Slim();

// TODO: move index.html into the /views directory and
// point the templates to /views
$view = $app->view();
$view->setTemplatesDirectory('./');

// Define mysql connector
// TODO: pull user:password out of ini file
$app->container->singleton('mysql', function () {
    //return new PDO('mysql:host=127.0.0.1;dbname=BlueEconomics', $GLOBALS['db_user'], $GLOBALS['db_pass']);
    return new PDO('mysql:host=localhost;dbname=BlueEconomics', 'root', 'root');
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
        echo "<br>";
    };

});

// industry example
$app->get('/industries', function () use ($app) {
    $mysql = $app->mysql;
    $handler = $mysql->prepare("SELECT * FROM `industries`");
    $handler->execute();
    $res = $handler->fetchAll(PDO::FETCH_OBJ);
 
    foreach($res as $row){
        echo "<span>";
        echo $row->Name;
        echo "</span>";
        echo "<br>";
    };

});

// jobs example
$app->get('/jobs', function () use ($app) {
    $mysql = $app->mysql;
    $handler = $mysql->prepare("SELECT * FROM `occupations`");
    $handler->execute();
    $res = $handler->fetchAll(PDO::FETCH_OBJ);
 
    foreach($res as $row){
        echo "<span>";
        echo $row->Name;
        echo "</span>";
        echo "<br>";
    };

});

$app->run();
?>