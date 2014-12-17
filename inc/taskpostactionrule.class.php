<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryTaskpostactionRule extends Rule {

   // From Rule
   static $rightname = "plugin_fusioninventory_taskpostactionrule";

   public $can_sort = TRUE;
   public $specific_parameters = FALSE;


   function getTitle() {
      return __('Tasks post actions', 'fusioninventory');
   }

   static function getTypeName($nb=0) {
      return __('Tasks post actions', 'fusioninventory');
   }

   function getCriterias() {

      $criteria = array ();

      $criteria['task']['field']             = 'plugin_fusioninventory_tasks_id';
      $criteria['task']['name']              = __('Task', 'fusioninventory');
      $criteria['task']['table']             = 'glpi_plugin_fusioninventory_tasks';
      $criteria['task']['type']              = 'dropdown';

      $criteria['agent']['field']             = 'plugin_fusioninventory_agents_id';
      $criteria['agent']['name']              = __('Agent', 'fusioninventory');
      $criteria['agent']['table']             = 'glpi_plugin_fusioninventory_agents';
      $criteria['agent']['type']              = 'dropdown';

      $criteria['method']['field']           = 'method';
      $criteria['method']['name']            = __('Module method', 'fusioninventory');

      $criteria['target_type']['field']      = 'target_type';
      $criteria['target_type']['name']       = __('Target Type', 'fusioninventory');

      $criteria['target']['field']           = 'target';
      $criteria['target']['name']            = __('Target Item', 'fusioninventory');

      $criteria['actor_type']['field']       = 'actor_type';
      $criteria['actor_type']['name']        = __('Actor Type', 'fusioninventory');

      $criteria['actor']['field']            = 'actor';
      $criteria['actor']['name']             = __('Actor Item', 'fusioninventory');

      $criteria['state']['field']           = 'state';
      $criteria['state']['name']            = __('Job result', 'fusioninventory');
      $criteria['state']['allow_condition'] = array(Rule::PATTERN_IS, Rule::PATTERN_IS_NOT);


      return $criteria;
   }


   /**
    * @since version 0.84
    *
    * @see Rule::displayCriteriaSelectPattern()
   **/
   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test=false) {
      global $PLUGIN_HOOKS;

      if (!isset($criteria['field'])
          || !in_array($condition, array(self::PATTERN_IS,        self::PATTERN_IS_NOT,
                                         self::PATTERN_NOT_UNDER, self::PATTERN_UNDER))) {
         return false;
      }

      switch ($criteria['field']) {
         case 'method':
            $modules_methods = PluginFusioninventoryStaticmisc::getModulesMethods();
            Dropdown::showFromArray($name, $modules_methods, array('value' => $value));
            
            return true;
            break;

         case 'target_type':
            $tj = new PluginFusioninventoryTaskjob;
            $modules_methods = PluginFusioninventoryStaticmisc::getModulesMethods();
            $targets = array();
            foreach ($modules_methods as $method_key => $method_label) {
               $targets_method = $tj->getTypesForModule($method_key, 'targets');
               $targets = array_merge($targets, $targets_method);
            }
           
            Dropdown::showFromArray($name, $targets, array('value' => $value));

            return true;
            break;

         case 'actor_type':
            $tj = new PluginFusioninventoryTaskjob;
            $modules_methods = PluginFusioninventoryStaticmisc::getModulesMethods();
            $actors = array();
            foreach ($modules_methods as $method_key => $method_label) {
               $actors_method = $tj->getTypesForModule($method_key, 'actors');
               $actors = array_merge($actors, $actors_method);
            }
           
            Dropdown::showFromArray($name, $actors, array('value' => $value));

            return true;
            break;

         case 'state':
            $states = PluginFusioninventoryTaskjobstate::getStateNames();
            Dropdown::showFromArray($name, $states, array('value' => $value));

            return true;
            break;
      }

      return false;
   }


   function getActions() {
      $actions                             = array();

      $actions['states_id']['name']           = __('Status');
      $actions['states_id']['type']           = 'dropdown';
      $actions['states_id']['table']          = 'glpi_states';

      $actions['users_id_tech']['name']       = __('Technician in charge of the hardware');
      $actions['users_id_tech']['type']       = 'dropdown_users';

      $actions['groups_id_tech']['name']      = __('Group in charge of the hardware');
      $actions['groups_id_tech']['type']      = 'dropdown';
      $actions['groups_id_tech']['table']     = 'glpi_groups';
      $actions['groups_id_tech']['condition'] = '`is_assign`';

      $actions['comment']['name']             = __('Comment');

      return $actions;
   }


   function executeActions($output, $params) {
      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            switch ($action->fields["action_type"]) {
               case "assign" :
                  $output[$action->fields["field"]] = $action->fields["value"];
                  break;

               case "regex_result" :
                  
                  break;
            }
         }
      }
      return $output;
   }
}

?>
