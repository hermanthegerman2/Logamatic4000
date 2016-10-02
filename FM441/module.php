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
        IPS_LogMessage('FM441 -> Logamatic', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data->Buffer)));
        return $id;
    }

    public function Desinfektion(int $id)
    {
        $Betriebsart =  array(0 => 'aus', 1 => 'ein');
        IPS_LogMessage('Logamatic FM441', 'thermische Desinfektion: ' . $Betriebsart[$id]);
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektiontemperatur(float $temp)
    {
        IPS_LogMessage('Logamatic FM441', 'Warmwassersolltemperatur für die Zeit der thermischen Desinfektion: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektionstag(int $id)
    {
        $Betriebsart =  array(0 => 'Montag', 1 => 'Dienstag', 2 => 'Mittwoch', 3 => 'Donnerstag', 4 => 'Freitag', 5 => 'Samstag', 6 => 'Sonntag', 7 => 'täglich');
        IPS_LogMessage('Logamatic FM441', 'Desinfektionstag: ' . $Betriebsart[$id]);
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Desinfektionsuhrzeit(int $id)
    {
        IPS_LogMessage('Logamatic FM441', 'Uhrzeit an der die thermische Desinfektion starten soll ' . $id . ' Uhr');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x00)).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Warmwassersolltemperatur(float $temp)
    {
        IPS_LogMessage('Logamatic FM441', 'Warmwassersolltemperatur senden: ' . $temp . '°C');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x07).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function BetriebsartWarmwasser(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        IPS_LogMessage('Logamatic FM441', 'Betriebsart Warmwasser auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x0e).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Zirkulationspumpenlaeufe(int $id)
    {
        IPS_LogMessage('Logamatic FM441', 'Zirkulationspumpenläufe pro Stunde ' . $id . ' ändern');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x0e).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr(0x65).chr($id));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function BetriebsartZirkulation(int $id)
    {
        $Betriebsart =  array(0 => 'Manuell Nacht', 1 => 'Manuell Tag', 2 => 'Automatik');
        IPS_LogMessage('Logamatic FM441', 'Betriebsart Zirkulation auf ' . $Betriebsart[$id] . ' umschalten');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x15).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function Aufheizzeit(int $id)
    {
        IPS_LogMessage('Logamatic FM441', 'Uhrzeit tägliche Aufheizung ' . $id . ' ändern');
        $data = utf8_encode(chr(Command::Parameter).chr(Command::leer).chr(Command::Warmwasser).chr(0x2a)).chr(0x65).chr(0x65).chr($id).chr(0x65).chr(0x65).chr(0x65));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
        return $id;
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic FM441 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
        switch ($modultyp) {
            case '82':
            case '84':
                switch ($datentyp) {
                    case 'a7':   // A7 Monitordaten Normalmodus
                        IPS_LogMessage('Logamatic FM441', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                        EncodeMonitorNormalData($stream, $this->InstanceID);
                        break;
                    case 'ab':
                        IPS_LogMessage('Logamatic FM441', 'Monitordaten ECO-CAN Adresse '.$bus.' Direktmodus :'.$stream);
                        EncodeMonitorDirektData($stream, $this->InstanceID, $modultyp);
                        break;
                }
            case '13':
            case '14':
            case '1f':
                switch ($datentyp) {
                    case 'a9':
                        IPS_LogMessage('Logamatic FM441', 'Schaltuhr Nr. ' . $modultyp . ' Daten :' . $stream);
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
    
    ################## SEMAPHOREN Helper  - private  
    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter('Logamatic_' . (string) $this->InstanceID . (string) $ident, 1))
            {
                return true;
            }
            else
            {
                IPS_Sleep(mt_rand(1, 5));
            }
        }
        return false;
    }
    private function unlock($ident)
    {
        IPS_SemaphoreLeave('Logamatic_' . (string) $this->InstanceID . (string) $ident);
    }
}
?>