<?php
class ToolboxTest extends Common_TestCase {

   public $formatJson_input = array(
      'test_text' => 'Lorem Ipsum',
      'test_number' => 1234,
      'test_float' => 1234.5678,
      'test_array' => array( 1,2,3,4, 'lorem_ipsum' ),
      'test_hash' => array('lorem' => 'ipsum', 'ipsum' => 'lorem')
   );

   public $formatJson_expected = <<<JSON
{
    "test_text": "Lorem Ipsum",
    "test_number": 1234,
    "test_float": 1234.5678,
    "test_array": [
        1,
        2,
        3,
        4,
        "lorem_ipsum"
    ],
    "test_hash": {
        "lorem": "ipsum",
        "ipsum": "lorem"
    }
}
JSON;

   /**
    * @test
    */
   public function formatJson() {

      $this->assertEquals(
         $this->formatJson_expected,
         PluginFusioninventoryToolbox::formatJson(json_encode($this->formatJson_input))
      );
   }
}
