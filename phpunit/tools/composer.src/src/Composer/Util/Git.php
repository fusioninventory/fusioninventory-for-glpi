<?php











namespace Composer\Util;




class Git
{
public function cleanEnv()
{
if (ini_get('safe_mode') && false === strpos(ini_get('safe_mode_allowed_env_vars'), 'GIT_ASKPASS')) {
throw new \RuntimeException('safe_mode is enabled and safe_mode_allowed_env_vars does not contain GIT_ASKPASS, can not set env var. You can disable safe_mode with "-dsafe_mode=0" when running composer');
}


 if (getenv('GIT_ASKPASS') !== 'echo') {
putenv('GIT_ASKPASS=echo');
}


 if (getenv('GIT_DIR')) {
putenv('GIT_DIR');
}
if (getenv('GIT_WORK_TREE')) {
putenv('GIT_WORK_TREE');
}
}
}
