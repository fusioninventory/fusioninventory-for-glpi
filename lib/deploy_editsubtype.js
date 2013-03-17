function edit_subtype(subtype, order_id, _rand,el) {

   // get parent row of the selected element
   row = jQuery(el).parents('tr:first')

   //remove all border to previous selected item (remove classes)
   Ext.select('#table_' + subtype + '_'+ _rand +' tr').removeClass('selected');

   //add border to selected index (add class)
   //Ext.select('#table_' + subtype + '_'+ _rand +' tr:nth-child('+(index+1)+')').addClass('selected');
   row.addClass('selected');

   //scroll to edit form
   document.getElementById('th_title_' + subtype + '_' + _rand).scrollIntoView();

   //show and load form
   Ext.get(subtype + 's_block' + _rand).setDisplayed('block');
   Ext.get(subtype + 's_block' + _rand).load({
      'url': '../ajax/deploypackage_form.php',
      'scripts': true,
      'params' : {
         'subtype': subtype,
         'index': row.index(),
         'orders_id': order_id,
         'rand': _rand
      }
   });

   //change plus button behavior
   //(for always have possibility to add an item also in edit mode)
   Ext.get('plus_' + subtype + 's_block' + _rand).on('click', function() {
      //empty sub value
      Ext.fly('show_'+ subtype + '_value' + _rand).update('');

      //replace type select
      Ext.get('show_' + subtype + '_type' + _rand).load({
         'url': '../ajax/deploypackage_form.php',
         'scripts': true,
         'params' : {
            'subtype': subtype,
            'orders_id': order_id,
            'rand': _rand
         }
      });
   });
}

