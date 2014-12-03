<?php

  date_default_timezone_set('America/Phoenix');

  class Config {
    var $budget_db_host = 'spacely.wr.usgs.gov';
    var $budget_db_port = '3309';
    var $budget_db_name = 'propbudgets_prd';
    var $budget_db_user = 'budgetmgr';
    var $budget_db_pswd = '!MgrBudget$';

    var $casServer       = 'astrocas.wr.usgs.gov';
    var $casPort         = 443;
    var $casContext      = '/cas';

  }

?>
