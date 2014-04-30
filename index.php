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
    $handler = $mysql->prepare("SELECT * FROM `industries` ORDER BY `Name`");
    $handler->execute();
    $res = $handler->fetchAll(PDO::FETCH_OBJ);
 
    foreach($res as $row){
        echo "<a href=\"#\" onclick=\"return loadJob(";
        echo $row->Id;
        echo ")\" style=\"color:#333333;\">" . $row->Name . "</a>";
        echo "<br />";
    };

});

// jobs example
$app->get('/jobs', function () use ($app) {
    $mysql = $app->mysql;
    
    if (empty($_GET)) {
        $handler = $mysql->prepare("SELECT * FROM `occupations` ORDER BY `Name`");
        $handler->execute();
        $res = $handler->fetchAll(PDO::FETCH_OBJ);
    } else {
        $industry = intval($_GET['industry']);
        $handler = $mysql->prepare("SELECT * FROM `occupations` WHERE `IndustryId` = '".$industry."' ORDER BY `Name`");
        $handler->execute();
        $res = $handler->fetchAll(PDO::FETCH_OBJ);
    };
    
    $list = array();
    $x = 0;
    
    foreach ($res as $item) {
        $list[$x] = $item->Name;
        $x++;
    };

    $new_array = array_unique($list);
        
    foreach($new_array as $row){

        echo "<span>";
        echo $row;
        echo "</span>";
        echo "<br>";
    
    };

});

$app->run();
?>