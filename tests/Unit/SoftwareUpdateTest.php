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
   @since     2010

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class SoftwareUpdateTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all softwares
      $software = new Software();
      $items = $software->find();
      foreach ($items as $item) {
         $software->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function AddAllRules() {

      $rule         = new Rule();
      $ruleCriteria = new RuleCriteria();
      $ruleAction   = new RuleAction();

      $nbRules = countElementsInTable('glpi_rules');

      //Add a rule to rename indepnet manufacturer
      $input               = [];
      $input['sub_type']   = 'RuleDictionnaryManufacturer';
      $input['name']       = 'Set indepnet manufacturer';
      $input['match']      = 'AND';
      $input['is_active']  = 1;
      $rules_id = $rule->add($input);

      $input               = [];
      $input['rules_id']   = $rules_id;
      $input['criteria']   = 'name';
      $input['condition']  = Rule::PATTERN_IS;
      $input['pattern']    = 'indepnet assoce';
      $ruleCriteria->add($input);

      $input                  = [];
      $input['rules_id']      = $rules_id;
      $input['action_type']   = 'assign';
      $input['field']         = 'name';
      $input['value']         = 'indepnet';
      $ruleAction->add($input);

      // * Add a rule to explicitly ignore import of a software whose name is
      // 'glpi'
      $input               = [];
      $input['sub_type']   = 'RuleDictionnarySoftware';
      $input['name']       = 'Ignore glpi software import';
      $input['match']      = 'AND';
      $input['is_active']  = 1;
      $rules_id = $rule->add($input);

      $input               = [];
      $input['rules_id']   = $rules_id;
      $input['criteria']   = 'name';
      $input['condition']  = Rule::PATTERN_IS;
      $input['pattern']    = 'glpi';
      $ruleCriteria->add($input);

      $input                  = [];
      $input['rules_id']      = $rules_id;
      $input['action_type']   = 'assign';
      $input['field']         = '_ignore_import';
      $input['value']         = 1;
      $ruleAction->add($input);

      // * Add rule rename software
      $input               = [];
      $input['sub_type']   = 'RuleDictionnarySoftware';
      $input['name']       = 'Change glpi name';
      $input['match']      = 'AND';
      $input['is_active']  = 1;
      $rules_id = $rule->add($input);

      $input               = [];
      $input['rules_id']   = $rules_id;
      $input['criteria']   = 'name';
      $input['condition']  = Rule::PATTERN_IS;
      $input['pattern']    = 'glpi0.85';
      $ruleCriteria->add($input);

      $input                  = [];
      $input['rules_id']      = $rules_id;
      $input['action_type']   = 'assign';
      $input['field']         = 'name';
      $input['value']         = 'glpi';
      $ruleAction->add($input);

      // * Add rule Modify version
      $input               = [];
      $input['sub_type']   = 'RuleDictionnarySoftware';
      $input['name']       = 'Set glpi version';
      $input['match']      = 'AND';
      $input['is_active']  = 1;
      $rules_id = $rule->add($input);

      $input               = [];
      $input['rules_id']   = $rules_id;
      $input['criteria']   = 'name';
      $input['condition']  = Rule::PATTERN_IS;
      $input['pattern']    = 'glpi0.85';
      $ruleCriteria->add($input);

      $input                  = [];
      $input['rules_id']      = $rules_id;
      $input['action_type']   = 'assign';
      $input['field']         = 'version';
      $input['value']         = '0.85';
      $ruleAction->add($input);

      $nbRulesAfter = countElementsInTable('glpi_rules');

      $this->assertEquals(($nbRules + 4), $nbRulesAfter);
   }


   /**
    * @test
    */
   public function AddSoftwareNormal() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"]                      = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'fusioninventory team',
                'NAME'      => 'fusioninventory',
                'VERSION'   => '0.85+1.0',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return        = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $manufacturer     = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'fusioninventory team']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference = [];
      $a_reference['software']["fusioninventory$$$$0.85+1.0$$$$".$manufacturers_id."$$$$0$$$$0"] =[
               'name'                  => 'fusioninventory',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '0.85+1.0',
               'is_template_item'  => 0,
               'is_deleted_item'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel',
               'comp_key_noos'         => "fusioninventory$$$$0.85+1.0$$$$".$manufacturers_id."$$$$0$$$$0",
               'comment'               => ''
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
   * This tests ignore the import of a computer, based on the software dictionnary
    * @test
    */
   public function AddSoftwareIgnore() {

      $a_software  = [];
      $a_reference = [];

      $_SESSION["plugin_fusioninventory_entity"] = 1;
      $_SESSION["glpiname"]                      = 'Plugin_FusionInventory';

      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi',
                'VERSION'   => '0.85'
               ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return        = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $a_reference['software'] = [];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareRename() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $manufacturer     = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'indepnet']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$".$manufacturers_id."$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '0.85',
               'is_template_item'  => 0,
               'is_deleted_item'   => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel',
               'comp_key_noos'         => 'glpi$$$$0.85$$$$'.$manufacturers_id.'$$$$0$$$$0',
               'comment'               => ''
            ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareRenameManufacturer() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet assoce',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85'
             ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return        = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $manufacturer     = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'indepnet']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$".$manufacturers_id."$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '0.85',
               'is_template_item'      => 0,
               'is_deleted_item'       => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => '',
               'comment'               => '',
               'comp_key_noos'         => "glpi$$$$0.85$$$$".$manufacturers_id."$$$$0$$$$0"
      ];

      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function AddSoftwareVersion() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'PUBLISHER' => 'indepnet',
                'NAME'      => 'glpi0.85',
                'VERSION'   => '0.85',
                'SYSTEM_CATEGORY' => 'devel'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $manufacturer     = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'indepnet']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference = [];
      $a_reference['software']["glpi$$$$0.85$$$$".$manufacturers_id."$$$$0$$$$0"] = [
               'name'                  => 'glpi',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '0.85',
               'is_template_item'      => 0,
               'is_deleted_item'       => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'devel',
               'comp_key_noos'         => 'glpi$$$$0.85$$$$'.$manufacturers_id.'$$$$0$$$$0',
               'comment'               => ''
            ];
      $this->assertEquals($a_reference, $a_return);

   }


   /**
    * @test
    */
   public function ProcessInstalldate() {

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_software = [];
      $a_software['SOFTWARES'][] = [
                'ARCH'             => 'i586',
                'FROM'             => 'registry',
                'GUID'             => 'Audacity_is1',
                'HELPLINK'         => 'http://audacity.sourceforge.net',
                'INSTALLDATE'      => '16/10/2013',
                'NAME'             => 'Audacity 2.0.4',
                'PUBLISHER'        => 'Audacity Team',
                'UNINSTALL_STRING' => '"C:\\Program Files\\Audacity\\unins000.exe\"',
                'URL_INFO_ABOUT'   => 'http://audacity.sourceforge.net',
                'VERSION'          => '2.0.4',
                'VERSION_MAJOR'    => '2',
                'VERSION_MINOR'    => '0',
                'SYSTEM_CATEGORY'  => 'application'
            ];
      $a_software['SOFTWARES'][] = [
                'ARCH'             => 'i586',
                'FROM'             => 'registry',
                'GUID'             => 'AutoItv3',
                'NAME'             => 'AutoIt v3.3.8.1',
                'PUBLISHER'        => 'AutoIt Team',
                'UNINSTALL_STRING' => 'C:\\Program Files\\AutoIt3\\Uninstall.exe',
                'URL_INFO_ABOUT'   => 'http://www.autoitscript.com/autoit3',
                'SYSTEM_CATEGORY'  => 'application'
          ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerSoftwareTransformation($a_software, 0);

      $manufacturer  = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'Audacity Team']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference = [];
      $a_reference['software']["audacity 2.0.4$$$$2.0.4$$$$".$manufacturers_id."$$$$0$$$$0"] = [
               'name'                  => 'Audacity 2.0.4',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '2.0.4',
               'is_template_item'      => 0,
               'is_deleted_item'       => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               'date_install'          => '2013-10-16',
               '_system_category'      => 'application',
               'comp_key_noos'         => 'audacity 2.0.4$$$$2.0.4$$$$'.$manufacturers_id.'$$$$0$$$$0',
               'comment'               => ''
            ];

      $manufacturer->getFromDBByCrit(['name' => 'AutoIt Team']);
      $manufacturers_id = $manufacturer->fields['id'];
      $this->assertGreaterThan(0, $manufacturers_id);

      $a_reference['software']["autoit v3.3.8.1$$$$$$$$".$manufacturers_id."$$$$0$$$$0"] = [
               'name'                  => 'AutoIt v3.3.8.1',
               'manufacturers_id'      => $manufacturers_id,
               'version'               => '',
               'is_template_item'      => 0,
               'is_deleted_item'       => 0,
               'entities_id'           => 0,
               'is_recursive'          => 0,
               'operatingsystems_id'   => 0,
               '_system_category'      => 'application',
               'comp_key_noos'         => 'autoit v3.3.8.1$$$$$$$$'.$manufacturers_id.'$$$$0$$$$0',
               'comment'               => ''
            ];
      $this->assertEquals($a_reference, $a_return);
   }
}
