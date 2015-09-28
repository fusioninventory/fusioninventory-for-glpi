<?php

include ("../../../inc/includes.php");

Session::checkLoginUser();
Html::header(__('FusionInventory', 'fusioninventory'),
             $_SERVER["PHP_SELF"],
             "plugins",
             "pluginfusioninventorymenu",
             "ruletaskpostaction");

PluginFusioninventoryMenu::displayMenu("mini");

$rulecollection = new PluginFusioninventoryTaskpostactionRuleCollection();

include (GLPI_ROOT . "/front/rule.common.php");

?>
