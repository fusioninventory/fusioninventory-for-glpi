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
         return _('networking > location');
         breaks;

      case 2:
         return _('networking > firmware');
         breaks;

      case 3:
         return _('networking > uptime');
         breaks;

      case 4:
         return _('networking > port > mtu');
         breaks;

      case 5:
         return _('networking > port > speed');
         breaks;

      case 6:
         return _('networking > port > internal status');
         breaks;

      case 7:
         return _('networking > ports > last change');
         breaks;

      case 8:
         return _('networking > port > number of bytes entered');
         breaks;

      case 9:
         return _('networking > port > number of bytes out');
         breaks;

      case 10:
         return _('networking > port > number of input errors');
         breaks;

      case 11:
         return _('networking > port > number of errors output');
         breaks;

      case 12:
         return _('networking > CPU usage');
         breaks;

      case 13:
         return _('networking > serial number');
         breaks;

      case 14:
         return _('networking > port > connection status');
         breaks;

      case 15:
         return _('networking > port > MAC address');
         breaks;

      case 16:
         return _('networking > port > name');
         breaks;

      case 17:
         return _('networking > model');
         breaks;

      case 18:
         return _('networking > port > type');
         breaks;

      case 19:
         return _('networking > VLAN');
         breaks;

      case 20:
         return _('networking > name');
         breaks;

      case 21:
         return _('networking > total memory');
         breaks;

      case 22:
         return _('networking > free memory');
         breaks;

      case 23:
         return _('networking > port > port description');
         breaks;

      case 24:
         return _('printer > name');
         breaks;

      case 25:
         return _('printer > model');
         breaks;

      case 26:
         return _('printer > total memory');
         breaks;

      case 27:
         return _('printer > serial number');
         breaks;

      case 28:
         return _('printer > meter > total number of printed pages');
         breaks;

      case 29:
         return _('printer > meter > number of printed black and white pages');
         breaks;

      case 30:
         return _('printer > meter > number of printed color pages');
         breaks;

      case 31:
         return _('printer > meter > number of printed monochrome pages');
         breaks;

      case 33:
         return _('networking > port > duplex type');
         breaks;

      case 34:
         return _('printer > consumables > black cartridge (%)');
         breaks;

      case 35:
         return _('printer > consumables > photo black cartridge (%)');
         breaks;

      case 36:
         return _('printer > consumables > cyan cartridge (%)');
         breaks;

      case 37:
         return _('printer > consumables > yellow cartridge (%)');
         breaks;

      case 38:
         return _('printer > consumables > magenta cartridge (%)');
         breaks;

      case 39:
         return _('printer > consumables > light cyan cartridge (%)');
         breaks;

      case 40:
         return _('printer > consumables > light magenta cartridge (%)');
         breaks;

      case 41:
         return _('printer > consumables > photoconductor (%)');
         breaks;

      case 42:
         return _('printer > consumables > black photoconductor (%)');
         breaks;

      case 43:
         return _('printer > consumables > color photoconductor (%)');
         breaks;

      case 44:
         return _('printer > consumables > cyan photoconductor (%)');
         breaks;

      case 45:
         return _('printer > consumables > yellow photoconductor (%)');
         breaks;

      case 46:
         return _('printer > consumables > magenta photoconductor (%)');
         breaks;

      case 47:
         return _('printer > consumables > black transfer unit (%)');
         breaks;

      case 48:
         return _('printer > consumables > cyan transfer unit (%)');
         breaks;

      case 49:
         return _('printer > consumables > yellow transfer unit (%)');
         breaks;

      case 50:
         return _('printer > consumables > magenta transfer unit (%)');
         breaks;

      case 51:
         return _('printer > consumables > waste bin (%)');
         breaks;

      case 52:
         return _('printer > consumables > four (%)');
         breaks;

      case 53:
         return _('printer > consumables > cleaning module (%)');
         breaks;

      case 54:
         return _('printer > meter > number of printed duplex pages');
         breaks;

      case 55:
         return _('printer > meter > nomber of scanned pages');
         breaks;

      case 56:
         return _('printer > location');
         breaks;

      case 57:
         return _('printer > port > name');
         breaks;

      case 58:
         return _('printer > port > MAC address');
         breaks;

      case 59:
         return _('printer > consumables > black cartridge (max ink)');
         breaks;

      case 60:
         return _('printer > consumables > black cartridge (remaining ink )');
         breaks;

      case 61:
         return _('printer > consumables > cyan cartridge (max ink)');
         breaks;

      case 62:
         return _('printer > consumables > cyan cartridge (remaining ink)');
         breaks;

      case 63:
         return _('printer > consumables > yellow cartridge (max ink)');
         breaks;

      case 64:
         return _('printer > consumables > yellow cartridge (remaining ink)');
         breaks;

      case 65:
         return _('printer > consumables > magenta cartridge (max ink)');
         breaks;

      case 66:
         return _('printer > consumables > magenta cartridge (remaining ink)');
         breaks;

      case 67:
         return _('printer > consumables > light cyan cartridge (max ink)');
         breaks;

      case 68:
         return _('printer > consumables > light cyan cartridge (remaining ink)');
         breaks;

      case 69:
         return _('printer > consumables > light magenta cartridge (max ink)');
         breaks;

      case 70:
         return _('printer > consumables > light magenta cartridge (remaining ink)');
         breaks;

      case 71:
         return _('printer > consumables > photoconductor (max ink)');
         breaks;

      case 72:
         return _('printer > consumables > photoconductor (remaining ink)');
         breaks;

      case 73:
         return _('printer > consumables > black photoconductor (max ink)');
         breaks;

      case 74:
         return _('printer > consumables > black photoconductor (remaining ink)');
         breaks;

      case 75:
         return _('printer > consumables > color photoconductor (max ink)');
         breaks;

      case 76:
         return _('printer > consumables > color photoconductor (remaining ink)');
         breaks;

      case 77:
         return _('printer > consumables > cyan photoconductor (max ink)');
         breaks;

      case 78:
         return _('printer > consumables > cyan photoconductor (remaining ink)');
         breaks;

      case 79:
         return _('printer > consumables > yellow photoconductor (max ink)');
         breaks;

      case 80:
         return _('printer > consumables > yellow photoconductor (remaining ink)');
         breaks;

      case 81:
         return _('printer > consumables > magenta photoconductor (max ink)');
         breaks;

      case 82:
         return _('printer > consumables > magenta photoconductor (remaining ink)');
         breaks;

      case 83:
         return _('printer > consumables > black transfer unit (max ink)');
         breaks;

      case 84:
         return _('printer > consumables > black transfer unit (remaining ink)');
         breaks;

      case 85:
         return _('printer > consumables > cyan transfer unit (max ink)');
         breaks;

      case 86:
         return _('printer > consumables > cyan transfer unit (remaining ink)');
         breaks;

      case 87:
         return _('printer > consumables > yellow transfer unit (max ink)');
         breaks;

      case 88:
         return _('printer > consumables > yellow transfer unit (remaining ink)');
         breaks;

      case 89:
         return _('printer > consumables > magenta transfer unit (max ink)');
         breaks;

      case 90:
         return _('printer > consumables > magenta transfer unit (remaining ink)');
         breaks;

      case 91:
         return _('printer > consumables > waste bin (max ink)');
         breaks;

      case 92:
         return _('printer > consumables > waste bin (remaining ink)');
         breaks;

      case 93:
         return _('printer > consumables > four (max ink)');
         breaks;

      case 94:
         return _('printer > consumables > four (remaining ink)');
         breaks;

      case 95:
         return _('printer > consumables > cleaning module (max ink)');
         breaks;

      case 96:
         return _('printer > consumables > cleaning module (remaining ink)');
         breaks;

      case 97:
         return _('printer > port > type');
         breaks;

      case 98:
         return _('printer > consumables > maintenance kit (max)');
         breaks;

      case 99:
         return _('printer > consumables > maintenance kit (remaining)');
         breaks;

      case 400:
         return _('printer > consumables > maintenance kit (%)');
         breaks;

      case 401:
         return _('networking > CPU user');
         breaks;

      case 402:
         return _('networking > CPU system');
         breaks;

      case 403:
         return _('networking > contact');
         breaks;

      case 404:
         return _('networking > comments');
         breaks;

      case 405:
         return _('printer > contact');
         breaks;

      case 406:
         return _('printer > comments');
         breaks;

      case 407:
         return _('printer > port > IP address');
         breaks;

      case 408:
         return _('networking > port > index number');
         breaks;

      case 409:
         return _('networking > Address CDP');
         breaks;

      case 410:
         return _('networking > Port CDP');
         breaks;

      case 411:
         return _('networking > port > trunk/tagged');
         breaks;

      case 412:
         return _('networking > MAC address filters (dot1dTpFdbAddress)');
         breaks;

      case 413:
         return _('networking > Physical addresses in memory (ipNetToMediaPhysAddress)');
         breaks;

      case 414:
         return _('networking > instances de ports (dot1dTpFdbPort)');
         breaks;

      case 415:
         return _('networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)');
         breaks;

      case 416:
         return _('printer > port > index number');
         breaks;

      case 417:
         return _('networking > MAC address');
         breaks;

      case 418:
         return _('printer > Inventory number');
         breaks;

      case 419:
         return _('networking > Inventory number');
         breaks;

      case 420:
         return _('printer > manufacturer');
         breaks;

      case 421:
         return _('networking > IP addresses');
         breaks;

      case 422:
         return _('networking > PVID (port VLAN ID)');
         breaks;

      case 423:
         return _('printer > meter > total number of printed pages (print)');
         breaks;

      case 424:
         return _('printer > meter > number of printed black and white pages (print)');
         breaks;

      case 425:
         return _('printer > meter > number of printed color pages (print)');
         breaks;

      case 426:
         return _('printer > meter > total number of printed pages (copy)');
         breaks;

      case 427:
         return _('printer > meter > number of printed black and white pages (copy)');
         breaks;

      case 428:
         return _('printer > meter > number of printed color pages (copy)');
         breaks;

      case 429:
         return _('printer > meter > total number of printed pages (fax)');
         breaks;

      case 430:
         return _('networking > port > vlan');
         breaks;

      case 435:
         return _('networking > CDP remote sysdescr');
         breaks;

      case 436:
         return _('networking > CDP remote id');
         breaks;

      case 437:
         return _('networking > CDP remote model device');
         breaks;

      case 438:
         return _('networking > LLDP remote sysdescr');
         breaks;

      case 439:
         return _('networking > LLDP remote id');
         breaks;

      case 440:
         return _('networking > LLDP remote port description');
         breaks;


      case 104:
         return _('MTU');
         breaks;

      case 105:
         return _('Speed');
         breaks;

      case 106:
         return _('Internal status');
         breaks;

      case 107:
         return _('Last Change');
         breaks;

      case 108:
         return _('Number of received bytes');
         breaks;

      case 109:
         return _('Number of outgoing bytes');
         breaks;

      case 110:
         return _('Number of input errors');
         breaks;

      case 111:
         return _('Number of output errors');
         breaks;

      case 112:
         return _('CPU usage');
         breaks;

      case 114:
         return _('Connection');
         breaks;

      case 115:
         return _('Internal MAC address');
         breaks;

      case 116:
         return _('Name');
         breaks;

      case 117:
         return _('Model');
         breaks;

      case 118:
         return _('Type');
         breaks;

      case 119:
         return _('VLAN');
         breaks;

      case 128:
         return _('Total number of printed pages');
         breaks;

      case 129:
         return _('Number of printed black and white pages');
         breaks;

      case 130:
         return _('Number of printed color pages');
         breaks;

      case 131:
         return _('Number of printed monochrome pages');
         breaks;

      case 134:
         return _('Black cartridge');
         breaks;

      case 135:
         return _('Photo black cartridge');
         breaks;

      case 136:
         return _('Cyan cartridge');
         breaks;

      case 137:
         return _('Yellow cartridge');
         breaks;

      case 138:
         return _('Magenta cartridge');
         breaks;

      case 139:
         return _('Light cyan cartridge');
         breaks;

      case 140:
         return _('Light magenta cartridge');
         breaks;

      case 141:
         return _('Photoconductor');
         breaks;

      case 142:
         return _('Black photoconductor');
         breaks;

      case 143:
         return _('Color photoconductor');
         breaks;

      case 144:
         return _('Cyan photoconductor');
         breaks;

      case 145:
         return _('Yellow photoconductor');
         breaks;

      case 146:
         return _('Magenta photoconductor');
         breaks;

      case 147:
         return _('Black transfer unit');
         breaks;

      case 148:
         return _('Cyan transfer unit');
         breaks;

      case 149:
         return _('Yellow transfer unit');
         breaks;

      case 150:
         return _('Magenta transfer unit');
         breaks;

      case 151:
         return _('Waste bin');
         breaks;

      case 152:
         return _('Four');
         breaks;

      case 153:
         return _('Cleaning module');
         breaks;

      case 154:
         return _('Number of pages printed duplex');
         breaks;

      case 155:
         return _('Number of scanned pages');
         breaks;

      case 156:
         return _('Maintenance kit');
         breaks;

      case 157:
         return _('Black toner');
         breaks;

      case 158:
         return _('Cyan toner');
         breaks;

      case 159:
         return _('Magenta toner');
         breaks;

      case 160:
         return _('Yellow toner');
         breaks;

      case 161:
         return _('Black drum');
         breaks;

      case 162:
         return _('Cyan drum');
         breaks;

      case 163:
         return _('Magenta drum');
         breaks;

      case 164:
         return _('Yellow drum');
         breaks;

      case 165:
         return _('Many informations grouped');
         breaks;

      case 166:
         return _('Black toner 2');
         breaks;

      case 167:
         return _('Black toner Utilisé');
         breaks;

      case 168:
         return _('Black toner Restant');
         breaks;

      case 169:
         return _('Cyan toner Max');
         breaks;

      case 170:
         return _('Cyan toner Utilisé');
         breaks;

      case 171:
         return _('Cyan toner Restant');
         breaks;

      case 172:
         return _('Magenta toner Max');
         breaks;

      case 173:
         return _('Magenta toner Utilisé');
         breaks;

      case 174:
         return _('Magenta toner Restant');
         breaks;

      case 175:
         return _('Yellow toner Max');
         breaks;

      case 176:
         return _('Yellow toner Utilisé');
         breaks;

      case 177:
         return _('Yellow toner Restant');
         breaks;

      case 178:
         return _('Black drum Max');
         breaks;

      case 179:
         return _('Black drum Utilisé');
         breaks;

      case 180:
         return _('Black drum Restant');
         breaks;

      case 181:
         return _('Cyan drum Max');
         breaks;

      case 182:
         return _('Cyan drum Utilisé');
         breaks;

      case 183:
         return _('Cyan drumRestant');
         breaks;

      case 184:
         return _('Magenta drum Max');
         breaks;

      case 185:
         return _('Magenta drum Utilisé');
         breaks;

      case 186:
         return _('Magenta drum Restant');
         breaks;

      case 187:
         return _('Yellow drum Max');
         breaks;

      case 188:
         return _('Yellow drum Utilisé');
         breaks;

      case 189:
         return _('Yellow drum Restant');
         breaks;

      case 190:
         return _('Waste bin Max');
         breaks;

      case 191:
         return _('Waste bin Utilisé');
         breaks;

      case 192:
         return _('Waste bin Restant');
         breaks;

      case 193:
         return _('Maintenance kit Max');
         breaks;

      case 194:
         return _('Maintenance kit Utilisé');
         breaks;

      case 195:
         return _('Maintenance kit Restant');
         breaks;

      case 196:
         return _('Grey ink cartridge');
         breaks;


      case 1423:
         return _('Total number of printed pages (print)');
         breaks;

      case 1424:
         return _('Number of printed black and white pages (print)');
         breaks;

      case 1425:
         return _('Number of printed color pages (print)');
         breaks;

      case 1426:
         return _('Total number of printed pages (copy)');
         breaks;

      case 1427:
         return _('Number of printed black and white pages (copy)');
         breaks;

      case 1428:
         return _('Number of printed color pages (copy)');
         breaks;

      case 1429:
         return _('Total number of printed pages (fax)');
         breaks;

      case 1434:
         return _('Total number of large printed pages');
         breaks;
   }

   return $mapping['name'];

   }
}

?>
