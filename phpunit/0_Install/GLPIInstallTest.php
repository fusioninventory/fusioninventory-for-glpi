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
require_once('commonfunction.php');
include_once (GLPI_ROOT . "/inc/define.php");
include_once (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");

class GLPIInstallTest extends PHPUnit_Framework_TestCase {


   /**
    * @test
    */
   public function installDatabase() {

      $DBvars = get_class_vars('DB');

      drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      $glpi_version = getenv('GLPI');
      $glpisql = '';
      if ($glpi_version == '0.90/bugfixes') {
         $glpisql = '0.90';
      } else if ($glpi_version == '0.85/bugfixes') {
         $glpisql = '0.85.5';
      } else {
         $glpisql = null;
      }

      $sql_file = $glpisql === null ? "glpi-empty.sql" : "glpi-$glpisql-empty.sql";
      $result = load_mysql_file(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         GLPI_ROOT ."/install/mysql/$sql_file"
      );

      $output = [];
      $returncode = 0;
      exec(
         "php ".GLPI_ROOT. "/scripts/cliupdate.php --dev --force",
         $output, $returncode
      );
      $this->assertEquals(0, $returncode,
         "Error when update GLPI in CLI mode\n".
         implode("\n", $output)
      );

      $this->assertEquals( 0, $result['returncode'],
         "Failed to install GLPI database:\n".
         implode("\n", $result['output'])
      );

   }


}


