<?php
//Script to convert file data to sql

filePCItoDB();
fileUSBtoDB();


function filePCItoDB() {
   $pciFile = fopen("pci.ids","r");

   $sql_creation = "DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_pcivendors`;

CREATE TABLE `glpi_plugin_fusinvinventory_pcivendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendorid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendorid` (`vendorid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_pcidevices`;

CREATE TABLE `glpi_plugin_fusinvinventory_pcidevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_fusinvinventory_pcivendor_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deviceid` (`deviceid`),
  KEY `plugin_fusinvinventory_pcivendor_id` (`plugin_fusinvinventory_pcivendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


";


   $sql_insert_vendor = "INSERT INTO `glpi_plugin_fusinvinventory_pcivendors`
      (`id`, `vendorid`, `name`) VALUES ";
   $sql_insert_device = "INSERT INTO `glpi_plugin_fusinvinventory_pcidevices`
      (`id`, `deviceid`, `name`, `plugin_fusinvinventory_pcivendor_id`) VALUES ";

   $v = 0;
   $d = 0;
   while(!feof($pciFile)) {
      $buffer = fgets($pciFile, 4096);

      $stack = array();
      if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack)) {
         $v++;
         $vendorId = $stack[1];
         $vendorName = $stack[2];

         $sql_insert_vendor .= "\n(".$v.", '".$vendorId."', '".addslashes(htmlentities($vendorName))."'),";

      }

      $stack = array();
      if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack)) {
         $d++;
         $deviceId = $stack[1];
         $deviceName=$stack[2];

         $sql_insert_device .= "\n(".$d.", '".$deviceId."', '".addslashes(htmlentities($deviceName))."', '".$v."'),";
      }
   }

   $sql_insert_vendor .= ";";
   $sql_insert_vendor = str_replace(",;", ";\n", $sql_insert_vendor);

   $sql_insert_device .= ";";
   $sql_insert_device = str_replace(",;", ";\n", $sql_insert_device);
   
   file_put_contents("../install/mysql/pciid.sql", $sql_creation.$sql_insert_vendor."\n\n".$sql_insert_device);
}



function fileUSBtoDB() {
    $usbFile = fopen("usb.ids","r");

   $sql_creation = "DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_usbvendors`;

CREATE TABLE `glpi_plugin_fusinvinventory_usbvendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendorid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendorid` (`vendorid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusinvinventory_usbdevices`;

CREATE TABLE `glpi_plugin_fusinvinventory_usbdevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_fusinvinventory_usbvendor_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deviceid` (`deviceid`),
  KEY `plugin_fusinvinventory_usbvendor_id` (`plugin_fusinvinventory_usbvendor_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


";

   $sql_insert_vendor = "INSERT INTO `glpi_plugin_fusinvinventory_usbvendors`
      (`id`, `vendorid`, `name`) VALUES ";
   $sql_insert_device = "INSERT INTO `glpi_plugin_fusinvinventory_usbdevices`
      (`id`, `deviceid`, `name`, `plugin_fusinvinventory_usbvendor_id`) VALUES ";


   $v = 0;
   $d = 0;
   while(!feof($usbFile)) {
      $buffer = fgets($usbFile, 4096);

      $stack = array();
      if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack)) {
         $v++;
         $vendorId = $stack[1];
         $vendorName = $stack[2];

         $sql_insert_vendor .= "\n(".$v.", '".$vendorId."', '".addslashes(htmlentities($vendorName))."'),";
      }

      $stack = array();
      if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack)) {
         $d++;
         $deviceId = $stack[1];
         $deviceName=$stack[2];

          $sql_insert_device .= "\n(".$d.", '".$deviceId."', '".addslashes(htmlentities($deviceName))."', '".$v."'),";
      }
   }

   $sql_insert_vendor .= ";";
   $sql_insert_vendor = str_replace(",;", ";\n", $sql_insert_vendor);

   $sql_insert_device .= ";";
   $sql_insert_device = str_replace(",;", ";\n", $sql_insert_device);

   file_put_contents("../install/mysql/usbid.sql", $sql_creation.$sql_insert_vendor."\n\n".$sql_insert_device);
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
