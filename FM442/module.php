<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class FM442 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('HK1', 'Heizkreis 1');
        $this->RegisterPropertyString('HK2', 'Heizkreis 2');
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
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('FM442 -> Logamatic', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data->Buffer)));
        return $id;
    }

    public function Umschaltschwelle(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Umschaltschwelle Sommer/Winter senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Nachtraumsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Nachtraumsolltemperatur senden: ' . $temp . '°C');
        $temp = $temp*2;
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Tagsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Tagsolltemperatur senden: ' . $temp . '°C');
        $temp = $temp*2;
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Betriebsart(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Betriebsart auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AbsenkartFerien(int $id)
    {
        $Betriebsart =  array(0 => 'Abschalt (Frostschutz bleibt aktiv)', 1 => 'Reduziert', 2 => 'Raumhalt' , 3 => 'Außenhalt');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Absenkart Ferien: ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x3f).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AussenhalttemperaturFerien(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Aussenhalttemperatur Ferien: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x3f).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AuslegungstemperaturHeizkreis(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Auslegungstemperatur Heizkreis: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x0e).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Heizsystem(int $id)
    {
        $Betriebsart =  array(0 => 'kein Heizsystem', 1 => 'Heizkörper', 2 => 'Konvektor' , 3 => 'Fussboden', 4 => 'Fusspunkt', 5 => 'konstant', 6 => 'Raumregler' , 7 => 'EIB');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Heizsystem: ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x38).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    /*________________________________________________________________________________________________________________________________________________________________*/

    public function Umschaltschwelle2(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Umschaltschwelle Sommer/Winter senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x00).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Nachtraumsolltemperatur2(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Nachtraumsolltemperatur senden: ' . $temp . '°C');
        $temp = $temp*2;
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Tagsolltemperatur2(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Tagsolltemperatur senden: ' . $temp . '°C');
        $temp = $temp*2;
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Betriebsart2(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Betriebsart auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AbsenkartFerien2(int $id)
    {
        $Betriebsart =  array(0 => 'Abschalt (Frostschutz bleibt aktiv)', 1 => 'Reduziert', 2 => 'Raumhalt' , 3 => 'Außenhalt');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Absenkart Ferien: ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x3f).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AussenhalttemperaturFerien2(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Aussenhalttemperatur Ferien: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x3f).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function AuslegungstemperaturHeizkreis2(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Auslegungstemperatur Heizkreis: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x0e).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function Heizsystem2(int $id)
    {
        $Betriebsart =  array(0 => 'kein Heizsystem', 1 => 'Heizkörper', 2 => 'Konvektor' , 3 => 'Fussboden', 4 => 'Fusspunkt', 5 => 'konstant', 6 => 'Raumregler' , 7 => 'EIB');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM441', 'Heizsystem: ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis2).chr(0x38).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{5EC102FC-380C-4C7C-AA9A-F7D4070CD15F}", "Buffer" => $data)));
        return $id;
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 2, 2);
        $modultyp = substr($stream, 4, 2);
        switch ($modultyp) {
            case '80':  // Heizkreis 1
            case '81':  // Heizkreis 2
            case '07':  // Heizkreis 1 einstellbare Daten
            case '08':  // Heizkreis 2 einstellbare Daten
            case '11':  // Schaltuhr Kanal 1
            case '12':  // Schaltuhr Kanal 2
                switch ($datentyp) {
                    case 'a7':  // A7 Monitordaten Normalmodus
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Monitordaten ECO-CAN Adresse ' . $bus . ' Normalmodus :' . $stream);
                        $result = EncodeMonitorNormalData($stream, $this->InstanceID, $modultyp);
                        if ($result != 1) {
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Message zurück an Logamatic: ' . $result);
                            $data = utf8_encode(hex2bin($result));
                            $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
                        }
                        break;
                    case 'ab':
                    case 'ad':  // AD Direktdaten Normalmodus
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Monitordaten ECO-CAN Adresse ' . $bus . ' Direktmodus :' . $stream);
                        EncodeMonitorDirektData($stream, $this->InstanceID, $modultyp);
                        break;
                    case 'a9':
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Schaltuhr Nr. ' . $modultyp . ' Daten :' . $stream);
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