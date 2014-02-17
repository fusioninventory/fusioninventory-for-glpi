<?php











namespace Composer\Package\Archiver;

use Composer\Util\Filesystem;

use Symfony\Component\Finder;









class ArchivableFilesFinder extends \FilterIterator
{



protected $finder;







public function __construct($sources, array $excludes)
{
$fs = new Filesystem();

$sources = $fs->normalizePath($sources);

$filters = array(
new HgExcludeFilter($sources),
new GitExcludeFilter($sources),
new ComposerExcludeFilter($sources, $excludes),
);

$this->finder = new Finder\Finder();

$filter = function (\SplFileInfo $file) use ($sources, $filters, $fs) {
$relativePath = preg_replace(
'#^'.preg_quote($sources, '#').'#',
'',
$fs->normalizePath($file->getRealPath())
);

$exclude = false;
foreach ($filters as $filter) {
$exclude = $filter->filter($relativePath, $exclude);
}

return !$exclude;
};

if (method_exists($filter, 'bindTo')) {
$filter = $filter->bindTo(null);
}

$this->finder
->in($sources)
->filter($filter)
->ignoreVCS(true)
->ignoreDotFiles(false);

parent::__construct($this->finder->getIterator());
}

public function accept()
{
return !$this->getInnerIterator()->current()->isDir();
}
}
