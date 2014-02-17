<?php












namespace Composer\Autoload;
use Symfony\Component\Finder\Finder;






class ClassMapGenerator
{






public static function dump($dirs, $file)
{
$maps = array();

foreach ($dirs as $dir) {
$maps = array_merge($maps, static::createMap($dir));
}

file_put_contents($file, sprintf('<?php return %s;', var_export($maps, true)));
}











public static function createMap($path, $whitelist = null)
{
if (is_string($path)) {
if (is_file($path)) {
$path = array(new \SplFileInfo($path));
} elseif (is_dir($path)) {
$path = Finder::create()->files()->followLinks()->name('/\.(php|inc)$/')->in($path);
} else {
throw new \RuntimeException(
'Could not scan for classes inside "'.$path.
'" which does not appear to be a file nor a folder'
);
}
}

$map = array();

foreach ($path as $file) {
$filePath = $file->getRealPath();

if (!in_array(pathinfo($filePath, PATHINFO_EXTENSION), array('php', 'inc'))) {
continue;
}

if ($whitelist && !preg_match($whitelist, strtr($filePath, '\\', '/'))) {
continue;
}

$classes = self::findClasses($filePath);

foreach ($classes as $class) {
$map[$class] = $filePath;
}
}

return $map;
}








private static function findClasses($path)
{
$traits = version_compare(PHP_VERSION, '5.4', '<') ? '' : '|trait';

try {
$contents = php_strip_whitespace($path);
} catch (\Exception $e) {
throw new \RuntimeException('Could not scan for classes inside '.$path.": \n".$e->getMessage(), 0, $e);
}


 if (!preg_match('{\b(?:class|interface'.$traits.')\s}i', $contents)) {
return array();
}


 $contents = preg_replace('{<<<\'?(\w+)\'?(?:\r\n|\n|\r)(?:.*?)(?:\r\n|\n|\r)\\1(?=\r\n|\n|\r|;)}s', 'null', $contents);

 $contents = preg_replace('{"[^"\\\\]*(\\\\.[^"\\\\]*)*"|\'[^\'\\\\]*(\\\\.[^\'\\\\]*)*\'}s', 'null', $contents);

 if (substr($contents, 0, 2) !== '<?') {
$contents = preg_replace('{^.+?<\?}s', '<?', $contents);
}

 $contents = preg_replace('{\?>.+<\?}s', '?><?', $contents);

 $pos = strrpos($contents, '?>');
if (false !== $pos && false === strpos(substr($contents, $pos), '<?')) {
$contents = substr($contents, 0, $pos);
}

preg_match_all('{
            (?:
                 \b(?<![\$:>])(?P<type>class|interface'.$traits.') \s+ (?P<name>[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)
               | \b(?<![\$:>])(?P<ns>namespace) (?P<nsname>\s+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?:\s*\\\\\s*[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)*)? \s*[\{;]
            )
        }ix', $contents, $matches);

$classes = array();
$namespace = '';

for ($i = 0, $len = count($matches['type']); $i < $len; $i++) {
if (!empty($matches['ns'][$i])) {
$namespace = str_replace(array(' ', "\t", "\r", "\n"), '', $matches['nsname'][$i]) . '\\';
} else {
$classes[] = ltrim($namespace . $matches['name'][$i], '\\');
}
}

return $classes;
}
}
