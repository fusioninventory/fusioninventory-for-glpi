function plusbutton(dom_id, template) {

   // return immediately if no dom_id (this is an error)
   if ( typeof dom_id == 'undefined' ) {
      return;
   }

   // set to null if no template is defined
   if ( typeof template == 'undefined' ) {
      template = null;
   }

   // append a clone of the selected template class under the selected dom_id
   if (template != null) {
      // get old width before destroy
      var old_width = $(template)
         .find('.select2-container')
         .css('width');

      /**
       * Since GLPI 0.85, select tag are wrapped with the jQuery plugin select2 and the cloned
       * select doesn't respond if they are cloned with the select2 DOM mutations.
       * The following links, explain we must disable the select2 DOM mutations in order to clone
       * the select node correctly:
       *    http://stackoverflow.com/questions/17175534/clonned-select2-is-not-responding
       *    http://jsfiddle.net/ZzgTG/
       */
      $(template)
         .find('select')
         .select2('destroy');

      $('#' + dom_id)
         .append(
            $(template)
            .clone()
               .removeClass('template')
               .css('display', 'block')
         );

      // re-enable all select
      $('#' + dom_id)
         .find('select')
         .select2({
            'width': old_width
         });
   }
}

