<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: 
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Virtualmachine extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      if ($PluginFusioninventoryConfig->getValue($_SESSION["plugin_fusinvinventory_moduleid"],
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
                                                                  $dataSection["VMTYPE"]);
      }

      if (isset($dataSection["SUBSYSTEM"])) {
         $vm['virtualmachinesystems_id']=Dropdown::importExternal('VirtualMachineSystem', 
                                                                  $dataSection["SUBSYSTEM"]);
      }

      if (isset($dataSection["STATUS"])) {
         $vm['virtualmachinestates_id']=Dropdown::importExternal('VirtualMachineState', 
                                                                  $dataSection["STATUS"]);
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


   
   function deleteItem($items_id, $idmachine) {
      $virtualmachine = new ComputerVirtualMachine();
      $virtualmachine->getFromDB($items_id);
      if ($virtualmachine->fields['computers_id'] == $idmachine) {
         $virtualmachine->delete(array("id" => $items_id), 0, $_SESSION["plugin_fusinvinventory_history_add"]);
      }
   }

}

?>