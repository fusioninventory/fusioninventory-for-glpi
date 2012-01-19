ALTER TABLE `glpi_plugin_fusinvdeploy_actions_commands`
   MODIFY COLUMN `exec` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `glpi_plugin_fusinvdeploy_actions_copies`
   MODIFY COLUMN `from` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   MODIFY COLUMN `to` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `glpi_plugin_fusinvdeploy_actions_moves`
   MODIFY COLUMN `from` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
   MODIFY COLUMN `to` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `glpi_plugin_fusinvdeploy_actions_deletes`
   MODIFY COLUMN `path` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `glpi_plugin_fusinvdeploy_actions_mkdirs`
   MODIFY COLUMN `path` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `glpi_plugin_fusinvdeploy_checks`
   MODIFY COLUMN `path` VARCHAR(1024)  CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
