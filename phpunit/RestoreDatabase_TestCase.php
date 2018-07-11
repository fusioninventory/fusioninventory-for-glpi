<?php

/*
 * Helper class to restore database from some SQL restore point file
 */

abstract class RestoreDatabase_TestCase extends Common_TestCase {

   public static function setUpBeforeClass() {
      self::restore_database();
   }

}
