<?php

/**
 * Simple Demonstration about calling the Php Web Application lib
 */

//for debuging
error_reporting(E_ALL);
ini_set('display_errors', '1');

//requiring 
$framework_path = realpath(dirname(__FILE__) . "/../../framework");
echo $framework_path; die;

$app_path = dirname(__FILE__);

require_once($framework_path."/lib/BaseApplication.php");
require_once($framework_path."/lib/BaseController.php");

//creating and executing the Application.
new BaseApplication($app_path)->run();
