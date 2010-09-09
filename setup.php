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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

include_once ("includes.php");

// Init the hooks of fusinvdeploy
function plugin_init_fusinvdeploy() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   Plugin::registerClass('PluginFusinvdeployPackage');
   Plugin::registerClass('PluginFusinvdeployFile');
   Plugin::registerClass('PluginFusinvdeployPackageFile');
   Plugin::registerClass('PluginFusinvdeployDependence');
   Plugin::registerClass('PluginFusinvdeployHistory');

   $a_plugin = plugin_version_fusinvdeploy();

   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;

	//$PLUGIN_HOOKS['init_session']['fusioninventory'] = array('Profile', 'initSession');
   $PLUGIN_HOOKS['change_profile']['fusinvdeploy'] = PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);


   //$PLUGIN_HOOKS['menu_entry']['fusinvdeploy'] = true;
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['packages'] = '../fusinvdeploy/front/package.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['packages'] = '../fusinvdeploy/front/package.php';

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['files'] = '../fusinvdeploy/front/file.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['files'] = '../fusinvdeploy/front/file.php';

   $_SESSION['glpi_plugin_fusioninventory']['xmltags']['DOWNLOAD'] = 'PluginFusinvdeploycommunicationDeployOcsdeploy';

}



// Name and Version of the plugin
function plugin_version_fusinvdeploy() {
	return array('name'           => 'FusionInventory DEPLOY',
                'shortname'      => 'fusinvdeploy',
                'version'        => '2.3.0-1',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & <a href="mailto:v.mazzoni@siprossii.com">Vincent MAZZONI</a>',
                'homepage'       =>'http://forge.fusioninventory.org/projects/pluginfusinvdeploy',
                'minGlpiVersion' => '0.78'// For compatibility / no install in version < 0.78
   );
}



// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvdeploy_check_prerequisites() {
   global $LANG;
	if (GLPI_VERSION >= '0.78') {
		return true;
   } else {
		echo $LANG['plugin_fusinvdeploy']["errors"][50];
   }
}



function plugin_fusinvdeploy_check_config() {
	return true;
}


?>