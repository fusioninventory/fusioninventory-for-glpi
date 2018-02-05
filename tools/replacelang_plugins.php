<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

$DB_file = 'locales/en_GB.php';

$sql_query = file_get_contents($DB_file);
foreach (explode(";\n", "$sql_query") as $line) {
   $split = explode("=", $line, 2);
   $string = $split[1];
   $string = str_replace('"', '', $string);
   echo $split[0]." => ".$string."\n";
   foreach (["./",
                 "./inc/",
                 "./ajax/",
                 "./b/deploy/",
                 "./install/",
                 "./js/",
                 "./scripts/",
                 "./test/"] as $dir) {
      foreach (glob($dir.'*.php') as $file) {
         $php_line_content = file_get_contents($file);
         $php_line_content = str_replace($split[0], "__('".$string."', 'fusioninventory')",
                                        $php_line_content);
         file_put_contents($file, $php_line_content);
      }
   }
}


