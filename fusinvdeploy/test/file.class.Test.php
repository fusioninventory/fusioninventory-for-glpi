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

      $DB->query("DELETE FROM glpi_plugin_fusinvdeploy_files");
      $DB->query("DELETE FROM glpi_plugin_fusinvdeploy_fileparts");
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
      $this->assertGreaterThan(0, $file_id);

      $this->assertFileNotExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp");
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/e/ed/ed4d36b9146786cdee148810b3d8f5f47cf9dc9f5c7036f998ce530a52f6847b09f4e406254cafedce830296a9b32ba81949e27cb0deef94dc857364d3ee9d56.gz");

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
      $this->assertEquals($file_id, $new_file_id);

# Create a big file
      $handle = fopen(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-big-file", "wb");
      for ($i = 0; $i < 1000000; $i++) {
          fwrite($handle, sha1(rand()));
      }
      fclose($handle);



      $file_id = $PluginFusinvdeployFile->addFileInRepo(array(
               'filename' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-big-file",
               'file_tmp_name' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-big-file",
               'mime_type' => "text/plain",
               'is_p2p' => 1,
               'p2p_retention_days' => 1,
               'order_id' => 1,
               'uncompress' => 1,
      ));

      $dirs = glob(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/*");
      $this->assertGreaterThan(5, count($dirs));


      $this->cleanUp();
   }
}

?>