<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  array ('caption' => 'People', 'href' => 'index.php?view=people'),
  array ('caption' => 'Proposals', 'href' => 'index.php?view=proposals'),
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
      $templateArgs = peopleSave($pbdb, $templateArgs, $_REQUEST['peopleid'],
                                 $_REQUEST['name'], $_REQUEST['username']);
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

function peopleSave ($pbdb, $templateArgs, $peopleid, $name, $username) {
  if ($peopleid != 'new') {
    $templateArgs['debug'] = array ($peopleid, $name, $username);
    $pbdb->updatePerson ($peopleid, $name, $username);
  }
  else {
    $templateArgs['debug'] = array ("peopleid was 'new'");
  }

  $templateArgs['peopleid'] = $peopleid;
  $templateArgs['name'] = $name;
  $templateArgs['username'] = $username;
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

?>
