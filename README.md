# phpTradfri
This project is a set of PHP Classes working with [libcoap](https://github.com/obgm/libcoap)'s coap-client to allow controlling IKEA's Trådfri smart lighting products. For this, coap-client is used to communicate with the Trådfri Gateway through [COAP](https://tools.ietf.org/html/rfc7252) over TLS.
## Features
* Querying a list of the following items configured in the gateway:
  * Devices
    * Lamps
    * Remote Controls
    * Motion Sensors
  * Groups
* Querying the status of the items 
  * On/Off
  * Brightness
  * Battery level
  * Names
* Turning lights and groups on and off
* Gateway Functions
  * Querying the Status informations
  * Reboot
## Requirements
* IKEA Trådfri Gateway
* PHP7 (with minor adjustments older versions can also be used)
* libcoap (only supports Linux unfortunately)
## Installation
1. Install coap-client with DTLS support. Preferrably, debug output from [tinydtls](https://projects.eclipse.org/projects/iot.tinydtls) should be disabled (otherwise adjustments in the scripts are required, see inside list.php for details on this). The script [install-coap-client.sh](https://github.com/ggravlingen/pytradfri/blob/master/script/install-coap-client.sh) from the [pytradfri](https://github.com/ggravlingen/pytradfri) repository automates this.
2. Place the scripts in a web server directory accessible to web browsers. Ensure executing processes on the command line is allowed in your PHP installation.
## Configuration
While creating an object in your code, you must provide the parameters for user, secretkey and IP Address
Example:
```
$groups = new tradfrigroups("<user>", "<secret>", "<ip>");
```
