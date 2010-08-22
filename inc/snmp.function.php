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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}





function plugin_fusioninventory_hex_to_string($value) {
	if (strstr($value, "0x0115")) {
		$hex = str_replace("0x0115","",$value);
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2) {
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
      }
		$value = $string;
	}
	if (strstr($value, "0x")) {
		$hex = str_replace("0x","",$value);
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2) {
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
      }
		$value = $string;
	}
	return $value;
}


?>