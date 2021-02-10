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
 * This file is used to manage the common rule list.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @author    David Durieux
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

Session::checkLoginUser();

$rule = $rulecollection->getRuleClass();

if (!isset($_GET["id"])) {
   $_GET["id"] = "";
}

$rulecollection->checkGlobal('r');

if (isset($_GET["action"])) {
   $rulecollection->checkGlobal('w');
   $rulecollection->changeRuleOrder($_GET["id"], $_GET["action"]);
   Html::back();

} else if (isset($_POST["action"])) {
   $rulecollection->checkGlobal('w');

   // Use massive action system
   switch ($_POST["action"]) {
      case "delete" :
         if (isset($_POST["item"]) && count($_POST["item"])) {
            foreach ($_POST["item"] as $key => $val) {
               $rule->getFromDB($key);
               $rulecollection->deleteRuleOrder($rule->fields["ranking"]);
               $rule->delete(['id' => $key]);
            }
            Event::log(0, "rules", 4, "setup", $_SESSION["glpiname"]." ".__('item\'s deletion'));

            Html::back();
         }
         break;

      case "move_rule" :
         if (isset($_POST["item"]) && count($_POST["item"])) {
            foreach ($_POST["item"] as $key => $val) {
               $rule->getFromDB($key);
               $rulecollection->moveRule($key, $_POST['ranking'], $_POST['move_type']);
            }
         }
         break;

      case "activate_rule" :
         if (isset($_POST["item"])) {
            foreach ($_POST["item"] as $key => $val) {
               if ($val == 1) {
                  $input = [];
                  $input['id'] = $key;
                  $input['is_active'] = $_POST["activate_rule"];
                  $rule->update($input);
               }
            }
         }
         break;
   }

} else if (isset($_POST["replay_rule"]) || isset($_GET["replay_rule"])) {
   $rulecollection->checkGlobal('w');

   // Current time
   $start = explode(" ", microtime());
   $start = $start[0]+$start[1];

   // Limit computed from current time
   $max = get_cfg_var("max_execution_time");
   $max = $start + ($max>0 ? $max/2.0 : 30.0);

   Html::header(_n('Rule', 'Rules', 2), $_SERVER['PHP_SELF'], "admin", $rulecollection->menu_type,
                $rulecollection->menu_option);

   if (!(isset($_POST['replay_confirm']) || isset($_GET['offset']))
       && $rulecollection->warningBeforeReplayRulesOnExistingDB($_SERVER['PHP_SELF'])) {
      Html::footer();
      exit();
   }

   echo "<table class='tab_cadrehov'>";

   echo "<tr><th><div class='relative'><strong>" .$rulecollection->getTitle(). "</strong>";
   echo " - " .__('Replay the dictionary rules'). "</th></tr>\n";
   echo "<tr><td class='center'>";
   Html::createProgressBar(__('Work in progress...'));

   echo "</td></tr>\n";
   echo "</table>";

   $manufacturer = 0;
   if (!isset($_GET['offset'])) {
      // First run
      $offset       = $rulecollection->replayRulesOnExistingDB(0, $max, [], $_POST);
      $manufacturer = (isset($_POST["manufacturer"]) ? $_POST["manufacturer"] : 0);

   } else {
      // Next run
      $offset       = $rulecollection->replayRulesOnExistingDB($_GET['offset'],
                                                               $max,
                                                               [],
                                                               $_GET);
      $manufacturer = $_GET["manufacturer"];

      // global start for stat
      $start = $_GET["start"];
   }

   if ($offset < 0) {
      // Work ended
      $end   = explode(" ", microtime());
      $duree = round($end[0]+$end[1]-$start);
      Html::changeProgressBarMessage(__('Task completed.').
                                       " (".Html::timestampToString($duree).")");
      echo "<a href='".$_SERVER['PHP_SELF']."'>".__('Back')."</a>";

   } else {
      // Need more work
      Html::redirect($_SERVER['PHP_SELF'].
                        "?start=$start&replay_rule=1&offset=$offset&manufacturer="."$manufacturer");
   }

   Html::footer(true);
   exit();
}

$fi_path = Plugin::getWebDir('fusioninventory');

Html::header(_n('Rule', 'Rules', 2), $_SERVER['PHP_SELF'], "admin", $rulecollection->menu_type,
             $rulecollection->menu_option);

   $tabs = [];
if ($rulecollection->showInheritedTab()) {
   $tabs[0] = [
             'title'  => __('Rules applied', 'fusioninventory').' : '.
                             Dropdown::getDropdownName('glpi_entities',
                                                       $_SESSION['glpiactive_entity']),
             'url'    => $fi_path."/ajax/rules.tabs.php",
             'params' => "target=".$_SERVER['PHP_SELF']."&glpi_tab=1&inherited=1&itemtype=".
                              get_class($rulecollection)];
}

   $title = _n('Rule', 'Rules', 2);

if ($rulecollection->isRuleRecursive()) {
   $title = __('Local rules', 'fusioninventory').' : '.
               Dropdown::getDropdownName('glpi_entities', $_SESSION['glpiactive_entity']);
}
   $tabs[1] = ['title'  => $title,
                   'url'    => $fi_path."/ajax/rules.tabs.php",
                   'params' => "target=".$_SERVER['PHP_SELF']."&glpi_tab=0&inherited=0&itemtype=".
                                 get_class($rulecollection)];

   if ($rulecollection->showChildrensTab()) {
      $tabs[2] = [
                  'title'  => __('Rules applicable in the sub-entities'),

                  'url'    => $fi_path."/ajax/rules.tabs.php",
                  'params' => "target=".$_SERVER['PHP_SELF'].
                                    "&glpi_tab=2&inherited=0&childrens=1&itemtype=".
                                    get_class($rulecollection)];
   }


   echo "<div id='tabspanel' class='center-h'></div>";
   Ajax::createTabs('tabspanel', 'tabcontent', $tabs, $rulecollection->getRuleClassName());
   echo "<div id='tabcontent'>&nbsp;</div>";
   echo "<script type='text/javascript'>loadDefaultTab();</script>";

   Html::footer();

