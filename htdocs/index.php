<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  array ('caption' => 'Home', 'href' => 'index.php'),
  array ('caption' => 'Proposals', 'href' => 'index.php?view=proposals'),
  array ('caption' => 'People', 'href' => 'index.php?view=people'),
  array ('caption' => 'Conferences/Travel', 'href' => 'index.php?view=conferences'),
  array ('caption' => 'Expenses', 'href' => 'index.php?view=expensetypes'),
  array ('caption' => 'Programs', 'href' => 'index.php?view=programs')));

$templateArgs['remote_user'] = $pbdb->getPerson(null, null, $_SERVER['REMOTE_USER']);

# Handle GET options
if (isset($_REQUEST['view'])) {
  switch($_REQUEST['view']) {
    case 'people':
      $templateArgs = peopleView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'people-edit':
      $templateArgs = peopleView($pbdb, $templateArgs);
      $templateArgs['view'] = 'people-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'people-list-json':
      $templateArgs = peopleView($pbdb, $templateArgs);
      $templateArgs['view'] = 'people-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'people-save':
      $templateArgs = peopleSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'salary-list-json':
      if (isset($_REQUEST['peopleid'])) {
        $templateArgs = salaryView($pbdb, $templateArgs, $_REQUEST['peopleid']);
      }
      $view = $templateArgs['view'];
      break;
    case 'salary-save':
      $templateArgs = salarySave($pbdb, $templateArgs, $_REQUEST['salaryid'], $_REQUEST['peopleid'],
        $_REQUEST['effectivedate'], $_REQUEST['payplan'], $_REQUEST['title'], $_REQUEST['appttype'],
        $_REQUEST['authhours'], $_REQUEST['estsalary'], $_REQUEST['estbenefits'], $_REQUEST['leavecategory'],
        $_REQUEST['laf']);
      $view = $templateArgs['view'];
      break;
    case 'proposals':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposal-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'proposal-edit':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = programsView($pbdb, $templateArgs); # for dropdown
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'proposal-save':
      break;
    case 'programs':
      $templateArgs = programsView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'program-edit':
      $templateArgs = programsView($pbdb, $templateArgs);
      $templateArgs['view'] = 'programs-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'programs-list-json':
      $templateArgs = programsView($pbdb, $templateArgs);
      $templateArgs['view'] = 'programs-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'program-save':
      $templateArgs = programSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'conferences':
      $templateArgs = conferenceView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'conference-edit':
      $templateArgs = conferenceView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'conference-save':
      $templateArgs = conferenceSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'conferences-list-json':
      $templateArgs = conferenceView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conferences-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'conference-attendee-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-attendee-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'expensetypes':
      $templateArgs = expensetypesView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'expensetype-edit':
      $templateArgs = expensetypesView($pbdb, $templateArgs);
      $templateArgs['view'] = 'expensetype-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'expensetypes-list-json':
      $templateArgs = expensetypesView($pbdb, $templateArgs);
      $templateArgs['view'] = 'expensetypes-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'expensetype-save':
      $templateArgs = expensetypesSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'tasks-list-json':
      $templateArgs = tasksView($pbdb, $templateArgs);
      $templateArgs['view'] = 'tasks-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'expense-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'expense-list-ajax.json';
      $view = $templateArgs['view'];
      break;
  }
}

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem ('/var/www/budgetprops-dev/htdocs/views');
$twig = new Twig_Environment ($loader, 
  array ('cache' => '/var/www/budgetprops-dev/htdocs/views/cache',
         'auto_reload' => true));

$template = $twig->loadTemplate($view);

if (isset($_REQUEST['debug'])) {
  $varsDump = print_r ($templateArgs, true);
  echo "<pre>$varsDump</pre>\n";
}

echo $template->render($templateArgs);

function peopleView ($pbdb, $templateArgs) {
  $peopleid = null;

  if (isset($_REQUEST['peopleid'])) { $peopleid = $_REQUEST['peopleid']; }

  if ($peopleid == 'new') {
    $templateArgs['people'] = array ( array ('peopleid' => 'new', 'name' => '', 'username' => '', 'admin' => 'f'));
    return ($templateArgs);
  }

  $templateArgs['people'] = $pbdb->getPerson ($peopleid, null, null);
  for ($i = 0; $i < count($templateArgs['people']); $i++) {
    $salaryResults = $pbdb->getEffectiveSalary ($templateArgs['people'][$i]['peopleid'],
                                                $date = date('m/d/Y', time()));
    if (isset($salaryResults[0])) {
      $templateArgs['people'][$i]['payplan'] = $salaryResults[0]['payplan'];
      $templateArgs['people'][$i]['title'] = $salaryResults[0]['title'];
    }
  }

  $templateArgs['view'] = 'people.html';

  return ($templateArgs);
}

function peopleSave ($pbdb, $templateArgs) {
  if (!isset($_REQUEST['peopleid'])) { 
    $templateArgs['debug'] = array ("Missing person ID to create or update people");
    return ($templateArgs);
  }

  $peopleid = $_REQUEST['peopleid'];
  $name     = (isset($_REQUEST['name'])? $_REQUEST['name'] : null);
  $username = (isset($_REQUEST['username'])? $_REQUEST['username'] : null);
  $admin    = (isset($_REQUEST['admin'])? $_REQUEST['admin'] : null);

  if ($peopleid == 'new') {
    $pbdb->addPerson ($name, $username, $admin);
  }
  else {
    $pbdb->updatePerson ($peopleid, $name, $username, $admin);
  }

  $templateArgs['peopleid'] = $peopleid;
  $templateArgs['name'] = $name;
  $templateArgs['username'] = $username;
  $templateArgs['admin'] = $admin;
  $templateArgs['view'] = 'people-save-result.html';

  return ($templateArgs);
}

function salaryView ($pbdb, $templateArgs, $peopleid) {
  $templateArgs['salaries'] = $pbdb->getSalary($peopleid);
  $templateArgs['view'] = 'salary-list-ajax.json';

  return ($templateArgs);
}

function salarySave ($pbdb, $templateArgs) {
  $salaryid = null;
  if (isset($_REQUEST['salaryid'])) { $salaryid = $_REQUEST['salaryid']; }
  else { 
    $templateArgs['debug'] = array ('Missing salary ID');
    return ($templateArgs);
  }
  $peopleid = null;
  if (isset($_REQUEST['peopleid'])) { $peopleid = $_REQUEST['peopleid']; }
  else { 
    $templateArgs['debug'] = array ('Missing person ID');
    return ($templateArgs);
  }

  if ($salaryid == 'new') {
    $pbdb->addSalary($peopleid, $_REQUEST['effectivedate'], $_REQUEST['payplan'], $_REQUEST['title'],
                     $_REQUEST['appttype'], $_REQUEST['authhours'], $_REQUEST['estsalary'], $_REQUEST['estbenefits'],
                     $_REQUEST['leavecategory'], $_REQUEST['laf']);
  }
  else {
    $pbdb->updateSalary($salaryid, $peopleid, $_REQUEST['effectivedate'], $_REQUEST['payplan'], 
                        $_REQUEST['title'], $_REQUEST['appttype'], $_REQUEST['authhours'], $_REQUEST['estsalary'], 
                        $_REQUEST['estbenefits'], $_REQUEST['leavecategory'], $_REQUEST['laf']);
  }

  $templateArgs['view'] = 'salary-save-result.html';

  return ($templateArgs);
}

function proposalView ($pbdb, $templateArgs) {
  $peopleid = null;
  $proposalid = null;
  if (isset($_REQUEST['proposalid'])) { 
    $proposalid = $_REQUEST['proposalid']; 
    $templateArgs['view'] = 'proposals.html';
  }
  else {
    $templateArgs['view'] = 'proposals.html';
    if ($templateArgs['remote_user'][0]['admin'] != 't') { 
      $peopleid = $templateArgs['remote_user'][0]['peopleid']; 
    }
  }

  $templateArgs['proposals'] = $pbdb->getProposals ($proposalid, $peopleid, null, null, null, null);

  # Add in the tasks, FBMS accounts, conferences/attendees, and expenses too
  for ($i = 0; $i < count($templateArgs['proposals']); $i++) {
    $proposalid = $templateArgs['proposals'][$i]['proposalid'];
    $templateArgs['proposals'][$i]['FBMSaccounts'] = $pbdb->getFBMSAccounts (null, null, $proposalid);
    $templateArgs['proposals'][$i]['conferenceattendees'] = $pbdb->getConferenceAttendees (null, null, $proposalid, null);
    $templateArgs['proposals'][$i]['tasks'] = $pbdb->getTasks (null, $proposalid, null);
    $templateArgs['proposals'][$i]['expenses'] = $pbdb->getExpenses (null, $proposalid, null, null);
  }

  return ($templateArgs);
}

function proposalSave ($pbdb, $templateArgs) {
  $proposalid = null;
  if (isset($_REQUEST['proposalid'])) {
    $templateArgs['debug'] = array ("Missing proposal ID to create or update proposals");
    return ($templateArgs);
  }

  $peopleid        = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  $projectname     = (isset($_REQUEST['projectname'])? $_REQUEST['projectname'] : null);
  $proposalnumber  = (isset($_REQUEST['proposalnumber'])? $_REQUEST['proposalnumber'] : null);
  $awardnumber     = (isset($_REQUEST['awardnumber'])? $_REQUEST['awardnumber'] : null);
  $programid       = (isset($_REQUEST['programid'])? $_REQUEST['programid'] : null);
  $perfperiodstart = (isset($_REQUEST['perfperiodstart'])? $_REQUEST['perfperiodstart'] : null);
  $perfperiodend   = (isset($_REQUEST['perfperiodend'])? $_REQUEST['perfperiodend'] : null);

  if ($proposalid == 'new') {
    $pbdb->addProposal ($peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                        $perfperiodstart, $perfperiodend);
  }
  else {
    $pbdb->updateProposal ($proposalid, $peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                           $perfperiodstart, $perfperiodend);
  }

  $templateArgs['view'] = 'proposal-save-results.html';
  $templateArgs['proposalid']      = $proposalid;
  $templateArgs['peopleid']        = $peopleid;
  $templateArgs['projectname']     = $projectname;
  $templateArgs['proposalnumber']  = $proposalnumber;
  $templateArgs['awardnumber']     = $awardnumber;
  $templateArgs['programid']       = $programid;
  $templateArgs['perfperiodstart'] = $perfperiodstart;
  $templateArgs['perfperiodend']   = $perfperiodend;

  return ($templateArgs);
}

function programsView ($pbdb, $templateArgs) {
  $programid = null;
  if (isset($_REQUEST['programid'])) { $programid = $_REQUEST['programid']; }
  $templateArgs['programid'] = $programid;
  if ($programid == 'new') { $programid = 0; }

  $templateArgs['programs'] = $pbdb->getFundingPrograms ($programid, null, null, null, null, null);

  $templateArgs['view'] = 'programs.html';

  return ($templateArgs);
}

function programSave ($pbdb, $templateArgs) {
  if (!isset($_REQUEST['programid'])) { 
    $templateArgs['debug'] = array ("Missing program ID to create or update programs");
    return ($templateArgs);
  }

  $programid   = $_REQUEST['programid'];
  $programname = (isset($_REQUEST['programname'])? $_REQUEST['programname'] : null);
  $agency      = (isset($_REQUEST['agency'])? $_REQUEST['agency'] : null);
  $pocname     = (isset($_REQUEST['pocname'])? $_REQUEST['pocname'] : null);
  $pocemail    = (isset($_REQUEST['pocemail'])? $_REQUEST['pocemail'] : null);
  $startdate   = (isset($_REQUEST['startdate'])? $_REQUEST['startdate'] : null);
  $enddate     = (isset($_REQUEST['enddate'])? $_REQUEST['enddate'] : null);

  if ($programid == 'new') {
    $pbdb->addFundingProgram ($programname, $agency, $pocname, $pocemail, $startdate, $enddate);
  }
  else {
    $pbdb->updateFundingProgram ($programid, $programname, $agency, $pocname, $pocemail, $startdate, $enddate);
  }

  $templateArgs['programid'] = $programid;
  $templateArgs['programname'] = $programname;
  $templateArgs['agency'] = $agency;
  $templateArgs['view'] = 'program-save-result.html';

  return ($templateArgs);
}

function conferenceView ($pbdb, $templateArgs) {
  $conferenceid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  if ($conferenceid == 'new') { $conferenceid = 0; }

  $meeting = null;
  if (isset($_REQUEST['meeting'])) { $meeting = $_REQUEST['meeting']; }

  $templateArgs['conferences'] = $pbdb->getConferences ($conferenceid, $meeting, null);

  $templateArgs['view'] = 'conferences.html';

  return ($templateArgs);
}

function conferenceSave ($pbdb, $templateArgs) {
  if (!isset($_REQUEST['conferenceid'])) {
    $templateArgs['debug'] = array ('Missing conference ID to create or update conferences');
    return ($templateArgs);
  }
  $conferenceid = $_REQUEST['conferenceid'];

  $meeting  = (isset($_REQUEST['meeting'])? $_REQUEST['meeting'] : null);

  if ($conferenceid == 'new') {
    $pbdb->addConference ($meeting);
  }
  else {
    $pbdb->updateConference ($conferenceid, $meeting);
  }

  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['meeting'] = $meeting;
  $templateArgs['view'] = 'conference-save-result.html';

  return ($templateArgs);
}

function conferenceRatesView ($pbdb, $templateArgs) {
  $conferenceid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  if ($conferenceid == 'new') { $conferenceid = 0; }
  $effectivedate = (isset($_REQUEST['effectivedate'])? $_REQUEST['effectivedate'] : null);
  $templateArgs['conferencerates'] = $pbdb->getConferenceRates ($conferenceid, $effectivedate);

  $templateArgs['view'] = 'conferencerates.html';

  return ($templateArgs);
}

function conferenceRateSave ($pbdb, $templateArgs) {
  $conferenceid     = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  $conferencerateid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  $effectivedate    = (isset($_REQUEST['effectivedate'])? $_REQUEST['effectivedate'] : null);
  $perdiem          = (isset($_REQUEST['perdiem'])? $_REQUEST['perdiem'] : null);
  $registration     = (isset($_REQUEST['registration'])? $_REQUEST['registration'] : null);
  $groundtransport  = (isset($_REQUEST['groundtransport'])? $_REQUEST['groundtransport'] : null);
  $airfare          = (isset($_REQUEST['airfare'])? $_REQUEST['airfare'] : null);

  if ($conferencerateid == 'new') {
    $pbdb->addConferenceRate ($conferenceid, $effectivedate, $perdiem, $registration, $groundtransport, $airfare);
  }
  else {
    $pbdb->updateConferenceRate ($conferencerateid, $conferenceid, $effectivedate, $perdiem, $registration,
                                 $groundtransport, $airfare);
  }

  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['conferencerateid'] = $conferencerateid;
  $templateArgs['effectivedate'] = $effectivedate;
  $templateArgs['perdiem'] = $perdiem;
  $templateArgs['registration'] = $registration;
  $templateArgs['groundtransport'] = $groundtransport;
  $templateArgs['airfare'] = $airfare;

  $templateArgs['view'] = 'conferencerate-save-result.html';

  return ($templateArgs);
}

function conferenceAttendeeSave ($pbdb, $templateArgs) {
  $conferenceattendeeid = (isset($_REQUEST['conferenceattendeeid'])? $_REQUEST['conferenceattendeeid'] : null);

  $conferenceid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  $proposalid   = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $peopleid     = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  $meetingdays  = (isset($_REQUEST['meetingdays'])? $_REQUEST['meetingdays'] : null);
  $traveldays   = (isset($_REQUEST['traveldays'])? $_REQUEST['traveldays'] : null);
  $startdate    = (isset($_REQUEST['startdate'])? $_REQUEST['startdate'] : null);

  if ($conferenceattendeeid == 'new') {
    $pbdb->addConferenceAttendee ($conferenceid, $proposalid, $peopleid, $meetingdays, $traveldays, $startdate);
  }
  else {
    $pbdb->updateConferenceAttendee ($conferenceattendeeid, $conferenceid, $proposalid, $peopleid, $meetingdays,
                                     $traveldays, $startdate);
  }

  $templateArgs['conferenceattendeeid'] = $conferenceattendeeid;
  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['peopleid'] = $peopleid;
  $templateArgs['meetingdays'] = $meetingdays;
  $templateArgs['traveldays'] = $traveldays;
  $templateArgs['startdate'] = $startdate;

  $templateArgs['view'] = 'conferenceattendee-save-result.html';

  return ($templateArgs);
}

function tasksView ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $taskname   = (isset($_REQUEST['taskname'])? $_REQUEST['taskname'] : null);

  $templateArgs['tasks'] = $pbdb->getTasks ($taskid, $proposalid, $taskname);
  for ($i = 0; $i < count($templateArgs['tasks']); $i++) {
    $templateArgs['tasks'][$i]['staffing'] = $pbdb->getStaffing(null, $templateArgs['tasks'][$i]['taskid'],
                                                                null, null);

    error_log("Task $i has " . count($templateArgs['tasks'][$i]['staffing']) . " staff", 0);
    for ($j = 0; $j < count($templateArgs['tasks'][$i]['staffing']); $j++) {
      error_log("Peopleid " . $templateArgs['tasks'][$i]['staffing'][$j]['peopleid'], 0);
      $templateArgs['tasks'][$i]['staffing'][$j]['salary'] = 
        $pbdb->getEffectiveSalary ($templateArgs['tasks'][$i]['staffing'][$j]['peopleid'], 
          $date = date('m/d/Y', time()));
    }
  }

  $templateArgs['view'] = 'tasks.html'; # TBD? probably will just be part of overall proposal view or JSON

  return ($templateArgs);
}

function taskSave ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $taskname   = (isset($_REQUEST['taskname'])? $_REQUEST['taskname'] : null);

  if ($taskid == 'new') {
    $pbdb->addTask ($proposalid, $taskname);
  }
  else {
    $pbdb->updateTask ($taskid, $proposalid, $taskname);
  }

  $templateArgs['taskid'] = $taskid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['taskname'] = $taskname;

  $templateArgs['view'] = 'task-save-result.html';

  return ($templateArgs);
}

function expensetypesView ($pbdb, $templateArgs) {
  $expensetypeid = (isset($_REQUEST['expensetypeid'])? $_REQUEST['expensetypeid'] : null);
  $description   = (isset($_REQUEST['description'])? $_REQUEST['description'] : null);

  if ($expensetypeid == 'new') { $expensetypeid = 0; }

  $templateArgs['expensetypes'] = $pbdb->getExpenseTypes ($expensetypeid, $description);

  $templateArgs['view'] = 'expensetypes.html';

  return ($templateArgs);
}

function expensetypesSave ($pbdb, $templateArgs) {
  $expensetypeid = (isset($_REQUEST['expensetypeid'])? $_REQUEST['expensetypeid'] : null);
  $description   = (isset($_REQUEST['description'])? $_REQUEST['description'] : null);
  
  if ($expensetypeid == 'new') {
    $pbdb->addExpenseType($description);
  }
  else {
    $pbdb->updateExpenseType($expensetypeid, $description);
  }

  $templateArgs['expensetypeid'] = $expensetypeid;
  $templateArgs['description']   = $description;

  $templateArgs['view'] = 'expensetype-save-result.html';

  return ($templateArgs);
}

?>
