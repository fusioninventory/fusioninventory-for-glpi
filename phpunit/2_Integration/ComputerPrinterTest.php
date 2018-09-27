<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

class ComputerPrinter extends Common_TestCase {
   public $a_computer1 = [];
   public $a_computer1_beforeformat = [];
   public $a_computer2 = [];
   public $a_computer3 = [];


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
   public function PrinterUniqueSerialimport() {
      $this->mark_incomplete();
   }


   public function testPrinterDicoIgnoreImport() {
      global $DB;

      $DB->connect();

      self::restore_database();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfConfig         = new PluginFusioninventoryConfig();
      $computer         = new Computer();

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
      $input['value'] = '1';
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

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $a_printers = getAllDatasFromTable("glpi_printers");

      $this->assertEquals(0, countElementsInTable('glpi_printers'),
              'Printer p2 may be ignored ('.print_r($a_printers, true).')');

      $printer = new Printer();
      $printer->delete(['id' => 1], 1);
      $DB->query("TRUNCATE TABLE `glpi_printers`");

   }


   /**
    * @test
    */
   public function PrinterDicoRename() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfConfig         = new PluginFusioninventoryConfig();
      $computer         = new Computer();

      $DB->query("TRUNCATE TABLE `glpi_printers`");

      $pfici = new PluginFusioninventoryInventoryComputerInventory();

      $_SESSION['plugin_fusioninventory_agents_id'] = 1;

      $this->a_computer1_beforeformat["CONTENT"]['PRINTERS'][1]['NAME'] = 'p3';

      $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(1, countElementsInTable('glpi_printers'),
              'May have 1 printer in DB (1)');

      // Test re-inventory to see if not have more than 2 printers
      $pfici->sendCriteria("toto", $this->a_computer1_beforeformat);

      $this->assertEquals(1, countElementsInTable('glpi_printers'),
              'May have 1 printer in DB (2)');

      $printer = new Printer();
      $printer->getFromDB(1);
      $this->assertEquals('p3bis', $printer->fields['name'], 'Printer p3 may be renamed p3bis');

   }


   /**
    * @test
    */
   public function PrinterDicoManufacturer() {
      global $DB;

      $DB->connect();

      $printer = new Printer();
      $printer->getFromDB(1);
      $this->assertEquals('1',
         $printer->fields['manufacturers_id'], 'Printer p3 may have manufacturer with id=1'
      );
   }


   /**
    * @test
    */
   public function PrinterDicoUnitManagement() {

      $this->mark_incomplete();

      global $DB;

      $DB->connect();

      $printer = new Printer();
      $printer->getFromDB(2);
      $this->assertEquals('0', $printer->fields['is_global'], 'Printer p3 may be managed unit');
   }


   /**
    * @test
    */
   public function PrinterDicoGlobalManagement() {

      $this->mark_incomplete();

      global $DB;

      $DB->connect();

      $printer = new Printer();
      $printer->getFromDB(1);
      $this->assertEquals('1', $printer->fields['is_global'], 'Printer p3 may be managed global');
   }


}

