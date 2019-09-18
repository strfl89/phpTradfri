# phpTradfri - Version History
## Version 2.3 - 18th Sep 2019
* devices.php
  + add Last Seen Information for Lamps and Remote Controls
  + add statuscontroloutlet()
## Version 2.2 - 16th Sep 2019
+ getDimmer() and setDimmer() added to devices.php
## Version 2.1.1 - 14th Sep 2019
* Fix missing return in Function statusgateway()
## Version 2.1 - 14th Sep 2019
* Add Subclass for Trådfri Gateway
* defines.php
  + defines for gateway
* general.php
  * extend function action() for Gateway Class (reboot command has no payload to transmit)
* include.php
  + add gateway.php for including gateway Sublcass
* ReadMe
  + Gateway functions
* Release Notes
  * create Release Notes
## Version 2.0 - 11th Sep 2019
* general.php
  * Move from Constants to Parameters for Gateway Config
  * to initialize new object parameters User, Secret and Gateway IP must enterd. Example:
    ```
    $groups = new tradfrigroups("<user>", "<secret>", "<ip>");
    ```
  * cleaning up file
* Update ReadMe File
## Version 1.1 - 09th Sep 2019
* devices
  + Output of Firmware Version at statusremotecontrol()
  + Add Motion Sensor to statusremotecontrol()
## Version 1.0 - 30th Aug 2019
* Initial Commit