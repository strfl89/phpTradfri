<?php

require_once('general.php');

class tradfridevices extends tradfri
	{

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

		if($this->getTypeId($path) == TYPE_LIGHT){
			$payload = '{ "3311": [{ "5850": 0 }] }';
			$this->action("put", $payload, "15001/$path");

			if($this->getPowerStatus($path) == 0)
				return $this->getName("15001/$path")." wurde ausgeschaltet";
			else
				return $this->getName("15001/$path")." konnte nicht ausgeschaltet werden";
			}

		else
			return "Gerät kann nicht ausgeschalet werden, da es keine Lampe ist";

		}

	function poweron($path){

		if($this->getTypeId($path) == TYPE_LIGHT){
			$payload = '{ "3311": [{ "5850": 1 }] }';
			$this->action("put", $payload, "15001/$path");

			if($this->getPowerStatus($path) == 1)
				return $this->getName("15001/$path")." wurde eingeschaltet";
			else
				return $this->getName("15001/$path")." konnte nicht eingeschaltet werden";
			}

		else
			return "Gerät konnte nicht eingeschaltet werden, da es keine Lampe ist";

		}

	function statusremotecontrol(){

		$Ids = $this->getIds();
		foreach($Ids as $device){
			$details = $this->getDetails("15001/$device");
			if($details[TYPE] == TYPE_REMOTE_CONTROL || $details[TYPE] == TYPE_MOTION_SENSOR){
				$output[] = array("id" => $device,"name" => $details[NAME], "type" => $details['3']['1'], "battery" => $details['3']['9']);
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

				$output[] = array("id" => $device,"name" => $details[NAME], "type" => $details['3']['1'], "power" => $details[LIGHT]['0'][ONOFF], "dimmer" => $details[LIGHT]['0'][DIMMER], "colorhex" => $colorhex, "colorx" => $colorx, "colory" => $colory, "colortemp" => $colortemp, "transition" => $transition);

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
