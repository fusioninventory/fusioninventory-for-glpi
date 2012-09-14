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

//get const check class for use in heredoc
$refl = new ReflectionClass('PluginFusinvdeployCheck');
$chkConst = $refl->getConstants();

// Size of div/form/label...
$width_left                  = 590;
$height_left                 = 250;

$width_right                   = 340;
$height_right                  = 250;
$width_right_fieldset          = $width_right-19;
$width_right_fieldset_default  = $width_right-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$column_width = array(30,180,210,95,50);

$label_width = 95;
// END - Size of div/form/label...

// Render div

if(isset($_POST["glpi_tab"])) {
   if (strpos($_POST["glpi_tab"], 'PluginFusinvdeployInstall') !== false) {
      $render = "install";
      $title2 = $LANG['plugin_fusinvdeploy']['ftitle'][17];
   } elseif (strpos($_POST["glpi_tab"], 'PluginFusinvdeployUninstall') !== false) {
      $render = "uninstall";
      $title2 = $LANG['plugin_fusinvdeploy']['ftitle'][18];
   }
}
// END - Render div

$JS = <<<JS

//define colums for grid
var {$render}checkColumns =  [{
   id: '{$render}id',
   header: "{$LANG['plugin_fusinvdeploy']['label'][10]}",
   width: {$column_width[0]},
   dataIndex: '{$render}id',
   hidden: true
}, {
   id: '{$render}type',
   header: "{$LANG['plugin_fusinvdeploy']['label'][0]}",
   width: {$column_width[1]},
   dataIndex: '{$render}type',
   renderer: {$render}renderType
}, {
   id: '{$render}path',
   header: "{$LANG['plugin_fusinvdeploy']['label'][1]}",
   width: {$column_width[2]},
   dataIndex: '{$render}path'
}, {
   id: '{$render}value',
   header: "{$LANG['plugin_fusinvdeploy']['label'][2]}",
   width: {$column_width[3]},
   dataIndex: '{$render}value',
   renderer: {$render}renderValue
}, {
   id: '{$render}ranking',
   dataIndex: '{$render}ranking',
   hidden: true
}];


//define renderer for grid columns
function {$render}renderType(val) {
   switch(val) {
      case "{$chkConst['WINKEY_EXISTS']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][0]}";
      case "{$chkConst['WINKEY_MISSING']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][1]}";
      case "{$chkConst['WINKEY_EQUAL']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][2]}";
      case "{$chkConst['FILE_EXISTS']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][3]}";
      case "{$chkConst['FILE_MISSING']}":
         return "{$LANG['plugin_fusinvdeploy']['label'][15]}";
      case "{$chkConst['FILE_SIZEGREATER']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][5]}";
      case "{$chkConst['FILE_SIZEEQUAL']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][8]}";
      case "{$chkConst['FILE_SIZELOWER']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][9]}";
      case "{$chkConst['FILE_SHA512']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][6]}";
      case "{$chkConst['FREE_SPACE']}":
         return "{$LANG['plugin_fusinvdeploy']['check'][7]}";
      default:
         return '';
   }
}

function {$render}renderValue(val, meta, record) {
   var unit = '';
   if (record.data.installunit) unit = record.data.installunit;
   return val+' '+unit;
}



//create store and load data
var {$render}checkGridReader = new Ext.data.JsonReader({
   root: '{$render}checks',
   fields: [
      { name: '{$render}id', type: 'integer' },
      { name: '{$render}type' },
      { name: '{$render}path' },
      { name: '{$render}value' },
      { name: '{$render}unit' },
      { name: '{$render}ranking' }
]
});

var {$render}checkGridStore = new Ext.data.Store({
   url: '../ajax/package_check.data.php?package_id={$id}&render={$render}',
   autoLoad: true,
   reader: {$render}checkGridReader,
   sortInfo: {field: '{$render}ranking', direction: "ASC"}
});

function printObject(o) {
  var out = '';
  for (var p in o) {
    out += p + ': ' + o[p] + '\\n';
  }
  alert(out);
}

//define grid
var {$render}checkGrid = new Ext.grid.GridPanel({
   disabled: {$disabled},
   region: 'center',
   margins: '0 0 0 5',
   store: {$render}checkGridStore,
   columns: {$render}checkColumns,
   stripeRows: true,
   height: {$height_left},
   width: {$width_left},
   style:'margin-bottom:5px',
   title: "{$LANG['plugin_fusinvdeploy']['ftitle'][2]} ({$title2})",
   stateId: '{$render}checkGrid',
   tbar: [{
      text: "{$LANG['plugin_fusinvdeploy']['ftitle'][1]}",
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new {$render}checkGridStore.recordType({
            {$render}id : '',
            {$render}type : '',
            {$render}path: '',
            {$render}value: ''
         });
         {$render}checkGridStore.insert(0,u);
         {$render}checkGrid.getSelectionModel().selectFirstRow();
         {$render}checkGrid.setDisabled(true);
         {$render}checkForm.buttons[1].setVisible(true);
         {$render}checkForm.setTitle("{$LANG['plugin_fusinvdeploy']['ftitle'][1]}");
      }
   }, '-', {
      text: "{$LANG['plugin_fusinvdeploy']['ftitle'][9]}",
      iconCls: 'exticon-delete',
      handler: function(btn,ev) {
         var selection = {$render}checkGrid.getSelectionModel().getSelections();
         if (!selection) {
            return false;
         }
         for(var i = 0, r; r = selection[i]; i++){
            {$render}checkGridStore.remove(r);
         }
         {$render}checkGrid.getSelectionModel().selectFirstRow();
         Ext.Ajax.request({
            url: '../ajax/package_check.delete.php?package_id={$id}&render={$render}',
            params: { {$render}id: selection[0].data.{$render}id }
         });
         if({$render}checkGridStore.data.length == 0) {
            {$render}checkForm.hide();
            {$render}checkForm.collapse();

            {$render}checkForm.buttons[0].setDisabled(true);

         } else {
            {$render}checkGrid.getSelectionModel().selectFirstRow();
         }
      }
   }, '-'],
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g,index,ev) {
            if (!{$disabled}) {
               var {$render}rec = {$render}checkGrid.store.getAt(index);
               {$render}checkForm.show();
               {$render}checkForm.enable();
               {$render}checkForm.loadData({$render}rec);
               {$render}checkForm.setTitle("{$LANG['plugin_fusinvdeploy']['ftitle'][0]}");
               {$render}checkForm.expand();
               {$render}checkForm.buttons[0].setDisabled(false);
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
                  url : '../ajax/package_check.reorder.php',
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
   ddGroup: '{$render}checkGridDD'
});



//define group item
var {$render}fieldset_item_default = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
      name: '{$render}path',
      allowBlank: false,
   }, {
      name: '{$render}value',
      xtype:'hidden'
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_FileSHA512 = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
      name: '{$render}path',
      allowBlank: false
   }, {
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][2]}",
      name: '{$render}value',
      xtype: 'textarea',
      allowBlank: false
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_FreespaceGreater = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][12]}",
      name: '{$render}path',
      allowBlank: false
   }, {
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][2]}",
      name: '{$render}value',
      allowBlank: false
   }, {
      xtype: 'combo',
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][3]}",
      name: '{$render}unit',
      valueField: 'value',
      allowBlank: false,
      displayField: 'name',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['MiB', 'MiB'],
            ['GiB', 'GiB']
         ]
      }),
      value: 'MiB',
      mode: 'local',
      triggerAction: 'all'
   }
];

var {$render}fieldset_item_FileSize = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
      name: '{$render}path',
      allowBlank: false
   }, {
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][2]}",
      name: '{$render}value',
      allowBlank: false
   }, {
      xtype: 'combo',
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][3]}",
      name: '{$render}unit',
      valueField: 'value',
      displayField: 'name',
      allowBlank: false,
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['MiB', 'MiB'],
            ['GiB', 'GiB']
         ]
      }),
      value: 'MiB',
      mode: 'local',
      triggerAction: 'all'
   }
];

var {$render}fieldset_item_Winkey_1 = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][13]}",
      name: '{$render}path',
      allowBlank: false
   }, {
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][14]}",
      name: '{$render}value',
      allowBlank: false
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_Winkey_2 = [{
      fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][13]}",
      name: '{$render}path',
      allowBlank: false
   }, {
      xtype:'hidden',
      name: '{$render}value',
      allowBlank: false
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];



var {$render}dynFieldset =  new Ext.form.FieldSet({
   layout: 'form',
   xtype: 'fieldset',
   style: 'margin:0;padding:0',
   border: false,
   autoHeight: true,
   defaultType: 'textfield',
    allowBlank: false,
   defaults: {width: {$width_right_fieldset_default}},
   width: {$width_right_fieldset},
   items: {$render}fieldset_item_default,
   flex: 1
});

function {$render}refreshDynFieldset(val) {
   {$render}dynFieldset.removeAll();
   switch(val) {
      case 'freespaceGreater':
         {$render}dynFieldset.add({$render}fieldset_item_FreespaceGreater);
         break;
      case 'fileSHA512':
         {$render}dynFieldset.add({$render}fieldset_item_FileSHA512);
         break;
      case 'fileSizeGreater':
      case 'fileSizeEquals':
      case 'fileSizeLower':
         {$render}dynFieldset.add({$render}fieldset_item_FileSize);
         break;
      case 'winkeyExists':
      case 'winkeyMissing':
         {$render}dynFieldset.add({$render}fieldset_item_Winkey_2);
         break;
      case 'winkeyEquals':
         {$render}dynFieldset.add({$render}fieldset_item_Winkey_1);
         break
      default:
         {$render}dynFieldset.add({$render}fieldset_item_default);
         break;
   }
   {$render}dynFieldset.doLayout();
}

//define form
var {$render}checkForm = new Ext.FormPanel({
   disabled: true,
   hidden: true,
   region: 'east',
   collapsible: true,
   collapsed: true,
   labelWidth: {$label_width},
   frame: true,
   title: "{$LANG['plugin_fusinvdeploy']['ftitle'][0]}",
   bodyStyle:'padding:5px 5px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_right},
   height: {$height_right},
   defaultType: 'textfield',
   items: [{
      name: '{$render}id',
      xtype: 'hidden'
   },
   new Ext.form.ComboBox({
      fieldLabel:"{$LANG['plugin_fusinvdeploy']['label'][0]}",
      name: 'type_name',
      valueField: 'name',
      allowBlank: false,
      displayField: 'value',
      hiddenName: '{$render}type',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['{$chkConst['WINKEY_EXISTS']}',  "{$LANG['plugin_fusinvdeploy']['check'][0]}"],
            ['{$chkConst['WINKEY_MISSING']}',  "{$LANG['plugin_fusinvdeploy']['check'][1]}"],
            ['{$chkConst['WINKEY_EQUAL']}',    "{$LANG['plugin_fusinvdeploy']['check'][2]}"],
            ['{$chkConst['FILE_EXISTS']}',    "{$LANG['plugin_fusinvdeploy']['check'][3]}"],
            ['{$chkConst['FILE_MISSING']}',    "{$LANG['plugin_fusinvdeploy']['label'][15]}"],
            ['{$chkConst['FILE_SIZEGREATER']}',"{$LANG['plugin_fusinvdeploy']['check'][5]}"],
            ['{$chkConst['FILE_SIZEEQUAL']}',  "{$LANG['plugin_fusinvdeploy']['check'][8]}"],
            ['{$chkConst['FILE_SIZELOWER']}',  "{$LANG['plugin_fusinvdeploy']['check'][9]}"],
            ['{$chkConst['FILE_SHA512']}',     "{$LANG['plugin_fusinvdeploy']['check'][6]}"],
            ['{$chkConst['FREE_SPACE']}',      "{$LANG['plugin_fusinvdeploy']['check'][7]}"]
         ]
      }),
      mode: 'local',
      triggerAction: 'all',
      listeners: {select:{fn:function(){
         {$render}refreshDynFieldset({$render}checkForm.getForm().findField('{$render}type').value);
      }}}
   }),
   {$render}dynFieldset
   ],
   buttons: [{
      text: "{$LANG['plugin_fusinvdeploy']['action'][2]}",
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {
         if ({$render}checkForm.record == null) {
            Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
            return;
         }
         if (!{$render}checkForm.getForm().isValid()) {
            Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
            return false;
         }
         {$render}checkForm.getForm().updateRecord({$render}checkForm.record);
         {$render}checkForm.getForm().submit({
            url : '../ajax/package_check.save.php?package_id={$id}&render={$render}',
            waitMsg: "{$LANG['plugin_fusinvdeploy']['message'][2]}",
            success: function(fileForm, o){
               {$render}checkGridStore.reload();
               {$render}checkGrid.setDisabled(false);
               {$render}checkForm.buttons[1].setVisible(false);
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
   }, {
      text: "{$LANG['buttons'][34]}",
      iconCls: 'exticon-cancel',
      name : '{$render}cancelbtn',
      id : '{$render}Checkcancelbtn',
      iconCls: 'exticon-cancel',
      hidden : true,
      handler: function(btn, ev) {
         btn.setVisible(false);
         {$render}checkGrid.store.reload();
         {$render}checkGrid.setDisabled(false);

         var selection = {$render}checkGrid.getSelectionModel().getSelected();
         if (!selection) {
             return false;
         }
         if (selection !== undefined) {
            {$render}checkGrid.store.remove(selection);
         }

         {$render}checkGrid.store.reload();

         if({$render}checkGridStore.data.length == 0) {
            {$render}checkForm.hide();
            {$render}checkForm.collapse();
         }
      }
   }],
   loadData : function({$render}rec) {
      {$render}refreshDynFieldset({$render}rec.data.{$render}type);
      {$render}checkForm.record = {$render}rec;
      {$render}checkForm.getForm().loadRecord({$render}rec);
   }
});

//render grid and form in a border layout
var {$render}CheckLayout = new Ext.Panel({
   layout: 'border',
   renderTo: '{$render}Check',
   height: {$height_layout},
   width: {$width_layout},
   defaults: {
      split: true,
   },
   items:[
      {$render}checkForm,
      {$render}checkGrid
   ]
 });


//grid store loading events
{$render}checkGridStore.on({
   'load':{
      fn: function(store, records, options) {
         //select first row
         {$render}checkGrid.getSelectionModel().selectFirstRow();
      },
      scope:this
   },
   'loadexception':{
      fn: function(obj, options, response, e){
         //disable form
         //{$render}checkForm.setDisabled(true);
      },
      scope:this
   }
});

JS;


echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
