<?php

// Definicija klase DiplomskiRadoviDBHelper
class DiplomskiRadoviDBHelper {

    // Privatne varijable klase
    protected $db; // Veza s bazom podataka
    private static $instance = null; // Instanca klase

    // Konstruktor klase
    private function __construct(MyPDO $db) {
        $this->db = $db; // Postavljanje veze s bazom podataka
    }

    // Metoda za dohvacanje svih podataka iz tablice diplomski_radovi
    public function findAll() {
        return $this->db->query("SELECT * FROM diplomski_radovi")->fetchAll(); // Izvrsavanje SQL upita za dohvacanje svih podataka iz tablice
    }

    // Metoda za unos novog reda u tablicu diplomski_radovi
    public function insert($name, $text, $link, $oib) {
        $stmt = $this->db->prepare("INSERT INTO diplomski_radovi (name, text, link, oib) VALUES (?, ?, ?, ?)"); // Priprema SQL upita za unos podataka
        $valid = $stmt->execute([$name, $text, $link, $oib]); // Izvrsavanje SQL upita s parametrima
        return $valid;
    }

    // Staticka metoda za dohvacanje instance klase
    public static function getInstance(MyPDO $db) {
        if (self::$instance == null) {
            self::$instance = new DiplomskiRadoviDBHelper($db); // stvara se nova instanca klase
        }
        return self::$instance;
    }

    // Metoda za unistavanje veze s bazom podataka
    public function destroy() {
        $this->db->destroy();
    }
}

?>

