var taskjobs = {}

taskjobs.showForm = function( data, textStatus, jqXHR) {
   $('#taskjobs_form')
      .html(data)
      .show();
}

taskjobs.hideForm = function () {
   $('#taskjobs_form')
      .html('')
      .hide();
}

taskjobs.create = function(form_url, options) {
   $.ajax({
      url: form_url,
      data: options,
      success: taskjobs.showForm
   })
}

taskjobs.edit = function(form_url, options) {
   $.ajax({
      url: form_url,
      data: options,
      success: taskjobs.showForm
   })
}
