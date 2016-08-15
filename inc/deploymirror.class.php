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
 * This file is used to manage the deploy mirror depend on location of
 * computer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
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
 * Manage the deploy mirror depend on location of computer.
 */
class PluginFusioninventoryDeployMirror extends CommonDBTM {

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = TRUE;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_deploymirror';


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Mirror servers', 'fusioninventory');
   }



   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options=array()) {

      $ong=array();
      $this->addDefaultFormTab($ong)
         ->addStandardTab('Log', $ong, $options);

      return $ong;
   }



   /**
    * Get and filter mirrors list by computer agent and location.
    * Location is retrieved from the computer data.
    *
    * @global array $PF_CONFIG
    * @param integer $agents_id
    * @return array
    */
   static function getList($agents_id = NULL) {
      global $PF_CONFIG;

      if (is_null($agents_id)) {
         return array();
      }

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->getFromDB($agents_id);
      $agent = $pfAgent->fields;

      $ancestors = getAncestorsOf('glpi_entities', $agent['entities_id']);

      $results = getAllDatasFromTable(
              'glpi_plugin_fusioninventory_deploymirrors',
              "(`entities_id` IN ('".implode("', '", $ancestors)."') AND `is_recursive`='1')"
              . " OR `entities_id`='".$agent['entities_id']."'");
      if (!isset($agent) || !isset($agent['computers_id'])) {
         return array();
      }
      $computer = new Computer();
      $computer->getFromDB($agent['computers_id']);

      $mirrors = array();
      foreach ($results as $result) {
         if ($computer->fields['locations_id'] == $result['locations_id']) {
            $mirrors[] = $result['url'];
         }
      }

      //add default mirror (this server) if enabled in config
      $entities_id = 0;
      if (isset($agent['entities_id'])) {
         $entities_id = $agent['entities_id'];
      }
      if (isset($PF_CONFIG['server_as_mirror'])
              && (bool)$PF_CONFIG['server_as_mirror'] == TRUE) {
         $mirrors[] = PluginFusioninventoryAgentmodule::getUrlForModule('DEPLOY', $entities_id)
            ."?action=getFilePart&file=";
      }

      return $mirrors;
   }



   /**
    * Display form
    *
    * @global array $CFG_GLPI
    * @param integer $id
    * @param array $options
    * @return true
    */
   function showForm($id, $options=array()) {
      global $CFG_GLPI;

      $this->initForm($id, $options);

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')."&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'name', array('size' => 40));
      echo "</td>";

      echo "<td rowspan='2' class='middle right'>".__('Comments')."&nbsp;: </td>";
      echo "<td class='center middle' rowspan='2'><textarea cols='45'
      rows='4' name='comment' >".$this->fields["comment"]."</textarea></td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Mirror server address', 'fusioninventory')."&nbsp;:</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'url', array('size' => 40));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Mirror location', 'fusioninventory')." (".__('Location').")"."&nbsp;:</td>";
      echo "<td align='center'>";

      echo "<script type='text/javascript'>\n";
      echo "document.getElementsByName('is_recursive')[0].id = 'is_recursive';\n";
      echo "</script>";

      $params = array('is_recursive' => '__VALUE__',
                      'id'           => $id);
      Ajax::updateItemOnEvent('is_recursive', "displaydropdownlocation",
              $CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/dropdownlocation.php", $params);

      echo "<div id='displaydropdownlocation'>";
      // Location option
      Location::dropdown(
         array(
            'value'  => $this->fields["locations_id"],
            'entity' => $this->fields["entities_id"],
            'entity_sons' => $this->isRecursive(),
         )
      );
      echo "</div>";
      echo "</td></tr>";

      $this->showFormButtons($options);

      return TRUE;
   }



   /**
    * Get search function for the class
    *
    * @return array
    */
   function getSearchOptions() {

      $tab = array();
      $tab[1]['table']         = $this->getTable();
      $tab[1]['field']         = 'name';
      $tab[1]['linkfield']     = 'name';
      $tab[1]['name']          = __('Name');
      $tab[1]['datatype']      = 'itemlink';
      $tab[1]['itemlink_type'] = $this->getType();

      $tab[19]['table']     = $this->getTable();
      $tab[19]['field']     = 'date_mod';
      $tab[19]['linkfield'] = '';
      $tab[19]['name']      = __('Last update');
      $tab[19]['datatype']  = 'datetime';

      $tab[2]['table']     = $this->getTable();
      $tab[2]['field']     = 'url';
      $tab[2]['linkfield'] = 'url';
      $tab[2]['name']      = __('Mirror server address', 'fusioninventory');
      $tab[2]['datatype']  = 'string';

      $tab[16]['table']     = $this->getTable();
      $tab[16]['field']     = 'comment';
      $tab[16]['linkfield'] = 'comment';
      $tab[16]['name']      = __('Comments');
      $tab[16]['datatype']  = 'text';

      $tab[80]['table']     = 'glpi_entities';
      $tab[80]['field']     = 'completename';
      $tab[80]['name']      = __('Entity');
      $tab[80]['datatype']  = 'dropdown';

      $tab[81]['table']     = getTableNameForForeignKeyField('locations_id');
      $tab[81]['field']     = 'completename';
      $tab[81]['linkfield'] = 'locations_id';
      $tab[81]['name']      = Location::getTypeName();
      $tab[81]['datatype']  = 'itemlink';

      $tab[86]['table']     = $this->getTable();
      $tab[86]['field']     = 'is_recursive';
      $tab[86]['linkfield'] = 'is_recursive';
      $tab[86]['name']      = __('Child entities');
      $tab[86]['datatype']  = 'bool';

      return $tab;
   }



   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      $actions[__CLASS__.MassiveAction::CLASS_ACTION_SEPARATOR.'transfert'] = __('Transfer');
      return $actions;
   }



   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {
      if ($ma->getAction() == 'transfert') {
         Dropdown::show('Entity');
         echo Html::submit(_x('button','Post'), array('name' => 'massiveaction'));
         return TRUE;
      }
      return FALSE;
   }



   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $pfDeployMirror = new self();
      switch ($ma->getAction()) {

         case "transfert" :
            foreach ($ids as $key) {
               if ($pfDeployMirror->getFromDB($key)) {
                  $input = array();
                  $input['id'] = $key;
                  $input['entities_id'] = $_POST['entities_id'];
                  if ($pfDeployMirror->update($input)) {
                     //set action massive ok for this item
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     // KO
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            break;

      }
   }
}

?>