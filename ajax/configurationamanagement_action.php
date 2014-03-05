<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2014

   ------------------------------------------------------------------------
 */

if (strpos($_SERVER['PHP_SELF'], "configurationamanagement_action.php")) {
   include ("../../../inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}
if (!defined('GLPI_ROOT')) {
   die("Can not acces directly to this file");
}

Session::checkCentralAccess();

$elements = array(
    '_managed_'    => __('In referentiel + alert', 'fusioninventory'),
    '_ignored_'    => __('In referentiel - alert', 'fusioninventory'),
    '_notmanaged_' => __('Not in referentiel', 'fusioninventory'),
);

echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] .
   "/plugins/fusioninventory/front/configurationmanagement.form.php\">";

$tree = $_POST['tree'];
$items_id = $_POST['items_id'];
unset($_POST['tree']);
unset($_POST['items_id']);

echo Html::hidden('id', array('value' => $items_id));
echo Html::hidden('tree', array('value' => $tree));

foreach ($_POST as $elkey => $elvalue) {
   if ($elvalue == 1) {
      echo "<input type='submit' class='submit' name='update_".$elkey."' value='".$elements['_'.$elkey.'_']."' /> ";
   }
}
Html::closeForm();

?>