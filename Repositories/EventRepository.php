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
            return 1;
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

    public static function eventSameTime($dateStart, $dateEnd, $idUser)
    {
        $sql = "SELECT count(*) as sameTime FROM Events WHERE ((dateStart BETWEEN :dateStart AND :dateEnd) OR (dateEnd BETWEEN :dateStart AND :dateEnd)) AND idUser = :idUser";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":dateStart", $dateStart);
        $stmt->bindValue(":dateEnd", $dateEnd);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->execute();

        $result = $stmt->fetch();

        return $result['sameTime'];
    }

}