<?php

namespace Repositories;

use PDO;
use PdoBD;
use Entities\User;
class UserRepository
{
    public static function getUser($login, $password) {
        $sql = "SELECT * FROM `Users` WHERE login = :login";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":login", $login);
        $stmt->execute();

        $userData = $stmt->fetch();

        if (!$userData) {
            return null;
        }

        if (password_verify($password, $userData['password'])) {
            $user = new User(
                $userData['idUser'],
                $userData['login'],
                $userData['password'],
                $userData['firstName'],
                $userData['lastName']
            );

            return $user;
        } else {
            return null;
        }
    }

    public static function getUserById($idUser) {
        $sql = "SELECT * FROM `Users` WHERE idUser = :idUser";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->execute();

        $userData = $stmt->fetch();
        if (!$userData) {
            return null;
        }

        return new User(
            $userData['idUser'],
            $userData['login'],
            $userData['password'],
            $userData['firstName'],
            $userData['lastName']
        );
    }

    public static function createUser($login, $password, $firstname, $lastname) {
        // Hachage avec bcrypt
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO Users (login, password, firstname, lastname) VALUES (:login, :password, :firstname, :lastname)";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);

        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);

        $stmt->execute();
    }

}
