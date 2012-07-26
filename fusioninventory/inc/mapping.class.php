<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryMapping extends CommonDBTM {

   /**
    * Get mapping
    *
    *@param $p_itemtype Mapping itemtype
    *@param $p_name Mapping name
    *@return mapping fields or false
    **/
   function get($p_itemtype, $p_name) {
      $data = $this->find("`itemtype`='".$p_itemtype."' AND `name`='".$p_name."'", "", 1);
      $mapping = current($data);
      if (isset($mapping['id'])) {
         return $mapping;
      }
      return false;
   }



   /**
    *
    * @param $parm
    */
   function set($parm) {
      global $DB;

      $data = current(getAllDatasFromTable("glpi_plugin_fusioninventory_mappings",
                                   "`itemtype`='".$parm['itemtype']."' AND `name`='".$parm['name']."'"));
      if (empty($data)) {
         // Insert
         $query = '';
         if (isset($parm['shortlocale'])) {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
                     VALUES ('".$parm['itemtype']."','".$parm['name']."','".$parm['table']."',
                             '".$parm['tablefield']."','".$parm['locale']."','".$parm['shortlocale']."')";
         } else {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`)
                     VALUES ('".$parm['itemtype']."','".$parm['name']."','".$parm['table']."',
                             '".$parm['tablefield']."','".$parm['locale']."')";
         }
         $DB->query($query);
      } elseif ($data['table'] != $parm['table']
                OR $data['tablefield'] != $parm['tablefield']
                OR $data['locale'] != $parm['locale']) {
         $data['table'] = $parm['table'];
         $data['tablefield'] = $parm['tablefield'];
         $data['locale'] = $parm['locale'];
         if (isset($parm['shortlocale'])) {
            $data['shortlocale'] = $parm['shortlocale'];
         }
         $this->update($data);
      }
   }

   function getTranslation ($mapping) {

   switch ($mapping['locale']) {
      case 1:
         return __('networking > location');
         breaks;

      case 2:
         return __('networking > firmware');
         breaks;

      case 3:
         return __('networking > uptime');
         breaks;

      case 4:
         return __('networking > port > mtu');
         breaks;

      case 5:
         return __('networking > port > speed');
         breaks;

      case 6:
         return __('networking > port > internal status');
         breaks;

      case 7:
         return __('networking > ports > last change');
         breaks;

      case 8:
         return __('networking > port > number of bytes entered');
         breaks;

      case 9:
         return __('networking > port > number of bytes out');
         breaks;

      case 10:
         return __('networking > port > number of input errors');
         breaks;

      case 11:
         return __('networking > port > number of errors output');
         breaks;

      case 12:
         return __('networking > CPU usage');
         breaks;

      case 13:
         return __('networking > serial number');
         breaks;

      case 14:
         return __('networking > port > connection status');
         breaks;

      case 15:
         return __('networking > port > MAC address');
         breaks;

      case 16:
         return __('networking > port > name');
         breaks;

      case 17:
         return __('networking > model');
         breaks;

      case 18:
         return __('networking > port > type');
         breaks;

      case 19:
         return __('networking > VLAN');
         breaks;

      case 20:
         return __('networking > name');
         breaks;

      case 21:
         return __('networking > total memory');
         breaks;

      case 22:
         return __('networking > free memory');
         breaks;

      case 23:
         return __('networking > port > port description');
         breaks;

      case 24:
         return __('printer > name');
         breaks;

      case 25:
         return __('printer > model');
         breaks;

      case 26:
         return __('printer > total memory');
         breaks;

      case 27:
         return __('printer > serial number');
         breaks;

      case 28:
         return __('printer > meter > total number of printed pages');
         breaks;

      case 29:
         return __('printer > meter > number of printed black and white pages');
         breaks;

      case 30:
         return __('printer > meter > number of printed color pages');
         breaks;

      case 31:
         return __('printer > meter > number of printed monochrome pages');
         breaks;

      case 33:
         return __('networking > port > duplex type');
         breaks;

      case 34:
         return __('printer > consumables > black cartridge (%)');
         breaks;

      case 35:
         return __('printer > consumables > photo black cartridge (%)');
         breaks;

      case 36:
         return __('printer > consumables > cyan cartridge (%)');
         breaks;

      case 37:
         return __('printer > consumables > yellow cartridge (%)');
         breaks;

      case 38:
         return __('printer > consumables > magenta cartridge (%)');
         breaks;

      case 39:
         return __('printer > consumables > light cyan cartridge (%)');
         breaks;

      case 40:
         return __('printer > consumables > light magenta cartridge (%)');
         breaks;

      case 41:
         return __('printer > consumables > photoconductor (%)');
         breaks;

      case 42:
         return __('printer > consumables > black photoconductor (%)');
         breaks;

      case 43:
         return __('printer > consumables > color photoconductor (%)');
         breaks;

      case 44:
         return __('printer > consumables > cyan photoconductor (%)');
         breaks;

      case 45:
         return __('printer > consumables > yellow photoconductor (%)');
         breaks;

      case 46:
         return __('printer > consumables > magenta photoconductor (%)');
         breaks;

      case 47:
         return __('printer > consumables > black transfer unit (%)');
         breaks;

      case 48:
         return __('printer > consumables > cyan transfer unit (%)');
         breaks;

      case 49:
         return __('printer > consumables > yellow transfer unit (%)');
         breaks;

      case 50:
         return __('printer > consumables > magenta transfer unit (%)');
         breaks;

      case 51:
         return __('printer > consumables > waste bin (%)');
         breaks;

      case 52:
         return __('printer > consumables > four (%)');
         breaks;

      case 53:
         return __('printer > consumables > cleaning module (%)');
         breaks;

      case 54:
         return __('printer > meter > number of printed duplex pages');
         breaks;

      case 55:
         return __('printer > meter > nomber of scanned pages');
         breaks;

      case 56:
         return __('printer > location');
         breaks;

      case 57:
         return __('printer > port > name');
         breaks;

      case 58:
         return __('printer > port > MAC address');
         breaks;

      case 59:
         return __('printer > consumables > black cartridge (max ink)');
         breaks;

      case 60:
         return __('printer > consumables > black cartridge (remaining ink )');
         breaks;

      case 61:
         return __('printer > consumables > cyan cartridge (max ink)');
         breaks;

      case 62:
         return __('printer > consumables > cyan cartridge (remaining ink)');
         breaks;

      case 63:
         return __('printer > consumables > yellow cartridge (max ink)');
         breaks;

      case 64:
         return __('printer > consumables > yellow cartridge (remaining ink)');
         breaks;

      case 65:
         return __('printer > consumables > magenta cartridge (max ink)');
         breaks;

      case 66:
         return __('printer > consumables > magenta cartridge (remaining ink)');
         breaks;

      case 67:
         return __('printer > consumables > light cyan cartridge (max ink)');
         breaks;

      case 68:
         return __('printer > consumables > light cyan cartridge (remaining ink)');
         breaks;

      case 69:
         return __('printer > consumables > light magenta cartridge (max ink)');
         breaks;

      case 70:
         return __('printer > consumables > light magenta cartridge (remaining ink)');
         breaks;

      case 71:
         return __('printer > consumables > photoconductor (max ink)');
         breaks;

      case 72:
         return __('printer > consumables > photoconductor (remaining ink)');
         breaks;

      case 73:
         return __('printer > consumables > black photoconductor (max ink)');
         breaks;

      case 74:
         return __('printer > consumables > black photoconductor (remaining ink)');
         breaks;

      case 75:
         return __('printer > consumables > color photoconductor (max ink)');
         breaks;

      case 76:
         return __('printer > consumables > color photoconductor (remaining ink)');
         breaks;

      case 77:
         return __('printer > consumables > cyan photoconductor (max ink)');
         breaks;

      case 78:
         return __('printer > consumables > cyan photoconductor (remaining ink)');
         breaks;

      case 79:
         return __('printer > consumables > yellow photoconductor (max ink)');
         breaks;

      case 80:
         return __('printer > consumables > yellow photoconductor (remaining ink)');
         breaks;

      case 81:
         return __('printer > consumables > magenta photoconductor (max ink)');
         breaks;

      case 82:
         return __('printer > consumables > magenta photoconductor (remaining ink)');
         breaks;

      case 83:
         return __('printer > consumables > black transfer unit (max ink)');
         breaks;

      case 84:
         return __('printer > consumables > black transfer unit (remaining ink)');
         breaks;

      case 85:
         return __('printer > consumables > cyan transfer unit (max ink)');
         breaks;

      case 86:
         return __('printer > consumables > cyan transfer unit (remaining ink)');
         breaks;

      case 87:
         return __('printer > consumables > yellow transfer unit (max ink)');
         breaks;

      case 88:
         return __('printer > consumables > yellow transfer unit (remaining ink)');
         breaks;

      case 89:
         return __('printer > consumables > magenta transfer unit (max ink)');
         breaks;

      case 90:
         return __('printer > consumables > magenta transfer unit (remaining ink)');
         breaks;

      case 91:
         return __('printer > consumables > waste bin (max ink)');
         breaks;

      case 92:
         return __('printer > consumables > waste bin (remaining ink)');
         breaks;

      case 93:
         return __('printer > consumables > four (max ink)');
         breaks;

      case 94:
         return __('printer > consumables > four (remaining ink)');
         breaks;

      case 95:
         return __('printer > consumables > cleaning module (max ink)');
         breaks;

      case 96:
         return __('printer > consumables > cleaning module (remaining ink)');
         breaks;

      case 97:
         return __('printer > port > type');
         breaks;

      case 98:
         return __('printer > consumables > maintenance kit (max)');
         breaks;

      case 99:
         return __('printer > consumables > maintenance kit (remaining)');
         breaks;

      case 400:
         return __('printer > consumables > maintenance kit (%)');
         breaks;

      case 401:
         return __('networking > CPU user');
         breaks;

      case 402:
         return __('networking > CPU system');
         breaks;

      case 403:
         return __('networking > contact');
         breaks;

      case 404:
         return __('networking > comments');
         breaks;

      case 405:
         return __('printer > contact');
         breaks;

      case 406:
         return __('printer > comments');
         breaks;

      case 407:
         return __('printer > port > IP address');
         breaks;

      case 408:
         return __('networking > port > index number');
         breaks;

      case 409:
         return __('networking > Address CDP');
         breaks;

      case 410:
         return __('networking > Port CDP');
         breaks;

      case 411:
         return __('networking > port > trunk/tagged');
         breaks;

      case 412:
         return __('networking > MAC address filters (dot1dTpFdbAddress)');
         breaks;

      case 413:
         return __('networking > Physical addresses in memory (ipNetToMediaPhysAddress)');
         breaks;

      case 414:
         return __('networking > instances de ports (dot1dTpFdbPort)');
         breaks;

      case 415:
         return __('networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)');
         breaks;

      case 416:
         return __('printer > port > index number');
         breaks;

      case 417:
         return __('networking > MAC address');
         breaks;

      case 418:
         return __('printer > Inventory number');
         breaks;

      case 419:
         return __('networking > Inventory number');
         breaks;

      case 420:
         return __('printer > manufacturer');
         breaks;

      case 421:
         return __('networking > IP addresses');
         breaks;

      case 422:
         return __('networking > PVID (port VLAN ID)');
         breaks;

      case 423:
         return __('printer > meter > total number of printed pages (print)');
         breaks;

      case 424:
         return __('printer > meter > number of printed black and white pages (print)');
         breaks;

      case 425:
         return __('printer > meter > number of printed color pages (print)');
         breaks;

      case 426:
         return __('printer > meter > total number of printed pages (copy)');
         breaks;

      case 427:
         return __('printer > meter > number of printed black and white pages (copy)');
         breaks;

      case 428:
         return __('printer > meter > number of printed color pages (copy)');
         breaks;

      case 429:
         return __('printer > meter > total number of printed pages (fax)');
         breaks;

      case 430:
         return __('networking > port > vlan');
         breaks;

      case 435:
         return __('networking > CDP remote sysdescr');
         breaks;

      case 436:
         return __('networking > CDP remote id');
         breaks;

      case 437:
         return __('networking > CDP remote model device');
         breaks;

      case 438:
         return __('networking > LLDP remote sysdescr');
         breaks;

      case 439:
         return __('networking > LLDP remote id');
         breaks;

      case 440:
         return __('networking > LLDP remote port description');
         breaks;


      case 104:
         return __('MTU');
         breaks;

      case 105:
         return __('Speed');
         breaks;

      case 106:
         return __('Internal status');
         breaks;

      case 107:
         return __('Last Change');
         breaks;

      case 108:
         return __('Number of received bytes');
         breaks;

      case 109:
         return __('Number of outgoing bytes');
         breaks;

      case 110:
         return __('Number of input errors');
         breaks;

      case 111:
         return __('Number of output errors');
         breaks;

      case 112:
         return __('CPU usage');
         breaks;

      case 114:
         return __('Connection');
         breaks;

      case 115:
         return __('Internal MAC address');
         breaks;

      case 116:
         return __('Name');
         breaks;

      case 117:
         return __('Model');
         breaks;

      case 118:
         return __('Type');
         breaks;

      case 119:
         return __('VLAN');
         breaks;

      case 128:
         return __('Total number of printed pages');
         breaks;

      case 129:
         return __('Number of printed black and white pages');
         breaks;

      case 130:
         return __('Number of printed color pages');
         breaks;

      case 131:
         return __('Number of printed monochrome pages');
         breaks;

      case 134:
         return __('Black cartridge');
         breaks;

      case 135:
         return __('Photo black cartridge');
         breaks;

      case 136:
         return __('Cyan cartridge');
         breaks;

      case 137:
         return __('Yellow cartridge');
         breaks;

      case 138:
         return __('Magenta cartridge');
         breaks;

      case 139:
         return __('Light cyan cartridge');
         breaks;

      case 140:
         return __('Light magenta cartridge');
         breaks;

      case 141:
         return __('Photoconductor');
         breaks;

      case 142:
         return __('Black photoconductor');
         breaks;

      case 143:
         return __('Color photoconductor');
         breaks;

      case 144:
         return __('Cyan photoconductor');
         breaks;

      case 145:
         return __('Yellow photoconductor');
         breaks;

      case 146:
         return __('Magenta photoconductor');
         breaks;

      case 147:
         return __('Black transfer unit');
         breaks;

      case 148:
         return __('Cyan transfer unit');
         breaks;

      case 149:
         return __('Yellow transfer unit');
         breaks;

      case 150:
         return __('Magenta transfer unit');
         breaks;

      case 151:
         return __('Waste bin');
         breaks;

      case 152:
         return __('Four');
         breaks;

      case 153:
         return __('Cleaning module');
         breaks;

      case 154:
         return __('Number of pages printed duplex');
         breaks;

      case 155:
         return __('Number of scanned pages');
         breaks;

      case 156:
         return __('Maintenance kit');
         breaks;

      case 157:
         return __('Black toner');
         breaks;

      case 158:
         return __('Cyan toner');
         breaks;

      case 159:
         return __('Magenta toner');
         breaks;

      case 160:
         return __('Yellow toner');
         breaks;

      case 161:
         return __('Black drum');
         breaks;

      case 162:
         return __('Cyan drum');
         breaks;

      case 163:
         return __('Magenta drum');
         breaks;

      case 164:
         return __('Yellow drum');
         breaks;

      case 165:
         return __('Many informations grouped');
         breaks;

      case 166:
         return __('Black toner 2');
         breaks;

      case 167:
         return __('Black toner Utilisé');
         breaks;

      case 168:
         return __('Black toner Restant');
         breaks;

      case 169:
         return __('Cyan toner Max');
         breaks;

      case 170:
         return __('Cyan toner Utilisé');
         breaks;

      case 171:
         return __('Cyan toner Restant');
         breaks;

      case 172:
         return __('Magenta toner Max');
         breaks;

      case 173:
         return __('Magenta toner Utilisé');
         breaks;

      case 174:
         return __('Magenta toner Restant');
         breaks;

      case 175:
         return __('Yellow toner Max');
         breaks;

      case 176:
         return __('Yellow toner Utilisé');
         breaks;

      case 177:
         return __('Yellow toner Restant');
         breaks;

      case 178:
         return __('Black drum Max');
         breaks;

      case 179:
         return __('Black drum Utilisé');
         breaks;

      case 180:
         return __('Black drum Restant');
         breaks;

      case 181:
         return __('Cyan drum Max');
         breaks;

      case 182:
         return __('Cyan drum Utilisé');
         breaks;

      case 183:
         return __('Cyan drumRestant');
         breaks;

      case 184:
         return __('Magenta drum Max');
         breaks;

      case 185:
         return __('Magenta drum Utilisé');
         breaks;

      case 186:
         return __('Magenta drum Restant');
         breaks;

      case 187:
         return __('Yellow drum Max');
         breaks;

      case 188:
         return __('Yellow drum Utilisé');
         breaks;

      case 189:
         return __('Yellow drum Restant');
         breaks;

      case 190:
         return __('Waste bin Max');
         breaks;

      case 191:
         return __('Waste bin Utilisé');
         breaks;

      case 192:
         return __('Waste bin Restant');
         breaks;

      case 193:
         return __('Maintenance kit Max');
         breaks;

      case 194:
         return __('Maintenance kit Utilisé');
         breaks;

      case 195:
         return __('Maintenance kit Restant');
         breaks;

      case 196:
         return __('Grey ink cartridge');
         breaks;


      case 1423:
         return __('Total number of printed pages (print)');
         breaks;

      case 1424:
         return __('Number of printed black and white pages (print)');
         breaks;

      case 1425:
         return __('Number of printed color pages (print)');
         breaks;

      case 1426:
         return __('Total number of printed pages (copy)');
         breaks;

      case 1427:
         return __('Number of printed black and white pages (copy)');
         breaks;

      case 1428:
         return __('Number of printed color pages (copy)');
         breaks;

      case 1429:
         return __('Total number of printed pages (fax)');
         breaks;

      case 1434:
         return __('Total number of large printed pages');
         breaks;
   }

   return $mapping['name'];

   }
}

?>
