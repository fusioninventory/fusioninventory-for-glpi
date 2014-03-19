var taskjobs = {}

taskjobs.urls = {
   "create"    : "/plugins/fusioninventory/ajax/taskjob_form.php",
   "edit"      : "/plugins/fusioninventory/ajax/taskjob_form.php",
   "targets"   : "/plugins/fusioninventory/ajax/taskjob_targets.php",
   "actors"    : "/plugins/fusioninventory/ajax/taskjob_actors.php"
}

taskjobs.showForm = function( data, textStatus, jqXHR) {
   $('#taskjobs_form')
      .html(data);
}

taskjobs.hideForm = function () {
   $('#taskjobs_form')
      .html('');
}

taskjobs.create = function(plugin_url, task_id) {
   $.ajax({
      url: plugin_url + taskjobs.urls.create,
      data: {
         "task_id" : task_id
      },
      success: taskjobs.showForm
   })
}

taskjobs.edit = function(plugin_url, taskjob_id) {
   $.ajax({
      url: plugin_url + taskjobs.urls.edit,
      data: {
         "id" : taskjob_id
      },
      success: taskjobs.showForm
   })
}

taskjobs.showTargets = function(plugin_url, taskjob_id, module) {
   $.ajax({
      url: plugin_url + taskjobs.urls.targets,
      data: {
         "id" : taskjob_id,
         "module" : module
      },
      success: showTargetForm
   });
}

taskjobs.showActors = function(plugin_url, taskjob_id, module) {
   $.ajax({
      url: plugin_url + taskjobs.urls.actors,
      data: {
         "id" : taskjob_id,
         "module" : module
      },
      success: showTargetForm
   });
}
