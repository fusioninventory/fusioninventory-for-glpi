<?

define('GLPI_ROOT', dirname(__FILE__) . '/../../..');
define('GLPI_PLUGIN_DOC_DIR', '/tmp/test-suite');
require_once(dirname(__FILE__) . '/../../../inc/commonglpi.class.php');
require_once(dirname(__FILE__) . '/../../../inc/commondbtm.class.php');
require_once(dirname(__FILE__) . "/../inc/file.class.php");
require_once(dirname(__FILE__) . "/../inc/filepart.class.php");

include (GLPI_ROOT."/inc/includes.php");

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
               'file_tmp_name' => GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/tmp-file",
               'mime_type' => "text/plain",
               'is_p2p' => 1,
               'p2p_retention_days' => 1,
               'order_id' => 1,
               'uncompress' => 1,
               'testMode' => 1
      ));
      $this->assertFileNotExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/part.tmp");
      $this->assertFileExists(GLPI_PLUGIN_DOC_DIR."/fusinvdeploy/files/repository/c/cf/cf83e1357eefb8bdf1542850d66d8007d620e4050b5715dc83f4a921d36ce9ce47d0d13c5d85f2b0ff8318d2877eec2f63b931bd47417a81a538327af927da3e.gz");
      system("rm -r ".GLPI_PLUGIN_DOC_DIR."/fusinvdeploy");
   }

   public function testPluginFusinvdeployGroup() {
      global $DB;

      //test static group
      $static_group = new PluginFusinvdeployGroup();
      $static_groupID = $static_group->add(array(
         'name'      => "UNITTEST_STATIC_GROUP",
         'comment'   => "UNITTEST",
         'type'      => "STATIC"
      ));
      $query = "SELECT * FROM .glpi_plugin_fusinvdeploy_groups WHERE name = 'UNITTEST_STATIC_GROUP'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), 1);

      //test static group datas
      $group_item = new PluginFusinvdeployGroup_Staticdata();
      $query = "SELECT id FROM glpi_computers LIMIT 50";
      $res = $DB->query($query);
      $items = array();
      while ($row = $DB->fetch_array($res)) {
         $items[] = $row['id'];
      }
      foreach ($items as $val) {
         $group_item->add(array(
            'groups_id' => $static_groupID,
            'itemtype' => "Computer",
            'items_id' => $val
         ));
      }
      $nb_datas = count($items);
      $query = "SELECT * FROM .glpi_plugin_fusinvdeploy_groups_staticdatas WHERE groups_id = '$static_groupID'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), $nb_datas);



      //test dynamic group
      $dynamic_group = new PluginFusinvdeployGroup();
      $dynamic_groupID = $dynamic_group->add(array(
         'name'      => "UNITTEST_DYNAMIC_GROUP",
         'comment'   => "UNITTEST",
         'type'      => "DYNAMIC"
      ));
      $query = "SELECT * FROM .glpi_plugin_fusinvdeploy_groups WHERE name = 'UNITTEST_DYNAMIC_GROUP'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), 1);



      //test static group datas
      $group_item = new PluginFusinvdeployGroup_Dynamicdata();
      $fields_array = array(
         'itemtype'     => "Computer",
         'start'        => 0,
         'limit'        => '',
         'serial'       => "01234",
         'otherserial'  => "01234",
         'locations'    => 0,
         'room'         => "room_test",
         'building'     => "room_test"
      );
      $group_item->add(array(
         'groups_id' => $dynamic_groupID,
         'fields_array' => serialize($fields_array)
      ));
      $query = "SELECT * FROM .glpi_plugin_fusinvdeploy_groups_dynamicdatas WHERE groups_id = '$dynamic_groupID'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), 1);


      //test get json group
      $res = json_decode(PluginFusinvdeployGroup::getAllDatas());

      //remove groups
      $query = "DELETE FROM glpi_plugin_fusinvdeploy_groups WHERE comment = 'UNITTEST'";
      $res = $DB->query($query);

   }
}
