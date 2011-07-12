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
$height_right                 = 350;

$width_left                   = 340;
$height_left                  = 350;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$label_width = 140;

$field_width = 170;

$task_methods = PluginFusinvdeployStaticmisc::task_methods();
$JS_method = "var methods = new Array();";
foreach($task_methods as $method) {
   $JS_method.= "methods['".$method['method']."']= \"".$method['name']."\";";
}

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
   header: '{$LANG['plugin_fusinvdeploy']['task'][6]}',
   renderer: renderGroup,
   width:150
}, {
   id: 'package_id',
   header: '{$LANG['plugin_fusinvdeploy']['package'][7]}',
   dataIndex: 'package_id',
   renderer: renderPackage,
   groupable: false
}, {
   id: 'method',
   header: '{$LANG['plugin_fusioninventory']['task'][26]}',
   dataIndex: 'method',
   renderer: renderMethod,
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
}, {
   id: 'comment',
   dataIndex: 'comment',
   hidden: true
}, {
   id: 'modified',
   dataIndex: 'modified',
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
function renderMethod(val) {
   return methods[val];
}

//create store and load data
var taskJobGridReader = new Ext.data.JsonReader({
   root: 'tasks',
   fields: [
      'group_id', 'package_id', 'method', 'retry_nb',
      'retry_time', 'periodicity_count', 'periodicity_type',
      'comment'
   ]
});

var taskJobGridWriter = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: true,
    listful: true
});

var taskJobGridProxy = new Ext.data.HttpProxy({
   method: 'POST',
   api: {
      read:     '../ajax/task_job.data.php?tasks_id={$id}',
      create:   '../ajax/task_job.save.php?tasks_id={$id}',
      update:   '../ajax/task_job.save.php?tasks_id={$id}',
      destroy:  '../ajax/task_job.save.php?tasks_id={$id}'
   }
});

var taskJobStore = new Ext.data.GroupingStore({
   reader: taskJobGridReader,
   writer: taskJobGridWriter,
   proxy: taskJobGridProxy,
   autoSave: false,
   sortInfo: {field: 'group_id', direction: "ASC"},
   groupField : 'group_id',
   listeners: {
      save: function(store, batch, data) {
         store.reload();
      }
   }
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
   title: '{$LANG['plugin_fusinvdeploy']['task'][13]}',
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text} ({[values.rs.length]})',
      forceFit : true,
      hideGroupedColumn: true,
      showGroupName: false
   }),
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][10]}',
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new taskJobStore.recordType({
            group_id:            '',
            package_id:          '',
            retry_nb:            0,
            retry_time:          0,
            periodicity_count:   1,
            periodicity_type:    'minutes'
         });
         taskJobStore.insert(0,u);
         taskJobGrid.getSelectionModel().selectFirstRow();
         taskJobForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][10]}');
      }
   }, '-', {
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][11]}',
      iconCls: 'exticon-delete',
      handler: function(btn,ev) {
         var selection = taskJobGrid.getSelectionModel().getSelections();
         if (!selection) return false;

         for(var i = 0, r; r = selection[i]; i++){
            taskJobStore.remove(r);
         }

         taskJobStore.save();

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
            taskJobForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][12]}');
            taskJobForm.expand();
            taskJobForm.buttons[0].setDisabled(false);
         }
      }
   }),
   listeners: {
      viewready: function(component) {
         taskJobStore.load.defer(200, taskJobStore);
         //taskJobStore.load();
      }
   }
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
   title: '{$LANG['plugin_fusinvdeploy']['task'][11]}',
   items: [
      new Ext.form.ComboBox({
         fieldLabel: '{$LANG['plugin_fusioninventory']['task'][26]}',
         name: 'method',
         hiddenName: 'method',
         valueField: 'value',
         displayField: 'name',
         allowBlank: false,
         width: {$field_width},
         store: new Ext.data.ArrayStore({
            fields: ['value', 'name'],
            data: [
               ['{$task_methods[0]['method']}', "{$task_methods[0]['name']}"],
               ['{$task_methods[1]['method']}', "{$task_methods[1]['name']}"]
            ]
         }),
         mode: 'local',
         triggerAction: 'all'
      }),
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
         fieldLabel: '{$LANG['common'][25]}',
         xtype: 'textarea',
         name: 'comment',
         hiddenName: 'comment',
         width: {$field_width}
      }, {
         xtype:'fieldset',
         title: '{$LANG['plugin_fusinvdeploy']['task'][14]}',
         collapsed: true,
         checkboxToggle:true,
         autoHeight:true,
         items :[/*{
            fieldLabel:'{$LANG['plugin_fusioninventory']['task'][31]}',
            layout: 'column',
            items: [
               new Ext.ux.form.SpinnerField({
                  name: 'periodicity_count',
                  hiddenName: 'periodicity_count',
                  allowBlank: false,
                  width: 60
               }),
               new Ext.form.ComboBox({
                  name: 'periodicity_type',
                  valueField: 'name',
                  displayField: 'value',
                  hiddenName: 'periodicity_type',
                  allowBlank: false,
                  width: 90,
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
               })
            ]
         },*/ new Ext.ux.form.SpinnerField({
            fieldLabel: "{$LANG['plugin_fusioninventory']['task'][24]}",
            name: 'retry_nb',
            hiddenName: 'retry_nb',
            allowBlank: false,
            width:50
         }), new Ext.ux.form.SpinnerField({
            fieldLabel: '{$LANG['plugin_fusioninventory']['task'][25]}',
            name: 'retry_time',
            hiddenName: 'retry_time',
            allowBlank: false,
            width:50
         })
      ]}
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

   //mark all row a edited
   taskJobGrid.getStore().getRange().forEach(function(r){
      r.set('modified', 'true');
   });

   taskJobStore.save();
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
echo $JS_method;
echo $JS;
echo "</script>";

?>
