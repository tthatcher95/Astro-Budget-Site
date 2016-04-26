<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  # array ('caption' => 'Home', 'href' => 'index.php'),
  array ('caption' => 'Projects', 'href' => 'index.php?view=proposals'),
  array ('caption' => 'People', 'href' => 'index.php?view=people'),
  array ('caption' => 'Conferences/Travel', 'href' => 'index.php?view=conferences'),
  array ('caption' => 'Expense Categories', 'href' => 'index.php?view=expensetypes'),
  array ('caption' => 'Programs', 'href' => 'index.php?view=programs')));
$templateArgs['statuscodes'] = array ('Notional', 'Submitted', 'Selected', 'Rejected', 'Active', 'Completed', 'Scratch');

$templateArgs['remote_user'] = $pbdb->getPerson(null, null, $_SERVER['REMOTE_USER']);

# Handle GET options
$viewSwitch = 'proposals';
if (isset($_REQUEST['view'])) { $viewSwitch = $_REQUEST['view']; }

if (true) {
  # switch($_REQUEST['view']) {
  switch($viewSwitch) {
    case 'budget-graphs':
      $templateArgs['view'] = 'budget-graphs.html';
      $view = $templateArgs['view'];
      break;
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
    case 'people-task-list-json':
      $templateArgs = peopleStaffingView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'salary-list-json':
      $templateArgs = salaryView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'salary-edit-json':
      $templateArgs = salaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'salary-edit-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'salary-save':
      $templateArgs = salarySave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposals':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = programsView($pbdb, $templateArgs); # for dropdown
      $templateArgs = proposalView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposal-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'proposal-copy':
      $templateArgs = proposalCopy($pbdb, $templateArgs);
    case 'proposal-edit':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = programsView($pbdb, $templateArgs); # for dropdown
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'proposal-save':
      $templateArgs = proposalSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposal-delete':
      $templateArgs = proposalDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposal-nspires':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = programsView($pbdb, $templateArgs); # for dropdown
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-nspires.html';
      $view = $templateArgs['view'];
      break;
    case 'proposal-budget-details':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = programsView($pbdb, $templateArgs); # for dropdown
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-budget-details.html';
      $view = $templateArgs['view'];
      break;
    case 'fbms-list-json':
      $templateArgs = fbmsView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'fbms-edit':
      $templateArgs = fbmsView($pbdb, $templateArgs);
      $templateArgs['view'] = 'fbms-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'fbms-save':
      $templateArgs = fbmsSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'fbms-delete':
      $templateArgs = fbmsDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'overhead-list-json':
      $templateArgs = overheadView($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'overhead-edit':
      $templateArgs = overheadView($pbdb, $templateArgs);
      $templateArgs['view'] = 'overhead-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'overhead-save':
      $templateArgs = overheadSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
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
    case 'conference-rate-edit-json':
      $templateArgs = conferenceRatesView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-rate-edit-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'conference-rate-list-json':
      $templateArgs = conferenceRatesView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-rate-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'conference-rate-save':
      $templateArgs = conferenceRateSave($pbdb, $templateArgs);
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
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-attendee-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'conference-attendee-edit':
      $templateArgs = conferenceView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'conference-attendee-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'conference-attendee-save':
      $templateArgs = conferenceAttendeeSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'conference-attendee-delete':
      $templateArgs = conferenceAttendeeDelete($pbdb, $templateArgs);
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
    case 'funding-edit':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'funding-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'funding-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'funding-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'funding-save':
      $templateArgs = fundingSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'funding-delete':
      $templateArgs = fundingDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'tasks-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = tasksView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'tasks-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'tasks-list-csv':
      $startdate = '10/01/2016';
      $enddate = '09/30/2017';
      $statuses = array(0, 1, 2, 4);
      $templateArgs['csv'] = $pbdb->getCsvTasks ($startdate, $enddate, $statuses);
      serveCsv ($templateArgs);
      return;
      break;
    case 'task-edit':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = tasksView($pbdb, $templateArgs);
      $templateArgs['view'] = 'task-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'task-save':
      $templateArgs = peopleView($pbdb, $templateArgs);   # for dropdown
      $templateArgs = tasksView($pbdb, $templateArgs);
      $templateArgs = taskSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'task-delete':
      $templateArgs = taskDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'staffing-edit-json':
      $templateArgs = staffingView($pbdb, $templateArgs);
      $templateArgs['view'] = 'staffing-edit-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'staffing-list-json':
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = tasksView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'staffing-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'staffing-save':
      $templateArgs = staffingSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'staffing-delete':
      $templateArgs = staffingDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'expense-list-json':
      $templateArgs = expensetypesView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'expense-list-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'expense-edit':
      $templateArgs = expensetypesView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs['view'] = 'expense-edit.html';
      $view = $templateArgs['view'];
      break;
    case 'expense-save':
      $templateArgs = expenseSave($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'expense-delete':
      $templateArgs = expenseDelete($pbdb, $templateArgs);
      $view = $templateArgs['view'];
      break;
    case 'proposal-cost-titles-json':
      $templateArgs = overheadView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-cost-titles-ajax.json';
      $view = $templateArgs['view'];
      break;
    case 'proposal-costs-json':
      $templateArgs = overheadView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-costs-ajax.json';
      $view = $templateArgs['view'];
  }
}

# $basepath = '/var/www/html/budgets/budget-proposals/htdocs';
$basepath = '/var/www/budgetprops-dev/htdocs/dev/htdocs';

Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem ($basepath . '/views'); 
$twig = new Twig_Environment ($loader, 
  array ('cache' => $basepath . '/views/cache',
         'auto_reload' => true));

$template = $twig->loadTemplate($view);

if (isset($_REQUEST['debug'])) {
  $varsDump = print_r ($templateArgs, true);
  echo "<pre>$varsDump</pre>\n";
}

echo $template->render($templateArgs);

function  serveCsv ($templateArgs) {
  header("Content-type: text/csv");
  header("Content-Disposition: attachment; filename=tasks.csv");
  header("Pragma: no-cache");
  header("Expires: 0");

  echo $templateArgs['csv'];

  return;
}

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

function salaryView ($pbdb, $templateArgs) {
  $peopleid = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  $salaryid = (isset($_REQUEST['salaryid'])? $_REQUEST['salaryid'] : null);
  $templateArgs['salaries'] = $pbdb->getSalary($peopleid, $salaryid);
  $templateArgs['view'] = 'salary-list-ajax.json';

  return ($templateArgs);
}

function salarySave ($pbdb, $templateArgs) {
  $templateArgs['view'] = 'salary-save-result.html';

  $salaryid = (isset($_REQUEST['salaryid'])? $_REQUEST['salaryid'] : null);
  if ($salaryid == null) { 
    error_log('salarySave: missing salary ID');
    $templateArgs['debug'] = array ('Missing salary ID');
    return ($templateArgs);
  }
  $peopleid = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  if ($peopleid == null) { 
    error_log('salarySave: missing people ID');
    $templateArgs['debug'] = array ('Missing person ID');
    return ($templateArgs);
  }
  $effectivedate = (isset($_REQUEST['effdate'])? $_REQUEST['effdate'] : null);
  $payplan       = (isset($_REQUEST['payplan'])? $_REQUEST['payplan'] : null);
  $title         = (isset($_REQUEST['title'])? $_REQUEST['title'] : null);
  $appttype      = (isset($_REQUEST['appttype'])? $_REQUEST['appttype'] : null);
  $authhours     = (isset($_REQUEST['authhours'])? $_REQUEST['authhours'] : null);
  $estsalary     = (isset($_REQUEST['estsalary'])? $_REQUEST['estsalary'] : null);
  $estbenefits   = (isset($_REQUEST['estbenefits'])? $_REQUEST['estbenefits'] : null);
  $leavecategory = (isset($_REQUEST['leavecategory'])? $_REQUEST['leavecategory'] : null);
  $laf           = (isset($_REQUEST['laf'])? $_REQUEST['laf'] : null);

  if ($salaryid == 'new') {
    $pbdb->addSalary($peopleid, $effectivedate, $payplan, $title, $appttype, $authhours, $estsalary, 
                     $estbenefits, $leavecategory, $laf);
  }
  else {
    $pbdb->updateSalary($salaryid, $peopleid, $effectivedate, $payplan, $title, $appttype, $authhours, $estsalary, 
                        $estbenefits, $leavecategory, $laf);
  }

  $templateArgs['peopleid'] = $peopleid;
  $templateArgs['salaryid'] = $salaryid;
  $templateArgs['payplan'] = $payplan;
  $templateArgs['title'] = $title;

  return ($templateArgs);
}

function proposalView ($pbdb, $templateArgs) {
  $peopleid   = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  if (isset($_REQUEST['proposalid'])) { 
    $templateArgs['view'] = 'proposals.html';
  }
  else {
    $templateArgs['view'] = 'proposals.html';
    if ($templateArgs['remote_user'][0]['admin'] != 't') { 
      $peopleid = $templateArgs['remote_user'][0]['peopleid']; 
    }
  }
  $expenseid = (isset($_REQUEST['expenseid'])? $_REQUEST['expenseid'] : null);

  $templateArgs['proposals'] = $pbdb->getProposals ($proposalid, $peopleid, null, null, null, null, null);
  $fundingid  = (isset($_REQUEST['fundingid'])? $_REQUEST['fundingid'] : null);

  $conferenceattendeeid = (isset($_REQUEST['conferenceattendeeid'])? $_REQUEST['conferenceattendeeid'] : null);
  # Add in the tasks, FBMS accounts, conferences/attendees, and expenses too
  for ($i = 0; $i < count($templateArgs['proposals']); $i++) {
    $proposalid = $templateArgs['proposals'][$i]['proposalid'];
    $templateArgs['proposals'][$i]['FBMSaccounts'] = $pbdb->getFBMSAccounts (null, null, $proposalid);
    $templateArgs['proposals'][$i]['funding'] = $pbdb->getFunding ($fundingid, $proposalid);
    $templateArgs['proposals'][$i]['conferenceattendees'] = $pbdb->getConferenceAttendees ($conferenceattendeeid, null, $proposalid, null);
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['conferenceattendees']); $j++) {
      $templateArgs['debug'] = 'looking up conference rate for ' .
        $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferenceid'] . " " .
                                   $templateArgs['proposals'][$i]['conferenceattendees'][$j]['startdate'];
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'] = 
        $pbdb->getConferenceRates ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferenceid'],
                                   null,
                                   $templateArgs['proposals'][$i]['conferenceattendees'][$j]['startdate']);
    }
    $taskid = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
    $templateArgs['proposals'][$i]['tasks'] = $pbdb->getTasks ($taskid, $proposalid, null);
    $templateArgs['proposals'][$i]['expenses'] = $pbdb->getExpenses ($expenseid, $proposalid, null, null);
  }

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['fundingid']  = (isset($_REQUEST['fundingid'])? $_REQUEST['fundingid'] : 'new');

  return ($templateArgs);
}

function peopleCompare ($a, $b) {
  if ($a['name'] === $b['name']) { return 0; }

  return ($a['name'] < $b['name']) ? -1: 1;
}

function costsSummaryView ($pbdb, $templateArgs) {
  error_reporting( error_reporting() & ~E_NOTICE );
  setlocale(LC_MONETARY, 'en_US');
  $templateArgs['costs'] = array ();
  $templateArgs['budgets'] = array ();
  $fiscalyears = array ();
  for ($i = 0; $i < count($templateArgs['proposals']); $i++) {
    $templateArgs['costs'][$i] = array ();
    $templateArgs['budgets'][$i] = array ();
    $totals = array();
    $overhead = array();
    $subtotals = array();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['tasks']); $j++) {
      $templateArgs['proposals'][$i]['tasks'][$j]['staffing'] = $pbdb->getStaffing(null, 
        $templateArgs['proposals'][$i]['tasks'][$j]['taskid'], null, null);

      $templateArgs['proposals'][$i]['tasks'][$j]['people'] = array();

      for ($x = 0; $x < count($templateArgs['proposals'][$i]['tasks'][$j]['staffing']); $x++) {
        $peopleid = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['peopleid'];
        $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['salary'] = 
          $pbdb->getEffectiveSalary ($peopleid, 
            $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['fiscalyear']);
        $templateArgs['proposals'][$i]['people'][$peopleid]['name'] = 
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['name'];
        
      }
      $templateArgs['proposals'][$i]['tasks'][$j]['stafflist'] = array();
      $templateArgs['proposals'][$i]['tasks'][$j]['fylist'] = array();
      for ($k = 0; $k < count($templateArgs['proposals'][$i]['tasks'][$j]['staffing']); $k++) {
        $currOver = getOverhead ($pbdb, $templateArgs,
                      $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['fiscalyear']);
        $currFy = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['FY'];
        array_push ($fiscalyears, $currFy);
        $peopleid = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['peopleid'];
        $name = $templateArgs['proposals'][$i]['people'][$peopleid]['name'];

        $taskhours = 
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q1hours'] +
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q2hours'] +
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q3hours'] +
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q4hours'] +
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['flexhours'];
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['hours'] += $taskhours;
        $laf = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['laf'];
        $lafhours = round($taskhours * $laf);
        $estsalary = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['estsalary'];
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['estsalary'] = $estsalary;
        $estbenefits = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['estbenefits'];
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['estbenefits'] = $estbenefits;
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['authhours'] =
          $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['authhours'];
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['taskhours'] += $taskhours;
        $staffcosts = ($estsalary * $lafhours) + ($estbenefits * $lafhours);
        $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['staffcosts'] = $staffcosts;
        array_push($templateArgs['proposals'][$i]['tasks'][$j]['stafflist'], $name);
        $templateArgs['proposals'][$i]['tasks'][$j]['taskhourlist'] += $taskhours;
        $templateArgs['proposals'][$i]['tasks'][$j]['tasktotalcost'] += $staffcosts;
        array_push($templateArgs['proposals'][$i]['tasks'][$j]['fylist'], $currFy);
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['staffcosts'] += $staffcosts;
        $currSalary = $taskhours * $estsalary;
        $currBenefits = $staffcosts - $currSalary;
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['salaryreqcosts'] += $currSalary;
        $templateArgs['proposals'][$i]['people'][$peopleid]['ALL']['salaryreqcosts'] += $currSalary;
        $templateArgs['proposals'][$i]['people'][$peopleid][$currFy]['benefitsreqcosts'] += $currBenefits;
        $templateArgs['proposals'][$i]['people'][$peopleid]['ALL']['benefitsreqcosts'] += $currBenefits;
        $templateArgs['proposals'][$i]['peopletotals'][$currFy]['total'] += $currSalary + $currBenefits;
        $templateArgs['proposals'][$i]['peopletotals']['ALL']['total'] += $currSalary + $currBenefits;
        $templateArgs['proposals'][$i]['people'][$peopleid]['ALL']['hours'] += $taskhours;
        $templateArgs['proposals'][$i]['people'][$peopleid]['ALL']['estsalary'] += $estsalary * $lafhours;
        $templateArgs['proposals'][$i]['people'][$peopleid]['ALL']['estbenefits'] += $estbenefits * $lafhours;
          
        $cost = $staffcosts;
        $subtotals[$currFy] += $cost;
        $overhead[$currFy] += $cost * ($currOver / (100 - $currOver));
        $totals[$currFy] += $cost + ($cost * ($currOver / (100 - $currOver)));
        $templateArgs['budgets'][$i]['FY'][$currFy]['fy'] = $currFy;
        $templateArgs['budgets'][$i]['FY'][$currFy]['staffing'] += $cost;
        $templateArgs['budgets'][$i]['FY']['ALL']['staffing'] += $cost;
        $templateArgs['budgets'][$i]['ALL']['total'] += $cost;
        $templateArgs['budgets'][$i]['FY'][$currFy]['overhead'] += $cost * ($currOver / (100 - $currOver));
        $templateArgs['budgets'][$i]['FY']['ALL']['overhead'] += $cost * ($currOver / (100 - $currOver));
      }
      $stafflist = array_unique($templateArgs['proposals'][$i]['tasks'][$j]['stafflist']);
      sort($stafflist);
      $templateArgs['proposals'][$i]['tasks'][$j]['stafflist'] = join (' / ', $stafflist);
      $fylist = array_unique($templateArgs['proposals'][$i]['tasks'][$j]['fylist']);
      sort($fylist);
      $templateArgs['proposals'][$i]['tasks'][$j]['fylist'] = join(', ', $fylist);
    }

    usort ($templateArgs['proposals'][$i]['people'], "peopleCompare");
  
    $subtotal = 0;
    $templateArgs['costs'][$i]['staffing'] = "Tasks ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $subcost) {
      $templateArgs['costs'][$i]['staffing'] .= " - $fy " . money_format('%.2n', $subcost);
      $subtotal += $subcost;
    }
    
    $templateArgs['costs'][$i]['staffing'] .= " Total " . money_format('%.2n', $subtotal);
    $total += $subtotal;
    $subtotal = 0;
    $subtotals = array ();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['conferenceattendees']); $j++) {
      $currFy = $templateArgs['proposals'][$i]['conferenceattendees'][$j]['FY'];
      array_push ($fiscalyears, $currFy);
      $perdiem = ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
                 ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['meetingdays'] +
                 ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['traveldays'] * 0.75)) *
                 $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['perdiem']);
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['perdiemcosts'] = $perdiem;
      $lodging = ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
                  $templateArgs['proposals'][$i]['conferenceattendees'][$j]['meetingdays'] *
                  $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['lodging']);
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['lodgingcosts'] = $lodging;
      $groundtransport = 
        ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['groundtransport'] *
        $templateArgs['proposals'][$i]['conferenceattendees'][$j]['rentalcars']);
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['groundtransportcosts'] =
        $groundtransport;
      $airfare = ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
        $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['airfare']);
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['airfarecosts'] = $airfare;
      $registration = ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
        $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['registration']);
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['registrationcosts'] =
        $registration;
      $cost = $perdiem + $lodging + $groundtransport + $airfare + $registration;
      $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['totalcost'] = $cost;

      $meeting = $templateArgs['proposals'][$i]['conferenceattendees'][$j]['meeting'];
      if (strcasecmp($templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['country'],
          'USA') == 0) { $traveltype = 'D1'; }
      else { $traveltype = 'D2'; }
      $templateArgs['proposals'][$i]['conferences'][$meeting]['meeting'] = $meeting;
      $templateArgs['proposals'][$i]['conferences'][$meeting]['section'] = $traveltype;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['perdiem'] += $perdiem;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['lodging'] += $lodging;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['registration'] += $registration;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['groundtransport'] += $groundtransport;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['airfare'] += $airfare;
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['travelers'] +=
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['traveldays'] +=
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['traveldays'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['meetingdays'] +=
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['meetingdays'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['city'] =
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['city'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['state'] =
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['state'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['country'] =
         $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['country'];
      $templateArgs['proposals'][$i]['conferences'][$meeting][$currFy]['total'] += $cost;
      $templateArgs['proposals'][$i]['conferencetotals'][$traveltype][$currFy] += 
        $perdiem + $lodging + $registration + $groundtransport + $airfare;
      $templateArgs['proposals'][$i]['conferencetotals'][$traveltype]['ALL'] += 
        $perdiem + $lodging + $registration + $groundtransport + $airfare;

      $currOver = getOverhead ($pbdb, $templateArgs,
                    $templateArgs['proposals'][$i]['conferenceattendees'][$j]['startdate']);
      $subtotals[$currFy] += $cost;
      $overhead[$currFy] += $cost * ($currOver / (100 - $currOver));
      $totals[$currFy] += $cost + ($cost * ($currOver / (100 - $currOver)));
      $templateArgs['budgets'][$i]['FY'][$currFy]['fy'] = $currFy;
      $templateArgs['budgets'][$i]['FY'][$currFy]['travel'] += $cost;
      $templateArgs['budgets'][$i]['FY']['ALL']['travel'] += $cost;
      $templateArgs['budgets'][$i]['ALL']['total'] += $cost;
      $templateArgs['budgets'][$i]['FY'][$currFy]['overhead'] += $cost * ($currOver / (100 - $currOver));
      $templateArgs['budgets'][$i]['FY']['ALL']['overhead'] += $cost * ($currOver / (100 - $currOver));
    }
    $templateArgs['costs'][$i]['conferences'] = "Conferences/Training/Meetings ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $cost) {
      $templateArgs['costs'][$i]['conferences'] .= " - $fy " . money_format('%.2n', $cost);
      $subtotal += $cost;
    }
    
    $templateArgs['costs'][$i]['conferences'] .= " - Total " . money_format('%.2n', $subtotal);
    $total += $subtotal;
    $subtotal = 0;
    $subtotals = array();
    $equipmentlist = array();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['expenses']); $j++) {
      $currFy = $templateArgs['proposals'][$i]['expenses'][$j]['FY'];
      array_push ($fiscalyears, $currFy);
      $currOver = getOverhead ($pbdb, $templateArgs,
                    $templateArgs['proposals'][$i]['expenses'][$j]['fiscalyear']);
      $cost = $templateArgs['proposals'][$i]['expenses'][$j]['amount'];
      $subtotals[$currFy] += $cost;
      $templateArgs['budgets'][$i]['FY'][$currFy]['fy'] = $currFy;
      $templateArgs['budgets'][$i]['FY'][$currFy]['expenses'] += $cost;
      $expensetype = $templateArgs['proposals'][$i]['expenses'][$j]['type'];
      if ($expensetype == 'Section C Equipment') {
        $description = $templateArgs['proposals'][$i]['expenses'][$j]['description'];
        array_push ($equipmentlist, $description);
        $templateArgs['budgets'][$i]['FY'][$currFy]['equipment'][$description] += $cost;
        $templateArgs['budgets'][$i]['FY'][$currFy]['equipmenttotal'] += $cost;
      }
      else {
        $templateArgs['budgets'][$i]['ALL']['expensestotal'] += $cost;
        $templateArgs['budgets'][$i]['FY'][$currFy]['expensestotal'] += $cost;
      }
      $templateArgs['budgets'][$i]['FY'][$currFy][$expensetype] += $cost;
      $templateArgs['budgets'][$i]['ALL'][$expensetype] += $cost;
      $templateArgs['budgets'][$i]['FY'][$currFy]['total'] += $cost;
      $templateArgs['budgets'][$i]['ALL']['total'] += $cost;
      if ($expensetype != 'Directed Funded Contracts (no USGS overhead)') {
        # No overhead for these expenses
        $totals[$currFy] += $cost;
        $overhead[$currFy] += $cost * ($currOver / (100 - $currOver));
        $templateArgs['budgets'][$i]['FY'][$currFy]['overhead'] += $cost * ($currOver / (100 - $currOver));
        $templateArgs['budgets'][$i]['FY']['ALL']['overhead'] += $cost * ($currOver / (100 - $currOver));
      }
      else {
        $totals[$currFy] += $cost + ($cost * ($currOver / (100 - $currOver)));
      }
    }
    $templateArgs['budgets'][$i]['equipmentlist'] = array_unique($equipmentlist);
    ksort ($templateArgs['budgets'][$i]['equipmentlist']);
    $templateArgs['costs'][$i]['expenses'] = "Expenses ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $cost) {
      $templateArgs['costs'][$i]['expenses'] .= " - $fy " . money_format('%.2n', $cost);
      $subtotal += $cost;
    }
    
    $templateArgs['costs'][$i]['expenses'] .= " - Totals " . money_format('%.2n', $subtotal);
    $total += $subtotal;
    $templateArgs['costs'][$i]['proposal'] = "Proposal Details - " . 
                                             $templateArgs['proposals'][$i]['projectname'];
    $templateArgs['budgets'][$i]['projectname'] = $templateArgs['proposals'][$i]['projectname'];
    $templateArgs['budgets'][$i]['status'] = $templateArgs['proposals'][$i]['status'];
    $subtotal = 0;
    $templateArgs['costs'][$i]['funding'] = "Funding";
    ksort($totals);
    foreach ($totals as $fy => $cost) {
      $templateArgs['costs'][$i]['proposal'] .= " - $fy " . money_format('%.2n', $cost);
      $subtotal += $cost;

      $fyfunding = 0;
      foreach ($templateArgs['proposals'][$i]['funding'] as $funds) {
        if ($funds['FY'] == $fy) {
          $fyfunding += $funds['newfunding'] + $funds['carryover'];
        }
      }
      $templateArgs['costs'][$i]['funding'] .= " - $fy ";
      if ($fyfunding < $cost) {
       # do nothing for now, need to style heading to be red if underfunded
      }
      $templateArgs['costs'][$i]['funding'] .= money_format('%.2n', $fyfunding);
      $templateArgs['budgets'][$i]['FY'][$fy]['funding'] += $fyfunding;
        
    }
    $templateArgs['costs'][$i]['proposal'] .= " - Totals " . money_format('%.2n', $subtotal);

    # Simple over-ride for now
    $templateArgs['costs'][$i]['proposal'] = "Proposal Details - " .  $templateArgs['proposals'][$i]['projectname'];

    $templateArgs['costs'][$i]['overhead'] = "Overhead ";
    ksort($overhead);
    $subtotal = 0;
    foreach ($overhead as $fy => $cost) {
      $templateArgs['costs'][$i]['overhead'] .= " - $fy " . money_format('%.2n', $cost);
      $subtotal += $cost;
    }
    $templateArgs['costs'][$i]['overhead'] .= " - Total " . money_format('%.2n', $subtotal);
    $templateArgs['budgets'][$i]['FYs'] = array_unique($fiscalyears);
    sort ($templateArgs['budgets'][$i]['FYs']);

    $templateArgs['costs'][$i]['overhead'] = "Overhead ";
    foreach ($templateArgs['budgets'][$i]['FYs'] as $budgetFy) {
      $templateArgs['costs'][$i]['overhead'] .= " - $budgetFy " .
        money_format('%.2n', $templateArgs['budgets'][$i]['FY'][$budgetFy]['overhead']);
    }
    $templateArgs['costs'][$i]['overhead'] .= " - Total " .
        money_format('%.2n', $templateArgs['budgets'][$i]['FY']['ALL']['overhead']);
  }
  
  return ($templateArgs);
}

function proposalSave ($pbdb, $templateArgs) {
  $proposalid      = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $peopleid        = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);
  $projectname     = (isset($_REQUEST['projectname'])? $_REQUEST['projectname'] : null);
  $proposalnumber  = (isset($_REQUEST['proposalnumber'])? $_REQUEST['proposalnumber'] : null);
  $awardnumber     = (isset($_REQUEST['awardnumber'])? $_REQUEST['awardnumber'] : null);
  $programid       = (isset($_REQUEST['programid'])? $_REQUEST['programid'] : null);
  $perfperiodstart = (isset($_REQUEST['perfperiodstart'])? $_REQUEST['perfperiodstart'] : null);
  $perfperiodend   = (isset($_REQUEST['perfperiodend'])? $_REQUEST['perfperiodend'] : null);
  $status          = (isset($_REQUEST['status'])? $_REQUEST['status'] : null);

  if ($proposalid == 'new') {
    $proposalid = $pbdb->addProposal ($peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                                      $perfperiodstart, $perfperiodend, $status);
  }
  else {
    $pbdb->updateProposal ($proposalid, $peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
                           $perfperiodstart, $perfperiodend, $status);
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
  $templateArgs['status']          = $status;

  return ($templateArgs);
}

function proposalCopy ($pbdb, $templateArgs) {
  $oldproposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);

  if ($oldproposalid == null) {
    return ($templateArgs);
  }

  $proposalid = $pbdb->copyProposal ($oldproposalid);

  $_REQUEST['proposalid'] = $proposalid;
  $templateArgs['proposalid'] = $proposalid;

  return ($templateArgs);
}

function proposalDelete ($pbdb, $templateArgs) {
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);

  if ($proposalid == null) {
    return ($templateArgs);
  }

  $pbdb->deleteProposal ($proposalid);

  $templateArgs['deleteid'] = $proposalid;
  $templateArgs['view'] = 'delete-result.html';
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

function fundingSave($pbdb, $templateArgs) {
  $fundingid  = (isset($_REQUEST['fundingid'])? $_REQUEST['fundingid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $fiscalyear = (isset($_REQUEST['fiscalyear'])? $_REQUEST['fiscalyear'] : null);
  $newfunding = (isset($_REQUEST['newfunding'])? $_REQUEST['newfunding'] : null);
  $carryover  = (isset($_REQUEST['carryover'])? $_REQUEST['carryover'] : null);

  if ($fundingid == 'new') {
    $pbdb->addFunding ($proposalid, $fiscalyear, $newfunding, $carryover);
  }
  else {
    $pbdb->updateFunding ($fundingid, $proposalid, $fiscalyear, $newfunding, $carryover);
  }

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['fundingid'] = $fundingid;
  $templateArgs['view'] = 'funding-save-result.html';

  return ($templateArgs);
}

function fundingDelete ($pbdb, $templateArgs) {
  $proposalid    = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $fundingid     = (isset($_REQUEST['fundingid'])? $_REQUEST['fundingid'] : null);

  if ($fundingid != null) {
    $pbdb->deleteFunding($fundingid);
  }

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['fundingid'] = $fundingid;
  $templateArgs['deleteid'] = $fundingid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function fbmsView ($pbdb, $templateArgs) {
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $fbmsid     = (isset($_REQUEST['fbmsid'])? $_REQUEST['fbmsid'] : null);

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['fbmsid']     = $fbmsid;

  $templateArgs['fbms'] = $pbdb->getFBMSAccounts ($fbmsid, null, $proposalid);
  $templateArgs['view'] = 'fbms-list-ajax.json';

  return ($templateArgs);
}

function fbmsSave ($pbdb, $templateArgs) {
  $fbmsid     = (isset($_REQUEST['fbmsid'])? $_REQUEST['fbmsid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $accountno  = (isset($_REQUEST['accountno'])? $_REQUEST['accountno'] : null);

  if ($fbmsid == 'new') {
    $pbdb->addFBMSAccount ($accountno, $proposalid);
  }
  else {
    $pbdb->updateFBMSAccount ($fbmsid, $accountno, $proposalid);
  }

  $templateArgs['fbmsid'] = $fbmsid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['accountno'] = $accountno;
  $templateArgs['view'] = 'fbms-save-result.html';

  return ($templateArgs);
}

function fbmsDelete ($pbdb, $templateArgs) {
  $fbmsid     = (isset($_REQUEST['fbmsid'])? $_REQUEST['fbmsid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  
  if ($fbmsid != null) {
    $pbdb->deleteFBMSAccount ($fbmsid);
  }

  $templateArgs['fbmsid'] = $fbmsid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['deleteid'] = $fbmsid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function overheadView ($pbdb, $templateArgs) {
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $overheadid = (isset($_REQUEST['overheadid'])? $_REQUEST['overheadid'] : null);
  $effectivedate = (isset($_REQUEST['effectivedate'])? $_REQUEST['effectivedate'] : null);

  $templateArgs['proposalid']    = $proposalid;
  $templateArgs['overheadid']    = $overheadid;
  $templateArgs['effectivedate'] = $effectivedate;

  $templateArgs['overheadrates'] = $pbdb->getOverheadrates ($proposalid, $overheadid, $effectivedate);
  $templateArgs['view'] = 'overhead-list-ajax.json';

  return ($templateArgs);
}

function conferenceView ($pbdb, $templateArgs) {
  $conferenceid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  if ($conferenceid == 'new') { $conferenceid = 0; }

  $meeting = null;
  if (isset($_REQUEST['meeting'])) { $meeting = $_REQUEST['meeting']; }

  $templateArgs['conferences'] = $pbdb->getConferences ($conferenceid, $meeting, null);
  $effectivedate = date('m/d/Y');
  for ($i=0; $i < count($templateArgs['conferences']); $i++) {
    $templateArgs['conferences'][$i]['conferencerates'] = 
      $pbdb->getConferenceRates ($templateArgs['conferences'][$i]['conferenceid'], null, $effectivedate);
  }

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
  $conferencerateid = (isset($_REQUEST['conferencerateid'])? $_REQUEST['conferencerateid'] : null);
  if ($conferencerateid == 'new') { $conferencerateid = 0; }
  $effectivedate = (isset($_REQUEST['effectivedate'])? $_REQUEST['effectivedate'] : null);
  $templateArgs['conferencerates'] = $pbdb->getConferenceRates ($conferenceid, $conferencerateid, $effectivedate);

  $templateArgs['view'] = 'conferencerates.html';

  return ($templateArgs);
}

function conferenceRateSave ($pbdb, $templateArgs) {
  $conferenceid     = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  $conferencerateid = (isset($_REQUEST['conferencerateid'])? $_REQUEST['conferencerateid'] : null);
  $effectivedate    = (isset($_REQUEST['effectivedate'])? $_REQUEST['effectivedate'] : null);
  $perdiem          = (isset($_REQUEST['perdiem'])? $_REQUEST['perdiem'] : null);
  $registration     = (isset($_REQUEST['registration'])? $_REQUEST['registration'] : null);
  $groundtransport  = (isset($_REQUEST['groundtransport'])? $_REQUEST['groundtransport'] : null);
  $airfare          = (isset($_REQUEST['airfare'])? $_REQUEST['airfare'] : null);
  $lodging          = (isset($_REQUEST['lodging'])? $_REQUEST['lodging'] : null);
  $city             = (isset($_REQUEST['city'])? $_REQUEST['city'] : null);
  $state            = (isset($_REQUEST['state'])? $_REQUEST['state'] : null);
  $country          = (isset($_REQUEST['country'])? $_REQUEST['country'] : null);

  if ($conferencerateid == 'new') {
    $pbdb->addConferenceRate ($conferenceid, $effectivedate, $perdiem, $registration, $groundtransport, $airfare, 
                              $lodging, $city, $state, $country);
  }
  else {
    $pbdb->updateConferenceRate ($conferencerateid, $conferenceid, $effectivedate, $perdiem, $registration,
                                 $groundtransport, $airfare, $lodging, $city, $state, $country);
  }

  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['conferencerateid'] = $conferencerateid;
  $templateArgs['effectivedate'] = $effectivedate;
  $templateArgs['perdiem'] = $perdiem;
  $templateArgs['registration'] = $registration;
  $templateArgs['groundtransport'] = $groundtransport;
  $templateArgs['airfare'] = $airfare;

  $templateArgs['view'] = 'conference-rate-save-result.html';

  return ($templateArgs);
}

function conferenceAttendeeSave ($pbdb, $templateArgs) {
  $conferenceattendeeid = (isset($_REQUEST['conferenceattendeeid'])? $_REQUEST['conferenceattendeeid'] : null);

  $conferenceid = (isset($_REQUEST['conferenceid'])? $_REQUEST['conferenceid'] : null);
  $proposalid   = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $travelers    = (isset($_REQUEST['travelers'])? $_REQUEST['travelers'] : null);
  $meetingdays  = (isset($_REQUEST['meetingdays'])? $_REQUEST['meetingdays'] : null);
  $traveldays   = (isset($_REQUEST['traveldays'])? $_REQUEST['traveldays'] : null);
  $startdate    = (isset($_REQUEST['startdate'])? $_REQUEST['startdate'] : null);
  $rentalcars   = (isset($_REQUEST['rentalcars'])? $_REQUEST['rentalcars'] : null);

  if ($conferenceattendeeid == 'new') {
    $pbdb->addConferenceAttendee ($conferenceid, $proposalid, $travelers, $meetingdays, $traveldays, $startdate,
      $rentalcars);
  }
  else {
    $pbdb->updateConferenceAttendee ($conferenceattendeeid, $conferenceid, $proposalid, $travelers, $meetingdays,
                                     $traveldays, $startdate, $rentalcars);
  }

  $templateArgs['conferenceattendeeid'] = $conferenceattendeeid;
  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['travelers'] = $travelers;
  $templateArgs['meetingdays'] = $meetingdays;
  $templateArgs['traveldays'] = $traveldays;
  $templateArgs['startdate'] = $startdate;
  $templateArgs['rentalcars'] = $rentalcars;

  $templateArgs['view'] = 'conference-attendee-save-result.html';

  return ($templateArgs);
}

function conferenceAttendeeDelete ($pbdb, $templateArgs) {
  $conferenceattendeeid = (isset($_REQUEST['conferenceattendeeid'])? $_REQUEST['conferenceattendeeid'] : null);
  $proposalid   = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);

  if ($conferenceattendeeid != null) {
    $pbdb->deleteConferenceAttendee ($conferenceattendeeid);
  }

  $templateArgs['conferenceattendeeid'] = $conferenceattendeeid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['deleteid'] = $conferenceattendeeid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function tasksView ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $taskname   = (isset($_REQUEST['taskname'])? $_REQUEST['taskname'] : null);
  $peopleid   = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);

  if (isset($templateArgs['taskid'])) {
    $taskid = $templateArgs['taskid'];
  }

  $templateArgs['tasks'] = $pbdb->getTasks ($taskid, $proposalid, $taskname);
  for ($i = 0; $i < count($templateArgs['tasks']); $i++) {
    $templateArgs['tasks'][$i]['staffing'] = $pbdb->getStaffing(null, $templateArgs['tasks'][$i]['taskid'],
                                                                $peopleid, null);

    for ($j = 0; $j < count($templateArgs['tasks'][$i]['staffing']); $j++) {
      $templateArgs['tasks'][$i]['staffing'][$j]['salary'] = 
        $pbdb->getEffectiveSalary ($templateArgs['tasks'][$i]['staffing'][$j]['peopleid'], 
          $date = date('m/d/Y', time()));
    }
  }

  $templateArgs['view'] = 'tasks.html'; # TBD? probably will just be part of overall proposal view or JSON
  $templateArgs['proposalid'] = $proposalid;

  return ($templateArgs);
}

function taskSave ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $taskname   = (isset($_REQUEST['taskname'])? $_REQUEST['taskname'] : null);
  
  if ($taskid == 'new') {
    $taskid = $pbdb->addTask ($proposalid, $taskname);
  }
  else {
    $pbdb->updateTask ($taskid, $proposalid, $taskname);
  }

  $templateArgs['taskid']     = $taskid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['taskname']   = $taskname;

  $templateArgs['view'] = 'task-result-ajax.json';

  return ($templateArgs);
}

function taskDelete ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);

  # TBD - need to loop through staffing and delete it first
  $staffing = $pbdb->getStaffing(null, $taskid, null, null);

  for ($i = 0; $i < count($staffing); $i++) {
    $pbdb->deleteStaffing($staffing[$i]['staffingid']);
  }

  if ($taskid != null) {
    $pbdb->deleteTask($taskid);
  }

  $templateArgs['taskid'] = $taskid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['deleteid'] = $taskid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function staffingView ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $staffingid = (isset($_REQUEST['staffingid'])? $_REQUEST['staffingid'] : null);

  $templateArgs['staffing'] = $pbdb->getStaffing($staffingid, $taskid, null, null);
  $templateArgs['view'] = 'staffing-edit-ajax.json';

  return ($templateArgs);
}

function staffingSave ($pbdb, $templateArgs) {
  $staffingid = (isset($_REQUEST['staffingid'])? $_REQUEST['staffingid'] : null);
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $peopleid   = (isset($_REQUEST['staffingpeopleid'])? $_REQUEST['staffingpeopleid'] : null);
  $fiscalyear = (isset($_REQUEST['fiscalyear'])? $_REQUEST['fiscalyear'] : null);
  $q1hours    = (isset($_REQUEST['q1hours'])? $_REQUEST['q1hours'] : null);
  $q2hours    = (isset($_REQUEST['q2hours'])? $_REQUEST['q2hours'] : null);
  $q3hours    = (isset($_REQUEST['q3hours'])? $_REQUEST['q3hours'] : null);
  $q4hours    = (isset($_REQUEST['q4hours'])? $_REQUEST['q4hours'] : null);
  $flexhours  = (isset($_REQUEST['flexhours'])? $_REQUEST['flexhours'] : null);

  if ($staffingid == 'new') {
    $pbdb->addStaffing($taskid, $peopleid, $fiscalyear, $q1hours, $q2hours, $q3hours, $q4hours, $flexhours);
  }
  else {
    $pbdb->updateStaffing($staffingid, $taskid, $peopleid, $fiscalyear, $q1hours, $q2hours, $q3hours, $q4hours, 
                          $flexhours);
  }

  $templateArgs['staffingid'] = $staffingid;
  $templateArgs['taskid']     = $taskid;
  $templateArgs['peopleid']   = $peopleid;
  $templateArgs['fiscalyear'] = $fiscalyear;
  $templateArgs['q1hours']    = $q1hours;
  $templateArgs['q2hours']    = $q2hours;
  $templateArgs['q3hours']    = $q3hours;
  $templateArgs['q4hours']    = $q4hours;
  $templateArgs['flexhours']  = $flexhours;

  $templateArgs['view'] = 'staffing-save-result.html';

  return ($templateArgs);
}

function staffingDelete ($pbdb, $templateArgs) {
  $staffingid = (isset($_REQUEST['staffingid'])? $_REQUEST['staffingid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);

  if ($staffingid != null) {
    $pbdb->deleteStaffing($staffingid);
  }

  $templateArgs['staffingid'] = $staffingid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['deleteid'] = $staffingid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function peopleStaffingView ($pbdb, $templateArgs) {
  $peopleid = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);

  $templateArgs['staffing'] = $pbdb->getStaffing(null, null, $peopleid, null);

  $templateArgs['view'] = 'people-task-list-ajax.json';

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

function expenseSave ($pbdb, $templateArgs) {
  $proposalid    = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $expenseid     = (isset($_REQUEST['expenseid'])? $_REQUEST['expenseid'] : null);
  $expensetypeid = (isset($_REQUEST['expensetypeid'])? $_REQUEST['expensetypeid'] : null);
  $description   = (isset($_REQUEST['description'])? $_REQUEST['description'] : null);
  $amount        = (isset($_REQUEST['amount'])? $_REQUEST['amount'] : null);
  $fiscalyear    = (isset($_REQUEST['fiscalyear'])? $_REQUEST['fiscalyear'] : null);
  
  if ($expenseid == 'new') {
    $pbdb->addExpense ($proposalid, $expensetypeid, $description, $amount, $fiscalyear);
  }
  else {
    $pbdb->updateExpense ($expenseid, $proposalid, $expensetypeid, $description, $amount, $fiscalyear);
  }

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['expenseid'] = $expenseid;
  $templateArgs['expensetypeid'] = $expensetypeid;
  $templateArgs['description']   = $description;
  $templateArgs['amount']   = $amount;
  $templateArgs['fiscalyear']   = $fiscalyear;

  $templateArgs['view'] = 'expense-save-result.html';

  return ($templateArgs);
}

function expenseDelete ($pbdb, $templateArgs) {
  $proposalid    = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $expenseid     = (isset($_REQUEST['expenseid'])? $_REQUEST['expenseid'] : null);

  if ($expenseid != null) {
    $pbdb->deleteExpense($expenseid);
  }

  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['expenseid'] = $expenseid;
  $templateArgs['deleteid'] = $expenseid;
  $templateArgs['view'] = 'delete-result.html';

  return ($templateArgs);
}

function getOverhead ($pbdb, $templateArgs, $effectivedate) {
  if (!isset($templateArgs['overheadrates'])) {
    $templateArgs = overheadView ($pbdb, $templateArgs);
  }
    
  $effDate = strtotime($effectivedate);
  for ($o = 0; $o < count($templateArgs['overheadrates']); $o++) {
    $overDate = strtotime($templateArgs['overheadrates'][$o]['effectivedate']);
    if ($effDate > $overDate) {
      return $templateArgs['overheadrates'][$o]['rate'];
    }
  }

  return $templateArgs['overheadrates'][0]['rate'];
}

?>
