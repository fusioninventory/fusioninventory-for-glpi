<?php
class TaskTest extends Common_TestCase {

   /**
    * @dataProvider provider
    * @test
    */
   public function Dummy($a) {
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

