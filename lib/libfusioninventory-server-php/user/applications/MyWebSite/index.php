<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="Style/Style2.css">
<link rel="alternate" type="application/rss+xml" href="rss.php" />
</head>

<body>

<div id="title"><a href='index.php'><img src="Style/images/logo4.png" /></a></div>

<div id="container">
<div id="menu">
    <?php $dbh = new PDO('sqlite:inventory.sqlite3');
    $res = $dbh->query("SELECT * FROM machine");
    while ($row = $res->fetch()) {
        echo "<div><a href='?idmachine={$row['idmachine']}'>Machine {$row['idmachine']}</a></div> <br />";
    } ?>
</div>
</div>

<div id="content">
<?php
if (isset($_GET['idmachine']))
{
$dbh = new PDO('sqlite:inventory.sqlite3');
$idmachine = $_GET['idmachine'];
$res = $dbh->query("SELECT * FROM section WHERE idmachine=$idmachine");
echo "<fieldset>";
echo "<legend>Machine $idmachine</legend>";
echo "<dl>";
while ($row = $res->fetch())
{
    echo "<dt>{$row['sectionName']}";
    echo "<dd><blockquote>{$row['sectionData']}</blockquote>";
}
echo "</dl>";
echo "</fieldset>";

} else {
    echo '<a href="rss.php">Rss</a>';
} ?>
</div>
</body>
</html>
