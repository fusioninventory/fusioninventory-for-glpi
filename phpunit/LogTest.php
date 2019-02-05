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

use PHPUnit\Framework\TestCase;

class GLPIlogs extends TestCase {

   public function testSQLlogs() {

      $filecontent = file_get_contents(GLPI_ROOT."/files/_log/sql-errors.log");

      $this->assertEmpty($filecontent, 'sql-errors.log not empty: '.$filecontent);
      // Reinitialize file
      file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
   }


   public function testPHPlogs() {
      $filecontent = file_get_contents(GLPI_ROOT."/files/_log/php-errors.log");
      $this->assertEmpty($filecontent, 'php-errors.log not empty: '.$filecontent);
      // Reinitialize file
      file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');
   }


}



class GLPIlogs_AllTests  {


   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('GLPIlogs');
      return $suite;
   }


}

