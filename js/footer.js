var fifooter = "<div id='footer'> \
<table width='100%'>  \
<tr> \
<td class='right'> \
<a class='copyright' href='http://fusioninventory.org/'> \
FusionInventory 9.1+1.0 - Copyleft \
<span style='display:inline-block;transform: rotate(180deg);font-size: 12px;'>&copy;</span> \
2010-2016 by FusionInventory Team \
</a> \
</td> \
</tr> \
</table> \
</div>"

$(window).bind("load", function() { $("#footer").after(fifooter); })

