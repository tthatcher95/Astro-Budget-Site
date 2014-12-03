<?php

require_once(dirname(__FILE__) . '/../configure.php');

class DatabasePG {
  var $connect;
  var $result;
  var $time;
  var $total;
  
  var $host;
  var $port;
  var $dbname;
  var $user;
  var $password;

  function DatabasePG() {
  }

  function getDBSettings() {
    $config = new Config();

    $this->host     = $config->budget_db_host;
    $this->port     = $config->budget_db_port;
    $this->dbname   = $config->budget_db_name;
    $this->user     = $config->budget_db_user;
    $this->password = $config->budget_db_pswd;
  }

  function query ($query) {

    $startTime = microtime (TRUE);

    $this->getDBSettings();
    $this->connect = pg_connect("host=$this->host " .
      "port=$this->port " .
      "dbname=$this->dbname " .
      "user=$this->user " .
      "password=$this->password") or die ("Could not connect: " . pg_last_error());
    # TBD - replace these die calls by throwing exceptions

    $this->result = pg_query ($query) or die ("Query failed: " . pg_last_error() .  '<br/>' . $query);

    $this->total = pg_num_rows($this->result);

    $this->time = microtime(TRUE) - $startTime;
  }

  function getResultRow () {
    if (!isset($this->result)) {
      return (NULL);
    }

    $row = pg_fetch_row($this->result, null, PGSQL_ASSOC);
    return ($row);
  }

  function getResultArray () {
    if (!isset($this->result)) {
      return (array());
    }

    $resultArray = array();
    while ($row = pg_fetch_array($this->result, null, PGSQL_ASSOC)) {
      $resultArray[] = $row;
    }

    return ($resultArray);
  }

  function setResult (&$result) {
    $this->result =& $result;
  }
}

?>
