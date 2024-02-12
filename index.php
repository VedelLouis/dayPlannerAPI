<?php

session_start();
ini_set('display_errors', 'on');
error_reporting(E_ERROR | E_COMPILE_ERROR | E_CORE_ERROR);
header('Content-Type: application/json');

function loadClasses($classe) {
    $cls = str_replace('\\', DIRECTORY_SEPARATOR, $classe);
    include $cls . '.php';
}

spl_autoload_register('loadClasses');

$pdo = PdoBD::getInstance();

$controller = filter_input(INPUT_GET, 'controller', FILTER_SANITIZE_STRING);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if (!$controller) {
    $controller = "intervenant";
}

if (isset($_SESSION['login'])) {
    switch ($controller) {
        case "connexion":
            $c = new \Controllers\ConnexionController($action);
            break;

        default:
            $r = new \models\RetourJSON($action, false, "Le controleur " . $controller . " n'est pas encore implémenté.");
            echo $r->retourToJSON();
    }
} else {
    switch ($controller) {
        case "intervenant":
            if (!$action) {
                $action = "connexion";
            }
            $c = new \controllers\IntervenantController($action);
            break;

        default:
            $r = new \models\RetourJSON($action, false, "Veuillez vous connecter pour accéder à ces informations.");
            echo $r->retourToJSON();
    }
}