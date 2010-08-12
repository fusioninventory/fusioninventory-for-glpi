<?php
set_include_path(dirname(__FILE__) . PATH_SEPARATOR . get_include_path());
require_once dirname(__FILE__) ."/../Classes/FusionLibServer.class.php";
require_once dirname(__FILE__) ."/../Classes/MyException.class.php";
require_once dirname(__FILE__) ."/../Classes/Logger.class.php";

$configs = parse_ini_file(dirname(__FILE__) ."/configs.ini", true);

if (file_exists ($path=dirname(__FILE__) ."/applications/{$configs['application']['name']}/FusInvHooks.class.php"))
{
    require_once $path;
} else {
    throw new MyException ("you have to put FusInvHooks class in applications/{$configs['application']['name']}/ directory");
}
$fusionLibServer = FusionLibServer::getInstance();

$fusionLibServer->setApplicationName($configs['application']['name']);
$fusionLibServer->setPrologFreq($configs['prolog']['freq']);

//We set configs for each action
foreach($configs['actions'] as $action){
    $fusionLibServer->setActionConfig($action, $configs[$action]);
}
$fusionLibServer->start();
?>
