<?

if (!defined('GLPI_PLUGIN_DOC_DIR')) define('GLPI_PLUGIN_DOC_DIR', '/tmp/test-suite');
if (!defined('GLPI_ROOT')) define('GLPI_ROOT', '../..');

$CFG_GLPI["root_doc"] = $_SERVER['PHP_SELF'];
require_once (GLPI_ROOT."/inc/includes.php");

class GroupTest extends PHPUnit_Framework_TestCase
{
   public function testPluginFusinvdeployGroup() {
      global $DB;

      //remove old test group
      $query = "DELETE FROM glpi_plugin_fusinvdeploy_groups WHERE comment = 'UNITTEST'";
      $res = $DB->query($query);

      //test static group
      $static_group = new PluginFusinvdeployGroup();
      $static_groupID = $static_group->add(array(
         'name'      => "UNITTEST_STATIC_GROUP",
         'comment'   => "UNITTEST",
         'type'      => "STATIC"
      ));
      $query = "SELECT * FROM glpi_plugin_fusinvdeploy_groups WHERE name = 'UNITTEST_STATIC_GROUP'";
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
      $query = "SELECT * FROM glpi_plugin_fusinvdeploy_groups_staticdatas WHERE groups_id = '$static_groupID'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), $nb_datas);



      //test dynamic group
      $dynamic_group = new PluginFusinvdeployGroup();
      $dynamic_groupID = $dynamic_group->add(array(
         'name'      => "UNITTEST_DYNAMIC_GROUP",
         'comment'   => "UNITTEST",
         'type'      => "DYNAMIC"
      ));
      $query = "SELECT * FROM glpi_plugin_fusinvdeploy_groups WHERE name = 'UNITTEST_DYNAMIC_GROUP'";
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
      $query = "SELECT * FROM glpi_plugin_fusinvdeploy_groups_dynamicdatas WHERE groups_id = '$dynamic_groupID'";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), 1);


      //test get json group

      $json = PluginFusinvdeployGroup::getAllDatas();
      $datas = get_object_vars(json_decode($json));
      $this->assertArrayHasKey('groups', $datas);
      $query = "SELECT * FROM glpi_plugin_fusinvdeploy_groups";
      $res = $DB->query($query);
      $this->assertEquals($DB->numrows($res), count($datas['groups']));

      //remove groups
      $query = "DELETE FROM glpi_plugin_fusinvdeploy_groups WHERE comment = 'UNITTEST'";
      $res = $DB->query($query);

   }
}

?>