<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
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
    *@return mapping fields or FALSE
    **/
   function get($p_itemtype, $p_name) {
      $data = $this->find("`itemtype`='".$p_itemtype."' AND `name`='".$p_name."'", "", 1);
      $mapping = current($data);
      if (isset($mapping['id'])) {
         return $mapping;
      }
      return FALSE;
   }



   /**
    *
    * @param $parm
    */
   function set($parm) {
      global $DB;

      $data = current(getAllDatasFromTable("glpi_plugin_fusioninventory_mappings",
                                   "`itemtype`='".$parm['itemtype']."' AND `name`='".
                                   $parm['name']."'"));
      if (empty($data)) {
         // Insert
         $query = '';
         if (isset($parm['shortlocale'])) {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`, `shortlocale`)
                     VALUES ('".$parm['itemtype']."', '".$parm['name']."', '".$parm['table']."',
                             '".$parm['tablefield']."', '".$parm['locale']."',
                                '".$parm['shortlocale']."')";
         } else {
            $query = "INSERT INTO `glpi_plugin_fusioninventory_mappings`
                        (`itemtype`, `name`, `table`, `tablefield`, `locale`)
                     VALUES ('".$parm['itemtype']."', '".$parm['name']."', '".$parm['table']."',
                             '".$parm['tablefield']."', '".$parm['locale']."')";
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
            return __('networking > location', 'fusioninventory');
            break;

         case 2:
            return __('networking > firmware', 'fusioninventory');
            break;

         case 3:
            return __('networking > uptime', 'fusioninventory');
            break;

         case 4:
            return __('networking > port > mtu', 'fusioninventory');
            break;

         case 5:
            return __('networking > port > speed', 'fusioninventory');
            break;

         case 6:
            return __('networking > port > internal status', 'fusioninventory');
            break;

         case 7:
            return __('networking > ports > last change', 'fusioninventory');
            break;

         case 8:
            return __('networking > port > number of bytes entered', 'fusioninventory');
            break;

         case 9:
            return __('networking > port > number of bytes out', 'fusioninventory');
            break;

         case 10:
            return __('networking > port > number of input errors', 'fusioninventory');
            break;

         case 11:
            return __('networking > port > number of errors output', 'fusioninventory');
            break;

         case 12:
            return __('networking > CPU usage', 'fusioninventory');
            break;

         case 13:
            return __('networking > serial number', 'fusioninventory');
            break;

         case 14:
            return __('networking > port > connection status', 'fusioninventory');
            break;

         case 15:
            return __('networking > port > MAC address', 'fusioninventory');
            break;

         case 16:
            return __('networking > port > name', 'fusioninventory');
            break;

         case 17:
            return __('networking > model', 'fusioninventory');
            break;

         case 18:
            return __('networking > port > type', 'fusioninventory');
            break;

         case 19:
            return __('networking > VLAN', 'fusioninventory');
            break;

         case 20:
            return __('networking > name', 'fusioninventory');
            break;

         case 21:
            return __('networking > total memory', 'fusioninventory');
            break;

         case 22:
            return __('networking > free memory', 'fusioninventory');
            break;

         case 23:
            return __('networking > port > port description', 'fusioninventory');
            break;

         case 24:
            return __('printer > name', 'fusioninventory');
            break;

         case 25:
            return __('printer > model', 'fusioninventory');
            break;

         case 26:
            return __('printer > total memory', 'fusioninventory');
            break;

         case 27:
            return __('printer > serial number', 'fusioninventory');
            break;

         case 28:
            return __('printer > meter > total number of printed pages', 'fusioninventory');
            break;

         case 29:
            return __('printer > meter > number of printed black and white pages', 'fusioninventory');
            break;

         case 30:
            return __('printer > meter > number of printed color pages', 'fusioninventory');
            break;

         case 31:
            return __('printer > meter > number of printed monochrome pages', 'fusioninventory');
            break;

         case 33:
            return __('networking > port > duplex type', 'fusioninventory');
            break;

         case 34:
            return __('printer > consumables > black cartridge (%)', 'fusioninventory');
            break;

         case 35:
            return __('printer > consumables > photo black cartridge (%)', 'fusioninventory');
            break;

         case 36:
            return __('printer > consumables > cyan cartridge (%)', 'fusioninventory');
            break;

         case 37:
            return __('printer > consumables > yellow cartridge (%)', 'fusioninventory');
            break;

         case 38:
            return __('printer > consumables > magenta cartridge (%)', 'fusioninventory');
            break;

         case 39:
            return __('printer > consumables > light cyan cartridge (%)', 'fusioninventory');
            break;

         case 40:
            return __('printer > consumables > light magenta cartridge (%)', 'fusioninventory');
            break;

         case 41:
            return __('printer > consumables > photoconductor (%)', 'fusioninventory');
            break;

         case 42:
            return __('printer > consumables > black photoconductor (%)', 'fusioninventory');
            break;

         case 43:
            return __('printer > consumables > color photoconductor (%)', 'fusioninventory');
            break;

         case 44:
            return __('printer > consumables > cyan photoconductor (%)', 'fusioninventory');
            break;

         case 45:
            return __('printer > consumables > yellow photoconductor (%)', 'fusioninventory');
            break;

         case 46:
            return __('printer > consumables > magenta photoconductor (%)', 'fusioninventory');
            break;

         case 47:
            return __('printer > consumables > black transfer unit (%)', 'fusioninventory');
            break;

         case 48:
            return __('printer > consumables > cyan transfer unit (%)', 'fusioninventory');
            break;

         case 49:
            return __('printer > consumables > yellow transfer unit (%)', 'fusioninventory');
            break;

         case 50:
            return __('printer > consumables > magenta transfer unit (%)', 'fusioninventory');
            break;

         case 51:
            return __('printer > consumables > waste bin (%)', 'fusioninventory');
            break;

         case 52:
            return __('printer > consumables > four (%)', 'fusioninventory');
            break;

         case 53:
            return __('printer > consumables > cleaning module (%)', 'fusioninventory');
            break;

         case 54:
            return __('printer > meter > number of printed duplex pages', 'fusioninventory');
            break;

         case 55:
            return __('printer > meter > nomber of scanned pages', 'fusioninventory');
            break;

         case 56:
            return __('printer > location', 'fusioninventory');
            break;

         case 57:
            return __('printer > port > name', 'fusioninventory');
            break;

         case 58:
            return __('printer > port > MAC address', 'fusioninventory');
            break;

         case 59:
            return __('printer > consumables > black cartridge (max ink)', 'fusioninventory');
            break;

         case 60:
            return __('printer > consumables > black cartridge (remaining ink )', 'fusioninventory');
            break;

         case 61:
            return __('printer > consumables > cyan cartridge (max ink)', 'fusioninventory');
            break;

         case 62:
            return __('printer > consumables > cyan cartridge (remaining ink)', 'fusioninventory');
            break;

         case 63:
            return __('printer > consumables > yellow cartridge (max ink)', 'fusioninventory');
            break;

         case 64:
            return __('printer > consumables > yellow cartridge (remaining ink)', 'fusioninventory');
            break;

         case 65:
            return __('printer > consumables > magenta cartridge (max ink)', 'fusioninventory');
            break;

         case 66:
            return __('printer > consumables > magenta cartridge (remaining ink)', 'fusioninventory');
            break;

         case 67:
            return __('printer > consumables > light cyan cartridge (max ink)', 'fusioninventory');
            break;

         case 68:
          return __('printer > consumables > light cyan cartridge (remaining ink)', 'fusioninventory');
            break;

         case 69:
            return __('printer > consumables > light magenta cartridge (max ink)', 'fusioninventory');
            break;

         case 70:
            return __('printer > consumables > light magenta cartridge (remaining ink)', 'fusioninventory');
            break;

         case 71:
            return __('printer > consumables > photoconductor (max ink)', 'fusioninventory');
            break;

         case 72:
            return __('printer > consumables > photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 73:
            return __('printer > consumables > black photoconductor (max ink)', 'fusioninventory');
            break;

         case 74:
          return __('printer > consumables > black photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 75:
            return __('printer > consumables > color photoconductor (max ink)', 'fusioninventory');
            break;

         case 76:
          return __('printer > consumables > color photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 77:
            return __('printer > consumables > cyan photoconductor (max ink)', 'fusioninventory');
            break;

         case 78:
           return __('printer > consumables > cyan photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 79:
            return __('printer > consumables > yellow photoconductor (max ink)', 'fusioninventory');
            break;

         case 80:
         return __('printer > consumables > yellow photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 81:
            return __('printer > consumables > magenta photoconductor (max ink)', 'fusioninventory');
            break;

         case 82:
            return __('printer > consumables > magenta photoconductor (remaining ink)', 'fusioninventory');
            break;

         case 83:
            return __('printer > consumables > black transfer unit (max ink)', 'fusioninventory');
            break;

         case 84:
           return __('printer > consumables > black transfer unit (remaining ink)', 'fusioninventory');
            break;

         case 85:
            return __('printer > consumables > cyan transfer unit (max ink)', 'fusioninventory');
            break;

         case 86:
            return __('printer > consumables > cyan transfer unit (remaining ink)', 'fusioninventory');
            break;

         case 87:
            return __('printer > consumables > yellow transfer unit (max ink)', 'fusioninventory');
            break;

         case 88:
          return __('printer > consumables > yellow transfer unit (remaining ink)', 'fusioninventory');
            break;

         case 89:
            return __('printer > consumables > magenta transfer unit (max ink)', 'fusioninventory');
            break;

         case 90:
         return __('printer > consumables > magenta transfer unit (remaining ink)', 'fusioninventory');
            break;

         case 91:
            return __('printer > consumables > waste bin (max ink)', 'fusioninventory');
            break;

         case 92:
            return __('printer > consumables > waste bin (remaining ink)', 'fusioninventory');
            break;

         case 93:
            return __('printer > consumables > four (max ink)', 'fusioninventory');
            break;

         case 94:
            return __('printer > consumables > four (remaining ink)', 'fusioninventory');
            break;

         case 95:
            return __('printer > consumables > cleaning module (max ink)', 'fusioninventory');
            break;

         case 96:
            return __('printer > consumables > cleaning module (remaining ink)', 'fusioninventory');
            break;

         case 97:
            return __('printer > port > type', 'fusioninventory');
            break;

         case 98:
            return __('printer > consumables > maintenance kit (max)', 'fusioninventory');
            break;

         case 99:
            return __('printer > consumables > maintenance kit (remaining)', 'fusioninventory');
            break;

         case 400:
            return __('printer > consumables > maintenance kit (%)', 'fusioninventory');
            break;

         case 401:
            return __('networking > CPU user', 'fusioninventory');
            break;

         case 402:
            return __('networking > CPU system', 'fusioninventory');
            break;

         case 403:
            return __('networking > contact', 'fusioninventory');
            break;

         case 404:
            return __('networking > comments', 'fusioninventory');
            break;

         case 405:
            return __('printer > contact', 'fusioninventory');
            break;

         case 406:
            return __('printer > comments', 'fusioninventory');
            break;

         case 407:
            return __('printer > port > IP address', 'fusioninventory');
            break;

         case 408:
            return __('networking > port > index number', 'fusioninventory');
            break;

         case 409:
            return __('networking > Address CDP', 'fusioninventory');
            break;

         case 410:
            return __('networking > Port CDP', 'fusioninventory');
            break;

         case 411:
            return __('networking > port > trunk/tagged', 'fusioninventory');
            break;

         case 412:
            return __('networking > MAC address filters (dot1dTpFdbAddress)', 'fusioninventory');
            break;

         case 413:
            return __('networking > Physical addresses in memory (ipNetToMediaPhysAddress)', 'fusioninventory');
            break;

         case 414:
            return __('networking > instances de ports (dot1dTpFdbPort)', 'fusioninventory');
            break;

         case 415:
            return __('networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)');
            break;

         case 416:
            return __('printer > port > index number', 'fusioninventory');
            break;

         case 417:
            return __('networking > MAC address', 'fusioninventory');
            break;

         case 418:
            return __('printer > Inventory number', 'fusioninventory');
            break;

         case 419:
            return __('networking > Inventory number', 'fusioninventory');
            break;

         case 420:
            return __('printer > manufacturer', 'fusioninventory');
            break;

         case 421:
            return __('networking > IP addresses', 'fusioninventory');
            break;

         case 422:
            return __('networking > PVID (port VLAN ID)', 'fusioninventory');
            break;

         case 423:
            return __('printer > meter > total number of printed pages (print)', 'fusioninventory');
            break;

         case 424:
            return __('printer > meter > number of printed black and white pages (print)', 'fusioninventory');
            break;

         case 425:
            return __('printer > meter > number of printed color pages (print)', 'fusioninventory');
            break;

         case 426:
            return __('printer > meter > total number of printed pages (copy)', 'fusioninventory');
            break;

         case 427:
            return __('printer > meter > number of printed black and white pages (copy)', 'fusioninventory');
            break;

         case 428:
            return __('printer > meter > number of printed color pages (copy)', 'fusioninventory');
            break;

         case 429:
            return __('printer > meter > total number of printed pages (fax)', 'fusioninventory');
            break;

         case 430:
            return __('networking > port > vlan', 'fusioninventory');
            break;

         case 435:
            return __('networking > CDP remote sysdescr', 'fusioninventory');
            break;

         case 436:
            return __('networking > CDP remote id', 'fusioninventory');
            break;

         case 437:
            return __('networking > CDP remote model device', 'fusioninventory');
            break;

         case 438:
            return __('networking > LLDP remote sysdescr', 'fusioninventory');
            break;

         case 439:
            return __('networking > LLDP remote id', 'fusioninventory');
            break;

         case 440:
            return __('networking > LLDP remote port description', 'fusioninventory');
            break;


         case 104:
            return __('MTU', 'fusioninventory');
            break;

         case 105:
            return __('Speed');
            break;

         case 106:
            return __('Internal status', 'fusioninventory');
            break;

         case 107:
            return __('Last Change', 'fusioninventory');
            break;

         case 108:
            return __('Number of received bytes', 'fusioninventory');
            break;

         case 109:
            return __('Number of outgoing bytes', 'fusioninventory');
            break;

         case 110:
            return __('Number of input errors', 'fusioninventory');
            break;

         case 111:
            return __('Number of output errors', 'fusioninventory');
            break;

         case 112:
            return __('CPU usage', 'fusioninventory');
            break;

         case 114:
            return __('Connection');
            break;

         case 115:
            return __('Internal MAC address', 'fusioninventory');
            break;

         case 116:
            return __('Name');
            break;

         case 117:
            return __('Model');
            break;

         case 118:
            return __('Type');
            break;

         case 119:
            return __('VLAN');
            break;

         case 120:
            return __('Alias', 'fusioninventory');
            break;

         case 128:
            return __('Total number of printed pages', 'fusioninventory');
            break;

         case 129:
            return __('Number of printed black and white pages', 'fusioninventory');
            break;

         case 130:
            return __('Number of printed color pages', 'fusioninventory');
            break;

         case 131:
            return __('Number of printed monochrome pages', 'fusioninventory');
            break;

         case 134:
            return __('Black cartridge', 'fusioninventory');
            break;

         case 135:
            return __('Photo black cartridge', 'fusioninventory');
            break;

         case 136:
            return __('Cyan cartridge', 'fusioninventory');
            break;

         case 137:
            return __('Yellow cartridge', 'fusioninventory');
            break;

         case 138:
            return __('Magenta cartridge', 'fusioninventory');
            break;

         case 139:
            return __('Light cyan cartridge', 'fusioninventory');
            break;

         case 140:
            return __('Light magenta cartridge', 'fusioninventory');
            break;

         case 141:
            return __('Photoconductor', 'fusioninventory');
            break;

         case 142:
            return __('Black photoconductor', 'fusioninventory');
            break;

         case 143:
            return __('Color photoconductor', 'fusioninventory');
            break;

         case 144:
            return __('Cyan photoconductor', 'fusioninventory');
            break;

         case 145:
            return __('Yellow photoconductor', 'fusioninventory');
            break;

         case 146:
            return __('Magenta photoconductor', 'fusioninventory');
            break;

         case 147:
            return __('Black transfer unit', 'fusioninventory');
            break;

         case 148:
            return __('Cyan transfer unit', 'fusioninventory');
            break;

         case 149:
            return __('Yellow transfer unit', 'fusioninventory');
            break;

         case 150:
            return __('Magenta transfer unit', 'fusioninventory');
            break;

         case 151:
            return __('Waste bin', 'fusioninventory');
            break;

         case 152:
            return __('Four', 'fusioninventory');
            break;

         case 153:
            return __('Cleaning module', 'fusioninventory');
            break;

         case 154:
            return __('Number of pages printed duplex', 'fusioninventory');
            break;

         case 155:
            return __('Number of scanned pages', 'fusioninventory');
            break;

         case 156:
            return __('Maintenance kit', 'fusioninventory');
            break;

         case 157:
            return __('Black toner', 'fusioninventory');
            break;

         case 158:
            return __('Cyan toner', 'fusioninventory');
            break;

         case 159:
            return __('Magenta toner', 'fusioninventory');
            break;

         case 160:
            return __('Yellow toner', 'fusioninventory');
            break;

         case 161:
            return __('Black drum', 'fusioninventory');
            break;

         case 162:
            return __('Cyan drum', 'fusioninventory');
            break;

         case 163:
            return __('Magenta drum', 'fusioninventory');
            break;

         case 164:
            return __('Yellow drum', 'fusioninventory');
            break;

         case 165:
            return __('Many informations grouped', 'fusioninventory');
            break;

         case 166:
            return __('Black toner 2', 'fusioninventory');
            break;

         case 167:
            return __('Black toner Utilisé', 'fusioninventory');
            break;

         case 168:
            return __('Black toner Restant', 'fusioninventory');
            break;

         case 169:
            return __('Cyan toner Max', 'fusioninventory');
            break;

         case 170:
            return __('Cyan toner Utilisé', 'fusioninventory');
            break;

         case 171:
            return __('Cyan toner Restant', 'fusioninventory');
            break;

         case 172:
            return __('Magenta toner Max', 'fusioninventory');
            break;

         case 173:
            return __('Magenta toner Utilisé', 'fusioninventory');
            break;

         case 174:
            return __('Magenta toner Restant', 'fusioninventory');
            break;

         case 175:
            return __('Yellow toner Max', 'fusioninventory');
            break;

         case 176:
            return __('Yellow toner Utilisé', 'fusioninventory');
            break;

         case 177:
            return __('Yellow toner Restant', 'fusioninventory');
            break;

         case 178:
            return __('Black drum Max', 'fusioninventory');
            break;

         case 179:
            return __('Black drum Utilisé', 'fusioninventory');
            break;

         case 180:
            return __('Black drum Restant', 'fusioninventory');
            break;

         case 181:
            return __('Cyan drum Max', 'fusioninventory');
            break;

         case 182:
            return __('Cyan drum Utilisé', 'fusioninventory');
            break;

         case 183:
            return __('Cyan drumRestant', 'fusioninventory');
            break;

         case 184:
            return __('Magenta drum Max', 'fusioninventory');
            break;

         case 185:
            return __('Magenta drum Utilisé', 'fusioninventory');
            break;

         case 186:
            return __('Magenta drum Restant', 'fusioninventory');
            break;

         case 187:
            return __('Yellow drum Max', 'fusioninventory');
            break;

         case 188:
            return __('Yellow drum Utilisé', 'fusioninventory');
            break;

         case 189:
            return __('Yellow drum Restant', 'fusioninventory');
            break;

         case 190:
            return __('Waste bin Max', 'fusioninventory');
            break;

         case 191:
            return __('Waste bin Utilisé', 'fusioninventory');
            break;

         case 192:
            return __('Waste bin Restant', 'fusioninventory');
            break;

         case 193:
            return __('Maintenance kit Max', 'fusioninventory');
            break;

         case 194:
            return __('Maintenance kit Utilisé', 'fusioninventory');
            break;

         case 195:
            return __('Maintenance kit Restant', 'fusioninventory');
            break;

         case 196:
            return __('Grey ink cartridge', 'fusioninventory');
            break;


         case 1423:
            return __('Total number of printed pages (print)', 'fusioninventory');
            break;

         case 1424:
            return __('Number of printed black and white pages (print)', 'fusioninventory');
            break;

         case 1425:
            return __('Number of printed color pages (print)', 'fusioninventory');
            break;

         case 1426:
            return __('Total number of printed pages (copy)', 'fusioninventory');
            break;

         case 1427:
            return __('Number of printed black and white pages (copy)', 'fusioninventory');
            break;

         case 1428:
            return __('Number of printed color pages (copy)', 'fusioninventory');
            break;

         case 1429:
            return __('Total number of printed pages (fax)', 'fusioninventory');
            break;

         case 1434:
            return __('Total number of large printed pages', 'fusioninventory');
            break;
      }
      return $mapping['name'];
   }
}

?>
