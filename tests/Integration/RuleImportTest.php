<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class RuleImportTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all inventoryrules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRuleImport"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }
   }

   public static function tearDownAfterClass(): void {
      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }

   function setUp(): void {

      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }

      // Delete all networkequipment
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

   }


   /**
    * @test
    */
   function changeRulesForPrinterRules() {

      $rule = new Rule();
      // Add a rule test check model
      $input = [
         'is_active' => 1,
         'name'      => 'Printer model',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport',
         'ranking'   => 198,
      ];
      $rule_id = $rule->add($input);
      $this->assertNotFalse($rule_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'model',
         'pattern'   => '1',
         'condition' => 10
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

      // Add a rule to ignore import
      // Create rule for import into unknown devices
      $input = [
         'is_active' => 1,
         'name'      => 'Import printer',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport',
         'ranking'   => 199,
      ];
      $rule_id = $rule->add($input);
      $this->assertNotFalse($rule_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'name',
         'pattern'   => '1',
         'condition' => 8
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'serial',
         'pattern'   => '1',
         'condition' => 10
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'serial',
         'pattern'   => '1',
         'condition' => 8
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'itemtype',
         'pattern'   => 'Printer',
         'condition' => 0
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

      // Denied import
      $input = [
         'is_active' => 1,
         'name'      => 'Import printer',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport',
         'ranking'   => 200,
      ];
      $rule_id = $rule->add($input);
      $this->assertNotFalse($rule_id);

      // Add criteria
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'name',
         'pattern'   => '1',
         'condition' => 8
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '2'
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

   }


   /**
    * @test
    */
   public function PrinterDiscoveryImport() {

      $this->changeRulesForPrinterRules();

      $a_inventory = [
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
      ];

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $printer = new Printer();

      $printer->add([
          'entities_id' => '0',
          'serial'      => 'E8J596100'
      ]);

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

      $this->changeRulesForPrinterRules();

      $a_inventory = [
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
      ];

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
      $a_logs = $pfTaskjoblog->find(['comment' => ['LIKE', '%importdenied%']], ['id DESC'], 1);
      $a_log = current($a_logs);
      $this->assertEquals('==importdenied== [serial]:E8J596100A, '.
              '[mac]:00:80:77:d9:51:c3, [ip]:10.36.4.29, [model]:Printer0442, '.
              '[name]:UH4DLPT01, [entities_id]:0, [itemtype]:Printer',
              $a_log['comment'], 'Import denied message');
   }
}
