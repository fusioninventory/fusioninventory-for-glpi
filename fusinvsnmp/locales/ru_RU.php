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


$LANG['plugin_fusinvsnmp']['agents'][24]="Threads number";
$LANG['plugin_fusinvsnmp']['agents'][25]="Агент (ы)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Версия модуля Netdiscovery";
$LANG['plugin_fusinvsnmp']['agents'][27]="Версия модуля Snmpquery";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="devices queried";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="Устройств найдено";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="SNMP equipment definition isn't up to date on agent. For the next run, it will get new version from server.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="Добавить элемент";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="Обновить элемент";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="Инфенторизация запущена";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Подробно";

$LANG['plugin_fusinvsnmp']['config'][10]="Ports types to import (for network equipments)";
$LANG['plugin_fusinvsnmp']['config'][3]="Сетевая инвентаризация (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Сетевое обнаружение";
$LANG['plugin_fusinvsnmp']['config'][8]="Никогда";
$LANG['plugin_fusinvsnmp']['config'][9]="Всегда";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Автоматическое создание моделей";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Генерация файла обнаружения";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Удаление не используемых моделей";
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
$LANG['plugin_fusinvsnmp']['mapping'][10]="сеть > порт > число полученных ошибок";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Число входящих ошибок";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Число исходящих ошибок";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Загрузка CPU";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Соединение";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Внутренний MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Имя";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Модель";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Тип";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="сеть > порт > число отправленных ошибок";
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
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Общее число напечатанных страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Число напечатанных черных и белых страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Число напечатанных цветных страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Общее число напечатанных страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Число напечатанных черных и белых страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Число напечатанных цветных страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Общее число напечатанных страниц (факс)";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Черный фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Общее число напечатанных больших страниц";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Цветной фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Голубой фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Желтый фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Пурпурный фотокондуктор";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Черный transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Голубой transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Желтый transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][14]="сеть > порт > статус соединения";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Пурпурный transfer unit";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Бункер отработки";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Four";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Чистящий модуль";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Число напечатанных двухсторонних страниц";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Число сканированных страниц";
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
$LANG['plugin_fusinvsnmp']['mapping'][28]="принтер > счетчик > число напечатанных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][29]="принтер > счетчик > число напечатанных черных и белых страниц";
$LANG['plugin_fusinvsnmp']['mapping'][2]="сеть > прошивка";
$LANG['plugin_fusinvsnmp']['mapping'][30]="принтер > счетчик > число напечатанных цветных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][31]="принтер > счетчик > число напечатанных черно-белых страниц";
$LANG['plugin_fusinvsnmp']['mapping'][33]="сеть > порт > тип дуплекса";
$LANG['plugin_fusinvsnmp']['mapping'][34]="принтер > расходные материалы > черный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="принтер > расходные материалы > черный фото картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="принтер > расходные материалы > голубой картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="принтер > расходные материалы > желтый картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="принтер > расходные материалы > пурпурный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="принтер > расходные материалы > светло голубой картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="сеть > доступность";
$LANG['plugin_fusinvsnmp']['mapping'][400]="принтер > счетчик > maintenance kit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="сеть > CPU user";
$LANG['plugin_fusinvsnmp']['mapping'][402]="сеть > CPU системы";
$LANG['plugin_fusinvsnmp']['mapping'][403]="сеть > контакты";
$LANG['plugin_fusinvsnmp']['mapping'][404]="сеть > коментарии";
$LANG['plugin_fusinvsnmp']['mapping'][405]="принтер > контакты";
$LANG['plugin_fusinvsnmp']['mapping'][406]="принтер > комментарии";
$LANG['plugin_fusinvsnmp']['mapping'][407]="принтер > порт > IP адрес";
$LANG['plugin_fusinvsnmp']['mapping'][408]="сеть > порт > номер индекса";
$LANG['plugin_fusinvsnmp']['mapping'][409]="сеть > адрес CDP";
$LANG['plugin_fusinvsnmp']['mapping'][40]="принтер > расходные материалы > светло пурпурный картридж (%)";
$LANG['plugin_fusinvsnmp']['mapping'][410]="сеть > порт CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="сеть > порт > trunk/tagged";
$LANG['plugin_fusinvsnmp']['mapping'][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="принтер > порт > номер индекса";
$LANG['plugin_fusinvsnmp']['mapping'][417]="сеть > MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][418]="принтер > инвентарный номер";
$LANG['plugin_fusinvsnmp']['mapping'][419]="сеть > инвентарный номер";
$LANG['plugin_fusinvsnmp']['mapping'][41]="принтер > расходные материалы > фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="принтер > производитель";
$LANG['plugin_fusinvsnmp']['mapping'][421]="сеть > IP адрес";
$LANG['plugin_fusinvsnmp']['mapping'][422]="сеть > PVID (порт VLAN ID)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="принтер > счетчик > общее число напечатанных страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="принтер > счетчик > число напечатанных черных и белых страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="принтер > счетчик > число напечатанных цветных страниц (принтер)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="принтер > счетчик > число напечатанных страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="принтер > счетчик > число напечатанных черных и белых страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="принтер > счетчик > число напечатанных цветных страниц (копир)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="принтер > счетчик > число напечатанных страниц (факс)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="принтер > расходные материалы > черный фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="сеть > порт > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][435]="networking > CDP remote sysdescr";
$LANG['plugin_fusinvsnmp']['mapping'][436]="сеть > CDP удаленный id";
$LANG['plugin_fusinvsnmp']['mapping'][437]="networking > CDP remote model device";
$LANG['plugin_fusinvsnmp']['mapping'][438]="сеть > LLDP удаленный sysdescr";
$LANG['plugin_fusinvsnmp']['mapping'][439]="сеть > LLDP удаленный id";
$LANG['plugin_fusinvsnmp']['mapping'][43]="принтер > расходные материалы > цветной фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="сеть > LLDP описание удаленного порта";
$LANG['plugin_fusinvsnmp']['mapping'][44]="принтер > расходные материалы > голубой фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="принтер > расходные материалы > желтый фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="принтер > расходные материалы > пурпурный фотокондуктор (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="принтер > расходные материалы > черный transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="принтер > расходные материалы > голубой transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="принтер > расходные материалы > желтый transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="сеть > порт > mtu";
$LANG['plugin_fusinvsnmp']['mapping'][50]="принтер > расходные материалы > пурпурный transfer unit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="принтер > расходные материалы > бункер сбора тонера (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="принтер > расходные материалы > four (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="принтер > расходные материалы > чистящий модуль (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="принтер > расходные материалы > число напечатанных двухсторонних страниц";
$LANG['plugin_fusinvsnmp']['mapping'][55]="принтер > расходные материалы > число сканированных страниц";
$LANG['plugin_fusinvsnmp']['mapping'][56]="принтер > местоположение";
$LANG['plugin_fusinvsnmp']['mapping'][57]="принтер > порт > имя";
$LANG['plugin_fusinvsnmp']['mapping'][58]="принтер > порт > MAC адрес";
$LANG['plugin_fusinvsnmp']['mapping'][59]="принтер > расходные материалы > черный картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][5]="сеть > порт > скорость";
$LANG['plugin_fusinvsnmp']['mapping'][60]="принтер > расходные материалы > черный картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="принтер > расходные материалы > голубой картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="принтер > расходные материалы > голубой картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="принтер > расходные материалы > желтый картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="принтер > расходные материалы > желтый картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="принтер > расходные материалы > пурпурный картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="принтер > расходные материалы > пурпурный картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="принтер > расходные материалы > светло-голубой картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="принтер > расходные материалы > светло-голубой картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="принтер > расходные материалы > светло-пурпурный картридж (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][6]="сеть > порт > внутренний статус";
$LANG['plugin_fusinvsnmp']['mapping'][70]="принтер > расходные материалы > светло-пурпурный картридж (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="принтер > расходные материалы > фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="принтер > расходные материалы > фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="принтер > расходные материалы > черный фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="принтер > расходные материалы > черный фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="принтер > расходные материалы > цветной фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="принтер > расходные материалы > цветной фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="принтер > расходные материалы > голубой фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="принтер > расходные материалы > голубой фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="принтер > расходные материалы > желтый фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][7]="сеть > порты > последнее изменение";
$LANG['plugin_fusinvsnmp']['mapping'][80]="принтер > расходные материалы > желтый фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="принтер > расходные материалы > пурпурный фотокондуктор (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="принтер > расходные материалы > пурпурный фотокондуктор (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="принтер > расходные материалы > черный transfer unit (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="принтер > расходные материалы > черный transfer unit (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="принтер > расходные материалы > голубой transfer unit (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="принтер > расходные материалы > голубой transfer unit (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="принтер > расходные материалы > желтый transfer unit (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="принтер > расходные материалы > желтый transfer unit (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="принтер > расходные материалы > пурпурный transfer unit (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][8]="сеть > порт > число полученных байт";
$LANG['plugin_fusinvsnmp']['mapping'][90]="принтер > расходные материалы > пурпурный transfer unit (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="принтер > расходные материалы > бункер сбора тонера (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="принтер > расходные материалы > бункер сбора тонера (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="принтер > расходные материалы > four  (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="принтер > расходные материалы > four  (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="принтер > расходные материалы > чистящий модуль (макс чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="принтер > расходные материалы > чистящий модуль (осталось чернил)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="принтер > порт > тип";
$LANG['plugin_fusinvsnmp']['mapping'][98]="принтер > счетчик > maintenance kit (макс)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="принтер > счетчик > maintenance kit (осталось)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="сеть > порт > число отправленных байт";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Назначить модель SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Назначить SNMP аутентификацию";

$LANG['plugin_fusinvsnmp']['menu'][10]="Статус инвентаризации сети";
$LANG['plugin_fusinvsnmp']['menu'][2]="Конфигурация IP диапазона";
$LANG['plugin_fusinvsnmp']['menu'][5]="Switchs ports history";
$LANG['plugin_fusinvsnmp']['menu'][6]="Не используемые порты коммутатора";
$LANG['plugin_fusinvsnmp']['menu'][9]="Статус обнаружения";

$LANG['plugin_fusinvsnmp']['mib'][1]="MIB маркировка";
$LANG['plugin_fusinvsnmp']['mib'][2]="Объект";
$LANG['plugin_fusinvsnmp']['mib'][3]="OID";
$LANG['plugin_fusinvsnmp']['mib'][4]="Добавить OID...";
$LANG['plugin_fusinvsnmp']['mib'][5]="Список OID";
$LANG['plugin_fusinvsnmp']['mib'][6]="Счетчики порта";
$LANG['plugin_fusinvsnmp']['mib'][7]="Динамический порт (.х)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Связанные области";

$LANG['plugin_fusinvsnmp']['model_info'][10]="Импорт модели SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][11]="Активный";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Key of model discovery";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Загрузить правильную модель";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Загрузить правильную SNMP модель";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Mass import of models";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Mass import of models in folder plugins/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Версия SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="SNMP аутентификация";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Модели SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Изменить модель SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Создать модель SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Импорт завершен успешно";

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
$LANG['plugin_fusinvsnmp']['profile'][8]="Отчет о принтерах";
$LANG['plugin_fusinvsnmp']['profile'][9]="Отчет о сети";

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

$LANG['plugin_fusinvsnmp']['snmp'][12]="Доступность";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Использование CPU (в %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Использование памяти (в %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Массив портов";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Описание порта";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Число байт получено";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Число байт отправлено";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Число ошибок в получении";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Дуплекс";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Последняя инвентаризация";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Данные не доступны";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Число в секунду";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Community";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Пользователь";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Протокол шифрования для аунтификации";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Пароль";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Протокол шифрования для данных";

$LANG['plugin_fusinvsnmp']['state'][10]="Импортированных устройств";
$LANG['plugin_fusinvsnmp']['state'][4]="Время начала";
$LANG['plugin_fusinvsnmp']['state'][5]="Время завершения";
$LANG['plugin_fusinvsnmp']['state'][6]="Всего обнаруженных устройств";
$LANG['plugin_fusinvsnmp']['state'][7]="Всего по ошибкам";
$LANG['plugin_fusinvsnmp']['state'][8]="Устройства не импортированы";
$LANG['plugin_fusinvsnmp']['state'][9]="Соединенных устройств";

$LANG['plugin_fusinvsnmp']['stats'][0]="Всего счетчиков";
$LANG['plugin_fusinvsnmp']['stats'][1]="Страниц в день";
$LANG['plugin_fusinvsnmp']['stats'][2]="Дисплей";

$LANG['plugin_fusinvsnmp']['task'][15]="Permanent task";
$LANG['plugin_fusinvsnmp']['task'][17]="Тип взаимодействия";
$LANG['plugin_fusinvsnmp']['task'][18]="Create task easily";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="SNMP информация";
$LANG['plugin_fusinvsnmp']['title'][2]="История соединений";
$LANG['plugin_fusinvsnmp']['title'][5]="Блокировки FusionInventory";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>