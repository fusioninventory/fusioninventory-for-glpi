<?php











namespace Composer\IO;






class NullIO extends BaseIO
{



public function isInteractive()
{
return false;
}




public function isVerbose()
{
return false;
}




public function isVeryVerbose()
{
return false;
}




public function isDebug()
{
return false;
}




public function isDecorated()
{
return false;
}




public function write($messages, $newline = true)
{
}




public function overwrite($messages, $newline = true, $size = 80)
{
}




public function ask($question, $default = null)
{
return $default;
}




public function askConfirmation($question, $default = true)
{
return $default;
}




public function askAndValidate($question, $validator, $attempts = false, $default = null)
{
return $default;
}




public function askAndHideAnswer($question)
{
return null;
}
}
