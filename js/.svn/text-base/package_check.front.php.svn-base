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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------
global $LANG;

//get const check class for use in heredoc
$refl = new ReflectionClass('PluginFusinvdeployCheck');
$chkConst = $refl->getConstants();

// Size of div/form/label...
$width_right                  = 590;
$height_right                 = 325;

$width_left                   = 340;
$height_left                  = 325;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$column_width = array(30,180,210,95,50);

$label_width = 75;
// END - Size of div/form/label...

// Render div
if(isset($_POST["glpi_tab"])) {
   switch($_POST["glpi_tab"]){
      case 2 :
         $render = "install";
         break;
      case 3 :
         $render = "uninstall";
         break;
   }
}
// END - Render div

$JS = <<<JS

Ext.QuickTips.init();


//define colums for grid
var {$render}checkColumns =  [{
   id: '{$render}id',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][10]}',
   width: {$column_width[0]},
   dataIndex: '{$render}id',
   hidden: true
}, {
   id: '{$render}type',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
   width: {$column_width[1]},
   dataIndex: '{$render}type',
   renderer: {$render}renderType
}, {
   id: '{$render}path',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][1]}',
   width: {$column_width[2]},
   dataIndex: '{$render}path'
}, {
   id: '{$render}value',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][2]}',
   width: {$column_width[3]},
   dataIndex: '{$render}value'
}, {
   id: '{$render}unit',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][3]}',
   width: {$column_width[4]},
   dataIndex: '{$render}unit'
}];


//define renderer for grid columns
function {$render}renderType(val) {
   switch(val) {
      case '{$chkConst['WINKEY_PRESENT']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][0]}';
      case '{$chkConst['WINKEY_MISSING']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][1]}';
      case '{$chkConst['WINKEY_EQUAL']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][2]}';
      case '{$chkConst['FILE_PRESENT']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][3]}';
      case '{$chkConst['FILE_MISSING']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][4]}';
      case '{$chkConst['FILE_SIZEGREATER']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][5]}';
      case '{$chkConst['FILE_SIZEEQUAL']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][8]}';
      case '{$chkConst['FILE_SIZELOWER']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][9]}';
      case '{$chkConst['FILE_SHA512']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][6]}';
      case '{$chkConst['FREE_SPACE']}':
         return '{$LANG['plugin_fusinvdeploy']['form']['check'][7]}';
   }
}



//create store and load data
var {$render}checkGridReader = new Ext.data.JsonReader({
   root: '{$render}checks',
   fields: ['{$render}id', '{$render}type', '{$render}path', '{$render}value','{$render}unit']
});

var {$render}checkGridStore = new Ext.data.GroupingStore({
   url: '../ajax/package_check.data.php?package_id={$id}&render={$render}',
   autoLoad: true,
   reader: {$render}checkGridReader,
   sortInfo: {field: '{$render}id', direction: "ASC"},
   groupField : '{$render}type'
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
   region: 'center',
   margins: '0 0 0 5',
   store: {$render}checkGridStore,
   columns: {$render}checkColumns,
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style:'margin-bottom:5px',
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][2]}',
   stateId: '{$render}checkGrid',
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text} ({[values.rs.length]})',
      startCollapsed : true,
      forceFit : true,
   }),
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][0]}',
      iconCls: 'exticon-add',
      handler: function(btn,ev) {
         var u = new {$render}checkGridStore.recordType({
            {$render}id : '',
            {$render}type : '',
            {$render}path: '',
            {$render}value: '',
            {$render}unit: ''
         });
         {$render}checkGridStore.insert(0,u);
         {$render}checkGrid.getSelectionModel().selectFirstRow();
         {$render}checkForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][1]}');
      }
   }, '-', {
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][1]}',
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
            if (!{$render}checkForm.collapsed) {$render}checkForm.toggleCollapse();
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
            var {$render}rec = {$render}checkGrid.store.getAt(index);
            {$render}checkForm.loadData({$render}rec);
            {$render}checkForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][0]}');
            if ({$render}checkForm.collapsed) {$render}checkForm.toggleCollapse();
            {$render}checkForm.buttons[0].setDisabled(false);
         }
      }
   })
});



//define group item
var {$render}fieldset_item_default = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
      name: '{$render}path'
   }, {
      name: '{$render}value',
      xtype:'hidden'
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_FileSHA512 = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
      name: '{$render}path'
   }, {
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][2]}',
      name: '{$render}value',
      xtype: 'textarea'
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_FreespaceGreater = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][12]}',
      name: '{$render}path'
   }, {
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][2]}',
      name: '{$render}value'
   }, {
      xtype: 'combo',
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][3]}',
      name: '{$render}unit',
      valueField: 'value',
      displayField: 'name',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['{$LANG['common'][82]}', 'Mb']
         ]
      }),
      value: 'Mb',
      mode: 'local',
      triggerAction: 'all'
   }
];

var {$render}fieldset_item_FileSize = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
      name: '{$render}path'
   }, {
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][2]}',
      name: '{$render}value'
   }, {
      xtype: 'combo',
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][3]}',
      name: '{$render}unit',
      valueField: 'value',
      displayField: 'name',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['{$LANG['common'][82]}', 'Mb']
         ]
      }),
      value: 'Mb',
      mode: 'local',
      triggerAction: 'all'
   }
];

var {$render}fieldset_item_Winkey_1 = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][13]}',
      name: '{$render}path'
   }, {
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][14]}',
      name: '{$render}value'
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];

var {$render}fieldset_item_Winkey_2 = [{
      fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][13]}',
      name: '{$render}path'
   }, {
      xtype:'hidden',
      name: '{$render}value'
   }, {
      name: '{$render}unit',
      xtype:'hidden'
   }
];



var {$render}dynFieldset =  new Ext.form.FieldSet({
   layout: 'form',
   xtype: 'fieldset',
   style : 'margin-top:10px',
   autoHeight: true,
   defaultType: 'textfield',
   defaults: {width: {$width_left_fieldset_default}},
   width: {$width_left_fieldset},
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
      case 'fileSizeEqual':
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
   region: 'west',
   collapsible: true,
   collapsed: true,
   labelWidth: {$label_width},
   frame: true,
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][0]}',
   bodyStyle:'padding:5px 5px',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_left},
   height: {$height_left},
   defaultType: 'textfield',
   items: [{
      name: '{$render}id',
      xtype: 'hidden'
   },
   new Ext.form.ComboBox({
      fieldLabel:'{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
      name: 'type_name',
      valueField: 'name',
      displayField: 'value',
      hiddenName: '{$render}type',
      store: new Ext.data.ArrayStore({
         fields: ['name', 'value'],
         data: [
            ['{$chkConst['WINKEY_PRESENT']}',  '{$LANG['plugin_fusinvdeploy']['form']['check'][0]}'],
            ['{$chkConst['WINKEY_MISSING']}',  '{$LANG['plugin_fusinvdeploy']['form']['check'][1]}'],
            ['{$chkConst['WINKEY_EQUAL']}',    '{$LANG['plugin_fusinvdeploy']['form']['check'][2]}'],
            ['{$chkConst['FILE_PRESENT']}',    '{$LANG['plugin_fusinvdeploy']['form']['check'][3]}'],
            ['{$chkConst['FILE_MISSING']}',    '{$LANG['plugin_fusinvdeploy']['form']['check'][4]}'],
            ['{$chkConst['FILE_SIZEGREATER']}','{$LANG['plugin_fusinvdeploy']['form']['check'][5]}'],
            ['{$chkConst['FILE_SIZEEQUAL']}',  '{$LANG['plugin_fusinvdeploy']['form']['check'][8]}'],
            ['{$chkConst['FILE_SIZELOWER']}',  '{$LANG['plugin_fusinvdeploy']['form']['check'][9]}'],
            ['{$chkConst['FILE_SHA512']}',     '{$LANG['plugin_fusinvdeploy']['form']['check'][6]}'],
            ['{$chkConst['FREE_SPACE']}',      '{$LANG['plugin_fusinvdeploy']['form']['check'][7]}']
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
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][2]}',
      iconCls: 'exticon-save',
      disabled:true,
      handler: function(btn,ev) {
         if ({$render}checkForm.record == null) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return;
         }
         if (!{$render}checkForm.getForm().isValid()) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return false;
         }
         {$render}checkForm.getForm().updateRecord({$render}checkForm.record);
         {$render}checkForm.getForm().submit({
            url : '../ajax/package_check.save.php?package_id={$id}&render={$render}',
            waitMsg: '{$LANG['plugin_fusinvdeploy']['form']['message'][2]}',
            success: function(fileForm, o){
               {$render}checkGridStore.reload();
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
