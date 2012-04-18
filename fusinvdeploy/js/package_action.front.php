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

// Size of div/form/label...
$width_left                  = 590;
$height_left                 = 260;

$width_right                   = 340;
$height_right                  = 260;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$column_width = array(30,180,370,1,1,1,1,1,1,1,1,1,1);

$label_width = 75;

$field_width = 215;
$field_height = 40;
// END - Size of div/form/label...

// Render div
if(isset($_POST["glpi_tab"])) {
   switch($_POST["glpi_tab"]){
      case 2 :
         $render = "install";
         $title2 = $LANG['plugin_fusinvdeploy']['form']['title'][15];
         break;
      case 3 :
         $render = "uninstall";
         $title2 = $LANG['plugin_fusinvdeploy']['form']['title'][16];
         break;
   }
}
// END - Render div

$JS = <<<JS

//define colums for grid
var {$render}actionColumns =  [{
   id: '{$render}id',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][10]}',
   width: {$column_width[0]},
   dataIndex: '{$render}id',
   hidden: true
}, {
   id: '{$render}itemtype',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
   width: {$column_width[1]},
   dataIndex: '{$render}itemtype',
   renderer: {$render}renderType
}, {
   id: '{$render}value',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][2]}',
   width: {$column_width[2]},
   dataIndex: '{$render}value'
}, {
   id: '{$render}exec',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][11]}',
   width: {$column_width[3]},
   dataIndex: '{$render}exec'
}, {
   id: '{$render}from',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][16]}',
   width: {$column_width[4]},
   dataIndex: '{$render}from'
}, {
   id: '{$render}to',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][17]}',
   width: {$column_width[5]},
   dataIndex: '{$render}to'
}, {
   id: '{$render}path',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['files'][1]}',
   width: {$column_width[6]},
   dataIndex: '{$render}path'
}, {
   id: '{$render}messagename',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][1]}',
   width: {$column_width[10]},
   dataIndex: '{$render}messagename'
}, {
   id: '{$render}messagevalue',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][2]}',
   width: {$column_width[11]},
   dataIndex: '{$render}messagevalue'
}, {
   id: '{$render}messagetype',
   hidden:true,
   header: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][3]}',
   width: {$column_width[12]},
   dataIndex: '{$render}messagetype'
}, {
   id: '{$render}ranking',
   hidden:true,
   dataIndex: '{$render}ranking'
}];

//define renderer for grid columns
function {$render}renderType(val) {
   switch(val) {
      case 'PluginFusinvdeployAction_Command':
         return '{$LANG['plugin_fusinvdeploy']['package'][1]}';
      case 'PluginFusinvdeployAction_Move':
         return '{$LANG['plugin_fusinvdeploy']['package'][18]}';
      case 'PluginFusinvdeployAction_Copy':
         return '{$LANG['plugin_fusinvdeploy']['package'][28]}';
      case 'PluginFusinvdeployAction_Delete':
         return '{$LANG['plugin_fusinvdeploy']['package'][20]}';
      case 'PluginFusinvdeployAction_Mkdir':
         return '{$LANG['plugin_fusinvdeploy']['package'][27]}';
      case 'PluginFusinvdeployAction_Message':
         return '{$LANG['plugin_fusinvdeploy']['package'][21]}';
      default:
         return '';
   }
}

//create store and load data
var {$render}actionGridReader = new Ext.data.JsonReader({
   root: '{$render}actions',
   fields: ['{$render}id', '{$render}itemtype', '{$render}value', '{$render}from', '{$render}to',
            '{$render}exec', '{$render}path', '{$render}messagename', '{$render}messagevalue',
            '{$render}messagetype', '{$render}ranking']
});

var {$render}actionGridStore = new Ext.data.Store({
   url: '../ajax/package_action.data.php?package_id={$id}&render={$render}',
   autoLoad: true,
   reader: {$render}actionGridReader,
   sortInfo: {field: '{$render}ranking', direction: "ASC"}
});

//define grid
var {$render}actionGrid = new Ext.grid.GridPanel({
   disabled: {$disabled},
   region: 'center',
   margins: '0 0 0 5',
   store: {$render}actionGridStore,
   columns: {$render}actionColumns,
   stripeRows: false,
   height: {$height_left},
   width: {$width_left},
   style:'margin-bottom:5px',
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][8]} ({$title2})',
   stateId: '{$render}actionGrid',
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][6]}',
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new {$render}actionGridStore.recordType({
            {$render}id             : '',
            {$render}itemtype       : '',
            {$render}from           : '',
            {$render}to             : '',
            {$render}path           : '',
            {$render}exec           : '',
            {$render}messagename    : '',
            {$render}messagevalue   : '',
            {$render}messagetype    : ''
         });
         {$render}actionGridStore.insert(0,u);
         {$render}actionGrid.getSelectionModel().selectFirstRow();
         {$render}actionGrid.setDisabled(true);
         {$render}actionForm.buttons[1].setVisible(true);
         {$render}actionForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][6]}');
      }
   }, '-', {
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][14]}',
      iconCls: 'exticon-delete',
      handler: function(btn,ev) {
         var selection = {$render}actionGrid.getSelectionModel().getSelections();
         if (!selection) {
            return false;
         }
         for(var i = 0, r; r = selection[i]; i++){
            {$render}actionGridStore.remove(r);
         }
         {$render}actionGrid.getSelectionModel().selectFirstRow();
         Ext.Ajax.request({
            url: '../ajax/package_action.delete.php?render={$render}',
            params: { {$render}id: selection[0].data.{$render}id }
         });
         if({$render}actionGridStore.data.length == 0) {
            {$render}actionForm.buttons[0].setDisabled(true);

            {$render}actionForm.hide();
            {$render}actionForm.collapse();
         } else {
            {$render}actionGrid.getSelectionModel().selectFirstRow();
         }
      }
   }, '-'],
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g,index,ev) {
            if (!{$disabled}) {
               var rec = {$render}actionGrid.store.getAt(index);
               {$render}actionForm.loadData(rec);
               {$render}actionForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][7]}');
               {$render}actionForm.expand();
               {$render}actionForm.buttons[0].setDisabled(false);

               {$render}actionForm.enable();
               {$render}actionForm.show();
            }
         }
      }
   }),
   enableDragDrop: true,
   plugins: [
      new Ext.ux.dd.GridDragDropRowOrder({
         scrollable: true,
         copy: false,
         listeners: {
            afterrowmove: function (objThis, oldIndex, newIndex, records) {
               var id = records[0].data.{$render}id;

               //save reorder
               Ext.Ajax.request({
                  url : '../ajax/package_action.reorder.php',
                  params : {
                     id: id,
                     old_ranking: oldIndex,
                     new_ranking: newIndex,
                     package_id: '{$id}',
                     render: '{$render}'
                  },
                  method: 'GET'
               });
            }
         }
      })
   ],
   ddGroup: '{$render}actionGridDD'
});




/**** NEW RET CHECKS ****/
var {$render}actionGridReaderRetChecks = {
   root: '{$render}retChecks',
   fields: [
      '{$render}CommandId',
      'id',
      'type',
      'value'
   ]
};

var {$render}actionGridWriterRetChecks = {
    encode: false,
    writeAllFields: true
};

var {$render}actionGridProxyRetChecks = {
   api: {
      read    : '../ajax/package_action.retChecks.data.php?render={$render}',
      create  : '../ajax/package_action.retChecks.create.php?render={$render}',
      update  : '../ajax/package_action.retChecks.update.php?render={$render}',
      destroy : '../ajax/package_action.retChecks.destroy.php?render={$render}'
   }
};





{$render}ActionStoreConfigRetChecks = Ext.extend( Ext.data.Store, {
   reader: new Ext.data.JsonReader({$render}actionGridReaderRetChecks),
   writer: new Ext.data.JsonWriter({$render}actionGridWriterRetChecks),
   proxy: new Ext.data.HttpProxy({$render}actionGridProxyRetChecks),
   autoSave: true,
   sortInfo: {field: 'id', direction: "ASC"},
   initComponent: function( config ) {
      Ext.apply( this, Ext.apply(this.initialConfig, config) );
      {$render}ActionStoreConfigRetChecks.superclass.initComponent.call( this, config );
   }
});

{$render}actionGridRetChecksConfig = Ext.extend( Ext.grid.EditorGridPanel, {
   width: 295,
   height: 120,
   /*title: '{$LANG['plugin_fusinvdeploy']['package'][22]}',*/
   /*style : 'margin:10px 0 0',*/
   initComponent: function( config ) {
      Ext.apply( this, {
         store: new {$render}ActionStoreConfigRetChecks({}),
         cm: new Ext.grid.ColumnModel({
            columns: [{
               dataIndex: '{$render}CommandId',
               hidden: true
            }, {
               dataIndex: 'id',
               hidden: true
            }, {
               header: '{$LANG['plugin_fusinvdeploy']['form']['command_status'][1]}',
               dataIndex: 'type',
               width: 180,
               editor: new Ext.form.ComboBox({
                  triggerAction: 'all',
                  name: 'type',
                  hiddenName: 'type',
                  valueField: 'name',
                  displayField: 'value',
                  allowBlank: false,
                  mode: 'local',
                  store: new Ext.data.ArrayStore({
                     fields: ['name', 'value'],
                     data: [
                        ['RETURNCODE_OK', '{$LANG['plugin_fusinvdeploy']['form']['command_status'][3]}'],
                        ['RETURNCODE_KO', '{$LANG['plugin_fusinvdeploy']['form']['command_status'][4]}'],
                        ['REGEX_OK',      '{$LANG['plugin_fusinvdeploy']['form']['command_status'][5]}'],
                        ['REGEX_KO',      '{$LANG['plugin_fusinvdeploy']['form']['command_status'][6]}']
                     ]
                  })
               })
            }, {
               header: '{$LANG['plugin_fusinvdeploy']['form']['command_status'][2]}',
               dataIndex: 'value',
               allowBlank: false,
               width: 100,
               editor: new Ext.form.TextField()
            }]
         }),
         sm: new Ext.grid.RowSelectionModel({
            singleSelect: true,
            moveEditorOnEnter: false
         }),
         tbar: new Ext.Toolbar({
            items: [{
               text: '{$LANG['plugin_fusinvdeploy']['form']['action'][6]}',
               iconCls: 'exticon-add',
               handler: function(btn,ev) {
                  var u = new {$render}ActionGridRetChecks.store.recordType({
                     {$render}CommandId : {$render}actionForm.getForm().findField('{$render}id').getValue(),
                     id : '',
                     type : '',
                     value: ''
                  });
                  {$render}ActionGridRetChecks.store.insert(0,u);
                  {$render}ActionGridRetChecks.getSelectionModel().selectFirstRow();
               }
            }, '-', {
               text: '{$LANG['plugin_fusinvdeploy']['form']['action'][7]}',
               iconCls: 'exticon-delete',
               handler: function(btn,ev) {
                  var selection = {$render}ActionGridRetChecks.getSelectionModel().getSelections();
                  if (!selection) {
                     return false;
                  }
                  for(var i = 0, r; r = selection[i]; i++){
                     {$render}ActionGridRetChecks.store.remove(r);
                  }
                  {$render}ActionGridRetChecks.getSelectionModel().selectFirstRow();
               }
            }]
         })
      });
      {$render}actionGridRetChecksConfig.superclass.initComponent.call( this, config );
   }
});

/**** DEFINE DYNAMIC FIELDSETS ****/
var {$render}Command_fieldset_item_default = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][11]}',
      name: '{$render}exec',
      xtype:  'textarea',
      width: {$field_width},
      height : {$field_height}
   }
];



var {$render}Command_fieldset_item_PluginFusinvdeployAction_Command = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][11]}',
      name: '{$render}exec',
      xtype:  'textarea',
      width: {$field_width},
      height : {$field_height}
   }
];

var {$render}Command_fieldset_item_PluginFusinvdeployAction_Move = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][16]}',
      name: '{$render}from',
      xtype: 'textfield'
   } , {
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][17]}',
      name: '{$render}to',
      xtype: 'textfield'
   }
];

var {$render}Command_fieldset_item_PluginFusinvdeployAction_Copy = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][16]}',
      name: '{$render}from',
      xtype: 'textfield'
   } , {
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][17]}',
      name: '{$render}to',
      xtype: 'textfield'
   }
];

var {$render}Command_fieldset_item_PluginFusinvdeployAction_Delete = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
      name: '{$render}path',
      xtype: 'textfield'
   }
];

var {$render}Command_fieldset_item_PluginFusinvdeployAction_Mkdir = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][1]}',
      name: '{$render}path',
      xtype: 'textfield'
   }
];

var {$render}Command_fieldset_item_PluginFusinvdeployAction_Message = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][1]}',
      name: '{$render}messagename',
      xtype: 'textfield'
   } , {
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][2]}',
      name: '{$render}messagevalue',
      xtype:  'textarea',
      width: {$field_width},
      height : {$field_height}
   } , {
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['action_message'][3]}',
      name: '{$render}messagetype',
      hiddenName : '{$render}messagetype',
      xtype : 'combo',
      valueField: 'name',
      displayField: 'value',
      width: 215,
      emptyText : '{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['INFO',       '{$LANG['plugin_fusinvdeploy']['form']['action_message'][4]}'],
            ['POSTPONE',   '{$LANG['plugin_fusinvdeploy']['form']['action_message'][5]}']
         ]
      }),
      mode: 'local',
      triggerAction: 'all'
   }
];

var {$render}Commands_values = new Ext.data.Store({
   url: '../ajax/package_action_command.data.php?package_id={$id}&render={$render}',
   autoLoad: true,
   reader: new Ext.data.JsonReader({
      fields: ['{$render}commands_id', '{$render}commands_name'],
      root: '{$render}commands'
   })
});

var {$render}Command_dynFieldset =  new Ext.form.FieldSet({
   xtype: 'fieldset',
   width: {$width_left_fieldset},
   style: 'margin:0;padding:0',
   border: false,
   items: {$render}Command_fieldset_item_default
});


/**** DEFINE FUNCTIONS FOR REFRESH FIEDLSET AND RET CHECK GRID ****/
function {$render}Command_refreshDynFieldset(val) {
   {$render}Command_dynFieldset.removeAll();
   {$render}actionForm.remove({$render}ActionGridRetChecks);

   switch(val) {
      case 'PluginFusinvdeployAction_Command':
         /*var form = {$render}actionForm.getForm().getValues();
         console.log(form.installid.length);
         if (form.installid.length == 0) {
            Ext.Ajax.request({
               url : '../ajax/package_action.save.php?package_id={$id}&render={$render}',
               method: 'POST',
               params : {
                  itemtype : 'PluginFusinvdeployAction_Command',
                  exec : '',
                  id : '',
               },
               success: function ( result, request ) {
                  console.log(Ext.util.JSON.decode(result.responseText).newId);
               }
            });
         }*/
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Command);
         break;
      case 'PluginFusinvdeployAction_Move':
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Move);
         break;
      case 'PluginFusinvdeployAction_Copy':
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Copy);
         break;
      case 'PluginFusinvdeployAction_Delete':
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Delete);
         break;
      case 'PluginFusinvdeployAction_Mkdir':
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Mkdir);
         break;
      /*case 'PluginFusinvdeployAction_Message':
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_PluginFusinvdeployAction_Message);*/
         break;
      default:
         {$render}Command_dynFieldset.add({$render}Command_fieldset_item_default);
         break;
   }
   {$render}Command_dynFieldset.doLayout();
}

var {$render}ActionGridRetChecks;
function refreshRetChecks() {
   if ({$render}ActionGridRetChecks != undefined) {

      {$render}actionForm.remove({$render}ActionGridRetChecks);
      {$render}ActionGridRetChecks = '';
   }

   var commandId = {$render}actionForm.getForm().findField('{$render}id').getValue();
   if (commandId != '') {
      {$render}ActionGridRetChecks = new {$render}actionGridRetChecksConfig;
      //{$render}ActionGridRetChecks.store = new Ext.data.Store({$render}ActionStoreConfigRetChecks);
      {$render}actionForm.add({$render}ActionGridRetChecks);

      {$render}ActionGridRetChecks.store.proxy.setApi(
         'read', '../ajax/package_action.retChecks.data.php?render={$render}&CommandId='+commandId
      );
      {$render}ActionGridRetChecks.store.reload();
      {$render}actionForm.doLayout();
   }
}

/**** DEFINE GENERAL FORM ****/
function {$render}actionFormSave() {
   if ({$render}actionForm.record == null) {
      Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
      return;
   }
   if (!{$render}actionForm.getForm().isValid()) {
      Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
      return false;
   }

   var action_id = {$render}actionForm.record.data.{$render}id;

   {$render}actionForm.getForm().updateRecord({$render}actionForm.record);
   {$render}actionForm.getForm().submit({
      url : '../ajax/package_action.save.php?package_id={$id}&render={$render}',
      waitMsg: '{$LANG['plugin_fusinvdeploy']['form']['message'][2]}',
      success: function(fileForm, o){
         {$render}actionGridStore.reload({
            callback: function() {
               var index = {$render}actionGrid.store.findExact('{$render}id', action_id);
               if (index != -1) {$render}actionGrid.getSelectionModel().selectRow(index);
               else {
                  var mystoreItems = {$render}actionGrid.store.data.items;
                  var indexes = new Array(mystoreItems.length);
                  for (var i = 0; i <= mystoreItems.length-1; i++) {
                     indexes[i] = mystoreItems[i].id;
                  }
                  indexes.sort();
                  var row_id = indexes[mystoreItems.length-1];
                  var record = {$render}actionGrid.store.getById(row_id);
                  {$render}actionGrid.getSelectionModel().selectRecords([record]);
               }
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

var {$render}actionForm = new Ext.FormPanel({
   disabled: true,
   hidden: true,
   region: 'east',
   collapsible: true,
   collapsed: true,
   labelWidth: {$label_width},
   frame: true,
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][7]}',
   bodyStyle:'padding:5px 10px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_right},
   height: {$height_right},
   items: [{
      name: '{$render}id',
      xtype: 'hidden'
   },
   new Ext.form.ComboBox({
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
      name: 'type_name',
      valueField: 'name',
      width: {$field_width},
      displayField: 'value',
      allowBlank: false,
      hiddenName: '{$render}itemtype',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['PluginFusinvdeployAction_Command', '{$LANG['plugin_fusinvdeploy']['package'][1]}'],
            ['PluginFusinvdeployAction_Move',    '{$LANG['plugin_fusinvdeploy']['package'][18]}'],
            ['PluginFusinvdeployAction_Copy',    '{$LANG['plugin_fusinvdeploy']['package'][28]}'],
            ['PluginFusinvdeployAction_Delete',  '{$LANG['plugin_fusinvdeploy']['package'][20]}'],
            ['PluginFusinvdeployAction_Mkdir',   '{$LANG['plugin_fusinvdeploy']['package'][27]}']/*,
            ['PluginFusinvdeployAction_Message', '{$LANG['plugin_fusinvdeploy']['package'][21]}']*/
         ]
      }),
      mode: 'local',
      triggerAction: 'all',
      listeners: {select:{fn:function(){
         {$render}Command_refreshDynFieldset({$render}actionForm.getForm().findField('{$render}itemtype').value);
         {$render}actionFormSave();
      }}}
   }),
   {$render}Command_dynFieldset
   ],
   buttons: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][2]}',
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {
         {$render}actionFormSave();
         {$render}actionGrid.setDisabled(false);
         {$render}actionForm.buttons[1].setVisible(false);
      }
   }, {
      text: '{$LANG['buttons'][34]}',
      iconCls: 'exticon-cancel',
      name : '{$render}cancelbtn',
      id : '{$render}Actioncancelbtn',
      iconCls: 'exticon-cancel',
      hidden : true,
      handler: function(btn, ev) {
         btn.setVisible(false);
         {$render}actionGrid.setDisabled(false);

         var selection = {$render}actionGrid.getSelectionModel().getSelected();
         if (!selection) {
             return false;
         }
         if (selection !== undefined) {
            {$render}actionGrid.store.remove(selection);
         }
         {$render}actionGrid.store.reload();

         if({$render}actionGridStore.data.length == 0) {
            {$render}actionForm.hide();
            {$render}actionForm.collapse();
         }

      }
   }],
   loadData : function(rec) {
      {$render}actionForm.record = rec;
      {$render}Command_refreshDynFieldset(rec.data.{$render}itemtype);
      {$render}actionForm.getForm().loadRecord(rec);

      if (rec.data.{$render}itemtype == 'PluginFusinvdeployAction_Command') {
         refreshRetChecks()
      }
   }
});

//render grid and form in a border layout
var {$render}ActionLayout = new Ext.Panel({
   layout: 'border',
   renderTo: '{$render}Action',
   height: {$height_layout},
   width: {$width_layout},
   defaults: {
      split: true
   },
   items:[
      {$render}actionForm,
      {$render}actionGrid
   ]
 });


//grid store loading events
{$render}actionGridStore.on({
   'load':{
      fn: function(store, records, options) {
         //select first row
         {$render}actionGrid.getSelectionModel().selectFirstRow();
      },
      scope:this
   },
   'loadexception':{
      fn: function(obj, options, response, e){
         //disable form
         //{$render}actionForm.setDisabled(true);
      },
      scope:this
   }
});
JS;


echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>