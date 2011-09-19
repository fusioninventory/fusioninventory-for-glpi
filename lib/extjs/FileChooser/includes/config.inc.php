<?
define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

define('EXT_DIRECTORY', '/scripts/ext-3.1'); // The web accessible path to your ext source files - No trailing slash!
define('DIRECTORY', $_SERVER['DOCUMENT_ROOT'].$CFG_GLPI['root_doc']/*.'/files/_uploads/'*/); // The directory of files that the file manager will access - No trailing slash!
define('WEB_DIRECTORY', $_SERVER['DOCUMENT_ROOT'].$CFG_GLPI['root_doc']); // The web accessible path to the same directory - No trailing slash!
?>
