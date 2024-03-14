<?php

// Kreiranje klase MyPDO za upravljanje s PDO vezom prema bazi podataka
class MyPDO {
    // Staticki atribut za pohranu instance klase
    protected static $instance;
    // Objekt PDO
    public $pdo;

    // Privatni konstruktor za sprjecavanje vanjskog instanciranja
    private function __construct() {
        // Postavljanje podataka za spajanje na bazu podataka
        $host = "localhost";
        $dbName = "radovi";
        $charset = "utf8";
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => FALSE,
        );
        $dsn = 'mysql:host=' . $host . ';dbname=' . $dbName . ';charset=' . $charset;
        // Stvaranje PDO objekta
        $this->pdo = new PDO($dsn, 'root', '', $opt);
    }

    // Metoda za dobivanje instance klase (Singleton pattern)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // Magic method __call za preusmjeravanje nepozvanih metoda PDO objekta
    public function __call($method, $args) {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    // Metoda za izvrsavanje SQL upita
    public function run($sql, $args = []) {
        if (!$args) {
            return $this->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    // Metoda za unistavanje instance klase
    public function destroy() {
        self::$instance = null;
        $this->pdo = null;
    }
}

?>
