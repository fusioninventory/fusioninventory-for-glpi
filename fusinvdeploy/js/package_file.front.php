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
$height_left                 = 250;

$width_right                   = 340;
$height_right                  = 250;
$width_left_fieldset          = $width_left-19;
$width_left_fieldset_default  = $width_left-125;

$width_layout = $width_left + $width_right;
$height_layout = ($height_left>$height_right)?$height_left:$height_right;

$column_width = array(0,160,40,60,40,75,95,90);

$label_width = 120;
// END - Size of div/form/label...

//get max upload file size
$maxUpload = PluginFusinvdeployFile::getMaxUploadSize();

//get the file extensions that have an action to automatically add
$files_autoactions = json_encode(PluginFusinvdeployFile::getExtensionsWithAutoAction());


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
   header: "{$LANG['plugin_fusinvdeploy']['label'][10]}",
   width: {$column_width[0]},
   dataIndex: '{$render}id',
   hidden: true
}, {
   id: '{$render}file',
   header: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
   width: {$column_width[1]},
   dataIndex: '{$render}file',
}, {
   id: '{$render}mimetype',
   header: "{$LANG['plugin_fusinvdeploy']['label'][0]}",
   width: {$column_width[2]},
   dataIndex: '{$render}mimetype',
   renderer: {$render}renderMimetype
}, {
   header: "{$LANG['plugin_fusinvdeploy']['label'][21]}",
   width: {$column_width[3]},
   dataIndex: '{$render}filesize'
}, {
   id: '{$render}p2p',
   header: "{$LANG['plugin_fusinvdeploy']['label'][6]}",
   width: {$column_width[4]},
   dataIndex: '{$render}p2p',
   renderer: {$render}renderBool
}, {
   id: '{$render}dateadd',
   header: "{$LANG['plugin_fusinvdeploy']['label'][7]}",
   width: {$column_width[5]},
   dataIndex: '{$render}dateadd'
}, {
   id: '{$render}validity',
   header: "{$LANG['plugin_fusinvdeploy']['label'][8]}",
   width: {$column_width[6]},
   dataIndex: '{$render}validity'
}, {
   id: '{$render}uncompress',
   header: "{$LANG['plugin_fusinvdeploy']['label'][19]}",
   width: {$column_width[7]},
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
   if (val == 1) return "{$LANG['choice'][1]}";
   else if (val == 0) return "{$LANG['choice'][0]}";
   else return '';
}



//create store
var {$render}fileGridStore = new Ext.data.ArrayStore({
   fields: [
      {name: '{$render}id', type: 'integer'},
      {name: '{$render}file'},
      {name: '{$render}mimetype'},
      {name: '{$render}filesize'},
      {name: '{$render}p2p'},
      {name: '{$render}dateadd'},
      {name: '{$render}validity'},
      {name: '{$render}uncompress'}
   ]
});

var {$render}fileReader = new Ext.data.JsonReader({
   root: '{$render}files',
   fields: [
      '{$render}id',
      '{$render}file',
      '{$render}mimetype',
      '{$render}filesize',
      '{$render}p2p',
      '{$render}dateadd',
      '{$render}validity',
      '{$render}uncompress'
   ]
});

var {$render}fileStore = new Ext.data.GroupingStore({
   url               : '../ajax/package_file.data.php?package_id={$id}&render={$render}',
   autoLoad       : true,
   reader            : {$render}fileReader,
   sortInfo       :{field: '{$render}id', direction: "ASC"},
   groupField        :'{$render}p2p'
});




/***** DEFINE GRID ****/
var {$render}fileGrid = new Ext.grid.GridPanel({
   disabled: $disabled,
   region: 'center',
   margins: '0 0 0 5',
   store: {$render}fileStore,
   columns: {$render}fileColumns,
   stripeRows: true,
   height: $height_left,
   width: $width_left,
   style:'margin-bottom:5px',
   title: "{$LANG['plugin_fusinvdeploy']['ftitle'][3]}",
   stateId: '{$render}fileGrid',
   view: new Ext.grid.GroupingView({
      forceFit:true,
      groupTextTpl: '{text}',
      hideGroupedColumn: true,
      startCollapsed : true,
      forceFit : true,
      emptyText: '',
      emptyGroupText: ''
   }),
   tbar: [{
      text: "{$LANG['plugin_fusinvdeploy']['ftitle'][4]}",
      iconCls: 'exticon-add',
      handler: function(btn, ev) {
         {$render}fileForm.newFileMode(true);

         var {$render}u = new {$render}fileGridStore.recordType({
             {$render}file : '',
             {$render}mimetype: '',
             {$render}p2p: '0',
             {$render}dateadd: '',
             {$render}id: '',
             {$render}validity: '5',
             {$render}uncompress: '0'
         });
         {$render}fileStore.insert(0, {$render}u);
         {$render}fileGrid.getSelectionModel().selectFirstRow();
         var {$render}rec = {$render}fileGrid.getSelectionModel().getSelected();
         {$render}fileForm.loadData({$render}rec);
         //{$render}unlockForm();
         {$render}fileForm.setTitle("{$LANG['plugin_fusinvdeploy']['ftitle'][4]}");

         Ext.getCmp('{$render}p2p_t').setValue(false);
         Ext.getCmp('{$render}p2p_f').setValue(true);
         Ext.getCmp('{$render}uncompress_t').setValue(false);
         Ext.getCmp('{$render}uncompress_f').setValue(true);
      }
   }, '-', {
      text: "{$LANG['plugin_fusinvdeploy']['ftitle'][13]}",
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

                  {$render}fileForm.hide();
                  {$render}fileForm.collapse();
               },
               success: function(){
                  {$render}fileStore.reload();

                  {$render}fileForm.hide();
                  {$render}fileForm.collapse();
               }
            });
         }
      }
   }, '-'],
   sm: new Ext.grid.RowSelectionModel({
      singleSelect: true,
      listeners: {
         rowselect: function(g, index, ev) {
            if (!{$disabled}) {
               var {$render}rec = {$render}fileGrid.store.getAt(index);
               {$render}fileForm.loadData({$render}rec);

               if({$render}rec.get('{$render}file') == '') {$render}fileForm.newFileMode(true);
               else {$render}fileForm.newFileMode(false);


               if({$render}rec.get('{$render}p2p') == 0){
                  Ext.getCmp('{$render}p2p_t').setValue(false);
                  Ext.getCmp('{$render}p2p_f').setValue(true);
               }else{
                  Ext.getCmp('{$render}p2p_f').setValue(false);
                  Ext.getCmp('{$render}p2p_t').setValue(true);
               }

               if({$render}rec.get('{$render}uncompress') == 0){
                  Ext.getCmp('{$render}uncompress_t').setValue(false);
                  Ext.getCmp('{$render}uncompress_f').setValue(true);
               }else{
                  Ext.getCmp('{$render}uncompress_f').setValue(false);
                  Ext.getCmp('{$render}uncompress_t').setValue(true);
               }

               Ext.getCmp('{$render}file').setValue('');
               //Ext.getCmp('{$render}url').setValue('');
               {$render}fileForm.setTitle("{$LANG['plugin_fusinvdeploy']['ftitle'][5]}");
            }
         }
      }
   })
});






/***** DEFINE FORM ****/
var {$render}fileForm = new Ext.FormPanel({
   disabled: true,
   hidden : true,
   collapsible: true,
   collapsed: true,
   region: 'east',
   labelWidth: $label_width,
   fileUpload        : true,
   method            :'POST',
   enctype           :'multipart/form-data',
   frame: true,
   title: "{$LANG['plugin_fusinvdeploy']['ftitle'][5]}",
   bodyStyle:' padding:5px 5px 0',
   style:'margin-left:5px;margin-bottom:5px',
   width: $width_right,
   height: $height_right,
   defaultType: 'textfield',
   items: [{
         name: '{$render}id',
         xtype: 'hidden'
      }, new Ext.form.ComboBox({
         fieldLabel:"{$LANG['plugin_fusinvdeploy']['files'][7]}",
         id: '{$render}type',
         name: '{$render}type',
         valueField: 'name',
         displayField: 'value',
         width:180,
         hiddenName: '{$render}itemtype',
         store: new Ext.data.ArrayStore({
            fields: ['name', 'value'],
            data: [
               ['filehttp', "{$LANG['plugin_fusinvdeploy']['files'][8]}"],
               ['fileserver', "{$LANG['plugin_fusinvdeploy']['files'][9]}"]
            ]
         }),
         mode: 'local',
         triggerAction: 'all',
         listeners: {select:{fn:function(combo, record, index){
            switch(record.data.name) {
               case 'filehttp':
                  {$render}fileForm.getForm().findField('{$render}file_server').hide();
                  {$render}fileForm.getForm().findField('{$render}file').show();
                  {$render}fileForm.getForm().findField('{$render}file_info_maxfilesize').show();
                  break;
               case 'fileserver':
                  {$render}fileForm.getForm().findField('{$render}file').hide();
                  {$render}fileForm.getForm().findField('{$render}file_info_maxfilesize').hide();
                  {$render}fileForm.getForm().findField('{$render}file_server').show();
                  break;
            }
         }}}
      }), new Ext.ux.form.FileUploadField({
         name: '{$render}file',
         id: '{$render}file',
         hidden: true,
         width:180,
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
         buttonText: '',
         emptyText: "{$LANG['plugin_fusinvdeploy']['action'][3]}",
         buttonCfg: {
            iconCls: 'exticon-file'
         },
         listeners: {
            'fileselected': function(fb, v){
               {$render}ToggleUncompress(v);
               {$render}AddActionsAuto(v);
            }
         }
      }), {
         id: '{$render}file_info_maxfilesize',
         name: '{$render}file_info_maxfilesize',
         value: '$maxUpload',
         xtype: 'displayfield',
         allowBlank: false,
         hidden:true,
         style: 'font-size:8px'
      }, {
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][5]}",
         name: '{$render}file_server',
         id: '{$render}file_server',
         hidden: true,
         width: 180,
         xtype:'trigger',
         triggerClass: 'x-form-file-trigger',
         editable:false,
         emptyText: "{$LANG['plugin_fusinvdeploy']['action'][3]}",
         onTriggerClick: function() {
            chooser = new FileChooser({
               width: 615,
               height: 400,
               url_ls: '../ajax/package_file.ls.php',
               url_actions: '../ajax/package_file.actions.php',
               title: "{$LANG['document'][36]}"
            });

            chooser.show(this, function(el, data) {
               el.setValue(data);
               {$render}ToggleUncompress(data);
               {$render}AddActionsAuto(data);
            });
         }
      }, {
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][6]}",
         name: '{$render}p2p',
         id: '{$render}p2p',
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
            {boxLabel: "{$LANG['choice'][1]}", name: '{$render}p2p', inputValue: 'true', checked: true, id : '{$render}p2p_t'},
            {boxLabel: "{$LANG['choice'][0]}", name: '{$render}p2p', inputValue: 'false',id : '{$render}p2p_f'}
         ]
      }, new Ext.ux.form.SpinnerField({
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][9]}",
         name: '{$render}validity',
         id: '{$render}validity',
         width: 50,
         hidden: true
      }), {
         fieldLabel: "{$LANG['plugin_fusinvdeploy']['label'][23]}",
         name: '{$render}uncompress',
         id: '{$render}uncompress',
         allowBlank: false,
         xtype: 'radiogroup',
         width: 100,
         hidden: true,
         items: [
            {boxLabel: "{$LANG['choice'][1]}", name: '{$render}uncompress', inputValue: 'true', id : '{$render}uncompress_t'},
            {boxLabel: "{$LANG['choice'][0]}", name: '{$render}uncompress', inputValue: 'false', id : '{$render}uncompress_f'}
         ],
         tooltip:{
            tip:'Enter the customer\'s name',
            width: 150
         }
      }
   ],
   buttons: [{
      text: "{$LANG['plugin_fusinvdeploy']['action'][2]}",
      iconCls: 'exticon-save',
      name : '{$render}savebtn',
      id : '{$render}savebtn',
      hidden: true,
      handler: function(btn, ev) {
         if ({$render}fileForm.record == null) {
            Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
            return;
         }
         if (!{$render}fileForm.getForm().isValid()) {
            Ext.MessageBox.alert('Erreur', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
            return false;
         }

         {$render}fileForm.getForm().updateRecord({$render}fileForm.record);
         {$render}fileForm.getForm().submit({
            url : '../ajax/package_file.save.php?package_id={$id}&render={$render}',
            waitMsg: 'Chargement du fichier...',
            success: function(form, o){
               {$render}msg('Traitement du fichier',o.result.msg);
               installfileStore.reload();
               uninstallfileStore.reload();
               {$render}fileForm.newFileMode(false);
               form.reset();
               {$render}fileGrid.getSelectionModel().clearSelections();
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
   }, {
      text: "{$LANG['buttons'][14]}",
      iconCls: 'exticon-save',
      name : '{$render}updatebtn',
      id : '{$render}updatebtn',
      hidden : true,
      handler: function(btn, ev) {
            Ext.MessageBox.alert('Erreur', "update");
            if ({$render}fileForm.record == null) {
               Ext.MessageBox.alert('Erreur 1 ', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
               return;
            }
            if (!{$render}fileForm.getForm().isValid()) {
               Ext.MessageBox.alert('Erreur 2', "{$LANG['plugin_fusinvdeploy']['message'][0]}");
               return false;
            }
            {$render}fileForm.getForm().updateRecord({$render}fileForm.record);

            {$render}fileForm.getForm().submit({
               url : '../ajax/package_file.update.php?package_id={$id}&render={$render}',
               waitMsg: 'Chargement du fichier...',
               success: function(form, o){
                  {$render}msg('Traitement du fichier',o.result.msg);
                  {$render}fileStore.reload();
                  {$render}fileForm.newFileMode(false);
                  form.reset();
                  {$render}fileGrid.getSelectionModel().clearSelections();

               },
               failure: function({$render}fileForm, action){
                  switch (action.failureType) {
                     case Ext.form.Action.CLIENT_INVALID:
                        Ext.Msg.alert('Failure 1', 'Form fields may not be submitted with invalid values');
                        break;
                     case Ext.form.Action.CONNECT_FAILURE:
                        Ext.Msg.alert('Failure 2', 'Ajax communication failed');
                        break;
                     case Ext.form.Action.SERVER_INVALID:
                        Ext.Msg.alert('Failure 3', action.result.msg);
                  }

               }
            });
         }
   }, {
      text: "{$LANG['buttons'][34]}",
      iconCls: 'exticon-cancel',
      name : '{$render}cancelbtn',
      id : '{$render}cancelbtn',
      iconCls: 'exticon-cancel',
      hidden : true,
      handler: function(btn, ev) {
         {$render}fileForm.newFileMode(false);
         {$render}fileForm.buttons[2].setVisible(false);
         var selection = {$render}fileGrid.getSelectionModel().getSelected();
         if (!selection) {
             return false;
         }
         if (selection !== undefined) {
            {$render}fileGrid.store.remove(selection);
         }
         {$render}fileGrid.store.reload();

         if({$render}fileGrid.store.data.length == 0) {
            {$render}fileForm.hide();
            {$render}fileForm.collapse();
         }
      }
   }],
   loadData : function({$render}rec) {
      this.record = {$render}rec;
      this.getForm().loadRecord({$render}rec);
      {$render}ToggleUncompress(this.record.data.{$render}file);
   },
   newFileMode : function(s) {
      if (!{$disabled}) {
         if(s == true){
            {$render}fileForm.getForm().findField('{$render}type').show();
            {$render}fileForm.buttons[0].setVisible(true);
            {$render}fileForm.buttons[1].setVisible(false);
            {$render}fileForm.buttons[2].setVisible(true);
            {$render}fileGrid.setDisabled(true);
         } else {
            {$render}fileForm.getForm().findField('{$render}type').hide();
            {$render}fileForm.getForm().findField('{$render}file').hide();
            {$render}fileForm.getForm().findField('{$render}file_server').hide();
            {$render}fileForm.getForm().findField('{$render}file_info_maxfilesize').hide();
            {$render}fileForm.buttons[0].setVisible(false);
            {$render}fileForm.buttons[1].setVisible(true);
            {$render}fileForm.buttons[2].setVisible(false);
            {$render}fileGrid.setDisabled(false);
         }
         {$render}fileForm.show();
         {$render}fileForm.expand();
         {$render}fileForm.enable();
      }
   }
});

var {$render}ToggleUncompress = function(filename) {
   {$render}fileForm.getForm().findField('{$render}uncompress').hide();
   if (
      filename.indexOf('.zip') != -1
      || filename.indexOf('.gz') != -1
      || filename.indexOf('.bz2') != -1
      ||filename.indexOf('.tar') != -1
   ) {$render}fileForm.getForm().findField('{$render}uncompress').show();
}


var files_autoactions = $files_autoactions;
var {$render}AddActionsAuto = function(filename) {
   //clean filename (remove path)
   filename = filename.replace(/^.*[\\\/]/, '')

   //get file extension
   var ext = filename.substr(filename.lastIndexOf('.') + 1);

   if (ext in files_autoactions) {
      //show question to automatically add actions for this file
      Ext.Msg.show({
         title: "{$LANG['plugin_fusinvdeploy']['message'][5]}",
         msg: "{$LANG['plugin_fusinvdeploy']['message'][6]}",
         buttons: Ext.Msg.YESNO,
         icon: Ext.MessageBox.QUESTION,
         minWidth: 350,
         fn: function(btn, text) {
            //send data in db if user accept the alert
            if (btn == 'yes') {

               //get scripts
               var install_a = files_autoactions[ext].install.replace("##FILENAME##", filename);
               var uninstall_a = files_autoactions[ext].uninstall.replace("##FILENAME##", filename);
         
               //send scripts
               //-> install
               Ext.Ajax.request({
                  url: '../ajax/package_action.save.php?package_id={$_REQUEST["id"]}&render=install',
                  params: {
                     installexec: install_a,
                     installitemtype: 'PluginFusinvdeployAction_Command',
                     installid: ''
                  },
                  success: function(){
                     //-> uninstall
                     Ext.Ajax.request({
                        url: '../ajax/package_action.save.php?package_id={$_REQUEST["id"]}&render=uninstall',
                        params: {
                           uninstallexec: uninstall_a,
                           uninstallitemtype: 'PluginFusinvdeployAction_Command',
                           uninstallid: ''
                        },
                        success: function(){
                           installactionGrid.store.reload();
                           uninstallactionGrid.store.reload();
                        
                           //click submit button
                           myButton = {$render}fileForm.getForm().buttons[0];
                           myButton.handler.call(myButton.scope, myButton, Ext.EventObject);
                        }
                     });
                  }
               })
            }
         }
      });
   }
}

//render grid and form in a border layout
var {$render}FileLayout = new Ext.Panel({
   layout: 'border',
   renderTo: '{$render}File',
   height: $height_layout,
   width: $width_layout,
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
