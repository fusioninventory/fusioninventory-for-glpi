<?php
/**
 * Logging facility
 */


class Logging {
   public static $LOG_DEBUG = array('level'=>2, 'name'=>'DEBUG ');
   public static $LOG_INFO  = array('level'=>1, 'name'=>'INFO  ');
   public static $LOG_QUIET = array('level'=>0, 'name'=>'QUIET ');

   public $loglevel;

   public function __construct($loglevel = NULL) {

      if( is_null($loglevel) ) {
         $this->loglevel = self::LOG_INFO;
      } else {
         $this->loglevel = $loglevel;
      }
   }

   public function formatlog($arg, $loglevel) {
      if (is_array($arg) || is_object($arg)) {
         $msg = print_r($arg, true);
      } else if (is_null($arg)) {
         $msg = ' NULL';
      } else if (is_bool($arg)) {
         $msg = ($arg ? 'true' : 'false');
      } else {
         $msg = $arg;
      }
      return $loglevel['name'] . ': '. $msg;
   }

   function printlog($msg="", $loglevel) {

      if ( is_null($loglevel) ) {
         $loglevel = self::$LOG_INFO;
      }

      /*
         print(
            var_export($this->loglevel['level'],true) . " >= " .
            var_export($loglevel['level'],true) . "\n"
         );
       */
      if ($this->loglevel['level'] >= $loglevel['level']) {
         print( $this->formatlog($msg, $loglevel) . PHP_EOL );
      }
   }

   function info($msg) {
      $this->printlog($msg, self::$LOG_INFO);
   }

   function debug($msg) {
      $this->printlog($msg, self::$LOG_DEBUG);
   }
}

?>
