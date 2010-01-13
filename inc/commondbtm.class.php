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
// Purpose of file: modelisation of a networking switch ports
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to use networking interface address
 * Adds field management to core CommonDBTM class.
 **/
class PluginTrackerCommonDBTM extends CommonDBTM {
   private $ptcdFields=array();
   private $ptcdLockFields=array();
   protected $ptcdUpdates=array();
   protected $ptcdLinkedObjects=array();

	/**
	 * Constructor
	**/
   function __construct($p_table) {
      $this->table=$p_table;
   }

   /**
    * Load an existing address
    *
    *@return nothing
    **/
   function load($p_id='') {
      global $DB, $LINK_ID_TABLE;

      if ($p_id!='') { // existing item : load old values
         $this->getFromDB($p_id);
         $this->ptcdFields=$this->fields;
         $itemtype=array_search($this->table, $LINK_ID_TABLE);
         if ($itemtype) {
            $this->ptcdLockFields=plugin_tracker_lock_getLockFields($itemtype, $p_id);
         }
      } else { // new item : initialize all fields to NULL
         $query = "SHOW COLUMNS FROM `".$this->table."`";
         if ($result=$DB->query($query)) {
            while ($data=$DB->fetch_array($result)) {
               $this->ptcdFields[$data[0]]=NULL;
            }
         }
      }
   }

   /**
    * Update an existing preloaded item with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
      if (count($this->ptcdUpdates)) {
         $this->ptcdUpdates['ID'] = $this->getValue('ID');
         $this->update($this->ptcdUpdates);
      }
   }

   /**
    * Delete a loaded item
    *
    *@param $p_id Ifaddr ID
    *@return nothing
    **/
   function deleteDB() {
      $this->deleteFromDB($this->ptcdFields['ID'], 1);
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
    *@param $p_object=NULL Object to update
    *@return Field value / NULL if unknown field
    **/
   function getValue($p_field, $p_object=NULL) {
      if (is_null($p_object)) {
         $p_object=$this;
      }
      if (array_key_exists($p_field, $p_object->ptcdFields)) {
         return $p_object->ptcdFields[$p_field];
      } else {
         foreach ($p_object->ptcdLinkedObjects as $object) {
            $value = $object->getValue($p_field, $object);
            if (!is_null($value)) {
               return $value;
            }
         }
         return NULL;
      }
   }

   /**
    * Set field value
    *
    *@param $p_field Field
    *@param $p_value Value
    *@param $p_object=NULL Object to update
    *@return true if value set / false if unknown field
    **/
   function setValue($p_field, $p_value, $p_object=NULL) {
      if (is_null($p_object)) {
         $p_object=$this;
      }
      if (array_key_exists($p_field, $p_object->ptcdFields)) {
         if ($p_object->ptcdFields[$p_field]!=$p_value) { // don't update if values are the same
            if (!in_array($p_field, $this->ptcdLockFields)) { // don't update if field is locked
               $p_object->ptcdFields[$p_field] = $p_value;
               $p_object->ptcdUpdates[$p_field] = $p_value;
            }
         }
         return true;
      } else {
         foreach ($this->ptcdLinkedObjects as $object) {
            if ($object->setValue($p_field, $p_value)) {
               return true;
            }
         }
         return false;
      }
   }
}
?>
