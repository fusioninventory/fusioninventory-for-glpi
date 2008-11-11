<?php
/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$NEEDED_ITEMS=array("setup","rulesengine");

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

include_once ("../inc/plugin_tracker.classes.php");

commonHeader($LANGTRACKER["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker");

$plugin_tracker_model_infos=new plugin_tracker_model_infos();

$plugin_tracker_mib_networking=new plugin_tracker_mib_networking();

$importexport = new plugin_tracker_importexport;

if (isset($_POST["add"]))
{

	$plugin_tracker_model_infos->addentry($_SERVER["PHP_SELF"],$_POST);

}
elseif (isset($_FILES['importfile']['tmp_name']))
{

	$importexport->import($_FILES);

}



if (isset($_GET["add"]))
{

	$importexport->showForm($_SERVER["PHP_SELF"]);

	$plugin_tracker_model_infos->showForm($_SERVER["PHP_SELF"],0,"glpi_plugin_tracker_model_infos");

}
elseif (isset($_POST["add_oid"]))
{
	$plugin_tracker_mib_networking->addentry($_SERVER["PHP_SELF"],$_POST["add_oid"]);

}
elseif (isset($_GET["ID"]))
{
	plugin_tracker_checkRight("errors","r");

	if (!isset($_SESSION['glpi_tab'])) $_SESSION['glpi_tab']=1;
	if (isset($_GET['onglet'])) {
		$_SESSION['glpi_tab']=$_GET['onglet'];
		//		glpi_header($_SERVER['HTTP_REFERER']);
	}
	
	$plugin_tracker_model_infos->showForm($_SERVER["PHP_SELF"],$_GET["ID"],"glpi_plugin_tracker_model_infos");

	//$plugin_tracker_mib_networking->showAddMIB();
	
	$plugin_tracker_mib_networking->showForm($_SERVER["PHP_SELF"],$_GET["ID"]);

}

commonFooter();
?>