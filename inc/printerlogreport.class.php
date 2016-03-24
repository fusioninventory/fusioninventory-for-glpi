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
   @author    David Durieux
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
   die("Sorry. You can't access this file directly");
}

class PluginFusioninventoryPrinterLogReport extends CommonDBTM {

   function __construct() {
      global $CFG_GLPI;
      $this->table = "glpi_plugin_fusioninventory_printers";
      $CFG_GLPI['glpitablesitemtype']["PluginFusioninventoryPrinterLogReport"] = $this->table;
   }


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

?>
