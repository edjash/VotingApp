<?php
//Bring in the config file
if (!is_file('./config.php')) {
    die("Configuration file not found. See config.example.php");
}
require_once './config.php';

//initialise session
@session_start();

//Set basic constants and include essential files
define('BASE_URL', $config['base_url']);
define('BASE_DIR', rtrim(dirname(__FILE__), '/').'/');
define('TPL_DIR', './templates/');
define('IS_DEBUG', $config['debug']);

//supress errors if code is live
if (IS_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');
} else {
    error_reporting(0);
    ini_set('display_errors', 'off');
}

//Include essential files
require BASE_DIR.'util.php';
require BASE_DIR.'Database.php';
require BASE_DIR.'Router.php';
require BASE_DIR.'Model.php';
require BASE_DIR.'Controller.php';

//Set an exception handler
function exception_handler($exception)
{
    if (!IS_DEBUG) {
        die("An internal error occured.");
    }
    echo "Uncaught exception: " , $exception->getMessage(), "\n";
}
set_exception_handler('exception_handler');

//Make initial database connection, see Database.php
Database::connect($config['db_host'], $config['db_name'], $config['db_user'], $config['db_pass']);

//The Router parses the URL to determine which Controller file to use, see Router.php
Router::getInstance()->dispatch();
