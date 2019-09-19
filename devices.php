<?php

require_once('general.php');

class tradfridevices extends tradfri
	{

	function getDimmer($Id){

		$dimid = $this->getDetails("15001/$Id");

		return $dimid['3311']['0'][DIMMER];

		}

	function getIds(){

		return explode(",", trim(str_replace(['[',']'], "" ,strstr($this->query("15001"), '[65'))));

		}

	function getIdbyName($name){

		$Ids = $this->getIds() ?? [];
		foreach($Ids as $Id){
			$Idname = strtolower(trim($this->getName("15001/$Id")));
			$Idname = str_replace(['ä','ö','ü','ß'], ['ae','oe','ue','ss'], $Idname);
			if(strcasecmp(trim($name), $Idname) == 0)
				$output = $Id;
			}

		if(isset($output))
			return $output;

		else
			return NULL;

		}

	function getTypeId($Id){

		$tid = $this->getDetails("15001/$Id");

		return $tid[TYPE];

		}

	function getPowerStatus($Id){

		$psid = $this->getDetails("15001/$Id");

		return $psid['3311']['0'][ONOFF];

		}

	function poweroff($path){

		switch($this->getTypeId($path)){
			case TYPE_LIGHT:
				$payload = '{ "3311": [{ "5850": 0 }] }';
				break;

  			case TYPE_CONTROL_OUTLET:
				$payload = '{ "3312“: [{ "5850": 0 }] }';
				break;

			default:
				$payload = NULL;
			}

		if (!is_null($payload)){
			$this->action("put", $payload, "15001/$path");

			if($this->getPowerStatus($path) == 0)
				return $this->getName("15001/$path")." wurde ausgeschaltet";
			else
				return $this->getName("15001/$path")." konnte nicht ausgeschaltet werden";
			}

		else
			return $this->getName("15001/$device")." konnte nicht ausgeschaltet werden, da es keine Lampe ist";

		}

	function poweron($path){

		switch($this->getTypeId($path)){
			case TYPE_LIGHT:
				$payload = '{ "3311": [{ "5850": 1 }] }';
				break;

  			case TYPE_CONTROL_OUTLET:
				$payload = '{ "3312“: [{ "5850": 1 }] }';
				break;

			default:
				$payload = NULL;
			}

		if (!is_null($payload)){

			$this->action("put", $payload, "15001/$path");

			if($this->getPowerStatus($path) == 1)
				return $this->getName("15001/$path")." wurde eingeschaltet";
			else
				return $this->getName("15001/$path")." konnte nicht eingeschaltet werden";
			}

		else
			return $this->getName("15001/$device")." konnte nicht eingeschaltet werden, da es keine Lampe ist";

		}

	function setDimmer($path, $dimmer, $transition = NULL){

		if($this->getTypeId($path) == TYPE_LIGHT){

			$dim = round(254 * (int)str_replace("%", "", trim($dimmer)) / 100, 0);

			$payload = is_null($transition) ? '{ "3311": [{ "5851" : '.$dim.' }] }' : '{ "3311": [{ "5851": '.$dim.', "5712": '.$transition.' }] }';
			$this->action("put", $payload, "15001/$path");

			if($this->getDimmer($path) == $dim)
				return $this->getName("15001/$path")." wurde auf {$dimmer} gedimmt";
			else
				return $this->getName("15001/$path")." konnte nicht auf {$dimmer} gedimmt werden";

			}

		else
			return $this->getName("15001/$device")." konnte nicht gedimmt werden, da es keine Lampe ist";

		}

	function statuscontroloutlet(){

		$Ids = $this->getIds();
		sort($Ids, SORT_NUMERIC);
		
		foreach($Ids as $device){
			$details = $this->getDetails("15001/$device");
			if($details[TYPE] == TYPE_CONTROL_OUTLET){
				$output[] = array("id" => $device,"name" => $details[NAME], "type" => $details['3']['1'], "firmware" => $details['3']['3'], "lastseen" => date('H:i:s d.m.Y', $details[LAST_SEEN]), "lastseenunix" => $details[LAST_SEEN], "power" => $details['3312']['0'][ONOFF]);
				}
			}

		return $output;

		}

	function statusremotecontrol(){

		$Ids = $this->getIds();
		sort($Ids, SORT_NUMERIC);

		foreach($Ids as $device){
			$details = $this->getDetails("15001/$device");
			if($details[TYPE] == TYPE_REMOTE_CONTROL || $details[TYPE] == TYPE_MOTION_SENSOR){
				$output[] = array("id" => $device,"name" => $details[NAME], "type" => $details['3']['1'], "battery" => $details['3']['9'], "firmware" => $details['3']['3'], "lastseen" => date('H:i:s d.m.Y', $details[LAST_SEEN]), "lastseenunix" => $details[LAST_SEEN]);
				}
			}

		return $output;

		}

	function statuslamps(){

		$Ids = $this->getIds();
		sort($Ids, SORT_NUMERIC);

		foreach($Ids as $device){
			$details = $this->getDetails("15001/$device");
			if($details[TYPE] == TYPE_LIGHT){

				$colorhex = isset($details[LIGHT]['0'][COLORHEX]) ? $details[LIGHT]['0'][COLORHEX] : NULL;
				$colorx = isset($details[LIGHT]['0'][COLORX]) ? $details[LIGHT]['0'][COLORX] : NULL;
				$colory = isset($details[LIGHT]['0'][COLORY]) ? $details[LIGHT]['0'][COLORY] : NULL;
				$colortemp = isset($details[LIGHT]['0'][COLORTEMP]) ? $details[LIGHT]['0'][COLORTEMP] : NULL;
				$transition = isset($details[LIGHT]['0'][TRANSITION]) ? $details[LIGHT]['0'][TRANSITION] : NULL;

				//Set Dimmer to the INT Value in %
				$details[LIGHT]['0'][DIMMER] = round($details[LIGHT]['0'][DIMMER] * 100 / 255);

				$output[] = array("id" => $device,"name" => $details[NAME], "type" => $details['3']['1'], "power" => $details[LIGHT]['0'][ONOFF], "dimmer" => $details[LIGHT]['0'][DIMMER], "colorhex" => $colorhex, "colorx" => $colorx, "colory" => $colory, "colortemp" => $colortemp, "transition" => $transition, "firmware" => $details['3']['3'], "lastseen" => date('H:i:s d.m.Y', $details[LAST_SEEN]), "lastseenunix" => $details[LAST_SEEN]);

				//Clean up
				unset($colorhex, $colorx, $colory, $colortemp, $transition);

				}
			}

		return $output;

		//Clean up
		unset($Ids, $device, $output);

		}

	}

?>
