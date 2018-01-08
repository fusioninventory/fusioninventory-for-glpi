<?php

class Bootstrap extends BaseTestCase {


   public function should_restore_install() {
      return false;
   }


   public function testInitDatabase() {
      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to database");
   }


}

