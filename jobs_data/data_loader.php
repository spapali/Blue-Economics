<?php
require '../vendor/autoload.php';

//var $app = null;

ini_set('auto_detect_line_endings',TRUE);

function initialize() {
    $app = new \Slim\Slim(array(
        // change to 'development' for testing
        'mode' => 'production'
    ));

    // Only invoked if mode is "production"
    $app->configureMode('production', function () use ($app) {
        $app->config(array(
            'log.enable' => false,
            'debug' => false,
            'config.path' => '../config/prod/'
        ));
    });

    // Only invoked if mode is "development"
    $app->configureMode('development', function () use ($app) {
        $app->config(array(
            'log.enable' => false,
            'debug' => true,
            'config.path' => '../config/dev/'
        ));
    });

}

function loadTSV($fileName, $skipLines = 0, $primaryKey = null, $excludedCols = []) {
    $excluded = array_combine($excludedCols, $excludedCols);
    $header = null;
    $records = array();
    if (($handle = fopen($fileName, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, null, "\t", '"')) !== FALSE) {
            if($skipLines == 0 ) {
                if($header == null) {
                    $header = $data;
                }else {
                    $data = array_combine($header, $data);
                    array_push($records, array_diff_key($data, $excluded));
                }
            } else {
              $skipLines--;
            }
        }
        fclose($handle);
    }

    $result = array();

    $structMappingFunc = function($item, $key, $userData) {
        $userData['output'][$item[$userData['pKey']]] = $item;
    };

    if($primaryKey != null) {
        array_walk($records, $structMappingFunc, array('pKey' => $primaryKey, 'output' => &$result));
        return $result;
    }

    return $records;
}

function growthScore($rawValue) {
    switch($rawValue) {
        case "Very Favorable":
            return 4;
        case "Favorable":
            return 3;
        case "Unfavorable":
            return 2;
        case "Very Unfavorable":
            return 1;
        case "NA":
            return 3;
        default:
            return 0;
    }
}

function createDbQueries($data, $tableName) {
    $queries = [];

    foreach( $data as $entry) {
        $fieldNames = implode(",", array_keys($entry));
        $fieldVals = implode(",", array_map(function($item) {
            $strVal = is_array($item) ? implode("|", $item) : $item;
            return "\"$strVal\"";
        }, array_values($entry)));
        $query = sprintf("INSERT INTO blueeconomics.$tableName(%s) VALUES (%s);",$fieldNames, $fieldVals);
        print $query . "\n";
        array_push($queries, $query);
    };

    return $queries;
}

function getAppConfigFile($configFile) {
    $app = \Slim\Slim::getInstance();
    return sprintf("%s%s", $app->config('config.path'), $configFile);
}

function executeQueries($statements) {
    $config = parse_ini_file(getAppConfigFile('mysql.ini'));
    $pdo = new PDO("mysql:host=". $config['db.hostname'].";dbname=".$config['db.schema'], $config['db.user'], $config['db.password']);
    // set the character set to utf8
    $pdo->exec("SET NAMES 'utf8'");

    // execute sql scripts
    $scripts = ['ddl.sql', '../DDL_TABDELIMITED_DATA/blueecondbDUMP.sql'];
    foreach($scripts as $script) {
        $ddl = file_get_contents($script);
        $pdo->exec($ddl);
    };

    // execute generated statements
    foreach($statements as $stmt) {
       $pdo->exec($stmt);
    };
}


function calcJobScores(&$data) {
    $weights = [.5, .5, 1, 1];
    foreach($data as $key => &$details) {
        $scores = [ 0, 0, 0, 0];
        if(array_key_exists("Prospects", $details)) {
            $details["GrowthScore"] = $scores[0] = growthScore($details["Prospects"]);
        }
        if(array_key_exists("EntryEduLevel", $details)){
            $details["EducationScore"] = $scores[1] = educationScore($details["EntryEduLevel"]);
        }
        if(array_key_exists("MedianAnnWage", $details)) {
            $details["IncomeScore"] = $scores[2] = incomeScore($details["MedianAnnWage"]);
        }
        if(array_key_exists("AnnualAvgOpenings", $details)) {
            $details["AvailabilityScore"] = $scores[3] = availabilityScore($details["AnnualAvgOpenings"]);
        }

        $blueEconScore = 0;
        foreach($weights as $idx => $weight) {
            if(is_numeric($scores[$idx])) {
                $blueEconScore += $weight * $scores[$idx];
            }
        }

        $details["BlueEconScore"] = $blueEconScore;
        $details["BlueEconGrade"] = blueEconGrade($blueEconScore);
    }

    //find the max weight score and use to normalize all scores
    $maxScore = max(array_map(function($item, $key){
        return $item["BlueEconScore"];
    }, $data));

    $func = function(&$item, $key, $userData) {
        $item["BlueEconScore"] = $item["BlueEconScore"] * 5 / $userData['maxScore'];
    };

    array_walk($data, $func, array('maxScore' => $maxScore));
}

function educationScore($rawValue) {
    switch(strtolower($rawValue)) {
        case "less than high school":
            return 4;
        case "high school diploma or equivalent":
        case "high school":
            return 3;
        case "postsecondary non-degree award":
        case "some college, no degree":
            return 2;
        case "associate's degree":
            return 1;
        case "bachelor's degree":
        case "master's degree":
        case "doctoral or professional degree":
            return 0;
        default:
            throw new InvalidArgumentException("can't map [$rawValue] to an score");
    }
}

function incomeScore($rawValue) {
    if(!is_numeric($rawValue)) {
        $matches = array();
        preg_match('/^[><](\d+)/', $rawValue, $matches);
        if(count($matches) != 0) {
            $rawValue = $matches[1];
        }
    }
    if(is_numeric($rawValue)) {
        //TODO: Figure out where 130K came from
        $maxIncome = 130000/4;
        return doubleval($rawValue)/$maxIncome;
    }else {
        throw new InvalidArgumentException("$rawValue is not a valid numeric value");
    }
}

function availabilityScore($rawValue) {
    if($rawValue == "Less than 10") {
        $rawValue = 10;
    }

    if(is_numeric($rawValue)) {
        //TODO: Figure out where 11K came from
        $maxJobs = 11000/4;
        return $rawValue/$maxJobs;
    }else {
        throw new InvalidArgumentException("[$rawValue] is not a valid numeric value");
    }
}

function blueEconGrade($totalVal) {
    if($totalVal > 3) {
        return "Premium";
    }elseif($totalVal > 1.5) {
        return "Great";
    }elseif($totalVal > 0.4) {
        return "Good";
    }else{
        return "Not Recommended";
    }
}

function cleanupWages(&$wageData) {
    array_walk($wageData, function(&$item, $key) {
        //AvgAnnWage	MedianAnnWage	AvgEntryWage	AvgExpWage
        $cols  = ["AvgAnnWage",	"MedianAnnWage", "AvgEntryWage", "AvgExpWage"];
        foreach($cols as $col) {
            $matches = array();
            preg_match('/^[><](\d+)/', $item[$col], $matches);
            if(count($matches) != 0) {
                $item[$col] = $matches[1];
            }
        }
    });
    print_r($wageData);

}

function main() {

    $shortopts = "i:j:p:w:";  // Required value

    $longopts  = array(
        "industry:",
        "jobs:",
        "prospects:",
        "wages:",
    );

    //initialize
    initialize();

    $options = getopt($shortopts, $longopts);
    $industries = loadTSV($options['industry'], 0, "SocCode");
    $jobs = loadTSV($options['jobs'], 0, "SocCode", ["JobTitle", ""]);
    $prospects = loadTSV($options['prospects'], 1, "SocCode");
    $wages = loadTSV($options['wages'], 1, "SocCode", ["JobTitle", ""]);
    cleanupWages($wages);
    $master = array_merge_recursive($jobs, $prospects, $wages);
    array_walk($master, function(&$item, $key) {
        if(is_array($item)) {
            array_walk($item, function(&$item, $key){
                if(is_array($item)) {
                    $item = array_unique($item);
                }
            });
        }
    });

    usort($master, function($a, $b) {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    });

    calcJobScores($master);

    $industries = createDbQueries($industries, "industry");
    $jobs = createDbQueries($master, "jobs");

    executeQueries(array_merge($industries, $jobs));

}

main();

?>