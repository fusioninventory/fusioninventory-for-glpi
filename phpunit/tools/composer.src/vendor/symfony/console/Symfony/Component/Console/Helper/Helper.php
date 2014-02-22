<?php










namespace Symfony\Component\Console\Helper;






abstract class Helper implements HelperInterface
{
protected $helperSet = null;






public function setHelperSet(HelperSet $helperSet = null)
{
$this->helperSet = $helperSet;
}






public function getHelperSet()
{
return $this->helperSet;
}








protected function strlen($string)
{
if (!function_exists('mb_strlen')) {
return strlen($string);
}

if (false === $encoding = mb_detect_encoding($string)) {
return strlen($string);
}

return mb_strlen($string, $encoding);
}
}
