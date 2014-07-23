<?php

use Base\Responses as BaseResponses;

class Responses extends BaseResponses
{

    /**
     * @param \Propel\Runtime\Connection\ConnectionInterface $conn
     * @return bool|void
     */
    public function preInsert(\Propel\Runtime\Connection\ConnectionInterface $conn = null)
    {
        $this->setCreated(time());
        return true;
    }

    /**
     * notify user who posted question about response
     */
    public function postSave(\Propel\Runtime\Connection\ConnectionInterface $conn = null)
    {
        $this->updateQuestionState($conn);
        $this->notifyUser($conn);
    }

    private function updateQuestionState($conn)
    {
        $state = new \ExpertQuestionState();
        $state->setExperts(ExpertsQuery::create()->findOneByUsername($this->getExpert()));
        $state->setQuestionId($this->getQuestionId());
        $state->setIsResponded(true);
        $state->setIsRead(true);
        $state->save();
    }

    private function notifyUser($conn = null)
    {
        //TODO: Make this a more general function. For now only email supported.
        //TODO: Complete/cleanup email setup and execution; This should probably be config driven
        /*
        $mailer = new PHPMailer();
        $question = QuestionsQuery::create($conn)->findOneById($this->getQuestionId());
        $mailer->addAddress($question->getSubmitter());
        $mailer->Subject = 'Answer to your Question: ' . $question->getQuestion();
        $mailer->setFrom($this->getExpert());
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates/');
        $twig = new Twig_Environment($loader, array(
            'cache' => __DIR__ . '/../templates/cache/'
        ));
        $context = array('expert' => $this->getExpert(), 'time' => $this->getCreated()->format(DateTime::RFC822));
        $mailer->Body = $twig->loadTemplate('question-response-email.html')->render($context);
        $mailer->send();
        */
        $logger = \BlueEcons\FAQApi::getLogger(get_class($this));
        $logger->info(sprintf("User notification will be generated for question %s. Answered by %s",
            $this->getQuestionId(),
            $this->getExpert()));

    }

}
