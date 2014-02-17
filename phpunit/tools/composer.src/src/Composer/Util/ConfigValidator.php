<?php











namespace Composer\Util;

use Composer\Package\Loader\ArrayLoader;
use Composer\Package\Loader\ValidatingArrayLoader;
use Composer\Package\Loader\InvalidPackageException;
use Composer\Json\JsonValidationException;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;







class ConfigValidator
{
private $io;

public function __construct(IOInterface $io)
{
$this->io = $io;
}








public function validate($file)
{
$errors = array();
$publishErrors = array();
$warnings = array();


 $laxValid = false;
try {
$json = new JsonFile($file, new RemoteFilesystem($this->io));
$manifest = $json->read();

$json->validateSchema(JsonFile::LAX_SCHEMA);
$laxValid = true;
$json->validateSchema();
} catch (JsonValidationException $e) {
foreach ($e->getErrors() as $message) {
if ($laxValid) {
$publishErrors[] = $message;
} else {
$errors[] = $message;
}
}
} catch (\Exception $e) {
$errors[] = $e->getMessage();

return array($errors, $publishErrors, $warnings);
}


 if (!empty($manifest['license'])) {

 if (is_array($manifest['license'])) {
foreach ($manifest['license'] as $key => $license) {
if ('proprietary' === $license) {
unset($manifest['license'][$key]);
}
}
}

$licenseValidator = new SpdxLicenseIdentifier();
if ('proprietary' !== $manifest['license'] && array() !== $manifest['license'] && !$licenseValidator->validate($manifest['license'])) {
$warnings[] = sprintf(
'License %s is not a valid SPDX license identifier, see http://www.spdx.org/licenses/ if you use an open license.'
."\nIf the software is closed-source, you may use \"proprietary\" as license.",
json_encode($manifest['license'])
);
}
} else {
$warnings[] = 'No license specified, it is recommended to do so. For closed-source software you may use "proprietary" as license.';
}

if (!empty($manifest['name']) && preg_match('{[A-Z]}', $manifest['name'])) {
$suggestName = preg_replace('{(?:([a-z])([A-Z])|([A-Z])([A-Z][a-z]))}', '\\1\\3-\\2\\4', $manifest['name']);
$suggestName = strtolower($suggestName);

$warnings[] = sprintf(
'Name "%s" does not match the best practice (e.g. lower-cased/with-dashes). We suggest using "%s" instead. As such you will not be able to submit it to Packagist.',
$manifest['name'],
$suggestName
);
}

if (!empty($manifest['type']) && $manifest['type'] == 'composer-installer') {
$warnings[] = "The package type 'composer-installer' is deprecated. Please distribute your custom installers as plugins from now on. See http://getcomposer.org/doc/articles/plugins.md for plugin documentation.";
}


 if (isset($manifest['require']) && isset($manifest['require-dev'])) {
$requireOverrides = array_intersect_key($manifest['require'], $manifest['require-dev']);

if (!empty($requireOverrides)) {
$plural = (count($requireOverrides) > 1) ? 'are' : 'is';
$warnings[] = implode(', ', array_keys($requireOverrides)). " {$plural} required both in require and require-dev, this can lead to unexpected behavior";
}
}

try {
$loader = new ValidatingArrayLoader(new ArrayLoader());
if (!isset($manifest['version'])) {
$manifest['version'] = '1.0.0';
}
if (!isset($manifest['name'])) {
$manifest['name'] = 'dummy/dummy';
}
$loader->load($manifest);
} catch (InvalidPackageException $e) {
$errors = array_merge($errors, $e->getErrors());
}

$warnings = array_merge($warnings, $loader->getWarnings());

return array($errors, $publishErrors, $warnings);
}
}
