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

$label_width = 125;

$field_width = 190;
$field_height = 70;

$JS = <<<JS

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
   reader: groupReader
});
groupStore.load();

var packageStore = new Ext.data.Store({
   url: '../ajax/task_job_package.data.php',
   reader: packageReader
});
packageStore.load();



/**** DEFINE COLUMS FOR GRID ****/
var taskJobColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'group_id',
   dataIndex: 'group_id',
   header: 'Groupe',
   renderer: renderGroup,
   width:150
}, {
   id: 'package_id',
   header: 'Paquet',
   dataIndex: 'package_id',
   renderer: renderPackage,
   groupable: false
}, {
   id: 'retry_nb',
   dataIndex: 'retry_nb',
   hidden: true
}, {
   id: 'retry_time',
   dataIndex: 'retry_time',
   hidden: true
}, {
   id: 'periodicity_count',
   dataIndex: 'periodicity_count',
   hidden: true
}, {
   id: 'periodicity_type',
   dataIndex: 'periodicity_type',
   hidden: true
}];

//define renderer for grid columns
function renderGroupedGroup(val) {
   return '<img src="../pics/ext/group.png">&nbsp;'+val;
}
function renderGroup(val) {
   var img = '<img src="../pics/ext/group.png">&nbsp;';
   var index = groupStore.findExact('group_id', val)
   if (index != -1) {
      var record = groupStore.getAt(index);
      return img+record.get('group_name');
   } else return '';
}
function renderPackage(val) {
   var img = '&nbsp;&nbsp;&nbsp;<img src="../pics/ext/package.png">&nbsp;';
   var index = packageStore.findExact('package_id', val)
   if (index != -1) {
      var record = packageStore.getAt(index);
      return img+record.get('package_name');
   } else return '';
}

//create store and load data
var taskJobGridReader = new Ext.data.JsonReader({
   root: 'tasks',
   fields: [
      'group_id', 'package_id', 'retry_nb',
      'retry_time', 'periodicity_count', 'periodicity_type'
   ]
});

var taskJobStore = new Ext.data.GroupingStore({
   url: '../ajax/task_job.data.php?tasks_id={$id}',
   autoLoad: true,
   reader: taskJobGridReader,
   sortInfo: {field: 'group_id', direction: "ASC"},
   groupField : 'group_id'
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
   title: 'test',
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text} ({[values.rs.length]})',
      forceFit : true,
      hideGroupedColumn: true,
      showGroupName: false
   }),
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][0]}',
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new taskJobStore.recordType({
            group_id:      '',
            package_id:    ''
         });
         taskJobStore.insert(0,u);
         taskJobGrid.getSelectionModel().selectFirstRow();
         taskJobForm.setTitle('{$LANG['plugin_fusinvdeploy']['task'][12]}');
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
            var rec = taskJobGrid.store.getAt(index);
            taskJobForm.loadData(rec);
            taskJobForm.setTitle('{$LANG['plugin_fusinvdeploy']['task'][11]}');
            taskJobForm.expand();
            taskJobForm.buttons[0].setDisabled(false);
         }
      }
   })
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
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['task'][6]}',
         name: 'group_id',
         valueField: 'group_id',
         displayField: 'group_name',
         hiddenName: 'group_id',
         allowBlank: false,
         triggerAction: 'all',
         store: groupStore,
         width: {$field_width}
      }),
      new Ext.form.ComboBox({
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['package'][7]}',
         name: 'package_id',
         valueField: 'package_id',
         displayField: 'package_name',
         hiddenName: 'package_id',
         allowBlank: false,
         triggerAction: 'all',
         store: packageStore,
         width: {$field_width}
      }), {
         fieldLabel:'{$LANG['plugin_fusioninventory']['task'][31]}',
         layout: 'column',
         items: [{
            xtype: 'textfield',
            name: 'periodicity_count',
            hiddenName: 'periodicity_count',
            allowBlank: false,
            style: 'margin-right:5px;',
            width: 30
         },
         new Ext.form.ComboBox({
            name: 'periodicity_type',
            valueField: 'name',
            displayField: 'value',
            hiddenName: 'periodicity_type',
            allowBlank: false,
            width: 100,
            store: new Ext.data.ArrayStore({
               fields: ['name', 'value'],
               data: [
                  ['minutes',  '{$LANG['plugin_fusioninventory']['task'][35]}'],
                  ['hours',    '{$LANG['plugin_fusioninventory']['task'][36]}'],
                  ['days',     '{$LANG['plugin_fusioninventory']['task'][37]}'],
                  ['months',   '{$LANG['plugin_fusioninventory']['task'][38]}']
               ]
            }),
            mode: 'local',
            triggerAction: 'all'
         })]
      }, {
         fieldLabel: "{$LANG['plugin_fusioninventory']['task'][24]}",
         xtype: 'textfield',
         name: 'retry_nb',
         hiddenName: 'retry_nb',
         allowBlank: false,
         width:'30px'
      }, {
         fieldLabel: '{$LANG['plugin_fusioninventory']['task'][25]}',
         xtype: 'textfield',
         name: 'retry_time',
         hiddenName: 'retry_time',
         allowBlank: false,
         width:'30px'
      }
   ],
   buttons: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][2]}',
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {
         taskJobFormSave();
      }
   }],
   loadData : function(rec) {
      taskJobForm.record = rec;
      taskJobForm.getForm().loadRecord(rec);
   }
});

function taskJobFormSave() {
   if (taskJobForm.record == null) {
      Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
      return;
   }
   if (!taskJobForm.getForm().isValid()) {
      Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
      return false;
   }

   taskJobForm.getForm().updateRecord(taskJobForm.record);
   taskJobForm.getForm().submit({
      url : '../ajax/task_job.save.php?task_id={$id}',
      waitMsg: '{$LANG['plugin_fusinvdeploy']['form']['message'][2]}',
      success: function(fileForm, o){
         taskJobStore.reload({
            callback: function() {
               taskJobGrid.getSelectionModel().selectRow(index);
            }
         });
      },
      failure: function(fileForm, action){
         switch (action.failureType) {
            case Ext.form.Action.CLIENT_INVALID:
               Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid values');
               break;
            case Ext.form.Action.CONNECT_FAILURE:
               Ext.Msg.alert('Failure', 'Ajax communication failed');
               break;
            case Ext.form.Action.SERVER_INVALID:
               Ext.Msg.alert('Failure', action.result.msg);
         }
      }
   });
}


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
