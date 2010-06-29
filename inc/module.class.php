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
    *@param $p_pluginsId Plugin id
    *@param $p_xmltag Tag used for module name in XML file
    *@return integer the new id of the added item (or false if fail)
    **/
   static function addModule($p_pluginsId, $p_xmltag) {
      $pfm = new PluginFusioninventoryModule;
      return $pfm->add(array('plugins_id'=>$p_pluginsId, 'xmltag'=>$p_xmltag));
   }

   /**
    * Update module
    *
    *@param $p_id Module id
    *@param $p_xmltag Tag used for module name in XML file
    *@return boolean : true on success
    **/
   function updateModule($p_id, $p_xmltag) {
      return $this->update(array('id'=>$p_id, 'xmltag'=>$p_xmltag));
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
    *@param $p_id Module id
    *@return array(id, xmltag, plugins_id) (one line max)
    **/
   function get($p_id) {
      $pfm = new PluginFusioninventoryModule;
      return $pfm->find("`id`='".$p_id."'
                          AND `plugins_id` IN (SELECT `id`
                                               FROM `glpi_plugins`
                                               WHERE `state`=1)");
   }

   /**
    * Get all modules
    *
    *@return array(id, xmltag, plugins_id)
    **/
   static function getAll() {
      $pfm = new PluginFusioninventoryModule;
      return $pfm->find("`id`<>0
                          AND `plugins_id` IN (SELECT `id`
                                               FROM `glpi_plugins`
                                               WHERE `state`=1)");
   }

   /**
    * Get module id
    *
    *@param $p_name Module name
    *@return id or false if module is not active
    **/
   static function getId($p_name) {
      return array_search($p_name, $_SESSION['glpi_plugins']);
   }

   /**
    * Get module name
    *
    *@param $p_id Module id
    *@return name or false if module is not active
    **/
   static function getModuleName($p_id) {
      if (isset ($_SESSION['glpi_plugins'][$p_id])) {
         return $_SESSION['glpi_plugins'][$p_id];
      } else {
         return false;
      }
   }


}

?>