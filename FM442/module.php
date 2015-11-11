<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class FM442 extends IPSModule
{
        
    public function Create()
    {
        //Never delete this line!
        parent::Create();

        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        //$this->ConnectParent('{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}');
        $this->RegisterPropertyInteger('ID', '');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        if ($this->ReadPropertyInteger('ID') == '')
            $this->SetStatus(202);
        else
            $this->SetStatus(102);            
            /*$this->RegisterVariableString('Monitordaten', 'Monitordaten', '', -4);
            IPS_SetHidden($this->GetIDForIdent('Monitordaten'), true);
            $this->RegisterVariableString('EinstellPar', 'EinstellPar', '', -4);
            IPS_SetHidden($this->GetIDForIdent('EinstellPar'), true);
            $this->RegisterProfile('Minutes', '2', '', '', ' m',  0, 0, 0);
            $this->RegisterProfile('Hours', '2', '', '', ' h',  0, 0, 0);
            $this->RegisterProfile('Watt', '2', '', '', ' kWh',  0, 0, 0);
            $this->RegisterProfile('Waerme', '2', '', '', ' Wh', 0, 0, 0);
            $this->RegisterProfile('Version', '3', '', 'V ', '', 0, 0, 0);
            $this->RegisterProfile('Flow', '2', '', '', ' l/h', 0, 0, 0);*/
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