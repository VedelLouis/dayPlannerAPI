<?php

namespace Controllers;

use Repositories\UserRepository;

class AccountController
{

    public function __construct($action)
    {
        session_start();
        switch ($action) {
            case "index":
                $this->connectedUser();
                break;
            case "user":
                $this->getUser();
                break;
            case "create":
                $this->creerUser();
                break;
            case "edit":
                $this->modifierMonCompte();
                break;
            case "delete":
                $this->supprimerMonCompte();
                break;
            case "session":
                $this->session();
                break;
        }
    }

    private function connectedUser()
    {
        $connectedUser = UserRepository::getUserById($_SESSION['idUser']);
        if ($connectedUser) {
            echo json_encode($connectedUser, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0]);
        }

    }

    private function getUser()
    {
        $idUser = filter_input(INPUT_GET, 'iduser', FILTER_SANITIZE_NUMBER_INT);
        $connectedUser = UserRepository::getUserById($idUser);
        if ($connectedUser) {
            echo json_encode($connectedUser, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0]);
        }

    }

    private function creerUser()
    {
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);

        require_once "Repositories/UserRepository.php";

        UserRepository::createUser($login, $password, $firstname, $lastname);

    }

    private function modifierMonCompte()
    {
        $idUser = $_SESSION['idUser'];
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $mdpActuel = filter_input(INPUT_POST, 'mdpActuel', FILTER_SANITIZE_STRING);
        $nouveauMdp = filter_input(INPUT_POST, 'nouveauMdp', FILTER_SANITIZE_STRING);
        $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
        $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);

        require_once "Repositories/UserRepository.php";

        $userData = UserRepository::getUserById($idUser);

        if (!password_verify($mdpActuel, $userData->getPassword())) {
            echo json_encode(['success' => 0]);
        } else {
            UserRepository::updateUser($idUser, $login, $nouveauMdp, $firstname, $lastname);
            echo json_encode(['success' => 1]);
        }
    }

    private function supprimerMonCompte()
    {
        $idUser = $_SESSION['idUser'];
        $confirmDeletePassword = filter_input(INPUT_POST, 'confirmDeletePassword', FILTER_SANITIZE_STRING);

        require_once "Repositories/UserRepository.php";

        $userData = UserRepository::getUserById($idUser);

        if (!password_verify($confirmDeletePassword, $userData->getPassword())) {
            echo json_encode(['success' => 0]);
        } else {
            UserRepository::deleteUser($idUser);
            echo json_encode(['success' => 1]);
        }
    }

}