<?php

namespace Entities ;

class RetourJSON implements \JsonSerializable {
    
    private $action;
    private $success;
    private $retour;
    
    public function __construct($action, $success, $retour) {
        $this->action = $action;
        $this->success = $success;
        $this->retour = $retour;
    }
    
    public function jsonSerialize() {
        return [
            'action' => $this->action,
            'success' => $this->success,
            'retour' => $this->retour
        ];
    }
    
    public function getAction() {
        return $this->action;
    }

    public function getSuccess() {
        return $this->success;
    }

    public function getRetour() {
        return $this->retour;
    }

        public function retourToJSON() {
        return json_encode($this, JSON_PRETTY_PRINT);
    }
    
}