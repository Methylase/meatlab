<?php
//The main config file
define('BASE_URL', 'http://localhost/meatlab.com/');
define('ENV', 'dev');
define('DEFAULT_MODULE', 'home');
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_METHOD', 'index');
define('MODULE_ASSETS_TRIGGER', '_module');
define('INTERCEPT_404', 'trongate_pages/attempt_display');
require_once __DIR__ . '/meatlab.php';