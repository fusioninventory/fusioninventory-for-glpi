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
   die("Sorry. You can't access this file directly");
}


class PluginFusinvsnmpConfig extends CommonDBTM {

   function initConfigModule() {

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $insert = array('storagesnmpauth'      => 'DB',
                      'version'              => PLUGIN_FUSINVSNMP_VERSION,
                      'threads_netdiscovery' => 1,
                      'threads_snmpquery'    => 1);
      $pfConfig->addValues($plugins_id, $insert);
   }

   
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry(_('FusionInventory SNMP'));

         }
         return _('FusionInventory SNMP');

      }
      return '';
   }
   
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         $pfConfig = new self();
         $pfConfig->showForm($item);
      }
      return true;
   }


   
   
   function putForm($p_post) {

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $pfConfig->updateConfigType($plugins_id, 'storagesnmpauth', 
                                                     $p_post['storagesnmpauth']);
      $pfConfig->updateConfigType($plugins_id, 'threads_netdiscovery',
                                                     $p_post['threads_netdiscovery']);
      $pfConfig->updateConfigType($plugins_id, 'threads_snmpquery',
                                                     $p_post['threads_snmpquery']);
   }

   

   function showForm($options=array()) {
      global $CFG_GLPI;

      $pfConfig = new PluginFusioninventoryConfig();

      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');

      echo "<form name='form' method='post' action='".$this->getFormURL()."'>";
      echo "<div class='center' id='tabsbody'>";
      echo "<table class='tab_cadre_fixe'>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>"._('SNMP authentication')."&nbsp;:</td>";
      echo "<td>";
      $ArrayValues = array();
      $ArrayValues['DB']= _('Database');

      $ArrayValues['file']= _('Files');

      Dropdown::showFromArray('storagesnmpauth', $ArrayValues,
                              array('value'=>$pfConfig->getValue($plugins_id, 'storagesnmpauth')));
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>"._('Threads number')."&nbsp;(".strtolower(_('Network discovery')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_netdiscovery", $pfConfig->getValue($plugins_id, 'threads_netdiscovery'),1,400);
      echo "</td>";
      echo "<td>"._('Threads number')."&nbsp;(".strtolower(_('Network inventory (SNMP)')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_snmpquery", $pfConfig->getValue($plugins_id, 'threads_snmpquery'),1,400);
      echo "</td>";
      echo "</tr>";


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "w")) {
         echo "<tr class='tab_bg_2'><td align='center' colspan='4'>
               <input class='submit' type='submit' name='plugin_fusinvsnmp_config_set'
                      value='" . _('Update') . "'></td></tr>";
      }
      echo "</table>";
      Html::closeForm();
      echo "<br/>";

      $pfConfigLogField = new PluginFusioninventoryConfigLogField();
      $pfConfigLogField->showForm(array('target'=>$CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/functionalities.form.php"));

      $pfNetworkporttype = new PluginFusinvsnmpNetworkporttype();
      $pfNetworkporttype->showNetworkporttype();
      
      return true;
   }
}

?>