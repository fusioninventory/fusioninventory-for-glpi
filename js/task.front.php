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

$JS = <<<JS
Ext.app.BookLoader = Ext.extend(Ext.ux.tree.XmlTreeLoader, {
   processAttributes : function(attr){
   if(attr.first){ // is it an author node?

      // Set the node text that will show in the tree since our raw data does not include a text attribute:
      attr.text = attr.first + ' ' + attr.last;

      // Author icon, using the gender flag to choose a specific icon:
      attr.iconCls = 'author-' + attr.gender;

      // Override these values for our folder nodes because we are loading all data at once.  If we were
      // loading each node asynchronously (the default) we would not want to do this:
      attr.loaded = true;
      attr.expanded = true;
   }
   else if(attr.title){ // is it a book node?

      // Set the node text that will show in the tree since our raw data does not include a text attribute:
      attr.text = attr.title + ' (' + attr.published + ')';

      // Book icon:
      attr.iconCls = 'book';

      // Tell the tree this is a leaf node.  This could also be passed as an attribute in the original XML,
      // but this example demonstrates that you can control this even when you cannot dictate the format of
      // the incoming source XML:
      attr.leaf = true;
   }
   }
});


var detailsText = '<i>Select a book to see more information...</i>';

var tpl = new Ext.Template(
   '<h2 class="title">{title}</h2>',
   '<p><b>Published</b>: {published}</p>',
   '<p><b>Synopsis</b>: {innerText}</p>',
   '<p><a href="{url}" target="_blank">Purchase from Amazon</a></p>'
);
tpl.compile();

new Ext.Panel({
   title: 'Reading List',
   renderTo: 'Task',
   layout: 'border',
   width: {$width_layout},
   height: {$height_layout},
   items: [{
      xtype: 'treepanel',
      id: 'tree-panel',
      region: 'center',
      margins: '2 2 0 2',
      autoScroll: true,
      rootVisible: false,
      root: new Ext.tree.AsyncTreeNode(),

      // Our custom TreeLoader:
      loader: new Ext.app.BookLoader({
         dataUrl:'http://dev.sencha.com/deploy/ext-3.3.1/examples/tree/xml-tree-data.xml'
      }),

      listeners: {
         'render': function(tp){
            tp.getSelectionModel().on('selectionchange', function(tree, node){
               var el = Ext.getCmp('details-panel').body;
               if(node && node.leaf){
                  tpl.overwrite(el, node.attributes);
               }else{
                  el.update(detailsText);
               }
            })
         }
      }
   },{
      region: 'east',
      title: 'Book Details',
      id: 'details-panel',
      autoScroll: true,
      collapsible: true,
      split: true,
      margins: '0 2 2 2',
      cmargins: '2 2 2 2',
      height: {$height_left},
      width: {$width_left},
      html: detailsText
   }]
});


JS;

echo "<script type='text/javascript'>";
echo $JS;
echo "</script>";

?>
