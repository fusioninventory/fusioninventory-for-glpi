<?php










namespace Symfony\Component\Console\Formatter;








class OutputFormatterStyle implements OutputFormatterStyleInterface
{
private static $availableForegroundColors = array(
'black' => 30,
'red' => 31,
'green' => 32,
'yellow' => 33,
'blue' => 34,
'magenta' => 35,
'cyan' => 36,
'white' => 37
);
private static $availableBackgroundColors = array(
'black' => 40,
'red' => 41,
'green' => 42,
'yellow' => 43,
'blue' => 44,
'magenta' => 45,
'cyan' => 46,
'white' => 47
);
private static $availableOptions = array(
'bold' => 1,
'underscore' => 4,
'blink' => 5,
'reverse' => 7,
'conceal' => 8
);

private $foreground;
private $background;
private $options = array();










public function __construct($foreground = null, $background = null, array $options = array())
{
if (null !== $foreground) {
$this->setForeground($foreground);
}
if (null !== $background) {
$this->setBackground($background);
}
if (count($options)) {
$this->setOptions($options);
}
}










public function setForeground($color = null)
{
if (null === $color) {
$this->foreground = null;

return;
}

if (!isset(static::$availableForegroundColors[$color])) {
throw new \InvalidArgumentException(sprintf(
'Invalid foreground color specified: "%s". Expected one of (%s)',
$color,
implode(', ', array_keys(static::$availableForegroundColors))
));
}

$this->foreground = static::$availableForegroundColors[$color];
}










public function setBackground($color = null)
{
if (null === $color) {
$this->background = null;

return;
}

if (!isset(static::$availableBackgroundColors[$color])) {
throw new \InvalidArgumentException(sprintf(
'Invalid background color specified: "%s". Expected one of (%s)',
$color,
implode(', ', array_keys(static::$availableBackgroundColors))
));
}

$this->background = static::$availableBackgroundColors[$color];
}










public function setOption($option)
{
if (!isset(static::$availableOptions[$option])) {
throw new \InvalidArgumentException(sprintf(
'Invalid option specified: "%s". Expected one of (%s)',
$option,
implode(', ', array_keys(static::$availableOptions))
));
}

if (false === array_search(static::$availableOptions[$option], $this->options)) {
$this->options[] = static::$availableOptions[$option];
}
}









public function unsetOption($option)
{
if (!isset(static::$availableOptions[$option])) {
throw new \InvalidArgumentException(sprintf(
'Invalid option specified: "%s". Expected one of (%s)',
$option,
implode(', ', array_keys(static::$availableOptions))
));
}

$pos = array_search(static::$availableOptions[$option], $this->options);
if (false !== $pos) {
unset($this->options[$pos]);
}
}






public function setOptions(array $options)
{
$this->options = array();

foreach ($options as $option) {
$this->setOption($option);
}
}








public function apply($text)
{
$codes = array();

if (null !== $this->foreground) {
$codes[] = $this->foreground;
}
if (null !== $this->background) {
$codes[] = $this->background;
}
if (count($this->options)) {
$codes = array_merge($codes, $this->options);
}

if (0 === count($codes)) {
return $text;
}

return sprintf("\033[%sm%s\033[0m", implode(';', $codes), $text);
}
}
