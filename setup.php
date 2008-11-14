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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

include_once ("plugin_tracker.includes.php");

// Init the hooks of tracker
function plugin_init_tracker() {
	
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANGTRACKER;

	$config = new plugin_tracker_config();
	
	pluginNewType('tracker', "PLUGIN_TRACKER_ERROR_TYPE", 5150, "plugin_tracker_errors", "glpi_plugin_tracker_errors", "front/plugin_tracker.errors.form.php");
	pluginNewType('tracker', "PLUGIN_TRACKER_MODEL", 5151, "plugin_tracker_model_infos", "glpi_plugin_tracker_model_infos", "front/plugin_tracker.models.form.php",$LANGTRACKER["model_info"][4]);

	array_push($CFG_GLPI["specif_entities_tables"],"glpi_plugin_tracker_errors");
	
	$PLUGIN_HOOKS['init_session']['tracker'] = 'plugin_tracker_initSession';
	$PLUGIN_HOOKS['change_profile']['tracker'] = 'plugin_tracker_changeprofile';

	if (isset($_SESSION["glpiID"])){

		if (haveRight("config","w")) {
			// Config page
			$PLUGIN_HOOKS['config_page']['tracker'] = 'front/plugin_tracker.config.php';
		}
	
		if(isset($_SESSION["glpi_plugin_tracker_installed"]) && $_SESSION["glpi_plugin_tracker_installed"]==1) {

			if ( ($config->isActivated('counters_statement')) || ($config->isActivated('cleaning')) )
				$PLUGIN_HOOKS['cron']['tracker'] = DAY_TIMESTAMP;
			
			if (isset($_SESSION["glpi_plugin_tracker_profile"])) {
				
				// Tabs for each type
				$PLUGIN_HOOKS['headings']['tracker'] = 'plugin_get_headings_tracker';
				$PLUGIN_HOOKS['headings_action']['tracker'] = 'plugin_headings_actions_tracker';
			
				if (plugin_tracker_haveRight("errors","r")) {
					$PLUGIN_HOOKS['menu_entry']['tracker'] = true;
					$PLUGIN_HOOKS['submenu_entry']['tracker']['add']['models'] = 'front/plugin_tracker.models.form.php?add=1';
					$PLUGIN_HOOKS['submenu_entry']['tracker']['search']['models'] = 'front/plugin_tracker.models.php';

//					$PLUGIN_HOOKS['submenu_entry']['tracker']['printers'] = 'front/plugin_tracker.errors.form.php?device=printer';
//					$PLUGIN_HOOKS['submenu_entry']['tracker']['computers'] = 'front/plugin_tracker.errors.form.php?device=computer';
				}
			}
		}
	}
}

// Name and Version of the plugin
function plugin_version_tracker(){

	return array( 'name'    => 'Tracker',
		'minGlpiVersion' => '0.71',
		'maxGlpiVersion' => '0.71.9',
		'version' => '0.1');
}

function plugin_tracker_haveTypeRight($type,$right){
	
	switch ($type){
		case PLUGIN_TRACKER_ERROR_TYPE :
			return plugin_tracker_haveRight("errors",$right);
			break;
	}
	return true;
}
?>
