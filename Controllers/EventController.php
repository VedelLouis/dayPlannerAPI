<?php

namespace Controllers;

use Repositories\EventRepository;

class EventController
{

    public function __construct($action)
    {
        // CORS headers
        session_start();
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $allowed_origins = [
            'http://localhost:8100',
            'https://projects.lvedel.com/dayplanner/',
            'capacitor-electron://-',
            'https://localhost'
        ];
        if (in_array($origin, $allowed_origins)) {
            header("Access-Control-Allow-Origin: $origin");
        }
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');

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
            case "choiceMove":
                $this->choiceMoveEvent();
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
        $newEventId = EventRepository::createEvent($name, $dateStart, $dateEnd, $idUser, $color);
        echo json_encode(['success' => 1, 'idEvent' => $newEventId]);
    }

    private function updateEvent()
    {
        $idUser = $_SESSION['idUser'];
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_NUMBER_INT);
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
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_NUMBER_INT);
        require_once "Repositories/EventRepository.php";
        $response = EventRepository::deleteEvent($idEvent, $idUser);
        echo json_encode(['success' => $response]);
    }

    private function eventSameTime()
    {
        $idUser = $_SESSION['idUser'];
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_VALIDATE_INT, ['options' => ['default' => null]]);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::eventSameTime($dateStart, $dateEnd, $idUser, $idEvent);
        echo json_encode(['success' => $response]);
    }

    private function choiceMoveEvent()
    {
        $idUser = $_SESSION['idUser'];
        $choice = filter_input(INPUT_POST, 'choice', FILTER_SANITIZE_NUMBER_INT);
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_NUMBER_INT);
        $oldDateStart = filter_input(INPUT_POST, 'oldDateStart', FILTER_SANITIZE_STRING);
        $oldDateEnd = filter_input(INPUT_POST, 'oldDateEnd', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING, ['options' => ['default' => null]]);
        $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING, ['options' => ['default' => null]]);

        require_once "Repositories/EventRepository.php";
        $newEventId = EventRepository::choiceMoveEvent($idUser, $choice, $idEvent, $oldDateStart, $oldDateEnd, $name, $color);

        if ($choice == 2) {
            echo json_encode(['success' => 1, 'idEvent' => $newEventId]);
        } else {
            echo json_encode(['success' => 1]);
        }
    }

}