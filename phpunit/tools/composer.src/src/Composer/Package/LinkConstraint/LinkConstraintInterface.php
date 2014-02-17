<?php











namespace Composer\Package\LinkConstraint;






interface LinkConstraintInterface
{
public function matches(LinkConstraintInterface $provider);
public function setPrettyString($prettyString);
public function getPrettyString();
public function __toString();
}
