<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class Logamatic4311 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->ConnectParent('{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}');
        $this->RegisterPropertyString('Bus', '');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        if ($this->ReadPropertyString('Bus') == '')
            $this->SetStatus(202);
        else
            $this->SetStatus(102);            
            $this->RegisterVariableString('Monitordaten', 'Monitordaten', '', -4);
            IPS_SetHidden($this->GetIDForIdent('Monitordaten'), true);
            $this->RegisterVariableString('EinstellPar', 'EinstellPar', '', -4);
            IPS_SetHidden($this->GetIDForIdent('EinstellPar'), true);
            $this->RegisterProfile('Minutes', '2', '', '', ' m',  0, 0, 0);
            $this->RegisterProfile('Hours', '2', '', '', ' h',  0, 0, 0);
            $this->RegisterProfile('Watt', '2', '', '', ' kWh',  0, 0, 0);
            $this->RegisterProfile('Waerme', '2', '', '', ' Wh', 0, 0, 0);
            $this->RegisterProfile('Version', '3', '', 'V ', '', 0, 0, 0);
            $this->RegisterProfile('Flow', '2', '', '', ' l/h', 0, 0, 0);
    }        
     

    public function RequestMonitordaten()
    {
        $data = chr(Command::Direktmodus).chr(Command::NUL);
        $this->SendDataToParent($data);
        $data = chr(Command::Monitordaten).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL);
        $this->SendDataToParent($data);
        $monitorID = $this->GetIDForIdent('Monitordaten');
        SetValueString($monitorID, '');
        return true;
    }
    public function RequestEinstellPar()
    {
        $data = chr(Command::Direktmodus).chr(Command::NUL);
        $this->SendDataToParent($data);
        $data = chr(Command::EinstellPar).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL);
        $this->SendDataToParent($data);
        $EinstellParID = $this->GetIDForIdent('EinstellPar');
        SetValueString($EinstellParID, '');
        return true;
    }       
    protected function SendDataToParent($data)
    {
      
        $JSONString = json_encode(Array('DataID' => '{0D923A14-D3B4-4F44-A4AB-D2B534693C35}', 'Buffer' => utf8_encode($data)));
       
        IPS_LogMessage('Logamatic -> Gateway:',str2hex(utf8_decode($data)));
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic <- Gateway:', bin2hex(utf8_decode($data->Buffer)));
        $monitorID = $this->GetIDForIdent('Monitordaten');
        $EinstellParID = $this->GetIDForIdent('EinstellPar');
        $stream = bin2hex(utf8_decode($data->Buffer));
        $datentyp = substr($stream, 0, 2);
        $bus = substr($stream, 4, 2);
        
        	switch ($datentyp)   {
                                      
                                    case 'a5':   // A5 Monitordaten einzelmeldung
                                        
                                        IPS_LogMessage('Buderus Logamatic', 'ECO-CAN Adresse '.$bus.' is alive');
                                        return true;
                                    
                                    case 'a7':   // A7 Monitordaten Normalmodus

                                        IPS_LogMessage('Buderus Logamatic', 'Monitordaten ECO-CAN Adresse '.$bus.' Normalmodus :'.$stream);
                                        EncodeMonitorNormalData($stream, $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        break;                                  
                                    
                                    case 'a9':   // A9 Kennung für einstellbare Parameter
                                        $head = GetValueString($EinstellParID);
                                        $EinstellPar = $head.$stream;
                                        SetValueString($EinstellParID, $EinstellPar);
                                        break;
                                    
                                    case 'aa':   // AA Einstellbare Parameter komplett übertragen
                                        IPS_LogMessage('Buderus Logamatic', 'Einstellbare Parameter ECO-CAN Adresse '.$bus.' komplett :'.strlen(GetValueString($EinstellParID)).' Bytes');
                                        EncodeEinstellParData(GetValueString($EinstellParID), $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data); // Umschalten in Normalmodus senden
                                        break;
                                    
                                    case 'ab':   // AB Monitordaten Direktmodus
                                        $head = GetValueString($monitorID);
                                        $Monitordaten = $head.$stream;
                                        SetValueString($monitorID, $Monitordaten);
                                        break;
                                        
                                    case 'ac':   // AC Monitordaten komplett übertragen
                                        //$monitordaten = GetValueString($monitorID);
                                        IPS_LogMessage('Buderus Logamatic:', 'Monitordaten ECO-CAN Adresse '.$bus.' komplett :'.strlen(GetValueString($monitorID)).' Bytes\n');
                                        EncodeMonitorDirektData(GetValueString($monitorID), $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data); // Umschalten in Normalmodus senden
                                        break;
                                    case 'ad':  // AD Datenblock empfangen
                                        IPS_LogMessage('Buderus Logamatic', 'Datenblock '.$stream);
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