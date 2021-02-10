<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the actions in package for deploy system.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage user interactions templates.
 * @since 9.2
 */
class PluginFusioninventoryDeployUserinteractionTemplate extends CommonDropdown {

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_userinteractiontemplate';

   const ALERT_WTS                = 'win32'; //Alerts for win32 platform (WTS API)

   //Behaviors (to sent to the agent) :
   //in two parts :
   //- left part is the instruction for the agent
   //- right part is the code that the agent returns to the server

   //Continue a software deployment
   const BEHAVIOR_CONTINUE_DEPLOY = 'continue:continue';

   //Cancel a software deployment
   const BEHAVIOR_STOP_DEPLOY     = 'stop:stop';

   //Postpone a software deployment
   const BEHAVIOR_POSTPONE_DEPLOY = 'stop:postpone';

   //Available buttons for Windows WTS API
   const WTS_BUTTON_OK_SYNC             = 'ok';
   const WTS_BUTTON_OK_ASYNC            = 'ok_async';
   const WTS_BUTTON_OK_CANCEL           = 'okcancel';
   const WTS_BUTTON_YES_NO              = 'yesno';
   const WTS_BUTTON_ABORT_RETRY_IGNORE  = 'abortretryignore';
   const WTS_BUTTON_RETRY_CANCEL        = 'retrycancel';
   const WTS_BUTTON_YES_NO_CANCEL       = 'yesnocancel';
   const WTS_BUTTON_CANCEL_TRY_CONTINUE = 'canceltrycontinue';

   //Icons to be displayed
   const WTS_ICON_NONE                = 'none';
   const WTS_ICON_WARNING             = 'warn';
   const WTS_ICON_QUESTION            = 'question';
   const WTS_ICON_INFO                = 'info';
   const WTS_ICON_ERROR               = 'error';


   /**
    * @see CommonGLPI::defineTabs()
   **/
   function defineTabs($options = []) {

      $ong = [];
      $this->addStandardTab(__CLASS__, $ong, $options)
         ->addStandardTab('Log', $ong, $options);

      return $ong;
   }


   /**
    * @see CommonGLPI::getTabNameForItem()
   **/
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      $tabs[1] = __('General');
      $tabs[2] = _n('Behavior', 'Behaviors', 2, 'fusioninventory');
      return $tabs;
   }


   /**
    * @param $item         CommonGLPI object
    * @param $tabnum       (default 1)
    * @param $withtemplate (default 0)
   **/
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      global $CFG_GLPI;

      if ($item->getType() == __CLASS__) {
         switch ($tabnum) {
            case 1 :
               $item->showForm($item->fields['id']);
               break;

            case 2 :
               $item->showBehaviors($item->fields['id']);
               break;
         }
      }
      return true;
   }


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
         return _n('User interaction template',
                   'User interaction templates', $nb, 'fusioninventory');
   }


   /**
    * Get list of supported interaction methods
    *
    * @since 9.2
    * @return array
    */
   static function getTypes() {
      return [self::ALERT_WTS
               => __("Windows system alert (WTS)", 'fusioninventory')];
   }


   /**
    * Display a dropdown with the list of alert types
    *
    * @since 9.2
    * @param type the type of alert (if one already selected)
    * @return rand
    */
   function dropdownTypes($type = self::ALERT_WTS) {
      return Dropdown::showFromArray('platform', self::getTypes(),
                                     ['value' => $type]);
   }


   /**
    * Get available buttons for alerts, by interaction type
    *
    * @since 9.2
    * @param interaction_type the type of interaction
    * @return array
    */
   static function getButtons($interaction_type = '') {
      $interactions = [
         self::ALERT_WTS => [
            self::WTS_BUTTON_OK_SYNC             => __('OK', 'fusioninventory'),
            self::WTS_BUTTON_OK_ASYNC            => __('OK (asynchronous)', 'fusioninventory'),
            self::WTS_BUTTON_OK_CANCEL           => __('OK - Cancel', 'fusioninventory'),
            self::WTS_BUTTON_YES_NO              => __('Yes - No', 'fusioninventory'),
            self::WTS_BUTTON_ABORT_RETRY_IGNORE  => __('OK - Abort - Retry', 'fusioninventory'),
            self::WTS_BUTTON_RETRY_CANCEL        => __('Retry - Cancel', 'fusioninventory'),
            self::WTS_BUTTON_ABORT_RETRY_IGNORE  => __('Abort - Retry - Ignore', 'fusioninventory'),
            self::WTS_BUTTON_CANCEL_TRY_CONTINUE => __('Cancel - Try - Continue', 'fusioninventory'),
            self::WTS_BUTTON_YES_NO_CANCEL       => __('Yes - No - Cancel', 'fusioninventory')
         ]
      ];
      if (isset($interactions[$interaction_type])) {
         return $interactions[$interaction_type];
      } else {
         return false;
      }
   }


   /**
    * Display a dropdown with the list of buttons available
    *
    * @since 9.2
    * @param type the type of button (if one already selected)
    * @return rand
    */
   public function dropdownButtons($button = self::WTS_BUTTON_OK_SYNC) {
      return Dropdown::showFromArray('buttons',
                                     self::getButtons(self::ALERT_WTS),
                                     ['value' => $button]);
   }


   /**
    * Get available icons for alerts, by interaction type
    *
    * @since 9.2
    * @param interaction_type the type of interaction
    * @return array
    */
   static function getIcons($interaction_type = self::ALERT_WTS) {
      $icons = [
         self::ALERT_WTS => [
            self::WTS_ICON_NONE     => __('None'),
            self::WTS_ICON_WARNING  => __('Warning'),
            self::WTS_ICON_INFO     => _n('Information', 'Informations', 1),
            self::WTS_ICON_ERROR    => __('Error'),
            self::WTS_ICON_QUESTION => __('Question', 'fusioninventory')
         ]
      ];
      if (isset($icons[$interaction_type])) {
         return $icons[$interaction_type];
      } else {
         return false;
      }
   }


   /**
    * Display a dropdown with the list of buttons available
    *
    * @since 9.2
    * @param type the type of button (if one already selected)
    * @return rand
    */
   function dropdownIcons($icon = self::WTS_ICON_NONE) {
      return Dropdown::showFromArray('icon',
                                     self::getIcons(),
                                     ['value' => $icon]);
   }


   /**
    * Get available behaviors in case of user interactions
    *
    * @since 9.2
    * @return array
    */
   static function getBehaviors() {
      return [self::BEHAVIOR_CONTINUE_DEPLOY => __('Continue job with no user interaction', 'fusioninventory'),
              self::BEHAVIOR_POSTPONE_DEPLOY => __('Retry job later', 'fusioninventory'),
              self::BEHAVIOR_STOP_DEPLOY     => __('Cancel job', 'fusioninventory')
             ];
   }


   /**
    * Display a dropdown with the list of available behaviors
    *
    * @since 9.2
    * @param type the type of bahaviors (if one already selected)
    * @return rand
    */
   function dropdownBehaviors($name, $behavior = self::BEHAVIOR_CONTINUE_DEPLOY) {
      return Dropdown::showFromArray($name,
                                     self::getBehaviors(),
                                     ['value' => $behavior]);
   }


   /**
   * Get the fields to be encoded in json
   * @since 9.2
   * @return an array of field names
   */
   function getJsonFields() {
      return  array_merge($this->getMainFormFields(),
                          $this->getBehaviorsFields());

   }


   /**
   * Get the fields to be encoded in json
   * @since 9.2
   * @return an array of field names
   */
   function getMainFormFields() {
      return  ['platform', 'timeout', 'buttons', 'icon',
               'retry_after', 'nb_max_retry'];

   }


   /**
   * Get the fields to be encoded in json
   * @since 9.2
   * @return an array of field names
   */
   function getBehaviorsFields() {
      return  ['on_timeout', 'on_nouser', 'on_multiusers', 'on_ok', 'on_no',
               'on_yes', 'on_cancel', 'on_abort', 'on_retry', 'on_tryagain',
               'on_ignore', 'on_continue', 'on_async'];

   }


   /**
   * Initialize json fields
   * @since 9.2
   *
   * @return an array of field names
   */
   function initializeJsonFields($json_fields) {
      foreach ($this->getJsonFields() as $field) {
         if (!isset($json_fields[$field])) {
            $json_fields[$field] = $this->getDefaultBehaviorForAButton($field);
         }
      }
      return $json_fields;
   }


   /**
   * Save form data as a json encoded array
   * @since 9.2
   * @param params form parameters
   * @return json encoded array
   */
   function saveToJson($params = []) {
      $result = [];
      foreach ($this->getJsonFields() as $field) {
         if (isset($params[$field])) {
            $result[$field] = $params[$field];
         }
      }
      return json_encode($result);
   }


   /**
   * Add the json template fields to package
   *
   * @since 9.2
   * @param the input array
   * @param array now containing input data + data from the template
   */
   function addJsonFieldsToArray($params = []) {
      $fields = json_decode($this->fields['json'], true);
      foreach ($this->getJsonFields() as $field) {
         if (isset($fields[$field])) {
            $params[$field] = $fields[$field];
         }
      }
      //If we deal with an asynchronous OK, then wait must be set to 0
      if ($params['buttons'] == self::WTS_BUTTON_OK_ASYNC) {
         $params['buttons'] = self::WTS_BUTTON_OK_SYNC;
         $params['wait']    = 'no';
      } else {
         //Otherwise wait is 1
         $params['wait'] = 'yes';
      }
      return $params;
   }


   /**
   * Display an interaction template form
   * @since 9.2
   * @param $id id of a template to edit
   * @param options POST form options
   */
   function showForm($ID, $options = []) {
      $this->initForm($ID);
      $this->showFormHeader();

      $json_data = json_decode($this->fields['json'], true);
      $json_data = $this->initializeJsonFields($json_data);

      echo "<tr class='tab_bg_1'>";

      foreach ($this->getBehaviorsFields() as $field) {
         echo Html::hidden($field, ['value' => $json_data[$field]]);
      }

      $rand    = mt_rand();
      $tplmark = $this->getAutofillMark('name', $options);

      //TRANS: %1$s is a string, %2$s a second one without spaces between them : to change for RTL
      echo "<td><label for='textfield_name$rand'>".sprintf(__('%1$s%2$s'), __('Name'), $tplmark) .
           "</label></td>";
      echo "<td>";
      $objectName = autoName($this->fields["name"], "name",
                             (isset($options['withtemplate']) && ( $options['withtemplate']== 2)),
                             $this->getType(), $this->fields["entities_id"]);
      Html::autocompletionTextField($this, 'name', [ 'value'     => $objectName,
                                                     'rand'      => $rand
                                                   ]);
      echo "</td>";

      echo "<td>".__('Interaction format', 'fusioninventory')."</td>";
      echo "<td>";
      $this->dropdownTypes($json_data['platform']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";

      echo "<td>".__('Interaction type', 'fusioninventory')."</td>";
      echo "<td>";
      $this->dropdownButtons($json_data['buttons']);
      echo "</td>";

      echo "<td>".__('Alert icon', 'fusioninventory')."</td>";
      echo "<td>";
      $this->dropdownIcons($json_data['icon']);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Retry job after', 'fusioninventory')."</td>";
      echo "<td>";
      $this->dropdownRetry($json_data['retry_after']);
      echo "</td>";

      echo "<td>".__('Maximum number of retry allowed', 'fusioninventory')."</td>";
      echo "<td>";
      Dropdown::showNumber('nb_max_retry',
                           ['value' => $json_data['nb_max_retry'],
                            'min'   => 1,
                            'max'   => 20,
                            'step'  => 1
                           ]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Alert display timeout', 'fusioninventory')."</td>";
      echo "<td>";
      $this->dropdownTimeout($json_data['timeout']);
      echo "</td>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      $this->showFormButtons();

      return true;

   }


   /**
    * Dropdown for frequency (interval between 2 actions)
    *
    * @param $name   select name
    * @param $value  default value (default 0)
   **/
   function dropdownRetry($value = 0) {

      $tab[0] = __('Never');

      $tab[MINUTE_TIMESTAMP]   = sprintf(_n('%d minute', '%d minutes', 1), 1);
      $tab[2*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 2), 2);
      $tab[3*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 3), 3);
      $tab[4*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 4), 4);

      // Minutes
      for ($i=5; $i<60; $i+=5) {
         $tab[$i*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', $i), $i);
      }

      // Heures
      for ($i=1; $i<24; $i++) {
         $tab[$i*HOUR_TIMESTAMP] = sprintf(_n('%d hour', '%d hours', $i), $i);
      }

      // Jours
      $tab[DAY_TIMESTAMP] = __('Each day');
      for ($i=2; $i<7; $i++) {
         $tab[$i*DAY_TIMESTAMP] = sprintf(_n('%d day', '%d days', $i), $i);
      }

      $tab[WEEK_TIMESTAMP]  = __('Each week');
      $tab[MONTH_TIMESTAMP] = __('Each month');

      Dropdown::showFromArray('retry_after', $tab, ['value' => $value]);
   }


   /**
    * Dropdown for frequency (interval between 2 actions)
    *
    * @param $name   select name
    * @param $value  default value (default 0)
   **/
   function dropdownTimeout($value = 0) {

      $tab[0] = __('Never');

      // Minutes
      for ($i=30; $i<60; $i+=5) {
         $tab[$i] = sprintf(_n('%d second', '%d seconds', $i), $i);
      }

      $tab[MINUTE_TIMESTAMP]   = sprintf(_n('%d minute', '%d minutes', 1), 1);
      $tab[2*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 2), 2);
      $tab[3*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 3), 3);
      $tab[4*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', 4), 4);

      // Minutes
      for ($i=5; $i<60; $i+=5) {
         $tab[$i*MINUTE_TIMESTAMP] = sprintf(_n('%d minute', '%d minutes', $i), $i);
      }

      // Hours
      for ($i=1; $i<13; $i++) {
         $tab[$i*HOUR_TIMESTAMP] = sprintf(_n('%d hour', '%d hours', $i), $i);
      }

      Dropdown::showFromArray('timeout', $tab, ['value' => $value]);
   }


   /**
   * Get all events leading to an action on a task
   *
   * @since 9.2
   * @return array an array of event => event label
   */
   public function getEvents() {
      return ['on_ok'       => __('Button ok', 'fusioninventory'),
              'on_yes'      => __('Button yes', 'fusioninventory'),
              'on_continue' => __('Button continue', 'fusioninventory'),
              'on_retry'    => __('Button retry', 'fusioninventory'),
              'on_tryagain' => __('Button try', 'fusioninventory'),
              'on_no'       => __('Button no', 'fusioninventory'),
              'on_cancel'   => __('Button cancel', 'fusioninventory'),
              'on_abort'    => __('Button abort', 'fusioninventory'),
              'on_ignore'   => __('Button ignore', 'fusioninventory'),
              'on_nouser'   => __('No active session', 'fusioninventory'),
              'on_timeout'  => __('Alert timeout exceeded', 'fusioninventory'),
              'on_multiusers' => __('Several active sessions', 'fusioninventory')
             ];

   }


   /**
   * Get the behaviors to define for an agent to correctly handle the interaction
   *
   * @since 9.2
   * @param $param the button selected in the interaction template form
   * @return array an array of needed interaction behaviors
   */
   public function getBehaviorsToDisplay($button) {
      $display = ['on_timeout', 'on_nouser', 'on_multiusers'];
      switch ($button) {
         case self::WTS_BUTTON_OK_SYNC:
         case self::WTS_BUTTON_OK_ASYNC:
            $display[] = 'on_ok';
            break;

         case self::WTS_BUTTON_YES_NO:
            $display[] = 'on_yes';
            $display[] = 'on_no';
            break;

         case self::WTS_BUTTON_YES_NO_CANCEL:
            $display[] = 'on_yes';
            $display[] = 'on_no';
            $display[] = 'on_cancel';
            break;

         case self::WTS_BUTTON_OK_CANCEL:
            $display[] = 'on_ok';
            $display[] = 'on_cancel';
            break;

         case self::WTS_BUTTON_ABORT_RETRY_IGNORE:
            $display[] = 'on_abort';
            $display[] = 'on_retry';
            $display[] = 'on_ignore';
            break;

         case self::WTS_BUTTON_RETRY_CANCEL:
            $display[] = 'on_retry';
            $display[] = 'on_cancel';
            break;

         case self::WTS_BUTTON_CANCEL_TRY_CONTINUE:
            $display[] = 'on_tryagain';
            $display[] = 'on_cancel';
            $display[] = 'on_continue';
            break;

      }
      return $display;
   }


   /**
   * Get the default behavior for a button
   * @since 9.2
   * @param string $button the button for which the default behavior is request
   * @return string the behavior
   */
   public function getDefaultBehaviorForAButton($button) {
      $behavior = '';
      switch ($button) {
         case 'on_yes':
         case 'on_ok':
         case 'on_multiusers':
         case 'on_timeout':
         case 'on_nouser':
            $behavior = self::BEHAVIOR_CONTINUE_DEPLOY;
            break;

         case 'on_no':
         case 'on_cancel':
         case 'on_abort':
            $behavior = self::BEHAVIOR_STOP_DEPLOY;
            break;

         case 'on_retry':
         case 'on_ignore':
         case 'on_tryagain':
            $behavior = self::BEHAVIOR_POSTPONE_DEPLOY;
            break;

      }
      return $behavior;
   }


   /**
   * Show behaviors form
   *
   * @since 9.2
   * @param ID the template's ID
   */
   public function showBehaviors($ID) {

      $json_data = json_decode($this->fields['json'], true);
      $json_data = $this->initializeJsonFields($json_data);

      $this->initForm($ID);
      $this->showFormHeader();

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>".__('Behaviors', 'fusioninventory')."</th>";
      echo "</tr>";

      foreach ($this->getMainFormFields() as $field) {
         echo Html::hidden($field, ['value' => $json_data[$field]]);
      }

      foreach ($this->getEvents() as $event => $label) {
         if (in_array($event,
                      $this->getBehaviorsToDisplay($json_data['buttons']))) {
            echo "<tr class='tab_bg_1'>";

            echo "<td>$label</td>";
            echo "<td>";
            if (empty($json_data[$event])) {
               $value = $this->getDefaultBehaviorForAButton($event);
            } else {
               $value = $json_data[$event];
            }
            $this->dropdownBehaviors($event, $value);
            echo "</td>";
            echo "</tr>";
         } else {
            echo Html::hidden($event, $json_data[$event]);
         }

      }

      $this->showFormButtons();

      return true;
   }


   public function prepareInputForAdd($input) {
      //Save params as a json array, ready to be saved in db
      $input['json'] = $this->saveToJson($input);
      return $input;
   }


   public function prepareInputForUpdate($input) {
      return $this->prepareInputForAdd($input);
   }


   /**
   * Get temlate values as an array
   * @since 9.2
   * @return array the template values as an array
   */
   public function getValues() {
      return json_decode($this->fields['json'], true);
   }
}
