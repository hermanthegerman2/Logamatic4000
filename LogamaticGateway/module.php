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
        $this->RegisterProfileIntegerEx("Scanner.SqueezeboxServer", "Gear", "", "", Array(
            Array(0, "Standby", "", -1),
            Array(1, "Abbruch", "", -1),
            Array(2, "Scan", "", -1),
            Array(3, "Nur Playlists", "", -1),
            Array(4, "Vollständig", "", -1)
        ));
        $this->RegisterProfileInteger("PlayerSelect" . $this->InstanceID . ".SqueezeboxServer", "Speaker", "", "", 0, 0, 0);
        // Eigene Variablen
        $this->RegisterVariableInteger("RescanState", "Scanner", "Scanner.SqueezeboxServer", 1);
        $this->RegisterVariableString("RescanInfo", "Rescan Status", "", 2);
        $this->RegisterVariableString("RescanProgress", "Rescan Fortschritt", "", 3);
        $this->EnableAction("RescanState");
        $this->RegisterVariableInteger("PlayerSelect", "Player wählen", "PlayerSelect" . $this->InstanceID . ".SqueezeboxServer", 4);
        $this->EnableAction("PlayerSelect");
        $this->RegisterVariableString("Playlists", "Playlisten", "~HTMLBox", 5);

        // Eigene Scripte
        $ID = $this->RegisterScript("WebHookPlaylist", "WebHookPlaylist", $this->CreateWebHookScript(), -8);
        IPS_SetHidden($ID, true);
        $this->RegisterHook('/hook/GatewayPlaylist' . $this->InstanceID, $ID);

        $ID = $this->RegisterScript('PlaylistDesign', 'Playlist Config', $this->CreatePlaylistConfigScript(), -4);
        IPS_SetHidden($ID, true);
        */
        //Workaround für persistente Daten der Instanz
        $this->RegisterVariableString("BufferIN", "BufferIN", "", -3);
        $this->RegisterVariableString("BufferOUT", "BufferOUT", "", -2);
        $this->RegisterVariableBoolean("WaitForResponse", "WaitForResponse", "", -1);
        IPS_SetHidden($this->GetIDForIdent('BufferIN'), true);
        IPS_SetHidden($this->GetIDForIdent('BufferOUT'), true);
        IPS_SetHidden($this->GetIDForIdent('WaitForResponse'), true);

        // Wenn wir verbunden sind, am Gateway mit listen anmelden für Events
        if (($this->ReadPropertyBoolean('Open'))
                and ( $this->HasActiveParent($ParentID)))
        {
            $Data = new GatewayData("Direktmodus", "DD", false);
            $this->SendGatewayData($Data);
            $Data = new GatewayData("Monitordaten", "A200010000", false);
            $this->SendGatewayData($Data);
           
        }
    }

################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */

    
################## DataPoints
//Ankommend von Child-Device

    public function ForwardData($JSONString)
    {
        $Data = json_decode($JSONString);

// Daten annehmen und Command zusammenfügen wenn Array
        if (is_array($Data->Gateway->Command))
//            $Data->Gateway->Command = implode(' ', $Data->Gateway->Command);
            $Data->Gateway->Command[0] = $Data->Gateway->Bus . ' ' . $Data->Gateway->Command[0];
        else
            $Data->Gateway->Command = $Data->Gateway->Bus . ' ' . $Data->Gateway->Command;
// Gateway-Objekt erzeugen und Daten mit Adresse ergänzen.
//        $GatewayData = new GatewayData($Data->Gateway->Address . ' ' . $Data->Gateway->Command, $Data->Gateway->Value, false);
        $GatewayData = new GatewayData($Data->Gateway->Command, $Data->Gateway->Value, false);
// Senden über die Warteschlange
        $ret = $this->SendGatewayData($GatewayData);
        return $ret;
    }

// Ankommend von Parent-ClientSocket
    public function ReceiveData($JSONString)
    {
        $data = json_decode($JSONString);
        $bufferID = $this->GetIDForIdent("BufferIN");

// Empfangs Lock setzen
        if (!$this->lock("bufferin"))
        {
            throw new Exception("ReceiveBuffer is locked");
        }

// Datenstream zusammenfügen
        $head = GetValueString($bufferID);
        SetValueString($bufferID, '');

// Stream in einzelne Pakete schneiden
        
        $packet = utf8_decode($data->Buffer);
        print $packet."\n";
        $type = ord(substr($packet, 0, 1));
        switch ($type) {
			case 165:   // A7 Monitordaten einzelmeldung
                            $packet = explode(chr(0xA5), $head . utf8_decode($data->Buffer));
                            break;
                        case 167:   // A7 Monitordaten einzelmeldung
                            $packet = explode(chr(0xA7), $head . utf8_decode($data->Buffer));
                            break;
                        case 171:  //AB Monitordaten auslesen
                            $packet = explode(chr(0xAB), $head . utf8_decode($data->Buffer));
                            break;
                        case 172:  //AC Ende
                            $packet = explode(chr(0xAC), $head . utf8_decode($data->Buffer));
                            break;
                        }
        //echo $type."\n";

// Rest vom Stream wieder in den Empfangsbuffer schieben
        $tail = array_pop($packet);
        SetValueString($bufferID, $tail);

// Empfangs Lock aufheben
        $this->unlock("bufferin");

// Pakete verarbeiten
        $ReceiveOK = true;
        foreach ($packet as $part)
        {
            $part = trim($part);
            $Data = new GatewayResponse($part);
/* Server Antworten hier verarbeiten
            if ($Data->Device == GatewayResponse::isServer)
            {
                $isResponse = $this->WriteResponse($Data->Data);
                if ($isResponse === true)
                {
// TODO Gateway-Statusvariablen nachführen....
// 
                    continue; // später unnötig
                }
                elseif ($isResponse === false)
                { //Info Daten von Server verarbeiten
// TODO
                    if (!$this->DecodeGatewayEvent($Data))
                        IPS_LogMessage('GatewayEvent', print_r($Data, 1));
                }
                else
                {
                    $ret = new Exception($isResponse);
                }
            }*/
// Nicht Server antworten zu den Devices weiter senden.
            //else
            //{
                try
                {
                    if (!$this->SendDataToChildren(json_encode(Array("DataID" => "{CB5950B3-593C-4126-9F0F-8655A3944419}", "Gateway" => $Data))))
                        $ReceiveOK = false;
                }
                catch (Exception $exc)
                {
                    $ret = new Exception($exc);
                }
                //if ($Data->Data[0] == GatewayResponse::client) // Client änderungen auch hier verarbeiten!
                {
                    //IPS_RunScriptText("<?\nGateway_RefreshPlayerList(" . $this->InstanceID . ");");
                }
            //}
        }
// Ist ein Fehler aufgetreten ?
        if (isset($ret))
            throw $ret; // dann erst jetzt werfen
        return $ReceiveOK;
    }

// Sende-Routine an den Parent
    protected function SendDataToParent($GatewayData)
    {
        if (is_array($GatewayData->Command))
            $Commands = implode(' ', $GatewayData->Command);
        else
            $Commands = $GatewayData->Command;
        if (is_array($GatewayData->Data))
            $Data = $Commands . ' ' . implode(' ', $GatewayData->Data);
        else
            $Data = $Commands . ' ' . $GatewayData->Data;
        $Data = trim($Data);
//Semaphore setzen
        if (!$this->lock("ToParent"))
        {
            throw new Exception("Can not send to Logamatic");
        }
// Daten senden
        try
        {
            $ret = IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}", "Buffer" => utf8_encode($Data . chr(0x0d)))));
        }
        catch (Exception $exc)
        {
// Senden fehlgeschlagen
            $this->unlock("ToParent");
            throw new Exception("Logamatic not reachable");
        }
        $this->unlock("ToParent");
        return $ret;
    }

// Sende-Routine an den Child
    protected function SendDataToChildren($Data)
    {
        return IPS_SendDataToChildren($this->InstanceID, $Data);
    }

################## Datenaustausch      
// Sende-Routine des GatewayData-Objektes an den Parent

    private function SendGatewayData(GatewayData $GatewayData)
    {
        $ParentID = $this->GetParent();
        if ($ParentID === false)
            throw new Exception('Instance has no parent.');
        else
        if (!$this->HasActiveParent($ParentID))
            throw new Exception('Instance has no active parent.');
        if ($GatewayData->needResponse)
        {
//Semaphore setzen für Sende-Routine
            if (!$this->lock("GatewayData"))
            {
                throw new Exception("Can not send to Logamatic");
            }

// Noch Umbauen wie das Device ?!?!
            /*            if ($GatewayData->Typ == GatewayData::GetData)
              {
              $WaitData = substr($GatewayData->Data, 0, -2);
              }
              else
              {
              $WaitData = $GatewayData->Data;
              } */

// Anfrage für die Warteschleife schreiben
            if (!$this->SetWaitForResponse($GatewayData->Command))
            {
// Konnte Daten nicht in den ResponseBuffer schreiben
// Lock der Sende-Routine aufheben.
                $this->unlock("GatewayData");
                throw new Exception("Can not send to Logamatic");
            }

            try
            {
// Senden an Parent
                $this->SendDataToParent($GatewayData);
            }
            catch (Exception $exc)
            {
// Konnte nicht senden
//Daten in ResponseBuffer löschen
                $this->ResetWaitForResponse();
// Lock der Sende-Routine aufheben.
                $this->unlock("GatewayData");
                throw $exc;
            }
// Auf Antwort warten....
            $ret = $this->WaitForResponse();
// Lock der Sende-Routine aufheben.
            $this->unlock("GatewayData");



            if ($ret === false) // Response-Warteschleife lief in Timeout
            {
//  Daten in ResponseBuffer löschen                
                $this->ResetWaitForResponse();
// Fehler
                throw new Exception("No answer from Logamatic");
            }

// Rückgabe ist eine Bestätigung von einem Befehl
            /*            if ($GatewayData->Typ == GatewayData::SendCommand)
              {
              if (trim($GatewayData->Data) == trim($ret))
              return true;
              else
              return false;
              }
              // Rückgabe ist ein Wert auf eine Anfrage

              else
              {
              // Abschneiden der Anfrage.
              $ret = str_replace($WaitData, "", $ret);
              return $ret;
              } */
            return $ret;
        }
// ohne Response, also ohne warten raussenden, 
        else
        {
            try
            {
                $this->SendDataToParent($GatewayData);
            }
            catch (Exception $exc)
            {
                throw $exc;
            }
        }
    }

################## ResponseBuffer    -   private

    private function SetWaitForResponse($Data)
    {
        if (is_array($Data))
            $Data = implode(' ', $Data);
        if ($this->lock('BufferOut'))
        {
            $buffer = $this->GetIDForIdent('BufferOUT');
            $WaitForResponse = $this->GetIDForIdent('WaitForResponse');
            SetValueString($buffer, $Data);
            SetValueBoolean($WaitForResponse, true);
            $this->unlock('BufferOut');
            return true;
        }
        return false;
    }

    private function ResetWaitForResponse()
    {
        if ($this->lock('BufferOut'))
        {
            $buffer = $this->GetIDForIdent('BufferOUT');
            $WaitForResponse = $this->GetIDForIdent('WaitForResponse');
            SetValueString($buffer, '');
            SetValueBoolean($WaitForResponse, false);
            $this->unlock('BufferOut');
            return true;
        }
        return false;
    }

    private function WaitForResponse()
    {
        $Event = $this->GetIDForIdent('WaitForResponse');
        for ($i = 0; $i < 500; $i++)
        {
            if (GetValueBoolean($Event))
                IPS_Sleep(10);
            else
            {
                if ($this->lock('BufferOut'))
                {
                    $buffer = $this->GetIDForIdent('BufferOUT');
                    $ret = GetValueString($buffer);
                    SetValueString($buffer, "");
                    $this->unlock('BufferOut');
                    if ($ret == '')
                        return true;
                    else
                        return $ret;
                }
                return false;
            }
        }
        return false;
    }

    private function WriteResponse($Array)
    {
        if (is_array($Array))
            $Array = implode(' ', $Array);

        $Event = $this->GetIDForIdent('WaitForResponse');
        if (!GetValueBoolean($Event))
            return false;
        $BufferID = $this->GetIDForIdent('BufferOUT');
        $BufferOut = GetValueString($BufferID);
        $Data = utf8_decode($Array /* implode(" ", $Array) */);
        $DataPos = strpos($Data, $BufferOut);
        if (!($DataPos === false))
        {
            if ($this->lock('BufferOut'))
            {
//                $Event = $this->GetIDForIdent('WaitForResponse');
                SetValueString($BufferID, trim(substr($Data, $DataPos + strlen($BufferOut))));
                SetValueBoolean($Event, false);
                $this->unlock('BufferOut');
                return true;
            }
            return 'Error on write ResponseBuffer';
        }
        return false;
    }

################## SEMAPHOREN Helper  - private  

    private function lock($ident)
    {
        for ($i = 0; $i < 100; $i++)
        {
            if (IPS_SemaphoreEnter("Gateway_" . (string) $this->InstanceID . (string) $ident, 1))
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
        IPS_SemaphoreLeave("Gateway_" . (string) $this->InstanceID . (string) $ident);
    }

################## DUMMYS / WOARKAROUNDS - protected

    protected function GetParent()
    {
        $instance = IPS_GetInstance($this->InstanceID);
        return ($instance['ConnectionID'] > 0) ? $instance['ConnectionID'] : false;
    }

    protected function HasActiveParent($ParentID)
    {
        if ($ParentID > 0)
        {
            $parent = IPS_GetInstance($ParentID);
            if ($parent['InstanceStatus'] == 102)
            {
                $this->SetStatus(102);
                return true;
            }
        }
        $this->SetStatus(203);
        return false;
    }

    protected function RequireParent($ModuleID, $Name = '')
    {

        $instance = IPS_GetInstance($this->InstanceID);
        if ($instance['ConnectionID'] == 0)
        {

            $parentID = IPS_CreateInstance($ModuleID);
            $instance = IPS_GetInstance($parentID);
            if ($Name == '')
                IPS_SetName($parentID, $instance['ModuleInfo']['ModuleName']);
            else
                IPS_SetName($parentID, $Name);
            IPS_ConnectInstance($this->InstanceID, $parentID);
        }
    }

    private function SetValueBoolean($Ident, $value)
    {
        $id = $this->GetIDForIdent($Ident);
        if (GetValueBoolean($id) <> $value)
        {
            SetValueBoolean($id, $value);
            return true;
        }
        return false;
    }

    private function SetValueInteger($Ident, $value)
    {
        $id = $this->GetIDForIdent($Ident);
        if (GetValueInteger($id) <> $value)
        {
            SetValueInteger($id, $value);
            return true;
        }
        return false;
    }

    private function SetValueString($Ident, $value)
    {
        $id = $this->GetIDForIdent($Ident);
        if (GetValueString($id) <> $value)
        {
            SetValueString($id, $value);
            return true;
        }
        return false;
    }

    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus'])
            parent::SetStatus($InstanceStatus);
    }

    //Remove on next Symcon update
    protected function RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize)
    {

        if (!IPS_VariableProfileExists($Name))
        {
            IPS_CreateVariableProfile($Name, 1);
        }
        else
        {
            $profile = IPS_GetVariableProfile($Name);
            if ($profile['ProfileType'] != 1)
                throw new Exception("Variable profile type does not match for profile " . $Name);
        }

        IPS_SetVariableProfileIcon($Name, $Icon);
        IPS_SetVariableProfileText($Name, $Prefix, $Suffix);
        IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
    }

    protected function RegisterProfileIntegerEx($Name, $Icon, $Prefix, $Suffix, $Associations)
    {
        if (sizeof($Associations) === 0)
        {
            $MinValue = 0;
            $MaxValue = 0;
        }
        else
        {
            $MinValue = $Associations[0][0];
            $MaxValue = $Associations[sizeof($Associations) - 1][0];
        }

        $this->RegisterProfileInteger($Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, 0);
        $old = IPS_GetVariableProfile($Name)["Associations"];
        $OldValues = array_column($old,'Value');
        foreach ($Associations as $Association)
        {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
            $OldKey = array_search($Association[0],$OldValues);
            if (!($OldKey === false ))
            {
                unset($OldValues[$OldKey]);
            }
        }
        foreach ($OldValues as $OldKey => $OldValue)
        {
            IPS_SetVariableProfileAssociation($Name, $OldValue, '', '', 0);
        }
    }

}

?>