<?php

require_once('general.php');

class tradfrigroups extends tradfri
	{

	function getDimmer($Id){

		$dimid = $this->getDetails("15004/$Id");

		return $dimid[DIMMER];

		}

	function getIds(){

		return explode(",", trim(str_replace(['[',']'], "" ,strstr($this->query("15004"), '[13'))));

		}

	function getIdbyName($name){

		$Ids = $this->getIds() ?? [];
		foreach($Ids as $Id){
			$Idname = strtolower(trim($this->getName("15004/$Id")));
			$Idname = str_replace(['ä','ö','ü','ß'], ['ae','oe','ue','ss'], $Idname);
			if(strcasecmp(trim($name), $Idname) == 0)
				$output = $Id;
			}

		if(isset($output))
			return $output;

		else
			return NULL;

		}

	function getPowerStatus($Id){

		$psid = $this->getDetails("15004/$Id");

		return $psid[ONOFF];

		}

	function poweroff($path){

		$payload = '{ "5850": 0 }';
		$this->action("put", $payload, "15004/$path");

		if($this->getPowerStatus($path) == 0)
			return $this->getName("15004/$path")." wurde ausgeschaltet";

		else
			return $this->getName("15004/$path")." konnte nicht ausgeschaltet werden";

		}

	function poweron($path){

		$payload = '{ "5850": 1 }';
		$this->action("put", $payload, "15004/$path");

		if($this->getPowerStatus($path) == 1)
			return $this->getName("15004/$path")." wurde eingeschaltet";

		else
			return $this->getName("15004/$path")." konnte nicht eingeschaltet werden";

		}

	function setDimmer($path, $dimmer){

		$dim = round(254 * (int)str_replace("%", "", trim($dimmer)) / 100, 0);

		$payload = '{ "'.DIMMER.'": '.$dim.' }';
		$this->action("put", $payload, "15004/$path");

		if($this->getDimmer($path) == $dim)
			return $this->getName("15004/$path")." wurde auf {$dimmer} gedimmt.";
		else
			return $this->getName("15004/$path")." konnte nicht auf {$dimmer} gedimmt werden.";

		}

	} // End of class tradfrigroups

?>
