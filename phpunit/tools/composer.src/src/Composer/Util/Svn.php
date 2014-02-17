<?php











namespace Composer\Util;

use Composer\IO\IOInterface;





class Svn
{
const MAX_QTY_AUTH_TRIES = 5;




protected $credentials;




protected $hasAuth;




protected $io;




protected $url;




protected $cacheCredentials = true;




protected $process;




protected $qtyAuthTries = 0;






public function __construct($url, IOInterface $io, ProcessExecutor $process = null)
{
$this->url = $url;
$this->io = $io;
$this->process = $process ?: new ProcessExecutor;
}















public function execute($command, $url, $cwd = null, $path = null, $verbose = false)
{
$svnCommand = $this->getCommand($command, $url, $path);
$output = null;
$io = $this->io;
$handler = function ($type, $buffer) use (&$output, $io, $verbose) {
if ($type !== 'out') {
return;
}
if ('Redirecting to URL ' === substr($buffer, 0, 19)) {
return;
}
$output .= $buffer;
if ($verbose) {
$io->write($buffer, false);
}
};
$status = $this->process->execute($svnCommand, $handler, $cwd);
if (0 === $status) {
return $output;
}

if (empty($output)) {
$output = $this->process->getErrorOutput();
}


 if (false === stripos($output, 'Could not authenticate to server:')
&& false === stripos($output, 'authorization failed')
&& false === stripos($output, 'svn: E170001:')
&& false === stripos($output, 'svn: E215004:')) {
throw new \RuntimeException($output);
}


 if (!$this->io->isInteractive()) {
throw new \RuntimeException(
'can not ask for authentication in non interactive mode ('.$output.')'
);
}


 if ($this->qtyAuthTries++ < self::MAX_QTY_AUTH_TRIES || !$this->hasAuth()) {
$this->doAuthDance();


 return $this->execute($command, $url, $cwd, $path, $verbose);
}

throw new \RuntimeException(
'wrong credentials provided ('.$output.')'
);
}






protected function doAuthDance()
{
$this->io->write("The Subversion server ({$this->url}) requested credentials:");

$this->hasAuth = true;
$this->credentials['username'] = $this->io->ask("Username: ");
$this->credentials['password'] = $this->io->askAndHideAnswer("Password: ");

$this->cacheCredentials = $this->io->askConfirmation("Should Subversion cache these credentials? (yes/no) ", true);

return $this;
}










protected function getCommand($cmd, $url, $path = null)
{
$cmd = sprintf('%s %s%s %s',
$cmd,
'--non-interactive ',
$this->getCredentialString(),
escapeshellarg($url)
);

if ($path) {
$cmd .= ' ' . escapeshellarg($path);
}

return $cmd;
}








protected function getCredentialString()
{
if (!$this->hasAuth()) {
return '';
}

return sprintf(
' %s--username %s --password %s ',
$this->getAuthCache(),
escapeshellarg($this->getUsername()),
escapeshellarg($this->getPassword())
);
}







protected function getPassword()
{
if ($this->credentials === null) {
throw new \LogicException("No svn auth detected.");
}

return isset($this->credentials['password']) ? $this->credentials['password'] : '';
}







protected function getUsername()
{
if ($this->credentials === null) {
throw new \LogicException("No svn auth detected.");
}

return $this->credentials['username'];
}






protected function hasAuth()
{
if (null !== $this->hasAuth) {
return $this->hasAuth;
}

$uri = parse_url($this->url);
if (empty($uri['user'])) {
return $this->hasAuth = false;
}

$this->credentials['username'] = $uri['user'];
if (!empty($uri['pass'])) {
$this->credentials['password'] = $uri['pass'];
}

return $this->hasAuth = true;
}






protected function getAuthCache()
{
return $this->cacheCredentials ? '' : '--no-auth-cache ';
}
}
