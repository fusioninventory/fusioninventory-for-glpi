<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to convert inventory from agent to inventory ready to
 * inject in GLPI.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Used to convert inventory from agent to inventory ready to inject in GLPI.
 */
class PluginFusioninventoryFormatconvert {

   /**
    * Initialize the foreignkey itemtypes
    *
    * @var array
    */
   var $foreignkey_itemtype = array();

   /**
    * Initialize the manufacturer cache
    *
    * @var array
    */
   var $manufacturer_cache = array();


   /**
    * Convert XML into php array
    *
    * @global string $PLUGIN_FUSIONINVENTORY_XML
    * @param string $xml
    * @return array
    */
   static function XMLtoArray($xml) {
      global $PLUGIN_FUSIONINVENTORY_XML;

      $PLUGIN_FUSIONINVENTORY_XML = $xml;
      $datainventory = json_decode(json_encode((array)$xml), TRUE);
      if (isset($datainventory['CONTENT']['ENVS'])) {
         unset($datainventory['CONTENT']['ENVS']);
      }
      if (isset($datainventory['CONTENT']['PROCESSES'])) {
         unset($datainventory['CONTENT']['PROCESSES']);
      }
      if (isset($datainventory['CONTENT']['PORTS'])) {
         unset($datainventory['CONTENT']['PORTS']);
      }
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      // Hack for some sections
         $a_fields = array('SOUNDS', 'VIDEOS', 'CONTROLLERS', 'CPUS', 'DRIVES',
                           'MEMORIES', 'NETWORKS', 'SOFTWARE', 'USERS',
                           'VIRTUALMACHINES', 'ANTIVIRUS', 'MONITORS',
                           'PRINTERS', 'USBDEVICES', 'PHYSICAL_VOLUMES',
                           'VOLUME_GROUPS', 'LOGICAL_VOLUMES', 'BATTERIES',
                           'LICENSEINFOS', 'STORAGES', 'INPUTS', 'REMOTE_MGMT');
         foreach ($a_fields as $field) {
            if (isset($datainventory['CONTENT'][$field])
                    AND !is_array($datainventory['CONTENT'][$field])) {
               $datainventory['CONTENT'][$field] = array($datainventory['CONTENT'][$field]);
            } else if (isset($datainventory['CONTENT'][$field])
                    AND !is_int(key($datainventory['CONTENT'][$field]))) {
               $datainventory['CONTENT'][$field] = array($datainventory['CONTENT'][$field]);
            }
         }
      if (isset($datainventory['CONTENT'])
              && isset($datainventory['CONTENT']['BIOS'])
              && !is_array($datainventory['CONTENT']['BIOS'])) {
         unset($datainventory['CONTENT']['BIOS']);
      }
      if (isset($datainventory['CONTENT']['VIRTUALMACHINES'])) {
         foreach ($datainventory['CONTENT']['VIRTUALMACHINES'] as $key=>$data) {
            if (isset($data['NETWORKS'])
                    && !is_int(key($data['NETWORKS']))) {
               $datainventory['CONTENT']['VIRTUALMACHINES'][$key]['NETWORKS'] =
                  array($datainventory['CONTENT']['VIRTUALMACHINES'][$key]['NETWORKS']);

            }
         }
      }

      // Hack for Network discovery and inventory
      if (isset($datainventory['CONTENT']['DEVICE'])
              AND !is_array($datainventory['CONTENT']['DEVICE'])) {
         $datainventory['CONTENT']['DEVICE'] = array($datainventory['CONTENT']['DEVICE']);
      } else if (isset($datainventory['CONTENT']['DEVICE'])
              AND !is_int(key($datainventory['CONTENT']['DEVICE']))) {
         $datainventory['CONTENT']['DEVICE'] = array($datainventory['CONTENT']['DEVICE']);
      }
      if (isset($datainventory['CONTENT']['DEVICE'])) {
         foreach ($datainventory['CONTENT']['DEVICE'] as $num=>$data) {
            if (isset($data['INFO']['IPS']['IP'])
                    AND !is_array($data['INFO']['IPS']['IP'])) {
               $datainventory['CONTENT']['DEVICE'][$num]['INFO']['IPS']['IP'] =
                     array($datainventory['CONTENT']['DEVICE'][$num]['INFO']['IPS']['IP']);
            } else if (isset($data['INFO']['IPS']['IP'])
                    AND !is_int(key($data['INFO']['IPS']['IP']))) {
               $datainventory['CONTENT']['DEVICE'][$num]['INFO']['IPS']['IP'] =
                     array($datainventory['CONTENT']['DEVICE'][$num]['INFO']['IPS']['IP']);
            }

            if (isset($data['PORTS']['PORT'])
                    AND !is_array($data['PORTS']['PORT'])) {
               $datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT'] =
                     array($datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT']);
            } else if (isset($data['PORTS']['PORT'])
                    AND !is_int(key($data['PORTS']['PORT']))) {
               $datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT'] =
                     array($datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT']);
            }
            if (isset($datainventory['CONTENT']['DEVICE'][$num]['PORTS'])) {
               foreach ($datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT']
                                    as $numport=>$a_port) {
                  if (isset($a_port['CONNECTIONS'])
                          && isset($a_port['CONNECTIONS']['CONNECTION'])
                          && isset($a_port['CONNECTIONS']['CONNECTION']['MAC'])
                          && !is_array($a_port['CONNECTIONS']['CONNECTION']['MAC'])) {
                     $datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT'][$numport]['CONNECTIONS']['CONNECTION']['MAC'] =
                           array($a_port['CONNECTIONS']['CONNECTION']['MAC']);
                  }
                  if (isset($a_port['VLANS'])
                          && isset($a_port['VLANS']['VLAN'])
                          && !is_int(key($a_port['VLANS']['VLAN']))) {
                     $datainventory['CONTENT']['DEVICE'][$num]['PORTS']['PORT'][$numport]['VLANS']['VLAN'] =
                           array($a_port['VLANS']['VLAN']);
                  }
               }
            }
         }
      }

      //Fix bad WINOWNER; see https://github.com/fusioninventory/fusioninventory-for-glpi/issues/2095
      if (isset($datainventory['CONTENT']['HARDWARE']['WINOWNER'])) {
         if (is_array($datainventory['CONTENT']['HARDWARE']['WINOWNER'])) {
            $fixed = trim(implode(' ', $datainventory['CONTENT']['HARDWARE']['WINOWNER']));
            $datainventory['CONTENT']['HARDWARE']['WINOWNER'] = $fixed;
         }
      }

      return $datainventory;
   }



   /**
    * Convert json into php array
    *
    * @param string $json
    * @return array
    */
   static function JSONtoArray($json) {
      $datainventory = json_decode($json, TRUE);
      $datainventory = PluginFusioninventoryFormatconvert::cleanArray($datainventory);
      return $datainventory;
   }



   /**
    * Clean the php array(remove unwanted characters, potential attack code...)
    *
    * @param array $data
    * @return array cleaned php array
    */
   static function cleanArray($data) {
      foreach ($data as $key=>$value) {
         //if (is_array($value)) {
           if ((array)$value === $value) {
            if (count($value) == 0) {
               $value = '';
            } else {
               $value = PluginFusioninventoryFormatconvert::cleanArray($value);
            }
         } else {
            if (strpos($value, "\'")) {
               $value = str_replace("\'", "'", $value);
            }

            if (preg_match("/[^a-zA-Z0-9 \-_\(\)]+/", $value)) {
               $value = Toolbox::addslashes_deep($value);
            }
            $value = Toolbox::clean_cross_side_scripting_deep($value);
         }
         $data[$key] = $value;
      }
      return array_change_key_case($data, CASE_UPPER);
   }



   /**
    * Convert Fusioninventory Computer inventory to pre-prepared GLPI inventory
    *
    * @global object $DB
    * @global boolean $PF_ESXINVENTORY
    * @global array $CFG_GLPI
    * @param array $array
    * @return array
    */
   static function computerInventoryTransformation($array) {
      global $DB, $PF_ESXINVENTORY, $CFG_GLPI;

      // Initialize
      $a_inventory = array(
         'Computer'                => array(),
         'fusioninventorycomputer' => array(),
         'processor'               => array(),
         'memory'                  => array(),
         'harddrive'               => array(),
         'drive'                   => array(),
         'graphiccard'             => array(),
         'networkcard'             => array(),
         'soundcard'               => array(),
         'controller'              => array(),
         'SOFTWARES'               => array(),
         'virtualmachine'          => array(),
         'computerdisk'            => array(),
         'networkport'             => array(),
         'antivirus'               => array(),
         'licenseinfo'             => array(),
         'batteries'               => array(),
         'monitor'                 => array(),
         'printer'                 => array(),
         'peripheral'              => array(),
         'storage'                 => array(),
         'remote_mgmt'             => array()
      );
      $thisc = new self();
      $pfConfig = new PluginFusioninventoryConfig();

      $ignorecontrollers = array();

      if (isset($array['ACCOUNTINFO'])) {
         $a_inventory['ACCOUNTINFO'] = $array['ACCOUNTINFO'];
      }

      // * HARDWARE
      $array_tmp = $thisc->addValues($array['HARDWARE'],
                                     array(
                                        'NAME'           => 'name',
                                        'WINPRODID'      => 'licenseid',
                                        'WINPRODKEY'     => 'license_number',
                                        'WORKGROUP'      => 'domains_id',
                                        'UUID'           => 'uuid',
                                        'LASTLOGGEDUSER' => 'users_id',
                                        'manufacturers_id' => 'manufacturers_id',
                                        'computermodels_id' => 'computermodels_id',
                                        'serial' => 'serial',
                                        'computertypes_id' => 'computertypes_id'));
      if (!isset($array['OPERATINGSYSTEM']) || empty($array['OPERATINGSYSTEM'])) {
         $array['OPERATINGSYSTEM'] = array();
         if (isset($array['HARDWARE']['OSNAME'])) {
            $array['OPERATINGSYSTEM']['FULL_NAME'] = $array['HARDWARE']['OSNAME'];
         }
         if (isset($array['HARDWARE']['OSVERSION'])) {
            $array['OPERATINGSYSTEM']['VERSION'] = $array['HARDWARE']['OSVERSION'];
         }
         if (isset($array['HARDWARE']['OSCOMMENTS'])
                 && $array['HARDWARE']['OSCOMMENTS'] != ''
                 && !strstr($array['HARDWARE']['OSCOMMENTS'], 'UTC')) {
            $array['OPERATINGSYSTEM']['SERVICE_PACK'] = $array['HARDWARE']['OSCOMMENTS'];
         }
      }
      if (isset($array_tmp['users_id'])) {
         if ($array_tmp['users_id'] == '') {
            unset($array_tmp['users_id']);
         } else {
            $array_tmp['contact'] = $array_tmp['users_id'];
            $tmp_users_id = $array_tmp['users_id'];
            $split_user = explode("@", $tmp_users_id);
            $query = "SELECT `id`
                      FROM `glpi_users`
                      WHERE `name` = '" . $split_user[0] . "'
                      LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) == 1) {
               $array_tmp['users_id'] = $DB->result($result, 0, 0);
            } else {
               $array_tmp['users_id'] = 0;
            }
         }
      }
      $array_tmp['is_dynamic'] = 1;

      $a_inventory['Computer'] = $array_tmp;

      $array_tmp = $thisc->addValues($array['HARDWARE'],
                                     array(
                                        'OSINSTALLDATE'  => 'operatingsystem_installationdate',
                                        'WINOWNER'       => 'winowner',
                                        'WINCOMPANY'     => 'wincompany'));
      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');

      // * Determine "Public contact address"
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) { // Try "X-Forwarded-For" HTTP header
         // Parse "X-Forwarded-For" header (can contain multiple IP addresses, client should be first)
         $forwarded_for_ip_tmp = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
         $forwarded_for_ip_tmp = new IPAddress($forwarded_for_ip_tmp[0]);
         if ($forwarded_for_ip_tmp->is_valid()) {
            $array_tmp['remote_addr'] = $forwarded_for_ip_tmp->getTextual();
         }
      } elseif (isset($_SERVER['HTTP_X_REAL_IP'])) { // Then try "X-Real-IP" HTTP header
         $real_ip_tmp = new IPAddress($_SERVER['HTTP_X_REAL_IP']);
         if ($real_ip_tmp->is_valid()) {
            $array_tmp['remote_addr'] = $real_ip_tmp->getTextual();
         }
      } elseif (isset($_SERVER['REMOTE_ADDR'])) { // Fall back on the currently connected IP
         $array_tmp['remote_addr'] = $_SERVER['REMOTE_ADDR'];
      }

      $a_inventory['fusioninventorycomputer'] = $array_tmp;
      if (isset($array['OPERATINGSYSTEM']['INSTALL_DATE'])
              && !empty($array['OPERATINGSYSTEM']['INSTALL_DATE'])) {
         $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'] =
                     $array['OPERATINGSYSTEM']['INSTALL_DATE'];
      }

      if (isset($array['HARDWARE']['DESCRIPTION'])) {
         $a_inventory['fusioninventorycomputer']['oscomment'] = $array['HARDWARE']['DESCRIPTION'];
      }

      if (empty($a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'])) {
         $a_inventory['fusioninventorycomputer']['operatingsystem_installationdate'] = "NULL";
      }

      // * BIOS
      if (isset($array['BIOS'])) {
         if (isset($array['BIOS']['ASSETTAG'])
                 && !empty($array['BIOS']['ASSETTAG'])) {
            $a_inventory['Computer']['otherserial'] = $array['BIOS']['ASSETTAG'];
         }
         if ((isset($array['BIOS']['SMANUFACTURER']))
               AND (!empty($array['BIOS']['SMANUFACTURER']))) {
            $a_inventory['Computer']['manufacturers_id'] = $array['BIOS']['SMANUFACTURER'];
         } else if ((isset($array['BIOS']['MMANUFACTURER']))
                      AND (!empty($array['BIOS']['MMANUFACTURER']))) {
            $a_inventory['Computer']['manufacturers_id'] = $array['BIOS']['MMANUFACTURER'];
         } else if ((isset($array['BIOS']['BMANUFACTURER']))
                      AND (!empty($array['BIOS']['BMANUFACTURER']))) {
            $a_inventory['Computer']['manufacturers_id'] = $array['BIOS']['BMANUFACTURER'];
         } else {
            if ((isset($array['BIOS']['MMANUFACTURER']))
                         AND (!empty($array['BIOS']['MMANUFACTURER']))) {
               $a_inventory['Computer']['manufacturers_id'] = $array['BIOS']['MMANUFACTURER'];
            } else {
               if ((isset($array['BIOS']['BMANUFACTURER']))
                            AND (!empty($array['BIOS']['BMANUFACTURER']))) {
                  $a_inventory['Computer']['manufacturers_id'] = $array['BIOS']['BMANUFACTURER'];
               }
            }
         }
         if ((isset($array['BIOS']['MMANUFACTURER']))
                      AND (!empty($array['BIOS']['MMANUFACTURER']))) {
            $a_inventory['Computer']['mmanufacturer'] = $array['BIOS']['MMANUFACTURER'];
         }
         if ((isset($array['BIOS']['BMANUFACTURER']))
                      AND (!empty($array['BIOS']['BMANUFACTURER']))) {
            $a_inventory['Computer']['bmanufacturer'] = $array['BIOS']['BMANUFACTURER'];
         }

         if (isset($array['BIOS']['SMODEL']) AND $array['BIOS']['SMODEL'] != '') {
            $a_inventory['Computer']['computermodels_id'] = $array['BIOS']['SMODEL'];
         } else if (isset($array['BIOS']['MMODEL']) AND $array['BIOS']['MMODEL'] != '') {
            $a_inventory['Computer']['computermodels_id'] = $array['BIOS']['MMODEL'];
         }
         if (isset($array['BIOS']['MMODEL']) AND $array['BIOS']['MMODEL'] != '') {
            $a_inventory['Computer']['mmodel'] = $array['BIOS']['MMODEL'];
         }

         if (isset($array['BIOS']['SSN'])) {
            $a_inventory['Computer']['serial'] = trim($array['BIOS']['SSN']);
            // HP patch for serial begin with 'S'
            if ((isset($a_inventory['Computer']['manufacturers_id']))
                  AND (strstr($a_inventory['Computer']['manufacturers_id'], "ewlett"))
                    && preg_match("/^[sS]/", $a_inventory['Computer']['serial'])) {
               $a_inventory['Computer']['serial'] = trim(
                                                preg_replace("/^[sS]/",
                                                             "",
                                                             $a_inventory['Computer']['serial']));
            }
         }
         if (isset($array['BIOS']['MSN'])) {
            $a_inventory['Computer']['mserial'] = trim($array['BIOS']['MSN']);
         }
      }

      // * Type of computer

      //First the HARDWARE/VMSYSTEM is not Physical : then it's a virtual machine
      if (isset($array['HARDWARE']['VMSYSTEM'])
            && $array['HARDWARE']['VMSYSTEM'] != ''
               && $array['HARDWARE']['VMSYSTEM'] != 'Physical') {
         $a_inventory['Computer']['computertypes_id'] = $array['HARDWARE']['VMSYSTEM'];
         // HACK FOR BSDJail, remove serial and UUID (because it's of host, not contener)
         if ($array['HARDWARE']['VMSYSTEM'] == 'BSDJail') {
            if (isset($a_inventory['Computer']['serial'])) {
               $a_inventory['Computer']['serial'] = '';
            }

            $a_inventory['Computer']['uuid'] .= "-".$a_inventory['Computer']['name'];
         }
      } else {
         //It's not a virtual machine, then check :
         //1 - HARDWARE/CHASSIS_TYPE
         //2 - BIOS/TYPE
         //3 - BIOS/MMODEL
         //4 - HARDWARE/VMSYSTEM (should not go there)
         if (isset($array['HARDWARE']['CHASSIS_TYPE'])
               && !empty($array['HARDWARE']['CHASSIS_TYPE'])) {
            $a_inventory['Computer']['computertypes_id'] = $array['HARDWARE']['CHASSIS_TYPE'];
         } else  if (isset($array['BIOS']['TYPE'])
               && !empty($array['BIOS']['TYPE'])) {
            $a_inventory['Computer']['computertypes_id'] = $array['BIOS']['TYPE'];
         } else if (isset($array['BIOS']['MMODEL'])
               && !empty($array['BIOS']['MMODEL'])) {
            $a_inventory['Computer']['computertypes_id'] = $array['BIOS']['MMODEL'];
         } else if (isset($array['HARDWARE']['VMSYSTEM'])
               && !empty($array['HARDWARE']['VMSYSTEM'])) {
            $a_inventory['Computer']['computertypes_id'] = $array['HARDWARE']['VMSYSTEM'];
         }
      }

      //if (isset($array['BIOS']['SKUNUMBER'])) {
      //   $a_inventory['BIOS']['PARTNUMBER'] = $array['BIOS']['SKUNUMBER'];
      //}

      $CFG_GLPI['plugin_fusioninventory_computermanufacturer'][$a_inventory['Computer']['manufacturers_id']] = $a_inventory['Computer']['manufacturers_id'];

      // * BIOS
      if (isset($array['BIOS'])) {
         $a_bios = $thisc->addValues(
            $array['BIOS'],
            [
               'BDATE'           => 'date',
               'BVERSION'        => 'version',
               'BMANUFACTURER'   => 'manufacturers_id',
               'BIOSSERIAL'      => 'serial'
            ]
         );

         $a_bios['designation'] = sprintf(
            __('%1$s BIOS'),
            isset($array['BIOS']['BMANUFACTURER']) ? $array['BIOS']['BMANUFACTURER'] : ''
         );

         $matches = array();
         preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $a_bios['date'], $matches);
         if (count($matches) == 4) {
            $a_bios['date'] = $matches[3]."-".$matches[1]."-".$matches[2];
         } else {
            unset($a_bios['date']);
         }

         $a_inventory['bios'] = $a_bios;
      }

      // * OPERATINGSYSTEM
      if (isset($array['OPERATINGSYSTEM'])) {
         $array_tmp = $thisc->addValues(
                 $array['OPERATINGSYSTEM'],
                 array(
                    'NAME'           => 'operatingsystems_id',
                    'VERSION'        => 'operatingsystemversions_id',
                    'SERVICE_PACK'   => 'operatingsystemservicepacks_id',
                    'ARCH'           => 'operatingsystemarchitectures_id',
                    'KERNEL_NAME'    => 'operatingsystemkernels_id',
                    'KERNEL_VERSION' => 'operatingsystemkernelversions_id'));

         if (isset($array['OPERATINGSYSTEM']['HOSTID'])) {
            $a_inventory['fusioninventorycomputer']['hostid'] = $array['OPERATINGSYSTEM']['HOSTID'];
         }

         if (isset($a_inventory['Computer']['licenseid'])) {
            $array_tmp['licenseid'] = $a_inventory['Computer']['licenseid'];
            unset($a_inventory['Computer']['licenseid']);
         }

         if (isset($a_inventory['Computer']['license_number'])) {
            $array_tmp['license_number'] = $a_inventory['Computer']['license_number'];
            unset($a_inventory['Computer']['license_number']);
         }

         $array_tmp['operatingsystemeditions_id'] = '';
         if (isset($array['OPERATINGSYSTEM']['FULL_NAME']) && $pfConfig->getValue('manage_osname') == 1) {
            $matches = array();
            preg_match("/.+ Windows (XP |\d\.\d |\d{1,4} |Vista(™)? )(.*)/", $array['OPERATINGSYSTEM']['FULL_NAME'], $matches);
            if (count($matches) == 4) {
               $array_tmp['operatingsystemeditions_id'] = $matches[3];
               if ($array_tmp['operatingsystemversions_id'] == '') {
                  $matches[1] = trim($matches[1]);
                  if ($matches[2] != '') {
                     $matches[1] = trim($matches[1], $matches[2]);
                  }
                  $array_tmp['operatingsystemversions_id'] = $matches[1];
               }
            } else if (count($matches) == 2) {
               $array_tmp['operatingsystemeditions_id'] = $matches[1];
            } else {
               preg_match("/^(.*) GNU\/Linux (\d{1,2}|\d{1,2}\.\d{1,2}) \((.*)\)$/", $array['OPERATINGSYSTEM']['FULL_NAME'], $matches);
               if (count($matches) == 4) {
                  if (empty($array_tmp['operatingsystems_id'])) {
                     $array_tmp['operatingsystems_id'] = $matches[1];
                  }
                  if (empty($array_tmp['operatingsystemkernelversions_id'])) {
                     $array_tmp['operatingsystemkernelversions_id'] = $array_tmp['operatingsystemversions_id'];
                     $array_tmp['operatingsystemversions_id'] = $matches[2]." (".$matches[3].")";
                  } else if (empty($array_tmp['operatingsystemversions_id'])) {
                     $array_tmp['operatingsystemversions_id'] = $matches[2]." (".$matches[3].")";
                  }
                  if (empty($array_tmp['operatingsystemkernels_id'])) {
                     $array_tmp['operatingsystemkernels_id'] = 'linux';
                  }
               } else {
                  preg_match("/Linux (.*) (\d{1,2}|\d{1,2}\.\d{1,2}) \((.*)\)$/", $array['OPERATINGSYSTEM']['FULL_NAME'], $matches);
                  if (count($matches) == 4) {
                     if (empty($array_tmp['operatingsystemversions_id'])) {
                        $array_tmp['operatingsystemversions_id'] = $matches[2];
                     }
                     if (empty($array_tmp['operatingsystemarchitectures_id'])) {
                        $array_tmp['operatingsystemarchitectures_id'] = $matches[3];
                     }
                     if (empty($array_tmp['operatingsystemkernels_id'])) {
                        $array_tmp['operatingsystemkernels_id'] = 'linux';
                     }
                     $array_tmp['operatingsystemeditions_id'] = trim($matches[1]);
                  } else {
                     preg_match("/\w[\s\S]{0,4} (?:Windows[\s\S]{0,4} |)(.*) (\d{4} R2|\d{4})(?:, | |)(.*|)$/", $array['OPERATINGSYSTEM']['FULL_NAME'], $matches);
                     if (count($matches) == 4) {
                        $array_tmp['operatingsystemversions_id'] = $matches[2];
                        $array_tmp['operatingsystemeditions_id'] = trim($matches[1]." ".$matches[3]);
                     } else if ($array['OPERATINGSYSTEM']['FULL_NAME'] == 'Microsoft Windows Embedded Standard') {
                        $array_tmp['operatingsystemeditions_id'] = 'Embedded Standard';
                     } else if (empty($array_tmp['operatingsystems_id'])) {
                        $array_tmp['operatingsystems_id'] = $array['OPERATINGSYSTEM']['FULL_NAME'];
                     }
                  }
               }
            }
         } elseif (isset($array['OPERATINGSYSTEM']['FULL_NAME'])) {
            $array_tmp['operatingsystems_id'] = $array['OPERATINGSYSTEM']['FULL_NAME'];
         }
         if (isset($array_tmp['operatingsystemarchitectures_id'])
                 && $array_tmp['operatingsystemarchitectures_id'] != '') {

            $rulecollection = new RuleDictionnaryOperatingSystemArchitectureCollection();
            $res_rule = $rulecollection->processAllRules(array("name"=>$array_tmp['operatingsystemarchitectures_id']));
            if (isset($res_rule['name'])) {
               $array_tmp['operatingsystemarchitectures_id'] = $res_rule['name'];
            }
            if ($array_tmp['operatingsystemarchitectures_id'] == '0') {
               $array_tmp['operatingsystemarchitectures_id'] = '';
            }
         }
         if ($array_tmp['operatingsystemservicepacks_id'] == '0') {
            $array_tmp['operatingsystemservicepacks_id'] = '';
         }
         $a_inventory['fusioninventorycomputer']['items_operatingsystems_id'] = $array_tmp;
      }

      // otherserial (on tag) if defined in config
      if ($pfConfig->getValue('otherserial') == 1) {
         if (isset($array['ACCOUNTINFO'])) {
            //In very rare case, ACCOUNTINFO section is present twice in the XML file...
            if (isset($array['ACCOUNTINFO'][0])) {
               $tmpacc = $array['ACCOUNTINFO'][0];
               $array['ACCOUNTINFO'] = $tmpacc;
            }
            if (isset($array['ACCOUNTINFO']['KEYNAME'])
                    && $array['ACCOUNTINFO']['KEYNAME'] == 'TAG') {
               if (isset($array['ACCOUNTINFO']['KEYVALUE'])
                       && $array['ACCOUNTINFO']['KEYVALUE'] != '') {
                  $a_inventory['Computer']['otherserial'] = $array['ACCOUNTINFO']['KEYVALUE'];
               }
            }
         }
      }

      // Hack for problems of ESX inventory with same deviceid than real computer inventory
      if (isset($a_inventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id'])
              && strstr($a_inventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id'], 'VMware ESX')) {
         $PF_ESXINVENTORY = TRUE;
      }

      // * BATTERIES
      $a_inventory['batteries'] = array();
      if ($pfConfig->getValue('component_battery') == 1) {
         if (isset($array['BATTERIES'])) {
            foreach ($array['BATTERIES'] as $a_batteries) {
               $a_battery = $thisc->addValues($a_batteries,
                  array(
                     'NAME'         => 'designation',
                     'MANUFACTURER' => 'manufacturers_id',
                     'SERIAL'       => 'serial',
                     'DATE'         => 'manufacturing_date',
                     'CAPACITY'     => 'capacity',
                     'CHEMISTRY'    => 'devicebatterytypes_id',
                     'VOLTAGE'      => 'voltage'
                  )
               );

               // test date_install
               $matches = array();
               preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $a_battery['manufacturing_date'], $matches);
               if (count($matches) == 4) {
                  $a_battery['manufacturing_date'] = $matches[3]."-".$matches[2]."-".$matches[1];
               } else {
                  unset($a_battery['manufacturing_date']);
               }

               $a_inventory['batteries'][] = $a_battery;
            }
         }
      }

      // * SOUNDS
      $a_inventory['soundcard'] = array();
      if ($pfConfig->getValue('component_soundcard') == 1) {
         if (isset($array['SOUNDS'])) {
            foreach ($array['SOUNDS'] as $a_sounds) {
               $a_inventory['soundcard'][] = $thisc->addValues($a_sounds,
                                                           array(
                                                              'NAME'          => 'designation',
                                                              'MANUFACTURER'  => 'manufacturers_id',
                                                              'DESCRIPTION'   => 'comment'));

               $ignorecontrollers[$a_sounds['NAME']] = 1;
            }
         }
      }

      // * VIDEOS
      $a_inventory['graphiccard'] = array();
      if ($pfConfig->getValue('component_graphiccard') == 1) {
         if (isset($array['VIDEOS'])) {
            foreach ($array['VIDEOS'] as $a_videos) {
               if (is_array($a_videos)
                       && isset($a_videos['NAME'])) {
                  $array_tmp = $thisc->addValues($a_videos, array(
                                                              'NAME'   => 'designation',
                                                              'MEMORY' => 'memory'));
                  $array_tmp['designation'] = trim($array_tmp['designation']);
                  $a_inventory['graphiccard'][] = $array_tmp;
                  if (isset($a_videos['NAME'])) {
                     $ignorecontrollers[$a_videos['NAME']] = 1;
                  }
                  if (isset($a_videos['CHIPSET'])) {
                     $ignorecontrollers[$a_videos['CHIPSET']] = 1;
                  }
               }
            }
         }
      }

      // * NETWORK CARD
      $a_inventory['networkcard'] = array();
      if ($pfConfig->getValue('component_networkcard') == 1) {
         if (isset($array['NETWORKS'])) {
            foreach ($array['NETWORKS'] as $a_netcards) {
               if (is_array($a_netcards)
                       && isset($a_netcards['DESCRIPTION'])) {

                  // Search in controller if find NAME = CONTROLLER TYPE
                  $a_found = array();
                  if (isset($array['CONTROLLERS'])) {
                     foreach ($array['CONTROLLERS'] as $a_controllers) {
                        if (count($a_found) == 0) {
                           if (isset($a_controllers['TYPE'])
                              && ($a_netcards['DESCRIPTION'] == $a_controllers['TYPE']
                                   || strtolower($a_netcards['DESCRIPTION']." controller") ==
                                          strtolower($a_controllers['TYPE']))
                                 && !isset($ignorecontrollers[$a_controllers['NAME']])) {
                              $a_found = $a_controllers;
                              if (isset($a_netcards['MACADDR'])) {
                                 $a_found['MACADDR'] = $a_netcards['MACADDR'];
                              }
                           }
                        }
                     }
                  }
                  if (count($a_found) > 0) {
                     $array_tmp = $thisc->addValues($a_found,
                                                    array(
                                                       'NAME'          => 'designation',
                                                       'MANUFACTURER'  => 'manufacturers_id',
                                                       'MACADDR'       => 'mac'));
                     if (isset($a_found['PCIID'])) {
                        $a_PCIData =
                              PluginFusioninventoryInventoryExternalDB::getDataFromPCIID(
                                $a_found['PCIID']
                              );
                        if (isset($a_PCIData['manufacturer'])) {
                           $array_tmp['manufacturers_id'] = $a_PCIData['manufacturer'];
                        }
                        if (isset($a_PCIData['name'])) {
                           $array_tmp['designation'] = $a_PCIData['name'];
                        }
                        $array_tmp['designation'] = Toolbox::addslashes_deep($array_tmp['designation']);
                     }
                     $array_tmp['mac'] = strtolower($array_tmp['mac']);
                     $a_inventory['networkcard'][] = $array_tmp;

                     if (isset($a_found['NAME'])) {
                        $ignorecontrollers[$a_found['NAME']] = 1;
                     }
                  }
               }
            }
         }
      }

      // * NETWORKS
      $a_inventory['networkport'] = array();
      if ($pfConfig->getValue('component_networkcard') == 1) {
         if (isset($array['NETWORKS'])) {
            $a_networknames = array();
            foreach ($array['NETWORKS'] as $a_networks) {
               $virtual_import = 1;
               if ($pfConfig->getValue("component_networkcardvirtual") == 0) {
                  if (isset($a_networks['VIRTUALDEV'])
                          && $a_networks['VIRTUALDEV'] == 1) {

                     $virtual_import = 0;
                  }
               }
               if ($virtual_import == 1) {
                  $array_tmp = $thisc->addValues($a_networks,
                                                 array(
                                                    'DESCRIPTION' => 'name',
                                                    'MACADDR'     => 'mac',
                                                    'TYPE'        => 'instantiation_type',
                                                    'IPADDRESS'   => 'ip',
                                                    'VIRTUALDEV'  => 'virtualdev',
                                                    'IPSUBNET'    => 'subnet',
                                                    'SSID'        => 'ssid',
                                                    'IPGATEWAY'   => 'gateway',
                                                    'IPMASK'      => 'netmask',
                                                    'IPDHCP'      => 'dhcpserver',
                                                    'WWN'         => 'wwn',
                                                    'SPEED'       => 'speed'));

                  if ((isset($array_tmp['name'])
                          && $array_tmp['name'] != '')
                       || (isset($array_tmp['mac'])
                          && $array_tmp['mac'] != '')) {

                     if (!isset($array_tmp['virtualdev'])
                             || $array_tmp['virtualdev'] != 1) {
                        $array_tmp['virtualdev'] = 0;
                     }
                     $array_tmp['logical_number'] = 1;
                     if ($array_tmp['virtualdev'] == 1) {
                        $array_tmp['logical_number'] = 0;
                     }

                     $array_tmp['mac'] = strtolower($array_tmp['mac']);
                     if (isset($a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']])) {
                        if (isset($array_tmp['ip'])
                                && $array_tmp['ip'] != '') {
                           if (!in_array($array_tmp['ip'], $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']]['ipaddress'])) {
                              $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']]['ipaddress'][]
                                      = $array_tmp['ip'];
                           }
                        }
                        if (isset($a_networks['IPADDRESS6'])
                                && $a_networks['IPADDRESS6'] != '') {
                           if (!in_array($a_networks['IPADDRESS6'], $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']]['ipaddress'])) {
                              $a_networknames[$array_tmp['name'].'-'.$array_tmp['mac']]['ipaddress'][]
                                      = $a_networks['IPADDRESS6'];
                           }
                        }
                     } else {
                        if (isset($array_tmp['ip'])
                                && $array_tmp['ip'] != '') {
                           $array_tmp['ipaddress'] = array($array_tmp['ip']);
                           unset($array_tmp['ip']);
                        } else {
                           $array_tmp['ipaddress'] = array();
                        }
                        if (isset($a_networks['IPADDRESS6'])
                              && $a_networks['IPADDRESS6'] != '') {
                           $array_tmp['ipaddress'][] = $a_networks['IPADDRESS6'];
                        }

                        if (isset($array_tmp["instantiation_type"])
                                AND $array_tmp["instantiation_type"] == 'Ethernet') {
                           $array_tmp["instantiation_type"] = 'NetworkPortEthernet';
                        } else if (isset($array_tmp["instantiation_type"])
                                AND ($array_tmp["instantiation_type"] == 'wifi'
                                     OR $array_tmp["instantiation_type"] == 'IEEE')) {
                           $array_tmp["instantiation_type"] = 'NetworkPortWifi';
                        } else if (isset($array_tmp["instantiation_type"])
                                AND ($array_tmp["instantiation_type"] == 'fibrechannel'
                                    OR $array_tmp["instantiation_type"] == 'fiberchannel')
                                OR !empty($array_tmp['wwn']) ) {
                           $array_tmp["instantiation_type"] = 'NetworkPortFiberchannel';
                        } else if ($array_tmp['mac'] != '') {
                           $array_tmp["instantiation_type"] = 'NetworkPortEthernet';
                        } else {
                           $array_tmp["instantiation_type"] = 'NetworkPortLocal';
                        }
                        if (isset($array_tmp['ip'])) {
                           unset($array_tmp['ip']);
                        }
                        if (isset($array_tmp['speed'])
                                && is_numeric($array_tmp['speed'])) {
                           // Old agent version have speed in b/s instead Mb/s
                           if ($array_tmp['speed'] > 100000) {
                              $array_tmp['speed'] = $array_tmp['speed'] / 1000000;
                           }
                        } else {
                           $array_tmp['speed'] = 0;
                        }

                        $uniq = '';
                        if (!empty($array_tmp['mac'])) {
                           $uniq = $array_tmp['mac'];
                        } else if (!empty($array_tmp['wwn'])) {
                           $uniq = $array_tmp['wwn'];
                        }
                        $a_networknames[$array_tmp['name'].'-'.$uniq] = $array_tmp;
                     }
                  }
               }
            }
            $a_inventory['networkport'] = $a_networknames;
         }
      }

      // * CONTROLLERS
      $a_inventory['controller'] = array();
      if ($pfConfig->getValue('component_control') == 1) {
         if (isset($array['CONTROLLERS'])) {
            foreach ($array['CONTROLLERS'] as $a_controllers) {
               if ((isset($a_controllers["NAME"]))
                       AND (!isset($ignorecontrollers[$a_controllers["NAME"]]))) {
                  $array_tmp = $thisc->addValues($a_controllers,
                                                 array(
                                                    'NAME'          => 'designation',
                                                    'MANUFACTURER'  => 'manufacturers_id',
                                                    'type'          => 'interfacetypes_id'));
                  if (isset($a_controllers['PCIID'])) {
                     $a_PCIData =
                           PluginFusioninventoryInventoryExternalDB::getDataFromPCIID(
                             $a_controllers['PCIID']
                           );
                     if (isset($a_PCIData['manufacturer'])) {
                        $array_tmp['manufacturers_id'] = $a_PCIData['manufacturer'];
                     }
                     if (isset($a_PCIData['name'])) {
                        $array_tmp['designation'] = $a_PCIData['name'];
                     }
                     $array_tmp['designation'] = Toolbox::addslashes_deep($array_tmp['designation']);
                  }
                  $a_inventory['controller'][] = $array_tmp;
               }
            }
         }
      }

      // * CPUS
      $a_inventory['processor'] = array();
      if ($pfConfig->getValue('component_processor') == 1) {
         if (isset($array['CPUS'])) {
            foreach ($array['CPUS'] as $a_cpus) {
               if (is_array($a_cpus)
                       && (isset($a_cpus['NAME'])
                        || isset($a_cpus['TYPE']))) {
                  $array_tmp = $thisc->addValues($a_cpus,
                                                 array(
                                                    'SPEED'        => 'frequency',
                                                    'MANUFACTURER' => 'manufacturers_id',
                                                    'SERIAL'       => 'serial',
                                                    'NAME'         => 'designation',
                                                    'CORE'         => 'nbcores',
                                                    'THREAD'       => 'nbthreads'));
                  if ($array_tmp['designation'] == ''
                          && isset($a_cpus['TYPE'])) {
                     $array_tmp['designation'] = $a_cpus['TYPE'];
                  }
                  $array_tmp['frequence'] = $array_tmp['frequency'];
                  $array_tmp['frequency_default'] = $array_tmp['frequency'];
                  $a_inventory['processor'][] = $array_tmp;
               }
            }
         }
      }

      // * DRIVES
      $a_inventory['computerdisk'] = array();
      if (isset($array['DRIVES'])) {
         foreach ($array['DRIVES'] as $a_drives) {
            $isNetworkDriveOrFS = false;
            $isRemovableMedia = false;
            if (isset($a_drives['TYPE'])) {
               switch ($a_drives['TYPE']) {
                  case 'Network Drive':
                     $isNetworkDriveOrFS = true;
                     break;

                  case 'Removable Disk':
                  case 'Compact Disc':
                     $isRemovableMedia = true;
                     break;
               }
            }
            if (isset($a_drives['FILESYSTEM']) && $a_drives['FILESYSTEM'] == 'nfs') {
               $isNetworkDriveOrFS = true;
            }
            if ($pfConfig->getValue("component_drive") == '0'
                OR ($pfConfig->getValue("component_networkdrive") == '0' AND $isNetworkDriveOrFS)
                OR ($pfConfig->getValue("component_removablemedia") == '0' AND $isRemovableMedia)) {

            } else {
               if ($pfConfig->getValue('import_volume') == 1) {
                  $array_tmp = $thisc->addValues($a_drives,
                                                 array(
                                                    'VOLUMN'      => 'device',
                                                    'FILESYSTEM'  => 'filesystems_id',
                                                    'TOTAL'       => 'totalsize',
                                                    'FREE'        => 'freesize'));
                  if ((isset($a_drives['LABEL'])) AND (!empty($a_drives['LABEL']))) {
                     $array_tmp['name'] = $a_drives['LABEL'];
                  } else if (((!isset($a_drives['VOLUMN']))
                          OR (empty($a_drives['VOLUMN'])))
                          AND (isset($a_drives['LETTER']))) {
                     $array_tmp['name'] = $a_drives['LETTER'];
                  } else if (isset($a_drives['TYPE'])) {
                     $array_tmp['name'] = $a_drives['TYPE'];
                  } else if (isset($a_drives['VOLUMN'])) {
                     $array_tmp['name'] = $a_drives['VOLUMN'];
                  }
                  if (isset($a_drives['MOUNTPOINT'])) {
                     $array_tmp['mountpoint'] = $a_drives['MOUNTPOINT'];
                  } else if (isset($a_drives['LETTER'])) {
                     $array_tmp['mountpoint'] = $a_drives['LETTER'];
                  } else if (isset($a_drives['TYPE'])) {
                     $array_tmp['mountpoint'] = $a_drives['TYPE'];
                  }
                  $a_inventory['computerdisk'][] = $array_tmp;
               }
            }
         }
      }

      // * MEMORIES
      $a_inventory['memory'] = array();
      if ($pfConfig->getValue('component_memory') == 1) {
         if (isset($array['MEMORIES'])) {
            foreach ($array['MEMORIES'] as $a_memories) {
               if ((!isset($a_memories["CAPACITY"]))
                    OR ((isset($a_memories["CAPACITY"]))
                            AND (!preg_match("/^[0-9]+$/i", $a_memories["CAPACITY"])))) {
                  // Nothing
               } else {
                  $array_tmp = $thisc->addValues($a_memories,
                                                 array(
                                                    'CAPACITY'     => 'size',
                                                    'SPEED'        => 'frequence',
                                                    'TYPE'         => 'devicememorytypes_id',
                                                    'SERIALNUMBER' => 'serial',
                                                    'NUMSLOTS'     => 'busID'));
                  if ($array_tmp['size'] > 0) {
                     $array_tmp['designation'] = "";
                     if (isset($a_memories["TYPE"])
                             && $a_memories["TYPE"]!="Empty Slot"
                             && $a_memories["TYPE"] != "Unknown") {
                        $array_tmp["designation"] = $a_memories["TYPE"];
                     }
                     if (isset($a_memories["DESCRIPTION"])) {
                        if (!empty($array_tmp["designation"])) {
                           $array_tmp["designation"].=" - ";
                        }
                        $array_tmp["designation"] .= $a_memories["DESCRIPTION"];
                     }
                     //agent sometimes gives " MHz" or "MT/s" along with frequence
                     $array_tmp['frequence'] = str_replace([' MHz', ' MT/s'], '', $array_tmp['frequence']);
                     $a_inventory['memory'][] = $array_tmp;
                  }
               }
            }
         } else if (isset($array['HARDWARE']['MEMORY'])) {
            $array_tmp = $thisc->addValues($array['HARDWARE'],
                                           array(
                                              'MEMORY' => 'size'));
            $array_tmp['designation'] = 'Dummy Memory Module';
            $array_tmp['frequence'] = 0;
            $array_tmp['serial'] = '';
            $array_tmp['devicememorytypes_id'] = '';
            $array_tmp['busID'] = '';
            $a_inventory['memory'][] = $array_tmp;
         }
      }

      // * MONITORS
      $a_inventory['monitor'] = array();
      if (isset($array['MONITORS'])) {
         $a_serialMonitor = array();
         foreach ($array['MONITORS'] as $a_monitors) {
            $array_tmp = $thisc->addValues($a_monitors,
                                           array(
                                              'CAPTION'      => 'name',
                                              'MANUFACTURER' => 'manufacturers_id',
                                              'SERIAL'       => 'serial',
                                              'DESCRIPTION'  => 'comment'));
            $array_tmp['is_dynamic'] = 1;
            if (!isset($array_tmp['name'])) {
               $array_tmp['name'] = '';
            }
            if ($array_tmp['name'] == ''
                    && isset($array_tmp['comment'])) {
               $array_tmp['name'] = $array_tmp['comment'];
            }
            if (isset($array_tmp['comment'])) {
               unset($array_tmp['comment']);
            }
            if (!isset($array_tmp['serial'])) {
               $array_tmp['serial'] = '';
            }
            if (!isset($array_tmp['manufacturers_id'])) {
               $array_tmp['manufacturers_id'] = '';
            }
            if (!isset($a_serialMonitor[$array_tmp['serial']])) {
               $a_inventory['monitor'][] = $array_tmp;
               $a_serialMonitor[$array_tmp['serial']] = 1;
            }
         }
      }

      // * PRINTERS
      $a_inventory['printer'] = array();
      if (isset($array['PRINTERS'])) {
         $rulecollection = new RuleDictionnaryPrinterCollection();
         foreach ($array['PRINTERS'] as $a_printers) {
            $array_tmp = $thisc->addValues($a_printers,
                                           array(
                                              'NAME'         => 'name',
                                              'PORT'         => 'port',
                                              'SERIAL'       => 'serial'));
            $array_tmp['is_dynamic'] = 1;
            if (strstr($array_tmp['port'], "USB")) {
               $array_tmp['have_usb'] = 1;
            } else {
               $array_tmp['have_usb'] = 0;
            }
            unset($array_tmp['port']);
            $res_rule = $rulecollection->processAllRules(array("name"=>$array_tmp['name']));
            if (isset($res_rule['_ignore_ocs_import'])
                    && $res_rule['_ignore_ocs_import'] == "1") {
               // Ignrore import printer
            } else if (isset($res_rule['_ignore_import'])
                    && $res_rule['_ignore_import'] == "1") {
               // Ignrore import printer
            } else {
               if (isset($res_rule['name'])) {
                  $array_tmp['name'] = $res_rule['name'];
               }
               if (isset($res_rule['manufacturer'])) {
                  $array_tmp['manufacturers_id'] = $res_rule['manufacturer'];
               }
               $a_inventory['printer'][] = $array_tmp;
            }
         }
      }

      // * PERIPHERAL
      $a_inventory['peripheral'] = array();
      $a_peripheral_name = array();
      $per = 0;
      if (isset($array['USBDEVICES'])) {
         foreach ($array['USBDEVICES'] as $a_peripherals) {
            $array_tmp = $thisc->addValues($a_peripherals,
                                           array(
                                              'NAME'         => 'name',
                                              'MANUFACTURER' => 'manufacturers_id',
                                              'SERIAL'       => 'serial',
                                              'PRODUCTNAME'  => 'productname'));

            $array_tmp['is_dynamic'] = 1;
            if (isset($a_peripherals['VENDORID'])
                     AND $a_peripherals['VENDORID'] != ''
                     AND isset($a_peripherals['PRODUCTID'])) {

               $dataArray = PluginFusioninventoryInventoryExternalDB::getDataFromUSBID(
                          $a_peripherals['VENDORID'],
                          $a_peripherals['PRODUCTID']
                       );
               $dataArray[0] = preg_replace('/&(?!\w+;)/', '&amp;', $dataArray[0]);
               if (!empty($dataArray[0])
                       AND empty($array_tmp['manufacturers_id'])) {
                  $array_tmp['manufacturers_id'] = $dataArray[0];
               }
               $dataArray[1] = preg_replace('/&(?!\w+;)/', '&amp;', $dataArray[1]);
               if (!empty($dataArray[1])
                       AND empty($a_peripherals['productname'])) {
                  $a_peripherals['productname'] = $dataArray[1];
               }
            }

            if ($array_tmp['productname'] != '') {
               $array_tmp['name'] = $array_tmp['productname'];
            }
            unset($array_tmp['productname']);

            $a_inventory['peripheral'][] = $array_tmp;
            $a_peripheral_name[$array_tmp['name']] = $per;
            $per++;
         }
      }
      if (isset($array['INPUTS'])) {
         $a_pointingtypes = array(
             3 => 'Mouse',
             4 => 'Trackball',
             5 => 'Track Point',
             6 => 'Glide Point',
             7 => 'Touch Pad',
             8 => 'Touch Screen',
             9 => 'Mouse - Optical Sensor'
         );
         foreach ($array['INPUTS'] as $a_peripherals) {
            $array_tmp = $thisc->addValues($a_peripherals,
                                           array(
                                              'NAME'         => 'name',
                                              'MANUFACTURER' => 'manufacturers_id'));
            $array_tmp['serial'] = '';
            $array_tmp['peripheraltypes_id'] = '';
            if (isset($a_peripherals['POINTINGTYPE'])
                    && isset($a_pointingtypes[$a_peripherals['POINTINGTYPE']])) {

               $array_tmp['peripheraltypes_id'] = $a_pointingtypes[$a_peripherals['POINTINGTYPE']];
            }
            if (isset($a_peripherals['LAYOUT'])) {
               $array_tmp['peripheraltypes_id'] = 'keyboard';
            }

            if (isset($a_peripheral_name[$array_tmp['name']])) {
               $a_inventory['peripheral'][$a_peripheral_name[$array_tmp['name']]]['peripheraltypes_id'] = $array_tmp['peripheraltypes_id'];
            } else {
               $a_inventory['peripheral'][] = $array_tmp;
            }
         }
      }

      // * SLOTS

      // * SOFTWARES
      $a_inventory['SOFTWARES'] = array();
      if ($pfConfig->getValue('import_software') == 1) {
         if (isset($array['SOFTWARES'])) {
            $a_inventory['SOFTWARES'] = $array['SOFTWARES'];
         }
      }

      // * STORAGES/COMPUTERDISK
      $a_inventory['harddrive'] = array();
      if (isset($array['STORAGES'])) {
         foreach ($array['STORAGES']  as $a_storage) {
            $type_tmp = PluginFusioninventoryFormatconvert::getTypeDrive($a_storage);
            if ($type_tmp == "Drive") {
               // it's cd-rom / dvd
//               if ($pfConfig->getValue(,
//                    "component_drive") =! 0) {
               if ($pfConfig->getValue('component_drive') == 1) {
                  $array_tmp = $thisc->addValues($a_storage,
                                                 array(
                                                    'SERIALNUMBER' => 'serial',
                                                    'NAME'         => 'designation',
                                                    'TYPE'         => 'interfacetypes_id',
                                                    'MANUFACTURER' => 'manufacturers_id',
                                                     ));
                  if ($array_tmp['designation'] == '') {
                     if (isset($a_storage['DESCRIPTION'])) {
                        $array_tmp['designation'] = $a_storage['DESCRIPTION'];
                     }
                  }
                  $a_inventory['drive'][] = $array_tmp;
                }
            } else {
               // it's harddisk
//               if ($pfConfig->getValue(,
//                    "component_harddrive") != 0) {
               if (is_array($a_storage)) {
                  if ($pfConfig->getValue('component_harddrive') == 1) {
                     $array_tmp = $thisc->addValues($a_storage,
                                                    array(
                                                        'DISKSIZE'      => 'capacity',
                                                        'INTERFACE'     => 'interfacetypes_id',
                                                        'MANUFACTURER'  => 'manufacturers_id',
                                                        'MODEL'         => 'designation',
                                                        'SERIALNUMBER'  => 'serial'));
                     if ($array_tmp['designation'] == '') {
                        if (isset($a_storage['NAME'])) {
                           $array_tmp['designation'] = $a_storage['NAME'];
                        } else if (isset($a_storage['DESIGNATION'])) {
                           $array_tmp['designation'] = $a_storage['DESIGNATION'];
                        }
                     }
                     $a_inventory['harddrive'][] = $array_tmp;
                  }
               }
            }
         }
      }

      // * USERS
      $cnt = 0;
      if (isset($array['USERS'])) {
         if (count($array['USERS']) > 0) {
            $user_temp = '';
            if (isset($a_inventory['Computer']['contact'])) {
               $user_temp = $a_inventory['Computer']['contact'];
            }
            $a_inventory['Computer']['contact'] = '';
         }
         foreach ($array['USERS'] as $a_users) {
            $array_tmp = $thisc->addValues($a_users,
                                           array(
                                              'LOGIN'  => 'login',
                                              'DOMAIN' => 'domain'));
            $user = '';
            if (isset($array_tmp['login'])) {
               $user = $array_tmp['login'];
               if (isset($array_tmp['domain'])
                       && !empty($array_tmp['domain'])) {
                  $user .= "@".$array_tmp['domain'];
               }
            }
            if ($cnt == 0) {
               if (isset($array_tmp['login'])) {
                  $query = "SELECT `id`
                            FROM `glpi_users`
                            WHERE `name` = '" . $array_tmp['login'] . "'
                            LIMIT 1";
                  $result = $DB->query($query);
                  if ($DB->numrows($result) == 1) {
                     $a_inventory['Computer']['users_id'] = $DB->result($result, 0, 0);
                  }
               }
            }

            if ($user != '') {
               if (isset($a_inventory['Computer']['contact'])) {
                  if ($a_inventory['Computer']['contact'] == '') {
                     $a_inventory['Computer']['contact'] = $user;
                  } else {
                     $a_inventory['Computer']['contact'] .= "/".$user;
                  }
               } else {
                  $a_inventory['Computer']['contact'] = $user;
               }
            }
            $cnt++;
         }
         if (empty($a_inventory['Computer']['contact'])) {
            $a_inventory['Computer']['contact'] = $user_temp;
         }
      }

      // * VIRTUALMACHINES
      $a_inventory['virtualmachine'] = array();
      if ($pfConfig->getValue('import_vm') == 1) {
         if (isset($array['VIRTUALMACHINES'])) {
            foreach ($array['VIRTUALMACHINES'] as $a_virtualmachines) {
               $array_tmp = $thisc->addValues($a_virtualmachines,
                                              array(
                                                 'NAME'        => 'name',
                                                 'VCPU'        => 'vcpu',
                                                 'MEMORY'      => 'ram',
                                                 'VMTYPE'      => 'virtualmachinetypes_id',
                                                 'SUBSYSTEM'   => 'virtualmachinesystems_id',
                                                 'STATUS'      => 'virtualmachinestates_id',
                                                 'UUID'        => 'uuid'));
               $array_tmp['is_dynamic'] = 1;
               // Hack for BSD jails
               if ($array_tmp['virtualmachinetypes_id'] == 'jail') {
                  $array_tmp['uuid'] = $a_inventory['Computer']['uuid']."-".$array_tmp['name'];
               }

               $a_inventory['virtualmachine'][] = $array_tmp;
            }
         }
      }
      if ($pfConfig->getValue('create_vm') == 1) {
         if (isset($array['VIRTUALMACHINES'])) {
            foreach ($array['VIRTUALMACHINES'] as $a_virtualmachines) {
               if (strstr($a_virtualmachines['MEMORY'], 'MB')) {
                  $a_virtualmachines['MEMORY'] = str_replace('MB', '', $a_virtualmachines['MEMORY']);
               } else if (strstr($a_virtualmachines['MEMORY'], 'KB')) {
                  $a_virtualmachines['MEMORY'] = str_replace('KB', '', $a_virtualmachines['MEMORY']);
                  $a_virtualmachines['MEMORY'] = $a_virtualmachines['MEMORY'] / 1000;
               } else if (strstr($a_virtualmachines['MEMORY'], 'GB')) {
                  $a_virtualmachines['MEMORY'] = str_replace('GB', '', $a_virtualmachines['MEMORY']);
                  $a_virtualmachines['MEMORY'] = $a_virtualmachines['MEMORY'] * 1000;
               } else if (strstr($a_virtualmachines['MEMORY'], 'B')) {
                  $a_virtualmachines['MEMORY'] = str_replace('B', '', $a_virtualmachines['MEMORY']);
                  $a_virtualmachines['MEMORY'] = $a_virtualmachines['MEMORY'] / 1000000;
               }
               $array_tmp = $thisc->addValues($a_virtualmachines,
                                              array(
                                                 'NAME'            => 'name',
                                                 'VCPU'            => 'vcpu',
                                                 'MEMORY'          => 'ram',
                                                 'VMTYPE'          => 'computertypes_id',
                                                 'UUID'            => 'uuid',
                                                 'OPERATINGSYSTEM' => 'operatingsystems_id',
                                                 'CUSTOMFIELDS'    => 'comment'));
               $array_tmp['is_dynamic'] = 1;
               if (isset($array_tmp['comment'])
                       && is_array($array_tmp['comment'])) {
                  $a_com_temp = $array_tmp['comment'];
                  $array_tmp['comment'] = '';
                  foreach ($a_com_temp as $data) {
                     $array_tmp['comment'] .= $data['NAME'].' : '.$data['VALUE'].'\n';
                  }
               }
               $array_tmp['networkport'] = array();
               if (isset($a_virtualmachines['NETWORKS'])
                       && is_array($a_virtualmachines['NETWORKS'])) {
                  foreach ($a_virtualmachines['NETWORKS'] as $data) {

                     $array_tmp_np = $thisc->addValues($data,
                                  array(
                                     'DESCRIPTION' => 'name',
                                     'MACADDR'     => 'mac',
                                     'IPADDRESS'   => 'ip'));
                     $array_tmp_np['instantiation_type'] = 'NetworkPortEthernet';
                     $array_tmp_np['mac'] = strtolower($array_tmp_np['mac']);
                     if (isset($array_tmp['networkport'][$array_tmp_np['name'].'-'.$array_tmp_np['mac']])) {
                        if (isset($array_tmp_np['ip'])) {
                           $array_tmp['networkport'][$array_tmp_np['name'].'-'.$array_tmp_np['mac']]['ipaddress'][]
                                   = $array_tmp_np['ip'];
                        }
                     } else {
                        if (isset($array_tmp_np['ip'])
                                && $array_tmp_np['ip'] != '') {
                           $array_tmp_np['ipaddress'] = array($array_tmp_np['ip']);
                           unset($array_tmp_np['ip']);
                        } else {
                           $array_tmp_np['ipaddress'] = array();
                        }
                        $array_tmp['networkport'][$array_tmp_np['name'].'-'.$array_tmp_np['mac']] = $array_tmp_np;
                     }
                  }
               }
               $a_inventory['virtualmachine_creation'][] = $array_tmp;
            }
         }
      }

      // * ANTIVIRUS
      $a_inventory['antivirus'] = array();
      if ($pfConfig->getValue("import_antivirus") != 0) {
         if (isset($array['ANTIVIRUS'])) {
            foreach ($array['ANTIVIRUS'] as $a_antiviruses) {
               $array_tmp = $thisc->addValues($a_antiviruses,
                                             array(
                                                'NAME'     => 'name',
                                                'COMPANY'  => 'manufacturers_id',
                                                'VERSION'  => 'antivirus_version',
                                                'ENABLED'  => 'is_active',
                                                'UPTODATE' => 'is_uptodate'));
               $a_inventory['antivirus'][] = $array_tmp;
            }
         }
      }

      // * STORAGE/VOLUMES
      $a_inventory['storage'] = array();

      // * LICENSEINFOS
      $a_inventory['licenseinfo'] = array();
      if (isset($array['LICENSEINFOS'])) {
         foreach ($array['LICENSEINFOS'] as $a_licenseinfo) {
            $array_tmp = $thisc->addValues($a_licenseinfo,
                                           array(
                                              'NAME'     => 'name',
                                              'FULLNAME' => 'fullname',
                                              'KEY'      => 'serial'));
            $a_inventory['licenseinfo'][] = $array_tmp;
         }
      }

      // * REMOTE_MGMT
      $a_inventory['remote_mgmt'] = array();
      if (isset($array['REMOTE_MGMT'])) {
         foreach ($array['REMOTE_MGMT'] as $a_remotemgmt) {
            $array_tmp = $thisc->addValues($a_remotemgmt,
                                           array(
                                              'ID'   => 'number',
                                              'TYPE' => 'type'));
            $a_inventory['remote_mgmt'][] = $array_tmp;
         }
      }

      $plugin_params = array(
         'inventory' => $a_inventory,
         'source'    => $array
      );
      $plugin_values = Plugin::doHookFunction(
         "fusioninventory_addinventoryinfos",
         $plugin_params
      );

      if (is_array($plugin_values) && $plugin_values !== $plugin_params) {
         $a_inventory = array_merge($a_inventory, $plugin_values);
      }

      return $a_inventory;
   }



   /**
    * Convert SOFTWARE part of computer inventory because need a special
    * transformation
    *
    * @param array $a_inventory computer inventory converted
    * @param integer $entities_id entity id
    * @return array
    */
   function computerSoftwareTransformation($a_inventory, $entities_id) {

   /*
    * Sometimes we can have 2 same software, but one without manufacturer and
    * one with. So in this case, delete the software without manufacturer
    */
      $operatingsystems_id = 0;
      if (isset($a_inventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id'])) {
         $operatingsystems_id = $a_inventory['fusioninventorycomputer']['items_operatingsystems_id']['operatingsystems_id'];
      }

      $softwareWithManufacturer = array();
      $softwareWithoutManufacturer = array();

      $entities_id_software = Entity::getUsedConfig('entities_id_software',
                                                    $entities_id);
      $is_software_recursive = 0;
      $nb_RuleDictionnarySoftware = countElementsInTable("glpi_rules",
                                                         "`sub_type`='RuleDictionnarySoftware'
                                                            AND `is_active`='1'");

      //Configuration says that software can be created in the computer's entity
      if ($entities_id_software < 0) {
         $entities_id_software = $entities_id;
      } else {
         //Software will be created in an entity which is not the computer's entity.
         //It should be set as recursive
         $is_software_recursive = 1;
      }
      $a_inventory['software'] = array();

      $rulecollection = new RuleDictionnarySoftwareCollection();

      foreach ($a_inventory['SOFTWARES'] as $a_softwares) {
         if (isset($a_softwares['PUBLISHER'])
                 && gettype($a_softwares['PUBLISHER']) == 'array')  {
            $a_softwares['PUBLISHER'] = current($a_softwares['PUBLISHER']);
         }

         $array_tmp = $this->addValues($a_softwares,
                                        array(
                                           'PUBLISHER'   => 'manufacturers_id',
                                           'NAME'        => 'name',
                                           'VERSION'     => 'version',
                                           'INSTALLDATE' => 'date_install',
                                           'SYSTEM_CATEGORY' => '_system_category'));
         if (!isset($array_tmp['name'])
                 || $array_tmp['name'] == '') {
            if (isset($a_softwares['GUID'])
                    && $a_softwares['GUID'] != '') {
               $array_tmp['name'] = $a_softwares['GUID'];
            }
         }
         $array_tmp['operatingsystems_id'] = $operatingsystems_id;
         // test date_install
         if (isset($array_tmp['date_install'])) {
            $matches = array();
            preg_match("/^(\d{2})\/(\d{2})\/(\d{4})$/", $array_tmp['date_install'], $matches);
            if (count($matches) == 4) {
               $array_tmp['date_install'] = $matches[3]."-".$matches[2]."-".$matches[1];
            } else {
               unset($array_tmp['date_install']);
            }
         }

         if (!(!isset($array_tmp['name'])
                 || $array_tmp['name'] == '')) {
            if (count($array_tmp) > 0) {
               $res_rule = array();
               if ($nb_RuleDictionnarySoftware > 0) {
                  $res_rule = $rulecollection->processAllRules(
                                               [
                                                "name"         => $array_tmp['name'],
                                                "manufacturer" => $array_tmp['manufacturers_id'],
                                                "old_version"  => $array_tmp['version'],
                                                "entities_id"  => $entities_id_software,
                                                "_system_category" => $array_tmp['_system_category']
                                                ]
                                             );
               }

               if (isset($res_rule['_ignore_import'])
                       && $res_rule['_ignore_import'] == 1) {

               } else {
                  if (isset($res_rule["name"])) {
                     $array_tmp['name'] = $res_rule["name"];
                  }
                  if (isset($res_rule["version"])) {
                     $array_tmp['version'] = $res_rule["version"];
                  }
                  if (isset($res_rule["manufacturer"])) {
                     $array_tmp['manufacturers_id'] = Dropdown::import("Manufacturer",
                                                   array('name' => $res_rule["manufacturer"]));
                  } else if (isset($array_tmp['manufacturers_id'])
                          && $array_tmp['manufacturers_id'] != ''
                          && $array_tmp['manufacturers_id'] != '0') {

                     if (!isset($this->manufacturer_cache[$array_tmp['manufacturers_id']])) {
                        $new_value = Dropdown::importExternal('Manufacturer',
                                                              $array_tmp['manufacturers_id']);
                        $this->manufacturer_cache[$array_tmp['manufacturers_id']] = $new_value;
                     }
                     $array_tmp['manufacturers_id'] =
                                 $this->manufacturer_cache[$array_tmp['manufacturers_id']];
                  } else {
                     $array_tmp['manufacturers_id'] = 0;
                  }
                  if (isset($res_rule['new_entities_id'])) {
                     $array_tmp['entities_id'] = $res_rule['new_entities_id'];
                     $is_software_recursive = 1;
                  }
                  if (!isset($array_tmp['entities_id'])
                          || $array_tmp['entities_id'] == '') {
                     $array_tmp['entities_id'] = $entities_id_software;
                  }
                  if (!isset($array_tmp['version'])) {
                     $array_tmp['version'] = "";
                  }
                  $array_tmp['is_template_computer'] = 0;
                  $array_tmp['is_deleted_computer'] = 0;
                  $array_tmp['is_recursive']= $is_software_recursive;
                  $comp_key = strtolower($array_tmp['name']).
                               "$$$$".strtolower($array_tmp['version']).
                               "$$$$".$array_tmp['manufacturers_id'].
                               "$$$$".$array_tmp['entities_id'].
                               "$$$$".$array_tmp['operatingsystems_id'];

                  $comp_key_simple = strtolower($array_tmp['name']).
                               "$$$$".strtolower($array_tmp['version']).
                               "$$$$".$array_tmp['entities_id'].
                               "$$$$".$array_tmp['operatingsystems_id'];

                  if ($array_tmp['manufacturers_id'] == 0) {
                     $softwareWithoutManufacturer[$comp_key_simple] = $array_tmp;
                  } else {
                     if (!isset($a_inventory['software'][$comp_key])) {
                        $softwareWithManufacturer[$comp_key_simple] = 1;
                        $a_inventory['software'][$comp_key] = $array_tmp;
                     }
                  }
               }
            }
         }
      }
      foreach ($softwareWithoutManufacturer as $key=>$array_tmp) {
         if (!isset($softwareWithManufacturer[$key])) {
            $comp_key = strtolower($array_tmp['name']).
                         "$$$$".strtolower($array_tmp['version']).
                         "$$$$".$array_tmp['manufacturers_id'].
                         "$$$$".$array_tmp['entities_id'].
                         "$$$$".$array_tmp['operatingsystems_id'];
            if (!isset($a_inventory['software'][$comp_key])) {
               $a_inventory['software'][$comp_key] = $array_tmp;
            }
         }
      }
      unset($a_inventory['SOFTWARES']);
      return $a_inventory;
   }



   /**
    * Prepare collect info (file, wmi and registry) to be glpi ready :D
    *
    * @param array $a_inventory computer array prepared
    * @param integer $computers_id computer ID
    * @return array
    *
    */
   function extraCollectInfo($a_inventory, $computers_id) {
      global $DB;

      $pfCollectRuleCollection = new PluginFusioninventoryCollectRuleCollection();

      // Get data from rules / collect registry, wmi, find files
      $data_collect = array();

      $data_registries = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_registries_contents',
                                         "`computers_id`='".$computers_id."'");

      foreach ($data_registries as $data) {
         $res_rule = $pfCollectRuleCollection->processAllRules(
                       array(
                           "regkey"   => $data['key'],
                           "regvalue" => $data['value'],
                        )
                     );
         if (!isset($res_rule['_no_rule_matches'])) {
            $data_collect[] = $res_rule;
         }
      }

      $data_wmis = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_wmis_contents',
                                         "`computers_id`='".$computers_id."'");

      foreach ($data_wmis as $data) {
         $res_rule = $pfCollectRuleCollection->processAllRules(
                       array(
                           "wmiproperty"  => $data['property'],
                           "wmivalue"     => $data['value'],
                        )
                     );
         if (!isset($res_rule['_no_rule_matches'])) {
            $data_collect[] = $res_rule;
         }
      }

      $data_files = getAllDatasFromTable('glpi_plugin_fusioninventory_collects_files_contents',
                                         "`computers_id`='".$computers_id."'");

      foreach ($data_files as $data) {
         $a_split = explode("/", $data['pathfile']);
         $filename = array_pop($a_split);
         $path = implode("/", $a_split);

         $res_rule = $pfCollectRuleCollection->processAllRules(
                       array(
                           "filename"  => $filename,
                           "filepath"  => $path,
                           "size"      => $data['size']
                        )
                     );
         if (!isset($res_rule['_no_rule_matches'])) {
            $data_collect[] = $res_rule;
         }
      }

      // * Update $a_inventory with $data_collect;
      foreach ($data_collect as $data) {
         // Update computer model
         if (isset($data['computermodels_id'])) {
            $a_inventory['Computer']['computermodels_id'] = $data['computermodels_id'];
         }
         // Update computer type
         if (isset($data['computertypes_id'])) {
            $a_inventory['Computer']['computertypes_id'] = $data['computertypes_id'];
         }
         // Update computer user
         if (isset($data['user'])) {
            $query = "SELECT `id`
                      FROM `glpi_users`
                      WHERE `name` = '" . $data['user'] . "'
                      LIMIT 1";
            $result = $DB->query($query);
            if ($DB->numrows($result) == 1) {
               $a_inventory['Computer']['users_id'] = $DB->result($result, 0, 0);
            }
         }
         // Update computer location
         if (isset($data['locations_id'])) {
            $a_inventory['Computer']['locations_id'] = $data['locations_id'];
         }
         // Update computer status
         if (isset($data['states_id'])) {
            $a_inventory['Computer']['states_id'] = $data['states_id'];
         }
         // Add software
         if (isset($data['software'])
                 && isset($data['softwareversion'])) {
            $a_inventory['SOFTWARES'][] = array(
                'NAME'     => $data['software'],
                'VERSION'  => $data['softwareversion']
            );
         }
         // Update computer inventory number
         if (isset($data['otherserial'])) {
            $a_inventory['Computer']['otherserial'] = $data['otherserial'];
         }
      }
      return $a_inventory;
   }



   /**
    * Convert data in right format (string, integer) when have empty or not
    * exist in inventory
    *
    * @param array $array inventory data
    * @param array $a_key the key to check
    * @return string|array
    */
   static function addValues($array, $a_key) {
      $a_return = array();
      //if (!is_array($array)) {
      if ((array)$array !== $array) {
         return $a_return;
      }
      foreach ($array as $key=>$value) {
         if (isset($a_key[$key])) {
            $a_return[$a_key[$key]] = $value;
         }
      }

      $a_int_values = array('capacity', 'freesize', 'totalsize', 'memory', 'memory_size',
         'pages_total', 'pages_n_b', 'pages_color', 'pages_recto_verso', 'scanned',
         'pages_total_print', 'pages_n_b_print', 'pages_color_print', 'pages_total_copy',
         'pages_n_b_copy', 'pages_color_copy', 'pages_total_fax',
         'cpu', 'trunk', 'is_active', 'uptodate', 'nbthreads', 'vcpu', 'ram',
         'ifinerrors', 'ifinoctets', 'ifouterrors', 'ifoutoctets', 'ifmtu', 'speed',
         'nbcores', 'nbthreads', 'frequency');

      foreach ($a_key as $key=>$value) {
         if (!isset($a_return[$value])
                 || $a_return[$value] == '') {

            if (in_array($value, $a_int_values)) {
               $a_return[$value] = 0;
            } else {
               $a_return[$value] = '';
            }
         }
      }
      return $a_return;
   }



   /**
    * Replace string in prepared data into GLPI ID (create items if required)
    *
    * @global array $CFG_GLPI
    * @param array $array data prepared
    * @param string $itemtype it's itemtype of item
    * @param integer $items_id id of the item
    * @param integer $level
    * @return array
    */
   function replaceids($array, $itemtype, $items_id, $level=0) {
      global $CFG_GLPI;

      $a_lockable = PluginFusioninventoryLock::getLockFields(getTableForItemType($itemtype),
                                                             $items_id);

      foreach ($array as $key=>$value) {
         if (!is_int($key)
                 && ($key == "software"
                     || $key == 'ipaddress'
                     || $key == 'internalport')) {
            // do nothing
         } else {
            //if (is_array($value)) {
            if ((array)$value === $value) {
               $new_itemtype = $itemtype;
               if ($level == 0) {
                  $new_itemtype = $key;
               }
               $array[$key] = $this->replaceids($value, $new_itemtype, $items_id, $level + 1);
            } else {
               if (!PluginFusioninventoryLock::isFieldLocked($a_lockable, $key)) {
                  if (!is_numeric($key)
                          && ($key == "manufacturers_id"
                              || $key == 'bios_manufacturers_id')) {
                     $manufacturer = new Manufacturer();
                     $array[$key]  = $manufacturer->processName($value);
                     if ($key == 'bios_manufacturers_id') {
                        $this->foreignkey_itemtype[$key] =
                                 getItemTypeForTable(getTableNameForForeignKeyField('manufacturers_id'));
                     } else {
                        if (isset($CFG_GLPI['plugin_fusioninventory_computermanufacturer'][$value])) {
                           $CFG_GLPI['plugin_fusioninventory_computermanufacturer'][$value] = $array[$key];
                        }
                     }
                  }
                  if (!is_numeric($key)) {
                     if ($key == "locations_id") {
                        $array[$key] = Dropdown::importExternal('Location',
                                                                $value,
                                                                $_SESSION["plugin_fusioninventory_entity"]);
                     } else if (isset($this->foreignkey_itemtype[$key])) {
                        $array[$key] = Dropdown::importExternal($this->foreignkey_itemtype[$key],
                                                                $value,
                                                                $_SESSION["plugin_fusioninventory_entity"]);
                     } else if (isForeignKeyField($key)
                             && $key != "users_id") {
                        $this->foreignkey_itemtype[$key] =
                                    getItemTypeForTable(getTableNameForForeignKeyField($key));
                        $array[$key] = Dropdown::importExternal($this->foreignkey_itemtype[$key],
                                                                $value,
                                                                $_SESSION["plugin_fusioninventory_entity"]);

                        if ($key == 'operatingsystemkernelversions_id'
                           && isset($array['operatingsystemkernels_id'])
                           && (int)$array[$key] > 0
                        ) {
                           $kversion = new OperatingSystemKernelVersion();
                           $kversion->getFromDB($array[$key]);
                           if ($kversion->fields['operatingsystemkernels_id'] != $array['operatingsystemkernels_id']) {
                              $kversion->update([
                                 'id'                          => $kversion->getID(),
                                 'operatingsystemkernels_id'   => $array['operatingsystemkernels_id']
                              ]);
                           }
                        }
                     }
                  }
               } else {
                  unset($array[$key]);
               }
            }
         }
      }
      return $array;
   }



   /**
    * Convert network equipment in GLPI prepared data
    *
    * @param array $array
    * @return array
    */
   static function networkequipmentInventoryTransformation($array) {

      $a_inventory = array();
      $thisc = new self();

      // * INFO
      $array_tmp = $thisc->addValues($array['INFO'],
                                     array(
                                        'NAME'         => 'name',
                                        'SERIAL'       => 'serial',
                                        'ID'           => 'id',
                                        'LOCATION'     => 'locations_id',
                                        'MODEL'        => 'networkequipmentmodels_id',
                                        'MANUFACTURER' => 'manufacturers_id',
                                        'FIRMWARE'     => 'networkequipmentfirmwares_id',
                                        'RAM'          => 'ram',
                                        'MEMORY'       => 'memory',
                                        'MAC'          => 'mac'));

      if (strstr($array_tmp['networkequipmentfirmwares_id'], "CW_VERSION")
              OR strstr($array_tmp['networkequipmentfirmwares_id'], "CW_INTERIM_VERSION")) {
         $explode = explode("$", $array_tmp['networkequipmentfirmwares_id']);
         if (isset($explode[1])) {
            $array_tmp['networkequipmentfirmwares_id'] = $explode[1];
         }
      }
      $array_tmp['is_dynamic'] = 1;
      $a_inventory['NetworkEquipment'] = $array_tmp;
      $a_inventory['itemtype'] = 'NetworkEquipment';


      $array_tmp = $thisc->addValues($array['INFO'],
                                     array(
                                        'COMMENTS' => 'sysdescr',
                                        'UPTIME'   => 'uptime',
                                        'CPU'      => 'cpu',
                                        'MEMORY'   => 'memory'));
      if (!isset($array_tmp['cpu'])
              || $array_tmp['cpu'] == '') {
         $array_tmp['cpu'] = 0;
      }

      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');
      $a_inventory['PluginFusioninventoryNetworkEquipment'] = $array_tmp;

      // * Internal ports
      $a_inventory['internalport'] = array();
      if (isset($array['INFO']['IPS'])) {
         foreach ($array['INFO']['IPS']['IP'] as $IP) {
            $a_inventory['internalport'][] = $IP;
         }
      }
      $a_inventory['internalport'] = array_unique($a_inventory['internalport']);

      // * PORTS
      $a_inventory['networkport'] = array();
      if (isset($array['PORTS'])) {
         foreach ($array['PORTS']['PORT'] as $a_port) {
            $array_tmp = $thisc->addValues($a_port,
                                           array(
                                              'IFNAME'            => 'name',
                                              'IFNUMBER'          => 'logical_number',
                                              'MAC'               => 'mac',
                                              'IFSPEED'           => 'speed',
                                              'IFDESCR'           => 'ifdescr',
                                              'IFALIAS'           => 'ifalias',
                                              'IFINERRORS'        => 'ifinerrors',
                                              'IFINOCTETS'        => 'ifinoctets',
                                              'IFINTERNALSTATUS'  => 'ifinternalstatus',
                                              'IFLASTCHANGE'      => 'iflastchange',
                                              'IFMTU'             => 'ifmtu',
                                              'IFOUTERRORS'       => 'ifouterrors',
                                              'IFOUTOCTETS'       => 'ifoutoctets',
                                              'IFSTATUS'          => 'ifstatus',
                                              'IFTYPE'            => 'iftype',
                                              'TRUNK'             => 'trunk',
                                              'IFPORTDUPLEX'      => 'portduplex'));
            $array_tmp['ifspeed'] = $array_tmp['speed'];
            if ($array_tmp['ifdescr'] == '') {
               $array_tmp['ifdescr'] = $array_tmp['name'];
            }
            $array_tmp['ifspeed'] = $array_tmp['speed'];

            if (!isset($a_port['IFNUMBER'])) {
               continue;
            }
            $a_inventory['networkport'][$a_port['IFNUMBER']] = $array_tmp;

            if (isset($a_port['CONNECTIONS'])) {
               if (isset($a_port['CONNECTIONS']['CDP'])
                       && $a_port['CONNECTIONS']['CDP'] == 1) {

                  $array_tmp = $thisc->addValues($a_port['CONNECTIONS']['CONNECTION'],
                                                 array(
                                                    'IFDESCR'  => 'ifdescr',
                                                    'IFNUMBER' => 'logical_number',
                                                    'SYSDESCR' => 'sysdescr',
                                                    'MODEL'    => 'model',
                                                    'IP'       => 'ip',
                                                    'SYSMAC'   => 'mac',
                                                    'SYSNAME'  => 'name'));
                  $a_inventory['connection-lldp'][$a_port['IFNUMBER']] = $array_tmp;
               } else {
                  // MAC
                  if (isset($a_port['CONNECTIONS']['CONNECTION'])) {
                     if (!is_array($a_port['CONNECTIONS']['CONNECTION'])) {
                        $a_port['CONNECTIONS']['CONNECTION'] =
                                       array($a_port['CONNECTIONS']['CONNECTION']);
                     } else if (!is_int(key($a_port['CONNECTIONS']['CONNECTION']))) {
                        $a_port['CONNECTIONS']['CONNECTION'] =
                                       array($a_port['CONNECTIONS']['CONNECTION']);
                     }
                     foreach ($a_port['CONNECTIONS']['CONNECTION'] as $dataconn) {
                        foreach ($dataconn as $keymac=>$mac) {
                           if ($keymac == 'MAC') {
                              $a_inventory['connection-mac'][$a_port['IFNUMBER']] = $mac;
                           }
                        }
                     }
                     $a_inventory['connection-mac'][$a_port['IFNUMBER']] =
                                    array_unique($a_inventory['connection-mac'][$a_port['IFNUMBER']]);
                  }
               }
            }

            // VLAN
            if (isset($a_port['VLANS'])) {
               if (!is_int(key($a_port['VLANS']['VLAN']))) {
                  $a_port['VLANS']['VLAN'] = array($a_port['VLANS']['VLAN']);
               }

               foreach ($a_port['VLANS']['VLAN'] as $a_vlan) {
                  $array_tmp = $thisc->addValues($a_vlan,
                                                 array(
                                                    'NAME'   => 'name',
                                                    'NUMBER' => 'tag'));
                  if (isset($array_tmp['tag'])) {
                     $a_inventory['vlans'][$a_port['IFNUMBER']][$array_tmp['tag']] = $array_tmp;
                  }
               }
            }
            // AGGREGATE PORT
            if (isset($a_port['AGGREGATE'])) {
               if (!is_int(key($a_port['AGGREGATE']['PORT']))) {
                  $a_port['AGGREGATE']['PORT'] = array($a_port['AGGREGATE']['PORT']);
               }
               $a_inventory['aggregate'][$a_port['IFNUMBER']] = $a_port['AGGREGATE']['PORT'];
            }
         }
      }
      return $a_inventory;
   }



   /**
    * Convert printer in GLPI prepared data
    *
    * @param array $array
    * @return array
    */
   static function printerInventoryTransformation($array) {

      $a_inventory = array();
      $thisc = new self();

      // * INFO
      $array_tmp = $thisc->addValues($array['INFO'],
                                     array(
                                        'NAME'         => 'name',
                                        'SERIAL'       => 'serial',
                                        'ID'           => 'id',
                                        'MANUFACTURER' => 'manufacturers_id',
                                        'LOCATION'     => 'locations_id',
                                        'MODEL'        => 'printermodels_id',
                                        'MEMORY'       => 'memory_size'));
      $array_tmp['is_dynamic'] = 1;
      $array_tmp['have_ethernet'] = 1;

      $a_inventory['Printer'] = $array_tmp;
      $a_inventory['itemtype'] = 'Printer';

      $array_tmp = $thisc->addValues($array['INFO'],
                                     array(
                                        'COMMENTS' => 'sysdescr'));
      $array_tmp['last_fusioninventory_update'] = date('Y-m-d H:i:s');
      $a_inventory['PluginFusioninventoryPrinter'] = $array_tmp;

      // * PORTS
      $a_inventory['networkport'] = array();
      if (isset($array['PORTS'])) {
         foreach ($array['PORTS']['PORT'] as $a_port) {
            if (!isset($a_port['IFNUMBER'])) {
               $array_tmp = $thisc->addValues($a_port,
                                              array(
                                                 'IFNAME'   => 'name',
                                                 'IFNUMBER' => 'logical_number',
                                                 'MAC'      => 'mac',
                                                 'IP'       => 'ip',
                                                 'IFTYPE'   => 'iftype'));

               $a_inventory['networkport'][$a_port['IFNUMBER']] = $array_tmp;
            }
         }
      }

      // CARTRIDGES
      $a_inventory['cartridge'] = array();
      if (isset($array['CARTRIDGES'])) {
         $pfMapping = new PluginFusioninventoryMapping();

         foreach ($array['CARTRIDGES'] as $name=>$value) {
            $plugin_fusioninventory_mappings = $pfMapping->get("Printer", strtolower($name));
            if ($plugin_fusioninventory_mappings) {
               if (strstr($value, 'pages')) { // 30pages
                  $value = str_replace('pages', '', $value);
                  $value = 0 - $value;
               } else if ($value == '') { // no info
                  // nothing to do
               } else if (is_numeric($value)) { // percentage
                  // nothing to do
               } else if ($value == 'OK') { // state type 'OK'
                  $value = 100000;
               } else {
                  // special cases
                  $value = '';
               }
               if ($value != '') {
                  $a_inventory['cartridge'][$plugin_fusioninventory_mappings['id']] = $value;
               }
            }
         }
      }

      // * PAGESCOUNTER
      $a_inventory['pagecounters'] = array();
      if (isset($array['PAGECOUNTERS'])) {
         $array_tmp = $thisc->addValues($array['PAGECOUNTERS'],
                                        array(
                                           'TOTAL'       => 'pages_total',
                                           'BLACK'       => 'pages_n_b',
                                           'COLOR'       => 'pages_color',
                                           'RECTOVERSO'  => 'pages_recto_verso',
                                           'SCANNED'     => 'scanned',
                                           'PRINTTOTAL'  => 'pages_total_print',
                                           'PRINTBLACK'  => 'pages_n_b_print',
                                           'PRINTCOLOR'  => 'pages_color_print',
                                           'COPYTOTAL'   => 'pages_total_copy',
                                           'COPYBLACK'   => 'pages_n_b_copy',
                                           'COPYCOLOR'   => 'pages_color_copy',
                                           'FAXTOTAL'    => 'pages_total_fax'
                                         ));
         $a_inventory['pagecounters'] = $array_tmp;
      }

      return $a_inventory;
   }



   /**
    * Get type of the drive
    *
    * @param array $data information of the storage
    * @return string "Drive" or "HardDrive"
    */
   static function getTypeDrive($data) {
      $to_match_regex = ['rom', 'dvd', 'blu[\s-]*ray', 'reader',
                         'sd[\s-]*card', 'micro[\s-]*sd', 'mmc'];
      $found_drive    = false;
      foreach ($to_match_regex as $regex) {
         foreach (['TYPE', 'MODEL', 'NAME'] as $field) {
            if (isset($data[$field])
               && !empty($data[$field])
                  && preg_match("/".$regex."/i", $data[$field])) {
               $found_drive = true;
               break;
            }
         }
      }
      if ($found_drive) {
         return 'Drive';
      }
      return 'HardDrive';
   }
}
