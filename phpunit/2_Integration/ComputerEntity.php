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
   
  
      /*
      Tests:
       
       * (step1) entity rule => entity 1     |  transfers_id_auto = 1
       
            * add pc1            => entity 1
            * update pc1         => entity 1
            * transfert manual   => pc1 entity 0    
            * update pc1         => entity 1
      
       * (step2) entity rule => entity 1     |  transfers_id_auto = 0
       
            * add pc1            => entity 1
            * update pc1         => entity 1
            * transfert manual   => pc1 entity 0    
            * update pc1         => entity 0
        
       * (step3) no entity rule              |  transfers_id_auto = 1
       
            * add pc1            => entity 0
            * update pc1         => entity 0
            * transfert manual   => pc1 entity 1    
            * update pc1         => entity 1
       
      
      */
      
   
   public function testAddComputerStep1() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      $transfer = new Transfer();
      
      $DB->query("INSERT INTO `glpi_entities` 
         (`id`, `name`, `entities_id`, `completename`, `level`) 
         VALUES (1, 'entity1', 0, 'Entité racine > entity1', 2)");
      
    
      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $pfConfig = new PluginFusioninventoryConfig();
      $computer = new Computer();
      
      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
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

      $pfConfig->updateValue('transfers_id_auto', 1);

      // ** Add
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Add computer');     

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer');


      // ** Transfer to entity 0
         $transfer->getFromDB(1);
         $item_to_transfer = array("Computer" => array(1 => 1));
         $transfer->moveItems($item_to_transfer, 0, $transfer->fields);

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(0, $computer->fields['entities_id'], 'manual transfer computer');

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer after '.
                  'manual transfer');

   }   
   
   
   
   public function testAddComputerStep2() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      $transfer = new Transfer();
      
      $DB->query("INSERT INTO `glpi_entities` 
         (`id`, `name`, `entities_id`, `completename`, `level`) 
         VALUES (1, 'entity1', 0, 'Entité racine > entity1', 2)");
      
    
      $_SESSION['glpiactive_entity'] = 0;
      $PF_CONFIG = array();
      
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $pfConfig = new PluginFusioninventoryConfig();
      $computer = new Computer();
      
      $pfConfig->updateValue('transfers_id_auto', 0);
      
      $a_inventory['CONTENT']['HARDWARE'] = array(
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

      // ** Add
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Add computer');     

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer');


      // ** Transfer to entity 0
         $transfer->getFromDB(1);
         $item_to_transfer = array("Computer" => array(1 => 1));
         $transfer->moveItems($item_to_transfer, 0, $transfer->fields);

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(0, $computer->fields['entities_id'], 'manual transfer computer');

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(2, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(0, $computer->fields['entities_id'], 'Update computer 1 after '.
                  'manual transfer');

         $computer->getFromDB(2);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer 2 after '.
                  'manual transfer');

   }   
   
   
   
   public function testAddComputerStep3() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      $transfer = new Transfer();
      
      $DB->query("INSERT INTO `glpi_entities` 
         (`id`, `name`, `entities_id`, `completename`, `level`) 
         VALUES (1, 'entity1', 0, 'Entité racine > entity1', 2)");
      
    
      $_SESSION['glpiactive_entity'] = 0;
      $PF_CONFIG = array();
      
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $pfConfig = new PluginFusioninventoryConfig();
      $computer = new Computer();
      
      $pfConfig->updateValue('transfers_id_auto', 1);
      
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc2'
      );

      // ** Add
         $pfiComputerInv->import("pc2-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(0, $computer->fields['entities_id'], 'Add computer');     

      // ** Update
         $pfiComputerInv->import("pc2-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(0, $computer->fields['entities_id'], 'Update computer');


      // ** Transfer to entity 1
         $transfer->getFromDB(1);
         $item_to_transfer = array("Computer" => array(1 => 1));
         $transfer->moveItems($item_to_transfer, 1, $transfer->fields);

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'manual transfer computer');

      // ** Update
         $pfiComputerInv->import("pc2-2013-02-13", "", $a_inventory); // creation

         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer 1 after '.
                  'manual transfer');

   }
 }



class ComputerEntity_AllTests  {

   public static function suite() {

 
     
      $suite = new PHPUnit_Framework_TestSuite('ComputerEntity');
      return $suite;
   }
}

?>