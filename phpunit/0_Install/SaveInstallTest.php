<?php

class SaveInstallTest extends BaseTestCase {

   public function should_restore_install() {
      return FALSE;
   }
   public function testSaveInstallation() {
      global $DB;
      $mysqldump_cmd = array('mysqldump');

      $mysqldump_cmd[] = "--opt";
      $mysqldump_cmd[] = "-h ".$DB->dbhost;

      $mysqldump_cmd[] = "-u ".$DB->dbuser;

      if (!empty($DB->dbpassword)) {
         $mysqldump_cmd[] = "-p'".urldecode($DB->dbpassword)."'";
      }

      $mysqldump_cmd[] = $DB->dbdefault;

      $output = shell_exec(
         implode(' ', $mysqldump_cmd)
      );
      $this->assertNotNull($output, print_r(implode(' ', $mysqldump_cmd),TRUE));
      $dumpfile = fopen("./save.sql", "w+");
      fwrite($dumpfile, $output);
      fclose($dumpfile);

      $this->assertFileExists("./save.sql");
      $length = stat("./save.sql")[7];
      $this->assertGreaterThan(0, $length);
   }
}
