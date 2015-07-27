<?php
//load config
require_once 'config.php';

if (ENVIRONMENT === 'dev') {
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

/*Auto loader*/
require_once 'vendor/autoload.php';


\System\Api::run();

