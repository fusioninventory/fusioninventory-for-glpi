use Locale::PO;
use Data::Dumper;
use IO::File;

if (not defined $ARGV[0]) {
   print "Error !
Use : perl create_pofile.pl lang
lang is de_DE, fr_FR...
exiting...\n";
exit;
}

my $file = '../locales/en_GB.php';
my $fh = IO::File->new($file,'<')
   or die "can't open file";
my @enlines = $fh->getlines;
$fh->close();

my @href;

$po = new Locale::PO(-msgid=>'', -msgstr=>
         "Project-Id-Version: FusionInventory\\n" .
         "Report-Msgid-Bugs-To: http://forge.fusioninventory.org\\n" .
         "POT-Creation-Date: 2011-08-21 22:45+0200\\n" .
         "PO-Revision-Date: 2011-08-21 22:45+0200\\n" .
         "Last-Translator: promotux <d.durieux@siprossii.com>\\n" .
         "Language-Team: FusionInventory mailling list i18n <fusioninventory-i18n@lists.alioth.debian.org>\\n" .
         "MIME-Version: 1.0\\n" .
         "Content-Type: text/plain; charset=UTF-8\\n" .
         "Content-Transfer-Encoding: 8bit\\n" .
         "Language: ".$ARGV[0]."\\n");
push @href, $po;

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
      my $translated = getTranslatedtext(@split[0]);
      $po->msgstr($translated);
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
   return " ";
}