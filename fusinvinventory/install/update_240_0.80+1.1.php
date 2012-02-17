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

// Update from 2.2.1 to 2.3.0
function update240to080011() {
   global $DB;

   ini_set("max_execution_time", "0");

   echo "<strong>Update 2.4.0 to 0.80+1.1</strong><br/>";
   echo "</td>";
   echo "</tr>";

   echo "<tr class='tab_bg_1'>";
   echo "<td align='center'>";

   plugin_fusioninventory_displayMigrationMessage("0.80+1.1"); // Start
   $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         for ($i=1; $i<4;$i++) {
            $queryUpdate = "UPDATE `glpi_plugin_fusinvinventory_libserialization`
               SET `serialized_sections$i` = '" .
               mysql_real_escape_string(htmlspecialchars_decode($data['serialized_sections'.$i])) ."'
               WHERE `internal_id` = '" . $data['internal_id'] . "'";
            $DB->query($queryUpdate);
         }
      }
   }
   
   /*
    * Table `glpi_plugin_fusinvinventory_computer`
    */
   $migration = new Migration("0.80+1.1");
   $newTable = "glpi_plugin_fusinvinventory_computers";
   if (!TableExists($newTable)) {
      $DB->query($newTable);
   }   
   $migration->addField($newTable, 
                        "id", 
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable, 
                        "computers_id", 
                        "int(11) NOT NULL default '0'");   
    $migration->addField($newTable, 
                        "bios_date", 
                        "datetime DEFAULT NULL");
    $migration->addField($newTable, 
                        "bios_version", 
                        "varchar(255) DEFAULT NULL");
    $migration->addField($newTable, 
                        "bios_manufacturers_id", 
                        "int(11) NOT NULL default '0'");
    $migration->addField($newTable, 
                        "operatingsystem_installationdate", 
                        "datetime DEFAULT NULL");
    $migration->addField($newTable, 
                        "winowner", 
                        "varchar(255) DEFAULT NULL");
    $migration->addField($newTable, 
                        "wincompany", 
                        "varchar(255) DEFAULT NULL");
    $migration->addKey($newTable, 
                       "computers_id");

    // TODO : parse all libserialization to update these fields this computers yet in DB
   
   
   plugin_fusioninventory_displayMigrationMessage("0.80+1.1"); // End
}

?>
