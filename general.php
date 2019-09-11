<?php

//require_once('ikea-smart-home.config.php');
require_once('define.php');

class tradfri
	{

	privat $gateway;

	function __construct($user, $secret, $gwip){

			$gateway['user'] = $user;
			$gateway['secretkey'] = $secret;
			$gateway['ip'] = $gwip;

		}

	function query($path){

		$cmd = "coap-client -m get -u '{$gateway['user']}' -k '{$gateway['secretkey']}' 'coaps://{$gateway['ip']}:5684/{$path}'";
		$process = proc_open($cmd, [STDOUT => ['pipe', 'w'], STDERR => ['pipe', 'w']], $output);

		//read the outputs
		$stdout = stream_get_contents($output[STDOUT]);
		$stderr = stream_get_contents($output[STDERR]);

		//clean up and properly close our handles
		fclose($output[STDOUT]);
		fclose($output[STDERR]);
		$rc = proc_close($process);

		//$result = json_decode(strstr($stdout,'{"'), true);
		$result = json_encode($stdout, true);

		return $stdout;
		//return $result;

		}

	function action($method, $payload, $path){
		$cmd = "coap-client -m {$method} -u '{$gateway['user']}' -k '{$gateway['secretkey']}' -e '{$payload}' 'coaps://{$gateway['ip']}:5684/{$path}'";
		exec($cmd);

		}

	function getDeviceIds(){

		return explode(",", trim(str_replace(['[',']'], "" ,strstr($this->query("15001"), '[65'))));

		}

	function getDetails($path){

		return json_decode(strstr($this->query($path), '{"'), true);

		}

	function getName($path){

		$details = $this->getDetails($path);

		return trim($details[NAME]);

		}

	}
//End of Class tradfri

?>
