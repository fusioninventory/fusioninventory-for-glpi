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
   @author    Walid Nouh
   @co-author David Durieux
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class DeployUserinteractionTemplateTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all Interactions
      $interaction = new PluginFusioninventoryDeployUserinteractionTemplate();
      $items = $interaction->find();
      foreach ($items as $item) {
         $interaction->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function testDefineTabs() {
      $expected = [
                   'PluginFusioninventoryDeployUserinteractionTemplate$1' => 'General',
                   'PluginFusioninventoryDeployUserinteractionTemplate$2' => 'Behaviors',
                   'Log$1' => 'Historical'
                  ];
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $this->assertEquals($expected, $template->defineTabs());
   }


   /**
    * @test
    */
   public function testGetTabNameForItem() {
      $expected = [  1 => 'General', 2 => 'Behaviors'];
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $this->assertEquals($expected, $template->getTabNameForItem($template));
   }


   /**
    * @test
    */
   public function testGetTypeName() {
      $this->assertEquals('User interaction templates',
                           PluginFusioninventoryDeployUserinteractionTemplate::getTypeName());
      $this->assertEquals('User interaction template',
                           PluginFusioninventoryDeployUserinteractionTemplate::getTypeName(1));
      $this->assertEquals('User interaction templates',
                           PluginFusioninventoryDeployUserinteractionTemplate::getTypeName(2));
   }


   /**
    * @test
    */
   public function testGetTypes() {
      $types = PluginFusioninventoryDeployUserinteractionTemplate::getTypes();
      $this->assertEquals($types,
                          [PluginFusioninventoryDeployUserinteractionTemplate::ALERT_WTS => __("Windows system alert (WTS)", 'fusioninventory')]);
   }


   /**
    * @test
    */
   public function testGetButtons() {
      $buttons  = PluginFusioninventoryDeployUserinteractionTemplate::getButtons(PluginFusioninventoryDeployUserinteractionTemplate::ALERT_WTS);
      $this->assertEquals(8, count($buttons));

      $buttons = PluginFusioninventoryDeployUserinteractionTemplate::getButtons('foo');
      $this->assertFalse($buttons);

      $buttons = PluginFusioninventoryDeployUserinteractionTemplate::getButtons();
      $this->assertFalse($buttons);

   }


   /**
    * @test
    */
   public function testAddJsonFieldsToArray() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $template->fields['json'] = '{"platform":"wts","timeout":4,"buttons":"ok","retry_after":4,"nb_max_retry":4,"on_timeout":"continue","on_nouser":"continue","on_multiusers":"cancel"}';
      $result = ['name' => 'foo'];
      $result = $template->addJsonFieldsToArray($result);

      $expected = ['name'          => 'foo',
                   'platform'      => 'wts',
                   'timeout'      => 4,
                   'buttons'       => 'ok',
                   'retry_after'   => 4,
                   'nb_max_retry'  => 4,
                   'on_timeout'    => 'continue',
                   'on_nouser'     => 'continue',
                   'on_multiusers' => 'cancel',
                   'wait'          => 'yes'];
      $this->assertEquals($expected, $result);

      $template->fields['json'] = '{"platform":"wts","timeout":4,"buttons":"ok_async","retry_after":4,"nb_max_retry":4,"on_timeout":"continue","on_nouser":"continue","on_multiusers":"cancel"}';
      $result = ['name' => 'foo'];
      $result = $template->addJsonFieldsToArray($result);

      $expected = ['name'          => 'foo',
                   'platform'      => 'wts',
                   'timeout'      => 4,
                   'buttons'       => 'ok',
                   'retry_after'   => 4,
                   'nb_max_retry'  => 4,
                   'on_timeout'    => 'continue',
                   'on_nouser'     => 'continue',
                   'on_multiusers' => 'cancel',
                   'wait'          => 'no'];
      $this->assertEquals($expected, $result);

   }


   /**
    * @test
    */
   public function testGetIcons() {
      $icons = PluginFusioninventoryDeployUserinteractionTemplate::getIcons(PluginFusioninventoryDeployUserinteractionTemplate::ALERT_WTS);
      $this->assertEquals(5, count($icons));
      $this->assertEquals($icons, [ PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_NONE     => __('None'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_WARNING  => __('Warning'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_INFO     => _n('Information', 'Informations', 1),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_ERROR    => __('Error'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_QUESTION => __('Question', 'fusioninventory')
                                   ]);

      $icons = PluginFusioninventoryDeployUserinteractionTemplate::getIcons('foo');
      $this->assertFalse($icons);

      $icons = PluginFusioninventoryDeployUserinteractionTemplate::getIcons();
      $this->assertEquals($icons, [ PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_NONE     => __('None'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_WARNING  => __('Warning'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_INFO     => _n('Information', 'Informations', 1),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_ERROR    => __('Error'),
                                    PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_QUESTION => __('Question', 'fusioninventory')
                                   ]);

   }


   /**
    * @test
    */
   public function testGetBehaviors() {
      $behaviors = PluginFusioninventoryDeployUserinteractionTemplate::getBehaviors();
      $expected  = [PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY => __('Continue job with no user interaction'),
                    PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_POSTPONE_DEPLOY => __('Retry job later', 'fusioninventory'),
                    PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_STOP_DEPLOY   => __('Cancel job')
                   ];
      $this->assertEquals($expected, $behaviors);
   }


   /**
    * @test
    */
   public function testAdd() {
      $interaction = new PluginFusioninventoryDeployUserinteractionTemplate();
      $tmp = ['name'         => 'test',
              'entities_id'  => 0,
              'is_recursive' => 0,
              'json'         => ''
             ];
      $this->assertNotNull($interaction->add($tmp));
      $interaction->getFromDB(1);
      $this->assertEquals('[]', $interaction->fields['json']);

      $tmp = ['name'         => 'test2',
              'entities_id'  => 0,
              'is_recursive' => 0,
              'platform'     => PluginFusioninventoryDeployUserinteractionTemplate::ALERT_WTS,
              'timeout'      => 4,
              'buttons'      => PluginFusioninventoryDeployUserinteractionTemplate::WTS_BUTTON_OK_SYNC,
              'icon'         => 'warning',
              'retry_after'  => 4,
              'nb_max_retry' => 4,
              'on_timeout'   => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY,
              'on_nouser'    => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY,
              'on_multiusers' => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_STOP_DEPLOY
             ];
      $this->assertNotNull($interaction->add($tmp));
      $expected = '{"platform":"win32","timeout":4,"buttons":"ok","icon":"warning","retry_after":4,"nb_max_retry":4,"on_timeout":"continue:continue","on_nouser":"continue:continue","on_multiusers":"stop:stop"}';
      $this->assertEquals($expected, $interaction->fields['json']);

   }


   /**
    * @test
    */
   public function testUpdate() {
      $interaction = new PluginFusioninventoryDeployUserinteractionTemplate();
      $interaction->getFromDBByCrit(['name' => 'test']);
      $tmp = [
         'id'   => $interaction->fields['id'],
         'name' => 'test_update',
         'json' => ''
      ];
      $this->assertTrue($interaction->update($tmp));
      $this->assertEquals('test_update', $interaction->fields['name']);

   }


   /**
    * @test
    */
   public function testSaveToJson() {
      $values = ['name'          => 'interaction',
                 'platform'      => PluginFusioninventoryDeployUserinteractionTemplate::ALERT_WTS,
                 'timeout'       => 4,
                 'buttons'       => PluginFusioninventoryDeployUserinteractionTemplate::WTS_BUTTON_OK_SYNC,
                 'icon'          => 'warning',
                 'retry_after'   => 4,
                 'nb_max_retry'  => 4,
                 'on_timeout'    => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY,
                 'on_nouser'     => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY,
                 'on_multiusers' => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_STOP_DEPLOY
                ];
      $interaction = new PluginFusioninventoryDeployUserinteractionTemplate();
      $result      = $interaction->saveToJson($values);
      $expected    = '{"platform":"win32","timeout":4,"buttons":"ok","icon":"warning","retry_after":4,"nb_max_retry":4,"on_timeout":"continue:continue","on_nouser":"continue:continue","on_multiusers":"stop:stop"}';
      $this->assertEquals($expected, $result);

      $result      = $interaction->saveToJson([]);
      $this->assertEquals($result, "[]");

   }


   /**
    * @test
    */
   function testGestMainFormFields() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $expected = ['platform', 'timeout', 'buttons', 'icon',
                   'retry_after', 'nb_max_retry'];
      $this->assertEquals($expected, $template->getMainFormFields());
   }


   /**
    * @test
    */
   function testGetBehaviorsFields() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $expected = ['on_timeout', 'on_nouser', 'on_multiusers', 'on_ok', 'on_no',
                   'on_yes', 'on_cancel', 'on_abort', 'on_retry', 'on_tryagain',
                   'on_ignore', 'on_continue', 'on_async'];
      $this->assertEquals($expected, $template->getBehaviorsFields());
   }


   /**
    * @test
    */
   function testGetJsonFields() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $expected = ['platform', 'timeout', 'buttons', 'icon',
                   'retry_after', 'nb_max_retry',
                   'on_timeout', 'on_nouser', 'on_multiusers', 'on_ok', 'on_no',
                   'on_yes', 'on_cancel', 'on_abort', 'on_retry', 'on_tryagain',
                   'on_ignore', 'on_continue', 'on_async'];
      $this->assertEquals($expected, $template->getJsonFields());
   }


   /**
    * @test
    */
   public function testInitializeJsonFields() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $this->assertEquals(19, count($template->initializeJsonFields([])));
   }


   /**
    * @test
    */
   public function testGetEvents() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $this->assertEquals(12, count($template->getEvents()));
   }


   /**
    * @test
    */
   public function testGetBehaviorsToDisplay() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers', 'on_ok'],
                           $template->getBehaviorsToDisplay('ok'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers', 'on_ok'],
                           $template->getBehaviorsToDisplay('ok_async'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                            'on_ok', 'on_cancel'],
                           $template->getBehaviorsToDisplay('okcancel'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                            'on_yes', 'on_no'],
                           $template->getBehaviorsToDisplay('yesno'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                            'on_yes', 'on_no', 'on_cancel'],
                           $template->getBehaviorsToDisplay('yesnocancel'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                            'on_abort', 'on_retry', 'on_ignore'],
                           $template->getBehaviorsToDisplay('abortretryignore'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                           'on_retry', 'on_cancel'],
                           $template->getBehaviorsToDisplay('retrycancel'));

      $this->assertEquals(['on_timeout', 'on_nouser', 'on_multiusers',
                           'on_tryagain', 'on_cancel', 'on_continue'],
                           $template->getBehaviorsToDisplay('canceltrycontinue'));

   }


   /**
    * @test
    */
   public function testPrepareInputForAdd() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $input = ['name'       => 'foo',
                'button'     => PluginFusioninventoryDeployUserinteractionTemplate::WTS_BUTTON_CANCEL_TRY_CONTINUE,
                'icon'       => PluginFusioninventoryDeployUserinteractionTemplate::WTS_ICON_QUESTION,
                'on_timeout' => PluginFusioninventoryDeployUserinteractionTemplate::BEHAVIOR_CONTINUE_DEPLOY
               ];
      $expected = '{"icon":"question","on_timeout":"continue:continue"}';
      $modified = $template->prepareInputForAdd($input);
      $this->assertEquals($expected, $modified['json']);
   }


   /**
    * @test
    */
   public function testGetDefaultBehaviorForAButton() {
      $template = new PluginFusioninventoryDeployUserinteractionTemplate();
      $this->assertEquals('continue:continue', $template->getDefaultBehaviorForAButton('on_ok'));
      $this->assertEquals('continue:continue', $template->getDefaultBehaviorForAButton('on_yes'));
      $this->assertEquals('continue:continue', $template->getDefaultBehaviorForAButton('on_multiusers'));
      $this->assertEquals('continue:continue', $template->getDefaultBehaviorForAButton('on_timeout'));

      $this->assertEquals('stop:stop', $template->getDefaultBehaviorForAButton('on_no'));
      $this->assertEquals('stop:stop', $template->getDefaultBehaviorForAButton('on_cancel'));
      $this->assertEquals('stop:stop', $template->getDefaultBehaviorForAButton('on_abort'));

      $this->assertEquals('stop:postpone', $template->getDefaultBehaviorForAButton('on_retry'));
      $this->assertEquals('stop:postpone', $template->getDefaultBehaviorForAButton('on_ignore'));
   }
}
