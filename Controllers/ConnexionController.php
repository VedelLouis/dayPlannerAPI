<?php

namespace Controllers;

use Repositories\UserRepository;

class ConnexionController
{
    public function __construct($action)
    {
        session_start();

        // CORS headers
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $allowed_origins = [
            'http://localhost:8100',
            'https://dayplanner.tech',
            'capacitor-electron://-',
            'capacitor-android://-',
            'https://localhost'
        ];
        if (in_array($origin, $allowed_origins)) {
            header("Access-Control-Allow-Origin: $origin");
        }
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');

        switch ($action) {
            case "connect":
                $this->traiterConnexion();
                break;
            case "deconnect":
                $this->deconnecter();
                break;
            case "session":
                $this->session();
                break;
        }
    }

    private function traiterConnexion()
    {
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

        if(empty($login) || empty($password)) {
            echo json_encode(['success' => 0, 'message' => 'Veuillez fournir un login et un mot de passe.']);
            return;
        }

        require_once "Repositories/UserRepository.php";
        $connectedUser = UserRepository::getUser($login, $password);

        if ($connectedUser) {
            session_regenerate_id(true);
            $_SESSION['idUser'] = $connectedUser->getIdUser();
            echo json_encode($connectedUser, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0, 'message' => 'Identifiants incorrects.']);
        }
    }

    private function deconnecter()
    {
        $_SESSION['idUser'] = null;
        if ($_SESSION['idUser'] == null) {
            echo json_encode(['success' => 1]);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    private function session()
    {
        if ($_SESSION['idUser'] == null) {
            echo json_encode(['success' => 0]);
        } else {
            echo json_encode(['success' => 1]);
        }
    }

}
?>
