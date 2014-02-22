<?php










namespace Symfony\Component\Finder\Adapter;






abstract class AbstractAdapter implements AdapterInterface
{
protected $followLinks = false;
protected $mode = 0;
protected $minDepth = 0;
protected $maxDepth = PHP_INT_MAX;
protected $exclude = array();
protected $names = array();
protected $notNames = array();
protected $contains = array();
protected $notContains = array();
protected $sizes = array();
protected $dates = array();
protected $filters = array();
protected $sort = false;
protected $paths = array();
protected $notPaths = array();
protected $ignoreUnreadableDirs = false;

private static $areSupported = array();




public function isSupported()
{
$name = $this->getName();

if (!array_key_exists($name, self::$areSupported)) {
self::$areSupported[$name] = $this->canBeUsed();
}

return self::$areSupported[$name];
}




public function setFollowLinks($followLinks)
{
$this->followLinks = $followLinks;

return $this;
}




public function setMode($mode)
{
$this->mode = $mode;

return $this;
}




public function setDepths(array $depths)
{
$this->minDepth = 0;
$this->maxDepth = PHP_INT_MAX;

foreach ($depths as $comparator) {
switch ($comparator->getOperator()) {
case '>':
$this->minDepth = $comparator->getTarget() + 1;
break;
case '>=':
$this->minDepth = $comparator->getTarget();
break;
case '<':
$this->maxDepth = $comparator->getTarget() - 1;
break;
case '<=':
$this->maxDepth = $comparator->getTarget();
break;
default:
$this->minDepth = $this->maxDepth = $comparator->getTarget();
}
}

return $this;
}




public function setExclude(array $exclude)
{
$this->exclude = $exclude;

return $this;
}




public function setNames(array $names)
{
$this->names = $names;

return $this;
}




public function setNotNames(array $notNames)
{
$this->notNames = $notNames;

return $this;
}




public function setContains(array $contains)
{
$this->contains = $contains;

return $this;
}




public function setNotContains(array $notContains)
{
$this->notContains = $notContains;

return $this;
}




public function setSizes(array $sizes)
{
$this->sizes = $sizes;

return $this;
}




public function setDates(array $dates)
{
$this->dates = $dates;

return $this;
}




public function setFilters(array $filters)
{
$this->filters = $filters;

return $this;
}




public function setSort($sort)
{
$this->sort = $sort;

return $this;
}




public function setPath(array $paths)
{
$this->paths = $paths;

return $this;
}




public function setNotPath(array $notPaths)
{
$this->notPaths = $notPaths;

return $this;
}




public function ignoreUnreadableDirs($ignore = true)
{
$this->ignoreUnreadableDirs = (Boolean) $ignore;

return $this;
}












abstract protected function canBeUsed();
}
