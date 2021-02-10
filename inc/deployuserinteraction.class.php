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
 * Manage user interactions.
 * @since 9.2
 */
class PluginFusioninventoryDeployUserinteraction extends PluginFusioninventoryDeployPackageItem {

   public $shortname = 'userinteractions';
   public $json_name = 'userinteractions';

   //--------------- Events ---------------------------------------//

   //Audits are all been executed successfully, just before download
   const EVENT_BEFORE_DOWNLOAD    = 'before';
   //File download has been done, just before actions execution
   const EVENT_AFTER_DOWNLOAD  = 'after_download';
   //Actions have been executed, deployement is finished
   const EVENT_AFTER_ACTIONS   = 'after';
   //At least one downlod has failed
   const EVENT_DOWNLOAD_FAILURE = 'after_download_failure';
   //At least one action has failed
   const EVENT_ACTION_FAILURE   = 'after_failure';

   //--------------- Responses ---------------------------------------//

   //The agent notice that the job must continue
   const RESPONSE_CONTINUE        = 'continue';

   //The agent notice that the job must be postponed
   const RESPONSE_POSTPONE        = 'postpone';

   //The agent notice that the job must be canceled
   const RESPONSE_STOP            = 'stop';

   //The agent recieved a malformed or non existing event
   const RESPONSE_BAD_EVENT       = 'error_bad_event';

   //String to replace a \r\n, to avoid stripcslashes issue
   const RN_TRANSFORMATION        = "$#r$#n";


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {
         return _n('User interaction',
                   'User interactions', $nb, 'fusioninventory');
   }


   /**
    * Get events with name => description
    * @since 9.2
    * @return array
    */
   function getTypes() {
      return [self::EVENT_BEFORE_DOWNLOAD  => __("Before download", 'fusioninventory'),
              self::EVENT_AFTER_DOWNLOAD   => __("After download", 'fusioninventory'),
              self::EVENT_AFTER_ACTIONS    => __("After actions", 'fusioninventory'),
              self::EVENT_DOWNLOAD_FAILURE => __("On download failure", 'fusioninventory'),
              self::EVENT_ACTION_FAILURE   => __("On actions failure", 'fusioninventory')
             ];
   }


   /**
    * Get an event label by it's identifier
    * @since 9.2
    * @return array
    */
   function getLabelForAType($event) {
      $events = $this->getTypes();
      if (isset($events[$event])) {
         return $events[$event];
      } else {
         return false;
      }
   }


   /**
    * Display different fields relative the check selected
    *
    * @param array $config
    * @param array $request_data
    * @param string $rand unique element id used to identify/update an element
    * @param string $mode mode in use (create, edit...)
    * @return boolean
    */
   function displayAjaxValues($config, $request_data, $rand, $mode) {
      global $CFG_GLPI;

      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      if (isset($request_data['packages_id'])) {
         $pfDeployPackage->getFromDB($request_data['orders_id']);
      } else {
         $pfDeployPackage->getEmpty();
      }

      /*
       * Get type from request params
       */
      $type = null;
      if ($mode === self::CREATE) {
         $type = $request_data['value'];
         $config_data = null;
      } else {
         $type = $config['type'];
         $config_data = $config['data'];
      }

      $values = $this->getValues($type, $config_data, $mode);
      if ($values === false) {
         return false;
      }

      echo "<table class='package_item'>";
      echo "<tr>";
      echo "<th>{$values['name_label']}</th>";
      echo "<td><input type='text' name='name' id='userinteraction_name{$rand}' value=\"{$values['name_value']}\" /></td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>{$values['title_label']}</th>";
      echo "<td><input type='text' name='title' id='userinteraction_title{$rand}' value=\"{$values['title_value']}\" />";
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>{$values['description_label']}</th>";
      echo "<td><textarea name='text' id='userinteraction_description{$rand}' rows='5'>{$values['description_value']}</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<th>{$values['template_label']}</th>";
      echo "<td>";
      Dropdown::show('PluginFusioninventoryDeployUserinteractionTemplate',
                     ['value' => $values['template_value'], 'name' => 'template']);
      echo "</td>";
      echo "</tr>";

      $this->addOrSaveButton($pfDeployPackage, $mode);

      echo "</table>";
   }


   /**
    * Get fields for the check type requested
    *
    * @param string $type the type of check
    * @param array $data fields yet defined in edit mode
    * @param string $mode mode in use (create, edit...)
    *
    * @return string|false
    */
   function getValues($type, $data, $mode) {
      $values = [
         'name_value'          => "",
         'name_label'          => __('Interaction label', 'fusioninventory'),
         'name_type'           => "input",
         'title_label'         => __('Title').$this->getMandatoryMark(),
         'title_value'         => "",
         'title_type'          => "input",
         'description_label'   => __('Message'),
         'description_type'    => "text",
         'description_value'   => "",
         'template_label'
            => PluginFusioninventoryDeployUserinteractionTemplate::getTypeName(1)
               .$this->getMandatoryMark(),
         'template_value'      => "",
         'template_type'       => "dropdown",
      ];

      if ($mode === self::EDIT) {
         $values['name_value']        = isset($data['name'])?$data['name']:"";
         $values['title_value']       = isset($data['title'])?$data['title']:"";
         $values['description_value'] = isset($data['text'])?$data['text']:"";
         $values['template_value']    = isset($data['template'])?$data['template']:"";
      }

      //Trick to add \r\n in the description text area
      $values['description_value'] = str_replace(self::RN_TRANSFORMATION, "\r\n",
                                                 $values['description_value']);
      return $values;
   }


   /**
    * Display list of user interactions
    *
    * @global array $CFG_GLPI
    * @param object $package PluginFusioninventoryDeployPackage instance
    * @param array $data array converted of 'json' field in DB where stored checks
    * @param string $rand unique element id used to identify/update an element
    */
   function displayList(PluginFusioninventoryDeployPackage $package, $data, $rand) {
      global $CFG_GLPI;

      $interaction_types = $this->getTypes();
      $package_id        = $package->getID();
      $canedit           = $package->canUpdateContent();
      $i                 = 0;

      echo "<table class='tab_cadrehov package_item_list' id='table_userinteractions_$rand'>";
      foreach ($data['jobs']['userinteractions'] as $interaction) {
         echo Search::showNewLine(Search::HTML_OUTPUT, ($i%2));
         if ($canedit) {
            echo "<td class='control'>";
            Html::showCheckbox(['name' => 'userinteractions_entries['.$i.']']);
            echo "</td>";
         }

         //Get the audit full description (with type and return value)
         //to be displayed in the UI
         $text = $this->getInteractionDescription($interaction);
         echo "<td>";
         if ($canedit) {
            echo "<a class='edit'
                     onclick=\"edit_subtype('userinteraction', $package_id, $rand ,this)\">";
         }
         echo $text;
         if ($canedit) {
            echo "</a>";
         }

         echo "</td>";
         if ($canedit) {
            echo "<td class='rowhandler control' title='".__('drag', 'fusioninventory').
               "'><div class='drag row'></div></td>";
         }
         echo "</tr>";
         $i++;
      }
      if ($canedit) {
         echo "<tr><th>";
         echo Html::getCheckAllAsCheckbox("userinteractionsList$rand", mt_rand());
         echo "</th><th colspan='3' class='mark'></th></tr>";
      }
      echo "</table>";
      if ($canedit) {
         echo "&nbsp;&nbsp;<img src='".$CFG_GLPI["root_doc"]."/pics/arrow-left.png' alt='' />";
         echo "<input type='submit' name='delete' value=\"".
            __('Delete', 'fusioninventory')."\" class='submit' />";
      }
   }


   /**
   * Get of a short description of a user interaction
   *
   * @since 9.2
   * @param interaction an array representing an interaction
   * @return a short description
   */
   function getInteractionDescription($interaction) {
      $text = '';

      if (isset($interaction['label']) && !empty($interaction['label'])) {
         $text = $interaction['label'];
      } else if (isset($interaction['name'])) {
         $text.= $interaction['name'];
      }
      $text .= ' - '.$this->getLabelForAType($interaction['type']);

      if ($interaction['template']) {
         $text .= ' (';
         $text .= Dropdown::getDropdownName('glpi_plugin_fusioninventory_deployuserinteractiontemplates',
                                           $interaction['template']);
         $text.= ')';
      }

      return $text;
   }


   /**
    * Add a new item in checks of the package
    *
    * @param array $params list of fields with value of the check
    */
   function add_item($params) {
      if (!isset($params['text'])) {
         $params['text'] = "";
      }
      if (!isset($params['template'])) {
         $params['template'] = 0;
      }

      //prepare new check entry to insert in json
      $entry = [
         'name'        => $params['name'],
         'title'       => $params['title'],
         'text'        => $params['text'],
         'type'        => $params['userinteractionstype'],
         'template'    => $params['template']
      ];

      //Add to package defintion
      $this->addToPackage($params['id'], $entry, 'userinteractions');
   }


   /**
    * Save the item in checks
    *
    * @param array $params list of fields with value of the check
    */
   function save_item($params) {
      if (!isset($params['value'])) {
         $params['value'] = "";
      }
      if (!isset($params['name'])) {
         $params['name'] = "";
      }
      //prepare new check entry to insert in json
      $entry = [
         'name'        => $params['name'],
         'title'       => $params['title'],
         'text'        => $params['text'],
         'type'        => $params['userinteractionstype'],
         'template'    => $params['template']
      ];

      //update order
      $this->updateOrderJson($params['id'],
                             $this->prepareDataToSave($params, $entry));
   }


   function getTypesAlreadyInUse(PluginFusioninventoryDeployPackage $package) {
      $used_interactions = [];
      $json              = json_decode($package->fields['json'], true);

      if (isset($json['jobs'][$this->json_name])
         && !empty($json['jobs'][$this->json_name])) {
         foreach ($json['jobs'][$this->json_name] as $interaction) {
            if (!isset($used_interactions[$interaction['type']])) {
               $used_interactions[$interaction['type']] = $interaction['type'];
            }
         }
      }
      return $used_interactions;
   }


   /**
   * Get a log message depending on an agent response
   * @since 9.2
   *
   * @param behavior the behavior the agent must adopt for the job
   * @param type the type of event that triggered the user interaction
   * @param $event the button clicked by the user
   *         (or the what's happened in special cases, as defined in a template)
   * @param user userid the user who performed the interaction
   * @return string the message to be display in a taskjob log
   */
   public function getLogMessage($behavior, $type, $event, $user) {
      $message  = self::getTypeName(1);
      $message .= ': '.$this->getLabelForAType($type);
      $message .= '/';
      switch ($behavior) {
         case self::RESPONSE_STOP:
            $message .= sprintf(__('Job cancelled by the user %1$s',
                                   'fusioninventory'), $user);
            break;

         case self::RESPONSE_CONTINUE:
            $message .= sprintf(__('User %1$s agreed to continue the job',
                                   'fusioninventory'), $user);
            break;

         case self::RESPONSE_POSTPONE:
            $message .= sprintf(__('Job postponed by the user %1$s', 'fusioninventory'),
                                $user);
            break;

         case self::RESPONSE_BAD_EVENT:
            $message .= __('Bad event sent to the agent', 'fusioninventory');
            break;

      }
      $message.= ' ('.$this->getEventMessage($event).')';
      return $message;
   }


   function getEventMessage($event = '') {
      $message = __('%1$s button pressed');
      switch ($event) {
         case 'on_ok':
            return sprintf($message, __('OK'));

         case 'on_yes':
            return sprintf($message, __('Yes'));

         case 'on_async':
            return __('Alert displayed, no input required', 'fusioninventory');

         case 'on_no':
            return sprintf($message, __('No'));

         case 'on_retry':
            return sprintf($message, __('Retry', 'fusioninventory'));

         case 'on_cancel':
            return sprintf($message, __('Cancel'));

         case 'on_abort':
            return sprintf($message, __('Abort', 'fusioninventory'));

         case 'on_ignore':
            return sprintf($message, __('Ignore', 'fusioninventory'));

         case 'on_continue':
            return sprintf($message, __('Continue'));

         case 'on_timeout':
            return __('Alert duration exceeded', 'fusioninventory');

         case 'on_nouser':
            return __('No user connected', 'fusioninventory');

         case 'on_multiusers':
            return __('Multiple users connected', 'fusioninventory');
      }
   }
}
