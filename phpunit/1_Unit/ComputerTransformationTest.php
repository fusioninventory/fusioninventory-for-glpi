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

class ComputerTransformation extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function ComputerGeneral() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'ARCHNAME'             => 'i386-freebsd-thread-multi',
                'CHASSIS_TYPE'         => 'Notebook',
                'CHECKSUM'             => '131071',
                'DATELASTLOGGEDUSER'   => 'Fri Feb  1 10:56',
                'DEFAULTGATEWAY'       => '',
                'DESCRIPTION'          => 'amd64/-1-11-30 22:04:44',
                'DNS'                  => '8.8.8.8',
                'ETIME'                => '1',
                'IPADDR'               => '',
                'LASTLOGGEDUSER'       => 'ddurieux',
                'MEMORY'               => '3802',
                'NAME'                 => 'pc',
                'OSCOMMENTS'           => 'GENERIC ()root@farrell.cse.buffalo.edu',
                'OSNAME'               => 'freebsd',
                'OSVERSION'            => '9.1-RELEASE',
                'PROCESSORN'           => '4',
                'PROCESSORS'           => '2400',
                'PROCESSORT'           => 'Core i3',
                'SWAP'                 => '0',
                'USERDOMAIN'           => '',
                'USERID'               => 'ddurieux',
                'UUID'                 => '68405E00-E5BE-11DF-801C-B05981201220',
                'VMSYSTEM'             => 'Physical',
                'WORKGROUP'            => 'mydomain.local'
            );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                        => '',
              'wincompany'                      => '',
              'operatingsystem_installationdate'=> 'NULL',
              'last_fusioninventory_update'     => $date,
              'oscomment'                       => 'amd64/-1-11-30 22:04:44',
              'items_operatingsystems_id'       => array(
                'operatingsystems_id'              => 'freebsd',
                'operatingsystemversions_id'       => '9.1-RELEASE',
                'operatingsystemservicepacks_id'   => 'GENERIC ()root@farrell.cse.buffalo.edu',
                'operatingsystemarchitectures_id'  => '',
                'operatingsystemkernels_id'        => '',
                'operatingsystemkernelversions_id' => '',
                'operatingsystemeditions_id'       => '',
                'licenseid'                       => '',
                'license_number'                   => '',
              )
          ),
          'soundcard'               => array(),
          'graphiccard'             => array(),
          'controller'              => array(),
          'processor'               => array(),
          'computerdisk'            => array(),
          'memory'                  => array(
              array(
                  'size'        => '3802',
                  'designation' => 'Dummy Memory Module',
                  'frequence' => 0,
                  'serial' => '',
                  'devicememorytypes_id' => '',
                  'busID' => ''
              )
          ),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          );
      $a_reference['Computer'] = array(
          'name'                             => 'pc',
          'users_id'                         => 0,
          'uuid'                             => '68405E00-E5BE-11DF-801C-B05981201220',
          'domains_id'                       => 'mydomain.local',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => '',
          'computertypes_id'                 => 'Notebook',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux'
      );
      $this->assertEquals($a_reference, $a_return);
   }



   /**
    * @test
    */
   public function ComputerUsers() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'                 => 'pc',
                'LASTLOGGEDUSER'       => 'ddurieux',
                'USERID'               => 'ddurieux',
            );
      $a_computer['USERS'][] = array('LOGIN'  => 'ddurieux');
      $a_computer['USERS'][] = array('LOGIN'  => 'admin',
                                     'DOMAIN' => 'local.com');

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                        => '',
              'wincompany'                      => '',
              'operatingsystem_installationdate'=> 'NULL',
              'last_fusioninventory_update'     => $date,
              'items_operatingsystems_id' => array(
                  'operatingsystems_id'              => '',
                  'operatingsystemversions_id'       => '',
                  'operatingsystemservicepacks_id'   => '',
                  'operatingsystems_id'              => '',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                       => '',
                  'license_number'                   => ''
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          );
      $a_reference['Computer'] = array(
          'name'                             => 'pc',
          'users_id'                         => 0,
          'uuid'                             => '',
          'domains_id'                       => '',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => '',
          'computertypes_id'                 => '',
          'is_dynamic'                       => 1,
          'contact'                          => 'ddurieux/admin@local.com'
      );
      $this->assertEquals($a_reference, $a_return);
   }



   /**
    * @test
    */
   public function ComputerOperatingSystem() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['OPERATINGSYSTEM'] = array(
          'FULL_NAME'      => 'Microsoft Windows XP Professionnel',
          'INSTALL_DATE'   => '2012-10-16 08:12:56',
          'KERNEL_NAME'    => 'MSWin32',
          'KERNEL_VERSION' => '5.1.2600',
          'NAME'           => 'Windows',
          'SERVICE_PACK'   => 'Service Pack 3',
          'ARCH'           => '32 bits');


      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                                 => 'test',
              'wincompany'                               => 'siprossii',
              'operatingsystem_installationdate'         => '2012-10-16 08:12:56',
              'last_fusioninventory_update'              => $date,
              'oscomment'                                => '',
              'items_operatingsystems_id' => array(
                  'operatingsystems_id'              => 'Windows',
                  'operatingsystemversions_id'       => 'XP',
                  'operatingsystemservicepacks_id'   => 'Service Pack 3',
                  'operatingsystemkernels_id'        => 'MSWin32',
                  'operatingsystemkernelversions_id' => '5.1.2600',
                  'operatingsystemarchitectures_id'  => '32 bits',
                  'operatingsystemeditions_id'       => 'Professionnel',
                  'licenseid'                       => '76413-OEM-0054453-04701',
                  'license_number'                   => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          );
      $a_reference['Computer'] = array(
          'name'                             => 'vbox-winxp',
          'uuid'                             => '',
          'domains_id'                       => 'WORKGROUP',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => '',
          'computertypes_id'                 => 'VirtualBox',
          'is_dynamic'                       => 1,
     );
     $this->assertEquals($a_reference, $a_return);


      $operatingsystems = array(
//          array(
//              array(
//                  'ARCH'           => '',
//                  'FULL_NAME'      => '',
//                  'KERNEL_NAME'    => '',
//                  'KERNEL_VERSION' => '',
//                  'NAME'           => '',
//                  'SERVICE_PACK'   => ''
//              ),
//              array(
//                  'arch'        => '',
//                  'kernname'    => '',
//                  'kernversion' => '',
//                  'os'          => '',
//                  'osversion'   => '',
//                  'servicepack' => '',
//                  'edition'     => ''
//              ),
//              array(
//                  'arch'        => '',
//                  'kernname'    => '',
//                  'kernversion' => '',
//                  'os'          => '',
//                  'osversion'   => '',
//                  'servicepack' => '',
//                  'edition'     => ''
//              )
//          ),
          array(
              array(
                  'ARCH'           => '64-bit',
                  'FULL_NAME'      => 'Microsoft Windows 7 Enterprise',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.1.7600',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7600',
                  'os'          => 'Windows',
                  'osversion'   => '7',
                  'servicepack' => '',
                  'edition'     => 'Enterprise'
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7600',
                  'os'          => 'Microsoft Windows 7 Enterprise',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'SUSE Linux Enterprise Server 12 (x86_64)',
                  'KERNEL_NAME'    => 'linux',
                  'KERNEL_VERSION' => '3.12.43-52.6-default',
                  'NAME'           => 'SuSE',
                  'SERVICE_PACK'   => '0',
                  'VERSION'        => '12'
              ),
              array(
                  'arch'        => 'x86_64',
                  'kernname'    => 'linux',
                  'kernversion' => '3.12.43-52.6-default',
                  'os'          => 'SuSE',
                  'osversion'   => '12',
                  'servicepack' => '',
                  'edition'     => 'Enterprise Server'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.12.43-52.6-default',
                  'os'          => 'SUSE Linux Enterprise Server 12 (x86_64)',
                  'osversion'   => '12',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Debian GNU/Linux 7.8 (wheezy)',
                  'KERNEL_NAME'    => 'linux',
                  'KERNEL_VERSION' => '3.2.0-4-amd64',
                  'NAME'           => 'Debian',
                  'VERSION'        => '7.8'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.2.0-4-amd64',
                  'os'          => 'Debian',
                  'osversion'   => '7.8',
                  'servicepack' => '',
                  'edition'     => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.2.0-4-amd64',
                  'os'          => 'Debian GNU/Linux 7.8 (wheezy)',
                  'osversion'   => '7.8',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'ARCH'           => 'x86_64',
                  'FULL_NAME'      => 'Debian GNU/Linux 8.4 (jessie)',
                  'KERNEL_NAME'    => 'linux',
                  'KERNEL_VERSION' => '3.16.0-4-amd64',
                  'NAME'           => 'Debian',
                  'VERSION'        => '8.4'
              ),
              array(
                  'arch'        => 'x86_64',
                  'kernname'    => 'linux',
                  'kernversion' => '3.16.0-4-amd64',
                  'os'          => 'Debian',
                  'osversion'   => '8.4',
                  'servicepack' => '',
                  'edition'     => ''
              ),
              array(
                  'arch'        => 'x86_64',
                  'kernname'    => 'linux',
                  'kernversion' => '3.16.0-4-amd64',
                  'os'          => 'Debian GNU/Linux 8.4 (jessie)',
                  'osversion'   => '8.4',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'ARCH'           => '32-bit',
                  'FULL_NAME'      => 'Microsoft Windows Embedded Standard',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.1.7601',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 1'
              ),
              array(
                  'arch'        => '32-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7601',
                  'os'          => 'Windows',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => 'Embedded Standard'
              ),
              array(
                  'arch'        => '32-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7601',
                  'os'          => 'Microsoft Windows Embedded Standard',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'MicrosoftÂ® Windows ServerÂ® 2008 Standard',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.0.6002',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 2'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.0.6002',
                  'os'          => 'Windows',
                  'osversion'   => '2008',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => 'ServerÂ® Standard'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.0.6002',
                  'os'          => 'MicrosoftÂ® Windows ServerÂ® 2008 Standard',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft(R) Windows(R) Server 2003, Standard Edition',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '5.2.3790',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 2'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.2.3790',
                  'os'          => 'Windows',
                  'osversion'   => '2003',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => 'Server Standard Edition'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.2.3790',
                  'os'          => 'Microsoft(R) Windows(R) Server 2003, Standard Edition',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft Windows Server 2012 R2 Datacenter',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.3.9600',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Windows',
                  'osversion'   => '2012 R2',
                  'servicepack' => '',
                  'edition'     => 'Server Datacenter'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Microsoft Windows Server 2012 R2 Datacenter',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'ARCH'           => '64-bit',
                  'FULL_NAME'      => 'Microsoft Windows Server 2008 R2 Datacenter',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.1.7601',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 1'
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7601',
                  'os'          => 'Windows',
                  'osversion'   => '2008 R2',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => 'Server Datacenter'
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.1.7601',
                  'os'          => 'Microsoft Windows Server 2008 R2 Datacenter',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft® Windows Vista™ Professionnel',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.0.6001',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 1'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.0.6001',
                  'os'          => 'Windows',
                  'osversion'   => 'Vista',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => 'Professionnel'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.0.6001',
                  'os'          => 'Microsoft® Windows Vista™ Professionnel',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 1',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft(R) Windows(R) Server 2003, Standard Edition x64',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '5.2.3790',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 2'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.2.3790',
                  'os'          => 'Windows',
                  'osversion'   => '2003',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => 'Server Standard Edition x64'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.2.3790',
                  'os'          => 'Microsoft(R) Windows(R) Server 2003, Standard Edition x64',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 2',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft Windows XP Édition familiale',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '5.1.2600',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 3'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.1.2600',
                  'os'          => 'Windows',
                  'osversion'   => 'XP',
                  'servicepack' => 'Service Pack 3',
                  'edition'     => 'Édition familiale'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.1.2600',
                  'os'          => 'Microsoft Windows XP Édition familiale',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 3',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft Windows 2000 Professionnel',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '5.0.2195',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => 'Service Pack 4'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.0.2195',
                  'os'          => 'Windows',
                  'osversion'   => '2000',
                  'servicepack' => 'Service Pack 4',
                  'edition'     => 'Professionnel'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '5.0.2195',
                  'os'          => 'Microsoft Windows 2000 Professionnel',
                  'osversion'   => '',
                  'servicepack' => 'Service Pack 4',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Microsoft Hyper-V Server 2012 R2',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.3.9600',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Windows',
                  'osversion'   => '2012 R2',
                  'servicepack' => '',
                  'edition'     => 'Hyper-V Server'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Microsoft Hyper-V Server 2012 R2',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )

          ),
          array(
              array(
                  'ARCH'           => 'x86_64',
                  'FULL_NAME'      => 'Fedora release 23 (Twenty Three)',
                  'KERNEL_NAME'    => 'linux',
                  'KERNEL_VERSION' => '4.4.6-301.fc23.x86_64',
                  'NAME'           => 'Fedora',
                  'VERSION'        => '23'
              ),
              array(
                  'arch'        => 'x86_64',
                  'kernname'    => 'linux',
                  'kernversion' => '4.4.6-301.fc23.x86_64',
                  'os'          => 'Fedora',
                  'osversion'   => '23',
                  'servicepack' => '',
                  'edition'     => ''
              ),
              array(
                  'arch'        => 'x86_64',
                  'kernname'    => 'linux',
                  'kernversion' => '4.4.6-301.fc23.x86_64',
                  'os'          => 'Fedora release 23 (Twenty Three)',
                  'osversion'   => '23',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'ThinPro 5.2.0',
                  'KERNEL_NAME'    => 'linux',
                  'KERNEL_VERSION' => '3.8.13-hp',
                  'NAME'           => 'ThinPro',
                  'VERSION'        => '5.2.0'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.8.13-hp',
                  'os'          => 'ThinPro',
                  'osversion'   => '5.2.0',
                  'servicepack' => '',
                  'edition'     => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.8.13-hp',
                  'os'          => 'ThinPro 5.2.0',
                  'osversion'   => '5.2.0',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'ARCH'           => '64-bit',
                  'FULL_NAME'      => 'Microsoft Windows 10 Professionnel',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '10.0.10586',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '10.0.10586',
                  'os'          => 'Windows',
                  'osversion'   => '10',
                  'servicepack' => '',
                  'edition'     => 'Professionnel'
              ),
              array(
                  'arch'        => '64-bit',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '10.0.10586',
                  'os'          => 'Microsoft Windows 10 Professionnel',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'   => 'Debian GNU/Linux 7.4 (wheezy)',
                  'VERSION'     => '3.2.0-2-amd64',
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'linux',
                  'kernversion' => '3.2.0-2-amd64',
                  'os'          => 'Debian',
                  'osversion'   => '7.4 (wheezy)',
                  'servicepack' => '',
                  'edition'     => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => '',
                  'kernversion' => '',
                  'os'          => 'Debian GNU/Linux 7.4 (wheezy)',
                  'osversion'   => '3.2.0-2-amd64',
                  'servicepack' => '',
                  'edition'     => ''
              )
          ),
          array(
              array(
                  'FULL_NAME'      => 'Майкрософт Windows 8.1 Профессиональная',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '6.3.9600',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Windows',
                  'osversion'   => '8.1',
                  'servicepack' => '',
                  'edition'     => 'Профессиональная'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '6.3.9600',
                  'os'          => 'Майкрософт Windows 8.1 Профессиональная',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )
         ),
         array(
              array(
                  'FULL_NAME'      => 'Майкрософт Windows 10 Pro',
                  'KERNEL_NAME'    => 'MSWin32',
                  'KERNEL_VERSION' => '10.0.10586',
                  'NAME'           => 'Windows',
                  'SERVICE_PACK'   => ''
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '10.0.10586',
                  'os'          => 'Windows',
                  'osversion'   => '10',
                  'servicepack' => '',
                  'edition'     => 'Pro'
              ),
              array(
                  'arch'        => '',
                  'kernname'    => 'MSWin32',
                  'kernversion' => '10.0.10586',
                  'os'          => 'Майкрософт Windows 10 Pro',
                  'osversion'   => '',
                  'servicepack' => '',
                  'edition'     => ''
              )
         )
      );
      $mapping = array(
          'arch'        => 'operatingsystemarchitectures_id',
          'kernname'    => 'operatingsystemkernels_id',
          'kernversion' => 'operatingsystemkernelversions_id',
          'os'          => 'operatingsystems_id',
          'osversion'   => 'operatingsystemversions_id',
          'servicepack' => 'operatingsystemservicepacks_id',
          'edition'     => 'operatingsystemeditions_id'
      );
      foreach ($operatingsystems as $operatingsystem) {
         $a_computer = array();
         $a_computer['OPERATINGSYSTEM'] = $operatingsystem[0];
         $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
            );

         //test with managed OS name
         $PF_CONFIG['manage_osname'] = '1';
         $a_reference = array();
         foreach ($operatingsystem[1] as $key=>$value) {
             $a_reference[$mapping[$key]] = $value;
         }
         $a_reference['licenseid'] = '';
         $a_reference['license_number'] = '';
         $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
         $GLPIlog = new GLPIlogs();
         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();
         $this->assertEquals($a_reference, $a_return['fusioninventorycomputer']['items_operatingsystems_id']);

         //test with unmanaged OS name
         $PF_CONFIG['manage_osname'] = '0';
         $a_reference = array();
         foreach ($operatingsystem[2] as $key=>$value) {
             $a_reference[$mapping[$key]] = $value;
         }
         $a_reference['licenseid'] = '';
         $a_reference['license_number'] = '';
         $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
         $GLPIlog = new GLPIlogs();
         $GLPIlog->testSQLlogs();
         $GLPIlog->testPHPlogs();
         $this->assertEquals($a_reference, $a_return['fusioninventorycomputer']['items_operatingsystems_id']);
      }
   }



   /**
    * @test
    */
   public function ComputerOperatingSystemOCSType() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3',
                'OSNAME'         => 'Microsoft Windows XP Professionnel',
                'OSVERSION'      => '5.1.2600',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );


      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> 'NULL',
              'last_fusioninventory_update'     => $date,
              'oscomment'                       => '',
              'items_operatingsystems_id' => array(
                  'operatingsystems_id'              => 'Microsoft Windows XP Professionnel',
                  'operatingsystemversions_id'       => '5.1.2600',
                  'operatingsystemservicepacks_id'   => 'Service Pack 3',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                       => '76413-OEM-0054453-04701',
                  'license_number'                   => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3'
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          );
      $a_reference['Computer'] = array(
          'name'                             => 'vbox-winxp',
          'uuid'                             => '',
          'domains_id'                       => 'WORKGROUP',
          'manufacturers_id'                 => '',
          'computermodels_id'                => '',
          'serial'                           => '',
          'computertypes_id'                 => 'VirtualBox',
          'is_dynamic'                       => 1
     );
     $this->assertEquals($a_reference, $a_return);
   }



   /**
    * @test
    */
   public function ComputerProcessor() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['CPUS'] = Array(
            Array(
                'EXTERNAL_CLOCK' => 133,
                'FAMILYNAME'     => 'Core i3',
                'FAMILYNUMBER'   => 6,
                'ID'             => '55 06 02 00 FF FB EB BF',
                'MANUFACTURER'   => 'Intel Corporation',
                'MODEL'          => '37',
                'NAME'           => 'Core i3',
                'SPEED'          => 2400,
                'STEPPING'       => 5
                ));


      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference[0] = array(
                    'manufacturers_id'  => 'Intel Corporation',
                    'designation'       => 'Core i3',
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'frequency_default' => 2400,
                    'nbcores'           => '0',
                    'nbthreads'         => '0'
          );

      $this->assertEquals($a_reference, $a_return['processor']);
   }

   /**
    * @test
    */
   public function ComputerHook_addinventoryinfos () {
      global $DB, $PLUGIN_HOOKS;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = [
        'HARDWARE' => [
           'NAME'           => 'vbox-winxp',
        ]
      ];

      $callable = function($params) {
         $params['inventory']['OPERATINGSYSTEM']['FULL_NAME'] =
            'Fedora release 23 (Twenty Three)';
         return $params;
      };
      $PLUGIN_HOOKS['fusioninventory_addinventoryinfos']['tests'] = $callable;

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return        = $pfFormatconvert->computerInventoryTransformation($a_computer);

      unset($PLUGIN_HOOKS['fusioninventory_addinventoryinfos']);

      $this->assertTrue(isset($a_return['OPERATINGSYSTEM']));
      $this->assertTrue(isset($a_return['OPERATINGSYSTEM']['FULL_NAME']));
      $this->assertEquals('Fedora release 23 (Twenty Three)',
                          $a_return['OPERATINGSYSTEM']['FULL_NAME']);
   }

   /**
    * @test
    */
   public function ComputerMonitor() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['MONITORS'] = array(
            array(
               'BASE64'       => 'AP///////wA4o75h/gQAABsLAQOA////zgAAoFdJmyYQSE...',
               'CAPTION'      => 'Écran Plug-and-Play',
               'DESCRIPTION'  => '27/2001',
               'MANUFACTURER' => 'NEC Technologies, Inc.'
                ),
            array(
               'BASE64'       => 'AP///////wAwrhBAAAAAACgSAQOAGhB46uWVk1ZPkCgoUFQAAAABA...',
               'CAPTION'      => 'ThinkPad Display 1280x800',
               'MANUFACTURER' => 'Lenovo',
               'SERIAL'       => 'UBYVUTFYEIUI'
             )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference = array();
      $a_reference[0] = array(
            'manufacturers_id'   => 'NEC Technologies, Inc.',
            'name'               => 'Écran Plug-and-Play',
            'serial'             => '',
            'is_dynamic'         => 1
          );
      $a_reference[1] = array(
            'manufacturers_id'   => 'Lenovo',
            'name'               => 'ThinkPad Display 1280x800',
            'serial'             => 'UBYVUTFYEIUI',
            'is_dynamic'         => 1
          );
      $this->assertEquals($a_reference, $a_return['monitor']);
   }



   /**
    * @test
    */
   public function ComputerLicenses() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['LICENSEINFOS'] = array(
            array(
               'COMPONENTS' => 'Word/Excel/Access/Outlook/PowerPoint/Publisher/InfoPath',
               'FULLNAME'   => 'Microsoft Office Professional Edition 2003',
               'KEY'        => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx',
               'NAME'       => 'Microsoft Office 2003',
               'PRODUCTID'  => 'xxxxx-640-0000xxx-xxxxx'
                )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference = array();
      $a_reference[0] = array(
            'name'      => 'Microsoft Office 2003',
            'fullname'  => 'Microsoft Office Professional Edition 2003',
            'serial'    => 'xxxxx-xxxxx-P6RC4-xxxxx-xxxxx'
          );
      $this->assertEquals($a_reference, $a_return['licenseinfo']);
   }



   /**
    * @test
    */
   public function ComputerBiosVirtual() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3',
                'OSNAME'         => 'Microsoft Windows XP Professionnel',
                'OSVERSION'      => '5.1.2600',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['BIOS'] = array(
          'ASSETTAG'      => '',
          'BDATE'         => '05/30/2006',
          'BMANUFACTURER' => 'Dell Inc.',
          'BVERSION'      => 'A05',
          'MMANUFACTURER' => 'Dell Inc.',
          'MMODEL'        => '0FJ030',
          'MSN'           => '..CN7082166DF04E.',
          'SKUNUMBER'     => '',
          'SMANUFACTURER' => 'Dell Inc.',
          'SMODEL'        => 'Dell DXP051',
          'SSN'           => '6PkkD1K'
          );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                                 => 'test',
              'wincompany'                               => 'siprossii',
              'operatingsystem_installationdate'         => 'NULL',
              'last_fusioninventory_update'              => $date,
              'oscomment'                                => '',
              'items_operatingsystems_id' => array(
                  'operatingsystems_id'              => 'Microsoft Windows XP Professionnel',
                  'operatingsystemversions_id'       => '5.1.2600',
                  'operatingsystemservicepacks_id'   => 'Service Pack 3',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                       => '76413-OEM-0054453-04701',
                  'license_number'                   => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3'
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          'bios'           => array(
              'date'             => '2006-05-30',
              'version'          => 'A05',
              'manufacturers_id' => 'Dell Inc.',
              'serial'           => '',
              'designation'      => 'Dell Inc. BIOS'
          )
          );
      $a_reference['Computer'] = array(
          'name'                             => 'vbox-winxp',
         'uuid'                             => '',
          'domains_id'                       => 'WORKGROUP',
          'manufacturers_id'                 => 'Dell Inc.',
          'computermodels_id'                => 'Dell DXP051',
          'serial'                           => '6PkkD1K',
          'mserial'                          => '..CN7082166DF04E.',
          'computertypes_id'                 => 'VirtualBox',
          'is_dynamic'                       => 1,
          'mmanufacturer'                    => 'Dell Inc.',
          'bmanufacturer'                    => 'Dell Inc.',
          'mmodel'                           => '0FJ030'
      );
      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function ComputerBiosPhysical() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3',
                'OSNAME'         => 'Microsoft Windows XP Professionnel',
                'OSVERSION'      => '5.1.2600',
                'VMSYSTEM'       => 'Physical',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['BIOS'] = array(
          'ASSETTAG'      => '',
          'BDATE'         => '05/30/2006',
          'BMANUFACTURER' => 'Dell Inc.',
          'BVERSION'      => 'A05',
          'MMANUFACTURER' => 'Dell Inc.',
          'MMODEL'        => '0FJ030',
          'MSN'           => '..CN7082166DF04E.',
          'SKUNUMBER'     => '',
          'SMANUFACTURER' => 'Dell Inc.',
          'SMODEL'        => 'Dell DXP051',
          'SSN'           => '6PkkD1K');

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                                 => 'test',
              'wincompany'                               => 'siprossii',
              'operatingsystem_installationdate'         => 'NULL',
              'last_fusioninventory_update'              => $date,
              'oscomment'                                => '',
              'items_operatingsystems_id'                => array(
                  'operatingsystems_id'              => 'Microsoft Windows XP Professionnel',
                  'operatingsystemversions_id'       => '5.1.2600',
                  'operatingsystemservicepacks_id'   => 'Service Pack 3',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                       => '76413-OEM-0054453-04701',
                  'license_number'                   => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3'
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          'bios'           => array(
              'date'             => '2006-05-30',
              'version'          => 'A05',
              'manufacturers_id' => 'Dell Inc.',
              'serial'           => '',
              'designation'      => 'Dell Inc. BIOS'
          )
          );
      $a_reference['Computer'] = array(
          'name'                             => 'vbox-winxp',
          'uuid'                             => '',
          'domains_id'                       => 'WORKGROUP',
          'manufacturers_id'                 => 'Dell Inc.',
          'computermodels_id'                => 'Dell DXP051',
          'serial'                           => '6PkkD1K',
          'mserial'                          => '..CN7082166DF04E.',
          'computertypes_id'                 => '0FJ030',
          'is_dynamic'                       => 1,
          'mmanufacturer'                    => 'Dell Inc.',
          'bmanufacturer'                    => 'Dell Inc.',
          'mmodel'                           => '0FJ030'
          );
      $this->assertEquals($a_reference, $a_return);
   }



   /**
    * @test
    */
   public function ComputerCDROM() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['STORAGES'] = array(
            array(
               'DESCRIPTION'  => 'Lecteur de CD-ROM',
               'MANUFACTURER' => '(Lecteurs de CD-ROM standard)',
               'MODEL'        => 'hp DVD RW AD-7251H5 ATA Device',
               'NAME'         => 'hp DVD RW AD-7251H5 ATA Device',
               'SCSI_COID'    => '3',
               'SCSI_LUN'     => '0',
               'SCSI_UNID'    => '0',
               'SERIALNUMBER' => '',
               'TYPE'         => 'DVD Writer',
                )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference = array();
      $a_reference[0] = array(
            'serial'            => '',
            'designation'       => 'hp DVD RW AD-7251H5 ATA Device',
            'interfacetypes_id' => 'DVD Writer',
            'manufacturers_id'  => '(Lecteurs de CD-ROM standard)'
          );
      $this->assertEquals($a_reference, $a_return['drive']);
   }



   /**
    * @test
    */
   public function ComputerNetworkport() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['NETWORKS'] = array(
         0 => array(
            'DESCRIPTION' => 'Connexion réseau Intel(R) 82566DM-2 Gigabit',
            'IPADDRESS'   => '192.168.20.49',
            'IPDHCP'      => '192.168.20.1',
            'IPGATEWAY'   => '192.168.20.1',
            'IPMASK'      => '255.255.255.0',
            'IPSUBNET'    => '192.168.20.0',
            'MACADDR'     => '00:0F:FE:9C:FA:5D',
            'PCIID'       => '8086:10BD:2818:103C',
            'PNPDEVICEID' => 'PCI\VEN_8086&amp;DEV_10BD&amp;SUBSYS_2818103C&amp;REV_02\3&amp;21436425&amp;0&amp;C8',
            'SPEED'       => '100',
            'STATUS'      => 'Up',
            'TYPE'        => 'ethernet',
            'VIRTUALDEV'  => '0'
         ),
         1 => array(
            'DESCRIPTION'  => 'HBA_Port_WWN_1 /dev/cfg/c1',
            'DRIVER'       => 'qlc',
            'FIRMWARE'     => '02.01.145',
            'MANUFACTURER' => 'QLogic Corp.',
            'MODEL'        => '2200',
            'SPEED'        => '1000',
            'STATUS'       => 'Up',
            'WWN'          => '220000144f3eb274',
         )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $a_reference = array(
         'Connexion réseau Intel(R) 82566DM-2 Gigabit-00:0f:fe:9c:fa:5d' => array(
            'name'               => 'Connexion réseau Intel(R) 82566DM-2 Gigabit',
            'mac'                => '00:0f:fe:9c:fa:5d',
            'instantiation_type' => 'NetworkPortEthernet',
            'ipaddress'          => array('192.168.20.49'),
            'virtualdev'         => 0,
            'subnet'             => '192.168.20.0',
            'ssid'               => '',
            'gateway'            => '192.168.20.1',
            'netmask'            => '255.255.255.0',
            'dhcpserver'         => '192.168.20.1',
            'speed'              => 100,
            'logical_number'     => 1,
            'wwn'                => ''
         ),
         'HBA_Port_WWN_1 /dev/cfg/c1-220000144f3eb274' => array(
            'name'               => 'HBA_Port_WWN_1 /dev/cfg/c1',
            'speed'              => 1000,
            'wwn'                => '220000144f3eb274',
            'mac'                => '',
            'instantiation_type' => 'NetworkPortFiberchannel',
            'virtualdev'         => 0,
            'subnet'             => '',
            'ssid'               => '',
            'gateway'            => '',
            'netmask'            => '',
            'dhcpserver'         => '',
            'logical_number'     => 1,
            'ipaddress'          => [],
         )
      );
      $this->assertEquals($a_reference, $a_return['networkport']);
   }



   /**
    * @test
    *
    * Old agent have networkport speed in b/s and new in Mb/s
    */
   public function ComputerNetworkportOldFormat() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['NETWORKS'] = array(
         0 => array(
            'DESCRIPTION' => 'Intel(R) 82577LM Gigabit Network Connection',
            'IPADDRESS'   => '192.168.20.50',
            'IPDHCP'      => '192.168.20.1',
            'IPGATEWAY'   => '192.168.20.1',
            'IPMASK'      => '255.255.255.0',
            'IPSUBNET'    => '192.168.20.0',
            'MACADDR'     => '5C:26:0A:38:C4:8B',
            'PNPDEVICEID' => 'PCI\VEN_8086&amp;DEV_10EA&amp;SUBSYS_040B1028&amp;REV_05\3&amp;11583659&amp;0&amp;C8',
            'SPEED'       => '100000000',
            'STATUS'      => 'Up',
            'TYPE'        => 'ethernet',
            'VIRTUALDEV'  => '0'
         )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $a_reference = array(
         'Intel(R) 82577LM Gigabit Network Connection-5c:26:0a:38:c4:8b' => array(
            'name'               => 'Intel(R) 82577LM Gigabit Network Connection',
            'mac'                => '5c:26:0a:38:c4:8b',
            'instantiation_type' => 'NetworkPortEthernet',
            'ipaddress'          => array('192.168.20.50'),
            'virtualdev'         => 0,
            'subnet'             => '192.168.20.0',
            'ssid'               => '',
            'gateway'            => '192.168.20.1',
            'netmask'            => '255.255.255.0',
            'dhcpserver'         => '192.168.20.1',
            'speed'              => 100,
            'logical_number'     => 1,
            'wwn'                => ''
         )
      );
      $this->assertEquals($a_reference, $a_return['networkport']);
   }


   /**
    * @test
    */
   public function ComputerBattery() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = [];
      $a_computer['HARDWARE'] = [
         'NAME'           => 'vbox-winxp',
         'ARCHNAME'       => 'MSWin32-x86-multi-thread',
         'CHASSIS_TYPE'   => '',
         'DESCRIPTION'    => '',
         'OSCOMMENTS'     => 'Service Pack 3 BAD',
         'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
         'OSVERSION'      => '5.1.2600 BAD',
         'VMSYSTEM'       => 'VirtualBox',
         'WINCOMPANY'     => 'siprossii',
         'WINLANG'        => '1036',
         'WINOWNER'       => 'test',
         'WINPRODID'      => '76413-OEM-0054453-04701',
         'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
         'WORKGROUP'      => 'WORKGROUP'
      ];

      $a_computer['BATTERIES'] = [
         [
            'CAPACITY'     => '57530',
            'CHEMISTRY'    => 'Li-ION',
            'DATE'         => '21/02/2015',
            'NAME'         => 'THE BATTERY',
            'SERIAL'       => '0E52B',
            'MANUFACTURER' => 'MANU',
            'VOLTAGE'      => '14000'
         ]
      ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference[0] = [
         'capacity'              => '57530',
         'designation'           => 'THE BATTERY',
         'manufacturing_date'    => '2015-02-21',
         'devicebatterytypes_id' => 'Li-ION',
         'manufacturers_id'      => 'MANU',
         'voltage'               => '14000',
         'serial'                => '0E52B'
      ];

      $this->assertEquals($a_reference, $a_return['batteries']);

      $a_computer['BATTERIES'] = [
         [
            'CHEMISTRY'    => 'Li-ION',
            'SERIAL'       => '00000000',
            'MANUFACTURER' => 'OTHER MANU'
         ]
      ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference[0] = [
         'capacity'              => 0,
         'designation'           => '',
         'devicebatterytypes_id' => 'Li-ION',
         'manufacturers_id'      => 'OTHER MANU',
         'voltage'               => '',
         'serial'                => '00000000'
      ];

      $this->assertEquals($a_reference, $a_return['batteries']);

   }


   /**
    * @test
    */
   public function ComputerRemotemgmt() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3 BAD',
                'OSNAME'         => 'Microsoft Windows XP Professionnel BAD',
                'OSVERSION'      => '5.1.2600 BAD',
                'VMSYSTEM'       => 'VirtualBox',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['REMOTE_MGMT'] = array(
            array(
               'ID'   => 'xxxxyyyy1234',
               'TYPE' => 'teamviewer',
                )
      );

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);

      $a_reference = array();
      $a_reference[0] = array(
            'type'   => 'teamviewer',
            'number' => 'xxxxyyyy1234'
          );
      $this->assertEquals($a_reference, $a_return['remote_mgmt']);
   }



   /**
    * @test
    */
   function ComputerBiosAssettag_filled() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_computer = array();
      $a_computer['HARDWARE'] = array(
                'NAME'           => 'vbox-winxp',
                'ARCHNAME'       => 'MSWin32-x86-multi-thread',
                'CHASSIS_TYPE'   => '',
                'DESCRIPTION'    => '',
                'OSCOMMENTS'     => 'Service Pack 3',
                'OSNAME'         => 'Microsoft Windows XP Professionnel',
                'OSVERSION'      => '5.1.2600',
                'VMSYSTEM'       => 'Physical',
                'WINCOMPANY'     => 'siprossii',
                'WINLANG'        => '1036',
                'WINOWNER'       => 'test',
                'WINPRODID'      => '76413-OEM-0054453-04701',
                'WINPRODKEY'     => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3',
                'WORKGROUP'      => 'WORKGROUP'
            );

      $a_computer['BIOS'] = array(
          'ASSETTAG'      => 'LAPTOP0034',
          'BDATE'         => '05/30/2006',
          'BMANUFACTURER' => 'Dell Inc.',
          'BVERSION'      => 'A05',
          'MMANUFACTURER' => 'Dell Inc.',
          'MMODEL'        => '0FJ030',
          'MSN'           => '..CN7082166DF04E.',
          'SKUNUMBER'     => '',
          'SMANUFACTURER' => 'Dell Inc.',
          'SMODEL'        => 'Dell DXP051',
          'SSN'           => '6PkkD1K');

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->computerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['fusioninventorycomputer'])
              && isset($a_return['fusioninventorycomputer']['last_fusioninventory_update'])) {
         $date = $a_return['fusioninventorycomputer']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'fusioninventorycomputer' => Array(
              'winowner'                                 => 'test',
              'wincompany'                               => 'siprossii',
              'operatingsystem_installationdate'         => 'NULL',
              'last_fusioninventory_update'              => $date,
              'oscomment'                                => '',
              'items_operatingsystems_id'                => array(
                  'operatingsystems_id'              => 'Microsoft Windows XP Professionnel',
                  'operatingsystemversions_id'       => '5.1.2600',
                  'operatingsystemservicepacks_id'   => 'Service Pack 3',
                  'operatingsystemarchitectures_id'  => '',
                  'operatingsystemkernels_id'        => '',
                  'operatingsystemkernelversions_id' => '',
                  'operatingsystemeditions_id'       => '',
                  'licenseid'                       => '76413-OEM-0054453-04701',
                  'license_number'                   => 'BW728-6G2PM-2MCWP-VCQ79-DCWX3'
              )
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'SOFTWARES'      => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          'bios'           => array(
              'date'             => '2006-05-30',
              'version'          => 'A05',
              'manufacturers_id' => 'Dell Inc.',
              'serial'           => '',
              'designation'      => 'Dell Inc. BIOS'
          )
          );
      $a_reference['Computer'] = array(
          'name'                             => 'vbox-winxp',
          'uuid'                             => '',
          'domains_id'                       => 'WORKGROUP',
          'manufacturers_id'                 => 'Dell Inc.',
          'computermodels_id'                => 'Dell DXP051',
          'serial'                           => '6PkkD1K',
          'mserial'                          => '..CN7082166DF04E.',
          'computertypes_id'                 => '0FJ030',
          'is_dynamic'                       => 1,
          'mmanufacturer'                    => 'Dell Inc.',
          'bmanufacturer'                    => 'Dell Inc.',
          'mmodel'                           => '0FJ030',
          'otherserial'                      => 'LAPTOP0034'
          );
      $this->assertEquals($a_reference, $a_return);
   }
}
