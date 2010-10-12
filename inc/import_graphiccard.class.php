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
class PluginFusinvinventoryImport_Graphiccard extends CommonDBTM {


   function AddUpdateItem($type, $items_id, $dataSection) {

      foreach($dataSection as $key=>$value) {
         $dataSection[$key] = addslashes_deep($value);
      }

      $CompDevice = new Computer_Device('DeviceGraphicCard');

      if ($type == "update") {
         $devID = $items_id;
         $CompDevice->getFromDB($items_id);
         $computer_graphiccard = $CompDevice->fields;
      } else if ($type == "add") {
         $id_disk = 0;
      }
      $graphiccard = array();

      if (isset($dataSection['NAME'])) {
         $graphiccard['designation'] = $dataSection['NAME'];
      }
      if (isset($dataSection['MEMORY'])) {
         $graphiccard['specif_default'] = $dataSection['MEMORY'];
      } else {
         $graphiccard['specif_default'] = "0";
      }

      $DeviceGraphicCard = new DeviceGraphicCard();
      $graphiccard_id = $DeviceGraphicCard->import($graphiccard);

      if ($graphiccard_id) {
         if ($type == "update") {
            $devID = $CompDevice->update(array('id' => $items_id,
                                         '_no_history' => true,
                                         'computers_id' => $computer_graphiccard['computers_id'],
                                         '_itemtype'     => 'DeviceGraphicCard',
                                         'devicegraphiccards_id'     => $graphiccard_id));
         } else if ($type == "add") {
            $devID = $CompDevice->add(array('computers_id' => $items_id,
                                         '_no_history' => true,
                                         '_itemtype'     => 'DeviceGraphicCard',
                                         'devicegraphiccards_id'     => $graphiccard_id));
         }
         return $devID;         
      }
      return "";
   }


   
   function deleteItem() {

   }

}

?>