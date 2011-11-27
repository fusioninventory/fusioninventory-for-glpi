<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_fusinvsnmp']['agents'][24]="Threads number";
$LANG['plugin_fusinvsnmp']['agents'][25]="Агент (ы)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Netdiscovery module version";
$LANG['plugin_fusinvsnmp']['agents'][27]="Snmpquery module version";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="devices queried";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="Устройств найдено";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="SNMP equipment definition isn't up to date on agent. For the next run, it will get new version from server.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="Добавить элемент";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="Обновить элемент";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="Inventory started";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Подробно";

$LANG['plugin_fusinvsnmp']['config'][3]="Сетевая инвентаризация (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Сетевое обнаружение";
$LANG['plugin_fusinvsnmp']['config'][8]="Никогда";
$LANG['plugin_fusinvsnmp']['config'][9]="Всегда";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Automatic creation of models";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Generate discovery file";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Delete models non used";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Экспорт всех моделей";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Re-create models comments";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Число импортированных устройств";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Число не импортированных устройств т.к. не определен тип";

$LANG['plugin_fusinvsnmp']['errors'][50]="Не совместимая версия с GLPI, рекомендуемая 0.78";

$LANG['plugin_fusinvsnmp']['legend'][0]="Connection with a switch or a server in trunk or tagged mode";
$LANG['plugin_fusinvsnmp']['legend'][1]="Other connections (with a computer, a printer...)";

$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Скорость";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Внутренний статус";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Последние изменение";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Число полученных байт";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Число отправленных байт";
$LANG['plugin_fusinvsnmp']['mapping'][10]="networking > port > number of input errors";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Число входящих ошибок";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Число исходящих ошибок";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Загрузка CPU";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Соединение";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Внутренний MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Имя";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Модель";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Тип";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="networking > port > number of errors output";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Число напечатанных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Число напечатанных черных и белых страниц";
$LANG['plugin_fusinvsnmp']['mapping'][12]="сеть > использование CPU";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Число напечатанных цветных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Число напечатанных монохромных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Черный картридж";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Черный фото картридж";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Голубой картридж";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Желтый картридж";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Пурпурный картридж";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Светло-голубой картридж";
$LANG['plugin_fusinvsnmp']['mapping'][13]="сеть > серийный номер";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Светло-пурпурный картридж";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Фотокондуктор ";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Total number of printed pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Number of printed black and white pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Number of printed color pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Total number of printed pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Number of printed black and white pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Number of printed color pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Total number of printed pages (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Черный фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Total number of large printed pages";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Цветной фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Голубой фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Желтый фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Пурпурный фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Black transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Cyan transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Yellow transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][14]="сеть > порт > статус соединения";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Magenta transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Waste bin";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Four";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Cleaning module";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Number of pages printed duplex";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Number of scanned pages";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Maintenance kit";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Черный тонер";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Голубой тонер";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Пурпурный тонер";
$LANG['plugin_fusinvsnmp']['mapping'][15]="сеть > порт > MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Желтый тонер";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Черный барабан";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Голубой барабан";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Пурпурный барабан";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Желтый барабан";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Many informations grouped";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Черный тонер 2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Использованный черный тонер";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Оставшийся черный тонер";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Cyan toner Max";
$LANG['plugin_fusinvsnmp']['mapping'][16]="сеть > порт > имя";
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
$LANG['plugin_fusinvsnmp']['mapping'][17]="сеть > модель";
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
$LANG['plugin_fusinvsnmp']['mapping'][18]="сеть > порт > тип";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Waste bin Max";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Waste bin Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Waste bin Restant";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Maintenance kit Max";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Maintenance kit Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Maintenance kit Restant";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Grey ink cartridge";
$LANG['plugin_fusinvsnmp']['mapping'][19]="сеть > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][1]="сеть > местоположение";
$LANG['plugin_fusinvsnmp']['mapping'][20]="сеть > имя";
$LANG['plugin_fusinvsnmp']['mapping'][21]="сеть > всего памяти";
$LANG['plugin_fusinvsnmp']['mapping'][22]="сеть > свободной памяти";
$LANG['plugin_fusinvsnmp']['mapping'][23]="сеть > порт > описание порта";
$LANG['plugin_fusinvsnmp']['mapping'][24]="принтер > имя";
$LANG['plugin_fusinvsnmp']['mapping'][25]="принтер > модель";
$LANG['plugin_fusinvsnmp']['mapping'][26]="принтер > всего памяти";
$LANG['plugin_fusinvsnmp']['mapping'][27]="принтер > серийный номер";
$LANG['plugin_fusinvsnmp']['mapping'][28]="printer > meter > total number of printed pages";
$LANG['plugin_fusinvsnmp']['mapping'][29]="printer > meter > number of printed black and white pages";
$LANG['plugin_fusinvsnmp']['mapping'][2]="сеть > прошивка";
$LANG['plugin_fusinvsnmp']['mapping'][30]="printer > meter > number of printed color pages";
$LANG['plugin_fusinvsnmp']['mapping'][31]="printer > meter > number of printed monochrome pages";
$LANG['plugin_fusinvsnmp']['mapping'][33]="сеть > порт > тип дуплекса";
$LANG['plugin_fusinvsnmp']['mapping'][34]="принтер > расходные материалы > черный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="принтер > расходные материалы > черный фото картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="принтер > расходные материалы > голубой картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="принтер > расходные материалы > желтый картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="принтер > расходные материалы > пурпурный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="принтер > расходные материалы > светло голубой картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="сеть > доступность";
$LANG['plugin_fusinvsnmp']['mapping'][400]="printer > consumables > maintenance kit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="сеть > CPU user";
$LANG['plugin_fusinvsnmp']['mapping'][402]="сеть > CPU системы";
$LANG['plugin_fusinvsnmp']['mapping'][403]="сеть > контакты";
$LANG['plugin_fusinvsnmp']['mapping'][404]="сеть > коментарии";
$LANG['plugin_fusinvsnmp']['mapping'][405]="принтер > контакты";
$LANG['plugin_fusinvsnmp']['mapping'][406]="принтер > комментарии";
$LANG['plugin_fusinvsnmp']['mapping'][407]="принтер > порт > IP адрес";
$LANG['plugin_fusinvsnmp']['mapping'][408]="networking > port > index number";
$LANG['plugin_fusinvsnmp']['mapping'][409]="сеть > адрес CDP";
$LANG['plugin_fusinvsnmp']['mapping'][40]="принтер > расходные материалы > светло пурпурный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][410]="сеть > порт CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="сеть > порт > trunk/tagged";
$LANG['plugin_fusinvsnmp']['mapping'][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="printer > port > index number";
$LANG['plugin_fusinvsnmp']['mapping'][417]="сеть > MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][418]="принтер > инвентарный номер";
$LANG['plugin_fusinvsnmp']['mapping'][419]="сеть > инвентарный номер";
$LANG['plugin_fusinvsnmp']['mapping'][41]="принтер > расходные материалы > фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="принтер > производитель";
$LANG['plugin_fusinvsnmp']['mapping'][421]="сеть > IP адрес";
$LANG['plugin_fusinvsnmp']['mapping'][422]="сеть > PVID (порт VLAN ID)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="printer > meter > total number of printed pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="printer > meter > number of printed black and white pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="printer > meter > number of printed color pages (print)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="printer > meter > total number of printed pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="printer > meter > number of printed black and white pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="printer > meter > number of printed color pages (copy)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="printer > meter > total number of printed pages (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="принтер > расходные материалы > черный фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="сеть > порт > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][435]="networking > CDP remote sysdescr";
$LANG['plugin_fusinvsnmp']['mapping'][436]="networking > CDP remote id";
$LANG['plugin_fusinvsnmp']['mapping'][437]="networking > CDP remote model device";
$LANG['plugin_fusinvsnmp']['mapping'][438]="networking > LLDP remote sysdescr";
$LANG['plugin_fusinvsnmp']['mapping'][439]="networking > LLDP remote id";
$LANG['plugin_fusinvsnmp']['mapping'][43]="принтер > расходные материалы > цветной фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="networking > LLDP remote port description";
$LANG['plugin_fusinvsnmp']['mapping'][44]="принтер > расходные материалы > голубой фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="принтер > расходные материалы > желтый фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="принтер > расходные материалы > пурпурный фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="принтер > расходные материалы > черный transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="принтер > расходные материалы > голубой transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="принтер > расходные материалы > желтый transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="сеть > порт > mtu";
$LANG['plugin_fusinvsnmp']['mapping'][50]="принтер > расходные материалы > пурпурный transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="printer > consumables > waste bin (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="printer > consumables > four (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="printer > consumables > cleaning module (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="printer > meter > number of printed duplex pages";
$LANG['plugin_fusinvsnmp']['mapping'][55]="printer > meter > nomber of scanned pages";
$LANG['plugin_fusinvsnmp']['mapping'][56]="принтер > местоположение";
$LANG['plugin_fusinvsnmp']['mapping'][57]="принтер > порт > имя";
$LANG['plugin_fusinvsnmp']['mapping'][58]="принтер > порт > MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][59]="printer > consumables > black cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][5]="сеть > порт > скорость";
$LANG['plugin_fusinvsnmp']['mapping'][60]="printer > consumables > black cartridge (remaining ink )";
$LANG['plugin_fusinvsnmp']['mapping'][61]="printer > consumables > cyan cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="printer > consumables > yellow cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="printer > consumables > magenta cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="printer > consumables > light cyan cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="printer > consumables > light magenta cartridge (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][6]="сеть > порт > внутренний статус";
$LANG['plugin_fusinvsnmp']['mapping'][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="printer > consumables > photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="printer > consumables > black photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="printer > consumables > color photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][7]="сеть > порты > последнее изменение";
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
$LANG['plugin_fusinvsnmp']['mapping'][8]="networking > port > number of bytes entered";
$LANG['plugin_fusinvsnmp']['mapping'][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="printer > consumables > waste bin (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="printer > consumables > waste bin (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="printer > consumables > four (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="printer > consumables > four (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="printer > consumables > cleaning module (max ink)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="printer > consumables > cleaning module (remaining ink)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="принтер > порт > тип";
$LANG['plugin_fusinvsnmp']['mapping'][98]="printer > consumables > maintenance kit (max)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="printer > consumables > maintenance kit (remaining)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="networking > port > number of bytes out";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Назначить модель SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Назначить SNMP аунтификацию";

$LANG['plugin_fusinvsnmp']['menu'][10]="Статус инвентаризации сети";
$LANG['plugin_fusinvsnmp']['menu'][2]="Конфигурация IP диапазона";
$LANG['plugin_fusinvsnmp']['menu'][5]="Switchs ports history";
$LANG['plugin_fusinvsnmp']['menu'][6]="Не используемые порты коммутатора";
$LANG['plugin_fusinvsnmp']['menu'][9]="Статус обнаружения";

$LANG['plugin_fusinvsnmp']['mib'][1]="MIB маркеровка";
$LANG['plugin_fusinvsnmp']['mib'][2]="Объект";
$LANG['plugin_fusinvsnmp']['mib'][3]="OID";
$LANG['plugin_fusinvsnmp']['mib'][4]="Добавить OID...";
$LANG['plugin_fusinvsnmp']['mib'][5]="Список OID";
$LANG['plugin_fusinvsnmp']['mib'][6]="Счетчики порта";
$LANG['plugin_fusinvsnmp']['mib'][7]="Динамический порт (.х)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Связанные области";

$LANG['plugin_fusinvsnmp']['model_info'][10]="Иморт модели SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][11]="is_active";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Key of model discovery";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Загрузить правильную модель";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Загрузить правильную SNMP модель";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Mass import of models";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Mass import of models in folder plugins/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Версия SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="SNMP аунтификация";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Модели SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Изменить модель SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Создать модель SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import completed successfully";

$LANG['plugin_fusinvsnmp']['portlogs'][0]="Настройка истории";
$LANG['plugin_fusinvsnmp']['portlogs'][1]="List of fields for which to keep history";
$LANG['plugin_fusinvsnmp']['portlogs'][2]="Retention in days";

$LANG['plugin_fusinvsnmp']['printhistory'][1]="Too datas to display";

$LANG['plugin_fusinvsnmp']['processes'][37]="Всего адресов IP";

$LANG['plugin_fusinvsnmp']['profile'][2]="Настройка";
$LANG['plugin_fusinvsnmp']['profile'][4]="Диапазон IP";
$LANG['plugin_fusinvsnmp']['profile'][5]="Сетевое оборудование SNMP";
$LANG['plugin_fusinvsnmp']['profile'][6]="Принтер SNMP";
$LANG['plugin_fusinvsnmp']['profile'][7]="SNMP модель";
$LANG['plugin_fusinvsnmp']['profile'][8]="Доклады принтеров";
$LANG['plugin_fusinvsnmp']['profile'][9]="Сетевые доклады";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="История и статистика счетчиков притера";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Общее число напечатанных страниц";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Страниц / день";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="История счетчика принтера";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Дата";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Счетчик";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Единица времени";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Добавить принтер";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Удалить принтер";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="день";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="неделя";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="месяц";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="год";
$LANG['plugin_fusinvsnmp']['prt_history'][38]="Принтеры для сравнения";

$LANG['plugin_fusinvsnmp']['report'][0]="Число дней с последней инвенторизации";
$LANG['plugin_fusinvsnmp']['report'][1]="Printed page counter";

$LANG['plugin_fusinvsnmp']['setup'][17]="Плагину FusionInventory SNMP требуется активный плагин FusionInventory до его активации.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Плагину FusionInventory SNMP требуется активный плагин FusionInventory до его удаления.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Converting history port";
$LANG['plugin_fusinvsnmp']['setup'][20]="Moving creation connections history";
$LANG['plugin_fusinvsnmp']['setup'][21]="Moving deleted connections history";

$LANG['plugin_fusinvsnmp']['snmp'][12]="Uptime";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Использование CPU (в %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Использование памяти (в %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Массив портов";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Описание порта";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Число байт получено";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Число байт отправлено";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Number of errors in reception";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Дуплекс";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Последняя инвентаризация";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Данные не доступны";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Number per second";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Community";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Пользователь";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Протокол шифрования для аунтификации ";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Пароль";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Протокол шифрования для данных";

$LANG['plugin_fusinvsnmp']['state'][10]="Devices imported";
$LANG['plugin_fusinvsnmp']['state'][4]="Время начала";
$LANG['plugin_fusinvsnmp']['state'][5]="Время завершения";
$LANG['plugin_fusinvsnmp']['state'][6]="Всего обнаруженных устройств";
$LANG['plugin_fusinvsnmp']['state'][7]="Всего по ошибкам";
$LANG['plugin_fusinvsnmp']['state'][8]="Устройства не импортированы";
$LANG['plugin_fusinvsnmp']['state'][9]="Devices linked";

$LANG['plugin_fusinvsnmp']['stats'][0]="Total counter";
$LANG['plugin_fusinvsnmp']['stats'][1]="Страниц в день";
$LANG['plugin_fusinvsnmp']['stats'][2]="Дисплей";

$LANG['plugin_fusinvsnmp']['task'][15]="Permanent task";
$LANG['plugin_fusinvsnmp']['task'][17]="Тип взаимодействия";
$LANG['plugin_fusinvsnmp']['task'][18]="Create task easily";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="SNMP информация";
$LANG['plugin_fusinvsnmp']['title'][2]="История соединений";
$LANG['plugin_fusinvsnmp']['title'][5]="FusionInventory's locks";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>
