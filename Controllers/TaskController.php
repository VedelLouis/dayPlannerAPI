<?php

namespace Controllers;

use Repositories\TaskRepository;

class TaskController {

    public function __construct($action)
    {
        session_start();
        switch ($action) {
            case "index":
                $this->traiterGetTasks();
                break;
            case "create":
                $this->createTask();
                break;
            case "update":
                $this->updateTask();
                break;
            case "delete":
                $this->deleteTask();
                break;
        }
    }

    private function traiterGetTasks()
    {
        $idUser = $_SESSION['idUser'];
        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/TaskRepository.php";
        $tasks = TaskRepository::getTasks($idUser, $date);

        if ($tasks) {
            echo json_encode($tasks, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    private function createTask()
    {
        $idUser = $_SESSION['idUser'];
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $priority = filter_input(INPUT_POST, 'priority', FILTER_SANITIZE_NUMBER_INT);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/TaskRepository.php";
        $newTaskId = TaskRepository::createTask($title, $priority, $date, $idUser);

        echo json_encode(['idTask' => $newTaskId]);
    }

    private function updateTask()
    {
        $idUser = $_SESSION['idUser'];
        $idTask = filter_input(INPUT_POST, 'idTask', FILTER_SANITIZE_NUMBER_INT);
        $done = filter_input(INPUT_POST, 'done', FILTER_SANITIZE_NUMBER_INT);

        require_once "Repositories/TaskRepository.php";
        $response = TaskRepository::updateTask($idTask, $done, $idUser);

        if ($response == 1) {
            echo json_encode(['success' => 1]);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    private function deleteTask()
    {
        $idUser = $_SESSION['idUser'];
        $idTask = filter_input(INPUT_POST, 'idTask', FILTER_SANITIZE_NUMBER_INT);

        require_once "Repositories/TaskRepository.php";
        TaskRepository::deleteTask($idTask, $idUser);
        echo json_encode(['success' => 1]);
    }

}