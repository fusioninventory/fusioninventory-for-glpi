<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

/// Plugin FusionInventory lock class
class PluginFusionInventoryLock extends CommonDBTM{

	/**
	 * Constructor
	**/
	function __construct () {
		$this->table="glpi_plugin_fusioninventory_lock";
		$this->type=-1;
	}


	/**
    * Show locks form.
    *
    *@param $p_target Target file.
    *@param $p_itemtype Table name.
    *@param $p_items_id Line id.
    *TODO:  check rights and entity
    *
    *@return nothing (print the form)
    **/
   // si suppr du lockable --> suppr les locks
   function showForm($p_target, $p_itemtype, $p_items_id) {
      global $DB, $LANG, $SEARCH_OPTION;

      echo "<div width='50%'>";
      $lockable_fields = plugin_fusioninventory_lockable_getLockableFields('', $p_itemtype);
         $locked = plugin_fusioninventory_lock_getLockFields($p_itemtype, $p_items_id);
         if (count($locked)){
            foreach ($locked as $key => $val){
               if (!in_array($val, $lockable_fields)) {
                  unset($locked[$key]);
               }
            }
      } else {
         $locked = array();
      }

      include_once(GLPI_ROOT.'/plugins/fusioninventory/inc_constants/plugin_fusioninventory.mapping.fields.constant.php');
      $CommonItem = new CommonItem;
      $CommonItem->getFromDB($p_itemtype, $p_items_id);

      echo "<form method='post' action=\"$p_target\">";
      echo "<input type='hidden' name='ID' value='$p_items_id'>";
      echo "<input type='hidden' name='type' value='$p_itemtype'>";
      echo "<table class='tab_cadre'>";
      echo "<tr><th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][73]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][74]."&nbsp;</th>";
      echo "<th>&nbsp;".$LANG['plugin_fusioninventory']["functionalities"][75]."&nbsp;</th></tr>";
      foreach ($lockable_fields as $key => $val) {
         if (in_array($val, $locked)) {
            $checked = 'checked';
         } else {
            $checked = '';
         }
         echo "<tr class='tab_bg_1'><td>" . $FUSIONINVENTORY_MAPPING_FIELDS[$val] . "</td>
                  <td>".$CommonItem->getField($val)."</td><td align='center'><input type='checkbox' name='lockfield_fusioninventory[" . $val . "]' $checked></td></tr>";
      }
      echo "<tr class='tab_bg_2'><td align='center' colspan='3'>
               <input class='submit' type='submit' name='unlock_field_fusioninventory'
                      value='" . $LANG['buttons'][7] . "'></td></tr>";
      echo "</table>";
      echo "</form>";
      echo "</div>";
   }
}

?>