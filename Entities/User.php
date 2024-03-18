<?php

namespace Entities;
class User implements \JsonSerializable {
    private $idUser;
    private $login;
    private $password;
    private $firstName;
    private $lastName;

    // Constructeur
    public function __construct($idUser, $login, $password, $firstName, $lastName) {
        $this->idUser = $idUser;
        $this->login = $login;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    // Getters
    public function getIdUser() {
        return $this->idUser;
    }

    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    // Setters
    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function jsonSerialize() {
        return [
            'success' => 1,
            'idUser' => $this->idUser,
            'login' => $this->login,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'session' => session_id()
        ];
    }
}

?>
