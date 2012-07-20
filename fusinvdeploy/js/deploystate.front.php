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


//get const check class for use in heredoc
$refl = new ReflectionClass('PluginFusinvdeployState');
$stateConst = $refl->getConstants();

$width_left                  = 550;
$height_left                 = 350;

$width_right                  = 390;
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
         val = '{_('Prepared')}';
         break
      case '1':
         var img_name = 'bullet-yellow.png';
         val = '{_('Started')}';
         break
      case '2':
         var img_name = 'bullet-green.png';
         val = '{_('Ok')}';
         break
      case '3':
         var img_name = 'bullet-red.png';
         val = '{_('Error / rescheduled')}';
         break
      case '4':
         var img_name = 'bullet-red.png';
         val = '{_('Error')}';
         break
      case '5':
         var img_name = 'bullet-red.png';
         val = '{_('unknown')}';
         break
      case '6':
         var img_name = 'bullet-yellow.png';
         val = '{_('Running')}';
         break
      case '7':
         var img_name = 'bullet-blue.png';
         val = '{_('Prepared')}';
         break
      default:
         var img_name = 'bullet-grey.png';
         val = '';
   }

   if (full) return '<div class="c_state"><img src="../pics/ext/'+img_name+'">&nbsp;'+val+'</div>';
   else return '<img src="../pics/ext/'+img_name+'" alt="'+val+'">';
}

function createGridTooltip(value, contentid, message) {

   var btn = new Ext.Button({
      text: value,
      icon: '../pics/ext/information.png',
      height: 6,
      listeners: {
         click: {
            fn:function (btn,event){
               var tooltip = new Ext.ToolTip({
                  target: btn.id,
                  anchor: 'right',
                  cls: 'log-tooltip',
                  autoHide: false,
                  autoShow: false,
                  closable: true,
                  showDelay: 100,
                  html: message,
                  listeners: {
                     hide: {
                        fn:function (cmp){
                           tooltip.destroy();
                        }
                     }
                  }
               });

               tooltip.show();
            }
         }
      }
   }).render(document.body, contentid);


}

var taskJobsTreeGrid = new Ext.ux.tree.TreeGrid({
   title: "{_('Deployment status')}",
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
               return '<div class="c_progress">{_('Progress')} :&nbsp;<div class="progress-container"><div style="width: '+val+'">'+val+'</div></div></div>';
            else {
               return displayState(val);
            }

         }
      })
   }, {
      dataIndex: 'items_id',
      hidden: true
   }, {
      dataIndex: 'tasks_id',
      hidden: true
   }],
   root : new Ext.tree.AsyncTreeNode(),
   listeners: {
      click: {
         fn:function (node,event){
            if (node.attributes.items_id) {
               taskJobLogsTreeGrid.getLoader().baseParams.items_id = node.attributes.items_id;
               taskJobLogsTreeGrid.getLoader().baseParams.taskjobs_id = node.attributes.taskjobs_id;
               taskJobLogsTreeGrid.getLoader().baseParams.status_id = '0';
               taskJobLogsTreeGrid.getLoader().load(taskJobLogsTreeGrid.root);
            }
            node.toggle();
         }
      }
   },
   loader: new Ext.ux.tree.TreeGridLoader({
      dataUrl: "../ajax/state_taskjobs.tree.data.php",
      baseParams: {
         items_id: 0,
         parent_type: 'all',
      },
      listeners: {
         beforeload: {
            fn:function (treeLoader,node) {
               if (node.attributes.items_id) {
                  treeLoader.baseParams.items_id = node.attributes.items_id;
                  treeLoader.baseParams.parent_type = node.attributes.type;
               }
            }
         }
      }
   })
});


var taskJobLogsTreeGrid = new Ext.ux.tree.TreeGrid({
   title: "{_('Associated logs')}",
   height: {$height_right},
   width: {$width_right},
   region: 'east',
   style: 'margin-bottom:5px',
   enableDD: false,
   enableHdMenu: false,
   columnResize: false,
   enableSort: false,
   defaultSortable: false,
   columnResize: false,
   id: 'taskJobLogsTreeGrid',
   columns:[{
      dataIndex: 'date',
      width:106
   },{
      dataIndex: 'state',
      width:15,
      tpl: new Ext.XTemplate('{state:this.renderState}', {
         renderState: function(val) {
            //console.log(val);
            if (val == "null" || val == null) return '';
            return displayState(val, false);
         }
      })
   },{
      dataIndex: 'comment',
      width:123
   },{
      dataIndex: 'log',
      width:30,
      tpl: new Ext.XTemplate('{log:this.logRenderer}', {
         logRenderer: function(val, values) {
            var message = '';

            if (val != '')
                  message = val;
            else switch(values.comment) {
               case '{$stateConst['RECEIVED']}':
                  message = "{_('The agent received the job request')}"
                  break;
               case '{$stateConst['DOWNLOADING']}':
                  message = "{_('The agent started to check the mirror to download the file')}"
                  break;
               case '{$stateConst['EXTRACTING']}':
                  message = "{_('Preparing the working directory')}"
                  break;
               case '{$stateConst['PROCESSING']}':
                  message = "{_('The agent is processing the job')}"
                  break;
              default:
                  break
            }

            if (message != "") {
               var contentId = Ext.id();
               createGridTooltip.defer(1, this, ['', contentId, message]);
               return('<div id="' + contentId + '"></div>');
            }
            return '';
         }
      })
   },{
      dataIndex: 'type',
      hidden: true,
      width: 0
   },{
      dataIndex: 'status_id',
      hidden: true,
      width: 0
   },{
      dataIndex: 'logs_id',
      hidden: true,
      width: 0
   }],
   root : new Ext.tree.AsyncTreeNode({
      iconCls :'no-icon',
   }),
   loader: new Ext.ux.tree.TreeGridLoader({
      dataUrl: "../ajax/state_taskjoblogs.data.php",
      baseParams: {
         items_id: 0,
         status_id: 0,
      },
      listeners: {
         beforeload: {
            fn:function (treeLoader,node) {
               if (node.attributes.status_id) {
                  treeLoader.baseParams.status_id = node.attributes.status_id;
               }
            }
         }
      }
   }),
   listeners: {
      click: {
         fn:function (node, event) {
            node.toggle();
         }
      }
   }
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
      taskJobsTreeGrid, taskJobLogsTreeGrid
   ]
});


JS;

echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>