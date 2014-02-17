<?php











namespace Composer\IO;

use Composer\Config;






interface IOInterface
{





public function isInteractive();






public function isVerbose();






public function isVeryVerbose();






public function isDebug();






public function isDecorated();







public function write($messages, $newline = true);








public function overwrite($messages, $newline = true, $size = null);











public function ask($question, $default = null);











public function askConfirmation($question, $default = true);

















public function askAndValidate($question, $validator, $attempts = false, $default = null);








public function askAndHideAnswer($question);






public function getAuthentications();








public function hasAuthentication($repositoryName);








public function getAuthentication($repositoryName);








public function setAuthentication($repositoryName, $username, $password = null);






public function loadConfiguration(Config $config);
}
