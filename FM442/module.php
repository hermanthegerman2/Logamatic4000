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
        IPS_LogMessage('FM442 -> Logamatic', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data->Buffer)));
        //$id = IPS_SendDataToParent( $this->InstanceID, json_encode(Array('DataID' => '{482A20C1-35A8-4591-96F0-C119AB72EBB2}', "Buffer" => $data))); // Daten senden
        return $id;
    }

    public function Tagsolltemperatur(float $temp)
    {
        IPS_LogMessage('Logamatic FM442', 'Tagsolltemperatur senden: ' . $temp . '°C');
        $data = chr(Command::Parameter).chr(0x01).chr(Command::Heizkreis1).chr(0x00).chr(0x65).chr(0x65).chr(0x65).chr($temp).chr(0x65).chr(0x65);
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{482A20C1-35A8-4591-96F0-C119AB72EBB2}", "Buffer" => $data)));
        return $id;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic FM442 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
        switch ($modultyp) {
            case '80':
            case '81':
                switch ($datentyp) {
                    case 'a7':   // A7 Monitordaten Normalmodus
                        IPS_LogMessage('Logamatic FM442', 'Monitordaten ECO-CAN Adresse ' . $bus . ' Normalmodus :' . $stream);
                        EncodeMonitorNormalData($stream, $this->InstanceID);
                        break;
                    case 'ab':
                        IPS_LogMessage('Logamatic FM442', 'Monitordaten ECO-CAN Adresse ' . $bus . ' Direktmodus :' . $stream);
                        EncodeMonitorDirektData($stream, $this->InstanceID, $modultyp);
                        break;
                }
            case '11':
            case '12':
                switch ($datentyp) {
                    case 'a9':
                        IPS_LogMessage('Logamatic FM442', 'Schaltuhr Nr. ' . $modultyp . ' Daten :' . $stream);
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