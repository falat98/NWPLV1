<?php

// Definiranje sucelja iRadovi s metodama create, save i read
interface iRadovi {
    public function create($name, $text, $link, $oib);
    public function save();
    public function read();
}

