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


   
   /**
    * Add profile
    *
    *@param $p_plugins_id Module plugin id
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    *@param $p_profiles_id Profile id
    *@return integer the new id of the added item (or false if fail)
    **/
    static function addProfile($p_plugins_id, $p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      $pfp = new PluginFusioninventoryProfile;
      return $pfp->add(array('type'=>$p_type,
                              'right'=>$p_right,
                              'plugins_id'=>$p_plugins_id,
                              'profiles_id'=>$p_profiles_id));
   }



   /**
    * Update profile
    *
    *@param $p_id Profile id
    *@param $p_plugins_id Module plugin id
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    *@param $p_profiles_id Profile id
    *@return boolean : true on success
    **/
   function updateProfile($p_id, $p_plugins_id, $p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      return $this->update(array('id'=>$p_id,
                                 'type'=>$p_type,
                                 'right'=>$p_right,
                                 'plugins_id'=>$p_plugins_id,
                                 'profiles_id'=>$p_profiles_id));
   }



   /**
    * Create full profile (used on install plugin)
    *
    *@param $p_plugins_id Module plugin id
    *@param $a_profile array with Right type ('wol', 'agents'...) and Right (NULL, r, w)
    **/
   static function initProfile($p_plugins_id, $a_profile = array()) {
      global $DB;

      $pfp = new PluginFusioninventoryProfile;
      foreach ($a_profile as $type=>$right) {
         $pfp->addProfile($p_plugins_id, $type, $right);
      }
      $pfp = new PluginFusioninventoryProfile;
     $pfp->changeProfile($p_plugins_id);
   }



   /**
    * Change profile (for used connected)
    *
    *@param $p_plugins_id Module plugin id
    **/
   static function changeprofile($p_plugins_id) {
      $moduleName = PluginFusioninventoryModule::getModuleName($p_plugins_id);
      if ($moduleName != false) {
            $pfp=new PluginFusioninventoryProfile;
            $a_rights = $pfp->find("`profiles_id` = ".$_SESSION['glpiactiveprofile']['id'].
                                   " AND `plugins_id`='".$p_plugins_id."'");
            $i = 0;
            foreach ($a_rights as $id=>$datas) {
               $i++;
               $_SESSION["glpi_plugin_".$moduleName."_profile"][$datas['type']] = $datas['right'];
            }
            if ($i == '0') {
               unset($_SESSION["glpi_plugin_".$moduleName."_profile"]);
         }
      }
   }



   /**
    * test if user have right
    *
    *@param $p_moduleName Module name
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    *@return boolean : true if right is ok
    **/
   static function haveRight($p_moduleName, $p_type, $p_right) {
      $matches=array(
            ""  => array("","r","w"), // ne doit pas arriver normalement
            "r" => array("r","w"),
            "w" => array("w"),
               );
      if (isset($_SESSION["glpi_plugin_".$p_moduleName."_profile"][$p_type])
                &&in_array($_SESSION["glpi_plugin_".$p_moduleName."_profile"][$p_type],$matches[$p_right])) {
         return true;
      } else {
         return false;
      }
   }



   /**
    * Check right and display error if right not ok
    *
    *@param $p_moduleName Module name
    *@param $p_type Right type ('wol', 'agents'...)
    *@param $p_right Right (NULL, r, w)
    **/
   static function checkRight($p_moduleName, $p_type, $p_right) {
      global $CFG_GLPI;

      $pfp = new PluginFusioninventoryProfile;
      if (!$pfp->haveRight($p_moduleName, $p_type, $p_right)) {
         // Gestion timeout session
         if (!isset ($_SESSION["glpiID"])) {
            glpi_header($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         displayRightError();
      }
   }



   /**
    * Clean profile
    *
    *@param $p_moduleName Module name
    *@return boolean : true on success
    **/
   static function cleanProfile($p_moduleName) {
      global $DB;

      $pfp = new PluginFusioninventoryProfile;

      $plugins_id = PluginFusioninventoryModule::getModuleId($p_moduleName);

      $delete = "DELETE FROM ".$pfp->table.
                " WHERE `plugins_id`='".$plugins_id."';";
      return $DB->query($delete);
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


	
	function showProfileForm($target,$id) {
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
}

?>