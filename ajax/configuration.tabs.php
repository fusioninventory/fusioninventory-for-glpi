<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

//echo "configuration.tabs";

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
//header("Content-Type: text/html; charset=UTF-8");
//header_nocache();

//if(!isset($_POST["id"])) {
//	exit();
//}
//if(!isset($_POST["sort"])) $_POST["sort"] = "";
//if(!isset($_POST["order"])) $_POST["order"] = "";
//if(!isset($_POST["withtemplate"])) $_POST["withtemplate"] = "";




//checkRight("config","w");

if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {
   switch($_POST['glpi_tab']) {
      case 2 :
         // lockables
         $ptLockable = new PluginFusioninventoryLockable;
//         $ptLockable->showForm(array('target'=>$_POST['target']));
//      echo 'avant showForm';
         $ptLockable->showForm(array('id'=>0, 'target'=>$_POST['target']));
         break;

      default :
      case 1 :
         $config = new PluginFusioninventoryConfig;
//         $config->showForm('1', array('target'=>$_POST['target']));
         $config->showForm();
//         $ptLockable = new PluginFusioninventoryLockable;
//         $ptLockable->showForm(array('target'=>$_POST['target']));
         break;
   }
} else {
   echo $LANG['common'][83]."<br/>";
}
ajaxFooter();

?>
