/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */
var fifooter = "<br/> \
<a class='copyright' href='http://fusioninventory.org/'> \
FusionInventory 9.3+1.1 - Copyleft \
<span style='display:inline-block;transform: rotate(180deg);font-size: 12px;'>&copy;</span> \
2010-2018 by FusionInventory Team \
</a>";

$(window).bind("load", function() {
   $('#footer').css('height', 'auto');
   $("#footer td.right").append(fifooter);
});
