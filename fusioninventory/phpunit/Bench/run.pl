use threads;
use threads::shared;

my $overload : shared = 0;
my @threads;

@afiles = <xml/*>;

$nb_xml = 0;
for ($f=2; $f < 1000; $f++) {
   $overload = 0;
$f = 4;
   $nb_xml += $f;
   my @threads;
   print "========== ".$f." inventories ========== ".$nb_xml."\n";
   
   for ($g=0; $g < $f; $g++) {
      $file = pop @afiles;
print $file."\n";
      push @threads, threads->new(\&sendinventory, $file);
   }
   $_->join foreach @threads;
   if ($overload == 0) {
      print "passed...\n";
   } else {
      print "The limit is ".($f -1)." simultaneous inventories\n";
      $f = 1000;
   }
}



sub sendinventory {

   $result = `php import.php $_[0]`;
print $result;
   if (index($result, 'SERVER OVERLOADED') != -1) {
      $overload++;
   }
   
}
