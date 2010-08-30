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

function plugin_fusioninventory_task_methods() {
   global $LANG;

   $a_tasks = array();
   $a_tasks[] = array('module'               => 'fusioninventory',
                      'method'               => 'wakeonlan',
                      'selection_type'       => 'devices',
                      'selection_type_name'  => $LANG['common'][1]);
   $a_tasks[] = array('module'         => 'fusioninventory',
                      'method'         => 'wakeonlan',
                      'selection_type' => 'rules');
   $a_tasks[] = array('module'         => 'fusioninventory',
                      'method'         => 'wakeonlan',
                      'selection_type' => 'devicegroups');
   $a_tasks[] = array('module'         => 'fusioninventory',
                      'method'         => 'wakeonlan',
                      'selection_type' => 'fromothertasks');
   return $a_tasks;
}



function plugin_fusioninventory_task_wakeonlan_fromothertasks($a_computerid = array()) {
   global $LANG;


   


}


# Actions with itemtype autorized
function plugin_fusioninventory_task_action_wakeonlan() {
   $a_itemtype = array();
   $a_itemtype[] = COMPUTER_TYPE;

   return $a_itemtype;
}

# Selection type for actions
function plugin_fusioninventory_task_selection_type_wakeonlan($itemtype) {

   switch ($itemtype) {

      case "Computer";
         $selection_type = 'devices';
         break;

   }

   return $selection_type;
}

?>