use Locale::PO;
use Data::Dumper;
use IO::File;

if (not defined $ARGV[1]) {
   print "Error !
Use : perl create_glpilangfilefrompo.pl pluginname po.file
exiting...\n";
}

my $aref = Locale::PO->load_file_ashash($ARGV[1]);

my $file = $ARGV[1].'.php';
my $fh = IO::File->new($file,'>')
   or die "can't open file";
$fh->print("<?php\n");
my @lines;
while (my ($key, $po) = each %{$aref}) {
   @split = split /\|/ , $po->reference;
   push @lines, "\$LANG['plugin_".$ARGV[0]."']['".$split[0]."'][".$split[1]."]=".$po->msgstr.";\n";
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
