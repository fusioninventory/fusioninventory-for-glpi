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

      $Computer = new Computer;
      $sectionsId = array();
      $Computer->getFromDB($idmachine);
      foreach($data as $section) {
         $a_fields = array();
         $a_fields_temp = explode('<br />', $section['dataSection']);
         foreach ($a_fields_temp as $num=>$fields) {
            if (strstr($fields, " = ")) {
               $a_fields_temp2 = explode(" = ", $fields);
               $a_fields[$a_fields_temp2[0]] = $a_fields_temp2[1];
            }
         }

         switch ($section['sectionName']) {

            case 'BIOS':
               if (isset($a_fields['SMANUFACTURER'])) {
                  $Manufacturer = new Manufacturer;
                  $Computer->fields['manufacturers_id'] = $Manufacturer->import($Manufacturer->processName($a_fields['SMANUFACTURER']));
               }
               if (isset($a_fields['SMODEL'])) {
                  $ComputerModel = new ComputerModel;
                  $Computer->fields['computermodels_id'] = $ComputerModel->import(array('name'=>$a_fields['SMODEL']));
               }
               if (isset($a_fields['SSN']))
                  $Computer->fields['serial'] = $a_fields['SSN'];
               
               break;

            case 'HARDWARE':
               if (isset($a_fields['NAME']))
                  $Computer->fields['name'] = $a_fields['NAME'];
               if (isset($a_fields['OSNAME'])) {
                  $OperatingSystem = new OperatingSystem;
                  $Computer->fields['operatingsystems_id'] = $OperatingSystem->import(array('name'=>$a_fields['OSNAME']));
               }
               if (isset($a_fields['OSVERSION'])) {
                  $OperatingSystemVersion = new OperatingSystemVersion;
                  $Computer->fields['operatingsystemversions_id'] = $OperatingSystemVersion->import(array('name'=>$a_fields['OSVERSION']));
               }

               break;

         }
      }

      $Computer->update($Computer->fields);
       
      return $sectionsId;
    }

    /**
    * remove a machine's section in an application
    * @access public
    * @param int $externalId
    * @param string $sectionName
    * @param array $dataSection
    */
    public static function removeSection($idsections, $idmachine)
    {
        echo "section removed";
        $sectionsId = array();
        return $sectionsId;
    }

}

?>