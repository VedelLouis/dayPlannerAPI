<?php

namespace Repositories;

use Entities\Event;
use PdoBD;

class EventRepository
{
    public static function getEvents($idUser, $date)
    {
        $sql = "SELECT * FROM `Events` WHERE idUser = :idUser AND DATE(dateStart) = :date";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->bindValue(":date", $date);
        $stmt->execute();

        $eventData = $stmt->fetchAll();

        if (!$eventData) {
            return null;
        } else {
            $events = [];

            foreach ($eventData as $event) {
                $newEvent = new Event(
                    $event['idEvent'],
                    $event['name'],
                    $event['dateStart'],
                    $event['dateEnd'],
                    $event['color'],
                    $event['idUser']
                );
                $events[] = $newEvent;
            }

            return $events;
        }
    }

    public static function createEvent($name, $dateStart, $dateEnd, $idUser, $color)
    {
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
            $lastInsertedId = PdoBD::getInstance()->getMonPdo()->lastInsertId();
            return $lastInsertedId;
        } else {
            return 0;
        }
    }

    public static function updateEvent($idEvent, $name, $dateStart, $dateEnd, $color, $idUser)
    {
        $sql = "UPDATE `Events` SET name = :name, dateStart = :dateStart, dateEnd = :dateEnd, color = :color WHERE idUser = :idUser AND idEvent = :idEvent";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idEvent", $idEvent);
        $stmt->bindValue(":name", $name);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":color", $color);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function updateEventTime($idEvent, $dateStart, $dateEnd, $idUser)
    {
        $sql = "UPDATE `Events` SET dateStart = :dateStart, dateEnd = :dateEnd WHERE idUser = :idUser AND idEvent = :idEvent";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idEvent", $idEvent);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function deleteEvent($idEvent, $idUser)
    {
        $sql = "DELETE FROM `Events` WHERE idEvent = :idEvent AND idUser = :idUser";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idEvent", $idEvent);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function eventSameTime($dateStart, $dateEnd, $idUser, $idEvent = null)
    {
        $sql = "SELECT count(*) as sameTime 
                FROM `Events` WHERE (
                        (dateStart BETWEEN :dateStart AND :dateEnd) 
                        OR 
                        (dateEnd BETWEEN :dateStart AND :dateEnd)
                        OR
                        (:dateStart BETWEEN dateStart AND dateEnd)
                        OR
                        (:dateEnd BETWEEN dateStart AND dateEnd)
                    ) 
                    AND idUser = :idUser";

        if ($idEvent !== null) {
            $sql .= " AND idEvent != :idEvent";
        }

        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":idUser", $idUser);

        if ($idEvent !== null) {
            $stmt->bindValue(":idEvent", $idEvent);
        }

        $stmt->execute();

        $result = $stmt->fetch();

        return $result['sameTime'];
    }

    public static function choiceMoveEvent($idUser, $choice, $idEvent, $oldDateStart, $oldDateEnd, $name, $color)
    {
        $db = PdoBD::getInstance()->getMonPdo();
        if ($choice == 2) {
            $stmt = $db->prepare("CALL sp_choiceMoveEvent(:idUser, :choice, :idEvent, :oldDateStart, :oldDateEnd, :name, :color, @newEventId)");
        }

        $stmt->bindParam(':idUser', $idUser, \PDO::PARAM_INT);
        $stmt->bindParam(':choice', $choice, \PDO::PARAM_INT);
        $stmt->bindParam(':idEvent', $idEvent, \PDO::PARAM_INT);
        $stmt->bindParam(':oldDateStart', $oldDateStart, \PDO::PARAM_STR);
        $stmt->bindParam(':oldDateEnd', $oldDateEnd, \PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        $stmt->bindParam(':color', $color, \PDO::PARAM_STR);

        $stmt->execute();
        $stmt->closeCursor();

        $result = $db->query("SELECT @newEventId AS newEventId")->fetch(\PDO::FETCH_ASSOC);
        return $result['newEventId'] ?? 1;
    }
}
