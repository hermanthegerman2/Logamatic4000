<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class Logamatic42xx extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyInteger ('Bus', 0);
        $this->RegisterPropertyBoolean ("Logging", true);
    }

    public function Destroy()
    {
        //Never delete this line!
        parent::Destroy();
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->ConnectParent('{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}'); // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $id = $this->ReadPropertyInteger('Bus');
        /*switch ($id) {
            case $id < 0:
                $this->SetStatus(202);
                break;
            case $id > 15:
                $this->SetStatus(203);
                break;
            case $id < 16:*/
                $this->MaintainVariable('Einstellparameter', 'Einstellparameter', 3, '~String', -3, 1);
                IPS_SetHidden($this->GetIDForIdent('Einstellparameter'), true);
                $this->MaintainVariable('Monitordaten', 'Monitordaten', 3, '~String', 0, 1);
                IPS_SetHidden($this->GetIDForIdent('Monitordaten'), true);
                $this->RegisterProfile('Minutes', '2', '', '', ' m', 0, 0, 0);
                $this->RegisterProfile('Hours', '2', '', '', ' h', 0, 0, 0);
                $this->RegisterProfile('Watt', '2', '', '', ' kWh', 0, 0, 0);
                $this->RegisterProfile('Waerme', '2', '', '', ' Wh', 0, 0, 0);
                $this->RegisterProfile('Version', '3', '', 'V ', '', 0, 0, 0);
                $this->RegisterProfile('Flow', '2', '', '', ' l/h', 0, 0, 0);
                $this->SetStatus(102);
                /*break;
        }*/
    }

    public function SwitchDM()
    {
        if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('Logamatic', 'Umschalten in den Direktmodus');
        $data = utf8_encode(chr(Command::Direktmodus));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
        return $id;
    }

    public function SwitchNM()
    {
        if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('Logamatic', 'Umschalten in den Normalmodus');
        $data = utf8_encode(chr(Command::Normalmodus));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
        return $id;
    }

    public function RequestMonitordaten()
    {
        $this->SwitchDM();
        sleep (0.2);
        $data = utf8_encode(chr(Command::Monitordaten).chr($this->ReadPropertyInteger('Bus')));
        $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
        SetValueString($this->GetIDForIdent('Monitordaten'), '');
        return true;
    }

    public function RequestEinstellPar()
    {
        $this->SwitchDM();
        sleep (0.2);
        $data = utf8_encode(chr(Command::Einstellparameter).chr($this->ReadPropertyInteger('Bus')));
        $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
        SetValueString($this->GetIDForIdent('Einstellparameter'), '');
        return true;
    }

    public function RequestErrorLog()
    {
        $this->SwitchDM();
        sleep (0.2);
        $data = utf8_encode(chr(Command::Datenblock).chr($this->ReadPropertyInteger('Bus')));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
        return $id;
    }

    public function RequestModule()
    {
        $ParentID = @IPS_GetObjectIDByName('Konfiguration', $this->InstanceID);
        if ($ParentID == false) {
            $this->RequestMonitordaten(); // Monitordaten abrufen
            return true;
        }
        $Monitordaten = GetValueString($this->GetIDForIdent('Monitordaten'));
        EncodeKonfigurationData($Monitordaten, $this->InstanceID); // Monitordaten nur auf Konfigurationsdaten überprüfen und anlegen
        $array = array('Modul in Slot 1', 'Modul in Slot 2', 'Modul in Slot A'); // mögliche Slots in Logamatic 42xx
        for ($x = 0; $x < count($array); $x++) {
                $Slot = @IPS_GetObjectIDByName($array[$x], $ParentID);
                $Modultyp = GetValueString($Slot);
                switch ($Modultyp) {
                    case 'FM441':
                        if (count(IPS_GetInstanceListByModuleID("{08E2244F-D084-4574-9EE7-C6A23A008CFA}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{08E2244F-D084-4574-9EE7-C6A23A008CFA}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic FM441');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul FM441 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
                        }
                        break;
                    case 'FM444':
                        if (count(IPS_GetInstanceListByModuleID("{D887C2E7-9A65-42CB-9DC7-A092FD98FCBA}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{D887C2E7-9A65-42CB-9DC7-A092FD98FCBA}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic FM444');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul FM444 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
                        }
                        break;
                    case 'ZM422':
                        if (count(IPS_GetInstanceListByModuleID("{557BBB7A-DCD5-4C78-B165-BBAAD2F7BEFE}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{557BBB7A-DCD5-4C78-B165-BBAAD2F7BEFE}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic ZM422');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul ZM422 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
                        }
                        break;
                    case 'FM443':
                        if (count(IPS_GetInstanceListByModuleID("{882E275E-A658-4FE4-9947-FE0178A7149D}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{882E275E-A658-4FE4-9947-FE0178A7149D}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic FM443');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul FM443 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
                        }
                        break;
                    case 'FM442':
                        if (count(IPS_GetInstanceListByModuleID("{02B58635-9185-4AA4-90D2-FF0F1C947201}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{02B58635-9185-4AA4-90D2-FF0F1C947201}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic FM442');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul FM442 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
                        }
                        break;
                }
        }
        return true;
    }

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 42xx -> Gateway', bin2hex(utf8_decode($data->Buffer)));
        $stream = utf8_decode($data->Buffer);
        $datentyp = substr(bin2hex($stream), 0, 2);
        switch ($datentyp) {
            case 'b0':
                $this->SwitchDM();
                sleep (0.2);
                $data = utf8_encode(substr($stream, 0, 1) . chr($this->ReadPropertyInteger('Bus')) . substr($stream, 2)); // ECO-CAN Busadresse einfügen
                $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
                //$this->SwitchNM();
                $offset = substr(bin2hex($stream), 6, 2);
                switch ($offset) {
                    case '00':
                        $data = utf8_encode(chr(Command::Datenblock) . chr($this->ReadPropertyInteger('Bus')) . substr($stream, 2, 1) . chr(Command::ONE)); // Rückantwort anfragen
                        break;
                    default:
                        $data = utf8_encode(chr(Command::Datenblock) . chr($this->ReadPropertyInteger('Bus')) . substr($stream, 2, 2)); // Rückantwort anfragen
                }
                $this->SendDataToParent(json_encode(Array("DataID" => "{60C485C0-E28D-45D5-BF86-57F0257C1F4E}", "Buffer" => $data)));
                sleep (0.2);
                $this->SwitchNM();
                break;
            case 'a7':
            case 'a5':
                $this->ReceiveData($JSONString);
                break;
        }
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 42xx Receive Data', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = ord(hex2bin(substr($stream, 2, 2)));
        $modultyp = substr($stream, 4, 2);
        if ($bus === $this->ReadPropertyInteger('Bus'))
        {
            switch ($datentyp) {
                case 'a5':   // A5 Statusmeldung
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Buderus Logamatic', 'ECO-CAN Adresse ' . $bus . ' is alive');
                    return true;

                case 'a7':
                case 'ad':
                    // A7 Monitordaten Normalmodus
                    //IPS_LogMessage('Buderus Logamatic', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                    switch ($modultyp) {
                        case '9f':
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 42xx -> FM444', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{FBEB66A7-B400-4B76-A905-2F316A69C464}", "Buffer" => $data->Buffer)));
                            break;
                        case '80':  // Heizkreis 1
                        case '81':  // Heizkreis 2
                        case '07':  // Heizkreis 1 einstellbare Daten
                        case '08':  // Heizkreis 2 einstellbare Daten
                        case '11':  // Schaltuhr Kanal 1
                        case '12':  // Schaltuhr Kanal 2
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 42xx -> FM442', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{B5591015-1034-42B1-B945-8D27BB0901DE}", "Buffer" => $data->Buffer)));
                            break;
                        case '82':  // Heizkreis 3
                        //case '84':  // Warmwasser
                        case '09':  // Heizkreis 3 einstellbare Daten
                        //case '0c':  // Warmwasser einstellbare Daten
                        case '13':  // Schaltuhr Kanal 3
                        //case '14':  // Schaltuhr Kanal 4 Warmwasser
                        //case '1f':  // Schaltuhr Kanal 10 Zirkulation
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 42xx -> FM441', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{630C78DF-CB8D-490D-B21A-13D436DBC0B5}", "Buffer" => $data->Buffer)));
                            break;
                        case '9e':  // Solar Monitordaten
                        case '24':  // Solar einstellbare Daten
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 42xx -> FM443', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{693602C1-B28A-4C80-BB15-49596F1EDF3E}", "Buffer" => $data->Buffer)));
                            break;
                        case '84':  // Warmwasser
                        case '88':  // bodenstehender Kessel Monitordaten
                        case '10':  // einstellbaren Parameter / bodenstehender Kessel
                        case '8a':  // Heizkreis 5 Kesselkreis HK
                        case '15':  // Schaltuhr Kanal 5 Kesselkreis HK
                        case '0c':  // Warmwasser einstellbare Daten
                        case '16':  // Heizkreis 5 einstellbare Daten
                        case '17':  // Schaltuhr Kanal 6 Warmwasser
                        case '1f':  // Schaltuhr Kanal 10 Zirkulation
                            IPS_LogMessage('NM Logamatic 42xx -> ZM422', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{7C608E31-D80A-45DB-9D2A-CFB402C95CBD}", "Buffer" => $data->Buffer)));
                            break;
                        case '89':  // Konfiguration Monitordaten
                        case '0d':  // Datentyp für Konfiguration (Modulauswahl) der einstellbaren Parameter
                            $result = EncodeMonitorNormalData($stream, $this->InstanceID, $modultyp);
                            if ($result != 1 and @IPS_GetObjectIDByName('Konfiguration', $this->InstanceID) == true) // Endlosschleife verhindern, falls noch keine Konfiguration!
                            {
                                if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 42xx', 'Message zurück an Logamatic: ' . $result);
                                //$this->ReceiveData($JSONString);
                            }
                            break;
                    }
                    break;

                case 'a9':   // A9 Kennung für einstellbare Parameter
                case 'aa':   // AA Einstellbare Parameter komplett übertragen
                    $head = GetValueString($this->GetIDForIdent('Einstellparameter'));
                    $Einstellparameter = $head . $stream; // $stream anhängen
                    SetValueString($this->GetIDForIdent('Einstellparameter'), $Einstellparameter);
                    if (substr($stream, -4, 4) == 'aa00') {
                        $this->DistributeDataToChildren($Einstellparameter, $this->InstanceID);
                        $this->SwitchNM();  // einstellbare Parameter komplett -> Normalmodus umschalten
                    }
                    break;

                case 'ab':   // AB Monitordaten Direktmodus
                case 'ac':   // AC Monitordaten komplett übertragen
                    $head = GetValueString($this->GetIDForIdent('Monitordaten'));
                    $Monitordaten = $head . $stream; // $stream anhängen
                    SetValueString($this->GetIDForIdent('Monitordaten'), $Monitordaten);
                    if (substr($stream, -4, 4) == 'ac00') {
                        $this->DistributeDataToChildren($Monitordaten, $this->InstanceID);
                        $this->SwitchNM();  // Monitordaten komplett -> Normalmodus umschalten
                        $ParentID = @IPS_GetObjectIDByName('Konfiguration', $this->InstanceID);
                    }
                    break;
                }
        $stream = '';
        return true;
        }
        else
            {
                if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 42xx', 'falsche Bus-Adresse '.$this->ReadPropertyInteger('Bus'));
                return false;
            }
    }

    protected function DistributeDataToChildren($Monitordaten, $ID)
    {
        $array = str_split($Monitordaten, 20);
        for ( $x = 0; $x < count ( $array ); $x++ )
        {
            $modultyp = substr($array[$x], 4, 2);
            $datentyp = (substr($array[$x], 0, 2));
            //IPS_LogMessage('DM Modultyp', $modultyp." Datentyp ".$datentyp);
            $data = hex2bin($array[$x]);
            switch ($modultyp)
            {
                case '9f':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{FBEB66A7-B400-4B76-A905-2F316A69C464}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 42xx -> FM444', $array[$x]);
                    break;
                case '9e':  // Solar Monitordaten
                case '24':  // Solar einstellbare Daten
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{693602C1-B28A-4C80-BB15-49596F1EDF3E}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 42xx -> FM443', $array[$x]);
                    break;
                case '89':  // Konfiguration Monitordaten
                case '0d':  // Datentyp für Konfiguration (Modulauswahl) der einstellbaren Parameter
                    if ($datentyp = 'ab') EncodeMonitorDirektData($array[$x], $ID, $modultyp);
                    if ($datentyp = 'a7') EncodeMonitorNormalData($array[$x], $ID, $modultyp);
                    break;
                case '84':  // Warmwasser
                case '88':  // bodenstehender Kessel Monitordaten
                case '10':  // einstellbaren Parameter / bodenstehender Kessel
                case '8a':  // Heizkreis 5 Kesselkreis HK
                case '15':  // Schaltuhr Kanal 5 Kesselkreis HK
                case '0c':  // Warmwasser einstellbare Daten
                case '16':  // Heizkreis 5 einstellbare Daten
                case '17':  // Schaltuhr Kanal 6 Warmwasser
                case '1f':  // Schaltuhr Kanal 10 Zirkulation
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{7C608E31-D80A-45DB-9D2A-CFB402C95CBD}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 43xx -> ZM422', $array[$x]);
                    break;
                case '82':  // Heizkreis 3
                    //case '84':  // Warmwasser
                case '09':  // Heizkreis 3 einstellbare Daten
                    //case '0c':  // Warmwasser einstellbare Daten
                case '13':  // Schaltuhr Kanal 3
                    //case '14':  // Schaltuhr Kanal 4 Warmwasser
                    //case '1f':  // Schaltuhr Kanal 10 Zirkulation
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{630C78DF-CB8D-490D-B21A-13D436DBC0B5}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('DM Logamatic 42xx -> FM441', $array[$x]);
                    break;
                case '80':  // Heizkreis 1
                case '81':  // Heizkreis 2
                case '07':  // Heizkreis 1 einstellbare Daten
                case '08':  // Heizkreis 2 einstellbare Daten
                case '11':  // Schaltuhr Kanal 1
                case '12':  // Schaltuhr Kanal 2
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{B5591015-1034-42B1-B945-8D27BB0901DE}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 42xx -> FM442', $array[$x]);
                    break;
            }
        }
        return true;
    }


    ################## DUMMYS / WOARKAROUNDS - protected

    protected function RegisterProfile($Name, $VariablenTyp, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize)
    {
        if (!IPS_VariableProfileExists($Name))
        {
            IPS_CreateVariableProfile($Name, $VariablenTyp);
        }
        else
        {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != $VariablenTyp)
                throw new Exception("Variable profile type does not match for profile " . $Name);
        }
        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
    }
    protected function HasActiveParent()
    {
        $instance = @IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] > 0)
        {
            $parent = IPS_GetInstance($instance['ConnectionID']);
            if ($parent['InstanceStatus'] == 102)
                return true;
        }
        return false;
    }
    protected function GetParent()
    {
        $instance = @IPS_GetInstance($this->InstanceID);
        return ($instance['ConnectionID'] > 0) ? $instance['ConnectionID'] : false;
    }
    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus'])
            parent::SetStatus($InstanceStatus);
    }
}
?>