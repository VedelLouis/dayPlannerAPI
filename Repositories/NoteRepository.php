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
                $newNote = new Note(
                    $note['idNote'],
                    $note['text'],
                    $note['date'],
                    $note['idUser']
                );
                $notes[] = $newNote;
            }

            return $notes;
        }
    }

    public static function createEvent($name, $dateStart, $dateEnd, $idUser, $color) {
        $sql = "INSERT INTO `Events` (name, dateStart, dateEnd, idUser, color) VALUES (:name, :dateStart, :dateEnd, :idUser, :color);";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->bindValue(":color", $color);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateEvent($idEvent, $name, $dateStart, $dateEnd, $color) {
        $sql = "UPDATE `Events` SET name = :name, dateStart = :dateStart, dateEnd = :dateEnd, color = :color WHERE idEvent = :idEvent";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idEvent", $idEvent);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":color", $color);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function deleteEvent($idEvent) {
        $sql = "DELETE FROM `Events` WHERE idEvent = :idEvent";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idEvent", $idEvent);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

}