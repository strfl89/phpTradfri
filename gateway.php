<?php

require_once('general.php');

class tradfrigateway extends tradfri
	{

		function reboot(){

			$this->action("post", "", "15011/9030");

			// Response
			// v:1 t:CON c:POST i:3bb9 {} [ ]
			// decrypt_verify(): found 24 bytes cleartext
			// decrypt_verify(): found 4 bytes cleartext
			
		}

		function statusgateway(){

			$details = $this->getDetails(GATEWAY."/15012");

			$output = array('setup' => $details[GATEWAY_SETUP_TIME], 'ntp' => $details['GATEWAY_NTP'], 'time' => $details[GATEWAY_TIME_UNIX], 'firmware' => $details[GATEWAY_FIRMWARE], 'alexa' => $details[GATEWAY_ALEXA_STATUS], 'google' => $details[GATEWAY_GOOGLE_STATUS]);

			return $output;
		}

	}

?>