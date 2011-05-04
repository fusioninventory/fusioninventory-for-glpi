<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryWizard {

   function filAriane($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();
      } else {
         return;
      }

      if (count($a_list) == '0') {
         return;
      }
      echo "<table class='tab_cadre' width='250'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th>";
      echo "<strong>Fil d'ariane</strong>";
      echo "</th>";
      echo "</tr>";
      foreach ($a_list as $name=>$link) {
         echo "<tr class='tab_bg_1'>";
         echo "<td>";
         if (strstr($link, $_GET['wizz'])) {
            echo "<img src='".GLPI_ROOT."/pics/right.png'/>";
         } else {
            echo "<img src='".GLPI_ROOT."/pics/right_off.png'/>";
         }
         $getariane = "&ariane=".$ariane;
         if ($link == "w_start") {
            $getariane = "";
         }
         echo " <a href='".GLPI_ROOT."/plugins/fusioninventory/front/wizard.php?wizz=".$link.$getariane."'>".$name."</a>";
         echo "</td>";
         echo "</tr>";
      }

      echo "</table>";
   }


   static function displayButtons($a_buttons, $filariane) {

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td rowspan='2' align='center'>";
         echo "<table cellspacing='10'>";
         echo "<tr>";
         foreach ($a_buttons as $array) {
            $getariane = '';
            if (isset($array[3]) AND $array[3] != '') {
               $getariane = '&ariane='.$array[3];
            }
            echo "<td class='bgout'
               onmouseover='this.className=\"bgover\"' onmouseout='this.className=\"bgout\"'
               onClick='location.href=\"".GLPI_ROOT."/plugins/fusioninventory/front/wizard.php?wizz=".$array[1].$getariane."\"'
               width='240' height='155' align='center'>";
            echo "<strong>".$array[0]."</strong><br/><br/>";
            if ($array[2] != '') {
               echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/".$array[2]."'/>";
            }
            echo "</td>";
         }
         echo "</tr>";
         echo "</table>";
      echo "</td>";
      echo "<td height='8'></td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td valign='top'>";
      $pluginFusioninventoryWizard->filAriane($filariane);
      echo "</td>";
      echo "</tr>";

      echo "</table></center>";
   }


   static function displayShowForm($a_button, $a_filariane, $classname) {

      $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();

      echo "<style type='text/css'>
      .bgout {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button.png);
      }
      .bgover {
         background-image: url(".GLPI_ROOT."/plugins/fusioninventory/pics/wizard_button_active.png);
      }
      </style>";
      echo "<center><table width='950'>";
      echo "<tr>";
      echo "<td>";

      if (isset($_GET['id'])) {
         $target = $_SERVER["REQUEST_URI"];
         $target = str_replace("&id=0", "", $target);
         echo "<table class='tab_cadre'>";
         echo "<tr>";
         echo "<th>";
         echo "<a href='".$target."'>See list</a>";
         echo "</th>";
         echo "</tr>";
         echo "</table>";
         $class = new $classname;
         $class->showForm(0, array('target'=>$target));
      } else {
         echo "<table class='tab_cadre'>";
         echo "<tr>";
         echo "<th>";
         echo "<a href='".$_SERVER["REQUEST_URI"]."&id=0'>Add an item</a>";
         echo "</th>";
         echo "</tr>";
         echo "</table>";
         Search::manageGetValues($classname);
         Search::showList($classname, $_GET);
      }

      echo "</td>";
      echo "<td valign='top'>";
      $pluginFusioninventoryWizard->filAriane($a_filariane);
      echo "</td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td align='right'>";

      // 
      echo "<input class='submit' type='submit' name='next' value='".$a_button['name']."'
            onclick='window.location.href=\"".GLPI_ROOT."/plugins/fusioninventory/front/wizard.php?wizz=".$a_button['link']."\"'/>";
      echo "</form>";
      echo "</td>";
      echo "<td></td>";
      echo "</tr>";

      echo "</table></center>";
   }


   static function getNextStep($ariane) {
      if (method_exists("PluginFusioninventoryWizard", $ariane)) {
         $pluginFusioninventoryWizard = new PluginFusioninventoryWizard();
         $a_list = $pluginFusioninventoryWizard->$ariane();

         $find = 0;
         foreach ($a_list as $link) {
            if (strstr($link, $_GET['wizz'])) {
               $find = 1;
            } else {
               if ($find == '1') {
                  return $link."&ariane=".$ariane;
               }
            }
         }
      } else {
         return;
      }
   }




   function filInventoryComputer() {
      return array(
      "choix de l'action"              => "w_start",
      "Type de matériel à inventorier" => "w_inventorychoice",
      "Options d'importation"          => "w_importcomputeroptions",
      "Règles d'import d'ordinateurs"  => "w_importrules",
      "Règles de sélection de l'entité"=> "",
      "Configuration des agents"       => "");
   }



   function filInventoryESX() {
      return array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_inventorychoice",
      "Gestion des mots de passe"          => "w_credential",
      "Gestion des serveur ESX"            => "w_remotedevices",
      "Règles d'import d'ordinateurs"      => "w_importrules",
      "Création de taches d'exécution"     => "",
      "Affichage des inventaires réalisés" => "");
   }


   
   function filInventorySNMP() {
      return array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_inventorychoice",
      "Choix (decouverte ou inventaire)"   => "",
      "Authentification SNMP"              => "w_authsnmp",
      "Règles d'import"                    => "w_importrules",
      "Création de taches d'exécution"     => "",
      "Affichage des inventaires réalisés" => "");
   }


   function filNetDiscovery() {
      $array = array(
      "choix de l'action"                  => "w_start");
      return array_merge($array, $this->fil_Part_NetDiscovery());
   }


   function filInventorySNMP_Netdiscovery() {
      $array = array(
      "choix de l'action"                  => "w_start",
      "Type de matériel à inventorier"     => "w_snmpdeviceschoice",
      "Choix (decouverte ou inventaire)"   => "");
      return array_merge($array, $this->fil_Part_NetDiscovery());
  }


   function fil_Part_NetDiscovery() {
      return array(
      "Authentification SNMP"              => "w_authsnmp",
      "Règles d'import"                    => "w_importrules",
      "Création de taches d'exécution"     => "",
      "Affichage des inventaires réalisés" => "");
   }

   

  // ********************* All wizard display **********************//

   /*
    * First panel of wizard
    */
   static function w_start($ariane='') {
      $a_buttons = array(array('Découvrir le matériel sur le réseau',
                               'w_authsnmp',
                               'networkscan.png',
                               'filNetDiscovery'),
                         array('Inventorier des matériels',
                                'w_inventorychoice',
                                'general_inventory.png',
                                ''));

      echo "<center>Bienvenue dans FusionInventory. Commencer la configuration ?</center><br/>";

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }


   
   static function w_inventorychoice($ariane='') {
      $a_buttons = array(array('Des ordinateurs et leur périphériques',
                               'w_importcomputeroptions',
                               '',
                               'filInventoryComputer'),
                         array('Serveurs ESX',
                               'w_credential',
                               '',
                               'filInventoryESX'),
                         array('Des imprimantes réseaux ou des matériels réseaux',
                                'w_snmpdeviceschoice',
                                'general_inventory.png',
                                ''));

      echo "<center>Bienvenue dans FusionInventory. Commencer la configuration ?</center><br/>";

      PluginFusioninventoryWizard::displayButtons($a_buttons, $ariane);
   }
   


   static function w_authsnmp($ariane='') {
      $a_button = array('name' => 'Suivant',
                  'link' => PluginFusioninventoryWizard::getNextStep($ariane));

      PluginFusioninventoryWizard::displayShowForm($a_button, $ariane, "PluginFusinvsnmpConfigSecurity");
   }


   static function w_importrules($ariane='') {
      $a_button = array('name' => 'Suivant',
                  'link' => PluginFusioninventoryWizard::getNextStep($ariane));

      PluginFusioninventoryWizard::displayShowForm($a_button, $ariane, "PluginFusioninventoryRuleImportEquipmentCollection");
   }


   
   static function w_credential($ariane='') {
      $a_button = array('name' => 'Suivant',
                  'link' => PluginFusioninventoryWizard::getNextStep($ariane));

      PluginFusioninventoryWizard::displayShowForm($a_button, $ariane, "PluginFusioninventoryCredential");
   }



   static function w_remotedevices($ariane='') {
      $a_button = array('name' => 'Suivant',
                  'link' => PluginFusioninventoryWizard::getNextStep($ariane));

      PluginFusioninventoryWizard::displayShowForm($a_button, $ariane, "PluginFusioninventoryCredentialIp");
   }

}

?>