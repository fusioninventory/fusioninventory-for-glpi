<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

/*Below, you can find all the OIDs used by Tracker*/

/*General*/
define('MIB_NAME','1.3.6.1.2.1.1.5.0'); // sysName.0
define('MIB_CONTACT','1.3.6.1.2.1.1.4.0'); // sysContact.0
define('MIB_LOCATION','1.3.6.1.2.1.1.6.0'); // sysLocation.0



/*With prefix - General*/
define('MIB_NETMASK_PREFIX','1.3.6.1.2.1.4.20.1.3'); // ipAdEntNetMask, suffix = ip


/*Printer*/
define('MIB_PRINTER_MODEL','1.3.6.1.2.1.25.3.2.1.3.1');
define('MIB_PRINTER_SERIAL','1.3.6.1.2.1.43.5.1.1.17.1'); // prtGeneralSerialNumber
define('MIB_PRINTER_COUNTER','SNMPv2-SMI::mib-2.43.10.2.1.4.1.1'); // SNMPv2-SMI::mib- equiv. to : 1.3.6.1.2.1.
define('MIB_PRINTER_IFMAC_1','1.3.6.1.2.1.2.2.1.6.1');
define('MIB_PRINTER_IFMAC_2','1.3.6.1.2.1.2.2.1.6.2');


/*Switch*/
define('MIB_SWITCH_MODEL','1.3.6.1.2.1.47.1.1.1.1.2.1'); // entPhysicalDescr.1
define('MIB_SWITCH_SERIAL','1.3.6.1.2.1.47.1.1.1.1.11.1'); // entPhysicalSerialNum.1
define('MIB_SWITCH_FIRMWARE','1.3.6.1.2.1.1.1.0'); // sysDescr.0
define('MIB_SWITCH_IFMAC','1.3.6.1.2.1.17.1.1.0'); // dot1dBaseBridgeAddress.0

define('MIB_CISCO_SWITCH_RAM','1.3.6.1.4.1.9.3.6.6.0'); // processorRam (or CiscoTotalMem)

/*With prefix - switch*/
define('MIB_SWITCH_PORT_PREFIX','SNMPv2-SMI::mib-2.17.4.3.1.2'); //suffix = ifmac (decimal)
define('MIB_SWITCH_STATE_PREFIX','SNMPv2-SMI::mib-2.17.4.3.1.3'); // to check if type == 3 (learn), suffix = ifmac (decimal)

define('MIB_SWITCH_PORT_DESCR_PREFIX','1.3.6.1.2.1.2.2.1.2'); // ifDescr, suffix = port

?>
