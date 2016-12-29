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
   @since     2016

   ------------------------------------------------------------------------
 */
/*
 * Test not have problems with history in computers
 * like remove and add a component / software on each inventory
 */

class ComputerHistory extends RestoreDatabase_TestCase {


   /**
    * Test with network card vmxnet3
    *
    * @test
    */
   public function Memory() {
      global $DB;

      $DB->connect();

      $xml = '<?xml version="1.0" encoding="UTF-8" ?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2016-08-15 17:22:22</LOGDATE>
    </ACCESSLOG>
    <BIOS>
      <ASSETTAG>LAPTOP0034</ASSETTAG>
      <BDATE>10/21/2015</BDATE>
      <BMANUFACTURER>Dell Inc.</BMANUFACTURER>
      <BVERSION>A10</BVERSION>
      <MMANUFACTURER>Dell Inc.</MMANUFACTURER>
      <MMODEL>044GCP</MMODEL>
      <MSN>/5H4PRY1/CN1296139D002E/</MSN>
      <SKUNUMBER>Latitude 6430U</SKUNUMBER>
      <SMANUFACTURER>Dell Inc.</SMANUFACTURER>
      <SMODEL>Latitude 6430U</SMODEL>
      <SSN>5H4PRY1</SSN>
    </BIOS>
    <HARDWARE>
      <ARCHNAME>amd64-freebsd-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Laptop</CHASSIS_TYPE>
      <CHECKSUM>131071</CHECKSUM>
      <DATELASTLOGGEDUSER>Mon Aug 15 17:22</DATELASTLOGGEDUSER>
      <DESCRIPTION>amd64/-1-11-30 23:58:05</DESCRIPTION>
      <DNS>192.168.43.1</DNS>
      <ETIME>4</ETIME>
      <IPADDR>10.0.20.254/10.0.20.1/10.0.20.2/10.0.20.3/10.0.20.4/10.0.20.5/10.0.20.6/10.0.20.7/10.0.20.8/10.0.20.9/10.0.20.10/10.0.20.11/10.0.20.12/10.0.20.13/10.0.20.14/10.0.20.15/192.168.43.151</IPADDR>
      <LASTLOGGEDUSER>ddurieux</LASTLOGGEDUSER>
      <MEMORY>8067</MEMORY>
      <NAME>portdavid</NAME>
      <OSCOMMENTS>FreeBSD 10.3-RELEASE #0 r297264: Fri Mar 25 02:10:02 UTC 2016     root@releng1.nyi.freebsd.org:/usr/obj/usr/src/sys/GENERIC </OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>10.3-RELEASE</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2100</PROCESSORS>
      <PROCESSORT>Core i7</PROCESSORT>
      <SWAP>4096</SWAP>
      <USERID>ddurieux</USERID>
      <UUID>4C4C4544-0048-3410-8050-B5C04F525931</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
    </HARDWARE>
    <CONTROLLERS>
      <CAPTION>VMXNET3 Ethernet Controller</CAPTION>
      <MANUFACTURER>VMware</MANUFACTURER>
      <NAME>VMXNET3 Ethernet Controller</NAME>
      <PCISUBSYSTEMID>15ad:07b0</PCISUBSYSTEMID>
      <PRODUCTID>07b0</PRODUCTID>
      <TYPE>vmxnet3 Ethernet Adapter</TYPE>
      <VENDORID>15ad</VENDORID>
    </CONTROLLERS>
    <NETWORKS>
      <DESCRIPTION>vmxnet3 Ethernet Adapter</DESCRIPTION>
      <IPADDRESS>10.225.4.79</IPADDRESS>
      <IPGATEWAY>10.225.4.254</IPGATEWAY>
      <IPMASK>255.255.255.0</IPMASK>
      <IPSUBNET>10.225.4.0</IPSUBNET>
      <MACADDR>00:50:56:BC:0C:90</MACADDR>
      <PCIID>15AD:07B0:07B0:15AD</PCIID>
      <PNPDEVICEID>PCI\VEN_15AD&amp;DEV_07B0&amp;SUBSYS_07B015AD&amp;REV_01\4&amp;21C36F57&amp;0&amp;00A8</PNPDEVICEID>
      <STATUS>Up</STATUS>
      <TYPE>ethernet</TYPE>
      <VIRTUALDEV>0</VIRTUALDEV>
    </NETWORKS>
  </CONTENT>
  <DEVICEID>portdavid-2016-08-15-17-22-21</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>';

      $pfCommunication = new PluginFusioninventoryCommunication();
      $log = new Log();

      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      $DB->query("TRUNCATE TABLE `glpi_logs`");

      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      $this->assertEquals(1, countElementsInTable('glpi_computers'));
      $this->assertEquals(0, countElementsInTable('glpi_logs'), print_r($log->find()));

   }


}
