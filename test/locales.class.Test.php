<?
class LocalesTest extends PHPUnit_Framework_TestCase {
   private $path_locales   = "locales";
   private $lang_key       = 'plugin_fusinvdeploy';

   /**
    * This function tests the difference of the locales files keys
    */
   public function testPluginLocales() {
      chdir($this->path_locales);

      $locales_a = array();

      //get all lang files
      foreach (glob("*.php") as $filename) {
         require_once "../".$this->path_locales."/".$filename;

         $locales_a[$filename] = $LANG[$this->lang_key];
         unset($LANG[$this->lang_key]);
      }

      $filenames = array_keys($locales_a);

      //tests diff between locales
      foreach ($locales_a as $filename => $locale) {
         if ($filename == $filenames[0]) continue;
         echo "diff between ".$filenames[0]." and $filename\n";
         $return = $this->ignore_testCheckArray($locale, $locales_a[$filenames[0]]);
         $this->assertEquals($return, true);
      }
   }

   /**
    * Recursive function to test all keys and sub keys of locales files
    */
   protected function ignore_testCheckArray($array1, $array2) {
      $diff = count(array_diff_key($array1, $array2));
      if ($diff > 0) return false;

      //check if all sub values is an associative array
      foreach($array2 as $key => $sub) {
         if (!is_int($key)) {
            $return = $this->ignore_testCheckArray($array1[$key], $sub);
            if (!$return) {
               echo "problem in key $key\n";
               return false;
            }
         }
      }

      return true;
   }
}
