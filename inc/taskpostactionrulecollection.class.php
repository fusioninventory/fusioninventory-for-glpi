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
}

?>
