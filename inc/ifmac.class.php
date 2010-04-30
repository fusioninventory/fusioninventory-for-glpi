<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryIfmac extends CommonDBTM {
   /* To check if a value is hexadecimal */
   static function is_hex($value) {
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

   static function ifmacwalk_ifmacaddress($mac) {
      $MacAddress = str_replace("0x","",$mac);
      $MacAddress_tmp = str_split($MacAddress, 2);
      $MacAddress = $MacAddress_tmp[0];
      for($i=1 ; $i < count($MacAddress_tmp) ; $i++) {
         $MacAddress .= ":".$MacAddress_tmp[$i];
      }
      return $MacAddress;
   }
}

?>