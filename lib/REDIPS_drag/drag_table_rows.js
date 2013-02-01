/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false, REDIPS: true */

/* enable strict mode */
"use strict";

// define redipsInit variable
var redipsInit;

// redips initialization
redipsInit = function () {
   // reference to the REDIPS.drag library and message line
   var rd = REDIPS.drag, msg;
   // initialization
   if (document.getElementById("drag_checks")  != null) rd.init("drag_checks");
   if (document.getElementById("drag_files")   != null) rd.init("drag_files");
   if (document.getElementById("drag_actions") != null) rd.init("drag_actions");
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

   // row was dropped - event handler
   rd.event.rowDropped = function (targetRow, sourceTable, sourceRowIndex) {
      var pos = rd.getPosition();

      var old_index = sourceRowIndex;
      var new_index = pos[1];
      var id = rd.obj.id.replace("table_", "")

      //get id, remove "table_" and capitalize first letter
      id = id.charAt(0).toUpperCase() + id.slice(1)
      var itemtype = "PluginFusioninventoryDeploy"+id;

      //send ajax request to update json
      Ext.Ajax.request({
         url : '../front/deploypackage.form.php',
         method: 'POST',
         params :{
            "move_item": true, 
            "old_index": old_index,
            "new_index": new_index,
            "itemtype": itemtype,
            "orders_id": orders[rand]
         },
         success: function ( result, request ) {
            window.location.reload();
         },
         failure: function ( result, request ) {
            console.log("Ajax.error : "+result.responseText)
         }
      });

      // display message
   };

   // row was dropped to the source - event handler
   // mini table (cloned row) will be removed and source row should return to original state
   rd.event.rowDroppedSource = function () {
      // make source row completely visible (no opacity)
      rd.rowOpacity(rd.objOld, 100);
   };
};

redipsInit();