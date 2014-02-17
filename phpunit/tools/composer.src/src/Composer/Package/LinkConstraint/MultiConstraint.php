<?php











namespace Composer\Package\LinkConstraint;







class MultiConstraint implements LinkConstraintInterface
{
protected $constraints;
protected $prettyString;
protected $conjunctive;







public function __construct(array $constraints, $conjunctive = true)
{
$this->constraints = $constraints;
$this->conjunctive = $conjunctive;
}

public function matches(LinkConstraintInterface $provider)
{
if (false === $this->conjunctive) {
foreach ($this->constraints as $constraint) {
if ($constraint->matches($provider)) {
return true;
}
}

return false;
}

foreach ($this->constraints as $constraint) {
if (!$constraint->matches($provider)) {
return false;
}
}

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
$constraints = array();
foreach ($this->constraints as $constraint) {
$constraints[] = $constraint->__toString();
}

return '['.implode($this->conjunctive ? ', ' : ' | ', $constraints).']';
}
}
