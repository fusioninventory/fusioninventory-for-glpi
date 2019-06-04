#!/usr/bin/php
<?php
// Generate snmpwalk of unknown devices
// requires: snmpwalk from Net-SNMP
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

$snmpwalkCmd = "snmpwalk";
// $snmpwalkCmd = "ssh 192.168.14.128 --";
// you can launch snmpwalk on a remote machine through ssh:
// tunnel="0",command="/usr/bin/snmpwalk $SSH_ORIGINAL_COMMAND" ssh-rsa AAAAB3NzaC1yc2EAAAABIwAAAQEA0FqlGjmx6IxuPihc1B1zN1gTnGZoQs1SenUaRkmUD+gbUWbfhUBbPRJIIFicNjsr6toerAQM/YKfZnmYG5BnYKgJPbKdkpdrSTMwSUqccDFH8tu6lIoRFiqZgajIznUls3Mhz5B4JXErapbQN/7cWnpvuG8vdZu56N19T0/gYdlTf8a71liva20zBk+y+pdWWsd4l2zBLm6tkmMWqYL/Xj/jY92gLzY0Dm0IFiBfV9gk4UBWh6jycLBZGbdqx25XqK8L8Ob9oyhJhtsCNeft1c6xzNAM21WYH4/trtwYgHaEA1LLN4IbY9+lJfJamN9ii4acyfMz/J+lQsnw3yTlOw== root@mysnmpmachine

$outputDir = GLPI_PLUGIN_DOC_DIR.'/fusioninventory/walk';

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
  glpi_plugin_fusinvsnmp_unmanageds,
  glpi_networkports,
  glpi_plugin_fusioninventory_configsecurities
WHERE
  glpi_plugin_fusinvsnmp_unmanageds.plugin_fusioninventory_snmpmodels_id<1
 AND
  sysdescr IS NOT NULL
 AND
  glpi_networkports.itemtype='PluginFusioninventoryUnmanaged'
 AND
  glpi_networkports.items_id=plugin_fusioninventory_unmanageds_id
 AND
  length(glpi_networkports.ip)>1
 AND
  glpi_plugin_fusioninventory_configsecurities.id=glpi_plugin_fusinvsnmp_unmanageds.plugin_fusinvsnmp_configsecurities_id

";
$result = $DB->query($sql);
while ($host=$DB->fetchArray($result)) {

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

   $cmd = sprintf("%s -v %s -t 30 -Cc -c %s %s .1", $snmpwalkCmd, $snmpversion, $host['community'], $host['ip']);
   // print $cmd."\n";
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
