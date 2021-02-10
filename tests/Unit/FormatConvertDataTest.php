<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class FormatConvertDataTest extends TestCase {


   /**
    * @test
    */
   public function JsontoArray() {

      $a_inventory['hardware'] = [
         "dns"                  => "10.0.5.105",
         "userid"               => "root/goneri",
         "oscomments"           => "#1 SMP Wed Jan 12 03:40:32 UTC 2011",
         "processort"           => "Intel(R) Core(TM) i3 CPU       M 350  @ 2.27GHz",
         "uuid"                 => "AAE79880-C977-11DF-801C-B05991157081",
         "lastloggeduser"       => "goneri",
         "checksum"             => 262143,
         "osversion"            => "2.6.32-5-amd64",
         "archname"             => "x86_64-linux-gnu-thread-multi",
         "processors"           => 2270,
         "osname"               => "Debian GNU/Linux unstable (sid)",
         "swap"                 => 0,
         "ipaddr"               => "172.28.218.100/192.168.41.6/192.168.1.11",
         "defaultgateway"       => "192.168.1.254",
         "name"                 => "tosh-r630",
         "description"          => "x86_64/00-00-11 18:38:10",
         "workgroup"            => "rulezlan.org",
         "vmsystem"             => "Physical",
         "etime"                => 7,
         "memory"               => 3696,
         "userdomain"           => "",
         "processorn"           => 1,
         "datelastloggeduser"   => "Wed Mar 30 19:29"
      ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->JSONtoArray(json_encode($a_inventory));

      $a_reference['HARDWARE'] = [
         'ARCHNAME'             => 'x86_64-linux-gnu-thread-multi',
         'CHECKSUM'             => '262143',
         'DATELASTLOGGEDUSER'   => 'Wed Mar 30 19:29',
         'DEFAULTGATEWAY'       => '192.168.1.254',
         'DESCRIPTION'          => 'x86_64/00-00-11 18:38:10',
         'DNS'                  => '10.0.5.105',
         'ETIME'                => '7',
         'IPADDR'               => '172.28.218.100/192.168.41.6/192.168.1.11',
         'LASTLOGGEDUSER'       => 'goneri',
         'MEMORY'               => '3696',
         'NAME'                 => 'tosh-r630',
         'OSCOMMENTS'           => '#1 SMP Wed Jan 12 03:40:32 UTC 2011',
         'OSNAME'               => 'Debian GNU/Linux unstable (sid)',
         'OSVERSION'            => '2.6.32-5-amd64',
         'PROCESSORN'           => '1',
         'PROCESSORS'           => '2270',
         'PROCESSORT'           => 'Intel(R) Core(TM) i3 CPU       M 350  @ 2.27GHz',
         'SWAP'                 => '0',
         'USERDOMAIN'           => '',
         'USERID'               => 'root/goneri',
         'UUID'                 => 'AAE79880-C977-11DF-801C-B05991157081',
         'VMSYSTEM'             => 'Physical',
         'WORKGROUP'            => 'rulezlan.org'
      ];
      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function Replaceids() {

      // create a computer's model dictionnay to test import with manufacturer
      $rule = new RuleDictionnaryComputerModel;
      $rules_id = $rule->add([
         'sub_type'  => 'RuleDictionnaryComputerModel',
         'name'      => 'test import with manufacturer',
         'is_active' => 1,
         'match'     => 'AND'
      ]);
      $ruleacriteria = new RuleCriteria;
      $ruleaaction   = new RuleAction;
      $ruleacriteria->add([
         'rules_id'  => $rules_id,
         'criteria'  => 'name',
         'condition' => Rule::PATTERN_CONTAIN,
         'pattern'   => 'XPS 13',
      ]);
      $ruleacriteria->add([
         'rules_id'  => $rules_id,
         'criteria'  => 'manufacturer',
         'condition' => Rule::PATTERN_CONTAIN,
         'pattern'   => 'Dell',
      ]);
      $ruleaaction->add([
         'rules_id'    => $rules_id,
         'action_type' => 'assign',
         'field'       => 'name',
         'value'       => 'Modified by dictionary',
      ]);

      $a_inventory['software'] = [];
      $a_inventory['Computer'] = [
         'name'                       => 'pc',
         'users_id'                   => 0,
         'operatingsystems_id'        => 'freebsd',
         'operatingsystemversions_id' => '9.1-RELEASE',
         'manufacturers_id'           => 'Dell Inc.',
         'computermodels_id'          => 'XPS 13 9350'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $manufacturer = new Manufacturer();
      $manufacturer->getFromDBByCrit(['name' => 'Dell Inc.']);

      $computerModel = new ComputerModel();
      $computerModel->getFromDBByCrit(['name' => 'Modified by dictionary']);

      $os = new OperatingSystem();
      $os->getFromDBByCrit(['name' => 'freebsd']);

      $osVersion = new OperatingSystemVersion();
      $osVersion->getFromDBByCrit(['name' => '9.1-RELEASE']);

      $a_reference['software'] = [];
      $a_reference['Computer'] = [
         'name'                       => 'pc',
         'users_id'                   => 0,
         'operatingsystems_id'        => $os->fields['id'],
         'operatingsystemversions_id' => $osVersion->fields['id'],
         'manufacturers_id'           => $manufacturer->fields['id'],
         'computermodels_id'          => $computerModel->fields['id']
      ];

      $this->assertEquals($a_reference, $a_inventory);
   }


   /**
    * @test
    */
   function DiscoveryDeviceConvert() {

      $sxml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <AUTHSNMP>1</AUTHSNMP>
      <DESCRIPTION>Eaton 5PX 1500</DESCRIPTION>
      <IP>192.168.20.196</IP>
      <MAC>00:20:85:f5:2d:19</MAC>
      <MANUFACTURER>Eaton</MANUFACTURER>
      <SNMPHOSTNAME>ups25</SNMPHOSTNAME>
      <TYPE>NETWORKING</TYPE>
    </DEVICE>
    <MODULEVERSION>2.2.0</MODULEVERSION>
    <PROCESSNUMBER>11</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>port004.bureau.siprossii.com-2012-12-20-16-27-27</DEVICEID>
  <QUERY>NETDISCOVERY</QUERY>
</REQUEST>';
      $xml = @simplexml_load_string($sxml, 'SimpleXMLElement', LIBXML_NOCDATA);

      $a_xml = PluginFusioninventoryFormatconvert::XMLtoArray($xml);
      $referecnce = [
         'CONTENT'  => [
            'DEVICE' => [
               [
                  'AUTHSNMP' => 1,
                  'DESCRIPTION'  => 'Eaton 5PX 1500',
                  'IP'           => '192.168.20.196',
                  'MAC'          => '00:20:85:f5:2d:19',
                  'MANUFACTURER' => 'Eaton',
                  'SNMPHOSTNAME' => 'ups25',
                  'TYPE'         => 'NETWORKING'
               ]
            ],
            'MODULEVERSION' => '2.2.0',
            'PROCESSNUMBER' => '11'
         ],
         'DEVICEID' => 'port004.bureau.siprossii.com-2012-12-20-16-27-27',
         'QUERY'    => 'NETDISCOVERY'
      ];
      $this->assertEquals($referecnce, $a_xml);
   }


   /**
    * @test
    */
   function SwitchConvert() {

      $sxml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <DEVICE>
      <INFO>
        <COMMENTS>Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(55)SE10, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2015 by Cisco Systems, Inc.
Compiled Wed 11-Feb-15 11:46 by prod_rel_team</COMMENTS>
        <CPU>8</CPU>
        <FIRMWARE>12.2(55)SE10</FIRMWARE>
        <ID>0</ID>
        <IPS>
          <IP>192.168.20.80</IP>
        </IPS>
        <MAC>00:1a:6c:9a:fc:80</MAC>
        <MANUFACTURER>Cisco</MANUFACTURER>
        <MEMORY>7</MEMORY>
        <MODEL>Catalyst 2960-24TC</MODEL>
        <NAME>cisco2960</NAME>
        <RAM>64</RAM>
        <SERIAL>FOC1047ZFMY</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>12 hours, 59:37.55</UPTIME>
        <VENDOR>Cisco</VENDOR>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CONNECTION>
              <MAC>64:9e:f3:32:cc:06</MAC>
              <MAC>fc:99:47:13:d5:10</MAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>GigabitEthernet0/1</IFDESCR>
          <IFINERRORS>0</IFINERRORS>
          <IFINOCTETS>189552874</IFINOCTETS>
          <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
          <IFLASTCHANGE>3 minutes, 01.53</IFLASTCHANGE>
          <IFMTU>1500</IFMTU>
          <IFNAME>Gi0/1</IFNAME>
          <IFNUMBER>10101</IFNUMBER>
          <IFOUTERRORS>0</IFOUTERRORS>
          <IFOUTOCTETS>16579230</IFOUTOCTETS>
          <IFPORTDUPLEX>3</IFPORTDUPLEX>
          <IFSPEED>1000000000</IFSPEED>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>00:1a:6c:9a:fc:99</MAC>
          <TRUNK>0</TRUNK>
          <VLANS>
            <VLAN>
              <NAME>default</NAME>
              <NUMBER>1</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
    </DEVICE>
    <MODULEVERSION>2.2.1</MODULEVERSION>
    <PROCESSNUMBER>1</PROCESSNUMBER>
  </CONTENT>
  <DEVICEID>foo</DEVICEID>
  <QUERY>SNMPQUERY</QUERY>
</REQUEST>';
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $xml = @simplexml_load_string($sxml, 'SimpleXMLElement', LIBXML_NOCDATA);

      $a_xml = PluginFusioninventoryFormatconvert::XMLtoArray($xml);
      $a_inventory = PluginFusioninventoryFormatconvert::networkequipmentInventoryTransformation($a_xml['CONTENT']['DEVICE'][0]);

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'NetworkEquipment', 0);

      $a_reference = [
          10101 => [
              '64:9e:f3:32:cc:06',
              'fc:99:47:13:d5:10'
          ]
      ];
      $this->assertEquals($a_reference, $a_inventory['connection-mac'], "Must have 2 macs ".print_r($a_inventory['connection-mac'], true));
   }


   /**
    * @test
    */
   function testComputerConvert() {

      $sxml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <HARDWARE>
      <ARCHNAME>MSWin32-x64-multi-thread</ARCHNAME>
      <CHASSIS_TYPE>Notebook</CHASSIS_TYPE>
      <CHECKSUM>127855</CHECKSUM>
      <ETIME>13</ETIME>
      <IPADDR>192.168.0.224</IPADDR>
      <LASTLOGGEDUSER>winuser</LASTLOGGEDUSER>
      <MEMORY>3887</MEMORY>
      <NAME>pc-test</NAME>
      <OSNAME>Microsoft Windows 8.1 Professionnel</OSNAME>
      <OSVERSION>6.3.9600</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2530</PROCESSORS>
      <PROCESSORT>Intel(R) Core(TM) i5 CPU M 540 @ 2.53GHz</PROCESSORT>
      <USERID>winuserid</USERID>
      <UUID>ABCDE-ABCDE-ABCDE</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WINLANG>1036</WINLANG>
      <WINOWNER> </WINOWNER>
      <WINPRODID>0000-0000-0000</WINPRODID>
      <WINPRODKEY>FGHI-FGHI-FGHI</WINPRODKEY>
      <WORKGROUP>local.group</WORKGROUP>
    </HARDWARE>
  </CONTENT>
</REQUEST>';
      $xml = @simplexml_load_string($sxml, 'SimpleXMLElement', LIBXML_NOCDATA);

      $array = PluginFusioninventoryFormatconvert::XMLtoArray($xml);

      $expected =  [
         'CONTENT' =>
             [
               'HARDWARE' =>  [
                  'ARCHNAME' => 'MSWin32-x64-multi-thread',
                  'CHASSIS_TYPE' => 'Notebook',
                  'CHECKSUM' => '127855',
                  'ETIME' => '13',
                  'IPADDR' => '192.168.0.224',
                  'LASTLOGGEDUSER' => 'winuser',
                  'MEMORY' => '3887',
                  'NAME' => 'pc-test',
                  'OSNAME' => 'Microsoft Windows 8.1 Professionnel',
                  'OSVERSION' => '6.3.9600',
                  'PROCESSORN' => '1',
                  'PROCESSORS' => '2530',
                  'PROCESSORT' => 'Intel(R) Core(TM) i5 CPU M 540 @ 2.53GHz',
                  'USERID' => 'winuserid',
                  'UUID' => 'ABCDE-ABCDE-ABCDE',
                  'VMSYSTEM' => 'Physical',
                  'WINLANG' => '1036',
                  'WINOWNER' => '',
                  'WINPRODID' => '0000-0000-0000',
                  'WINPRODKEY' => 'FGHI-FGHI-FGHI',
                  'WORKGROUP' => 'local.group'
               ]
             ]
      ];
      $this->assertEquals($expected, $array);
   }


   public function deviceTypeExamplesProvider() {
      $tests = [
                  ['', 'DVD Reader', 'MATSHITA DVD-R UJ-85J', 'Drive'],
                  ['', 'DVD Rom', 'MATSHITA DVD-R UJ-85J', 'Drive'],
                  ['', 'DVD Burning Disc', 'MATSHITA DVD-R UJ-85J', 'Drive'],
                  ['', '', 'MATSHITA DVD-R UJ-85J', 'Drive'],
                  ['', '', 'PLDS DVD+-RW DH-16AES ATA Device', 'Drive'],
                  ['DVD Rom', '', '', 'Drive'],
                  ['sdcard', '', '', 'Drive'],
                  ['sd card', '', '', 'Drive'],
                  ['sd-card', '', '', 'Drive'],
                  ['bluray', '', '', 'Drive'],
                  ['blu ray', '', '', 'Drive'],
                  ['blu-ray', '', '', 'Drive'],
                  ['', 'MicroSD/M2', 'sdb', 'Drive'],
                  ['Generic', 'SDMMC', 'sda', 'Drive'],
                  ['PIONEER DVD-RW  DVR-K06A',
                   'PIONEER DVD-RW  DVR-K06A', '', 'Drive'],
                  ['', '', 'ST3250824AS Q', 'HardDrive'],
                  ['DISK', 'PM951NVMe SAMSUNG 256GB',
                   'nvme0n1', 'HardDrive']
               ];
      return $tests;
   }


   /**
   * Test method getTypeDrive
   * @dataProvider deviceTypeExamplesProvider
   * @test
   */
   function testGetTypeDrive($type, $model, $name, $return_expected) {
      $value = [
         'TYPE' => $type,
         'MODEL' => $model,
         'NAME' => $name
      ];
      $result = PluginFusioninventoryFormatconvert::getTypeDrive($value);
      $this->assertEquals($return_expected, $result);
   }
}
