<?php

namespace Controllers;

use Repositories\NoteRepository;

class NoteController {

    public function __construct($action)
    {
        session_start();
        switch ($action) {
            case "index":
                $this->traiterGetNotes();
                break;
            case "create":
                $this->createNote();
                break;
            case "update":
                $this->updateNote();
                break;
        }
    }

    private function traiterGetNotes()
    {
        $idUser = $_SESSION['idUser'];
        $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/NoteRepository.php";
        $notes = NoteRepository::getNotes($idUser, $date);

        if ($notes) {
            echo json_encode($notes, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => 0]);
        }
    }

    private function createNote()
    {
        $idUser = $_SESSION['idUser'];
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/NoteRepository.php";
        NoteRepository::createNote($text, $date, $idUser);

        echo json_encode(['success' => 1]);
    }

    private function updateNote()
    {
        $idUser = $_SESSION['idUser'];
        $text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);

        require_once "Repositories/NoteRepository.php";

        if ($text == "") {
            $response = NoteRepository::deleteNote($date, $idUser);
        } else {
            $response = NoteRepository::updateNote($text, $date, $idUser);
        }

        echo json_encode(['success' => $response]);
    }

}