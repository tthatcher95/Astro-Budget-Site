<?php

require_once(dirname(__FILE__) . '/configure.php');

$config = new Config();

#$dsn = 'pgsql://' . $config->budget_db_user .  ':' . $config->budget_db_pswd . 
#       '@tcp(' . $config->budget_db_host . ':' . $config->budget_db_port . ')/' . $config->budget_db_name;

# pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass

$dsn = 'pgsql:host=' . $config->budget_db_host . ';port=' . $config->budget_db_port . 
       ';dbname=' . $config->budget_db_name . ';user=' . $config->budget_db_user . 
       ';password=' . $config->budget_db_pswd;

$dbh = new PDO ($dsn);

?>
