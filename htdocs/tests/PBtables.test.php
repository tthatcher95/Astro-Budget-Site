<?php

require_once(dirname(__FILE__) . '/../models/PBTables.php');

class PBTablesTest extends PHPUnit_Framework_TestCase {
  private $jsonFile = 'data.PBTables.json';
  private $jsonData;

  /**
   * @beforeClass
   */
  function setUpData() {
    $jsonFileData = file_get_contents($this->jsonFile);
    $this->jsonData = json_decode($jsonFileData, true);
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
        break;
        case JSON_ERROR_DEPTH:
            echo ' - Maximum stack depth exceeded';
            print "\n\n";
            exit;
        break;
        case JSON_ERROR_STATE_MISMATCH:
            echo ' - Underflow or the modes mismatch';
            print "\n\n";
            exit;
        break;
        case JSON_ERROR_CTRL_CHAR:
            echo ' - Unexpected control character found';
            print "\n\n";
            exit;
        break;
        case JSON_ERROR_SYNTAX:
            echo ' - Syntax error, malformed JSON';
            print "\n\n";
            exit;
        break;
        case JSON_ERROR_UTF8:
            echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            print "\n\n";
            exit;
        break;
        default:
            echo ' - Unknown error';
            print "\n\n";
            exit;
        break;
    }
  }

  public function peopleTestData () {
    $this->setUpData();
    return array (array ($this->jsonData['people'][0]));
  }

  /**
   * @dataProvider peopleTestData
   */
  public function testPeople ($personData) {
    $pbdb = new PBTables();

    # Add person
    $pbdb->addPerson ($personData['name'], $personData['username']);
    $personResult = $pbdb->getPerson (null, $personData['name'], $personData['username']);
    $this->assertEquals($personData['name'], $personResult[0]['name'], "The addPerson result doesn't match");

    # Update person
    $pbdb->updatePerson ($personResult[0]['peopleid'], null, $personData['username2']);
    $personResult = $pbdb->getPerson (null, null, $personData['username2']);
    $this->assertEquals($personData['username2'], $personResult[0]['username'], "The updatePerson result doesn't match");

    # Delete test people records
    $personResult = $pbdb->getPerson (null, $personData['name'], null);
    # Delete person (or extra people if tests were run w/o successful deletes
    foreach ($personResult as $person) {
      $pbdb->deletePerson ($person['peopleid']);
    }

    # Test that everyone was deleted
    $personResult = $pbdb->getPerson (null, $personData['name'], null);
    $this->assertEquals(0, count($personResult));
  }

  public function salaryTestData () {
    return array (array ("Zippy D. Pinhead", "zdp001", "01/01/2010", "GS-1330/13/2", 
                         "Research Space Scientist", "FTP", 0, 69515.20, 19545.20, 4, 1.25), 
                  array ("Zippy D. Pinhead", "zdp001", "01/01/2013", "GS-1330/13/3", 
                         "Research Space Scientist", "FTP", 0, 70000.20, 22540.20, 4, 1.25),
                  array ("Zippy D. Pinhead", "zdp001", "01/01/2020", "GS-1330/14/1", 
                         "Research Space Scientist", "FTP", 0, 110000.20, 28540.20, 4, 1.25));
  }

  /**
   * @depends testPeople
   * @dataProvider salaryTestData
   */
  function testSalary ($name, $username, $effdate, $payplan, $title, $appttype, $authhours, 
                       $estsalary, $estbenefits, $leave, $laf) {
    $pbdb = new PBTables();

    # Add person
    $pbdb->addPerson ($name, $username);
    $personResult = $pbdb->getPerson (null, $name, $username);
    $this->assertEquals($name, $personResult[0]['name'], "The addPerson result doesn't match");

    # get peopleid
    $personResult = $pbdb->getPerson (null, $name, null);
    $pbdb->addSalary($personResult[0]['peopleid'], $effdate, $payplan, $title, $appttype, $authhours,
                     $estsalary, $estbenefits, $leave, $laf);
    $salaryResult = $pbdb->getEffectiveSalary($personResult[0]['peopleid'], str_replace('01/01', '01/02', $effdate));
    $this->assertEquals($payplan, $salaryResult[0]['payplan'], 
                        "$payplan does not match " . $salaryResult[0]['payplan']);

# updateSalary ($salaryid, $peopleid, $effectivedate, $payplan, $title, $appttype, $authhours,

    # Test getEffectiveSalary
    $salaryResult = $pbdb->getEffectiveSalary($personResult[0]['peopleid'], str_replace('01/01', '01/02', $effdate));
    $this->assertEquals($payplan, $salaryResult[0]['payplan']);

    # delete salary
    $salaryResult = $pbdb->getSalary($personResult[0]['peopleid']);
    foreach ($salaryResult as $salary) {
      $pbdb->deleteSalary ($salary['salaryid']);
    }
    $salaryResult = $pbdb->getSalary($personResult[0]['peopleid']);
    $this->assertEquals(0, count($salaryResult));

    # Delete test people records
    $personResult = $pbdb->getPerson (null, $name, null);
    # Delete person (or extra people if tests were run w/o successful deletes
    foreach ($personResult as $person) {
      $pbdb->deletePerson ($person['peopleid']);
    }

    # Test that everyone was deleted
    $personResult = $pbdb->getPerson (null, $name, null);
    $this->assertEquals(0, count($personResult));
  }

  function conferenceTestData () {
    $this->setUpData();

    return array (array($this->jsonData['conferences'][0]));
  }

  /**
   * @dataProvider conferenceTestData
   */
  function testConferences ($conferenceData) {
    $pbdb = new PBTables();

    # add Conference
    $pbdb->addConference ($conferenceData['meeting'], $conferenceData['location']);
    $conferenceResult = $pbdb->getConferences (null, $conferenceData['meeting'], $conferenceData['location']);
    $this->assertEquals($conferenceData['meeting'], $conferenceResult[0]['meeting'], 
                        "The addConference result doesn't match");
    # delete Conference
    $pbdb->deleteConference($conferenceResult[0]['conferenceid']);
    $conferenceResult = $pbdb->getConferences (null, $conferenceData['meeting'], $conferenceData['location']);
    $this->assertEquals(0, count($conferenceResult));
  }

# updateConference ($conferenceid, $meeting, $location) {
# deleteConference ($conferenceid) {
# ConferenceRates
# addConferenceRate ($conferenceid, $effectivedate, $perdiem, $registration, $groundtransport, $airfare) {
# updateConferenceRate ($conferencerateid, $conferenceid, $effectivedate, $perdiem,
# getConferenceRates ($conferenceid, $effectivedate) {
# deleteConferenceRate ($conferencerateid) {
# ConferenceAttendee
# addConferenceAttendee ($conferenceid, $proposalid, $peopleid, $meetingdays, $traveldays, $startdate) {
# updateConferenceAttendee ($conferenceattendeeid, $conferenceid, $proposalid, $peopleid, $meetingdays,
# getConferenceAttendees ($confereneceattendeeid, $conferenceid, $proposalid, $peopleid) {
# deleteConferenceAttendee ($conferenceattendeeid) {
}

?>nn
