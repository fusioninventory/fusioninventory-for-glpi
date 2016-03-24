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
   @since     2013

   ------------------------------------------------------------------------
 */

class RuleImportTest extends Common_TestCase {


   protected function setUp() {
      global $DB;

      parent::setUp();

      $DB->connect();

      self::restore_database();

      $DB->query("UPDATE `glpi_rules`
         SET `is_active`='0'
         WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");

      // Add a rule to ignore import
      // Create rule for import into unknown devices
      $rule = new Rule();
      $input = array();
      $input['is_active']=1;
      $input['name']='Import pinter';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 198;
      $rule_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= '1';
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "serial";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Printer';
         $input['condition']=0;
         $rulecriteria->add($input);


         // Add action
         $ruleaction = new RuleAction();
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);

      // Denied import
      $input = array();
      $input['is_active']=1;
      $input['name']='Import pinter';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 199;
      $rule_id = $rule->add($input);

         // Add criteria
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= '1';
         $input['condition']=8;
         $rulecriteria->add($input);

         // Add action
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '2';
         $ruleaction->add($input);

   }



   /**
    * @test
    */
   public function PrinterDiscoveryImport() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $a_inventory = array(
          'AUTHSNMP'     => '1',
          'DESCRIPTION'  => 'Brother NC-6400h, Firmware Ver.1.11  (06.12.20),MID 84UZ92',
          'ENTITY'       => '0',
          'FIRMWARE'     => '',
          'IP'           => '10.36.4.29',
          'MAC'          => '00:80:77:d9:51:c3',
          'MANUFACTURER' => 'Brother',
          'MODEL'        => '',
          'MODELSNMP'    => 'Printer0442',
          'NETBIOSNAME'  => 'UH4DLPT01',
          'SERIAL'       => 'E8J596100',
          'SNMPHOSTNAME' => 'UH4DLPT01',
          'TYPE'         => 'PRINTER'
      );

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $printer = new Printer();

      $printer->add(array(
          'entities_id' => '0',
          'serial'      => 'E8J596100'
      ));

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Printer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_printers = $printer->find();
      $this->assertEquals(1, count($a_printers), 'May have only one Printer');

      $a_printer = current($a_printers);
      $this->assertEquals('UH4DLPT01', $a_printer['name'], 'Hostname of printer may be updated');

   }



   /**
    * @test
    */
   public function PrinterDiscoveryImportDenied() {
      global $DB;

      $DB->connect();

      $a_inventory = array(
          'AUTHSNMP'     => '1',
          'DESCRIPTION'  => 'Brother NC-6400h, Firmware Ver.1.11  (06.12.20),MID 84UZ92',
          'ENTITY'       => '0',
          'FIRMWARE'     => '',
          'IP'           => '10.36.4.29',
          'MAC'          => '00:80:77:d9:51:c3',
          'MANUFACTURER' => 'Brother',
          'MODEL'        => '',
          'MODELSNMP'    => 'Printer0442',
          'NETBIOSNAME'  => 'UH4DLPT01',
          'SERIAL'       => 'E8J596100A',
          'SNMPHOSTNAME' => 'UH4DLPT01',
          'TYPE'         => 'PRINTER'
      );

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $printer = new Printer();

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Printer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_printers = $printer->find();
      $this->assertEquals(0, count($a_printers), 'May have only one Printer');

      $pfTaskjoblog = new PluginFusioninventoryTaskjoblog();
      $a_logs = $pfTaskjoblog->find("`comment` LIKE '%importdenied%'", '`id` DESC', 1);
      $a_log = current($a_logs);
      $this->assertEquals('==importdenied== [serial]:E8J596100A, '.
              '[mac]:00:80:77:d9:51:c3, [ip]:10.36.4.29, [model]:Printer0442, '.
              '[name]:UH4DLPT01, [entities_id]:0, [itemtype]:Printer',
              $a_log['comment'], 'Import denied message');
   }

}
?>
