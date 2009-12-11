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
 **/
class PluginTrackerIfaddr extends CommonDBTM {
   private $ID, $ifaddr;
   private $updates=array();

	/**
	 * Constructor
	**/
   function __construct() {
      $this->table="glpi_plugin_tracker_networking_ifaddr";
   }

   /**
    * Load an existing address
    *
    *@return nothing
    **/
   function load($p_id) {
      global $DB;

      $this->getFromDB($p_id);

      $this->ID = $this->fields['ID'];
      $this->ifaddr = $this->fields['ifaddr'];
   }

   /**
    * Update an existing preloaded address with the instance values
    *
    *@return nothing
    **/
   function updateDB() {
//      print_r($this->updates);
      if (count($this->updates)) {
         $this->updates['ID'] = $this->ID;
         $this->update($this->updates);
      }
   }

   /**
    * Get all objetc vars and values
    *
    *@return Array of all class vars => values
    **/
   function getVars() {
//      return get_class_vars( __CLASS__ );
//      return get_class_vars( __CLASS__ );
//      return get_object_vars( __CLASS__ );
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
      if (eval("return isset(\$this->\$p_field);")) {
         if (!eval("return \$this->$p_field==\$p_value;")) { // don't update if values are the same
            eval("return \$this->$p_field=\$p_value;");
   //         array_push($this->updates, "$p_field=>$p_value");
            $this->updates[$p_field] = $p_value;
   //         $this->updates = compact(eval("return \$this->$p_field;"));
         }
         return true;
      } else {
         return false;
      }
   }

}
?>
