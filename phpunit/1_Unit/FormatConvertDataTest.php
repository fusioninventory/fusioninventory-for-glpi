<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

class FormatConvertDataTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function JsontoArray() {
      global $DB;

      $DB->connect();

      $a_inventory['hardware'] = array(
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
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->JSONtoArray(json_encode($a_inventory));

      $a_reference['HARDWARE'] = array(
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

      );
      $this->assertEquals($a_reference, $a_return);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


   /**
    * @test
    */
   public function Replaceids() {
      global $DB;

      $DB->connect();

      $a_inventory['software'] = array();
      $a_inventory['Computer'] = array(
          'name'                             => 'pc',
          'users_id'                         => 0,
          'operatingsystems_id'              => 'freebsd',
          'operatingsystemversions_id'       => '9.1-RELEASE'
          );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_inventory = $pfFormatconvert->replaceids($a_inventory, 'Computer', 0);

      $a_reference['software'] = array();
      $a_reference['Computer'] = array(
          'name'                             => 'pc',
          'users_id'                         => 0,
          'operatingsystems_id'              => 1,
          'operatingsystemversions_id'       => 1
          );

       $this->assertEquals($a_reference, $a_inventory);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }



   /**
    * @test
    */
   function DiscoveryDeviceConvert() {
      global $DB;

      $DB->connect();

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

      PluginFusioninventoryFormatconvert::XMLtoArray($xml);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

   }
}
?>
