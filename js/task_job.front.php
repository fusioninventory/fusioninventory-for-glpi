<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Alexandre DELAUNAY
// Purpose of file:
// ----------------------------------------------------------------------
global $LANG;

$width_right                  = 590;
$height_right                 = 300;

$width_left                   = 340;
$height_left                  = 300;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$label_width = 75;

$field_width = 215;
$field_height = 70;

$JS = <<<JS

//define colums for grid
var taskJobColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'group',
   header: 'Groupe',
   dataIndex: 'group',
   renderer: renderGroup
}];

//define renderer for grid columns
function renderGroup(val) {
   return val
}

//create store and load data
var taskJobGridReader = new Ext.data.JsonReader({
   root: 'tasks',
   fields: ['id', 'group', 'package']
});

var taskJobStore = new Ext.data.GroupingStore({
   url: '../ajax/task_job.data.php?group_id={$id}',
   autoLoad: true,
   reader: taskJobGridReader,
   sortInfo: {field: 'id', direction: "ASC"},
   groupField : 'group'
});

/**** DEFINE GRID ****/
var taskJobGrid = new Ext.grid.GridPanel({
   region: 'center',
   margins: '0 0 0 5',
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style:'margin-bottom:5px',
   columns: taskJobColumns,
   store: taskJobStore
});



/**** DEFINE FORM ****/
var taskJobForm = new Ext.FormPanel({
   region: 'east',
   collapsible: true,
   collapsed: true,
   labelWidth: {$label_width},
   bodyStyle:'padding:5px 10px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_left},
   height: {$height_left}
});


//render grid and form in a border layout
var taskLayout = new Ext.Panel({
   layout: 'border',
   renderTo: 'TaskJob',
   height: {$height_layout},
   width: {$width_layout},
   defaults: {
      split: true
   },
   items:[
      taskJobGrid, taskJobForm
   ]
});


JS;

echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
