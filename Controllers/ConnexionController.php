<?php

namespace Controllers;

use Entities\RetourJSON;
use Repositories\UserRepository;

class ConnexionController
{
    public function __construct($action)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        switch ($action) {
            case "index":
                $this->afficherConnexionView();
                break;
            case "connect":
                $this->traiterConnexion();
                break;
            case "deconnect":
                $this->deconnecter();
                break;
        }
    }

    private function afficherConnexionView()
    {
        include "Views/ConnexionView.php";
    }

    private function traiterConnexion()
    {
        $login = filter_input(INPUT_GET, 'login', FILTER_SANITIZE_STRING);

        require_once "Repositories/UserRepository.php";
        $connectedUser = UserRepository::getUser($login);

        // Vérifier si un utilisateur a été trouvé
        if ($connectedUser) {
            // Afficher les données de l'utilisateur
            echo "ID de l'utilisateur : " . $connectedUser->getIdUser() . "<br>";
            echo "Login de l'utilisateur : " . $connectedUser->getLogin() . "<br>";
            echo "Prénom de l'utilisateur : " . $connectedUser->getFirstName() . "<br>";
            echo "Nom de l'utilisateur : " . $connectedUser->getLastName() . "<br>";

            // Créer un objet RetourJSON avec les données de l'utilisateur
            $retour = new RetourJSON("getUser", true, $connectedUser);
        } else {
            // Créer un objet RetourJSON avec un message d'erreur approprié si aucun utilisateur n'est trouvé
            $retour = new RetourJSON("getUser", false, "Utilisateur non trouvé");
        }

        // Convertir l'objet RetourJSON en format JSON et l'afficher
        echo $retour->retourToJSON();
    }



    private function deconnecter()
    {
        session_destroy();
        header('Location: index.php');
    }
}
?>