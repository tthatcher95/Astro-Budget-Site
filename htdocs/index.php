<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  array ('caption' => 'Proposals', 'href' => 'index.php?view=proposals'),
  array ('caption' => 'People', 'href' => 'index.php?view=people'),
  array ('caption' => 'Conferences', 'href' => 'index.php?view=conferences'),
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
    $templateArgs['people'][$i]['payplan'] = $salaryResults[0]['payplan'];
    $templateArgs['people'][$i]['title'] = $salaryResults[0]['title'];
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

function salarySave ($pbdb, $templateArgs, $salaryid, $peopleid, $effectivedate, $payplan, $title, $appttype, 
                     $authhours, $estsalary, $estbenefits, $leavecategory, $laf) {
  if ($salaryid != 'new') {
    $pbdb->updateSalary ($salaryid, $peopleid, $effectivedate, $payplan, $title, $appttype, $authhours,
                         $estsalary, $estbenefits, $leavecategory, $laf);
  }
  else {
    $templateArgs['debug'] = array ("salaryid was 'new'");
  }

  $templateArgs['view'] = 'salary-save-result.html';

  return ($templateArgs);
}

function proposalView ($pbdb, $templateArgs) {
  $peopleid = null;
  $proposalid = null;
  if (isset($_REQUEST['proposalid'])) { 
    $proposalid = $_REQUEST['proposalid']; 
    $templateArgs['view'] = 'proposal-view.html';
  }
  else {
    $templateArgs['view'] = 'proposals.html';
    if ($templateArgs['remote_user'][0]['admin'] != 't') { 
      $peopleid = $templateArgs['remote_user'][0]['peopleid']; 
    }
  }

  $templateArgs['proposals'] = $pbdb->getProposals ($proposalid, $peopleid, null, null, null, null);

  return ($templateArgs);
}

function programsView ($pbdb, $templateArgs) {
  $programid = null;
  if (isset($_REQUEST['programid'])) { $programid = $_REQUEST['programid']; }
  if ($programid == 'new') { 
    $templateArgs['programs'] = array ( array ('programid' => 'new', 'programname' => '',
      'agency' => '', 'pocname' => '', 'pocemail' => '', 'startdate' => '', 'enddate' => ''));
  }
  else {
    $templateArgs['programs'] = $pbdb->getFundingPrograms ($programid, null, null, null, null, null);
  }

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

?>
