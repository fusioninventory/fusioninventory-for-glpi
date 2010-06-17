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


function pluginFusinvsnmpInstall($version) {
   global $DB,$LANG;

   include (GLPI_ROOT . "/plugins/fusinvsnmp/install/update.php");
   $version_detected = pluginFusioninventoryGetCurrentVersion($version);
   if ((isset($version_detected)) AND ($version_detected != $version)) {
      pluginFusioninventoryUpdate($version);
   } else {
      // Install
      $DB_file = GLPI_ROOT ."/plugins/fusinvsnmp/install/mysql/plugin_fusinvsnmp-".$version."-empty.sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) $DB->query($sql_line)/* or die($DB->error())*/;
      }

      PluginFusioninventoryDb::createfirstaccess($_SESSION['glpiactiveprofile']['id']);
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp');
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/tmp');
      }

      $config = new PluginFusioninventoryConfig;
      $config->initConfig($version);
      $config_modules = new PluginFusioninventoryConfigModules;
      $config_modules->initConfig();
      $configLogField = new PluginFusinvsnmpConfigLogField();
      $configLogField->initConfig();

      // Import models
      $importexport = new PluginFusinvsnmpImportExport;
//      include(GLPI_ROOT.'/inc/setup.function.php');
//      include(GLPI_ROOT.'/inc/rulesengine.function.php');
      foreach (glob(GLPI_ROOT.'/plugins/fusinvsnmp/models/*.xml') as $file) $importexport->import($file,0,1);

      PluginFusioninventoryAuth::initSession();

   }
}

?>