<?php
/* Retrieve data from Sqlite Database */
$dbh = new PDO('sqlite:inventory.sqlite3');
$res = $dbh->query("SELECT * FROM changeslog ORDER BY time DESC LIMIT 0,10");
$lastChanges = array();
while ($row = $res->fetch())
{
    $lastChanges[$row['idchange']]['idmachine'] = $row['idmachine'];
    $lastChanges[$row['idchange']]['nbAddedSections'] = $row['nbAddedSections'];
    $lastChanges[$row['idchange']]['nbRemovedSections'] = $row['nbRemovedSections'];
    $lastChanges[$row['idchange']]['time'] = $row['time'];
}
//data to XML
ob_start();
foreach($lastChanges as $iditem => $item)
{
    echo "<item>";
    echo "  <title>Machine {$item['idmachine']}</title>";
    echo "  <description>Number of: Added sections=>{$item['nbAddedSections']} Removed sections=>{$item['nbRemovedSections']}</description>";
    $date = date('D, d M Y H:i:s O', $item['time']);
    echo "  <pubDate>$date</pubDate>";
    echo "  <guid>http://localhost/FusionLib/user/applications/MyWebSite/index.php?idmachine={$item['idmachine']}</guid>";
    echo "</item>";
}
$items = ob_get_contents();
ob_end_clean();

/* RSS data */
$rss = <<<RSS
<?xml version="1.0" encoding="ISO-8859-15"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>RSS FusionLibServer</title>
        <link>http://fusioninventory.org</link>
        <description>FusionLib Inventory RSS</description>
        <pubDate>'.date('D, d M Y H:i:s O').'</pubDate>
        <atom:link href="rss.php" rel="self" type="application/rss+xml" />
        $items
    </channel>
</rss>
RSS;

header('Content-Type: text/xml');
header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');

echo $rss;

?>