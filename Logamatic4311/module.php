<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class Logamatic4311 extends IPSModule
{

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->ConnectParent("{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}");
        $this->RegisterPropertyString("Bus", "");
        
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        

    
?>