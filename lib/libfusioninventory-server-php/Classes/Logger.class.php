<?php

/** A logging class.
* Examples:
* $log = new Logger("logFilePath");
* $log->notifyDebugMessage("machine $internalId created");
* $log->notifyExceptionMessage("Put your application in the user/applications directory");
*/

class Logger
{
    const EXCEPT = 1;
    const DEBUG = 2;

    private $_fileHandle;

    public function __construct()
    {
        if(!file_exists(LIBSERVERFUSIONINVENTORY_LOG_DIR))
        {
            mkdir(LIBSERVERFUSIONINVENTORY_LOG_DIR,0777,true);
        }

        $this->_fileHandle = fopen(LIBSERVERFUSIONINVENTORY_LOG_FILE, "a");
    }


    public function notifyDebugMessage($line)
    {
        //echo "\n $line";
        $this->_log($line, Logger::DEBUG);
    }


    public function notifyExceptionMessage($line)
    {
        if(LIBSERVERFUSIONINVENTORY_PRINTERROR)
        {
            echo "\n $line";
        }
        $this->_log($line, Logger::EXCEPT);
        if(LIBSERVERFUSIONINVENTORY_PRINTERROR)
        {
            echo "\n $line";
        }
    }


    private function _log($line, $messageType)
    {
        $status = $this->_getStatus($messageType);
        fputs($this->_fileHandle, "$status $line \n");
    }


    private function _getStatus($messageType)
    {
        $time = date("d M Y - H:i:s");

        switch($messageType)
        {
            case Logger::DEBUG:
                $status = "$time - DEBUG -->";
            break;
            case Logger::EXCEPT:
                $status = "$time - EXCEPTION -->";
            break;
            default:
                $status = "$time - LOG   -->";
            break;
        }
        return $status;
    }

}


?>
