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
        IPS_LogMessage('Logamatic <- Gateway:', str2hex(utf8_decode($data->Buffer)));
        $monitorID = $this->GetIDForIdent('Monitordaten');
        $EinstellParID = $this->GetIDForIdent('EinstellPar');
        $stream = utf8_decode($data->Buffer);
        $typ = ord(substr($stream, 0, 1));
        $bus = ord(substr($stream, 2, 1));
        
        	switch ($typ)   {
                                      
                                    case 165:   // A5 Monitordaten einzelmeldung
                                        
                                        IPS_LogMessage('Logamatic Gateway', 'Logamatic Gateway is alive');
                                        $stream = '';
                                        return true;
                                    
                                    case 167:   // A7 Monitordaten Normalmodus

                                        IPS_LogMessage('Logamatic Gateway', 'Monitordaten Normalmodus :'.str2hex($stream));
                                        EncodeMonitorNormalData($stream, $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $stream = '';
                                        break;                                  
                                    
                                    case 169:   // A9 Kennung für einstellbare Parameter
                                        $head = GetValueString($EinstellParID);
                                        $EinstellPar = $head.$stream;
                                        $stream = '';
                                        SetValueString($EinstellParID, $EinstellPar);
                                        break;
                                    
                                    case 170:   // AA Einstellbare Parameter komplett übertragen
                                        $stream = '';
                                        IPS_LogMessage('Logamatic Gateway', 'Einstellbare Parameter komplett :'.strlen(GetValueString($EinstellParID)).' Bytes');
                                        EncodeEinstellParData(GetValueString($EinstellParID), $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data); // Umschalten in Normalmodus senden
                                        break;
                                    
                                    case 171:   // AB Monitordaten Direktmodus
                                        $head = GetValueString($monitorID);
                                        $Monitordaten = $head.$stream;
                                        SetValueString($monitorID, $Monitordaten);
                                        break;
                                        
                                    case 172:   // AC Monitordaten komplett übertragen
                                        $stream = '';
                                        IPS_LogMessage('Logamatic Gateway:', 'Monitordaten komplett :'.strlen(GetValueString($monitorID)).' Bytes\n');
                                        EncodeMonitorDirektData(GetValueString($monitorID), $this->InstanceID, chr($this->ReadPropertyString('Bus')));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data); // Umschalten in Normalmodus senden
                                        break;
                                }
        
        return true;             
    }
        
################## DUMMYS / WOARKAROUNDS - protected
 
    
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