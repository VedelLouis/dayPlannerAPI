<?php

namespace Controllers;

use Repositories\EventRepository;

class EventController {

    public function __construct($action)
    {
        // CORS headers
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }

        switch ($action) {
            case "index":
                $this->traiterGetEvents();
                break;
            case "create":
                $this->createEvent();
                break;
            case "update":
                $this->updateEvent();
                break;
            case "updateTime":
                $this->updateEventTime();
                break;
            case "delete":
                $this->deleteEvent();
                break;
            case "sameTime":
                $this->eventSameTime();
                break;
        }
    }

    private function traiterGetEvents()
    {
        $idUser = $_SESSION['idUser'];
        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $events = EventRepository::getEvents($idUser, $date);

        if ($events) {
            echo json_encode($events, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    private function createEvent()
    {
        $idUser = $_SESSION['idUser'];
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);
        $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        EventRepository::createEvent($name, $dateStart, $dateEnd, $idUser, $color);

        echo json_encode(['success' => 1]);
    }

    private function updateEvent()
    {
        $idUser = $_SESSION['idUser'];
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);
        $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::updateEvent($idEvent, $name, $dateStart, $dateEnd, $color, $idUser);

        echo json_encode(['success' => $response]);
    }

    private function updateEventTime()
    {
        $idUser = $_SESSION['idUser'];
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_NUMBER_INT);
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::updateEventTime($idEvent, $dateStart, $dateEnd, $idUser);
        echo json_encode(['success' => $response]);
    }

    private function deleteEvent()
    {
        $idUser = $_SESSION['idUser'];
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_STRING);
        require_once "Repositories/EventRepository.php";
        $response = EventRepository::deleteEvent($idEvent, $idUser);

        echo json_encode(['success' => $response]);
    }

    private function eventSameTime()
    {
        $idUser = $_SESSION['idUser'];
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::eventSameTime($dateStart, $dateEnd, $idUser);
        echo json_encode(['success' => $response]);
    }

}