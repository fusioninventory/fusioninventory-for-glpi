<?php










namespace Symfony\Component\Finder\Expression;




class Regex implements ValueInterface
{
const START_FLAG = '^';
const END_FLAG = '$';
const BOUNDARY = '~';
const JOKER = '.*';
const ESCAPING = '\\';




private $pattern;




private $options;




private $startFlag;




private $endFlag;




private $startJoker;




private $endJoker;








public static function create($expr)
{
if (preg_match('/^(.{3,}?)([imsxuADU]*)$/', $expr, $m)) {
$start = substr($m[1], 0, 1);
$end = substr($m[1], -1);

if (($start === $end && !preg_match('/[*?[:alnum:] \\\\]/', $start)) || ($start === '{' && $end === '}')) {
return new self(substr($m[1], 1, -1), $m[2], $end);
}
}

throw new \InvalidArgumentException('Given expression is not a regex.');
}






public function __construct($pattern, $options = '', $delimiter = null)
{
if (null !== $delimiter) {

 $pattern = str_replace('\\'.$delimiter, $delimiter, $pattern);
}

$this->parsePattern($pattern);
$this->options = $options;
}




public function __toString()
{
return $this->render();
}




public function render()
{
return self::BOUNDARY
.$this->renderPattern()
.self::BOUNDARY
.$this->options;
}




public function renderPattern()
{
return ($this->startFlag ? self::START_FLAG : '')
.($this->startJoker ? self::JOKER : '')
.str_replace(self::BOUNDARY, '\\'.self::BOUNDARY, $this->pattern)
.($this->endJoker ? self::JOKER : '')
.($this->endFlag ? self::END_FLAG : '');
}




public function isCaseSensitive()
{
return !$this->hasOption('i');
}




public function getType()
{
return Expression::TYPE_REGEX;
}




public function prepend($expr)
{
$this->pattern = $expr.$this->pattern;

return $this;
}




public function append($expr)
{
$this->pattern .= $expr;

return $this;
}






public function hasOption($option)
{
return false !== strpos($this->options, $option);
}






public function addOption($option)
{
if (!$this->hasOption($option)) {
$this->options.= $option;
}

return $this;
}






public function removeOption($option)
{
$this->options = str_replace($option, '', $this->options);

return $this;
}






public function setStartFlag($startFlag)
{
$this->startFlag = $startFlag;

return $this;
}




public function hasStartFlag()
{
return $this->startFlag;
}






public function setEndFlag($endFlag)
{
$this->endFlag = (bool) $endFlag;

return $this;
}




public function hasEndFlag()
{
return $this->endFlag;
}






public function setStartJoker($startJoker)
{
$this->startJoker = $startJoker;

return $this;
}




public function hasStartJoker()
{
return $this->startJoker;
}






public function setEndJoker($endJoker)
{
$this->endJoker = (bool) $endJoker;

return $this;
}




public function hasEndJoker()
{
return $this->endJoker;
}






public function replaceJokers($replacement)
{
$replace = function ($subject) use ($replacement) {
$subject = $subject[0];
$replace = 0 === substr_count($subject, '\\') % 2;

return $replace ? str_replace('.', $replacement, $subject) : $subject;
};

$this->pattern = preg_replace_callback('~[\\\\]*\\.~', $replace, $this->pattern);

return $this;
}




private function parsePattern($pattern)
{
if ($this->startFlag = self::START_FLAG === substr($pattern, 0, 1)) {
$pattern = substr($pattern, 1);
}

if ($this->startJoker = self::JOKER === substr($pattern, 0, 2)) {
$pattern = substr($pattern, 2);
}

if ($this->endFlag = (self::END_FLAG === substr($pattern, -1) && self::ESCAPING !== substr($pattern, -2, -1))) {
$pattern = substr($pattern, 0, -1);
}

if ($this->endJoker = (self::JOKER === substr($pattern, -2) && self::ESCAPING !== substr($pattern, -3, -2))) {
$pattern = substr($pattern, 0, -2);
}

$this->pattern = $pattern;
}
}
