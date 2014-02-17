<?php











namespace Composer\Util;






class ErrorHandler
{











public static function handle($level, $message, $file, $line)
{

 if (!error_reporting()) {
return;
}

if (ini_get('xdebug.scream')) {
$message .= "\n\nWarning: You have xdebug.scream enabled, the warning above may be".
"\na legitimately suppressed error that you were not supposed to see.";
}

throw new \ErrorException($message, 0, $level, $file, $line);
}






public static function register()
{
set_error_handler(array(__CLASS__, 'handle'));
}
}
