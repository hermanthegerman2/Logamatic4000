<?php

require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class LogamaticGateway extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RequireParent("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}", "Logamatic Gateway");
        $this->RegisterPropertyString("Host", "192.168.178.133");
        $this->RegisterPropertyBoolean("Open", false);
        $this->RegisterPropertyInteger("Port", 10010);
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
            if (!$ParentOpen)
                $this->SetStatus(104);

            if ($this->ReadPropertyString('Host') == '')
            {
                if ($ParentOpen)
                    $this->SetStatus(202);
                $ParentOpen = false;
            }
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
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -3);      
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        
        // Wenn wir verbunden sind, am Gateway mit listen anmelden für Events
        if (($this->ReadPropertyBoolean('Open'))
                and ( $this->HasActiveParent($ParentID)))
        {
            $Data = chr(221).chr(0).chr(1).chr(0);
            $this->SendDataToParent($Data);
            $Data = chr(162).chr(0).chr(1).chr(0).chr(0).chr(0);
            $this->SendDataToParent($Data);
                     
        }
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
            if ($parentGUID == '{61051B08-5B92-472B-AFB2-6D971D9B99EE}')
            {
                IPS_DisconnectInstance($this->InstanceID);
                IPS_LogMessage('XBee-ZigBee Gateway', 'XB-ZB Gateway has invalid Parent.');
                $result = false;
            }
        }
        return $result;
    }
   
    
################## DATAPOINT RECEIVE FROM CHILD
    public function ForwardData($JSONString)
    {
        // Prüfen und aufteilen nach ForwardDataFromSplitter und ForwardDataFromDevcie
        $Data = json_decode($JSONString);
//        IPS_LogMessage('ForwardDataFrom???:'.$this->InstanceID,  print_r($Data,1));
        
        switch ($Data->DataID)
        {
            case "{5971FB22-3F96-45AE-916F-AE3AC8CA8782}": //API
                $APIData = new TXB_API_Data();
                $APIData->GetDataFromJSONObject($Data);
                $this->ForwardDataFromSplitter($APIData);
                break;
            case "{C2813FBB-CBA1-4A92-8896-C8BC32A82BA4}": //CMD
                $ATData = new TXB_Command_Data();
                $ATData->GetDataFromJSONObject($Data);
                $this->ForwardDataFromDevice($ATData);
                break;
        }
    }

################## DATAPOINTS DEVICE
    private function ForwardDataFromDevice(TXB_Command_Data $ATData)
    {
//        IPS_LogMessage('ForwardDataFromDevice:'.$this->InstanceID,  print_r($ATData,1));
        
        $Frame = chr(TXB_API_Command::XB_API_AT_Command) . chr($ATData->FrameID) . $ATData->ATCommand . $ATData->Data;
        $this->SendDataToParent($Frame);
    }
    private function SendDataToDevice(TXB_Command_Data $ATData)
    {
//        IPS_LogMessage('SendDataToDevice:'.$this->InstanceID,  print_r($ATData,1));
        $Data = $ATData->ToJSONString('{CB5950B3-593C-4126-9F0F-8655A3944419}');
        IPS_SendDataToChildren($this->InstanceID, $Data);
    }
################## DATAPOINTS PARENT
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('ReceiveDataFromSerialPort:'.$this->InstanceID,  print_r($data,1));
        
        $bufferID = $this->GetIDForIdent("BufferIN");
        // Empfangs Lock setzen
        if (!$this->lock("ReceiveLock"))
            throw new Exception("ReceiveBuffer is locked");
        // Datenstream zusammenfügen
        $head = GetValueString($bufferID);
        SetValueString($bufferID, '');
        // Stream in einzelne Pakete schneiden
        $stream = $head . utf8_decode($data->Buffer);
        $start = strpos($stream, chr(0x7e));
        //Anfang suchen
        if ($start === false)
        {
//            IPS_LogMessage('Logamatic Gateway', 'Frame without 0x7e');
            $stream = '';
        }
        elseif ($start > 0)
        {
//            IPS_LogMessage('Logamatic Gateway', 'Frame do not start with 0x7e');
            $stream = substr($stream, $start);
        }
        //Paket suchen
        if (strlen($stream) < 5)
        {
//            IPS_LogMessage('Logamatic', 'Frame to short');
            SetValueString($bufferID, $stream);
            $this->unlock("ReceiveLock");
            return;
        }
        $len = ord($stream[1]) * 256 + ord($stream[2]);
        if (strlen($stream) < $len + 4)
        {
//            IPS_LogMessage('Logamatic Gateway', 'Frame must have ' . $len . ' Bytes. ' . strlen($stream) . ' Bytes given.');
            SetValueString($bufferID, $stream);
            $this->unlock("ReceiveLock");
            return;
        }
        $packet = substr($stream, 3, $len + 1);
        // Ende wieder in den Buffer werfen
        $tail = substr($stream, $len + 4);
        if ($tail===false) $tail='';
        SetValueString($bufferID, $tail);
        $this->unlock("ReceiveLock");
        $this->DecodeData($packet);
        // Ende war länger als 4 ? Dann nochmal Packet suchen.
        if (strlen($tail) > 4)
            $this->ReceiveData(json_encode(array('Buffer' => '')));
        return true;
    }
    protected function SendDataToParent($Data)
    {
        IPS_LogMessage('SendDataToSerialPort:'.$this->InstanceID,$Data);
        
        //Parent ok ?
        if (!$this->HasActiveParent())
            throw new Exception("Instance has no active Parent.");
        // Frame bauen
        //Laenge bilden
        $len = strlen($Data);
        //Startzeichen
        //$frame = 
        
        //Laenge
        
        //Daten
        $frame.=$Data;
        //Checksum
        
        //Semaphore setzen
        if (!$this->lock("ToParent"))
        {
            throw new Exception("Can not send to Parent");
        }
        // Daten senden
        try
        {
            IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => utf8_encode($frame))));
        }
        catch (Exception $exc)
        {
        // Senden fehlgeschlagen
            $this->unlock("ToParent");
            throw new Exception($exc);
        }
        $this->unlock("ToParent");
        return true;
    }
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
    protected function SetSummary($data)
    {
//        IPS_LogMessage(__CLASS__, __FUNCTION__ . "Data:" . $data); //                   
    }
################## SEMAPHOREN Helper  - private  
    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter("XBZB_" . (string) $this->InstanceID . (string) $ident, 1))
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
        IPS_SemaphoreLeave("XBZB_" . (string) $this->InstanceID . (string) $ident);
    }
}
?>