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
   @author    Walid Nouh
   @co-author
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../../..');
include (GLPI_ROOT."/inc/includes.php");

$plugin = new Plugin();
if ($plugin->isActivated('fusinvdeploy')) {
   //Only process response if GET HTTP request indicates an agent and an action
   if (isset($_GET['machineid']) && isset($_GET['action'])) {
      $response = array();
      switch ($_GET['action']) {
         //Get jobs to perform
         case 'getJobs':
            $response['jobs'] = PluginFusinvdeployJob::get($_GET['machineid']);
            if (!$response['jobs']) {
               echo "{}\n"; # Empty answer
               exit;
            }
            $response['associatedFiles']  = PluginFusinvdeployFile::getAssociatedFiles($_GET['machineid']);
            break;
         //Change job status
         case 'setStatus':
            $response = PluginFusinvdeployJob::update($_GET, true);
            break;
         default:
            header("HTTP/1.1 500");
            Toolbox::logInFile("unkown action");
            return;
      }

      if ((count($response) === 0)) {
          echo "{}\n"; # Empty answer
      } else {
         $options = 0;
         if (version_compare(PHP_VERSION, '5.3.3') >= 0) $options = $options | JSON_NUMERIC_CHECK;
         if (version_compare(PHP_VERSION, '5.4.0') >= 0) $options = $options | JSON_UNESCAPED_SLASHES;
         
         //the option parameter of json_encode function added in php 5.3
         if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            $json_response = json_encode($response, $options);
         } else {
             $json_response = json_encode($response);
         }

         if (isset($_GET['debug'])) $json_response = PluginFusinvdeployStaticmisc::json_indent($json_response);

         echo $json_response;
      }
   }
   elseif (isset($_GET['action']) && $_GET['action'] == 'getFilePart') {
      PluginFusinvdeployFilepart::httpSendFile($_GET);
   }
} else {
   //Send an error if Fusinvdeploy plugin is not activated !
   header("HTTP/1.1 500");
}

?>