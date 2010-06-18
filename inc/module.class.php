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
		$this->table="glpi_plugin_fusioninventory_modules";
	}

   /**
    * Add module
    *
    *@param $p_name Module name ('Fusioninventory', 'Fusinvsnmp'...)
    *@return integer the new id of the added item (or false if fail)
    **/
   function addModule($p_name) {
      return $this->add(array('name'=>$p_name));
   }

   /**
    * Update module
    *
    *@param $p_id Module id
    *@param $p_name Module name ('Fusioninventory', 'Fusinvsnmp'...)
    *@return boolean : true on success
    **/
   function updateModule($p_id, $p_name) {
      return $this->update(array('id'=>$p_id, 'name'=>$p_name));
   }

   /**
    * Delete module
    *
    *@param $p_id Module id
    *@return boolean : true on success
    **/
   function deleteModule($p_id) {
      return $this->delete(array('id'=>$p_id));
   }

   /**
    * Get module
    *
    *@param $p_name Module name
    *@return array(id, name, xmltag, plugins_id) (one line max)
    **/
   function get($p_name) {
      return $this->find("`name`='".$p_name."' AND `id`<>0
                          AND `plugins_id` IN (SELECT `id`
                                               FROM `glpi_plugins`
                                               WHERE `state`=1)");
   }

   /**
    * Get all modules
    *
    *@return array(id, name, xmltag, plugins_id)
    **/
   function getAll() {
      return $this->find("`id`<>0
                          AND `plugins_id` IN (SELECT `id`
                                               FROM `glpi_plugins`
                                               WHERE `state`=1)
                         ", 'name');
   }

}

?>