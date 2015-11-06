<?php

class Command extends stdClass
{
    
    const NUL = 0x00;
    const Direktmodus = 0xDD; //Umschalten von Normalmodus -> Direktmodus
    const Normalmodus = 0xDC; //Umschalten von Direktmodus -> Normalmodus
    const Monitordaten = 0xA2; //Monitordaten anfordern
        
}
function Buderus ($typ, $offset, $value)
    {
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

$Buderus[135][-1] = array ("Fehlerprotokoll", "41");
$Buderus[135][0] = array ("Offset 0","Modul");
$Buderus[135][1] = array ("Offset 1","Modul");
$Buderus[135][2] = array ("Offset 2","Modul");
$Buderus[135][3] = array ("Offset 3","Modul");
$Buderus[135][4] = array ("Offset 4","Modul");
$Buderus[135][5] = array ("Offset 5","Modul");
$Buderus[135][6] = array ("Offset 6","Modul");
$Buderus[135][7] = array ("Offset 7","Modul");
$Buderus[135][8] = array ("Offset 8","Modul");
$Buderus[135][9] = array ("Offset 9","Modul");
$Buderus[135][10] = array ("Offset 10","Modul");
$Buderus[135][11] = array ("Offset 11","Modul");
$Buderus[135][12] = array ("Offset 12","Modul");
$Buderus[135][13] = array ("Offset 13","Modul");
$Buderus[135][14] = array ("Offset 14","Modul");
$Buderus[135][15] = array ("Offset 15","Modul");
$Buderus[135][16] = array ("Offset 16","Modul");
$Buderus[135][17] = array ("Offset 17","Modul");
$Buderus[135][18] = array ("Offset 18","Modul");
$Buderus[135][19] = array ("Offset 19","Modul");
$Buderus[135][20] = array ("Offset 20","Modul");
$Buderus[135][21] = array ("Offset 21","Modul");
$Buderus[135][22] = array ("Offset 22","Modul");
$Buderus[135][23] = array ("Offset 23","Modul");
$Buderus[135][24] = array ("Offset 24","Modul");
$Buderus[135][25] = array ("Offset 25","Modul");
$Buderus[135][26] = array ("Offset 26","Modul");
$Buderus[135][27] = array ("Offset 27","Modul");
$Buderus[135][28] = array ("Offset 28","Modul");
$Buderus[135][29] = array ("Offset 29","Modul");
$Buderus[135][30] = array ("Offset 30","Modul");
$Buderus[135][31] = array ("Offset 31","Modul");
$Buderus[135][32] = array ("Offset 32","Modul");
$Buderus[135][33] = array ("Offset 33","Modul");
$Buderus[135][34] = array ("Offset 34","Modul");
$Buderus[135][35] = array ("Offset 35","Modul");
$Buderus[135][36] = array ("Offset 36","Modul");
$Buderus[135][37] = array ("Offset 37","Modul");
$Buderus[135][38] = array ("Offset 38","Modul");
$Buderus[135][39] = array ("Offset 39","Modul");
$Buderus[135][40] = array ("Offset 40","Modul");
$Buderus[135][41] = array ("Offset 41","Modul");

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
$Buderus[137][6] = array ("Modul in Slot 1", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM432", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
$Buderus[137][7] = array ("Modul in Slot 2", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM432", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
$Buderus[137][8] = array ("Modul in Slot 3", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM432", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
$Buderus[137][9] = array ("Modul in Slot 4", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM432", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
$Buderus[137][10] = array ("Modul in Slot A", "Modul", "defekt", "frei", "ZM432", "FM442", "FM441", "FM447", "ZM432", "FM445", "FM451", "FM454", "ZM424", "UBA", "FM452", "FM448", "ZM433", "FM446", "FM443", "", "", "", "", "FM444");
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
$Buderus[137][21] = array ("max. Ansteuerung für Stellglied", "Prozent", "1", "%");
$Buderus[137][22] = array ("max. Ansteuerung für Stellglied", "Prozent", "1", "%");
$Buderus[137][23] = array ("Regelgerätevorlaufisttemperatur", "Temp", "1", "°C");

$Buderus[154][-1] = array ("Imaginäres Modul", "59");
$Buderus[154][0] = array ("Offset 0","Modul");
$Buderus[154][1] = array ("Offset 1","Modul");
$Buderus[154][2] = array ("Offset 2","Modul");
$Buderus[154][3] = array ("Offset 3","Modul");
$Buderus[154][4] = array ("Offset 4","Modul");
$Buderus[154][5] = array ("Offset 5","Modul");
$Buderus[154][6] = array ("Offset 6","Modul");
$Buderus[154][7] = array ("Offset 7","Modul");
$Buderus[154][8] = array ("Offset 8","Modul");
$Buderus[154][9] = array ("Offset 9","Modul");
$Buderus[154][10] = array ("Offset 10","Modul");
$Buderus[154][11] = array ("Offset 11","Modul");
$Buderus[154][12] = array ("Offset 12","Modul");
$Buderus[154][13] = array ("Offset 13","Modul");
$Buderus[154][14] = array ("Offset 14","Modul");
$Buderus[154][15] = array ("Offset 15","Modul");
$Buderus[154][16] = array ("Offset 16","Modul");
$Buderus[154][17] = array ("Offset 17","Modul");
$Buderus[154][18] = array ("Offset 18","Modul");
$Buderus[154][19] = array ("Offset 19","Modul");
$Buderus[154][20] = array ("Offset 20","Modul");
$Buderus[154][21] = array ("Offset 21","Modul");
$Buderus[154][22] = array ("Offset 22","Modul");
$Buderus[154][23] = array ("Offset 23","Modul");
$Buderus[154][24] = array ("Offset 24","Modul");
$Buderus[154][25] = array ("Offset 25","Modul");
$Buderus[154][26] = array ("Offset 26","Modul");
$Buderus[154][27] = array ("Offset 27","Modul");
$Buderus[154][28] = array ("Offset 28","Modul");
$Buderus[154][29] = array ("Offset 29","Modul");
$Buderus[154][30] = array ("Offset 30","Modul");
$Buderus[154][31] = array ("Offset 31","Modul");
$Buderus[154][32] = array ("Offset 32","Modul");
$Buderus[154][33] = array ("Offset 33","Modul");
$Buderus[154][34] = array ("Offset 34","Modul");
$Buderus[154][35] = array ("Offset 35","Modul");
$Buderus[154][36] = array ("Offset 36","Modul");
$Buderus[154][37] = array ("Offset 37","Modul");
$Buderus[154][38] = array ("Offset 38","Modul");
$Buderus[154][39] = array ("Offset 39","Modul");
$Buderus[154][40] = array ("Offset 40","Modul");
$Buderus[154][41] = array ("Offset 41","Modul");
$Buderus[154][42] = array ("Offset 42","Modul");
$Buderus[154][43] = array ("Offset 43","Modul");
$Buderus[154][44] = array ("Offset 44","Modul");
$Buderus[154][45] = array ("Offset 45","Modul");
$Buderus[154][46] = array ("Offset 46","Modul");
$Buderus[154][47] = array ("Offset 47","Modul");
$Buderus[154][48] = array ("Offset 48","Modul");
$Buderus[154][49] = array ("Offset 49","Modul");
$Buderus[154][50] = array ("Offset 50","Modul");
$Buderus[154][51] = array ("Offset 51","Modul");
$Buderus[154][52] = array ("Offset 52","Modul");
$Buderus[154][53] = array ("Offset 53","Modul");
$Buderus[154][54] = array ("Offset 54","Modul");
$Buderus[154][55] = array ("Offset 55","Modul");
$Buderus[154][56] = array ("Offset 56","Modul");
$Buderus[154][57] = array ("Offset 57","Modul");
$Buderus[154][58] = array ("Offset 58","Modul");
$Buderus[154][59] = array ("Offset 59","Modul");

$Buderus[158][-1] = array ("Solarfunktion", "35");
$Buderus[158][0] = array ("Betriebswerte 1", "Bit", "Fehler Einstellung Hysterese", "Speicher 2 auf max. Temperatur", "Speicher 1 auf max. Temperatur", "Kollektor auf max. Temperatur", "", "", "", "");
$Buderus[158][1] = array ("Betriebswerte 1", "Bit", "Fehler Fühler Anlagenrücklauf Bypass defekt", "Fehler Fühler Speichermitte Bypass defekt", "Fehler Volumenstromzähler WZ defekt", "Fehler Fühler Rücklauf WZ defekt", "Fehler Fühler Vorlauf WZ defekt", "Fehler Fühler Speicher-unten 2 defekt", "Fehler Fühler Speicher-unten 1 defekt", "Fehler Fühler Kollektor defekt");
$Buderus[158][2] = array ("Betriebswerte 1", "Bit", "Umschaltventil Speicher 2 zu", "Umschaltventil Speicher 2 auf/Speicherladepumpe2", "Umschaltventil Bypass zu", "Umschaltventil Bypass auf", "Sekundärpumpe Speicher 2 Betrieb", "", "", "");
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
$Buderus[158][30] = array ("Betriebsstunden Speicher 1", "Betr2", "65536", "min");
$Buderus[158][31] = array ("Betriebsstunden Speicher 1", "Betr2", "256", "min");
$Buderus[158][32] = array ("Betriebsstunden Speicher 1", "Betr2", "1", "min");
$Buderus[158][33] = array ("","");
$Buderus[158][34] = array ("","");
$Buderus[158][35] = array ("","");

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
    //echo "Ergebnis Check: ".$typ." ".$offset." ".$value."\n";;
    $name = $Buderus[$typ][$offset][$value];
    if ($name === false) 
        {
        echo "dieses Buderus Modul existiert nicht !";
        return false;
        }
    return $name;
    }

function CheckVariable($typ, $offset, $value, $parentID)
   {
  		//echo "Check: ".$typ." ".$offset." ".$value." ".$ID;
                $name = Buderus($typ, $offset, $value);
                if ($name === false) 
                    {
                    echo "Check: dieses Buderus Modul existiert nicht !";
                    return false;
                    }
                //echo "Ergebnis return: ".$name."\n";
                $InstanzID = @IPS_GetVariableIDByName($name, $parentID);
                if ($InstanzID === false)
                {
                $InstanzID = IPS_CreateVariable(3);
                IPS_SetName($InstanzID, $name); // Instanz benennen
                IPS_SetParent($InstanzID, $parentID);
                }
                //echo "ID: ".$InstanzID." ".$name."\n";
                return $InstanzID;
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
 
function EncodeMonitorData($Monitordaten, $ID, $Bus)
    {
                $array = explode("\xAB\x00\x01\x00", $Monitordaten); //x".str2hex($Bus)."
                    for ( $x = 0; $x < count ( $array ); $x++ )
                        {
                        $typ = ord(substr($array[$x], 0, 1));
                        if ($typ > 79) 
                            {
                            
                            //echo "Typ: ".$typ."\n";
                            $offset = ord(substr($array[$x], 2, 1));
                            //echo "Offset: ".$offset."\n";
                            $text = substr($array[$x], 4, 1).substr($array[$x], 6, 1).substr($array[$x], 8, 1).substr($array[$x], 10, 1).substr($array[$x], 12, 1).substr($array[$x], 14, 1);
                            $var = CheckVariable($typ, -1, 0, $ID);
                            if ($var === false) 
                                {
                                echo "Encode: dieses Buderus Modul existiert nicht !";
                                return false;
                                }
                            echo "Name: ".$var." : ".$typ." : ".$offset." : ".str2hex($text)."\n";
                            //echo "Name: ".CheckVariable($typ, -1, 0, $ID);
                            $value = GetValueString($var);
                            $value = substr_replace($value, $text, $offset, 6);
                            SetValueString($var, $value);
                            EncodeVariableData($ID, $typ);
                            }
                        else
                            echo "Encode: dieses Buderus Modul existiert nicht !";
                        }
                return true;
    }
    
function EncodeVariableData($parentID, $typ)
    {
    $ID = IPS_GetVariableIDByName(Buderus($typ, -1, 0), $parentID);
    $value = GetValueString($ID);
    
    for ($x=0; $x < Buderus($typ, -1, 1) ; $x++)
	{
        if (Buderus($typ, $x, 0) !== "")
		{
		switch (Buderus($typ, $x, 1))
                    {
			case "Bit":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID),str_pad(base_convert(ord(substr($value, $x, 1)),16,2),8,"0",STR_PAD_LEFT));
                            break;
                        case "Temp":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),ord(substr($value, $x, 1))*Buderus($typ, $x, 2));
                            break;
			case "Temp2":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),(ord(substr($value, $x, 1))*Buderus($typ, $x, 2)+ord(substr($value, $x+1 ,1))*Buderus($typ, $x+1, 2)));
                            $x++;
                            break;
                        case "Temp3":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "~Temperature", $ID),ord(substr($value, $x, 1))*Buderus($typ, $x, 2));
                            break;
			case "Zeit":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "Minutes", $ID),ord(substr($value, $x, 1)));
                            break;
			case "Prozent":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 1, "~Valve", $ID),ord(substr($value, $x, 1)));
                            break;
			case "Betr":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Hours", $ID),(ord(substr($value, $x, 1))*Buderus($typ, $x, 2)+ord(substr($value, $x+1, 1))*Buderus($typ, $x+1, 2)+ord(substr($value, $x+2, 1))*Buderus($typ, $x+2, 2))/60);
                            $x++;
                            $x++;
                            break;
			case "Betr2":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Minutes", $ID),(ord(substr($value,$x,1))*Buderus($typ, $x, 2)+ord(substr($value,$x+1,1))*Buderus($typ, $x+1, 2)+ord(substr($value,$x+2,1))*Buderus($typ, $x+2, 2))/60);
                            $x++;
                            $x++;
                            break;
                        case "Waerme":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Waerme", $ID),(ord(substr($value,$x,1))*Buderus($typ, $x, 2)+ord(substr($value,$x+1,1))*Buderus($typ, $x+1, 2)+ord(substr($value,$x+2,1))*Buderus($typ, $x+2, 2))/100);
                            $x++;
                            $x++;
                            break;
			case "Watt":
			    SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 2, "Watt", $ID),(ord(substr($value,$x,1))*Buderus($typ, $x, 2)+ord(substr($value,$x+1,1))*Buderus($typ, $x+1, 2)));
                            $x++;
                            break;
			case "Version":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "Version", $ID),ord(substr($value,$x,1)).".".ord(substr($value,$x+1,1)));
                            $x++;
                            break;
                            case "Modul":
                            SetValue(CheckVariableTYP(Buderus($typ, $x, 0), 3, "~String", $ID),$Buderus($typ, $x, ord(substr($value,$x,1))+2));
                            break;

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