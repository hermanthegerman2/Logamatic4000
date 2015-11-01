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
        
    }

    public function RequestMonitordaten($Data)
    {
        $Data = chr(221).chr(0).$this->ReadPropertyString('Bus').chr(0).chr(0);
        $this->SendDataToParent($Data);
        //sleep (0.5);
        $Data = chr(162).chr(0).$this->ReadPropertyString('Bus').chr(0).chr(0).chr(0);
        $this->SendDataToParent($Data);
    }
    protected function SendDataToParent($Data)
    {
        // API-Daten verpacken und dann versenden.
        //$Data->Bus=$this->ReadPropertyString('Bus');
        $JSONString = $Data->ToJSONString('{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}');
        IPS_LogMessage('SendDataToGateway:'.$this->InstanceID,$JSONString);
        // Daten senden
        IPS_SendDataToParent($this->InstanceID, $JSONString);
        return true;
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