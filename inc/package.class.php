<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
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

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][8];
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
         $ong[4] = $LANG['plugin_fusioninventory']['title'][0]." - ".$LANG['plugin_fusioninventory']['task'][18];
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

      echo "<td>".$LANG['plugin_fusinvdeploy']['files'][1]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='filename' size='40' value='".$this->fields["filename"]."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvdeploy']['files'][2]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='version' size='40' value='".$this->fields["version"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusinvdeploy']['package'][9]."&nbsp;:</td>";
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
      echo "<td>".$LANG['plugin_fusinvdeploy']['package'][10]."&nbsp;:</td>";
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

      echo "<td rowspan='2'>".$LANG['computers'][9]."&nbsp;:</td>";
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
      echo "<form name='form' method='post' action='".$this->getFormURL()."'>";
      echo "<input type='hidden' name='id' value='".$id."'/>";
      if ($this->fields['sha1sum'] == "") {
         echo "".$LANG['plugin_fusinvdeploy']['package'][11]."<br/>";
         echo "<input type='submit' name='generate' value='".$LANG['plugin_fusinvdeploy']['package'][12]."' class='submit'/><br/>";
      } else {
         echo "<input type='submit' name='regenerate' value='".$LANG['plugin_fusinvdeploy']['package'][13]."' class='submit'/><br/>";
      }
      echo "</form>";
   }



   function generatePackage($id, $regenerate=0) {
      global $CFG_GLPI;

      $zip = new ZipArchive();
      $PluginFusinvdeployPackage_File = new PluginFusinvdeployPackage_File;
      $PluginFusinvdeployFile = new PluginFusinvdeployFile;

      $this->getFromDB($id);

      $path_package = GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/packages/";

      if ($regenerate == '1') {
         unlink($path_package.$this->fields['sha1sum']);
      }

      // Create zip file
      $filename_package = sha1(serialize($this->fields));
      if ($zip->open($path_package.$filename_package, ZIPARCHIVE::CREATE)!==TRUE) {
         exit("Impossible d'ouvrir <".$path_package.$filename_package.">\n");
      }

      // Add each files
      $a_list = $PluginFusinvdeployPackage_File->find("`plugin_fusinvdeploy_packages_id`='".$id."'");
      foreach($a_list as $packagefile_id=>$data) {
         $PluginFusinvdeployFile->getFromDB($data['plugin_fusinvdeploy_files_id']);

         $filename_sum = $PluginFusinvdeployFile->fields['sha1sum'];
         $filename = $PluginFusinvdeployFile->fields['filename'];

         $zip->addFile(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/".$filename_sum,
                        $data['packagepath'].$filename);

      }

      $zip->close();

      $this->fields['sha1sum'] = $filename_package;
      $this->update($this->fields);
   }



   function downloadFragments ($fileSha1sum) {
      global $CFG_GLPI;

      if (strstr($fileSha1sum, "-")) {
         list($package_id, $numFragment) = explode("-", $fileSha1sum);
      }
      $a_packages = $this->getFromDB($package_id);

      $fileSha1sum = $this->fields["sha1sum"];

      if (isset($numFragment)) {
         // Send fragment
         $fileSize = filesize(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/packages/".$fileSha1sum);
         $fragmentSize = ceil($fileSize / $this->fields['fragments']);
         if ($numFragment == $this->fields['fragments']) {
            echo file_get_contents(GLPI_ROOT."/files/_plugins/fusinvdeploy/packages/".$fileSha1sum,
                                   NULL, NULL, $fragmentSize * ($numFragment - 1));
         } else {
            echo file_get_contents(GLPI_ROOT."/files/_plugins/fusinvdeploy/packages/".$fileSha1sum,
                                   NULL, NULL, $fragmentSize * ($numFragment - 1), $fragmentSize);
         }
      } else {
         // send all file
         echo file_get_contents(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/packages/".$fileSha1sum);
      }

   }


   function downloadFileinfo ($package_id) {
      global $CFG_GLPI;

      $this->getFromDB($package_id);
      $md5 = md5_file(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/packages/".$this->fields['sha1sum']);
      echo '<DOWNLOAD ID="'.$package_id.'" PRI="5" ACT="STORE" DIGEST="';
      echo $md5.'" PROTO="HTTP" FRAGS="'.$this->fields['fragments'].'" DIGEST_ALGO="MD5" DIGEST_ENCODE="Hexa" PATH="./" NAME="" COMMAND="" NOTIFY_USER="0"  NOTIFY_TEXT="" NOTIFY_COUNTDOWN="" NOTIFY_CAN_ABORT="0" NOTIFY_CAN_DELAY="0" NEED_DONE_ACTION="0" NEED_DONE_ACTION_TEXT="" GARDEFOU="rien" />';

   }

}

?>