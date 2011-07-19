<?php

/*
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

$width_left                  = 590;
$height_left                 = 350;

$width_right                  = 340;
$height_right                 = 350;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$label_width = 140;

$field_width = 170;

$JS = <<<JS

var taskJobsColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'task_id',
   dataIndex: 'task_id',
   hidden: true
}, {
   id: 'task_name',
   dataIndex: 'task_name',
   header: 'task name',
   renderer: renderTasks,
   groupRenderer: renderTasks
}, {
   id: 'name',
   dataIndex: 'name',
   header: 'job name',
   renderer: renderTaskJobName
}, {
   id: 'status',
   dataIndex: 'status',
   header: 'status',
   renderer: renderTaskJobStatus
}]

function renderTasks(val) {
   var img = '<img src="../pics/ext/task.png">&nbsp;';
   return img+val;
}

function renderTaskJobName(val) {
   var img = '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../pics/ext/taskjob.png">&nbsp;';
   return img+val;
}

function renderTaskJobStatus(val) {
   var img = '<img src="../pics/ext/bullet-green.png">&nbsp;';
   return img+val;
}

var tasksJobLogsColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'date',
   dataIndex: 'date',
   header: '{$LANG['common'][27]}'
}, {
   id: 'comment',
   dataIndex: 'comment',
   header: '{$LANG['common'][25]}'
}];

var taskJobsReader = new Ext.data.JsonReader({
   root: 'taskjobs',
   fields: [
      'id', 'name', 'task_id', 'task_name'
   ]
});

var taskJobLogsReader = new Ext.data.JsonReader({
   root: 'task_logs',
   fields: [
      'id', 'name'
   ]
});

var taskJobsStore = new Ext.data.GroupingStore({
   url: '../ajax/state_taskjobs.data.php',
   autoLoad: true,
   reader: taskJobsReader,
   sortInfo: {field: 'task_name', direction: "ASC"},
   groupField : 'task_name'
});

var taskJobLogsStore = new Ext.data.Store({
   url: '../ajax/state_taskjoblogs.data.php',
   autoLoad: false,
   reader: taskJobLogsReader,
   sortInfo: {field: '', direction: "ASC"}
});

var taskJobsGrid = new Ext.grid.GridPanel({
   region: 'center',
   stripeRows: true,
   height: {$height_left},
   width: {$width_left},
   style: 'margin-bottom:5px',
   title: "{$LANG['plugin_fusioninventory']['menu'][7]}",
   store: taskJobsStore,
   columns: taskJobsColumns,
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '<b>{text}</b>',
      hideGroupedColumn: true,
      showGroupName: false,
      emptyText: '',
      emptyGroupText: ''
   })
});

var taskJobLogsGrid = new Ext.grid.GridPanel({
   region: 'east',
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style: 'margin-bottom:5px',
   title: 'logs associés',
   store: taskJobLogsStore,
   columns: tasksJobLogsColumns
});


//render elements in a border layout
var stateLayout = new Ext.Panel({
   layout: 'border',
   renderTo: 'deployStates',
   height: {$height_layout},
   width: {$width_layout},
   defaults: {
      split: true
   },
   items:[
      taskJobsGrid, taskJobLogsGrid
   ]
});


JS;

echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
