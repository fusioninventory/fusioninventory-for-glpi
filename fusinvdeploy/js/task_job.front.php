<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Alexandre Delaunay
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

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

$JS_method = "var methods = new Array();".
   "methods['deployinstall']= \"".$LANG['plugin_fusinvdeploy']['package'][16]."\";".
   "methods['deployuninstall']= \"".$LANG['plugin_fusinvdeploy']['package'][17]."\";";

$JS = <<<JS

/**** DEFINE STORES AND JSON READER FOR FORM ****/

var packageReader = new Ext.data.JsonReader({
   root: 'packages',
   totalProperty: 'results',
   fields: ['package_id', 'package_name']
});

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
   id: 'package_id',
   header: "{$LANG['plugin_fusinvdeploy']['package'][7]}",
   dataIndex: 'package_id',
   renderer: renderPackage,
   groupable: false
}, {
   id: 'method',
   header: "{$LANG['plugin_fusioninventory']['task'][26]}",
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
   id: 'comment',
   dataIndex: 'comment',
   hidden: true
}, {
   id: 'action_type',
   dataIndex: 'action_type',
   hidden: true
}, {
   id: 'action_name',
   dataIndex: 'action_name',
   hidden: true
}, {
   id: 'action_selection',
   dataIndex: 'action_selection',
   groupRenderer: renderActionSelection,
   renderer: renderActionSelection,
   header: 'group'
}, {
   id: 'modified',
   dataIndex: 'modified',
   hidden: true
}];

//define renderer for grid columns
function renderActionSelection(val, metaData, record) {
  // return val;
   switch (record.data.action_type) {
      case 'Computer':
         var img = '<img src="../pics/ext/computer.png">&nbsp;';
         break;
      case 'PluginFusinvdeployGroup':
         var img = '<img src="../pics/ext/group.png">&nbsp;';
         break;
      case 'Group':
         var img = '<img src="../pics/ext/group_user">&nbsp;';
         break;
      default:
         var img = '';
   }
   //console.log(img+record.get('action_name'));
   return img+record.get('action_name');
}
function renderPackage(val) {
   var img = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../pics/ext/package.png">&nbsp;';
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
      'package_id', 'method', 'retry_nb',
      'retry_time', 'comment', 'action_type',
      'action_selection', 'action_name'
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
   autoSave: true,
   pruneModifiedRecords: true,
   sortInfo: {field: 'action_selection', direction: "ASC"},
   groupField : 'action_selection',
   listeners: {
      save: function(store, batch, data) {
         taskJobStore.reload();
      }
   }
});

var action_typeStore = new Ext.data.Store({
   url: '../ajax/task_job_actions.data.php',
   baseParams: {
      'get': 'type'
   },
   reader: new Ext.data.JsonReader({
      root: 'action_types',
      totalProperty: 'results',
      fields: ['name', 'value']
   })
});
action_typeStore.load();

/**** DEFINE GRID ****/
var taskJobGrid = new Ext.grid.GridPanel({
   disabled: {$disabled},
   region: 'center',
   stripeRows: true,
   height: {$height_left},
   width: {$width_left},
   style: 'margin-bottom:5px',
   columns: taskJobColumns,
   store: taskJobStore,
   title: "{$LANG['plugin_fusinvdeploy']['task'][13]}",
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '<b>{text}</b>',
      hideGroupedColumn: true,
      showGroupName: false,
      emptyText: '',
      emptyGroupText: ''
   }),
   tbar: [{
      text: "{$LANG['plugin_fusinvdeploy']['task'][15]}",
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new taskJobStore.recordType({
            package_id:'',
            retry_nb:3,
            retry_time:0,
            action_type:'',
            action_selection:'',
            comment: '',
            action_name : '',
            method: ''
         });
         taskJobStore.insert(0,u);
         taskJobGrid.getSelectionModel().selectFirstRow();
         taskJobForm.setTitle("{$LANG['plugin_fusinvdeploy']['task'][15]}");
      }
   }, '-', {
      text: "{$LANG['plugin_fusinvdeploy']['task'][16]}",
      iconCls: 'exticon-delete',
      handler: function(btn,ev) {
         var selection = taskJobGrid.getSelectionModel().getSelections();
         if (!selection) return false;

         for(var i = 0, r; r = selection[i]; i++){
            taskJobGrid.store.remove(r);
         }

         //mark all row a edited
         taskJobGrid.getStore().getRange().forEach(function(r){
            r.set('modified', 'true');
         });

         taskJobStore.save();

         if(taskJobStore.data.length == 0) {
            Ext.Ajax.request({
               url : '../ajax/task_job.save.php?tasks_id={$id}' ,
               params : { tasks : '' }
            });

            taskJobForm.hide();
            taskJobForm.collapse();
            taskJobForm.disable();

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
            taskJobForm.setTitle("{$LANG['plugin_fusinvdeploy']['task'][17]}");
            taskJobForm.buttons[0].setDisabled(false);

            taskJobForm.expand();
            taskJobForm.show();
            taskJobForm.enable();
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
   disabled: true,
   hidden: true,
   region: 'east',
   collapsible: true,
   collapsed: true,
   labelWidth: {$label_width},
   bodyStyle:'padding:5px 10px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_right},
   title: "{$LANG['plugin_fusinvdeploy']['task'][11]}",
   items: [
      new Ext.form.ComboBox({
         fieldLabel: "{$LANG['plugin_fusioninventory']['task'][26]}",
         name: 'method',
         hiddenName: 'method',
         valueField: 'value',
         displayField: 'name',
         allowBlank: false,
         width: {$field_width},
         store: new Ext.data.ArrayStore({
            fields: ['value', 'name'],
            data: [
               ['deployinstall', "{$LANG['plugin_fusinvdeploy']['package'][14]}"],
               ['deployuninstall', "{$LANG['plugin_fusinvdeploy']['package'][15]}"]
            ]
         }),
         mode: 'local',
         triggerAction: 'all'
      }),
      new Ext.form.ComboBox({
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['package'][7]}",
         name: 'package_id',
         valueField: 'package_id',
         displayField: 'package_name',
         hiddenName: 'package_id',
         allowBlank: false,
         triggerAction: 'all',
         style: 'margin-bottom:10px',
         store: packageStore,
         width: {$field_width}
      }),
      new Ext.form.ComboBox({
         id: 'action_type',
         fieldLabel: 'type',
         name: 'action_type',
         valueField: 'value',
         displayField: 'name',
         hiddenName: 'action_type',
         allowBlank: false,
         triggerAction: 'all',
         store: action_typeStore,
         width: {$field_width},
         listeners: {
            select: function(combo, record){
               loadActionSelection();
            },
            show: function(component) {
               component.getStore().load();
            }
         }
      }),
      new Ext.form.ComboBox({
         id: 'action_selection',
         fieldLabel: 'selection',
         name: 'action_selection',
         valueField: 'id',
         displayField: 'name',
         hiddenName: 'action_selection',
         allowBlank: false,
         triggerAction: 'all',
         editable: true,
         forceSelection: false,
         style: 'margin-bottom:5px',
         store: new Ext.data.Store({
            url: '../ajax/task_job_actions.data.php',
            baseParams: {
               'get': 'selection'
            },
            reader: new Ext.data.JsonReader({
               root: 'action_selections',
               totalProperty: 'results',
               fields: ['id', 'name']
            })
         }),
         width: {$field_width}
      }), {
         fieldLabel: "{$LANG['common'][25]}",
         xtype: 'textarea',
         name: 'comment',
         hiddenName: 'comment',
         width: {$field_width},
         height:45
      }, {
         xtype:'fieldset',
         title: "{$LANG['plugin_fusinvdeploy']['task'][14]}",
         collapsed: true,
         checkboxToggle:true,
         autoHeight:true,
         style:'margin-top:5px',
         items :[
            new Ext.ux.form.SpinnerField({
               fieldLabel: "{$LANG['plugin_fusioninventory']['task'][24]}",
               name: 'retry_nb',
               hiddenName: 'retry_nb',
               allowBlank: false,
               width:50
            }), new Ext.ux.form.SpinnerField({
               fieldLabel: "{$LANG['plugin_fusioninventory']['task'][25]}",
               name: 'retry_time',
               hiddenName: 'retry_time',
               allowBlank: false,
               width:50
            })
         ]
      }
   ],
   buttons: [{
      text: "{$LANG['plugin_fusinvdeploy']['action'][2]}",
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {
         taskJobFormSave();
      }
   }],
   loadData : function(rec) {
      taskJobForm.record = rec;
      taskJobForm.getForm().loadRecord(rec);
      loadActionSelection();
   }
});

function taskJobFormSave() {
   if (taskJobForm.record == null) {
      Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
      return;
   }
   if (!taskJobForm.getForm().isValid()) {
      Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
      return false;
   }

   taskJobForm.getForm().updateRecord(taskJobForm.record);

   //mark all row a edited
   taskJobGrid.getStore().getRange().forEach(function(r){
      r.set('modified', 'true');
   });

   taskJobStore.save();
}

//
function loadActionSelection () {
   var action_selection = Ext.ComponentMgr.get('action_selection');
   var action_selectionValue = action_selection.getValue();
   var action_type = Ext.ComponentMgr.get('action_type');

   var action_selectionStore = action_selection.getStore();
   action_selectionStore.setBaseParam('type', action_type.getValue());
   action_selectionStore.removeAll();

   //reload store and set value on combobox
   action_selectionStore.on("load", function() {
      action_selection.setValue(action_selectionValue);
   });
   action_selectionStore.reload();

   // force the reload on trigger
   action_selection.lastQuery = null;

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