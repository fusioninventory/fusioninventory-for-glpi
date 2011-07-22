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
   id: 'task_id',
   dataIndex: 'task_id',
   hidden: true
}, {
   id: 'task_percent',
   dataIndex: 'task_percent',
   hidden: true
}, {
   id: 'task_name',
   dataIndex: 'task_name',
   /*header: 'task name',*/
   renderer: renderTasks,
   groupRenderer: renderTasks
}, {
   id: 'name',
   dataIndex: 'name',
   /*header: 'job name',*/
   renderer: renderTaskJobName,
   width: 70
}, {
   id: 'status',
   dataIndex: 'status',
   /*header: '{$LANG['joblist'][0]}',*/
   width: 70,
   renderer: renderTaskJobStatus,
}, {
   id: 'computer_name',
   dataIndex: 'computer_name',
   /*header: "{$LANG['rulesengine'][25]}",*/
   renderer: renderComputer
}, {
   id: 'job_id',
   dataIndex: 'job_id',
   hidden: true
}, {
   id: 'status_id',
   dataIndex: 'status_id',
   hidden: true
}]

function renderTasks(val, metaData, record) {
   var task_percent = record.data.task_percent;
   var img = '<img src="../pics/ext/task.png">&nbsp;';
   var progressbar = '<div style="float:right;">{$LANG['common'][47]} :&nbsp;<div class="progress-container"><div style="width: '+task_percent+'">'+task_percent+'</div></div></div>';
   return img+"<b>"+val+"</b>"+progressbar;
}

function renderTaskJobName(val) {
   var img = '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../pics/ext/taskjob.png">&nbsp;';
   return img+val;
}

function renderComputer(val) {
   if (val == 'N/A') return '';
   var img = '<img src="../pics/ext/computer.png">&nbsp;';
   return img+val;
}

function renderTaskJobStatus(val) {
   switch (val) {
      case '0':
         var img_name = 'bullet-blue.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][7]}';
         break
      case '1':
         var img_name = 'bullet-yellow.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][1]}';
         break
      case '2':
         var img_name = 'bullet-green.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][2]}';
         break
      case '3':
         var img_name = 'bullet-red.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][3]}';
         break
      case '4':
         var img_name = 'bullet-red.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][4]}';
         break
      case '5':
         var img_name = 'bullet-red.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][5]}';
         break
      case '6':
         var img_name = 'bullet-yellow.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][6]}';
         break
      case '7':
         var img_name = 'bullet-blue.png';
         val = '{$LANG['plugin_fusioninventory']['taskjoblog'][7]}';
         break
      default:
         var img_name = 'bullet-grey.png';
         val = '';
   }

   var img = '<img src="../pics/ext/'+img_name+'">&nbsp;';
   return img+val;
}

var tasksJobLogsColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'date',
   dataIndex: 'date',
   header: '{$LANG['common'][27]}',
   width: 130
}, {
   id: 'comment',
   dataIndex: 'comment',
   header: '{$LANG['common'][25]}',
   width: 200
}];

var taskJobsReader = new Ext.data.JsonReader({
   root: 'taskjobs',
   fields: [
      'name', 'task_id', 'task_percent', 'task_name',
      'computer_name', 'status', 'job_id', 'status_id'
   ]
});

var taskJobLogsReader = new Ext.data.JsonReader({
   root: 'taskjoblogs',
   fields: [
      'id', 'date', 'comment'
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
   sortInfo: {field: 'id', direction: "ASC"}
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
      groupTextTpl: '{text}',
      hideGroupedColumn: true,
      showGroupName: false,
      emptyText: '',
      emptyGroupText: '',
      startCollapsed: true
   }),
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g,index,ev) {
            var rec = taskJobsGrid.store.getAt(index);
            var status_id = rec.data.status_id;

            taskJobLogsGrid.getStore().setBaseParam('status_id', status_id);
            taskJobLogsGrid.getStore().removeAll();
            taskJobLogsGrid.getStore().reload();
            console.log(taskJobLogsGrid.getStore());
         }
      }
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
