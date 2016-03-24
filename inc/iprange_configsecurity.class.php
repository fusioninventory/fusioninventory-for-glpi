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
   @since     2014

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryIPRange_ConfigSecurity extends CommonDBRelation {

   // From CommonDBRelation
   static public $itemtype_1    = 'PluginFusioninventoryIPRange';
   static public $items_id_1    = 'plugin_fusioninventory_ipranges_id';
   static public $take_entity_1 = true ;

   static public $itemtype_2    = 'PluginFusioninventoryConfigSecurity';
   static public $items_id_2    = 'plugin_fusioninventory_configsecurities_id';
   static public $take_entity_2 = false ;



   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      $ong = array();
      if ($item->getID() > 0) {
         $ong[] = __('Associated SNMP authentications', 'fusioninventory');
      }
      return $ong;
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      $pfIPRange_ConfigSecurity = new self();
      $pfIPRange_ConfigSecurity->showForm($item);
   }



   function getForbiddenStandardMassiveAction() {

      $forbidden   = parent::getForbiddenStandardMassiveAction();
      $forbidden[] = 'update';
      return $forbidden;
   }



   function showForm(CommonDBTM $item, $options=array()) {

      $ID = $item->getField('id');

      if ($item->isNewID($ID)) {
         return false;
      }

      if (!$item->can($item->fields['id'], READ)) {
         return false;
      }
      $rand = mt_rand();

      $a_data = getAllDatasFromTable('glpi_plugin_fusioninventory_ipranges_configsecurities',
                                     "`plugin_fusioninventory_ipranges_id`='".$item->getID()."'",
                                     false,
                                     '`rank`');

      $a_used = array();
      foreach ($a_data as $data) {
         $a_used[] = $data['plugin_fusioninventory_configsecurities_id'];
      }
      echo "<div class='firstbloc'>";
      echo "<form name='iprange_configsecurity_form$rand' id='iprange_configsecurity_form$rand' method='post'
             action='".Toolbox::getItemTypeFormURL('PluginFusioninventoryIPRange_ConfigSecurity')."' >";

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_2'>";
      echo "<th colspan='2'>".__('Add a SNMP authentication')."</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_2'>";
      echo "<td>";
      Dropdown::show('PluginFusioninventoryConfigSecurity', array('used' => $a_used));
      echo "</td>";
      echo "<td>";
      echo Html::hidden('plugin_fusioninventory_ipranges_id',
                   array('value' => $item->getID()));
      echo "<input type='submit' name='add' value=\"".
          _sx('button', 'Associate')."\" class='submit'>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();
      echo "</div>";


      // Display list of auth associated with IP range
      $rand = mt_rand();

      echo "<div class='spaced'>";
      Html::openMassiveActionsForm('mass'.__CLASS__.$rand);
      $massiveactionparams = array('container' => 'mass'.__CLASS__.$rand);
      Html::showMassiveActions($massiveactionparams);

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_2'>";
      echo "<th width='10'>".Html::getCheckAllAsCheckbox('mass'.__CLASS__.$rand)."</th>";
      echo "<th>";
      echo __('SNMP Authentication', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      echo __('Version', 'fusioninventory');
      echo "</th>";
      echo "<th>";
      echo __('Rank');
      echo "</th>";
      echo "</tr>";

      $pfConfigSecurity = new PluginFusioninventoryConfigSecurity();
      foreach ($a_data as $data) {
         echo "<tr class='tab_bg_2'>";
         echo "<td>";
         Html::showMassiveActionCheckBox(__CLASS__, $data["id"]);
         echo "</td>";
         echo "<td>";
         $pfConfigSecurity->getFromDB($data['plugin_fusioninventory_configsecurities_id']);
         echo $pfConfigSecurity->getLink();
         echo "</td>";
         echo "<td>";
         echo $pfConfigSecurity->getSNMPVersion($pfConfigSecurity->fields['snmpversion']);
         echo "</td>";
         echo "<td>";
         echo $data['rank'];
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
      $massiveactionparams['ontop'] =false;
      Html::showMassiveActions($massiveactionparams);
      echo "</div>";
   }



   function post_purgeItem() {

   }
}

?>
