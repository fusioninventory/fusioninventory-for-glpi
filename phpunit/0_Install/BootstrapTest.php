<?php
use PHPUnit\Framework\TestCase;

class Bootstrap extends TestCase {

   public function should_restore_install() {
      return FALSE;
   }

   public function testInitDatabase() {
      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to database");
   }
}

