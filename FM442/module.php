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
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Umschaltschwelle Sommer/Winter senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
        return $id;
    }

    public function Nachtraumsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Nachtraumsolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
        return $id;
    }

    public function Tagsolltemperatur(float $temp)
    {
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Tagsolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
        return $id;
    }

    public function Betriebsart(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442', 'Betriebsart auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
        return $id;
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic FM442 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
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