<?php

// Ukljucivanje potrebnih datoteka
include 'simple_html_dom.php'; // Ukljucivanje biblioteke za parsiranje HTML-a
include "DiplomskiRadovi.php"; // Ukljucivanje klase DiplomskiRadovi

// Definicija klase DataManager
class DataManager
{
    // Privatne varijable klase
    private $url; // URL adresa za dohvat podataka
    private $data; // Podaci
    private $htmlParser; // Objekt za parsiranje HTML-a
    private $diplomskiRadovi; // Objekt klase DiplomskiRadovi

    // Konstruktor klase
    public function __construct($url)
    {
        $this->url = $url; // Postavljanje URL adrese
        $this->htmlParser = new simple_html_dom(); // Inicijalizacija objekta za parsiranje HTML-a
        $this->diplomskiRadovi = new DiplomskiRadovi(); // Inicijalizacija objekta klase DiplomskiRadovi
    }

    // Metoda za dohvacanje podataka
    public function fetchData()
    {
        $data = []; // Inicijalizacija praznog niza podataka

        // Petlja za dohvacanje podataka sa stranica
        for ($i = 2; $i < 6; $i++) {
            $fullUrl = $this->url . $i; // Kreiranje potpune URL adrese
            $curl = curl_init($fullUrl); // Inicijalizacija cURL sesije
            curl_setopt($curl, CURLOPT_FAILONERROR, 1); // Postavljanje opcija cURL sesije
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 5);
            $result = curl_exec($curl); // Izvrsavanje cURL sesije i spremanje rezultata

            array_push($data, $result); // Dodavanje rezultata u niz podataka
            curl_close($curl); // Zatvaranje cURL sesije
        }

        $this->parseData($data); // Poziv metode za parsiranje podataka
    }

    // Privatna metoda za parsiranje podataka
    private function parseData($data)
    {
        foreach ($data as $result) {
            // Inicijalizacija nizova za URL-ove slika, OIB-ova, href atributa, naslova i tekstova
            $imagesUrl = [];
            $oibs = [];
            $hrefElements = [];
            $titleElements = [];
            $textElements = [];

            $html = $this->htmlParser->load($result); // Parsiranje HTML-a

            // Pronalazenje URL-ova slika
            foreach ($html->find('img') as $element) {
                if (strpos($element, "logos") !== false) {
                    array_push($imagesUrl, $element->src);
                }
            }

            // Pronalazenje OIB-ova
            foreach ($imagesUrl as $image) {
                $oibWithImageExtension = explode("logos/", $image);
                array_push($oibs, substr($oibWithImageExtension[1], 0, 11));
            }

            // Pronalazenje href atributa, naslova i tekstova
            foreach ($html->find('a') as $element) {
                array_push($hrefElements, $element->href);
                array_push($titleElements, $element->plaintext);
            }

            $filtered = $this->filterHrefElements($hrefElements, $titleElements); // Filtriranje href atributa i naslova
            $hrefElements = $filtered[0];
            $titleElements = $filtered[1];

            // Pronalazenje teksta i spremanje u bazu podataka
            for ($i = 0; $i < count($imagesUrl); $i++) {
                array_push($textElements, $this->getText($hrefElements[$i]));
                $this->diplomskiRadovi->create($titleElements[$i], $textElements[$i], $hrefElements[$i], $oibs[$i]);
                $this->diplomskiRadovi->save();
            }
        }
    }

    // Privatna metoda za filtriranje href atributa
    private function filterHrefElements($hrefElements, $textElements) {
        // Uklanjanje nepotrebnih elemenata
        for ($i = 0; $i <= 26; $i++) {
            unset($hrefElements[$i]);
            unset($textElements[$i]);
        }

        // Uklanjanje nepotrebnih elemenata
        for ($i = 51; $i <= 61; $i++) {
            unset($hrefElements[$i]);
            unset($textElements[$i]);
        }

        // Resetiranje indeksa
        $hrefElements = array_values($hrefElements);
        $textElements = array_values($textElements);

        $hrefFiltered = [];
        $textFiltered = [];

        // Filtriranje nepotrebnih elemenata
        for ($i = 0; $i < count($hrefElements) / 4; $i++) {
            $hrefFiltered[$i] = $hrefElements[$i * 4];
            $textFiltered[$i] = $textElements[$i * 4];
        }

        return array($hrefFiltered, $textFiltered); // Povratak filtriranih elemenata
    }

    // Privatna metoda za dohvacanje teksta
    private function getText($link) {
        $textResult = [];
        $curl = curl_init($link);
        curl_setopt($curl, CURLOPT_FAILONERROR, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        $results = curl_exec($curl);
        array_push($textResult, $results);
        curl_close($curl);

        $paragraphs = [];

        foreach ($textResult as $result) {
            $html = $this->htmlParser->load($result);
            foreach ($html->find("div.post-content") as $element) {
                foreach($element->find('p') as $paragraph){
                    $paragraphs[]=strip_tags($paragraph->innertext);
                }
            }
        }

        return implode("\n", $paragraphs); // Povratak teksta kao niza paragrafa
    }

    // Metoda za postavljanje URL adrese
    public function setUrl($url)
    {
        $this->url = $url;
    }

    // Metoda za dohvacanje objekta klase DiplomskiRadovi
    public function getDiplomskiRadovi()
    {
        return $this->diplomskiRadovi;
    }
}

?>
