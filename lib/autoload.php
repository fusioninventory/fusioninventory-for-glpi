<?php

use Zend\Loader\SplAutoloader;

class ModifiedIncludePathAutoloader implements SplAutoloader
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

   public function autoload($classname)
   {
      Toolbox::logDebug($classname);

      $parts = explode('\\', $classname);
      Toolbox::logDebug($parts);

      $filename = null;

      /**
       * The namespaces of Fusioninventory classes to load must be respect the following format:
       *    Fusioninventory\<Type>\<Class>
       */

      if (count($parts) < 3) {
         return false;
      }

      if ( $parts[0] !== "Fusioninventory" ) {
         return false;
      }

      if ( ! preg_match("|PluginFusioninventory|", $parts[2]) ){
         return false;
      }

      switch ($parts[1]) {
         case "View":
            $filename = strtolower(
               implode (
                  ".",
                  array(
                     str_replace("Plugin".$parts[0].$parts[1], "", $parts[2]),
                     $parts[1],
                     "class",
                     "php"
                  )
               )
            );
            break;
         case "Model":
            $filename = "";
            break;
      }
      Toolbox::logDebug($filename);

      foreach ($this->paths as $path) {
         $test = $path . DIRECTORY_SEPARATOR . $filename;
         Toolbox::logDebug($test);
         if (file_exists($test)) {
            return include($test);
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
