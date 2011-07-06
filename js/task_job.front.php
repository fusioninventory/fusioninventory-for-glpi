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
   id: 'group_id',
   dataIndex: 'group_id',
   hidden: true
}, {
   id: 'group_name',
   header: 'Groupe',
   dataIndex: 'group_name',
   groupRenderer: renderGroup,
   renderer: renderGroup,
   width:150
}, {
   id: 'package_id',
   dataIndex: 'package_id',
   hidden: true
}, {
   id: 'package_name',
   header: 'Paquet',
   dataIndex: 'package_name',
   renderer: renderPackage,
   groupable: false
}];

//define renderer for grid columns
function renderGroup(val) {
   return '<img src="../pics/ext/group.png">&nbsp;'+val
}
function renderPackage(val) {
   return '&nbsp;&nbsp;&nbsp;<img src="../pics/ext/package.png">&nbsp;'+val
}

//create store and load data
var taskJobGridReader = new Ext.data.JsonReader({
   root: 'tasks',
   fields: ['id', 'group_id', 'group_name', 'package_id', 'package_name']
});

var taskJobStore = new Ext.data.GroupingStore({
   url: '../ajax/task_job.data.php?tasks_id={$id}',
   autoLoad: true,
   reader: taskJobGridReader,
   sortInfo: {field: 'group_name', direction: "ASC"},
   groupField : 'group_name'
});

/**** DEFINE GRID ****/
var taskJobGrid = new Ext.grid.GridPanel({
   region: 'center',
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style:'margin-bottom:5px',
   columns: taskJobColumns,
   store: taskJobStore,
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text} ({[values.rs.length]})',
      startCollapsed : true,
      forceFit : true,
      hideGroupedColumn: true,
      showGroupName: false
   }),
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][0]}',
      iconCls: 'exticon-add',
      handler: function(btn,ev) {

      }
   }, '-', {
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][1]}',
      iconCls: 'exticon-delete',
      handler: function(btn,ev) {
         var selection = taskJobGrid.getSelectionModel().getSelections();
         if (!selection) return false;

         for(var i = 0, r; r = selection[i]; i++){
            taskJobStore.remove(r);
         }

         /*Ext.Ajax.request({
            url: '../ajax/package_action.delete.php',
            params: {

            }
         });*/

         if(taskJobStore.data.length == 0) {
            taskJobForm.collapse();
            taskJobForm.buttons[0].setDisabled(true);
         } else {
            taskJobGrid.getSelectionModel().selectFirstRow();
         }
      }
   }, '-'],
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g,index,ev) {

         }
      }
   })
});


/**** DEFINE STORES AND JSON READER FOR FORM ****/

var groupReader = new Ext.data.JsonReader({
   root: 'groups',
   totalProperty: 'results',
   fields: ['group_id', 'group_name']
});

var packageReader = new Ext.data.JsonReader({
   root: 'packages',
   totalProperty: 'results',
   fields: ['package_id', 'package_name']
});

var groupStore = new Ext.data.Store({
   url: '../ajax/task_job_group.data.php',
   autoLoad: true,
   reader: groupReader
});

var packageStore = new Ext.data.Store({
   url: '../ajax/task_job_package.data.php',
   autoLoad: true,
   reader: packageReader
});

/**** DEFINE FORM ****/
var taskJobForm = new Ext.FormPanel({
   region: 'east',
   collapsible: true,
   /*collapsed: true,*/
   labelWidth: {$label_width},
   bodyStyle:'padding:5px 10px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_left},
   height: {$height_left},
   title: '{$LANG['plugin_fusinvdeploy']['task'][11]}',
   items: [
      new Ext.form.ComboBox({
         fieldLabel:'{$LANG['plugin_fusinvdeploy']['task'][6]}',
         name: 'group_id',
         valueField: 'group_id',
         displayField: 'group_name',
         hiddenName: 'group_id',
         triggerAction: 'all',
         store: groupStore,
         listeners: {
            select: function() {
               taskJobForm.store.reload();
            }
         }
      }),
      new Ext.form.ComboBox({
         fieldLabel:'{$LANG['plugin_fusinvdeploy']['package'][7]}',
         name: 'package_id',
         valueField: 'package_id',
         displayField: 'package_name',
         hiddenName: 'package_id',
         triggerAction: 'all',
         store: packageStore,
         listeners: {
            select: function() {
               taskJobForm.store.reload();
            }
         }
      })
   ],
   buttons: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][2]}',
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {

      }
   }]
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
