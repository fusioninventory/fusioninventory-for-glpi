<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2014

   ------------------------------------------------------------------------
 */

class TimeslotTest extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function addTimeslot() {
      $pfTimeslot = new PluginFusioninventoryTimeslot();
      $input = array(
          'entities_id'  => 0,
          'is_recursive' => 0,
          'name'         => 'unitdefault'
      );
      $pfTimeslot->add($input);
      $cnt = countElementsInTable('glpi_plugin_fusioninventory_timeslots');
      $this->assertEquals(1, $cnt, "Timeslot may be added");
   }



   /**
    * @test
    */
   public function addSimpleEntrieslot() {
      $pfTimeslotEntry = new PluginFusioninventoryTimeslotEntry();
      $input = array(
          'plugin_fusioninventory_timeslots_id' => 1,
          'entities_id'  => 0,
          'is_recursive' => 0,
          'day'          => 1,
          'begin'        => 7215,
          'end'          => 43200
      );
      $pfTimeslotEntry->add($input);

      $input = array(
          'plugin_fusioninventory_timeslots_id' => 1,
          'entities_id'  => 0,
          'is_recursive' => 0,
          'day'          => 1,
          'begin'        => 72000,
          'end'          => 79200
      );
      $pfTimeslotEntry->add($input);

      $input = array(
          'plugin_fusioninventory_timeslots_id' => 1,
          'entities_id'  => 0,
          'is_recursive' => 0,
          'day'          => 3,
          'begin'        => 39600,
          'end'          => 79200
      );
      $pfTimeslotEntry->add($input);

      $references = array(
          '1' => array(
              'id' => '1',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '1',
              'begin'        => '7215',
              'end'          => '43200'
          ),
          '2' => array(
              'id'           => '2',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '1',
              'begin'        => '72000',
              'end'          => '79200'
          ),
          '3' => array(
              'id'           => '3',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '3',
              'begin'        => '39600',
              'end'          => '79200'
          )
      );
      $db = getAllDatasFromTable('glpi_plugin_fusioninventory_timeslotentries');
      $this->assertEquals($references, $db, "May have 3 entries");
   }



   /**
    * @test
    */
   public function addEntrieslotYetAdded() {

      $pfTimeslotEntry = new PluginFusioninventoryTimeslotEntry();
      $input = array(
          'timeslots_id' => 1,
          'beginday'     => 1,
          'lastday'      => 1,
          'beginhours'   => 7230,
          'lasthours'    => 43140
      );
      $pfTimeslotEntry->addEntry($input);

      $input = array(
          'timeslots_id' => 1,
          'beginday'     => 1,
          'lastday'      => 1,
          'beginhours'   => 72000,
          'lasthours'    => 79140
      );
      $pfTimeslotEntry->addEntry($input);

      $references = array(
          '1' => array(
              'id' => '1',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '1',
              'begin'        => '7215',
              'end'          => '43200'
          ),
          '2' => array(
              'id'           => '2',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '1',
              'begin'        => '72000',
              'end'          => '79200'
          ),
          '3' => array(
              'id'           => '3',
              'entities_id'  => '0',
              'plugin_fusioninventory_timeslots_id' => '1',
              'is_recursive' => '0',
              'day'          => '3',
              'begin'        => '39600',
              'end'          => '79200'
          )
      );
      $db = getAllDatasFromTable('glpi_plugin_fusioninventory_timeslotentries');
      $this->assertEquals($references, $db, "May have 3 entries ".print_r($db, true));
   }

}

