<?php










namespace Symfony\Component\Finder\Expression;




class Expression implements ValueInterface
{
const TYPE_REGEX = 1;
const TYPE_GLOB = 2;




private $value;






public static function create($expr)
{
return new self($expr);
}




public function __construct($expr)
{
try {
$this->value = Regex::create($expr);
} catch (\InvalidArgumentException $e) {
$this->value = new Glob($expr);
}
}




public function __toString()
{
return $this->render();
}




public function render()
{
return $this->value->render();
}




public function renderPattern()
{
return $this->value->renderPattern();
}




public function isCaseSensitive()
{
return $this->value->isCaseSensitive();
}




public function getType()
{
return $this->value->getType();
}




public function prepend($expr)
{
$this->value->prepend($expr);

return $this;
}




public function append($expr)
{
$this->value->append($expr);

return $this;
}




public function isRegex()
{
return self::TYPE_REGEX === $this->value->getType();
}




public function isGlob()
{
return self::TYPE_GLOB === $this->value->getType();
}






public function getGlob()
{
if (self::TYPE_GLOB !== $this->value->getType()) {
throw new \LogicException('Regex can\'t be transformed to glob.');
}

return $this->value;
}




public function getRegex()
{
return self::TYPE_REGEX === $this->value->getType() ? $this->value : $this->value->toRegex();
}
}
