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

// Size of div/form/label...
$width_right                  = 590;
$height_right                 = 250;

$width_left                   = 340;
$height_left                  = 250;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$column_width = array(100,160,40,100,80,95,80);

$label_width = 140;
// END - Size of div/form/label...

//get max upload file size
$maxUpload = PluginFusinvdeployFile::getMaxUploadSize();


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

var {$render}msg = function(title, msg){
   Ext.Msg.show({
      title          : title,
      msg               : msg,
      minWidth       : 200,
      modal          : true,
      icon           : Ext.Msg.INFO,
      buttons           : Ext.Msg.OK
   });
};

//define colums for grid
var {$render}fileColumns =  [{
   id: '{$render}id',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][10]}',
   width: {$column_width[0]},
   dataIndex: '{$render}id',
   hidden: true
}, {
   id: '{$render}file',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
   width: {$column_width[1]},
   dataIndex: '{$render}file'
}, {
   id: '{$render}mimetype',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][0]}',
   width: {$column_width[2]},
   dataIndex: '{$render}mimetype',
   renderer: {$render}renderMimetype
}, {
   id: '{$render}p2p',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][6]}',
   width: {$column_width[3]},
   dataIndex: '{$render}p2p',
   renderer: {$render}renderBool
}, {
   id: '{$render}dateadd',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][7]}',
   width: {$column_width[4]},
   dataIndex: '{$render}dateadd'
}, {
   id: '{$render}validity',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][8]}',
   width: {$column_width[5]},
   dataIndex: '{$render}validity'
}, {
   id: '{$render}uncompress',
   header: '{$LANG['plugin_fusinvdeploy']['form']['label'][19]}',
   width: {$column_width[6]},
   dataIndex: '{$render}uncompress',
   renderer: {$render}renderBool
}];

function {$render}renderMimetype(val) {
   val = val.replace(/\//g, '__');
   return '<img src="../pics/ext/extensions/'+val+'.png" onError="{$render}badImage(this)" />';
}

function {$render}badImage(img) {
   img.src='../pics/ext/extensions/documents.png';
}

function {$render}renderBool(val) {
   if (val == 1) return '{$LANG['choice'][1]}';
   else return '{$LANG['choice'][0]}';
}



//create store
var {$render}fileGridStore = new Ext.data.ArrayStore({
   fields: [
      {name: '{$render}id'},
      {name: '{$render}file'},
      {name: '{$render}mimetype'},
      {name: '{$render}p2p'},
      {name: '{$render}dateadd'},
      {name: '{$render}validity'},
      {name: '{$render}uncompress'}
   ]
});

var {$render}fileReader = new Ext.data.JsonReader({
   root           : '{$render}files',
   fields            : ['{$render}id', '{$render}file', '{$render}mimetype', '{$render}p2p','{$render}dateadd', '{$render}validity', '{$render}uncompress']
});

var {$render}fileStore = new Ext.data.GroupingStore({
   url               : '../ajax/package_file.data.php?package_id={$id}&render={$render}',
   autoLoad       : true,
   reader            : {$render}fileReader,
   sortInfo       :{field: '{$render}id', direction: "ASC"},
   groupField        :'{$render}p2p'
});


//define grid
var {$render}fileGrid = new Ext.grid.GridPanel({
   region: 'center',
   margins: '0 0 0 5',
   store: {$render}fileStore,
   columns: {$render}fileColumns,
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style:'margin-bottom:5px',
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][3]}',
   stateId: '{$render}fileGrid',
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text} ({[values.rs.length]})',
      startCollapsed : true,
      forceFit : true,
   }),
   tbar: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][4]}',
      iconCls: 'exticon-add',
      handler: function(btn, ev) {
         var {$render}u = new {$render}fileGridStore.recordType({
             {$render}file : '',
             {$render}mimetype: '',
             {$render}p2p: '',
             {$render}dateadd: '',
             {$render}id: '',
             {$render}validity: '',
             {$render}uncompress: ''
         });
         {$render}fileStore.insert(0, {$render}u);
         {$render}fileGrid.getSelectionModel().selectFirstRow();
         var {$render}rec = {$render}fileGrid.getSelectionModel().getSelected();
         {$render}fileForm.loadData({$render}rec);
         {$render}unlockForm();
         {$render}fileForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][4]}');

         Ext.getCmp('{$render}p2p_t').setValue(false);
         Ext.getCmp('{$render}p2p_f').setValue(true);
         Ext.getCmp('{$render}uncompress_t').setValue(false);
         Ext.getCmp('{$render}uncompress_f').setValue(true);
      }
   }, '-', {
      text: '{$LANG['plugin_fusinvdeploy']['form']['title'][13]}',
      iconCls: 'exticon-delete',
      handler: function(btn, ev) {
         var selection = {$render}fileGrid.getSelectionModel().getSelected();
         if (!selection) {
             return false;
         }
         if (selection !== undefined) {
            {$render}fileGrid.store.remove(selection);
            Ext.Ajax.request({
               url: '../ajax/package_file.delete.php?package_id={$id}&render={$render}',
               params: {
                  id: selection.get('{$render}id')
               },
               success: function(){
                  {$render}fileStore.reload();
               }
            });
         }
         //{$render}fileForm.hide();
         {$render}fileForm.collapse();
      }
   }, '-'],
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g, index, ev) {
            /*
            var {$render}rec = {$render}fileGrid.store.getAt(index);
            {$render}fileForm.loadData({$render}rec);

            {$render}unlockForm({$render}rec);

            if({$render}rec.get('{$render}p2p') == 0){
               Ext.getCmp('{$render}p2p_t').setValue(false);
               Ext.getCmp('{$render}p2p_f').setValue(true);
            }else{
               Ext.getCmp('{$render}p2p_f').setValue(false);
               Ext.getCmp('{$render}p2p_t').setValue(true);
            }


            Ext.getCmp('{$render}file').setValue('');
            //Ext.getCmp('{$render}url').setValue('');
            Ext.getCmp('{$render}validity').setValue({$render}rec.get('{$render}validity'));
            {$render}fileForm.setTitle('{$LANG['plugin_fusinvdeploy']['form']['title'][5]}');
            */
         }
      }
   })
});





//define form
var {$render}fileForm = new Ext.FormPanel({
   collapsible: true,
   collapsed: true,
   /*hidden : true,*/
   region: 'east',
   labelWidth: {$label_width},
   fileUpload        : true,
   method            :'POST',
   enctype           :'multipart/form-data',
   frame: true,
   title: '{$LANG['plugin_fusinvdeploy']['form']['title'][5]}',
   bodyStyle:' padding:5px 5px 0',
   style:'margin-left:5px;margin-bottom:5px',
   width: {$width_left},
   height: {$height_left},
   defaultType: 'textfield',
   items: [
      {name: '{$render}id',xtype: 'hidden'},
      new Ext.ux.form.FileUploadField({
         name: '{$render}file',
         id: '{$render}file',
         allowBlank: false,
         width:180,
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][5]}',
         buttonText: '',
         emptyText: '{$LANG['plugin_fusinvdeploy']['form']['action'][3]}',
         buttonCfg: {
            iconCls: 'exticon-file'
         }
      }), {
         name: '{$render}file_info_maxfilesize',
         value: '{$maxUpload}',
         xtype: 'displayfield',
         allowBlank: false,
         style: 'font-size:8px'
      }, /*new Ext.form.Field({
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['action'][5]}',
         name: '{$render}url',
         id: '{$render}url',
         width: 50,
      }),*/
         {
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][6]}',
         name: '{$render}p2p',
         id: '{$render}p2p',
         allowBlank: false,
         xtype: 'radiogroup',
         width: 100,
         listeners: {change:{fn:function(){
            var {$render}radioP2p = {$render}fileForm.getForm().findField('{$render}p2p').getValue();
            if({$render}radioP2p.getGroupValue() == 'false')
               {$render}fileForm.getForm().findField('{$render}validity').hide();
            else
               {$render}fileForm.getForm().findField('{$render}validity').show();
            }
         }},
         items: [
            {boxLabel: '{$LANG['choice'][1]}', name: '{$render}p2p', inputValue: 'true', checked: true, id : '{$render}p2p_t'},
            {boxLabel: '{$LANG['choice'][0]}', name: '{$render}p2p', inputValue: 'false',id : '{$render}p2p_f'}
         ]
      }, new Ext.ux.form.SpinnerField({
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][9]}',
         name: '{$render}validity',
         id: '{$render}validity',
         width: 50,
         hidden: true
      }), {
         fieldLabel: '{$LANG['plugin_fusinvdeploy']['form']['label'][19]}',
         name: '{$render}uncompress',
         id: '{$render}uncompress',
         allowBlank: false,
         xtype: 'radiogroup',
         width: 100,
         items: [
            {boxLabel: '{$LANG['choice'][1]}', name: '{$render}uncompress', inputValue: 'true', checked: true, id : '{$render}uncompress_t'},
            {boxLabel: '{$LANG['choice'][0]}', name: '{$render}uncompress', inputValue: 'false',id : '{$render}uncompress_f'}
         ],
         tooltip:{
            tip:'Enter the customer\'s name',
            width: 150
         }
      }
   ],
   buttons: [{
      text: '{$LANG['plugin_fusinvdeploy']['form']['action'][2]}',
      iconCls: 'exticon-save',
      name : '{$render}save',
      id : '{$render}save',
      disabled: true,
      handler: function(btn, ev) {
         if ({$render}fileForm.record == null) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return;
         }
         if (!{$render}fileForm.getForm().isValid()) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return false;
         }

         {$render}fileForm.getForm().updateRecord({$render}fileForm.record);
         {$render}fileForm.getForm().submit({
            url : '../ajax/package_file.save.php?package_id={$id}&render={$render}',
            waitMsg: 'Chargement du fichier...',
            success: function(form, o){
               {$render}msg('Traitement du fichier',o.result.msg);
               {$render}fileStore.reload();
               form.reset();
               {$render}fileGrid.getSelectionModel().clearSelections();
               {$render}fileForm.hide();
               {$render}fileForm.collapse();
            },
            failure: function({$render}fileForm, action){
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
   },
   {
      text: '{$LANG['buttons'][14]}',
      iconCls: 'exticon-save',
      name : '{$render}update',
      id : '{$render}update',
      hidden : true,
      handler: function(btn, ev) {
         if ({$render}fileForm.record == null) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return;
         }
         if (!{$render}fileForm.getForm().isValid()) {
            Ext.MessageBox.alert('Erreur', '{$LANG['plugin_fusinvdeploy']['form']['message'][0]}');
            return false;
         }
         {$render}fileForm.getForm().updateRecord({$render}fileForm.record);
         {$render}fileForm.getForm().submit({
            url : '../ajax/package_file.update.php?package_id={$id}&render={$render}',
            waitMsg: 'Chargement du fichier...',
            success: function(form, o){
               {$render}msg('Traitement du fichier',o.result.msg);
               {$render}fileStore.reload();
               form.reset();
               {$render}fileGrid.getSelectionModel().clearSelections();
               {$render}fileForm.hide();
               {$render}fileForm.collapse();
            },
            failure: function({$render}fileForm, action){
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
      this.record = {$render}rec;
      this.getForm().loadRecord({$render}rec);
   }
});


function {$render}unlockForm({$render}rec){
   {$render}fileForm.show();
   {$render}fileForm.expand();
   {$render}fileForm.buttons[0].setDisabled(false);
}


//render grid and form in a border layout
var {$render}FileLayout = new Ext.Panel({
   layout: 'border',
   renderTo: '{$render}File',
   height: {$height_layout},
   width: {$width_layout},
   defaults: {
      split: true,
   },
   items:[
      {$render}fileForm,
      {$render}fileGrid
   ]
 });

//grid store loading events
{$render}fileStore.on({
   'load':{
      fn: function(store, records, options) {
         //select first row
         {$render}fileGrid.getSelectionModel().selectFirstRow();
      },
      scope:this
   },
   'loadexception':{
      fn: function(obj, options, response, e){
         //disable form
         //{$render}fileForm.setDisabled(true);
      },
      scope:this
   }
});

JS;
echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
