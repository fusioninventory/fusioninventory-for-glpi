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

class ComputerPrinter extends Common_TestCase {
   public $a_computer1 = array();
   public $a_computer1_beforeformat = array();
   public $a_computer2 = array();
   public $a_computer3 = array();

   function __construct() {
      $this->a_computer1 = array(
          "Computer" => array(
              "name"   => "pc001",
              "serial" => "ggheb7ne7"
          ),
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(
              array(
                  'name'    => 'p1',
                  'have_usb'=> 0,
                  'serial'  => '',
                  'is_dynamic' => 1
              ),
              array(
                  'name'    => 'p2',
                  'have_usb'=> 0,
                  'serial'  => 's1537',
                  'is_dynamic' => 1
              )
          ),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
      );

      $this->a_computer1_beforeformat = array(
          "CONTENT" => array(
              "HARDWARE" => array(
                  "NAME"   => "pc001"
              ),
              "BIOS" => array(
                  "SSN" => "ggheb7ne7"
              ),
              'PRINTERS'        => Array(
                  array(
                      'NAME'    => 'p1',
                      'SERIAL'  => ''
                  ),
                  array(
                      'NAME'    => 'p2',
                      'SERIAL'  => 's1537'
                  )
              )
          )
      );

      $this->a_computer2 = array(
          "Computer" => array(
              "name"   => "pc002",
              "serial" => "ggheb7ne8"
          ),
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(
              array(
                  'name'    => 'p1',
                  'have_usb'=> 0,
                  'serial'  => 'f275',
                  'is_dynamic' => 1
              ),
              array(
                  'name'    => 'p2',
                  'have_usb'=> 0,
                  'serial'  => 's1537',
                  'is_dynamic' => 1
              )
          ),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
      );

      $this->a_computer3 = array(
          "Computer" => array(
              "name"   => "pc003",
              "serial" => "ggheb7ne9"
          ),
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(
              array(
                  'name'    => 'p1',
                  'have_usb'=> 0,
                  'serial'  => '',
                  'is_dynamic' => 1
              ),
              array(
                  'name'    => 'p2',
                  'have_usb'=> 0,
                  'serial'  => '',
                  'is_dynamic' => 1
              )
          ),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
      );
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
      $input = array();
      $input['is_active']=1;
      $input['name']='Ignore import';
      $input['match']='AND';
      $input['sub_type'] = 'RuleDictionnaryPrinter';
      $input['ranking'] = 1;
      $rule_id = $rule->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "name";
      $input['pattern']= 'p2';
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


      // Add dictionnary rule for ignore import for printer p3
      $rulecollection = new RuleDictionnaryPrinterCollection();
      $rule = $rulecollection->getRuleClass();
      $input = array();
      $input['is_active']=1;
      $input['name']='rename';
      $input['match']='AND';
      $input['sub_type'] = 'RuleDictionnaryPrinter';
      $input['ranking'] = 2;
      $rule_id = $rule->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "name";
      $input['pattern']= 'p3';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = 'name';
      $input['value'] = 'p3bis';
      $ruleaction->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = 'manufacturer';
      $input['value'] = '1';
      $ruleaction->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
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
              'Printer p2 may be ignored ('.print_r($a_printers, TRUE).')');

      $printer = new Printer();
      $printer->delete(array('id' => 1), 1);
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

?>
