<?php

namespace Controllers;

use Repositories\UserRepository;

class ConnexionController
{
    public function __construct($action)
    {
        session_start();
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