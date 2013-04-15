#!/usr/bin/php
<?php
# Generate snmpwalk of unknown devices
# requires: snmpwalk from Net-SNMP
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

define('GLPI_ROOT', '../../..');
require_once (GLPI_ROOT . "/inc/includes.php");

$snmpwalkCmd = "snmpwalk";

$outputDir = GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/walk';

if (mkdir($outputDir) && !is_dir($outputDir)) {
  echo("Failed to create $outputDir\n");
  exit(1);
}

$t = system(sprintf("%s --version", $snmpwalkCmd));
if (!strncmp("/NET-SNMP/", $t, 7)) {
   echo "[error] snmpwalk not found. please install snmpwalk from Net-SNMP package\n";
   exit(1);
}

$sql = "

SELECT
  DISTINCT(ip),sysdescr,snmpversion,community
FROM
  glpi_plugin_fusinvsnmp_unknowndevices,
  glpi_networkports,
  glpi_plugin_fusinvsnmp_configsecurities
WHERE
  glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_models_id<1
 AND
  sysdescr IS NOT NULL
 AND
  glpi_networkports.itemtype='PluginFusioninventoryUnknownDevice'
 AND
  glpi_networkports.items_id=plugin_fusioninventory_unknowndevices_id
 AND
  length(glpi_networkports.ip)>1
 AND
  glpi_plugin_fusinvsnmp_configsecurities.id=glpi_plugin_fusinvsnmp_unknowndevices.plugin_fusinvsnmp_configsecurities_id

";
$result = $DB->query($sql);
while ($host=$DB->fetch_array($result)) {

   $filePath = sprintf("%s/%s-%s.walk", $outputDir, $host['ip'], preg_replace('/[^a-zA-Z0-9,_-]/', '_', $host['sysdescr']));

   switch ($host['snmpversion']) {
      case 1:
         $snmpversion = '1';
         break;
      case 2:
         $snmpversion = '2c';
         break;
      default:
         printf("unsupported SNMP version: '%s'\n", $host['snmpversion']);
         continue;
   }

   $cmd = sprintf("%s -v %s -Cc -On -c %s %s .1", $snmpwalkCmd, $snmpversion, $host['community'], $host['ip']);
#   print $cmd."\n";
   printf("---\nscanning %s\n", $host['ip']);

   $fileFd = fopen($filePath, "w");
   $cmdFd = popen($cmd, "r");

   while (!feof($cmdFd)) {
      fwrite($fileFd, fgets($cmdFd));
   }
   print "done\n";

   pclose($cmdFd);
   fclose($fileFd);

   $st = stat($filePath);
   if ($st['size'] == 0) {
      unlink($filePath);
   } else {
      printf("file %s generated\n", $filePath);
   }

}
