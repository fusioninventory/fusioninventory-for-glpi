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
   die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Virtualmachine extends CommonDBTM {


   /**
   * Add VirtualMachine
   *
   * @param $type value "add" or "update"
   * @param $items_id integer id of the computer
   * @param $dataSection array all values of the section
   *
   * @return id of the VM or false
   **/
   function AddUpdateItem($type, $items_id, $dataSection) {

      $pfConfig = new PluginFusioninventoryConfig();
      if ($pfConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
              "import_vm") == '0') {
         return;
      }

      $virtualmachine = new ComputerVirtualMachine();

      $id_vm = 0;
      $vm=array();
      if ($type == "update") {
         $id_vm = $items_id;
         $virtualmachine->getFromDB($items_id);
         $vm = $virtualmachine->fields;
      } else if ($type == "add") {
         $id_vm = 0;
         $vm=array();
         $vm['computers_id']=$items_id;
      }

      if (isset($dataSection["NAME"])) {
         $vm['name']= $dataSection["NAME"];
      }

      $fields = array('VCPU' => 'vcpu', 'MEMORY' => 'ram');
      foreach ($fields as $from_inventory => $in_glpi) {
         if (isset($dataSection[$from_inventory])) {
            if ($dataSection[$from_inventory] == '') {
               $vm[$in_glpi] = 0;
            }
            $vm[$in_glpi] = $dataSection[$from_inventory];
         }
      
      }

      if (isset($dataSection["VMTYPE"])) {
         $vm['virtualmachinetypes_id']=Dropdown::importExternal('VirtualMachineType', 
                                                                $dataSection["VMTYPE"],
                                                                $_SESSION["plugin_fusinvinventory_entity"]);
      }

      if (isset($dataSection["SUBSYSTEM"])) {
         $vm['virtualmachinesystems_id']=Dropdown::importExternal('VirtualMachineSystem', 
                                                                  $dataSection["SUBSYSTEM"],
                                                                  $_SESSION["plugin_fusinvinventory_entity"]);
      }

      if (isset($dataSection["STATUS"])) {
         $vm['virtualmachinestates_id']=Dropdown::importExternal('VirtualMachineState', 
                                                                  $dataSection["STATUS"],
                                                                  $_SESSION["plugin_fusinvinventory_entity"]);
      }

      if (isset($dataSection["UUID"])) {
         $vm['uuid']=$dataSection["UUID"];
      }

      if (count($vm) > 0) {
         if ($type == "update") {
            $id_vm = $virtualmachine->update($vm);
         } else if ($type == "add") {
            if ($_SESSION["plugin_fusinvinventory_no_history_add"]) {
               $vm['_no_history'] = $_SESSION["plugin_fusinvinventory_no_history_add"];
            }
            $id_vm = $virtualmachine->add($vm);
         }
      }
      return $id_vm;
   }


   
   /**
   * Delete virtual machine
   *
   * @param $items_id integer id of the user or -username
   * @param $idmachine integer id of the computer
   *
   * @return nothing
   *
   **/
   function deleteItem($items_id, $idmachine) {
      $virtualmachine = new ComputerVirtualMachine();
      $virtualmachine->getFromDB($items_id);
      if ($virtualmachine->fields['computers_id'] == $idmachine) {
         $virtualmachine->delete(array("id" => $items_id), 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }
}

?>