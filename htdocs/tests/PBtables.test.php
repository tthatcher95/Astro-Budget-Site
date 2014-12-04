<?php

require_once(dirname(__FILE__) . '/../models/PBTables.php');

class PBTablesTest extends PHPUnit_Framework_TestCase {

  function testPeople () {
    $name = 'Zippy D. Pinhead';
    $username = 'zdp000';

    $pbdb = new PBTables ();

    # print "Adding user ...\n";
    $pbdb->addPerson ($name, $username);
    # print "Listing matches ...\n";
    $personResult = $pbdb->getPerson (null, $name, $username);
    print_r ($personResult);
#  function updatePerson ($peopleid, $name, $username) {
    foreach ($personResult as $person) {
      print "Deleting ID: " . $person['peopleid'] . " " . $person['name'] . " - " . $person['username'] . "\n";
      $pbdb->deletePerson ($person['peopleid']);
    }

    $personResult = $pbdb->getPerson (null, $name, $username);
    print "After deletes ... " . count($personResult) . "\n";
  }
}

?>
