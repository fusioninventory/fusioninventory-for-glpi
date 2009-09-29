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

if (!defined('GLPI_ROOT'))  {
	die("Sorry. You can't access directly to this file");
}

/* To check if a value is hexadecimal */
function plugin_tracker_is_hex($value) {
	$hex=0;
	$len = strlen($value);
	$value = str_split($value);
	for ($i=0 ; $i<$len ; $i++) {
		if ((($value["$i"] >= '0') && ($value["$i"] <= '9')) 
         || (($value["$i"] >= 'a') &&  ($value["$i"] <= 'f'))
         || (($value["$i"] >= 'A') &&  ($value["$i"] <= 'F'))) {
         
         $hex++;
      }
	}
	if ($hex == $len) {
		return true;
   } else {
		return false;
   }
}

/* To convert a string to a MAC address... Return false if not a MAC */
function plugin_tracker_stringToIfmac($string) {
	if ($string == "") {
		return false;
   }
	// if MAC adress without any separation character : 12 characters
	if (strlen($string) == 12) { 
		$string = str_split($string, 2);
	} else {
		// to seperate each element of the MAC address in an array
		$string = split("[ :-]", $string);
		for ($i=0 ; $i<count($string) ; $i++) {
			// if value like "0" or "x" instead of "00" or "0x", put "0" before
			if (strlen($string["$i"]) == 1) {
				$string["$i"] = "0".$string["$i"];
         // if length not equal to 2, not a correct value => unset
         } else if (strlen($string["$i"]) != 2) {
				unset($string["$i"]);
         }
		}
		if (count($string) != 6) {
			return false;
      }
	}
	// check if mac address not equal to : "00:00:00:00:00:00" and if each value is hexadecimal
	$i=0;
	$null=0;
	while (($i<6)  && ( ($is_hex = plugin_tracker_is_hex($string["$i"])) != false )) {
		if ($string["$i"] == "00") {
			$null++;
      }
		$i++;
	}
	if (($null != 6) && ($is_hex != false)) {
		$ifmac = implode (':', $string);
		$ifmac = strtoupper($ifmac); // uppercase
		return $ifmac;
	} else {
		return false;
   }
}

/* transforms an hexadecimal MAC address to a decimal MAC address, like "%d.%d.%d.%d.%d.%d" */
function plugin_tracker_ifmacToDecimal($ifmac) {
	$decimal = explode(":", $ifmac);
	for($i=0 ; $i<6 ; $i++) {
		$decimal["$i"] = hexdec($decimal["$i"]);
	}
	$decimal = implode(".", $decimal);
	return $decimal;
}

function plugin_tracker_ifmacwalk_ifmacaddress($mac) {
   $MacAddress = str_replace("0x","",$mac);
   $MacAddress_tmp = str_split($MacAddress, 2);
   $MacAddress = $MacAddress_tmp[0];
   for($i=1 ; $i < count($MacAddress_tmp) ; $i++) {
      $MacAddress .= ":".$MacAddress_tmp[$i];
   }
   return $MacAddress;
}

?>
