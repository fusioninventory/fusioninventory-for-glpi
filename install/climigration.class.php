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

/**
 * Manage the display information of class Migration when upgrade
 * plugin in CLI.
 */
class CliMigration extends Migration {


   /**
    * __contruct function where initialize variables
    *
    * @param string $ver
    */
   function __construct($ver) {
      $this->deb     = time();
      $this->version = $ver;
   }


   /**
    * Display a message
    *
    * @param string $msg
    */
   function displayMessage($msg) {
      $msg .= " (".Html::timestampToString(time()-$this->deb).")";
      echo str_pad($msg, 100)."\n";
   }


   /**
    * Display a title
    *
    * @param string $title
    */
   function displayTitle($title) {
      echo "\n".str_pad(" $title ", 100, '=', STR_PAD_BOTH)."\n";
   }


   /**
    * Display a warning message
    *
    * @param string $msg
    * @param boolean $red
    */
   function displayWarning($msg, $red = false) {
      if ($red) {
         $msg = "** $msg";
      }
      echo str_pad($msg, 100)."\n";
   }


}

