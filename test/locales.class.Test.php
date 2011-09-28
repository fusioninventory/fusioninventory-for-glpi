<?
class LocalesTest extends PHPUnit_Framework_TestCase {

   /**
    * This function tests the difference of the locales files keys
    */
   public function testPluginWebservicesLocales() {

      $path_locales = "locales";
      chdir($path_locales);

      $locales_a = array();

      //get all lang files
      foreach (glob("*.php") as $filename) {
         require_once "../".$path_locales."/".$filename;

         $locales_a[] = $LANG['plugin_fusinvdeploy'];
         unset($LANG['plugin_fusinvdeploy']);
      }

      //tests diff between locales
      foreach ($locales_a as $locale) {
         $diff = count(array_diff_key($locale, $locales_a[0]));
         $this->assertEquals($diff, 0);
      }
   }
}
