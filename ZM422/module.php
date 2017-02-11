<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class ZM422 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Kessel', 'bodenstehender Kessel');
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
        $this->ConnectParent('{9888202F-A490-4785-BDA7-DBB817B163B2}'); // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->SetStatus(102);
    }

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('ZM422 -> Logamatic', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data->Buffer)));
        return $id;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic ZM422 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 2, 2);
        $modultyp = substr($stream, 4, 2);
        switch ($modultyp) {
            case '88':  // bodenstehender Kessel Monitordaten
            case '10':  // einstellbaren Parameter / bodenstehender Kessel
            case '1f':  // Schaltuhr Kanal 9 Kesselkreis
                switch ($datentyp) {
                    case 'a7':  // A7 Monitordaten Normalmodus
                    case 'ad':  // AD Direktdaten Normalmodus
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic ZM422', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                        $result = EncodeMonitorNormalData($stream, $this->InstanceID, $modultyp);
                        if ($result != 1) {
                            if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic ZM422', 'Message zurück an Logamatic: ' . $result);
                            $data = utf8_encode(hex2bin($result));
                            $this->SendDataToParent(json_encode(Array("DataID" => "{054466C5-C0E0-46C6-82D7-29A2FAE4276C}", "Buffer" => $data)));
                        }
                        break;
                    case 'ab':
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic ZM422', 'Monitordaten ECO-CAN Adresse '.$bus.' Direktmodus :'.$stream);
                        EncodeMonitorDirektData($stream, $this->InstanceID, $modultyp);
                        break;
                    case 'a9':
                        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic ZM422', 'Schaltuhr Nr. ' . $modultyp . ' Daten :' . $stream);
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