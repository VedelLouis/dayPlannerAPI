<?php

class PdoBD {

    private static $_monPdo;
    private static $_instance = null;

    private function __construct() {
        $_serveur = 'mysql:host=' . BDConfig::$DBHOST.':3306';
        $_bdd = 'dbname=' . BDConfig::$DBNAME;
        $_user = BDConfig::$DBUSER;
        $_mdp = BDConfig::$DBPASS;
        PdoBD::$_monPdo = new \PDO($_serveur . ';' . $_bdd, $_user, $_mdp);
        PdoBD::$_monPdo->query("SET CHARACTER SET utf8");
    }

    public function _destruct() {
        PdoBD::$_monPdo = null;
    }

    public static function getInstance() {
        if (PdoBD::$_instance == null) {
            PdoBD::$_instance = new PdoBD();
        }
        return PdoBD::$_instance;
    }

    public static function getMonPdo() {
        return self::$_monPdo;
    }

}
