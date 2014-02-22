<?php










namespace Symfony\Component\Finder\Iterator;








abstract class FilterIterator extends \FilterIterator
{






public function rewind()
{
$iterator = $this;
while ($iterator instanceof \OuterIterator) {
$innerIterator = $iterator->getInnerIterator();

if ($innerIterator instanceof RecursiveDirectoryIterator) {
if ($innerIterator->isRewindable()) {
$innerIterator->next();
$innerIterator->rewind();
}
} elseif ($iterator->getInnerIterator() instanceof \FilesystemIterator) {
$iterator->getInnerIterator()->next();
$iterator->getInnerIterator()->rewind();
}
$iterator = $iterator->getInnerIterator();
}

parent::rewind();
}
}
