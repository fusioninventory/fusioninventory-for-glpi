<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-201° by the INDEPNET Development Team.

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
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryModule extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugins";
	}

   /**
    * Get all active modules
    *
    *@return array of fields
    **/
   static function getAll() {
      $plugin = new Plugin;
      return $plugin->find("`state`='1' AND `directory` LIKE 'fusinv%'");
   }

   /**
    * Get module id or fusioninventory plugin id
    *
    *@param $p_name Module name
    *@return Plugin id or false if module is not active or not a fusioninventory module
    **/
   static function getModuleId($p_name) {
      if ((substr($p_name, 0, 6) == 'fusinv') OR ($p_name == 'fusioninventory')) {
         return array_search($p_name, $_SESSION['glpi_plugins']);
      } else {
         return false;
      }
   }

   /**
    * Get module name
    *
    *@param $p_id Module id
    *@return name or false if module is not active or not a fusioninventory module
    **/
   static function getModuleName($p_id) {
      if (isset($_SESSION['glpi_plugins'][$p_id])) {
         if ((substr($_SESSION['glpi_plugins'][$p_id], 0, 6) == 'fusinv')
              OR ($_SESSION['glpi_plugins'][$p_id] == 'fusioninventory')) {
            return $_SESSION['glpi_plugins'][$p_id];
         } else {
            return false;
         }
      } else {
         return false;
      }
   }


}

?>