<?php

require_once (dirname(__FILE__) . "/../doctrine_boot.php");

class People extends Doctrine_Record {
  public function setTableDefinition() {
    $this->hasColumn('peopleid',     'integer', 20);
    $this->hasColumn('name',     'string', 128);
    $this->hasColumn('username', 'string', 32);
  }
}

?>
