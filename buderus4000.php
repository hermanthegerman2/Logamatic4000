<?php

class Command extends stdClass
{
    
    const NUL = 0x00;
    const Direktmodus = 0xDD; //Umschalten von Normalmodus -> Direktmodus
    const Normalmodus = 0xDC; //Umschalten von Direktmodus -> Normalmodus
    const Monitordaten = 0xA2; //Monitordaten anfordern
        
}

function str2hex($string) // Funktion String in Hex umwandeln
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
			{
			$hex .=dechex(ord($string[$i]))." ";
			}
		return $hex;
	}

function CheckVariable($name)
   {
  		$InstanzID = @IPS_GetVariableIDByName($name, $this->InstanceID);
                if ($InstanzID === false)
                {
                $InstanzID = IPS_CreateVariable(3);
                IPS_SetName($InstanzID, $name); // Instanz benennen
                IPS_SetParent($InstanzID, $this->InstanceID);
                }
                //echo "ID: ".$InstanzID." ".$name."\n";
                return $InstanzID;
   }
   
function CheckVariableTYP($name, $vartyp, $profile)
   {
  		$InstanzID = @IPS_GetVariableIDByName($name, IPS_GetParent($_IPS['SELF']));
                if ($InstanzID === false)
                {
                $InstanzID = IPS_CreateVariable($vartyp);
                IPS_SetName($InstanzID, $name); // Instanz benennen
                IPS_SetParent($InstanzID, IPS_GetParent($_IPS['SELF']));
                IPS_SetVariableCustomProfile($InstanzID, $profile);
					 }
                //echo "ID: ".$InstanzID." ".$name."\n";
                return $InstanzID;
   }
?>