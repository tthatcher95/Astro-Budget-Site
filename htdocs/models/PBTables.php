<?php

require_once (dirname(__FILE__) . '/../tools/databaseClass.php');

class PBTables {

  var $dbh;

  function __construct() {
    $this->db = new DatabasePG ();
  }

  # People
  function addPerson ($name, $username, $admin) {
    if (!isset($admin)) { $admin = 'false'; }
    if ($admin == 't') { $admin = 'true'; }
    if ($admin == 'f') { $admin = 'false'; }
    
    $query = "INSERT INTO people (name, username, admin) VALUES ('$name', '$username', $admin)";

    $this->db->query ($query);
  }

  function updatePerson ($peopleid, $name, $username, $admin) {
    if (!isset($peopleid)) { return "No people ID provided to update"; }

    if ((!isset($name)) and (!isset($username)) and !(isset($admin))) { 
      return "A name or username update must be provided"; 
    }

    $query = "UPDATE people SET ";
    if (isset($name)) {
      $query .= "name='$name'";
    }
    if (isset($name)) {
      $query .= ", ";
    }
    if (isset($username)) {
      $query .= "username='$username'";
    }
    if (isset($admin)) {
      if ($admin == 't') { $admin = 'true'; }
      else { $admin = 'false'; }
      if ((isset($name)) or (isset($username))) {
        $query .= ", ";
      }
      $query .= "admin=$admin";
    }

    $query .= " WHERE peopleid=$peopleid";

    $this->db->query($query);
  }

  function getPerson ($peopleid, $name, $username) {
    $query = 'SELECT peopleid, name, username, admin FROM people';

    $needAnd = false;
    if (isset($peopleid)) { 
      $query .= " WHERE peopleid=$peopleid"; 
      $needAnd = true;
    }
    if (isset($name)) {
      if ($needAnd) { $query .= " AND "; }
      else {
        $query .= " WHERE ";
        $needAnd = true;
      }
      $query .= "name='$name'";
    }
    if (isset($username)) {
      if ($needAnd) { $query .= " AND "; }
      else {
        $query .= " WHERE ";
        $needAnd = true;
      }
      $query .= "username='$username'";
    }
    $query .= " ORDER BY name";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deletePerson ($peopleid) {
    if (!isset($peopleid)) { return "A people ID is needed to delete a user"; }
    $query = "DELETE FROM people WHERE peopleid=$peopleid";

    $this->db->query ($query);
  }

  # Salaries
  function addSalary ($peopleid, $effectivedate, $payplan, $title, $appttype, $authhours, $estsalary, $estbenefits,
                      $leavecategory, $laf) {
    if (!isset($peopleid)) { return "A people ID is needed to add salary information"; }

    $query = "INSERT INTO salaries (peopleid, effectivedate, payplan, title, appttype, authhours, " .
             "estsalary, estbenefits, leavecategory, laf) VALUES (" .
             "$peopleid, ";

    if (!isset($effectivedate)) { $query .= "now(), "; }
    else { 
      $query .= "'" . $this->formatDate($effectivedate) . "', "; 
    }

    $query .= "'$payplan', '$title', '$appttype', $authhours, $estsalary, $estbenefits, $leavecategory, $laf)";

    $this->db->query($query);
  }

  function updateSalary ($salaryid, $peopleid, $effectivedate, $payplan, $title, $appttype, $authhours, 
                         $estsalary, $estbenefits, $leavecategory, $laf) {
    if (!isset($salaryid)) { return "A salary ID must be provided to update"; }

    if (!(isset($peopleid) or isset($effectivedate) or isset($payplan) or isset($title) or isset($appttype)
          or isset($authhours) or isset($estsalary) or isset($estbenefits) or isset($leavecategory) or isset($laf))) {
      return "At least one value must be provided to update salary information";
    }

    $needComma = false;
    $query = "UPDATE salaries SET ";
    if (isset($peopleid)) {
      $query .= "peopleid=$peopleid";
      $needComma = true;
    }
    if (isset($effectivedate)) {
      if ($needComma) { $query .= ", "; }
      $query .= "effectivedate='$effectivedate'";
      $needComma = true;
    }
    if (isset($payplan)) {
      if ($needComma) { $query .= ", "; }
      $query .= "payplan='$payplan'";
      $needComma = true;
    }
    if (isset($title)) {
      if ($needComma) { $query .= ", "; }
      $query .= "title='$title'";
      $needComma = true;
    }
    if (isset($appttype)) {
      if ($needComma) { $query .= ", "; }
      $query .= "appttype='$appttype'";
      $needComma = true;
    }
    if (isset($authhours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "authhours=$authhours";
      $needComma = true;
    }
    if (isset($estsalary)) {
      if ($needComma) { $query .= ", "; }
      $estsalary = 
      $query .= "estsalary=" . $this->getAmount($estsalary);
      $needComma = true;
    }
    if (isset($estbenefits)) {
      if ($needComma) { $query .= ", "; }
      $query .= "estbenefits=" . $this->getAmount($estbenefits);
      $needComma = true;
    }
    if (isset($leavecategory)) {
      if ($needComma) { $query .= ", "; }
      $query .= "leavecategory=$leavecategory";
      $needComma = true;
    }
    if (isset($laf)) {
      if ($needComma) { $query .= ", "; }
      $query .= "laf=$laf";
    }

    $query .= " WHERE salaryid=$salaryid";

    $this->db->query($query);
  }

  function getAmount ($money) {
    $cleanString = preg_replace('/([^0-9\.])/i', '', $money);

    error_log("Amount is $cleanString");
    return $cleanString;
    $onlyNumbersString = preg_replace('/([^0-9\.])/i', '', $money);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);
    # error_log("getAmount for $money returning " . (float) str_replace(',', '.', $removedThousendSeparator));

    return (float) $cleanString;
    # return (float) str_replace(',', '.', $removedThousendSeparator);
  }

  function getEffectiveSalary ($peopleid, $targetdate) {
    if (!isset($peopleid)) { return "No ID provided to lookup effective salary for"; }

    $query = "SELECT salaryid, peopleid, effectivedate, payplan, title, appttype, " .
             "authhours, estsalary, estbenefits, leavecategory, laf FROM salaries " .
             "WHERE peopleid=$peopleid";

    if (isset($targetdate)) {
      $query .= " AND effectivedate < '" . $this->formatDate($targetdate) . "'";
    }
    else {
      $query .= " AND effectivedate < now()";
    }

    $query .= " ORDER BY effectivedate DESC LIMIT 1";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function getSalary ($peopleid, $salaryid) {
    if (!isset($peopleid) and !isset($salaryid)) { return "No ID provided to lookup salary information for"; }
    if ($peopleid == 'new') { $peopleid=0; }
    if ($salaryid == 'new') { $salaryid=0; }

    $query = "SELECT salaryid, peopleid, effectivedate, payplan, title, appttype, " .
             "authhours, estsalary, estbenefits, leavecategory, laf FROM salaries WHERE ";
    if (isset($peopleid)) {
      $query .= "peopleid=$peopleid";
      if (isset($salaryid)) { $query .= " AND "; }
    }
    if (isset($salaryid)) { $query .= "salaryid=$salaryid"; }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deleteSalary ($salaryid) {
    if (!isset($salaryid)) { return "A salary ID must be provided to delete"; }

    $query = "DELETE FROM salaries WHERE salaryid=$salaryid";

    $this->db->query($query);
  }

#CREATE TABLE salaries (
#  salaryid SERIAL Primary Key,
#  peopleid INTEGER,
#  effectivedate TIMESTAMP,
#  payplan VARCHAR(32),
#  title VARCHAR(128),
#  appttype VARCHAR(8),
#  authhours REAL,
#  estsalary REAL,
#  estbenefits REAL,
#  leavecategory REAL,
#  laf REAL,

  # FundingPrograms
  function addFundingProgram ($programname, $agency, $pocname, $pocemail, $startdate, $enddate) {
    if (!isset($programname)) { return "The program name must be set to add a funding program"; }
    if (!isset($agency)) { return "The agency must be set to add a funding program"; }

    $query = "INSERT INTO fundingprograms (programname, agency, pocname, pocemail, startdate, enddate) " .
             "VALUES ('$programname', '$agency', '$pocname', '$pocemail', '" . $this->formatDate($startdate) . "', " .
             "'" . $this->formatDate($enddate) . "')";
             # "VALUES ('$programname', '$agency', '$pocname', '$pocemail', '" . $this->formatdate($startdate) . "', " .
             # "'" . $this->formatDate($enddate) . "')";

    $this->db->query($query);
  }

  function updateFundingProgram ($programid, $programname, $agency, $pocname, $pocemail, $startdate, $enddate) {
    if (!isset($programid)) { return "Must provide a program ID to update information"; }

    if (!(isset($programname) or isset($agency) or isset($pocname) or isset($pocemail) or 
          isset($startdate) or isset($enddate))) {
      return "At least one field must be provided to update funding program";
    }

    $needComma = false;
    $query = "UPDATE fundingprograms SET ";
    if (isset($programname)) {
      $query .= "programname='$programname'";
      $needComma = true;
    }
    if (isset($agency)) {
      if ($needComma) { $query .= ", "; }
      $query .= "agency='$agency'";
      $needComma = true;
    }
    if (isset($pocname)) {
      if ($needComma) { $query .= ", "; }
      $query .= "pocname='$pocname'";
      $needComma = true;
    }
    if (isset($pocemail)) {
      if ($needComma) { $query .= ", "; }
      $query .= "pocemail='$pocemail'";
      $needComma = true;
    }
    if (isset($startdate)) {
      if ($needComma) { $query .= ", "; }
      $query .= "startdate= '" . $this->formatDate($startdate) . "'";
      $needComma = true;
    }
    if (isset($enddate)) {
      if ($needComma) { $query .= ", "; }
      $query .= "enddate='" . $this->formatDate($enddate) . "'";
      $needComma = true;
    }

    $query .= " WHERE programid=$programid";

    $this->db->query($query);
  }

  function getFundingPrograms ($programid, $programname, $agency, $pocname, $pocemail, $targetdate) {
    $query = "SELECT programid, programname, agency, pocname, pocemail, " .
             "to_char (startdate, 'MM/DD/YYYY') as startdate, to_char(enddate, 'MM/DD/YYYY') as enddate " .
             "FROM fundingprograms";

    $needAnd = false;
    if (isset($programid)) {
      $query .= " WHERE programid=$programid";
      $needAnd = true;
    }
    if (isset($programname)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "programname='$programname'";
    }
    if (isset($agency)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "agency='$agency'";
    }
    if (isset($pocname)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "pocname='$pocname'";
    }
    if (isset($pocemail)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "pocemail='$pocemail'";
    }
    if (isset($targetdate)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "startdate > '" . $this->formatDate($targetdate) . "' AND enddate < '" .
                $this->formatDate($targetdate) . "'";
    }

    $query .= " ORDER BY programname";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['startFY'] = $this->fiscalYear($results[$r]['startdate']);
      $results[$r]['endFY'] = $this->fiscalYear($results[$r]['enddate']);
    }

    return ($results);
  }

  function deleteFundingProgram ($programid) {
    if (!isset($programid)) { return "No funding program ID specified to delete"; }

    $query = "DELETE FROM fundingprograms WHERE programid=$programid";

    $this->db->query($query);
  }

#CREATE TABLE fundingprograms (
#  programid SERIAL Primary Key,
#  programname VARCHAR(256),
#  agency VARCHAR(32),
#  pocname VARCHAR(128),
#  pocemail VARCHAR(128),
#  startdate TIMESTAMP,
#  enddate TIMESTAMP

  # Proposals
  function addProposal ($peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                        $perfperiodstart, $perfperiodend, $status) {
    if (!(isset($peopleid) and isset($projectname))) { 
      return "A PI and project name must be provided to create a proposal"; 
    }

    $query = "INSERT INTO proposals (peopleid, projectname, proposalnumber, awardnumber, " .
             "programid, perfperiodstart, perfperiodend, status) VALUES ($peopleid, '$projectname', " .
             "'$proposalnumber', '$awardnumber', $programid, ";
    if (isset($perfperiodstart)) { $query .= "'" . $this->formatDate($perfperiodstart) . "', "; }
    else { $query .= "null, "; }
    if (isset($perfperiodend)) { $query .= "'" . $this->formatDate($perfperiodend) . "', "; }
    else { $query .= "null, "; }
    $query .= " $status)";

    $this->db->query($query);

    $query = "SELECT proposalid FROM proposals WHERE peopleid=$peopleid AND projectname='$projectname' " .
             " ORDER BY proposalid DESC LIMIT 1";
    $this->db->query($query);
    $results = $this->db->getResultArray();
    return ($results[0]['proposalid']);
  }

  function updateProposal ($proposalid, $peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                        $perfperiodstart, $perfperiodend, $status) {
    if (!isset($proposalid)) { return "A proposal ID is required for an update"; }
    if (!(isset($peopleid) or isset($projectname) or isset($proposalnumber) or isset($awardnumber) or
          isset($programid) or isset($perfperiodstart) or isset($perfperiodend) or isset($status))) {
      return "No changed provided to update proposals with";
    }

    $query = "UPDATE proposals SET ";
    $needComma = false;

    if (isset($peopleid)) {
      $query .= "peopleid=$peopleid";
      $needComma = true;
    }
    if (isset($projectname)) {
      if ($needComma) { $query .= ", "; }
      $query .= "projectname='$projectname'";
      $needComma = true;
    }
    if (isset($proposalnumber)) {
      if ($needComma) { $query .= ", "; }
      $query .= "proposalnumber='$proposalnumber'";
      $needComma = true;
    }
    if (isset($awardnumber)) {
      if ($needComma) { $query .= ", "; }
      $query .= "awardnumber='$awardnumber'";
      $needComma = true;
    }
    if (isset($programid)) {
      if ($needComma) { $query .= ", "; }
      $query .= "programid=$programid";
      $needComma = true;
    }
    if (isset($perfperiodstart)) {
      if ($needComma) { $query .= ", "; }
      $query .= "perfperiodstart='" . $this->formatDate($perfperiodstart) . "'";
      $needComma = true;
    }
    if (isset($perfperiodend)) {
      if ($needComma) { $query .= ", "; }
      $query .= "perfperiodend='" . $this->formatDate($perfperiodend) . "'";
      $needComma = true;
    }
    if (isset($status)) {
      if ($needComma) { $query .= ", "; }
      $query .= "status='$status'";
      $needComma = true;
    }

    $query .= " WHERE proposalid=$proposalid";

    $this->db->query($query);
  }

  function getProposals ($proposalid, $peopleid, $programid, $awardnumber, $proposalnumber, $perfperiod, $status) {
    $query = "SELECT p.proposalid, p.projectname, p.peopleid, u.name, p.programid, f.programname, p.awardnumber, " .
             "p.proposalnumber, to_char(p.perfperiodstart, 'MM/DD/YYYY') as perfperiodstart, " .
             "to_char(p.perfperiodend, 'MM/DD/YYYY') as perfperiodend, p.status " .
             "FROM proposals p JOIN people u ON (p.peopleid=u.peopleid) " .
             "JOIN fundingprograms f ON (f.programid=p.programid)";
    $needAnd = false;

    if (isset($proposalid)) {
      $query .= " WHERE proposalid=$proposalid";
      $needAnd = true;
    }
    if (isset($peopleid)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "p.peopleid=$peopleid";
      $needAnd = true;
    }
    if (isset($programid)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "programid=$programid";
      $needAnd = true;
    }
    if (isset($awardnumber)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "awardnumber='$awardnumber'";
      $needAnd = true;
    }
    if (isset($proposalnumber)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "proposalnumber='$proposalnumber'";
      $needAnd = true;
    }
    if (isset($perfperiod)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "perfperiodstart < '" . $this->formatDate($perfperiod) . "' AND perfperiodend > '" .
                $this->formatDate($perfperiod) . "'";
      $needAnd = true;
    }
    if (isset($status)) {
      if ($needAnd) { $query .= " AND ";}
      else { $query .= " WHERE "; }
      $query .= "status=$status";
      $needAnd = true;
    }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['startFY'] = $this->fiscalYear($results[$r]['perfperiodstart']);
      $results[$r]['endFY'] = $this->fiscalYear($results[$r]['perfperiodend']);
    }

    return ($results);
  }

  function deleteProposal ($proposalid) {
    if (!isset($proposalid)) { return "A proposal ID is required to delete a proposal"; }
    $query = "DELETE FROM proposals WHERE proposalid=$proposalid";
    
    $this->db->query($query);
  }

  # CREATE TABLE proposals (
  #   proposalid SERIAL Primary Key,
  #   peopleid INTEGER,
  #   projectname VARCHAR(256),
  #   proposalnumber VARCHAR(128),
  #   awardnumber VARCHAR(128),
  #   programid INTEGER,
  #   perfperiodstart TIMESTAMP,
  #   perfperiodend TIMESTAMP,

  #                                          Table "public.funding"
#   Column   |            Type             |                          Modifiers
#   ------------+-----------------------------+-------------------------------------------------------------
#   fundingid  | integer                     | not null default nextval('funding_fundingid_seq'::regclass)
#   proposalid | integer                     |
#   fiscalyear | timestamp without time zone |
#   newfunding | real                        |
#   carryover  | real                        |

  # funding
  function addFunding ($proposalid, $fiscalyear, $newfunding, $carryover) {
    if (!isset($proposalid)) { return "The proposal ID is required to add new funding"; }

    $query = "INSERT INTO funding (proposalid, fiscalyear, newfunding, carryover) VALUES " .
             "($proposalid, '" . $this->formatDate($fiscalyear) . "', " . $this->getAmount($newfunding) . ", " .
             $this->getAmount($carryover) . ")";

    $this->db->query($query);
  }

  function updateFunding ($fundingid, $proposalid, $fiscalyear, $newfunding, $carryover) {
    if (!isset($fundingid)) { return "The funding ID is required to update funding"; }

    if (!(isset($proposalid) or isset($fiscalyear) or isset($newfunding) or isset($carryover))) {
      return "Nothing to change";
    }

    $needComma = false;
    $query = "UPDATE funding SET";
    if (isset($proposalid)) {
      $query .= " proposalid=$proposalid";
      $needComma = true;
    }
    if (isset($fiscalyear)) {
      if ($needComma) { $query .= ", "; }
      $query .= " fiscalyear='" . $this->formatDate($fiscalyear) . "'";
      $needComma = true;
    }
    if (isset($newfunding)) {
      if ($needComma) { $query .= ", "; }
      $query .= " newfunding=" . $this->getAmount($newfunding);
      $needComma = true;
    }
    if (isset($carryover)) {
      if ($needComma) { $query .= ", "; }
      $query .= " carryover=" . $this->getAmount($carryover);
      $needComma = true;
    }

    $query .= " WHERE fundingid=$fundingid";

    $this->db->query($query);
  }

  function getFunding ($fundingid, $proposalid) {
    $query = "SELECT fundingid, proposalid, to_char(fiscalyear, 'MM/DD/YYYY') as fiscalyear, " .
             "newfunding, carryover FROM funding";

    $needAnd = false;
    if (isset($fundingid)) {
      if ($fundingid == 'new') { $fundingid=0; }
      $query .= " WHERE fundingid=$fundingid";
      $needAnd = true;
    }
    if (isset($proposalid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "proposalid=$proposalid";
    }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['FY'] = $this->fiscalYear($results[$r]['fiscalyear']);
    }

    return ($results);
  }

  function deleteFunding ($fundingid) {
    if (!isset($fundingid)) { return "A funding ID must be set to delete";}

    $query = "DELETE FROM funding WHERE fundingid=$fundingid";

    $this->db->query($query);
  }

  # FBMSAccounts
  function addFBMSAccount ($accountno, $proposalid) {
    if (!(isset($accountno) and isset($proposalid))) { return "Both the account No and proposal ID are required"; }

    $query = "INSERT INTO fbmsaccounts (accountno, proposalid) VALUES ('$accountno', $proposalid)";

    $this->db->query($query);
  }
  
  function updateFBMSAccount ($fbmsid, $accountno, $proposalid) {
    if (!isset($fbmsid)) { return "No FBMS account ID specified to update"; }

    if (!(isset($accountno) or isset($proposalid))) { 
      return "The Account No or proposal ID are required to update FBMS"; 
    }

    $query = "UPDATE fbmsaccounts SET";
    if (isset($accountno)) { $query .= " accountno='$accountno'"; }
    if (isset($proposalid)) {
      if (isset($accountno)) { $query .= ", "; }
      $query .= "proposalid=$proposalid";
    }
    $query .= " WHERE fbmsid=$fbmsid";

    $this->db->query($query);
  }  
  
  function getFBMSAccounts ($fbmsid, $accountno, $proposalid) {
    $query = "SELECT fbmsid, accountno, proposalid FROM fbmsaccounts";
    $needAnd = false;
    if (isset($fbmsid)) {
      if ($fbmsid == 'new') { $fbmsid = 0; }
      $query .= " WHERE fbmsid=$fbmsid";
      $needAnd = true;
    }
    if (isset($accountno)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "accountno='$accountno'";
    }
    if (isset($proposalid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "proposalid='$proposalid'";
    }
    
    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deleteFBMSAccount ($fbmsid) {
    if (!isset($fbmsid)) { return "A FBMS ID must be provided to delete an entry"; }

    $query = "DELETE FROM fbmsaccounts WHERE fbmsid=$fbmsid";

    $this->db->query($query);
  }

  # CREATE TABLE fbmsaccounts (
  #  fbmsid SERIAL Primary Key,
  #  accountno VARCHAR(128),
  #  proposalid INTEGER,
  
  # Conferences
  function addConference ($meeting) {
    if (!isset($meeting)) { return "A meeting name must be provided"; }

    $query = "INSERT INTO conferences (meeting) VALUES ('$meeting')";

    $this->db->query($query);
  }

  function updateConference ($conferenceid, $meeting) {
    if (!isset($conferenceid)) { return "A conference ID must be provided to update conferences"; }
    if (!isset($meeting)) { return "A meeting name must be provided"; }

    $query = "UPDATE conferences SET ";
    if (isset($meeting)) { $query .= "meeting='$meeting'"; }
    $query .= " WHERE conferenceid=$conferenceid";

    $this->db->query($query);
  }

  function getConferences ($conferenceid, $meeting) {
    $query = "SELECT conferenceid, meeting FROM conferences";
    $needAnd = false;
    if (isset($conferenceid)) {
      $query .= " WHERE conferenceid=$conferenceid";
      $needAnd = true;
    }
    if (isset($meeting)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $needAnd = true;
      $query .= "meeting='$meeting'";
    }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deleteConference ($conferenceid) {
    if (!isset($conferenceid)) { return "A conference ID must be provided to delete"; }

    $query = "DELETE FROM conferences WHERE conferenceid=$conferenceid";

    $this->db->query($query);
  }

  # CREATE TABLE conferences (
  #  conferenceid SERIAL Primary Key,
  #  meeting VARCHAR(256),
  
  # ConferenceRates
  function addConferenceRate ($conferenceid, $effectivedate, $perdiem, $registration, $groundtransport, $airfare,
                              $city, $state, $country) {
    if (!isset($conferenceid)) { return "A conference ID must be provided to add new conference rates"; }

    $query = "INSERT INTO conferencerates (conferenceid, effectivedate, perdiem, registration, " .
             "groundtransport, airfare, city, state, country) VALUES ($conferenceid, ";
    if (isset($effectivedate)) { $query .= "'" . $this->formatDate($effectivedate) . "', "; }
    else { $query .= "now(), "; }
    $query .= $this->getAmount($perdiem) . ", " . $this->getAmount($registration) . ", " .
              $this->getAmount($groundtransport) . ", " . $this->getAmount($airfare) . ", '$city', '$state', '$country')";

    $this->db->query($query);
  }

  function updateConferenceRate ($conferencerateid, $conferenceid, $effectivedate, $perdiem, 
                                $registration, $groundtransport, $airfare, $city, $state, $country) {
    if (!isset($conferencerateid)) { return "A conference rate ID must be provided for an update"; }
    if (!(isset($effectivedate) or isset($perdiem) or isset($registration) 
       or isset($groundtransport) or isset ($airfare))) { return "Nothing to change in conference rate update"; }

    $query = "UPDATE conferencerates SET ";
    $needComma = false;
    if (isset($conferenceid)) {
      $query .= "conferenceid=$conferenceid";
      $needComma = true;
    }
    if (isset($effectivedate)) {
      if ($needComma) { $query .= ", "; }
      $query .= "effectivedate = '" . $this->formatDate($effectivedate) . "'";
      $needComma = true;
    }
    if (isset($perdiem)) {
      if ($needComma) { $query .= ", "; }
      $query .= "perdiem=" . $this->getAmount($perdiem);
      $needComma = true;
    }
    if (isset($registration)) {
      if ($needComma) { $query .= ", "; }
      $query .= "registration=" . $this->getAmount($registration);
      $needComma = true;
    }
    if (isset($groundtransport)) {
      if ($needComma) { $query .= ", "; }
      $query .= "groundtransport=" . $this->getAmount($groundtransport);
      $needComma = true;
    }
    if (isset($airfare)) {
      if ($needComma) { $query .= ", "; }
      $query .= "airfare=" . $this->getAmount($airfare);
      $needComma = true;
    }
    if (isset($city)) {
      if ($needComma) { $query .= ", "; }
      $query .= "city='$city'";
      $needComma = true;
    }
    if (isset($state)) {
      if ($needComma) { $query .= ", "; }
      $query .= "state='$state'";
      $needComma = true;
    }
    if (isset($country)) {
      if ($needComma) { $query .= ", "; }
      $query .= "country='$country'";
      $needComma = true;
    }

    $query .= " WHERE conferencerateid=$conferencerateid";

    $this->db->query($query);
  }

  function getConferenceRates ($conferenceid, $conferencerateid, $effectivedate) {
    if (!isset($conferenceid)) { return "A conference ID must be provided to list conference rates"; }
    if ($conferenceid == 'new') { $conferenceid=0; }
    if (!is_numeric($conferenceid)) { 
     error_log("is_numeric says false for ($conferenceid)");
     $conferenceid=0;
    }

    $query = "SELECT conferencerateid, conferenceid, effectivedate, perdiem, registration, " .
             "groundtransport, airfare, city, state, country FROM conferencerates WHERE conferenceid=$conferenceid";
    if (isset($conferencerateid)) {
      $query .= " AND conferencerateid=$conferencerateid";
    }
    if (isset($effectivedate)) { 
      $query .= " AND effectivedate < '" . $this->formatDate($effectivedate) . "'"; 
      $query .= " ORDER BY effectivedate DESC LIMIT 1";
    }
    else { $query .= " ORDER BY effectivedate DESC"; }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deleteConferenceRate ($conferencerateid) {
    if (!isset($conferenceid)) { return "A conference rate ID must be provided to delete"; }

    $query = "DELETE FROM conferencerates WHERE conferencerateid=$conferencerateid";

    $this->db->query($query);
  }

  # CREATE TABLE conferencerates (
  #  conferencerateid SERIAL Primary Key,
  #  conferenceid INTEGER,
  #  effectivedate TIMESTAMP,
  #  perdiem REAL,
  #  registration REAL,
  #  groundtransport REAL,
  #  airfare REAL,
  
  # ConferenceAttendee
  function addConferenceAttendee ($conferenceid, $proposalid, $travelers, $meetingdays, $traveldays, $startdate,
  $rentalcars) {
    if (!(isset($conferenceid) and isset($proposalid) and isset($travelers))) {
      return "Missing required information to add conference attendee";
    }

    $query = "INSERT INTO conferenceattendee (conferenceid, proposalid, travelers, meetingdays, traveldays, " .
             "startdate, rentalcars)".
             " VALUES ($conferenceid, $proposalid, $travelers, $meetingdays, $traveldays, ";
    if (isset($startdate)) { $query .= "'" . $this->formatDate($startdate) . "'"; }
    else { $query .= "now()"; }
    $query .= "$rentalcars)";

    $this->db->query($query);
  }

  function updateConferenceAttendee ($conferenceattendeeid, $conferenceid, $proposalid, $travelers, $meetingdays, 
                                    $traveldays, $startdate, $rentalcars) {
    if (!isset($conferenceattendeeid)) { return "A conference attendee ID must be provided for an update"; }
    if (!(isset($conferenceid) or isset($proposalid) or isset($travelers) or isset($meetingdays)
       or isset($traveldays) or isset($startdate))) { return "No changes provided for conference attendee update"; }

    $query = "UPDATE conferenceattendee set ";

    $needComma = false;
    if (isset($conferenceid)) {
      $query .= "conferenceid=$conferenceid";
      $needComma = true;
    }
    if (isset($proposalid)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "proposalid=$proposalid";
    }
    if (isset($travelers)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "travelers=$travelers";
    }
    if (isset($meetingdays)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "meetingdays=$meetingdays";
    }
    if (isset($traveldays)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "traveldays=$traveldays";
    }
    if (isset($startdate)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "startdate='" . $this->formatDate($startdate) . "'";
    }
    if (isset($rentalcars)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "rentalcars=$rentalcars";
    }

    $query .= " WHERE conferenceattendeeid=$conferenceattendeeid";

    $this->db->query($query);
  }
    
  function getConferenceAttendees ($conferenceattendeeid, $conferenceid, $proposalid, $travelers) {
    $query = "SELECT c.conferenceattendeeid, c.conferenceid, c.proposalid, c.travelers, c.meetingdays, " .
             "c.traveldays, to_char(c.startdate, 'MM/DD/YYYY') as startdate, c.rentalcars, m.meeting " .
             "FROM conferenceattendee c JOIN conferences m ON (c.conferenceid=m.conferenceid)";

    $needAnd = false;
    if (isset($conferenceattendeeid)) {
      if ($conferenceattendeeid == 'new') { $conferenceattendeeid=0; }
      $query .= " WHERE conferenceattendeeid=$conferenceattendeeid";
      $needAnd = true;
    }
    if (isset($conferenceid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "conferenceid=$conferenceid";
      $needAnd = true;
    }
    if (isset($proposalid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "proposalid=$proposalid";
      $needAnd = true;
    }

    $query .= " ORDER BY startdate asc";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['FY'] = $this->fiscalYear($results[$r]['startdate']);
    }

    return ($results);
  }

  function deleteConferenceAttendee ($conferenceattendeeid) {
    if (!isset($conferenceattendeeid)) { return "A conference attendee ID must be provided to delete"; }

    $query = "DELETE FROM conferenceattendee WHERE conferenceattendeeid=$conferenceattendeeid";

    $this->db->query($query);
  }
  
  # CREATE TABLE conferenceattendee (
  #  conferenceattendeeid SERIAL Primary Key,
  #  conferenceid INTEGER,
  #  proposalid INTEGER,
  #  peopleid INTEGER,
  #  meetingdays INTEGER,
  #  traveldays INTEGER,
  #  startdate TIMESTAMP,
  
  # Tasks
  function addTask ($proposalid, $taskname) {
    if (!(isset($proposalid) and isset($taskname))) { return "Both a proposal ID and a task name are required"; }

    $query = "INSERT INTO tasks (proposalid, taskname) VALUES ($proposalid, '$taskname')";

    $this->db->query($query);

    $query = "SELECT taskid from tasks WHERE proposalid=$proposalid and taskname='$taskname' " .
             " order by taskid desc limit 1";

    $this->db->query($query);
    $results = $this->db->getResultArray();
    return ($results[0]['taskid']);
  }
  
  function updateTask ($taskid, $proposalid, $taskname) {
    if (!isset($taskid)) { return "A task ID is required to update tasks"; }
    if (!(isset($proposalid) or isset($taskname))) { return "No changes provided to update tasks"; }

    $query = "UPDATE tasks SET ";
    if (isset($proposalid)) { $query .= "proposalid=$proposalid"; }
    if (isset($taskname)) {
      if (isset($proposalid)) { $query .= ", "; }
      $query .= "taskname='$taskname'";
    }

    $query .= " WHERE taskid=$taskid";

    $this->db->query($query);
  }
  
  function getTasks ($taskid, $proposalid, $taskname) {
    $query = "SELECT taskid, proposalid, taskname FROM tasks";

    $needAnd = false;
    if ((isset($taskid)) and ($taskid != 'new')) {
      $query .= " WHERE taskid=$taskid";
      $needAnd = true;
    }
    if (isset($proposalid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "proposalid=$proposalid";
      $needAnd = true;
    }
    if (isset($taskname)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "taskname='$taskname'";
      $needAnd = true;
    }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }

  function deleteTask ($taskid) {
    if (!isset($taskid)) { return "A task ID is required to delete a task"; }

    $query = "DELETE FROM tasks WHERE taskid=$taskid";
    
    $this->db->query($query);
  }

  # CREATE TABLE tasks (
  #  taskid BIGSERIAL Primary Key,
  #  proposalid INTEGER,
  #  taskname VARCHAR(1024),
  
  # Staffing
  function addStaffing ($taskid, $peopleid, $fiscalyear, $q1hours, $q2hours, $q3hours, $q4hours, $flexhours) {
    if (!(isset($taskid) and isset($peopleid) and isset($fiscalyear))) { 
      return "A task, person, and FY are required for staffing"; 
    }

    $query = "INSERT INTO staffing (taskid, peopleid, fiscalyear, q1hours, q2hours, q3hours, q4hours, flexhours) " .
             "VALUES ($taskid, $peopleid, ";
    if (isset($fiscalyear)) { $query .= "'" . $this->formatDate($fiscalyear) . "', "; }
    else { $query .= "now(), "; }
    $query .= "$q1hours, $q2hours, $q3hours, $q4hours, $flexhours)";
    
    $this->db->query($query);
  }

  function updateStaffing ($staffingid, $taskid, $peopleid, $fiscalyear, 
                          $q1hours, $q2hours, $q3hours, $q4hours, $flexhours) {
    if (!isset($staffingid)) { return "A staffing ID is required to make an update"; }
    if (!(isset($taskid) or isset($peopleid) or isset($fiscalyear) or isset($q1hours) or isset($q2hours) or
          isset($q3hours) or isset($q4hours) or isset($flexhours))) { "No change provided to update staffing"; }

    $query = "UPDATE STAFFING SET ";
    $needComma = false;

    if (isset($taskid)) {
      $query .= "taskid=$taskid";
      $needComma = true;
    }
    if (isset($peopleid)) {
      if ($needComma) { $query .= ", "; }
      $query .= "peopleid=$peopleid";
      $needComma = true;
    }
    if (isset($fiscalyear)) {
      if ($needComma) { $query .= ", "; }
      $query .= "fiscalyear='" . $this->formatDate($fiscalyear) . "'";
      $needComma = true;
    }
    if (isset($q1hours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "q1hours=$q1hours";
      $needComma = true;
    }
    if (isset($q2hours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "q2hours=$q2hours";
      $needComma = true;
    }
    if (isset($q3hours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "q3hours=$q3hours";
      $needComma = true;
    }
    if (isset($q4hours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "q4hours=$q4hours";
      $needComma = true;
    }
    if (isset($flexhours)) {
      if ($needComma) { $query .= ", "; }
      $query .= "flexhours=$flexhours";
      $needComma = true;
    }

    $query .= " WHERE staffingid=$staffingid";

    $this->db->query($query);
  }

  function getStaffing ($staffingid, $taskid, $peopleid, $fiscalyear) {
    $query = "SELECT s.staffingid, s.taskid, t.taskname, x.projectname, s.peopleid, p.name, " .
             "to_char(s.fiscalyear, 'MM/DD/YYYY') as fiscalyear, s.q1hours, s.q2hours, s.q3hours, s.q4hours, s.flexhours " .
             "FROM staffing s JOIN people p ON (s.peopleid=p.peopleid) JOIN tasks t ON (t.taskid=s.taskid) " .
             "JOIN proposals x ON (x.proposalid=t.proposalid)";
    $needAnd = false;

    if (isset($staffingid)) {
      if ($staffingid == 'new') {$staffingid = -1;}
      $query .= " WHERE s.staffingid=$staffingid ";
      $needAnd = true;
    }
    if (isset($taskid)) {
      if ($taskid == 'new') { $taskid=0; }
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "s.taskid=$taskid";
      $needAnd = true;
    }
    if (isset($peopleid)) {
      if ($peopleid == 'new') { $peopleid=-1; }
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "s.peopleid=$peopleid";
      $needAnd = true;
    }
    if (isset($fiscalyear)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "s.fiscalyear='" . $this->formatDate($fiscalyear) . "'";
      $needAnd = true;
    }

    $query .= " ORDER BY s.fiscalyear ASC";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['FY'] = $this->fiscalYear($results[$r]['fiscalyear']);
    }

    return ($results);
  }

  function deleteStaffing ($staffingid) {
    if (!isset($staffingid)) { return "A staffing ID is required for a delete"; }
    $query = "DELETE FROM staffing WHERE staffingid=$staffingid";

    $this->db->query($query);
  }

  # CREATE TABLE staffing (
  #  staffingid BIGSERIAL Primary Key,
  #  taskid BIGINT,
  #  peopleid INTEGER,
  #  fiscalyear VARCHAR(4),
  #  q1hours REAL,
  #  q2hours REAL,
  #  q3hours REAL,
  #  q4hours REAL,
  #  flexhours REAL,
  
  # ExpenseTypes
  function addExpenseType ($description) {
    if (!isset($description)) { return "No description provided to add expense type"; }

    $query = "INSERT INTO expensetypes (description) VALUES ('$description')";

    $this->db->query($query);
  }

  function updateExpenseType ($expensetypeid, $description) {
    if (!(isset($expensetypeid) and isset($description))) { return "No ID or change provided to update expense types"; }
    
    $query = "UPDATE expensetypes SET description='$description' WHERE expensetypeid=$expensetypeid";
    
    $this->db->query($query);
  }
  
  function getExpenseTypes ($expensetypeid, $description) {
    $query = "SELECT expensetypeid, description FROM expensetypes";

    if (isset($expensetypeid)) { $query .= " WHERE expensetypeid=$expensetypeid"; }

    if (isset($description)) {
      if (isset($expensetypeid)) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "description='$description'";
    }

    $this->db->query($query);
    $results = $this->db->getResultArray();

    return ($results);
  }
  
  function deleteExpenseType ($expensetypeid) {
    if (!isset($expensetypeid)) { return "No expense type ID provided to delete"; }

    $query = "DELETE FROM expensetypes WHERE expensetypeid=$expensetypeid";

    $this->db->query($query);
  }

  # CREATE TABLE expensetypes (
  #  expensetypeid SERIAL Primary Key,
  #  description VARCHAR(256)

  # Expenses
  function addExpense ($proposalid, $expensetypeid, $description, $amount, $fiscalyear) {
    if (!(isset($proposalid) and isset($expensetypeid) and isset($amount))) {
      return "Missing required fields to add a new expense";
    }

    $query = "INSERT INTO expenses (proposalid, expensetypeid, description, amount, fiscalyear) VALUES " .
             "($proposalid, $expensetypeid, '$description', " . $this->getAmount($amount) . ", '" . 
              $this->formatDate($fiscalyear) . "')";

    $this->db->query($query);
  }

  function updateExpense ($expenseid, $proposalid, $expensetypeid, $description, $amount, $fiscalyear) {
    if (!isset($expenseid)) { return "An expense ID is required to update expenses"; }
    if (!(isset($proposalid) or isset($expensetypeid) or isset($description) or isset($amount) or isset($fiscalyear))) {
      return "No changes provided to update expenses with";
    }

    $query = "UPDATE expenses SET ";
    $needComma = false;
    if (isset($proposalid)) {
      $query .= "proposalid=$proposalid";
      $needComma = true;
    }
    if (isset($expensetypeid)) {
      if ($needComma) { $query .= ", "; }
      $query .= "expensetypeid=$expensetypeid";
      $needComma = true;
    }
    if (isset($description)) {
      if ($needComma) { $query .= ", "; }
      $query .= "description='$description'";
      $needComma = true;
    }
    if (isset($amount)) {
      if ($needComma) { $query .= ", "; }
      $query .= "amount=" . $this->getAmount($amount);
      $needComma = true;
    }
    if (isset($fiscalyear)) {
      if ($needComma) { $query .= ", "; }
      $query .= "fiscalyear='" . $this->formatDate($fiscalyear) . "'";
      $needComma = true;
    }

    $query .= " WHERE expenseid=$expenseid";
    
    $this->db->query($query);
  }

  function getExpenses ($expenseid, $proposalid, $expensetypeid, $fiscalyear) {
    if ($expenseid == 'new') { $expenseid=0; }
    $query = "SELECT e.expenseid, e.proposalid, e.expensetypeid, t.description as type, e.description, " .
             "e.amount, to_char(e.fiscalyear, 'MM/DD/YYYY') as fiscalyear FROM expenses e JOIN expensetypes t ON (t.expensetypeid=e.expensetypeid)";

    $needAnd = false;
    if (isset($expenseid)) {
      $query .= " WHERE expenseid=$expenseid";
      $needAnd = true;
    }
    if (isset($proposalid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "proposalid=$proposalid";
      $needAnd = true;
    }
    if (isset($expensetypeid)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "expensetypeid=$expensetypeid";
      $needAnd = true;
    }
    if (isset($fiscalyear)) {
      if ($needAnd) { $query .= " AND "; }
      else { $query .= " WHERE "; }
      $query .= "fiscalyear='" . $this->formatDate($fiscalyear) . "'";
      $needAnd = true;
    }

    $query .= " ORDER BY fiscalyear ASC";

    $this->db->query($query);
    $results = $this->db->getResultArray();

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['FY'] = $this->fiscalYear($results[$r]['fiscalyear']);
    }

    return ($results);
  }

  function deleteExpense ($expenseid) {
    if (!isset($expenseid)) { return "No expense ID provided to delete"; }

    $query = "DELETE FROM expenses WHERE expenseid=$expenseid";

    $this->db->query($query);
  }

  function addOverheadrate ($proposalid, $rate, $description, $effectivedate) {
    if (!(isset($rate) and isset($effectivedate))) {
      return "Missing required information to add overhead rate";
    }

    $query = "INSERT INTO overheadrates (proposalid, rate, description, effectivedate) " .
             " VALUES ($proposalid, $rate, '$description', '" . $this->formatDate($effectivedate) . "')"; 

    $this->db->query($query);
  }

  function updateOverheadrate ($overheadid, $proposalid, $rate, $description, $effectivedate) {
    if (!isset($overheadid)) { return "An overhead rate ID must be provided for an update"; }
    if (!(isset($rate) or isset($proposalid) or isset($description) or isset($effectivedate))) { 
      return "No changes provided for overhead rate update"; 
    }

    $query = "UPDATE overheadrates set ";

    $needComma = false;
    if (isset($proposalid)) {
      $query .= "proposalid=$proposalid";
      $needComma = true;
    }
    if (isset($rate)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "rate=$rate";
    }
    if (isset($description)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "description='$description'";
    }
    if (isset($effectivedate)) {
      if ($needComma) { $query .= ", "; }
      $needComma = true;
      $query .= "effectivedate='" . $this->formatDate($effectivedate) . "'";
    }

    $query .= " WHERE overheadid=$overheadid";

    $this->db->query($query);
  }
    
  function getOverheadrates ($proposalid, $overheadid, $targetdate) {
    $query = "SELECT overheadid, proposalid, rate, description, to_char(effectivedate, 'MM/DD/YYYY') as effectivedate" .
             " FROM overheadrates";

    $results = array();

    # If $proposalid isset, search first with that $proposalid, if no results, do the search again 
    # where the proposalid is null (default rate for everything) and return that instead.
    if (isset($proposalid)) {
      $proposalquery = $query . " WHERE proposalid=$proposalid order by effectivedate desc";
      $this->db->query($proposalquery);
      $results = $this->db->getResultArray();
    }

    if (count($results) < 1) {
      $query .= " WHERE proposalid is null order by effectivedate desc";
      $this->db->query($query);
      $results = $this->db->getResultArray();
    }

    for ($r = 0; $r < count($results); $r++) {
      $results[$r]['FY'] = $this->fiscalYear($results[$r]['effectivedate']);
    }

    return ($results);
  }

  function formatDate ($effectivedate) {
    $newtime = strtotime($effectivedate);
    if ($newtime) {
      $effectivedate = date('Y-m-d H:i:s', $newtime);
    }

    return $effectivedate;
  }

  function fiscalYear ($date) {
    $newtime = strtotime($date);
    $month = intval(date('m', $newtime));
    $year  = intval(date('y', $newtime));

    if ($month > 9) {
      $year = $year + 1;
    }
    
    return "FY$year";
  }
}
  
?>
