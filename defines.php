<?php

//stream identifiers for proc_open
define('STDIN', 0);
define('STDOUT', 1);
define('STDERR', 2);

//IKEA Devices identifiers
define('NAME', 9001);
define('LIGHT', 3311);
define('ONOFF', 5850);					//3311/0/5850 = device on/off
define('DIMMER', 5851);					//3311/0/5851 = device brightness
define('COLORHEX', 5706);				//3311/0/5706
define('COLORX', 5709);					//3311/0/5709 => don't use 5706 and 5709 + 5710 at the same time
define('COLORY', 5710);
define('COLORTEMP', 5711);
define('TRANSITION', 5712);				//Fade Time
define('TYPE', 5750);
define('TYPE_REMOTE_CONTROL', 0);		//5750 = 0 => Default Remote Control
define('TYPE_LIGHT', 2);				//5750 = 2 => All lightning Devices (Driver, Lamps, ...)
define('TYPE_CONTROL_OUTLET', 3);		//5750 = 3 => Control Outlet for power Switching
define('TYPE_MOTION_SENSOR', 4);		//5750 = 4 => Motion Sensor
define('TYPE_SYMFONISK_CONTROLLER', 8);
define('GATEWAY', 15011);
define('GATEWAY_NTP', 9023);
define('GATEWAY_FIRMWARE', 9029);
define('GATEWAY_ALEXA_STATUS', 9093);
define('GATEWAY_GOOGLE_STATUS', 9105);
define('GATEWAY_TIME_UNIX', 9059);
define('GATEWAY_SETUP_TIME', 9069);
define('LAST_SEEN', 9020);
define('TIMERS', 15010);
define('TIMERS_TYPE', 9040);			//4 = transition | 1 = not home | 2 = on/off
define('TIMERS_TYPE_NOT_AT_HOME', 1);
define('TIMERS_TYPE_ONOFF', 2);
define('TIMERS_TYPE_WAKEUP', 4);
define('TIMERS_START_ACTION', 9042);
define('TIMERS_START_HOUR', 9046);
define('TIMERS_START_MIN', 9047);
define('TIMERS_END_HOUR', 9048);
define('TIMERS_ED_MIN', 9049);

?>
