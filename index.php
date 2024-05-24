<?php

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'None');

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR);
date_default_timezone_set('Europe/Paris');

function loadClasses($classe)
{
    $cls = str_replace('\\', DIRECTORY_SEPARATOR, $classe);
    include $cls . '.php';
}

spl_autoload_register('loadClasses');

$controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING);
if (!$controller) {
    $controller = "connexion";
}

switch ($controller) {

    case "connexion":
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = "connect";
        }
        $c = new \Controllers\ConnexionController($action);
        break;
    case "account":
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = "index";
        }
        $c = new \Controllers\AccountController($action);
        break;
    case "event":
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = "index";
        }
        $c = new \Controllers\EventController($action);
        break;
    case "task":
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = "index";
        }
        $c = new \Controllers\TaskController($action);
        break;
    case "note":
        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
        if (!$action) {
            $action = "index";
        }
        $c = new \Controllers\NoteController($action);
        break;

}

?>
