<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class GLPIlogs extends TestCase {

   public function testSQLlogs() {
      $filecontent = file_get_contents("../../files/_log/sql-errors.log");

      $this->assertEmpty($filecontent, 'sql-errors.log not empty: '.$filecontent);
      // Reinitialize file
      file_put_contents("../../files/_log/sql-errors.log", '');
   }


   public function testPHPlogs() {
      $filecontent = file("../../files/_log/php-errors.log");
      $lines = [];
      foreach ($filecontent as $line) {
         if (!strstr($line, 'apc.')
            && !strstr($line, 'glpiphplog.DEBUG: Config::getCache()')
            && !strstr($line, 'Test logger')) {
            $lines[] = $line;
         }
      }
      $this->assertEmpty(implode("", $lines), 'php-errors.log not empty: '.implode("", $lines));
      // Reinitialize file
      file_put_contents("../../files/_log/php-errors.log", '');
   }
}
