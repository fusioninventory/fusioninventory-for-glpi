<?php
//Script to convert file data to sql

filePCItoDB();
fileUSBtoDB();
fileOUItoDB();


function filePCItoDB() {
   $pciFile = fopen("pci.ids", "r");

   $sql_creation = "DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_pcivendors`;

CREATE TABLE `glpi_plugin_fusioninventory_pcivendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendorid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendorid` (`vendorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_pcidevices`;

CREATE TABLE `glpi_plugin_fusioninventory_pcidevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_fusioninventory_pcivendor_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deviceid` (`deviceid`),
  KEY `plugin_fusioninventory_pcivendor_id` (`plugin_fusioninventory_pcivendor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


";

   $sql_insert_vendor = "INSERT INTO `glpi_plugin_fusioninventory_pcivendors`
      (`id`, `vendorid`, `name`) VALUES ";
   $sql_insert_device = "INSERT INTO `glpi_plugin_fusioninventory_pcidevices`
      (`id`, `deviceid`, `name`, `plugin_fusioninventory_pcivendor_id`) VALUES ";

   $v = 0;
   $d = 0;
   $nblines_vendor = 0;
   $nblines_device = 0;
   while (!feof($pciFile)) {
      $buffer = fgets($pciFile, 4096);

      $stack = [];
      if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack)) {
         if ($nblines_vendor > 10000) {
            $sql_insert_vendor .= ";";
            $sql_insert_vendor .= "INSERT INTO `glpi_plugin_fusioninventory_pcivendors`
               (`id`, `vendorid`, `name`) VALUES ";
            $nblines_vendor = 0;
         }
         $v++;
         $vendorId = $stack[1];
         $vendorName = $stack[2];

         $sql_insert_vendor .= "\n(".$v.", '".$vendorId."', '".addslashes(htmlentities($vendorName))."'),";
         $nblines_vendor++;
      }

      $stack = [];
      if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack)) {
         if ($nblines_device > 10000) {
            $sql_insert_device .= ";";
            $sql_insert_device .= "INSERT INTO `glpi_plugin_fusioninventory_pcidevices`
               (`id`, `deviceid`, `name`, `plugin_fusioninventory_pcivendor_id`) VALUES ";
            $nblines_device = 0;
         }
         $d++;
         $deviceId = $stack[1];
         $deviceName=$stack[2];

         $sql_insert_device .= "\n(".$d.", '".$deviceId."', '".addslashes(htmlentities($deviceName))."', '".$v."'),";
         $nblines_device++;
      }
   }

   $sql_insert_vendor .= ";";
   $sql_insert_vendor = str_replace(",;", ";\n", $sql_insert_vendor);

   $sql_insert_device .= ";";
   $sql_insert_device = str_replace(",;", ";\n", $sql_insert_device);

   file_put_contents("../../install/mysql/pciid.sql", utf8_encode($sql_creation.$sql_insert_vendor."\n\n".$sql_insert_device));
}


function fileUSBtoDB() {
    $usbFile = fopen("usb.ids", "r");

   $sql_creation = "DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_usbvendors`;

CREATE TABLE `glpi_plugin_fusioninventory_usbvendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendorid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendorid` (`vendorid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_usbdevices`;

CREATE TABLE `glpi_plugin_fusioninventory_usbdevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plugin_fusioninventory_usbvendor_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `deviceid` (`deviceid`,`plugin_fusioninventory_usbvendor_id`),
  KEY `plugin_fusioninventory_usbvendor_id` (`plugin_fusioninventory_usbvendor_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


";

   $sql_insert_vendor = "INSERT INTO `glpi_plugin_fusioninventory_usbvendors`
      (`id`, `vendorid`, `name`) VALUES ";
   $sql_insert_device = "INSERT INTO `glpi_plugin_fusioninventory_usbdevices`
      (`id`, `deviceid`, `name`, `plugin_fusioninventory_usbvendor_id`) VALUES ";

   $v = 0;
   $d = 0;
   $nblines_vendor = 0;
   $nblines_device = 0;
   while (!feof($usbFile)) {
      $buffer = fgets($usbFile, 4096);

      $stack = [];
      if (preg_match("/^(\w+)\s*(.+)/i", $buffer, $stack)) {
         if ($nblines_vendor > 10000) {
            $sql_insert_vendor .= ";";
            $sql_insert_vendor .= "INSERT INTO `glpi_plugin_fusioninventory_usbvendors`
               (`id`, `vendorid`, `name`) VALUES ";
            $nblines_vendor = 0;
         }
         $v++;
         $vendorId = $stack[1];
         $vendorName = $stack[2];

         $sql_insert_vendor .= "\n(".$v.", '".$vendorId."', '".addslashes(htmlentities($vendorName))."'),";
         $nblines_vendor++;
      }

      $stack = [];
      if (preg_match("/^\t(\w+)\s*(.+)/i", $buffer, $stack)) {
         if ($nblines_device > 10000) {
            $sql_insert_device .= ";";
            $sql_insert_device .= "INSERT INTO `glpi_plugin_fusioninventory_usbdevices`
               (`id`, `deviceid`, `name`, `plugin_fusioninventory_usbvendor_id`) VALUES ";
            $nblines_device = 0;
         }
         $d++;
         $deviceId = $stack[1];
         $deviceName=$stack[2];

          $sql_insert_device .= "\n(".$d.", '".$deviceId."', '".addslashes(htmlentities($deviceName))."', '".$v."'),";
         $nblines_device++;
      }
   }

   $sql_insert_vendor .= ";";
   $sql_insert_vendor = str_replace(",;", ";\n", $sql_insert_vendor);

   $sql_insert_device .= ";";
   $sql_insert_device = str_replace(",;", ";\n", $sql_insert_device);

   file_put_contents("../../install/mysql/usbid.sql", utf8_encode($sql_creation.$sql_insert_vendor."\n\n".$sql_insert_device));
}


function fileOUItoDB() {

   $sql_creation = "DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ouis`;

CREATE TABLE `glpi_plugin_fusioninventory_ouis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

";
   $sql_insert_oui = "INSERT INTO `glpi_plugin_fusioninventory_ouis`
      (`id`, `mac`, `name`) VALUES ";

   $ouiFile = fopen("oui.txt", "r");
   $d = 0;
   $nblines = 0;
   while (!feof($ouiFile)) {
      $buffer = fgets($ouiFile, 4096);

      $stack = [];
      if (preg_match("/^(\S+)\s*\(hex\)\t{2}(.+)/i", $buffer, $stack)) {
         if ($nblines > 10000) {
            $sql_insert_oui .= ";";
            $sql_insert_oui .= "INSERT INTO `glpi_plugin_fusioninventory_ouis`
               (`id`, `mac`, `name`) VALUES ";
            $nblines = 0;
         }
         $OUI = $stack[1];
         $OUI = strtr($OUI, "-", ":");
         $organization = trim($stack[2]);
         $d++;
         $sql_insert_oui .= "\n(".$d.", '".$OUI."', '".addslashes(htmlentities($organization))."'),";

         $nblines++;
      }
   }
   $sql_insert_oui .= ";";
   $sql_insert_oui = str_replace(",;", ";\n", $sql_insert_oui);
   file_put_contents("../../install/mysql/oui.sql", utf8_encode($sql_creation.$sql_insert_oui));

}
