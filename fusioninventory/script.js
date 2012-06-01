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

function showHideElement(id,display,img_name,img_src_close,img_src_open) {
	//safe function to hide an element with a specified id
	if (document.getElementById) { // DOM3 = IE5, NS6
      e = document.getElementById(id);
		if (e.style.display == 'none')
		{
			e.style.display = display;
			if (img_name!=''){
				document[img_name].src=img_src_open;
			}
		}
		else
		{
			e.style.display = 'none';
			if (img_name!=''){
				document[img_name].src=img_src_close;
			}
		}

	}
	else {
		if (document.layers) { // Netscape 4
			if (document.id.display == 'none')
			{
				document.id.display = display;
				if (img_name!=''){
					document[img_name].src=img_src_open;
				}
			}
			else
			{
				document.id.display = 'none';
				if (img_name!=''){
					document[img_name].src=img_src_close;
				}
			}
		}
		else { // IE 4
			if (document.all.id.style.display == 'none')
			{
				document.all.id.style.display = display;
				if (img_name!=''){
					document[img_name].src=img_src_close;
				}
			}
			else
			{
				document.all.id.style.display = 'none';
				if (img_name!=''){
					document[img_name].src=img_src_close;
				}
			}
		}
	}
}
