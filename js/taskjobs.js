var taskjobs = {}

taskjobs.show_form = function( data, textStatus, jqXHR) {
   $('#taskjobs_form')
      .html(data);
}

taskjobs.hide_form = function () {
   $('#taskjobs_form')
      .html('');
}

taskjobs.register_update_method = function (rand_id) {

   //reset onchange event
   $("#" + rand_id ).off("change", "*");
   $("#" + rand_id ).on("change",
      function(e) {
         $("#method_selected").text(e.val);
         //reset targets and actors dropdown
         taskjobs.hide_moduletypes_dropdown();
         taskjobs.hide_moduleitems_dropdown();
         taskjobs.clear_list('targets');
         taskjobs.clear_list('actors');
      }
      );
}

taskjobs.register_update_items = function (rand_id, moduletype, ajax_url) {
   //reset onchange event
   $("#" + rand_id ).off("change", "*");
   $("#" + rand_id ).on("change",
         function(e) {
            //$("#taskjob_moduleitems_dropdown").text(e.val);
            taskjobs.show_moduleitems(ajax_url, moduletype, e.val)
         }
         );
}

taskjobs.register_form_changed = function () {
   //reset onchange event
   $("form[name=form_taskjob]" ).off("change", "*");
   $("form[name=form_taskjob]" ).on("change",
         function(e) {
            $('#cancel_job_changes_button').show();
         }
         );
}

taskjobs.create = function(ajax_url, task_id) {
   $.ajax({
      url: ajax_url,
      data: {
         "task_id" : task_id
      },
      success: taskjobs.show_form
   })
}

taskjobs.edit = function(ajax_url, taskjob_id) {
   $.ajax({
      url: ajax_url,
      data: {
         "id" : taskjob_id
      },
      success: taskjobs.show_form
   })
}

taskjobs.hide_moduletypes_dropdown = function() {
   $('#taskjob_moduletypes_dropdown')
      .html('');
}

taskjobs.show_moduletypes_dropdown = function(dropdown_dom) {
   $('#taskjob_moduletypes_dropdown')
      .html(dropdown_dom);
}

taskjobs.hide_moduleitems_dropdown = function() {
   $('#taskjob_moduleitems_dropdown')
      .html('');
}

taskjobs.show_moduleitems_dropdown = function(dropdown_dom) {
   $('#taskjob_moduleitems_dropdown')
      .html(dropdown_dom);
}

taskjobs.clear_list = function(moduletype) {
   $('#taskjob_'+moduletype+'_list')
      .html('');
}

taskjobs.delete_items_selected = function(moduletype) {
   $('#taskjob_'+moduletype+'_list')
      .find(".taskjob_item")
      .has('input[type=checkbox]:checked')
      .remove()
}

taskjobs.add_item = function (moduletype, itemtype, itemtype_name, rand_id) {
   item_id = $("#"+rand_id).val();
   item_name = $("#taskjob_moduleitems_dropdown .select2-chosen").text();
   if ( item_id > 0 ) {
      item_to_add = {
         'id' : itemtype + "-" + item_id,
         'name' : item_name
      }
      item_exists = $('#taskjob_' + moduletype + '_list').find('#'+item_to_add.id);
      if (item_exists.length == 0) {
         // Append the element to the list input
         // TODO: replace this with an ajax call to taskjobview class.
         $('#taskjob_' + moduletype + '_list')
            .append(
               "<div class='taskjob_item new' id='" + item_to_add.id + "'"+
               //"  onclick='$(this).children(\"input[type=checkbox]\").trigger(\"click\")'"+
               "  >" +
               "  <input type='checkbox'>" +
               "  </input>" +
               "  <span class='"+itemtype+"'></span>"+
               "  <label>"+
               "     <span style='font-style:oblique'>" + itemtype_name +"</span>" +
               "     "+ item_to_add.name +
               "  </label>"+
               "  <input type='hidden' name='"+moduletype+"[]' value='"+item_to_add.id+"'>" +
               "  </input>" +
               "</div>"
            );
      } else {
         item_exists.fadeOut(100).fadeIn(100);
      }
   }
}

taskjobs.show_moduletypes = function(ajax_url, moduletype) {
   taskjobs.hide_moduletypes_dropdown();
   taskjobs.hide_moduleitems_dropdown();
   $.ajax({
      url: ajax_url,
      data: {
         "moduletype" : moduletype,
         "method" : $('#method_selected').text()
      },
      success: function( data, textStatus, jqXHR) {
         taskjobs.show_moduletypes_dropdown( data )
      }
   });
}

taskjobs.show_moduleitems = function(ajax_url, moduletype, itemtype) {
   taskjobs.hide_moduleitems_dropdown();
   $.ajax({
      url: ajax_url,
      data: {
         "itemtype" : itemtype,
         "moduletype" : moduletype,
         "method" : $('#method_selected').text()
      },
      success: function( data, textStatus, jqXHR) {
         taskjobs.show_moduleitems_dropdown( data )
      }
   });
}

// Taskjobs logs refresh

taskjobs.Queue = $({
   refresh : 0,
   timer : null
});

taskjobs.update_logs = function (data) {
   $("#joblogs_block").html(data);
}

taskjobs.get_logs = function( ajax_url, task_id ) {
   console.log(ajax_url + ", " + task_id);
   console.log(taskjobs.Queue.refresh);
   $.ajax({
      url: ajax_url,
      data: {
         "task_id" : task_id
      },
      success: function( data, textStatus, jqXHR) {
         taskjobs.update_logs(data);
      },
      complete: function( ) {
         taskjobs.Queue.queue("refresh_logs").pop();
      }
   });
}

taskjobs.update_logs_timeout = function( ajax_url, task_id , refresh_id) {

   console.log(refresh_id);
   console.log('refresh_id: ' + refresh_id);
   var refresh = $("#" + refresh_id).val();

   $("#"+ refresh_id)
      .off("change","*")
      .on("change", function() {
         console.log("changing timer");
         taskjobs.update_logs_timeout( ajax_url, task_id, refresh_id )
      }
   );

   window.clearTimeout(taskjobs.Queue.timer);
   taskjobs.queue_refresh_logs(ajax_url, task_id);

   if (refresh != 'off') {
      taskjobs.Queue.refresh = refresh * 1000;
      taskjobs.Queue.timer = window.setInterval(
         function () {
            taskjobs.queue_refresh_logs(ajax_url, task_id);
            //taskjobs.update_logs_timeout(ajax_url, task_id, timeout_id);
         },
      taskjobs.Queue.refresh);
   } else {
      window.clearTimeout(taskjobs.Queue.timer);
   }
}


taskjobs.queue_refresh_logs = function (ajax_url, task_id) {
   var n = taskjobs.Queue.queue('refresh_logs');

   console.log(n);
   if (n.length == 0 ) {
      taskjobs.Queue.queue('refresh_logs', function( ) {
         taskjobs.get_logs(ajax_url, task_id);
      })
      taskjobs.Queue.queue('refresh_logs')[0]();
   }
}
