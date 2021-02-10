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
   @copyright Copyright (c) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2021

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class ToolboxTest extends TestCase {

   public $formatJson_input = [
      'test_text' => 'Lorem Ipsum',
      'test_number' => 1234,
      'test_float' => 1234.5678,
      'test_array' => [ 1,2,3,4, 'lorem_ipsum' ],
      'test_hash' => ['lorem' => 'ipsum', 'ipsum' => 'lorem']
   ];

   public $formatJson_expected = <<<JSON
{
    "test_text": "Lorem Ipsum",
    "test_number": 1234,
    "test_float": 1234.5678,
    "test_array": [
        1,
        2,
        3,
        4,
        "lorem_ipsum"
    ],
    "test_hash": {
        "lorem": "ipsum",
        "ipsum": "lorem"
    }
}
JSON;


   /**
    * @test
    */
   public function formatJson() {

      $this->assertEquals(
         $this->formatJson_expected,
         PluginFusioninventoryToolbox::formatJson(json_encode($this->formatJson_input))
      );
   }


   /**
    * @test
    */
   public function isAFusionInventoryDevice() {
      $computer = new Computer();

      $this->assertFalse(PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer));

      $values = ['name'         => 'comp',
                 'is_dynamic'   => 1,
                 'entities_id'  => 0,
                 'is_recursive' => 0];
      $computers_id = $computer->add($values);
      $computer->getFromDB($computers_id);

      $this->assertFalse(PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer));

      $pfComputer = new PluginFusioninventoryInventoryComputerComputer();
      $pfComputer->add(['computers_id' => $computers_id]);
      $this->assertTrue(PluginFusioninventoryToolbox::isAFusionInventoryDevice($computer));

      $printer = new Printer();
      $values  = ['name'         => 'printer',
                  'is_dynamic'   => 1,
                  'entities_id'  => 0,
                  'is_recursive' => 0];
      $printers_id = $printer->add($values);
      $printer->getFromDB($printers_id);
      $this->assertFalse(PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer));

      $pfPrinter = new PluginFusioninventoryPrinter();
      $pfPrinter->add(['printers_id' => $printers_id]);
      $this->assertTrue(PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer));

      $values  = ['name'         => 'printer2',
                  'is_dynamic'   => 0,
                  'entities_id'  => 0,
                  'is_recursive' => 0];
      $printers_id_2 = $printer->add($values);
      $printer->getFromDB($printers_id_2);
      $pfPrinter->add(['printers_id' => $printers_id_2]);
      $this->assertFalse(PluginFusioninventoryToolbox::isAFusionInventoryDevice($printer));

   }


   /**
    * @test
    */
   public function addDefaultStateIfNeeded() {
      $input = [];

      $state = new State();
      $states_id_computer = $state->importExternal('state_computer');
      $states_id_snmp = $state->importExternal('state_snmp');

      $config = new PluginFusioninventoryConfig();
      $config->updateValue('states_id_snmp_default', $states_id_snmp);
      $config->updateValue('states_id_default', $states_id_computer);

      $result = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('computer', $input);
      $this->assertEquals(['states_id' => $states_id_computer], $result);

      $result = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('snmp', $input);
      $this->assertEquals(['states_id' => $states_id_snmp], $result);

      $result = PluginFusioninventoryToolbox::addDefaultStateIfNeeded('foo', $input);
      $this->assertEquals([], $result);

      // Redo default
      $config->updateValue('states_id_snmp_default', 0);
      $config->updateValue('states_id_default', 0);
   }
}
