<?php
//Script to convert file data to tree folder

filePCItoTreeFolder();
fileUSBtoTreeFolder();
fileOUItoTreeFolder();


function filePCItoTreeFolder()
{
    $pciFile = fopen(dirname(__FILE__)."/pci.ids","r");

    while(!feof($pciFile))
    {
        $buffer = fgets($pciFile, 4096);

        $stack = array();
        if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $vendorId = $stack[1];
            $vendorName = $stack[2];

            if (!is_dir(dirname(__FILE__)."/pciids/$vendorId"))
            {
                mkdir (dirname(__FILE__)."/pciids/$vendorId",0777,true);
            }
        }

        $stack = array();
        if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $deviceId = $stack[1];
            $deviceName=$stack[2];
            $fd = fopen(dirname(__FILE__)."/pciids/$vendorId/$deviceId.info", 'w');
            fputs($fd, "$vendorName\n$deviceName");
            fclose ($fd);
        }
    }
}

function fileUSBtoTreeFolder()
{
    $usbFile = fopen(dirname(__FILE__)."/usb.ids","r");

    while(!feof($usbFile))
    {
        $buffer = fgets($usbFile, 4096);

        $stack = array();
        if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $vendorId = $stack[1];
            $vendorName = $stack[2];

            if (!is_dir(dirname(__FILE__)."/usbids/$vendorId"))
            {
                mkdir (dirname(__FILE__)."/usbids/$vendorId",0777,true);
            }
        }

        $stack = array();
        if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $deviceId = $stack[1];
            $deviceName=$stack[2];
            $fd = fopen(dirname(__FILE__)."/usbids/$vendorId/$deviceId.info", 'w');
            fputs($fd, "$vendorName\n$deviceName");
            fclose ($fd);
        }
    }
}

function fileOUItoTreeFolder()
{
    $ouiFile = fopen(dirname(__FILE__)."/oui.txt","r");

    while(!feof($ouiFile))
    {
        $buffer = fgets($ouiFile, 4096);

        $stack = array();
        if (preg_match("/^(\S+)\s*\(hex\)\t{2}(.+)/i", $buffer, $stack))
        {

            $OUI = $stack[1];
            $OUI = strtr($OUI, "-", ":");
            $organization = $stack[2];

            if (!is_dir(dirname(__FILE__)."/oui/$OUI/$organization"))
            {
                mkdir (dirname(__FILE__)."/oui/$OUI/$organization",0777,true);
            }

        }
    }
}
?>
