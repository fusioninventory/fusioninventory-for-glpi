<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: management of communication with agents
// ----------------------------------------------------------------------
/**
 * The datas are XML encoded and compressed with Zlib.
 * XML rules :
 * - XML tags in uppercase
 **/

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class 
 **/
class PluginFusinvinventoryLibhook {
    /**
    * Disable instance
    * @access private
    *
    */
    private function __construct()
    {
    }

    /**
    * create a new machine in an application
    * @access public
    * @return int $externalId Id to match application data with the library
    */
    public static function createMachine() {

       $Computer = new Computer;
       $Computer->getEmpty();
       return $Computer->addToDB();
    }

    /**
    * add a new section to the machine in an application
    * @access public
    * @param int $externalId
    * @param string $sectionName
    * @param array $dataSection
    * @return int $sectionId
    */
    public static function addSections($data, $idmachine) {
       echo "section added";

      $Computer = new Computer;
      $sectionsId = array();
      $Computer->getFromDB($idmachine);

      $i = -1;
      foreach($data as $section) {
         $i++;
         switch ($section['sectionName']) {

            case 'BIOS':
               if (isset($section['dataSection']['SMANUFACTURER'])) {
                  $Manufacturer = new Manufacturer;
                  $Computer->fields['manufacturers_id'] = $Manufacturer->import($Manufacturer->processName($section['dataSection']['SMANUFACTURER']));
               }
               if (isset($section['dataSection']['SMODEL'])) {
                  $ComputerModel = new ComputerModel;
                  $Computer->fields['computermodels_id'] = $ComputerModel->import(array('name'=>$section['dataSection']['SMODEL']));
               }
               if (isset($section['dataSection']['SSN']))
                  $Computer->fields['serial'] = $section['dataSection']['SSN'];

               break;

            case 'HARDWARE':
               if (isset($section['dataSection']['NAME']))
                  $Computer->fields['name'] = $section['dataSection']['NAME'];
               if (isset($section['dataSection']['OSNAME'])) {
                  $OperatingSystem = new OperatingSystem;
                  $Computer->fields['operatingsystems_id'] = $OperatingSystem->import(array('name'=>$section['dataSection']['OSNAME']));
               }
               if (isset($section['dataSection']['OSVERSION'])) {
                  $OperatingSystemVersion = new OperatingSystemVersion;
                  $Computer->fields['operatingsystemversions_id'] = $OperatingSystemVersion->import(array('name'=>$section['dataSection']['OSVERSION']));
               }
               break;

            case 'ENVS':
               // Not using it now
               unset($data[$i]);

               break;

            case 'PROCESSES':
               // Not using it now
               unset($data[$i]);

               break;

         }
      }
print_r($data);
      $Computer->update($Computer->fields);

      foreach($data as $section) {

         switch ($section['sectionName']) {

            case 'CPUS':
               $DeviceProcessor = new DeviceProcessor();
               $Computer_Device = new Computer_Device('DeviceProcessor');

               $input = array();
               $input['designation'] = $section['dataSection']['NAME'];
               $input['frequence'] = $section['dataSection']['SPEED'];
               $Manufacturer = new Manufacturer;
               $input['manufacturers_id'] = $Manufacturer->import($Manufacturer->processName($section['dataSection']['MANUFACTURER']));

               $proc_id = $DeviceProcessor->import($input);
               $input = array();
               $input['computers_id'] = $idmachine;
               $input['deviceprocessors_id'] = $proc_id;
               $input['specificity'] = $section['dataSection']['SPEED'];
               $input['_itemtype'] = 'DeviceProcessor';
               $id_link_device = $Computer_Device->add($input);

               array_push($sectionsId,$id_link_device);
               break;

            case 'DRIVES':
               $ComputerDisk = new ComputerDisk;
               $id_disk = 0;
               $disk=array();
               $disk['computers_id']=$idmachine;
               if (in_array($section['dataSection']['TYPE'],array("vxfs","ufs")) ) {
                  $disk['name']=$section['dataSection']['VOLUMN'];
                  $disk['mountpoint']=$section['dataSection']['VOLUMN'];
                  $disk['device']=$section['dataSection']['FILESYSTEM'];
                  $disk['filesystems_id']=Dropdown::importExternal('Filesystem', $section['dataSection']["TYPE"]);
               } else if (in_array($section['dataSection']['FILESYSTEM'],array('ext4','ext3','ext2','ffs','jfs','jfs2',
                                                             'xfs','smbfs','nfs','hfs','ufs',
                                                             'Journaled HFS+','fusefs','fuseblk')) ) {
                  $disk['mountpoint']=$section['dataSection']['VOLUMN'];
                  $disk['device']=$section['dataSection']['TYPE'];
                  // Found /dev in VOLUMN : invert datas
                  if (strstr($section['dataSection']['VOLUMN'],'/dev/')) {
                     $disk['mountpoint']=$section['dataSection']['TYPE'];
                     $disk['device']=$section['dataSection']['VOLUMN'];
                  }

                  $disk['name']=$disk['mountpoint'];
                  $disk['filesystems_id']=Dropdown::importExternal('Filesystem', $section['dataSection']["FILESYSTEM"]);
               } else if (in_array($section['dataSection']['FILESYSTEM'],array('FAT32',
                                                             'NTFS',
                                                             'FAT')) ){
                  if (!empty($section['dataSection']['VOLUMN'])) {
                     $disk['name']=$section['dataSection']['VOLUMN'];
                  } else {
                     $disk['name']=$section['dataSection']['LETTER'];
                  }
                  $disk['mountpoint']=$section['dataSection']['LETTER'];
                  $disk['filesystems_id']=Dropdown::importExternal('Filesystem', $section['dataSection']["FILESYSTEM"]);
               }
               if (isset($disk['name']) && !empty($disk["name"])) {
                  $disk['totalsize']=$section['dataSection']['TOTAL'];
                  $disk['freesize']=$section['dataSection']['FREE'];
                  $id_disk = $ComputerDisk->add($disk);
               }
               array_push($sectionsId,$id_disk);
               break;

            case 'MEMORIES':
               $CompDevice = new Computer_Device('DeviceMemory');
               if (!empty ($section['dataSection']["CAPACITY"])) {
                  $ram["designation"]="";
                  if ($section['dataSection']["TYPE"]!="Empty Slot" && $section['dataSection']["TYPE"] != "Unknown") {
                     $ram["designation"]=$section['dataSection']["TYPE"];
                  }
                  if ($section['dataSection']["DESCRIPTION"]) {
                     if (!empty($ram["designation"])) {
                        $ram["designation"].=" - ";
                     }
                     $ram["designation"] .= $section['dataSection']["DESCRIPTION"];
                  }
                  if (!is_numeric($section['dataSection']["CAPACITY"])) {
                     $section['dataSection']["CAPACITY"]=0;
                  }

                  $ram["specif_default"] = $section['dataSection']["CAPACITY"];
                  
                  $ram["frequence"] = $section['dataSection']["SPEED"];
                  $ram["devicememorytypes_id"]
                        = Dropdown::importExternal('DeviceMemoryType', $section['dataSection']["TYPE"]);

                  $DeviceMemory = new DeviceMemory();
                  $ram_id = $DeviceMemory->import($ram);
                  if ($ram_id) {
                     $devID = $CompDevice->add(array('computers_id' => $idmachine,
                                                     '_itemtype'     => 'DeviceMemory',
                                                     'devicememories_id'     => $ram_id,
                                                     'specificity'  => $section['dataSection']["CAPACITY"]));
                  }
               }

               break;

            case 'SOFTWARES':

               // Add software name
               // Add version of software
               // link version with computer : glpi_computers_softwareversions
               $PluginFusinvinventorySoftwares = new PluginFusinvinventorySoftwares;
               $Computer_SoftwareVersion_id = $PluginFusinvinventorySoftwares->addSoftware($idmachine, array('name'=>$section['dataSection']['NAME'],
                                                                              'version'=>$section['dataSection']['VERSION']));
               array_push($sectionsId,$Computer_SoftwareVersion_id);
               break;

            case 'BIOS':
               array_push($sectionsId,$idmachine);
               break;


            case 'HARDWARE':
               array_push($sectionsId,$idmachine);
               break;

            default:
               array_push($sectionsId,0);
               break;



         }
      }

       
      return $sectionsId;
    }

    /**
    * remove a machine's section in an application
    * @access public
    * @param int $externalId
    * @param string $sectionName
    * @param array $dataSection
    */
    public static function removeSections($idsections, $idmachine)
    {
        echo "section removed";
        print_r($idsections);
        $sectionsId = array();
        return $sectionsId;
    }

}

?>