<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class Logamatic43xx extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyInteger ('Bus', 1);
        $this->RegisterPropertyBoolean ("Logging", true);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->ConnectParent('{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}'); // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $id = $this->ReadPropertyInteger('Bus');
        switch ($id) {
            case ($id < 0):
                $this->SetStatus(202);
                break;
            case ($id > 15):
                $this->SetStatus(203);
                break;
            case ($id <= 15 && $id >= 1):
                $this->MaintainVariable('Einstellparameter', 'Einstellparameter', 3, '~String', -3, 1);
                $this->MaintainVariable('Monitordaten', 'Monitordaten', 3, '~String', 0, 1);
                $this->RegisterProfile('Minutes', '2', '', '', ' m', 0, 0, 0);
                $this->RegisterProfile('Hours', '2', '', '', ' h', 0, 0, 0);
                $this->RegisterProfile('Watt', '2', '', '', ' kWh', 0, 0, 0);
                $this->RegisterProfile('Waerme', '2', '', '', ' Wh', 0, 0, 0);
                $this->RegisterProfile('Version', '3', '', 'V ', '', 0, 0, 0);
                $this->RegisterProfile('Flow', '2', '', '', ' l/h', 0, 0, 0);
                $this->SetStatus(102);
                break;
        }
    }

    public function SwitchDM()
    {
        if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('Logamatic', 'Umschalten in den Direktmodus');
        $data = utf8_encode(chr(Command::Direktmodus).chr(Command::NUL));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
        return $id;
    }

    public function SwitchNM()
    {
        if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('Logamatic', 'Umschalten in den Normalmodus');
        $data = utf8_encode(chr(Command::Normalmodus).chr(Command::NUL));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
        return $id;
    }

    public function RequestMonitordaten()
    {
        $this->SwitchDM();
        $data = utf8_encode(chr(Command::Monitordaten).chr($this->ReadPropertyInteger('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL));
        $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
        SetValueString($this->GetIDForIdent('Monitordaten'), '');
        return true;
    }

    public function RequestEinstellPar()
    {
        $this->SwitchDM();
        $data = utf8_encode(chr(Command::Einstellparameter).chr($this->ReadPropertyInteger('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL));
        $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
        SetValueString($this->GetIDForIdent('Einstellparameter'), '');
        return true;
    }

    public function RequestErrorLog()
    {
        $this->SwitchDM();
        $data = utf8_encode(chr(Command::Datenblock).chr($this->ReadPropertyInteger('Bus')).chr(Command::Fehlerprotokoll).chr(Command::NUL));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
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
        $array = array('Modul in Slot 1', 'Modul in Slot 2', 'Modul in Slot 3', 'Modul in Slot 4', 'Modul in Slot A'); // mögliche Slots in Logamatic 43xx
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
                    case 'ZM432':
                        if (count(IPS_GetInstanceListByModuleID("{DC32EE80-C473-4806-A8A3-158DCFB6E2EE}")) == 0 ) {
                            $InsID = IPS_CreateInstance('{DC32EE80-C473-4806-A8A3-158DCFB6E2EE}');
                            IPS_SetName($InsID, $array[$x] . ' / Logamatic ZM432');
                            IPS_SetParent($InsID, $this->InstanceID);
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Modul ZM432 angelegt', 'Parent-ID: ' . $this->InstanceID . ' Instanz-ID: ' . $InsID);
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
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 43xx -> Gateway', bin2hex(utf8_decode($data->Buffer)));
        $stream = utf8_decode($data->Buffer);
        $datentyp = substr(bin2hex($stream), 0, 2);
        switch ($datentyp) {
            case 'b0':
                $this->SwitchDM();
                sleep (0.5);
                $data = utf8_encode(substr($stream, 0, 1) . chr($this->ReadPropertyInteger('Bus')) . substr($stream, 2) . chr(0x00)); // ECO-CAN Busadresse einfügen
                $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
                //$this->SwitchNM();
                $offset = substr($stream, 3, 1);
                if ($offset = '00') $offset = '1';
                sleep (2.5);
                $data = utf8_encode(chr(Command::Datenblock).chr($this->ReadPropertyInteger('Bus')).substr($stream, 2, 1).chr($offset).chr(0x00)); // Rückantwort anfragen
                $this->SendDataToParent(json_encode(Array("DataID" => "{0D923A14-D3B4-4F44-A4AB-D2B534693C35}", "Buffer" => $data)));
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
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 43xx Receive Data', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = ord(hex2bin(substr($stream, 4, 2)));
        $modultyp = substr($stream, 8, 2);
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
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 43xx -> FM444', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{CAAD553B-F39D-42FA-BCBD-A755D031D0ED}", "Buffer" => $data->Buffer)));
                            break;
                        case '80':  // Heizkreis 1
                        case '81':  // Heizkreis 2
                        case '07':  // Heizkreis 1 einstellbare Daten
                        case '08':  // Heizkreis 2 einstellbare Daten
                        case '11':  // Schaltuhr Kanal 1
                        case '12':  // Schaltuhr Kanal 2
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 43xx -> FM442', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{E0D2CD4C-BB90-479E-8370-34663C717F9A}", "Buffer" => $data->Buffer)));
                            break;
                        case '82':  // Heizkreis 3
                        case '84':  // Warmwasser
                        case '09':  // Heizkreis 3 einstellbare Daten
                        case '0c':  // Warmwasser einstellbare Daten
                        case '13':  // Schaltuhr Kanal 3
                        case '14':  // Schaltuhr Kanal 4 Warmwasser
                        case '1f':  // Schaltuhr Kanal 10 Zirkulation
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 43xx -> FM441', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{E1EA01E8-3901-4EB8-9898-15E9E69B9977}", "Buffer" => $data->Buffer)));
                            break;
                        case '9e':  // Solar Monitordaten
                        case '24':  // Solar einstellbare Daten
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('NM Logamatic 43xx -> FM443', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{0774465C-7A72-496E-B1BC-E16392A67EBD}", "Buffer" => $data->Buffer)));
                            break;
                        case '88':  // bodenstehender Kessel Monitordaten
                        case '10':  // einstellbaren Parameter / bodenstehender Kessel
                        case '1f':  // Schaltuhr Kanal 9 Kesselkreis
                            IPS_LogMessage('NM Logamatic 43xx -> ZM432', $stream);
                            $this->SendDataToChildren(json_encode(Array("DataID" => "{487A7347-AAC6-4084-9A86-25C61A2482DC}", "Buffer" => $data->Buffer)));
                            break;
                        case '89':  // Konfiguration Monitordaten
                        case '0d':  // Datentyp für Konfiguration (Modulauswahl) der einstellbaren Parameter
                            $result = EncodeMonitorNormalData($stream, $this->InstanceID, $modultyp);
                            if ($result != 1 and @IPS_GetObjectIDByName('Konfiguration', $this->InstanceID) == true) // Endlosschleife verhindern, falls noch keine Konfiguration!
                            {
                                if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 43xx', 'Message zurück an Logamatic: ' . $result);
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
                    if (substr($stream, -12, 4) == 'aa00') {
                        $this->DistributeDataToChildren($Einstellparameter, $this->InstanceID);
                        $this->SwitchNM();  // einstellbare Parameter komplett -> Normalmodus umschalten
                    }
                    break;

                case 'ab':   // AB Monitordaten Direktmodus
                case 'ac':   // AC Monitordaten komplett übertragen
                    $head = GetValueString($this->GetIDForIdent('Monitordaten'));
                    $Monitordaten = $head . $stream; // $stream anhängen
                    SetValueString($this->GetIDForIdent('Monitordaten'), $Monitordaten);
                    if (substr($stream, -12, 4) == 'ac00') {
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
                if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic 43xx', 'falsche Bus-Adresse '.$this->ReadPropertyInteger('Bus'));
                return false;
            }
    }

    protected function DistributeDataToChildren($Monitordaten, $ID)
    {
        $array = str_split($Monitordaten, 44);
        for ( $x = 0; $x < count ( $array ); $x++ )
        {
            $modultyp = substr($array[$x], 8, 2);
            $datentyp = (substr($array[$x], 0, 2));
            //IPS_LogMessage('DM Modultyp', $modultyp." Datentyp ".$datentyp);
            $data = hex2bin($array[$x]);
            switch ($modultyp)
            {
                case '9f':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{CAAD553B-F39D-42FA-BCBD-A755D031D0ED}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 43xx -> FM444', $array[$x]);
                    break;
                case '9e': case '24':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{0774465C-7A72-496E-B1BC-E16392A67EBD}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 43xx -> FM443', $array[$x]);
                    break;
                case '89': case '0d':
                    if ($datentyp = 'ab') EncodeMonitorDirektData($array[$x], $ID, $modultyp);
                    if ($datentyp = 'a7') EncodeMonitorNormalData($array[$x], $ID, $modultyp);
                    break;
                case '88': case '10': case 'ob': case '1d':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{487A7347-AAC6-4084-9A86-25C61A2482DC}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 43xx -> ZM432', $array[$x]);
                    break;
                case '82': case '09': case '84': case '0c': case '13': case '14': case '1f':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{E1EA01E8-3901-4EB8-9898-15E9E69B9977}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging") == true) IPS_LogMessage('DM Logamatic 43xx -> FM441', $array[$x]);
                    break;
                case '80': case '81': case '07': case '08': case '11': case '12':
                    $result = $this->SendDataToChildren(json_encode(Array("DataID" => "{E0D2CD4C-BB90-479E-8370-34663C717F9A}", "Buffer" => utf8_encode($data))));
                    if ($result == false) $this->RequestModule();
                    if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('DM Logamatic 43xx -> FM442', $array[$x]);
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