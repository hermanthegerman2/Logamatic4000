<?php
require_once(__DIR__ . "/../buderus4000.php");  // diverse Klassen

class Logamatic4311 extends IPSModule
{

    private $Bus, $Connected = 'noInit';

    public function Create()
    {
        //Never delete this line!
        parent::Create();

        // 1. Verfügbarer Logamatic-Splitter wird verbunden oder neu erzeugt, wenn nicht vorhanden.
        $this->ConnectParent("{24F1DF95-D340-48DB-B0CC-ABB40B12BCAA}");
        $this->RegisterPropertyString("Bus", "");
        
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        // Addresse prüfen
        $Bus = $this->ReadPropertyString('Bus');
        if ($Bus == '')
        {
            // Status inaktiv
            $this->SetStatus(104);
        }
        else
        {
            
                        if (strlen($Bus) > 0)
                        {
                            $this->SetStatus(102);
                            // STATUS config OK
                        }
                        else
                        {
                            $Bus = '';
                            $this->SetStatus(202);
                            // STATUS config falsch
                        }
                        IPS_SetProperty($this->InstanceID, 'Bus', $Bus);
                        IPS_ApplyChanges($this->InstanceID);
                        return;
        }
                    
  


        /* Profile anlegen
        $this->RegisterProfileIntegerEx("Status.Squeezebox", "Information", "", "", Array(
            Array(0, "Prev", "", -1),
            Array(1, "Stop", "", -1),
            Array(2, "Play", "", -1),
            Array(3, "Pause", "", -1),
            Array(4, "Next", "", -1)
        ));
        $this->RegisterProfileInteger("Intensity.Squeezebox", "Intensity", "", " %", 0, 100, 1);
        $this->RegisterProfileIntegerEx("Shuffle.Squeezebox", "Shuffle", "", "", Array(
            Array(0, "off", "", -1),
            Array(1, "Title", "", -1),
            Array(2, "Album", "", -1)
        ));
        $this->RegisterProfileIntegerEx("Repeat.Squeezebox", "Repeat", "", "", Array(
            Array(0, "off", "", -1),
            Array(1, "Title", "", -1),
            Array(2, "Playlist", "", -1)
        ));
        $this->RegisterProfileIntegerEx("Preset.Squeezebox", "Speaker", "", "", Array(
            Array(1, "1", "", -1),
            Array(2, "2", "", -1),
            Array(3, "3", "", -1),
            Array(4, "4", "", -1),
            Array(5, "5", "", -1),
            Array(6, "6", "", -1)
        ));
        $this->RegisterProfileInteger("Tracklist.Squeezebox." . $this->InstanceID, "", "", "", 1, 1, 1);
        $this->RegisterProfileIntegerEx("SleepTimer.Squeezebox", "Gear", "", "", Array(
            Array(0, "%d", "", -1),
            Array(900, "%d", "", -1),
            Array(1800, "%d", "", -1),
            Array(2700, "%d", "", -1),
            Array(3600, "%d", "", -1),
            Array(5400, "%d", "", -1)
        ));


        //Status-Variablen anlegen
        $this->RegisterVariableBoolean("Power", "Power", "~Switch", 1);
        $this->EnableAction("Power");
        $this->RegisterVariableInteger("Status", "Status", "Status.Squeezebox", 3);
        $this->EnableAction("Status");
        $this->RegisterVariableInteger("Preset", "Preset", "Preset.Squeezebox", 2);
        $this->EnableAction("Preset");
        $this->RegisterVariableBoolean("Mute", "Mute", "~Switch", 4);
        $this->EnableAction("Mute");

        $this->RegisterVariableInteger("Volume", "Volume", "Intensity.Squeezebox", 5);
        $this->EnableAction("Volume");
        $this->RegisterVariableInteger("Bass", "Bass", "Intensity.Squeezebox", 6);
        $this->EnableAction("Bass");
        $this->RegisterVariableInteger("Treble", "Treble", "Intensity.Squeezebox", 7);
        $this->EnableAction("Treble");
        $this->RegisterVariableInteger("Pitch", "Pitch", "Intensity.Squeezebox", 8);
        $this->EnableAction("Pitch");

        $this->RegisterVariableInteger("Shuffle", "Shuffle", "Shuffle.Squeezebox", 9);
        $this->EnableAction("Shuffle");
        $this->RegisterVariableInteger("Repeat", "Repeat", "Repeat.Squeezebox", 10);
        $this->EnableAction("Repeat");
        $this->RegisterVariableInteger("Tracks", "Playlist Anzahl Tracks", "", 11);
        $this->RegisterVariableInteger("Index", "Playlist Position", "Tracklist.Squeezebox." . $this->InstanceID, 12);
        $this->EnableAction("Index");

        $this->RegisterVariableString("Playlistname", "Playlist", "", 19);
        $this->RegisterVariableString("Album", "Album", "", 20);
        $this->RegisterVariableString("Title", "Titel", "", 21);
        $this->RegisterVariableString("Interpret", "Interpret", "", 22);
        $this->RegisterVariableString("Genre", "Stilrichtung", "", 23);
        $this->RegisterVariableString("Duration", "Dauer", "", 24);
        $this->RegisterVariableString("Position", "Spielzeit", "", 25);
        $this->RegisterVariableInteger("Position2", "Position", "Intensity.Squeezebox", 26);
        $this->EnableAction("Position2");
        $this->RegisterVariableString("Playlist", "Playlist", "~HTMLBox", 29);

        $this->RegisterVariableInteger("Signalstrength", utf8_encode("Signalstärke"), "Intensity.Squeezebox", 30);
        $this->RegisterVariableInteger("SleepTimer", "Einschlaftimer", "SleepTimer.Squeezebox", 31);
        $this->EnableAction("SleepTimer");
        $this->RegisterVariableString("SleepTimeout", "Ausschalten in ", "", 32);

        // Workaround für persistente Daten der Instanz.
        $this->RegisterVariableBoolean("can_seek", "can_seek", "", -5);
        $this->RegisterVariableString("BufferOUT", "BufferOUT", "", -4);
        $this->RegisterVariableBoolean("WaitForResponse", "WaitForResponse", "", -5);
        $this->RegisterVariableBoolean("Connected", "Connected", "", -3);

        $this->RegisterVariableInteger("PositionRAW", "PositionRAW", "", -1);
        $this->RegisterVariableInteger("DurationRAW", "DurationRAW", "", -2);
        IPS_SetHidden($this->GetIDForIdent('can_seek'), true);
        IPS_SetHidden($this->GetIDForIdent('BufferOUT'), true);
        IPS_SetHidden($this->GetIDForIdent('WaitForResponse'), true);
        IPS_SetHidden($this->GetIDForIdent('Connected'), true);
        IPS_SetHidden($this->GetIDForIdent('PositionRAW'), true);
        IPS_SetHidden($this->GetIDForIdent('DurationRAW'), true);
        */
        // Eigene Scripte
 
        $this->RegisterVariableString("BufferOUT", "BufferOUT", "", -4);
        $this->RegisterVariableBoolean("WaitForResponse", "WaitForResponse", "", -5);
        $this->RegisterVariableBoolean("Connected", "Connected", "", -3);
        $this->SetConnected(true);
        // Adresse nicht leer ?
        // Parent vorhanden und nicht in Fehlerstatus ?
       
    }

    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */
################## PUBLIC
    /**
     * This function will be available automatically after the module is imported with the module control.
     * Using the custom prefix this function will be callable from PHP and JSON-RPC through:
     */
   
    /**
     * Aktuellen Status des Devices ermitteln und, wenn verbunden, abfragen..
     *
     * @return boolean
     */
    public function RequestState()
    {
        $this->Init();
        $this->init();

          if ($this->Connected)
          {

        $this->SendLogamaticData(
                new LogamaticData(LogamaticResponse::direktmodus, 'DD', false)
        );
        $this->SendLogamaticData(
                new LogamaticData(LogamaticResponse::monitordaten, 'A200010000', false)
        );
           }          
        return true;
    }

    public function RawSend($Command, $Value, $needResponse)
    {
        $this->Init();

        $LogamaticData = new LogamaticData($Command, $Value, $needResponse);
        return $this->SendLogamaticData($LogamaticData);
        //return $this->SendDataToParent($LogamaticData);
    }

 
 ################## ActionHandler

    

################## PRIVATE

    private function SetConnected($Status)
    {
        $this->SetValueBoolean('Connected', $Status);
        $this->Connected = $Status;
        $this->Init(false);
        //if ($Status === true)
        //    $this->RequestState();
    }

    private function Init($throwException = true)
    {
        if ($this->Connected <> 'noInit')
            return true;

        $this->Address = $this->ReadPropertyString("Bus");
        //$this->Interval = $this->ReadPropertyInteger("Interval");
        $this->Connected = GetValueBoolean($this->GetIDForIdent('Connected'));
        if ($this->Bus == '')
        {
            $this->SetStatus(202);
            if ($throwException)
                throw new Exception('Address not set.');
            else
                return false;
        }
        $ParentID = $this->GetParent();
        if ($ParentID === false)
        {
            $this->SetStatus(104);
            if ($throwException)
                throw new Exception('Instance has no parent.');
            else
                return false;
        }
        else
        if (!$this->HasActiveParent($ParentID))
        {
            $this->SetStatus(203);
            if ($throwException)
                throw new Exception('Instance has no active parent.');
            else
                return false;
        }
        $this->SetStatus(102);

        return true;
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

################## DataPoints
    // Ankommend von Parent-Logamatic Gateway

    public function ReceiveData($JSONString)
    {
        $Data = json_decode($JSONString);

        $this->Init(false);
        if ($this->Bus === '') //Keine Adresse Daten nicht verarbeiten
            return false;

        // Adressen stimmen überein, die Daten sind für uns.
        if ($this->Bus = "1")
        {
            // Objekt erzeugen welches die Commands und die Values enthält.
            $Response = new LogamaticResponse($Data->Logamatic);

            // Ist das Command schon bekannt ?
            if ($Response->Command <> false)
            {
                // Daten prüfen ob Antwort
                $isResponse = $this->WriteResponse($Response->Command, $Response->Value);
                if (is_bool($isResponse))
                {
                    $Response->isResponse = $isResponse;
                    if (!$isResponse)
                    {
                        // Daten dekodieren
                        $this->decodeLogamaticEvent($Response);
                    }
                    return true;
                }
                else
                {
                    throw new Exception($isResponse);
                }
            }
            // Ignorierte Commands loggen
            /*
              else
              {
              IPS_LogMessage("ToDoLogamaticDevice: Unbekannter Datensatz:", print_r($Response->Value, 1));
              return true;
              } */
        }
        // Daten waren nicht für uns
        return false;
    }

    // Sende-Routine an den Parent
    protected function SendDataToParent($LogamaticData)
    {
        $LogamaticData->Bus = $this->ReadPropertyString('Bus');
        // Sende Lock setzen
        if (!$this->lock("ToParent"))
        {
            throw new Exception("Can not send to Logamatic Gateway");
        }
        // Daten senden
        try
        {
            $ret = IPS_SendDataToParent($this->InstanceID, json_encode(Array("DataID" => "{EDDCCB34-E194-434D-93AD-FFDF1B56EF38}", "Logamatic" => $LogamaticData)));
        }
        catch (Exception $exc)
        {
            // Senden fehlgeschlagen
            // Sende Lock aufheben
            $this->unlock("ToParent");
            throw new Exception("Logamatic Gateway not reachable");
        }
        // Sende Lock aufheben
        $this->unlock("ToParent");
        return $ret;
    }

    ################## Datenaustausch

    private function SendLogamaticData($LogamaticData)
    {
        $this->init();
        // prüfen ob Player connected ?
        // nur senden wenn connected ODER wir eine connected anfrage senden wollen
        if (!$this->Connected)
        {
            throw new Exception("Device not connected");
        }
        $ParentID = $this->GetParent();
        if (!($ParentID === false))
        {
            if (!$this->HasActiveParent($ParentID))
                return;
        }
        else
            return;

        if ($LogamaticData->needResponse)
        {
            //Semaphore setzen
            if (!$this->lock("LogamaticData"))
            {
                throw new Exception("Can not send to Logamatic Gateway");
            }
            // Anfrage f??r die Warteschleife schreiben
            if (!$this->SetWaitForResponse($LogamaticData->Command))
            {
                $this->unlock("LogamaticData");
                throw new Exception("Can not send to Logamatic Gateway");
            }
            try
            {
                $this->SendDataToParent($LogamaticData);
            }
            catch (Exception $exc)
            {
                //  Daten in Warteschleife l?¶schen
                $this->ResetWaitForResponse();
                $this->unlock("LogamaticData");
                throw $exc;
            }
            // Auf Antwort warten....
            $ret = $this->WaitForResponse();
            // SendeLock  velassen
            $this->unlock("LogamaticData");

            if ($ret === false) // Warteschleife lief in Timeout
            {
                //  Daten in Warteschleife l?¶schen                
                $this->ResetWaitForResponse();
                // Fehler
                throw new Exception("No answer from Logamatic");
            }
            return $ret;
        }
        else
        {
            try
            {
                $this->SendDataToParent($LogamaticData);
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
//        throw new Exception("No answer from Logamatic789");

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
//                throw new Exception("No answer from Logamatic123");

                return false;
            }
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
//        throw new Exception("No answer from Logamatic456");

        return false;
    }

    private function WriteResponse($Command, $Value)
    {
        if (is_array($Command))
            $Command = implode(' ', $Command);
        if (is_array($Value))
            $Value = implode(' ', $Value);

        $EventID = $this->GetIDForIdent('WaitForResponse');
        if (!GetValueBoolean($EventID))
            return false;
        $BufferID = $this->GetIDForIdent('BufferOUT');
        if ($Command == GetValueString($BufferID))
        {
            if ($this->lock('BufferOut'))
            {
                SetValueString($BufferID, trim($Value));
                SetValueBoolean($EventID, false);
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
            if (IPS_SemaphoreEnter("Logamatic_" . (string) $this->InstanceID . (string) $ident, 1))
            {
                return true;
            }
            else
                IPS_Sleep(mt_rand(1, 5));
        }
        return false;
    }

    private function unlock($ident)
    {
        IPS_SemaphoreLeave("Logamatic_" . (string) $this->InstanceID . (string) $ident);
    }

################## DUMMYS / WOARKAROUNDS - protected

    protected function HasActiveParent()
    {
        $instance = IPS_GetInstance($this->InstanceID);
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
        $instance = IPS_GetInstance($this->InstanceID);
        return ($instance['ConnectionID'] > 0) ? $instance['ConnectionID'] : false;
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

        foreach ($Associations as $Association)
        {
            IPS_SetVariableProfileAssociation($Name, $Association[0], $Association[1], $Association[2], $Association[3]);
        }
    }

    protected function SetStatus($InstanceStatus)
    {
        if ($InstanceStatus <> IPS_GetInstance($this->InstanceID)['InstanceStatus'])
            parent::SetStatus($InstanceStatus);
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

}

?>