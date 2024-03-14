<?php

// Ukljucivanje potrebnih datoteka
include "iRadovi.php";
include 'MyPDO.php'; 
include 'DiplomskiRadoviDBHelper.php';

// Definicija klase DiplomskiRadovi
class DiplomskiRadovi implements iRadovi
{
    // Privatne varijable klase
    private $name; // Naziv rada
    private $text; // Tekst rada
    private $link; // Link rada
    private $oib; // OIB tvrtke

    private $diplomskiPdo; // Objekt klase DiplomskiRadoviDBHelper

    // Konstruktor klase
    public function __construct()
    {
        $this->diplomskiPdo = DiplomskiRadoviDBHelper::getInstance(MyPDO::getInstance()); // Inicijalizacija objekta klase DiplomskiRadoviDBHelper
    }

    // Metoda za postavljanje podataka rada
    public function create($name, $text, $link, $oib)
    {
        $this->name = $name; // Postavljanje naziva rada
        $this->text = $text; // Postavljanje teksta rada
        $this->link = $link; // Postavljanje linka rada
        $this->oib = $oib; // Postavljanje OIB-a tvrtke
    }

    // Metoda za spremanje podataka rada u bazu
    public function save()
    {
        $this->diplomskiPdo->insert($this->name, $this->text, $this->link, $this->oib); // Poziv metode za unos podataka u bazu
    }

    // Metoda za citanje podataka rada iz baze
    public function read()
    {
        return json_encode($this->diplomskiPdo->findAll()); // Povratak JSON reprezentacije podataka dobivenih iz baze
    }

    // Metoda za zavrsetak rada s bazom
    public function finish() {
        $this->diplomskiPdo->destroy(); // Poziv metode za unistavanje veze s bazom
    }

    // Getter za naziv rada
    public function getName()
    {
        return $this->name;
    }

    // Setter za naziv rada
    public function setName($name)
    {
        $this->name = $name;
    }

    // Getter za tekst rada
    public function getText()
    {
        return $this->text;
    }

    // Setter za tekst rada
    public function setText($text)
    {
        $this->text = $text;
    }

    // Getter za link rada
    public function getLink()
    {
        return $this->link;
    }

    // Setter za link rada
    public function setLink($link)
    {
        $this->link = $link;
    }

    // Getter za OIB tvrtke
    public function getOib()
    {
        return $this->oib;
    }

    // Setter za OIB tvrtke
    public function setOib($oib)
    {
        $this->oib = $oib;
    }
}
?>
