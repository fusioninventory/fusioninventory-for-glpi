<?php











namespace Composer\IO;

use Composer\Config;

abstract class BaseIO implements IOInterface
{
protected $authentications = array();




public function getAuthentications()
{
return $this->authentications;
}




public function hasAuthentication($repositoryName)
{
return isset($this->authentications[$repositoryName]);
}




public function getAuthentication($repositoryName)
{
if (isset($this->authentications[$repositoryName])) {
return $this->authentications[$repositoryName];
}

return array('username' => null, 'password' => null);
}




public function setAuthentication($repositoryName, $username, $password = null)
{
$this->authentications[$repositoryName] = array('username' => $username, 'password' => $password);
}




public function loadConfiguration(Config $config)
{

 if ($tokens = $config->get('github-oauth')) {
foreach ($tokens as $domain => $token) {
if (!preg_match('{^[a-z0-9]+$}', $token)) {
throw new \UnexpectedValueException('Your github oauth token for '.$domain.' contains invalid characters: "'.$token.'"');
}
$this->setAuthentication($domain, $token, 'x-oauth-basic');
}
}
}
}
