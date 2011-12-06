
DROP TABLE IF EXISTS `glpi_display`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_display` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `num` smallint(6) NOT NULL DEFAULT '0',
  `rank` smallint(6) NOT NULL DEFAULT '0',
  `FK_users` int(11) NOT NULL DEFAULT '0' COMMENT 'RELATION to glpi_users (ID)',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `display` (`type`,`num`,`FK_users`),
  KEY `rank` (`rank`),
  KEY `num` (`num`),
  KEY `FK_users` (`FK_users`)
) ENGINE=MyISAM AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_display`
--

LOCK TABLES `glpi_display` WRITE;
/*!40000 ALTER TABLE `glpi_display` DISABLE KEYS */;
INSERT INTO `glpi_display` VALUES (32,1,4,4,0),(34,1,6,6,0),(33,1,5,5,0),(31,1,8,3,0),(30,1,23,2,0),(86,12,3,1,0),(49,4,31,1,0),(50,4,23,2,0),(51,4,3,3,0),(52,4,4,4,0),(44,3,31,1,0),(38,2,31,1,0),(39,2,23,2,0),(45,3,23,2,0),(46,3,3,3,0),(63,6,4,3,0),(62,6,5,2,0),(61,6,23,1,0),(83,11,4,2,0),(82,11,3,1,0),(57,5,3,3,0),(56,5,23,2,0),(55,5,31,1,0),(29,1,31,1,0),(35,1,3,7,0),(36,1,19,8,0),(37,1,17,9,0),(40,2,3,3,0),(41,2,4,4,0),(42,2,11,6,0),(43,2,9,7,0),(47,3,4,4,0),(48,3,9,6,0),(53,4,9,6,0),(54,4,7,7,0),(58,5,4,4,0),(59,5,9,6,0),(60,5,7,7,0),(64,7,3,1,0),(65,7,4,2,0),(66,7,5,3,0),(67,7,6,4,0),(68,7,9,5,0),(69,8,9,1,0),(70,8,3,2,0),(71,8,4,3,0),(72,8,5,4,0),(73,8,10,5,0),(74,8,6,6,0),(75,10,4,1,0),(76,10,3,2,0),(77,10,5,3,0),(78,10,6,4,0),(79,10,7,5,0),(80,10,11,6,0),(84,11,5,3,0),(85,11,6,4,0),(88,12,6,2,0),(89,12,4,3,0),(90,12,5,4,0),(91,13,3,1,0),(92,13,4,2,0),(93,13,7,3,0),(94,13,5,4,0),(95,13,6,5,0),(96,15,3,1,0),(98,15,5,3,0),(99,15,6,4,0),(100,15,7,5,0),(101,17,3,1,0),(102,17,4,2,0),(103,17,5,3,0),(104,17,6,4,0),(105,2,40,5,0),(106,3,40,5,0),(107,4,40,5,0),(108,5,40,5,0),(109,15,8,6,0),(110,23,31,1,0),(111,23,23,2,0),(112,23,3,3,0),(113,23,4,4,0),(114,23,40,5,0),(115,23,9,6,0),(116,23,7,7,0),(117,27,16,1,0),(118,22,31,1,0),(119,29,4,1,0),(120,29,3,2,0),(121,35,80,1,0),(122,6,72,4,0),(123,6,163,5,0),(124,5150,3,1,0),(125,5150,4,2,0),(126,5150,6,3,0),(127,5150,7,4,0),(128,5150,8,5,0),(129,5151,3,1,0),(130,5151,5,2,0),(131,5152,3,1,0),(132,5152,4,2,0),(133,5152,5,3,0),(134,5152,6,4,0),(135,5152,7,5,0),(136,5152,8,6,0),(137,5152,9,7,0),(138,5152,10,8,0),(139,5153,2,1,0),(140,5153,4,2,0),(141,5153,5,3,0),(142,5153,6,4,0),(143,5153,7,5,0),(144,5153,8,6,0),(145,5157,2,1,0),(146,5157,3,2,0),(147,5157,4,3,0),(148,5157,5,4,0),(149,5157,6,5,0),(150,5157,7,6,0),(151,5157,8,7,0),(152,5157,9,8,0),(153,5157,10,9,0),(154,5157,11,10,0),(155,5157,14,11,0),(156,5157,12,12,0),(157,5157,13,13,0),(158,5158,8,1,0),(159,5158,9,2,0),(160,5158,10,3,0),(161,5158,11,4,0),(162,5158,12,5,0),(163,5159,2,1,0),(164,5159,3,2,0),(165,5159,5,3,0),(166,5159,6,4,0),(167,5159,7,5,0),(168,5159,8,6,0),(169,5160,3,1,0),(170,5160,4,2,0),(171,5160,5,3,0),(172,5160,6,4,0),(173,5160,7,5,0),(174,5160,8,6,0),(175,5160,9,7,0),(176,5160,10,8,0),(177,5161,2,1,0),(178,5161,3,2,0),(179,5161,4,3,0),(180,5161,5,4,0),(181,5161,6,5,0),(182,5161,7,6,0),(183,5161,8,7,0),(184,5161,9,8,0),(185,5161,10,9,0),(186,5161,11,10,0),(187,5161,12,11,0),(188,5162,2,1,0),(189,5162,3,2,0),(190,5162,4,3,0),(191,5162,5,4,0),(192,5162,6,5,0),(193,5163,2,1,0),(194,5163,3,2,0),(195,2,5190,8,0),(196,2,5191,9,0),(197,2,5194,10,0),(198,2,5195,11,0),(199,1,5192,10,0),(200,1,5193,11,0),(201,5158,3,6,0),(202,5158,4,7,0),(203,5158,5,8,0),(204,5158,6,9,0),(205,5153,30,7,0);
/*!40000 ALTER TABLE `glpi_display` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_display_default`
--


DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_mib_label` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_mib_label`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_mib_label` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_mib_object`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_mib_object` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_mib_object`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_mib_object` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_object` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_mib_object` VALUES (1,'sysDescr',''),(2,'dot1dBasePortIfIndex',''),(3,'dot1dTpFdbAddress',''),(4,'dot1dTpFdbPort',''),(5,'entPhysicalModelName',''),(6,'IF-MIB::ifDescr',''),(7,'IF-MIB::ifIndex',''),(8,'IF-MIB::ifInErrors',''),(9,'IF-MIB::ifInOctets',''),(10,'IF-MIB::ifAdminStatus',''),(11,'IF-MIB::ifLastChange',''),(12,'IF-MIB::ifMtu',''),(13,'IF-MIB::ifOutErrors',''),(14,'IF-MIB::ifOutOctets',''),(15,'IF-MIB::ifPhysAddress',''),(16,'IF-MIB::ifSpeed',''),(17,'IF-MIB::ifOpenStatus',''),(18,'IF-MIB::ifType',''),(19,'dot1dBaseBridgeAddress',''),(20,'sysName',''),(21,'SerialNumber',''),(22,'sysUpTime',''),(23,'ifNumber',''),(24,'cdpCacheAddress',''),(25,'cdpCacheDevicePort',''),(26,'cpmCPUTotal5sec',''),(27,'IF-MIB::ifName',''),(28,'ipAdEntAddr',''),(29,'ipNetToMediaPhysAddress',''),(30,'sysLocation',''),(31,'freeMem',''),(32,'processorRam',''),(33,'entPhysicalSerialNum',''),(34,'vlanTrunkPortDynamicStatus',''),(35,'vtpVlanName',''),(36,'entPhysicalFirmwareRev',''),(37,'cpmCPUTotal1min',''),(38,'hpSwitchCpuStat',''),(39,'hpLocalMemFreeBytes',''),(40,'hpSwitchlgmpPortVlanIndex2',''),(41,'hpLocalMemTotalBytes',''),(42,'dot1qPortAcceptableFrameType',''),(43,'dot1qVlanStaticName',''),(44,'prtMarkerSuppliesMaxCapacity',''),(45,'prtMarkerSuppliesLevel',''),(46,'Enterprise',''),(47,'IP-MIB::ipAdEntIfIndex',''),(48,'hrMemorySize',''),(49,'hrDeviceDescr',''),(50,'prtMarkerLifeCount',''),(51,'prtMarkerSuppliesMaxCapacitycyan',''),(52,'prtMarkerSuppliesLevelcyan',''),(53,'prtMarkerSuppliesMaxCapacitymagenta',''),(54,'prtMarkerSuppliesLevelmagenta',''),(55,'prtMarkerSuppliesMaxCapacityyellow',''),(56,'prtMarkerSuppliesLevelyellow',''),(57,'hrStorageSize',''),(58,'assetnumber',''),(59,'prtMarkerLifeCountBW',''),(60,'prtMarkerLifeCountColor',''),(61,'fuserUnit',''),(62,'transfertUnit',''),(63,'TotalCounterNB',''),(64,'TotalCounterC',''),(65,'Totalcounter',''),(66,'TotalCounterPrint',''),(67,'TotalCounterNBPrint',''),(68,'TotalCounterCPrint',''),(69,'TotalCounterCopy',''),(70,'TotalCounterNBCopy',''),(71,'TotalCounterCCopy',''),(72,'TotalCounterFax','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_mib_oid`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_mib_oid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_mib_oid` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_mib_oid`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_mib_oid` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_oid` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_mib_oid` VALUES (1,'.1.3.6.1.2.1.1.1.0',''),(2,'.1.3.6.1.2.1.17.1.4.1.2',''),(3,'.1.3.6.1.2.1.17.4.3.1.1',''),(4,'.1.3.6.1.2.1.17.4.3.1.2',''),(5,'.1.3.6.1.2.1.2.2.1.2',''),(6,'.1.3.6.1.2.1.2.2.1.1',''),(7,'.1.3.6.1.2.1.2.2.1.14',''),(8,'.1.3.6.1.2.1.2.2.1.10',''),(9,'.1.3.6.1.2.1.2.2.1.7',''),(10,'.1.3.6.1.2.1.2.2.1.9',''),(11,'.1.3.6.1.2.1.2.2.1.4',''),(12,'.1.3.6.1.2.1.2.2.1.20',''),(13,'.1.3.6.1.2.1.2.2.1.16',''),(14,'.1.3.6.1.2.1.2.2.1.6',''),(15,'.1.3.6.1.2.1.2.2.1.5',''),(16,'.1.3.6.1.2.1.2.2.1.8',''),(17,'.1.3.6.1.2.1.2.2.1.3',''),(18,'.1.3.6.1.2.1.17.1.1.0',''),(19,'.1.3.6.1.2.1.1.6.0',''),(20,'.1.3.6.1.4.1.43.29.4.18.2.1.7.1',''),(21,'.1.3.6.1.2.1.1.3.0',''),(22,'.1.3.6.1.2.1.2.1.0',''),(23,'.1.3.6.1.4.1.9.9.23.1.2.1.1.4',''),(24,'.1.3.6.1.4.1.9.9.23.1.2.1.1.7',''),(25,'.1.3.6.1.4.1.9.9.109.1.1.1.1.3.1',''),(26,'.1.3.6.1.2.1.47.1.1.1.1.13.1',''),(27,'.1.3.6.1.2.1.31.1.1.1.1',''),(28,'.1.3.6.1.2.1.4.20.1.1',''),(29,'.1.3.6.1.2.1.4.22.1.2',''),(30,'.1.3.6.1.4.1.9.2.1.8.0',''),(31,'.1.3.6.1.2.1.1.5.0',''),(32,'.1.3.6.1.4.1.9.3.6.6.0',''),(33,'.1.3.6.1.4.1.9.3.6.3.0',''),(34,'.1.3.6.1.4.1.9.9.46.1.6.1.1.14',''),(35,'.1.3.6.1.4.1.9.9.46.1.3.1.1.4.1',''),(36,'.1.3.6.1.2.1.47.1.1.1.1.10.1',''),(37,'.1.3.6.1.2.1.47.1.1.1.1.11.1',''),(38,'.1.3.6.1.2.1.47.1.1.1.1.11.1001',''),(39,'.1.3.6.1.2.1.47.1.1.1.1.9.1001',''),(40,'.1.3.6.1.2.1.47.1.1.1.1.13.1001',''),(41,'.1.3.6.1.2.1.47.1.1.1.1.9.1',''),(42,'.1.3.6.1.4.1.1991.1.1.2.1.52.0',''),(43,'.1.3.6.1.4.1.1991.1.1.2.1.55',''),(44,'.1.3.6.1.4.1.1991.1.1.2.1.54',''),(45,'.1.3.6.1.4.1.11.2.14.11.5.1.9.6.1.0',''),(46,'.1.3.6.1.4.1.11.2.36.1.1.2.5.0',''),(47,'.1.3.6.1.4.1.11.2.36.1.1.2.6.0',''),(48,'.1.3.6.1.4.1.11.2.14.11.5.1.1.2.1.1.1.6.1',''),(49,'.1.3.6.1.4.1.11.2.14.11.5.1.7.1.15.3.1.1',''),(50,'.1.3.6.1.4.1.11.2.14.11.5.1.1.2.1.1.1.5.1',''),(51,'.1.3.6.1.4.1.11.2.36.1.1.2.9.0',''),(52,'.1.3.6.1.2.1.17.7.1.4.5.1.2',''),(53,'.1.3.6.1.2.1.17.7.1.4.3.1.1',''),(54,'.1.3.6.1.2.1.43.11.1.1.8.1.1',''),(55,'.1.3.6.1.2.1.43.11.1.1.9.1.1',''),(56,'.1.3.6.1.2.1.43.9.2.1.8.1.1',''),(57,'.1.3.6.1.2.1.4.20.1.2',''),(58,'.1.3.6.1.2.1.25.2.2.0',''),(59,'.1.3.6.1.4.1.2699.1.2.1.2.1.1.2.1',''),(60,'.1.3.6.1.2.1.43.10.2.1.4.1.1',''),(61,'.1.3.6.1.4.1.674.10898.100.2.1.2.1.6.1',''),(62,'.1.3.6.1.2.1.43.11.1.1.8.1.2',''),(63,'.1.3.6.1.2.1.43.11.1.1.9.1.2',''),(64,'.1.3.6.1.2.1.43.11.1.1.8.1.3',''),(65,'.1.3.6.1.2.1.43.11.1.1.9.1.3',''),(66,'.1.3.6.1.2.1.43.11.1.1.8.1.4',''),(67,'.1.3.6.1.2.1.43.11.1.1.9.1.4',''),(68,'.1.3.6.1.2.1.25.2.3.1.5.1',''),(69,'.1.3.6.1.2.1.25.3.2.1.3.1',''),(70,'.1.3.6.1.4.1.11.2.3.9.4.2.1.1.3.12.0',''),(71,'.1.3.6.1.4.1.1602.1.11.1.3.1.4.113',''),(72,'.1.3.6.1.4.1.1602.1.11.1.3.1.4.123',''),(73,'.1.3.6.1.4.1.1602.1.2.1.4.0',''),(74,'.1.3.6.1.2.1.43.5.1.1.5.1.1',''),(75,'.1.3.6.1.4.1.236.11.5.1.1.1.1.0',''),(76,'.1.3.6.1.2.1.43.5.1.1.17.1.1',''),(77,'.1.3.6.1.2.1.43.11.1.1.9.1.5',''),(78,'.1.3.6.1.2.1.43.11.1.1.9.1.6',''),(79,'.1.3.6.1.4.1.367.3.2.1.6.1.1.7.1',''),(80,'.1.3.6.1.4.1.1248.1.2.2.27.1.1.3.1.1',''),(81,'.1.3.6.1.4.1.1248.1.2.2.27.1.1.4.1.1',''),(82,'.1.3.6.1.4.1.1248.1.2.2.1.1.1.5.1',''),(83,'.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.1.1.0',''),(84,'.1.3.6.1.4.1.11.2.3.9.4.2.1.1.16.4.1.3.1.0',''),(85,'.1.3.6.1.2.1.43.5.1.1.17.1',''),(86,'.1.3.6.1.2.1.43.8.2.1.14.1.1',''),(87,'.1.3.6.1.4.1.641.2.1.2.1.2.1',''),(88,'.1.3.6.1.4.1.641.2.1.2.1.6.1',''),(89,'.1.3.6.1.4.1.367.3.2.1.2.1.4.0',''),(90,'.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.2',''),(91,'.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.1',''),(92,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.1',''),(93,'.1.3.6.1.4.1.367.3.2.1.1.1.7.0',''),(94,'.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.3',''),(95,'.1.3.6.1.4.1.367.3.2.1.2.24.1.1.5.4',''),(96,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.22',''),(97,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.21',''),(98,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.8',''),(99,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.9',''),(100,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.11',''),(101,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.2',''),(102,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.3',''),(103,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.5',''),(104,'.1.3.6.1.4.1.367.3.2.1.2.19.5.1.9.6',''),(105,'.1.3.6.1.4.1.2001.1.1.1.1.11.1.10.45.0','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_mib_oid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` VALUES (1,'MD5',''),(2,'SHA','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` VALUES (3,'DES',''),(4,'AES128',''),(5,'AES192',''),(6,'AES256','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_snmp_auth_sec_level`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` VALUES (1,'noAuthNoPriv',''),(2,'authNoPriv',''),(3,'authPriv','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_auth_sec_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_dropdown_plugin_tracker_snmp_version`
--

DROP TABLE IF EXISTS `glpi_dropdown_plugin_tracker_snmp_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_dropdown_plugin_tracker_snmp_version` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `comments` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_dropdown_plugin_tracker_snmp_version`
--

LOCK TABLES `glpi_dropdown_plugin_tracker_snmp_version` WRITE;
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_version` DISABLE KEYS */;
INSERT INTO `glpi_dropdown_plugin_tracker_snmp_version` VALUES (1,'1',''),(2,'2c',''),(3,'3','');
/*!40000 ALTER TABLE `glpi_dropdown_plugin_tracker_snmp_version` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_agents`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_agents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_agents` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `core_discovery` int(11) NOT NULL DEFAULT '1',
  `threads_discovery` int(11) NOT NULL DEFAULT '1',
  `core_query` int(11) NOT NULL DEFAULT '1',
  `threads_query` int(11) NOT NULL DEFAULT '1',
  `last_agent_update` datetime DEFAULT NULL,
  `tracker_agent_version` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '0',
  `logs` int(1) NOT NULL DEFAULT '0',
  `key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fragment` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `key` (`key`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_agents`
--

LOCK TABLES `glpi_plugin_tracker_agents` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_agents` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_agents` VALUES (1,'Local',1,15,1,15,'2011-10-27 15:55:22','1.5.3',0,2,'gAcj42DawoX02dHvv7HrPsrviJl9MW',50);
/*!40000 ALTER TABLE `glpi_plugin_tracker_agents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_agents_processes`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_agents_processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_agents_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `process_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_agent` int(11) NOT NULL DEFAULT '0',
  `status` int(1) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_discovery` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time_query` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `errors` int(11) NOT NULL DEFAULT '0',
  `error_msg` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `networking_queries` int(11) NOT NULL DEFAULT '0',
  `printers_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries` int(11) NOT NULL DEFAULT '0',
  `discovery_queries_total` int(11) NOT NULL DEFAULT '0',
  `networking_ports_queries` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `process_number` (`process_number`,`FK_agent`),
  KEY `process_number_2` (`process_number`,`FK_agent`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_agents_processes`
--

LOCK TABLES `glpi_plugin_tracker_agents_processes` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_agents_processes` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_agents_processes` VALUES (1,'02991530001',1,2,'2011-10-27 15:30:32','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(2,'02991531001',1,2,'2011-10-27 15:31:03','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(3,'02991532001',1,2,'2011-10-27 15:32:45','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(4,'02991533001',1,2,'2011-10-27 15:33:16','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(5,'02991534001',1,2,'2011-10-27 15:34:10','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(6,'02991534001',1,2,'2011-10-27 15:34:26','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(7,'02991534001',1,2,'2011-10-27 15:34:40','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00',0,'0',0,0,0,0,0),(8,'02991537001',1,3,'2011-10-27 15:37:40','2011-10-27 15:39:09','2011-10-27 15:37:40','2011-10-27 15:39:09','2011-10-27 15:39:09','2011-10-27 15:39:09',0,'0',0,0,2,254,0),(9,'02991543001',1,3,'2011-10-27 15:43:24','2011-10-27 15:43:41','2011-10-27 15:43:24','2011-10-27 15:43:24','2011-10-27 15:43:24','2011-10-27 15:43:41',0,'0',2,0,0,0,0),(10,'02991546001',1,3,'2011-10-27 15:46:18','2011-10-27 15:46:36','2011-10-27 15:46:18','2011-10-27 15:46:18','2011-10-27 15:46:19','2011-10-27 15:46:36',0,'0',2,0,0,0,0),(11,'02991548001',1,3,'2011-10-27 15:48:16','2011-10-27 15:48:34','2011-10-27 15:48:16','2011-10-27 15:48:16','2011-10-27 15:48:16','2011-10-27 15:48:34',0,'0',2,0,0,0,0),(12,'02991555001',1,3,'2011-10-27 15:55:03','2011-10-27 15:55:22','2011-10-27 15:55:03','2011-10-27 15:55:03','2011-10-27 15:55:03','2011-10-27 15:55:22',0,'0',2,0,0,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_agents_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_computers`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_computers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_computers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_computers`
--

LOCK TABLES `glpi_plugin_tracker_computers` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_computers` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_computers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `activation_history` int(1) DEFAULT NULL,
  `activation_connection` int(1) DEFAULT NULL,
  `activation_snmp_computer` int(1) NOT NULL DEFAULT '0',
  `activation_snmp_networking` int(1) DEFAULT NULL,
  `activation_snmp_peripheral` int(1) DEFAULT NULL,
  `activation_snmp_phone` int(1) DEFAULT NULL,
  `activation_snmp_printer` int(1) DEFAULT NULL,
  `authsnmp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `URL_agent_conf` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ssl_only` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config`
--

LOCK TABLES `glpi_plugin_tracker_config` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_config` VALUES (1,'2.1.3',1,0,0,1,0,0,1,'DB','http://127.0.0.1/glpi072_fusdb',0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config_discovery`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config_discovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config_discovery` (
  `ID` int(1) NOT NULL AUTO_INCREMENT,
  `link_ip` int(1) NOT NULL DEFAULT '0',
  `link_name` int(1) NOT NULL DEFAULT '0',
  `link_serial` int(1) NOT NULL DEFAULT '0',
  `link2_ip` int(1) NOT NULL DEFAULT '0',
  `link2_name` int(1) NOT NULL DEFAULT '0',
  `link2_serial` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config_discovery`
--

LOCK TABLES `glpi_plugin_tracker_config_discovery` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_discovery` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_config_discovery` VALUES (1,0,0,0,0,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_discovery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config_snmp_history`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config_snmp_history` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config_snmp_history`
--

LOCK TABLES `glpi_plugin_tracker_config_snmp_history` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config_snmp_networking`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_networking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config_snmp_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(11) NOT NULL DEFAULT '0',
  `history_wire` int(11) NOT NULL DEFAULT '0',
  `history_ports_state` int(11) NOT NULL DEFAULT '0',
  `history_unknown_mac` int(11) NOT NULL DEFAULT '0',
  `history_snmp_errors` int(11) NOT NULL DEFAULT '0',
  `history_process` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config_snmp_networking`
--

LOCK TABLES `glpi_plugin_tracker_config_snmp_networking` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_networking` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_config_snmp_networking` VALUES (1,0,0,0,0,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_networking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config_snmp_printer`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_printer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config_snmp_printer` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `active_device_state` int(1) NOT NULL DEFAULT '0',
  `manage_cartridges` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config_snmp_printer`
--

LOCK TABLES `glpi_plugin_tracker_config_snmp_printer` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_printer` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_config_snmp_printer` VALUES (1,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_printer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_config_snmp_script`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_config_snmp_script`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_config_snmp_script` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nb_process` int(11) NOT NULL DEFAULT '1',
  `logs` int(1) NOT NULL DEFAULT '0',
  `lock` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_config_snmp_script`
--

LOCK TABLES `glpi_plugin_tracker_config_snmp_script` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_script` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_config_snmp_script` VALUES (1,1,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_config_snmp_script` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_connection_history`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_connection_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_computers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `state` int(1) NOT NULL DEFAULT '0',
  `username` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_users` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_connection_history`
--

LOCK TABLES `glpi_plugin_tracker_connection_history` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_connection_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_connection_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_connection_stats`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_connection_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_connection_stats` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `device_type` int(11) NOT NULL DEFAULT '0',
  `item_id` int(11) NOT NULL,
  `checksum` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_connection_stats`
--

LOCK TABLES `glpi_plugin_tracker_connection_stats` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_connection_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_connection_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_discovery`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_discovery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_discovery` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(11) NOT NULL DEFAULT '0',
  `FK_agents` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descr` text COLLATE utf8_unicode_ci,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `FK_model_infos` int(11) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_discovery`
--

LOCK TABLES `glpi_plugin_tracker_discovery` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_discovery` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_discovery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_errors`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_errors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_errors` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ifaddr` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` smallint(6) NOT NULL,
  `device_id` int(11) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `first_pb_date` datetime DEFAULT NULL,
  `last_pb_date` datetime DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_errors`
--

LOCK TABLES `glpi_plugin_tracker_errors` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_mib_networking`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_mib_networking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_mib_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_model_infos` int(8) DEFAULT NULL,
  `FK_mib_label` int(8) DEFAULT NULL,
  `FK_mib_oid` int(8) DEFAULT NULL,
  `FK_mib_object` int(8) DEFAULT NULL,
  `oid_port_counter` int(1) DEFAULT NULL,
  `oid_port_dyn` int(1) DEFAULT NULL,
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activation` int(1) NOT NULL DEFAULT '1',
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`,`mapping_name`),
  KEY `FK_model_infos_4` (`FK_model_infos`,`mapping_name`),
  KEY `oid_port_dyn` (`oid_port_dyn`),
  KEY `activation` (`activation`)
) ENGINE=MyISAM AUTO_INCREMENT=542 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_mib_networking`
--

LOCK TABLES `glpi_plugin_tracker_mib_networking` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_mib_networking` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_mib_networking` VALUES (1,1,NULL,1,1,0,0,'2','comments',1,0),(2,1,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,0),(3,1,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,0),(4,1,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,0),(5,1,NULL,1,5,0,0,'2','entPhysicalModelName',1,0),(6,1,NULL,5,6,0,1,'2','ifdescr',1,0),(7,1,NULL,6,7,0,1,'2','ifIndex',1,0),(8,1,NULL,7,8,0,1,'2','ifinerrors',1,0),(9,1,NULL,8,9,0,1,'2','ifinoctets',1,0),(10,1,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(11,1,NULL,10,11,0,1,'2','iflastchange',1,0),(12,1,NULL,11,12,0,1,'2','ifmtu',1,0),(13,1,NULL,5,6,0,1,'2','ifName',1,0),(14,1,NULL,12,13,0,1,'2','ifouterrors',1,0),(15,1,NULL,13,14,0,1,'2','ifoutoctets',1,0),(16,1,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(17,1,NULL,15,16,0,1,'2','ifspeed',1,0),(18,1,NULL,16,17,0,1,'2','ifstatus',1,0),(19,1,NULL,17,18,0,1,'2','ifType',1,0),(20,1,NULL,18,19,0,0,'2','macaddr',1,0),(21,1,NULL,19,20,0,0,'2','name',1,0),(22,1,NULL,20,21,0,0,'2','serial',1,0),(23,1,NULL,21,22,0,0,'2','uptime',1,0),(24,1,NULL,22,23,1,0,'0','',1,0),(25,2,NULL,22,23,1,0,'0','',1,0),(26,2,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(27,2,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(28,2,NULL,1,1,0,0,'2','comments',1,0),(29,2,NULL,25,26,0,0,'2','cpu',1,0),(30,2,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,1),(31,2,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,1),(32,2,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,1),(33,2,NULL,26,5,0,0,'2','entPhysicalModelName',1,0),(34,2,NULL,5,6,0,1,'2','ifdescr',1,0),(35,2,NULL,6,7,0,1,'2','ifIndex',1,0),(36,2,NULL,7,8,0,1,'2','ifinerrors',1,0),(37,2,NULL,8,9,0,1,'2','ifinoctets',1,0),(38,2,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(39,2,NULL,10,11,0,1,'2','iflastchange',1,0),(40,2,NULL,11,12,0,1,'2','ifmtu',1,0),(41,2,NULL,27,27,0,1,'2','ifName',1,0),(42,2,NULL,12,13,0,1,'2','ifouterrors',1,0),(43,2,NULL,13,14,0,1,'2','ifoutoctets',1,0),(44,2,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(45,2,NULL,15,16,0,1,'2','ifspeed',1,0),(46,2,NULL,16,17,0,1,'2','ifstatus',1,0),(47,2,NULL,17,18,0,1,'2','ifType',1,0),(48,2,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(49,2,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(50,2,NULL,19,30,0,0,'2','location',1,0),(51,2,NULL,18,19,0,0,'2','macaddr',1,0),(52,2,NULL,30,31,0,0,'2','memory',1,0),(53,2,NULL,31,20,0,0,'2','name',1,0),(54,2,NULL,32,32,0,0,'2','ram',1,0),(55,2,NULL,33,33,0,0,'2','serial',1,0),(56,2,NULL,21,22,0,0,'2','uptime',1,0),(57,2,NULL,34,34,0,1,'2','vlanTrunkPortDynamicStatus',1,1),(58,2,NULL,35,35,0,1,'2','vtpVlanName',1,0),(59,3,NULL,22,23,1,0,'0','',1,0),(60,3,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(61,3,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(62,3,NULL,1,1,0,0,'2','comments',1,0),(63,3,NULL,25,26,0,0,'2','cpu',1,0),(64,3,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,1),(65,3,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,1),(66,3,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,1),(67,3,NULL,26,5,0,0,'2','entPhysicalModelName',1,0),(68,3,NULL,36,36,0,0,'2','firmware',1,0),(69,3,NULL,5,6,0,1,'2','ifdescr',1,0),(70,3,NULL,6,7,0,1,'2','ifIndex',1,0),(71,3,NULL,7,8,0,1,'2','ifinerrors',1,0),(72,3,NULL,8,9,0,1,'2','ifinoctets',1,0),(73,3,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(74,3,NULL,10,11,0,1,'2','iflastchange',1,0),(75,3,NULL,11,12,0,1,'2','ifmtu',1,0),(76,3,NULL,27,27,0,1,'2','ifName',1,0),(77,3,NULL,12,13,0,1,'2','ifouterrors',1,0),(78,3,NULL,13,14,0,1,'2','ifoutoctets',1,0),(79,3,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(80,3,NULL,15,16,0,1,'2','ifspeed',1,0),(81,3,NULL,16,17,0,1,'2','ifstatus',1,0),(82,3,NULL,17,18,0,1,'2','ifType',1,0),(83,3,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(84,3,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(85,3,NULL,19,30,0,0,'2','location',1,0),(86,3,NULL,18,19,0,0,'2','macaddr',1,0),(87,3,NULL,30,31,0,0,'2','memory',1,0),(88,3,NULL,31,20,0,0,'2','name',1,0),(89,3,NULL,32,32,0,0,'2','ram',1,0),(90,3,NULL,37,33,0,0,'2','serial',1,0),(91,3,NULL,21,22,0,0,'2','uptime',1,0),(92,3,NULL,34,34,0,1,'2','vlanTrunkPortDynamicStatus',1,1),(93,3,NULL,35,35,0,1,'2','vtpVlanName',1,0),(94,4,NULL,22,23,1,0,'0','',1,0),(95,4,NULL,25,26,0,0,'2','cpu',1,0),(96,4,NULL,21,22,0,0,'2','uptime',1,0),(97,4,NULL,38,33,0,0,'2','serial',1,0),(98,4,NULL,19,30,0,0,'2','location',1,0),(99,4,NULL,30,31,0,0,'2','memory',1,0),(100,4,NULL,32,32,0,0,'2','ram',1,0),(101,4,NULL,31,20,0,0,'2','name',1,0),(102,4,NULL,1,1,0,0,'2','comments',1,0),(103,4,NULL,39,36,0,0,'2','firmware',1,0),(104,4,NULL,40,5,0,0,'2','entPhysicalModelName',1,0),(105,4,NULL,18,19,0,0,'2','macaddr',1,0),(106,4,NULL,15,16,0,1,'2','ifspeed',1,0),(107,4,NULL,8,9,0,1,'2','ifinoctets',1,0),(108,4,NULL,11,12,0,1,'2','ifmtu',1,0),(109,4,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(110,4,NULL,10,11,0,1,'2','iflastchange',1,0),(111,4,NULL,7,8,0,1,'2','ifinerrors',1,0),(112,4,NULL,13,14,0,1,'2','ifoutoctets',1,0),(113,4,NULL,12,13,0,1,'2','ifouterrors',1,0),(114,4,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(115,4,NULL,27,27,0,1,'2','ifName',1,0),(116,4,NULL,17,18,0,1,'2','ifType',1,0),(117,4,NULL,35,35,0,1,'2','vtpVlanName',1,0),(118,4,NULL,16,17,0,1,'2','ifstatus',1,0),(119,4,NULL,5,6,0,1,'2','ifdescr',1,0),(120,4,NULL,6,7,0,1,'2','ifIndex',1,0),(121,4,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(122,4,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(123,4,NULL,34,34,0,1,'2','vlanTrunkPortDynamicStatus',1,1),(124,4,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,1),(125,4,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(126,4,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,1),(127,4,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,1),(128,4,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(129,5,NULL,22,23,1,0,'0','',1,0),(130,5,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(131,5,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(132,5,NULL,1,1,0,0,'2','comments',1,0),(133,5,NULL,25,26,0,0,'2','cpu',1,0),(134,5,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,1),(135,5,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,1),(136,5,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,1),(137,5,NULL,26,5,0,0,'2','entPhysicalModelName',1,0),(138,5,NULL,41,36,0,0,'2','firmware',1,0),(139,5,NULL,5,6,0,1,'2','ifdescr',1,0),(140,5,NULL,6,7,0,1,'2','ifIndex',1,0),(141,5,NULL,7,8,0,1,'2','ifinerrors',1,0),(142,5,NULL,8,9,0,1,'2','ifinoctets',1,0),(143,5,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(144,5,NULL,10,11,0,1,'2','iflastchange',1,0),(145,5,NULL,11,12,0,1,'2','ifmtu',1,0),(146,5,NULL,27,27,0,1,'2','ifName',1,0),(147,5,NULL,12,13,0,1,'2','ifouterrors',1,0),(148,5,NULL,13,14,0,1,'2','ifoutoctets',1,0),(149,5,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(150,5,NULL,15,16,0,1,'2','ifspeed',1,0),(151,5,NULL,16,17,0,1,'2','ifstatus',1,0),(152,5,NULL,17,18,0,1,'2','ifType',1,0),(153,5,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(154,5,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(155,5,NULL,19,30,0,0,'2','location',1,0),(156,5,NULL,18,19,0,0,'2','macaddr',1,0),(157,5,NULL,30,31,0,0,'2','memory',1,0),(158,5,NULL,31,20,0,0,'2','name',1,0),(159,5,NULL,32,32,0,0,'2','ram',1,0),(160,5,NULL,37,33,0,0,'2','serial',1,0),(161,5,NULL,21,22,0,0,'2','uptime',1,0),(162,5,NULL,34,34,0,1,'2','vlanTrunkPortDynamicStatus',1,1),(163,5,NULL,35,35,0,1,'2','vtpVlanName',1,0),(164,6,NULL,1,1,0,0,'2','comments',1,0),(165,6,NULL,42,37,0,0,'2','cpu',1,0),(166,6,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,1),(167,6,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,1),(168,6,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,1),(169,6,NULL,5,6,0,1,'2','ifdescr',1,0),(170,6,NULL,6,7,0,1,'2','ifIndex',1,0),(171,6,NULL,7,8,0,1,'2','ifinerrors',1,0),(172,6,NULL,8,9,0,1,'2','ifinoctets',1,0),(173,6,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(174,6,NULL,10,11,0,1,'2','iflastchange',1,0),(175,6,NULL,11,12,0,1,'2','ifmtu',1,0),(176,6,NULL,27,27,0,1,'2','ifName',1,0),(177,6,NULL,12,13,0,1,'2','ifouterrors',1,0),(178,6,NULL,13,14,0,1,'2','ifoutoctets',1,0),(179,6,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(180,6,NULL,15,16,0,1,'2','ifspeed',1,0),(181,6,NULL,16,17,0,1,'2','ifstatus',1,0),(182,6,NULL,17,18,0,1,'2','ifType',1,0),(183,6,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(184,6,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(185,6,NULL,19,30,0,0,'2','location',1,0),(186,6,NULL,18,19,0,0,'2','macaddr',1,0),(187,6,NULL,43,31,0,0,'2','memory',1,0),(188,6,NULL,31,20,0,0,'2','name',1,0),(189,6,NULL,44,32,0,0,'2','ram',1,0),(190,6,NULL,21,22,0,0,'2','uptime',1,0),(191,6,NULL,22,23,1,0,'0','',1,0),(192,7,NULL,22,23,1,0,'0','',1,0),(193,7,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(194,7,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(195,7,NULL,1,1,0,0,'2','comments',1,0),(196,7,NULL,45,38,0,0,'2','cpu',1,0),(197,7,NULL,2,2,0,1,'2','dot1dBasePortIfIndex',1,0),(198,7,NULL,3,3,0,1,'2','dot1dTpFdbAddress',1,0),(199,7,NULL,4,4,0,1,'2','dot1dTpFdbPort',1,0),(200,7,NULL,46,5,0,0,'2','entPhysicalModelName',1,0),(201,7,NULL,47,36,0,0,'2','firmware',1,0),(202,7,NULL,5,6,0,1,'2','ifdescr',1,0),(203,7,NULL,6,7,0,1,'2','ifIndex',1,0),(204,7,NULL,7,8,0,1,'2','ifinerrors',1,0),(205,7,NULL,8,9,0,1,'2','ifinoctets',1,0),(206,7,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(207,7,NULL,10,11,0,1,'2','iflastchange',1,0),(208,7,NULL,11,12,0,1,'2','ifmtu',1,0),(209,7,NULL,27,27,0,1,'2','ifName',1,0),(210,7,NULL,12,13,0,1,'2','ifouterrors',1,0),(211,7,NULL,13,14,0,1,'2','ifoutoctets',1,0),(212,7,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(213,7,NULL,15,16,0,1,'2','ifspeed',1,0),(214,7,NULL,16,17,0,1,'2','ifstatus',1,0),(215,7,NULL,17,18,0,1,'2','ifType',1,0),(216,7,NULL,28,28,0,1,'2','ipAdEntAddr',1,0),(217,7,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(218,7,NULL,19,30,0,0,'2','location',1,0),(219,7,NULL,18,19,0,0,'2','macaddr',1,0),(220,7,NULL,48,39,0,0,'2','memory',1,0),(221,7,NULL,31,20,0,0,'2','name',1,0),(222,7,NULL,49,40,0,1,'2','PortVlanIndex',1,0),(223,7,NULL,50,41,0,0,'2','ram',1,0),(224,7,NULL,51,33,0,0,'2','serial',1,0),(225,7,NULL,21,22,0,0,'2','uptime',1,0),(226,7,NULL,52,42,0,1,'2','vlanTrunkPortDynamicStatus',1,0),(227,7,NULL,53,43,0,1,'2','vtpVlanName',1,0),(228,8,NULL,23,24,0,1,'2','cdpCacheAddress',1,0),(229,8,NULL,24,25,0,1,'2','cdpCacheDevicePort',1,0),(230,8,NULL,1,1,0,0,'2','comments',1,0),(231,8,NULL,5,6,0,1,'2','ifdescr',1,0),(232,8,NULL,6,7,0,1,'2','ifIndex',1,0),(233,8,NULL,7,8,0,1,'2','ifinerrors',1,0),(234,8,NULL,8,9,0,1,'2','ifinoctets',1,0),(235,8,NULL,9,10,0,1,'2','ifinternalstatus',1,0),(236,8,NULL,10,11,0,1,'2','iflastchange',1,0),(237,8,NULL,11,12,0,1,'2','ifmtu',1,0),(238,8,NULL,27,27,0,1,'2','ifName',1,0),(239,8,NULL,12,13,0,1,'2','ifouterrors',1,0),(240,8,NULL,13,14,0,1,'2','ifoutoctets',1,0),(241,8,NULL,14,15,0,1,'2','ifPhysAddress',1,0),(242,8,NULL,15,16,0,1,'2','ifspeed',1,0),(243,8,NULL,16,17,0,1,'2','ifstatus',1,0),(244,8,NULL,17,18,0,1,'2','ifType',1,0),(245,8,NULL,29,29,0,1,'2','ipNetToMediaPhysAddress',1,1),(246,8,NULL,19,30,0,0,'2','location',1,0),(247,8,NULL,30,31,0,0,'2','memory',1,0),(248,8,NULL,31,20,0,0,'2','name',1,0),(249,8,NULL,32,32,0,0,'2','ram',1,0),(250,8,NULL,38,33,0,0,'2','serial',1,0),(251,8,NULL,21,22,0,0,'2','uptime',1,0),(252,8,NULL,34,34,0,1,'2','vlanTrunkPortDynamicStatus',1,1),(253,8,NULL,35,35,0,1,'2','vtpVlanName',1,0),(254,8,NULL,22,23,1,0,'0','',1,0),(255,9,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(256,9,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(257,9,NULL,1,1,0,0,'3','comments',1,0),(258,9,NULL,56,46,0,0,'3','enterprise',1,0),(259,9,NULL,57,47,0,1,'3','ifaddr',1,0),(260,9,NULL,6,7,0,1,'3','ifIndex',1,0),(261,9,NULL,5,6,0,1,'3','ifName',1,0),(262,9,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(263,9,NULL,17,18,0,1,'3','ifType',1,0),(264,9,NULL,19,30,0,0,'3','location',1,0),(265,9,NULL,58,48,0,0,'3','memory',1,0),(266,9,NULL,59,49,0,0,'3','model',1,0),(267,9,NULL,31,20,0,0,'3','name',1,0),(268,9,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(269,9,NULL,61,21,0,0,'3','serial',1,0),(270,9,NULL,22,23,1,0,'0','',1,0),(271,10,NULL,22,23,1,0,'0','',1,0),(272,10,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(273,10,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(274,10,NULL,62,51,0,0,'3','cartridgescyanMAX',1,0),(275,10,NULL,63,52,0,0,'3','cartridgescyanREMAIN',1,0),(276,10,NULL,64,53,0,0,'3','cartridgesmagentaMAX',1,0),(277,10,NULL,65,54,0,0,'3','cartridgesmagentaREMAIN',1,0),(278,10,NULL,66,55,0,0,'3','cartridgesyellowMAX',1,0),(279,10,NULL,67,56,0,0,'3','cartridgesyellowREMAIN',1,0),(280,10,NULL,1,1,0,0,'3','comments',1,0),(281,10,NULL,57,47,0,1,'3','ifaddr',1,0),(282,10,NULL,6,7,0,1,'3','ifIndex',1,0),(283,10,NULL,5,6,0,1,'3','ifName',1,0),(284,10,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(285,10,NULL,17,18,0,1,'3','ifType',1,0),(286,10,NULL,19,30,0,0,'3','location',1,0),(287,10,NULL,68,57,0,0,'3','memory',1,0),(288,10,NULL,69,49,0,0,'3','model',1,0),(289,10,NULL,31,20,0,0,'3','name',1,0),(290,10,NULL,70,58,0,0,'3','otherserial',1,0),(291,10,NULL,71,59,0,0,'3','pagecounterblackpages',1,0),(292,10,NULL,72,60,0,0,'3','pagecountercolorpages',1,0),(293,10,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(294,10,NULL,73,21,0,0,'3','serial',1,0),(295,11,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(296,11,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(297,11,NULL,1,1,0,0,'3','comments',1,0),(298,11,NULL,74,46,0,0,'3','enterprise',1,0),(299,11,NULL,57,47,0,1,'3','ifaddr',1,0),(300,11,NULL,6,7,0,1,'3','ifIndex',1,0),(301,11,NULL,5,6,0,1,'3','ifName',1,0),(302,11,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(303,11,NULL,17,18,0,1,'3','ifType',1,0),(304,11,NULL,19,30,0,0,'3','location',1,0),(305,11,NULL,58,48,0,0,'3','memory',1,0),(306,11,NULL,75,49,0,0,'3','model',1,0),(307,11,NULL,31,20,0,0,'3','name',1,0),(308,11,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(309,11,NULL,76,21,0,0,'3','serial',1,0),(310,11,NULL,22,23,1,0,'0','',1,0),(311,12,NULL,67,45,0,0,'3','cartridgesblack',1,0),(312,12,NULL,65,52,0,0,'3','cartridgescyan',1,0),(313,12,NULL,77,61,0,0,'3','cartridgesfuser',1,0),(314,12,NULL,63,53,0,0,'3','cartridgesmagenta',1,0),(315,12,NULL,78,62,0,0,'3','cartridgesmaintenancekit',1,0),(316,12,NULL,55,56,0,0,'3','cartridgesyellow',1,0),(317,12,NULL,1,1,0,0,'3','comments',1,0),(318,12,NULL,57,47,0,1,'3','ifaddr',1,0),(319,12,NULL,6,7,0,1,'3','ifIndex',1,0),(320,12,NULL,5,6,0,1,'3','ifName',1,0),(321,12,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(322,12,NULL,17,18,0,1,'3','ifType',1,0),(323,12,NULL,19,30,0,0,'3','location',1,0),(324,12,NULL,68,57,0,0,'3','memory',1,0),(325,12,NULL,69,49,0,0,'3','model',1,0),(326,12,NULL,79,20,0,0,'3','name',1,0),(327,12,NULL,80,63,0,0,'3','pagecounterblackpages',1,0),(328,12,NULL,81,64,0,0,'3','pagecountercolorpages',1,0),(329,12,NULL,60,65,0,0,'3','pagecountertotalpages',1,0),(330,12,NULL,82,21,0,0,'3','serial',1,0),(331,12,NULL,22,23,1,0,'0','',1,0),(332,13,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(333,13,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(334,13,NULL,62,51,0,0,'3','cartridgescyanMAX',1,0),(335,13,NULL,63,52,0,0,'3','cartridgescyanREMAIN',1,0),(336,13,NULL,64,53,0,0,'3','cartridgesmagentaMAX',1,0),(337,13,NULL,65,54,0,0,'3','cartridgesmagentaREMAIN',1,0),(338,13,NULL,66,55,0,0,'3','cartridgesyellowMAX',1,0),(339,13,NULL,67,56,0,0,'3','cartridgesyellowREMAIN',1,0),(340,13,NULL,1,1,0,0,'3','comments',1,0),(341,13,NULL,57,47,0,1,'3','ifaddr',1,0),(342,13,NULL,6,7,0,1,'3','ifIndex',1,0),(343,13,NULL,5,6,0,1,'3','ifName',1,0),(344,13,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(345,13,NULL,17,18,0,1,'3','ifType',1,0),(346,13,NULL,19,30,0,0,'3','location',1,0),(347,13,NULL,68,57,0,0,'3','memory',1,0),(348,13,NULL,69,49,0,0,'3','model',1,0),(349,13,NULL,31,20,0,0,'3','name',1,0),(350,13,NULL,70,58,0,0,'3','otherserial',1,0),(351,13,NULL,83,59,0,0,'3','pagecounterblackpages',1,0),(352,13,NULL,84,60,0,0,'3','pagecountercolorpages',1,0),(353,13,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(354,13,NULL,85,21,0,0,'3','serial',1,0),(355,13,NULL,22,23,1,0,'0','',1,0),(356,14,NULL,22,23,1,0,'0','',1,0),(357,14,NULL,1,1,0,0,'3','comments',1,0),(358,14,NULL,19,30,0,0,'3','location',1,0),(359,14,NULL,68,57,0,0,'3','memory',1,0),(360,14,NULL,69,49,0,0,'3','model',1,0),(361,14,NULL,31,20,0,0,'3','name',1,0),(362,14,NULL,70,58,0,0,'3','otherserial',1,0),(363,14,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(364,14,NULL,85,21,0,0,'3','serial',1,0),(365,14,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(366,14,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(367,14,NULL,86,46,0,0,'3','enterprise',1,0),(368,14,NULL,57,47,0,1,'3','ifaddr',1,0),(369,14,NULL,6,7,0,1,'3','ifIndex',1,0),(370,14,NULL,5,6,0,1,'3','ifName',1,0),(371,14,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(372,14,NULL,17,18,0,1,'3','ifType',1,0),(373,15,NULL,87,49,0,0,'3','model',1,0),(374,15,NULL,31,20,0,0,'3','name',1,0),(375,15,NULL,88,21,0,0,'3','serial',1,0),(376,15,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(377,15,NULL,19,30,0,0,'3','location',1,0),(378,15,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(379,15,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(380,15,NULL,68,57,0,0,'3','memory',1,0),(381,15,NULL,22,23,1,0,'0','',1,0),(382,15,NULL,1,1,0,0,'3','comments',1,0),(383,15,NULL,62,51,0,0,'3','cartridgesmaintenancekitMAX',1,0),(384,15,NULL,63,52,0,0,'3','cartridgesmaintenancekitREMAIN',1,0),(385,15,NULL,86,46,0,0,'3','enterprise',1,0),(386,15,NULL,57,47,0,1,'3','ifaddr',1,0),(387,15,NULL,6,7,0,1,'3','ifIndex',1,0),(388,15,NULL,5,6,0,1,'3','ifName',1,0),(389,15,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(390,15,NULL,17,18,0,1,'3','ifType',1,0),(391,16,NULL,69,49,0,0,'3','model',1,0),(392,16,NULL,79,20,0,0,'3','name',1,0),(393,16,NULL,89,21,0,0,'3','serial',1,0),(394,16,NULL,90,52,0,0,'3','cartridgescyan',1,0),(395,16,NULL,19,30,0,0,'3','location',1,0),(396,16,NULL,91,45,0,0,'3','cartridgesblack',1,0),(397,16,NULL,92,65,0,0,'3','pagecountertotalpages',1,0),(398,16,NULL,68,57,0,0,'3','memory',1,0),(399,16,NULL,22,23,1,0,'0','',1,0),(400,16,NULL,1,1,0,0,'3','comments',1,0),(401,16,NULL,57,47,0,1,'3','ifaddr',1,0),(402,16,NULL,6,7,0,1,'3','ifIndex',1,0),(403,16,NULL,5,6,0,1,'3','ifName',1,0),(404,16,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(405,16,NULL,17,18,0,1,'3','ifType',1,0),(406,16,NULL,93,46,0,0,'3','enterprise',1,0),(407,16,NULL,94,54,0,0,'3','cartridgesmagenta',1,0),(408,16,NULL,95,56,0,0,'3','cartridgesyellow',1,0),(409,16,NULL,96,63,0,0,'3','pagecounterblackpages',1,0),(410,16,NULL,97,64,0,0,'3','pagecountercolorpages',1,0),(411,16,NULL,98,66,0,0,'3','pagecountertotalpages_print',1,0),(412,16,NULL,99,67,0,0,'3','pagecounterblackpages_print',1,0),(413,16,NULL,100,68,0,0,'3','pagecountercolorpages_print',1,0),(414,16,NULL,101,69,0,0,'3','pagecountertotalpages_copy',1,0),(415,16,NULL,102,70,0,0,'3','pagecounterblackpages_copy',1,0),(416,16,NULL,103,71,0,0,'3','pagecountercolorpages_copy',1,0),(417,16,NULL,104,72,0,0,'3','pagecountertotalpages_fax',1,0),(418,17,NULL,1,1,0,0,'3','comments',1,0),(419,17,NULL,93,46,0,0,'3','enterprise',1,0),(420,17,NULL,57,47,0,1,'3','ifaddr',1,0),(421,17,NULL,6,7,0,1,'3','ifIndex',1,0),(422,17,NULL,5,6,0,1,'3','ifName',1,0),(423,17,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(424,17,NULL,17,18,0,1,'3','ifType',1,0),(425,17,NULL,19,30,0,0,'3','location',1,0),(426,17,NULL,68,57,0,0,'3','memory',1,0),(427,17,NULL,69,49,0,0,'3','model',1,0),(428,17,NULL,79,20,0,0,'3','name',1,0),(429,17,NULL,92,65,0,0,'3','pagecountertotalpages',1,0),(430,17,NULL,101,69,0,0,'3','pagecountertotalpages_copy',1,0),(431,17,NULL,104,72,0,0,'3','pagecountertotalpages_fax',1,0),(432,17,NULL,98,66,0,0,'3','pagecountertotalpages_print',1,0),(433,17,NULL,89,21,0,0,'3','serial',1,0),(434,17,NULL,22,23,1,0,'0','',1,0),(435,18,NULL,69,49,0,0,'3','model',1,0),(436,18,NULL,31,20,0,0,'3','name',1,0),(437,18,NULL,85,21,0,0,'3','serial',1,0),(438,18,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(439,18,NULL,19,30,0,0,'3','location',1,0),(440,18,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(441,18,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(442,18,NULL,68,57,0,0,'3','memory',1,0),(443,18,NULL,22,23,1,0,'0','',1,0),(444,18,NULL,1,1,0,0,'3','comments',1,0),(445,18,NULL,57,47,0,1,'3','ifaddr',1,0),(446,18,NULL,6,7,0,1,'3','ifIndex',1,0),(447,18,NULL,5,6,0,1,'3','ifName',1,0),(448,18,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(449,18,NULL,17,18,0,1,'3','ifType',1,0),(450,19,NULL,69,49,0,0,'3','model',1,0),(451,19,NULL,31,20,0,0,'3','name',1,0),(452,19,NULL,73,21,0,0,'3','serial',1,0),(453,19,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(454,19,NULL,19,30,0,0,'3','location',1,0),(455,19,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(456,19,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(457,19,NULL,68,57,0,0,'3','memory',1,0),(458,19,NULL,22,23,1,0,'0','',1,0),(459,19,NULL,1,1,0,0,'3','comments',1,0),(460,19,NULL,57,47,0,1,'3','ifaddr',1,0),(461,19,NULL,6,7,0,1,'3','ifIndex',1,0),(462,19,NULL,5,6,0,1,'3','ifName',1,0),(463,19,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(464,19,NULL,17,18,0,1,'3','ifType',1,0),(465,19,NULL,62,51,0,0,'3','cartridgescyanMAX',1,0),(466,19,NULL,64,53,0,0,'3','cartridgesmagentaMAX',1,0),(467,19,NULL,66,55,0,0,'3','cartridgesyellowMAX',1,0),(468,19,NULL,63,52,0,0,'3','cartridgescyanREMAIN',1,0),(469,19,NULL,65,54,0,0,'3','cartridgesmagentaREMAIN',1,0),(470,19,NULL,67,56,0,0,'3','cartridgesyellowREMAIN',1,0),(471,20,NULL,91,45,0,0,'3','cartridgesblack',1,0),(472,20,NULL,1,1,0,0,'3','comments',1,0),(473,20,NULL,93,46,0,0,'3','enterprise',1,0),(474,20,NULL,57,47,0,1,'3','ifaddr',1,0),(475,20,NULL,6,7,0,1,'3','ifIndex',1,0),(476,20,NULL,5,6,0,1,'3','ifName',1,0),(477,20,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(478,20,NULL,17,18,0,1,'3','ifType',1,0),(479,20,NULL,19,30,0,0,'3','location',1,0),(480,20,NULL,68,57,0,0,'3','memory',1,0),(481,20,NULL,69,49,0,0,'3','model',1,0),(482,20,NULL,79,20,0,0,'3','name',1,0),(483,20,NULL,60,50,0,0,'3','pagecountertotalpages',1,0),(484,20,NULL,89,21,0,0,'3','serial',1,0),(485,20,NULL,22,23,1,0,'0','',1,0),(486,21,NULL,69,49,0,0,'3','model',1,0),(487,21,NULL,65,54,0,0,'3','cartridgesmagentaREMAIN',1,0),(488,21,NULL,64,53,0,0,'3','cartridgesmagentaMAX',1,0),(489,21,NULL,63,52,0,0,'3','cartridgescyanREMAIN',1,0),(490,21,NULL,68,57,0,0,'3','memory',1,0),(491,21,NULL,22,23,1,0,'0','',1,0),(492,21,NULL,1,1,0,0,'3','comments',1,0),(493,21,NULL,62,51,0,0,'3','cartridgescyanMAX',1,0),(494,21,NULL,55,45,0,0,'3','cartridgesblackREMAIN',1,0),(495,21,NULL,54,44,0,0,'3','cartridgesblackMAX',1,0),(496,21,NULL,60,65,0,0,'3','pagecountertotalpages',1,0),(497,21,NULL,66,55,0,0,'3','cartridgesyellowMAX',1,0),(498,21,NULL,67,56,0,0,'3','cartridgesyellowREMAIN',1,0),(499,21,NULL,105,33,0,0,'3','serial',1,0),(500,21,NULL,57,47,0,1,'3','ifaddr',1,0),(501,21,NULL,6,7,0,1,'3','ifIndex',1,0),(502,21,NULL,5,6,0,1,'3','ifName',1,0),(503,21,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(504,21,NULL,17,18,0,1,'3','ifType',1,0),(505,21,NULL,19,30,0,0,'3','location',1,0),(506,22,NULL,69,49,0,0,'3','model',1,0),(507,22,NULL,68,57,0,0,'3','memory',1,0),(508,22,NULL,22,23,1,0,'0','',1,0),(509,22,NULL,1,1,0,0,'3','comments',1,0),(510,22,NULL,67,56,0,0,'3','cartridgesyellow',1,0),(511,22,NULL,55,45,0,0,'3','cartridgesblack',1,0),(512,22,NULL,63,52,0,0,'3','cartridgescyan',1,0),(513,22,NULL,60,65,0,0,'3','pagecountertotalpages',1,0),(514,22,NULL,65,54,0,0,'3','cartridgesmagenta',1,0),(515,22,NULL,105,33,0,0,'3','serial',1,0),(516,22,NULL,57,47,0,1,'3','ifaddr',1,0),(517,22,NULL,6,7,0,1,'3','ifIndex',1,0),(518,22,NULL,5,6,0,1,'3','ifName',1,0),(519,22,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(520,22,NULL,17,18,0,1,'3','ifType',1,0),(521,22,NULL,19,30,0,0,'3','location',1,0),(522,23,NULL,69,49,0,0,'3','model',1,0),(523,23,NULL,79,20,0,0,'3','name',1,0),(524,23,NULL,89,21,0,0,'3','serial',1,0),(525,23,NULL,90,52,0,0,'3','cartridgescyan',1,0),(526,23,NULL,19,30,0,0,'3','location',1,0),(527,23,NULL,91,45,0,0,'3','cartridgesblack',1,0),(528,23,NULL,92,50,0,0,'3','pagecountertotalpages',1,0),(529,23,NULL,68,57,0,0,'3','memory',1,0),(530,23,NULL,22,23,1,0,'0','',1,0),(531,23,NULL,1,1,0,0,'3','comments',1,0),(532,23,NULL,93,46,0,0,'3','enterprise',1,0),(533,23,NULL,94,54,0,0,'3','cartridgesmagenta',1,0),(534,23,NULL,95,56,0,0,'3','cartridgesyellow',1,0),(535,23,NULL,102,63,0,0,'3','pagecounterblackpages',1,0),(536,23,NULL,103,64,0,0,'3','pagecountercolorpages',1,0),(537,23,NULL,57,47,0,1,'3','ifaddr',1,0),(538,23,NULL,6,7,0,1,'3','ifIndex',1,0),(539,23,NULL,5,6,0,1,'3','ifName',1,0),(540,23,NULL,14,15,0,1,'3','ifPhysAddress',1,0),(541,23,NULL,17,18,0,1,'3','ifType',1,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_mib_networking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_model_infos`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_model_infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_model_infos` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `device_type` int(8) NOT NULL DEFAULT '0',
  `deleted` int(1) DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `activation` int(1) NOT NULL DEFAULT '1',
  `discovery_key` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `name` (`name`),
  KEY `device_type` (`device_type`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_model_infos`
--

LOCK TABLES `glpi_plugin_tracker_model_infos` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_model_infos` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_model_infos` VALUES (1,'3Com IntelliJack NJ225',2,NULL,0,1,'Networking0004'),(2,'Cisco Aironet 1xxx',2,NULL,0,1,'Networking0006'),(3,'Cisco Catalyst 2950 4xxx 6xxx',2,NULL,0,1,'Networking0005'),(4,'Cisco générique',2,NULL,0,1,'Networking0001'),(5,'Cisco Catalyst older',2,NULL,0,1,'Networking0008'),(6,'Fourdry Networks',2,NULL,0,1,'Networking0007'),(7,'HP ProCurve générique',2,NULL,0,1,'Networking0003'),(8,'Switch générique',2,NULL,0,1,'Networking0002'),(9,'Dell Laser 1720',3,NULL,0,1,'Printer0008'),(10,'Canon IR3180C',3,NULL,0,1,'Printer0009'),(11,'Dell 1815',3,NULL,0,1,'Printer0007'),(12,'Imprimante Laser Couleur Epson AL-C3800',3,NULL,0,1,'Printer0016'),(13,'HP LaserJet Color generique',3,NULL,0,1,'Printer0005'),(14,'HP LaserJet generique',3,NULL,0,1,'Printer0006'),(15,'Imprimante Lexmark monochrome série T',3,NULL,0,1,'Printer0010'),(16,'Photocopieur couleur Ricoh',3,NULL,0,1,'Printer0002'),(17,'Photocopieur Noir et Blanc Ricoh',3,NULL,0,1,'Printer0013'),(18,'Imprimante générique',3,NULL,0,1,'Printer0001'),(19,'Canon iR couleur générique',3,NULL,0,1,'Printer0004'),(20,'Imprimante Laser Noir et Blanc Ricoh',3,NULL,0,1,'Printer0012'),(21,'Imprimante Laser Couleur Oki C5250n',3,NULL,0,1,'Printer0014'),(22,'Imprimante Laser Couleur Oki C5600',3,NULL,0,1,'Printer0015'),(23,'Imprimante Laser Couleur Ricoh',3,NULL,0,1,'Printer0011');
/*!40000 ALTER TABLE `glpi_plugin_tracker_model_infos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_networking`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_networking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_networking` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(8) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `uptime` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `cpu` int(3) NOT NULL DEFAULT '0',
  `memory` int(8) NOT NULL DEFAULT '0',
  `last_tracker_update` datetime DEFAULT NULL,
  `last_PID_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_networking` (`FK_networking`),
  KEY `FK_model_infos` (`FK_model_infos`,`FK_snmp_connection`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_networking`
--

LOCK TABLES `glpi_plugin_tracker_networking` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_networking` VALUES (1,1,4,2,'52 days, 08:57:10.75',20,9,'2011-10-27 15:57:13',3001557),(2,2,4,2,'62 days, 22:45:54.51',12,18,'2011-10-27 15:57:12',3001557);
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_networking_ifaddr`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ifaddr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_networking_ifaddr` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(11) NOT NULL,
  `ifaddr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ifaddr` (`ifaddr`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_networking_ifaddr`
--

LOCK TABLES `glpi_plugin_tracker_networking_ifaddr` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking_ifaddr` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_networking_ifaddr` VALUES (1,2,'192.168.0.80'),(2,2,'192.168.20.80'),(3,1,'192.168.20.81');
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking_ifaddr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_networking_ports`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_networking_ports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_networking_ports` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_networking_ports` int(8) NOT NULL,
  `ifmtu` int(8) NOT NULL DEFAULT '0',
  `ifspeed` int(12) NOT NULL DEFAULT '0',
  `ifinternalstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifconnectionstatus` int(8) NOT NULL DEFAULT '0',
  `iflastchange` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifinoctets` bigint(50) NOT NULL DEFAULT '0',
  `ifinerrors` bigint(50) NOT NULL DEFAULT '0',
  `ifoutoctets` bigint(50) NOT NULL DEFAULT '0',
  `ifouterrors` bigint(50) NOT NULL DEFAULT '0',
  `ifstatus` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifmac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifdescr` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `portduplex` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trunk` int(1) NOT NULL DEFAULT '0',
  `lastup` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `FK_networking_ports` (`FK_networking_ports`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_networking_ports`
--

LOCK TABLES `glpi_plugin_tracker_networking_ports` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking_ports` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_networking_ports` VALUES (1,1,1500,10000000,'1',0,'36.84 seconds',0,0,0,0,'2',NULL,'FastEthernet0/1',NULL,0,'0000-00-00 00:00:00'),(2,2,1500,10000000,'1',0,'36.84 seconds',0,0,0,0,'2',NULL,'FastEthernet0/2',NULL,0,'0000-00-00 00:00:00'),(3,3,1500,100000000,'1',0,'3 minutes, 08.19',2038152780,0,1282525347,0,'1',NULL,'FastEthernet0/3',NULL,0,'2011-10-27 15:57:12'),(4,4,1500,100000000,'1',0,'27 days, 01:47:34.13',2929666829,0,2995194041,0,'1',NULL,'FastEthernet0/4',NULL,0,'2011-10-27 15:57:12'),(5,5,1500,100000000,'1',0,'10 days, 13:48:08.96',4047998113,0,925388681,0,'1',NULL,'FastEthernet0/5',NULL,0,'2011-10-27 15:57:12'),(6,6,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/6',NULL,0,'0000-00-00 00:00:00'),(7,7,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/7',NULL,0,'0000-00-00 00:00:00'),(8,8,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/8',NULL,0,'0000-00-00 00:00:00'),(9,9,1500,100000000,'1',0,'2 days, 00:16:15.66',4278528739,0,1024136063,0,'1',NULL,'FastEthernet0/9',NULL,0,'2011-10-27 15:57:12'),(10,10,1500,100000000,'1',0,'27 days, 01:47:56.41',750817086,0,257027800,0,'1',NULL,'FastEthernet0/10',NULL,0,'2011-10-27 15:57:12'),(11,11,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/11',NULL,0,'0000-00-00 00:00:00'),(12,12,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/12',NULL,0,'0000-00-00 00:00:00'),(13,13,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/13',NULL,0,'0000-00-00 00:00:00'),(14,14,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/14',NULL,0,'0000-00-00 00:00:00'),(15,15,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/15',NULL,0,'0000-00-00 00:00:00'),(16,16,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/16',NULL,0,'0000-00-00 00:00:00'),(17,17,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/17',NULL,0,'0000-00-00 00:00:00'),(18,18,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/18',NULL,0,'0000-00-00 00:00:00'),(19,19,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/19',NULL,0,'0000-00-00 00:00:00'),(20,20,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/20',NULL,0,'0000-00-00 00:00:00'),(21,21,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/21',NULL,0,'0000-00-00 00:00:00'),(22,22,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'FastEthernet0/22',NULL,0,'0000-00-00 00:00:00'),(23,23,1500,100000000,'1',0,'61 days, 06:43:44.14',1822637096,1747,3118105220,0,'1',NULL,'FastEthernet0/23',NULL,0,'2011-10-27 15:57:12'),(24,24,1500,100000000,'1',0,'2 days, 00:15:07.80',2845449643,0,2094812282,0,'1',NULL,'FastEthernet0/24',NULL,-1,'2011-10-27 15:57:12'),(25,25,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'GigabitEthernet0/1',NULL,0,'0000-00-00 00:00:00'),(26,26,1500,10000000,'1',0,'36.85 seconds',0,0,0,0,'2',NULL,'GigabitEthernet0/2',NULL,0,'0000-00-00 00:00:00'),(27,33,1500,100000000,'1',0,'18.95 seconds',2030651325,0,1609982364,0,'1',NULL,'FastEthernet0/1',NULL,0,'2011-10-27 15:57:13'),(28,34,1500,0,'1',0,'18.96 seconds',64,0,892,0,'2',NULL,'FastEthernet0/2',NULL,0,'0000-00-00 00:00:00'),(29,35,1500,100000000,'1',0,'26 days, 01:30:44.28',2370775668,0,2215228539,0,'1',NULL,'FastEthernet0/3',NULL,0,'2011-10-27 15:57:13'),(30,36,1500,0,'1',0,'18.98 seconds',64,0,892,0,'2',NULL,'FastEthernet0/4',NULL,0,'0000-00-00 00:00:00'),(31,37,1500,0,'1',0,'18.98 seconds',64,0,892,0,'2',NULL,'FastEthernet0/5',NULL,0,'0000-00-00 00:00:00'),(32,38,1500,0,'1',0,'18.98 seconds',64,0,892,0,'2',NULL,'FastEthernet0/6',NULL,0,'0000-00-00 00:00:00'),(33,39,1500,0,'1',0,'18.99 seconds',64,0,892,0,'2',NULL,'FastEthernet0/7',NULL,0,'0000-00-00 00:00:00'),(34,40,1500,0,'1',0,'19.00 seconds',64,0,892,0,'2',NULL,'FastEthernet0/8',NULL,0,'0000-00-00 00:00:00'),(35,41,1500,0,'1',0,'19.00 seconds',64,0,848,0,'2',NULL,'FastEthernet0/9',NULL,1,'0000-00-00 00:00:00'),(36,42,1500,0,'1',0,'19.01 seconds',64,0,894,0,'2',NULL,'FastEthernet0/10',NULL,0,'0000-00-00 00:00:00'),(37,43,1500,0,'1',0,'19.02 seconds',64,0,894,0,'2',NULL,'FastEthernet0/11',NULL,0,'0000-00-00 00:00:00'),(38,44,1500,0,'1',0,'19.02 seconds',64,0,894,0,'2',NULL,'FastEthernet0/12',NULL,0,'0000-00-00 00:00:00'),(39,45,1500,0,'1',0,'19.03 seconds',64,0,894,0,'2',NULL,'FastEthernet0/13',NULL,0,'0000-00-00 00:00:00'),(40,46,1500,0,'1',0,'19.03 seconds',64,0,894,0,'2',NULL,'FastEthernet0/14',NULL,0,'0000-00-00 00:00:00'),(41,47,1500,0,'1',0,'19.04 seconds',64,0,894,0,'2',NULL,'FastEthernet0/15',NULL,0,'0000-00-00 00:00:00'),(42,48,1500,0,'1',0,'19.04 seconds',64,0,894,0,'2',NULL,'FastEthernet0/16',NULL,0,'0000-00-00 00:00:00'),(43,49,1500,0,'1',0,'19.06 seconds',64,0,894,0,'2',NULL,'FastEthernet0/17',NULL,0,'0000-00-00 00:00:00'),(44,50,1500,0,'1',0,'19.06 seconds',64,0,850,0,'2',NULL,'FastEthernet0/18',NULL,1,'0000-00-00 00:00:00'),(45,51,1500,100000000,'1',0,'52 days, 03:33:16.16',39078152,0,440346591,0,'1',NULL,'FastEthernet0/19',NULL,0,'2011-10-27 15:57:13'),(46,52,1500,0,'1',0,'46 days, 15:57:23.10',1500957604,0,626641710,0,'2',NULL,'FastEthernet0/20',NULL,0,'0000-00-00 00:00:00'),(47,53,1500,0,'1',0,'19.08 seconds',64,0,894,0,'2',NULL,'FastEthernet0/21',NULL,0,'0000-00-00 00:00:00'),(48,54,1500,100000000,'1',0,'19.08 seconds',264322251,0,1308252253,0,'1',NULL,'FastEthernet0/22',NULL,0,'2011-10-27 15:57:13'),(49,55,1500,100000000,'1',0,'51 days, 08:12:16.39',3173200374,0,3877503599,0,'1',NULL,'FastEthernet0/23',NULL,0,'2011-10-27 15:57:13'),(50,56,1500,100000000,'1',0,'46 days, 09:50:47.44',567225896,0,945670095,0,'1',NULL,'FastEthernet0/24',NULL,0,'2011-10-27 15:57:13');
/*!40000 ALTER TABLE `glpi_plugin_tracker_networking_ports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_printers`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_printers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_printers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `FK_model_infos` int(8) NOT NULL DEFAULT '0',
  `FK_snmp_connection` int(8) NOT NULL DEFAULT '0',
  `frequence_days` int(5) NOT NULL DEFAULT '1',
  `last_tracker_update` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `FK_printers` (`FK_printers`),
  KEY `FK_snmp_connection` (`FK_snmp_connection`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_printers`
--

LOCK TABLES `glpi_plugin_tracker_printers` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_printers_cartridges`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_cartridges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_printers_cartridges` (
  `ID` int(100) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL,
  `object_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FK_cartridges` int(11) NOT NULL DEFAULT '0',
  `state` int(3) NOT NULL DEFAULT '100',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_printers_cartridges`
--

LOCK TABLES `glpi_plugin_tracker_printers_cartridges` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers_cartridges` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers_cartridges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_printers_history`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_printers_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_printers_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_printers` int(11) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT '0000-00-00 00:00:00',
  `pages_total` int(11) NOT NULL DEFAULT '0',
  `pages_n_b` int(11) NOT NULL DEFAULT '0',
  `pages_color` int(11) NOT NULL DEFAULT '0',
  `pages_recto_verso` int(11) NOT NULL DEFAULT '0',
  `scanned` int(11) NOT NULL DEFAULT '0',
  `pages_total_print` int(11) NOT NULL DEFAULT '0',
  `pages_n_b_print` int(11) NOT NULL DEFAULT '0',
  `pages_color_print` int(11) NOT NULL DEFAULT '0',
  `pages_total_copy` int(11) NOT NULL DEFAULT '0',
  `pages_n_b_copy` int(11) NOT NULL DEFAULT '0',
  `pages_color_copy` int(11) NOT NULL DEFAULT '0',
  `pages_total_fax` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_printers_history`
--

LOCK TABLES `glpi_plugin_tracker_printers_history` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_printers_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_processes`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_processes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_processes` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(4) NOT NULL DEFAULT '0',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(4) NOT NULL DEFAULT '0',
  `error_msg` text COLLATE utf8_unicode_ci,
  `process_id` int(11) NOT NULL DEFAULT '0',
  `network_queries` int(8) NOT NULL DEFAULT '0',
  `printer_queries` int(8) NOT NULL DEFAULT '0',
  `ports_queries` int(8) NOT NULL DEFAULT '0',
  `discovery_queries` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `end_time` (`end_time`),
  KEY `process_id` (`process_id`),
  KEY `network_queries` (`network_queries`,`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_processes`
--

LOCK TABLES `glpi_plugin_tracker_processes` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_processes` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_processes` VALUES (1,1,'2011-10-27 15:57:08','2011-10-27 15:57:13',3,'0',3001557,8,0,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_processes_values`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_processes_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_processes_values` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `FK_processes` int(8) NOT NULL,
  `device_ID` int(8) NOT NULL DEFAULT '0',
  `device_type` int(8) NOT NULL DEFAULT '0',
  `port` int(8) NOT NULL DEFAULT '0',
  `unknow_mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_errors` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dropdown_add` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`ID`),
  KEY `unknow_mac` (`unknow_mac`,`FK_processes`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_processes_values`
--

LOCK TABLES `glpi_plugin_tracker_processes_values` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_processes_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_processes_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_profiles`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_profiles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interface` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'tracker',
  `is_default` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_networking` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_printers` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_models` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_authentification` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_scripts_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_discovery` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `general_config` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_iprange` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_agent` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_agent_infos` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  `snmp_report` char(1) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_profiles`
--

LOCK TABLES `glpi_plugin_tracker_profiles` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_profiles` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_profiles` VALUES (4,'super-admin','tracker','0','w','w','w','w','w','w','w','w','w','w','w');
/*!40000 ALTER TABLE `glpi_plugin_tracker_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_rangeip`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_rangeip`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_rangeip` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_tracker_agents` int(11) NOT NULL DEFAULT '0',
  `ifaddr_start` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ifaddr_end` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `discover` int(1) NOT NULL DEFAULT '0',
  `query` int(1) NOT NULL DEFAULT '0',
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_tracker_agents` (`FK_tracker_agents`,`discover`),
  KEY `FK_tracker_agents_2` (`FK_tracker_agents`,`query`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_rangeip`
--

LOCK TABLES `glpi_plugin_tracker_rangeip` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_rangeip` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_rangeip` VALUES (1,'Bureau',1,'192.168.20.1','192.168.20.254',0,1,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_rangeip` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_snmp_connection`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_connection`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_snmp_connection` (
  `ID` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FK_snmp_version` int(8) NOT NULL,
  `community` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sec_level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_protocol` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priv_passphrase` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_snmp_version` (`FK_snmp_version`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_snmp_connection`
--

LOCK TABLES `glpi_plugin_tracker_snmp_connection` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_snmp_connection` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_snmp_connection` VALUES (1,'Communauté Public v1',1,'public','','0','0','','0','',0),(2,'Communauté Public v2c',2,'public','','0','0','','0','',0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_snmp_connection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_snmp_history`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_snmp_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_snmp_history` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_ports` int(11) NOT NULL,
  `Field` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `date_mod` datetime DEFAULT NULL,
  `old_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_device_type` int(11) NOT NULL DEFAULT '0',
  `old_device_ID` int(11) NOT NULL DEFAULT '0',
  `new_value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `new_device_type` int(11) NOT NULL DEFAULT '0',
  `new_device_ID` int(11) NOT NULL DEFAULT '0',
  `FK_process` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_ports` (`FK_ports`,`date_mod`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_snmp_history`
--

LOCK TABLES `glpi_plugin_tracker_snmp_history` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_snmp_history` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_snmp_history` VALUES (1,27,'0','2011-10-27 15:57:08',NULL,0,0,'00:1a:6c:9a:fc:85',2,2,3001557),(2,5,'0','2011-10-27 15:57:08',NULL,0,0,'',5153,1,3001557),(3,24,'trunk','2011-10-27 15:57:08','0',0,0,'-1',0,0,3001557),(4,28,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:89',2,2,3001557),(5,9,'0','2011-10-27 15:57:09',NULL,0,0,'00:24:d4:b4:ca:6d',5153,2,3001557),(6,29,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:8a',2,2,3001557),(7,10,'0','2011-10-27 15:57:09',NULL,0,0,'00:30:05:be:19:ae',5153,3,3001557),(8,30,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:84',2,2,3001557),(9,4,'0','2011-10-27 15:57:09',NULL,0,0,'00:90:27:94:70:f9',5153,4,3001557),(10,31,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:83',2,2,3001557),(11,3,'0','2011-10-27 15:57:09',NULL,0,0,'00:1b:21:1b:f6:ff',5153,5,3001557),(12,32,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:97',2,2,3001557),(13,23,'0','2011-10-27 15:57:09',NULL,0,0,'00:40:f4:56:86:0f',5153,6,3001557),(14,27,'0','2011-10-27 15:57:09','00:1a:6c:9a:fc:85',2,2,NULL,0,0,3001557),(15,5,'0','2011-10-27 15:57:09','',5153,1,NULL,0,0,3001557),(16,5,'0','2011-10-27 15:57:09',NULL,0,0,'00:08:a3:3b:fd:01',2,1,3001557),(17,33,'0','2011-10-27 15:57:09',NULL,0,0,'00:1a:6c:9a:fc:85',2,2,3001557),(18,57,'0','2011-10-27 15:57:09',NULL,0,0,'00:08:a3:3b:fd:18',2,1,3001557),(19,56,'0','2011-10-27 15:57:09',NULL,0,0,'',5153,7,3001557),(20,41,'trunk','2011-10-27 15:57:09','0',0,0,'1',0,0,3001557),(21,50,'trunk','2011-10-27 15:57:09','0',0,0,'1',0,0,3001557),(22,58,'0','2011-10-27 15:57:10',NULL,0,0,'00:08:a3:3b:fd:03',2,1,3001557),(23,35,'0','2011-10-27 15:57:10',NULL,0,0,'00:1d:72:17:08:f4',5153,8,3001557),(24,59,'0','2011-10-27 15:57:10',NULL,0,0,'00:08:a3:3b:fd:17',2,1,3001557),(25,55,'0','2011-10-27 15:57:10',NULL,0,0,'00:23:18:cf:0d:93',5153,9,3001557),(26,60,'0','2011-10-27 15:57:10',NULL,0,0,'00:08:a3:3b:fd:13',2,1,3001557),(27,51,'0','2011-10-27 15:57:10',NULL,0,0,'00:50:8d:7c:79:d8',5153,10,3001557),(28,61,'0','2011-10-27 15:57:10',NULL,0,0,'00:08:a3:3b:fd:16',2,1,3001557),(29,54,'0','2011-10-27 15:57:10',NULL,0,0,'f0:ad:4e:00:19:f7',5153,11,3001557);
/*!40000 ALTER TABLE `glpi_plugin_tracker_snmp_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_tmp_connections`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_tmp_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_tmp_connections` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_tmp_netports` int(11) NOT NULL DEFAULT '0',
  `macaddress` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `macaddress` (`macaddress`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_tmp_connections`
--

LOCK TABLES `glpi_plugin_tracker_tmp_connections` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_tmp_connections` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_tmp_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_tmp_netports`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_tmp_netports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_tmp_netports` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FK_networking` int(11) NOT NULL DEFAULT '0',
  `FK_networking_port` int(11) NOT NULL DEFAULT '0',
  `cdp` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `cdp` (`cdp`),
  KEY `FK_networking` (`FK_networking`,`FK_networking_port`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_tmp_netports`
--

LOCK TABLES `glpi_plugin_tracker_tmp_netports` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_tmp_netports` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpi_plugin_tracker_tmp_netports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_unknown_device`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_unknown_device`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_unknown_device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_mod` datetime DEFAULT NULL,
  `FK_entities` int(11) NOT NULL DEFAULT '0',
  `location` int(11) NOT NULL DEFAULT '0',
  `deleted` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpi_plugin_tracker_unknown_device`
--

LOCK TABLES `glpi_plugin_tracker_unknown_device` WRITE;
/*!40000 ALTER TABLE `glpi_plugin_tracker_unknown_device` DISABLE KEYS */;
INSERT INTO `glpi_plugin_tracker_unknown_device` VALUES (1,'','2011-10-27 15:57:08',0,0,0),(2,'','2011-10-27 15:57:08',0,0,0),(3,'','2011-10-27 15:57:08',0,0,0),(4,'','2011-10-27 15:57:08',0,0,0),(5,'','2011-10-27 15:57:08',0,0,0),(6,'','2011-10-27 15:57:08',0,0,0),(7,'','2011-10-27 15:57:09',0,0,0),(8,'','2011-10-27 15:57:09',0,0,0),(9,'','2011-10-27 15:57:09',0,0,0),(10,'','2011-10-27 15:57:09',0,0,0),(11,'','2011-10-27 15:57:09',0,0,0);
/*!40000 ALTER TABLE `glpi_plugin_tracker_unknown_device` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpi_plugin_tracker_walks`
--

DROP TABLE IF EXISTS `glpi_plugin_tracker_walks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpi_plugin_tracker_walks` (
  `ID` int(30) NOT NULL AUTO_INCREMENT,
  `on_device` int(11) NOT NULL DEFAULT '0',
  `device_type` int(11) NOT NULL DEFAULT '0',
  `FK_agents_processes` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `vlan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `oid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
