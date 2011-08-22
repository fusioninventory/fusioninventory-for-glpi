<?

if (!defined('GLPI_PLUGIN_DOC_DIR')) define('GLPI_PLUGIN_DOC_DIR', '/tmp/test-suite');
if (!defined('GLPI_ROOT')) define('GLPI_ROOT', '../..');

$CFG_GLPI["root_doc"] = $_SERVER['PHP_SELF'];
require_once (GLPI_ROOT."/inc/includes.php");

class FileTest extends PHPUnit_Framework_TestCase
{

   public function cleanUp()
   {
      global $DB;

      $DB->query("DELETE FROM glpi_plugin_fusinvdeploy_files WHERE shortsha512 = 'cf83e1'");
      system("rm -rf ".GLPI_PLUGIN_DOC_DIR."/fusinvdeploy");
   }

   public function testPluginFusinvdeployFile()
   {
      $this->cleanUp();

      $PluginFusinvdeployFile = new PluginFusinvdeployFile();
      $this->assertEquals($PluginFusinvdeployFile->getDirBySha512("aezfesf"), "a/ae");

      if (!file_exists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/")) {
         mkdir (GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/", 0700, 1);
      }
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/");
      touch(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file");
      $file_id = $PluginFusinvdeployFile->addFileInRepo(array(
               'filename' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'file_tmp_name' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'mime_type' => "text/plain",
               'is_p2p' => 1,
               'p2p_retention_days' => 1,
               'order_id' => 1,
               'uncompress' => 1,
      ));
      $this->assertFileNotExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp");
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/c/cf/cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e.gz");

      # Add the same file 2 time in a row
      touch(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file");
      $new_file_id = $PluginFusinvdeployFile->addFileInRepo(array(
               'filename' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'file_tmp_name' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'mime_type' => "text/plain",
               'is_p2p' => 1,
               'p2p_retention_days' => 1,
               'order_id' => 1,
               'uncompress' => 1,
      ));
      error_log("$file_if, $new_file_id");
      $this->assertEquals($file_if, $new_file_id);

      $this->cleanUp();
   }
}
