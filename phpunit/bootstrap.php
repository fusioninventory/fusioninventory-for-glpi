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
   define('GLPI_ROOT', realpath('./glpi'));
   define('FUSINV_ROOT', GLPI_ROOT . DIRECTORY_SEPARATOR . '/plugins/fusioninventory');
   set_include_path(
      get_include_path() . PATH_SEPARATOR .
      GLPI_ROOT . PATH_SEPARATOR .
      GLPI_ROOT . "/plugins/fusioninventory/phpunit/"
   );
}

require_once (GLPI_ROOT . "/inc/autoload.function.php");

include_once("Common_TestCase.php");
include_once("RestoreDatabase_TestCase.php");
include_once("LogTest.php");
include_once("commonfunction.php");
