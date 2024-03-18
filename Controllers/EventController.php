<?php

namespace Controllers;

use Repositories\EventRepository;

class EventController {

    public function __construct($action)
    {
        session_start();
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
            case "delete":
                $this->deleteEvent();
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
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_STRING);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $dateStart = filter_input(INPUT_POST, 'dateStart', FILTER_SANITIZE_STRING);
        $dateEnd = filter_input(INPUT_POST, 'dateEnd', FILTER_SANITIZE_STRING);
        $color = filter_input(INPUT_POST, 'color', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::updateEvent($idEvent, $name, $dateStart, $dateEnd, $color);

        echo json_encode(['success' => $response]);
    }

    private function deleteEvent()
    {
        $idEvent = filter_input(INPUT_POST, 'idEvent', FILTER_SANITIZE_STRING);

        require_once "Repositories/EventRepository.php";
        $response = EventRepository::deleteEvent($idEvent);

        echo json_encode(['success' => $response]);
    }

}