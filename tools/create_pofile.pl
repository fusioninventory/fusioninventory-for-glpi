use Locale::PO;
use Data::Dumper;
use IO::File;

if (not defined $ARGV[0]) {
   print "Error !
Use : perl create_pofile.pl lang
lang is de_DE, fr_FR...
exiting...\n";
}

my $file = '../locales/en_GB.php';
my $fh = IO::File->new($file,'<')
   or die "can't open file";
my @enlines = $fh->getlines;
$fh->close();

my @href;
foreach my $line (@enlines) {
   if ( $line =~ "LANG") {
      $line =~ s/\]( )+=( )+/\]=/;
      @split = split /=/ , $line;
      @splitref = split /'/, $split[0];
      $po = new Locale::PO();
      @stext = split /;/ , @split[1];
      $text = @stext[0];
      $text =~ s/^"//;
      $text =~ s/^ "//;
      $text =~ s/"$//;
      $po->msgid($text);
      my $number = @splitref[4];
      $number =~ s/\]//g;
      $number =~ s/\[//g;
      $po->reference(@splitref[3]."|".$number);
      $po->msgstr(getTranslatedtext(@split[0]));
      push @href, $po;
   }
}

#print Dumper(\@href);
Locale::PO->save_file_fromarray($ARGV[0].".po",\@href);

sub getTranslatedtext {
   my ($reflang) = @_;

   my $file = '../locales/'.$ARGV[0].'.php';
   my $fh = IO::File->new($file,'<');
   if (not defined $fh) {
      return "";
   }
   my @delines = $fh->getlines;
   $fh->close();

   $reflang =~ s/\]/\\]/g;
   $reflang =~ s/\[/\\[/g;
   $reflang =~ s/\$//g;
   foreach my $line (@delines) {
      if ($line =~ ($reflang)) {
         @split = split /=/ , $line;
         @splitref = split /'/, $split[0];
         @stext = split /;/ , @split[1];
         $text = @stext[0];
         $text =~ s/^"//;
         $text =~ s/^ "//;
         $text =~ s/"$//;
         return $text;
      }
   }
   return "";
}