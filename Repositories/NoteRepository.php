<?php

namespace Repositories;

use Entities\Note;
use PdoBD;

class NoteRepository {

    public static function getNotes($idUser, $date) {
        $sql = "SELECT * FROM `Notes` WHERE idUser = :idUser AND DATE(date) = :date";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->bindValue(":date", $date);
        $stmt->execute();

        $noteData = $stmt->fetchAll();

        if (!$noteData) {
            return null;
        } else {
            $notes = [];

            foreach ($noteData as $note) {
                $decodedText = html_entity_decode($note['text'], ENT_QUOTES, 'UTF-8');

                $newNote = new Note(
                    $note['idNote'],
                    $decodedText,
                    $note['date'],
                    $note['idUser']
                );
                $notes[] = $newNote;
            }

            return $notes;
        }
    }

    public static function createNote($text, $date, $idUser) {
        $sql = "INSERT INTO `Notes` (text, date, idUser) VALUES (:text, :date, :idUser);";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":text", $text);
        $stmt->bindValue(":date", $date);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateNote($text, $date, $idUser) {
        $sql = "UPDATE `Notes` SET text = :text WHERE date = :date AND idUser = :idUser";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":text", $text);
        $stmt->bindValue(":date", $date);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function deleteNote($date, $idUser) {
        $sql = "DELETE FROM `Notes` WHERE date = :date AND idUser = :idUser";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":date", $date);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}