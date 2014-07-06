<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim(array(
    // change to 'development' for testing
    'mode' => 'development'
));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => false,
        'config.path' => 'config/prod/'
    ));
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config(array(
        'log.enable' => false,
        'debug' => true,
        'config.path' => 'config/dev/'
    ));
});

// Define mysql connector
$app->container->singleton('mysql', function () {
    $app = \Slim\Slim::getInstance();
    $config = parse_ini_file(getAppConfigFile('mysql.ini'));
    $pdo = new PDO("mysql:host=". $config['db.hostname'].";dbname=".$config['db.schema'], $config['db.user'], $config['db.password']);
    // set the character set to utf8 to ensure proper json encoding
    $pdo->exec("SET NAMES 'utf8'");
    return $pdo;
});


$app->container->singleton('log', function() {
    $app = \Slim\Slim::getInstance();
    Logger::configure(getAppConfigFile('log4php-config.xml'));
    return Logger::getLogger('default');
});

// FIXME: Implement separation of view and data

// TODO: move index.html into the /views directory and
// point the templates to /views
$view = $app->view();
$view->setTemplatesDirectory('./');

function executeSql($query, array $params = array()) {
    $app = \Slim\Slim::getInstance();
    $app->log->debug(sprintf("Executing query: %s with params: %s", $query, json_encode($params)));
    $mysql = $app->mysql;    
    $handler = $mysql->prepare($query);
    $handler->execute($params);
    return $handler->fetchAll(PDO::FETCH_OBJ);    
};

function getAppConfigFile($configFile) {
    $app = \Slim\Slim::getInstance();
    return sprintf("%s%s", $app->config('config.path'), $configFile);
}

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
    $industries = executeSql('
		SELECT DISTINCT
			Id AS id,
			Name AS name
		FROM industries
		ORDER BY Name
	');

	$result = [];

    foreach($industries as $industry){
        $result[] = [
			'id' => $industry->id,
			'name' => $industry->name
		];
    };

	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->write(json_encode($result));
});

// jobs example
$app->get('/jobs', function () use ($app) {
	if (isset($_GET['industry']) && strlen(trim($_GET['industry'])) > 0) {
		$occupations = executeSql(
			'
				SELECT
					DISTINCT Name AS name,
					Id AS id
				FROM occupations
				WHERE IndustryId = :industry
				ORDER BY Name
			',
			['industry' => intval($_GET['industry'])]
		);
	} else {
		$occupations = executeSql('
			SELECT
				DISTINCT Name AS name,
				Id AS id
			FROM occupations
			ORDER BY Name
		');
	}

	$result = [];

    foreach($occupations as $occupation) {
        $result[] = [
			'id' => $occupation->id,
			'name' => $occupation->name
		];
    };

	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->write(json_encode($result));
});

// jobs example
$app->get('/job_description', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $job = rawurldecode($_SERVER["QUERY_STRING"]);
    $res = executeSql('SELECT DISTINCT Name, Description, MedianPayAnnual, MedianPayHourly, NumberOfJobs, EmploymentOpenings FROM occupations WHERE Name = :jobName', array(':jobName' => $job));
    foreach($res as $entry) {
        $app->response->write(json_encode($entry, JSON_PRETTY_PRINT));
    }
});

$app->get('/workexperience/:id', function($id) use($app) {
    $res = executeSql('SELECT DISTINCT Id, Name FROM workexperiences WHERE id = :id', array(':id' => $id));
    $result = [];
    foreach ($res as $entry) {
        array_push($result, array( 'id' => $id, 'name' => $entry->Name));
    }
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->write(json_encode($result));
});

$app->get('/workexperience', function() use($app) {
    $res = executeSql('SELECT DISTINCT Id, Name FROM workexperiences');
    $result = [];
    foreach($res as $entry) {
        array_push($result, array('id' => $entry->Id, 'name' => $entry->Name));        
    }
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->write(json_encode($result));
});

$app->get('/search/:searchQuery', function($searchQuery) use($app) {
    $result = array('industries' => [], 'jobs' => []);

    // find matching industries
    $industries = executeSql(
		'
			SELECT
				Id AS id,
				Name AS name
			FROM industries
			WHERE MATCH(Name) AGAINST ( :searchQuery )
		',
		['searchQuery' => $searchQuery]
	);

    $resultIndustries = [];

    foreach($industries as $industry) {
		$resultIndustries[$industry->id] = [
			'id' => $industry->id,
			'name' => $industry->name
		];
    }

    // find matching jobs
	$jobs = executeSql(
		'
			SELECT DISTINCT
				i.Id as industryId,
				i.Name as industryName,
				o.Name as jobName
			FROM occupations o,
				industries i
			WHERE o.IndustryId = i.Id
				AND MATCH(o.Description, o.Name) AGAINST ( :searchQuery )
		',
		['searchQuery' => $searchQuery]
	);

    $resultJobs = [];

    foreach($jobs as $job) {
		$resultJobs[] = [
			'name' => $job->jobName
		];

        // add job industry to industries list
		$resultIndustries[$job->industryId] = [
			'id' => $job->industryId,
			'name' => $job->industryName
		];
    }

    $result = [
		'industries' => array_values($resultIndustries),
		'jobs' => $resultJobs
	];

    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->write(json_encode($result));
});

$app->post('/occupations', function() use($app) {
    $result = [];
    if (isset($_POST['education'])) {
        $optionArray = $_POST['education'];
        array_walk($optionArray, function($value, $index) {
            $value = explode(",", $value);
        });
        $edLevels = implode(",", $optionArray);
        //$app->log->info(sprintf("Education levels %s", $edLevels));
        $res = executeSql("SELECT DISTINCT Name FROM occupations WHERE  EducationLevelId in ( $edLevels ) ORDER BY Name");
    } else {
        $res  = executeSql('SELECT DISTINCT Name FROM occupations ORDER BY Name');
    } 
    foreach($res as $occupation) {
        array_push($result, (array) $occupation);
    }
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->write(json_encode($result));
});

$app->run();
?>