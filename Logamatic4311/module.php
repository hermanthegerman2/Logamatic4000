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
            $this->SetSummary(202);
        else
            $this->SetStatus(102);
            $this->SetSummary($this->ReadPropertyString('Bus'));
        if (!$this->HasActiveParent())
            IPS_LogMessage('Logamatic', 'Instance has no active Parent.');
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -3);      
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
    }

    public function RequestMonitordaten()
    {
        $Data = chr(0xDD).chr(0x00).chr(0x01).chr(0x00).chr(0x00);
        $this->SendDataToParent($Data);
        //sleep (0.5);
        $Data = chr(0xA2).chr(0x00).chr(0x01).chr(0x00).chr(0x00);
        $this->SendDataToParent($Data);
        return true;
    }
    
    protected function SendDataToParent($Data)
    {
        // API-Daten verpacken und dann versenden.
        //$Data->Bus=$this->ReadPropertyString('Bus');
        $JSONString = json_encode(Array("DataID" => '{0D923A14-D3B4-4F44-A4AB-D2B534693C35}', "Buffer" => utf8_encode($Data)));
       
        IPS_LogMessage('Logamatic -> Gateway:'.$this->InstanceID,$JSONString);
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
	{
		$data = json_decode($JSONString);
                print utf8_decode($data->Buffer)."\n";
                IPS_LogMessage('Logamatic <- Gateway:', utf8_decode($data->Buffer));                        				
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

                                        echo "Daten: A7 ".str2hex($stream)."\n";
                                        $stream = substr($stream, 0, 9);
                                        $this->SendDataToChildren(json_encode(Array("DataID" => "{FDAAB689-6162-47D3-A05D-F342430AF8C2}", "Buffer" => $data->Buffer)));
		                        $stream = '';
                                        break;
                                    }
                $this->unlock("ReceiveLock");
	}
        
################## DUMMYS / WOARKAROUNDS - protected
 
    protected function HasActiveParent()
    {
        IPS_LogMessage(__CLASS__, __FUNCTION__); //          
        $instance = IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] > 0)
        {
            $parent = IPS_GetInstance($instance['ConnectionID']);
            if ($parent['InstanceStatus'] == 102)
                return true;
        }
        return false;
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
}
?>