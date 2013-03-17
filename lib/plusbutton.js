function plusbutton(dom_id, clone_tag) {

   // return immediately if no dom_id (this is an error)
   if ( typeof dom_id == 'undefined' ) {
      return;
   }

   // set to null if no clone_tag is defined
   if ( typeof clone_tag == 'undefined' ) {
      clone_tag = null;
   }

   // clone the underlying tag in the dom_id selected
   if (clone_tag != null) {
      var root=document.getElementById(dom_id);
      if (root.style.display == 'block') {
         var clone=root.getElementsByTagName(clone_tag)[0].cloneNode(true);
         root.appendChild(clone);
         clone.style.display = 'block';
      }
   }
   //show block associated to plus button
   Ext.get(dom_id).setDisplayed('block');

   //remove all border to previous selected item (remove classes)
   Ext.select('table.package_item_list tr.selected ').removeClass('selected');
}

