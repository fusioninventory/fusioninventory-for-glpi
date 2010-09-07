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

class PluginFusinvdeployPackage extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_fusinvdeploy_packages";
		$this->type = "PluginFusinvdeployPackage";
	}


      static function getTypeName() {
      global $LANG;

      return "Packages";
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }



   function defineTabs($options=array()){
		global $LANG,$CFG_GLPI;

      $ong = array();
		if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1]=$LANG['plugin_fusinvdeploy']["package"][5];
         $ong[2]="Fichiers";
         $ong[3]="Dépendances";
      }
		return $ong;
	}


   
   function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

      if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG["common"][16]."&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
		echo "</td>";

		echo "<td>Nom de fichier&nbsp;:</td>";
		echo "<td align='center'>";
      echo "<input type='text' name='filename' size='40' value='".$this->fields["filename"]."'/>";
		echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>Version&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='version' size='40' value='".$this->fields["version"]."'/>";
		echo "</td>";

		echo "<td>Nombre de fragments&nbsp;:</td>";
		echo "<td align='center'>";
      Dropdown::showInteger("fragments",$this->fields["fragments"] , 1, 100);
		echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusinvdeploy']["package"][0]."&nbsp;:</td>";
		echo "<td align='center'>";
      $a_actions[] = $LANG['plugin_fusinvdeploy']["package"][2];
      $a_actions[] = $LANG['plugin_fusinvdeploy']["package"][3];
      $a_actions[] = $LANG['plugin_fusinvdeploy']["package"][4];
      Dropdown::showFromArray('action',$a_actions, array('value' => $this->fields["action"]));
		echo "</td>";
		echo "<td>Module&nbsp;:</td>";
		echo "<td align='center'>";
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $a_modules = $PluginFusioninventoryAgentmodule->find("`plugins_id`='".$_SESSION["plugin_fusinvdeploy_moduleid"]."'");
      $a_modulename = array();
      foreach($a_modules as $module_id=>$data) {
         $a_modulename[$module_id] = $data['modulename'];
      }
      Dropdown::showFromArray("modulename", $a_modulename);
		echo "</td>";
      echo "</tr>";


		echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['plugin_fusinvdeploy']["package"][1]."&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='commandline' size='40' value='".$this->fields["commandline"]."'/>";
		echo "</td>";

		echo "<td rowspan='2'>Operating system&nbsp;:</td>";
		echo "<td rowspan='2' align='center'>";
      $OperatingSystem = new OperatingSystem;
      $list = $OperatingSystem->find();
      echo "<table>";
      foreach($list as $operatingsystem_id=>$data) {
         echo "<tr>";
         echo "<td>";
         echo "<input type='checkbox' name='operatingsystems_id' value='".$operatingsystem_id."'>".$data['name'];
         echo "</td>";
         echo "</tr>";
      }
      echo "</table>";
		echo "</td>";
      echo "</tr>";
      

      echo "<tr class='tab_bg_1'>";
		echo "<td>".$LANG['common'][25]."&nbsp;:</td>";
		echo "<td align='center'>";
		echo "<textarea cols='40' rows='6' name='comment' >".$this->fields["comment"]."</textarea>";
		echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo "<div id='tabcontent'></div>";
      echo "<script type='text/javascript'>loadDefaultTab();</script>";

      return true;
   }


   function showFormGenerate($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

      $this->getFromDB($id);
      if ($this->fields['sha1sum'] == "") {
         echo "Ce paquet n'a pas encore été généré<br/>";
         echo "<input type='button' value='générer le package' class='submit'/><br/>";
      } else {
         echo "<input type='button' value='re-générer le package' class='submit'/><br/>";
      }
   }
  
}

?>