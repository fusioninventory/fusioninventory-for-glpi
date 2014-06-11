<?php

use Zend\Loader\SplAutoloader;

class FusioninventoryIncludePathAutoloader implements SplAutoloader
{
   protected $paths = array();

   public function __construct($options = null)
   {
      if (null !== $options) {
         $this->setOptions($options);
      }
   }

   public function setOptions($options)
   {
      if (!is_array($options) && !($options instanceof \Traversable)) {
         throw new \InvalidArgumentException();
      }

      foreach ($options as $path) {
         if (!in_array($path, $this->paths)) {
            $this->paths[] = $path;
         }
      }
      return $this;
   }

   public function processClassname($classname)
   {
      preg_match("/Plugin([A-Z][a-z0-9]+)([A-Z]\w+)([A-Z]\w+)/",$classname,$matches);

      if (count($matches) < 4) {
         return false;
      } else {
         return $matches;
      }

   }

   public function autoload($classname)
   {
//      Toolbox::logDebug($classname);

      $matches = $this->processClassname($classname);

      if($matches !== false) {
         $plugin_name = strtolower($matches[1]);
         $class_name = strtolower($matches[2]);
         $class_category = strtolower($matches[3]);

//         Toolbox::logDebug($matches);
//         Toolbox::logDebug($plugin_name);
         if ( $plugin_name !== "fusioninventory" ) {
            return false;
         }

         $filename = implode(".", array(
            $class_name,
            $class_category,
            "class",
            "php"
         ));

//         Toolbox::logDebug($filename);

         foreach ($this->paths as $path) {
            $test = $path . DIRECTORY_SEPARATOR . $filename;
            //Toolbox::logDebug($test);
            if (file_exists($test)) {
               return include($test);
            }
         }
      }
      return false;
   }

   public function register()
   {
      spl_autoload_register(array($this, 'autoload'));
   }
}

?>
