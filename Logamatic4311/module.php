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
            $this->RegisterVariableString("BufferIN", "BufferIN", "", -3);      
            IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        if (!$this->HasActiveParent())
            IPS_LogMessage('Logamatic', 'Instance has no active Parent.');
        
    }        
     

    public function RequestMonitordaten()
    {
        $data = chr(0xDD).chr(0x00);
        //$data = chr(221).chr(0).chr(1).chr(0).chr(0).chr(0);
        $this->SendDataToParent($data);
        sleep (2);
        $data = chr(0xA2).chr(0x00).chr(0x01).chr(0x00).chr(0x00);
        //$data = chr(162).chr(0).chr(1).chr(0).chr(0).chr(0);
        $this->SendDataToParent($data);
        return true;
    }
    
    protected function SendDataToParent($data)
    {
      
        $JSONString = json_encode(Array("DataID" => '{0D923A14-D3B4-4F44-A4AB-D2B534693C35}', "Buffer" => utf8_encode($data)));
       
        IPS_LogMessage('Logamatic -> Gateway:'.$this->InstanceID,str2hex(utf8_decode($data)));
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        
        return true;
    }
    
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        IPS_LogMessage('Logamatic <- Gateway:'.$this->InstanceID, str2hex(utf8_decode($data->BufferIN)));
        //IPS_LogMessage('ReceivedData:'.$this->InstanceID, utf8_decode(print_r($data,1)));
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