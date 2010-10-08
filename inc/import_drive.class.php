<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

require_once GLPI_ROOT.'/plugins/fusinvsnmp/inc/communicationsnmp.class.php';

/**
 * Class 
 **/
class PluginFusinvinventoryImport_Drive extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      if (($dataSection['TYPE'] == "Removable Disk")
             OR ($dataSection['TYPE'] == "Compact Disc")) {

         return "";
      }

      $ComputerDisk = new ComputerDisk;

      if ($type == "update") {
         $id_disk = $items_id;
         $ComputerDisk->getFromDB($items_id);
         $disk = $ComputerDisk->fields;
      } else if ($type == "add") {
         $id_disk = 0;
         $disk=array();
         $disk['computers_id']=$items_id;
      }

      // totalsize 	freesize
      if ((isset($dataSection['LABEL'])) AND (!empty($dataSection['LABEL']))) {
         $disk['name']=$dataSection['LABEL'];
      } else if ((!isset($dataSection['VOLUMN'])) AND (isset($dataSection['LETTER']))) {
         $disk['name']=$dataSection['LETTER'];
      } else {
         $disk['name']=$dataSection['TYPE'];
      }
      if (isset($dataSection['VOLUMN'])) {
         $disk['device']=$dataSection['VOLUMN'];
      }
      if (isset($dataSection['MOUNTPOINT'])) {
         $disk['mountpoint'] = $dataSection['MOUNTPOINT'];
      } else if (isset($dataSection['LETTER'])) {
         $disk['mountpoint'] = $dataSection['LETTER'];
      } else if (isset($dataSection['TYPE'])) {
         $disk['mountpoint'] = $dataSection['TYPE'];
      }
      $disk['filesystems_id']=Dropdown::importExternal('Filesystem', $dataSection["FILESYSTEM"]);
      if (isset($dataSection['TOTAL'])) {
         $disk['totalsize']=$dataSection['TOTAL'];
      }
      if (isset($dataSection['FREE'])) {
         $disk['freesize']=$dataSection['FREE'];
      }
      if (isset($disk['name']) && !empty($disk["name"])) {
         if ($type == "update") {
            $id_disk = $ComputerDisk->update($disk);
         } else if ($type == "add") {
            $id_disk = $ComputerDisk->add($disk);
         }
      }
      return $id_disk;
   }


   
   function deleteItem() {

   }

}

?>