<?php

function transactionSql($query, array $params = array(), &$mysql) {
    $app = \Slim\Slim::getInstance();
    $app->log->debug(sprintf("Executing query: %s with params: %s", $query, json_encode($params)));

    $handler = $mysql->prepare($query);
    $handler->execute($params);
    return $handler->fetchAll(PDO::FETCH_OBJ);
};

/**
 * A function add a response to a specific question. (Ruben)
 *
 * @param int $q_id id of the question, e.g. 1.
 * @param text $text text of the response, e.g. "You can find information on the Contact form".
 * @param int $faq_rsid id of the response source, e.g. 1.
 *
 * @return none
 */

$app->post('/add_response_from_question/:q_id/:faq_rsid', function($q_id, $faq_rsid) use ($app) {

	if (isset($_POST['text'])) {
		$text = $_POST['text'];
	} else {
		$app->response()->status(400);
		$app->response()->header('X-Status-Reason', 'Missing "text" parameter');
		$app->response->write('Missing "text" parameter');
		return;
	}

	try {
		$mysql = $app->mysql;
    	$mysql->beginTransaction();
	    transactionSql(
			'
				INSERT INTO faq_response (`Text`, `FAQ_ResponseSourceId`)
				VALUES (:text, :faq_rsid)
			',
			[
				'text' => $text,
				'faq_rsid' => $faq_rsid
			],
			$mysql
		);
		$r_id = transactionSql('SELECT LAST_INSERT_ID() AS id',
			[],
			$mysql
		);
	    $r_id = $r_id[0]->id;
	    transactionSql(
	        '
	            INSERT INTO faq_responsefaq_question (`FAQ_Response_Id`, `FAQ_Question_Id`)
	            VALUES (:r_id, :q_id)
	        ',
	        [
	            'r_id' => $r_id,
	            'q_id' => $q_id
	        ],
	        $mysql
	    );
	    $mysql->commit();
	} catch (Exception $e) {
	    $mysql->rollback();
	}
});

/**
 * A function that will flag a question as a duplicate or as containing inappropriate language. (Ruben)
 *
 * @param int $q_id id of the question, e.g. 1.
 *
 * @return none
 */

/**
 * ALTER TABLE `faq_question` ADD `Inappropriate` BOOLEAN NOT NULL DEFAULT FALSE;
 * */

$app->get('/update_inappropriate_question/:q_id', function($q_id) use ($app) {
	executeSql(
        '
			UPDATE faq_question
			SET Inappropriate=1
			WHERE Id=:q_id;
        ',
        [
            'q_id' => $q_id
        ]
    );
});

/**
 * This function should be sorting questions by most recent.. (Ruben)
 *
 * @param none
 *
 * @return json composed by questions sorted by most recent.
 */

$app->get('/get_recent_questions/', function() use ($app) {
	$recentQuestions = executeSql(
        '
			SELECT *
			FROM faq_question
			ORDER BY dateCreated DESC;
        '
    );

	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->write(json_encode($recentQuestions));
});

/**
 * A function that accepts an input from the user that changes the question's state to read from unread.
 *
 * @param int $q_id id of the question, e.g. 1.
 *
 * @return none
 */

/**
 * ALTER TABLE `faq_question` ADD `Read` BOOLEAN NOT NULL DEFAULT FALSE;
 * */

$app->get('/update_read_question/:q_id', function($q_id) use ($app) {
	executeSql(
        '
			UPDATE faq_question
			SET Read=1
			WHERE Id=:q_id;
        ',
        [
            'q_id' => $q_id
        ]
    );
});

/**
 * A function that will accept a like to to a specific response.
 *
 * @param int $r_id id of the response, e.g. 1.
 *
 * @return none
 */

/**
 * ALTER TABLE `faq_response` ADD `likeCount` INT NOT NULL DEFAULT '0' ;
 * */

$app->get('/increase_like_response/:r_id', function($r_id) use ($app) {
	executeSql(
        '
			UPDATE faq_response
			SET likeCount=likeCount+1
			WHERE Id=:r_id;
        ',
        [
            'r_id' => $r_id
        ]
    );
});
