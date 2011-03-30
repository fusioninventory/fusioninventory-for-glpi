<?php
/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the FusionInventory Development Team.

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

// Original Author of file: Walid Nouh (wnouh@teclib.com)
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

$plugin = new Plugin();
if ($plugin->isActivated('fusinvdeploy')) {
   //Only process response if GET HTTP request indicates an agent and an action
   if (isset($_GET['d']) && isset($_GET['a'])) {
      switch ($_GET['a']) {
         //Get jobs to perform
         case 'getJobs':
            echo json_encode(PluginFusinvdeployJob::get($_GET['d']));
            break;
         //Change job status
         case 'setStatus':
            echo PluginFusinvdeployJob::update($_GET, true);
            break;
         //Add log to a job
         case 'setLog':
            echo PluginFusinvdeployJob::update($_GET, false);
            break;
      }
   }
} else {
   //Send an error if Fusinvdeploy plugin is not activated !
   header("HTTP/1.1 500");
}
?>