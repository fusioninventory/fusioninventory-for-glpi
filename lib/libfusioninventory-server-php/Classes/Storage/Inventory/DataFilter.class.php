<?php
/**
* Filter some input data :
* - if PCIID or USBID (CONTROLLERS) are avalaible, retrieve manufacturer/device from the mapping files.
* - retrieve the network interface manufacturer from oui database
*/
class DataFilter
{

    public static function init()
    {
        require_once dirname(__FILE__) . '/SourceDataFilter/FilesToTreeFolder.php';
        filePCItoTreeFolder();
        fileUSBtoTreeFolder();
        fileOUItoTreeFolder();
    }

    /**
    * get device from pciid
    * @access public
    * @param string $pciid
    */
    public static function filter($section)
    {
        $log = new Logger();
        switch($section->getName())
        {
            case 'CONTROLLERS':
                if(!file_exists(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/pciids"))
                {
                    $log->notifyDebugMessage("You have to create tree folders for PCI, no filter for CONTROLLERS");
                    $nofilter = 'CONTROLLERS';
                    return $nofilter;
                }
                if(isset($section->PCIID) AND $section->PCIID != '')
                {
                    $manufacturer = self::_getDataFromPCIID($section->PCIID);
                    $section->MANUFACTURER = $manufacturer;
                }

            break;

            case 'NETWORKS':
                if(!file_exists(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/oui"))
                {
                    $log->notifyDebugMessage("You have to create tree folders for OUI, no filter for NETWORKS");
                    $nofilter = 'NETWORKS';
                    return $nofilter;
                }
                if(isset($section->MACADDR) AND $section->MACADDR != '')
                {
                    //Mac address is locally or universal ?
                    $msByte = substr($section->MACADDR, 0, 2);
                    $msBin = decbin(hexdec($msByte));
                    if (substr($msBin, -2, 1) != 1)
                    {
                        //second bit isn't 1, the mac address isn't locally
                        $manufacturer = self::_getDataFromMACADDR($section->MACADDR);
                        $section->addChild('MANUFACTURER', $manufacturer);
                    }
                }
            break;

            case 'USBDEVICES':
                if(!file_exists(LIBSERVERFUSIONINVENTORY_STORAGELOCATION."/DataFilter/usbids"))
                {
                    $log->notifyDebugMessage("You have to create tree folders for USB, no filter for USB DEVICES");
                    $nofilter = 'USBDEVICES';
                    return $nofilter;
                }
                if(isset($section->VENDORID) AND $section->VENDORID != ''
                AND isset($section->PRODUCTID))
                {
                    $dataArray = self::_getDataFromUSBID($section->VENDORID, $section->PRODUCTID);

                    $section->addChild('MANUFACTURER', $dataArray[0]);
                    $section->addChild('PRODUCTNAME', $dataArray[1]);
                    
                }

            break;

            default:
            break;
        }
        return false;
    }

    /**
    * get manufacturer from pciid
    * @access private
    * @param string $pciid
    */
    private static function _getDataFromPCIID($pciid)
    {
        $pciidArray = explode(":", $pciid);
        $vendorId = $pciidArray[0];
        $deviceId = $pciidArray[1];

        $dataPath = sprintf('%s/%s/%s/%s/%s',
        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
        "DataFilter",
        "pciids",
        $vendorId,
        "$deviceId.info");

        $dataArray = explode("\n", file_get_contents($dataPath));
        $vendorName = $dataArray[0];
        $deviceName = $dataArray[1];

        $manufacturer = "$vendorName $deviceName";

        return $manufacturer;

    }

    /**
    * get data from macaddr
    * @access private
    * @param string $macaddr
    */
    private static function _getDataFromMACADDR($macaddr)
    {

        $macOUI = substr($macaddr, 0, 8);

        $dataPath = sprintf('%s/%s/%s/%s',
        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
        "DataFilter",
        "oui",
        strtoupper($macOUI));
        
        if (is_dir($dataPath)) {
            $manufacturer = scandir($dataPath);

            return $manufacturer[2];
        }
    }

    /**
    * get data from vendorid and productid
    * @access private
    * @param string $usbid
    */
    private static function _getDataFromUSBID($vendorId, $productId)
    {

        $dataPath = sprintf('%s/%s/%s/%s/%s',
        LIBSERVERFUSIONINVENTORY_STORAGELOCATION,
        "DataFilter",
        "usbids",
        strtolower($vendorId),
        strtolower($productId).".info");

        $dataArray = explode("\n", file_get_contents($dataPath));

        return $dataArray;
    }


}


?>