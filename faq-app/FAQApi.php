<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gheming
 * Date: 6/29/14
 * Time: 2:30 PM
 * To change this template use File | Settings | File Templates.
 */

namespace BlueEcons;


use ExpertGroupQuery;
use Map\ExpertQuestionStateTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\TransactionTrait;
use Propel\Runtime\Propel;
use Logger;

/**
 * Encapsulation of FAQ Application actions
 *
 * Class FAQApi
 *
 * @package BlueEcons
 */
class FAQApi
{

    /**
     * @param string $loggerName
     *
     * @return \Logger
     */
    static function getLogger($loggerName = 'FAQApi')
    {
        // FIXME: Need a better way to load the config appropriate to the application mode
        Logger::configure(__DIR__ . '/../config/prod/log4php-config.xml');
        return Logger::getLogger($loggerName);
    }

    /**
     * Create an Expert-Group. This is tied to a job/industry SocCode ( from the Jobs & Industry tables )
     *
     * @param $name
     * @param $socCode
     *
     * @return \ExpertGroup
     */
    public function createExpertGroup($name, $socCode)
    {
        $group = new \ExpertGroup();
        $group->setGroupName($name);
        $group->setSocCode($socCode);
        $group->save();
        return $group;
    }

    /**
     * Create an expert: A user who can response to questions
     *
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $org
     * @param $bio
     * @param $password
     *
     * @return \Experts
     */
    public function createExpert($username, $firstName, $lastName, $org, $bio, $password)
    {
        $expert = new \Experts();
        $expert->setUsername($username);
        $expert->setFirstName($firstName);
        $expert->setLastName($lastName);
        $expert->setOrganization($org);
        $expert->setBio($bio);
        // TODO: find a better way to do this
        $expert->setPassword(md5($password));
        $expert->save();
        return $expert;
    }

    /**
     * Add an expert to an expert to a group. Questions are linked to SocCodes and Groups are linked likewise
     *
     * @param \Experts     $expert
     * @param \ExpertGroup $group
     *
     * @return \ExpertGroupMembers
     */
    public function addExpertToGroup(\Experts $expert, \ExpertGroup $group)
    {
        $membership = new \ExpertGroupMembers();
        $membership->setExpert($expert->getUsername());
        $membership->setGroupName($group->getGroupName());
        $membership->save();
        return $membership;
    }

    /**
     * Add a question to the FAQ database
     *
     * @param $submitter
     * @param $text
     * @param $socCode
     *
     * @return \Questions
     */
    public function addQuestion($submitter, $text, $socCode)
    {
        $question = new \Questions();
        $question->setSocCode($socCode);
        $question->setQuestion($text);
        $question->setSubmitter($submitter);
        $question->save();
        $eligibleExperts = $this->findExperts($socCode);
        $this->addQuestionToExpertQueue($question, $eligibleExperts);
        return $question;
    }

    private function findExperts($socCode)
    {
        $groups = ExpertGroupQuery::create()->findBySocCode($socCode);
        $result = new ObjectCollection();
        foreach ($groups as $group) {
            $experts = \ExpertGroupMembersQuery::create()->findByGroupName($group->getGroupName());
            foreach ($experts as $expert) {
                $result->push($expert);
            }
        }
        return $result;
    }

    private function addQuestionToExpertQueue(\Questions $question, ObjectCollection $expertMembers)
    {
        $conn = Propel::getWriteConnection(ExpertQuestionStateTableMap::DATABASE_NAME);
        $conn->beginTransaction();
        try {
            foreach ($expertMembers as $member) {
                $qState = new \ExpertQuestionState();
                $qState->setUsername($member->getExpert());
                $qState->setQuestionId($question->getId());
                $qState->save($conn);
            }
            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    /**
     * Adds an expert response to a question. This action has the effect of generating an email to the person asking
     * the questions as well as setting the question state to read + responded for this expert
     *
     * @param $questionId
     * @param $expertUserName
     * @param $responseText
     *
     * @return \Responses
     */
    public function addResponse($questionId, $expertUserName, $responseText)
    {
        $response = new \Responses();
        $response->setQuestionId($questionId);
        $response->setExpert($expertUserName);
        $response->setResponse($responseText);
        $response->save();
        return $response;
    }
}