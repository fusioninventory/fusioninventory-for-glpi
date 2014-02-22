<?php










namespace Symfony\Component\Console\Formatter;








class OutputFormatter implements OutputFormatterInterface
{



const FORMAT_PATTERN = '#(\\\\?)<(/?)([a-z][a-z0-9_=;-]*)?>((?: [^<\\\\]+ | (?!<(?:/?[a-z]|/>)). | .(?<=\\\\<) )*)#isx';

private $decorated;
private $styles = array();
private $styleStack;








public static function escape($text)
{
return preg_replace('/([^\\\\]?)</is', '$1\\<', $text);
}









public function __construct($decorated = false, array $styles = array())
{
$this->decorated = (Boolean) $decorated;

$this->setStyle('error', new OutputFormatterStyle('white', 'red'));
$this->setStyle('info', new OutputFormatterStyle('green'));
$this->setStyle('comment', new OutputFormatterStyle('yellow'));
$this->setStyle('question', new OutputFormatterStyle('black', 'cyan'));

foreach ($styles as $name => $style) {
$this->setStyle($name, $style);
}

$this->styleStack = new OutputFormatterStyleStack();
}








public function setDecorated($decorated)
{
$this->decorated = (Boolean) $decorated;
}








public function isDecorated()
{
return $this->decorated;
}









public function setStyle($name, OutputFormatterStyleInterface $style)
{
$this->styles[strtolower($name)] = $style;
}










public function hasStyle($name)
{
return isset($this->styles[strtolower($name)]);
}












public function getStyle($name)
{
if (!$this->hasStyle($name)) {
throw new \InvalidArgumentException(sprintf('Undefined style: %s', $name));
}

return $this->styles[strtolower($name)];
}










public function format($message)
{
$message = preg_replace_callback(self::FORMAT_PATTERN, array($this, 'replaceStyle'), $message);

return str_replace('\\<', '<', $message);
}




public function getStyleStack()
{
return $this->styleStack;
}










private function replaceStyle($match)
{

 if ('\\' === $match[1]) {
return $this->applyCurrentStyle($match[0]);
}

if ('' === $match[3]) {
if ('/' === $match[2]) {

 $this->styleStack->pop();

return $this->applyCurrentStyle($match[4]);
}


 return '<>'.$this->applyCurrentStyle($match[4]);
}

if (isset($this->styles[strtolower($match[3])])) {
$style = $this->styles[strtolower($match[3])];
} else {
$style = $this->createStyleFromString($match[3]);

if (false === $style) {
return $this->applyCurrentStyle($match[0]);
}
}

if ('/' === $match[2]) {
$this->styleStack->pop($style);
} else {
$this->styleStack->push($style);
}

return $this->applyCurrentStyle($match[4]);
}








private function createStyleFromString($string)
{
if (!preg_match_all('/([^=]+)=([^;]+)(;|$)/', strtolower($string), $matches, PREG_SET_ORDER)) {
return false;
}

$style = new OutputFormatterStyle();
foreach ($matches as $match) {
array_shift($match);

if ('fg' == $match[0]) {
$style->setForeground($match[1]);
} elseif ('bg' == $match[0]) {
$style->setBackground($match[1]);
} else {
$style->setOption($match[1]);
}
}

return $style;
}








private function applyCurrentStyle($text)
{
return $this->isDecorated() && strlen($text) > 0 ? $this->styleStack->getCurrent()->apply($text) : $text;
}
}
