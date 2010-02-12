<?php
# Version 1.01
# Correspondance between Description of device and oid to get serial
###########################################
################# Switchs #################
###########################################
/*
<SNMPDISCOVERY>
   <DEVICE>
      <MANUFACTURER NAME="3Com" SERIAL=".1.3.6.1.4.1.43.29.4.18.2.1.7.1" TYPE="2" MODELSNMP="Networking0004"/>
      <SYSDESCR>3Com IntelliJack NJ225</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="3Com" SERIAL=".1.3.6.1.4.1.43.10.27.1.1.1.13.1" TYPE="2" MODELSNMP="Networking0002"/>
      <SYSDESCR>3Com SuperStack</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Alcatel" SERIAL=".1.3.6.1.2.1.47.1.1.1.1.11.67108992" TYPE="2" MODELSNMP="Networking0002"/>
      <SYSDESCR>OmniStack LS</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Alvarion" TYPE="2" MODELSNMP="Networking0002"/>
      <SYSDESCR>Alvarion - BreezeNet B</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Apple" TYPE="2"/>
      <SYSDESCR>Apple AirPort</SYSDESCR>
      <SYSDESCR>Apple Base Station</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Avaya" TYPE="2" MODELSNMP="Networking0002"/>
      <SYSDESCR>Avaya Inc. - P330 Stackable Switch</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" TYPE="2"/>
      <SYSDESCR>Cisco IOS Software, C850</SYSDESCR>
      <SYSDESCR>Cisco IOS Software, C870</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" TYPE="2" MODELSNMP="Networking0005"/>
      <SYSDESCR>Cisco Cisco PIX Security Appliance</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" SERIAL=".1.3.6.1.2.1.47.1.1.1.1.11.1" TYPE="2" MODELSNMP="Networking0005"/>
       <SYSDESCR>IOS (tm) C2950</SYSDESCR>
      <SYSDESCR>Cisco IOS Software, Catalyst 4000</SYSDESCR>
      <SYSDESCR>IOS (tm) Catalyst 4000</SYSDESCR>
      <SYSDESCR>Cisco Systems, Inc. WS-C4003 Cisco Catalyst</SYSDESCR>
      <SYSDESCR>Cisco IOS Software, Catalyst 4500</SYSDESCR>
      <SYSDESCR>Cisco Systems, Inc. WS-C4506 Cisco Catalyst</SYSDESCR>
      <SYSDESCR>Cisco Systems WS-C6006 Cisco Catalyst</SYSDESCR>
      <SYSDESCR>Cisco IOS Software, s72033_rp Software</SYSDESCR>
      <SYSDESCR>IOS (tm) s72033</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" SERIAL=".1.3.6.1.2.1.47.1.1.1.1.11.1" TYPE="2" MODELSNMP="Networking0007"/>
       <SYSDESCR>Cisco IOS Software, 2800 Software</SYSDESCR>
       <SYSDESCR>IOS (tm) C2900XL</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" SERIAL=".1.3.6.1.2.1.47.1.1.1.1.11.1001" TYPE="2" MODELSNMP="Networking0001"/>
       <SYSDESCR>Cisco IOS Software, C2960 Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, C3550 Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, C3560 Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, C3560E Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, C3750 Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, CBS31X0 Software</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Cisco" SERIAL=".1.3.6.1.4.1.9.3.6.3.0" TYPE="2" MODELSNMP="Networking0006"/>
       <SYSDESCR>Cisco IOS Software, 1841 Software</SYSDESCR>
       <SYSDESCR>Cisco IOS Software, C1</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Dlink" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>DES-3</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Extreme Networks" TYPE="2"/>
       <SYSDESCR>Summit200</SYSDESCR>
       <SYSDESCR>Summit300</SYSDESCR>
       <SYSDESCR>Summit400</SYSDESCR>
       <SYSDESCR>BlackDiamond6</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Foundry Networks" TYPE="2" MODELSNMP="Networking0007"/>
       <SYSDESCR>Foundry Networks</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="HP" SERIAL=".1.3.6.1.4.1.11.2.36.1.1.2.9.0" TYPE="2" MODELSNMP="Networking0003"/>
       <SYSDESCR>PROCURVE J</SYSDESCR>
       <SYSDESCR>ProCurve J</SYSDESCR>
       <SYSDESCR>ProCurve Switch</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="HP" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>HP ProCurve Access Point</SYSDESCR>
       <SYSDESCR>ProCurve Access Point</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Motorola" TYPE="2"/>
       <SYSDESCR>Netopia</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="NetGear" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>Linux PA2 2.4.27-devicescape.3 #2 Wed Oct 3 15:02:28 EDT 2007 armv5b</SYSDESCR>
       <SYSDESCR>ProSafe 802.11b/g Wireless Access Point</SYSDESCR>
       <SYSDESCR>FS726</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Nortel" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>BayStack </SYSDESCR>
       <SYSDESCR>Passport-</SYSDESCR>
       <SYSDESCR>Ethernet Switch 425-24T</SYSDESCR>
       <SYSDESCR>Ethernet Routing Switch 25</SYSDESCR>
       <SYSDESCR>Ethernet Routing Switch 55</SYSDESCR>
       <SYSDESCR>Alteon Application Switch</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="SMC" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>TigerSwitch 10/100 SMC</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="SonicWall" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>SonicWALL</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Zyxel" TYPE="2" MODELSNMP="Networking0002"/>
       <SYSDESCR>ZyWALL USG</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Zyxel" TYPE="2"/>
       <SYSDESCR>ZyWALL USG</SYSDESCR>
       <SYSDESCR>ZyWALL</SYSDESCR>
       <SYSDESCR>ZyXEL</SYSDESCR>
       <SYSDESCR>Prestige 3</SYSDESCR>
       <SYSDESCR>Prestige 6</SYSDESCR>
       <SYSDESCR>P-660H</SYSDESCR>
       <SYSDESCR>P-660R</SYSDESCR>
       <SYSDESCR>P-661H</SYSDESCR>
       <SYSDESCR>P-662H</SYSDESCR>
   </DEVICE>





   <DEVICE>
      <MANUFACTURER NAME="Axis" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>AXIS 5000+ Network Print Server</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Brother" SERIAL=".1.3.6.1.4.1.2435.2.3.9.4.2.1.5.5.1.0" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>Brother NC-</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Canon" SERIAL=".1.3.6.1.4.1.1602.1.2.1.4.0" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>Canon CLC-iR</SYSDESCR>
      <SYSDESCR>Canon LBP</SYSDESCR>
      <SYSDESCR>Canon W8</SYSDESCR>
      <SYSDESCR></SYSDESCR>
      <SYSDESCR></SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Canon" SERIAL=".1.3.6.1.4.1.1602.1.2.1.4.0" TYPE="3" MODELSNMP="Printer0004"/>
      <SYSDESCR>Canon iR C</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Canon" SERIAL=".1.3.6.1.4.1.1602.1.2.1.4.0" TYPE="3" MODELSNMP="Printer0009"/>
      <SYSDESCR>Canon iR 3180C</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Canon" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>Canon CLC5</SYSDESCR>
      <SYSDESCR>Canon Inc., LBP</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Dlink" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>D-Link DP-301U Print Server</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Dell" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>DELL NETWORK PRINTER</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Dell" SERIAL=".1.3.6.1.2.1.43.5.1.1.17.1" TYPE="3" MODELSNMP="Printer0001"/>
      <SYSDESCR>Dell Laser Printer</SYSDESCR>
   </DEVICE>
   <DEVICE>
      <MANUFACTURER NAME="Dell" SERIAL=".1.3.6.1.2.1.43.5.1.1.17.1.1" TYPE="3" MODELSNMP="Printer0007"/>
      <SYSDESCR>Dell  18</SYSDESCR>
   </DEVICE>








</SNMPDISCOVERY>
*/
	#* 3Com
	$SerialNumberDef['3Com IntelliJack NJ225'] = '.1.3.6.1.4.1.43.29.4.18.2.1.7.1';
	$TypeDef['3Com IntelliJack NJ225'] = 2;
	$ModelDef['3Com IntelliJack NJ225'] = 'Networking0004';

	$SerialNumberDef['3Com SuperStack'] = '.1.3.6.1.4.1.43.10.27.1.1.1.13.1';
	$TypeDef['3Com SuperStack'] = 2;
	$ModelDef['3Com SuperStack'] = 'Networking0002';

	#* Alcatel
	$SerialNumberDef['OmniStack LS'] = '.1.3.6.1.2.1.47.1.1.1.1.11.67108992';
	$TypeDef['OmniStack LS'] = 2;
	$ModelDef['OmniStack LS'] = 'Networking0002';

	#* Alvarion (Wireless)
	$SerialNumberDef['Alvarion - BreezeNet B'] = '';
	$TypeDef['Alvarion - BreezeNet B'] = 2;
	$ModelDef['Alvarion - BreezeNet B'] = 'Networking0002';

   #* Apple (wireless router)
	$SerialNumberDef['Apple AirPort'] = '';
	$TypeDef['Apple AirPort'] = 2;
	$ModelDef['Apple AirPort'] = '';

	$SerialNumberDef['Apple Base Station'] = '';
	$TypeDef['Apple Base Station'] = 2;
	$ModelDef['Apple Base Station'] = '';

	#* Avaya
	$SerialNumberDef['Avaya Inc. - P330 Stackable Switch'] = '';
	$TypeDef['Avaya Inc. - P330 Stackable Switch'] = 2;
	$ModelDef['Avaya Inc. - P330 Stackable Switch'] = 'Networking0002';

	#* Cisco

      #** C850 (Router)

         $SerialNumberDef['Cisco IOS Software, C850'] = '';
         $TypeDef['Cisco IOS Software, C850'] = 2;
         $ModelDef['Cisco IOS Software, C850'] = '';

      #** C870 (Router)

         $SerialNumberDef['Cisco IOS Software, C870'] = '';
         $TypeDef['Cisco IOS Software, C870'] = 2;
         $ModelDef['Cisco IOS Software, C870'] = '';

      #** C2800
         $SerialNumberDef['Cisco IOS Software, 2800 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco IOS Software, 2800 Software'] = 2;
         $ModelDef['Cisco IOS Software, 2800 Software'] = 'Networking0007';

      #** C2900XL (2912 / 2924)

         $SerialNumberDef['IOS (tm) C2900XL'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['IOS (tm) C2900XL'] = 2;
         $ModelDef['IOS (tm) C2900XL'] = 'Networking0007';

      #** C2950

#         $SerialNumberDef['Cisco Internetwork Operating System Software IOS (tm) C2950 Software (C2950    ), Version 12.0(5.3)WC(1)'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
#         $TypeDef['IOS (tm) C2950'] = 2;
#         $ModelDef['IOS (tm) C2950'] = 'Networking0007';

#         $SerialNumberDef['Cisco Internetwork Operating System Software IOS (tm) C2950'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
#         $TypeDef['Cisco Internetwork Operating System Software IOS (tm) C2950'] = 2;
#         $ModelDef['Cisco Internetwork Operating System Software IOS (tm) C2950'] = 'Networking0005';

         $SerialNumberDef['IOS (tm) C2950'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['IOS (tm) C2950'] = 2;
         $ModelDef['IOS (tm) C2950'] = 'Networking0005';

      #** C2960

         $SerialNumberDef['Cisco IOS Software, C2960 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, C2960 Software'] = 2;
         $ModelDef['Cisco IOS Software, C2960 Software'] = 'Networking0001';

      #** C3550

         $SerialNumberDef['Cisco IOS Software, C3550 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, C3550 Software'] = 2;
         $ModelDef['Cisco IOS Software, C3550 Software'] = 'Networking0001';

      #** C3560

         $SerialNumberDef['Cisco IOS Software, C3560 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, C3560 Software'] = 2;
         $ModelDef['Cisco IOS Software, C3560 Software'] = 'Networking0001';

         $SerialNumberDef['Cisco IOS Software, C3560E Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, C3560E Software'] = 2;
         $ModelDef['Cisco IOS Software, C3560E Software'] = 'Networking0001';

      #** C3750

         $SerialNumberDef['Cisco IOS Software, C3750 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, C3750 Software'] = 2;
         $ModelDef['Cisco IOS Software, C3750 Software'] = 'Networking0001';

      #** C4000

         $SerialNumberDef['Cisco IOS Software, Catalyst 4000'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco IOS Software, Catalyst 4000'] = 2;
         $ModelDef['Cisco IOS Software, Catalyst 4000'] = 'Networking0005';

         $SerialNumberDef['IOS (tm) Catalyst 4000'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['IOS (tm) Catalyst 4000'] = 2;
         $ModelDef['IOS (tm) Catalyst 4000'] = 'Networking0005';

      #** C4003

         $SerialNumberDef['Cisco Systems, Inc. WS-C4003 Cisco Catalyst'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco Systems, Inc. WS-C4003 Cisco Catalyst'] = 2;
         $ModelDef['Cisco Systems, Inc. WS-C4003 Cisco Catalyst'] = 'Networking0005';

      #** C4500

         $SerialNumberDef['Cisco IOS Software, Catalyst 4500'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco IOS Software, Catalyst 4500'] = 2;
         $ModelDef['Cisco IOS Software, Catalyst 4500'] = 'Networking0005';

      #** C4506

         $SerialNumberDef['Cisco Systems, Inc. WS-C4506 Cisco Catalyst'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco Systems, Inc. WS-C4506 Cisco Catalyst'] = 2;
         $ModelDef['Cisco Systems, Inc. WS-C4506 Cisco Catalyst'] = 'Networking0005';

      #** C6006

         $SerialNumberDef['Cisco Systems WS-C6006 Cisco Catalyst'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco Systems WS-C6006 Cisco Catalyst'] = 2;
         $ModelDef['Cisco Systems WS-C6006 Cisco Catalyst'] = 'Networking0005';

      #** Model 6xxx

         $SerialNumberDef['Cisco IOS Software, s72033_rp Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['Cisco IOS Software, s72033_rp Software'] = 2;
         $ModelDef['Cisco IOS Software, s72033_rp Software'] = 'Networking0005';

      #** Other

         $SerialNumberDef['Cisco IOS Software, Catalyst'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, Catalyst'] = 2;
         $ModelDef['Cisco IOS Software, Catalyst'] = 'Networking0001';

         $SerialNumberDef['IOS (tm) s72033'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1';
         $TypeDef['IOS (tm) s72033'] = 2;
         $ModelDef['IOS (tm) s72033'] = 'Networking0005';

         $SerialNumberDef['Cisco Cisco PIX Security Appliance'] = '';
         $TypeDef['Cisco Cisco PIX Security Appliance'] = 2;
         $ModelDef['Cisco Cisco PIX Security Appliance'] = 'Networking0002';

         $SerialNumberDef['Cisco Systems Catalyst'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco Systems Catalyst'] = 2;
         $ModelDef['Cisco Systems Catalyst'] = 'Networking0001';

         $SerialNumberDef['Cisco Internetwork Operating System Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco Internetwork Operating System Software'] = 2;
         $ModelDef['Cisco Internetwork Operating System Software'] = 'Networking0001';

         $SerialNumberDef['Cisco IOS Software, 1841 Software'] = '.1.3.6.1.4.1.9.3.6.3.0';
         $TypeDef['Cisco IOS Software, 1841 Software'] = 2;
         $ModelDef['Cisco IOS Software, 1841 Software'] = 'Networking0006';

         $SerialNumberDef['Cisco IOS Software, C1'] = '.1.3.6.1.4.1.9.3.6.3.0';
         $TypeDef['Cisco IOS Software, C1'] = 2;
         $ModelDef['Cisco IOS Software, C1'] = 'Networking0006';

      #	$SerialNumberDef['Cisco IOS Software, C1100 Software'] = '.1.3.6.1.4.1.9.3.6.3.0';
      #	$TypeDef['Cisco IOS Software, C1100 Software'] = 2;
      #	$ModelDef['Cisco IOS Software, C1100 Software'] = 'Networking0006';
      #
      #	$SerialNumberDef['Cisco IOS Software, C1130 Software'] = '.1.3.6.1.4.1.9.3.6.3.0';
      #	$TypeDef['Cisco IOS Software, C1130 Software'] = 2;
      #	$ModelDef['Cisco IOS Software, C1130 Software'] = 'Networking0006';
      #
      #	$SerialNumberDef['Cisco IOS Software, C1200 Software'] = '.1.3.6.1.4.1.9.3.6.3.0';
      #	$TypeDef['Cisco IOS Software, C1200 Software'] = 2;
      #	$ModelDef['Cisco IOS Software, C1200 Software'] = 'Networking0006';
      #
      #	$SerialNumberDef['Cisco IOS Software, C1310 Software'] = '.1.3.6.1.4.1.9.3.6.3.0';
      #	$TypeDef['Cisco IOS Software, C1310 Software'] = 2;
      #	$ModelDef['Cisco IOS Software, C1310 Software'] = 'Networking0006';

         $SerialNumberDef['Cisco IOS Software, CBS31X0 Software'] = '.1.3.6.1.2.1.47.1.1.1.1.11.1001';
         $TypeDef['Cisco IOS Software, CBS31X0 Software'] = 2;
         $ModelDef['Cisco IOS Software, CBS31X0 Software'] = 'Networking0001';


	#* Dlink
	$SerialNumberDef['DES-3'] = '';
	$TypeDef['DES-3'] = 2;
	$ModelDef['DES-3'] = 'Networking0002';

   #* extreme networks
	$SerialNumberDef['Summit200'] = '';
	$TypeDef['Summit200'] = 2;
	$ModelDef['Summit200'] = '';

	$SerialNumberDef['Summit300'] = '';
	$TypeDef['Summit300'] = 2;
	$ModelDef['Summit300'] = '';

	$SerialNumberDef['Summit400'] = '';
	$TypeDef['Summit400'] = 2;
	$ModelDef['Summit400'] = '';

	$SerialNumberDef['BlackDiamond6'] = '';
	$TypeDef['BlackDiamond6'] = 2;
	$ModelDef['BlackDiamond6'] = '';

   #* Foundry Networks
   $SerialNumberDef['Foundry Networks'] = '';
   $TypeDef['Foundry Networks'] = 2;
	$ModelDef['Foundry Networks'] = 'Networking0007';

	#* HP
	$SerialNumberDef['PROCURVE J'] = '.1.3.6.1.4.1.11.2.36.1.1.2.9.0';
	$TypeDef['PROCURVE J'] = 2;
	$ModelDef['PROCURVE J'] = 'Networking0003';

	$SerialNumberDef['ProCurve J'] = '.1.3.6.1.4.1.11.2.36.1.1.2.9.0';
	$TypeDef['ProCurve J'] = 2;
	$ModelDef['ProCurve J'] = 'Networking0003';

	$SerialNumberDef['HP ProCurve Access Point'] = '';
	$TypeDef['HP ProCurve Access Point'] = 2;
	$ModelDef['HP ProCurve Access Point'] = 'Networking0002';

	$SerialNumberDef['ProCurve Access Point'] = '';
	$TypeDef['ProCurve Access Point'] = 2;
	$ModelDef['ProCurve Access Point'] = 'Networking0002';

	$SerialNumberDef['ProCurve Switch'] = '.1.3.6.1.4.1.11.2.36.1.1.2.9.0';
	$TypeDef['ProCurve Switch'] = 2;
	$ModelDef['ProCurve Switch'] = 'Networking0003';

   #* Motorola
	$SerialNumberDef['Netopia'] = '';
	$TypeDef['Netopia'] = 2;
	$ModelDef['Netopia'] = '';

	#* NetGear
	# Wireless
		$SerialNumberDef['Linux PA2 2.4.27-devicescape.3 #2 Wed Oct 3 15:02:28 EDT 2007 armv5b'] = '';
		$TypeDef['Linux PA2 2.4.27-devicescape.3 #2 Wed Oct 3 15:02:28 EDT 2007 armv5b'] = 2;
		$ModelDef['Linux PA2 2.4.27-devicescape.3 #2 Wed Oct 3 15:02:28 EDT 2007 armv5b'] = 'Networking0002';

		$SerialNumberDef['ProSafe 802.11b/g Wireless Access Point'] = '';
		$TypeDef['ProSafe 802.11b/g Wireless Access Point'] = 2;
		$ModelDef['ProSafe 802.11b/g Wireless Access Point'] = 'Networking0002';

	$SerialNumberDef['FS726'] = '';
	$TypeDef['FS726'] = 2;
	$ModelDef['FS726'] = 'Networking0002';

	#* Nortel
	$SerialNumberDef['BayStack '] = '';
	$TypeDef['BayStack '] = 2;
	$ModelDef['BayStack '] = 'Networking0002';

	$SerialNumberDef['Passport-'] = '';
	$TypeDef['Passport-'] = 2;
	$ModelDef['Passport-'] = 'Networking0002';

	$SerialNumberDef['Ethernet Switch 425-24T'] = '';
	$TypeDef['Ethernet Switch 425-24T'] = 2;
	$ModelDef['Ethernet Switch 425-24T'] = 'Networking0002';

	$SerialNumberDef['Ethernet Routing Switch 25'] = '';
	$TypeDef['Ethernet Routing Switch 25'] = 2;
	$ModelDef['Ethernet Routing Switch 25'] = 'Networking0002';

	$SerialNumberDef['Ethernet Routing Switch 55'] = '';
	$TypeDef['Ethernet Routing Switch 55'] = 2;
	$ModelDef['Ethernet Routing Switch 55'] = 'Networking0002';

	$SerialNumberDef['Alteon Application Switch'] = '';
	$TypeDef['Alteon Application Switch'] = 2;
	$ModelDef['Alteon Application Switch'] = 'Networking0002';

	#* SMC
	$SerialNumberDef['TigerSwitch 10/100 SMC'] = '';
	$TypeDef['TigerSwitch 10/100 SMC'] = 2;
	$ModelDef['TigerSwitch 10/100 SMC'] = 'Networking0002';

	#* SonicWall (FireWall)
	$SerialNumberDef['SonicWALL'] = '';
	$TypeDef['SonicWALL'] = 2;
	$ModelDef['SonicWALL'] = 'Networking0002';

	#* Zyxel
	$SerialNumberDef['ZyWALL USG '] = '';
	$TypeDef['ZyWALL USG '] = 2;
	$ModelDef['ZyWALL USG '] = 'Networking0002';

	$SerialNumberDef['ZyWALL'] = '';
	$TypeDef['ZyWALL'] = 2;
	$ModelDef['ZyWALL'] = '';

	$SerialNumberDef['ZyXEL'] = '';
	$TypeDef['ZyXEL'] = 2;
	$ModelDef['ZyXEL'] = '';

	$SerialNumberDef['Prestige 3'] = '';
	$TypeDef['Prestige 3'] = 2;
	$ModelDef['Prestige 3'] = '';

	$SerialNumberDef['Prestige 6'] = '';
	$TypeDef['Prestige 6'] = 2;
	$ModelDef['Prestige 6'] = '';

	$SerialNumberDef['P-660H'] = '';
	$TypeDef['P-660H'] = 2;
	$ModelDef['P-660H'] = '';

	$SerialNumberDef['P-660R'] = '';
	$TypeDef['P-660R'] = 2;
	$ModelDef['P-660R'] = '';

	$SerialNumberDef['P-661H'] = '';
	$TypeDef['P-661H'] = 2;
	$ModelDef['P-661H'] = '';

	$SerialNumberDef['P-662H'] = '';
	$TypeDef['P-662H'] = 2;
	$ModelDef['P-662H'] = '';

###########################################
################ Printers #################
###########################################

	#* Axis
	$SerialNumberDef['AXIS 5[0-9]00+ Network Print Server'] = '';
	$TypeDef['AXIS 5[0-9]00+ Network Print Server'] = 3;
	$ModelDef['AXIS 5[0-9]00+ Network Print Server'] = 'Printer0001';

	#* Brother
	$SerialNumberDef['Brother NC-'] = '.1.3.6.1.4.1.2435.2.3.9.4.2.1.5.5.1.0';
	$TypeDef['Brother NC-'] = 3;
	$ModelDef['Brother NC-'] = 'Printer0001';

	#* Canon
	$SerialNumberDef['Canon iR'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon iR'] = 3;
	$ModelDef['Canon iR'] = 'Printer0001';

	$SerialNumberDef['Canon iR C'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon iR C'] = 3;
	$ModelDef['Canon iR C'] = 'Printer0004';

	$SerialNumberDef['Canon CLC-iR'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon CLC-iR'] = 3;
	$ModelDef['Canon CLC-iR'] = 'Printer0001';

	$SerialNumberDef['Canon CLC5'] = '';
   $TypeDef['Canon CLC5'] = 3;
   $ModelDef['Canon CLC5'] = 'Printer0001';

	$SerialNumberDef['Canon LBP'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon LBP'] = 3;
	$ModelDef['Canon LBP'] = 'Printer0001';

	$SerialNumberDef['Canon Inc., LBP'] = '';
   $TypeDef['Canon Inc., LBP'] = 3;
   $ModelDef['Canon Inc., LBP'] = 'Printer0001';

	$SerialNumberDef['Canon W8'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon W8'] = 3;
	$ModelDef['Canon W8'] = 'Printer0001';

	$SerialNumberDef['Canon iR 3180C'] = '.1.3.6.1.4.1.1602.1.2.1.4.0';
	$TypeDef['Canon iR 3180C'] = 3;
	$ModelDef['Canon iR 3180C'] = 'Printer0009';

	#* Dlink
	$SerialNumberDef['D-Link DP-301U Print Server'] = '';
	$TypeDef['D-Link DP-301U Print Server'] = 3;
	$ModelDef['D-Link DP-301U Print Server'] = 'Printer0001';

	#* Dell
	$SerialNumberDef['Dell Laser Printer'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['Dell Laser Printer'] = 3;
	$ModelDef['Dell Laser Printer'] = 'Printer0001';

	$SerialNumberDef['DELL NETWORK PRINTER'] = '';
	$TypeDef['DELL NETWORK PRINTER'] = 3;
	$ModelDef['DELL NETWORK PRINTER'] = 'Printer0001';

	$SerialNumberDef['Dell  18'] = '.1.3.6.1.2.1.43.5.1.1.17.1.1';
	$TypeDef['Dell  18'] = 3;
	$ModelDef['Dell  18'] = 'Printer0007';

	$SerialNumberDef['Dell Laser Printer 1720'] = '.1.3.6.1.4.1.674.10898.100.2.1.2.1.6.1';
	$TypeDef['Dell Laser Printer 1720'] = 3;
	$ModelDef['Dell Laser Printer 1720'] = 'Printer0008';

	$SerialNumberDef['Dell Color Laser'] = '.1.3.6.1.4.1.253.8.53.3.2.1.3.1';
	$TypeDef['Dell Color Laser'] = 3;
	$ModelDef['Dell Color Laser'] = 'Printer0001';

	$SerialNumberDef['Dell MFP Laser'] = '.1.3.6.1.4.1.253.8.53.3.2.1.3.1';
	$TypeDef['Dell MFP Laser'] = 3;
	$ModelDef['Dell MFP Laser'] = 'Printer0001';

	#* Epson
	$SerialNumberDef['EPSON Built-in 10Base-T/100Base-TX Print Server'] = '';
	$TypeDef['EPSON Built-in 10Base-T/100Base-TX Print Server'] = 3;
	$ModelDef['EPSON Built-in 10Base-T/100Base-TX Print Server'] = 'Printer0001';

	$SerialNumberDef['EPSON Type-B 10Base-T/100Base-TX Print Server'] = '';
	$TypeDef['EPSON Type-B 10Base-T/100Base-TX Print Server'] = 3;
	$ModelDef['EPSON Type-B 10Base-T/100Base-TX Print Server'] = 'Printer0001';

	$SerialNumberDef['EPSON AL-'] = '.1.3.6.1.4.1.1248.1.2.2.1.1.1.5.'; # Todo : verify
	$TypeDef['EPSON AL-'] = 3;
	$ModelDef['EPSON AL-'] = 'Printer0001';

	#* Fiery (Network module for differents printers (kinica, canon...)
	$SerialNumberDef['Fiery X3e'] = '';
	$TypeDef['Fiery X3e'] = 3;
	$ModelDef['Fiery X3e'] = 'Printer0001';

	#* HP
	$SerialNumberDef['HP ETHERNET MULTI-ENVIRONMENT'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['HP ETHERNET MULTI-ENVIRONMENT'] = 3;
	$ModelDef['HP ETHERNET MULTI-ENVIRONMENT'] = 'Printer0001';

	$SerialNumberDef['hp color LaserJet'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['hp color LaserJet'] = 3;
	$ModelDef['hp color LaserJet'] = 'Printer0005';

	$SerialNumberDef['HP Color Laserjet'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['HP Color Laserjet'] = 3;
	$ModelDef['HP Color Laserjet'] = 'Printer0005';

	$SerialNumberDef['HP Color LaserJet'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['HP Color LaserJet'] = 3;
	$ModelDef['HP Color LaserJet'] = 'Printer0005';

	$SerialNumberDef['HP Color Inkjet CP1'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
   $TypeDef['HP Color Inkjet CP1'] = 3;
   $ModelDef['HP Color Inkjet CP1'] = 'Printer0005';

	$SerialNumberDef['HP LaserJet'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['HP LaserJet'] = 3;
	$ModelDef['HP LaserJet'] = 'Printer0006';

	$SerialNumberDef['hp LaserJet'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
	$TypeDef['hp LaserJet'] = 3;
	$ModelDef['hp LaserJet'] = 'Printer0006';

	$SerialNumberDef['hp business inkjet 3000'] = '.1.3.6.1.2.1.43.5.1.1.17.1';
   $TypeDef['hp business inkjet 3000'] = 3;
   $ModelDef['hp business inkjet 3000'] = 'Printer0005';

	$SerialNumberDef['Officejet 6'] = '';
   $TypeDef['Officejet 6'] = 3;
   $ModelDef['Officejet 6'] = 'Printer0001';

   $SerialNumberDef['HP Officejet Pro K'] = '';
   $TypeDef['HP Officejet Pro K'] = 3;
   $ModelDef['HP Officejet Pro K'] = 'Printer0001';

   $SerialNumberDef['Photosmart C'] = '';
   $TypeDef['Photosmart C'] = 3;
   $ModelDef['Photosmart C'] = 'Printer0001';

	#* Intel
	$SerialNumberDef['NetportExpress(tm) 10/100'] = ''; # Serveur d'impression
	$TypeDef['NetportExpress(tm) 10/100'] = 3;
	$ModelDef['NetportExpress(tm) 10/100'] = 'Printer0001';

   #* Intermec
   $SerialNumberDef['Thermal Label Printer Intermec EasyCoder PF4i'] = '';
   $TypeDef['Thermal Label Printer Intermec EasyCoder PF4i'] = 3;
   $ModelDef['Thermal Label Printer Intermec EasyCoder PF4i'] = 'Printer0001';

	#* KONICA MINOLTA
   $SerialNumberDef['KONICA MINOLTA'] = '';
   $TypeDef['KONICA MINOLTA'] = 3;
   $ModelDef['KONICA MINOLTA'] = 'Printer0001';

#	$SerialNumberDef['KONICA MINOLTA bizhub 501'] = '';
#	$TypeDef['KONICA MINOLTA bizhub 501'] = 3;
#	$ModelDef['KONICA MINOLTA bizhub 501'] = 'Printer0001';

	#* KYOCERA
	$SerialNumberDef['KYOCERA MITA Printing'] = '';
	$TypeDef['KYOCERA MITA Printing'] = 3;
	$ModelDef['KYOCERA MITA Printing'] = 'Printer0001';

	#* Lexmark
	$SerialNumberDef['Lexmark C'] = '.1.3.6.1.4.1.641.2.1.2.1.6.1';
	$TypeDef['Lexmark C'] = 3;
	$ModelDef['Lexmark C'] = 'Printer0001';

	$SerialNumberDef['Lexmark E'] = '.1.3.6.1.4.1.641.2.1.2.1.6.1';
	$TypeDef['Lexmark E'] = 3;
	$ModelDef['Lexmark E'] = 'Printer0001';

	$SerialNumberDef['Lexmark T'] = '.1.3.6.1.4.1.641.2.1.2.1.6.1';
	$TypeDef['Lexmark T'] = 3;
	$ModelDef['Lexmark T'] = 'Printer0010';

	$SerialNumberDef['Lexmark Optra'] = '';
	$TypeDef['Lexmark Optra'] = 3;
	$ModelDef['Lexmark Optra'] = 'Printer0001';

	#* Nashuatec
	$SerialNumberDef['NRG SP C'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['NRG SP C'] = 3;
	$ModelDef['NRG SP C'] = 'Printer0011';

	$SerialNumberDef['NRG SP'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['NRG SP'] = 3;
	$ModelDef['NRG SP'] = 'Printer0012';

	$SerialNumberDef['NRG'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['NRG'] = 3;
	$ModelDef['NRG'] = 'Printer0012';

	$SerialNumberDef['NRG MP C'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['NRG MP C'] = 3;
	$ModelDef['NRG MP C'] = 'Printer0002';

	$SerialNumberDef['NRG MP'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['NRG MP'] = 3;
	$ModelDef['NRG MP'] = 'Printer0013';

   #* OKI
	$SerialNumberDef['BASE Ethernet PrintServer: Attached to C5250n'] = '.1.3.6.1.4.1.2001.1.1.1.1.11.1.10.45.0';
	$TypeDef['BASE Ethernet PrintServer: Attached to C5250n'] = 3;
	$ModelDef['BASE Ethernet PrintServer: Attached to C5250n'] = 'Printer0014';

	$SerialNumberDef['BASE Ethernet PrintServer: Attached to C5600 Rev'] = '.1.3.6.1.4.1.2001.1.1.1.1.11.1.10.45.0';
	$TypeDef['BASE Ethernet PrintServer: Attached to C5600 Rev'] = 3;
	$ModelDef['BASE Ethernet PrintServer: Attached to C5600 Rev'] = 'Printer0015';

	$SerialNumberDef['OKI OkiLAN 8'] = '.1.3.6.1.4.1.2001.1.1.1.1.11.1.10.45.0';
	$TypeDef['OKI OkiLAN 8'] = 3;
	$ModelDef['OKI OkiLAN 8'] = 'Printer0015';

	#* Ricoh
	$SerialNumberDef['RICOH Aficio SP C'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Aficio SP C'] = 3;
	$ModelDef['RICOH Aficio SP C'] = 'Printer0011';

	$SerialNumberDef['RICOH Aficio MP C'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Aficio MP C'] = 3;
	$ModelDef['RICOH Aficio MP C'] = 'Printer0002';

	$SerialNumberDef['RICOH Aficio MP'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Aficio MP'] = 3;
	$ModelDef['RICOH Aficio MP'] = 'Printer0013';

	$SerialNumberDef['RICOH Aficio AP'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Aficio AP'] = 3;
	$ModelDef['RICOH Aficio AP'] = 'Printer0012';

	$SerialNumberDef['RICOH Aficio 3260'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Aficio 3260'] = 3;
	$ModelDef['RICOH Aficio 3260'] = 'Printer0012';

	$SerialNumberDef['RICOH Network Printer C model'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Network Printer C model'] = 3;
	$ModelDef['RICOH Network Printer C model'] = 'Printer0012';

	$SerialNumberDef['RICOH Network Printer D model'] = '.1.3.6.1.4.1.367.3.2.1.2.1.4.0';
	$TypeDef['RICOH Network Printer D model'] = 3;
	$ModelDef['RICOH Network Printer D model'] = 'Printer0012';

	#* Samsung
	$SerialNumberDef['SAMSUNG NETWORK PRINTER,ROM'] = '.1.3.6.1.4.1.236.11.5.1.1.1.4.0';
	$TypeDef['SAMSUNG NETWORK PRINTER,ROM'] = 3;
	$ModelDef['SAMSUNG NETWORK PRINTER,ROM'] = 'Printer0001';

	$SerialNumberDef['Samsung CLP-'] = '.1.3.6.1.4.1.236.11.5.1.1.1.4.0';
	$TypeDef['Samsung CLP-'] = 3;
	$ModelDef['Samsung CLP-'] = 'Printer0001';

	$SerialNumberDef['Samsung CLX'] = '.1.3.6.1.4.1.236.11.5.1.1.1.4.0';
	$TypeDef['Samsung CLX'] = 3;
   $ModelDef['Samsung CLX'] = 'Printer0001';

	$SerialNumberDef['Samsung ML'] = '.1.3.6.1.4.1.236.11.5.1.1.1.4.0';
	$TypeDef['Samsung ML'] = 3;
	$ModelDef['Samsung ML'] = 'Printer0001';

   #* Sharp
	$SerialNumberDef['SHARP MX'] = '';
	$TypeDef['SHARP MX'] = 3;
	$ModelDef['SHARP MX'] = 'Printer0001';

   #* Toshiba
   $SerialNumberDef['TOSHIBA e-STUDIO'] = '';
   $TypeDef['TOSHIBA e-STUDIO'] = 3;
   $ModelDef[' TOSHIBA e-STUDIO'] = 'Printer0001';

	#* TRENDnet
	# Print server
		$SerialNumberDef['TRENDnet TE100-P21 Print Server'] = '';
		$TypeDef['TRENDnet TE100-P21 Print Server'] = 3;
		$ModelDef['TRENDnet TE100-P21 Print Server'] = 'Printer0001';

	#* XeroX
	$SerialNumberDef['Xerox WorkCentre'] = '.1.3.6.1.4.1.253.8.53.3.2.1.3.1';
	$TypeDef['Xerox WorkCentre'] = 3;
	$ModelDef['Xerox WorkCentre'] = 'Printer0001';

   $SerialNumberDef['Xerox 41'] = '.1.3.6.1.4.1.253.8.53.3.2.1.3.1';
   $TypeDef['Xerox 41'] = 3;
   $ModelDef['Xerox 41'] = 'Printer0001';

   $SerialNumberDef['Xerox Phaser'] = '';
   $TypeDef['Xerox Phaser'] = 3;
   $ModelDef['Xerox Phaser'] = 'Printer0001';

###########################################
################# Phones ##################
###########################################

	#* Avaya
	$SerialNumberDef['Avaya Phone'] = '.1.3.6.1.2.1.1.5.0';
	$TypeDef['Avaya Phone'] = 23;
	$ModelDef['Avaya Phone'] = '';

	#* Thomson
	$SerialNumberDef['ST2030 SIP'] = '';
	$TypeDef['ST2030 SIP'] = 23;
	$ModelDef['ST2030 SIP'] = '';

###########################################
################ Computers ################
###########################################

	#* Itium
	$SerialNumberDef['ITIUM 2820'] = '';
	$TypeDef['ITIUM 2820'] = 1;
	$ModelDef['ITIUM 2820'] = '';

	$SerialNumberDef['ITIUM 4030'] = '';
	$TypeDef['ITIUM 4030'] = 1;
	$ModelDef['ITIUM 4030'] = '';

	#* Wyse
	$SerialNumberDef['Wyse 5150SE'] = '.1.3.6.1.4.1.714.1.2.5.3.5.0';
	$TypeDef['Wyse 5150SE'] = 1;
	$ModelDef['Wyse 5150SE'] = '';

	#* Windows 2000 SP4
	$SerialNumberDef['Service Pack 4 2000 Professional x86 Family'] = '';
	$TypeDef['Service Pack 4 2000 Professional x86 Family'] = 1;
	$ModelDef['Service Pack 4 2000 Professional x86 Family'] = '';

	#* Windows XP - 2003
	$SerialNumberDef['AT/AT COMPATIBLE - Software: Windows Version 5.2 (Build 3790 Multiprocessor Free)'] = '';
	$TypeDef['AT/AT COMPATIBLE - Software: Windows Version 5.2 (Build 3790 Multiprocessor Free)'] = 1;
	$ModelDef['AT/AT COMPATIBLE - Software: Windows Version 5.2 (Build 3790 Multiprocessor Free)'] = '';

	#* FreeBSD
	$SerialNumberDef['[0-9].[0-9]-RELEASE FreeBSD [0-9].[0-9]-RELEASE'] = '';
	$TypeDef['[0-9].[0-9]-RELEASE FreeBSD [0-9].[0-9]-RELEASE'] = 1;
	$ModelDef['[0-9].[0-9]-RELEASE FreeBSD [0-9].[0-9]-RELEASE'] = '';


###########################################
################ Onduleurs ################
###########################################

	#* MGE
	$SerialNumberDef['Evolution 500'] = '';
	$TypeDef['Evolution 500'] = 5;
	$ModelDef['Evolution 500'] = '';


?>