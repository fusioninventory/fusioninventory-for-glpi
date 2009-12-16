<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

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
// Purpose of file: modelisation of a networking switch port
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use networking ports
 **/
class PluginTrackerPort extends PluginTrackerCommonDBTM {
   private $oTracker_networking_ports, $tracker_networking_ports_ID;

	/**
	 * Constructor
	**/
   function __construct() {
      parent::__construct("glpi_networking_ports");
      $this->oTracker_networking_ports = new PluginTrackerCommonDBTM("glpi_plugin_tracker_networking_ports");
   }

   /**
    * Load an optionnaly existing port
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB;

      parent::load($p_id);
      if (is_numeric($p_id)) { // port exists
         $query = "SELECT `ID`
                   FROM `glpi_plugin_tracker_networking_ports`
                   WHERE `FK_networking_ports` = '".$p_id."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $portTracker = $DB->fetch_assoc($result);
               $this->tracker_networking_ports_ID = $portTracker['ID'];
               $this->oTracker_networking_ports->load($this->tracker_networking_ports_ID);
               $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
            } else {
               $this->tracker_networking_ports_ID = NULL;
               $this->oTracker_networking_ports->load();
               $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
            }
         }
      } else {
         $this->tracker_networking_ports_ID = NULL;
         $this->oTracker_networking_ports->load();
         $this->ptcdLinkedObjects[]=$this->oTracker_networking_ports;
      }
   }

   /**
    * Update an existing preloaded port with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      parent::updateDB(); // update core
      $this->oTracker_networking_ports->updateDB(); // update tracker
   }

   /**
    * Add a new port with the instance values
    *
    *@param $p_id Networking ID
    *@return nothing
    **/
   function addDB($p_id) { //todo utiliser les bons tableau updates port et tracker ou le meme ?
      if (count($this->ptcdUpdates)) {
         // update core
         $this->ptcdUpdates['on_device']=$p_id;
         $this->ptcdUpdates['device_type']=NETWORKING_TYPE;
         $portID=parent::add($this->ptcdUpdates);
         // update tracker
         if (count($this->oTracker_networking_ports->ptcdUpdates)) {
            $this->oTracker_networking_ports->ptcdUpdates['FK_networking_ports']=$portID;
            $this->oTracker_networking_ports->add($this->oTracker_networking_ports->ptcdUpdates);
         }
      }
   }

   /**
    * Delete a loaded port
    *
    *@param $p_id Port ID
    *@return nothing
    **/
   function deleteDB() {
      $this->oTracker_networking_ports->deleteDB(); // tracker
      parent::deleteDB(); // core
   }
}
?>
