use Locale::PO;
use Data::Dumper;
use IO::File;
use Path::Class;

if (not defined $ARGV[1]) {
   print "Error !
Use : perl create_glpilangfilefrompo.pl pluginname 0/1
pluginname => fusioninventory, fusinvsnmp, fusinvinventory
O/1 => complete file with english(1) or keep empty (0) for word not translated
exiting...\n";
   exit;
}


my $user = 'ddurieux';
my $password = '';
my $transifexhost = 'https://www.transifex.net/';
my $folder;
if ($ARGV[0] eq "fusioninventory") {
   $folder = "Plugin_FusionInventory";
} elsif ($ARGV[0] eq "fusinvsnmp") {
   $folder = "Plugin_FusinvSNMP";
} elsif ($ARGV[0] eq "fusinvinventory") {
   $folder = "Plugin_Fusinvinventory";
} elsif ($ARGV[0] eq "fusinvdeploy") {
   $folder = "Plugin_Fusinvdeploy";
}

my $remoteurltranslations = 'https://www.transifex.net/projects/p/FusionInventory/resource/'.$folder.'/';


`tx init --user=$user --pass=$password --host=$transifexhost`;
`tx set --auto-remote $remoteurltranslations`;
`tx pull -a`;

my $dir  = dir('translations/FusionInventory.'.$folder);
my @filesdir = $dir->children;

foreach my $filename (@filesdir) {

   my $aref = Locale::PO->load_file_ashash($filename);

   my $file = $filename.'.php';
   $file =~ s/.po//;
   $file =~ s/translations\/FusionInventory.$folder\///;
   my $fh = IO::File->new($file,'>')
      or die "can't open file";
   $fh->print("<?php\n");
   $fh->print("
/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   \@package   FusionInventory
   \@author    David Durieux
   \@co-author 
   \@comment   Not translate this file, use https://www.transifex.net/projects/p/FusionInventory/
   \@copyright Copyright (c) 2010-2012 FusionInventory team
   \@license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   \@link      http://www.fusioninventory.org/
   \@link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   \@since     2010
 
   ------------------------------------------------------------------------
 */

");
   my @lines;
   while (my ($key, $po) = each %{$aref}) {
      @split = split /\|/ , $po->reference;
      if (@split > 1) {
         if ($po->msgstr eq '""' && $ARGV[1] eq '1') {
            push @lines, "\$LANG['plugin_".$ARGV[0]."']['".$split[0]."'][".$split[1]."]=".$po->msgid.";\n";
         } else {
            push @lines, "\$LANG['plugin_".$ARGV[0]."']['".$split[0]."'][".$split[1]."]=".$po->msgstr.";\n";
         }
      }
   }
   my @out = sort @lines;
   my $before = '';
   foreach my $line (@out) {
      my @split = split /'/, $line;
      if ($split[3] ne $before) {
         $fh->print("\n");
      }
      $fh->print($line);
      $before = $split[3];
   }
   $fh->print("?>\n");
   $fh->close();
}
`rm -fr .tx`;
`rm -fr translations`;
