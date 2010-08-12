<?php
/**
* We manage actions here (snmp query, netdiscovery, wakeonlan, inventory)
*/
class ActionFactory
{
    public static function createAction($nameAction)
    {
        $baseClass = 'Action';
        $targetClass = ucfirst($nameAction).$baseClass;

        if (file_exists ($path=dirname(__FILE__) .'/Action/'.$targetClass.'.class.php'))
        {
            require_once $path;
            if (class_exists($targetClass) && is_subclass_of($targetClass, $baseClass))
            {
                return new $targetClass;
            } else {
                throw new MyException("The action '$nameAction' is not recognized.");
            }
        }
    }
}

abstract class Action
{
    abstract function checkConfig($applicationName, $config);
    abstract function startAction($simpleXMLObj);
}

?>