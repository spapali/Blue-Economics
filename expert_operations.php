<?php

/**
 * This file includes the operations of the expert in their dashboard
 */

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
 * @param int $q_id id of the question, e.g. 636724.
 * @param int $e_id id of the expert, e.g. 5023.
 * @param text $text text of the response, e.g. "You can find information on the Contact form".
 *
 * @return none
 */

$app->post('/add_response_from_question/:q_id/:e_id', function($q_id, $e_id) use ($app) {

	if (isset($_POST['text'])) {
		$text = $_POST['text'];
	} else {
		$app->response()->status(400);
		$app->response()->header('X-Status-Reason', 'Missing "text" parameter');
		$app->response->write('Missing "text" parameter');
		return;
	}
	
	executeSql(
        '
			INSERT INTO faq_response (`Text`, `DateCreated`, `FAQ_ExpertId`, `FAQ_QuestionId`)
			VALUES (:text, NOW(), :e_id, :q_id)
        ',
        [
        	'text' => $text,
            'e_id' => $e_id,
        	'q_id' => $q_id
        ]
    );
});

/**
 * A function that will flag a question as a duplicate. (Ruben)
 *
 * @param int $e_id id of the expert, e.g. 5023.
 * @param int $q_id id of the question, e.g. 636724.
 *
 * @return none
 */

$app->get('/update_duplicated_question/:e_id/:q_id', function($e_id, $q_id) use ($app) {
	executeSql(
        '
			UPDATE faq_questionassignment
			SET IsDuplicated=1
			WHERE FAQ_ExpertID=:e_id AND FAQ_QuestionID=:q_id;
        ',
        [
        	'e_id' => $e_id,
            'q_id' => $q_id
        ]
    );
});

/**
 * A function that will flag a question containing inappropriate language. (Ruben)
 *
 * @param int $e_id id of the expert, e.g. 5023.
 * @param int $q_id id of the question, e.g. 636724.
 * 
 * @return none
 */

$app->get('/update_censored_question/:e_id/:q_id', function($e_id, $q_id) use ($app) {
	executeSql(
        '
			UPDATE faq_questionassignment
			SET IsCensored=1
			WHERE FAQ_ExpertID=:e_id AND FAQ_QuestionID=:q_id;
        ',
        [
        	'e_id' => $e_id,
            'q_id' => $q_id
        ]
    );
});

/**
 * A function that accepts an input from the user that changes the question's state to read from unread. (Ruben)
 *
 * @param int $e_id id of the expert, e.g. 5023.
 * @param int $q_id id of the question, e.g. 636724.
 * 
 * @return none
 */

$app->get('/update_read_question/:e_id/:q_id', function($e_id, $q_id) use ($app) {
	executeSql(
        '
			UPDATE faq_questionassignment
			SET IsRead=1
			WHERE FAQ_ExpertID=:e_id AND FAQ_QuestionID=:q_id;
        ',
        [
        	'e_id' => $e_id,
            'q_id' => $q_id
        ]
    );
});

/**
 * This function should be sorting questions by most recent.. (Ruben)
 *
 * @param int $e_id id of the expert, e.g. 5023.
 *
 * @return json composed by questions sorted by most recent.
 */

$app->get('/get_recent_questions/:e_id', function($e_id) use ($app) {
	$recentQuestions = executeSql(
        '
			SELECT faq_question.Id, faq_question.Text, faq_question.OccupationId, faq_question.DateCreated
			FROM faq_question
			INNER JOIN faq_questionassignment ON
				faq_questionassignment.FAQ_QuestionID = faq_question.Id
			WHERE faq_questionassignment.FAQ_ExpertID=:e_id
			ORDER BY DateCreated DESC;
        ',
        [
        	'e_id' => $e_id
        ]
    );

	$app->response->headers->set('Content-Type', 'application/json');
	$app->response->write(json_encode($recentQuestions));
});

/**
 * A function that will accept a like to to a specific response. (Ruben)
 *
 * @param int $e_id id of the expert, e.g. 5023.
 * @param int $r_id id of the response of another expert, e.g. 633491.
 *
 * @return none
 */

$app->get('/add_like_response/:e_id/:r_id', function($e_id, $r_id) use ($app) {
	executeSql(
        '
        	INSERT INTO faq_responserating (`FAQ_ResponseId`, `FAQ_ExpertId`)
			VALUES (:r_id, :e_id)
        ',
        [
        	'e_id' => $e_id,
            'r_id' => $r_id
        ]
    );
});
