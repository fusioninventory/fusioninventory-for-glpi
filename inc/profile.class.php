<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryProfile extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusioninventory_profiles";
	}
	
	//if profile deleted
	function cleanProfiles($id) {
		global $DB;

		$query = "DELETE FROM `glpi_plugin_fusioninventory_profiles`
                WHERE `id`='$id';";
		$DB->query($query);
	}
		
	function showprofileForm($target,$id) {
		global $LANG,$CFG_GLPI;

		if (!haveRight("profile","r")) return false;

		$onfocus="";
		if ($id) {
			$this->getFromDB($id);
		} else {
			$this->getEmpty();
			$onfocus="onfocus=\"this.value=''\"";
		}

		if (empty($this->fields["interface"])) $this->fields["interface"]="fusioninventory";
		if (empty($this->fields["name"])) $this->fields["name"]=$LANG["common"][0];


		echo "<form name='form' method='post' action=\"$target\">";
		echo "<div align='center'>";
		echo "<table class='tab_cadre'><tr>";
		echo "<table class='tab_cadre_fixe'>";
		echo "<th>".$LANG["common"][16].":</th>";
		echo "<th><input type='text' name='name' value=\"".$this->fields["name"]."\" $onfocus></th>";
		echo "<tr><th colspan='2' align='center'><strong>TEST ".$this->fields["name"]."</strong></th></tr>";

		echo "<th>".$LANG["profiles"][2].":</th>";
		echo "<th><select name='interface' id='profile_interface'>";
		echo "<option value='fusioninventory' ".($this->fields["interface"]!="fusioninventory"?"selected":"").">".$LANG['plugin_fusioninventory']["profile"][1]."</option>";

		echo "</select></th>";
		echo "</tr></table>";
		echo "</div>";
		
		$params=array('interface'=>'__VALUE__',
				'id'=>$id,
			);
		ajaxUpdateItemOnSelectEvent("profile_interface","profile_form",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/profiles.php",$params,false);
		ajaxUpdateItem("profile_form",$CFG_GLPI["root_doc"]."/plugins/fusioninventory/ajax/profiles.php",$params,false,'profile_interface');
//$prof=new PluginFusioninventoryProfile;

//	$prof->showfusioninventoryForm($_POST["id"]);

		echo "<br>";

		echo "<div align='center' id='profile_form'>";
		echo "</div>";

		echo "</form>";

	}
	
	function showForm($id, $options=array()) {
		global $LANG;

		if (!haveRight("profile","r")) return false;
		$canedit=haveRight("profile","w");
		if ($id) {
			$this->getFromDB($id);
		}

		$this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][23]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("configuration",$this->fields["configuration"],1,1,1);
		echo "</td>";
		echo "</tr>";      

		echo "<tr class='tab_bg_1'>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']["profile"][34]." :";
      echo "</th>";
      echo "</tr>";

		echo "<td>".$LANG['plugin_fusioninventory']["profile"][29]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("remotecontrol",$this->fields["remotecontrol"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][31]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("deviceinventory",$this->fields["deviceinventory"],1,0,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][26]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("agents",$this->fields["agents"],1,1,1);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][27]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("agentsprocesses",$this->fields["agentsprocesses"],1,1,0);
		echo "</td>";
		echo "<td>".$LANG['plugin_fusioninventory']["profile"][33]." :</td>";
      echo "<td>";
		Profile::dropdownNoneReadWrite("wol",$this->fields["wol"],1,0,1);
		echo "</td>";
		echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
	}

   static function initSession() {
      global $DB;

      if(TableExists("glpi_plugin_fusioninventory_configs")) {

         if (FieldExists("glpi_plugin_fusioninventory_configs","id")) {
            $profile=new PluginFusioninventoryProfile;

            $query = "SELECT DISTINCT `glpi_profiles`.*
                      FROM `glpi_profiles_users` INNER JOIN `glpi_profiles`
                           ON (`glpi_profiles_users`.`profiles_id` = `glpi_profiles`.`id`)
                      WHERE `glpi_profiles_users`.`users_id`='".$_SESSION["glpiID"]."'";
            $result = $DB->query($query);
            $_SESSION['glpi_plugin_fusioninventory_profile'] = array ();
            if ($DB->numrows($result)) {
               while ($data = $DB->fetch_assoc($result)) {
                  $profile->fields = array ();
                  if(isset($_SESSION["glpiactiveprofile"]["id"])) {
                     $profile->getFromDB($_SESSION["glpiactiveprofile"]["id"]);
                     $_SESSION['glpi_plugin_fusioninventory_profile'] = $profile->fields;
                  } else {
                     $profile->getFromDB($data['id']);
                     $_SESSION['glpi_plugin_fusioninventory_profile'] = $profile->fields;
                  }
                  $_SESSION["glpi_plugin_fusioninventory_installed"]=1;
               }
            }
         }
      }
   }

//   static function haveRight($module,$right) {
//   static function haveRight($modules_id, $type, $right) {
   /*
    * $module fusinv* plugin name
    */
   static function haveRight($module, $type, $right) {
   // echo $_SESSION["glpiactive_entity"];
      $matches=array(
            ""  => array("","r","w"), // ne doit pas arriver normalement
            "r" => array("r","w"),
            "w" => array("w"),
            "1" => array("1"),
            "0" => array("0","1"), // ne doit pas arriver non plus
               );
      if (isset($_SESSION["glpi_plugin_fusioninventory_profile"][$module][$type])
                &&in_array($_SESSION["glpi_plugin_fusioninventory_profile"][$module][$type],$matches[$right])) {
         return true;
      } else {
         return false;
      }
   }

   static function checkRight($module, $type, $right) {
      global $CFG_GLPI;

      if (!PluginFusioninventoryProfile::haveRight($module, $type, $right)) {
         // Gestion timeout session
         if (!isset ($_SESSION["glpiID"])) {
            glpi_header($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }

         displayRightError();
      }
   }

   static function changeprofile() {
      if(isset($_SESSION["glpi_plugin_fusioninventory_installed"])
               && $_SESSION["glpi_plugin_fusioninventory_installed"]==1) {
         $prof=new PluginFusionInventoryProfile;
         if($prof->getFromDB($_SESSION['glpiactiveprofile']['id'])) {
            $_SESSION["glpi_plugin_fusioninventory_profile"]=$prof->fields;
         } else {
            unset($_SESSION["glpi_plugin_fusioninventory_profile"]);
         }
      }
   }

   /*
    * Give all rights to this profile (the installer profile)
    */
   static function createfirstaccess($id) {
      global $DB;

      $plugin_fusioninventory_Profile=new PluginFusioninventoryProfile;
      if (!$plugin_fusioninventory_Profile->GetfromDB($id)) {
         $query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
                     `type`, `right`, `plugin_fusioninventory_modules_id`,
                     `plugin_fusioninventory_profiles_id` )
                   VALUES ('remotecontrol', 'w', 0, $id),
                          ('configuration', 'w', 0, $id),
                          ('agents', 'w', 0, $id),
                          ('agentprocesses', 'r', 0, $id),
                          ('wol', 'w', 0, $id),
                          ('deviceinventory', 'w', 0, $id);";
         $DB->query($query);
      }
   }

   /*
    * Init all rights to NULL for this profile
    */
   static function createaccess($id) {
      global $DB;

      $query = "INSERT INTO `glpi_plugin_fusioninventory_profiles` (
                  `type`, `right`, `plugin_fusioninventory_modules_id`,
                  `plugin_fusioninventory_profiles_id` )
                VALUES ('remotecontrol', NULL, 0, $id),
                       ('configuration', NULL, 0, $id),
                       ('agents', NULL, 0, $id),
                       ('agentprocesses', NULL, 0, $id),
                       ('wol', NULL, 0, $id),
                       ('deviceinventory', NULL, 0, $id);";
      $DB->query($query);
   }

   static function updateaccess($id) {
      global $DB;

      $Profile=new Profile;
      $Profile->GetfromDB($id);
      $name=$Profile->fields["name"];

      $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
                  SET `interface`='fusioninventory', `snmp_networking`='w',
                      `snmp_printers`='w', `snmp_models`='w',
                      `snmp_authentication`='w', `iprange`='w',
                      `agents`='w', `remotecontrol`='w',
                      `agentprocesses`='r', `unknowndevices`='w',
                      `reports`='r', `deviceinventory`='w',
                      `netdiscovery`='w', `snmp_query`='w',
                      `wol`='w', `configuration`='w'
                  WHERE `name`='".$name."'";
      $DB->query($query);

   }

   /**
    * Add profile
    *
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    *@param $p_profiles_id Profile id
    *@return integer the new id of the added item (or false if fail)
    **/
   function addProfile($p_modules_id, $p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      return $this->add(array('type'=>$p_type, `right`=>$p_right,
                       'plugin_fusioninventory_modules_id'=>$p_modules_id,
                       'plugin_fusioninventory_profiles_id'=>$p_profiles_id));
   }

   /**
    * Update profile
    *
    *@param $p_id Profile id
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    *@param $p_profiles_id Profile id
    *@return boolean : true on success
    **/
   function updateProfile($p_id, $p_modules_id, $p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      return $this->update(array('id'=>$p_id, 'type'=>$p_type, `right`=>$p_right,
                       'plugin_fusioninventory_modules_id'=>$p_modules_id,
                       'plugin_fusioninventory_profiles_id'=>$p_profiles_id));
   }

   /**
    * Delete profile
    *
    *@param $p_id Profile id
    *@return boolean : true on success
    **/
   function deleteProfile($p_id) {
      return $this->delete(array('id'=>$p_id));
   }

   /**
    * Clean profile
    *
    *@param $p_modules_id Module id (0 for Fusioninventory)
    *@return boolean : true on success
    **/
   function cleanProfile($p_modules_id) {
      global $DB;

      $delete = "DELETE FROM ".$this->table.
                "WHERE `plugin_fusioninventory_modules_id`='".$p_modules_id."';";
      return $DB->query($delete);
   }

}

?>