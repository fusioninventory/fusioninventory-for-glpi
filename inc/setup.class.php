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
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventorySetup {

   // Uninstallation function
   static function uninstall() {
      global $DB;

      CronTask::Unregister('fusioninventory');
      PluginFusioninventoryProfile::uninstallProfile();

      $pfSetup  = new PluginFusioninventorySetup();
      $user     = new User();

      if (class_exists('PluginFusioninventoryConfig')) {
         $fusioninventory_config      = new PluginFusioninventoryConfig();
         $users_id = $fusioninventory_config->getValue('users_id');
         $user->delete(array('id'=>$users_id), 1);
      }

      if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         $pfSetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }

      $query = "SHOW TABLES;";
      $result = $DB->query($query);
      while ($data = $DB->fetch_array($result)) {
         if ((strstr($data[0], "glpi_plugin_fusioninventory_"))
                 OR (strstr($data[0], "glpi_plugin_fusinvsnmp_"))
                 OR (strstr($data[0], "glpi_plugin_fusinvinventory_"))
                OR (strstr($data[0], "glpi_dropdown_plugin_fusioninventory"))
                OR (strstr($data[0], "glpi_plugin_tracker"))
                OR (strstr($data[0], "glpi_dropdown_plugin_tracker"))) {

            $query_delete = "DROP TABLE `".$data[0]."`;";
            $DB->query($query_delete) or die($DB->error());
         }
      }

      $query= "DELETE FROM `glpi_displaypreferences`
               WHERE `itemtype` LIKE 'PluginFusioninventory%';";
      $DB->query($query) or die($DB->error());

      // Delete rules
      $Rule = new Rule();
      $Rule->deleteByCriteria(array('sub_type' => 'PluginFusioninventoryInventoryRuleImport'));

      //Remove informations related to profiles from the session (to clean menu and breadcrumb)
      PluginFusioninventoryProfile::removeRightsFromSession();
      return TRUE;
   }



   /**
    * Remove a directory and sub-directory
    *
    * @param type $dir name of the directory
    */
   function rrmdir($dir) {
      $pfSetup = new PluginFusioninventorySetup();

      if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (filetype($dir."/".$object) == "dir") {
               $pfSetup->rrmdir($dir."/".$object);
            } else {
               unlink($dir."/".$object);
            }
          }
        }
        reset($objects);
        rmdir($dir);
      }
   }



   /**
    * Create rules (initialisation)
    */
   function initRules($reset = 0) {

      if ($reset == 1) {
         $grule = new Rule();
         $a_rules = $grule->find("`sub_type`='PluginFusioninventoryInventoryRuleImport'");
         foreach ($a_rules as $data) {
            $grule->delete($data);
         }
      }

      $rules = array();

      $rules[] = array(
         'name'      => 'Computer constraint (name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 9,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Computer update (by serial + uuid)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );
      $rules[] = array(
         'name'      => 'Computer update (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer update (by uuid)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer update (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer update (by name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'name',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import (by serial + uuid)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import (by uuid)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'uuid',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import (by name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Computer import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Computer'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Printer constraint (name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 9,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Printer update (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Printer update (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Printer import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Printer import (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Printer import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Printer'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment constraint (name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 9,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment update (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment import (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'NetworkEquipment import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'NetworkEquipment'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Peripheral update (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Peripheral'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Peripheral import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Peripheral'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Peripheral import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Peripheral'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Monitor update (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Monitor'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Monitor import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Monitor'
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Monitor import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Monitor'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Phone constraint (name)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Phone'
            ),
            array(
               'criteria'  => 'name',
               'condition' => 9,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Phone update (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Phone'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 10,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Phone import (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Phone'
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Phone import denied',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => 'Phone'
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Global constraint (name)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'name',
               'condition' => 9,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion2'
      );

      $rules[] = array(
         'name'      => 'Global update (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'serial',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Global update (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            ),
            array(
               'criteria'  => 'mac',
               'condition' => 10,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Global import (by serial)',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'serial',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Global import (by mac)',
         'match'     => 'AND',
         'is_active' => 0,
         'criteria'  => array(
            array(
               'criteria'  => 'mac',
               'condition' => 8,
               'pattern'   => 1
            )
         ),
         'action'    => '_fusion1'
      );

      $rules[] = array(
         'name'      => 'Global import denied',
         'match'     => 'AND',
         'is_active' => 1,
         'criteria'  => array(
            array(
               'criteria'  => 'itemtype',
               'condition' => 0,
               'pattern'   => ''
            )
         ),
         'action'    => '_fusion2'
      );


      $ranking = 0;
      foreach ($rules as $rule) {
         $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
         $input = array();
         $input['is_active'] = $rule['is_active'];
         $input['name']      = $rule['name'];
         $input['match']     = $rule['match'];
         $input['sub_type']  = 'PluginFusioninventoryInventoryRuleImport';
         $input['ranking']   = $ranking;
         $rule_id = $rulecollection->add($input);

         // Add criteria
         $rulefi = $rulecollection->getRuleClass();
         foreach ($rule['criteria'] as $criteria) {
            $rulecriteria = new RuleCriteria(get_class($rulefi));
            $criteria['rules_id'] = $rule_id;
            $rulecriteria->add($criteria);
         }

         // Add action
         $ruleaction = new RuleAction(get_class($rulefi));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         if ($rule['action'] == '_fusion1') {
            $input['field'] = '_fusion';
            $input['value'] = '1';
         } else if ($rule['action'] == '_fusion2') {
            $input['field'] = '_fusion';
            $input['value'] = '2';
         } else if ($rule['action'] == '_ignore_import') {
            $input['field'] = '_ignore_import';
            $input['value'] = '1';
         }
         $ruleaction->add($input);

         $ranking++;
      }








      return true;

      // Old rules

      $ranking = 0;

     // Create rule for : Peripheral + serial
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Peripheral serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Peripheral';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Peripheral import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Peripheral import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Peripheral';
         $input['condition']=0;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Peripheral ignore import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Peripheral ignore import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Peripheral';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_ignore_import';
         $input['value'] = '1';
         $ruleaction->add($input);

     $ranking++;
     // Create rule for : Monitor + serial
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Monitor serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Monitor';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Monitor import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Monitor import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Monitor';
         $input['condition']=0;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Monitor ignore import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Monitor ignore import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Monitor';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_ignore_import';
         $input['value'] = '1';
         $ruleaction->add($input);

     $ranking++;
     // Create rule for : Computer + serial + uuid
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer serial + uuid';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "uuid";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for : Computer + serial
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;

     $ranking++;
     // Create rule for : Computer + mac
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=0;
      $input['name']='Computer mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

     $ranking++;
     // Create rule for : Computer + name
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer name';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Computer import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

//         $input = array();
//         $input['rules_id'] = $rule_id;
//         $input['criteria'] = "name";
//         $input['pattern']= 1;
//         $input['condition']=8;
//         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);



     $ranking++;
     // Create rule for : Printer + serial
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Printer + mac
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Printer + name
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer name';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : Printer import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Printer import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment + serial
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment serial';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment + mac
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment mac';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for : NetworkEquipment import
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='NetworkEquipment import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'NetworkEquipment';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      $ranking++;
      // Create rule for search serial in all DB
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find serial in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for search mac in all DB
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find mac in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "mac";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);


     $ranking++;
     // Create rule for search name in all DB
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Find name in all GLPI';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);


      $ranking++;
      // Create rule for import into unmanaged devices
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Unmanaged device import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '*';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);
   }



   /**
    * Creation of FusionInventory user
    *
    * @return int id of the user "plugin FusionInventory"
    */
   function createFusionInventoryUser() {
      $user = new User();
      $a_users = array();
      $a_users = $user->find("`name`='Plugin_FusionInventory'");
      if (count($a_users) == '0') {
         $input = array();
         $input['name'] = 'Plugin_FusionInventory';
         $input['password'] = mt_rand(30, 39);
         $input['firstname'] = "Plugin FusionInventory";
         return $user->add($input);
      } else {
         $user = current($a_users);
         return $user['id'];
      }
   }
}

?>
