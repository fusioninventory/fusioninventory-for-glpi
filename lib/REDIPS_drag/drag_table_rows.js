/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redipsInit variable
var redipsInit;

// redips initialization
redipsInit = function (order_type,order_subtype,order_id) {
   // reference to the REDIPS.drag library and message line
   var rd = REDIPS.drag, msg;
   var drag_type = 'drag_' + order_type + '_' + order_subtype + 's';
   // initialization
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
      //var table = document.getElementById(rd.obj.id);
      var form = jQuery('#' + rd.obj.id).parents("form:first")
      var itemtype = null;
      var itemtype_search = form.children("input[name='itemtype']");
      var orders_id_search = form.children("input[name='order_id']");

      if (itemtype_search.length == 1 ) itemtype = itemtype_search.val();
      if (orders_id_search.length == 1 ) order_id = orders_id_search.val();

      if ( (typeof order_id === 'undefined') || (typeof itemtype === 'undefined') ) {
         return false;
      }

      //send ajax request to update json (and debug if activated)
      jQuery.ajax({
         url : '../ajax/deploypackage_form.php',
         method : 'POST',
         async : false,
         dataType: 'json',
         data :{
            "move_item": true,
            "old_index": old_index,
            "new_index": new_index,
            "itemtype": itemtype,
            "id": order_id
         },
         success: function ( result, request ) {
//            var data = Ext.decode( result.responseText );
            dropok = result['success'];

            var els = jQuery('#package_order_'+ order_id +' #package_json_debug');

            if (els.length > 0) {
               if (els.length == 1) {
                  els.first().load(
                        '../ajax/deploypackage_form.php',
                        {
                           "id": orders_id,
                           "subtype":"package_json_debug"
                        }
                  );
               } else {
                  //this is weird and should not happen !!
                  // but who can do more can do less ;) ... so just reporting it
                  console.log("too much #package_json_debug elements (count=" + els.getCount() + ")");
               }
            }
         },
         error: function ( result, request ) {
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
