#!/usr/local/bin/perl -w

$file = "tracker_fullsync.log";

open (LOGSOURCE, $file);
while(defined($ligne = <LOGSOURCE>))
{
	$_ = $ligne;
	/\[(.{11})(.*)\]\[(.{4,17})\](.*)/;
	if (exists $dates_start{$3})
	{
		$dates_end{$3} = $2;
	}
	else
	{
		$dates_start{$3} = $2;
	}
	open (FILE, ">>$3.log");
	print FILE $ligne;
	close (FILE);
}
close (LOGSOURCE);

open (FILE, ">resume.txt");
while (($ip, $start) = each %dates_start)
{
	print FILE "$ip => $start => ".$dates_end{$ip}."\n";

}
close (FILE);