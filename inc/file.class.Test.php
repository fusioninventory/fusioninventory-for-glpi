<?

define('GLPI_ROOT', dirname(__FILE__) . '/../..');
define('GLPI_PLUGIN_DOC_DIR', '/tmp/test-suite');
require_once(dirname(__FILE__) . '/../../../inc/commonglpi.class.php');
require_once(dirname(__FILE__) . '/../../../inc/commondbtm.class.php');
require_once(dirname(__FILE__) . '/file.class.php');
class StackTest extends PHPUnit_Framework_TestCase
{
   public function testPluginFusinvdeployFile()
   {

      $PluginFusinvdeployFile = new PluginFusinvdeployFile();
      $this->assertEquals($PluginFusinvdeployFile->getDirBySha512("aezfesf"), "a/ae");

      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/")) {
         mkdir (GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/", 0700, 1);
      }
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/");
      touch(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file");
      $PluginFusinvdeployFile->addFileInRepo(array(
               'filename' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'is_p2p' => 1,
               'p2p_retention_days' => 1,
               'order_id' => 1,
               'testMode' => 1
      ));
      $this->assertFileNotExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp");
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/c/cf/cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e.gz");
      system("rm -r ".GLPI_PLUGIN_DOC_DIR."/fusinvdeploy");
   }
}
