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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage extra debug in files.
 */
class PluginFusioninventoryLogger {


   /**
    * Log when extra-debug is activated
    *
    * @param string $file
    * @param string $message
    */
   static function logIfExtradebug($file, $message) {
      if (!PluginFusioninventoryConfig::isExtradebugActive()) {
         return;
      }
      Toolbox::logInFile($file, $message);
   }


   /**
    * log when extra-debug and debug mode is activated
    *
    * @param string $file
    * @param string $message
    */
   static function logIfExtradebugAndDebugMode($file, $message) {
      if ($_SESSION['glpi_use_mode'] != Session::DEBUG_MODE) {
         return;
      }
      self::logIfExtradebug($file, $message);
   }


}

