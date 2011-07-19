<?

if (!defined('GLPI_PLUGIN_DOC_DIR')) define('GLPI_PLUGIN_DOC_DIR', '/tmp/test-suite');
if (!defined('GLPI_ROOT')) define('GLPI_ROOT', '../..');

$CFG_GLPI["root_doc"] = $_SERVER['PHP_SELF'];
require_once (GLPI_ROOT."/inc/includes.php");

class DeploystateTest extends PHPUnit_Framework_TestCase
{
   public function testPluginFusinvdeployState() {
      //create fake agent
      $agent = new PluginFusioninventoryAgent;
      $agent->add(array(
         'entities_id' => 0,
         'is_recursive' => 0,
         'name' => "testunit_agent",
         'lock' => 0,
         'items_id' => 1,
         'itemtype' => "computer"
      ));

      //create tmp package
      $package = new PluginFusinvdeployPackage;
      $package_id = $package->add(array(
         'name' => "testunit_package",
         'entities_id' => 0,
         'is_recursive' => 0
      ));

      //create tmp task
      $task = new PluginFusinvdeployTask;
      $task_id = $task->add(array(
         'name' => "testunit_task",
         'entities_id' => 0,
         'is_recursive' => 0,
         'is_active' => 1,
         'communication' => "push"
      ));

      //create a job for previous task
      $taskjob = new PluginFusinvdeployTaskjob;
      $taskjob->add(array(
         'plugin_fusinvdeploy_tasks_id' => $task_id,
         'entities_id' => 0,
         'name' => "testunit_taskjob",
         'method' => "deployinstall",
         'definition' => '[{"PluginFusinvdeployPackage":"'.$package_id.'"}]',
         'action' => '[{"Computer":"1"}]'
      ));

      //delete tmp objects
      $agent->deleteFromDB();
      $package->deleteFromDB();
      $task->deleteFromDB();
      $taskjob->deleteFromDB();

   }
}
