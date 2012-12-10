<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvinventory", "importxml","w");

$pfLibintegrity = new PluginFusinvinventoryLibintegrity();
if (!empty($_POST)) {
   if (isset($_POST['clean'])) {
      $pfLibintegrity->cleanGLPI();      
   }
   
   if (isset($_POST['reimport'])) {
      foreach($_POST['reimport'] as $infos=>$num) {
         $pfLibintegrity->Import($infos);
      }
   }
   if (isset($_POST['glpidelete'])) {
      foreach($_POST['glpidelete'] as $infos=>$num) {
         $pfLibintegrity->deleteGLPI($infos);
      }
   }
   Html::back();
}

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","libintegrity");

PluginFusioninventoryMenu::displayMenu("mini");

$pfLibintegrity->showForm();

Html::footer();

?>