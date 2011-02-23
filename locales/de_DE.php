<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file: Mathieu SIMON
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory SNMP";
$version="2.3.0-1";

$LANG['plugin_fusinvsnmp']['title'][0]="$title";
$LANG['plugin_fusinvsnmp']['title'][1]="SNMP information";
$LANG['plugin_fusinvsnmp']['title'][2]="Verbindungs Historie";
$LANG['plugin_fusinvsnmp']['title'][5]="FusionInventory's locks";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";

$LANG['plugin_fusinvsnmp']['config'][3] = "Inventory";
$LANG['plugin_fusinvsnmp']['config'][4] = "Devices discovery";
$LANG['plugin_fusinvsnmp']['config'][8] = "Never";
$LANG['plugin_fusinvsnmp']['config'][9] = "Always";

$LANG['plugin_fusinvsnmp']['profile'][2]="Configuration";
$LANG['plugin_fusinvsnmp']['profile'][3]="SNMP authentication";
$LANG['plugin_fusinvsnmp']['profile'][4]="IP Range";
$LANG['plugin_fusinvsnmp']['profile'][5]="Network equipment SNMP";
$LANG['plugin_fusinvsnmp']['profile'][6]="Printer SNMP";
$LANG['plugin_fusinvsnmp']['profile'][7]="SNMP model";

$LANG['plugin_fusinvsnmp']['setup'][17]="Plugin ".$title." need plugin FusionInventory installed before install.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Plugin ".$title." need plugin FusionInventory activated before uninstall.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Converting history port";
$LANG['plugin_fusinvsnmp']['setup'][20]="Moving creation connections history";
$LANG['plugin_fusinvsnmp']['setup'][21]="Moving deleted connections history";

$LANG['plugin_fusinvsnmp']['functionalities'][3]="SNMP";
$LANG['plugin_fusinvsnmp']['functionalities'][43]="SNMP Authentifizierung";

$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";
$LANG['plugin_fusinvsnmp']['snmp'][12]="Uptime";
$LANG['plugin_fusinvsnmp']['snmp'][13]="CPU Verwendung (in %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Speicher Verwendung (in %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Anschlu&szlig; Aufstellung";
$LANG['plugin_fusinvsnmp']['snmp'][41]="";
$LANG['plugin_fusinvsnmp']['snmp'][42]="MTU";
$LANG['plugin_fusinvsnmp']['snmp'][43]="Geschwindigkeit";
$LANG['plugin_fusinvsnmp']['snmp'][44]="Interner Zustand";
$LANG['plugin_fusinvsnmp']['snmp'][45]="Letzte &Auml;nderung";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Anzahl empfangener Bytes";
$LANG['plugin_fusinvsnmp']['snmp'][47]="Anzahl der Input Fehler";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Anzahl gesendeter Bytes";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Anzahl von Fehlern beim Empfang";
$LANG['plugin_fusinvsnmp']['snmp'][50]="Verbindung";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Duplex";
$LANG['plugin_fusinvsnmp']['snmp'][52]="Datum des letzen FusionInventory Inventarisierung";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Letzte Inventarisierung";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Datas not available";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Gemeinschaft";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Benutzer";
$LANG['plugin_fusinvsnmp']['snmpauth'][3]="Authentifizierungsmodell";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Verschl&uuml;sselungsprotokoll f&uuml;r die Authentifizierung ";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Passwort";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Verschl&uuml;sselungsprotokoll f&uuml;r Daten (schreiben)";
$LANG['plugin_fusinvsnmp']['snmpauth'][7]="Passwort (schreiben)";

$LANG['plugin_fusinvsnmp']['errors'][50]="GLPI version not compatible need 0.78";

$LANG['plugin_fusinvsnmp']['history'][0] = "Alt";
$LANG['plugin_fusinvsnmp']['history'][1] = "Neu";
$LANG['plugin_fusinvsnmp']['history'][2] = "Trennen";
$LANG['plugin_fusinvsnmp']['history'][3] = "Verbindung";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Historie und Statistik der Druckerzähler";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Gedruckte Seiten gesamt";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Seiten / Tag";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="History meter Drucker";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Datum";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Meter";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Time unit";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Add a printer";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Remove a printer";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="day";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="week";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="month";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="year";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="SNMP zuordnen";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="SNMP Authentifizierung zuordnen";

$LANG['plugin_fusinvsnmp']['model_info'][2]="SNMP Version";
$LANG['plugin_fusinvsnmp']['model_info'][3]="SNMP Authentifizierung";
$LANG['plugin_fusinvsnmp']['model_info'][4]="SNMP Modelle";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Bearbeite SNMP Modell";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Erstelle SNMP Modell";
$LANG['plugin_fusinvsnmp']["model_info"][8]="Model already exists";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import vollst&auml;ndig Abgeschlossen";
$LANG['plugin_fusinvsnmp']['model_info'][10]="SNMP Modell Import";
$LANG['plugin_fusinvsnmp']['model_info'][11]="Aktivierung";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Key for model discovery";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Lade richtiges Modell";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Lade richtiges SNMP Modell";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Mass import of models";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Mass import of models in folder plugins/fusioninventory/models/";

$LANG['plugin_fusinvsnmp']['mib'][1]="MIB Bezeichnung";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objekt";
$LANG['plugin_fusinvsnmp']['mib'][3]="oid";
$LANG['plugin_fusinvsnmp']['mib'][4]="F&uuml;ge eine oid hinzu...";
$LANG['plugin_fusinvsnmp']['mib'][5]="oid Liste";
$LANG['plugin_fusinvsnmp']['mib'][6]="Port Counters";
$LANG['plugin_fusinvsnmp']['mib'][7]="Dynamische ports (.x)";
$LANG['plugin_fusinvsnmp']['mib'][8]="VerlTintete Felder";
$LANG['plugin_fusinvsnmp']['mib'][9]="Vlan";

$LANG['plugin_fusinvsnmp']['processes'][37]="IP total";

$LANG['plugin_fusinvsnmp']['state'][4]="Starting date";
$LANG['plugin_fusinvsnmp']['state'][5]="Ending date";
$LANG['plugin_fusinvsnmp']['state'][6]="Total discovery devices";
$LANG['plugin_fusinvsnmp']['state'][7]="Total in error";

$LANG['plugin_fusinvsnmp']['mapping'][1]="Netzwerk > Standort";
$LANG['plugin_fusinvsnmp']['mapping'][2]="Netzwerk > Firmware";
$LANG['plugin_fusinvsnmp']['mapping'][3]="Netzwerk > Uptime";
$LANG['plugin_fusinvsnmp']['mapping'][4]="Netzwerk > Port > MTU";
$LANG['plugin_fusinvsnmp']['mapping'][5]="Netzwerk > Port > Geschwindigkeit";
$LANG['plugin_fusinvsnmp']['mapping'][6]="Netzwerk > Port > Interner Zustand";
$LANG['plugin_fusinvsnmp']['mapping'][7]="Netzwerk > Ports > Letzte &Auml;nderung";
$LANG['plugin_fusinvsnmp']['mapping'][8]="Netzwerk > Port > Anzahl eingegangene Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][9]="Netzwerk > Port > Anzahl ausgehende Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][10]="Netzwerk > Port > Anzahl Input Fehler";
$LANG['plugin_fusinvsnmp']['mapping'][11]="Netzwerk > Port > Anzahl Fehler Ausgehend";
$LANG['plugin_fusinvsnmp']['mapping'][12]="Netzwerk > CPU Auslastung";
$LANG['plugin_fusinvsnmp']['mapping'][13]="Netzwerk > Seriennummer";
$LANG['plugin_fusinvsnmp']['mapping'][14]="Netzwerk > Port > Verbingungszustand";
$LANG['plugin_fusinvsnmp']['mapping'][15]="Netzwerk > Port > MAC Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][16]="Netzwerk > Port > Name";
$LANG['plugin_fusinvsnmp']['mapping'][17]="Netzwerk > Modell";
$LANG['plugin_fusinvsnmp']['mapping'][18]="Netzwerk > Ports > Typ";
$LANG['plugin_fusinvsnmp']['mapping'][19]="Netzwerk > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][20]="Netzwerk > Name";
$LANG['plugin_fusinvsnmp']['mapping'][21]="Netzwerk > Gesamter Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][22]="Netzwerk > Freier Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][23]="Netzwerk > Port > Port Bezeichnung";
$LANG['plugin_fusinvsnmp']['mapping'][24]="Drucker > Name";
$LANG['plugin_fusinvsnmp']['mapping'][25]="Drucker > Modell";
$LANG['plugin_fusinvsnmp']['mapping'][26]="Drucker > Gesamter Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][27]="Drucker > Seriennummer";
$LANG['plugin_fusinvsnmp']['mapping'][28]="Drucker > Messung > Gesamtanzahl gedruckter Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][29]="Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][30]="Drucker > Messung > Gesamtanzahl gedruckter Farbseiten";
$LANG['plugin_fusinvsnmp']['mapping'][31]="Drucker > Messung > Anzahl gedruckter Schwarz/Wei&szlig; Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][32]="Drucker > Messung > Anzahl gedruckter Farbseiten";
$LANG['plugin_fusinvsnmp']['mapping'][33]="Netzwerk > Port > Duplex Typ";
$LANG['plugin_fusinvsnmp']['mapping'][34]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="Drucker > Verbrauchsmaterial > Schwarze Photo Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="Drucker > Verbrauchsmaterial > Cyan Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="Drucker > Verbrauchsmaterial > Magenta Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="Drucker > Verbrauchsmaterial > Leicht Cyan Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][40]="Drucker > Verbrauchsmaterial > Leicht Magenta Kartusche (%)";
$LANG['plugin_fusinvsnmp']['mapping'][41]="Drucker > Verbrauchsmaterial > Photoleiter (%)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="Drucker > Verbrauchsmaterial > Photoleiter Schwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][43]="Drucker > Verbrauchsmaterial > Photoleiter Farbe (%)";
$LANG['plugin_fusinvsnmp']['mapping'][44]="Drucker > Verbrauchsmaterial > Photoleiter Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="Drucker > Verbrauchsmaterial > Photoleiter Gelb (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="Drucker > Verbrauchsmaterial > Photoleiter Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="Drucker > Verbrauchsmaterial > Fixiereinheit Schwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="Drucker > Verbrauchsmaterial > Fixiereinheit Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="Drucker > Verbrauchsmaterial > Fixiereinheit Gelb (%)";
$LANG['plugin_fusinvsnmp']['mapping'][50]="Drucker > Verbrauchsmaterial > Fixiereinheit Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="Drucker > Verbrauchsmaterial > Abfalleimer (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="Drucker > Verbrauchsmaterial > vier (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="Drucker > Verbrauchsmaterial > Reinigungsmodul (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="Drucker > Messung > Anzahl der gedruckten Duplex Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][55]="Drucker > Messung > Anzahl der gescannten Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][56]="Drucker > Standort";
$LANG['plugin_fusinvsnmp']['mapping'][57]="Drucker > Port > Name";
$LANG['plugin_fusinvsnmp']['mapping'][58]="Drucker > Port > MAC Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][59]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][60]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="Drucker > Verbrauchsmaterial > Cyan Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="Drucker > Verbrauchsmaterial > Cyan Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="Drucker > Verbrauchsmaterial > Aagenta Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="Drucker > Verbrauchsmaterial > Aagenta Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="Drucker > Verbrauchsmaterial > Leichtes Cyan Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="Drucker > Verbrauchsmaterial > Leichtes Cyan Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="Drucker > Verbrauchsmaterial > Leichtes Magenta Kartusche (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][70]="Drucker > Verbrauchsmaterial > Leichtes Magenta Kartusche (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="Drucker > Verbrauchsmaterial > Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="Drucker > Verbrauchsmaterial > Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="Drucker > Verbrauchsmaterial > Schwarzer Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="Drucker > Verbrauchsmaterial > Schwarzer Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="Drucker > Verbrauchsmaterial > Farbiger Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="Drucker > Verbrauchsmaterial > Farbiger Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="Drucker > Verbrauchsmaterial > Cyan Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="Drucker > Verbrauchsmaterial > Cyan Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="Drucker > Verbrauchsmaterial > Gelber Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][80]="Drucker > Verbrauchsmaterial > Gelber Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="Drucker > Verbrauchsmaterial > Magenta Photoleiter (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="Drucker > Verbrauchsmaterial > Magenta Photoleiter (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="Drucker > Verbrauchsmaterial > Schwarze Fixiereinheit (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="Drucker > Verbrauchsmaterial > Schwarze Fixiereinheit (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="Drucker > Verbrauchsmaterial > Cyan Fixiereinheit (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="Drucker > Verbrauchsmaterial > Cyan Fixiereinheit (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="Drucker > Verbrauchsmaterial > Gelbe Fixiereinheit (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="Drucker > Verbrauchsmaterial > Gelbe Fixiereinheit (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="Drucker > Verbrauchsmaterial > Magenta Fixiereinheit (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][90]="Drucker > Verbrauchsmaterial > Magenta Fixiereinheit (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="Drucker > Verbrauchsmaterial > Abfalleimer (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="Drucker > Verbrauchsmaterial > Abfalleimer (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="Drucker > Verbrauchsmaterial > vier (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="Drucker > Verbrauchsmaterial > vier (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="Drucker > Verbrauchsmaterial > Reinigungsmodul (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="Drucker > Verbrauchsmaterial > Reinigungsmodul (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="Drucker > port > Typ";
$LANG['plugin_fusinvsnmp']['mapping'][98]="Drucker > Verbrauchsmaterial > Wartungsmodul (Maximal)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="Drucker > Verbrauchsmaterial > Wartungsmodul (Verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][400]="Drucker > Verbrauchsmaterial > Wartungsmodul (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="Netzwerk > CPU Benutzer";
$LANG['plugin_fusinvsnmp']['mapping'][402]="Netzwerk > CPU System";
$LANG['plugin_fusinvsnmp']['mapping'][403]="Netzwerk > Kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][404]="Netzwerk > Kommentar";
$LANG['plugin_fusinvsnmp']['mapping'][405]="Drucker > Kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][406]="Drucker > Kommentar";
$LANG['plugin_fusinvsnmp']['mapping'][407]="Drucker > Port > IP Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][408]="Netzwerk > Port > Nummerischer Index";
$LANG['plugin_fusinvsnmp']['mapping'][409]="Netzwerk > Adresse CDP";
$LANG['plugin_fusinvsnmp']['mapping'][410]="Netzwerk > Port CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="Netzwerk > Port > trunk/tagged";
$LANG['plugin_fusinvsnmp']['mapping'][412]="Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="Netzwerk > Instanzen des Ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der id des Ports (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="Drucker > Port > Indexnummer";
$LANG['plugin_fusinvsnmp']['mapping'][417]="Netzwerk > MAC Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][418]="Drucker > Inventarnummer";
$LANG['plugin_fusinvsnmp']['mapping'][419]="Netzwerk > Inventarnummer";
$LANG['plugin_fusinvsnmp']['mapping'][420]="Drucker > Hersteller";
$LANG['plugin_fusinvsnmp']['mapping'][421]="Netzwerk > IP Adressen";
$LANG['plugin_fusinvsnmp']['mapping'][422]="Netzwerk > portVlanIndex";
$LANG['plugin_fusinvsnmp']['mapping'][423]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="networking > port > vlan";


$LANG['plugin_fusinvsnmp']['mapping'][101]="";
$LANG['plugin_fusinvsnmp']['mapping'][102]="";
$LANG['plugin_fusinvsnmp']['mapping'][103]="";
$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Geschwindigkeit";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Interer Status";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Letze &Auml;nderung";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Anzahl empfangener Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Anzahl ausgehender Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Anzahl input Fehler";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Anzahl output Fehler";
$LANG['plugin_fusinvsnmp']['mapping'][112]="CPU Verwendung";
$LANG['plugin_fusinvsnmp']['mapping'][113]="";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Verbindung";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Interne MAC Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Name";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Model";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Typ";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Gesamtanzahl gedruckte Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Anzahl gedruckter Schwarz/Wei&szlig; Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Anzahl gedruckter Farbeseiten";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Anzahl gedruckter Graustufenseiten";
$LANG['plugin_fusinvsnmp']['mapping'][132]="Anzahl gedruckter Farbseiten";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Schwarze Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Photoschwarz Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Cyan Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Gelbe Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Magenta Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Leichtes Cyan Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Leichtes Magenta Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Black Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Farbiger Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Cyan Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Gelber Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Magenta Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Schwarze Fixiereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Cyan Fixiereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Gelbe Fixiereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Magenta Fixiereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Abfalleimer";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Vier";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Reinigungsmodul";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Anzahl gedruckter Duplexseiten";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Anzahl gescannter Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Wartungsmodul";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Black toner";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Cyan toner";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Magenta toner";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Yellow toner";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Black drum";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Cyan drum";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Magenta drum";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Yellow drum";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Many informations grouped";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Black toner Max2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Black toner Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Black toner Restant";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Cyan toner Max";
$LANG['plugin_fusinvsnmp']['mapping'][170]="Cyan toner Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][171]="Cyan toner Restant";
$LANG['plugin_fusinvsnmp']['mapping'][172]="Magenta toner Max";
$LANG['plugin_fusinvsnmp']['mapping'][173]="Magenta toner Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][174]="Magenta toner Restant";
$LANG['plugin_fusinvsnmp']['mapping'][175]="Yellow toner Max";
$LANG['plugin_fusinvsnmp']['mapping'][176]="Yellow toner Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][177]="Yellow toner Restant";
$LANG['plugin_fusinvsnmp']['mapping'][178]="Black drum Max";
$LANG['plugin_fusinvsnmp']['mapping'][179]="Black drum Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][180]="Black drum Restant";
$LANG['plugin_fusinvsnmp']['mapping'][181]="Cyan drum Max";
$LANG['plugin_fusinvsnmp']['mapping'][182]="Cyan drum Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][183]="Cyan drumRestant";
$LANG['plugin_fusinvsnmp']['mapping'][184]="Magenta drum Max";
$LANG['plugin_fusinvsnmp']['mapping'][185]="Magenta drum Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][186]="Magenta drum Restant";
$LANG['plugin_fusinvsnmp']['mapping'][187]="Yellow drum Max";
$LANG['plugin_fusinvsnmp']['mapping'][188]="Yellow drum Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][189]="Yellow drum Restant";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Waste bin Max";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Waste bin Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Waste bin Restant";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Maintenance kit Max";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Maintenance kit Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Maintenance kit Restant";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Grey ink cartridge";

$LANG['plugin_fusinvsnmp']['mapping'][1423]="Gesamtanzahl gedruckter Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Gesamtanzahl farbig gedruckter Seiten (Druck)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Gesamtanzahl gedruckter Seiten (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Gesamtanzahl farbig gedruckter Seiten (Kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Gesamtanzahl gedruckter Seiten (Fax)";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Total number of large printed pages";

$LANG['plugin_fusinvsnmp']['menu'][2]="IP Bereich Konfiguration";
$LANG['plugin_fusinvsnmp']['menu'][5]="Switchs ports history";
$LANG['plugin_fusinvsnmp']['menu'][6]="Unused switchs ports";
$LANG['plugin_fusinvsnmp']['menu'][9]="Discovery status";
$LANG['plugin_fusinvsnmp']['menu'][10]="Network inventory status";

$LANG['plugin_fusinvsnmp']['buttons'][0]="Entdecke";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Anzahl importierter Ger&auml;te";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Number of devices not imported because type non defined";

$LANG['plugin_fusinvsnmp']['iprange'][0]="Start des IP Bereichs";
$LANG['plugin_fusinvsnmp']['iprange'][1]="Ende des IP Bereichs";
$LANG['plugin_fusinvsnmp']['iprange'][2]="IP Bereiche";
$LANG['plugin_fusinvsnmp']['iprange'][3]="Abfrage";
$LANG['plugin_fusinvsnmp']['iprange'][7]="Bad IP";

$LANG['plugin_fusinvsnmp']['agents'][24]="SNMP - Threads";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agent(s)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Netdiscovery module version";
$LANG['plugin_fusinvsnmp']['agents'][27]="Snmpquery module version";

$LANG['plugin_fusinvsnmp']['task'][15]="Permanent task - Discovery";
$LANG['plugin_fusinvsnmp']['task'][16]="Permanent task - Inventory";
$LANG['plugin_fusinvsnmp']['task'][17]="Communication type";
$LANG['plugin_fusinvsnmp']['task'][18]="Create task easily";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Automatic creation of models";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Generate discovery file";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Delete models non used";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Export all models";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Re-create models comments";

$LANG['plugin_fusinvsnmp']['stats'][0]="Total counter";
$LANG['plugin_fusinvsnmp']['stats'][1]="pages per day";
$LANG['plugin_fusinvsnmp']['stats'][2]="Display";

?>