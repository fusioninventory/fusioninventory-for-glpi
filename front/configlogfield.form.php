<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

Session::checkRight('config', UPDATE);

Html::header(__('Features', 'fusioninventory'), $_SERVER["PHP_SELF"], "plugins",
        "pluginfusioninventorymenu", "configlogfield");

if (isset($_POST['update'])) {

   if (empty($_POST['cleaning_days'])) {
      $_POST['cleaning_days'] = 0;
   }

   $_POST['id']=1;
   switch ($_POST['tabs']) {

      case 'config' :
         break;

      case 'history' :
         $pfConfigLogField = new PluginFusioninventoryConfigLogField();
         foreach ($_POST as $key=>$val) {
            $split = explode("-", $key);
            if (isset($split[1]) AND is_numeric($split[1])) {
               $pfConfigLogField->getFromDB($split[1]);
               $input = array();
               $input['id'] = $pfConfigLogField->fields['id'];
               $input['days'] = $val;
               $pfConfigLogField->update($input);
            }
         }
         break;

   }
   if (isset($pfConfig)) {
      $pfConfig->update($_POST);
   }
   Html::back();
} else if ((isset($_POST['Clean_history']))) {
   $pfNetworkPortLog = new PluginFusioninventoryNetworkPortLog();
   $pfNetworkPortLog->cronCleannetworkportlogs();
   Html::back();
}

Html::footer();

?>
