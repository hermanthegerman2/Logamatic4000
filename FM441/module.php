<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class FM441 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('HK', 'Heizkreis 3');
        $this->RegisterPropertyString('WW', 'Warmwasser');
        $this->RegisterPropertyBoolean ("Logging", true);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->ConnectParent('{9888202F-A490-4785-BDA7-DBB817B163B2}'); // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->SetStatus(102);
    }        

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('FM441 -> Logamatic', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data->Buffer)));
        return $id;
    }

    public function Umschaltschwelle(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Umschaltschwelle Sommer/Winter senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis3).chr(0x00).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Nachtraumsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Nachtraumsolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis3).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Tagsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Tagsolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis3).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Betriebsart(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Betriebsart auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis3).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektion(int $id)
    {
        $Betriebsart =  array(0 => 'aus', 1 => 'ein');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'thermische Desinfektion: ' . $Betriebsart[$id]);
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektiontemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Warmwassersolltemperatur für die Zeit der thermischen Desinfektion: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektionstag(int $id)
    {
        $Betriebsart =  array(0 => 'Montag', 1 => 'Dienstag', 2 => 'Mittwoch', 3 => 'Donnerstag', 4 => 'Freitag', 5 => 'Samstag', 6 => 'Sonntag', 7 => 'täglich');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Desinfektionstag: ' . $Betriebsart[$id]);
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektionsuhrzeit(int $id)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Uhrzeit an der die thermische Desinfektion starten soll ' . $id . ' Uhr');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Warmwassersolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Warmwassersolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x07).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function BetriebsartWarmwasser(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Betriebsart Warmwasser auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x0e).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Zirkulationspumpenlaeufe(int $id)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Zirkulationspumpenläufe pro Stunde ' . $id . ' ändern');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x0e).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function BetriebsartZirkulation(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Betriebsart Zirkulation auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x15).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Aufheizzeit(int $id)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Uhrzeit tägliche Aufheizung ' . $id . ' ändern');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x2a).chr(0x65).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
        switch ($modultyp) {
            case '82':  // Heizkreis 3
            case '84':  // Warmwasser
            case '09':  // Heizkreis 3 einstellbare Daten
            case '0c':  // Warmwasser einstellbare Daten
            case '13':  // Schaltuhr Kanal 3
            case '14':  // Schaltuhr Kanal 4 Warmwasser
            case '1f':  // Schaltuhr Kanal 10 Zirkulation
                switch ($datentyp) {
                    case 'a7':  // A7 Monitordaten Normalmodus
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                        $result = EncodeMonitorNormalData($stream, $this->InstanceID, $modultyp);
                        if ($result != 1) {
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Message zurück an Logamatic: ' . $result);
                            $data = utf8_encode(hex2bin($result));
                            $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
                        }
                        break;
                    case 'ab':
                    case 'ad':  // AD Direktdaten Normalmodus
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Monitordaten ECO-CAN Adresse '.$bus.' Direktmodus :'.$stream);
                        EncodeMonitorDirektData($stream, $this->InstanceID, $modultyp);
                        break;
                    case 'a9':
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Schaltuhr Nr. ' . $modultyp . ' Daten :' . $stream);
                        EncodeCyclicEventData($stream, $this->InstanceID, $modultyp);
                        break;
                }
        }
        $stream = '';
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