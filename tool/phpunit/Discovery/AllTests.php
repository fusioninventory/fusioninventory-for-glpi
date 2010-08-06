<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../../../..');
   $_SESSION['glpi_use_mode'] = 2;
   require_once GLPI_ROOT."/inc/includes.php";

   ini_set('display_errors','On');
   error_reporting(E_ALL | E_STRICT);
   set_error_handler("userErrorHandler");

}

require_once 'Agent.php';
require_once 'Newdevices.php';





?>
