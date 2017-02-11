<?php

class Command extends stdClass
{
    const NUL = 0x00;
    const ONE = 0x01;
    const leer = 0x65;                  // Platzhalter der einstellbaren Parameter
    const Einstellparameter = 0xA1;     // Kommando einstellbare Parameter empfangen
    const Monitordaten = 0xA2;          // Kommando Monitordaten anfordern
    const Datenblock = 0xA3;            // Kommando Datenblock anfordern
    const Parameter = 0xB0;             // Kommando einstellbare Parameter senden
    const Normalmodus = 0xDC;           // Kommando Umschalten von Direktmodus -> Normalmodus
    const Direktmodus = 0xDD;           // Kommando Umschalten von Normalmodus -> Direktmodus

    const Heizkreis1 = 0x07;    // Datentyp für Heizkreis1 der einstellbaren Parameter
    const Heizkreis2 = 0x08;    // Datentyp für Heizkreis1 der einstellbaren Parameter
    const Heizkreis3 = 0x09;    // Datentyp für Heizkreis1 der einstellbaren Parameter
    const Heizkreis4 = 0x0A;    // Datentyp für Heizkreis4 der einstellbaren Parameter
    const Außenparameter = 0x0B;// Datentyp für Außenparameter der einstellbaren Parameter
    const Warmwasser = 0x0C;    // Datentyp für Warmwasser der einstellbaren Parameter
    const Konfiguration = 0x0D; // Datentyp für Konfiguration (Modulauswahl) der einstellbaren Parameter
    const UBA = 0x0E;           // Datentyp für UBA der einstellbaren Parameter / wandhängende Strategie (UBA)
    const Kessel = 0x10;        // Datentyp für Kessel der einstellbaren Parameter / bodenstehender Kessel
    const Kanal1 = 0x11;        // Datentyp für Schaltuhr pro Woche Kanal 1 der einstellbaren Parameter
    const Kanal2 = 0x12;        // Datentyp für Schaltuhr pro Woche Kanal 2 der einstellbaren Parameter
    const Kanal3 = 0x13;        // Datentyp für Schaltuhr pro Woche Kanal 3 der einstellbaren Parameter
    const Kanal4 = 0x14;        // Datentyp für Schaltuhr pro Woche Kanal 4 der einstellbaren Parameter
    const Kanal5 = 0x15;        // Datentyp für Schaltuhr pro Woche Kanal 5 der einstellbaren Parameter
    const Heizkreis5 = 0x16;    // Datentyp für Heizkreis 5 der einstellbaren Parameter
    const Kanal6 = 0x17;        // Datentyp für Kanal 6 der einstellbaren Parameter / Schaltuhr pro Woche Kanal 6
    const Heizkreis6 = 0x18;    // Datentyp für Heizkreis 6 der einstellbaren Parameter
    const Kanal7 = 0x19;        // Datentyp für Kanal 7 der einstellbaren Parameter / Schaltuhr pro Woche Kanal 7
    const Heizkreis7 = 0x1A;    // Datentyp für Heizkreis 7 der einstellbaren Parameter
    const Kanal8 = 0x1B;        // Datentyp für Kanal 8 der einstellbaren Parameter / Schaltuhr pro Woche Kanal 8
    const Heizkreis8 = 0x1C;    // Datentyp für Heizkreis 8 der einstellbaren Parameter
    const Kanal9 = 0x1D;        // Datentyp für Kanal 9 der einstellbaren Parameter / Schaltuhr pro Woche Kanal 9
    const Kanal10 = 0x1F;       // Datentyp für Kanal 10 der einstellbaren Parameter / Schaltuhr pro Woche Kanal 10
    const Strategie = 0x20;     // Datentyp für Strategie der einstellbaren Parameter / bodenstehende Strategie
    const Solar = 0x24;         // Datentyp für Solar der einstellbaren Parameter 
    const FM458 = 0x26;         // Datentyp für FM458 der einstellbaren Parameter / Strategie (FM458)
    
    const Fehlerprotokoll = 0x87; 
}
function Buderus ($typ, $offset, $value)
    {
    $Buderus[7][-1] = array ("Heizkreis 1 / einstellbare Werte", "64");
        $Buderus[7][0] = array ("", "");
        $Buderus[7][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[7][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[7][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[7][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[7][5] = array ("", 16);
        $Buderus[7][16] = array ("", "");
        $Buderus[7][17] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[7][19] = array ("", 56);
        $Buderus[7][56] = array ("", "");
        $Buderus[7][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[7][58] = array ("", 61);
        $Buderus[7][61] = array ("", "");
        $Buderus[7][62] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[7][63] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[7][64] = array ("", "");
    $Buderus[8][-1] = array ("Heizkreis 2 / einstellbare Werte", "64");
        $Buderus[8][0] = array ("", "");
        $Buderus[8][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[8][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[8][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[8][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[8][5] = array ("", 17);
        $Buderus[8][17] = array ("", "");
        $Buderus[8][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[8][19] = array ("", 56);
        $Buderus[8][56] = array ("", "");
        $Buderus[8][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[8][58] = array ("", 61);
        $Buderus[8][61] = array ("", "");
        $Buderus[8][62] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[8][63] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[8][64] = array ("", "");
    $Buderus[9][-1] = array ("Heizkreis 3 / einstellbare Werte", "65");
        $Buderus[9][0] = array ("", "");
        $Buderus[9][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[9][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[9][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[9][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[9][5] = array ("", 17);
        $Buderus[9][17] = array ("", "");
        $Buderus[9][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[9][19] = array ("", 56);
        $Buderus[9][56] = array ("", "");
        $Buderus[9][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[9][58] = array ("", 62);
        $Buderus[9][62] = array ("", "");
        $Buderus[9][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[9][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[9][65] = array ("", "");
    $Buderus[10][-1] = array ("Heizkreis 4 / einstellbare Werte", "65");
        $Buderus[10][0] = array ("", "");
        $Buderus[10][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[10][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[10][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[10][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[10][5] = array ("", 17);
        $Buderus[10][17] = array ("", "");
        $Buderus[10][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[10][19] = array ("", 56);
        $Buderus[10][56] = array ("", "");
        $Buderus[10][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[10][58] = array ("", 62);
        $Buderus[10][62] = array ("", "");
        $Buderus[10][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[10][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[10][65] = array ("", "");
    $Buderus[11][-1] = array ("Außenparameter");
        $Buderus[11][0] = array ("", "");
    $Buderus[12][-1] = array ("Warmwasser / einstellbare Werte", "29");
        $Buderus[12][0] = array ("", "");
        $Buderus[12][1] = array ("thermische Desinfektion", "Modul", "AUS", "EIN");
        $Buderus[12][2] = array ("Warmwassersolltemperatur für die Zeit der thermischen Desinfektion", "Temp", "1", "°C");
        $Buderus[12][3] = array ("Desinfektionstag", "Modul", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag", "täglich");
        $Buderus[12][4] = array ("Uhrzeit an der die thermische Desinfektion starten soll", "ZeitHours", "1", "Std");
        $Buderus[12][5] = array ("", 9);
        $Buderus[12][9] = array ("", "");
        $Buderus[12][10] = array ("Warmwassersolltemperatur", "Temp", "1", "°C");
        $Buderus[12][11] = array ("", 13);
        $Buderus[12][13] = array ("", "");
        $Buderus[12][14] = array ("Betriebsart Warmwasser", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[12][15] = array ("", 18);
        $Buderus[12][18] = array ("", "");
        $Buderus[12][19] = array ("Zirkulationspumpenläufe pro Stunde", "Modul", "ständig aus (läuft nur bei Einmalladung)", "ständig an", "2", "3", "4", "5", "6", "7");
        $Buderus[12][20] = array ("", "");
        $Buderus[12][21] = array ("Betriebsart Zirkulation", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[12][22] = array ("", 27);
        $Buderus[12][27] = array ("", "");
        $Buderus[12][28] = array ("Uhrzeit zu der die tägliche Aufheizung gestartet wird", "ZeitHours", "1", "Std");
        $Buderus[12][29] = array ("", "");
    $Buderus[13][-1] = array ("Konfiguration / einstellbare Werte");
    $Buderus[14][-1] = array ("UBA");
    $Buderus[16][-1] = array ("Kessel / einstellbare Werte", "75");
        $Buderus[16][0] = array ("", "11");
        $Buderus[16][10] = array ("", "");
        $Buderus[16][11] = array ("Abgastemperatur Grenze", "Temp", "5", "°C");
        $Buderus[16][12] = array ("", 65);
        $Buderus[16][65] = array ("", "");
        $Buderus[16][66] = array ("Lastbegrenzung 2x1-stufiger Brenner", "Temp", "1", "°C");
        $Buderus[16][67] = array ("", 72);
        $Buderus[12][72] = array ("", "");
        $Buderus[12][73] = array ("Nachtabsenkung Kesselkennlinie", "Temp4", "1", "K");
        $Buderus[12][74] = array ("Betriebsart Kesselkennlinie", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[12][75] = array ("", "");
    $Buderus[17][-1] = array ("Kanal 1", "4");
        $Buderus[17][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[17][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[17][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[17][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[17][4] = array ("", "");
    $Buderus[18][-1] = array ("Kanal 2", "4");
        $Buderus[18][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[18][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[18][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[18][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[18][4] = array ("", "");
    $Buderus[19][-1] = array ("Kanal 3", "4");
        $Buderus[19][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[19][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[19][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[19][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[19][4] = array ("", "");
    $Buderus[20][-1] = array ("Kanal 4", "4");
        $Buderus[20][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[20][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[20][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[20][3] = array ("Ferien", "ZeitDays", "1", "Tage");
    $Buderus[21][-1] = array ("Kanal 5", "4");
        $Buderus[21][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[21][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[21][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[21][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[21][4] = array ("", "");
    $Buderus[22][-1] = array ("Heizkreis 5 / einstellbare Werte", "65");
        $Buderus[22][0] = array ("", "");
        $Buderus[22][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[22][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[22][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[22][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[22][5] = array ("", 17);
        $Buderus[22][17] = array ("", "");
        $Buderus[22][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[22][19] = array ("", 56);
        $Buderus[22][56] = array ("", "");
        $Buderus[22][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[22][58] = array ("", 62);
        $Buderus[22][62] = array ("", "");
        $Buderus[22][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[22][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[22][65] = array ("", "");
    $Buderus[23][-1] = array ("Kanal 6", "4");
        $Buderus[23][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[23][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[23][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[23][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[23][4] = array ("", "");
    $Buderus[24][-1] = array ("Heizkreis 6 / einstellbare Werte", "65");
        $Buderus[24][0] = array ("", "");
        $Buderus[24][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[24][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[24][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[24][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[24][5] = array ("", 17);
        $Buderus[24][17] = array ("", "");
        $Buderus[24][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[24][19] = array ("", 56);
        $Buderus[24][56] = array ("", "");
        $Buderus[24][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[24][58] = array ("", 62);
        $Buderus[24][62] = array ("", "");
        $Buderus[24][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[24][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[24][65] = array ("", "");
    $Buderus[25][-1] = array ("Kanal 7", "4");
        $Buderus[25][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[25][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[25][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[25][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[25][4] = array ("", "");
    $Buderus[26][-1] = array ("Heizkreis 7 / einstellbare Werte", "65");
        $Buderus[26][0] = array ("", "");
        $Buderus[26][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[26][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[26][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[26][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[26][5] = array ("", 17);
        $Buderus[26][17] = array ("", "");
        $Buderus[26][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[26][19] = array ("", 56);
        $Buderus[26][56] = array ("", "");
        $Buderus[26][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[26][58] = array ("", 62);
        $Buderus[26][62] = array ("", "");
        $Buderus[26][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[26][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[26][65] = array ("", "");
    $Buderus[27][-1] = array ("Kanal 8", "4");
        $Buderus[27][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[27][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[27][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[27][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[27][4] = array ("", "");
    $Buderus[28][-1] = array ("Heizkreis 8 / einstellbare Werte", "65");
        $Buderus[28][0] = array ("", "");
        $Buderus[28][1] = array ("Sommer / Winter – Umschaltschwelle", "Temp", "1", "°C");
        $Buderus[28][2] = array ("Nachtraumsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[28][3] = array ("Tagsolltemperatur", "Temp", "0.5", "°C");
        $Buderus[28][4] = array ("Betriebsart", "HKBetriebsart", "Manuell Nacht", "Manuell Tag", "Automatik");
        $Buderus[28][5] = array ("", 17);
        $Buderus[28][17] = array ("", "");
        $Buderus[28][18] = array ("Auslegungstemperatur Heizkreis", "Temp", "1", "°C");
        $Buderus[28][19] = array ("", 56);
        $Buderus[28][56] = array ("", "");
        $Buderus[28][57] = array ("Heizsystem", "HKHeizsystem", "kein Heizsystem", "Heizkörper", "Konvektor", "Fussboden", "Fusspunkt", "konstant", "Raumregler", "EIB");
        $Buderus[28][58] = array ("", 62);
        $Buderus[28][62] = array ("", "");
        $Buderus[28][63] = array ("Absenkart Ferien", "HKAbsenkart", "Abschalt (Frostschutz bleibt aktiv)", "Reduziert", "Raumhalt", "Außenhalt");
        $Buderus[28][64] = array ("Umschalttemperatur für Absenkart Außenhalt bei Ferienbetrieb", "Temp", "1", "°C");
        $Buderus[28][65] = array ("", "");
    $Buderus[29][-1] = array ("Kanal 9", "4");
        $Buderus[29][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[29][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[29][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[29][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[29][4] = array ("", "");
    $Buderus[30][-1] = array ("");
    $Buderus[31][-1] = array ("Kanal 10 / Zirkulation", "4");
        $Buderus[31][0] = array ("festgelegte Schaltprogramme", "Modul", "Eigen", "Frueh", "Spaet", "Nachmittag", "Mittag", "Single", "Senior", "LEER");
        $Buderus[31][1] = array ("Pause", "ZeitHours", "1", "Std");
        $Buderus[31][2] = array ("Party", "ZeitHours", "1", "Std");
        $Buderus[31][3] = array ("Ferien", "ZeitDays", "1", "Tage");
        $Buderus[31][4] = array ("", "");
    $Buderus[32][-1] = array ("Strategie");
    $Buderus[35][-1] = array ("");
    $Buderus[36][-1] = array ("Solarfunktion / einstellbare Werte", "29");
        $Buderus[36][0] = array ("", 3);
        $Buderus[36][3] = array ("");
        $Buderus[36][4] = array ("Betriebsart", "HKBetriebsart", "Aus", "Automatik", "EIN");
        $Buderus[36][5] = array ("Umschaltung für Verbraucher", "HKBetriebsart", "Automatisch", "nur Speicher 1", "nur Speicher 2");
        $Buderus[36][6] = array ("Maximaltemperatur Speicher 1", "Temp", "1", "°C");
        $Buderus[36][7] = array ("", 17);
        $Buderus[36][17] = array ("");
        $Buderus[36][18] = array ("Maximaltemperatur Speicher 2", "Temp", "1", "°C");
        $Buderus[36][19] = array ("", 27);
        $Buderus[36][27] = array ("");
        $Buderus[36][28] = array ("Glykolanteil", "Prozent", "10", "%");
        $Buderus[36][29] = array ("");
    $Buderus[37][-1] = array ("");
    $Buderus[38][-1] = array ("FM458");
    
    $Buderus[128][-1] = array ("Heizkreis 1", "17");
        $Buderus[128][0] = array ("Betriebswerte", "Bit", "Ausschaltoptimierung", "Einschaltoptimierung", "Automatik", "Warmwasservorrang", "Estrichtrocknung", "Ferien", "Frostschutz", "Manuell");
        $Buderus[128][1] = array ("Betriebswerte 2", "Bit", "Sommer", "Tag", "keine Kommunikation mit FB", "FB fehlerhaft", "Fehler Vorlauffühler", "maximaler Vorlauf", "externer Störeingang", "Party / Pause");
        $Buderus[128][2] = array ("Vorlaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[128][3] = array ("Vorlaufistwert", "Temp", "1", "°C");
        $Buderus[128][4] = array ("Raumsollwert", "Temp", "0.5", "°C");
        $Buderus[128][5] = array ("Raumistwert", "Temp", "0.5", "°C");
        $Buderus[128][6] = array ("Einschaltoptimierung", "Zeit", "0.5", "min");
        $Buderus[128][7] = array ("Ausschaltoptimierung", "Zeit", "1", "min");
        $Buderus[128][8] = array ("Pumpe", "Prozent", "1", "%");
        $Buderus[128][9] = array ("Stellglied", "Prozent", "1", "%");
        $Buderus[128][10] = array ("HK- Eingang", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[128][11] = array ("", "");
        $Buderus[128][12] = array ("Heizkennlinie + 10 °C", "Temp", "1", "°C");
        $Buderus[128][13] = array ("Heizkennlinie 0 °C", "Temp", "1", "°C");
        $Buderus[128][14] = array ("Heizkennlinie - 10 °C", "Temp", "1", "°C");
        $Buderus[128][15] = array ("", "");
        $Buderus[128][16] = array ("", "");
        $Buderus[128][17] = array ("", "");

        $Buderus[129][-1] = array ("Heizkreis 2", "17");
        $Buderus[129][0] = array ("Betriebswerte", "Bit", "Ausschaltoptimierung", "Einschaltoptimierung", "Automatik", "Warmwasservorrang", "Estrichtrocknung", "Ferien", "Frostschutz", "Manuell");
        $Buderus[129][1] = array ("Betriebswerte 2", "Bit", "Sommer", "Tag", "keine Kommunikation mit FB", "FB fehlerhaft", "Fehler Vorlauffühler", "maximaler Vorlauf", "externer Störeingang", "Party / Pause");
        $Buderus[129][2] = array ("Vorlaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[129][3] = array ("Vorlaufistwert", "Temp", "1", "°C");
        $Buderus[129][4] = array ("Raumsollwert", "Temp", "0.5", "°C");
        $Buderus[129][5] = array ("Raumistwert", "Temp", "0.5", "°C");
        $Buderus[129][6] = array ("Einschaltoptimierung", "Zeit", "0.5", "min");
        $Buderus[129][7] = array ("Ausschaltoptimierung", "Zeit", "1", "min");
        $Buderus[129][8] = array ("Pumpe", "Prozent", "1", "%");
        $Buderus[129][9] = array ("Stellglied", "Prozent", "1", "%");
        $Buderus[129][10] = array ("HK- Eingang", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[129][11] = array ("", "");
        $Buderus[129][12] = array ("Heizkennlinie + 10 °C", "Temp", "1", "°C");
        $Buderus[129][13] = array ("Heizkennlinie 0 °C", "Temp", "1", "°C");
        $Buderus[129][14] = array ("Heizkennlinie - 10 °C", "Temp", "1", "°C");
        $Buderus[129][15] = array ("", "");
        $Buderus[129][16] = array ("", "");
        $Buderus[129][17] = array ("", "");

    $Buderus[130][-1] = array ("Heizkreis 3", "17");
        $Buderus[130][0] = array ("Betriebswerte", "Bit", "Ausschaltoptimierung", "Einschaltoptimierung", "Automatik", "Warmwasservorrang", "Estrichtrocknung", "Ferien", "Frostschutz", "Manuell");
        $Buderus[130][1] = array ("Betriebswerte 2", "Bit", "Sommer", "Tag", "keine Kommunikation mit FB", "FB fehlerhaft", "Fehler Vorlauffühler", "maximaler Vorlauf", "externer Störeingang", "Party / Pause");
        $Buderus[130][2] = array ("Vorlaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[130][3] = array ("Vorlaufistwert", "Temp", "1", "°C");
        $Buderus[130][4] = array ("Raumsollwert", "Temp", "0.5", "°C");
        $Buderus[130][5] = array ("Raumistwert", "Temp", "0.5", "°C");
        $Buderus[130][6] = array ("Einschaltoptimierung", "Zeit", "0.5", "min");
        $Buderus[130][7] = array ("Ausschaltoptimierung", "Zeit", "1", "min");
        $Buderus[130][8] = array ("Pumpe", "Prozent", "1", "%");
        $Buderus[130][9] = array ("Stellglied", "Prozent", "1", "%");
        $Buderus[130][10] = array ("HK- Eingang", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[130][11] = array ("", "");
        $Buderus[130][12] = array ("Heizkennlinie + 10 °C", "Temp", "1", "°C");
        $Buderus[130][13] = array ("Heizkennlinie 0 °C", "Temp", "1", "°C");
        $Buderus[130][14] = array ("Heizkennlinie - 10 °C", "Temp", "1", "°C");
        $Buderus[130][15] = array ("", "");
        $Buderus[130][16] = array ("", "");
        $Buderus[130][17] = array ("", "");

    $Buderus[131][-1] = array ("Heizkreis 4", "17");
        $Buderus[131][0] = array ("Betriebswerte", "Bit", "Ausschaltoptimierung", "Einschaltoptimierung", "Automatik", "Warmwasservorrang", "Estrichtrocknung", "Ferien", "Frostschutz", "Manuell");
        $Buderus[131][1] = array ("Betriebswerte 2", "Bit", "Sommer", "Tag", "keine Kommunikation mit FB", "FB fehlerhaft", "Fehler Vorlauffühler", "maximaler Vorlauf", "externer Störeingang", "Party / Pause");
        $Buderus[131][2] = array ("Vorlaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[131][3] = array ("Vorlaufistwert", "Temp", "1", "°C");
        $Buderus[131][4] = array ("Raumsollwert", "Temp", "0.5", "°C");
        $Buderus[131][5] = array ("Raumistwert", "Temp", "0.5", "°C");
        $Buderus[131][6] = array ("Einschaltoptimierung", "Zeit", "0.5", "min");
        $Buderus[131][7] = array ("Ausschaltoptimierung", "Zeit", "1", "min");
        $Buderus[131][8] = array ("Pumpe", "Prozent", "1", "%");
        $Buderus[131][9] = array ("Stellglied", "Prozent", "1", "%");
        $Buderus[131][10] = array ("HK- Eingang", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[131][11] = array ("", "");
        $Buderus[131][12] = array ("Heizkennlinie + 10 °C", "Temp", "1", "°C");
        $Buderus[131][13] = array ("Heizkennlinie 0 °C", "Temp", "1", "°C");
        $Buderus[131][14] = array ("Heizkennlinie - 10 °C", "Temp", "1", "°C");
        $Buderus[131][15] = array ("", "");
        $Buderus[131][16] = array ("", "");
        $Buderus[131][17] = array ("", "");

    $Buderus[132][-1]= array ("Warmwasser", "11");
        $Buderus[132][0] = array ("Betriebswerte 1", "Bit", "Automatik", "Desinfektion", "Desinfektion", "Ferien", "Fehler Desinfektion", "Fehler Fühler", "Fehler WW bleibt kalt", "Fehler Anode");
        $Buderus[132][1] = array ("Betriebswerte 2", "Bit", "Laden", "Manuell", "Nachladen", "Ausschaltoptimierung", "Einschaltoptimierung", "Tag", "Warm", "Vorrang");
        $Buderus[132][2] = array ("Warmwassersolltemperatur", "Temp", "1", "°C");
        $Buderus[132][3] = array ("Warmwasseristtemperatur", "Temp", "1", "°C");
        $Buderus[132][4] = array ("Einschaltoptimierungszeit", "Zeit", "1", "min");
        $Buderus[132][5] = array ("Bit-Pumpe", "Bit", "Ladepumpe", "Zirkulationspumpe", "Absenkung Solar", "", "", "", "", "");
        $Buderus[132][6] = array ("WW-Eingang", "Bit", "Eingang 2", "Eingang 3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[132][7] = array ("Betriebswerte 3", "Bit", "Fehler extern", "", "", "", "", "", "", "");
        $Buderus[132][8] = array ("Bit-Zirkulationspumpe", "Bit", "Tag", "Automatik", "Ferien", "Einmal", "", "", "", "");
        $Buderus[132][9] = array ("", "");
        $Buderus[132][10] = array ("", "");
        $Buderus[132][11] = array ("", "");

    $Buderus[135][-1] = array ("Fehlerprotokoll", "0");
    $Buderus[135][0] = array ("","");
    $Buderus[135][1] = array ("","");
    $Buderus[135][2] = array ("","");
    $Buderus[135][3] = array ("","");
    $Buderus[135][4] = array ("","");
    $Buderus[135][5] = array ("","");
    $Buderus[135][6] = array ("","");
    $Buderus[135][7] = array ("","");
    $Buderus[135][8] = array("","");
    $Buderus[135][9] = array ("","");
    $Buderus[135][10] = array ("","");
    $Buderus[135][11] = array ("","");
    $Buderus[135][12] = array ("","");
    $Buderus[135][13] = array ("","");
    $Buderus[135][14] = array ("","");
    $Buderus[135][15] = array ("","");
    $Buderus[135][16] = array ("","");
    $Buderus[135][17] = array ("","");
    $Buderus[135][18] = array ("","");
    $Buderus[135][19] = array ("","");
    $Buderus[135][20] = array ("","");
    $Buderus[135][21] = array ("","");
    $Buderus[135][22] = array ("","");
    $Buderus[135][23] = array ("","");
    $Buderus[135][24] = array ("","");
    $Buderus[135][25] = array ("","");
    $Buderus[135][26] = array ("","");
    $Buderus[135][27] = array ("","");
    $Buderus[135][28] = array ("","");
    $Buderus[135][29] = array ("","");
    $Buderus[135][30] = array ("","");
    $Buderus[135][31] = array ("","");
    $Buderus[135][32] = array ("","");
    $Buderus[135][33] = array ("","");
    $Buderus[135][34] = array ("","");
    $Buderus[135][35] = array ("","");
    $Buderus[135][36] = array ("","");
    $Buderus[135][37] = array ("","");
    $Buderus[135][38] = array ("","");
    $Buderus[135][39] = array ("","");
    $Buderus[135][40] = array ("","");
    $Buderus[135][41] = array ("","");

    $Buderus[136][-1] = array ("bodenstehender Kessel", "41");
        $Buderus[136][0] = array ("Kesselvorlauf-Solltemperatur", "Temp", "1", "°C");
        $Buderus[136][1] = array ("Kesselvorlauf-Isttemperatur", "Temp", "1", "°C");
        $Buderus[136][2] = array ("Brennereinschalttemperatur", "Temp", "1", "°C");
        $Buderus[136][3] = array ("Brennerausschalttemperratur", "Temp", "1", "°C");
        $Buderus[136][4] = array ("Kesselintegral_HB", "KKs", "1", "KKs");
        $Buderus[136][5] = array ("Kesselintegral_LB", "KKs", "1", "KKs");
        $Buderus[136][6] = array ("Kessel Bit Fehler", "Bit", "Brennerstörung", "Kesselfühler", "Zusatzfühler", "Kessel bleibt kalt", "Abgas Fühler", "Abgas über Grenzwert", "Sicherheitskette ausgelöst", "externe Störung");
        $Buderus[136][7] = array ("Kessel Bit Betrieb", "Bit", "Abgastest", "Betriebsstunden 1. Stufe", "Kesselschutz", "unter Betrieb", "Leistung frei", "Leistung hoch", "Betriebsstunden 2. Stufe", "frei");
        $Buderus[136][8] = array ("Brenner Ansteuerung", "Bit", "Kessel aus", "1. Stufe an", "2.Stufe an bzw. Modulation frei", "frei", "frei", "frei", "frei", "frei");
        $Buderus[136][9] = array ("Abgastemperatur", "Temp", "1", "°C");
        $Buderus[136][10] = array ("Mod. Brenner Stellglied", "Prozent", "1", "%");
        $Buderus[136][11] = array ("Mod. Brenner Ist Leistung", "Prozent", "1", "%");
        $Buderus[136][12] = array ("Brennerlaufzeit 1. Stufe", "Betr", "65536", "h");
        $Buderus[136][13] = array ("Brennerlaufzeit 1. Stufe", "Betr", "256", "h");
        $Buderus[136][14] = array ("Brennerlaufzeit 1. Stufe", "Betr", "1", "h");
        $Buderus[136][15] = array ("Brennerlaufzeit 2. Stufe", "Betr", "65536", "h");
        $Buderus[136][16] = array ("Brennerlaufzeit 2. Stufe", "Betr", "256", "h");
        $Buderus[136][17] = array ("Brennerlaufzeit 2. Stufe", "Betr", "1", "h");
        $Buderus[136][18] = array ("Brennerstarts 1. Stufe", "Start", "65536", "");
        $Buderus[136][19] = array ("Brennerstarts 1. Stufe", "Start", "256", "");
        $Buderus[136][20] = array ("Brennerstarts 1. Stufe", "Start", "1", "");
        $Buderus[136][21] = array ("Brennerstarts 2. Stufe", "Start", "65536", "");
        $Buderus[136][22] = array ("Brennerstarts 2. Stufe", "Start", "256", "");
        $Buderus[136][23] = array ("Brennerstarts 2. Stufe", "Start", "1", "");
        $Buderus[136][24] = array ("Kesselpumpe", "Prozent", "1", "%");
        $Buderus[136][25] = array ("max. Ansteuerung HK-Stellglied", "Prozent", "1", "%");
        $Buderus[136][26] = array ("Kessel Stellglied Eigen", "Prozent", "1", "%");
        $Buderus[136][27] = array ("Kesselrücklaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[136][28] = array ("Kesselvorlauf-Isttemperatur", "Temp", "1", "°C");
        $Buderus[136][29] = array ("Zusatzfühler-Isttemperatur", "Temp", "1", "°C");
        $Buderus[136][30] = array ("max. Abgastemperatur", "Temp", "1", "°C");
        $Buderus[136][31] = array ("Vorlaufsolltemperatur des Kesselintegral´s", "Temp", "1", "°C");
        $Buderus[136][32] = array ("Änderung Kesselvorlauf-Isttemperatur", "Temp2", "256", "°C");
        $Buderus[136][33] = array ("Änderung Kesselvorlauf-Isttemperatur", "Temp2", "1", "°C");
        $Buderus[136][34] = array ("Kessel Bit Fehler", "Bit", "Abgastest", "Brenner runter / aus", "Brenner Auto", "Brenner 1. Stufe", "Brenner 2. Stufe", "Handschalter Modul in Stellung „0“", "Handschalter Modul in Stellung „HAND“", "Handschalter Modul in Stellung „AUT“");
        $Buderus[136][35] = array ("Ansteuersignal für Kesselkreispumpe / Zubringerpumpe", "Prozent", "1", "%");
        $Buderus[136][36] = array ("Kessel Betrieb", "Bit", "Tag", "Automatik", "Sommer", "frei", "frei", "frei", "frei", "frei");
        $Buderus[136][37] = array ("Vorlaufsolltemperatur der Kesselkennlinie","Temp", "1", "°C");
        $Buderus[136][38] = array ("Heizkennlinie +10 Grad","Temp", "1", "°C");
        $Buderus[136][39] = array ("Heizkennlinie 0 Grad","Temp", "1", "°C");
        $Buderus[136][40] = array ("Heizkennlinie -10 Grad","Temp", "1", "°C");
        $Buderus[136][41] = array ("Kessel Betrieb", "Bit", "Folgeumkehr ( 2 – 1 )", "Lastbegrenzung", "frei", "frei", "frei", "frei", "frei", "frei");

        $Buderus[137][-1] = array ("Konfiguration", "23");
        $Buderus[137][0] = array ("Außentemperatur", "Temp3", "1", "°C");
        $Buderus[137][1] = array ("gedämpfte Außentemperatur", "Temp3", "1", "°C");
        $Buderus[137][2] = array ("Version", "Version", "", "");
        $Buderus[137][3] = array ("Version", "Version", "", "");
        $Buderus[137][4] = array ("", "");
        $Buderus[137][5] = array ("", "");
        $Buderus[137][6] = array ("Modul in Slot 1", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM422", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
        $Buderus[137][7] = array ("Modul in Slot 2", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM422", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
        $Buderus[137][8] = array ("Modul in Slot 3", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM422", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
        $Buderus[137][9] = array ("Modul in Slot 4", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM422", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
        $Buderus[137][10] = array ("Modul in Slot A", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM422", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
        $Buderus[137][11] = array ("", "");
        $Buderus[137][12] = array ("Fehler bei Slot 1", "Modul", "kein Fehler", "unbekanntes Modul", "Fehler bei CAN – Adresse", "SOLL // IST – Fehler", "keine Antwort", "Handbetrieb");
        $Buderus[137][13] = array ("Fehler bei Slot 2", "Modul", "kein Fehler", "unbekanntes Modul", "Fehler bei CAN – Adresse", "SOLL // IST – Fehler", "keine Antwort", "Handbetrieb");
        $Buderus[137][14] = array ("Fehler bei Slot 3", "Modul", "kein Fehler", "unbekanntes Modul", "Fehler bei CAN – Adresse", "SOLL // IST – Fehler", "keine Antwort", "Handbetrieb");
        $Buderus[137][15] = array ("Fehler bei Slot 4", "Modul", "kein Fehler", "unbekanntes Modul", "Fehler bei CAN – Adresse", "SOLL // IST – Fehler", "keine Antwort", "Handbetrieb");
        $Buderus[137][16] = array ("Fehler bei Slot A", "Modul", "kein Fehler", "unbekanntes Modul", "Fehler bei CAN – Adresse", "SOLL // IST – Fehler", "keine Antwort", "Handbetrieb");
        $Buderus[137][17] = array ("", "");
        $Buderus[137][18] = array ("Anlagenvorlaufsolltemperatur", "Temp", "1", "°C");
        $Buderus[137][19] = array ("Anlagenvorlaufisttemperatur", "Temp", "1", "°C");
        $Buderus[137][20] = array ("Betriebsflags der Anlage", "Bit", "Pufferspeicher bleibt kalt", "Fühler UST-FK defekt", "Wartezeit läuft");
        $Buderus[137][21] = array ("max. Ansteuerung für Heizkreispumpe", "Prozent", "1", "%");
        $Buderus[137][22] = array ("max. Ansteuerung für Stellglied", "Prozent", "1", "%");
        $Buderus[137][23] = array ("Regelgerätevorlaufisttemperatur", "Temp", "1", "°C");

    $Buderus[144][-1] = array ("LAP", "17");
        $Buderus[144][0] = array ("Betriebswerte 1", "Bit", "Automatik", "Desinfektion", "Einmal-Ladung", "Ferien", "Fehler Desinfektion", "Fehler Fühler", "Fehler WW bleibt kalt", "Fehler Anode");
        $Buderus[144][1] = array ("Betriebswerte 2", "Bit", "Laden durch Kesselanforderung", "Manuell", "Laden durch Restwärmenutzung", "Ausschaltoptimierung", "Einschaltoptimierung", "Tag", "Warm", "Vorrang");
        $Buderus[144][2] = array ("Warmwassersolltemperatur", "Temp", "1", "°C");
        $Buderus[144][3] = array ("Warmwasseristtemperatur", "Temp", "1", "°C");
        $Buderus[144][4] = array ("Einschaltoptimierungszeit", "Zeit", "1", "min");
        $Buderus[144][5] = array ("Bit-Pumpe", "Bit", "Ladepumpe", "Zirkulationspumpe", "Absenkung Solar", "", "", "", "", "");
        $Buderus[144][6] = array ("WW-Eingang", "Bit", "", "", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[144][7] = array ("Betriebswerte 3", "Bit", "externe Störmeldung Pumpe WF1-2", "Fehler Fühler unten", "Fehler Fühler Wärmetauscher", "Verkalkungsschutz aktiv", "Fehler Umschaltventil WW UBA", "", "", "");
        $Buderus[144][8] = array ("Bit-Zirkulationspumpe", "Bit", "Tag", "Automatik", "Ferien", "Einmal-Lauf 3 min", "", "", "", "");
        $Buderus[144][9] = array ("WF-Eingänge/Handschalter", "Bit", "Eingang 2", "Eingang 3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[144][10] = array ("Start Ladung", "Temp", "1", "°C");
        $Buderus[144][11] = array ("Ende Ladung", "Temp", "1", "°C");
        $Buderus[144][12] = array ("Isttemperatur Speicher unten", "Temp", "1", "°C");
        $Buderus[144][13] = array ("Isttemperatur Wärmetauscher", "Temp", "1", "°C");
        $Buderus[144][14] = array ("Sollstellung Mischer",  "Prozent", "1", "%");
        $Buderus[144][15] = array ("Iststellung Mischer",  "Prozent", "1", "%");
        $Buderus[144][16] = array ("Ansteuerung Primärpumpe",  "Prozent", "1", "%");
        $Buderus[144][17] = array ("Ansteuerung Sekundärpumpe",  "Prozent", "1", "%");

    $Buderus[154][-1] = array ("Imaginäres Modul", "0");
    $Buderus[154][0] = array ("","");


    $Buderus[158][-1] = array ("Solarfunktion", "32");
        $Buderus[158][0] = array ("Betriebswerte 1", "Bit", "Fehler Einstellung Hysterese", "Speicher 2 auf max. Temperatur", "Speicher 1 auf max. Temperatur", "Kollektor auf max. Temperatur", "", "", "", "");
        $Buderus[158][1] = array ("Betriebswerte 2", "Bit", "Fehler Fühler Anlagenrücklauf Bypass defekt", "Fehler Fühler Speichermitte Bypass defekt", "Fehler Volumenstromzähler WZ defekt", "Fehler Fühler Rücklauf WZ defekt", "Fehler Fühler Vorlauf WZ defekt", "Fehler Fühler Speicher-unten 2 defekt", "Fehler Fühler Speicher-unten 1 defekt", "Fehler Fühler Kollektor defekt");
        $Buderus[158][2] = array ("Betriebswerte 3", "Bit", "Umschaltventil Speicher 2 zu", "Umschaltventil Speicher 2 auf/Speicherladepumpe2", "Umschaltventil Bypass zu", "Umschaltventil Bypass auf", "Sekundärpumpe Speicher 2 Betrieb", "", "", "");
        $Buderus[158][3] = array ("Kollektortemperatur", "Solar", "0.1", "°C");
        $Buderus[158][4] = array ("Kollektortemperatur", "Solar", "0.1", "°C");
        $Buderus[158][5] = array ("Modulation Pumpe Speicher", "Prozent", "1", "%");
        $Buderus[158][6] = array ("Warmwassertemperatur Speicher 1 unten", "Temp", "1", "°C");
        $Buderus[158][7] = array ("Betriebsstatus Speicher 1", "Modul", "Gesperrt", "zu wenig solarer Ertrag", "Low Flow", "High Flow", "HAND ein", "Umschalt-Check", "", "");
        $Buderus[158][8] = array ("Warmwassertemperatur Speicher 2 unten", "Temp", "1", "°C");
        $Buderus[158][9] = array ("Betriebsstatus Speicher 2", "Modul", "Gesperrt", "zu wenig solarer Ertrag", "Low Flow", "High Flow", "HAND ein", "Umschalt-Check", "", "");
        $Buderus[158][10] = array ("Warmwassertemperatur Speichermitte (Bypass)", "Temp", "1", "°C");
        $Buderus[158][11] = array ("Anlagenrücklauftemperatur (Bypass)", "1", "°C");
        $Buderus[158][12] = array ("Vorlauftemperatur Wärmemengenzähler", "Temp", "1", "°C");
        $Buderus[158][13] = array ("Rücklauftemperatur Wärmemengenzähler", "Temp", "1", "°C");
        $Buderus[158][14] = array ("Anlagen-Volumenstrom", "Flow", "256", "l/h");
        $Buderus[158][15] = array ("Anlagen-Volumenstrom", "Flow", "1", "l/h");
        $Buderus[158][16] = array ("Momentan-Leistung Solar", "Watt", "256", "W");
        $Buderus[158][17] = array ("Momentan-Leistung Solar", "Watt", "1", "W");
        $Buderus[158][18] = array ("eingebrachte Wärmemenge Solar in Speicher 1", "Waerme", "65536", "100 Wh");
        $Buderus[158][19] = array ("eingebrachte Wärmemenge Solar in Speicher 1", "Waerme", "256", "100 Wh");
        $Buderus[158][20] = array ("eingebrachte Wärmemenge Solar in Speicher 1", "Waerme", "1", "100 Wh");
        $Buderus[158][21] = array ("eingebrachte Wärmemenge Solar in Speicher 2", "Waerme", "65536", "100 Wh");
        $Buderus[158][22] = array ("eingebrachte Wärmemenge Solar in Speicher 2", "Waerme", "256", "100 Wh");
        $Buderus[158][23] = array ("eingebrachte Wärmemenge Solar in Speicher 2", "Waerme", "1", "100 Wh");
        $Buderus[158][24] = array ("Betriebsstunden Speicher 1", "Betr2", "65536", "min");
        $Buderus[158][25] = array ("Betriebsstunden Speicher 1", "Betr2", "256", "min");
        $Buderus[158][26] = array ("Betriebsstunden Speicher 1", "Betr2", "1", "min");
        $Buderus[158][27] = array ("Warmwassersolltemperaturabsenkung durch solaren Ertrag", "Temp", "1", "K");
        $Buderus[158][28] = array ("Warmwassersolltemperaturabsenkung durch Wärmekapazität (Speicher unten Temperatur)", "Temp", "1", "K");
        $Buderus[158][29] = array ("Kollektortemperatur", "Temp", "1", "°C");
        $Buderus[158][30] = array ("Betriebsstunden Speicher 2", "Betr2", "65536", "min");
        $Buderus[158][31] = array ("Betriebsstunden Speicher 2", "Betr2", "256", "min");
        $Buderus[158][32] = array ("Betriebsstunden Speicher 2", "Betr2", "1", "min");

    $Buderus[159][-1] = array ("Alternativer WE", "41");
        $Buderus[159][0] = array ("Wärmeerzeuger Vorlauf", "Temp", "1", "°C");
        $Buderus[159][1] = array ("Wärmeerzeuger Anl.-Rücklauf", "Temp", "1", "°C");
        $Buderus[159][2] = array ("Puffer Oben", "Temp", "1", "°C");
        $Buderus[159][3] = array ("Puffer Unten", "Temp", "1", "°C");
        $Buderus[159][4] = array ("Puffer Mitte", "Temp", "1", "°C");
        $Buderus[159][5] = array ("Wärmeerzeuger Rücklauf", "Temp", "1", "°C");
        $Buderus[159][6] = array ("Sollwert Vorlaufregelung", "Temp", "1", "°C");
        $Buderus[159][7] = array ("Sollwert Puffer", "Temp", "1", "°C");
        $Buderus[159][8] = array ("","");
        $Buderus[159][9] = array ("Stellglied SWE", "Prozent", "1", "%");
        $Buderus[159][10] = array ("Stellglied SWR", "Prozent", "1", "%");
        $Buderus[159][11] = array ("max. Temp Puffer", "Temp", "1", "°C");
        $Buderus[159][12] = array ("Betriebsstunden", "Betr", "65536", "h");
        $Buderus[159][13] = array ("Betriebsstunden", "Betr", "256", "h");
        $Buderus[159][14] = array ("Betriebsstunden", "Betr", "1", "h");
        $Buderus[159][15] = array ("","");
        $Buderus[159][16] = array ("","");
        $Buderus[159][17] = array ("Soll Anlage", "Temp", "1", "°C");
        $Buderus[159][18] = array ("","");
        $Buderus[159][19] = array ("Wärmeerzeuger Abgastemperatur", "Temp", "1", "°C");
        $Buderus[159][20] = array ("","");
        $Buderus[159][21] = array ("Betriebswerte 1", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[159][22] = array ("","");
        $Buderus[159][23] = array ("Betriebswerte 2", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[159][24] = array ("","");
        $Buderus[159][25] = array ("","");
        $Buderus[159][26] = array ("","");
        $Buderus[159][27] = array ("Betriebswerte 3", "Bit", "Eingang WF2", "Eingang WF3", "", "", "", "Schalter 0", "Schalter Hand", "Schalter AUT");
        $Buderus[159][28] = array ("","");
        $Buderus[159][29] = array ("","");
        $Buderus[159][30] = array ("","");
        $Buderus[159][31] = array ("","");
        $Buderus[159][32] = array ("","");
        $Buderus[159][33] = array ("","");
        $Buderus[159][34] = array ("","");
        $Buderus[159][35] = array ("","");
        $Buderus[159][36] = array ("","");
        $Buderus[159][37] = array ("","");
        $Buderus[159][38] = array ("","");
        $Buderus[159][39] = array ("","");
        $Buderus[159][40] = array ("","");
        $Buderus[159][41] = array ("","");


        //IPS_LogMessage('Buderus Logamatic', 'Modultyp:'.$typ.' : '.$offset.' : '.$value);
    $name = $Buderus[$typ][$offset][$value];
    if ($name === false) 
        {
        //IPS_LogMessage('Buderus Logamatic', 'Modultyp:'.$typ.' : '.$offset.' : '.$value.'existiert nicht !');
        return false;
        }
    return $name;
    }

function CheckVariable($typ, $offset, $value, $parentID)
   {
  		$var = Buderus($typ, -1, 1);
                if ($var === '0')
                {
                    return true;
                }
                else
                {
                    $name = Buderus($typ, $offset, $value);
                    $InstanzID = @IPS_GetVariableIDByName($name, $parentID);
                    if ($InstanzID === false)
                    {
                        $InstanzID = IPS_CreateVariable(3);
                        IPS_SetName($InstanzID, $name); // Instanz benennen
                        IPS_SetParent($InstanzID, $parentID);
                    }
                    return $InstanzID;
                }
                
   }
   
function CheckVariableTYP($name, $vartyp, $profile, $parentID)
   {
  		$InstanzID = @IPS_GetVariableIDByName($name, $parentID);
                if ($InstanzID === false)
                    {
                    $InstanzID = IPS_CreateVariable($vartyp);
                    IPS_SetName($InstanzID, $name); // Instanz benennen
                    IPS_SetParent($InstanzID, $parentID);
                    IPS_SetVariableCustomProfile($InstanzID, $profile);
                    }
                //echo "ID: ".$InstanzID." ".$name."\n";
                return $InstanzID;
   }

function CheckEventVariable($typ, $parentID)
    {
    $name = Buderus($typ, -1, 0);
    $subname = substr($name, 0, 5);
    if ($subname === "Kanal")
    {
        $InstanzID = @IPS_GetEventIDByName($name, $parentID);
        if ($InstanzID === false)
        {
            $InstanzID = IPS_CreateEvent(2);
            IPS_SetName($InstanzID, $name); // Instanz benennen
            IPS_SetParent($InstanzID, $parentID);
            IPS_SetEventActive($InstanzID, true);
            IPS_SetEventScheduleAction($InstanzID, 0, "Aus", 0x0000FF, '');
            IPS_SetEventScheduleAction($InstanzID, 1, "Ein", 0xFF0000, '');
            IPS_SetEventScheduleGroup($InstanzID, 0, 1);
            IPS_SetEventScheduleGroup($InstanzID, 1, 2);
            IPS_SetEventScheduleGroup($InstanzID, 2, 4);
            IPS_SetEventScheduleGroup($InstanzID, 3, 8);
            IPS_SetEventScheduleGroup($InstanzID, 4, 16);
            IPS_SetEventScheduleGroup($InstanzID, 5, 32);
            IPS_SetEventScheduleGroup($InstanzID, 6, 64);
        }
        return $InstanzID;
        //IPS_LogMessage('Schaltuhr', $name.' : '.$InstanzID);
    }
    return true;
    }

function EncodeCyclicEventData ($EinstellPar, $ID, $modultyp)
{
    $modultyp = ord(hex2bin($modultyp));
    $array = str_split($EinstellPar, 20);
    for ( $x = 0; $x < count ( $array ); $x++ ) {
        if (substr($array[$x], 0, 2) == 'a9') {
            $typ = ord(hex2bin(substr($array[$x], 4, 2)));
            $offset = ord(hex2bin(substr($array[$x], 6, 2)));
            if ($typ == $modultyp and $offset != '0') {
                $InstanzID = CheckEventVariable($typ, $ID);
                for ($y = 0; $y < 3; $y++) {
                    $byte1 = sprintf('%08b', ord(hex2bin(substr($array[$x], 8 + (8 * $y), 2))));
                    $byte2 = ord(hex2bin(substr($array[$x], 10 + (8 * $y), 2)));
                    $ein = (int)(substr($byte1, -1, 1));
                    $tag = bindec(substr($byte1, 0, 3));
                    $SchaltpunktID = $y + (($offset / 7) * 3) - 2;
                    $hour = floor($byte2 / 6);
                    $min = fmod($byte2, 6) * 10;
                    if ($hour != 24) @IPS_SetEventScheduleGroupPoint($InstanzID, $tag, $SchaltpunktID, $hour, $min, 0, $ein);
                    //IPS_LogMessage('Schaltuhr', $InstanzID . ' | ' . $SchaltpunktID . " : " . $tag . " : " . $hour . ":" . $min . " : " . $ein . " | ");
                }
            }
        }
    }
    return true;
}

function EncodeMonitorDirektData($Monitordaten, $ID, $Modultyp)
{
    $Modultyp = ord(hex2bin($Modultyp));
    $array = str_split($Monitordaten, 20);
    for ( $x = 0; $x < count ( $array ); $x++ )
    {
        $typ = ord(hex2bin(substr($array[$x], 4, 2)));
        if ($typ == $Modultyp)
        {
            switch (substr($array[$x], 0, 2))
            {
                case 'ab':
                case 'ad':
                    //IPS_LogMessage('Buderus Logamatic', 'Message: '.$array[$x]);
                    $offset = ord(hex2bin(substr($array[$x], 6, 2)));
                    $substring = substr($array[$x], 8, 2).substr($array[$x], 10, 2).substr($array[$x], 12, 2).substr($array[$x], 14, 2).substr($array[$x], 16, 2).substr($array[$x], 18, 2);
                    //IPS_LogMessage('Buderus Logamatic', 'Data: '.$typ.' : '.$offset.' : '.$substring);
                    $var = Buderus($typ, -1, 1);
                    if ($var === '0')
                    {
                        break;
                    }
                    else
                    {
                        $var = CheckVariable($typ, -1, 0, $ID);
                        $value = GetValueString($var);
                        $newvalue = substr_replace($value, $substring, $offset*2, 12);
                        SetValueString($var, $newvalue);
                        EncodeVariableData($ID, $typ);
                        break;
                    }
            }
        }
        //else IPS_LogMessage('Logamatic 4000', 'Modultyp: '.$Modultyp." Typ: ".$typ);
    }
    return true;
}

function EncodeMonitorNormalData($Monitordaten, $ID, $Modultyp)
{
    $Modultyp = ord(hex2bin($Modultyp));
    $array = str_split($Monitordaten, 10);
    for ( $x = 0; $x < count ( $array ); $x++ )
    {
        $typ = ord(hex2bin(substr($array[$x], 4, 2)));
        if ($typ === $Modultyp) {
            $typ = ord(hex2bin(substr($array[$x], 4, 2)));
            //IPS_LogMessage('Buderus Logamatic', 'Message: ' . $array[$x]);
            $offset = ord(hex2bin(substr($array[$x], 6, 2)));
            $substring = substr($array[$x], 8, 2);
            //IPS_LogMessage('Buderus Logamatic', 'Data: ' . $typ . ' : ' . $offset . ' : ' . $substring);
            $var = CheckVariable($typ, -1, 0, $ID);
            $value = GetValueString($var);
            $newvalue = substr_replace($value, $substring, $offset * 2, 2);
            SetValueString($var, $newvalue);
            EncodeVariableData($ID, $typ);
        }
        elseif ($typ !== $Modultyp) {
            $data = $array[$x];//substr($Monitordaten,((count($array)-($x+1))*24));
            //IPS_LogMessage('Buderus Logamatic', 'Message back: ' . $data);
            return $data;
        }
    }
    return true;
}

function EncodeKonfigurationData($Monitordaten, $ID)
{
    $array = str_split($Monitordaten, 10);
    for ( $x = 0; $x < count ( $array ); $x++ )
    {
        if (substr($array[$x], 0, 2) == 'a7')
        {
            $typ = ord(hex2bin(substr($array[$x], 4, 2)));
            switch ($typ)
            {
                case 137:
                    //IPS_LogMessage('Buderus Logamatic', 'Message: ' . $array[$x]);
                    $offset = ord(hex2bin(substr($array[$x], 6, 2)));
                    $substring = substr($array[$x], 8, 2);
                    //IPS_LogMessage('Buderus Logamatic', 'Data: ' . $typ . ' : ' . $offset . ' : ' . $substring);
                    $var = CheckVariable($typ, -1, 0, $ID);
                    $value = GetValueString($var);
                    $newvalue = substr_replace($value, $substring, $offset * 2, 2);
                    SetValueString($var, $newvalue);
                    EncodeVariableData($ID, $typ);
                    break;
            }
        }
    }
    return true;
}

function EncodeEinstellParameterData ($EinstellParameter, $ID)
{
    return true;
}

function CalculateTimeValue ($value)
{
    return true;
}

    
function EncodeVariableData($parentID, $typ)
    {
    $var = Buderus($typ, -1, 1);
    if ($var === '0')
        {
            return true;
        }
    else
           {
                $ID = IPS_GetVariableIDByName(Buderus($typ, -1, 0), $parentID);
                $value = GetValueString($ID);
                for ($x=0; $x < Buderus($typ, -1, 1) ; $x++)
                    {

                        if (Buderus($typ, $x, 0) != "")
                        {
                            switch (Buderus($typ, $x, 1))
                                {
                                case "Bit":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID), sprintf('%08b', ord(hex2bin(substr($value, $x*2, 2)))));
                                        break;
                                case "Temp":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2));
                                        break;
                                case "Temp2":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,($x*2)+1,2)))*Buderus($typ, $x+1, 2)));
                                        $x++;
                                        break;
                                case "Temp3":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2));
                                        break;
                                case "Temp4":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature.Difference", $ID),ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2));
                                        break;
                                case "Zeit":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "Minutes", $ID),ord(hex2bin(substr($value, $x*2, 2))));//Minutes
                                        break;
                                case "Prozent":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "~Valve", $ID),ord(hex2bin(substr($value, $x*2, 2))));
                                        break;
                                case "Betr":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Hours", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,($x*2)+1, 2)))*Buderus($typ, $x+1, 2)+ord(hex2bin(substr($value,($x*2)+2,2)))*Buderus($typ, $x+2, 2))/60); //
                                        $x++;
                                        $x++;
                                        break;
                        	    case "Betr2":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Minutes", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,($x*2)+1, 2)))*Buderus($typ, $x+1, 2)+ord(hex2bin(substr($value,($x*2)+2,2)))*Buderus($typ, $x+2, 2))/60); //
                                        $x++;
                                        $x++;
                                        break;
                                case "Waerme":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Waerme", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,($x*2)+1, 2)))*Buderus($typ, $x+1, 2)+ord(hex2bin(substr($value,($x*2)+2,2)))*Buderus($typ, $x+2, 2))/100); //
                                        $x++;
                                        $x++;
                                        break;
                                case "Watt":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Watt", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,$x+1, 2)))*Buderus($typ, $x+1, 2))); //
                                        $x++;
                                        break;
                                case "Flow":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Flow", $ID),(ord(hex2bin(substr($value, $x*2, 2)))*Buderus($typ, $x, 2)+ord(hex2bin(substr($value,($x*2)+1, 2)))*Buderus($typ, $x+1, 2))); // l/h
                                        $x++;
                                        break;
                                case "Version":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "Version", $ID),ord(hex2bin(substr($value, $x*2, 2))).".".ord(hex2bin(substr($value,($x*2)+1, 2)))); //
                                        $x++;
                                        break;
                                case "Modul":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID), Buderus($typ, $x, ord(hex2bin(substr($value, $x*2, 2)))+2));
                                        break;
                                case "HKBetriebsart":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID), Buderus($typ, $x, ord(hex2bin(substr($value, $x*2, 2)))+2));
                                        break;
                                case "HKAbsenkart":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID), Buderus($typ, $x, ord(hex2bin(substr($value, $x*2, 2)))+2));
                                        break;
                                case "HKHeizsystem":
                                        SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID), Buderus($typ, $x, ord(hex2bin(substr($value, $x*2, 2)))+2));
                                        break;
                                case "ZeitHours":
                                    SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "Hours", $ID),ord(hex2bin(substr($value, $x*2, 2))));//Hours
                                    break;
                                case "ZeitDays":
                                    SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "Hours", $ID),ord(hex2bin(substr($value, $x*2, 2))));//Days
                                    break;
                                }
                        }
                        else
                            if (Buderus($typ, $x, 1) != "") {
                            $x = (Buderus($typ, $x, 1));
                        }
                    }

            }
    }
    

function str2hex($string) // Funktion String in Hex umwandeln
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
			{
			$hex .=dechex(ord($string[$i]))." ";
			}
		return $hex;
	}

?>