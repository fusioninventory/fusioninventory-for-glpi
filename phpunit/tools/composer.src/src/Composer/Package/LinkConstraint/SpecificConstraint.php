<?php











namespace Composer\Package\LinkConstraint;






abstract class SpecificConstraint implements LinkConstraintInterface
{
protected $prettyString;

public function matches(LinkConstraintInterface $provider)
{
if ($provider instanceof MultiConstraint) {

 return $provider->matches($this);
} elseif ($provider instanceof $this) {
return $this->matchSpecific($provider);
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


 
 

}
