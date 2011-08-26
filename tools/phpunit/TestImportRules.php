<?php

define('PHPUnit_MAIN_METHOD', 'Plugins_Fusioninventory_TestImortRules::main');

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../../..');

   require_once GLPI_ROOT."/inc/includes.php";
   $_SESSION['glpi_use_mode'] = 2;
   $_SESSION['glpiactiveprofile']['id'] = 4;

   ini_set('display_errors','On');
   error_reporting(E_ALL | E_STRICT);
   set_error_handler("userErrorHandler");

   // Backup present DB
   include_once("inc/backup.php");
   backupMySQL();

   $_SESSION["glpilanguage"] = 'fr_FR';

   // Install
   include_once("inc/installation.php");
   installGLPI();
   installFusionPlugins();

   loadLanguage();
   include_once(GLPI_ROOT."/locales/fr_FR.php");
   include_once(GLPI_ROOT."/plugins/fusioninventory/locales/fr_FR.php");
   include_once(GLPI_ROOT."/plugins/fusinvsnmp/locales/fr_FR.php");
   include_once(GLPI_ROOT."/plugins/fusinvinventory/locales/fr_FR.php");
   $CFG_GLPI["root_doc"] = GLPI_ROOT;
}
include_once('emulatoragent.php');


// Define XML
$XML = array();
$XML['Computer'] = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2011-07-03 23:23:21</LOGDATE>
      <USERID>N/A</USERID>
    </ACCESSLOG>
    <BATTERIES>
      <CHEMISTRY>Lithium </CHEMISTRY>
      <DATE></DATE>
      <MANUFACTURER>TOSHIBA</MANUFACTURER>
      <SERIAL>0000000000</SERIAL>
    </BATTERIES>
    <BIOS>
      <ASSETTAG>0000000000</ASSETTAG>
      <BDATE>09/15/2010</BDATE>
      <BMANUFACTURER>TOSHIBA</BMANUFACTURER>
      <BVERSION>Version 1.60</BVERSION>
      <MMANUFACTURER>TOSHIBA</MMANUFACTURER>
      <MMODEL>Portable PC</MMODEL>
      <MSN>0000000000</MSN>
      <SKUNUMBER>0000000000</SKUNUMBER>
      <SMANUFACTURER>TOSHIBA</SMANUFACTURER>
      <SMODEL>Satellite R630</SMODEL>
      <SSN>XA201220H</SSN>
    </BIOS>
    <CONTROLLERS>
      <CAPTION>Core Processor DRAM Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Core Processor DRAM Controller</NAME>
      <PCICLASS>0600</PCICLASS>
      <PCIID>8086:0044</PCIID>
      <PCISLOT>00:00.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>Core Processor Integrated Graphics Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Core Processor Integrated Graphics Controller</NAME>
      <PCICLASS>0300</PCICLASS>
      <PCIID>8086:0046</PCIID>
      <PCISLOT>00:02.0</PCISLOT>
      <TYPE>Display controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset HECI Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset HECI Controller</NAME>
      <PCICLASS>0780</PCICLASS>
      <PCIID>8086:3b64</PCIID>
      <PCISLOT>00:16.0</PCISLOT>
      <TYPE>Communication controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>82577LC Gigabit Network Connection</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>82577LC Gigabit Network Connection</NAME>
      <PCICLASS>0200</PCICLASS>
      <PCIID>8086:10eb</PCIID>
      <PCISLOT>00:19.0</PCISLOT>
      <TYPE>Network controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</NAME>
      <PCICLASS>0c03</PCICLASS>
      <PCIID>8086:3b3c</PCIID>
      <PCISLOT>00:1a.0</PCISLOT>
      <TYPE>Serial bus controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset High Definition Audio</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset High Definition Audio</NAME>
      <PCICLASS>0403</PCICLASS>
      <PCIID>8086:3b56</PCIID>
      <PCISLOT>00:1b.0</PCISLOT>
      <TYPE>Multimedia controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 1</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 1</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b42</PCIID>
      <PCISLOT>00:1c.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 2</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 2</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b44</PCIID>
      <PCISLOT>00:1c.1</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset PCI Express Root Port 3</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset PCI Express Root Port 3</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:3b46</PCIID>
      <PCISLOT>00:1c.2</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset USB2 Enhanced Host Controller</NAME>
      <PCICLASS>0c03</PCICLASS>
      <PCIID>8086:3b34</PCIID>
      <PCISLOT>00:1d.0</PCISLOT>
      <TYPE>Serial bus controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>82801 Mobile PCI Bridge</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>82801 Mobile PCI Bridge</NAME>
      <PCICLASS>0604</PCICLASS>
      <PCIID>8086:2448</PCIID>
      <PCISLOT>00:1e.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>Mobile 5 Series Chipset LPC Interface Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>Mobile 5 Series Chipset LPC Interface Controller</NAME>
      <PCICLASS>0601</PCICLASS>
      <PCIID>8086:3b09</PCIID>
      <PCISLOT>00:1f.0</PCISLOT>
      <TYPE>Bridge</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset 4 port SATA AHCI Controller</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset 4 port SATA AHCI Controller</NAME>
      <PCICLASS>0106</PCICLASS>
      <PCIID>8086:3b29</PCIID>
      <PCISLOT>00:1f.2</PCISLOT>
      <TYPE>Mass storage controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>5 Series/3400 Series Chipset Thermal Subsystem</CAPTION>
      <MANUFACTURER>Intel Corporation</MANUFACTURER>
      <NAME>5 Series/3400 Series Chipset Thermal Subsystem</NAME>
      <PCICLASS>1180</PCICLASS>
      <PCIID>8086:3b32</PCIID>
      <PCISLOT>00:1f.6</PCISLOT>
      <TYPE>Signal processing controller</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>MMC/SD Host Controller</CAPTION>
      <MANUFACTURER>Ricoh Co Ltd</MANUFACTURER>
      <NAME>MMC/SD Host Controller</NAME>
      <PCICLASS>0805</PCICLASS>
      <PCIID>1180:e822</PCIID>
      <PCISLOT>01:00.0</PCISLOT>
      <TYPE>Generic system peripheral</TYPE>
    </CONTROLLERS>
    <CONTROLLERS>
      <CAPTION>BCM4313 802.11b/g/n Wireless LAN Controller</CAPTION>
      <MANUFACTURER>Broadcom Corporation</MANUFACTURER>
      <NAME>BCM4313 802.11b/g/n Wireless LAN Controller</NAME>
      <PCICLASS>0280</PCICLASS>
      <PCIID>14e4:4727</PCIID>
      <PCISLOT>02:00.0</PCISLOT>
      <TYPE>Network controller</TYPE>
    </CONTROLLERS>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>8529</FREE>
      <TOTAL>9681</TOTAL>
      <TYPE>/</TYPE>
      <VOLUMN>/dev/ad4s1a</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>194276</FREE>
      <TOTAL>403402</TOTAL>
      <TYPE>/Donnees</TYPE>
      <VOLUMN>/dev/ad4s1g</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>1213</FREE>
      <TOTAL>1447</TOTAL>
      <TYPE>/tmp</TYPE>
      <VOLUMN>/dev/ad4s1e</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>13983</FREE>
      <TOTAL>38739</TOTAL>
      <TYPE>/usr</TYPE>
      <VOLUMN>/dev/ad4s1f</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>20</FREE>
      <TOTAL>4673</TOTAL>
      <TYPE>/var</TYPE>
      <VOLUMN>/dev/ad4s1d</VOLUMN>
    </DRIVES>
    <ENVS>
      <KEY>HOST</KEY>
      <VAL>port004.bureau.siprossii.com</VAL>
    </ENVS>
    <HARDWARE>
      <ARCHNAME>amd64-freebsd-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Notebook</CHASSIS_TYPE>
      <CHECKSUM>262143</CHECKSUM>
      <DESCRIPTION>amd64/00-00-01 04:36:54</DESCRIPTION>
      <DNS>8.8.8.8</DNS>
      <ETIME>22</ETIME>
      <IPADDR>192.168.20.184/10.0.0.254/10.0.0.1</IPADDR>
      <MEMORY>3810</MEMORY>
      <NAME>port004</NAME>
      <OSCOMMENTS>GENERIC (Thu Feb 17 02:41:51 UTC 2011)root@mason.cse.buffalo.edu</OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>8.2-RELEASE</OSVERSION>
      <SWAP>4096</SWAP>
      <USERDOMAIN></USERDOMAIN>
      <USERID>ddurieux</USERID>
      <UUID>68405E00-E5BE-11DF-801C-B05981201220</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WORKGROUP>bureau.siprossii.com</WORKGROUP>
    </HARDWARE>
    <MEMORIES>
      <CAPACITY>2048</CAPACITY>
      <CAPTION>DIMM0</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>1</NUMSLOTS>
      <SERIALNUMBER>98F6FF18</SERIALNUMBER>
      <SPEED>1067</SPEED>
      <TYPE>DDR3</TYPE>
    </MEMORIES>
    <MEMORIES>
      <CAPACITY>2048</CAPACITY>
      <CAPTION>DIMM2</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>2</NUMSLOTS>
      <SERIALNUMBER>95F1833E</SERIALNUMBER>
      <SPEED>1067</SPEED>
      <TYPE>DDR3</TYPE>
    </MEMORIES>
    <NETWORKS>
      <DESCRIPTION>em0</DESCRIPTION>
      <IPADDRESS>192.168.20.184</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>192.168.20.0</IPSUBNET>
      <MACADDR>00:23:18:cf:0d:93</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE>Ethernet</TYPE>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo0</DESCRIPTION>
      <IPADDRESS>127.0.0.1</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.0.0.0</IPMASK>
      <IPSUBNET>127.0.0.0</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>1</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo1</DESCRIPTION>
      <IPADDRESS>10.0.0.1</IPADDRESS>
      <IPGATEWAY>192.168.20.1</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>10.0.0.0</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>1</VIRTUALDEV>
    </NETWORKS>
    <PORTS>
      <CAPTION>DB-15 female</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>RJ-45</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Network Port</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Access Bus (USB)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>USB</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Mini Jack (headphones)</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PORTS>
      <CAPTION>Other</CAPTION>
      <DESCRIPTION>None</DESCRIPTION>
      <NAME> </NAME>
      <TYPE>Other</TYPE>
    </PORTS>
    <PROCESSES>
      <CMD>[idle]</CMD>
      <CPUUSAGE>374.3</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>11</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <SLOTS>
      <DESCRIPTION>Other</DESCRIPTION>
      <NAME>SD CARD</NAME>
      <STATUS>In Use</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>1</DESIGNATION>
      <NAME>EXPRESS CARD</NAME>
      <STATUS>In Use</STATUS>
    </SLOTS>
    <SOFTWARES>
      <COMMENTS>Image processing tools</COMMENTS>
      <NAME>ImageMagick</NAME>
      <VERSION>6.7.0.2</VERSION>
    </SOFTWARES>
    <SOUNDS>
      <DESCRIPTION>rev 06</DESCRIPTION>
      <MANUFACTURER>Intel Corporation 5 Series/3400 Series Chipset High Definition Audio </MANUFACTURER>
      <NAME>Audio device</NAME>
    </SOUNDS>
    <STORAGES>
      <DESCRIPTION>ad4s1b</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1a</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1g</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1e</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1f</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>ad4s1d</DESCRIPTION>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>acd0</DESCRIPTION>
      <MODEL>MATSHITADVD-RAM UJ892ES/1.20</MODEL>
      <TYPE></TYPE>
    </STORAGES>
    <USERS>
      <LOGIN>ddurieux</LOGIN>
    </USERS>
    <VERSIONCLIENT>FusionInventory-Agent_v2.1.9-3</VERSIONCLIENT>
    <VIDEOS>
      <CHIPSET>VGA compatible controller</CHIPSET>
      <NAME>Intel Corporation Core Processor Integrated Graphics Controller </NAME>
    </VIDEOS>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>";
      
$XML['NetworkEquipment'] = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(52)SE, RELEASE SOFTWARE (fc3)
Copyright (c) 1986-2009 by Cisco Systems, Inc.
Compiled Fri 25-Sep-09 08:49 by sasyamal</COMMENTS>
        <CPU>11</CPU>
        <ID>2</ID>
        <IPS>
          <IP>192.168.20.81</IP>
        </IPS>
        <MAC>00:1a:6c:9a:fa:80</MAC>
        <NAME>switch2960-002</NAME>
        <SERIAL>FOC1040ZFNU</SERIAL>
        <TYPE>NETWORKING</TYPE>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CONNECTION>
              <MAC>00:23:18:cf:0d:93</MAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/1</IFDESCR>
          <IFNAME>Fa0/1</IFNAME>
          <IFNUMBER>10001</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:85</MAC>
          <TRUNK>0</TRUNK>
          <VLANS>
            <VLAN>
              <NAME>VLAN0020</NAME>
              <NUMBER>20</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
        <PORT>
          <CONNECTIONS>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/2</IFDESCR>
          <IFNAME>Fa0/2</IFNAME>
          <IFNUMBER>10002</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:86</MAC>
          <TRUNK>0</TRUNK>
       </PORT>
        <PORT>
          <CONNECTIONS>
          </CONNECTIONS>
          <IFDESCR>FastEthernet0/3</IFDESCR>
          <IFNAME>Fa0/3</IFNAME>
          <IFNUMBER>10003</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fa:87</MAC>
          <TRUNK>0</TRUNK>
       </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>2</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';

$XML['Unknowndevice_Computer'] = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <AUTHSNMP>1</AUTHSNMP>
      <DESCRIPTION>VideoJet-X10</DESCRIPTION>
      <ENTITY>0</ENTITY>
      <IP>192.168.40.132</IP>
      <MAC>00:07:5f:76:8a:83</MAC>
      <MODELSNMP/>
      <SERIAL/>
      <SNMPHOSTNAME>Test2</SNMPHOSTNAME>
      <TYPE>1</TYPE>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>3</PROCESSNUMBER>
   </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';

$XML['Unknowndevice_NetworkEquipment'] = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <AUTHSNMP>2</AUTHSNMP>
      <DESCRIPTION>HP J4813A ProCurve Switch 2524, revision F.05.55, ROM F.01.01  (/sw/code/build/info(s02))</DESCRIPTION>
      <ENTITY>0</ENTITY>
      <IP>192.168.40.56</IP>
      <MAC>00:01:e7:6a:55:40</MAC>
      <MODELSNMP/>
      <SERIAL/>
      <SNMPHOSTNAME>Procurve 2524</SNMPHOSTNAME>
      <TYPE>2</TYPE>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>3</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';

$XML['Unknowndevice_Printer'] = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
     <DEVICE>
      <AUTHSNMP>2</AUTHSNMP>
      <DESCRIPTION>RICOH Aficio MP C3300 1.23 / RICOH Network Printer C model / RICOH Network Scanner C model / RICOH Network Facsimile C model</DESCRIPTION>
      <ENTITY>0</ENTITY>
      <IP>192.168.40.3</IP>
      <MAC>00:26:73:06:9D:46</MAC>
      <MODELSNMP/>
      <NETBIOSNAME>COPIEUR-1</NETBIOSNAME>
      <SERIAL/>
      <SNMPHOSTNAME>Aficio MP C3300</SNMPHOSTNAME>
      <TYPE>3</TYPE>
      <WORKGROUP>TGER</WORKGROUP>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>3</PROCESSNUMBER>
    </CONTENT>
     <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';

$XML['Unknowndevice_notype'] = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <AUTHSNMP>2</AUTHSNMP>
      <DESCRIPTION>RICOH Aficio MP C2800 1.22 / RICOH Network Printer C model / RICOH Network Scanner C model / RICOH Network Facsimile C model</DESCRIPTION>
      <ENTITY>0</ENTITY>
      <IP>192.168.40.87</IP>
      <MAC>00:26:73:00:38:FE</MAC>
      <MODELSNMP/>
      <NETBIOSNAME>COPIEUR-A2</NETBIOSNAME>
      <SERIAL/>
      <SNMPHOSTNAME>Aficio MP C2800</SNMPHOSTNAME>
      <TYPE>0</TYPE>
      <WORKGROUP>WORKGROUP</WORKGROUP>
    </DEVICE>
    <MODULEVERSION>1.3</MODULEVERSION>
    <PROCESSNUMBER>3</PROCESSNUMBER>
    </CONTENT>
     <DEVICEID>port004.bureau.siprossii.com-2010-12-30-12-24-14</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';

/**
 * Test class for MyFile.
 * Generated by PHPUnit on 2010-08-06 at 12:05:09.
 */
class Plugins_Fusioninventory_TestImortRules extends PHPUnit_Framework_TestCase {

    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('Plugins_Fusioninventory_TestImortRules');
        $result = PHPUnit_TextUI_TestRunner::run($suite);

    }

    
    
   /*
   * No rules
   *   => Computer must be created in Computer type
   *   => networkequipment must be created in network equipment (SNMP inventory)
   *   => printer must be created in printer (SNMP inventory)
   *   => type defined in discovery created into it's itemtype
   *   => type not defined in discovery created into unknown devices
   */
   public function testNoRule() {
      global $DB, $XML;
      
     // Disable all rules
     $query = "UPDATE `glpi_rules` SET `is_active` = '0' 
        WHERE `sub_type`='PluginFusioninventoryRuleImportEquipment' ";
     $DB->query($query);

      // Activate Extra-debug
      $plugin = new Plugin();
      $data = $plugin->find("`name` = 'FusionInventory'");
      $fields = current($data);
      $plugins_id = $fields['id'];
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $PluginFusioninventoryConfig->updateConfigType($plugins_id, "extradebug", "1");
      
      // Activate all modules for all agents
       $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
         SET `is_active`='1' ";
      $DB->query($query);
      
       
      // ** Import Computer => Computer must be created in Computer type
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $xml = simplexml_load_string($XML['Computer'],'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $computer = new Computer();
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 1 , 'Problem import Computer ('.(string)$xml->DEVICEID.') not right created!');
      $computerdata = current($a_computer);
      $this->assertEquals($computerdata['entities_id'], 0 , 'Problem On computer entity, must be created in root entity instead '.$computerdata['entities_id']);
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import Computer ('.(string)$xml->DEVICEID.'), unknown device created');
//      $computer->delete(array('id'=>1), 1);      
             
      // ** Import networkequipment  => networkequipment must be created in network equipment (SNMP inventory)
       
         // Add task and taskjob
         $pluginFusioninventoryTask = new PluginFusioninventoryTask();
         $pluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
         $pluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus();
         
         $input = array();
         $input['entities_id'] = '0';
         $input['name'] = 'snmpquery';
         $tasks_id = $pluginFusioninventoryTask->add($input);

         $input = array();
         $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
         $input['method'] = 'snmpquery';
         $input['status'] = 1;
         $taskjobs_id = $pluginFusioninventoryTaskjob->add($input);

         $input = array();
         $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
         $input['itemtype'] = 'NetworkEquipment';
         $input['items_id'] = '1';
         $input['state'] = 1;
         $input['plugin_fusioninventory_agents_id'] = 1;
         $pluginFusioninventoryTaskjobstatus->add($input);
         $input['items_id'] = '2';
         $pluginFusioninventoryTaskjobstatus->add($input);
         
         $this->testSendinventory("toto", $XML['NetworkEquipment'], 0);
         $networkEquipment = new NetworkEquipment();
         $a_switch = $networkEquipment->find("`name`='switch2960-002'");
         $this->assertEquals(count($a_switch), 1 , 'Problem import switch (switch2960-002) not right created!');
         $switchdata = current($a_switch);
         $this->assertEquals($switchdata['entities_id'], 0 , 'Problem On switch entity, must be created in root entity instead '.$switchdata['entities_id']);
         $a_unknown = $pluginFusioninventoryUnknownDevice->find();
         $this->assertEquals(count($a_unknown), 0 , 'Problem import switch (switch2960-002), unknown device created');
//         $networkEquipment->delete(array('id'=>1), 1);
      
       // ** [TODO] Import printer 

          
         
      // ** Unknowndevice_Computer => Computer must be created in Computer type
      $input = array();
      $input['entities_id'] = '0';
      $input['name'] = 'netdiscovery';
      $tasks_id = $pluginFusioninventoryTask->add($input);

      $input = array();
      $input['plugin_fusioninventory_tasks_id'] = $tasks_id;
      $input['method'] = 'netdiscovery';
      $input['status'] = 1;
      $taskjobs_id = $pluginFusioninventoryTaskjob->add($input);

      $input = array();
      $input['plugin_fusioninventory_taskjobs_id'] = $taskjobs_id;
      $input['itemtype'] = 'NetworkEquipment';
      $input['items_id'] = '1';
      $input['state'] = 1;
      $input['plugin_fusioninventory_agents_id'] = 1;
      $pluginFusioninventoryTaskjobstatus->add($input);
      $input['items_id'] = '2';
      $pluginFusioninventoryTaskjobstatus->add($input);
      
      $this->testSendinventory("toto", $XML['Unknowndevice_Computer'], 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 1 , 'Problem import discovered Computer (Test2) not right created!');
      $computerdata = current($a_computer);
      $this->assertEquals($computerdata['entities_id'], 0 , 'Problem On computer entity, must be created in root entity instead '.$computerdata['entities_id']);
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered Computer (Test2), unknown device created');
//      $computer->delete(array('id'=>1), 1);
       
      
      // ** Unknowndevice_NetworkEquipment => NetworkEquipment must be created in NetworkEquipment type
      $this->testSendinventory("toto", $XML['Unknowndevice_NetworkEquipment'], 0);
      $a_networkequipment = $networkEquipment->find("`name`='Procurve 2524'");
      $this->assertEquals(count($a_networkequipment), 1 , 'Problem import discovered networkequipment (Procurve 2524) not right created!');
      $switchdata = current($a_networkequipment);
      $this->assertEquals($switchdata['entities_id'], 0 , 'Problem On switch entity, must be created in root entity instead '.$switchdata['entities_id']);
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered networkequipment (Procurve 2524), unknown device created');

      
      // ** Unknowndevice_Printer => Printer must be created in Printer type
      $this->testSendinventory("toto", $XML['Unknowndevice_Printer'], 0);
      $printer = new Printer();
      $a_printer = $printer->find("`name`='COPIEUR-1'");
      $this->assertEquals(count($a_printer), 1 , 'Problem import discovered printer (COPIEUR-1) not right created!');
      $printerdata = current($a_printer);
      $this->assertEquals($printerdata['entities_id'], 0 , 'Problem On printer entity, must be created in root entity instead '.$printerdata['entities_id']);
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered printer (COPIEUR-1), unknown device created');

      
      // ** Unknowndevice_notype => type not defined in discovery created into unknown devices
      $this->testSendinventory("toto", $XML['Unknowndevice_notype'], 0);
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 1 , 'Problem import discovered device with no type not right created!');
      $unknowndata = current($a_unknown);
      $this->assertEquals($unknowndata['entities_id'], 0 , 'Problem On unknown entity, must be created in root entity instead '.$unknowndata['entities_id']);
      $unknown = current($a_unknown);
      $pluginFusioninventoryUnknownDevice->delete($unknown, 1);      
      
   }

    
   
   /*
    *  Computer: Rule 1/ itemtype is computer
    *                    -> FusionInventory link Assign Link if possible, else create device
    *            Rule 2/ name is * 
    *                    -> FusionInventory link Assign Link if possible, else create device
    *    => Computer may be created in Computer type and not in unknown device
    */
   public function testImportComputerwithTypeOnly() {
      global $DB, $XML;
      
      // Add the rule with criterial only if type = Computer
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer type import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 0;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
         
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Unknown device import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 1;
      $rule2_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['criteria'] = "name";
         $input['pattern']= '*';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);

         
      // ** Import Computer XML
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $xml = simplexml_load_string($XML['Computer'],'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $computer = new Computer();
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 2 , 'Problem import Computer ('.(string)$xml->DEVICEID.') not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import Computer ('.(string)$xml->DEVICEID.'), unknown device created');
         
         
      // ** Import discovered Computer
      $this->testSendinventory("toto", $XML['Unknowndevice_Computer'], 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 2 , 'Problem import discovered Computer (Test2) not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered Computer (Test2), unknown device created');

      
      $rulecollection->delete(array('id'=>$rule_id), 1);
      $rulecollection->delete(array('id'=>$rule2_id), 1);
   }
   
   

   /*
    *  Computer: Rule 1/ itemtype is computer
    *                    name exist
    *                    -> FusionInventory link Assign Link if possible, else create device
    *            Rule 2/ name is * 
    *                    -> FusionInventory link Assign Link if possible, else create device
    *    => Computer may be created in Computer type and not in unknown device
    */
   public function testImportComputerwithTypeAndNameExist() {
      global $DB, $XML;
      
      // Add the rule with criterial only if type = Computer
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer type import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 0;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);
         
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
         
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Unknown device import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 1;
      $rule2_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['criteria'] = "name";
         $input['pattern']= '*';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
      
      
      // ** Import Computer XML (have name)
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $xml = simplexml_load_string($XML['Computer'],'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $computer = new Computer();
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 3 , 'Problem import Computer ('.(string)$xml->DEVICEID.') not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import Computer ('.(string)$xml->DEVICEID.'), unknown device created');
         
         
      // ** Import discovered Computer (have name)
      $this->testSendinventory("toto", $XML['Unknowndevice_Computer'], 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 3 , 'Problem import discovered Computer (Test2) not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered Computer (Test2), unknown device created');

      
      // ** Import Computer XML (not have name)
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $xmltmp = $XML['Computer'];
      $xmltmp = str_replace(">port004<", "><", $xmltmp);
      $xml = simplexml_load_string($xmltmp,'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($xmltmp);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $computer = new Computer();
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 3 , 'Problem import Computer without name have been created into Computer instead unknown');
      $a_computer = $computer->find("`name`=''");
      $this->assertEquals(count($a_computer), 0 , 'Problem import Computer without name have been created into Computer instead unknown');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 1 , 'Problem import Computer without name , unknown device not created');
         
      
      // ** Import discovered Computer (not have name)
      $xmltmp = $XML['Unknowndevice_Computer'];
      $xmltmp = str_replace(">Test2<", "><", $xmltmp);
      $this->testSendinventory("toto", $xmltmp, 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 3 , 'Problem import discovered Computer without name have been created into Computer instead unknown');
      $a_computer = $computer->find("`name`=''");
      $this->assertEquals(count($a_computer), 0 , 'Problem import Computer without name have been created into Computer instead unknown');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 2 , 'Problem import discovered Computer without name, unknown device not created');

            
      $input = array();
      $input['is_active']=0;
      $input['id'] = $rule_id;
      $rulecollection->update($input);
      $input['id'] = $rule2_id;
      $rulecollection->update($input);
   }
    

    
   /*
    *  Computer (computer created into GLPI with good name: 
    *            Rule 1/ itemtype is computer
    *                    name exits
    *                    name is present in GLPI
    *                    -> FusionInventory link Assign Link if possible, else create device
    *            Rule 2/ name is * 
    *                    -> FusionInventory link Assign Link if possible, else create device
    *    => Computer may be created in Computer type and not in unknown device
    */
   public function testImportComputerwithTypeAndNameExistNamePresent() {
      global $DB, $XML;
      

      // Add the rule with criterial only if type = Computer
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer type import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 0;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);
         
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
         
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Unknown device import';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryRuleImportEquipment';
      $input['ranking'] = 1;
      $rule2_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['criteria'] = "name";
         $input['pattern']= '*';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule2_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '0';
         $ruleaction->add($input);
      
      
         
      // ** Import Computer XML (have name and exist in DB)
      $computer = new Computer();
      $computer->delete(array('id'=>3), 1);
      $computer->delete(array('id'=>5), 1); 
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $pluginFusioninventoryUnknownDevice->delete(array('id'=>2), 1);
      $pluginFusioninventoryUnknownDevice->delete(array('id'=>3), 1);
      $xml = simplexml_load_string($XML['Computer'],'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 1 , 'Problem import Computer ('.(string)$xml->DEVICEID.') not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import Computer ('.(string)$xml->DEVICEID.'), unknown device created');
         
         
         
      // ** Import discovered Computer (have name and exist in DB)
      $computer->delete(array('id'=>4), 1);
      $computer->delete(array('id'=>6), 1);
      $this->testSendinventory("toto", $XML['Unknowndevice_Computer'], 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 1 , 'Problem import discovered Computer (Test2) not right created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 0 , 'Problem import discovered Computer (Test2), unknown device created');

         
      // ** Import Computer XML (have name but not exist in DB)
      $computer = new Computer();
      $computer->delete(array('id'=>1), 1); 
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $pluginFusioninventoryUnknownDevice->delete(array('id'=>2), 1);
      $pluginFusioninventoryUnknownDevice->delete(array('id'=>3), 1);
      $xml = simplexml_load_string($XML['Computer'],'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $a_computer = $computer->find("`name`='port004'");
      $this->assertEquals(count($a_computer), 0 , 'Problem import Computer ('.(string)$xml->DEVICEID.') is created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find();
      $this->assertEquals(count($a_unknown), 1 , 'Problem import Computer ('.(string)$xml->DEVICEID.'), unknown device not created');
         
         
      // ** Import discovered Computer (have name but not exist in DB)
      $computer->delete(array('id'=>2), 1);
      $this->testSendinventory("toto", $XML['Unknowndevice_Computer'], 0);
      $a_computer = $computer->find("`name`='Test2'");
      $this->assertEquals(count($a_computer), 0 , 'Problem import discovered Computer (Test2) created!');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find("`name`='Test2'");
      $this->assertEquals(count($a_unknown), 1 , 'Problem import discovered Computer (Test2), unknown device not created');

         
      // ** Import Computer XML (not have name)
      $computer->delete(array('id'=>7), 1);
      $computer->delete(array('id'=>8), 1);
      $pluginFusioninventoryUnknownDevice = new PluginFusioninventoryUnknownDevice();
      $xmltmp = $XML['Computer'];
      $xmltmp = str_replace(">port004<", "><", $xmltmp);
      $xml = simplexml_load_string($xmltmp,'SimpleXMLElement', LIBXML_NOCDATA);
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($xmltmp);
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent();
      $a_agent = $PluginFusioninventoryAgent->find("`device_id`='".(string)$xml->DEVICEID."'");
      $this->assertEquals(count($a_agent), 1 , 'Problem on prolog, agent ('.(string)$xml->DEVICEID.') not right created!');
      $computer = new Computer();
      $a_computer = $computer->find("`serial`='XA201220H'");
      $this->assertEquals(count($a_computer), 0 , 'Problem import Computer without name have been created into Computer instead unknown');
      $a_unknown = $pluginFusioninventoryUnknownDevice->find("`serial`='XA201220H'");
      $this->assertEquals(count($a_unknown), 2 , 'Problem import Computer without name , unknown device not created');
         
                  
      $input = array();
      $input['is_active']=0;
      $input['id'] = $rule_id;
      $rulecollection->update($input);
      $input['id'] = $rule2_id;
      $rulecollection->update($input);
         
   }

   
   
   public function testImportComputerCheckrulevalidationlocal_and_globalcriteria() {
      global $DB, $XML;
      
      // Create computer only with serial and name;
      $computer = new Computer();
      $input = array();
      $input['name'] = "port004";
      $input['serial'] = "XA201220H";
      $computer->add($input);
      
      // Activation of rules
      $rulecollection = new PluginFusioninventoryRuleImportEquipmentCollection();
         // Computer serial + uuid
         $input = array();
         $input['is_active']=1;
         $input['id']=6; 
         $rule_id = $rulecollection->update($input);
         
         // Computer serial
         $input = array();
         $input['is_active']=1;
         $input['id']=7; 
         $rule_id = $rulecollection->update($input);
      
         // Computer name
         $input = array();
         $input['is_active']=1;
         $input['id']=10; 
         $rule_id = $rulecollection->update($input);
         
      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      $prologXML = $emulatorAgent->sendProlog($XML['Computer']);
      $a_computers = $computer->find("`serial`='XA201220H'");
      $this->assertEquals(count($a_computers), 1 , 'Problem on global criteria of rules, 
         these criteria must be valided to valid the rule (Computer seria = UUID)!');
         
   }
    
     
     
   function testSendinventory($xmlFile='', $xmlstring='', $create='0') {

      if (empty($xmlFile)) {
         echo "testSendinventory with no arguments...\n";
         return;
      }

      $emulatorAgent = new emulatorAgent;
      $emulatorAgent->server_urlpath = "/glpi080/plugins/fusioninventory/";
      if (empty($xmlstring)) {
         $xml = simplexml_load_file($xmlFile,'SimpleXMLElement', LIBXML_NOCDATA);
      } else {
         $xml = simplexml_load_string($xmlstring);
      }

      if ($create == '1') {
         // Send prolog for creation of agent in GLPI
         $input_xml = '<?xml version="1.0" encoding="UTF-8"?>
   <REQUEST>
     <DEVICEID>'.$xml->DEVICEID.'</DEVICEID>
     <QUERY>PROLOG</QUERY>
     <TOKEN>CBXTMXLU</TOKEN>
   </REQUEST>';
         $emulatorAgent->sendProlog($input_xml);
         if (isset($xml->CONTENT->DEVICE)) {
            foreach ($xml->CONTENT->DEVICE as $child) {
               if (isset($child->INFO)) {
                  foreach ($child->INFO as $child2) {
                     if ($child2->TYPE == 'NETWORKING') {
                        // Create switch in asset
                        $NetworkEquipment = new NetworkEquipment();
                        $input = array();
                        if (isset($child2->SERIAL)) {
                           $input['serial']=$child2->SERIAL;
                        } else {
                           $input['name']=$child2->NAME;
                        }
                        $input['entities_id'] = 0;
                        $NetworkEquipment->add($input);
                     }
                  }
               }
            }
         }
      }
      $input_xml = $xml->asXML();
      $code = $emulatorAgent->sendProlog($input_xml);
      echo $code."\n";
   }
   

   
}

// Call Plugins_Fusioninventory_Discovery_Newdevices::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == 'Plugins_Fusioninventory_TestImortRules::main') {
    Plugins_Fusioninventory_TestImortRules::main();

}

?>