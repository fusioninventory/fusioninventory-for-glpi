<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/*
 * Display the test rule page.
 */
if (!defined('GLPI_ROOT')) {
   include ("../../../inc/includes.php");
}

Session::checkLoginUser();
$sub_type = 0;
if (isset($_POST["sub_type"])) {
   $sub_type = $_POST["sub_type"];
} else if (isset($_GET["sub_type"])) {
   $sub_type = $_GET["sub_type"];
}

$rules_id = 0;
if (isset($_POST["rules_id"])) {
   $rules_id = $_POST["rules_id"];
} else if (isset($_GET["rules_id"])) {
   $rules_id = $_GET["rules_id"];
}

$rule = new $sub_type();
$rule->checkGlobal(READ);

$test_rule_output = null;

if (!strpos($_SERVER['PHP_SELF'], "popup")) {
   Html::header(__('Setup'), $_SERVER['PHP_SELF'], "config", "display");
}

$rule->showRulePreviewCriteriasForm($_SERVER['PHP_SELF'], $rules_id);

if (isset($_POST["test_rule"])) {
   $params = [];
   //Unset values that must not be processed by the rule
   unset($_POST["test_rule"]);
   unset($_POST["rules_id"]);
   unset($_POST["sub_type"]);
   $rule->getRuleWithCriteriasAndActions($rules_id, 1, 1);

   // Need for RuleEngines
   foreach ($_POST as $key => $val) {
      $_POST[$key] = stripslashes($_POST[$key]);
   }
   //Add rules specific POST fields to the param array
   $params = $rule->addSpecificParamsForPreview($params);

   $input = $rule->prepareInputDataForProcess($_POST, $params);
   //$rule->regex_results = array();
   echo "<br>";
   $rule->showRulePreviewResultsForm($_SERVER['PHP_SELF'], $input, $params);
}

if (!strpos($_SERVER['PHP_SELF'], "popup")) {
   Html::footer();
}

