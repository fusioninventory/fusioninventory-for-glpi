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
   @since     2016

   ------------------------------------------------------------------------
 */
/*
 * Test not have problems with history in computers
 * like remove and add a component / software on each inventory
 */

use PHPUnit\Framework\TestCase;

class ComputerHistoryTest extends TestCase {

   private $xml_bsd = '<?xml version="1.0" encoding="UTF-8" ?>
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
    <SOFTWARES>
      <COMMENTS>PHP Scripting Language</COMMENTS>
      <NAME>php70</NAME>
      <VERSION>7.0.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Robust and small WWW server</COMMENTS>
      <NAME>nginx</NAME>
      <VERSION>1.10.2_3,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Port scanning utility for large networks</COMMENTS>
      <NAME>nmap</NAME>
      <VERSION>7.40</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Network file distribution/synchronization utility</COMMENTS>
      <NAME>rsync</NAME>
      <VERSION>3.1.2_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Object-oriented interpreted scripting language</COMMENTS>
      <NAME>ruby</NAME>
      <VERSION>2.3.3_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>WiFi Networks Manager</COMMENTS>
      <NAME>wifimgr</NAME>
      <VERSION>1.11_2</VERSION>
    </SOFTWARES>
  </CONTENT>
  <DEVICEID>portdavid-2016-08-15-17-22-21</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>';

   private $xml_linux = '<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2017-02-01 06:27:09</LOGDATE>
    </ACCESSLOG>
    <BIOS>
      <ASSETTAG/>  <BDATE>03/01/2016</BDATE>
      <BMANUFACTURER>Dell Inc.</BMANUFACTURER>
      <BVERSION>1.3.3</BVERSION>
      <MMANUFACTURER>Dell Inc.</MMANUFACTURER>
      <MMODEL>07TYC2</MMODEL>
      <MSN>/5BTGP72/CN12963646012E/</MSN>
      <SKUNUMBER>0704</SKUNUMBER>
      <SMANUFACTURER>Dell Inc.</SMANUFACTURER>
      <SMODEL>XPS 13 9350</SMODEL>
      <SSN>5BTGP72</SSN>
    </BIOS>
    <HARDWARE>
      <ARCHNAME>x86_64-linux-gnu-thread-multi</ARCHNAME>
      <CHASSIS_TYPE>Laptop</CHASSIS_TYPE>
      <CHECKSUM>70383</CHECKSUM>
      <DATELASTLOGGEDUSER>Mon Jan 30 16:49</DATELASTLOGGEDUSER>
      <DEFAULTGATEWAY>172.28.213.1</DEFAULTGATEWAY>
      <DNS>172.28.200.20/127.0.0.1</DNS>
      <ETIME>3</ETIME>
      <IPADDR>172.28.213.147/172.28.213.114/172.17.0.1</IPADDR>
      <LASTLOGGEDUSER>adelauna</LASTLOGGEDUSER>
      <MEMORY>7830</MEMORY>
      <NAME>LU002</NAME>
      <OSCOMMENTS>#201611260431 SMP Sat Nov 26 09:33:21 UTC 2016</OSCOMMENTS>
      <OSNAME>Ubuntu 16.04.1 LTS</OSNAME>
      <OSVERSION>4.8.11-040811-generic</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2300</PROCESSORS>
      <PROCESSORT>Intel(R) Core(TM) i5-6200U CPU @ 2.30GHz</PROCESSORT>
      <SWAP>8035</SWAP>
      <USERID>adelaunay</USERID>
      <UUID>4C4C4544-0042-5410-8047-B5C04F503732</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WINPRODID>ID-1000010001</WINPRODID>
      <WORKGROUP>ad.teclib.infra/luisant.chartres.workgroup.teclib.infra</WORKGROUP>
    </HARDWARE>
    <OPERATINGSYSTEM>
      <ARCH>x86_64</ARCH>
      <BOOT_TIME>2017-01-19 09:16:01</BOOT_TIME>
      <DNS_DOMAIN>luisant.chartres.workgroup.teclib.infra</DNS_DOMAIN>
      <FQDN>LU002.luisant.chartres.workgroup.teclib.infra</FQDN>
      <FULL_NAME>Ubuntu 16.04.1 LTS</FULL_NAME>
      <KERNEL_NAME>linux</KERNEL_NAME>
      <KERNEL_VERSION>4.8.11-040811-generic</KERNEL_VERSION>
      <NAME>Ubuntu</NAME>
      <SSH_KEY>ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCs5iAKhbVj9K2Od0PELoslRyiPEmXFrCuWgxO6K2LXJMSW39cXG80MDlTyOmXIiqgi+6KWclUWlP8vrUF/MWRdcLZOjVVtaFZrz3THh6uezB853CTiP4T6/7s6aVvWaa8MhreLFrYPxe9Vy4f3mrxDXY0LbtmRSR1LY34jd6eUcSxMtjB9JOAjfMyY64hsjgWE3w/CXQPezkxgrTqCKbbZCHKp+btHa40kh+BgMKZaW1nAJ7wLBNaTBi8ypDp01wSIOwusKhj/cXxrgRFMO9GmzhyrODrh6cZz6tX1oZZwmGlk0hYy2IvUmLKKPIzc2Z/ZSUrfDJwyg7fzy51OOqqn</SSH_KEY>
      <VERSION>16.04</VERSION>
    </OPERATINGSYSTEM>
    <SOFTWARES>
      <ARCH>amd64</ARCH>
      <FILESIZE>669</FILESIZE>
      <FROM>deb</FROM>
      <NAME>perl</NAME>
      <PUBLISHER>Ubuntu</PUBLISHER>
      <VERSION>5.22.1-9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <ARCH>amd64</ARCH>
      <FILESIZE>4401</FILESIZE>
      <FROM>deb</FROM>
      <NAME>php7.0-fpm</NAME>
      <PUBLISHER>Ubuntu</PUBLISHER>
      <VERSION>7.0.14-2+deb.sury.org~xenial+1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <ARCH>all</ARCH>
      <FILESIZE>37</FILESIZE>
      <FROM>deb</FROM>
      <NAME>nginx</NAME>
      <PUBLISHER>Ubuntu</PUBLISHER>
      <VERSION>1.10.0-0ubuntu0.16.04.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <ARCH>amd64</ARCH>
      <FILESIZE>6716</FILESIZE>
      <FROM>deb</FROM>
      <NAME>unity</NAME>
      <PUBLISHER>Ubuntu</PUBLISHER>
      <VERSION>7.4.0+16.04.20160906-0ubuntu1</VERSION>
    </SOFTWARES>
  </CONTENT>
  <DEVICEID>LU002-2016-05-12-10-04-59</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>';


   /**
    * Test with network card vmxnet3
    *
    * @test
    */
   public function Memory() {
      $this->checkComputerLogs($this->xml_bsd);
      $this->checkComputerLogs($this->xml_linux);
   }


   public function checkComputerLogs($xml = "") {
      global $DB;

      $pfCommunication = new PluginFusioninventoryCommunication();
      $log             = new Log();
      $computer        = new Computer();

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // add computer
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      $DB->query("TRUNCATE TABLE `glpi_logs`");

      // find number of computers
      $found = $computer->find();
      $nb_computer = count($found);

      // update computer
      $pfCommunication->handleOCSCommunication('', $xml, 'glpi');
      $this->assertEquals($nb_computer, countElementsInTable('glpi_computers'));
      $this->assertEquals(0, countElementsInTable('glpi_logs'), print_r($log->find(), true));
   }
}
