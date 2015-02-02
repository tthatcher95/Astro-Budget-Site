<?php

require_once '/usr/share/pear/Twig/Autoloader.php';
require_once(dirname(__FILE__) . '/models/PBTables.php');

$pbdb = new PBTables();

$view = 'default.html'; # Change to default landing page

$templateArgs = array('navigation' => array (
  array ('caption' => 'People', 'href' => 'index.php?view=people'),
  array ('caption' => 'Proposals', 'href' => 'index.php?view=proposals'),
  array ('caption' => 'Programs', 'href' => 'index.php?view=programs')));

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
    case 'salary-ajax':
      if (isset($_REQUEST['peopleid'])) {
        $templateArgs = salaryView($pbdb, $templateArgs, $_REQUEST['peopleid']);
      }
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

function salaryView ($pbdb, $templateArgs, $peopleid) {
  $templateArgs['salaries'] = $pbdb->getSalary($peopleid);
  $templateArgs['view'] = 'salary-ajax.html';

  return ($templateArgs);
}

?>
