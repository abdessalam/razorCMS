<?php

/**
 * razorCMS FBCMS
 *
 * Copywrite 2014 to Present Day - Paul Smith (aka smiffy6969, razorcms)
 *
 * @author Paul Smith
 * @site ulsmith.net
 * @created Feb 2014
 */

// set session
session_start();
session_regenerate_id();

// sidewide constants
define('RAZOR_BASE_PATH', '../');
define("RAZOR_BASE_URL", '/');
define("RAZOR_USERS_IP", $_SERVER["REMOTE_ADDR"]);
define("RAZOR_USERS_UAGENT", $_SERVER["HTTP_USER_AGENT"]);

// permission defines
// 6 to 10 - access to admin dash
define("SUPER_ADMIN", 10); // only one account with this and it cannot be removed
define("ADMIN", 9); // pretty much the same as super admin but can be removed
define("MANAGER", 8); // add, edt, remove content only
define("EDITOR", 7); // add, edit content only
define("CONTRIBUTER", 6); // add content only
// 1 to 5 - no access to admin dash, user levels only
define("USER_5", 5); // base level, can onlyalter profile and user areas of public site that are protected to level 1
define("USER_4", 4); // base level, can onlyalter profile and user areas of public site that are protected to level 1
define("USER_3", 3); // base level, can onlyalter profile and user areas of public site that are protected to level 1
define("USER_2", 2); // base level, can onlyalter profile and user areas of public site that are protected to level 1
define("USER_1", 1); // base level, can onlyalter profile and user areas of public site that are protected to level 1

// PDO
define('RAZOR_PDO', 'sqlite:'.RAZOR_BASE_PATH.'db.sqlite');

// includes
include_once(RAZOR_BASE_PATH.'src/library/php/razor/razor_file_tools.php');
include_once(RAZOR_BASE_PATH.'src/library/php/razor/razor_error_handler.php');
include_once(RAZOR_BASE_PATH.'src/library/php/razor/razor_site.php');
include_once(RAZOR_BASE_PATH.'src/library/php/razor/razor_pdo.php');
require(RAZOR_BASE_PATH.'src/library/vendor/autoload.php');

// Load error handler
$error = new RazorErrorHandler();
set_error_handler(array($error, 'handle_error'));
set_exception_handler(array($error, 'handle_error'));


// continue with public load
$site = new RazorSite();
$site->load();
$site->render();

/* PHP END */