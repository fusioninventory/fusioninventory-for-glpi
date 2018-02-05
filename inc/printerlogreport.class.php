<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the log reports of printers.
 */
class PluginFusioninventoryPrinterLogReport extends CommonDBTM {


   /**
    * __contruct function where initialize some variables
    *
    * @global array $CFG_GLPI
    */
   function __construct() {
      global $CFG_GLPI;
      $this->table = "glpi_plugin_fusioninventory_printers";
      $CFG_GLPI['glpitablesitemtype']["PluginFusioninventoryPrinterLogReport"] = $this->table;
   }


   /**
    * Get search function for the class
    *
    * @return array
    */
   function getSearchOptions() {

      $pfPrinterLog = new PluginFusioninventoryPrinterLog();
      $tab = $pfPrinterLog->getSearchOptions();

      $tab[6]['forcegroupby']='1';
      $tab[7]['forcegroupby']='1';
      $tab[8]['forcegroupby']='1';
      $tab[9]['forcegroupby']='1';
      $tab[10]['forcegroupby']='1';
      $tab[11]['forcegroupby']='1';
      $tab[12]['forcegroupby']='1';
      $tab[13]['forcegroupby']='1';
      $tab[14]['forcegroupby']='1';
      $tab[15]['forcegroupby']='1';
      $tab[16]['forcegroupby']='1';
      $tab[17]['forcegroupby']='1';

      return $tab;
   }


}

