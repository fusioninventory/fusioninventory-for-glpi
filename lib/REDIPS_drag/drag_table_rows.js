/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redipsInit variable
var redipsInit;

// redips initialization
redipsInit = function (drag_type) {
   // reference to the REDIPS.drag library and message line
   var rd = REDIPS.drag, msg;
   // initialization
   //if (document.getElementById("drag_checks")  != null) rd.init("drag_checks");
   //if (document.getElementById("drag_files")   != null) rd.init("drag_files");
   //if (document.getElementById("drag_actions") != null) rd.init("drag_actions");
   if (document.getElementById(drag_type) != null) rd.init(drag_type);
   // set hover color for TD and TR
   rd.hover.colorTr = '#FFF4DF';
   // set hover border for current TD and TR
   rd.hover.borderTr = '2px solid #c0cc7b';
   // drop row after highlighted row (if row is dropped to other tables)
   rd.rowDropMode = 'after';

   // row was moved - event handler
   rd.event.rowMoved = function () {
      // set opacity for moved row
      // rd.obj is reference of cloned row (mini table)
      rd.rowOpacity(rd.obj, 30);
      // set opacity for source row and change source row background color
      // rd.objOld is reference of source row
      rd.rowOpacity(rd.objOld, 20, '#f1f4e3');
   };

   //Try to update and check if it's possible.
   //If not, cancel the drop
   rd.event.rowDroppedBefore = function (sourceTable, sourceRowIndex) {
      var pos = rd.getPosition();

      var old_index = sourceRowIndex;
      var new_index = pos[1];

      var dropok = false;

      //get the hidden input containing itemtype
      var table = document.getElementById(rd.obj.id);
      var formitems = table.parentNode.childNodes;
      var itemtype = null;
      for( var i = 0; i < formitems.length; i++ ) {
         if ( formitems[i].name == 'itemtype') {
            itemtype = formitems[i].value;
            break;
         }
      }


      //send ajax request to update json
      Ext.Ajax.request({
         url : '../ajax/deploypackage_form.php',
         method: 'POST',
         params :{
            "move_item": true, 
            "old_index": old_index,
            "new_index": new_index,
            "itemtype": itemtype,
            "orders_id": orders[rand]
         },
         success: function ( result, request ) {

            var els = Ext.select('#package_json_debug');

            if (els.getCount() > 0) {
               if (els.getCount() == 1) {
                  els.first().load({
                        url: '../ajax/deploypackage_form.php',
                        method : 'GET',
                        params : {
                           "orders_id": orders[rand],
                           "itemtype":"package_json_debug"
                        },
                        waitMsg: 'updating...'
                     }
                  )
               } else {
                  //this should not happen but who can do more can do less ;) ... so just report it
                  console.log("too much #package_json_debug elements (count=" + els.getCount() + ")");
               }
            } else {
               console.log("no #package_json_debug");
            }
         },
         failure: function ( result, request ) {
            console.log("Ajax.error : "+result.responseText);
            dropok = false;
         }
      });

      //restore dragged object opacity if the drop fails
      if (!dropok) rd.rowOpacity(rd.objOld, 100);

      return dropok;
   }

   // row was dropped - event handler
   rd.event.rowDropped = function (targetRow, sourceTable, sourceRowIndex) {

      // display message
   };

   // row was dropped to the source - event handler
   // mini table (cloned row) will be removed and source row should return to original state
   rd.event.rowDroppedSource = function () {
      // make source row completely visible (no opacity)
      rd.rowOpacity(rd.objOld, 100);
   };
};

//redipsInit();
