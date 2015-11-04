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
        if (!$this->HasActiveParent())
            IPS_LogMessage('Logamatic', 'Instance has no active Parent.');
        
    }        
     

    public function RequestMonitordaten()
    {
        $data = chr(Command::Direktmodus).chr(Command::NUL);
        $this->SendDataToParent($data);
        sleep (1);
        $data = chr(Command::Monitordaten).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL).chr(Command::NUL);
        $this->SendDataToParent($data);
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
        IPS_LogMessage('Logamatic <- Gateway:', str2hex(utf8_decode($data->BufferIN)));
        $stream = utf8_decode($data->BufferIN);
        //IPS_LogMessage('ReceiveDataHex:'.$this->InstanceID,  print(str2hex($data->Buffer)));
        if (strlen($stream) > 5)
        {
            
        $type = ord(substr($stream, 0, 1));
        $bus = ord(substr($stream, 2, 1));
        
        //echo $type." / ".$bus."\n";

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
                                        echo "Monitordaten Direktmodus :".str2hex($data)."\n";
                                        //if (strlen($data) > 22)
			     		//{			
				    	$typ = ord(substr($data, 4, 1));
                                        echo "Typ: ".$typ."\n";
					$offset = ord(substr($data, 6, 1));
                                        echo "Offset: ".$offset."\n";
                                        $text = substr($data, 8, 1).substr($data, 10, 1).substr($data, 12, 1).substr($data, 14, 1).substr($data, 16, 1).substr($data, 18, 1);
                                        echo "Daten: ".str2hex($text)."\n";
                                        echo "Name: ".CheckVariable($typ, -1, 0, $this->InstanceID);
                                        $value=GetValueString(CheckVariable($typ, -1, 0, $this->InstanceID));
                                        $value=substr_replace($value, $text, $offset, 1);
                                        setvaluestring(CheckVariable($typ, -1, 0, $this->InstanceID), $value);                                                             
                                        $stream = substr($stream, -(strlen($stream)-22));
                                        //}
                                        break;
                                        
                                    case 172:   // AC Monitordaten komplett übertragen
                                        $data = substr($stream, 0, 6);
                                        echo "Monitordaten komplett ".str2hex($data)."\n";
                                        $stream = substr($stream, -(strlen($stream)-6));
                                        $data = chr(Command::Normalmodus).chr($this->ReadPropertyString('Bus')).chr(Command::NUL).chr(Command::NUL);
                                        $this->SendDataToParent($data);
                                        break;
                                    
                                    
                                }
                echo "Rest : ".str2hex($stream)."\n";            
        }
        else
        $stream="";
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