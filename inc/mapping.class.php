<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the mapping of network equipment and
 * printer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the mapping of network equipment and printer.
 */
class PluginFusioninventoryMapping extends CommonDBTM {


   /**
    * Get mapping
    *
    * @param string $p_itemtype Mapping itemtype
    * @param string $p_name Mapping name
    * @return array|false mapping fields or FALSE
    */
   function get($p_itemtype, $p_name) {
      $data = $this->find(['itemtype' => $p_itemtype, 'name' => $p_name], [], 1);
      $mapping = current($data);
      if (isset($mapping['id'])) {
         return $mapping;
      }
      return false;
   }


   /**
    * Add new mapping
    *
    * @global object $DB
    * @param array $parm
    */
   function set($parm) {
      global $DB;

      $data = current(getAllDataFromTable("glpi_plugin_fusioninventory_mappings",
         ['itemtype' => $parm['itemtype'], 'name' => $parm['name']]));
      if (empty($data)) {
         // Insert
         $values = [
            'itemtype'     => $parm['itemtype'],
            'name'         => $parm['name'],
            'table'        => $parm['table'],
            'tablefield'   => $parm['tablefield'],
            'locale'       => $parm['locale']
         ];
         if (isset($parm['shortlocale'])) {
            $values['shortlocale'] = $parm['shortlocale'];
         }
         $DB->insert('glpi_plugin_fusioninventory_mappings', $values);
      } else if ($data['table'] != $parm['table']
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


   /**
    * Get translation name of mapping
    *
    * @param array $mapping
    * @return string
    */
   function getTranslation ($mapping) {

      switch ($mapping['locale']) {

         case 1:
            return __('networking > location', 'fusioninventory');

         case 2:
            return __('networking > firmware', 'fusioninventory');

         case 3:
            return __('networking > uptime', 'fusioninventory');

         case 4:
            return __('networking > port > mtu', 'fusioninventory');

         case 5:
            return __('networking > port > speed', 'fusioninventory');

         case 6:
            return __('networking > port > internal status', 'fusioninventory');

         case 7:
            return __('networking > ports > last change', 'fusioninventory');

         case 8:
            return __('networking > port > number of bytes entered', 'fusioninventory');

         case 9:
            return __('networking > port > number of bytes out', 'fusioninventory');

         case 10:
            return __('networking > port > number of input errors', 'fusioninventory');

         case 11:
            return __('networking > port > number of errors output', 'fusioninventory');

         case 12:
            return __('networking > CPU usage', 'fusioninventory');

         case 13:
            return __('networking > serial number', 'fusioninventory');

         case 14:
            return __('networking > port > connection status', 'fusioninventory');

         case 15:
            return __('networking > port > MAC address', 'fusioninventory');

         case 16:
            return __('networking > port > name', 'fusioninventory');

         case 17:
            return __('networking > model', 'fusioninventory');

         case 18:
            return __('networking > port > type', 'fusioninventory');

         case 19:
            return __('networking > VLAN', 'fusioninventory');

         case 20:
            return __('networking > name', 'fusioninventory');

         case 21:
            return __('networking > total memory', 'fusioninventory');

         case 22:
            return __('networking > free memory', 'fusioninventory');

         case 23:
            return __('networking > port > port description', 'fusioninventory');

         case 24:
            return __('printer > name', 'fusioninventory');

         case 25:
            return __('printer > model', 'fusioninventory');

         case 26:
            return __('printer > total memory', 'fusioninventory');

         case 27:
            return __('printer > serial number', 'fusioninventory');

         case 28:
            return __('printer > meter > total number of printed pages', 'fusioninventory');

         case 29:
            return __('printer > meter > number of printed black and white pages', 'fusioninventory');

         case 30:
            return __('printer > meter > number of printed color pages', 'fusioninventory');

         case 31:
            return __('printer > meter > number of printed monochrome pages', 'fusioninventory');

         case 33:
            return __('networking > port > duplex type', 'fusioninventory');

         case 34:
            return __('printer > consumables > black cartridge (%)', 'fusioninventory');

         case 35:
            return __('printer > consumables > photo black cartridge (%)', 'fusioninventory');

         case 36:
            return __('printer > consumables > cyan cartridge (%)', 'fusioninventory');

         case 37:
            return __('printer > consumables > yellow cartridge (%)', 'fusioninventory');

         case 38:
            return __('printer > consumables > magenta cartridge (%)', 'fusioninventory');

         case 39:
            return __('printer > consumables > light cyan cartridge (%)', 'fusioninventory');

         case 40:
            return __('printer > consumables > light magenta cartridge (%)', 'fusioninventory');

         case 41:
            return __('printer > consumables > photoconductor (%)', 'fusioninventory');

         case 42:
            return __('printer > consumables > black photoconductor (%)', 'fusioninventory');

         case 43:
            return __('printer > consumables > color photoconductor (%)', 'fusioninventory');

         case 44:
            return __('printer > consumables > cyan photoconductor (%)', 'fusioninventory');

         case 45:
            return __('printer > consumables > yellow photoconductor (%)', 'fusioninventory');

         case 46:
            return __('printer > consumables > magenta photoconductor (%)', 'fusioninventory');

         case 47:
            return __('printer > consumables > black transfer unit (%)', 'fusioninventory');

         case 48:
            return __('printer > consumables > cyan transfer unit (%)', 'fusioninventory');

         case 49:
            return __('printer > consumables > yellow transfer unit (%)', 'fusioninventory');

         case 50:
            return __('printer > consumables > magenta transfer unit (%)', 'fusioninventory');

         case 51:
            return __('printer > consumables > waste bin (%)', 'fusioninventory');

         case 52:
            return __('printer > consumables > four (%)', 'fusioninventory');

         case 53:
            return __('printer > consumables > cleaning module (%)', 'fusioninventory');

         case 54:
            return __('printer > meter > number of printed duplex pages', 'fusioninventory');

         case 55:
            return __('printer > meter > nomber of scanned pages', 'fusioninventory');

         case 56:
            return __('printer > location', 'fusioninventory');

         case 57:
            return __('printer > port > name', 'fusioninventory');

         case 58:
            return __('printer > port > MAC address', 'fusioninventory');

         case 59:
            return __('printer > consumables > black cartridge (max ink)', 'fusioninventory');

         case 60:
            return __('printer > consumables > black cartridge (remaining ink )', 'fusioninventory');

         case 61:
            return __('printer > consumables > cyan cartridge (max ink)', 'fusioninventory');

         case 62:
            return __('printer > consumables > cyan cartridge (remaining ink)', 'fusioninventory');

         case 63:
            return __('printer > consumables > yellow cartridge (max ink)', 'fusioninventory');

         case 64:
            return __('printer > consumables > yellow cartridge (remaining ink)', 'fusioninventory');

         case 65:
            return __('printer > consumables > magenta cartridge (max ink)', 'fusioninventory');

         case 66:
            return __('printer > consumables > magenta cartridge (remaining ink)', 'fusioninventory');

         case 67:
            return __('printer > consumables > light cyan cartridge (max ink)', 'fusioninventory');

         case 68:
          return __('printer > consumables > light cyan cartridge (remaining ink)', 'fusioninventory');

         case 69:
            return __('printer > consumables > light magenta cartridge (max ink)', 'fusioninventory');

         case 70:
            return __('printer > consumables > light magenta cartridge (remaining ink)', 'fusioninventory');

         case 71:
            return __('printer > consumables > photoconductor (max ink)', 'fusioninventory');

         case 72:
            return __('printer > consumables > photoconductor (remaining ink)', 'fusioninventory');

         case 73:
            return __('printer > consumables > black photoconductor (max ink)', 'fusioninventory');

         case 74:
          return __('printer > consumables > black photoconductor (remaining ink)', 'fusioninventory');

         case 75:
            return __('printer > consumables > color photoconductor (max ink)', 'fusioninventory');

         case 76:
          return __('printer > consumables > color photoconductor (remaining ink)', 'fusioninventory');

         case 77:
            return __('printer > consumables > cyan photoconductor (max ink)', 'fusioninventory');

         case 78:
           return __('printer > consumables > cyan photoconductor (remaining ink)', 'fusioninventory');

         case 79:
            return __('printer > consumables > yellow photoconductor (max ink)', 'fusioninventory');

         case 80:
         return __('printer > consumables > yellow photoconductor (remaining ink)', 'fusioninventory');

         case 81:
            return __('printer > consumables > magenta photoconductor (max ink)', 'fusioninventory');

         case 82:
            return __('printer > consumables > magenta photoconductor (remaining ink)', 'fusioninventory');

         case 83:
            return __('printer > consumables > black transfer unit (max ink)', 'fusioninventory');

         case 84:
           return __('printer > consumables > black transfer unit (remaining ink)', 'fusioninventory');

         case 85:
            return __('printer > consumables > cyan transfer unit (max ink)', 'fusioninventory');

         case 86:
            return __('printer > consumables > cyan transfer unit (remaining ink)', 'fusioninventory');

         case 87:
            return __('printer > consumables > yellow transfer unit (max ink)', 'fusioninventory');

         case 88:
          return __('printer > consumables > yellow transfer unit (remaining ink)', 'fusioninventory');

         case 89:
            return __('printer > consumables > magenta transfer unit (max ink)', 'fusioninventory');

         case 90:
         return __('printer > consumables > magenta transfer unit (remaining ink)', 'fusioninventory');

         case 91:
            return __('printer > consumables > waste bin (max ink)', 'fusioninventory');

         case 92:
            return __('printer > consumables > waste bin (remaining ink)', 'fusioninventory');

         case 93:
            return __('printer > consumables > four (max ink)', 'fusioninventory');

         case 94:
            return __('printer > consumables > four (remaining ink)', 'fusioninventory');

         case 95:
            return __('printer > consumables > cleaning module (max ink)', 'fusioninventory');

         case 96:
            return __('printer > consumables > cleaning module (remaining ink)', 'fusioninventory');

         case 97:
            return __('printer > port > type', 'fusioninventory');

         case 98:
            return __('printer > consumables > maintenance kit (max)', 'fusioninventory');

         case 99:
            return __('printer > consumables > maintenance kit (remaining)', 'fusioninventory');

         case 400:
            return __('printer > consumables > maintenance kit (%)', 'fusioninventory');

         case 401:
            return __('networking > CPU user', 'fusioninventory');

         case 402:
            return __('networking > CPU system', 'fusioninventory');

         case 403:
            return __('networking > contact', 'fusioninventory');

         case 404:
            return __('networking > comments', 'fusioninventory');

         case 405:
            return __('printer > contact', 'fusioninventory');

         case 406:
            return __('printer > comments', 'fusioninventory');

         case 407:
            return __('printer > port > IP address', 'fusioninventory');

         case 408:
            return __('networking > port > index number', 'fusioninventory');

         case 409:
            return __('networking > Address CDP', 'fusioninventory');

         case 410:
            return __('networking > Port CDP', 'fusioninventory');

         case 411:
            return __('networking > port > trunk/tagged', 'fusioninventory');

         case 412:
            return __('networking > MAC address filters (dot1dTpFdbAddress)', 'fusioninventory');

         case 413:
            return __('networking > Physical addresses in memory (ipNetToMediaPhysAddress)', 'fusioninventory');

         case 414:
            return __('networking > instances de ports (dot1dTpFdbPort)', 'fusioninventory');

         case 415:
            return __('networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)');

         case 416:
            return __('printer > port > index number', 'fusioninventory');

         case 417:
            return __('networking > MAC address', 'fusioninventory');

         case 418:
            return __('printer > Inventory number', 'fusioninventory');

         case 419:
            return __('networking > Inventory number', 'fusioninventory');

         case 420:
            return __('printer > manufacturer', 'fusioninventory');

         case 421:
            return __('networking > IP addresses', 'fusioninventory');

         case 422:
            return __('networking > PVID (port VLAN ID)', 'fusioninventory');

         case 423:
            return __('printer > meter > total number of printed pages (print)', 'fusioninventory');

         case 424:
            return __('printer > meter > number of printed black and white pages (print)', 'fusioninventory');

         case 425:
            return __('printer > meter > number of printed color pages (print)', 'fusioninventory');

         case 426:
            return __('printer > meter > total number of printed pages (copy)', 'fusioninventory');

         case 427:
            return __('printer > meter > number of printed black and white pages (copy)', 'fusioninventory');

         case 428:
            return __('printer > meter > number of printed color pages (copy)', 'fusioninventory');

         case 429:
            return __('printer > meter > total number of printed pages (fax)', 'fusioninventory');

         case 430:
            return __('networking > port > vlan', 'fusioninventory');

         case 435:
            return __('networking > CDP remote sysdescr', 'fusioninventory');

         case 436:
            return __('networking > CDP remote id', 'fusioninventory');

         case 437:
            return __('networking > CDP remote model device', 'fusioninventory');

         case 438:
            return __('networking > LLDP remote sysdescr', 'fusioninventory');

         case 439:
            return __('networking > LLDP remote id', 'fusioninventory');

         case 440:
            return __('networking > LLDP remote port description', 'fusioninventory');

         case 104:
            return __('MTU', 'fusioninventory');

         case 105:
            return __('Speed');

         case 106:
            return __('Internal status', 'fusioninventory');

         case 107:
            return __('Last Change', 'fusioninventory');

         case 108:
            return __('Number of received bytes', 'fusioninventory');

         case 109:
            return __('Number of outgoing bytes', 'fusioninventory');

         case 110:
            return __('Number of input errors', 'fusioninventory');

         case 111:
            return __('Number of output errors', 'fusioninventory');

         case 112:
            return __('CPU usage', 'fusioninventory');

         case 114:
            return __('Connection');

         case 115:
            return __('Internal MAC address', 'fusioninventory');

         case 116:
            return __('Name');

         case 117:
            return __('Model');

         case 118:
            return __('Type');

         case 119:
            return __('VLAN');

         case 120:
            return __('Alias', 'fusioninventory');

         case 128:
            return __('Total number of printed pages', 'fusioninventory');

         case 129:
            return __('Number of printed black and white pages', 'fusioninventory');

         case 130:
            return __('Number of printed color pages', 'fusioninventory');

         case 131:
            return __('Number of printed monochrome pages', 'fusioninventory');

         case 133:
            return __('Matte black cartridge', 'fusioninventory');

         case 134:
            return __('Black cartridge', 'fusioninventory');

         case 135:
            return __('Photo black cartridge', 'fusioninventory');

         case 136:
            return __('Cyan cartridge', 'fusioninventory');

         case 137:
            return __('Yellow cartridge', 'fusioninventory');

         case 138:
            return __('Magenta cartridge', 'fusioninventory');

         case 139:
            return __('Light cyan cartridge', 'fusioninventory');

         case 140:
            return __('Light magenta cartridge', 'fusioninventory');

         case 141:
            return __('Photoconductor', 'fusioninventory');

         case 142:
            return __('Black photoconductor', 'fusioninventory');

         case 143:
            return __('Color photoconductor', 'fusioninventory');

         case 144:
            return __('Cyan photoconductor', 'fusioninventory');

         case 145:
            return __('Yellow photoconductor', 'fusioninventory');

         case 146:
            return __('Magenta photoconductor', 'fusioninventory');

         case 147:
            return __('Black transfer unit', 'fusioninventory');

         case 148:
            return __('Cyan transfer unit', 'fusioninventory');

         case 149:
            return __('Yellow transfer unit', 'fusioninventory');

         case 150:
            return __('Magenta transfer unit', 'fusioninventory');

         case 151:
            return __('Waste bin', 'fusioninventory');

         case 152:
            return __('Four', 'fusioninventory');

         case 153:
            return __('Cleaning module', 'fusioninventory');

         case 154:
            return __('Number of pages printed duplex', 'fusioninventory');

         case 155:
            return __('Number of scanned pages', 'fusioninventory');

         case 156:
            return __('Maintenance kit', 'fusioninventory');

         case 157:
            return __('Black toner', 'fusioninventory');

         case 158:
            return __('Cyan toner', 'fusioninventory');

         case 159:
            return __('Magenta toner', 'fusioninventory');

         case 160:
            return __('Yellow toner', 'fusioninventory');

         case 161:
            return __('Black drum', 'fusioninventory');

         case 162:
            return __('Cyan drum', 'fusioninventory');

         case 163:
            return __('Magenta drum', 'fusioninventory');

         case 164:
            return __('Yellow drum', 'fusioninventory');

         case 165:
            return __('Many informations grouped', 'fusioninventory');

         case 166:
            return __('Black toner 2', 'fusioninventory');

         case 167:
            return __('Black toner Utilisé', 'fusioninventory');

         case 168:
            return __('Black toner Restant', 'fusioninventory');

         case 169:
            return __('Cyan toner Max', 'fusioninventory');

         case 170:
            return __('Cyan toner Utilisé', 'fusioninventory');

         case 171:
            return __('Cyan toner Restant', 'fusioninventory');

         case 172:
            return __('Magenta toner Max', 'fusioninventory');

         case 173:
            return __('Magenta toner Utilisé', 'fusioninventory');

         case 174:
            return __('Magenta toner Restant', 'fusioninventory');

         case 175:
            return __('Yellow toner Max', 'fusioninventory');

         case 176:
            return __('Yellow toner Utilisé', 'fusioninventory');

         case 177:
            return __('Yellow toner Restant', 'fusioninventory');

         case 178:
            return __('Black drum Max', 'fusioninventory');

         case 179:
            return __('Black drum Utilisé', 'fusioninventory');

         case 180:
            return __('Black drum Restant', 'fusioninventory');

         case 181:
            return __('Cyan drum Max', 'fusioninventory');

         case 182:
            return __('Cyan drum Utilisé', 'fusioninventory');

         case 183:
            return __('Cyan drumRestant', 'fusioninventory');

         case 184:
            return __('Magenta drum Max', 'fusioninventory');

         case 185:
            return __('Magenta drum Utilisé', 'fusioninventory');

         case 186:
            return __('Magenta drum Restant', 'fusioninventory');

         case 187:
            return __('Yellow drum Max', 'fusioninventory');

         case 188:
            return __('Yellow drum Utilisé', 'fusioninventory');

         case 189:
            return __('Yellow drum Restant', 'fusioninventory');

         case 190:
            return __('Waste bin Max', 'fusioninventory');

         case 191:
            return __('Waste bin Utilisé', 'fusioninventory');

         case 192:
            return __('Waste bin Restant', 'fusioninventory');

         case 193:
            return __('Maintenance kit Max', 'fusioninventory');

         case 194:
            return __('Maintenance kit Utilisé', 'fusioninventory');

         case 195:
            return __('Maintenance kit Restant', 'fusioninventory');

         case 196:
            return __('Grey ink cartridge', 'fusioninventory');

         case 197:
            return __('Paper roll in inches', 'fusioninventory');

         case 198:
            return __('Paper roll in centimeters', 'fusioninventory');

         case 199:
            return __('Transfer kit Max', 'fusioninventory');

         case 200:
            return __('Transfer kit used', 'fusioninventory');

         case 201:
            return __('Transfer kit remaining', 'fusioninventory');

         case 202:
            return __('Fuser kit', 'fusioninventory');

         case 203:
            return __('Fuser kit max', 'fusioninventory');

         case 204:
            return __('Fuser kit used', 'fusioninventory');

         case 205:
            return __('Fuser kit remaining', 'fusioninventory');

         case 206:
            return __('Gloss Enhancer ink cartridge', 'fusioninventory');

         case 207:
            return __('Blue ink cartridge', 'fusioninventory');

         case 208:
            return __('Green ink cartridge', 'fusioninventory');

         case 209:
            return __('Red ink cartridge', 'fusioninventory');

         case 210:
            return __('Chromatic Red ink cartridge', 'fusioninventory');

         case 211:
            return __('Light grey ink cartridge', 'fusioninventory');

         case 212:
            return __('Transfer kit', 'fusioninventory');

         case 1423:
            return __('Total number of printed pages (print)', 'fusioninventory');

         case 1424:
            return __('Number of printed black and white pages (print)', 'fusioninventory');

         case 1425:
            return __('Number of printed color pages (print)', 'fusioninventory');

         case 1426:
            return __('Total number of printed pages (copy)', 'fusioninventory');

         case 1427:
            return __('Number of printed black and white pages (copy)', 'fusioninventory');

         case 1428:
            return __('Number of printed color pages (copy)', 'fusioninventory');

         case 1429:
            return __('Total number of printed pages (fax)', 'fusioninventory');

         case 1434:
            return __('Total number of large printed pages', 'fusioninventory');

      }
      return $mapping['name'];
   }
}
