<?php











namespace Composer\Package\LinkConstraint;






class EmptyConstraint implements LinkConstraintInterface
{
protected $prettyString;

public function matches(LinkConstraintInterface $provider)
{
return true;
}

public function setPrettyString($prettyString)
{
$this->prettyString = $prettyString;
}

public function getPrettyString()
{
if ($this->prettyString) {
return $this->prettyString;
}

return $this->__toString();
}

public function __toString()
{
return '[]';
}
}
