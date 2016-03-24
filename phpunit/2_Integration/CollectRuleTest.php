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

class CollectRuleTest extends RestoreDatabase_TestCase {

   var $rules_id = 0;
   var $ruleactions_id = 0;

   /**
    * @test
    */
   public function prepareDB() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $rule = new Rule();
      $ruleCriteria = new RuleCriteria();
      $ruleAction = new RuleAction();

      // * computer model assign
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'computer model',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/latitude(.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'assign',
          'field'       => 'computermodels_id',
          'value'       => 1
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * computer model regex
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'computer model 2',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/longitude(.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'regex_result',
          'field'       => 'computermodels_id',
          'value'       => '#0'
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * user regex
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'user',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/user (.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'regex_result',
          'field'       => 'user',
          'value'       => '#0'
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * softwareversion regex
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'softwareversion 3.0',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/version (.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'regex_result',
          'field'       => 'softwareversion',
          'value'       => '#0'
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * otherserial regex
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'otherserial',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/other (.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'regex_result',
          'field'       => 'otherserial',
          'value'       => '#0'
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * otherserial regex
      $input = array(
          'entities_id' => 0,
          'sub_type' => 'PluginFusioninventoryCollectRule',
          'name' => 'otherserial assign',
          'match' => 'AND'
      );
      $rules_id = $rule->add($input);

      $input = array(
          'rules_id'  => $rules_id,
          'criteria'  => 'filename',
          'condition' => 6,
          'pattern'   => "/serial (.*)/"
      );
      $ruleCriteria->add($input);

      $input = array(
          'rules_id'    => $rules_id,
          'action_type' => 'assign',
          'field'       => 'otherserial',
          'value'       => 'ttuujj'
      );
      $this->ruleactions_id = $ruleAction->add($input);


      // * create items
      $computerModel = new ComputerModel();
      $input = array(
          'name' => '6430u'
      );
      $computerModel->add($input);

   }



   /**
    * @test
    */
   public function getComputerModelAssign() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'latitude 6430u',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals(1, $res_rule['computermodels_id']);
   }



   /**
    * @test
    */
   public function getComputerModelRegexCreate() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'longitude 6431u',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals(2, $res_rule['computermodels_id']);
   }



   /**
    * @test
    */
   public function getComputerModelRegex() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'longitude 6430u',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals(1, $res_rule['computermodels_id']);
   }



   /**
    * @test
    */
   public function getUserRegex() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'user david',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals('david', $res_rule['user']);
   }



   /**
    * @test
    */
   public function getSoftwareVersionRegex() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'version 3.2.0',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals('3.2.0', $res_rule['softwareversion']);
   }



   /**
    * @test
    */
   public function getOtherserialRegex() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'other xxyyzz',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals('xxyyzz', $res_rule['otherserial']);
   }



   /**
    * @test
    */
   public function getOtherserialAssign() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      $res_rule = $pfCollectRuleCollection->processAllRules(
                    array(
                        "filename"  => 'serial clic',
                        "filepath"  => '/tmp',
                        "size"      => 1000
                     )
                  );

      $this->assertEquals('ttuujj', $res_rule['otherserial']);
   }

}
?>
