# /**
#  * ---------------------------------------------------------------------
#  * FusionInventory plugin for GLPI
#  * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
#  *
#  * http://fusioninventory.org/
#  * ---------------------------------------------------------------------
#  *
#  * LICENSE
#  *
#  * This file is part of FusionInventory plugin for GLPI.
#  *
#  * This program is free software: you can redistribute it and/or modify
#  * it under the terms of the GNU Affero General Public License as
#  * published by the Free Software Foundation, either version 3 of the
#  * License, or (at your option) any later version.
#  *
#  * This program is distributed in the hope that it will be useful,
#  * but WITHOUT ANY WARRANTY; without even the implied warranty of
#  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
#  * GNU Affero General Public License for more details.
#  *
#  * You should have received a copy of the GNU Affero General Public License
#  * along with this program.  If not, see <https://www.gnu.org/licenses/>.
#  * ---------------------------------------------------------------------
#  */
use File::Slurp;
use Data::Dumper;

my $file = read_file("../locales/glpi.pot");


my @lines = split("\n", $file);


my $toRemove = 0;
my $text = '';
my $newFile;
foreach my $line(@lines) {
   if ($line =~ /msgctxt/
        && $line =~ /fusioninventory/) {
      # No add this msgctxt
   } elsif ($line =~ /msgctxt/) {
      $toRemove = 1;
   } else {
      $text .= $line."\n";
   }

   if ($line eq '') {
      if ($toRemove == 0) {
         $newFile .= $text;
      }
      $text = '';
      $toRemove = 0;
   }
}

open (MYFILE, ">../locales/glpi.pot");
print MYFILE $newFile;
close (MYFILE);


