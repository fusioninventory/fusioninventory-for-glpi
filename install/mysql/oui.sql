DROP TABLE IF EXISTS `glpi_plugin_fusioninventory_ouis`;

CREATE TABLE `glpi_plugin_fusioninventory_ouis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mac` (`mac`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_plugin_fusioninventory_ouis`
      (`id`, `mac`, `name`) VALUES 
(1, '00:00:00', 'XEROX CORPORATION'),
(2, '00:00:01', 'XEROX CORPORATION'),
(3, '00:00:02', 'XEROX CORPORATION'),
(4, '00:00:03', 'XEROX CORPORATION'),
(5, '00:00:04', 'XEROX CORPORATION'),
(6, '00:00:05', 'XEROX CORPORATION'),
(7, '00:00:06', 'XEROX CORPORATION'),
(8, '00:00:07', 'XEROX CORPORATION'),
(9, '00:00:08', 'XEROX CORPORATION'),
(10, '00:00:09', 'XEROX CORPORATION'),
(11, '00:00:0A', 'OMRON TATEISI ELECTRONICS CO.'),
(12, '00:00:0B', 'MATRIX CORPORATION'),
(13, '00:00:0C', 'CISCO SYSTEMS, INC.'),
(14, '00:00:0D', 'FIBRONICS LTD.'),
(15, '00:00:0E', 'FUJITSU LIMITED'),
(16, '00:00:0F', 'NEXT, INC.'),
(17, '00:00:10', 'SYTEK INC.'),
(18, '00:00:11', 'NORMEREL SYSTEMES'),
(19, '00:00:12', 'INFORMATION TECHNOLOGY LIMITED'),
(20, '00:00:13', 'CAMEX'),
(21, '00:00:14', 'NETRONIX'),
(22, '00:00:15', 'DATAPOINT CORPORATION'),
(23, '00:00:16', 'DU PONT PIXEL SYSTEMS     .'),
(24, '00:00:17', 'Oracle'),
(25, '00:00:18', 'WEBSTER COMPUTER CORPORATION'),
(26, '00:00:19', 'APPLIED DYNAMICS INTERNATIONAL'),
(27, '00:00:1A', 'ADVANCED MICRO DEVICES'),
(28, '00:00:1B', 'NOVELL INC.'),
(29, '00:00:1C', 'BELL TECHNOLOGIES'),
(30, '00:00:1D', 'CABLETRON SYSTEMS, INC.'),
(31, '00:00:1E', 'TELSIST INDUSTRIA ELECTRONICA'),
(32, '00:00:1F', 'Telco Systems, Inc.'),
(33, '00:00:20', 'DATAINDUSTRIER DIAB AB'),
(34, '00:00:21', 'SUREMAN COMP. &amp; COMMUN. CORP.'),
(35, '00:00:22', 'VISUAL TECHNOLOGY INC.'),
(36, '00:00:23', 'ABB INDUSTRIAL SYSTEMS AB'),
(37, '00:00:24', 'CONNECT AS'),
(38, '00:00:25', 'RAMTEK CORP.'),
(39, '00:00:26', 'SHA-KEN CO., LTD.'),
(40, '00:00:27', 'JAPAN RADIO COMPANY'),
(41, '00:00:28', 'PRODIGY SYSTEMS CORPORATION'),
(42, '00:00:29', 'IMC NETWORKS CORP.'),
(43, '00:00:2A', 'TRW - SEDD/INP'),
(44, '00:00:2B', 'CRISP AUTOMATION, INC'),
(45, '00:00:2C', 'AUTOTOTE LIMITED'),
(46, '00:00:2D', 'CHROMATICS INC'),
(47, '00:00:2E', 'SOCIETE EVIRA'),
(48, '00:00:2F', 'TIMEPLEX INC.'),
(49, '00:00:30', 'VG LABORATORY SYSTEMS LTD'),
(50, '00:00:31', 'QPSX COMMUNICATIONS PTY LTD'),
(51, '00:00:32', 'Marconi plc'),
(52, '00:00:33', 'EGAN MACHINERY COMPANY'),
(53, '00:00:34', 'NETWORK RESOURCES CORPORATION'),
(54, '00:00:35', 'SPECTRAGRAPHICS CORPORATION'),
(55, '00:00:36', 'ATARI CORPORATION'),
(56, '00:00:37', 'OXFORD METRICS LIMITED'),
(57, '00:00:38', 'CSS LABS'),
(58, '00:00:39', 'TOSHIBA CORPORATION'),
(59, '00:00:3A', 'CHYRON CORPORATION'),
(60, '00:00:3B', 'i Controls, Inc.'),
(61, '00:00:3C', 'AUSPEX SYSTEMS INC.'),
(62, '00:00:3D', 'UNISYS'),
(63, '00:00:3E', 'SIMPACT'),
(64, '00:00:3F', 'SYNTREX, INC.'),
(65, '00:00:40', 'APPLICON, INC.'),
(66, '00:00:41', 'ICE CORPORATION'),
(67, '00:00:42', 'METIER MANAGEMENT SYSTEMS LTD.'),
(68, '00:00:43', 'MICRO TECHNOLOGY'),
(69, '00:00:44', 'CASTELLE CORPORATION'),
(70, '00:00:45', 'FORD AEROSPACE &amp; COMM. CORP.'),
(71, '00:00:46', 'OLIVETTI NORTH AMERICA'),
(72, '00:00:47', 'NICOLET INSTRUMENTS CORP.'),
(73, '00:00:48', 'SEIKO EPSON CORPORATION'),
(74, '00:00:49', 'APRICOT COMPUTERS, LTD'),
(75, '00:00:4A', 'ADC CODENOLL TECHNOLOGY CORP.'),
(76, '00:00:4B', 'ICL DATA OY'),
(77, '00:00:4C', 'NEC CORPORATION'),
(78, '00:00:4D', 'DCI CORPORATION'),
(79, '00:00:4E', 'AMPEX CORPORATION'),
(80, '00:00:4F', 'LOGICRAFT, INC.'),
(81, '00:00:50', 'RADISYS CORPORATION'),
(82, '00:00:51', 'HOB ELECTRONIC GMBH &amp; CO. KG'),
(83, '00:00:52', 'Intrusion.com, Inc.'),
(84, '00:00:53', 'COMPUCORP'),
(85, '00:00:54', 'Schnieder Electric'),
(86, '00:00:55', 'COMMISSARIAT A L`ENERGIE ATOM.'),
(87, '00:00:56', 'DR. B. STRUCK'),
(88, '00:00:57', 'SCITEX CORPORATION LTD.'),
(89, '00:00:58', 'RACORE COMPUTER PRODUCTS INC.'),
(90, '00:00:59', 'HELLIGE GMBH'),
(91, '00:00:5A', 'SysKonnect GmbH'),
(92, '00:00:5B', 'ELTEC ELEKTRONIK AG'),
(93, '00:00:5C', 'TELEMATICS INTERNATIONAL INC.'),
(94, '00:00:5D', 'CS TELECOM'),
(95, '00:00:5E', 'ICANN, IANA Department'),
(96, '00:00:5F', 'SUMITOMO ELECTRIC IND., LTD.'),
(97, '00:00:60', 'KONTRON ELEKTRONIK GMBH'),
(98, '00:00:61', 'GATEWAY COMMUNICATIONS'),
(99, '00:00:62', 'BULL HN INFORMATION SYSTEMS'),
(100, '00:00:63', 'BARCO CONTROL ROOMS GMBH'),
(101, '00:00:64', 'Yokogawa Electric Corporation'),
(102, '00:00:65', 'Network General Corporation'),
(103, '00:00:66', 'TALARIS SYSTEMS, INC.'),
(104, '00:00:67', 'SOFT * RITE, INC.'),
(105, '00:00:68', 'ROSEMOUNT CONTROLS'),
(106, '00:00:69', 'CONCORD COMMUNICATIONS INC'),
(107, '00:00:6A', 'COMPUTER CONSOLES INC.'),
(108, '00:00:6B', 'SILICON GRAPHICS INC./MIPS'),
(109, '00:00:6C', 'PRIVATE'),
(110, '00:00:6D', 'CRAY COMMUNICATIONS, LTD.'),
(111, '00:00:6E', 'ARTISOFT, INC.'),
(112, '00:00:6F', 'Madge Ltd.'),
(113, '00:00:70', 'HCL LIMITED'),
(114, '00:00:71', 'ADRA SYSTEMS INC.'),
(115, '00:00:72', 'MINIWARE TECHNOLOGY'),
(116, '00:00:73', 'SIECOR CORPORATION'),
(117, '00:00:74', 'RICOH COMPANY LTD.'),
(118, '00:00:75', 'Nortel Networks'),
(119, '00:00:76', 'ABEKAS VIDEO SYSTEM'),
(120, '00:00:77', 'INTERPHASE CORPORATION'),
(121, '00:00:78', 'LABTAM LIMITED'),
(122, '00:00:79', 'NETWORTH INCORPORATED'),
(123, '00:00:7A', 'DANA COMPUTER INC.'),
(124, '00:00:7B', 'RESEARCH MACHINES'),
(125, '00:00:7C', 'AMPERE INCORPORATED'),
(126, '00:00:7D', 'Oracle Corporation'),
(127, '00:00:7E', 'CLUSTRIX CORPORATION'),
(128, '00:00:7F', 'LINOTYPE-HELL AG'),
(129, '00:00:80', 'CRAY COMMUNICATIONS A/S'),
(130, '00:00:81', 'BAY NETWORKS'),
(131, '00:00:82', 'LECTRA SYSTEMES SA'),
(132, '00:00:83', 'TADPOLE TECHNOLOGY PLC'),
(133, '00:00:84', 'SUPERNET'),
(134, '00:00:85', 'CANON INC.'),
(135, '00:00:86', 'MEGAHERTZ CORPORATION'),
(136, '00:00:87', 'HITACHI, LTD.'),
(137, '00:00:88', 'Brocade Communications Systems, Inc.'),
(138, '00:00:89', 'CAYMAN SYSTEMS INC.'),
(139, '00:00:8A', 'DATAHOUSE INFORMATION SYSTEMS'),
(140, '00:00:8B', 'INFOTRON'),
(141, '00:00:8C', 'Alloy Computer Products (Australia) Pty Ltd'),
(142, '00:00:8D', 'Cryptek Inc.'),
(143, '00:00:8E', 'SOLBOURNE COMPUTER, INC.'),
(144, '00:00:8F', 'Raytheon'),
(145, '00:00:90', 'MICROCOM'),
(146, '00:00:91', 'ANRITSU CORPORATION'),
(147, '00:00:92', 'COGENT DATA TECHNOLOGIES'),
(148, '00:00:93', 'PROTEON INC.'),
(149, '00:00:94', 'ASANTE TECHNOLOGIES'),
(150, '00:00:95', 'SONY TEKTRONIX CORP.'),
(151, '00:00:96', 'MARCONI ELECTRONICS LTD.'),
(152, '00:00:97', 'EMC Corporation'),
(153, '00:00:98', 'CROSSCOMM CORPORATION'),
(154, '00:00:99', 'MTX, INC.'),
(155, '00:00:9A', 'RC COMPUTER A/S'),
(156, '00:00:9B', 'INFORMATION INTERNATIONAL, INC'),
(157, '00:00:9C', 'ROLM MIL-SPEC COMPUTERS'),
(158, '00:00:9D', 'LOCUS COMPUTING CORPORATION'),
(159, '00:00:9E', 'MARLI S.A.'),
(160, '00:00:9F', 'AMERISTAR TECHNOLOGIES INC.'),
(161, '00:00:A0', 'SANYO Electric Co., Ltd.'),
(162, '00:00:A1', 'MARQUETTE ELECTRIC CO.'),
(163, '00:00:A2', 'BAY NETWORKS'),
(164, '00:00:A3', 'NETWORK APPLICATION TECHNOLOGY'),
(165, '00:00:A4', 'ACORN COMPUTERS LIMITED'),
(166, '00:00:A5', 'Tattile SRL'),
(167, '00:00:A6', 'NETWORK GENERAL CORPORATION'),
(168, '00:00:A7', 'NETWORK COMPUTING DEVICES INC.'),
(169, '00:00:A8', 'STRATUS COMPUTER INC.'),
(170, '00:00:A9', 'NETWORK SYSTEMS CORP.'),
(171, '00:00:AA', 'XEROX CORPORATION'),
(172, '00:00:AB', 'LOGIC MODELING CORPORATION'),
(173, '00:00:AC', 'CONWARE COMPUTER CONSULTING'),
(174, '00:00:AD', 'BRUKER INSTRUMENTS INC.'),
(175, '00:00:AE', 'DASSAULT ELECTRONIQUE'),
(176, '00:00:AF', 'Canberra Industries, Inc.'),
(177, '00:00:B0', 'RND-RAD NETWORK DEVICES'),
(178, '00:00:B1', 'ALPHA MICROSYSTEMS INC.'),
(179, '00:00:B2', 'TELEVIDEO SYSTEMS, INC.'),
(180, '00:00:B3', 'CIMLINC INCORPORATED'),
(181, '00:00:B4', 'EDIMAX COMPUTER COMPANY'),
(182, '00:00:B5', 'DATABILITY SOFTWARE SYS. INC.'),
(183, '00:00:B6', 'MICRO-MATIC RESEARCH'),
(184, '00:00:B7', 'DOVE COMPUTER CORPORATION'),
(185, '00:00:B8', 'SEIKOSHA CO., LTD.'),
(186, '00:00:B9', 'MCDONNELL DOUGLAS COMPUTER SYS'),
(187, '00:00:BA', 'SIIG, INC.'),
(188, '00:00:BB', 'TRI-DATA'),
(189, '00:00:BC', 'Rockwell Automation'),
(190, '00:00:BD', 'MITSUBISHI CABLE COMPANY'),
(191, '00:00:BE', 'THE NTI GROUP'),
(192, '00:00:BF', 'SYMMETRIC COMPUTER SYSTEMS'),
(193, '00:00:C0', 'WESTERN DIGITAL CORPORATION'),
(194, '00:00:C1', 'Madge Ltd.'),
(195, '00:00:C2', 'INFORMATION PRESENTATION TECH.'),
(196, '00:00:C3', 'HARRIS CORP COMPUTER SYS DIV'),
(197, '00:00:C4', 'WATERS DIV. OF MILLIPORE'),
(198, '00:00:C5', 'ARRIS Group, Inc.'),
(199, '00:00:C6', 'EON SYSTEMS'),
(200, '00:00:C7', 'ARIX CORPORATION'),
(201, '00:00:C8', 'ALTOS COMPUTER SYSTEMS'),
(202, '00:00:C9', 'Emulex Corporation'),
(203, '00:00:CA', 'ARRIS International'),
(204, '00:00:CB', 'COMPU-SHACK ELECTRONIC GMBH'),
(205, '00:00:CC', 'DENSAN CO., LTD.'),
(206, '00:00:CD', 'Allied Telesis Labs Ltd'),
(207, '00:00:CE', 'MEGADATA CORP.'),
(208, '00:00:CF', 'HAYES MICROCOMPUTER PRODUCTS'),
(209, '00:00:D0', 'DEVELCON ELECTRONICS LTD.'),
(210, '00:00:D1', 'ADAPTEC INCORPORATED'),
(211, '00:00:D2', 'SBE, INC.'),
(212, '00:00:D3', 'WANG LABORATORIES INC.'),
(213, '00:00:D4', 'PURE DATA LTD.'),
(214, '00:00:D5', 'MICROGNOSIS INTERNATIONAL'),
(215, '00:00:D6', 'PUNCH LINE HOLDING'),
(216, '00:00:D7', 'DARTMOUTH COLLEGE'),
(217, '00:00:D8', 'NOVELL, INC.'),
(218, '00:00:D9', 'NIPPON TELEGRAPH &amp; TELEPHONE'),
(219, '00:00:DA', 'ATEX'),
(220, '00:00:DB', 'British Telecommunications plc'),
(221, '00:00:DC', 'HAYES MICROCOMPUTER PRODUCTS'),
(222, '00:00:DD', 'TCL INCORPORATED'),
(223, '00:00:DE', 'CETIA'),
(224, '00:00:DF', 'BELL &amp; HOWELL PUB SYS DIV'),
(225, '00:00:E0', 'QUADRAM CORP.'),
(226, '00:00:E1', 'GRID SYSTEMS'),
(227, '00:00:E2', 'ACER TECHNOLOGIES CORP.'),
(228, '00:00:E3', 'INTEGRATED MICRO PRODUCTS LTD'),
(229, '00:00:E4', 'IN2 GROUPE INTERTECHNIQUE'),
(230, '00:00:E5', 'SIGMEX LTD.'),
(231, '00:00:E6', 'APTOR PRODUITS DE COMM INDUST'),
(232, '00:00:E7', 'STAR GATE TECHNOLOGIES'),
(233, '00:00:E8', 'ACCTON TECHNOLOGY CORP.'),
(234, '00:00:E9', 'ISICAD, INC.'),
(235, '00:00:EA', 'UPNOD AB'),
(236, '00:00:EB', 'MATSUSHITA COMM. IND. CO. LTD.'),
(237, '00:00:EC', 'MICROPROCESS'),
(238, '00:00:ED', 'APRIL'),
(239, '00:00:EE', 'NETWORK DESIGNERS, LTD.'),
(240, '00:00:EF', 'KTI'),
(241, '00:00:F0', 'SAMSUNG ELECTRONICS CO., LTD.'),
(242, '00:00:F1', 'MAGNA COMPUTER CORPORATION'),
(243, '00:00:F2', 'SPIDER COMMUNICATIONS'),
(244, '00:00:F3', 'GANDALF DATA LIMITED'),
(245, '00:00:F4', 'Allied Telesis'),
(246, '00:00:F5', 'DIAMOND SALES LIMITED'),
(247, '00:00:F6', 'APPLIED MICROSYSTEMS CORP.'),
(248, '00:00:F7', 'YOUTH KEEP ENTERPRISE CO LTD'),
(249, '00:00:F8', 'DIGITAL EQUIPMENT CORPORATION'),
(250, '00:00:F9', 'QUOTRON SYSTEMS INC.'),
(251, '00:00:FA', 'MICROSAGE COMPUTER SYSTEMS INC'),
(252, '00:00:FB', 'RECHNER ZUR KOMMUNIKATION'),
(253, '00:00:FC', 'MEIKO'),
(254, '00:00:FD', 'HIGH LEVEL HARDWARE'),
(255, '00:00:FE', 'ANNAPOLIS MICRO SYSTEMS'),
(256, '00:00:FF', 'CAMTEC ELECTRONICS LTD.'),
(257, '00:01:00', 'EQUIP\'TRANS'),
(258, '00:01:01', 'PRIVATE'),
(259, '00:01:02', '3COM CORPORATION'),
(260, '00:01:03', '3COM CORPORATION'),
(261, '00:01:04', 'DVICO Co., Ltd.'),
(262, '00:01:05', 'Beckhoff Automation GmbH'),
(263, '00:01:06', 'Tews Datentechnik GmbH'),
(264, '00:01:07', 'Leiser GmbH'),
(265, '00:01:08', 'AVLAB Technology, Inc.'),
(266, '00:01:09', 'Nagano Japan Radio Co., Ltd.'),
(267, '00:01:0A', 'CIS TECHNOLOGY INC.'),
(268, '00:01:0B', 'Space CyberLink, Inc.'),
(269, '00:01:0C', 'System Talks Inc.'),
(270, '00:01:0D', 'CORECO, INC.'),
(271, '00:01:0E', 'Bri-Link Technologies Co., Ltd'),
(272, '00:01:0F', 'Brocade Communications Systems, Inc.'),
(273, '00:01:10', 'Gotham Networks'),
(274, '00:01:11', 'iDigm Inc.'),
(275, '00:01:12', 'Shark Multimedia Inc.'),
(276, '00:01:13', 'OLYMPUS CORPORATION'),
(277, '00:01:14', 'KANDA TSUSHIN KOGYO CO., LTD.'),
(278, '00:01:15', 'EXTRATECH CORPORATION'),
(279, '00:01:16', 'Netspect Technologies, Inc.'),
(280, '00:01:17', 'CANAL +'),
(281, '00:01:18', 'EZ Digital Co., Ltd.'),
(282, '00:01:19', 'RTUnet (Australia)'),
(283, '00:01:1A', 'Hoffmann und Burmeister GbR'),
(284, '00:01:1B', 'Unizone Technologies, Inc.'),
(285, '00:01:1C', 'Universal Talkware Corporation'),
(286, '00:01:1D', 'Centillium Communications'),
(287, '00:01:1E', 'Precidia Technologies, Inc.'),
(288, '00:01:1F', 'RC Networks, Inc.'),
(289, '00:01:20', 'OSCILLOQUARTZ S.A.'),
(290, '00:01:21', 'Watchguard Technologies, Inc.'),
(291, '00:01:22', 'Trend Communications, Ltd.'),
(292, '00:01:23', 'DIGITAL ELECTRONICS CORP.'),
(293, '00:01:24', 'Acer Incorporated'),
(294, '00:01:25', 'YAESU MUSEN CO., LTD.'),
(295, '00:01:26', 'PAC Labs'),
(296, '00:01:27', 'OPEN Networks Pty Ltd'),
(297, '00:01:28', 'EnjoyWeb, Inc.'),
(298, '00:01:29', 'DFI Inc.'),
(299, '00:01:2A', 'Telematica Sistems Inteligente'),
(300, '00:01:2B', 'TELENET Co., Ltd.'),
(301, '00:01:2C', 'Aravox Technologies, Inc.'),
(302, '00:01:2D', 'Komodo Technology'),
(303, '00:01:2E', 'PC Partner Ltd.'),
(304, '00:01:2F', 'Twinhead International Corp'),
(305, '00:01:30', 'Extreme Networks'),
(306, '00:01:31', 'Bosch Security Systems, Inc.'),
(307, '00:01:32', 'Dranetz - BMI'),
(308, '00:01:33', 'KYOWA Electronic Instruments C'),
(309, '00:01:34', 'Selectron Systems AG'),
(310, '00:01:35', 'KDC Corp.'),
(311, '00:01:36', 'CyberTAN Technology, Inc.'),
(312, '00:01:37', 'IT Farm Corporation'),
(313, '00:01:38', 'XAVi Technologies Corp.'),
(314, '00:01:39', 'Point Multimedia Systems'),
(315, '00:01:3A', 'SHELCAD COMMUNICATIONS, LTD.'),
(316, '00:01:3B', 'BNA SYSTEMS'),
(317, '00:01:3C', 'TIW SYSTEMS'),
(318, '00:01:3D', 'RiscStation Ltd.'),
(319, '00:01:3E', 'Ascom Tateco AB'),
(320, '00:01:3F', 'Neighbor World Co., Ltd.'),
(321, '00:01:40', 'Sendtek Corporation'),
(322, '00:01:41', 'CABLE PRINT'),
(323, '00:01:42', 'CISCO SYSTEMS, INC.'),
(324, '00:01:43', 'CISCO SYSTEMS, INC.'),
(325, '00:01:44', 'EMC Corporation'),
(326, '00:01:45', 'WINSYSTEMS, INC.'),
(327, '00:01:46', 'Tesco Controls, Inc.'),
(328, '00:01:47', 'Zhone Technologies'),
(329, '00:01:48', 'X-traWeb Inc.'),
(330, '00:01:49', 'T.D.T. Transfer Data Test GmbH'),
(331, '00:01:4A', 'Sony Corporation'),
(332, '00:01:4B', 'Ennovate Networks, Inc.'),
(333, '00:01:4C', 'Berkeley Process Control'),
(334, '00:01:4D', 'Shin Kin Enterprises Co., Ltd'),
(335, '00:01:4E', 'WIN Enterprises, Inc.'),
(336, '00:01:4F', 'ADTRAN INC'),
(337, '00:01:50', 'GILAT COMMUNICATIONS, LTD.'),
(338, '00:01:51', 'Ensemble Communications'),
(339, '00:01:52', 'CHROMATEK INC.'),
(340, '00:01:53', 'ARCHTEK TELECOM CORPORATION'),
(341, '00:01:54', 'G3M Corporation'),
(342, '00:01:55', 'Promise Technology, Inc.'),
(343, '00:01:56', 'FIREWIREDIRECT.COM, INC.'),
(344, '00:01:57', 'SYSWAVE CO., LTD'),
(345, '00:01:58', 'Electro Industries/Gauge Tech'),
(346, '00:01:59', 'S1 Corporation'),
(347, '00:01:5A', 'Digital Video Broadcasting'),
(348, '00:01:5B', 'ITALTEL S.p.A/RF-UP-I'),
(349, '00:01:5C', 'CADANT INC.'),
(350, '00:01:5D', 'Oracle Corporation'),
(351, '00:01:5E', 'BEST TECHNOLOGY CO., LTD.'),
(352, '00:01:5F', 'DIGITAL DESIGN GmbH'),
(353, '00:01:60', 'ELMEX Co., LTD.'),
(354, '00:01:61', 'Meta Machine Technology'),
(355, '00:01:62', 'Cygnet Technologies, Inc.'),
(356, '00:01:63', 'CISCO SYSTEMS, INC.'),
(357, '00:01:64', 'CISCO SYSTEMS, INC.'),
(358, '00:01:65', 'AirSwitch Corporation'),
(359, '00:01:66', 'TC GROUP A/S'),
(360, '00:01:67', 'HIOKI E.E. CORPORATION'),
(361, '00:01:68', 'VITANA CORPORATION'),
(362, '00:01:69', 'Celestix Networks Pte Ltd.'),
(363, '00:01:6A', 'ALITEC'),
(364, '00:01:6B', 'LightChip, Inc.'),
(365, '00:01:6C', 'FOXCONN'),
(366, '00:01:6D', 'CarrierComm Inc.'),
(367, '00:01:6E', 'Conklin Corporation'),
(368, '00:01:6F', 'Inkel Corp.'),
(369, '00:01:70', 'ESE Embedded System Engineer\'g'),
(370, '00:01:71', 'Allied Data Technologies'),
(371, '00:01:72', 'TechnoLand Co., LTD.'),
(372, '00:01:73', 'AMCC'),
(373, '00:01:74', 'CyberOptics Corporation'),
(374, '00:01:75', 'Radiant Communications Corp.'),
(375, '00:01:76', 'Orient Silver Enterprises'),
(376, '00:01:77', 'EDSL'),
(377, '00:01:78', 'MARGI Systems, Inc.'),
(378, '00:01:79', 'WIRELESS TECHNOLOGY, INC.'),
(379, '00:01:7A', 'Chengdu Maipu Electric Industrial Co., Ltd.'),
(380, '00:01:7B', 'Heidelberger Druckmaschinen AG'),
(381, '00:01:7C', 'AG-E GmbH'),
(382, '00:01:7D', 'ThermoQuest'),
(383, '00:01:7E', 'ADTEK System Science Co., Ltd.'),
(384, '00:01:7F', 'Experience Music Project'),
(385, '00:01:80', 'AOpen, Inc.'),
(386, '00:01:81', 'Nortel Networks'),
(387, '00:01:82', 'DICA TECHNOLOGIES AG'),
(388, '00:01:83', 'ANITE TELECOMS'),
(389, '00:01:84', 'SIEB &amp; MEYER AG'),
(390, '00:01:85', 'Hitachi Aloka Medical, Ltd.'),
(391, '00:01:86', 'Uwe Disch'),
(392, '00:01:87', 'I2SE GmbH'),
(393, '00:01:88', 'LXCO Technologies ag'),
(394, '00:01:89', 'Refraction Technology, Inc.'),
(395, '00:01:8A', 'ROI COMPUTER AG'),
(396, '00:01:8B', 'NetLinks Co., Ltd.'),
(397, '00:01:8C', 'Mega Vision'),
(398, '00:01:8D', 'AudeSi Technologies'),
(399, '00:01:8E', 'Logitec Corporation'),
(400, '00:01:8F', 'Kenetec, Inc.'),
(401, '00:01:90', 'SMK-M'),
(402, '00:01:91', 'SYRED Data Systems'),
(403, '00:01:92', 'Texas Digital Systems'),
(404, '00:01:93', 'Hanbyul Telecom Co., Ltd.'),
(405, '00:01:94', 'Capital Equipment Corporation'),
(406, '00:01:95', 'Sena Technologies, Inc.'),
(407, '00:01:96', 'CISCO SYSTEMS, INC.'),
(408, '00:01:97', 'CISCO SYSTEMS, INC.'),
(409, '00:01:98', 'Darim Vision'),
(410, '00:01:99', 'HeiSei Electronics'),
(411, '00:01:9A', 'LEUNIG GmbH'),
(412, '00:01:9B', 'Kyoto Microcomputer Co., Ltd.'),
(413, '00:01:9C', 'JDS Uniphase Inc.'),
(414, '00:01:9D', 'E-Control Systems, Inc.'),
(415, '00:01:9E', 'ESS Technology, Inc.'),
(416, '00:01:9F', 'ReadyNet'),
(417, '00:01:A0', 'Infinilink Corporation'),
(418, '00:01:A1', 'Mag-Tek, Inc.'),
(419, '00:01:A2', 'Logical Co., Ltd.'),
(420, '00:01:A3', 'GENESYS LOGIC, INC.'),
(421, '00:01:A4', 'Microlink Corporation'),
(422, '00:01:A5', 'Nextcomm, Inc.'),
(423, '00:01:A6', 'Scientific-Atlanta Arcodan A/S'),
(424, '00:01:A7', 'UNEX TECHNOLOGY CORPORATION'),
(425, '00:01:A8', 'Welltech Computer Co., Ltd.'),
(426, '00:01:A9', 'BMW AG'),
(427, '00:01:AA', 'Airspan Communications, Ltd.'),
(428, '00:01:AB', 'Main Street Networks'),
(429, '00:01:AC', 'Sitara Networks, Inc.'),
(430, '00:01:AD', 'Coach Master International  d.b.a. CMI Worldwide, Inc.'),
(431, '00:01:AE', 'Trex Enterprises'),
(432, '00:01:AF', 'Artesyn Embedded Technologies'),
(433, '00:01:B0', 'Fulltek Technology Co., Ltd.'),
(434, '00:01:B1', 'General Bandwidth'),
(435, '00:01:B2', 'Digital Processing Systems, Inc.'),
(436, '00:01:B3', 'Precision Electronic Manufacturing'),
(437, '00:01:B4', 'Wayport, Inc.'),
(438, '00:01:B5', 'Turin Networks, Inc.'),
(439, '00:01:B6', 'SAEJIN T&amp;M Co., Ltd.'),
(440, '00:01:B7', 'Centos, Inc.'),
(441, '00:01:B8', 'Netsensity, Inc.'),
(442, '00:01:B9', 'SKF Condition Monitoring'),
(443, '00:01:BA', 'IC-Net, Inc.'),
(444, '00:01:BB', 'Frequentis'),
(445, '00:01:BC', 'Brains Corporation'),
(446, '00:01:BD', 'Peterson Electro-Musical Products, Inc.'),
(447, '00:01:BE', 'Gigalink Co., Ltd.'),
(448, '00:01:BF', 'Teleforce Co., Ltd.'),
(449, '00:01:C0', 'CompuLab, Ltd.'),
(450, '00:01:C1', 'Vitesse Semiconductor Corporation'),
(451, '00:01:C2', 'ARK Research Corp.'),
(452, '00:01:C3', 'Acromag, Inc.'),
(453, '00:01:C4', 'NeoWave, Inc.'),
(454, '00:01:C5', 'Simpler Networks'),
(455, '00:01:C6', 'Quarry Technologies'),
(456, '00:01:C7', 'CISCO SYSTEMS, INC.'),
(457, '00:01:C8', 'THOMAS CONRAD CORP.'),
(458, '00:01:C8', 'CONRAD CORP.'),
(459, '00:01:C9', 'CISCO SYSTEMS, INC.'),
(460, '00:01:CA', 'Geocast Network Systems, Inc.'),
(461, '00:01:CB', 'EVR'),
(462, '00:01:CC', 'Japan Total Design Communication Co., Ltd.'),
(463, '00:01:CD', 'ARtem'),
(464, '00:01:CE', 'Custom Micro Products, Ltd.'),
(465, '00:01:CF', 'Alpha Data Parallel Systems, Ltd.'),
(466, '00:01:D0', 'VitalPoint, Inc.'),
(467, '00:01:D1', 'CoNet Communications, Inc.'),
(468, '00:01:D2', 'inXtron, Inc.'),
(469, '00:01:D3', 'PAXCOMM, Inc.'),
(470, '00:01:D4', 'Leisure Time, Inc.'),
(471, '00:01:D5', 'HAEDONG INFO &amp; COMM CO., LTD'),
(472, '00:01:D6', 'manroland AG'),
(473, '00:01:D7', 'F5 Networks, Inc.'),
(474, '00:01:D8', 'Teltronics, Inc.'),
(475, '00:01:D9', 'Sigma, Inc.'),
(476, '00:01:DA', 'WINCOMM Corporation'),
(477, '00:01:DB', 'Freecom Technologies GmbH'),
(478, '00:01:DC', 'Activetelco'),
(479, '00:01:DD', 'Avail Networks'),
(480, '00:01:DE', 'Trango Systems, Inc.'),
(481, '00:01:DF', 'ISDN Communications, Ltd.'),
(482, '00:01:E0', 'Fast Systems, Inc.'),
(483, '00:01:E1', 'Kinpo Electronics, Inc.'),
(484, '00:01:E2', 'Ando Electric Corporation'),
(485, '00:01:E3', 'Siemens AG'),
(486, '00:01:E4', 'Sitera, Inc.'),
(487, '00:01:E5', 'Supernet, Inc.'),
(488, '00:01:E6', 'Hewlett-Packard Company'),
(489, '00:01:E7', 'Hewlett-Packard Company'),
(490, '00:01:E8', 'Force10 Networks, Inc.'),
(491, '00:01:E9', 'Litton Marine Systems B.V.'),
(492, '00:01:EA', 'Cirilium Corp.'),
(493, '00:01:EB', 'C-COM Corporation'),
(494, '00:01:EC', 'Ericsson Group'),
(495, '00:01:ED', 'SETA Corp.'),
(496, '00:01:EE', 'Comtrol Europe, Ltd.'),
(497, '00:01:EF', 'Camtel Technology Corp.'),
(498, '00:01:F0', 'Tridium, Inc.'),
(499, '00:01:F1', 'Innovative Concepts, Inc.'),
(500, '00:01:F2', 'Mark of the Unicorn, Inc.'),
(501, '00:01:F3', 'QPS, Inc.'),
(502, '00:01:F4', 'Enterasys Networks'),
(503, '00:01:F5', 'ERIM S.A.'),
(504, '00:01:F6', 'Association of Musical Electronics Industry'),
(505, '00:01:F7', 'Image Display Systems, Inc.'),
(506, '00:01:F8', 'Texio Technology Corporation'),
(507, '00:01:F9', 'TeraGlobal Communications Corp.'),
(508, '00:01:FA', 'HOROSCAS'),
(509, '00:01:FB', 'DoTop Technology, Inc.'),
(510, '00:01:FC', 'Keyence Corporation'),
(511, '00:01:FD', 'Digital Voice Systems, Inc.'),
(512, '00:01:FE', 'DIGITAL EQUIPMENT CORPORATION'),
(513, '00:01:FF', 'Data Direct Networks, Inc.'),
(514, '00:02:00', 'Net &amp; Sys Co., Ltd.'),
(515, '00:02:01', 'IFM Electronic gmbh'),
(516, '00:02:02', 'Amino Communications, Ltd.'),
(517, '00:02:03', 'Woonsang Telecom, Inc.'),
(518, '00:02:04', 'Bodmann Industries Elektronik GmbH'),
(519, '00:02:05', 'Hitachi Denshi, Ltd.'),
(520, '00:02:06', 'Telital R&amp;D Denmark A/S'),
(521, '00:02:07', 'VisionGlobal Network Corp.'),
(522, '00:02:08', 'Unify Networks, Inc.'),
(523, '00:02:09', 'Shenzhen SED Information Technology Co., Ltd.'),
(524, '00:02:0A', 'Gefran Spa'),
(525, '00:02:0B', 'Native Networks, Inc.'),
(526, '00:02:0C', 'Metro-Optix'),
(527, '00:02:0D', 'Micronpc.com'),
(528, '00:02:0E', 'ECI Telecom, Ltd'),
(529, '00:02:0F', 'AATR'),
(530, '00:02:10', 'Fenecom'),
(531, '00:02:11', 'Nature Worldwide Technology Corp.'),
(532, '00:02:12', 'SierraCom'),
(533, '00:02:13', 'S.D.E.L.'),
(534, '00:02:14', 'DTVRO'),
(535, '00:02:15', 'Cotas Computer Technology A/B'),
(536, '00:02:16', 'CISCO SYSTEMS, INC.'),
(537, '00:02:17', 'CISCO SYSTEMS, INC.'),
(538, '00:02:18', 'Advanced Scientific Corp'),
(539, '00:02:19', 'Paralon Technologies'),
(540, '00:02:1A', 'Zuma Networks'),
(541, '00:02:1B', 'Kollmorgen-Servotronix'),
(542, '00:02:1C', 'Network Elements, Inc.'),
(543, '00:02:1D', 'Data General Communication Ltd.'),
(544, '00:02:1E', 'SIMTEL S.R.L.'),
(545, '00:02:1F', 'Aculab PLC'),
(546, '00:02:20', 'CANON FINETECH INC.'),
(547, '00:02:21', 'DSP Application, Ltd.'),
(548, '00:02:22', 'Chromisys, Inc.'),
(549, '00:02:23', 'ClickTV'),
(550, '00:02:24', 'C-COR'),
(551, '00:02:25', 'One Stop Systems'),
(552, '00:02:26', 'XESystems, Inc.'),
(553, '00:02:27', 'ESD Electronic System Design GmbH'),
(554, '00:02:28', 'Necsom, Ltd.'),
(555, '00:02:29', 'Adtec Corporation'),
(556, '00:02:2A', 'Asound Electronic'),
(557, '00:02:2B', 'SAXA, Inc.'),
(558, '00:02:2C', 'ABB Bomem, Inc.'),
(559, '00:02:2D', 'Agere Systems'),
(560, '00:02:2E', 'TEAC Corp. R&amp; D'),
(561, '00:02:2F', 'P-Cube, Ltd.'),
(562, '00:02:30', 'Intersoft Electronics'),
(563, '00:02:31', 'Ingersoll-Rand'),
(564, '00:02:32', 'Avision, Inc.'),
(565, '00:02:33', 'Mantra Communications, Inc.'),
(566, '00:02:34', 'Imperial Technology, Inc.'),
(567, '00:02:35', 'Paragon Networks International'),
(568, '00:02:36', 'INIT GmbH'),
(569, '00:02:37', 'Cosmo Research Corp.'),
(570, '00:02:38', 'Serome Technology, Inc.'),
(571, '00:02:39', 'Visicom'),
(572, '00:02:3A', 'ZSK Stickmaschinen GmbH'),
(573, '00:02:3B', 'Ericsson'),
(574, '00:02:3C', 'Creative Technology, Ltd.'),
(575, '00:02:3D', 'Cisco Systems, Inc.'),
(576, '00:02:3E', 'Selta Telematica S.p.a'),
(577, '00:02:3F', 'Compal Electronics, Inc.'),
(578, '00:02:40', 'Seedek Co., Ltd.'),
(579, '00:02:41', 'Amer.com'),
(580, '00:02:42', 'Videoframe Systems'),
(581, '00:02:43', 'Raysis Co., Ltd.'),
(582, '00:02:44', 'SURECOM Technology Co.'),
(583, '00:02:45', 'Lampus Co, Ltd.'),
(584, '00:02:46', 'All-Win Tech Co., Ltd.'),
(585, '00:02:47', 'Great Dragon Information Technology (Group) Co., Ltd.'),
(586, '00:02:48', 'Pilz GmbH &amp; Co.'),
(587, '00:02:49', 'Aviv Infocom Co, Ltd.'),
(588, '00:02:4A', 'CISCO SYSTEMS, INC.'),
(589, '00:02:4B', 'CISCO SYSTEMS, INC.'),
(590, '00:02:4C', 'SiByte, Inc.'),
(591, '00:02:4D', 'Mannesman Dematic Colby Pty. Ltd.'),
(592, '00:02:4E', 'Datacard Group'),
(593, '00:02:4F', 'IPM Datacom S.R.L.'),
(594, '00:02:50', 'Geyser Networks, Inc.'),
(595, '00:02:51', 'Soma Networks, Inc.'),
(596, '00:02:52', 'Carrier Corporation'),
(597, '00:02:53', 'Televideo, Inc.'),
(598, '00:02:54', 'WorldGate'),
(599, '00:02:55', 'IBM Corp'),
(600, '00:02:56', 'Alpha Processor, Inc.'),
(601, '00:02:57', 'Microcom Corp.'),
(602, '00:02:58', 'Flying Packets Communications'),
(603, '00:02:59', 'Tsann Kuen China (Shanghai)Enterprise Co., Ltd. IT Group'),
(604, '00:02:5A', 'Catena Networks'),
(605, '00:02:5B', 'Cambridge Silicon Radio'),
(606, '00:02:5C', 'SCI Systems (Kunshan) Co., Ltd.'),
(607, '00:02:5D', 'Calix Networks'),
(608, '00:02:5E', 'High Technology Ltd'),
(609, '00:02:5F', 'Nortel Networks'),
(610, '00:02:60', 'Accordion Networks, Inc.'),
(611, '00:02:61', 'Tilgin AB'),
(612, '00:02:62', 'Soyo Group Soyo Com Tech Co., Ltd'),
(613, '00:02:63', 'UPS Manufacturing SRL'),
(614, '00:02:64', 'AudioRamp.com'),
(615, '00:02:65', 'Virditech Co. Ltd.'),
(616, '00:02:66', 'Thermalogic Corporation'),
(617, '00:02:67', 'NODE RUNNER, INC.'),
(618, '00:02:68', 'Harris Government Communications'),
(619, '00:02:69', 'Nadatel Co., Ltd'),
(620, '00:02:6A', 'Cocess Telecom Co., Ltd.'),
(621, '00:02:6B', 'BCM Computers Co., Ltd.'),
(622, '00:02:6C', 'Philips CFT'),
(623, '00:02:6D', 'Adept Telecom'),
(624, '00:02:6E', 'NeGeN Access, Inc.'),
(625, '00:02:6F', 'Senao International Co., Ltd.'),
(626, '00:02:70', 'Crewave Co., Ltd.'),
(627, '00:02:71', 'Zhone Technologies'),
(628, '00:02:72', 'CC&amp;C Technologies, Inc.'),
(629, '00:02:73', 'Coriolis Networks'),
(630, '00:02:74', 'Tommy Technologies Corp.'),
(631, '00:02:75', 'SMART Technologies, Inc.'),
(632, '00:02:76', 'Primax Electronics Ltd.'),
(633, '00:02:77', 'Cash Systemes Industrie'),
(634, '00:02:78', 'Samsung Electro-Mechanics Co., Ltd.'),
(635, '00:02:79', 'Control Applications, Ltd.'),
(636, '00:02:7A', 'IOI Technology Corporation'),
(637, '00:02:7B', 'Amplify Net, Inc.'),
(638, '00:02:7C', 'Trilithic, Inc.'),
(639, '00:02:7D', 'CISCO SYSTEMS, INC.'),
(640, '00:02:7E', 'CISCO SYSTEMS, INC.'),
(641, '00:02:7F', 'ask-technologies.com'),
(642, '00:02:80', 'Mu Net, Inc.'),
(643, '00:02:81', 'Madge Ltd.'),
(644, '00:02:82', 'ViaClix, Inc.'),
(645, '00:02:83', 'Spectrum Controls, Inc.'),
(646, '00:02:84', 'AREVA T&amp;D'),
(647, '00:02:85', 'Riverstone Networks'),
(648, '00:02:86', 'Occam Networks'),
(649, '00:02:87', 'Adapcom'),
(650, '00:02:88', 'GLOBAL VILLAGE COMMUNICATION'),
(651, '00:02:89', 'DNE Technologies'),
(652, '00:02:8A', 'Ambit Microsystems Corporation'),
(653, '00:02:8B', 'VDSL Systems OY'),
(654, '00:02:8C', 'Micrel-Synergy Semiconductor'),
(655, '00:02:8D', 'Movita Technologies, Inc.'),
(656, '00:02:8E', 'Rapid 5 Networks, Inc.'),
(657, '00:02:8F', 'Globetek, Inc.'),
(658, '00:02:90', 'Woorigisool, Inc.'),
(659, '00:02:91', 'Open Network Co., Ltd.'),
(660, '00:02:92', 'Logic Innovations, Inc.'),
(661, '00:02:93', 'Solid Data Systems'),
(662, '00:02:94', 'Tokyo Sokushin Co., Ltd.'),
(663, '00:02:95', 'IP.Access Limited'),
(664, '00:02:96', 'Lectron Co,. Ltd.'),
(665, '00:02:97', 'C-COR.net'),
(666, '00:02:98', 'Broadframe Corporation'),
(667, '00:02:99', 'Apex, Inc.'),
(668, '00:02:9A', 'Storage Apps'),
(669, '00:02:9B', 'Kreatel Communications AB'),
(670, '00:02:9C', '3COM'),
(671, '00:02:9D', 'Merix Corp.'),
(672, '00:02:9E', 'Information Equipment Co., Ltd.'),
(673, '00:02:9F', 'L-3 Communication Aviation Recorders'),
(674, '00:02:A0', 'Flatstack Ltd.'),
(675, '00:02:A1', 'World Wide Packets'),
(676, '00:02:A2', 'Hilscher GmbH'),
(677, '00:02:A3', 'ABB Switzerland Ltd, Power Systems'),
(678, '00:02:A4', 'AddPac Technology Co., Ltd.'),
(679, '00:02:A5', 'Hewlett-Packard Company'),
(680, '00:02:A6', 'Effinet Systems Co., Ltd.'),
(681, '00:02:A7', 'Vivace Networks'),
(682, '00:02:A8', 'Air Link Technology'),
(683, '00:02:A9', 'RACOM, s.r.o.'),
(684, '00:02:AA', 'PLcom Co., Ltd.'),
(685, '00:02:AB', 'CTC Union Technologies Co., Ltd.'),
(686, '00:02:AC', '3PAR data'),
(687, '00:02:AD', 'HOYA Corporation'),
(688, '00:02:AE', 'Scannex Electronics Ltd.'),
(689, '00:02:AF', 'TeleCruz Technology, Inc.'),
(690, '00:02:B0', 'Hokubu Communication &amp; Industrial Co., Ltd.'),
(691, '00:02:B1', 'Anritsu, Ltd.'),
(692, '00:02:B2', 'Cablevision'),
(693, '00:02:B3', 'Intel Corporation'),
(694, '00:02:B4', 'DAPHNE'),
(695, '00:02:B5', 'Avnet, Inc.'),
(696, '00:02:B6', 'Acrosser Technology Co., Ltd.'),
(697, '00:02:B7', 'Watanabe Electric Industry Co., Ltd.'),
(698, '00:02:B8', 'WHI KONSULT AB'),
(699, '00:02:B9', 'CISCO SYSTEMS, INC.'),
(700, '00:02:BA', 'CISCO SYSTEMS, INC.'),
(701, '00:02:BB', 'Continuous Computing Corp'),
(702, '00:02:BC', 'LVL 7 Systems, Inc.'),
(703, '00:02:BD', 'Bionet Co., Ltd.'),
(704, '00:02:BE', 'Totsu Engineering, Inc.'),
(705, '00:02:BF', 'dotRocket, Inc.'),
(706, '00:02:C0', 'Bencent Tzeng Industry Co., Ltd.'),
(707, '00:02:C1', 'Innovative Electronic Designs, Inc.'),
(708, '00:02:C2', 'Net Vision Telecom'),
(709, '00:02:C3', 'Arelnet Ltd.'),
(710, '00:02:C4', 'Vector International BVBA'),
(711, '00:02:C5', 'Evertz Microsystems Ltd.'),
(712, '00:02:C6', 'Data Track Technology PLC'),
(713, '00:02:C7', 'ALPS ELECTRIC Co., Ltd.'),
(714, '00:02:C8', 'Technocom Communications Technology (pte) Ltd'),
(715, '00:02:C9', 'Mellanox Technologies'),
(716, '00:02:CA', 'EndPoints, Inc.'),
(717, '00:02:CB', 'TriState Ltd.'),
(718, '00:02:CC', 'M.C.C.I'),
(719, '00:02:CD', 'TeleDream, Inc.'),
(720, '00:02:CE', 'FoxJet, Inc.'),
(721, '00:02:CF', 'ZyGate Communications, Inc.'),
(722, '00:02:D0', 'Comdial Corporation'),
(723, '00:02:D1', 'Vivotek, Inc.'),
(724, '00:02:D2', 'Workstation AG'),
(725, '00:02:D3', 'NetBotz, Inc.'),
(726, '00:02:D4', 'PDA Peripherals, Inc.'),
(727, '00:02:D5', 'ACR'),
(728, '00:02:D6', 'NICE Systems'),
(729, '00:02:D7', 'EMPEG Ltd'),
(730, '00:02:D8', 'BRECIS Communications Corporation'),
(731, '00:02:D9', 'Reliable Controls'),
(732, '00:02:DA', 'ExiO Communications, Inc.'),
(733, '00:02:DB', 'NETSEC'),
(734, '00:02:DC', 'Fujitsu General Limited'),
(735, '00:02:DD', 'Bromax Communications, Ltd.'),
(736, '00:02:DE', 'Astrodesign, Inc.'),
(737, '00:02:DF', 'Net Com Systems, Inc.'),
(738, '00:02:E0', 'ETAS GmbH'),
(739, '00:02:E1', 'Integrated Network Corporation'),
(740, '00:02:E2', 'NDC Infared Engineering'),
(741, '00:02:E3', 'LITE-ON Communications, Inc.'),
(742, '00:02:E4', 'JC HYUN Systems, Inc.'),
(743, '00:02:E5', 'Timeware Ltd.'),
(744, '00:02:E6', 'Gould Instrument Systems, Inc.'),
(745, '00:02:E7', 'CAB GmbH &amp; Co KG'),
(746, '00:02:E8', 'E.D.&amp;A.'),
(747, '00:02:E9', 'CS Systemes De Securite - C3S'),
(748, '00:02:EA', 'Focus Enhancements'),
(749, '00:02:EB', 'Pico Communications'),
(750, '00:02:EC', 'Maschoff Design Engineering'),
(751, '00:02:ED', 'DXO Telecom Co., Ltd.'),
(752, '00:02:EE', 'Nokia Danmark A/S'),
(753, '00:02:EF', 'CCC Network Systems Group Ltd.'),
(754, '00:02:F0', 'AME Optimedia Technology Co., Ltd.'),
(755, '00:02:F1', 'Pinetron Co., Ltd.'),
(756, '00:02:F2', 'eDevice, Inc.'),
(757, '00:02:F3', 'Media Serve Co., Ltd.'),
(758, '00:02:F4', 'PCTEL, Inc.'),
(759, '00:02:F5', 'VIVE Synergies, Inc.'),
(760, '00:02:F6', 'Equipe Communications'),
(761, '00:02:F7', 'ARM'),
(762, '00:02:F8', 'SEAKR Engineering, Inc.'),
(763, '00:02:F9', 'MIMOS Berhad'),
(764, '00:02:FA', 'DX Antenna Co., Ltd.'),
(765, '00:02:FB', 'Baumuller Aulugen-Systemtechnik GmbH'),
(766, '00:02:FC', 'CISCO SYSTEMS, INC.'),
(767, '00:02:FD', 'CISCO SYSTEMS, INC.'),
(768, '00:02:FE', 'Viditec, Inc.'),
(769, '00:02:FF', 'Handan BroadInfoCom'),
(770, '00:03:00', 'Barracuda Networks, Inc.'),
(771, '00:03:01', 'EXFO'),
(772, '00:03:02', 'Charles Industries, Ltd.'),
(773, '00:03:03', 'JAMA Electronics Co., Ltd.'),
(774, '00:03:04', 'Pacific Broadband Communications'),
(775, '00:03:05', 'MSC Vertriebs GmbH'),
(776, '00:03:06', 'Fusion In Tech Co., Ltd.'),
(777, '00:03:07', 'Secure Works, Inc.'),
(778, '00:03:08', 'AM Communications, Inc.'),
(779, '00:03:09', 'Texcel Technology PLC'),
(780, '00:03:0A', 'Argus Technologies'),
(781, '00:03:0B', 'Hunter Technology, Inc.'),
(782, '00:03:0C', 'Telesoft Technologies Ltd.'),
(783, '00:03:0D', 'Uniwill Computer Corp.'),
(784, '00:03:0E', 'Core Communications Co., Ltd.'),
(785, '00:03:0F', 'Digital China (Shanghai) Networks Ltd.'),
(786, '00:03:10', 'E-Globaledge Corporation'),
(787, '00:03:11', 'Micro Technology Co., Ltd.'),
(788, '00:03:12', 'TR-Systemtechnik GmbH'),
(789, '00:03:13', 'Access Media SPA'),
(790, '00:03:14', 'Teleware Network Systems'),
(791, '00:03:15', 'Cidco Incorporated'),
(792, '00:03:16', 'Nobell Communications, Inc.'),
(793, '00:03:17', 'Merlin Systems, Inc.'),
(794, '00:03:18', 'Cyras Systems, Inc.'),
(795, '00:03:19', 'Infineon AG'),
(796, '00:03:1A', 'Beijing Broad Telecom Ltd., China'),
(797, '00:03:1B', 'Cellvision Systems, Inc.'),
(798, '00:03:1C', 'Svenska Hardvarufabriken AB'),
(799, '00:03:1D', 'Taiwan Commate Computer, Inc.'),
(800, '00:03:1E', 'Optranet, Inc.'),
(801, '00:03:1F', 'Condev Ltd.'),
(802, '00:03:20', 'Xpeed, Inc.'),
(803, '00:03:21', 'Reco Research Co., Ltd.'),
(804, '00:03:22', 'IDIS Co., Ltd.'),
(805, '00:03:23', 'Cornet Technology, Inc.'),
(806, '00:03:24', 'SANYO Consumer Electronics Co., Ltd.'),
(807, '00:03:25', 'Arima Computer Corp.'),
(808, '00:03:26', 'Iwasaki Information Systems Co., Ltd.'),
(809, '00:03:27', 'ACT\'L'),
(810, '00:03:28', 'Mace Group, Inc.'),
(811, '00:03:29', 'F3, Inc.'),
(812, '00:03:2A', 'UniData Communication Systems, Inc.'),
(813, '00:03:2B', 'GAI Datenfunksysteme GmbH'),
(814, '00:03:2C', 'ABB Switzerland Ltd'),
(815, '00:03:2D', 'IBASE Technology, Inc.'),
(816, '00:03:2E', 'Scope Information Management, Ltd.'),
(817, '00:03:2F', 'Global Sun Technology, Inc.'),
(818, '00:03:30', 'Imagenics, Co., Ltd.'),
(819, '00:03:31', 'CISCO SYSTEMS, INC.'),
(820, '00:03:32', 'CISCO SYSTEMS, INC.'),
(821, '00:03:33', 'Digitel Co., Ltd.'),
(822, '00:03:34', 'Newport Electronics'),
(823, '00:03:35', 'Mirae Technology'),
(824, '00:03:36', 'Zetes Technologies'),
(825, '00:03:37', 'Vaone, Inc.'),
(826, '00:03:38', 'Oak Technology'),
(827, '00:03:39', 'Eurologic Systems, Ltd.'),
(828, '00:03:3A', 'Silicon Wave, Inc.'),
(829, '00:03:3B', 'TAMI Tech Co., Ltd.'),
(830, '00:03:3C', 'Daiden Co., Ltd.'),
(831, '00:03:3D', 'ILSHin Lab'),
(832, '00:03:3E', 'Tateyama System Laboratory Co., Ltd.'),
(833, '00:03:3F', 'BigBand Networks, Ltd.'),
(834, '00:03:40', 'Floware Wireless Systems, Ltd.'),
(835, '00:03:41', 'Axon Digital Design'),
(836, '00:03:42', 'Nortel Networks'),
(837, '00:03:43', 'Martin Professional A/S'),
(838, '00:03:44', 'Tietech.Co., Ltd.'),
(839, '00:03:45', 'Routrek Networks Corporation'),
(840, '00:03:46', 'Hitachi Kokusai Electric, Inc.'),
(841, '00:03:47', 'Intel Corporation'),
(842, '00:03:48', 'Norscan Instruments, Ltd.'),
(843, '00:03:49', 'Vidicode Datacommunicatie B.V.'),
(844, '00:03:4A', 'RIAS Corporation'),
(845, '00:03:4B', 'Nortel Networks'),
(846, '00:03:4C', 'Shanghai DigiVision Technology Co., Ltd.'),
(847, '00:03:4D', 'Chiaro Networks, Ltd.'),
(848, '00:03:4E', 'Pos Data Company, Ltd.'),
(849, '00:03:4F', 'Sur-Gard Security'),
(850, '00:03:50', 'BTICINO SPA'),
(851, '00:03:51', 'Diebold, Inc.'),
(852, '00:03:52', 'Colubris Networks'),
(853, '00:03:53', 'Mitac, Inc.'),
(854, '00:03:54', 'Fiber Logic Communications'),
(855, '00:03:55', 'TeraBeam Internet Systems'),
(856, '00:03:56', 'Wincor Nixdorf International GmbH'),
(857, '00:03:57', 'Intervoice-Brite, Inc.'),
(858, '00:03:58', 'Hanyang Digitech Co., Ltd.'),
(859, '00:03:59', 'DigitalSis'),
(860, '00:03:5A', 'Photron Limited'),
(861, '00:03:5B', 'BridgeWave Communications'),
(862, '00:03:5C', 'Saint Song Corp.'),
(863, '00:03:5D', 'Bosung Hi-Net Co., Ltd.'),
(864, '00:03:5E', 'Metropolitan Area Networks, Inc.'),
(865, '00:03:5F', 'Pr&uuml;ftechnik Condition Monitoring GmbH &amp; Co. KG'),
(866, '00:03:60', 'PAC Interactive Technology, Inc.'),
(867, '00:03:61', 'Widcomm, Inc.'),
(868, '00:03:62', 'Vodtel Communications, Inc.'),
(869, '00:03:63', 'Miraesys Co., Ltd.'),
(870, '00:03:64', 'Scenix Semiconductor, Inc.'),
(871, '00:03:65', 'Kira Information &amp; Communications, Ltd.'),
(872, '00:03:66', 'ASM Pacific Technology'),
(873, '00:03:67', 'Jasmine Networks, Inc.'),
(874, '00:03:68', 'Embedone Co., Ltd.'),
(875, '00:03:69', 'Nippon Antenna Co., Ltd.'),
(876, '00:03:6A', 'Mainnet, Ltd.'),
(877, '00:03:6B', 'CISCO SYSTEMS, INC.'),
(878, '00:03:6C', 'CISCO SYSTEMS, INC.'),
(879, '00:03:6D', 'Runtop, Inc.'),
(880, '00:03:6E', 'Nicon Systems (Pty) Limited'),
(881, '00:03:6F', 'Telsey SPA'),
(882, '00:03:70', 'NXTV, Inc.'),
(883, '00:03:71', 'Acomz Networks Corp.'),
(884, '00:03:72', 'ULAN'),
(885, '00:03:73', 'Aselsan A.S'),
(886, '00:03:74', 'Control Microsystems'),
(887, '00:03:75', 'NetMedia, Inc.'),
(888, '00:03:76', 'Graphtec Technology, Inc.'),
(889, '00:03:77', 'Gigabit Wireless'),
(890, '00:03:78', 'HUMAX Co., Ltd.'),
(891, '00:03:79', 'Proscend Communications, Inc.'),
(892, '00:03:7A', 'Taiyo Yuden Co., Ltd.'),
(893, '00:03:7B', 'IDEC IZUMI Corporation'),
(894, '00:03:7C', 'Coax Media'),
(895, '00:03:7D', 'Stellcom'),
(896, '00:03:7E', 'PORTech Communications, Inc.'),
(897, '00:03:7F', 'Atheros Communications, Inc.'),
(898, '00:03:80', 'SSH Communications Security Corp.'),
(899, '00:03:81', 'Ingenico International'),
(900, '00:03:82', 'A-One Co., Ltd.'),
(901, '00:03:83', 'Metera Networks, Inc.'),
(902, '00:03:84', 'AETA'),
(903, '00:03:85', 'Actelis Networks, Inc.'),
(904, '00:03:86', 'Ho Net, Inc.'),
(905, '00:03:87', 'Blaze Network Products'),
(906, '00:03:88', 'Fastfame Technology Co., Ltd.'),
(907, '00:03:89', 'Plantronics'),
(908, '00:03:8A', 'America Online, Inc.'),
(909, '00:03:8B', 'PLUS-ONE I&amp;T, Inc.'),
(910, '00:03:8C', 'Total Impact'),
(911, '00:03:8D', 'PCS Revenue Control Systems, Inc.'),
(912, '00:03:8E', 'Atoga Systems, Inc.'),
(913, '00:03:8F', 'Weinschel Corporation'),
(914, '00:03:90', 'Digital Video Communications, Inc.'),
(915, '00:03:91', 'Advanced Digital Broadcast, Ltd.'),
(916, '00:03:92', 'Hyundai Teletek Co., Ltd.'),
(917, '00:03:93', 'Apple'),
(918, '00:03:94', 'Connect One'),
(919, '00:03:95', 'California Amplifier'),
(920, '00:03:96', 'EZ Cast Co., Ltd.'),
(921, '00:03:97', 'Watchfront Limited'),
(922, '00:03:98', 'WISI'),
(923, '00:03:99', 'Dongju Informations &amp; Communications Co., Ltd.'),
(924, '00:03:9A', 'SiConnect'),
(925, '00:03:9B', 'NetChip Technology, Inc.'),
(926, '00:03:9C', 'OptiMight Communications, Inc.'),
(927, '00:03:9D', 'Qisda Corporation'),
(928, '00:03:9E', 'Tera System Co., Ltd.'),
(929, '00:03:9F', 'CISCO SYSTEMS, INC.'),
(930, '00:03:A0', 'CISCO SYSTEMS, INC.'),
(931, '00:03:A1', 'HIPER Information &amp; Communication, Inc.'),
(932, '00:03:A2', 'Catapult Communications'),
(933, '00:03:A3', 'MAVIX, Ltd.'),
(934, '00:03:A4', 'Imation Corp.'),
(935, '00:03:A5', 'Medea Corporation'),
(936, '00:03:A6', 'Traxit Technology, Inc.'),
(937, '00:03:A7', 'Unixtar Technology, Inc.'),
(938, '00:03:A8', 'IDOT Computers, Inc.'),
(939, '00:03:A9', 'AXCENT Media AG'),
(940, '00:03:AA', 'Watlow'),
(941, '00:03:AB', 'Bridge Information Systems'),
(942, '00:03:AC', 'Fronius Schweissmaschinen'),
(943, '00:03:AD', 'Emerson Energy Systems AB'),
(944, '00:03:AE', 'Allied Advanced Manufacturing Pte, Ltd.'),
(945, '00:03:AF', 'Paragea Communications'),
(946, '00:03:B0', 'Xsense Technology Corp.'),
(947, '00:03:B1', 'Hospira Inc.'),
(948, '00:03:B2', 'Radware'),
(949, '00:03:B3', 'IA Link Systems Co., Ltd.'),
(950, '00:03:B4', 'Macrotek International Corp.'),
(951, '00:03:B5', 'Entra Technology Co.'),
(952, '00:03:B6', 'QSI Corporation'),
(953, '00:03:B7', 'ZACCESS Systems'),
(954, '00:03:B8', 'NetKit Solutions, LLC'),
(955, '00:03:B9', 'Hualong Telecom Co., Ltd.'),
(956, '00:03:BA', 'Oracle Corporation'),
(957, '00:03:BB', 'Signal Communications Limited'),
(958, '00:03:BC', 'COT GmbH'),
(959, '00:03:BD', 'OmniCluster Technologies, Inc.'),
(960, '00:03:BE', 'Netility'),
(961, '00:03:BF', 'Centerpoint Broadband Technologies, Inc.'),
(962, '00:03:C0', 'RFTNC Co., Ltd.'),
(963, '00:03:C1', 'Packet Dynamics Ltd'),
(964, '00:03:C2', 'Solphone K.K.'),
(965, '00:03:C3', 'Micronik Multimedia'),
(966, '00:03:C4', 'Tomra Systems ASA'),
(967, '00:03:C5', 'Mobotix AG'),
(968, '00:03:C6', 'ICUE Systems, Inc.'),
(969, '00:03:C7', 'hopf Elektronik GmbH'),
(970, '00:03:C8', 'CML Emergency Services'),
(971, '00:03:C9', 'TECOM Co., Ltd.'),
(972, '00:03:CA', 'MTS Systems Corp.'),
(973, '00:03:CB', 'Nippon Systems Development Co., Ltd.'),
(974, '00:03:CC', 'Momentum Computer, Inc.'),
(975, '00:03:CD', 'Clovertech, Inc.'),
(976, '00:03:CE', 'ETEN Technologies, Inc.'),
(977, '00:03:CF', 'Muxcom, Inc.'),
(978, '00:03:D0', 'KOANKEISO Co., Ltd.'),
(979, '00:03:D1', 'Takaya Corporation'),
(980, '00:03:D2', 'Crossbeam Systems, Inc.'),
(981, '00:03:D3', 'Internet Energy Systems, Inc.'),
(982, '00:03:D4', 'Alloptic, Inc.'),
(983, '00:03:D5', 'Advanced Communications Co., Ltd.'),
(984, '00:03:D6', 'RADVision, Ltd.'),
(985, '00:03:D7', 'NextNet Wireless, Inc.'),
(986, '00:03:D8', 'iMPath Networks, Inc.'),
(987, '00:03:D9', 'Secheron SA'),
(988, '00:03:DA', 'Takamisawa Cybernetics Co., Ltd.'),
(989, '00:03:DB', 'Apogee Electronics Corp.'),
(990, '00:03:DC', 'Lexar Media, Inc.'),
(991, '00:03:DD', 'Comark Corp.'),
(992, '00:03:DE', 'OTC Wireless'),
(993, '00:03:DF', 'Desana Systems'),
(994, '00:03:E0', 'ARRIS Group, Inc.'),
(995, '00:03:E1', 'Winmate Communication, Inc.'),
(996, '00:03:E2', 'Comspace Corporation'),
(997, '00:03:E3', 'CISCO SYSTEMS, INC.'),
(998, '00:03:E4', 'CISCO SYSTEMS, INC.'),
(999, '00:03:E5', 'Hermstedt SG'),
(1000, '00:03:E6', 'Entone, Inc.'),
(1001, '00:03:E7', 'Logostek Co. Ltd.'),
(1002, '00:03:E8', 'Wavelength Digital Limited'),
(1003, '00:03:E9', 'Akara Canada, Inc.'),
(1004, '00:03:EA', 'Mega System Technologies, Inc.'),
(1005, '00:03:EB', 'Atrica'),
(1006, '00:03:EC', 'ICG Research, Inc.'),
(1007, '00:03:ED', 'Shinkawa Electric Co., Ltd.'),
(1008, '00:03:EE', 'MKNet Corporation'),
(1009, '00:03:EF', 'Oneline AG'),
(1010, '00:03:F0', 'Redfern Broadband Networks'),
(1011, '00:03:F1', 'Cicada Semiconductor, Inc.'),
(1012, '00:03:F2', 'Seneca Networks'),
(1013, '00:03:F3', 'Dazzle Multimedia, Inc.'),
(1014, '00:03:F4', 'NetBurner'),
(1015, '00:03:F5', 'Chip2Chip'),
(1016, '00:03:F6', 'Allegro Networks, Inc.'),
(1017, '00:03:F7', 'Plast-Control GmbH'),
(1018, '00:03:F8', 'SanCastle Technologies, Inc.'),
(1019, '00:03:F9', 'Pleiades Communications, Inc.'),
(1020, '00:03:FA', 'TiMetra Networks'),
(1021, '00:03:FB', 'ENEGATE Co.,Ltd.'),
(1022, '00:03:FC', 'Intertex Data AB'),
(1023, '00:03:FD', 'CISCO SYSTEMS, INC.'),
(1024, '00:03:FE', 'CISCO SYSTEMS, INC.'),
(1025, '00:03:FF', 'Microsoft Corporation'),
(1026, '00:04:00', 'LEXMARK INTERNATIONAL, INC.'),
(1027, '00:04:01', 'Osaki Electric Co., Ltd.'),
(1028, '00:04:02', 'Nexsan Technologies, Ltd.'),
(1029, '00:04:03', 'Nexsi Corporation'),
(1030, '00:04:04', 'Makino Milling Machine Co., Ltd.'),
(1031, '00:04:05', 'ACN Technologies'),
(1032, '00:04:06', 'Fa. Metabox AG'),
(1033, '00:04:07', 'Topcon Positioning Systems, Inc.'),
(1034, '00:04:08', 'Sanko Electronics Co., Ltd.'),
(1035, '00:04:09', 'Cratos Networks'),
(1036, '00:04:0A', 'Sage Systems'),
(1037, '00:04:0B', '3com Europe Ltd.'),
(1038, '00:04:0C', 'Kanno Works, Ltd.'),
(1039, '00:04:0D', 'Avaya, Inc.'),
(1040, '00:04:0E', 'AVM GmbH'),
(1041, '00:04:0F', 'Asus Network Technologies, Inc.'),
(1042, '00:04:10', 'Spinnaker Networks, Inc.'),
(1043, '00:04:11', 'Inkra Networks, Inc.'),
(1044, '00:04:12', 'WaveSmith Networks, Inc.'),
(1045, '00:04:13', 'SNOM Technology AG'),
(1046, '00:04:14', 'Umezawa Musen Denki Co., Ltd.'),
(1047, '00:04:15', 'Rasteme Systems Co., Ltd.'),
(1048, '00:04:16', 'Parks S/A Comunicacoes Digitais'),
(1049, '00:04:17', 'ELAU AG'),
(1050, '00:04:18', 'Teltronic S.A.U.'),
(1051, '00:04:19', 'Fibercycle Networks, Inc.'),
(1052, '00:04:1A', 'Ines Test and Measurement GmbH &amp; CoKG'),
(1053, '00:04:1B', 'Bridgeworks Ltd.'),
(1054, '00:04:1C', 'ipDialog, Inc.'),
(1055, '00:04:1D', 'Corega of America'),
(1056, '00:04:1E', 'Shikoku Instrumentation Co., Ltd.'),
(1057, '00:04:1F', 'Sony Computer Entertainment, Inc.'),
(1058, '00:04:20', 'Slim Devices, Inc.'),
(1059, '00:04:21', 'Ocular Networks'),
(1060, '00:04:22', 'Gordon Kapes, Inc.'),
(1061, '00:04:23', 'Intel Corporation'),
(1062, '00:04:24', 'TMC s.r.l.'),
(1063, '00:04:25', 'Atmel Corporation'),
(1064, '00:04:26', 'Autosys'),
(1065, '00:04:27', 'CISCO SYSTEMS, INC.'),
(1066, '00:04:28', 'CISCO SYSTEMS, INC.'),
(1067, '00:04:29', 'Pixord Corporation'),
(1068, '00:04:2A', 'Wireless Networks, Inc.'),
(1069, '00:04:2B', 'IT Access Co., Ltd.'),
(1070, '00:04:2C', 'Minet, Inc.'),
(1071, '00:04:2D', 'Sarian Systems, Ltd.'),
(1072, '00:04:2E', 'Netous Technologies, Ltd.'),
(1073, '00:04:2F', 'International Communications Products, Inc.'),
(1074, '00:04:30', 'Netgem'),
(1075, '00:04:31', 'GlobalStreams, Inc.'),
(1076, '00:04:32', 'Voyetra Turtle Beach, Inc.'),
(1077, '00:04:33', 'Cyberboard A/S'),
(1078, '00:04:34', 'Accelent Systems, Inc.'),
(1079, '00:04:35', 'Comptek International, Inc.'),
(1080, '00:04:36', 'ELANsat Technologies, Inc.'),
(1081, '00:04:37', 'Powin Information Technology, Inc.'),
(1082, '00:04:38', 'Nortel Networks'),
(1083, '00:04:39', 'Rosco Entertainment Technology, Inc.'),
(1084, '00:04:3A', 'Intelligent Telecommunications, Inc.'),
(1085, '00:04:3B', 'Lava Computer Mfg., Inc.'),
(1086, '00:04:3C', 'SONOS Co., Ltd.'),
(1087, '00:04:3D', 'INDEL AG'),
(1088, '00:04:3E', 'Telencomm'),
(1089, '00:04:3F', 'ESTeem Wireless Modems, Inc'),
(1090, '00:04:40', 'cyberPIXIE, Inc.'),
(1091, '00:04:41', 'Half Dome Systems, Inc.'),
(1092, '00:04:42', 'NACT'),
(1093, '00:04:43', 'Agilent Technologies, Inc.'),
(1094, '00:04:44', 'Western Multiplex Corporation'),
(1095, '00:04:45', 'LMS Skalar Instruments GmbH'),
(1096, '00:04:46', 'CYZENTECH Co., Ltd.'),
(1097, '00:04:47', 'Acrowave Systems Co., Ltd.'),
(1098, '00:04:48', 'Polaroid Corporation'),
(1099, '00:04:49', 'Mapletree Networks'),
(1100, '00:04:4A', 'iPolicy Networks, Inc.'),
(1101, '00:04:4B', 'NVIDIA'),
(1102, '00:04:4C', 'JENOPTIK'),
(1103, '00:04:4D', 'CISCO SYSTEMS, INC.'),
(1104, '00:04:4E', 'CISCO SYSTEMS, INC.'),
(1105, '00:04:4F', 'Leukhardt Systemelektronik GmbH'),
(1106, '00:04:50', 'DMD Computers SRL'),
(1107, '00:04:51', 'Medrad, Inc.'),
(1108, '00:04:52', 'RocketLogix, Inc.'),
(1109, '00:04:53', 'YottaYotta, Inc.'),
(1110, '00:04:54', 'Quadriga UK'),
(1111, '00:04:55', 'ANTARA.net'),
(1112, '00:04:56', 'Cambium Networks Limited'),
(1113, '00:04:57', 'Universal Access Technology, Inc.'),
(1114, '00:04:58', 'Fusion X Co., Ltd.'),
(1115, '00:04:59', 'Veristar Corporation'),
(1116, '00:04:5A', 'The Linksys Group, Inc.'),
(1117, '00:04:5B', 'Techsan Electronics Co., Ltd.'),
(1118, '00:04:5C', 'Mobiwave Pte Ltd'),
(1119, '00:04:5D', 'BEKA Elektronik'),
(1120, '00:04:5E', 'PolyTrax Information Technology AG'),
(1121, '00:04:5F', 'Avalue Technology, Inc.'),
(1122, '00:04:60', 'Knilink Technology, Inc.'),
(1123, '00:04:61', 'EPOX Computer Co., Ltd.'),
(1124, '00:04:62', 'DAKOS Data &amp; Communication Co., Ltd.'),
(1125, '00:04:63', 'Bosch Security Systems'),
(1126, '00:04:64', 'Pulse-Link Inc'),
(1127, '00:04:65', 'i.s.t isdn-support technik GmbH'),
(1128, '00:04:66', 'ARMITEL Co.'),
(1129, '00:04:67', 'Wuhan Research Institute of MII'),
(1130, '00:04:68', 'Vivity, Inc.'),
(1131, '00:04:69', 'Innocom, Inc.'),
(1132, '00:04:6A', 'Navini Networks'),
(1133, '00:04:6B', 'Palm Wireless, Inc.'),
(1134, '00:04:6C', 'Cyber Technology Co., Ltd.'),
(1135, '00:04:6D', 'CISCO SYSTEMS, INC.'),
(1136, '00:04:6E', 'CISCO SYSTEMS, INC.'),
(1137, '00:04:6F', 'Digitel S/A Industria Eletronica'),
(1138, '00:04:70', 'ipUnplugged AB'),
(1139, '00:04:71', 'IPrad'),
(1140, '00:04:72', 'Telelynx, Inc.'),
(1141, '00:04:73', 'Photonex Corporation'),
(1142, '00:04:74', 'LEGRAND'),
(1143, '00:04:75', '3 Com Corporation'),
(1144, '00:04:76', '3 Com Corporation'),
(1145, '00:04:77', 'Scalant Systems, Inc.'),
(1146, '00:04:78', 'G. Star Technology Corporation'),
(1147, '00:04:79', 'Radius Co., Ltd.'),
(1148, '00:04:7A', 'AXXESSIT ASA'),
(1149, '00:04:7B', 'Schlumberger'),
(1150, '00:04:7C', 'Skidata AG'),
(1151, '00:04:7D', 'Pelco'),
(1152, '00:04:7E', 'Siqura B.V.'),
(1153, '00:04:7F', 'Chr. Mayr GmbH &amp; Co. KG'),
(1154, '00:04:80', 'Brocade Communications Systems, Inc'),
(1155, '00:04:81', 'Econolite Control Products, Inc.'),
(1156, '00:04:82', 'Medialogic Corp.'),
(1157, '00:04:83', 'Deltron Technology, Inc.'),
(1158, '00:04:84', 'Amann GmbH'),
(1159, '00:04:85', 'PicoLight'),
(1160, '00:04:86', 'ITTC, University of Kansas'),
(1161, '00:04:87', 'Cogency Semiconductor, Inc.'),
(1162, '00:04:88', 'Eurotherm Controls'),
(1163, '00:04:89', 'YAFO Networks, Inc.'),
(1164, '00:04:8A', 'Temia Vertriebs GmbH'),
(1165, '00:04:8B', 'Poscon Corporation'),
(1166, '00:04:8C', 'Nayna Networks, Inc.'),
(1167, '00:04:8D', 'Tone Commander Systems, Inc.'),
(1168, '00:04:8E', 'Ohm Tech Labs, Inc.'),
(1169, '00:04:8F', 'TD Systems Corporation'),
(1170, '00:04:90', 'Optical Access'),
(1171, '00:04:91', 'Technovision, Inc.'),
(1172, '00:04:92', 'Hive Internet, Ltd.'),
(1173, '00:04:93', 'Tsinghua Unisplendour Co., Ltd.'),
(1174, '00:04:94', 'Breezecom, Ltd.'),
(1175, '00:04:95', 'Tejas Networks India Limited'),
(1176, '00:04:96', 'Extreme Networks'),
(1177, '00:04:97', 'MacroSystem Digital Video AG'),
(1178, '00:04:98', 'Mahi Networks'),
(1179, '00:04:99', 'Chino Corporation'),
(1180, '00:04:9A', 'CISCO SYSTEMS, INC.'),
(1181, '00:04:9B', 'CISCO SYSTEMS, INC.'),
(1182, '00:04:9C', 'Surgient Networks, Inc.'),
(1183, '00:04:9D', 'Ipanema Technologies'),
(1184, '00:04:9E', 'Wirelink Co., Ltd.'),
(1185, '00:04:9F', 'Freescale Semiconductor'),
(1186, '00:04:A0', 'Verity Instruments, Inc.'),
(1187, '00:04:A1', 'Pathway Connectivity'),
(1188, '00:04:A2', 'L.S.I. Japan Co., Ltd.'),
(1189, '00:04:A3', 'Microchip Technology, Inc.'),
(1190, '00:04:A4', 'NetEnabled, Inc.'),
(1191, '00:04:A5', 'Barco Projection Systems NV'),
(1192, '00:04:A6', 'SAF Tehnika Ltd.'),
(1193, '00:04:A7', 'FabiaTech Corporation'),
(1194, '00:04:A8', 'Broadmax Technologies, Inc.'),
(1195, '00:04:A9', 'SandStream Technologies, Inc.'),
(1196, '00:04:AA', 'Jetstream Communications'),
(1197, '00:04:AB', 'Comverse Network Systems, Inc.'),
(1198, '00:04:AC', 'IBM Corp'),
(1199, '00:04:AD', 'Malibu Networks'),
(1200, '00:04:AE', 'Sullair Corporation'),
(1201, '00:04:AF', 'Digital Fountain, Inc.'),
(1202, '00:04:B0', 'ELESIGN Co., Ltd.'),
(1203, '00:04:B1', 'Signal Technology, Inc.'),
(1204, '00:04:B2', 'ESSEGI SRL'),
(1205, '00:04:B3', 'Videotek, Inc.'),
(1206, '00:04:B4', 'CIAC'),
(1207, '00:04:B5', 'Equitrac Corporation'),
(1208, '00:04:B6', 'Stratex Networks, Inc.'),
(1209, '00:04:B7', 'AMB i.t. Holding'),
(1210, '00:04:B8', 'Kumahira Co., Ltd.'),
(1211, '00:04:B9', 'S.I. Soubou, Inc.'),
(1212, '00:04:BA', 'KDD Media Will Corporation'),
(1213, '00:04:BB', 'Bardac Corporation'),
(1214, '00:04:BC', 'Giantec, Inc.'),
(1215, '00:04:BD', 'ARRIS Group, Inc.'),
(1216, '00:04:BE', 'OptXCon, Inc.'),
(1217, '00:04:BF', 'VersaLogic Corp.'),
(1218, '00:04:C0', 'CISCO SYSTEMS, INC.'),
(1219, '00:04:C1', 'CISCO SYSTEMS, INC.'),
(1220, '00:04:C2', 'Magnipix, Inc.'),
(1221, '00:04:C3', 'CASTOR Informatique'),
(1222, '00:04:C4', 'Allen &amp; Heath Limited'),
(1223, '00:04:C5', 'ASE Technologies, USA'),
(1224, '00:04:C6', 'Yamaha Motor Co., Ltd.'),
(1225, '00:04:C7', 'NetMount'),
(1226, '00:04:C8', 'LIBA Maschinenfabrik GmbH'),
(1227, '00:04:C9', 'Micro Electron Co., Ltd.'),
(1228, '00:04:CA', 'FreeMs Corp.'),
(1229, '00:04:CB', 'Tdsoft Communication, Ltd.'),
(1230, '00:04:CC', 'Peek Traffic B.V.'),
(1231, '00:04:CD', 'Extenway Solutions Inc'),
(1232, '00:04:CE', 'Patria Ailon'),
(1233, '00:04:CF', 'Seagate Technology'),
(1234, '00:04:D0', 'Softlink s.r.o.'),
(1235, '00:04:D1', 'Drew Technologies, Inc.'),
(1236, '00:04:D2', 'Adcon Telemetry GmbH'),
(1237, '00:04:D3', 'Toyokeiki Co., Ltd.'),
(1238, '00:04:D4', 'Proview Electronics Co., Ltd.'),
(1239, '00:04:D5', 'Hitachi Information &amp; Communication Engineering, Ltd.'),
(1240, '00:04:D6', 'Takagi Industrial Co., Ltd.'),
(1241, '00:04:D7', 'Omitec Instrumentation Ltd.'),
(1242, '00:04:D8', 'IPWireless, Inc.'),
(1243, '00:04:D9', 'Titan Electronics, Inc.'),
(1244, '00:04:DA', 'Relax Technology, Inc.'),
(1245, '00:04:DB', 'Tellus Group Corp.'),
(1246, '00:04:DC', 'Nortel Networks'),
(1247, '00:04:DD', 'CISCO SYSTEMS, INC.'),
(1248, '00:04:DE', 'CISCO SYSTEMS, INC.'),
(1249, '00:04:DF', 'Teracom Telematica Ltda.'),
(1250, '00:04:E0', 'Procket Networks'),
(1251, '00:04:E1', 'Infinior Microsystems'),
(1252, '00:04:E2', 'SMC Networks, Inc.'),
(1253, '00:04:E3', 'Accton Technology Corp.'),
(1254, '00:04:E4', 'Daeryung Ind., Inc.'),
(1255, '00:04:E5', 'Glonet Systems, Inc.'),
(1256, '00:04:E6', 'Banyan Network Private Limited'),
(1257, '00:04:E7', 'Lightpointe Communications, Inc'),
(1258, '00:04:E8', 'IER, Inc.'),
(1259, '00:04:E9', 'Infiniswitch Corporation'),
(1260, '00:04:EA', 'Hewlett-Packard Company'),
(1261, '00:04:EB', 'Paxonet Communications, Inc.'),
(1262, '00:04:EC', 'Memobox SA'),
(1263, '00:04:ED', 'Billion Electric Co., Ltd.'),
(1264, '00:04:EE', 'Lincoln Electric Company'),
(1265, '00:04:EF', 'Polestar Corp.'),
(1266, '00:04:F0', 'International Computers, Ltd'),
(1267, '00:04:F1', 'WhereNet'),
(1268, '00:04:F2', 'Polycom'),
(1269, '00:04:F3', 'FS FORTH-SYSTEME GmbH'),
(1270, '00:04:F4', 'Infinite Electronics Inc.'),
(1271, '00:04:F5', 'SnowShore Networks, Inc.'),
(1272, '00:04:F6', 'Amphus'),
(1273, '00:04:F7', 'Omega Band, Inc.'),
(1274, '00:04:F8', 'QUALICABLE TV Industria E Com., Ltda'),
(1275, '00:04:F9', 'Xtera Communications, Inc.'),
(1276, '00:04:FA', 'NBS Technologies Inc.'),
(1277, '00:04:FB', 'Commtech, Inc.'),
(1278, '00:04:FC', 'Stratus Computer (DE), Inc.'),
(1279, '00:04:FD', 'Japan Control Engineering Co., Ltd.'),
(1280, '00:04:FE', 'Pelago Networks'),
(1281, '00:04:FF', 'Acronet Co., Ltd.'),
(1282, '00:05:00', 'CISCO SYSTEMS, INC.'),
(1283, '00:05:01', 'CISCO SYSTEMS, INC.'),
(1284, '00:05:02', 'Apple'),
(1285, '00:05:03', 'ICONAG'),
(1286, '00:05:04', 'Naray Information &amp; Communication Enterprise'),
(1287, '00:05:05', 'Systems Integration Solutions, Inc.'),
(1288, '00:05:06', 'Reddo Networks AB'),
(1289, '00:05:07', 'Fine Appliance Corp.'),
(1290, '00:05:08', 'Inetcam, Inc.'),
(1291, '00:05:09', 'AVOC Nishimura Ltd.'),
(1292, '00:05:0A', 'ICS Spa'),
(1293, '00:05:0B', 'SICOM Systems, Inc.'),
(1294, '00:05:0C', 'Network Photonics, Inc.'),
(1295, '00:05:0D', 'Midstream Technologies, Inc.'),
(1296, '00:05:0E', '3ware, Inc.'),
(1297, '00:05:0F', 'Tanaka S/S Ltd.'),
(1298, '00:05:10', 'Infinite Shanghai Communication Terminals Ltd.'),
(1299, '00:05:11', 'Complementary Technologies Ltd'),
(1300, '00:05:12', 'Zebra Technologies Inc'),
(1301, '00:05:13', 'VTLinx Multimedia Systems, Inc.'),
(1302, '00:05:14', 'KDT Systems Co., Ltd.'),
(1303, '00:05:15', 'Nuark Co., Ltd.'),
(1304, '00:05:16', 'SMART Modular Technologies'),
(1305, '00:05:17', 'Shellcomm, Inc.'),
(1306, '00:05:18', 'Jupiters Technology'),
(1307, '00:05:19', 'Siemens Building Technologies AG,'),
(1308, '00:05:1A', '3Com Europe Ltd.'),
(1309, '00:05:1B', 'Magic Control Technology Corporation'),
(1310, '00:05:1C', 'Xnet Technology Corp.'),
(1311, '00:05:1D', 'Airocon, Inc.'),
(1312, '00:05:1E', 'Brocade Communications Systems, Inc.'),
(1313, '00:05:1F', 'Taijin Media Co., Ltd.'),
(1314, '00:05:20', 'Smartronix, Inc.'),
(1315, '00:05:21', 'Control Microsystems'),
(1316, '00:05:22', 'LEA*D Corporation, Inc.'),
(1317, '00:05:23', 'AVL List GmbH'),
(1318, '00:05:24', 'BTL System (HK) Limited'),
(1319, '00:05:25', 'Puretek Industrial Co., Ltd.'),
(1320, '00:05:26', 'IPAS GmbH'),
(1321, '00:05:27', 'SJ Tek Co. Ltd'),
(1322, '00:05:28', 'New Focus, Inc.'),
(1323, '00:05:29', 'Shanghai Broadan Communication Technology Co., Ltd'),
(1324, '00:05:2A', 'Ikegami Tsushinki Co., Ltd.'),
(1325, '00:05:2B', 'HORIBA, Ltd.'),
(1326, '00:05:2C', 'Supreme Magic Corporation'),
(1327, '00:05:2D', 'Zoltrix International Limited'),
(1328, '00:05:2E', 'Cinta Networks'),
(1329, '00:05:2F', 'Leviton Network Solutions'),
(1330, '00:05:30', 'Andiamo Systems, Inc.'),
(1331, '00:05:31', 'CISCO SYSTEMS, INC.'),
(1332, '00:05:32', 'CISCO SYSTEMS, INC.'),
(1333, '00:05:33', 'Brocade Communications Systems, Inc.'),
(1334, '00:05:34', 'Northstar Engineering Ltd.'),
(1335, '00:05:35', 'Chip PC Ltd.'),
(1336, '00:05:36', 'Danam Communications, Inc.'),
(1337, '00:05:37', 'Nets Technology Co., Ltd.'),
(1338, '00:05:38', 'Merilus, Inc.'),
(1339, '00:05:39', 'A Brand New World in Sweden AB'),
(1340, '00:05:3A', 'Willowglen Services Pte Ltd'),
(1341, '00:05:3B', 'Harbour Networks Ltd., Co. Beijing'),
(1342, '00:05:3C', 'Xircom'),
(1343, '00:05:3D', 'Agere Systems'),
(1344, '00:05:3E', 'KID Systeme GmbH'),
(1345, '00:05:3F', 'VisionTek, Inc.'),
(1346, '00:05:40', 'FAST Corporation'),
(1347, '00:05:41', 'Advanced Systems Co., Ltd.'),
(1348, '00:05:42', 'Otari, Inc.'),
(1349, '00:05:43', 'IQ Wireless GmbH'),
(1350, '00:05:44', 'Valley Technologies, Inc.'),
(1351, '00:05:45', 'Internet Photonics'),
(1352, '00:05:46', 'KDDI Network &amp; Solultions Inc.'),
(1353, '00:05:47', 'Starent Networks'),
(1354, '00:05:48', 'Disco Corporation'),
(1355, '00:05:49', 'Salira Optical Network Systems'),
(1356, '00:05:4A', 'Ario Data Networks, Inc.'),
(1357, '00:05:4B', 'Eaton Automation AG'),
(1358, '00:05:4C', 'RF Innovations Pty Ltd'),
(1359, '00:05:4D', 'Brans Technologies, Inc.'),
(1360, '00:05:4E', 'Philips'),
(1361, '00:05:4F', 'PRIVATE'),
(1362, '00:05:50', 'Vcomms Connect Limited'),
(1363, '00:05:51', 'F &amp; S Elektronik Systeme GmbH'),
(1364, '00:05:52', 'Xycotec Computer GmbH'),
(1365, '00:05:53', 'DVC Company, Inc.'),
(1366, '00:05:54', 'Rangestar Wireless'),
(1367, '00:05:55', 'Japan Cash Machine Co., Ltd.'),
(1368, '00:05:56', '360 Systems'),
(1369, '00:05:57', 'Agile TV Corporation'),
(1370, '00:05:58', 'Synchronous, Inc.'),
(1371, '00:05:59', 'Intracom S.A.'),
(1372, '00:05:5A', 'Power Dsine Ltd.'),
(1373, '00:05:5B', 'Charles Industries, Ltd.'),
(1374, '00:05:5C', 'Kowa Company, Ltd.'),
(1375, '00:05:5D', 'D-Link Systems, Inc.'),
(1376, '00:05:5E', 'CISCO SYSTEMS, INC.'),
(1377, '00:05:5F', 'CISCO SYSTEMS, INC.'),
(1378, '00:05:60', 'LEADER COMM.CO., LTD'),
(1379, '00:05:61', 'nac Image Technology, Inc.'),
(1380, '00:05:62', 'Digital View Limited'),
(1381, '00:05:63', 'J-Works, Inc.'),
(1382, '00:05:64', 'Tsinghua Bitway Co., Ltd.'),
(1383, '00:05:65', 'Tailyn Communication Company Ltd.'),
(1384, '00:05:66', 'Secui.com Corporation'),
(1385, '00:05:67', 'Etymonic Design, Inc.'),
(1386, '00:05:68', 'Piltofish Networks AB'),
(1387, '00:05:69', 'VMware, Inc.'),
(1388, '00:05:6A', 'Heuft Systemtechnik GmbH'),
(1389, '00:05:6B', 'C.P. Technology Co., Ltd.'),
(1390, '00:05:6C', 'Hung Chang Co., Ltd.'),
(1391, '00:05:6D', 'Pacific Corporation'),
(1392, '00:05:6E', 'National Enhance Technology, Inc.'),
(1393, '00:05:6F', 'Innomedia Technologies Pvt. Ltd.'),
(1394, '00:05:70', 'Baydel Ltd.'),
(1395, '00:05:71', 'Seiwa Electronics Co.'),
(1396, '00:05:72', 'Deonet Co., Ltd.'),
(1397, '00:05:73', 'CISCO SYSTEMS, INC.'),
(1398, '00:05:74', 'CISCO SYSTEMS, INC.'),
(1399, '00:05:75', 'CDS-Electronics BV'),
(1400, '00:05:76', 'NSM Technology Ltd.'),
(1401, '00:05:77', 'SM Information &amp; Communication'),
(1402, '00:05:78', 'PRIVATE'),
(1403, '00:05:79', 'Universal Control Solution Corp.'),
(1404, '00:05:7A', 'Overture Networks'),
(1405, '00:05:7B', 'Chung Nam Electronic Co., Ltd.'),
(1406, '00:05:7C', 'RCO Security AB'),
(1407, '00:05:7D', 'Sun Communications, Inc.'),
(1408, '00:05:7E', 'Eckelmann Steuerungstechnik GmbH'),
(1409, '00:05:7F', 'Acqis Technology'),
(1410, '00:05:80', 'FibroLAN Ltd.'),
(1411, '00:05:81', 'Snell'),
(1412, '00:05:82', 'ClearCube Technology'),
(1413, '00:05:83', 'ImageCom Limited'),
(1414, '00:05:84', 'AbsoluteValue Systems, Inc.'),
(1415, '00:05:85', 'Juniper Networks, Inc.'),
(1416, '00:05:86', 'Lucent Technologies'),
(1417, '00:05:87', 'Locus, Incorporated'),
(1418, '00:05:88', 'Sensoria Corp.'),
(1419, '00:05:89', 'National Datacomputer'),
(1420, '00:05:8A', 'Netcom Co., Ltd.'),
(1421, '00:05:8B', 'IPmental, Inc.'),
(1422, '00:05:8C', 'Opentech Inc.'),
(1423, '00:05:8D', 'Lynx Photonic Networks, Inc.'),
(1424, '00:05:8E', 'Flextronics International GmbH &amp; Co. Nfg. KG'),
(1425, '00:05:8F', 'CLCsoft co.'),
(1426, '00:05:90', 'Swissvoice Ltd.'),
(1427, '00:05:91', 'Active Silicon Ltd'),
(1428, '00:05:92', 'Pultek Corp.'),
(1429, '00:05:93', 'Grammar Engine Inc.'),
(1430, '00:05:94', 'IXXAT Automation GmbH'),
(1431, '00:05:95', 'Alesis Corporation'),
(1432, '00:05:96', 'Genotech Co., Ltd.'),
(1433, '00:05:97', 'Eagle Traffic Control Systems'),
(1434, '00:05:98', 'CRONOS S.r.l.'),
(1435, '00:05:99', 'DRS Test and Energy Management or DRS-TEM'),
(1436, '00:05:9A', 'CISCO SYSTEMS, INC.'),
(1437, '00:05:9B', 'CISCO SYSTEMS, INC.'),
(1438, '00:05:9C', 'Kleinknecht GmbH, Ing. B&uuml;ro'),
(1439, '00:05:9D', 'Daniel Computing Systems, Inc.'),
(1440, '00:05:9E', 'Zinwell Corporation'),
(1441, '00:05:9F', 'Yotta Networks, Inc.'),
(1442, '00:05:A0', 'MOBILINE Kft.'),
(1443, '00:05:A1', 'Zenocom'),
(1444, '00:05:A2', 'CELOX Networks'),
(1445, '00:05:A3', 'QEI, Inc.'),
(1446, '00:05:A4', 'Lucid Voice Ltd.'),
(1447, '00:05:A5', 'KOTT'),
(1448, '00:05:A6', 'Extron Electronics'),
(1449, '00:05:A7', 'Hyperchip, Inc.'),
(1450, '00:05:A8', 'WYLE ELECTRONICS'),
(1451, '00:05:A9', 'Princeton Networks, Inc.'),
(1452, '00:05:AA', 'Moore Industries International Inc.'),
(1453, '00:05:AB', 'Cyber Fone, Inc.'),
(1454, '00:05:AC', 'Northern Digital, Inc.'),
(1455, '00:05:AD', 'Topspin Communications, Inc.'),
(1456, '00:05:AE', 'Mediaport USA'),
(1457, '00:05:AF', 'InnoScan Computing A/S'),
(1458, '00:05:B0', 'Korea Computer Technology Co., Ltd.'),
(1459, '00:05:B1', 'ASB Technology BV'),
(1460, '00:05:B2', 'Medison Co., Ltd.'),
(1461, '00:05:B3', 'Asahi-Engineering Co., Ltd.'),
(1462, '00:05:B4', 'Aceex Corporation'),
(1463, '00:05:B5', 'Broadcom Technologies'),
(1464, '00:05:B6', 'INSYS Microelectronics GmbH'),
(1465, '00:05:B7', 'Arbor Technology Corp.'),
(1466, '00:05:B8', 'Electronic Design Associates, Inc.'),
(1467, '00:05:B9', 'Airvana, Inc.'),
(1468, '00:05:BA', 'Area Netwoeks, Inc.'),
(1469, '00:05:BB', 'Myspace AB'),
(1470, '00:05:BC', 'Resource Data Management Ltd'),
(1471, '00:05:BD', 'ROAX BV'),
(1472, '00:05:BE', 'Kongsberg Seatex AS'),
(1473, '00:05:BF', 'JustEzy Technology, Inc.'),
(1474, '00:05:C0', 'Digital Network Alacarte Co., Ltd.'),
(1475, '00:05:C1', 'A-Kyung Motion, Inc.'),
(1476, '00:05:C2', 'Soronti, Inc.'),
(1477, '00:05:C3', 'Pacific Instruments, Inc.'),
(1478, '00:05:C4', 'Telect, Inc.'),
(1479, '00:05:C5', 'Flaga HF'),
(1480, '00:05:C6', 'Triz Communications'),
(1481, '00:05:C7', 'I/F-COM A/S'),
(1482, '00:05:C8', 'VERYTECH'),
(1483, '00:05:C9', 'LG Innotek Co., Ltd.'),
(1484, '00:05:CA', 'Hitron Technology, Inc.'),
(1485, '00:05:CB', 'ROIS Technologies, Inc.'),
(1486, '00:05:CC', 'Sumtel Communications, Inc.'),
(1487, '00:05:CD', 'Denon, Ltd.'),
(1488, '00:05:CE', 'Prolink Microsystems Corporation'),
(1489, '00:05:CF', 'Thunder River Technologies, Inc.'),
(1490, '00:05:D0', 'Solinet Systems'),
(1491, '00:05:D1', 'Metavector Technologies'),
(1492, '00:05:D2', 'DAP Technologies'),
(1493, '00:05:D3', 'eProduction Solutions, Inc.'),
(1494, '00:05:D4', 'FutureSmart Networks, Inc.'),
(1495, '00:05:D5', 'Speedcom Wireless'),
(1496, '00:05:D6', 'L-3 Linkabit'),
(1497, '00:05:D7', 'Vista Imaging, Inc.'),
(1498, '00:05:D8', 'Arescom, Inc.'),
(1499, '00:05:D9', 'Techno Valley, Inc.'),
(1500, '00:05:DA', 'Apex Automationstechnik'),
(1501, '00:05:DB', 'PSI Nentec GmbH'),
(1502, '00:05:DC', 'CISCO SYSTEMS, INC.'),
(1503, '00:05:DD', 'CISCO SYSTEMS, INC.'),
(1504, '00:05:DE', 'Gi Fone Korea, Inc.'),
(1505, '00:05:DF', 'Electronic Innovation, Inc.'),
(1506, '00:05:E0', 'Empirix Corp.'),
(1507, '00:05:E1', 'Trellis Photonics, Ltd.'),
(1508, '00:05:E2', 'Creativ Network Technologies'),
(1509, '00:05:E3', 'LightSand Communications, Inc.'),
(1510, '00:05:E4', 'Red Lion Controls Inc.'),
(1511, '00:05:E5', 'Renishaw PLC'),
(1512, '00:05:E6', 'Egenera, Inc.'),
(1513, '00:05:E7', 'Netrake an AudioCodes Company'),
(1514, '00:05:E8', 'TurboWave, Inc.'),
(1515, '00:05:E9', 'Unicess Network, Inc.'),
(1516, '00:05:EA', 'Rednix'),
(1517, '00:05:EB', 'Blue Ridge Networks, Inc.'),
(1518, '00:05:EC', 'Mosaic Systems Inc.'),
(1519, '00:05:ED', 'Technikum Joanneum GmbH'),
(1520, '00:05:EE', 'Siemens AB, Infrastructure &amp; Cities, Building Technologies Division, IC BT SSP SP BA PR'),
(1521, '00:05:EF', 'ADOIR Digital Technology'),
(1522, '00:05:F0', 'SATEC'),
(1523, '00:05:F1', 'Vrcom, Inc.'),
(1524, '00:05:F2', 'Power R, Inc.'),
(1525, '00:05:F3', 'Webyn'),
(1526, '00:05:F4', 'System Base Co., Ltd.'),
(1527, '00:05:F5', 'Geospace Technologies'),
(1528, '00:05:F6', 'Young Chang Co. Ltd.'),
(1529, '00:05:F7', 'Analog Devices, Inc.'),
(1530, '00:05:F8', 'Real Time Access, Inc.'),
(1531, '00:05:F9', 'TOA Corporation'),
(1532, '00:05:FA', 'IPOptical, Inc.'),
(1533, '00:05:FB', 'ShareGate, Inc.'),
(1534, '00:05:FC', 'Schenck Pegasus Corp.'),
(1535, '00:05:FD', 'PacketLight Networks Ltd.'),
(1536, '00:05:FE', 'Traficon N.V.'),
(1537, '00:05:FF', 'SNS Solutions, Inc.'),
(1538, '00:06:00', 'Toshiba Teli Corporation'),
(1539, '00:06:01', 'Otanikeiki Co., Ltd.'),
(1540, '00:06:02', 'Cirkitech Electronics Co.'),
(1541, '00:06:03', 'Baker Hughes Inc.'),
(1542, '00:06:04', '@Track Communications, Inc.'),
(1543, '00:06:05', 'Inncom International, Inc.'),
(1544, '00:06:06', 'RapidWAN, Inc.'),
(1545, '00:06:07', 'Omni Directional Control Technology Inc.'),
(1546, '00:06:08', 'At-Sky SAS'),
(1547, '00:06:09', 'Crossport Systems'),
(1548, '00:06:0A', 'Blue2space'),
(1549, '00:06:0B', 'Artesyn Embedded Technologies'),
(1550, '00:06:0C', 'Melco Industries, Inc.'),
(1551, '00:06:0D', 'Wave7 Optics'),
(1552, '00:06:0E', 'IGYS Systems, Inc.'),
(1553, '00:06:0F', 'Narad Networks Inc'),
(1554, '00:06:10', 'Abeona Networks Inc'),
(1555, '00:06:11', 'Zeus Wireless, Inc.'),
(1556, '00:06:12', 'Accusys, Inc.'),
(1557, '00:06:13', 'Kawasaki Microelectronics Incorporated'),
(1558, '00:06:14', 'Prism Holdings'),
(1559, '00:06:15', 'Kimoto Electric Co., Ltd.'),
(1560, '00:06:16', 'Tel Net Co., Ltd.'),
(1561, '00:06:17', 'Redswitch Inc.'),
(1562, '00:06:18', 'DigiPower Manufacturing Inc.'),
(1563, '00:06:19', 'Connection Technology Systems'),
(1564, '00:06:1A', 'Zetari Inc.'),
(1565, '00:06:1B', 'Notebook Development Lab.  Lenovo Japan Ltd.'),
(1566, '00:06:1C', 'Hoshino Metal Industries, Ltd.'),
(1567, '00:06:1D', 'MIP Telecom, Inc.'),
(1568, '00:06:1E', 'Maxan Systems'),
(1569, '00:06:1F', 'Vision Components GmbH'),
(1570, '00:06:20', 'Serial System Ltd.'),
(1571, '00:06:21', 'Hinox, Co., Ltd.'),
(1572, '00:06:22', 'Chung Fu Chen Yeh Enterprise Corp.'),
(1573, '00:06:23', 'MGE UPS Systems France'),
(1574, '00:06:24', 'Gentner Communications Corp.'),
(1575, '00:06:25', 'The Linksys Group, Inc.'),
(1576, '00:06:26', 'MWE GmbH'),
(1577, '00:06:27', 'Uniwide Technologies, Inc.'),
(1578, '00:06:28', 'CISCO SYSTEMS, INC.'),
(1579, '00:06:29', 'IBM Corp'),
(1580, '00:06:2A', 'CISCO SYSTEMS, INC.'),
(1581, '00:06:2B', 'INTRASERVER TECHNOLOGY'),
(1582, '00:06:2C', 'Bivio Networks'),
(1583, '00:06:2D', 'TouchStar Technologies, L.L.C.'),
(1584, '00:06:2E', 'Aristos Logic Corp.'),
(1585, '00:06:2F', 'Pivotech Systems Inc.'),
(1586, '00:06:30', 'Adtranz Sweden'),
(1587, '00:06:31', 'Calix'),
(1588, '00:06:32', 'Mesco Engineering GmbH'),
(1589, '00:06:33', 'Cross Match Technologies GmbH'),
(1590, '00:06:34', 'GTE Airfone Inc.'),
(1591, '00:06:35', 'PacketAir Networks, Inc.'),
(1592, '00:06:36', 'Jedai Broadband Networks'),
(1593, '00:06:37', 'Toptrend-Meta Information (ShenZhen) Inc.'),
(1594, '00:06:38', 'Sungjin C&amp;C Co., Ltd.'),
(1595, '00:06:39', 'Newtec'),
(1596, '00:06:3A', 'Dura Micro, Inc.'),
(1597, '00:06:3B', 'Arcturus Networks Inc.'),
(1598, '00:06:3C', 'Intrinsyc Software International Inc.'),
(1599, '00:06:3D', 'Microwave Data Systems Inc.'),
(1600, '00:06:3E', 'Opthos Inc.'),
(1601, '00:06:3F', 'Everex Communications Inc.'),
(1602, '00:06:40', 'White Rock Networks'),
(1603, '00:06:41', 'ITCN'),
(1604, '00:06:42', 'Genetel Systems Inc.'),
(1605, '00:06:43', 'SONO Computer Co., Ltd.'),
(1606, '00:06:44', 'neix,Inc'),
(1607, '00:06:45', 'Meisei Electric Co. Ltd.'),
(1608, '00:06:46', 'ShenZhen XunBao Network Technology Co Ltd'),
(1609, '00:06:47', 'Etrali S.A.'),
(1610, '00:06:48', 'Seedsware, Inc.'),
(1611, '00:06:49', '3M Deutschland GmbH'),
(1612, '00:06:4A', 'Honeywell Co., Ltd. (KOREA)'),
(1613, '00:06:4B', 'Alexon Co., Ltd.'),
(1614, '00:06:4C', 'Invicta Networks, Inc.'),
(1615, '00:06:4D', 'Sencore'),
(1616, '00:06:4E', 'Broad Net Technology Inc.'),
(1617, '00:06:4F', 'PRO-NETS Technology Corporation'),
(1618, '00:06:50', 'Tiburon Networks, Inc.'),
(1619, '00:06:51', 'Aspen Networks Inc.'),
(1620, '00:06:52', 'CISCO SYSTEMS, INC.'),
(1621, '00:06:53', 'CISCO SYSTEMS, INC.'),
(1622, '00:06:54', 'Winpresa Building Automation Technologies GmbH'),
(1623, '00:06:55', 'Yipee, Inc.'),
(1624, '00:06:56', 'Tactel AB'),
(1625, '00:06:57', 'Market Central, Inc.'),
(1626, '00:06:58', 'Helmut Fischer GmbH Institut f&uuml;r Elektronik und Messtechnik'),
(1627, '00:06:59', 'EAL (Apeldoorn) B.V.'),
(1628, '00:06:5A', 'Strix Systems'),
(1629, '00:06:5B', 'Dell Computer Corp.'),
(1630, '00:06:5C', 'Malachite Technologies, Inc.'),
(1631, '00:06:5D', 'Heidelberg Web Systems'),
(1632, '00:06:5E', 'Photuris, Inc.'),
(1633, '00:06:5F', 'ECI Telecom - NGTS Ltd.'),
(1634, '00:06:60', 'NADEX Co., Ltd.'),
(1635, '00:06:61', 'NIA Home Technologies Corp.'),
(1636, '00:06:62', 'MBM Technology Ltd.'),
(1637, '00:06:63', 'Human Technology Co., Ltd.'),
(1638, '00:06:64', 'Fostex Corporation'),
(1639, '00:06:65', 'Sunny Giken, Inc.'),
(1640, '00:06:66', 'Roving Networks'),
(1641, '00:06:67', 'Tripp Lite'),
(1642, '00:06:68', 'Vicon Industries Inc.'),
(1643, '00:06:69', 'Datasound Laboratories Ltd'),
(1644, '00:06:6A', 'InfiniCon Systems, Inc.'),
(1645, '00:06:6B', 'Sysmex Corporation'),
(1646, '00:06:6C', 'Robinson Corporation'),
(1647, '00:06:6D', 'Compuprint S.P.A.'),
(1648, '00:06:6E', 'Delta Electronics, Inc.'),
(1649, '00:06:6F', 'Korea Data Systems'),
(1650, '00:06:70', 'Upponetti Oy'),
(1651, '00:06:71', 'Softing AG'),
(1652, '00:06:72', 'Netezza'),
(1653, '00:06:73', 'TKH Security Solutions USA'),
(1654, '00:06:74', 'Spectrum Control, Inc.'),
(1655, '00:06:75', 'Banderacom, Inc.'),
(1656, '00:06:76', 'Novra Technologies Inc.'),
(1657, '00:06:77', 'SICK AG'),
(1658, '00:06:78', 'Marantz Brand Company'),
(1659, '00:06:79', 'Konami Corporation'),
(1660, '00:06:7A', 'JMP Systems'),
(1661, '00:06:7B', 'Toplink C&amp;C Corporation'),
(1662, '00:06:7C', 'CISCO SYSTEMS, INC.'),
(1663, '00:06:7D', 'Takasago Ltd.'),
(1664, '00:06:7E', 'WinCom Systems, Inc.'),
(1665, '00:06:7F', 'Digeo, Inc.'),
(1666, '00:06:80', 'Card Access, Inc.'),
(1667, '00:06:81', 'Goepel Electronic GmbH'),
(1668, '00:06:82', 'Convedia'),
(1669, '00:06:83', 'Bravara Communications, Inc.'),
(1670, '00:06:84', 'Biacore AB'),
(1671, '00:06:85', 'NetNearU Corporation'),
(1672, '00:06:86', 'ZARDCOM Co., Ltd.'),
(1673, '00:06:87', 'Omnitron Systems Technology, Inc.'),
(1674, '00:06:88', 'Telways Communication Co., Ltd.'),
(1675, '00:06:89', 'yLez Technologies Pte Ltd'),
(1676, '00:06:8A', 'NeuronNet Co. Ltd. R&amp;D Center'),
(1677, '00:06:8B', 'AirRunner Technologies, Inc.'),
(1678, '00:06:8C', '3Com Corporation'),
(1679, '00:06:8D', 'SEPATON, Inc.'),
(1680, '00:06:8E', 'HID Corporation'),
(1681, '00:06:8F', 'Telemonitor, Inc.'),
(1682, '00:06:90', 'Euracom Communication GmbH'),
(1683, '00:06:91', 'PT Inovacao'),
(1684, '00:06:92', 'Intruvert Networks, Inc.'),
(1685, '00:06:93', 'Flexus Computer Technology, Inc.'),
(1686, '00:06:94', 'Mobillian Corporation'),
(1687, '00:06:95', 'Ensure Technologies, Inc.'),
(1688, '00:06:96', 'Advent Networks'),
(1689, '00:06:97', 'R &amp; D Center'),
(1690, '00:06:98', 'egnite GmbH'),
(1691, '00:06:99', 'Vida Design Co.'),
(1692, '00:06:9A', 'e &amp; Tel'),
(1693, '00:06:9B', 'AVT Audio Video Technologies GmbH'),
(1694, '00:06:9C', 'Transmode Systems AB'),
(1695, '00:06:9D', 'Petards Ltd'),
(1696, '00:06:9E', 'UNIQA, Inc.'),
(1697, '00:06:9F', 'Kuokoa Networks'),
(1698, '00:06:A0', 'Mx Imaging'),
(1699, '00:06:A1', 'Celsian Technologies, Inc.'),
(1700, '00:06:A2', 'Microtune, Inc.'),
(1701, '00:06:A3', 'Bitran Corporation'),
(1702, '00:06:A4', 'INNOWELL Corp.'),
(1703, '00:06:A5', 'PINON Corp.'),
(1704, '00:06:A6', 'Artistic Licence Engineering Ltd'),
(1705, '00:06:A7', 'Primarion'),
(1706, '00:06:A8', 'KC Technology, Inc.'),
(1707, '00:06:A9', 'Universal Instruments Corp.'),
(1708, '00:06:AA', 'VT Miltope'),
(1709, '00:06:AB', 'W-Link Systems, Inc.'),
(1710, '00:06:AC', 'Intersoft Co.'),
(1711, '00:06:AD', 'KB Electronics Ltd.'),
(1712, '00:06:AE', 'Himachal Futuristic Communications Ltd'),
(1713, '00:06:AF', 'Xalted Networks'),
(1714, '00:06:B0', 'Comtech EF Data Corp.'),
(1715, '00:06:B1', 'Sonicwall'),
(1716, '00:06:B2', 'Linxtek Co.'),
(1717, '00:06:B3', 'Diagraph Corporation'),
(1718, '00:06:B4', 'Vorne Industries, Inc.'),
(1719, '00:06:B5', 'Source Photonics, Inc.'),
(1720, '00:06:B6', 'Nir-Or Israel Ltd.'),
(1721, '00:06:B7', 'TELEM GmbH'),
(1722, '00:06:B8', 'Bandspeed Pty Ltd'),
(1723, '00:06:B9', 'A5TEK Corp.'),
(1724, '00:06:BA', 'Westwave Communications'),
(1725, '00:06:BB', 'ATI Technologies Inc.'),
(1726, '00:06:BC', 'Macrolink, Inc.'),
(1727, '00:06:BD', 'BNTECHNOLOGY Co., Ltd.'),
(1728, '00:06:BE', 'Baumer Optronic GmbH'),
(1729, '00:06:BF', 'Accella Technologies Co., Ltd.'),
(1730, '00:06:C0', 'United Internetworks, Inc.'),
(1731, '00:06:C1', 'CISCO SYSTEMS, INC.'),
(1732, '00:06:C2', 'Smartmatic Corporation'),
(1733, '00:06:C3', 'Schindler Elevator Ltd.'),
(1734, '00:06:C4', 'Piolink Inc.'),
(1735, '00:06:C5', 'INNOVI Technologies Limited'),
(1736, '00:06:C6', 'lesswire AG'),
(1737, '00:06:C7', 'RFNET Technologies Pte Ltd (S)'),
(1738, '00:06:C8', 'Sumitomo Metal Micro Devices, Inc.'),
(1739, '00:06:C9', 'Technical Marketing Research, Inc.'),
(1740, '00:06:CA', 'American Computer &amp; Digital Components, Inc. (ACDC)'),
(1741, '00:06:CB', 'Jotron Electronics A/S'),
(1742, '00:06:CC', 'JMI Electronics Co., Ltd.'),
(1743, '00:06:CD', 'Leaf Imaging Ltd.'),
(1744, '00:06:CE', 'DATENO'),
(1745, '00:06:CF', 'Thales Avionics In-Flight Systems, LLC'),
(1746, '00:06:D0', 'Elgar Electronics Corp.'),
(1747, '00:06:D1', 'Tahoe Networks, Inc.'),
(1748, '00:06:D2', 'Tundra Semiconductor Corp.'),
(1749, '00:06:D3', 'Alpha Telecom, Inc. U.S.A.'),
(1750, '00:06:D4', 'Interactive Objects, Inc.'),
(1751, '00:06:D5', 'Diamond Systems Corp.'),
(1752, '00:06:D6', 'CISCO SYSTEMS, INC.'),
(1753, '00:06:D7', 'CISCO SYSTEMS, INC.'),
(1754, '00:06:D8', 'Maple Optical Systems'),
(1755, '00:06:D9', 'IPM-Net S.p.A.'),
(1756, '00:06:DA', 'ITRAN Communications Ltd.'),
(1757, '00:06:DB', 'ICHIPS Co., Ltd.'),
(1758, '00:06:DC', 'Syabas Technology (Amquest)'),
(1759, '00:06:DD', 'AT &amp; T Laboratories - Cambridge Ltd'),
(1760, '00:06:DE', 'Flash Technology'),
(1761, '00:06:DF', 'AIDONIC Corporation'),
(1762, '00:06:E0', 'MAT Co., Ltd.'),
(1763, '00:06:E1', 'Techno Trade s.a'),
(1764, '00:06:E2', 'Ceemax Technology Co., Ltd.'),
(1765, '00:06:E3', 'Quantitative Imaging Corporation'),
(1766, '00:06:E4', 'Citel Technologies Ltd.'),
(1767, '00:06:E5', 'Fujian Newland Computer Ltd. Co.'),
(1768, '00:06:E6', 'DongYang Telecom Co., Ltd.'),
(1769, '00:06:E7', 'Bit Blitz Communications Inc.'),
(1770, '00:06:E8', 'Optical Network Testing, Inc.'),
(1771, '00:06:E9', 'Intime Corp.'),
(1772, '00:06:EA', 'ELZET80 Mikrocomputer GmbH&amp;Co. KG'),
(1773, '00:06:EB', 'Global Data'),
(1774, '00:06:EC', 'Harris Corporation'),
(1775, '00:06:ED', 'Inara Networks'),
(1776, '00:06:EE', 'Shenyang Neu-era Information &amp; Technology Stock Co., Ltd'),
(1777, '00:06:EF', 'Maxxan Systems, Inc.'),
(1778, '00:06:F0', 'Digeo, Inc.'),
(1779, '00:06:F1', 'Optillion'),
(1780, '00:06:F2', 'Platys Communications'),
(1781, '00:06:F3', 'AcceLight Networks'),
(1782, '00:06:F4', 'Prime Electronics &amp; Satellitics Inc.'),
(1783, '00:06:F5', 'ALPS Co,. Ltd.'),
(1784, '00:06:F6', 'CISCO SYSTEMS, INC.'),
(1785, '00:06:F7', 'ALPS Co,. Ltd.'),
(1786, '00:06:F8', 'The Boeing Company'),
(1787, '00:06:F9', 'Mitsui Zosen Systems Research Inc.'),
(1788, '00:06:FA', 'IP SQUARE Co, Ltd.'),
(1789, '00:06:FB', 'Hitachi Printing Solutions, Ltd.'),
(1790, '00:06:FC', 'Fnet Co., Ltd.'),
(1791, '00:06:FD', 'Comjet Information Systems Corp.'),
(1792, '00:06:FE', 'Ambrado, Inc'),
(1793, '00:06:FF', 'Sheba Systems Co., Ltd.'),
(1794, '00:07:00', 'Zettamedia Korea'),
(1795, '00:07:01', 'RACAL-DATACOM'),
(1796, '00:07:02', 'Varian Medical Systems'),
(1797, '00:07:03', 'CSEE Transport'),
(1798, '00:07:04', 'ALPS Co,. Ltd.'),
(1799, '00:07:05', 'Endress &amp; Hauser GmbH &amp; Co'),
(1800, '00:07:06', 'Sanritz Corporation'),
(1801, '00:07:07', 'Interalia Inc.'),
(1802, '00:07:08', 'Bitrage Inc.'),
(1803, '00:07:09', 'Westerstrand Urfabrik AB'),
(1804, '00:07:0A', 'Unicom Automation Co., Ltd.'),
(1805, '00:07:0B', 'Novabase SGPS, SA'),
(1806, '00:07:0C', 'SVA-Intrusion.com Co. Ltd.'),
(1807, '00:07:0D', 'CISCO SYSTEMS, INC.'),
(1808, '00:07:0E', 'CISCO SYSTEMS, INC.'),
(1809, '00:07:0F', 'Fujant, Inc.'),
(1810, '00:07:10', 'Adax, Inc.'),
(1811, '00:07:11', 'Acterna'),
(1812, '00:07:12', 'JAL Information Technology'),
(1813, '00:07:13', 'IP One, Inc.'),
(1814, '00:07:14', 'Brightcom'),
(1815, '00:07:15', 'General Research of Electronics, Inc.'),
(1816, '00:07:16', 'J &amp; S Marine Ltd.'),
(1817, '00:07:17', 'Wieland Electric GmbH'),
(1818, '00:07:18', 'iCanTek Co., Ltd.'),
(1819, '00:07:19', 'Mobiis Co., Ltd.'),
(1820, '00:07:1A', 'Finedigital Inc.'),
(1821, '00:07:1B', 'CDVI Americas Ltd'),
(1822, '00:07:1C', 'AT&amp;T Fixed Wireless Services'),
(1823, '00:07:1D', 'Satelsa Sistemas Y Aplicaciones De Telecomunicaciones, S.A.'),
(1824, '00:07:1E', 'Tri-M Engineering / Nupak Dev. Corp.'),
(1825, '00:07:1F', 'European Systems Integration'),
(1826, '00:07:20', 'Trutzschler GmbH &amp; Co. KG'),
(1827, '00:07:21', 'Formac Elektronik GmbH'),
(1828, '00:07:22', 'The Nielsen Company'),
(1829, '00:07:23', 'ELCON Systemtechnik GmbH'),
(1830, '00:07:24', 'Telemax Co., Ltd.'),
(1831, '00:07:25', 'Bematech International Corp.'),
(1832, '00:07:26', 'Shenzhen Gongjin Electronics Co., Ltd.'),
(1833, '00:07:27', 'Zi Corporation (HK) Ltd.'),
(1834, '00:07:28', 'Neo Telecom'),
(1835, '00:07:29', 'Kistler Instrumente AG'),
(1836, '00:07:2A', 'Innovance Networks'),
(1837, '00:07:2B', 'Jung Myung Telecom Co., Ltd.'),
(1838, '00:07:2C', 'Fabricom'),
(1839, '00:07:2D', 'CNSystems'),
(1840, '00:07:2E', 'North Node AB'),
(1841, '00:07:2F', 'Intransa, Inc.'),
(1842, '00:07:30', 'Hutchison OPTEL Telecom Technology Co., Ltd.'),
(1843, '00:07:31', 'Ophir-Spiricon LLC'),
(1844, '00:07:32', 'AAEON Technology Inc.'),
(1845, '00:07:33', 'DANCONTROL Engineering'),
(1846, '00:07:34', 'ONStor, Inc.'),
(1847, '00:07:35', 'Flarion Technologies, Inc.'),
(1848, '00:07:36', 'Data Video Technologies Co., Ltd.'),
(1849, '00:07:37', 'Soriya Co. Ltd.'),
(1850, '00:07:38', 'Young Technology Co., Ltd.'),
(1851, '00:07:39', 'Scotty Group Austria Gmbh'),
(1852, '00:07:3A', 'Inventel Systemes'),
(1853, '00:07:3B', 'Tenovis GmbH &amp; Co KG'),
(1854, '00:07:3C', 'Telecom Design'),
(1855, '00:07:3D', 'Nanjing Postel Telecommunications Co., Ltd.'),
(1856, '00:07:3E', 'China Great-Wall Computer Shenzhen Co., Ltd.'),
(1857, '00:07:3F', 'Woojyun Systec Co., Ltd.'),
(1858, '00:07:40', 'Buffalo Inc.'),
(1859, '00:07:41', 'Sierra Automated Systems'),
(1860, '00:07:42', 'Ormazabal'),
(1861, '00:07:43', 'Chelsio Communications'),
(1862, '00:07:44', 'Unico, Inc.'),
(1863, '00:07:45', 'Radlan Computer Communications Ltd.'),
(1864, '00:07:46', 'TURCK, Inc.'),
(1865, '00:07:47', 'Mecalc'),
(1866, '00:07:48', 'The Imaging Source Europe'),
(1867, '00:07:49', 'CENiX Inc.'),
(1868, '00:07:4A', 'Carl Valentin GmbH'),
(1869, '00:07:4B', 'Daihen Corporation'),
(1870, '00:07:4C', 'Beicom Inc.'),
(1871, '00:07:4D', 'Zebra Technologies Corp.'),
(1872, '00:07:4E', 'IPFRONT Inc'),
(1873, '00:07:4F', 'CISCO SYSTEMS, INC.'),
(1874, '00:07:50', 'CISCO SYSTEMS, INC.'),
(1875, '00:07:51', 'm-u-t AG'),
(1876, '00:07:52', 'Rhythm Watch Co., Ltd.'),
(1877, '00:07:53', 'Beijing Qxcomm Technology Co., Ltd.'),
(1878, '00:07:54', 'Xyterra Computing, Inc.'),
(1879, '00:07:55', 'Lafon'),
(1880, '00:07:56', 'Juyoung Telecom'),
(1881, '00:07:57', 'Topcall International AG'),
(1882, '00:07:58', 'Dragonwave'),
(1883, '00:07:59', 'Boris Manufacturing Corp.'),
(1884, '00:07:5A', 'Air Products and Chemicals, Inc.'),
(1885, '00:07:5B', 'Gibson Guitars'),
(1886, '00:07:5C', 'Eastman Kodak Company'),
(1887, '00:07:5D', 'Celleritas Inc.'),
(1888, '00:07:5E', 'Ametek Power Instruments'),
(1889, '00:07:5F', 'VCS Video Communication Systems AG'),
(1890, '00:07:60', 'TOMIS Information &amp; Telecom Corp.'),
(1891, '00:07:61', 'Logitech Europe SA'),
(1892, '00:07:62', 'Group Sense Limited'),
(1893, '00:07:63', 'Sunniwell Cyber Tech. Co., Ltd.'),
(1894, '00:07:64', 'YoungWoo Telecom Co. Ltd.'),
(1895, '00:07:65', 'Jade Quantum Technologies, Inc.'),
(1896, '00:07:66', 'Chou Chin Industrial Co., Ltd.'),
(1897, '00:07:67', 'Yuxing Electronics Company Limited'),
(1898, '00:07:68', 'Danfoss A/S'),
(1899, '00:07:69', 'Italiana Macchi SpA'),
(1900, '00:07:6A', 'NEXTEYE Co., Ltd.'),
(1901, '00:07:6B', 'Stralfors AB'),
(1902, '00:07:6C', 'Daehanet, Inc.'),
(1903, '00:07:6D', 'Flexlight Networks'),
(1904, '00:07:6E', 'Sinetica Corporation Limited'),
(1905, '00:07:6F', 'Synoptics Limited'),
(1906, '00:07:70', 'Ubiquoss Inc'),
(1907, '00:07:71', 'Embedded System Corporation'),
(1908, '00:07:72', 'Alcatel Shanghai Bell Co., Ltd.'),
(1909, '00:07:73', 'Ascom Powerline Communications Ltd.'),
(1910, '00:07:74', 'GuangZhou Thinker Technology Co. Ltd.'),
(1911, '00:07:75', 'Valence Semiconductor, Inc.'),
(1912, '00:07:76', 'Federal APD'),
(1913, '00:07:77', 'Motah Ltd.'),
(1914, '00:07:78', 'GERSTEL GmbH &amp; Co. KG'),
(1915, '00:07:79', 'Sungil Telecom Co., Ltd.'),
(1916, '00:07:7A', 'Infoware System Co., Ltd.'),
(1917, '00:07:7B', 'Millimetrix Broadband Networks'),
(1918, '00:07:7C', 'Westermo Teleindustri AB'),
(1919, '00:07:7D', 'CISCO SYSTEMS, INC.'),
(1920, '00:07:7E', 'Elrest GmbH'),
(1921, '00:07:7F', 'J Communications Co., Ltd.'),
(1922, '00:07:80', 'Bluegiga Technologies OY'),
(1923, '00:07:81', 'Itron Inc.'),
(1924, '00:07:82', 'Oracle Corporation'),
(1925, '00:07:83', 'SynCom Network, Inc.'),
(1926, '00:07:84', 'CISCO SYSTEMS, INC.'),
(1927, '00:07:85', 'CISCO SYSTEMS, INC.'),
(1928, '00:07:86', 'Wireless Networks Inc.'),
(1929, '00:07:87', 'Idea System Co., Ltd.'),
(1930, '00:07:88', 'Clipcomm, Inc.'),
(1931, '00:07:89', 'DONGWON SYSTEMS'),
(1932, '00:07:8A', 'Mentor Data System Inc.'),
(1933, '00:07:8B', 'Wegener Communications, Inc.'),
(1934, '00:07:8C', 'Elektronikspecialisten i Borlange AB'),
(1935, '00:07:8D', 'NetEngines Ltd.'),
(1936, '00:07:8E', 'Garz &amp; Friche GmbH'),
(1937, '00:07:8F', 'Emkay Innovative Products'),
(1938, '00:07:90', 'Tri-M Technologies (s) Limited'),
(1939, '00:07:91', 'International Data Communications, Inc.'),
(1940, '00:07:92', 'S&uuml;tron Electronic GmbH'),
(1941, '00:07:93', 'Shin Satellite Public Company Limited'),
(1942, '00:07:94', 'Simple Devices, Inc.'),
(1943, '00:07:95', 'Elitegroup Computer System Co. (ECS)'),
(1944, '00:07:96', 'LSI Systems, Inc.'),
(1945, '00:07:97', 'Netpower Co., Ltd.'),
(1946, '00:07:98', 'Selea SRL'),
(1947, '00:07:99', 'Tipping Point Technologies, Inc.'),
(1948, '00:07:9A', 'Verint Systems Inc'),
(1949, '00:07:9B', 'Aurora Networks'),
(1950, '00:07:9C', 'Golden Electronics Technology Co., Ltd.'),
(1951, '00:07:9D', 'Musashi Co., Ltd.'),
(1952, '00:07:9E', 'Ilinx Co., Ltd.'),
(1953, '00:07:9F', 'Action Digital Inc.'),
(1954, '00:07:A0', 'e-Watch Inc.'),
(1955, '00:07:A1', 'VIASYS Healthcare GmbH'),
(1956, '00:07:A2', 'Opteon Corporation'),
(1957, '00:07:A3', 'Ositis Software, Inc.'),
(1958, '00:07:A4', 'GN Netcom Ltd.'),
(1959, '00:07:A5', 'Y.D.K Co. Ltd.'),
(1960, '00:07:A6', 'Home Automation, Inc.'),
(1961, '00:07:A7', 'A-Z Inc.'),
(1962, '00:07:A8', 'Haier Group Technologies Ltd.'),
(1963, '00:07:A9', 'Novasonics'),
(1964, '00:07:AA', 'Quantum Data Inc.'),
(1965, '00:07:AB', 'Samsung Electronics Co.,Ltd'),
(1966, '00:07:AC', 'Eolring'),
(1967, '00:07:AD', 'Pentacon GmbH Foto-und Feinwerktechnik'),
(1968, '00:07:AE', 'Britestream Networks, Inc.'),
(1969, '00:07:AF', 'Red Lion Controls, LP'),
(1970, '00:07:B0', 'Office Details, Inc.'),
(1971, '00:07:B1', 'Equator Technologies'),
(1972, '00:07:B2', 'Transaccess S.A.'),
(1973, '00:07:B3', 'CISCO SYSTEMS, INC.'),
(1974, '00:07:B4', 'CISCO SYSTEMS, INC.'),
(1975, '00:07:B5', 'Any One Wireless Ltd.'),
(1976, '00:07:B6', 'Telecom Technology Ltd.'),
(1977, '00:07:B7', 'Samurai Ind. Prods Eletronicos Ltda'),
(1978, '00:07:B8', 'Corvalent Corporation'),
(1979, '00:07:B9', 'Ginganet Corporation'),
(1980, '00:07:BA', 'UTStarcom, Inc.'),
(1981, '00:07:BB', 'Candera Inc.'),
(1982, '00:07:BC', 'Identix Inc.'),
(1983, '00:07:BD', 'Radionet Ltd.'),
(1984, '00:07:BE', 'DataLogic SpA'),
(1985, '00:07:BF', 'Armillaire Technologies, Inc.'),
(1986, '00:07:C0', 'NetZerver Inc.'),
(1987, '00:07:C1', 'Overture Networks, Inc.'),
(1988, '00:07:C2', 'Netsys Telecom'),
(1989, '00:07:C3', 'Thomson'),
(1990, '00:07:C4', 'JEAN Co. Ltd.'),
(1991, '00:07:C5', 'Gcom, Inc.'),
(1992, '00:07:C6', 'VDS Vosskuhler GmbH'),
(1993, '00:07:C7', 'Synectics Systems Limited'),
(1994, '00:07:C8', 'Brain21, Inc.'),
(1995, '00:07:C9', 'Technol Seven Co., Ltd.'),
(1996, '00:07:CA', 'Creatix Polymedia Ges Fur Kommunikaitonssysteme'),
(1997, '00:07:CB', 'Freebox SA'),
(1998, '00:07:CC', 'Kaba Benzing GmbH'),
(1999, '00:07:CD', 'Kumoh Electronic Co, Ltd'),
(2000, '00:07:CE', 'Cabletime Limited'),
(2001, '00:07:CF', 'Anoto AB'),
(2002, '00:07:D0', 'Automat Engenharia de Automa&ccedil;&atilde;o Ltda.'),
(2003, '00:07:D1', 'Spectrum Signal Processing Inc.'),
(2004, '00:07:D2', 'Logopak Systeme GmbH &amp; Co. KG'),
(2005, '00:07:D3', 'SPGPrints B.V.'),
(2006, '00:07:D4', 'Zhejiang Yutong Network Communication Co Ltd.'),
(2007, '00:07:D5', '3e Technologies Int;., Inc.'),
(2008, '00:07:D6', 'Commil Ltd.'),
(2009, '00:07:D7', 'Caporis Networks AG'),
(2010, '00:07:D8', 'Hitron Systems Inc.'),
(2011, '00:07:D9', 'Splicecom'),
(2012, '00:07:DA', 'Neuro Telecom Co., Ltd.'),
(2013, '00:07:DB', 'Kirana Networks, Inc.'),
(2014, '00:07:DC', 'Atek Co, Ltd.'),
(2015, '00:07:DD', 'Cradle Technologies'),
(2016, '00:07:DE', 'eCopilt AB'),
(2017, '00:07:DF', 'Vbrick Systems Inc.'),
(2018, '00:07:E0', 'Palm Inc.'),
(2019, '00:07:E1', 'WIS Communications Co. Ltd.'),
(2020, '00:07:E2', 'Bitworks, Inc.'),
(2021, '00:07:E3', 'Navcom Technology, Inc.'),
(2022, '00:07:E4', 'SoftRadio Co., Ltd.'),
(2023, '00:07:E5', 'Coup Corporation'),
(2024, '00:07:E6', 'edgeflow Canada Inc.'),
(2025, '00:07:E7', 'FreeWave Technologies'),
(2026, '00:07:E8', 'EdgeWave'),
(2027, '00:07:E9', 'Intel Corporation'),
(2028, '00:07:EA', 'Massana, Inc.'),
(2029, '00:07:EB', 'CISCO SYSTEMS, INC.'),
(2030, '00:07:EC', 'CISCO SYSTEMS, INC.'),
(2031, '00:07:ED', 'Altera Corporation'),
(2032, '00:07:EE', 'telco Informationssysteme GmbH'),
(2033, '00:07:EF', 'Lockheed Martin Tactical Systems'),
(2034, '00:07:F0', 'LogiSync LLC'),
(2035, '00:07:F1', 'TeraBurst Networks Inc.'),
(2036, '00:07:F2', 'IOA Corporation'),
(2037, '00:07:F3', 'Thinkengine Networks'),
(2038, '00:07:F4', 'Eletex Co., Ltd.'),
(2039, '00:07:F5', 'Bridgeco Co AG'),
(2040, '00:07:F6', 'Qqest Software Systems'),
(2041, '00:07:F7', 'Galtronics'),
(2042, '00:07:F8', 'ITDevices, Inc.'),
(2043, '00:07:F9', 'Sensaphone'),
(2044, '00:07:FA', 'ITT Co., Ltd.'),
(2045, '00:07:FB', 'Giga Stream UMTS Technologies GmbH'),
(2046, '00:07:FC', 'Adept Systems Inc.'),
(2047, '00:07:FD', 'LANergy Ltd.'),
(2048, '00:07:FE', 'Rigaku Corporation'),
(2049, '00:07:FF', 'Gluon Networks'),
(2050, '00:08:00', 'MULTITECH SYSTEMS, INC.'),
(2051, '00:08:01', 'HighSpeed Surfing Inc.'),
(2052, '00:08:02', 'Hewlett-Packard Company'),
(2053, '00:08:03', 'Cos Tron'),
(2054, '00:08:04', 'ICA Inc.'),
(2055, '00:08:05', 'Techno-Holon Corporation'),
(2056, '00:08:06', 'Raonet Systems, Inc.'),
(2057, '00:08:07', 'Access Devices Limited'),
(2058, '00:08:08', 'PPT Vision, Inc.'),
(2059, '00:08:09', 'Systemonic AG'),
(2060, '00:08:0A', 'Espera-Werke GmbH'),
(2061, '00:08:0B', 'Birka BPA Informationssystem AB'),
(2062, '00:08:0C', 'VDA Elettronica spa'),
(2063, '00:08:0D', 'Toshiba'),
(2064, '00:08:0E', 'ARRIS Group, Inc.'),
(2065, '00:08:0F', 'Proximion Fiber Optics AB'),
(2066, '00:08:10', 'Key Technology, Inc.'),
(2067, '00:08:11', 'VOIX Corporation'),
(2068, '00:08:12', 'GM-2 Corporation'),
(2069, '00:08:13', 'Diskbank, Inc.'),
(2070, '00:08:14', 'TIL Technologies'),
(2071, '00:08:15', 'CATS Co., Ltd.'),
(2072, '00:08:16', 'Bluelon ApS'),
(2073, '00:08:17', 'EmergeCore Networks LLC'),
(2074, '00:08:18', 'Pixelworks, Inc.'),
(2075, '00:08:19', 'Banksys'),
(2076, '00:08:1A', 'Sanrad Intelligence Storage Communications (2000) Ltd.'),
(2077, '00:08:1B', 'Windigo Systems'),
(2078, '00:08:1C', '@pos.com'),
(2079, '00:08:1D', 'Ipsil, Incorporated'),
(2080, '00:08:1E', 'Repeatit AB'),
(2081, '00:08:1F', 'Pou Yuen Tech Corp. Ltd.'),
(2082, '00:08:20', 'CISCO SYSTEMS, INC.'),
(2083, '00:08:21', 'CISCO SYSTEMS, INC.'),
(2084, '00:08:22', 'InPro Comm'),
(2085, '00:08:23', 'Texa Corp.'),
(2086, '00:08:24', 'Nuance Document Imaging'),
(2087, '00:08:25', 'Acme Packet'),
(2088, '00:08:26', 'Colorado Med Tech'),
(2089, '00:08:27', 'ADB Broadband Italia'),
(2090, '00:08:28', 'Koei Engineering Ltd.'),
(2091, '00:08:29', 'Aval Nagasaki Corporation'),
(2092, '00:08:2A', 'Powerwallz Network Security'),
(2093, '00:08:2B', 'Wooksung Electronics, Inc.'),
(2094, '00:08:2C', 'Homag AG'),
(2095, '00:08:2D', 'Indus Teqsite Private Limited'),
(2096, '00:08:2E', 'Multitone Electronics PLC'),
(2097, '00:08:2F', 'CISCO SYSTEMS, INC.'),
(2098, '00:08:30', 'CISCO SYSTEMS, INC.'),
(2099, '00:08:31', 'CISCO SYSTEMS, INC.'),
(2100, '00:08:32', 'Cisco'),
(2101, '00:08:4E', 'DivergeNet, Inc.'),
(2102, '00:08:4F', 'Qualstar Corporation'),
(2103, '00:08:50', 'Arizona Instrument Corp.'),
(2104, '00:08:51', 'Canadian Bank Note Company, Ltd.'),
(2105, '00:08:52', 'Davolink Co. Inc.'),
(2106, '00:08:53', 'Schleicher GmbH &amp; Co. Relaiswerke KG'),
(2107, '00:08:54', 'Netronix, Inc.'),
(2108, '00:08:55', 'NASA-Goddard Space Flight Center'),
(2109, '00:08:56', 'Gamatronic Electronic Industries Ltd.'),
(2110, '00:08:57', 'Polaris Networks, Inc.'),
(2111, '00:08:58', 'Novatechnology Inc.'),
(2112, '00:08:59', 'ShenZhen Unitone Electronics Co., Ltd.'),
(2113, '00:08:5A', 'IntiGate Inc.'),
(2114, '00:08:5B', 'Hanbit Electronics Co., Ltd.'),
(2115, '00:08:5C', 'Shanghai Dare Technologies Co. Ltd.'),
(2116, '00:08:5D', 'Aastra'),
(2117, '00:08:5E', 'PCO AG'),
(2118, '00:08:5F', 'Picanol N.V.'),
(2119, '00:08:60', 'LodgeNet Entertainment Corp.'),
(2120, '00:08:61', 'SoftEnergy Co., Ltd.'),
(2121, '00:08:62', 'NEC Eluminant Technologies, Inc.'),
(2122, '00:08:63', 'Entrisphere Inc.'),
(2123, '00:08:64', 'Fasy S.p.A.'),
(2124, '00:08:65', 'JASCOM CO., LTD'),
(2125, '00:08:66', 'DSX Access Systems, Inc.'),
(2126, '00:08:67', 'Uptime Devices'),
(2127, '00:08:68', 'PurOptix'),
(2128, '00:08:69', 'Command-e Technology Co.,Ltd.'),
(2129, '00:08:6A', 'Securiton Gmbh'),
(2130, '00:08:6B', 'MIPSYS'),
(2131, '00:08:6C', 'Plasmon LMS'),
(2132, '00:08:6D', 'Missouri FreeNet'),
(2133, '00:08:6E', 'Hyglo AB'),
(2134, '00:08:6F', 'Resources Computer Network Ltd.'),
(2135, '00:08:70', 'Rasvia Systems, Inc.'),
(2136, '00:08:71', 'NORTHDATA Co., Ltd.'),
(2137, '00:08:72', 'Sorenson Communications'),
(2138, '00:08:73', 'DapTechnology B.V.'),
(2139, '00:08:74', 'Dell Computer Corp.'),
(2140, '00:08:75', 'Acorp Electronics Corp.'),
(2141, '00:08:76', 'SDSystem'),
(2142, '00:08:77', 'Liebert-Hiross Spa'),
(2143, '00:08:78', 'Benchmark Storage Innovations'),
(2144, '00:08:79', 'CEM Corporation'),
(2145, '00:08:7A', 'Wipotec GmbH'),
(2146, '00:08:7B', 'RTX Telecom A/S'),
(2147, '00:08:7C', 'CISCO SYSTEMS, INC.'),
(2148, '00:08:7D', 'CISCO SYSTEMS, INC.'),
(2149, '00:08:7E', 'Bon Electro-Telecom Inc.'),
(2150, '00:08:7F', 'SPAUN electronic GmbH &amp; Co. KG'),
(2151, '00:08:80', 'BroadTel Canada Communications inc.'),
(2152, '00:08:81', 'DIGITAL HANDS CO.,LTD.'),
(2153, '00:08:82', 'SIGMA CORPORATION'),
(2154, '00:08:83', 'Hewlett-Packard Company'),
(2155, '00:08:84', 'Index Braille AB'),
(2156, '00:08:85', 'EMS Dr. Thomas W&uuml;nsche'),
(2157, '00:08:86', 'Hansung Teliann, Inc.'),
(2158, '00:08:87', 'Maschinenfabrik Reinhausen GmbH'),
(2159, '00:08:88', 'OULLIM Information Technology Inc,.'),
(2160, '00:08:89', 'Echostar Technologies Corp'),
(2161, '00:08:8A', 'Minds@Work'),
(2162, '00:08:8B', 'Tropic Networks Inc.'),
(2163, '00:08:8C', 'Quanta Network Systems Inc.'),
(2164, '00:08:8D', 'Sigma-Links Inc.'),
(2165, '00:08:8E', 'Nihon Computer Co., Ltd.'),
(2166, '00:08:8F', 'ADVANCED DIGITAL TECHNOLOGY'),
(2167, '00:08:90', 'AVILINKS SA'),
(2168, '00:08:91', 'Lyan Inc.'),
(2169, '00:08:92', 'EM Solutions'),
(2170, '00:08:93', 'LE INFORMATION COMMUNICATION INC.'),
(2171, '00:08:94', 'InnoVISION Multimedia Ltd.'),
(2172, '00:08:95', 'DIRC Technologie GmbH &amp; Co.KG'),
(2173, '00:08:96', 'Printronix, Inc.'),
(2174, '00:08:97', 'Quake Technologies'),
(2175, '00:08:98', 'Gigabit Optics Corporation'),
(2176, '00:08:99', 'Netbind, Inc.'),
(2177, '00:08:9A', 'Alcatel Microelectronics'),
(2178, '00:08:9B', 'ICP Electronics Inc.'),
(2179, '00:08:9C', 'Elecs Industry Co., Ltd.'),
(2180, '00:08:9D', 'UHD-Elektronik'),
(2181, '00:08:9E', 'Beijing Enter-Net co.LTD'),
(2182, '00:08:9F', 'EFM Networks'),
(2183, '00:08:A0', 'Stotz Feinmesstechnik GmbH'),
(2184, '00:08:A1', 'CNet Technology Inc.'),
(2185, '00:08:A2', 'ADI Engineering, Inc.'),
(2186, '00:08:A3', 'CISCO SYSTEMS, INC.'),
(2187, '00:08:A4', 'CISCO SYSTEMS, INC.'),
(2188, '00:08:A5', 'Peninsula Systems Inc.'),
(2189, '00:08:A6', 'Multiware &amp; Image Co., Ltd.'),
(2190, '00:08:A7', 'iLogic Inc.'),
(2191, '00:08:A8', 'Systec Co., Ltd.'),
(2192, '00:08:A9', 'SangSang Technology, Inc.'),
(2193, '00:08:AA', 'KARAM'),
(2194, '00:08:AB', 'EnerLinx.com, Inc.'),
(2195, '00:08:AC', 'Eltromat GmbH'),
(2196, '00:08:AD', 'Toyo-Linx Co., Ltd.'),
(2197, '00:08:AE', 'PacketFront Network Products AB'),
(2198, '00:08:AF', 'Novatec Corporation'),
(2199, '00:08:B0', 'BKtel communications GmbH'),
(2200, '00:08:B1', 'ProQuent Systems'),
(2201, '00:08:B2', 'SHENZHEN COMPASS TECHNOLOGY DEVELOPMENT CO.,LTD'),
(2202, '00:08:B3', 'Fastwel'),
(2203, '00:08:B4', 'SYSPOL'),
(2204, '00:08:B5', 'TAI GUEN ENTERPRISE CO., LTD'),
(2205, '00:08:B6', 'RouteFree, Inc.'),
(2206, '00:08:B7', 'HIT Incorporated'),
(2207, '00:08:B8', 'E.F. Johnson'),
(2208, '00:08:B9', 'KAON MEDIA Co., Ltd.'),
(2209, '00:08:BA', 'Erskine Systems Ltd'),
(2210, '00:08:BB', 'NetExcell'),
(2211, '00:08:BC', 'Ilevo AB'),
(2212, '00:08:BD', 'TEPG-US'),
(2213, '00:08:BE', 'XENPAK MSA Group'),
(2214, '00:08:BF', 'Aptus Elektronik AB'),
(2215, '00:08:C0', 'ASA SYSTEMS'),
(2216, '00:08:C1', 'Avistar Communications Corporation'),
(2217, '00:08:C2', 'CISCO SYSTEMS, INC.'),
(2218, '00:08:C3', 'Contex A/S'),
(2219, '00:08:C4', 'Hikari Co.,Ltd.'),
(2220, '00:08:C5', 'Liontech Co., Ltd.'),
(2221, '00:08:C6', 'Philips Consumer Communications'),
(2222, '00:08:C7', 'Hewlett-Packard Company'),
(2223, '00:08:C8', 'Soneticom, Inc.'),
(2224, '00:08:C9', 'TechniSat Digital GmbH'),
(2225, '00:08:CA', 'TwinHan Technology Co.,Ltd'),
(2226, '00:08:CB', 'Zeta Broadband Inc.'),
(2227, '00:08:CC', 'Remotec, Inc.'),
(2228, '00:08:CD', 'With-Net Inc'),
(2229, '00:08:CE', 'IPMobileNet Inc.'),
(2230, '00:08:CF', 'Nippon Koei Power Systems Co., Ltd.'),
(2231, '00:08:D0', 'Musashi Engineering Co., LTD.'),
(2232, '00:08:D1', 'KAREL INC.'),
(2233, '00:08:D2', 'ZOOM Networks Inc.'),
(2234, '00:08:D3', 'Hercules Technologies S.A.S.'),
(2235, '00:08:D4', 'IneoQuest Technologies, Inc'),
(2236, '00:08:D5', 'Vanguard Networks Solutions, LLC'),
(2237, '00:08:D6', 'HASSNET Inc.'),
(2238, '00:08:D7', 'HOW CORPORATION'),
(2239, '00:08:D8', 'Dowkey Microwave'),
(2240, '00:08:D9', 'Mitadenshi Co.,LTD'),
(2241, '00:08:DA', 'SofaWare Technologies Ltd.'),
(2242, '00:08:DB', 'Corrigent Systems'),
(2243, '00:08:DC', 'Wiznet'),
(2244, '00:08:DD', 'Telena Communications, Inc.'),
(2245, '00:08:DE', '3UP Systems'),
(2246, '00:08:DF', 'Alistel Inc.'),
(2247, '00:08:E0', 'ATO Technology Ltd.'),
(2248, '00:08:E1', 'Barix AG'),
(2249, '00:08:E2', 'CISCO SYSTEMS, INC.'),
(2250, '00:08:E3', 'CISCO SYSTEMS, INC.'),
(2251, '00:08:E4', 'Envenergy Inc'),
(2252, '00:08:E5', 'IDK Corporation'),
(2253, '00:08:E6', 'Littlefeet'),
(2254, '00:08:E7', 'SHI ControlSystems,Ltd.'),
(2255, '00:08:E8', 'Excel Master Ltd.'),
(2256, '00:08:E9', 'NextGig'),
(2257, '00:08:EA', 'Motion Control Engineering, Inc'),
(2258, '00:08:EB', 'ROMWin Co.,Ltd.'),
(2259, '00:08:EC', 'Optical Zonu Corporation'),
(2260, '00:08:ED', 'ST&amp;T Instrument Corp.'),
(2261, '00:08:EE', 'Logic Product Development'),
(2262, '00:08:EF', 'DIBAL,S.A.'),
(2263, '00:08:F0', 'Next Generation Systems, Inc.'),
(2264, '00:08:F1', 'Voltaire'),
(2265, '00:08:F2', 'C&amp;S Technology'),
(2266, '00:08:F3', 'WANY'),
(2267, '00:08:F4', 'Bluetake Technology Co., Ltd.'),
(2268, '00:08:F5', 'YESTECHNOLOGY Co.,Ltd.'),
(2269, '00:08:F6', 'Sumitomo Electric System Solutions Co., Ltd.'),
(2270, '00:08:F7', 'Hitachi Ltd, Semiconductor &amp; Integrated Circuits Gr'),
(2271, '00:08:F8', 'UTC CCS'),
(2272, '00:08:F9', 'Artesyn Embedded Technologies'),
(2273, '00:08:FA', 'Karl E.Brinkmann GmbH'),
(2274, '00:08:FB', 'SonoSite, Inc.'),
(2275, '00:08:FC', 'Gigaphoton Inc.'),
(2276, '00:08:FD', 'BlueKorea Co., Ltd.'),
(2277, '00:08:FE', 'UNIK C&amp;C Co.,Ltd.'),
(2278, '00:08:FF', 'Trilogy Communications Ltd'),
(2279, '00:09:00', 'TMT'),
(2280, '00:09:01', 'Shenzhen Shixuntong Information &amp; Technoligy Co'),
(2281, '00:09:02', 'Redline Communications Inc.'),
(2282, '00:09:03', 'Panasas, Inc'),
(2283, '00:09:04', 'MONDIAL electronic'),
(2284, '00:09:05', 'iTEC Technologies Ltd.'),
(2285, '00:09:06', 'Esteem Networks'),
(2286, '00:09:07', 'Chrysalis Development'),
(2287, '00:09:08', 'VTech Technology Corp.'),
(2288, '00:09:09', 'Telenor Connect A/S'),
(2289, '00:09:0A', 'SnedFar Technology Co., Ltd.'),
(2290, '00:09:0B', 'MTL  Instruments PLC'),
(2291, '00:09:0C', 'Mayekawa Mfg. Co. Ltd.'),
(2292, '00:09:0D', 'LEADER ELECTRONICS CORP.'),
(2293, '00:09:0E', 'Helix Technology Inc.'),
(2294, '00:09:0F', 'Fortinet Inc.'),
(2295, '00:09:10', 'Simple Access Inc.'),
(2296, '00:09:11', 'CISCO SYSTEMS, INC.'),
(2297, '00:09:12', 'CISCO SYSTEMS, INC.'),
(2298, '00:09:13', 'SystemK Corporation'),
(2299, '00:09:14', 'COMPUTROLS INC.'),
(2300, '00:09:15', 'CAS Corp.'),
(2301, '00:09:16', 'Listman Home Technologies, Inc.'),
(2302, '00:09:17', 'WEM Technology Inc'),
(2303, '00:09:18', 'SAMSUNG TECHWIN CO.,LTD'),
(2304, '00:09:19', 'MDS Gateways'),
(2305, '00:09:1A', 'Macat Optics &amp; Electronics Co., Ltd.'),
(2306, '00:09:1B', 'Digital Generation Inc.'),
(2307, '00:09:1C', 'CacheVision, Inc'),
(2308, '00:09:1D', 'Proteam Computer Corporation'),
(2309, '00:09:1E', 'Firstech Technology Corp.'),
(2310, '00:09:1F', 'A&amp;D Co., Ltd.'),
(2311, '00:09:20', 'EpoX COMPUTER CO.,LTD.'),
(2312, '00:09:21', 'Planmeca Oy'),
(2313, '00:09:22', 'TST Biometrics GmbH'),
(2314, '00:09:23', 'Heaman System Co., Ltd'),
(2315, '00:09:24', 'Telebau GmbH'),
(2316, '00:09:25', 'VSN Systemen BV'),
(2317, '00:09:26', 'YODA COMMUNICATIONS, INC.'),
(2318, '00:09:27', 'TOYOKEIKI CO.,LTD.'),
(2319, '00:09:28', 'Telecore'),
(2320, '00:09:29', 'Sanyo Industries (UK) Limited'),
(2321, '00:09:2A', 'MYTECS Co.,Ltd.'),
(2322, '00:09:2B', 'iQstor Networks, Inc.'),
(2323, '00:09:2C', 'Hitpoint Inc.'),
(2324, '00:09:2D', 'HTC Corporation'),
(2325, '00:09:2E', 'B&amp;Tech System Inc.'),
(2326, '00:09:2F', 'Akom Technology Corporation'),
(2327, '00:09:30', 'AeroConcierge Inc.'),
(2328, '00:09:31', 'Future Internet, Inc.'),
(2329, '00:09:32', 'Omnilux'),
(2330, '00:09:33', 'Ophit Co.Ltd.'),
(2331, '00:09:34', 'Dream-Multimedia-Tv GmbH'),
(2332, '00:09:35', 'Sandvine Incorporated'),
(2333, '00:09:36', 'Ipetronik GmbH &amp; Co. KG'),
(2334, '00:09:37', 'Inventec Appliance Corp'),
(2335, '00:09:38', 'Allot Communications'),
(2336, '00:09:39', 'ShibaSoku Co.,Ltd.'),
(2337, '00:09:3A', 'Molex Fiber Optics'),
(2338, '00:09:3B', 'HYUNDAI NETWORKS INC.'),
(2339, '00:09:3C', 'Jacques Technologies P/L'),
(2340, '00:09:3D', 'Newisys,Inc.'),
(2341, '00:09:3E', 'C&amp;I Technologies'),
(2342, '00:09:3F', 'Double-Win Enterpirse CO., LTD'),
(2343, '00:09:40', 'AGFEO GmbH &amp; Co. KG'),
(2344, '00:09:41', 'Allied Telesis K.K.'),
(2345, '00:09:42', 'Wireless Technologies, Inc'),
(2346, '00:09:43', 'CISCO SYSTEMS, INC.'),
(2347, '00:09:44', 'CISCO SYSTEMS, INC.'),
(2348, '00:09:45', 'Palmmicro Communications Inc'),
(2349, '00:09:46', 'Cluster Labs GmbH'),
(2350, '00:09:47', 'Aztek, Inc.'),
(2351, '00:09:48', 'Vista Control Systems, Corp.'),
(2352, '00:09:49', 'Glyph Technologies Inc.'),
(2353, '00:09:4A', 'Homenet Communications'),
(2354, '00:09:4B', 'FillFactory NV'),
(2355, '00:09:4C', 'Communication Weaver Co.,Ltd.'),
(2356, '00:09:4D', 'Braintree Communications Pty Ltd'),
(2357, '00:09:4E', 'BARTECH SYSTEMS INTERNATIONAL, INC'),
(2358, '00:09:4F', 'elmegt GmbH &amp; Co. KG'),
(2359, '00:09:50', 'Independent Storage Corporation'),
(2360, '00:09:51', 'Apogee Imaging Systems'),
(2361, '00:09:52', 'Auerswald GmbH &amp; Co. KG'),
(2362, '00:09:53', 'Linkage System Integration Co.Ltd.'),
(2363, '00:09:54', 'AMiT spol. s. r. o.'),
(2364, '00:09:55', 'Young Generation International Corp.'),
(2365, '00:09:56', 'Network Systems Group, Ltd. (NSG)'),
(2366, '00:09:57', 'Supercaller, Inc.'),
(2367, '00:09:58', 'INTELNET S.A.'),
(2368, '00:09:59', 'Sitecsoft'),
(2369, '00:09:5A', 'RACEWOOD TECHNOLOGY'),
(2370, '00:09:5B', 'Netgear, Inc.'),
(2371, '00:09:5C', 'Philips Medical Systems - Cardiac and Monitoring Systems (CM'),
(2372, '00:09:5D', 'Dialogue Technology Corp.'),
(2373, '00:09:5E', 'Masstech Group Inc.'),
(2374, '00:09:5F', 'Telebyte, Inc.'),
(2375, '00:09:60', 'YOZAN Inc.'),
(2376, '00:09:61', 'Switchgear and Instrumentation Ltd'),
(2377, '00:09:62', 'Sonitor Technologies AS'),
(2378, '00:09:63', 'Dominion Lasercom Inc.'),
(2379, '00:09:64', 'Hi-Techniques, Inc.'),
(2380, '00:09:65', 'HyunJu Computer Co., Ltd.'),
(2381, '00:09:66', 'Thales Navigation'),
(2382, '00:09:67', 'Tachyon, Inc'),
(2383, '00:09:68', 'TECHNOVENTURE, INC.'),
(2384, '00:09:69', 'Meret Optical Communications'),
(2385, '00:09:6A', 'Cloverleaf Communications Inc.'),
(2386, '00:09:6B', 'IBM Corp'),
(2387, '00:09:6C', 'Imedia Semiconductor Corp.'),
(2388, '00:09:6D', 'Powernet Technologies Corp.'),
(2389, '00:09:6E', 'GIANT ELECTRONICS LTD.'),
(2390, '00:09:6F', 'Beijing Zhongqing Elegant Tech. Corp.,Limited'),
(2391, '00:09:70', 'Vibration Research Corporation'),
(2392, '00:09:71', 'Time Management, Inc.'),
(2393, '00:09:72', 'Securebase,Inc'),
(2394, '00:09:73', 'Lenten Technology Co., Ltd.'),
(2395, '00:09:74', 'Innopia Technologies, Inc.'),
(2396, '00:09:75', 'fSONA Communications Corporation'),
(2397, '00:09:76', 'Datasoft ISDN Systems GmbH'),
(2398, '00:09:77', 'Brunner Elektronik AG'),
(2399, '00:09:78', 'AIJI System Co., Ltd.'),
(2400, '00:09:79', 'Advanced Television Systems Committee, Inc.'),
(2401, '00:09:7A', 'Louis Design Labs.'),
(2402, '00:09:7B', 'CISCO SYSTEMS, INC.'),
(2403, '00:09:7C', 'CISCO SYSTEMS, INC.'),
(2404, '00:09:7D', 'SecWell Networks Oy'),
(2405, '00:09:7E', 'IMI TECHNOLOGY CO., LTD'),
(2406, '00:09:7F', 'Vsecure 2000 LTD.'),
(2407, '00:09:80', 'Power Zenith Inc.'),
(2408, '00:09:81', 'Newport Networks'),
(2409, '00:09:82', 'Loewe Opta GmbH'),
(2410, '00:09:83', 'GlobalTop Technology, Inc.'),
(2411, '00:09:84', 'MyCasa Network Inc.'),
(2412, '00:09:85', 'Auto Telecom Company'),
(2413, '00:09:86', 'Metalink LTD.'),
(2414, '00:09:87', 'NISHI NIPPON ELECTRIC WIRE &amp; CABLE CO.,LTD.'),
(2415, '00:09:88', 'Nudian Electron Co., Ltd.'),
(2416, '00:09:89', 'VividLogic Inc.'),
(2417, '00:09:8A', 'EqualLogic Inc'),
(2418, '00:09:8B', 'Entropic Communications, Inc.'),
(2419, '00:09:8C', 'Option Wireless Sweden'),
(2420, '00:09:8D', 'Velocity Semiconductor'),
(2421, '00:09:8E', 'ipcas GmbH'),
(2422, '00:09:8F', 'Cetacean Networks'),
(2423, '00:09:90', 'ACKSYS Communications &amp; systems'),
(2424, '00:09:91', 'GE Fanuc Automation Manufacturing, Inc.'),
(2425, '00:09:92', 'InterEpoch Technology,INC.'),
(2426, '00:09:93', 'Visteon Corporation'),
(2427, '00:09:94', 'Cronyx Engineering'),
(2428, '00:09:95', 'Castle Technology Ltd'),
(2429, '00:09:96', 'RDI'),
(2430, '00:09:97', 'Nortel Networks'),
(2431, '00:09:98', 'Capinfo Company Limited'),
(2432, '00:09:99', 'CP GEORGES RENAULT'),
(2433, '00:09:9A', 'ELMO COMPANY, LIMITED'),
(2434, '00:09:9B', 'Western Telematic Inc.'),
(2435, '00:09:9C', 'Naval Research Laboratory'),
(2436, '00:09:9D', 'Haliplex Communications'),
(2437, '00:09:9E', 'Testech, Inc.'),
(2438, '00:09:9F', 'VIDEX INC.'),
(2439, '00:09:A0', 'Microtechno Corporation'),
(2440, '00:09:A1', 'Telewise Communications, Inc.'),
(2441, '00:09:A2', 'Interface Co., Ltd.'),
(2442, '00:09:A3', 'Leadfly Techologies Corp. Ltd.'),
(2443, '00:09:A4', 'HARTEC Corporation'),
(2444, '00:09:A5', 'HANSUNG ELETRONIC INDUSTRIES DEVELOPMENT CO., LTD'),
(2445, '00:09:A6', 'Ignis Optics, Inc.'),
(2446, '00:09:A7', 'Bang &amp; Olufsen A/S'),
(2447, '00:09:A8', 'Eastmode Pte Ltd'),
(2448, '00:09:A9', 'Ikanos Communications'),
(2449, '00:09:AA', 'Data Comm for Business, Inc.'),
(2450, '00:09:AB', 'Netcontrol Oy'),
(2451, '00:09:AC', 'LANVOICE'),
(2452, '00:09:AD', 'HYUNDAI SYSCOMM, INC.'),
(2453, '00:09:AE', 'OKANO ELECTRIC CO.,LTD'),
(2454, '00:09:AF', 'e-generis'),
(2455, '00:09:B0', 'Onkyo Corporation'),
(2456, '00:09:B1', 'Kanematsu Electronics, Ltd.'),
(2457, '00:09:B2', 'L&amp;F Inc.'),
(2458, '00:09:B3', 'MCM Systems Ltd'),
(2459, '00:09:B4', 'KISAN TELECOM CO., LTD.'),
(2460, '00:09:B5', '3J Tech. Co., Ltd.'),
(2461, '00:09:B6', 'CISCO SYSTEMS, INC.'),
(2462, '00:09:B7', 'CISCO SYSTEMS, INC.'),
(2463, '00:09:B8', 'Entise Systems'),
(2464, '00:09:B9', 'Action Imaging Solutions'),
(2465, '00:09:BA', 'MAKU Informationstechik GmbH'),
(2466, '00:09:BB', 'MathStar, Inc.'),
(2467, '00:09:BC', 'Digital Safety Technologies, Inc'),
(2468, '00:09:BD', 'Epygi Technologies, Ltd.'),
(2469, '00:09:BE', 'Mamiya-OP Co.,Ltd.'),
(2470, '00:09:BF', 'Nintendo Co., Ltd.'),
(2471, '00:09:C0', '6WIND'),
(2472, '00:09:C1', 'PROCES-DATA A/S'),
(2473, '00:09:C2', 'Onity, Inc.'),
(2474, '00:09:C3', 'NETAS'),
(2475, '00:09:C4', 'Medicore Co., Ltd'),
(2476, '00:09:C5', 'KINGENE Technology Corporation'),
(2477, '00:09:C6', 'Visionics Corporation'),
(2478, '00:09:C7', 'Movistec'),
(2479, '00:09:C8', 'SINAGAWA TSUSHIN KEISOU SERVICE'),
(2480, '00:09:C9', 'BlueWINC Co., Ltd.'),
(2481, '00:09:CA', 'iMaxNetworks(Shenzhen)Limited.'),
(2482, '00:09:CB', 'HBrain'),
(2483, '00:09:CC', 'Moog GmbH'),
(2484, '00:09:CD', 'HUDSON SOFT CO.,LTD.'),
(2485, '00:09:CE', 'SpaceBridge Semiconductor Corp.'),
(2486, '00:09:CF', 'iAd GmbH'),
(2487, '00:09:D0', 'Solacom Technologies Inc.'),
(2488, '00:09:D1', 'SERANOA NETWORKS INC'),
(2489, '00:09:D2', 'Mai Logic Inc.'),
(2490, '00:09:D3', 'Western DataCom Co., Inc.'),
(2491, '00:09:D4', 'Transtech Networks'),
(2492, '00:09:D5', 'Signal Communication, Inc.'),
(2493, '00:09:D6', 'KNC One GmbH'),
(2494, '00:09:D7', 'DC Security Products'),
(2495, '00:09:D8', 'F&auml;lt Communications AB'),
(2496, '00:09:D9', 'Neoscale Systems, Inc'),
(2497, '00:09:DA', 'Control Module Inc.'),
(2498, '00:09:DB', 'eSpace'),
(2499, '00:09:DC', 'Galaxis Technology AG'),
(2500, '00:09:DD', 'Mavin Technology Inc.'),
(2501, '00:09:DE', 'Samjin Information &amp; Communications Co., Ltd.'),
(2502, '00:09:DF', 'Vestel Komunikasyon Sanayi ve Ticaret A.S.'),
(2503, '00:09:E0', 'XEMICS S.A.'),
(2504, '00:09:E1', 'Gemtek Technology Co., Ltd.'),
(2505, '00:09:E2', 'Sinbon Electronics Co., Ltd.'),
(2506, '00:09:E3', 'Angel Iglesias S.A.'),
(2507, '00:09:E4', 'K Tech Infosystem Inc.'),
(2508, '00:09:E5', 'Hottinger Baldwin Messtechnik GmbH'),
(2509, '00:09:E6', 'Cyber Switching Inc.'),
(2510, '00:09:E7', 'ADC Techonology'),
(2511, '00:09:E8', 'CISCO SYSTEMS, INC.'),
(2512, '00:09:E9', 'CISCO SYSTEMS, INC.'),
(2513, '00:09:EA', 'YEM Inc.'),
(2514, '00:09:EB', 'HuMANDATA LTD.'),
(2515, '00:09:EC', 'Daktronics, Inc.'),
(2516, '00:09:ED', 'CipherOptics'),
(2517, '00:09:EE', 'MEIKYO ELECTRIC CO.,LTD'),
(2518, '00:09:EF', 'Vocera Communications'),
(2519, '00:09:F0', 'Shimizu Technology Inc.'),
(2520, '00:09:F1', 'Yamaki Electric Corporation'),
(2521, '00:09:F2', 'Cohu, Inc., Electronics Division'),
(2522, '00:09:F3', 'WELL Communication Corp.'),
(2523, '00:09:F4', 'Alcon Laboratories, Inc.'),
(2524, '00:09:F5', 'Emerson Network Power Co.,Ltd'),
(2525, '00:09:F6', 'Shenzhen Eastern Digital Tech Ltd.'),
(2526, '00:09:F7', 'SED, a division of Calian'),
(2527, '00:09:F8', 'UNIMO TECHNOLOGY CO., LTD.'),
(2528, '00:09:F9', 'ART JAPAN CO., LTD.'),
(2529, '00:09:FB', 'Philips Patient Monitoring'),
(2530, '00:09:FC', 'IPFLEX Inc.'),
(2531, '00:09:FD', 'Ubinetics Limited'),
(2532, '00:09:FE', 'Daisy Technologies, Inc.'),
(2533, '00:09:FF', 'X.net 2000 GmbH'),
(2534, '00:0A:00', 'Mediatek Corp.'),
(2535, '00:0A:01', 'SOHOware, Inc.'),
(2536, '00:0A:02', 'ANNSO CO., LTD.'),
(2537, '00:0A:03', 'ENDESA SERVICIOS, S.L.'),
(2538, '00:0A:04', '3Com Ltd'),
(2539, '00:0A:05', 'Widax Corp.'),
(2540, '00:0A:06', 'Teledex LLC'),
(2541, '00:0A:07', 'WebWayOne Ltd'),
(2542, '00:0A:08', 'ALPINE ELECTRONICS, INC.'),
(2543, '00:0A:09', 'TaraCom Integrated Products, Inc.'),
(2544, '00:0A:0A', 'SUNIX Co., Ltd.'),
(2545, '00:0A:0B', 'Sealevel Systems, Inc.'),
(2546, '00:0A:0C', 'Scientific Research Corporation'),
(2547, '00:0A:0D', 'FCI Deutschland GmbH'),
(2548, '00:0A:0E', 'Invivo Research Inc.'),
(2549, '00:0A:0F', 'Ilryung Telesys, Inc'),
(2550, '00:0A:10', 'FAST media integrations AG'),
(2551, '00:0A:11', 'ExPet Technologies, Inc'),
(2552, '00:0A:12', 'Azylex Technology, Inc'),
(2553, '00:0A:13', 'Honeywell Video Systems'),
(2554, '00:0A:14', 'TECO a.s.'),
(2555, '00:0A:15', 'Silicon Data, Inc'),
(2556, '00:0A:16', 'Lassen Research'),
(2557, '00:0A:17', 'NESTAR COMMUNICATIONS, INC'),
(2558, '00:0A:18', 'Vichel Inc.'),
(2559, '00:0A:19', 'Valere Power, Inc.'),
(2560, '00:0A:1A', 'Imerge Ltd'),
(2561, '00:0A:1B', 'Stream Labs'),
(2562, '00:0A:1C', 'Bridge Information Co., Ltd.'),
(2563, '00:0A:1D', 'Optical Communications Products Inc.'),
(2564, '00:0A:1E', 'Red-M Products Limited'),
(2565, '00:0A:1F', 'ART WARE Telecommunication Co., Ltd.'),
(2566, '00:0A:20', 'SVA Networks, Inc.'),
(2567, '00:0A:21', 'Integra Telecom Co. Ltd'),
(2568, '00:0A:22', 'Amperion Inc'),
(2569, '00:0A:23', 'Parama Networks Inc'),
(2570, '00:0A:24', 'Octave Communications'),
(2571, '00:0A:25', 'CERAGON NETWORKS'),
(2572, '00:0A:26', 'CEIA S.p.A.'),
(2573, '00:0A:27', 'Apple'),
(2574, '00:0A:28', 'Motorola'),
(2575, '00:0A:29', 'Pan Dacom Networking AG'),
(2576, '00:0A:2A', 'QSI Systems Inc.'),
(2577, '00:0A:2B', 'Etherstuff'),
(2578, '00:0A:2C', 'Active Tchnology Corporation'),
(2579, '00:0A:2D', 'Cabot Communications Limited'),
(2580, '00:0A:2E', 'MAPLE NETWORKS CO., LTD'),
(2581, '00:0A:2F', 'Artnix Inc.'),
(2582, '00:0A:30', 'Visteon Corporation'),
(2583, '00:0A:31', 'HCV Consulting'),
(2584, '00:0A:32', 'Xsido Corporation'),
(2585, '00:0A:33', 'Emulex Corporation'),
(2586, '00:0A:34', 'Identicard Systems Incorporated'),
(2587, '00:0A:35', 'Xilinx'),
(2588, '00:0A:36', 'Synelec Telecom Multimedia'),
(2589, '00:0A:37', 'Procera Networks, Inc.'),
(2590, '00:0A:38', 'Apani Networks'),
(2591, '00:0A:39', 'LoPA Information Technology'),
(2592, '00:0A:3A', 'J-THREE INTERNATIONAL Holding Co., Ltd.'),
(2593, '00:0A:3B', 'GCT Semiconductor, Inc'),
(2594, '00:0A:3C', 'Enerpoint Ltd.'),
(2595, '00:0A:3D', 'Elo Sistemas Eletronicos S.A.'),
(2596, '00:0A:3E', 'EADS Telecom'),
(2597, '00:0A:3F', 'Data East Corporation'),
(2598, '00:0A:40', 'Crown Audio -- Harmanm International'),
(2599, '00:0A:41', 'CISCO SYSTEMS, INC.'),
(2600, '00:0A:42', 'CISCO SYSTEMS, INC.'),
(2601, '00:0A:43', 'Chunghwa Telecom Co., Ltd.'),
(2602, '00:0A:44', 'Avery Dennison Deutschland GmbH'),
(2603, '00:0A:45', 'Audio-Technica Corp.'),
(2604, '00:0A:46', 'ARO WELDING TECHNOLOGIES SAS'),
(2605, '00:0A:47', 'Allied Vision Technologies'),
(2606, '00:0A:48', 'Albatron Technology'),
(2607, '00:0A:49', 'F5 Networks, Inc.'),
(2608, '00:0A:4A', 'Targa Systems Ltd.'),
(2609, '00:0A:4B', 'DataPower Technology, Inc.'),
(2610, '00:0A:4C', 'Molecular Devices Corporation'),
(2611, '00:0A:4D', 'Noritz Corporation'),
(2612, '00:0A:4E', 'UNITEK Electronics INC.'),
(2613, '00:0A:4F', 'Brain Boxes Limited'),
(2614, '00:0A:50', 'REMOTEK CORPORATION'),
(2615, '00:0A:51', 'GyroSignal Technology Co., Ltd.'),
(2616, '00:0A:52', 'AsiaRF Ltd.'),
(2617, '00:0A:53', 'Intronics, Incorporated'),
(2618, '00:0A:54', 'Laguna Hills, Inc.'),
(2619, '00:0A:55', 'MARKEM Corporation'),
(2620, '00:0A:56', 'HITACHI Maxell Ltd.'),
(2621, '00:0A:57', 'Hewlett-Packard Company - Standards'),
(2622, '00:0A:58', 'Freyer &amp; Siegel Elektronik GmbH &amp; Co. KG'),
(2623, '00:0A:59', 'HW server'),
(2624, '00:0A:5A', 'GreenNET Technologies Co.,Ltd.'),
(2625, '00:0A:5B', 'Power-One as'),
(2626, '00:0A:5C', 'Carel s.p.a.'),
(2627, '00:0A:5D', 'FingerTec Worldwide Sdn Bhd'),
(2628, '00:0A:5E', '3COM Corporation'),
(2629, '00:0A:5F', 'almedio inc.'),
(2630, '00:0A:60', 'Autostar Technology Pte Ltd'),
(2631, '00:0A:61', 'Cellinx Systems Inc.'),
(2632, '00:0A:62', 'Crinis Networks, Inc.'),
(2633, '00:0A:63', 'DHD GmbH'),
(2634, '00:0A:64', 'Eracom Technologies'),
(2635, '00:0A:65', 'GentechMedia.co.,ltd.'),
(2636, '00:0A:66', 'MITSUBISHI ELECTRIC SYSTEM &amp; SERVICE CO.,LTD.'),
(2637, '00:0A:67', 'OngCorp'),
(2638, '00:0A:68', 'SolarFlare Communications, Inc.'),
(2639, '00:0A:69', 'SUNNY bell Technology Co., Ltd.'),
(2640, '00:0A:6A', 'SVM Microwaves s.r.o.'),
(2641, '00:0A:6B', 'Tadiran Telecom Business Systems LTD'),
(2642, '00:0A:6C', 'Walchem Corporation'),
(2643, '00:0A:6D', 'EKS Elektronikservice GmbH'),
(2644, '00:0A:6E', 'Harmonic, Inc'),
(2645, '00:0A:6F', 'ZyFLEX Technologies Inc'),
(2646, '00:0A:70', 'MPLS Forum'),
(2647, '00:0A:71', 'Avrio Technologies, Inc'),
(2648, '00:0A:72', 'STEC, INC.'),
(2649, '00:0A:73', 'Scientific Atlanta'),
(2650, '00:0A:74', 'Manticom Networks Inc.'),
(2651, '00:0A:75', 'Caterpillar, Inc'),
(2652, '00:0A:76', 'Beida Jade Bird Huaguang Technology Co.,Ltd'),
(2653, '00:0A:77', 'Bluewire Technologies LLC'),
(2654, '00:0A:78', 'OLITEC'),
(2655, '00:0A:79', 'corega K.K'),
(2656, '00:0A:7A', 'Kyoritsu Electric Co., Ltd.'),
(2657, '00:0A:7B', 'Cornelius Consult'),
(2658, '00:0A:7C', 'Tecton Ltd'),
(2659, '00:0A:7D', 'Valo, Inc.'),
(2660, '00:0A:7E', 'The Advantage Group'),
(2661, '00:0A:7F', 'Teradon Industries, Inc'),
(2662, '00:0A:80', 'Telkonet Inc.'),
(2663, '00:0A:81', 'TEIMA Audiotex S.L.'),
(2664, '00:0A:82', 'TATSUTA SYSTEM ELECTRONICS CO.,LTD.'),
(2665, '00:0A:83', 'SALTO SYSTEMS S.L.'),
(2666, '00:0A:84', 'Rainsun Enterprise Co., Ltd.'),
(2667, '00:0A:85', 'PLAT\'C2,Inc'),
(2668, '00:0A:86', 'Lenze'),
(2669, '00:0A:87', 'Integrated Micromachines Inc.'),
(2670, '00:0A:88', 'InCypher S.A.'),
(2671, '00:0A:89', 'Creval Systems, Inc.'),
(2672, '00:0A:8A', 'CISCO SYSTEMS, INC.'),
(2673, '00:0A:8B', 'CISCO SYSTEMS, INC.'),
(2674, '00:0A:8C', 'Guardware Systems Ltd.'),
(2675, '00:0A:8D', 'EUROTHERM LIMITED'),
(2676, '00:0A:8E', 'Invacom Ltd'),
(2677, '00:0A:8F', 'Aska International Inc.'),
(2678, '00:0A:90', 'Bayside Interactive, Inc.'),
(2679, '00:0A:91', 'HemoCue AB'),
(2680, '00:0A:92', 'Presonus Corporation'),
(2681, '00:0A:93', 'W2 Networks, Inc.'),
(2682, '00:0A:94', 'ShangHai cellink CO., LTD'),
(2683, '00:0A:95', 'Apple'),
(2684, '00:0A:96', 'MEWTEL TECHNOLOGY INC.'),
(2685, '00:0A:97', 'SONICblue, Inc.'),
(2686, '00:0A:98', 'M+F Gwinner GmbH &amp; Co'),
(2687, '00:0A:99', 'Calamp Wireless Networks Inc'),
(2688, '00:0A:9A', 'Aiptek International Inc'),
(2689, '00:0A:9B', 'TB Group Inc'),
(2690, '00:0A:9C', 'Server Technology, Inc.'),
(2691, '00:0A:9D', 'King Young Technology Co. Ltd.'),
(2692, '00:0A:9E', 'BroadWeb Corportation'),
(2693, '00:0A:9F', 'Pannaway Technologies, Inc.'),
(2694, '00:0A:A0', 'Cedar Point Communications'),
(2695, '00:0A:A1', 'V V S Limited'),
(2696, '00:0A:A2', 'SYSTEK INC.'),
(2697, '00:0A:A3', 'SHIMAFUJI ELECTRIC CO.,LTD.'),
(2698, '00:0A:A4', 'SHANGHAI SURVEILLANCE TECHNOLOGY CO,LTD'),
(2699, '00:0A:A5', 'MAXLINK INDUSTRIES LIMITED'),
(2700, '00:0A:A6', 'Hochiki Corporation'),
(2701, '00:0A:A7', 'FEI Electron Optics'),
(2702, '00:0A:A8', 'ePipe Pty. Ltd.'),
(2703, '00:0A:A9', 'Brooks Automation GmbH'),
(2704, '00:0A:AA', 'AltiGen Communications Inc.'),
(2705, '00:0A:AB', 'Toyota Technical Development Corporation'),
(2706, '00:0A:AC', 'TerraTec Electronic GmbH'),
(2707, '00:0A:AD', 'Stargames Corporation'),
(2708, '00:0A:AE', 'Rosemount Process Analytical'),
(2709, '00:0A:AF', 'Pipal Systems'),
(2710, '00:0A:B0', 'LOYTEC electronics GmbH'),
(2711, '00:0A:B1', 'GENETEC Corporation'),
(2712, '00:0A:B2', 'Fresnel Wireless Systems'),
(2713, '00:0A:B3', 'Fa. GIRA'),
(2714, '00:0A:B4', 'ETIC Telecommunications'),
(2715, '00:0A:B5', 'Digital Electronic Network'),
(2716, '00:0A:B6', 'COMPUNETIX, INC'),
(2717, '00:0A:B7', 'CISCO SYSTEMS, INC.'),
(2718, '00:0A:B8', 'CISCO SYSTEMS, INC.'),
(2719, '00:0A:B9', 'Astera Technologies Corp.'),
(2720, '00:0A:BA', 'Arcon Technology Limited'),
(2721, '00:0A:BB', 'Taiwan Secom Co,. Ltd'),
(2722, '00:0A:BC', 'Seabridge Ltd.'),
(2723, '00:0A:BD', 'Rupprecht &amp; Patashnick Co.'),
(2724, '00:0A:BE', 'OPNET Technologies CO., LTD.'),
(2725, '00:0A:BF', 'HIROTA SS'),
(2726, '00:0A:C0', 'Fuyoh Video Industry CO., LTD.'),
(2727, '00:0A:C1', 'Futuretel'),
(2728, '00:0A:C2', 'FiberHome Telecommunication Technologies CO.,LTD'),
(2729, '00:0A:C3', 'eM Technics Co., Ltd.'),
(2730, '00:0A:C4', 'Daewoo Teletech Co., Ltd'),
(2731, '00:0A:C5', 'Color Kinetics'),
(2732, '00:0A:C6', 'Overture Networks.'),
(2733, '00:0A:C7', 'Unication Group'),
(2734, '00:0A:C8', 'ZPSYS CO.,LTD. (Planning&amp;Management)'),
(2735, '00:0A:C9', 'Zambeel Inc'),
(2736, '00:0A:CA', 'YOKOYAMA SHOKAI CO.,Ltd.'),
(2737, '00:0A:CB', 'XPAK MSA Group'),
(2738, '00:0A:CC', 'Winnow Networks, Inc.'),
(2739, '00:0A:CD', 'Sunrich Technology Limited'),
(2740, '00:0A:CE', 'RADIANTECH, INC.'),
(2741, '00:0A:CF', 'PROVIDEO Multimedia Co. Ltd.'),
(2742, '00:0A:D0', 'Niigata Develoment Center,  F.I.T. Co., Ltd.'),
(2743, '00:0A:D1', 'MWS'),
(2744, '00:0A:D2', 'JEPICO Corporation'),
(2745, '00:0A:D3', 'INITECH Co., Ltd'),
(2746, '00:0A:D4', 'CoreBell Systems Inc.'),
(2747, '00:0A:D5', 'Brainchild Electronic Co., Ltd.'),
(2748, '00:0A:D6', 'BeamReach Networks'),
(2749, '00:0A:D7', 'Origin ELECTRIC CO.,LTD.'),
(2750, '00:0A:D8', 'IPCserv Technology Corp.'),
(2751, '00:0A:D9', 'Sony Ericsson Mobile Communications AB'),
(2752, '00:0A:DA', 'Vindicator Technologies'),
(2753, '00:0A:DB', 'SkyPilot Network, Inc'),
(2754, '00:0A:DC', 'RuggedCom Inc.'),
(2755, '00:0A:DD', 'Allworx Corp.'),
(2756, '00:0A:DE', 'Happy Communication Co., Ltd.'),
(2757, '00:0A:DF', 'Gennum Corporation'),
(2758, '00:0A:E0', 'Fujitsu Softek'),
(2759, '00:0A:E1', 'EG Technology'),
(2760, '00:0A:E2', 'Binatone Electronics International, Ltd'),
(2761, '00:0A:E3', 'YANG MEI TECHNOLOGY CO., LTD'),
(2762, '00:0A:E4', 'Wistron Corp.'),
(2763, '00:0A:E5', 'ScottCare Corporation'),
(2764, '00:0A:E6', 'Elitegroup Computer System Co. (ECS)'),
(2765, '00:0A:E7', 'ELIOP S.A.'),
(2766, '00:0A:E8', 'Cathay Roxus Information Technology Co. LTD'),
(2767, '00:0A:E9', 'AirVast Technology Inc.'),
(2768, '00:0A:EA', 'ADAM ELEKTRONIK LTD. ÅTI'),
(2769, '00:0A:EB', 'Shenzhen Tp-Link Technology Co; Ltd.'),
(2770, '00:0A:EC', 'Koatsu Gas Kogyo Co., Ltd.'),
(2771, '00:0A:ED', 'HARTING Systems GmbH &amp; Co KG'),
(2772, '00:0A:EE', 'GCD Hard- &amp; Software GmbH'),
(2773, '00:0A:EF', 'OTRUM ASA'),
(2774, '00:0A:F0', 'SHIN-OH ELECTRONICS CO., LTD. R&amp;D'),
(2775, '00:0A:F1', 'Clarity Design, Inc.'),
(2776, '00:0A:F2', 'NeoAxiom Corp.'),
(2777, '00:0A:F3', 'CISCO SYSTEMS, INC.'),
(2778, '00:0A:F4', 'CISCO SYSTEMS, INC.'),
(2779, '00:0A:F5', 'Airgo Networks, Inc.'),
(2780, '00:0A:F6', 'Emerson Climate Technologies Retail Solutions, Inc.'),
(2781, '00:0A:F7', 'Broadcom Corp.'),
(2782, '00:0A:F8', 'American Telecare Inc.'),
(2783, '00:0A:F9', 'HiConnect, Inc.'),
(2784, '00:0A:FA', 'Traverse Technologies Australia'),
(2785, '00:0A:FB', 'Ambri Limited'),
(2786, '00:0A:FC', 'Core Tec Communications, LLC'),
(2787, '00:0A:FD', 'Kentec Electronics'),
(2788, '00:0A:FE', 'NovaPal Ltd'),
(2789, '00:0A:FF', 'Kilchherr Elektronik AG'),
(2790, '00:0B:00', 'FUJIAN START COMPUTER EQUIPMENT CO.,LTD'),
(2791, '00:0B:01', 'DAIICHI ELECTRONICS CO., LTD.'),
(2792, '00:0B:02', 'Dallmeier electronic'),
(2793, '00:0B:03', 'Taekwang Industrial Co., Ltd'),
(2794, '00:0B:04', 'Volktek Corporation'),
(2795, '00:0B:05', 'Pacific Broadband Networks'),
(2796, '00:0B:06', 'ARRIS Group, Inc.'),
(2797, '00:0B:07', 'Voxpath Networks'),
(2798, '00:0B:08', 'Pillar Data Systems'),
(2799, '00:0B:09', 'Ifoundry Systems Singapore'),
(2800, '00:0B:0A', 'dBm Optics'),
(2801, '00:0B:0B', 'Corrent Corporation'),
(2802, '00:0B:0C', 'Agile Systems Inc.'),
(2803, '00:0B:0D', 'Air2U, Inc.'),
(2804, '00:0B:0E', 'Trapeze Networks'),
(2805, '00:0B:0F', 'Bosch Rexroth'),
(2806, '00:0B:10', '11wave Technonlogy Co.,Ltd'),
(2807, '00:0B:11', 'HIMEJI ABC TRADING CO.,LTD.'),
(2808, '00:0B:12', 'NURI Telecom Co., Ltd.'),
(2809, '00:0B:13', 'ZETRON INC'),
(2810, '00:0B:14', 'ViewSonic Corporation'),
(2811, '00:0B:15', 'Platypus Technology'),
(2812, '00:0B:16', 'Communication Machinery Corporation'),
(2813, '00:0B:17', 'MKS Instruments'),
(2814, '00:0B:18', 'PRIVATE'),
(2815, '00:0B:19', 'Vernier Networks, Inc.'),
(2816, '00:0B:1A', 'Industrial Defender, Inc.'),
(2817, '00:0B:1B', 'Systronix, Inc.'),
(2818, '00:0B:1C', 'SIBCO bv'),
(2819, '00:0B:1D', 'LayerZero Power Systems, Inc.'),
(2820, '00:0B:1E', 'KAPPA opto-electronics GmbH'),
(2821, '00:0B:1F', 'I CON Computer Co.'),
(2822, '00:0B:20', 'Hirata corporation'),
(2823, '00:0B:21', 'G-Star Communications Inc.'),
(2824, '00:0B:22', 'Environmental Systems and Services'),
(2825, '00:0B:23', 'Siemens Subscriber Networks'),
(2826, '00:0B:24', 'AirLogic'),
(2827, '00:0B:25', 'Aeluros'),
(2828, '00:0B:26', 'Wetek Corporation'),
(2829, '00:0B:27', 'Scion Corporation'),
(2830, '00:0B:28', 'Quatech Inc.'),
(2831, '00:0B:29', 'LS(LG) Industrial Systems co.,Ltd'),
(2832, '00:0B:2A', 'HOWTEL Co., Ltd.'),
(2833, '00:0B:2B', 'HOSTNET CORPORATION'),
(2834, '00:0B:2C', 'Eiki Industrial Co. Ltd.'),
(2835, '00:0B:2D', 'Danfoss Inc.'),
(2836, '00:0B:2E', 'Cal-Comp Electronics (Thailand) Public Company Limited Taipe'),
(2837, '00:0B:2F', 'bplan GmbH'),
(2838, '00:0B:30', 'Beijing Gongye Science &amp; Technology Co.,Ltd'),
(2839, '00:0B:31', 'Yantai ZhiYang Scientific and technology industry CO., LTD'),
(2840, '00:0B:32', 'VORMETRIC, INC.'),
(2841, '00:0B:33', 'Vivato Technologies'),
(2842, '00:0B:34', 'ShangHai Broadband Technologies CO.LTD'),
(2843, '00:0B:35', 'Quad Bit System co., Ltd.'),
(2844, '00:0B:36', 'Productivity Systems, Inc.'),
(2845, '00:0B:37', 'MANUFACTURE DES MONTRES ROLEX SA'),
(2846, '00:0B:38', 'Kn&uuml;rr GmbH'),
(2847, '00:0B:39', 'Keisoku Giken Co.,Ltd.'),
(2848, '00:0B:3A', 'QuStream Corporation'),
(2849, '00:0B:3B', 'devolo AG'),
(2850, '00:0B:3C', 'Cygnal Integrated Products, Inc.'),
(2851, '00:0B:3D', 'CONTAL OK Ltd.'),
(2852, '00:0B:3E', 'BittWare, Inc'),
(2853, '00:0B:3F', 'Anthology Solutions Inc.'),
(2854, '00:0B:40', 'Oclaro'),
(2855, '00:0B:41', 'Ing. B&uuml;ro Dr. Beutlhauser'),
(2856, '00:0B:42', 'commax Co., Ltd.'),
(2857, '00:0B:43', 'Microscan Systems, Inc.'),
(2858, '00:0B:44', 'Concord IDea Corp.'),
(2859, '00:0B:45', 'CISCO SYSTEMS, INC.'),
(2860, '00:0B:46', 'CISCO SYSTEMS, INC.'),
(2861, '00:0B:47', 'Advanced Energy'),
(2862, '00:0B:48', 'sofrel'),
(2863, '00:0B:49', 'RF-Link System Inc.'),
(2864, '00:0B:4A', 'Visimetrics (UK) Ltd'),
(2865, '00:0B:4B', 'VISIOWAVE SA'),
(2866, '00:0B:4C', 'Clarion (M) Sdn Bhd'),
(2867, '00:0B:4D', 'Emuzed'),
(2868, '00:0B:4E', 'VertexRSI, General Dynamics SatCOM Technologies, Inc.'),
(2869, '00:0B:4F', 'Verifone, INC.'),
(2870, '00:0B:50', 'Oxygnet'),
(2871, '00:0B:51', 'Micetek International Inc.'),
(2872, '00:0B:52', 'JOYMAX ELECTRONICS CO. LTD.'),
(2873, '00:0B:53', 'INITIUM Co., Ltd.'),
(2874, '00:0B:54', 'BiTMICRO Networks, Inc.'),
(2875, '00:0B:55', 'ADInstruments'),
(2876, '00:0B:56', 'Cybernetics'),
(2877, '00:0B:57', 'Silicon Laboratories'),
(2878, '00:0B:58', 'Astronautics C.A  LTD'),
(2879, '00:0B:59', 'ScriptPro, LLC'),
(2880, '00:0B:5A', 'HyperEdge'),
(2881, '00:0B:5B', 'Rincon Research Corporation'),
(2882, '00:0B:5C', 'Newtech Co.,Ltd'),
(2883, '00:0B:5D', 'FUJITSU LIMITED'),
(2884, '00:0B:5E', 'Audio Engineering Society Inc.'),
(2885, '00:0B:5F', 'CISCO SYSTEMS, INC.'),
(2886, '00:0B:60', 'CISCO SYSTEMS, INC.'),
(2887, '00:0B:61', 'Friedrich L&uuml;tze GmbH &amp; Co. KG'),
(2888, '00:0B:62', 'ib-mohnen KG'),
(2889, '00:0B:63', 'Kaleidescape'),
(2890, '00:0B:64', 'Kieback &amp; Peter GmbH &amp; Co KG'),
(2891, '00:0B:65', 'Sy.A.C. srl'),
(2892, '00:0B:66', 'Teralink Communications'),
(2893, '00:0B:67', 'Topview Technology Corporation'),
(2894, '00:0B:68', 'Addvalue Communications Pte Ltd'),
(2895, '00:0B:69', 'Franke Finland Oy'),
(2896, '00:0B:6A', 'Asiarock Incorporation'),
(2897, '00:0B:6B', 'Wistron Neweb Corp.'),
(2898, '00:0B:6C', 'Sychip Inc.'),
(2899, '00:0B:6D', 'SOLECTRON JAPAN NAKANIIDA'),
(2900, '00:0B:6E', 'Neff Instrument Corp.'),
(2901, '00:0B:6F', 'Media Streaming Networks Inc'),
(2902, '00:0B:70', 'Load Technology, Inc.'),
(2903, '00:0B:71', 'Litchfield Communications Inc.'),
(2904, '00:0B:72', 'Lawo AG'),
(2905, '00:0B:73', 'Kodeos Communications'),
(2906, '00:0B:74', 'Kingwave Technology Co., Ltd.'),
(2907, '00:0B:75', 'Iosoft Ltd.'),
(2908, '00:0B:76', 'ET&amp;T Technology Co. Ltd.'),
(2909, '00:0B:77', 'Cogent Systems, Inc.'),
(2910, '00:0B:78', 'TAIFATECH INC.'),
(2911, '00:0B:79', 'X-COM, Inc.'),
(2912, '00:0B:7A', 'L-3 Linkabit'),
(2913, '00:0B:7B', 'Test-Um Inc.'),
(2914, '00:0B:7C', 'Telex Communications'),
(2915, '00:0B:7D', 'SOLOMON EXTREME INTERNATIONAL LTD.'),
(2916, '00:0B:7E', 'SAGINOMIYA Seisakusho Inc.'),
(2917, '00:0B:7F', 'Align Engineering LLC'),
(2918, '00:0B:80', 'Lycium Networks'),
(2919, '00:0B:81', 'Kaparel Corporation'),
(2920, '00:0B:82', 'Grandstream Networks, Inc.'),
(2921, '00:0B:83', 'DATAWATT B.V.'),
(2922, '00:0B:84', 'BODET'),
(2923, '00:0B:85', 'CISCO SYSTEMS, INC.'),
(2924, '00:0B:86', 'Aruba Networks'),
(2925, '00:0B:87', 'American Reliance Inc.'),
(2926, '00:0B:88', 'Vidisco ltd.'),
(2927, '00:0B:89', 'Top Global Technology, Ltd.'),
(2928, '00:0B:8A', 'MITEQ Inc.'),
(2929, '00:0B:8B', 'KERAJET, S.A.'),
(2930, '00:0B:8C', 'Flextronics'),
(2931, '00:0B:8D', 'Avvio Networks'),
(2932, '00:0B:8E', 'Ascent Corporation'),
(2933, '00:0B:8F', 'AKITA ELECTRONICS SYSTEMS CO.,LTD.'),
(2934, '00:0B:90', 'ADVA Optical Networking Ltd.'),
(2935, '00:0B:91', 'Aglaia Gesellschaft f&uuml;r Bildverarbeitung und Kommunikation mbH'),
(2936, '00:0B:92', 'Ascom Danmark A/S'),
(2937, '00:0B:93', 'Ritter Elektronik'),
(2938, '00:0B:94', 'Digital Monitoring Products, Inc.'),
(2939, '00:0B:95', 'eBet Gaming Systems Pty Ltd'),
(2940, '00:0B:96', 'Innotrac Diagnostics Oy'),
(2941, '00:0B:97', 'Matsushita Electric Industrial Co.,Ltd.'),
(2942, '00:0B:98', 'NiceTechVision'),
(2943, '00:0B:99', 'SensAble Technologies, Inc.'),
(2944, '00:0B:9A', 'Shanghai Ulink Telecom Equipment Co. Ltd.'),
(2945, '00:0B:9B', 'Sirius System Co, Ltd.'),
(2946, '00:0B:9C', 'TriBeam Technologies, Inc.'),
(2947, '00:0B:9D', 'TwinMOS Technologies Inc.'),
(2948, '00:0B:9E', 'Yasing Technology Corp.'),
(2949, '00:0B:9F', 'Neue ELSA GmbH'),
(2950, '00:0B:A0', 'T&amp;L Information Inc.'),
(2951, '00:0B:A1', 'SYSCOM Ltd.'),
(2952, '00:0B:A2', 'Sumitomo Electric Networks, Inc'),
(2953, '00:0B:A3', 'Siemens AG, I&amp;S'),
(2954, '00:0B:A4', 'Shiron Satellite Communications Ltd. (1996)'),
(2955, '00:0B:A5', 'Quasar Cipta Mandiri, PT'),
(2956, '00:0B:A6', 'Miyakawa Electric Works Ltd.'),
(2957, '00:0B:A7', 'Maranti Networks'),
(2958, '00:0B:A8', 'HANBACK ELECTRONICS CO., LTD.'),
(2959, '00:0B:A9', 'CloudShield Technologies, Inc.'),
(2960, '00:0B:AA', 'Aiphone co.,Ltd'),
(2961, '00:0B:AB', 'Advantech Technology (CHINA) Co., Ltd.'),
(2962, '00:0B:AC', '3Com Ltd'),
(2963, '00:0B:AD', 'PC-PoS Inc.'),
(2964, '00:0B:AE', 'Vitals System Inc.'),
(2965, '00:0B:AF', 'WOOJU COMMUNICATIONS Co,.Ltd'),
(2966, '00:0B:B0', 'Sysnet Telematica srl'),
(2967, '00:0B:B1', 'Super Star Technology Co., Ltd.'),
(2968, '00:0B:B2', 'SMALLBIG TECHNOLOGY'),
(2969, '00:0B:B3', 'RiT technologies Ltd.'),
(2970, '00:0B:B4', 'RDC Semiconductor Inc.,'),
(2971, '00:0B:B5', 'nStor Technologies, Inc.'),
(2972, '00:0B:B6', 'Metalligence Technology Corp.'),
(2973, '00:0B:B7', 'Micro Systems Co.,Ltd.'),
(2974, '00:0B:B8', 'Kihoku Electronic Co.'),
(2975, '00:0B:B9', 'Imsys AB'),
(2976, '00:0B:BA', 'Harmonic, Inc'),
(2977, '00:0B:BB', 'Etin Systems Co., Ltd'),
(2978, '00:0B:BC', 'En Garde Systems, Inc.'),
(2979, '00:0B:BD', 'Connexionz Limited'),
(2980, '00:0B:BE', 'CISCO SYSTEMS, INC.'),
(2981, '00:0B:BF', 'CISCO SYSTEMS, INC.'),
(2982, '00:0B:C0', 'China IWNComm Co., Ltd.'),
(2983, '00:0B:C1', 'Bay Microsystems, Inc.'),
(2984, '00:0B:C2', 'Corinex Communication Corp.'),
(2985, '00:0B:C3', 'Multiplex, Inc.'),
(2986, '00:0B:C4', 'BIOTRONIK GmbH &amp; Co'),
(2987, '00:0B:C5', 'SMC Networks, Inc.'),
(2988, '00:0B:C6', 'ISAC, Inc.'),
(2989, '00:0B:C7', 'ICET S.p.A.'),
(2990, '00:0B:C8', 'AirFlow Networks'),
(2991, '00:0B:C9', 'Electroline Equipment'),
(2992, '00:0B:CA', 'DATAVAN International Corporation'),
(2993, '00:0B:CB', 'Fagor Automation , S. Coop'),
(2994, '00:0B:CC', 'JUSAN, S.A.'),
(2995, '00:0B:CD', 'Hewlett-Packard Company'),
(2996, '00:0B:CE', 'Free2move AB'),
(2997, '00:0B:CF', 'AGFA NDT INC.'),
(2998, '00:0B:D0', 'XiMeta Technology Americas Inc.'),
(2999, '00:0B:D1', 'Aeronix, Inc.'),
(3000, '00:0B:D2', 'Remopro Technology Inc.'),
(3001, '00:0B:D3', 'cd3o'),
(3002, '00:0B:D4', 'Beijing Wise Technology &amp; Science Development Co.Ltd'),
(3003, '00:0B:D5', 'Nvergence, Inc.'),
(3004, '00:0B:D6', 'Paxton Access Ltd'),
(3005, '00:0B:D7', 'DORMA Time + Access GmbH'),
(3006, '00:0B:D8', 'Industrial Scientific Corp.'),
(3007, '00:0B:D9', 'General Hydrogen'),
(3008, '00:0B:DA', 'EyeCross Co.,Inc.'),
(3009, '00:0B:DB', 'Dell Inc'),
(3010, '00:0B:DC', 'AKCP'),
(3011, '00:0B:DD', 'TOHOKU RICOH Co., LTD.'),
(3012, '00:0B:DE', 'TELDIX GmbH'),
(3013, '00:0B:DF', 'Shenzhen RouterD Networks Limited'),
(3014, '00:0B:E0', 'SercoNet Ltd.'),
(3015, '00:0B:E1', 'Nokia NET Product Operations'),
(3016, '00:0B:E2', 'Lumenera Corporation'),
(3017, '00:0B:E3', 'Key Stream Co., Ltd.'),
(3018, '00:0B:E4', 'Hosiden Corporation'),
(3019, '00:0B:E5', 'HIMS International Corporation'),
(3020, '00:0B:E6', 'Datel Electronics'),
(3021, '00:0B:E7', 'COMFLUX TECHNOLOGY INC.'),
(3022, '00:0B:E8', 'AOIP'),
(3023, '00:0B:E9', 'Actel Corporation'),
(3024, '00:0B:EA', 'Zultys Technologies'),
(3025, '00:0B:EB', 'Systegra AG'),
(3026, '00:0B:EC', 'NIPPON ELECTRIC INSTRUMENT, INC.'),
(3027, '00:0B:ED', 'ELM Inc.'),
(3028, '00:0B:EE', 'inc.jet, Incorporated'),
(3029, '00:0B:EF', 'Code Corporation'),
(3030, '00:0B:F0', 'MoTEX Products Co., Ltd.'),
(3031, '00:0B:F1', 'LAP Laser Applikations'),
(3032, '00:0B:F2', 'Chih-Kan Technology Co., Ltd.'),
(3033, '00:0B:F3', 'BAE SYSTEMS'),
(3034, '00:0B:F4', 'PRIVATE'),
(3035, '00:0B:F5', 'Shanghai Sibo Telecom Technology Co.,Ltd'),
(3036, '00:0B:F6', 'Nitgen Co., Ltd'),
(3037, '00:0B:F7', 'NIDEK CO.,LTD'),
(3038, '00:0B:F8', 'Infinera'),
(3039, '00:0B:F9', 'Gemstone communications, Inc.'),
(3040, '00:0B:FA', 'EXEMYS SRL'),
(3041, '00:0B:FB', 'D-NET International Corporation'),
(3042, '00:0B:FC', 'CISCO SYSTEMS, INC.'),
(3043, '00:0B:FD', 'CISCO SYSTEMS, INC.'),
(3044, '00:0B:FE', 'CASTEL Broadband Limited'),
(3045, '00:0B:FF', 'Berkeley Camera Engineering'),
(3046, '00:0C:00', 'BEB Industrie-Elektronik AG'),
(3047, '00:0C:01', 'Abatron AG'),
(3048, '00:0C:02', 'ABB Oy'),
(3049, '00:0C:03', 'HDMI Licensing, LLC'),
(3050, '00:0C:04', 'Tecnova'),
(3051, '00:0C:05', 'RPA Reserch Co., Ltd.'),
(3052, '00:0C:06', 'Nixvue Systems  Pte Ltd'),
(3053, '00:0C:07', 'Iftest AG'),
(3054, '00:0C:08', 'HUMEX Technologies Corp.'),
(3055, '00:0C:09', 'Hitachi IE Systems Co., Ltd'),
(3056, '00:0C:0A', 'Guangdong Province Electronic Technology Research Institute'),
(3057, '00:0C:0B', 'Broadbus Technologies'),
(3058, '00:0C:0C', 'APPRO TECHNOLOGY INC.'),
(3059, '00:0C:0D', 'Communications &amp; Power Industries / Satcom Division'),
(3060, '00:0C:0E', 'XtremeSpectrum, Inc.'),
(3061, '00:0C:0F', 'Techno-One Co., Ltd'),
(3062, '00:0C:10', 'PNI Corporation'),
(3063, '00:0C:11', 'NIPPON DEMPA CO.,LTD.'),
(3064, '00:0C:12', 'Micro-Optronic-Messtechnik GmbH'),
(3065, '00:0C:13', 'MediaQ'),
(3066, '00:0C:14', 'Diagnostic Instruments, Inc.'),
(3067, '00:0C:15', 'CyberPower Systems, Inc.'),
(3068, '00:0C:16', 'Concorde Microsystems Inc.'),
(3069, '00:0C:17', 'AJA Video Systems Inc'),
(3070, '00:0C:18', 'Zenisu Keisoku Inc.'),
(3071, '00:0C:19', 'Telio Communications GmbH'),
(3072, '00:0C:1A', 'Quest Technical Solutions Inc.'),
(3073, '00:0C:1B', 'ORACOM Co, Ltd.'),
(3074, '00:0C:1C', 'MicroWeb Co., Ltd.'),
(3075, '00:0C:1D', 'Mettler &amp; Fuchs AG'),
(3076, '00:0C:1E', 'Global Cache'),
(3077, '00:0C:1F', 'Glimmerglass Networks'),
(3078, '00:0C:20', 'Fi WIn, Inc.'),
(3079, '00:0C:21', 'Faculty of Science and Technology, Keio University'),
(3080, '00:0C:22', 'Double D Electronics Ltd'),
(3081, '00:0C:23', 'Beijing Lanchuan Tech. Co., Ltd.'),
(3082, '00:0C:24', 'ANATOR'),
(3083, '00:0C:25', 'Allied Telesis Labs, Inc.'),
(3084, '00:0C:26', 'Weintek Labs. Inc.'),
(3085, '00:0C:27', 'Sammy Corporation'),
(3086, '00:0C:28', 'RIFATRON'),
(3087, '00:0C:29', 'VMware, Inc.'),
(3088, '00:0C:2A', 'OCTTEL Communication Co., Ltd.'),
(3089, '00:0C:2B', 'ELIAS Technology, Inc.'),
(3090, '00:0C:2C', 'Enwiser Inc.'),
(3091, '00:0C:2D', 'FullWave Technology Co., Ltd.'),
(3092, '00:0C:2E', 'Openet information technology(shenzhen) Co., Ltd.'),
(3093, '00:0C:2F', 'SeorimTechnology Co.,Ltd.'),
(3094, '00:0C:30', 'CISCO SYSTEMS, INC.'),
(3095, '00:0C:31', 'CISCO SYSTEMS, INC.'),
(3096, '00:0C:32', 'Avionic Design Development GmbH'),
(3097, '00:0C:33', 'Compucase Enterprise Co. Ltd.'),
(3098, '00:0C:34', 'Vixen Co., Ltd.'),
(3099, '00:0C:35', 'KaVo Dental GmbH &amp; Co. KG'),
(3100, '00:0C:36', 'SHARP TAKAYA ELECTRONICS INDUSTRY CO.,LTD.'),
(3101, '00:0C:37', 'Geomation, Inc.'),
(3102, '00:0C:38', 'TelcoBridges Inc.'),
(3103, '00:0C:39', 'Sentinel Wireless Inc.'),
(3104, '00:0C:3A', 'Oxance'),
(3105, '00:0C:3B', 'Orion Electric Co., Ltd.'),
(3106, '00:0C:3C', 'MediaChorus, Inc.'),
(3107, '00:0C:3D', 'Glsystech Co., Ltd.'),
(3108, '00:0C:3E', 'Crest Audio'),
(3109, '00:0C:3F', 'Cogent Defence &amp; Security Networks,'),
(3110, '00:0C:40', 'Altech Controls'),
(3111, '00:0C:41', 'Cisco-Linksys'),
(3112, '00:0C:42', 'Routerboard.com'),
(3113, '00:0C:43', 'Ralink Technology, Corp.'),
(3114, '00:0C:44', 'Automated Interfaces, Inc.'),
(3115, '00:0C:45', 'Animation Technologies Inc.'),
(3116, '00:0C:46', 'Allied Telesyn Inc.'),
(3117, '00:0C:47', 'SK Teletech(R&amp;D Planning Team)'),
(3118, '00:0C:48', 'QoStek Corporation'),
(3119, '00:0C:49', 'Dangaard Telecom RTC Division A/S'),
(3120, '00:0C:4A', 'Cygnus Microsystems (P) Limited'),
(3121, '00:0C:4B', 'Cheops Elektronik'),
(3122, '00:0C:4C', 'Arcor AG&amp;Co.'),
(3123, '00:0C:4D', 'Curtiss-Wright Controls Avionics &amp; Electronics'),
(3124, '00:0C:4E', 'Winbest Technology CO,LT'),
(3125, '00:0C:4F', 'UDTech Japan Corporation'),
(3126, '00:0C:50', 'Seagate Technology'),
(3127, '00:0C:51', 'Scientific Technologies Inc.'),
(3128, '00:0C:52', 'Roll Systems Inc.'),
(3129, '00:0C:53', 'PRIVATE'),
(3130, '00:0C:54', 'Pedestal Networks, Inc'),
(3131, '00:0C:55', 'Microlink Communications Inc.'),
(3132, '00:0C:56', 'Megatel Computer (1986) Corp.'),
(3133, '00:0C:57', 'MACKIE Engineering Services Belgium BVBA'),
(3134, '00:0C:58', 'M&amp;S Systems'),
(3135, '00:0C:59', 'Indyme Electronics, Inc.'),
(3136, '00:0C:5A', 'IBSmm Embedded Electronics Consulting'),
(3137, '00:0C:5B', 'HANWANG TECHNOLOGY CO.,LTD'),
(3138, '00:0C:5C', 'GTN Systems B.V.'),
(3139, '00:0C:5D', 'CHIC TECHNOLOGY (CHINA) CORP.'),
(3140, '00:0C:5E', 'Calypso Medical'),
(3141, '00:0C:5F', 'Avtec, Inc.'),
(3142, '00:0C:60', 'ACM Systems'),
(3143, '00:0C:61', 'AC Tech corporation DBA Advanced Digital'),
(3144, '00:0C:62', 'ABB AB, Cewe-Control'),
(3145, '00:0C:63', 'Zenith Electronics Corporation'),
(3146, '00:0C:64', 'X2 MSA Group'),
(3147, '00:0C:65', 'Sunin Telecom'),
(3148, '00:0C:66', 'Pronto Networks Inc'),
(3149, '00:0C:67', 'OYO ELECTRIC CO.,LTD'),
(3150, '00:0C:68', 'SigmaTel, Inc.'),
(3151, '00:0C:69', 'National Radio Astronomy Observatory'),
(3152, '00:0C:6A', 'MBARI'),
(3153, '00:0C:6B', 'Kurz Industrie-Elektronik GmbH'),
(3154, '00:0C:6C', 'Elgato Systems LLC'),
(3155, '00:0C:6D', 'Edwards Ltd.'),
(3156, '00:0C:6E', 'ASUSTEK COMPUTER INC.'),
(3157, '00:0C:6F', 'Amtek system co.,LTD.'),
(3158, '00:0C:70', 'ACC GmbH'),
(3159, '00:0C:71', 'Wybron, Inc'),
(3160, '00:0C:72', 'Tempearl Industrial Co., Ltd.'),
(3161, '00:0C:73', 'TELSON ELECTRONICS CO., LTD'),
(3162, '00:0C:74', 'RIVERTEC CORPORATION'),
(3163, '00:0C:75', 'Oriental integrated electronics. LTD'),
(3164, '00:0C:76', 'MICRO-STAR INTERNATIONAL CO., LTD.'),
(3165, '00:0C:77', 'Life Racing Ltd'),
(3166, '00:0C:78', 'In-Tech Electronics Limited'),
(3167, '00:0C:79', 'Extel Communications P/L'),
(3168, '00:0C:7A', 'DaTARIUS Technologies GmbH'),
(3169, '00:0C:7B', 'ALPHA PROJECT Co.,Ltd.'),
(3170, '00:0C:7C', 'Internet Information Image Inc.'),
(3171, '00:0C:7D', 'TEIKOKU ELECTRIC MFG. CO., LTD'),
(3172, '00:0C:7E', 'Tellium Incorporated'),
(3173, '00:0C:7F', 'synertronixx GmbH'),
(3174, '00:0C:80', 'Opelcomm Inc.'),
(3175, '00:0C:81', 'Schneider Electric (Australia)'),
(3176, '00:0C:82', 'NETWORK TECHNOLOGIES INC'),
(3177, '00:0C:83', 'Logical Solutions'),
(3178, '00:0C:84', 'Eazix, Inc.'),
(3179, '00:0C:85', 'CISCO SYSTEMS, INC.'),
(3180, '00:0C:86', 'CISCO SYSTEMS, INC.'),
(3181, '00:0C:87', 'AMD'),
(3182, '00:0C:88', 'Apache Micro Peripherals, Inc.'),
(3183, '00:0C:89', 'AC Electric Vehicles, Ltd.'),
(3184, '00:0C:8A', 'Bose Corporation'),
(3185, '00:0C:8B', 'Connect Tech Inc'),
(3186, '00:0C:8C', 'KODICOM CO.,LTD.'),
(3187, '00:0C:8D', 'MATRIX VISION GmbH'),
(3188, '00:0C:8E', 'Mentor Engineering Inc'),
(3189, '00:0C:8F', 'Nergal s.r.l.'),
(3190, '00:0C:90', 'Octasic Inc.'),
(3191, '00:0C:91', 'Riverhead Networks Inc.'),
(3192, '00:0C:92', 'WolfVision Gmbh'),
(3193, '00:0C:93', 'Xeline Co., Ltd.'),
(3194, '00:0C:94', 'United Electronic Industries, Inc. (EUI)'),
(3195, '00:0C:95', 'PrimeNet'),
(3196, '00:0C:96', 'OQO, Inc.'),
(3197, '00:0C:97', 'NV ADB TTV Technologies SA'),
(3198, '00:0C:98', 'LETEK Communications Inc.'),
(3199, '00:0C:99', 'HITEL LINK Co.,Ltd'),
(3200, '00:0C:9A', 'Hitech Electronics Corp.'),
(3201, '00:0C:9B', 'EE Solutions, Inc'),
(3202, '00:0C:9C', 'Chongho information &amp; communications'),
(3203, '00:0C:9D', 'UbeeAirWalk, Inc.'),
(3204, '00:0C:9E', 'MemoryLink Corp.'),
(3205, '00:0C:9F', 'NKE Corporation'),
(3206, '00:0C:A0', 'StorCase Technology, Inc.'),
(3207, '00:0C:A1', 'SIGMACOM Co., LTD.'),
(3208, '00:0C:A2', 'Harmonic Video Network'),
(3209, '00:0C:A3', 'Rancho Technology, Inc.'),
(3210, '00:0C:A4', 'Prompttec Product Management GmbH'),
(3211, '00:0C:A5', 'Naman NZ LTd'),
(3212, '00:0C:A6', 'Mintera Corporation'),
(3213, '00:0C:A7', 'Metro (Suzhou) Technologies Co., Ltd.'),
(3214, '00:0C:A8', 'Garuda Networks Corporation'),
(3215, '00:0C:A9', 'Ebtron Inc.'),
(3216, '00:0C:AA', 'Cubic Transportation Systems Inc'),
(3217, '00:0C:AB', 'COMMEND International'),
(3218, '00:0C:AC', 'Citizen Watch Co., Ltd.'),
(3219, '00:0C:AD', 'BTU International'),
(3220, '00:0C:AE', 'Ailocom Oy'),
(3221, '00:0C:AF', 'TRI TERM CO.,LTD.'),
(3222, '00:0C:B0', 'Star Semiconductor Corporation'),
(3223, '00:0C:B1', 'Salland Engineering (Europe) BV'),
(3224, '00:0C:B2', 'UNION co., ltd.'),
(3225, '00:0C:B3', 'ROUND Co.,Ltd.'),
(3226, '00:0C:B4', 'AutoCell Laboratories, Inc.'),
(3227, '00:0C:B5', 'Premier Technolgies, Inc'),
(3228, '00:0C:B6', 'NANJING SEU MOBILE &amp; INTERNET TECHNOLOGY CO.,LTD'),
(3229, '00:0C:B7', 'Nanjing Huazhuo Electronics Co., Ltd.'),
(3230, '00:0C:B8', 'MEDION AG'),
(3231, '00:0C:B9', 'LEA'),
(3232, '00:0C:BA', 'Jamex, Inc.'),
(3233, '00:0C:BB', 'ISKRAEMECO'),
(3234, '00:0C:BC', 'Iscutum'),
(3235, '00:0C:BD', 'Interface Masters, Inc'),
(3236, '00:0C:BE', 'Innominate Security Technologies AG'),
(3237, '00:0C:BF', 'Holy Stone Ent. Co., Ltd.'),
(3238, '00:0C:C0', 'Genera Oy'),
(3239, '00:0C:C1', 'Cooper Industries Inc.'),
(3240, '00:0C:C2', 'ControlNet (India) Private Limited'),
(3241, '00:0C:C3', 'BeWAN systems'),
(3242, '00:0C:C4', 'Tiptel AG'),
(3243, '00:0C:C5', 'Nextlink Co., Ltd.'),
(3244, '00:0C:C6', 'Ka-Ro electronics GmbH'),
(3245, '00:0C:C7', 'Intelligent Computer Solutions Inc.'),
(3246, '00:0C:C8', 'Xytronix Research &amp; Design, Inc.'),
(3247, '00:0C:C9', 'ILWOO DATA &amp; TECHNOLOGY CO.,LTD'),
(3248, '00:0C:CA', 'HGST a Western Digital Company'),
(3249, '00:0C:CB', 'Design Combus Ltd'),
(3250, '00:0C:CC', 'Aeroscout Ltd.'),
(3251, '00:0C:CD', 'IEC - TC57'),
(3252, '00:0C:CE', 'CISCO SYSTEMS, INC.'),
(3253, '00:0C:CF', 'CISCO SYSTEMS, INC.'),
(3254, '00:0C:D0', 'Symetrix'),
(3255, '00:0C:D1', 'SFOM Technology Corp.'),
(3256, '00:0C:D2', 'Schaffner EMV AG'),
(3257, '00:0C:D3', 'Prettl Elektronik Radeberg GmbH'),
(3258, '00:0C:D4', 'Positron Public Safety Systems inc.'),
(3259, '00:0C:D5', 'Passave Inc.'),
(3260, '00:0C:D6', 'PARTNER TECH'),
(3261, '00:0C:D7', 'Nallatech Ltd'),
(3262, '00:0C:D8', 'M. K. Juchheim GmbH &amp; Co'),
(3263, '00:0C:D9', 'Itcare Co., Ltd'),
(3264, '00:0C:DA', 'FreeHand Systems, Inc.'),
(3265, '00:0C:DB', 'Brocade Communications Systems, Inc'),
(3266, '00:0C:DC', 'BECS Technology, Inc'),
(3267, '00:0C:DD', 'AOS Technologies AG'),
(3268, '00:0C:DE', 'ABB STOTZ-KONTAKT GmbH'),
(3269, '00:0C:DF', 'PULNiX America, Inc'),
(3270, '00:0C:E0', 'Trek Diagnostics Inc.'),
(3271, '00:0C:E1', 'The Open Group'),
(3272, '00:0C:E2', 'Rolls-Royce'),
(3273, '00:0C:E3', 'Option International N.V.'),
(3274, '00:0C:E4', 'NeuroCom International, Inc.'),
(3275, '00:0C:E5', 'ARRIS Group, Inc.'),
(3276, '00:0C:E6', 'Meru Networks Inc'),
(3277, '00:0C:E7', 'MediaTek Inc.'),
(3278, '00:0C:E8', 'GuangZhou AnJuBao Co., Ltd'),
(3279, '00:0C:E9', 'BLOOMBERG L.P.'),
(3280, '00:0C:EA', 'aphona Kommunikationssysteme'),
(3281, '00:0C:EB', 'CNMP Networks, Inc.'),
(3282, '00:0C:EC', 'Spectracom Corp.'),
(3283, '00:0C:ED', 'Real Digital Media'),
(3284, '00:0C:EE', 'jp-embedded'),
(3285, '00:0C:EF', 'Open Networks Engineering Ltd'),
(3286, '00:0C:F0', 'M &amp; N GmbH'),
(3287, '00:0C:F1', 'Intel Corporation'),
(3288, '00:0C:F2', 'GAMESA E&oacute;lica'),
(3289, '00:0C:F3', 'CALL IMAGE SA'),
(3290, '00:0C:F4', 'AKATSUKI ELECTRIC MFG.CO.,LTD.'),
(3291, '00:0C:F5', 'InfoExpress'),
(3292, '00:0C:F6', 'Sitecom Europe BV'),
(3293, '00:0C:F7', 'Nortel Networks'),
(3294, '00:0C:F8', 'Nortel Networks'),
(3295, '00:0C:F9', 'Xylem Water Solutions'),
(3296, '00:0C:FA', 'Digital Systems Corp'),
(3297, '00:0C:FB', 'Korea Network Systems'),
(3298, '00:0C:FC', 'S2io Technologies Corp'),
(3299, '00:0C:FD', 'Hyundai ImageQuest Co.,Ltd.'),
(3300, '00:0C:FE', 'Grand Electronic Co., Ltd'),
(3301, '00:0C:FF', 'MRO-TEK LIMITED'),
(3302, '00:0D:00', 'Seaway Networks Inc.'),
(3303, '00:0D:01', 'P&amp;E Microcomputer Systems, Inc.'),
(3304, '00:0D:02', 'NEC Platforms, Ltd.'),
(3305, '00:0D:03', 'Matrics, Inc.'),
(3306, '00:0D:04', 'Foxboro Eckardt Development GmbH'),
(3307, '00:0D:05', 'cybernet manufacturing inc.'),
(3308, '00:0D:06', 'Compulogic Limited'),
(3309, '00:0D:07', 'Calrec Audio Ltd'),
(3310, '00:0D:08', 'AboveCable, Inc.'),
(3311, '00:0D:09', 'Yuehua(Zhuhai) Electronic CO. LTD'),
(3312, '00:0D:0A', 'Projectiondesign as'),
(3313, '00:0D:0B', 'Buffalo Inc.'),
(3314, '00:0D:0C', 'MDI Security Systems'),
(3315, '00:0D:0D', 'ITSupported, LLC'),
(3316, '00:0D:0E', 'Inqnet Systems, Inc.'),
(3317, '00:0D:0F', 'Finlux Ltd'),
(3318, '00:0D:10', 'Embedtronics Oy'),
(3319, '00:0D:11', 'DENTSPLY - Gendex'),
(3320, '00:0D:12', 'AXELL Corporation'),
(3321, '00:0D:13', 'Wilhelm Rutenbeck GmbH&amp;Co.KG'),
(3322, '00:0D:14', 'Vtech Innovation LP dba Advanced American Telephones'),
(3323, '00:0D:15', 'Voipac s.r.o.'),
(3324, '00:0D:16', 'UHS Systems Pty Ltd'),
(3325, '00:0D:17', 'Turbo Networks Co.Ltd'),
(3326, '00:0D:18', 'Mega-Trend Electronics CO., LTD.'),
(3327, '00:0D:19', 'ROBE Show lighting'),
(3328, '00:0D:1A', 'Mustek System Inc.'),
(3329, '00:0D:1B', 'Kyoto Electronics Manufacturing Co., Ltd.'),
(3330, '00:0D:1C', 'Amesys Defense'),
(3331, '00:0D:1D', 'HIGH-TEK HARNESS ENT. CO., LTD.'),
(3332, '00:0D:1E', 'Control Techniques'),
(3333, '00:0D:1F', 'AV Digital'),
(3334, '00:0D:20', 'ASAHIKASEI TECHNOSYSTEM CO.,LTD.'),
(3335, '00:0D:21', 'WISCORE Inc.'),
(3336, '00:0D:22', 'Unitronics LTD'),
(3337, '00:0D:23', 'Smart Solution, Inc'),
(3338, '00:0D:24', 'SENTEC E&amp;E CO., LTD.'),
(3339, '00:0D:25', 'SANDEN CORPORATION'),
(3340, '00:0D:26', 'Primagraphics Limited'),
(3341, '00:0D:27', 'MICROPLEX Printware AG'),
(3342, '00:0D:28', 'CISCO SYSTEMS, INC.'),
(3343, '00:0D:29', 'CISCO SYSTEMS, INC.'),
(3344, '00:0D:2A', 'Scanmatic AS'),
(3345, '00:0D:2B', 'Racal Instruments'),
(3346, '00:0D:2C', 'Patapsco Designs Ltd'),
(3347, '00:0D:2D', 'NCT Deutschland GmbH'),
(3348, '00:0D:2E', 'Matsushita Avionics Systems Corporation'),
(3349, '00:0D:2F', 'AIN Comm.Tech.Co., LTD'),
(3350, '00:0D:30', 'IceFyre Semiconductor'),
(3351, '00:0D:31', 'Compellent Technologies, Inc.'),
(3352, '00:0D:32', 'DispenseSource, Inc.'),
(3353, '00:0D:33', 'Prediwave Corp.'),
(3354, '00:0D:34', 'Shell International Exploration and Production, Inc.'),
(3355, '00:0D:35', 'PAC International Ltd'),
(3356, '00:0D:36', 'Wu Han Routon Electronic Co., Ltd'),
(3357, '00:0D:37', 'WIPLUG'),
(3358, '00:0D:38', 'NISSIN INC.'),
(3359, '00:0D:39', 'Network Electronics'),
(3360, '00:0D:3A', 'Microsoft Corp.'),
(3361, '00:0D:3B', 'Microelectronics Technology Inc.'),
(3362, '00:0D:3C', 'i.Tech Dynamic Ltd'),
(3363, '00:0D:3D', 'Hammerhead Systems, Inc.'),
(3364, '00:0D:3E', 'APLUX Communications Ltd.'),
(3365, '00:0D:3F', 'VTI Instruments Corporation'),
(3366, '00:0D:40', 'Verint Loronix Video Solutions'),
(3367, '00:0D:41', 'Siemens AG ICM MP UC RD IT KLF1'),
(3368, '00:0D:42', 'Newbest Development Limited'),
(3369, '00:0D:43', 'DRS Tactical Systems Inc.'),
(3370, '00:0D:44', 'Audio BU - Logitech'),
(3371, '00:0D:45', 'Tottori SANYO Electric Co., Ltd.'),
(3372, '00:0D:46', 'Parker SSD Drives'),
(3373, '00:0D:47', 'Collex'),
(3374, '00:0D:48', 'AEWIN Technologies Co., Ltd.'),
(3375, '00:0D:49', 'Triton Systems of Delaware, Inc.'),
(3376, '00:0D:4A', 'Steag ETA-Optik'),
(3377, '00:0D:4B', 'Roku, LLC'),
(3378, '00:0D:4C', 'Outline Electronics Ltd.'),
(3379, '00:0D:4D', 'Ninelanes'),
(3380, '00:0D:4E', 'NDR Co.,LTD.'),
(3381, '00:0D:4F', 'Kenwood Corporation'),
(3382, '00:0D:50', 'Galazar Networks'),
(3383, '00:0D:51', 'DIVR Systems, Inc.'),
(3384, '00:0D:52', 'Comart system'),
(3385, '00:0D:53', 'Beijing 5w Communication Corp.'),
(3386, '00:0D:54', '3Com Ltd'),
(3387, '00:0D:55', 'SANYCOM Technology Co.,Ltd'),
(3388, '00:0D:56', 'Dell Inc'),
(3389, '00:0D:57', 'Fujitsu I-Network Systems Limited.'),
(3390, '00:0D:58', 'PRIVATE'),
(3391, '00:0D:59', 'Amity Systems, Inc.'),
(3392, '00:0D:5A', 'Tiesse SpA'),
(3393, '00:0D:5B', 'Smart Empire Investments Limited'),
(3394, '00:0D:5C', 'Robert Bosch GmbH, VT-ATMO'),
(3395, '00:0D:5D', 'Raritan Computer, Inc'),
(3396, '00:0D:5E', 'NEC Personal Products'),
(3397, '00:0D:5F', 'Minds Inc'),
(3398, '00:0D:60', 'IBM Corp'),
(3399, '00:0D:61', 'Giga-Byte Technology Co., Ltd.'),
(3400, '00:0D:62', 'Funkwerk Dabendorf GmbH'),
(3401, '00:0D:63', 'DENT Instruments, Inc.'),
(3402, '00:0D:64', 'COMAG Handels AG'),
(3403, '00:0D:65', 'CISCO SYSTEMS, INC.'),
(3404, '00:0D:66', 'CISCO SYSTEMS, INC.'),
(3405, '00:0D:67', 'Ericsson'),
(3406, '00:0D:68', 'Vinci Systems, Inc.'),
(3407, '00:0D:69', 'TMT&amp;D Corporation'),
(3408, '00:0D:6A', 'Redwood Technologies LTD'),
(3409, '00:0D:6B', 'Mita-Teknik A/S'),
(3410, '00:0D:6C', 'M-Audio'),
(3411, '00:0D:6D', 'K-Tech Devices Corp.'),
(3412, '00:0D:6E', 'K-Patents Oy'),
(3413, '00:0D:6F', 'Ember Corporation'),
(3414, '00:0D:70', 'Datamax Corporation'),
(3415, '00:0D:71', 'boca systems'),
(3416, '00:0D:72', '2Wire, Inc'),
(3417, '00:0D:73', 'Technical Support, Inc.'),
(3418, '00:0D:74', 'Sand Network Systems, Inc.'),
(3419, '00:0D:75', 'Kobian Pte Ltd - Taiwan Branch'),
(3420, '00:0D:76', 'Hokuto Denshi Co,. Ltd.'),
(3421, '00:0D:77', 'FalconStor Software'),
(3422, '00:0D:78', 'Engineering &amp; Security'),
(3423, '00:0D:79', 'Dynamic Solutions Co,.Ltd.'),
(3424, '00:0D:7A', 'DiGATTO Asia Pacific Pte Ltd'),
(3425, '00:0D:7B', 'Consensys Computers Inc.'),
(3426, '00:0D:7C', 'Codian Ltd'),
(3427, '00:0D:7D', 'Afco Systems'),
(3428, '00:0D:7E', 'Axiowave Networks, Inc.'),
(3429, '00:0D:7F', 'MIDAS  COMMUNICATION TECHNOLOGIES PTE LTD ( Foreign Branch)'),
(3430, '00:0D:80', 'Online Development Inc'),
(3431, '00:0D:81', 'Pepperl+Fuchs GmbH'),
(3432, '00:0D:82', 'PHS srl'),
(3433, '00:0D:83', 'Sanmina-SCI Hungary  Ltd.'),
(3434, '00:0D:84', 'Makus Inc.'),
(3435, '00:0D:85', 'Tapwave, Inc.'),
(3436, '00:0D:86', 'Huber + Suhner AG'),
(3437, '00:0D:87', 'Elitegroup Computer System Co. (ECS)'),
(3438, '00:0D:88', 'D-Link Corporation'),
(3439, '00:0D:89', 'Bils Technology Inc'),
(3440, '00:0D:8A', 'Winners Electronics Co., Ltd.'),
(3441, '00:0D:8B', 'T&amp;D Corporation'),
(3442, '00:0D:8C', 'Shanghai Wedone Digital Ltd. CO.'),
(3443, '00:0D:8D', 'Prosoft Technology, Inc'),
(3444, '00:0D:8E', 'Koden Electronics Co., Ltd.'),
(3445, '00:0D:8F', 'King Tsushin Kogyo Co., LTD.'),
(3446, '00:0D:90', 'Factum Electronics AB'),
(3447, '00:0D:91', 'Eclipse (HQ Espana) S.L.'),
(3448, '00:0D:92', 'Arima Communication Corporation'),
(3449, '00:0D:93', 'Apple'),
(3450, '00:0D:94', 'AFAR Communications,Inc'),
(3451, '00:0D:95', 'Opti-cell, Inc.'),
(3452, '00:0D:96', 'Vtera Technology Inc.'),
(3453, '00:0D:97', 'Tropos Networks, Inc.'),
(3454, '00:0D:98', 'S.W.A.C. Schmitt-Walter Automation Consult GmbH'),
(3455, '00:0D:99', 'Orbital Sciences Corp.; Launch Systems Group'),
(3456, '00:0D:9A', 'INFOTEC LTD'),
(3457, '00:0D:9B', 'Heraeus Electro-Nite International N.V.'),
(3458, '00:0D:9C', 'Elan GmbH &amp; Co KG'),
(3459, '00:0D:9D', 'Hewlett-Packard Company'),
(3460, '00:0D:9E', 'TOKUDEN OHIZUMI SEISAKUSYO Co.,Ltd.'),
(3461, '00:0D:9F', 'RF Micro Devices'),
(3462, '00:0D:A0', 'NEDAP N.V.'),
(3463, '00:0D:A1', 'MIRAE ITS Co.,LTD.'),
(3464, '00:0D:A2', 'Infrant Technologies, Inc.'),
(3465, '00:0D:A3', 'Emerging Technologies Limited'),
(3466, '00:0D:A4', 'DOSCH &amp; AMAND SYSTEMS AG'),
(3467, '00:0D:A5', 'Fabric7 Systems, Inc'),
(3468, '00:0D:A6', 'Universal Switching Corporation'),
(3469, '00:0D:A7', 'PRIVATE'),
(3470, '00:0D:A8', 'Teletronics Technology Corporation'),
(3471, '00:0D:A9', 'T.E.A.M. S.L.'),
(3472, '00:0D:AA', 'S.A.Tehnology co.,Ltd.'),
(3473, '00:0D:AB', 'Parker Hannifin GmbH Electromechanical Division Europe'),
(3474, '00:0D:AC', 'Japan CBM Corporation'),
(3475, '00:0D:AD', 'Dataprobe, Inc.'),
(3476, '00:0D:AE', 'SAMSUNG HEAVY INDUSTRIES CO., LTD.'),
(3477, '00:0D:AF', 'Plexus Corp (UK) Ltd'),
(3478, '00:0D:B0', 'Olym-tech Co.,Ltd.'),
(3479, '00:0D:B1', 'Japan Network Service Co., Ltd.'),
(3480, '00:0D:B2', 'Ammasso, Inc.'),
(3481, '00:0D:B3', 'SDO Communication Corperation'),
(3482, '00:0D:B4', 'NETASQ'),
(3483, '00:0D:B5', 'GLOBALSAT TECHNOLOGY CORPORATION'),
(3484, '00:0D:B6', 'Broadcom Corporation'),
(3485, '00:0D:B7', 'SANKO ELECTRIC CO,.LTD'),
(3486, '00:0D:B8', 'SCHILLER AG'),
(3487, '00:0D:B9', 'PC Engines GmbH'),
(3488, '00:0D:BA', 'Oc&eacute; Document Technologies GmbH'),
(3489, '00:0D:BB', 'Nippon Dentsu Co.,Ltd.'),
(3490, '00:0D:BC', 'CISCO SYSTEMS, INC.'),
(3491, '00:0D:BD', 'CISCO SYSTEMS, INC.'),
(3492, '00:0D:BE', 'Bel Fuse Europe Ltd.,UK'),
(3493, '00:0D:BF', 'TekTone Sound &amp; Signal Mfg., Inc.'),
(3494, '00:0D:C0', 'Spagat AS'),
(3495, '00:0D:C1', 'SafeWeb Inc'),
(3496, '00:0D:C2', 'PRIVATE'),
(3497, '00:0D:C3', 'First Communication, Inc.'),
(3498, '00:0D:C4', 'Emcore Corporation'),
(3499, '00:0D:C5', 'EchoStar Global B.V.'),
(3500, '00:0D:C6', 'DigiRose Technology Co., Ltd.'),
(3501, '00:0D:C7', 'COSMIC ENGINEERING INC.'),
(3502, '00:0D:C8', 'AirMagnet, Inc'),
(3503, '00:0D:C9', 'THALES Elektronik Systeme GmbH'),
(3504, '00:0D:CA', 'Tait Electronics'),
(3505, '00:0D:CB', 'Petcomkorea Co., Ltd.'),
(3506, '00:0D:CC', 'NEOSMART Corp.'),
(3507, '00:0D:CD', 'GROUPE TXCOM'),
(3508, '00:0D:CE', 'Dynavac Technology Pte Ltd'),
(3509, '00:0D:CF', 'Cidra Corp.'),
(3510, '00:0D:D0', 'TetraTec Instruments GmbH'),
(3511, '00:0D:D1', 'Stryker Corporation'),
(3512, '00:0D:D2', 'Simrad Optronics ASA'),
(3513, '00:0D:D3', 'SAMWOO Telecommunication Co.,Ltd.'),
(3514, '00:0D:D4', 'Symantec Corporation'),
(3515, '00:0D:D5', 'O\'RITE TECHNOLOGY CO.,LTD'),
(3516, '00:0D:D6', 'ITI    LTD'),
(3517, '00:0D:D7', 'Bright'),
(3518, '00:0D:D8', 'BBN'),
(3519, '00:0D:D9', 'Anton Paar GmbH'),
(3520, '00:0D:DA', 'ALLIED TELESIS K.K.'),
(3521, '00:0D:DB', 'AIRWAVE TECHNOLOGIES INC.'),
(3522, '00:0D:DC', 'VAC'),
(3523, '00:0D:DD', 'Profilo Telra Elektronik Sanayi ve Ticaret. A.Å'),
(3524, '00:0D:DE', 'Joyteck Co., Ltd.'),
(3525, '00:0D:DF', 'Japan Image &amp; Network Inc.'),
(3526, '00:0D:E0', 'ICPDAS Co.,LTD'),
(3527, '00:0D:E1', 'Control Products, Inc.'),
(3528, '00:0D:E2', 'CMZ Sistemi Elettronici'),
(3529, '00:0D:E3', 'AT Sweden AB'),
(3530, '00:0D:E4', 'DIGINICS, Inc.'),
(3531, '00:0D:E5', 'Samsung Thales'),
(3532, '00:0D:E6', 'YOUNGBO ENGINEERING CO.,LTD'),
(3533, '00:0D:E7', 'Snap-on OEM Group'),
(3534, '00:0D:E8', 'Nasaco Electronics Pte. Ltd'),
(3535, '00:0D:E9', 'Napatech Aps'),
(3536, '00:0D:EA', 'Kingtel Telecommunication Corp.'),
(3537, '00:0D:EB', 'CompXs Limited'),
(3538, '00:0D:EC', 'CISCO SYSTEMS, INC.'),
(3539, '00:0D:ED', 'CISCO SYSTEMS, INC.'),
(3540, '00:0D:EE', 'Andrew RF Power Amplifier Group'),
(3541, '00:0D:EF', 'Soc. Coop. Bilanciai'),
(3542, '00:0D:F0', 'QCOM TECHNOLOGY INC.'),
(3543, '00:0D:F1', 'IONIX INC.'),
(3544, '00:0D:F2', 'PRIVATE'),
(3545, '00:0D:F3', 'Asmax Solutions'),
(3546, '00:0D:F4', 'Watertek Co.'),
(3547, '00:0D:F5', 'Teletronics International Inc.'),
(3548, '00:0D:F6', 'Technology Thesaurus Corp.'),
(3549, '00:0D:F7', 'Space Dynamics Lab'),
(3550, '00:0D:F8', 'ORGA Kartensysteme GmbH'),
(3551, '00:0D:F9', 'NDS Limited'),
(3552, '00:0D:FA', 'Micro Control Systems Ltd.'),
(3553, '00:0D:FB', 'Komax AG'),
(3554, '00:0D:FC', 'ITFOR Inc.'),
(3555, '00:0D:FD', 'Huges Hi-Tech Inc.,'),
(3556, '00:0D:FE', 'Hauppauge Computer Works, Inc.'),
(3557, '00:0D:FF', 'CHENMING MOLD INDUSTRY CORP.'),
(3558, '00:0E:00', 'Atrie'),
(3559, '00:0E:01', 'ASIP Technologies Inc.'),
(3560, '00:0E:02', 'Advantech AMT Inc.'),
(3561, '00:0E:03', 'Emulex Corporation'),
(3562, '00:0E:04', 'CMA/Microdialysis AB'),
(3563, '00:0E:05', 'WIRELESS MATRIX CORP.'),
(3564, '00:0E:06', 'Team Simoco Ltd'),
(3565, '00:0E:07', 'Sony Ericsson Mobile Communications AB'),
(3566, '00:0E:08', 'Cisco Linksys LLC'),
(3567, '00:0E:09', 'Shenzhen Coship Software Co.,LTD.'),
(3568, '00:0E:0A', 'SAKUMA DESIGN OFFICE'),
(3569, '00:0E:0B', 'Netac Technology Co., Ltd.'),
(3570, '00:0E:0C', 'Intel Corporation'),
(3571, '00:0E:0D', 'Hesch Schr&ouml;der GmbH'),
(3572, '00:0E:0E', 'ESA elettronica S.P.A.'),
(3573, '00:0E:0F', 'ERMME'),
(3574, '00:0E:10', 'C-guys, Inc.'),
(3575, '00:0E:11', 'BDT B&uuml;ro und Datentechnik GmbH &amp; Co.KG'),
(3576, '00:0E:12', 'Adaptive Micro Systems Inc.'),
(3577, '00:0E:13', 'Accu-Sort Systems inc.'),
(3578, '00:0E:14', 'Visionary Solutions, Inc.'),
(3579, '00:0E:15', 'Tadlys LTD'),
(3580, '00:0E:16', 'SouthWing S.L.'),
(3581, '00:0E:17', 'PRIVATE'),
(3582, '00:0E:18', 'MyA Technology'),
(3583, '00:0E:19', 'LogicaCMG Pty Ltd'),
(3584, '00:0E:1A', 'JPS Communications'),
(3585, '00:0E:1B', 'IAV GmbH'),
(3586, '00:0E:1C', 'Hach Company'),
(3587, '00:0E:1D', 'ARION Technology Inc.'),
(3588, '00:0E:1E', 'QLogic Corporation'),
(3589, '00:0E:1F', 'TCL Networks Equipment Co., Ltd.'),
(3590, '00:0E:20', 'ACCESS Systems Americas, Inc.'),
(3591, '00:0E:21', 'MTU Friedrichshafen GmbH'),
(3592, '00:0E:22', 'PRIVATE'),
(3593, '00:0E:23', 'Incipient, Inc.'),
(3594, '00:0E:24', 'Huwell Technology Inc.'),
(3595, '00:0E:25', 'Hannae Technology Co., Ltd'),
(3596, '00:0E:26', 'Gincom Technology Corp.'),
(3597, '00:0E:27', 'Crere Networks, Inc.'),
(3598, '00:0E:28', 'Dynamic Ratings P/L'),
(3599, '00:0E:29', 'Shester Communications Inc'),
(3600, '00:0E:2A', 'PRIVATE'),
(3601, '00:0E:2B', 'Safari Technologies'),
(3602, '00:0E:2C', 'Netcodec co.'),
(3603, '00:0E:2D', 'Hyundai Digital Technology Co.,Ltd.'),
(3604, '00:0E:2E', 'Edimax Technology Co., Ltd.'),
(3605, '00:0E:2F', 'Roche Diagnostics GmbH'),
(3606, '00:0E:30', 'AERAS Networks, Inc.'),
(3607, '00:0E:31', 'Olympus Soft Imaging Solutions GmbH'),
(3608, '00:0E:32', 'Kontron Medical'),
(3609, '00:0E:33', 'Shuko Electronics Co.,Ltd'),
(3610, '00:0E:34', 'NexGen City, LP'),
(3611, '00:0E:35', 'Intel Corp'),
(3612, '00:0E:36', 'HEINESYS, Inc.'),
(3613, '00:0E:37', 'Harms &amp; Wende GmbH &amp; Co.KG'),
(3614, '00:0E:38', 'CISCO SYSTEMS, INC.'),
(3615, '00:0E:39', 'CISCO SYSTEMS, INC.'),
(3616, '00:0E:3A', 'Cirrus Logic'),
(3617, '00:0E:3B', 'Hawking Technologies, Inc.'),
(3618, '00:0E:3C', 'Transact Technologies Inc'),
(3619, '00:0E:3D', 'Televic N.V.'),
(3620, '00:0E:3E', 'Sun Optronics Inc'),
(3621, '00:0E:3F', 'Soronti, Inc.'),
(3622, '00:0E:40', 'Nortel Networks'),
(3623, '00:0E:41', 'NIHON MECHATRONICS CO.,LTD.'),
(3624, '00:0E:42', 'Motic Incoporation Ltd.'),
(3625, '00:0E:43', 'G-Tek Electronics Sdn. Bhd.'),
(3626, '00:0E:44', 'Digital 5, Inc.'),
(3627, '00:0E:45', 'Beijing Newtry Electronic Technology Ltd'),
(3628, '00:0E:46', 'Niigata Seimitsu Co.,Ltd.'),
(3629, '00:0E:47', 'NCI System Co.,Ltd.'),
(3630, '00:0E:48', 'Lipman TransAction Solutions'),
(3631, '00:0E:49', 'Forsway Scandinavia AB'),
(3632, '00:0E:4A', 'Changchun Huayu WEBPAD Co.,LTD'),
(3633, '00:0E:4B', 'atrium c and i'),
(3634, '00:0E:4C', 'Bermai Inc.'),
(3635, '00:0E:4D', 'Numesa Inc.'),
(3636, '00:0E:4E', 'Waveplus Technology Co., Ltd.'),
(3637, '00:0E:4F', 'Trajet GmbH'),
(3638, '00:0E:50', 'Thomson Telecom Belgium'),
(3639, '00:0E:51', 'tecna elettronica srl'),
(3640, '00:0E:52', 'Optium Corporation'),
(3641, '00:0E:53', 'AV TECH CORPORATION'),
(3642, '00:0E:54', 'AlphaCell Wireless Ltd.'),
(3643, '00:0E:55', 'AUVITRAN'),
(3644, '00:0E:56', '4G Systems GmbH &amp; Co. KG'),
(3645, '00:0E:57', 'Iworld Networking, Inc.'),
(3646, '00:0E:58', 'Sonos, Inc.'),
(3647, '00:0E:59', 'SAGEM SA'),
(3648, '00:0E:5A', 'TELEFIELD inc.'),
(3649, '00:0E:5B', 'ParkerVision - Direct2Data'),
(3650, '00:0E:5C', 'ARRIS Group, Inc.'),
(3651, '00:0E:5D', 'Triple Play Technologies A/S'),
(3652, '00:0E:5E', 'Raisecom Technology'),
(3653, '00:0E:5F', 'activ-net GmbH &amp; Co. KG'),
(3654, '00:0E:60', '360SUN Digital Broadband Corporation'),
(3655, '00:0E:61', 'MICROTROL LIMITED'),
(3656, '00:0E:62', 'Nortel Networks'),
(3657, '00:0E:63', 'Lemke Diagnostics GmbH'),
(3658, '00:0E:64', 'Elphel, Inc'),
(3659, '00:0E:65', 'TransCore'),
(3660, '00:0E:66', 'Hitachi Industry &amp; Control Solutions, Ltd.'),
(3661, '00:0E:67', 'Eltis Microelectronics Ltd.'),
(3662, '00:0E:68', 'E-TOP Network Technology Inc.'),
(3663, '00:0E:69', 'China Electric Power Research Institute'),
(3664, '00:0E:6A', '3Com Ltd'),
(3665, '00:0E:6B', 'Janitza electronics GmbH'),
(3666, '00:0E:6C', 'Device Drivers Limited'),
(3667, '00:0E:6D', 'Murata Manufacturing Co., Ltd.'),
(3668, '00:0E:6E', 'MAT S.A. (Mircrelec Advanced Technology)'),
(3669, '00:0E:6F', 'IRIS Corporation Berhad'),
(3670, '00:0E:70', 'in2 Networks'),
(3671, '00:0E:71', 'Gemstar Technology Development Ltd.'),
(3672, '00:0E:72', 'CTS electronics'),
(3673, '00:0E:73', 'Tpack A/S'),
(3674, '00:0E:74', 'Solar Telecom. Tech'),
(3675, '00:0E:75', 'New York Air Brake Corp.'),
(3676, '00:0E:76', 'GEMSOC INNOVISION INC.'),
(3677, '00:0E:77', 'Decru, Inc.'),
(3678, '00:0E:78', 'Amtelco'),
(3679, '00:0E:79', 'Ample Communications Inc.'),
(3680, '00:0E:7A', 'GemWon Communications Co., Ltd.'),
(3681, '00:0E:7B', 'Toshiba'),
(3682, '00:0E:7C', 'Televes S.A.'),
(3683, '00:0E:7D', 'Electronics Line 3000 Ltd.'),
(3684, '00:0E:7E', 'ionSign Oy'),
(3685, '00:0E:7F', 'Hewlett-Packard Company'),
(3686, '00:0E:80', 'Thomson Technology Inc'),
(3687, '00:0E:81', 'Devicescape Software, Inc.'),
(3688, '00:0E:82', 'Commtech Wireless'),
(3689, '00:0E:83', 'CISCO SYSTEMS, INC.'),
(3690, '00:0E:84', 'CISCO SYSTEMS, INC.'),
(3691, '00:0E:85', 'Catalyst Enterprises, Inc.'),
(3692, '00:0E:86', 'Alcatel North America'),
(3693, '00:0E:87', 'adp Gauselmann GmbH'),
(3694, '00:0E:88', 'VIDEOTRON CORP.'),
(3695, '00:0E:89', 'CLEMATIC'),
(3696, '00:0E:8A', 'Avara Technologies Pty. Ltd.'),
(3697, '00:0E:8B', 'Astarte Technology Co, Ltd.'),
(3698, '00:0E:8C', 'Siemens AG A&amp;D ET'),
(3699, '00:0E:8D', 'Systems in Progress Holding GmbH'),
(3700, '00:0E:8E', 'SparkLAN Communications, Inc.'),
(3701, '00:0E:8F', 'Sercomm Corp.'),
(3702, '00:0E:90', 'PONICO CORP.'),
(3703, '00:0E:91', 'Navico Auckland Ltd'),
(3704, '00:0E:92', 'Open Telecom'),
(3705, '00:0E:93', 'Mil&eacute;nio 3 Sistemas Electr&oacute;nicos, Lda.'),
(3706, '00:0E:94', 'Maas International BV'),
(3707, '00:0E:95', 'Fujiya Denki Seisakusho Co.,Ltd.'),
(3708, '00:0E:96', 'Cubic Defense Applications, Inc.'),
(3709, '00:0E:97', 'Ultracker Technology CO., Inc'),
(3710, '00:0E:98', 'HME Clear-Com LTD.'),
(3711, '00:0E:99', 'Spectrum Digital, Inc'),
(3712, '00:0E:9A', 'BOE TECHNOLOGY GROUP CO.,LTD'),
(3713, '00:0E:9B', 'Ambit Microsystems Corporation'),
(3714, '00:0E:9C', 'Benchmark Electronics'),
(3715, '00:0E:9D', 'Tiscali UK Ltd'),
(3716, '00:0E:9E', 'Topfield Co., Ltd'),
(3717, '00:0E:9F', 'TEMIC SDS GmbH'),
(3718, '00:0E:A0', 'NetKlass Technology Inc.'),
(3719, '00:0E:A1', 'Formosa Teletek Corporation'),
(3720, '00:0E:A2', 'McAfee, Inc'),
(3721, '00:0E:A3', 'CNCR-IT CO.,LTD,HangZhou P.R.CHINA'),
(3722, '00:0E:A4', 'Certance Inc.'),
(3723, '00:0E:A5', 'BLIP Systems'),
(3724, '00:0E:A6', 'ASUSTEK COMPUTER INC.'),
(3725, '00:0E:A7', 'Endace Technology'),
(3726, '00:0E:A8', 'United Technologists Europe Limited'),
(3727, '00:0E:A9', 'Shanghai Xun Shi Communications Equipment Ltd. Co.'),
(3728, '00:0E:AA', 'Scalent Systems, Inc.'),
(3729, '00:0E:AB', 'Cray Inc'),
(3730, '00:0E:AC', 'MINTRON ENTERPRISE CO., LTD.'),
(3731, '00:0E:AD', 'Metanoia Technologies, Inc.'),
(3732, '00:0E:AE', 'GAWELL TECHNOLOGIES CORP.'),
(3733, '00:0E:AF', 'CASTEL'),
(3734, '00:0E:B0', 'Solutions Radio BV'),
(3735, '00:0E:B1', 'Newcotech,Ltd'),
(3736, '00:0E:B2', 'Micro-Research Finland Oy'),
(3737, '00:0E:B3', 'Hewlett-Packard'),
(3738, '00:0E:B4', 'GUANGZHOU GAOKE COMMUNICATIONS TECHNOLOGY CO.LTD.'),
(3739, '00:0E:B5', 'Ecastle Electronics Co., Ltd.'),
(3740, '00:0E:B6', 'Riverbed Technology, Inc.'),
(3741, '00:0E:B7', 'Knovative, Inc.'),
(3742, '00:0E:B8', 'Iiga co.,Ltd'),
(3743, '00:0E:B9', 'HASHIMOTO Electronics Industry Co.,Ltd.'),
(3744, '00:0E:BA', 'HANMI SEMICONDUCTOR CO., LTD.'),
(3745, '00:0E:BB', 'Everbee Networks'),
(3746, '00:0E:BC', 'Paragon Fidelity GmbH'),
(3747, '00:0E:BD', 'Burdick, a Quinton Compny'),
(3748, '00:0E:BE', 'B&amp;B Electronics Manufacturing Co.'),
(3749, '00:0E:BF', 'Remsdaq Limited'),
(3750, '00:0E:C0', 'Nortel Networks'),
(3751, '00:0E:C1', 'MYNAH Technologies'),
(3752, '00:0E:C2', 'Lowrance Electronics, Inc.'),
(3753, '00:0E:C3', 'Logic Controls, Inc.'),
(3754, '00:0E:C4', 'Iskra Transmission d.d.'),
(3755, '00:0E:C5', 'Digital Multitools Inc'),
(3756, '00:0E:C6', 'ASIX ELECTRONICS CORP.'),
(3757, '00:0E:C7', 'Motorola Korea'),
(3758, '00:0E:C8', 'Zoran Corporation'),
(3759, '00:0E:C9', 'YOKO Technology Corp.'),
(3760, '00:0E:CA', 'WTSS Inc'),
(3761, '00:0E:CB', 'VineSys Technology'),
(3762, '00:0E:CC', 'Tableau, LLC'),
(3763, '00:0E:CD', 'SKOV A/S'),
(3764, '00:0E:CE', 'S.I.T.T.I. S.p.A.'),
(3765, '00:0E:CF', 'PROFIBUS Nutzerorganisation e.V.'),
(3766, '00:0E:D0', 'Privaris, Inc.'),
(3767, '00:0E:D1', 'Osaka Micro Computer.'),
(3768, '00:0E:D2', 'Filtronic plc'),
(3769, '00:0E:D3', 'Epicenter, Inc.'),
(3770, '00:0E:D4', 'CRESITT INDUSTRIE'),
(3771, '00:0E:D5', 'COPAN Systems Inc.'),
(3772, '00:0E:D6', 'CISCO SYSTEMS, INC.'),
(3773, '00:0E:D7', 'CISCO SYSTEMS, INC.'),
(3774, '00:0E:D8', 'Aktino, Inc.'),
(3775, '00:0E:D9', 'Aksys, Ltd.'),
(3776, '00:0E:DA', 'C-TECH UNITED CORP.'),
(3777, '00:0E:DB', 'XiNCOM Corp.'),
(3778, '00:0E:DC', 'Tellion INC.'),
(3779, '00:0E:DD', 'SHURE INCORPORATED'),
(3780, '00:0E:DE', 'REMEC, Inc.'),
(3781, '00:0E:DF', 'PLX Technology'),
(3782, '00:0E:E0', 'Mcharge'),
(3783, '00:0E:E1', 'ExtremeSpeed Inc.'),
(3784, '00:0E:E2', 'Custom Engineering'),
(3785, '00:0E:E3', 'Chiyu Technology Co.,Ltd'),
(3786, '00:0E:E4', 'BOE TECHNOLOGY GROUP CO.,LTD'),
(3787, '00:0E:E5', 'bitWallet, Inc.'),
(3788, '00:0E:E6', 'Adimos Systems LTD'),
(3789, '00:0E:E7', 'AAC ELECTRONICS CORP.'),
(3790, '00:0E:E8', 'zioncom'),
(3791, '00:0E:E9', 'WayTech Development, Inc.'),
(3792, '00:0E:EA', 'Shadong Luneng Jicheng Electronics,Co.,Ltd'),
(3793, '00:0E:EB', 'Sandmartin(zhong shan)Electronics Co.,Ltd'),
(3794, '00:0E:EC', 'Orban'),
(3795, '00:0E:ED', 'Nokia Danmark A/S'),
(3796, '00:0E:EE', 'Muco Industrie BV'),
(3797, '00:0E:EF', 'PRIVATE'),
(3798, '00:0E:F0', 'Festo AG &amp; Co. KG'),
(3799, '00:0E:F1', 'EZQUEST INC.'),
(3800, '00:0E:F2', 'Infinico Corporation'),
(3801, '00:0E:F3', 'Smarthome'),
(3802, '00:0E:F4', 'Kasda Networks Inc'),
(3803, '00:0E:F5', 'iPAC Technology Co., Ltd.'),
(3804, '00:0E:F6', 'E-TEN Information Systems Co., Ltd.'),
(3805, '00:0E:F7', 'Vulcan Portals Inc'),
(3806, '00:0E:F8', 'SBC ASI'),
(3807, '00:0E:F9', 'REA Elektronik GmbH'),
(3808, '00:0E:FA', 'Optoway Technology Incorporation'),
(3809, '00:0E:FB', 'Macey Enterprises'),
(3810, '00:0E:FC', 'JTAG Technologies B.V.'),
(3811, '00:0E:FD', 'FUJINON CORPORATION'),
(3812, '00:0E:FE', 'EndRun Technologies LLC'),
(3813, '00:0E:FF', 'Megasolution,Inc.'),
(3814, '00:0F:00', 'Legra Systems, Inc.'),
(3815, '00:0F:01', 'DIGITALKS INC'),
(3816, '00:0F:02', 'Digicube Technology Co., Ltd'),
(3817, '00:0F:03', 'COM&amp;C CO., LTD'),
(3818, '00:0F:04', 'cim-usa inc'),
(3819, '00:0F:05', '3B SYSTEM INC.'),
(3820, '00:0F:06', 'Nortel Networks'),
(3821, '00:0F:07', 'Mangrove Systems, Inc.'),
(3822, '00:0F:08', 'Indagon Oy'),
(3823, '00:0F:09', 'PRIVATE'),
(3824, '00:0F:0A', 'Clear Edge Networks'),
(3825, '00:0F:0B', 'Kentima Technologies AB'),
(3826, '00:0F:0C', 'SYNCHRONIC ENGINEERING'),
(3827, '00:0F:0D', 'Hunt Electronic Co., Ltd.'),
(3828, '00:0F:0E', 'WaveSplitter Technologies, Inc.'),
(3829, '00:0F:0F', 'Real ID Technology Co., Ltd.'),
(3830, '00:0F:10', 'RDM Corporation'),
(3831, '00:0F:11', 'Prodrive B.V.'),
(3832, '00:0F:12', 'Panasonic Europe Ltd.'),
(3833, '00:0F:13', 'Nisca corporation'),
(3834, '00:0F:14', 'Mindray Co., Ltd.'),
(3835, '00:0F:15', 'Kjaerulff1 A/S'),
(3836, '00:0F:16', 'JAY HOW TECHNOLOGY CO.,'),
(3837, '00:0F:17', 'Insta Elektro GmbH'),
(3838, '00:0F:18', 'Industrial Control Systems'),
(3839, '00:0F:19', 'Boston Scientific'),
(3840, '00:0F:1A', 'Gaming Support B.V.'),
(3841, '00:0F:1B', 'Ego Systems Inc.'),
(3842, '00:0F:1C', 'DigitAll World Co., Ltd'),
(3843, '00:0F:1D', 'Cosmo Techs Co., Ltd.'),
(3844, '00:0F:1E', 'Chengdu KT Electric Co.of High &amp; New Technology'),
(3845, '00:0F:1F', 'Dell Inc'),
(3846, '00:0F:20', 'Hewlett-Packard Company'),
(3847, '00:0F:21', 'Scientific Atlanta, Inc'),
(3848, '00:0F:22', 'Helius, Inc.'),
(3849, '00:0F:23', 'CISCO SYSTEMS, INC.'),
(3850, '00:0F:24', 'CISCO SYSTEMS, INC.'),
(3851, '00:0F:25', 'AimValley B.V.'),
(3852, '00:0F:26', 'WorldAccxx  LLC'),
(3853, '00:0F:27', 'TEAL Electronics, Inc.'),
(3854, '00:0F:28', 'Itronix Corporation'),
(3855, '00:0F:29', 'Augmentix Corporation'),
(3856, '00:0F:2A', 'Cableware Electronics'),
(3857, '00:0F:2B', 'GREENBELL SYSTEMS'),
(3858, '00:0F:2C', 'Uplogix, Inc.'),
(3859, '00:0F:2D', 'CHUNG-HSIN ELECTRIC &amp; MACHINERY MFG.CORP.'),
(3860, '00:0F:2E', 'Megapower International Corp.'),
(3861, '00:0F:2F', 'W-LINX TECHNOLOGY CO., LTD.'),
(3862, '00:0F:30', 'Raza Microelectronics Inc'),
(3863, '00:0F:31', 'Allied Vision Technologies Canada Inc'),
(3864, '00:0F:32', 'Lootom Telcovideo Network Wuxi Co Ltd'),
(3865, '00:0F:33', 'DUALi Inc.'),
(3866, '00:0F:34', 'CISCO SYSTEMS, INC.'),
(3867, '00:0F:35', 'CISCO SYSTEMS, INC.'),
(3868, '00:0F:36', 'Accurate Techhnologies, Inc.'),
(3869, '00:0F:37', 'Xambala Incorporated'),
(3870, '00:0F:38', 'Netstar'),
(3871, '00:0F:39', 'IRIS SENSORS'),
(3872, '00:0F:3A', 'HISHARP'),
(3873, '00:0F:3B', 'Fuji System Machines Co., Ltd.'),
(3874, '00:0F:3C', 'Endeleo Limited'),
(3875, '00:0F:3D', 'D-Link Corporation'),
(3876, '00:0F:3E', 'CardioNet, Inc'),
(3877, '00:0F:3F', 'Big Bear Networks'),
(3878, '00:0F:40', 'Optical Internetworking Forum'),
(3879, '00:0F:41', 'Zipher Ltd'),
(3880, '00:0F:42', 'Xalyo Systems'),
(3881, '00:0F:43', 'Wasabi Systems Inc.'),
(3882, '00:0F:44', 'Tivella Inc.'),
(3883, '00:0F:45', 'Stretch, Inc.'),
(3884, '00:0F:46', 'SINAR AG'),
(3885, '00:0F:47', 'ROBOX SPA'),
(3886, '00:0F:48', 'Polypix Inc.'),
(3887, '00:0F:49', 'Northover Solutions Limited'),
(3888, '00:0F:4A', 'Kyushu-kyohan co.,ltd'),
(3889, '00:0F:4B', 'Oracle Corporation'),
(3890, '00:0F:4C', 'Elextech INC'),
(3891, '00:0F:4D', 'TalkSwitch'),
(3892, '00:0F:4E', 'Cellink'),
(3893, '00:0F:4F', 'Cadmus Technology Ltd'),
(3894, '00:0F:50', 'StreamScale Limited'),
(3895, '00:0F:51', 'Azul Systems, Inc.'),
(3896, '00:0F:52', 'YORK Refrigeration, Marine &amp; Controls'),
(3897, '00:0F:53', 'Solarflare Communications Inc'),
(3898, '00:0F:54', 'Entrelogic Corporation'),
(3899, '00:0F:55', 'Datawire Communication Networks Inc.'),
(3900, '00:0F:56', 'Continuum Photonics Inc'),
(3901, '00:0F:57', 'CABLELOGIC Co., Ltd.'),
(3902, '00:0F:58', 'Adder Technology Limited'),
(3903, '00:0F:59', 'Phonak Communications AG'),
(3904, '00:0F:5A', 'Peribit Networks'),
(3905, '00:0F:5B', 'Delta Information Systems, Inc.'),
(3906, '00:0F:5C', 'Day One Digital Media Limited'),
(3907, '00:0F:5D', 'Genexis BV'),
(3908, '00:0F:5E', 'Veo'),
(3909, '00:0F:5F', 'Nicety Technologies Inc. (NTS)'),
(3910, '00:0F:60', 'Lifetron Co.,Ltd'),
(3911, '00:0F:61', 'Hewlett-Packard Company'),
(3912, '00:0F:62', 'Alcatel Bell Space N.V.'),
(3913, '00:0F:63', 'Obzerv Technologies'),
(3914, '00:0F:64', 'D&amp;R Electronica Weesp BV'),
(3915, '00:0F:65', 'icube Corp.'),
(3916, '00:0F:66', 'Cisco-Linksys'),
(3917, '00:0F:67', 'West Instruments'),
(3918, '00:0F:68', 'Vavic Network Technology, Inc.'),
(3919, '00:0F:69', 'SEW Eurodrive GmbH &amp; Co. KG'),
(3920, '00:0F:6A', 'Nortel Networks'),
(3921, '00:0F:6B', 'GateWare Communications GmbH'),
(3922, '00:0F:6C', 'ADDI-DATA GmbH'),
(3923, '00:0F:6D', 'Midas Engineering'),
(3924, '00:0F:6E', 'BBox'),
(3925, '00:0F:6F', 'FTA Communication Technologies'),
(3926, '00:0F:70', 'Wintec Industries, inc.'),
(3927, '00:0F:71', 'Sanmei Electronics Co.,Ltd'),
(3928, '00:0F:72', 'Sandburst'),
(3929, '00:0F:73', 'RS Automation Co., Ltd'),
(3930, '00:0F:74', 'Qamcom Technology AB'),
(3931, '00:0F:75', 'First Silicon Solutions'),
(3932, '00:0F:76', 'Digital Keystone, Inc.'),
(3933, '00:0F:77', 'DENTUM CO.,LTD'),
(3934, '00:0F:78', 'Datacap Systems Inc'),
(3935, '00:0F:79', 'Bluetooth Interest Group Inc.'),
(3936, '00:0F:7A', 'BeiJing NuQX Technology CO.,LTD'),
(3937, '00:0F:7B', 'Arce Sistemas, S.A.'),
(3938, '00:0F:7C', 'ACTi Corporation'),
(3939, '00:0F:7D', 'Xirrus'),
(3940, '00:0F:7E', 'Ablerex Electronics Co., LTD'),
(3941, '00:0F:7F', 'UBSTORAGE Co.,Ltd.'),
(3942, '00:0F:80', 'Trinity Security Systems,Inc.'),
(3943, '00:0F:81', 'PAL Pacific Inc.'),
(3944, '00:0F:82', 'Mortara Instrument, Inc.'),
(3945, '00:0F:83', 'Brainium Technologies Inc.'),
(3946, '00:0F:84', 'Astute Networks, Inc.'),
(3947, '00:0F:85', 'ADDO-Japan Corporation'),
(3948, '00:0F:86', 'Research In Motion Limited'),
(3949, '00:0F:87', 'Maxcess International'),
(3950, '00:0F:88', 'AMETEK, Inc.'),
(3951, '00:0F:89', 'Winnertec System Co., Ltd.'),
(3952, '00:0F:8A', 'WideView'),
(3953, '00:0F:8B', 'Orion MultiSystems Inc'),
(3954, '00:0F:8C', 'Gigawavetech Pte Ltd'),
(3955, '00:0F:8D', 'FAST TV-Server AG'),
(3956, '00:0F:8E', 'DONGYANG TELECOM CO.,LTD.'),
(3957, '00:0F:8F', 'CISCO SYSTEMS, INC.'),
(3958, '00:0F:90', 'CISCO SYSTEMS, INC.'),
(3959, '00:0F:91', 'Aerotelecom Co.,Ltd.'),
(3960, '00:0F:92', 'Microhard Systems Inc.'),
(3961, '00:0F:93', 'Landis+Gyr Ltd.'),
(3962, '00:0F:94', 'Genexis BV'),
(3963, '00:0F:95', 'ELECOM Co.,LTD Laneed Division'),
(3964, '00:0F:96', 'Telco Systems, Inc.'),
(3965, '00:0F:97', 'Avanex Corporation'),
(3966, '00:0F:98', 'Avamax Co. Ltd.'),
(3967, '00:0F:99', 'APAC opto Electronics Inc.'),
(3968, '00:0F:9A', 'Synchrony, Inc.'),
(3969, '00:0F:9B', 'Ross Video Limited'),
(3970, '00:0F:9C', 'Panduit Corp'),
(3971, '00:0F:9D', 'DisplayLink (UK) Ltd'),
(3972, '00:0F:9E', 'Murrelektronik GmbH'),
(3973, '00:0F:9F', 'ARRIS Group, Inc.'),
(3974, '00:0F:A0', 'CANON KOREA BUSINESS SOLUTIONS INC.'),
(3975, '00:0F:A1', 'Gigabit Systems Inc.'),
(3976, '00:0F:A2', '2xWireless'),
(3977, '00:0F:A3', 'Alpha Networks Inc.'),
(3978, '00:0F:A4', 'Sprecher Automation GmbH'),
(3979, '00:0F:A5', 'BWA Technology GmbH'),
(3980, '00:0F:A6', 'S2 Security Corporation'),
(3981, '00:0F:A7', 'Raptor Networks Technology'),
(3982, '00:0F:A8', 'Photometrics, Inc.'),
(3983, '00:0F:A9', 'PC Fabrik'),
(3984, '00:0F:AA', 'Nexus Technologies'),
(3985, '00:0F:AB', 'Kyushu Electronics Systems Inc.'),
(3986, '00:0F:AC', 'IEEE 802.11'),
(3987, '00:0F:AD', 'FMN communications GmbH'),
(3988, '00:0F:AE', 'E2O Communications'),
(3989, '00:0F:AF', 'Dialog Inc.'),
(3990, '00:0F:B0', 'Compal Electronics,INC.'),
(3991, '00:0F:B1', 'Cognio Inc.'),
(3992, '00:0F:B2', 'Broadband Pacenet (India) Pvt. Ltd.'),
(3993, '00:0F:B3', 'Actiontec Electronics, Inc'),
(3994, '00:0F:B4', 'Timespace Technology'),
(3995, '00:0F:B5', 'NETGEAR Inc'),
(3996, '00:0F:B6', 'Europlex Technologies'),
(3997, '00:0F:B7', 'Cavium Networks'),
(3998, '00:0F:B8', 'CallURL Inc.'),
(3999, '00:0F:B9', 'Adaptive Instruments'),
(4000, '00:0F:BA', 'Tevebox AB'),
(4001, '00:0F:BB', 'Nokia Siemens Networks GmbH &amp; Co. KG.'),
(4002, '00:0F:BC', 'Onkey Technologies, Inc.'),
(4003, '00:0F:BD', 'MRV Communications (Networks) LTD'),
(4004, '00:0F:BE', 'e-w/you Inc.'),
(4005, '00:0F:BF', 'DGT Sp. z o.o.'),
(4006, '00:0F:C0', 'DELCOMp'),
(4007, '00:0F:C1', 'WAVE Corporation'),
(4008, '00:0F:C2', 'Uniwell Corporation'),
(4009, '00:0F:C3', 'PalmPalm Technology, Inc.'),
(4010, '00:0F:C4', 'NST co.,LTD.'),
(4011, '00:0F:C5', 'KeyMed Ltd'),
(4012, '00:0F:C6', 'Eurocom Industries A/S'),
(4013, '00:0F:C7', 'Dionica R&amp;D Ltd.'),
(4014, '00:0F:C8', 'Chantry Networks'),
(4015, '00:0F:C9', 'Allnet GmbH'),
(4016, '00:0F:CA', 'A-JIN TECHLINE CO, LTD'),
(4017, '00:0F:CB', '3Com Ltd'),
(4018, '00:0F:CC', 'ARRIS Group, Inc.'),
(4019, '00:0F:CD', 'Nortel Networks'),
(4020, '00:0F:CE', 'Kikusui Electronics Corp.'),
(4021, '00:0F:CF', 'Datawind Research'),
(4022, '00:0F:D0', 'ASTRI'),
(4023, '00:0F:D1', 'Applied Wireless Identifications Group, Inc.'),
(4024, '00:0F:D2', 'EWA Technologies, Inc.'),
(4025, '00:0F:D3', 'Digium'),
(4026, '00:0F:D4', 'Soundcraft'),
(4027, '00:0F:D5', 'Schwechat - RISE'),
(4028, '00:0F:D6', 'Sarotech Co., Ltd'),
(4029, '00:0F:D7', 'Harman Music Group'),
(4030, '00:0F:D8', 'Force, Inc.'),
(4031, '00:0F:D9', 'FlexDSL Telecommunications AG'),
(4032, '00:0F:DA', 'YAZAKI CORPORATION'),
(4033, '00:0F:DB', 'Westell Technologies'),
(4034, '00:0F:DC', 'Ueda Japan  Radio Co., Ltd.'),
(4035, '00:0F:DD', 'SORDIN AB'),
(4036, '00:0F:DE', 'Sony Ericsson Mobile Communications AB'),
(4037, '00:0F:DF', 'SOLOMON Technology Corp.'),
(4038, '00:0F:E0', 'NComputing Co.,Ltd.'),
(4039, '00:0F:E1', 'ID DIGITAL CORPORATION'),
(4040, '00:0F:E2', 'Hangzhou H3C Technologies Co., Ltd.'),
(4041, '00:0F:E3', 'Damm Cellular Systems A/S'),
(4042, '00:0F:E4', 'Pantech Co.,Ltd'),
(4043, '00:0F:E5', 'MERCURY SECURITY CORPORATION'),
(4044, '00:0F:E6', 'MBTech Systems, Inc.'),
(4045, '00:0F:E7', 'Lutron Electronics Co., Inc.'),
(4046, '00:0F:E8', 'Lobos, Inc.'),
(4047, '00:0F:E9', 'GW TECHNOLOGIES CO.,LTD.'),
(4048, '00:0F:EA', 'Giga-Byte Technology Co.,LTD.'),
(4049, '00:0F:EB', 'Cylon Controls'),
(4050, '00:0F:EC', 'ARKUS Inc.'),
(4051, '00:0F:ED', 'Anam Electronics Co., Ltd'),
(4052, '00:0F:EE', 'XTec, Incorporated'),
(4053, '00:0F:EF', 'Thales e-Transactions GmbH'),
(4054, '00:0F:F0', 'Sunray Co. Ltd.'),
(4055, '00:0F:F1', 'nex-G Systems Pte.Ltd'),
(4056, '00:0F:F2', 'Loud Technologies Inc.'),
(4057, '00:0F:F3', 'Jung Myoung Communications&amp;Technology'),
(4058, '00:0F:F4', 'Guntermann &amp; Drunck GmbH'),
(4059, '00:0F:F5', 'GN&amp;S company'),
(4060, '00:0F:F6', 'Darfon Electronics Corp.'),
(4061, '00:0F:F7', 'CISCO SYSTEMS, INC.'),
(4062, '00:0F:F8', 'CISCO SYSTEMS, INC.'),
(4063, '00:0F:F9', 'Valcretec, Inc.'),
(4064, '00:0F:FA', 'Optinel Systems, Inc.'),
(4065, '00:0F:FB', 'Nippon Denso Industry Co., Ltd.'),
(4066, '00:0F:FC', 'Merit Li-Lin Ent.'),
(4067, '00:0F:FD', 'Glorytek Network Inc.'),
(4068, '00:0F:FE', 'G-PRO COMPUTER'),
(4069, '00:0F:FF', 'Control4'),
(4070, '00:10:00', 'CABLE TELEVISION LABORATORIES, INC.'),
(4071, '00:10:01', 'Citel'),
(4072, '00:10:02', 'ACTIA'),
(4073, '00:10:03', 'IMATRON, INC.'),
(4074, '00:10:04', 'THE BRANTLEY COILE COMPANY,INC'),
(4075, '00:10:05', 'UEC COMMERCIAL'),
(4076, '00:10:06', 'Thales Contact Solutions Ltd.'),
(4077, '00:10:07', 'CISCO SYSTEMS, INC.'),
(4078, '00:10:08', 'VIENNA SYSTEMS CORPORATION'),
(4079, '00:10:09', 'HORO QUARTZ'),
(4080, '00:10:0A', 'WILLIAMS COMMUNICATIONS GROUP'),
(4081, '00:10:0B', 'CISCO SYSTEMS, INC.'),
(4082, '00:10:0C', 'ITO CO., LTD.'),
(4083, '00:10:0D', 'CISCO SYSTEMS, INC.'),
(4084, '00:10:0E', 'MICRO LINEAR COPORATION'),
(4085, '00:10:0F', 'INDUSTRIAL CPU SYSTEMS'),
(4086, '00:10:10', 'INITIO CORPORATION'),
(4087, '00:10:11', 'CISCO SYSTEMS, INC.'),
(4088, '00:10:12', 'PROCESSOR SYSTEMS (I) PVT LTD'),
(4089, '00:10:13', 'Kontron America, Inc.'),
(4090, '00:10:14', 'CISCO SYSTEMS, INC.'),
(4091, '00:10:15', 'OOmon Inc.'),
(4092, '00:10:16', 'T.SQWARE'),
(4093, '00:10:17', 'Bosch Access Systems GmbH'),
(4094, '00:10:18', 'BROADCOM CORPORATION'),
(4095, '00:10:19', 'SIRONA DENTAL SYSTEMS GmbH &amp; Co. KG'),
(4096, '00:10:1A', 'PictureTel Corp.'),
(4097, '00:10:1B', 'CORNET TECHNOLOGY, INC.'),
(4098, '00:10:1C', 'OHM TECHNOLOGIES INTL, LLC'),
(4099, '00:10:1D', 'WINBOND ELECTRONICS CORP.'),
(4100, '00:10:1E', 'MATSUSHITA ELECTRONIC INSTRUMENTS CORP.'),
(4101, '00:10:1F', 'CISCO SYSTEMS, INC.'),
(4102, '00:10:20', 'Hand Held Products Inc'),
(4103, '00:10:21', 'ENCANTO NETWORKS, INC.'),
(4104, '00:10:22', 'SatCom Media Corporation'),
(4105, '00:10:23', 'Network Equipment Technologies'),
(4106, '00:10:24', 'NAGOYA ELECTRIC WORKS CO., LTD'),
(4107, '00:10:25', 'Grayhill, Inc'),
(4108, '00:10:26', 'ACCELERATED NETWORKS, INC.'),
(4109, '00:10:27', 'L-3 COMMUNICATIONS EAST'),
(4110, '00:10:28', 'COMPUTER TECHNICA, INC.'),
(4111, '00:10:29', 'CISCO SYSTEMS, INC.'),
(4112, '00:10:2A', 'ZF MICROSYSTEMS, INC.'),
(4113, '00:10:2B', 'UMAX DATA SYSTEMS, INC.'),
(4114, '00:10:2C', 'Lasat Networks A/S'),
(4115, '00:10:2D', 'HITACHI SOFTWARE ENGINEERING'),
(4116, '00:10:2E', 'NETWORK SYSTEMS &amp; TECHNOLOGIES PVT. LTD.'),
(4117, '00:10:2F', 'CISCO SYSTEMS, INC.'),
(4118, '00:10:30', 'EION Inc.'),
(4119, '00:10:31', 'OBJECTIVE COMMUNICATIONS, INC.'),
(4120, '00:10:32', 'ALTA TECHNOLOGY'),
(4121, '00:10:33', 'ACCESSLAN COMMUNICATIONS, INC.'),
(4122, '00:10:34', 'GNP Computers'),
(4123, '00:10:35', 'ELITEGROUP COMPUTER SYSTEMS CO., LTD'),
(4124, '00:10:36', 'INTER-TEL INTEGRATED SYSTEMS'),
(4125, '00:10:37', 'CYQ\'ve Technology Co., Ltd.'),
(4126, '00:10:38', 'MICRO RESEARCH INSTITUTE, INC.'),
(4127, '00:10:39', 'Vectron Systems AG'),
(4128, '00:10:3A', 'DIAMOND NETWORK TECH'),
(4129, '00:10:3B', 'HIPPI NETWORKING FORUM'),
(4130, '00:10:3C', 'IC ENSEMBLE, INC.'),
(4131, '00:10:3D', 'PHASECOM, LTD.'),
(4132, '00:10:3E', 'NETSCHOOLS CORPORATION'),
(4133, '00:10:3F', 'TOLLGRADE COMMUNICATIONS, INC.'),
(4134, '00:10:40', 'INTERMEC CORPORATION'),
(4135, '00:10:41', 'BRISTOL BABCOCK, INC.'),
(4136, '00:10:42', 'Alacritech, Inc.'),
(4137, '00:10:43', 'A2 CORPORATION'),
(4138, '00:10:44', 'InnoLabs Corporation'),
(4139, '00:10:45', 'Nortel Networks'),
(4140, '00:10:46', 'ALCORN MCBRIDE INC.'),
(4141, '00:10:47', 'ECHO ELETRIC CO. LTD.'),
(4142, '00:10:48', 'HTRC AUTOMATION, INC.'),
(4143, '00:10:49', 'ShoreTel, Inc'),
(4144, '00:10:4A', 'The Parvus Corporation'),
(4145, '00:10:4B', '3COM CORPORATION'),
(4146, '00:10:4C', 'Teledyne LeCroy, Inc'),
(4147, '00:10:4D', 'SURTEC INDUSTRIES, INC.'),
(4148, '00:10:4E', 'CEOLOGIC'),
(4149, '00:10:4F', 'Oracle Corporation'),
(4150, '00:10:50', 'RION CO., LTD.'),
(4151, '00:10:51', 'CMICRO CORPORATION'),
(4152, '00:10:52', 'METTLER-TOLEDO (ALBSTADT) GMBH'),
(4153, '00:10:53', 'COMPUTER TECHNOLOGY CORP.'),
(4154, '00:10:54', 'CISCO SYSTEMS, INC.'),
(4155, '00:10:55', 'FUJITSU MICROELECTRONICS, INC.'),
(4156, '00:10:56', 'SODICK CO., LTD.'),
(4157, '00:10:57', 'Rebel.com, Inc.'),
(4158, '00:10:58', 'ArrowPoint Communications'),
(4159, '00:10:59', 'DIABLO RESEARCH CO. LLC'),
(4160, '00:10:5A', '3COM CORPORATION'),
(4161, '00:10:5B', 'NET INSIGHT AB'),
(4162, '00:10:5C', 'QUANTUM DESIGNS (H.K.) LTD.'),
(4163, '00:10:5D', 'Draeger Medical'),
(4164, '00:10:5E', 'Spirent plc, Service Assurance Broadband'),
(4165, '00:10:5F', 'ZODIAC DATA SYSTEMS'),
(4166, '00:10:60', 'BILLIONTON SYSTEMS, INC.'),
(4167, '00:10:61', 'HOSTLINK CORP.'),
(4168, '00:10:62', 'NX SERVER, ILNC.'),
(4169, '00:10:63', 'STARGUIDE DIGITAL NETWORKS'),
(4170, '00:10:64', 'DNPG, LLC'),
(4171, '00:10:65', 'RADYNE CORPORATION'),
(4172, '00:10:66', 'ADVANCED CONTROL SYSTEMS, INC.'),
(4173, '00:10:67', 'Ericsson'),
(4174, '00:10:68', 'COMOS TELECOM'),
(4175, '00:10:69', 'HELIOSS COMMUNICATIONS, INC.'),
(4176, '00:10:6A', 'DIGITAL MICROWAVE CORPORATION'),
(4177, '00:10:6B', 'SONUS NETWORKS, INC.'),
(4178, '00:10:6C', 'EDNT GmbH'),
(4179, '00:10:6D', 'Axxcelera Broadband Wireless'),
(4180, '00:10:6E', 'TADIRAN COM. LTD.'),
(4181, '00:10:6F', 'TRENTON TECHNOLOGY INC.'),
(4182, '00:10:70', 'CARADON TREND LTD.'),
(4183, '00:10:71', 'ADVANET INC.'),
(4184, '00:10:72', 'GVN TECHNOLOGIES, INC.'),
(4185, '00:10:73', 'Technobox, Inc.'),
(4186, '00:10:74', 'ATEN INTERNATIONAL CO., LTD.'),
(4187, '00:10:75', 'Segate Technology LLC'),
(4188, '00:10:76', 'EUREM GmbH'),
(4189, '00:10:77', 'SAF DRIVE SYSTEMS, LTD.'),
(4190, '00:10:78', 'NUERA COMMUNICATIONS, INC.'),
(4191, '00:10:79', 'CISCO SYSTEMS, INC.'),
(4192, '00:10:7A', 'AmbiCom, Inc.'),
(4193, '00:10:7B', 'CISCO SYSTEMS, INC.'),
(4194, '00:10:7C', 'P-COM, INC.'),
(4195, '00:10:7D', 'AURORA COMMUNICATIONS, LTD.'),
(4196, '00:10:7E', 'BACHMANN ELECTRONIC GmbH'),
(4197, '00:10:7F', 'CRESTRON ELECTRONICS, INC.'),
(4198, '00:10:80', 'METAWAVE COMMUNICATIONS'),
(4199, '00:10:81', 'DPS, INC.'),
(4200, '00:10:82', 'JNA TELECOMMUNICATIONS LIMITED'),
(4201, '00:10:83', 'HEWLETT-PACKARD COMPANY'),
(4202, '00:10:84', 'K-BOT COMMUNICATIONS'),
(4203, '00:10:85', 'POLARIS COMMUNICATIONS, INC.'),
(4204, '00:10:86', 'ATTO Technology, Inc.'),
(4205, '00:10:87', 'Xstreamis PLC'),
(4206, '00:10:88', 'AMERICAN NETWORKS INC.'),
(4207, '00:10:89', 'WebSonic'),
(4208, '00:10:8A', 'TeraLogic, Inc.'),
(4209, '00:10:8B', 'LASERANIMATION SOLLINGER GmbH'),
(4210, '00:10:8C', 'FUJITSU TELECOMMUNICATIONS EUROPE, LTD.'),
(4211, '00:10:8D', 'Johnson Controls, Inc.'),
(4212, '00:10:8E', 'HUGH SYMONS CONCEPT Technologies Ltd.'),
(4213, '00:10:8F', 'RAPTOR SYSTEMS'),
(4214, '00:10:90', 'CIMETRICS, INC.'),
(4215, '00:10:91', 'NO WIRES NEEDED BV'),
(4216, '00:10:92', 'NETCORE INC.'),
(4217, '00:10:93', 'CMS COMPUTERS, LTD.'),
(4218, '00:10:94', 'Performance Analysis Broadband, Spirent plc'),
(4219, '00:10:95', 'Thomson Inc.'),
(4220, '00:10:96', 'TRACEWELL SYSTEMS, INC.'),
(4221, '00:10:97', 'WinNet Metropolitan Communications Systems, Inc.'),
(4222, '00:10:98', 'STARNET TECHNOLOGIES, INC.'),
(4223, '00:10:99', 'InnoMedia, Inc.'),
(4224, '00:10:9A', 'NETLINE'),
(4225, '00:10:9B', 'Emulex Corporation'),
(4226, '00:10:9C', 'M-SYSTEM CO., LTD.'),
(4227, '00:10:9D', 'CLARINET SYSTEMS, INC.'),
(4228, '00:10:9E', 'AWARE, INC.'),
(4229, '00:10:9F', 'PAVO, INC.'),
(4230, '00:10:A0', 'INNOVEX TECHNOLOGIES, INC.'),
(4231, '00:10:A1', 'KENDIN SEMICONDUCTOR, INC.'),
(4232, '00:10:A2', 'TNS'),
(4233, '00:10:A3', 'OMNITRONIX, INC.'),
(4234, '00:10:A4', 'XIRCOM'),
(4235, '00:10:A5', 'OXFORD INSTRUMENTS'),
(4236, '00:10:A6', 'CISCO SYSTEMS, INC.'),
(4237, '00:10:A7', 'UNEX TECHNOLOGY CORPORATION'),
(4238, '00:10:A8', 'RELIANCE COMPUTER CORP.'),
(4239, '00:10:A9', 'ADHOC TECHNOLOGIES'),
(4240, '00:10:AA', 'MEDIA4, INC.'),
(4241, '00:10:AB', 'KOITO ELECTRIC INDUSTRIES, LTD.'),
(4242, '00:10:AC', 'IMCI TECHNOLOGIES'),
(4243, '00:10:AD', 'SOFTRONICS USB, INC.'),
(4244, '00:10:AE', 'SHINKO ELECTRIC INDUSTRIES CO.'),
(4245, '00:10:AF', 'TAC SYSTEMS, INC.'),
(4246, '00:10:B0', 'MERIDIAN TECHNOLOGY CORP.'),
(4247, '00:10:B1', 'FOR-A CO., LTD.'),
(4248, '00:10:B2', 'COACTIVE AESTHETICS'),
(4249, '00:10:B3', 'NOKIA MULTIMEDIA TERMINALS'),
(4250, '00:10:B4', 'ATMOSPHERE NETWORKS'),
(4251, '00:10:B5', 'ACCTON TECHNOLOGY CORPORATION'),
(4252, '00:10:B6', 'ENTRATA COMMUNICATIONS CORP.'),
(4253, '00:10:B7', 'COYOTE TECHNOLOGIES, LLC'),
(4254, '00:10:B8', 'ISHIGAKI COMPUTER SYSTEM CO.'),
(4255, '00:10:B9', 'MAXTOR CORP.'),
(4256, '00:10:BA', 'MARTINHO-DAVIS SYSTEMS, INC.'),
(4257, '00:10:BB', 'DATA &amp; INFORMATION TECHNOLOGY'),
(4258, '00:10:BC', 'Aastra Telecom'),
(4259, '00:10:BD', 'THE TELECOMMUNICATION TECHNOLOGY COMMITTEE (TTC)'),
(4260, '00:10:BE', 'MARCH NETWORKS CORPORATION'),
(4261, '00:10:BF', 'InterAir Wireless'),
(4262, '00:10:C0', 'ARMA, Inc.'),
(4263, '00:10:C1', 'OI ELECTRIC CO., LTD.'),
(4264, '00:10:C2', 'WILLNET, INC.'),
(4265, '00:10:C3', 'CSI-CONTROL SYSTEMS'),
(4266, '00:10:C4', 'MEDIA LINKS CO., LTD.'),
(4267, '00:10:C5', 'PROTOCOL TECHNOLOGIES, INC.'),
(4268, '00:10:C6', 'Universal Global Scientific Industrial Co., Ltd.'),
(4269, '00:10:C7', 'DATA TRANSMISSION NETWORK'),
(4270, '00:10:C8', 'COMMUNICATIONS ELECTRONICS SECURITY GROUP'),
(4271, '00:10:C9', 'MITSUBISHI ELECTRONICS LOGISTIC SUPPORT CO.'),
(4272, '00:10:CA', 'Telco Systems, Inc.'),
(4273, '00:10:CB', 'FACIT K.K.'),
(4274, '00:10:CC', 'CLP COMPUTER LOGISTIK PLANUNG GmbH'),
(4275, '00:10:CD', 'INTERFACE CONCEPT'),
(4276, '00:10:CE', 'VOLAMP, LTD.'),
(4277, '00:10:CF', 'FIBERLANE COMMUNICATIONS'),
(4278, '00:10:D0', 'WITCOM, LTD.'),
(4279, '00:10:D1', 'Top Layer Networks, Inc.'),
(4280, '00:10:D2', 'NITTO TSUSHINKI CO., LTD'),
(4281, '00:10:D3', 'GRIPS ELECTRONIC GMBH'),
(4282, '00:10:D4', 'STORAGE COMPUTER CORPORATION'),
(4283, '00:10:D5', 'IMASDE CANARIAS, S.A.'),
(4284, '00:10:D6', 'Exelis'),
(4285, '00:10:D7', 'ARGOSY RESEARCH INC.'),
(4286, '00:10:D8', 'CALISTA'),
(4287, '00:10:D9', 'IBM JAPAN, FUJISAWA MT+D'),
(4288, '00:10:DA', 'Kollmorgen Corp'),
(4289, '00:10:DB', 'Juniper Networks, Inc.'),
(4290, '00:10:DC', 'MICRO-STAR INTERNATIONAL CO., LTD.'),
(4291, '00:10:DD', 'ENABLE SEMICONDUCTOR, INC.'),
(4292, '00:10:DE', 'INTERNATIONAL DATACASTING CORPORATION'),
(4293, '00:10:DF', 'RISE COMPUTER INC.'),
(4294, '00:10:E0', 'Oracle Corporation'),
(4295, '00:10:E1', 'S.I. TECH, INC.'),
(4296, '00:10:E2', 'ArrayComm, Inc.'),
(4297, '00:10:E3', 'Hewlett-Packard Company'),
(4298, '00:10:E4', 'NSI CORPORATION'),
(4299, '00:10:E5', 'SOLECTRON TEXAS'),
(4300, '00:10:E6', 'APPLIED INTELLIGENT SYSTEMS, INC.'),
(4301, '00:10:E7', 'BreezeCom'),
(4302, '00:10:E8', 'TELOCITY, INCORPORATED'),
(4303, '00:10:E9', 'RAIDTEC LTD.'),
(4304, '00:10:EA', 'ADEPT TECHNOLOGY'),
(4305, '00:10:EB', 'SELSIUS SYSTEMS, INC.'),
(4306, '00:10:EC', 'RPCG, LLC'),
(4307, '00:10:ED', 'SUNDANCE TECHNOLOGY, INC.'),
(4308, '00:10:EE', 'CTI PRODUCTS, INC.'),
(4309, '00:10:EF', 'DBTEL INCORPORATED'),
(4310, '00:10:F0', 'RITTAL-WERK RUDOLF LOH GmbH &amp; Co.'),
(4311, '00:10:F1', 'I-O CORPORATION'),
(4312, '00:10:F2', 'ANTEC'),
(4313, '00:10:F3', 'Nexcom International Co., Ltd.'),
(4314, '00:10:F4', 'Vertical Communications'),
(4315, '00:10:F5', 'AMHERST SYSTEMS, INC.'),
(4316, '00:10:F6', 'CISCO SYSTEMS, INC.'),
(4317, '00:10:F7', 'IRIICHI TECHNOLOGIES Inc.'),
(4318, '00:10:F8', 'TEXIO TECHNOLOGY CORPORATION'),
(4319, '00:10:F9', 'UNIQUE SYSTEMS, INC.'),
(4320, '00:10:FA', 'Apple'),
(4321, '00:10:FB', 'ZIDA TECHNOLOGIES LIMITED'),
(4322, '00:10:FC', 'BROADBAND NETWORKS, INC.'),
(4323, '00:10:FD', 'COCOM A/S'),
(4324, '00:10:FE', 'DIGITAL EQUIPMENT CORPORATION'),
(4325, '00:10:FF', 'CISCO SYSTEMS, INC.'),
(4326, '00:11:00', 'Schneider Electric'),
(4327, '00:11:01', 'CET Technologies Pte Ltd'),
(4328, '00:11:02', 'Aurora Multimedia Corp.'),
(4329, '00:11:03', 'kawamura electric inc.'),
(4330, '00:11:04', 'TELEXY'),
(4331, '00:11:05', 'Sunplus Technology Co., Ltd.'),
(4332, '00:11:06', 'Siemens NV (Belgium)'),
(4333, '00:11:07', 'RGB Networks Inc.'),
(4334, '00:11:08', 'Orbital Data Corporation'),
(4335, '00:11:09', 'Micro-Star International'),
(4336, '00:11:0A', 'Hewlett-Packard Company'),
(4337, '00:11:0B', 'Franklin Technology Systems'),
(4338, '00:11:0C', 'Atmark Techno, Inc.'),
(4339, '00:11:0D', 'SANBlaze Technology, Inc.'),
(4340, '00:11:0E', 'Tsurusaki Sealand Transportation Co. Ltd.'),
(4341, '00:11:0F', 'netplat,Inc.'),
(4342, '00:11:10', 'Maxanna Technology Co., Ltd.'),
(4343, '00:11:11', 'Intel Corporation'),
(4344, '00:11:12', 'Honeywell CMSS'),
(4345, '00:11:13', 'Fraunhofer FOKUS'),
(4346, '00:11:14', 'EverFocus Electronics Corp.'),
(4347, '00:11:15', 'EPIN Technologies, Inc.'),
(4348, '00:11:16', 'COTEAU VERT CO., LTD.'),
(4349, '00:11:17', 'CESNET'),
(4350, '00:11:18', 'BLX IC Design Corp., Ltd.'),
(4351, '00:11:19', 'Solteras, Inc.'),
(4352, '00:11:1A', 'ARRIS Group, Inc.'),
(4353, '00:11:1B', 'Targa Systems Div L-3 Communications Canada'),
(4354, '00:11:1C', 'Pleora Technologies Inc.'),
(4355, '00:11:1D', 'Hectrix Limited'),
(4356, '00:11:1E', 'EPSG (Ethernet Powerlink Standardization Group)'),
(4357, '00:11:1F', 'Doremi Labs, Inc.'),
(4358, '00:11:20', 'CISCO SYSTEMS, INC.'),
(4359, '00:11:21', 'CISCO SYSTEMS, INC.'),
(4360, '00:11:22', 'CIMSYS Inc'),
(4361, '00:11:23', 'Appointech, Inc.'),
(4362, '00:11:24', 'Apple'),
(4363, '00:11:25', 'IBM Corp'),
(4364, '00:11:26', 'Venstar Inc.'),
(4365, '00:11:27', 'TASI, Inc'),
(4366, '00:11:28', 'Streamit'),
(4367, '00:11:29', 'Paradise Datacom Ltd.'),
(4368, '00:11:2A', 'Niko NV'),
(4369, '00:11:2B', 'NetModule AG'),
(4370, '00:11:2C', 'IZT GmbH'),
(4371, '00:11:2D', 'iPulse Systems'),
(4372, '00:11:2E', 'CEICOM'),
(4373, '00:11:2F', 'ASUSTek Computer Inc.'),
(4374, '00:11:30', 'Allied Telesis (Hong Kong) Ltd.'),
(4375, '00:11:31', 'UNATECH. CO.,LTD'),
(4376, '00:11:32', 'Synology Incorporated'),
(4377, '00:11:33', 'Siemens Austria SIMEA'),
(4378, '00:11:34', 'MediaCell, Inc.'),
(4379, '00:11:35', 'Grandeye Ltd'),
(4380, '00:11:36', 'Goodrich Sensor Systems'),
(4381, '00:11:37', 'AICHI ELECTRIC CO., LTD.'),
(4382, '00:11:38', 'TAISHIN CO., LTD.'),
(4383, '00:11:39', 'STOEBER ANTRIEBSTECHNIK GmbH + Co. KG.'),
(4384, '00:11:3A', 'SHINBORAM'),
(4385, '00:11:3B', 'Micronet Communications Inc.'),
(4386, '00:11:3C', 'Micronas GmbH'),
(4387, '00:11:3D', 'KN SOLTEC CO.,LTD.'),
(4388, '00:11:3E', 'JL Corporation'),
(4389, '00:11:3F', 'Alcatel DI'),
(4390, '00:11:40', 'Nanometrics Inc.'),
(4391, '00:11:41', 'GoodMan Corporation'),
(4392, '00:11:42', 'e-SMARTCOM  INC.'),
(4393, '00:11:43', 'Dell Inc'),
(4394, '00:11:44', 'Assurance Technology Corp'),
(4395, '00:11:45', 'ValuePoint Networks'),
(4396, '00:11:46', 'Telecard-Pribor Ltd'),
(4397, '00:11:47', 'Secom-Industry co.LTD.'),
(4398, '00:11:48', 'Prolon Control Systems'),
(4399, '00:11:49', 'Proliphix Inc.'),
(4400, '00:11:4A', 'KAYABA INDUSTRY Co,.Ltd.'),
(4401, '00:11:4B', 'Francotyp-Postalia GmbH'),
(4402, '00:11:4C', 'caffeina applied research ltd.'),
(4403, '00:11:4D', 'Atsumi Electric Co.,LTD.'),
(4404, '00:11:4E', '690885 Ontario Inc.'),
(4405, '00:11:4F', 'US Digital Television, Inc'),
(4406, '00:11:50', 'Belkin Corporation'),
(4407, '00:11:51', 'Mykotronx'),
(4408, '00:11:52', 'Eidsvoll Electronics AS'),
(4409, '00:11:53', 'Trident Tek, Inc.'),
(4410, '00:11:54', 'Webpro Technologies Inc.'),
(4411, '00:11:55', 'Sevis Systems'),
(4412, '00:11:56', 'Pharos Systems NZ'),
(4413, '00:11:57', 'OF Networks Co., Ltd.'),
(4414, '00:11:58', 'Nortel Networks'),
(4415, '00:11:59', 'MATISSE NETWORKS INC'),
(4416, '00:11:5A', 'Ivoclar Vivadent AG'),
(4417, '00:11:5B', 'Elitegroup Computer System Co. (ECS)'),
(4418, '00:11:5C', 'CISCO SYSTEMS, INC.'),
(4419, '00:11:5D', 'CISCO SYSTEMS, INC.'),
(4420, '00:11:5E', 'ProMinent Dosiertechnik GmbH'),
(4421, '00:11:5F', 'ITX Security Co., Ltd.'),
(4422, '00:11:60', 'ARTDIO Company Co., LTD'),
(4423, '00:11:61', 'NetStreams, LLC'),
(4424, '00:11:62', 'STAR MICRONICS CO.,LTD.'),
(4425, '00:11:63', 'SYSTEM SPA DEPT. ELECTRONICS'),
(4426, '00:11:64', 'ACARD Technology Corp.'),
(4427, '00:11:65', 'Znyx Networks'),
(4428, '00:11:66', 'Taelim Electronics Co., Ltd.'),
(4429, '00:11:67', 'Integrated System Solution Corp.'),
(4430, '00:11:68', 'HomeLogic LLC'),
(4431, '00:11:69', 'EMS Satcom'),
(4432, '00:11:6A', 'Domo Ltd'),
(4433, '00:11:6B', 'Digital Data Communications Asia Co.,Ltd'),
(4434, '00:11:6C', 'Nanwang Multimedia Inc.,Ltd'),
(4435, '00:11:6D', 'American Time and Signal'),
(4436, '00:11:6E', 'PePLink Ltd.'),
(4437, '00:11:6F', 'Netforyou Co., LTD.'),
(4438, '00:11:70', 'GSC SRL'),
(4439, '00:11:71', 'DEXTER Communications, Inc.'),
(4440, '00:11:72', 'COTRON CORPORATION'),
(4441, '00:11:73', 'SMART Storage Systems'),
(4442, '00:11:74', 'Wibhu Technologies, Inc.'),
(4443, '00:11:75', 'PathScale, Inc.'),
(4444, '00:11:76', 'Intellambda Systems, Inc.'),
(4445, '00:11:77', 'Coaxial Networks, Inc.'),
(4446, '00:11:78', 'Chiron Technology Ltd'),
(4447, '00:11:79', 'Singular Technology Co. Ltd.'),
(4448, '00:11:7A', 'Singim International Corp.'),
(4449, '00:11:7B', 'B&uuml;chi  Labortechnik AG'),
(4450, '00:11:7C', 'e-zy.net'),
(4451, '00:11:7D', 'ZMD America, Inc.'),
(4452, '00:11:7E', 'Progeny, A division of Midmark Corp'),
(4453, '00:11:7F', 'Neotune Information Technology Corporation,.LTD'),
(4454, '00:11:80', 'ARRIS Group, Inc.'),
(4455, '00:11:81', 'InterEnergy Co.Ltd,'),
(4456, '00:11:82', 'IMI Norgren Ltd'),
(4457, '00:11:83', 'Datalogic ADC, Inc.'),
(4458, '00:11:84', 'Humo Laboratory,Ltd.'),
(4459, '00:11:85', 'Hewlett-Packard Company'),
(4460, '00:11:86', 'Prime Systems, Inc.'),
(4461, '00:11:87', 'Category Solutions, Inc'),
(4462, '00:11:88', 'Enterasys'),
(4463, '00:11:89', 'Aerotech Inc'),
(4464, '00:11:8A', 'Viewtran Technology Limited'),
(4465, '00:11:8B', 'Alcatel-Lucent, Enterprise Business Group'),
(4466, '00:11:8C', 'Missouri Department of Transportation'),
(4467, '00:11:8D', 'Hanchang System Corp.'),
(4468, '00:11:8E', 'Halytech Mace'),
(4469, '00:11:8F', 'EUTECH INSTRUMENTS PTE. LTD.'),
(4470, '00:11:90', 'Digital Design Corporation'),
(4471, '00:11:91', 'CTS-Clima Temperatur Systeme GmbH'),
(4472, '00:11:92', 'CISCO SYSTEMS, INC.'),
(4473, '00:11:93', 'CISCO SYSTEMS, INC.'),
(4474, '00:11:94', 'Chi Mei Communication Systems, Inc.'),
(4475, '00:11:95', 'D-Link Corporation'),
(4476, '00:11:96', 'Actuality Systems, Inc.'),
(4477, '00:11:97', 'Monitoring Technologies Limited'),
(4478, '00:11:98', 'Prism Media Products Limited'),
(4479, '00:11:99', '2wcom Systems GmbH'),
(4480, '00:11:9A', 'Alkeria srl'),
(4481, '00:11:9B', 'Telesynergy Research Inc.'),
(4482, '00:11:9C', 'EP&amp;T Energy'),
(4483, '00:11:9D', 'Diginfo Technology Corporation'),
(4484, '00:11:9E', 'Solectron Brazil'),
(4485, '00:11:9F', 'Nokia Danmark A/S'),
(4486, '00:11:A0', 'Vtech Engineering Canada Ltd'),
(4487, '00:11:A1', 'VISION NETWARE CO.,LTD'),
(4488, '00:11:A2', 'Manufacturing Technology Inc'),
(4489, '00:11:A3', 'LanReady Technologies Inc.'),
(4490, '00:11:A4', 'JStream Technologies Inc.'),
(4491, '00:11:A5', 'Fortuna Electronic Corp.'),
(4492, '00:11:A6', 'Sypixx Networks'),
(4493, '00:11:A7', 'Infilco Degremont Inc.'),
(4494, '00:11:A8', 'Quest Technologies'),
(4495, '00:11:A9', 'MOIMSTONE Co., LTD'),
(4496, '00:11:AA', 'Uniclass Technology, Co., LTD'),
(4497, '00:11:AB', 'TRUSTABLE TECHNOLOGY CO.,LTD.'),
(4498, '00:11:AC', 'Simtec Electronics'),
(4499, '00:11:AD', 'Shanghai Ruijie Technology'),
(4500, '00:11:AE', 'ARRIS Group, Inc.'),
(4501, '00:11:AF', 'Medialink-i,Inc'),
(4502, '00:11:B0', 'Fortelink Inc.'),
(4503, '00:11:B1', 'BlueExpert Technology Corp.'),
(4504, '00:11:B2', '2001 Technology Inc.'),
(4505, '00:11:B3', 'YOSHIMIYA CO.,LTD.'),
(4506, '00:11:B4', 'Westermo Teleindustri AB'),
(4507, '00:11:B5', 'Shenzhen Powercom Co.,Ltd'),
(4508, '00:11:B6', 'Open Systems International'),
(4509, '00:11:B7', 'Octalix B.V.'),
(4510, '00:11:B8', 'Liebherr - Elektronik GmbH'),
(4511, '00:11:B9', 'Inner Range Pty. Ltd.'),
(4512, '00:11:BA', 'Elexol Pty Ltd'),
(4513, '00:11:BB', 'CISCO SYSTEMS, INC.'),
(4514, '00:11:BC', 'CISCO SYSTEMS, INC.'),
(4515, '00:11:BD', 'Bombardier Transportation'),
(4516, '00:11:BE', 'AGP Telecom Co. Ltd'),
(4517, '00:11:BF', 'AESYS S.p.A.'),
(4518, '00:11:C0', 'Aday Technology Inc'),
(4519, '00:11:C1', '4P MOBILE DATA PROCESSING'),
(4520, '00:11:C2', 'United Fiber Optic Communication'),
(4521, '00:11:C3', 'Transceiving System Technology Corporation'),
(4522, '00:11:C4', 'Terminales de Telecomunicacion Terrestre, S.L.'),
(4523, '00:11:C5', 'TEN Technology'),
(4524, '00:11:C6', 'Seagate Technology'),
(4525, '00:11:C7', 'Raymarine UK Ltd'),
(4526, '00:11:C8', 'Powercom Co., Ltd.'),
(4527, '00:11:C9', 'MTT Corporation'),
(4528, '00:11:CA', 'Long Range Systems, Inc.'),
(4529, '00:11:CB', 'Jacobsons AB'),
(4530, '00:11:CC', 'Guangzhou Jinpeng Group Co.,Ltd.'),
(4531, '00:11:CD', 'Axsun Technologies'),
(4532, '00:11:CE', 'Ubisense Limited'),
(4533, '00:11:CF', 'Thrane &amp; Thrane A/S'),
(4534, '00:11:D0', 'Tandberg Data ASA'),
(4535, '00:11:D1', 'Soft Imaging System GmbH'),
(4536, '00:11:D2', 'Perception Digital Ltd'),
(4537, '00:11:D3', 'NextGenTel Holding ASA'),
(4538, '00:11:D4', 'NetEnrich, Inc'),
(4539, '00:11:D5', 'Hangzhou Sunyard System Engineering Co.,Ltd.'),
(4540, '00:11:D6', 'HandEra, Inc.'),
(4541, '00:11:D7', 'eWerks Inc'),
(4542, '00:11:D8', 'ASUSTek Computer Inc.'),
(4543, '00:11:D9', 'TiVo'),
(4544, '00:11:DA', 'Vivaas Technology Inc.'),
(4545, '00:11:DB', 'Land-Cellular Corporation'),
(4546, '00:11:DC', 'Glunz &amp; Jensen'),
(4547, '00:11:DD', 'FROMUS TEC. Co., Ltd.'),
(4548, '00:11:DE', 'EURILOGIC'),
(4549, '00:11:DF', 'Current Energy'),
(4550, '00:11:E0', 'U-MEDIA Communications, Inc.'),
(4551, '00:11:E1', 'Arcelik A.S'),
(4552, '00:11:E2', 'Hua Jung Components Co., Ltd.'),
(4553, '00:11:E3', 'Thomson, Inc.'),
(4554, '00:11:E4', 'Danelec Electronics A/S'),
(4555, '00:11:E5', 'KCodes Corporation'),
(4556, '00:11:E6', 'Scientific Atlanta'),
(4557, '00:11:E7', 'WORLDSAT - Texas de France'),
(4558, '00:11:E8', 'Tixi.Com'),
(4559, '00:11:E9', 'STARNEX CO., LTD.'),
(4560, '00:11:EA', 'IWICS Inc.'),
(4561, '00:11:EB', 'Innovative Integration'),
(4562, '00:11:EC', 'AVIX INC.'),
(4563, '00:11:ED', '802 Global'),
(4564, '00:11:EE', 'Estari, Inc.'),
(4565, '00:11:EF', 'Conitec Datensysteme GmbH'),
(4566, '00:11:F0', 'Wideful Limited'),
(4567, '00:11:F1', 'QinetiQ Ltd'),
(4568, '00:11:F2', 'Institute of Network Technologies'),
(4569, '00:11:F3', 'NeoMedia Europe AG'),
(4570, '00:11:F4', 'woori-net'),
(4571, '00:11:F5', 'ASKEY COMPUTER CORP.'),
(4572, '00:11:F6', 'Asia Pacific Microsystems , Inc.'),
(4573, '00:11:F7', 'Shenzhen Forward Industry Co., Ltd'),
(4574, '00:11:F8', 'AIRAYA Corp'),
(4575, '00:11:F9', 'Nortel Networks'),
(4576, '00:11:FA', 'Rane Corporation'),
(4577, '00:11:FB', 'Heidelberg Engineering GmbH'),
(4578, '00:11:FC', 'HARTING Electric Gmbh &amp; Co.KG'),
(4579, '00:11:FD', 'KORG INC.'),
(4580, '00:11:FE', 'Keiyo System Research, Inc.'),
(4581, '00:11:FF', 'Digitro Tecnologia Ltda'),
(4582, '00:12:00', 'CISCO SYSTEMS, INC.'),
(4583, '00:12:01', 'CISCO SYSTEMS, INC.'),
(4584, '00:12:02', 'Decrane Aerospace - Audio International Inc.'),
(4585, '00:12:03', 'ActivNetworks'),
(4586, '00:12:04', 'u10 Networks, Inc.'),
(4587, '00:12:05', 'Terrasat Communications, Inc.'),
(4588, '00:12:06', 'iQuest (NZ) Ltd'),
(4589, '00:12:07', 'Head Strong International Limited'),
(4590, '00:12:08', 'Gantner Instruments GmbH'),
(4591, '00:12:09', 'Fastrax Ltd'),
(4592, '00:12:0A', 'Emerson Climate Technologies GmbH'),
(4593, '00:12:0B', 'Chinasys Technologies Limited'),
(4594, '00:12:0C', 'CE-Infosys Pte Ltd'),
(4595, '00:12:0D', 'Advanced Telecommunication Technologies, Inc.'),
(4596, '00:12:0E', 'AboCom'),
(4597, '00:12:0F', 'IEEE 802.3'),
(4598, '00:12:10', 'WideRay Corp'),
(4599, '00:12:11', 'Protechna Herbst GmbH &amp; Co. KG'),
(4600, '00:12:12', 'PLUS  Corporation'),
(4601, '00:12:13', 'Metrohm AG'),
(4602, '00:12:14', 'Koenig &amp; Bauer AG'),
(4603, '00:12:15', 'iStor Networks, Inc.'),
(4604, '00:12:16', 'ICP Internet Communication Payment AG'),
(4605, '00:12:17', 'Cisco-Linksys, LLC'),
(4606, '00:12:18', 'ARUZE Corporation'),
(4607, '00:12:19', 'Ahead Communication Systems Inc'),
(4608, '00:12:1A', 'Techno Soft Systemnics Inc.'),
(4609, '00:12:1B', 'Sound Devices, LLC'),
(4610, '00:12:1C', 'PARROT S.A.'),
(4611, '00:12:1D', 'Netfabric Corporation'),
(4612, '00:12:1E', 'Juniper Networks, Inc.'),
(4613, '00:12:1F', 'Harding Instruments'),
(4614, '00:12:20', 'Cadco Systems'),
(4615, '00:12:21', 'B.Braun Melsungen AG'),
(4616, '00:12:22', 'Skardin (UK) Ltd'),
(4617, '00:12:23', 'Pixim'),
(4618, '00:12:24', 'NexQL Corporation'),
(4619, '00:12:25', 'ARRIS Group, Inc.'),
(4620, '00:12:26', 'Japan Direx Corporation'),
(4621, '00:12:27', 'Franklin Electric Co., Inc.'),
(4622, '00:12:28', 'Data Ltd.'),
(4623, '00:12:29', 'BroadEasy Technologies Co.,Ltd'),
(4624, '00:12:2A', 'VTech Telecommunications Ltd.'),
(4625, '00:12:2B', 'Virbiage Pty Ltd'),
(4626, '00:12:2C', 'Soenen Controls N.V.'),
(4627, '00:12:2D', 'SiNett Corporation'),
(4628, '00:12:2E', 'Signal Technology - AISD'),
(4629, '00:12:2F', 'Sanei Electric Inc.'),
(4630, '00:12:30', 'Picaso Infocommunication CO., LTD.'),
(4631, '00:12:31', 'Motion Control Systems, Inc.'),
(4632, '00:12:32', 'LeWiz Communications Inc.'),
(4633, '00:12:33', 'JRC TOKKI Co.,Ltd.'),
(4634, '00:12:34', 'Camille Bauer'),
(4635, '00:12:35', 'Andrew Corporation'),
(4636, '00:12:36', 'ConSentry Networks'),
(4637, '00:12:37', 'Texas Instruments'),
(4638, '00:12:38', 'SetaBox Technology Co., Ltd.'),
(4639, '00:12:39', 'S Net Systems Inc.'),
(4640, '00:12:3A', 'Posystech Inc., Co.'),
(4641, '00:12:3B', 'KeRo Systems ApS'),
(4642, '00:12:3C', 'Second Rule LLC'),
(4643, '00:12:3D', 'GES Co, Ltd'),
(4644, '00:12:3E', 'ERUNE technology Co., Ltd.'),
(4645, '00:12:3F', 'Dell Inc'),
(4646, '00:12:40', 'AMOI ELECTRONICS CO.,LTD'),
(4647, '00:12:41', 'a2i marketing center'),
(4648, '00:12:42', 'Millennial Net'),
(4649, '00:12:43', 'CISCO SYSTEMS, INC.'),
(4650, '00:12:44', 'CISCO SYSTEMS, INC.'),
(4651, '00:12:45', 'Zellweger Analytics, Inc.'),
(4652, '00:12:46', 'T.O.M TECHNOLOGY INC..'),
(4653, '00:12:47', 'Samsung Electronics Co., Ltd.'),
(4654, '00:12:48', 'EMC Corporation (Kashya)'),
(4655, '00:12:49', 'Delta Elettronica S.p.A.'),
(4656, '00:12:4A', 'Dedicated Devices, Inc.'),
(4657, '00:12:4B', 'Texas Instruments'),
(4658, '00:12:4C', 'BBWM Corporation'),
(4659, '00:12:4D', 'Inducon BV'),
(4660, '00:12:4E', 'XAC AUTOMATION CORP.'),
(4661, '00:12:4F', 'Pentair Thermal Management'),
(4662, '00:12:50', 'Tokyo Aircaft Instrument Co., Ltd.'),
(4663, '00:12:51', 'SILINK'),
(4664, '00:12:52', 'Citronix, LLC'),
(4665, '00:12:53', 'AudioDev AB'),
(4666, '00:12:54', 'Spectra Technologies Holdings Company Ltd'),
(4667, '00:12:55', 'NetEffect Incorporated'),
(4668, '00:12:56', 'LG INFORMATION &amp; COMM.'),
(4669, '00:12:57', 'LeapComm Communication Technologies Inc.'),
(4670, '00:12:58', 'Activis Polska'),
(4671, '00:12:59', 'THERMO ELECTRON KARLSRUHE'),
(4672, '00:12:5A', 'Microsoft Corporation'),
(4673, '00:12:5B', 'KAIMEI ELECTRONI'),
(4674, '00:12:5C', 'Green Hills Software, Inc.'),
(4675, '00:12:5D', 'CyberNet Inc.'),
(4676, '00:12:5E', 'CAEN'),
(4677, '00:12:5F', 'AWIND Inc.'),
(4678, '00:12:60', 'Stanton Magnetics,inc.'),
(4679, '00:12:61', 'Adaptix, Inc'),
(4680, '00:12:62', 'Nokia Danmark A/S'),
(4681, '00:12:63', 'Data Voice Technologies GmbH'),
(4682, '00:12:64', 'daum electronic gmbh'),
(4683, '00:12:65', 'Enerdyne Technologies, Inc.'),
(4684, '00:12:66', 'Swisscom Hospitality Services SA'),
(4685, '00:12:67', 'Panasonic Corporation'),
(4686, '00:12:68', 'IPS d.o.o.'),
(4687, '00:12:69', 'Value Electronics'),
(4688, '00:12:6A', 'OPTOELECTRONICS Co., Ltd.'),
(4689, '00:12:6B', 'Ascalade Communications Limited'),
(4690, '00:12:6C', 'Visonic Ltd.'),
(4691, '00:12:6D', 'University of California, Berkeley'),
(4692, '00:12:6E', 'Seidel Elektronik GmbH Nfg.KG'),
(4693, '00:12:6F', 'Rayson Technology Co., Ltd.'),
(4694, '00:12:70', 'NGES Denro Systems'),
(4695, '00:12:71', 'Measurement Computing Corp'),
(4696, '00:12:72', 'Redux Communications Ltd.'),
(4697, '00:12:73', 'Stoke Inc'),
(4698, '00:12:74', 'NIT lab'),
(4699, '00:12:75', 'Sentilla Corporation'),
(4700, '00:12:76', 'CG Power Systems Ireland Limited'),
(4701, '00:12:77', 'Korenix Technologies Co., Ltd.'),
(4702, '00:12:78', 'International Bar Code'),
(4703, '00:12:79', 'Hewlett-Packard Company'),
(4704, '00:12:7A', 'Sanyu Industry Co.,Ltd.'),
(4705, '00:12:7B', 'VIA Networking Technologies, Inc.'),
(4706, '00:12:7C', 'SWEGON AB'),
(4707, '00:12:7D', 'MobileAria'),
(4708, '00:12:7E', 'Digital Lifestyles Group, Inc.'),
(4709, '00:12:7F', 'CISCO SYSTEMS, INC.'),
(4710, '00:12:80', 'CISCO SYSTEMS, INC.'),
(4711, '00:12:81', 'March Networks S.p.A.'),
(4712, '00:12:82', 'Qovia'),
(4713, '00:12:83', 'Nortel Networks'),
(4714, '00:12:84', 'Lab33 Srl'),
(4715, '00:12:85', 'Gizmondo Europe Ltd'),
(4716, '00:12:86', 'ENDEVCO CORP'),
(4717, '00:12:87', 'Digital Everywhere Unterhaltungselektronik GmbH'),
(4718, '00:12:88', '2Wire, Inc'),
(4719, '00:12:89', 'Advance Sterilization Products'),
(4720, '00:12:8A', 'ARRIS Group, Inc.'),
(4721, '00:12:8B', 'Sensory Networks Inc'),
(4722, '00:12:8C', 'Woodward Governor'),
(4723, '00:12:8D', 'STB Datenservice GmbH'),
(4724, '00:12:8E', 'Q-Free ASA'),
(4725, '00:12:8F', 'Montilio'),
(4726, '00:12:90', 'KYOWA Electric &amp; Machinery Corp.'),
(4727, '00:12:91', 'KWS Computersysteme GmbH'),
(4728, '00:12:92', 'Griffin Technology'),
(4729, '00:12:93', 'GE Energy'),
(4730, '00:12:94', 'SUMITOMO ELECTRIC DEVICE INNOVATIONS, INC'),
(4731, '00:12:95', 'Aiware Inc.'),
(4732, '00:12:96', 'Addlogix'),
(4733, '00:12:97', 'O2Micro, Inc.'),
(4734, '00:12:98', 'MICO ELECTRIC(SHENZHEN) LIMITED'),
(4735, '00:12:99', 'Ktech Telecommunications Inc'),
(4736, '00:12:9A', 'IRT Electronics Pty Ltd'),
(4737, '00:12:9B', 'E2S Electronic Engineering Solutions, S.L.'),
(4738, '00:12:9C', 'Yulinet'),
(4739, '00:12:9D', 'First International Computer do Brasil'),
(4740, '00:12:9E', 'Surf Communications Inc.'),
(4741, '00:12:9F', 'RAE Systems'),
(4742, '00:12:A0', 'NeoMeridian Sdn Bhd'),
(4743, '00:12:A1', 'BluePacket Communications Co., Ltd.'),
(4744, '00:12:A2', 'VITA'),
(4745, '00:12:A3', 'Trust International B.V.'),
(4746, '00:12:A4', 'ThingMagic, LLC'),
(4747, '00:12:A5', 'Stargen, Inc.'),
(4748, '00:12:A6', 'Dolby Australia'),
(4749, '00:12:A7', 'ISR TECHNOLOGIES Inc'),
(4750, '00:12:A8', 'intec GmbH'),
(4751, '00:12:A9', '3Com Ltd'),
(4752, '00:12:AA', 'IEE, Inc.'),
(4753, '00:12:AB', 'WiLife, Inc.'),
(4754, '00:12:AC', 'ONTIMETEK INC.'),
(4755, '00:12:AD', 'IDS GmbH'),
(4756, '00:12:AE', 'HLS HARD-LINE Solutions Inc.'),
(4757, '00:12:AF', 'ELPRO Technologies'),
(4758, '00:12:B0', 'Efore Oyj   (Plc)'),
(4759, '00:12:B1', 'Dai Nippon Printing Co., Ltd'),
(4760, '00:12:B2', 'AVOLITES LTD.'),
(4761, '00:12:B3', 'Advance Wireless Technology Corp.'),
(4762, '00:12:B4', 'Work Microwave GmbH'),
(4763, '00:12:B5', 'Vialta, Inc.'),
(4764, '00:12:B6', 'Santa Barbara Infrared, Inc.'),
(4765, '00:12:B7', 'PTW Freiburg'),
(4766, '00:12:B8', 'G2 Microsystems'),
(4767, '00:12:B9', 'Fusion Digital Technology'),
(4768, '00:12:BA', 'FSI Systems, Inc.'),
(4769, '00:12:BB', 'Telecommunications Industry Association TR-41 Committee'),
(4770, '00:12:BC', 'Echolab LLC'),
(4771, '00:12:BD', 'Avantec Manufacturing Limited'),
(4772, '00:12:BE', 'Astek Corporation'),
(4773, '00:12:BF', 'Arcadyan Technology Corporation'),
(4774, '00:12:C0', 'HotLava Systems, Inc.'),
(4775, '00:12:C1', 'Check Point Software Technologies'),
(4776, '00:12:C2', 'Apex Electronics Factory'),
(4777, '00:12:C3', 'WIT S.A.'),
(4778, '00:12:C4', 'Viseon, Inc.'),
(4779, '00:12:C5', 'V-Show  Technology (China) Co.,Ltd'),
(4780, '00:12:C6', 'TGC America, Inc'),
(4781, '00:12:C7', 'SECURAY Technologies Ltd.Co.'),
(4782, '00:12:C8', 'Perfect tech'),
(4783, '00:12:C9', 'ARRIS Group, Inc.'),
(4784, '00:12:CA', 'Mechatronic Brick Aps'),
(4785, '00:12:CB', 'CSS Inc.'),
(4786, '00:12:CC', 'Bitatek CO., LTD'),
(4787, '00:12:CD', 'ASEM SpA'),
(4788, '00:12:CE', 'Advanced Cybernetics Group'),
(4789, '00:12:CF', 'Accton Technology Corporation'),
(4790, '00:12:D0', 'Gossen-Metrawatt-GmbH'),
(4791, '00:12:D1', 'Texas Instruments Inc'),
(4792, '00:12:D2', 'Texas Instruments'),
(4793, '00:12:D3', 'Zetta Systems, Inc.'),
(4794, '00:12:D4', 'Princeton Technology, Ltd'),
(4795, '00:12:D5', 'Motion Reality Inc.'),
(4796, '00:12:D6', 'Jiangsu Yitong High-Tech Co.,Ltd'),
(4797, '00:12:D7', 'Invento Networks, Inc.'),
(4798, '00:12:D8', 'International Games System Co., Ltd.'),
(4799, '00:12:D9', 'CISCO SYSTEMS, INC.'),
(4800, '00:12:DA', 'CISCO SYSTEMS, INC.'),
(4801, '00:12:DB', 'ZIEHL industrie-elektronik GmbH + Co KG'),
(4802, '00:12:DC', 'SunCorp Industrial Limited'),
(4803, '00:12:DD', 'Shengqu Information Technology (Shanghai) Co., Ltd.'),
(4804, '00:12:DE', 'Radio Components Sweden AB'),
(4805, '00:12:DF', 'Novomatic AG'),
(4806, '00:12:E0', 'Codan Limited'),
(4807, '00:12:E1', 'Alliant Networks, Inc'),
(4808, '00:12:E2', 'ALAXALA Networks Corporation'),
(4809, '00:12:E3', 'Agat-RT, Ltd.'),
(4810, '00:12:E4', 'ZIEHL industrie-electronik GmbH + Co KG'),
(4811, '00:12:E5', 'Time America, Inc.'),
(4812, '00:12:E6', 'SPECTEC COMPUTER CO., LTD.'),
(4813, '00:12:E7', 'Projectek Networking Electronics Corp.'),
(4814, '00:12:E8', 'Fraunhofer IMS'),
(4815, '00:12:E9', 'Abbey Systems Ltd'),
(4816, '00:12:EA', 'Trane'),
(4817, '00:12:EB', 'PDH Solutions, LLC'),
(4818, '00:12:EC', 'Movacolor b.v.'),
(4819, '00:12:ED', 'AVG Advanced Technologies'),
(4820, '00:12:EE', 'Sony Ericsson Mobile Communications AB'),
(4821, '00:12:EF', 'OneAccess SA'),
(4822, '00:12:F0', 'Intel Corporate'),
(4823, '00:12:F1', 'IFOTEC'),
(4824, '00:12:F2', 'Brocade Communications Systems, Inc'),
(4825, '00:12:F3', 'connectBlue AB'),
(4826, '00:12:F4', 'Belco International Co.,Ltd.'),
(4827, '00:12:F5', 'Imarda New Zealand Limited'),
(4828, '00:12:F6', 'MDK CO.,LTD.'),
(4829, '00:12:F7', 'Xiamen Xinglian Electronics Co., Ltd.'),
(4830, '00:12:F8', 'WNI Resources, LLC'),
(4831, '00:12:F9', 'URYU SEISAKU, LTD.'),
(4832, '00:12:FA', 'THX LTD'),
(4833, '00:12:FB', 'Samsung Electronics'),
(4834, '00:12:FC', 'PLANET System Co.,LTD'),
(4835, '00:12:FD', 'OPTIMUS IC S.A.'),
(4836, '00:12:FE', 'Lenovo Mobile Communication Technology Ltd.'),
(4837, '00:12:FF', 'Lely Industries N.V.'),
(4838, '00:13:00', 'IT-FACTORY, INC.'),
(4839, '00:13:01', 'IronGate S.L.'),
(4840, '00:13:02', 'Intel Corporate'),
(4841, '00:13:03', 'GateConnect'),
(4842, '00:13:04', 'Flaircomm Technologies Co. LTD'),
(4843, '00:13:05', 'Epicom, Inc.'),
(4844, '00:13:06', 'Always On Wireless'),
(4845, '00:13:07', 'Paravirtual Corporation'),
(4846, '00:13:08', 'Nuvera Fuel Cells'),
(4847, '00:13:09', 'Ocean Broadband Networks'),
(4848, '00:13:0A', 'Nortel'),
(4849, '00:13:0B', 'Mextal B.V.'),
(4850, '00:13:0C', 'HF System Corporation'),
(4851, '00:13:0D', 'GALILEO AVIONICA'),
(4852, '00:13:0E', 'Focusrite Audio Engineering Limited'),
(4853, '00:13:0F', 'EGEMEN Bilgisayar Muh San ve Tic LTD STI'),
(4854, '00:13:10', 'Cisco-Linksys, LLC'),
(4855, '00:13:11', 'ARRIS International'),
(4856, '00:13:12', 'Amedia Networks Inc.'),
(4857, '00:13:13', 'GuangZhou Post &amp; Telecom Equipment ltd'),
(4858, '00:13:14', 'Asiamajor Inc.'),
(4859, '00:13:15', 'SONY Computer Entertainment inc,'),
(4860, '00:13:16', 'L-S-B Broadcast Technologies GmbH'),
(4861, '00:13:17', 'GN Netcom as'),
(4862, '00:13:18', 'DGSTATION Co., Ltd.'),
(4863, '00:13:19', 'CISCO SYSTEMS, INC.'),
(4864, '00:13:1A', 'CISCO SYSTEMS, INC.'),
(4865, '00:13:1B', 'BeCell Innovations Corp.'),
(4866, '00:13:1C', 'LiteTouch, Inc.'),
(4867, '00:13:1D', 'Scanvaegt International A/S'),
(4868, '00:13:1E', 'Peiker acustic GmbH &amp; Co. KG'),
(4869, '00:13:1F', 'NxtPhase T&amp;D, Corp.'),
(4870, '00:13:20', 'Intel Corporate'),
(4871, '00:13:21', 'Hewlett-Packard Company'),
(4872, '00:13:22', 'DAQ Electronics, Inc.'),
(4873, '00:13:23', 'Cap Co., Ltd.'),
(4874, '00:13:24', 'Schneider Electric Ultra Terminal'),
(4875, '00:13:25', 'Cortina Systems Inc'),
(4876, '00:13:26', 'ECM Systems Ltd'),
(4877, '00:13:27', 'Data Acquisitions limited'),
(4878, '00:13:28', 'Westech Korea Inc.,'),
(4879, '00:13:29', 'VSST Co., LTD'),
(4880, '00:13:2A', 'Sitronics Telecom Solutions'),
(4881, '00:13:2B', 'Phoenix Digital'),
(4882, '00:13:2C', 'MAZ Brandenburg GmbH'),
(4883, '00:13:2D', 'iWise Communications'),
(4884, '00:13:2E', 'ITian Coporation'),
(4885, '00:13:2F', 'Interactek'),
(4886, '00:13:30', 'EURO PROTECTION SURVEILLANCE'),
(4887, '00:13:31', 'CellPoint Connect'),
(4888, '00:13:32', 'Beijing Topsec Network Security Technology Co., Ltd.'),
(4889, '00:13:33', 'BaudTec Corporation'),
(4890, '00:13:34', 'Arkados, Inc.'),
(4891, '00:13:35', 'VS Industry Berhad'),
(4892, '00:13:36', 'Tianjin 712 Communication Broadcasting co., ltd.'),
(4893, '00:13:37', 'Orient Power Home Network Ltd.'),
(4894, '00:13:38', 'FRESENIUS-VIAL'),
(4895, '00:13:39', 'CCV Deutschland GmbH'),
(4896, '00:13:3A', 'VadaTech Inc.'),
(4897, '00:13:3B', 'Speed Dragon Multimedia Limited'),
(4898, '00:13:3C', 'QUINTRON SYSTEMS INC.'),
(4899, '00:13:3D', 'Micro Memory Curtiss Wright Co'),
(4900, '00:13:3E', 'MetaSwitch'),
(4901, '00:13:3F', 'Eppendorf Instrumente GmbH'),
(4902, '00:13:40', 'AD.EL s.r.l.'),
(4903, '00:13:41', 'Shandong New Beiyang Information Technology Co.,Ltd'),
(4904, '00:13:42', 'Vision Research, Inc.'),
(4905, '00:13:43', 'Matsushita Electronic Components (Europe) GmbH'),
(4906, '00:13:44', 'Fargo Electronics Inc.'),
(4907, '00:13:45', 'Eaton Corporation'),
(4908, '00:13:46', 'D-Link Corporation'),
(4909, '00:13:47', 'Red Lion Controls, LP'),
(4910, '00:13:48', 'Artila Electronics Co., Ltd.'),
(4911, '00:13:49', 'ZyXEL Communications Corporation'),
(4912, '00:13:4A', 'Engim, Inc.'),
(4913, '00:13:4B', 'ToGoldenNet Technology Inc.'),
(4914, '00:13:4C', 'YDT Technology International'),
(4915, '00:13:4D', 'Inepro BV'),
(4916, '00:13:4E', 'Valox Systems, Inc.'),
(4917, '00:13:4F', 'Tranzeo Wireless Technologies Inc.'),
(4918, '00:13:50', 'Silver Spring Networks, Inc'),
(4919, '00:13:51', 'Niles Audio Corporation'),
(4920, '00:13:52', 'Naztec, Inc.'),
(4921, '00:13:53', 'HYDAC Filtertechnik GMBH'),
(4922, '00:13:54', 'Zcomax Technologies, Inc.'),
(4923, '00:13:55', 'TOMEN Cyber-business Solutions, Inc.'),
(4924, '00:13:56', 'FLIR Radiation Inc'),
(4925, '00:13:57', 'Soyal Technology Co., Ltd.'),
(4926, '00:13:58', 'Realm Systems, Inc.'),
(4927, '00:13:59', 'ProTelevision Technologies A/S'),
(4928, '00:13:5A', 'Project T&amp;E Limited'),
(4929, '00:13:5B', 'PanelLink Cinema, LLC'),
(4930, '00:13:5C', 'OnSite Systems, Inc.'),
(4931, '00:13:5D', 'NTTPC Communications, Inc.'),
(4932, '00:13:5E', 'EAB/RWI/K'),
(4933, '00:13:5F', 'CISCO SYSTEMS, INC.'),
(4934, '00:13:60', 'CISCO SYSTEMS, INC.'),
(4935, '00:13:61', 'Biospace Co., Ltd.'),
(4936, '00:13:62', 'ShinHeung Precision Co., Ltd.'),
(4937, '00:13:63', 'Verascape, Inc.'),
(4938, '00:13:64', 'Paradigm Technology Inc..'),
(4939, '00:13:65', 'Nortel'),
(4940, '00:13:66', 'Neturity Technologies Inc.'),
(4941, '00:13:67', 'Narayon. Co., Ltd.'),
(4942, '00:13:68', 'Saab Danmark A/S'),
(4943, '00:13:69', 'Honda Electron Co., LED.'),
(4944, '00:13:6A', 'Hach Lange Sarl'),
(4945, '00:13:6B', 'E-TEC'),
(4946, '00:13:6C', 'TomTom'),
(4947, '00:13:6D', 'Tentaculus AB'),
(4948, '00:13:6E', 'Techmetro Corp.'),
(4949, '00:13:6F', 'PacketMotion, Inc.'),
(4950, '00:13:70', 'Nokia Danmark A/S'),
(4951, '00:13:71', 'ARRIS Group, Inc.'),
(4952, '00:13:72', 'Dell Inc'),
(4953, '00:13:73', 'BLwave Electronics Co., Ltd'),
(4954, '00:13:74', 'Atheros Communications, Inc.'),
(4955, '00:13:75', 'American Security Products Co.'),
(4956, '00:13:76', 'Tabor Electronics Ltd.'),
(4957, '00:13:77', 'Samsung Electronics CO., LTD'),
(4958, '00:13:78', 'Qsan Technology, Inc.'),
(4959, '00:13:79', 'PONDER INFORMATION INDUSTRIES LTD.'),
(4960, '00:13:7A', 'Netvox Technology Co., Ltd.'),
(4961, '00:13:7B', 'Movon Corporation'),
(4962, '00:13:7C', 'Kaicom co., Ltd.'),
(4963, '00:13:7D', 'Dynalab, Inc.'),
(4964, '00:13:7E', 'CorEdge Networks, Inc.'),
(4965, '00:13:7F', 'CISCO SYSTEMS, INC.'),
(4966, '00:13:80', 'CISCO SYSTEMS, INC.'),
(4967, '00:13:81', 'CHIPS &amp; Systems, Inc.'),
(4968, '00:13:82', 'Cetacea Networks Corporation'),
(4969, '00:13:83', 'Application Technologies and Engineering Research Laboratory'),
(4970, '00:13:84', 'Advanced Motion Controls'),
(4971, '00:13:85', 'Add-On Technology Co., LTD.'),
(4972, '00:13:86', 'ABB Inc./Totalflow'),
(4973, '00:13:87', '27M Technologies AB'),
(4974, '00:13:88', 'WiMedia Alliance'),
(4975, '00:13:89', 'Redes de Telefon&iacute;a M&oacute;vil S.A.'),
(4976, '00:13:8A', 'QINGDAO GOERTEK ELECTRONICS CO.,LTD.'),
(4977, '00:13:8B', 'Phantom Technologies LLC'),
(4978, '00:13:8C', 'Kumyoung.Co.Ltd'),
(4979, '00:13:8D', 'Kinghold'),
(4980, '00:13:8E', 'FOAB Elektronik AB'),
(4981, '00:13:8F', 'Asiarock Incorporation'),
(4982, '00:13:90', 'Termtek Computer Co., Ltd'),
(4983, '00:13:91', 'OUEN CO.,LTD.'),
(4984, '00:13:92', 'Ruckus Wireless'),
(4985, '00:13:93', 'Panta Systems, Inc.'),
(4986, '00:13:94', 'Infohand Co.,Ltd'),
(4987, '00:13:95', 'congatec AG'),
(4988, '00:13:96', 'Acbel Polytech Inc.'),
(4989, '00:13:97', 'Oracle Corporation'),
(4990, '00:13:98', 'TrafficSim Co.,Ltd'),
(4991, '00:13:99', 'STAC Corporation.'),
(4992, '00:13:9A', 'K-ubique ID Corp.'),
(4993, '00:13:9B', 'ioIMAGE Ltd.'),
(4994, '00:13:9C', 'Exavera Technologies, Inc.'),
(4995, '00:13:9D', 'Marvell Hispana S.L.'),
(4996, '00:13:9E', 'Ciara Technologies Inc.'),
(4997, '00:13:9F', 'Electronics Design Services, Co., Ltd.'),
(4998, '00:13:A0', 'ALGOSYSTEM Co., Ltd.'),
(4999, '00:13:A1', 'Crow Electronic Engeneering'),
(5000, '00:13:A2', 'MaxStream, Inc'),
(5001, '00:13:A3', 'Siemens Com CPE Devices'),
(5002, '00:13:A4', 'KeyEye Communications'),
(5003, '00:13:A5', 'General Solutions, LTD.'),
(5004, '00:13:A6', 'Extricom Ltd'),
(5005, '00:13:A7', 'BATTELLE MEMORIAL INSTITUTE'),
(5006, '00:13:A8', 'Tanisys Technology'),
(5007, '00:13:A9', 'Sony Corporation'),
(5008, '00:13:AA', 'ALS  &amp; TEC Ltd.'),
(5009, '00:13:AB', 'Telemotive AG'),
(5010, '00:13:AC', 'Sunmyung Electronics Co., LTD'),
(5011, '00:13:AD', 'Sendo Ltd'),
(5012, '00:13:AE', 'Radiance Technologies, Inc.'),
(5013, '00:13:AF', 'NUMA Technology,Inc.'),
(5014, '00:13:B0', 'Jablotron'),
(5015, '00:13:B1', 'Intelligent Control Systems (Asia) Pte Ltd'),
(5016, '00:13:B2', 'Carallon Limited'),
(5017, '00:13:B3', 'Ecom Communications Technology Co., Ltd.'),
(5018, '00:13:B4', 'Appear TV'),
(5019, '00:13:B5', 'Wavesat'),
(5020, '00:13:B6', 'Sling Media, Inc.'),
(5021, '00:13:B7', 'Scantech ID'),
(5022, '00:13:B8', 'RyCo Electronic Systems Limited'),
(5023, '00:13:B9', 'BM SPA'),
(5024, '00:13:BA', 'ReadyLinks Inc'),
(5025, '00:13:BB', 'Smartvue Corporation'),
(5026, '00:13:BC', 'Artimi Ltd'),
(5027, '00:13:BD', 'HYMATOM SA'),
(5028, '00:13:BE', 'Virtual Conexions'),
(5029, '00:13:BF', 'Media System Planning Corp.'),
(5030, '00:13:C0', 'Trix Tecnologia Ltda.'),
(5031, '00:13:C1', 'Asoka USA Corporation'),
(5032, '00:13:C2', 'WACOM Co.,Ltd'),
(5033, '00:13:C3', 'CISCO SYSTEMS, INC.'),
(5034, '00:13:C4', 'CISCO SYSTEMS, INC.'),
(5035, '00:13:C5', 'LIGHTRON FIBER-OPTIC DEVICES INC.'),
(5036, '00:13:C6', 'OpenGear, Inc'),
(5037, '00:13:C7', 'IONOS Co.,Ltd.'),
(5038, '00:13:C8', 'ADB Broadband Italia'),
(5039, '00:13:C9', 'Beyond Achieve Enterprises Ltd.'),
(5040, '00:13:CA', 'Pico Digital'),
(5041, '00:13:CB', 'Zenitel Norway AS'),
(5042, '00:13:CC', 'Tall Maple Systems'),
(5043, '00:13:CD', 'MTI co. LTD'),
(5044, '00:13:CE', 'Intel Corporate'),
(5045, '00:13:CF', '4Access Communications'),
(5046, '00:13:D0', 't+ Medical Ltd'),
(5047, '00:13:D1', 'KIRK telecom A/S'),
(5048, '00:13:D2', 'PAGE IBERICA, S.A.'),
(5049, '00:13:D3', 'MICRO-STAR INTERNATIONAL CO., LTD.'),
(5050, '00:13:D4', 'ASUSTek COMPUTER INC.'),
(5051, '00:13:D5', 'RuggedCom'),
(5052, '00:13:D6', 'TII NETWORK TECHNOLOGIES, INC.'),
(5053, '00:13:D7', 'SPIDCOM Technologies SA'),
(5054, '00:13:D8', 'Princeton Instruments'),
(5055, '00:13:D9', 'Matrix Product Development, Inc.'),
(5056, '00:13:DA', 'Diskware Co., Ltd'),
(5057, '00:13:DB', 'SHOEI Electric Co.,Ltd'),
(5058, '00:13:DC', 'IBTEK INC.'),
(5059, '00:13:DD', 'Abbott Diagnostics'),
(5060, '00:13:DE', 'Adapt4, LLC'),
(5061, '00:13:DF', 'Ryvor Corp.'),
(5062, '00:13:E0', 'Murata Manufacturing Co., Ltd.'),
(5063, '00:13:E1', 'Iprobe AB'),
(5064, '00:13:E2', 'GeoVision Inc.'),
(5065, '00:13:E3', 'CoVi Technologies, Inc.'),
(5066, '00:13:E4', 'YANGJAE SYSTEMS CORP.'),
(5067, '00:13:E5', 'TENOSYS, INC.'),
(5068, '00:13:E6', 'Technolution'),
(5069, '00:13:E7', 'Halcro'),
(5070, '00:13:E8', 'Intel Corporate'),
(5071, '00:13:E9', 'VeriWave, Inc.'),
(5072, '00:13:EA', 'Kamstrup A/S'),
(5073, '00:13:EB', 'Sysmaster Corporation'),
(5074, '00:13:EC', 'Sunbay Software AG'),
(5075, '00:13:ED', 'PSIA'),
(5076, '00:13:EE', 'JBX Designs Inc.'),
(5077, '00:13:EF', 'Kingjon Digital Technology Co.,Ltd'),
(5078, '00:13:F0', 'Wavefront Semiconductor'),
(5079, '00:13:F1', 'AMOD Technology Co., Ltd.'),
(5080, '00:13:F2', 'Klas Ltd'),
(5081, '00:13:F3', 'Giga-byte Communications Inc.'),
(5082, '00:13:F4', 'Psitek (Pty) Ltd'),
(5083, '00:13:F5', 'Akimbi Systems'),
(5084, '00:13:F6', 'Cintech'),
(5085, '00:13:F7', 'SMC Networks, Inc.'),
(5086, '00:13:F8', 'Dex Security Solutions'),
(5087, '00:13:F9', 'Cavera Systems'),
(5088, '00:13:FA', 'LifeSize Communications, Inc'),
(5089, '00:13:FB', 'RKC INSTRUMENT INC.'),
(5090, '00:13:FC', 'SiCortex, Inc'),
(5091, '00:13:FD', 'Nokia Danmark A/S'),
(5092, '00:13:FE', 'GRANDTEC ELECTRONIC CORP.'),
(5093, '00:13:FF', 'Dage-MTI of MC, Inc.'),
(5094, '00:14:00', 'MINERVA KOREA CO., LTD'),
(5095, '00:14:01', 'Rivertree Networks Corp.'),
(5096, '00:14:02', 'kk-electronic a/s'),
(5097, '00:14:03', 'Renasis, LLC'),
(5098, '00:14:04', 'ARRIS Group, Inc.'),
(5099, '00:14:05', 'OpenIB, Inc.'),
(5100, '00:14:06', 'Go Networks'),
(5101, '00:14:07', 'Sperian Protection Instrumentation'),
(5102, '00:14:08', 'Eka Systems Inc.'),
(5103, '00:14:09', 'MAGNETI MARELLI   S.E. S.p.A.'),
(5104, '00:14:0A', 'WEPIO Co., Ltd.'),
(5105, '00:14:0B', 'FIRST INTERNATIONAL COMPUTER, INC.'),
(5106, '00:14:0C', 'GKB CCTV CO., LTD.'),
(5107, '00:14:0D', 'Nortel'),
(5108, '00:14:0E', 'Nortel'),
(5109, '00:14:0F', 'Federal State Unitary Enterprise Leningrad R&amp;D Institute of'),
(5110, '00:14:10', 'Suzhou Keda Technology CO.,Ltd'),
(5111, '00:14:11', 'Deutschmann Automation GmbH &amp; Co. KG'),
(5112, '00:14:12', 'S-TEC electronics AG'),
(5113, '00:14:13', 'Trebing &amp; Himstedt Proze&szlig;automation GmbH &amp; Co. KG'),
(5114, '00:14:14', 'Jumpnode Systems LLC.'),
(5115, '00:14:15', 'Intec Automation Inc.'),
(5116, '00:14:16', 'Scosche Industries, Inc.'),
(5117, '00:14:17', 'RSE Informations Technologie GmbH'),
(5118, '00:14:18', 'C4Line'),
(5119, '00:14:19', 'SIDSA'),
(5120, '00:14:1A', 'DEICY CORPORATION'),
(5121, '00:14:1B', 'CISCO SYSTEMS, INC.'),
(5122, '00:14:1C', 'CISCO SYSTEMS, INC.'),
(5123, '00:14:1D', 'LTi DRIVES GmbH'),
(5124, '00:14:1E', 'P.A. Semi, Inc.'),
(5125, '00:14:1F', 'SunKwang Electronics Co., Ltd'),
(5126, '00:14:20', 'G-Links networking company'),
(5127, '00:14:21', 'Total Wireless Technologies Pte. Ltd.'),
(5128, '00:14:22', 'Dell Inc'),
(5129, '00:14:23', 'J-S Co. NEUROCOM'),
(5130, '00:14:24', 'Merry Electrics CO., LTD.'),
(5131, '00:14:25', 'Galactic Computing Corp.'),
(5132, '00:14:26', 'NL Technology'),
(5133, '00:14:27', 'JazzMutant'),
(5134, '00:14:28', 'Vocollect, Inc'),
(5135, '00:14:29', 'V Center Technologies Co., Ltd.'),
(5136, '00:14:2A', 'Elitegroup Computer System Co., Ltd'),
(5137, '00:14:2B', 'Edata Communication Inc.'),
(5138, '00:14:2C', 'Koncept International, Inc.'),
(5139, '00:14:2D', 'Toradex AG'),
(5140, '00:14:2E', '77 Elektronika Kft.'),
(5141, '00:14:2F', 'WildPackets'),
(5142, '00:14:30', 'ViPowER, Inc'),
(5143, '00:14:31', 'PDL Electronics Ltd'),
(5144, '00:14:32', 'Tarallax Wireless, Inc.'),
(5145, '00:14:33', 'Empower Technologies(Canada) Inc.'),
(5146, '00:14:34', 'Keri Systems, Inc'),
(5147, '00:14:35', 'CityCom Corp.'),
(5148, '00:14:36', 'Qwerty Elektronik AB'),
(5149, '00:14:37', 'GSTeletech Co.,Ltd.'),
(5150, '00:14:38', 'Hewlett-Packard Company'),
(5151, '00:14:39', 'Blonder Tongue Laboratories, Inc.'),
(5152, '00:14:3A', 'RAYTALK INTERNATIONAL SRL'),
(5153, '00:14:3B', 'Sensovation AG'),
(5154, '00:14:3C', 'Rheinmetall Canada Inc.'),
(5155, '00:14:3D', 'Aevoe Inc.'),
(5156, '00:14:3E', 'AirLink Communications, Inc.'),
(5157, '00:14:3F', 'Hotway Technology Corporation'),
(5158, '00:14:40', 'ATOMIC Corporation'),
(5159, '00:14:41', 'Innovation Sound Technology Co., LTD.'),
(5160, '00:14:42', 'ATTO CORPORATION'),
(5161, '00:14:43', 'Consultronics Europe Ltd'),
(5162, '00:14:44', 'Grundfos Holding'),
(5163, '00:14:45', 'Telefon-Gradnja d.o.o.'),
(5164, '00:14:46', 'SuperVision Solutions LLC'),
(5165, '00:14:47', 'BOAZ Inc.'),
(5166, '00:14:48', 'Inventec Multimedia &amp; Telecom Corporation'),
(5167, '00:14:49', 'Sichuan Changhong Electric Ltd.'),
(5168, '00:14:4A', 'Taiwan Thick-Film Ind. Corp.'),
(5169, '00:14:4B', 'Hifn, Inc.'),
(5170, '00:14:4C', 'General Meters Corp.'),
(5171, '00:14:4D', 'Intelligent Systems'),
(5172, '00:14:4E', 'SRISA'),
(5173, '00:14:4F', 'Oracle Corporation'),
(5174, '00:14:50', 'Heim Systems GmbH'),
(5175, '00:14:51', 'Apple'),
(5176, '00:14:52', 'CALCULEX,INC.'),
(5177, '00:14:53', 'ADVANTECH TECHNOLOGIES CO.,LTD'),
(5178, '00:14:54', 'Symwave'),
(5179, '00:14:55', 'Coder Electronics Corporation'),
(5180, '00:14:56', 'Edge Products'),
(5181, '00:14:57', 'T-VIPS AS'),
(5182, '00:14:58', 'HS Automatic ApS'),
(5183, '00:14:59', 'Moram Co., Ltd.'),
(5184, '00:14:5A', 'Neratec Solutions AG'),
(5185, '00:14:5B', 'SeekerNet Inc.'),
(5186, '00:14:5C', 'Intronics B.V.'),
(5187, '00:14:5D', 'WJ Communications, Inc.'),
(5188, '00:14:5E', 'IBM Corp'),
(5189, '00:14:5F', 'ADITEC CO. LTD'),
(5190, '00:14:60', 'Kyocera Wireless Corp.'),
(5191, '00:14:61', 'CORONA CORPORATION'),
(5192, '00:14:62', 'Digiwell Technology, inc'),
(5193, '00:14:63', 'IDCS N.V.'),
(5194, '00:14:64', 'Cryptosoft'),
(5195, '00:14:65', 'Novo Nordisk A/S'),
(5196, '00:14:66', 'Kleinhenz Elektronik GmbH'),
(5197, '00:14:67', 'ArrowSpan Inc.'),
(5198, '00:14:68', 'CelPlan International, Inc.'),
(5199, '00:14:69', 'CISCO SYSTEMS, INC.'),
(5200, '00:14:6A', 'CISCO SYSTEMS, INC.'),
(5201, '00:14:6B', 'Anagran, Inc.'),
(5202, '00:14:6C', 'Netgear Inc.'),
(5203, '00:14:6D', 'RF Technologies'),
(5204, '00:14:6E', 'H. Stoll GmbH &amp; Co. KG'),
(5205, '00:14:6F', 'Kohler Co'),
(5206, '00:14:70', 'Prokom Software SA'),
(5207, '00:14:71', 'Eastern Asia Technology Limited'),
(5208, '00:14:72', 'China Broadband Wireless IP Standard Group'),
(5209, '00:14:73', 'Bookham Inc'),
(5210, '00:14:74', 'K40 Electronics'),
(5211, '00:14:75', 'Wiline Networks, Inc.'),
(5212, '00:14:76', 'MultiCom Industries Limited'),
(5213, '00:14:77', 'Nertec  Inc.'),
(5214, '00:14:78', 'ShenZhen TP-LINK Technologies Co., Ltd.'),
(5215, '00:14:79', 'NEC Magnus Communications,Ltd.'),
(5216, '00:14:7A', 'Eubus GmbH'),
(5217, '00:14:7B', 'Iteris, Inc.'),
(5218, '00:14:7C', '3Com Ltd'),
(5219, '00:14:7D', 'Aeon Digital International'),
(5220, '00:14:7E', 'InnerWireless'),
(5221, '00:14:7F', 'Thomson Telecom Belgium'),
(5222, '00:14:80', 'Hitachi-LG Data Storage Korea, Inc'),
(5223, '00:14:81', 'Multilink Inc'),
(5224, '00:14:82', 'Aurora Networks'),
(5225, '00:14:83', 'eXS Inc.'),
(5226, '00:14:84', 'Cermate Technologies Inc.'),
(5227, '00:14:85', 'Giga-Byte'),
(5228, '00:14:86', 'Echo Digital Audio Corporation'),
(5229, '00:14:87', 'American Technology Integrators'),
(5230, '00:14:88', 'Akorri'),
(5231, '00:14:89', 'B15402100 - JANDEI, S.L.'),
(5232, '00:14:8A', 'Elin Ebg Traction Gmbh'),
(5233, '00:14:8B', 'Globo Electronic GmbH &amp; Co. KG'),
(5234, '00:14:8C', 'Fortress Technologies'),
(5235, '00:14:8D', 'Cubic Defense Simulation Systems'),
(5236, '00:14:8E', 'Tele Power Inc.'),
(5237, '00:14:8F', 'Protronic (Far East) Ltd.'),
(5238, '00:14:90', 'ASP Corporation'),
(5239, '00:14:91', 'Daniels Electronics Ltd. dbo Codan Rado Communications'),
(5240, '00:14:92', 'Liteon, Mobile Media Solution SBU'),
(5241, '00:14:93', 'Systimax Solutions'),
(5242, '00:14:94', 'ESU AG'),
(5243, '00:14:95', '2Wire, Inc.'),
(5244, '00:14:96', 'Phonic Corp.'),
(5245, '00:14:97', 'ZHIYUAN Eletronics co.,ltd.'),
(5246, '00:14:98', 'Viking Design Technology'),
(5247, '00:14:99', 'Helicomm Inc'),
(5248, '00:14:9A', 'ARRIS Group, Inc.'),
(5249, '00:14:9B', 'Nokota Communications, LLC'),
(5250, '00:14:9C', 'HF Company'),
(5251, '00:14:9D', 'Sound ID Inc.'),
(5252, '00:14:9E', 'UbONE Co., Ltd'),
(5253, '00:14:9F', 'System and Chips, Inc.'),
(5254, '00:14:A0', 'Accsense, Inc.'),
(5255, '00:14:A1', 'Synchronous Communication Corp'),
(5256, '00:14:A2', 'Core Micro Systems Inc.'),
(5257, '00:14:A3', 'Vitelec BV'),
(5258, '00:14:A4', 'Hon Hai Precision Ind. Co., Ltd.'),
(5259, '00:14:A5', 'Gemtek Technology Co., Ltd.'),
(5260, '00:14:A6', 'Teranetics, Inc.'),
(5261, '00:14:A7', 'Nokia Danmark A/S'),
(5262, '00:14:A8', 'CISCO SYSTEMS, INC.'),
(5263, '00:14:A9', 'CISCO SYSTEMS, INC.'),
(5264, '00:14:AA', 'Ashly Audio, Inc.'),
(5265, '00:14:AB', 'Senhai Electronic Technology Co., Ltd.'),
(5266, '00:14:AC', 'Bountiful WiFi'),
(5267, '00:14:AD', 'Gassner Wiege- und Me&szlig;technik GmbH'),
(5268, '00:14:AE', 'Wizlogics Co., Ltd.'),
(5269, '00:14:AF', 'Datasym POS Inc.'),
(5270, '00:14:B0', 'Naeil Community'),
(5271, '00:14:B1', 'Axell Wireless Limited'),
(5272, '00:14:B2', 'mCubelogics Corporation'),
(5273, '00:14:B3', 'CoreStar International Corp'),
(5274, '00:14:B4', 'General Dynamics United Kingdom Ltd'),
(5275, '00:14:B5', 'PHYSIOMETRIX,INC'),
(5276, '00:14:B6', 'Enswer Technology Inc.'),
(5277, '00:14:B7', 'AR Infotek Inc.'),
(5278, '00:14:B8', 'Hill-Rom'),
(5279, '00:14:B9', 'MSTAR SEMICONDUCTOR'),
(5280, '00:14:BA', 'Carvers SA de CV'),
(5281, '00:14:BB', 'Open Interface North America'),
(5282, '00:14:BC', 'SYNECTIC TELECOM EXPORTS PVT. LTD.'),
(5283, '00:14:BD', 'incNETWORKS, Inc'),
(5284, '00:14:BE', 'Wink communication technology CO.LTD'),
(5285, '00:14:BF', 'Cisco-Linksys LLC'),
(5286, '00:14:C0', 'Symstream Technology Group Ltd'),
(5287, '00:14:C1', 'U.S. Robotics Corporation'),
(5288, '00:14:C2', 'Hewlett-Packard Company'),
(5289, '00:14:C3', 'Seagate Technology'),
(5290, '00:14:C4', 'Vitelcom Mobile Technology'),
(5291, '00:14:C5', 'Alive Technologies Pty Ltd'),
(5292, '00:14:C6', 'Quixant Ltd'),
(5293, '00:14:C7', 'Nortel'),
(5294, '00:14:C8', 'Contemporary Research Corp'),
(5295, '00:14:C9', 'Brocade Communications Systems, Inc.'),
(5296, '00:14:CA', 'Key Radio Systems Limited'),
(5297, '00:14:CB', 'LifeSync Corporation'),
(5298, '00:14:CC', 'Zetec, Inc.'),
(5299, '00:14:CD', 'DigitalZone Co., Ltd.'),
(5300, '00:14:CE', 'NF CORPORATION'),
(5301, '00:14:CF', 'INVISIO Communications'),
(5302, '00:14:D0', 'BTI Systems Inc.'),
(5303, '00:14:D1', 'TRENDnet'),
(5304, '00:14:D2', 'Kyuden Technosystems Corporation'),
(5305, '00:14:D3', 'SEPSA'),
(5306, '00:14:D4', 'K Technology Corporation'),
(5307, '00:14:D5', 'Datang Telecom Technology CO. , LCD,Optical Communication Br'),
(5308, '00:14:D6', 'Jeongmin Electronics Co.,Ltd.'),
(5309, '00:14:D7', 'Datastore Technology Corp'),
(5310, '00:14:D8', 'bio-logic SA'),
(5311, '00:14:D9', 'IP Fabrics, Inc.'),
(5312, '00:14:DA', 'Huntleigh Healthcare'),
(5313, '00:14:DB', 'Elma Trenew Electronic GmbH'),
(5314, '00:14:DC', 'Communication System Design &amp; Manufacturing (CSDM)'),
(5315, '00:14:DD', 'Covergence Inc.'),
(5316, '00:14:DE', 'Sage Instruments Inc.'),
(5317, '00:14:DF', 'HI-P Tech Corporation'),
(5318, '00:14:E0', 'LET\'S Corporation'),
(5319, '00:14:E1', 'Data Display AG'),
(5320, '00:14:E2', 'datacom systems inc.'),
(5321, '00:14:E3', 'mm-lab GmbH'),
(5322, '00:14:E4', 'infinias, LLC'),
(5323, '00:14:E5', 'Alticast'),
(5324, '00:14:E6', 'AIM Infrarotmodule GmbH'),
(5325, '00:14:E7', 'Stolinx,. Inc'),
(5326, '00:14:E8', 'ARRIS Group, Inc.'),
(5327, '00:14:E9', 'Nortech International'),
(5328, '00:14:EA', 'S Digm Inc. (Safe Paradigm Inc.)'),
(5329, '00:14:EB', 'AwarePoint Corporation'),
(5330, '00:14:EC', 'Acro Telecom'),
(5331, '00:14:ED', 'Airak, Inc.'),
(5332, '00:14:EE', 'Western Digital Technologies, Inc.'),
(5333, '00:14:EF', 'TZero Technologies, Inc.'),
(5334, '00:14:F0', 'Business Security OL AB'),
(5335, '00:14:F1', 'CISCO SYSTEMS, INC.'),
(5336, '00:14:F2', 'CISCO SYSTEMS, INC.'),
(5337, '00:14:F3', 'ViXS Systems Inc'),
(5338, '00:14:F4', 'DekTec Digital Video B.V.'),
(5339, '00:14:F5', 'OSI Security Devices'),
(5340, '00:14:F6', 'Juniper Networks, Inc.'),
(5341, '00:14:F7', 'CREVIS Co., LTD'),
(5342, '00:14:F8', 'Scientific Atlanta'),
(5343, '00:14:F9', 'Vantage Controls'),
(5344, '00:14:FA', 'AsGa S.A.'),
(5345, '00:14:FB', 'Technical Solutions Inc.'),
(5346, '00:14:FC', 'Extandon, Inc.'),
(5347, '00:14:FD', 'Thecus Technology Corp.'),
(5348, '00:14:FE', 'Artech Electronics'),
(5349, '00:14:FF', 'Precise Automation, Inc.'),
(5350, '00:15:00', 'Intel Corporate'),
(5351, '00:15:01', 'LexBox'),
(5352, '00:15:02', 'BETA tech'),
(5353, '00:15:03', 'PROFIcomms s.r.o.'),
(5354, '00:15:04', 'GAME PLUS CO., LTD.'),
(5355, '00:15:05', 'Actiontec Electronics, Inc'),
(5356, '00:15:06', 'Neo Photonics'),
(5357, '00:15:07', 'Renaissance Learning Inc'),
(5358, '00:15:08', 'Global Target Enterprise Inc'),
(5359, '00:15:09', 'Plus Technology Co., Ltd'),
(5360, '00:15:0A', 'Sonoa Systems, Inc'),
(5361, '00:15:0B', 'SAGE INFOTECH LTD.'),
(5362, '00:15:0C', 'AVM GmbH'),
(5363, '00:15:0D', 'Hoana Medical, Inc.'),
(5364, '00:15:0E', 'OPENBRAIN TECHNOLOGIES CO., LTD.'),
(5365, '00:15:0F', 'mingjong'),
(5366, '00:15:10', 'Techsphere Co., Ltd'),
(5367, '00:15:11', 'Data Center Systems'),
(5368, '00:15:12', 'Zurich University of Applied Sciences'),
(5369, '00:15:13', 'EFS sas'),
(5370, '00:15:14', 'Hu Zhou NAVA Networks&amp;Electronics Ltd.'),
(5371, '00:15:15', 'Leipold+Co.GmbH'),
(5372, '00:15:16', 'URIEL SYSTEMS INC.'),
(5373, '00:15:17', 'Intel Corporate'),
(5374, '00:15:18', 'Shenzhen 10MOONS Technology Development CO.,Ltd'),
(5375, '00:15:19', 'StoreAge Networking Technologies'),
(5376, '00:15:1A', 'Hunter Engineering Company'),
(5377, '00:15:1B', 'Isilon Systems Inc.'),
(5378, '00:15:1C', 'LENECO'),
(5379, '00:15:1D', 'M2I CORPORATION'),
(5380, '00:15:1E', 'Ethernet Powerlink Standardization Group (EPSG)'),
(5381, '00:15:1F', 'Multivision Intelligent Surveillance (Hong Kong) Ltd'),
(5382, '00:15:20', 'Radiocrafts AS'),
(5383, '00:15:21', 'Horoquartz'),
(5384, '00:15:22', 'Dea Security'),
(5385, '00:15:23', 'Meteor Communications Corporation'),
(5386, '00:15:24', 'Numatics, Inc.'),
(5387, '00:15:25', 'Chamberlain Access Solutions'),
(5388, '00:15:26', 'Remote Technologies Inc'),
(5389, '00:15:27', 'Balboa Instruments'),
(5390, '00:15:28', 'Beacon Medical Products LLC d.b.a. BeaconMedaes'),
(5391, '00:15:29', 'N3 Corporation'),
(5392, '00:15:2A', 'Nokia GmbH'),
(5393, '00:15:2B', 'CISCO SYSTEMS, INC.'),
(5394, '00:15:2C', 'CISCO SYSTEMS, INC.'),
(5395, '00:15:2D', 'TenX Networks, LLC'),
(5396, '00:15:2E', 'PacketHop, Inc.'),
(5397, '00:15:2F', 'ARRIS Group, Inc.'),
(5398, '00:15:30', 'EMC Corporation'),
(5399, '00:15:31', 'KOCOM'),
(5400, '00:15:32', 'Consumer Technologies Group, LLC'),
(5401, '00:15:33', 'NADAM.CO.,LTD'),
(5402, '00:15:34', 'A Beltr&oacute;nica-Companhia de Comunica&ccedil;&otilde;es, Lda'),
(5403, '00:15:35', 'OTE Spa'),
(5404, '00:15:36', 'Powertech co.,Ltd'),
(5405, '00:15:37', 'Ventus Networks'),
(5406, '00:15:38', 'RFID, Inc.'),
(5407, '00:15:39', 'Technodrive SRL'),
(5408, '00:15:3A', 'Shenzhen Syscan Technology Co.,Ltd.'),
(5409, '00:15:3B', 'EMH metering GmbH &amp; Co. KG'),
(5410, '00:15:3C', 'Kprotech Co., Ltd.'),
(5411, '00:15:3D', 'ELIM PRODUCT CO.'),
(5412, '00:15:3E', 'Q-Matic Sweden AB'),
(5413, '00:15:3F', 'Alcatel Alenia Space Italia'),
(5414, '00:15:40', 'Nortel'),
(5415, '00:15:41', 'StrataLight Communications, Inc.'),
(5416, '00:15:42', 'MICROHARD S.R.L.'),
(5417, '00:15:43', 'Aberdeen Test Center'),
(5418, '00:15:44', 'coM.s.a.t. AG'),
(5419, '00:15:45', 'SEECODE Co., Ltd.'),
(5420, '00:15:46', 'ITG Worldwide Sdn Bhd'),
(5421, '00:15:47', 'AiZen Solutions Inc.'),
(5422, '00:15:48', 'CUBE TECHNOLOGIES'),
(5423, '00:15:49', 'Dixtal Biomedica Ind. Com. Ltda'),
(5424, '00:15:4A', 'WANSHIH ELECTRONIC CO., LTD'),
(5425, '00:15:4B', 'Wonde Proud Technology Co., Ltd'),
(5426, '00:15:4C', 'Saunders Electronics'),
(5427, '00:15:4D', 'Netronome Systems, Inc.'),
(5428, '00:15:4E', 'IEC'),
(5429, '00:15:4F', 'one RF Technology'),
(5430, '00:15:50', 'Nits Technology Inc'),
(5431, '00:15:51', 'RadioPulse Inc.'),
(5432, '00:15:52', 'Wi-Gear Inc.'),
(5433, '00:15:53', 'Cytyc Corporation'),
(5434, '00:15:54', 'Atalum Wireless S.A.'),
(5435, '00:15:55', 'DFM GmbH'),
(5436, '00:15:56', 'SAGEM COMMUNICATION'),
(5437, '00:15:57', 'Olivetti'),
(5438, '00:15:58', 'FOXCONN'),
(5439, '00:15:59', 'Securaplane Technologies, Inc.'),
(5440, '00:15:5A', 'DAINIPPON PHARMACEUTICAL CO., LTD.'),
(5441, '00:15:5B', 'Sampo Corporation'),
(5442, '00:15:5C', 'Dresser Wayne'),
(5443, '00:15:5D', 'Microsoft Corporation'),
(5444, '00:15:5E', 'Morgan Stanley'),
(5445, '00:15:5F', 'GreenPeak Technologies'),
(5446, '00:15:60', 'Hewlett-Packard Company'),
(5447, '00:15:61', 'JJPlus Corporation'),
(5448, '00:15:62', 'CISCO SYSTEMS, INC.'),
(5449, '00:15:63', 'CISCO SYSTEMS, INC.'),
(5450, '00:15:64', 'BEHRINGER Spezielle Studiotechnik GmbH'),
(5451, '00:15:65', 'XIAMEN YEALINK NETWORK TECHNOLOGY CO.,LTD'),
(5452, '00:15:66', 'A-First Technology Co., Ltd.'),
(5453, '00:15:67', 'RADWIN Inc.'),
(5454, '00:15:68', 'Dilithium Networks'),
(5455, '00:15:69', 'PECO II, Inc.'),
(5456, '00:15:6A', 'DG2L Technologies Pvt. Ltd.'),
(5457, '00:15:6B', 'Perfisans Networks Corp.'),
(5458, '00:15:6C', 'SANE SYSTEM CO., LTD'),
(5459, '00:15:6D', 'Ubiquiti Networks Inc.'),
(5460, '00:15:6E', 'A. W. Communication Systems Ltd'),
(5461, '00:15:6F', 'Xiranet Communications GmbH'),
(5462, '00:15:70', 'Zebra Technologies Inc'),
(5463, '00:15:71', 'Nolan Systems'),
(5464, '00:15:72', 'Red-Lemon'),
(5465, '00:15:73', 'NewSoft  Technology Corporation'),
(5466, '00:15:74', 'Horizon Semiconductors Ltd.'),
(5467, '00:15:75', 'Nevis Networks Inc.'),
(5468, '00:15:76', 'LABiTec - Labor Biomedical Technologies GmbH'),
(5469, '00:15:77', 'Allied Telesis'),
(5470, '00:15:78', 'Audio / Video Innovations'),
(5471, '00:15:79', 'Lunatone Industrielle Elektronik GmbH'),
(5472, '00:15:7A', 'Telefin S.p.A.'),
(5473, '00:15:7B', 'Leuze electronic GmbH + Co. KG'),
(5474, '00:15:7C', 'Dave Networks, Inc.'),
(5475, '00:15:7D', 'POSDATA CO., LTD.'),
(5476, '00:15:7E', 'Weidm&uuml;ller Interface GmbH &amp; Co. KG'),
(5477, '00:15:7F', 'ChuanG International Holding CO.,LTD.'),
(5478, '00:15:80', 'U-WAY CORPORATION'),
(5479, '00:15:81', 'MAKUS Inc.'),
(5480, '00:15:82', 'Pulse Eight Limited'),
(5481, '00:15:83', 'IVT corporation'),
(5482, '00:15:84', 'Schenck Process GmbH'),
(5483, '00:15:85', 'Aonvision Technolopy Corp.'),
(5484, '00:15:86', 'Xiamen Overseas Chinese Electronic Co., Ltd.'),
(5485, '00:15:87', 'Takenaka Seisakusho Co.,Ltd'),
(5486, '00:15:88', 'Salutica Allied Solutions Sdn Bhd'),
(5487, '00:15:89', 'D-MAX Technology Co.,Ltd'),
(5488, '00:15:8A', 'SURECOM Technology Corp.'),
(5489, '00:15:8B', 'Park Air Systems Ltd'),
(5490, '00:15:8C', 'Liab ApS'),
(5491, '00:15:8D', 'Jennic Ltd'),
(5492, '00:15:8E', 'Plustek.INC'),
(5493, '00:15:8F', 'NTT Advanced Technology Corporation'),
(5494, '00:15:90', 'Hectronic GmbH'),
(5495, '00:15:91', 'RLW Inc.'),
(5496, '00:15:92', 'Facom UK Ltd (Melksham)'),
(5497, '00:15:93', 'U4EA Technologies Inc.'),
(5498, '00:15:94', 'BIXOLON CO.,LTD'),
(5499, '00:15:95', 'Quester Tangent Corporation'),
(5500, '00:15:96', 'ARRIS International'),
(5501, '00:15:97', 'AETA AUDIO SYSTEMS'),
(5502, '00:15:98', 'Kolektor group'),
(5503, '00:15:99', 'Samsung Electronics Co., LTD'),
(5504, '00:15:9A', 'ARRIS Group, Inc.'),
(5505, '00:15:9B', 'Nortel'),
(5506, '00:15:9C', 'B-KYUNG SYSTEM Co.,Ltd.'),
(5507, '00:15:9D', 'Tripp Lite'),
(5508, '00:15:9E', 'Mad Catz Interactive Inc'),
(5509, '00:15:9F', 'Terascala, Inc.'),
(5510, '00:15:A0', 'Nokia Danmark A/S'),
(5511, '00:15:A1', 'ECA-SINTERS'),
(5512, '00:15:A2', 'ARRIS International'),
(5513, '00:15:A3', 'ARRIS International'),
(5514, '00:15:A4', 'ARRIS International'),
(5515, '00:15:A5', 'DCI Co., Ltd.'),
(5516, '00:15:A6', 'Digital Electronics Products Ltd.'),
(5517, '00:15:A7', 'Robatech AG'),
(5518, '00:15:A8', 'ARRIS Group, Inc.'),
(5519, '00:15:A9', 'KWANG WOO I&amp;C CO.,LTD'),
(5520, '00:15:AA', 'Rextechnik International Co.,'),
(5521, '00:15:AB', 'PRO CO SOUND INC'),
(5522, '00:15:AC', 'Capelon AB'),
(5523, '00:15:AD', 'Accedian Networks'),
(5524, '00:15:AE', 'kyung il'),
(5525, '00:15:AF', 'AzureWave Technologies, Inc.'),
(5526, '00:15:B0', 'AUTOTELENET CO.,LTD'),
(5527, '00:15:B1', 'Ambient Corporation'),
(5528, '00:15:B2', 'Advanced Industrial Computer, Inc.'),
(5529, '00:15:B3', 'Caretech AB'),
(5530, '00:15:B4', 'Polymap  Wireless LLC'),
(5531, '00:15:B5', 'CI Network Corp.'),
(5532, '00:15:B6', 'ShinMaywa Industries, Ltd.'),
(5533, '00:15:B7', 'Toshiba'),
(5534, '00:15:B8', 'Tahoe'),
(5535, '00:15:B9', 'Samsung Electronics Co., Ltd.'),
(5536, '00:15:BA', 'iba AG'),
(5537, '00:15:BB', 'SMA Solar Technology AG'),
(5538, '00:15:BC', 'Develco'),
(5539, '00:15:BD', 'Group 4 Technology Ltd'),
(5540, '00:15:BE', 'Iqua Ltd.'),
(5541, '00:15:BF', 'technicob'),
(5542, '00:15:C0', 'DIGITAL TELEMEDIA CO.,LTD.'),
(5543, '00:15:C1', 'SONY Computer Entertainment inc,'),
(5544, '00:15:C2', '3M Germany'),
(5545, '00:15:C3', 'Ruf Telematik AG'),
(5546, '00:15:C4', 'FLOVEL CO., LTD.'),
(5547, '00:15:C5', 'Dell Inc'),
(5548, '00:15:C6', 'CISCO SYSTEMS, INC.'),
(5549, '00:15:C7', 'CISCO SYSTEMS, INC.'),
(5550, '00:15:C8', 'FlexiPanel Ltd'),
(5551, '00:15:C9', 'Gumstix, Inc'),
(5552, '00:15:CA', 'TeraRecon, Inc.'),
(5553, '00:15:CB', 'Surf Communication Solutions Ltd.'),
(5554, '00:15:CC', 'UQUEST, LTD.'),
(5555, '00:15:CD', 'Exartech International Corp.'),
(5556, '00:15:CE', 'ARRIS International'),
(5557, '00:15:CF', 'ARRIS International'),
(5558, '00:15:D0', 'ARRIS International'),
(5559, '00:15:D1', 'ARRIS Group, Inc.'),
(5560, '00:15:D2', 'Xantech Corporation'),
(5561, '00:15:D3', 'Pantech&amp;Curitel Communications, Inc.'),
(5562, '00:15:D4', 'Emitor AB'),
(5563, '00:15:D5', 'NICEVT'),
(5564, '00:15:D6', 'OSLiNK Sp. z o.o.'),
(5565, '00:15:D7', 'Reti Corporation'),
(5566, '00:15:D8', 'Interlink Electronics'),
(5567, '00:15:D9', 'PKC Electronics Oy'),
(5568, '00:15:DA', 'IRITEL A.D.'),
(5569, '00:15:DB', 'Canesta Inc.'),
(5570, '00:15:DC', 'KT&amp;C Co., Ltd.'),
(5571, '00:15:DD', 'IP Control Systems Ltd.'),
(5572, '00:15:DE', 'Nokia Danmark A/S'),
(5573, '00:15:DF', 'Clivet S.p.A.'),
(5574, '00:15:E0', 'Ericsson'),
(5575, '00:15:E1', 'Picochip Ltd'),
(5576, '00:15:E2', 'Dr.Ing. Herbert Knauer GmbH'),
(5577, '00:15:E3', 'Dream Technologies Corporation'),
(5578, '00:15:E4', 'Zimmer Elektromedizin'),
(5579, '00:15:E5', 'Cheertek Inc.'),
(5580, '00:15:E6', 'MOBILE TECHNIKA Inc.'),
(5581, '00:15:E7', 'Quantec Tontechnik'),
(5582, '00:15:E8', 'Nortel'),
(5583, '00:15:E9', 'D-Link Corporation'),
(5584, '00:15:EA', 'Tellumat (Pty) Ltd'),
(5585, '00:15:EB', 'ZTE CORPORATION'),
(5586, '00:15:EC', 'Boca Devices LLC'),
(5587, '00:15:ED', 'Fulcrum Microsystems, Inc.'),
(5588, '00:15:EE', 'Omnex Control Systems'),
(5589, '00:15:EF', 'NEC TOKIN Corporation'),
(5590, '00:15:F0', 'EGO BV'),
(5591, '00:15:F1', 'KYLINK Communications Corp.'),
(5592, '00:15:F2', 'ASUSTek COMPUTER INC.'),
(5593, '00:15:F3', 'PELTOR AB'),
(5594, '00:15:F4', 'Eventide'),
(5595, '00:15:F5', 'Sustainable Energy Systems'),
(5596, '00:15:F6', 'SCIENCE AND ENGINEERING SERVICES, INC.'),
(5597, '00:15:F7', 'Wintecronics Ltd.'),
(5598, '00:15:F8', 'Kingtronics Industrial Co. Ltd.'),
(5599, '00:15:F9', 'CISCO SYSTEMS, INC.'),
(5600, '00:15:FA', 'CISCO SYSTEMS, INC.'),
(5601, '00:15:FB', 'setex schermuly textile computer gmbh'),
(5602, '00:15:FC', 'Littelfuse Startco'),
(5603, '00:15:FD', 'Complete Media Systems'),
(5604, '00:15:FE', 'SCHILLING ROBOTICS LLC'),
(5605, '00:15:FF', 'Novatel Wireless, Inc.'),
(5606, '00:16:00', 'CelleBrite Mobile Synchronization'),
(5607, '00:16:01', 'Buffalo Inc.'),
(5608, '00:16:02', 'CEYON TECHNOLOGY CO.,LTD.'),
(5609, '00:16:03', 'COOLKSKY Co., LTD'),
(5610, '00:16:04', 'Sigpro'),
(5611, '00:16:05', 'YORKVILLE SOUND INC.'),
(5612, '00:16:06', 'Ideal Industries'),
(5613, '00:16:07', 'Curves International Inc.'),
(5614, '00:16:08', 'Sequans Communications'),
(5615, '00:16:09', 'Unitech electronics co., ltd.'),
(5616, '00:16:0A', 'SWEEX Europe BV'),
(5617, '00:16:0B', 'TVWorks LLC'),
(5618, '00:16:0C', 'LPL  DEVELOPMENT S.A. DE C.V'),
(5619, '00:16:0D', 'Be Here Corporation'),
(5620, '00:16:0E', 'Optica Technologies Inc.'),
(5621, '00:16:0F', 'BADGER METER INC'),
(5622, '00:16:10', 'Carina Technology'),
(5623, '00:16:11', 'Altecon Srl'),
(5624, '00:16:12', 'Otsuka Electronics Co., Ltd.'),
(5625, '00:16:13', 'LibreStream Technologies Inc.'),
(5626, '00:16:14', 'Picosecond Pulse Labs'),
(5627, '00:16:15', 'Nittan Company, Limited'),
(5628, '00:16:16', 'BROWAN COMMUNICATION INC.'),
(5629, '00:16:17', 'MSI'),
(5630, '00:16:18', 'HIVION Co., Ltd.'),
(5631, '00:16:19', 'Lancelan Technologies S.L.'),
(5632, '00:16:1A', 'Dametric AB'),
(5633, '00:16:1B', 'Micronet Corporation'),
(5634, '00:16:1C', 'e:cue'),
(5635, '00:16:1D', 'Innovative Wireless Technologies, Inc.'),
(5636, '00:16:1E', 'Woojinnet'),
(5637, '00:16:1F', 'SUNWAVETEC Co., Ltd.'),
(5638, '00:16:20', 'Sony Ericsson Mobile Communications AB'),
(5639, '00:16:21', 'Colorado Vnet'),
(5640, '00:16:22', 'BBH SYSTEMS GMBH'),
(5641, '00:16:23', 'Interval Media'),
(5642, '00:16:24', 'Teneros, Inc.'),
(5643, '00:16:25', 'Impinj, Inc.'),
(5644, '00:16:26', 'ARRIS Group, Inc.'),
(5645, '00:16:27', 'embedded-logic DESIGN AND MORE GmbH'),
(5646, '00:16:28', 'Ultra Electronics Manufacturing and Card Systems'),
(5647, '00:16:29', 'Nivus GmbH'),
(5648, '00:16:2A', 'Antik computers &amp; communications s.r.o.'),
(5649, '00:16:2B', 'Togami Electric Mfg.co.,Ltd.'),
(5650, '00:16:2C', 'Xanboo'),
(5651, '00:16:2D', 'STNet Co., Ltd.'),
(5652, '00:16:2E', 'Space Shuttle Hi-Tech Co., Ltd.'),
(5653, '00:16:2F', 'Geutebr&uuml;ck GmbH'),
(5654, '00:16:30', 'Vativ Technologies'),
(5655, '00:16:31', 'Xteam'),
(5656, '00:16:32', 'SAMSUNG ELECTRONICS CO., LTD.'),
(5657, '00:16:33', 'Oxford Diagnostics Ltd.'),
(5658, '00:16:34', 'Mathtech, Inc.'),
(5659, '00:16:35', 'Hewlett-Packard Company'),
(5660, '00:16:36', 'Quanta Computer Inc.'),
(5661, '00:16:37', 'CITEL SpA'),
(5662, '00:16:38', 'TECOM Co., Ltd.'),
(5663, '00:16:39', 'UBIQUAM Co.,Ltd'),
(5664, '00:16:3A', 'YVES TECHNOLOGY CO., LTD.'),
(5665, '00:16:3B', 'VertexRSI/General Dynamics'),
(5666, '00:16:3C', 'Rebox B.V.'),
(5667, '00:16:3D', 'Tsinghua Tongfang Legend Silicon Tech. Co., Ltd.'),
(5668, '00:16:3E', 'Xensource, Inc.'),
(5669, '00:16:3F', 'CReTE SYSTEMS Inc.'),
(5670, '00:16:40', 'Asmobile Communication Inc.'),
(5671, '00:16:41', 'Universal Global Scientific Industrial Co., Ltd.'),
(5672, '00:16:42', 'Pangolin'),
(5673, '00:16:43', 'Sunhillo Corporation'),
(5674, '00:16:44', 'LITE-ON Technology Corp.'),
(5675, '00:16:45', 'Power Distribution, Inc.'),
(5676, '00:16:46', 'CISCO SYSTEMS, INC.'),
(5677, '00:16:47', 'CISCO SYSTEMS, INC.'),
(5678, '00:16:48', 'SSD Company Limited'),
(5679, '00:16:49', 'SetOne GmbH'),
(5680, '00:16:4A', 'Vibration Technology Limited'),
(5681, '00:16:4B', 'Quorion Data Systems GmbH'),
(5682, '00:16:4C', 'PLANET INT Co., Ltd'),
(5683, '00:16:4D', 'Alcatel North America IP Division'),
(5684, '00:16:4E', 'Nokia Danmark A/S'),
(5685, '00:16:4F', 'World Ethnic Broadcastin Inc.'),
(5686, '00:16:50', 'Herley General Microwave Israel.'),
(5687, '00:16:51', 'Exeo Systems'),
(5688, '00:16:52', 'Hoatech Technologies, Inc.'),
(5689, '00:16:53', 'LEGO System A/S IE Electronics Division'),
(5690, '00:16:54', 'Flex-P Industries Sdn. Bhd.'),
(5691, '00:16:55', 'FUHO TECHNOLOGY Co., LTD'),
(5692, '00:16:56', 'Nintendo Co., Ltd.'),
(5693, '00:16:57', 'Aegate Ltd'),
(5694, '00:16:58', 'Fusiontech Technologies Inc.'),
(5695, '00:16:59', 'Z.M.P. RADWAG'),
(5696, '00:16:5A', 'Harman Specialty Group'),
(5697, '00:16:5B', 'Grip Audio'),
(5698, '00:16:5C', 'Trackflow Ltd'),
(5699, '00:16:5D', 'AirDefense, Inc.'),
(5700, '00:16:5E', 'Precision I/O'),
(5701, '00:16:5F', 'Fairmount Automation'),
(5702, '00:16:60', 'Nortel'),
(5703, '00:16:61', 'Novatium Solutions (P) Ltd'),
(5704, '00:16:62', 'Liyuh Technology Ltd.'),
(5705, '00:16:63', 'KBT Mobile'),
(5706, '00:16:64', 'Prod-El SpA'),
(5707, '00:16:65', 'Cellon France'),
(5708, '00:16:66', 'Quantier Communication Inc.'),
(5709, '00:16:67', 'A-TEC Subsystem INC.'),
(5710, '00:16:68', 'Eishin Electronics'),
(5711, '00:16:69', 'MRV Communication (Networks) LTD'),
(5712, '00:16:6A', 'TPS'),
(5713, '00:16:6B', 'Samsung Electronics'),
(5714, '00:16:6C', 'Samsung Electonics Digital Video System Division'),
(5715, '00:16:6D', 'Yulong Computer Telecommunication Scientific(shenzhen)Co.,Lt'),
(5716, '00:16:6E', 'Arbitron Inc.'),
(5717, '00:16:6F', 'Intel Corporate'),
(5718, '00:16:70', 'SKNET Corporation'),
(5719, '00:16:71', 'Symphox Information Co.'),
(5720, '00:16:72', 'Zenway enterprise ltd'),
(5721, '00:16:73', 'Bury GmbH &amp; Co. KG'),
(5722, '00:16:74', 'EuroCB (Phils.), Inc.'),
(5723, '00:16:75', 'ARRIS Group, Inc.'),
(5724, '00:16:76', 'Intel Corporate'),
(5725, '00:16:77', 'Bihl + Wiedemann GmbH'),
(5726, '00:16:78', 'SHENZHEN BAOAN GAOKE ELECTRONICS CO., LTD'),
(5727, '00:16:79', 'eOn Communications'),
(5728, '00:16:7A', 'Skyworth Overseas Dvelopment Ltd.'),
(5729, '00:16:7B', 'Haver&amp;Boecker'),
(5730, '00:16:7C', 'iRex Technologies BV'),
(5731, '00:16:7D', 'Sky-Line Information Co., Ltd.'),
(5732, '00:16:7E', 'DIBOSS.CO.,LTD'),
(5733, '00:16:7F', 'Bluebird Soft Inc.'),
(5734, '00:16:80', 'Bally Gaming + Systems'),
(5735, '00:16:81', 'Vector Informatik GmbH'),
(5736, '00:16:82', 'Pro Dex, Inc'),
(5737, '00:16:83', 'WEBIO International Co.,.Ltd.'),
(5738, '00:16:84', 'Donjin Co.,Ltd.'),
(5739, '00:16:85', 'Elisa Oyj'),
(5740, '00:16:86', 'Karl Storz Imaging'),
(5741, '00:16:87', 'Chubb CSC-Vendor AP'),
(5742, '00:16:88', 'ServerEngines LLC'),
(5743, '00:16:89', 'Pilkor Electronics Co., Ltd'),
(5744, '00:16:8A', 'id-Confirm Inc'),
(5745, '00:16:8B', 'Paralan Corporation'),
(5746, '00:16:8C', 'DSL Partner AS'),
(5747, '00:16:8D', 'KORWIN CO., Ltd.'),
(5748, '00:16:8E', 'Vimicro corporation'),
(5749, '00:16:8F', 'GN Netcom as'),
(5750, '00:16:90', 'J-TEK INCORPORATION'),
(5751, '00:16:91', 'Moser-Baer AG'),
(5752, '00:16:92', 'Scientific-Atlanta, Inc.'),
(5753, '00:16:93', 'PowerLink Technology Inc.'),
(5754, '00:16:94', 'Sennheiser Communications A/S'),
(5755, '00:16:95', 'AVC Technology (International) Limited'),
(5756, '00:16:96', 'QDI Technology (H.K.) Limited'),
(5757, '00:16:97', 'NEC Corporation'),
(5758, '00:16:98', 'T&amp;A Mobile Phones'),
(5759, '00:16:99', 'Tonic DVB Marketing Ltd'),
(5760, '00:16:9A', 'Quadrics Ltd'),
(5761, '00:16:9B', 'Alstom Transport'),
(5762, '00:16:9C', 'CISCO SYSTEMS, INC.'),
(5763, '00:16:9D', 'CISCO SYSTEMS, INC.'),
(5764, '00:16:9E', 'TV One Ltd'),
(5765, '00:16:9F', 'Vimtron Electronics Co., Ltd.'),
(5766, '00:16:A0', 'Auto-Maskin'),
(5767, '00:16:A1', '3Leaf Networks'),
(5768, '00:16:A2', 'CentraLite Systems, Inc.'),
(5769, '00:16:A3', 'Ingeteam Transmission&amp;Distribution, S.A.'),
(5770, '00:16:A4', 'Ezurio Ltd'),
(5771, '00:16:A5', 'Tandberg Storage ASA'),
(5772, '00:16:A6', 'Dovado FZ-LLC'),
(5773, '00:16:A7', 'AWETA G&amp;P'),
(5774, '00:16:A8', 'CWT CO., LTD.'),
(5775, '00:16:A9', '2EI'),
(5776, '00:16:AA', 'Kei Communication Technology Inc.'),
(5777, '00:16:AB', 'Dansensor A/S'),
(5778, '00:16:AC', 'Toho Technology Corp.'),
(5779, '00:16:AD', 'BT-Links Company Limited'),
(5780, '00:16:AE', 'INVENTEL'),
(5781, '00:16:AF', 'Shenzhen Union Networks Equipment Co.,Ltd.'),
(5782, '00:16:B0', 'VK Corporation'),
(5783, '00:16:B1', 'KBS'),
(5784, '00:16:B2', 'DriveCam Inc'),
(5785, '00:16:B3', 'Photonicbridges (China) Co., Ltd.'),
(5786, '00:16:B4', 'PRIVATE'),
(5787, '00:16:B5', 'ARRIS Group, Inc.'),
(5788, '00:16:B6', 'Cisco-Linksys'),
(5789, '00:16:B7', 'Seoul Commtech'),
(5790, '00:16:B8', 'Sony Ericsson Mobile Communications'),
(5791, '00:16:B9', 'ProCurve Networking'),
(5792, '00:16:BA', 'WEATHERNEWS INC.'),
(5793, '00:16:BB', 'Law-Chain Computer Technology Co Ltd'),
(5794, '00:16:BC', 'Nokia Danmark A/S'),
(5795, '00:16:BD', 'ATI Industrial Automation'),
(5796, '00:16:BE', 'INFRANET, Inc.'),
(5797, '00:16:BF', 'PaloDEx Group Oy'),
(5798, '00:16:C0', 'Semtech Corporation'),
(5799, '00:16:C1', 'Eleksen Ltd'),
(5800, '00:16:C2', 'Avtec Systems Inc'),
(5801, '00:16:C3', 'BA Systems Inc'),
(5802, '00:16:C4', 'SiRF Technology, Inc.'),
(5803, '00:16:C5', 'Shenzhen Xing Feng Industry Co.,Ltd'),
(5804, '00:16:C6', 'North Atlantic Industries'),
(5805, '00:16:C7', 'CISCO SYSTEMS, INC.'),
(5806, '00:16:C8', 'CISCO SYSTEMS, INC.'),
(5807, '00:16:C9', 'NAT Seattle, Inc.'),
(5808, '00:16:CA', 'Nortel'),
(5809, '00:16:CB', 'Apple'),
(5810, '00:16:CC', 'Xcute Mobile Corp.'),
(5811, '00:16:CD', 'HIJI HIGH-TECH CO., LTD.'),
(5812, '00:16:CE', 'Hon Hai Precision Ind. Co., Ltd.'),
(5813, '00:16:CF', 'Hon Hai Precision Ind. Co., Ltd.'),
(5814, '00:16:D0', 'ATech elektronika d.o.o.'),
(5815, '00:16:D1', 'ZAT a.s.'),
(5816, '00:16:D2', 'Caspian'),
(5817, '00:16:D3', 'Wistron Corporation'),
(5818, '00:16:D4', 'Compal Communications, Inc.'),
(5819, '00:16:D5', 'Synccom Co., Ltd'),
(5820, '00:16:D6', 'TDA Tech Pty Ltd'),
(5821, '00:16:D7', 'Sunways AG'),
(5822, '00:16:D8', 'Senea AB'),
(5823, '00:16:D9', 'NINGBO BIRD CO.,LTD.'),
(5824, '00:16:DA', 'Futronic Technology Co. Ltd.'),
(5825, '00:16:DB', 'Samsung Electronics Co., Ltd.'),
(5826, '00:16:DC', 'ARCHOS'),
(5827, '00:16:DD', 'Gigabeam Corporation'),
(5828, '00:16:DE', 'FAST Inc'),
(5829, '00:16:DF', 'Lundinova AB'),
(5830, '00:16:E0', '3Com Ltd'),
(5831, '00:16:E1', 'SiliconStor, Inc.'),
(5832, '00:16:E2', 'American Fibertek, Inc.'),
(5833, '00:16:E3', 'ASKEY COMPUTER CORP.'),
(5834, '00:16:E4', 'VANGUARD SECURITY ENGINEERING CORP.'),
(5835, '00:16:E5', 'FORDLEY DEVELOPMENT LIMITED'),
(5836, '00:16:E6', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(5837, '00:16:E7', 'Dynamix Promotions Limited'),
(5838, '00:16:E8', 'Sigma Designs, Inc.'),
(5839, '00:16:E9', 'Tiba Medical Inc'),
(5840, '00:16:EA', 'Intel Corporate'),
(5841, '00:16:EB', 'Intel Corporate'),
(5842, '00:16:EC', 'Elitegroup Computer Systems Co., Ltd.'),
(5843, '00:16:ED', 'Digital Safety Technologies, Inc'),
(5844, '00:16:EE', 'RoyalDigital Inc.'),
(5845, '00:16:EF', 'Koko Fitness, Inc.'),
(5846, '00:16:F0', 'Dell'),
(5847, '00:16:F1', 'OmniSense, LLC'),
(5848, '00:16:F2', 'Dmobile System Co., Ltd.'),
(5849, '00:16:F3', 'CAST Information Co., Ltd'),
(5850, '00:16:F4', 'Eidicom Co., Ltd.'),
(5851, '00:16:F5', 'Dalian Golden Hualu Digital Technology Co.,Ltd'),
(5852, '00:16:F6', 'Video Products Group'),
(5853, '00:16:F7', 'L-3 Communications, Aviation Recorders'),
(5854, '00:16:F8', 'AVIQTECH TECHNOLOGY CO., LTD.'),
(5855, '00:16:F9', 'CETRTA POT, d.o.o., Kranj'),
(5856, '00:16:FA', 'ECI Telecom Ltd.'),
(5857, '00:16:FB', 'SHENZHEN MTC CO.,LTD.'),
(5858, '00:16:FC', 'TOHKEN CO.,LTD.'),
(5859, '00:16:FD', 'Jaty Electronics'),
(5860, '00:16:FE', 'Alps Electric Co., Ltd'),
(5861, '00:16:FF', 'Wamin Optocomm Mfg Corp'),
(5862, '00:17:00', 'ARRIS Group, Inc.'),
(5863, '00:17:01', 'KDE, Inc.'),
(5864, '00:17:02', 'Osung Midicom Co., Ltd'),
(5865, '00:17:03', 'MOSDAN Internation Co.,Ltd'),
(5866, '00:17:04', 'Shinco Electronics Group Co.,Ltd'),
(5867, '00:17:05', 'Methode Electronics'),
(5868, '00:17:06', 'Techfaith Wireless Communication Technology Limited.'),
(5869, '00:17:07', 'InGrid, Inc'),
(5870, '00:17:08', 'Hewlett-Packard Company'),
(5871, '00:17:09', 'Exalt Communications'),
(5872, '00:17:0A', 'INEW DIGITAL COMPANY'),
(5873, '00:17:0B', 'Contela, Inc.'),
(5874, '00:17:0C', 'Twig Com Ltd.'),
(5875, '00:17:0D', 'Dust Networks Inc.'),
(5876, '00:17:0E', 'CISCO SYSTEMS, INC.'),
(5877, '00:17:0F', 'CISCO SYSTEMS, INC.'),
(5878, '00:17:10', 'Casa Systems Inc.'),
(5879, '00:17:11', 'GE Healthcare Bio-Sciences AB'),
(5880, '00:17:12', 'ISCO International'),
(5881, '00:17:13', 'Tiger NetCom'),
(5882, '00:17:14', 'BR Controls Nederland bv'),
(5883, '00:17:15', 'Qstik'),
(5884, '00:17:16', 'Qno Technology Inc.'),
(5885, '00:17:17', 'Leica Geosystems AG'),
(5886, '00:17:18', 'Vansco Electronics Oy'),
(5887, '00:17:19', 'AudioCodes USA, Inc'),
(5888, '00:17:1A', 'Winegard Company'),
(5889, '00:17:1B', 'Innovation Lab Corp.'),
(5890, '00:17:1C', 'NT MicroSystems, Inc.'),
(5891, '00:17:1D', 'DIGIT'),
(5892, '00:17:1E', 'Theo Benning GmbH &amp; Co. KG'),
(5893, '00:17:1F', 'IMV Corporation'),
(5894, '00:17:20', 'Image Sensing Systems, Inc.'),
(5895, '00:17:21', 'FITRE S.p.A.'),
(5896, '00:17:22', 'Hanazeder Electronic GmbH'),
(5897, '00:17:23', 'Summit Data Communications'),
(5898, '00:17:24', 'Studer Professional Audio GmbH'),
(5899, '00:17:25', 'Liquid Computing'),
(5900, '00:17:26', 'm2c Electronic Technology Ltd.'),
(5901, '00:17:27', 'Thermo Ramsey Italia s.r.l.'),
(5902, '00:17:28', 'Selex Communications'),
(5903, '00:17:29', 'Ubicod Co.LTD'),
(5904, '00:17:2A', 'Proware Technology Corp.(By Unifosa)'),
(5905, '00:17:2B', 'Global Technologies Inc.'),
(5906, '00:17:2C', 'TAEJIN INFOTECH'),
(5907, '00:17:2D', 'Axcen Photonics Corporation'),
(5908, '00:17:2E', 'FXC Inc.'),
(5909, '00:17:2F', 'NeuLion Incorporated'),
(5910, '00:17:30', 'Automation Electronics'),
(5911, '00:17:31', 'ASUSTek COMPUTER INC.'),
(5912, '00:17:32', 'Science-Technical Center &quot;RISSA&quot;'),
(5913, '00:17:33', 'SFR'),
(5914, '00:17:34', 'ADC Telecommunications'),
(5915, '00:17:35', 'PRIVATE'),
(5916, '00:17:36', 'iiTron Inc.'),
(5917, '00:17:37', 'Industrie Dial Face S.p.A.'),
(5918, '00:17:38', 'International Business Machines'),
(5919, '00:17:39', 'Bright Headphone Electronics Company'),
(5920, '00:17:3A', 'Reach Systems Inc.'),
(5921, '00:17:3B', 'Cisco Systems, Inc.'),
(5922, '00:17:3C', 'Extreme Engineering Solutions'),
(5923, '00:17:3D', 'Neology'),
(5924, '00:17:3E', 'LeucotronEquipamentos Ltda.'),
(5925, '00:17:3F', 'Belkin Corporation'),
(5926, '00:17:40', 'Bluberi Gaming Technologies Inc'),
(5927, '00:17:41', 'DEFIDEV'),
(5928, '00:17:42', 'FUJITSU LIMITED'),
(5929, '00:17:43', 'Deck Srl'),
(5930, '00:17:44', 'Araneo Ltd.'),
(5931, '00:17:45', 'INNOTZ CO., Ltd'),
(5932, '00:17:46', 'Freedom9 Inc.'),
(5933, '00:17:47', 'Trimble'),
(5934, '00:17:48', 'Neokoros Brasil Ltda'),
(5935, '00:17:49', 'HYUNDAE YONG-O-SA CO.,LTD'),
(5936, '00:17:4A', 'SOCOMEC'),
(5937, '00:17:4B', 'Nokia Danmark A/S'),
(5938, '00:17:4C', 'Millipore'),
(5939, '00:17:4D', 'DYNAMIC NETWORK FACTORY, INC.'),
(5940, '00:17:4E', 'Parama-tech Co.,Ltd.'),
(5941, '00:17:4F', 'iCatch Inc.'),
(5942, '00:17:50', 'GSI Group, MicroE Systems'),
(5943, '00:17:51', 'Online Corporation'),
(5944, '00:17:52', 'DAGS, Inc'),
(5945, '00:17:53', 'nFore Technology Inc.'),
(5946, '00:17:54', 'Arkino HiTOP Corporation Limited'),
(5947, '00:17:55', 'GE Security'),
(5948, '00:17:56', 'Vinci Labs Oy'),
(5949, '00:17:57', 'RIX TECHNOLOGY LIMITED'),
(5950, '00:17:58', 'ThruVision Ltd'),
(5951, '00:17:59', 'CISCO SYSTEMS, INC.'),
(5952, '00:17:5A', 'CISCO SYSTEMS, INC.'),
(5953, '00:17:5B', 'ACS Solutions Switzerland Ltd.'),
(5954, '00:17:5C', 'SHARP CORPORATION'),
(5955, '00:17:5D', 'Dongseo system.'),
(5956, '00:17:5E', 'Zed-3'),
(5957, '00:17:5F', 'XENOLINK Communications Co., Ltd.'),
(5958, '00:17:60', 'Naito Densei Machida MFG.CO.,LTD'),
(5959, '00:17:61', 'PRIVATE'),
(5960, '00:17:62', 'Solar Technology, Inc.'),
(5961, '00:17:63', 'Essentia S.p.A.'),
(5962, '00:17:64', 'ATMedia GmbH'),
(5963, '00:17:65', 'Nortel'),
(5964, '00:17:66', 'Accense Technology, Inc.'),
(5965, '00:17:67', 'Earforce AS'),
(5966, '00:17:68', 'Zinwave Ltd'),
(5967, '00:17:69', 'Cymphonix Corp'),
(5968, '00:17:6A', 'Avago Technologies'),
(5969, '00:17:6B', 'Kiyon, Inc.'),
(5970, '00:17:6C', 'Pivot3, Inc.'),
(5971, '00:17:6D', 'CORE CORPORATION'),
(5972, '00:17:6E', 'DUCATI SISTEMI'),
(5973, '00:17:6F', 'PAX Computer Technology(Shenzhen) Ltd.'),
(5974, '00:17:70', 'Arti Industrial Electronics Ltd.'),
(5975, '00:17:71', 'APD Communications Ltd'),
(5976, '00:17:72', 'ASTRO Strobel Kommunikationssysteme GmbH'),
(5977, '00:17:73', 'Laketune Technologies Co. Ltd'),
(5978, '00:17:74', 'Elesta GmbH'),
(5979, '00:17:75', 'TTE Germany GmbH'),
(5980, '00:17:76', 'Meso Scale Diagnostics, LLC'),
(5981, '00:17:77', 'Obsidian Research Corporation'),
(5982, '00:17:78', 'Central Music Co.'),
(5983, '00:17:79', 'QuickTel'),
(5984, '00:17:7A', 'ASSA ABLOY AB'),
(5985, '00:17:7B', 'Azalea Networks inc'),
(5986, '00:17:7C', 'Smartlink Network Systems Limited'),
(5987, '00:17:7D', 'IDT International Limited'),
(5988, '00:17:7E', 'Meshcom Technologies Inc.'),
(5989, '00:17:7F', 'Worldsmart Retech'),
(5990, '00:17:80', 'Applied Biosystems B.V.'),
(5991, '00:17:81', 'Greystone Data System, Inc.'),
(5992, '00:17:82', 'LoBenn Inc.'),
(5993, '00:17:83', 'Texas Instruments'),
(5994, '00:17:84', 'ARRIS Group, Inc.'),
(5995, '00:17:85', 'Sparr Electronics Ltd'),
(5996, '00:17:86', 'wisembed'),
(5997, '00:17:87', 'Brother, Brother &amp; Sons ApS'),
(5998, '00:17:88', 'Philips Lighting BV'),
(5999, '00:17:89', 'Zenitron Corporation'),
(6000, '00:17:8A', 'DARTS TECHNOLOGIES CORP.'),
(6001, '00:17:8B', 'Teledyne Technologies Incorporated'),
(6002, '00:17:8C', 'Independent Witness, Inc'),
(6003, '00:17:8D', 'Checkpoint Systems, Inc.'),
(6004, '00:17:8E', 'Gunnebo Cash Automation AB'),
(6005, '00:17:8F', 'NINGBO YIDONG ELECTRONIC CO.,LTD.'),
(6006, '00:17:90', 'HYUNDAI DIGITECH Co, Ltd.'),
(6007, '00:17:91', 'LinTech GmbH'),
(6008, '00:17:92', 'Falcom Wireless Comunications Gmbh'),
(6009, '00:17:93', 'Tigi Corporation'),
(6010, '00:17:94', 'CISCO SYSTEMS, INC.'),
(6011, '00:17:95', 'CISCO SYSTEMS, INC.'),
(6012, '00:17:96', 'Rittmeyer AG'),
(6013, '00:17:97', 'Telsy Elettronica S.p.A.'),
(6014, '00:17:98', 'Azonic Technology Co., LTD'),
(6015, '00:17:99', 'SmarTire Systems Inc.'),
(6016, '00:17:9A', 'D-Link Corporation'),
(6017, '00:17:9B', 'Chant Sincere CO., LTD.'),
(6018, '00:17:9C', 'DEPRAG SCHULZ GMBH u. CO.'),
(6019, '00:17:9D', 'Kelman Limited'),
(6020, '00:17:9E', 'Sirit Inc'),
(6021, '00:17:9F', 'Apricorn'),
(6022, '00:17:A0', 'RoboTech srl'),
(6023, '00:17:A1', '3soft inc.'),
(6024, '00:17:A2', 'Camrivox Ltd.'),
(6025, '00:17:A3', 'MIX s.r.l.'),
(6026, '00:17:A4', 'Hewlett-Packard Company'),
(6027, '00:17:A5', 'Ralink Technology Corp'),
(6028, '00:17:A6', 'YOSIN ELECTRONICS CO., LTD.'),
(6029, '00:17:A7', 'Mobile Computing Promotion Consortium'),
(6030, '00:17:A8', 'EDM Corporation'),
(6031, '00:17:A9', 'Sentivision'),
(6032, '00:17:AA', 'elab-experience inc.'),
(6033, '00:17:AB', 'Nintendo Co., Ltd.'),
(6034, '00:17:AC', 'O\'Neil Product Development Inc.'),
(6035, '00:17:AD', 'AceNet Corporation'),
(6036, '00:17:AE', 'GAI-Tronics'),
(6037, '00:17:AF', 'Enermet'),
(6038, '00:17:B0', 'Nokia Danmark A/S'),
(6039, '00:17:B1', 'ACIST Medical Systems, Inc.'),
(6040, '00:17:B2', 'SK Telesys'),
(6041, '00:17:B3', 'Aftek Infosys Limited'),
(6042, '00:17:B4', 'Remote Security Systems, LLC'),
(6043, '00:17:B5', 'Peerless Systems Corporation'),
(6044, '00:17:B6', 'Aquantia'),
(6045, '00:17:B7', 'Tonze Technology Co.'),
(6046, '00:17:B8', 'NOVATRON CO., LTD.'),
(6047, '00:17:B9', 'Gambro Lundia AB'),
(6048, '00:17:BA', 'SEDO CO., LTD.'),
(6049, '00:17:BB', 'Syrinx Industrial Electronics'),
(6050, '00:17:BC', 'Touchtunes Music Corporation'),
(6051, '00:17:BD', 'Tibetsystem'),
(6052, '00:17:BE', 'Tratec Telecom B.V.'),
(6053, '00:17:BF', 'Coherent Research Limited'),
(6054, '00:17:C0', 'PureTech Systems, Inc.'),
(6055, '00:17:C1', 'CM Precision Technology LTD.'),
(6056, '00:17:C2', 'ADB Broadband Italia'),
(6057, '00:17:C3', 'KTF Technologies Inc.'),
(6058, '00:17:C4', 'Quanta Microsystems, INC.'),
(6059, '00:17:C5', 'SonicWALL'),
(6060, '00:17:C6', 'Cross Match Technologies Inc'),
(6061, '00:17:C7', 'MARA Systems Consulting AB'),
(6062, '00:17:C8', 'KYOCERA Document Solutions Inc.'),
(6063, '00:17:C9', 'Samsung Electronics Co., Ltd.'),
(6064, '00:17:CA', 'Qisda Corporation'),
(6065, '00:17:CB', 'Juniper Networks'),
(6066, '00:17:CC', 'Alcatel-Lucent'),
(6067, '00:17:CD', 'CEC Wireless R&amp;D Ltd.'),
(6068, '00:17:CE', 'Screen Service Spa'),
(6069, '00:17:CF', 'iMCA-GmbH'),
(6070, '00:17:D0', 'Opticom Communications, LLC'),
(6071, '00:17:D1', 'Nortel'),
(6072, '00:17:D2', 'THINLINX PTY LTD'),
(6073, '00:17:D3', 'Etymotic Research, Inc.'),
(6074, '00:17:D4', 'Monsoon Multimedia, Inc'),
(6075, '00:17:D5', 'Samsung Electronics Co., Ltd.'),
(6076, '00:17:D6', 'Bluechips Microhouse Co.,Ltd.'),
(6077, '00:17:D7', 'ION Geophysical Corporation Inc.'),
(6078, '00:17:D8', 'Magnum Semiconductor, Inc.'),
(6079, '00:17:D9', 'AAI Corporation'),
(6080, '00:17:DA', 'Spans Logic'),
(6081, '00:17:DB', 'CANKO TECHNOLOGIES INC.'),
(6082, '00:17:DC', 'DAEMYUNG ZERO1'),
(6083, '00:17:DD', 'Clipsal Australia'),
(6084, '00:17:DE', 'Advantage Six Ltd'),
(6085, '00:17:DF', 'CISCO SYSTEMS, INC.'),
(6086, '00:17:E0', 'CISCO SYSTEMS, INC.'),
(6087, '00:17:E1', 'DACOS Technologies Co., Ltd.'),
(6088, '00:17:E2', 'ARRIS Group, Inc.'),
(6089, '00:17:E3', 'Texas Instruments'),
(6090, '00:17:E4', 'Texas Instruments'),
(6091, '00:17:E5', 'Texas Instruments'),
(6092, '00:17:E6', 'Texas Instruments'),
(6093, '00:17:E7', 'Texas Instruments'),
(6094, '00:17:E8', 'Texas Instruments'),
(6095, '00:17:E9', 'Texas Instruments'),
(6096, '00:17:EA', 'Texas Instruments'),
(6097, '00:17:EB', 'Texas Instruments'),
(6098, '00:17:EC', 'Texas Instruments'),
(6099, '00:17:ED', 'WooJooIT Ltd.'),
(6100, '00:17:EE', 'ARRIS Group, Inc.'),
(6101, '00:17:EF', 'IBM Corp'),
(6102, '00:17:F0', 'SZCOM Broadband Network Technology Co.,Ltd'),
(6103, '00:17:F1', 'Renu Electronics Pvt Ltd'),
(6104, '00:17:F2', 'Apple'),
(6105, '00:17:F3', 'Harris Corparation'),
(6106, '00:17:F4', 'ZERON ALLIANCE'),
(6107, '00:17:F5', 'LIG NEOPTEK'),
(6108, '00:17:F6', 'Pyramid Meriden Inc.'),
(6109, '00:17:F7', 'CEM Solutions Pvt Ltd'),
(6110, '00:17:F8', 'Motech Industries Inc.'),
(6111, '00:17:F9', 'Forcom Sp. z o.o.'),
(6112, '00:17:FA', 'Microsoft Corporation'),
(6113, '00:17:FB', 'FA'),
(6114, '00:17:FC', 'Suprema Inc.'),
(6115, '00:17:FD', 'Amulet Hotkey'),
(6116, '00:17:FE', 'TALOS SYSTEM INC.'),
(6117, '00:17:FF', 'PLAYLINE Co.,Ltd.'),
(6118, '00:18:00', 'UNIGRAND LTD'),
(6119, '00:18:01', 'Actiontec Electronics, Inc'),
(6120, '00:18:02', 'Alpha Networks Inc.'),
(6121, '00:18:03', 'ArcSoft Shanghai Co. LTD'),
(6122, '00:18:04', 'E-TEK DIGITAL TECHNOLOGY LIMITED'),
(6123, '00:18:05', 'Beijing InHand Networking Technology Co.,Ltd.'),
(6124, '00:18:06', 'Hokkei Industries Co., Ltd.'),
(6125, '00:18:07', 'Fanstel Corp.'),
(6126, '00:18:08', 'SightLogix, Inc.'),
(6127, '00:18:09', 'CRESYN'),
(6128, '00:18:0A', 'Meraki, Inc.'),
(6129, '00:18:0B', 'Brilliant Telecommunications'),
(6130, '00:18:0C', 'Optelian Access Networks'),
(6131, '00:18:0D', 'Terabytes Server Storage Tech Corp'),
(6132, '00:18:0E', 'Avega Systems'),
(6133, '00:18:0F', 'Nokia Danmark A/S'),
(6134, '00:18:10', 'IPTrade S.A.'),
(6135, '00:18:11', 'Neuros Technology International, LLC.'),
(6136, '00:18:12', 'Beijing Xinwei Telecom Technology Co., Ltd.'),
(6137, '00:18:13', 'Sony Ericsson Mobile Communications'),
(6138, '00:18:14', 'Mitutoyo Corporation'),
(6139, '00:18:15', 'GZ Technologies, Inc.'),
(6140, '00:18:16', 'Ubixon Co., Ltd.'),
(6141, '00:18:17', 'D. E. Shaw Research, LLC'),
(6142, '00:18:18', 'CISCO SYSTEMS, INC.'),
(6143, '00:18:19', 'CISCO SYSTEMS, INC.'),
(6144, '00:18:1A', 'AVerMedia Information Inc.'),
(6145, '00:18:1B', 'TaiJin Metal Co., Ltd.'),
(6146, '00:18:1C', 'Exterity Limited'),
(6147, '00:18:1D', 'ASIA ELECTRONICS CO.,LTD'),
(6148, '00:18:1E', 'GDX Technologies Ltd.'),
(6149, '00:18:1F', 'Palmmicro Communications'),
(6150, '00:18:20', 'w5networks'),
(6151, '00:18:21', 'SINDORICOH'),
(6152, '00:18:22', 'CEC TELECOM CO.,LTD.'),
(6153, '00:18:23', 'Delta Electronics, Inc.'),
(6154, '00:18:24', 'Kimaldi Electronics, S.L.'),
(6155, '00:18:25', 'PRIVATE'),
(6156, '00:18:26', 'Cale Access AB'),
(6157, '00:18:27', 'NEC UNIFIED SOLUTIONS NEDERLAND B.V.'),
(6158, '00:18:28', 'e2v technologies (UK) ltd.'),
(6159, '00:18:29', 'Gatsometer'),
(6160, '00:18:2A', 'Taiwan Video &amp; Monitor'),
(6161, '00:18:2B', 'Softier'),
(6162, '00:18:2C', 'Ascend Networks, Inc.'),
(6163, '00:18:2D', 'Artec Design'),
(6164, '00:18:2E', 'XStreamHD, LLC'),
(6165, '00:18:2F', 'Texas Instruments'),
(6166, '00:18:30', 'Texas Instruments'),
(6167, '00:18:31', 'Texas Instruments'),
(6168, '00:18:32', 'Texas Instruments'),
(6169, '00:18:33', 'Texas Instruments'),
(6170, '00:18:34', 'Texas Instruments'),
(6171, '00:18:35', 'Thoratec / ITC'),
(6172, '00:18:36', 'Reliance Electric Limited'),
(6173, '00:18:37', 'Universal ABIT Co., Ltd.'),
(6174, '00:18:38', 'PanAccess Communications,Inc.'),
(6175, '00:18:39', 'Cisco-Linksys LLC'),
(6176, '00:18:3A', 'Westell Technologies'),
(6177, '00:18:3B', 'CENITS Co., Ltd.'),
(6178, '00:18:3C', 'Encore Software Limited'),
(6179, '00:18:3D', 'Vertex Link Corporation'),
(6180, '00:18:3E', 'Digilent, Inc'),
(6181, '00:18:3F', '2Wire, Inc'),
(6182, '00:18:40', '3 Phoenix, Inc.'),
(6183, '00:18:41', 'High Tech Computer Corp'),
(6184, '00:18:42', 'Nokia Danmark A/S'),
(6185, '00:18:43', 'Dawevision Ltd'),
(6186, '00:18:44', 'Heads Up Technologies, Inc.'),
(6187, '00:18:45', 'Pulsar-Telecom LLC.'),
(6188, '00:18:46', 'Crypto S.A.'),
(6189, '00:18:47', 'AceNet Technology Inc.'),
(6190, '00:18:48', 'Vecima Networks Inc.'),
(6191, '00:18:49', 'Pigeon Point Systems LLC'),
(6192, '00:18:4A', 'Catcher, Inc.'),
(6193, '00:18:4B', 'Las Vegas Gaming, Inc.'),
(6194, '00:18:4C', 'Bogen Communications'),
(6195, '00:18:4D', 'Netgear Inc.'),
(6196, '00:18:4E', 'Lianhe Technologies, Inc.'),
(6197, '00:18:4F', '8 Ways Technology Corp.'),
(6198, '00:18:50', 'Secfone Kft'),
(6199, '00:18:51', 'SWsoft'),
(6200, '00:18:52', 'StorLink Semiconductors, Inc.'),
(6201, '00:18:53', 'Atera Networks LTD.'),
(6202, '00:18:54', 'Argard Co., Ltd'),
(6203, '00:18:55', 'Aeromaritime Systembau GmbH'),
(6204, '00:18:56', 'EyeFi, Inc'),
(6205, '00:18:57', 'Unilever R&amp;D'),
(6206, '00:18:58', 'TagMaster AB'),
(6207, '00:18:59', 'Strawberry Linux Co.,Ltd.'),
(6208, '00:18:5A', 'uControl, Inc.'),
(6209, '00:18:5B', 'Network Chemistry, Inc'),
(6210, '00:18:5C', 'EDS Lab Pte Ltd'),
(6211, '00:18:5D', 'TAIGUEN TECHNOLOGY (SHEN-ZHEN) CO., LTD.'),
(6212, '00:18:5E', 'Nexterm Inc.'),
(6213, '00:18:5F', 'TAC Inc.'),
(6214, '00:18:60', 'SIM Technology Group Shanghai Simcom Ltd.,'),
(6215, '00:18:61', 'Ooma, Inc.'),
(6216, '00:18:62', 'Seagate Technology'),
(6217, '00:18:63', 'Veritech Electronics Limited'),
(6218, '00:18:64', 'Eaton Corporation'),
(6219, '00:18:65', 'Siemens Healthcare Diagnostics Manufacturing Ltd'),
(6220, '00:18:66', 'Leutron Vision'),
(6221, '00:18:67', 'Datalogic ADC'),
(6222, '00:18:68', 'Scientific Atlanta, A Cisco Company'),
(6223, '00:18:69', 'KINGJIM'),
(6224, '00:18:6A', 'Global Link Digital Technology Co,.LTD'),
(6225, '00:18:6B', 'Sambu Communics CO., LTD.'),
(6226, '00:18:6C', 'Neonode AB'),
(6227, '00:18:6D', 'Zhenjiang Sapphire Electronic Industry CO.'),
(6228, '00:18:6E', '3Com Ltd'),
(6229, '00:18:6F', 'Setha Industria Eletronica LTDA'),
(6230, '00:18:70', 'E28 Shanghai Limited'),
(6231, '00:18:71', 'Hewlett-Packard Company'),
(6232, '00:18:72', 'Expertise Engineering'),
(6233, '00:18:73', 'CISCO SYSTEMS, INC.'),
(6234, '00:18:74', 'CISCO SYSTEMS, INC.'),
(6235, '00:18:75', 'AnaCise Testnology Pte Ltd'),
(6236, '00:18:76', 'WowWee Ltd.'),
(6237, '00:18:77', 'Amplex A/S'),
(6238, '00:18:78', 'Mackware GmbH'),
(6239, '00:18:79', 'dSys'),
(6240, '00:18:7A', 'Wiremold'),
(6241, '00:18:7B', '4NSYS Co. Ltd.'),
(6242, '00:18:7C', 'INTERCROSS, LLC'),
(6243, '00:18:7D', 'Armorlink shanghai Co. Ltd'),
(6244, '00:18:7E', 'RGB Spectrum'),
(6245, '00:18:7F', 'ZODIANET'),
(6246, '00:18:80', 'Maxim Integrated Products'),
(6247, '00:18:81', 'Buyang Electronics Industrial Co., Ltd'),
(6248, '00:18:82', 'Huawei Technologies Co., Ltd.'),
(6249, '00:18:83', 'FORMOSA21 INC.'),
(6250, '00:18:84', 'Fon Technology S.L.'),
(6251, '00:18:85', 'Avigilon Corporation'),
(6252, '00:18:86', 'EL-TECH, INC.'),
(6253, '00:18:87', 'Metasystem SpA'),
(6254, '00:18:88', 'GOTIVE a.s.'),
(6255, '00:18:89', 'WinNet Solutions Limited'),
(6256, '00:18:8A', 'Infinova LLC'),
(6257, '00:18:8B', 'Dell Inc'),
(6258, '00:18:8C', 'Mobile Action Technology Inc.'),
(6259, '00:18:8D', 'Nokia Danmark A/S'),
(6260, '00:18:8E', 'Ekahau, Inc.'),
(6261, '00:18:8F', 'Montgomery Technology, Inc.'),
(6262, '00:18:90', 'RadioCOM, s.r.o.'),
(6263, '00:18:91', 'Zhongshan General K-mate Electronics Co., Ltd'),
(6264, '00:18:92', 'ads-tec GmbH'),
(6265, '00:18:93', 'SHENZHEN PHOTON BROADBAND TECHNOLOGY CO.,LTD'),
(6266, '00:18:94', 'NPCore, Inc.'),
(6267, '00:18:95', 'Hansun Technologies Inc.'),
(6268, '00:18:96', 'Great Well Electronic LTD'),
(6269, '00:18:97', 'JESS-LINK PRODUCTS Co., LTD'),
(6270, '00:18:98', 'KINGSTATE ELECTRONICS CORPORATION'),
(6271, '00:18:99', 'ShenZhen jieshun Science&amp;Technology Industry CO,LTD.'),
(6272, '00:18:9A', 'HANA Micron Inc.'),
(6273, '00:18:9B', 'Thomson Inc.'),
(6274, '00:18:9C', 'Weldex Corporation'),
(6275, '00:18:9D', 'Navcast Inc.'),
(6276, '00:18:9E', 'OMNIKEY GmbH.'),
(6277, '00:18:9F', 'Lenntek Corporation'),
(6278, '00:18:A0', 'Cierma Ascenseurs'),
(6279, '00:18:A1', 'Tiqit Computers, Inc.'),
(6280, '00:18:A2', 'XIP Technology AB'),
(6281, '00:18:A3', 'ZIPPY TECHNOLOGY CORP.'),
(6282, '00:18:A4', 'ARRIS Group, Inc.'),
(6283, '00:18:A5', 'ADigit Technologies Corp.'),
(6284, '00:18:A6', 'Persistent Systems, LLC'),
(6285, '00:18:A7', 'Yoggie Security Systems LTD.'),
(6286, '00:18:A8', 'AnNeal Technology Inc.'),
(6287, '00:18:A9', 'Ethernet Direct Corporation'),
(6288, '00:18:AA', 'Protec Fire Detection plc'),
(6289, '00:18:AB', 'BEIJING LHWT MICROELECTRONICS INC.'),
(6290, '00:18:AC', 'Shanghai Jiao Da HISYS Technology Co. Ltd.'),
(6291, '00:18:AD', 'NIDEC SANKYO CORPORATION'),
(6292, '00:18:AE', 'TVT CO.,LTD'),
(6293, '00:18:AF', 'Samsung Electronics Co., Ltd.'),
(6294, '00:18:B0', 'Nortel'),
(6295, '00:18:B1', 'IBM Corp'),
(6296, '00:18:B2', 'ADEUNIS RF'),
(6297, '00:18:B3', 'TEC WizHome Co., Ltd.'),
(6298, '00:18:B4', 'Dawon Media Inc.'),
(6299, '00:18:B5', 'Magna Carta'),
(6300, '00:18:B6', 'S3C, Inc.'),
(6301, '00:18:B7', 'D3 LED, LLC'),
(6302, '00:18:B8', 'New Voice International AG'),
(6303, '00:18:B9', 'CISCO SYSTEMS, INC.'),
(6304, '00:18:BA', 'CISCO SYSTEMS, INC.'),
(6305, '00:18:BB', 'Eliwell Controls srl'),
(6306, '00:18:BC', 'ZAO NVP Bolid'),
(6307, '00:18:BD', 'SHENZHEN DVBWORLD TECHNOLOGY CO., LTD.'),
(6308, '00:18:BE', 'ANSA Corporation'),
(6309, '00:18:BF', 'Essence Technology Solution, Inc.'),
(6310, '00:18:C0', 'ARRIS Group, Inc.'),
(6311, '00:18:C1', 'Almitec Inform&aacute;tica e Com&eacute;rcio'),
(6312, '00:18:C2', 'Firetide, Inc'),
(6313, '00:18:C3', 'CS Corporation'),
(6314, '00:18:C4', 'Raba Technologies LLC'),
(6315, '00:18:C5', 'Nokia Danmark A/S'),
(6316, '00:18:C6', 'OPW Fuel Management Systems'),
(6317, '00:18:C7', 'Real Time Automation'),
(6318, '00:18:C8', 'ISONAS Inc.'),
(6319, '00:18:C9', 'EOps Technology Limited'),
(6320, '00:18:CA', 'Viprinet GmbH'),
(6321, '00:18:CB', 'Tecobest Technology Limited'),
(6322, '00:18:CC', 'AXIOHM SAS'),
(6323, '00:18:CD', 'Erae Electronics Industry Co., Ltd'),
(6324, '00:18:CE', 'Dreamtech Co., Ltd'),
(6325, '00:18:CF', 'Baldor Electric Company'),
(6326, '00:18:D0', 'AtRoad,  A Trimble Company'),
(6327, '00:18:D1', 'Siemens Home &amp; Office Comm. Devices'),
(6328, '00:18:D2', 'High-Gain Antennas LLC'),
(6329, '00:18:D3', 'TEAMCAST'),
(6330, '00:18:D4', 'Unified Display Interface SIG'),
(6331, '00:18:D5', 'REIGNCOM'),
(6332, '00:18:D6', 'Swirlnet A/S'),
(6333, '00:18:D7', 'Javad Navigation Systems Inc.'),
(6334, '00:18:D8', 'ARCH METER Corporation'),
(6335, '00:18:D9', 'Santosha Internatonal, Inc'),
(6336, '00:18:DA', 'AMBER wireless GmbH'),
(6337, '00:18:DB', 'EPL Technology Ltd'),
(6338, '00:18:DC', 'Prostar Co., Ltd.'),
(6339, '00:18:DD', 'Silicondust Engineering Ltd'),
(6340, '00:18:DE', 'Intel Corporate'),
(6341, '00:18:DF', 'The Morey Corporation'),
(6342, '00:18:E0', 'ANAVEO'),
(6343, '00:18:E1', 'Verkerk Service Systemen'),
(6344, '00:18:E2', 'Topdata Sistemas de Automacao Ltda'),
(6345, '00:18:E3', 'Visualgate Systems, Inc.'),
(6346, '00:18:E4', 'YIGUANG'),
(6347, '00:18:E5', 'Adhoco AG'),
(6348, '00:18:E6', 'Computer Hardware Design SIA'),
(6349, '00:18:E7', 'Cameo Communications, INC.'),
(6350, '00:18:E8', 'Hacetron Corporation'),
(6351, '00:18:E9', 'Numata Corporation'),
(6352, '00:18:EA', 'Alltec GmbH'),
(6353, '00:18:EB', 'BroVis Wireless Networks'),
(6354, '00:18:EC', 'Welding Technology Corporation'),
(6355, '00:18:ED', 'Accutech Ultrasystems Co., Ltd.'),
(6356, '00:18:EE', 'Videology Imaging Solutions, Inc.'),
(6357, '00:18:EF', 'Escape Communications, Inc.'),
(6358, '00:18:F0', 'JOYTOTO Co., Ltd.'),
(6359, '00:18:F1', 'Chunichi Denshi Co.,LTD.'),
(6360, '00:18:F2', 'Beijing Tianyu Communication Equipment Co., Ltd'),
(6361, '00:18:F3', 'ASUSTek COMPUTER INC.'),
(6362, '00:18:F4', 'EO TECHNICS Co., Ltd.'),
(6363, '00:18:F5', 'Shenzhen Streaming Video Technology Company Limited'),
(6364, '00:18:F6', 'Thomson Telecom Belgium'),
(6365, '00:18:F7', 'Kameleon Technologies'),
(6366, '00:18:F8', 'Cisco-Linksys LLC'),
(6367, '00:18:F9', 'VVOND, Inc.'),
(6368, '00:18:FA', 'Yushin Precision Equipment Co.,Ltd.'),
(6369, '00:18:FB', 'Compro Technology'),
(6370, '00:18:FC', 'Altec Electronic AG'),
(6371, '00:18:FD', 'Optimal Technologies International Inc.'),
(6372, '00:18:FE', 'Hewlett-Packard Company'),
(6373, '00:18:FF', 'PowerQuattro Co.'),
(6374, '00:19:00', 'Intelliverese - DBA Voicecom'),
(6375, '00:19:01', 'F1MEDIA'),
(6376, '00:19:02', 'Cambridge Consultants Ltd'),
(6377, '00:19:03', 'Bigfoot Networks Inc'),
(6378, '00:19:04', 'WB Electronics Sp. z o.o.'),
(6379, '00:19:05', 'SCHRACK Seconet AG'),
(6380, '00:19:06', 'CISCO SYSTEMS, INC.'),
(6381, '00:19:07', 'CISCO SYSTEMS, INC.'),
(6382, '00:19:08', 'Duaxes Corporation'),
(6383, '00:19:09', 'DEVI - Danfoss A/S'),
(6384, '00:19:0A', 'HASWARE INC.'),
(6385, '00:19:0B', 'Southern Vision Systems, Inc.'),
(6386, '00:19:0C', 'Encore Electronics, Inc.'),
(6387, '00:19:0D', 'IEEE 1394c'),
(6388, '00:19:0E', 'Atech Technology Co., Ltd.'),
(6389, '00:19:0F', 'Advansus Corp.'),
(6390, '00:19:10', 'Knick Elektronische Messgeraete GmbH &amp; Co. KG'),
(6391, '00:19:11', 'Just In Mobile Information Technologies (Shanghai) Co., Ltd.'),
(6392, '00:19:12', 'Welcat Inc'),
(6393, '00:19:13', 'Chuang-Yi Network Equipment Co.Ltd.'),
(6394, '00:19:14', 'Winix Co., Ltd'),
(6395, '00:19:15', 'TECOM Co., Ltd.'),
(6396, '00:19:16', 'PayTec AG'),
(6397, '00:19:17', 'Posiflex Inc.'),
(6398, '00:19:18', 'Interactive Wear AG'),
(6399, '00:19:19', 'ASTEL Inc.'),
(6400, '00:19:1A', 'IRLINK'),
(6401, '00:19:1B', 'Sputnik Engineering AG'),
(6402, '00:19:1C', 'Sensicast Systems'),
(6403, '00:19:1D', 'Nintendo Co., Ltd.'),
(6404, '00:19:1E', 'Beyondwiz Co., Ltd.'),
(6405, '00:19:1F', 'Microlink communications Inc.'),
(6406, '00:19:20', 'KUME electric Co.,Ltd.'),
(6407, '00:19:21', 'Elitegroup Computer System Co.'),
(6408, '00:19:22', 'CM Comandos Lineares'),
(6409, '00:19:23', 'Phonex Korea Co., LTD.'),
(6410, '00:19:24', 'LBNL  Engineering'),
(6411, '00:19:25', 'Intelicis Corporation'),
(6412, '00:19:26', 'BitsGen Co., Ltd.'),
(6413, '00:19:27', 'ImCoSys Ltd'),
(6414, '00:19:28', 'Siemens AG, Transportation Systems'),
(6415, '00:19:29', '2M2B Montadora de Maquinas Bahia Brasil LTDA'),
(6416, '00:19:2A', 'Antiope Associates'),
(6417, '00:19:2B', 'Aclara RF Systems Inc.'),
(6418, '00:19:2C', 'ARRIS Group, Inc.'),
(6419, '00:19:2D', 'Nokia Corporation'),
(6420, '00:19:2E', 'Spectral Instruments, Inc.'),
(6421, '00:19:2F', 'CISCO SYSTEMS, INC.'),
(6422, '00:19:30', 'CISCO SYSTEMS, INC.'),
(6423, '00:19:31', 'Balluff GmbH'),
(6424, '00:19:32', 'Gude Analog- und Digialsysteme GmbH'),
(6425, '00:19:33', 'Strix Systems, Inc.'),
(6426, '00:19:34', 'TRENDON TOUCH TECHNOLOGY CORP.'),
(6427, '00:19:35', 'DUERR DENTAL AG'),
(6428, '00:19:36', 'STERLITE OPTICAL TECHNOLOGIES LIMITED'),
(6429, '00:19:37', 'CommerceGuard AB'),
(6430, '00:19:38', 'UMB Communications Co., Ltd.'),
(6431, '00:19:39', 'Gigamips'),
(6432, '00:19:3A', 'OESOLUTIONS'),
(6433, '00:19:3B', 'Wilibox Deliberant Group LLC'),
(6434, '00:19:3C', 'HighPoint Technologies Incorporated'),
(6435, '00:19:3D', 'GMC Guardian Mobility Corp.'),
(6436, '00:19:3E', 'ADB Broadband Italia'),
(6437, '00:19:3F', 'RDI technology(Shenzhen) Co.,LTD'),
(6438, '00:19:40', 'Rackable Systems'),
(6439, '00:19:41', 'Pitney Bowes, Inc'),
(6440, '00:19:42', 'ON SOFTWARE INTERNATIONAL LIMITED'),
(6441, '00:19:43', 'Belden'),
(6442, '00:19:44', 'Fossil Partners, L.P.'),
(6443, '00:19:45', 'RF COncepts, LLC'),
(6444, '00:19:46', 'Cianet Industria e Comercio S/A'),
(6445, '00:19:47', 'Scientific Atlanta, A Cisco Company'),
(6446, '00:19:48', 'AireSpider Networks'),
(6447, '00:19:49', 'TENTEL  COMTECH CO., LTD.'),
(6448, '00:19:4A', 'TESTO AG'),
(6449, '00:19:4B', 'SAGEM COMMUNICATION'),
(6450, '00:19:4C', 'Fujian Stelcom information &amp; Technology CO.,Ltd'),
(6451, '00:19:4D', 'Avago Technologies Sdn Bhd'),
(6452, '00:19:4E', 'Ultra Electronics - TCS (Tactical Communication Systems)'),
(6453, '00:19:4F', 'Nokia Danmark A/S'),
(6454, '00:19:50', 'Harman Multimedia'),
(6455, '00:19:51', 'NETCONS, s.r.o.'),
(6456, '00:19:52', 'ACOGITO Co., Ltd'),
(6457, '00:19:53', 'Chainleader Communications Corp.'),
(6458, '00:19:54', 'Leaf Corporation.'),
(6459, '00:19:55', 'CISCO SYSTEMS, INC.'),
(6460, '00:19:56', 'CISCO SYSTEMS, INC.'),
(6461, '00:19:57', 'Saafnet Canada Inc.'),
(6462, '00:19:58', 'Bluetooth SIG, Inc.'),
(6463, '00:19:59', 'Staccato Communications Inc.'),
(6464, '00:19:5A', 'Jenaer Antriebstechnik GmbH'),
(6465, '00:19:5B', 'D-Link Corporation'),
(6466, '00:19:5C', 'Innotech Corporation'),
(6467, '00:19:5D', 'ShenZhen XinHuaTong Opto Electronics Co.,Ltd'),
(6468, '00:19:5E', 'ARRIS Group, Inc.'),
(6469, '00:19:5F', 'Valemount Networks Corporation'),
(6470, '00:19:60', 'DoCoMo Systems, Inc.'),
(6471, '00:19:61', 'Blaupunkt  Embedded Systems GmbH'),
(6472, '00:19:62', 'Commerciant, LP'),
(6473, '00:19:63', 'Sony Ericsson Mobile Communications AB'),
(6474, '00:19:64', 'Doorking Inc.'),
(6475, '00:19:65', 'YuHua TelTech (ShangHai) Co., Ltd.'),
(6476, '00:19:66', 'Asiarock Technology Limited'),
(6477, '00:19:67', 'TELDAT Sp.J.'),
(6478, '00:19:68', 'Digital Video Networks(Shanghai) CO. LTD.'),
(6479, '00:19:69', 'Nortel'),
(6480, '00:19:6A', 'MikroM GmbH'),
(6481, '00:19:6B', 'Danpex Corporation'),
(6482, '00:19:6C', 'ETROVISION TECHNOLOGY'),
(6483, '00:19:6D', 'Raybit Systems Korea, Inc'),
(6484, '00:19:6E', 'Metacom (Pty) Ltd.'),
(6485, '00:19:6F', 'SensoPart GmbH'),
(6486, '00:19:70', 'Z-Com, Inc.'),
(6487, '00:19:71', 'Guangzhou Unicomp Technology Co.,Ltd'),
(6488, '00:19:72', 'Plexus (Xiamen) Co.,ltd'),
(6489, '00:19:73', 'Zeugma Systems'),
(6490, '00:19:74', 'AboCom Systems, Inc.'),
(6491, '00:19:75', 'Beijing Huisen networks technology Inc'),
(6492, '00:19:76', 'Xipher Technologies, LLC'),
(6493, '00:19:77', 'Aerohive Networks, Inc.'),
(6494, '00:19:78', 'Datum Systems, Inc.'),
(6495, '00:19:79', 'Nokia Danmark A/S'),
(6496, '00:19:7A', 'MAZeT GmbH'),
(6497, '00:19:7B', 'Picotest Corp.'),
(6498, '00:19:7C', 'Riedel Communications GmbH'),
(6499, '00:19:7D', 'Hon Hai Precision Ind. Co., Ltd'),
(6500, '00:19:7E', 'Hon Hai Precision Ind. Co., Ltd'),
(6501, '00:19:7F', 'PLANTRONICS, INC.'),
(6502, '00:19:80', 'Gridpoint Systems'),
(6503, '00:19:81', 'Vivox Inc'),
(6504, '00:19:82', 'SmarDTV'),
(6505, '00:19:83', 'CCT R&amp;D Limited'),
(6506, '00:19:84', 'ESTIC Corporation'),
(6507, '00:19:85', 'IT Watchdogs, Inc'),
(6508, '00:19:86', 'Cheng Hongjian'),
(6509, '00:19:87', 'Panasonic Mobile Communications Co., Ltd.'),
(6510, '00:19:88', 'Wi2Wi, Inc'),
(6511, '00:19:89', 'Sonitrol Corporation'),
(6512, '00:19:8A', 'Northrop Grumman Systems Corp.'),
(6513, '00:19:8B', 'Novera Optics Korea, Inc.'),
(6514, '00:19:8C', 'iXSea'),
(6515, '00:19:8D', 'Ocean Optics, Inc.'),
(6516, '00:19:8E', 'Oticon A/S'),
(6517, '00:19:8F', 'Alcatel Bell N.V.'),
(6518, '00:19:90', 'ELM DATA Co., Ltd.'),
(6519, '00:19:91', 'avinfo'),
(6520, '00:19:92', 'ADTRAN INC.'),
(6521, '00:19:93', 'Changshu Switchgear MFG. Co.,Ltd. (Former Changshu Switchgea'),
(6522, '00:19:94', 'Jorjin Technologies Inc.'),
(6523, '00:19:95', 'Jurong Hi-Tech (Suzhou)Co.ltd'),
(6524, '00:19:96', 'TurboChef Technologies Inc.'),
(6525, '00:19:97', 'Soft Device Sdn Bhd'),
(6526, '00:19:98', 'SATO CORPORATION'),
(6527, '00:19:99', 'Fujitsu Technology Solutions'),
(6528, '00:19:9A', 'EDO-EVI'),
(6529, '00:19:9B', 'Diversified Technical Systems, Inc.'),
(6530, '00:19:9C', 'CTRING'),
(6531, '00:19:9D', 'VIZIO, Inc.'),
(6532, '00:19:9E', 'Nifty'),
(6533, '00:19:9F', 'DKT A/S'),
(6534, '00:19:A0', 'NIHON DATA SYSTENS, INC.'),
(6535, '00:19:A1', 'LG INFORMATION &amp; COMM.'),
(6536, '00:19:A2', 'ORDYN TECHNOLOGIES'),
(6537, '00:19:A3', 'asteel electronique atlantique'),
(6538, '00:19:A4', 'Austar Technology (hang zhou) Co.,Ltd'),
(6539, '00:19:A5', 'RadarFind Corporation'),
(6540, '00:19:A6', 'ARRIS Group, Inc.'),
(6541, '00:19:A7', 'ITU-T'),
(6542, '00:19:A8', 'WiQuest Communications'),
(6543, '00:19:A9', 'CISCO SYSTEMS, INC.'),
(6544, '00:19:AA', 'CISCO SYSTEMS, INC.'),
(6545, '00:19:AB', 'Raycom CO ., LTD'),
(6546, '00:19:AC', 'GSP SYSTEMS Inc.'),
(6547, '00:19:AD', 'BOBST SA'),
(6548, '00:19:AE', 'Hopling Technologies b.v.'),
(6549, '00:19:AF', 'Rigol Technologies, Inc.'),
(6550, '00:19:B0', 'HanYang System'),
(6551, '00:19:B1', 'Arrow7 Corporation'),
(6552, '00:19:B2', 'XYnetsoft Co.,Ltd'),
(6553, '00:19:B3', 'Stanford Research Systems'),
(6554, '00:19:B4', 'Intellio Ltd'),
(6555, '00:19:B5', 'Famar Fueguina S.A.'),
(6556, '00:19:B6', 'Euro Emme s.r.l.'),
(6557, '00:19:B7', 'Nokia Danmark A/S'),
(6558, '00:19:B8', 'Boundary Devices'),
(6559, '00:19:B9', 'Dell Inc.'),
(6560, '00:19:BA', 'Paradox Security Systems Ltd'),
(6561, '00:19:BB', 'Hewlett-Packard Company'),
(6562, '00:19:BC', 'ELECTRO CHANCE SRL'),
(6563, '00:19:BD', 'New Media Life'),
(6564, '00:19:BE', 'Altai Technologies Limited'),
(6565, '00:19:BF', 'Citiway technology Co.,ltd'),
(6566, '00:19:C0', 'ARRIS Group, Inc.'),
(6567, '00:19:C1', 'Alps Electric Co., Ltd'),
(6568, '00:19:C2', 'Equustek Solutions, Inc.'),
(6569, '00:19:C3', 'Qualitrol'),
(6570, '00:19:C4', 'Infocrypt Inc.'),
(6571, '00:19:C5', 'SONY Computer Entertainment inc,'),
(6572, '00:19:C6', 'ZTE Corporation'),
(6573, '00:19:C7', 'Cambridge Industries(Group) Co.,Ltd.'),
(6574, '00:19:C8', 'AnyDATA Corporation'),
(6575, '00:19:C9', 'S&amp;C ELECTRIC COMPANY'),
(6576, '00:19:CA', 'Broadata Communications, Inc'),
(6577, '00:19:CB', 'ZyXEL Communications Corporation'),
(6578, '00:19:CC', 'RCG (HK) Ltd'),
(6579, '00:19:CD', 'Chengdu ethercom information technology Ltd.'),
(6580, '00:19:CE', 'Progressive Gaming International'),
(6581, '00:19:CF', 'SALICRU, S.A.'),
(6582, '00:19:D0', 'Cathexis'),
(6583, '00:19:D1', 'Intel Corporate'),
(6584, '00:19:D2', 'Intel Corporate'),
(6585, '00:19:D3', 'TRAK Microwave'),
(6586, '00:19:D4', 'ICX Technologies'),
(6587, '00:19:D5', 'IP Innovations, Inc.'),
(6588, '00:19:D6', 'LS Cable and System Ltd.'),
(6589, '00:19:D7', 'FORTUNETEK CO., LTD'),
(6590, '00:19:D8', 'MAXFOR'),
(6591, '00:19:D9', 'Zeutschel GmbH'),
(6592, '00:19:DA', 'Welltrans O&amp;E Technology Co. , Ltd.'),
(6593, '00:19:DB', 'MICRO-STAR INTERNATIONAL CO., LTD.'),
(6594, '00:19:DC', 'ENENSYS Technologies'),
(6595, '00:19:DD', 'FEI-Zyfer, Inc.'),
(6596, '00:19:DE', 'MOBITEK'),
(6597, '00:19:DF', 'Thomson Inc.'),
(6598, '00:19:E0', 'TP-LINK Technologies Co., Ltd.'),
(6599, '00:19:E1', 'Nortel'),
(6600, '00:19:E2', 'Juniper Networks'),
(6601, '00:19:E3', 'Apple'),
(6602, '00:19:E4', '2Wire, Inc'),
(6603, '00:19:E5', 'Lynx Studio Technology, Inc.'),
(6604, '00:19:E6', 'TOYO MEDIC CO.,LTD.'),
(6605, '00:19:E7', 'CISCO SYSTEMS, INC.'),
(6606, '00:19:E8', 'CISCO SYSTEMS, INC.'),
(6607, '00:19:E9', 'S-Information Technolgy, Co., Ltd.'),
(6608, '00:19:EA', 'TeraMage Technologies Co., Ltd.'),
(6609, '00:19:EB', 'Pyronix Ltd'),
(6610, '00:19:EC', 'Sagamore Systems, Inc.'),
(6611, '00:19:ED', 'Axesstel Inc.'),
(6612, '00:19:EE', 'CARLO GAVAZZI CONTROLS SPA-Controls Division'),
(6613, '00:19:EF', 'SHENZHEN LINNKING ELECTRONICS CO.,LTD'),
(6614, '00:19:F0', 'UNIONMAN TECHNOLOGY CO.,LTD'),
(6615, '00:19:F1', 'Star Communication Network Technology Co.,Ltd'),
(6616, '00:19:F2', 'Teradyne K.K.'),
(6617, '00:19:F3', 'Cetis, Inc'),
(6618, '00:19:F4', 'Convergens Oy Ltd'),
(6619, '00:19:F5', 'Imagination Technologies Ltd'),
(6620, '00:19:F6', 'Acconet (PTE) Ltd'),
(6621, '00:19:F7', 'Onset Computer Corporation'),
(6622, '00:19:F8', 'Embedded Systems Design, Inc.'),
(6623, '00:19:F9', 'TDK-Lambda'),
(6624, '00:19:FA', 'Cable Vision Electronics CO., LTD.'),
(6625, '00:19:FB', 'BSkyB Ltd'),
(6626, '00:19:FC', 'PT. Ufoakses Sukses Luarbiasa'),
(6627, '00:19:FD', 'Nintendo Co., Ltd.'),
(6628, '00:19:FE', 'SHENZHEN SEECOMM TECHNOLOGY CO.,LTD.'),
(6629, '00:19:FF', 'Finnzymes'),
(6630, '00:1A:00', 'MATRIX INC.'),
(6631, '00:1A:01', 'Smiths Medical'),
(6632, '00:1A:02', 'SECURE CARE PRODUCTS, INC'),
(6633, '00:1A:03', 'Angel Electronics Co., Ltd.'),
(6634, '00:1A:04', 'Interay Solutions BV'),
(6635, '00:1A:05', 'OPTIBASE LTD'),
(6636, '00:1A:06', 'OpVista, Inc.'),
(6637, '00:1A:07', 'Arecont Vision'),
(6638, '00:1A:08', 'Simoco Ltd.'),
(6639, '00:1A:09', 'Wayfarer Transit Systems Ltd'),
(6640, '00:1A:0A', 'Adaptive Micro-Ware Inc.'),
(6641, '00:1A:0B', 'BONA TECHNOLOGY INC.'),
(6642, '00:1A:0C', 'Swe-Dish Satellite Systems AB'),
(6643, '00:1A:0D', 'HandHeld entertainment, Inc.'),
(6644, '00:1A:0E', 'Cheng Uei Precision Industry Co.,Ltd'),
(6645, '00:1A:0F', 'Sistemas Avanzados de Control, S.A.'),
(6646, '00:1A:10', 'LUCENT TRANS ELECTRONICS CO.,LTD'),
(6647, '00:1A:11', 'Google Inc.'),
(6648, '00:1A:12', 'Essilor'),
(6649, '00:1A:13', 'Wanlida Group Co., LTD'),
(6650, '00:1A:14', 'Xin Hua Control Engineering Co.,Ltd.'),
(6651, '00:1A:15', 'gemalto e-Payment'),
(6652, '00:1A:16', 'Nokia Danmark A/S'),
(6653, '00:1A:17', 'Teak Technologies, Inc.'),
(6654, '00:1A:18', 'Advanced Simulation Technology inc.'),
(6655, '00:1A:19', 'Computer Engineering Limited'),
(6656, '00:1A:1A', 'Gentex Corporation/Electro-Acoustic Products'),
(6657, '00:1A:1B', 'ARRIS Group, Inc.'),
(6658, '00:1A:1C', 'GT&amp;T Engineering Pte Ltd'),
(6659, '00:1A:1D', 'PChome Online Inc.'),
(6660, '00:1A:1E', 'Aruba Networks'),
(6661, '00:1A:1F', 'Coastal Environmental Systems'),
(6662, '00:1A:20', 'CMOTECH Co. Ltd.'),
(6663, '00:1A:21', 'Indac B.V.'),
(6664, '00:1A:22', 'eQ-3 Entwicklung GmbH'),
(6665, '00:1A:23', 'Ice Qube, Inc'),
(6666, '00:1A:24', 'Galaxy Telecom Technologies Ltd'),
(6667, '00:1A:25', 'DELTA DORE'),
(6668, '00:1A:26', 'Deltanode Solutions AB'),
(6669, '00:1A:27', 'Ubistar'),
(6670, '00:1A:28', 'ASWT Co., LTD. Taiwan Branch H.K.'),
(6671, '00:1A:29', 'Johnson Outdoors Marine Electronics, Inc'),
(6672, '00:1A:2A', 'Arcadyan Technology Corporation'),
(6673, '00:1A:2B', 'Ayecom Technology Co., Ltd.'),
(6674, '00:1A:2C', 'SATEC Co.,LTD'),
(6675, '00:1A:2D', 'The Navvo Group'),
(6676, '00:1A:2E', 'Ziova Coporation'),
(6677, '00:1A:2F', 'CISCO SYSTEMS, INC.'),
(6678, '00:1A:30', 'CISCO SYSTEMS, INC.'),
(6679, '00:1A:31', 'SCAN COIN Industries AB'),
(6680, '00:1A:32', 'ACTIVA MULTIMEDIA'),
(6681, '00:1A:33', 'ASI Communications, Inc.'),
(6682, '00:1A:34', 'Konka Group Co., Ltd.'),
(6683, '00:1A:35', 'BARTEC GmbH'),
(6684, '00:1A:36', 'Aipermon GmbH &amp; Co. KG'),
(6685, '00:1A:37', 'Lear Corporation'),
(6686, '00:1A:38', 'Sanmina-SCI'),
(6687, '00:1A:39', 'Merten GmbH&amp;CoKG'),
(6688, '00:1A:3A', 'Dongahelecomm'),
(6689, '00:1A:3B', 'Doah Elecom Inc.'),
(6690, '00:1A:3C', 'Technowave Ltd.'),
(6691, '00:1A:3D', 'Ajin Vision Co.,Ltd'),
(6692, '00:1A:3E', 'Faster Technology LLC'),
(6693, '00:1A:3F', 'intelbras'),
(6694, '00:1A:40', 'A-FOUR TECH CO., LTD.'),
(6695, '00:1A:41', 'INOCOVA Co.,Ltd'),
(6696, '00:1A:42', 'Techcity Technology co., Ltd.'),
(6697, '00:1A:43', 'Logical Link Communications'),
(6698, '00:1A:44', 'JWTrading Co., Ltd'),
(6699, '00:1A:45', 'GN Netcom as'),
(6700, '00:1A:46', 'Digital Multimedia Technology Co., Ltd'),
(6701, '00:1A:47', 'Agami Systems, Inc.'),
(6702, '00:1A:48', 'Takacom Corporation'),
(6703, '00:1A:49', 'Micro Vision Co.,LTD'),
(6704, '00:1A:4A', 'Qumranet Inc.'),
(6705, '00:1A:4B', 'Hewlett-Packard Company'),
(6706, '00:1A:4C', 'Crossbow Technology, Inc'),
(6707, '00:1A:4D', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(6708, '00:1A:4E', 'NTI AG / LinMot'),
(6709, '00:1A:4F', 'AVM GmbH'),
(6710, '00:1A:50', 'PheeNet Technology Corp.'),
(6711, '00:1A:51', 'Alfred Mann Foundation'),
(6712, '00:1A:52', 'Meshlinx Wireless Inc.'),
(6713, '00:1A:53', 'Zylaya'),
(6714, '00:1A:54', 'Hip Shing Electronics Ltd.'),
(6715, '00:1A:55', 'ACA-Digital Corporation'),
(6716, '00:1A:56', 'ViewTel Co,. Ltd.'),
(6717, '00:1A:57', 'Matrix Design Group, LLC'),
(6718, '00:1A:58', 'CCV Deutschland GmbH - Celectronic eHealth Div.'),
(6719, '00:1A:59', 'Ircona'),
(6720, '00:1A:5A', 'Korea Electric Power Data Network  (KDN) Co., Ltd'),
(6721, '00:1A:5B', 'NetCare Service Co., Ltd.'),
(6722, '00:1A:5C', 'Euchner GmbH+Co. KG'),
(6723, '00:1A:5D', 'Mobinnova Corp.'),
(6724, '00:1A:5E', 'Thincom Technology Co.,Ltd'),
(6725, '00:1A:5F', 'KitWorks.fi Ltd.'),
(6726, '00:1A:60', 'Wave Electronics Co.,Ltd.'),
(6727, '00:1A:61', 'PacStar Corp.'),
(6728, '00:1A:62', 'Data Robotics, Incorporated'),
(6729, '00:1A:63', 'Elster Solutions, LLC,'),
(6730, '00:1A:64', 'IBM Corp'),
(6731, '00:1A:65', 'Seluxit'),
(6732, '00:1A:66', 'ARRIS Group, Inc.'),
(6733, '00:1A:67', 'Infinite QL Sdn Bhd'),
(6734, '00:1A:68', 'Weltec Enterprise Co., Ltd.'),
(6735, '00:1A:69', 'Wuhan Yangtze Optical Technology CO.,Ltd.'),
(6736, '00:1A:6A', 'Tranzas, Inc.'),
(6737, '00:1A:6B', 'Universal Global Scientific Industrial Co., Ltd.'),
(6738, '00:1A:6C', 'CISCO SYSTEMS, INC.'),
(6739, '00:1A:6D', 'CISCO SYSTEMS, INC.'),
(6740, '00:1A:6E', 'Impro Technologies'),
(6741, '00:1A:6F', 'MI.TEL s.r.l.'),
(6742, '00:1A:70', 'Cisco-Linksys, LLC'),
(6743, '00:1A:71', 'Diostech Co., Ltd.'),
(6744, '00:1A:72', 'Mosart Semiconductor Corp.'),
(6745, '00:1A:73', 'Gemtek Technology Co., Ltd.'),
(6746, '00:1A:74', 'Procare International Co'),
(6747, '00:1A:75', 'Sony Ericsson Mobile Communications'),
(6748, '00:1A:76', 'SDT information Technology Co.,LTD.'),
(6749, '00:1A:77', 'ARRIS Group, Inc.'),
(6750, '00:1A:78', 'ubtos'),
(6751, '00:1A:79', 'TELECOMUNICATION TECHNOLOGIES LTD.'),
(6752, '00:1A:7A', 'Lismore Instruments Limited'),
(6753, '00:1A:7B', 'Teleco, Inc.'),
(6754, '00:1A:7C', 'Hirschmann Multimedia B.V.'),
(6755, '00:1A:7D', 'cyber-blue(HK)Ltd'),
(6756, '00:1A:7E', 'LN Srithai Comm Ltd.'),
(6757, '00:1A:7F', 'GCI Science&amp;Technology Co.,Ltd.'),
(6758, '00:1A:80', 'Sony Corporation'),
(6759, '00:1A:81', 'Zelax'),
(6760, '00:1A:82', 'PROBA Building Automation Co.,LTD'),
(6761, '00:1A:83', 'Pegasus Technologies Inc.'),
(6762, '00:1A:84', 'V One Multimedia Pte Ltd'),
(6763, '00:1A:85', 'NV Michel Van de Wiele'),
(6764, '00:1A:86', 'AdvancedIO Systems Inc'),
(6765, '00:1A:87', 'Canhold International Limited'),
(6766, '00:1A:88', 'Venergy,Co,Ltd'),
(6767, '00:1A:89', 'Nokia Danmark A/S'),
(6768, '00:1A:8A', 'Samsung Electronics Co., Ltd.'),
(6769, '00:1A:8B', 'CHUNIL ELECTRIC IND., CO.'),
(6770, '00:1A:8C', 'Sophos Ltd'),
(6771, '00:1A:8D', 'AVECS Bergen GmbH'),
(6772, '00:1A:8E', '3Way Networks Ltd'),
(6773, '00:1A:8F', 'Nortel'),
(6774, '00:1A:90', 'Tr&oacute;pico Sistemas e Telecomunica&ccedil;&otilde;es da Amaz&ocirc;nia LTDA.'),
(6775, '00:1A:91', 'FusionDynamic Ltd.'),
(6776, '00:1A:92', 'ASUSTek COMPUTER INC.'),
(6777, '00:1A:93', 'ERCO Leuchten GmbH'),
(6778, '00:1A:94', 'Votronic GmbH'),
(6779, '00:1A:95', 'Hisense Mobile Communications Technoligy Co.,Ltd.'),
(6780, '00:1A:96', 'ECLER S.A.'),
(6781, '00:1A:97', 'fitivision technology Inc.'),
(6782, '00:1A:98', 'Asotel Communication Limited Taiwan Branch'),
(6783, '00:1A:99', 'Smarty (HZ) Information Electronics Co., Ltd'),
(6784, '00:1A:9A', 'Skyworth Digital technology(shenzhen)co.ltd.'),
(6785, '00:1A:9B', 'ADEC &amp; Parter AG'),
(6786, '00:1A:9C', 'RightHand Technologies, Inc.'),
(6787, '00:1A:9D', 'Skipper Wireless, Inc.'),
(6788, '00:1A:9E', 'ICON Digital International Limited'),
(6789, '00:1A:9F', 'A-Link Ltd'),
(6790, '00:1A:A0', 'Dell Inc'),
(6791, '00:1A:A1', 'CISCO SYSTEMS, INC.'),
(6792, '00:1A:A2', 'CISCO SYSTEMS, INC.'),
(6793, '00:1A:A3', 'DELORME'),
(6794, '00:1A:A4', 'Future University-Hakodate'),
(6795, '00:1A:A5', 'BRN Phoenix'),
(6796, '00:1A:A6', 'Telefunken Radio Communication Systems GmbH &amp;CO.KG'),
(6797, '00:1A:A7', 'Torian Wireless'),
(6798, '00:1A:A8', 'Mamiya Digital Imaging Co., Ltd.'),
(6799, '00:1A:A9', 'FUJIAN STAR-NET COMMUNICATION CO.,LTD'),
(6800, '00:1A:AA', 'Analogic Corp.'),
(6801, '00:1A:AB', 'eWings s.r.l.'),
(6802, '00:1A:AC', 'Corelatus AB'),
(6803, '00:1A:AD', 'ARRIS Group, Inc.'),
(6804, '00:1A:AE', 'Savant Systems LLC'),
(6805, '00:1A:AF', 'BLUSENS TECHNOLOGY'),
(6806, '00:1A:B0', 'Signal Networks Pvt. Ltd.,'),
(6807, '00:1A:B1', 'Asia Pacific Satellite Industries Co., Ltd.'),
(6808, '00:1A:B2', 'Cyber Solutions Inc.'),
(6809, '00:1A:B3', 'VISIONITE INC.'),
(6810, '00:1A:B4', 'FFEI Ltd.'),
(6811, '00:1A:B5', 'Home Network System'),
(6812, '00:1A:B6', 'Texas Instruments'),
(6813, '00:1A:B7', 'Ethos Networks LTD.'),
(6814, '00:1A:B8', 'Anseri Corporation'),
(6815, '00:1A:B9', 'PMC'),
(6816, '00:1A:BA', 'Caton Overseas Limited'),
(6817, '00:1A:BB', 'Fontal Technology Incorporation'),
(6818, '00:1A:BC', 'U4EA Technologies Ltd'),
(6819, '00:1A:BD', 'Impatica Inc.'),
(6820, '00:1A:BE', 'COMPUTER HI-TECH INC.'),
(6821, '00:1A:BF', 'TRUMPF Laser Marking Systems AG'),
(6822, '00:1A:C0', 'JOYBIEN TECHNOLOGIES CO., LTD.'),
(6823, '00:1A:C1', '3Com Ltd'),
(6824, '00:1A:C2', 'YEC Co.,Ltd.'),
(6825, '00:1A:C3', 'Scientific-Atlanta, Inc'),
(6826, '00:1A:C4', '2Wire, Inc'),
(6827, '00:1A:C5', 'BreakingPoint Systems, Inc.'),
(6828, '00:1A:C6', 'Micro Control Designs'),
(6829, '00:1A:C7', 'UNIPOINT'),
(6830, '00:1A:C8', 'ISL (Instrumentation Scientifique de Laboratoire)'),
(6831, '00:1A:C9', 'SUZUKEN CO.,LTD'),
(6832, '00:1A:CA', 'Tilera Corporation'),
(6833, '00:1A:CB', 'Autocom Products Ltd'),
(6834, '00:1A:CC', 'Celestial Semiconductor, Ltd'),
(6835, '00:1A:CD', 'Tidel Engineering LP'),
(6836, '00:1A:CE', 'YUPITERU CORPORATION'),
(6837, '00:1A:CF', 'C.T. ELETTRONICA'),
(6838, '00:1A:D0', 'Albis Technologies AG'),
(6839, '00:1A:D1', 'FARGO CO., LTD.'),
(6840, '00:1A:D2', 'Eletronica Nitron Ltda'),
(6841, '00:1A:D3', 'Vamp Ltd.'),
(6842, '00:1A:D4', 'iPOX Technology Co., Ltd.'),
(6843, '00:1A:D5', 'KMC CHAIN INDUSTRIAL CO., LTD.'),
(6844, '00:1A:D6', 'JIAGNSU AETNA ELECTRIC CO.,LTD'),
(6845, '00:1A:D7', 'Christie Digital Systems, Inc.'),
(6846, '00:1A:D8', 'AlsterAero GmbH'),
(6847, '00:1A:D9', 'International Broadband Electric Communications, Inc.'),
(6848, '00:1A:DA', 'Biz-2-Me Inc.'),
(6849, '00:1A:DB', 'ARRIS Group, Inc.'),
(6850, '00:1A:DC', 'Nokia Danmark A/S'),
(6851, '00:1A:DD', 'PePWave Ltd'),
(6852, '00:1A:DE', 'ARRIS Group, Inc.'),
(6853, '00:1A:DF', 'Interactivetv Pty Limited'),
(6854, '00:1A:E0', 'Mythology Tech Express Inc.'),
(6855, '00:1A:E1', 'EDGE ACCESS INC'),
(6856, '00:1A:E2', 'CISCO SYSTEMS, INC.'),
(6857, '00:1A:E3', 'CISCO SYSTEMS, INC.'),
(6858, '00:1A:E4', 'Medicis Technologies Corporation'),
(6859, '00:1A:E5', 'Mvox Technologies Inc.'),
(6860, '00:1A:E6', 'Atlanta Advanced Communications Holdings Limited'),
(6861, '00:1A:E7', 'Aztek Networks, Inc.'),
(6862, '00:1A:E8', 'Unify GmbH and Co KG'),
(6863, '00:1A:E9', 'Nintendo Co., Ltd.'),
(6864, '00:1A:EA', 'Radio Terminal Systems Pty Ltd'),
(6865, '00:1A:EB', 'Allied Telesis K.K.'),
(6866, '00:1A:EC', 'Keumbee Electronics Co.,Ltd.'),
(6867, '00:1A:ED', 'INCOTEC GmbH'),
(6868, '00:1A:EE', 'Shenztech Ltd'),
(6869, '00:1A:EF', 'Loopcomm Technology, Inc.'),
(6870, '00:1A:F0', 'Alcatel - IPD'),
(6871, '00:1A:F1', 'Embedded Artists AB'),
(6872, '00:1A:F2', 'Dynavisions Schweiz AG'),
(6873, '00:1A:F3', 'Samyoung Electronics'),
(6874, '00:1A:F4', 'Handreamnet'),
(6875, '00:1A:F5', 'PENTAONE. CO., LTD.'),
(6876, '00:1A:F6', 'Woven Systems, Inc.'),
(6877, '00:1A:F7', 'dataschalt e+a GmbH'),
(6878, '00:1A:F8', 'Copley Controls Corporation'),
(6879, '00:1A:F9', 'AeroVIronment (AV Inc)'),
(6880, '00:1A:FA', 'Welch Allyn, Inc.'),
(6881, '00:1A:FB', 'Joby Inc.'),
(6882, '00:1A:FC', 'ModusLink Corporation'),
(6883, '00:1A:FD', 'EVOLIS'),
(6884, '00:1A:FE', 'SOFACREAL'),
(6885, '00:1A:FF', 'Wizyoung Tech.'),
(6886, '00:1B:00', 'Neopost Technologies'),
(6887, '00:1B:01', 'Applied Radio Technologies'),
(6888, '00:1B:02', 'ED Co.Ltd'),
(6889, '00:1B:03', 'Action Technology (SZ) Co., Ltd'),
(6890, '00:1B:04', 'Affinity International S.p.a'),
(6891, '00:1B:05', 'YMC AG'),
(6892, '00:1B:06', 'Ateliers R. LAUMONIER'),
(6893, '00:1B:07', 'Mendocino Software'),
(6894, '00:1B:08', 'Danfoss Drives A/S'),
(6895, '00:1B:09', 'Matrix Telecom Pvt. Ltd.'),
(6896, '00:1B:0A', 'Intelligent Distributed Controls Ltd'),
(6897, '00:1B:0B', 'Phidgets Inc.'),
(6898, '00:1B:0C', 'CISCO SYSTEMS, INC.'),
(6899, '00:1B:0D', 'CISCO SYSTEMS, INC.'),
(6900, '00:1B:0E', 'InoTec GmbH Organisationssysteme'),
(6901, '00:1B:0F', 'Petratec'),
(6902, '00:1B:10', 'ShenZhen Kang Hui Technology Co.,ltd'),
(6903, '00:1B:11', 'D-Link Corporation'),
(6904, '00:1B:12', 'Apprion'),
(6905, '00:1B:13', 'Icron Technologies Corporation'),
(6906, '00:1B:14', 'Carex Lighting Equipment Factory'),
(6907, '00:1B:15', 'Voxtel, Inc.'),
(6908, '00:1B:16', 'Celtro Ltd.'),
(6909, '00:1B:17', 'Palo Alto Networks'),
(6910, '00:1B:18', 'Tsuken Electric Ind. Co.,Ltd'),
(6911, '00:1B:19', 'IEEE I&amp;M Society TC9'),
(6912, '00:1B:1A', 'e-trees Japan, Inc.'),
(6913, '00:1B:1B', 'Siemens AG,'),
(6914, '00:1B:1C', 'Coherent'),
(6915, '00:1B:1D', 'Phoenix International Co., Ltd'),
(6916, '00:1B:1E', 'HART Communication Foundation'),
(6917, '00:1B:1F', 'DELTA - Danish Electronics, Light &amp; Acoustics'),
(6918, '00:1B:20', 'TPine Technology'),
(6919, '00:1B:21', 'Intel Corporate'),
(6920, '00:1B:22', 'Palit Microsystems ( H.K.) Ltd.'),
(6921, '00:1B:23', 'SimpleComTools'),
(6922, '00:1B:24', 'Quanta Computer Inc.'),
(6923, '00:1B:25', 'Nortel'),
(6924, '00:1B:26', 'RON-Telecom ZAO'),
(6925, '00:1B:27', 'Merlin CSI'),
(6926, '00:1B:28', 'POLYGON, JSC'),
(6927, '00:1B:29', 'Avantis.Co.,Ltd'),
(6928, '00:1B:2A', 'CISCO SYSTEMS, INC.'),
(6929, '00:1B:2B', 'CISCO SYSTEMS, INC.'),
(6930, '00:1B:2C', 'ATRON electronic GmbH'),
(6931, '00:1B:2D', 'Med-Eng Systems Inc.'),
(6932, '00:1B:2E', 'Sinkyo Electron Inc'),
(6933, '00:1B:2F', 'NETGEAR Inc.'),
(6934, '00:1B:30', 'Solitech Inc.'),
(6935, '00:1B:31', 'Neural Image. Co. Ltd.'),
(6936, '00:1B:32', 'QLogic Corporation'),
(6937, '00:1B:33', 'Nokia Danmark A/S'),
(6938, '00:1B:34', 'Focus System Inc.'),
(6939, '00:1B:35', 'ChongQing JINOU Science &amp; Technology Development CO.,Ltd'),
(6940, '00:1B:36', 'Tsubata Engineering Co.,Ltd. (Head Office)'),
(6941, '00:1B:37', 'Computec Oy'),
(6942, '00:1B:38', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(6943, '00:1B:39', 'Proxicast'),
(6944, '00:1B:3A', 'SIMS Corp.'),
(6945, '00:1B:3B', 'Yi-Qing CO., LTD'),
(6946, '00:1B:3C', 'Software Technologies Group,Inc.'),
(6947, '00:1B:3D', 'EuroTel Spa'),
(6948, '00:1B:3E', 'Curtis, Inc.'),
(6949, '00:1B:3F', 'ProCurve Networking by HP'),
(6950, '00:1B:40', 'Network Automation mxc AB'),
(6951, '00:1B:41', 'General Infinity Co.,Ltd.'),
(6952, '00:1B:42', 'Wise &amp; Blue'),
(6953, '00:1B:43', 'Beijing DG Telecommunications equipment Co.,Ltd'),
(6954, '00:1B:44', 'SanDisk Corporation'),
(6955, '00:1B:45', 'ABB AS, Division Automation Products'),
(6956, '00:1B:46', 'Blueone Technology Co.,Ltd'),
(6957, '00:1B:47', 'Futarque A/S'),
(6958, '00:1B:48', 'Shenzhen Lantech Electronics Co., Ltd.'),
(6959, '00:1B:49', 'Roberts Radio limited'),
(6960, '00:1B:4A', 'W&amp;W Communications, Inc.'),
(6961, '00:1B:4B', 'SANION Co., Ltd.'),
(6962, '00:1B:4C', 'Signtech'),
(6963, '00:1B:4D', 'Areca Technology Corporation'),
(6964, '00:1B:4E', 'Navman New Zealand'),
(6965, '00:1B:4F', 'Avaya Inc.'),
(6966, '00:1B:50', 'Nizhny Novgorod Factory named after M.Frunze, FSUE (NZiF)'),
(6967, '00:1B:51', 'Vector Technology Corp.'),
(6968, '00:1B:52', 'ARRIS Group, Inc.'),
(6969, '00:1B:53', 'CISCO SYSTEMS, INC.'),
(6970, '00:1B:54', 'CISCO SYSTEMS, INC.'),
(6971, '00:1B:55', 'Hurco Automation Ltd.'),
(6972, '00:1B:56', 'Tehuti Networks Ltd.'),
(6973, '00:1B:57', 'SEMINDIA SYSTEMS PRIVATE LIMITED'),
(6974, '00:1B:58', 'ACE CAD Enterprise Co., Ltd.'),
(6975, '00:1B:59', 'Sony Ericsson Mobile Communications AB'),
(6976, '00:1B:5A', 'Apollo Imaging Technologies, Inc.'),
(6977, '00:1B:5B', '2Wire, Inc.'),
(6978, '00:1B:5C', 'Azuretec Co., Ltd.'),
(6979, '00:1B:5D', 'Vololink Pty Ltd'),
(6980, '00:1B:5E', 'BPL Limited'),
(6981, '00:1B:5F', 'Alien Technology'),
(6982, '00:1B:60', 'NAVIGON AG'),
(6983, '00:1B:61', 'Digital Acoustics, LLC'),
(6984, '00:1B:62', 'JHT Optoelectronics Co.,Ltd.'),
(6985, '00:1B:63', 'Apple'),
(6986, '00:1B:64', 'IsaacLandKorea Co., Ltd,'),
(6987, '00:1B:65', 'China Gridcom Co., Ltd'),
(6988, '00:1B:66', 'Sennheiser electronic GmbH &amp; Co. KG'),
(6989, '00:1B:67', 'Cisco Systems Inc'),
(6990, '00:1B:68', 'Modnnet Co., Ltd'),
(6991, '00:1B:69', 'Equaline Corporation'),
(6992, '00:1B:6A', 'Powerwave Technologies Sweden AB'),
(6993, '00:1B:6B', 'Swyx Solutions AG'),
(6994, '00:1B:6C', 'LookX Digital Media BV'),
(6995, '00:1B:6D', 'Midtronics, Inc.'),
(6996, '00:1B:6E', 'Anue Systems, Inc.'),
(6997, '00:1B:6F', 'Teletrak Ltd'),
(6998, '00:1B:70', 'IRI Ubiteq, INC.'),
(6999, '00:1B:71', 'Telular Corp.'),
(7000, '00:1B:72', 'Sicep s.p.a.'),
(7001, '00:1B:73', 'DTL Broadcast Ltd'),
(7002, '00:1B:74', 'MiraLink Corporation'),
(7003, '00:1B:75', 'Hypermedia Systems'),
(7004, '00:1B:76', 'Ripcode, Inc.'),
(7005, '00:1B:77', 'Intel Corporate'),
(7006, '00:1B:78', 'Hewlett-Packard Company'),
(7007, '00:1B:79', 'FAIVELEY TRANSPORT'),
(7008, '00:1B:7A', 'Nintendo Co., Ltd.'),
(7009, '00:1B:7B', 'The Tintometer Ltd'),
(7010, '00:1B:7C', 'A &amp; R Cambridge'),
(7011, '00:1B:7D', 'CXR Anderson Jacobson'),
(7012, '00:1B:7E', 'Beckmann GmbH'),
(7013, '00:1B:7F', 'TMN Technologies Telecomunicacoes Ltda'),
(7014, '00:1B:80', 'LORD Corporation'),
(7015, '00:1B:81', 'DATAQ Instruments, Inc.'),
(7016, '00:1B:82', 'Taiwan Semiconductor Co., Ltd.'),
(7017, '00:1B:83', 'Finsoft Ltd'),
(7018, '00:1B:84', 'Scan Engineering Telecom'),
(7019, '00:1B:85', 'MAN Diesel SE'),
(7020, '00:1B:86', 'Bosch Access Systems GmbH'),
(7021, '00:1B:87', 'Deepsound Tech. Co., Ltd'),
(7022, '00:1B:88', 'Divinet Access Technologies Ltd'),
(7023, '00:1B:89', 'EMZA Visual Sense Ltd.'),
(7024, '00:1B:8A', '2M Electronic A/S'),
(7025, '00:1B:8B', 'NEC Platforms, Ltd.'),
(7026, '00:1B:8C', 'JMicron Technology Corp.'),
(7027, '00:1B:8D', 'Electronic Computer Systems, Inc.'),
(7028, '00:1B:8E', 'Hulu Sweden AB'),
(7029, '00:1B:8F', 'CISCO SYSTEMS, INC.'),
(7030, '00:1B:90', 'CISCO SYSTEMS, INC.'),
(7031, '00:1B:91', 'EFKON AG'),
(7032, '00:1B:92', 'l-acoustics'),
(7033, '00:1B:93', 'JC Decaux SA DNT'),
(7034, '00:1B:94', 'T.E.M.A. S.p.A.'),
(7035, '00:1B:95', 'VIDEO SYSTEMS SRL'),
(7036, '00:1B:96', 'General Sensing'),
(7037, '00:1B:97', 'Violin Technologies'),
(7038, '00:1B:98', 'Samsung Electronics Co., Ltd.'),
(7039, '00:1B:99', 'KS System GmbH'),
(7040, '00:1B:9A', 'Apollo Fire Detectors Ltd'),
(7041, '00:1B:9B', 'Hose-McCann Communications'),
(7042, '00:1B:9C', 'SATEL sp. z o.o.'),
(7043, '00:1B:9D', 'Novus Security Sp. z o.o.'),
(7044, '00:1B:9E', 'ASKEY  COMPUTER  CORP'),
(7045, '00:1B:9F', 'Calyptech Pty Ltd'),
(7046, '00:1B:A0', 'Awox'),
(7047, '00:1B:A1', '&Aring;mic AB'),
(7048, '00:1B:A2', 'IDS Imaging Development Systems GmbH'),
(7049, '00:1B:A3', 'Flexit Group GmbH'),
(7050, '00:1B:A4', 'S.A.E Afikim'),
(7051, '00:1B:A5', 'MyungMin Systems, Inc.'),
(7052, '00:1B:A6', 'intotech inc.'),
(7053, '00:1B:A7', 'Lorica Solutions'),
(7054, '00:1B:A8', 'UBI&amp;MOBI,.Inc'),
(7055, '00:1B:A9', 'BROTHER INDUSTRIES, LTD.'),
(7056, '00:1B:AA', 'XenICs nv'),
(7057, '00:1B:AB', 'Telchemy, Incorporated'),
(7058, '00:1B:AC', 'Curtiss Wright Controls Embedded Computing'),
(7059, '00:1B:AD', 'iControl Incorporated'),
(7060, '00:1B:AE', 'Micro Control Systems, Inc'),
(7061, '00:1B:AF', 'Nokia Danmark A/S'),
(7062, '00:1B:B0', 'BHARAT ELECTRONICS'),
(7063, '00:1B:B1', 'Wistron Neweb Corp.'),
(7064, '00:1B:B2', 'Intellect International NV'),
(7065, '00:1B:B3', 'Condalo GmbH'),
(7066, '00:1B:B4', 'Airvod Limited'),
(7067, '00:1B:B5', 'ZF Electronics GmbH'),
(7068, '00:1B:B6', 'Bird Electronic Corp.'),
(7069, '00:1B:B7', 'Alta Heights Technology Corp.'),
(7070, '00:1B:B8', 'BLUEWAY ELECTRONIC CO;LTD'),
(7071, '00:1B:B9', 'Elitegroup Computer System Co.'),
(7072, '00:1B:BA', 'Nortel'),
(7073, '00:1B:BB', 'RFTech Co.,Ltd'),
(7074, '00:1B:BC', 'Silver Peak Systems, Inc.'),
(7075, '00:1B:BD', 'FMC Kongsberg Subsea AS'),
(7076, '00:1B:BE', 'ICOP Digital'),
(7077, '00:1B:BF', 'SAGEM COMMUNICATION'),
(7078, '00:1B:C0', 'Juniper Networks'),
(7079, '00:1B:C1', 'HOLUX Technology, Inc.'),
(7080, '00:1B:C2', 'Integrated Control Technology Limitied'),
(7081, '00:1B:C3', 'Mobisolution Co.,Ltd'),
(7082, '00:1B:C4', 'Ultratec, Inc.'),
(7083, '00:1B:C5', 'IEEE REGISTRATION AUTHORITY  - Please see OUI36/MA-S public listing for more information.'),
(7084, '00:1B:C6', 'Strato Rechenzentrum AG'),
(7085, '00:1B:C7', 'StarVedia Technology Inc.'),
(7086, '00:1B:C8', 'MIURA CO.,LTD'),
(7087, '00:1B:C9', 'FSN DISPLAY INC'),
(7088, '00:1B:CA', 'Beijing Run Technology LTD. Company'),
(7089, '00:1B:CB', 'PEMPEK SYSTEMS PTY LTD'),
(7090, '00:1B:CC', 'KINGTEK CCTV ALLIANCE CO., LTD.'),
(7091, '00:1B:CD', 'DAVISCOMMS (S) PTE LTD'),
(7092, '00:1B:CE', 'Measurement Devices Ltd'),
(7093, '00:1B:CF', 'Dataupia Corporation'),
(7094, '00:1B:D0', 'IDENTEC SOLUTIONS'),
(7095, '00:1B:D1', 'SOGESTMATIC'),
(7096, '00:1B:D2', 'ULTRA-X ASIA PACIFIC Inc.'),
(7097, '00:1B:D3', 'Panasonic Corp. AVC Company'),
(7098, '00:1B:D4', 'CISCO SYSTEMS, INC.'),
(7099, '00:1B:D5', 'CISCO SYSTEMS, INC.'),
(7100, '00:1B:D6', 'Kelvin Hughes Ltd'),
(7101, '00:1B:D7', 'Scientific Atlanta, A Cisco Company'),
(7102, '00:1B:D8', 'DVTel LTD'),
(7103, '00:1B:D9', 'Edgewater Computer Systems'),
(7104, '00:1B:DA', 'UTStarcom Inc'),
(7105, '00:1B:DB', 'Valeo VECS'),
(7106, '00:1B:DC', 'Vencer Co., Ltd.'),
(7107, '00:1B:DD', 'ARRIS Group, Inc.'),
(7108, '00:1B:DE', 'Renkus-Heinz, Inc.'),
(7109, '00:1B:DF', 'Iskra Sistemi d.d.'),
(7110, '00:1B:E0', 'TELENOT ELECTRONIC GmbH'),
(7111, '00:1B:E1', 'ViaLogy'),
(7112, '00:1B:E2', 'AhnLab,Inc.'),
(7113, '00:1B:E3', 'Health Hero Network, Inc.'),
(7114, '00:1B:E4', 'TOWNET SRL'),
(7115, '00:1B:E5', '802automation Limited'),
(7116, '00:1B:E6', 'VR AG'),
(7117, '00:1B:E7', 'Postek Electronics Co., Ltd.'),
(7118, '00:1B:E8', 'Ultratronik GmbH'),
(7119, '00:1B:E9', 'Broadcom Corporation'),
(7120, '00:1B:EA', 'Nintendo Co., Ltd.'),
(7121, '00:1B:EB', 'DMP Electronics INC.'),
(7122, '00:1B:EC', 'Netio Technologies Co., Ltd'),
(7123, '00:1B:ED', 'Brocade Communications Systems, Inc'),
(7124, '00:1B:EE', 'Nokia Danmark A/S'),
(7125, '00:1B:EF', 'Blossoms Digital Technology Co.,Ltd.'),
(7126, '00:1B:F0', 'Value Platforms Limited'),
(7127, '00:1B:F1', 'Nanjing SilverNet Software Co., Ltd.'),
(7128, '00:1B:F2', 'KWORLD COMPUTER CO., LTD'),
(7129, '00:1B:F3', 'TRANSRADIO SenderSysteme Berlin AG'),
(7130, '00:1B:F4', 'KENWIN INDUSTRIAL(HK) LTD.'),
(7131, '00:1B:F5', 'Tellink Sistemas de Telecomunicaci&oacute;n S.L.'),
(7132, '00:1B:F6', 'CONWISE Technology Corporation Ltd.'),
(7133, '00:1B:F7', 'Lund IP Products AB'),
(7134, '00:1B:F8', 'Digitrax Inc.'),
(7135, '00:1B:F9', 'Intellitect Water Ltd'),
(7136, '00:1B:FA', 'G.i.N. mbH'),
(7137, '00:1B:FB', 'Alps Electric Co., Ltd'),
(7138, '00:1B:FC', 'ASUSTek COMPUTER INC.'),
(7139, '00:1B:FD', 'Dignsys Inc.'),
(7140, '00:1B:FE', 'Zavio Inc.'),
(7141, '00:1B:FF', 'Millennia Media inc.'),
(7142, '00:1C:00', 'Entry Point, LLC'),
(7143, '00:1C:01', 'ABB Oy Drives'),
(7144, '00:1C:02', 'Pano Logic'),
(7145, '00:1C:03', 'Betty TV Technology AG'),
(7146, '00:1C:04', 'Airgain, Inc.'),
(7147, '00:1C:05', 'Nonin Medical Inc.'),
(7148, '00:1C:06', 'Siemens Numerical Control Ltd., Nanjing'),
(7149, '00:1C:07', 'Cwlinux Limited'),
(7150, '00:1C:08', 'Echo360, Inc.'),
(7151, '00:1C:09', 'SAE Electronic Co.,Ltd.'),
(7152, '00:1C:0A', 'Shenzhen AEE Technology Co.,Ltd.'),
(7153, '00:1C:0B', 'SmartAnt Telecom'),
(7154, '00:1C:0C', 'TANITA Corporation'),
(7155, '00:1C:0D', 'G-Technology, Inc.'),
(7156, '00:1C:0E', 'CISCO SYSTEMS, INC.'),
(7157, '00:1C:0F', 'CISCO SYSTEMS, INC.'),
(7158, '00:1C:10', 'Cisco-Linksys, LLC'),
(7159, '00:1C:11', 'ARRIS Group, Inc.'),
(7160, '00:1C:12', 'ARRIS Group, Inc.'),
(7161, '00:1C:13', 'OPTSYS TECHNOLOGY CO., LTD.'),
(7162, '00:1C:14', 'VMware, Inc'),
(7163, '00:1C:15', 'iPhotonix LLC'),
(7164, '00:1C:16', 'ThyssenKrupp Elevator'),
(7165, '00:1C:17', 'Nortel'),
(7166, '00:1C:18', 'Sicert S.r.L.'),
(7167, '00:1C:19', 'secunet Security Networks AG'),
(7168, '00:1C:1A', 'Thomas Instrumentation, Inc'),
(7169, '00:1C:1B', 'Hyperstone GmbH'),
(7170, '00:1C:1C', 'Center Communication Systems GmbH'),
(7171, '00:1C:1D', 'CHENZHOU GOSPELL DIGITAL TECHNOLOGY CO.,LTD'),
(7172, '00:1C:1E', 'emtrion GmbH'),
(7173, '00:1C:1F', 'Quest Retail Technology Pty Ltd'),
(7174, '00:1C:20', 'CLB Benelux'),
(7175, '00:1C:21', 'Nucsafe Inc.'),
(7176, '00:1C:22', 'Aeris Elettronica s.r.l.'),
(7177, '00:1C:23', 'Dell Inc'),
(7178, '00:1C:24', 'Formosa Wireless Systems Corp.'),
(7179, '00:1C:25', 'Hon Hai Precision Ind. Co.,Ltd.'),
(7180, '00:1C:26', 'Hon Hai Precision Ind. Co.,Ltd.'),
(7181, '00:1C:27', 'Sunell Electronics Co.'),
(7182, '00:1C:28', 'Sphairon Technologies GmbH'),
(7183, '00:1C:29', 'CORE DIGITAL ELECTRONICS CO., LTD'),
(7184, '00:1C:2A', 'Envisacor Technologies Inc.'),
(7185, '00:1C:2B', 'Alertme.com Limited'),
(7186, '00:1C:2C', 'Synapse'),
(7187, '00:1C:2D', 'FlexRadio Systems'),
(7188, '00:1C:2E', 'HPN Supply Chain'),
(7189, '00:1C:2F', 'Pfister GmbH'),
(7190, '00:1C:30', 'Mode Lighting (UK ) Ltd.'),
(7191, '00:1C:31', 'Mobile XP Technology Co., LTD'),
(7192, '00:1C:32', 'Telian Corporation'),
(7193, '00:1C:33', 'Sutron'),
(7194, '00:1C:34', 'HUEY CHIAO INTERNATIONAL CO., LTD.'),
(7195, '00:1C:35', 'Nokia Danmark A/S'),
(7196, '00:1C:36', 'iNEWiT NV'),
(7197, '00:1C:37', 'Callpod, Inc.'),
(7198, '00:1C:38', 'Bio-Rad Laboratories, Inc.'),
(7199, '00:1C:39', 'S Netsystems Inc.'),
(7200, '00:1C:3A', 'Element Labs, Inc.'),
(7201, '00:1C:3B', 'AmRoad Technology Inc.'),
(7202, '00:1C:3C', 'Seon Design Inc.'),
(7203, '00:1C:3D', 'WaveStorm'),
(7204, '00:1C:3E', 'ECKey Corporation'),
(7205, '00:1C:3F', 'International Police Technologies, Inc.'),
(7206, '00:1C:40', 'VDG-Security bv'),
(7207, '00:1C:41', 'scemtec Transponder Technology GmbH'),
(7208, '00:1C:42', 'Parallels, Inc.'),
(7209, '00:1C:43', 'Samsung Electronics Co.,Ltd'),
(7210, '00:1C:44', 'Bosch Security Systems BV'),
(7211, '00:1C:45', 'Chenbro Micom Co., Ltd.'),
(7212, '00:1C:46', 'QTUM'),
(7213, '00:1C:47', 'Hangzhou Hollysys Automation Co., Ltd'),
(7214, '00:1C:48', 'WiDeFi, Inc.'),
(7215, '00:1C:49', 'Zoltan Technology Inc.'),
(7216, '00:1C:4A', 'AVM GmbH'),
(7217, '00:1C:4B', 'Gener8, Inc.'),
(7218, '00:1C:4C', 'Petrotest Instruments'),
(7219, '00:1C:4D', 'Aplix IP Holdings Corporation'),
(7220, '00:1C:4E', 'TASA International Limited'),
(7221, '00:1C:4F', 'MACAB AB'),
(7222, '00:1C:50', 'TCL Technoly Electronics(Huizhou)Co.,Ltd'),
(7223, '00:1C:51', 'Celeno Communications'),
(7224, '00:1C:52', 'VISIONEE SRL'),
(7225, '00:1C:53', 'Synergy Lighting Controls'),
(7226, '00:1C:54', 'Hillstone Networks Inc'),
(7227, '00:1C:55', 'Shenzhen Kaifa Technology Co.'),
(7228, '00:1C:56', 'Pado Systems, Inc.'),
(7229, '00:1C:57', 'CISCO SYSTEMS, INC.'),
(7230, '00:1C:58', 'CISCO SYSTEMS, INC.'),
(7231, '00:1C:59', 'DEVON IT'),
(7232, '00:1C:5A', 'Advanced Relay Corporation'),
(7233, '00:1C:5B', 'Chubb Electronic Security Systems Ltd'),
(7234, '00:1C:5C', 'Integrated Medical Systems, Inc.'),
(7235, '00:1C:5D', 'Leica Microsystems'),
(7236, '00:1C:5E', 'ASTON France'),
(7237, '00:1C:5F', 'Winland Electronics, Inc.'),
(7238, '00:1C:60', 'CSP Frontier Technologies,Inc.'),
(7239, '00:1C:61', 'Galaxy  Microsystems LImited'),
(7240, '00:1C:62', 'LG Electronics Inc'),
(7241, '00:1C:63', 'TRUEN'),
(7242, '00:1C:64', 'Landis+Gyr'),
(7243, '00:1C:65', 'JoeScan, Inc.'),
(7244, '00:1C:66', 'UCAMP CO.,LTD'),
(7245, '00:1C:67', 'Pumpkin Networks, Inc.'),
(7246, '00:1C:68', 'Anhui Sun Create Electronics Co., Ltd'),
(7247, '00:1C:69', 'Packet Vision Ltd'),
(7248, '00:1C:6A', 'Weiss Engineering Ltd.'),
(7249, '00:1C:6B', 'COVAX  Co. Ltd'),
(7250, '00:1C:6C', 'Jabil Circuit (Guangzhou) Limited'),
(7251, '00:1C:6D', 'KYOHRITSU ELECTRONIC INDUSTRY CO., LTD.'),
(7252, '00:1C:6E', 'Newbury Networks, Inc.'),
(7253, '00:1C:6F', 'Emfit Ltd'),
(7254, '00:1C:70', 'NOVACOMM LTDA'),
(7255, '00:1C:71', 'Emergent Electronics'),
(7256, '00:1C:72', 'Mayer &amp; Cie GmbH &amp; Co KG'),
(7257, '00:1C:73', 'Arista Networks, Inc.'),
(7258, '00:1C:74', 'Syswan Technologies Inc.'),
(7259, '00:1C:75', 'Segnet Ltd.'),
(7260, '00:1C:76', 'The Wandsworth Group Ltd'),
(7261, '00:1C:77', 'Prodys'),
(7262, '00:1C:78', 'WYPLAY SAS'),
(7263, '00:1C:79', 'Cohesive Financial Technologies LLC'),
(7264, '00:1C:7A', 'Perfectone Netware Company Ltd'),
(7265, '00:1C:7B', 'Castlenet Technology Inc.'),
(7266, '00:1C:7C', 'PERQ SYSTEMS CORPORATION'),
(7267, '00:1C:7D', 'Excelpoint Manufacturing Pte Ltd'),
(7268, '00:1C:7E', 'Toshiba'),
(7269, '00:1C:7F', 'Check Point Software Technologies'),
(7270, '00:1C:80', 'New Business Division/Rhea-Information CO., LTD.'),
(7271, '00:1C:81', 'NextGen Venturi LTD'),
(7272, '00:1C:82', 'Genew Technologies'),
(7273, '00:1C:83', 'New Level Telecom Co., Ltd.'),
(7274, '00:1C:84', 'STL Solution Co.,Ltd.'),
(7275, '00:1C:85', 'Eunicorn'),
(7276, '00:1C:86', 'Cranite Systems, Inc.'),
(7277, '00:1C:87', 'Uriver Inc.'),
(7278, '00:1C:88', 'TRANSYSTEM INC.'),
(7279, '00:1C:89', 'Force Communications, Inc.'),
(7280, '00:1C:8A', 'Cirrascale Corporation'),
(7281, '00:1C:8B', 'MJ Innovations Ltd.'),
(7282, '00:1C:8C', 'DIAL TECHNOLOGY LTD.'),
(7283, '00:1C:8D', 'Mesa Imaging'),
(7284, '00:1C:8E', 'Alcatel-Lucent IPD'),
(7285, '00:1C:8F', 'Advanced Electronic Design, Inc.'),
(7286, '00:1C:90', 'Empacket Corporation'),
(7287, '00:1C:91', 'Gefen Inc.'),
(7288, '00:1C:92', 'Tervela'),
(7289, '00:1C:93', 'ExaDigm Inc'),
(7290, '00:1C:94', 'LI-COR Biosciences'),
(7291, '00:1C:95', 'Opticomm Corporation'),
(7292, '00:1C:96', 'Linkwise Technology Pte Ltd'),
(7293, '00:1C:97', 'Enzytek Technology Inc.,'),
(7294, '00:1C:98', 'LUCKY TECHNOLOGY (HK) COMPANY LIMITED'),
(7295, '00:1C:99', 'Shunra Software Ltd.'),
(7296, '00:1C:9A', 'Nokia Danmark A/S'),
(7297, '00:1C:9B', 'FEIG ELECTRONIC GmbH'),
(7298, '00:1C:9C', 'Nortel'),
(7299, '00:1C:9D', 'Liecthi AG'),
(7300, '00:1C:9E', 'Dualtech IT AB'),
(7301, '00:1C:9F', 'Razorstream, LLC'),
(7302, '00:1C:A0', 'Production Resource Group, LLC'),
(7303, '00:1C:A1', 'AKAMAI TECHNOLOGIES, INC.'),
(7304, '00:1C:A2', 'ADB Broadband Italia'),
(7305, '00:1C:A3', 'Terra'),
(7306, '00:1C:A4', 'Sony Ericsson Mobile Communications'),
(7307, '00:1C:A5', 'Zygo Corporation'),
(7308, '00:1C:A6', 'Win4NET'),
(7309, '00:1C:A7', 'International Quartz Limited'),
(7310, '00:1C:A8', 'AirTies Wireless Networks'),
(7311, '00:1C:A9', 'Audiomatica Srl'),
(7312, '00:1C:AA', 'Bellon Pty Ltd'),
(7313, '00:1C:AB', 'Meyer Sound Laboratories, Inc.'),
(7314, '00:1C:AC', 'Qniq Technology Corp.'),
(7315, '00:1C:AD', 'Wuhan Telecommunication Devices Co.,Ltd'),
(7316, '00:1C:AE', 'WiChorus, Inc.'),
(7317, '00:1C:AF', 'Plato Networks Inc.'),
(7318, '00:1C:B0', 'CISCO SYSTEMS, INC.'),
(7319, '00:1C:B1', 'CISCO SYSTEMS, INC.'),
(7320, '00:1C:B2', 'BPT SPA'),
(7321, '00:1C:B3', 'Apple'),
(7322, '00:1C:B4', 'Iridium Satellite LLC'),
(7323, '00:1C:B5', 'Neihua Network Technology Co.,LTD.(NHN)'),
(7324, '00:1C:B6', 'Duzon CNT Co., Ltd.'),
(7325, '00:1C:B7', 'USC DigiArk Corporation'),
(7326, '00:1C:B8', 'CBC Co., Ltd'),
(7327, '00:1C:B9', 'KWANG SUNG ELECTRONICS CO., LTD.'),
(7328, '00:1C:BA', 'VerScient, Inc.'),
(7329, '00:1C:BB', 'MusicianLink'),
(7330, '00:1C:BC', 'CastGrabber, LLC'),
(7331, '00:1C:BD', 'Ezze Mobile Tech., Inc.'),
(7332, '00:1C:BE', 'Nintendo Co., Ltd.'),
(7333, '00:1C:BF', 'Intel Corporate'),
(7334, '00:1C:C0', 'Intel Corporate'),
(7335, '00:1C:C1', 'ARRIS Group, Inc.'),
(7336, '00:1C:C2', 'Part II Research, Inc.'),
(7337, '00:1C:C3', 'Pace plc'),
(7338, '00:1C:C4', 'Hewlett-Packard Company'),
(7339, '00:1C:C5', '3COM LTD'),
(7340, '00:1C:C6', 'ProStor Systems'),
(7341, '00:1C:C7', 'Rembrandt Technologies, LLC d/b/a REMSTREAM'),
(7342, '00:1C:C8', 'INDUSTRONIC Industrie-Electronic GmbH &amp; Co. KG'),
(7343, '00:1C:C9', 'Kaise Electronic Technology Co., Ltd.'),
(7344, '00:1C:CA', 'Shanghai Gaozhi Science &amp; Technology Development Co.'),
(7345, '00:1C:CB', 'Forth Corporation Public Company Limited'),
(7346, '00:1C:CC', 'Research In Motion Limited'),
(7347, '00:1C:CD', 'Alektrona Corporation'),
(7348, '00:1C:CE', 'By Techdesign'),
(7349, '00:1C:CF', 'LIMETEK'),
(7350, '00:1C:D0', 'Circleone Co.,Ltd.'),
(7351, '00:1C:D1', 'Waves Audio LTD'),
(7352, '00:1C:D2', 'King Champion (Hong Kong) Limited'),
(7353, '00:1C:D3', 'ZP Engineering SEL'),
(7354, '00:1C:D4', 'Nokia Danmark A/S'),
(7355, '00:1C:D5', 'ZeeVee, Inc.'),
(7356, '00:1C:D6', 'Nokia Danmark A/S'),
(7357, '00:1C:D7', 'Harman/Becker Automotive Systems GmbH'),
(7358, '00:1C:D8', 'BlueAnt Wireless'),
(7359, '00:1C:D9', 'GlobalTop Technology Inc.'),
(7360, '00:1C:DA', 'Exegin Technologies Limited'),
(7361, '00:1C:DB', 'CARPOINT CO.,LTD'),
(7362, '00:1C:DC', 'Custom Computer Services, Inc.'),
(7363, '00:1C:DD', 'COWBELL ENGINEERING CO., LTD.'),
(7364, '00:1C:DE', 'Interactive Multimedia eXchange Inc.'),
(7365, '00:1C:DF', 'Belkin International Inc.'),
(7366, '00:1C:E0', 'DASAN TPS'),
(7367, '00:1C:E1', 'INDRA SISTEMAS, S.A.'),
(7368, '00:1C:E2', 'Attero Tech, LLC.'),
(7369, '00:1C:E3', 'Optimedical Systems'),
(7370, '00:1C:E4', 'EleSy JSC'),
(7371, '00:1C:E5', 'MBS Electronic Systems GmbH'),
(7372, '00:1C:E6', 'INNES'),
(7373, '00:1C:E7', 'Rocon PLC Research Centre'),
(7374, '00:1C:E8', 'Cummins Inc'),
(7375, '00:1C:E9', 'Galaxy Technology Limited'),
(7376, '00:1C:EA', 'Scientific-Atlanta, Inc'),
(7377, '00:1C:EB', 'Nortel'),
(7378, '00:1C:EC', 'Mobilesoft (Aust.) Pty Ltd'),
(7379, '00:1C:ED', 'ENVIRONNEMENT SA'),
(7380, '00:1C:EE', 'SHARP Corporation'),
(7381, '00:1C:EF', 'Primax Electronics LTD'),
(7382, '00:1C:F0', 'D-Link Corporation'),
(7383, '00:1C:F1', 'SUPoX Technology Co. , LTD.'),
(7384, '00:1C:F2', 'Tenlon Technology Co.,Ltd.'),
(7385, '00:1C:F3', 'EVS BROADCAST EQUIPMENT'),
(7386, '00:1C:F4', 'Media Technology Systems Inc'),
(7387, '00:1C:F5', 'Wiseblue Technology Limited'),
(7388, '00:1C:F6', 'CISCO SYSTEMS, INC.'),
(7389, '00:1C:F7', 'AudioScience'),
(7390, '00:1C:F8', 'Parade Technologies, Ltd.'),
(7391, '00:1C:F9', 'CISCO SYSTEMS, INC.'),
(7392, '00:1C:FA', 'Alarm.com'),
(7393, '00:1C:FB', 'ARRIS Group, Inc.'),
(7394, '00:1C:FC', 'Suminet Communication Technologies (Shanghai) Co., Ltd.'),
(7395, '00:1C:FD', 'Universal Electronics'),
(7396, '00:1C:FE', 'Quartics Inc'),
(7397, '00:1C:FF', 'Napera Networks Inc'),
(7398, '00:1D:00', 'Brivo Systems, LLC'),
(7399, '00:1D:01', 'Neptune Digital'),
(7400, '00:1D:02', 'Cybertech Telecom Development'),
(7401, '00:1D:03', 'Design Solutions Inc.'),
(7402, '00:1D:04', 'Zipit Wireless, Inc.'),
(7403, '00:1D:05', 'Eaton Corporation'),
(7404, '00:1D:06', 'HM Electronics, Inc.'),
(7405, '00:1D:07', 'Shenzhen Sang Fei Consumer Communications Co.,Ltd'),
(7406, '00:1D:08', 'JIANGSU YINHE ELECTRONICS CO., LTD'),
(7407, '00:1D:09', 'Dell Inc'),
(7408, '00:1D:0A', 'Davis Instruments, Inc.'),
(7409, '00:1D:0B', 'Power Standards Lab'),
(7410, '00:1D:0C', 'MobileCompia'),
(7411, '00:1D:0D', 'Sony Computer Entertainment inc.'),
(7412, '00:1D:0E', 'Agapha Technology co., Ltd.'),
(7413, '00:1D:0F', 'TP-LINK Technologies Co., Ltd.'),
(7414, '00:1D:10', 'LightHaus Logic, Inc.'),
(7415, '00:1D:11', 'Analogue &amp; Micro Ltd'),
(7416, '00:1D:12', 'ROHM CO., LTD.'),
(7417, '00:1D:13', 'NextGTV'),
(7418, '00:1D:14', 'SPERADTONE INFORMATION TECHNOLOGY LIMITED'),
(7419, '00:1D:15', 'Shenzhen Dolphin Electronic Co., Ltd'),
(7420, '00:1D:16', 'SFR'),
(7421, '00:1D:17', 'Digital Sky Corporation'),
(7422, '00:1D:18', 'Power Innovation GmbH'),
(7423, '00:1D:19', 'Arcadyan Technology Corporation'),
(7424, '00:1D:1A', 'OvisLink S.A.'),
(7425, '00:1D:1B', 'Sangean Electronics Inc.'),
(7426, '00:1D:1C', 'Gennet s.a.'),
(7427, '00:1D:1D', 'Inter-M Corporation'),
(7428, '00:1D:1E', 'KYUSHU TEN CO.,LTD'),
(7429, '00:1D:1F', 'Siauliu Tauro Televizoriai, JSC'),
(7430, '00:1D:20', 'COMTREND CO.'),
(7431, '00:1D:21', 'Alcad SL'),
(7432, '00:1D:22', 'Foss Analytical A/S'),
(7433, '00:1D:23', 'SENSUS'),
(7434, '00:1D:24', 'Aclara Power-Line Systems Inc.'),
(7435, '00:1D:25', 'Samsung Electronics Co.,Ltd'),
(7436, '00:1D:26', 'Rockridgesound Technology Co.'),
(7437, '00:1D:27', 'NAC-INTERCOM'),
(7438, '00:1D:28', 'Sony Ericsson Mobile Communications AB'),
(7439, '00:1D:29', 'Doro AB'),
(7440, '00:1D:2A', 'SHENZHEN BUL-TECH CO.,LTD.'),
(7441, '00:1D:2B', 'Wuhan Pont Technology CO. , LTD'),
(7442, '00:1D:2C', 'Wavetrend Technologies (Pty) Limited'),
(7443, '00:1D:2D', 'Pylone, Inc.'),
(7444, '00:1D:2E', 'Ruckus Wireless'),
(7445, '00:1D:2F', 'QuantumVision Corporation'),
(7446, '00:1D:30', 'YX Wireless S.A.'),
(7447, '00:1D:31', 'HIGHPRO INTERNATIONAL R&amp;D CO,.LTD.'),
(7448, '00:1D:32', 'Longkay Communication &amp; Technology (Shanghai) Co. Ltd'),
(7449, '00:1D:33', 'Maverick Systems Inc.'),
(7450, '00:1D:34', 'SYRIS Technology Corp'),
(7451, '00:1D:35', 'Viconics Electronics Inc.'),
(7452, '00:1D:36', 'ELECTRONICS CORPORATION OF INDIA LIMITED'),
(7453, '00:1D:37', 'Thales-Panda Transportation System'),
(7454, '00:1D:38', 'Seagate Technology'),
(7455, '00:1D:39', 'MOOHADIGITAL CO., LTD'),
(7456, '00:1D:3A', 'mh acoustics LLC'),
(7457, '00:1D:3B', 'Nokia Danmark A/S'),
(7458, '00:1D:3C', 'Muscle Corporation'),
(7459, '00:1D:3D', 'Avidyne Corporation'),
(7460, '00:1D:3E', 'SAKA TECHNO SCIENCE CO.,LTD'),
(7461, '00:1D:3F', 'Mitron Pty Ltd'),
(7462, '00:1D:40', ' Intel &ndash; GE Care Innovations LLC'),
(7463, '00:1D:41', 'Hardy Instruments'),
(7464, '00:1D:42', 'Nortel'),
(7465, '00:1D:43', 'Shenzhen G-link Digital Technology Co., Ltd.'),
(7466, '00:1D:44', 'KROHNE Messtechnik GmbH'),
(7467, '00:1D:45', 'CISCO SYSTEMS, INC.'),
(7468, '00:1D:46', 'CISCO SYSTEMS, INC.'),
(7469, '00:1D:47', 'Covote GmbH &amp; Co KG'),
(7470, '00:1D:48', 'Sensor-Technik Wiedemann GmbH'),
(7471, '00:1D:49', 'Innovation Wireless Inc.'),
(7472, '00:1D:4A', 'Carestream Health, Inc.'),
(7473, '00:1D:4B', 'Grid Connect Inc.'),
(7474, '00:1D:4C', 'Alcatel-Lucent'),
(7475, '00:1D:4D', 'Adaptive Recognition Hungary, Inc'),
(7476, '00:1D:4E', 'TCM Mobile LLC'),
(7477, '00:1D:4F', 'Apple'),
(7478, '00:1D:50', 'SPINETIX SA'),
(7479, '00:1D:51', 'Babcock &amp; Wilcox Power Generation Group, Inc'),
(7480, '00:1D:52', 'Defzone B.V.'),
(7481, '00:1D:53', 'S&amp;O Electronics (Malaysia) Sdn. Bhd.'),
(7482, '00:1D:54', 'Sunnic Technology &amp; Merchandise INC.'),
(7483, '00:1D:55', 'ZANTAZ, Inc'),
(7484, '00:1D:56', 'Kramer Electronics Ltd.'),
(7485, '00:1D:57', 'CAETEC Messtechnik'),
(7486, '00:1D:58', 'CQ Inc'),
(7487, '00:1D:59', 'Mitra Energy &amp; Infrastructure'),
(7488, '00:1D:5A', '2Wire Inc.'),
(7489, '00:1D:5B', 'Tecvan Inform&aacute;tica Ltda'),
(7490, '00:1D:5C', 'Tom Communication Industrial Co.,Ltd.'),
(7491, '00:1D:5D', 'Control Dynamics Pty. Ltd.'),
(7492, '00:1D:5E', 'COMING MEDIA CORP.'),
(7493, '00:1D:5F', 'OverSpeed SARL'),
(7494, '00:1D:60', 'ASUSTek COMPUTER INC.'),
(7495, '00:1D:61', 'BIJ Corporation'),
(7496, '00:1D:62', 'InPhase Technologies'),
(7497, '00:1D:63', 'Miele &amp; Cie. KG'),
(7498, '00:1D:64', 'Adam Communications Systems Int Ltd'),
(7499, '00:1D:65', 'Microwave Radio Communications'),
(7500, '00:1D:66', 'Hyundai Telecom'),
(7501, '00:1D:67', 'AMEC'),
(7502, '00:1D:68', 'Thomson Telecom Belgium'),
(7503, '00:1D:69', 'Knorr-Bremse IT-Services GmbH'),
(7504, '00:1D:6A', 'Alpha Networks Inc.'),
(7505, '00:1D:6B', 'ARRIS Group, Inc.'),
(7506, '00:1D:6C', 'ClariPhy Communications, Inc.'),
(7507, '00:1D:6D', 'Confidant International LLC'),
(7508, '00:1D:6E', 'Nokia Danmark A/S'),
(7509, '00:1D:6F', 'Chainzone Technology Co., Ltd'),
(7510, '00:1D:70', 'CISCO SYSTEMS, INC.'),
(7511, '00:1D:71', 'CISCO SYSTEMS, INC.'),
(7512, '00:1D:72', 'Wistron Corporation'),
(7513, '00:1D:73', 'Buffalo Inc.'),
(7514, '00:1D:74', 'Tianjin China-Silicon Microelectronics Co., Ltd.'),
(7515, '00:1D:75', 'Radioscape PLC'),
(7516, '00:1D:76', 'Eyeheight Ltd.'),
(7517, '00:1D:77', 'NSGate'),
(7518, '00:1D:78', 'Invengo Information Technology Co.,Ltd'),
(7519, '00:1D:79', 'SIGNAMAX LLC'),
(7520, '00:1D:7A', 'Wideband Semiconductor, Inc.'),
(7521, '00:1D:7B', 'Ice Energy, Inc.'),
(7522, '00:1D:7C', 'ABE Elettronica S.p.A.'),
(7523, '00:1D:7D', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(7524, '00:1D:7E', 'Cisco-Linksys, LLC'),
(7525, '00:1D:7F', 'Tekron International Ltd'),
(7526, '00:1D:80', 'Beijing Huahuan Eletronics Co.,Ltd'),
(7527, '00:1D:81', 'GUANGZHOU GATEWAY ELECTRONICS CO., LTD'),
(7528, '00:1D:82', 'GN A/S (GN Netcom A/S)'),
(7529, '00:1D:83', 'Emitech Corporation'),
(7530, '00:1D:84', 'Gateway, Inc.'),
(7531, '00:1D:85', 'Call Direct Cellular Solutions'),
(7532, '00:1D:86', 'Shinwa Industries(China) Ltd.'),
(7533, '00:1D:87', 'VigTech Labs Sdn Bhd'),
(7534, '00:1D:88', 'Clearwire'),
(7535, '00:1D:89', 'VaultStor Corporation'),
(7536, '00:1D:8A', 'TechTrex Inc'),
(7537, '00:1D:8B', 'ADB Broadband Italia'),
(7538, '00:1D:8C', 'La Crosse Technology LTD'),
(7539, '00:1D:8D', 'Raytek GmbH'),
(7540, '00:1D:8E', 'Alereon, Inc.'),
(7541, '00:1D:8F', 'PureWave Networks'),
(7542, '00:1D:90', 'EMCO Flow Systems'),
(7543, '00:1D:91', 'Digitize, Inc'),
(7544, '00:1D:92', 'MICRO-STAR INT\'L CO.,LTD.'),
(7545, '00:1D:93', 'Modacom'),
(7546, '00:1D:94', 'Climax Technology Co., Ltd'),
(7547, '00:1D:95', 'Flash, Inc.'),
(7548, '00:1D:96', 'WatchGuard Video'),
(7549, '00:1D:97', 'Alertus Technologies LLC'),
(7550, '00:1D:98', 'Nokia Danmark A/S'),
(7551, '00:1D:99', 'Cyan Optic, Inc.'),
(7552, '00:1D:9A', 'GODEX INTERNATIONAL CO., LTD'),
(7553, '00:1D:9B', 'Hokuyo Automatic Co., Ltd.'),
(7554, '00:1D:9C', 'Rockwell Automation'),
(7555, '00:1D:9D', 'ARTJOY INTERNATIONAL LIMITED'),
(7556, '00:1D:9E', 'AXION TECHNOLOGIES'),
(7557, '00:1D:9F', 'MATT   R.P.Traczynscy Sp.J.'),
(7558, '00:1D:A0', 'Heng Yu Electronic Manufacturing Company Limited'),
(7559, '00:1D:A1', 'CISCO SYSTEMS, INC.'),
(7560, '00:1D:A2', 'CISCO SYSTEMS, INC.'),
(7561, '00:1D:A3', 'SabiOso'),
(7562, '00:1D:A4', 'Hangzhou System Technology CO., LTD'),
(7563, '00:1D:A5', 'WB Electronics'),
(7564, '00:1D:A6', 'Media Numerics Limited'),
(7565, '00:1D:A7', 'Seamless Internet'),
(7566, '00:1D:A8', 'Takahata Electronics Co.,Ltd'),
(7567, '00:1D:A9', 'Castles Technology, Co., LTD'),
(7568, '00:1D:AA', 'DrayTek Corp.'),
(7569, '00:1D:AB', 'SwissQual License AG'),
(7570, '00:1D:AC', 'Gigamon Systems LLC'),
(7571, '00:1D:AD', 'Sinotech Engineering Consultants, Inc.  Geotechnical Enginee'),
(7572, '00:1D:AE', 'CHANG TSENG TECHNOLOGY CO., LTD'),
(7573, '00:1D:AF', 'Nortel'),
(7574, '00:1D:B0', 'FuJian HengTong Information Technology Co.,Ltd'),
(7575, '00:1D:B1', 'Crescendo Networks'),
(7576, '00:1D:B2', 'HOKKAIDO ELECTRIC ENGINEERING CO.,LTD.'),
(7577, '00:1D:B3', 'HPN Supply Chain'),
(7578, '00:1D:B4', 'KUMHO ENG CO.,LTD'),
(7579, '00:1D:B5', 'Juniper networks'),
(7580, '00:1D:B6', 'BestComm Networks, Inc.'),
(7581, '00:1D:B7', 'Tendril Networks, Inc.'),
(7582, '00:1D:B8', 'Intoto Inc.'),
(7583, '00:1D:B9', 'Wellspring Wireless'),
(7584, '00:1D:BA', 'Sony Corporation'),
(7585, '00:1D:BB', 'Dynamic System Electronics Corp.'),
(7586, '00:1D:BC', 'Nintendo Co., Ltd.'),
(7587, '00:1D:BD', 'Versamed Inc.'),
(7588, '00:1D:BE', 'ARRIS Group, Inc.'),
(7589, '00:1D:BF', 'Radiient Technologies, Inc.'),
(7590, '00:1D:C0', 'Enphase Energy'),
(7591, '00:1D:C1', 'Audinate Pty L'),
(7592, '00:1D:C2', 'XORTEC OY'),
(7593, '00:1D:C3', 'RIKOR TV, Ltd'),
(7594, '00:1D:C4', 'AIOI Systems Co., Ltd.'),
(7595, '00:1D:C5', 'Beijing Jiaxun Feihong Electricial Co., Ltd.'),
(7596, '00:1D:C6', 'SNR Inc.'),
(7597, '00:1D:C7', 'L-3 Communications Geneva Aerospace'),
(7598, '00:1D:C8', 'Navionics Research Inc., dba SCADAmetrics'),
(7599, '00:1D:C9', 'GainSpan Corp.'),
(7600, '00:1D:CA', 'PAV Electronics Limited'),
(7601, '00:1D:CB', 'Ex&eacute;ns Development Oy'),
(7602, '00:1D:CC', 'Hetra Secure Solutions'),
(7603, '00:1D:CD', 'ARRIS Group, Inc.'),
(7604, '00:1D:CE', 'ARRIS Group, Inc.'),
(7605, '00:1D:CF', 'ARRIS Group, Inc.'),
(7606, '00:1D:D0', 'ARRIS Group, Inc.'),
(7607, '00:1D:D1', 'ARRIS Group, Inc.'),
(7608, '00:1D:D2', 'ARRIS Group, Inc.'),
(7609, '00:1D:D3', 'ARRIS Group, Inc.'),
(7610, '00:1D:D4', 'ARRIS Group, Inc.'),
(7611, '00:1D:D5', 'ARRIS Group, Inc.'),
(7612, '00:1D:D6', 'ARRIS Group, Inc.'),
(7613, '00:1D:D7', 'Algolith'),
(7614, '00:1D:D8', 'Microsoft Corporation'),
(7615, '00:1D:D9', 'Hon Hai Precision Ind.Co.,Ltd.'),
(7616, '00:1D:DA', 'Mikroelektronika spol. s r. o.'),
(7617, '00:1D:DB', 'C-BEL Corporation'),
(7618, '00:1D:DC', 'HangZhou DeChangLong Tech&amp;Info Co.,Ltd'),
(7619, '00:1D:DD', 'DAT H.K. LIMITED'),
(7620, '00:1D:DE', 'Zhejiang Broadcast&amp;Television Technology Co.,Ltd.'),
(7621, '00:1D:DF', 'Sunitec Enterprise Co., Ltd.'),
(7622, '00:1D:E0', 'Intel Corporate'),
(7623, '00:1D:E1', 'Intel Corporate'),
(7624, '00:1D:E2', 'Radionor Communications'),
(7625, '00:1D:E3', 'Intuicom'),
(7626, '00:1D:E4', 'Visioneered Image Systems'),
(7627, '00:1D:E5', 'CISCO SYSTEMS, INC.'),
(7628, '00:1D:E6', 'CISCO SYSTEMS, INC.'),
(7629, '00:1D:E7', 'Marine Sonic Technology, Ltd.'),
(7630, '00:1D:E8', 'Nikko Denki Tsushin Corporation(NDTC)'),
(7631, '00:1D:E9', 'Nokia Danmark A/S'),
(7632, '00:1D:EA', 'Commtest Instruments Ltd'),
(7633, '00:1D:EB', 'DINEC International'),
(7634, '00:1D:EC', 'Marusys'),
(7635, '00:1D:ED', 'Grid Net, Inc.'),
(7636, '00:1D:EE', 'NEXTVISION SISTEMAS DIGITAIS DE TELEVIS&Atilde;O LTDA.'),
(7637, '00:1D:EF', 'TRIMM, INC.'),
(7638, '00:1D:F0', 'Vidient Systems, Inc.'),
(7639, '00:1D:F1', 'Intego Systems, Inc.'),
(7640, '00:1D:F2', 'Netflix, Inc.'),
(7641, '00:1D:F3', 'SBS Science &amp; Technology Co., Ltd'),
(7642, '00:1D:F4', 'Magellan Technology Pty Limited'),
(7643, '00:1D:F5', 'Sunshine Co,LTD'),
(7644, '00:1D:F6', 'Samsung Electronics Co.,Ltd'),
(7645, '00:1D:F7', 'R. STAHL Schaltger&auml;te GmbH'),
(7646, '00:1D:F8', 'Webpro Vision Technology Corporation'),
(7647, '00:1D:F9', 'Cybiotronics (Far East) Limited'),
(7648, '00:1D:FA', 'Fujian LANDI Commercial Equipment Co.,Ltd'),
(7649, '00:1D:FB', 'NETCLEUS Systems Corporation'),
(7650, '00:1D:FC', 'KSIC'),
(7651, '00:1D:FD', 'Nokia Danmark A/S'),
(7652, '00:1D:FE', 'Palm, Inc'),
(7653, '00:1D:FF', 'Network Critical Solutions Ltd'),
(7654, '00:1E:00', 'Shantou Institute of Ultrasonic Instruments'),
(7655, '00:1E:01', 'Renesas Technology Sales Co., Ltd.'),
(7656, '00:1E:02', 'Sougou Keikaku Kougyou Co.,Ltd.'),
(7657, '00:1E:03', 'LiComm Co., Ltd.'),
(7658, '00:1E:04', 'Hanson Research Corporation'),
(7659, '00:1E:05', 'Xseed Technologies &amp; Computing'),
(7660, '00:1E:06', 'WIBRAIN'),
(7661, '00:1E:07', 'Winy Technology Co., Ltd.'),
(7662, '00:1E:08', 'Centec Networks Inc'),
(7663, '00:1E:09', 'ZEFATEK Co.,LTD'),
(7664, '00:1E:0A', 'Syba Tech Limited'),
(7665, '00:1E:0B', 'Hewlett-Packard Company'),
(7666, '00:1E:0C', 'Sherwood Information Partners, Inc.'),
(7667, '00:1E:0D', 'Micran Ltd.'),
(7668, '00:1E:0E', 'MAXI VIEW HOLDINGS LIMITED'),
(7669, '00:1E:0F', 'Briot International'),
(7670, '00:1E:10', 'ShenZhen Huawei Communication Technologies Co.,Ltd.'),
(7671, '00:1E:11', 'ELELUX INTERNATIONAL LTD'),
(7672, '00:1E:12', 'Ecolab'),
(7673, '00:1E:13', 'CISCO SYSTEMS, INC.'),
(7674, '00:1E:14', 'CISCO SYSTEMS, INC.'),
(7675, '00:1E:15', 'Beech Hill Electronics'),
(7676, '00:1E:16', 'Keytronix'),
(7677, '00:1E:17', 'STN BV'),
(7678, '00:1E:18', 'Radio Activity srl'),
(7679, '00:1E:19', 'GTRI'),
(7680, '00:1E:1A', 'Best Source Taiwan Inc.'),
(7681, '00:1E:1B', 'Digital Stream Technology, Inc.'),
(7682, '00:1E:1C', 'SWS Australia Pty Limited'),
(7683, '00:1E:1D', 'East Coast Datacom, Inc.'),
(7684, '00:1E:1E', 'Honeywell Life Safety'),
(7685, '00:1E:1F', 'Nortel'),
(7686, '00:1E:20', 'Intertain Inc.'),
(7687, '00:1E:21', 'Qisda Co.'),
(7688, '00:1E:22', 'ARVOO Imaging Products BV'),
(7689, '00:1E:23', 'Electronic Educational Devices, Inc'),
(7690, '00:1E:24', 'Zhejiang Bell Technology Co.,ltd'),
(7691, '00:1E:25', 'Intek Digital Inc'),
(7692, '00:1E:26', 'Digifriends Co. Ltd'),
(7693, '00:1E:27', 'SBN TECH Co.,Ltd.'),
(7694, '00:1E:28', 'Lumexis Corporation'),
(7695, '00:1E:29', 'Hypertherm Inc'),
(7696, '00:1E:2A', 'Netgear Inc.'),
(7697, '00:1E:2B', 'Radio Systems Design, Inc.'),
(7698, '00:1E:2C', 'CyVerse Corporation'),
(7699, '00:1E:2D', 'STIM'),
(7700, '00:1E:2E', 'SIRTI S.p.A.'),
(7701, '00:1E:2F', 'DiMoto Pty Ltd'),
(7702, '00:1E:30', 'Shireen Inc'),
(7703, '00:1E:31', 'INFOMARK CO.,LTD.'),
(7704, '00:1E:32', 'Zensys'),
(7705, '00:1E:33', 'Inventec Corporation'),
(7706, '00:1E:34', 'CryptoMetrics'),
(7707, '00:1E:35', 'Nintendo Co., Ltd.'),
(7708, '00:1E:36', 'IPTE'),
(7709, '00:1E:37', 'Universal Global Scientific Industrial Co., Ltd.'),
(7710, '00:1E:38', 'Bluecard Software Technology Co., Ltd.'),
(7711, '00:1E:39', 'Comsys Communication Ltd.'),
(7712, '00:1E:3A', 'Nokia Danmark A/S'),
(7713, '00:1E:3B', 'Nokia Danmark A/S'),
(7714, '00:1E:3C', 'Lyngbox Media AB'),
(7715, '00:1E:3D', 'Alps Electric Co., Ltd'),
(7716, '00:1E:3E', 'KMW Inc.'),
(7717, '00:1E:3F', 'TrellisWare Technologies, Inc.'),
(7718, '00:1E:40', 'Shanghai DareGlobal Technologies  Co.,Ltd.'),
(7719, '00:1E:41', 'Microwave Communication &amp; Component, Inc.'),
(7720, '00:1E:42', 'Teltonika'),
(7721, '00:1E:43', 'AISIN AW CO.,LTD.'),
(7722, '00:1E:44', 'SANTEC'),
(7723, '00:1E:45', 'Sony Ericsson Mobile Communications AB'),
(7724, '00:1E:46', 'ARRIS Group, Inc.'),
(7725, '00:1E:47', 'PT. Hariff Daya Tunggal Engineering'),
(7726, '00:1E:48', 'Wi-Links'),
(7727, '00:1E:49', 'CISCO SYSTEMS, INC.'),
(7728, '00:1E:4A', 'CISCO SYSTEMS, INC.'),
(7729, '00:1E:4B', 'City Theatrical'),
(7730, '00:1E:4C', 'Hon Hai Precision Ind.Co., Ltd.'),
(7731, '00:1E:4D', 'Welkin Sciences, LLC'),
(7732, '00:1E:4E', 'DAKO EDV-Ingenieur- und Systemhaus GmbH'),
(7733, '00:1E:4F', 'Dell Inc.'),
(7734, '00:1E:50', 'BATTISTONI RESEARCH'),
(7735, '00:1E:51', 'Converter Industry Srl'),
(7736, '00:1E:52', 'Apple'),
(7737, '00:1E:53', 'Further Tech Co., LTD'),
(7738, '00:1E:54', 'TOYO ELECTRIC Corporation'),
(7739, '00:1E:55', 'COWON SYSTEMS,Inc.'),
(7740, '00:1E:56', 'Bally Wulff Entertainment GmbH'),
(7741, '00:1E:57', 'ALCOMA, spol. s r.o.'),
(7742, '00:1E:58', 'D-Link Corporation'),
(7743, '00:1E:59', 'Silicon Turnkey Express, LLC'),
(7744, '00:1E:5A', 'ARRIS Group, Inc.'),
(7745, '00:1E:5B', 'Unitron Company, Inc.'),
(7746, '00:1E:5C', 'RB GeneralEkonomik'),
(7747, '00:1E:5D', 'Holosys d.o.o.'),
(7748, '00:1E:5E', 'COmputime Ltd.'),
(7749, '00:1E:5F', 'KwikByte, LLC'),
(7750, '00:1E:60', 'Digital Lighting Systems, Inc'),
(7751, '00:1E:61', 'ITEC GmbH'),
(7752, '00:1E:62', 'Siemon'),
(7753, '00:1E:63', 'Vibro-Meter SA'),
(7754, '00:1E:64', 'Intel Corporate'),
(7755, '00:1E:65', 'Intel Corporate'),
(7756, '00:1E:66', 'RESOL Elektronische Regelungen GmbH'),
(7757, '00:1E:67', 'Intel Corporate'),
(7758, '00:1E:68', 'Quanta Computer'),
(7759, '00:1E:69', 'Thomson Inc.'),
(7760, '00:1E:6A', 'Beijing Bluexon Technology Co.,Ltd'),
(7761, '00:1E:6B', 'Cisco SPVTG'),
(7762, '00:1E:6C', 'Opaque Systems'),
(7763, '00:1E:6D', 'IT R&amp;D Center'),
(7764, '00:1E:6E', 'Shenzhen First Mile Communications Ltd'),
(7765, '00:1E:6F', 'Magna-Power Electronics, Inc.'),
(7766, '00:1E:70', 'Cobham Defence Communications Ltd'),
(7767, '00:1E:71', 'MIrcom Group of Companies'),
(7768, '00:1E:72', 'PCS'),
(7769, '00:1E:73', 'ZTE CORPORATION'),
(7770, '00:1E:74', 'SAGEM COMMUNICATION'),
(7771, '00:1E:75', 'LG Electronics'),
(7772, '00:1E:76', 'Thermo Fisher Scientific'),
(7773, '00:1E:77', 'Air2App'),
(7774, '00:1E:78', 'Owitek Technology Ltd.,'),
(7775, '00:1E:79', 'CISCO SYSTEMS, INC.'),
(7776, '00:1E:7A', 'CISCO SYSTEMS, INC.'),
(7777, '00:1E:7B', 'R.I.CO. S.r.l.'),
(7778, '00:1E:7C', 'Taiwick Limited'),
(7779, '00:1E:7D', 'Samsung Electronics Co.,Ltd'),
(7780, '00:1E:7E', 'Nortel'),
(7781, '00:1E:7F', 'CBM of America'),
(7782, '00:1E:80', 'Last Mile Ltd.'),
(7783, '00:1E:81', 'CNB Technology Inc.'),
(7784, '00:1E:82', 'SanDisk Corporation'),
(7785, '00:1E:83', 'LAN/MAN Standards Association (LMSC)'),
(7786, '00:1E:84', 'Pika Technologies Inc.'),
(7787, '00:1E:85', 'Lagotek Corporation'),
(7788, '00:1E:86', 'MEL Co.,Ltd.'),
(7789, '00:1E:87', 'Realease Limited'),
(7790, '00:1E:88', 'ANDOR SYSTEM SUPPORT CO., LTD.'),
(7791, '00:1E:89', 'CRFS Limited'),
(7792, '00:1E:8A', 'eCopy, Inc'),
(7793, '00:1E:8B', 'Infra Access Korea Co., Ltd.'),
(7794, '00:1E:8C', 'ASUSTek COMPUTER INC.'),
(7795, '00:1E:8D', 'ARRIS Group, Inc.'),
(7796, '00:1E:8E', 'Hunkeler AG'),
(7797, '00:1E:8F', 'CANON INC.'),
(7798, '00:1E:90', 'Elitegroup Computer Systems Co'),
(7799, '00:1E:91', 'KIMIN Electronic Co., Ltd.'),
(7800, '00:1E:92', 'JEULIN S.A.'),
(7801, '00:1E:93', 'CiriTech Systems Inc'),
(7802, '00:1E:94', 'SUPERCOM TECHNOLOGY CORPORATION'),
(7803, '00:1E:95', 'SIGMALINK'),
(7804, '00:1E:96', 'Sepura Plc'),
(7805, '00:1E:97', 'Medium Link System Technology CO., LTD,'),
(7806, '00:1E:98', 'GreenLine Communications'),
(7807, '00:1E:99', 'Vantanol Industrial Corporation'),
(7808, '00:1E:9A', 'HAMILTON Bonaduz AG'),
(7809, '00:1E:9B', 'San-Eisha, Ltd.'),
(7810, '00:1E:9C', 'Fidustron INC'),
(7811, '00:1E:9D', 'Recall Technologies, Inc.'),
(7812, '00:1E:9E', 'ddm hopt + schuler Gmbh + Co. KG'),
(7813, '00:1E:9F', 'Visioneering Systems, Inc.'),
(7814, '00:1E:A0', 'XLN-t'),
(7815, '00:1E:A1', 'Brunata a/s'),
(7816, '00:1E:A2', 'Symx Systems, Inc.'),
(7817, '00:1E:A3', 'Nokia Danmark A/S'),
(7818, '00:1E:A4', 'Nokia Danmark A/S'),
(7819, '00:1E:A5', 'ROBOTOUS, Inc.'),
(7820, '00:1E:A6', 'Best IT World (India) Pvt. Ltd.'),
(7821, '00:1E:A7', 'ActionTec Electronics, Inc'),
(7822, '00:1E:A8', 'Datang Mobile Communications Equipment CO.,LTD'),
(7823, '00:1E:A9', 'Nintendo Co., Ltd.'),
(7824, '00:1E:AA', 'E-Senza Technologies GmbH'),
(7825, '00:1E:AB', 'TeleWell Oy'),
(7826, '00:1E:AC', 'Armadeus Systems'),
(7827, '00:1E:AD', 'Wingtech Group Limited'),
(7828, '00:1E:AE', 'Continental Automotive Systems'),
(7829, '00:1E:AF', 'Ophir Optronics Ltd'),
(7830, '00:1E:B0', 'ImesD Electronica S.L.'),
(7831, '00:1E:B1', 'Cryptsoft Pty Ltd'),
(7832, '00:1E:B2', 'LG innotek'),
(7833, '00:1E:B3', 'Primex Wireless'),
(7834, '00:1E:B4', 'UNIFAT TECHNOLOGY LTD.'),
(7835, '00:1E:B5', 'Ever Sparkle Technologies Ltd'),
(7836, '00:1E:B6', 'TAG Heuer SA'),
(7837, '00:1E:B7', 'TBTech, Co., Ltd.'),
(7838, '00:1E:B8', 'Fortis, Inc.'),
(7839, '00:1E:B9', 'Sing Fai Technology Limited'),
(7840, '00:1E:BA', 'High Density Devices AS'),
(7841, '00:1E:BB', 'BLUELIGHT TECHNOLOGY INC.'),
(7842, '00:1E:BC', 'WINTECH AUTOMATION CO.,LTD.'),
(7843, '00:1E:BD', 'CISCO SYSTEMS, INC.'),
(7844, '00:1E:BE', 'CISCO SYSTEMS, INC.'),
(7845, '00:1E:BF', 'Haas Automation Inc.'),
(7846, '00:1E:C0', 'Microchip Technology Inc.'),
(7847, '00:1E:C1', '3COM EUROPE LTD'),
(7848, '00:1E:C2', 'Apple'),
(7849, '00:1E:C3', 'Kozio, Inc.'),
(7850, '00:1E:C4', 'Celio Corp'),
(7851, '00:1E:C5', 'Middle Atlantic Products Inc'),
(7852, '00:1E:C6', 'Obvius Holdings LLC'),
(7853, '00:1E:C7', '2Wire, Inc.'),
(7854, '00:1E:C8', 'Rapid Mobile (Pty) Ltd'),
(7855, '00:1E:C9', 'Dell Inc'),
(7856, '00:1E:CA', 'Nortel'),
(7857, '00:1E:CB', '&quot;RPC &quot;Energoautomatika&quot; Ltd'),
(7858, '00:1E:CC', 'CDVI'),
(7859, '00:1E:CD', 'KYLAND Technology Co. LTD'),
(7860, '00:1E:CE', 'BISA Technologies (Hong Kong) Limited'),
(7861, '00:1E:CF', 'PHILIPS ELECTRONICS UK LTD'),
(7862, '00:1E:D0', 'Ingespace'),
(7863, '00:1E:D1', 'Keyprocessor B.V.'),
(7864, '00:1E:D2', 'Ray Shine Video Technology Inc'),
(7865, '00:1E:D3', 'Dot Technology Int\'l Co., Ltd.'),
(7866, '00:1E:D4', 'Doble Engineering'),
(7867, '00:1E:D5', 'Tekon-Automatics'),
(7868, '00:1E:D6', 'Alentec &amp; Orion AB'),
(7869, '00:1E:D7', 'H-Stream Wireless, Inc.'),
(7870, '00:1E:D8', 'Digital United Inc.'),
(7871, '00:1E:D9', 'Mitsubishi Precision Co.,LTd.'),
(7872, '00:1E:DA', 'Wesemann Elektrotechniek B.V.'),
(7873, '00:1E:DB', 'Giken Trastem Co., Ltd.'),
(7874, '00:1E:DC', 'Sony Ericsson Mobile Communications AB'),
(7875, '00:1E:DD', 'WASKO S.A.'),
(7876, '00:1E:DE', 'BYD COMPANY LIMITED'),
(7877, '00:1E:DF', 'Master Industrialization Center Kista'),
(7878, '00:1E:E0', 'Urmet Domus SpA'),
(7879, '00:1E:E1', 'Samsung Electronics Co.,Ltd'),
(7880, '00:1E:E2', 'Samsung Electronics Co.,Ltd'),
(7881, '00:1E:E3', 'T&amp;W Electronics (ShenZhen) Co.,Ltd'),
(7882, '00:1E:E4', 'ACS Solutions France'),
(7883, '00:1E:E5', 'Cisco-Linksys, LLC'),
(7884, '00:1E:E6', 'Shenzhen Advanced Video Info-Tech Co., Ltd.'),
(7885, '00:1E:E7', 'Epic Systems Inc'),
(7886, '00:1E:E8', 'Mytek'),
(7887, '00:1E:E9', 'Stoneridge Electronics AB'),
(7888, '00:1E:EA', 'Sensor Switch, Inc.'),
(7889, '00:1E:EB', 'Talk-A-Phone Co.'),
(7890, '00:1E:EC', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(7891, '00:1E:ED', 'Adventiq Ltd.'),
(7892, '00:1E:EE', 'ETL Systems Ltd'),
(7893, '00:1E:EF', 'Cantronic International Limited'),
(7894, '00:1E:F0', 'Gigafin Networks'),
(7895, '00:1E:F1', 'Servimat'),
(7896, '00:1E:F2', 'Micro Motion Inc'),
(7897, '00:1E:F3', 'From2'),
(7898, '00:1E:F4', 'L-3 Communications Display Systems'),
(7899, '00:1E:F5', 'Hitek Automated Inc.'),
(7900, '00:1E:F6', 'CISCO SYSTEMS, INC.'),
(7901, '00:1E:F7', 'CISCO SYSTEMS, INC.'),
(7902, '00:1E:F8', 'Emfinity Inc.'),
(7903, '00:1E:F9', 'Pascom Kommunikations systeme GmbH.'),
(7904, '00:1E:FA', 'PROTEI Ltd.'),
(7905, '00:1E:FB', 'Trio Motion Technology Ltd'),
(7906, '00:1E:FC', 'JSC &quot;MASSA-K&quot;'),
(7907, '00:1E:FD', 'Microbit 2.0 AB'),
(7908, '00:1E:FE', 'LEVEL s.r.o.'),
(7909, '00:1E:FF', 'Mueller-Elektronik GmbH &amp; Co. KG'),
(7910, '00:1F:00', 'Nokia Danmark A/S'),
(7911, '00:1F:01', 'Nokia Danmark A/S'),
(7912, '00:1F:02', 'Pixelmetrix Corporation Pte Ltd'),
(7913, '00:1F:03', 'NUM AG'),
(7914, '00:1F:04', 'Granch Ltd.'),
(7915, '00:1F:05', 'iTAS Technology Corp.'),
(7916, '00:1F:06', 'Integrated Dispatch Solutions'),
(7917, '00:1F:07', 'AZTEQ Mobile'),
(7918, '00:1F:08', 'RISCO LTD'),
(7919, '00:1F:09', 'JASTEC CO., LTD.'),
(7920, '00:1F:0A', 'Nortel'),
(7921, '00:1F:0B', 'Federal State Unitary Enterprise Industrial Union&quot;Electropribor&quot;'),
(7922, '00:1F:0C', 'Intelligent Digital Services GmbH'),
(7923, '00:1F:0D', 'L3 Communications - Telemetry West'),
(7924, '00:1F:0E', 'Japan Kyastem Co., Ltd'),
(7925, '00:1F:0F', 'Select Engineered Systems'),
(7926, '00:1F:10', 'TOLEDO DO BRASIL INDUSTRIA DE BALANCAS  LTDA'),
(7927, '00:1F:11', 'OPENMOKO, INC.'),
(7928, '00:1F:12', 'Juniper Networks'),
(7929, '00:1F:13', 'S.&amp; A.S. Ltd.'),
(7930, '00:1F:14', 'NexG'),
(7931, '00:1F:15', 'Bioscrypt Inc'),
(7932, '00:1F:16', 'Wistron Corporation'),
(7933, '00:1F:17', 'IDX Company, Ltd.'),
(7934, '00:1F:18', 'Hakusan.Mfg.Co,.Ltd'),
(7935, '00:1F:19', 'BEN-RI ELECTRONICA S.A.'),
(7936, '00:1F:1A', 'Prominvest'),
(7937, '00:1F:1B', 'RoyalTek Company Ltd.'),
(7938, '00:1F:1C', 'KOBISHI ELECTRIC Co.,Ltd.'),
(7939, '00:1F:1D', 'Atlas Material Testing Technology LLC'),
(7940, '00:1F:1E', 'Astec Technology Co., Ltd'),
(7941, '00:1F:1F', 'Edimax Technology Co. Ltd.'),
(7942, '00:1F:20', 'Logitech Europe SA'),
(7943, '00:1F:21', 'Inner Mongolia Yin An Science &amp; Technology Development Co.,L'),
(7944, '00:1F:22', 'Source Photonics, Inc.'),
(7945, '00:1F:23', 'Interacoustics'),
(7946, '00:1F:24', 'DIGITVIEW TECHNOLOGY CO., LTD.'),
(7947, '00:1F:25', 'MBS GmbH'),
(7948, '00:1F:26', 'CISCO SYSTEMS, INC.'),
(7949, '00:1F:27', 'CISCO SYSTEMS, INC.'),
(7950, '00:1F:28', 'HPN Supply Chain'),
(7951, '00:1F:29', 'Hewlett-Packard Company'),
(7952, '00:1F:2A', 'ACCM'),
(7953, '00:1F:2B', 'Orange Logic'),
(7954, '00:1F:2C', 'Starbridge Networks'),
(7955, '00:1F:2D', 'Electro-Optical Imaging, Inc.'),
(7956, '00:1F:2E', 'Triangle Research Int\'l Pte Ltd'),
(7957, '00:1F:2F', 'Berker GmbH &amp; Co. KG'),
(7958, '00:1F:30', 'Travelping'),
(7959, '00:1F:31', 'Radiocomp'),
(7960, '00:1F:32', 'Nintendo Co., Ltd.'),
(7961, '00:1F:33', 'Netgear Inc.'),
(7962, '00:1F:34', 'Lung Hwa Electronics Co., Ltd.'),
(7963, '00:1F:35', 'AIR802 LLC'),
(7964, '00:1F:36', 'Bellwin Information Co. Ltd.,'),
(7965, '00:1F:37', 'Genesis I&amp;C'),
(7966, '00:1F:38', 'POSITRON'),
(7967, '00:1F:39', 'Construcciones y Auxiliar de Ferrocarriles, S.A.'),
(7968, '00:1F:3A', 'Hon Hai Precision Ind.Co., Ltd.'),
(7969, '00:1F:3B', 'Intel Corporate'),
(7970, '00:1F:3C', 'Intel Corporate'),
(7971, '00:1F:3D', 'Qbit GmbH'),
(7972, '00:1F:3E', 'RP-Technik e.K.'),
(7973, '00:1F:3F', 'AVM GmbH'),
(7974, '00:1F:40', 'Speakercraft Inc.'),
(7975, '00:1F:41', 'Ruckus Wireless'),
(7976, '00:1F:42', 'Etherstack plc'),
(7977, '00:1F:43', 'ENTES ELEKTRONIK'),
(7978, '00:1F:44', 'GE Transportation Systems'),
(7979, '00:1F:45', 'Enterasys'),
(7980, '00:1F:46', 'Nortel'),
(7981, '00:1F:47', 'MCS Logic Inc.'),
(7982, '00:1F:48', 'Mojix Inc.'),
(7983, '00:1F:49', 'Eurosat Distribution Ltd'),
(7984, '00:1F:4A', 'Albentia Systems S.A.'),
(7985, '00:1F:4B', 'Lineage Power'),
(7986, '00:1F:4C', 'Roseman Engineering Ltd'),
(7987, '00:1F:4D', 'Segnetics LLC'),
(7988, '00:1F:4E', 'ConMed Linvatec'),
(7989, '00:1F:4F', 'Thinkware Co. Ltd.'),
(7990, '00:1F:50', 'Swissdis AG'),
(7991, '00:1F:51', 'HD Communications Corp'),
(7992, '00:1F:52', 'UVT Unternehmensberatung fur Verkehr und Technik GmbH'),
(7993, '00:1F:53', 'GEMAC Gesellschaft f&uuml;r Mikroelektronikanwendung Chemnitz mbH'),
(7994, '00:1F:54', 'Lorex Technology Inc.'),
(7995, '00:1F:55', 'Honeywell Security (China) Co., Ltd.'),
(7996, '00:1F:56', 'DIGITAL FORECAST'),
(7997, '00:1F:57', 'Phonik Innovation Co.,LTD'),
(7998, '00:1F:58', 'EMH Energiemesstechnik GmbH'),
(7999, '00:1F:59', 'Kronback Tracers'),
(8000, '00:1F:5A', 'Beckwith Electric Co.'),
(8001, '00:1F:5B', 'Apple'),
(8002, '00:1F:5C', 'Nokia Danmark A/S'),
(8003, '00:1F:5D', 'Nokia Danmark A/S'),
(8004, '00:1F:5E', 'Dyna Technology Co.,Ltd.'),
(8005, '00:1F:5F', 'Blatand GmbH'),
(8006, '00:1F:60', 'COMPASS SYSTEMS CORP.'),
(8007, '00:1F:61', 'Talent Communication Networks Inc.'),
(8008, '00:1F:62', 'JSC &quot;Stilsoft&quot;'),
(8009, '00:1F:63', 'JSC Goodwin-Europa'),
(8010, '00:1F:64', 'Beijing Autelan Technology Inc.'),
(8011, '00:1F:65', 'KOREA ELECTRIC TERMINAL CO., LTD.'),
(8012, '00:1F:66', 'PLANAR LLC'),
(8013, '00:1F:67', 'Hitachi,Ltd.'),
(8014, '00:1F:68', 'Martinsson Elektronik AB'),
(8015, '00:1F:69', 'Pingood Technology Co., Ltd.'),
(8016, '00:1F:6A', 'PacketFlux Technologies, Inc.'),
(8017, '00:1F:6B', 'LG Electronics'),
(8018, '00:1F:6C', 'CISCO SYSTEMS, INC.'),
(8019, '00:1F:6D', 'CISCO SYSTEMS, INC.'),
(8020, '00:1F:6E', 'Vtech Engineering Corporation'),
(8021, '00:1F:6F', 'Fujian Sunnada Communication Co.,Ltd.'),
(8022, '00:1F:70', 'Botik Technologies LTD'),
(8023, '00:1F:71', 'xG Technology, Inc.'),
(8024, '00:1F:72', 'QingDao Hiphone Technology Co,.Ltd'),
(8025, '00:1F:73', 'Teraview Technology Co., Ltd.'),
(8026, '00:1F:74', 'Eigen Development'),
(8027, '00:1F:75', 'GiBahn Media'),
(8028, '00:1F:76', 'AirLogic Systems Inc.'),
(8029, '00:1F:77', 'HEOL DESIGN'),
(8030, '00:1F:78', 'Blue Fox Porini Textile'),
(8031, '00:1F:79', 'Lodam Electronics A/S'),
(8032, '00:1F:7A', 'WiWide Inc.'),
(8033, '00:1F:7B', 'TechNexion Ltd.'),
(8034, '00:1F:7C', 'Witelcom AS'),
(8035, '00:1F:7D', 'embedded wireless GmbH'),
(8036, '00:1F:7E', 'ARRIS Group, Inc.'),
(8037, '00:1F:7F', 'Phabrix Limited'),
(8038, '00:1F:80', 'Lucas Holding bv'),
(8039, '00:1F:81', 'Accel Semiconductor Corp'),
(8040, '00:1F:82', 'Cal-Comp Electronics &amp; Communications Co., Ltd'),
(8041, '00:1F:83', 'Teleplan Technology Services Sdn Bhd'),
(8042, '00:1F:84', 'Gigle Semiconductor'),
(8043, '00:1F:85', 'Apriva ISS, LLC'),
(8044, '00:1F:86', 'digEcor'),
(8045, '00:1F:87', 'Skydigital Inc.'),
(8046, '00:1F:88', 'FMS Force Measuring Systems AG'),
(8047, '00:1F:89', 'Signalion GmbH'),
(8048, '00:1F:8A', 'Ellion Digital Inc.'),
(8049, '00:1F:8B', 'Cache IQ'),
(8050, '00:1F:8C', 'CCS Inc.'),
(8051, '00:1F:8D', 'Ingenieurbuero Stark GmbH und Ko. KG'),
(8052, '00:1F:8E', 'Metris USA Inc.'),
(8053, '00:1F:8F', 'Shanghai Bellmann Digital Source Co.,Ltd.'),
(8054, '00:1F:90', 'Actiontec Electronics, Inc'),
(8055, '00:1F:91', 'DBS Lodging Technologies, LLC'),
(8056, '00:1F:92', 'VideoIQ, Inc.'),
(8057, '00:1F:93', 'Xiotech Corporation'),
(8058, '00:1F:94', 'Lascar Electronics Ltd'),
(8059, '00:1F:95', 'SAGEM COMMUNICATION'),
(8060, '00:1F:96', 'APROTECH CO.LTD'),
(8061, '00:1F:97', 'BERTANA SRL'),
(8062, '00:1F:98', 'DAIICHI-DENTSU LTD.'),
(8063, '00:1F:99', 'SERONICS co.ltd'),
(8064, '00:1F:9A', 'Nortel Networks'),
(8065, '00:1F:9B', 'POSBRO'),
(8066, '00:1F:9C', 'LEDCO'),
(8067, '00:1F:9D', 'CISCO SYSTEMS, INC.'),
(8068, '00:1F:9E', 'CISCO SYSTEMS, INC.'),
(8069, '00:1F:9F', 'Thomson Telecom Belgium'),
(8070, '00:1F:A0', 'A10 Networks'),
(8071, '00:1F:A1', 'Gtran Inc'),
(8072, '00:1F:A2', 'Datron World Communications, Inc.'),
(8073, '00:1F:A3', 'T&amp;W Electronics(Shenzhen)Co.,Ltd.'),
(8074, '00:1F:A4', 'ShenZhen Gongjin Electronics Co.,Ltd'),
(8075, '00:1F:A5', 'Blue-White Industries'),
(8076, '00:1F:A6', 'Stilo srl'),
(8077, '00:1F:A7', 'Sony Computer Entertainment Inc.'),
(8078, '00:1F:A8', 'Smart Energy Instruments Inc.'),
(8079, '00:1F:A9', 'Atlanta DTH, Inc.'),
(8080, '00:1F:AA', 'Taseon, Inc.'),
(8081, '00:1F:AB', 'I.S HIGH TECH.INC'),
(8082, '00:1F:AC', 'Goodmill Systems Ltd'),
(8083, '00:1F:AD', 'Brown Innovations, Inc'),
(8084, '00:1F:AE', 'Blick South Africa (Pty) Ltd'),
(8085, '00:1F:AF', 'NextIO, Inc.'),
(8086, '00:1F:B0', 'TimeIPS, Inc.'),
(8087, '00:1F:B1', 'Cybertech Inc.'),
(8088, '00:1F:B2', 'Sontheim Industrie Elektronik GmbH'),
(8089, '00:1F:B3', '2Wire'),
(8090, '00:1F:B4', 'SmartShare Systems'),
(8091, '00:1F:B5', 'I/O Interconnect Inc.'),
(8092, '00:1F:B6', 'Chi Lin Technology Co., Ltd.'),
(8093, '00:1F:B7', 'WiMate Technologies Corp.'),
(8094, '00:1F:B8', 'Universal Remote Control, Inc.'),
(8095, '00:1F:B9', 'Paltronics'),
(8096, '00:1F:BA', 'BoYoung Tech. &amp; Marketing, Inc.'),
(8097, '00:1F:BB', 'Xenatech Co.,LTD'),
(8098, '00:1F:BC', 'EVGA Corporation'),
(8099, '00:1F:BD', 'Kyocera Wireless Corp.'),
(8100, '00:1F:BE', 'Shenzhen Mopnet Industrial Co.,Ltd'),
(8101, '00:1F:BF', 'Fulhua Microelectronics Corp. Taiwan Branch'),
(8102, '00:1F:C0', 'Control Express Finland Oy'),
(8103, '00:1F:C1', 'Hanlong Technology Co.,LTD'),
(8104, '00:1F:C2', 'Jow Tong Technology Co Ltd'),
(8105, '00:1F:C3', 'SmartSynch, Inc'),
(8106, '00:1F:C4', 'ARRIS Group, Inc.'),
(8107, '00:1F:C5', 'Nintendo Co., Ltd.'),
(8108, '00:1F:C6', 'ASUSTek COMPUTER INC.'),
(8109, '00:1F:C7', 'Casio Hitachi Mobile Comunications Co., Ltd.'),
(8110, '00:1F:C8', 'Up-Today Industrial Co., Ltd.'),
(8111, '00:1F:C9', 'CISCO SYSTEMS, INC.'),
(8112, '00:1F:CA', 'CISCO SYSTEMS, INC.'),
(8113, '00:1F:CB', 'NIW Solutions'),
(8114, '00:1F:CC', 'Samsung Electronics Co.,Ltd'),
(8115, '00:1F:CD', 'Samsung Electronics'),
(8116, '00:1F:CE', 'QTECH LLC'),
(8117, '00:1F:CF', 'MSI Technology GmbH'),
(8118, '00:1F:D0', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(8119, '00:1F:D1', 'OPTEX CO.,LTD.'),
(8120, '00:1F:D2', 'COMMTECH TECHNOLOGY MACAO COMMERCIAL OFFSHORE LTD.'),
(8121, '00:1F:D3', 'RIVA Networks Inc.'),
(8122, '00:1F:D4', '4IPNET, INC.'),
(8123, '00:1F:D5', 'MICRORISC s.r.o.'),
(8124, '00:1F:D6', 'Shenzhen Allywll'),
(8125, '00:1F:D7', 'TELERAD SA'),
(8126, '00:1F:D8', 'A-TRUST COMPUTER CORPORATION'),
(8127, '00:1F:D9', 'RSD Communications Ltd'),
(8128, '00:1F:DA', 'Nortel Networks'),
(8129, '00:1F:DB', 'Network Supply Corp.,'),
(8130, '00:1F:DC', 'Mobile Safe Track Ltd'),
(8131, '00:1F:DD', 'GDI LLC'),
(8132, '00:1F:DE', 'Nokia Danmark A/S'),
(8133, '00:1F:DF', 'Nokia Danmark A/S'),
(8134, '00:1F:E0', 'EdgeVelocity Corp'),
(8135, '00:1F:E1', 'Hon Hai Precision Ind. Co., Ltd.'),
(8136, '00:1F:E2', 'Hon Hai Precision Ind. Co., Ltd.'),
(8137, '00:1F:E3', 'LG Electronics'),
(8138, '00:1F:E4', 'Sony Ericsson Mobile Communications'),
(8139, '00:1F:E5', 'In-Circuit GmbH'),
(8140, '00:1F:E6', 'Alphion Corporation'),
(8141, '00:1F:E7', 'Simet'),
(8142, '00:1F:E8', 'KURUSUGAWA Electronics Industry Inc,.'),
(8143, '00:1F:E9', 'Printrex, Inc.'),
(8144, '00:1F:EA', 'Applied Media Technologies Corporation'),
(8145, '00:1F:EB', 'Trio Datacom Pty Ltd'),
(8146, '00:1F:EC', 'Synapse &Eacute;lectronique'),
(8147, '00:1F:ED', 'Tecan Systems Inc.'),
(8148, '00:1F:EE', 'ubisys technologies GmbH'),
(8149, '00:1F:EF', 'SHINSEI INDUSTRIES CO.,LTD'),
(8150, '00:1F:F0', 'Audio Partnership'),
(8151, '00:1F:F1', 'Paradox Hellas S.A.'),
(8152, '00:1F:F2', 'VIA Technologies, Inc.'),
(8153, '00:1F:F3', 'Apple'),
(8154, '00:1F:F4', 'Power Monitors, Inc.'),
(8155, '00:1F:F5', 'Kongsberg Defence &amp; Aerospace'),
(8156, '00:1F:F6', 'PS Audio International'),
(8157, '00:1F:F7', 'Nakajima All Precision Co., Ltd.'),
(8158, '00:1F:F8', 'Siemens AG, Sector Industry, Drive Technologies, Motion Control Systems'),
(8159, '00:1F:F9', 'Advanced Knowledge Associates'),
(8160, '00:1F:FA', 'Coretree, Co, Ltd'),
(8161, '00:1F:FB', 'Green Packet Bhd'),
(8162, '00:1F:FC', 'Riccius+Sohn GmbH'),
(8163, '00:1F:FD', 'Indigo Mobile Technologies Corp.'),
(8164, '00:1F:FE', 'HPN Supply Chain'),
(8165, '00:1F:FF', 'Respironics, Inc.'),
(8166, '00:20:00', 'LEXMARK INTERNATIONAL, INC.'),
(8167, '00:20:01', 'DSP SOLUTIONS, INC.'),
(8168, '00:20:02', 'SERITECH ENTERPRISE CO., LTD.'),
(8169, '00:20:03', 'PIXEL POWER LTD.'),
(8170, '00:20:04', 'YAMATAKE-HONEYWELL CO., LTD.'),
(8171, '00:20:05', 'SIMPLE TECHNOLOGY'),
(8172, '00:20:06', 'GARRETT COMMUNICATIONS, INC.'),
(8173, '00:20:07', 'SFA, INC.'),
(8174, '00:20:08', 'CABLE &amp; COMPUTER TECHNOLOGY'),
(8175, '00:20:09', 'PACKARD BELL ELEC., INC.'),
(8176, '00:20:0A', 'SOURCE-COMM CORP.'),
(8177, '00:20:0B', 'OCTAGON SYSTEMS CORP.'),
(8178, '00:20:0C', 'ADASTRA SYSTEMS CORP.'),
(8179, '00:20:0D', 'CARL ZEISS'),
(8180, '00:20:0E', 'SATELLITE TECHNOLOGY MGMT, INC'),
(8181, '00:20:0F', 'TANBAC CO., LTD.'),
(8182, '00:20:10', 'JEOL SYSTEM TECHNOLOGY CO. LTD'),
(8183, '00:20:11', 'CANOPUS CO., LTD.'),
(8184, '00:20:12', 'CAMTRONICS MEDICAL SYSTEMS'),
(8185, '00:20:13', 'DIVERSIFIED TECHNOLOGY, INC.'),
(8186, '00:20:14', 'GLOBAL VIEW CO., LTD.'),
(8187, '00:20:15', 'ACTIS COMPUTER SA'),
(8188, '00:20:16', 'SHOWA ELECTRIC WIRE &amp; CABLE CO'),
(8189, '00:20:17', 'ORBOTECH'),
(8190, '00:20:18', 'CIS TECHNOLOGY INC.'),
(8191, '00:20:19', 'OHLER GmbH'),
(8192, '00:20:1A', 'MRV Communications, Inc.'),
(8193, '00:20:1B', 'NORTHERN TELECOM/NETWORK'),
(8194, '00:20:1C', 'EXCEL, INC.'),
(8195, '00:20:1D', 'KATANA PRODUCTS'),
(8196, '00:20:1E', 'NETQUEST CORPORATION'),
(8197, '00:20:1F', 'BEST POWER TECHNOLOGY, INC.'),
(8198, '00:20:20', 'MEGATRON COMPUTER INDUSTRIES PTY, LTD.'),
(8199, '00:20:21', 'ALGORITHMS SOFTWARE PVT. LTD.'),
(8200, '00:20:22', 'NMS Communications'),
(8201, '00:20:23', 'T.C. TECHNOLOGIES PTY. LTD'),
(8202, '00:20:24', 'PACIFIC COMMUNICATION SCIENCES'),
(8203, '00:20:25', 'CONTROL TECHNOLOGY, INC.'),
(8204, '00:20:26', 'AMKLY SYSTEMS, INC.'),
(8205, '00:20:27', 'MING FORTUNE INDUSTRY CO., LTD'),
(8206, '00:20:28', 'WEST EGG SYSTEMS, INC.'),
(8207, '00:20:29', 'TELEPROCESSING PRODUCTS, INC.'),
(8208, '00:20:2A', 'N.V. DZINE'),
(8209, '00:20:2B', 'ADVANCED TELECOMMUNICATIONS MODULES, LTD.'),
(8210, '00:20:2C', 'WELLTRONIX CO., LTD.'),
(8211, '00:20:2D', 'TAIYO CORPORATION'),
(8212, '00:20:2E', 'DAYSTAR DIGITAL'),
(8213, '00:20:2F', 'ZETA COMMUNICATIONS, LTD.'),
(8214, '00:20:30', 'ANALOG &amp; DIGITAL SYSTEMS'),
(8215, '00:20:31', 'Tattile SRL'),
(8216, '00:20:32', 'ALCATEL TAISEL'),
(8217, '00:20:33', 'SYNAPSE TECHNOLOGIES, INC.'),
(8218, '00:20:34', 'ROTEC INDUSTRIEAUTOMATION GMBH'),
(8219, '00:20:35', 'IBM Corp'),
(8220, '00:20:36', 'BMC SOFTWARE'),
(8221, '00:20:37', 'SEAGATE TECHNOLOGY'),
(8222, '00:20:38', 'VME MICROSYSTEMS INTERNATIONAL CORPORATION'),
(8223, '00:20:39', 'SCINETS'),
(8224, '00:20:3A', 'DIGITAL BI0METRICS INC.'),
(8225, '00:20:3B', 'WISDM LTD.'),
(8226, '00:20:3C', 'EUROTIME AB'),
(8227, '00:20:3D', 'Honeywell ECC'),
(8228, '00:20:3E', 'LogiCan Technologies, Inc.'),
(8229, '00:20:3F', 'JUKI CORPORATION'),
(8230, '00:20:40', 'ARRIS Group, Inc.'),
(8231, '00:20:41', 'DATA NET'),
(8232, '00:20:42', 'DATAMETRICS CORP.'),
(8233, '00:20:43', 'NEURON COMPANY LIMITED'),
(8234, '00:20:44', 'GENITECH PTY LTD'),
(8235, '00:20:45', 'ION Networks, Inc.'),
(8236, '00:20:46', 'CIPRICO, INC.'),
(8237, '00:20:47', 'STEINBRECHER CORP.'),
(8238, '00:20:48', 'Marconi Communications'),
(8239, '00:20:49', 'COMTRON, INC.'),
(8240, '00:20:4A', 'PRONET GMBH'),
(8241, '00:20:4B', 'AUTOCOMPUTER CO., LTD.'),
(8242, '00:20:4C', 'MITRON COMPUTER PTE LTD.'),
(8243, '00:20:4D', 'INOVIS GMBH'),
(8244, '00:20:4E', 'NETWORK SECURITY SYSTEMS, INC.'),
(8245, '00:20:4F', 'DEUTSCHE AEROSPACE AG'),
(8246, '00:20:50', 'KOREA COMPUTER INC.'),
(8247, '00:20:51', 'Verilink Corporation'),
(8248, '00:20:52', 'RAGULA SYSTEMS'),
(8249, '00:20:53', 'HUNTSVILLE MICROSYSTEMS, INC.'),
(8250, '00:20:54', 'Sycamore Networks'),
(8251, '00:20:55', 'ALTECH CO., LTD.'),
(8252, '00:20:56', 'NEOPRODUCTS'),
(8253, '00:20:57', 'TITZE DATENTECHNIK GmbH'),
(8254, '00:20:58', 'ALLIED SIGNAL INC.'),
(8255, '00:20:59', 'MIRO COMPUTER PRODUCTS AG'),
(8256, '00:20:5A', 'COMPUTER IDENTICS'),
(8257, '00:20:5B', 'Kentrox, LLC'),
(8258, '00:20:5C', 'InterNet Systems of Florida, Inc.'),
(8259, '00:20:5D', 'NANOMATIC OY'),
(8260, '00:20:5E', 'CASTLE ROCK, INC.'),
(8261, '00:20:5F', 'GAMMADATA COMPUTER GMBH'),
(8262, '00:20:60', 'ALCATEL ITALIA S.p.A.'),
(8263, '00:20:61', 'GarrettCom, Inc.'),
(8264, '00:20:62', 'SCORPION LOGIC, LTD.'),
(8265, '00:20:63', 'WIPRO INFOTECH LTD.'),
(8266, '00:20:64', 'PROTEC MICROSYSTEMS, INC.'),
(8267, '00:20:65', 'SUPERNET NETWORKING INC.'),
(8268, '00:20:66', 'GENERAL MAGIC, INC.'),
(8269, '00:20:67', 'PRIVATE'),
(8270, '00:20:68', 'ISDYNE'),
(8271, '00:20:69', 'ISDN SYSTEMS CORPORATION'),
(8272, '00:20:6A', 'OSAKA COMPUTER CORP.'),
(8273, '00:20:6B', 'KONICA MINOLTA HOLDINGS, INC.'),
(8274, '00:20:6C', 'EVERGREEN TECHNOLOGY CORP.'),
(8275, '00:20:6D', 'DATA RACE, INC.'),
(8276, '00:20:6E', 'XACT, INC.'),
(8277, '00:20:6F', 'FLOWPOINT CORPORATION'),
(8278, '00:20:70', 'HYNET, LTD.'),
(8279, '00:20:71', 'IBR GMBH'),
(8280, '00:20:72', 'WORKLINK INNOVATIONS'),
(8281, '00:20:73', 'FUSION SYSTEMS CORPORATION'),
(8282, '00:20:74', 'SUNGWOON SYSTEMS'),
(8283, '00:20:75', 'MOTOROLA COMMUNICATION ISRAEL'),
(8284, '00:20:76', 'REUDO CORPORATION'),
(8285, '00:20:77', 'KARDIOS SYSTEMS CORP.'),
(8286, '00:20:78', 'RUNTOP, INC.'),
(8287, '00:20:79', 'MIKRON GMBH'),
(8288, '00:20:7A', 'WiSE Communications, Inc.'),
(8289, '00:20:7B', 'Intel Corporation'),
(8290, '00:20:7C', 'AUTEC GmbH'),
(8291, '00:20:7D', 'ADVANCED COMPUTER APPLICATIONS'),
(8292, '00:20:7E', 'FINECOM Co., Ltd.'),
(8293, '00:20:7F', 'KYOEI SANGYO CO., LTD.'),
(8294, '00:20:80', 'SYNERGY (UK) LTD.'),
(8295, '00:20:81', 'TITAN ELECTRONICS'),
(8296, '00:20:82', 'ONEAC CORPORATION'),
(8297, '00:20:83', 'PRESTICOM INCORPORATED'),
(8298, '00:20:84', 'OCE PRINTING SYSTEMS, GMBH'),
(8299, '00:20:85', 'Eaton Corporation'),
(8300, '00:20:86', 'MICROTECH ELECTRONICS LIMITED'),
(8301, '00:20:87', 'MEMOTEC, INC.'),
(8302, '00:20:88', 'GLOBAL VILLAGE COMMUNICATION'),
(8303, '00:20:89', 'T3PLUS NETWORKING, INC.'),
(8304, '00:20:8A', 'SONIX COMMUNICATIONS, LTD.'),
(8305, '00:20:8B', 'LAPIS TECHNOLOGIES, INC.'),
(8306, '00:20:8C', 'GALAXY NETWORKS, INC.'),
(8307, '00:20:8D', 'CMD TECHNOLOGY'),
(8308, '00:20:8E', 'CHEVIN SOFTWARE ENG. LTD.'),
(8309, '00:20:8F', 'ECI TELECOM LTD.'),
(8310, '00:20:90', 'ADVANCED COMPRESSION TECHNOLOGY, INC.'),
(8311, '00:20:91', 'J125, NATIONAL SECURITY AGENCY'),
(8312, '00:20:92', 'CHESS ENGINEERING B.V.'),
(8313, '00:20:93', 'LANDINGS TECHNOLOGY CORP.'),
(8314, '00:20:94', 'CUBIX CORPORATION'),
(8315, '00:20:95', 'RIVA ELECTRONICS'),
(8316, '00:20:96', 'Invensys'),
(8317, '00:20:97', 'APPLIED SIGNAL TECHNOLOGY'),
(8318, '00:20:98', 'HECTRONIC AB'),
(8319, '00:20:99', 'BON ELECTRIC CO., LTD.'),
(8320, '00:20:9A', 'THE 3DO COMPANY'),
(8321, '00:20:9B', 'ERSAT ELECTRONIC GMBH'),
(8322, '00:20:9C', 'PRIMARY ACCESS CORP.'),
(8323, '00:20:9D', 'LIPPERT AUTOMATIONSTECHNIK'),
(8324, '00:20:9E', 'BROWN\'S OPERATING SYSTEM SERVICES, LTD.'),
(8325, '00:20:9F', 'MERCURY COMPUTER SYSTEMS, INC.'),
(8326, '00:20:A0', 'OA LABORATORY CO., LTD.'),
(8327, '00:20:A1', 'DOVATRON'),
(8328, '00:20:A2', 'GALCOM NETWORKING LTD.'),
(8329, '00:20:A3', 'Harmonic, Inc'),
(8330, '00:20:A4', 'MULTIPOINT NETWORKS'),
(8331, '00:20:A5', 'API ENGINEERING'),
(8332, '00:20:A6', 'Proxim Wireless'),
(8333, '00:20:A7', 'PAIRGAIN TECHNOLOGIES, INC.'),
(8334, '00:20:A8', 'SAST TECHNOLOGY CORP.'),
(8335, '00:20:A9', 'WHITE HORSE INDUSTRIAL'),
(8336, '00:20:AA', 'Ericsson Television Limited'),
(8337, '00:20:AB', 'MICRO INDUSTRIES CORP.'),
(8338, '00:20:AC', 'INTERFLEX DATENSYSTEME GMBH'),
(8339, '00:20:AD', 'LINQ SYSTEMS'),
(8340, '00:20:AE', 'ORNET DATA COMMUNICATION TECH.'),
(8341, '00:20:AF', '3COM CORPORATION'),
(8342, '00:20:B0', 'GATEWAY DEVICES, INC.'),
(8343, '00:20:B1', 'COMTECH RESEARCH INC.'),
(8344, '00:20:B2', 'GKD Gesellschaft Fur Kommunikation Und Datentechnik'),
(8345, '00:20:B3', 'Tattile SRL'),
(8346, '00:20:B4', 'TERMA ELEKTRONIK AS'),
(8347, '00:20:B5', 'YASKAWA ELECTRIC CORPORATION'),
(8348, '00:20:B6', 'AGILE NETWORKS, INC.'),
(8349, '00:20:B7', 'NAMAQUA COMPUTERWARE'),
(8350, '00:20:B8', 'PRIME OPTION, INC.'),
(8351, '00:20:B9', 'METRICOM, INC.'),
(8352, '00:20:BA', 'CENTER FOR HIGH PERFORMANCE'),
(8353, '00:20:BB', 'ZAX CORPORATION'),
(8354, '00:20:BC', 'Long Reach Networks Pty Ltd'),
(8355, '00:20:BD', 'NIOBRARA R &amp; D CORPORATION'),
(8356, '00:20:BE', 'LAN ACCESS CORP.'),
(8357, '00:20:BF', 'AEHR TEST SYSTEMS'),
(8358, '00:20:C0', 'PULSE ELECTRONICS, INC.'),
(8359, '00:20:C1', 'SAXA, Inc.'),
(8360, '00:20:C2', 'TEXAS MEMORY SYSTEMS, INC.'),
(8361, '00:20:C3', 'COUNTER SOLUTIONS LTD.'),
(8362, '00:20:C4', 'INET,INC.'),
(8363, '00:20:C5', 'EAGLE TECHNOLOGY'),
(8364, '00:20:C6', 'NECTEC'),
(8365, '00:20:C7', 'AKAI Professional M.I. Corp.'),
(8366, '00:20:C8', 'LARSCOM INCORPORATED'),
(8367, '00:20:C9', 'VICTRON BV'),
(8368, '00:20:CA', 'DIGITAL OCEAN'),
(8369, '00:20:CB', 'PRETEC ELECTRONICS CORP.'),
(8370, '00:20:CC', 'DIGITAL SERVICES, LTD.'),
(8371, '00:20:CD', 'HYBRID NETWORKS, INC.'),
(8372, '00:20:CE', 'LOGICAL DESIGN GROUP, INC.'),
(8373, '00:20:CF', 'TEST &amp; MEASUREMENT SYSTEMS INC'),
(8374, '00:20:D0', 'VERSALYNX CORPORATION'),
(8375, '00:20:D1', 'MICROCOMPUTER SYSTEMS (M) SDN.'),
(8376, '00:20:D2', 'RAD DATA COMMUNICATIONS, LTD.'),
(8377, '00:20:D3', 'OST (OUEST STANDARD TELEMATIQU'),
(8378, '00:20:D4', 'CABLETRON - ZEITTNET INC.'),
(8379, '00:20:D5', 'VIPA GMBH'),
(8380, '00:20:D6', 'BREEZECOM'),
(8381, '00:20:D7', 'JAPAN MINICOMPUTER SYSTEMS CO., Ltd.'),
(8382, '00:20:D8', 'Nortel Networks'),
(8383, '00:20:D9', 'PANASONIC TECHNOLOGIES, INC./MIECO-US'),
(8384, '00:20:DA', 'Alcatel North America ESD'),
(8385, '00:20:DB', 'XNET TECHNOLOGY, INC.'),
(8386, '00:20:DC', 'DENSITRON TAIWAN LTD.'),
(8387, '00:20:DD', 'Cybertec Pty Ltd'),
(8388, '00:20:DE', 'JAPAN DIGITAL LABORAT\'Y CO.LTD'),
(8389, '00:20:DF', 'KYOSAN ELECTRIC MFG. CO., LTD.'),
(8390, '00:20:E0', 'Actiontec Electronics, Inc.'),
(8391, '00:20:E1', 'ALAMAR ELECTRONICS'),
(8392, '00:20:E2', 'INFORMATION RESOURCE ENGINEERING'),
(8393, '00:20:E3', 'MCD KENCOM CORPORATION'),
(8394, '00:20:E4', 'HSING TECH ENTERPRISE CO., LTD'),
(8395, '00:20:E5', 'APEX DATA, INC.'),
(8396, '00:20:E6', 'LIDKOPING MACHINE TOOLS AB'),
(8397, '00:20:E7', 'B&amp;W NUCLEAR SERVICE COMPANY'),
(8398, '00:20:E8', 'DATATREK CORPORATION'),
(8399, '00:20:E9', 'DANTEL'),
(8400, '00:20:EA', 'EFFICIENT NETWORKS, INC.'),
(8401, '00:20:EB', 'CINCINNATI MICROWAVE, INC.'),
(8402, '00:20:EC', 'TECHWARE SYSTEMS CORP.'),
(8403, '00:20:ED', 'GIGA-BYTE TECHNOLOGY CO., LTD.'),
(8404, '00:20:EE', 'GTECH CORPORATION'),
(8405, '00:20:EF', 'USC CORPORATION'),
(8406, '00:20:F0', 'UNIVERSAL MICROELECTRONICS CO.'),
(8407, '00:20:F1', 'ALTOS INDIA LIMITED'),
(8408, '00:20:F2', 'Oracle Corporation'),
(8409, '00:20:F3', 'RAYNET CORPORATION'),
(8410, '00:20:F4', 'SPECTRIX CORPORATION'),
(8411, '00:20:F5', 'PANDATEL AG'),
(8412, '00:20:F6', 'NET TEK  AND KARLNET, INC.'),
(8413, '00:20:F7', 'CYBERDATA CORPORATION'),
(8414, '00:20:F8', 'CARRERA COMPUTERS, INC.'),
(8415, '00:20:F9', 'PARALINK NETWORKS, INC.'),
(8416, '00:20:FA', 'GDE SYSTEMS, INC.'),
(8417, '00:20:FB', 'OCTEL COMMUNICATIONS CORP.'),
(8418, '00:20:FC', 'MATROX'),
(8419, '00:20:FD', 'ITV TECHNOLOGIES, INC.'),
(8420, '00:20:FE', 'TOPWARE INC. / GRAND COMPUTER'),
(8421, '00:20:FF', 'SYMMETRICAL TECHNOLOGIES'),
(8422, '00:21:00', 'GemTek Technology Co., Ltd.'),
(8423, '00:21:01', 'Aplicaciones Electronicas Quasar (AEQ)'),
(8424, '00:21:02', 'UpdateLogic Inc.'),
(8425, '00:21:03', 'GHI Electronics, LLC'),
(8426, '00:21:04', 'Gigaset Communications GmbH'),
(8427, '00:21:05', 'Alcatel-Lucent'),
(8428, '00:21:06', 'RIM Testing Services'),
(8429, '00:21:07', 'Seowonintech Co Ltd.'),
(8430, '00:21:08', 'Nokia Danmark A/S'),
(8431, '00:21:09', 'Nokia Danmark A/S'),
(8432, '00:21:0A', 'byd:sign Corporation'),
(8433, '00:21:0B', 'GEMINI TRAZE RFID PVT. LTD.'),
(8434, '00:21:0C', 'Cymtec Systems, Inc.'),
(8435, '00:21:0D', 'SAMSIN INNOTEC'),
(8436, '00:21:0E', 'Orpak Systems L.T.D.'),
(8437, '00:21:0F', 'Cernium Corp'),
(8438, '00:21:10', 'Clearbox Systems'),
(8439, '00:21:11', 'Uniphone Inc.'),
(8440, '00:21:12', 'WISCOM SYSTEM CO.,LTD'),
(8441, '00:21:13', 'Padtec S/A'),
(8442, '00:21:14', 'Hylab Technology Inc.'),
(8443, '00:21:15', 'PHYWE Systeme GmbH &amp; Co. KG'),
(8444, '00:21:16', 'Transcon Electronic Systems, spol. s r. o.'),
(8445, '00:21:17', 'Tellord'),
(8446, '00:21:18', 'Athena Tech, Inc.'),
(8447, '00:21:19', 'Samsung Electro-Mechanics'),
(8448, '00:21:1A', 'LInTech Corporation'),
(8449, '00:21:1B', 'CISCO SYSTEMS, INC.'),
(8450, '00:21:1C', 'CISCO SYSTEMS, INC.'),
(8451, '00:21:1D', 'Dataline AB'),
(8452, '00:21:1E', 'ARRIS Group, Inc.'),
(8453, '00:21:1F', 'SHINSUNG DELTATECH CO.,LTD.'),
(8454, '00:21:20', 'Sequel Technologies'),
(8455, '00:21:21', 'VRmagic GmbH'),
(8456, '00:21:22', 'Chip-pro Ltd.'),
(8457, '00:21:23', 'Aerosat Avionics'),
(8458, '00:21:24', 'Optos Plc'),
(8459, '00:21:25', 'KUK JE TONG SHIN Co.,LTD'),
(8460, '00:21:26', 'Shenzhen Torch Equipment Co., Ltd.'),
(8461, '00:21:27', 'TP-LINK Technology Co., Ltd.'),
(8462, '00:21:28', 'Oracle Corporation'),
(8463, '00:21:29', 'Cisco-Linksys, LLC'),
(8464, '00:21:2A', 'Audiovox Corporation'),
(8465, '00:21:2B', 'MSA Auer'),
(8466, '00:21:2C', 'SemIndia System Private Limited'),
(8467, '00:21:2D', 'SCIMOLEX CORPORATION'),
(8468, '00:21:2E', 'dresden-elektronik'),
(8469, '00:21:2F', 'Phoebe Micro Inc.'),
(8470, '00:21:30', 'Keico Hightech Inc.'),
(8471, '00:21:31', 'Blynke Inc.'),
(8472, '00:21:32', 'Masterclock, Inc.'),
(8473, '00:21:33', 'Building B, Inc'),
(8474, '00:21:34', 'Brandywine Communications'),
(8475, '00:21:35', 'ALCATEL-LUCENT'),
(8476, '00:21:36', 'ARRIS Group, Inc.'),
(8477, '00:21:37', 'Bay Controls, LLC'),
(8478, '00:21:38', 'Cepheid'),
(8479, '00:21:39', 'Escherlogic Inc.'),
(8480, '00:21:3A', 'Winchester Systems Inc.'),
(8481, '00:21:3B', 'Berkshire Products, Inc'),
(8482, '00:21:3C', 'AliphCom'),
(8483, '00:21:3D', 'Cermetek Microelectronics, Inc.'),
(8484, '00:21:3E', 'TomTom'),
(8485, '00:21:3F', 'A-Team Technology Ltd.'),
(8486, '00:21:40', 'EN Technologies Inc.'),
(8487, '00:21:41', 'RADLIVE'),
(8488, '00:21:42', 'Advanced Control Systems doo'),
(8489, '00:21:43', 'ARRIS Group, Inc.'),
(8490, '00:21:44', 'SS Telecoms'),
(8491, '00:21:45', 'Semptian Technologies Ltd.'),
(8492, '00:21:46', 'Sanmina-SCI'),
(8493, '00:21:47', 'Nintendo Co., Ltd.'),
(8494, '00:21:48', 'Kaco Solar Korea'),
(8495, '00:21:49', 'China Daheng Group ,Inc.'),
(8496, '00:21:4A', 'Pixel Velocity, Inc'),
(8497, '00:21:4B', 'Shenzhen HAMP Science &amp; Technology Co.,Ltd'),
(8498, '00:21:4C', 'SAMSUNG ELECTRONICS CO., LTD.'),
(8499, '00:21:4D', 'Guangzhou Skytone Transmission Technology Com. Ltd.'),
(8500, '00:21:4E', 'GS Yuasa Power Supply Ltd.'),
(8501, '00:21:4F', 'ALPS Electric Co., Ltd'),
(8502, '00:21:50', 'EYEVIEW ELECTRONICS'),
(8503, '00:21:51', 'Millinet Co., Ltd.'),
(8504, '00:21:52', 'General Satellite Research &amp; Development Limited'),
(8505, '00:21:53', 'SeaMicro Inc.'),
(8506, '00:21:54', 'D-TACQ Solutions Ltd'),
(8507, '00:21:55', 'CISCO SYSTEMS, INC.'),
(8508, '00:21:56', 'CISCO SYSTEMS, INC.'),
(8509, '00:21:57', 'National Datacast, Inc.'),
(8510, '00:21:58', 'Style Flying Technology Co.'),
(8511, '00:21:59', 'Juniper Networks'),
(8512, '00:21:5A', 'Hewlett-Packard Company'),
(8513, '00:21:5B', 'Inotive'),
(8514, '00:21:5C', 'Intel Corporate'),
(8515, '00:21:5D', 'Intel Corporate'),
(8516, '00:21:5E', 'IBM Corp'),
(8517, '00:21:5F', 'IHSE GmbH'),
(8518, '00:21:60', 'Hidea Solutions Co. Ltd.'),
(8519, '00:21:61', 'Yournet Inc.'),
(8520, '00:21:62', 'Nortel'),
(8521, '00:21:63', 'ASKEY COMPUTER CORP'),
(8522, '00:21:64', 'Special Design Bureau for Seismic Instrumentation'),
(8523, '00:21:65', 'Presstek Inc.'),
(8524, '00:21:66', 'NovAtel Inc.'),
(8525, '00:21:67', 'HWA JIN T&amp;I Corp.'),
(8526, '00:21:68', 'iVeia, LLC'),
(8527, '00:21:69', 'Prologix, LLC.'),
(8528, '00:21:6A', 'Intel Corporate'),
(8529, '00:21:6B', 'Intel Corporate'),
(8530, '00:21:6C', 'ODVA'),
(8531, '00:21:6D', 'Soltech Co., Ltd.'),
(8532, '00:21:6E', 'Function ATI (Huizhou) Telecommunications Co., Ltd.'),
(8533, '00:21:6F', 'SymCom, Inc.'),
(8534, '00:21:70', 'Dell Inc'),
(8535, '00:21:71', 'Wesung TNC Co., Ltd.'),
(8536, '00:21:72', 'Seoultek Valley'),
(8537, '00:21:73', 'Ion Torrent Systems, Inc.'),
(8538, '00:21:74', 'AvaLAN Wireless'),
(8539, '00:21:75', 'Pacific Satellite International Ltd.'),
(8540, '00:21:76', 'YMax Telecom Ltd.'),
(8541, '00:21:77', 'W. L. Gore &amp; Associates'),
(8542, '00:21:78', 'Matuschek Messtechnik GmbH'),
(8543, '00:21:79', 'IOGEAR, Inc.'),
(8544, '00:21:7A', 'Sejin Electron, Inc.'),
(8545, '00:21:7B', 'Bastec AB'),
(8546, '00:21:7C', '2Wire'),
(8547, '00:21:7D', 'PYXIS S.R.L.'),
(8548, '00:21:7E', 'Telit Communication s.p.a'),
(8549, '00:21:7F', 'Intraco Technology Pte Ltd'),
(8550, '00:21:80', 'ARRIS Group, Inc.'),
(8551, '00:21:81', 'Si2 Microsystems Limited'),
(8552, '00:21:82', 'SandLinks Systems, Ltd.'),
(8553, '00:21:83', 'VATECH HYDRO'),
(8554, '00:21:84', 'POWERSOFT SRL'),
(8555, '00:21:85', 'MICRO-STAR INT\'L CO.,LTD.'),
(8556, '00:21:86', 'Universal Global Scientific Industrial Co., Ltd'),
(8557, '00:21:87', 'Imacs GmbH'),
(8558, '00:21:88', 'EMC Corporation'),
(8559, '00:21:89', 'AppTech, Inc.'),
(8560, '00:21:8A', 'Electronic Design and Manufacturing Company'),
(8561, '00:21:8B', 'Wescon Technology, Inc.'),
(8562, '00:21:8C', 'TopControl GMBH'),
(8563, '00:21:8D', 'AP Router Ind. Eletronica LTDA'),
(8564, '00:21:8E', 'MEKICS CO., LTD.'),
(8565, '00:21:8F', 'Avantgarde Acoustic Lautsprechersysteme GmbH'),
(8566, '00:21:90', 'Goliath Solutions'),
(8567, '00:21:91', 'D-Link Corporation'),
(8568, '00:21:92', 'Baoding Galaxy Electronic Technology  Co.,Ltd'),
(8569, '00:21:93', 'Videofon MV'),
(8570, '00:21:94', 'Ping Communication'),
(8571, '00:21:95', 'GWD Media Limited'),
(8572, '00:21:96', 'Telsey  S.p.A.'),
(8573, '00:21:97', 'ELITEGROUP COMPUTER SYSTEM'),
(8574, '00:21:98', 'Thai Radio Co, LTD'),
(8575, '00:21:99', 'Vacon Plc'),
(8576, '00:21:9A', 'Cambridge Visual Networks Ltd'),
(8577, '00:21:9B', 'Dell Inc'),
(8578, '00:21:9C', 'Honeywld Technology Corp.'),
(8579, '00:21:9D', 'Adesys BV'),
(8580, '00:21:9E', 'Sony Ericsson Mobile Communications'),
(8581, '00:21:9F', 'SATEL OY'),
(8582, '00:21:A0', 'CISCO SYSTEMS, INC.'),
(8583, '00:21:A1', 'CISCO SYSTEMS, INC.'),
(8584, '00:21:A2', 'EKE-Electronics Ltd.'),
(8585, '00:21:A3', 'Micromint'),
(8586, '00:21:A4', 'Dbii Networks'),
(8587, '00:21:A5', 'ERLPhase Power Technologies Ltd.'),
(8588, '00:21:A6', 'Videotec Spa'),
(8589, '00:21:A7', 'Hantle System Co., Ltd.'),
(8590, '00:21:A8', 'Telephonics Corporation'),
(8591, '00:21:A9', 'Mobilink Telecom Co.,Ltd'),
(8592, '00:21:AA', 'Nokia Danmark A/S'),
(8593, '00:21:AB', 'Nokia Danmark A/S'),
(8594, '00:21:AC', 'Infrared Integrated Systems Ltd'),
(8595, '00:21:AD', 'Nordic ID Oy'),
(8596, '00:21:AE', 'ALCATEL-LUCENT FRANCE - WTD'),
(8597, '00:21:AF', 'Radio Frequency Systems'),
(8598, '00:21:B0', 'Tyco Telecommunications'),
(8599, '00:21:B1', 'DIGITAL SOLUTIONS LTD'),
(8600, '00:21:B2', 'Fiberblaze A/S'),
(8601, '00:21:B3', 'Ross Controls'),
(8602, '00:21:B4', 'APRO MEDIA CO., LTD'),
(8603, '00:21:B5', 'Galvanic Ltd'),
(8604, '00:21:B6', 'Triacta Power Technologies Inc.'),
(8605, '00:21:B7', 'Lexmark International Inc.'),
(8606, '00:21:B8', 'Inphi Corporation'),
(8607, '00:21:B9', 'Universal Devices Inc.'),
(8608, '00:21:BA', 'Texas Instruments'),
(8609, '00:21:BB', 'Riken Keiki Co., Ltd.'),
(8610, '00:21:BC', 'ZALA COMPUTER'),
(8611, '00:21:BD', 'Nintendo Co., Ltd.'),
(8612, '00:21:BE', 'Cisco, Service Provider Video Technology Group'),
(8613, '00:21:BF', 'Hitachi High-Tech Control Systems Corporation'),
(8614, '00:21:C0', 'Mobile Appliance, Inc.'),
(8615, '00:21:C1', 'ABB Oy / Medium Voltage Products'),
(8616, '00:21:C2', 'GL Communications Inc'),
(8617, '00:21:C3', 'CORNELL Communications, Inc.'),
(8618, '00:21:C4', 'Consilium AB'),
(8619, '00:21:C5', '3DSP Corp'),
(8620, '00:21:C6', 'CSJ Global, Inc.'),
(8621, '00:21:C7', 'Russound'),
(8622, '00:21:C8', 'LOHUIS Networks'),
(8623, '00:21:C9', 'Wavecom Asia Pacific Limited'),
(8624, '00:21:CA', 'ART System Co., Ltd.'),
(8625, '00:21:CB', 'SMS TECNOLOGIA ELETRONICA LTDA'),
(8626, '00:21:CC', 'Flextronics International'),
(8627, '00:21:CD', 'LiveTV'),
(8628, '00:21:CE', 'NTC-Metrotek'),
(8629, '00:21:CF', 'The Crypto Group'),
(8630, '00:21:D0', 'Global Display Solutions Spa'),
(8631, '00:21:D1', 'Samsung Electronics Co.,Ltd'),
(8632, '00:21:D2', 'Samsung Electronics Co.,Ltd'),
(8633, '00:21:D3', 'BOCOM SECURITY(ASIA PACIFIC) LIMITED'),
(8634, '00:21:D4', 'Vollmer Werke GmbH'),
(8635, '00:21:D5', 'X2E GmbH'),
(8636, '00:21:D6', 'LXI Consortium'),
(8637, '00:21:D7', 'CISCO SYSTEMS, INC.'),
(8638, '00:21:D8', 'CISCO SYSTEMS, INC.'),
(8639, '00:21:D9', 'SEKONIC CORPORATION'),
(8640, '00:21:DA', 'Automation Products Group Inc.'),
(8641, '00:21:DB', 'Santachi Video Technology (Shenzhen) Co., Ltd.'),
(8642, '00:21:DC', 'TECNOALARM S.r.l.'),
(8643, '00:21:DD', 'Northstar Systems Corp'),
(8644, '00:21:DE', 'Firepro Wireless'),
(8645, '00:21:DF', 'Martin Christ GmbH'),
(8646, '00:21:E0', 'CommAgility Ltd'),
(8647, '00:21:E1', 'Nortel Networks'),
(8648, '00:21:E2', 'Creative Electronic GmbH'),
(8649, '00:21:E3', 'SerialTek LLC'),
(8650, '00:21:E4', 'I-WIN'),
(8651, '00:21:E5', 'Display Solution AG'),
(8652, '00:21:E6', 'Starlight Video Limited'),
(8653, '00:21:E7', 'Informatics Services Corporation'),
(8654, '00:21:E8', 'Murata Manufacturing Co., Ltd.'),
(8655, '00:21:E9', 'Apple'),
(8656, '00:21:EA', 'Bystronic Laser AG'),
(8657, '00:21:EB', 'ESP SYSTEMS, LLC'),
(8658, '00:21:EC', 'Solutronic GmbH'),
(8659, '00:21:ED', 'Telegesis'),
(8660, '00:21:EE', 'Full Spectrum Inc.'),
(8661, '00:21:EF', 'Kapsys'),
(8662, '00:21:F0', 'EW3 Technologies LLC'),
(8663, '00:21:F1', 'Tutus Data AB'),
(8664, '00:21:F2', 'EASY3CALL Technology Limited'),
(8665, '00:21:F3', 'Si14 SpA'),
(8666, '00:21:F4', 'INRange Systems, Inc'),
(8667, '00:21:F5', 'Western Engravers Supply, Inc.'),
(8668, '00:21:F6', 'Oracle Corporation'),
(8669, '00:21:F7', 'HPN Supply Chain'),
(8670, '00:21:F8', 'Enseo, Inc.'),
(8671, '00:21:F9', 'WIRECOM Technologies'),
(8672, '00:21:FA', 'A4SP Technologies Ltd.'),
(8673, '00:21:FB', 'LG Electronics'),
(8674, '00:21:FC', 'Nokia Danmark A/S'),
(8675, '00:21:FD', 'DSTA S.L.'),
(8676, '00:21:FE', 'Nokia Danmark A/S'),
(8677, '00:21:FF', 'Cyfrowy Polsat SA'),
(8678, '00:22:00', 'IBM Corp'),
(8679, '00:22:01', 'Aksys Networks Inc'),
(8680, '00:22:02', 'Excito Elektronik i Sk&aring;ne AB'),
(8681, '00:22:03', 'Glensound Electronics Ltd'),
(8682, '00:22:04', 'KORATEK'),
(8683, '00:22:05', 'WeLink Solutions, Inc.'),
(8684, '00:22:06', 'Cyberdyne Inc.'),
(8685, '00:22:07', 'Inteno Broadband Technology AB'),
(8686, '00:22:08', 'Certicom Corp'),
(8687, '00:22:09', 'Omron Healthcare Co., Ltd'),
(8688, '00:22:0A', 'OnLive, Inc'),
(8689, '00:22:0B', 'National Source Coding Center'),
(8690, '00:22:0C', 'CISCO SYSTEMS, INC.'),
(8691, '00:22:0D', 'CISCO SYSTEMS, INC.'),
(8692, '00:22:0E', 'Indigo Security Co., Ltd.'),
(8693, '00:22:0F', 'MoCA (Multimedia over Coax Alliance)'),
(8694, '00:22:10', 'ARRIS Group, Inc.'),
(8695, '00:22:11', 'Rohati Systems'),
(8696, '00:22:12', 'CAI Networks, Inc.'),
(8697, '00:22:13', 'PCI CORPORATION'),
(8698, '00:22:14', 'RINNAI KOREA'),
(8699, '00:22:15', 'ASUSTek COMPUTER INC.'),
(8700, '00:22:16', 'SHIBAURA VENDING MACHINE CORPORATION'),
(8701, '00:22:17', 'Neat Electronics'),
(8702, '00:22:18', 'Verivue Inc.'),
(8703, '00:22:19', 'Dell Inc'),
(8704, '00:22:1A', 'Audio Precision'),
(8705, '00:22:1B', 'Morega Systems'),
(8706, '00:22:1C', 'PRIVATE'),
(8707, '00:22:1D', 'Freegene Technology LTD'),
(8708, '00:22:1E', 'Media Devices Co., Ltd.'),
(8709, '00:22:1F', 'eSang Technologies Co., Ltd.'),
(8710, '00:22:20', 'Mitac Technology Corp'),
(8711, '00:22:21', 'ITOH DENKI CO,LTD.'),
(8712, '00:22:22', 'Schaffner Deutschland GmbH'),
(8713, '00:22:23', 'TimeKeeping Systems, Inc.'),
(8714, '00:22:24', 'Good Will Instrument Co., Ltd.'),
(8715, '00:22:25', 'Thales Avionics Ltd'),
(8716, '00:22:26', 'Avaak, Inc.'),
(8717, '00:22:27', 'uv-electronic GmbH'),
(8718, '00:22:28', 'Breeze Innovations Ltd.'),
(8719, '00:22:29', 'Compumedics Ltd'),
(8720, '00:22:2A', 'SoundEar A/S'),
(8721, '00:22:2B', 'Nucomm, Inc.'),
(8722, '00:22:2C', 'Ceton Corp'),
(8723, '00:22:2D', 'SMC Networks Inc.'),
(8724, '00:22:2E', 'maintech GmbH'),
(8725, '00:22:2F', 'Open Grid Computing, Inc.'),
(8726, '00:22:30', 'FutureLogic Inc.'),
(8727, '00:22:31', 'SMT&amp;C Co., Ltd.'),
(8728, '00:22:32', 'Design Design Technology Ltd'),
(8729, '00:22:33', 'ADB Broadband Italia'),
(8730, '00:22:34', 'Corventis Inc.'),
(8731, '00:22:35', 'Strukton Systems bv'),
(8732, '00:22:36', 'VECTOR SP. Z O.O.'),
(8733, '00:22:37', 'Shinhint Group'),
(8734, '00:22:38', 'LOGIPLUS'),
(8735, '00:22:39', 'Indiana Life Sciences Incorporated'),
(8736, '00:22:3A', 'Scientific Atlanta, Cisco SPVT Group'),
(8737, '00:22:3B', 'Communication Networks, LLC'),
(8738, '00:22:3C', 'RATIO Entwicklungen GmbH'),
(8739, '00:22:3D', 'JumpGen Systems, LLC'),
(8740, '00:22:3E', 'IRTrans GmbH'),
(8741, '00:22:3F', 'Netgear Inc.'),
(8742, '00:22:40', 'Universal Telecom S/A'),
(8743, '00:22:41', 'Apple'),
(8744, '00:22:42', 'Alacron Inc.'),
(8745, '00:22:43', 'AzureWave Technologies, Inc.'),
(8746, '00:22:44', 'Chengdu Linkon Communications Device Co., Ltd'),
(8747, '00:22:45', 'Leine &amp; Linde AB'),
(8748, '00:22:46', 'Evoc Intelligent Technology Co.,Ltd.'),
(8749, '00:22:47', 'DAC ENGINEERING CO., LTD.'),
(8750, '00:22:48', 'Microsoft Corporation'),
(8751, '00:22:49', 'HOME MULTIENERGY SL'),
(8752, '00:22:4A', 'RAYLASE AG'),
(8753, '00:22:4B', 'AIRTECH TECHNOLOGIES, INC.'),
(8754, '00:22:4C', 'Nintendo Co., Ltd.'),
(8755, '00:22:4D', 'MITAC INTERNATIONAL CORP.'),
(8756, '00:22:4E', 'SEEnergy Corp.'),
(8757, '00:22:4F', 'Byzoro Networks Ltd.'),
(8758, '00:22:50', 'Point Six Wireless, LLC'),
(8759, '00:22:51', 'Lumasense Technologies'),
(8760, '00:22:52', 'ZOLL Lifecor Corporation'),
(8761, '00:22:53', 'Entorian Technologies'),
(8762, '00:22:54', 'Bigelow Aerospace'),
(8763, '00:22:55', 'CISCO SYSTEMS, INC.'),
(8764, '00:22:56', 'CISCO SYSTEMS, INC.'),
(8765, '00:22:57', '3Com Europe Ltd'),
(8766, '00:22:58', 'Taiyo Yuden Co., Ltd.'),
(8767, '00:22:59', 'Guangzhou New Postcom Equipment Co.,Ltd.'),
(8768, '00:22:5A', 'Garde Security AB'),
(8769, '00:22:5B', 'Teradici Corporation'),
(8770, '00:22:5C', 'Multimedia &amp; Communication Technology'),
(8771, '00:22:5D', 'Digicable Network India Pvt. Ltd.'),
(8772, '00:22:5E', 'Uwin Technologies Co.,LTD'),
(8773, '00:22:5F', 'Liteon Technology Corporation'),
(8774, '00:22:60', 'AFREEY Inc.'),
(8775, '00:22:61', 'Frontier Silicon Ltd'),
(8776, '00:22:62', 'BEP Marine'),
(8777, '00:22:63', 'Koos Technical Services, Inc.'),
(8778, '00:22:64', 'Hewlett-Packard Company'),
(8779, '00:22:65', 'Nokia Danmark A/S'),
(8780, '00:22:66', 'Nokia Danmark A/S'),
(8781, '00:22:67', 'Nortel Networks'),
(8782, '00:22:68', 'Hon Hai Precision Ind. Co., Ltd.'),
(8783, '00:22:69', 'Hon Hai Precision Ind. Co., Ltd.'),
(8784, '00:22:6A', 'Honeywell'),
(8785, '00:22:6B', 'Cisco-Linksys, LLC'),
(8786, '00:22:6C', 'LinkSprite Technologies, Inc.'),
(8787, '00:22:6D', 'Shenzhen GIEC Electronics Co., Ltd.'),
(8788, '00:22:6E', 'Gowell Electronic Limited'),
(8789, '00:22:6F', '3onedata Technology Co. Ltd.'),
(8790, '00:22:70', 'ABK North America, LLC'),
(8791, '00:22:71', 'J&auml;ger Computergesteuerte Me&szlig;technik GmbH.'),
(8792, '00:22:72', 'American Micro-Fuel Device Corp.'),
(8793, '00:22:73', 'Techway'),
(8794, '00:22:74', 'FamilyPhone AB'),
(8795, '00:22:75', 'Belkin International Inc.'),
(8796, '00:22:76', 'Triple EYE B.V.'),
(8797, '00:22:77', 'NEC Australia Pty Ltd'),
(8798, '00:22:78', 'Shenzhen  Tongfang Multimedia  Technology Co.,Ltd.'),
(8799, '00:22:79', 'Nippon Conlux Co., Ltd.'),
(8800, '00:22:7A', 'Telecom Design'),
(8801, '00:22:7B', 'Apogee Labs, Inc.'),
(8802, '00:22:7C', 'Woori SMT Co.,ltd'),
(8803, '00:22:7D', 'YE DATA INC.'),
(8804, '00:22:7E', 'Chengdu 30Kaitian Communication Industry Co.Ltd'),
(8805, '00:22:7F', 'Ruckus Wireless'),
(8806, '00:22:80', 'A2B Electronics AB'),
(8807, '00:22:81', 'Daintree Networks Pty'),
(8808, '00:22:82', '8086 Consultancy'),
(8809, '00:22:83', 'Juniper Networks'),
(8810, '00:22:84', 'DESAY A&amp;V SCIENCE AND TECHNOLOGY CO.,LTD'),
(8811, '00:22:85', 'NOMUS COMM SYSTEMS'),
(8812, '00:22:86', 'ASTRON'),
(8813, '00:22:87', 'Titan Wireless LLC'),
(8814, '00:22:88', 'Sagrad, Inc.'),
(8815, '00:22:89', 'Optosecurity Inc.'),
(8816, '00:22:8A', 'Teratronik elektronische systeme gmbh'),
(8817, '00:22:8B', 'Kensington Computer Products Group'),
(8818, '00:22:8C', 'Photon Europe GmbH'),
(8819, '00:22:8D', 'GBS Laboratories LLC'),
(8820, '00:22:8E', 'TV-NUMERIC'),
(8821, '00:22:8F', 'CNRS'),
(8822, '00:22:90', 'CISCO SYSTEMS, INC.'),
(8823, '00:22:91', 'CISCO SYSTEMS, INC.'),
(8824, '00:22:92', 'Cinetal'),
(8825, '00:22:93', 'ZTE Corporation'),
(8826, '00:22:94', 'Kyocera Corporation'),
(8827, '00:22:95', 'SGM Technology for lighting spa'),
(8828, '00:22:96', 'LinoWave Corporation'),
(8829, '00:22:97', 'XMOS Semiconductor'),
(8830, '00:22:98', 'Sony Ericsson Mobile Communications'),
(8831, '00:22:99', 'SeaMicro Inc.'),
(8832, '00:22:9A', 'Lastar, Inc.'),
(8833, '00:22:9B', 'AverLogic Technologies, Inc.'),
(8834, '00:22:9C', 'Verismo Networks Inc'),
(8835, '00:22:9D', 'PYUNG-HWA IND.CO.,LTD'),
(8836, '00:22:9E', 'Social Aid Research Co., Ltd.'),
(8837, '00:22:9F', 'Sensys Traffic AB'),
(8838, '00:22:A0', 'Delphi Corporation'),
(8839, '00:22:A1', 'Huawei Symantec Technologies Co.,Ltd.'),
(8840, '00:22:A2', 'Xtramus Technologies'),
(8841, '00:22:A3', 'California Eastern Laboratories'),
(8842, '00:22:A4', '2Wire'),
(8843, '00:22:A5', 'Texas Instruments'),
(8844, '00:22:A6', 'Sony Computer Entertainment America'),
(8845, '00:22:A7', 'Tyco Electronics AMP GmbH'),
(8846, '00:22:A8', 'Ouman Oy'),
(8847, '00:22:A9', 'LG Electronics Inc'),
(8848, '00:22:AA', 'Nintendo Co., Ltd.'),
(8849, '00:22:AB', 'Shenzhen Turbosight Technology Ltd'),
(8850, '00:22:AC', 'Hangzhou Siyuan Tech. Co., Ltd'),
(8851, '00:22:AD', 'TELESIS TECHNOLOGIES, INC.'),
(8852, '00:22:AE', 'Mattel Inc.'),
(8853, '00:22:AF', 'Safety Vision'),
(8854, '00:22:B0', 'D-Link Corporation'),
(8855, '00:22:B1', 'Elbit Systems'),
(8856, '00:22:B2', '4RF Communications Ltd'),
(8857, '00:22:B3', 'Sei S.p.A.'),
(8858, '00:22:B4', 'ARRIS Group, Inc.'),
(8859, '00:22:B5', 'NOVITA'),
(8860, '00:22:B6', 'Superflow Technologies Group'),
(8861, '00:22:B7', 'GSS Grundig SAT-Systems GmbH'),
(8862, '00:22:B8', 'Norcott'),
(8863, '00:22:B9', 'Analogix Seminconductor, Inc'),
(8864, '00:22:BA', 'HUTH Elektronik Systeme GmbH'),
(8865, '00:22:BB', 'beyerdynamic GmbH &amp; Co. KG'),
(8866, '00:22:BC', 'JDSU France SAS'),
(8867, '00:22:BD', 'CISCO SYSTEMS, INC.'),
(8868, '00:22:BE', 'CISCO SYSTEMS, INC.'),
(8869, '00:22:BF', 'SieAmp Group of Companies'),
(8870, '00:22:C0', 'Shenzhen Forcelink Electronic Co, Ltd'),
(8871, '00:22:C1', 'Active Storage Inc.'),
(8872, '00:22:C2', 'Proview Eletr&ocirc;nica do Brasil LTDA'),
(8873, '00:22:C3', 'Zeeport Technology Inc.'),
(8874, '00:22:C4', 'epro GmbH'),
(8875, '00:22:C5', 'INFORSON Co,Ltd.'),
(8876, '00:22:C6', 'Sutus Inc'),
(8877, '00:22:C7', 'SEGGER Microcontroller GmbH &amp; Co. KG'),
(8878, '00:22:C8', 'Applied Instruments B.V.'),
(8879, '00:22:C9', 'Lenord, Bauer &amp; Co GmbH'),
(8880, '00:22:CA', 'Anviz Biometric Tech. Co., Ltd.'),
(8881, '00:22:CB', 'IONODES Inc.'),
(8882, '00:22:CC', 'SciLog, Inc.'),
(8883, '00:22:CD', 'Ared Technology Co., Ltd.'),
(8884, '00:22:CE', 'Cisco, Service Provider Video Technology Group'),
(8885, '00:22:CF', 'PLANEX Communications INC'),
(8886, '00:22:D0', 'Polar Electro Oy'),
(8887, '00:22:D1', 'Albrecht Jung GmbH &amp; Co. KG'),
(8888, '00:22:D2', 'All Earth Com&eacute;rcio de Eletr&ocirc;nicos LTDA.'),
(8889, '00:22:D3', 'Hub-Tech'),
(8890, '00:22:D4', 'ComWorth Co., Ltd.'),
(8891, '00:22:D5', 'Eaton Corp. Electrical Group Data Center Solutions - Pulizzi'),
(8892, '00:22:D6', 'Cypak AB'),
(8893, '00:22:D7', 'Nintendo Co., Ltd.'),
(8894, '00:22:D8', 'Shenzhen GST Security and Safety Technology Limited'),
(8895, '00:22:D9', 'Fortex Industrial Ltd.'),
(8896, '00:22:DA', 'ANATEK, LLC'),
(8897, '00:22:DB', 'Translogic Corporation'),
(8898, '00:22:DC', 'Vigil Health Solutions Inc.'),
(8899, '00:22:DD', 'Protecta Electronics Ltd'),
(8900, '00:22:DE', 'OPPO Digital, Inc.'),
(8901, '00:22:DF', 'TAMUZ Monitors'),
(8902, '00:22:E0', 'Atlantic Software Technologies S.r.L.'),
(8903, '00:22:E1', 'ZORT Labs, LLC.'),
(8904, '00:22:E2', 'WABTEC Transit Division'),
(8905, '00:22:E3', 'Amerigon'),
(8906, '00:22:E4', 'APASS TECHNOLOGY CO., LTD.'),
(8907, '00:22:E5', 'Fisher-Rosemount Systems Inc.'),
(8908, '00:22:E6', 'Intelligent Data'),
(8909, '00:22:E7', 'WPS Parking Systems'),
(8910, '00:22:E8', 'Applition Co., Ltd.'),
(8911, '00:22:E9', 'ProVision Communications'),
(8912, '00:22:EA', 'Rustelcom Inc.'),
(8913, '00:22:EB', 'Data Respons A/S'),
(8914, '00:22:EC', 'IDEALBT TECHNOLOGY CORPORATION'),
(8915, '00:22:ED', 'TSI Power Corporation'),
(8916, '00:22:EE', 'Algo Communication Products Ltd'),
(8917, '00:22:EF', 'iWDL Technologies'),
(8918, '00:22:F0', '3 Greens Aviation Limited'),
(8919, '00:22:F1', 'PRIVATE'),
(8920, '00:22:F2', 'SunPower Corp'),
(8921, '00:22:F3', 'SHARP Corporation'),
(8922, '00:22:F4', 'AMPAK Technology, Inc.'),
(8923, '00:22:F5', 'Advanced Realtime Tracking GmbH'),
(8924, '00:22:F6', 'Syracuse Research Corporation'),
(8925, '00:22:F7', 'Conceptronic'),
(8926, '00:22:F8', 'PIMA Electronic Systems Ltd.'),
(8927, '00:22:F9', 'Pollin Electronic GmbH'),
(8928, '00:22:FA', 'Intel Corporate'),
(8929, '00:22:FB', 'Intel Corporate'),
(8930, '00:22:FC', 'Nokia Danmark A/S'),
(8931, '00:22:FD', 'Nokia Danmark A/S'),
(8932, '00:22:FE', 'Microprocessor Designs Inc'),
(8933, '00:22:FF', 'iWDL Technologies'),
(8934, '00:23:00', 'Cayee Computer Ltd.'),
(8935, '00:23:01', 'Witron Technology Limited'),
(8936, '00:23:02', 'Cobalt Digital, Inc.'),
(8937, '00:23:03', 'LITE-ON IT Corporation'),
(8938, '00:23:04', 'CISCO SYSTEMS, INC.'),
(8939, '00:23:05', 'CISCO SYSTEMS, INC.'),
(8940, '00:23:06', 'ALPS Electric Co., Ltd'),
(8941, '00:23:07', 'FUTURE INNOVATION TECH CO.,LTD'),
(8942, '00:23:08', 'Arcadyan Technology Corporation'),
(8943, '00:23:09', 'Janam Technologies LLC'),
(8944, '00:23:0A', 'ARBURG GmbH &amp; Co KG'),
(8945, '00:23:0B', 'ARRIS Group, Inc.'),
(8946, '00:23:0C', 'CLOVER ELECTRONICS CO.,LTD.'),
(8947, '00:23:0D', 'Nortel Networks'),
(8948, '00:23:0E', 'Gorba AG'),
(8949, '00:23:0F', 'Hirsch Electronics Corporation'),
(8950, '00:23:10', 'LNC Technology Co., Ltd.'),
(8951, '00:23:11', 'Gloscom Co., Ltd.'),
(8952, '00:23:12', 'Apple'),
(8953, '00:23:13', 'Qool Technologies Ltd.'),
(8954, '00:23:14', 'Intel Corporate'),
(8955, '00:23:15', 'Intel Corporate'),
(8956, '00:23:16', 'KISAN ELECTRONICS CO'),
(8957, '00:23:17', 'Lasercraft Inc'),
(8958, '00:23:18', 'Toshiba'),
(8959, '00:23:19', 'Sielox LLC'),
(8960, '00:23:1A', 'ITF Co., Ltd.'),
(8961, '00:23:1B', 'Danaher Motion - Kollmorgen'),
(8962, '00:23:1C', 'Fourier Systems Ltd.'),
(8963, '00:23:1D', 'Deltacom Electronics Ltd'),
(8964, '00:23:1E', 'Cezzer Multimedia Technologies'),
(8965, '00:23:1F', 'Guangda Electronic &amp; Telecommunication Technology Development Co., Ltd.'),
(8966, '00:23:20', 'Nicira Networks'),
(8967, '00:23:21', 'Avitech International Corp'),
(8968, '00:23:22', 'KISS Teknical Solutions, Inc.'),
(8969, '00:23:23', 'Zylin AS'),
(8970, '00:23:24', 'G-PRO COMPUTER'),
(8971, '00:23:25', 'IOLAN Holding'),
(8972, '00:23:26', 'Fujitsu Limited'),
(8973, '00:23:27', 'Shouyo Electronics CO., LTD'),
(8974, '00:23:28', 'ALCON TELECOMMUNICATIONS CO., LTD.'),
(8975, '00:23:29', 'DDRdrive LLC'),
(8976, '00:23:2A', 'eonas IT-Beratung und -Entwicklung GmbH'),
(8977, '00:23:2B', 'IRD A/S'),
(8978, '00:23:2C', 'Senticare'),
(8979, '00:23:2D', 'SandForce'),
(8980, '00:23:2E', 'Kedah Electronics Engineering, LLC'),
(8981, '00:23:2F', 'Advanced Card Systems Ltd.'),
(8982, '00:23:30', 'DIZIPIA, INC.'),
(8983, '00:23:31', 'Nintendo Co., Ltd.'),
(8984, '00:23:32', 'Apple'),
(8985, '00:23:33', 'CISCO SYSTEMS, INC.'),
(8986, '00:23:34', 'CISCO SYSTEMS, INC.'),
(8987, '00:23:35', 'Linkflex Co.,Ltd'),
(8988, '00:23:36', 'METEL s.r.o.'),
(8989, '00:23:37', 'Global Star Solutions ULC'),
(8990, '00:23:38', 'OJ-Electronics A/S'),
(8991, '00:23:39', 'Samsung Electronics'),
(8992, '00:23:3A', 'Samsung Electronics Co.,Ltd'),
(8993, '00:23:3B', 'C-Matic Systems Ltd'),
(8994, '00:23:3C', 'Alflex'),
(8995, '00:23:3D', 'Novero holding B.V.'),
(8996, '00:23:3E', 'Alcatel-Lucent-IPD'),
(8997, '00:23:3F', 'Purechoice Inc'),
(8998, '00:23:40', 'MiX Telematics'),
(8999, '00:23:41', 'Siemens AB, Infrastructure &amp; Cities, Building Technologies Division, IC BT SSP SP BA PR'),
(9000, '00:23:42', 'Coffee Equipment Company'),
(9001, '00:23:43', 'TEM AG'),
(9002, '00:23:44', 'Objective Interface Systems, Inc.'),
(9003, '00:23:45', 'Sony Ericsson Mobile Communications'),
(9004, '00:23:46', 'Vestac'),
(9005, '00:23:47', 'ProCurve Networking by HP'),
(9006, '00:23:48', 'SAGEM COMMUNICATION'),
(9007, '00:23:49', 'Helmholtz Centre Berlin for Material and Energy'),
(9008, '00:23:4A', 'PRIVATE'),
(9009, '00:23:4B', 'Inyuan Technology Inc.'),
(9010, '00:23:4C', 'KTC AB'),
(9011, '00:23:4D', 'Hon Hai Precision Ind. Co., Ltd.'),
(9012, '00:23:4E', 'Hon Hai Precision Ind. Co., Ltd.'),
(9013, '00:23:4F', 'Luminous Power Technologies Pvt. Ltd.'),
(9014, '00:23:50', 'LynTec'),
(9015, '00:23:51', '2Wire'),
(9016, '00:23:52', 'DATASENSOR S.p.A.'),
(9017, '00:23:53', 'F E T Elettronica snc'),
(9018, '00:23:54', 'ASUSTek COMPUTER INC.'),
(9019, '00:23:55', 'Kinco Automation(Shanghai) Ltd.'),
(9020, '00:23:56', 'Packet Forensics LLC'),
(9021, '00:23:57', 'Pitronot Technologies and Engineering P.T.E. Ltd.'),
(9022, '00:23:58', 'SYSTEL SA'),
(9023, '00:23:59', 'Benchmark Electronics ( Thailand ) Public Company Limited'),
(9024, '00:23:5A', 'COMPAL INFORMATION (KUNSHAN) CO., Ltd.'),
(9025, '00:23:5B', 'Gulfstream'),
(9026, '00:23:5C', 'Aprius, Inc.'),
(9027, '00:23:5D', 'CISCO SYSTEMS, INC.'),
(9028, '00:23:5E', 'CISCO SYSTEMS, INC.'),
(9029, '00:23:5F', 'Silicon Micro Sensors GmbH'),
(9030, '00:23:60', 'Lookit Technology Co., Ltd'),
(9031, '00:23:61', 'Unigen Corporation'),
(9032, '00:23:62', 'Goldline Controls'),
(9033, '00:23:63', 'Zhuhai RaySharp Technology Co., Ltd.'),
(9034, '00:23:64', 'Power Instruments Pte Ltd'),
(9035, '00:23:65', 'ELKA-Elektronik GmbH'),
(9036, '00:23:66', 'Beijing Siasun Electronic System Co.,Ltd.'),
(9037, '00:23:67', 'UniControls a.s.'),
(9038, '00:23:68', 'Zebra Technologies Inc'),
(9039, '00:23:69', 'Cisco-Linksys, LLC'),
(9040, '00:23:6A', 'SmartRG Inc'),
(9041, '00:23:6B', 'Xembedded, Inc.'),
(9042, '00:23:6C', 'Apple'),
(9043, '00:23:6D', 'ResMed Ltd'),
(9044, '00:23:6E', 'Burster GmbH &amp; Co KG'),
(9045, '00:23:6F', 'DAQ System'),
(9046, '00:23:70', 'Snell'),
(9047, '00:23:71', 'SOAM Systel'),
(9048, '00:23:72', 'MORE STAR INDUSTRIAL GROUP LIMITED'),
(9049, '00:23:73', 'GridIron Systems, Inc.'),
(9050, '00:23:74', 'ARRIS Group, Inc.'),
(9051, '00:23:75', 'ARRIS Group, Inc.'),
(9052, '00:23:76', 'HTC Corporation'),
(9053, '00:23:77', 'Isotek Electronics Ltd'),
(9054, '00:23:78', 'GN Netcom A/S'),
(9055, '00:23:79', 'Union Business Machines Co. Ltd.'),
(9056, '00:23:7A', 'RIM'),
(9057, '00:23:7B', 'WHDI LLC'),
(9058, '00:23:7C', 'NEOTION'),
(9059, '00:23:7D', 'Hewlett-Packard Company'),
(9060, '00:23:7E', 'ELSTER GMBH'),
(9061, '00:23:7F', 'PLANTRONICS, INC.'),
(9062, '00:23:80', 'Nanoteq'),
(9063, '00:23:81', 'Lengda Technology(Xiamen) Co.,Ltd.'),
(9064, '00:23:82', 'Lih Rong Electronic Enterprise Co., Ltd.'),
(9065, '00:23:83', 'InMage Systems Inc'),
(9066, '00:23:84', 'GGH Engineering s.r.l.'),
(9067, '00:23:85', 'ANTIPODE'),
(9068, '00:23:86', 'Tour &amp; Andersson AB'),
(9069, '00:23:87', 'ThinkFlood, Inc.'),
(9070, '00:23:88', 'V.T. Telematica S.p.a.'),
(9071, '00:23:89', 'HANGZHOU H3C Technologies Co., Ltd.'),
(9072, '00:23:8A', 'Ciena Corporation'),
(9073, '00:23:8B', 'Quanta Computer Inc.'),
(9074, '00:23:8C', 'PRIVATE'),
(9075, '00:23:8D', 'Techno Design Co., Ltd.'),
(9076, '00:23:8E', 'Pirelli Tyre S.p.A.'),
(9077, '00:23:8F', 'NIDEC COPAL CORPORATION'),
(9078, '00:23:90', 'Algolware Corporation'),
(9079, '00:23:91', 'Maxian'),
(9080, '00:23:92', 'Proteus Industries Inc.'),
(9081, '00:23:93', 'AJINEXTEK'),
(9082, '00:23:94', 'Samjeon'),
(9083, '00:23:95', 'ARRIS Group, Inc.'),
(9084, '00:23:96', 'ANDES TECHNOLOGY CORPORATION'),
(9085, '00:23:97', 'Westell Technologies Inc.'),
(9086, '00:23:98', 'Sky Control'),
(9087, '00:23:99', 'VD Division, Samsung Electronics Co.'),
(9088, '00:23:9A', 'EasyData Hardware GmbH'),
(9089, '00:23:9B', 'Elster Solutions, LLC'),
(9090, '00:23:9C', 'Juniper Networks'),
(9091, '00:23:9D', 'Mapower Electronics Co., Ltd'),
(9092, '00:23:9E', 'Jiangsu Lemote Technology Corporation Limited'),
(9093, '00:23:9F', 'Institut f&uuml;r Pr&uuml;ftechnik'),
(9094, '00:23:A0', 'Hana CNS Co., LTD.'),
(9095, '00:23:A1', 'Trend Electronics Ltd'),
(9096, '00:23:A2', 'ARRIS Group, Inc.'),
(9097, '00:23:A3', 'ARRIS Group, Inc.'),
(9098, '00:23:A4', 'New Concepts Development Corp.'),
(9099, '00:23:A5', 'SageTV, LLC'),
(9100, '00:23:A6', 'E-Mon'),
(9101, '00:23:A7', 'Redpine Signals, Inc.'),
(9102, '00:23:A8', 'Marshall Electronics'),
(9103, '00:23:A9', 'Beijing Detianquan Electromechanical Equipment Co., Ltd'),
(9104, '00:23:AA', 'HFR, Inc.'),
(9105, '00:23:AB', 'CISCO SYSTEMS, INC.'),
(9106, '00:23:AC', 'CISCO SYSTEMS, INC.'),
(9107, '00:23:AD', 'Xmark Corporation'),
(9108, '00:23:AE', 'Dell Inc.'),
(9109, '00:23:AF', 'ARRIS Group, Inc.'),
(9110, '00:23:B0', 'COMXION Technology Inc.'),
(9111, '00:23:B1', 'Longcheer Technology (Singapore) Pte Ltd'),
(9112, '00:23:B2', 'Intelligent Mechatronic Systems Inc'),
(9113, '00:23:B3', 'Lyyn AB'),
(9114, '00:23:B4', 'Nokia Danmark A/S'),
(9115, '00:23:B5', 'ORTANA LTD'),
(9116, '00:23:B6', 'SECURITE COMMUNICATIONS / HONEYWELL'),
(9117, '00:23:B7', 'Q-Light Co., Ltd.'),
(9118, '00:23:B8', 'Sichuan Jiuzhou Electronic Technology Co.,Ltd'),
(9119, '00:23:B9', 'EADS Deutschland GmbH'),
(9120, '00:23:BA', 'Chroma'),
(9121, '00:23:BB', 'Schmitt Industries'),
(9122, '00:23:BC', 'EQ-SYS GmbH'),
(9123, '00:23:BD', 'Digital Ally, Inc.'),
(9124, '00:23:BE', 'Cisco SPVTG'),
(9125, '00:23:BF', 'Mainpine, Inc.'),
(9126, '00:23:C0', 'Broadway Networks'),
(9127, '00:23:C1', 'Securitas Direct AB'),
(9128, '00:23:C2', 'SAMSUNG Electronics. Co. LTD'),
(9129, '00:23:C3', 'LogMeIn, Inc.'),
(9130, '00:23:C4', 'Lux Lumen'),
(9131, '00:23:C5', 'Radiation Safety and Control Services Inc'),
(9132, '00:23:C6', 'SMC Corporation'),
(9133, '00:23:C7', 'AVSystem'),
(9134, '00:23:C8', 'TEAM-R'),
(9135, '00:23:C9', 'Sichuan Tianyi Information Science &amp; Technology Stock CO.,LTD'),
(9136, '00:23:CA', 'Behind The Set, LLC'),
(9137, '00:23:CB', 'Shenzhen Full-join Technology Co.,Ltd'),
(9138, '00:23:CC', 'Nintendo Co., Ltd.'),
(9139, '00:23:CD', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(9140, '00:23:CE', 'KITA DENSHI CORPORATION'),
(9141, '00:23:CF', 'CUMMINS-ALLISON CORP.'),
(9142, '00:23:D0', 'Uniloc USA Inc.'),
(9143, '00:23:D1', 'TRG'),
(9144, '00:23:D2', 'Inhand Electronics, Inc.'),
(9145, '00:23:D3', 'AirLink WiFi Networking Corp.'),
(9146, '00:23:D4', 'Texas Instruments'),
(9147, '00:23:D5', 'WAREMA electronic GmbH'),
(9148, '00:23:D6', 'Samsung Electronics Co.,LTD'),
(9149, '00:23:D7', 'Samsung Electronics'),
(9150, '00:23:D8', 'Ball-It Oy'),
(9151, '00:23:D9', 'Banner Engineering'),
(9152, '00:23:DA', 'Industrial Computer Source (Deutschland)GmbH'),
(9153, '00:23:DB', 'saxnet gmbh'),
(9154, '00:23:DC', 'Benein, Inc'),
(9155, '00:23:DD', 'ELGIN S.A.'),
(9156, '00:23:DE', 'Ansync Inc.'),
(9157, '00:23:DF', 'Apple'),
(9158, '00:23:E0', 'INO Therapeutics LLC'),
(9159, '00:23:E1', 'Cavena Image Products AB'),
(9160, '00:23:E2', 'SEA Signalisation'),
(9161, '00:23:E3', 'Microtronic AG'),
(9162, '00:23:E4', 'IPnect co. ltd.'),
(9163, '00:23:E5', 'IPaXiom Networks'),
(9164, '00:23:E6', 'Pirkus, Inc.'),
(9165, '00:23:E7', 'Hinke A/S'),
(9166, '00:23:E8', 'Demco Corp.'),
(9167, '00:23:E9', 'F5 Networks, Inc.'),
(9168, '00:23:EA', 'CISCO SYSTEMS, INC.'),
(9169, '00:23:EB', 'CISCO SYSTEMS, INC.'),
(9170, '00:23:EC', 'Algorithmix GmbH'),
(9171, '00:23:ED', 'ARRIS Group, Inc.'),
(9172, '00:23:EE', 'ARRIS Group, Inc.'),
(9173, '00:23:EF', 'Zuend Systemtechnik AG'),
(9174, '00:23:F0', 'Shanghai Jinghan Weighing Apparatus Co. Ltd.'),
(9175, '00:23:F1', 'Sony Ericsson Mobile Communications'),
(9176, '00:23:F2', 'TVLogic'),
(9177, '00:23:F3', 'Glocom, Inc.'),
(9178, '00:23:F4', 'Masternaut'),
(9179, '00:23:F5', 'WILO SE'),
(9180, '00:23:F6', 'Softwell Technology Co., Ltd.'),
(9181, '00:23:F7', 'PRIVATE'),
(9182, '00:23:F8', 'ZyXEL Communications Corporation'),
(9183, '00:23:F9', 'Double-Take Software, INC.'),
(9184, '00:23:FA', 'RG Nets, Inc.'),
(9185, '00:23:FB', 'IP Datatel, LLC.'),
(9186, '00:23:FC', 'Ultra Stereo Labs, Inc'),
(9187, '00:23:FD', 'AFT Atlas Fahrzeugtechnik GmbH'),
(9188, '00:23:FE', 'Biodevices, SA'),
(9189, '00:23:FF', 'Beijing HTTC Technology Ltd.'),
(9190, '00:24:00', 'Nortel Networks'),
(9191, '00:24:01', 'D-Link Corporation'),
(9192, '00:24:02', 'Op-Tection GmbH'),
(9193, '00:24:03', 'Nokia Danmark A/S'),
(9194, '00:24:04', 'Nokia Danmark A/S'),
(9195, '00:24:05', 'Dilog Nordic AB'),
(9196, '00:24:06', 'Pointmobile'),
(9197, '00:24:07', 'TELEM SAS'),
(9198, '00:24:08', 'Pacific Biosciences'),
(9199, '00:24:09', 'The Toro Company'),
(9200, '00:24:0A', 'US Beverage Net'),
(9201, '00:24:0B', 'Virtual Computer Inc.'),
(9202, '00:24:0C', 'DELEC GmbH'),
(9203, '00:24:0D', 'OnePath Networks LTD.'),
(9204, '00:24:0E', 'Inventec Besta Co., Ltd.'),
(9205, '00:24:0F', 'Ishii Tool &amp; Engineering Corporation'),
(9206, '00:24:10', 'NUETEQ Technology,Inc.'),
(9207, '00:24:11', 'PharmaSmart LLC'),
(9208, '00:24:12', 'Benign Technologies Co, Ltd.'),
(9209, '00:24:13', 'CISCO SYSTEMS, INC.'),
(9210, '00:24:14', 'CISCO SYSTEMS, INC.'),
(9211, '00:24:15', 'Magnetic Autocontrol GmbH'),
(9212, '00:24:16', 'Any Use'),
(9213, '00:24:17', 'Thomson Telecom Belgium'),
(9214, '00:24:18', 'Nextwave Semiconductor'),
(9215, '00:24:19', 'PRIVATE'),
(9216, '00:24:1A', 'Red Beetle Inc.'),
(9217, '00:24:1B', 'iWOW Communications Pte Ltd'),
(9218, '00:24:1C', 'FuGang Electronic (DG) Co.,Ltd'),
(9219, '00:24:1D', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(9220, '00:24:1E', 'Nintendo Co., Ltd.'),
(9221, '00:24:1F', 'DCT-Delta GmbH'),
(9222, '00:24:20', 'NetUP Inc.'),
(9223, '00:24:21', 'MICRO-STAR INT\'L CO., LTD.'),
(9224, '00:24:22', 'Knapp Logistik Automation GmbH'),
(9225, '00:24:23', 'AzureWave Technologies (Shanghai) Inc.'),
(9226, '00:24:24', 'Axis Network Technology'),
(9227, '00:24:25', 'Shenzhenshi chuangzhicheng Technology Co.,Ltd'),
(9228, '00:24:26', 'NOHMI BOSAI LTD.'),
(9229, '00:24:27', 'SSI COMPUTER CORP'),
(9230, '00:24:28', 'EnergyICT'),
(9231, '00:24:29', 'MK MASTER INC.'),
(9232, '00:24:2A', 'Hittite Microwave Corporation'),
(9233, '00:24:2B', 'Hon Hai Precision Ind.Co.,Ltd.'),
(9234, '00:24:2C', 'Hon Hai Precision Ind. Co., Ltd.'),
(9235, '00:24:2E', 'Datastrip Inc.'),
(9236, '00:24:2F', 'Micron'),
(9237, '00:24:30', 'Ruby Tech Corp.'),
(9238, '00:24:31', 'Uni-v co.,ltd'),
(9239, '00:24:32', 'Neostar Technology Co.,LTD'),
(9240, '00:24:33', 'Alps Electric Co., Ltd'),
(9241, '00:24:34', 'Lectrosonics, Inc.'),
(9242, '00:24:35', 'WIDE CORPORATION'),
(9243, '00:24:36', 'Apple'),
(9244, '00:24:37', 'Motorola - BSG'),
(9245, '00:24:38', 'Brocade Communications Systems, Inc'),
(9246, '00:24:39', 'Digital Barriers Advanced Technologies'),
(9247, '00:24:3A', 'Ludl Electronic Products'),
(9248, '00:24:3B', 'CSSI (S) Pte Ltd'),
(9249, '00:24:3C', 'S.A.A.A.'),
(9250, '00:24:3D', 'Emerson Appliance Motors and Controls'),
(9251, '00:24:3F', 'Storwize, Inc.'),
(9252, '00:24:40', 'Halo Monitoring, Inc.'),
(9253, '00:24:41', 'Wanzl Metallwarenfabrik GmbH'),
(9254, '00:24:42', 'Axona Limited'),
(9255, '00:24:43', 'Nortel Networks'),
(9256, '00:24:44', 'Nintendo Co., Ltd.'),
(9257, '00:24:45', 'CommScope Canada Inc.'),
(9258, '00:24:46', 'MMB Research Inc.'),
(9259, '00:24:47', 'Kaztek Systems'),
(9260, '00:24:48', 'SpiderCloud Wireless, Inc'),
(9261, '00:24:49', 'Shen Zhen Lite Star Electronics Technology Co., Ltd'),
(9262, '00:24:4A', 'Voyant International'),
(9263, '00:24:4B', 'PERCEPTRON INC'),
(9264, '00:24:4C', 'Solartron Metrology Ltd'),
(9265, '00:24:4D', 'Hokkaido Electronics Corporation'),
(9266, '00:24:4E', 'RadChips, Inc.'),
(9267, '00:24:4F', 'Asantron Technologies Ltd.'),
(9268, '00:24:50', 'CISCO SYSTEMS, INC.'),
(9269, '00:24:51', 'CISCO SYSTEMS, INC.'),
(9270, '00:24:52', 'Silicon Software GmbH'),
(9271, '00:24:53', 'Initra d.o.o.'),
(9272, '00:24:54', 'Samsung Electronics CO., LTD'),
(9273, '00:24:55', 'MuLogic BV'),
(9274, '00:24:56', '2Wire'),
(9275, '00:24:58', 'PA Bastion CC'),
(9276, '00:24:59', 'ABB STOTZ-KONTAKT GmbH'),
(9277, '00:24:5A', 'Nanjing Panda Electronics Company Limited'),
(9278, '00:24:5B', 'RAIDON TECHNOLOGY, INC.'),
(9279, '00:24:5C', 'Design-Com Technologies Pty. Ltd.'),
(9280, '00:24:5D', 'Terberg besturingstechniek B.V.'),
(9281, '00:24:5E', 'Hivision Co.,ltd'),
(9282, '00:24:5F', 'Vine Telecom CO.,Ltd.'),
(9283, '00:24:60', 'Giaval Science Development Co. Ltd.'),
(9284, '00:24:61', 'Shin Wang Tech.'),
(9285, '00:24:62', 'Rayzone Corporation'),
(9286, '00:24:63', 'Phybridge Inc'),
(9287, '00:24:64', 'Bridge Technologies Co AS'),
(9288, '00:24:65', 'Elentec'),
(9289, '00:24:66', 'Unitron nv'),
(9290, '00:24:67', 'AOC International (Europe) GmbH'),
(9291, '00:24:68', 'Sumavision Technologies Co.,Ltd'),
(9292, '00:24:69', 'Smart Doorphones'),
(9293, '00:24:6A', 'Solid Year Co., Ltd.'),
(9294, '00:24:6B', 'Covia, Inc.'),
(9295, '00:24:6C', 'ARUBA NETWORKS, INC.'),
(9296, '00:24:6D', 'Weinzierl Engineering GmbH'),
(9297, '00:24:6E', 'Phihong USA Corp.'),
(9298, '00:24:6F', 'Onda Communication spa'),
(9299, '00:24:70', 'AUROTECH ultrasound AS.'),
(9300, '00:24:71', 'Fusion MultiSystems dba Fusion-io'),
(9301, '00:24:72', 'ReDriven Power Inc.'),
(9302, '00:24:73', '3Com Europe Ltd'),
(9303, '00:24:74', 'Autronica Fire And Securirty'),
(9304, '00:24:75', 'Compass System(Embedded Dept.)'),
(9305, '00:24:76', 'TAP.tv'),
(9306, '00:24:77', 'Tibbo Technology'),
(9307, '00:24:78', 'Mag Tech Electronics Co Limited'),
(9308, '00:24:79', 'Optec Displays, Inc.'),
(9309, '00:24:7A', 'FU YI CHENG Technology Co., Ltd.'),
(9310, '00:24:7B', 'Actiontec Electronics, Inc'),
(9311, '00:24:7C', 'Nokia Danmark A/S'),
(9312, '00:24:7D', 'Nokia Danmark A/S'),
(9313, '00:24:7E', 'Universal Global Scientific Industrial Co., Ltd'),
(9314, '00:24:7F', 'Nortel Networks'),
(9315, '00:24:80', 'Meteocontrol GmbH'),
(9316, '00:24:81', 'Hewlett-Packard Company'),
(9317, '00:24:82', 'Ruckus Wireless'),
(9318, '00:24:83', 'LG Electronics'),
(9319, '00:24:84', 'Bang and Olufsen Medicom a/s'),
(9320, '00:24:85', 'ConteXtream Ltd'),
(9321, '00:24:86', 'DesignArt Networks'),
(9322, '00:24:87', 'Blackboard Inc.'),
(9323, '00:24:88', 'Centre For Development Of Telematics'),
(9324, '00:24:89', 'Vodafone Omnitel N.V.'),
(9325, '00:24:8A', 'Kaga Electronics Co., Ltd.'),
(9326, '00:24:8B', 'HYBUS CO., LTD.'),
(9327, '00:24:8C', 'ASUSTek COMPUTER INC.'),
(9328, '00:24:8D', 'Sony Computer Entertainment Inc.'),
(9329, '00:24:8E', 'Infoware ZRt.'),
(9330, '00:24:8F', 'DO-MONIX'),
(9331, '00:24:90', 'Samsung Electronics Co.,LTD'),
(9332, '00:24:91', 'Samsung Electronics'),
(9333, '00:24:92', 'Motorola, Broadband Solutions Group'),
(9334, '00:24:93', 'ARRIS Group, Inc.'),
(9335, '00:24:94', 'Shenzhen Baoxin Tech CO., Ltd.'),
(9336, '00:24:95', 'ARRIS Group, Inc.'),
(9337, '00:24:96', 'Ginzinger electronic systems'),
(9338, '00:24:97', 'CISCO SYSTEMS, INC.'),
(9339, '00:24:98', 'CISCO SYSTEMS, INC.'),
(9340, '00:24:99', 'Aquila Technologies'),
(9341, '00:24:9A', 'Beijing Zhongchuang Telecommunication Test Co., Ltd.'),
(9342, '00:24:9B', 'Action Star Enterprise Co., Ltd.'),
(9343, '00:24:9C', 'Bimeng Comunication System Co. Ltd'),
(9344, '00:24:9D', 'NES Technology Inc.'),
(9345, '00:24:9E', 'ADC-Elektronik GmbH'),
(9346, '00:24:9F', 'RIM Testing Services'),
(9347, '00:24:A0', 'ARRIS Group, Inc.'),
(9348, '00:24:A1', 'ARRIS Group, Inc.'),
(9349, '00:24:A2', 'Hong Kong Middleware Technology Limited'),
(9350, '00:24:A3', 'Sonim Technologies Inc'),
(9351, '00:24:A4', 'Siklu Communication'),
(9352, '00:24:A5', 'Buffalo Inc.'),
(9353, '00:24:A6', 'TELESTAR DIGITAL GmbH'),
(9354, '00:24:A7', 'Advanced Video Communications Inc.'),
(9355, '00:24:A8', 'ProCurve Networking by HP'),
(9356, '00:24:A9', 'Ag Leader Technology'),
(9357, '00:24:AA', 'Dycor Technologies Ltd.'),
(9358, '00:24:AB', 'A7 Engineering, Inc.'),
(9359, '00:24:AC', 'Hangzhou DPtech Technologies Co., Ltd.'),
(9360, '00:24:AD', 'Adolf Thies Gmbh &amp; Co. KG'),
(9361, '00:24:AE', 'Morpho'),
(9362, '00:24:AF', 'EchoStar Technologies'),
(9363, '00:24:B0', 'ESAB AB'),
(9364, '00:24:B1', 'Coulomb Technologies'),
(9365, '00:24:B2', 'Netgear'),
(9366, '00:24:B3', 'Graf-Syteco GmbH &amp; Co. KG'),
(9367, '00:24:B4', 'ESCATRONIC GmbH'),
(9368, '00:24:B5', 'Nortel Networks'),
(9369, '00:24:B6', 'Seagate Technology'),
(9370, '00:24:B7', 'GridPoint, Inc.'),
(9371, '00:24:B8', 'free alliance sdn bhd'),
(9372, '00:24:B9', 'Wuhan Higheasy Electronic Technology Development Co.Ltd'),
(9373, '00:24:BA', 'Texas Instruments'),
(9374, '00:24:BB', 'CENTRAL Corporation'),
(9375, '00:24:BC', 'HuRob Co.,Ltd'),
(9376, '00:24:BD', 'Hainzl Industriesysteme GmbH'),
(9377, '00:24:BE', 'Sony Corporation'),
(9378, '00:24:BF', 'CIAT'),
(9379, '00:24:C0', 'NTI COMODO INC'),
(9380, '00:24:C1', 'ARRIS Group, Inc.'),
(9381, '00:24:C2', 'Asumo Co.,Ltd.'),
(9382, '00:24:C3', 'CISCO SYSTEMS, INC.'),
(9383, '00:24:C4', 'CISCO SYSTEMS, INC.'),
(9384, '00:24:C5', 'Meridian Audio Limited'),
(9385, '00:24:C6', 'Hager Electro SAS'),
(9386, '00:24:C7', 'Mobilarm Ltd'),
(9387, '00:24:C8', 'Broadband Solutions Group'),
(9388, '00:24:C9', 'Broadband Solutions Group'),
(9389, '00:24:CA', 'Tobii Technology AB'),
(9390, '00:24:CB', 'Autonet Mobile'),
(9391, '00:24:CC', 'Fascinations Toys and Gifts, Inc.'),
(9392, '00:24:CD', 'Willow Garage, Inc.'),
(9393, '00:24:CE', 'Exeltech Inc'),
(9394, '00:24:CF', 'Inscape Data Corporation'),
(9395, '00:24:D0', 'Shenzhen SOGOOD Industry CO.,LTD.'),
(9396, '00:24:D1', 'Thomson Inc.'),
(9397, '00:24:D2', 'Askey Computer'),
(9398, '00:24:D3', 'QUALICA Inc.'),
(9399, '00:24:D4', 'FREEBOX SA'),
(9400, '00:24:D5', 'Winward Industrial Limited'),
(9401, '00:24:D6', 'Intel Corporate'),
(9402, '00:24:D7', 'Intel Corporate'),
(9403, '00:24:D8', 'IlSung Precision'),
(9404, '00:24:D9', 'BICOM, Inc.'),
(9405, '00:24:DA', 'Innovar Systems Limited'),
(9406, '00:24:DB', 'Alcohol Monitoring Systems'),
(9407, '00:24:DC', 'Juniper Networks'),
(9408, '00:24:DD', 'Centrak, Inc.'),
(9409, '00:24:DE', 'GLOBAL Technology Inc.'),
(9410, '00:24:DF', 'Digitalbox Europe GmbH'),
(9411, '00:24:E0', 'DS Tech, LLC'),
(9412, '00:24:E1', 'Convey Computer Corp.'),
(9413, '00:24:E2', 'HASEGAWA ELECTRIC CO.,LTD.'),
(9414, '00:24:E3', 'CAO Group'),
(9415, '00:24:E4', 'Withings'),
(9416, '00:24:E5', 'Seer Technology, Inc'),
(9417, '00:24:E6', 'In Motion Technology Inc.'),
(9418, '00:24:E7', 'Plaster Networks'),
(9419, '00:24:E8', 'Dell Inc.'),
(9420, '00:24:E9', 'Samsung Electronics Co., Ltd., Storage System Division'),
(9421, '00:24:EA', 'iris-GmbH infrared &amp; intelligent sensors'),
(9422, '00:24:EB', 'ClearPath Networks, Inc.'),
(9423, '00:24:EC', 'United Information Technology Co.,Ltd.'),
(9424, '00:24:ED', 'YT Elec. Co,.Ltd.'),
(9425, '00:24:EE', 'Wynmax Inc.'),
(9426, '00:24:EF', 'Sony Ericsson Mobile Communications'),
(9427, '00:24:F0', 'Seanodes'),
(9428, '00:24:F1', 'Shenzhen Fanhai Sanjiang Electronics Co., Ltd.'),
(9429, '00:24:F2', 'Uniphone Telecommunication Co., Ltd.'),
(9430, '00:24:F3', 'Nintendo Co., Ltd.'),
(9431, '00:24:F4', 'Kaminario Technologies Ltd.'),
(9432, '00:24:F5', 'NDS Surgical Imaging'),
(9433, '00:24:F6', 'MIYOSHI ELECTRONICS CORPORATION'),
(9434, '00:24:F7', 'CISCO SYSTEMS, INC.'),
(9435, '00:24:F8', 'Technical Solutions Company Ltd.'),
(9436, '00:24:F9', 'CISCO SYSTEMS, INC.'),
(9437, '00:24:FA', 'Hilger u. Kern GMBH'),
(9438, '00:24:FB', 'PRIVATE'),
(9439, '00:24:FC', 'QuoPin Co., Ltd.'),
(9440, '00:24:FD', 'Accedian Networks Inc'),
(9441, '00:24:FE', 'AVM GmbH'),
(9442, '00:24:FF', 'QLogic Corporation'),
(9443, '00:25:00', 'Apple'),
(9444, '00:25:01', 'JSC &quot;Supertel&quot;'),
(9445, '00:25:02', 'NaturalPoint'),
(9446, '00:25:03', 'IBM Corp'),
(9447, '00:25:04', 'Valiant Communications Limited'),
(9448, '00:25:05', 'eks Engel GmbH &amp; Co. KG'),
(9449, '00:25:06', 'A.I. ANTITACCHEGGIO ITALIA SRL'),
(9450, '00:25:07', 'ASTAK Inc.'),
(9451, '00:25:08', 'Maquet Cardiopulmonary AG'),
(9452, '00:25:09', 'SHARETRONIC Group LTD'),
(9453, '00:25:0A', 'Security Expert Co. Ltd'),
(9454, '00:25:0B', 'CENTROFACTOR  INC'),
(9455, '00:25:0C', 'Enertrac'),
(9456, '00:25:0D', 'GZT Telkom-Telmor sp. z o.o.'),
(9457, '00:25:0E', 'gt german telematics gmbh'),
(9458, '00:25:0F', 'On-Ramp Wireless, Inc.'),
(9459, '00:25:10', 'Pico-Tesla Magnetic Therapies'),
(9460, '00:25:11', 'ELITEGROUP COMPUTER SYSTEM CO., LTD.'),
(9461, '00:25:12', 'ZTE Corporation'),
(9462, '00:25:13', 'CXP DIGITAL BV'),
(9463, '00:25:14', 'PC Worth Int\'l Co., Ltd.'),
(9464, '00:25:15', 'SFR'),
(9465, '00:25:16', 'Integrated Design Tools, Inc.'),
(9466, '00:25:17', 'Venntis, LLC'),
(9467, '00:25:18', 'Power PLUS Communications AG'),
(9468, '00:25:19', 'Viaas Inc'),
(9469, '00:25:1A', 'Psiber Data Systems Inc.'),
(9470, '00:25:1B', 'Philips CareServant'),
(9471, '00:25:1C', 'EDT'),
(9472, '00:25:1D', 'DSA Encore, LLC'),
(9473, '00:25:1E', 'ROTEL TECHNOLOGIES'),
(9474, '00:25:1F', 'ZYNUS VISION INC.'),
(9475, '00:25:20', 'SMA Railway Technology GmbH'),
(9476, '00:25:21', 'Logitek Electronic Systems, Inc.'),
(9477, '00:25:22', 'ASRock Incorporation'),
(9478, '00:25:23', 'OCP Inc.'),
(9479, '00:25:24', 'Lightcomm Technology Co., Ltd'),
(9480, '00:25:25', 'CTERA Networks Ltd.'),
(9481, '00:25:26', 'Genuine Technologies Co., Ltd.'),
(9482, '00:25:27', 'Bitrode Corp.'),
(9483, '00:25:28', 'Daido Signal Co., Ltd.'),
(9484, '00:25:29', 'COMELIT GROUP S.P.A'),
(9485, '00:25:2A', 'Chengdu GeeYa Technology Co.,LTD'),
(9486, '00:25:2B', 'Stirling Energy Systems'),
(9487, '00:25:2C', 'Entourage Systems, Inc.'),
(9488, '00:25:2D', 'Kiryung Electronics'),
(9489, '00:25:2E', 'Cisco SPVTG'),
(9490, '00:25:2F', 'Energy, Inc.'),
(9491, '00:25:30', 'Aetas Systems Inc.'),
(9492, '00:25:31', 'Cloud Engines, Inc.'),
(9493, '00:25:32', 'Digital Recorders'),
(9494, '00:25:33', 'WITTENSTEIN AG'),
(9495, '00:25:35', 'Minimax GmbH &amp; Co KG'),
(9496, '00:25:36', 'Oki Electric Industry Co., Ltd.'),
(9497, '00:25:37', 'Runcom Technologies Ltd.'),
(9498, '00:25:38', 'Samsung Electronics Co., Ltd., Memory Division'),
(9499, '00:25:39', 'IfTA GmbH'),
(9500, '00:25:3A', 'CEVA, Ltd.'),
(9501, '00:25:3B', 'din Dietmar Nocker Facilitymanagement GmbH'),
(9502, '00:25:3C', '2Wire'),
(9503, '00:25:3D', 'DRS Consolidated Controls'),
(9504, '00:25:3E', 'Sensus Metering Systems'),
(9505, '00:25:40', 'Quasar Technologies, Inc.'),
(9506, '00:25:41', 'Maquet Critical Care AB'),
(9507, '00:25:42', 'Pittasoft'),
(9508, '00:25:43', 'MONEYTECH'),
(9509, '00:25:44', 'LoJack Corporation'),
(9510, '00:25:45', 'CISCO SYSTEMS, INC.'),
(9511, '00:25:46', 'CISCO SYSTEMS, INC.'),
(9512, '00:25:47', 'Nokia Danmark A/S'),
(9513, '00:25:48', 'Nokia Danmark A/S'),
(9514, '00:25:49', 'Jeorich Tech. Co.,Ltd.'),
(9515, '00:25:4A', 'RingCube Technologies, Inc.'),
(9516, '00:25:4B', 'Apple'),
(9517, '00:25:4C', 'Videon Central, Inc.'),
(9518, '00:25:4D', 'Singapore Technologies Electronics Limited'),
(9519, '00:25:4E', 'Vertex Wireless Co., Ltd.'),
(9520, '00:25:4F', 'ELETTROLAB Srl'),
(9521, '00:25:50', 'Riverbed Technology'),
(9522, '00:25:51', 'SE-Elektronic GmbH'),
(9523, '00:25:52', 'VXI CORPORATION'),
(9524, '00:25:53', 'Pirelli Tyre S.p.A.'),
(9525, '00:25:54', 'Pixel8 Networks'),
(9526, '00:25:55', 'Visonic Technologies 1993 Ltd'),
(9527, '00:25:56', 'Hon Hai Precision Ind. Co., Ltd.'),
(9528, '00:25:57', 'Research In Motion'),
(9529, '00:25:58', 'MPEDIA'),
(9530, '00:25:59', 'Syphan Technologies Ltd'),
(9531, '00:25:5A', 'Tantalus Systems Corp.'),
(9532, '00:25:5B', 'CoachComm, LLC'),
(9533, '00:25:5C', 'NEC Corporation'),
(9534, '00:25:5D', 'Morningstar Corporation'),
(9535, '00:25:5E', 'Shanghai Dare Technologies Co.,Ltd.'),
(9536, '00:25:5F', 'SenTec AG'),
(9537, '00:25:60', 'Ibridge Networks &amp; Communications Ltd.'),
(9538, '00:25:61', 'ProCurve Networking by HP'),
(9539, '00:25:62', 'interbro Co. Ltd.'),
(9540, '00:25:63', 'Luxtera Inc'),
(9541, '00:25:64', 'Dell Inc.'),
(9542, '00:25:65', 'Vizimax Inc.'),
(9543, '00:25:66', 'Samsung Electronics Co.,Ltd'),
(9544, '00:25:67', 'Samsung Electronics'),
(9545, '00:25:68', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(9546, '00:25:69', 'SAGEM COMMUNICATION'),
(9547, '00:25:6A', 'inIT - Institut Industrial IT'),
(9548, '00:25:6B', 'ATENIX E.E. s.r.l.'),
(9549, '00:25:6C', '&quot;Azimut&quot; Production Association JSC'),
(9550, '00:25:6D', 'Broadband Forum'),
(9551, '00:25:6E', 'Van Breda B.V.'),
(9552, '00:25:6F', 'Dantherm Power'),
(9553, '00:25:70', 'Eastern Communications Company Limited'),
(9554, '00:25:71', 'Zhejiang Tianle Digital Electric Co.,Ltd'),
(9555, '00:25:72', 'Nemo-Q International AB'),
(9556, '00:25:73', 'ST Electronics (Info-Security) Pte Ltd'),
(9557, '00:25:74', 'KUNIMI MEDIA DEVICE Co., Ltd.'),
(9558, '00:25:75', 'FiberPlex Technologies, LLC'),
(9559, '00:25:76', 'NELI TECHNOLOGIES'),
(9560, '00:25:77', 'D-BOX Technologies'),
(9561, '00:25:78', 'JSC &quot;Concern &quot;Sozvezdie&quot;'),
(9562, '00:25:79', 'J &amp; F Labs'),
(9563, '00:25:7A', 'CAMCO Produktions- und Vertriebs-GmbH f&uuml;r  Beschallungs- und Beleuchtungsanlagen'),
(9564, '00:25:7B', 'STJ  ELECTRONICS  PVT  LTD'),
(9565, '00:25:7C', 'Huachentel Technology Development Co., Ltd'),
(9566, '00:25:7D', 'PointRed Telecom Private Ltd.'),
(9567, '00:25:7E', 'NEW POS Technology Limited'),
(9568, '00:25:7F', 'CallTechSolution Co.,Ltd'),
(9569, '00:25:80', 'Equipson S.A.'),
(9570, '00:25:81', 'x-star networks Inc.'),
(9571, '00:25:82', 'Maksat Technologies (P) Ltd'),
(9572, '00:25:83', 'CISCO SYSTEMS, INC.'),
(9573, '00:25:84', 'CISCO SYSTEMS, INC.'),
(9574, '00:25:85', 'KOKUYO S&amp;T Co., Ltd.'),
(9575, '00:25:86', 'TP-LINK Technologies Co., Ltd.'),
(9576, '00:25:87', 'Vitality, Inc.'),
(9577, '00:25:88', 'Genie Industries, Inc.'),
(9578, '00:25:89', 'Hills Industries Limited'),
(9579, '00:25:8A', 'Pole/Zero Corporation'),
(9580, '00:25:8B', 'Mellanox Technologies Ltd'),
(9581, '00:25:8C', 'ESUS ELEKTRONIK SAN. VE DIS. TIC. LTD. STI.'),
(9582, '00:25:8D', 'Haier'),
(9583, '00:25:8E', 'The Weather Channel'),
(9584, '00:25:8F', 'Trident Microsystems, Inc.'),
(9585, '00:25:90', 'Super Micro Computer, Inc.'),
(9586, '00:25:91', 'NEXTEK, Inc.'),
(9587, '00:25:92', 'Guangzhou Shirui Electronic Co., Ltd'),
(9588, '00:25:93', 'DatNet Informatikai Kft.'),
(9589, '00:25:94', 'Eurodesign BG LTD'),
(9590, '00:25:95', 'Northwest Signal Supply, Inc'),
(9591, '00:25:96', 'GIGAVISION srl'),
(9592, '00:25:97', 'Kalki Communication Technologies'),
(9593, '00:25:98', 'Zhong Shan City Litai Electronic Industrial Co. Ltd'),
(9594, '00:25:99', 'Hedon e.d. B.V.'),
(9595, '00:25:9A', 'CEStronics GmbH'),
(9596, '00:25:9B', 'Beijing PKUNITY Microsystems Technology Co., Ltd'),
(9597, '00:25:9C', 'Cisco-Linksys, LLC'),
(9598, '00:25:9D', 'PRIVATE'),
(9599, '00:25:9E', 'Huawei Technologies Co., Ltd.'),
(9600, '00:25:9F', 'TechnoDigital Technologies GmbH'),
(9601, '00:25:A0', 'Nintendo Co., Ltd.'),
(9602, '00:25:A1', 'Enalasys'),
(9603, '00:25:A2', 'Alta Definicion LINCEO S.L.'),
(9604, '00:25:A3', 'Trimax Wireless, Inc.'),
(9605, '00:25:A4', 'EuroDesign embedded technologies GmbH'),
(9606, '00:25:A5', 'Walnut Media Network'),
(9607, '00:25:A6', 'Central Network Solution Co., Ltd.'),
(9608, '00:25:A7', 'Comverge, Inc.'),
(9609, '00:25:A8', 'Kontron (BeiJing) Technology Co.,Ltd'),
(9610, '00:25:A9', 'Shanghai Embedway Information Technologies Co.,Ltd'),
(9611, '00:25:AA', 'Beijing Soul Technology Co.,Ltd.'),
(9612, '00:25:AB', 'AIO LCD PC BU / TPV'),
(9613, '00:25:AC', 'I-Tech corporation'),
(9614, '00:25:AD', 'Manufacturing Resources International'),
(9615, '00:25:AE', 'Microsoft Corporation'),
(9616, '00:25:AF', 'COMFILE Technology'),
(9617, '00:25:B0', 'Schmartz Inc'),
(9618, '00:25:B1', 'Maya-Creation Corporation'),
(9619, '00:25:B2', 'MBDA Deutschland GmbH'),
(9620, '00:25:B3', 'Hewlett-Packard Company'),
(9621, '00:25:B4', 'CISCO SYSTEMS, INC.'),
(9622, '00:25:B5', 'CISCO SYSTEMS, INC.'),
(9623, '00:25:B6', 'Telecom FM'),
(9624, '00:25:B7', 'Costar  electronics, inc.,'),
(9625, '00:25:B8', 'Agile Communications, Inc.'),
(9626, '00:25:B9', 'Cypress Solutions Inc'),
(9627, '00:25:BA', 'Alcatel-Lucent IPD'),
(9628, '00:25:BB', 'INNERINT Co., Ltd.'),
(9629, '00:25:BC', 'Apple'),
(9630, '00:25:BD', 'Italdata Ingegneria dell\'Idea S.p.A.'),
(9631, '00:25:BE', 'Tektrap Systems Inc.'),
(9632, '00:25:BF', 'Wireless Cables Inc.'),
(9633, '00:25:C0', 'ZillionTV Corporation'),
(9634, '00:25:C1', 'Nawoo Korea Corp.'),
(9635, '00:25:C2', 'RingBell Co.,Ltd.'),
(9636, '00:25:C3', 'Nortel Networks'),
(9637, '00:25:C4', 'Ruckus Wireless'),
(9638, '00:25:C5', 'Star Link Communication Pvt. Ltd.'),
(9639, '00:25:C6', 'kasercorp, ltd'),
(9640, '00:25:C7', 'altek Corporation'),
(9641, '00:25:C8', 'S-Access GmbH'),
(9642, '00:25:C9', 'SHENZHEN HUAPU DIGITAL CO., LTD'),
(9643, '00:25:CA', 'LS Research, LLC'),
(9644, '00:25:CB', 'Reiner SCT'),
(9645, '00:25:CC', 'Mobile Communications Korea Incorporated'),
(9646, '00:25:CD', 'Skylane Optics'),
(9647, '00:25:CE', 'InnerSpace'),
(9648, '00:25:CF', 'Nokia Danmark A/S'),
(9649, '00:25:D0', 'Nokia Danmark A/S'),
(9650, '00:25:D1', 'Eastern Asia Technology Limited'),
(9651, '00:25:D2', 'InpegVision Co., Ltd'),
(9652, '00:25:D3', 'AzureWave Technologies, Inc'),
(9653, '00:25:D4', 'Fortress Technologies'),
(9654, '00:25:D5', 'Robonica (Pty) Ltd'),
(9655, '00:25:D6', 'The Kroger Co.'),
(9656, '00:25:D7', 'CEDO'),
(9657, '00:25:D8', 'KOREA MAINTENANCE'),
(9658, '00:25:D9', 'DataFab Systems Inc.'),
(9659, '00:25:DA', 'Secura Key'),
(9660, '00:25:DB', 'ATI Electronics(Shenzhen) Co., LTD'),
(9661, '00:25:DC', 'Sumitomo Electric Networks, Inc'),
(9662, '00:25:DD', 'SUNNYTEK INFORMATION CO., LTD.'),
(9663, '00:25:DE', 'Probits Co., LTD.'),
(9664, '00:25:DF', 'PRIVATE'),
(9665, '00:25:E0', 'CeedTec Sdn Bhd'),
(9666, '00:25:E1', 'SHANGHAI SEEYOO ELECTRONIC &amp; TECHNOLOGY CO., LTD'),
(9667, '00:25:E2', 'Everspring Industry Co., Ltd.'),
(9668, '00:25:E3', 'Hanshinit Inc.'),
(9669, '00:25:E4', 'OMNI-WiFi, LLC'),
(9670, '00:25:E5', 'LG Electronics Inc'),
(9671, '00:25:E6', 'Belgian Monitoring Systems bvba'),
(9672, '00:25:E7', 'Sony Ericsson Mobile Communications'),
(9673, '00:25:E8', 'Idaho Technology'),
(9674, '00:25:E9', 'i-mate Development, Inc.'),
(9675, '00:25:EA', 'Iphion BV'),
(9676, '00:25:EB', 'Reutech Radar Systems (PTY) Ltd'),
(9677, '00:25:EC', 'Humanware'),
(9678, '00:25:ED', 'NuVo Technologies LLC'),
(9679, '00:25:EE', 'Avtex Ltd'),
(9680, '00:25:EF', 'I-TEC Co., Ltd.'),
(9681, '00:25:F0', 'Suga Electronics Limited'),
(9682, '00:25:F1', 'ARRIS Group, Inc.'),
(9683, '00:25:F2', 'ARRIS Group, Inc.'),
(9684, '00:25:F3', 'Nordwestdeutsche Z&auml;hlerrevision'),
(9685, '00:25:F4', 'KoCo Connector AG'),
(9686, '00:25:F5', 'DVS Korea, Co., Ltd'),
(9687, '00:25:F6', 'netTALK.com, Inc.'),
(9688, '00:25:F7', 'Ansaldo STS USA'),
(9689, '00:25:F9', 'GMK electronic design GmbH'),
(9690, '00:25:FA', 'J&amp;M Analytik AG'),
(9691, '00:25:FB', 'Tunstall Healthcare A/S'),
(9692, '00:25:FC', 'ENDA ENDUSTRIYEL ELEKTRONIK LTD. STI.'),
(9693, '00:25:FD', 'OBR Centrum Techniki Morskiej S.A.'),
(9694, '00:25:FE', 'Pilot Electronics Corporation'),
(9695, '00:25:FF', 'CreNova Multimedia Co., Ltd'),
(9696, '00:26:00', 'TEAC Australia Pty Ltd.'),
(9697, '00:26:01', 'Cutera Inc'),
(9698, '00:26:02', 'SMART Temps LLC'),
(9699, '00:26:03', 'Shenzhen Wistar Technology Co., Ltd'),
(9700, '00:26:04', 'Audio Processing Technology Ltd'),
(9701, '00:26:05', 'CC Systems AB'),
(9702, '00:26:06', 'RAUMFELD GmbH'),
(9703, '00:26:07', 'Enabling Technology Pty Ltd'),
(9704, '00:26:08', 'Apple'),
(9705, '00:26:09', 'Phyllis Co., Ltd.'),
(9706, '00:26:0A', 'CISCO SYSTEMS, INC.'),
(9707, '00:26:0B', 'CISCO SYSTEMS, INC.'),
(9708, '00:26:0C', 'Dataram'),
(9709, '00:26:0D', 'Mercury Systems, Inc.'),
(9710, '00:26:0E', 'Ablaze Systems, LLC'),
(9711, '00:26:0F', 'Linn Products Ltd'),
(9712, '00:26:10', 'Apacewave Technologies'),
(9713, '00:26:11', 'Licera AB'),
(9714, '00:26:12', 'Space Exploration Technologies'),
(9715, '00:26:13', 'Engel Axil S.L.'),
(9716, '00:26:14', 'KTNF'),
(9717, '00:26:15', 'Teracom Limited'),
(9718, '00:26:16', 'Rosemount Inc.'),
(9719, '00:26:17', 'OEM Worldwide'),
(9720, '00:26:18', 'ASUSTek COMPUTER INC.'),
(9721, '00:26:19', 'FRC'),
(9722, '00:26:1A', 'Femtocomm System Technology Corp.'),
(9723, '00:26:1B', 'LAUREL BANK MACHINES CO., LTD.'),
(9724, '00:26:1C', 'NEOVIA INC.'),
(9725, '00:26:1D', 'COP SECURITY SYSTEM CORP.'),
(9726, '00:26:1E', 'QINGBANG ELEC(SZ) CO., LTD'),
(9727, '00:26:1F', 'SAE Magnetics (H.K.) Ltd.'),
(9728, '00:26:20', 'ISGUS GmbH'),
(9729, '00:26:21', 'InteliCloud Technology Inc.'),
(9730, '00:26:22', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(9731, '00:26:23', 'JRD Communication Inc'),
(9732, '00:26:24', 'Thomson Inc.'),
(9733, '00:26:25', 'MediaSputnik'),
(9734, '00:26:26', 'Geophysical Survey Systems, Inc.'),
(9735, '00:26:27', 'Truesell'),
(9736, '00:26:28', 'companytec automa&ccedil;&atilde;o e controle ltda.'),
(9737, '00:26:29', 'Juphoon System Software Inc.'),
(9738, '00:26:2A', 'Proxense, LLC'),
(9739, '00:26:2B', 'Wongs Electronics Co. Ltd.'),
(9740, '00:26:2C', 'IKT Advanced Technologies s.r.o.'),
(9741, '00:26:2D', 'Wistron Corporation'),
(9742, '00:26:2E', 'Chengdu Jiuzhou Electronic Technology Inc'),
(9743, '00:26:2F', 'HAMAMATSU TOA ELECTRONICS'),
(9744, '00:26:30', 'ACOREL S.A.S'),
(9745, '00:26:31', 'COMMTACT LTD'),
(9746, '00:26:32', 'Instrumentation Technologies d.d.'),
(9747, '00:26:33', 'MIR - Medical International Research'),
(9748, '00:26:34', 'Infineta Systems, Inc'),
(9749, '00:26:35', 'Bluetechnix GmbH'),
(9750, '00:26:36', 'ARRIS Group, Inc.'),
(9751, '00:26:37', 'Samsung Electro-Mechanics'),
(9752, '00:26:38', 'Xia Men Joyatech Co., Ltd.'),
(9753, '00:26:39', 'T.M. Electronics, Inc.'),
(9754, '00:26:3A', 'Digitec Systems'),
(9755, '00:26:3B', 'Onbnetech'),
(9756, '00:26:3C', 'Bachmann Technology GmbH &amp; Co. KG'),
(9757, '00:26:3D', 'MIA Corporation'),
(9758, '00:26:3E', 'Trapeze Networks'),
(9759, '00:26:3F', 'LIOS Technology GmbH'),
(9760, '00:26:40', 'Baustem Broadband Technologies, Ltd.'),
(9761, '00:26:41', 'ARRIS Group, Inc.'),
(9762, '00:26:42', 'ARRIS Group, Inc.'),
(9763, '00:26:43', 'Alps Electric Co., Ltd'),
(9764, '00:26:44', 'Thomson Telecom Belgium'),
(9765, '00:26:45', 'Circontrol S.A.'),
(9766, '00:26:46', 'SHENYANG TONGFANG MULTIMEDIA TECHNOLOGY COMPANY LIMITED'),
(9767, '00:26:47', 'WFE TECHNOLOGY CORP.'),
(9768, '00:26:48', 'Emitech Corp.'),
(9769, '00:26:4A', 'Apple'),
(9770, '00:26:4C', 'Shanghai DigiVision Technology Co., Ltd.'),
(9771, '00:26:4D', 'Arcadyan Technology Corporation'),
(9772, '00:26:4E', 'Rail &amp; Road Protec GmbH'),
(9773, '00:26:4F', 'Kr&uuml;ger &amp;Gothe GmbH'),
(9774, '00:26:50', '2Wire'),
(9775, '00:26:51', 'CISCO SYSTEMS, INC.'),
(9776, '00:26:52', 'CISCO SYSTEMS, INC.'),
(9777, '00:26:53', 'DaySequerra Corporation'),
(9778, '00:26:54', '3Com Corporation'),
(9779, '00:26:55', 'Hewlett-Packard Company'),
(9780, '00:26:56', 'Sansonic Electronics USA'),
(9781, '00:26:57', 'OOO NPP EKRA'),
(9782, '00:26:58', 'T-Platforms (Cyprus) Limited'),
(9783, '00:26:59', 'Nintendo Co., Ltd.'),
(9784, '00:26:5A', 'D-Link Corporation'),
(9785, '00:26:5B', 'Hitron Technologies. Inc'),
(9786, '00:26:5C', 'Hon Hai Precision Ind. Co.,Ltd.'),
(9787, '00:26:5D', 'Samsung Electronics'),
(9788, '00:26:5E', 'Hon Hai Precision Ind. Co.,Ltd.'),
(9789, '00:26:5F', 'Samsung Electronics Co.,Ltd'),
(9790, '00:26:60', 'Logiways'),
(9791, '00:26:61', 'Irumtek Co., Ltd.'),
(9792, '00:26:62', 'Actiontec Electronics, Inc'),
(9793, '00:26:63', 'Shenzhen Huitaiwei Tech. Ltd, co.'),
(9794, '00:26:64', 'Core System Japan'),
(9795, '00:26:65', 'ProtectedLogic Corporation'),
(9796, '00:26:66', 'EFM Networks'),
(9797, '00:26:67', 'CARECOM CO.,LTD.'),
(9798, '00:26:68', 'Nokia Danmark A/S'),
(9799, '00:26:69', 'Nokia Danmark A/S'),
(9800, '00:26:6A', 'ESSENSIUM NV'),
(9801, '00:26:6B', 'SHINE UNION ENTERPRISE LIMITED'),
(9802, '00:26:6C', 'Inventec'),
(9803, '00:26:6D', 'MobileAccess Networks'),
(9804, '00:26:6E', 'Nissho-denki Co.,LTD.'),
(9805, '00:26:6F', 'Coordiwise Technology Corp.'),
(9806, '00:26:70', 'Cinch Connectors'),
(9807, '00:26:71', 'AUTOVISION Co., Ltd'),
(9808, '00:26:72', 'AAMP of America'),
(9809, '00:26:73', 'RICOH COMPANY,LTD.'),
(9810, '00:26:74', 'Electronic Solutions, Inc.'),
(9811, '00:26:75', 'Aztech Electronics Pte Ltd'),
(9812, '00:26:76', 'COMMidt AS'),
(9813, '00:26:77', 'DEIF A/S'),
(9814, '00:26:78', 'Logic Instrument SA'),
(9815, '00:26:79', 'Euphonic Technologies, Inc.'),
(9816, '00:26:7A', 'wuhan hongxin telecommunication technologies co.,ltd'),
(9817, '00:26:7B', 'GSI Helmholtzzentrum f&uuml;r Schwerionenforschung GmbH'),
(9818, '00:26:7C', 'Metz-Werke GmbH &amp; Co KG'),
(9819, '00:26:7D', 'A-Max Technology Macao Commercial Offshore Company Limited'),
(9820, '00:26:7E', 'Parrot SA'),
(9821, '00:26:7F', 'Zenterio AB'),
(9822, '00:26:80', 'Lockie Innovation Pty Ltd'),
(9823, '00:26:81', 'Interspiro AB'),
(9824, '00:26:82', 'Gemtek Technology Co., Ltd.'),
(9825, '00:26:83', 'Ajoho Enterprise Co., Ltd.'),
(9826, '00:26:84', 'KISAN SYSTEM'),
(9827, '00:26:85', 'Digital Innovation'),
(9828, '00:26:86', 'Quantenna Communcations, Inc.'),
(9829, '00:26:87', 'Corega K.K'),
(9830, '00:26:88', 'Juniper Networks'),
(9831, '00:26:89', 'General Dynamics Robotic Systems'),
(9832, '00:26:8A', 'Terrier SC Ltd'),
(9833, '00:26:8B', 'Guangzhou Escene Computer Technology Limited'),
(9834, '00:26:8C', 'StarLeaf Ltd.'),
(9835, '00:26:8D', 'CellTel S.p.A.'),
(9836, '00:26:8E', 'Alta Solutions, Inc.'),
(9837, '00:26:8F', 'MTA SpA'),
(9838, '00:26:90', 'I DO IT'),
(9839, '00:26:91', 'SAGEM COMMUNICATION'),
(9840, '00:26:92', 'Mitsubishi Electric Co.'),
(9841, '00:26:93', 'QVidium Technologies, Inc.'),
(9842, '00:26:94', 'Senscient Ltd'),
(9843, '00:26:95', 'ZT Group Int\'l Inc'),
(9844, '00:26:96', 'NOOLIX Co., Ltd'),
(9845, '00:26:97', 'Cheetah Technologies, L.P.'),
(9846, '00:26:98', 'CISCO SYSTEMS, INC.'),
(9847, '00:26:99', 'CISCO SYSTEMS, INC.'),
(9848, '00:26:9A', 'Carina System Co., Ltd.'),
(9849, '00:26:9B', 'SOKRAT Ltd.'),
(9850, '00:26:9C', 'ITUS JAPAN CO. LTD'),
(9851, '00:26:9D', 'M2Mnet Co., Ltd.'),
(9852, '00:26:9E', 'Quanta Computer Inc'),
(9853, '00:26:9F', 'PRIVATE'),
(9854, '00:26:A0', 'moblic'),
(9855, '00:26:A1', 'Megger'),
(9856, '00:26:A2', 'Instrumentation Technology Systems'),
(9857, '00:26:A3', 'FQ Ingenieria Electronica S.A.'),
(9858, '00:26:A4', 'Novus Produtos Eletronicos Ltda'),
(9859, '00:26:A5', 'MICROROBOT.CO.,LTD'),
(9860, '00:26:A6', 'TRIXELL'),
(9861, '00:26:A7', 'CONNECT SRL'),
(9862, '00:26:A8', 'DAEHAP HYPER-TECH'),
(9863, '00:26:A9', 'Strong Technologies Pty Ltd'),
(9864, '00:26:AA', 'Kenmec Mechanical Engineering Co., Ltd.'),
(9865, '00:26:AB', 'SEIKO EPSON CORPORATION'),
(9866, '00:26:AC', 'Shanghai LUSTER Teraband photonic Co., Ltd.'),
(9867, '00:26:AD', 'Arada Systems, Inc.'),
(9868, '00:26:AE', 'Wireless Measurement Ltd'),
(9869, '00:26:AF', 'Duelco A/S'),
(9870, '00:26:B0', 'Apple'),
(9871, '00:26:B1', 'Navis Auto Motive Systems, Inc.'),
(9872, '00:26:B2', 'Setrix GmbH'),
(9873, '00:26:B3', 'Thales Communications Inc'),
(9874, '00:26:B4', 'Ford Motor Company'),
(9875, '00:26:B5', 'ICOMM Tele Ltd'),
(9876, '00:26:B6', 'Askey Computer'),
(9877, '00:26:B7', 'Kingston Technology Company, Inc.'),
(9878, '00:26:B8', 'Actiontec Electronics, Inc'),
(9879, '00:26:B9', 'Dell Inc'),
(9880, '00:26:BA', 'ARRIS Group, Inc.'),
(9881, '00:26:BB', 'Apple'),
(9882, '00:26:BC', 'General Jack Technology Ltd.'),
(9883, '00:26:BD', 'JTEC Card &amp; Communication Co., Ltd.'),
(9884, '00:26:BE', 'Schoonderbeek Elektronica Systemen B.V.'),
(9885, '00:26:BF', 'ShenZhen Temobi Science&amp;Tech Development Co.,Ltd'),
(9886, '00:26:C0', 'EnergyHub'),
(9887, '00:26:C1', 'ARTRAY CO., LTD.'),
(9888, '00:26:C2', 'SCDI Co. LTD'),
(9889, '00:26:C3', 'Insightek Corp.'),
(9890, '00:26:C4', 'Cadmos microsystems S.r.l.'),
(9891, '00:26:C5', 'Guangdong Gosun Telecommunications Co.,Ltd'),
(9892, '00:26:C6', 'Intel Corporate'),
(9893, '00:26:C7', 'Intel Corporate'),
(9894, '00:26:C8', 'System Sensor'),
(9895, '00:26:C9', 'Proventix Systems, Inc.'),
(9896, '00:26:CA', 'CISCO SYSTEMS, INC.'),
(9897, '00:26:CB', 'CISCO SYSTEMS, INC.'),
(9898, '00:26:CC', 'Nokia Danmark A/S'),
(9899, '00:26:CD', 'PurpleComm, Inc.'),
(9900, '00:26:CE', 'Kozumi USA Corp.'),
(9901, '00:26:CF', 'DEKA R&amp;D'),
(9902, '00:26:D0', 'Semihalf'),
(9903, '00:26:D1', 'S Squared Innovations Inc.'),
(9904, '00:26:D2', 'Pcube Systems, Inc.'),
(9905, '00:26:D3', 'Zeno Information System'),
(9906, '00:26:D4', 'IRCA SpA'),
(9907, '00:26:D5', 'Ory Solucoes em Comercio de Informatica Ltda.'),
(9908, '00:26:D6', 'Ningbo Andy Optoelectronic Co., Ltd.'),
(9909, '00:26:D7', 'KM Electornic Technology Co., Ltd.'),
(9910, '00:26:D8', 'Magic Point Inc.'),
(9911, '00:26:D9', 'Pace plc'),
(9912, '00:26:DA', 'Universal Media Corporation /Slovakia/ s.r.o.'),
(9913, '00:26:DB', 'Ionics EMS Inc.'),
(9914, '00:26:DC', 'Optical Systems Design'),
(9915, '00:26:DD', 'Fival Science &amp; Technology Co.,Ltd.'),
(9916, '00:26:DE', 'FDI MATELEC'),
(9917, '00:26:DF', 'TaiDoc Technology Corp.'),
(9918, '00:26:E0', 'ASITEQ'),
(9919, '00:26:E1', 'Stanford University, OpenFlow Group'),
(9920, '00:26:E2', 'LG Electronics'),
(9921, '00:26:E3', 'DTI'),
(9922, '00:26:E4', 'CANAL OVERSEAS'),
(9923, '00:26:E5', 'AEG Power Solutions'),
(9924, '00:26:E6', 'Visionhitech Co., Ltd.'),
(9925, '00:26:E7', 'Shanghai ONLAN Communication Tech. Co., Ltd.'),
(9926, '00:26:E8', 'Murata Manufacturing Co., Ltd.'),
(9927, '00:26:E9', 'SP Corp'),
(9928, '00:26:EA', 'Cheerchip Electronic Technology (ShangHai) Co., Ltd.'),
(9929, '00:26:EB', 'Advanced Spectrum Technology Co., Ltd.'),
(9930, '00:26:EC', 'Legrand Home Systems, Inc'),
(9931, '00:26:ED', 'zte corporation'),
(9932, '00:26:EE', 'TKM GmbH'),
(9933, '00:26:EF', 'Technology Advancement Group, Inc.'),
(9934, '00:26:F0', 'cTrixs International GmbH.'),
(9935, '00:26:F1', 'ProCurve Networking by HP'),
(9936, '00:26:F2', 'Netgear'),
(9937, '00:26:F3', 'SMC Networks'),
(9938, '00:26:F4', 'Nesslab'),
(9939, '00:26:F5', 'XRPLUS Inc.'),
(9940, '00:26:F6', 'Military Communication Institute'),
(9941, '00:26:F7', 'Infosys Technologies Ltd.'),
(9942, '00:26:F8', 'Golden Highway Industry Development Co., Ltd.'),
(9943, '00:26:F9', 'S.E.M. srl'),
(9944, '00:26:FA', 'BandRich Inc.'),
(9945, '00:26:FB', 'AirDio Wireless, Inc.'),
(9946, '00:26:FC', 'AcSiP Technology Corp.'),
(9947, '00:26:FD', 'Interactive Intelligence'),
(9948, '00:26:FE', 'MKD Technology Inc.'),
(9949, '00:26:FF', 'Research In Motion'),
(9950, '00:27:00', 'Shenzhen Siglent Technology Co., Ltd.'),
(9951, '00:27:01', 'INCOstartec GmbH'),
(9952, '00:27:02', 'SolarEdge Technologies'),
(9953, '00:27:03', 'Testech Electronics Pte Ltd'),
(9954, '00:27:04', 'Accelerated Concepts, Inc'),
(9955, '00:27:05', 'Sectronic'),
(9956, '00:27:06', 'YOISYS'),
(9957, '00:27:07', 'Lift Complex DS, JSC'),
(9958, '00:27:08', 'Nordiag ASA'),
(9959, '00:27:09', 'Nintendo Co., Ltd.'),
(9960, '00:27:0A', 'IEE S.A.'),
(9961, '00:27:0B', 'Adura Technologies'),
(9962, '00:27:0C', 'CISCO SYSTEMS, INC.'),
(9963, '00:27:0D', 'CISCO SYSTEMS, INC.'),
(9964, '00:27:0E', 'Intel Corporate'),
(9965, '00:27:0F', 'Envisionnovation Inc'),
(9966, '00:27:10', 'Intel Corporate'),
(9967, '00:27:11', 'LanPro Inc'),
(9968, '00:27:12', 'MaxVision LLC'),
(9969, '00:27:13', 'Universal Global Scientific Industrial Co., Ltd.'),
(9970, '00:27:14', 'Grainmustards, Co,ltd.'),
(9971, '00:27:15', 'Rebound Telecom. Co., Ltd'),
(9972, '00:27:16', 'Adachi-Syokai Co., Ltd.'),
(9973, '00:27:17', 'CE Digital(Zhenjiang)Co.,Ltd'),
(9974, '00:27:18', 'Suzhou NEW SEAUNION Video Technology Co.,Ltd'),
(9975, '00:27:19', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(9976, '00:27:1A', 'Geenovo Technology Ltd.'),
(9977, '00:27:1B', 'Alec Sicherheitssysteme GmbH'),
(9978, '00:27:1C', 'MERCURY CORPORATION'),
(9979, '00:27:1D', 'Comba Telecom Systems (China) Ltd.'),
(9980, '00:27:1E', 'Xagyl Communications'),
(9981, '00:27:1F', 'MIPRO Electronics Co., Ltd'),
(9982, '00:27:20', 'NEW-SOL COM'),
(9983, '00:27:21', 'Shenzhen Baoan Fenda Industrial Co., Ltd'),
(9984, '00:27:22', 'Ubiquiti Networks'),
(9985, '00:27:F8', 'Brocade Communications Systems, Inc.'),
(9986, '00:2A:6A', 'CISCO SYSTEMS, INC.'),
(9987, '00:2A:AF', 'LARsys-Automation GmbH'),
(9988, '00:2D:76', 'TITECH GmbH'),
(9989, '00:30:00', 'ALLWELL TECHNOLOGY CORP.'),
(9990, '00:30:01', 'SMP'),
(9991, '00:30:02', 'Expand Networks'),
(9992, '00:30:03', 'Phasys Ltd.'),
(9993, '00:30:04', 'LEADTEK RESEARCH INC.'),
(9994, '00:30:05', 'Fujitsu Siemens Computers'),
(9995, '00:30:06', 'SUPERPOWER COMPUTER'),
(9996, '00:30:07', 'OPTI, INC.'),
(9997, '00:30:08', 'AVIO DIGITAL, INC.'),
(9998, '00:30:09', 'Tachion Networks, Inc.'),
(9999, '00:30:0A', 'AZTECH Electronics Pte Ltd'),
(10000, '00:30:0B', 'mPHASE Technologies, Inc.'),
(10001, '00:30:0C', 'CONGRUENCY, LTD.'),
(10002, '00:30:0D', 'MMC Technology, Inc.'),
(10003, '00:30:0E', 'Klotz Digital AG'),
(10004, '00:30:0F', 'IMT - Information Management T'),
(10005, '00:30:10', 'VISIONETICS INTERNATIONAL'),
(10006, '00:30:11', 'HMS Industrial Networks'),
(10007, '00:30:12', 'DIGITAL ENGINEERING LTD.'),
(10008, '00:30:13', 'NEC Corporation'),
(10009, '00:30:14', 'DIVIO, INC.'),
(10010, '00:30:15', 'CP CLARE CORP.'),
(10011, '00:30:16', 'ISHIDA CO., LTD.'),
(10012, '00:30:17', 'BlueArc UK Ltd'),
(10013, '00:30:18', 'Jetway Information Co., Ltd.'),
(10014, '00:30:19', 'CISCO SYSTEMS, INC.'),
(10015, '00:30:1A', 'SMARTBRIDGES PTE. LTD.'),
(10016, '00:30:1B', 'SHUTTLE, INC.'),
(10017, '00:30:1C', 'ALTVATER AIRDATA SYSTEMS'),
(10018, '00:30:1D', 'SKYSTREAM, INC.'),
(10019, '00:30:1E', '3COM Europe Ltd.'),
(10020, '00:30:1F', 'OPTICAL NETWORKS, INC.'),
(10021, '00:30:20', 'TSI, Inc..'),
(10022, '00:30:21', 'HSING TECH. ENTERPRISE CO.,LTD'),
(10023, '00:30:22', 'Fong Kai Industrial Co., Ltd.'),
(10024, '00:30:23', 'COGENT COMPUTER SYSTEMS, INC.'),
(10025, '00:30:24', 'CISCO SYSTEMS, INC.'),
(10026, '00:30:25', 'CHECKOUT COMPUTER SYSTEMS, LTD'),
(10027, '00:30:26', 'HeiTel Digital Video GmbH'),
(10028, '00:30:27', 'KERBANGO, INC.'),
(10029, '00:30:28', 'FASE Saldatura srl'),
(10030, '00:30:29', 'OPICOM'),
(10031, '00:30:2A', 'SOUTHERN INFORMATION'),
(10032, '00:30:2B', 'INALP NETWORKS, INC.'),
(10033, '00:30:2C', 'SYLANTRO SYSTEMS CORPORATION'),
(10034, '00:30:2D', 'QUANTUM BRIDGE COMMUNICATIONS'),
(10035, '00:30:2E', 'Hoft &amp; Wessel AG'),
(10036, '00:30:2F', 'GE Aviation System'),
(10037, '00:30:30', 'HARMONIX CORPORATION'),
(10038, '00:30:31', 'LIGHTWAVE COMMUNICATIONS, INC.'),
(10039, '00:30:32', 'MagicRam, Inc.'),
(10040, '00:30:33', 'ORIENT TELECOM CO., LTD.'),
(10041, '00:30:34', 'SET ENGINEERING'),
(10042, '00:30:35', 'Corning Incorporated'),
(10043, '00:30:36', 'RMP ELEKTRONIKSYSTEME GMBH'),
(10044, '00:30:37', 'Packard Bell Nec Services'),
(10045, '00:30:38', 'XCP, INC.'),
(10046, '00:30:39', 'SOFTBOOK PRESS'),
(10047, '00:30:3A', 'MAATEL'),
(10048, '00:30:3B', 'PowerCom Technology'),
(10049, '00:30:3C', 'ONNTO CORP.'),
(10050, '00:30:3D', 'IVA CORPORATION'),
(10051, '00:30:3E', 'Radcom Ltd.'),
(10052, '00:30:3F', 'TurboComm Tech Inc.'),
(10053, '00:30:40', 'CISCO SYSTEMS, INC.'),
(10054, '00:30:41', 'SAEJIN T &amp; M CO., LTD.'),
(10055, '00:30:42', 'DeTeWe-Deutsche Telephonwerke'),
(10056, '00:30:43', 'IDREAM TECHNOLOGIES, PTE. LTD.'),
(10057, '00:30:44', 'CradlePoint, Inc'),
(10058, '00:30:45', 'Village Networks, Inc. (VNI)'),
(10059, '00:30:46', 'Controlled Electronic Manageme'),
(10060, '00:30:47', 'NISSEI ELECTRIC CO., LTD.'),
(10061, '00:30:48', 'Supermicro Computer, Inc.'),
(10062, '00:30:49', 'BRYANT TECHNOLOGY, LTD.'),
(10063, '00:30:4A', 'Fraunhofer IPMS'),
(10064, '00:30:4B', 'ORBACOM SYSTEMS, INC.'),
(10065, '00:30:4C', 'APPIAN COMMUNICATIONS, INC.'),
(10066, '00:30:4D', 'ESI'),
(10067, '00:30:4E', 'BUSTEC PRODUCTION LTD.'),
(10068, '00:30:4F', 'PLANET Technology Corporation'),
(10069, '00:30:50', 'Versa Technology'),
(10070, '00:30:51', 'ORBIT AVIONIC &amp; COMMUNICATION'),
(10071, '00:30:52', 'ELASTIC NETWORKS'),
(10072, '00:30:53', 'Basler AG'),
(10073, '00:30:54', 'CASTLENET TECHNOLOGY, INC.'),
(10074, '00:30:55', 'Renesas Technology America, Inc.'),
(10075, '00:30:56', 'Beck IPC GmbH'),
(10076, '00:30:57', 'QTelNet, Inc.'),
(10077, '00:30:58', 'API MOTION'),
(10078, '00:30:59', 'KONTRON COMPACT COMPUTERS AG'),
(10079, '00:30:5A', 'TELGEN CORPORATION'),
(10080, '00:30:5B', 'Toko Inc.'),
(10081, '00:30:5C', 'SMAR Laboratories Corp.'),
(10082, '00:30:5D', 'DIGITRA SYSTEMS, INC.'),
(10083, '00:30:5E', 'Abelko Innovation'),
(10084, '00:30:5F', 'Hasselblad'),
(10085, '00:30:60', 'Powerfile, Inc.'),
(10086, '00:30:61', 'MobyTEL'),
(10087, '00:30:62', 'IP Video Networks Inc'),
(10088, '00:30:63', 'SANTERA SYSTEMS, INC.'),
(10089, '00:30:64', 'ADLINK TECHNOLOGY, INC.'),
(10090, '00:30:65', 'Apple'),
(10091, '00:30:66', 'RFM'),
(10092, '00:30:67', 'BIOSTAR MICROTECH INT\'L CORP.'),
(10093, '00:30:68', 'CYBERNETICS TECH. CO., LTD.'),
(10094, '00:30:69', 'IMPACCT TECHNOLOGY CORP.'),
(10095, '00:30:6A', 'PENTA MEDIA CO., LTD.'),
(10096, '00:30:6B', 'CMOS SYSTEMS, INC.'),
(10097, '00:30:6C', 'Hitex Holding GmbH'),
(10098, '00:30:6D', 'LUCENT TECHNOLOGIES'),
(10099, '00:30:6E', 'HEWLETT PACKARD'),
(10100, '00:30:6F', 'SEYEON TECH. CO., LTD.'),
(10101, '00:30:70', '1Net Corporation'),
(10102, '00:30:71', 'CISCO SYSTEMS, INC.'),
(10103, '00:30:72', 'Intellibyte Inc.'),
(10104, '00:30:73', 'International Microsystems, In'),
(10105, '00:30:74', 'EQUIINET LTD.'),
(10106, '00:30:75', 'ADTECH'),
(10107, '00:30:76', 'Akamba Corporation'),
(10108, '00:30:77', 'ONPREM NETWORKS'),
(10109, '00:30:78', 'CISCO SYSTEMS, INC.'),
(10110, '00:30:79', 'CQOS, INC.'),
(10111, '00:30:7A', 'Advanced Technology &amp; Systems'),
(10112, '00:30:7B', 'CISCO SYSTEMS, INC.'),
(10113, '00:30:7C', 'ADID SA'),
(10114, '00:30:7D', 'GRE AMERICA, INC.'),
(10115, '00:30:7E', 'Redflex Communication Systems'),
(10116, '00:30:7F', 'IRLAN LTD.'),
(10117, '00:30:80', 'CISCO SYSTEMS, INC.'),
(10118, '00:30:81', 'ALTOS C&amp;C'),
(10119, '00:30:82', 'TAIHAN ELECTRIC WIRE CO., LTD.'),
(10120, '00:30:83', 'Ivron Systems'),
(10121, '00:30:84', 'ALLIED TELESYN INTERNAIONAL'),
(10122, '00:30:85', 'CISCO SYSTEMS, INC.'),
(10123, '00:30:86', 'Transistor Devices, Inc.'),
(10124, '00:30:87', 'VEGA GRIESHABER KG'),
(10125, '00:30:88', 'Ericsson'),
(10126, '00:30:89', 'Spectrapoint Wireless, LLC'),
(10127, '00:30:8A', 'NICOTRA SISTEMI S.P.A'),
(10128, '00:30:8B', 'Brix Networks'),
(10129, '00:30:8C', 'Quantum Corporation'),
(10130, '00:30:8D', 'Pinnacle Systems, Inc.'),
(10131, '00:30:8E', 'CROSS MATCH TECHNOLOGIES, INC.'),
(10132, '00:30:8F', 'MICRILOR, Inc.'),
(10133, '00:30:90', 'CYRA TECHNOLOGIES, INC.'),
(10134, '00:30:91', 'TAIWAN FIRST LINE ELEC. CORP.'),
(10135, '00:30:92', 'ModuNORM GmbH'),
(10136, '00:30:93', 'Sonnet Technologies, Inc'),
(10137, '00:30:94', 'CISCO SYSTEMS, INC.'),
(10138, '00:30:95', 'Procomp Informatics, Ltd.'),
(10139, '00:30:96', 'CISCO SYSTEMS, INC.'),
(10140, '00:30:97', 'AB Regin'),
(10141, '00:30:98', 'Global Converging Technologies'),
(10142, '00:30:99', 'BOENIG UND KALLENBACH OHG'),
(10143, '00:30:9A', 'ASTRO TERRA CORP.'),
(10144, '00:30:9B', 'Smartware'),
(10145, '00:30:9C', 'Timing Applications, Inc.'),
(10146, '00:30:9D', 'Nimble Microsystems, Inc.'),
(10147, '00:30:9E', 'WORKBIT CORPORATION.'),
(10148, '00:30:9F', 'AMBER NETWORKS'),
(10149, '00:30:A0', 'TYCO SUBMARINE SYSTEMS, LTD.'),
(10150, '00:30:A1', 'WEBGATE Inc.'),
(10151, '00:30:A2', 'Lightner Engineering'),
(10152, '00:30:A3', 'CISCO SYSTEMS, INC.'),
(10153, '00:30:A4', 'Woodwind Communications System'),
(10154, '00:30:A5', 'ACTIVE POWER'),
(10155, '00:30:A6', 'VIANET TECHNOLOGIES, LTD.'),
(10156, '00:30:A7', 'SCHWEITZER ENGINEERING'),
(10157, '00:30:A8', 'OL\'E COMMUNICATIONS, INC.'),
(10158, '00:30:A9', 'Netiverse, Inc.'),
(10159, '00:30:AA', 'AXUS MICROSYSTEMS, INC.'),
(10160, '00:30:AB', 'DELTA NETWORKS, INC.'),
(10161, '00:30:AC', 'Systeme Lauer GmbH &amp; Co., Ltd.'),
(10162, '00:30:AD', 'SHANGHAI COMMUNICATION'),
(10163, '00:30:AE', 'Times N System, Inc.'),
(10164, '00:30:AF', 'Honeywell GmbH'),
(10165, '00:30:B0', 'Convergenet Technologies'),
(10166, '00:30:B1', 'TrunkNet'),
(10167, '00:30:B2', 'L-3 Sonoma EO'),
(10168, '00:30:B3', 'San Valley Systems, Inc.'),
(10169, '00:30:B4', 'INTERSIL CORP.'),
(10170, '00:30:B5', 'Tadiran Microwave Networks'),
(10171, '00:30:B6', 'CISCO SYSTEMS, INC.'),
(10172, '00:30:B7', 'Teletrol Systems, Inc.'),
(10173, '00:30:B8', 'RiverDelta Networks'),
(10174, '00:30:B9', 'ECTEL'),
(10175, '00:30:BA', 'AC&amp;T SYSTEM CO., LTD.'),
(10176, '00:30:BB', 'CacheFlow, Inc.'),
(10177, '00:30:BC', 'Optronic AG'),
(10178, '00:30:BD', 'BELKIN COMPONENTS'),
(10179, '00:30:BE', 'City-Net Technology, Inc.'),
(10180, '00:30:BF', 'MULTIDATA GMBH'),
(10181, '00:30:C0', 'Lara Technology, Inc.'),
(10182, '00:30:C1', 'HEWLETT-PACKARD'),
(10183, '00:30:C2', 'COMONE'),
(10184, '00:30:C3', 'FLUECKIGER ELEKTRONIK AG'),
(10185, '00:30:C4', 'Canon Imaging Systems Inc.'),
(10186, '00:30:C5', 'CADENCE DESIGN SYSTEMS'),
(10187, '00:30:C6', 'CONTROL SOLUTIONS, INC.'),
(10188, '00:30:C7', 'Macromate Corp.'),
(10189, '00:30:C8', 'GAD LINE, LTD.'),
(10190, '00:30:C9', 'LuxN, N'),
(10191, '00:30:CA', 'Discovery Com'),
(10192, '00:30:CB', 'OMNI FLOW COMPUTERS, INC.'),
(10193, '00:30:CC', 'Tenor Networks, Inc.'),
(10194, '00:30:CD', 'CONEXANT SYSTEMS, INC.'),
(10195, '00:30:CE', 'Zaffire'),
(10196, '00:30:CF', 'TWO TECHNOLOGIES, INC.'),
(10197, '00:30:D0', 'Tellabs'),
(10198, '00:30:D1', 'INOVA CORPORATION'),
(10199, '00:30:D2', 'WIN TECHNOLOGIES, CO., LTD.'),
(10200, '00:30:D3', 'Agilent Technologies'),
(10201, '00:30:D4', 'AAE Systems, Inc.'),
(10202, '00:30:D5', 'DResearch GmbH'),
(10203, '00:30:D6', 'MSC VERTRIEBS GMBH'),
(10204, '00:30:D7', 'Innovative Systems, L.L.C.'),
(10205, '00:30:D8', 'SITEK'),
(10206, '00:30:D9', 'DATACORE SOFTWARE CORP.'),
(10207, '00:30:DA', 'COMTREND CO.'),
(10208, '00:30:DB', 'Mindready Solutions, Inc.'),
(10209, '00:30:DC', 'RIGHTECH CORPORATION'),
(10210, '00:30:DD', 'INDIGITA CORPORATION'),
(10211, '00:30:DE', 'WAGO Kontakttechnik GmbH'),
(10212, '00:30:DF', 'KB/TEL TELECOMUNICACIONES'),
(10213, '00:30:E0', 'OXFORD SEMICONDUCTOR LTD.'),
(10214, '00:30:E1', 'Network Equipment Technologies, Inc.'),
(10215, '00:30:E2', 'GARNET SYSTEMS CO., LTD.'),
(10216, '00:30:E3', 'SEDONA NETWORKS CORP.'),
(10217, '00:30:E4', 'CHIYODA SYSTEM RIKEN'),
(10218, '00:30:E5', 'Amper Datos S.A.'),
(10219, '00:30:E6', 'Draeger Medical Systems, Inc.'),
(10220, '00:30:E7', 'CNF MOBILE SOLUTIONS, INC.'),
(10221, '00:30:E8', 'ENSIM CORP.'),
(10222, '00:30:E9', 'GMA COMMUNICATION MANUFACT\'G'),
(10223, '00:30:EA', 'TeraForce Technology Corporation'),
(10224, '00:30:EB', 'TURBONET COMMUNICATIONS, INC.'),
(10225, '00:30:EC', 'BORGARDT'),
(10226, '00:30:ED', 'Expert Magnetics Corp.'),
(10227, '00:30:EE', 'DSG Technology, Inc.'),
(10228, '00:30:EF', 'NEON TECHNOLOGY, INC.'),
(10229, '00:30:F0', 'Uniform Industrial Corp.'),
(10230, '00:30:F1', 'Accton Technology Corp.'),
(10231, '00:30:F2', 'CISCO SYSTEMS, INC.'),
(10232, '00:30:F3', 'At Work Computers'),
(10233, '00:30:F4', 'STARDOT TECHNOLOGIES'),
(10234, '00:30:F5', 'Wild Lab. Ltd.'),
(10235, '00:30:F6', 'SECURELOGIX CORPORATION'),
(10236, '00:30:F7', 'RAMIX INC.'),
(10237, '00:30:F8', 'Dynapro Systems, Inc.'),
(10238, '00:30:F9', 'Sollae Systems Co., Ltd.'),
(10239, '00:30:FA', 'TELICA, INC.'),
(10240, '00:30:FB', 'AZS Technology AG'),
(10241, '00:30:FC', 'Terawave Communications, Inc.'),
(10242, '00:30:FD', 'INTEGRATED SYSTEMS DESIGN'),
(10243, '00:30:FE', 'DSA GmbH'),
(10244, '00:30:FF', 'DATAFAB SYSTEMS, INC.'),
(10245, '00:33:6C', 'SynapSense Corporation'),
(10246, '00:34:F1', 'Radicom Research, Inc.'),
(10247, '00:35:32', 'Electro-Metrics Corporation'),
(10248, '00:35:60', 'Rosen Aviation'),
(10249, '00:36:F8', 'Conti Temic microelectronic GmbH'),
(10250, '00:36:FE', 'SuperVision'),
(10251, '00:37:6D', 'Murata Manufacturing Co., Ltd.'),
(10252, '00:3A:98', 'CISCO SYSTEMS, INC.'),
(10253, '00:3A:99', 'CISCO SYSTEMS, INC.'),
(10254, '00:3A:9A', 'CISCO SYSTEMS, INC.'),
(10255, '00:3A:9B', 'CISCO SYSTEMS, INC.'),
(10256, '00:3A:9C', 'CISCO SYSTEMS, INC.'),
(10257, '00:3A:9D', 'NEC Platforms, Ltd.'),
(10258, '00:3A:AF', 'BlueBit Ltd.'),
(10259, '00:3C:C5', 'WONWOO Engineering Co., Ltd'),
(10260, '00:3D:41', 'Hatteland Computer AS'),
(10261, '00:3E:E1', 'Apple'),
(10262, '00:40:00', 'PCI COMPONENTES DA AMZONIA LTD'),
(10263, '00:40:01', 'Zero One Technology Co. Ltd.'),
(10264, '00:40:02', 'PERLE SYSTEMS LIMITED'),
(10265, '00:40:03', 'Emerson Process Management Power &amp; Water Solutions, Inc.'),
(10266, '00:40:04', 'ICM CO. LTD.'),
(10267, '00:40:05', 'ANI COMMUNICATIONS INC.'),
(10268, '00:40:06', 'SAMPO TECHNOLOGY CORPORATION'),
(10269, '00:40:07', 'TELMAT INFORMATIQUE'),
(10270, '00:40:08', 'A PLUS INFO CORPORATION'),
(10271, '00:40:09', 'TACHIBANA TECTRON CO., LTD.'),
(10272, '00:40:0A', 'PIVOTAL TECHNOLOGIES, INC.'),
(10273, '00:40:0B', 'CISCO SYSTEMS, INC.'),
(10274, '00:40:0C', 'GENERAL MICRO SYSTEMS, INC.'),
(10275, '00:40:0D', 'LANNET DATA COMMUNICATIONS,LTD'),
(10276, '00:40:0E', 'MEMOTEC, INC.'),
(10277, '00:40:0F', 'DATACOM TECHNOLOGIES'),
(10278, '00:40:10', 'SONIC SYSTEMS, INC.'),
(10279, '00:40:11', 'ANDOVER CONTROLS CORPORATION'),
(10280, '00:40:12', 'WINDATA, INC.'),
(10281, '00:40:13', 'NTT DATA COMM. SYSTEMS CORP.'),
(10282, '00:40:14', 'COMSOFT GMBH'),
(10283, '00:40:15', 'ASCOM INFRASYS AG'),
(10284, '00:40:16', 'ADC - Global Connectivity Solutions Division'),
(10285, '00:40:17', 'Silex Technology America'),
(10286, '00:40:18', 'ADOBE SYSTEMS, INC.'),
(10287, '00:40:19', 'AEON SYSTEMS, INC.'),
(10288, '00:40:1A', 'FUJI ELECTRIC CO., LTD.'),
(10289, '00:40:1B', 'PRINTER SYSTEMS CORP.'),
(10290, '00:40:1C', 'AST RESEARCH, INC.'),
(10291, '00:40:1D', 'INVISIBLE SOFTWARE, INC.'),
(10292, '00:40:1E', 'ICC'),
(10293, '00:40:1F', 'COLORGRAPH LTD'),
(10294, '00:40:20', 'TE Connectivity Ltd.'),
(10295, '00:40:21', 'RASTER GRAPHICS'),
(10296, '00:40:22', 'KLEVER COMPUTERS, INC.'),
(10297, '00:40:23', 'LOGIC CORPORATION'),
(10298, '00:40:24', 'COMPAC INC.'),
(10299, '00:40:25', 'MOLECULAR DYNAMICS'),
(10300, '00:40:26', 'Buffalo Inc.'),
(10301, '00:40:27', 'SMC MASSACHUSETTS, INC.'),
(10302, '00:40:28', 'NETCOMM LIMITED'),
(10303, '00:40:29', 'COMPEX'),
(10304, '00:40:2A', 'CANOGA-PERKINS'),
(10305, '00:40:2B', 'TRIGEM COMPUTER, INC.'),
(10306, '00:40:2C', 'ISIS DISTRIBUTED SYSTEMS, INC.'),
(10307, '00:40:2D', 'HARRIS ADACOM CORPORATION'),
(10308, '00:40:2E', 'PRECISION SOFTWARE, INC.'),
(10309, '00:40:2F', 'XLNT DESIGNS INC.'),
(10310, '00:40:30', 'GK COMPUTER'),
(10311, '00:40:31', 'KOKUSAI ELECTRIC CO., LTD'),
(10312, '00:40:32', 'DIGITAL COMMUNICATIONS'),
(10313, '00:40:33', 'ADDTRON TECHNOLOGY CO., LTD.'),
(10314, '00:40:34', 'BUSTEK CORPORATION'),
(10315, '00:40:35', 'OPCOM'),
(10316, '00:40:36', 'TRIBE COMPUTER WORKS, INC.'),
(10317, '00:40:37', 'SEA-ILAN, INC.'),
(10318, '00:40:38', 'TALENT ELECTRIC INCORPORATED'),
(10319, '00:40:39', 'OPTEC DAIICHI DENKO CO., LTD.'),
(10320, '00:40:3A', 'IMPACT TECHNOLOGIES'),
(10321, '00:40:3B', 'SYNERJET INTERNATIONAL CORP.'),
(10322, '00:40:3C', 'FORKS, INC.'),
(10323, '00:40:3D', 'Teradata Corporation'),
(10324, '00:40:3E', 'RASTER OPS CORPORATION'),
(10325, '00:40:3F', 'SSANGYONG COMPUTER SYSTEMS'),
(10326, '00:40:40', 'RING ACCESS, INC.'),
(10327, '00:40:41', 'FUJIKURA LTD.'),
(10328, '00:40:42', 'N.A.T. GMBH'),
(10329, '00:40:43', 'Nokia Siemens Networks GmbH &amp; Co. KG.'),
(10330, '00:40:44', 'QNIX COMPUTER CO., LTD.'),
(10331, '00:40:45', 'TWINHEAD CORPORATION'),
(10332, '00:40:46', 'UDC RESEARCH LIMITED'),
(10333, '00:40:47', 'WIND RIVER SYSTEMS'),
(10334, '00:40:48', 'SMD INFORMATICA S.A.'),
(10335, '00:40:49', 'Roche Diagnostics International Ltd.'),
(10336, '00:40:4A', 'WEST AUSTRALIAN DEPARTMENT'),
(10337, '00:40:4B', 'MAPLE COMPUTER SYSTEMS'),
(10338, '00:40:4C', 'HYPERTEC PTY LTD.'),
(10339, '00:40:4D', 'TELECOMMUNICATIONS TECHNIQUES'),
(10340, '00:40:4E', 'FLUENT, INC.'),
(10341, '00:40:4F', 'SPACE &amp; NAVAL WARFARE SYSTEMS'),
(10342, '00:40:50', 'IRONICS, INCORPORATED'),
(10343, '00:40:51', 'GRACILIS, INC.'),
(10344, '00:40:52', 'STAR TECHNOLOGIES, INC.'),
(10345, '00:40:53', 'AMPRO COMPUTERS'),
(10346, '00:40:54', 'CONNECTION MACHINES SERVICES'),
(10347, '00:40:55', 'METRONIX GMBH'),
(10348, '00:40:56', 'MCM JAPAN LTD.'),
(10349, '00:40:57', 'LOCKHEED - SANDERS'),
(10350, '00:40:58', 'KRONOS, INC.'),
(10351, '00:40:59', 'YOSHIDA KOGYO K. K.'),
(10352, '00:40:5A', 'GOLDSTAR INFORMATION &amp; COMM.'),
(10353, '00:40:5B', 'FUNASSET LIMITED'),
(10354, '00:40:5C', 'FUTURE SYSTEMS, INC.'),
(10355, '00:40:5D', 'STAR-TEK, INC.'),
(10356, '00:40:5E', 'NORTH HILLS ISRAEL'),
(10357, '00:40:5F', 'AFE COMPUTERS LTD.'),
(10358, '00:40:60', 'COMENDEC LTD'),
(10359, '00:40:61', 'DATATECH ENTERPRISES CO., LTD.'),
(10360, '00:40:62', 'E-SYSTEMS, INC./GARLAND DIV.'),
(10361, '00:40:63', 'VIA TECHNOLOGIES, INC.'),
(10362, '00:40:64', 'KLA INSTRUMENTS CORPORATION'),
(10363, '00:40:65', 'GTE SPACENET'),
(10364, '00:40:66', 'Hitachi Metals, Ltd.'),
(10365, '00:40:67', 'OMNIBYTE CORPORATION'),
(10366, '00:40:68', 'EXTENDED SYSTEMS'),
(10367, '00:40:69', 'LEMCOM SYSTEMS, INC.'),
(10368, '00:40:6A', 'KENTEK INFORMATION SYSTEMS,INC'),
(10369, '00:40:6B', 'SYSGEN'),
(10370, '00:40:6C', 'COPERNIQUE'),
(10371, '00:40:6D', 'LANCO, INC.'),
(10372, '00:40:6E', 'COROLLARY, INC.'),
(10373, '00:40:6F', 'SYNC RESEARCH INC.'),
(10374, '00:40:70', 'INTERWARE CO., LTD.'),
(10375, '00:40:71', 'ATM COMPUTER GMBH'),
(10376, '00:40:72', 'Applied Innovation Inc.'),
(10377, '00:40:73', 'BASS ASSOCIATES'),
(10378, '00:40:74', 'CABLE AND WIRELESS'),
(10379, '00:40:75', 'Tattile SRL'),
(10380, '00:40:76', 'Sun Conversion Technologies'),
(10381, '00:40:77', 'MAXTON TECHNOLOGY CORPORATION'),
(10382, '00:40:78', 'WEARNES AUTOMATION PTE LTD'),
(10383, '00:40:79', 'JUKO MANUFACTURE COMPANY, LTD.'),
(10384, '00:40:7A', 'SOCIETE D\'EXPLOITATION DU CNIT'),
(10385, '00:40:7B', 'SCIENTIFIC ATLANTA'),
(10386, '00:40:7C', 'QUME CORPORATION'),
(10387, '00:40:7D', 'EXTENSION TECHNOLOGY CORP.'),
(10388, '00:40:7E', 'EVERGREEN SYSTEMS, INC.'),
(10389, '00:40:7F', 'FLIR Systems'),
(10390, '00:40:80', 'ATHENIX CORPORATION'),
(10391, '00:40:81', 'MANNESMANN SCANGRAPHIC GMBH'),
(10392, '00:40:82', 'LABORATORY EQUIPMENT CORP.'),
(10393, '00:40:83', 'TDA INDUSTRIA DE PRODUTOS'),
(10394, '00:40:84', 'HONEYWELL ACS'),
(10395, '00:40:85', 'SAAB INSTRUMENTS AB'),
(10396, '00:40:86', 'MICHELS &amp; KLEBERHOFF COMPUTER'),
(10397, '00:40:87', 'UBITREX CORPORATION'),
(10398, '00:40:88', 'MOBIUS TECHNOLOGIES, INC.'),
(10399, '00:40:89', 'MEIDENSHA CORPORATION'),
(10400, '00:40:8A', 'TPS TELEPROCESSING SYS. GMBH'),
(10401, '00:40:8B', 'RAYLAN CORPORATION'),
(10402, '00:40:8C', 'AXIS COMMUNICATIONS AB'),
(10403, '00:40:8D', 'THE GOODYEAR TIRE &amp; RUBBER CO.'),
(10404, '00:40:8E', 'Tattile SRL'),
(10405, '00:40:8F', 'WM-DATA MINFO AB'),
(10406, '00:40:90', 'ANSEL COMMUNICATIONS'),
(10407, '00:40:91', 'PROCOMP INDUSTRIA ELETRONICA'),
(10408, '00:40:92', 'ASP COMPUTER PRODUCTS, INC.'),
(10409, '00:40:93', 'PAXDATA NETWORKS LTD.'),
(10410, '00:40:94', 'SHOGRAPHICS, INC.'),
(10411, '00:40:95', 'R.P.T. INTERGROUPS INT\'L LTD.'),
(10412, '00:40:96', 'Cisco Systems'),
(10413, '00:40:97', 'DATEX DIVISION OF'),
(10414, '00:40:98', 'DRESSLER GMBH &amp; CO.'),
(10415, '00:40:99', 'NEWGEN SYSTEMS CORP.'),
(10416, '00:40:9A', 'NETWORK EXPRESS, INC.'),
(10417, '00:40:9B', 'HAL COMPUTER SYSTEMS INC.'),
(10418, '00:40:9C', 'TRANSWARE'),
(10419, '00:40:9D', 'DIGIBOARD, INC.'),
(10420, '00:40:9E', 'CONCURRENT TECHNOLOGIES  LTD.'),
(10421, '00:40:9F', 'Telco Systems, Inc.'),
(10422, '00:40:A0', 'GOLDSTAR CO., LTD.'),
(10423, '00:40:A1', 'ERGO COMPUTING'),
(10424, '00:40:A2', 'KINGSTAR TECHNOLOGY INC.'),
(10425, '00:40:A3', 'MICROUNITY SYSTEMS ENGINEERING'),
(10426, '00:40:A4', 'ROSE ELECTRONICS'),
(10427, '00:40:A5', 'CLINICOMP INTL.'),
(10428, '00:40:A6', 'Cray, Inc.'),
(10429, '00:40:A7', 'ITAUTEC PHILCO S.A.'),
(10430, '00:40:A8', 'IMF INTERNATIONAL LTD.'),
(10431, '00:40:A9', 'DATACOM INC.'),
(10432, '00:40:AA', 'Metso Automation'),
(10433, '00:40:AB', 'ROLAND DG CORPORATION'),
(10434, '00:40:AC', 'SUPER WORKSTATION, INC.'),
(10435, '00:40:AD', 'SMA REGELSYSTEME GMBH'),
(10436, '00:40:AE', 'DELTA CONTROLS, INC.'),
(10437, '00:40:AF', 'DIGITAL PRODUCTS, INC.'),
(10438, '00:40:B0', 'BYTEX CORPORATION, ENGINEERING'),
(10439, '00:40:B1', 'CODONICS INC.'),
(10440, '00:40:B2', 'SYSTEMFORSCHUNG'),
(10441, '00:40:B3', 'ParTech Inc.'),
(10442, '00:40:B4', 'NEXTCOM K.K.'),
(10443, '00:40:B5', 'VIDEO TECHNOLOGY COMPUTERS LTD'),
(10444, '00:40:B6', 'COMPUTERM  CORPORATION'),
(10445, '00:40:B7', 'STEALTH COMPUTER SYSTEMS'),
(10446, '00:40:B8', 'IDEA ASSOCIATES'),
(10447, '00:40:B9', 'MACQ ELECTRONIQUE SA'),
(10448, '00:40:BA', 'ALLIANT COMPUTER SYSTEMS CORP.'),
(10449, '00:40:BB', 'GOLDSTAR CABLE CO., LTD.'),
(10450, '00:40:BC', 'ALGORITHMICS LTD.'),
(10451, '00:40:BD', 'STARLIGHT NETWORKS, INC.'),
(10452, '00:40:BE', 'BOEING DEFENSE &amp; SPACE'),
(10453, '00:40:BF', 'CHANNEL SYSTEMS INTERN\'L INC.'),
(10454, '00:40:C0', 'VISTA CONTROLS CORPORATION'),
(10455, '00:40:C1', 'BIZERBA-WERKE WILHEIM KRAUT'),
(10456, '00:40:C2', 'APPLIED COMPUTING DEVICES'),
(10457, '00:40:C3', 'FISCHER AND PORTER CO.'),
(10458, '00:40:C4', 'KINKEI SYSTEM CORPORATION'),
(10459, '00:40:C5', 'MICOM COMMUNICATIONS INC.'),
(10460, '00:40:C6', 'FIBERNET RESEARCH, INC.'),
(10461, '00:40:C7', 'RUBY TECH CORPORATION'),
(10462, '00:40:C8', 'MILAN TECHNOLOGY CORPORATION'),
(10463, '00:40:C9', 'NCUBE'),
(10464, '00:40:CA', 'FIRST INTERNAT\'L COMPUTER, INC'),
(10465, '00:40:CB', 'LANWAN TECHNOLOGIES'),
(10466, '00:40:CC', 'SILCOM MANUF\'G TECHNOLOGY INC.'),
(10467, '00:40:CD', 'TERA MICROSYSTEMS, INC.'),
(10468, '00:40:CE', 'NET-SOURCE, INC.'),
(10469, '00:40:CF', 'STRAWBERRY TREE, INC.'),
(10470, '00:40:D0', 'MITAC INTERNATIONAL CORP.'),
(10471, '00:40:D1', 'FUKUDA DENSHI CO., LTD.'),
(10472, '00:40:D2', 'PAGINE CORPORATION'),
(10473, '00:40:D3', 'KIMPSION INTERNATIONAL CORP.'),
(10474, '00:40:D4', 'GAGE TALKER CORP.'),
(10475, '00:40:D5', 'Sartorius Mechatronics T&amp;H GmbH'),
(10476, '00:40:D6', 'LOCAMATION B.V.'),
(10477, '00:40:D7', 'STUDIO GEN INC.'),
(10478, '00:40:D8', 'OCEAN OFFICE AUTOMATION LTD.'),
(10479, '00:40:D9', 'AMERICAN MEGATRENDS INC.'),
(10480, '00:40:DA', 'TELSPEC LTD'),
(10481, '00:40:DB', 'ADVANCED TECHNICAL SOLUTIONS'),
(10482, '00:40:DC', 'TRITEC ELECTRONIC GMBH'),
(10483, '00:40:DD', 'HONG TECHNOLOGIES'),
(10484, '00:40:DE', 'Elsag Datamat spa'),
(10485, '00:40:DF', 'DIGALOG SYSTEMS, INC.'),
(10486, '00:40:E0', 'ATOMWIDE LTD.'),
(10487, '00:40:E1', 'MARNER INTERNATIONAL, INC.'),
(10488, '00:40:E2', 'MESA RIDGE TECHNOLOGIES, INC.'),
(10489, '00:40:E3', 'QUIN SYSTEMS LTD'),
(10490, '00:40:E4', 'E-M TECHNOLOGY, INC.'),
(10491, '00:40:E5', 'SYBUS CORPORATION'),
(10492, '00:40:E6', 'C.A.E.N.'),
(10493, '00:40:E7', 'ARNOS INSTRUMENTS &amp; COMPUTER'),
(10494, '00:40:E8', 'CHARLES RIVER DATA SYSTEMS,INC'),
(10495, '00:40:E9', 'ACCORD SYSTEMS, INC.'),
(10496, '00:40:EA', 'PLAIN TREE SYSTEMS INC'),
(10497, '00:40:EB', 'MARTIN MARIETTA CORPORATION'),
(10498, '00:40:EC', 'MIKASA SYSTEM ENGINEERING'),
(10499, '00:40:ED', 'NETWORK CONTROLS INT\'NATL INC.'),
(10500, '00:40:EE', 'OPTIMEM'),
(10501, '00:40:EF', 'HYPERCOM, INC.'),
(10502, '00:40:F0', 'MicroBrain,Inc.'),
(10503, '00:40:F1', 'CHUO ELECTRONICS CO., LTD.'),
(10504, '00:40:F2', 'JANICH &amp; KLASS COMPUTERTECHNIK'),
(10505, '00:40:F3', 'NETCOR'),
(10506, '00:40:F4', 'CAMEO COMMUNICATIONS, INC.'),
(10507, '00:40:F5', 'OEM ENGINES'),
(10508, '00:40:F6', 'KATRON COMPUTERS INC.'),
(10509, '00:40:F7', 'Polaroid Corporation'),
(10510, '00:40:F8', 'SYSTEMHAUS DISCOM'),
(10511, '00:40:F9', 'COMBINET'),
(10512, '00:40:FA', 'MICROBOARDS, INC.'),
(10513, '00:40:FB', 'CASCADE COMMUNICATIONS CORP.'),
(10514, '00:40:FC', 'IBR COMPUTER TECHNIK GMBH'),
(10515, '00:40:FD', 'LXE'),
(10516, '00:40:FE', 'SYMPLEX COMMUNICATIONS'),
(10517, '00:40:FF', 'TELEBIT CORPORATION'),
(10518, '00:41:B4', 'Wuxi Zhongxing Optoelectronics Technology Co.,Ltd.'),
(10519, '00:42:52', 'RLX Technologies'),
(10520, '00:43:FF', 'KETRON S.R.L.'),
(10521, '00:45:01', 'Versus Technology, Inc.'),
(10522, '00:46:4B', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(10523, '00:4D:32', 'Andon Health Co.,Ltd.'),
(10524, '00:50:00', 'NEXO COMMUNICATIONS, INC.'),
(10525, '00:50:01', 'YAMASHITA SYSTEMS CORP.'),
(10526, '00:50:02', 'OMNISEC AG'),
(10527, '00:50:03', 'Xrite Inc'),
(10528, '00:50:04', '3COM CORPORATION'),
(10529, '00:50:06', 'TAC AB'),
(10530, '00:50:07', 'SIEMENS TELECOMMUNICATION SYSTEMS LIMITED'),
(10531, '00:50:08', 'TIVA MICROCOMPUTER CORP. (TMC)'),
(10532, '00:50:09', 'PHILIPS BROADBAND NETWORKS'),
(10533, '00:50:0A', 'IRIS TECHNOLOGIES, INC.'),
(10534, '00:50:0B', 'CISCO SYSTEMS, INC.'),
(10535, '00:50:0C', 'e-Tek Labs, Inc.'),
(10536, '00:50:0D', 'SATORI ELECTORIC CO., LTD.'),
(10537, '00:50:0E', 'CHROMATIS NETWORKS, INC.'),
(10538, '00:50:0F', 'CISCO SYSTEMS, INC.'),
(10539, '00:50:10', 'NovaNET Learning, Inc.'),
(10540, '00:50:12', 'CBL - GMBH'),
(10541, '00:50:13', 'Chaparral Network Storage'),
(10542, '00:50:14', 'CISCO SYSTEMS, INC.'),
(10543, '00:50:15', 'BRIGHT STAR ENGINEERING'),
(10544, '00:50:16', 'SST/WOODHEAD INDUSTRIES'),
(10545, '00:50:17', 'RSR S.R.L.'),
(10546, '00:50:18', 'AMIT, Inc.'),
(10547, '00:50:19', 'SPRING TIDE NETWORKS, INC.'),
(10548, '00:50:1A', 'IQinVision'),
(10549, '00:50:1B', 'ABL CANADA, INC.'),
(10550, '00:50:1C', 'JATOM SYSTEMS, INC.'),
(10551, '00:50:1E', 'Miranda Technologies, Inc.'),
(10552, '00:50:1F', 'MRG SYSTEMS, LTD.'),
(10553, '00:50:20', 'MEDIASTAR CO., LTD.'),
(10554, '00:50:21', 'EIS INTERNATIONAL, INC.'),
(10555, '00:50:22', 'ZONET TECHNOLOGY, INC.'),
(10556, '00:50:23', 'PG DESIGN ELECTRONICS, INC.'),
(10557, '00:50:24', 'NAVIC SYSTEMS, INC.'),
(10558, '00:50:26', 'COSYSTEMS, INC.'),
(10559, '00:50:27', 'GENICOM CORPORATION'),
(10560, '00:50:28', 'AVAL COMMUNICATIONS'),
(10561, '00:50:29', '1394 PRINTER WORKING GROUP'),
(10562, '00:50:2A', 'CISCO SYSTEMS, INC.'),
(10563, '00:50:2B', 'GENRAD LTD.'),
(10564, '00:50:2C', 'SOYO COMPUTER, INC.'),
(10565, '00:50:2D', 'ACCEL, INC.'),
(10566, '00:50:2E', 'CAMBEX CORPORATION'),
(10567, '00:50:2F', 'TollBridge Technologies, Inc.'),
(10568, '00:50:30', 'FUTURE PLUS SYSTEMS'),
(10569, '00:50:31', 'AEROFLEX LABORATORIES, INC.'),
(10570, '00:50:32', 'PICAZO COMMUNICATIONS, INC.'),
(10571, '00:50:33', 'MAYAN NETWORKS'),
(10572, '00:50:36', 'NETCAM, LTD.'),
(10573, '00:50:37', 'KOGA ELECTRONICS CO.'),
(10574, '00:50:38', 'DAIN TELECOM CO., LTD.'),
(10575, '00:50:39', 'MARINER NETWORKS'),
(10576, '00:50:3A', 'DATONG ELECTRONICS LTD.'),
(10577, '00:50:3B', 'MEDIAFIRE CORPORATION'),
(10578, '00:50:3C', 'TSINGHUA NOVEL ELECTRONICS'),
(10579, '00:50:3E', 'CISCO SYSTEMS, INC.'),
(10580, '00:50:3F', 'ANCHOR GAMES'),
(10581, '00:50:40', 'Panasonic Electric Works Co., Ltd.'),
(10582, '00:50:41', 'Coretronic Corporation'),
(10583, '00:50:42', 'SCI MANUFACTURING SINGAPORE PTE, LTD.'),
(10584, '00:50:43', 'MARVELL SEMICONDUCTOR, INC.'),
(10585, '00:50:44', 'ASACA CORPORATION'),
(10586, '00:50:45', 'RIOWORKS SOLUTIONS, INC.'),
(10587, '00:50:46', 'MENICX INTERNATIONAL CO., LTD.'),
(10588, '00:50:47', 'PRIVATE'),
(10589, '00:50:48', 'INFOLIBRIA'),
(10590, '00:50:49', 'Arbor Networks Inc'),
(10591, '00:50:4A', 'ELTECO A.S.'),
(10592, '00:50:4B', 'BARCONET N.V.'),
(10593, '00:50:4C', 'Galil Motion Control'),
(10594, '00:50:4D', 'Tokyo Electron Device Limited'),
(10595, '00:50:4E', 'SIERRA MONITOR CORP.'),
(10596, '00:50:4F', 'OLENCOM ELECTRONICS'),
(10597, '00:50:50', 'CISCO SYSTEMS, INC.'),
(10598, '00:50:51', 'IWATSU ELECTRIC CO., LTD.'),
(10599, '00:50:52', 'TIARA NETWORKS, INC.'),
(10600, '00:50:53', 'CISCO SYSTEMS, INC.'),
(10601, '00:50:54', 'CISCO SYSTEMS, INC.'),
(10602, '00:50:55', 'DOMS A/S'),
(10603, '00:50:56', 'VMware, Inc.'),
(10604, '00:50:57', 'BROADBAND ACCESS SYSTEMS'),
(10605, '00:50:58', 'VegaStream Group Limted'),
(10606, '00:50:59', 'iBAHN'),
(10607, '00:50:5A', 'NETWORK ALCHEMY, INC.'),
(10608, '00:50:5B', 'KAWASAKI LSI U.S.A., INC.'),
(10609, '00:50:5C', 'TUNDO CORPORATION'),
(10610, '00:50:5E', 'DIGITEK MICROLOGIC S.A.'),
(10611, '00:50:5F', 'BRAND INNOVATORS'),
(10612, '00:50:60', 'TANDBERG TELECOM AS'),
(10613, '00:50:62', 'KOUWELL ELECTRONICS CORP.  **'),
(10614, '00:50:63', 'OY COMSEL SYSTEM AB'),
(10615, '00:50:64', 'CAE ELECTRONICS'),
(10616, '00:50:65', 'TDK-Lambda Corporation'),
(10617, '00:50:66', 'AtecoM GmbH advanced telecomunication modules'),
(10618, '00:50:67', 'AEROCOMM, INC.'),
(10619, '00:50:68', 'ELECTRONIC INDUSTRIES ASSOCIATION'),
(10620, '00:50:69', 'PixStream Incorporated'),
(10621, '00:50:6A', 'EDEVA, INC.'),
(10622, '00:50:6B', 'SPX-ATEG'),
(10623, '00:50:6C', 'Beijer Electronics Products AB'),
(10624, '00:50:6D', 'VIDEOJET SYSTEMS'),
(10625, '00:50:6E', 'CORDER ENGINEERING CORPORATION'),
(10626, '00:50:6F', 'G-CONNECT'),
(10627, '00:50:70', 'CHAINTECH COMPUTER CO., LTD.'),
(10628, '00:50:71', 'AIWA CO., LTD.'),
(10629, '00:50:72', 'CORVIS CORPORATION'),
(10630, '00:50:73', 'CISCO SYSTEMS, INC.'),
(10631, '00:50:74', 'ADVANCED HI-TECH CORP.'),
(10632, '00:50:75', 'KESTREL SOLUTIONS'),
(10633, '00:50:76', 'IBM Corp'),
(10634, '00:50:77', 'PROLIFIC TECHNOLOGY, INC.'),
(10635, '00:50:78', 'MEGATON HOUSE, LTD.'),
(10636, '00:50:79', 'PRIVATE'),
(10637, '00:50:7A', 'XPEED, INC.'),
(10638, '00:50:7B', 'MERLOT COMMUNICATIONS'),
(10639, '00:50:7C', 'VIDEOCON AG'),
(10640, '00:50:7D', 'IFP'),
(10641, '00:50:7E', 'NEWER TECHNOLOGY'),
(10642, '00:50:7F', 'DrayTek Corp.'),
(10643, '00:50:80', 'CISCO SYSTEMS, INC.'),
(10644, '00:50:81', 'MURATA MACHINERY, LTD.'),
(10645, '00:50:82', 'FORESSON CORPORATION'),
(10646, '00:50:83', 'GILBARCO, INC.'),
(10647, '00:50:84', 'ATL PRODUCTS'),
(10648, '00:50:86', 'TELKOM SA, LTD.'),
(10649, '00:50:87', 'TERASAKI ELECTRIC CO., LTD.'),
(10650, '00:50:88', 'AMANO CORPORATION'),
(10651, '00:50:89', 'SAFETY MANAGEMENT SYSTEMS'),
(10652, '00:50:8B', 'Hewlett-Packard Company'),
(10653, '00:50:8C', 'RSI SYSTEMS'),
(10654, '00:50:8D', 'ABIT COMPUTER CORPORATION'),
(10655, '00:50:8E', 'OPTIMATION, INC.'),
(10656, '00:50:8F', 'ASITA TECHNOLOGIES INT\'L LTD.'),
(10657, '00:50:90', 'DCTRI'),
(10658, '00:50:91', 'NETACCESS, INC.'),
(10659, '00:50:92', 'RIGAKU INDUSTRIAL CORPORATION'),
(10660, '00:50:93', 'BOEING'),
(10661, '00:50:94', 'PACE plc'),
(10662, '00:50:95', 'PERACOM NETWORKS'),
(10663, '00:50:96', 'SALIX TECHNOLOGIES, INC.'),
(10664, '00:50:97', 'MMC-EMBEDDED COMPUTERTECHNIK GmbH'),
(10665, '00:50:98', 'GLOBALOOP, LTD.'),
(10666, '00:50:99', '3COM EUROPE, LTD.'),
(10667, '00:50:9A', 'TAG ELECTRONIC SYSTEMS'),
(10668, '00:50:9B', 'SWITCHCORE AB'),
(10669, '00:50:9C', 'BETA RESEARCH'),
(10670, '00:50:9D', 'THE INDUSTREE B.V.'),
(10671, '00:50:9E', 'Les Technologies SoftAcoustik Inc.'),
(10672, '00:50:9F', 'HORIZON COMPUTER'),
(10673, '00:50:A0', 'DELTA COMPUTER SYSTEMS, INC.'),
(10674, '00:50:A1', 'CARLO GAVAZZI, INC.'),
(10675, '00:50:A2', 'CISCO SYSTEMS, INC.'),
(10676, '00:50:A3', 'TransMedia Communications, Inc.'),
(10677, '00:50:A4', 'IO TECH, INC.'),
(10678, '00:50:A5', 'CAPITOL BUSINESS SYSTEMS, LTD.'),
(10679, '00:50:A6', 'OPTRONICS'),
(10680, '00:50:A7', 'CISCO SYSTEMS, INC.'),
(10681, '00:50:A8', 'OpenCon Systems, Inc.'),
(10682, '00:50:A9', 'MOLDAT WIRELESS TECHNOLGIES'),
(10683, '00:50:AA', 'KONICA MINOLTA HOLDINGS, INC.'),
(10684, '00:50:AB', 'NALTEC, Inc.'),
(10685, '00:50:AC', 'MAPLE COMPUTER CORPORATION'),
(10686, '00:50:AD', 'CommUnique Wireless Corp.'),
(10687, '00:50:AE', 'FDK Co., Ltd'),
(10688, '00:50:AF', 'INTERGON, INC.'),
(10689, '00:50:B0', 'TECHNOLOGY ATLANTA CORPORATION'),
(10690, '00:50:B1', 'GIDDINGS &amp; LEWIS'),
(10691, '00:50:B2', 'BRODEL GmbH'),
(10692, '00:50:B3', 'VOICEBOARD CORPORATION'),
(10693, '00:50:B4', 'SATCHWELL CONTROL SYSTEMS, LTD'),
(10694, '00:50:B5', 'FICHET-BAUCHE'),
(10695, '00:50:B6', 'GOOD WAY IND. CO., LTD.'),
(10696, '00:50:B7', 'BOSER TECHNOLOGY CO., LTD.'),
(10697, '00:50:B8', 'INOVA COMPUTERS GMBH &amp; CO. KG'),
(10698, '00:50:B9', 'XITRON TECHNOLOGIES, INC.'),
(10699, '00:50:BA', 'D-LINK'),
(10700, '00:50:BB', 'CMS TECHNOLOGIES'),
(10701, '00:50:BC', 'HAMMER STORAGE SOLUTIONS'),
(10702, '00:50:BD', 'CISCO SYSTEMS, INC.'),
(10703, '00:50:BE', 'FAST MULTIMEDIA AG'),
(10704, '00:50:BF', 'Metalligence Technology Corp.'),
(10705, '00:50:C0', 'GATAN, INC.'),
(10706, '00:50:C1', 'GEMFLEX NETWORKS, LTD.'),
(10707, '00:50:C2', 'IEEE REGISTRATION AUTHORITY  - Please see IAB public listing for more information.'),
(10708, '00:50:C4', 'IMD'),
(10709, '00:50:C5', 'ADS Technologies, Inc'),
(10710, '00:50:C6', 'LOOP TELECOMMUNICATION INTERNATIONAL, INC.'),
(10711, '00:50:C8', 'Addonics Technologies, Inc.'),
(10712, '00:50:C9', 'MASPRO DENKOH CORP.'),
(10713, '00:50:CA', 'NET TO NET TECHNOLOGIES'),
(10714, '00:50:CB', 'JETTER'),
(10715, '00:50:CC', 'XYRATEX'),
(10716, '00:50:CD', 'DIGIANSWER A/S'),
(10717, '00:50:CE', 'LG INTERNATIONAL CORP.'),
(10718, '00:50:CF', 'VANLINK COMMUNICATION TECHNOLOGY RESEARCH INSTITUTE'),
(10719, '00:50:D0', 'MINERVA SYSTEMS'),
(10720, '00:50:D1', 'CISCO SYSTEMS, INC.'),
(10721, '00:50:D2', 'CMC Electronics Inc'),
(10722, '00:50:D3', 'DIGITAL AUDIO PROCESSING PTY. LTD.'),
(10723, '00:50:D4', 'JOOHONG INFORMATION &amp;'),
(10724, '00:50:D5', 'AD SYSTEMS CORP.'),
(10725, '00:50:D6', 'ATLAS COPCO TOOLS AB'),
(10726, '00:50:D7', 'TELSTRAT'),
(10727, '00:50:D8', 'UNICORN COMPUTER CORP.'),
(10728, '00:50:D9', 'ENGETRON-ENGENHARIA ELETRONICA IND. e COM. LTDA'),
(10729, '00:50:DA', '3COM CORPORATION'),
(10730, '00:50:DB', 'CONTEMPORARY CONTROL'),
(10731, '00:50:DC', 'TAS TELEFONBAU A. SCHWABE GMBH &amp; CO. KG'),
(10732, '00:50:DD', 'SERRA SOLDADURA, S.A.'),
(10733, '00:50:DE', 'SIGNUM SYSTEMS CORP.'),
(10734, '00:50:DF', 'AirFiber, Inc.'),
(10735, '00:50:E1', 'NS TECH ELECTRONICS SDN BHD'),
(10736, '00:50:E2', 'CISCO SYSTEMS, INC.'),
(10737, '00:50:E3', 'ARRIS Group, Inc.'),
(10738, '00:50:E4', 'Apple'),
(10739, '00:50:E6', 'HAKUSAN CORPORATION'),
(10740, '00:50:E7', 'PARADISE INNOVATIONS (ASIA)'),
(10741, '00:50:E8', 'NOMADIX INC.'),
(10742, '00:50:EA', 'XEL COMMUNICATIONS, INC.'),
(10743, '00:50:EB', 'ALPHA-TOP CORPORATION'),
(10744, '00:50:EC', 'OLICOM A/S'),
(10745, '00:50:ED', 'ANDA NETWORKS'),
(10746, '00:50:EE', 'TEK DIGITEL CORPORATION'),
(10747, '00:50:EF', 'SPE Systemhaus GmbH'),
(10748, '00:50:F0', 'CISCO SYSTEMS, INC.'),
(10749, '00:50:F1', 'Intel Corporation'),
(10750, '00:50:F2', 'MICROSOFT CORP.'),
(10751, '00:50:F3', 'GLOBAL NET INFORMATION CO., Ltd.'),
(10752, '00:50:F4', 'SIGMATEK GMBH &amp; CO. KG'),
(10753, '00:50:F6', 'PAN-INTERNATIONAL INDUSTRIAL CORP.'),
(10754, '00:50:F7', 'VENTURE MANUFACTURING (SINGAPORE) LTD.'),
(10755, '00:50:F8', 'ENTREGA TECHNOLOGIES, INC.'),
(10756, '00:50:F9', 'Sensormatic Electronics LLC'),
(10757, '00:50:FA', 'OXTEL, LTD.'),
(10758, '00:50:FB', 'VSK ELECTRONICS'),
(10759, '00:50:FC', 'EDIMAX TECHNOLOGY CO., LTD.'),
(10760, '00:50:FD', 'VISIONCOMM CO., LTD.'),
(10761, '00:50:FE', 'PCTVnet ASA'),
(10762, '00:50:FF', 'HAKKO ELECTRONICS CO., LTD.'),
(10763, '00:52:18', 'Wuxi Keboda Electron Co.Ltd'),
(10764, '00:54:AF', 'Continental Automotive Systems Inc.'),
(10765, '00:59:07', 'LenovoEMC Products USA, LLC'),
(10766, '00:5A:39', 'SHENZHEN FAST TECHNOLOGIES CO., LTD.'),
(10767, '00:5C:B1', 'Gospell DIGITAL TECHNOLOGY CO., LTD'),
(10768, '00:5D:03', 'Xilinx, Inc'),
(10769, '00:60:00', 'XYCOM INC.'),
(10770, '00:60:01', 'InnoSys, Inc.'),
(10771, '00:60:02', 'SCREEN SUBTITLING SYSTEMS, LTD'),
(10772, '00:60:03', 'TERAOKA WEIGH SYSTEM PTE, LTD.'),
(10773, '00:60:04', 'COMPUTADORES MODULARES SA'),
(10774, '00:60:05', 'FEEDBACK DATA LTD.'),
(10775, '00:60:06', 'SOTEC CO., LTD'),
(10776, '00:60:07', 'ACRES GAMING, INC.'),
(10777, '00:60:08', '3COM CORPORATION'),
(10778, '00:60:09', 'CISCO SYSTEMS, INC.'),
(10779, '00:60:0A', 'SORD COMPUTER CORPORATION'),
(10780, '00:60:0B', 'LOGWARE GmbH'),
(10781, '00:60:0C', 'Eurotech Inc.'),
(10782, '00:60:0D', 'Digital Logic GmbH'),
(10783, '00:60:0E', 'WAVENET INTERNATIONAL, INC.'),
(10784, '00:60:0F', 'WESTELL, INC.'),
(10785, '00:60:10', 'NETWORK MACHINES, INC.'),
(10786, '00:60:11', 'CRYSTAL SEMICONDUCTOR CORP.'),
(10787, '00:60:12', 'POWER COMPUTING CORPORATION'),
(10788, '00:60:13', 'NETSTAL MASCHINEN AG'),
(10789, '00:60:14', 'EDEC CO., LTD.'),
(10790, '00:60:15', 'NET2NET CORPORATION'),
(10791, '00:60:16', 'CLARIION'),
(10792, '00:60:17', 'TOKIMEC INC.'),
(10793, '00:60:18', 'STELLAR ONE CORPORATION'),
(10794, '00:60:19', 'Roche Diagnostics'),
(10795, '00:60:1A', 'KEITHLEY INSTRUMENTS'),
(10796, '00:60:1B', 'MESA ELECTRONICS'),
(10797, '00:60:1C', 'TELXON CORPORATION'),
(10798, '00:60:1D', 'LUCENT TECHNOLOGIES'),
(10799, '00:60:1E', 'SOFTLAB, INC.'),
(10800, '00:60:1F', 'STALLION TECHNOLOGIES'),
(10801, '00:60:20', 'PIVOTAL NETWORKING, INC.'),
(10802, '00:60:21', 'DSC CORPORATION'),
(10803, '00:60:22', 'VICOM SYSTEMS, INC.'),
(10804, '00:60:23', 'PERICOM SEMICONDUCTOR CORP.'),
(10805, '00:60:24', 'GRADIENT TECHNOLOGIES, INC.'),
(10806, '00:60:25', 'ACTIVE IMAGING PLC'),
(10807, '00:60:26', 'VIKING Modular Solutions'),
(10808, '00:60:27', 'Superior Modular Products'),
(10809, '00:60:28', 'MACROVISION CORPORATION'),
(10810, '00:60:29', 'CARY PERIPHERALS INC.'),
(10811, '00:60:2A', 'SYMICRON COMPUTER COMMUNICATIONS, LTD.'),
(10812, '00:60:2B', 'PEAK AUDIO'),
(10813, '00:60:2C', 'LINX Data Terminals, Inc.'),
(10814, '00:60:2D', 'ALERTON TECHNOLOGIES, INC.'),
(10815, '00:60:2E', 'CYCLADES CORPORATION'),
(10816, '00:60:2F', 'CISCO SYSTEMS, INC.'),
(10817, '00:60:30', 'VILLAGE TRONIC ENTWICKLUNG'),
(10818, '00:60:31', 'HRK SYSTEMS'),
(10819, '00:60:32', 'I-CUBE, INC.'),
(10820, '00:60:33', 'ACUITY IMAGING, INC.'),
(10821, '00:60:34', 'ROBERT BOSCH GmbH'),
(10822, '00:60:35', 'DALLAS SEMICONDUCTOR, INC.'),
(10823, '00:60:36', 'AIT Austrian Institute of Technology GmbH'),
(10824, '00:60:37', 'NXP Semiconductors'),
(10825, '00:60:38', 'Nortel Networks'),
(10826, '00:60:39', 'SanCom Technology, Inc.'),
(10827, '00:60:3A', 'QUICK CONTROLS LTD.'),
(10828, '00:60:3B', 'AMTEC spa'),
(10829, '00:60:3C', 'HAGIWARA SYS-COM CO., LTD.'),
(10830, '00:60:3D', '3CX'),
(10831, '00:60:3E', 'CISCO SYSTEMS, INC.'),
(10832, '00:60:3F', 'PATAPSCO DESIGNS'),
(10833, '00:60:40', 'NETRO CORP.'),
(10834, '00:60:41', 'Yokogawa Electric Corporation'),
(10835, '00:60:42', 'TKS (USA), INC.'),
(10836, '00:60:43', 'iDirect, INC.'),
(10837, '00:60:44', 'LITTON/POLY-SCIENTIFIC'),
(10838, '00:60:45', 'PATHLIGHT TECHNOLOGIES'),
(10839, '00:60:46', 'VMETRO, INC.'),
(10840, '00:60:47', 'CISCO SYSTEMS, INC.'),
(10841, '00:60:48', 'EMC CORPORATION'),
(10842, '00:60:49', 'VINA TECHNOLOGIES'),
(10843, '00:60:4A', 'SAIC IDEAS GROUP'),
(10844, '00:60:4B', 'Safe-com GmbH &amp; Co. KG'),
(10845, '00:60:4C', 'SAGEM COMMUNICATION'),
(10846, '00:60:4D', 'MMC NETWORKS, INC.'),
(10847, '00:60:4E', 'CYCLE COMPUTER CORPORATION, INC.'),
(10848, '00:60:4F', 'Tattile SRL'),
(10849, '00:60:50', 'INTERNIX INC.'),
(10850, '00:60:51', 'QUALITY SEMICONDUCTOR'),
(10851, '00:60:52', 'PERIPHERALS ENTERPRISE CO., Ltd.'),
(10852, '00:60:53', 'TOYODA MACHINE WORKS, LTD.'),
(10853, '00:60:54', 'CONTROLWARE GMBH'),
(10854, '00:60:55', 'CORNELL UNIVERSITY'),
(10855, '00:60:56', 'NETWORK TOOLS, INC.'),
(10856, '00:60:57', 'MURATA MANUFACTURING CO., LTD.'),
(10857, '00:60:58', 'COPPER MOUNTAIN COMMUNICATIONS, INC.'),
(10858, '00:60:59', 'TECHNICAL COMMUNICATIONS CORP.'),
(10859, '00:60:5A', 'CELCORE, INC.'),
(10860, '00:60:5B', 'IntraServer Technology, Inc.'),
(10861, '00:60:5C', 'CISCO SYSTEMS, INC.'),
(10862, '00:60:5D', 'SCANIVALVE CORP.'),
(10863, '00:60:5E', 'LIBERTY TECHNOLOGY NETWORKING'),
(10864, '00:60:5F', 'NIPPON UNISOFT CORPORATION'),
(10865, '00:60:60', 'Data Innovations North America'),
(10866, '00:60:61', 'WHISTLE COMMUNICATIONS CORP.'),
(10867, '00:60:62', 'TELESYNC, INC.'),
(10868, '00:60:63', 'PSION DACOM PLC.'),
(10869, '00:60:64', 'NETCOMM LIMITED'),
(10870, '00:60:65', 'BERNECKER &amp; RAINER INDUSTRIE-ELEKTRONIC GmbH'),
(10871, '00:60:66', 'LACROIX Trafic'),
(10872, '00:60:67', 'ACER NETXUS INC.'),
(10873, '00:60:68', 'Dialogic Corporation'),
(10874, '00:60:69', 'Brocade Communications Systems, Inc.'),
(10875, '00:60:6A', 'MITSUBISHI WIRELESS COMMUNICATIONS. INC.'),
(10876, '00:60:6B', 'Synclayer Inc.'),
(10877, '00:60:6C', 'ARESCOM'),
(10878, '00:60:6D', 'DIGITAL EQUIPMENT CORP.'),
(10879, '00:60:6E', 'DAVICOM SEMICONDUCTOR, INC.'),
(10880, '00:60:6F', 'CLARION CORPORATION OF AMERICA'),
(10881, '00:60:70', 'CISCO SYSTEMS, INC.'),
(10882, '00:60:71', 'MIDAS LAB, INC.'),
(10883, '00:60:72', 'VXL INSTRUMENTS, LIMITED'),
(10884, '00:60:73', 'REDCREEK COMMUNICATIONS, INC.'),
(10885, '00:60:74', 'QSC AUDIO PRODUCTS'),
(10886, '00:60:75', 'PENTEK, INC.'),
(10887, '00:60:76', 'SCHLUMBERGER TECHNOLOGIES RETAIL PETROLEUM SYSTEMS'),
(10888, '00:60:77', 'PRISA NETWORKS'),
(10889, '00:60:78', 'POWER MEASUREMENT LTD.'),
(10890, '00:60:79', 'Mainstream Data, Inc.'),
(10891, '00:60:7A', 'DVS GmbH'),
(10892, '00:60:7B', 'FORE SYSTEMS, INC.'),
(10893, '00:60:7C', 'WaveAccess, Ltd.'),
(10894, '00:60:7D', 'SENTIENT NETWORKS INC.'),
(10895, '00:60:7E', 'GIGALABS, INC.'),
(10896, '00:60:7F', 'AURORA TECHNOLOGIES, INC.'),
(10897, '00:60:80', 'MICROTRONIX DATACOM LTD.'),
(10898, '00:60:81', 'TV/COM INTERNATIONAL'),
(10899, '00:60:82', 'NOVALINK TECHNOLOGIES, INC.'),
(10900, '00:60:83', 'CISCO SYSTEMS, INC.'),
(10901, '00:60:84', 'DIGITAL VIDEO'),
(10902, '00:60:85', 'Storage Concepts'),
(10903, '00:60:86', 'LOGIC REPLACEMENT TECH. LTD.'),
(10904, '00:60:87', 'KANSAI ELECTRIC CO., LTD.'),
(10905, '00:60:88', 'WHITE MOUNTAIN DSP, INC.'),
(10906, '00:60:89', 'XATA'),
(10907, '00:60:8A', 'CITADEL COMPUTER'),
(10908, '00:60:8B', 'ConferTech International'),
(10909, '00:60:8C', '3COM CORPORATION'),
(10910, '00:60:8D', 'UNIPULSE CORP.'),
(10911, '00:60:8E', 'HE ELECTRONICS, TECHNOLOGIE &amp; SYSTEMTECHNIK GmbH'),
(10912, '00:60:8F', 'TEKRAM TECHNOLOGY CO., LTD.'),
(10913, '00:60:90', 'Artiza Networks Inc'),
(10914, '00:60:91', 'FIRST PACIFIC NETWORKS, INC.'),
(10915, '00:60:92', 'MICRO/SYS, INC.'),
(10916, '00:60:93', 'VARIAN'),
(10917, '00:60:94', 'IBM Corp'),
(10918, '00:60:95', 'ACCU-TIME SYSTEMS, INC.'),
(10919, '00:60:96', 'T.S. MICROTECH INC.'),
(10920, '00:60:97', '3COM CORPORATION'),
(10921, '00:60:98', 'HT COMMUNICATIONS'),
(10922, '00:60:99', 'SBE, Inc.'),
(10923, '00:60:9A', 'NJK TECHNO CO.'),
(10924, '00:60:9B', 'ASTRO-MED, INC.'),
(10925, '00:60:9C', 'Perkin-Elmer Incorporated'),
(10926, '00:60:9D', 'PMI FOOD EQUIPMENT GROUP'),
(10927, '00:60:9E', 'ASC X3 - INFORMATION TECHNOLOGY STANDARDS SECRETARIATS'),
(10928, '00:60:9F', 'PHAST CORPORATION'),
(10929, '00:60:A0', 'SWITCHED NETWORK TECHNOLOGIES, INC.'),
(10930, '00:60:A1', 'VPNet, Inc.'),
(10931, '00:60:A2', 'NIHON UNISYS LIMITED CO.'),
(10932, '00:60:A3', 'CONTINUUM TECHNOLOGY CORP.'),
(10933, '00:60:A4', 'GEW Technologies (PTY)Ltd'),
(10934, '00:60:A5', 'PERFORMANCE TELECOM CORP.'),
(10935, '00:60:A6', 'PARTICLE MEASURING SYSTEMS'),
(10936, '00:60:A7', 'MICROSENS GmbH &amp; CO. KG'),
(10937, '00:60:A8', 'TIDOMAT AB'),
(10938, '00:60:A9', 'GESYTEC MbH'),
(10939, '00:60:AA', 'INTELLIGENT DEVICES INC. (IDI)'),
(10940, '00:60:AB', 'LARSCOM INCORPORATED'),
(10941, '00:60:AC', 'RESILIENCE CORPORATION'),
(10942, '00:60:AD', 'MegaChips Corporation'),
(10943, '00:60:AE', 'TRIO INFORMATION SYSTEMS AB'),
(10944, '00:60:AF', 'PACIFIC MICRO DATA, INC.'),
(10945, '00:60:B0', 'HEWLETT-PACKARD CO.'),
(10946, '00:60:B1', 'INPUT/OUTPUT, INC.'),
(10947, '00:60:B2', 'PROCESS CONTROL CORP.'),
(10948, '00:60:B3', 'Z-COM, INC.'),
(10949, '00:60:B4', 'GLENAYRE R&amp;D INC.'),
(10950, '00:60:B5', 'KEBA GmbH'),
(10951, '00:60:B6', 'LAND COMPUTER CO., LTD.'),
(10952, '00:60:B7', 'CHANNELMATIC, INC.'),
(10953, '00:60:B8', 'CORELIS Inc.'),
(10954, '00:60:B9', 'NEC Platforms, Ltd'),
(10955, '00:60:BA', 'SAHARA NETWORKS, INC.'),
(10956, '00:60:BB', 'CABLETRON - NETLINK, INC.'),
(10957, '00:60:BC', 'KeunYoung Electronics &amp; Communication Co., Ltd.'),
(10958, '00:60:BD', 'HUBBELL-PULSECOM'),
(10959, '00:60:BE', 'WEBTRONICS'),
(10960, '00:60:BF', 'MACRAIGOR SYSTEMS, INC.'),
(10961, '00:60:C0', 'Nera Networks AS'),
(10962, '00:60:C1', 'WaveSpan Corporation'),
(10963, '00:60:C2', 'MPL AG'),
(10964, '00:60:C3', 'NETVISION CORPORATION'),
(10965, '00:60:C4', 'SOLITON SYSTEMS K.K.'),
(10966, '00:60:C5', 'ANCOT CORP.'),
(10967, '00:60:C6', 'DCS AG'),
(10968, '00:60:C7', 'AMATI COMMUNICATIONS CORP.'),
(10969, '00:60:C8', 'KUKA WELDING SYSTEMS &amp; ROBOTS'),
(10970, '00:60:C9', 'ControlNet, Inc.'),
(10971, '00:60:CA', 'HARMONIC SYSTEMS INCORPORATED'),
(10972, '00:60:CB', 'HITACHI ZOSEN CORPORATION'),
(10973, '00:60:CC', 'EMTRAK, INCORPORATED'),
(10974, '00:60:CD', 'VideoServer, Inc.'),
(10975, '00:60:CE', 'ACCLAIM COMMUNICATIONS'),
(10976, '00:60:CF', 'ALTEON NETWORKS, INC.'),
(10977, '00:60:D0', 'SNMP RESEARCH INCORPORATED'),
(10978, '00:60:D1', 'CASCADE COMMUNICATIONS'),
(10979, '00:60:D2', 'LUCENT TECHNOLOGIES TAIWAN TELECOMMUNICATIONS CO., LTD.'),
(10980, '00:60:D3', 'AT&amp;T'),
(10981, '00:60:D4', 'ELDAT COMMUNICATION LTD.'),
(10982, '00:60:D5', 'MIYACHI TECHNOS CORP.'),
(10983, '00:60:D6', 'NovAtel Wireless Technologies Ltd.'),
(10984, '00:60:D7', 'ECOLE POLYTECHNIQUE FEDERALE DE LAUSANNE (EPFL)'),
(10985, '00:60:D8', 'ELMIC SYSTEMS, INC.'),
(10986, '00:60:D9', 'TRANSYS NETWORKS INC.'),
(10987, '00:60:DA', 'Red Lion Controls, LP'),
(10988, '00:60:DB', 'NTP ELEKTRONIK A/S'),
(10989, '00:60:DC', 'Toyo Network Systems  &amp; System Integration Co. LTD'),
(10990, '00:60:DD', 'MYRICOM, INC.'),
(10991, '00:60:DE', 'Kayser-Threde GmbH'),
(10992, '00:60:DF', 'Brocade Communications Systems, Inc.'),
(10993, '00:60:E0', 'AXIOM TECHNOLOGY CO., LTD.'),
(10994, '00:60:E1', 'ORCKIT COMMUNICATIONS LTD.'),
(10995, '00:60:E2', 'QUEST ENGINEERING &amp; DEVELOPMENT'),
(10996, '00:60:E3', 'ARBIN INSTRUMENTS'),
(10997, '00:60:E4', 'COMPUSERVE, INC.'),
(10998, '00:60:E5', 'FUJI AUTOMATION CO., LTD.'),
(10999, '00:60:E6', 'SHOMITI SYSTEMS INCORPORATED'),
(11000, '00:60:E7', 'RANDATA'),
(11001, '00:60:E8', 'HITACHI COMPUTER PRODUCTS (AMERICA), INC.'),
(11002, '00:60:E9', 'ATOP TECHNOLOGIES, INC.'),
(11003, '00:60:EA', 'StreamLogic'),
(11004, '00:60:EB', 'FOURTHTRACK SYSTEMS'),
(11005, '00:60:EC', 'HERMARY OPTO ELECTRONICS INC.'),
(11006, '00:60:ED', 'RICARDO TEST AUTOMATION LTD.'),
(11007, '00:60:EE', 'APOLLO'),
(11008, '00:60:EF', 'FLYTECH TECHNOLOGY CO., LTD.'),
(11009, '00:60:F0', 'JOHNSON &amp; JOHNSON MEDICAL, INC'),
(11010, '00:60:F1', 'EXP COMPUTER, INC.'),
(11011, '00:60:F2', 'LASERGRAPHICS, INC.'),
(11012, '00:60:F3', 'Performance Analysis Broadband, Spirent plc'),
(11013, '00:60:F4', 'ADVANCED COMPUTER SOLUTIONS, Inc.'),
(11014, '00:60:F5', 'ICON WEST, INC.'),
(11015, '00:60:F6', 'NEXTEST COMMUNICATIONS PRODUCTS, INC.'),
(11016, '00:60:F7', 'DATAFUSION SYSTEMS'),
(11017, '00:60:F8', 'Loran International Technologies Inc.'),
(11018, '00:60:F9', 'DIAMOND LANE COMMUNICATIONS'),
(11019, '00:60:FA', 'EDUCATIONAL TECHNOLOGY RESOURCES, INC.'),
(11020, '00:60:FB', 'PACKETEER, INC.'),
(11021, '00:60:FC', 'CONSERVATION THROUGH INNOVATION LTD.'),
(11022, '00:60:FD', 'NetICs, Inc.'),
(11023, '00:60:FE', 'LYNX SYSTEM DEVELOPERS, INC.'),
(11024, '00:60:FF', 'QuVis, Inc.'),
(11025, '00:61:71', 'Apple'),
(11026, '00:64:40', 'CISCO SYSTEMS, INC.'),
(11027, '00:64:A6', 'Maquet CardioVascular'),
(11028, '00:66:4B', 'Huawei Technologies Co., Ltd'),
(11029, '00:6B:8E', 'Shanghai Feixun Communication Co.,Ltd.'),
(11030, '00:6B:9E', 'VIZIO Inc'),
(11031, '00:6B:A0', 'SHENZHEN UNIVERSAL INTELLISYS PTE LTD'),
(11032, '00:6D:FB', 'Vutrix (UK) Ltd'),
(11033, '00:70:B0', 'M/A-COM INC. COMPANIES'),
(11034, '00:70:B3', 'DATA RECALL LTD.'),
(11035, '00:71:C2', 'PEGATRON CORPORATION'),
(11036, '00:71:CC', 'Hon Hai Precision Ind. Co.,Ltd.'),
(11037, '00:73:8D', 'Tinno Mobile Technology Corp'),
(11038, '00:73:E0', 'Samsung Electronics Co.,Ltd'),
(11039, '00:75:32', 'INID BV'),
(11040, '00:75:E1', 'Ampt, LLC'),
(11041, '00:78:9E', 'SAGEMCOM'),
(11042, '00:7D:FA', 'Volkswagen Group of America'),
(11043, '00:7E:56', 'China Dragon Technology Limited'),
(11044, '00:7F:28', 'Actiontec Electronics, Inc'),
(11045, '00:80:00', 'MULTITECH SYSTEMS, INC.'),
(11046, '00:80:01', 'PERIPHONICS CORPORATION'),
(11047, '00:80:02', 'SATELCOM (UK) LTD'),
(11048, '00:80:03', 'HYTEC ELECTRONICS LTD.'),
(11049, '00:80:04', 'ANTLOW COMMUNICATIONS, LTD.'),
(11050, '00:80:05', 'CACTUS COMPUTER INC.'),
(11051, '00:80:06', 'COMPUADD CORPORATION'),
(11052, '00:80:07', 'DLOG NC-SYSTEME'),
(11053, '00:80:08', 'DYNATECH COMPUTER SYSTEMS'),
(11054, '00:80:09', 'JUPITER SYSTEMS, INC.'),
(11055, '00:80:0A', 'JAPAN COMPUTER CORP.'),
(11056, '00:80:0B', 'CSK CORPORATION'),
(11057, '00:80:0C', 'VIDECOM LIMITED'),
(11058, '00:80:0D', 'VOSSWINKEL F.U.'),
(11059, '00:80:0E', 'ATLANTIX CORPORATION'),
(11060, '00:80:0F', 'STANDARD MICROSYSTEMS'),
(11061, '00:80:10', 'COMMODORE INTERNATIONAL'),
(11062, '00:80:11', 'DIGITAL SYSTEMS INT\'L. INC.'),
(11063, '00:80:12', 'INTEGRATED MEASUREMENT SYSTEMS'),
(11064, '00:80:13', 'THOMAS-CONRAD CORPORATION'),
(11065, '00:80:14', 'ESPRIT SYSTEMS'),
(11066, '00:80:15', 'SEIKO SYSTEMS, INC.'),
(11067, '00:80:16', 'WANDEL AND GOLTERMANN'),
(11068, '00:80:17', 'PFU LIMITED'),
(11069, '00:80:18', 'KOBE STEEL, LTD.'),
(11070, '00:80:19', 'DAYNA COMMUNICATIONS, INC.'),
(11071, '00:80:1A', 'BELL ATLANTIC'),
(11072, '00:80:1B', 'KODIAK TECHNOLOGY'),
(11073, '00:80:1C', 'NEWPORT SYSTEMS SOLUTIONS'),
(11074, '00:80:1D', 'INTEGRATED INFERENCE MACHINES'),
(11075, '00:80:1E', 'XINETRON, INC.'),
(11076, '00:80:1F', 'KRUPP ATLAS ELECTRONIK GMBH'),
(11077, '00:80:20', 'NETWORK PRODUCTS'),
(11078, '00:80:21', 'Alcatel Canada Inc.'),
(11079, '00:80:22', 'SCAN-OPTICS'),
(11080, '00:80:23', 'INTEGRATED BUSINESS NETWORKS'),
(11081, '00:80:24', 'KALPANA, INC.'),
(11082, '00:80:25', 'STOLLMANN GMBH'),
(11083, '00:80:26', 'NETWORK PRODUCTS CORPORATION'),
(11084, '00:80:27', 'ADAPTIVE SYSTEMS, INC.'),
(11085, '00:80:28', 'TRADPOST (HK) LTD'),
(11086, '00:80:29', 'EAGLE TECHNOLOGY, INC.'),
(11087, '00:80:2A', 'TEST SYSTEMS &amp; SIMULATIONS INC'),
(11088, '00:80:2B', 'INTEGRATED MARKETING CO'),
(11089, '00:80:2C', 'THE SAGE GROUP PLC'),
(11090, '00:80:2D', 'XYLOGICS INC'),
(11091, '00:80:2E', 'CASTLE ROCK COMPUTING'),
(11092, '00:80:2F', 'NATIONAL INSTRUMENTS CORP.'),
(11093, '00:80:30', 'NEXUS ELECTRONICS'),
(11094, '00:80:31', 'BASYS, CORP.'),
(11095, '00:80:32', 'ACCESS CO., LTD.'),
(11096, '00:80:33', 'EMS Aviation, Inc.'),
(11097, '00:80:34', 'SMT GOUPIL'),
(11098, '00:80:35', 'TECHNOLOGY WORKS, INC.'),
(11099, '00:80:36', 'REFLEX MANUFACTURING SYSTEMS'),
(11100, '00:80:37', 'Ericsson Group'),
(11101, '00:80:38', 'DATA RESEARCH &amp; APPLICATIONS'),
(11102, '00:80:39', 'ALCATEL STC AUSTRALIA'),
(11103, '00:80:3A', 'VARITYPER, INC.'),
(11104, '00:80:3B', 'APT COMMUNICATIONS, INC.'),
(11105, '00:80:3C', 'TVS ELECTRONICS LTD'),
(11106, '00:80:3D', 'SURIGIKEN CO.,  LTD.'),
(11107, '00:80:3E', 'SYNERNETICS'),
(11108, '00:80:3F', 'TATUNG COMPANY'),
(11109, '00:80:40', 'JOHN FLUKE MANUFACTURING CO.'),
(11110, '00:80:41', 'VEB KOMBINAT ROBOTRON'),
(11111, '00:80:42', 'Artesyn Embedded Technologies'),
(11112, '00:80:43', 'NETWORLD, INC.'),
(11113, '00:80:44', 'SYSTECH COMPUTER CORP.'),
(11114, '00:80:45', 'MATSUSHITA ELECTRIC IND. CO'),
(11115, '00:80:46', 'Tattile SRL'),
(11116, '00:80:47', 'IN-NET CORP.'),
(11117, '00:80:48', 'COMPEX INCORPORATED'),
(11118, '00:80:49', 'NISSIN ELECTRIC CO., LTD.'),
(11119, '00:80:4A', 'PRO-LOG'),
(11120, '00:80:4B', 'EAGLE TECHNOLOGIES PTY.LTD.'),
(11121, '00:80:4C', 'CONTEC CO., LTD.'),
(11122, '00:80:4D', 'CYCLONE MICROSYSTEMS, INC.'),
(11123, '00:80:4E', 'APEX COMPUTER COMPANY'),
(11124, '00:80:4F', 'DAIKIN INDUSTRIES, LTD.'),
(11125, '00:80:50', 'ZIATECH CORPORATION'),
(11126, '00:80:51', 'FIBERMUX'),
(11127, '00:80:52', 'TECHNICALLY ELITE CONCEPTS'),
(11128, '00:80:53', 'INTELLICOM, INC.'),
(11129, '00:80:54', 'FRONTIER TECHNOLOGIES CORP.'),
(11130, '00:80:55', 'FERMILAB'),
(11131, '00:80:56', 'SPHINX ELEKTRONIK GMBH'),
(11132, '00:80:57', 'ADSOFT, LTD.'),
(11133, '00:80:58', 'PRINTER SYSTEMS CORPORATION'),
(11134, '00:80:59', 'STANLEY ELECTRIC CO., LTD'),
(11135, '00:80:5A', 'TULIP COMPUTERS INTERNAT\'L B.V'),
(11136, '00:80:5B', 'CONDOR SYSTEMS, INC.'),
(11137, '00:80:5C', 'AGILIS CORPORATION'),
(11138, '00:80:5D', 'CANSTAR'),
(11139, '00:80:5E', 'LSI LOGIC CORPORATION'),
(11140, '00:80:5F', 'Hewlett-Packard Company'),
(11141, '00:80:60', 'NETWORK INTERFACE CORPORATION'),
(11142, '00:80:61', 'LITTON SYSTEMS, INC.'),
(11143, '00:80:62', 'INTERFACE  CO.'),
(11144, '00:80:63', 'Hirschmann Automation and Control GmbH'),
(11145, '00:80:64', 'WYSE TECHNOLOGY LLC'),
(11146, '00:80:65', 'CYBERGRAPHIC SYSTEMS PTY LTD.'),
(11147, '00:80:66', 'ARCOM CONTROL SYSTEMS, LTD.'),
(11148, '00:80:67', 'SQUARE D COMPANY'),
(11149, '00:80:68', 'YAMATECH SCIENTIFIC LTD.'),
(11150, '00:80:69', 'COMPUTONE SYSTEMS'),
(11151, '00:80:6A', 'ERI (EMPAC RESEARCH INC.)'),
(11152, '00:80:6B', 'SCHMID TELECOMMUNICATION'),
(11153, '00:80:6C', 'CEGELEC PROJECTS LTD'),
(11154, '00:80:6D', 'CENTURY SYSTEMS CORP.'),
(11155, '00:80:6E', 'NIPPON STEEL CORPORATION'),
(11156, '00:80:6F', 'ONELAN LTD.'),
(11157, '00:80:70', 'COMPUTADORAS MICRON'),
(11158, '00:80:71', 'SAI TECHNOLOGY'),
(11159, '00:80:72', 'MICROPLEX SYSTEMS LTD.'),
(11160, '00:80:73', 'DWB ASSOCIATES'),
(11161, '00:80:74', 'FISHER CONTROLS'),
(11162, '00:80:75', 'PARSYTEC GMBH'),
(11163, '00:80:76', 'MCNC'),
(11164, '00:80:77', 'BROTHER INDUSTRIES, LTD.'),
(11165, '00:80:78', 'PRACTICAL PERIPHERALS, INC.'),
(11166, '00:80:79', 'MICROBUS DESIGNS LTD.'),
(11167, '00:80:7A', 'AITECH SYSTEMS LTD.'),
(11168, '00:80:7B', 'ARTEL COMMUNICATIONS CORP.'),
(11169, '00:80:7C', 'FIBERCOM, INC.'),
(11170, '00:80:7D', 'EQUINOX SYSTEMS INC.'),
(11171, '00:80:7E', 'SOUTHERN PACIFIC LTD.'),
(11172, '00:80:7F', 'DY-4 INCORPORATED'),
(11173, '00:80:80', 'DATAMEDIA CORPORATION'),
(11174, '00:80:81', 'KENDALL SQUARE RESEARCH CORP.'),
(11175, '00:80:82', 'PEP MODULAR COMPUTERS GMBH'),
(11176, '00:80:83', 'AMDAHL'),
(11177, '00:80:84', 'THE CLOUD INC.'),
(11178, '00:80:85', 'H-THREE SYSTEMS CORPORATION'),
(11179, '00:80:86', 'COMPUTER GENERATION INC.'),
(11180, '00:80:87', 'OKI ELECTRIC INDUSTRY CO., LTD'),
(11181, '00:80:88', 'VICTOR COMPANY OF JAPAN, LTD.'),
(11182, '00:80:89', 'TECNETICS (PTY) LTD.'),
(11183, '00:80:8A', 'SUMMIT MICROSYSTEMS CORP.'),
(11184, '00:80:8B', 'DACOLL LIMITED'),
(11185, '00:80:8C', 'NetScout Systems, Inc.'),
(11186, '00:80:8D', 'WESTCOAST TECHNOLOGY B.V.'),
(11187, '00:80:8E', 'RADSTONE TECHNOLOGY'),
(11188, '00:80:8F', 'C. ITOH ELECTRONICS, INC.'),
(11189, '00:80:90', 'MICROTEK INTERNATIONAL, INC.'),
(11190, '00:80:91', 'TOKYO ELECTRIC CO.,LTD'),
(11191, '00:80:92', 'Silex Technology, Inc.'),
(11192, '00:80:93', 'XYRON CORPORATION'),
(11193, '00:80:94', 'ALFA LAVAL AUTOMATION AB'),
(11194, '00:80:95', 'BASIC MERTON HANDELSGES.M.B.H.'),
(11195, '00:80:96', 'HUMAN DESIGNED SYSTEMS, INC.'),
(11196, '00:80:97', 'CENTRALP AUTOMATISMES'),
(11197, '00:80:98', 'TDK CORPORATION'),
(11198, '00:80:99', 'Eaton Industries GmbH'),
(11199, '00:80:9A', 'NOVUS NETWORKS LTD'),
(11200, '00:80:9B', 'JUSTSYSTEM CORPORATION'),
(11201, '00:80:9C', 'LUXCOM, INC.'),
(11202, '00:80:9D', 'Commscraft Ltd.'),
(11203, '00:80:9E', 'DATUS GMBH'),
(11204, '00:80:9F', 'Alcatel-Lucent Enterprise'),
(11205, '00:80:A0', 'EDISA HEWLETT PACKARD S/A'),
(11206, '00:80:A1', 'MICROTEST, INC.'),
(11207, '00:80:A2', 'CREATIVE ELECTRONIC SYSTEMS'),
(11208, '00:80:A3', 'Lantronix'),
(11209, '00:80:A4', 'LIBERTY ELECTRONICS'),
(11210, '00:80:A5', 'SPEED INTERNATIONAL'),
(11211, '00:80:A6', 'REPUBLIC TECHNOLOGY, INC.'),
(11212, '00:80:A7', 'Honeywell International Inc'),
(11213, '00:80:A8', 'VITACOM CORPORATION'),
(11214, '00:80:A9', 'CLEARPOINT RESEARCH'),
(11215, '00:80:AA', 'MAXPEED'),
(11216, '00:80:AB', 'DUKANE NETWORK INTEGRATION'),
(11217, '00:80:AC', 'IMLOGIX, DIVISION OF GENESYS'),
(11218, '00:80:AD', 'CNET TECHNOLOGY, INC.'),
(11219, '00:80:AE', 'HUGHES NETWORK SYSTEMS'),
(11220, '00:80:AF', 'ALLUMER CO., LTD.'),
(11221, '00:80:B0', 'ADVANCED INFORMATION'),
(11222, '00:80:B1', 'SOFTCOM A/S'),
(11223, '00:80:B2', 'NETWORK EQUIPMENT TECHNOLOGIES'),
(11224, '00:80:B3', 'AVAL DATA CORPORATION'),
(11225, '00:80:B4', 'SOPHIA SYSTEMS'),
(11226, '00:80:B5', 'UNITED NETWORKS INC.'),
(11227, '00:80:B6', 'THEMIS COMPUTER'),
(11228, '00:80:B7', 'STELLAR COMPUTER'),
(11229, '00:80:B8', 'B.U.G. MORISEIKI, INCORPORATED'),
(11230, '00:80:B9', 'ARCHE TECHNOLIGIES INC.'),
(11231, '00:80:BA', 'SPECIALIX (ASIA) PTE, LTD'),
(11232, '00:80:BB', 'HUGHES LAN SYSTEMS'),
(11233, '00:80:BC', 'HITACHI ENGINEERING CO., LTD'),
(11234, '00:80:BD', 'THE FURUKAWA ELECTRIC CO., LTD'),
(11235, '00:80:BE', 'ARIES RESEARCH'),
(11236, '00:80:BF', 'TAKAOKA ELECTRIC MFG. CO. LTD.'),
(11237, '00:80:C0', 'PENRIL DATACOMM'),
(11238, '00:80:C1', 'LANEX CORPORATION'),
(11239, '00:80:C2', 'IEEE 802.1 COMMITTEE'),
(11240, '00:80:C3', 'BICC INFORMATION SYSTEMS &amp; SVC'),
(11241, '00:80:C4', 'DOCUMENT TECHNOLOGIES, INC.'),
(11242, '00:80:C5', 'NOVELLCO DE MEXICO'),
(11243, '00:80:C6', 'NATIONAL DATACOMM CORPORATION'),
(11244, '00:80:C7', 'XIRCOM'),
(11245, '00:80:C8', 'D-LINK SYSTEMS, INC.'),
(11246, '00:80:C9', 'ALBERTA MICROELECTRONIC CENTRE'),
(11247, '00:80:CA', 'NETCOM RESEARCH INCORPORATED'),
(11248, '00:80:CB', 'FALCO DATA PRODUCTS'),
(11249, '00:80:CC', 'MICROWAVE BYPASS SYSTEMS'),
(11250, '00:80:CD', 'MICRONICS COMPUTER, INC.'),
(11251, '00:80:CE', 'BROADCAST TELEVISION SYSTEMS'),
(11252, '00:80:CF', 'EMBEDDED PERFORMANCE INC.'),
(11253, '00:80:D0', 'COMPUTER PERIPHERALS, INC.'),
(11254, '00:80:D1', 'KIMTRON CORPORATION'),
(11255, '00:80:D2', 'SHINNIHONDENKO CO., LTD.'),
(11256, '00:80:D3', 'SHIVA CORP.'),
(11257, '00:80:D4', 'CHASE RESEARCH LTD.'),
(11258, '00:80:D5', 'CADRE TECHNOLOGIES'),
(11259, '00:80:D6', 'NUVOTECH, INC.'),
(11260, '00:80:D7', 'Fantum Engineering'),
(11261, '00:80:D8', 'NETWORK PERIPHERALS INC.'),
(11262, '00:80:D9', 'EMK Elektronik GmbH &amp; Co. KG'),
(11263, '00:80:DA', 'Bruel &amp; Kjaer Sound &amp; Vibration Measurement A/S'),
(11264, '00:80:DB', 'GRAPHON CORPORATION'),
(11265, '00:80:DC', 'PICKER INTERNATIONAL'),
(11266, '00:80:DD', 'GMX INC/GIMIX'),
(11267, '00:80:DE', 'GIPSI S.A.'),
(11268, '00:80:DF', 'ADC CODENOLL TECHNOLOGY CORP.'),
(11269, '00:80:E0', 'XTP SYSTEMS, INC.'),
(11270, '00:80:E1', 'STMICROELECTRONICS'),
(11271, '00:80:E2', 'T.D.I. CO., LTD.'),
(11272, '00:80:E3', 'CORAL NETWORK CORPORATION'),
(11273, '00:80:E4', 'NORTHWEST DIGITAL SYSTEMS, INC'),
(11274, '00:80:E5', 'NetApp, Inc'),
(11275, '00:80:E6', 'PEER NETWORKS, INC.'),
(11276, '00:80:E7', 'LYNWOOD SCIENTIFIC DEV. LTD.'),
(11277, '00:80:E8', 'CUMULUS CORPORATIION'),
(11278, '00:80:E9', 'Madge Ltd.'),
(11279, '00:80:EA', 'ADVA Optical Networking Ltd.'),
(11280, '00:80:EB', 'COMPCONTROL B.V.'),
(11281, '00:80:EC', 'SUPERCOMPUTING SOLUTIONS, INC.'),
(11282, '00:80:ED', 'IQ TECHNOLOGIES, INC.'),
(11283, '00:80:EE', 'THOMSON CSF'),
(11284, '00:80:EF', 'RATIONAL'),
(11285, '00:80:F0', 'Panasonic Communications Co., Ltd.'),
(11286, '00:80:F1', 'OPUS SYSTEMS'),
(11287, '00:80:F2', 'RAYCOM SYSTEMS INC'),
(11288, '00:80:F3', 'SUN ELECTRONICS CORP.'),
(11289, '00:80:F4', 'TELEMECANIQUE ELECTRIQUE'),
(11290, '00:80:F5', 'Quantel Ltd'),
(11291, '00:80:F6', 'SYNERGY MICROSYSTEMS'),
(11292, '00:80:F7', 'ZENITH ELECTRONICS'),
(11293, '00:80:F8', 'MIZAR, INC.'),
(11294, '00:80:F9', 'HEURIKON CORPORATION'),
(11295, '00:80:FA', 'RWT GMBH'),
(11296, '00:80:FB', 'BVM LIMITED'),
(11297, '00:80:FC', 'AVATAR CORPORATION'),
(11298, '00:80:FD', 'EXSCEED CORPRATION'),
(11299, '00:80:FE', 'AZURE TECHNOLOGIES, INC.'),
(11300, '00:80:FF', 'SOC. DE TELEINFORMATIQUE RTC'),
(11301, '00:86:A0', 'PRIVATE'),
(11302, '00:88:65', 'Apple'),
(11303, '00:8B:43', 'RFTECH'),
(11304, '00:8C:10', 'Black Box Corp.'),
(11305, '00:8C:54', 'ADB Broadband Italia'),
(11306, '00:8C:FA', 'Inventec Corporation'),
(11307, '00:8D:4E', 'CJSC NII STT'),
(11308, '00:8D:DA', 'Link One Co., Ltd.'),
(11309, '00:8E:F2', 'NETGEAR INC.,'),
(11310, '00:90:00', 'DIAMOND MULTIMEDIA'),
(11311, '00:90:01', 'NISHIMU ELECTRONICS INDUSTRIES CO., LTD.'),
(11312, '00:90:02', 'ALLGON AB'),
(11313, '00:90:03', 'APLIO'),
(11314, '00:90:04', '3COM EUROPE LTD.'),
(11315, '00:90:05', 'PROTECH SYSTEMS CO., LTD.'),
(11316, '00:90:06', 'HAMAMATSU PHOTONICS K.K.'),
(11317, '00:90:07', 'DOMEX TECHNOLOGY CORP.'),
(11318, '00:90:08', 'HanA Systems Inc.'),
(11319, '00:90:09', 'I Controls, Inc.'),
(11320, '00:90:0A', 'PROTON ELECTRONIC INDUSTRIAL CO., LTD.'),
(11321, '00:90:0B', 'LANNER ELECTRONICS, INC.'),
(11322, '00:90:0C', 'CISCO SYSTEMS, INC.'),
(11323, '00:90:0D', 'Overland Storage Inc.'),
(11324, '00:90:0E', 'HANDLINK TECHNOLOGIES, INC.'),
(11325, '00:90:0F', 'KAWASAKI HEAVY INDUSTRIES, LTD'),
(11326, '00:90:10', 'SIMULATION LABORATORIES, INC.'),
(11327, '00:90:11', 'WAVTrace, Inc.'),
(11328, '00:90:12', 'GLOBESPAN SEMICONDUCTOR, INC.'),
(11329, '00:90:13', 'SAMSAN CORP.'),
(11330, '00:90:14', 'ROTORK INSTRUMENTS, LTD.'),
(11331, '00:90:15', 'CENTIGRAM COMMUNICATIONS CORP.'),
(11332, '00:90:16', 'ZAC'),
(11333, '00:90:17', 'Zypcom, Inc'),
(11334, '00:90:18', 'ITO ELECTRIC INDUSTRY CO, LTD.'),
(11335, '00:90:19', 'HERMES ELECTRONICS CO., LTD.'),
(11336, '00:90:1A', 'UNISPHERE SOLUTIONS'),
(11337, '00:90:1B', 'DIGITAL CONTROLS'),
(11338, '00:90:1C', 'mps Software Gmbh'),
(11339, '00:90:1D', 'PEC (NZ) LTD.'),
(11340, '00:90:1E', 'Selesta Ingegneria S.p.A.'),
(11341, '00:90:1F', 'ADTEC PRODUCTIONS, INC.'),
(11342, '00:90:20', 'PHILIPS ANALYTICAL X-RAY B.V.'),
(11343, '00:90:21', 'CISCO SYSTEMS, INC.'),
(11344, '00:90:22', 'IVEX'),
(11345, '00:90:23', 'ZILOG INC.'),
(11346, '00:90:24', 'PIPELINKS, INC.'),
(11347, '00:90:25', 'BAE Systems Australia (Electronic Systems) Pty Ltd'),
(11348, '00:90:26', 'ADVANCED SWITCHING COMMUNICATIONS, INC.'),
(11349, '00:90:27', 'INTEL CORPORATION'),
(11350, '00:90:28', 'NIPPON SIGNAL CO., LTD.'),
(11351, '00:90:29', 'CRYPTO AG'),
(11352, '00:90:2A', 'COMMUNICATION DEVICES, INC.'),
(11353, '00:90:2B', 'CISCO SYSTEMS, INC.'),
(11354, '00:90:2C', 'DATA &amp; CONTROL EQUIPMENT LTD.'),
(11355, '00:90:2D', 'DATA ELECTRONICS (AUST.) PTY, LTD.'),
(11356, '00:90:2E', 'NAMCO LIMITED'),
(11357, '00:90:2F', 'NETCORE SYSTEMS, INC.'),
(11358, '00:90:30', 'HONEYWELL-DATING'),
(11359, '00:90:31', 'MYSTICOM, LTD.'),
(11360, '00:90:32', 'PELCOMBE GROUP LTD.'),
(11361, '00:90:33', 'INNOVAPHONE AG'),
(11362, '00:90:34', 'IMAGIC, INC.'),
(11363, '00:90:35', 'ALPHA TELECOM, INC.'),
(11364, '00:90:36', 'ens, inc.'),
(11365, '00:90:37', 'ACUCOMM, INC.'),
(11366, '00:90:38', 'FOUNTAIN TECHNOLOGIES, INC.'),
(11367, '00:90:39', 'SHASTA NETWORKS'),
(11368, '00:90:3A', 'NIHON MEDIA TOOL INC.'),
(11369, '00:90:3B', 'TriEMS Research Lab, Inc.'),
(11370, '00:90:3C', 'ATLANTIC NETWORK SYSTEMS'),
(11371, '00:90:3D', 'BIOPAC SYSTEMS, INC.'),
(11372, '00:90:3E', 'N.V. PHILIPS INDUSTRIAL ACTIVITIES'),
(11373, '00:90:3F', 'AZTEC RADIOMEDIA'),
(11374, '00:90:40', 'Siemens Network Convergence LLC'),
(11375, '00:90:41', 'APPLIED DIGITAL ACCESS'),
(11376, '00:90:42', 'ECCS, Inc.'),
(11377, '00:90:43', 'Tattile SRL'),
(11378, '00:90:44', 'ASSURED DIGITAL, INC.'),
(11379, '00:90:45', 'Marconi Communications'),
(11380, '00:90:46', 'DEXDYNE, LTD.'),
(11381, '00:90:47', 'GIGA FAST E. LTD.'),
(11382, '00:90:48', 'ZEAL CORPORATION'),
(11383, '00:90:49', 'ENTRIDIA CORPORATION'),
(11384, '00:90:4A', 'CONCUR SYSTEM TECHNOLOGIES'),
(11385, '00:90:4B', 'GemTek Technology Co., Ltd.'),
(11386, '00:90:4C', 'EPIGRAM, INC.'),
(11387, '00:90:4D', 'SPEC S.A.'),
(11388, '00:90:4E', 'DELEM BV'),
(11389, '00:90:4F', 'ABB POWER T&amp;D COMPANY, INC.'),
(11390, '00:90:50', 'TELESTE OY'),
(11391, '00:90:51', 'ULTIMATE TECHNOLOGY CORP.'),
(11392, '00:90:52', 'SELCOM ELETTRONICA S.R.L.'),
(11393, '00:90:53', 'DAEWOO ELECTRONICS CO., LTD.'),
(11394, '00:90:54', 'INNOVATIVE SEMICONDUCTORS, INC'),
(11395, '00:90:55', 'PARKER HANNIFIN CORPORATION COMPUMOTOR DIVISION'),
(11396, '00:90:56', 'TELESTREAM, INC.'),
(11397, '00:90:57', 'AANetcom, Inc.'),
(11398, '00:90:58', 'Ultra Electronics Ltd., Command and Control Systems'),
(11399, '00:90:59', 'TELECOM DEVICE K.K.'),
(11400, '00:90:5A', 'DEARBORN GROUP, INC.'),
(11401, '00:90:5B', 'RAYMOND AND LAE ENGINEERING'),
(11402, '00:90:5C', 'EDMI'),
(11403, '00:90:5D', 'NETCOM SICHERHEITSTECHNIK GmbH'),
(11404, '00:90:5E', 'RAULAND-BORG CORPORATION'),
(11405, '00:90:5F', 'CISCO SYSTEMS, INC.'),
(11406, '00:90:60', 'SYSTEM CREATE CORP.'),
(11407, '00:90:61', 'PACIFIC RESEARCH &amp; ENGINEERING CORPORATION'),
(11408, '00:90:62', 'ICP VORTEX COMPUTERSYSTEME GmbH'),
(11409, '00:90:63', 'COHERENT COMMUNICATIONS SYSTEMS CORPORATION'),
(11410, '00:90:64', 'Thomson Inc.'),
(11411, '00:90:65', 'FINISAR CORPORATION'),
(11412, '00:90:66', 'Troika Networks, Inc.'),
(11413, '00:90:67', 'WalkAbout Computers, Inc.'),
(11414, '00:90:68', 'DVT CORP.'),
(11415, '00:90:69', 'JUNIPER NETWORKS, INC.'),
(11416, '00:90:6A', 'TURNSTONE SYSTEMS, INC.'),
(11417, '00:90:6B', 'APPLIED RESOURCES, INC.'),
(11418, '00:90:6C', 'Sartorius Hamburg GmbH'),
(11419, '00:90:6D', 'CISCO SYSTEMS, INC.'),
(11420, '00:90:6E', 'PRAXON, INC.'),
(11421, '00:90:6F', 'CISCO SYSTEMS, INC.'),
(11422, '00:90:70', 'NEO NETWORKS, INC.'),
(11423, '00:90:71', 'Applied Innovation Inc.'),
(11424, '00:90:72', 'SIMRAD AS'),
(11425, '00:90:73', 'GAIO TECHNOLOGY'),
(11426, '00:90:74', 'ARGON NETWORKS, INC.'),
(11427, '00:90:75', 'NEC DO BRASIL S.A.'),
(11428, '00:90:76', 'FMT AIRCRAFT GATE SUPPORT SYSTEMS AB'),
(11429, '00:90:77', 'ADVANCED FIBRE COMMUNICATIONS'),
(11430, '00:90:78', 'MER TELEMANAGEMENT SOLUTIONS, LTD.'),
(11431, '00:90:79', 'ClearOne, Inc.'),
(11432, '00:90:7A', 'Spectralink, Inc'),
(11433, '00:90:7B', 'E-TECH, INC.'),
(11434, '00:90:7C', 'DIGITALCAST, INC.'),
(11435, '00:90:7D', 'Lake Communications'),
(11436, '00:90:7E', 'VETRONIX CORP.'),
(11437, '00:90:7F', 'WatchGuard Technologies, Inc.'),
(11438, '00:90:80', 'NOT LIMITED, INC.'),
(11439, '00:90:81', 'ALOHA NETWORKS, INC.'),
(11440, '00:90:82', 'FORCE INSTITUTE'),
(11441, '00:90:83', 'TURBO COMMUNICATION, INC.'),
(11442, '00:90:84', 'ATECH SYSTEM'),
(11443, '00:90:85', 'GOLDEN ENTERPRISES, INC.'),
(11444, '00:90:86', 'CISCO SYSTEMS, INC.'),
(11445, '00:90:87', 'ITIS'),
(11446, '00:90:88', 'BAXALL SECURITY LTD.'),
(11447, '00:90:89', 'SOFTCOM MICROSYSTEMS, INC.'),
(11448, '00:90:8A', 'BAYLY COMMUNICATIONS, INC.'),
(11449, '00:90:8B', 'Tattile SRL'),
(11450, '00:90:8C', 'ETREND ELECTRONICS, INC.'),
(11451, '00:90:8D', 'VICKERS ELECTRONICS SYSTEMS'),
(11452, '00:90:8E', 'Nortel Networks Broadband Access'),
(11453, '00:90:8F', 'AUDIO CODES LTD.'),
(11454, '00:90:90', 'I-BUS'),
(11455, '00:90:91', 'DigitalScape, Inc.'),
(11456, '00:90:92', 'CISCO SYSTEMS, INC.'),
(11457, '00:90:93', 'NANAO CORPORATION'),
(11458, '00:90:94', 'OSPREY TECHNOLOGIES, INC.'),
(11459, '00:90:95', 'UNIVERSAL AVIONICS'),
(11460, '00:90:96', 'ASKEY COMPUTER CORP.'),
(11461, '00:90:97', 'Sycamore Networks'),
(11462, '00:90:98', 'SBC DESIGNS, INC.'),
(11463, '00:90:99', 'ALLIED TELESIS, K.K.'),
(11464, '00:90:9A', 'ONE WORLD SYSTEMS, INC.'),
(11465, '00:90:9B', 'MARKEM-IMAJE'),
(11466, '00:90:9C', 'ARRIS Group, Inc.'),
(11467, '00:90:9D', 'NovaTech Process Solutions, LLC'),
(11468, '00:90:9E', 'Critical IO, LLC'),
(11469, '00:90:9F', 'DIGI-DATA CORPORATION'),
(11470, '00:90:A0', '8X8 INC.'),
(11471, '00:90:A1', 'Flying Pig Systems/High End Systems Inc.'),
(11472, '00:90:A2', 'CYBERTAN TECHNOLOGY, INC.'),
(11473, '00:90:A3', 'Corecess Inc.'),
(11474, '00:90:A4', 'ALTIGA NETWORKS'),
(11475, '00:90:A5', 'SPECTRA LOGIC'),
(11476, '00:90:A6', 'CISCO SYSTEMS, INC.'),
(11477, '00:90:A7', 'CLIENTEC CORPORATION'),
(11478, '00:90:A8', 'NineTiles Networks, Ltd.'),
(11479, '00:90:A9', 'WESTERN DIGITAL'),
(11480, '00:90:AA', 'INDIGO ACTIVE VISION SYSTEMS LIMITED'),
(11481, '00:90:AB', 'CISCO SYSTEMS, INC.'),
(11482, '00:90:AC', 'OPTIVISION, INC.'),
(11483, '00:90:AD', 'ASPECT ELECTRONICS, INC.'),
(11484, '00:90:AE', 'ITALTEL S.p.A.'),
(11485, '00:90:AF', 'J. MORITA MFG. CORP.'),
(11486, '00:90:B0', 'VADEM'),
(11487, '00:90:B1', 'CISCO SYSTEMS, INC.'),
(11488, '00:90:B2', 'AVICI SYSTEMS INC.'),
(11489, '00:90:B3', 'AGRANAT SYSTEMS'),
(11490, '00:90:B4', 'WILLOWBROOK TECHNOLOGIES'),
(11491, '00:90:B5', 'NIKON CORPORATION'),
(11492, '00:90:B6', 'FIBEX SYSTEMS'),
(11493, '00:90:B7', 'DIGITAL LIGHTWAVE, INC.'),
(11494, '00:90:B8', 'ROHDE &amp; SCHWARZ GMBH &amp; CO. KG'),
(11495, '00:90:B9', 'BERAN INSTRUMENTS LTD.'),
(11496, '00:90:BA', 'VALID NETWORKS, INC.'),
(11497, '00:90:BB', 'TAINET COMMUNICATION SYSTEM Corp.'),
(11498, '00:90:BC', 'TELEMANN CO., LTD.'),
(11499, '00:90:BD', 'OMNIA COMMUNICATIONS, INC.'),
(11500, '00:90:BE', 'IBC/INTEGRATED BUSINESS COMPUTERS'),
(11501, '00:90:BF', 'CISCO SYSTEMS, INC.'),
(11502, '00:90:C0', 'K.J. LAW ENGINEERS, INC.'),
(11503, '00:90:C1', 'Peco II, Inc.'),
(11504, '00:90:C2', 'JK microsystems, Inc.'),
(11505, '00:90:C3', 'TOPIC SEMICONDUCTOR CORP.'),
(11506, '00:90:C4', 'JAVELIN SYSTEMS, INC.'),
(11507, '00:90:C5', 'INTERNET MAGIC, INC.'),
(11508, '00:90:C6', 'OPTIM SYSTEMS, INC.'),
(11509, '00:90:C7', 'ICOM INC.'),
(11510, '00:90:C8', 'WAVERIDER COMMUNICATIONS (CANADA) INC.'),
(11511, '00:90:C9', 'DPAC Technologies'),
(11512, '00:90:CA', 'ACCORD VIDEO TELECOMMUNICATIONS, LTD.'),
(11513, '00:90:CB', 'Wireless OnLine, Inc.'),
(11514, '00:90:CC', 'Planex Communications'),
(11515, '00:90:CD', 'ENT-EMPRESA NACIONAL DE TELECOMMUNICACOES, S.A.'),
(11516, '00:90:CE', 'TETRA GmbH'),
(11517, '00:90:CF', 'NORTEL'),
(11518, '00:90:D0', 'Thomson Telecom Belgium'),
(11519, '00:90:D1', 'LEICHU ENTERPRISE CO., LTD.'),
(11520, '00:90:D2', 'ARTEL VIDEO SYSTEMS'),
(11521, '00:90:D3', 'GIESECKE &amp; DEVRIENT GmbH'),
(11522, '00:90:D4', 'BindView Development Corp.'),
(11523, '00:90:D5', 'EUPHONIX, INC.'),
(11524, '00:90:D6', 'CRYSTAL GROUP'),
(11525, '00:90:D7', 'NetBoost Corp.'),
(11526, '00:90:D8', 'WHITECROSS SYSTEMS'),
(11527, '00:90:D9', 'CISCO SYSTEMS, INC.'),
(11528, '00:90:DA', 'DYNARC, INC.'),
(11529, '00:90:DB', 'NEXT LEVEL COMMUNICATIONS'),
(11530, '00:90:DC', 'TECO INFORMATION SYSTEMS'),
(11531, '00:90:DD', 'MIHARU COMMUNICATIONS Inc'),
(11532, '00:90:DE', 'CARDKEY SYSTEMS, INC.'),
(11533, '00:90:DF', 'MITSUBISHI CHEMICAL AMERICA, INC.'),
(11534, '00:90:E0', 'SYSTRAN CORP.'),
(11535, '00:90:E1', 'TELENA S.P.A.'),
(11536, '00:90:E2', 'DISTRIBUTED PROCESSING TECHNOLOGY'),
(11537, '00:90:E3', 'AVEX ELECTRONICS INC.'),
(11538, '00:90:E4', 'NEC AMERICA, INC.'),
(11539, '00:90:E5', 'TEKNEMA, INC.'),
(11540, '00:90:E6', 'ALi Corporation'),
(11541, '00:90:E7', 'HORSCH ELEKTRONIK AG'),
(11542, '00:90:E8', 'MOXA TECHNOLOGIES CORP., LTD.'),
(11543, '00:90:E9', 'JANZ COMPUTER AG'),
(11544, '00:90:EA', 'ALPHA TECHNOLOGIES, INC.'),
(11545, '00:90:EB', 'SENTRY TELECOM SYSTEMS'),
(11546, '00:90:EC', 'PYRESCOM'),
(11547, '00:90:ED', 'CENTRAL SYSTEM RESEARCH CO., LTD.'),
(11548, '00:90:EE', 'PERSONAL COMMUNICATIONS TECHNOLOGIES'),
(11549, '00:90:EF', 'INTEGRIX, INC.'),
(11550, '00:90:F0', 'Harmonic Video Systems Ltd.'),
(11551, '00:90:F1', 'DOT HILL SYSTEMS CORPORATION'),
(11552, '00:90:F2', 'CISCO SYSTEMS, INC.'),
(11553, '00:90:F3', 'ASPECT COMMUNICATIONS'),
(11554, '00:90:F4', 'LIGHTNING INSTRUMENTATION'),
(11555, '00:90:F5', 'CLEVO CO.'),
(11556, '00:90:F6', 'ESCALATE NETWORKS, INC.'),
(11557, '00:90:F7', 'NBASE COMMUNICATIONS LTD.'),
(11558, '00:90:F8', 'MEDIATRIX TELECOM'),
(11559, '00:90:F9', 'LEITCH'),
(11560, '00:90:FA', 'Emulex Corporation'),
(11561, '00:90:FB', 'PORTWELL, INC.'),
(11562, '00:90:FC', 'NETWORK COMPUTING DEVICES'),
(11563, '00:90:FD', 'CopperCom, Inc.'),
(11564, '00:90:FE', 'ELECOM CO., LTD.  (LANEED DIV.)'),
(11565, '00:90:FF', 'TELLUS TECHNOLOGY INC.'),
(11566, '00:91:D6', 'Crystal Group, Inc.'),
(11567, '00:91:FA', 'Synapse Product Development'),
(11568, '00:92:FA', 'SHENZHEN WISKY TECHNOLOGY CO.,LTD'),
(11569, '00:93:63', 'Uni-Link Technology Co., Ltd.'),
(11570, '00:95:69', 'LSD Science and Technology Co.,Ltd.'),
(11571, '00:97:FF', 'Heimann Sensor GmbH'),
(11572, '00:9C:02', 'Hewlett-Packard Company'),
(11573, '00:9D:8E', 'CARDIAC RECORDERS, INC.'),
(11574, '00:9E:C8', 'Beijing Xiaomi Electronic Products Co., Ltd.'),
(11575, '00:A0:00', 'CENTILLION NETWORKS, INC.'),
(11576, '00:A0:01', 'DRS Signal Solutions'),
(11577, '00:A0:02', 'LEEDS &amp; NORTHRUP AUSTRALIA PTY LTD'),
(11578, '00:A0:03', 'Siemens Switzerland Ltd., I B T HVP'),
(11579, '00:A0:04', 'NETPOWER, INC.'),
(11580, '00:A0:05', 'DANIEL INSTRUMENTS, LTD.'),
(11581, '00:A0:06', 'IMAGE DATA PROCESSING SYSTEM GROUP'),
(11582, '00:A0:07', 'APEXX TECHNOLOGY, INC.'),
(11583, '00:A0:08', 'NETCORP'),
(11584, '00:A0:09', 'WHITETREE NETWORK'),
(11585, '00:A0:0A', 'Airspan'),
(11586, '00:A0:0B', 'COMPUTEX CO., LTD.'),
(11587, '00:A0:0C', 'KINGMAX TECHNOLOGY, INC.'),
(11588, '00:A0:0D', 'THE PANDA PROJECT'),
(11589, '00:A0:0E', 'VISUAL NETWORKS, INC.'),
(11590, '00:A0:0F', 'Broadband Technologies'),
(11591, '00:A0:10', 'SYSLOGIC DATENTECHNIK AG'),
(11592, '00:A0:11', 'MUTOH INDUSTRIES LTD.'),
(11593, '00:A0:12', 'Telco Systems, Inc.'),
(11594, '00:A0:13', 'TELTREND LTD.'),
(11595, '00:A0:14', 'CSIR'),
(11596, '00:A0:15', 'WYLE'),
(11597, '00:A0:16', 'MICROPOLIS CORP.'),
(11598, '00:A0:17', 'J B M CORPORATION'),
(11599, '00:A0:18', 'CREATIVE CONTROLLERS, INC.'),
(11600, '00:A0:19', 'NEBULA CONSULTANTS, INC.'),
(11601, '00:A0:1A', 'BINAR ELEKTRONIK AB'),
(11602, '00:A0:1B', 'PREMISYS COMMUNICATIONS, INC.'),
(11603, '00:A0:1C', 'NASCENT NETWORKS CORPORATION'),
(11604, '00:A0:1D', 'Red Lion Controls, LP'),
(11605, '00:A0:1E', 'EST CORPORATION'),
(11606, '00:A0:1F', 'TRICORD SYSTEMS, INC.'),
(11607, '00:A0:20', 'CITICORP/TTI'),
(11608, '00:A0:21', 'General Dynamics'),
(11609, '00:A0:22', 'CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING'),
(11610, '00:A0:23', 'APPLIED CREATIVE TECHNOLOGY, INC.'),
(11611, '00:A0:24', '3COM CORPORATION'),
(11612, '00:A0:25', 'REDCOM LABS INC.'),
(11613, '00:A0:26', 'TELDAT, S.A.'),
(11614, '00:A0:27', 'FIREPOWER SYSTEMS, INC.'),
(11615, '00:A0:28', 'CONNER PERIPHERALS'),
(11616, '00:A0:29', 'COULTER CORPORATION'),
(11617, '00:A0:2A', 'TRANCELL SYSTEMS'),
(11618, '00:A0:2B', 'TRANSITIONS RESEARCH CORP.'),
(11619, '00:A0:2C', 'interWAVE Communications'),
(11620, '00:A0:2D', '1394 Trade Association'),
(11621, '00:A0:2E', 'BRAND COMMUNICATIONS, LTD.'),
(11622, '00:A0:2F', 'PIRELLI CAVI'),
(11623, '00:A0:30', 'CAPTOR NV/SA'),
(11624, '00:A0:31', 'HAZELTINE CORPORATION, MS 1-17'),
(11625, '00:A0:32', 'GES SINGAPORE PTE. LTD.'),
(11626, '00:A0:33', 'imc MeBsysteme GmbH'),
(11627, '00:A0:34', 'AXEL'),
(11628, '00:A0:35', 'CYLINK CORPORATION'),
(11629, '00:A0:36', 'APPLIED NETWORK TECHNOLOGY'),
(11630, '00:A0:37', 'Mindray DS USA, Inc.'),
(11631, '00:A0:38', 'EMAIL ELECTRONICS'),
(11632, '00:A0:39', 'ROSS TECHNOLOGY, INC.'),
(11633, '00:A0:3A', 'KUBOTEK CORPORATION'),
(11634, '00:A0:3B', 'TOSHIN ELECTRIC CO., LTD.'),
(11635, '00:A0:3C', 'EG&amp;G NUCLEAR INSTRUMENTS'),
(11636, '00:A0:3D', 'OPTO-22'),
(11637, '00:A0:3E', 'ATM FORUM'),
(11638, '00:A0:3F', 'COMPUTER SOCIETY MICROPROCESSOR &amp; MICROPROCESSOR STANDARDS C'),
(11639, '00:A0:40', 'Apple'),
(11640, '00:A0:41', 'INFICON'),
(11641, '00:A0:42', 'SPUR PRODUCTS CORP.'),
(11642, '00:A0:43', 'AMERICAN TECHNOLOGY LABS, INC.'),
(11643, '00:A0:44', 'NTT IT CO., LTD.'),
(11644, '00:A0:45', 'PHOENIX CONTACT GMBH &amp; CO.'),
(11645, '00:A0:46', 'SCITEX CORP. LTD.'),
(11646, '00:A0:47', 'INTEGRATED FITNESS CORP.'),
(11647, '00:A0:48', 'QUESTECH, LTD.'),
(11648, '00:A0:49', 'DIGITECH INDUSTRIES, INC.'),
(11649, '00:A0:4A', 'NISSHIN ELECTRIC CO., LTD.'),
(11650, '00:A0:4B', 'TFL LAN INC.'),
(11651, '00:A0:4C', 'INNOVATIVE SYSTEMS &amp; TECHNOLOGIES, INC.'),
(11652, '00:A0:4D', 'EDA INSTRUMENTS, INC.'),
(11653, '00:A0:4E', 'VOELKER TECHNOLOGIES, INC.'),
(11654, '00:A0:4F', 'AMERITEC CORP.'),
(11655, '00:A0:50', 'CYPRESS SEMICONDUCTOR'),
(11656, '00:A0:51', 'ANGIA COMMUNICATIONS. INC.'),
(11657, '00:A0:52', 'STANILITE ELECTRONICS PTY. LTD'),
(11658, '00:A0:53', 'COMPACT DEVICES, INC.'),
(11659, '00:A0:54', 'PRIVATE'),
(11660, '00:A0:55', 'Data Device Corporation'),
(11661, '00:A0:56', 'MICROPROSS'),
(11662, '00:A0:57', 'LANCOM Systems GmbH'),
(11663, '00:A0:58', 'GLORY, LTD.'),
(11664, '00:A0:59', 'HAMILTON HALLMARK'),
(11665, '00:A0:5A', 'KOFAX IMAGE PRODUCTS'),
(11666, '00:A0:5B', 'MARQUIP, INC.'),
(11667, '00:A0:5C', 'INVENTORY CONVERSION, INC./'),
(11668, '00:A0:5D', 'CS COMPUTER SYSTEME GmbH'),
(11669, '00:A0:5E', 'MYRIAD LOGIC INC.'),
(11670, '00:A0:5F', 'BTG Electronics Design BV'),
(11671, '00:A0:60', 'ACER PERIPHERALS, INC.'),
(11672, '00:A0:61', 'PURITAN BENNETT'),
(11673, '00:A0:62', 'AES PRODATA'),
(11674, '00:A0:63', 'JRL SYSTEMS, INC.'),
(11675, '00:A0:64', 'KVB/ANALECT'),
(11676, '00:A0:65', 'Symantec Corporation'),
(11677, '00:A0:66', 'ISA CO., LTD.'),
(11678, '00:A0:67', 'NETWORK SERVICES GROUP'),
(11679, '00:A0:68', 'BHP LIMITED'),
(11680, '00:A0:69', 'Symmetricom, Inc.'),
(11681, '00:A0:6A', 'Verilink Corporation'),
(11682, '00:A0:6B', 'DMS DORSCH MIKROSYSTEM GMBH'),
(11683, '00:A0:6C', 'SHINDENGEN ELECTRIC MFG. CO., LTD.'),
(11684, '00:A0:6D', 'MANNESMANN TALLY CORPORATION'),
(11685, '00:A0:6E', 'AUSTRON, INC.'),
(11686, '00:A0:6F', 'THE APPCON GROUP, INC.'),
(11687, '00:A0:70', 'COASTCOM'),
(11688, '00:A0:71', 'VIDEO LOTTERY TECHNOLOGIES,INC'),
(11689, '00:A0:72', 'OVATION SYSTEMS LTD.'),
(11690, '00:A0:73', 'COM21, INC.'),
(11691, '00:A0:74', 'PERCEPTION TECHNOLOGY'),
(11692, '00:A0:75', 'MICRON TECHNOLOGY, INC.'),
(11693, '00:A0:76', 'CARDWARE LAB, INC.'),
(11694, '00:A0:77', 'FUJITSU NEXION, INC.'),
(11695, '00:A0:78', 'Marconi Communications'),
(11696, '00:A0:79', 'ALPS ELECTRIC (USA), INC.'),
(11697, '00:A0:7A', 'ADVANCED PERIPHERALS TECHNOLOGIES, INC.'),
(11698, '00:A0:7B', 'DAWN COMPUTER INCORPORATION'),
(11699, '00:A0:7C', 'TONYANG NYLON CO., LTD.'),
(11700, '00:A0:7D', 'SEEQ TECHNOLOGY, INC.'),
(11701, '00:A0:7E', 'AVID TECHNOLOGY, INC.'),
(11702, '00:A0:7F', 'GSM-SYNTEL, LTD.'),
(11703, '00:A0:80', 'Tattile SRL'),
(11704, '00:A0:81', 'ALCATEL DATA NETWORKS'),
(11705, '00:A0:82', 'NKT ELEKTRONIK A/S'),
(11706, '00:A0:83', 'ASIMMPHONY TURKEY'),
(11707, '00:A0:84', 'Dataplex Pty Ltd'),
(11708, '00:A0:85', 'PRIVATE'),
(11709, '00:A0:86', 'AMBER WAVE SYSTEMS, INC.'),
(11710, '00:A0:87', 'Microsemi Corporation'),
(11711, '00:A0:88', 'ESSENTIAL COMMUNICATIONS'),
(11712, '00:A0:89', 'XPOINT TECHNOLOGIES, INC.'),
(11713, '00:A0:8A', 'BROOKTROUT TECHNOLOGY, INC.'),
(11714, '00:A0:8B', 'ASTON ELECTRONIC DESIGNS LTD.'),
(11715, '00:A0:8C', 'MultiMedia LANs, Inc.'),
(11716, '00:A0:8D', 'JACOMO CORPORATION'),
(11717, '00:A0:8E', 'Check Point Software Technologies'),
(11718, '00:A0:8F', 'DESKNET SYSTEMS, INC.'),
(11719, '00:A0:90', 'TimeStep Corporation'),
(11720, '00:A0:91', 'APPLICOM INTERNATIONAL'),
(11721, '00:A0:92', 'H. BOLLMANN MANUFACTURERS, LTD'),
(11722, '00:A0:93', 'B/E AEROSPACE, Inc.'),
(11723, '00:A0:94', 'COMSAT CORPORATION'),
(11724, '00:A0:95', 'ACACIA NETWORKS, INC.'),
(11725, '00:A0:96', 'MITSUMI ELECTRIC CO., LTD.'),
(11726, '00:A0:97', 'JC INFORMATION SYSTEMS'),
(11727, '00:A0:98', 'NetApp'),
(11728, '00:A0:99', 'K-NET LTD.'),
(11729, '00:A0:9A', 'NIHON KOHDEN AMERICA'),
(11730, '00:A0:9B', 'QPSX COMMUNICATIONS, LTD.'),
(11731, '00:A0:9C', 'Xyplex, Inc.'),
(11732, '00:A0:9D', 'JOHNATHON FREEMAN TECHNOLOGIES'),
(11733, '00:A0:9E', 'ICTV'),
(11734, '00:A0:9F', 'COMMVISION CORP.'),
(11735, '00:A0:A0', 'COMPACT DATA, LTD.'),
(11736, '00:A0:A1', 'EPIC DATA INC.'),
(11737, '00:A0:A2', 'DIGICOM S.P.A.'),
(11738, '00:A0:A3', 'RELIABLE POWER METERS'),
(11739, '00:A0:A4', 'MICROS SYSTEMS, INC.'),
(11740, '00:A0:A5', 'TEKNOR MICROSYSTEME, INC.'),
(11741, '00:A0:A6', 'M.I. SYSTEMS, K.K.'),
(11742, '00:A0:A7', 'VORAX CORPORATION'),
(11743, '00:A0:A8', 'RENEX CORPORATION'),
(11744, '00:A0:A9', 'NAVTEL COMMUNICATIONS INC.'),
(11745, '00:A0:AA', 'SPACELABS MEDICAL'),
(11746, '00:A0:AB', 'NETCS INFORMATIONSTECHNIK GMBH'),
(11747, '00:A0:AC', 'GILAT SATELLITE NETWORKS, LTD.'),
(11748, '00:A0:AD', 'MARCONI SPA'),
(11749, '00:A0:AE', 'NUCOM SYSTEMS, INC.'),
(11750, '00:A0:AF', 'WMS INDUSTRIES'),
(11751, '00:A0:B0', 'I-O DATA DEVICE, INC.'),
(11752, '00:A0:B1', 'FIRST VIRTUAL CORPORATION'),
(11753, '00:A0:B2', 'SHIMA SEIKI'),
(11754, '00:A0:B3', 'ZYKRONIX'),
(11755, '00:A0:B4', 'TEXAS MICROSYSTEMS, INC.'),
(11756, '00:A0:B5', '3H TECHNOLOGY'),
(11757, '00:A0:B6', 'SANRITZ AUTOMATION CO., LTD.'),
(11758, '00:A0:B7', 'CORDANT, INC.'),
(11759, '00:A0:B8', 'SYMBIOS LOGIC INC.'),
(11760, '00:A0:B9', 'EAGLE TECHNOLOGY, INC.'),
(11761, '00:A0:BA', 'PATTON ELECTRONICS CO.'),
(11762, '00:A0:BB', 'HILAN GMBH'),
(11763, '00:A0:BC', 'VIASAT, INCORPORATED'),
(11764, '00:A0:BD', 'I-TECH CORP.'),
(11765, '00:A0:BE', 'INTEGRATED CIRCUIT SYSTEMS, INC. COMMUNICATIONS GROUP'),
(11766, '00:A0:BF', 'WIRELESS DATA GROUP MOTOROLA'),
(11767, '00:A0:C0', 'DIGITAL LINK CORP.'),
(11768, '00:A0:C1', 'ORTIVUS MEDICAL AB'),
(11769, '00:A0:C2', 'R.A. SYSTEMS CO., LTD.'),
(11770, '00:A0:C3', 'UNICOMPUTER GMBH'),
(11771, '00:A0:C4', 'CRISTIE ELECTRONICS LTD.'),
(11772, '00:A0:C5', 'ZYXEL COMMUNICATION'),
(11773, '00:A0:C6', 'QUALCOMM INCORPORATED'),
(11774, '00:A0:C7', 'TADIRAN TELECOMMUNICATIONS'),
(11775, '00:A0:C8', 'ADTRAN INC.'),
(11776, '00:A0:C9', 'INTEL CORPORATION - HF1-06'),
(11777, '00:A0:CA', 'FUJITSU DENSO LTD.'),
(11778, '00:A0:CB', 'ARK TELECOMMUNICATIONS, INC.'),
(11779, '00:A0:CC', 'LITE-ON COMMUNICATIONS, INC.'),
(11780, '00:A0:CD', 'DR. JOHANNES HEIDENHAIN GmbH'),
(11781, '00:A0:CE', 'Ecessa'),
(11782, '00:A0:CF', 'SOTAS, INC.'),
(11783, '00:A0:D0', 'TEN X TECHNOLOGY, INC.'),
(11784, '00:A0:D1', 'INVENTEC CORPORATION'),
(11785, '00:A0:D2', 'ALLIED TELESIS INTERNATIONAL CORPORATION'),
(11786, '00:A0:D3', 'INSTEM COMPUTER SYSTEMS, LTD.'),
(11787, '00:A0:D4', 'RADIOLAN,  INC.'),
(11788, '00:A0:D5', 'SIERRA WIRELESS INC.'),
(11789, '00:A0:D6', 'SBE, INC.'),
(11790, '00:A0:D7', 'KASTEN CHASE APPLIED RESEARCH'),
(11791, '00:A0:D8', 'SPECTRA - TEK'),
(11792, '00:A0:D9', 'CONVEX COMPUTER CORPORATION'),
(11793, '00:A0:DA', 'INTEGRATED SYSTEMS Technology, Inc.'),
(11794, '00:A0:DB', 'FISHER &amp; PAYKEL PRODUCTION'),
(11795, '00:A0:DC', 'O.N. ELECTRONIC CO., LTD.'),
(11796, '00:A0:DD', 'AZONIX CORPORATION'),
(11797, '00:A0:DE', 'YAMAHA CORPORATION'),
(11798, '00:A0:DF', 'STS TECHNOLOGIES, INC.'),
(11799, '00:A0:E0', 'TENNYSON TECHNOLOGIES PTY LTD'),
(11800, '00:A0:E1', 'WESTPORT RESEARCH ASSOCIATES, INC.'),
(11801, '00:A0:E2', 'Keisokugiken Corporation'),
(11802, '00:A0:E3', 'XKL SYSTEMS CORP.'),
(11803, '00:A0:E4', 'OPTIQUEST'),
(11804, '00:A0:E5', 'NHC COMMUNICATIONS'),
(11805, '00:A0:E6', 'DIALOGIC CORPORATION'),
(11806, '00:A0:E7', 'CENTRAL DATA CORPORATION'),
(11807, '00:A0:E8', 'REUTERS HOLDINGS PLC'),
(11808, '00:A0:E9', 'ELECTRONIC RETAILING SYSTEMS INTERNATIONAL'),
(11809, '00:A0:EA', 'ETHERCOM CORP.'),
(11810, '00:A0:EB', 'Encore Networks, Inc.'),
(11811, '00:A0:EC', 'TRANSMITTON LTD.'),
(11812, '00:A0:ED', 'Brooks Automation, Inc.'),
(11813, '00:A0:EE', 'NASHOBA NETWORKS'),
(11814, '00:A0:EF', 'LUCIDATA LTD.'),
(11815, '00:A0:F0', 'TORONTO MICROELECTRONICS INC.'),
(11816, '00:A0:F1', 'MTI'),
(11817, '00:A0:F2', 'INFOTEK COMMUNICATIONS, INC.'),
(11818, '00:A0:F3', 'STAUBLI'),
(11819, '00:A0:F4', 'GE'),
(11820, '00:A0:F5', 'RADGUARD LTD.'),
(11821, '00:A0:F6', 'AutoGas Systems Inc.'),
(11822, '00:A0:F7', 'V.I COMPUTER CORP.'),
(11823, '00:A0:F8', 'Zebra Technologies Inc'),
(11824, '00:A0:F9', 'BINTEC COMMUNICATIONS GMBH'),
(11825, '00:A0:FA', 'Marconi Communication GmbH'),
(11826, '00:A0:FB', 'TORAY ENGINEERING CO., LTD.'),
(11827, '00:A0:FC', 'IMAGE SCIENCES, INC.'),
(11828, '00:A0:FD', 'SCITEX DIGITAL PRINTING, INC.'),
(11829, '00:A0:FE', 'BOSTON TECHNOLOGY, INC.'),
(11830, '00:A0:FF', 'TELLABS OPERATIONS, INC.'),
(11831, '00:A1:DE', 'ShenZhen ShiHua Technology CO.,LTD'),
(11832, '00:A2:DA', 'INAT GmbH'),
(11833, '00:A2:F5', 'Guangzhou Yuanyun Network Technology Co.,Ltd'),
(11834, '00:A2:FF', 'abatec group AG'),
(11835, '00:A5:09', 'WigWag Inc.'),
(11836, '00:AA:00', 'INTEL CORPORATION'),
(11837, '00:AA:01', 'INTEL CORPORATION'),
(11838, '00:AA:02', 'INTEL CORPORATION'),
(11839, '00:AA:3C', 'OLIVETTI TELECOM SPA (OLTECO)'),
(11840, '00:AA:70', 'LG Electronics'),
(11841, '00:AC:E0', 'ARRIS Group, Inc.'),
(11842, '00:AE:FA', 'Murata Manufacturing Co., Ltd.'),
(11843, '00:B0:09', 'Grass Valley Group'),
(11844, '00:B0:17', 'InfoGear Technology Corp.'),
(11845, '00:B0:19', 'UTC CCS'),
(11846, '00:B0:1C', 'Westport Technologies'),
(11847, '00:B0:1E', 'Rantic Labs, Inc.'),
(11848, '00:B0:2A', 'ORSYS GmbH'),
(11849, '00:B0:2D', 'ViaGate Technologies, Inc.'),
(11850, '00:B0:33', 'OAO &quot;Izhevskiy radiozavod&quot;'),
(11851, '00:B0:3B', 'HiQ Networks'),
(11852, '00:B0:48', 'Marconi Communications Inc.'),
(11853, '00:B0:4A', 'CISCO SYSTEMS, INC.'),
(11854, '00:B0:52', 'Atheros Communications'),
(11855, '00:B0:64', 'CISCO SYSTEMS, INC.'),
(11856, '00:B0:69', 'Honewell Oy'),
(11857, '00:B0:6D', 'Jones Futurex Inc.'),
(11858, '00:B0:80', 'Mannesmann Ipulsys B.V.'),
(11859, '00:B0:86', 'LocSoft Limited'),
(11860, '00:B0:8E', 'CISCO SYSTEMS, INC.'),
(11861, '00:B0:91', 'Transmeta Corp.'),
(11862, '00:B0:94', 'Alaris, Inc.'),
(11863, '00:B0:9A', 'Morrow Technologies Corp.'),
(11864, '00:B0:9D', 'Point Grey Research Inc.'),
(11865, '00:B0:AC', 'SIAE-Microelettronica S.p.A.'),
(11866, '00:B0:AE', 'Symmetricom'),
(11867, '00:B0:B3', 'Xstreamis PLC'),
(11868, '00:B0:C2', 'CISCO SYSTEMS, INC.'),
(11869, '00:B0:C7', 'Tellabs Operations, Inc.'),
(11870, '00:B0:CE', 'TECHNOLOGY RESCUE'),
(11871, '00:B0:D0', 'Dell Computer Corp.'),
(11872, '00:B0:DB', 'Nextcell, Inc.'),
(11873, '00:B0:DF', 'Starboard Storage Systems'),
(11874, '00:B0:E7', 'British Federal Ltd.'),
(11875, '00:B0:EC', 'EACEM'),
(11876, '00:B0:EE', 'Ajile Systems, Inc.'),
(11877, '00:B0:F0', 'CALY NETWORKS'),
(11878, '00:B0:F5', 'NetWorth Technologies, Inc.'),
(11879, '00:B3:38', 'Kontron Design Manufacturing Services (M) Sdn. Bhd'),
(11880, '00:B3:42', 'MacroSAN Technologies Co., Ltd.'),
(11881, '00:B5:6D', 'David Electronics Co., LTD.'),
(11882, '00:B5:D6', 'Omnibit Inc.'),
(11883, '00:B7:8D', 'Nanjing Shining Electric Automation Co., Ltd'),
(11884, '00:B9:F6', 'Shenzhen Super Rich Electronics Co.,Ltd'),
(11885, '00:BA:C0', 'Biometric Access Company'),
(11886, '00:BB:01', 'OCTOTHORPE CORP.'),
(11887, '00:BB:3A', 'PRIVATE'),
(11888, '00:BB:8E', 'HME Co., Ltd.'),
(11889, '00:BB:F0', 'UNGERMANN-BASS INC.'),
(11890, '00:BD:27', 'Exar Corp.'),
(11891, '00:BD:3A', 'Nokia Corporation'),
(11892, '00:BF:15', 'Genetec Inc.'),
(11893, '00:C0:00', 'LANOPTICS, LTD.'),
(11894, '00:C0:01', 'DIATEK PATIENT MANAGMENT'),
(11895, '00:C0:02', 'SERCOMM CORPORATION'),
(11896, '00:C0:03', 'GLOBALNET COMMUNICATIONS'),
(11897, '00:C0:04', 'JAPAN BUSINESS COMPUTER CO.LTD'),
(11898, '00:C0:05', 'LIVINGSTON ENTERPRISES, INC.'),
(11899, '00:C0:06', 'NIPPON AVIONICS CO., LTD.'),
(11900, '00:C0:07', 'PINNACLE DATA SYSTEMS, INC.'),
(11901, '00:C0:08', 'SECO SRL'),
(11902, '00:C0:09', 'KT TECHNOLOGY (S) PTE LTD'),
(11903, '00:C0:0A', 'MICRO CRAFT'),
(11904, '00:C0:0B', 'NORCONTROL A.S.'),
(11905, '00:C0:0C', 'RELIA TECHNOLGIES'),
(11906, '00:C0:0D', 'ADVANCED LOGIC RESEARCH, INC.'),
(11907, '00:C0:0E', 'PSITECH, INC.'),
(11908, '00:C0:0F', 'QUANTUM SOFTWARE SYSTEMS LTD.'),
(11909, '00:C0:10', 'HIRAKAWA HEWTECH CORP.'),
(11910, '00:C0:11', 'INTERACTIVE COMPUTING DEVICES'),
(11911, '00:C0:12', 'NETSPAN CORPORATION'),
(11912, '00:C0:13', 'NETRIX'),
(11913, '00:C0:14', 'TELEMATICS CALABASAS INT\'L,INC'),
(11914, '00:C0:15', 'NEW MEDIA CORPORATION'),
(11915, '00:C0:16', 'ELECTRONIC THEATRE CONTROLS'),
(11916, '00:C0:17', 'Fluke Corporation'),
(11917, '00:C0:18', 'LANART CORPORATION'),
(11918, '00:C0:19', 'LEAP TECHNOLOGY, INC.'),
(11919, '00:C0:1A', 'COROMETRICS MEDICAL SYSTEMS'),
(11920, '00:C0:1B', 'SOCKET COMMUNICATIONS, INC.'),
(11921, '00:C0:1C', 'INTERLINK COMMUNICATIONS LTD.'),
(11922, '00:C0:1D', 'GRAND JUNCTION NETWORKS, INC.'),
(11923, '00:C0:1E', 'LA FRANCAISE DES JEUX'),
(11924, '00:C0:1F', 'S.E.R.C.E.L.'),
(11925, '00:C0:20', 'ARCO ELECTRONIC, CONTROL LTD.'),
(11926, '00:C0:21', 'NETEXPRESS'),
(11927, '00:C0:22', 'LASERMASTER TECHNOLOGIES, INC.'),
(11928, '00:C0:23', 'TUTANKHAMON ELECTRONICS'),
(11929, '00:C0:24', 'EDEN SISTEMAS DE COMPUTACAO SA'),
(11930, '00:C0:25', 'DATAPRODUCTS CORPORATION'),
(11931, '00:C0:26', 'LANS TECHNOLOGY CO., LTD.'),
(11932, '00:C0:27', 'CIPHER SYSTEMS, INC.'),
(11933, '00:C0:28', 'JASCO CORPORATION'),
(11934, '00:C0:29', 'Nexans Deutschland GmbH - ANS'),
(11935, '00:C0:2A', 'OHKURA ELECTRIC CO., LTD.'),
(11936, '00:C0:2B', 'GERLOFF GESELLSCHAFT FUR'),
(11937, '00:C0:2C', 'CENTRUM COMMUNICATIONS, INC.'),
(11938, '00:C0:2D', 'FUJI PHOTO FILM CO., LTD.'),
(11939, '00:C0:2E', 'NETWIZ'),
(11940, '00:C0:2F', 'OKUMA CORPORATION'),
(11941, '00:C0:30', 'INTEGRATED ENGINEERING B. V.'),
(11942, '00:C0:31', 'DESIGN RESEARCH SYSTEMS, INC.'),
(11943, '00:C0:32', 'I-CUBED LIMITED'),
(11944, '00:C0:33', 'TELEBIT COMMUNICATIONS APS'),
(11945, '00:C0:34', 'TRANSACTION NETWORK'),
(11946, '00:C0:35', 'QUINTAR COMPANY'),
(11947, '00:C0:36', 'RAYTECH ELECTRONIC CORP.'),
(11948, '00:C0:37', 'DYNATEM'),
(11949, '00:C0:38', 'RASTER IMAGE PROCESSING SYSTEM'),
(11950, '00:C0:39', 'Teridian Semiconductor Corporation'),
(11951, '00:C0:3A', 'MEN-MIKRO ELEKTRONIK GMBH'),
(11952, '00:C0:3B', 'MULTIACCESS COMPUTING CORP.'),
(11953, '00:C0:3C', 'TOWER TECH S.R.L.'),
(11954, '00:C0:3D', 'WIESEMANN &amp; THEIS GMBH'),
(11955, '00:C0:3E', 'FA. GEBR. HELLER GMBH'),
(11956, '00:C0:3F', 'STORES AUTOMATED SYSTEMS, INC.'),
(11957, '00:C0:40', 'ECCI'),
(11958, '00:C0:41', 'DIGITAL TRANSMISSION SYSTEMS'),
(11959, '00:C0:42', 'DATALUX CORP.'),
(11960, '00:C0:43', 'STRATACOM'),
(11961, '00:C0:44', 'EMCOM CORPORATION'),
(11962, '00:C0:45', 'ISOLATION SYSTEMS, LTD.'),
(11963, '00:C0:46', 'Blue Chip Technology Ltd'),
(11964, '00:C0:47', 'UNIMICRO SYSTEMS, INC.'),
(11965, '00:C0:48', 'BAY TECHNICAL ASSOCIATES'),
(11966, '00:C0:49', 'U.S. ROBOTICS, INC.'),
(11967, '00:C0:4A', 'GROUP 2000 AG'),
(11968, '00:C0:4B', 'CREATIVE MICROSYSTEMS'),
(11969, '00:C0:4C', 'DEPARTMENT OF FOREIGN AFFAIRS'),
(11970, '00:C0:4D', 'MITEC, INC.'),
(11971, '00:C0:4E', 'COMTROL CORPORATION'),
(11972, '00:C0:4F', 'DELL COMPUTER CORPORATION'),
(11973, '00:C0:50', 'TOYO DENKI SEIZO K.K.'),
(11974, '00:C0:51', 'ADVANCED INTEGRATION RESEARCH'),
(11975, '00:C0:52', 'BURR-BROWN'),
(11976, '00:C0:53', 'Aspect Software Inc.'),
(11977, '00:C0:54', 'NETWORK PERIPHERALS, LTD.'),
(11978, '00:C0:55', 'MODULAR COMPUTING TECHNOLOGIES'),
(11979, '00:C0:56', 'SOMELEC'),
(11980, '00:C0:57', 'MYCO ELECTRONICS'),
(11981, '00:C0:58', 'DATAEXPERT CORP.'),
(11982, '00:C0:59', 'DENSO CORPORATION'),
(11983, '00:C0:5A', 'SEMAPHORE COMMUNICATIONS CORP.'),
(11984, '00:C0:5B', 'NETWORKS NORTHWEST, INC.'),
(11985, '00:C0:5C', 'ELONEX PLC'),
(11986, '00:C0:5D', 'L&amp;N TECHNOLOGIES'),
(11987, '00:C0:5E', 'VARI-LITE, INC.'),
(11988, '00:C0:5F', 'FINE-PAL COMPANY LIMITED'),
(11989, '00:C0:60', 'ID SCANDINAVIA AS'),
(11990, '00:C0:61', 'SOLECTEK CORPORATION'),
(11991, '00:C0:62', 'IMPULSE TECHNOLOGY'),
(11992, '00:C0:63', 'MORNING STAR TECHNOLOGIES, INC'),
(11993, '00:C0:64', 'GENERAL DATACOMM IND. INC.'),
(11994, '00:C0:65', 'SCOPE COMMUNICATIONS, INC.'),
(11995, '00:C0:66', 'DOCUPOINT, INC.'),
(11996, '00:C0:67', 'UNITED BARCODE INDUSTRIES'),
(11997, '00:C0:68', 'HME Clear-Com LTD.'),
(11998, '00:C0:69', 'Axxcelera Broadband Wireless'),
(11999, '00:C0:6A', 'ZAHNER-ELEKTRIK GMBH &amp; CO. KG'),
(12000, '00:C0:6B', 'OSI PLUS CORPORATION'),
(12001, '00:C0:6C', 'SVEC COMPUTER CORP.'),
(12002, '00:C0:6D', 'BOCA RESEARCH, INC.'),
(12003, '00:C0:6E', 'HAFT TECHNOLOGY, INC.'),
(12004, '00:C0:6F', 'KOMATSU LTD.'),
(12005, '00:C0:70', 'SECTRA SECURE-TRANSMISSION AB'),
(12006, '00:C0:71', 'AREANEX COMMUNICATIONS, INC.'),
(12007, '00:C0:72', 'KNX LTD.'),
(12008, '00:C0:73', 'XEDIA CORPORATION'),
(12009, '00:C0:74', 'TOYODA AUTOMATIC LOOM'),
(12010, '00:C0:75', 'XANTE CORPORATION'),
(12011, '00:C0:76', 'I-DATA INTERNATIONAL A-S'),
(12012, '00:C0:77', 'DAEWOO TELECOM LTD.'),
(12013, '00:C0:78', 'COMPUTER SYSTEMS ENGINEERING'),
(12014, '00:C0:79', 'FONSYS CO.,LTD.'),
(12015, '00:C0:7A', 'PRIVA B.V.'),
(12016, '00:C0:7B', 'ASCEND COMMUNICATIONS, INC.'),
(12017, '00:C0:7C', 'HIGHTECH INFORMATION'),
(12018, '00:C0:7D', 'RISC DEVELOPMENTS LTD.'),
(12019, '00:C0:7E', 'KUBOTA CORPORATION ELECTRONIC'),
(12020, '00:C0:7F', 'NUPON COMPUTING CORP.'),
(12021, '00:C0:80', 'NETSTAR, INC.'),
(12022, '00:C0:81', 'METRODATA LTD.'),
(12023, '00:C0:82', 'MOORE PRODUCTS CO.'),
(12024, '00:C0:83', 'TRACE MOUNTAIN PRODUCTS, INC.'),
(12025, '00:C0:84', 'DATA LINK CORP. LTD.'),
(12026, '00:C0:85', 'ELECTRONICS FOR IMAGING, INC.'),
(12027, '00:C0:86', 'THE LYNK CORPORATION'),
(12028, '00:C0:87', 'UUNET TECHNOLOGIES, INC.'),
(12029, '00:C0:88', 'EKF ELEKTRONIK GMBH'),
(12030, '00:C0:89', 'TELINDUS DISTRIBUTION'),
(12031, '00:C0:8A', 'Lauterbach GmbH'),
(12032, '00:C0:8B', 'RISQ MODULAR SYSTEMS, INC.'),
(12033, '00:C0:8C', 'PERFORMANCE TECHNOLOGIES, INC.'),
(12034, '00:C0:8D', 'TRONIX PRODUCT DEVELOPMENT'),
(12035, '00:C0:8E', 'NETWORK INFORMATION TECHNOLOGY'),
(12036, '00:C0:8F', 'Panasonic Electric Works Co., Ltd.'),
(12037, '00:C0:90', 'PRAIM S.R.L.'),
(12038, '00:C0:91', 'JABIL CIRCUIT, INC.'),
(12039, '00:C0:92', 'MENNEN MEDICAL INC.'),
(12040, '00:C0:93', 'ALTA RESEARCH CORP.'),
(12041, '00:C0:94', 'VMX INC.'),
(12042, '00:C0:95', 'ZNYX'),
(12043, '00:C0:96', 'TAMURA CORPORATION'),
(12044, '00:C0:97', 'ARCHIPEL SA'),
(12045, '00:C0:98', 'CHUNTEX ELECTRONIC CO., LTD.'),
(12046, '00:C0:99', 'YOSHIKI INDUSTRIAL CO.,LTD.'),
(12047, '00:C0:9A', 'PHOTONICS CORPORATION'),
(12048, '00:C0:9B', 'RELIANCE COMM/TEC, R-TEC'),
(12049, '00:C0:9C', 'HIOKI E.E. CORPORATION'),
(12050, '00:C0:9D', 'DISTRIBUTED SYSTEMS INT\'L, INC'),
(12051, '00:C0:9E', 'CACHE COMPUTERS, INC.'),
(12052, '00:C0:9F', 'QUANTA COMPUTER, INC.'),
(12053, '00:C0:A0', 'ADVANCE MICRO RESEARCH, INC.'),
(12054, '00:C0:A1', 'TOKYO DENSHI SEKEI CO.'),
(12055, '00:C0:A2', 'INTERMEDIUM A/S'),
(12056, '00:C0:A3', 'DUAL ENTERPRISES CORPORATION'),
(12057, '00:C0:A4', 'UNIGRAF OY'),
(12058, '00:C0:A5', 'DICKENS DATA SYSTEMS'),
(12059, '00:C0:A6', 'EXICOM AUSTRALIA PTY. LTD'),
(12060, '00:C0:A7', 'SEEL LTD.'),
(12061, '00:C0:A8', 'GVC CORPORATION'),
(12062, '00:C0:A9', 'BARRON MCCANN LTD.'),
(12063, '00:C0:AA', 'SILICON VALLEY COMPUTER'),
(12064, '00:C0:AB', 'Telco Systems, Inc.'),
(12065, '00:C0:AC', 'GAMBIT COMPUTER COMMUNICATIONS'),
(12066, '00:C0:AD', 'MARBEN COMMUNICATION SYSTEMS'),
(12067, '00:C0:AE', 'TOWERCOM CO. INC. DBA PC HOUSE'),
(12068, '00:C0:AF', 'TEKLOGIX INC.'),
(12069, '00:C0:B0', 'GCC TECHNOLOGIES,INC.'),
(12070, '00:C0:B1', 'GENIUS NET CO.'),
(12071, '00:C0:B2', 'NORAND CORPORATION'),
(12072, '00:C0:B3', 'COMSTAT DATACOMM CORPORATION'),
(12073, '00:C0:B4', 'MYSON TECHNOLOGY, INC.'),
(12074, '00:C0:B5', 'CORPORATE NETWORK SYSTEMS,INC.'),
(12075, '00:C0:B6', 'Overland Storage, Inc.'),
(12076, '00:C0:B7', 'AMERICAN POWER CONVERSION CORP'),
(12077, '00:C0:B8', 'FRASER\'S HILL LTD.'),
(12078, '00:C0:B9', 'FUNK SOFTWARE, INC.'),
(12079, '00:C0:BA', 'NETVANTAGE'),
(12080, '00:C0:BB', 'FORVAL CREATIVE, INC.'),
(12081, '00:C0:BC', 'TELECOM AUSTRALIA/CSSC'),
(12082, '00:C0:BD', 'INEX TECHNOLOGIES, INC.'),
(12083, '00:C0:BE', 'ALCATEL - SEL'),
(12084, '00:C0:BF', 'TECHNOLOGY CONCEPTS, LTD.'),
(12085, '00:C0:C0', 'SHORE MICROSYSTEMS, INC.'),
(12086, '00:C0:C1', 'QUAD/GRAPHICS, INC.'),
(12087, '00:C0:C2', 'INFINITE NETWORKS LTD.'),
(12088, '00:C0:C3', 'ACUSON COMPUTED SONOGRAPHY'),
(12089, '00:C0:C4', 'COMPUTER OPERATIONAL'),
(12090, '00:C0:C5', 'SID INFORMATICA'),
(12091, '00:C0:C6', 'PERSONAL MEDIA CORP.'),
(12092, '00:C0:C7', 'SPARKTRUM MICROSYSTEMS, INC.'),
(12093, '00:C0:C8', 'MICRO BYTE PTY. LTD.'),
(12094, '00:C0:C9', 'ELSAG BAILEY PROCESS'),
(12095, '00:C0:CA', 'ALFA, INC.'),
(12096, '00:C0:CB', 'CONTROL TECHNOLOGY CORPORATION'),
(12097, '00:C0:CC', 'TELESCIENCES CO SYSTEMS, INC.'),
(12098, '00:C0:CD', 'COMELTA, S.A.'),
(12099, '00:C0:CE', 'CEI SYSTEMS &amp; ENGINEERING PTE'),
(12100, '00:C0:CF', 'IMATRAN VOIMA OY'),
(12101, '00:C0:D0', 'RATOC SYSTEM INC.'),
(12102, '00:C0:D1', 'COMTREE TECHNOLOGY CORPORATION'),
(12103, '00:C0:D2', 'SYNTELLECT, INC.'),
(12104, '00:C0:D3', 'OLYMPUS IMAGE SYSTEMS, INC.'),
(12105, '00:C0:D4', 'AXON NETWORKS, INC.'),
(12106, '00:C0:D5', 'Werbeagentur J&uuml;rgen Siebert'),
(12107, '00:C0:D6', 'J1 SYSTEMS, INC.'),
(12108, '00:C0:D7', 'TAIWAN TRADING CENTER DBA'),
(12109, '00:C0:D8', 'UNIVERSAL DATA SYSTEMS'),
(12110, '00:C0:D9', 'QUINTE NETWORK CONFIDENTIALITY'),
(12111, '00:C0:DA', 'NICE SYSTEMS LTD.'),
(12112, '00:C0:DB', 'IPC CORPORATION (PTE) LTD.'),
(12113, '00:C0:DC', 'EOS TECHNOLOGIES, INC.'),
(12114, '00:C0:DD', 'QLogic Corporation'),
(12115, '00:C0:DE', 'ZCOMM, INC.'),
(12116, '00:C0:DF', 'KYE Systems Corp.'),
(12117, '00:C0:E0', 'DSC COMMUNICATION CORP.'),
(12118, '00:C0:E1', 'SONIC SOLUTIONS'),
(12119, '00:C0:E2', 'CALCOMP, INC.'),
(12120, '00:C0:E3', 'OSITECH COMMUNICATIONS, INC.'),
(12121, '00:C0:E4', 'SIEMENS BUILDING'),
(12122, '00:C0:E5', 'GESPAC, S.A.'),
(12123, '00:C0:E6', 'Verilink Corporation'),
(12124, '00:C0:E7', 'FIBERDATA AB'),
(12125, '00:C0:E8', 'PLEXCOM, INC.'),
(12126, '00:C0:E9', 'OAK SOLUTIONS, LTD.'),
(12127, '00:C0:EA', 'ARRAY TECHNOLOGY LTD.'),
(12128, '00:C0:EB', 'SEH COMPUTERTECHNIK GMBH'),
(12129, '00:C0:EC', 'DAUPHIN TECHNOLOGY'),
(12130, '00:C0:ED', 'US ARMY ELECTRONIC'),
(12131, '00:C0:EE', 'KYOCERA CORPORATION'),
(12132, '00:C0:EF', 'ABIT CORPORATION'),
(12133, '00:C0:F0', 'KINGSTON TECHNOLOGY CORP.'),
(12134, '00:C0:F1', 'SHINKO ELECTRIC CO., LTD.'),
(12135, '00:C0:F2', 'TRANSITION NETWORKS'),
(12136, '00:C0:F3', 'NETWORK COMMUNICATIONS CORP.'),
(12137, '00:C0:F4', 'INTERLINK SYSTEM CO., LTD.'),
(12138, '00:C0:F5', 'METACOMP, INC.'),
(12139, '00:C0:F6', 'CELAN TECHNOLOGY INC.'),
(12140, '00:C0:F7', 'ENGAGE COMMUNICATION, INC.'),
(12141, '00:C0:F8', 'ABOUT COMPUTING INC.'),
(12142, '00:C0:F9', 'Artesyn Embedded Technologies'),
(12143, '00:C0:FA', 'CANARY COMMUNICATIONS, INC.'),
(12144, '00:C0:FB', 'ADVANCED TECHNOLOGY LABS'),
(12145, '00:C0:FC', 'ELASTIC REALITY, INC.'),
(12146, '00:C0:FD', 'PROSUM'),
(12147, '00:C0:FE', 'APTEC COMPUTER SYSTEMS, INC.'),
(12148, '00:C0:FF', 'DOT HILL SYSTEMS CORPORATION'),
(12149, '00:C1:4F', 'DDL Co,.ltd.'),
(12150, '00:C2:C6', 'Intel Corporate'),
(12151, '00:C5:DB', 'Datatech Sistemas Digitales Avanzados SL'),
(12152, '00:C6:10', 'Apple'),
(12153, '00:CB:BD', 'Cambridge Broadband Networks Ltd.'),
(12154, '00:CD:90', 'MAS Elektronik AG'),
(12155, '00:CF:1C', 'COMMUNICATION MACHINERY CORP.'),
(12156, '00:D0:00', 'FERRAN SCIENTIFIC, INC.'),
(12157, '00:D0:01', 'VST TECHNOLOGIES, INC.'),
(12158, '00:D0:02', 'DITECH CORPORATION'),
(12159, '00:D0:03', 'COMDA ENTERPRISES CORP.'),
(12160, '00:D0:04', 'PENTACOM LTD.'),
(12161, '00:D0:05', 'ZHS ZEITMANAGEMENTSYSTEME'),
(12162, '00:D0:06', 'CISCO SYSTEMS, INC.'),
(12163, '00:D0:07', 'MIC ASSOCIATES, INC.'),
(12164, '00:D0:08', 'MACTELL CORPORATION'),
(12165, '00:D0:09', 'HSING TECH. ENTERPRISE CO. LTD'),
(12166, '00:D0:0A', 'LANACCESS TELECOM S.A.'),
(12167, '00:D0:0B', 'RHK TECHNOLOGY, INC.'),
(12168, '00:D0:0C', 'SNIJDER MICRO SYSTEMS'),
(12169, '00:D0:0D', 'MICROMERITICS INSTRUMENT'),
(12170, '00:D0:0E', 'PLURIS, INC.'),
(12171, '00:D0:0F', 'SPEECH DESIGN GMBH'),
(12172, '00:D0:10', 'CONVERGENT NETWORKS, INC.'),
(12173, '00:D0:11', 'PRISM VIDEO, INC.'),
(12174, '00:D0:12', 'GATEWORKS CORP.'),
(12175, '00:D0:13', 'PRIMEX AEROSPACE COMPANY'),
(12176, '00:D0:14', 'ROOT, INC.'),
(12177, '00:D0:15', 'UNIVEX MICROTECHNOLOGY CORP.'),
(12178, '00:D0:16', 'SCM MICROSYSTEMS, INC.'),
(12179, '00:D0:17', 'SYNTECH INFORMATION CO., LTD.'),
(12180, '00:D0:18', 'QWES. COM, INC.'),
(12181, '00:D0:19', 'DAINIPPON SCREEN CORPORATE'),
(12182, '00:D0:1A', 'URMET  TLC S.P.A.'),
(12183, '00:D0:1B', 'MIMAKI ENGINEERING CO., LTD.'),
(12184, '00:D0:1C', 'SBS TECHNOLOGIES,'),
(12185, '00:D0:1D', 'FURUNO ELECTRIC CO., LTD.'),
(12186, '00:D0:1E', 'PINGTEL CORP.'),
(12187, '00:D0:1F', 'Senetas Security'),
(12188, '00:D0:20', 'AIM SYSTEM, INC.'),
(12189, '00:D0:21', 'REGENT ELECTRONICS CORP.'),
(12190, '00:D0:22', 'INCREDIBLE TECHNOLOGIES, INC.'),
(12191, '00:D0:23', 'INFORTREND TECHNOLOGY, INC.'),
(12192, '00:D0:24', 'Cognex Corporation'),
(12193, '00:D0:25', 'XROSSTECH, INC.'),
(12194, '00:D0:26', 'HIRSCHMANN AUSTRIA GMBH'),
(12195, '00:D0:27', 'APPLIED AUTOMATION, INC.'),
(12196, '00:D0:28', 'Harmonic, Inc'),
(12197, '00:D0:29', 'WAKEFERN FOOD CORPORATION'),
(12198, '00:D0:2A', 'Voxent Systems Ltd.'),
(12199, '00:D0:2B', 'JETCELL, INC.'),
(12200, '00:D0:2C', 'CAMPBELL SCIENTIFIC, INC.'),
(12201, '00:D0:2D', 'ADEMCO'),
(12202, '00:D0:2E', 'COMMUNICATION AUTOMATION CORP.'),
(12203, '00:D0:2F', 'VLSI TECHNOLOGY INC.'),
(12204, '00:D0:30', 'Safetran Systems Corp'),
(12205, '00:D0:31', 'INDUSTRIAL LOGIC CORPORATION'),
(12206, '00:D0:32', 'YANO ELECTRIC CO., LTD.'),
(12207, '00:D0:33', 'DALIAN DAXIAN NETWORK'),
(12208, '00:D0:34', 'ORMEC SYSTEMS CORP.'),
(12209, '00:D0:35', 'BEHAVIOR TECH. COMPUTER CORP.'),
(12210, '00:D0:36', 'TECHNOLOGY ATLANTA CORP.'),
(12211, '00:D0:37', 'Pace France'),
(12212, '00:D0:38', 'FIVEMERE, LTD.'),
(12213, '00:D0:39', 'UTILICOM, INC.'),
(12214, '00:D0:3A', 'ZONEWORX, INC.'),
(12215, '00:D0:3B', 'VISION PRODUCTS PTY. LTD.'),
(12216, '00:D0:3C', 'Vieo, Inc.'),
(12217, '00:D0:3D', 'GALILEO TECHNOLOGY, LTD.'),
(12218, '00:D0:3E', 'ROCKETCHIPS, INC.'),
(12219, '00:D0:3F', 'AMERICAN COMMUNICATION'),
(12220, '00:D0:40', 'SYSMATE CO., LTD.'),
(12221, '00:D0:41', 'AMIGO TECHNOLOGY CO., LTD.'),
(12222, '00:D0:42', 'MAHLO GMBH &amp; CO. UG'),
(12223, '00:D0:43', 'ZONAL RETAIL DATA SYSTEMS'),
(12224, '00:D0:44', 'ALIDIAN NETWORKS, INC.'),
(12225, '00:D0:45', 'KVASER AB'),
(12226, '00:D0:46', 'DOLBY LABORATORIES, INC.'),
(12227, '00:D0:47', 'XN TECHNOLOGIES'),
(12228, '00:D0:48', 'ECTON, INC.'),
(12229, '00:D0:49', 'IMPRESSTEK CO., LTD.'),
(12230, '00:D0:4A', 'PRESENCE TECHNOLOGY GMBH'),
(12231, '00:D0:4B', 'LA CIE GROUP S.A.'),
(12232, '00:D0:4C', 'EUROTEL TELECOM LTD.'),
(12233, '00:D0:4D', 'DIV OF RESEARCH &amp; STATISTICS'),
(12234, '00:D0:4E', 'LOGIBAG'),
(12235, '00:D0:4F', 'BITRONICS, INC.'),
(12236, '00:D0:50', 'ISKRATEL'),
(12237, '00:D0:51', 'O2 MICRO, INC.'),
(12238, '00:D0:52', 'ASCEND COMMUNICATIONS, INC.'),
(12239, '00:D0:53', 'CONNECTED SYSTEMS'),
(12240, '00:D0:54', 'SAS INSTITUTE INC.'),
(12241, '00:D0:55', 'KATHREIN-WERKE KG'),
(12242, '00:D0:56', 'SOMAT CORPORATION'),
(12243, '00:D0:57', 'ULTRAK, INC.'),
(12244, '00:D0:58', 'CISCO SYSTEMS, INC.'),
(12245, '00:D0:59', 'AMBIT MICROSYSTEMS CORP.'),
(12246, '00:D0:5A', 'SYMBIONICS, LTD.'),
(12247, '00:D0:5B', 'ACROLOOP MOTION CONTROL'),
(12248, '00:D0:5C', 'TECHNOTREND SYSTEMTECHNIK GMBH'),
(12249, '00:D0:5D', 'INTELLIWORXX, INC.'),
(12250, '00:D0:5E', 'STRATABEAM TECHNOLOGY, INC.'),
(12251, '00:D0:5F', 'VALCOM, INC.'),
(12252, '00:D0:60', 'Panasonic Europe Ltd.'),
(12253, '00:D0:61', 'TREMON ENTERPRISES CO., LTD.'),
(12254, '00:D0:62', 'DIGIGRAM'),
(12255, '00:D0:63', 'CISCO SYSTEMS, INC.'),
(12256, '00:D0:64', 'MULTITEL'),
(12257, '00:D0:65', 'TOKO ELECTRIC'),
(12258, '00:D0:66', 'WINTRISS ENGINEERING CORP.'),
(12259, '00:D0:67', 'CAMPIO COMMUNICATIONS'),
(12260, '00:D0:68', 'IWILL CORPORATION'),
(12261, '00:D0:69', 'TECHNOLOGIC SYSTEMS'),
(12262, '00:D0:6A', 'LINKUP SYSTEMS CORPORATION'),
(12263, '00:D0:6B', 'SR TELECOM INC.'),
(12264, '00:D0:6C', 'SHAREWAVE, INC.'),
(12265, '00:D0:6D', 'ACRISON, INC.'),
(12266, '00:D0:6E', 'TRENDVIEW RECORDERS LTD.'),
(12267, '00:D0:6F', 'KMC CONTROLS'),
(12268, '00:D0:70', 'LONG WELL ELECTRONICS CORP.'),
(12269, '00:D0:71', 'ECHELON CORP.'),
(12270, '00:D0:72', 'BROADLOGIC'),
(12271, '00:D0:73', 'ACN ADVANCED COMMUNICATIONS'),
(12272, '00:D0:74', 'TAQUA SYSTEMS, INC.'),
(12273, '00:D0:75', 'ALARIS MEDICAL SYSTEMS, INC.'),
(12274, '00:D0:76', 'Bank of America'),
(12275, '00:D0:77', 'LUCENT TECHNOLOGIES'),
(12276, '00:D0:78', 'Eltex of Sweden AB'),
(12277, '00:D0:79', 'CISCO SYSTEMS, INC.'),
(12278, '00:D0:7A', 'AMAQUEST COMPUTER CORP.'),
(12279, '00:D0:7B', 'COMCAM INTERNATIONAL INC'),
(12280, '00:D0:7C', 'KOYO ELECTRONICS INC. CO.,LTD.'),
(12281, '00:D0:7D', 'COSINE COMMUNICATIONS'),
(12282, '00:D0:7E', 'KEYCORP LTD.'),
(12283, '00:D0:7F', 'STRATEGY &amp; TECHNOLOGY, LIMITED'),
(12284, '00:D0:80', 'EXABYTE CORPORATION'),
(12285, '00:D0:81', 'RTD Embedded Technologies, Inc.'),
(12286, '00:D0:82', 'IOWAVE INC.'),
(12287, '00:D0:83', 'INVERTEX, INC.'),
(12288, '00:D0:84', 'NEXCOMM SYSTEMS, INC.'),
(12289, '00:D0:85', 'OTIS ELEVATOR COMPANY'),
(12290, '00:D0:86', 'FOVEON, INC.'),
(12291, '00:D0:87', 'MICROFIRST INC.'),
(12292, '00:D0:88', 'ARRIS Group, Inc.'),
(12293, '00:D0:89', 'DYNACOLOR, INC.'),
(12294, '00:D0:8A', 'PHOTRON USA'),
(12295, '00:D0:8B', 'ADVA Optical Networking Ltd.'),
(12296, '00:D0:8C', 'GENOA TECHNOLOGY, INC.'),
(12297, '00:D0:8D', 'PHOENIX GROUP, INC.'),
(12298, '00:D0:8E', 'NVISION INC.'),
(12299, '00:D0:8F', 'ARDENT TECHNOLOGIES, INC.'),
(12300, '00:D0:90', 'CISCO SYSTEMS, INC.'),
(12301, '00:D0:91', 'SMARTSAN SYSTEMS, INC.'),
(12302, '00:D0:92', 'GLENAYRE WESTERN MULTIPLEX'),
(12303, '00:D0:93', 'TQ - COMPONENTS GMBH'),
(12304, '00:D0:94', 'TIMELINE VISTA, INC.'),
(12305, '00:D0:95', 'Alcatel-Lucent, Enterprise Business Group'),
(12306, '00:D0:96', '3COM EUROPE LTD.'),
(12307, '00:D0:97', 'CISCO SYSTEMS, INC.'),
(12308, '00:D0:98', 'Photon Dynamics Canada Inc.'),
(12309, '00:D0:99', 'Elcard Wireless Systems Oy'),
(12310, '00:D0:9A', 'FILANET CORPORATION'),
(12311, '00:D0:9B', 'SPECTEL LTD.'),
(12312, '00:D0:9C', 'KAPADIA COMMUNICATIONS'),
(12313, '00:D0:9D', 'VERIS INDUSTRIES'),
(12314, '00:D0:9E', '2WIRE, INC.'),
(12315, '00:D0:9F', 'NOVTEK TEST SYSTEMS'),
(12316, '00:D0:A0', 'MIPS DENMARK'),
(12317, '00:D0:A1', 'OSKAR VIERLING GMBH + CO. KG'),
(12318, '00:D0:A2', 'INTEGRATED DEVICE'),
(12319, '00:D0:A3', 'VOCAL DATA, INC.'),
(12320, '00:D0:A4', 'ALANTRO COMMUNICATIONS'),
(12321, '00:D0:A5', 'AMERICAN ARIUM'),
(12322, '00:D0:A6', 'LANBIRD TECHNOLOGY CO., LTD.'),
(12323, '00:D0:A7', 'TOKYO SOKKI KENKYUJO CO., LTD.'),
(12324, '00:D0:A8', 'NETWORK ENGINES, INC.'),
(12325, '00:D0:A9', 'SHINANO KENSHI CO., LTD.'),
(12326, '00:D0:AA', 'CHASE COMMUNICATIONS'),
(12327, '00:D0:AB', 'DELTAKABEL TELECOM CV'),
(12328, '00:D0:AC', 'GRAYSON WIRELESS'),
(12329, '00:D0:AD', 'TL INDUSTRIES'),
(12330, '00:D0:AE', 'ORESIS COMMUNICATIONS, INC.'),
(12331, '00:D0:AF', 'CUTLER-HAMMER, INC.'),
(12332, '00:D0:B0', 'BITSWITCH LTD.'),
(12333, '00:D0:B1', 'OMEGA ELECTRONICS SA'),
(12334, '00:D0:B2', 'XIOTECH CORPORATION'),
(12335, '00:D0:B3', 'DRS Technologies Canada Ltd'),
(12336, '00:D0:B4', 'KATSUJIMA CO., LTD.'),
(12337, '00:D0:B5', 'IPricot formerly DotCom'),
(12338, '00:D0:B6', 'CRESCENT NETWORKS, INC.'),
(12339, '00:D0:B7', 'INTEL CORPORATION'),
(12340, '00:D0:B8', 'Iomega Corporation'),
(12341, '00:D0:B9', 'MICROTEK INTERNATIONAL, INC.'),
(12342, '00:D0:BA', 'CISCO SYSTEMS, INC.'),
(12343, '00:D0:BB', 'CISCO SYSTEMS, INC.'),
(12344, '00:D0:BC', 'CISCO SYSTEMS, INC.'),
(12345, '00:D0:BD', 'Silicon Image GmbH'),
(12346, '00:D0:BE', 'EMUTEC INC.'),
(12347, '00:D0:BF', 'PIVOTAL TECHNOLOGIES'),
(12348, '00:D0:C0', 'CISCO SYSTEMS, INC.'),
(12349, '00:D0:C1', 'HARMONIC DATA SYSTEMS, LTD.'),
(12350, '00:D0:C2', 'BALTHAZAR TECHNOLOGY AB'),
(12351, '00:D0:C3', 'VIVID TECHNOLOGY PTE, LTD.'),
(12352, '00:D0:C4', 'TERATECH CORPORATION'),
(12353, '00:D0:C5', 'COMPUTATIONAL SYSTEMS, INC.'),
(12354, '00:D0:C6', 'THOMAS &amp; BETTS CORP.'),
(12355, '00:D0:C7', 'PATHWAY, INC.'),
(12356, '00:D0:C8', 'Prevas A/S'),
(12357, '00:D0:C9', 'ADVANTECH CO., LTD.'),
(12358, '00:D0:CA', 'Intrinsyc Software International Inc.'),
(12359, '00:D0:CB', 'DASAN CO., LTD.'),
(12360, '00:D0:CC', 'TECHNOLOGIES LYRE INC.'),
(12361, '00:D0:CD', 'ATAN TECHNOLOGY INC.'),
(12362, '00:D0:CE', 'ASYST ELECTRONIC'),
(12363, '00:D0:CF', 'MORETON BAY'),
(12364, '00:D0:D0', 'ZHONGXING TELECOM LTD.'),
(12365, '00:D0:D1', 'Sycamore Networks'),
(12366, '00:D0:D2', 'EPILOG CORPORATION'),
(12367, '00:D0:D3', 'CISCO SYSTEMS, INC.'),
(12368, '00:D0:D4', 'V-BITS, INC.'),
(12369, '00:D0:D5', 'GRUNDIG AG'),
(12370, '00:D0:D6', 'AETHRA TELECOMUNICAZIONI'),
(12371, '00:D0:D7', 'B2C2, INC.'),
(12372, '00:D0:D8', '3Com Corporation'),
(12373, '00:D0:D9', 'DEDICATED MICROCOMPUTERS'),
(12374, '00:D0:DA', 'TAICOM DATA SYSTEMS CO., LTD.'),
(12375, '00:D0:DB', 'MCQUAY INTERNATIONAL'),
(12376, '00:D0:DC', 'MODULAR MINING SYSTEMS, INC.'),
(12377, '00:D0:DD', 'SUNRISE TELECOM, INC.'),
(12378, '00:D0:DE', 'PHILIPS MULTIMEDIA NETWORK'),
(12379, '00:D0:DF', 'KUZUMI ELECTRONICS, INC.'),
(12380, '00:D0:E0', 'DOOIN ELECTRONICS CO.'),
(12381, '00:D0:E1', 'AVIONITEK ISRAEL INC.'),
(12382, '00:D0:E2', 'MRT MICRO, INC.'),
(12383, '00:D0:E3', 'ELE-CHEM ENGINEERING CO., LTD.'),
(12384, '00:D0:E4', 'CISCO SYSTEMS, INC.'),
(12385, '00:D0:E5', 'SOLIDUM SYSTEMS CORP.'),
(12386, '00:D0:E6', 'IBOND INC.'),
(12387, '00:D0:E7', 'VCON TELECOMMUNICATION LTD.'),
(12388, '00:D0:E8', 'MAC SYSTEM CO., LTD.'),
(12389, '00:D0:E9', 'Advantage Century Telecommunication Corp.'),
(12390, '00:D0:EA', 'NEXTONE COMMUNICATIONS, INC.'),
(12391, '00:D0:EB', 'LIGHTERA NETWORKS, INC.'),
(12392, '00:D0:EC', 'NAKAYO TELECOMMUNICATIONS, INC'),
(12393, '00:D0:ED', 'XIOX'),
(12394, '00:D0:EE', 'DICTAPHONE CORPORATION'),
(12395, '00:D0:EF', 'IGT'),
(12396, '00:D0:F0', 'CONVISION TECHNOLOGY GMBH'),
(12397, '00:D0:F1', 'SEGA ENTERPRISES, LTD.'),
(12398, '00:D0:F2', 'MONTEREY NETWORKS'),
(12399, '00:D0:F3', 'SOLARI DI UDINE SPA'),
(12400, '00:D0:F4', 'CARINTHIAN TECH INSTITUTE'),
(12401, '00:D0:F5', 'ORANGE MICRO, INC.'),
(12402, '00:D0:F6', 'Alcatel Canada'),
(12403, '00:D0:F7', 'NEXT NETS CORPORATION'),
(12404, '00:D0:F8', 'FUJIAN STAR TERMINAL'),
(12405, '00:D0:F9', 'ACUTE COMMUNICATIONS CORP.'),
(12406, '00:D0:FA', 'Thales e-Security Ltd.'),
(12407, '00:D0:FB', 'TEK MICROSYSTEMS, INCORPORATED'),
(12408, '00:D0:FC', 'GRANITE MICROSYSTEMS'),
(12409, '00:D0:FD', 'OPTIMA TELE.COM, INC.'),
(12410, '00:D0:FE', 'ASTRAL POINT'),
(12411, '00:D0:FF', 'CISCO SYSTEMS, INC.'),
(12412, '00:D1:1C', 'ACETEL'),
(12413, '00:D3:8D', 'Hotel Technology Next Generation'),
(12414, '00:D6:32', 'GE Energy'),
(12415, '00:D9:D1', 'Sony Computer Entertainment Inc.'),
(12416, '00:DB:1E', 'Albedo Telecom SL'),
(12417, '00:DB:45', 'THAMWAY CO.,LTD.'),
(12418, '00:DB:DF', 'Intel Corporate'),
(12419, '00:DD:00', 'UNGERMANN-BASS INC.'),
(12420, '00:DD:01', 'UNGERMANN-BASS INC.'),
(12421, '00:DD:02', 'UNGERMANN-BASS INC.'),
(12422, '00:DD:03', 'UNGERMANN-BASS INC.'),
(12423, '00:DD:04', 'UNGERMANN-BASS INC.'),
(12424, '00:DD:05', 'UNGERMANN-BASS INC.'),
(12425, '00:DD:06', 'UNGERMANN-BASS INC.'),
(12426, '00:DD:07', 'UNGERMANN-BASS INC.'),
(12427, '00:DD:08', 'UNGERMANN-BASS INC.'),
(12428, '00:DD:09', 'UNGERMANN-BASS INC.'),
(12429, '00:DD:0A', 'UNGERMANN-BASS INC.'),
(12430, '00:DD:0B', 'UNGERMANN-BASS INC.'),
(12431, '00:DD:0C', 'UNGERMANN-BASS INC.'),
(12432, '00:DD:0D', 'UNGERMANN-BASS INC.'),
(12433, '00:DD:0E', 'UNGERMANN-BASS INC.'),
(12434, '00:DD:0F', 'UNGERMANN-BASS INC.'),
(12435, '00:DE:FB', 'CISCO SYSTEMS, INC.'),
(12436, '00:E0:00', 'Fujitsu Limited'),
(12437, '00:E0:01', 'STRAND LIGHTING LIMITED'),
(12438, '00:E0:02', 'CROSSROADS SYSTEMS, INC.'),
(12439, '00:E0:03', 'NOKIA WIRELESS BUSINESS COMMUN'),
(12440, '00:E0:04', 'PMC-SIERRA, INC.'),
(12441, '00:E0:05', 'TECHNICAL CORP.'),
(12442, '00:E0:06', 'SILICON INTEGRATED SYS. CORP.'),
(12443, '00:E0:07', 'Avaya ECS Ltd'),
(12444, '00:E0:08', 'AMAZING CONTROLS! INC.'),
(12445, '00:E0:09', 'MARATHON TECHNOLOGIES CORP.'),
(12446, '00:E0:0A', 'DIBA, INC.'),
(12447, '00:E0:0B', 'ROOFTOP COMMUNICATIONS CORP.'),
(12448, '00:E0:0C', 'MOTOROLA'),
(12449, '00:E0:0D', 'RADIANT SYSTEMS'),
(12450, '00:E0:0E', 'AVALON IMAGING SYSTEMS, INC.'),
(12451, '00:E0:0F', 'SHANGHAI BAUD DATA'),
(12452, '00:E0:10', 'HESS SB-AUTOMATENBAU GmbH'),
(12453, '00:E0:11', 'Uniden Corporation'),
(12454, '00:E0:12', 'PLUTO TECHNOLOGIES INTERNATIONAL INC.'),
(12455, '00:E0:13', 'EASTERN ELECTRONIC CO., LTD.'),
(12456, '00:E0:14', 'CISCO SYSTEMS, INC.'),
(12457, '00:E0:15', 'HEIWA CORPORATION'),
(12458, '00:E0:16', 'RAPID CITY COMMUNICATIONS'),
(12459, '00:E0:17', 'EXXACT GmbH'),
(12460, '00:E0:18', 'ASUSTEK COMPUTER INC.'),
(12461, '00:E0:19', 'ING. GIORDANO ELETTRONICA'),
(12462, '00:E0:1A', 'COMTEC SYSTEMS. CO., LTD.'),
(12463, '00:E0:1B', 'SPHERE COMMUNICATIONS, INC.'),
(12464, '00:E0:1C', 'Cradlepoint, Inc'),
(12465, '00:E0:1D', 'WebTV NETWORKS, INC.'),
(12466, '00:E0:1E', 'CISCO SYSTEMS, INC.'),
(12467, '00:E0:1F', 'AVIDIA Systems, Inc.'),
(12468, '00:E0:20', 'TECNOMEN OY'),
(12469, '00:E0:21', 'FREEGATE CORP.'),
(12470, '00:E0:22', 'Analog Devices Inc.'),
(12471, '00:E0:23', 'TELRAD'),
(12472, '00:E0:24', 'GADZOOX NETWORKS'),
(12473, '00:E0:25', 'dit Co., Ltd.'),
(12474, '00:E0:26', 'Redlake MASD LLC'),
(12475, '00:E0:27', 'DUX, INC.'),
(12476, '00:E0:28', 'APTIX CORPORATION'),
(12477, '00:E0:29', 'STANDARD MICROSYSTEMS CORP.'),
(12478, '00:E0:2A', 'TANDBERG TELEVISION AS'),
(12479, '00:E0:2B', 'EXTREME NETWORKS'),
(12480, '00:E0:2C', 'AST COMPUTER'),
(12481, '00:E0:2D', 'InnoMediaLogic, Inc.'),
(12482, '00:E0:2E', 'SPC ELECTRONICS CORPORATION'),
(12483, '00:E0:2F', 'MCNS HOLDINGS, L.P.'),
(12484, '00:E0:30', 'MELITA INTERNATIONAL CORP.'),
(12485, '00:E0:31', 'HAGIWARA ELECTRIC CO., LTD.'),
(12486, '00:E0:32', 'MISYS FINANCIAL SYSTEMS, LTD.'),
(12487, '00:E0:33', 'E.E.P.D. GmbH'),
(12488, '00:E0:34', 'CISCO SYSTEMS, INC.'),
(12489, '00:E0:35', 'Artesyn Embedded Technologies'),
(12490, '00:E0:36', 'PIONEER CORPORATION'),
(12491, '00:E0:37', 'CENTURY CORPORATION'),
(12492, '00:E0:38', 'PROXIMA CORPORATION'),
(12493, '00:E0:39', 'PARADYNE CORP.'),
(12494, '00:E0:3A', 'CABLETRON SYSTEMS, INC.'),
(12495, '00:E0:3B', 'PROMINET CORPORATION'),
(12496, '00:E0:3C', 'AdvanSys'),
(12497, '00:E0:3D', 'FOCON ELECTRONIC SYSTEMS A/S'),
(12498, '00:E0:3E', 'ALFATECH, INC.'),
(12499, '00:E0:3F', 'JATON CORPORATION'),
(12500, '00:E0:40', 'DeskStation Technology, Inc.'),
(12501, '00:E0:41', 'CSPI'),
(12502, '00:E0:42', 'Pacom Systems Ltd.'),
(12503, '00:E0:43', 'VitalCom'),
(12504, '00:E0:44', 'LSICS CORPORATION'),
(12505, '00:E0:45', 'TOUCHWAVE, INC.'),
(12506, '00:E0:46', 'BENTLY NEVADA CORP.'),
(12507, '00:E0:47', 'InFocus Corporation'),
(12508, '00:E0:48', 'SDL COMMUNICATIONS, INC.'),
(12509, '00:E0:49', 'MICROWI ELECTRONIC GmbH'),
(12510, '00:E0:4A', 'ZX Technologies, Inc'),
(12511, '00:E0:4B', 'JUMP INDUSTRIELLE COMPUTERTECHNIK GmbH'),
(12512, '00:E0:4C', 'REALTEK SEMICONDUCTOR CORP.'),
(12513, '00:E0:4D', 'INTERNET INITIATIVE JAPAN, INC'),
(12514, '00:E0:4E', 'SANYO DENKI CO., LTD.'),
(12515, '00:E0:4F', 'CISCO SYSTEMS, INC.'),
(12516, '00:E0:50', 'EXECUTONE INFORMATION SYSTEMS, INC.'),
(12517, '00:E0:51', 'TALX CORPORATION'),
(12518, '00:E0:52', 'Brocade Communications Systems, Inc'),
(12519, '00:E0:53', 'CELLPORT LABS, INC.'),
(12520, '00:E0:54', 'KODAI HITEC CO., LTD.'),
(12521, '00:E0:55', 'INGENIERIA ELECTRONICA COMERCIAL INELCOM S.A.'),
(12522, '00:E0:56', 'HOLONTECH CORPORATION'),
(12523, '00:E0:57', 'HAN MICROTELECOM. CO., LTD.'),
(12524, '00:E0:58', 'PHASE ONE DENMARK A/S'),
(12525, '00:E0:59', 'CONTROLLED ENVIRONMENTS, LTD.'),
(12526, '00:E0:5A', 'GALEA NETWORK SECURITY'),
(12527, '00:E0:5B', 'WEST END SYSTEMS CORP.'),
(12528, '00:E0:5C', 'MATSUSHITA KOTOBUKI ELECTRONICS INDUSTRIES, LTD.'),
(12529, '00:E0:5D', 'UNITEC CO., LTD.'),
(12530, '00:E0:5E', 'JAPAN AVIATION ELECTRONICS INDUSTRY, LTD.'),
(12531, '00:E0:5F', 'e-Net, Inc.'),
(12532, '00:E0:60', 'SHERWOOD'),
(12533, '00:E0:61', 'EdgePoint Networks, Inc.'),
(12534, '00:E0:62', 'HOST ENGINEERING'),
(12535, '00:E0:63', 'CABLETRON - YAGO SYSTEMS, INC.'),
(12536, '00:E0:64', 'SAMSUNG ELECTRONICS'),
(12537, '00:E0:65', 'OPTICAL ACCESS INTERNATIONAL'),
(12538, '00:E0:66', 'ProMax Systems, Inc.'),
(12539, '00:E0:67', 'eac AUTOMATION-CONSULTING GmbH'),
(12540, '00:E0:68', 'MERRIMAC SYSTEMS INC.'),
(12541, '00:E0:69', 'JAYCOR'),
(12542, '00:E0:6A', 'KAPSCH AG'),
(12543, '00:E0:6B', 'W&amp;G SPECIAL PRODUCTS'),
(12544, '00:E0:6C', 'Ultra Electronics Limited (AEP Networks)'),
(12545, '00:E0:6D', 'COMPUWARE CORPORATION'),
(12546, '00:E0:6E', 'FAR SYSTEMS S.p.A.'),
(12547, '00:E0:6F', 'ARRIS Group, Inc.'),
(12548, '00:E0:70', 'DH TECHNOLOGY'),
(12549, '00:E0:71', 'EPIS MICROCOMPUTER'),
(12550, '00:E0:72', 'LYNK'),
(12551, '00:E0:73', 'NATIONAL AMUSEMENT NETWORK, INC.'),
(12552, '00:E0:74', 'TIERNAN COMMUNICATIONS, INC.'),
(12553, '00:E0:75', 'Verilink Corporation'),
(12554, '00:E0:76', 'DEVELOPMENT CONCEPTS, INC.'),
(12555, '00:E0:77', 'WEBGEAR, INC.'),
(12556, '00:E0:78', 'BERKELEY NETWORKS'),
(12557, '00:E0:79', 'A.T.N.R.'),
(12558, '00:E0:7A', 'MIKRODIDAKT AB'),
(12559, '00:E0:7B', 'BAY NETWORKS'),
(12560, '00:E0:7C', 'METTLER-TOLEDO, INC.'),
(12561, '00:E0:7D', 'NETRONIX, INC.'),
(12562, '00:E0:7E', 'WALT DISNEY IMAGINEERING'),
(12563, '00:E0:7F', 'LOGISTISTEM s.r.l.'),
(12564, '00:E0:80', 'CONTROL RESOURCES CORPORATION'),
(12565, '00:E0:81', 'TYAN COMPUTER CORP.'),
(12566, '00:E0:82', 'ANERMA'),
(12567, '00:E0:83', 'JATO TECHNOLOGIES, INC.'),
(12568, '00:E0:84', 'COMPULITE R&amp;D'),
(12569, '00:E0:85', 'GLOBAL MAINTECH, INC.'),
(12570, '00:E0:86', 'Emerson Network Power, Avocent Division'),
(12571, '00:E0:87', 'LeCroy - Networking Productions Division'),
(12572, '00:E0:88', 'LTX-Credence CORPORATION'),
(12573, '00:E0:89', 'ION Networks, Inc.'),
(12574, '00:E0:8A', 'GEC AVERY, LTD.'),
(12575, '00:E0:8B', 'QLogic Corp.'),
(12576, '00:E0:8C', 'NEOPARADIGM LABS, INC.'),
(12577, '00:E0:8D', 'PRESSURE SYSTEMS, INC.'),
(12578, '00:E0:8E', 'UTSTARCOM'),
(12579, '00:E0:8F', 'CISCO SYSTEMS, INC.'),
(12580, '00:E0:90', 'BECKMAN LAB. AUTOMATION DIV.'),
(12581, '00:E0:91', 'LG ELECTRONICS, INC.'),
(12582, '00:E0:92', 'ADMTEK INCORPORATED'),
(12583, '00:E0:93', 'ACKFIN NETWORKS'),
(12584, '00:E0:94', 'OSAI SRL'),
(12585, '00:E0:95', 'ADVANCED-VISION TECHNOLGIES CORP.'),
(12586, '00:E0:96', 'SHIMADZU CORPORATION'),
(12587, '00:E0:97', 'CARRIER ACCESS CORPORATION'),
(12588, '00:E0:98', 'AboCom Systems, Inc.'),
(12589, '00:E0:99', 'SAMSON AG'),
(12590, '00:E0:9A', 'Positron Inc.'),
(12591, '00:E0:9B', 'ENGAGE NETWORKS, INC.'),
(12592, '00:E0:9C', 'MII'),
(12593, '00:E0:9D', 'SARNOFF CORPORATION'),
(12594, '00:E0:9E', 'QUANTUM CORPORATION'),
(12595, '00:E0:9F', 'PIXEL VISION'),
(12596, '00:E0:A0', 'WILTRON CO.'),
(12597, '00:E0:A1', 'HIMA PAUL HILDEBRANDT GmbH Co. KG'),
(12598, '00:E0:A2', 'MICROSLATE INC.'),
(12599, '00:E0:A3', 'CISCO SYSTEMS, INC.'),
(12600, '00:E0:A4', 'ESAOTE S.p.A.'),
(12601, '00:E0:A5', 'ComCore Semiconductor, Inc.'),
(12602, '00:E0:A6', 'TELOGY NETWORKS, INC.'),
(12603, '00:E0:A7', 'IPC INFORMATION SYSTEMS, INC.'),
(12604, '00:E0:A8', 'SAT GmbH &amp; Co.'),
(12605, '00:E0:A9', 'FUNAI ELECTRIC CO., LTD.'),
(12606, '00:E0:AA', 'ELECTROSONIC LTD.'),
(12607, '00:E0:AB', 'DIMAT S.A.'),
(12608, '00:E0:AC', 'MIDSCO, INC.'),
(12609, '00:E0:AD', 'EES TECHNOLOGY, LTD.'),
(12610, '00:E0:AE', 'XAQTI CORPORATION'),
(12611, '00:E0:AF', 'GENERAL DYNAMICS INFORMATION SYSTEMS'),
(12612, '00:E0:B0', 'CISCO SYSTEMS, INC.'),
(12613, '00:E0:B1', 'Alcatel-Lucent, Enterprise Business Group'),
(12614, '00:E0:B2', 'TELMAX COMMUNICATIONS CORP.'),
(12615, '00:E0:B3', 'EtherWAN Systems, Inc.'),
(12616, '00:E0:B4', 'TECHNO SCOPE CO., LTD.'),
(12617, '00:E0:B5', 'ARDENT COMMUNICATIONS CORP.'),
(12618, '00:E0:B6', 'Entrada Networks'),
(12619, '00:E0:B7', 'PI GROUP, LTD.'),
(12620, '00:E0:B8', 'GATEWAY 2000'),
(12621, '00:E0:B9', 'BYAS SYSTEMS'),
(12622, '00:E0:BA', 'BERGHOF AUTOMATIONSTECHNIK GmbH'),
(12623, '00:E0:BB', 'NBX CORPORATION'),
(12624, '00:E0:BC', 'SYMON COMMUNICATIONS, INC.'),
(12625, '00:E0:BD', 'INTERFACE SYSTEMS, INC.'),
(12626, '00:E0:BE', 'GENROCO INTERNATIONAL, INC.'),
(12627, '00:E0:BF', 'TORRENT NETWORKING TECHNOLOGIES CORP.'),
(12628, '00:E0:C0', 'SEIWA ELECTRIC MFG. CO., LTD.'),
(12629, '00:E0:C1', 'MEMOREX TELEX JAPAN, LTD.'),
(12630, '00:E0:C2', 'NECSY S.p.A.'),
(12631, '00:E0:C3', 'SAKAI SYSTEM DEVELOPMENT CORP.'),
(12632, '00:E0:C4', 'HORNER ELECTRIC, INC.'),
(12633, '00:E0:C5', 'BCOM ELECTRONICS INC.'),
(12634, '00:E0:C6', 'LINK2IT, L.L.C.'),
(12635, '00:E0:C7', 'EUROTECH SRL'),
(12636, '00:E0:C8', 'VIRTUAL ACCESS, LTD.'),
(12637, '00:E0:C9', 'AutomatedLogic Corporation'),
(12638, '00:E0:CA', 'BEST DATA PRODUCTS'),
(12639, '00:E0:CB', 'RESON, INC.'),
(12640, '00:E0:CC', 'HERO SYSTEMS, LTD.'),
(12641, '00:E0:CD', 'SAAB SENSIS CORPORATION'),
(12642, '00:E0:CE', 'ARN'),
(12643, '00:E0:CF', 'INTEGRATED DEVICE TECHNOLOGY, INC.'),
(12644, '00:E0:D0', 'NETSPEED, INC.'),
(12645, '00:E0:D1', 'TELSIS LIMITED'),
(12646, '00:E0:D2', 'VERSANET COMMUNICATIONS, INC.'),
(12647, '00:E0:D3', 'DATENTECHNIK GmbH'),
(12648, '00:E0:D4', 'EXCELLENT COMPUTER'),
(12649, '00:E0:D5', 'Emulex Corporation'),
(12650, '00:E0:D6', 'COMPUTER &amp; COMMUNICATION RESEARCH LAB.'),
(12651, '00:E0:D7', 'SUNSHINE ELECTRONICS, INC.'),
(12652, '00:E0:D8', 'LANBit Computer, Inc.'),
(12653, '00:E0:D9', 'TAZMO CO., LTD.'),
(12654, '00:E0:DA', 'Alcatel North America ESD'),
(12655, '00:E0:DB', 'ViaVideo Communications, Inc.'),
(12656, '00:E0:DC', 'NEXWARE CORP.'),
(12657, '00:E0:DD', 'ZENITH ELECTRONICS CORPORATION'),
(12658, '00:E0:DE', 'DATAX NV'),
(12659, '00:E0:DF', 'KEYMILE GmbH'),
(12660, '00:E0:E0', 'SI ELECTRONICS, LTD.'),
(12661, '00:E0:E1', 'G2 NETWORKS, INC.'),
(12662, '00:E0:E2', 'INNOVA CORP.'),
(12663, '00:E0:E3', 'SK-ELEKTRONIK GmbH'),
(12664, '00:E0:E4', 'FANUC ROBOTICS NORTH AMERICA, Inc.'),
(12665, '00:E0:E5', 'CINCO NETWORKS, INC.'),
(12666, '00:E0:E6', 'INCAA DATACOM B.V.'),
(12667, '00:E0:E7', 'RAYTHEON E-SYSTEMS, INC.'),
(12668, '00:E0:E8', 'GRETACODER Data Systems AG'),
(12669, '00:E0:E9', 'DATA LABS, INC.'),
(12670, '00:E0:EA', 'INNOVAT COMMUNICATIONS, INC.'),
(12671, '00:E0:EB', 'DIGICOM SYSTEMS, INCORPORATED'),
(12672, '00:E0:EC', 'CELESTICA INC.'),
(12673, '00:E0:ED', 'SILICOM, LTD.'),
(12674, '00:E0:EE', 'MAREL HF'),
(12675, '00:E0:EF', 'DIONEX'),
(12676, '00:E0:F0', 'ABLER TECHNOLOGY, INC.'),
(12677, '00:E0:F1', 'THAT CORPORATION'),
(12678, '00:E0:F2', 'ARLOTTO COMNET, INC.'),
(12679, '00:E0:F3', 'WebSprint Communications, Inc.'),
(12680, '00:E0:F4', 'INSIDE Technology A/S'),
(12681, '00:E0:F5', 'TELES AG'),
(12682, '00:E0:F6', 'DECISION EUROPE'),
(12683, '00:E0:F7', 'CISCO SYSTEMS, INC.'),
(12684, '00:E0:F8', 'DICNA CONTROL AB'),
(12685, '00:E0:F9', 'CISCO SYSTEMS, INC.'),
(12686, '00:E0:FA', 'TRL TECHNOLOGY, LTD.'),
(12687, '00:E0:FB', 'LEIGHTRONIX, INC.'),
(12688, '00:E0:FC', 'HUAWEI TECHNOLOGIES CO., LTD.'),
(12689, '00:E0:FD', 'A-TREND TECHNOLOGY CO., LTD.'),
(12690, '00:E0:FE', 'CISCO SYSTEMS, INC.'),
(12691, '00:E0:FF', 'SECURITY DYNAMICS TECHNOLOGIES, Inc.'),
(12692, '00:E1:6D', 'Cisco'),
(12693, '00:E1:75', 'AK-Systems Ltd'),
(12694, '00:E3:B2', 'Samsung Electronics Co.,Ltd'),
(12695, '00:E6:66', 'ARIMA Communications Corp.'),
(12696, '00:E6:D3', 'NIXDORF COMPUTER CORP.'),
(12697, '00:E6:E8', 'Netzin Technology Corporation,.Ltd.'),
(12698, '00:E8:AB', 'Meggitt Training Systems, Inc.'),
(12699, '00:EB:2D', 'Sony Mobile Communications AB'),
(12700, '00:EE:BD', 'HTC Corporation'),
(12701, '00:F0:51', 'KWB Gmbh'),
(12702, '00:F3:DB', 'WOO Sports'),
(12703, '00:F4:03', 'Orbis Systems Oy'),
(12704, '00:F4:6F', 'Samsung Elec Co.,Ltd'),
(12705, '00:F4:B9', 'Apple'),
(12706, '00:F7:6F', 'Apple'),
(12707, '00:F8:60', 'PT. Panggung Electric Citrabuana'),
(12708, '00:FA:3B', 'CLOOS ELECTRONIC GMBH'),
(12709, '00:FC:58', 'WebSilicon Ltd.'),
(12710, '00:FC:70', 'Intrepid Control Systems, Inc.'),
(12711, '00:FD:4C', 'NEVATEC'),
(12712, '02:07:01', 'RACAL-DATACOM'),
(12713, '02:1C:7C', 'PERQ SYSTEMS CORPORATION'),
(12714, '02:60:86', 'LOGIC REPLACEMENT TECH. LTD.'),
(12715, '02:60:8C', '3COM CORPORATION'),
(12716, '02:70:01', 'RACAL-DATACOM'),
(12717, '02:70:B0', 'M/A-COM INC. COMPANIES'),
(12718, '02:70:B3', 'DATA RECALL LTD'),
(12719, '02:9D:8E', 'CARDIAC RECORDERS INC.'),
(12720, '02:AA:3C', 'OLIVETTI TELECOMM SPA (OLTECO)'),
(12721, '02:BB:01', 'OCTOTHORPE CORP.'),
(12722, '02:C0:8C', '3COM CORPORATION'),
(12723, '02:CF:1C', 'COMMUNICATION MACHINERY CORP.'),
(12724, '02:E6:D3', 'NIXDORF COMPUTER CORPORATION'),
(12725, '04:0A:83', 'Alcatel-Lucent'),
(12726, '04:0A:E0', 'XMIT AG COMPUTER NETWORKS'),
(12727, '04:0C:CE', 'Apple'),
(12728, '04:0E:C2', 'ViewSonic Mobile China Limited'),
(12729, '04:15:52', 'Apple'),
(12730, '04:18:0F', 'Samsung Electronics Co.,Ltd'),
(12731, '04:18:B6', 'PRIVATE'),
(12732, '04:18:D6', 'Ubiquiti Networks'),
(12733, '04:1A:04', 'WaveIP'),
(12734, '04:1B:94', 'Host Mobility AB'),
(12735, '04:1B:BA', 'Samsung Electronics Co.,Ltd'),
(12736, '04:1D:10', 'Dream Ware Inc.'),
(12737, '04:1E:64', 'Apple'),
(12738, '04:20:9A', 'Panasonic AVC Networks Company'),
(12739, '04:22:34', 'Wireless Standard Extensions'),
(12740, '04:26:05', 'GFR Gesellschaft f&uuml;r Regelungstechnik und Energieeinsparung mbH'),
(12741, '04:26:65', 'Apple'),
(12742, '04:2B:BB', 'PicoCELA, Inc.'),
(12743, '04:2F:56', 'ATOCS (Shenzhen) LTD'),
(12744, '04:32:F4', 'Partron'),
(12745, '04:36:04', 'Gyeyoung I&amp;T'),
(12746, '04:3D:98', 'ChongQing QingJia Electronics CO.,LTD'),
(12747, '04:44:A1', 'TELECON GALICIA,S.A.'),
(12748, '04:46:65', 'Murata Manufacturing Co., Ltd.'),
(12749, '04:48:9A', 'Apple'),
(12750, '04:4A:50', 'Ramaxel Technology (Shenzhen) limited company'),
(12751, '04:4B:FF', 'GuangZhou Hedy Digital Technology Co., Ltd'),
(12752, '04:4C:EF', 'Fujian Sanao Technology Co.,Ltd'),
(12753, '04:4E:06', 'Ericsson AB'),
(12754, '04:4F:8B', 'Adapteva, Inc.'),
(12755, '04:4F:AA', 'Ruckus Wireless'),
(12756, '04:54:53', 'Apple'),
(12757, '04:55:CA', 'BriView (Xiamen) Corp.'),
(12758, '04:57:2F', 'Sertel Electronics UK Ltd'),
(12759, '04:58:6F', 'Sichuan Whayer information industry Co.,LTD'),
(12760, '04:5A:95', 'Nokia Corporation'),
(12761, '04:5C:06', 'Zmodo Technology Corporation'),
(12762, '04:5C:8E', 'gosund GROUP CO.,LTD'),
(12763, '04:5D:56', 'camtron industrial inc.'),
(12764, '04:5F:A7', 'Shenzhen Yichen Technology Development Co.,LTD'),
(12765, '04:62:D7', 'ALSTOM HYDRO FRANCE'),
(12766, '04:63:E0', 'Nome Oy'),
(12767, '04:67:85', 'scemtec Hard- und Software fuer Mess- und Steuerungstechnik GmbH'),
(12768, '04:6D:42', 'Bryston Ltd.'),
(12769, '04:6E:49', 'TaiYear Electronic Technology (Suzhou) Co., Ltd'),
(12770, '04:70:BC', 'Globalstar Inc.'),
(12771, '04:74:A1', 'Aligera Equipamentos Digitais Ltda'),
(12772, '04:75:F5', 'CSST'),
(12773, '04:76:6E', 'ALPS Co,. Ltd.'),
(12774, '04:7D:7B', 'Quanta Computer Inc.'),
(12775, '04:81:AE', 'Clack Corporation'),
(12776, '04:84:8A', '7INOVA TECHNOLOGY LIMITED'),
(12777, '04:88:8C', 'Eifelwerk Butler Systeme GmbH'),
(12778, '04:88:E2', 'Beats Electronics LLC'),
(12779, '04:8A:15', 'Avaya, Inc'),
(12780, '04:8B:42', 'Skspruce Technology Limited'),
(12781, '04:8C:03', 'ThinPAD Technology (Shenzhen)CO.,LTD'),
(12782, '04:8D:38', 'Netcore Technology Inc.'),
(12783, '04:92:EE', 'iway AG'),
(12784, '04:94:A1', 'CATCH THE WIND INC'),
(12785, '04:98:F3', 'ALPS Electric Co,. Ltd.'),
(12786, '04:99:E6', 'Shenzhen Yoostar Technology Co., Ltd'),
(12787, '04:9B:9C', 'Eadingcore  Intelligent Technology Co., Ltd.'),
(12788, '04:9C:62', 'BMT Medical Technology s.r.o.'),
(12789, '04:9F:06', 'Smobile Co., Ltd.'),
(12790, '04:9F:81', 'Netscout Systems, Inc.'),
(12791, '04:A1:51', 'NETGEAR INC.,'),
(12792, '04:A3:F3', 'Emicon'),
(12793, '04:A8:2A', 'Nokia Corporation'),
(12794, '04:B3:B6', 'Seamap (UK) Ltd'),
(12795, '04:B4:66', 'BSP Co., Ltd.'),
(12796, '04:BD:70', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(12797, '04:BD:88', 'Aruba Networks'),
(12798, '04:BF:A8', 'ISB Corporation'),
(12799, '04:C0:5B', 'Tigo Energy'),
(12800, '04:C0:6F', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(12801, '04:C0:9C', 'Tellabs Inc.'),
(12802, '04:C1:B9', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(12803, '04:C5:A4', 'CISCO SYSTEMS, INC.'),
(12804, '04:C8:80', 'Samtec Inc'),
(12805, '04:C9:91', 'Phistek INC.'),
(12806, '04:C9:D9', 'EchoStar Technologies Corp'),
(12807, '04:CB:1D', 'Traka plc'),
(12808, '04:CE:14', 'Wilocity LTD.'),
(12809, '04:CF:25', 'MANYCOLORS, INC.'),
(12810, '04:D4:37', 'ZNV'),
(12811, '04:D7:83', 'Y&amp;H E&amp;C Co.,LTD.'),
(12812, '04:DA:D2', 'Cisco'),
(12813, '04:DB:56', 'Apple, Inc.'),
(12814, '04:DB:8A', 'Suntech International Ltd.'),
(12815, '04:DD:4C', 'Velocytech'),
(12816, '04:DE:DB', 'Rockport Networks Inc'),
(12817, '04:DF:69', 'Car Connectivity Consortium'),
(12818, '04:E0:C4', 'TRIUMPH-ADLER AG'),
(12819, '04:E1:C8', 'IMS Solu&ccedil;&otilde;es em Energia Ltda.'),
(12820, '04:E2:F8', 'AEP Ticketing solutions srl'),
(12821, '04:E4:51', 'Texas Instruments'),
(12822, '04:E5:36', 'Apple'),
(12823, '04:E5:48', 'Cohda Wireless Pty Ltd'),
(12824, '04:E6:62', 'Acroname Inc.'),
(12825, '04:E6:76', 'AMPAK Technology Inc.'),
(12826, '04:E9:E5', 'PJRC.COM, LLC'),
(12827, '04:EE:91', 'x-fabric GmbH'),
(12828, '04:F0:21', 'Compex Systems Pte Ltd'),
(12829, '04:F1:3E', 'Apple'),
(12830, '04:F1:7D', 'Tarana Wireless'),
(12831, '04:F4:BC', 'Xena Networks'),
(12832, '04:F7:E4', 'Apple'),
(12833, '04:F8:C2', 'Flaircomm Microelectronics, Inc.'),
(12834, '04:F9:38', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(12835, '04:FE:31', 'Samsung Electronics Co.,Ltd'),
(12836, '04:FE:7F', 'CISCO SYSTEMS, INC.'),
(12837, '04:FF:51', 'NOVAMEDIA INNOVISION SP. Z O.O.'),
(12838, '08:00:01', 'COMPUTERVISION CORPORATION'),
(12839, '08:00:02', 'BRIDGE COMMUNICATIONS INC.'),
(12840, '08:00:03', 'ADVANCED COMPUTER COMM.'),
(12841, '08:00:04', 'CROMEMCO INCORPORATED'),
(12842, '08:00:05', 'SYMBOLICS INC.'),
(12843, '08:00:06', 'SIEMENS AG'),
(12844, '08:00:07', 'Apple'),
(12845, '08:00:08', 'BOLT BERANEK AND NEWMAN INC.'),
(12846, '08:00:09', 'HEWLETT PACKARD'),
(12847, '08:00:0A', 'NESTAR SYSTEMS INCORPORATED'),
(12848, '08:00:0B', 'UNISYS CORPORATION'),
(12849, '08:00:0C', 'MIKLYN DEVELOPMENT CO.'),
(12850, '08:00:0D', 'INTERNATIONAL COMPUTERS LTD.'),
(12851, '08:00:0E', 'NCR CORPORATION'),
(12852, '08:00:0F', 'MITEL CORPORATION'),
(12853, '08:00:11', 'TEKTRONIX INC.'),
(12854, '08:00:12', 'BELL ATLANTIC INTEGRATED SYST.'),
(12855, '08:00:13', 'EXXON'),
(12856, '08:00:14', 'EXCELAN'),
(12857, '08:00:15', 'STC BUSINESS SYSTEMS'),
(12858, '08:00:16', 'BARRISTER INFO SYS CORP'),
(12859, '08:00:17', 'NATIONAL SEMICONDUCTOR'),
(12860, '08:00:18', 'PIRELLI FOCOM NETWORKS'),
(12861, '08:00:19', 'GENERAL ELECTRIC CORPORATION'),
(12862, '08:00:1A', 'TIARA/ 10NET'),
(12863, '08:00:1B', 'EMC Corporation'),
(12864, '08:00:1C', 'KDD-KOKUSAI DEBNSIN DENWA CO.'),
(12865, '08:00:1D', 'ABLE COMMUNICATIONS INC.'),
(12866, '08:00:1E', 'APOLLO COMPUTER INC.'),
(12867, '08:00:1F', 'SHARP CORPORATION'),
(12868, '08:00:20', 'Oracle Corporation'),
(12869, '08:00:21', '3M COMPANY'),
(12870, '08:00:22', 'NBI INC.'),
(12871, '08:00:23', 'Panasonic Communications Co., Ltd.'),
(12872, '08:00:24', '10NET COMMUNICATIONS/DCA'),
(12873, '08:00:25', 'CONTROL DATA'),
(12874, '08:00:26', 'NORSK DATA A.S.'),
(12875, '08:00:27', 'CADMUS COMPUTER SYSTEMS'),
(12876, '08:00:28', 'Texas Instruments'),
(12877, '08:00:29', 'MEGATEK CORPORATION'),
(12878, '08:00:2A', 'MOSAIC TECHNOLOGIES INC.'),
(12879, '08:00:2B', 'DIGITAL EQUIPMENT CORPORATION'),
(12880, '08:00:2C', 'BRITTON LEE INC.'),
(12881, '08:00:2D', 'LAN-TEC INC.'),
(12882, '08:00:2E', 'METAPHOR COMPUTER SYSTEMS'),
(12883, '08:00:2F', 'PRIME COMPUTER INC.'),
(12884, '08:00:30', 'NETWORK RESEARCH CORPORATION'),
(12885, '08:00:30', 'CERN'),
(12886, '08:00:30', 'ROYAL MELBOURNE INST OF TECH'),
(12887, '08:00:31', 'LITTLE MACHINES INC.'),
(12888, '08:00:32', 'TIGAN INCORPORATED'),
(12889, '08:00:33', 'BAUSCH &amp; LOMB'),
(12890, '08:00:34', 'FILENET CORPORATION'),
(12891, '08:00:35', 'MICROFIVE CORPORATION'),
(12892, '08:00:36', 'INTERGRAPH CORPORATION'),
(12893, '08:00:37', 'FUJI-XEROX CO. LTD.'),
(12894, '08:00:38', 'BULL S.A.S.'),
(12895, '08:00:39', 'SPIDER SYSTEMS LIMITED'),
(12896, '08:00:3A', 'ORCATECH INC.'),
(12897, '08:00:3B', 'TORUS SYSTEMS LIMITED'),
(12898, '08:00:3C', 'SCHLUMBERGER WELL SERVICES'),
(12899, '08:00:3D', 'CADNETIX CORPORATIONS'),
(12900, '08:00:3E', 'CODEX CORPORATION'),
(12901, '08:00:3F', 'FRED KOSCHARA ENTERPRISES'),
(12902, '08:00:40', 'FERRANTI COMPUTER SYS. LIMITED'),
(12903, '08:00:41', 'RACAL-MILGO INFORMATION SYS..'),
(12904, '08:00:42', 'JAPAN MACNICS CORP.'),
(12905, '08:00:43', 'PIXEL COMPUTER INC.'),
(12906, '08:00:44', 'DAVID SYSTEMS INC.'),
(12907, '08:00:45', 'CONCURRENT COMPUTER CORP.'),
(12908, '08:00:46', 'Sony Corporation'),
(12909, '08:00:47', 'SEQUENT COMPUTER SYSTEMS INC.'),
(12910, '08:00:48', 'EUROTHERM GAUGING SYSTEMS'),
(12911, '08:00:49', 'UNIVATION'),
(12912, '08:00:4A', 'BANYAN SYSTEMS INC.'),
(12913, '08:00:4B', 'PLANNING RESEARCH CORP.'),
(12914, '08:00:4C', 'HYDRA COMPUTER SYSTEMS INC.'),
(12915, '08:00:4D', 'CORVUS SYSTEMS INC.'),
(12916, '08:00:4E', '3COM EUROPE LTD.'),
(12917, '08:00:4F', 'CYGNET SYSTEMS'),
(12918, '08:00:50', 'DAISY SYSTEMS CORP.'),
(12919, '08:00:51', 'EXPERDATA'),
(12920, '08:00:52', 'INSYSTEC'),
(12921, '08:00:53', 'MIDDLE EAST TECH. UNIVERSITY'),
(12922, '08:00:55', 'STANFORD TELECOMM. INC.'),
(12923, '08:00:56', 'STANFORD LINEAR ACCEL. CENTER'),
(12924, '08:00:57', 'EVANS &amp; SUTHERLAND'),
(12925, '08:00:58', 'SYSTEMS CONCEPTS'),
(12926, '08:00:59', 'A/S MYCRON'),
(12927, '08:00:5A', 'IBM Corp'),
(12928, '08:00:5B', 'VTA TECHNOLOGIES INC.'),
(12929, '08:00:5C', 'FOUR PHASE SYSTEMS'),
(12930, '08:00:5D', 'GOULD INC.'),
(12931, '08:00:5E', 'COUNTERPOINT COMPUTER INC.'),
(12932, '08:00:5F', 'SABER TECHNOLOGY CORP.'),
(12933, '08:00:60', 'INDUSTRIAL NETWORKING INC.'),
(12934, '08:00:61', 'JAROGATE LTD.'),
(12935, '08:00:62', 'GENERAL DYNAMICS'),
(12936, '08:00:63', 'PLESSEY'),
(12937, '08:00:64', 'Sitasys AG'),
(12938, '08:00:65', 'GENRAD INC.'),
(12939, '08:00:66', 'AGFA CORPORATION'),
(12940, '08:00:67', 'COMDESIGN'),
(12941, '08:00:68', 'RIDGE COMPUTERS'),
(12942, '08:00:69', 'SILICON GRAPHICS INC.'),
(12943, '08:00:6A', 'ATT BELL LABORATORIES'),
(12944, '08:00:6B', 'ACCEL TECHNOLOGIES INC.'),
(12945, '08:00:6C', 'SUNTEK TECHNOLOGY INT\'L'),
(12946, '08:00:6D', 'WHITECHAPEL COMPUTER WORKS'),
(12947, '08:00:6E', 'MASSCOMP'),
(12948, '08:00:6F', 'PHILIPS APELDOORN B.V.'),
(12949, '08:00:70', 'MITSUBISHI ELECTRIC CORP.'),
(12950, '08:00:71', 'MATRA (DSIE)'),
(12951, '08:00:72', 'XEROX CORP UNIV GRANT PROGRAM'),
(12952, '08:00:73', 'TECMAR INC.'),
(12953, '08:00:74', 'CASIO COMPUTER CO. LTD.'),
(12954, '08:00:75', 'DANSK DATA ELECTRONIK'),
(12955, '08:00:76', 'PC LAN TECHNOLOGIES'),
(12956, '08:00:77', 'TSL COMMUNICATIONS LTD.'),
(12957, '08:00:78', 'ACCELL CORPORATION'),
(12958, '08:00:79', 'THE DROID WORKS'),
(12959, '08:00:7A', 'INDATA'),
(12960, '08:00:7B', 'SANYO ELECTRIC CO. LTD.'),
(12961, '08:00:7C', 'VITALINK COMMUNICATIONS CORP.'),
(12962, '08:00:7E', 'AMALGAMATED WIRELESS(AUS) LTD'),
(12963, '08:00:7F', 'CARNEGIE-MELLON UNIVERSITY'),
(12964, '08:00:80', 'AES DATA INC.'),
(12965, '08:00:81', 'ASTECH INC.'),
(12966, '08:00:82', 'VERITAS SOFTWARE'),
(12967, '08:00:83', 'Seiko Instruments Inc.'),
(12968, '08:00:84', 'TOMEN ELECTRONICS CORP.'),
(12969, '08:00:85', 'ELXSI'),
(12970, '08:00:86', 'KONICA MINOLTA HOLDINGS, INC.'),
(12971, '08:00:87', 'XYPLEX'),
(12972, '08:00:88', 'Brocade Communications Systems, Inc.'),
(12973, '08:00:89', 'KINETICS'),
(12974, '08:00:8A', 'PerfTech, Inc.'),
(12975, '08:00:8B', 'PYRAMID TECHNOLOGY CORP.'),
(12976, '08:00:8C', 'NETWORK RESEARCH CORPORATION'),
(12977, '08:00:8D', 'XYVISION INC.'),
(12978, '08:00:8E', 'TANDEM COMPUTERS'),
(12979, '08:00:8F', 'CHIPCOM CORPORATION'),
(12980, '08:00:90', 'SONOMA SYSTEMS'),
(12981, '08:03:71', 'KRG CORPORATE'),
(12982, '08:05:CD', 'DongGuang EnMai Electronic Product Co.Ltd.'),
(12983, '08:08:C2', 'Samsung Electronics'),
(12984, '08:08:EA', 'AMSC'),
(12985, '08:09:B6', 'Masimo Corp'),
(12986, '08:0C:0B', 'SysMik GmbH Dresden'),
(12987, '08:0C:C9', 'Mission Technology Group, dba Magma'),
(12988, '08:0D:84', 'GECO, Inc.'),
(12989, '08:0E:A8', 'Velex s.r.l.'),
(12990, '08:0F:FA', 'KSP INC.'),
(12991, '08:11:5E', 'Bitel Co., Ltd.'),
(12992, '08:11:96', 'Intel Corporate'),
(12993, '08:14:43', 'UNIBRAIN S.A.'),
(12994, '08:16:51', 'Shenzhen Sea Star Technology Co.,Ltd'),
(12995, '08:17:35', 'CISCO SYSTEMS, INC.'),
(12996, '08:17:F4', 'IBM Corp'),
(12997, '08:18:1A', 'zte corporation'),
(12998, '08:18:4C', 'A. S. Thomas, Inc.'),
(12999, '08:19:A6', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13000, '08:1D:FB', 'Shanghai Mexon Communication Technology Co.,Ltd'),
(13001, '08:1F:3F', 'WondaLink Inc.'),
(13002, '08:1F:F3', 'CISCO SYSTEMS, INC.'),
(13003, '08:25:22', 'ADVANSEE'),
(13004, '08:27:19', 'APS systems/electronic AG'),
(13005, '08:2A:D0', 'SRD Innovations Inc.'),
(13006, '08:2E:5F', 'Hewlett Packard'),
(13007, '08:35:71', 'CASwell INC.'),
(13008, '08:37:3D', 'Samsung Electronics Co.,Ltd'),
(13009, '08:37:9C', 'Topaz Co. LTD.'),
(13010, '08:38:A5', 'Funkwerk plettac electronic GmbH'),
(13011, '08:3A:B8', 'Shinoda Plasma Co., Ltd.'),
(13012, '08:3D:88', 'Samsung Electronics Co.,Ltd'),
(13013, '08:3E:0C', 'ARRIS Group, Inc.'),
(13014, '08:3E:8E', 'Hon Hai Precision Ind.Co.Ltd'),
(13015, '08:3F:3E', 'WSH GmbH'),
(13016, '08:3F:76', 'Intellian Technologies, Inc.'),
(13017, '08:40:27', 'Gridstore Inc.'),
(13018, '08:46:56', 'VODALYS Ing&eacute;nierie'),
(13019, '08:48:2C', 'Raycore Taiwan Co., LTD.'),
(13020, '08:4E:1C', 'H2A Systems, LLC'),
(13021, '08:4E:BF', 'Broad Net Mux Corporation'),
(13022, '08:51:2E', 'Orion Diagnostica Oy'),
(13023, '08:52:40', 'EbV Elektronikbau- und Vertriebs GmbH'),
(13024, '08:57:00', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13025, '08:5A:E0', 'Recovision Technology Co., Ltd.'),
(13026, '08:5B:0E', 'Fortinet, Inc.'),
(13027, '08:5D:DD', 'Mercury Corporation'),
(13028, '08:60:6E', 'ASUSTek COMPUTER INC.'),
(13029, '08:62:66', 'ASUSTek COMPUTER INC.'),
(13030, '08:63:61', 'Huawei Technologies Co., Ltd'),
(13031, '08:68:D0', 'Japan System Design'),
(13032, '08:68:EA', 'EITO ELECTRONICS CO., LTD.'),
(13033, '08:6D:F2', 'Shenzhen MIMOWAVE Technology Co.,Ltd'),
(13034, '08:70:45', 'Apple'),
(13035, '08:74:F6', 'Winterhalter Gastronom GmbH'),
(13036, '08:75:72', 'Obelux Oy'),
(13037, '08:76:18', 'ViE Technologies Sdn. Bhd.'),
(13038, '08:76:95', 'Auto Industrial Co., Ltd.'),
(13039, '08:76:FF', 'Thomson Telecom Belgium'),
(13040, '08:79:99', 'AIM GmbH'),
(13041, '08:7A:4C', 'Huawei Technologies Co., Ltd'),
(13042, '08:7B:AA', 'SVYAZKOMPLEKTSERVICE, LLC'),
(13043, '08:7C:BE', 'Quintic Corp.'),
(13044, '08:7D:21', 'Altasec technology corporation'),
(13045, '08:80:39', 'Cisco SPVTG'),
(13046, '08:81:BC', 'HongKong Ipro Technology Co., Limited'),
(13047, '08:81:F4', 'Juniper Networks'),
(13048, '08:86:3B', 'Belkin International, Inc.'),
(13049, '08:8D:C8', 'Ryowa Electronics Co.,Ltd'),
(13050, '08:8E:4F', 'SF Software Solutions'),
(13051, '08:8F:2C', 'Hills Sound Vision &amp; Lighting'),
(13052, '08:96:D7', 'AVM GmbH'),
(13053, '08:97:58', 'Shenzhen Strong Rising Electronics Co.,Ltd DongGuan Subsidiary'),
(13054, '08:9E:01', 'QUANTA COMPUTER INC.'),
(13055, '08:9F:97', 'LEROY AUTOMATION'),
(13056, '08:A1:2B', 'ShenZhen EZL Technology Co., Ltd'),
(13057, '08:A5:C8', 'Sunnovo International Limited'),
(13058, '08:A9:5A', 'Azurewave'),
(13059, '08:AC:A5', 'Benu Video, Inc.'),
(13060, '08:AF:78', 'Totus Solutions, Inc.'),
(13061, '08:B2:A3', 'Cynny Italia S.r.L.'),
(13062, '08:B4:CF', 'Abicom International'),
(13063, '08:B7:38', 'Lite-On Technogy Corp.'),
(13064, '08:B7:EC', 'Wireless Seismic'),
(13065, '08:BB:CC', 'AK-NORD EDV VERTRIEBSGES. mbH'),
(13066, '08:BD:43', 'NETGEAR INC.,'),
(13067, '08:BE:09', 'Astrol Electronic AG'),
(13068, '08:CA:45', 'Toyou Feiji Electronics Co., Ltd.'),
(13069, '08:CC:68', 'Cisco'),
(13070, '08:CD:9B', 'samtec automotive electronics &amp; software GmbH'),
(13071, '08:D0:9F', 'CISCO SYSTEMS, INC.'),
(13072, '08:D2:9A', 'Proformatique'),
(13073, '08:D3:4B', 'Techman Electronics (Changshu) Co., Ltd.'),
(13074, '08:D4:0C', 'Intel Corporate'),
(13075, '08:D4:2B', 'Samsung Electronics'),
(13076, '08:D5:C0', 'Seers Technology Co., Ltd'),
(13077, '08:D8:33', 'Shenzhen RF Technology Co,.Ltd'),
(13078, '08:DF:1F', 'Bose Corporation'),
(13079, '08:E5:DA', 'NANJING FUJITSU COMPUTER PRODUCTS CO.,LTD.'),
(13080, '08:E6:72', 'JEBSEE ELECTRONICS CO.,LTD.'),
(13081, '08:E8:4F', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13082, '08:EA:44', 'Aerohive Networks, Inc.'),
(13083, '08:EB:29', 'Jiangsu Huitong Group Co.,Ltd.'),
(13084, '08:EB:74', 'Humax'),
(13085, '08:EB:ED', 'World Elite Technology Co.,LTD'),
(13086, '08:ED:B9', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13087, '08:EE:8B', 'Samsung Elec Co.,Ltd'),
(13088, '08:EF:3B', 'MCS Logic Inc.'),
(13089, '08:EF:AB', 'SAYME WIRELESS SENSOR NETWORK'),
(13090, '08:F1:B7', 'Towerstream Corpration'),
(13091, '08:F2:F4', 'Net One Partners Co.,Ltd.'),
(13092, '08:F6:F8', 'GET Engineering'),
(13093, '08:F7:28', 'GLOBO Multimedia Sp. z o.o. Sp.k.'),
(13094, '08:FA:E0', 'Fohhn Audio AG'),
(13095, '08:FC:52', 'OpenXS BV'),
(13096, '08:FC:88', 'Samsung Electronics Co.,Ltd'),
(13097, '08:FD:0E', 'Samsung Electronics Co.,Ltd'),
(13098, '0C:04:00', 'Jantar d.o.o.'),
(13099, '0C:05:35', 'Juniper Systems'),
(13100, '0C:11:05', 'Ringslink (Xiamen) Network Communication Technologies Co., Ltd'),
(13101, '0C:12:62', 'zte corporation'),
(13102, '0C:13:0B', 'Uniqoteq Ltd.'),
(13103, '0C:14:20', 'Samsung Electronics Co.,Ltd'),
(13104, '0C:15:39', 'Apple'),
(13105, '0C:15:C5', 'SDTEC Co., Ltd.'),
(13106, '0C:17:F1', 'TELECSYS'),
(13107, '0C:19:1F', 'Inform Electronik'),
(13108, '0C:1D:AF', 'Beijing Xiaomi communications co.,ltd'),
(13109, '0C:1D:C2', 'SeAH Networks'),
(13110, '0C:20:26', 'noax Technologies AG'),
(13111, '0C:27:24', 'Cisco'),
(13112, '0C:27:55', 'Valuable Techologies Limited'),
(13113, '0C:2A:69', 'electric imp, incorporated'),
(13114, '0C:2A:E7', 'Beijing General Research Institute of Mining and Metallurgy'),
(13115, '0C:2D:89', 'QiiQ Communications Inc.'),
(13116, '0C:30:21', 'Apple'),
(13117, '0C:37:DC', 'Huawei Technologies Co., Ltd'),
(13118, '0C:38:3E', 'Fanvil Technology Co., Ltd.'),
(13119, '0C:39:56', 'Observator instruments'),
(13120, '0C:3C:65', 'Dome Imaging Inc'),
(13121, '0C:3E:9F', 'Apple, Inc'),
(13122, '0C:46:9D', 'MS Sedco'),
(13123, '0C:47:3D', 'Hitron Technologies. Inc'),
(13124, '0C:48:85', 'LG Electronics'),
(13125, '0C:4C:39', 'Mitrastar Technology'),
(13126, '0C:4D:E9', 'Apple'),
(13127, '0C:4F:5A', 'ASA-RT s.r.l.'),
(13128, '0C:51:F7', 'CHAUVIN ARNOUX'),
(13129, '0C:54:A5', 'PEGATRON CORPORATION'),
(13130, '0C:55:21', 'Axiros GmbH'),
(13131, '0C:56:5C', 'HyBroad Vision (Hong Kong) Technology Co Ltd'),
(13132, '0C:57:EB', 'Mueller Systems'),
(13133, '0C:5A:19', 'Axtion Sdn Bhd'),
(13134, '0C:5C:D8', 'DOLI Elektronik GmbH'),
(13135, '0C:60:76', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13136, '0C:63:FC', 'Nanjing Signway Technology Co., Ltd'),
(13137, '0C:68:03', 'Cisco'),
(13138, '0C:6E:4F', 'PrimeVOLT Co., Ltd.'),
(13139, '0C:71:5D', 'Samsung Electronics Co.,Ltd'),
(13140, '0C:72:2C', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13141, '0C:74:C2', 'Apple'),
(13142, '0C:75:23', 'BEIJING GEHUA CATV NETWORK CO.,LTD'),
(13143, '0C:77:1A', 'Apple'),
(13144, '0C:7D:7C', 'Kexiang Information Technology Co, Ltd.'),
(13145, '0C:81:12', 'PRIVATE'),
(13146, '0C:82:30', 'SHENZHEN MAGNUS TECHNOLOGIES CO.,LTD'),
(13147, '0C:82:68', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13148, '0C:82:6A', 'Wuhan Huagong Genuine Optics Technology Co., Ltd'),
(13149, '0C:84:11', 'A.O. Smith Water Products'),
(13150, '0C:84:84', 'Zenovia Electronics Inc.'),
(13151, '0C:84:DC', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13152, '0C:85:25', 'CISCO SYSTEMS, INC.'),
(13153, '0C:89:10', 'Samsung Electronics Co.,LTD'),
(13154, '0C:8B:FD', 'Intel Corporate'),
(13155, '0C:8C:8F', 'Kamo Technology Limited'),
(13156, '0C:8C:DC', 'Suunto Oy'),
(13157, '0C:8D:98', 'TOP EIGHT IND CORP'),
(13158, '0C:92:4E', 'Rice Lake Weighing Systems'),
(13159, '0C:93:01', 'PT. Prasimax Inovasi Teknologi'),
(13160, '0C:93:FB', 'BNS Solutions'),
(13161, '0C:96:BF', 'Huawei Technologies Co., Ltd'),
(13162, '0C:9B:13', 'Shanghai Magic Mobile Telecommunication Co.Ltd.'),
(13163, '0C:9D:56', 'Consort Controls Ltd'),
(13164, '0C:9E:91', 'Sankosha Corporation'),
(13165, '0C:A1:38', 'Blinq Wireless Inc.'),
(13166, '0C:A2:F4', 'Chameleon Technology (UK) Limited'),
(13167, '0C:A4:02', 'Alcatel Lucent IPD'),
(13168, '0C:A4:2A', 'OB Telecom Electronic Technology Co., Ltd'),
(13169, '0C:A6:94', 'Sunitec Enterprise Co.,Ltd'),
(13170, '0C:AC:05', 'Unitend Technologies Inc.'),
(13171, '0C:AF:5A', 'GENUS POWER INFRASTRUCTURES LIMITED'),
(13172, '0C:B3:19', 'Samsung Elec Co.,Ltd'),
(13173, '0C:B4:EF', 'Digience Co.,Ltd.'),
(13174, '0C:B5:DE', 'Alcatel Lucent'),
(13175, '0C:BC:9F', 'Apple'),
(13176, '0C:BD:51', 'TCT Mobile Limited'),
(13177, '0C:BF:15', 'Genetec'),
(13178, '0C:C0:C0', 'MAGNETI MARELLI SISTEMAS ELECTRONICOS MEXICO'),
(13179, '0C:C3:A7', 'Meritec'),
(13180, '0C:C4:7A', 'Super Micro Computer, Inc.'),
(13181, '0C:C4:7E', 'EUCAST Co., Ltd.'),
(13182, '0C:C6:55', 'Wuxi YSTen Technology Co.,Ltd.'),
(13183, '0C:C6:6A', 'Nokia Corporation'),
(13184, '0C:C6:AC', 'DAGS'),
(13185, '0C:C8:1F', 'Summer Infant, Inc.'),
(13186, '0C:C9:C6', 'Samwin Hong Kong Limited'),
(13187, '0C:CB:8D', 'ASCO Numatics GmbH'),
(13188, '0C:CD:D3', 'EASTRIVER TECHNOLOGY CO., LTD.'),
(13189, '0C:CD:FB', 'EDIC Systems Inc.'),
(13190, '0C:CF:D1', 'SPRINGWAVE Co., Ltd'),
(13191, '0C:D2:92', 'Intel Corporate'),
(13192, '0C:D2:B5', 'Binatone Telecommunication Pvt. Ltd'),
(13193, '0C:D5:02', 'Westell'),
(13194, '0C:D6:96', 'Amimon Ltd'),
(13195, '0C:D7:C2', 'Axium Technologies, Inc.'),
(13196, '0C:D9:96', 'CISCO SYSTEMS, INC.'),
(13197, '0C:D9:C1', 'Visteon Corporation'),
(13198, '0C:DA:41', 'Hangzhou H3C Technologies Co., Limited'),
(13199, '0C:DC:CC', 'Inala Technologies'),
(13200, '0C:DD:EF', 'Nokia Corporation'),
(13201, '0C:DF:A4', 'Samsung Electronics Co.,Ltd'),
(13202, '0C:E0:E4', 'Plantronics, Inc'),
(13203, '0C:E5:D3', 'DH electronics GmbH'),
(13204, '0C:E7:09', 'Fox Crypto B.V.'),
(13205, '0C:E8:2F', 'Bonfiglioli Vectron GmbH'),
(13206, '0C:E9:36', 'ELIMOS srl'),
(13207, '0C:EE:E6', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13208, '0C:EF:7C', 'AnaCom Inc'),
(13209, '0C:EF:AF', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(13210, '0C:F0:19', 'Malgn Technology Co., Ltd.'),
(13211, '0C:F0:B4', 'Globalsat International Technology Ltd'),
(13212, '0C:F3:61', 'Java Information'),
(13213, '0C:F3:EE', 'EM Microelectronic'),
(13214, '0C:F4:05', 'Beijing Signalway Technologies Co.,Ltd'),
(13215, '0C:F5:A4', 'Cisco'),
(13216, '0C:F8:93', 'ARRIS Group, Inc.'),
(13217, '0C:FC:83', 'Airoha Technology Corp.,'),
(13218, '10:00:00', 'PRIVATE'),
(13219, '10:00:5A', 'IBM Corp'),
(13220, '10:00:E8', 'NATIONAL SEMICONDUCTOR'),
(13221, '10:00:FD', 'LaonPeople'),
(13222, '10:01:CA', 'Ashley Butterworth'),
(13223, '10:05:CA', 'Cisco'),
(13224, '10:07:23', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(13225, '10:08:B1', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13226, '10:09:0C', 'Janome Sewing Machine Co., Ltd.'),
(13227, '10:0B:A9', 'Intel Corporate'),
(13228, '10:0C:24', 'pomdevices, LLC'),
(13229, '10:0D:2F', 'Online Security Pty. Ltd.'),
(13230, '10:0D:32', 'Embedian, Inc.'),
(13231, '10:0D:7F', 'NETGEAR INC.,'),
(13232, '10:0E:2B', 'NEC CASIO Mobile Communications'),
(13233, '10:0E:7E', 'Juniper networks'),
(13234, '10:0F:18', 'Fu Gang Electronic(KunShan)CO.,LTD'),
(13235, '10:10:B6', 'McCain Inc'),
(13236, '10:12:12', 'Vivo International Corporation Pty Ltd'),
(13237, '10:12:18', 'Korins Inc.'),
(13238, '10:12:48', 'ITG, Inc.'),
(13239, '10:13:EE', 'Justec International Technology INC.'),
(13240, '10:18:9E', 'Elmo Motion Control'),
(13241, '10:1B:54', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13242, '10:1C:0C', 'Apple'),
(13243, '10:1D:51', 'ON-Q LLC dba ON-Q Mesh Networks'),
(13244, '10:1D:C0', 'Samsung Electronics Co.,Ltd'),
(13245, '10:1F:74', 'Hewlett-Packard Company'),
(13246, '10:22:79', 'ZeroDesktop, Inc.'),
(13247, '10:27:BE', 'TVIP'),
(13248, '10:28:31', 'Morion Inc.'),
(13249, '10:2C:83', 'XIMEA'),
(13250, '10:2D:96', 'Looxcie Inc.'),
(13251, '10:2E:AF', 'Texas Instruments'),
(13252, '10:2F:6B', 'Microsoft Corporation'),
(13253, '10:30:47', 'Samsung Electronics Co.,Ltd'),
(13254, '10:33:78', 'FLECTRON Co., LTD'),
(13255, '10:37:11', 'Simlink AS'),
(13256, '10:3B:59', 'Samsung Electronics Co.,Ltd'),
(13257, '10:3D:EA', 'HFC Technology (Beijing) Ltd. Co.'),
(13258, '10:40:F3', 'Apple'),
(13259, '10:43:69', 'Soundmax Electronic Limited'),
(13260, '10:44:5A', 'Shaanxi Hitech Electronic Co., LTD'),
(13261, '10:45:BE', 'Norphonic AS'),
(13262, '10:45:F8', 'LNT-Automation GmbH'),
(13263, '10:47:80', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13264, '10:48:B1', 'Beijing Duokan Technology Limited'),
(13265, '10:4A:7D', 'Intel Corporate'),
(13266, '10:4B:46', 'Mitsubishi Electric Corporation'),
(13267, '10:4D:77', 'Innovative Computer Engineering'),
(13268, '10:4E:07', 'Shanghai Genvision Industries Co.,Ltd'),
(13269, '10:51:72', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13270, '10:56:CA', 'Peplink International Ltd.'),
(13271, '10:5C:3B', 'Perma-Pipe, Inc.'),
(13272, '10:5C:BF', 'DuroByte Inc'),
(13273, '10:5F:06', 'Actiontec Electronics, Inc'),
(13274, '10:5F:49', 'Cisco SPVTG'),
(13275, '10:60:4B', 'Hewlett Packard'),
(13276, '10:62:C9', 'Adatis GmbH &amp; Co. KG'),
(13277, '10:64:E2', 'ADFweb.com s.r.l.'),
(13278, '10:65:A3', 'Core Brands LLC'),
(13279, '10:65:CF', 'IQSIM'),
(13280, '10:66:82', 'NEC Platforms, Ltd.'),
(13281, '10:68:3F', 'LG Electronics'),
(13282, '10:6F:3F', 'Buffalo Inc.'),
(13283, '10:6F:EF', 'Ad-Sol Nissin Corp'),
(13284, '10:71:F9', 'Cloud Telecomputers, LLC'),
(13285, '10:76:8A', 'EoCell'),
(13286, '10:77:B1', 'Samsung Electronics Co.,LTD'),
(13287, '10:78:73', 'Shenzhen Jinkeyi Communication Co., Ltd.'),
(13288, '10:78:CE', 'Hanvit SI, Inc.'),
(13289, '10:78:D2', 'ELITEGROUP COMPUTER SYSTEM CO., LTD.'),
(13290, '10:7A:86', 'U&amp;U ENGINEERING INC.'),
(13291, '10:7B:EF', 'ZyXEL Communications Corp'),
(13292, '10:83:D2', 'Microseven Systems, LLC'),
(13293, '10:88:0F', 'Daruma Telecomunica&ccedil;&otilde;es e Inform&aacute;tica S.A.'),
(13294, '10:88:CE', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(13295, '10:8A:1B', 'RAONIX Inc.'),
(13296, '10:8C:CF', 'CISCO SYSTEMS, INC.'),
(13297, '10:92:66', 'Samsung Electronics Co.,Ltd'),
(13298, '10:93:E9', 'Apple'),
(13299, '10:9A:B9', 'Tosibox Oy'),
(13300, '10:9A:DD', 'Apple'),
(13301, '10:9F:A9', 'Actiontec Electronics, Inc'),
(13302, '10:A1:3B', 'FUJIKURA RUBBER LTD.'),
(13303, '10:A5:D0', 'Murata Manufacturing Co.,Ltd.'),
(13304, '10:A6:59', 'Mobile Create Co.,Ltd.'),
(13305, '10:A7:43', 'SK Mtek Limited'),
(13306, '10:A9:32', 'Beijing Cyber Cloud Technology Co. ,Ltd.'),
(13307, '10:AE:60', 'PRIVATE'),
(13308, '10:B2:6B', 'base Co.,Ltd.'),
(13309, '10:B7:13', 'PRIVATE'),
(13310, '10:B7:F6', 'Plastoform Industries Ltd.'),
(13311, '10:B9:FE', 'Lika srl'),
(13312, '10:BA:A5', 'GANA I&amp;C CO., LTD'),
(13313, '10:BD:18', 'CISCO SYSTEMS, INC.'),
(13314, '10:BF:48', 'ASUSTEK COMPUTER INC.'),
(13315, '10:C0:7C', 'Blu-ray Disc Association'),
(13316, '10:C2:BA', 'UTT Co., Ltd.'),
(13317, '10:C3:7B', 'ASUSTek COMPUTER INC.'),
(13318, '10:C5:86', 'BIO SOUND LAB CO., LTD.'),
(13319, '10:C6:1F', 'Huawei Technologies Co., Ltd'),
(13320, '10:C6:7E', 'SHENZHEN JUCHIN TECHNOLOGY CO., LTD'),
(13321, '10:C6:FC', 'Garmin International'),
(13322, '10:C7:3F', 'Midas Klark Teknik Ltd'),
(13323, '10:CA:81', 'PRECIA'),
(13324, '10:CC:DB', 'AXIMUM PRODUITS ELECTRONIQUES'),
(13325, '10:D1:DC', 'INSTAR Deutschland GmbH'),
(13326, '10:D3:8A', 'Samsung Electronics Co.,Ltd'),
(13327, '10:D5:42', 'Samsung Electronics Co.,Ltd'),
(13328, '10:DD:B1', 'Apple'),
(13329, '10:DD:F4', 'Maxway Electronics CO.,LTD'),
(13330, '10:DE:E4', 'automationNEXT GmbH'),
(13331, '10:E2:D5', 'Qi Hardware Inc.'),
(13332, '10:E3:C7', 'Seohwa Telecom'),
(13333, '10:E4:AF', 'APR, LLC'),
(13334, '10:E6:AE', 'Source Technologies, LLC'),
(13335, '10:E8:78', 'Alcatel-Lucent'),
(13336, '10:E8:EE', 'PhaseSpace'),
(13337, '10:EA:59', 'Cisco SPVTG'),
(13338, '10:EE:D9', 'Canoga Perkins Corporation'),
(13339, '10:F3:11', 'Cisco'),
(13340, '10:F3:DB', 'Gridco Systems, Inc.'),
(13341, '10:F4:9A', 'T3 Innovation'),
(13342, '10:F6:81', 'vivo Mobile Communication Co., Ltd.'),
(13343, '10:F9:6F', 'LG Electronics'),
(13344, '10:F9:EE', 'Nokia Corporation'),
(13345, '10:FA:CE', 'Reacheng Communication Technology Co.,Ltd'),
(13346, '10:FB:F0', 'KangSheng LTD.'),
(13347, '10:FC:54', 'Shany Electronic Co., Ltd.'),
(13348, '10:FE:ED', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(13349, '11:00:AA', 'PRIVATE'),
(13350, '14:07:08', 'PRIVATE'),
(13351, '14:07:E0', 'Abrantix AG'),
(13352, '14:0C:76', 'FREEBOX SAS'),
(13353, '14:0D:4F', 'Flextronics International'),
(13354, '14:10:9F', 'Apple'),
(13355, '14:13:30', 'Anakreon UK LLP'),
(13356, '14:14:4B', 'FUJIAN STAR-NET COMMUNICATION CO.,LTD'),
(13357, '14:1A:51', 'Treetech Sistemas Digitais'),
(13358, '14:1A:A3', 'Motorola Mobility LLC'),
(13359, '14:1B:BD', 'Volex Inc.'),
(13360, '14:1B:F0', 'Intellimedia Systems Ltd'),
(13361, '14:1F:BA', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(13362, '14:23:D7', 'EUTRONIX CO., LTD.'),
(13363, '14:29:71', 'NEMOA ELECTRONICS (HK) CO. LTD'),
(13364, '14:2B:D2', 'Armtel Ltd.'),
(13365, '14:2B:D6', 'Guangdong Appscomm Co.,Ltd'),
(13366, '14:2D:27', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13367, '14:2D:8B', 'Incipio Technologies, Inc'),
(13368, '14:2D:F5', 'Amphitech'),
(13369, '14:30:7A', 'Avermetrics'),
(13370, '14:30:C6', 'Motorola Mobility LLC'),
(13371, '14:35:8B', 'Mediabridge Products, LLC.'),
(13372, '14:35:B3', 'Future Designs, Inc.'),
(13373, '14:36:05', 'Nokia Corporation'),
(13374, '14:36:C6', 'Lenovo Mobile Communication Technology Ltd.'),
(13375, '14:37:3B', 'PROCOM Systems'),
(13376, '14:3A:EA', 'Dynapower Company LLC'),
(13377, '14:3D:F2', 'Beijing Shidai Hongyuan Network Communication Co.,Ltd'),
(13378, '14:3E:60', 'Alcatel-Lucent'),
(13379, '14:41:E2', 'Monaco Enterprises, Inc.'),
(13380, '14:43:19', 'Creative&amp;Link Technology Limited'),
(13381, '14:46:E4', 'AVISTEL'),
(13382, '14:48:8B', 'Shenzhen Doov Technology Co.,Ltd'),
(13383, '14:49:78', 'Digital Control Incorporated'),
(13384, '14:49:E0', 'Samsung Electro Mechanics co.,LTD.'),
(13385, '14:4C:1A', 'Max Communication GmbH'),
(13386, '14:54:12', 'Entis Co., Ltd.'),
(13387, '14:56:45', 'Savitech Corp.'),
(13388, '14:58:D0', 'Hewlett Packard'),
(13389, '14:5A:05', 'Apple'),
(13390, '14:5B:D1', 'ARRIS Group, Inc.'),
(13391, '14:60:80', 'zte corporation'),
(13392, '14:63:08', 'JABIL CIRCUIT (SHANGHAI) LTD.'),
(13393, '14:6A:0B', 'Cypress Electronics Limited'),
(13394, '14:6B:72', 'Shenzhen Fortune Ship Technology Co., Ltd.'),
(13395, '14:6E:0A', 'PRIVATE'),
(13396, '14:73:73', 'TUBITAK UEKAE'),
(13397, '14:74:11', 'RIM'),
(13398, '14:75:90', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13399, '14:7D:B3', 'JOA TELECOM.CO.,LTD'),
(13400, '14:7D:C5', 'Murata Manufacturing Co., Ltd.'),
(13401, '14:82:5B', 'Hefei Radio Communication Technology Co., Ltd'),
(13402, '14:86:92', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13403, '14:89:3E', 'VIXTEL TECHNOLOGIES LIMTED'),
(13404, '14:89:FD', 'Samsung Electronics'),
(13405, '14:8A:70', 'ADS GmbH'),
(13406, '14:8F:21', 'Garmin International'),
(13407, '14:8F:C6', 'Apple'),
(13408, '14:90:90', 'KongTop industrial(shen zhen)CO.,LTD'),
(13409, '14:94:48', 'BLU CASTLE S.A.'),
(13410, '14:99:E2', 'Apple, Inc'),
(13411, '14:9F:E8', 'Lenovo Mobile Communication Technology Ltd.'),
(13412, '14:A3:64', 'Samsung Electronics Co.,Ltd'),
(13413, '14:A6:2C', 'S.M. Dezac S.A.'),
(13414, '14:A8:6B', 'ShenZhen Telacom Science&amp;Technology Co., Ltd'),
(13415, '14:A9:E3', 'MST CORPORATION'),
(13416, '14:AB:F0', 'ARRIS Group, Inc.'),
(13417, '14:B1:26', 'Industrial Software Co'),
(13418, '14:B1:C8', 'InfiniWing, Inc.'),
(13419, '14:B4:84', 'Samsung Electronics Co.,Ltd'),
(13420, '14:B7:3D', 'ARCHEAN Technologies'),
(13421, '14:B9:68', 'Huawei Technologies Co., Ltd'),
(13422, '14:C0:89', 'DUNE HD LTD'),
(13423, '14:C1:26', 'Nokia Corporation'),
(13424, '14:C2:1D', 'Sabtech Industries'),
(13425, '14:CC:20', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(13426, '14:CF:8D', 'OHSUNG ELECTRONICS CO., LTD.'),
(13427, '14:CF:92', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(13428, '14:CF:E2', 'ARRIS Group, Inc.'),
(13429, '14:D4:FE', 'Pace plc'),
(13430, '14:D6:4D', 'D-Link International'),
(13431, '14:D7:6E', 'CONCH ELECTRONIC Co.,Ltd'),
(13432, '14:DA:E9', 'ASUSTek COMPUTER INC.'),
(13433, '14:DB:85', 'S NET MEDIA'),
(13434, '14:E4:EC', 'mLogic LLC'),
(13435, '14:E6:E4', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(13436, '14:EB:33', 'BSMediasoft Co., Ltd.'),
(13437, '14:ED:A5', 'WaÌchter GmbH Sicherheitssysteme'),
(13438, '14:ED:E4', 'Kaiam Corporation'),
(13439, '14:EE:9D', 'AirNav Systems LLC'),
(13440, '14:F0:C5', 'Xtremio Ltd.'),
(13441, '14:F2:8E', 'ShenYang ZhongKe-Allwin Technology Co.LTD'),
(13442, '14:F4:2A', 'Samsung Electronics'),
(13443, '14:F6:5A', 'Xiaomi inc.'),
(13444, '14:F8:93', 'Wuhan FiberHome Digital Technology Co.,Ltd.'),
(13445, '14:FE:AF', 'SAGITTAR LIMITED'),
(13446, '14:FE:B5', 'Dell Inc'),
(13447, '18:00:2D', 'Sony Mobile Communications AB'),
(13448, '18:00:DB', 'Fitbit Inc.'),
(13449, '18:01:E3', 'Elektrobit Wireless Communications Ltd'),
(13450, '18:03:73', 'Dell Inc'),
(13451, '18:03:FA', 'IBT Interfaces'),
(13452, '18:06:75', 'DILAX Intelcom GmbH'),
(13453, '18:0B:52', 'Nanotron Technologies GmbH'),
(13454, '18:0C:14', 'iSonea Limited'),
(13455, '18:0C:77', 'Westinghouse Electric Company, LLC'),
(13456, '18:0C:AC', 'CANON INC.'),
(13457, '18:10:4E', 'CEDINT-UPM'),
(13458, '18:14:20', 'TEB SAS'),
(13459, '18:14:56', 'Nokia Corporation'),
(13460, '18:17:14', 'DAEWOOIS'),
(13461, '18:17:25', 'Cameo Communications, Inc.'),
(13462, '18:19:3F', 'Tamtron Oy'),
(13463, '18:1B:EB', 'Actiontec Electronics, Inc'),
(13464, '18:1E:78', 'SAGEMCOM'),
(13465, '18:1E:B0', 'Samsung Electronics Co.,Ltd'),
(13466, '18:20:12', 'Aztech Associates Inc.'),
(13467, '18:20:32', 'Apple'),
(13468, '18:20:A6', 'Sage Co., Ltd.'),
(13469, '18:22:7E', 'Samsung Electronics Co.,Ltd'),
(13470, '18:26:66', 'Samsung Electronics Co.,Ltd'),
(13471, '18:28:61', 'AirTies Wireless Networks'),
(13472, '18:2A:7B', 'Nintendo Co., Ltd.'),
(13473, '18:2B:05', '8D Technologies'),
(13474, '18:2C:91', 'Concept Development, Inc.'),
(13475, '18:30:09', 'Woojin Industrial Systems Co., Ltd.'),
(13476, '18:32:A2', 'LAON TECHNOLOGY CO., LTD.'),
(13477, '18:33:9D', 'CISCO SYSTEMS, INC.'),
(13478, '18:34:51', 'Apple'),
(13479, '18:36:FC', 'Elecsys International Corporation'),
(13480, '18:38:25', 'Wuhan Lingjiu High-tech Co.,Ltd.'),
(13481, '18:38:64', 'CAP-TECH INTERNATIONAL CO., LTD.'),
(13482, '18:39:19', 'Unicoi Systems'),
(13483, '18:3A:2D', 'Samsung Electronics Co.,Ltd'),
(13484, '18:3B:D2', 'BYD Precision Manufacture Company Ltd.'),
(13485, '18:3D:A2', 'Intel Corporate'),
(13486, '18:3F:47', 'Samsung Electronics Co.,Ltd'),
(13487, '18:42:1D', 'PRIVATE'),
(13488, '18:42:2F', 'Alcatel Lucent'),
(13489, '18:44:62', 'Riava Networks, Inc.'),
(13490, '18:46:17', 'Samsung Electronics'),
(13491, '18:48:D8', 'Fastback Networks'),
(13492, '18:4A:6F', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(13493, '18:4E:94', 'MESSOA TECHNOLOGIES INC.'),
(13494, '18:52:53', 'Pixord Corporation'),
(13495, '18:53:E0', 'Hanyang Digitech Co.Ltd'),
(13496, '18:55:0F', 'Cisco SPVTG'),
(13497, '18:59:33', 'Cisco SPVTG'),
(13498, '18:59:36', 'XIAOMI INC'),
(13499, '18:5A:E8', 'Zenotech.Co.,Ltd'),
(13500, '18:62:2C', 'SAGEMCOM SAS'),
(13501, '18:64:72', 'Aruba Networks'),
(13502, '18:65:71', 'Top Victory Electronics (Taiwan) Co., Ltd.'),
(13503, '18:66:E3', 'Veros Systems, Inc.'),
(13504, '18:67:3F', 'Hanover Displays Limited'),
(13505, '18:67:51', 'KOMEG Industrielle Messtechnik GmbH'),
(13506, '18:67:B0', 'Samsung Electronics Co.,LTD'),
(13507, '18:68:82', 'Beward R&amp;D Co., Ltd.'),
(13508, '18:6D:99', 'Adanis Inc.'),
(13509, '18:71:17', 'eta plus electronic gmbh'),
(13510, '18:79:A2', 'GMJ ELECTRIC LIMITED'),
(13511, '18:7A:93', 'AMICCOM Electronics Corporation'),
(13512, '18:7C:81', 'Valeo Vision Systems'),
(13513, '18:7E:D5', 'shenzhen kaism technology Co. Ltd'),
(13514, '18:80:CE', 'Barberry Solutions Ltd'),
(13515, '18:80:F5', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(13516, '18:82:19', 'Alibaba Cloud Computing Ltd.'),
(13517, '18:83:31', 'Samsung Electronics Co.,Ltd'),
(13518, '18:83:BF', 'Arcadyan Technology Corporation'),
(13519, '18:84:10', 'CoreTrust Inc.'),
(13520, '18:86:3A', 'DIGITAL ART SYSTEM'),
(13521, '18:86:AC', 'Nokia Danmark A/S'),
(13522, '18:87:96', 'HTC Corporation'),
(13523, '18:88:57', 'Beijing Jinhong Xi-Dian Information Technology Corp.'),
(13524, '18:89:DF', 'CerebrEX Inc.'),
(13525, '18:8E:D5', 'TP Vision Belgium N.V. - innovation site Brugge'),
(13526, '18:92:2C', 'Virtual Instruments'),
(13527, '18:97:FF', 'TechFaith Wireless Technology Limited'),
(13528, '18:9A:67', 'CSE-Servelec Limited'),
(13529, '18:9C:5D', 'Cisco'),
(13530, '18:9E:FC', 'Apple'),
(13531, '18:A3:E8', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(13532, '18:A9:05', 'Hewlett-Packard Company'),
(13533, '18:A9:58', 'PROVISION THAI CO., LTD.'),
(13534, '18:A9:9B', 'Dell Inc'),
(13535, '18:AA:45', 'Fon Technology'),
(13536, '18:AB:F5', 'Ultra Electronics - Electrics'),
(13537, '18:AD:4D', 'Polostar Technology Corporation'),
(13538, '18:AE:BB', 'Siemens Convergence Creators GmbH&amp;Co.KG'),
(13539, '18:AF:61', 'Apple, Inc'),
(13540, '18:AF:8F', 'Apple'),
(13541, '18:AF:9F', 'DIGITRONIC Automationsanlagen GmbH'),
(13542, '18:B1:69', 'Sonicwall'),
(13543, '18:B2:09', 'Torrey Pines Logic, Inc'),
(13544, '18:B3:BA', 'Netlogic AB'),
(13545, '18:B4:30', 'Nest Labs Inc.'),
(13546, '18:B5:91', 'I-Storm'),
(13547, '18:B7:9E', 'Invoxia'),
(13548, '18:BD:AD', 'L-TECH CORPORATION'),
(13549, '18:C0:86', 'Broadcom Corporation'),
(13550, '18:C4:51', 'Tucson Embedded Systems'),
(13551, '18:C5:8A', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13552, '18:C8:E7', 'Shenzhen Hualistone Technology Co.,Ltd'),
(13553, '18:CC:23', 'Philio Technology Corporation'),
(13554, '18:CF:5E', 'Liteon Technology Corporation'),
(13555, '18:D0:71', 'DASAN CO., LTD.'),
(13556, '18:D5:B6', 'SMG Holdings LLC'),
(13557, '18:D6:6A', 'Inmarsat'),
(13558, '18:D6:CF', 'Kurth Electronic GmbH'),
(13559, '18:D9:49', 'Qvis Labs, LLC'),
(13560, '18:DC:56', 'Yulong Computer Telecommunication Scientific(shenzhen)Co.,Lt'),
(13561, '18:E2:88', 'STT Condigi'),
(13562, '18:E2:C2', 'Samsung Electronics'),
(13563, '18:E7:28', 'Cisco'),
(13564, '18:E7:F4', 'Apple'),
(13565, '18:E8:0F', 'Viking Electronics Inc.'),
(13566, '18:E8:DD', 'MODULETEK'),
(13567, '18:EE:69', 'Apple'),
(13568, '18:EF:63', 'CISCO SYSTEMS, INC.'),
(13569, '18:F1:45', 'NetComm Wireless Limited'),
(13570, '18:F4:6A', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13571, '18:F6:43', 'Apple'),
(13572, '18:F6:50', 'Multimedia Pacific Limited'),
(13573, '18:F8:7A', 'i3 International Inc.'),
(13574, '18:FA:6F', 'ISC applied systems corp'),
(13575, '18:FB:7B', 'Dell Inc'),
(13576, '18:FC:9F', 'Changhe Electronics Co., Ltd.'),
(13577, '18:FE:34', 'Espressif Inc.'),
(13578, '18:FF:0F', 'Intel Corporate'),
(13579, '18:FF:2E', 'Shenzhen Rui Ying Da Technology Co., Ltd'),
(13580, '1C:06:56', 'IDY Corporation'),
(13581, '1C:08:C1', 'Lg Innotek'),
(13582, '1C:0B:52', 'EPICOM S.A'),
(13583, '1C:0F:CF', 'Sypro Optics GmbH'),
(13584, '1C:11:E1', 'Wartsila Finland Oy'),
(13585, '1C:12:9D', 'IEEE PES PSRC/SUB'),
(13586, '1C:14:48', 'ARRIS Group, Inc.'),
(13587, '1C:14:B3', 'Pinyon Technologies'),
(13588, '1C:17:D3', 'CISCO SYSTEMS, INC.'),
(13589, '1C:18:4A', 'ShenZhen RicherLink Technologies Co.,LTD'),
(13590, '1C:19:DE', 'eyevis GmbH'),
(13591, '1C:1A:C0', 'Apple'),
(13592, '1C:1B:68', 'ARRIS Group, Inc.'),
(13593, '1C:1C:FD', 'Dalian Hi-Think Computer Technology, Corp'),
(13594, '1C:1D:67', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(13595, '1C:1D:86', 'Cisco'),
(13596, '1C:33:4D', 'ITS Telecom'),
(13597, '1C:34:77', 'Innovation Wireless'),
(13598, '1C:35:F1', 'NEW Lift Neue Elektronische Wege Steuerungsbau GmbH'),
(13599, '1C:37:BF', 'Cloudium Systems Ltd.'),
(13600, '1C:39:47', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(13601, '1C:3A:4F', 'AccuSpec Electronics, LLC'),
(13602, '1C:3D:E7', 'Sigma Koki Co.,Ltd.'),
(13603, '1C:3E:84', 'Hon Hai Precision Ind. Co.,Ltd.'),
(13604, '1C:41:58', 'Gemalto M2M GmbH'),
(13605, '1C:43:EC', 'JAPAN CIRCUIT CO.,LTD'),
(13606, '1C:45:93', 'Texas Instruments'),
(13607, '1C:48:40', 'IMS Messsysteme GmbH'),
(13608, '1C:48:F9', 'GN Netcom A/S'),
(13609, '1C:4A:F7', 'AMON INC'),
(13610, '1C:4B:B9', 'SMG ENTERPRISE, LLC'),
(13611, '1C:4B:D6', 'AzureWave'),
(13612, '1C:51:B5', 'Techaya LTD'),
(13613, '1C:52:16', 'DONGGUAN HELE ELECTRONICS CO., LTD'),
(13614, '1C:52:D6', 'FLAT DISPLAY TECHNOLOGY CORPORATION'),
(13615, '1C:5A:3E', 'Samsung Eletronics Co., Ltd (Visual Display Divison)'),
(13616, '1C:5A:6B', 'Philips Electronics Nederland BV'),
(13617, '1C:5C:55', 'PRIMA Cinema, Inc'),
(13618, '1C:5C:60', 'Shenzhen Belzon Technology Co.,LTD.'),
(13619, '1C:5F:FF', 'Beijing Ereneben Information Technology Co.,Ltd Shenzhen Branch'),
(13620, '1C:62:B8', 'Samsung Electronics Co.,Ltd'),
(13621, '1C:63:B7', 'OpenProducts 237 AB'),
(13622, '1C:65:9D', 'Liteon Technology Corporation'),
(13623, '1C:66:6D', 'Hon Hai Precision Ind.Co.Ltd'),
(13624, '1C:66:AA', 'Samsung Electronics'),
(13625, '1C:69:A5', 'Research In Motion'),
(13626, '1C:6A:7A', 'Cisco'),
(13627, '1C:6B:CA', 'Mitsunami Co., Ltd.'),
(13628, '1C:6F:65', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(13629, '1C:75:08', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(13630, '1C:76:CA', 'Terasic Technologies Inc.'),
(13631, '1C:78:39', 'Shenzhen Tencent Computer System Co., Ltd.'),
(13632, '1C:7B:21', 'Sony Mobile Communications AB'),
(13633, '1C:7C:11', 'EID'),
(13634, '1C:7C:45', 'Vitek Industrial Video Products, Inc.'),
(13635, '1C:7C:C7', 'Coriant GmbH'),
(13636, '1C:7D:22', 'Fuji Xerox Co., Ltd.'),
(13637, '1C:7E:51', '3bumen.com'),
(13638, '1C:7E:E5', 'D-Link International'),
(13639, '1C:83:B0', 'Linked IP GmbH'),
(13640, '1C:84:64', 'FORMOSA WIRELESS COMMUNICATION CORP.'),
(13641, '1C:86:AD', 'MCT CO., LTD.'),
(13642, '1C:8E:5C', 'Huawei Technologies Co., Ltd'),
(13643, '1C:8E:8E', 'DB Communication &amp; Systems Co., ltd.'),
(13644, '1C:8F:8A', 'Phase Motion Control SpA'),
(13645, '1C:91:79', 'Integrated System Technologies Ltd'),
(13646, '1C:94:92', 'RUAG Schweiz AG'),
(13647, '1C:95:5D', 'I-LAX ELECTRONICS INC.'),
(13648, '1C:95:9F', 'Veethree Electronics And Marine LLC'),
(13649, '1C:96:5A', 'Weifang goertek Electronics CO.,LTD'),
(13650, '1C:97:3D', 'PRICOM Design'),
(13651, '1C:99:4C', 'Murata Manufactuaring Co.,Ltd.'),
(13652, '1C:9C:26', 'Zoovel Technologies'),
(13653, '1C:9E:CB', 'Beijing Nari Smartchip Microelectronics Company Limited'),
(13654, '1C:A2:B1', 'ruwido austria gmbh'),
(13655, '1C:A5:32', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(13656, '1C:A7:70', 'SHENZHEN CHUANGWEI-RGB ELECTRONICS CO.,LT'),
(13657, '1C:AA:07', 'CISCO SYSTEMS, INC.'),
(13658, '1C:AB:01', 'Innovolt'),
(13659, '1C:AB:A7', 'Apple'),
(13660, '1C:AF:05', 'Samsung Electronics Co.,Ltd'),
(13661, '1C:AF:F7', 'D-LINK INTERNATIONAL PTE LIMITED'),
(13662, '1C:B0:94', 'HTC Corporation'),
(13663, '1C:B1:7F', 'NEC Platforms, Ltd.'),
(13664, '1C:B2:43', 'TDC A/S'),
(13665, '1C:BA:8C', 'Texas Instruments'),
(13666, '1C:BB:A8', 'OJSC &quot;Ufimskiy Zavod &quot;Promsvyaz&quot;'),
(13667, '1C:BD:0E', 'Amplified Engineering Pty Ltd'),
(13668, '1C:BD:B9', 'D-LINK INTERNATIONAL PTE LIMITED'),
(13669, '1C:C1:1A', 'Wavetronix'),
(13670, '1C:C1:DE', 'Hewlett-Packard Company'),
(13671, '1C:C3:16', 'MileSight Technology Co., Ltd.'),
(13672, '1C:C6:3C', 'Arcadyan Technology Corporation'),
(13673, '1C:C7:2D', 'Shenzhen Huapu Digital CO.,Ltd'),
(13674, '1C:D4:0C', 'Kriwan Industrie-Elektronik GmbH'),
(13675, '1C:DE:A7', 'Cisco'),
(13676, '1C:DF:0F', 'CISCO SYSTEMS, INC.'),
(13677, '1C:E1:65', 'Marshal Corporation'),
(13678, '1C:E1:92', 'Qisda Corporation'),
(13679, '1C:E2:CC', 'Texas Instruments'),
(13680, '1C:E6:2B', 'Apple'),
(13681, '1C:E6:C7', 'Cisco'),
(13682, '1C:E8:5D', 'Cisco'),
(13683, '1C:EE:E8', 'Ilshin Elecom'),
(13684, '1C:F0:61', 'SCAPS GmbH'),
(13685, '1C:F4:CA', 'PRIVATE'),
(13686, '1C:F5:E7', 'Turtle Industry Co., Ltd.'),
(13687, '1C:FA:68', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13688, '1C:FC:BB', 'Realfiction ApS'),
(13689, '1C:FE:A7', 'IDentytech Solutins Ltd.'),
(13690, '20:01:4F', 'Linea Research Ltd'),
(13691, '20:02:AF', 'Murata Manufactuaring Co.,Ltd.'),
(13692, '20:05:05', 'RADMAX COMMUNICATION PRIVATE LIMITED'),
(13693, '20:05:E8', 'OOO InProMedia'),
(13694, '20:08:ED', 'Huawei Technologies Co., Ltd'),
(13695, '20:0A:5E', 'Xiangshan Giant Eagle Technology Developing co.,LTD'),
(13696, '20:0B:C7', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13697, '20:0C:C8', 'NETGEAR INC.,'),
(13698, '20:0E:95', 'IEC &ndash; TC9 WG43'),
(13699, '20:10:7A', 'Gemtek Technology Co., Ltd.'),
(13700, '20:12:57', 'Most Lucky Trading Ltd'),
(13701, '20:12:D5', 'Scientech Materials Corporation'),
(13702, '20:13:E0', 'Samsung Electronics Co.,Ltd'),
(13703, '20:16:D8', 'Liteon Technology Corporation'),
(13704, '20:18:0E', 'Shenzhen Sunchip Technology Co., Ltd'),
(13705, '20:1A:06', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(13706, '20:1D:03', 'Elatec GmbH'),
(13707, '20:21:A5', 'LG Electronics Inc'),
(13708, '20:25:64', 'PEGATRON CORPORATION'),
(13709, '20:25:98', 'Teleview'),
(13710, '20:28:BC', 'Visionscape Co,. Ltd.'),
(13711, '20:2B:C1', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(13712, '20:2C:B7', 'Kong Yue Electronics &amp; Information Industry (Xinhui) Ltd.'),
(13713, '20:31:EB', 'HDSN'),
(13714, '20:37:06', 'CISCO SYSTEMS, INC.'),
(13715, '20:37:BC', 'Kuipers Electronic Engineering BV'),
(13716, '20:3A:07', 'Cisco'),
(13717, '20:40:05', 'feno GmbH'),
(13718, '20:41:5A', 'Smarteh d.o.o.'),
(13719, '20:44:3A', 'Schneider Electric Asia Pacific Ltd'),
(13720, '20:46:A1', 'VECOW Co., Ltd'),
(13721, '20:46:F9', 'Advanced Network Devices (dba:AND)'),
(13722, '20:4A:AA', 'Hanscan Spain S.A.'),
(13723, '20:4C:6D', 'Hugo Brennenstuhl Gmbh &amp; Co. KG.'),
(13724, '20:4E:6B', 'Axxana(israel) ltd'),
(13725, '20:4E:7F', 'NETGEAR'),
(13726, '20:53:CA', 'Risk Technology Ltd'),
(13727, '20:54:76', 'Sony Mobile Communications AB'),
(13728, '20:57:21', 'Salix Technology CO., Ltd.'),
(13729, '20:59:A0', 'Paragon Technologies Inc.'),
(13730, '20:5A:00', 'Coval'),
(13731, '20:5B:2A', 'PRIVATE'),
(13732, '20:5B:5E', 'Shenzhen Wonhe Technology Co., Ltd'),
(13733, '20:5C:FA', 'Yangzhou ChangLian Network Technology Co,ltd.'),
(13734, '20:62:74', 'Microsoft Corporation'),
(13735, '20:64:32', 'SAMSUNG ELECTRO MECHANICS CO.,LTD.'),
(13736, '20:67:B1', 'Pluto inc.'),
(13737, '20:68:9D', 'Liteon Technology Corporation'),
(13738, '20:6A:8A', 'Wistron InfoComm Manufacturing(Kunshan)Co.,Ltd.'),
(13739, '20:6A:FF', 'Atlas Elektronik UK Limited'),
(13740, '20:6E:9C', 'Samsung Electronics Co.,Ltd'),
(13741, '20:6F:EC', 'Braemac CA LLC'),
(13742, '20:73:55', 'ARRIS Group, Inc.'),
(13743, '20:74:CF', 'Shenzhen Voxtech Co.,Ltd'),
(13744, '20:76:00', 'Actiontec Electronics, Inc'),
(13745, '20:76:93', 'Lenovo (Beijing) Limited.'),
(13746, '20:7C:8F', 'Quanta Microsystems,Inc.'),
(13747, '20:7D:74', 'Apple'),
(13748, '20:85:8C', 'Assa'),
(13749, '20:87:AC', 'AES motomation'),
(13750, '20:89:84', 'COMPAL INFORMATION (KUNSHAN) CO., LTD'),
(13751, '20:89:86', 'zte corporation'),
(13752, '20:91:8A', 'PROFALUX'),
(13753, '20:91:D9', 'I\'M SPA'),
(13754, '20:93:4D', 'Fujian Star-net Communication Co., Ltd'),
(13755, '20:9A:E9', 'Volacomm Co., Ltd'),
(13756, '20:9B:A5', 'JIAXING GLEAD Electronics Co.,Ltd'),
(13757, '20:A2:E4', 'Apple'),
(13758, '20:A2:E7', 'Lee-Dickens Ltd'),
(13759, '20:A7:87', 'Bointec Taiwan Corporation Limited'),
(13760, '20:A9:9B', 'Microsoft Corporation'),
(13761, '20:AA:25', 'IP-NET LLC'),
(13762, '20:AA:4B', 'Cisco-Linksys, LLC'),
(13763, '20:B0:F7', 'Enclustra GmbH'),
(13764, '20:B3:99', 'Enterasys'),
(13765, '20:B5:C6', 'Mimosa Networks'),
(13766, '20:B7:C0', 'Omicron electronics GmbH'),
(13767, '20:BB:C0', 'Cisco'),
(13768, '20:BB:C6', 'Jabil Circuit Hungary Ltd.'),
(13769, '20:BF:DB', 'DVL'),
(13770, '20:C0:6D', 'SHENZHEN SPACETEK TECHNOLOGY CO.,LTD'),
(13771, '20:C1:AF', 'i Wit Digital Co., Limited'),
(13772, '20:C3:8F', 'Texas Instruments Inc'),
(13773, '20:C6:0D', 'Shanghai annijie Information technology Co.,LTD'),
(13774, '20:C6:EB', 'Panasonic Corporation AVC Networks Company'),
(13775, '20:C8:B3', 'SHENZHEN BUL-TECH CO.,LTD.'),
(13776, '20:C9:D0', 'Apple'),
(13777, '20:CD:39', 'Texas Instruments, Inc'),
(13778, '20:CE:C4', 'Peraso Technologies'),
(13779, '20:CF:30', 'ASUSTek COMPUTER INC.'),
(13780, '20:D2:1F', 'Wincal Technology Corp.'),
(13781, '20:D3:90', 'Samsung Electronics Co.,Ltd'),
(13782, '20:D5:AB', 'Korea Infocom Co.,Ltd.'),
(13783, '20:D5:BF', 'Samsung Eletronics Co., Ltd'),
(13784, '20:D6:07', 'Nokia Corporation'),
(13785, '20:D9:06', 'Iota, Inc.'),
(13786, '20:DC:93', 'Cheetah Hi-Tech, Inc.'),
(13787, '20:DC:E6', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(13788, '20:DF:3F', 'Nanjing SAC Power Grid Automation Co., Ltd.'),
(13789, '20:E5:2A', 'NETGEAR INC.,'),
(13790, '20:E5:64', 'ARRIS Group, Inc.'),
(13791, '20:E7:91', 'Siemens Healthcare Diagnostics, Inc'),
(13792, '20:EA:C7', 'SHENZHEN RIOPINE ELECTRONICS CO., LTD'),
(13793, '20:ED:74', 'Ability enterprise co.,Ltd.'),
(13794, '20:EE:C6', 'Elefirst Science &amp; Tech Co ., ltd'),
(13795, '20:F0:02', 'MTData Developments Pty. Ltd.'),
(13796, '20:F3:A3', 'Huawei Technologies Co., Ltd'),
(13797, '20:F8:5E', 'Delta Electronics'),
(13798, '20:FA:BB', 'Cambridge Executive Limited'),
(13799, '20:FD:F1', '3COM EUROPE LTD'),
(13800, '20:FE:CD', 'System In Frontier Inc.'),
(13801, '20:FE:DB', 'M2M Solution S.A.S.'),
(13802, '24:01:C7', 'Cisco'),
(13803, '24:05:0F', 'MTN Electronic Co. Ltd'),
(13804, '24:09:17', 'Devlin Electronics Limited'),
(13805, '24:09:95', 'Huawei Technologies Co., Ltd'),
(13806, '24:0A:11', 'TCT Mobile Limited'),
(13807, '24:0A:64', 'AzureWaveTechnologies,Inc'),
(13808, '24:0B:2A', 'Viettel Group'),
(13809, '24:0B:B1', 'KOSTAL Industrie Elektrik GmbH'),
(13810, '24:10:64', 'Shenzhen Ecsino Tecnical Co. Ltd'),
(13811, '24:11:25', 'Hutek Co., Ltd.'),
(13812, '24:11:48', 'Entropix, LLC'),
(13813, '24:11:D0', 'Chongqing Ehs Science and Technology Development Co.,Ltd.'),
(13814, '24:1A:8C', 'Squarehead Technology AS'),
(13815, '24:1B:13', 'Shanghai Nutshell Electronic Co., Ltd.'),
(13816, '24:1B:44', 'Hangzhou Tuners Electronics Co., Ltd'),
(13817, '24:1C:04', 'SHENZHEN JEHE TECHNOLOGY DEVELOPMENT CO., LTD.'),
(13818, '24:1F:2C', 'Calsys, Inc.'),
(13819, '24:21:AB', 'Sony Ericsson Mobile Communications'),
(13820, '24:24:0E', 'Apple'),
(13821, '24:26:42', 'SHARP Corporation.'),
(13822, '24:2F:FA', 'Toshiba Global Commerce Solutions'),
(13823, '24:33:6C', 'PRIVATE'),
(13824, '24:37:4C', 'Cisco SPVTG'),
(13825, '24:37:EF', 'EMC Electronic Media Communication SA'),
(13826, '24:3C:20', 'Dynamode Group'),
(13827, '24:42:BC', 'Alinco,incorporated'),
(13828, '24:45:97', 'GEMUE Gebr. Mueller Apparatebau'),
(13829, '24:47:0E', 'PentronicAB'),
(13830, '24:49:7B', 'Innovative Converged Devices Inc'),
(13831, '24:4B:03', 'Samsung Electronics Co.,Ltd'),
(13832, '24:4B:81', 'Samsung Electronics Co.,Ltd'),
(13833, '24:4F:1D', 'iRule LLC'),
(13834, '24:5F:DF', 'KYOCERA Corporation'),
(13835, '24:62:78', 'sysmocom - systems for mobile communications GmbH'),
(13836, '24:64:EF', 'CYG SUNRI CO.,LTD.'),
(13837, '24:65:11', 'AVM GmbH'),
(13838, '24:69:4A', 'Jasmine Systems Inc.'),
(13839, '24:69:A5', 'Huawei Technologies Co., Ltd'),
(13840, '24:6A:AB', 'IT-IS International'),
(13841, '24:76:56', 'Shanghai Net Miles Fiber Optics Technology Co., LTD.'),
(13842, '24:76:7D', 'Cisco SPVTG'),
(13843, '24:77:03', 'Intel Corporate'),
(13844, '24:7F:3C', 'Huawei Technologies Co., Ltd'),
(13845, '24:80:00', 'Westcontrol AS'),
(13846, '24:81:AA', 'KSH International Co., Ltd.'),
(13847, '24:82:8A', 'Prowave Technologies Ltd.'),
(13848, '24:86:F4', 'Ctek, Inc.'),
(13849, '24:87:07', 'SEnergy Corporation'),
(13850, '24:93:CA', 'Voxtronic Technology Computer-Systeme GmbH'),
(13851, '24:94:42', 'OPEN ROAD SOLUTIONS , INC.'),
(13852, '24:95:04', 'SFR'),
(13853, '24:97:ED', 'Techvision Intelligent Technology Limited'),
(13854, '24:A0:74', 'Apple'),
(13855, '24:A2:E1', 'Apple, Inc'),
(13856, '24:A4:2C', 'KOUKAAM a.s.'),
(13857, '24:A4:3C', 'Ubiquiti Networks, INC'),
(13858, '24:A4:95', 'Thales Canada Inc.'),
(13859, '24:A8:7D', 'Panasonic Automotive Systems Asia Pacific(Thailand)Co.,Ltd.'),
(13860, '24:A9:37', 'PURE Storage'),
(13861, '24:AB:81', 'Apple'),
(13862, '24:AF:4A', 'Alcatel-Lucent-IPD'),
(13863, '24:AF:54', 'NEXGEN Mediatech Inc.'),
(13864, '24:B6:57', 'CISCO SYSTEMS, INC.'),
(13865, '24:B6:B8', 'FRIEM SPA'),
(13866, '24:B6:FD', 'Dell Inc'),
(13867, '24:B8:8C', 'Crenus Co.,Ltd.'),
(13868, '24:B8:D2', 'Opzoon Technology Co.,Ltd.'),
(13869, '24:BA:30', 'Technical Consumer Products, Inc.'),
(13870, '24:BB:C1', 'Absolute Analysis'),
(13871, '24:BC:82', 'Dali Wireless, Inc.'),
(13872, '24:BE:05', 'Hewlett Packard'),
(13873, '24:BF:74', 'PRIVATE'),
(13874, '24:C0:B3', 'RSF'),
(13875, '24:C6:96', 'Samsung Electronics Co.,Ltd'),
(13876, '24:C8:48', 'mywerk system GmbH'),
(13877, '24:C8:6E', 'Chaney Instrument Co.'),
(13878, '24:C9:A1', 'Ruckus Wireless'),
(13879, '24:C9:DE', 'Genoray'),
(13880, '24:CB:E7', 'MYK, Inc.'),
(13881, '24:CF:21', 'Shenzhen State Micro Technology Co., Ltd'),
(13882, '24:D1:3F', 'MEXUS CO.,LTD'),
(13883, '24:D2:CC', 'SmartDrive Systems Inc.'),
(13884, '24:D9:21', 'Avaya, Inc'),
(13885, '24:DA:B6', 'Sistemas de Gesti&oacute;n Energ&eacute;tica S.A. de C.V'),
(13886, '24:DB:AC', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(13887, '24:DB:AD', 'ShopperTrak RCT Corporation'),
(13888, '24:DB:ED', 'Samsung Electronics Co.,Ltd'),
(13889, '24:DE:C6', 'Aruba Networks'),
(13890, '24:E2:71', 'Qingdao Hisense Communications Co.,Ltd'),
(13891, '24:E3:14', 'Apple'),
(13892, '24:E6:BA', 'JSC Zavod im. Kozitsky'),
(13893, '24:E9:B3', 'Cisco'),
(13894, '24:EA:40', 'Systeme Helmholz GmbH'),
(13895, '24:EB:65', 'SAET I.S. S.r.l.'),
(13896, '24:EC:99', 'Askey Computer Corp'),
(13897, '24:EC:D6', 'CSG Science &amp; Technology Co.,Ltd.Hefei'),
(13898, '24:EE:3A', 'Chengdu Yingji Electronic Hi-tech Co Ltd'),
(13899, '24:F0:FF', 'GHT Co., Ltd.'),
(13900, '24:F2:DD', 'Radiant Zemax LLC'),
(13901, '24:F5:AA', 'Samsung Electronics Co.,LTD'),
(13902, '24:FD:52', 'Liteon Technology Corporation'),
(13903, '28:04:E0', 'FERMAX ELECTRONICA S.A.U.'),
(13904, '28:06:1E', 'NINGBO GLOBAL USEFUL ELECTRIC CO.,LTD'),
(13905, '28:06:8D', 'ITL, LLC'),
(13906, '28:0B:5C', 'Apple'),
(13907, '28:0C:B8', 'Mikrosay Yazilim ve Elektronik A.S.'),
(13908, '28:0D:FC', 'Sony Computer Entertainment Inc.'),
(13909, '28:10:7B', 'D-Link International'),
(13910, '28:14:71', 'Lantis co., LTD.'),
(13911, '28:16:2E', '2Wire'),
(13912, '28:17:CE', 'Omnisense Ltd'),
(13913, '28:18:78', 'Microsoft Corporation'),
(13914, '28:18:FD', 'Aditya Infotech Ltd.'),
(13915, '28:22:46', 'Beijing Sinoix Communication Co., LTD'),
(13916, '28:26:A6', 'PBR electronics GmbH'),
(13917, '28:28:5D', 'ZyXEL Communications Corporation'),
(13918, '28:29:CC', 'Corsa Technology Incorporated'),
(13919, '28:29:D9', 'GlobalBeiMing technology (Beijing)Co. Ltd'),
(13920, '28:2C:B2', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(13921, '28:31:52', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13922, '28:32:C5', 'Humax.co.,ltd'),
(13923, '28:34:10', 'Enigma Diagnostics Limited'),
(13924, '28:34:A2', 'Cisco'),
(13925, '28:37:37', 'Apple'),
(13926, '28:38:CF', 'Gen2wave'),
(13927, '28:39:E7', 'Preceno Technology Pte.Ltd.'),
(13928, '28:3B:96', 'Cool Control LTD'),
(13929, '28:3C:E4', 'Huawei Technologies Co., Ltd'),
(13930, '28:40:1A', 'C8 MediSensors, Inc.'),
(13931, '28:41:21', 'OptiSense Network, LLC'),
(13932, '28:44:30', 'GenesisTechnical Systems (UK) Ltd'),
(13933, '28:47:AA', 'Nokia Corporation'),
(13934, '28:48:46', 'GridCentric Inc.'),
(13935, '28:4C:53', 'Intune Networks'),
(13936, '28:4D:92', 'Luminator'),
(13937, '28:4E:D7', 'OutSmart Power Systems, Inc.'),
(13938, '28:4F:CE', 'Liaoning Wontel Science and Technology Development Co.,Ltd.'),
(13939, '28:51:32', 'Shenzhen Prayfly Technology Co.,Ltd'),
(13940, '28:52:E0', 'Layon international Electronic &amp; Telecom Co.,Ltd'),
(13941, '28:57:67', 'Echostar Technologies Corp'),
(13942, '28:5F:DB', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(13943, '28:60:46', 'Lantech Communications Global, Inc.'),
(13944, '28:60:94', 'CAPELEC'),
(13945, '28:63:36', 'Siemens AG - Industrial Automation - EWA'),
(13946, '28:65:6B', 'Keystone Microtech Corporation'),
(13947, '28:6A:B8', 'Apple'),
(13948, '28:6A:BA', 'Apple'),
(13949, '28:6D:97', 'SAMJIN Co., Ltd.'),
(13950, '28:6E:D4', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(13951, '28:71:84', 'Spire Payments'),
(13952, '28:72:C5', 'Smartmatic Corp'),
(13953, '28:72:F0', 'ATHENA'),
(13954, '28:79:94', 'Realplay Digital Technology(Shenzhen) Co.,Ltd'),
(13955, '28:80:23', 'Hewlett Packard'),
(13956, '28:84:FA', 'SHARP Corporation'),
(13957, '28:85:2D', 'Touch Networks'),
(13958, '28:89:15', 'CashGuard Sverige AB'),
(13959, '28:8A:1C', 'Juniper networks'),
(13960, '28:91:D0', 'Stage Tec Entwicklungsgesellschaft f&uuml;r professionelle Audiotechnik mbH'),
(13961, '28:92:4A', 'Hewlett Packard'),
(13962, '28:93:FE', 'CISCO SYSTEMS, INC.'),
(13963, '28:94:0F', 'CISCO SYSTEMS, INC.'),
(13964, '28:94:AF', 'Samhwa Telecom'),
(13965, '28:98:7B', 'Samsung Electronics Co.,Ltd'),
(13966, '28:9A:4B', 'SteelSeries ApS'),
(13967, '28:9A:FA', 'TCT Mobile Limited'),
(13968, '28:9E:DF', 'Danfoss Turbocor Compressors, Inc'),
(13969, '28:A1:86', 'enblink'),
(13970, '28:A1:92', 'GERP Solution'),
(13971, '28:A1:EB', 'ETEK TECHNOLOGY (SHENZHEN) CO.,LTD'),
(13972, '28:A2:41', 'exlar corp'),
(13973, '28:A5:74', 'Miller Electric Mfg. Co.'),
(13974, '28:A5:EE', 'Shenzhen SDGI CATV Co., Ltd'),
(13975, '28:AF:0A', 'Sirius XM Radio Inc'),
(13976, '28:B0:CC', 'Xenya d.o.o.'),
(13977, '28:B2:BD', 'Intel Corporate'),
(13978, '28:B3:AB', 'Genmark Automation'),
(13979, '28:BA:18', 'NextNav, LLC'),
(13980, '28:BA:B5', 'Samsung Electronics Co.,Ltd'),
(13981, '28:BB:59', 'RNET Technologies, Inc.'),
(13982, '28:BE:9B', 'Technicolor USA Inc.'),
(13983, '28:C0:DA', 'Juniper Networks'),
(13984, '28:C2:DD', 'AzureWave Technologies, Inc.'),
(13985, '28:C6:71', 'Yota Devices OY'),
(13986, '28:C6:8E', 'NETGEAR INC.,'),
(13987, '28:C7:18', 'Altierre'),
(13988, '28:C7:CE', 'Cisco'),
(13989, '28:C8:25', 'DellKing Industrial Co., Ltd'),
(13990, '28:C9:14', 'Taimag Corporation'),
(13991, '28:CB:EB', 'One'),
(13992, '28:CC:01', 'Samsung Electronics Co.,Ltd'),
(13993, '28:CC:FF', 'Corporacion Empresarial Altra SL'),
(13994, '28:CD:1C', 'Espotel Oy'),
(13995, '28:CD:4C', 'Individual Computers GmbH'),
(13996, '28:CD:9C', 'Shenzhen Dynamax Software Development Co.,Ltd.'),
(13997, '28:CF:DA', 'Apple'),
(13998, '28:CF:E9', 'Apple'),
(13999, '28:D1:AF', 'Nokia Corporation'),
(14000, '28:D2:44', 'LCFC(HeFei) Electronics Technology Co., Ltd.'),
(14001, '28:D5:76', 'Premier Wireless, Inc.'),
(14002, '28:D9:3E', 'Telecor Inc.'),
(14003, '28:D9:8A', 'Hangzhou Konke Technology Co.,Ltd.'),
(14004, '28:D9:97', 'Yuduan Mobile Co., Ltd.'),
(14005, '28:DB:81', 'Shanghai Guao Electronic Technology Co., Ltd'),
(14006, '28:DE:F6', 'bioMerieux Inc.'),
(14007, '28:E0:2C', 'Apple'),
(14008, '28:E1:4C', 'Apple, Inc.'),
(14009, '28:E2:97', 'Shanghai InfoTM Microelectronics Co.,Ltd.'),
(14010, '28:E3:1F', 'Xiaomi inc.'),
(14011, '28:E3:47', 'Liteon Technology Corporation'),
(14012, '28:E4:76', 'Pi-Coral'),
(14013, '28:E6:08', 'Tokheim'),
(14014, '28:E6:E9', 'SIS Sat Internet Services GmbH'),
(14015, '28:E7:94', 'Microtime Computer Inc.'),
(14016, '28:E7:CF', 'Apple'),
(14017, '28:ED:58', 'JAG Jakob AG'),
(14018, '28:EE:2C', 'Frontline Test Equipment'),
(14019, '28:EF:01', 'PRIVATE'),
(14020, '28:F3:58', '2C - Trifonov &amp; Co'),
(14021, '28:F5:32', 'ADD-Engineering BV'),
(14022, '28:F6:06', 'Syes srl'),
(14023, '28:FB:D3', 'Ragentek Technology Group'),
(14024, '28:FC:51', 'The Electric Controller and Manufacturing Co., LLC'),
(14025, '28:FC:F6', 'Shenzhen Xin KingBrand enterprises Co.,Ltd'),
(14026, '28:FD:80', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(14027, '2C:00:2C', 'UNOWHY'),
(14028, '2C:00:33', 'EControls, LLC'),
(14029, '2C:00:F7', 'XOS'),
(14030, '2C:01:0B', 'NASCENT Technology, LLC - RemKon'),
(14031, '2C:06:23', 'Win Leader Inc.'),
(14032, '2C:07:3C', 'DEVLINE LIMITED'),
(14033, '2C:10:C1', 'Nintendo Co., Ltd.'),
(14034, '2C:18:AE', 'Trend Electronics Co., Ltd.'),
(14035, '2C:19:84', 'IDN Telecom, Inc.'),
(14036, '2C:1A:31', 'Electronics Company Limited'),
(14037, '2C:1E:EA', 'AERODEV'),
(14038, '2C:1F:23', 'Apple'),
(14039, '2C:21:72', 'Juniper Networks'),
(14040, '2C:24:5F', 'Babolat VS'),
(14041, '2C:26:5F', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(14042, '2C:26:C5', 'zte corporation'),
(14043, '2C:27:D7', 'Hewlett-Packard Company'),
(14044, '2C:28:2D', 'BBK COMMUNICATIAO TECHNOLOGY CO.,LTD.'),
(14045, '2C:29:97', 'Microsoft Corporation'),
(14046, '2C:2D:48', 'bct electronic GesmbH'),
(14047, '2C:30:68', 'Pantech Co.,Ltd'),
(14048, '2C:33:7A', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14049, '2C:34:27', 'ERCO &amp; GENER'),
(14050, '2C:35:57', 'ELLIY Power CO..Ltd'),
(14051, '2C:36:A0', 'Capisco Limited'),
(14052, '2C:36:F8', 'CISCO SYSTEMS, INC.'),
(14053, '2C:37:31', 'ShenZhen Yifang Digital Technology Co.,LTD'),
(14054, '2C:37:96', 'CYBO CO.,LTD.'),
(14055, '2C:39:96', 'SAGEMCOM'),
(14056, '2C:39:C1', 'Ciena Corporation'),
(14057, '2C:3A:28', 'Fagor Electr&oacute;nica'),
(14058, '2C:3B:FD', 'Netstor Technology Co., Ltd.'),
(14059, '2C:3E:CF', 'Cisco'),
(14060, '2C:3F:38', 'CISCO SYSTEMS, INC.'),
(14061, '2C:3F:3E', 'Alge-Timing GmbH'),
(14062, '2C:41:38', 'Hewlett-Packard Company'),
(14063, '2C:44:01', 'Samsung Electronics Co.,Ltd'),
(14064, '2C:44:1B', 'Spectrum Medical Limited'),
(14065, '2C:44:FD', 'Hewlett Packard'),
(14066, '2C:50:89', 'Shenzhen Kaixuan Visual Technology Co.,Limited'),
(14067, '2C:53:4A', 'Shenzhen Winyao Electronic Limited'),
(14068, '2C:54:2D', 'CISCO SYSTEMS, INC.'),
(14069, '2C:54:CF', 'LG Electronics'),
(14070, '2C:55:3C', 'Gainspeed, Inc.'),
(14071, '2C:59:E5', 'Hewlett Packard'),
(14072, '2C:5A:05', 'Nokia Corporation'),
(14073, '2C:5A:A3', 'PROMATE ELECTRONIC CO.LTD'),
(14074, '2C:5B:E1', 'Centripetal Networks, Inc'),
(14075, '2C:5D:93', 'Ruckus Wireless'),
(14076, '2C:5F:F3', 'Pertronic Industries'),
(14077, '2C:60:0C', 'QUANTA COMPUTER INC.'),
(14078, '2C:62:5A', 'Finest Security Systems Co., Ltd'),
(14079, '2C:62:89', 'Regenersis (Glenrothes) Ltd'),
(14080, '2C:67:FB', 'ShenZhen Zhengjili Electronics Co., LTD'),
(14081, '2C:69:BA', 'RF Controls, LLC'),
(14082, '2C:6B:F5', 'Juniper networks'),
(14083, '2C:71:55', 'HiveMotion'),
(14084, '2C:72:C3', 'Soundmatters'),
(14085, '2C:75:0F', 'Shanghai Dongzhou-Lawton Communication Technology Co. Ltd.'),
(14086, '2C:76:8A', 'Hewlett-Packard Company'),
(14087, '2C:7B:5A', 'Milper Ltd'),
(14088, '2C:7B:84', 'OOO Petr Telegin'),
(14089, '2C:7E:CF', 'Onzo Ltd'),
(14090, '2C:80:65', 'HARTING Inc. of North America'),
(14091, '2C:81:58', 'Hon Hai Precision Ind. Co.,Ltd'),
(14092, '2C:8A:72', 'HTC Corporation'),
(14093, '2C:8B:F2', 'Hitachi Metals America Ltd'),
(14094, '2C:91:27', 'Eintechno Corporation'),
(14095, '2C:92:2C', 'Kishu Giken Kogyou Company Ltd,.'),
(14096, '2C:94:64', 'Cincoze Co., Ltd.'),
(14097, '2C:95:7F', 'zte corporation'),
(14098, '2C:97:17', 'I.C.Y. B.V.'),
(14099, '2C:9A:A4', 'NGI SpA'),
(14100, '2C:9E:5F', 'ARRIS Group, Inc.'),
(14101, '2C:9E:FC', 'CANON INC.'),
(14102, '2C:A1:57', 'acromate, Inc.'),
(14103, '2C:A2:B4', 'Fortify Technologies, LLC'),
(14104, '2C:A3:0E', 'POWER DRAGON DEVELOPMENT LIMITED'),
(14105, '2C:A7:80', 'True Technologies Inc.'),
(14106, '2C:A8:35', 'RIM'),
(14107, '2C:AB:25', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(14108, '2C:AB:A4', 'Cisco SPVTG'),
(14109, '2C:AD:13', 'SHENZHEN ZHILU TECHNOLOGY CO.,LTD'),
(14110, '2C:B0:5D', 'NETGEAR'),
(14111, '2C:B0:DF', 'Soliton Technologies Pvt Ltd'),
(14112, '2C:B4:3A', 'Apple'),
(14113, '2C:B6:93', 'Radware'),
(14114, '2C:B6:9D', 'RED Digital Cinema'),
(14115, '2C:BE:08', 'Apple'),
(14116, '2C:BE:97', 'Ingenieurbuero Bickele und Buehler GmbH'),
(14117, '2C:C2:60', 'Ravello Systems'),
(14118, '2C:CC:15', 'Nokia Corporation'),
(14119, '2C:CD:27', 'Precor Inc'),
(14120, '2C:CD:43', 'Summit Technology Group'),
(14121, '2C:CD:69', 'Aqavi.com'),
(14122, '2C:D0:5A', 'Liteon Technology Corporation'),
(14123, '2C:D1:DA', 'Sanjole, Inc.'),
(14124, '2C:D2:E7', 'Nokia Corporation'),
(14125, '2C:D4:44', 'Fujitsu Limited'),
(14126, '2C:DD:0C', 'Discovergy GmbH'),
(14127, '2C:E2:A8', 'DeviceDesign'),
(14128, '2C:E4:12', 'SAGEMCOM SAS'),
(14129, '2C:E6:CC', 'Ruckus Wireless'),
(14130, '2C:E8:71', 'Alert Metalguard ApS'),
(14131, '2C:ED:EB', 'Alpheus Digital Company Limited'),
(14132, '2C:EE:26', 'Petroleum Geo-Services'),
(14133, '2C:F0:EE', 'Apple'),
(14134, '2C:F2:03', 'EMKO ELEKTRONIK SAN VE TIC AS'),
(14135, '2C:F4:C5', 'Avaya, Inc'),
(14136, '2C:F7:F1', 'Seeed Technology Inc.'),
(14137, '2C:FA:A2', 'Alcatel-Lucent'),
(14138, '30:05:5C', 'Brother industries, LTD.'),
(14139, '30:0B:9C', 'Delta Mobile Systems, Inc.'),
(14140, '30:0D:2A', 'Zhejiang Wellcom Technology Co.,Ltd.'),
(14141, '30:0E:D5', 'Hon Hai Precision Ind.Co.Ltd'),
(14142, '30:0E:E3', 'Aquantia Corporation'),
(14143, '30:10:B3', 'Liteon Technology Corporation'),
(14144, '30:10:E4', 'Apple, Inc.'),
(14145, '30:14:2D', 'Piciorgros GmbH'),
(14146, '30:14:4A', 'Wistron Neweb Corp.'),
(14147, '30:15:18', 'Ubiquitous Communication Co. ltd.'),
(14148, '30:16:8D', 'ProLon'),
(14149, '30:17:C8', 'Sony Ericsson Mobile Communications AB'),
(14150, '30:18:CF', 'DEOS control systems GmbH'),
(14151, '30:19:66', 'Samsung Electronics Co.,Ltd'),
(14152, '30:1A:28', 'Mako Networks Ltd'),
(14153, '30:21:5B', 'Shenzhen Ostar Display Electronic Co.,Ltd'),
(14154, '30:2D:E8', 'JDA, LLC (JDA Systems)'),
(14155, '30:32:94', 'W-IE-NE-R Plein &amp; Baus GmbH'),
(14156, '30:32:D4', 'Hanilstm Co., Ltd.'),
(14157, '30:33:35', 'Boosty'),
(14158, '30:37:A6', 'CISCO SYSTEMS, INC.'),
(14159, '30:38:55', 'Nokia Corporation'),
(14160, '30:39:26', 'Sony Ericsson Mobile Communications AB'),
(14161, '30:39:55', 'Shenzhen Jinhengjia Electronic Co., Ltd.'),
(14162, '30:39:F2', 'ADB Broadband Italia'),
(14163, '30:3A:64', 'Intel Corporate'),
(14164, '30:3D:08', 'GLINTT TES S.A.'),
(14165, '30:3E:AD', 'Sonavox Canada Inc'),
(14166, '30:41:74', 'ALTEC LANSING LLC'),
(14167, '30:42:25', 'BURG-W&Auml;CHTER KG'),
(14168, '30:44:49', 'PLATH GmbH'),
(14169, '30:46:9A', 'NETGEAR'),
(14170, '30:49:3B', 'Nanjing Z-Com Wireless Co.,Ltd'),
(14171, '30:4C:7E', 'Panasonic Electric Works Automation Controls Techno Co.,Ltd.'),
(14172, '30:4E:C3', 'Tianjin Techua Technology Co., Ltd.'),
(14173, '30:51:F8', 'BYK-Gardner GmbH'),
(14174, '30:52:5A', 'NST Co., LTD'),
(14175, '30:55:ED', 'Trex Network LLC'),
(14176, '30:57:AC', 'IRLAB LTD.'),
(14177, '30:59:5B', 'streamnow AG'),
(14178, '30:59:B7', 'Microsoft'),
(14179, '30:5D:38', 'Beissbarth'),
(14180, '30:60:23', 'ARRIS Group, Inc.'),
(14181, '30:61:12', 'PAV GmbH'),
(14182, '30:61:18', 'Paradom Inc.'),
(14183, '30:65:EC', 'Wistron (ChongQing)'),
(14184, '30:68:8C', 'Reach Technology Inc.'),
(14185, '30:69:4B', 'RIM'),
(14186, '30:6C:BE', 'Skymotion Technology (HK) Limited'),
(14187, '30:6E:5C', 'Validus Technologies'),
(14188, '30:71:B2', 'Hangzhou Prevail Optoelectronic Equipment Co.,LTD.'),
(14189, '30:73:50', 'Inpeco SA'),
(14190, '30:75:12', 'Sony Mobile Communications AB'),
(14191, '30:76:6F', 'LG Electronics'),
(14192, '30:77:CB', 'Maike Industry(Shenzhen)CO.,LTD'),
(14193, '30:78:6B', 'TIANJIN Golden Pentagon Electronics Co., Ltd.'),
(14194, '30:78:C2', 'Innowireless, Co. Ltd.'),
(14195, '30:7C:30', 'RIM'),
(14196, '30:7E:CB', 'SFR'),
(14197, '30:85:A9', 'Asustek Computer Inc'),
(14198, '30:87:30', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(14199, '30:89:99', 'Guangdong East Power Co.,'),
(14200, '30:8C:FB', 'Dropcam'),
(14201, '30:90:AB', 'Apple'),
(14202, '30:91:8F', 'Technicolor'),
(14203, '30:92:F6', 'SHANGHAI SUNMON COMMUNICATION TECHNOGY CO.,LTD'),
(14204, '30:9B:AD', 'BBK Electronics Corp., Ltd.,'),
(14205, '30:A8:DB', 'Sony Mobile Communications AB'),
(14206, '30:AA:BD', 'Shanghai Reallytek Information Technology Co.,Ltd'),
(14207, '30:AE:7B', 'Deqing Dusun Electron CO., LTD'),
(14208, '30:AE:F6', 'Radio Mobile Access'),
(14209, '30:B2:16', 'Hytec Geraetebau GmbH'),
(14210, '30:B3:A2', 'Shenzhen Heguang Measurement &amp; Control Technology Co.,Ltd'),
(14211, '30:B5:C2', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(14212, '30:B5:F1', 'Aitexin Technology Co., Ltd'),
(14213, '30:C7:50', 'MIC Technology Group'),
(14214, '30:C7:AE', 'Samsung Electronics Co.,Ltd'),
(14215, '30:C8:2A', 'Wi-Next s.r.l.'),
(14216, '30:CD:A7', 'Samsung Electronics ITS, Printer division'),
(14217, '30:D1:7E', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14218, '30:D3:57', 'Logosol, Inc.'),
(14219, '30:D4:6A', 'Autosales Incorporated'),
(14220, '30:D5:87', 'Samsung Electronics Co.,Ltd'),
(14221, '30:D6:C9', 'Samsung Electronics Co.,Ltd'),
(14222, '30:DE:86', 'Cedac Software S.r.l.'),
(14223, '30:E4:8E', 'Vodafone UK'),
(14224, '30:E4:DB', 'CISCO SYSTEMS, INC.'),
(14225, '30:EB:25', 'INTEK DIGITAL'),
(14226, '30:EF:D1', 'Alstom Strongwish (Shenzhen) Co., Ltd.'),
(14227, '30:F3:1D', 'zte corporation'),
(14228, '30:F3:3A', '+plugg srl'),
(14229, '30:F4:2F', 'ESP'),
(14230, '30:F7:0D', 'Cisco Systems'),
(14231, '30:F7:C5', 'Apple'),
(14232, '30:F7:D7', 'Thread Technology Co., Ltd'),
(14233, '30:F9:ED', 'Sony Corporation'),
(14234, '30:FA:B7', 'Tunai Creative'),
(14235, '30:FD:11', 'MACROTECH (USA) INC.'),
(14236, '34:00:A3', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14237, '34:02:86', 'Intel Corporate'),
(14238, '34:02:9B', 'CloudBerry Technologies Private Limited'),
(14239, '34:07:FB', 'Ericsson AB'),
(14240, '34:08:04', 'D-Link Corporation'),
(14241, '34:0A:FF', 'Qingdao Hisense Communications Co.,Ltd'),
(14242, '34:13:A8', 'Mediplan Limited'),
(14243, '34:13:E8', 'Intel Corporate'),
(14244, '34:15:9E', 'Apple'),
(14245, '34:17:EB', 'Dell Inc'),
(14246, '34:1A:4C', 'SHENZHEN WEIBU ELECTRONICS CO.,LTD.'),
(14247, '34:1B:22', 'Grandbeing Technology Co., Ltd'),
(14248, '34:21:09', 'Jensen Scandinavia AS'),
(14249, '34:23:87', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14250, '34:23:BA', 'Samsung Electro Mechanics co.,LTD.'),
(14251, '34:25:5D', 'Shenzhen Loadcom Technology Co.,Ltd'),
(14252, '34:28:F0', 'ATN International Limited'),
(14253, '34:29:EA', 'MCD ELECTRONICS SP. Z O.O.'),
(14254, '34:2F:6E', 'Anywire corporation'),
(14255, '34:31:11', 'Samsung Electronics Co.,Ltd'),
(14256, '34:31:C4', 'AVM GmbH'),
(14257, '34:36:3B', 'Apple'),
(14258, '34:38:AF', 'Inlab Software GmbH'),
(14259, '34:40:B5', 'IBM'),
(14260, '34:46:6F', 'HiTEM Engineering'),
(14261, '34:4B:3D', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(14262, '34:4B:50', 'ZTE Corporation'),
(14263, '34:4C:A4', 'amazipoint technology Ltd.'),
(14264, '34:4D:EA', 'zte corporation'),
(14265, '34:4D:F7', 'LG Electronics'),
(14266, '34:4F:3F', 'IO-Power Technology Co., Ltd.'),
(14267, '34:4F:5C', 'R&amp;amp;M AG'),
(14268, '34:4F:69', 'EKINOPS SAS'),
(14269, '34:51:AA', 'JID GLOBAL'),
(14270, '34:51:C9', 'Apple'),
(14271, '34:5B:11', 'EVI HEAT AB'),
(14272, '34:5C:40', 'Cargt Holdings LLC'),
(14273, '34:5D:10', 'Wytek'),
(14274, '34:61:78', 'The Boeing Company'),
(14275, '34:62:88', 'Cisco'),
(14276, '34:64:A9', 'Hewlett Packard'),
(14277, '34:68:4A', 'Teraworks Co., Ltd.'),
(14278, '34:68:95', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14279, '34:6B:D3', 'Huawei Technologies Co., Ltd'),
(14280, '34:6C:0F', 'Pramod Telecom Pvt. Ltd'),
(14281, '34:6E:8A', 'Ecosense'),
(14282, '34:6F:90', 'Cisco'),
(14283, '34:6F:92', 'White Rodgers Division'),
(14284, '34:75:C7', 'Avaya, Inc'),
(14285, '34:76:C5', 'I-O DATA DEVICE, INC.'),
(14286, '34:78:77', 'O-NET Communications(Shenzhen) Limited'),
(14287, '34:7E:39', 'Nokia Danmark A/S'),
(14288, '34:81:37', 'UNICARD SA'),
(14289, '34:81:C4', 'AVM GmbH'),
(14290, '34:82:DE', 'Kayo Technology, Inc.'),
(14291, '34:83:02', 'iFORCOM Co., Ltd'),
(14292, '34:84:46', 'Ericsson AB'),
(14293, '34:86:2A', 'Heinz Lackmann GmbH &amp; Co KG'),
(14294, '34:87:3D', 'Quectel Wireless Solution Co.,Ltd.'),
(14295, '34:88:5D', 'Logitech Far East'),
(14296, '34:8A:AE', 'SAGEMCOM SAS'),
(14297, '34:95:DB', 'Logitec Corporation'),
(14298, '34:97:FB', 'ADVANCED RF TECHNOLOGIES INC'),
(14299, '34:99:6F', 'VPI Engineering'),
(14300, '34:99:D7', 'Universal Flow Monitors, Inc.'),
(14301, '34:9A:0D', 'ZBD Displays Ltd'),
(14302, '34:9D:90', 'Heinzmann GmbH &amp; CO. KG'),
(14303, '34:9E:34', 'Evervictory Electronic Co.Ltd'),
(14304, '34:A1:83', 'AWare, Inc'),
(14305, '34:A3:95', 'Apple'),
(14306, '34:A3:BF', 'Terewave. Inc.'),
(14307, '34:A5:5D', 'TECHNOSOFT INTERNATIONAL SRL'),
(14308, '34:A5:E1', 'Sensorist ApS'),
(14309, '34:A6:8C', 'Shine Profit Development Limited'),
(14310, '34:A7:09', 'Trevil srl'),
(14311, '34:A7:BA', 'Fischer International Systems Corporation'),
(14312, '34:A8:43', 'KYOCERA Display Corporation'),
(14313, '34:A8:4E', 'Cisco'),
(14314, '34:AA:8B', 'Samsung Electronics Co.,Ltd'),
(14315, '34:AA:99', 'Alcatel-Lucent'),
(14316, '34:AA:EE', 'Mikrovisatos Servisas UAB'),
(14317, '34:AD:E4', 'Shanghai Chint Power Systems Co., Ltd.'),
(14318, '34:AF:2C', 'Nintendo Co., Ltd.'),
(14319, '34:B1:F7', 'Texas Instruments'),
(14320, '34:B5:71', 'PLDS'),
(14321, '34:B7:FD', 'Guangzhou Younghead Electronic Technology Co.,Ltd'),
(14322, '34:BA:51', 'Se-Kure Controls, Inc.'),
(14323, '34:BA:9A', 'Asiatelco Technologies Co.'),
(14324, '34:BB:1F', 'Research In Motion'),
(14325, '34:BB:26', 'Motorola Mobility LLC'),
(14326, '34:BC:A6', 'Beijing Ding Qing Technology, Ltd.'),
(14327, '34:BD:C8', 'Cisco Systems'),
(14328, '34:BD:F9', 'Shanghai WDK Industrial Co.,Ltd.'),
(14329, '34:BD:FA', 'Cisco SPVTG'),
(14330, '34:BE:00', 'Samsung Electronics Co.,Ltd'),
(14331, '34:BF:90', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(14332, '34:C0:59', 'Apple'),
(14333, '34:C3:AC', 'Samsung Electronics'),
(14334, '34:C5:D0', 'Hagleitner Hygiene International GmbH'),
(14335, '34:C6:9A', 'Enecsys Ltd'),
(14336, '34:C7:31', 'ALPS Co,. Ltd.'),
(14337, '34:C8:03', 'Nokia Corporation'),
(14338, '34:C9:9D', 'EIDOLON COMMUNICATIONS TECHNOLOGY CO. LTD.'),
(14339, '34:CD:6D', 'CommSky Technologies'),
(14340, '34:CD:BE', 'Huawei Technologies Co., Ltd'),
(14341, '34:CE:94', 'Parsec (Pty) Ltd'),
(14342, '34:D0:9B', 'MobilMAX Technology Inc.'),
(14343, '34:D2:C4', 'RENA GmbH Print Systeme'),
(14344, '34:D7:B4', 'Tributary Systems, Inc.'),
(14345, '34:DB:FD', 'Cisco'),
(14346, '34:DE:1A', 'Intel Corporate'),
(14347, '34:DE:34', 'zte corporation'),
(14348, '34:DF:2A', 'Fujikon Industrial Co.,Limited'),
(14349, '34:E0:CF', 'zte corporation'),
(14350, '34:E0:D7', 'DONGGUAN QISHENG ELECTRONICS INDUSTRIAL CO., LTD'),
(14351, '34:E2:FD', 'Apple'),
(14352, '34:E4:2A', 'Automatic Bar Controls Inc.'),
(14353, '34:E6:AD', 'Intel Corporate'),
(14354, '34:E6:D7', 'Dell Inc.'),
(14355, '34:EF:44', '2Wire'),
(14356, '34:EF:8B', 'NTT Communications Corporation'),
(14357, '34:F0:CA', 'Shenzhen Linghangyuan Digital Technology Co.,Ltd.'),
(14358, '34:F3:9B', 'WizLAN Ltd.'),
(14359, '34:F6:2D', 'SHARP Corporation'),
(14360, '34:F6:D2', 'Panasonic Taiwan Co.,Ltd.'),
(14361, '34:F9:68', 'ATEK Products, LLC'),
(14362, '34:FA:40', 'Guangzhou Robustel Technologies Co., Limited'),
(14363, '34:FC:6F', 'ALCEA'),
(14364, '34:FC:EF', 'LG Electronics'),
(14365, '38:01:97', 'Toshiba Samsung Storage Technolgoy Korea Corporation'),
(14366, '38:06:B4', 'A.D.C. GmbH'),
(14367, '38:08:FD', 'Silca Spa'),
(14368, '38:09:A4', 'Firefly Integrations'),
(14369, '38:0A:0A', 'Sky-City Communication and Electronics Limited Company'),
(14370, '38:0A:94', 'Samsung Electronics Co.,Ltd'),
(14371, '38:0B:40', 'Samsung Electronics Co.,Ltd'),
(14372, '38:0D:D4', 'Primax Electronics LTD.'),
(14373, '38:0E:7B', 'V.P.S. Thai Co., Ltd'),
(14374, '38:0F:4A', 'Apple'),
(14375, '38:0F:E4', 'Dedicated Network Partners Oy'),
(14376, '38:16:D1', 'Samsung Electronics Co.,Ltd'),
(14377, '38:17:66', 'PROMZAKAZ LTD.'),
(14378, '38:19:2F', 'Nokia Corporation'),
(14379, '38:1C:1A', 'Cisco'),
(14380, '38:1C:4A', 'SIMCom Wireless Solutions Co.,Ltd.'),
(14381, '38:22:9D', 'Pirelli Tyre S.p.A.'),
(14382, '38:22:D6', 'H3C Technologies Co., Limited'),
(14383, '38:26:2B', 'UTran Technology'),
(14384, '38:26:CD', 'ANDTEK'),
(14385, '38:28:EA', 'Fujian Netcom Technology Co., LTD'),
(14386, '38:2C:4A', 'ASUSTek COMPUTER INC.'),
(14387, '38:2D:D1', 'Samsung Electronics Co.,Ltd'),
(14388, '38:31:AC', 'WEG'),
(14389, '38:3B:C8', '2wire'),
(14390, '38:3F:10', 'DBL Technology Ltd.'),
(14391, '38:42:33', 'Wildeboer Bauteile GmbH'),
(14392, '38:42:A6', 'Ingenieurbuero Stahlkopf'),
(14393, '38:43:69', 'Patrol Products Consortium LLC'),
(14394, '38:45:8C', 'MyCloud Technology corporation'),
(14395, '38:46:08', 'ZTE Corporation'),
(14396, '38:48:4C', 'Apple'),
(14397, '38:4B:76', 'AIRTAME ApS'),
(14398, '38:4F:F0', 'Azurewave Technologies, Inc.'),
(14399, '38:52:1A', 'Alcatel-Lucent 7705'),
(14400, '38:58:0C', 'Panaccess Systems GmbH'),
(14401, '38:59:F8', 'MindMade sp. z o.o.'),
(14402, '38:59:F9', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14403, '38:5A:A8', 'Beijing Zhongdun Security Technology Development Co.'),
(14404, '38:5F:C3', 'Yu Jeong System, Co.Ltd'),
(14405, '38:60:77', 'PEGATRON CORPORATION'),
(14406, '38:63:BB', 'Hewlett Packard'),
(14407, '38:63:F6', '3NOD MULTIMEDIA(SHENZHEN)CO.,LTD'),
(14408, '38:66:45', 'OOSIC Technology CO.,Ltd'),
(14409, '38:67:93', 'Asia Optical Co., Inc.'),
(14410, '38:6B:BB', 'ARRIS Group, Inc.'),
(14411, '38:6C:9B', 'Ivy Biomedical'),
(14412, '38:6E:21', 'Wasion Group Ltd.'),
(14413, '38:72:C0', 'COMTREND'),
(14414, '38:7B:47', 'AKELA, Inc.'),
(14415, '38:83:45', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(14416, '38:89:DC', 'Opticon Sensors Europe B.V.'),
(14417, '38:8A:B7', 'ITC Networks'),
(14418, '38:8E:E7', 'Fanhattan LLC'),
(14419, '38:91:FB', 'Xenox Holding BV'),
(14420, '38:94:96', 'Samsung Elec Co.,Ltd'),
(14421, '38:95:92', 'Beijing Tendyron Corporation'),
(14422, '38:9F:83', 'OTN Systems N.V.'),
(14423, '38:A5:3C', 'Veenstra Instruments'),
(14424, '38:A5:B6', 'SHENZHEN MEGMEET ELECTRICAL CO.,LTD'),
(14425, '38:A8:51', 'Moog, Ing'),
(14426, '38:A8:6B', 'Orga BV'),
(14427, '38:A9:5F', 'Actifio Inc'),
(14428, '38:AA:3C', 'SAMSUNG ELECTRO-MECHANICS'),
(14429, '38:B1:2D', 'Sonotronic Nagel GmbH'),
(14430, '38:B1:DB', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14431, '38:B5:BD', 'E.G.O. Elektro-Ger'),
(14432, '38:B7:4D', 'Fijowave Limited'),
(14433, '38:BB:23', 'OzVision America LLC'),
(14434, '38:BB:3C', 'Avaya, Inc'),
(14435, '38:BC:1A', 'Meizu technology co.,ltd'),
(14436, '38:BF:2F', 'Espec Corp.'),
(14437, '38:BF:33', 'NEC CASIO Mobile Communications'),
(14438, '38:C0:96', 'ALPS ELECTRIC CO.,LTD.'),
(14439, '38:C7:0A', 'WiFiSong'),
(14440, '38:C7:BA', 'CS Services Co.,Ltd.'),
(14441, '38:C8:5C', 'Cisco SPVTG'),
(14442, '38:C9:A9', 'SMART High Reliability Solutions, Inc.'),
(14443, '38:CA:97', 'Contour Design LLC'),
(14444, '38:D1:35', 'EasyIO Corporation Sdn. Bhd.'),
(14445, '38:D8:2F', 'zte corporation'),
(14446, '38:DB:BB', 'Sunbow Telecom Co., Ltd.'),
(14447, '38:DE:60', 'Mohlenhoff GmbH'),
(14448, '38:E0:8E', 'Mitsubishi Electric Corporation'),
(14449, '38:E5:95', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(14450, '38:E7:D8', 'HTC Corporation'),
(14451, '38:E8:DF', 'b gmbh medien + datenbanken'),
(14452, '38:E9:8C', 'Reco S.p.A.'),
(14453, '38:EA:A7', 'Hewlett Packard'),
(14454, '38:EC:11', 'Novatek Microelectronics Corp.'),
(14455, '38:EC:E4', 'Samsung Electronics'),
(14456, '38:EE:9D', 'Anedo Ltd.'),
(14457, '38:F0:98', 'Vapor Stone Rail Systems'),
(14458, '38:F3:3F', 'TATSUNO CORPORATION'),
(14459, '38:F5:97', 'home2net GmbH'),
(14460, '38:F7:08', 'National Resource Management, Inc.'),
(14461, '38:F8:89', 'Huawei Technologies Co., Ltd'),
(14462, '38:F8:B7', 'V2COM PARTICIPACOES S.A.'),
(14463, '38:FE:C5', 'Ellips B.V.'),
(14464, '3C:02:B1', 'Creation Technologies LP'),
(14465, '3C:04:BF', 'PRAVIS SYSTEMS Co.Ltd.,'),
(14466, '3C:05:AB', 'Product Creation Studio'),
(14467, '3C:07:54', 'Apple'),
(14468, '3C:07:71', 'Sony Corporation'),
(14469, '3C:08:1E', 'Beijing Yupont Electric Power Technology Co.,Ltd'),
(14470, '3C:08:F6', 'Cisco'),
(14471, '3C:09:6D', 'Powerhouse Dynamics'),
(14472, '3C:0C:48', 'Servergy, Inc.'),
(14473, '3C:0E:23', 'Cisco'),
(14474, '3C:0F:C1', 'KBC Networks'),
(14475, '3C:10:40', 'daesung network'),
(14476, '3C:10:6F', 'ALBAHITH TECHNOLOGIES'),
(14477, '3C:15:C2', 'Apple'),
(14478, '3C:15:EA', 'TESCOM CO., LTD.'),
(14479, '3C:18:9F', 'Nokia Corporation'),
(14480, '3C:18:A0', 'Luxshare Precision Industry Co.,Ltd.'),
(14481, '3C:19:15', 'GFI Chrono Time'),
(14482, '3C:19:7D', 'Ericsson AB'),
(14483, '3C:1A:0F', 'ClearSky Data'),
(14484, '3C:1A:57', 'Cardiopulmonary Corp'),
(14485, '3C:1A:79', 'Huayuan Technology CO.,LTD'),
(14486, '3C:1C:BE', 'JADAK LLC'),
(14487, '3C:1E:04', 'D-Link International'),
(14488, '3C:1E:13', 'HANGZHOU SUNRISE TECHNOLOGY CO., LTD'),
(14489, '3C:25:D7', 'Nokia Corporation'),
(14490, '3C:26:D5', 'Sotera Wireless'),
(14491, '3C:27:63', 'SLE quality engineering GmbH &amp; Co. KG'),
(14492, '3C:2C:94', 'æ­å·å¾·æ¾ç§ææéå¬å¸ï¼HangZhou Delan Technology Co.,Ltdï¼'),
(14493, '3C:2D:B7', 'Texas Instruments'),
(14494, '3C:2F:3A', 'SFORZATO Corp.'),
(14495, '3C:30:0C', 'Dewar Electronics Pty Ltd'),
(14496, '3C:36:3D', 'Nokia Corporation'),
(14497, '3C:36:E4', 'Arris Group, Inc.'),
(14498, '3C:38:88', 'ConnectQuest, llc'),
(14499, '3C:39:C3', 'JW Electronics Co., Ltd.'),
(14500, '3C:39:E7', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(14501, '3C:3A:73', 'Avaya, Inc'),
(14502, '3C:40:4F', 'Guangdong Pisen Electronics Co. Ltd.'),
(14503, '3C:43:8E', 'ARRIS Group, Inc.'),
(14504, '3C:46:D8', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(14505, '3C:49:37', 'ASSMANN Electronic GmbH'),
(14506, '3C:4A:92', 'Hewlett-Packard Company'),
(14507, '3C:4C:69', 'Infinity System S.L.'),
(14508, '3C:4E:47', 'Etronic A/S'),
(14509, '3C:57:BD', 'Kessler Crane Inc.'),
(14510, '3C:57:D5', 'FiveCo'),
(14511, '3C:5A:37', 'Samsung Electronics'),
(14512, '3C:5A:B4', 'Google'),
(14513, '3C:5E:C3', 'Cisco'),
(14514, '3C:5F:01', 'Synerchip Co., Ltd.'),
(14515, '3C:61:04', 'Juniper Networks'),
(14516, '3C:62:00', 'Samsung electronics CO., LTD'),
(14517, '3C:62:78', 'SHENZHEN JETNET TECHNOLOGY CO.,LTD.'),
(14518, '3C:67:2C', 'Sciovid Inc.'),
(14519, '3C:6A:7D', 'Niigata Power Systems Co., Ltd.'),
(14520, '3C:6A:9D', 'Dexatek Technology LTD.'),
(14521, '3C:6E:63', 'Mitron OY'),
(14522, '3C:6F:45', 'Fiberpro Inc.'),
(14523, '3C:6F:F7', 'EnTek Systems, Inc.'),
(14524, '3C:70:59', 'MakerBot Industries'),
(14525, '3C:74:37', 'RIM'),
(14526, '3C:75:4A', 'ARRIS Group, Inc.'),
(14527, '3C:77:E6', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14528, '3C:7D:B1', 'Texas Instruments'),
(14529, '3C:81:D8', 'SAGEMCOM SAS'),
(14530, '3C:83:B5', 'Advance Vision Electronics Co. Ltd.'),
(14531, '3C:86:A8', 'Sangshin elecom .co,, LTD'),
(14532, '3C:89:A6', 'KAPELSE'),
(14533, '3C:8A:B0', 'Juniper Networks'),
(14534, '3C:8A:E5', 'Tensun Information Technology(Hangzhou) Co.,LTD'),
(14535, '3C:8B:FE', 'Samsung Electronics'),
(14536, '3C:8C:40', 'Hangzhou H3C Technologies Co., Limited'),
(14537, '3C:91:2B', 'Vexata Inc'),
(14538, '3C:91:57', 'Hangzhou Yulong Conmunication Co.,Ltd'),
(14539, '3C:91:74', 'ALONG COMMUNICATION TECHNOLOGY'),
(14540, '3C:94:D5', 'Juniper Networks'),
(14541, '3C:97:0E', 'Wistron InfoComm(Kunshan)Co.,Ltd.'),
(14542, '3C:97:7E', 'IPS Technology Limited'),
(14543, '3C:98:BF', 'Quest Controls, Inc.'),
(14544, '3C:99:F7', 'Lansentechnology AB'),
(14545, '3C:9F:81', 'Shenzhen CATIC Bit Communications Technology Co.,Ltd'),
(14546, '3C:A1:0D', 'Samsung Electronics Co.,Ltd'),
(14547, '3C:A3:15', 'Bless Information &amp; Communications Co., Ltd'),
(14548, '3C:A7:2B', 'MRV Communications (Networks) LTD'),
(14549, '3C:A9:F4', 'Intel Corporate'),
(14550, '3C:AA:3F', 'iKey, Ltd.'),
(14551, '3C:AB:8E', 'Apple'),
(14552, '3C:AE:69', 'ESA Elektroschaltanlagen Grimma GmbH'),
(14553, '3C:B1:5B', 'Avaya, Inc'),
(14554, '3C:B1:7F', 'Wattwatchers Pty Ld'),
(14555, '3C:B7:92', 'Hitachi Maxell, Ltd., Optronics Division'),
(14556, '3C:B8:7A', 'PRIVATE'),
(14557, '3C:B9:A6', 'Belden Deutschland GmbH'),
(14558, '3C:BD:D8', 'LG ELECTRONICS INC'),
(14559, '3C:C0:C6', 'd&amp;b audiotechnik GmbH'),
(14560, '3C:C1:2C', 'AES Corporation'),
(14561, '3C:C1:F6', 'Melange Systems Pvt. Ltd.'),
(14562, '3C:C2:43', 'Nokia Corporation'),
(14563, '3C:C2:E1', 'XINHUA CONTROL ENGINEERING CO.,LTD'),
(14564, '3C:C9:9E', 'Huiyang Technology Co., Ltd'),
(14565, '3C:CA:87', 'Iders Incorporated'),
(14566, '3C:CB:7C', 'TCT mobile ltd'),
(14567, '3C:CD:5A', 'Technische Alternative GmbH'),
(14568, '3C:CD:93', 'LG ELECTRONICS INC'),
(14569, '3C:CE:73', 'CISCO SYSTEMS, INC.'),
(14570, '3C:D0:F8', 'Apple'),
(14571, '3C:D1:6E', 'Telepower Communication Co., Ltd'),
(14572, '3C:D4:D6', 'WirelessWERX, Inc'),
(14573, '3C:D7:DA', 'SK Mtek microelectronics(shenzhen)limited'),
(14574, '3C:D9:2B', 'Hewlett-Packard Company'),
(14575, '3C:D9:CE', 'Eclipse WiFi'),
(14576, '3C:DF:1E', 'CISCO SYSTEMS, INC.'),
(14577, '3C:DF:BD', 'Huawei Technologies Co., Ltd'),
(14578, '3C:E0:72', 'Apple'),
(14579, '3C:E5:A6', 'Hangzhou H3C Technologies Co., Ltd.'),
(14580, '3C:E5:B4', 'KIDASEN INDUSTRIA E COMERCIO DE ANTENAS LTDA'),
(14581, '3C:E6:24', 'LG Display'),
(14582, '3C:EA:4F', '2Wire'),
(14583, '3C:EA:FB', 'NSE AG'),
(14584, '3C:F3:92', 'Virtualtek. Co. Ltd'),
(14585, '3C:F5:2C', 'DSPECIALISTS GmbH'),
(14586, '3C:F7:2A', 'Nokia Corporation'),
(14587, '3C:F7:48', 'Shenzhen Linsn Technology Development Co.,Ltd'),
(14588, '3C:F8:08', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14589, '3C:FB:96', 'Emcraft Systems LLC'),
(14590, '3C:FD:FE', 'Intel Corporate'),
(14591, '40:01:07', 'Arista Corp'),
(14592, '40:01:C6', '3COM EUROPE LTD'),
(14593, '40:04:0C', 'A&amp;T'),
(14594, '40:07:C0', 'Railtec Systems GmbH'),
(14595, '40:0E:67', 'Tremol Ltd.'),
(14596, '40:0E:85', 'Samsung Electro Mechanics co.,LTD.'),
(14597, '40:12:E4', 'Compass-EOS'),
(14598, '40:13:D9', 'Global ES'),
(14599, '40:15:97', 'Protect America, Inc.'),
(14600, '40:16:7E', 'ASUSTek COMPUTER INC.'),
(14601, '40:16:9F', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(14602, '40:16:FA', 'EKM Metering'),
(14603, '40:18:B1', 'Aerohive Networks Inc.'),
(14604, '40:18:D7', 'Smartronix, Inc.'),
(14605, '40:1D:59', 'Biometric Associates, LP'),
(14606, '40:22:ED', 'Digital Projection Ltd'),
(14607, '40:25:C2', 'Intel Corporate'),
(14608, '40:27:0B', 'Mobileeco Co., Ltd'),
(14609, '40:2B:A1', 'Sony Ericsson Mobile Communications AB'),
(14610, '40:2C:F4', 'Universal Global Scientific Industrial Co., Ltd.'),
(14611, '40:30:04', 'Apple'),
(14612, '40:30:67', 'Conlog (Pty) Ltd'),
(14613, '40:33:6C', 'Godrej &amp; Boyce Mfg. co. ltd'),
(14614, '40:37:AD', 'Macro Image Technology, Inc.'),
(14615, '40:3C:FC', 'Apple'),
(14616, '40:40:22', 'ZIV'),
(14617, '40:40:6B', 'Icomera'),
(14618, '40:45:DA', 'Spreadtrum Communications (Shanghai) Co., Ltd.'),
(14619, '40:4A:03', 'ZyXEL Communications Corporation'),
(14620, '40:4A:18', 'Addrek Smart Solutions'),
(14621, '40:4D:8E', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(14622, '40:4E:EB', 'Higher Way Electronic Co., Ltd.'),
(14623, '40:50:E0', 'Milton Security Group LLC'),
(14624, '40:51:6C', 'Grandex International Corporation'),
(14625, '40:52:0D', 'Pico Technology'),
(14626, '40:55:39', 'CISCO SYSTEMS, INC.'),
(14627, '40:56:0C', 'In Home Displays Ltd'),
(14628, '40:5A:9B', 'ANOVO'),
(14629, '40:5F:BE', 'RIM'),
(14630, '40:5F:C2', 'Texas Instruments'),
(14631, '40:60:5A', 'Hawkeye Tech Co. Ltd'),
(14632, '40:61:86', 'MICRO-STAR INT\'L CO.,LTD'),
(14633, '40:61:8E', 'Stella-Green Co'),
(14634, '40:62:B6', 'Tele system communication'),
(14635, '40:66:7A', 'mediola - connected living AG'),
(14636, '40:68:26', 'Thales UK Limited'),
(14637, '40:6A:AB', 'RIM'),
(14638, '40:6C:8F', 'Apple'),
(14639, '40:6F:2A', 'Research In Motion'),
(14640, '40:70:09', 'ARRIS Group, Inc.'),
(14641, '40:70:4A', 'Power Idea Technology Limited'),
(14642, '40:70:74', 'Life Technology (China) Co., Ltd'),
(14643, '40:74:96', 'aFUN TECHNOLOGY INC.'),
(14644, '40:78:6A', 'Motorola Mobility LLC'),
(14645, '40:78:75', 'IMBEL - Industria de Material Belico do Brasil'),
(14646, '40:7A:80', 'Nokia Corporation'),
(14647, '40:7B:1B', 'Mettle Networks Inc.'),
(14648, '40:82:56', 'Continental Automotive GmbH'),
(14649, '40:83:DE', 'Zebra Technologies Inc'),
(14650, '40:84:93', 'Clavister AB'),
(14651, '40:88:E0', 'Beijing Ereneben Information Technology Limited Shenzhen Branch'),
(14652, '40:8A:9A', 'TITENG CO., Ltd.'),
(14653, '40:8B:07', 'Actiontec Electronics, Inc'),
(14654, '40:8B:F6', 'Shenzhen TCL New Technology Co; Ltd.'),
(14655, '40:95:58', 'Aisino Corporation'),
(14656, '40:97:D1', 'BK Electronics cc'),
(14657, '40:98:4C', 'Casacom Solutions AG'),
(14658, '40:98:4E', 'Texas Instruments'),
(14659, '40:98:7B', 'Aisino Corporation'),
(14660, '40:9B:0D', 'Shenzhen Yourf Kwan Industrial Co., Ltd'),
(14661, '40:9F:C7', 'BAEKCHUN I&amp;C Co., Ltd.'),
(14662, '40:A5:EF', 'Shenzhen Four Seas Global Link Network Technology Co., Ltd.'),
(14663, '40:A6:77', 'Juniper Networks'),
(14664, '40:A6:A4', 'PassivSystems Ltd'),
(14665, '40:A6:D9', 'Apple'),
(14666, '40:A8:F0', 'Hewlett Packard'),
(14667, '40:AC:8D', 'Data Management, Inc.'),
(14668, '40:B0:FA', 'LG Electronics'),
(14669, '40:B2:C8', 'Nortel Networks'),
(14670, '40:B3:95', 'Apple'),
(14671, '40:B3:CD', 'Chiyoda Electronics Co.,Ltd.'),
(14672, '40:B3:FC', 'Logital Co. Limited'),
(14673, '40:B4:F0', 'Juniper Networks'),
(14674, '40:B6:B1', 'SUNGSAM CO,.Ltd'),
(14675, '40:B7:F3', 'ARRIS Group, Inc.'),
(14676, '40:BA:61', 'Arima Communications Corp.'),
(14677, '40:BC:73', 'Cronoplast  S.L.'),
(14678, '40:BC:8B', 'itelio GmbH'),
(14679, '40:BD:9E', 'Physio-Control, Inc'),
(14680, '40:BF:17', 'Digistar Telecom. SA'),
(14681, '40:C2:45', 'Shenzhen Hexicom Technology Co., Ltd.'),
(14682, '40:C4:D6', 'ChongQing Camyu Technology Development Co.,Ltd.'),
(14683, '40:C6:2A', 'Shanghai Jing Ren Electronic Technology Co., Ltd.'),
(14684, '40:C7:C9', 'Naviit Inc.'),
(14685, '40:CB:A8', 'Huawei Technologies Co., Ltd'),
(14686, '40:CD:3A', 'Z3 Technology'),
(14687, '40:D2:8A', 'Nintendo Co., Ltd.'),
(14688, '40:D3:2D', 'Apple'),
(14689, '40:D4:0E', 'Biodata Ltd'),
(14690, '40:D5:59', 'MICRO S.E.R.I.'),
(14691, '40:D8:55', 'IEEE REGISTRATION AUTHORITY - Please see IAB public listing for more information.'),
(14692, '40:E2:30', 'AzureWave Technologies, Inc.'),
(14693, '40:E7:30', 'DEY Storage Systems, Inc.'),
(14694, '40:E7:93', 'Shenzhen Siviton Technology Co.,Ltd'),
(14695, '40:EA:CE', 'FOUNDER BROADBAND NETWORK SERVICE CO.,LTD'),
(14696, '40:EC:F8', 'Siemens AG'),
(14697, '40:EF:4C', 'Fihonest communication co.,Ltd'),
(14698, '40:F0:2F', 'Liteon Technology Corporation'),
(14699, '40:F1:4C', 'ISE Europe SPRL'),
(14700, '40:F2:01', 'SAGEMCOM'),
(14701, '40:F2:E9', 'IBM'),
(14702, '40:F3:08', 'Murata Manufactuaring Co.,Ltd.'),
(14703, '40:F4:07', 'Nintendo Co., Ltd.'),
(14704, '40:F4:EC', 'CISCO SYSTEMS, INC.'),
(14705, '40:F5:2E', 'Leica Microsystems (Schweiz) AG'),
(14706, '40:FC:89', 'ARRIS Group, Inc.'),
(14707, '44:03:A7', 'Cisco'),
(14708, '44:0C:FD', 'NetMan Co., Ltd.'),
(14709, '44:11:C2', 'Telegartner Karl Gartner GmbH'),
(14710, '44:13:19', 'WKK TECHNOLOGY LTD.'),
(14711, '44:18:4F', 'Fitview'),
(14712, '44:19:B6', 'Hangzhou Hikvision Digital Technology Co.,Ltd.'),
(14713, '44:1E:91', 'ARVIDA Intelligent Electronics Technology  Co.,Ltd.'),
(14714, '44:1E:A1', 'Hewlett-Packard Company'),
(14715, '44:23:AA', 'Farmage Co., Ltd.'),
(14716, '44:25:BB', 'Bamboo Entertainment Corporation'),
(14717, '44:29:38', 'NietZsche enterprise Co.Ltd.'),
(14718, '44:2A:60', 'Apple'),
(14719, '44:2A:FF', 'E3 Technology, Inc.'),
(14720, '44:2B:03', 'CISCO SYSTEMS, INC.'),
(14721, '44:31:92', 'Hewlett Packard'),
(14722, '44:32:2A', 'Avaya, Inc'),
(14723, '44:32:C8', 'Technicolor USA Inc.'),
(14724, '44:33:4C', 'Shenzhen Bilian electronic CO.,LTD'),
(14725, '44:34:8F', 'MXT INDUSTRIAL LTDA'),
(14726, '44:35:6F', 'Neterix'),
(14727, '44:37:19', '2 Save Energy Ltd'),
(14728, '44:37:6F', 'Young Electric Sign Co'),
(14729, '44:37:E6', 'Hon Hai Precision Ind.Co.Ltd'),
(14730, '44:38:39', 'Cumulus Networks, inc'),
(14731, '44:39:C4', 'Universal Global Scientific Industrial Co.,Ltd'),
(14732, '44:3C:9C', 'Pintsch Tiefenbach GmbH'),
(14733, '44:3D:21', 'Nuvolt'),
(14734, '44:3E:B2', 'DEOTRON Co., LTD.'),
(14735, '44:48:91', 'HDMI Licensing, LLC'),
(14736, '44:4A:65', 'Silverflare Ltd.'),
(14737, '44:4C:0C', 'Apple'),
(14738, '44:4E:1A', 'Samsung Electronics Co.,Ltd'),
(14739, '44:4F:5E', 'Pan Studios Co.,Ltd.'),
(14740, '44:51:DB', 'Raytheon BBN Technologies'),
(14741, '44:54:C0', 'Thompson Aerospace'),
(14742, '44:56:8D', 'PNC Technologies  Co., Ltd.'),
(14743, '44:56:B7', 'Spawn Labs, Inc'),
(14744, '44:58:29', 'Cisco SPVTG'),
(14745, '44:59:9F', 'Criticare Systems, Inc'),
(14746, '44:5E:CD', 'Razer Inc'),
(14747, '44:5E:F3', 'Tonalite Holding B.V.'),
(14748, '44:5F:7A', 'Shihlin Electric &amp; Engineering Corp.'),
(14749, '44:61:32', 'ecobee inc'),
(14750, '44:61:9C', 'FONsystem co. ltd.'),
(14751, '44:66:6E', 'IP-LINE'),
(14752, '44:67:55', 'Orbit Irrigation'),
(14753, '44:68:AB', 'JUIN COMPANY, LIMITED'),
(14754, '44:6C:24', 'Reallin Electronic Co.,Ltd'),
(14755, '44:6D:57', 'Liteon Technology Corporation'),
(14756, '44:6D:6C', 'Samsung Elec Co.,Ltd'),
(14757, '44:70:0B', 'IFFU'),
(14758, '44:70:98', 'MING HONG TECHNOLOGY (SHEN ZHEN) LIMITED'),
(14759, '44:74:6C', 'Sony Mobile Communications AB'),
(14760, '44:7B:C4', 'DualShine Technology(SZ)Co.,Ltd'),
(14761, '44:7C:7F', 'Innolight Technology Corporation'),
(14762, '44:7D:A5', 'VTION INFORMATION TECHNOLOGY (FUJIAN) CO.,LTD'),
(14763, '44:7E:76', 'Trek Technology (S) Pte Ltd'),
(14764, '44:7E:95', 'Alpha and Omega, Inc'),
(14765, '44:80:EB', 'Motorola Mobility LLC, a Lenovo Company'),
(14766, '44:83:12', 'Star-Net'),
(14767, '44:85:00', 'Intel Corporate'),
(14768, '44:86:C1', 'Siemens Low Voltage &amp; Products'),
(14769, '44:87:FC', 'ELITEGROUP COMPUTER SYSTEM CO., LTD.'),
(14770, '44:88:CB', 'Camco Technologies NV'),
(14771, '44:8A:5B', 'Micro-Star INT\'L CO., LTD.'),
(14772, '44:8C:52', 'KTIS CO., Ltd'),
(14773, '44:8E:12', 'DT Research, Inc.'),
(14774, '44:8E:81', 'VIG'),
(14775, '44:91:DB', 'Shanghai Huaqin Telecom Technology Co.,Ltd'),
(14776, '44:94:FC', 'NETGEAR INC.,'),
(14777, '44:95:FA', 'Qingdao Santong Digital Technology Co.Ltd'),
(14778, '44:9B:78', 'The Now Factory'),
(14779, '44:9C:B5', 'Alcomp, Inc'),
(14780, '44:A4:2D', 'TCT Mobile Limited'),
(14781, '44:A6:89', 'PROMAX ELECTRONICA SA'),
(14782, '44:A6:E5', 'THINKING TECHNOLOGY CO.,LTD'),
(14783, '44:A7:CF', 'Murata Manufacturing Co., Ltd.'),
(14784, '44:A8:42', 'Dell Inc.'),
(14785, '44:A8:C2', 'SEWOO TECH CO., LTD'),
(14786, '44:AA:27', 'udworks Co., Ltd.'),
(14787, '44:AA:E8', 'Nanotec Electronic GmbH &amp; Co. KG'),
(14788, '44:AD:D9', 'Cisco'),
(14789, '44:B3:82', 'Kuang-chi Institute of Advanced Technology'),
(14790, '44:C1:5C', 'Texas Instruments'),
(14791, '44:C2:33', 'Guangzhou Comet Technology Development Co.Ltd'),
(14792, '44:C3:06', 'SIFROM Inc.'),
(14793, '44:C3:9B', 'OOO RUBEZH NPO'),
(14794, '44:C4:A9', 'Opticom Communication, LLC'),
(14795, '44:C5:6F', 'NGN Easy Satfinder (Tianjin) Electronic Co., Ltd'),
(14796, '44:C9:A2', 'Greenwald Industries'),
(14797, '44:CE:7D', 'SFR'),
(14798, '44:D1:5E', 'Shanghai Kingto Information Technology Ltd'),
(14799, '44:D2:44', 'Seiko Epson Corporation'),
(14800, '44:D2:CA', 'Anvia TV Oy'),
(14801, '44:D3:CA', 'CISCO SYSTEMS, INC.'),
(14802, '44:D4:E0', 'Sony Mobile Communications AB'),
(14803, '44:D6:3D', 'Talari Networks'),
(14804, '44:D8:32', 'Azurewave Technologies, Inc.'),
(14805, '44:D8:84', 'Apple'),
(14806, '44:D9:E7', 'Ubiquiti Networks, Inc.'),
(14807, '44:DC:91', 'PLANEX COMMUNICATIONS INC.'),
(14808, '44:DC:CB', 'SEMINDIA SYSTEMS PVT LTD'),
(14809, '44:E0:8E', 'Cisco SPVTG'),
(14810, '44:E1:37', 'ARRIS Group, Inc.'),
(14811, '44:E4:9A', 'OMNITRONICS PTY LTD'),
(14812, '44:E4:D9', 'CISCO SYSTEMS, INC.'),
(14813, '44:E8:A5', 'Myreka Technologies Sdn. Bhd.'),
(14814, '44:E9:DD', 'SAGEMCOM SAS'),
(14815, '44:ED:57', 'Longicorn, inc.'),
(14816, '44:EE:30', 'Budelmann Elektronik GmbH'),
(14817, '44:F4:59', 'Samsung Electronics'),
(14818, '44:F4:77', 'Juniper Networks'),
(14819, '44:F8:49', 'Union Pacific Railroad'),
(14820, '44:FB:42', 'Apple'),
(14821, '48:02:2A', 'B-Link Electronic Limited'),
(14822, '48:03:62', 'DESAY ELECTRONICS(HUIZHOU)CO.,LTD'),
(14823, '48:0C:49', 'NAKAYO TELECOMMUNICATIONS,INC'),
(14824, '48:12:49', 'Luxcom Technologies Inc.'),
(14825, '48:13:F3', 'BBK Electronics Corp., Ltd.'),
(14826, '48:17:4C', 'MicroPower technologies'),
(14827, '48:18:42', 'Shanghai Winaas Co. Equipment Co. Ltd.'),
(14828, '48:1A:84', 'Pointer Telocation Ltd'),
(14829, '48:1B:D2', 'Intron Scientific co., ltd.'),
(14830, '48:26:E8', 'Tek-Air Systems, Inc.'),
(14831, '48:28:2F', 'ZTE Corporation'),
(14832, '48:2C:EA', 'Motorola Inc Business Light Radios'),
(14833, '48:33:DD', 'ZENNIO AVANCE Y TECNOLOGIA, S.L.'),
(14834, '48:34:3D', 'IEP GmbH'),
(14835, '48:3D:32', 'Syscor Controls &amp;amp; Automation'),
(14836, '48:43:7C', 'Apple'),
(14837, '48:44:87', 'Cisco SPVTG'),
(14838, '48:44:F7', 'Samsung Electronics Co., LTD'),
(14839, '48:46:F1', 'Uros Oy'),
(14840, '48:46:FB', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14841, '48:51:B7', 'Intel Corporate'),
(14842, '48:52:61', 'SOREEL'),
(14843, '48:54:15', 'NET RULES TECNOLOGIA EIRELI'),
(14844, '48:55:5F', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(14845, '48:57:DD', 'Facebook'),
(14846, '48:59:29', 'LG Electronics'),
(14847, '48:5A:3F', 'WISOL'),
(14848, '48:5A:B6', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14849, '48:5B:39', 'ASUSTek COMPUTER INC.'),
(14850, '48:5D:60', 'Azurewave Technologies, Inc.'),
(14851, '48:60:BC', 'Apple'),
(14852, '48:61:A3', 'Concern &quot;Axion&quot; JSC'),
(14853, '48:62:76', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14854, '48:6B:2C', 'BBK Electronics Corp., Ltd.,'),
(14855, '48:6B:91', 'Fleetwood Group Inc.'),
(14856, '48:6E:73', 'Pica8, Inc.'),
(14857, '48:6F:D2', 'StorSimple Inc'),
(14858, '48:71:19', 'SGB GROUP LTD.'),
(14859, '48:74:6E', 'Apple'),
(14860, '48:76:04', 'PRIVATE'),
(14861, '48:82:44', 'Life Fitness / Div. of Brunswick'),
(14862, '48:86:E8', 'Microsoft Corporation'),
(14863, '48:8E:42', 'DIGALOG GmbH'),
(14864, '48:91:53', 'Weinmann Ger&auml;te f&uuml;r Medizin GmbH + Co. KG'),
(14865, '48:91:F6', 'Shenzhen Reach software technology CO.,LTD'),
(14866, '48:9B:E2', 'SCI Innovations Ltd'),
(14867, '48:9D:18', 'Flashbay Limited'),
(14868, '48:9D:24', 'Research In Motion'),
(14869, '48:A2:2D', 'Shenzhen Huaxuchang Telecom Technology Co.,Ltd'),
(14870, '48:A2:B7', 'Kodofon JSC'),
(14871, '48:A6:D2', 'GJsun Optical Science and Tech Co.,Ltd.'),
(14872, '48:A9:D2', 'Wistron Neweb Corp.'),
(14873, '48:AA:5D', 'Store Electronic Systems'),
(14874, '48:B2:53', 'Marketaxess Corporation'),
(14875, '48:B5:A7', 'Glory Horse Industries Ltd.'),
(14876, '48:B8:DE', 'HOMEWINS TECHNOLOGY CO.,LTD.'),
(14877, '48:B9:77', 'PulseOn Oy'),
(14878, '48:B9:C2', 'Teletics Inc.'),
(14879, '48:BE:2D', 'Symanitron'),
(14880, '48:C0:93', 'Xirrus, Inc.'),
(14881, '48:C1:AC', 'PLANTRONICS, INC.'),
(14882, '48:C8:62', 'Simo Wireless,Inc.'),
(14883, '48:C8:B6', 'SysTec GmbH'),
(14884, '48:CB:6E', 'Cello Electronics (UK) Ltd'),
(14885, '48:D0:CF', 'Universal Electronics, Inc.'),
(14886, '48:D1:8E', 'Metis Communication Co.,Ltd'),
(14887, '48:D2:24', 'Liteon Technology Corporation'),
(14888, '48:D5:4C', 'Jeda Networks'),
(14889, '48:D7:05', 'Apple'),
(14890, '48:D7:FF', 'BLANKOM Antennentechnik GmbH'),
(14891, '48:D8:55', 'Telvent'),
(14892, '48:D8:FE', 'ClarIDy Solutions, Inc.'),
(14893, '48:DC:FB', 'Nokia Corporation'),
(14894, '48:DF:1C', 'Wuhan NEC Fibre Optic Communications industry Co. Ltd'),
(14895, '48:E1:AF', 'Vity'),
(14896, '48:E9:F1', 'Apple'),
(14897, '48:EA:63', 'Zhejiang Uniview Technologies Co., Ltd.'),
(14898, '48:EB:30', 'ETERNA TECHNOLOGY, INC.'),
(14899, '48:ED:80', 'daesung eltec'),
(14900, '48:EE:07', 'Silver Palm Technologies LLC'),
(14901, '48:EE:0C', 'D-Link International'),
(14902, '48:EE:86', 'UTStarcom (China) Co.,Ltd'),
(14903, '48:F2:30', 'Ubizcore Co.,LTD'),
(14904, '48:F3:17', 'PRIVATE'),
(14905, '48:F4:7D', 'TechVision Holding  Internation Limited'),
(14906, '48:F7:F1', 'Alcatel-Lucent'),
(14907, '48:F8:B3', 'Cisco-Linksys, LLC'),
(14908, '48:F8:E1', 'Alcatel Lucent WT'),
(14909, '48:F9:25', 'Maestronic'),
(14910, '48:FC:B8', 'Woodstream Corporation'),
(14911, '48:FE:EA', 'HOMA B.V.'),
(14912, '4C:00:82', 'Cisco'),
(14913, '4C:02:2E', 'CMR KOREA CO., LTD'),
(14914, '4C:02:89', 'LEX COMPUTECH CO., LTD'),
(14915, '4C:06:8A', 'Basler Electric Company'),
(14916, '4C:07:C9', 'COMPUTER OFFICE Co.,Ltd.'),
(14917, '4C:09:B4', 'zte corporation'),
(14918, '4C:09:D4', 'Arcadyan Technology Corporation'),
(14919, '4C:0B:3A', 'TCT Mobile Limited'),
(14920, '4C:0B:BE', 'Microsoft'),
(14921, '4C:0D:EE', 'JABIL CIRCUIT (SHANGHAI) LTD.'),
(14922, '4C:0F:6E', 'Hon Hai Precision Ind. Co.,Ltd.'),
(14923, '4C:0F:C7', 'Earda Electronics Co.,Ltd'),
(14924, '4C:11:BF', 'ZHEJIANG DAHUA TECHNOLOGY CO.,LTD.'),
(14925, '4C:14:80', 'NOREGON SYSTEMS, INC'),
(14926, '4C:14:A3', 'TCL Technoly Electronics (Huizhou) Co., Ltd.'),
(14927, '4C:16:F1', 'zte corporation'),
(14928, '4C:17:EB', 'SAGEMCOM'),
(14929, '4C:1A:3A', 'PRIMA Research And Production Enterprise Ltd.'),
(14930, '4C:1A:95', 'Novakon Co., Ltd.'),
(14931, '4C:1F:CC', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14932, '4C:21:D0', 'Sony Mobile Communications AB'),
(14933, '4C:22:58', 'cozybit, Inc.'),
(14934, '4C:25:78', 'Nokia Corporation'),
(14935, '4C:26:E7', 'Welgate Co., Ltd.'),
(14936, '4C:2C:80', 'Beijing Skyway Technologies Co.,Ltd'),
(14937, '4C:2C:83', 'Zhejiang KaNong Network Technology Co.,Ltd.'),
(14938, '4C:2F:9D', 'ICM Controls'),
(14939, '4C:30:89', 'Thales Transportation Systems GmbH'),
(14940, '4C:32:2D', 'TELEDATA NETWORKS'),
(14941, '4C:32:D9', 'M Rutty Holdings Pty. Ltd.'),
(14942, '4C:39:09', 'HPL Electric &amp; Power Private Limited'),
(14943, '4C:39:10', 'Newtek Electronics co., Ltd.'),
(14944, '4C:3B:74', 'VOGTEC(H.K.) Co., Ltd'),
(14945, '4C:3C:16', 'Samsung Electronics Co.,Ltd'),
(14946, '4C:48:DA', 'Beijing Autelan Technology Co.,Ltd'),
(14947, '4C:4B:68', 'Mobile Device, Inc.'),
(14948, '4C:4E:35', 'Cisco'),
(14949, '4C:54:27', 'Linepro Sp. z o.o.'),
(14950, '4C:54:99', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(14951, '4C:55:85', 'Hamilton Systems'),
(14952, '4C:55:B8', 'Turkcell Teknoloji'),
(14953, '4C:55:CC', 'ACKme Networks Pty Ltd'),
(14954, '4C:5D:CD', 'Oy Finnish Electric Vehicle Technologies Ltd'),
(14955, '4C:5E:0C', 'Routerboard.com'),
(14956, '4C:5F:D2', 'Alcatel-Lucent'),
(14957, '4C:60:D5', 'airPointe of New Hampshire'),
(14958, '4C:60:DE', 'NETGEAR'),
(14959, '4C:62:55', 'SANMINA-SCI SYSTEM DE MEXICO S.A. DE C.V.'),
(14960, '4C:63:EB', 'Application Solutions (Electronics and Vision) Ltd'),
(14961, '4C:64:D9', 'Guangdong Leawin Group Co., Ltd'),
(14962, '4C:6E:6E', 'Comnect Technology CO.,LTD'),
(14963, '4C:72:B9', 'Pegatron Corporation'),
(14964, '4C:73:67', 'Genius Bytes Software Solutions GmbH'),
(14965, '4C:73:A5', 'KOVE'),
(14966, '4C:74:03', 'Mundo Reader (bq)'),
(14967, '4C:76:25', 'Dell Inc.'),
(14968, '4C:77:4F', 'Embedded Wireless Labs'),
(14969, '4C:78:97', 'Arrowhead Alarm Products Ltd'),
(14970, '4C:79:BA', 'Intel Corporate'),
(14971, '4C:7C:5F', 'Apple'),
(14972, '4C:7F:62', 'Nokia Corporation'),
(14973, '4C:80:4F', 'Armstrong Monitoring Corp'),
(14974, '4C:80:93', 'Intel Corporate'),
(14975, '4C:82:CF', 'Echostar Technologies'),
(14976, '4C:83:DE', 'Cisco SPVTG'),
(14977, '4C:8B:30', 'Actiontec Electronics, Inc'),
(14978, '4C:8B:55', 'Grupo Digicon'),
(14979, '4C:8B:EF', 'Huawei Technologies Co., Ltd'),
(14980, '4C:8D:79', 'Apple'),
(14981, '4C:8F:A5', 'Jastec'),
(14982, '4C:96:14', 'Juniper Networks'),
(14983, '4C:98:EF', 'Zeo'),
(14984, '4C:9E:80', 'KYOKKO ELECTRIC Co., Ltd.'),
(14985, '4C:9E:E4', 'Hanyang Navicom Co.,Ltd.'),
(14986, '4C:9E:FF', 'ZyXEL Communications Corp'),
(14987, '4C:A5:15', 'Baikal Electronics JSC'),
(14988, '4C:A5:6D', 'Samsung Electronics Co.,Ltd'),
(14989, '4C:A7:4B', 'Alcatel Lucent'),
(14990, '4C:A9:28', 'Insensi'),
(14991, '4C:AA:16', 'AzureWave Technologies (Shanghai) Inc.'),
(14992, '4C:AB:33', 'KST technology'),
(14993, '4C:AC:0A', 'ZTE Corporation'),
(14994, '4C:B1:6C', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(14995, '4C:B1:99', 'Apple'),
(14996, '4C:B4:EA', 'HRD (S) PTE., LTD.'),
(14997, '4C:B8:1C', 'SAM Electronics GmbH'),
(14998, '4C:B9:C8', 'CONET CO., LTD.'),
(14999, '4C:BA:A3', 'Bison Electronics Inc.'),
(15000, '4C:BB:58', 'Chicony Electronics Co., Ltd.'),
(15001, '4C:BC:42', 'Shenzhen Hangsheng Electronics Co.,Ltd.'),
(15002, '4C:BC:A5', 'Samsung Electronics Co.,Ltd'),
(15003, '4C:C4:52', 'Shang Hai Tyd. Electon Technology Ltd.'),
(15004, '4C:C6:02', 'Radios, Inc.'),
(15005, '4C:C9:4F', 'Alcatel-Lucent'),
(15006, '4C:CA:53', 'Skyera, Inc.'),
(15007, '4C:CB:F5', 'zte corporation'),
(15008, '4C:CC:34', 'Motorola Solutions Inc.'),
(15009, '4C:D0:8A', 'HUMAX.CO.,LTD'),
(15010, '4C:D6:37', 'Qsono Electronics Co., Ltd'),
(15011, '4C:D7:B6', 'Helmer Scientific'),
(15012, '4C:D9:C4', 'Magneti Marelli Automotive Electronics (Guangzhou) Co. Ltd'),
(15013, '4C:DF:3D', 'TEAM ENGINEERS ADVANCE TECHNOLOGIES INDIA PVT LTD'),
(15014, '4C:E1:BB', 'Zhuhai HiFocus Technology Co., Ltd.'),
(15015, '4C:E2:F1', 'sclak srl'),
(15016, '4C:E6:76', 'Buffalo Inc.'),
(15017, '4C:E9:33', 'RailComm, LLC'),
(15018, '4C:EB:42', 'Intel Corporate'),
(15019, '4C:ED:DE', 'Askey Computer Corp'),
(15020, '4C:F0:2E', 'Vifa Denmark A/S'),
(15021, '4C:F2:BF', 'Cambridge Industries(Group) Co.,Ltd.'),
(15022, '4C:F4:5B', 'Blue Clover Devices'),
(15023, '4C:F5:A0', 'Scalable Network Technologies Inc'),
(15024, '4C:F7:37', 'SamJi Electronics Co., Ltd'),
(15025, '50:00:8C', 'Hong Kong Telecommunications (HKT) Limited'),
(15026, '50:01:BB', 'Samsung Electronics'),
(15027, '50:05:3D', 'CyWee Group Ltd'),
(15028, '50:06:04', 'Cisco'),
(15029, '50:0B:32', 'Foxda Technology Industrial(ShenZhen)Co.,LTD'),
(15030, '50:0E:6D', 'TrafficCast International'),
(15031, '50:11:EB', 'SilverNet Ltd'),
(15032, '50:14:B5', 'Richfit Information Technology Co., Ltd'),
(15033, '50:17:FF', 'Cisco'),
(15034, '50:1A:C5', 'Microsoft'),
(15035, '50:1C:BF', 'Cisco'),
(15036, '50:20:6B', 'Emerson Climate Technologies Transportation Solutions'),
(15037, '50:22:67', 'PixeLINK'),
(15038, '50:25:2B', 'Nethra Imaging Incorporated'),
(15039, '50:26:90', 'Fujitsu Limited'),
(15040, '50:27:C7', 'TECHNART Co.,Ltd'),
(15041, '50:29:4D', 'NANJING IOT SENSOR TECHNOLOGY CO,LTD'),
(15042, '50:2A:7E', 'Smart electronic GmbH'),
(15043, '50:2A:8B', 'Telekom Research and Development Sdn Bhd'),
(15044, '50:2D:1D', 'Nokia Corporation'),
(15045, '50:2D:A2', 'Intel Corporate'),
(15046, '50:2D:F4', 'Phytec Messtechnik GmbH'),
(15047, '50:2E:5C', 'HTC Corporation'),
(15048, '50:2E:CE', 'Asahi Electronics Co.,Ltd'),
(15049, '50:32:75', 'Samsung Electronics Co.,Ltd'),
(15050, '50:39:55', 'Cisco SPVTG'),
(15051, '50:3C:C4', 'Lenovo Mobile Communication Technology Ltd.'),
(15052, '50:3D:E5', 'CISCO SYSTEMS, INC.'),
(15053, '50:3F:56', 'Syncmold Enterprise Corp'),
(15054, '50:46:5D', 'ASUSTek COMPUTER INC.'),
(15055, '50:48:EB', 'BEIJING HAIHEJINSHENG NETWORK TECHNOLOGY CO. LTD.'),
(15056, '50:4A:5E', 'Masimo Corporation'),
(15057, '50:4A:6E', 'NETGEAR INC.,'),
(15058, '50:4F:94', 'Loxone Electronics GmbH'),
(15059, '50:50:2A', 'Egardia'),
(15060, '50:50:65', 'TAKT Corporation'),
(15061, '50:55:27', 'LG Electronics'),
(15062, '50:56:63', 'Texas Instruments'),
(15063, '50:56:A8', 'Jolla Ltd'),
(15064, '50:56:BF', 'Samsung Electronics Co.,LTD'),
(15065, '50:57:A8', 'CISCO SYSTEMS, INC.'),
(15066, '50:58:00', 'WyTec International, Inc.'),
(15067, '50:5A:C6', 'GUANGDONG SUPER TELECOM CO.,LTD.'),
(15068, '50:60:28', 'Xirrus Inc.'),
(15069, '50:61:84', 'Avaya, Inc'),
(15070, '50:61:D6', 'Indu-Sol GmbH'),
(15071, '50:63:13', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15072, '50:64:41', 'Greenlee'),
(15073, '50:65:F3', 'Hewlett Packard'),
(15074, '50:67:87', 'iTellus'),
(15075, '50:67:AE', 'Cisco'),
(15076, '50:67:F0', 'ZyXEL Communications Corporation'),
(15077, '50:6F:9A', 'Wi-Fi Alliance'),
(15078, '50:70:E5', 'He Shan World Fair Electronics Technology Limited'),
(15079, '50:72:24', 'Texas Instruments'),
(15080, '50:72:4D', 'BEG Brueck Electronic GmbH'),
(15081, '50:76:91', 'Tekpea, Inc.'),
(15082, '50:76:A6', 'Ecil Informatica Ind. Com. Ltda'),
(15083, '50:79:5B', 'Interexport Telecomunicaciones S.A.'),
(15084, '50:7D:02', 'BIODIT'),
(15085, '50:7E:5D', 'Arcadyan Technology Corporation'),
(15086, '50:85:69', 'Samsung Electronics Co.,LTD'),
(15087, '50:87:89', 'Cisco'),
(15088, '50:87:B8', 'Nuvyyo Inc'),
(15089, '50:8A:42', 'Uptmate Technology Co., LTD'),
(15090, '50:8A:CB', 'SHENZHEN MAXMADE TECHNOLOGY CO., LTD.'),
(15091, '50:8C:77', 'DIRMEIER Schanktechnik GmbH &amp;Co KG'),
(15092, '50:8D:6F', 'CHAHOO Limited'),
(15093, '50:93:4F', 'Gradual Tecnologia Ltda.'),
(15094, '50:97:72', 'Westinghouse Digital'),
(15095, '50:98:71', 'Inventum Technologies Private Limited'),
(15096, '50:9F:27', 'Huawei Technologies Co., Ltd'),
(15097, '50:A0:54', 'Actineon'),
(15098, '50:A0:BF', 'Alba Fiber Systems Inc.'),
(15099, '50:A4:C8', 'Samsung Electronics Co.,Ltd'),
(15100, '50:A6:E3', 'David Clark Company'),
(15101, '50:A7:15', 'Aboundi, Inc.'),
(15102, '50:A7:33', 'Ruckus Wireless'),
(15103, '50:AB:BF', 'Hoseo Telecom'),
(15104, '50:AD:D5', 'Dynalec Corporation'),
(15105, '50:AF:73', 'Shenzhen Bitland Information Technology Co., Ltd.'),
(15106, '50:B6:95', 'Micropoint Biotechnologies,Inc.'),
(15107, '50:B7:C3', 'Samsung Electronics CO., LTD'),
(15108, '50:B8:88', 'wi2be Tecnologia S/A'),
(15109, '50:B8:A2', 'ImTech Technologies LLC,'),
(15110, '50:BD:5F', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(15111, '50:C0:06', 'Carmanah Signs'),
(15112, '50:C2:71', 'SECURETECH INC'),
(15113, '50:C5:8D', 'Juniper Networks'),
(15114, '50:C7:BF', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(15115, '50:C9:71', 'GN Netcom A/S'),
(15116, '50:C9:A0', 'SKIPPER Electronics AS'),
(15117, '50:CC:F8', 'Samsung Electro Mechanics'),
(15118, '50:CD:32', 'NanJing Chaoran Science &amp; Technology Co.,Ltd.'),
(15119, '50:CE:75', 'Measy Electronics Ltd'),
(15120, '50:D2:74', 'Steffes Corporation'),
(15121, '50:D6:D7', 'Takahata Precision'),
(15122, '50:E0:C7', 'TurControlSystme AG'),
(15123, '50:E1:4A', 'PRIVATE'),
(15124, '50:E5:49', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(15125, '50:EA:D6', 'Apple'),
(15126, '50:EB:1A', 'Brocade Communications Systems, Inc.'),
(15127, '50:ED:78', 'Changzhou Yongse Infotech Co.,Ltd'),
(15128, '50:ED:94', 'Egatel SL'),
(15129, '50:F0:03', 'Open Stack, Inc.'),
(15130, '50:F4:3C', 'Leeo Inc'),
(15131, '50:F5:20', 'Samsung Electronics Co.,Ltd'),
(15132, '50:F6:1A', 'Kunshan JADE Technologies co., Ltd.'),
(15133, '50:FA:AB', 'L-tek d.o.o.'),
(15134, '50:FC:30', 'Treehouse Labs'),
(15135, '50:FC:9F', 'Samsung Electronics Co.,Ltd'),
(15136, '50:FE:F2', 'Sify Technologies Ltd'),
(15137, '54:03:F5', 'EBN Technology Corp.'),
(15138, '54:04:96', 'Gigawave LTD'),
(15139, '54:04:A6', 'ASUSTek COMPUTER INC.'),
(15140, '54:05:36', 'Vivago Oy'),
(15141, '54:05:5F', 'Alcatel Lucent'),
(15142, '54:09:8D', 'deister electronic GmbH'),
(15143, '54:11:2F', 'Sulzer Pump Solutions Finland Oy'),
(15144, '54:11:5F', 'Atamo Pty Ltd'),
(15145, '54:1B:5D', 'Techno-Innov'),
(15146, '54:1D:FB', 'Freestyle Energy Ltd'),
(15147, '54:1F:D5', 'Advantage Electronics'),
(15148, '54:20:18', 'Tely Labs'),
(15149, '54:21:60', 'Resolution Products'),
(15150, '54:22:F8', 'zte corporation'),
(15151, '54:26:96', 'Apple'),
(15152, '54:27:1E', 'AzureWave Technonloies, Inc.'),
(15153, '54:2A:9C', 'LSY Defense, LLC.'),
(15154, '54:2A:A2', 'Alpha Networks Inc.'),
(15155, '54:2C:EA', 'PROTECTRON'),
(15156, '54:2F:89', 'Euclid Laboratories, Inc.'),
(15157, '54:31:31', 'Raster Vision Ltd'),
(15158, '54:35:30', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15159, '54:35:DF', 'Symeo GmbH'),
(15160, '54:36:9B', 'In one network technology (Beijing) Co., Ltd.'),
(15161, '54:39:68', 'Edgewater Networks Inc'),
(15162, '54:39:DF', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15163, '54:3D:37', 'Ruckus Wireless'),
(15164, '54:42:49', 'Sony Corporation'),
(15165, '54:44:08', 'Nokia Corporation'),
(15166, '54:46:6B', 'Shenzhen CZTIC Electronic Technology Co., Ltd'),
(15167, '54:4A:00', 'Cisco'),
(15168, '54:4A:05', 'wenglor sensoric gmbh'),
(15169, '54:4A:16', 'Texas Instruments'),
(15170, '54:51:46', 'AMG Systems Ltd.'),
(15171, '54:53:ED', 'Sony Corporation'),
(15172, '54:54:14', 'Digital RF Corea, Inc'),
(15173, '54:5E:BD', 'NL Technologies'),
(15174, '54:5F:A9', 'Teracom Limited'),
(15175, '54:61:EA', 'Zaplox AB'),
(15176, '54:72:4F', 'Apple'),
(15177, '54:73:98', 'Toyo Electronics Corporation'),
(15178, '54:74:E6', 'Webtech Wireless'),
(15179, '54:75:D0', 'CISCO SYSTEMS, INC.'),
(15180, '54:78:1A', 'Cisco'),
(15181, '54:79:75', 'Nokia Corporation'),
(15182, '54:7C:69', 'Cisco'),
(15183, '54:7F:54', 'INGENICO'),
(15184, '54:7F:A8', 'TELCO systems, s.r.o.'),
(15185, '54:7F:EE', 'CISCO SYSTEMS, INC.'),
(15186, '54:81:AD', 'Eagle Research Corporation'),
(15187, '54:84:7B', 'Digital Devices GmbH'),
(15188, '54:88:0E', 'Samsung Electro Mechanics co., LTD.'),
(15189, '54:89:22', 'Zelfy Inc'),
(15190, '54:89:98', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15191, '54:92:BE', 'Samsung Electronics Co.,Ltd'),
(15192, '54:93:59', 'SHENZHEN TWOWING TECHNOLOGIES CO.,LTD.'),
(15193, '54:94:78', 'Silvershore Technology Partners'),
(15194, '54:9A:16', 'Uzushio Electric Co.,Ltd.'),
(15195, '54:9B:12', 'Samsung Electronics'),
(15196, '54:9D:85', 'EnerAccess inc'),
(15197, '54:9F:13', 'Apple'),
(15198, '54:9F:35', 'Dell Inc.'),
(15199, '54:A0:4F', 't-mac Technologies Ltd'),
(15200, '54:A0:50', 'ASUSTek COMPUTER INC.'),
(15201, '54:A3:1B', 'Shenzhen Linkworld Technology Co,.LTD'),
(15202, '54:A5:1B', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(15203, '54:A5:4B', 'NSC Communications Siberia Ltd'),
(15204, '54:A6:19', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(15205, '54:A9:D4', 'Minibar Systems'),
(15206, '54:AE:27', 'Apple'),
(15207, '54:B6:20', 'SUHDOL E&amp;C Co.Ltd.'),
(15208, '54:B7:53', 'Hunan Fenghui Yinjia Science And Technology Co.,Ltd'),
(15209, '54:BE:F7', 'PEGATRON CORPORATION'),
(15210, '54:C8:0F', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(15211, '54:CD:A7', 'Fujian Shenzhou Electronic Co.,Ltd'),
(15212, '54:CD:EE', 'ShenZhen Apexis Electronic Co.,Ltd'),
(15213, '54:D0:ED', 'AXIM Communications'),
(15214, '54:D1:63', 'MAX-TECH,INC'),
(15215, '54:D1:B0', 'Universal Laser Systems, Inc'),
(15216, '54:D4:6F', 'Cisco SPVTG'),
(15217, '54:DF:00', 'Ulterius Technologies, LLC'),
(15218, '54:DF:63', 'Intrakey technologies GmbH'),
(15219, '54:E0:32', 'Juniper Networks'),
(15220, '54:E2:E0', 'Pace plc'),
(15221, '54:E3:B0', 'JVL Industri Elektronik'),
(15222, '54:E4:3A', 'Apple, Inc.'),
(15223, '54:E4:BD', 'FN-LINK TECHNOLOGY LIMITED'),
(15224, '54:E6:3F', 'ShenZhen LingKeWeiEr Technology Co., Ltd.'),
(15225, '54:E6:FC', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(15226, '54:EA:A8', 'Apple, Inc.'),
(15227, '54:EE:75', 'Wistron InfoComm(Kunshan)Co.,Ltd.'),
(15228, '54:EF:92', 'Shenzhen Elink Technology Co., LTD'),
(15229, '54:F5:B6', 'ORIENTAL PACIFIC INTERNATIONAL LIMITED'),
(15230, '54:F6:66', 'Berthold Technologies GmbH and Co.KG'),
(15231, '54:F8:76', 'ABB AG'),
(15232, '54:FA:3E', 'Samsung Electronics Co.,LTD'),
(15233, '54:FB:58', 'WISEWARE, Lda'),
(15234, '54:FD:BF', 'Scheidt &amp; Bachmann GmbH'),
(15235, '54:FF:CF', 'Mopria Alliance'),
(15236, '58:05:28', 'LABRIS NETWORKS'),
(15237, '58:05:56', 'Elettronica GF S.r.L.'),
(15238, '58:08:FA', 'Fiber Optic &amp; telecommunication INC.'),
(15239, '58:09:43', 'PRIVATE'),
(15240, '58:09:E5', 'Kivic Inc.'),
(15241, '58:0A:20', 'Cisco'),
(15242, '58:10:8C', 'Intelbras'),
(15243, '58:12:43', 'AcSiP Technology Corp.'),
(15244, '58:16:26', 'Avaya, Inc'),
(15245, '58:17:0C', 'Sony Ericsson Mobile Communications AB'),
(15246, '58:1C:BD', 'Affinegy'),
(15247, '58:1D:91', 'Advanced Mobile Telecom co.,ltd.'),
(15248, '58:1F:28', 'Huawei Technologies Co., Ltd'),
(15249, '58:1F:67', 'Open-m technology limited'),
(15250, '58:1F:AA', 'Apple'),
(15251, '58:1F:EF', 'Tuttnaer LTD'),
(15252, '58:21:36', 'KMB systems, s.r.o.'),
(15253, '58:23:8C', 'Technicolor CH USA'),
(15254, '58:2E:FE', 'Lighting Science Group'),
(15255, '58:2F:42', 'Universal Electric Corporation'),
(15256, '58:34:3B', 'Glovast Technology Ltd.'),
(15257, '58:35:D9', 'CISCO SYSTEMS, INC.'),
(15258, '58:3C:C6', 'Omneality Ltd.'),
(15259, '58:42:E4', 'Sigma International General Medical Apparatus, LLC.'),
(15260, '58:46:8F', 'Koncar Electronics and Informatics'),
(15261, '58:46:E1', 'Baxter Healthcare'),
(15262, '58:47:04', ' Shenzhen Webridge Technology Co.,Ltd'),
(15263, '58:48:C0', 'COFLEC'),
(15264, '58:49:3B', 'Palo Alto Networks'),
(15265, '58:49:BA', 'Chitai Electronic Corp.'),
(15266, '58:4C:19', 'Chongqing Guohong Technology Development Company Limited'),
(15267, '58:4C:EE', 'Digital One Technologies, Limited'),
(15268, '58:50:76', 'Linear Equipamentos Eletronicos SA'),
(15269, '58:50:AB', 'TLS Corporation'),
(15270, '58:50:E6', 'Best Buy Corporation'),
(15271, '58:55:CA', 'Apple'),
(15272, '58:56:E8', 'ARRIS Group, Inc.'),
(15273, '58:57:0D', 'Danfoss Solar Inverters'),
(15274, '58:63:9A', 'TPL SYSTEMES'),
(15275, '58:65:E6', 'INFOMARK CO., LTD.'),
(15276, '58:66:BA', 'Hangzhou H3C Technologies Co., Limited'),
(15277, '58:67:1A', 'BARNES&amp;NOBLE.COM'),
(15278, '58:67:7F', 'Clare Controls Inc.'),
(15279, '58:69:6C', 'Fujian Ruijie Networks co, ltd'),
(15280, '58:69:F9', 'Fusion Transactive Ltd.'),
(15281, '58:6A:B1', 'Hangzhou H3C Technologies Co., Limited'),
(15282, '58:6D:8F', 'Cisco-Linksys, LLC'),
(15283, '58:6E:D6', 'PRIVATE'),
(15284, '58:75:21', 'CJSC RTSoft'),
(15285, '58:76:75', 'Beijing ECHO Technologies Co.,Ltd'),
(15286, '58:76:C5', 'DIGI I\'S LTD'),
(15287, '58:7A:4D', 'Stonesoft Corporation'),
(15288, '58:7B:E9', 'AirPro Technology India Pvt. Ltd'),
(15289, '58:7E:61', 'Hisense Electric Co., Ltd'),
(15290, '58:7F:B7', 'SONAR INDUSTRIAL CO., LTD.'),
(15291, '58:7F:C8', 'S2M'),
(15292, '58:84:E4', 'IP500 Alliance e.V.'),
(15293, '58:85:6E', 'QSC AG'),
(15294, '58:87:4C', 'LITE-ON CLEAN ENERGY TECHNOLOGY CORP.'),
(15295, '58:87:E2', 'Shenzhen Coship Electronics Co., Ltd.'),
(15296, '58:8D:09', 'CISCO SYSTEMS, INC.'),
(15297, '58:91:CF', 'Intel Corporate'),
(15298, '58:92:0D', 'Kinetic Avionics Limited'),
(15299, '58:93:96', 'Ruckus Wireless'),
(15300, '58:94:6B', 'Intel Corporate'),
(15301, '58:94:CF', 'Vertex Standard LMR, Inc.'),
(15302, '58:97:1E', 'Cisco'),
(15303, '58:98:35', 'Technicolor'),
(15304, '58:98:6F', 'Revolution Display'),
(15305, '58:9B:0B', 'Shineway Technologies, Inc.'),
(15306, '58:9C:FC', 'FreeBSD Foundation'),
(15307, '58:A2:B5', 'LG Electronics'),
(15308, '58:A7:6F', 'iD corporation'),
(15309, '58:A8:39', 'Intel Corporate'),
(15310, '58:B0:35', 'Apple'),
(15311, '58:B0:D4', 'ZuniData Systems Inc.'),
(15312, '58:B9:61', 'SOLEM Electronique'),
(15313, '58:B9:E1', 'Crystalfontz America, Inc.'),
(15314, '58:BC:27', 'CISCO SYSTEMS, INC.'),
(15315, '58:BD:A3', 'Nintendo Co., Ltd.'),
(15316, '58:BD:F9', 'Sigrand'),
(15317, '58:BF:EA', 'CISCO SYSTEMS, INC.'),
(15318, '58:C2:32', 'NEC Corporation'),
(15319, '58:C3:8B', 'Samsung Electronics'),
(15320, '58:CF:4B', 'Lufkin Industries'),
(15321, '58:D0:71', 'BW Broadcast'),
(15322, '58:D0:8F', 'IEEE 1904.1 Working Group'),
(15323, '58:D6:D3', 'Dairy Cheq Inc'),
(15324, '58:DB:8D', 'Fast Co., Ltd.'),
(15325, '58:E0:2C', 'Micro Technic A/S'),
(15326, '58:E3:26', 'Compass Technologies Inc.'),
(15327, '58:E4:76', 'CENTRON COMMUNICATIONS TECHNOLOGIES FUJIAN CO.,LTD'),
(15328, '58:E6:36', 'EVRsafe Technologies'),
(15329, '58:E7:47', 'Deltanet AG'),
(15330, '58:E8:08', 'AUTONICS CORPORATION'),
(15331, '58:EB:14', 'Proteus Digital Health'),
(15332, '58:EC:E1', 'Newport Corporation'),
(15333, '58:EE:CE', 'Icon Time Systems'),
(15334, '58:F3:87', 'HCCP'),
(15335, '58:F3:9C', 'Cisco'),
(15336, '58:F6:7B', 'Xia Men UnionCore Technology LTD.'),
(15337, '58:F6:BF', 'Kyoto University'),
(15338, '58:F9:8E', 'SECUDOS GmbH'),
(15339, '58:FC:DB', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(15340, '58:FD:20', 'Bravida Sakerhet AB'),
(15341, '5C:02:6A', 'Applied Vision Corporation'),
(15342, '5C:07:6F', 'Thought Creator'),
(15343, '5C:0A:5B', 'SAMSUNG ELECTRO-MECHANICS CO., LTD.'),
(15344, '5C:0C:BB', 'CELIZION Inc.'),
(15345, '5C:0E:8B', 'Zebra Technologies Inc'),
(15346, '5C:11:93', 'Seal One AG'),
(15347, '5C:14:37', 'Thyssenkrupp Aufzugswerke GmbH'),
(15348, '5C:15:15', 'ADVAN'),
(15349, '5C:15:E1', 'AIDC TECHNOLOGY (S) PTE LTD'),
(15350, '5C:16:C7', 'Big Switch Networks'),
(15351, '5C:17:37', 'I-View Now, LLC.'),
(15352, '5C:17:D3', 'LGE'),
(15353, '5C:18:B5', 'Talon Communications'),
(15354, '5C:20:D0', 'Asoni Communication Co., Ltd.'),
(15355, '5C:22:C4', 'DAE EUN ELETRONICS CO., LTD'),
(15356, '5C:24:79', 'Baltech AG'),
(15357, '5C:25:4C', 'Avire Global Pte Ltd'),
(15358, '5C:26:0A', 'Dell Inc.'),
(15359, '5C:2A:EF', 'Open Access Pty Ltd'),
(15360, '5C:2B:F5', 'Vivint'),
(15361, '5C:2E:59', 'Samsung Electronics Co.,Ltd'),
(15362, '5C:2E:D2', 'ABC(XiSheng) Electronics Co.,Ltd'),
(15363, '5C:31:3E', 'Texas Instruments'),
(15364, '5C:33:27', 'Spazio Italia srl'),
(15365, '5C:33:5C', 'Swissphone Telecom AG'),
(15366, '5C:33:8E', 'Alpha Networkc Inc.'),
(15367, '5C:35:3B', 'Compal Broadband Networks Inc.'),
(15368, '5C:35:DA', 'There Corporation Oy'),
(15369, '5C:36:B8', 'TCL King Electrical Appliances (Huizhou) Ltd.'),
(15370, '5C:38:E0', 'Shanghai Super Electronics Technology Co.,LTD'),
(15371, '5C:3B:35', 'Gehirn Inc.'),
(15372, '5C:3C:27', 'Samsung Electronics Co.,Ltd'),
(15373, '5C:40:58', 'Jefferson Audio Video Systems, Inc.'),
(15374, '5C:41:E7', 'Wiatec International Ltd.'),
(15375, '5C:43:D2', 'HAZEMEYER'),
(15376, '5C:4A:26', 'Enguity Technology Corp'),
(15377, '5C:4C:A9', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(15378, '5C:50:15', 'CISCO SYSTEMS, INC.'),
(15379, '5C:51:4F', 'Intel Corporate'),
(15380, '5C:56:ED', '3pleplay Electronics Private Limited'),
(15381, '5C:57:1A', 'ARRIS Group, Inc.'),
(15382, '5C:57:C8', 'Nokia Corporation'),
(15383, '5C:59:48', 'Apple'),
(15384, '5C:5B:35', 'Mist Systems, Inc.'),
(15385, '5C:5B:C2', 'YIK Corporation'),
(15386, '5C:5E:AB', 'Juniper Networks'),
(15387, '5C:63:BF', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(15388, '5C:69:84', 'NUVICO'),
(15389, '5C:6A:7D', 'KENTKART EGE ELEKTRONIK SAN. VE TIC. LTD. STI.'),
(15390, '5C:6B:32', 'Texas Instruments'),
(15391, '5C:6B:4F', 'PRIVATE'),
(15392, '5C:6D:20', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15393, '5C:6F:4F', 'S.A. SISTEL'),
(15394, '5C:77:57', 'Haivision Network Video'),
(15395, '5C:7D:5E', 'Huawei Technologies Co., Ltd'),
(15396, '5C:84:86', 'Brightsource Industries Israel LTD'),
(15397, '5C:86:4A', 'Secret Labs LLC'),
(15398, '5C:87:78', 'Cybertelbridge co.,ltd'),
(15399, '5C:89:9A', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(15400, '5C:89:D4', 'Beijing Banner Electric Co.,Ltd'),
(15401, '5C:8A:38', 'Hewlett Packard'),
(15402, '5C:8D:4E', 'Apple'),
(15403, '5C:8F:E0', 'ARRIS Group, Inc.'),
(15404, '5C:93:A2', 'Liteon Technology Corporation'),
(15405, '5C:95:AE', 'Apple'),
(15406, '5C:96:6A', 'RTNET'),
(15407, '5C:96:9D', 'Apple'),
(15408, '5C:97:F3', 'Apple'),
(15409, '5C:9A:D8', 'Fujitsu Limited'),
(15410, '5C:A3:9D', 'SAMSUNG ELECTRO-MECHANICS CO., LTD.'),
(15411, '5C:A3:EB', 'Lokel s.r.o.'),
(15412, '5C:A4:8A', 'Cisco'),
(15413, '5C:AA:FD', 'Sonos, Inc.'),
(15414, '5C:AC:4C', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15415, '5C:B5:24', 'Sony Ericsson Mobile Communications AB'),
(15416, '5C:B6:CC', 'NovaComm Technologies Inc.'),
(15417, '5C:B8:CB', 'Allis Communications'),
(15418, '5C:BD:9E', 'HONGKONG MIRACLE EAGLE TECHNOLOGY(GROUP) LIMITED'),
(15419, '5C:C2:13', 'Fr. Sauter AG'),
(15420, '5C:C5:D4', 'Intel Corporate'),
(15421, '5C:C6:D0', 'Skyworth Digital technology(shenzhen)co.ltd.'),
(15422, '5C:C9:D3', 'PALLADIUM ENERGY ELETRONICA DA AMAZONIA LTDA'),
(15423, '5C:CA:32', 'Theben AG'),
(15424, '5C:CC:FF', 'Techroutes Network Pvt Ltd'),
(15425, '5C:CE:AD', 'CDYNE Corporation'),
(15426, '5C:D1:35', 'Xtreme Power Systems'),
(15427, '5C:D2:E4', 'Intel Corporate'),
(15428, '5C:D4:1B', 'UCZOON Technology Co., LTD'),
(15429, '5C:D4:AB', 'Zektor'),
(15430, '5C:D6:1F', 'Qardio, Inc'),
(15431, '5C:D9:98', 'D-Link Corporation'),
(15432, '5C:DA:D4', 'Murata Manufacturing Co., Ltd.'),
(15433, '5C:DD:70', 'Hangzhou H3C Technologies Co., Limited'),
(15434, '5C:E0:C5', 'Intel Corporate'),
(15435, '5C:E0:CA', 'FeiTian United (Beijing) System Technology Co., Ltd.'),
(15436, '5C:E0:F6', 'NIC.br- Nucleo de Informacao e Coordenacao do Ponto BR'),
(15437, '5C:E2:23', 'Delphin Technology AG'),
(15438, '5C:E2:86', 'Nortel Networks'),
(15439, '5C:E2:F4', 'AcSiP Technology Corp.'),
(15440, '5C:E7:BF', 'New Singularity International Technical Development Co.,Ltd'),
(15441, '5C:E8:EB', 'Samsung Electronics'),
(15442, '5C:EB:4E', 'R. STAHL HMI Systems GmbH'),
(15443, '5C:EE:79', 'Global Digitech Co LTD'),
(15444, '5C:F2:07', 'Speco Technologies'),
(15445, '5C:F3:70', 'CC&amp;C Technologies, Inc'),
(15446, '5C:F3:FC', 'IBM Corp'),
(15447, '5C:F4:AB', 'ZyXEL Communications Corp'),
(15448, '5C:F5:0D', 'Institute of microelectronic applications'),
(15449, '5C:F5:DA', 'Apple'),
(15450, '5C:F6:DC', 'Samsung Electronics Co.,LTD'),
(15451, '5C:F7:C3', 'SYNTECH (HK) TECHNOLOGY LIMITED'),
(15452, '5C:F8:A1', 'Murata Manufactuaring Co.,Ltd.'),
(15453, '5C:F9:38', 'Apple, Inc'),
(15454, '5C:F9:6A', 'Huawei Technologies Co., Ltd'),
(15455, '5C:F9:DD', 'Dell Inc'),
(15456, '5C:F9:F0', 'Atomos Engineering P/L'),
(15457, '5C:FC:66', 'Cisco'),
(15458, '5C:FF:35', 'Wistron Corporation'),
(15459, '5C:FF:FF', 'Shenzhen Kezhonglong Optoelectronic Technology Co., Ltd'),
(15460, '60:02:92', 'PEGATRON CORPORATION'),
(15461, '60:02:B4', 'Wistron NeWeb Corp.'),
(15462, '60:03:08', 'Apple'),
(15463, '60:03:47', 'Billion Electric Co. Ltd.'),
(15464, '60:04:17', 'POSBANK CO.,LTD'),
(15465, '60:0F:77', 'SilverPlus, Inc'),
(15466, '60:11:99', 'Siama Systems Inc'),
(15467, '60:12:83', 'Soluciones Tecnologicas para la Salud y el Bienestar SA'),
(15468, '60:12:8B', 'CANON INC.'),
(15469, '60:15:C7', 'IdaTech'),
(15470, '60:19:0C', 'RRAMAC'),
(15471, '60:19:29', 'VOLTRONIC POWER TECHNOLOGY(SHENZHEN) CORP.'),
(15472, '60:1D:0F', 'Midnite Solar'),
(15473, '60:1E:02', 'EltexAlatau'),
(15474, '60:21:03', 'STCUBE.INC'),
(15475, '60:21:C0', 'Murata Manufactuaring Co.,Ltd.'),
(15476, '60:24:C1', 'Jiangsu Zhongxun Electronic Technology Co., Ltd'),
(15477, '60:2A:54', 'CardioTek B.V.'),
(15478, '60:2A:D0', 'Cisco SPVTG'),
(15479, '60:32:F0', 'Mplus technology'),
(15480, '60:33:4B', 'Apple'),
(15481, '60:35:53', 'Buwon Technology'),
(15482, '60:36:96', 'The Sapling Company'),
(15483, '60:36:DD', 'Intel Corporate'),
(15484, '60:38:0E', 'Alps Electric Co.,'),
(15485, '60:39:1F', 'ABB Ltd'),
(15486, '60:3F:C5', 'COX CO., LTD'),
(15487, '60:44:F5', 'Easy Digital Ltd.'),
(15488, '60:45:5E', 'Liptel s.r.o.'),
(15489, '60:45:BD', 'Microsoft'),
(15490, '60:46:16', 'XIAMEN VANN INTELLIGENT CO., LTD'),
(15491, '60:47:D4', 'FORICS Electronic Technology Co., Ltd.'),
(15492, '60:48:26', 'Newbridge Technologies Int. Ltd.'),
(15493, '60:4A:1C', 'SUYIN Corporation'),
(15494, '60:50:C1', 'Kinetek Sports'),
(15495, '60:51:2C', 'TCT mobile limited'),
(15496, '60:52:D0', 'FACTS Engineering'),
(15497, '60:54:64', 'Eyedro Green Solutions Inc.'),
(15498, '60:57:18', 'Intel Corporate'),
(15499, '60:5B:B4', 'AzureWave Technologies, Inc.'),
(15500, '60:60:1F', 'SZ DJI TECHNOLOGY CO.,LTD'),
(15501, '60:63:FD', 'Transcend Communication Beijing Co.,Ltd.'),
(15502, '60:64:A1', 'RADiflow Ltd.'),
(15503, '60:67:20', 'Intel Corporate'),
(15504, '60:69:44', 'Apple, Inc'),
(15505, '60:69:9B', 'isepos GmbH'),
(15506, '60:6B:BD', 'Samsung Electronics Co., LTD'),
(15507, '60:6C:66', 'Intel Corporate'),
(15508, '60:73:5C', 'Cisco'),
(15509, '60:74:8D', 'Atmaca Elektronik'),
(15510, '60:76:88', 'Velodyne'),
(15511, '60:77:E2', 'Samsung Electronics Co.,Ltd'),
(15512, '60:81:2B', 'Custom Control Concepts'),
(15513, '60:81:F9', 'Helium Systems, Inc'),
(15514, '60:83:B2', 'GkWare e.K.'),
(15515, '60:84:3B', 'Soladigm, Inc.'),
(15516, '60:86:45', 'Avery Weigh-Tronix, LLC'),
(15517, '60:89:3C', 'Thermo Fisher Scientific P.O.A.'),
(15518, '60:89:B1', 'Key Digital Systems'),
(15519, '60:89:B7', 'KAEL M&Uuml;HENDÄ°SLÄ°K ELEKTRONÄ°K TÄ°CARET SANAYÄ° LÄ°MÄ°TED ÅÄ°RKETÄ°'),
(15520, '60:8C:2B', 'Hanson Technology'),
(15521, '60:8D:17', 'Sentrus Government Systems Division, Inc'),
(15522, '60:8F:5C', 'Samsung Electronics Co.,Ltd'),
(15523, '60:90:84', 'DSSD Inc'),
(15524, '60:92:17', 'Apple'),
(15525, '60:96:20', 'PRIVATE'),
(15526, '60:99:D1', 'Vuzix / Lenovo'),
(15527, '60:9A:A4', 'GVI SECURITY INC.'),
(15528, '60:9E:64', 'Vivonic GmbH'),
(15529, '60:9F:9D', 'CloudSwitch'),
(15530, '60:A1:0A', 'Samsung Electronics Co.,Ltd'),
(15531, '60:A4:4C', 'ASUSTek COMPUTER INC.'),
(15532, '60:A8:FE', 'Nokia Solutions and Networks'),
(15533, '60:A9:B0', 'Merchandising Technologies, Inc'),
(15534, '60:AF:6D', 'Samsung Electronics Co.,Ltd'),
(15535, '60:B1:85', 'ATH system'),
(15536, '60:B3:C4', 'Elber Srl'),
(15537, '60:B6:06', 'Phorus'),
(15538, '60:B6:17', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(15539, '60:B9:33', 'Deutron Electronics Corp.'),
(15540, '60:B9:82', 'RO.VE.R. Laboratories S.p.A.'),
(15541, '60:BB:0C', 'Beijing HuaqinWorld Technology Co,Ltd'),
(15542, '60:BC:4C', 'EWM Hightec Welding GmbH'),
(15543, '60:BD:91', 'Move Innovation'),
(15544, '60:BE:B5', 'Motorola Mobility LLC'),
(15545, '60:C1:CB', 'Fujian Great Power PLC Equipment Co.,Ltd'),
(15546, '60:C3:97', '2Wire Inc'),
(15547, '60:C5:47', 'Apple'),
(15548, '60:C5:A8', 'Beijing LT Honway Technology Co.,Ltd'),
(15549, '60:C7:98', 'Verifone, Inc.'),
(15550, '60:C9:80', 'Trymus'),
(15551, '60:CB:FB', 'AirScape Inc.'),
(15552, '60:CD:A9', 'Abloomy'),
(15553, '60:CD:C5', 'Taiwan Carol Electronics., Ltd'),
(15554, '60:D0:A9', 'Samsung Electronics Co.,Ltd'),
(15555, '60:D1:AA', 'Vishal Telecommunications Pvt Ltd'),
(15556, '60:D2:B9', 'REALAND BIO CO., LTD.'),
(15557, '60:D3:0A', 'Quatius Limited'),
(15558, '60:D8:19', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15559, '60:D9:A0', 'Lenovo Mobile Communication Technology Ltd.'),
(15560, '60:D9:C7', 'Apple'),
(15561, '60:DA:23', 'Estech Co.,Ltd'),
(15562, '60:DB:2A', 'HNS'),
(15563, '60:DE:44', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15564, '60:E0:0E', 'SHINSEI ELECTRONICS CO LTD'),
(15565, '60:E3:27', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(15566, '60:E6:BC', 'Sino-Telecom Technology Co.,Ltd.'),
(15567, '60:E7:01', 'Huawei Technologies Co., Ltd'),
(15568, '60:E9:56', 'Ayla Networks, Inc'),
(15569, '60:EB:69', 'Quanta computer Inc.'),
(15570, '60:F1:3D', 'JABLOCOM s.r.o.'),
(15571, '60:F1:89', 'Murata Manufacturing Co., Ltd.'),
(15572, '60:F2:81', 'TRANWO TECHNOLOGY CO., LTD.'),
(15573, '60:F2:EF', 'VisionVera International Co., Ltd.'),
(15574, '60:F3:DA', 'Logic Way GmbH'),
(15575, '60:F4:94', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15576, '60:F5:9C', 'CRU-Dataport'),
(15577, '60:F6:73', 'TERUMO CORPORATION'),
(15578, '60:F8:1D', 'Apple'),
(15579, '60:FA:CD', 'Apple'),
(15580, '60:FB:42', 'Apple'),
(15581, '60:FE:1E', 'China Palms Telecom.Ltd'),
(15582, '60:FE:20', '2 Wire'),
(15583, '60:FE:C5', 'Apple'),
(15584, '60:FE:F9', 'Thomas &amp; Betts'),
(15585, '60:FF:DD', 'C.E. ELECTRONICS, INC'),
(15586, '64:00:2D', 'Powerlinq Co., LTD'),
(15587, '64:00:F1', 'CISCO SYSTEMS, INC.'),
(15588, '64:05:BE', 'NEW LIGHT LED'),
(15589, '64:09:4C', 'Beijing Superbee Wireless Technology Co.,Ltd'),
(15590, '64:09:80', 'XIAOMI Electronics,CO.,LTD'),
(15591, '64:0B:4A', 'Digital Telecom Technology Limited'),
(15592, '64:0E:36', 'TAZTAG'),
(15593, '64:0E:94', 'Pluribus Networks, Inc.'),
(15594, '64:0F:28', '2wire'),
(15595, '64:10:84', 'HEXIUM Technical Development Co., Ltd.'),
(15596, '64:12:25', 'Cisco'),
(15597, '64:16:8D', 'CISCO SYSTEMS, INC.'),
(15598, '64:16:F0', 'Shehzhen Huawei Communication Technologies Co., Ltd.'),
(15599, '64:1A:22', 'Heliospectra/Woodhill Investments'),
(15600, '64:1C:67', 'DIGIBRAS INDUSTRIA DO BRASILS/A'),
(15601, '64:1E:81', 'Dowslake Microsystems'),
(15602, '64:20:0C', 'Apple'),
(15603, '64:21:84', 'Nippon Denki Kagaku Co.,LTD'),
(15604, '64:22:16', 'Shandong Taixin Electronic co.,Ltd'),
(15605, '64:24:00', 'Xorcom Ltd.'),
(15606, '64:27:37', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15607, '64:2D:B7', 'SEUNGIL ELECTRONICS'),
(15608, '64:31:50', 'Hewlett-Packard Company'),
(15609, '64:31:7E', 'Dexin Corporation'),
(15610, '64:34:09', 'BITwave Pte Ltd'),
(15611, '64:3A:B1', 'SICHUAN TIANYI COMHEART TELECOMCO.,LTD'),
(15612, '64:3E:8C', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15613, '64:3F:5F', 'Exablaze'),
(15614, '64:42:14', 'Swisscom Energy Solutions AG'),
(15615, '64:43:46', 'GuangDong Quick Network Computer CO.,LTD'),
(15616, '64:4B:C3', 'Shanghai WOASiS Telecommunications Ltd., Co.'),
(15617, '64:4B:F0', 'CalDigit, Inc'),
(15618, '64:4D:70', 'dSPACE GmbH'),
(15619, '64:4F:74', 'LENUS Co., Ltd.'),
(15620, '64:4F:B0', 'Hyunjin.com'),
(15621, '64:51:06', 'Hewlett Packard'),
(15622, '64:51:7E', 'LONG BEN (DONGGUAN) ELECTRONIC TECHNOLOGY CO.,LTD.'),
(15623, '64:52:99', 'The Chamberlain Group, Inc'),
(15624, '64:53:5D', 'Frauscher Sensortechnik'),
(15625, '64:54:22', 'Equinox Payments'),
(15626, '64:55:63', 'Intelight Inc.'),
(15627, '64:55:7F', 'NSFOCUS Information Technology Co., Ltd.'),
(15628, '64:55:B1', 'ARRIS Group, Inc.'),
(15629, '64:56:01', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(15630, '64:5A:04', 'Chicony Electronics Co., Ltd.'),
(15631, '64:5D:D7', 'Shenzhen Lifesense Medical Electronics Co., Ltd.'),
(15632, '64:5E:BE', 'Yahoo! JAPAN'),
(15633, '64:5F:FF', 'Nicolet Neuro'),
(15634, '64:62:23', 'Cellient Co., Ltd.'),
(15635, '64:64:9B', 'juniper networks'),
(15636, '64:65:C0', 'Nuvon, Inc'),
(15637, '64:66:B3', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(15638, '64:67:07', 'Beijing Omnific Technology, Ltd.'),
(15639, '64:68:0C', 'COMTREND'),
(15640, '64:69:BC', 'Hytera Communications Co .,ltd'),
(15641, '64:6C:B2', 'Samsung Electronics Co.,Ltd'),
(15642, '64:6E:6C', 'Radio Datacom LLC'),
(15643, '64:6E:EA', 'Iskratel d.o.o.'),
(15644, '64:70:02', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(15645, '64:72:D8', 'GooWi Technology Co.,Limited'),
(15646, '64:73:E2', 'Arbiter Systems, Inc.'),
(15647, '64:76:57', 'Innovative Security Designs'),
(15648, '64:76:BA', 'Apple'),
(15649, '64:77:91', 'Samsung Electronics Co.,Ltd'),
(15650, '64:7B:D4', 'Texas Instruments'),
(15651, '64:7C:34', 'Ubee Interactive Corp.'),
(15652, '64:7D:81', 'YOKOTA INDUSTRIAL CO,.LTD'),
(15653, '64:7F:DA', 'TEKTELIC Communications Inc.'),
(15654, '64:80:8B', 'VG Controls, Inc.'),
(15655, '64:80:99', 'Intel Corporate'),
(15656, '64:81:25', 'Alphatron Marine BV'),
(15657, '64:87:88', 'Juniper Networks'),
(15658, '64:87:D7', 'Pirelli Tyre S.p.A.'),
(15659, '64:88:FF', 'Sichuan Changhong Electric Ltd.'),
(15660, '64:89:9A', 'LG Electronics'),
(15661, '64:8D:9E', 'IVT Electronic Co.,Ltd'),
(15662, '64:99:5D', 'LGE'),
(15663, '64:99:68', 'Elentec'),
(15664, '64:99:A0', 'AG Elektronik AB'),
(15665, '64:9A:BE', 'Apple'),
(15666, '64:9B:24', 'V Technology Co., Ltd.'),
(15667, '64:9C:81', 'Qualcomm iSkoot, Inc.'),
(15668, '64:9C:8E', 'Texas Instruments'),
(15669, '64:9E:F3', 'CISCO SYSTEMS, INC.'),
(15670, '64:9F:F7', 'Kone OYj'),
(15671, '64:A0:E7', 'CISCO SYSTEMS, INC.'),
(15672, '64:A2:32', 'OOO Samlight'),
(15673, '64:A3:41', 'Wonderlan (Beijing) Technology Co., Ltd.'),
(15674, '64:A3:CB', 'Apple'),
(15675, '64:A7:69', 'HTC Corporation'),
(15676, '64:A7:DD', 'Avaya, Inc'),
(15677, '64:A8:37', 'Juni Korea Co., Ltd'),
(15678, '64:AE:0C', 'CISCO SYSTEMS, INC.'),
(15679, '64:AE:88', 'Polytec GmbH'),
(15680, '64:B2:1D', 'Chengdu Phycom Tech Co., Ltd.'),
(15681, '64:B3:10', 'Samsung Electronics Co.,Ltd'),
(15682, '64:B3:70', 'PowerComm Solutons LLC'),
(15683, '64:B4:73', 'Xiaomi inc.'),
(15684, '64:B6:4A', 'ViVOtech, Inc.'),
(15685, '64:B8:53', 'Samsung Elec Co.,Ltd'),
(15686, '64:B9:E8', 'Apple'),
(15687, '64:BA:BD', 'SDJ Technologies, Inc.'),
(15688, '64:BC:11', 'CombiQ AB'),
(15689, '64:C5:AA', 'South African Broadcasting Corporation'),
(15690, '64:C6:67', 'Barnes&amp;Noble'),
(15691, '64:C6:AF', 'AXERRA Networks Ltd'),
(15692, '64:C9:44', 'LARK Technologies, Inc'),
(15693, '64:D0:2D', 'Next Generation Integration (NGI)'),
(15694, '64:D1:A3', 'Sitecom Europe BV'),
(15695, '64:D2:41', 'Keith &amp; Koep GmbH'),
(15696, '64:D4:BD', 'ALPS ELECTRIC CO.,LTD.'),
(15697, '64:D4:DA', 'Intel Corporate'),
(15698, '64:D8:14', 'CISCO SYSTEMS, INC.'),
(15699, '64:D9:12', 'Solidica, Inc.'),
(15700, '64:D9:54', 'TAICANG AND W ELECTRONICS CO LTD'),
(15701, '64:D9:89', 'CISCO SYSTEMS, INC.'),
(15702, '64:DB:18', 'OpenPattern'),
(15703, '64:DC:01', 'Static Systems Group PLC'),
(15704, '64:DE:1C', 'Kingnetic Pte Ltd'),
(15705, '64:E1:61', 'DEP Corp.'),
(15706, '64:E5:99', 'EFM Networks'),
(15707, '64:E6:25', 'Woxu Wireless Co., Ltd'),
(15708, '64:E6:82', 'Apple'),
(15709, '64:E8:4F', 'Serialway Communication Technology Co. Ltd'),
(15710, '64:E8:92', 'Morio Denki Co., Ltd.'),
(15711, '64:E8:E6', 'global moisture management system'),
(15712, '64:E9:50', 'Cisco'),
(15713, '64:EA:C5', 'SiboTech Automation Co., Ltd.'),
(15714, '64:EB:8C', 'Seiko Epson Corporation'),
(15715, '64:ED:57', 'ARRIS Group, Inc.'),
(15716, '64:ED:62', 'WOORI SYSTEMS Co., Ltd'),
(15717, '64:F2:42', 'Gerdes Aktiengesellschaft'),
(15718, '64:F5:0E', 'Kinion Technology Company Limited'),
(15719, '64:F6:9D', 'Cisco'),
(15720, '64:F9:70', 'Kenade Electronics Technology Co.,LTD.'),
(15721, '64:F9:87', 'Avvasi Inc.'),
(15722, '64:FC:8C', 'Zonar Systems'),
(15723, '68:05:71', 'Samsung Electronics Co.,Ltd'),
(15724, '68:05:CA', 'Intel Corporate'),
(15725, '68:09:27', 'Apple'),
(15726, '68:0A:D7', 'Yancheng Kecheng Optoelectronic Technology Co., Ltd'),
(15727, '68:12:2D', 'Special Instrument Development Co., Ltd.'),
(15728, '68:15:90', 'SAGEMCOM SAS'),
(15729, '68:15:D3', 'Zaklady Elektroniki i Mechaniki Precyzyjnej R&amp;G S.A.'),
(15730, '68:16:05', 'Systems And Electronic Development FZCO'),
(15731, '68:17:29', 'Intel Corporate'),
(15732, '68:19:3F', 'Digital Airways'),
(15733, '68:1A:B2', 'zte corporation'),
(15734, '68:1C:A2', 'Rosewill Inc.'),
(15735, '68:1D:64', 'Sunwave Communications Co., Ltd'),
(15736, '68:1E:8B', 'InfoSight Corporation'),
(15737, '68:1F:D8', 'Advanced Telemetry'),
(15738, '68:23:4B', 'Nihon Dengyo Kousaku'),
(15739, '68:28:BA', 'Dejai'),
(15740, '68:28:F6', 'Vubiq Networks, Inc.'),
(15741, '68:2D:DC', 'Wuhan Changjiang Electro-Communication Equipment CO.,LTD'),
(15742, '68:36:B5', 'DriveScale, Inc.'),
(15743, '68:3B:1E', 'Countwise LTD'),
(15744, '68:3C:7D', 'Magic Intelligence Technology Limited'),
(15745, '68:3E:EC', 'ERECA'),
(15746, '68:43:52', 'Bhuu Limited'),
(15747, '68:48:98', 'Samsung Electronics Co.,Ltd'),
(15748, '68:4B:88', 'Galtronics Telemetry Inc.'),
(15749, '68:4C:A8', 'Shenzhen Herotel Tech. Co., Ltd.'),
(15750, '68:51:B7', 'PowerCloud Systems, Inc.'),
(15751, '68:54:ED', 'Alcatel-Lucent - Nuage'),
(15752, '68:54:F5', 'enLighted Inc'),
(15753, '68:59:7F', 'Alcatel Lucent'),
(15754, '68:5B:35', 'Apple'),
(15755, '68:5B:36', 'POWERTECH INDUSTRIAL CO., LTD.'),
(15756, '68:5D:43', 'Intel Corporate'),
(15757, '68:5E:6B', 'PowerRay Co., Ltd.'),
(15758, '68:63:59', 'Advanced Digital Broadcast SA'),
(15759, '68:64:4B', 'Apple'),
(15760, '68:69:2E', 'Zycoo Co.,Ltd'),
(15761, '68:69:F2', 'ComAp s.r.o.'),
(15762, '68:6E:23', 'Wi3 Inc.'),
(15763, '68:6E:48', 'Prophet Electronic Technology Corp.,Ltd'),
(15764, '68:72:51', 'Ubiquiti Networks'),
(15765, '68:72:DC', 'CETORY.TV Company Limited'),
(15766, '68:76:4F', 'Sony Mobile Communications AB'),
(15767, '68:78:48', 'Westunitis Co., Ltd.'),
(15768, '68:78:4C', 'Nortel Networks'),
(15769, '68:79:24', 'ELS-GmbH &amp; Co. KG'),
(15770, '68:79:ED', 'SHARP Corporation'),
(15771, '68:7C:C8', 'Measurement Systems S. de R.L.'),
(15772, '68:7C:D5', 'Y Soft Corporation, a.s.'),
(15773, '68:7F:74', 'Cisco-Linksys, LLC'),
(15774, '68:83:1A', 'Pandora Mobility Corporation'),
(15775, '68:84:70', 'eSSys Co.,Ltd'),
(15776, '68:85:40', 'IGI Mobile, Inc.'),
(15777, '68:85:6A', 'OuterLink Corporation'),
(15778, '68:86:A7', 'Cisco'),
(15779, '68:86:E7', 'Orbotix, Inc.'),
(15780, '68:87:6B', 'INQ Mobile Limited'),
(15781, '68:8A:B5', 'EDP Servicos'),
(15782, '68:8F:84', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15783, '68:92:34', 'Ruckus Wireless'),
(15784, '68:94:23', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15785, '68:96:7B', 'Apple'),
(15786, '68:97:4B', 'Shenzhen Costar Electronics Co. Ltd.'),
(15787, '68:97:E8', 'Society of Motion Picture &amp;amp; Television Engineers'),
(15788, '68:99:CD', 'Cisco'),
(15789, '68:9C:5E', 'AcSiP Technology Corp.'),
(15790, '68:9C:70', 'Apple'),
(15791, '68:9C:E2', 'Cisco'),
(15792, '68:A0:F6', 'Huawei Technologies Co., Ltd'),
(15793, '68:A1:B7', 'Honghao Mingchuan Technology (Beijing) CO.,Ltd.'),
(15794, '68:A3:C4', 'Liteon Technology Corporation'),
(15795, '68:A4:0E', 'BSH Bosch and Siemens Home Appliances GmbH'),
(15796, '68:A8:6D', 'Apple'),
(15797, '68:AA:D2', 'DATECS LTD.,'),
(15798, '68:AB:8A', 'RF IDeas'),
(15799, '68:AE:20', 'Apple'),
(15800, '68:AF:13', 'Futura Mobility'),
(15801, '68:B0:94', 'INESA ELECTRON CO.,LTD'),
(15802, '68:B4:3A', 'WaterFurnace International, Inc.'),
(15803, '68:B5:99', 'Hewlett-Packard Company'),
(15804, '68:B6:FC', 'Hitron Technologies. Inc'),
(15805, '68:B8:D9', 'Act KDE, Inc.'),
(15806, '68:B9:83', 'b-plus GmbH'),
(15807, '68:BC:0C', 'CISCO SYSTEMS, INC.'),
(15808, '68:BD:AB', 'CISCO SYSTEMS, INC.'),
(15809, '68:C9:0B', 'Texas Instruments'),
(15810, '68:CA:00', 'Octopus Systems Limited'),
(15811, '68:CC:9C', 'Mine Site Technologies'),
(15812, '68:CD:0F', 'U Tek Company Limited'),
(15813, '68:CE:4E', 'L-3 Communications Infrared Products'),
(15814, '68:D1:FD', 'Shenzhen Trimax Technology Co.,Ltd'),
(15815, '68:D2:47', 'Portalis LC'),
(15816, '68:D9:25', 'ProSys Development Services'),
(15817, '68:D9:3C', 'Apple'),
(15818, '68:DB:67', 'Nantong Coship Electronics Co., Ltd'),
(15819, '68:DB:96', 'OPWILL Technologies CO .,LTD'),
(15820, '68:DC:E8', 'PacketStorm Communications'),
(15821, '68:DF:DD', 'Xiaomi inc.'),
(15822, '68:E1:66', 'PRIVATE'),
(15823, '68:E4:1F', 'Unglaube Identech GmbH'),
(15824, '68:EB:AE', 'Samsung Electronics Co.,Ltd'),
(15825, '68:EB:C5', 'Angstrem Telecom'),
(15826, '68:EC:62', 'YODO Technology Corp. Ltd.'),
(15827, '68:ED:43', 'Research In Motion'),
(15828, '68:EE:96', 'Cisco SPVTG'),
(15829, '68:EF:BD', 'CISCO SYSTEMS, INC.'),
(15830, '68:F0:6D', 'ALONG INDUSTRIAL CO., LIMITED'),
(15831, '68:F0:BC', 'Shenzhen LiWiFi Technology Co., Ltd'),
(15832, '68:F1:25', 'Data Controls Inc.'),
(15833, '68:F7:28', 'LCFC(HeFei) Electronics Technology co., ltd'),
(15834, '68:F8:95', 'Redflow Limited'),
(15835, '68:FB:95', 'Generalplus Technology Inc.'),
(15836, '68:FC:B3', 'Next Level Security Systems, Inc.'),
(15837, '6C:02:73', 'Shenzhen Jin Yun Video Equipment Co., Ltd.'),
(15838, '6C:04:60', 'RBH Access Technologies Inc.'),
(15839, '6C:09:D6', 'Digiquest Electronics LTD'),
(15840, '6C:0B:84', 'Universal Global Scientific Industrial Co.,Ltd.'),
(15841, '6C:0E:0D', 'Sony Ericsson Mobile Communications AB'),
(15842, '6C:0F:6A', 'JDC Tech Co., Ltd.'),
(15843, '6C:14:F7', 'Erhardt+Leimer GmbH'),
(15844, '6C:15:F9', 'Nautronix Limited'),
(15845, '6C:18:11', 'Decatur Electronics'),
(15846, '6C:19:8F', 'D-Link International'),
(15847, '6C:20:56', 'Cisco'),
(15848, '6C:22:AB', 'Ainsworth Game Technology'),
(15849, '6C:23:B9', 'Sony Ericsson Mobile Communications AB'),
(15850, '6C:25:B9', 'BBK Electronics Corp., Ltd.,'),
(15851, '6C:29:95', 'Intel Corporate'),
(15852, '6C:2C:06', 'OOO NPP Systemotechnika-NN'),
(15853, '6C:2E:33', 'Accelink Technologies Co.,Ltd.'),
(15854, '6C:2E:72', 'B&amp;B EXPORTING LIMITED'),
(15855, '6C:2E:85', 'SAGEMCOM'),
(15856, '6C:2F:2C', 'Samsung Electronics Co.,Ltd'),
(15857, '6C:32:DE', 'Indieon Technologies Pvt. Ltd.'),
(15858, '6C:33:A9', 'Magicjack LP'),
(15859, '6C:39:1D', 'Beijing ZhongHuaHun Network Information center'),
(15860, '6C:3A:84', 'Shenzhen Aero-Startech. Co.Ltd'),
(15861, '6C:3B:E5', 'Hewlett Packard'),
(15862, '6C:3C:53', 'SoundHawk Corp'),
(15863, '6C:3E:6D', 'Apple'),
(15864, '6C:3E:9C', 'KE Knestel Elektronik GmbH'),
(15865, '6C:40:08', 'Apple'),
(15866, '6C:40:C6', 'Nimbus Data Systems, Inc.'),
(15867, '6C:41:6A', 'Cisco'),
(15868, '6C:4B:7F', 'Vossloh-Schwabe Deutschland GmbH'),
(15869, '6C:50:4D', 'CISCO SYSTEMS, INC.'),
(15870, '6C:57:79', 'Aclima, Inc.'),
(15871, '6C:5A:34', 'Shenzhen Haitianxiong Electronic Co., Ltd.'),
(15872, '6C:5A:B5', 'TCL Technoly Electronics (Huizhou) Co., Ltd.'),
(15873, '6C:5C:DE', 'SunReports, Inc.'),
(15874, '6C:5D:63', 'ShenZhen Rapoo Technology Co., Ltd.'),
(15875, '6C:5E:7A', 'Ubiquitous Internet Telecom Co., Ltd'),
(15876, '6C:5F:1C', 'Lenovo Mobile Communication Technology Ltd.'),
(15877, '6C:61:26', 'Rinicom Holdings'),
(15878, '6C:62:6D', 'Micro-Star INT\'L CO., LTD'),
(15879, '6C:64:1A', 'Penguin Computing'),
(15880, '6C:6E:FE', 'Core Logic Inc.'),
(15881, '6C:6F:18', 'Stereotaxis, Inc.'),
(15882, '6C:70:39', 'Novar GmbH'),
(15883, '6C:70:9F', 'Apple'),
(15884, '6C:71:D9', 'AzureWave Technologies, Inc'),
(15885, '6C:76:60', 'KYOCERA Corporation'),
(15886, '6C:81:FE', 'Mitsuba Corporation'),
(15887, '6C:83:36', 'Samsung Electronics Co.,Ltd'),
(15888, '6C:83:66', 'Nanjing SAC Power Grid Automation Co., Ltd.'),
(15889, '6C:86:86', 'Technonia'),
(15890, '6C:88:14', 'Intel Corporate'),
(15891, '6C:8B:2F', 'zte corporation'),
(15892, '6C:8C:DB', 'Otus Technologies Ltd'),
(15893, '6C:8D:65', 'Wireless Glue Networks, Inc.'),
(15894, '6C:90:B1', 'SanLogic Inc'),
(15895, '6C:92:BF', 'Inspur Electronic Information Industry Co.,Ltd.'),
(15896, '6C:94:F8', 'Apple'),
(15897, '6C:98:EB', 'Ocedo GmbH'),
(15898, '6C:99:89', 'Cisco'),
(15899, '6C:9A:C9', 'Valentine Research, Inc.'),
(15900, '6C:9B:02', 'Nokia Corporation'),
(15901, '6C:9C:E9', 'Nimble Storage'),
(15902, '6C:9C:ED', 'CISCO SYSTEMS, INC.'),
(15903, '6C:A6:82', 'EDAM information &amp; communications'),
(15904, '6C:A7:80', 'Nokia Corporation'),
(15905, '6C:A7:FA', 'YOUNGBO ENGINEERING INC.'),
(15906, '6C:A8:49', 'Avaya, Inc'),
(15907, '6C:A9:06', 'Telefield Ltd'),
(15908, '6C:A9:6F', 'TransPacket AS'),
(15909, '6C:AA:B3', 'Ruckus Wireless'),
(15910, '6C:AB:4D', 'Digital Payment Technologies'),
(15911, '6C:AC:60', 'Venetex Corp'),
(15912, '6C:AD:3F', 'Hubbell Building Automation, Inc.'),
(15913, '6C:AD:EF', 'KZ Broadband Technologies, Ltd.'),
(15914, '6C:AD:F8', 'Azurewave Technologies, Inc.'),
(15915, '6C:AE:8B', 'IBM Corporation'),
(15916, '6C:B0:CE', 'NETGEAR'),
(15917, '6C:B3:11', 'Shenzhen Lianrui Electronics Co.,Ltd'),
(15918, '6C:B3:50', 'Anhui comhigher tech co.,ltd'),
(15919, '6C:B5:6B', 'HUMAX.CO.,LTD'),
(15920, '6C:B7:F4', 'Samsung Electronics Co.,Ltd'),
(15921, '6C:BE:E9', 'Alcatel-Lucent-IPD'),
(15922, '6C:BF:B5', 'Noon Technology Co., Ltd'),
(15923, '6C:C1:D2', 'ARRIS Group, Inc.'),
(15924, '6C:C2:17', 'Hewlett Packard'),
(15925, '6C:C2:6B', 'Apple'),
(15926, '6C:CA:08', 'ARRIS Group, Inc.'),
(15927, '6C:D0:32', 'LG Electronics'),
(15928, '6C:D1:46', 'Smartek d.o.o.'),
(15929, '6C:D1:B0', 'WING SING ELECTRONICS HONG KONG LIMITED'),
(15930, '6C:D6:8A', 'LG Electronics Inc'),
(15931, '6C:DC:6A', 'Promethean Limited'),
(15932, '6C:E0:B0', 'SOUND4'),
(15933, '6C:E4:CE', 'Villiger Security Solutions AG'),
(15934, '6C:E8:73', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(15935, '6C:E9:07', 'Nokia Corporation'),
(15936, '6C:E9:83', 'Gastron Co., LTD.'),
(15937, '6C:EC:A1', 'SHENZHEN CLOU ELECTRONICS CO. LTD.'),
(15938, '6C:EC:EB', 'Texas Instruments'),
(15939, '6C:F0:49', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(15940, '6C:F3:73', 'Samsung Electronics Co.,Ltd'),
(15941, '6C:F3:7F', 'Aruba Networks'),
(15942, '6C:F5:E8', 'Mooredoll Inc.'),
(15943, '6C:F9:7C', 'Nanoptix Inc.'),
(15944, '6C:FA:58', 'Avaya, Inc'),
(15945, '6C:FA:89', 'Cisco'),
(15946, '6C:FA:A7', 'AMPAK Technology Inc.'),
(15947, '6C:FD:B9', 'Proware Technologies Co Ltd.'),
(15948, '6C:FF:BE', 'MPB Communications Inc.'),
(15949, '70:01:36', 'FATEK Automation Corporation'),
(15950, '70:02:58', '01DB-METRAVIB'),
(15951, '70:05:14', 'LG Electronics'),
(15952, '70:0B:C0', 'Dewav Technology Company'),
(15953, '70:0F:C7', 'SHENZHEN IKINLOOP TECHNOLOGY CO.,LTD.'),
(15954, '70:0F:EC', 'Poindus Systems Corp.'),
(15955, '70:10:5C', 'Cisco'),
(15956, '70:11:24', 'Apple'),
(15957, '70:14:04', 'Limited Liability Company'),
(15958, '70:14:A6', 'Apple, Inc.'),
(15959, '70:18:8B', 'Hon Hai Precision Ind. Co.,Ltd.'),
(15960, '70:1A:04', 'Liteon Tech Corp.'),
(15961, '70:1A:ED', 'ADVAS CO., LTD.'),
(15962, '70:1D:7F', 'Comtech Technology Co., Ltd.'),
(15963, '70:23:93', 'fos4X GmbH'),
(15964, '70:25:26', 'Alcatel-Lucent'),
(15965, '70:25:59', 'CyberTAN Technology, Inc.'),
(15966, '70:2B:1D', 'E-Domus International Limited'),
(15967, '70:2C:1F', 'Wisol'),
(15968, '70:2D:D1', 'Newings Communication CO., LTD.'),
(15969, '70:2F:4B', 'PolyVision Inc.'),
(15970, '70:2F:97', 'Aava Mobile Oy'),
(15971, '70:30:18', 'Avaya, Inc'),
(15972, '70:30:5D', 'Ubiquoss Inc'),
(15973, '70:30:5E', 'Nanjing Zhongke Menglian Information Technology Co.,LTD'),
(15974, '70:31:87', 'ACX GmbH'),
(15975, '70:32:D5', 'Athena Wireless Communications Inc'),
(15976, '70:38:11', 'Invensys Rail'),
(15977, '70:38:B4', 'Low Tech Solutions'),
(15978, '70:38:EE', 'Avaya, Inc'),
(15979, '70:3A:D8', 'Shenzhen Afoundry Electronic Co., Ltd'),
(15980, '70:3C:39', 'SEAWING Kft'),
(15981, '70:3E:AC', 'Apple'),
(15982, '70:41:B7', 'Edwards Lifesciences LLC'),
(15983, '70:46:42', 'CHYNG HONG ELECTRONIC CO., LTD.'),
(15984, '70:4A:AE', 'Xstream Flow (Pty) Ltd'),
(15985, '70:4A:E4', 'Rinstrum Pty Ltd'),
(15986, '70:4C:ED', 'TMRG, Inc.'),
(15987, '70:4E:01', 'KWANGWON TECH CO., LTD.'),
(15988, '70:4E:66', 'SHENZHEN FAST TECHNOLOGIES CO.,LTD'),
(15989, '70:52:C5', 'Avaya, Inc.'),
(15990, '70:53:3F', 'Alfa Instrumentos Eletronicos Ltda.'),
(15991, '70:54:D2', 'PEGATRON CORPORATION'),
(15992, '70:54:F5', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(15993, '70:56:81', 'Apple'),
(15994, '70:58:12', 'Panasonic AVC Networks Company'),
(15995, '70:59:57', 'Medallion Instrumentation Systems'),
(15996, '70:59:86', 'OOO TTV'),
(15997, '70:5A:B6', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(15998, '70:5B:2E', 'M2Communication Inc.'),
(15999, '70:5C:AD', 'Konami Gaming Inc'),
(16000, '70:5E:AA', 'Action Target, Inc.'),
(16001, '70:60:DE', 'LaVision GmbH'),
(16002, '70:61:73', 'Calantec GmbH'),
(16003, '70:62:B8', 'D-Link International'),
(16004, '70:64:17', 'ORBIS TECNOLOGIA ELECTRICA S.A.'),
(16005, '70:65:82', 'Suzhou Hanming Technologies Co., Ltd.'),
(16006, '70:6F:81', 'PRIVATE'),
(16007, '70:70:4C', 'Purple Communications, Inc'),
(16008, '70:71:B3', 'Brain Corporation'),
(16009, '70:71:BC', 'PEGATRON CORPORATION'),
(16010, '70:72:0D', 'Lenovo Mobile Communication Technology Ltd.'),
(16011, '70:72:3C', 'Huawei Technologies Co., Ltd'),
(16012, '70:72:CF', 'EdgeCore Networks'),
(16013, '70:73:CB', 'Apple'),
(16014, '70:76:30', 'Pace plc.'),
(16015, '70:76:DD', 'Oxyguard International A/S'),
(16016, '70:76:F0', 'LevelOne Communications (India) Private Limited'),
(16017, '70:76:FF', 'KERLINK'),
(16018, '70:7B:E8', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16019, '70:7C:18', 'ADATA Technology Co., Ltd'),
(16020, '70:7E:43', 'ARRIS Group, Inc.'),
(16021, '70:7E:DE', 'NASTEC LTD.'),
(16022, '70:81:05', 'CISCO SYSTEMS, INC.'),
(16023, '70:82:0E', 'as electronics GmbH'),
(16024, '70:82:8E', 'OleumTech Corporation'),
(16025, '70:85:C6', 'Pace plc.'),
(16026, '70:8B:78', 'citygrow technology co., ltd'),
(16027, '70:8D:09', 'Nokia Corporation'),
(16028, '70:93:83', 'Intelligent Optical Network High Tech CO.,LTD.'),
(16029, '70:93:F8', 'Space Monkey, Inc.'),
(16030, '70:97:56', 'Happyelectronics Co.,Ltd'),
(16031, '70:9A:0B', 'Italian Institute of Technology'),
(16032, '70:9B:A5', 'Shenzhen Y&amp;D Electronics Co.,LTD.'),
(16033, '70:9B:FC', 'Bryton Inc.'),
(16034, '70:9C:8F', 'Nero AG'),
(16035, '70:9E:29', 'Sony Computer Entertainment Inc.'),
(16036, '70:9E:86', 'X6D Limited'),
(16037, '70:9F:2D', 'zte corporation'),
(16038, '70:A1:91', 'Trendsetter Medical, LLC'),
(16039, '70:A4:1C', 'Advanced Wireless Dynamics S.L.'),
(16040, '70:A6:6A', 'Prox Dynamics AS'),
(16041, '70:A8:E3', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16042, '70:AA:B2', 'Research In Motion'),
(16043, '70:AD:54', 'Malvern Instruments Ltd'),
(16044, '70:AF:25', 'Nishiyama Industry Co.,LTD.'),
(16045, '70:B0:35', 'Shenzhen Zowee Technology Co., Ltd'),
(16046, '70:B0:8C', 'Shenou Communication Equipment Co.,Ltd'),
(16047, '70:B1:4E', 'Pace plc'),
(16048, '70:B2:65', 'Hiltron s.r.l.'),
(16049, '70:B3:D5', 'IEEE REGISTRATION AUTHORITY  - Please see OUI36 public listing for more information.'),
(16050, '70:B5:99', 'Embedded Technologies s.r.o.'),
(16051, '70:B9:21', 'FiberHome Telecommunication Technologies CO.,LTD'),
(16052, '70:BA:EF', 'Hangzhou H3C Technologies Co., Limited'),
(16053, '70:C6:AC', 'Bosch Automotive Aftermarket'),
(16054, '70:C7:6F', 'INNO S'),
(16055, '70:CA:9B', 'CISCO SYSTEMS, INC.'),
(16056, '70:CD:60', 'Apple'),
(16057, '70:D4:F2', 'RIM'),
(16058, '70:D5:7E', 'Scalar Corporation'),
(16059, '70:D5:E7', 'Wellcore Corporation'),
(16060, '70:D6:B6', 'Metrum Technologies'),
(16061, '70:D8:80', 'Upos System sp. z o.o.'),
(16062, '70:DA:9C', 'TECSEN'),
(16063, '70:DD:A1', 'Tellabs'),
(16064, '70:DE:E2', 'Apple'),
(16065, '70:E0:27', 'HONGYU COMMUNICATION TECHNOLOGY LIMITED'),
(16066, '70:E1:39', '3view Ltd'),
(16067, '70:E2:4C', 'SAE IT-systems GmbH &amp; Co. KG'),
(16068, '70:E2:84', 'Wistron InfoComm(Zhongshan) Corporation'),
(16069, '70:E8:43', 'Beijing C&amp;W Optical Communication Technology Co.,Ltd.'),
(16070, '70:EE:50', 'Netatmo'),
(16071, '70:F1:76', 'Data Modul AG'),
(16072, '70:F1:96', 'Actiontec Electronics, Inc'),
(16073, '70:F1:A1', 'Liteon Technology Corporation'),
(16074, '70:F1:E5', 'Xetawave LLC'),
(16075, '70:F3:95', 'Universal Global Scientific Industrial Co., Ltd.'),
(16076, '70:F9:27', 'Samsung Electronics'),
(16077, '70:F9:6D', 'Hangzhou H3C Technologies Co., Limited'),
(16078, '70:FC:8C', 'OneAccess SA'),
(16079, '70:FF:5C', 'Cheerzing Communication(Xiamen)Technology Co.,Ltd'),
(16080, '70:FF:76', 'Texas Instruments'),
(16081, '74:03:BD', 'Buffalo Inc.'),
(16082, '74:0A:BC', 'JSJS Designs (Europe) Limited'),
(16083, '74:0E:DB', 'Optowiz Co., Ltd'),
(16084, '74:14:89', 'SRT Wireless'),
(16085, '74:15:E2', 'Tri-Sen Systems Corporation'),
(16086, '74:19:F8', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(16087, '74:1E:93', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(16088, '74:25:8A', 'Hangzhou H3C Technologies Co., Limited'),
(16089, '74:26:AC', 'Cisco'),
(16090, '74:27:3C', 'ChangYang Technology (Nanjing) Co., LTD'),
(16091, '74:27:EA', 'Elitegroup Computer Systems Co., Ltd.'),
(16092, '74:29:AF', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16093, '74:2B:0F', 'Infinidat Ltd.'),
(16094, '74:2B:62', 'Fujitsu Limited'),
(16095, '74:2D:0A', 'Norfolk Elektronik AG'),
(16096, '74:2E:FC', 'DirectPacket Research, Inc,'),
(16097, '74:2F:68', 'Azurewave Technologies, Inc.'),
(16098, '74:31:70', 'Arcadyan Technology Corporation'),
(16099, '74:32:56', 'NT-ware Systemprg GmbH'),
(16100, '74:37:2F', 'Tongfang Shenzhen Cloudcomputing Technology Co.,Ltd'),
(16101, '74:38:89', 'ANNAX Anzeigesysteme GmbH'),
(16102, '74:3E:CB', 'Gentrice tech'),
(16103, '74:44:01', 'NETGEAR'),
(16104, '74:45:8A', 'Samsung Electronics Co.,Ltd'),
(16105, '74:46:A0', 'Hewlett Packard'),
(16106, '74:4B:E9', 'EXPLORER HYPERTECH CO.,LTD'),
(16107, '74:4D:79', 'Arrive Systems Inc.'),
(16108, '74:51:BA', 'XIAOMI INC'),
(16109, '74:53:27', 'COMMSEN CO., LIMITED'),
(16110, '74:54:7D', 'Cisco SPVTG'),
(16111, '74:56:12', 'ARRIS Group, Inc.'),
(16112, '74:57:98', 'TRUMPF Laser GmbH + Co. KG'),
(16113, '74:5C:9F', 'TCT mobile ltd.'),
(16114, '74:5E:1C', 'PIONEER CORPORATION'),
(16115, '74:5F:00', 'Samsung Semiconductor Inc.'),
(16116, '74:5F:AE', 'TSL PPL'),
(16117, '74:63:DF', 'VTS GmbH'),
(16118, '74:65:D1', 'Atlinks'),
(16119, '74:66:30', 'T:mi Ytti'),
(16120, '74:6A:89', 'Rezolt Corporation'),
(16121, '74:6A:8F', 'VS Vision Systems GmbH'),
(16122, '74:6B:82', 'MOVEK'),
(16123, '74:6F:3D', 'Contec GmbH'),
(16124, '74:72:F2', 'Chipsip Technology Co., Ltd.'),
(16125, '74:75:48', 'Amazon Technologies Inc.'),
(16126, '74:78:18', 'ServiceAssure'),
(16127, '74:7B:7A', 'ETH Inc.'),
(16128, '74:7D:B6', 'Aliwei Communications, Inc'),
(16129, '74:7E:1A', 'Red Embedded Design Limited'),
(16130, '74:7E:2D', 'Beijing Thomson CITIC Digital Technology Co. LTD.'),
(16131, '74:81:14', 'Apple'),
(16132, '74:86:7A', 'Dell Inc'),
(16133, '74:88:2A', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16134, '74:88:8B', 'ADB Broadband Italia'),
(16135, '74:8E:08', 'Bestek Corp.'),
(16136, '74:8E:F8', 'Brocade Communications Systems, Inc.'),
(16137, '74:8F:1B', 'MasterImage 3D'),
(16138, '74:8F:4D', 'MEN Mikro Elektronik GmbH'),
(16139, '74:90:50', 'Renesas Electronics Corporation'),
(16140, '74:91:1A', 'Ruckus Wireless'),
(16141, '74:91:BD', 'Four systems Co.,Ltd.'),
(16142, '74:93:A4', 'Zebra Technologies Corp.'),
(16143, '74:94:3D', 'AgJunction'),
(16144, '74:96:37', 'Todaair Electronic Co., Ltd'),
(16145, '74:99:75', 'IBM Corporation'),
(16146, '74:9C:52', 'Huizhou Desay SV Automotive Co., Ltd.'),
(16147, '74:9C:E3', 'Art2Wave Canada Inc.'),
(16148, '74:9D:DC', '2Wire'),
(16149, '74:A0:2F', 'Cisco'),
(16150, '74:A0:63', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16151, '74:A3:4A', 'ZIMI CORPORATION'),
(16152, '74:A4:A7', 'QRS Music Technologies, Inc.'),
(16153, '74:A4:B5', 'Powerleader Science and Technology Co. Ltd.'),
(16154, '74:A7:22', 'LG Electronics'),
(16155, '74:AD:B7', 'China Mobile Group Device Co.,Ltd.'),
(16156, '74:AE:76', 'iNovo Broadband, Inc.'),
(16157, '74:B0:0C', 'Network Video Technologies, Inc'),
(16158, '74:B9:EB', 'Fujian JinQianMao Electronic Technology Co.,Ltd'),
(16159, '74:BA:DB', 'Longconn Electornics(shenzhen)Co.,Ltd'),
(16160, '74:BE:08', 'ATEK Products, LLC'),
(16161, '74:BF:A1', 'HYUNTECK'),
(16162, '74:C2:46', 'Amazon Technologies Inc.'),
(16163, '74:C6:21', 'Zhejiang Hite Renewable Energy Co.,LTD'),
(16164, '74:C9:9A', 'Ericsson AB'),
(16165, '74:CA:25', 'Calxeda, Inc.'),
(16166, '74:CD:0C', 'Smith Myers Communications Ltd.'),
(16167, '74:CE:56', 'Packet Force Technology Limited Company'),
(16168, '74:D0:2B', 'ASUSTek COMPUTER INC.'),
(16169, '74:D0:DC', 'ERICSSON AB'),
(16170, '74:D4:35', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(16171, '74:D6:75', 'WYMA Tecnologia'),
(16172, '74:D6:EA', 'Texas Instruments'),
(16173, '74:D8:50', 'Evrisko Systems'),
(16174, '74:DA:38', 'Edimax Technology Co. Ltd.'),
(16175, '74:DB:D1', 'Ebay Inc'),
(16176, '74:DE:2B', 'Liteon Technology Corporation'),
(16177, '74:E0:6E', 'Ergophone GmbH'),
(16178, '74:E1:4A', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(16179, '74:E1:B6', 'Apple'),
(16180, '74:E2:77', 'Vizmonet Pte Ltd'),
(16181, '74:E2:8C', 'Microsoft Corporation'),
(16182, '74:E2:F5', 'Apple'),
(16183, '74:E4:24', 'APISTE CORPORATION'),
(16184, '74:E5:0B', 'Intel Corporate'),
(16185, '74:E5:37', 'RADSPIN'),
(16186, '74:E5:43', 'Liteon Technology Corporation'),
(16187, '74:E6:E2', 'Dell Inc.'),
(16188, '74:E7:C6', 'ARRIS Group, Inc.'),
(16189, '74:EA:3A', 'TP-LINK Technologies Co.,Ltd.'),
(16190, '74:EC:F1', 'Acumen'),
(16191, '74:F0:6D', 'AzureWave Technologies, Inc.'),
(16192, '74:F0:7D', 'BnCOM Co.,Ltd'),
(16193, '74:F1:02', 'Beijing HCHCOM Technology Co., Ltd'),
(16194, '74:F4:13', 'Maxwell Forest'),
(16195, '74:F6:12', 'ARRIS Group, Inc.'),
(16196, '74:F7:26', 'Neuron Robotics'),
(16197, '74:F8:5D', 'Berkeley Nucleonics Corp'),
(16198, '74:FD:A0', 'Compupal (Group) Corporation'),
(16199, '74:FE:48', 'ADVANTECH CO., LTD.'),
(16200, '74:FF:7D', 'Wren Sound Systems, LLC'),
(16201, '78:02:8F', 'Adaptive Spectrum and Signal Alignment (ASSIA), Inc.'),
(16202, '78:07:38', 'Z.U.K. Elzab S.A.'),
(16203, '78:11:85', 'NBS Payment Solutions Inc.'),
(16204, '78:12:B8', 'ORANTEK LIMITED'),
(16205, '78:18:81', 'AzureWave Technologies, Inc.'),
(16206, '78:19:2E', 'NASCENT Technology'),
(16207, '78:19:F7', 'Juniper Networks'),
(16208, '78:1C:5A', 'SHARP Corporation'),
(16209, '78:1D:BA', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16210, '78:1D:FD', 'Jabil Inc'),
(16211, '78:1F:DB', 'Samsung Electronics Co.,Ltd'),
(16212, '78:22:3D', 'Affirmed Networks'),
(16213, '78:24:AF', 'ASUSTek COMPUTER INC.'),
(16214, '78:25:44', 'Omnima Limited'),
(16215, '78:25:AD', 'SAMSUNG ELECTRONICS CO., LTD.'),
(16216, '78:2B:CB', 'Dell Inc'),
(16217, '78:2E:EF', 'Nokia Corporation'),
(16218, '78:30:3B', 'Stephen Technologies Co.,Limited'),
(16219, '78:30:E1', 'UltraClenz, LLC'),
(16220, '78:31:2B', 'zte corporation'),
(16221, '78:31:C1', 'Apple'),
(16222, '78:32:4F', 'Millennium Group, Inc.'),
(16223, '78:3A:84', 'Apple'),
(16224, '78:3C:E3', 'Kai-EE'),
(16225, '78:3D:5B', 'TELNET Redes Inteligentes S.A.'),
(16226, '78:3E:53', 'BSkyB Ltd'),
(16227, '78:3F:15', 'EasySYNC Ltd.'),
(16228, '78:40:E4', 'Samsung Electronics Co.,Ltd'),
(16229, '78:44:05', 'FUJITU(HONG KONG) ELECTRONIC Co.,LTD.'),
(16230, '78:44:76', 'Zioncom technology co.,ltd'),
(16231, '78:45:61', 'CyberTAN Technology Inc.'),
(16232, '78:45:C4', 'Dell Inc'),
(16233, '78:46:C4', 'DAEHAP HYPER-TECH'),
(16234, '78:47:1D', 'Samsung Electronics Co.,Ltd'),
(16235, '78:48:59', 'Hewlett Packard'),
(16236, '78:49:1D', 'The Will-Burt Company'),
(16237, '78:4B:08', 'f.robotics acquisitions ltd'),
(16238, '78:4B:87', 'Murata Manufacturing Co.,Ltd.'),
(16239, '78:51:0C', 'LiveU Ltd.'),
(16240, '78:52:1A', 'Samsung Electronics Co.,Ltd'),
(16241, '78:52:62', 'Shenzhen Hojy Software Co., Ltd.'),
(16242, '78:54:2E', 'D-Link International'),
(16243, '78:55:17', 'SankyuElectronics'),
(16244, '78:57:12', 'Mobile Integration Workgroup'),
(16245, '78:58:F3', 'Vachen Co.,Ltd'),
(16246, '78:59:3E', 'RAFI GmbH &amp; Co.KG'),
(16247, '78:59:5E', 'Samsung Electronics Co.,Ltd'),
(16248, '78:59:68', 'Hon Hai Precision Ind.Co.,Ltd.'),
(16249, '78:5C:72', 'Hioso Technology Co., Ltd.'),
(16250, '78:61:7C', 'MITSUMI ELECTRIC CO.,LTD'),
(16251, '78:66:AE', 'ZTEC Instruments, Inc.'),
(16252, '78:6A:89', 'Huawei Technologies Co., Ltd'),
(16253, '78:6C:1C', 'Apple'),
(16254, '78:71:9C', 'ARRIS Group, Inc.'),
(16255, '78:7E:61', 'Apple'),
(16256, '78:7F:62', 'GiK mbH'),
(16257, '78:81:8F', 'Server Racks Australia Pty Ltd'),
(16258, '78:84:3C', 'Sony Corporation'),
(16259, '78:84:EE', 'INDRA ESPACIO S.A.'),
(16260, '78:89:73', 'CMC'),
(16261, '78:8C:54', 'Eltek Technologies LTD'),
(16262, '78:8D:F7', 'Hitron Technologies. Inc'),
(16263, '78:92:3E', 'Nokia Corporation'),
(16264, '78:92:9C', 'Intel Corporate'),
(16265, '78:96:84', 'ARRIS Group, Inc.'),
(16266, '78:98:FD', 'Q9 Networks Inc.'),
(16267, '78:99:5C', 'Nationz Technologies Inc'),
(16268, '78:99:66', 'Musilab Electronics (DongGuan)Co.,Ltd.'),
(16269, '78:99:8F', 'MEDILINE ITALIA SRL'),
(16270, '78:9C:E7', 'Shenzhen Aikede Technology Co., Ltd'),
(16271, '78:9E:D0', 'Samsung Electronics'),
(16272, '78:9F:4C', 'HOERBIGER Elektronik GmbH'),
(16273, '78:9F:87', 'Siemens AG I IA PP PRM'),
(16274, '78:A0:51', 'iiNet Labs Pty Ltd'),
(16275, '78:A1:06', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(16276, '78:A1:83', 'Advidia'),
(16277, '78:A2:A0', 'Nintendo Co., Ltd.'),
(16278, '78:A3:51', 'SHENZHEN ZHIBOTONG ELECTRONICS CO.,LTD'),
(16279, '78:A3:E4', 'Apple'),
(16280, '78:A5:04', 'Texas Instruments'),
(16281, '78:A5:DD', 'Shenzhen Smarteye Digital Electronics Co., Ltd'),
(16282, '78:A6:83', 'Precidata'),
(16283, '78:A6:BD', 'DAEYEON Control&amp;Instrument Co,.Ltd'),
(16284, '78:A7:14', 'Amphenol'),
(16285, '78:A8:73', 'Samsung Electronics Co.,Ltd'),
(16286, '78:AB:60', 'ABB Australia'),
(16287, '78:AB:BB', 'Samsung Electronics Co.,LTD'),
(16288, '78:AC:BF', 'Igneous Systems'),
(16289, '78:AC:C0', 'Hewlett-Packard Company'),
(16290, '78:AE:0C', 'Far South Networks'),
(16291, '78:B3:B9', 'ShangHai sunup lighting CO.,LTD'),
(16292, '78:B3:CE', 'Elo touch solutions'),
(16293, '78:B5:D2', 'Ever Treasure Industrial Limited'),
(16294, '78:B6:C1', 'AOBO Telecom Co.,Ltd'),
(16295, '78:B8:1A', 'INTER SALES A/S'),
(16296, '78:BA:D0', 'Shinybow Technology Co. Ltd.'),
(16297, '78:BE:B6', 'Enhanced Vision'),
(16298, '78:BE:BD', 'STULZ GmbH'),
(16299, '78:C4:0E', 'H&amp;D Wireless'),
(16300, '78:C4:AB', 'Shenzhen Runsil Technology Co.,Ltd'),
(16301, '78:C5:E5', 'Texas Instruments'),
(16302, '78:C6:BB', 'Innovasic, Inc.'),
(16303, '78:CA:04', 'Nokia Corporation'),
(16304, '78:CA:39', 'Apple'),
(16305, '78:CA:5E', 'ELNO'),
(16306, '78:CB:33', 'DHC Software Co.,Ltd'),
(16307, '78:CD:8E', 'SMC Networks Inc'),
(16308, '78:D0:04', 'Neousys Technology Inc.'),
(16309, '78:D1:29', 'Vicos'),
(16310, '78:D3:4F', 'Pace-O-Matic, Inc.'),
(16311, '78:D3:8D', 'HONGKONG YUNLINK TECHNOLOGY LIMITED'),
(16312, '78:D5:B5', 'NAVIELEKTRO KY'),
(16313, '78:D6:6F', 'Aristocrat Technologies Australia Pty. Ltd.'),
(16314, '78:D6:F0', 'Samsung Electro Mechanics'),
(16315, '78:D7:52', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16316, '78:D9:9F', 'NuCom HK Ltd.'),
(16317, '78:DA:6E', 'Cisco'),
(16318, '78:DA:B3', 'GBO Technology'),
(16319, '78:DD:08', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16320, '78:DD:D6', 'c-scape'),
(16321, '78:DE:E4', 'Texas Instruments'),
(16322, '78:E3:B5', 'Hewlett-Packard Company'),
(16323, '78:E4:00', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16324, '78:E7:D1', 'Hewlett-Packard Company'),
(16325, '78:E8:B6', 'zte corporation'),
(16326, '78:E9:80', 'RainUs Co.,Ltd'),
(16327, '78:EB:14', 'SHENZHEN FAST TECHNOLOGIES CO.,LTD'),
(16328, '78:EC:22', 'Shanghai Qihui Telecom Technology Co., LTD'),
(16329, '78:EC:74', 'Kyland-USA'),
(16330, '78:EF:4C', 'Unetconvergence Co., Ltd.'),
(16331, '78:F5:E5', 'BEGA Gantenbrink-Leuchten KG'),
(16332, '78:F5:FD', 'Huawei Technologies Co., Ltd'),
(16333, '78:F7:BE', 'Samsung Electronics Co.,Ltd'),
(16334, '78:F7:D0', 'Silverbrook Research'),
(16335, '78:FC:14', 'B Communications Pty Ltd'),
(16336, '78:FD:94', 'Apple'),
(16337, '78:FE:3D', 'Juniper Networks'),
(16338, '78:FE:41', 'Socus networks'),
(16339, '78:FE:E2', 'Shanghai Diveo Technology Co., Ltd'),
(16340, '78:FF:57', 'Intel Corporate'),
(16341, '7C:01:87', 'Curtis Instruments, Inc.'),
(16342, '7C:02:BC', 'Hansung Electronics Co. LTD'),
(16343, '7C:03:4C', 'SAGEMCOM'),
(16344, '7C:03:D8', 'SAGEMCOM SAS'),
(16345, '7C:05:07', 'PEGATRON CORPORATION'),
(16346, '7C:05:1E', 'RAFAEL LTD.'),
(16347, '7C:06:23', 'Ultra Electronics, CIS'),
(16348, '7C:08:D9', 'Shanghai B-Star Technology Co'),
(16349, '7C:09:2B', 'Bekey A/S'),
(16350, '7C:0A:50', 'J-MEX Inc.'),
(16351, '7C:0E:CE', 'Cisco'),
(16352, '7C:11:BE', 'Apple'),
(16353, '7C:11:CD', 'QianTang Technology'),
(16354, '7C:14:76', 'Damall Technologies SAS'),
(16355, '7C:16:0D', 'Saia-Burgess Controls AG'),
(16356, '7C:1A:03', '8Locations Co., Ltd.'),
(16357, '7C:1A:FC', 'Dalian Co-Edifice Video Technology Co., Ltd'),
(16358, '7C:1D:D9', 'XIAOMI IMC'),
(16359, '7C:1E:52', 'Microsoft'),
(16360, '7C:1E:B3', '2N TELEKOMUNIKACE a.s.'),
(16361, '7C:20:48', 'KoamTac'),
(16362, '7C:20:64', 'Alcatel Lucent IPD'),
(16363, '7C:25:87', 'chaowifi.com'),
(16364, '7C:2C:F3', 'Secure Electrans Ltd'),
(16365, '7C:2E:0D', 'Blackmagic Design'),
(16366, '7C:2F:80', 'Gigaset Communications GmbH'),
(16367, '7C:33:6E', 'MEG Electronics Inc.'),
(16368, '7C:38:6C', 'Real Time Logic'),
(16369, '7C:39:20', 'SSOMA SECURITY'),
(16370, '7C:3B:D5', 'Imago Group'),
(16371, '7C:3C:B6', 'Shenzhen Homecare Technology Co.,Ltd.'),
(16372, '7C:3E:9D', 'PATECH'),
(16373, '7C:43:8F', 'E-Band Communications Corp.'),
(16374, '7C:44:4C', 'Entertainment Solutions, S.L.'),
(16375, '7C:49:B9', 'Plexus Manufacturing Sdn Bhd'),
(16376, '7C:4A:82', 'Portsmith LLC'),
(16377, '7C:4A:A8', 'MindTree Wireless PVT Ltd'),
(16378, '7C:4B:78', 'Red Sun Synthesis Pte Ltd'),
(16379, '7C:4C:58', 'Scale Computing, Inc.'),
(16380, '7C:4C:A5', 'BSkyB Ltd'),
(16381, '7C:4F:B5', 'Arcadyan Technology Corporation'),
(16382, '7C:53:4A', 'Metamako'),
(16383, '7C:55:E7', 'YSI, Inc.'),
(16384, '7C:5C:F8', 'Intel Corporate'),
(16385, '7C:60:97', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16386, '7C:61:93', 'HTC Corporation'),
(16387, '7C:66:9D', 'Texas Instruments'),
(16388, '7C:69:F6', 'Cisco'),
(16389, '7C:6A:B3', 'IBC TECHNOLOGIES INC.'),
(16390, '7C:6A:C3', 'GatesAir, Inc'),
(16391, '7C:6A:DB', 'SafeTone Technology Co.,Ltd'),
(16392, '7C:6B:33', 'Tenyu Tech Co. Ltd.'),
(16393, '7C:6B:52', 'Tigaro Wireless'),
(16394, '7C:6C:39', 'PIXSYS SRL'),
(16395, '7C:6C:8F', 'AMS NEVE LTD'),
(16396, '7C:6D:62', 'Apple'),
(16397, '7C:6D:F8', 'Apple'),
(16398, '7C:6F:06', 'Caterpillar Trimble Control Technologies'),
(16399, '7C:6F:F8', 'ShenZhen ACTO Digital Video Technology Co.,Ltd.'),
(16400, '7C:70:BC', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(16401, '7C:72:E4', 'Unikey Technologies'),
(16402, '7C:76:73', 'ENMAS GmbH'),
(16403, '7C:7A:53', 'Phytrex Technology Corp.'),
(16404, '7C:7A:91', 'Intel Corporate'),
(16405, '7C:7B:E4', 'Z\'SEDAI KENKYUSHO CORPORATION'),
(16406, '7C:7D:41', 'Jinmuyu Electronics Co., Ltd.'),
(16407, '7C:82:2D', 'Nortec'),
(16408, '7C:82:74', 'Shenzhen Hikeen Technology CO.,LTD'),
(16409, '7C:83:06', 'Glen Dimplex Nordic as'),
(16410, '7C:8D:91', 'Shanghai Hongzhuo Information Technology co.,LTD'),
(16411, '7C:8E:E4', 'Texas Instruments'),
(16412, '7C:94:B2', 'Philips Healthcare PCCI'),
(16413, '7C:95:F3', 'Cisco'),
(16414, '7C:97:63', 'Openmatics s.r.o.'),
(16415, '7C:9A:9B', 'VSE valencia smart energy'),
(16416, '7C:A1:5D', 'GN ReSound A/S'),
(16417, '7C:A2:9B', 'D.SignT GmbH &amp; Co. KG'),
(16418, '7C:A6:1D', 'MHL, LLC'),
(16419, '7C:AC:B2', 'Bosch Software Innovations GmbH'),
(16420, '7C:AD:74', 'Cisco'),
(16421, '7C:B0:3E', 'OSRAM GmbH'),
(16422, '7C:B1:77', 'Satelco AG'),
(16423, '7C:B2:1B', 'Cisco SPVTG'),
(16424, '7C:B2:32', 'TCL King High Frequency EI,Co.,LTD'),
(16425, '7C:B5:42', 'ACES Technology'),
(16426, '7C:B7:33', 'ASKEY COMPUTER CORP'),
(16427, '7C:B7:7B', 'Paradigm Electronics Inc'),
(16428, '7C:BB:6F', 'Cosco Electronics Co., Ltd.'),
(16429, '7C:BD:06', 'AE REFUsol'),
(16430, '7C:BF:88', 'Mobilicom LTD'),
(16431, '7C:BF:B1', 'ARRIS Group, Inc.'),
(16432, '7C:C3:A1', 'Apple'),
(16433, '7C:C4:EF', 'Devialet'),
(16434, '7C:C5:37', 'Apple'),
(16435, '7C:C7:09', 'Shenzhen RF-LINK Elec&amp;Technology.,Ltd'),
(16436, '7C:C8:AB', 'Acro Associates, Inc.'),
(16437, '7C:C8:D0', 'TIANJIN YAAN TECHNOLOGY CO., LTD.'),
(16438, '7C:C8:D7', 'Damalisk'),
(16439, '7C:CB:0D', 'Antaira Technologies, LLC'),
(16440, '7C:CC:B8', 'Intel Corporate'),
(16441, '7C:CD:11', 'MS-Magnet'),
(16442, '7C:CD:3C', 'Guangzhou Juzing Technology Co., Ltd'),
(16443, '7C:CF:CF', 'Shanghai SEARI Intelligent System Co., Ltd'),
(16444, '7C:D1:C3', 'Apple'),
(16445, '7C:D3:0A', 'INVENTEC Corporation'),
(16446, '7C:D7:62', 'Freestyle Technology Pty Ltd'),
(16447, '7C:D8:44', 'Enmotus Inc'),
(16448, '7C:D9:FE', 'New Cosmos Electric Co., Ltd.'),
(16449, '7C:DA:84', 'Dongnian Networks Inc.'),
(16450, '7C:DD:11', 'Chongqing MAS SCI&amp;TECH.Co.,Ltd'),
(16451, '7C:DD:20', 'IOXOS Technologies S.A.'),
(16452, '7C:DD:90', 'Shenzhen Ogemray Technology Co., Ltd.'),
(16453, '7C:E0:44', 'NEON Inc'),
(16454, '7C:E1:FF', 'Computer Performance, Inc. DBA Digital Loggers, Inc.'),
(16455, '7C:E4:AA', 'PRIVATE'),
(16456, '7C:E5:24', 'Quirky, Inc.'),
(16457, '7C:E5:6B', 'ESEN Optoelectronics Technology Co.,Ltd.'),
(16458, '7C:E9:D3', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16459, '7C:EB:EA', 'ASCT'),
(16460, '7C:ED:8D', 'MICROSOFT'),
(16461, '7C:EF:18', 'Creative Product Design Pty. Ltd.'),
(16462, '7C:EF:8A', 'Inhon International Ltd.'),
(16463, '7C:F0:5F', 'Apple'),
(16464, '7C:F0:98', 'Bee Beans Technologies, Inc.'),
(16465, '7C:F0:BA', 'Linkwell Telesystems Pvt Ltd'),
(16466, '7C:F4:29', 'NUUO Inc.'),
(16467, '7C:FA:DF', 'Apple'),
(16468, '7C:FE:28', 'Salutron Inc.'),
(16469, '7C:FE:4E', 'Shenzhen Safe vision Technology Co.,LTD'),
(16470, '7C:FF:62', 'Huizhou Super Electron Technology Co.,Ltd.'),
(16471, '80:00:0B', 'Intel Corporate'),
(16472, '80:00:10', 'ATT BELL LABORATORIES'),
(16473, '80:00:6E', 'Apple'),
(16474, '80:05:DF', 'Montage Technology Group Limited'),
(16475, '80:07:A2', 'Esson Technology Inc.'),
(16476, '80:09:02', 'Keysight Technologies, Inc.'),
(16477, '80:0A:06', 'COMTEC co.,ltd'),
(16478, '80:0E:24', 'ForgetBox'),
(16479, '80:14:40', 'Sunlit System Technology Corp'),
(16480, '80:14:A8', 'Guangzhou V-SOLUTION Electronic Technology Co., Ltd.'),
(16481, '80:16:B7', 'Brunel University'),
(16482, '80:17:7D', 'Nortel Networks'),
(16483, '80:18:A7', 'Samsung Eletronics Co., Ltd'),
(16484, '80:19:34', 'Intel Corporate'),
(16485, '80:19:67', 'Shanghai Reallytek Information Technology  Co.,Ltd'),
(16486, '80:1D:AA', 'Avaya Inc'),
(16487, '80:1F:02', 'Edimax Technology Co. Ltd.'),
(16488, '80:20:AF', 'Trade FIDES, a.s.'),
(16489, '80:22:75', 'Beijing Beny Wave Technology Co Ltd'),
(16490, '80:2A:A8', 'Ubiquiti Networks, Inc.'),
(16491, '80:2A:FA', 'Germaneers GmbH'),
(16492, '80:2D:E1', 'Solarbridge Technologies'),
(16493, '80:2E:14', 'azeti Networks AG'),
(16494, '80:2F:DE', 'Zurich Instruments AG'),
(16495, '80:34:57', 'OT Systems Limited'),
(16496, '80:37:73', 'Netgear Inc'),
(16497, '80:38:FD', 'LeapFrog Enterprises, Inc.'),
(16498, '80:39:E5', 'PATLITE CORPORATION'),
(16499, '80:3B:9A', 'ghe-ces electronic ag'),
(16500, '80:3F:5D', 'Winstars Technology Ltd'),
(16501, '80:3F:D6', 'bytes at work AG'),
(16502, '80:41:4E', 'BBK Electronics Corp., Ltd.,'),
(16503, '80:42:7C', 'Adolf Tedsen GmbH &amp; Co. KG'),
(16504, '80:47:31', 'Packet Design, Inc.'),
(16505, '80:48:A5', 'SICHUAN TIANYI COMHEART TELECOM CO.,LTD'),
(16506, '80:49:71', 'Apple'),
(16507, '80:4B:20', 'Ventilation Control'),
(16508, '80:4F:58', 'ThinkEco, Inc.'),
(16509, '80:50:1B', 'Nokia Corporation'),
(16510, '80:56:F2', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16511, '80:57:19', 'Samsung Electronics Co.,Ltd'),
(16512, '80:58:C5', 'NovaTec Kommunikationstechnik GmbH'),
(16513, '80:59:FD', 'Noviga'),
(16514, '80:60:07', 'RIM'),
(16515, '80:61:8F', 'Shenzhen sangfei consumer communications co.,ltd'),
(16516, '80:64:59', 'Nimbus Inc.'),
(16517, '80:65:E9', 'BenQ Corporation'),
(16518, '80:66:29', 'Prescope Technologies CO.,LTD.'),
(16519, '80:6A:B0', 'Tinno Mobile Technology Corp'),
(16520, '80:6C:1B', 'Motorola Mobility LLC'),
(16521, '80:6C:8B', 'KAESER KOMPRESSOREN AG'),
(16522, '80:6C:BC', 'NET New Electronic Technology GmbH'),
(16523, '80:71:1F', 'Juniper Networks'),
(16524, '80:71:7A', 'Huawei Technologies Co., Ltd'),
(16525, '80:74:59', 'K\'s Co.,Ltd.'),
(16526, '80:76:93', 'Newag SA'),
(16527, '80:79:AE', 'ShanDong Tecsunrise  Co.,Ltd'),
(16528, '80:7A:7F', 'ABB Genway Xiamen Electrical Equipment CO., LTD'),
(16529, '80:7B:1E', 'Corsair Components'),
(16530, '80:7D:1B', 'Neosystem Co. Ltd.'),
(16531, '80:7D:E3', 'Chongqing Sichuan Instrument Microcircuit Co.LTD.'),
(16532, '80:81:A5', 'TONGQING COMMUNICATION EQUIPMENT (SHENZHEN) Co.,Ltd'),
(16533, '80:82:87', 'ATCOM Technology Co.Ltd.'),
(16534, '80:86:98', 'Netronics Technologies Inc.'),
(16535, '80:86:F2', 'Intel Corporate'),
(16536, '80:89:17', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(16537, '80:8B:5C', 'Shenzhen Runhuicheng Technology Co., Ltd'),
(16538, '80:91:2A', 'Lih Rong electronic Enterprise Co., Ltd.'),
(16539, '80:91:C0', 'AgileMesh, Inc.'),
(16540, '80:92:9F', 'Apple'),
(16541, '80:93:93', 'Xapt GmbH'),
(16542, '80:94:6C', 'TOKYO RADAR CORPORATION'),
(16543, '80:96:B1', 'ARRIS Group, Inc.'),
(16544, '80:96:CA', 'Hon Hai Precision Ind Co.,Ltd'),
(16545, '80:97:1B', 'Altenergy Power System,Inc.'),
(16546, '80:9B:20', 'Intel Corporate'),
(16547, '80:A1:D7', 'Shanghai DareGlobal Technologies Co.,Ltd'),
(16548, '80:A8:5D', 'Osterhout Design Group'),
(16549, '80:AA:A4', 'USAG'),
(16550, '80:AD:67', 'Kasda Networks Inc'),
(16551, '80:B2:19', 'ELEKTRON TECHNOLOGY UK LIMITED'),
(16552, '80:B2:89', 'Forworld Electronics Ltd.'),
(16553, '80:B3:2A', 'Alstom Grid'),
(16554, '80:B6:86', 'Huawei Technologies Co., Ltd'),
(16555, '80:B9:5C', 'ELFTECH Co., Ltd.'),
(16556, '80:BA:AC', 'TeleAdapt Ltd'),
(16557, '80:BA:E6', 'Neets'),
(16558, '80:BB:EB', 'Satmap Systems Ltd'),
(16559, '80:BE:05', 'Apple'),
(16560, '80:C1:6E', 'Hewlett Packard'),
(16561, '80:C6:3F', 'Remec Broadband Wireless , LLC'),
(16562, '80:C6:AB', 'Technicolor USA Inc.'),
(16563, '80:C6:CA', 'Endian s.r.l.'),
(16564, '80:C8:62', 'Openpeak, Inc'),
(16565, '80:CE:B1', 'Theissen Training Systems GmbH'),
(16566, '80:CF:41', 'Lenovo Mobile Communication Technology Ltd.'),
(16567, '80:D0:19', 'Embed, Inc'),
(16568, '80:D0:9B', 'Huawei Technologies Co., Ltd'),
(16569, '80:D1:8B', 'Hangzhou I\'converge Technology Co.,Ltd'),
(16570, '80:D2:1D', 'AzureWave Technologies, Inc'),
(16571, '80:D4:33', 'LzLabs GmbH'),
(16572, '80:D7:33', 'QSR Automations, Inc.'),
(16573, '80:DB:31', 'Power Quotient International Co., Ltd.'),
(16574, '80:E6:50', 'Apple'),
(16575, '80:EA:96', 'Apple'),
(16576, '80:EA:CA', 'Dialog Semiconductor Hellas SA'),
(16577, '80:EE:73', 'Shuttle Inc.'),
(16578, '80:F2:5E', 'Kyynel'),
(16579, '80:F5:03', 'Pace plc'),
(16580, '80:F5:93', 'IRCO Sistemas de Telecomunicaci&oacute;n S.A.'),
(16581, '80:F6:2E', 'Hangzhou H3C Technologies Co., Limited'),
(16582, '80:F8:EB', 'RayTight'),
(16583, '80:FA:5B', 'CLEVO CO.'),
(16584, '80:FB:06', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16585, '80:FF:A8', 'UNIDIS'),
(16586, '84:00:D2', 'Sony Ericsson Mobile Communications AB'),
(16587, '84:01:A7', 'Greyware Automation Products, Inc'),
(16588, '84:0B:2D', 'SAMSUNG ELECTRO-MECHANICS CO., LTD'),
(16589, '84:0F:45', 'Shanghai GMT Digital Technologies Co., Ltd'),
(16590, '84:17:15', 'GP Electronics (HK) Ltd.'),
(16591, '84:17:66', 'Weifang GoerTek Electronics Co., Ltd'),
(16592, '84:18:26', 'Osram GmbH'),
(16593, '84:18:3A', 'Ruckus Wireless'),
(16594, '84:18:88', 'Juniper Networks'),
(16595, '84:1B:38', 'Shenzhen Excelsecu Data Technology Co.,Ltd'),
(16596, '84:1B:5E', 'NETGEAR'),
(16597, '84:1E:26', 'KERNEL-I Co.,LTD'),
(16598, '84:21:41', 'Shenzhen Ginwave Technologies Ltd.'),
(16599, '84:24:8D', 'Zebra Technologies Inc'),
(16600, '84:25:3F', 'Silex Technology, Inc'),
(16601, '84:25:A4', 'Tariox Limited'),
(16602, '84:25:DB', 'Samsung Electronics Co.,Ltd'),
(16603, '84:26:15', 'ADB Broadband Italia'),
(16604, '84:26:2B', 'Alcatel-Lucent'),
(16605, '84:26:90', 'BEIJING THOUGHT SCIENCE CO.,LTD.'),
(16606, '84:27:CE', 'Corporation of the Presiding Bishop of The Church of Jesus Christ of Latter-day Saints'),
(16607, '84:29:14', 'EMPORIA TELECOM Produktions- und VertriebsgesmbH &amp; Co KG'),
(16608, '84:29:99', 'Apple'),
(16609, '84:2B:2B', 'Dell Inc.'),
(16610, '84:2B:50', 'Huria Co.,Ltd.'),
(16611, '84:2B:BC', 'Modelleisenbahn GmbH'),
(16612, '84:2F:75', 'Innokas Group'),
(16613, '84:30:E5', 'SkyHawke Technologies, LLC'),
(16614, '84:32:EA', 'ANHUI WANZTEN P&amp;T CO., LTD'),
(16615, '84:34:97', 'Hewlett Packard'),
(16616, '84:36:11', 'hyungseul publishing networks'),
(16617, '84:38:35', 'Apple'),
(16618, '84:38:38', 'Samsung Electro Mechanics co., LTD.'),
(16619, '84:3A:4B', 'Intel Corporate'),
(16620, '84:3F:4E', 'Tri-Tech Manufacturing, Inc.'),
(16621, '84:44:64', 'ServerU Inc'),
(16622, '84:48:23', 'WOXTER TECHNOLOGY Co. Ltd'),
(16623, '84:49:15', 'vArmour Networks, Inc.'),
(16624, '84:4B:B7', 'Beijing Sankuai Online Technology Co.,Ltd'),
(16625, '84:4B:F5', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16626, '84:4F:03', 'Ablelink Electronics Ltd'),
(16627, '84:51:81', 'Samsung Electronics Co.,Ltd'),
(16628, '84:55:A5', 'Samsung Elec Co.,Ltd'),
(16629, '84:56:9C', 'Coho Data, Inc.,'),
(16630, '84:57:87', 'DVR C&amp;C Co., Ltd.'),
(16631, '84:5C:93', 'Chabrier Services'),
(16632, '84:5D:D7', 'Shenzhen Netcom Electronics Co.,Ltd'),
(16633, '84:61:A0', 'ARRIS Group, Inc.'),
(16634, '84:62:23', 'Shenzhen Coship Electronics Co., Ltd.'),
(16635, '84:62:A6', 'EuroCB (Phils), Inc.'),
(16636, '84:63:D6', 'Microsoft Corporation'),
(16637, '84:6A:ED', 'Wireless Tsukamoto.,co.LTD'),
(16638, '84:6E:B1', 'Park Assist LLC'),
(16639, '84:72:07', 'I&amp;C Technology'),
(16640, '84:73:03', 'Letv Mobile and Intelligent Information Technology (Beijing) Corporation Ltd.'),
(16641, '84:74:2A', 'zte corporation'),
(16642, '84:76:16', 'Addat S.r.o.'),
(16643, '84:78:8B', 'Apple'),
(16644, '84:78:AC', 'Cisco'),
(16645, '84:7A:88', 'HTC Corporation'),
(16646, '84:7E:40', 'Texas Instruments'),
(16647, '84:80:2D', 'Cisco'),
(16648, '84:82:F4', 'Beijing Huasun Unicreate Technology Co., Ltd'),
(16649, '84:83:36', 'Newrun'),
(16650, '84:83:71', 'Avaya, Inc'),
(16651, '84:84:33', 'Paradox Engineering SA'),
(16652, '84:85:06', 'Apple'),
(16653, '84:85:0A', 'Hella Sonnen- und Wetterschutztechnik GmbH'),
(16654, '84:86:F3', 'Greenvity Communications'),
(16655, '84:8D:84', 'Rajant Corporation'),
(16656, '84:8D:C7', 'Cisco SPVTG'),
(16657, '84:8E:0C', 'Apple'),
(16658, '84:8E:96', 'Embertec Pty Ltd'),
(16659, '84:8E:DF', 'Sony Mobile Communications AB'),
(16660, '84:8F:69', 'Dell Inc.'),
(16661, '84:90:00', 'Arnold &amp; Richter Cine Technik'),
(16662, '84:93:0C', 'InCoax Networks Europe AB'),
(16663, '84:94:8C', 'Hitron Technologies. Inc'),
(16664, '84:96:81', 'Cathay Communication Co.,Ltd'),
(16665, '84:96:D8', 'Pace plc'),
(16666, '84:97:B8', 'Memjet Inc.'),
(16667, '84:9C:A6', 'Arcadyan Technology Corporation'),
(16668, '84:9D:C5', 'Centera Photonics Inc.'),
(16669, '84:A4:66', 'Samsung Electronics Co.,Ltd'),
(16670, '84:A6:C8', 'Intel Corporate'),
(16671, '84:A7:83', 'Alcatel Lucent'),
(16672, '84:A8:E4', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(16673, '84:A9:91', 'Cyber Trans Japan Co.,Ltd.'),
(16674, '84:AC:A4', 'Beijing Novel Super Digital TV Technology Co., Ltd'),
(16675, '84:AF:1F', 'Beat System Service Co,. Ltd.'),
(16676, '84:B1:53', 'Apple'),
(16677, '84:B5:9C', 'Juniper networks'),
(16678, '84:C2:E4', 'Jiangsu Qinheng Co., Ltd.'),
(16679, '84:C3:E8', 'Vaillant GmbH'),
(16680, '84:C7:27', 'Gnodal Ltd'),
(16681, '84:C7:A9', 'C3PO S.A.'),
(16682, '84:C8:B1', 'Incognito Software Inc.'),
(16683, '84:C9:B2', 'D-Link International'),
(16684, '84:CF:BF', 'Fairphone'),
(16685, '84:D3:2A', 'IEEE 1905.1'),
(16686, '84:D9:C8', 'Unipattern Co.,'),
(16687, '84:DB:2F', 'Sierra Wireless Inc'),
(16688, '84:DB:AC', 'Huawei Technologies Co., Ltd'),
(16689, '84:DD:20', 'Texas Instruments'),
(16690, '84:DD:B7', 'Cilag GmbH International'),
(16691, '84:DE:3D', 'Crystal Vision Ltd'),
(16692, '84:DF:0C', 'NET2GRID BV'),
(16693, '84:E0:58', 'Pace plc'),
(16694, '84:E4:D9', 'Shenzhen NEED technology Ltd.'),
(16695, '84:E6:29', 'Bluwan SA'),
(16696, '84:E7:14', 'Liang Herng Enterprise,Co.Ltd.'),
(16697, '84:EA:99', 'Vieworks'),
(16698, '84:EB:18', 'Texas Instruments'),
(16699, '84:ED:33', 'BBMC Co.,Ltd'),
(16700, '84:F1:29', 'Metrascale Inc.'),
(16701, '84:F4:93', 'OMS spol. s.r.o.'),
(16702, '84:F6:4C', 'Cross Point BV'),
(16703, '84:FC:FE', 'Apple'),
(16704, '84:FE:9E', 'RTC Industries, Inc.'),
(16705, '88:03:55', 'Arcadyan Technology Corp.'),
(16706, '88:09:05', 'MTMCommunications'),
(16707, '88:0F:10', 'Huami Information Technology Co.,Ltd.'),
(16708, '88:0F:B6', 'Jabil Circuits India Pvt Ltd,-EHTP unit'),
(16709, '88:10:36', 'Panodic(ShenZhen) Electronics Limted'),
(16710, '88:12:4E', 'Qualcomm Atheros'),
(16711, '88:14:2B', 'Protonic Holland'),
(16712, '88:15:44', 'Meraki, Inc.'),
(16713, '88:18:AE', 'Tamron Co., Ltd'),
(16714, '88:1D:FC', 'Cisco'),
(16715, '88:1F:A1', 'Apple'),
(16716, '88:20:12', 'LMI Technologies'),
(16717, '88:21:E3', 'Nebusens, S.L.'),
(16718, '88:23:64', 'Watchnet DVR Inc'),
(16719, '88:23:FE', 'TTTech Computertechnik AG'),
(16720, '88:25:2C', 'Arcadyan Technology Corporation'),
(16721, '88:25:93', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(16722, '88:29:50', 'Dalian Netmoon Tech Develop Co.,Ltd'),
(16723, '88:2E:5A', 'storONE'),
(16724, '88:30:8A', 'Murata Manufactuaring Co.,Ltd.'),
(16725, '88:32:9B', 'Samsung Electro Mechanics co.,LTD.'),
(16726, '88:33:14', 'Texas Instruments'),
(16727, '88:35:4C', 'Transics'),
(16728, '88:36:12', 'SRC Computers, LLC'),
(16729, '88:3B:8B', 'Cheering Connection Co. Ltd.'),
(16730, '88:41:C1', 'ORBISAT DA AMAZONIA IND E AEROL SA'),
(16731, '88:41:FC', 'AirTies Wireless Netowrks'),
(16732, '88:43:E1', 'CISCO SYSTEMS, INC.'),
(16733, '88:44:F6', 'Nokia Corporation'),
(16734, '88:46:2A', 'Telechips Inc.'),
(16735, '88:4B:39', 'Siemens AG, Healthcare Sector'),
(16736, '88:51:FB', 'Hewlett Packard'),
(16737, '88:53:2E', 'Intel Corporate'),
(16738, '88:53:95', 'Apple'),
(16739, '88:53:D4', 'Huawei Technologies Co., Ltd'),
(16740, '88:57:6D', 'XTA Electronics Ltd'),
(16741, '88:5A:92', 'Cisco'),
(16742, '88:5B:DD', 'Aerohive Networks Inc.'),
(16743, '88:5C:47', 'Alcatel Lucent'),
(16744, '88:61:5A', 'Siano Mobile Silicon Ltd.'),
(16745, '88:63:DF', 'Apple'),
(16746, '88:68:5C', 'Shenzhen ChuangDao &amp; Perpetual Eternal Technology Co.,Ltd'),
(16747, '88:6B:76', 'CHINA HOPEFUL GROUP HOPEFUL ELECTRIC CO.,LTD'),
(16748, '88:70:8C', 'Lenovo Mobile Communication Technology Ltd.'),
(16749, '88:70:EF', 'SC Professional Trading Co., Ltd.'),
(16750, '88:73:98', 'K2E Tekpoint'),
(16751, '88:75:56', 'Cisco'),
(16752, '88:78:9C', 'Game Technologies SA'),
(16753, '88:86:03', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16754, '88:86:A0', 'Simton Technologies, Ltd.'),
(16755, '88:87:17', 'CANON INC.'),
(16756, '88:87:DD', 'DarbeeVision Inc.'),
(16757, '88:89:14', 'All Components Incorporated'),
(16758, '88:89:64', 'GSI Electronics Inc.'),
(16759, '88:8B:5D', 'Storage Appliance Corporation'),
(16760, '88:8C:19', 'Brady Corp Asia Pacific Ltd'),
(16761, '88:91:66', 'Viewcooper Corp.'),
(16762, '88:91:DD', 'Racktivity'),
(16763, '88:94:71', 'Brocade Communications Systems, Inc.'),
(16764, '88:94:F9', 'Gemicom Technology, Inc.'),
(16765, '88:95:B9', 'Unified Packet Systems Crop'),
(16766, '88:96:76', 'TTC MARCONI s.r.o.'),
(16767, '88:97:DF', 'Entrypass Corporation Sdn. Bhd.'),
(16768, '88:98:21', 'TERAON'),
(16769, '88:9B:39', 'Samsung Electronics Co.,Ltd'),
(16770, '88:9C:A6', 'BTB Korea INC'),
(16771, '88:9F:FA', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16772, '88:A3:CC', 'Amatis Controls'),
(16773, '88:A5:BD', 'QPCOM INC.'),
(16774, '88:A7:3C', 'Ragentek Technology Group'),
(16775, '88:AC:C1', 'Generiton Co., Ltd.'),
(16776, '88:AE:1D', 'COMPAL INFORMATION(KUNSHAN)CO.,LTD'),
(16777, '88:B1:68', 'Delta Control GmbH'),
(16778, '88:B1:E1', 'AirTight Networks, Inc.'),
(16779, '88:B6:27', 'Gembird Europe BV'),
(16780, '88:BA:7F', 'Qfiednet Co., Ltd.'),
(16781, '88:BF:D5', 'Simple Audio Ltd'),
(16782, '88:C3:6E', 'Beijing Ereneben lnformation Technology Limited'),
(16783, '88:C6:26', 'Logitech - Ultimate Ears'),
(16784, '88:C6:63', 'Apple'),
(16785, '88:C9:D0', 'LG Electronics'),
(16786, '88:CB:87', 'Apple'),
(16787, '88:CE:FA', 'Huawei Technologies Co., Ltd'),
(16788, '88:D7:BC', 'DEP Company'),
(16789, '88:D9:62', 'Canopus Systems US LLC'),
(16790, '88:DC:96', 'SENAO Networks, Inc.'),
(16791, '88:DD:79', 'Voltaire'),
(16792, '88:E0:A0', 'Shenzhen VisionSTOR Technologies Co., Ltd'),
(16793, '88:E0:F3', 'Juniper Networks'),
(16794, '88:E1:61', 'Art Beijing Science and Technology Development Co., Ltd.'),
(16795, '88:E3:AB', 'Huawei Technologies Co., Ltd'),
(16796, '88:E6:03', 'Avotek corporation'),
(16797, '88:E7:12', 'Whirlpool Corporation'),
(16798, '88:E7:A6', 'iKnowledge Integration Corp.'),
(16799, '88:E8:F8', 'YONG TAI ELECTRONIC (DONGGUAN) LTD.'),
(16800, '88:E9:17', 'Tamaggo'),
(16801, '88:ED:1C', 'Cudo Communication Co., Ltd.'),
(16802, '88:F0:31', 'Cisco'),
(16803, '88:F0:77', 'CISCO SYSTEMS, INC.'),
(16804, '88:F4:88', 'cellon communications technology(shenzhen)Co.,Ltd.'),
(16805, '88:F4:90', 'Jetmobile Pte Ltd'),
(16806, '88:F7:C7', 'Technicolor USA Inc.'),
(16807, '88:FD:15', 'LINEEYE CO., LTD'),
(16808, '88:FE:D6', 'ShangHai WangYong Software Co., Ltd.'),
(16809, '8C:00:6D', 'Apple'),
(16810, '8C:04:FF', 'Technicolor USA Inc.'),
(16811, '8C:05:51', 'Koubachi AG'),
(16812, '8C:07:8C', 'FLOW DATA INC'),
(16813, '8C:08:8B', 'Remote Solution'),
(16814, '8C:09:F4', 'ARRIS Group, Inc.'),
(16815, '8C:0C:90', 'Ruckus Wireless'),
(16816, '8C:0C:A3', 'Amper'),
(16817, '8C:0E:E3', 'GUANGDONG OPPO MOBILE TELECOMMUNICATIONS CORP.,LTD.'),
(16818, '8C:11:CB', 'ABUS Security-Center GmbH &amp; Co. KG'),
(16819, '8C:18:D9', 'Shenzhen RF Technology Co., Ltd'),
(16820, '8C:1F:94', 'RF Surgical System Inc.'),
(16821, '8C:21:0A', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(16822, '8C:27:1D', 'QuantHouse'),
(16823, '8C:27:8A', 'Vocollect Inc'),
(16824, '8C:29:37', 'Apple'),
(16825, '8C:2D:AA', 'Apple'),
(16826, '8C:2F:39', 'IBA Dosimetry GmbH'),
(16827, '8C:33:30', 'EmFirst Co., Ltd.'),
(16828, '8C:33:57', 'HiteVision Digital Media Technology Co.,Ltd.'),
(16829, '8C:3A:E3', 'LG Electronics'),
(16830, '8C:3C:07', 'Skiva Technologies, Inc.'),
(16831, '8C:3C:4A', 'NAKAYO TELECOMMUNICATIONS,INC.'),
(16832, '8C:41:F2', 'RDA Technologies Ltd.'),
(16833, '8C:44:35', 'Shanghai BroadMobi Communication Technology Co., Ltd.'),
(16834, '8C:4A:EE', 'GIGA TMS INC'),
(16835, '8C:4B:59', '3D Imaging &amp; Simulations Corp'),
(16836, '8C:4C:DC', 'PLANEX COMMUNICATIONS INC.'),
(16837, '8C:4D:B9', 'Unmonday Ltd'),
(16838, '8C:4D:EA', 'Cerio Corporation'),
(16839, '8C:51:05', 'Shenzhen ireadygo Information Technology CO.,LTD.'),
(16840, '8C:53:F7', 'A&amp;D ENGINEERING CO., LTD.'),
(16841, '8C:54:1D', 'LGE'),
(16842, '8C:56:9D', 'Imaging Solutions Group'),
(16843, '8C:56:C5', 'Nintendo Co., Ltd.'),
(16844, '8C:57:FD', 'LVX Western'),
(16845, '8C:58:77', 'Apple'),
(16846, '8C:59:8B', 'C Technologies AB'),
(16847, '8C:5A:F0', 'Exeltech Solar Products'),
(16848, '8C:5C:A1', 'd-broad,INC'),
(16849, '8C:5D:60', 'UCI Corporation Co.,Ltd.'),
(16850, '8C:5F:DF', 'Beijing Railway Signal Factory'),
(16851, '8C:60:4F', 'CISCO SYSTEMS, INC.'),
(16852, '8C:64:0B', 'Beyond Devices d.o.o.'),
(16853, '8C:64:22', 'Sony Ericsson Mobile Communications AB'),
(16854, '8C:68:78', 'Nortek-AS'),
(16855, '8C:6A:E4', 'Viogem Limited'),
(16856, '8C:70:5A', 'Intel Corporate'),
(16857, '8C:71:F8', 'Samsung Electronics Co.,Ltd'),
(16858, '8C:73:6E', 'Fujitsu Limited'),
(16859, '8C:76:C1', 'Goden Tech Limited'),
(16860, '8C:77:12', 'Samsung Electronics Co.,Ltd'),
(16861, '8C:77:16', 'LONGCHEER TELECOMMUNICATION LIMITED'),
(16862, '8C:7B:9D', 'Apple'),
(16863, '8C:7C:92', 'Apple'),
(16864, '8C:7C:B5', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16865, '8C:7C:FF', 'Brocade Communications Systems, Inc.'),
(16866, '8C:7E:B3', 'Lytro, Inc.'),
(16867, '8C:7F:3B', 'ARRIS Group, Inc.'),
(16868, '8C:82:A8', 'Insigma Technology Co.,Ltd'),
(16869, '8C:84:01', 'PRIVATE'),
(16870, '8C:87:3B', 'Leica Camera AG'),
(16871, '8C:89:A5', 'Micro-Star INT\'L CO., LTD'),
(16872, '8C:8A:6E', 'ESTUN AUTOMATION TECHNOLOY CO., LTD'),
(16873, '8C:8E:76', 'taskit GmbH'),
(16874, '8C:90:D3', 'Alcatel Lucent'),
(16875, '8C:91:09', 'Toyoshima Electric Technoeogy(Suzhou) Co.,Ltd.'),
(16876, '8C:92:36', 'Aus.Linx Technology Co., Ltd.'),
(16877, '8C:94:CF', 'Encell Technology, Inc.'),
(16878, '8C:A0:48', 'Beijing NeTopChip Technology Co.,LTD'),
(16879, '8C:A9:82', 'Intel Corporate'),
(16880, '8C:AE:4C', 'Plugable Technologies'),
(16881, '8C:AE:89', 'Y-cam Solutions Ltd'),
(16882, '8C:B0:94', 'Airtech I&amp;C Co., Ltd'),
(16883, '8C:B6:4F', 'CISCO SYSTEMS, INC.'),
(16884, '8C:B7:F7', 'Shenzhen UniStrong Science &amp; Technology Co., Ltd'),
(16885, '8C:B8:2C', 'IPitomy Communications'),
(16886, '8C:B8:64', 'AcSiP Technology Corp.'),
(16887, '8C:BE:BE', 'Xiaomi Technology Co.,Ltd'),
(16888, '8C:BF:9D', 'Shanghai Xinyou Information Technology Ltd. Co.'),
(16889, '8C:BF:A6', 'Samsung Electronics Co.,Ltd'),
(16890, '8C:C1:21', 'Panasonic Corporation AVC Networks Company'),
(16891, '8C:C5:E1', 'ShenZhen Konka Telecommunication Technology Co.,Ltd'),
(16892, '8C:C7:AA', 'Radinet Communications Inc.'),
(16893, '8C:C7:D0', 'zhejiang ebang communication co.,ltd'),
(16894, '8C:C8:CD', 'Samsung Electronics Co., LTD'),
(16895, '8C:CD:A2', 'ACTP, Inc.'),
(16896, '8C:CD:E8', 'Nintendo Co., Ltd.'),
(16897, '8C:CF:5C', 'BEFEGA GmbH'),
(16898, '8C:D1:7B', 'CG Mobile'),
(16899, '8C:D3:A2', 'VisSim AS'),
(16900, '8C:D6:28', 'Ikor Metering'),
(16901, '8C:DB:25', 'ESG Solutions'),
(16902, '8C:DC:D4', 'Hewlett Packard'),
(16903, '8C:DD:8D', 'Wifly-City System Inc.'),
(16904, '8C:DE:52', 'ISSC Technologies Corp.'),
(16905, '8C:DE:99', 'Comlab Inc.'),
(16906, '8C:DF:9D', 'NEC Corporation'),
(16907, '8C:E0:81', 'zte corporation'),
(16908, '8C:E7:48', 'PRIVATE'),
(16909, '8C:E7:8C', 'DK Networks'),
(16910, '8C:E7:B3', 'Sonardyne International Ltd'),
(16911, '8C:EE:C6', 'Precepscion Pty. Ltd.'),
(16912, '8C:F8:13', 'ORANGE POLSKA'),
(16913, '8C:F9:45', 'Power Automation pte Ltd'),
(16914, '8C:F9:C9', 'MESADA Technology Co.,Ltd.'),
(16915, '8C:FA:BA', 'Apple'),
(16916, '8C:FD:F0', 'QUALCOMM Incorporated'),
(16917, '90:00:4E', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16918, '90:00:DB', 'Samsung Electronics Co.,Ltd'),
(16919, '90:01:3B', 'SAGEMCOM'),
(16920, '90:02:8A', 'Shenzhen Shidean Legrand Electronic Products Co.,Ltd'),
(16921, '90:02:A9', 'ZHEJIANG DAHUA TECHNOLOGY CO.,LTD'),
(16922, '90:03:B7', 'PARROT'),
(16923, '90:09:17', 'Far-sighted mobile'),
(16924, '90:0A:3A', 'PSG Plastic Service GmbH'),
(16925, '90:0C:B4', 'Alinket Electronic Technology Co., Ltd'),
(16926, '90:0D:66', 'Digimore Electronics Co., Ltd'),
(16927, '90:0D:CB', 'ARRIS Group, Inc.'),
(16928, '90:17:9B', 'Nanomegas'),
(16929, '90:17:AC', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(16930, '90:18:5E', 'Apex Tool Group GmbH &amp; Co OHG'),
(16931, '90:18:7C', 'Samsung Electro Mechanics co., LTD.'),
(16932, '90:18:AE', 'Shanghai Meridian Technologies, Co. Ltd.'),
(16933, '90:19:00', 'SCS SA'),
(16934, '90:1A:CA', 'ARRIS Group, Inc.'),
(16935, '90:1B:0E', 'Fujitsu Technology Solutions GmbH'),
(16936, '90:1D:27', 'zte corporation'),
(16937, '90:1E:DD', 'GREAT COMPUTER CORPORATION'),
(16938, '90:20:3A', 'BYD Precision Manufacture Co.,Ltd'),
(16939, '90:20:83', 'General Engine Management Systems Ltd.'),
(16940, '90:21:55', 'HTC Corporation'),
(16941, '90:21:81', 'Shanghai Huaqin Telecom Technology Co.,Ltd'),
(16942, '90:27:E4', 'Apple'),
(16943, '90:2B:34', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(16944, '90:2C:C7', 'C-MAX Asia Limited'),
(16945, '90:2E:87', 'LabJack'),
(16946, '90:31:CD', 'Onyx Healthcare Inc.'),
(16947, '90:34:2B', 'Gatekeeper Systems, Inc.'),
(16948, '90:34:FC', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16949, '90:35:6E', 'Vodafone Omnitel N.V.'),
(16950, '90:38:DF', 'Changzhou Tiannengbo System Co. Ltd.'),
(16951, '90:3A:A0', 'Alcatel-Lucent'),
(16952, '90:3C:92', 'Apple'),
(16953, '90:3C:AE', 'Yunnan KSEC Digital Technology Co.,Ltd.'),
(16954, '90:3D:5A', 'Shenzhen Wision Technology Holding Limited'),
(16955, '90:3D:6B', 'Zicon Technology Corp.'),
(16956, '90:3E:AB', 'ARRIS Group, Inc.'),
(16957, '90:45:06', 'Tokyo Boeki Medisys Inc.'),
(16958, '90:46:B7', 'Vadaro Pte Ltd'),
(16959, '90:47:16', 'RORZE CORPORATION'),
(16960, '90:48:9A', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16961, '90:49:FA', 'Intel Corporation'),
(16962, '90:4C:E5', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16963, '90:4E:2B', 'Huawei Technologies Co., Ltd'),
(16964, '90:50:7B', 'Advanced PANMOBIL Systems GmbH &amp; Co. KG'),
(16965, '90:51:3F', 'Elettronica Santerno SpA'),
(16966, '90:54:46', 'TES ELECTRONIC SOLUTIONS'),
(16967, '90:55:AE', 'Ericsson, EAB/RWI/K'),
(16968, '90:56:82', 'Lenbrook Industries Limited'),
(16969, '90:56:92', 'Autotalks Ltd.'),
(16970, '90:59:AF', 'Texas Instruments'),
(16971, '90:5F:2E', 'TCT Mobile Limited'),
(16972, '90:5F:8D', 'modas GmbH'),
(16973, '90:61:0C', 'Fida International (S) Pte Ltd'),
(16974, '90:67:17', 'Alphion India Private Limited'),
(16975, '90:67:B5', 'Alcatel-Lucent'),
(16976, '90:67:F3', 'Alcatel Lucent'),
(16977, '90:68:C3', 'Motorola Mobility LLC'),
(16978, '90:6D:C8', 'DLG Automa&ccedil;&atilde;o Industrial Ltda'),
(16979, '90:6E:BB', 'Hon Hai Precision Ind. Co.,Ltd.'),
(16980, '90:70:25', 'Garea Microsys Co.,Ltd.'),
(16981, '90:72:40', 'Apple'),
(16982, '90:79:90', 'Benchmark Electronics Romania SRL'),
(16983, '90:7A:0A', 'Gebr. Bode GmbH &amp; Co KG'),
(16984, '90:7A:28', 'Beijing Morncloud Information And Technology Co. Ltd.'),
(16985, '90:7A:F1', 'SNUPI Technologies'),
(16986, '90:7E:BA', 'UTEK TECHNOLOGY (SHENZHEN) CO.,LTD'),
(16987, '90:7F:61', 'Chicony Electronics Co., Ltd.'),
(16988, '90:82:60', 'IEEE 1904.1 Working Group'),
(16989, '90:83:7A', 'General Electric Water &amp; Process Technologies'),
(16990, '90:84:0D', 'Apple'),
(16991, '90:88:A2', 'IONICS TECHNOLOGY ME LTDA'),
(16992, '90:8C:09', 'Total Phase'),
(16993, '90:8C:44', 'H.K ZONGMU TECHNOLOGY CO., LTD.'),
(16994, '90:8C:63', 'GZ Weedong Networks Technology Co. , Ltd'),
(16995, '90:8D:1D', 'GH Technologies'),
(16996, '90:8D:6C', 'Apple'),
(16997, '90:8F:CF', 'UNO System Co., Ltd'),
(16998, '90:90:3C', 'TRISON TECHNOLOGY CORPORATION'),
(16999, '90:90:60', 'RSI VIDEO TECHNOLOGIES'),
(17000, '90:92:B4', 'Diehl BGT Defence GmbH &amp; Co. KG'),
(17001, '90:94:E4', 'D-Link International'),
(17002, '90:98:64', 'Impex-Sat GmbH&amp;amp;Co KG'),
(17003, '90:99:16', 'ELVEES NeoTek OJSC'),
(17004, '90:9D:E0', 'Newland Design + Assoc. Inc.'),
(17005, '90:9F:33', 'EFM Networks'),
(17006, '90:9F:43', 'Accutron Instruments Inc.'),
(17007, '90:A2:DA', 'GHEO SA'),
(17008, '90:A4:DE', 'Wistron Neweb Corp.'),
(17009, '90:A7:83', 'JSW PACIFIC CORPORATION'),
(17010, '90:A7:C1', 'Pakedge Device and Software Inc.'),
(17011, '90:AC:3F', 'BrightSign LLC'),
(17012, '90:AE:1B', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(17013, '90:B1:1C', 'Dell Inc.'),
(17014, '90:B1:34', 'ARRIS Group, Inc.'),
(17015, '90:B2:1F', 'Apple'),
(17016, '90:B6:86', 'Murata Manufacturing Co., Ltd.'),
(17017, '90:B8:D0', 'Joyent, Inc.'),
(17018, '90:B9:31', 'Apple, Inc'),
(17019, '90:B9:7D', 'Johnson Outdoors Marine Electronics d/b/a Minnkota'),
(17020, '90:C1:15', 'Sony Ericsson Mobile Communications AB'),
(17021, '90:C3:5F', 'Nanjing Jiahao Technology Co., Ltd.'),
(17022, '90:C7:92', 'ARRIS Group, Inc.'),
(17023, '90:CC:24', 'Synaptics, Inc'),
(17024, '90:CF:15', 'Nokia Corporation'),
(17025, '90:CF:6F', 'Dlogixs Co Ltd'),
(17026, '90:CF:7D', 'Qingdao Hisense Electric Co.,Ltd.'),
(17027, '90:D1:1B', 'Palomar Medical Technologies'),
(17028, '90:D7:4F', 'Bookeen'),
(17029, '90:D7:EB', 'Texas Instruments'),
(17030, '90:D8:52', 'Comtec Co., Ltd.'),
(17031, '90:D9:2C', 'HUG-WITSCHI AG'),
(17032, '90:DA:4E', 'AVANU'),
(17033, '90:DA:6A', 'FOCUS H&amp;S Co., Ltd.'),
(17034, '90:DB:46', 'E-LEAD ELECTRONIC CO., LTD'),
(17035, '90:DF:B7', 's.m.s smart microwave sensors GmbH'),
(17036, '90:E0:F0', 'IEEE 1722a Working Group'),
(17037, '90:E2:BA', 'Intel Corporate'),
(17038, '90:E6:BA', 'ASUSTek COMPUTER INC.'),
(17039, '90:E7:C4', 'HTC Corporation'),
(17040, '90:EA:60', 'SPI Lasers Ltd'),
(17041, '90:EF:68', 'ZyXEL Communications Corporation'),
(17042, '90:F1:AA', 'Samsung Electronics Co.,LTD'),
(17043, '90:F1:B0', 'Hangzhou Anheng Info&amp;Tech CO.,LTD'),
(17044, '90:F2:78', 'Radius Gateway'),
(17045, '90:F3:B7', 'Kirisun Communications Co., Ltd.'),
(17046, '90:F4:C1', 'Rand McNally'),
(17047, '90:F6:52', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(17048, '90:F7:2F', 'Phillips Machine &amp; Welding Co., Inc.'),
(17049, '90:FB:5B', 'Avaya, Inc'),
(17050, '90:FB:A6', 'Hon Hai Precision Ind.Co.Ltd'),
(17051, '90:FD:61', 'Apple'),
(17052, '90:FF:79', 'Metro Ethernet Forum'),
(17053, '94:00:70', 'Nokia Corporation'),
(17054, '94:01:49', 'AutoHotBox'),
(17055, '94:01:C2', 'Samsung Electronics Co.,Ltd'),
(17056, '94:04:9C', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(17057, '94:05:B6', 'Liling FullRiver Electronics &amp; Technology Ltd'),
(17058, '94:0B:2D', 'NetView Technologies(Shenzhen) Co., Ltd'),
(17059, '94:0B:D5', 'Himax Technologies, Inc'),
(17060, '94:0C:6D', 'TP-LINK Technologies Co.,Ltd.'),
(17061, '94:10:3E', 'Belkin International Inc.'),
(17062, '94:11:DA', 'ITF Fr&ouml;schl GmbH'),
(17063, '94:16:73', 'Point Core SARL'),
(17064, '94:1D:1C', 'TLab West Systems AB'),
(17065, '94:20:53', 'Nokia Corporation'),
(17066, '94:21:97', 'Stalmart Technology Limited'),
(17067, '94:23:6E', 'Shenzhen Junlan Electronic Ltd'),
(17068, '94:2E:17', 'Schneider Electric Canada Inc'),
(17069, '94:2E:63', 'Fins&eacute;cur'),
(17070, '94:31:9B', 'Alphatronics BV'),
(17071, '94:33:DD', 'Taco Electronic Solutions, Inc.'),
(17072, '94:35:0A', 'Samsung Electronics Co.,Ltd'),
(17073, '94:36:E0', 'Sichuan Bihong Broadcast &amp;amp; Television New Technologies Co.,Ltd'),
(17074, '94:39:E5', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17075, '94:3A:F0', 'Nokia Corporation'),
(17076, '94:3B:B1', 'KAONMEDIA'),
(17077, '94:40:A2', 'Anywave Communication Technologies, Inc.'),
(17078, '94:44:44', 'LG Innotek'),
(17079, '94:44:52', 'Belkin International Inc.'),
(17080, '94:46:96', 'BaudTec Corporation'),
(17081, '94:4A:09', 'BitWise Controls'),
(17082, '94:50:47', 'Rechnerbetriebsgruppe'),
(17083, '94:51:03', 'Samsung Electronics'),
(17084, '94:51:BF', 'Hyundai ESG'),
(17085, '94:54:93', 'Rigado, LLC'),
(17086, '94:59:2D', 'EKE Building Technology Systems Ltd'),
(17087, '94:5B:7E', 'TRILOBIT LTDA.'),
(17088, '94:61:24', 'Pason Systems'),
(17089, '94:62:69', 'Arris Group, Inc.'),
(17090, '94:63:D1', 'Samsung Electronics Co.,Ltd'),
(17091, '94:70:D2', 'WINFIRM TECHNOLOGY'),
(17092, '94:71:AC', 'TCT Mobile Limited'),
(17093, '94:75:6E', 'QinetiQ North America'),
(17094, '94:7C:3E', 'Polewall Norge AS'),
(17095, '94:81:A4', 'Azuray Technologies'),
(17096, '94:85:7A', 'Evantage Industries Corp'),
(17097, '94:86:D4', 'Surveillance Pro Corporation'),
(17098, '94:87:7C', 'ARRIS Group, Inc.'),
(17099, '94:88:54', 'Texas Instruments'),
(17100, '94:8B:03', 'EAGET Innovation and Technology Co., Ltd.'),
(17101, '94:8D:50', 'Beamex Oy Ab'),
(17102, '94:8E:89', 'INDUSTRIAS UNIDAS SA DE CV'),
(17103, '94:8F:EE', 'Hughes Telematics, Inc.'),
(17104, '94:94:26', 'Apple'),
(17105, '94:98:A2', 'Shanghai LISTEN TECH.LTD'),
(17106, '94:9B:FD', 'Trans New Technology, Inc.'),
(17107, '94:9C:55', 'Alta Data Technologies'),
(17108, '94:9F:3F', 'Optek Digital Technology company limited'),
(17109, '94:9F:B4', 'ChengDu JiaFaAnTai Technology Co.,Ltd'),
(17110, '94:A1:A2', 'AMPAK Technology Inc.'),
(17111, '94:A7:BC', 'BodyMedia, Inc.'),
(17112, '94:AA:B8', 'Joview(Beijing) Technology Co. Ltd.'),
(17113, '94:AC:CA', 'trivum technologies GmbH'),
(17114, '94:AE:61', 'Alcatel Lucent'),
(17115, '94:AE:E3', 'Belden Hirschmann Industries (Suzhou) Ltd.'),
(17116, '94:B4:0F', 'Aruba Networks'),
(17117, '94:B8:C5', 'RuggedCom Inc.'),
(17118, '94:B9:B4', 'Aptos Technology'),
(17119, '94:BA:31', 'Visiontec da Amaz&ocirc;nia Ltda.'),
(17120, '94:BA:56', 'Shenzhen Coship Electronics Co., Ltd.'),
(17121, '94:BF:1E', 'eflow Inc. / Smart Device Planning and Development Division'),
(17122, '94:BF:95', 'Shenzhen Coship Electronics Co., Ltd'),
(17123, '94:C0:14', 'Sorter Sp. j. Konrad Grzeszczyk MichaA, Ziomek'),
(17124, '94:C0:38', 'Tallac Networks'),
(17125, '94:C1:50', '2Wire Inc'),
(17126, '94:C3:E4', 'SCA Schucker Gmbh &amp; Co KG'),
(17127, '94:C4:E9', 'PowerLayer Microsystems HongKong Limited'),
(17128, '94:C6:EB', 'NOVA electronics, Inc.'),
(17129, '94:C7:AF', 'Raylios Technology'),
(17130, '94:C9:62', 'Teseq AG'),
(17131, '94:CA:0F', 'Honeywell Analytics'),
(17132, '94:CC:B9', 'ARRIS Group, Inc.'),
(17133, '94:CD:AC', 'Creowave Oy'),
(17134, '94:CE:2C', 'Sony Mobile Communications AB'),
(17135, '94:CE:31', 'CTS Limited'),
(17136, '94:D0:19', 'Cydle Corp.'),
(17137, '94:D4:17', 'GPI KOREA INC.'),
(17138, '94:D6:0E', 'shenzhen yunmao information technologies co., ltd'),
(17139, '94:D7:23', 'Shanghai DareGlobal Technologies Co., Ltd'),
(17140, '94:D7:71', 'Samsung Electronics Co.,Ltd'),
(17141, '94:D9:3C', 'ENELPS'),
(17142, '94:DB:49', 'SITCORP'),
(17143, '94:DB:C9', 'Azurewave'),
(17144, '94:DD:3F', 'A+V Link Technologies, Corp.'),
(17145, '94:DE:0E', 'SmartOptics AS'),
(17146, '94:DE:80', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(17147, '94:DF:4E', 'Wistron InfoComm(Kunshan)Co.,Ltd.'),
(17148, '94:DF:58', 'IJ Electron CO.,Ltd.'),
(17149, '94:E0:D0', 'HealthStream Taiwan Inc.'),
(17150, '94:E2:26', 'D. ORtiz Consulting, LLC'),
(17151, '94:E2:FD', 'Boge Kompressoren Otto Boge GmbH &amp; Co. KG'),
(17152, '94:E7:11', 'Xirka Dama Persada PT'),
(17153, '94:E8:48', 'FYLDE MICRO LTD'),
(17154, '94:E9:8C', 'Alcatel-Lucent'),
(17155, '94:EB:2C', 'Google Inc.'),
(17156, '94:EB:CD', 'Research In Motion Limited'),
(17157, '94:F1:9E', 'HUIZHOU MAORONG INTELLIGENT TECHNOLOGY CO.,LTD'),
(17158, '94:F6:92', 'Geminico co.,Ltd.'),
(17159, '94:F7:20', 'Tianjin Deviser Electronics Instrument Co., Ltd'),
(17160, '94:FA:E8', 'Shenzhen Eycom Technology Co., Ltd'),
(17161, '94:FB:B2', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(17162, '94:FD:1D', 'WhereWhen Corp'),
(17163, '94:FD:2E', 'Shanghai Uniscope Technologies Co.,Ltd'),
(17164, '94:FE:F4', 'SAGEMCOM'),
(17165, '98:02:84', 'Theobroma Systems GmbH'),
(17166, '98:02:D8', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(17167, '98:03:A0', 'ABB n.v. Power Quality Products'),
(17168, '98:03:D8', 'Apple'),
(17169, '98:0C:82', 'Samsung Electro Mechanics'),
(17170, '98:0D:2E', 'HTC Corporation'),
(17171, '98:0E:E4', 'PRIVATE'),
(17172, '98:10:94', 'Shenzhen Vsun communication technology Co.,ltd'),
(17173, '98:16:EC', 'IC Intracom'),
(17174, '98:1D:FA', 'Samsung Electronics Co.,Ltd'),
(17175, '98:20:8E', 'Definium Technologies'),
(17176, '98:26:2A', 'Applied Research Associates, Inc'),
(17177, '98:29:1D', 'Jaguar de Mexico, SA de CV'),
(17178, '98:29:3F', 'Fujian Start Computer Equipment Co.,Ltd'),
(17179, '98:2C:BE', '2Wire'),
(17180, '98:2D:56', 'Resolution Audio'),
(17181, '98:2F:3C', 'Sichuan Changhong Electric Ltd.'),
(17182, '98:30:00', 'Beijing KEMACOM Technologies Co., Ltd.'),
(17183, '98:30:71', 'DAIKYUNG VASCOM'),
(17184, '98:34:9D', 'Krauss Maffei Technologies GmbH'),
(17185, '98:35:71', 'Sub10 Systems Ltd'),
(17186, '98:35:B8', 'Assembled Products Corporation'),
(17187, '98:37:13', 'PT.Navicom Indonesia'),
(17188, '98:3B:16', 'AMPAK Technology Inc'),
(17189, '98:3F:9F', 'China SSJ (Suzhou) Network Technology Inc.'),
(17190, '98:42:46', 'SOL INDUSTRY PTE., LTD'),
(17191, '98:43:DA', 'INTERTECH'),
(17192, '98:47:3C', 'SHANGHAI SUNMON COMMUNICATION TECHNOGY CO.,LTD'),
(17193, '98:4A:47', 'CHG Hospital Beds'),
(17194, '98:4B:4A', 'ARRIS Group, Inc.'),
(17195, '98:4B:E1', 'Hewlett-Packard Company'),
(17196, '98:4C:04', 'Zhangzhou Keneng Electrical Equipment Co Ltd'),
(17197, '98:4C:D3', 'Mantis Deposition'),
(17198, '98:4E:97', 'Starlight Marketing (H. K.) Ltd.'),
(17199, '98:4F:EE', 'Intel Corporate'),
(17200, '98:52:B1', 'Samsung Electronics'),
(17201, '98:57:D3', 'HON HAI-CCPBG  PRECISION IND.CO.,LTD.'),
(17202, '98:58:8A', 'SYSGRATION Ltd.'),
(17203, '98:59:45', 'Texas Instruments'),
(17204, '98:5A:EB', 'Apple, Inc.'),
(17205, '98:5C:93', 'SBG Systems SAS'),
(17206, '98:5D:46', 'PeopleNet Communication'),
(17207, '98:5E:1B', 'ConversDigital Co., Ltd.'),
(17208, '98:60:22', 'EMW Co., Ltd.'),
(17209, '98:66:EA', 'Industrial Control Communications, Inc.'),
(17210, '98:6B:3D', 'ARRIS Group, Inc.'),
(17211, '98:6C:F5', 'zte corporation'),
(17212, '98:6D:C8', 'TOSHIBA MITSUBISHI-ELECTRIC INDUSTRIAL SYSTEMS CORPORATION'),
(17213, '98:73:C4', 'Sage Electronic Engineering LLC'),
(17214, '98:76:B6', 'Adafruit'),
(17215, '98:77:70', 'Pep Digital Technology (Guangzhou) Co., Ltd'),
(17216, '98:7E:46', 'Emizon Networks Limited'),
(17217, '98:82:17', 'Disruptive Ltd'),
(17218, '98:86:B1', 'Flyaudio corporation (China)'),
(17219, '98:89:ED', 'Anadem Information Inc.'),
(17220, '98:8B:5D', 'SAGEM COMMUNICATION'),
(17221, '98:8B:AD', 'Corintech Ltd.'),
(17222, '98:8E:34', 'ZHEJIANG BOXSAM ELECTRONIC CO.,LTD'),
(17223, '98:8E:4A', 'NOXUS(BEIJING) TECHNOLOGY CO.,LTD'),
(17224, '98:8E:DD', 'TE Connectivity Limerick'),
(17225, '98:90:80', 'Linkpower Network System Inc Ltd.'),
(17226, '98:90:96', 'Dell Inc'),
(17227, '98:93:CC', 'LG Electronics Inc.'),
(17228, '98:94:49', 'Skyworth Wireless Technology Ltd.'),
(17229, '98:A7:B0', 'MCST ZAO'),
(17230, '98:AA:D7', 'BLUE WAVE NETWORKING CO LTD'),
(17231, '98:B0:39', 'Alcatel-Lucent'),
(17232, '98:B8:E3', 'Apple'),
(17233, '98:BC:57', 'SVA TECHNOLOGIES CO.LTD'),
(17234, '98:BC:99', 'Edeltech Co.,Ltd.'),
(17235, '98:BE:94', 'IBM'),
(17236, '98:C0:EB', 'Global Regency Ltd'),
(17237, '98:C8:45', 'PacketAccess'),
(17238, '98:CD:B4', 'Virident Systems, Inc.'),
(17239, '98:D3:31', 'Shenzhen Bolutek Technology Co.,Ltd.'),
(17240, '98:D6:86', 'Chyi Lee industry Co., ltd.'),
(17241, '98:D6:BB', 'Apple'),
(17242, '98:D6:F7', 'LG Electronics'),
(17243, '98:D8:8C', 'Nortel Networks'),
(17244, '98:DA:92', 'Vuzix Corporation'),
(17245, '98:DC:D9', 'UNITEC Co., Ltd.'),
(17246, '98:E1:65', 'Accutome'),
(17247, '98:E7:9A', 'Foxconn(NanJing) Communication Co.,Ltd.'),
(17248, '98:EC:65', 'Cosesy ApS'),
(17249, '98:EE:CB', 'Wistron InfoComm(ZhongShan)Corporation'),
(17250, '98:F0:AB', 'Apple'),
(17251, '98:F1:70', 'Murata Manufacturing Co., Ltd.'),
(17252, '98:F5:37', 'zte corporation'),
(17253, '98:F5:A9', 'OHSUNG ELECTRONICS CO.,LTD.'),
(17254, '98:F8:C1', 'IDT Technology Limited'),
(17255, '98:F8:DB', 'Marini Impianti Industriali s.r.l.'),
(17256, '98:FA:E3', 'Xiaomi inc.'),
(17257, '98:FB:12', 'Grand Electronics (HK) Ltd'),
(17258, '98:FC:11', 'Cisco-Linksys, LLC'),
(17259, '98:FE:03', 'Ericsson - North America'),
(17260, '98:FE:94', 'Apple'),
(17261, '98:FF:6A', 'OTEC(Shanghai)Technology Co.,Ltd.'),
(17262, '98:FF:D0', 'Lenovo Mobile Communication Technology Ltd.'),
(17263, '9C:01:11', 'Shenzhen Newabel Electronic Co., Ltd.'),
(17264, '9C:02:98', 'Samsung Electronics Co.,Ltd'),
(17265, '9C:03:9E', 'Beijing Winchannel Software Technology Co., Ltd'),
(17266, '9C:04:73', 'Tecmobile (International) Ltd.'),
(17267, '9C:04:EB', 'Apple'),
(17268, '9C:06:6E', 'Hytera Communications Corporation Limited'),
(17269, '9C:0D:AC', 'Tymphany HK Limited'),
(17270, '9C:14:65', 'Edata Elektronik San. ve Tic. A.Å.'),
(17271, '9C:18:74', 'Nokia Danmark A/S'),
(17272, '9C:1C:12', 'Aruba Networks'),
(17273, '9C:1F:DD', 'Accupix Inc.'),
(17274, '9C:20:7B', 'Apple'),
(17275, '9C:21:6A', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(17276, '9C:22:0E', 'TASCAN Service GmbH'),
(17277, '9C:28:40', 'Discovery Technology,LTD..'),
(17278, '9C:28:BF', 'Continental Automotive Czech Republic s.r.o.'),
(17279, '9C:28:EF', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(17280, '9C:2A:70', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17281, '9C:30:66', 'RWE Effizienz GmbH'),
(17282, '9C:31:78', 'Foshan Huadian Intelligent Communications Teachnologies Co.,Ltd'),
(17283, '9C:31:B6', 'Kulite Semiconductor Products Inc'),
(17284, '9C:35:83', 'Nipro Diagnostics, Inc'),
(17285, '9C:35:EB', 'Apple, Inc.'),
(17286, '9C:3A:AF', 'Samsung Electronics Co.,Ltd'),
(17287, '9C:3E:AA', 'EnvyLogic Co.,Ltd.'),
(17288, '9C:41:7C', 'Hame  Technology Co.,  Limited'),
(17289, '9C:44:3D', 'CHENGDU XUGUANG TECHNOLOGY CO, LTD'),
(17290, '9C:44:A6', 'SwiftTest, Inc.'),
(17291, '9C:45:63', 'DIMEP Sistemas'),
(17292, '9C:4A:7B', 'Nokia Corporation'),
(17293, '9C:4C:AE', 'Mesa Labs'),
(17294, '9C:4E:20', 'CISCO SYSTEMS, INC.'),
(17295, '9C:4E:36', 'Intel Corporate'),
(17296, '9C:4E:8E', 'ALT Systems Ltd'),
(17297, '9C:4E:BF', 'BoxCast'),
(17298, '9C:53:CD', 'ENGICAM s.r.l.'),
(17299, '9C:54:1C', 'Shenzhen My-power Technology Co.,Ltd'),
(17300, '9C:54:CA', 'Zhengzhou VCOM Science and Technology Co.,Ltd'),
(17301, '9C:55:B4', 'I.S.E. S.r.l.'),
(17302, '9C:57:11', 'Feitian Xunda(Beijing) Aeronautical Information Technology Co., Ltd.'),
(17303, '9C:5B:96', 'NMR Corporation'),
(17304, '9C:5C:8D', 'FIREMAX IND&Uacute;STRIA E COM&Eacute;RCIO DE PRODUTOS ELETR&Ocirc;NICOS  LTDA'),
(17305, '9C:5D:12', 'Aerohive Networks Inc'),
(17306, '9C:5D:95', 'VTC Electronics Corp.'),
(17307, '9C:5E:73', 'Calibre UK Ltd'),
(17308, '9C:61:1D', 'Omni-ID USA, Inc.'),
(17309, '9C:64:5E', 'Harman Consumer Group'),
(17310, '9C:65:B0', 'Samsung Electronics Co.,Ltd'),
(17311, '9C:65:F9', 'AcSiP Technology Corp.'),
(17312, '9C:66:50', 'Glodio Technolies Co.,Ltd Tianjin Branch'),
(17313, '9C:68:5B', 'Octonion SA'),
(17314, '9C:6A:BE', 'QEES ApS.'),
(17315, '9C:6C:15', 'Microsoft Corporation'),
(17316, '9C:75:14', 'Wildix srl'),
(17317, '9C:77:AA', 'NADASNV'),
(17318, '9C:79:AC', 'Suntec Software(Shanghai) Co., Ltd.'),
(17319, '9C:7B:D2', 'NEOLAB Convergence'),
(17320, '9C:80:7D', 'SYSCABLE Korea Inc.'),
(17321, '9C:80:DF', 'Arcadyan Technology Corporation'),
(17322, '9C:86:DA', 'Phoenix Geophysics Ltd.'),
(17323, '9C:88:88', 'Simac Techniek NV'),
(17324, '9C:8B:F1', 'The Warehouse Limited'),
(17325, '9C:8D:1A', 'INTEG process group inc'),
(17326, '9C:8E:99', 'Hewlett-Packard Company'),
(17327, '9C:8E:DC', 'Teracom Limited'),
(17328, '9C:93:4E', 'Xerox Corporation'),
(17329, '9C:93:E4', 'PRIVATE'),
(17330, '9C:95:F8', 'SmartDoor Systems, LLC'),
(17331, '9C:97:26', 'Technicolor'),
(17332, '9C:98:11', 'Guangzhou Sunrise Electronics Development Co., Ltd'),
(17333, '9C:9C:1D', 'Starkey Labs Inc.'),
(17334, '9C:A1:0A', 'SCLE SFE'),
(17335, '9C:A1:34', 'Nike, Inc.'),
(17336, '9C:A3:BA', 'SAKURA Internet Inc.'),
(17337, '9C:A5:77', 'Osorno Enterprises Inc.'),
(17338, '9C:A9:E4', 'zte corporation'),
(17339, '9C:AD:97', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17340, '9C:AD:EF', 'Obihai Technology, Inc.'),
(17341, '9C:AF:CA', 'CISCO SYSTEMS, INC.'),
(17342, '9C:B0:08', 'Ubiquitous Computing Technology Corporation'),
(17343, '9C:B2:06', 'PROCENTEC'),
(17344, '9C:B6:54', 'Hewlett Packard'),
(17345, '9C:B7:0D', 'Liteon Technology Corporation'),
(17346, '9C:B7:93', 'Creatcomm Technology Inc.'),
(17347, '9C:BB:98', 'Shen Zhen RND Electronic Co.,LTD'),
(17348, '9C:BD:9D', 'SkyDisk, Inc.'),
(17349, '9C:C0:77', 'PrintCounts, LLC'),
(17350, '9C:C0:D2', 'Conductix-Wampfler GmbH'),
(17351, '9C:C1:72', 'Huawei Technologies Co., Ltd'),
(17352, '9C:C7:A6', 'AVM GmbH'),
(17353, '9C:C7:D1', 'SHARP Corporation'),
(17354, '9C:CA:D9', 'Nokia Corporation'),
(17355, '9C:CD:82', 'CHENG UEI PRECISION INDUSTRY CO.,LTD'),
(17356, '9C:D2:1E', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17357, '9C:D2:4B', 'zte corporation'),
(17358, '9C:D3:5B', 'Samsung Electronics Co.,Ltd'),
(17359, '9C:D3:6D', 'NETGEAR INC.,'),
(17360, '9C:D6:43', 'D-Link International'),
(17361, '9C:D9:17', 'Motorola Mobility LLC'),
(17362, '9C:DF:03', 'Harman/Becker Automotive Systems GmbH'),
(17363, '9C:E1:0E', 'NCTech Ltd'),
(17364, '9C:E1:D6', 'Junger Audio-Studiotechnik GmbH'),
(17365, '9C:E2:30', 'JULONG CO,.LTD.'),
(17366, '9C:E6:35', 'Nintendo Co., Ltd.'),
(17367, '9C:E6:E7', 'Samsung Electronics Co.,Ltd'),
(17368, '9C:E7:BD', 'Winduskorea co., Ltd'),
(17369, '9C:EB:E8', 'BizLink (Kunshan) Co.,Ltd'),
(17370, '9C:F3:87', 'Apple'),
(17371, '9C:F6:1A', 'UTC Fire and Security'),
(17372, '9C:F6:7D', 'Ricardo Prague, s.r.o.'),
(17373, '9C:F8:DB', 'shenzhen eyunmei technology co,.ltd'),
(17374, '9C:F9:38', 'AREVA NP GmbH'),
(17375, '9C:FB:F1', 'MESOMATIC GmbH &amp; Co.KG'),
(17376, '9C:FF:BE', 'OTSL Inc.'),
(17377, 'A0:02:DC', 'Amazon Technologies Inc.'),
(17378, 'A0:03:63', 'Robert Bosch Healthcare GmbH'),
(17379, 'A0:06:27', 'NEXPA System'),
(17380, 'A0:07:98', 'Samsung Electronics'),
(17381, 'A0:07:B6', 'Advanced Technical Support, Inc.'),
(17382, 'A0:0A:BF', 'Wieson Technologies Co., Ltd.'),
(17383, 'A0:0B:BA', 'SAMSUNG ELECTRO-MECHANICS'),
(17384, 'A0:0C:A1', 'SKTB SKiT'),
(17385, 'A0:12:90', 'Avaya, Inc'),
(17386, 'A0:12:DB', 'TABUCHI ELECTRIC CO.,LTD'),
(17387, 'A0:13:3B', 'Copyright &copy; HiTi Digital, Inc.'),
(17388, 'A0:14:3D', 'PARROT SA'),
(17389, 'A0:16:5C', 'Triteka LTD'),
(17390, 'A0:18:59', 'Shenzhen Yidashi Electronics Co Ltd'),
(17391, 'A0:19:17', 'Bertel S.p.a.'),
(17392, 'A0:1C:05', 'NIMAX TELECOM CO.,LTD.'),
(17393, 'A0:1D:48', 'Hewlett Packard'),
(17394, 'A0:21:95', 'Samsung Electronics Digital Imaging'),
(17395, 'A0:21:B7', 'NETGEAR'),
(17396, 'A0:23:1B', 'TeleComp R&amp;D Corp.'),
(17397, 'A0:2B:B8', 'Hewlett Packard'),
(17398, 'A0:2E:F3', 'United Integrated Services Co., Led.'),
(17399, 'A0:36:9F', 'Intel Corporate'),
(17400, 'A0:36:F0', 'Comprehensive Power'),
(17401, 'A0:36:FA', 'Ettus Research LLC'),
(17402, 'A0:3A:75', 'PSS Belgium N.V.'),
(17403, 'A0:3B:1B', 'Inspire Tech'),
(17404, 'A0:40:25', 'Actioncable, Inc.'),
(17405, 'A0:40:41', 'SAMWONFA Co.,Ltd.'),
(17406, 'A0:41:A7', 'NL Ministry of Defense'),
(17407, 'A0:42:3F', 'Tyan Computer Corp'),
(17408, 'A0:48:1C', 'Hewlett Packard'),
(17409, 'A0:4C:C1', 'Helixtech Corp.'),
(17410, 'A0:4E:04', 'Nokia Corporation'),
(17411, 'A0:51:C6', 'Avaya, Inc'),
(17412, 'A0:55:DE', 'Pace plc'),
(17413, 'A0:56:B2', 'Harman/Becker Automotive Systems GmbH'),
(17414, 'A0:59:3A', 'V.D.S. Video Display Systems srl'),
(17415, 'A0:5A:A4', 'Grand Products Nevada, Inc.'),
(17416, 'A0:5B:21', 'ENVINET GmbH'),
(17417, 'A0:5D:C1', 'TMCT Co., LTD.'),
(17418, 'A0:5D:E7', 'DIRECTV, Inc.'),
(17419, 'A0:5E:6B', 'MELPER Co., Ltd.'),
(17420, 'A0:63:91', 'Netgear Inc.'),
(17421, 'A0:65:18', 'VNPT TECHNOLOGY'),
(17422, 'A0:67:BE', 'Sicon s.r.l.'),
(17423, 'A0:69:86', 'Wellav Technologies Ltd'),
(17424, 'A0:6A:00', 'Verilink Corporation'),
(17425, 'A0:6C:EC', 'RIM'),
(17426, 'A0:6D:09', 'Intelcan Technosystems Inc.'),
(17427, 'A0:6E:50', 'Nanotek Elektronik Sistemler Ltd. Sti.'),
(17428, 'A0:71:A9', 'Nokia Corporation'),
(17429, 'A0:73:32', 'Cashmaster International Limited'),
(17430, 'A0:73:FC', 'Rancore Technologies Private Limited'),
(17431, 'A0:75:91', 'Samsung Electronics Co.,Ltd'),
(17432, 'A0:77:71', 'Vialis BV'),
(17433, 'A0:78:BA', 'Pantech Co., Ltd.'),
(17434, 'A0:82:1F', 'Samsung Electronics Co.,Ltd'),
(17435, 'A0:82:C7', 'P.T.I Co.,LTD'),
(17436, 'A0:86:1D', 'Chengdu Fuhuaxin Technology co.,Ltd'),
(17437, 'A0:86:EC', 'SAEHAN HITEC Co., Ltd'),
(17438, 'A0:88:69', 'Intel Corporate'),
(17439, 'A0:88:B4', 'Intel Corporate'),
(17440, 'A0:89:E4', 'Skyworth Digital Technology(Shenzhen) Co.,Ltd'),
(17441, 'A0:8A:87', 'HuiZhou KaiYue Electronic Co.,Ltd'),
(17442, 'A0:8C:15', 'Gerhard D. Wempe KG'),
(17443, 'A0:8C:9B', 'Xtreme Technologies Corp'),
(17444, 'A0:90:DE', 'VEEDIMS,LLC'),
(17445, 'A0:93:47', 'GUANGDONG OPPO MOBILE TELECOMMUNICATIONS CORP.,LTD.'),
(17446, 'A0:98:05', 'OpenVox Communication Co Ltd'),
(17447, 'A0:98:ED', 'Shandong Intelligent Optical Communication Development Co., Ltd.'),
(17448, 'A0:99:9B', 'Apple'),
(17449, 'A0:9A:5A', 'Time Domain'),
(17450, 'A0:9B:BD', 'Total Aviation Solutions Pty Ltd'),
(17451, 'A0:A1:30', 'DLI Taiwan Branch office'),
(17452, 'A0:A2:3C', 'GPMS'),
(17453, 'A0:A3:E2', 'Actiontec Electronics, Inc'),
(17454, 'A0:A7:63', 'Polytron Vertrieb GmbH'),
(17455, 'A0:A8:CD', 'Intel Corporate'),
(17456, 'A0:AA:FD', 'EraThink Technologies Corp.'),
(17457, 'A0:AD:A1', 'JMR Electronics, Inc'),
(17458, 'A0:B1:00', 'ShenZhen Cando Electronics Co.,Ltd'),
(17459, 'A0:B3:CC', 'Hewlett Packard'),
(17460, 'A0:B4:A5', 'Samsung Elec Co.,Ltd'),
(17461, 'A0:B5:DA', 'HongKong THTF Co., Ltd'),
(17462, 'A0:B6:62', 'Acutvista Innovation Co., Ltd.'),
(17463, 'A0:B9:ED', 'Skytap'),
(17464, 'A0:BA:B8', 'Pixon Imaging'),
(17465, 'A0:BB:3E', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(17466, 'A0:BF:50', 'S.C. ADD-PRODUCTION S.R.L.'),
(17467, 'A0:BF:A5', 'CORESYS'),
(17468, 'A0:C2:DE', 'Costar Video Systems'),
(17469, 'A0:C3:DE', 'Triton Electronic Systems Ltd.'),
(17470, 'A0:C5:62', 'Pace plc'),
(17471, 'A0:C6:EC', 'ShenZhen ANYK Technology Co.,LTD'),
(17472, 'A0:CE:C8', 'CE LINK LIMITED'),
(17473, 'A0:CF:5B', 'CISCO SYSTEMS, INC.'),
(17474, 'A0:D1:2A', 'AXPRO Technology Inc.'),
(17475, 'A0:D3:C1', 'Hewlett Packard'),
(17476, 'A0:DA:92', 'Nanjing Glarun Atten Technology Co. Ltd.'),
(17477, 'A0:DC:04', 'Becker-Antriebe GmbH'),
(17478, 'A0:DD:97', 'PolarLink Technologies, Ltd'),
(17479, 'A0:DD:E5', 'SHARP Corporation'),
(17480, 'A0:DE:05', 'JSC &quot;Irbis-T&quot;'),
(17481, 'A0:E2:01', 'AVTrace Ltd.(China)'),
(17482, 'A0:E2:5A', 'Amicus SK, s.r.o.'),
(17483, 'A0:E2:95', 'DAT System Co.,Ltd'),
(17484, 'A0:E4:53', 'Sony Mobile Communications AB'),
(17485, 'A0:E4:CB', 'ZyXEL Communications Corporation'),
(17486, 'A0:E5:34', 'Stratec Biomedical AG'),
(17487, 'A0:E5:E9', 'enimai Inc'),
(17488, 'A0:E6:F8', 'Texas Instruments Inc'),
(17489, 'A0:E9:DB', 'Ningbo FreeWings Technologies Co.,Ltd'),
(17490, 'A0:EB:76', 'AirCUVE Inc.'),
(17491, 'A0:EC:80', 'zte corporation'),
(17492, 'A0:EC:F9', 'Cisco'),
(17493, 'A0:ED:CD', 'Apple'),
(17494, 'A0:EF:84', 'Seine Image Int\'l Co., Ltd'),
(17495, 'A0:F2:17', 'GE Medical System(China) Co., Ltd.'),
(17496, 'A0:F3:C1', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(17497, 'A0:F3:E4', 'Alcatel Lucent IPD'),
(17498, 'A0:F4:19', 'Nokia Corporation'),
(17499, 'A0:F4:50', 'HTC Corporation'),
(17500, 'A0:F4:59', 'FN-LINK TECHNOLOGY LIMITED'),
(17501, 'A0:FC:6E', 'Telegrafia a.s.'),
(17502, 'A0:FE:91', 'AVAT Automation GmbH'),
(17503, 'A4:01:30', 'ABIsystems Co., LTD'),
(17504, 'A4:05:9E', 'STA Infinity LLP'),
(17505, 'A4:09:CB', 'Alfred Kaercher GmbH &amp;amp; Co KG'),
(17506, 'A4:0B:ED', 'Carry Technology Co.,Ltd'),
(17507, 'A4:0C:C3', 'CISCO SYSTEMS, INC.'),
(17508, 'A4:12:42', 'NEC Platforms, Ltd.'),
(17509, 'A4:13:4E', 'Luxul'),
(17510, 'A4:15:66', 'Wei Fang Goertek Electronics Co.,Ltd'),
(17511, 'A4:17:31', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17512, 'A4:18:75', 'CISCO SYSTEMS, INC.'),
(17513, 'A4:1B:C0', 'Fastec Imaging Corporation'),
(17514, 'A4:1F:72', 'Dell Inc.'),
(17515, 'A4:21:8A', 'Nortel Networks'),
(17516, 'A4:23:05', 'Open Networking Laboratory'),
(17517, 'A4:24:B3', 'FlatFrog Laboratories AB'),
(17518, 'A4:25:1B', 'Avaya, Inc'),
(17519, 'A4:29:40', 'Shenzhen YOUHUA Technology Co., Ltd'),
(17520, 'A4:29:B7', 'bluesky'),
(17521, 'A4:2B:8C', 'Netgear Inc'),
(17522, 'A4:2C:08', 'Masterwork Automodules'),
(17523, 'A4:33:D1', 'Fibrlink Communications Co.,Ltd.'),
(17524, 'A4:38:FC', 'Plastic Logic'),
(17525, 'A4:3A:69', 'Vers Inc'),
(17526, 'A4:3B:FA', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(17527, 'A4:3D:78', 'GUANGDONG OPPO MOBILE TELECOMMUNICATIONS CORP.,LTD'),
(17528, 'A4:46:6B', 'EOC Technology'),
(17529, 'A4:46:FA', 'AmTRAN Video Corporation'),
(17530, 'A4:4A:D3', 'ST Electronics(Shanghai) Co.,Ltd'),
(17531, 'A4:4B:15', 'Sun Cupid Technology (HK) LTD'),
(17532, 'A4:4C:11', 'CISCO SYSTEMS, INC.'),
(17533, 'A4:4E:2D', 'Adaptive Wireless Solutions, LLC'),
(17534, 'A4:4E:31', 'Intel Corporate'),
(17535, 'A4:4F:29', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(17536, 'A4:50:55', 'busware.de'),
(17537, 'A4:52:6F', 'ADB Broadband Italia'),
(17538, 'A4:56:1B', 'MCOT Corporation'),
(17539, 'A4:56:30', 'CISCO SYSTEMS, INC.'),
(17540, 'A4:5A:1C', 'smart-electronic GmbH'),
(17541, 'A4:5C:27', 'Nintendo Co., Ltd.'),
(17542, 'A4:5D:36', 'Hewlett Packard'),
(17543, 'A4:5D:A1', 'ADB Broadband Italia'),
(17544, 'A4:5E:60', 'Apple'),
(17545, 'A4:60:32', 'MRV Communications (Networks) LTD'),
(17546, 'A4:67:06', 'Apple'),
(17547, 'A4:6C:C1', 'LTi REEnergy GmbH'),
(17548, 'A4:6E:79', 'DFT System Co.Ltd'),
(17549, 'A4:70:D6', 'Motorola Mobility LLC'),
(17550, 'A4:77:33', 'Google'),
(17551, 'A4:77:60', 'Nokia Corporation'),
(17552, 'A4:79:E4', 'KLINFO Corp'),
(17553, 'A4:7A:A4', 'ARRIS Group, Inc.'),
(17554, 'A4:7A:CF', 'VIBICOM COMMUNICATIONS INC.'),
(17555, 'A4:7B:85', 'ULTIMEDIA Co Ltd,'),
(17556, 'A4:7C:14', 'ChargeStorm AB'),
(17557, 'A4:7C:1F', 'Cobham plc'),
(17558, 'A4:7E:39', 'zte corporation'),
(17559, 'A4:81:EE', 'Nokia Corporation'),
(17560, 'A4:85:6B', 'Q Electronics Ltd'),
(17561, 'A4:89:5B', 'ARK INFOSOLUTIONS PVT LTD'),
(17562, 'A4:8C:DB', 'Lenovo'),
(17563, 'A4:90:05', 'CHINA GREATWALL COMPUTER SHENZHEN CO.,LTD'),
(17564, 'A4:93:4C', 'CISCO SYSTEMS, INC.'),
(17565, 'A4:97:BB', 'Hitachi Industrial Equipment Systems Co.,Ltd'),
(17566, 'A4:99:47', 'Huawei Technologies Co., Ltd'),
(17567, 'A4:99:81', 'FuJian Elite Power Tech CO.,LTD.'),
(17568, 'A4:9A:58', 'Samsung Electronics Co.,Ltd'),
(17569, 'A4:9B:13', 'Burroughs Payment Systems, Inc.'),
(17570, 'A4:9D:49', 'Ketra, Inc.'),
(17571, 'A4:9E:DB', 'AutoCrib, Inc.'),
(17572, 'A4:9F:85', 'Lyve Minds, Inc'),
(17573, 'A4:9F:89', 'Shanghai Rui Rui Communication Technology Co.Ltd.'),
(17574, 'A4:A1:C2', 'Ericsson AB (EAB)'),
(17575, 'A4:A2:4A', 'Cisco SPVTG'),
(17576, 'A4:A4:D3', 'Bluebank Communication Technology Co.Ltd'),
(17577, 'A4:A8:0F', 'Shenzhen Coship Electronics Co., Ltd.'),
(17578, 'A4:AD:00', 'Ragsdale Technology'),
(17579, 'A4:AD:B8', 'Vitec Group, Camera Dynamics Ltd'),
(17580, 'A4:AE:9A', 'Maestro Wireless Solutions ltd.'),
(17581, 'A4:B1:21', 'Arantia 2010 S.L.'),
(17582, 'A4:B1:97', 'Apple'),
(17583, 'A4:B1:E9', 'Technicolor'),
(17584, 'A4:B1:EE', 'H. ZANDER GmbH &amp; Co. KG'),
(17585, 'A4:B2:A7', 'Adaxys Solutions AG'),
(17586, 'A4:B3:6A', 'JSC SDO Chromatec'),
(17587, 'A4:B8:18', 'PENTA Gesellschaft f&uuml;r elektronische Industriedatenverarbeitung mbH'),
(17588, 'A4:B9:80', 'Parking BOXX Inc.'),
(17589, 'A4:BA:DB', 'Dell Inc.'),
(17590, 'A4:BB:AF', 'Lime Instruments'),
(17591, 'A4:BE:61', 'EutroVision System, Inc.'),
(17592, 'A4:C0:C7', 'ShenZhen Hitom Communication Technology Co..LTD'),
(17593, 'A4:C0:E1', 'Nintendo Co., Ltd.'),
(17594, 'A4:C2:AB', 'Hangzhou LEAD-IT Information &amp; Technology Co.,Ltd'),
(17595, 'A4:C3:61', 'Apple'),
(17596, 'A4:C4:94', 'Intel Corporate'),
(17597, 'A4:C7:DE', 'Cambridge Industries(Group) Co.,Ltd.'),
(17598, 'A4:D0:94', 'Erwin Peters Systemtechnik GmbH'),
(17599, 'A4:D1:8F', 'Shenzhen Skyee Optical Fiber Communication Technology Ltd.'),
(17600, 'A4:D1:D1', 'ECOtality North America'),
(17601, 'A4:D1:D2', 'Apple'),
(17602, 'A4:D3:B5', 'GLITEL Stropkov, s.r.o.'),
(17603, 'A4:D8:56', 'Gimbal, Inc'),
(17604, 'A4:DA:3F', 'Bionics Corp.'),
(17605, 'A4:DB:2E', 'Kingspan Environmental Ltd'),
(17606, 'A4:DB:30', 'Liteon Technology Corporation'),
(17607, 'A4:DE:50', 'Total Walther GmbH'),
(17608, 'A4:E0:E6', 'FILIZOLA S.A. PESAGEM E AUTOMACAO'),
(17609, 'A4:E3:2E', 'Silicon &amp; Software Systems Ltd.'),
(17610, 'A4:E3:91', 'DENY FONTAINE'),
(17611, 'A4:E4:B8', 'BlackBerry Limited'),
(17612, 'A4:E7:31', 'Nokia Corporation'),
(17613, 'A4:E7:E4', 'Connex GmbH'),
(17614, 'A4:E9:91', 'SISTEMAS AUDIOVISUALES ITELSIS S.L.'),
(17615, 'A4:E9:A3', 'Honest Technology Co., Ltd'),
(17616, 'A4:EB:D3', 'Samsung Electronics Co.,Ltd'),
(17617, 'A4:ED:4E', 'ARRIS Group, Inc.'),
(17618, 'A4:EE:57', 'SEIKO EPSON CORPORATION'),
(17619, 'A4:EF:52', 'Telewave Co., Ltd.'),
(17620, 'A4:F3:C1', 'Open Source Robotics Foundation, Inc.'),
(17621, 'A4:F5:22', 'CHOFU SEISAKUSHO CO.,LTD'),
(17622, 'A4:F7:D0', 'LAN Accessories Co., Ltd.'),
(17623, 'A4:FB:8D', 'Hangzhou Dunchong Technology Co.Ltd'),
(17624, 'A4:FC:CE', 'Security Expert Ltd.'),
(17625, 'A8:01:80', 'IMAGO Technologies GmbH'),
(17626, 'A8:06:00', 'Samsung Electronics Co.,Ltd'),
(17627, 'A8:0C:0D', 'Cisco'),
(17628, 'A8:13:74', 'Panasonic Corporation AVC Networks Company'),
(17629, 'A8:15:4D', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(17630, 'A8:16:B2', 'LG Electronics'),
(17631, 'A8:17:58', 'Elektronik System i Ume&aring; AB'),
(17632, 'A8:1B:18', 'XTS CORP'),
(17633, 'A8:1B:5D', 'Foxtel Management Pty Ltd'),
(17634, 'A8:1D:16', 'AzureWave Technologies, Inc'),
(17635, 'A8:1F:AF', 'KRYPTON POLSKA'),
(17636, 'A8:20:66', 'Apple'),
(17637, 'A8:24:EB', 'ZAO NPO Introtest'),
(17638, 'A8:26:D9', 'HTC Corporation'),
(17639, 'A8:29:4C', 'Precision Optical Transceivers, Inc.'),
(17640, 'A8:2B:D6', 'Shina System Co., Ltd'),
(17641, 'A8:30:AD', 'Wei Fang Goertek Electronics Co.,Ltd'),
(17642, 'A8:32:9A', 'Digicom Futuristic Technologies Ltd.'),
(17643, 'A8:39:44', 'Actiontec Electronics, Inc'),
(17644, 'A8:40:41', 'Dragino Technology Co., Limited'),
(17645, 'A8:44:81', 'Nokia Corporation'),
(17646, 'A8:45:E9', 'Firich Enterprises CO., LTD.'),
(17647, 'A8:49:A5', 'Lisantech Co., Ltd.'),
(17648, 'A8:54:B2', 'Wistron Neweb Corp.'),
(17649, 'A8:55:6A', 'Pocketnet Technology Inc.'),
(17650, 'A8:57:4E', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(17651, 'A8:5B:78', 'Apple'),
(17652, 'A8:5B:B0', 'Shenzhen Dehoo Technology Co.,Ltd'),
(17653, 'A8:5B:F3', 'Audivo GmbH'),
(17654, 'A8:61:AA', 'Cloudview Limited'),
(17655, 'A8:62:A2', 'JIWUMEDIA CO., LTD.'),
(17656, 'A8:63:DF', 'DISPLAIRE CORPORATION'),
(17657, 'A8:63:F2', 'Texas Instruments'),
(17658, 'A8:64:05', 'nimbus 9, Inc'),
(17659, 'A8:65:B2', 'DONGGUAN YISHANG ELECTRONIC TECHNOLOGY CO., LIMITED'),
(17660, 'A8:66:7F', 'Apple, Inc.'),
(17661, 'A8:6A:6F', 'RIM'),
(17662, 'A8:70:A5', 'UniComm Inc.'),
(17663, 'A8:75:D6', 'FreeTek International Co., Ltd.'),
(17664, 'A8:75:E2', 'Aventura Technologies, Inc.'),
(17665, 'A8:77:6F', 'Zonoff'),
(17666, 'A8:7B:39', 'Nokia Corporation'),
(17667, 'A8:7C:01', 'Samsung Elec Co.,Ltd'),
(17668, 'A8:7E:33', 'Nokia Danmark A/S'),
(17669, 'A8:81:F1', 'BMEYE B.V.'),
(17670, 'A8:86:DD', 'Apple, Inc.'),
(17671, 'A8:87:92', 'Broadband Antenna Tracking Systems'),
(17672, 'A8:87:ED', 'ARC Wireless LLC'),
(17673, 'A8:88:08', 'Apple'),
(17674, 'A8:8C:EE', 'MicroMade Galka i Drozdz sp.j.'),
(17675, 'A8:8D:7B', 'SunDroid Global limited.'),
(17676, 'A8:8E:24', 'Apple'),
(17677, 'A8:90:08', 'Beijing Yuecheng Technology Co. Ltd.'),
(17678, 'A8:92:2C', 'LG Electronics'),
(17679, 'A8:93:E6', 'JIANGXI JINGGANGSHAN CKING COMMUNICATION TECHNOLOGY CO.,LTD'),
(17680, 'A8:95:B0', 'Aker Subsea Ltd'),
(17681, 'A8:96:8A', 'Apple'),
(17682, 'A8:97:DC', 'IBM'),
(17683, 'A8:98:C6', 'Shinbo Co., Ltd.'),
(17684, 'A8:99:5C', 'aizo ag'),
(17685, 'A8:9B:10', 'inMotion Ltd.'),
(17686, 'A8:9D:21', 'Cisco'),
(17687, 'A8:9D:D2', 'Shanghai DareGlobal Technologies Co., Ltd'),
(17688, 'A8:9F:BA', 'Samsung Electronics Co.,Ltd'),
(17689, 'A8:A6:68', 'zte corporation'),
(17690, 'A8:AD:3D', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(17691, 'A8:B0:AE', 'LEONI'),
(17692, 'A8:B1:D4', 'CISCO SYSTEMS, INC.'),
(17693, 'A8:B9:B3', 'ESSYS'),
(17694, 'A8:BB:CF', 'Apple'),
(17695, 'A8:BD:1A', 'Honey Bee (Hong Kong) Limited'),
(17696, 'A8:BD:3A', 'UNIONMAN TECHNOLOGY CO.,LTD'),
(17697, 'A8:C2:22', 'TM-Research Inc.'),
(17698, 'A8:CB:95', 'EAST BEST CO., LTD.'),
(17699, 'A8:CC:C5', 'Saab AB (publ)'),
(17700, 'A8:CE:90', 'CVC'),
(17701, 'A8:D0:E3', 'Systech Electronics Ltd.'),
(17702, 'A8:D0:E5', 'Juniper Networks'),
(17703, 'A8:D2:36', 'Lightware Visual Engineering'),
(17704, 'A8:D3:C8', 'Wachendorff Elektronik  GmbH &amp; Co. KG'),
(17705, 'A8:D8:8A', 'Wyconn'),
(17706, 'A8:E0:18', 'Nokia Corporation'),
(17707, 'A8:E3:EE', 'Sony Computer Entertainment Inc.'),
(17708, 'A8:E5:39', 'Moimstone Co.,Ltd'),
(17709, 'A8:EF:26', 'Tritonwave'),
(17710, 'A8:F0:38', 'SHEN ZHEN SHI JIN HUA TAI ELECTRONICS CO.,LTD'),
(17711, 'A8:F2:74', 'Samsung Electronics'),
(17712, 'A8:F4:70', 'Fujian Newland Communication Science Technologies Co.,Ltd.'),
(17713, 'A8:F7:E0', 'PLANET Technology Corporation'),
(17714, 'A8:F9:4B', 'Eltex Enterprise Ltd.'),
(17715, 'A8:FA:D8', 'Apple'),
(17716, 'A8:FB:70', 'WiseSec L.t.d'),
(17717, 'A8:FC:B7', 'Consolidated Resource Imaging'),
(17718, 'AA:00:00', 'DIGITAL EQUIPMENT CORPORATION'),
(17719, 'AA:00:01', 'DIGITAL EQUIPMENT CORPORATION'),
(17720, 'AA:00:02', 'DIGITAL EQUIPMENT CORPORATION'),
(17721, 'AA:00:03', 'DIGITAL EQUIPMENT CORPORATION'),
(17722, 'AA:00:04', 'DIGITAL EQUIPMENT CORPORATION'),
(17723, 'AC:01:42', 'Uriel Technologies SIA'),
(17724, 'AC:02:CA', 'HI Solutions, Inc.'),
(17725, 'AC:02:CF', 'RW Tecnologia Industria e Comercio Ltda'),
(17726, 'AC:02:EF', 'Comsis'),
(17727, 'AC:06:13', 'Senselogix Ltd'),
(17728, 'AC:0A:61', 'Labor S.r.L.'),
(17729, 'AC:0D:FE', 'Ekon GmbH - myGEKKO'),
(17730, 'AC:11:D3', 'Suzhou HOTEK  Video Technology Co. Ltd'),
(17731, 'AC:14:61', 'ATAW  Co., Ltd.'),
(17732, 'AC:14:D2', 'wi-daq, inc.'),
(17733, 'AC:16:2D', 'Hewlett Packard'),
(17734, 'AC:17:02', 'Fibar Group sp. z o.o.'),
(17735, 'AC:18:26', 'SEIKO EPSON CORPORATION'),
(17736, 'AC:19:9F', 'SUNGROW POWER SUPPLY CO.,LTD.'),
(17737, 'AC:20:AA', 'DMATEK Co., Ltd.'),
(17738, 'AC:22:0B', 'ASUSTek COMPUTER INC.'),
(17739, 'AC:2D:A3', 'TXTR GmbH'),
(17740, 'AC:2F:A8', 'Humannix Co.,Ltd.'),
(17741, 'AC:31:9D', 'Shenzhen TG-NET Botone Technology Co.,Ltd.'),
(17742, 'AC:34:CB', 'Shanhai GBCOM Communication Technology Co. Ltd'),
(17743, 'AC:36:13', 'Samsung Electronics Co.,Ltd'),
(17744, 'AC:38:70', 'Lenovo Mobile Communication Technology Ltd.'),
(17745, 'AC:3A:7A', 'Roku'),
(17746, 'AC:3C:0B', 'Apple'),
(17747, 'AC:3C:B4', 'Nilan A/S'),
(17748, 'AC:3D:05', 'Instorescreen Aisa'),
(17749, 'AC:3D:75', 'HANGZHOU ZHIWAY TECHNOLOGIES CO.,LTD.'),
(17750, 'AC:3F:A4', 'TAIYO YUDEN CO.,LTD'),
(17751, 'AC:40:EA', 'C&amp;T Solution Inc.'),
(17752, 'AC:41:22', 'Eclipse Electronic Systems Inc.'),
(17753, 'AC:44:F2', 'Revolabs Inc'),
(17754, 'AC:47:23', 'Genelec'),
(17755, 'AC:4A:FE', 'Hisense Broadband Multimedia Technology Co.,Ltd.'),
(17756, 'AC:4B:C8', 'Juniper Networks'),
(17757, 'AC:4E:91', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(17758, 'AC:4F:FC', 'SVS-VISTEK GmbH'),
(17759, 'AC:50:36', 'Pi-Coral Inc'),
(17760, 'AC:51:35', 'MPI TECH'),
(17761, 'AC:51:EE', 'Cambridge Communication Systems Ltd'),
(17762, 'AC:54:EC', 'IEEE P1823 Standards Working Group'),
(17763, 'AC:58:3B', 'Human Assembler, Inc.'),
(17764, 'AC:5D:10', 'Pace Americas'),
(17765, 'AC:5E:8C', 'Utillink'),
(17766, 'AC:61:23', 'Drivven, Inc.'),
(17767, 'AC:67:06', 'Ruckus Wireless'),
(17768, 'AC:6B:AC', 'Jenny Science AG'),
(17769, 'AC:6E:1A', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(17770, 'AC:6F:4F', 'Enspert Inc'),
(17771, 'AC:6F:BB', 'TATUNG Technology Inc.'),
(17772, 'AC:6F:D9', 'Valueplus Inc.'),
(17773, 'AC:72:36', 'Lexking Technology Co., Ltd.'),
(17774, 'AC:72:89', 'Intel Corporate'),
(17775, 'AC:7A:42', 'iConnectivity'),
(17776, 'AC:7B:A1', 'Intel Corporate'),
(17777, 'AC:7F:3E', 'Apple'),
(17778, 'AC:80:D6', 'Hexatronic AB'),
(17779, 'AC:81:12', 'Gemtek Technology Co., Ltd.'),
(17780, 'AC:81:F3', 'Nokia Corporation'),
(17781, 'AC:83:17', 'Shenzhen Furtunetel Communication Co., Ltd'),
(17782, 'AC:83:F0', 'ImmediaTV Corporation'),
(17783, 'AC:85:3D', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(17784, 'AC:86:74', 'Open Mesh, Inc.'),
(17785, 'AC:86:7E', 'Create New Technology (HK) Limited Company'),
(17786, 'AC:87:A3', 'Apple'),
(17787, 'AC:8A:CD', 'ROGER D.Wensker, G.Wensker sp.j.'),
(17788, 'AC:8D:14', 'Smartrove Inc'),
(17789, 'AC:93:2F', 'Nokia Corporation'),
(17790, 'AC:94:03', 'Envision Peripherals Inc'),
(17791, 'AC:9A:96', 'Lantiq Deutschland GmbH'),
(17792, 'AC:9B:84', 'Smak Tecnologia e Automacao'),
(17793, 'AC:9C:E4', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(17794, 'AC:9E:17', 'ASUSTek COMPUTER INC.'),
(17795, 'AC:A0:16', 'CISCO SYSTEMS, INC.'),
(17796, 'AC:A2:13', 'Shenzhen Bilian electronic CO.,LTD'),
(17797, 'AC:A2:2C', 'Baycity Technologies Ltd'),
(17798, 'AC:A3:1E', 'Aruba Networks'),
(17799, 'AC:A4:30', 'Peerless AV'),
(17800, 'AC:A9:19', 'TrekStor GmbH'),
(17801, 'AC:A9:A0', 'Audioengine, Ltd.'),
(17802, 'AC:AB:8D', 'Lyngso Marine A/S'),
(17803, 'AC:AB:BF', 'AthenTek Inc.'),
(17804, 'AC:B3:13', 'ARRIS Group, Inc.'),
(17805, 'AC:B5:7D', 'Liteon Technology Corporation'),
(17806, 'AC:B7:4F', 'METEL s.r.o.'),
(17807, 'AC:B8:59', 'Uniband Electronic Corp,'),
(17808, 'AC:BD:0B', 'IMAC CO.,LTD'),
(17809, 'AC:BE:75', 'Ufine Technologies Co.,Ltd.'),
(17810, 'AC:BE:B6', 'Visualedge Technology Co., Ltd.'),
(17811, 'AC:C2:EC', 'CLT INT\'L IND. CORP.'),
(17812, 'AC:C5:95', 'Graphite Systems'),
(17813, 'AC:C6:98', 'Kohzu Precision Co., Ltd.'),
(17814, 'AC:C7:3F', 'VITSMO CO., LTD.'),
(17815, 'AC:C9:35', 'Ness Corporation'),
(17816, 'AC:CA:54', 'Telldus Technologies AB'),
(17817, 'AC:CA:8E', 'ODA Technologies'),
(17818, 'AC:CA:AB', 'Virtual Electric Inc'),
(17819, 'AC:CA:BA', 'Midokura Co., Ltd.'),
(17820, 'AC:CB:09', 'Hefcom Metering (Pty) Ltd'),
(17821, 'AC:CC:8E', 'Axis Communications AB'),
(17822, 'AC:CE:8F', 'HWA YAO TECHNOLOGIES CO., LTD'),
(17823, 'AC:CF:23', 'Hi-flying electronics technology Co.,Ltd'),
(17824, 'AC:CF:5C', 'Apple'),
(17825, 'AC:D0:74', 'Espressif Inc.'),
(17826, 'AC:D1:80', 'Crexendo Business Solutions, Inc.'),
(17827, 'AC:D1:B8', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17828, 'AC:D3:64', 'ABB SPA, ABB SACE DIV.'),
(17829, 'AC:D6:57', 'Shaanxi Guolian Digital TV Technology Co., Ltd.'),
(17830, 'AC:D9:D6', 'tci GmbH'),
(17831, 'AC:DB:DA', 'Shenzhen Geniatech Inc, Ltd'),
(17832, 'AC:DE:48', 'PRIVATE'),
(17833, 'AC:E0:69', 'ISAAC Instruments'),
(17834, 'AC:E2:15', 'Huawei Technologies Co., Ltd'),
(17835, 'AC:E3:48', 'MadgeTech, Inc'),
(17836, 'AC:E4:2E', 'SK hynix'),
(17837, 'AC:E6:4B', 'Shenzhen Baojia Battery Technology Co., Ltd.'),
(17838, 'AC:E8:7B', 'Huawei Technologies Co., Ltd'),
(17839, 'AC:E8:7E', 'Bytemark Computer Consulting Ltd'),
(17840, 'AC:E9:7F', 'IoT Tech Limited'),
(17841, 'AC:E9:AA', 'Hay Systems Ltd'),
(17842, 'AC:EA:6A', 'GENIX INFOCOMM CO., LTD.'),
(17843, 'AC:EE:3B', '6harmonics Inc'),
(17844, 'AC:F0:B2', 'Becker Electronics Taiwan Ltd.'),
(17845, 'AC:F1:DF', 'D-Link International'),
(17846, 'AC:F2:C5', 'Cisco'),
(17847, 'AC:F7:F3', 'XIAOMI CORPORATION'),
(17848, 'AC:F9:7E', 'ELESYS INC.'),
(17849, 'AC:FD:CE', 'Intel Corporate'),
(17850, 'AC:FD:EC', 'Apple, Inc'),
(17851, 'B0:00:B4', 'Cisco'),
(17852, 'B0:05:94', 'Liteon Technology Corporation'),
(17853, 'B0:09:D3', 'Avizia'),
(17854, 'B0:10:41', 'Hon Hai Precision Ind. Co.,Ltd.'),
(17855, 'B0:12:03', 'Dynamics Hong Kong Limited'),
(17856, 'B0:12:66', 'Futaba-Kikaku'),
(17857, 'B0:14:08', 'LIGHTSPEED INTERNATIONAL CO.'),
(17858, 'B0:17:43', 'EDISON GLOBAL CIRCUITS LLC'),
(17859, 'B0:1B:7C', 'Ontrol A.S.'),
(17860, 'B0:1C:91', 'Elim Co'),
(17861, 'B0:1F:81', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(17862, 'B0:24:F3', 'Progeny Systems'),
(17863, 'B0:25:AA', 'PRIVATE'),
(17864, 'B0:34:95', 'Apple'),
(17865, 'B0:35:8D', 'Nokia Corporation'),
(17866, 'B0:38:29', 'Siliconware Precision Industries Co., Ltd.'),
(17867, 'B0:38:50', 'Nanjing CAS-ZDC IOT SYSTEM CO.,LTD'),
(17868, 'B0:43:5D', 'NuLEDs, Inc.'),
(17869, 'B0:45:15', 'mira fitness,LLC.'),
(17870, 'B0:45:19', 'TCT mobile ltd'),
(17871, 'B0:45:45', 'YACOUB Automation GmbH'),
(17872, 'B0:46:FC', 'MitraStar Technology Corp.'),
(17873, 'B0:48:7A', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(17874, 'B0:49:5F', 'OMRON HEALTHCARE Co., Ltd.'),
(17875, 'B0:4C:05', 'Fresenius Medical Care Deutschland GmbH'),
(17876, 'B0:50:BC', 'SHENZHEN BASICOM ELECTRONIC CO.,LTD.'),
(17877, 'B0:51:8E', 'Holl technology CO.Ltd.'),
(17878, 'B0:57:06', 'Vallox Oy'),
(17879, 'B0:58:C4', 'Broadcast Microwave Services, Inc'),
(17880, 'B0:5B:1F', 'THERMO FISHER SCIENTIFIC S.P.A.'),
(17881, 'B0:5B:67', 'Huawei Technologies Co., Ltd'),
(17882, 'B0:5C:E5', 'Nokia Corporation'),
(17883, 'B0:61:C7', 'Ericsson-LG Enterprise'),
(17884, 'B0:65:63', 'Shanghai Railway Communication Factory'),
(17885, 'B0:65:BD', 'Apple'),
(17886, 'B0:68:B6', 'Hangzhou OYE Technology Co. Ltd'),
(17887, 'B0:69:71', 'DEI Sales, Inc.'),
(17888, 'B0:6C:BF', '3ality Digital Systems GmbH'),
(17889, 'B0:75:0C', 'QA Cafe'),
(17890, 'B0:75:4D', 'Alcatel-Lucent'),
(17891, 'B0:75:D5', 'ZTE Corporation'),
(17892, 'B0:77:AC', 'ARRIS Group, Inc.'),
(17893, 'B0:79:08', 'Cummings Engineering'),
(17894, 'B0:79:3C', 'Revolv Inc'),
(17895, 'B0:79:94', 'Motorola Mobility LLC'),
(17896, 'B0:7D:62', 'Dipl.-Ing. H. Horstmann GmbH'),
(17897, 'B0:80:8C', 'Laser Light Engines'),
(17898, 'B0:81:D8', 'I-sys Corp'),
(17899, 'B0:83:FE', 'Dell Inc'),
(17900, 'B0:86:9E', 'Chloride S.r.L'),
(17901, 'B0:88:07', 'Strata Worldwide'),
(17902, 'B0:89:91', 'LGE'),
(17903, 'B0:8E:1A', 'URadio Systems Co., Ltd'),
(17904, 'B0:90:74', 'Fulan Electronics Limited'),
(17905, 'B0:91:34', 'Taleo'),
(17906, 'B0:91:37', 'ISis ImageStream Internet Solutions, Inc'),
(17907, 'B0:97:3A', 'E-Fuel Corporation'),
(17908, 'B0:98:9F', 'LG CNS'),
(17909, 'B0:99:28', 'Fujitsu Limited'),
(17910, 'B0:9A:E2', 'STEMMER IMAGING GmbH'),
(17911, 'B0:9B:D4', 'GNH Software India Private Limited'),
(17912, 'B0:9F:BA', 'Apple'),
(17913, 'B0:A1:0A', 'Pivotal Systems Corporation'),
(17914, 'B0:A3:7E', 'Qingdao Haier Electronics Co.,Ltd'),
(17915, 'B0:A7:2A', 'Ensemble Designs, Inc.'),
(17916, 'B0:A7:37', 'Roku, Inc.'),
(17917, 'B0:A8:6E', 'Juniper Networks'),
(17918, 'B0:AA:36', 'GUANGDONG OPPO MOBILE TELECOMMUNICATIONS CORP.,LTD.'),
(17919, 'B0:AC:FA', 'Fujitsu Limited'),
(17920, 'B0:AD:AA', 'Avaya, Inc'),
(17921, 'B0:B2:DC', 'Zyxel Communications Corporation'),
(17922, 'B0:B3:2B', 'Slican Sp. z o.o.'),
(17923, 'B0:B4:48', 'Texas Instruments'),
(17924, 'B0:B8:D5', 'Nanjing Nengrui Auto Equipment CO.,Ltd'),
(17925, 'B0:BD:6D', 'Echostreams Innovative Solutions'),
(17926, 'B0:BD:A1', 'ZAKLAD ELEKTRONICZNY SIMS'),
(17927, 'B0:BF:99', 'WIZITDONGDO'),
(17928, 'B0:C2:87', 'Technicolor CH USA Inc'),
(17929, 'B0:C4:E7', 'Samsung Electronics'),
(17930, 'B0:C5:54', 'D-Link International'),
(17931, 'B0:C5:59', 'Samsung Electronics Co.,Ltd'),
(17932, 'B0:C6:9A', 'Juniper Networks'),
(17933, 'B0:C7:45', 'Buffalo Inc.'),
(17934, 'B0:C8:3F', 'Jiangsu Cynray IOT Co., Ltd.'),
(17935, 'B0:C8:AD', 'People Power Company'),
(17936, 'B0:C9:5B', 'Beijing Symtech CO.,LTD'),
(17937, 'B0:CE:18', 'Zhejiang shenghui lighting co.,Ltd'),
(17938, 'B0:CF:4D', 'MI-Zone Technology Ireland'),
(17939, 'B0:D0:9C', 'Samsung Electronics Co.,Ltd'),
(17940, 'B0:D2:F5', 'Vello Systems, Inc.'),
(17941, 'B0:D5:9D', 'Shenzhen Zowee Technology Co., Ltd'),
(17942, 'B0:D7:C5', 'STP KFT'),
(17943, 'B0:DA:00', 'CERA ELECTRONIQUE'),
(17944, 'B0:DF:3A', 'Samsung Electronics Co.,Ltd'),
(17945, 'B0:E0:3C', 'TCT mobile ltd'),
(17946, 'B0:E2:E5', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(17947, 'B0:E3:9D', 'CAT SYSTEM CO.,LTD.'),
(17948, 'B0:E5:0E', 'NRG SYSTEMS INC'),
(17949, 'B0:E7:54', '2Wire'),
(17950, 'B0:E8:92', 'SEIKO EPSON CORPORATION'),
(17951, 'B0:E9:7E', 'Advanced Micro Peripherals'),
(17952, 'B0:EC:71', 'Samsung Electronics Co.,Ltd'),
(17953, 'B0:EC:8F', 'GMX SAS'),
(17954, 'B0:EE:45', 'AzureWave Technologies, Inc.'),
(17955, 'B0:F1:BC', 'Dhemax Ingenieros Ltda'),
(17956, 'B0:FA:EB', 'Cisco'),
(17957, 'B0:FE:BD', 'PRIVATE'),
(17958, 'B4:00:9C', 'CableWorld Ltd.'),
(17959, 'B4:01:42', 'GCI Science &amp; Technology Co.,LTD'),
(17960, 'B4:04:18', 'Smartchip Integrated Inc.'),
(17961, 'B4:05:66', 'SP Best Corporation Co., LTD.'),
(17962, 'B4:07:F9', 'SAMSUNG ELECTRO-MECHANICS'),
(17963, 'B4:08:32', 'TC Communications'),
(17964, 'B4:0A:C6', 'DEXON Systems Ltd.'),
(17965, 'B4:0B:44', 'Smartisan Technology Co., Ltd.'),
(17966, 'B4:0B:7A', 'Brusa Elektronik AG'),
(17967, 'B4:0C:25', 'Palo Alto Networks'),
(17968, 'B4:0E:96', 'HERAN'),
(17969, 'B4:0E:DC', 'LG-Ericsson Co.,Ltd.'),
(17970, 'B4:14:89', 'CISCO SYSTEMS, INC.'),
(17971, 'B4:15:13', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(17972, 'B4:17:80', 'DTI Group Ltd'),
(17973, 'B4:18:D1', 'Apple'),
(17974, 'B4:1D:EF', 'Internet Laboratories, Inc.'),
(17975, 'B4:21:1D', 'Beijing GuangXin Technology Co., Ltd'),
(17976, 'B4:21:8A', 'Dog Hunter LLC'),
(17977, 'B4:24:E7', 'Codetek Technology Co.,Ltd'),
(17978, 'B4:28:F1', 'E-Prime Co., Ltd.'),
(17979, 'B4:2A:39', 'ORBIT MERRET, spol. s r. o.'),
(17980, 'B4:2C:92', 'Zhejiang Weirong Electronic Co., Ltd'),
(17981, 'B4:2C:BE', 'Direct Payment Solutions Limited'),
(17982, 'B4:30:52', 'Huawei Technologies Co., Ltd'),
(17983, 'B4:31:B8', 'Aviwest'),
(17984, 'B4:34:6C', 'MATSUNICHI DIGITAL TECHNOLOGY (HONG KONG) LIMITED'),
(17985, 'B4:35:64', 'Fujian Tian Cheng Electron Science &amp; Technical Development Co.,Ltd.'),
(17986, 'B4:35:F7', 'Zhejiang Pearmain Electronics Co.ltd.'),
(17987, 'B4:37:41', 'Consert, Inc.'),
(17988, 'B4:39:34', 'Pen Generations, Inc.'),
(17989, 'B4:39:D6', 'ProCurve Networking by HP'),
(17990, 'B4:3A:28', 'Samsung Electronics Co.,Ltd'),
(17991, 'B4:3D:B2', 'Degreane Horizon'),
(17992, 'B4:3E:3B', 'Viableware, Inc'),
(17993, 'B4:41:7A', 'ShenZhen Gongjin Electronics Co.,Ltd'),
(17994, 'B4:43:0D', 'Broadlink Pty Ltd'),
(17995, 'B4:47:5E', 'Avaya, Inc'),
(17996, 'B4:4C:C2', 'NR ELECTRIC CO., LTD'),
(17997, 'B4:51:F9', 'NB Software'),
(17998, 'B4:52:53', 'Seagate Technology'),
(17999, 'B4:52:7D', 'Sony Mobile Communications AB'),
(18000, 'B4:52:7E', 'Sony Mobile Communications AB'),
(18001, 'B4:55:70', 'Borea'),
(18002, 'B4:58:61', 'CRemote, LLC'),
(18003, 'B4:5C:A4', 'Thing-talk Wireless Communication Technologies Corporation Limited'),
(18004, 'B4:61:FF', 'Lumigon A/S'),
(18005, 'B4:62:38', 'Exablox'),
(18006, 'B4:62:93', 'Samsung Electronics Co.,Ltd'),
(18007, 'B4:62:AD', 'raytest GmbH'),
(18008, 'B4:66:98', 'Zealabs srl'),
(18009, 'B4:67:E9', 'Qingdao GoerTek Technology Co., Ltd.'),
(18010, 'B4:73:56', 'Hangzhou Treebear Networking Co., Ltd.'),
(18011, 'B4:74:9F', 'askey computer corp'),
(18012, 'B4:75:0E', 'Belkin International Inc.'),
(18013, 'B4:79:A7', 'Samsung Electro Mechanics co., LTD.'),
(18014, 'B4:7C:29', 'Shenzhen Guzidi Technology Co.,Ltd'),
(18015, 'B4:7F:5E', 'Foresight Manufacture (S) Pte Ltd'),
(18016, 'B4:82:55', 'Research Products Corporation'),
(18017, 'B4:82:7B', 'AKG Acoustics GmbH'),
(18018, 'B4:82:C5', 'Relay2, Inc.'),
(18019, 'B4:82:FE', 'Askey Computer Corp'),
(18020, 'B4:85:47', 'Amptown System Company GmbH'),
(18021, 'B4:89:10', 'Coster T.E. S.P.A.'),
(18022, 'B4:94:4E', 'WeTelecom Co., Ltd.'),
(18023, 'B4:98:42', 'zte corporation'),
(18024, 'B4:99:4C', 'Texas Instruments'),
(18025, 'B4:99:BA', 'Hewlett-Packard Company'),
(18026, 'B4:9D:B4', 'Axion Technologies Inc.'),
(18027, 'B4:9E:AC', 'Imagik Int\'l Corp'),
(18028, 'B4:9E:E6', 'SHENZHEN TECHNOLOGY CO LTD'),
(18029, 'B4:A4:B5', 'Zen Eye Co.,Ltd'),
(18030, 'B4:A4:E3', 'CISCO SYSTEMS, INC.'),
(18031, 'B4:A5:A9', 'MODI GmbH'),
(18032, 'B4:A8:28', 'Shenzhen Concox Information Technology Co., Ltd'),
(18033, 'B4:A8:2B', 'Histar Digital Electronics Co., Ltd.'),
(18034, 'B4:A9:5A', 'Avaya, Inc'),
(18035, 'B4:A9:FE', 'GHIA Technology (Shenzhen) LTD'),
(18036, 'B4:AA:4D', 'Ensequence, Inc.'),
(18037, 'B4:AB:2C', 'MtM Technology Corporation'),
(18038, 'B4:AE:6F', 'Circle Reliance, Inc DBA Cranberry Networks'),
(18039, 'B4:B0:17', 'Avaya, Inc'),
(18040, 'B4:B3:62', 'ZTE Corporation'),
(18041, 'B4:B5:2F', 'Hewlett Packard'),
(18042, 'B4:B5:42', 'Hubbell Power Systems, Inc.'),
(18043, 'B4:B5:AF', 'Minsung Electronics'),
(18044, 'B4:B6:76', 'Intel Corporate'),
(18045, 'B4:B8:59', 'Texa Spa'),
(18046, 'B4:B8:8D', 'Thuh Company'),
(18047, 'B4:C4:4E', 'VXL eTech Pvt Ltd'),
(18048, 'B4:C7:99', 'Zebra Technologies Inc'),
(18049, 'B4:C8:10', 'UMPI Elettronica'),
(18050, 'B4:CC:E9', 'PROSYST'),
(18051, 'B4:CE:F6', 'HTC Corporation'),
(18052, 'B4:CF:DB', 'Shenzhen Jiuzhou Electric Co.,LTD'),
(18053, 'B4:D8:A9', 'BetterBots'),
(18054, 'B4:D8:DE', 'iota Computing, Inc.'),
(18055, 'B4:DD:15', 'ControlThings Oy Ab'),
(18056, 'B4:DF:3B', 'Chromlech'),
(18057, 'B4:DF:FA', 'Litemax Electronics Inc.'),
(18058, 'B4:E0:CD', 'Fusion-io, Inc'),
(18059, 'B4:E1:EB', 'PRIVATE'),
(18060, 'B4:E9:B0', 'Cisco'),
(18061, 'B4:ED:19', 'Pie Digital, Inc.'),
(18062, 'B4:ED:54', 'Wohler Technologies'),
(18063, 'B4:EE:B4', 'ASKEY COMPUTER CORP'),
(18064, 'B4:EE:D4', 'Texas Instruments'),
(18065, 'B4:EF:39', 'Samsung Electronics Co.,Ltd'),
(18066, 'B4:F0:AB', 'Apple'),
(18067, 'B4:F2:E8', 'Pace plc'),
(18068, 'B4:F3:23', 'PETATEL INC.'),
(18069, 'B4:FC:75', 'SEMA Electronics(HK) CO.,LTD'),
(18070, 'B4:FE:8C', 'Centro Sicurezza Italia SpA'),
(18071, 'B8:03:05', 'Intel Corporate'),
(18072, 'B8:04:15', 'Bayan Audio'),
(18073, 'B8:08:CF', 'Intel Corporate'),
(18074, 'B8:09:8A', 'Apple'),
(18075, 'B8:0B:9D', 'ROPEX Industrie-Elektronik GmbH'),
(18076, 'B8:14:13', 'Keen High Holding(HK) Ltd.'),
(18077, 'B8:16:19', 'ARRIS Group, Inc.'),
(18078, 'B8:17:C2', 'Apple'),
(18079, 'B8:18:6F', 'ORIENTAL MOTOR CO., LTD.'),
(18080, 'B8:19:99', 'Nesys'),
(18081, 'B8:20:E7', 'Guangzhou Horizontal Information &amp; Network Integration Co. Ltd'),
(18082, 'B8:24:10', 'Magneti Marelli Slovakia s.r.o.'),
(18083, 'B8:24:1A', 'SWEDA INFORMATICA LTDA'),
(18084, 'B8:26:6C', 'ANOV France'),
(18085, 'B8:26:D4', 'Furukawa Industrial S.A. Produtos El&eacute;tricos'),
(18086, 'B8:27:EB', 'Raspberry Pi Foundation'),
(18087, 'B8:28:8B', 'Parker Hannifin'),
(18088, 'B8:29:F7', 'Blaster Tech'),
(18089, 'B8:2A:72', 'Dell Inc'),
(18090, 'B8:2A:DC', 'EFR Europ&auml;ische Funk-Rundsteuerung GmbH'),
(18091, 'B8:2C:A0', 'Honeywell HomMed'),
(18092, 'B8:30:A8', 'Road-Track Telematics Development'),
(18093, 'B8:36:D8', 'Videoswitch'),
(18094, 'B8:38:61', 'Cisco'),
(18095, 'B8:38:CA', 'Kyokko Tsushin System CO.,LTD'),
(18096, 'B8:3A:7B', 'Worldplay (Canada) Inc.'),
(18097, 'B8:3D:4E', 'Shenzhen Cultraview Digital Technology Co.,Ltd Shanghai Branch'),
(18098, 'B8:3E:59', 'Roku, Inc'),
(18099, 'B8:41:5F', 'ASP AG'),
(18100, 'B8:43:E4', 'Vlatacom'),
(18101, 'B8:47:C6', 'SanJet Technology Corp.'),
(18102, 'B8:4F:D5', 'Microsoft Corporation'),
(18103, 'B8:55:10', 'Zioncom Electronics (Shenzhen) Ltd.'),
(18104, 'B8:56:BD', 'ITT LLC'),
(18105, 'B8:58:10', 'NUMERA, INC.'),
(18106, 'B8:5A:73', 'Samsung Electronics Co.,Ltd'),
(18107, 'B8:5A:F7', 'Ouya, Inc'),
(18108, 'B8:5A:FE', 'Handaer Communication Technology (Beijing) Co., Ltd'),
(18109, 'B8:5E:7B', 'Samsung Electronics Co.,Ltd'),
(18110, 'B8:60:91', 'Onnet Technologies and Innovations LLC'),
(18111, 'B8:61:6F', 'Accton Wireless Broadband(AWB), Corp.'),
(18112, 'B8:62:1F', 'CISCO SYSTEMS, INC.'),
(18113, 'B8:63:BC', 'ROBOTIS, Co, Ltd'),
(18114, 'B8:64:91', 'CK Telecom Ltd'),
(18115, 'B8:65:3B', 'Bolymin, Inc.'),
(18116, 'B8:6B:23', 'Toshiba'),
(18117, 'B8:6C:E8', 'Samsung Electronics Co.,Ltd'),
(18118, 'B8:70:F4', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(18119, 'B8:74:24', 'Viessmann Elektronik GmbH'),
(18120, 'B8:74:47', 'Convergence Technologies'),
(18121, 'B8:75:C0', 'PayPal, Inc.'),
(18122, 'B8:76:3F', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18123, 'B8:77:C3', 'Decagon Devices, Inc.'),
(18124, 'B8:78:2E', 'Apple'),
(18125, 'B8:78:79', 'Roche Diagnostics GmbH'),
(18126, 'B8:79:7E', 'Secure Meters (UK) Limited'),
(18127, 'B8:7A:C9', 'Siemens Ltd.'),
(18128, 'B8:7C:F2', 'Aerohive Networks Inc.'),
(18129, 'B8:87:1E', 'Good Mind Industries Co., Ltd.'),
(18130, 'B8:87:A8', 'Step Ahead Innovations Inc.'),
(18131, 'B8:88:E3', 'COMPAL INFORMATION (KUNSHAN) CO., LTD'),
(18132, 'B8:89:CA', 'ILJIN ELECTRIC Co., Ltd.'),
(18133, 'B8:8A:60', 'Intel Corporate'),
(18134, 'B8:8D:12', 'Apple'),
(18135, 'B8:8E:3A', 'Infinite Technologies JLT'),
(18136, 'B8:8E:C6', 'Stateless Networks'),
(18137, 'B8:8F:14', 'Analytica GmbH'),
(18138, 'B8:92:1D', 'BG T&amp;A'),
(18139, 'B8:94:D2', 'Retail Innovation HTT AB'),
(18140, 'B8:96:74', 'AllDSP GmbH &amp; Co. KG'),
(18141, 'B8:97:5A', 'BIOSTAR Microtech Int\'l Corp.'),
(18142, 'B8:98:B0', 'Atlona Inc.'),
(18143, 'B8:98:F7', 'Gionee Communication Equipment Co,Ltd.ShenZhen'),
(18144, 'B8:99:19', '7signal Solutions, Inc'),
(18145, 'B8:9A:CD', 'ELITE OPTOELECTRONIC(ASIA)CO.,LTD'),
(18146, 'B8:9A:ED', 'OceanServer Technology, Inc'),
(18147, 'B8:9B:C9', 'SMC Networks Inc'),
(18148, 'B8:9B:E4', 'ABB Power Systems Power Generation'),
(18149, 'B8:A3:86', 'D-Link International'),
(18150, 'B8:A3:E0', 'BenRui Technology Co.,Ltd'),
(18151, 'B8:A8:AF', 'Logic S.p.A.'),
(18152, 'B8:AC:6F', 'Dell Inc'),
(18153, 'B8:AD:3E', 'BLUECOM'),
(18154, 'B8:AE:6E', 'Nintendo Co., Ltd.'),
(18155, 'B8:AE:ED', 'Elitegroup Computer Systems Co., Ltd.'),
(18156, 'B8:AF:67', 'Hewlett-Packard Company'),
(18157, 'B8:B1:C7', 'BT&amp;COM CO.,LTD'),
(18158, 'B8:B4:2E', 'Gionee Communication Equipment Co,Ltd.ShenZhen'),
(18159, 'B8:B7:D7', '2GIG Technologies'),
(18160, 'B8:B9:4E', 'Shenzhen iBaby Labs, Inc.'),
(18161, 'B8:BA:68', 'Xi\'an Jizhong Digital Communication Co.,Ltd'),
(18162, 'B8:BA:72', 'Cynove'),
(18163, 'B8:BB:6D', 'ENERES Co.,Ltd.'),
(18164, 'B8:BD:79', 'TrendPoint Systems'),
(18165, 'B8:BE:BF', 'CISCO SYSTEMS, INC.'),
(18166, 'B8:BF:83', 'Intel Corporate'),
(18167, 'B8:C1:A2', 'Dragon Path Technologies Co., Limited'),
(18168, 'B8:C4:6F', 'PRIMMCON INDUSTRIES INC'),
(18169, 'B8:C6:8E', 'Samsung Electronics Co.,Ltd'),
(18170, 'B8:C7:16', 'Fiberhome Telecommunication Technologies Co.,LTD'),
(18171, 'B8:C7:5D', 'Apple'),
(18172, 'B8:C8:55', 'Shanghai GBCOM Communication Technology Co.,Ltd.'),
(18173, 'B8:CA:3A', 'Dell Inc'),
(18174, 'B8:CD:93', 'Penetek, Inc'),
(18175, 'B8:CD:A7', 'Maxeler Technologies Ltd.'),
(18176, 'B8:D0:6F', 'GUANGZHOU HKUST FOK YING TUNG RESEARCH INSTITUTE'),
(18177, 'B8:D4:9D', 'M Seven System Ltd.'),
(18178, 'B8:D8:12', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(18179, 'B8:D9:CE', 'Samsung Electronics'),
(18180, 'B8:DA:F1', 'Strahlenschutz- Entwicklungs- und Ausruestungsgesellschaft mbH'),
(18181, 'B8:DA:F7', 'Advanced Photonics, Inc.'),
(18182, 'B8:DC:87', 'IAI Corporation'),
(18183, 'B8:DF:6B', 'SpotCam Co., Ltd.'),
(18184, 'B8:E5:89', 'Payter BV'),
(18185, 'B8:E6:25', '2Wire'),
(18186, 'B8:E7:79', '9Solutions Oy'),
(18187, 'B8:E8:56', 'Apple'),
(18188, 'B8:E9:37', 'Sonos, Inc.'),
(18189, 'B8:EE:65', 'Liteon Technology Corporation'),
(18190, 'B8:EE:79', 'YWire Technologies, Inc.'),
(18191, 'B8:F0:80', 'SPS, INC.'),
(18192, 'B8:F3:17', 'iSun Smasher Communications Private Limited'),
(18193, 'B8:F4:D0', 'Herrmann Ultraschalltechnik GmbH &amp; Co. Kg'),
(18194, 'B8:F5:E7', 'WayTools, LLC'),
(18195, 'B8:F6:B1', 'Apple'),
(18196, 'B8:F7:32', 'Aryaka Networks Inc'),
(18197, 'B8:F8:28', 'Changshu Gaoshida Optoelectronic Technology Co. Ltd.'),
(18198, 'B8:F9:34', 'Sony Ericsson Mobile Communications AB'),
(18199, 'B8:FD:32', 'Zhejiang ROICX Microelectronics'),
(18200, 'B8:FF:61', 'Apple'),
(18201, 'B8:FF:6F', 'Shanghai Typrotech Technology Co.Ltd'),
(18202, 'B8:FF:FE', 'Texas Instruments'),
(18203, 'BC:02:00', 'Stewart Audio'),
(18204, 'BC:05:43', 'AVM GmbH'),
(18205, 'BC:0D:A5', 'Texas Instruments'),
(18206, 'BC:0F:2B', 'FORTUNE TECHGROUP CO.,LTD'),
(18207, 'BC:12:5E', 'Beijing  WisVideo  INC.'),
(18208, 'BC:14:01', 'Hitron Technologies. Inc'),
(18209, 'BC:14:85', 'Samsung Electronics Co.,Ltd'),
(18210, 'BC:14:EF', 'ITON Technology Limited'),
(18211, 'BC:15:A6', 'Taiwan Jantek Electronics,Ltd.'),
(18212, 'BC:16:65', 'Cisco'),
(18213, 'BC:16:F5', 'Cisco'),
(18214, 'BC:1A:67', 'YF Technology Co., Ltd'),
(18215, 'BC:20:A4', 'Samsung Electronics'),
(18216, 'BC:20:BA', 'Inspur (Shandong) Electronic Information Co., Ltd'),
(18217, 'BC:25:F0', '3D Display Technologies Co., Ltd.'),
(18218, 'BC:26:1D', 'HONG KONG TECON TECHNOLOGY'),
(18219, 'BC:28:46', 'NextBIT Computing Pvt. Ltd.'),
(18220, 'BC:28:D6', 'Rowley Associates Limited'),
(18221, 'BC:2B:6B', 'Beijing Haier IC Design Co.,Ltd'),
(18222, 'BC:2B:D7', 'Revogi Innovation Co., Ltd.'),
(18223, 'BC:2C:55', 'Bear Flag Design, Inc.'),
(18224, 'BC:2D:98', 'ThinGlobal LLC'),
(18225, 'BC:30:5B', 'Dell Inc.'),
(18226, 'BC:30:7D', 'Wistron Neweb Corp.'),
(18227, 'BC:34:00', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(18228, 'BC:35:E5', 'Hydro Systems Company'),
(18229, 'BC:38:D2', 'Pandachip Limited'),
(18230, 'BC:39:A6', 'CSUN System Technology Co.,LTD'),
(18231, 'BC:3B:AF', 'Apple'),
(18232, 'BC:3E:13', 'Accordance Systems Inc.'),
(18233, 'BC:41:00', 'Codaco Electronic s.r.o.'),
(18234, 'BC:43:77', 'Hang Zhou Huite Technology Co.,ltd.'),
(18235, 'BC:44:86', 'Samsung Electronics Co.,Ltd'),
(18236, 'BC:47:60', 'Samsung Electronics Co.,Ltd'),
(18237, 'BC:4B:79', 'SensingTek'),
(18238, 'BC:4C:C4', 'Apple'),
(18239, 'BC:4D:FB', 'Hitron Technologies. Inc'),
(18240, 'BC:4E:3C', 'CORE STAFF CO., LTD.'),
(18241, 'BC:4E:5D', 'ZhongMiao Technology Co., Ltd.'),
(18242, 'BC:51:FE', 'Swann Communications Pty Ltd'),
(18243, 'BC:52:B4', 'Alcatel-Lucent'),
(18244, 'BC:52:B7', 'Apple'),
(18245, 'BC:54:F9', 'Drogoo Technology Co., Ltd.'),
(18246, 'BC:5F:F4', 'ASRock Incorporation'),
(18247, 'BC:60:10', 'Qingdao Hisense Communications Co.,Ltd'),
(18248, 'BC:62:9F', 'Telenet Systems P. Ltd.'),
(18249, 'BC:66:41', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(18250, 'BC:67:1C', 'Cisco'),
(18251, 'BC:67:78', 'Apple'),
(18252, 'BC:67:84', 'Environics Oy'),
(18253, 'BC:6A:16', 'tdvine'),
(18254, 'BC:6A:29', 'Texas Instruments'),
(18255, 'BC:6B:4D', 'Alcatel-Lucent'),
(18256, 'BC:6E:64', 'Sony Mobile Communications AB'),
(18257, 'BC:6E:76', 'Green Energy Options Ltd'),
(18258, 'BC:71:C1', 'XTrillion, Inc.'),
(18259, 'BC:72:B1', 'Samsung Electronics Co.,Ltd'),
(18260, 'BC:74:D7', 'HangZhou JuRu Technology CO.,LTD'),
(18261, 'BC:76:4E', 'Rackspace US, Inc.'),
(18262, 'BC:76:70', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(18263, 'BC:77:37', 'Intel Corporate'),
(18264, 'BC:77:9F', 'SBM Co., Ltd.'),
(18265, 'BC:79:AD', 'Samsung Electronics Co.,Ltd'),
(18266, 'BC:7D:D1', 'Radio Data Comms'),
(18267, 'BC:81:1F', 'Ingate Systems'),
(18268, 'BC:81:99', 'BASIC Co.,Ltd.'),
(18269, 'BC:83:A7', 'SHENZHEN CHUANGWEI-RGB ELECTRONICS CO.,LT'),
(18270, 'BC:85:1F', 'Samsung Electronics'),
(18271, 'BC:85:56', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18272, 'BC:88:93', 'VILLBAU Ltd.'),
(18273, 'BC:8B:55', 'NPP ELIKS America Inc. DBA T&amp;M Atlantic'),
(18274, 'BC:8C:CD', 'Samsung Electro Mechanics co.,LTD.'),
(18275, 'BC:8D:0E', 'Alcatel-Lucent'),
(18276, 'BC:92:6B', 'Apple'),
(18277, 'BC:96:80', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(18278, 'BC:98:89', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(18279, 'BC:99:BC', 'FonSee Technology Inc.'),
(18280, 'BC:9C:C5', 'Beijing Huafei Technology Co., Ltd.'),
(18281, 'BC:9D:A5', 'DASCOM Europe GmbH'),
(18282, 'BC:A4:E1', 'Nabto'),
(18283, 'BC:A9:D6', 'Cyber-Rain, Inc.'),
(18284, 'BC:AE:C5', 'ASUSTek COMPUTER INC.'),
(18285, 'BC:B1:81', 'SHARP CORPORATION'),
(18286, 'BC:B1:F3', 'Samsung Electronics'),
(18287, 'BC:B3:08', 'HONGKONG RAGENTEK COMMUNICATION TECHNOLOGY CO.,LIMITED'),
(18288, 'BC:B8:52', 'Cybera, Inc.'),
(18289, 'BC:BA:E1', 'AREC Inc.'),
(18290, 'BC:BB:C9', 'Kellendonk Elektronik GmbH'),
(18291, 'BC:BC:46', 'SKS Welding Systems GmbH'),
(18292, 'BC:C1:68', 'DinBox Sverige AB'),
(18293, 'BC:C2:3A', 'Thomson Video Networks'),
(18294, 'BC:C3:42', 'Panasonic System Networks Co., Ltd.'),
(18295, 'BC:C6:1A', 'SPECTRA EMBEDDED SYSTEMS'),
(18296, 'BC:C6:DB', 'Nokia Corporation'),
(18297, 'BC:C8:10', 'Cisco SPVTG'),
(18298, 'BC:CA:B5', 'ARRIS Group, Inc.'),
(18299, 'BC:CD:45', 'VOISMART'),
(18300, 'BC:CF:CC', 'HTC Corporation'),
(18301, 'BC:D1:65', 'Cisco SPVTG'),
(18302, 'BC:D1:77', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18303, 'BC:D1:D3', 'Tinno Mobile Technology Corp'),
(18304, 'BC:D5:B6', 'd2d technologies'),
(18305, 'BC:D9:40', 'ASR Co,.Ltd.'),
(18306, 'BC:E0:9D', 'Eoslink'),
(18307, 'BC:E5:9F', 'WATERWORLD Technology Co.,LTD'),
(18308, 'BC:E7:67', 'Quanzhou  TDX Electronics Co., Ltd'),
(18309, 'BC:EA:2B', 'CityCom GmbH'),
(18310, 'BC:EA:FA', 'Hewlett Packard'),
(18311, 'BC:EC:23', 'SHENZHEN CHUANGWEI-RGB ELECTRONICS CO.,LTD'),
(18312, 'BC:EE:7B', 'ASUSTek COMPUTER INC.'),
(18313, 'BC:F2:AF', 'devolo AG'),
(18314, 'BC:F5:AC', 'LG Electronics'),
(18315, 'BC:F6:1C', 'Geomodeling Wuxi Technology Co. Ltd.'),
(18316, 'BC:F6:85', 'D-Link International'),
(18317, 'BC:FE:8C', 'Altronic, LLC'),
(18318, 'BC:FF:AC', 'TOPCON CORPORATION'),
(18319, 'C0:0D:7E', 'Additech, Inc.'),
(18320, 'C0:11:A6', 'Fort-Telecom ltd.'),
(18321, 'C0:12:42', 'Alpha Security Products'),
(18322, 'C0:14:3D', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18323, 'C0:18:85', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18324, 'C0:1A:DA', 'Apple'),
(18325, 'C0:1E:9B', 'Pixavi AS'),
(18326, 'C0:22:50', 'PRIVATE'),
(18327, 'C0:25:06', 'AVM GmbH'),
(18328, 'C0:25:5C', 'Cisco'),
(18329, 'C0:27:B9', 'Beijing National Railway Research &amp; Design Institute  of Signal &amp; Communication Co., Ltd.'),
(18330, 'C0:29:73', 'Audyssey Laboratories Inc.'),
(18331, 'C0:29:F3', 'XySystem'),
(18332, 'C0:2B:FC', 'iNES. applied informatics GmbH'),
(18333, 'C0:2C:7A', 'Shen Zhen Horn audio Co., Ltd.'),
(18334, 'C0:33:5E', 'Microsoft'),
(18335, 'C0:34:B4', 'Gigastone Corporation'),
(18336, 'C0:35:80', 'A&amp;R TECH'),
(18337, 'C0:35:BD', 'Velocytech Aps'),
(18338, 'C0:35:C5', 'Prosoft Systems LTD'),
(18339, 'C0:38:96', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18340, 'C0:38:F9', 'Nokia Danmark A/S'),
(18341, 'C0:3B:8F', 'Minicom Digital Signage'),
(18342, 'C0:3D:46', 'Shanghai Mochui Network Technology Co., Ltd'),
(18343, 'C0:3E:0F', 'BSkyB Ltd'),
(18344, 'C0:3F:0E', 'NETGEAR'),
(18345, 'C0:3F:2A', 'Biscotti, Inc.'),
(18346, 'C0:3F:D5', 'Elitegroup Computer Systems Co., LTD'),
(18347, 'C0:41:F6', 'LG Electronics Inc'),
(18348, 'C0:43:01', 'Epec Oy'),
(18349, 'C0:44:E3', 'Shenzhen Sinkna Electronics Co., LTD'),
(18350, 'C0:49:3D', 'MAITRISE TECHNOLOGIQUE'),
(18351, 'C0:4A:00', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18352, 'C0:4D:F7', 'SERELEC'),
(18353, 'C0:56:27', 'Belkin International, Inc.'),
(18354, 'C0:56:E3', 'Hangzhou Hikvision Digital Technology Co.,Ltd.'),
(18355, 'C0:57:BC', 'Avaya, Inc'),
(18356, 'C0:58:A7', 'Pico Systems Co., Ltd.'),
(18357, 'C0:5E:6F', 'V. Stonkaus firma &quot;Kodinis Raktas&quot;'),
(18358, 'C0:5E:79', 'SHENZHEN HUAXUN ARK TECHNOLOGIES CO.,LTD'),
(18359, 'C0:61:18', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18360, 'C0:62:6B', 'CISCO SYSTEMS, INC.'),
(18361, 'C0:63:94', 'Apple'),
(18362, 'C0:64:C6', 'Nokia Corporation'),
(18363, 'C0:65:99', 'Samsung Electronics Co.,Ltd'),
(18364, 'C0:67:AF', 'Cisco'),
(18365, 'C0:6C:0F', 'Dobbs Stanford'),
(18366, 'C0:6C:6D', 'MagneMotion, Inc.'),
(18367, 'C0:70:09', 'Huawei Technologies Co., Ltd'),
(18368, 'C0:7B:BC', 'Cisco'),
(18369, 'C0:7E:40', 'SHENZHEN XDK COMMUNICATION EQUIPMENT CO.,LTD'),
(18370, 'C0:81:70', 'Effigis GeoSolutions'),
(18371, 'C0:83:0A', '2Wire'),
(18372, 'C0:84:7A', 'Apple'),
(18373, 'C0:88:5B', 'SnD Tech Co., Ltd.'),
(18374, 'C0:8A:DE', 'Ruckus Wireless'),
(18375, 'C0:8B:6F', 'S I Sistemas Inteligentes Eletr&ocirc;nicos Ltda'),
(18376, 'C0:8C:60', 'Cisco'),
(18377, 'C0:91:32', 'Patriot Memory'),
(18378, 'C0:91:34', 'ProCurve Networking by HP'),
(18379, 'C0:98:79', 'Acer Inc.'),
(18380, 'C0:98:E5', 'University of Michigan'),
(18381, 'C0:9C:92', 'COBY'),
(18382, 'C0:9D:26', 'Topicon HK Lmd.'),
(18383, 'C0:9F:42', 'Apple'),
(18384, 'C0:A0:BB', 'D-Link International'),
(18385, 'C0:A0:C7', 'FAIRFIELD INDUSTRIES'),
(18386, 'C0:A0:DE', 'Multi Touch Oy'),
(18387, 'C0:A0:E2', 'Eden Innovations'),
(18388, 'C0:A2:6D', 'Abbott Point of Care'),
(18389, 'C0:A3:64', '3D Systems Massachusetts'),
(18390, 'C0:A3:9E', 'EarthCam, Inc.'),
(18391, 'C0:AA:68', 'OSASI Technos Inc.'),
(18392, 'C0:AC:54', 'SAGEMCOM'),
(18393, 'C0:B3:39', 'Comigo Ltd.'),
(18394, 'C0:B3:57', 'Yoshiki Electronics Industry Ltd.'),
(18395, 'C0:B8:B1', 'BitBox Ltd'),
(18396, 'C0:BA:E6', 'Application Solutions (Electronics and Vision) Ltd'),
(18397, 'C0:BD:42', 'ZPA Smart Energy a.s.'),
(18398, 'C0:BD:D1', 'Samsung Electro Mechanics co., LTD.'),
(18399, 'C0:C1:C0', 'Cisco-Linksys, LLC'),
(18400, 'C0:C3:B6', 'Automatic Systems'),
(18401, 'C0:C5:20', 'Ruckus Wireless'),
(18402, 'C0:C5:69', 'SHANGHAI LYNUC CNC TECHNOLOGY CO.,LTD'),
(18403, 'C0:C6:87', 'Cisco SPVTG'),
(18404, 'C0:C9:46', 'MITSUYA LABORATORIES INC.'),
(18405, 'C0:CB:38', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18406, 'C0:CF:A3', 'Creative Electronics &amp; Software, Inc.'),
(18407, 'C0:D0:44', 'SAGEMCOM'),
(18408, 'C0:D9:62', 'Askey Computer Corp.'),
(18409, 'C0:DA:74', 'Hangzhou Sunyard Technology Co., Ltd.'),
(18410, 'C0:DF:77', 'Conrad Electronic SE'),
(18411, 'C0:E4:22', 'Texas Instruments'),
(18412, 'C0:E5:4E', 'DENX Computer Systems GmbH'),
(18413, 'C0:EA:E4', 'Sonicwall'),
(18414, 'C0:EE:FB', 'OnePlus Tech (Shenzhen) Ltd'),
(18415, 'C0:F1:C4', 'Pacidal Corporation Ltd.'),
(18416, 'C0:F2:FB', 'Apple'),
(18417, 'C0:F7:9D', 'Powercode'),
(18418, 'C0:F8:DA', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18419, 'C0:F9:91', 'GME Standard Communications P/L'),
(18420, 'C0:FF:D4', 'Netgear Inc'),
(18421, 'C4:00:06', 'Lipi Data Systems Ltd.'),
(18422, 'C4:01:42', 'MaxMedia Technology Limited'),
(18423, 'C4:01:7C', 'Ruckus Wireless'),
(18424, 'C4:01:B1', 'SeekTech INC'),
(18425, 'C4:01:CE', 'PRESITION (2000) CO., LTD.'),
(18426, 'C4:04:15', 'NETGEAR INC.,'),
(18427, 'C4:05:28', 'Huawei Technologies Co., Ltd'),
(18428, 'C4:08:4A', 'Alcatel-Lucent'),
(18429, 'C4:08:80', 'Shenzhen UTEPO Tech Co., Ltd.'),
(18430, 'C4:09:38', 'Fujian Star-net Communication Co., Ltd'),
(18431, 'C4:0A:CB', 'CISCO SYSTEMS, INC.'),
(18432, 'C4:0E:45', 'ACK Networks,Inc.'),
(18433, 'C4:0F:09', 'Hermes electronic GmbH'),
(18434, 'C4:10:8A', 'Ruckus Wireless'),
(18435, 'C4:14:3C', 'Cisco'),
(18436, 'C4:16:FA', 'Prysm Inc'),
(18437, 'C4:17:FE', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18438, 'C4:19:8B', 'Dominion Voting Systems Corporation'),
(18439, 'C4:19:EC', 'Qualisys AB'),
(18440, 'C4:1E:CE', 'HMI Sources Ltd.'),
(18441, 'C4:21:C8', 'KYOCERA Corporation'),
(18442, 'C4:23:7A', 'WhizNets Inc.'),
(18443, 'C4:24:2E', 'Galvanic Applied Sciences Inc'),
(18444, 'C4:26:28', 'Airo Wireless'),
(18445, 'C4:27:95', 'Technicolor USA Inc.'),
(18446, 'C4:29:1D', 'KLEMSAN ELEKTRIK ELEKTRONIK SAN.VE TIC.AS.'),
(18447, 'C4:2C:03', 'Apple'),
(18448, 'C4:34:6B', 'Hewlett Packard'),
(18449, 'C4:36:6C', 'LG Innotek'),
(18450, 'C4:36:DA', 'Rusteletech Ltd.'),
(18451, 'C4:38:D3', 'TAGATEC CO.,LTD'),
(18452, 'C4:39:3A', 'SMC Networks Inc'),
(18453, 'C4:3A:9F', 'Siconix Inc.'),
(18454, 'C4:3A:BE', 'Sony Mobile Communications AB'),
(18455, 'C4:3C:3C', 'CYBELEC SA'),
(18456, 'C4:3D:C7', 'NETGEAR'),
(18457, 'C4:42:02', 'Samsung Electronics Co.,Ltd'),
(18458, 'C4:43:8F', 'LG Electronics'),
(18459, 'C4:45:67', 'SAMBON PRECISON and ELECTRONICS'),
(18460, 'C4:45:EC', 'Shanghai Yali Electron Co.,LTD'),
(18461, 'C4:46:19', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18462, 'C4:48:38', 'Satcom Direct, Inc.'),
(18463, 'C4:4A:D0', 'FIREFLIES SYSTEMS'),
(18464, 'C4:4B:44', 'Omniprint Inc.'),
(18465, 'C4:4B:D1', 'Wallys Communications  Teachnologies Co.,Ltd.'),
(18466, 'C4:4E:1F', 'BlueN'),
(18467, 'C4:4E:AC', 'Shenzhen Shiningworth Technology Co., Ltd.'),
(18468, 'C4:50:06', 'Samsung Electronics Co.,Ltd'),
(18469, 'C4:54:44', 'QUANTA COMPUTER INC.'),
(18470, 'C4:55:A6', 'Cadac Holdings Ltd'),
(18471, 'C4:55:C2', 'Bach-Simpson'),
(18472, 'C4:56:00', 'Galleon Embedded Computing'),
(18473, 'C4:56:FE', 'Lava International Ltd.'),
(18474, 'C4:57:6E', 'Samsung Electronics Co.,LTD'),
(18475, 'C4:58:C2', 'Shenzhen TATFOOK Technology Co., Ltd.'),
(18476, 'C4:59:76', 'Fugoo Coorporation'),
(18477, 'C4:5D:D8', 'HDMI Forum'),
(18478, 'C4:60:44', 'Everex Electronics Limited'),
(18479, 'C4:62:6B', 'ZPT Vigantice'),
(18480, 'C4:62:EA', 'Samsung Electronics Co.,Ltd'),
(18481, 'C4:63:54', 'U-Raku, Inc.'),
(18482, 'C4:64:13', 'CISCO SYSTEMS, INC.'),
(18483, 'C4:67:B5', 'Libratone A/S'),
(18484, 'C4:6A:B7', 'Xiaomi Technology,Inc.'),
(18485, 'C4:6B:B4', 'myIDkey'),
(18486, 'C4:6D:F1', 'DataGravity'),
(18487, 'C4:6E:1F', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(18488, 'C4:71:30', 'Fon Technology S.L.'),
(18489, 'C4:71:FE', 'CISCO SYSTEMS, INC.'),
(18490, 'C4:72:95', 'Cisco'),
(18491, 'C4:73:1E', 'Samsung Eletronics Co., Ltd'),
(18492, 'C4:7B:2F', 'Beijing JoinHope Image Technology Ltd.'),
(18493, 'C4:7B:A3', 'NAVIS Inc.'),
(18494, 'C4:7D:4F', 'CISCO SYSTEMS, INC.'),
(18495, 'C4:7D:CC', 'Zebra Technologies Inc'),
(18496, 'C4:7D:FE', 'A.N. Solutions GmbH'),
(18497, 'C4:7F:51', 'Inventek Systems'),
(18498, 'C4:82:3F', 'Fujian Newland Auto-ID Tech. Co,.Ltd.'),
(18499, 'C4:82:4E', 'Changzhou Uchip Electronics Co., LTD.'),
(18500, 'C4:85:08', 'Intel Corporate'),
(18501, 'C4:88:E5', 'Samsung Electronics Co.,Ltd'),
(18502, 'C4:8E:8F', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18503, 'C4:91:3A', 'Shenzhen Sanland Electronic Co., ltd.'),
(18504, 'C4:92:4C', 'KEISOKUKI CENTER CO.,LTD.'),
(18505, 'C4:93:00', '8Devices'),
(18506, 'C4:93:13', '100fio networks technology llc'),
(18507, 'C4:93:80', 'Speedytel technology'),
(18508, 'C4:95:A2', 'SHENZHEN WEIJIU INDUSTRY AND TRADE DEVELOPMENT CO., LTD'),
(18509, 'C4:98:05', 'Minieum Networks, Inc'),
(18510, 'C4:A8:1D', ' D-Link International'),
(18511, 'C4:AA:A1', 'SUMMIT DEVELOPMENT, spol.s r.o.'),
(18512, 'C4:AD:21', 'MEDIAEDGE Corporation'),
(18513, 'C4:B5:12', 'General Electric Digital Energy'),
(18514, 'C4:BA:99', 'I+ME Actia Informatik und Mikro-Elektronik GmbH'),
(18515, 'C4:BD:6A', 'SKF GmbH'),
(18516, 'C4:BE:84', 'Texas Instruments.'),
(18517, 'C4:C0:AE', 'MIDORI ELECTRONIC CO., LTD.'),
(18518, 'C4:C1:9F', 'National Oilwell Varco Instrumentation, Monitoring, and Optimization (NOV IMO)'),
(18519, 'C4:C7:55', 'Beijing HuaqinWorld Technology Co.,Ltd'),
(18520, 'C4:C9:19', 'Energy Imports Ltd'),
(18521, 'C4:C9:EC', 'D&amp;D GROUP sp. z o.o.'),
(18522, 'C4:CA:D9', 'Hangzhou H3C Technologies Co., Limited'),
(18523, 'C4:CD:45', 'Beijing Boomsense Technology CO.,LTD.'),
(18524, 'C4:D4:89', 'JiangSu Joyque Information Industry Co.,Ltd'),
(18525, 'C4:D6:55', 'Tercel technology co.,ltd'),
(18526, 'C4:D9:87', 'Intel Corporate'),
(18527, 'C4:DA:26', 'NOBLEX SA'),
(18528, 'C4:E0:32', 'IEEE 1904.1 Working Group'),
(18529, 'C4:E1:7C', 'U2S co.'),
(18530, 'C4:E7:BE', 'SCSpro Co.,Ltd'),
(18531, 'C4:E9:2F', 'AB Sciex'),
(18532, 'C4:E9:84', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18533, 'C4:EB:E3', 'RRCN SAS'),
(18534, 'C4:ED:BA', 'Texas Instruments'),
(18535, 'C4:EE:AE', 'VSS Monitoring'),
(18536, 'C4:EE:F5', 'Oclaro, Inc.'),
(18537, 'C4:F4:64', 'Spica international'),
(18538, 'C4:F5:7C', 'Brocade Communications Systems, Inc.'),
(18539, 'C4:FC:E4', 'DishTV NZ Ltd'),
(18540, 'C8:02:10', 'LG Innotek'),
(18541, 'C8:02:58', 'ITW GSE ApS'),
(18542, 'C8:02:A6', 'Beijing Newmine Technology'),
(18543, 'C8:07:18', 'TDSi'),
(18544, 'C8:08:E9', 'LG Electronics'),
(18545, 'C8:0A:A9', 'Quanta Computer Inc.'),
(18546, 'C8:0E:77', 'Le Shi Zhi Xin Electronic Technology (Tianjin)  Co.,Ltd'),
(18547, 'C8:0E:95', 'OmniLync Inc.'),
(18548, 'C8:14:79', 'Samsung Electronics Co.,Ltd'),
(18549, 'C8:16:BD', 'HISENSE ELECTRIC CO.,LTD.'),
(18550, 'C8:19:F7', 'Samsung Electronics Co.,Ltd'),
(18551, 'C8:1A:FE', 'DLOGIC GmbH'),
(18552, 'C8:1B:6B', 'Innova Security'),
(18553, 'C8:1E:8E', 'ADV Security (S) Pte Ltd'),
(18554, 'C8:1F:66', 'Dell Inc'),
(18555, 'C8:20:8E', 'Storagedata'),
(18556, 'C8:29:2A', 'Barun Electronics'),
(18557, 'C8:2A:14', 'Apple'),
(18558, 'C8:2E:94', 'Halfa Enterprise Co., Ltd.'),
(18559, 'C8:31:68', 'eZEX corporation'),
(18560, 'C8:32:32', 'Hunting Innova'),
(18561, 'C8:33:4B', 'Apple'),
(18562, 'C8:35:B8', 'Ericsson, EAB/RWI/K'),
(18563, 'C8:3A:35', 'Tenda Technology Co., Ltd.'),
(18564, 'C8:3B:45', 'JRI-Maxant'),
(18565, 'C8:3D:97', 'Nokia Corporation'),
(18566, 'C8:3E:99', 'Texas Instruments'),
(18567, 'C8:3E:A7', 'KUNBUS GmbH'),
(18568, 'C8:45:29', 'IMK Networks Co.,Ltd'),
(18569, 'C8:45:44', 'Shanghai Enlogic Electric Technology Co., Ltd.'),
(18570, 'C8:48:F5', 'MEDISON Xray Co., Ltd'),
(18571, 'C8:4C:75', 'CISCO SYSTEMS, INC.'),
(18572, 'C8:56:45', 'Intermas France'),
(18573, 'C8:56:63', 'Sunflex Europe GmbH'),
(18574, 'C8:60:00', 'ASUSTek COMPUTER INC.'),
(18575, 'C8:64:C7', 'zte corporation'),
(18576, 'C8:6C:1E', 'Display Systems Ltd'),
(18577, 'C8:6C:87', 'Zyxel Communications Corp'),
(18578, 'C8:6C:B6', 'Optcom Co., Ltd.'),
(18579, 'C8:6F:1D', 'Apple'),
(18580, 'C8:72:48', 'Aplicom Oy'),
(18581, 'C8:7B:5B', 'zte corporation'),
(18582, 'C8:7C:BC', 'Valink Co., Ltd.'),
(18583, 'C8:7D:77', 'Shenzhen Kingtech Communication Equipment Co.,Ltd'),
(18584, 'C8:7E:75', 'Samsung Electronics Co.,Ltd'),
(18585, 'C8:84:39', 'Sunrise Technologies'),
(18586, 'C8:84:47', 'Beautiful Enterprise Co., Ltd'),
(18587, 'C8:85:50', 'Apple'),
(18588, 'C8:87:3B', 'Net Optics'),
(18589, 'C8:8A:83', 'Dongguan HuaHong Electronics Co.,Ltd'),
(18590, 'C8:8B:47', 'Nolangroup S.P.A con Socio Unico'),
(18591, 'C8:90:3E', 'Pakton Technologies'),
(18592, 'C8:91:F9', 'SAGEMCOM'),
(18593, 'C8:93:46', 'MXCHIP Company Limited'),
(18594, 'C8:93:83', 'Embedded Automation, Inc.'),
(18595, 'C8:94:D2', 'Jiangsu Datang  Electronic Products Co., Ltd'),
(18596, 'C8:97:9F', 'Nokia Corporation'),
(18597, 'C8:9C:1D', 'CISCO SYSTEMS, INC.'),
(18598, 'C8:9C:DC', 'ELITEGROUP COMPUTER SYSTEM CO., LTD.'),
(18599, 'C8:9F:1D', 'SHENZHEN COMMUNICATION TECHNOLOGIES CO.,LTD'),
(18600, 'C8:9F:42', 'VDII Innovation AB'),
(18601, 'C8:A0:30', 'Texas Instruments'),
(18602, 'C8:A1:B6', 'Shenzhen Longway Technologies Co., Ltd'),
(18603, 'C8:A1:BA', 'Neul Ltd'),
(18604, 'C8:A6:20', 'Nebula, Inc'),
(18605, 'C8:A7:0A', 'Verizon Business'),
(18606, 'C8:A7:29', 'SYStronics Co., Ltd.'),
(18607, 'C8:A8:23', 'Samsung Electronics Co.,Ltd'),
(18608, 'C8:AA:21', 'ARRIS Group, Inc.'),
(18609, 'C8:AA:CC', 'PRIVATE'),
(18610, 'C8:AE:9C', 'Shanghai TYD Elecronic Technology Co. Ltd'),
(18611, 'C8:AF:40', 'marco Systemanalyse und Entwicklung GmbH'),
(18612, 'C8:B3:73', 'Cisco-Linksys, LLC'),
(18613, 'C8:B5:B7', 'Apple'),
(18614, 'C8:BA:94', 'Samsung Electro Mechanics co., LTD.'),
(18615, 'C8:BB:D3', 'Embrane'),
(18616, 'C8:BC:C8', 'Apple'),
(18617, 'C8:BE:19', 'D-Link International'),
(18618, 'C8:C1:26', 'ZPM Industria e Comercio Ltda'),
(18619, 'C8:C1:3C', 'RuggedTek Hangzhou Co., Ltd'),
(18620, 'C8:C7:91', 'Zero1.tv GmbH'),
(18621, 'C8:CB:B8', 'Hewlett Packard'),
(18622, 'C8:CD:72', 'SAGEMCOM'),
(18623, 'C8:D0:19', 'Shanghai Tigercel Communication Technology Co.,Ltd'),
(18624, 'C8:D1:0B', 'Nokia Corporation'),
(18625, 'C8:D1:5E', 'Huawei Technologies Co., Ltd'),
(18626, 'C8:D1:D1', 'AGAiT Technology Corporation'),
(18627, 'C8:D2:C1', 'Jetlun (Shenzhen) Corporation'),
(18628, 'C8:D3:A3', 'D-Link International'),
(18629, 'C8:D4:29', 'Muehlbauer AG'),
(18630, 'C8:D5:90', 'FLIGHT DATA SYSTEMS'),
(18631, 'C8:D5:FE', 'Shenzhen Zowee Technology Co., Ltd'),
(18632, 'C8:D7:19', 'Cisco Consumer Products, LLC'),
(18633, 'C8:D7:79', 'Qingdao Haier Telecom Co.ï¼Ltd'),
(18634, 'C8:DD:C9', 'Lenovo Mobile Communication Technology Ltd.'),
(18635, 'C8:DE:51', 'Integra Networks, Inc.'),
(18636, 'C8:DF:7C', 'Nokia Corporation'),
(18637, 'C8:E0:EB', 'Apple'),
(18638, 'C8:E1:A7', 'Vertu Corporation Limited'),
(18639, 'C8:E4:2F', 'Technical Research Design and Development'),
(18640, 'C8:E7:D8', 'SHENZHEN MERCURY COMMUNICATION TECHNOLOGIES CO.,LTD.'),
(18641, 'C8:EE:08', 'TANGTOP TECHNOLOGY CO.,LTD'),
(18642, 'C8:EE:75', 'Pishion International Co. Ltd'),
(18643, 'C8:EE:A6', 'Shenzhen SHX Technology Co., Ltd'),
(18644, 'C8:EF:2E', 'Beijing Gefei Tech. Co., Ltd'),
(18645, 'C8:F3:6B', 'Yamato Scale Co.,Ltd.'),
(18646, 'C8:F3:86', 'Shenzhen Xiaoniao Technology Co.,Ltd'),
(18647, 'C8:F4:06', 'Avaya, Inc'),
(18648, 'C8:F6:50', 'Apple'),
(18649, 'C8:F6:8D', 'S.E.TECHNOLOGIES LIMITED'),
(18650, 'C8:F7:04', 'Building Block Video'),
(18651, 'C8:F7:33', 'Intel Corporate'),
(18652, 'C8:F9:81', 'Seneca s.r.l.'),
(18653, 'C8:F9:F9', 'CISCO SYSTEMS, INC.'),
(18654, 'C8:FB:26', 'Cisco SPVTG'),
(18655, 'C8:FE:30', 'Bejing DAYO Mobile Communication Technology Ltd.'),
(18656, 'C8:FF:77', 'Dyson Limited'),
(18657, 'CC:00:80', 'BETTINI SRL'),
(18658, 'CC:03:FA', 'Technicolor CH USA'),
(18659, 'CC:04:7C', 'G-WAY Microwave'),
(18660, 'CC:04:B4', 'Select Comfort'),
(18661, 'CC:05:1B', 'Samsung Electronics Co.,Ltd'),
(18662, 'CC:07:AB', 'Samsung Electronics Co.,Ltd'),
(18663, 'CC:07:E4', 'Lenovo Mobile Communication Technology Ltd.'),
(18664, 'CC:08:E0', 'Apple'),
(18665, 'CC:09:C8', 'IMAQLIQ LTD'),
(18666, 'CC:0C:DA', 'Miljovakt AS'),
(18667, 'CC:0D:EC', 'Cisco SPVTG'),
(18668, 'CC:10:A3', 'Beijing Nan Bao Technology Co., Ltd.'),
(18669, 'CC:14:A6', 'Yichun MyEnergy Domain, Inc'),
(18670, 'CC:18:7B', 'Manzanita Systems, Inc.'),
(18671, 'CC:1A:FA', 'zte corporation'),
(18672, 'CC:1E:FF', 'Metrological Group BV'),
(18673, 'CC:22:18', 'InnoDigital Co., Ltd.'),
(18674, 'CC:26:2D', 'Verifi, LLC'),
(18675, 'CC:2A:80', 'Micro-Biz intelligence solutions Co.,Ltd'),
(18676, 'CC:2D:8C', 'LG ELECTRONICS INC'),
(18677, 'CC:30:80', 'VAIO Corporation'),
(18678, 'CC:33:BB', 'SAGEMCOM SAS'),
(18679, 'CC:34:29', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18680, 'CC:34:D7', 'GEWISS S.P.A.'),
(18681, 'CC:35:40', 'Technicolor USA Inc.'),
(18682, 'CC:39:8C', 'Shiningtek'),
(18683, 'CC:3A:61', 'SAMSUNG ELECTRO MECHANICS CO., LTD.'),
(18684, 'CC:3C:3F', 'SA.S.S. Datentechnik AG'),
(18685, 'CC:3D:82', 'Intel Corporate'),
(18686, 'CC:3E:5F', 'Hewlett Packard'),
(18687, 'CC:3F:1D', 'Intesis Software SL'),
(18688, 'CC:43:E3', 'Trump s.a.'),
(18689, 'CC:47:03', 'Intercon Systems Co., Ltd.'),
(18690, 'CC:4A:E1', 'Fourtec -Fourier Technologies'),
(18691, 'CC:4B:FB', 'Hellberg Safety AB'),
(18692, 'CC:4E:24', 'Brocade Communications Systems, Inc.'),
(18693, 'CC:50:1C', 'KVH Industries, Inc.'),
(18694, 'CC:50:76', 'Ocom Communications, Inc.'),
(18695, 'CC:52:AF', 'Universal Global Scientific Industrial Co., Ltd.'),
(18696, 'CC:53:B5', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(18697, 'CC:54:59', 'OnTime Networks AS'),
(18698, 'CC:55:AD', 'RIM'),
(18699, 'CC:59:3E', 'TOUMAZ LTD'),
(18700, 'CC:5C:75', 'Weightech Com. Imp. Exp. Equip. Pesagem Ltda'),
(18701, 'CC:5D:4E', 'ZyXEL Communications Corporation'),
(18702, 'CC:5D:57', 'Information  System Research Institute,Inc.'),
(18703, 'CC:60:BB', 'Empower RF Systems'),
(18704, 'CC:65:AD', 'ARRIS Group, Inc.'),
(18705, 'CC:69:B0', 'Global Traffic Technologies, LLC'),
(18706, 'CC:6B:98', 'Minetec Wireless Technologies'),
(18707, 'CC:6B:F1', 'Sound Masking Inc.'),
(18708, 'CC:6D:A0', 'Roku, Inc.'),
(18709, 'CC:6D:EF', 'TJK Tietolaite Oy'),
(18710, 'CC:72:0F', 'Viscount Systems Inc.'),
(18711, 'CC:74:98', 'Filmetrics Inc.'),
(18712, 'CC:76:69', 'SEETECH'),
(18713, 'CC:78:5F', 'Apple'),
(18714, 'CC:7A:30', 'CMAX Wireless Co., Ltd.'),
(18715, 'CC:7B:35', 'zte corporation'),
(18716, 'CC:7D:37', 'ARRIS Group, Inc.'),
(18717, 'CC:7E:E7', 'Panasonic AVC Networks Company'),
(18718, 'CC:85:6C', 'SHENZHEN MDK DIGITAL TECHNOLOGY CO.,LTD'),
(18719, 'CC:89:FD', 'Nokia Corporation'),
(18720, 'CC:8C:E3', 'Texas Instruments'),
(18721, 'CC:90:93', 'Hansong Tehnologies'),
(18722, 'CC:91:2B', 'TE Connectivity Touch Solutions'),
(18723, 'CC:94:4A', 'Pfeiffer Vacuum GmbH'),
(18724, 'CC:95:D7', 'VIZIO, Inc'),
(18725, 'CC:96:35', 'LVS Co.,Ltd.'),
(18726, 'CC:96:A0', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(18727, 'CC:9E:00', 'Nintendo Co., Ltd.'),
(18728, 'CC:9F:35', 'Transbit Sp. z o.o.'),
(18729, 'CC:A0:E5', 'DZG Metering GmbH'),
(18730, 'CC:A2:23', 'Huawei Technologies Co., Ltd'),
(18731, 'CC:A3:74', 'Guangdong Guanglian Electronic Technology Co.Ltd'),
(18732, 'CC:A4:62', 'ARRIS Group, Inc.'),
(18733, 'CC:A4:AF', 'Shenzhen Sowell Technology Co., LTD'),
(18734, 'CC:A6:14', 'AIFA TECHNOLOGY CORP.'),
(18735, 'CC:AF:78', 'Hon Hai Precision Ind. Co.,Ltd.'),
(18736, 'CC:B2:55', 'D-Link International'),
(18737, 'CC:B3:F8', 'FUJITSU ISOTEC LIMITED'),
(18738, 'CC:B5:5A', 'Fraunhofer ITWM'),
(18739, 'CC:B6:91', 'NECMagnusCommunications'),
(18740, 'CC:B8:88', 'AnB Securite s.a.'),
(18741, 'CC:B8:F1', 'EAGLE KINGDOM TECHNOLOGIES LIMITED'),
(18742, 'CC:BD:35', 'Steinel GmbH'),
(18743, 'CC:BD:D3', 'Ultimaker B.V.'),
(18744, 'CC:BE:71', 'OptiLogix BV'),
(18745, 'CC:C1:04', 'Applied Technical Systems'),
(18746, 'CC:C3:EA', 'Motorola Mobility LLC'),
(18747, 'CC:C5:0A', 'SHENZHEN DAJIAHAO TECHNOLOGY CO.,LTD'),
(18748, 'CC:C6:2B', 'Tri-Systems Corporation'),
(18749, 'CC:C8:D7', 'CIAS Elettronica srl'),
(18750, 'CC:CC:4E', 'Sun Fountainhead USA. Corp'),
(18751, 'CC:CC:81', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(18752, 'CC:CD:64', 'SM-Electronic GmbH'),
(18753, 'CC:CE:40', 'Janteq Corp'),
(18754, 'CC:D2:9B', 'Shenzhen Bopengfa Elec&amp;Technology CO.,Ltd'),
(18755, 'CC:D5:39', 'Cisco'),
(18756, 'CC:D8:11', 'Aiconn Technology Corporation'),
(18757, 'CC:D8:C1', 'Cisco'),
(18758, 'CC:D9:E9', 'SCR Engineers Ltd.'),
(18759, 'CC:E1:7F', 'juniper networks'),
(18760, 'CC:E1:D5', 'Buffalo Inc.'),
(18761, 'CC:E7:98', 'My Social Stuff'),
(18762, 'CC:E7:DF', 'American Magnetics, Inc.'),
(18763, 'CC:E8:AC', 'SOYEA Technology Co.,Ltd.'),
(18764, 'CC:EA:1C', 'DCONWORKS  Co., Ltd'),
(18765, 'CC:EE:D9', 'Deto Mechatronic GmbH'),
(18766, 'CC:EF:48', 'CISCO SYSTEMS, INC.'),
(18767, 'CC:F3:A5', 'Chi Mei Communication Systems, Inc'),
(18768, 'CC:F4:07', 'EUKREA ELECTROMATIQUE SARL'),
(18769, 'CC:F5:38', '3isysnetworks'),
(18770, 'CC:F6:7A', 'Ayecka Communication Systems LTD'),
(18771, 'CC:F8:41', 'Lumewave'),
(18772, 'CC:F8:F0', 'Xi\'an HISU Multimedia Technology Co.,Ltd.'),
(18773, 'CC:F9:54', 'Avaya, Inc'),
(18774, 'CC:F9:E8', 'Samsung Electronics Co.,Ltd'),
(18775, 'CC:FA:00', 'LG Electronics'),
(18776, 'CC:FB:65', 'Nintendo Co., Ltd.'),
(18777, 'CC:FC:6D', 'RIZ TRANSMITTERS'),
(18778, 'CC:FC:B1', 'Wireless Technology, Inc.'),
(18779, 'CC:FE:3C', 'Samsung Electronics'),
(18780, 'D0:07:90', 'Texas Instruments'),
(18781, 'D0:0A:AB', 'Yokogawa Digital Computer Corporation'),
(18782, 'D0:0E:A4', 'Porsche Cars North America'),
(18783, 'D0:12:42', 'BIOS Corporation'),
(18784, 'D0:13:1E', 'Sunrex Technology Corp'),
(18785, 'D0:15:4A', 'zte corporation'),
(18786, 'D0:17:6A', 'Samsung Electronics Co.,Ltd'),
(18787, 'D0:1A:A7', 'UniPrint'),
(18788, 'D0:1C:BB', 'Beijing Ctimes Digital Technology Co., Ltd.'),
(18789, 'D0:22:12', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(18790, 'D0:22:BE', 'Samsung Electro Mechanics co.,LTD.'),
(18791, 'D0:23:DB', 'Apple'),
(18792, 'D0:27:88', 'Hon Hai Precision Ind.Co.Ltd'),
(18793, 'D0:2C:45', 'littleBits Electronics, Inc.'),
(18794, 'D0:2D:B3', 'Huawei Technologies Co., Ltd'),
(18795, 'D0:31:10', 'Ingenic Semiconductor Co.,Ltd'),
(18796, 'D0:37:61', 'Texas Instruments'),
(18797, 'D0:39:72', 'Texas Instruments'),
(18798, 'D0:39:B3', 'ARRIS Group, Inc.'),
(18799, 'D0:46:DC', 'Southwest Research Institute'),
(18800, 'D0:4C:C1', 'SINTRONES Technology Corp.'),
(18801, 'D0:4F:7E', 'Apple'),
(18802, 'D0:50:99', 'ASRock Incorporation'),
(18803, 'D0:51:62', 'Sony Mobile Communications AB'),
(18804, 'D0:52:A8', 'Physical Graph Corporation'),
(18805, 'D0:53:49', 'Liteon Technology Co., Ltd.'),
(18806, 'D0:54:2D', 'Cambridge Industries(Group) Co.,Ltd.'),
(18807, 'D0:57:4C', 'CISCO SYSTEMS, INC.'),
(18808, 'D0:57:85', 'Pantech Co., Ltd.'),
(18809, 'D0:57:A1', 'Werma Signaltechnik GmbH &amp; Co. KG'),
(18810, 'D0:58:75', 'Active Control Technology Inc.'),
(18811, 'D0:59:C3', 'CeraMicro Technology Corporation'),
(18812, 'D0:59:E4', 'Samsung Electronics Co.,Ltd'),
(18813, 'D0:5A:0F', 'I-BT DIGITAL CO.,LTD'),
(18814, 'D0:5A:F1', 'Shenzhen Pulier Tech CO.,Ltd'),
(18815, 'D0:5B:A8', 'zte corporation'),
(18816, 'D0:5F:B8', 'Texas Instruments'),
(18817, 'D0:5F:CE', 'Hitachi Data Systems'),
(18818, 'D0:62:A0', 'China Essence Technology (Zhumadian) Co., Ltd.'),
(18819, 'D0:63:4D', 'Meiko Maschinenbau GmbH &amp;amp; Co. KG'),
(18820, 'D0:63:B4', 'SolidRun Ltd.'),
(18821, 'D0:66:7B', 'Samsung Electronics Co., LTD'),
(18822, 'D0:67:E5', 'Dell Inc'),
(18823, 'D0:69:9E', 'LUMINEX Lighting Control Equipment'),
(18824, 'D0:69:D0', 'Verto Medical Solutions, LLC'),
(18825, 'D0:6F:4A', 'TOPWELL INTERNATIONAL HOLDINGS LIMITED'),
(18826, 'D0:72:DC', 'Cisco'),
(18827, 'D0:73:7F', 'Mini-Circuits'),
(18828, 'D0:73:8E', 'DONG OH PRECISION CO., LTD.'),
(18829, 'D0:73:D5', 'LIFI LABS MANAGEMENT PTY LTD'),
(18830, 'D0:75:BE', 'Reno A&amp;E'),
(18831, 'D0:76:50', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(18832, 'D0:7A:B5', 'Huawei Technologies Co., Ltd'),
(18833, 'D0:7D:E5', 'Forward Pay Systems, Inc.'),
(18834, 'D0:7E:28', 'Hewlett Packard'),
(18835, 'D0:7E:35', 'Intel Corporate'),
(18836, 'D0:84:B0', 'Sagemcom'),
(18837, 'D0:89:99', 'APCON, Inc.'),
(18838, 'D0:8A:55', 'Skullcandy'),
(18839, 'D0:8B:7E', 'Passif Semiconductor'),
(18840, 'D0:8C:B5', 'Texas Instruments'),
(18841, 'D0:8C:FF', 'UPWIS AB'),
(18842, 'D0:92:9E', 'Microsoft Corporation'),
(18843, 'D0:93:F8', 'Stonestreet One LLC'),
(18844, 'D0:95:C7', 'Pantech Co., Ltd.'),
(18845, 'D0:9B:05', 'Emtronix'),
(18846, 'D0:9C:30', 'Foster Electric Company, Limited'),
(18847, 'D0:9D:0A', 'LINKCOM'),
(18848, 'D0:A0:D6', 'Chengdu TD Tech Ltd.'),
(18849, 'D0:A3:11', 'Neuberger Geb&auml;udeautomation GmbH'),
(18850, 'D0:A5:A6', 'Cisco'),
(18851, 'D0:A6:37', 'Apple'),
(18852, 'D0:AE:EC', 'Alpha Networks Inc.'),
(18853, 'D0:AF:B6', 'Linktop Technology Co., LTD'),
(18854, 'D0:B3:3F', 'SHENZHEN TINNO MOBILE TECHNOLOGY CO.,LTD.'),
(18855, 'D0:B4:98', 'Robert Bosch LLC Automotive Electronics'),
(18856, 'D0:B5:23', 'Bestcare Cloucal Corp.'),
(18857, 'D0:B5:3D', 'SEPRO ROBOTIQUE'),
(18858, 'D0:BB:80', 'SHL Telemedicine International Ltd.'),
(18859, 'D0:BD:01', 'DS International'),
(18860, 'D0:BE:2C', 'CNSLink Co., Ltd.'),
(18861, 'D0:BF:9C', 'Hewlett Packard'),
(18862, 'D0:C1:B1', 'Samsung Electronics Co.,Ltd'),
(18863, 'D0:C2:82', 'CISCO SYSTEMS, INC.'),
(18864, 'D0:C4:2F', 'Tamagawa Seiki Co.,Ltd.'),
(18865, 'D0:C7:89', 'Cisco'),
(18866, 'D0:C7:C0', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18867, 'D0:CD:E1', 'Scientech Electronics'),
(18868, 'D0:CF:5E', 'Energy Micro AS'),
(18869, 'D0:D0:FD', 'CISCO SYSTEMS, INC.'),
(18870, 'D0:D2:12', 'K2NET Co.,Ltd.'),
(18871, 'D0:D2:86', 'Beckman Coulter K.K.'),
(18872, 'D0:D3:FC', 'Mios, Ltd.'),
(18873, 'D0:D4:12', 'ADB Broadband Italia'),
(18874, 'D0:D4:71', 'MVTECH co., Ltd'),
(18875, 'D0:D6:CC', 'Wintop'),
(18876, 'D0:DB:32', 'Nokia Corporation'),
(18877, 'D0:DF:9A', 'Liteon Technology Corporation'),
(18878, 'D0:DF:B2', 'Genie Networks Limited'),
(18879, 'D0:DF:C7', 'Samsung Electronics Co.,Ltd'),
(18880, 'D0:E1:40', 'Apple, Inc'),
(18881, 'D0:E3:47', 'Yoga'),
(18882, 'D0:E4:0B', 'Wearable Inc.'),
(18883, 'D0:E5:4D', 'Pace plc'),
(18884, 'D0:E7:82', 'Azurewave Technologies, Inc.'),
(18885, 'D0:EB:03', 'Zhehua technology limited'),
(18886, 'D0:EB:9E', 'Seowoo Inc.'),
(18887, 'D0:F0:DB', 'Ericsson'),
(18888, 'D0:F2:7F', 'SteadyServ Technoligies, LLC'),
(18889, 'D0:F7:3B', 'Helmut Mauell GmbH'),
(18890, 'D0:FA:1D', 'Qihoo  360  Technology Co.,Ltd'),
(18891, 'D0:FF:50', 'Texas Instruments, Inc'),
(18892, 'D4:00:0D', 'Phoenix Broadband Technologies, LLC.'),
(18893, 'D4:00:57', 'MC Technologies GmbH'),
(18894, 'D4:01:29', 'Broadcom Corporation'),
(18895, 'D4:01:6D', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(18896, 'D4:02:4A', 'Delphian Systems LLC'),
(18897, 'D4:05:98', 'ARRIS Group, Inc.'),
(18898, 'D4:0B:1A', 'HTC Corporation'),
(18899, 'D4:0B:B9', 'Solid Semecs bv.'),
(18900, 'D4:0F:B2', 'Applied Micro Electronics AME bv'),
(18901, 'D4:10:90', 'iNFORM Systems AG'),
(18902, 'D4:10:CF', 'Huanshun Network Science and Technology Co., Ltd.'),
(18903, 'D4:11:D6', 'ShotSpotter, Inc.'),
(18904, 'D4:12:96', 'Anobit Technologies Ltd.'),
(18905, 'D4:12:BB', 'Quadrant Components Inc. Ltd'),
(18906, 'D4:13:6F', 'Asia Pacific Brands'),
(18907, 'D4:1C:1C', 'RCF S.P.A.'),
(18908, 'D4:1E:35', 'TOHO Electronics INC.'),
(18909, 'D4:1F:0C', 'JAI Oy'),
(18910, 'D4:20:6D', 'HTC Corporation'),
(18911, 'D4:21:22', 'Sercomm Corporation'),
(18912, 'D4:22:3F', 'Lenovo Mobile Communication Technology Ltd.'),
(18913, 'D4:22:4E', 'Alcatel Lucent'),
(18914, 'D4:27:51', 'Infopia Co., Ltd'),
(18915, 'D4:28:B2', 'ioBridge, Inc.'),
(18916, 'D4:29:EA', 'Zimory GmbH'),
(18917, 'D4:2C:3D', 'Sky Light Digital Limited'),
(18918, 'D4:2F:23', 'Akenori PTE Ltd'),
(18919, 'D4:31:9D', 'Sinwatec'),
(18920, 'D4:32:66', 'Fike Corporation'),
(18921, 'D4:37:D7', 'zte corporation'),
(18922, 'D4:3A:65', 'IGRS Engineering Lab Ltd.'),
(18923, 'D4:3A:E9', 'DONGGUAN ipt INDUSTRIAL CO., LTD'),
(18924, 'D4:3D:67', 'Carma Industries Inc.'),
(18925, 'D4:3D:7E', 'Micro-Star Int\'l Co, Ltd'),
(18926, 'D4:43:A8', 'Changzhou Haojie Electric Co., Ltd.'),
(18927, 'D4:4B:5E', 'TAIYO YUDEN CO., LTD.'),
(18928, 'D4:4C:24', 'Vuppalamritha Magnetic Components LTD'),
(18929, 'D4:4C:9C', 'Shenzhen YOOBAO Technology Co.Ltd'),
(18930, 'D4:4C:A7', 'Informtekhnika &amp; Communication, LLC'),
(18931, 'D4:4F:80', 'Kemper Digital GmbH'),
(18932, 'D4:50:7A', 'CEIVA Logic, Inc'),
(18933, 'D4:52:51', 'IBT Ingenieurbureau Broennimann Thun'),
(18934, 'D4:52:97', 'nSTREAMS Technologies, Inc.'),
(18935, 'D4:53:AF', 'VIGO System S.A.'),
(18936, 'D4:55:56', 'Fiber Mountain Inc.'),
(18937, 'D4:5A:B2', 'Galleon Systems'),
(18938, 'D4:5C:70', 'Wi-Fi Alliance'),
(18939, 'D4:5D:42', 'Nokia Corporation'),
(18940, 'D4:61:32', 'Pro Concept Manufacturer Co.,Ltd.'),
(18941, 'D4:64:F7', 'CHENGDU USEE DIGITAL TECHNOLOGY CO., LTD'),
(18942, 'D4:66:A8', 'Riedo Networks GmbH'),
(18943, 'D4:67:61', 'SAHAB TECHNOLOGY'),
(18944, 'D4:67:E7', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(18945, 'D4:68:4D', 'Ruckus Wireless'),
(18946, 'D4:68:67', 'Neoventus Design Group'),
(18947, 'D4:68:BA', 'Shenzhen Sundray Technologies Company Limited'),
(18948, 'D4:6A:91', 'Snap AV'),
(18949, 'D4:6A:A8', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(18950, 'D4:6C:BF', 'Goodrich ISR'),
(18951, 'D4:6C:DA', 'CSM GmbH'),
(18952, 'D4:6D:50', 'Cisco'),
(18953, 'D4:6E:5C', 'Huawei Technologies Co., Ltd'),
(18954, 'D4:6F:42', 'WAXESS USA Inc'),
(18955, 'D4:79:C3', 'Cameronet GmbH &amp; Co. KG'),
(18956, 'D4:7B:35', 'NEO Monitors AS'),
(18957, 'D4:7B:75', 'HARTING Electronics GmbH'),
(18958, 'D4:81:CA', 'iDevices, LLC'),
(18959, 'D4:82:3E', 'Argosy Technologies, Ltd.'),
(18960, 'D4:85:64', 'Hewlett-Packard Company'),
(18961, 'D4:87:D8', 'Samsung Electronics'),
(18962, 'D4:88:90', 'Samsung Electronics Co.,Ltd'),
(18963, 'D4:8C:B5', 'CISCO SYSTEMS, INC.'),
(18964, 'D4:8D:D9', 'Meld Technology, Inc'),
(18965, 'D4:8F:33', 'Microsoft Corporation'),
(18966, 'D4:8F:AA', 'Sogecam Industrial, S.A.'),
(18967, 'D4:91:AF', 'Electroacustica General Iberica, S.A.'),
(18968, 'D4:93:98', 'Nokia Corporation'),
(18969, 'D4:93:A0', 'Fidelix Oy'),
(18970, 'D4:94:5A', 'COSMO CO., LTD'),
(18971, 'D4:94:A1', 'Texas Instruments'),
(18972, 'D4:95:24', 'Clover Network, Inc.'),
(18973, 'D4:96:DF', 'SUNGJIN C&amp;T CO.,LTD'),
(18974, 'D4:97:0B', 'XIAOMI CORPORATION'),
(18975, 'D4:9A:20', 'Apple'),
(18976, 'D4:9C:28', 'JayBird Gear LLC'),
(18977, 'D4:9C:8E', 'University of FUKUI'),
(18978, 'D4:9E:6D', 'Wuhan Zhongyuan Huadian Science &amp; Technology Co.,'),
(18979, 'D4:A0:2A', 'CISCO SYSTEMS, INC.'),
(18980, 'D4:A4:25', 'SMAX Technology Co., Ltd.'),
(18981, 'D4:A4:99', 'InView Technology Corporation'),
(18982, 'D4:A9:28', 'GreenWave Reality Inc'),
(18983, 'D4:AA:FF', 'MICRO WORLD'),
(18984, 'D4:AC:4E', 'BODi rS, LLC'),
(18985, 'D4:AD:2D', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(18986, 'D4:AE:52', 'Dell Inc'),
(18987, 'D4:B1:10', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(18988, 'D4:B4:3E', 'Messcomp Datentechnik GmbH'),
(18989, 'D4:BE:D9', 'Dell Inc'),
(18990, 'D4:BF:2D', 'SE Controls Asia Pacific Ltd'),
(18991, 'D4:BF:7F', 'UPVEL'),
(18992, 'D4:C1:FC', 'Nokia Corporation'),
(18993, 'D4:C7:66', 'Acentic GmbH'),
(18994, 'D4:C9:EF', 'Hewlett Packard'),
(18995, 'D4:CA:6D', 'Routerboard.com'),
(18996, 'D4:CA:6E', 'u-blox AG'),
(18997, 'D4:CB:AF', 'Nokia Corporation'),
(18998, 'D4:CE:B8', 'Enatel LTD'),
(18999, 'D4:CF:F9', 'Shenzhen Sen5 Technology Co., Ltd.'),
(19000, 'D4:D1:84', 'ADB Broadband Italia'),
(19001, 'D4:D2:49', 'Power Ethernet'),
(19002, 'D4:D5:0D', 'Southwest Microwave, Inc'),
(19003, 'D4:D7:48', 'CISCO SYSTEMS, INC.'),
(19004, 'D4:D8:98', 'Korea CNO Tech Co., Ltd'),
(19005, 'D4:D9:19', 'GoPro'),
(19006, 'D4:DF:57', 'Alpinion Medical Systems'),
(19007, 'D4:E0:8E', 'ValueHD Corporation'),
(19008, 'D4:E3:2C', 'S. Siedle &amp; Sohne'),
(19009, 'D4:E3:3F', 'Alcatel-Lucent'),
(19010, 'D4:E8:B2', 'Samsung Electronics'),
(19011, 'D4:EA:0E', 'Avaya, Inc'),
(19012, 'D4:EC:0C', 'Harley-Davidson Motor Company'),
(19013, 'D4:EC:86', 'LinkedHope Intelligent Technologies Co., Ltd'),
(19014, 'D4:EE:07', 'HIWIFI Co., Ltd.'),
(19015, 'D4:F0:27', 'Navetas Energy Management'),
(19016, 'D4:F0:B4', 'Napco Security Technologies'),
(19017, 'D4:F1:43', 'IPROAD.,Inc'),
(19018, 'D4:F4:6F', 'Apple'),
(19019, 'D4:F5:13', 'Texas Instruments'),
(19020, 'D4:F6:3F', 'IEA S.R.L.'),
(19021, 'D8:00:4D', 'Apple'),
(19022, 'D8:05:2E', 'Skyviia Corporation'),
(19023, 'D8:06:D1', 'Honeywell Fire System (Shanghai) Co,. Ltd.'),
(19024, 'D8:08:F5', 'Arcadia Networks Co. Ltd.'),
(19025, 'D8:09:C3', 'Cercacor Labs'),
(19026, 'D8:0C:CF', 'C.G.V. S.A.S.'),
(19027, 'D8:0D:E3', 'FXI TECHNOLOGIES AS'),
(19028, 'D8:15:0D', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(19029, 'D8:16:0A', 'Nippon Electro-Sensory Devices'),
(19030, 'D8:18:2B', 'Conti Temic Microelectronic GmbH'),
(19031, 'D8:19:CE', 'Telesquare'),
(19032, 'D8:1B:FE', 'TWINLINX CORPORATION'),
(19033, 'D8:1C:14', 'Compacta International, Ltd.'),
(19034, 'D8:1E:DE', 'B&amp;W Group Ltd'),
(19035, 'D8:24:BD', 'CISCO SYSTEMS, INC.'),
(19036, 'D8:25:22', 'Pace plc'),
(19037, 'D8:26:B9', 'Guangdong Coagent Electronics S &amp;T Co., Ltd.'),
(19038, 'D8:27:0C', 'MaxTronic International Co., Ltd.'),
(19039, 'D8:28:C9', 'General Electric Consumer and Industrial'),
(19040, 'D8:29:16', 'Ascent Communication Technology'),
(19041, 'D8:29:86', 'Best Wish Technology LTD'),
(19042, 'D8:2A:15', 'Leitner SpA'),
(19043, 'D8:2A:7E', 'Nokia Corporation'),
(19044, 'D8:2D:9B', 'Shenzhen G.Credit Communication Technology Co., Ltd'),
(19045, 'D8:2D:E1', 'Tricascade Inc.'),
(19046, 'D8:30:62', 'Apple'),
(19047, 'D8:31:CF', 'Samsung Electronics Co.,Ltd'),
(19048, 'D8:33:7F', 'Office FA.com Co.,Ltd.'),
(19049, 'D8:3C:69', 'Tinno Mobile Technology Corp'),
(19050, 'D8:42:AC', 'Shanghai Feixun Communication Co.,Ltd.'),
(19051, 'D8:46:06', 'Silicon Valley Global Marketing'),
(19052, 'D8:49:0B', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19053, 'D8:49:2F', 'CANON INC.'),
(19054, 'D8:4A:87', 'OI ELECTRIC CO.,LTD'),
(19055, 'D8:4B:2A', 'Cognitas Technologies, Inc.'),
(19056, 'D8:50:E6', 'ASUSTek COMPUTER INC.'),
(19057, 'D8:54:3A', 'Texas Instruments'),
(19058, 'D8:55:A3', 'zte corporation'),
(19059, 'D8:57:EF', 'Samsung Electronics'),
(19060, 'D8:58:D7', 'CZ.NIC, z.s.p.o.'),
(19061, 'D8:5D:4C', 'TP-LINK Technologies Co.,Ltd.'),
(19062, 'D8:5D:84', 'CAx soft GmbH'),
(19063, 'D8:5D:E2', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19064, 'D8:5D:FB', 'PRIVATE'),
(19065, 'D8:61:94', 'Objetivos y Sevicios de Valor AnÌadido'),
(19066, 'D8:62:DB', 'Eno Inc.'),
(19067, 'D8:65:95', 'Toy\'s Myth Inc.'),
(19068, 'D8:66:C6', 'Shenzhen Daystar Technology Co.,ltd'),
(19069, 'D8:66:EE', 'BOXIN COMMUNICATION CO.,LTD.'),
(19070, 'D8:67:D9', 'CISCO SYSTEMS, INC.'),
(19071, 'D8:69:60', 'Steinsvik'),
(19072, 'D8:6B:F7', 'Nintendo Co., Ltd.'),
(19073, 'D8:6C:E9', 'SAGEMCOM SAS'),
(19074, 'D8:71:57', 'Lenovo Mobile Communication Technology Ltd.'),
(19075, 'D8:74:95', 'zte corporation'),
(19076, 'D8:75:33', 'Nokia Corporation'),
(19077, 'D8:76:0A', 'Escort, Inc.'),
(19078, 'D8:78:E5', 'KUHN SA'),
(19079, 'D8:79:88', 'Hon Hai Precision Ind. Co., Ltd.'),
(19080, 'D8:7C:DD', 'SANIX INCORPORATED'),
(19081, 'D8:7E:B1', 'x.o.ware, inc.'),
(19082, 'D8:80:39', 'Microchip Technology Inc.'),
(19083, 'D8:81:CE', 'AHN INC.'),
(19084, 'D8:84:66', 'Extreme Networks'),
(19085, 'D8:8A:3B', 'UNIT-EM'),
(19086, 'D8:8D:5C', 'Elentec'),
(19087, 'D8:90:E8', 'Samsung Electronics Co.,Ltd'),
(19088, 'D8:93:41', 'General Electric Global Research'),
(19089, 'D8:95:2F', 'Texas Instruments'),
(19090, 'D8:96:85', 'GoPro'),
(19091, 'D8:96:95', 'Apple'),
(19092, 'D8:96:E0', 'Alibaba Cloud Computing Ltd.'),
(19093, 'D8:97:3B', 'Artesyn Embedded Technologies'),
(19094, 'D8:97:60', 'C2 Development, Inc.'),
(19095, 'D8:97:7C', 'Grey Innovation'),
(19096, 'D8:97:BA', 'PEGATRON CORPORATION'),
(19097, 'D8:9D:67', 'Hewlett Packard'),
(19098, 'D8:9D:B9', 'eMegatech International Corp.'),
(19099, 'D8:9E:3F', 'Apple'),
(19100, 'D8:A2:5E', 'Apple'),
(19101, 'D8:AE:90', 'Itibia Technologies'),
(19102, 'D8:AF:3B', 'Hangzhou Bigbright Integrated communications system Co.,Ltd'),
(19103, 'D8:AF:F1', 'Panasonic Appliances Company'),
(19104, 'D8:B0:2E', 'Guangzhou Zonerich Business Machine Co., Ltd'),
(19105, 'D8:B0:4C', 'Jinan USR IOT Technology Co., Ltd.'),
(19106, 'D8:B1:2A', 'Panasonic Mobile Communications Co., Ltd.'),
(19107, 'D8:B3:77', 'HTC Corporation'),
(19108, 'D8:B6:B7', 'Comtrend Corporation'),
(19109, 'D8:B6:C1', 'NetworkAccountant, Inc.'),
(19110, 'D8:B6:D6', 'Blu Tether Limited'),
(19111, 'D8:B8:F6', 'Nantworks'),
(19112, 'D8:B9:0E', 'Triple Domain Vision Co.,Ltd.'),
(19113, 'D8:BB:2C', 'Apple'),
(19114, 'D8:BF:4C', 'Victory Concept Electronics Limited'),
(19115, 'D8:C0:68', 'Netgenetech.co.,ltd.'),
(19116, 'D8:C3:FB', 'DETRACOM'),
(19117, 'D8:C6:91', 'Hichan Technology Corp.'),
(19118, 'D8:C7:C8', 'Aruba Networks'),
(19119, 'D8:C9:9D', 'EA DISPLAY LIMITED'),
(19120, 'D8:CB:8A', 'Micro-Star INTL CO., LTD.'),
(19121, 'D8:CF:9C', 'Apple'),
(19122, 'D8:D1:CB', 'Apple'),
(19123, 'D8:D2:7C', 'JEMA ENERGY, SA'),
(19124, 'D8:D3:85', 'Hewlett-Packard Company'),
(19125, 'D8:D4:3C', 'Sony Corporation'),
(19126, 'D8:D5:B9', 'Rainforest Automation, Inc.'),
(19127, 'D8:D6:7E', 'GSK CNC EQUIPMENT CO.,LTD'),
(19128, 'D8:DA:52', 'APATOR S.A.'),
(19129, 'D8:DC:E9', 'Kunshan Erlab ductless filtration system Co.,Ltd'),
(19130, 'D8:DD:5F', 'BALMUDA Inc.'),
(19131, 'D8:DD:FD', 'Texas Instruments'),
(19132, 'D8:DE:CE', 'ISUNG CO.,LTD'),
(19133, 'D8:DF:0D', 'beroNet GmbH'),
(19134, 'D8:E3:AE', 'CIRTEC MEDICAL SYSTEMS'),
(19135, 'D8:E5:6D', 'TCT Mobile Limited'),
(19136, 'D8:E7:2B', 'NetScout Systems, Inc.'),
(19137, 'D8:E7:43', 'Wush, Inc'),
(19138, 'D8:E9:52', 'KEOPSYS'),
(19139, 'D8:EB:97', 'TRENDnet, Inc.'),
(19140, 'D8:EE:78', 'Moog Protokraft'),
(19141, 'D8:F0:F2', 'Zeebo Inc'),
(19142, 'D8:F7:10', 'Libre Wireless Technologies Inc.'),
(19143, 'D8:FB:11', 'AXACORE'),
(19144, 'D8:FC:93', 'Intel Corporate'),
(19145, 'D8:FE:8F', 'IDFone Co., Ltd.'),
(19146, 'D8:FE:E3', 'D-Link International'),
(19147, 'DC:02:65', 'Meditech Kft'),
(19148, 'DC:02:8E', 'zte corporation'),
(19149, 'DC:05:2F', 'National Products Inc.'),
(19150, 'DC:05:75', 'SIEMENS ENERGY AUTOMATION'),
(19151, 'DC:05:ED', 'Nabtesco  Corporation'),
(19152, 'DC:07:C1', 'HangZhou QiYang Technology Co.,Ltd.'),
(19153, 'DC:09:14', 'Talk-A-Phone Co.'),
(19154, 'DC:0B:1A', 'ADB Broadband Italia'),
(19155, 'DC:0E:A1', 'COMPAL INFORMATION (KUNSHAN) CO., LTD'),
(19156, 'DC:16:A2', 'Medtronic Diabetes'),
(19157, 'DC:17:5A', 'Hitachi High-Technologies Corporation'),
(19158, 'DC:17:92', 'Captivate Network'),
(19159, 'DC:1D:9F', 'U &amp; B tech'),
(19160, 'DC:1D:D4', 'Microstep-MIS spol. s r.o.'),
(19161, 'DC:1E:A3', 'Accensus LLC'),
(19162, 'DC:20:08', 'ASD Electronics Ltd'),
(19163, 'DC:2A:14', 'Shanghai Longjing Technology Co.'),
(19164, 'DC:2B:61', 'Apple'),
(19165, 'DC:2B:66', 'InfoBLOCK S.A. de C.V.'),
(19166, 'DC:2B:CA', 'Zera GmbH'),
(19167, 'DC:2C:26', 'Iton Technology Limited'),
(19168, 'DC:2E:6A', 'HCT. Co., Ltd.'),
(19169, 'DC:2F:03', 'Step forward Group Co., Ltd.'),
(19170, 'DC:30:9C', 'Heyrex Limited'),
(19171, 'DC:33:50', 'TechSAT GmbH'),
(19172, 'DC:37:14', 'Apple, Inc.'),
(19173, 'DC:37:D2', 'Hunan HKT Electronic Technology Co., Ltd'),
(19174, 'DC:38:E1', 'Juniper networks'),
(19175, 'DC:39:79', 'Skyport Systems'),
(19176, 'DC:3A:5E', 'Roku, Inc'),
(19177, 'DC:3C:2E', 'Manufacturing System Insights, Inc.'),
(19178, 'DC:3C:84', 'Ticom Geomatics, Inc.'),
(19179, 'DC:3E:51', 'Solberg &amp; Andersen AS'),
(19180, 'DC:3E:F8', 'Nokia Corporation'),
(19181, 'DC:45:17', 'ARRIS Group, Inc.'),
(19182, 'DC:49:C9', 'CASCO SIGNAL LTD'),
(19183, 'DC:4E:DE', 'SHINYEI TECHNOLOGY CO., LTD.'),
(19184, 'DC:53:7C', 'Compal Broadband Networks, Inc.'),
(19185, 'DC:57:26', 'Power-One'),
(19186, 'DC:5E:36', 'Paterson Technology'),
(19187, 'DC:60:A1', 'Teledyne DALSA Professional Imaging'),
(19188, 'DC:64:7C', 'C.R.S. iiMotion GmbH'),
(19189, 'DC:66:3A', 'Apacer Technology Inc.'),
(19190, 'DC:6F:00', 'Livescribe, Inc.'),
(19191, 'DC:6F:08', 'Bay Storage Technology'),
(19192, 'DC:70:14', 'PRIVATE'),
(19193, 'DC:71:44', 'Samsung Electro Mechanics'),
(19194, 'DC:7B:94', 'CISCO SYSTEMS, INC.'),
(19195, 'DC:82:5B', 'JANUS, spol. s r.o.'),
(19196, 'DC:85:DE', 'Azurewave Technologies., inc.'),
(19197, 'DC:86:D8', 'Apple, Inc'),
(19198, 'DC:9B:1E', 'Intercom, Inc.'),
(19199, 'DC:9B:9C', 'Apple'),
(19200, 'DC:9C:52', 'Sapphire Technology Limited.'),
(19201, 'DC:9F:A4', 'Nokia Corporation'),
(19202, 'DC:9F:DB', 'Ubiquiti Networks, Inc.'),
(19203, 'DC:A5:F4', 'Cisco'),
(19204, 'DC:A6:BD', 'Beijing Lanbo Technology Co., Ltd.'),
(19205, 'DC:A7:D9', 'Compressor Controls Corp'),
(19206, 'DC:A8:CF', 'New Spin Golf, LLC.'),
(19207, 'DC:A9:71', 'Intel Corporate'),
(19208, 'DC:A9:89', 'MACANDC'),
(19209, 'DC:AD:9E', 'GreenPriz'),
(19210, 'DC:AE:04', 'CELOXICA Ltd'),
(19211, 'DC:B0:58', 'Burkert Werke GmbH'),
(19212, 'DC:B4:C4', 'Microsoft XCG'),
(19213, 'DC:BF:90', 'HUIZHOU QIAOXING TELECOMMUNICATION INDUSTRY CO.,LTD.'),
(19214, 'DC:C0:DB', 'Shenzhen Kaiboer Technology Co., Ltd.'),
(19215, 'DC:C1:01', 'SOLiD Technologies, Inc.'),
(19216, 'DC:C4:22', 'Systembase Limited'),
(19217, 'DC:C6:22', 'BUHEUNG SYSTEM'),
(19218, 'DC:C7:93', 'Nokia Corporation'),
(19219, 'DC:CB:A8', 'Explora Technologies Inc'),
(19220, 'DC:CE:41', 'FE GLOBAL HONG KONG LIMITED'),
(19221, 'DC:CE:BC', 'Shenzhen JSR Technology Co.,Ltd.'),
(19222, 'DC:CF:94', 'Beijing Rongcheng Hutong Technology Co., Ltd.'),
(19223, 'DC:D0:F7', 'Bentek Systems Ltd.'),
(19224, 'DC:D2:FC', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19225, 'DC:D3:21', 'HUMAX co.,tld'),
(19226, 'DC:D5:2A', 'Sunny Heart Limited'),
(19227, 'DC:D8:7F', 'Shenzhen JoinCyber Telecom Equipment Ltd'),
(19228, 'DC:DA:4F', 'GETCK TECHNOLOGY,  INC'),
(19229, 'DC:DE:CA', 'Akyllor'),
(19230, 'DC:E0:26', 'Patrol Tag, Inc'),
(19231, 'DC:E1:AD', 'Shenzhen Wintop Photoelectric Technology Co., Ltd'),
(19232, 'DC:E2:AC', 'Lumens Digital Optics Inc.'),
(19233, 'DC:E5:78', 'Experimental Factory of Scientific Engineering and Special Design Department'),
(19234, 'DC:E7:1C', 'AUG Elektronik GmbH'),
(19235, 'DC:EC:06', 'Heimi Network Technology Co., Ltd.'),
(19236, 'DC:F0:5D', 'Letta Teknoloji'),
(19237, 'DC:F1:10', 'Nokia Corporation'),
(19238, 'DC:F7:55', 'SITRONIK'),
(19239, 'DC:F8:58', 'Lorent Networks, Inc.'),
(19240, 'DC:FA:D5', 'STRONG Ges.m.b.H.'),
(19241, 'DC:FB:02', 'Buffalo Inc.'),
(19242, 'E0:05:C5', 'TP-LINK Technologies Co.,Ltd.'),
(19243, 'E0:06:E6', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19244, 'E0:0B:28', 'Inovonics'),
(19245, 'E0:0C:7F', 'Nintendo Co., Ltd.'),
(19246, 'E0:0D:B9', 'PRIVATE'),
(19247, 'E0:10:7F', 'Ruckus Wireless'),
(19248, 'E0:14:3E', 'Modoosis Inc.'),
(19249, 'E0:18:77', 'Fujitsu Limited'),
(19250, 'E0:19:1D', 'Huawei Technologies Co., Ltd'),
(19251, 'E0:1C:41', 'Aerohive Networks Inc.'),
(19252, 'E0:1C:EE', 'Bravo Tech, Inc.'),
(19253, 'E0:1D:38', 'Beijing HuaqinWorld Technology Co.,Ltd'),
(19254, 'E0:1D:3B', 'Cambridge Industries(Group) Co.,Ltd'),
(19255, 'E0:1E:07', 'Anite Telecoms  US. Inc'),
(19256, 'E0:1F:0A', 'Xslent Energy Technologies. LLC'),
(19257, 'E0:24:7F', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19258, 'E0:25:38', 'Titan Pet Products'),
(19259, 'E0:26:30', 'Intrigue Technologies, Inc.'),
(19260, 'E0:26:36', 'Nortel Networks'),
(19261, 'E0:27:1A', 'TTC Next-generation Home Network System WG'),
(19262, 'E0:2A:82', 'Universal Global Scientific Industrial Co., Ltd.'),
(19263, 'E0:2F:6D', 'Cisco'),
(19264, 'E0:30:05', 'Alcatel-Lucent Shanghai Bell Co., Ltd'),
(19265, 'E0:31:D0', 'SZ Telstar CO., LTD'),
(19266, 'E0:35:60', 'Challenger Supply Holdings, LLC'),
(19267, 'E0:36:E3', 'Stage One International Co., Ltd.'),
(19268, 'E0:39:D7', 'Plexxi, Inc.'),
(19269, 'E0:3C:5B', 'SHENZHEN JIAXINJIE ELECTRON CO.,LTD'),
(19270, 'E0:3E:44', 'Broadcom Corporation'),
(19271, 'E0:3E:4A', 'Cavanagh Group International'),
(19272, 'E0:3E:7D', 'data-complex GmbH'),
(19273, 'E0:3F:49', 'ASUSTek COMPUTER INC.'),
(19274, 'E0:46:9A', 'NETGEAR'),
(19275, 'E0:55:97', 'Emergent Vision Technologies Inc.'),
(19276, 'E0:56:F4', 'AxesNetwork Solutions inc.'),
(19277, 'E0:58:9E', 'Laerdal Medical'),
(19278, 'E0:5B:70', 'Innovid, Co., Ltd.'),
(19279, 'E0:5D:A6', 'Detlef Fink Elektronik &amp; Softwareentwicklung'),
(19280, 'E0:5F:B9', 'CISCO SYSTEMS, INC.'),
(19281, 'E0:61:B2', 'HANGZHOU ZENOINTEL TECHNOLOGY CO., LTD'),
(19282, 'E0:62:90', 'Jinan Jovision Science &amp; Technology Co., Ltd.'),
(19283, 'E0:63:E5', 'Sony Mobile Communications AB'),
(19284, 'E0:64:BB', 'DigiView S.r.l.'),
(19285, 'E0:66:78', 'Apple'),
(19286, 'E0:67:B3', 'C-Data Technology Co., Ltd'),
(19287, 'E0:69:95', 'PEGATRON CORPORATION'),
(19288, 'E0:75:0A', 'ALPS ERECTORIC CO.,LTD.'),
(19289, 'E0:75:7D', 'Motorola Mobility LLC'),
(19290, 'E0:7C:62', 'Whistle Labs, Inc.'),
(19291, 'E0:7F:53', 'TECHBOARD SRL'),
(19292, 'E0:7F:88', 'EVIDENCE Network SIA'),
(19293, 'E0:81:77', 'GreenBytes, Inc.'),
(19294, 'E0:87:B1', 'Nata-Info Ltd.'),
(19295, 'E0:88:5D', 'Technicolor CH USA Inc'),
(19296, 'E0:89:9D', 'Cisco'),
(19297, 'E0:8A:7E', 'Exponent'),
(19298, 'E0:8E:3C', 'Aztech Electronics Pte Ltd'),
(19299, 'E0:8F:EC', 'REPOTEC CO., LTD.'),
(19300, 'E0:91:53', 'XAVi Technologies Corp.'),
(19301, 'E0:91:F5', 'NETGEAR'),
(19302, 'E0:94:67', 'Intel Corporate'),
(19303, 'E0:95:79', 'ORTHOsoft inc, d/b/a Zimmer CAS'),
(19304, 'E0:97:96', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19305, 'E0:97:F2', 'Atomax Inc.'),
(19306, 'E0:99:71', 'Samsung Electronics Co.,Ltd'),
(19307, 'E0:9D:31', 'Intel Corporate'),
(19308, 'E0:9D:B8', 'PLANEX COMMUNICATIONS INC.'),
(19309, 'E0:A1:98', 'NOJA Power Switchgear Pty Ltd'),
(19310, 'E0:A1:D7', 'SFR'),
(19311, 'E0:A3:0F', 'Pevco'),
(19312, 'E0:A6:70', 'Nokia Corporation'),
(19313, 'E0:AA:B0', 'GENERAL VISION ELECTRONICS CO. LTD.'),
(19314, 'E0:AB:FE', 'Orb Networks, Inc.'),
(19315, 'E0:AC:F1', 'Cisco'),
(19316, 'E0:AE:5E', 'ALPS Co,. Ltd.'),
(19317, 'E0:AE:B2', 'Bender GmbH &amp;amp; Co.KG'),
(19318, 'E0:AE:ED', 'LOENK'),
(19319, 'E0:AF:4B', 'Pluribus Networks, Inc.'),
(19320, 'E0:B2:F1', 'FN-LINK TECHNOLOGY LIMITED'),
(19321, 'E0:B5:2D', 'Apple'),
(19322, 'E0:B7:B1', 'Pace plc'),
(19323, 'E0:B9:A5', 'Azurewave'),
(19324, 'E0:B9:BA', 'Apple'),
(19325, 'E0:BC:43', 'C2 Microsystems, Inc.'),
(19326, 'E0:C2:86', 'Aisai Communication Technology Co., Ltd.'),
(19327, 'E0:C2:B7', 'Masimo Corporation'),
(19328, 'E0:C3:F3', 'ZTE Corporation'),
(19329, 'E0:C6:B3', 'MilDef AB'),
(19330, 'E0:C7:9D', 'Texas Instruments'),
(19331, 'E0:C8:6A', 'SHENZHEN TW-SCIE Co., Ltd'),
(19332, 'E0:C9:22', 'Jireh Energy Tech., Ltd.'),
(19333, 'E0:C9:7A', 'Apple'),
(19334, 'E0:CA:4D', 'Shenzhen Unistar Communication Co.,LTD'),
(19335, 'E0:CA:94', 'Askey Computer'),
(19336, 'E0:CB:1D', 'PRIVATE'),
(19337, 'E0:CB:4E', 'ASUSTek COMPUTER INC.'),
(19338, 'E0:CB:EE', 'Samsung Electronics Co.,Ltd'),
(19339, 'E0:CE:C3', 'ASKEY COMPUTER CORP'),
(19340, 'E0:CF:2D', 'Gemintek Corporation'),
(19341, 'E0:D1:0A', 'Katoudenkikougyousyo co ltd'),
(19342, 'E0:D1:73', 'Cisco'),
(19343, 'E0:D1:E6', 'Aliph dba Jawbone'),
(19344, 'E0:D3:1A', 'EQUES Technology Co., Limited'),
(19345, 'E0:D7:BA', 'Texas Instruments'),
(19346, 'E0:D9:A2', 'Hippih aps'),
(19347, 'E0:DA:DC', 'JVC KENWOOD Corporation'),
(19348, 'E0:DB:55', 'Dell Inc'),
(19349, 'E0:DB:88', 'Open Standard Digital-IF Interface for SATCOM Systems'),
(19350, 'E0:DC:A0', 'Siemens Electrical Apparatus Ltd., Suzhou Chengdu Branch'),
(19351, 'E0:E6:31', 'SNB TECHNOLOGIES LIMITED'),
(19352, 'E0:E7:51', 'Nintendo Co., Ltd.'),
(19353, 'E0:E8:E8', 'Olive Telecommunication Pvt. Ltd'),
(19354, 'E0:ED:1A', 'vastriver Technology Co., Ltd'),
(19355, 'E0:ED:C7', 'Shenzhen Friendcom Technology Development Co., Ltd'),
(19356, 'E0:EE:1B', 'Panasonic Automotive Systems Company of America'),
(19357, 'E0:EF:25', 'Lintes Technology Co., Ltd.'),
(19358, 'E0:F2:11', 'Digitalwatt'),
(19359, 'E0:F3:79', 'Vaddio'),
(19360, 'E0:F5:C6', 'Apple'),
(19361, 'E0:F5:CA', 'CHENG UEI PRECISION INDUSTRY CO.,LTD.'),
(19362, 'E0:F8:47', 'Apple'),
(19363, 'E0:F9:BE', 'Cloudena Corp.'),
(19364, 'E0:FA:EC', 'Platan sp. z o.o. sp. k.'),
(19365, 'E0:FF:F7', 'Softiron Inc.'),
(19366, 'E4:04:39', 'TomTom Software Ltd'),
(19367, 'E4:11:5B', 'Hewlett Packard'),
(19368, 'E4:12:18', 'ShenZhen Rapoo Technology Co., Ltd.'),
(19369, 'E4:12:1D', 'Samsung Electronics Co.,Ltd'),
(19370, 'E4:12:89', 'topsystem Systemhaus GmbH'),
(19371, 'E4:1C:4B', 'V2 TECHNOLOGY, INC.'),
(19372, 'E4:1D:2D', 'Mellanox Technologies, Inc.'),
(19373, 'E4:1F:13', 'IBM Corp'),
(19374, 'E4:23:54', 'SHENZHEN FUZHI SOFTWARE TECHNOLOGY CO.,LTD'),
(19375, 'E4:25:E7', 'Apple'),
(19376, 'E4:25:E9', 'Color-Chip'),
(19377, 'E4:27:71', 'Smartlabs'),
(19378, 'E4:2A:D3', 'Magneti Marelli S.p.A. Powertrain'),
(19379, 'E4:2C:56', 'Lilee Systems, Ltd.'),
(19380, 'E4:2D:02', 'TCT Mobile Limited'),
(19381, 'E4:2F:26', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(19382, 'E4:2F:F6', 'Unicore communication Inc.'),
(19383, 'E4:32:CB', 'Samsung Electronics Co.,Ltd'),
(19384, 'E4:35:93', 'Hangzhou GoTo technology Co.Ltd'),
(19385, 'E4:35:FB', 'Sabre Technology (Hull) Ltd'),
(19386, 'E4:37:D7', 'HENRI DEPAEPE S.A.S.'),
(19387, 'E4:38:F2', 'Advantage Controls'),
(19388, 'E4:3F:A2', 'Wuxi DSP Technologies Inc.'),
(19389, 'E4:40:E2', 'Samsung Electronics Co.,Ltd'),
(19390, 'E4:41:E6', 'Ottec Technology GmbH'),
(19391, 'E4:46:BD', 'C&amp;C TECHNIC TAIWAN CO., LTD.'),
(19392, 'E4:48:C7', 'Cisco SPVTG'),
(19393, 'E4:4C:6C', 'Shenzhen Guo Wei Electronic Co,. Ltd.'),
(19394, 'E4:4E:18', 'Gardasoft VisionLimited'),
(19395, 'E4:4F:29', 'MA Lighting Technology GmbH'),
(19396, 'E4:4F:5F', 'EDS Elektronik Destek San.Tic.Ltd.Sti'),
(19397, 'E4:55:EA', 'Dedicated Computing'),
(19398, 'E4:56:14', 'Suttle Apparatus'),
(19399, 'E4:57:A8', 'Stuart Manufacturing, Inc.'),
(19400, 'E4:58:E7', 'Samsung Electronics Co.,Ltd'),
(19401, 'E4:5D:52', 'Avaya, Inc'),
(19402, 'E4:64:49', 'ARRIS Group, Inc.'),
(19403, 'E4:67:BA', 'Danish Interpretation Systems A/S'),
(19404, 'E4:68:A3', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19405, 'E4:69:5A', 'Dictum Health, Inc.'),
(19406, 'E4:6C:21', 'messMa GmbH'),
(19407, 'E4:71:85', 'Securifi Ltd'),
(19408, 'E4:75:1E', 'Getinge Sterilization AB'),
(19409, 'E4:77:23', 'zte corporation'),
(19410, 'E4:77:6B', 'AARTESYS AG'),
(19411, 'E4:77:D4', 'Minrray Industry Co.,Ltd'),
(19412, 'E4:7C:F9', 'Samsung Electronics Co., LTD'),
(19413, 'E4:7D:5A', 'Beijing Hanbang Technology Corp.'),
(19414, 'E4:7F:B2', 'Fujitsu Limited'),
(19415, 'E4:81:84', 'Alcatel-Lucent'),
(19416, 'E4:81:B3', 'Shenzhen ACT Industrial Co.,Ltd.'),
(19417, 'E4:83:99', 'ARRIS Group, Inc.'),
(19418, 'E4:85:01', 'Geberit International AG'),
(19419, 'E4:8A:D5', 'RF WINDOW CO., LTD.'),
(19420, 'E4:8B:7F', 'Apple'),
(19421, 'E4:8C:0F', 'Discovery Insure'),
(19422, 'E4:90:69', 'Rockwell Automation'),
(19423, 'E4:92:E7', 'Gridlink Tech. Co.,Ltd.'),
(19424, 'E4:92:FB', 'Samsung Electronics Co.,Ltd'),
(19425, 'E4:95:6E', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(19426, 'E4:96:AE', 'ALTOGRAPHICS Inc.'),
(19427, 'E4:97:F0', 'Shanghai VLC Technologies Ltd. Co.'),
(19428, 'E4:98:D6', 'Apple, Inc'),
(19429, 'E4:A5:EF', 'TRON LINK ELECTRONICS CO., LTD.'),
(19430, 'E4:A7:FD', 'Cellco Partnership'),
(19431, 'E4:AB:46', 'UAB Selteka'),
(19432, 'E4:AD:7D', 'SCL Elements'),
(19433, 'E4:AF:A1', 'HES-SO'),
(19434, 'E4:B0:21', 'Samsung Electronics Co.,Ltd'),
(19435, 'E4:BA:D9', '360 Fly Inc.'),
(19436, 'E4:C1:46', 'Objetivos y Servicios de Valor A'),
(19437, 'E4:C6:2B', 'Airware'),
(19438, 'E4:C6:3D', 'Apple, Inc.'),
(19439, 'E4:C6:E6', 'Mophie, LLC'),
(19440, 'E4:C7:22', 'Cisco'),
(19441, 'E4:C8:06', 'Ceiec Electric Technology Inc.'),
(19442, 'E4:CE:70', 'Health &amp; Life co., Ltd.'),
(19443, 'E4:CE:8F', 'Apple'),
(19444, 'E4:D3:32', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(19445, 'E4:D3:F1', 'Cisco'),
(19446, 'E4:D5:3D', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19447, 'E4:D7:1D', 'Oraya Therapeutics'),
(19448, 'E4:DD:79', 'En-Vision America, Inc.'),
(19449, 'E4:E0:C5', 'Samsung Electronics Co., LTD'),
(19450, 'E4:E4:09', 'LEIFHEIT AG'),
(19451, 'E4:EC:10', 'Nokia Corporation'),
(19452, 'E4:EE:FD', 'MR&amp;D Manufacturing'),
(19453, 'E4:F3:65', 'Time-O-Matic, Inc.'),
(19454, 'E4:F3:E3', 'Shanghai iComhome Co.,Ltd.'),
(19455, 'E4:F4:C6', 'NETGEAR'),
(19456, 'E4:F7:A1', 'Datafox GmbH'),
(19457, 'E4:F8:EF', 'Samsung Elec Co.,Ltd'),
(19458, 'E4:F9:39', 'Minxon Hotel Technology INC.'),
(19459, 'E4:FA:1D', 'PAD Peripheral Advanced Design Inc.'),
(19460, 'E4:FE:D9', 'EDMI Europe Ltd'),
(19461, 'E4:FF:DD', 'ELECTRON INDIA'),
(19462, 'E8:03:9A', 'Samsung Electronics CO., LTD'),
(19463, 'E8:04:0B', 'Apple'),
(19464, 'E8:04:10', 'PRIVATE'),
(19465, 'E8:04:62', 'CISCO SYSTEMS, INC.'),
(19466, 'E8:04:F3', 'Throughtek Co., Ltd.'),
(19467, 'E8:05:6D', 'Nortel Networks'),
(19468, 'E8:06:88', 'Apple'),
(19469, 'E8:07:BF', 'SHENZHEN BOOMTECH INDUSTRY CO.,LTD'),
(19470, 'E8:08:8B', 'Huawei Technologies Co., Ltd'),
(19471, 'E8:0B:13', 'Akib Systems Taiwan, INC'),
(19472, 'E8:0C:38', 'DAEYOUNG INFORMATION SYSTEM CO., LTD'),
(19473, 'E8:0C:75', 'Syncbak, Inc.'),
(19474, 'E8:10:2E', 'Really Simple Software, Inc'),
(19475, 'E8:11:32', 'Samsung Electronics CO., LTD'),
(19476, 'E8:13:24', 'GuangZhou Bonsoninfo System CO.,LTD'),
(19477, 'E8:15:0E', 'Nokia Corporation'),
(19478, 'E8:16:2B', 'IDEO Security Co., Ltd.'),
(19479, 'E8:17:FC', 'NIFTY Corporation'),
(19480, 'E8:18:63', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(19481, 'E8:28:77', 'TMY Co., Ltd.'),
(19482, 'E8:28:D5', 'Cots Technology'),
(19483, 'E8:2A:EA', 'Intel Corporate'),
(19484, 'E8:2E:24', 'Out of the Fog Research LLC'),
(19485, 'E8:33:81', 'ARRIS Group, Inc.'),
(19486, 'E8:39:35', 'Hewlett Packard'),
(19487, 'E8:39:DF', 'Askey Computer'),
(19488, 'E8:3A:97', 'OCZ Technology Group'),
(19489, 'E8:3E:B6', 'RIM'),
(19490, 'E8:3E:FB', 'GEODESIC LTD.'),
(19491, 'E8:3E:FC', 'ARRIS Group, Inc.'),
(19492, 'E8:40:40', 'CISCO SYSTEMS, INC.'),
(19493, 'E8:40:F2', 'PEGATRON CORPORATION'),
(19494, 'E8:43:B6', 'QNAP Systems, Inc.'),
(19495, 'E8:44:7E', 'Bitdefender SRL'),
(19496, 'E8:48:1F', 'Advanced Automotive Antennas'),
(19497, 'E8:4E:06', 'EDUP INTERNATIONAL (HK) CO., LTD'),
(19498, 'E8:4E:84', 'Samsung Electronics Co.,Ltd'),
(19499, 'E8:4E:CE', 'Nintendo Co., Ltd.'),
(19500, 'E8:51:6E', 'TSMART Inc.'),
(19501, 'E8:51:9D', 'Yeonhab Precision Co.,LTD'),
(19502, 'E8:54:84', 'NEO INFORMATION SYSTEMS CO., LTD.'),
(19503, 'E8:56:D6', 'NCTech Ltd'),
(19504, 'E8:5A:A7', 'LLC Emzior'),
(19505, 'E8:5B:5B', 'LG ELECTRONICS INC'),
(19506, 'E8:5B:F0', 'Imaging Diagnostics'),
(19507, 'E8:5D:6B', 'Luminate Wireless'),
(19508, 'E8:5E:53', 'Infratec Datentechnik GmbH'),
(19509, 'E8:61:1F', 'Dawning Information Industry Co.,Ltd'),
(19510, 'E8:61:7E', 'Liteon Technology Corporation'),
(19511, 'E8:61:83', 'Black Diamond Advanced Technology, LLC'),
(19512, 'E8:6C:DA', 'Supercomputers and Neurocomputers Research Center'),
(19513, 'E8:6D:52', 'ARRIS Group, Inc.'),
(19514, 'E8:6D:54', 'Digit Mobile Inc'),
(19515, 'E8:6D:6E', 'Control &amp; Display Systems Ltd t/a CDSRail'),
(19516, 'E8:71:8D', 'Elsys Equipamentos Eletronicos Ltda'),
(19517, 'E8:74:E6', 'ADB BROADBAND ITALIA'),
(19518, 'E8:75:7F', 'FIRS Technologies(Shenzhen) Co., Ltd'),
(19519, 'E8:78:A1', 'BEOVIEW INTERCOM DOO'),
(19520, 'E8:7A:F3', 'S5 Tech S.r.l.'),
(19521, 'E8:80:2E', 'Apple'),
(19522, 'E8:80:D8', 'GNTEK Electronics Co.,Ltd.'),
(19523, 'E8:87:A3', 'Loxley Public Company Limited'),
(19524, 'E8:89:2C', 'ARRIS Group, Inc.'),
(19525, 'E8:8D:28', 'Apple'),
(19526, 'E8:8D:F5', 'ZNYX Networks, Inc.'),
(19527, 'E8:8E:60', 'NSD Corporation'),
(19528, 'E8:92:18', 'Arcontia International AB'),
(19529, 'E8:92:A4', 'LG Electronics'),
(19530, 'E8:94:4C', 'Cogent Healthcare Systems Ltd'),
(19531, 'E8:94:F6', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(19532, 'E8:96:06', 'testo Instruments (Shenzhen) Co., Ltd.'),
(19533, 'E8:99:5A', 'PiiGAB, Processinformation i Goteborg AB'),
(19534, 'E8:99:C4', 'HTC Corporation'),
(19535, 'E8:9A:8F', 'Quanta Computer Inc.'),
(19536, 'E8:9A:FF', 'Fujian Landi Commercial Equipment Co.,Ltd'),
(19537, 'E8:9D:87', 'Toshiba'),
(19538, 'E8:A3:64', 'Signal Path International / Peachtree Audio'),
(19539, 'E8:A4:C1', 'Deep Sea Electronics PLC'),
(19540, 'E8:AB:FA', 'Shenzhen Reecam Tech.Ltd.'),
(19541, 'E8:B1:FC', 'Intel Corporate'),
(19542, 'E8:B4:AE', 'Shenzhen C&amp;D Electronics Co.,Ltd'),
(19543, 'E8:B7:48', 'CISCO SYSTEMS, INC.'),
(19544, 'E8:BA:70', 'CISCO SYSTEMS, INC.'),
(19545, 'E8:BB:3D', 'Sino Prime-Tech Limited'),
(19546, 'E8:BB:A8', 'GUANGDONG OPPO MOBILE TELECOMMUNICATIONS CORP.,LTD.'),
(19547, 'E8:BE:81', 'SAGEMCOM'),
(19548, 'E8:C2:29', 'H-Displays (MSC) Bhd'),
(19549, 'E8:C3:20', 'Austco Communication Systems Pty Ltd'),
(19550, 'E8:C7:4F', 'Liteon Technology Corporation'),
(19551, 'E8:CB:A1', 'Nokia Corporation'),
(19552, 'E8:CC:18', 'D-Link International'),
(19553, 'E8:CC:32', 'Micronet  LTD'),
(19554, 'E8:CD:2D', 'Huawei Technologies Co., Ltd'),
(19555, 'E8:CE:06', 'SkyHawke Technologies, LLC.'),
(19556, 'E8:D0:FA', 'MKS Instruments Deutschland GmbH'),
(19557, 'E8:D4:83', 'ULTIMATE Europe Transportation Equipment GmbH'),
(19558, 'E8:D4:E0', 'Beijing BenyWave Technology Co., Ltd.'),
(19559, 'E8:DA:96', 'Zhuhai Tianrui Electrical Power Tech. Co., Ltd.'),
(19560, 'E8:DA:AA', 'VideoHome Technology Corp.'),
(19561, 'E8:DE:27', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(19562, 'E8:DF:F2', 'PRF Co., Ltd.'),
(19563, 'E8:E0:8F', 'GRAVOTECH MARKING SAS'),
(19564, 'E8:E0:B7', 'Toshiba'),
(19565, 'E8:E1:E2', 'Energotest'),
(19566, 'E8:E5:D6', 'Samsung Electronics Co.,Ltd'),
(19567, 'E8:E7:32', 'Alcatel-Lucent'),
(19568, 'E8:E7:70', 'Warp9 Tech Design, Inc.'),
(19569, 'E8:E7:76', 'Shenzhen Kootion Technology Co., Ltd'),
(19570, 'E8:E8:75', 'iS5 Communications Inc.'),
(19571, 'E8:EA:6A', 'StarTech.com'),
(19572, 'E8:EA:DA', 'Denkovi Assembly Electroncs LTD'),
(19573, 'E8:ED:05', 'ARRIS Group, Inc.'),
(19574, 'E8:ED:F3', 'Cisco'),
(19575, 'E8:EF:89', 'OPMEX Tech.'),
(19576, 'E8:F1:B0', 'SAGEMCOM SAS'),
(19577, 'E8:F2:26', 'MILLSON CUSTOM SOLUTIONS INC.'),
(19578, 'E8:F9:28', 'RFTECH SRL'),
(19579, 'E8:FC:60', 'ELCOM Innovations Private Limited'),
(19580, 'E8:FC:AF', 'NETGEAR INC.,'),
(19581, 'EC:0E:C4', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19582, 'EC:0E:D6', 'ITECH INSTRUMENTS SAS'),
(19583, 'EC:11:20', 'FloDesign Wind Turbine Corporation'),
(19584, 'EC:13:B2', 'Netonix'),
(19585, 'EC:14:F6', 'BioControl AS'),
(19586, 'EC:17:2F', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(19587, 'EC:17:66', 'Research Centre Module'),
(19588, 'EC:1A:59', 'Belkin International Inc.'),
(19589, 'EC:1D:7F', 'zte corporation'),
(19590, 'EC:21:9F', 'VidaBox LLC'),
(19591, 'EC:22:57', 'JiangSu NanJing University Electronic Information Technology Co.,Ltd'),
(19592, 'EC:22:80', ' D-Link International'),
(19593, 'EC:23:3D', 'Huawei Technologies Co., Ltd'),
(19594, 'EC:23:68', 'IntelliVoice Co.,Ltd.'),
(19595, 'EC:24:B8', 'Texas Instruments'),
(19596, 'EC:26:CA', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(19597, 'EC:2A:F0', 'Ypsomed AG'),
(19598, 'EC:2C:49', 'University of Tokyo'),
(19599, 'EC:2E:4E', 'HITACHI-LG DATA STORAGE INC'),
(19600, 'EC:30:91', 'CISCO SYSTEMS, INC.'),
(19601, 'EC:35:86', 'Apple'),
(19602, 'EC:3B:F0', 'NovelSat'),
(19603, 'EC:3C:5A', 'SHEN ZHEN HENG SHENG HUI DIGITAL TECHNOLOGY CO.,LTD'),
(19604, 'EC:3C:88', 'MCNEX Co.,Ltd.'),
(19605, 'EC:3E:09', 'PERFORMANCE DESIGNED PRODUCTS, LLC'),
(19606, 'EC:3F:05', 'Institute 706, The Second Academy China Aerospace Science &amp; Industry Corp'),
(19607, 'EC:42:F0', 'ADL Embedded Solutions, Inc.'),
(19608, 'EC:43:E6', 'AWCER Ltd.'),
(19609, 'EC:43:F6', 'ZyXEL Communications Corporation'),
(19610, 'EC:44:76', 'CISCO SYSTEMS, INC.'),
(19611, 'EC:46:44', 'TTK SAS'),
(19612, 'EC:46:70', 'Meinberg Funkuhren GmbH &amp; Co. KG'),
(19613, 'EC:47:3C', 'Redwire, LLC'),
(19614, 'EC:49:93', 'Qihan Technology Co., Ltd'),
(19615, 'EC:4C:4D', 'ZAO NPK RoTeK'),
(19616, 'EC:54:2E', 'Shanghai XiMei Electronic Technology Co. Ltd'),
(19617, 'EC:55:F9', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19618, 'EC:59:E7', 'Microsoft Corporation'),
(19619, 'EC:5A:86', 'Yulong Computer Telecommunication Scientific (Shenzhen) Co.,Ltd'),
(19620, 'EC:5C:69', 'MITSUBISHI HEAVY INDUSTRIES MECHATRONICS SYSTEMS,LTD.'),
(19621, 'EC:62:64', 'Global411 Internet Services, LLC'),
(19622, 'EC:63:E5', 'ePBoard Design LLC'),
(19623, 'EC:66:D1', 'B&amp;W Group LTD'),
(19624, 'EC:6C:9F', 'Chengdu Volans Technology CO.,LTD'),
(19625, 'EC:71:DB', 'Shenzhen Baichuan Digital Technology Co., Ltd.'),
(19626, 'EC:74:BA', 'Hirschmann Automation and Control GmbH'),
(19627, 'EC:7C:74', 'Justone Technologies Co., Ltd.'),
(19628, 'EC:7D:9D', 'MEI'),
(19629, 'EC:80:09', 'NovaSparks'),
(19630, 'EC:83:6C', 'RM Tech Co., Ltd.'),
(19631, 'EC:85:2F', 'Apple'),
(19632, 'EC:88:8F', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(19633, 'EC:88:92', 'Motorola Mobility LLC'),
(19634, 'EC:89:F5', 'Lenovo Mobile Communication Technology Ltd.'),
(19635, 'EC:8A:4C', 'zte corporation'),
(19636, 'EC:8E:AD', 'DLX'),
(19637, 'EC:92:33', 'Eddyfi NDT Inc'),
(19638, 'EC:93:27', 'MEMMERT GmbH + Co. KG'),
(19639, 'EC:96:81', '2276427 Ontario Inc'),
(19640, 'EC:98:6C', 'Lufft Mess- und Regeltechnik GmbH'),
(19641, 'EC:98:C1', 'Beijing Risbo Network Technology Co.,Ltd'),
(19642, 'EC:9A:74', 'Hewlett Packard'),
(19643, 'EC:9B:5B', 'Nokia Corporation'),
(19644, 'EC:9E:CD', 'Artesyn Embedded Technologies'),
(19645, 'EC:A2:9B', 'Kemppi Oy'),
(19646, 'EC:A8:6B', 'ELITEGROUP COMPUTER SYSTEMS CO., LTD.'),
(19647, 'EC:B1:06', 'Acuro Networks, Inc'),
(19648, 'EC:B1:D7', 'Hewlett Packard'),
(19649, 'EC:B5:41', 'SHINANO E and E Co.Ltd.'),
(19650, 'EC:B9:07', 'CloudGenix Inc'),
(19651, 'EC:BA:FE', 'GIROPTIC'),
(19652, 'EC:BB:AE', 'Digivoice Tecnologia em Eletronica Ltda'),
(19653, 'EC:BD:09', 'FUSION Electronics Ltd'),
(19654, 'EC:C3:8A', 'Accuenergy (CANADA) Inc'),
(19655, 'EC:C8:82', 'CISCO SYSTEMS, INC.'),
(19656, 'EC:CB:30', 'Huawei Technologies Co., Ltd'),
(19657, 'EC:CD:6D', 'Allied Telesis, Inc.'),
(19658, 'EC:D0:0E', 'MiraeRecognition Co., Ltd.'),
(19659, 'EC:D0:40', 'GEA Farm Technologies GmbH'),
(19660, 'EC:D1:9A', 'Zhuhai Liming Industries Co., Ltd'),
(19661, 'EC:D9:25', 'RAMI'),
(19662, 'EC:D9:50', 'IRT SA'),
(19663, 'EC:D9:D1', 'Shenzhen TG-NET Botone Technology Co.,Ltd.'),
(19664, 'EC:DE:3D', 'Lamprey Networks, Inc.'),
(19665, 'EC:E0:9B', 'Samsung electronics CO., LTD'),
(19666, 'EC:E1:A9', 'Cisco'),
(19667, 'EC:E2:FD', 'SKG Electric Group(Thailand) Co., Ltd.'),
(19668, 'EC:E5:12', 'tado GmbH'),
(19669, 'EC:E5:55', 'Hirschmann Automation'),
(19670, 'EC:E7:44', 'Omntec mfg. inc'),
(19671, 'EC:E9:0B', 'SISTEMA SOLUCOES ELETRONICAS LTDA - EASYTECH'),
(19672, 'EC:E9:15', 'STI Ltd'),
(19673, 'EC:E9:F8', 'Guang Zhou TRI-SUN Electronics Technology  Co., Ltd'),
(19674, 'EC:EA:03', 'DARFON LIGHTING CORP'),
(19675, 'EC:F0:0E', 'Abocom'),
(19676, 'EC:F2:36', 'NEOMONTANA ELECTRONICS'),
(19677, 'EC:F3:5B', 'Nokia Corporation'),
(19678, 'EC:F4:BB', 'Dell Inc'),
(19679, 'EC:F7:2B', 'HD DIGITAL TECH CO., LTD.'),
(19680, 'EC:FA:AA', 'The IMS Company'),
(19681, 'EC:FC:55', 'A. Eberle GmbH &amp; Co. KG'),
(19682, 'EC:FE:7E', 'BlueRadios, Inc.'),
(19683, 'F0:00:7F', 'Janz - Contadores de Energia, SA'),
(19684, 'F0:02:2B', 'Chrontel'),
(19685, 'F0:02:48', 'SmarteBuilding'),
(19686, 'F0:07:86', 'Shandong Bittel Electronics Co., Ltd'),
(19687, 'F0:08:F1', 'Samsung Electronics Co.,Ltd'),
(19688, 'F0:13:C3', 'SHENZHEN FENDA TECHNOLOGY CO., LTD'),
(19689, 'F0:15:A0', 'KyungDong One Co., Ltd.'),
(19690, 'F0:1C:13', 'LG Electronics'),
(19691, 'F0:1C:2D', 'Juniper Networks'),
(19692, 'F0:1E:34', 'ORICO Technologies Co., Ltd'),
(19693, 'F0:1F:AF', 'Dell Inc'),
(19694, 'F0:21:9D', 'Cal-Comp Electronics &amp; Communications Company Ltd.'),
(19695, 'F0:23:29', 'SHOWA DENKI CO.,LTD.'),
(19696, 'F0:24:05', 'OPUS High Technology Corporation'),
(19697, 'F0:24:08', 'Talaris (Sweden) AB'),
(19698, 'F0:24:75', 'Apple'),
(19699, 'F0:25:72', 'CISCO SYSTEMS, INC.'),
(19700, 'F0:25:B7', 'Samsung Electro Mechanics co., LTD.'),
(19701, 'F0:26:4C', 'Dr. Sigrist AG'),
(19702, 'F0:27:65', 'Murata Manufactuaring Co.,Ltd.'),
(19703, 'F0:29:29', 'Cisco'),
(19704, 'F0:2A:23', 'Creative Next Design'),
(19705, 'F0:2A:61', 'Waldo Networks, Inc.'),
(19706, 'F0:2F:D8', 'Bi2-Vision'),
(19707, 'F0:32:1A', 'Mita-Teknik A/S'),
(19708, 'F0:37:A1', 'Huike Electronics (SHENZHEN) CO., LTD.'),
(19709, 'F0:3A:4B', 'Bloombase, Inc.'),
(19710, 'F0:3A:55', 'Omega Elektronik AS'),
(19711, 'F0:3D:29', 'Actility'),
(19712, 'F0:3F:F8', 'R L Drake'),
(19713, 'F0:43:35', 'DVN(Shanghai)Ltd.'),
(19714, 'F0:4A:2B', 'PYRAMID Computer GmbH'),
(19715, 'F0:4B:6A', 'Scientific Production Association Siberian Arsenal, Ltd.'),
(19716, 'F0:4B:F2', 'JTECH Communications, Inc.'),
(19717, 'F0:4D:A2', 'Dell Inc.'),
(19718, 'F0:4F:7C', 'PRIVATE'),
(19719, 'F0:58:49', 'CareView Communications'),
(19720, 'F0:5A:09', 'Samsung Electronics Co.,Ltd'),
(19721, 'F0:5D:89', 'Dycon Limited'),
(19722, 'F0:5D:C8', 'Duracell Powermat'),
(19723, 'F0:5F:5A', 'Getriebebau NORD GmbH and Co. KG'),
(19724, 'F0:61:30', 'Advantage Pharmacy Services, LLC'),
(19725, 'F0:62:0D', 'Shenzhen Egreat Tech Corp.,Ltd'),
(19726, 'F0:62:81', 'ProCurve Networking by HP'),
(19727, 'F0:65:DD', 'Primax Electronics Ltd.'),
(19728, 'F0:68:53', 'Integrated Corporation'),
(19729, 'F0:6B:CA', 'Samsung Electronics Co.,Ltd'),
(19730, 'F0:72:8C', 'Samsung Electronics Co.,Ltd'),
(19731, 'F0:73:AE', 'PEAK-System Technik'),
(19732, 'F0:76:1C', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(19733, 'F0:77:65', 'Sourcefire, Inc'),
(19734, 'F0:77:D0', 'Xcellen'),
(19735, 'F0:79:59', 'ASUSTek COMPUTER INC.'),
(19736, 'F0:7B:CB', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19737, 'F0:7D:68', 'D-Link Corporation'),
(19738, 'F0:7F:06', 'Cisco'),
(19739, 'F0:7F:0C', 'Leopold Kostal GmbH &amp;Co. KG'),
(19740, 'F0:81:AF', 'IRZ AUTOMATION TECHNOLOGIES LTD'),
(19741, 'F0:82:61', 'SAGEMCOM'),
(19742, 'F0:84:2F', 'ADB Broadband Italia'),
(19743, 'F0:84:C9', 'zte corporation'),
(19744, 'F0:8A:28', 'JIANGSU HENGSION ELECTRONIC S and T CO.,LTD'),
(19745, 'F0:8B:FE', 'COSTEL.,CO.LTD'),
(19746, 'F0:8C:FB', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(19747, 'F0:8E:DB', 'VeloCloud Networks'),
(19748, 'F0:92:1C', 'Hewlett Packard'),
(19749, 'F0:93:3A', 'NxtConect'),
(19750, 'F0:93:C5', 'Garland Technology'),
(19751, 'F0:99:BF', 'Apple'),
(19752, 'F0:9C:BB', 'RaonThink Inc.'),
(19753, 'F0:9C:E9', 'Aerohive Networks Inc'),
(19754, 'F0:9E:63', 'Cisco'),
(19755, 'F0:9F:C2', 'Ubiquiti Networks, Inc.'),
(19756, 'F0:A2:25', 'PRIVATE'),
(19757, 'F0:A7:64', 'GST Co., Ltd.'),
(19758, 'F0:AC:A4', 'HBC-radiomatic'),
(19759, 'F0:AD:4E', 'Globalscale Technologies, Inc.'),
(19760, 'F0:AE:51', 'Xi3 Corp'),
(19761, 'F0:B0:52', 'Ruckus Wireless'),
(19762, 'F0:B4:79', 'Apple'),
(19763, 'F0:B6:EB', 'Poslab Technology Co., Ltd.'),
(19764, 'F0:BC:C8', 'MaxID (Pty) Ltd'),
(19765, 'F0:BD:F1', 'Sipod Inc.'),
(19766, 'F0:BF:97', 'Sony Corporation'),
(19767, 'F0:C1:F1', 'Apple, Inc.'),
(19768, 'F0:C2:4C', 'Zhejiang FeiYue Digital Technology Co., Ltd'),
(19769, 'F0:C2:7C', 'Mianyang Netop Telecom Equipment Co.,Ltd.'),
(19770, 'F0:C8:8C', 'LeddarTech Inc.'),
(19771, 'F0:CB:A1', 'Apple'),
(19772, 'F0:D1:4F', 'LINEAR LLC'),
(19773, 'F0:D1:A9', 'Apple'),
(19774, 'F0:D3:A7', 'CobaltRay Co., Ltd'),
(19775, 'F0:D3:E7', 'Sensometrix SA'),
(19776, 'F0:D7:67', 'Axema Passagekontroll AB'),
(19777, 'F0:DA:7C', 'RLH INDUSTRIES,INC.'),
(19778, 'F0:DB:30', 'Yottabyte'),
(19779, 'F0:DB:E2', 'Apple'),
(19780, 'F0:DB:F8', 'Apple'),
(19781, 'F0:DC:E2', 'Apple'),
(19782, 'F0:DE:71', 'Shanghai EDO Technologies Co.,Ltd.'),
(19783, 'F0:DE:B9', 'ShangHai Y&amp;Y Electronics Co., Ltd'),
(19784, 'F0:DE:F1', 'Wistron InfoComm (Kunshan)Co'),
(19785, 'F0:E5:C3', 'Dr&auml;gerwerk AG &amp; Co. KG aA'),
(19786, 'F0:E7:7E', 'Samsung Electronics Co.,Ltd'),
(19787, 'F0:EB:D0', 'Shanghai Feixun Communication Co.,Ltd.'),
(19788, 'F0:EC:39', 'Essec'),
(19789, 'F0:ED:1E', 'Bilkon Bilgisayar Kontrollu Cih. Im.Ltd.'),
(19790, 'F0:EE:BB', 'VIPAR GmbH'),
(19791, 'F0:F0:02', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19792, 'F0:F2:60', 'Mobitec AB'),
(19793, 'F0:F3:36', 'TP-LINK TECHNOLOGIES CO.,LTD'),
(19794, 'F0:F5:AE', 'Adaptrum Inc.'),
(19795, 'F0:F6:1C', 'Apple'),
(19796, 'F0:F6:44', 'Whitesky Science &amp; Technology Co.,Ltd.'),
(19797, 'F0:F6:69', 'Motion Analysis Corporation'),
(19798, 'F0:F7:55', 'CISCO SYSTEMS, INC.'),
(19799, 'F0:F7:B3', 'Phorm'),
(19800, 'F0:F8:42', 'KEEBOX, Inc.'),
(19801, 'F0:F9:F7', 'IES GmbH &amp; Co. KG'),
(19802, 'F0:FD:A0', 'Acurix Networks LP'),
(19803, 'F0:FE:6B', 'Shanghai High-Flying Electronics Technology Co., Ltd'),
(19804, 'F4:03:21', 'BeNeXt B.V.'),
(19805, 'F4:03:2F', 'Reduxio Systems'),
(19806, 'F4:04:4C', 'ValenceTech Limited'),
(19807, 'F4:06:69', 'Intel Corporate'),
(19808, 'F4:06:8D', 'devolo AG'),
(19809, 'F4:06:A5', 'Hangzhou Bianfeng Networking Technology Co., Ltd.'),
(19810, 'F4:09:D8', 'Samsung Electro Mechanics co., LTD.'),
(19811, 'F4:0B:93', 'Research In Motion'),
(19812, 'F4:0E:11', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(19813, 'F4:0F:1B', 'Cisco'),
(19814, 'F4:0F:9B', 'WAVELINK'),
(19815, 'F4:15:FD', 'Shanghai Pateo Electronic Equipment Manufacturing Co., Ltd.'),
(19816, 'F4:1B:A1', 'Apple'),
(19817, 'F4:1E:26', 'Simon-Kaloi Engineering'),
(19818, 'F4:1F:0B', 'YAMABISHI Corporation'),
(19819, 'F4:1F:C2', 'Cisco'),
(19820, 'F4:20:12', 'Cuciniale GmbH'),
(19821, 'F4:28:33', 'MMPC Inc.'),
(19822, 'F4:28:53', 'Zioncom Electronics (Shenzhen) Ltd.'),
(19823, 'F4:28:96', 'SPECTO PAINEIS ELETRONICOS LTDA'),
(19824, 'F4:29:81', 'vivo Mobile Communication Co., Ltd.'),
(19825, 'F4:2C:56', 'SENOR TECH CO LTD'),
(19826, 'F4:36:E1', 'Abilis Systems SARL'),
(19827, 'F4:37:B7', 'Apple'),
(19828, 'F4:38:14', 'Shanghai Howell Electronic Co.,Ltd'),
(19829, 'F4:3D:80', 'FAG Industrial Services GmbH'),
(19830, 'F4:3E:61', 'Shenzhen Gongjin Electronics Co., Ltd'),
(19831, 'F4:3E:9D', 'Benu Networks, Inc.'),
(19832, 'F4:42:27', 'S &amp; S Research Inc.'),
(19833, 'F4:44:50', 'BND Co., Ltd.'),
(19834, 'F4:45:ED', 'Portable Innovation Technology Ltd.'),
(19835, 'F4:47:2A', 'Nanjing Rousing Sci. and Tech. Industrial Co., Ltd'),
(19836, 'F4:48:48', 'Amscreen Group Ltd'),
(19837, 'F4:4E:05', 'Cisco'),
(19838, 'F4:4E:FD', 'Actions Semiconductor Co.,Ltd.(Cayman Islands)'),
(19839, 'F4:50:EB', 'Telechips Inc'),
(19840, 'F4:52:14', 'Mellanox Technologies, Inc.'),
(19841, 'F4:54:33', 'Rockwell Automation'),
(19842, 'F4:55:95', 'HENGBAO Corporation LTD.'),
(19843, 'F4:55:9C', 'Huawei Technologies Co., Ltd'),
(19844, 'F4:55:E0', 'Niceway CNC Technology Co.,Ltd.Hunan Province'),
(19845, 'F4:58:42', 'Boxx TV Ltd'),
(19846, 'F4:5F:69', 'Matsufu Electronics distribution Company'),
(19847, 'F4:5F:D4', 'Cisco SPVTG'),
(19848, 'F4:5F:F7', 'DQ Technology Inc.'),
(19849, 'F4:60:0D', 'Panoptic Technology, Inc'),
(19850, 'F4:63:49', 'Diffon Corporation'),
(19851, 'F4:64:5D', 'Toshiba'),
(19852, 'F4:6A:BC', 'Adonit Corp. Ltd.'),
(19853, 'F4:6D:04', 'ASUSTek COMPUTER INC.'),
(19854, 'F4:6D:E2', 'zte corporation'),
(19855, 'F4:73:CA', 'Conversion Sound Inc.'),
(19856, 'F4:76:26', 'Viltechmeda UAB'),
(19857, 'F4:7A:4E', 'Woojeon&amp;Handan'),
(19858, 'F4:7A:CC', 'SolidFire, Inc.'),
(19859, 'F4:7B:5E', 'Samsung Eletronics Co., Ltd'),
(19860, 'F4:7F:35', 'CISCO SYSTEMS, INC.'),
(19861, 'F4:81:39', 'CANON INC.'),
(19862, 'F4:87:71', 'Infoblox'),
(19863, 'F4:8E:09', 'Nokia Corporation'),
(19864, 'F4:90:CA', 'Tensorcom'),
(19865, 'F4:90:EA', 'Deciso B.V.'),
(19866, 'F4:94:61', 'NexGen Storage'),
(19867, 'F4:94:66', 'CountMax,  ltd'),
(19868, 'F4:99:AC', 'WEBER Schraubautomaten GmbH'),
(19869, 'F4:9F:54', 'Samsung Electronics'),
(19870, 'F4:9F:F3', 'Huawei Technologies Co., Ltd'),
(19871, 'F4:A2:94', 'EAGLE WORLD DEVELOPMENT CO., LIMITED'),
(19872, 'F4:A5:2A', 'Hawa Technologies Inc'),
(19873, 'F4:AC:C1', 'CISCO SYSTEMS, INC.'),
(19874, 'F4:B1:64', 'Lightning Telecommunications Technology Co. Ltd'),
(19875, 'F4:B3:81', 'WindowMaster A/S'),
(19876, 'F4:B5:2F', 'Juniper networks'),
(19877, 'F4:B5:49', 'Yeastar Technology Co., Ltd.'),
(19878, 'F4:B6:E5', 'TerraSem Co.,Ltd'),
(19879, 'F4:B7:2A', 'TIME INTERCONNECT LTD'),
(19880, 'F4:B7:E2', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19881, 'F4:B8:5E', 'Texas INstruments'),
(19882, 'F4:BD:7C', 'Chengdu jinshi communication Co., LTD'),
(19883, 'F4:C4:47', 'Coagent International Enterprise Limited'),
(19884, 'F4:C6:D7', 'blackned GmbH'),
(19885, 'F4:C7:14', 'Shenzhen Huawei Communication Technologies Co., Ltd'),
(19886, 'F4:C7:95', 'WEY Elektronik AG'),
(19887, 'F4:CA:E5', 'FREEBOX SA'),
(19888, 'F4:CD:90', 'Vispiron Rotec GmbH'),
(19889, 'F4:CE:46', 'Hewlett-Packard Company'),
(19890, 'F4:CF:E2', 'Cisco'),
(19891, 'F4:D0:32', 'Yunnan Ideal Information&amp;Technology.,Ltd'),
(19892, 'F4:D2:61', 'SEMOCON Co., Ltd'),
(19893, 'F4:D9:FB', 'Samsung Electronics CO., LTD'),
(19894, 'F4:DC:4D', 'Beijing CCD Digital Technology Co., Ltd'),
(19895, 'F4:DC:DA', 'Zhuhai Jiahe Communication Technology Co., limited'),
(19896, 'F4:DC:F9', 'Huawei Technologies Co., Ltd'),
(19897, 'F4:DD:9E', 'GoPro'),
(19898, 'F4:E1:42', 'Delta Elektronika BV'),
(19899, 'F4:E6:D7', 'Solar Power Technologies, Inc.'),
(19900, 'F4:EA:67', 'CISCO SYSTEMS, INC.'),
(19901, 'F4:EC:38', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(19902, 'F4:EE:14', 'SHENZHEN MERCURY COMMUNICATION TECHNOLOGIES CO.,LTD.'),
(19903, 'F4:F1:5A', 'Apple'),
(19904, 'F4:F1:E1', 'Motorola Mobility LLC'),
(19905, 'F4:F2:6D', 'TP-LINK TECHNOLOGIES CO.,LTD.'),
(19906, 'F4:F5:A5', 'Nokia corporation'),
(19907, 'F4:F5:E8', 'Google'),
(19908, 'F4:F6:46', 'Dediprog Technology Co. Ltd.'),
(19909, 'F4:F9:51', 'Apple'),
(19910, 'F4:FC:32', 'Texas Instruments'),
(19911, 'F4:FD:2B', 'ZOYI Company'),
(19912, 'F8:01:13', 'Huawei Technologies Co., Ltd'),
(19913, 'F8:02:78', 'IEEE REGISTRATION AUTHORITY  - Please see MAM public listing for more information.'),
(19914, 'F8:03:32', 'Khomp'),
(19915, 'F8:04:2E', 'Samsung Electro Mechanics co., LTD.'),
(19916, 'F8:05:1C', 'DRS Imaging and Targeting Solutions'),
(19917, 'F8:0B:BE', 'ARRIS Group, Inc.'),
(19918, 'F8:0B:D0', 'Datang Telecom communication terminal (Tianjin) Co., Ltd.'),
(19919, 'F8:0C:F3', 'LG Electronics'),
(19920, 'F8:0D:43', 'Hon Hai Precision Ind. Co., Ltd.'),
(19921, 'F8:0D:EA', 'ZyCast Technology Inc.'),
(19922, 'F8:0F:41', 'Wistron InfoComm(ZhongShan) Corporation'),
(19923, 'F8:0F:84', 'Natural Security SAS'),
(19924, 'F8:10:37', 'Atopia Systems, LP'),
(19925, 'F8:15:47', 'Avaya, Inc'),
(19926, 'F8:16:54', 'Intel Corporate'),
(19927, 'F8:18:97', '2Wire'),
(19928, 'F8:1A:67', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(19929, 'F8:1C:E5', 'Telefonbau Behnke GmbH'),
(19930, 'F8:1D:93', 'Longdhua(Beijing) Controls Technology Co.,Ltd'),
(19931, 'F8:1E:DF', 'Apple'),
(19932, 'F8:22:85', 'Cypress Technology CO., LTD.'),
(19933, 'F8:24:41', 'Yeelink'),
(19934, 'F8:27:93', 'Apple, Inc'),
(19935, 'F8:2B:C8', 'Jiangsu Switter Co., Ltd'),
(19936, 'F8:2E:DB', 'RTW GmbH &amp; Co. KG'),
(19937, 'F8:2F:5B', 'eGauge Systems LLC'),
(19938, 'F8:2F:A8', 'Hon Hai Precision Ind. Co.,Ltd.'),
(19939, 'F8:30:94', 'Alcatel-Lucent Telecom Limited'),
(19940, 'F8:31:3E', 'endeavour GmbH'),
(19941, 'F8:33:76', 'Good Mind Innovation Co., Ltd.'),
(19942, 'F8:35:53', 'Magenta Research Ltd.'),
(19943, 'F8:35:DD', 'Gemtek Technology Co., Ltd.'),
(19944, 'F8:3D:4E', 'Softlink Automation System Co., Ltd'),
(19945, 'F8:3D:FF', 'Huawei Technologies Co., Ltd'),
(19946, 'F8:42:FB', 'Yasuda Joho Co.,ltd.'),
(19947, 'F8:43:60', 'INGENICO'),
(19948, 'F8:45:AD', 'Konka Group Co., Ltd.'),
(19949, 'F8:46:2D', 'SYNTEC Incorporation'),
(19950, 'F8:47:2D', 'X2gen Digital Corp. Ltd'),
(19951, 'F8:48:97', 'Hitachi, Ltd.'),
(19952, 'F8:4A:73', 'EUMTECH CO., LTD'),
(19953, 'F8:4A:7F', 'Innometriks Inc'),
(19954, 'F8:4A:BF', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(19955, 'F8:4F:57', 'Cisco'),
(19956, 'F8:50:63', 'Verathon'),
(19957, 'F8:51:6D', 'Denwa Technology Corp.'),
(19958, 'F8:52:DF', 'VNL Europe AB'),
(19959, 'F8:54:AF', 'ECI Telecom Ltd.'),
(19960, 'F8:57:2E', 'Core Brands, LLC'),
(19961, 'F8:5B:C9', 'M-Cube Spa'),
(19962, 'F8:5C:45', 'IC Nexus Co. Ltd.'),
(19963, 'F8:5F:2A', 'Nokia Corporation'),
(19964, 'F8:62:AA', 'xn systems'),
(19965, 'F8:66:01', 'Suzhou Chi-tek information technology Co., Ltd'),
(19966, 'F8:66:D1', 'Hon Hai Precision Ind. Co., Ltd.'),
(19967, 'F8:66:F2', 'CISCO SYSTEMS, INC.'),
(19968, 'F8:69:71', 'Seibu Electric Co.,'),
(19969, 'F8:6E:CF', 'Arcx Inc'),
(19970, 'F8:71:FE', 'The Goldman Sachs Group, Inc.'),
(19971, 'F8:72:EA', 'Cisco'),
(19972, 'F8:73:94', 'NETGEAR INC.,'),
(19973, 'F8:76:9B', 'Neopis Co., Ltd.'),
(19974, 'F8:7A:EF', 'Rosonix Technology, Inc.'),
(19975, 'F8:7B:62', 'FASTWEL INTERNATIONAL CO., LTD. Taiwan Branch'),
(19976, 'F8:7B:7A', 'ARRIS Group, Inc.'),
(19977, 'F8:7B:8C', 'Amped Wireless'),
(19978, 'F8:81:1A', 'OVERKIZ'),
(19979, 'F8:84:79', 'Yaojin Technology(Shenzhen)Co.,Ltd'),
(19980, 'F8:84:F2', 'Samsung Electronics Co.,Ltd'),
(19981, 'F8:8C:1C', 'KAISHUN ELECTRONIC TECHNOLOGY CO., LTD. BEIJING'),
(19982, 'F8:8D:EF', 'Tenebraex'),
(19983, 'F8:8E:85', 'COMTREND CORPORATION'),
(19984, 'F8:8F:CA', 'Google Fiber, Inc'),
(19985, 'F8:91:2A', 'GLP German Light Products GmbH'),
(19986, 'F8:93:F3', 'VOLANS'),
(19987, 'F8:95:50', 'Proton Products Chengdu Ltd'),
(19988, 'F8:97:CF', 'DAESHIN-INFORMATION TECHNOLOGY CO., LTD.'),
(19989, 'F8:99:55', 'Fortress Technology Inc'),
(19990, 'F8:9D:0D', 'Control Technology Inc.'),
(19991, 'F8:9F:B8', 'YAZAKI Energy System Corporation'),
(19992, 'F8:A0:3D', 'Dinstar Technologies Co., Ltd.'),
(19993, 'F8:A2:B4', 'RHEWA-WAAGENFABRIK August Freudewald GmbH &amp;amp;Co. KG'),
(19994, 'F8:A4:5F', 'Beijing Xiaomi communications co.,ltd'),
(19995, 'F8:A9:63', 'COMPAL INFORMATION (KUNSHAN) CO., LTD.'),
(19996, 'F8:A9:D0', 'LG Electronics'),
(19997, 'F8:A9:DE', 'PUISSANCE PLUS'),
(19998, 'F8:AA:8A', 'Axview Technology (Shenzhen) Co.,Ltd'),
(19999, 'F8:AC:6D', 'Deltenna Ltd'),
(20000, 'F8:B1:56', 'Dell Inc'),
(20001, 'F8:B2:F3', 'GUANGZHOU BOSMA TECHNOLOGY CO.,LTD'),
(20002, 'F8:B5:99', 'Guangzhou CHNAVS Digital Technology Co.,Ltd'),
(20003, 'F8:BC:12', 'Dell Inc'),
(20004, 'F8:BC:41', 'Rosslare Enterprises Limited'),
(20005, 'F8:C0:01', 'Juniper Networks'),
(20006, 'F8:C0:91', 'Highgates Technology'),
(20007, 'F8:C2:88', 'Cisco'),
(20008, 'F8:C3:97', 'NZXT Corp. Ltd.'),
(20009, 'F8:C6:78', 'Carefusion'),
(20010, 'F8:C9:6C', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(20011, 'F8:CF:C5', 'Motorola Mobility LLC, a Lenovo Company'),
(20012, 'F8:D0:AC', 'Sony Computer Entertainment Inc.'),
(20013, 'F8:D0:BD', 'Samsung Electronics Co.,Ltd'),
(20014, 'F8:D1:11', 'TP-LINK TECHNOLOGIES CO., LTD.'),
(20015, 'F8:D3:A9', 'AXAN Networks'),
(20016, 'F8:D4:62', 'Pumatronix Equipamentos Eletronicos Ltda.'),
(20017, 'F8:D7:56', 'Simm Tronic Limited'),
(20018, 'F8:D7:BF', 'REV Ritter GmbH'),
(20019, 'F8:DA:DF', 'EcoTech, Inc.'),
(20020, 'F8:DA:E2', 'Beta LaserMike'),
(20021, 'F8:DA:F4', 'Taishan Online Technology Co., Ltd.'),
(20022, 'F8:DB:4C', 'PNY Technologies, INC.'),
(20023, 'F8:DB:7F', 'HTC Corporation'),
(20024, 'F8:DB:88', 'Dell Inc'),
(20025, 'F8:DC:7A', 'Variscite LTD'),
(20026, 'F8:DF:A8', 'ZTE Corporation'),
(20027, 'F8:E0:79', 'Motorola Mobility LLC'),
(20028, 'F8:E4:FB', 'Actiontec Electronics, Inc'),
(20029, 'F8:E7:B5', '&micro;Tech Tecnologia LTDA'),
(20030, 'F8:E8:11', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(20031, 'F8:E9:03', 'D-Link International'),
(20032, 'F8:E9:68', 'Egker Kft.'),
(20033, 'F8:EA:0A', 'Dipl.-Math. Michael Rauch'),
(20034, 'F8:ED:A5', 'ARRIS Group, Inc.'),
(20035, 'F8:F0:05', 'Newport Media Inc.'),
(20036, 'F8:F0:14', 'RackWare Inc.'),
(20037, 'F8:F0:82', 'Orion Networks International, Inc'),
(20038, 'F8:F1:B6', 'Motorola Mobility LLC'),
(20039, 'F8:F2:5A', 'G-Lab GmbH'),
(20040, 'F8:F7:D3', 'International Communications Corporation'),
(20041, 'F8:F7:FF', 'SYN-TECH SYSTEMS INC'),
(20042, 'F8:FB:2F', 'Santur Corporation'),
(20043, 'F8:FE:5C', 'Reciprocal Labs Corp'),
(20044, 'F8:FE:A8', 'Technico Japan Corporation'),
(20045, 'F8:FF:5F', 'Shenzhen Communication Technology Co.,Ltd'),
(20046, 'FC:00:12', 'Toshiba Samsung Storage Technolgoy Korea Corporation'),
(20047, 'FC:01:9E', 'VIEVU'),
(20048, 'FC:01:CD', 'FUNDACION TEKNIKER'),
(20049, 'FC:06:47', 'Cortland Research, LLC'),
(20050, 'FC:07:A0', 'LRE Medical GmbH'),
(20051, 'FC:08:77', 'Prentke Romich Company'),
(20052, 'FC:09:D8', 'ACTEON Group'),
(20053, 'FC:09:F6', 'GUANGDONG TONZE ELECTRIC CO.,LTD'),
(20054, 'FC:0A:81', 'Zebra Technologies Inc'),
(20055, 'FC:0F:E6', 'Sony Computer Entertainment Inc.'),
(20056, 'FC:10:BD', 'Control Sistematizado S.A.'),
(20057, 'FC:11:86', 'Logic3 plc'),
(20058, 'FC:13:49', 'Global Apps Corp.'),
(20059, 'FC:15:B4', 'Hewlett Packard'),
(20060, 'FC:16:07', 'Taian Technology(Wuxi) Co.,Ltd.'),
(20061, 'FC:17:94', 'InterCreative Co., Ltd'),
(20062, 'FC:19:10', 'Samsung Electronics Co.,Ltd'),
(20063, 'FC:19:D0', 'Cloud Vision Networks Technology Co.,Ltd.'),
(20064, 'FC:1B:FF', 'V-ZUG AG'),
(20065, 'FC:1D:59', 'I Smart Cities HK Ltd'),
(20066, 'FC:1D:84', 'Autobase'),
(20067, 'FC:1E:16', 'IPEVO corp'),
(20068, 'FC:1F:19', 'SAMSUNG ELECTRO-MECHANICS CO., LTD.'),
(20069, 'FC:1F:C0', 'EURECAM'),
(20070, 'FC:22:9C', 'Han Kyung I Net Co.,Ltd.'),
(20071, 'FC:23:25', 'EosTek (Shenzhen) Co., Ltd.'),
(20072, 'FC:25:3F', 'Apple'),
(20073, 'FC:27:A2', 'TRANS ELECTRIC CO., LTD.'),
(20074, 'FC:2A:54', 'Connected Data, Inc.'),
(20075, 'FC:2E:2D', 'Lorom Industrial Co.LTD.'),
(20076, 'FC:2F:40', 'Calxeda, Inc.'),
(20077, 'FC:32:88', 'CELOT Wireless Co., Ltd'),
(20078, 'FC:35:98', 'Favite Inc.'),
(20079, 'FC:35:E6', 'Visteon corp'),
(20080, 'FC:3D:93', 'LONGCHEER TELECOMMUNICATION LIMITED'),
(20081, 'FC:3F:AB', 'Henan Lanxin Technology Co., Ltd'),
(20082, 'FC:44:63', 'Universal Audio, Inc'),
(20083, 'FC:44:99', 'Swarco LEA d.o.o.'),
(20084, 'FC:45:5F', 'JIANGXI SHANSHUI OPTOELECTRONIC TECHNOLOGY CO.,LTD'),
(20085, 'FC:48:EF', 'HUAWEI TECHNOLOGIES CO.,LTD'),
(20086, 'FC:4A:E9', 'Castlenet Technology Inc.'),
(20087, 'FC:4B:1C', 'INTERSENSOR S.R.L.'),
(20088, 'FC:4B:BC', 'Sunplus Technology Co., Ltd.'),
(20089, 'FC:4D:D4', 'Universal Global Scientific Industrial Co., Ltd.'),
(20090, 'FC:50:90', 'SIMEX Sp. z o.o.'),
(20091, 'FC:52:CE', 'Control iD'),
(20092, 'FC:58:FA', 'Shen Zhen Shi Xin Zhong Xin Technology Co.,Ltd.'),
(20093, 'FC:5B:24', 'Weibel Scientific A/S'),
(20094, 'FC:5B:26', 'MikroBits'),
(20095, 'FC:5B:39', 'Cisco'),
(20096, 'FC:60:18', 'Zhejiang Kangtai Electric Co., Ltd.'),
(20097, 'FC:61:98', 'NEC Personal Products, Ltd'),
(20098, 'FC:62:6E', 'Beijing MDC Telecom'),
(20099, 'FC:62:B9', 'ALPS ERECTRIC CO.,LTD'),
(20100, 'FC:68:3E', 'Directed Perception, Inc'),
(20101, 'FC:6C:31', 'LXinstruments GmbH'),
(20102, 'FC:6D:C0', 'BME CORPORATION'),
(20103, 'FC:75:16', 'D-Link International'),
(20104, 'FC:75:E6', 'Handreamnet'),
(20105, 'FC:79:0B', 'Hitachi High Technologies America, Inc.'),
(20106, 'FC:7C:E7', 'FCI USA LLC'),
(20107, 'FC:83:29', 'Trei technics'),
(20108, 'FC:83:99', 'Avaya, Inc'),
(20109, 'FC:8B:97', 'Shenzhen Gongjin Electronics Co.,Ltd'),
(20110, 'FC:8E:7E', 'Pace plc'),
(20111, 'FC:8F:C4', 'Intelligent Technology Inc.'),
(20112, 'FC:92:3B', 'Nokia Corporation'),
(20113, 'FC:94:6C', 'UBIVELOX'),
(20114, 'FC:94:E3', 'Technicolor USA Inc.'),
(20115, 'FC:99:47', 'Cisco'),
(20116, 'FC:9F:AE', 'Fidus Systems Inc'),
(20117, 'FC:9F:E1', 'CONWIN.Tech. Ltd'),
(20118, 'FC:A1:3E', 'Samsung Electronics'),
(20119, 'FC:A2:2A', 'PT. Callysta Multi Engineering'),
(20120, 'FC:A8:41', 'Avaya, Inc'),
(20121, 'FC:A9:B0', 'MIARTECH (SHANGHAI),INC.'),
(20122, 'FC:AA:14', 'GIGA-BYTE TECHNOLOGY CO.,LTD.'),
(20123, 'FC:AD:0F', 'QTS NETWORKS'),
(20124, 'FC:AF:6A', 'Conemtech AB'),
(20125, 'FC:AF:AC', 'Panasonic System LSI'),
(20126, 'FC:B0:C4', 'Shanghai DareGlobal Technologies Co., Ltd'),
(20127, 'FC:B4:E6', 'ASKEY COMPUTER CORP.'),
(20128, 'FC:B6:98', 'Cambridge Industries(Group) Co.,Ltd.'),
(20129, 'FC:BB:A1', 'Shenzhen Minicreate Technology Co.,Ltd'),
(20130, 'FC:C2:3D', 'Atmel Corporation'),
(20131, 'FC:C2:DE', 'Murata Manufacturing Co., Ltd.'),
(20132, 'FC:C7:34', 'Samsung Electronics Co.,Ltd'),
(20133, 'FC:C8:97', 'ZTE Corporation'),
(20134, 'FC:CC:E4', 'Ascon Ltd.'),
(20135, 'FC:CF:62', 'IBM Corp'),
(20136, 'FC:D4:F2', 'The Coca Cola Company'),
(20137, 'FC:D4:F6', 'Messana Air.Ray Conditioning s.r.l.'),
(20138, 'FC:D5:D9', 'Shenzhen SDMC Technology Co., Ltd.'),
(20139, 'FC:D6:BD', 'Robert Bosch GmbH'),
(20140, 'FC:D8:17', 'Beijing Hesun Technologies Co.Ltd.'),
(20141, 'FC:DB:96', 'ENERVALLEY CO., LTD'),
(20142, 'FC:DB:B3', 'Murata Manufacturing Co., Ltd.'),
(20143, 'FC:DC:4A', 'G-Wearables Corp.'),
(20144, 'FC:DD:55', 'Shenzhen WeWins wireless Co.,Ltd'),
(20145, 'FC:E1:86', 'A3M Co., LTD'),
(20146, 'FC:E1:92', 'Sichuan Jinwangtong Electronic Science&amp;Technology Co,.Ltd'),
(20147, 'FC:E1:D9', 'Stable Imaging Solutions LLC'),
(20148, 'FC:E2:3F', 'CLAY PAKY SPA'),
(20149, 'FC:E5:57', 'Nokia Corporation'),
(20150, 'FC:E8:92', 'Hangzhou Lancable Technology Co.,Ltd'),
(20151, 'FC:E9:98', 'Apple'),
(20152, 'FC:ED:B9', 'Arrayent'),
(20153, 'FC:F1:52', 'Sony Corporation'),
(20154, 'FC:F1:CD', 'OPTEX-FA CO.,LTD.'),
(20155, 'FC:F5:28', 'ZyXEL Communications Corporation'),
(20156, 'FC:F6:47', 'Fiberhome Telecommunication Tech.Co.,Ltd.'),
(20157, 'FC:F8:AE', 'Intel Corporate'),
(20158, 'FC:F8:B7', 'TRONTEQ Electronic'),
(20159, 'FC:FA:F7', 'Shanghai Baud Data Communication Co.,Ltd.'),
(20160, 'FC:FB:FB', 'CISCO SYSTEMS, INC.'),
(20161, 'FC:FE:77', 'Hitachi Reftechno, Inc.'),
(20162, 'FC:FF:AA', 'IEEE REGISTRATION AUTHORITY  - Please see MAL public listing for more information.');
