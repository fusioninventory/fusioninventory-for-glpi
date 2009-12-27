<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$title="Tracker";
$version="2.1.3";

$LANG['plugin_tracker']["title"][0]="$title";
$LANG['plugin_tracker']["title"][1]="SNMP information";
$LANG['plugin_tracker']["title"][2]="Verbindungs Historie";
$LANG['plugin_tracker']["title"][3]="[Trk] Fehler";
$LANG['plugin_tracker']["title"][4]="[Trk] Cron";

$LANG['plugin_tracker']["profile"][0]="Rechte Management";
$LANG['plugin_tracker']["profile"][1]="$title"; //interface

$LANG['plugin_tracker']["profile"][10]="Konfigurierte Profile";
$LANG['plugin_tracker']["profile"][11]="Computer Historie";
$LANG['plugin_tracker']["profile"][12]="Drucker Historie";
$LANG['plugin_tracker']["profile"][13]="Drucker Information";
$LANG['plugin_tracker']["profile"][14]="Netzwerk Information";
$LANG['plugin_tracker']["profile"][15]="Fehler";

$LANG['plugin_tracker']["profile"][16]="SNMP Netzwerk";
$LANG['plugin_tracker']["profile"][17]="SNMP Geräte";
$LANG['plugin_tracker']["profile"][18]="SNMP Drucker";
$LANG['plugin_tracker']["profile"][19]="SNMP Modelle";
$LANG['plugin_tracker']["profile"][20]="SNMP Authentifizierung";
$LANG['plugin_tracker']["profile"][21]="Script Information";
$LANG['plugin_tracker']["profile"][22]="Netzwerk Entdeckung";
$LANG['plugin_tracker']["profile"][23]="Grundelegende Konfiguration";
$LANG['plugin_tracker']["profile"][24]="SNMP Modell";
$LANG['plugin_tracker']["profile"][25]="IP Bereich";
$LANG['plugin_tracker']["profile"][26]="Agent";
$LANG['plugin_tracker']["profile"][27]="Agent Information";
$LANG['plugin_tracker']["profile"][28]="Bericht";


$LANG['plugin_tracker']["setup"][2]="Danke das Sie alles in die Wurzel Entität gesteckt haben (alles sehen)";
$LANG['plugin_tracker']["setup"][3]="Plugin Konfiguration".$title;
$LANG['plugin_tracker']["setup"][4]="Install plugin $title $version";
$LANG['plugin_tracker']["setup"][5]="Update plugin $title auf version $version";
$LANG['plugin_tracker']["setup"][6]="Uninstall plugin $title $version";
$LANG['plugin_tracker']["setup"][8]="Achtung, die deinstallation dieses Plugins ist entg&uuml;ltig.<br> Sie werden alle Daten verlieren.";
$LANG['plugin_tracker']["setup"][11]="Anweisungen";
$LANG['plugin_tracker']["setup"][12]="FAQ";
$LANG['plugin_tracker']["setup"][13]="PHP Module &uuml;berpr&uuml;fen";
$LANG['plugin_tracker']["setup"][14]="PHP SNMP Erweiterung nicht geladen";
$LANG['plugin_tracker']["setup"][15]="PHP/PECL Laufzeiterweiterung nicht geladen";
$LANG['plugin_tracker']["setup"][16]="Dokumentation";

$LANG['plugin_tracker']["functionalities"][0]="Funktionen";
$LANG['plugin_tracker']["functionalities"][1]="Funktionen Hinzuf&uuml;gen/L&ouml;schen";
$LANG['plugin_tracker']["functionalities"][2]="Grundelegende Konfiguration";
$LANG['plugin_tracker']["functionalities"][3]="SNMP";
$LANG['plugin_tracker']["functionalities"][4]="Verbindung";
$LANG['plugin_tracker']["functionalities"][5]="Server script";
$LANG['plugin_tracker']["functionalities"][6]="Legende";

$LANG['plugin_tracker']["functionalities"][10]="Aktiviere Historie";
$LANG['plugin_tracker']["functionalities"][11]="Aktiviere Verbindungsmodul";
$LANG['plugin_tracker']["functionalities"][12]="Aktiviere SNMP Netzwerkmodul";
$LANG['plugin_tracker']["functionalities"][13]="Aktiviere SNMP Ger&auml;temodul";
$LANG['plugin_tracker']["functionalities"][14]="Aktiviere SNMP Telefonmodul";
$LANG['plugin_tracker']["functionalities"][15]="Aktiviere SNMP Druckermodul";
$LANG['plugin_tracker']["functionalities"][16]="SNMP Authentifizierung ";
$LANG['plugin_tracker']["functionalities"][17]="Datenbank";
$LANG['plugin_tracker']["functionalities"][18]="Dateien";
$LANG['plugin_tracker']["functionalities"][19]="Bitte Konfigurieren Sie die SNMP Authentifizierung im Setup des Plugin";
$LANG['plugin_tracker']["functionalities"][20]="Zustand des Aktiven Geräts";
$LANG['plugin_tracker']["functionalities"][21]="Aufbewahrung von alten Verbindungsdaten zwischen den Ger&auml;ten in Tagen (0 = Endlos)";
$LANG['plugin_tracker']["functionalities"][22]="Aufbewahrung von alten Status&auml;nderungen an Steckverbindungen in Tagen (0 = Endlos)";
$LANG['plugin_tracker']["functionalities"][23]="Aubewahrung von alten unbekannten MAC Adressen int Tagen (0 = Endlos)";
$LANG['plugin_tracker']["functionalities"][24]="Aufbewahrung von alten SNMP Fehlern in Tagen (0 = infinity))";
$LANG['plugin_tracker']["functionalities"][25]="Aufbewahrung der alten Laufzeitinformationen der Scripte in Tagen (0 = Endlos)";
$LANG['plugin_tracker']["functionalities"][26]="GLPI URL f&uuml;r den Agent";
$LANG['plugin_tracker']["functionalities"][27]="Nur SSL f&uuml;r den Agent";
$LANG['plugin_tracker']["functionalities"][28]="Konfiguration der Historie";
$LANG['plugin_tracker']["functionalities"][29]="Liste der Felder f&uuml;r die Historie";

$LANG['plugin_tracker']["functionalities"][30]="Status der aktiven Ger&auml;te";
$LANG['plugin_tracker']["functionalities"][31]="Verwaltung von Patronen und Lagerbestand";
$LANG['plugin_tracker']["functionalities"][36]="Frequency of meter reading";

$LANG['plugin_tracker']["functionalities"][40]="Konfiguration";
$LANG['plugin_tracker']["functionalities"][41]="Status der aktiven Ger&auml;te";
$LANG['plugin_tracker']["functionalities"][42]="Switch";
$LANG['plugin_tracker']["functionalities"][43]="SNMP Authentifizierung";

$LANG['plugin_tracker']["functionalities"][50]="Anzahl der gleichzeitigen Prozesse f&uuml;r die Netzwerkentdeckung";
$LANG['plugin_tracker']["functionalities"][51]="Anzahl der gleichzeitigen Prozesse f&uuml;r SNMP Anfragen";
$LANG['plugin_tracker']["functionalities"][52]="Aktivierung der Log Dateien";
$LANG['plugin_tracker']["functionalities"][53]="Anzahl der gelichzeitigen Prozesse des Server Scripts";

$LANG['plugin_tracker']["functionalities"][60]="Lösche Historie";

$LANG['plugin_tracker']["snmp"][0]="SNMP Informationen der Ger&auml;te";
$LANG['plugin_tracker']["snmp"][1]="Grundlage";
$LANG['plugin_tracker']["snmp"][2]="Verkabelung";
$LANG['plugin_tracker']["snmp"][2]="SNMP Daten";

$LANG['plugin_tracker']["snmp"][11]="Zus&auml;tzliche Informationen";
$LANG['plugin_tracker']["snmp"][12]="Uptime";
$LANG['plugin_tracker']["snmp"][13]="CPU Verwendung (in %)";
$LANG['plugin_tracker']["snmp"][14]="Speicher Verwendung (in %)";

$LANG['plugin_tracker']["snmp"][31]="Keine SNMP Informationen erhalten: Dies ist kein Switch";
$LANG['plugin_tracker']["snmp"][32]="Keine SNMP Informationen erhalten: Hardware inaktiv";
$LANG['plugin_tracker']["snmp"][33]="Keine SNMP Informationen erhalten: IP in der Basis nicht spezifiziert";
$LANG['plugin_tracker']["snmp"][34]="Der Switch ist an einer Maschine angeschlossen welche nicht eingetragen ist";

$LANG['plugin_tracker']["snmp"][40]="Anschlu&szlig; Aufstellung";
$LANG['plugin_tracker']["snmp"][41]="";
$LANG['plugin_tracker']["snmp"][42]="MTU";
$LANG['plugin_tracker']["snmp"][43]="Geschwindigkeit";
$LANG['plugin_tracker']["snmp"][44]="Interner Zustand";
$LANG['plugin_tracker']["snmp"][45]="Letzte &Auml;nderung";
$LANG['plugin_tracker']["snmp"][46]="Anzahl empfangener Bytes";
$LANG['plugin_tracker']["snmp"][47]="Anzahl der Input Fehler";
$LANG['plugin_tracker']["snmp"][48]="Anzahl gesendeter Bytes";
$LANG['plugin_tracker']["snmp"][49]="Anzahl von Fehlern beim Empfang";
$LANG['plugin_tracker']["snmp"][50]="Verbindung";
$LANG['plugin_tracker']["snmp"][51]="Duplex";
$LANG['plugin_tracker']["snmp"][52]="Datum des letzen TRACKER Inventarisierung";
$LANG['plugin_tracker']["snmp"][53]="Letzte Inventarisierung";

$LANG['plugin_tracker']["snmpauth"][1]="Gemeinschaft";
$LANG['plugin_tracker']["snmpauth"][2]="Benutzer";
$LANG['plugin_tracker']["snmpauth"][3]="Authentifizierungsmodell";
$LANG['plugin_tracker']["snmpauth"][4]="Verschl&uuml;sselungsprotokoll f&uuml;r die Authentifizierung ";
$LANG['plugin_tracker']["snmpauth"][5]="Passwort";
$LANG['plugin_tracker']["snmpauth"][6]="Verschl&uuml;sselungsprotokoll f&uuml;r Daten (schreiben)";
$LANG['plugin_tracker']["snmpauth"][7]="Passwort (schreiben)";

$LANG['plugin_tracker']["cron"][0]="Automatic reading meter";
$LANG['plugin_tracker']["cron"][1]="Aktiviere Eintrag";
$LANG['plugin_tracker']["cron"][2]="";
$LANG['plugin_tracker']["cron"][3]="Standard";

$LANG['plugin_tracker']["errors"][0]="Fehler";
$LANG['plugin_tracker']["errors"][1]="IP";
$LANG['plugin_tracker']["errors"][2]="Beschreibung";
$LANG['plugin_tracker']["errors"][3]="Datum des ersten Fehlers";
$LANG['plugin_tracker']["errors"][4]="Datum des letzten Fehlers";

$LANG['plugin_tracker']["errors"][10]="Inconsistent with the basic GLPI";
$LANG['plugin_tracker']["errors"][11]="Position unbekannt";
$LANG['plugin_tracker']["errors"][12]="Unbekannte IP";

$LANG['plugin_tracker']["errors"][20]="SNMP Fehler";
$LANG['plugin_tracker']["errors"][21]="Keine SNMP Informationen erhalten";

$LANG['plugin_tracker']["errors"][30]="Verkabelungsfehler";
$LANG['plugin_tracker']["errors"][31]="Verkabelungsproblem";

$LANG['plugin_tracker']["errors"][50]="GLPI version not compatible need 0.72.1";

$LANG['plugin_tracker']["errors"][101]="Timeout";
$LANG['plugin_tracker']["errors"][102]="Kein SNMP Modell zugeordnet";
$LANG['plugin_tracker']["errors"][103]="Keine SNMP Authentifzierung zugeordnet";

$LANG['plugin_tracker']["history"][0] = "Alt";
$LANG['plugin_tracker']["history"][1] = "Neu";
$LANG['plugin_tracker']["history"][2] = "Trennen";
$LANG['plugin_tracker']["history"][3] = "Verbindung";

$LANG['plugin_tracker']["prt_history"][0]="Historie und Statistik der Druckerzähler";

$LANG['plugin_tracker']["prt_history"][10]="Druckerzähler Statistik";
$LANG['plugin_tracker']["prt_history"][11]="Tag(e)";
$LANG['plugin_tracker']["prt_history"][12]="Gedruckte Seiten gesamt";
$LANG['plugin_tracker']["prt_history"][13]="Seiten / Tag";

$LANG['plugin_tracker']["prt_history"][20]="History meter Drucker";
$LANG['plugin_tracker']["prt_history"][21]="Datum";
$LANG['plugin_tracker']["prt_history"][22]="Meter";


$LANG['plugin_tracker']["cpt_history"][0]="Historie Sitzungen";
$LANG['plugin_tracker']["cpt_history"][1]="Kontakt";
$LANG['plugin_tracker']["cpt_history"][2]="Computer";
$LANG['plugin_tracker']["cpt_history"][3]="Benutzer";
$LANG['plugin_tracker']["cpt_history"][4]="Zustand";
$LANG['plugin_tracker']["cpt_history"][5]="Datum";


$LANG['plugin_tracker']["type"][1]="Computer";
$LANG['plugin_tracker']["type"][2]="Switch";
$LANG['plugin_tracker']["type"][3]="Drucker";

$LANG['plugin_tracker']["rules"][1]="Regeln";

$LANG['plugin_tracker']["massiveaction"][1]="SNMP zuordnen";
$LANG['plugin_tracker']["massiveaction"][2]="SNMP Authentifizierung zuordnen";

$LANG['plugin_tracker']["model_info"][1]="SNMP Information";
$LANG['plugin_tracker']["model_info"][2]="SNMP Version";
$LANG['plugin_tracker']["model_info"][3]="SNMP Authentifizierung";
$LANG['plugin_tracker']["model_info"][4]="SNMP Modelle";
$LANG['plugin_tracker']["model_info"][5]="MIB Verwaltung";
$LANG['plugin_tracker']["model_info"][6]="Bearbeite SNMP Modell";
$LANG['plugin_tracker']["model_info"][7]="Erstelle SNMP Modell";
$LANG['plugin_tracker']["model_info"][8]="Modell gibt es schon: Nicht importiert";
$LANG['plugin_tracker']["model_info"][9]="Import vollst&auml;ndig Abgeschlossen";
$LANG['plugin_tracker']["model_info"][10]="SNMP Modell Import";
$LANG['plugin_tracker']["model_info"][11]="Aktivierung";
$LANG['plugin_tracker']["model_info"][12]="Key for model discovery";
$LANG['plugin_tracker']["model_info"][13]="Lade richtiges Modell";
$LANG['plugin_tracker']["model_info"][14]="Lade richtiges SNMP Modell";

$LANG['plugin_tracker']["mib"][1]="MIB Bezeichnung";
$LANG['plugin_tracker']["mib"][2]="Objekt";
$LANG['plugin_tracker']["mib"][3]="oid";
$LANG['plugin_tracker']["mib"][4]="F&uuml;ge eine oid hinzu...";
$LANG['plugin_tracker']["mib"][5]="oid Liste";
$LANG['plugin_tracker']["mib"][6]="Port Counters";
$LANG['plugin_tracker']["mib"][7]="Dynamische ports (.x)";
$LANG['plugin_tracker']["mib"][8]="VerlTintete Felder";
$LANG['plugin_tracker']["mib"][9]="Vlan";

$LANG['plugin_tracker']["processes"][0]="Historie der Script ausf&uuml;hrung";
$LANG['plugin_tracker']["processes"][1]="PID";
$LANG['plugin_tracker']["processes"][2]="Status";
$LANG['plugin_tracker']["processes"][3]="Anzahl der Prozesse";
$LANG['plugin_tracker']["processes"][4]="Startdatum der Ausf&uuml;hrung";
$LANG['plugin_tracker']["processes"][5]="Enddatum der Ausf&uuml;hrung";
$LANG['plugin_tracker']["processes"][6]="Angefragte Netzwerkger&auml;te";
$LANG['plugin_tracker']["processes"][7]="Angefragte Drucker";
$LANG['plugin_tracker']["processes"][8]="Angefragte Anschl&uuml;&szlig;e";
$LANG['plugin_tracker']["processes"][9]="Fehler";
$LANG['plugin_tracker']["processes"][10]="Zeit Script";
$LANG['plugin_tracker']["processes"][11]="hinzugef&uuml;gte Felder";
$LANG['plugin_tracker']["processes"][12]="SNMP Fehler";
$LANG['plugin_tracker']["processes"][13]="Unbekannte MAC";
$LANG['plugin_tracker']["processes"][14]="Liste von unbekannten MAC Adressen";
$LANG['plugin_tracker']["processes"][15]="Erste PID";
$LANG['plugin_tracker']["processes"][16]="Letzte PID";
$LANG['plugin_tracker']["processes"][17]="Datum der ersten Erkennung";
$LANG['plugin_tracker']["processes"][18]="Datum der letzten Erkennung";
$LANG['plugin_tracker']["processes"][19]="Historie der Angen ausf&uuml;hrungen";
$LANG['plugin_tracker']["processes"][20]="Berichte und Statistiken";
$LANG['plugin_tracker']["processes"][21]="Abgefragte Ger&auml;te";
$LANG['plugin_tracker']["processes"][22]="Fehler";
$LANG['plugin_tracker']["processes"][23]="Dauer der gesamten Erkennung";
$LANG['plugin_tracker']["processes"][24]="Dauer der gesamten Anfrage";

$LANG['plugin_tracker']["state"][0]="Computer start";
$LANG['plugin_tracker']["state"][1]="Computer stop";
$LANG['plugin_tracker']["state"][2]="Benutzer Verbindung";
$LANG['plugin_tracker']["state"][3]="Benutzer Trennung";


$LANG['plugin_tracker']["mapping"][1]="Netzwerk > Standort";
$LANG['plugin_tracker']["mapping"][2]="Netzwerk > Firmware";
$LANG['plugin_tracker']["mapping"][3]="Netzwerk > Uptime";
$LANG['plugin_tracker']["mapping"][4]="Netzwerk > Port > MTU";
$LANG['plugin_tracker']["mapping"][5]="Netzwerk > Port > Geschwindigkeit";
$LANG['plugin_tracker']["mapping"][6]="Netzwerk > Port > Interner Zustand";
$LANG['plugin_tracker']["mapping"][7]="Netzwerk > Ports > Letzte &Auml;nderung";
$LANG['plugin_tracker']["mapping"][8]="Netzwerk > Port > Anzahl eingegangene Bytes";
$LANG['plugin_tracker']["mapping"][9]="Netzwerk > Port > Anzahl ausgehende Bytes";
$LANG['plugin_tracker']["mapping"][10]="Netzwerk > Port > Anzahl Input Fehler";
$LANG['plugin_tracker']["mapping"][11]="Netzwerk > Port > Anzahl Fehler Ausgehend";
$LANG['plugin_tracker']["mapping"][12]="Netzwerk > CPU Auslastung";
$LANG['plugin_tracker']["mapping"][13]="Netzwerk > Seriennummer";
$LANG['plugin_tracker']["mapping"][14]="Netzwerk > Port > Verbingungszustand";
$LANG['plugin_tracker']["mapping"][15]="Netzwerk > Port > MAC Adresse";
$LANG['plugin_tracker']["mapping"][16]="Netzwerk > Port > Name";
$LANG['plugin_tracker']["mapping"][17]="Netzwerk > Modell";
$LANG['plugin_tracker']["mapping"][18]="Netzwerk > Ports > Typ";
$LANG['plugin_tracker']["mapping"][19]="Netzwerk > VLAN";
$LANG['plugin_tracker']["mapping"][20]="Netzwerk > Name";
$LANG['plugin_tracker']["mapping"][21]="Netzwerk > Gesamter Speicher";
$LANG['plugin_tracker']["mapping"][22]="Netzwerk > Freier Speicher";
$LANG['plugin_tracker']["mapping"][23]="Netzwerk > Port > Port Bezeichnung";
$LANG['plugin_tracker']["mapping"][24]="Drucker > Name";
$LANG['plugin_tracker']["mapping"][25]="Drucker > Modell";
$LANG['plugin_tracker']["mapping"][26]="Drucker > Gesamter Speicher";
$LANG['plugin_tracker']["mapping"][27]="Drucker > Seriennummer";
$LANG['plugin_tracker']["mapping"][28]="Drucker > Messung > Gesamtanzahl gedruckter Seiten";
$LANG['plugin_tracker']["mapping"][29]="Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten";
$LANG['plugin_tracker']["mapping"][30]="Drucker > Messung > Gesamtanzahl gedruckter Farbseiten";
$LANG['plugin_tracker']["mapping"][31]="Drucker > Messung > Anzahl gedruckter Schwarz/Wei&szlig; Seiten";
$LANG['plugin_tracker']["mapping"][32]="Drucker > Messung > Anzahl gedruckter Farbseiten";
$LANG['plugin_tracker']["mapping"][33]="Netzwerk > Port > Duplex Typ";
$LANG['plugin_tracker']["mapping"][34]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (%)";
$LANG['plugin_tracker']["mapping"][35]="Drucker > Verbrauchsmaterial > Schwarze Photo Kartusche (%)";
$LANG['plugin_tracker']["mapping"][36]="Drucker > Verbrauchsmaterial > Cyan Kartusche (%)";
$LANG['plugin_tracker']["mapping"][37]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (%)";
$LANG['plugin_tracker']["mapping"][38]="Drucker > Verbrauchsmaterial > Magenta Kartusche (%)";
$LANG['plugin_tracker']["mapping"][39]="Drucker > Verbrauchsmaterial > Leicht Cyan Kartusche (%)";
$LANG['plugin_tracker']["mapping"][40]="Drucker > Verbrauchsmaterial > Leicht Magenta Kartusche (%)";
$LANG['plugin_tracker']["mapping"][41]="Drucker > Verbrauchsmaterial > Photoleiter (%)";
$LANG['plugin_tracker']["mapping"][42]="Drucker > Verbrauchsmaterial > Photoleiter Schwarz (%)";
$LANG['plugin_tracker']["mapping"][43]="Drucker > Verbrauchsmaterial > Photoleiter Farbe (%)";
$LANG['plugin_tracker']["mapping"][44]="Drucker > Verbrauchsmaterial > Photoleiter Cyan (%)";
$LANG['plugin_tracker']["mapping"][45]="Drucker > Verbrauchsmaterial > Photoleiter Gelb (%)";
$LANG['plugin_tracker']["mapping"][46]="Drucker > Verbrauchsmaterial > Photoleiter Magenta (%)";
$LANG['plugin_tracker']["mapping"][47]="Drucker > Verbrauchsmaterial > Fixiereinheit Schwarz (%)";
$LANG['plugin_tracker']["mapping"][48]="Drucker > Verbrauchsmaterial > Fixiereinheit Cyan (%)";
$LANG['plugin_tracker']["mapping"][49]="Drucker > Verbrauchsmaterial > Fixiereinheit Gelb (%)";
$LANG['plugin_tracker']["mapping"][50]="Drucker > Verbrauchsmaterial > Fixiereinheit Magenta (%)";
$LANG['plugin_tracker']["mapping"][51]="Drucker > Verbrauchsmaterial > Abfalleimer (%)";
$LANG['plugin_tracker']["mapping"][52]="Drucker > Verbrauchsmaterial > vier (%)";
$LANG['plugin_tracker']["mapping"][53]="Drucker > Verbrauchsmaterial > Reinigungsmodul (%)";
$LANG['plugin_tracker']["mapping"][54]="Drucker > Messung > Anzahl der gedruckten Duplex Seiten";
$LANG['plugin_tracker']["mapping"][55]="Drucker > Messung > Anzahl der gescannten Seiten";
$LANG['plugin_tracker']["mapping"][56]="Drucker > Standort";
$LANG['plugin_tracker']["mapping"][57]="Drucker > Port > Name";
$LANG['plugin_tracker']["mapping"][58]="Drucker > Port > MAC Adresse";
$LANG['plugin_tracker']["mapping"][59]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][60]="Drucker > Verbrauchsmaterial > Schwarze Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][61]="Drucker > Verbrauchsmaterial > Cyan Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][62]="Drucker > Verbrauchsmaterial > Cyan Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][63]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][64]="Drucker > Verbrauchsmaterial > Gelbe Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][65]="Drucker > Verbrauchsmaterial > Aagenta Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][66]="Drucker > Verbrauchsmaterial > Aagenta Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][67]="Drucker > Verbrauchsmaterial > Leichtes Cyan Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][68]="Drucker > Verbrauchsmaterial > Leichtes Cyan Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][69]="Drucker > Verbrauchsmaterial > Leichtes Magenta Kartusche (Maximal)";
$LANG['plugin_tracker']["mapping"][70]="Drucker > Verbrauchsmaterial > Leichtes Magenta Kartusche (Verbleibend)";
$LANG['plugin_tracker']["mapping"][71]="Drucker > Verbrauchsmaterial > Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][72]="Drucker > Verbrauchsmaterial > Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][73]="Drucker > Verbrauchsmaterial > Schwarzer Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][74]="Drucker > Verbrauchsmaterial > Schwarzer Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][75]="Drucker > Verbrauchsmaterial > Farbiger Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][76]="Drucker > Verbrauchsmaterial > Farbiger Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][77]="Drucker > Verbrauchsmaterial > Cyan Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][78]="Drucker > Verbrauchsmaterial > Cyan Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][79]="Drucker > Verbrauchsmaterial > Gelber Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][80]="Drucker > Verbrauchsmaterial > Gelber Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][81]="Drucker > Verbrauchsmaterial > Magenta Photoleiter (Maximal)";
$LANG['plugin_tracker']["mapping"][82]="Drucker > Verbrauchsmaterial > Magenta Photoleiter (Verbleibend)";
$LANG['plugin_tracker']["mapping"][83]="Drucker > Verbrauchsmaterial > Schwarze Fixiereinheit (Maximal)";
$LANG['plugin_tracker']["mapping"][84]="Drucker > Verbrauchsmaterial > Schwarze Fixiereinheit (Verbleibend)";
$LANG['plugin_tracker']["mapping"][85]="Drucker > Verbrauchsmaterial > Cyan Fixiereinheit (Maximal)";
$LANG['plugin_tracker']["mapping"][86]="Drucker > Verbrauchsmaterial > Cyan Fixiereinheit (Verbleibend)";
$LANG['plugin_tracker']["mapping"][87]="Drucker > Verbrauchsmaterial > Gelbe Fixiereinheit (Maximal)";
$LANG['plugin_tracker']["mapping"][88]="Drucker > Verbrauchsmaterial > Gelbe Fixiereinheit (Verbleibend)";
$LANG['plugin_tracker']["mapping"][89]="Drucker > Verbrauchsmaterial > Magenta Fixiereinheit (Maximal)";
$LANG['plugin_tracker']["mapping"][90]="Drucker > Verbrauchsmaterial > Magenta Fixiereinheit (Verbleibend)";
$LANG['plugin_tracker']["mapping"][91]="Drucker > Verbrauchsmaterial > Abfalleimer (Maximal)";
$LANG['plugin_tracker']["mapping"][92]="Drucker > Verbrauchsmaterial > Abfalleimer (Verbleibend)";
$LANG['plugin_tracker']["mapping"][93]="Drucker > Verbrauchsmaterial > vier (Maximal)";
$LANG['plugin_tracker']["mapping"][94]="Drucker > Verbrauchsmaterial > vier (Verbleibend)";
$LANG['plugin_tracker']["mapping"][95]="Drucker > Verbrauchsmaterial > Reinigungsmodul (Maximal)";
$LANG['plugin_tracker']["mapping"][96]="Drucker > Verbrauchsmaterial > Reinigungsmodul (Verbleibend)";
$LANG['plugin_tracker']["mapping"][97]="Drucker > port > Typ";
$LANG['plugin_tracker']["mapping"][98]="Drucker > Verbrauchsmaterial > Wartungsmodul (Maximal)";
$LANG['plugin_tracker']["mapping"][99]="Drucker > Verbrauchsmaterial > Wartungsmodul (Verbleibend)";
$LANG['plugin_tracker']["mapping"][400]="Drucker > Verbrauchsmaterial > Wartungsmodul (%)";
$LANG['plugin_tracker']["mapping"][401]="Netzwerk > CPU Benutzer";
$LANG['plugin_tracker']["mapping"][402]="Netzwerk > CPU System";
$LANG['plugin_tracker']["mapping"][403]="Netzwerk > Kontakt";
$LANG['plugin_tracker']["mapping"][404]="Netzwerk > Kommentar";
$LANG['plugin_tracker']["mapping"][405]="Drucker > Kontakt";
$LANG['plugin_tracker']["mapping"][406]="Drucker > Kommentar";
$LANG['plugin_tracker']["mapping"][407]="Drucker > Port > IP Adresse";
$LANG['plugin_tracker']["mapping"][408]="Netzwerk > Port > Nummerischer Index";
$LANG['plugin_tracker']["mapping"][409]="Netzwerk > Adresse CDP";
$LANG['plugin_tracker']["mapping"][410]="Netzwerk > Port CDP";
$LANG['plugin_tracker']["mapping"][411]="Netzwerk > Trunk Port Status";
$LANG['plugin_tracker']["mapping"][412]="Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)";
$LANG['plugin_tracker']["mapping"][413]="Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)";
$LANG['plugin_tracker']["mapping"][414]="Netzwerk > Instanzen des Ports (dot1dTpFdbPort)";
$LANG['plugin_tracker']["mapping"][415]="Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)";
$LANG['plugin_tracker']["mapping"][416]="Drucker > Port > Indexnummer";
$LANG['plugin_tracker']["mapping"][417]="Netzwerk > MAC Adresse";
$LANG['plugin_tracker']["mapping"][418]="Drucker > Inventarnummer";
$LANG['plugin_tracker']["mapping"][419]="Netzwerk > Inventarnummer";
$LANG['plugin_tracker']["mapping"][420]="Drucker > Hersteller";
$LANG['plugin_tracker']["mapping"][421]="Netzwerk > IP Adressen";
$LANG['plugin_tracker']["mapping"][422]="Netzwerk > portVlanIndex";
$LANG['plugin_tracker']["mapping"][423]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][424]="Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][425]="Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][426]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)";
$LANG['plugin_tracker']["mapping"][427]="Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)";
$LANG['plugin_tracker']["mapping"][428]="Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)";
$LANG['plugin_tracker']["mapping"][429]="Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)";



$LANG['plugin_tracker']["mapping"][101]="";
$LANG['plugin_tracker']["mapping"][102]="";
$LANG['plugin_tracker']["mapping"][103]="";
$LANG['plugin_tracker']["mapping"][104]="MTU";
$LANG['plugin_tracker']["mapping"][105]="Geschwindigkeit";
$LANG['plugin_tracker']["mapping"][106]="Interer Status";
$LANG['plugin_tracker']["mapping"][107]="Letze &Auml;nderung";
$LANG['plugin_tracker']["mapping"][108]="Anzahl empfangener Bytes";
$LANG['plugin_tracker']["mapping"][109]="Anzahl ausgehender Bytes";
$LANG['plugin_tracker']["mapping"][110]="Anzahl input Fehler";
$LANG['plugin_tracker']["mapping"][111]="Anzahl output Fehler";
$LANG['plugin_tracker']["mapping"][112]="CPU Verwendung";
$LANG['plugin_tracker']["mapping"][113]="";
$LANG['plugin_tracker']["mapping"][114]="Verbindung";
$LANG['plugin_tracker']["mapping"][115]="Interne MAC Adresse";
$LANG['plugin_tracker']["mapping"][116]="Name";
$LANG['plugin_tracker']["mapping"][117]="Model";
$LANG['plugin_tracker']["mapping"][118]="Typ";
$LANG['plugin_tracker']["mapping"][119]="VLAN";
$LANG['plugin_tracker']["mapping"][128]="Gesamtanzahl gedruckte Seiten";
$LANG['plugin_tracker']["mapping"][129]="Anzahl gedruckter Schwarz/Wei&szlig; Seiten";
$LANG['plugin_tracker']["mapping"][130]="Anzahl gedruckter Farbeseiten";
$LANG['plugin_tracker']["mapping"][131]="Anzahl gedruckter Graustufenseiten";
$LANG['plugin_tracker']["mapping"][132]="Anzahl gedruckter Farbseiten";
$LANG['plugin_tracker']["mapping"][134]="Schwarze Kartusche";
$LANG['plugin_tracker']["mapping"][135]="Photoschwarz Kartusche";
$LANG['plugin_tracker']["mapping"][136]="Cyan Kartusche";
$LANG['plugin_tracker']["mapping"][137]="Gelbe Kartusche";
$LANG['plugin_tracker']["mapping"][138]="Magenta Kartusche";
$LANG['plugin_tracker']["mapping"][139]="Leichtes Cyan Kartusche";
$LANG['plugin_tracker']["mapping"][140]="Leichtes Magenta Kartusche";
$LANG['plugin_tracker']["mapping"][141]="Photoleiter";
$LANG['plugin_tracker']["mapping"][142]="Black Photoleiter";
$LANG['plugin_tracker']["mapping"][143]="Farbiger Photoleiter";
$LANG['plugin_tracker']["mapping"][144]="Cyan Photoleiter";
$LANG['plugin_tracker']["mapping"][145]="Gelber Photoleiter";
$LANG['plugin_tracker']["mapping"][146]="Magenta Photoleiter";
$LANG['plugin_tracker']["mapping"][147]="Schwarze Fixiereinheit";
$LANG['plugin_tracker']["mapping"][148]="Cyan Fixiereinheit";
$LANG['plugin_tracker']["mapping"][149]="Gelbe Fixiereinheit";
$LANG['plugin_tracker']["mapping"][150]="Magenta Fixiereinheit";
$LANG['plugin_tracker']["mapping"][151]="Abfalleimer";
$LANG['plugin_tracker']["mapping"][152]="Vier";
$LANG['plugin_tracker']["mapping"][153]="Reinigungsmodul";
$LANG['plugin_tracker']["mapping"][154]="Anzahl gedruckter Duplexseiten";
$LANG['plugin_tracker']["mapping"][155]="Anzahl gescannter Seiten";
$LANG['plugin_tracker']["mapping"][156]="Wartungsmodul";
$LANG['plugin_tracker']["mapping"][1423]="Gesamtanzahl gedruckter Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][1424]="Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][1425]="Gesamtanzahl farbig gedruckter Seiten (Druck)";
$LANG['plugin_tracker']["mapping"][1426]="Gesamtanzahl gedruckter Seiten (Kopie)";
$LANG['plugin_tracker']["mapping"][1427]="Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)";
$LANG['plugin_tracker']["mapping"][1428]="Gesamtanzahl farbig gedruckter Seiten (Kopie)";
$LANG['plugin_tracker']["mapping"][1429]="Gesamtanzahl gedruckter Seiten (Fax)";


$LANG['plugin_tracker']["Drucker"][0]="Seiten";


$LANG['plugin_tracker']["menu"][1]="Agent Konfiguration";
$LANG['plugin_tracker']["menu"][2]="IP Bereich Konfiguration";
$LANG['plugin_tracker']["menu"][3]="Menu";
$LANG['plugin_tracker']["menu"][4]="Ubekanntes Ger&auml;t";

$LANG['plugin_tracker']["menu"][0]="Informationen &uuml;ber entdeckte Ger&auml;te";

$LANG['plugin_tracker']["buttons"][0]="Entdecke";

$LANG['plugin_tracker']["discovery"][0]="IP Bereich zum Scannen";
$LANG['plugin_tracker']["discovery"][1]="Endeckte Ger&auml;te";
$LANG['plugin_tracker']["discovery"][2]="Activation in the script automatically";
$LANG['plugin_tracker']["discovery"][3]="Endecker";
$LANG['plugin_tracker']["discovery"][4]="Serien Nummer";
$LANG['plugin_tracker']["discovery"][5]="Anzahl importierter Ger&auml;te";
$LANG['plugin_tracker']["discovery"][6]="Prim&auml;res Kriterium f&uuml;r die Existenz";
$LANG['plugin_tracker']["discovery"][7]="Sekund&auml;res Kriterium für die Existenz ";
$LANG['plugin_tracker']["discovery"][8]="Wenn ein Ger&auml;t ein leeres Feld bim ersten Kriterium bringt dann wird das zweite Benutzt.";

$LANG['plugin_tracker']["rangeip"][0]="Start des IP Bereichs";
$LANG['plugin_tracker']["rangeip"][1]="Ende des IP Bereichs";
$LANG['plugin_tracker']["rangeip"][2]="IP Bereiche";
$LANG['plugin_tracker']["rangeip"][3]="Abfrage";


$LANG['plugin_tracker']["agents"][0]="SNMP Agent";
$LANG['plugin_tracker']["agents"][2]="Number of threads used by core for querying devices";
$LANG['plugin_tracker']["agents"][3]="Number of threads used by core for network discovery";
$LANG['plugin_tracker']["agents"][4]="Last scan";
$LANG['plugin_tracker']["agents"][5]="Agent version";
$LANG['plugin_tracker']["agents"][6]="Lock";
$LANG['plugin_tracker']["agents"][7]="Export agent configuration";
$LANG['plugin_tracker']["agents"][8]="Fragments en Ko";
$LANG['plugin_tracker']["agents"][9]="Advanced options";
$LANG['plugin_tracker']["agents"][10]="Number of core(s) (CPU) used for querying devices";
$LANG['plugin_tracker']["agents"][11]="Number of core(s) (CPU) used for network discovery";

?>
