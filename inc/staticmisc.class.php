<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployStaticmisc {
   static function task_methods() {
      global $LANG;

      $a_tasks = array();
      $a_tasks[] = array('module'         => 'fusinvdeploy',
                         'method'         => 'ocsdeploy',
                         'selection_type' => 'devices',
                         'selection_type_name' => "devices");
      $a_tasks[] = array('module'         => 'fusinvdeploy',
                         'method'         => 'ocsdeploy',
                         'selection_type' => 'rules');
      $a_tasks[] = array('module'         => 'fusinvdeploy',
                         'method'         => 'ocsdeploy',
                         'selection_type' => 'devicegroups');
      return $a_tasks;
   }

   # Actions with itemtype autorized
   static function task_action_ocsdeploy() {
      $a_itemtype = array();
      $a_itemtype[] = 'PluginFusinvdeployPackage';
      $a_itemtype[] = 'Computer';

      return $a_itemtype;
   }

   # Selection type for actions
   static function task_selection_type_ocsdeploy($itemtype) {
      switch ($itemtype) {

         case 'PluginFusinvdeployPackage':
            $selection_type = 'devices';
            break;

         case 'Computer';
            $selection_type = 'devices';
            break;

      }

      return $selection_type;
   }

   # Select arguments if exist
   static function task_argument_ocsdeploy() {
      $PluginFusinvdeployPackage = new PluginFusinvdeployPackage;

      //$a_list = $PluginFusinvdeployPackage->find();
      echo "Package : ";
      echo "</td>";
      echo "<td>";
      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'argument';
      Dropdown::show("PluginFusinvdeployPackage", $options);
   }
}

?>
