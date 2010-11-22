<?php
/*
 ----------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

	This file is part of GLPI.

    FusionInventory is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    FusionInventory is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with FusionInventory; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
*/

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusinvsnmpLog extends CommonDBTM {
	
	function write($file,$text,$type,$ID_Device,$debug=0) {
		global $CFG_GLPI;

      $config = new PluginFusioninventoryConfigSNMPScript;

      if (($config->getValue("logs") == '1') AND ($debug == '0')) {
         error_log("[".convDateTime(date("Y-m-d H:i:s"))."][".$type."][".$ID_Device."] ".$text."\n",3,GLPI_LOG_DIR."/".$file.".log");
      } else if ($config->getValue("logs") == '2') {
         error_log("[".convDateTime(date("Y-m-d H:i:s"))."][".$type."][".$ID_Device."] ".$text."\n",3,GLPI_LOG_DIR."/".$file.".log");
      }
	}
}

?>