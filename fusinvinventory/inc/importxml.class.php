<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryImportXML extends CommonDBTM  {


   /**
   * Display form for import XML
   *
   * @return bool true if form is ok
   *
   **/
   function showForm() {
      global $CFG_GLPI,$LANG;

      $target = $CFG_GLPI['root_doc'].'/plugins/fusinvinventory/front/importxml.php';
      echo "<form action='".$target."' method='post' enctype='multipart/form-data'>";

      echo "<br>";
      echo "<table class='tab_cadre' cellpadding='1' width='600'><tr><th colspan='2'>";
      echo $LANG['plugin_fusinvinventory']['importxml'][0]." :</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo "</td>";
      echo "<td align='center'>";
      echo "<input type='file' name='importfile' value=''/>";
      echo "&nbsp;<input type='submit' value='".$LANG["buttons"][37]."' class='submit'/>";
      echo "</td>";
      echo "</tr>";

      echo "</table>";

      Html::closeForm();
      return true;
   }



   /**
   * Import the XML of the agent (Computer inventory)
   *
   * @param $file XML file to import
   *
   * @return bool
   *
   **/
   function importXMLFile($file) {
      $pfInventory = new PluginFusinvinventoryInventory();
      $p_xml = file_get_contents($file);
      libxml_use_internal_errors(true);      
      if ($pxml = @simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA)) {
         libxml_clear_errors();
         $communication  = new PluginFusioninventoryOCSCommunication();
         $pxml = $communication->cleanXML($pxml);
         $pfInventory->sendCriteria("", "", $pxml);
         return true;
      } else {
         libxml_clear_errors();
         return false;
      }
   }



   /**
   * Import the content of the XML of the agent (Computer inventory)
   *
   * @param $p_xml value XML of the agent inventory
   *
   * @return nothing
   *
   **/
   function importXMLContent($p_xml) {
      $pfInventory = new PluginFusinvinventoryInventory();
      libxml_use_internal_errors(true);
      if (simplexml_load_string($p_xml)) {
         libxml_clear_errors();
         $pfInventory->sendCriteria("", "", $p_xml);
      } else {
         libxml_clear_errors();
      }
   }
}

?>