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

function displayState(val, full) {
   if (full == null)
      full = true;

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

   if (full) return '<div class="c_state"><img src="../pics/ext/'+img_name+'">&nbsp;'+val+'</div>';
   else return '<img src="../pics/ext/'+img_name+'" alt="'+val+'">';
}

var taskJobsTreeGrid = new Ext.ux.tree.TreeGrid({
   title: "{$LANG['plugin_fusinvdeploy']['deploystatus'][0]}",
   height: {$height_left},
   width: {$width_left},
   region: 'center',
   style: 'margin-bottom:5px',
   enableDD: false,
   enableHdMenu: false,
   columnResize: false,
   enableSort: true,
   columns:[{
      dataIndex: 'name',
      width:200
   },{
      dataIndex: 'date',
      width:126
   },{
      dataIndex: 'type',
      hidden: true,
   },{
      dataIndex: 'progress',
      tpl: new Ext.XTemplate('{progress:this.renderProgressBar}', {
         renderProgressBar: function(val) {
            if (val == "null" || val == null) return '';
            if (val.indexOf('%') != -1)
               return '<div class="c_progress">{$LANG['common'][47]} :&nbsp;<div class="progress-container"><div style="width: '+val+'">'+val+'</div></div></div>';
            else {
               return displayState(val);
            }

         }
      })
   }, {
      dataIndex: 'items_id',
      hidden: true
   }],
   dataUrl: '../ajax/state_taskjobs.tree.data.php',
    listeners: {
        click: {
            fn:function (node,event){
               taskJobLogsGrid.getStore().removeAll();
               if (node.attributes.items_id) {
                  taskJobLogsGrid.getStore().setBaseParam('items_id', node.attributes.items_id);
                  taskJobLogsGrid.getStore().reload();
               }
            }
        }
    }

});

var tasksJobLogsColumns =  [{
   id: 'id',
   dataIndex: 'id',
   hidden: true
}, {
   id: 'date',
   dataIndex: 'date',
   header: '{$LANG['common'][27]}',
   width: 115
}, {
   id: 'state',
   dataIndex: 'state',
   renderer: logStateRenderer,
   header: '',
   width: 20
}, {
   id: 'comment',
   dataIndex: 'comment',
   header: '{$LANG['common'][25]}',
   width: 185
}];

function logStateRenderer(val) {
   return displayState(val, false);
}

var taskJobLogsReader = new Ext.data.JsonReader({
   root: 'taskjoblogs',
   fields: [
      'id', 'date', 'state', 'comment'
   ]
});

var taskJobLogsStore = new Ext.data.Store({
   url: '../ajax/state_taskjoblogs.data.php',
   autoLoad: false,
   reader: taskJobLogsReader,
   sortInfo: {field: 'date', direction: "DESC"}
});

var taskJobLogsGrid = new Ext.grid.GridPanel({
   region: 'east',
   stripeRows: true,
   height: {$height_right},
   width: {$width_right},
   style: 'margin-bottom:5px;',
   title: '{$LANG['plugin_fusinvdeploy']['task'][18]}',
   store: taskJobLogsStore,
   colModel: new Ext.grid.ColumnModel({
      defaults: {
         css: 'font-size:0.9em; vertical-align:middle;'
      },
      columns: tasksJobLogsColumns
   })
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
      taskJobsTreeGrid, taskJobLogsGrid
   ]
});


JS;

echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
