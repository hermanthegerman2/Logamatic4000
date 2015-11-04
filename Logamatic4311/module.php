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
            $this->RegisterVariableString("BufferIN", "BufferIN", "", -4);
            IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
            $this->RegisterVariableString("Monitordaten", "Monitordaten", "", -4);
            IPS_SetHidden($this->GetIDForIdent('Monitordaten'), true);        
    
    }        
     

    public function RequestMonitordaten()
    {
        $data = chr(Command::Direktmodus).chr(Command::NUL);
        $this->SendDataToParent($data);
        $data = chr(Command::Monitordaten).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL);
        $this->SendDataToParent($data);
        $monitorID = $this->GetIDForIdent("Monitordaten");
        SetValueString($monitorID, '');
        return true;
    }
    
    protected function SendDataToParent($data)
    {
      
        $JSONString = json_encode(Array("DataID" => '{0D923A14-D3B4-4F44-A4AB-D2B534693C35}', "Buffer" => utf8_encode($data)));
       
        IPS_LogMessage('Logamatic -> Gateway:',str2hex(utf8_decode($data)));
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        global $monitordaten;
        IPS_LogMessage('Logamatic <- Gateway:', str2hex(utf8_decode($data->Buffer)));
        $bufferID = $this->GetIDForIdent("BufferIN");
        $monitorID = $this->GetIDForIdent("Monitordaten");
        
        // Empfangs Lock setzen
        if (!$this->lock("ReceiveLock"))
            throw new Exception("ReceiveBuffer is locked");
        // Datenstream zusammenfügen
        $head = GetValueString($bufferID);
        SetValueString($bufferID, '');
        // Stream in einzelne Pakete schneiden
        $stream = $head . utf8_decode($data->Buffer);
        //$tail = '';
        //IPS_LogMessage('ReceiveDataHex:'.$this->InstanceID,  print(str2hex($data->Buffer)));
        while (strlen($stream) > 5)
        {
            
        $type = ord(substr($stream, 0, 1));
        $bus = ord(substr($stream, 2, 1));
        
        echo $type." / ".strlen($stream)."\n";

		switch ($type) {
                                    case 167:   // A7 Monitordaten Normalmodus

                                        $data = substr($stream, 0, 12);
                                        echo "Monitordaten Normalmodus :".str2hex($data)."\n";

		                        $stream = substr($stream, -(strlen($stream)-12));
                                        break;
                                    
                                    case 165:   // A5 Monitordaten einzelmeldung
                                        $data = substr($stream, 0, 12);
                                        echo "Daten: A5 ".str2hex($data)."\n";
                                        $stream = substr($stream, -(strlen($stream)-12));
                                        break;
                                    
                                    case 171:   // AB Monitordaten Direktmodus
                                        $data = substr($stream, 0, 22);
                                        echo "Monitordaten Direktmodus: AB ".str2hex($monitordaten)."\n";
                                        $monitordaten = GetValueString($monitorID);
                                        $monitordaten = $monitordaten . $data;
                                        SetValueString($monitorID, $monitordaten);
                                        $stream = substr($stream, -(strlen($stream)-22));
                                        break;
                                        
                                    case 172:   // AC Monitordaten komplett übertragen
                                        $monitordaten = GetValueString($monitorID);
                                        echo "Monitordaten komplett ".str2hex($monitordaten)."\n";
                                        EncodeMonitorData($monitordaten, $this->InstanceID);
                                        $stream = substr($stream, -(strlen($stream)-6));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data);
                                        break;
                                    
                                    
                                }
                echo "Rest : ".str2hex($stream)."\n";
                if ($stream===false) $stream='';
                SetValueString($bufferID, $stream);
                $this->unlock("ReceiveLock");
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
    protected function SetSummary($data)
    {
        IPS_LogMessage(__CLASS__, __FUNCTION__ . "Data:" . $data); // 
    }
    ################## SEMAPHOREN Helper  - private  
    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter("Logamatic_" . (string) $this->InstanceID . (string) $ident, 1))
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
        IPS_SemaphoreLeave("Logamatic_" . (string) $this->InstanceID . (string) $ident);
    }
}
?>