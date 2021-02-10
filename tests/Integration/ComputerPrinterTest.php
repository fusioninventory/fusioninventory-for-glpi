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

class ComputerPrinterTest extends TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];
   public $a_computer2 = [];
   public $a_computer3 = [];

   public static function setUpBeforeClass(): void {

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }
      // Delete all manufacturers
      $manufacturer = new Manufacturer();
      $items = $manufacturer->find();
      foreach ($items as $item) {
         $manufacturer->delete(['id' => $item['id']], true);
      }

      // Delete all dictionnaries
      $rule = new Rule();
      $items = $rule->find(['sub_type' => 'RuleDictionnaryPrinter']);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }

   }


   function __construct() {
      parent::__construct();
      $this->a_computer1 = [
         "Computer" => [
            "name"   => "pc001",
            "serial" => "ggheb7ne7"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         "computerdisk"   => [],
         'memory'         => [],
         'monitor'        => [],
         'printer'        => [
            [
               'name'    => 'p1',
               'have_usb'=> 0,
               'serial'  => '',
               'is_dynamic' => 1
            ],
            [
               'name'    => 'p2',
               'have_usb'=> 0,
               'serial'  => 's1537',
               'is_dynamic' => 1
            ]
         ],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'itemtype'       => 'Computer'
      ];

      $this->a_computer1_beforeformat = [
         "CONTENT" => [
            "HARDWARE" => [
               "NAME"   => "pc001"
            ],
            "BIOS" => [
               "SSN" => "ggheb7ne7"
            ],
            'PRINTERS'        => [
               [
                     'NAME'    => 'p1',
                     'SERIAL'  => ''
               ],
               [
                     'NAME'    => 'p2',
                     'SERIAL'  => 's1537'
               ]
            ]
         ]
      ];

      $this->a_computer2 = [
         "Computer" => [
            "name"   => "pc002",
            "serial" => "ggheb7ne8"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         "computerdisk"   => [],
         'memory'         => [],
         'monitor'        => [],
         'printer'        => [
            [
               'name'    => 'p1',
               'have_usb'=> 0,
               'serial'  => 'f275',
               'is_dynamic' => 1
            ],
            [
               'name'    => 'p2',
               'have_usb'=> 0,
               'serial'  => 's1537',
               'is_dynamic' => 1
            ]
         ],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'itemtype'       => 'Computer'
      ];

      $this->a_computer3 = [
         "Computer" => [
            "name"   => "pc003",
            "serial" => "ggheb7ne9"
         ],
         "fusioninventorycomputer" => [
            'last_fusioninventory_update' => date('Y-m-d H:i:s'),
            'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         "computerdisk"   => [],
         'memory'         => [],
         'monitor'        => [],
         'printer'        => [
            [
               'name'    => 'p1',
               'have_usb'=> 0,
               'serial'  => '',
               'is_dynamic' => 1
            ],
            [
               'name'    => 'p2',
               'have_usb'=> 0,
               'serial'  => '',
               'is_dynamic' => 1
            ]
         ],
         'peripheral'     => [],
         'networkport'    => [],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'itemtype'       => 'Computer'
      ];
   }


   /**
    * @test
    */
   public function testPrinterDicoIgnoreImport() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $manufacturer = new Manufacturer();
      $computer     = new Computer();

      $manufacturer->add(['name' => 'HP inc.']);

      /*
       * TODO: maybe we could use some dataProvider here ?
       */
      // Add dictionnary rule for ignore import for printer p2
      $rulecollection = new RuleDictionnaryPrinterCollection();
      $rule = $rulecollection->getRuleClass();
      $input = [];
      $input['is_active']=1;
      $input['name']='Ignore import';
      $input['match']='AND';
      $input['sub_type'] = 'RuleDictionnaryPrinter';
      $input['ranking'] = 1;
      $rule_id = $rule->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "name";
      $input['pattern']= 'p2';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_ignore_import';
      $input['value'] = '1';
      $ruleaction->add($input);

      // Add dictionnary rule for ignore import for printer p3
      $rulecollection = new RuleDictionnaryPrinterCollection();
      $rule = $rulecollection->getRuleClass();
      $input = [];
      $input['is_active']=1;
      $input['name']='rename';
      $input['match']='AND';
      $input['sub_type'] = 'RuleDictionnaryPrinter';
      $input['ranking'] = 2;
      $rule_id = $rule->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "name";
      $input['pattern']= 'p3';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = 'name';
      $input['value'] = 'p3bis';
      $ruleaction->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = 'manufacturer';
      $input['value'] = 'HP inc.';
      $ruleaction->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = 'is_global';
      $input['value'] = '0';
      $ruleaction->add($input);

      $pfici = new PluginFusioninventoryInventoryComputerInventory();

      $_SESSION['plugin_fusioninventory_agents_id'] = 1;
      $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $a_printers = getAllDataFromTable("glpi_printers");

      $this->assertEquals(0, countElementsInTable('glpi_printers'),
              'Printer p2 may be ignored ('.print_r($a_printers, true).')');

   }


   /**
    * @test
    */
   public function PrinterDicoRename() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $computer = new Computer();
      $pfici    = new PluginFusioninventoryInventoryComputerInventory();

      $_SESSION['plugin_fusioninventory_agents_id'] = 1;

      $this->a_computer1_beforeformat["CONTENT"]['PRINTERS'][1]['NAME'] = 'p3';

      $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);

      $computer->getFromDBByCrit(['name' => 'pc001']);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_printers'),
              'May have 1 printer in DB (1)');

      // Test re-inventory to see if not have more than 2 printers
      $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);

      $this->assertEquals(1, countElementsInTable('glpi_printers'),
              'May have 1 printer in DB (2)');

      // TODO
      // $printer = new Printer();
      // $printer->getFromDB(1);
      // $this->assertEquals('p3bis', $printer->fields['name'], 'Printer p3 may be renamed p3bis');

   }


   /**
    * @test
    */
   public function PrinterDicoManufacturer() {
      $printer = new Printer();
      $manufacturer = new Manufacturer();
      $printer->getFromDBByCrit(['name' => 'p3bis']);
      $manufacturer->getFromDBByCrit(['name' => 'HP inc.']);

      $this->assertEquals($manufacturer->fields['id'],
         $printer->fields['manufacturers_id'], 'Printer p3 may have manufacturer'
      );
   }


   /**
    * @test
    */
   public function PrinterDicoUnitManagement() {
      $printer = new Printer();
      $printer->getFromDBByCrit(['name' => 'p3bis']);
      $this->assertEquals('0', $printer->fields['is_global'], 'Printer p3 may be managed unit');
   }


   // public function PrinterDicoGlobalManagement() {
   //    // TODO seems global not works
   //    return;

   //    $printer = new Printer();
   //    $pfici    = new PluginFusioninventoryInventoryComputerInventory();
   //    $config = new Config();

   //    $printer->getFromDBByCrit(['name' => 'p3bis']);
   //    $printer->delete(['id' => $printer->fields['id']], true);

   //    // change config
   //    $CFG_GLPI["printers_management_restrict"] = 1;
   //    $config->setConfigurationValues('core', ['printers_management_restrict' => 1]);

   //    $_SESSION['plugin_fusioninventory_agents_id'] = 1;

   //    $this->a_computer1_beforeformat["CONTENT"]['PRINTERS'][1]['NAME'] = 'p3';

   //    $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);


   //    $printer->getFromDBByCrit(['name' => 'p3bis']);
   //    $this->assertEquals('1', $printer->fields['is_global'], 'Printer p3 may be managed global');
   // }
}
