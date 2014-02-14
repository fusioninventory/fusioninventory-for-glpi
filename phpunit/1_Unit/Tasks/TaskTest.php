<?php
/**
 * @runTestsInSeparateProcesses
 */
class TaskTest extends BaseTestCase {

   /**
    * @dataProvider provider
    */

   public function testDummy($a) {
      $session = new Session();
      sleep(1);
      $this->assertEquals(strlen($a), 11);
   }

   public function provider() {
      return array(
         array( "Hello World"),
         array( "Couin Couin"),
         array( "Trouver moi")
      );
   }
}

