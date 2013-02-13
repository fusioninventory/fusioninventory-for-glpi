<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class ComputerEntity extends PHPUnit_Framework_TestCase {
   
   public function testAddComputer() {
      global $DB;

      $DB->connect();

      $_SESSION['glpi_foreign_key_field_of']['Entity'] = 'entities_id';
      
      $GLPIlog = new GLPIlogs();
      $entity = new Entity();
      $transfer = new Transfer();
      
      $_GET["id"] = -1;
      if ($newid=$entity->add(array('name'        => 'entity1',
                         'entities_id' => 0,
                         'itemtype'    => 'Entity',
                         'add'         => 'add'))) {
         
      }
      $entity->refreshParentInfos();
      
      
      /*
      Tests:
       
       * (1) entity rule => entity 1     |  transfers_id_auto = 1
       
            * add pc1            => entity 1
            * update pc1         => entity 1
            * transfert manual   => pc1 entity 0    
            * update pc1         => entity 1
      
       * (2) entity rule => entity 1     |  transfers_id_auto = 0
       
            * add pc1            => entity 1
            * update pc1         => entity 1
            * transfert manual   => pc1 entity 0    
            * update pc1         => entity 0
        
       * (3) no entity rule              |  transfers_id_auto = 1
       
            * add pc1            => entity 0
            * update pc1         => entity 0
            * transfert manual   => pc1 entity 1    
            * update pc1         => entity 1
       
      
      */
      
      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $pfConfig = new PluginFusioninventoryConfig();
      $computer = new Computer();
      
      // * (1)
      
         $a_inventory['HARDWARE'] = array(
             'NAME' => 'pc1'
         );

         // * Add rule ignore
            $rule = new Rule();
            $ruleCriteria = new RuleCriteria();
            $ruleAction = new RuleAction();

            $input = array();
            $input['sub_type']   = 'PluginFusioninventoryInventoryRuleEntity';
            $input['name']       = 'pc1';
            $input['match']      = 'AND';
            $input['is_active']  = 1;
            $rules_id = $rule->add($input); 

            $input = array();
            $input['rules_id']   = $rules_id;
            $input['criteria']   = 'name';
            $input['condition']  = 0;
            $input['pattern']    = 'pc1';
            $ruleCriteria->add($input);

            $input = array();
            $input['rules_id']      = $rules_id;
            $input['action_type']   = 'assign';
            $input['field']         = 'entities_id';
            $input['value']         = 1;
            $ruleAction->add($input);  

         $pfConfig->addValues(array('transfers_id_auto' => 1));

         // ** Add
            $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

            $GLPIlog->testSQLlogs();
            $GLPIlog->testPHPlogs();

            $computer->getFromDB(1);
            $this->assertEquals(1, $computer->fields['entities_id']);     
         
         // ** Update
            $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

            $GLPIlog->testSQLlogs();
            $GLPIlog->testPHPlogs();

            $computer->getFromDB(1);
            $this->assertEquals(1, $computer->fields['entities_id']);
      
         // ** Transfer to entity 0
            $transfer->getFromDB(1);
            $item_to_transfer = array("Computer" => array(1 => 1));
            $transfer->moveItems($item_to_transfer, 0, $transfer->fields);
            
            $GLPIlog->testSQLlogs();
            $GLPIlog->testPHPlogs();

            $computer->getFromDB(1);
            $this->assertEquals(0, $computer->fields['entities_id']);
            
         // ** Update
            $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

            $GLPIlog->testSQLlogs();
            $GLPIlog->testPHPlogs();

            $computer->getFromDB(1);
            $this->assertEquals(1, $computer->fields['entities_id']);

   }   
 }



class ComputerEntity_AllTests  {

   public static function suite() {

      $Install = new Install();
      $Install->testInstall(0); 
     
      $suite = new PHPUnit_Framework_TestSuite('ComputerEntity');
      return $suite;
   }
}

?>