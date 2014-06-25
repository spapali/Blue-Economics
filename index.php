<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    'log.enabled' => true
));

// FIXME: Implement separation of view and data

// TODO: move index.html into the /views directory and
// point the templates to /views
$view = $app->view();
$view->setTemplatesDirectory('./');

function executeSql($query, array $params = array()) {
    $app = \Slim\Slim::getInstance();
//    $app->log->info(sprintf("Executing query: %s", $query));
//    $app->log->info(sprintf("parameters: %s", var_export($params)));
    $mysql = $app->mysql;    
    $handler = $mysql->prepare($query);
    $handler->execute($params);
    return $handler->fetchAll(PDO::FETCH_OBJ);    
};

// Define mysql connector
$app->container->singleton('mysql', function () {
    $config = parse_ini_file('config/mysql.ini');
    return new PDO("mysql:host=". $config['db.hostname'].";dbname=".$config['db.schema'], $config['db.user'], $config['db.password']);
});

// main page
$app->get('/', function () use ($app) {
    $app->render('index.html');
});

// api example
$app->get('/api', function () use ($app) {
    $res = executeSql("SELECT * FROM filters LIMIT 10");
    foreach($res as $row) {
        echo $row->Name;
        echo "<br>";
    };

});

// industry example
$app->get('/industries', function () use ($app) {
    $res = executeSql("SELECT DISTINCT Id, Name FROM industries ORDER BY Name");
    foreach($res as $row){
        echo "<a href=\"#\" onclick=\"return loadJob(";
        echo $row->Id;
        echo ")\" class=\"selectable_result\">" . $row->Name . "</a>";
        echo "<br />";
    };

});

// jobs example
$app->get('/jobs', function () use ($app) {
    $industry = intval($_GET['industry']);
    $res = empty($_GET) ? executeSql("SELECT DISTINCT Name FROM occupations ORDER BY Name") : executeSql("SELECT DISTINCT Name FROM occupations WHERE IndustryId = :industry ORDER BY Name", array(':industry' => $industry));
    foreach($res as $entry) {        
        $row = $entry->Name;
        echo "<a href=\"#\" onclick=\"return loadJobDetails('$row')\" class=\"selectable_result\">$row</a>";
        echo "<br>";    
    };
});

// jobs example
$app->get('/job_description', function () use ($app) {    
    $job = rawurldecode($_SERVER["QUERY_STRING"]);
    $res = executeSql('SELECT DISTINCT Name, Description, MedianPayAnnual, MedianPayHourly, NumberOfJobs, EmploymentOpenings FROM occupations WHERE Name = :jobName', array(':jobName' => $job));
    foreach($res as $entry) {
        echo json_encode($entry, JSON_PRETTY_PRINT);
    }
});

$app->get('/workexperience/:id', function($id) use($app) {
    $res = executeSql('SELECT DISTINCT Id, Name FROM workexperiences WHERE id = :id', array(':id' => $id));
    $result = [];
    foreach ($res as $entry) {
        array_push($result, array( 'id' => $id, 'name' => $entry->Name));
    }
    echo json_encode($result);
});

$app->get('/workexperience', function() use($app) {
    $res = executeSql('SELECT DISTINCT Id, Name FROM workexperiences');
    $result = [];
    foreach($res as $entry) {
        array_push($result, array('id' => $entry->Id, 'name' => $entry->Name));        
    }
    echo json_encode($result);
});

$app->get('/search/:searchQuery', function($searchQuery) use($app) {
    $result = array('industries' => [], 'jobs' => []);

    // find industries
    $query = "SELECT Id, Name FROM industries WHERE MATCH(Name) AGAINST ( '$searchQuery' )";
    $res = executeSql($query);
    $accumulator = [];
    foreach($res as $industry) {
        array_push($accumulator, array( 'id' => $industry->Id, 'name' => $industry->Name));
    }
    $result['industries'] = $accumulator;

    // find matching jobs
    $accumulator = [];
    $query = "SELECT DISTINCT Name FROM occupations WHERE MATCH(description, name) AGAINST ( '$searchQuery' )";
    $res = executeSql($query);
    foreach($res as $job) {
        array_push($accumulator, array( 'name' => $job->Name));
    }
    $result['jobs'] = $accumulator;

    echo json_encode($result);
});

$app->post('/occupations', function() use($app) {
    $result = [];
    if (isset($_POST['education'])) {
        $optionArray = $_POST['education'];
        array_walk($optionArray, function($value, $index) {
            $value = explode(",", $value);
        });
        $edLevels = implode(",", $optionArray);
        $app->log->info(sprintf("Education levels %s", $edLevels));
        $res = executeSql("SELECT DISTINCT Name FROM occupations WHERE  EducationLevelId in ( $edLevels )");
    } else {
        $res  = executeSql('SELECT DISTINCT Name FROM occupations');
    } 
    foreach($res as $occupation) {
        array_push($result, (array) $occupation);
    }
    echo json_encode($result);
});

$app->run();
?>