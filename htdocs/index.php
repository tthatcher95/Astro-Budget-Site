<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
# require_once '/var/www/budgetprops-dev/php_packages/Twig-1.18.2/lib/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  array ('caption' => 'Home', 'href' => 'index.php'),
  array ('caption' => 'Projects', 'href' => 'index.php?view=proposals'),
  array ('caption' => 'People*', 'href' => 'index.php?view=people'),
  array ('caption' => 'Conferences/Travel*', 'href' => 'index.php?view=conferences'),
  array ('caption' => 'Expense Categories*', 'href' => 'index.php?view=expensetypes'),
  array ('caption' => 'Programs*', 'href' => 'index.php?view=programs')));
$templateArgs['statuscodes'] = array ('Notional', 'Submitted', 'Selected', 'Rejected', 'Active', 'Completed');

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
      $templateArgs = proposalSave($pbdb, $templateArgs);
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
    case 'proposal-costs-json':
      $templateArgs = overheadView($pbdb, $templateArgs);
      $templateArgs = proposalView($pbdb, $templateArgs);
      $templateArgs = costsSummaryView($pbdb, $templateArgs);
      $templateArgs['view'] = 'proposal-costs-ajax.json';
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

  $conferenceattendeeid = (isset($_REQUEST['conferenceattendeeid'])? $_REQUEST['conferenceattendeeid'] : null);
  # Add in the tasks, FBMS accounts, conferences/attendees, and expenses too
  for ($i = 0; $i < count($templateArgs['proposals']); $i++) {
    $proposalid = $templateArgs['proposals'][$i]['proposalid'];
    $templateArgs['proposals'][$i]['FBMSaccounts'] = $pbdb->getFBMSAccounts (null, null, $proposalid);
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
    $templateArgs['proposals'][$i]['tasks'] = $pbdb->getTasks (null, $proposalid, null);
    $templateArgs['proposals'][$i]['expenses'] = $pbdb->getExpenses ($expenseid, $proposalid, null, null);
  }

  return ($templateArgs);
}

function costsSummaryView ($pbdb, $templateArgs) {
  setlocale(LC_MONETARY, 'en_US');
  $templateArgs['costs'] = array ();
  for ($i = 0; $i < count($templateArgs['proposals']); $i++) {
    $templateArgs['costs'][$i] = array ();
    $totals = array();
    $subtotals = array();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['tasks']); $j++) {
      $templateArgs['proposals'][$i]['tasks'][$j]['staffing'] = $pbdb->getStaffing(null, 
        $templateArgs['proposals'][$i]['tasks'][$j]['taskid'], null, null);

      for ($x = 0; $x < count($templateArgs['proposals'][$i]['tasks'][$j]['staffing']); $x++) {
        # TBD - this needs to be moved to pull salary based on the startdate of the task
        $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['salary'] = 
          $pbdb->getEffectiveSalary ($templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$x]['peopleid'], 
            $date = date('m/d/Y', time()));
      }
      for ($k = 0; $k < count($templateArgs['proposals'][$i]['tasks'][$j]['staffing']); $k++) {
        $currOver = getOverhead ($pbdb, $templateArgs,
                      $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['fiscalyear']);
        $currFy = $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['FY'];
        $cost = ($templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q1hours'] +
                 $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q2hours'] +
                 $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q3hours'] +
                 $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['q4hours'] +
                 $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['flexhours']) *
                 ($templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['estsalary'] +
                 $templateArgs['proposals'][$i]['tasks'][$j]['staffing'][$k]['salary'][0]['estbenefits']);
        $subtotals[$currFy] += $cost * (1 + ($currOver * .01));
        $totals[$currFy] += $cost * (1 + ($currOver * .01));
      }
    }
  
    $subtotal = 0;
    $templateArgs['costs'][$i]['staffing'] = "Tasks - ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $cost) {
      $templateArgs['costs'][$i]['staffing'] .= " $fy " . money_format('%(#8n', $cost);
      $subtotal += $cost;
    }
    
    $templateArgs['costs'][$i]['staffing'] .= " Total " . money_format('%(#8n', $subtotal);
    $total += $subtotal;
    $subtotal = 0;
    $subtotals = array ();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['conferenceattendees']); $j++) {
      $currFy = $templateArgs['proposals'][$i]['conferenceattendees'][$j]['FY'];
      $costs = ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
                $templateArgs['proposals'][$i]['conferenceattendees'][$j]['meetingdays'] *
                $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['perdiem']);
      $costs += ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['travelers'] * 
                ($templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['groundtransport'] +
                 $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['airfare'] +
                 $templateArgs['proposals'][$i]['conferenceattendees'][$j]['conferencerate'][0]['registration']));
      $currOver = getOverhead ($pbdb, $templateArgs,
                    $templateArgs['proposals'][$i]['conferenceattendees'][$j]['startdate']);
      $subtotals[$currFy] += $cost * (1 + ($currOver * .01));
      $totals[$currFy] += $cost * (1 + ($currOver * .01));
    }
    $templateArgs['costs'][$i]['conferences'] = "Conferences/Training/Meetings - ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $cost) {
      $templateArgs['costs'][$i]['conferences'] .= " $fy " . money_format('%(#8n', $cost);
      $subtotal += $cost;
    }
    
    $templateArgs['costs'][$i]['conferences'] .= " Total " . money_format('%(#8n', $subtotal);
    $total += $subtotal;
    $subtotal = 0;
    $subtotals = array();
    for ($j = 0; $j < count($templateArgs['proposals'][$i]['expenses']); $j++) {
      $currFy = $templateArgs['proposals'][$i]['expenses'][$j]['FY'];
      $currOver = getOverhead ($pbdb, $templateArgs,
                    $templateArgs['proposals'][$i]['expenses'][$j]['fiscalyear']);
      $subtotals[$currFy] += $templateArgs['proposals'][$i]['expenses'][$j]['amount'] * (1 + ($currOver * .01));;
      $totals[$currFy] += $templateArgs['proposals'][$i]['expenses'][$j]['amount'] * (1 + ($currOver * .01));;
    }
    $templateArgs['costs'][$i]['expenses'] = "Expenses - ";
    ksort($subtotals);
    foreach ($subtotals as $fy => $cost) {
      $templateArgs['costs'][$i]['expenses'] .= " $fy " . money_format('%(#8n', $cost);
      $subtotal += $cost;
    }
    
    $templateArgs['costs'][$i]['expenses'] .= " Totals " . money_format('%(#8n', $subtotal);
    $total += $subtotal;
    $templateArgs['costs'][$i]['proposal'] = "Proposal Details - " . 
                                             $templateArgs['proposals'][$i]['projectname'] . " - ";
    $total = 0;
    ksort($totals);
    foreach ($totals as $fy => $cost) {
      $templateArgs['costs'][$i]['proposal'] .= " $fy " . money_format('%(#8n', $cost);
      $total += $cost;
    }
    $templateArgs['costs'][$i]['proposal'] .= " Totals " . money_format('%(#8n', $total);
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
    $pbdb->addProposal ($peopleid, $projectname, $proposalnumber, $awardnumber, $programid,
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

  error_log("fbmsSave: $fbmsid - $proposalid - $accountno ");
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
  $city             = (isset($_REQUEST['city'])? $_REQUEST['city'] : null);
  $state            = (isset($_REQUEST['state'])? $_REQUEST['state'] : null);
  $country          = (isset($_REQUEST['country'])? $_REQUEST['country'] : null);

  if ($conferencerateid == 'new') {
    $pbdb->addConferenceRate ($conferenceid, $effectivedate, $perdiem, $registration, $groundtransport, $airfare, 
                              $city, $state, $country);
  }
  else {
    $pbdb->updateConferenceRate ($conferencerateid, $conferenceid, $effectivedate, $perdiem, $registration,
                                 $groundtransport, $airfare, $city, $state, $country);
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

  if ($conferenceattendeeid == 'new') {
    $pbdb->addConferenceAttendee ($conferenceid, $proposalid, $travelers, $meetingdays, $traveldays, $startdate);
  }
  else {
    $pbdb->updateConferenceAttendee ($conferenceattendeeid, $conferenceid, $proposalid, $travelers, $meetingdays,
                                     $traveldays, $startdate);
  }

  $templateArgs['conferenceattendeeid'] = $conferenceattendeeid;
  $templateArgs['conferenceid'] = $conferenceid;
  $templateArgs['proposalid'] = $proposalid;
  $templateArgs['travelers'] = $travelers;
  $templateArgs['meetingdays'] = $meetingdays;
  $templateArgs['traveldays'] = $traveldays;
  $templateArgs['startdate'] = $startdate;

  $templateArgs['view'] = 'conference-attendee-save-result.html';

  return ($templateArgs);
}

function tasksView ($pbdb, $templateArgs) {
  $taskid     = (isset($_REQUEST['taskid'])? $_REQUEST['taskid'] : null);
  $proposalid = (isset($_REQUEST['proposalid'])? $_REQUEST['proposalid'] : null);
  $taskname   = (isset($_REQUEST['taskname'])? $_REQUEST['taskname'] : null);
  $peopleid    = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);

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

  return ($templateArgs);
}

function peopleStaffingView ($pbdb, $templateArgs) {
  $peopleid = (isset($_REQUEST['peopleid'])? $_REQUEST['peopleid'] : null);

  $templateArgs['staffing'] = $pbdb->getStaffing(null, null, $peopleid, null);

  $templateArgs['view'] = 'people-task-list-ajax.json';

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
