<?php
//Script to convert file data to tree folder

function filePCItoTreeFolder()
{
    if(!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/pciids"))
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

            if (!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/pciids/$vendorId"))
            {
                mkdir (LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/pciids/$vendorId",0777,true);
            }
        }

        $stack = array();
        if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $deviceId = $stack[1];
            $deviceName=$stack[2];
            $fd = fopen(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/pciids/$vendorId/$deviceId.info", 'w');
            fputs($fd, "$vendorName\n$deviceName");
            fclose ($fd);
        }
    }
    }
}

function fileUSBtoTreeFolder()
{
    if(!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/usbids"))
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

            if (!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/usbids/$vendorId"))
            {
                mkdir (LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/usbids/$vendorId",0777,true);
            }
        }

        $stack = array();
        if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack))
        {
            $deviceId = $stack[1];
            $deviceName=$stack[2];
            $fd = fopen(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/usbids/$vendorId/$deviceId.info", 'w');
            fputs($fd, "$vendorName\n$deviceName");
            fclose ($fd);
        }
    }
    }
}

function fileOUItoTreeFolder()
{
    if(!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/oui"))
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

            if (!is_dir(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/oui/$OUI/$organization"))
            {
                mkdir (LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/oui/$OUI/$organization",0777,true);
            }

        }
    }
    }
}
?>
