<?php

require_once('/usr/share/pear/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

$manager = Doctrine_Manager::getInstance();

?>
