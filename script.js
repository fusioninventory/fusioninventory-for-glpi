function getSlide(id) {
   var displayValue =  document.getElementById(id).style.display;

   if( displayValue=='none') {

      Ext.get(id).slideIn('t',
      {
         duration: .5,
         remove: false,
         useDisplay: true
      });

   } else{


         Ext.get(id).slideOut('t',
         {
            duration: .5,
            remove: false,
            useDisplay: true
         });

   }

}
