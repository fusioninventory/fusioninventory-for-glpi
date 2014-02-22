<?php










namespace Symfony\Component\Finder;

use Symfony\Component\Finder\Adapter\AdapterInterface;
use Symfony\Component\Finder\Adapter\GnuFindAdapter;
use Symfony\Component\Finder\Adapter\BsdFindAdapter;
use Symfony\Component\Finder\Adapter\PhpAdapter;
use Symfony\Component\Finder\Exception\ExceptionInterface;
















class Finder implements \IteratorAggregate, \Countable
{
const IGNORE_VCS_FILES = 1;
const IGNORE_DOT_FILES = 2;

private $mode = 0;
private $names = array();
private $notNames = array();
private $exclude = array();
private $filters = array();
private $depths = array();
private $sizes = array();
private $followLinks = false;
private $sort = false;
private $ignore = 0;
private $dirs = array();
private $dates = array();
private $iterators = array();
private $contains = array();
private $notContains = array();
private $adapters = array();
private $paths = array();
private $notPaths = array();
private $ignoreUnreadableDirs = false;

private static $vcsPatterns = array('.svn', '_svn', 'CVS', '_darcs', '.arch-params', '.monotone', '.bzr', '.git', '.hg');




public function __construct()
{
$this->ignore = static::IGNORE_VCS_FILES | static::IGNORE_DOT_FILES;

$this
->addAdapter(new GnuFindAdapter())
->addAdapter(new BsdFindAdapter())
->addAdapter(new PhpAdapter(), -50)
->setAdapter('php')
;
}








public static function create()
{
return new static();
}









public function addAdapter(Adapter\AdapterInterface $adapter, $priority = 0)
{
$this->adapters[$adapter->getName()] = array(
'adapter' => $adapter,
'priority' => $priority,
'selected' => false,
);

return $this->sortAdapters();
}






public function useBestAdapter()
{
$this->resetAdapterSelection();

return $this->sortAdapters();
}










public function setAdapter($name)
{
if (!isset($this->adapters[$name])) {
throw new \InvalidArgumentException(sprintf('Adapter "%s" does not exist.', $name));
}

$this->resetAdapterSelection();
$this->adapters[$name]['selected'] = true;

return $this->sortAdapters();
}






public function removeAdapters()
{
$this->adapters = array();

return $this;
}






public function getAdapters()
{
return array_values(array_map(function (array $adapter) {
return $adapter['adapter'];
}, $this->adapters));
}








public function directories()
{
$this->mode = Iterator\FileTypeFilterIterator::ONLY_DIRECTORIES;

return $this;
}








public function files()
{
$this->mode = Iterator\FileTypeFilterIterator::ONLY_FILES;

return $this;
}


















public function depth($level)
{
$this->depths[] = new Comparator\NumberComparator($level);

return $this;
}





















public function date($date)
{
$this->dates[] = new Comparator\DateComparator($date);

return $this;
}


















public function name($pattern)
{
$this->names[] = $pattern;

return $this;
}












public function notName($pattern)
{
$this->notNames[] = $pattern;

return $this;
}















public function contains($pattern)
{
$this->contains[] = $pattern;

return $this;
}















public function notContains($pattern)
{
$this->notContains[] = $pattern;

return $this;
}

















public function path($pattern)
{
$this->paths[] = $pattern;

return $this;
}

















public function notPath($pattern)
{
$this->notPaths[] = $pattern;

return $this;
}

















public function size($size)
{
$this->sizes[] = new Comparator\NumberComparator($size);

return $this;
}












public function exclude($dirs)
{
$this->exclude = array_merge($this->exclude, (array) $dirs);

return $this;
}












public function ignoreDotFiles($ignoreDotFiles)
{
if ($ignoreDotFiles) {
$this->ignore = $this->ignore | static::IGNORE_DOT_FILES;
} else {
$this->ignore = $this->ignore & ~static::IGNORE_DOT_FILES;
}

return $this;
}












public function ignoreVCS($ignoreVCS)
{
if ($ignoreVCS) {
$this->ignore = $this->ignore | static::IGNORE_VCS_FILES;
} else {
$this->ignore = $this->ignore & ~static::IGNORE_VCS_FILES;
}

return $this;
}








public static function addVCSPattern($pattern)
{
foreach ((array) $pattern as $p) {
self::$vcsPatterns[] = $p;
}

self::$vcsPatterns = array_unique(self::$vcsPatterns);
}
















public function sort(\Closure $closure)
{
$this->sort = $closure;

return $this;
}












public function sortByName()
{
$this->sort = Iterator\SortableIterator::SORT_BY_NAME;

return $this;
}












public function sortByType()
{
$this->sort = Iterator\SortableIterator::SORT_BY_TYPE;

return $this;
}














public function sortByAccessedTime()
{
$this->sort = Iterator\SortableIterator::SORT_BY_ACCESSED_TIME;

return $this;
}
















public function sortByChangedTime()
{
$this->sort = Iterator\SortableIterator::SORT_BY_CHANGED_TIME;

return $this;
}














public function sortByModifiedTime()
{
$this->sort = Iterator\SortableIterator::SORT_BY_MODIFIED_TIME;

return $this;
}















public function filter(\Closure $closure)
{
$this->filters[] = $closure;

return $this;
}








public function followLinks()
{
$this->followLinks = true;

return $this;
}










public function ignoreUnreadableDirs($ignore = true)
{
$this->ignoreUnreadableDirs = (Boolean) $ignore;

return $this;
}












public function in($dirs)
{
$resolvedDirs = array();

foreach ((array) $dirs as $dir) {
if (is_dir($dir)) {
$resolvedDirs[] = $dir;
} elseif ($glob = glob($dir, GLOB_ONLYDIR)) {
$resolvedDirs = array_merge($resolvedDirs, $glob);
} else {
throw new \InvalidArgumentException(sprintf('The "%s" directory does not exist.', $dir));
}
}

$this->dirs = array_merge($this->dirs, $resolvedDirs);

return $this;
}










public function getIterator()
{
if (0 === count($this->dirs) && 0 === count($this->iterators)) {
throw new \LogicException('You must call one of in() or append() methods before iterating over a Finder.');
}

if (1 === count($this->dirs) && 0 === count($this->iterators)) {
return $this->searchInDirectory($this->dirs[0]);
}

$iterator = new \AppendIterator();
foreach ($this->dirs as $dir) {
$iterator->append($this->searchInDirectory($dir));
}

foreach ($this->iterators as $it) {
$iterator->append($it);
}

return $iterator;
}












public function append($iterator)
{
if ($iterator instanceof \IteratorAggregate) {
$this->iterators[] = $iterator->getIterator();
} elseif ($iterator instanceof \Iterator) {
$this->iterators[] = $iterator;
} elseif ($iterator instanceof \Traversable || is_array($iterator)) {
$it = new \ArrayIterator();
foreach ($iterator as $file) {
$it->append($file instanceof \SplFileInfo ? $file : new \SplFileInfo($file));
}
$this->iterators[] = $it;
} else {
throw new \InvalidArgumentException('Finder::append() method wrong argument type.');
}

return $this;
}






public function count()
{
return iterator_count($this->getIterator());
}




private function sortAdapters()
{
uasort($this->adapters, function (array $a, array $b) {
if ($a['selected'] || $b['selected']) {
return $a['selected'] ? -1 : 1;
}

return $a['priority'] > $b['priority'] ? -1 : 1;
});

return $this;
}








private function searchInDirectory($dir)
{
if (static::IGNORE_VCS_FILES === (static::IGNORE_VCS_FILES & $this->ignore)) {
$this->exclude = array_merge($this->exclude, self::$vcsPatterns);
}

if (static::IGNORE_DOT_FILES === (static::IGNORE_DOT_FILES & $this->ignore)) {
$this->notPaths[] = '#(^|/)\..+(/|$)#';
}

foreach ($this->adapters as $adapter) {
if ($adapter['adapter']->isSupported()) {
try {
return $this
->buildAdapter($adapter['adapter'])
->searchInDirectory($dir);
} catch (ExceptionInterface $e) {}
}
}

throw new \RuntimeException('No supported adapter found.');
}






private function buildAdapter(AdapterInterface $adapter)
{
return $adapter
->setFollowLinks($this->followLinks)
->setDepths($this->depths)
->setMode($this->mode)
->setExclude($this->exclude)
->setNames($this->names)
->setNotNames($this->notNames)
->setContains($this->contains)
->setNotContains($this->notContains)
->setSizes($this->sizes)
->setDates($this->dates)
->setFilters($this->filters)
->setSort($this->sort)
->setPath($this->paths)
->setNotPath($this->notPaths)
->ignoreUnreadableDirs($this->ignoreUnreadableDirs);
}




private function resetAdapterSelection()
{
$this->adapters = array_map(function (array $properties) {
$properties['selected'] = false;

return $properties;
}, $this->adapters);
}
}
