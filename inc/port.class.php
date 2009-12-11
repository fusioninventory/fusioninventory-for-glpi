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
class PluginTrackerPort extends CommonDBTM {
   private $ID, $name, $ifmac, $ifdescr, $ifinerrors, $ifouterrors, $ifinoctets, $ifoutoctets,
           $iflastchange, $ifmtu, $logical_number, $ifspeed, $ifstatus, $iftype, $ifinternalstatus,
           $trunk;
   private $oTracker_networking_ports, $tracker_networking_ports_ID;
   private $updates=array();

	/**
	 * Constructor
	**/
   function __construct() {
      $this->table="glpi_networking_ports";
      $this->oTracker_networking_ports = new CommonDBTM;
      $this->oTracker_networking_ports->table="glpi_plugin_tracker_networking_ports";
   }

   /**
    * Load an existing port
    *
    *@return nothing
    **/
   function load($p_id) {
      global $DB;

      if ($p_id!='') {
         $this->getFromDB($p_id);

         $this->ID = $this->fields['ID'];
         $this->name = $this->fields['name'];
         $this->ifmac = $this->fields['ifmac'];
         $this->logical_number = $this->fields['logical_number'];

         $query = "SELECT `ID`
                   FROM `glpi_plugin_tracker_networking_ports`
                   WHERE `FK_networking_ports` = '".$p_id."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $portTracker = $DB->fetch_assoc($result);
               $this->tracker_networking_ports_ID = $portTracker['ID'];
               $this->oTracker_networking_ports->getFromDB($this->tracker_networking_ports_ID);
               $this->ifdescr = $this->oTracker_networking_ports->fields['ifdescr']; //tracker
               $this->ifinerrors = $this->oTracker_networking_ports->fields['ifinerrors']; //tracker
               $this->ifouterrors = $this->oTracker_networking_ports->fields['ifouterrors']; //tracker
               $this->ifinoctets = $this->oTracker_networking_ports->fields['ifinoctets']; //tracker
               $this->ifoutoctets = $this->oTracker_networking_ports->fields['ifoutoctets']; //tracker
               $this->iflastchange = $this->oTracker_networking_ports->fields['iflastchange']; //tracker
               $this->ifmtu = $this->oTracker_networking_ports->fields['ifmtu']; //tracker
               $this->ifspeed = $this->oTracker_networking_ports->fields['ifspeed']; //tracker
               $this->ifstatus = $this->oTracker_networking_ports->fields['ifstatus']; //tracker
               $this->ifinternalstatus = $this->oTracker_networking_ports->fields['ifinternalstatus']; //tracker
               $this->trunk = $this->oTracker_networking_ports->fields['trunk']; //tracker
            } else { // port exists in core but not in tracker
               $this->tracker_networking_ports_ID = NULL;
               $this->ifdescr = NULL;
               $this->ifinerrors = NULL;
               $this->ifouterrors = NULL;
               $this->ifinoctets = NULL;
               $this->ifoutoctets = NULL;
               $this->iflastchange = NULL;
               $this->ifmtu = NULL;
               $this->ifspeed = NULL;
               $this->ifstatus = NULL;
   //          $this->iftype = NULL;
               $this->ifinternalstatus = NULL;
               $this->trunk = NULL;
            }
         }
      } else { // port doesn't exist in core (also in tracker)
            $this->ID = NULL;
            $this->name = NULL;
            $this->ifmac = NULL;
            $this->logical_number = NULL;
            $this->tracker_networking_ports_ID = NULL;
            $this->ifdescr = NULL;
            $this->ifinerrors = NULL;
            $this->ifouterrors = NULL;
            $this->ifinoctets = NULL;
            $this->ifoutoctets = NULL;
            $this->iflastchange = NULL;
            $this->ifmtu = NULL;
            $this->ifspeed = NULL;
            $this->ifstatus = NULL;
//          $this->iftype = NULL;
            $this->ifinternalstatus = NULL;
            $this->trunk = NULL;
      }
   }

   /**
    * Import a port
    *
    *@param $p_id='' Port id
    *@return nothing
    **/
   function import($p_id='') {
      global $DB;

      if ($p_id=='') { // port doesn't exist
         $this->ID = NULL;
         $this->tracker_networking_ports_ID = NULL;
      } else {
         $this->getFromDB($p_id);// todo voir s'il ne faudrait pas utiliser un objet temporaire pour ne pas polluer l'enregistrement avec des anciennes valeurs
         $this->ID = $this->fields['ID'];
         // get tracker id
         $query = "SELECT `ID`
                   FROM `glpi_plugin_tracker_networking_ports`
                   WHERE `FK_networking_ports` = '".$p_id."';";
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $portTracker = $DB->fetch_assoc($result);
               $this->tracker_networking_ports_ID = $portTracker['ID'];
               $this->oTracker_networking_ports->getFromDB($this->tracker_networking_ports_ID);
            } else {
               $this->tracker_networking_ports_ID = NULL;
            }
         } else {
            $this->tracker_networking_ports_ID = NULL;
         }
         // todo inititalisation des champs avec locks
      }
      $this->name = NULL;
      $this->ifmac = NULL;
      $this->logical_number = NULL;
      $this->ifdescr = NULL;
      $this->ifinerrors = NULL;
      $this->ifouterrors = NULL;
      $this->ifinoctets = NULL;
      $this->ifoutoctets = NULL;
      $this->iflastchange = NULL;
      $this->ifmtu = NULL;
      $this->ifspeed = NULL;
      $this->ifstatus = NULL;
//          $this->iftype = NULL;
      $this->ifinternalstatus = NULL;
      $this->trunk = NULL;
   }

   /**
    * Update an existing preloaded port with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      if (count($this->updates)) {
         // update tracker
         $this->updates['ID'] = $this->tracker_networking_ports_ID;
         $this->oTracker_networking_ports->update($this->updates);
         // update core
         $this->updates['ID'] = $this->ID;
         // todo : ajouter le device_type et on_device dans $this->updates
         // ou gérer ça par la commondbtm ?
         $this->update($this->updates);
      }
   }

   /**
    * Add a new port with the instance values
    *
    *@param $p_id Networking ID
    *@return nothing
    **/
   function addDB($p_id) {
      if (count($this->updates)) {
         // update core
         $this->updates['on_device']=$p_id;
         $this->updates['device_type']=NETWORKING_TYPE;
         $portID=$this->add($this->updates);
         // update tracker
         $this->updates['FK_networking_ports']=$portID;
         $this->oTracker_networking_ports->add($this->updates);
      }
   }

   /**
    * Delete a loaded port
    *
    *@param $p_id Port ID
    *@return nothing
    **/
   function deleteDB() {
      // tracker
      $this->oTracker_networking_ports->deleteFromDB($this->tracker_networking_ports_ID, 1);
      // core
      $this->deleteFromDB($this->ID, 1);
   }

   /**
    * Get all objetc vars and values
    *
    *@return Array of all class vars => values
    **/
   function getVars() {
      return get_object_vars($this);
   }

   /**
    * Get field value
    *
    *@param $p_field Field
    *@return Field value / nothing if unknown field
    **/
   function getValue($p_field) {
      if (eval("return isset(\$this->\$p_field);")) {
         return eval("return \$this->$p_field;");
      }
   }

   /**
    * Set field value
    *
    *@param $p_field Field
    *@param $p_value Value
    *@return true if value set / false if unknown field
    **/
   function setValue($p_field, $p_value) {
// todo simplifier le code : creer un autre port oldPort à l'import alimenté avec toutes les valeurs existantes et comparer avec oldPort->value

      if (is_null($this->ID)) { // new port --> nothing to check : let's update
         $this->$p_field=$p_value;
         $this->updates[$p_field] = $p_value;
      } else {
         if (property_exists($this, $p_field)) {
            if (array_key_exists($p_field, $this->fields)) { // core field
               if ($this->fields[$p_field]!=$p_value) { // don't update if values are the same
                  $this->$p_field=$p_value;
                  $this->updates[$p_field] = $p_value;
               } else {
                  return false;
               }
            } elseif(array_key_exists($p_field, $this->oTracker_networking_ports->fields)) { // tracker field
               if ($this->oTracker_networking_ports->fields[$p_field]!=$p_value) { // don't update if values are the same
                  $this->$p_field=$p_value;
                  $this->updates[$p_field] = $p_value;
               } else {
                  return false;
               }
            }
         } else {
            return false;
         }
      }
      return true;
   }
}
?>
