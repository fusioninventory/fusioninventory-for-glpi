<?php










namespace Symfony\Component\Finder\Iterator;

use Symfony\Component\Finder\Expression\Expression;






abstract class MultiplePcreFilterIterator extends FilterIterator
{
protected $matchRegexps = array();
protected $noMatchRegexps = array();








public function __construct(\Iterator $iterator, array $matchPatterns, array $noMatchPatterns)
{
foreach ($matchPatterns as $pattern) {
$this->matchRegexps[] = $this->toRegex($pattern);
}

foreach ($noMatchPatterns as $pattern) {
$this->noMatchRegexps[] = $this->toRegex($pattern);
}

parent::__construct($iterator);
}








protected function isRegex($str)
{
return Expression::create($str)->isRegex();
}








abstract protected function toRegex($str);
}
