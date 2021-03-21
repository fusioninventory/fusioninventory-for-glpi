var fifooter = "<br/> \
<a class='copyright' href='http://fusioninventory.org/'> \
FusionInventory 9.5+3.0 - Copyleft \
<span style='display:inline-block;transform: rotate(180deg);font-size: 12px;'>&copy;</span> \
2010-2019 by FusionInventory Team \
</a>";

$(window).bind("load", function() {
   $('#footer').css('height', 'auto');
   $("#footer td.right").append(fifooter);
});
