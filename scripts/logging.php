<?php
/**
 * Logging facility
 */

error_reporting( E_ALL  );
ini_set("display_errors", 'stderr');
ini_set("log_errors", true);

class Logging {
   public static $LOG_CRITICAL = array('level'=>50, 'name'=>'CRITICAL ');
   public static $LOG_ERROR    = array('level'=>40, 'name'=>'ERROR    ');
   public static $LOG_QUIET    = array('level'=>35,  'name'=>'QUIET    ');
   public static $LOG_WARNING  = array('level'=>30, 'name'=>'WARNING  ');
   public static $LOG_INFO     = array('level'=>20, 'name'=>'INFO     ');
   public static $LOG_DEBUG    = array('level'=>10, 'name'=>'DEBUG    ');

   public $loglevel;

   public function __construct($loglevel = NULL) {

      if( is_null($loglevel) ) {
         $this->loglevel = self::$LOG_INFO;
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
      if ($this->loglevel['level'] <= $loglevel['level']) {
         print( $this->formatlog($msg, $loglevel) . PHP_EOL );
      }
   }

   function info($msg) {
      $this->printlog($msg, self::$LOG_INFO);
   }

   function error($msg) {
      $this->printlog($msg, self::$LOG_ERROR);
   }

   function debug($msg) {
      $this->printlog($msg, self::$LOG_DEBUG);
   }

   function setLevelFromArgs($quiet=false, $debug=false) {
      $this->loglevel = self::$LOG_INFO;
      if          ( $quiet ) {
         $this->loglevel = self::$LOG_QUIET;
      } else if   ( $debug ) {
         $this->loglevel = self::$LOG_DEBUG;
      }
   }
}

?>
