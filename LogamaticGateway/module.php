<?php

require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class LogamaticGateway extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RequireParent("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}", "Logamatic Gateway");
        $this->RegisterPropertyString("Host", "127.0.0.1");
        $this->RegisterPropertyBoolean("Open", true);
        $this->RegisterPropertyInteger("Port", 2500);
        $this->RegisterPropertyBoolean("Logging", false);
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();
        $change = false;
        // Zwangskonfiguration des ClientSocket
        $ParentID = $this->GetParent();
        if (!($ParentID === false)) {
            if (IPS_GetProperty($ParentID, 'Host') <> $this->ReadPropertyString('Host')) {
                IPS_SetProperty($ParentID, 'Host', $this->ReadPropertyString('Host'));
                $change = true;
            }
            if (IPS_GetProperty($ParentID, 'Port') <> $this->ReadPropertyInteger('Port')) {
                IPS_SetProperty($ParentID, 'Port', $this->ReadPropertyInteger('Port'));
                $change = true;
            }
            $ParentOpen = $this->ReadPropertyBoolean('Open');
            // Keine Verbindung erzwingen wenn Host leer ist, sonst folgt später Exception.

            if (IPS_GetProperty($ParentID, 'Open') <> $ParentOpen) {
                IPS_SetProperty($ParentID, 'Open', $ParentOpen);
                $change = true;
            }
            if ($change)
                @IPS_ApplyChanges($ParentID);
        }

        if (($this->ReadPropertyBoolean('Open'))
            and ($this->HasActiveParent($ParentID))
        ) {
            $this->SetStatus(102);
        }

    }

    public function ForwardData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('Logamatic Gateway -> RS232 or TCP Port', bin2hex(utf8_decode($data->Buffer)));
        $this->SendDataToParent(json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => $data->Buffer)));
        return true;
    }

    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        if ($this->ReadPropertyBoolean("Logging")) IPS_LogMessage('RS232 or TCP Port -> Logamatic Gateway', bin2hex(utf8_decode($data->Buffer)));
        $this->SendDataToChildren(json_encode(Array("DataID" => "{FDAAB689-6162-47D3-A05D-F342430AF8C2}", "Buffer" => $data->Buffer)));
        return true;
    }

    ################## PRIVATE
    private function CheckParents()
    {
        $result = $this->HasActiveParent();
        if ($result) {
            $instance = IPS_GetInstance($this->InstanceID);
            $parentGUID = IPS_GetInstance($instance['ConnectionID'])['ModuleInfo']['ModuleID'];
            if ($parentGUID == '{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}') {
                IPS_DisconnectInstance($this->InstanceID);
                //IPS_LogMessage('Logamatic Gateway', 'Logamatic Gateway has invalid Parent.');
                $result = false;
            }
        }
        return $result;
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
        if ($instance['ConnectionID'] > 0) {
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
}
?>
