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
 * Class to manage generic multi-tables objects
 * Adds field management to core CommonDBTM class.
 **/
class PluginFusionInventoryCommonDBTM extends CommonDBTM {
   private $ptcdFields=array();
   private $ptcdLockFields=array();
   protected $ptcdUpdates=array();
   protected $ptcdLinkedObjects=array();
   private $logFile;

	/**
	 * Constructor
	**/
   function __construct($p_table, $p_logFile='') {
      $this->table=$p_table;
      if ($p_logFile != '') {
         $this->logFile = $p_logFile;
         $this->addLog('New PluginFusionInventoryCommonDBTM object.');
      } else {
         $this->logFile = GLPI_ROOT.'/files/_plugins/fusioninventory/commonDBTM_'.
                                    time().'_'.rand(1,1000);
         file_put_contents($this->logFile, 'New PluginFusionInventoryCommonDBTM object.');
      }
   }

   /**
    * Load an existing item
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
            $this->ptcdLockFields=plugin_fusioninventory_lock_getLockFields($itemtype, $p_id);
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
    * Add a new item with the instance values
    *
    *@param $p_force=FALSE Force add even if no updates where done
    *@return nothing
    **/
   function addCommon($p_force=FALSE) {
      if (count($this->ptcdUpdates) OR $p_force) {
         $itemID=parent::add($this->ptcdUpdates);
         $this->load($itemID);
      }
   }

   /**
    * Update an existing preloaded item with the instance values or add a new one
    *
    *@return nothing
    **/
   function updateDB() {
      if (count($this->ptcdUpdates)) {
         $this->ptcdUpdates['ID'] = $this->getValue('ID');
         if ($this->ptcdUpdates['ID'] != '') {
            $this->update($this->ptcdUpdates);
         } else {
            unset($this->ptcdUpdates['ID']);
            $this->add($this->ptcdUpdates);
         }
      }
   }

   /**
    * Delete a loaded item
    *
    *@param $p_id Item ID
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
   function setValue($p_field, $p_value, $p_object=NULL, $p_default='') {
      // TODO : replace $p_default by check default value in DB ?
      if (is_null($p_object)) {
         $p_object=$this;
      }
      if (array_key_exists($p_field, $p_object->ptcdFields)) {
         if (!in_array($p_field, $this->ptcdLockFields)) { // don't update if field is locked
            if ($p_object->ptcdFields[$p_field]!=$p_value) { // don't update if values are the same
               if (!($p_object->getValue($p_field)==$p_default AND $p_value=="")) { // don't update if both values are empty
                  $p_object->ptcdFields[$p_field] = $p_value;
                  $p_object->ptcdUpdates[$p_field] = $p_value;
               }
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

   /**
    * Add logs
    *
    *@param $p_logs logs to write
    *@return nothing (write text in log file)
    **/
   function addLog($p_logs) {
      file_put_contents($this->logFile, "\n".time().' : '.$p_logs, FILE_APPEND);
   }
}

?>