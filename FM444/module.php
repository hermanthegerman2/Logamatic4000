<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class FM444 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        
        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->ConnectParent('{9888202F-A490-4785-BDA7-DBB817B163B2}');
        $this->RegisterPropertyString('AWe', 'Holzvergaser');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $this->SetStatus(102);
            
    }        
    
    protected function SendDataToParent($data)
    {
      
        $JSONString = json_encode(Array('DataID' => '{9CA33B30-2DAD-4F3C-BE42-49EE8B27E8C7}', 'Buffer' => utf8_encode($data)));
       
        IPS_LogMessage('Logamatic -> Gateway:',str2hex(utf8_decode($data)));
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic FM444 <- Logamatic 43xx:', bin2hex(utf8_decode($data->Buffer)));
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        $modultyp = substr($stream, 8, 2);
        
        	switch ($datentyp)
                                    {
                                      
                                    case 'a5':   // A5 Statusmeldung
                                        
                                        IPS_LogMessage('Buderus Logamatic', 'ECO-CAN Adresse '.$bus.' is alive');
                                        return true;
                                    
                                    case 'a7':   // A7 Monitordaten Normalmodus

                                        IPS_LogMessage('Buderus Logamatic', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                                        
                                        //EncodeMonitorNormalData($stream, $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $array = str_split($stream, 24);
                                        for ( $x = 0; $x < count ( $array ); $x++ )
                                            {
                                                if (substr($array[$x], 0, 2) == 'a7')
                                                {
                                                    $Bus = ord(hex2bin(substr($array[$x], 4, 2)));
                                                    $typ = ord(hex2bin(substr($array[$x], 8, 2)));                                                    
                                                    IPS_LogMessage('Buderus FM444', 'ECO-CAN Adresse '.$Bus.' Array: '.$array[$x]);
                                                    $offset = ord(hex2bin(substr($array[$x], 12, 2)));
                                                    $substring = substr($array[$x], 16, 2);
                                                    IPS_LogMessage('Buderus FM444', 'ECO-CAN Adresse '.$Bus.' Data: '.$typ.' : '.$offset.' : '.$substring);
                                                    $var = CheckVariable($typ, -1, 0, $this->InstanceID);
                                                    $value = GetValueString($var);
                                                    $newvalue = substr_replace($value, $substring, $offset*2, 2);
                                                    SetValueString($var, $newvalue);
                                                    EncodeVariableData($this->InstanceID, $typ);
                                                }
                                                else
                                                IPS_LogMessage('Buderus FM444', 'EncodeMonitorNormalData');
                                            }
                                    
                                        break;                                  
                                    
                                
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