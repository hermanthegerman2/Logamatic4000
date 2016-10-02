<?php

require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class LogamaticGateway extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RequireParent("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}", "Logamatic Gateway");
        $this->RegisterPropertyString("Host", "192.168.178.16");
        $this->RegisterPropertyBoolean("Open", true);
        $this->RegisterPropertyInteger("Port", 6200);
        $this->RegisterPropertyString("Data", "");
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $change = false;
        // Zwangskonfiguration des ClientSocket
        $ParentID = $this->GetParent();
        if (!($ParentID === false))
        {
            if (IPS_GetProperty($ParentID, 'Host') <> $this->ReadPropertyString('Host'))
            {
                IPS_SetProperty($ParentID, 'Host', $this->ReadPropertyString('Host'));
                $change = true;
            }
            if (IPS_GetProperty($ParentID, 'Port') <> $this->ReadPropertyInteger('Port'))
            {
                IPS_SetProperty($ParentID, 'Port', $this->ReadPropertyInteger('Port'));
                $change = true;
            }
            $ParentOpen = $this->ReadPropertyBoolean('Open');
            // Keine Verbindung erzwingen wenn Host leer ist, sonst folgt später Exception.
         
            if (IPS_GetProperty($ParentID, 'Open') <> $ParentOpen)
            {
                IPS_SetProperty($ParentID, 'Open', $ParentOpen);
                $change = true;
            }
            if ($change)
                @IPS_ApplyChanges($ParentID);
        }
        /* Eigene Profile
        
        */
        //Workaround für persistente Daten der Instanz
                
        // Wenn wir verbunden sind, am Gateway mit listen anmelden für Events
        if (($this->ReadPropertyBoolean('Open'))
                and ( $this->HasActiveParent($ParentID)))
        {
             $this->SetStatus(102);                    
        }
        
    }
    
    public function RequestErrorLog()
    {
        $data = chr(Command::Direktmodus).chr(Command::NUL);
        $this->SendDataToParent($data);
        $data = chr(Command::Datenblock).chr(Command::NUL).chr(0x01).chr(Command::NUL).chr(Command::Fehlerprotokoll).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL);
        $this->SendDataToParent($data);
        return true;
    }
    public function SendRawData()
    {
        $this->SendDataToParent(chr($this->ReadPropertyString('Data')));
        return true;
    }
################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    
################## PRIVATE     
    private function CheckParents()
    {
        $result = $this->HasActiveParent();
        if ($result)
        {
            $instance = IPS_GetInstance($this->InstanceID);
            $parentGUID = IPS_GetInstance($instance['ConnectionID'])['ModuleInfo']['ModuleID'];
            if ($parentGUID == '{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}')
            {
                IPS_DisconnectInstance($this->InstanceID);
                //IPS_LogMessage('Logamatic Gateway', 'Logamatic Gateway has invalid Parent.');
                $result = false;
            }
        }
        return $result;
    }
   
    
################## DATAPOINT RECEIVE FROM CHILD
    /*public function ForwardData($JSONString)
    { 
        // Empfangene Daten von der Device Instanz
        $data = json_decode($JSONString);
        IPS_LogMessage("Gateway -> SerialPort:", str2hex(utf8_decode($data->Buffer)));
        $data = utf8_decode($data->Buffer);
        // Weiterleiten zur I/O Instanz
        $this->SendDataToParent($data);
    }*/

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic Gateway -> RS232 or TCP Port', bin2hex(utf8_decode($data->Buffer)));
        $id = $this->SendDataToParent(json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => $data->Buffer)));
        //$id = IPS_SendDataToParent( $this->InstanceID, json_encode(Array('DataID' => '{0D923A14-D3B4-4F44-A4AB-D2B534693C35}', "Buffer" => $data))); // Daten senden
        return $id;
    }
################## DATAPOINTS PARENT
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('RS232 or TCP Port -> Logamatic Gateway', str2hex(utf8_decode($data->Buffer)));
        $this->SendDataToChildren(json_encode(Array("DataID" => "{FDAAB689-6162-47D3-A05D-F342430AF8C2}", "Buffer" => $data->Buffer)));
        return true;
    }
    
    /*protected function SendDataToParent($data)
    {      
        $JSONString = json_encode(Array("DataID" => '{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}', "Buffer" => $data));
        IPS_LogMessage('Logamatic Gateway -> RS232 or TCP Port', str2hex(utf8_decode($data)));
        IPS_SendDataToParent($this->InstanceID, $JSONString);        
        return true;
    }*/
################## DUMMYS / WOARKAROUNDS - protected
      
    protected function GetParent()
    {
        $instance = IPS_GetInstance($this->InstanceID);
        return ($instance['ConnectionID'] > 0) ? $instance['ConnectionID'] : false;
    }
    protected function HasActiveParent()
    {
//        IPS_LogMessage(__CLASS__, __FUNCTION__); //          
        $instance = IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] > 0)
        {
            $parent = IPS_GetInstance($instance['ConnectionID']);
            if ($parent['InstanceStatus'] == 102)
                return true;
        }
        return false;
    }
    protected function RegisterTimer($Name, $Interval, $Script)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id === false)
            $id = 0;
        if ($id > 0)
        {
            if (!IPS_EventExists($id))
                throw new Exception("Ident with name " . $Name . " is used for wrong object type");
            if (IPS_GetEvent($id)['EventType'] <> 1)
            {
                IPS_DeleteEvent($id);
                $id = 0;
            }
        }
        if ($id == 0)
        {
            $id = IPS_CreateEvent(1);
            IPS_SetParent($id, $this->InstanceID);
            IPS_SetIdent($id, $Name);
        }
        IPS_SetName($id, $Name);
        IPS_SetHidden($id, true);
        IPS_SetEventScript($id, $Script);
        if ($Interval > 0)
        {
            IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $Interval);
            IPS_SetEventActive($id, true);
        }
        else
        {
            IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, 1);
            IPS_SetEventActive($id, false);
        }
    }
    protected function UnregisterTimer($Name)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id > 0)
        {
            if (!IPS_EventExists($id))
                throw new Exception('Timer not present');
            IPS_DeleteEvent($id);
        }
    }
    protected function SetTimerInterval($Name, $Interval)
    {
        $id = @IPS_GetObjectIDByIdent($Name, $this->InstanceID);
        if ($id === false)
            throw new Exception('Timer not present');
        if (!IPS_EventExists($id))
            throw new Exception('Timer not present');
        $Event = IPS_GetEvent($id);
        if ($Interval < 1)
        {
            if ($Event['EventActive'])
                IPS_SetEventActive($id, false);
        }
        else
        {
            if ($Event['CyclicTimeValue'] <> $Interval)
                IPS_SetEventCyclic($id, 0, 0, 0, 0, 1, $Interval);
            if (!$Event['EventActive'])
                IPS_SetEventActive($id, true);
        }
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