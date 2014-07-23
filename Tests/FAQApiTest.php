<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gheming
 * Date: 6/29/14
 * Time: 3:17 PM
 * To change this template use File | Settings | File Templates.
 */

namespace BlueEcons;

//require_once '../vendor/autoload.php';
use Base\Questions;

require_once dirname(__FILE__).'/../generated-conf/config.php';

class FAQApiTest extends \PHPUnit_Framework_TestCase {

    private $api;

    private $groupName = "TestGroupName";

    private $socCode = "11-0000";

    /**
     * @afterClass
     */
    public static function tearDownAfterClass() {
        \ResponsesQuery::create()->deleteAll();
        \ExpertQuestionStateQuery::create()->deleteAll();
        \ExpertGroupMembersQuery::create()->deleteAll();
        \ExpertGroupQuery::create()->deleteAll();
        \ExpertsQuery::create()->deleteAll();
        \QuestionsQuery::create()->deleteAll();
    }

    /**
     * @before
     */
    public function setUp() {
        $this->api = new FAQApi();
    }

    public function testCreateExpertGroup() {
        $group = $this->api->createExpertGroup($this->groupName, $this->socCode);
        \PHPUnit_Framework_Assert::assertNotNull($group);
        return $group;
    }

    /**
     * @param $group
     * @return array
     * @depends testCreateExpertGroup
     */
    public function testCreateExpert($group) {
        $expert = $this->api->createExpert("test@nowhere.com", "FName", "LName", "BlueEcon", "I'm a test user", "password");
        \PHPUnit_Framework_Assert::assertNotNull($expert);
        return array("expert" => $expert, "group" => $group );
    }

    /**
     * @param $data
     * @depends testCreateExpert
     */
    public function testAddExpertToGroup($data) {
        $membership = $this->api->addExpertToGroup($data["expert"], $data["group"]);
        \PHPUnit_Framework_Assert::assertNotNull($membership);
    }


    /**
     * @depends testAddExpertToGroup
     */
    public function testAddQuestion() {
        $question = $this->api->addQuestion("user@someplace.com", "red or blue pill?", $this->socCode);
        \PHPUnit_Framework_Assert::assertNotNull($question);
        \PHPUnit_Framework_Assert::assertNotNull($question->getId());
        $qId = $question->getId();
        //check that question state has been created for expert
        $state = \ExpertQuestionStateQuery::create()->findOneByQuestionId($qId);
        \PHPUnit_Framework_Assert::assertNotNull($state);
        \PHPUnit_Framework_Assert::assertFalse($state->getIsRead());
        return $question;
    }

    /**
     * @depends testAddQuestion
     */
    public function testAddResponse(Questions $question) {
        $response = $this->api->addResponse($question->getId(), "test@nowhere.com", "The red pill of course!");
        \PHPUnit_Framework_Assert::assertNotNull($response);
    }
}
