<?php

namespace Entities;

class Note implements \JsonSerializable {

    private $idNote;
    private $text;
    private $date;
    private $idUser;

    public function __construct($idNote, $text, $date,$idUser)
    {
        $this->idNote = $idNote;
        $this->text = $text;
        $this->date = $date;
        $this->idUser = $idUser;
    }

    public function getIdNote()
    {
        return $this->idNote;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function jsonSerialize() {
        return [
            'idNote' => $this->idNote,
            'text' => $this->text,
            'dateStart' => $this->date,
            'idUser' => $this->idUser,
            'session' => session_id()
        ];
    }
}