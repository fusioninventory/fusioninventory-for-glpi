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


