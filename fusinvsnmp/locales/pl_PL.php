<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @comment   Not translate this file, use https://www.transifex.net/projects/p/FusionInventory/
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_fusinvsnmp']['agents'][24]="Liczba wątków";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agent(ci)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Wersja modułu Netdiscovery";
$LANG['plugin_fusinvsnmp']['agents'][27]="Wersja modułu Snmpquery";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="odpytanie urządzeń";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="znalezione urządzenia";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="Definicji urządzenia SNMP agenta nie jest aktualna. Przy następnym uruchomieniu, będzie pobrana nowa wersja z serwera.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="Dodaj pozycje";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="Aktualizacja pozycji";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="Start inwentaryzacji";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Więcej";

$LANG['plugin_fusinvsnmp']['config'][10]="Ports types to import (for network equipments)";
$LANG['plugin_fusinvsnmp']['config'][3]="Inwentaryzacja sieci (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Przeszukiwanie sieci";
$LANG['plugin_fusinvsnmp']['config'][8]="Nigdy";
$LANG['plugin_fusinvsnmp']['config'][9]="Zawsze";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Automatyczne tworzenie modeli";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Generuj plik przeszukiwania";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Usuń nie używane modele";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Eksport wszystkich modeli";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Re-create models comments";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Liczba importowanych urządzeń";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Liczba urządzeń Które nie zostały zaimportowane, ponieważ nie mają zdefiniowanego typu";

$LANG['plugin_fusinvsnmp']['errors'][50]="Ta wersja GLPI jest niekompatybilna, wymagana jest 0.78";

$LANG['plugin_fusinvsnmp']['legend'][0]="Połączenie ze Switchem lub Serwerem w trybie trunk lub tagged";
$LANG['plugin_fusinvsnmp']['legend'][1]="Inne połączenia (z komputerem, drukarką ...)";

$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Szybkość";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Wewnętrzny status";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Ostatnia zmiana";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Liczba otrzymanych bajtów";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Liczba wysłanych bajtów";
$LANG['plugin_fusinvsnmp']['mapping'][10]="sieć > port > liczba błędów wejścia";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Liczba błędów wejścia";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Liczba błędów wyjścia";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Wykorzystanie CPU";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Połączenie";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Wewnętrzny numer MAC";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Nazwa";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Model";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Typ";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="sieć > port > liczba błędów wyjścia";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Liczba wydrukowanych stron";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Liczba wydrukowanych stron czarno-białych";
$LANG['plugin_fusinvsnmp']['mapping'][12]="sieć > Użycie CPU";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Liczba wydrukowanych stron kolorowych";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Liczba wydrukowanych stron jednokolorowych";
$LANG['plugin_fusinvsnmp']['mapping'][134]="czarny katridż";
$LANG['plugin_fusinvsnmp']['mapping'][135]="fotograficzny czarny katridz";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Niebieski katridż";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Żółty ";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Magenta Kartridż";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Jasno niebieski ";
$LANG['plugin_fusinvsnmp']['mapping'][13]="sieć > numer seryjny";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Jasna Magenta Kartridż";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Łączna liczba drukowanych stron (wydruki)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Ilość wydrukowanych stron czarno-białych (wydruki)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Ilość wydrukowanych stron kolorowych (wydruki)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Liczba wydrukowanych stron (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Liczba wydrukowanych stron czarno-białych (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Liczba wydrukowanych stron kolorowych (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Liczba wydrukowanych stron (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Czarny bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Liczba wszystkich wydrukowanych stron";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Kolorowy bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Niebieski bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Żółty bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Magenta bęben światłoczuły";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Czarny moduł transferu";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Niebieski moduł transferu";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Żółty moduł transferu";
$LANG['plugin_fusinvsnmp']['mapping'][14]="sieć > port > status połączenia";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Magenta Moduł transferu";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Pojemnik na odpady";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Cztery";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Czyszczenie modułu";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Liczba wydrukowanych stron podwójnych";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Liczba zeskanowanych stron";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Zestaw do konserwacji";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Czarny toner";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Niebieski toner";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Magenta toner";
$LANG['plugin_fusinvsnmp']['mapping'][15]="sieć > port > adres MAC";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Żółty toner";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Czarny bęben";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Niebieski Bęben";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Bęben Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Bęben Żółty ";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Informacje pogrupowane";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Czarny toner 2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Wykorzystany czarny toner";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Pozostały czarny toner";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Niebieski toner Max";
$LANG['plugin_fusinvsnmp']['mapping'][16]="sieć > port > Nazwa";
$LANG['plugin_fusinvsnmp']['mapping'][170]="Wykorzystany niebieski toner";
$LANG['plugin_fusinvsnmp']['mapping'][171]="Pozostały niebieski toner";
$LANG['plugin_fusinvsnmp']['mapping'][172]="Max magenta toner";
$LANG['plugin_fusinvsnmp']['mapping'][173]="Wykorzystany magenta toner";
$LANG['plugin_fusinvsnmp']['mapping'][174]="Pozostały magenta toner";
$LANG['plugin_fusinvsnmp']['mapping'][175]="Max żółtego toneru";
$LANG['plugin_fusinvsnmp']['mapping'][176]="Wykorzystany żółty toner";
$LANG['plugin_fusinvsnmp']['mapping'][177]="Pozostały żółty toner";
$LANG['plugin_fusinvsnmp']['mapping'][178]="Black drum Max";
$LANG['plugin_fusinvsnmp']['mapping'][179]="Black drum Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][17]="sieć > port > model";
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
$LANG['plugin_fusinvsnmp']['mapping'][18]="sieć > port > typ";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Waste bin Max";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Waste bin Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Waste bin Restant";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Maintenance kit Max";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Maintenance kit Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Maintenance kit Restant";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Szary ";
$LANG['plugin_fusinvsnmp']['mapping'][19]="sieć > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][1]="sieć > lokalizacja";
$LANG['plugin_fusinvsnmp']['mapping'][20]="sieć > nazwa";
$LANG['plugin_fusinvsnmp']['mapping'][21]="sieć > pamięć całkowita";
$LANG['plugin_fusinvsnmp']['mapping'][22]="sieć > wolna pamięć";
$LANG['plugin_fusinvsnmp']['mapping'][23]="sieć > port > opis portu";
$LANG['plugin_fusinvsnmp']['mapping'][24]="drukarka > nazwa";
$LANG['plugin_fusinvsnmp']['mapping'][25]="drukarka > model";
$LANG['plugin_fusinvsnmp']['mapping'][26]="drukarka > ilość pamięci";
$LANG['plugin_fusinvsnmp']['mapping'][27]="drukarka > numer seryjny";
$LANG['plugin_fusinvsnmp']['mapping'][28]="drukarka > licznik > całkowita liczba wydrukowanych stron";
$LANG['plugin_fusinvsnmp']['mapping'][29]="drukarka > licznik > liczba wydrukowanych stron czarno-białych";
$LANG['plugin_fusinvsnmp']['mapping'][2]="sieć > firmware";
$LANG['plugin_fusinvsnmp']['mapping'][30]="drukarka > licznik > liczba wydrukowanych stron kolorowych";
$LANG['plugin_fusinvsnmp']['mapping'][31]="drukarka > licznik > liczba wydrukowanych stron monochromatycznych";
$LANG['plugin_fusinvsnmp']['mapping'][33]="sieć > port > typ dwukierunkowości";
$LANG['plugin_fusinvsnmp']['mapping'][34]="drukarka > eksploatacyjne > kartridż czarny (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="drukarka > eksploatacyjne > kartridż czarny foto (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="drukarka > eksploatacyjne > kartridż niebieski (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="drukarka > eksploatacyjne > kartridż żółty (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="drukarka > eksploatacyjne > kartridż magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="drukarka > eksploatacyjne > kartridż jasno niebieski (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="sieć > czas działania";
$LANG['plugin_fusinvsnmp']['mapping'][400]="printer > consumables > maintenance kit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="sieć > CPU użytkownik";
$LANG['plugin_fusinvsnmp']['mapping'][402]="sieć > CPU system";
$LANG['plugin_fusinvsnmp']['mapping'][403]="sieć > kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][404]="sieć > komentarze";
$LANG['plugin_fusinvsnmp']['mapping'][405]="drukarka > kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][406]="Drukarka > komentarz";
$LANG['plugin_fusinvsnmp']['mapping'][407]="drukarka > port > Adres IP";
$LANG['plugin_fusinvsnmp']['mapping'][408]="sieć > port > numer index-u";
$LANG['plugin_fusinvsnmp']['mapping'][409]="sieć > Adres CDP";
$LANG['plugin_fusinvsnmp']['mapping'][40]="drukarka > eksploatacyjne > kartridż jasna magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][410]="sieć > Port CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="sieć > port > trunk/otagowany";
$LANG['plugin_fusinvsnmp']['mapping'][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="drukarka > port > numer index-u";
$LANG['plugin_fusinvsnmp']['mapping'][417]="sieć > adres MAC";
$LANG['plugin_fusinvsnmp']['mapping'][418]="drukarka > numer inwentaryzacyjny";
$LANG['plugin_fusinvsnmp']['mapping'][419]="sieć > numer inwentaryzacyjny";
$LANG['plugin_fusinvsnmp']['mapping'][41]="drukarka > eksploatacyjne > bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="Drukarka > producenta";
$LANG['plugin_fusinvsnmp']['mapping'][421]="sieć > adres IP";
$LANG['plugin_fusinvsnmp']['mapping'][422]="sieć > PVID (VLAN ID portu)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="drukarka > licznik > całkowita liczba wydrukowanych stron (wydruk)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="drukarka > licznik > liczba wydrukowanych stron czarno-białych (wydruk)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="drukarka > licznik > liczba wydrukowanych stron kolorowych (wydruk)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="drukarka > licznik > całkowita liczba wydrukowanych stron (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="drukarka > licznik > liczba wydrukowanych stron czarno-białych (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="drukarka > licznik > liczba wydrukowanych stron kolorowych (kopie)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="drukarka > licznik > liczba wydrukowanych stron (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="drukarka > eksploatacyjne > czarny bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="sieć > port > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][435]="sieć > zdalne sysdescr CDP";
$LANG['plugin_fusinvsnmp']['mapping'][436]="sieć > zdalny identyfikator CDP";
$LANG['plugin_fusinvsnmp']['mapping'][437]="sieć > model CDP zdalnego urządzenia";
$LANG['plugin_fusinvsnmp']['mapping'][438]="sieć > zdalny sysdescr LLDP";
$LANG['plugin_fusinvsnmp']['mapping'][439]="sieć > zdalny identyfikator LLDP";
$LANG['plugin_fusinvsnmp']['mapping'][43]="drukarka > eksploatacyjne > kolorowy bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="sieć > zdalny opis LLDP portu";
$LANG['plugin_fusinvsnmp']['mapping'][44]="drukarka > eksploatacyjne > niebieski bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="drukarka > eksploatacyjne > żółty bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="drukarka > eksploatacyjne > magenta bęben światłoczuły (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="printer > consumables > black transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="printer > consumables > cyan transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="printer > consumables > yellow transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="sieć > port > MTU";
$LANG['plugin_fusinvsnmp']['mapping'][50]="printer > consumables > magenta transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="printer > consumables > waste bin (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="printer > consumables > four (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="drukarka > eksploatacyjne > moduł czyszczący (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="drukarka > licznik > liczba wydrukowanych obustronnie stron";
$LANG['plugin_fusinvsnmp']['mapping'][55]="drukarka > licznik > liczba skanowanych stron";
$LANG['plugin_fusinvsnmp']['mapping'][56]="drukarka > lokalizacja";
$LANG['plugin_fusinvsnmp']['mapping'][57]="drukarka > port > nazwa";
$LANG['plugin_fusinvsnmp']['mapping'][58]="drukarka > port > MAC adres";
$LANG['plugin_fusinvsnmp']['mapping'][59]="drukarka > eksploatacyjne > kartridż czarny (max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][5]="sieć > port > szybkość";
$LANG['plugin_fusinvsnmp']['mapping'][60]="drukarka > eksploatacyjne > kartridż czarny (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="drukarka > eksploatacyjne > kartridż niebieski (max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="drukarka > eksploatacyjne > kartridż niebieski (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="drukarka > eksploatacyjne > kartridż żółty (max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="drukarka > eksploatacyjne > kartridż żółty  (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="drukarka > eksploatacyjne > kartridż magenta (max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="drukarka > eksploatacyjne > kartridż magenta (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="drukarka > eksploatacyjne > kartridż jasny niebieski(max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="drukarka > eksploatacyjne > kartridż jasny niebieski (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="drukarka > eksploatacyjne > kartridż jasna magenta (max tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][6]="sieć > port > status wewnętrzny";
$LANG['plugin_fusinvsnmp']['mapping'][70]="drukarka > eksploatacyjne > kartridż jasna magenta (pozostały tusz)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="printer > consumables > photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="printer > consumables > black photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="printer > consumables > color photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][7]="sieć > port > ostatnia zmiana";
$LANG['plugin_fusinvsnmp']['mapping'][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="printer > consumables > magenta photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="printer > consumables > black transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="printer > consumables > black transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="printer > consumables > cyan transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="printer > consumables > yellow transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="printer > consumables > magenta transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][8]="sieć > port > liczba bajtów wchodzących";
$LANG['plugin_fusinvsnmp']['mapping'][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="printer > consumables > waste bin (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="printer > consumables > waste bin (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="printer > consumables > four (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="printer > consumables > four (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="drukarka > eksploatacyjne > moduł czyszczący (max)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="drukarka > eksploatacyjne > moduł czyszczący (pozostało)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="drukarka > port > typ";
$LANG['plugin_fusinvsnmp']['mapping'][98]="printer > consumables > maintenance kit (max)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="printer > consumables > maintenance kit (remaining)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="sieć > port > liczba bajtów wychodzących";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Przypisz model SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Przypisz uwierzytelnienie SNMP";

$LANG['plugin_fusinvsnmp']['menu'][10]="Status inwentaryzacji sieci";
$LANG['plugin_fusinvsnmp']['menu'][2]="Konfiguracja zakresu IP";
$LANG['plugin_fusinvsnmp']['menu'][5]="Historia portów przełącznika";
$LANG['plugin_fusinvsnmp']['menu'][6]="Nieużywane poty przełącznika";
$LANG['plugin_fusinvsnmp']['menu'][9]="Status wyszukiwania";

$LANG['plugin_fusinvsnmp']['mib'][1]="Opis MIB";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objekt";
$LANG['plugin_fusinvsnmp']['mib'][3]="OID";
$LANG['plugin_fusinvsnmp']['mib'][4]="dodaj OID";
$LANG['plugin_fusinvsnmp']['mib'][5]="Lista OID-ów";
$LANG['plugin_fusinvsnmp']['mib'][6]="Liczniki portu";
$LANG['plugin_fusinvsnmp']['mib'][7]="Port dynamiczny (. X)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Połączone pola";

$LANG['plugin_fusinvsnmp']['model_info'][10]="import modelu SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][11]="jest aktywny";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Key of model discovery";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Wczytaj poprawny model";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Wczytaj poprawny model SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Masowy import modeli";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Masowy import modeli z folderu plugin/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Wersja SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="Uwierzytelnienie SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Modele SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Edycja modelu SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Utwórz model SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import zakończony sukcesem";

$LANG['plugin_fusinvsnmp']['portlogs'][0]="Konfiguracja historii";
$LANG['plugin_fusinvsnmp']['portlogs'][1]="Lista pól, dla których ma być zachowana historia";
$LANG['plugin_fusinvsnmp']['portlogs'][2]="Przechowywanie w dniach";

$LANG['plugin_fusinvsnmp']['printhistory'][1]="Zbyt dużo danych do wyświetlenia";

$LANG['plugin_fusinvsnmp']['processes'][37]="Liczba numerów IP";

$LANG['plugin_fusinvsnmp']['profile'][2]="Konfiguracja";
$LANG['plugin_fusinvsnmp']['profile'][4]="Zakres IP";
$LANG['plugin_fusinvsnmp']['profile'][5]="Sprzęt sieciowy SNMP";
$LANG['plugin_fusinvsnmp']['profile'][6]="Drukarka SNMP";
$LANG['plugin_fusinvsnmp']['profile'][7]="Model SNMP";
$LANG['plugin_fusinvsnmp']['profile'][8]="Raport drukarek";
$LANG['plugin_fusinvsnmp']['profile'][9]="Raport sieciowy";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Historia i statystyki liczników drukarki";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Liczba wydrukowanych stron";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Stron/ dzień";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="Historia licznika drukarki";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Data";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Metr";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Jednostka czasu";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Dodaj drukarkę";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Usuń drukarkę";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="dzień";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="tydzień";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="miesiąc";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="rok";
$LANG['plugin_fusinvsnmp']['prt_history'][38]="Drukarki do porównania";

$LANG['plugin_fusinvsnmp']['report'][0]="Liczba dni od ostatniej inwentaryzacji";
$LANG['plugin_fusinvsnmp']['report'][1]="Licznik wydrukowanych stron";

$LANG['plugin_fusinvsnmp']['setup'][17]="Plugin FusionInventory SNMP wymaga aktywowanej wtyczki FusionInventory przed aktywacją.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Plugin FusionInventory SNMP wymaga aktywnej wtyczki FusionInventory przed odinstalowaniem.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Konwersja historii portu";
$LANG['plugin_fusinvsnmp']['setup'][20]="Przeniesienie historii utworzonych połączeń";
$LANG['plugin_fusinvsnmp']['setup'][21]="Przenoszenie historii usuniętych połączeń";

$LANG['plugin_fusinvsnmp']['snmp'][12]="Czas działania";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Wykorzystanie CPU (%)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Wykorzystanie pamięci (%)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Tablica portów";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Opis portu";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Liczba odebranych bajtów";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Liczba wysłanych bajtów";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Liczba błędów w odbiorze";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Opis (Sysdescr)";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Dupleks";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Ostatnia inwentaryzacja";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Dane niedostępne";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Liczba na sekundę";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="społeczność SNMP";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Użytkownik";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Protokół szyfrowania dla uwierzytelnienia";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Hasło";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Protokół szyfrowania danych";

$LANG['plugin_fusinvsnmp']['state'][10]="Zaimportowane urządzenia";
$LANG['plugin_fusinvsnmp']['state'][4]="Data rozpoczęcia";
$LANG['plugin_fusinvsnmp']['state'][5]="Data zakończenia";
$LANG['plugin_fusinvsnmp']['state'][6]="Liczba znalezionych urządzeń";
$LANG['plugin_fusinvsnmp']['state'][7]="Liczba błędów";
$LANG['plugin_fusinvsnmp']['state'][8]="Urządzenia niezaimportowane";
$LANG['plugin_fusinvsnmp']['state'][9]="Urządzenia zlinkowane";

$LANG['plugin_fusinvsnmp']['stats'][0]="Całkowity licznik";
$LANG['plugin_fusinvsnmp']['stats'][1]="stron na dzień";
$LANG['plugin_fusinvsnmp']['stats'][2]="Wyświetl";

$LANG['plugin_fusinvsnmp']['task'][15]="Stałe zadanie";
$LANG['plugin_fusinvsnmp']['task'][17]="Typ komunikacji";
$LANG['plugin_fusinvsnmp']['task'][18]="Szybkie utworzenie zadania";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="Informacje SNMP";
$LANG['plugin_fusinvsnmp']['title'][2]="historia połączeń";
$LANG['plugin_fusinvsnmp']['title'][5]="Czarny toner";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>