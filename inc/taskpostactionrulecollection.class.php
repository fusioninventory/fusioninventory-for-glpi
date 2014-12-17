<?php

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryTaskpostactionRuleCollection extends RuleCollection {

   static $rightname = "plugin_fusioninventory_taskpostactionrule";

   // From RuleCollection
   public $stop_on_first_match=FALSE;

   function getTitle() {
      return __('Tasks post actions', 'fusioninventory');
   }

   function prepareInputDataForProcess($input, $params) {
      return $input;
   }

   static function launchProcess($input, $pfTaskjobstate) {
      if ($input['itemtype'] != 'PluginFusioninventoryDeployPackage') {
         return false;
      }

      $pfTaskjob = new PluginFusioninventoryTaskjob();
      $pfTaskjob->getFromDB($pfTaskjobstate->fields['plugin_fusioninventory_taskjobs_id']);

      $input['plugin_fusioninventory_agents_id'] = $pfTaskjobstate->fields['plugin_fusioninventory_agents_id'];
      $input['plugin_fusioninventory_tasks_id'] = $pfTaskjob->fields['plugin_fusioninventory_tasks_id'];
      $input['method'] = $pfTaskjob->fields['method'];

      Toolbox::logDebug($input);

      $rulepostaction_col = new self;
      $output = $rulepostaction_col->processAllRules($input, array());
      
      Toolbox::logDebug($output);

      $agent = new PluginFusioninventoryAgent;
      $agent->getFromDB($input['plugin_fusioninventory_agents_id']);

      $computer = new Computer;
      $update = array_merge(array('id' => $agent->fields['computers_id']), $output);
      return $computer->update($update);
   }
}

?>
