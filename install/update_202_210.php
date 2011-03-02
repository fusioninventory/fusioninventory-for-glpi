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

// Update from 2.0.2 to 2.1.0
function update202to210() {
   global $DB;

   $DB->query("UPDATE `glpi_plugin_tracker_networking`
               SET `last_PID_update` = '0';");
   $DB->query("UPDATE `glpi_plugin_tracker_config`
               SET `version` = '2.1.0'
               WHERE `id`='1'
               LIMIT 1 ;");


}

?>