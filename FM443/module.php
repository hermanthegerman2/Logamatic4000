<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class FM443 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        
        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->ConnectParent('{9888202F-A490-4785-BDA7-DBB817B163B2}');
        $this->RegisterPropertyString('Solar', 'Solarfunktion');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->SetStatus(102);
            
    }        
    
    public function SendDataToParent($data)
    {
      
        $JSONString = json_encode(Array('DataID' => '{7DADFA25-C0A9-43C6-81E0-523EA0E6D389}', 'Buffer' => utf8_encode($data)));
       
        IPS_LogMessage('Logamatic -> Gateway:',str2hex(utf8_decode($data)));
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic FM443 Receive Data:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
        if ($modultyp === '9e')
            {
        	switch ($datentyp)
                                    {                                   
                                                                     
                                    case 'a7':   // A7 Monitordaten Normalmodus

                                        IPS_LogMessage('Logamatic FM443', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                                        EncodeMonitorNormalData($stream, $this->InstanceID, $bus);
                                        break;
                                    
                                    case 'ab':
                                        IPS_LogMessage('Logamatic FM443', 'Monitordaten ECO-CAN Adresse '.$bus.' Direktmodus :'.$stream);
                                        EncodeMonitorDirektData($stream, $this->InstanceID, $bus);
                                        break;                                  
                                                                   
                                    }
            }
        else
        {
            SendDataToParent($stream);
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