<?php

namespace Repositories;

use Entities\Task;
use PdoBD;

class TaskRepository {

    public static function getTasks($idUser, $date) {
        $sql = "SELECT * FROM `Tasks` WHERE idUser = :idUser AND DATE(date) = :date";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idUser", $idUser);
        $stmt->bindValue(":date", $date);
        $stmt->execute();

        $taskData = $stmt->fetchAll();

        if (!$taskData) {
            return null;
        } else {
            $tasks = [];

            foreach ($taskData as $task) {
                $newTask = new Task(
                    $task['idTask'],
                    $task['title'],
                    $task['done'],
                    $task['priority'],
                    $task['date'],
                    $task['idUser']
                );
                $tasks[] = $newTask;
            }

            return $tasks;
        }
    }

    public static function createTask($title, $priority, $date, $idUser) {
        $sql = "INSERT INTO `Tasks` (title, done, priority, date, idUser) VALUES (:title, 0, :priority, :date, :idUser);";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":title", $title);
        $stmt->bindValue(":priority", $priority);
        $stmt->bindValue(":date", $date);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            $lastInsertedId = PdoBD::getInstance()->getMonPdo()->lastInsertId();
            return $lastInsertedId;
        } else {
            return 0;
        }
    }

    public static function updateTask($idTask, $done, $idUser) {
        $sql = "UPDATE `Tasks` SET done = :done WHERE idUser = :idUser AND idTask = :idTask";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idTask", $idTask);
        $stmt->bindValue(":done", $done);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function changeTask($idTask, $priority, $idUser) {
        $sql = "UPDATE `Tasks` SET priority = :priority WHERE idUser = :idUser AND idTask = :idTask";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idTask", $idTask);
        $stmt->bindValue(":priority", $priority);
        $stmt->bindValue(":idUser", $idUser);

        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function delayTask($idTask, $date, $idUser) {
        $sql = "UPDATE `Tasks` SET date = :date WHERE idUser = :idUser AND idTask = :idTask";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idTask", $idTask);
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

    public static function deleteTask($idTask, $idUser) {
        $sql = "DELETE FROM `Tasks` WHERE idUser = :idUser AND idTask = :idTask";
        $stmt = PdoBD::getInstance()->getMonPdo()->prepare($sql);
        $stmt->bindValue(":idTask", $idTask);
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