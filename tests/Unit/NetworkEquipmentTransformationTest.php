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

class NetworkEquipmentTransformationTest extends TestCase {


   /**
    * @test
    */
   public function ConvertXmltoArray1() {
      global $DB;

      $DB->connect();

      $xml_source = '<?xml version="1.0"?>
<REQUEST>
<CONTENT>
   <DEVICE>
      <INFO>
        <COMMENTS>ProCurve J9085A</COMMENTS>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <ID>123</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <MODEL>J9085A</MODEL>
        <NAME>FR-SW01</NAME>
        <SERIAL>CN536H7J</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
      </INFO>
      <PORTS>
        <PORT>
          <CONNECTIONS>
            <CONNECTION>
              <MAC>00:40:9d:3b:7f:c4</MAC>
            </CONNECTION>
          </CONNECTIONS>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
   </DEVICE>
</CONTENT>
</REQUEST>';

      $xml = simplexml_load_string($xml_source, 'SimpleXMLElement', LIBXML_NOCDATA);

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return = $pfFormatconvert->XMLtoArray($xml);

      $a_reference = [
          'CONTENT' => [
          'DEVICE' => [
              [
               'INFO' => [
                        'COMMENTS' => 'ProCurve J9085A',
                        'FIRMWARE' => 'R.10.06 R.11.60',
                        'ID'       => '123',
                        'IPS'      => [
                                 'IP' => ['192.168.1.56', '192.168.10.56']
                            ],
                        'LOCATION' => 'BAT A - Niv 3',
                        'MAC'      => 'b4:39:d6:3a:7f:00',
                        'MODEL'    => 'J9085A',
                        'NAME'     => 'FR-SW01',
                        'SERIAL'   => 'CN536H7J',
                        'TYPE'     => 'NETWORKING',
                        'UPTIME'   => '8 days, 01:48:57.95'
                   ],
               'PORTS' => [
                       'PORT' => [
                           [
                                'CONNECTIONS' => [
                                         'CONNECTION' => [
                                             'MAC' => ['00:40:9d:3b:7f:c4']
                                             ]
                                    ],

                                'IFDESCR'  => '3',
                                'IFNAME'   => '3',
                                'IFNUMBER' => '3',
                                'IFSTATUS' => '1',
                                'IFTYPE'   => '6',
                                'MAC'      => 'b4:39:d6:3b:22:bd',
                                'VLANS'    => [
                                         'VLAN' => [
                                             [
                                                  'NAME'   => 'VLAN160',
                                                  'NUMBER' => '160'
                                             ]
                                         ]
                                    ]
                               ]
                           ]
                   ]
                ]
              ]
              ]
      ];

      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function ConvertXmltoArrayAggregatePort() {
      global $DB;

      $DB->connect();

      $xml_source = '<?xml version="1.0"?>
<REQUEST>
<CONTENT>
   <DEVICE>
      <INFO>
        <COMMENTS>ProCurve J9085A</COMMENTS>
        <FIRMWARE>R.10.06 R.11.60</FIRMWARE>
        <ID>123</ID>
        <IPS>
          <IP>192.168.1.56</IP>
          <IP>192.168.10.56</IP>
        </IPS>
        <LOCATION>BAT A - Niv 3</LOCATION>
        <MAC>b4:39:d6:3a:7f:00</MAC>
        <MODEL>J9085A</MODEL>
        <NAME>FR-SW01</NAME>
        <SERIAL>CN536H7J</SERIAL>
        <TYPE>NETWORKING</TYPE>
        <UPTIME>8 days, 01:48:57.95</UPTIME>
      </INFO>
      <PORTS>
         <PORT>
           <AGGREGATE>
             <PORT>10001</PORT>
             <PORT>10002</PORT>
           </AGGREGATE>
           <IFDESCR>Port-channel10</IFDESCR>
           <IFINERRORS>0</IFINERRORS>
           <IFINOCTETS>1076823325</IFINOCTETS>
           <IFINTERNALSTATUS>1</IFINTERNALSTATUS>
           <IFLASTCHANGE>15 days, 23:20:53.24</IFLASTCHANGE>
           <IFMTU>1500</IFMTU>
           <IFNAME>Po10</IFNAME>
           <IFNUMBER>5010</IFNUMBER>
           <IFOUTERRORS>0</IFOUTERRORS>
           <IFOUTOCTETS>2179528910</IFOUTOCTETS>
           <IFSPEED>4294967295</IFSPEED>
           <IFSTATUS>1</IFSTATUS>
           <IFTYPE>53</IFTYPE>
           <MAC>c4:64:13:8c:7e:b5</MAC>
         </PORT>
      </PORTS>
   </DEVICE>
</CONTENT>
</REQUEST>';

      $xml = simplexml_load_string($xml_source, 'SimpleXMLElement', LIBXML_NOCDATA);

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return = $pfFormatconvert->XMLtoArray($xml);

      $a_reference = [
          'CONTENT' => [
          'DEVICE' => [
              [
               'INFO' => [
                        'COMMENTS' => 'ProCurve J9085A',
                        'FIRMWARE' => 'R.10.06 R.11.60',
                        'ID'       => '123',
                        'IPS'      => [
                                 'IP' => ['192.168.1.56', '192.168.10.56']
                            ],
                        'LOCATION' => 'BAT A - Niv 3',
                        'MAC'      => 'b4:39:d6:3a:7f:00',
                        'MODEL'    => 'J9085A',
                        'NAME'     => 'FR-SW01',
                        'SERIAL'   => 'CN536H7J',
                        'TYPE'     => 'NETWORKING',
                        'UPTIME'   => '8 days, 01:48:57.95'
                   ],
               'PORTS' => [
                       'PORT' => [
                           [
                                'AGGREGATE' => [
                                    'PORT' => ['10001', '10002']
                                ],
                                'IFDESCR' => 'Port-channel10',
                                'IFNAME' => 'Po10',
                                'IFNUMBER' => '5010',
                                'IFSTATUS' => '1',
                                'IFTYPE' => '53',
                                'MAC' => 'c4:64:13:8c:7e:b5',
                                'IFINERRORS' => '0',
                                'IFINOCTETS' => '1076823325',
                                'IFINTERNALSTATUS' => '1',
                                'IFLASTCHANGE' => '15 days, 23:20:53.24',
                                'IFMTU' => '1500',
                                'IFOUTERRORS' => '0',
                                'IFOUTOCTETS' => '2179528910',
                                'IFSPEED' => '4294967295'
                           ]
                       ]
                   ]
                ]
              ]
              ]
      ];

      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function ConvertXmltoArrayMultiVlan() {
      global $DB;

      $DB->connect();

      $xml_source = '<?xml version="1.0"?>
<REQUEST>
<CONTENT>
   <DEVICE>
      <INFO>
        <ID>123</ID>
        <NAME>FR-SW01</NAME>
        <SERIAL>CN536H7J</SERIAL>
        <TYPE>NETWORKING</TYPE>
      </INFO>
      <PORTS>
        <PORT>
          <IFDESCR>3</IFDESCR>
          <IFNAME>3</IFNAME>
          <IFNUMBER>3</IFNUMBER>
          <IFSTATUS>1</IFSTATUS>
          <IFTYPE>6</IFTYPE>
          <MAC>b4:39:d6:3b:22:bd</MAC>
          <VLANS>
            <VLAN>
              <NAME>VLAN160</NAME>
              <NUMBER>160</NUMBER>
            </VLAN>
            <VLAN>
              <NAME>VLAN161</NAME>
              <NUMBER>161</NUMBER>
            </VLAN>
          </VLANS>
        </PORT>
      </PORTS>
   </DEVICE>
</CONTENT>
</REQUEST>';

      $xml = simplexml_load_string($xml_source, 'SimpleXMLElement', LIBXML_NOCDATA);

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      $a_return = $pfFormatconvert->XMLtoArray($xml);

      $a_reference = [
          'CONTENT' => [
          'DEVICE' => [
              [
               'INFO' => [
                        'ID'       => '123',
                        'NAME'     => 'FR-SW01',
                        'SERIAL'   => 'CN536H7J',
                        'TYPE'     => 'NETWORKING'
                   ],
               'PORTS' => [
                       'PORT' => [
                           [
                                'IFDESCR'  => '3',
                                'IFNAME'   => '3',
                                'IFNUMBER' => '3',
                                'IFSTATUS' => '1',
                                'IFTYPE'   => '6',
                                'MAC'      => 'b4:39:d6:3b:22:bd',
                                'VLANS'    => [
                                         'VLAN' => [
                                             [
                                                  'NAME'   => 'VLAN160',
                                                  'NUMBER' => '160'
                                             ],
                                             [
                                                  'NAME'   => 'VLAN161',
                                                  'NUMBER' => '161'
                                             ]
                                         ]
                                    ]
                               ]
                           ]
                   ]
                ]
              ]
              ]
      ];

      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function NetworkEquipmentGeneral() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [];
      $a_inventory['INFO'] = [
                'COMMENTS'       => 'Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team',
                'CPU'            => 6,
                'FIRMWARE'       => '12.2(55)SE',
                'ID'             => '55',
                'IPS'            => ['IP' => ['172.27.0.40', '172.27.1.40']],
                'LOCATION'       => 'Room 100',
                'MAC'            => '00:1b:2b:20:40:80',
                'MEMORY'         => 33,
                'MODEL'          => 'WS-C3750G-24T-S',
                'NAME'           => 'sw1.siprossii.com',
                'RAM'            => 128,
                'SERIAL'         => 'CAT1109RGVK',
                'TYPE'           => 'NETWORKING',
                'UPTIME'         => '41 days, 06:53:36.46',
                'MANUFACTURER'   => 'Cisco'
            ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->networkequipmentInventoryTransformation($a_inventory);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['PluginFusioninventoryNetworkEquipment'])
              && isset($a_return['PluginFusioninventoryNetworkEquipment']['last_fusioninventory_update'])) {
         $date = $a_return['PluginFusioninventoryNetworkEquipment']['last_fusioninventory_update'];
      }
      $a_reference = [
          'PluginFusioninventoryNetworkEquipment' => [
                  'sysdescr'                    => 'Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team',
                  'last_fusioninventory_update' => $date,
                  'cpu'                         => 6,
                  'memory'                      => 33,
                  'uptime'                      => '41 days, 06:53:36.46'
                ],
          'networkport'    => [],
          'internalport'   => ['172.27.0.40', '172.27.1.40'],
          'itemtype'       => 'NetworkEquipment',
          'components'     => []
          ];
      $a_reference['NetworkEquipment'] = [
               'name'                           => 'sw1.siprossii.com',
               'id'                             => '55',
               'locations_id'                   => 'Room 100',
               'mac'                            => '00:1b:2b:20:40:80',
               'memory'                         => 33,
               'networkequipmentmodels_id'      => 'WS-C3750G-24T-S',
               'ram'                            => 128,
               'serial'                         => 'CAT1109RGVK',
               'manufacturers_id'               => 'Cisco',
               'is_dynamic'                     => 1

            ];
      $a_reference['firmwares'] = [
         [
            'name'                     => '12.2(55)SE',
            'version'                  => '12.2(55)SE',
            'description'              => '',
            'manufacturers_id'         => '',
            'devicefirmwaretypes_id'   => ''
         ]
      ];
      $this->assertEquals($a_reference, $a_return);

      //For FI agents > 2.3.21
      $a_inventory = [];
      $a_inventory['INFO'] = [
                'COMMENTS'       => 'Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team',
                'CPU'            => 6,
                'FIRMWARE'       => '12.2(55)SE',
                'ID'             => '55',
                'IPS'            => ['IP' => ['172.27.0.40', '172.27.1.40']],
                'LOCATION'       => 'Room 100',
                'MAC'            => '00:1b:2b:20:40:80',
                'MEMORY'         => 33,
                'MODEL'          => 'WS-C3750G-24T-S',
                'NAME'           => 'sw1.siprossii.com',
                'RAM'            => 128,
                'SERIAL'         => 'CAT1109RGVK',
                'TYPE'           => 'NETWORKING',
                'UPTIME'         => '41 days, 06:53:36.46',
                'MANUFACTURER'   => 'Cisco'
             ];
      $a_inventory['FIRMWARES'][] = [
         'DESCRIPTION'  => 'The firmware',
         'MANUFACTURER' => 'Cisco',
         'NAME'         => 'Firmware name',
         'TYPE'         => 'device',
         'VERSION'      => '12.2(55)SE',
      ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->networkequipmentInventoryTransformation($a_inventory);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['PluginFusioninventoryNetworkEquipment'])
              && isset($a_return['PluginFusioninventoryNetworkEquipment']['last_fusioninventory_update'])) {
         $date = $a_return['PluginFusioninventoryNetworkEquipment']['last_fusioninventory_update'];
      }
      $a_reference = [
          'PluginFusioninventoryNetworkEquipment' => [
                  'sysdescr'                    => 'Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team',
                  'last_fusioninventory_update' => $date,
                  'cpu'                         => 6,
                  'memory'                      => 33,
                  'uptime'                      => '41 days, 06:53:36.46'
               ],
          'networkport'    => [],
          'internalport'   => ['172.27.0.40', '172.27.1.40'],
          'itemtype'       => 'NetworkEquipment',
          'components'     => []
          ];
      $a_reference['NetworkEquipment'] = [
               'name'                           => 'sw1.siprossii.com',
               'id'                             => '55',
               'locations_id'                   => 'Room 100',
               'mac'                            => '00:1b:2b:20:40:80',
               'memory'                         => 33,
               'networkequipmentmodels_id'      => 'WS-C3750G-24T-S',
               'ram'                            => 128,
               'serial'                         => 'CAT1109RGVK',
               'manufacturers_id'               => 'Cisco',
               'is_dynamic'                     => 1

            ];
      $a_reference['firmwares'] = [
         [
            'name'                     => 'Firmware name',
            'version'                  => '12.2(55)SE',
            'description'              => 'The firmware',
            'manufacturers_id'         => 'Cisco',
            'devicefirmwaretypes_id'   => 'device'
         ]
      ];
      $this->assertEquals($a_reference, $a_return);
   }


   /**
    * @test
    */
   public function NetworkEquipmentConnectionMac() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = [];
      $a_inventory['INFO'] = [
                'COMMENTS'       => 'Cisco IOS Software, C3750 Software (C3750-IPSERVICESK9-M), Version 12.2(55)SE, RELEASE SOFTWARE (fc2)
Technical Support: http://www.cisco.com/techsupport
Copyright (c) 1986-2010 by Cisco Systems, Inc.
Compiled Sat 07-Aug-10 22:45 by prod_rel_team',
                'ID'             => '55',
                'MAC'            => '00:1b:2b:20:40:80',
                'NAME'           => 'sw1.siprossii.com',
                'SERIAL'         => 'CAT1109RGVK',
                'TYPE'           => 'NETWORKING'
            ];
      $a_inventory['PORTS']['PORT'][1] = [
          'CONNECTIONS' => [
               'CONNECTION' => [
                     'MAC' => [
                           '00:0f:fe:0d:30:70'
                      ]
                ]
           ],
          'IFNAME'   => 'Fa0/1',
          'IFNUMBER' => 1,
          'IFTYPE'   => 6,
          'MAC'      => 'b4:39:d6:3a:7f:3a'
      ];
      $a_inventory['PORTS']['PORT'][2] = [
          'CONNECTIONS' => [
               'CONNECTION' => [
                     'MAC' => [
                           '00:0f:fe:0d:30:76',
                           '00:0f:fe:0d:30:77',
                           '00:0f:fe:0d:30:78'
                      ]
                ]
           ],
          'IFNAME'   => 'Fa0/2',
          'IFNUMBER' => 2,
          'IFTYPE'   => 6,
          'MAC'      => 'b4:39:d6:3a:7f:3e'
      ];

      $pfFormatconvert = new PluginFusioninventoryFormatconvert();

      $a_return = $pfFormatconvert->networkequipmentInventoryTransformation($a_inventory);

      $a_reference = [
               '00:0f:fe:0d:30:70'

      ];
      $this->assertEquals($a_reference, $a_return['connection-mac'][1]);

      $a_reference = [];
      $a_reference = [
               '00:0f:fe:0d:30:76',
               '00:0f:fe:0d:30:77',
               '00:0f:fe:0d:30:78'

      ];
      $this->assertEquals($a_reference, $a_return['connection-mac'][2]);
   }
}
