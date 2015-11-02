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
            /*$Data = chr(221).chr(0).chr(1).chr(0).chr(0);
            $this->SendDataToParent($Data);
            //sleep (0.5);
            $Data = chr(162).chr(0).chr(1).chr(0).chr(0).chr(0);
            $this->SendDataToParent($Data);
            */        
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
            if ($parentGUID == '{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}')
            {
                IPS_DisconnectInstance($this->InstanceID);
                IPS_LogMessage('Logamatic Gateway', 'Logamatic Gateway has invalid Parent.');
                $result = false;
            }
        }
        return $result;
    }
   
    
################## DATAPOINT RECEIVE FROM CHILD
    /*public function ForwardData($JSONString)
    {
        // ForwardDataFromDevice
        $data = json_decode($JSONString);
        IPS_LogMessage('ForwardDataFromChild:'.$this->InstanceID,  print_r($data,1));
        $this->SendDataToParent($Data);
        
    }*/
    public function ForwardData($JSONString) 
    {
 
    // Empfangene Daten von der Device Instanz
    $data = json_decode($JSONString);
    IPS_LogMessage("Gateway -> SerialPort:", utf8_decode($data->Buffer));
 
    // Hier würde man den Buffer im Normalfall verarbeiten
    // z.B. CRC prüfen, in Einzelteile zerlegen
 
    // Weiterleiten zur I/O Instanz
    $resultat = $this->SendDataToParent(json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => $data->Buffer)));
 
    // Weiterverarbeiten und durchreichen
    return $resultat;
 
    }
################## DATAPOINTS PARENT
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Gateway <- SerialPort:'.$this->InstanceID,  print_r($data,1));
        
        $bufferID = $this->GetIDForIdent("BufferIN");
        // Empfangs Lock setzen
        if (!$this->lock("ReceiveLock"))
            throw new Exception("ReceiveBuffer is locked");
        // Datenstream zusammenfügen
        $head = GetValueString($bufferID);
        SetValueString($bufferID, '');
        // Stream in einzelne Pakete schneiden
        $stream = $head . utf8_decode($data->Buffer);
        //IPS_LogMessage('ReceiveDataHex:'.$this->InstanceID,  print(str2hex($data->Buffer)));
        $type = ord(substr($stream, 0, 1));
        $bus = ord(substr($stream, 2, 1));
        
        echo $type." / ".$bus."\n";

		switch ($type) {
					case 167:   // A7 Monitordaten einzelmeldung

                                        echo "Daten: ".str2hex($stream)."\n";
                                        $stream = substr($stream, 0, 9);
                                        $this->SendDataToChildren(json_encode(Array("DataID" => "{FDAAB689-6162-47D3-A05D-F342430AF8C2}", "Buffer" => $data->Buffer)));
		                        $stream = '';
                                        break;
                                    
                                        case 165:   // A5 Monitordaten einzelmeldung

                                        echo "Daten: A5 ".str2hex($stream)."\n";
                                        $stream = '';
                                        break;
                                    
                                        case 171:   // AB Monitordaten komplett

                                        echo "Daten: AB ".str2hex($stream)."\n";
                                        $stream = '';
                                        break;
                                    
                                    
                                    
                                }

        
        
        //IPS_LogMessage('Logamatic Gateway', 'Frame: ' . strlen($stream) . ' Bytes given.');
        SetValueString($bufferID, $stream);
        $this->unlock("ReceiveLock");
        return;
        //}
        $packet = substr($stream, 3, $len + 1);
        // Ende wieder in den Buffer werfen
        $tail = substr($stream, $len + 10);
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
        IPS_LogMessage('Gateway -> SerialPort::'.$this->InstanceID,$Data);
        
        //Parent ok ?
        if (!$this->HasActiveParent())
            throw new Exception("Instance has no active Parent.");
        // Frame bauen
        //Laenge bilden
        $len = strlen($Data);
        //Startzeichen
        $frame = '';
        
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
    private function str2hex($string) // Funktion String in Hex umwandeln
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
			{
			$hex .=dechex(ord($string[$i]))." ";
			}
		return $hex;
	}

}
?>