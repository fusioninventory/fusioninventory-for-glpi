<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$title="FusionInventory";
$version="2.3.0";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][1]="SNMP информация";
$LANG['plugin_fusioninventory']['title'][2]="История связей";
$LANG['plugin_fusioninventory']['title'][3]="[Trk] Ошибки";
$LANG['plugin_fusioninventory']['title'][4]="[Trk] Cron";
$LANG['plugin_fusioninventory']['title'][5]="Блокировки FusionInventory";

$LANG['plugin_fusioninventory']['config'][0] = "Интервал инвенторизации (в часах)";
$LANG['plugin_fusioninventory']['config'][1] = "Модули";
$LANG['plugin_fusioninventory']['config'][2] = "Snmp";
$LANG['plugin_fusioninventory']['config'][3] = "Инвентарь";
$LANG['plugin_fusioninventory']['config'][4] = "Обнаружение устройств";
$LANG['plugin_fusioninventory']['config'][5] = "Управление агентами из GLPI";
$LANG['plugin_fusioninventory']['config'][6] = "Функция Wake On Lan (WOL)";
$LANG['plugin_fusioninventory']['config'][7] = "SNMP запрос";

$LANG['plugin_fusioninventory']['profile'][0]="Управление правами";
$LANG['plugin_fusioninventory']['profile'][1]="$title"; //interface
$LANG['plugin_fusioninventory']['profile'][2]="Агенты";
$LANG['plugin_fusioninventory']['profile'][3]="Удалённое управление агентом";
$LANG['plugin_fusioninventory']['profile'][4]="Конфигурация";
$LANG['plugin_fusioninventory']['profile'][5]="WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6]="Неизвестные устройства";
$LANG['plugin_fusioninventory']['profile'][7]="Задачи";

$LANG['plugin_fusioninventory']['setup'][2]="Thanks to put all in root entity (see all)";
$LANG['plugin_fusioninventory']['setup'][3]="Настройка плагина".$title;
$LANG['plugin_fusioninventory']['setup'][4]="Установить плагин $title $version";
$LANG['plugin_fusioninventory']['setup'][5]="Обновить плагин $title до версии $version";
$LANG['plugin_fusioninventory']['setup'][6]="Удалить плагин $title $version";
$LANG['plugin_fusioninventory']['setup'][8]="Внимание, удаление плагина не обратимый шаг.<br> Вы потеряете все ваши данные.";
$LANG['plugin_fusioninventory']['setup'][11]="Инструкции";
$LANG['plugin_fusioninventory']['setup'][12]="ЧАВо";
$LANG['plugin_fusioninventory']['setup'][13]="Проверка модулей PHP";
$LANG['plugin_fusioninventory']['setup'][14]="SNMP расширение для PHP не загружено";
$LANG['plugin_fusioninventory']['setup'][15]="PHP/PECL runkit extension isn't load";
$LANG['plugin_fusioninventory']['setup'][16]="Документация";
$LANG['plugin_fusioninventory']['setup'][17]="Другие плагины FusionInventory (fusinv...) должныбыть удалены перед удалением FusionInventory плагина";

$LANG['plugin_fusioninventory']['functionalities'][0]="Функции";
$LANG['plugin_fusioninventory']['functionalities'][1]="Добавить / Удалить функции";
$LANG['plugin_fusioninventory']['functionalities'][2]="Основная конфигурация";
$LANG['plugin_fusioninventory']['functionalities'][3]="SNMP";
$LANG['plugin_fusioninventory']['functionalities'][4]="Связь";
$LANG['plugin_fusioninventory']['functionalities'][5]="Скрипты сервера";
$LANG['plugin_fusioninventory']['functionalities'][6]="Легенда";
$LANG['plugin_fusioninventory']['functionalities'][7]="Блокируемые поля";
$LANG['plugin_fusioninventory']['functionalities'][8]="Порт Агента";
$LANG['plugin_fusioninventory']['functionalities'][9]="Время хранения в днях";
$LANG['plugin_fusioninventory']['functionalities'][10]="Активация истории изменений";
$LANG['plugin_fusioninventory']['functionalities'][11]="Активация модуля 'Cвязи'";
$LANG['plugin_fusioninventory']['functionalities'][12]="Активация модуля SNMP Сеть";
$LANG['plugin_fusioninventory']['functionalities'][13]="Активация модуля SNMP Переферия";
$LANG['plugin_fusioninventory']['functionalities'][14]="Активация модуля SNMP Телефон";
$LANG['plugin_fusioninventory']['functionalities'][15]="Активация модуля SNMP Принтер";
$LANG['plugin_fusioninventory']['functionalities'][16]="SNMP Аутентификация";
$LANG['plugin_fusioninventory']['functionalities'][17]="База Данных";
$LANG['plugin_fusioninventory']['functionalities'][18]="Файлы";
$LANG['plugin_fusioninventory']['functionalities'][19]="Пожалуйста сконфигурируйте SNMP аутентификацию в настройках плагина";
$LANG['plugin_fusioninventory']['functionalities'][20]="Статус активных устройств";
$LANG['plugin_fusioninventory']['functionalities'][21]="Задержка исторических соединений между данными в днях (0 = infinity)";
$LANG['plugin_fusioninventory']['functionalities'][22]="Retention of the historic changes to the state of ports (0 = infinity)";
$LANG['plugin_fusioninventory']['functionalities'][23]="Retention of history unknown MAC addresses (0 = infinity)";
$LANG['plugin_fusioninventory']['functionalities'][24]="Retention of historical errors SNMP (0 = infinity))";
$LANG['plugin_fusioninventory']['functionalities'][25]="Retention of historical processes scripts (0 = infinity)";
$LANG['plugin_fusioninventory']['functionalities'][26]="GLPI URL для агента";
$LANG['plugin_fusioninventory']['functionalities'][27]="SSL only для агента";
$LANG['plugin_fusioninventory']['functionalities'][28]="Конфигурация истории";
$LANG['plugin_fusioninventory']['functionalities'][29]="Список полей для сохранения в истории";

$LANG['plugin_fusioninventory']['functionalities'][30]="Статус активных устройств";
$LANG['plugin_fusioninventory']['functionalities'][31]="Управление картриджами и расходными материалами";
$LANG['plugin_fusioninventory']['functionalities'][32]="Удалить задачу после";
$LANG['plugin_fusioninventory']['functionalities'][36]="Частота замеров";

$LANG['plugin_fusioninventory']['functionalities'][40]="Конфигурация";
$LANG['plugin_fusioninventory']['functionalities'][41]="Статус активных устройств";
$LANG['plugin_fusioninventory']['functionalities'][42]="Свич";
$LANG['plugin_fusioninventory']['functionalities'][43]="SNMP аутентификация";

$LANG['plugin_fusioninventory']['functionalities'][50]="Количество одновременных процессов сетевого обнаружения";
$LANG['plugin_fusioninventory']['functionalities'][51]="Количество одновременных процессов SNMP запросов";
$LANG['plugin_fusioninventory']['functionalities'][52]="Файл лога активности";
$LANG['plugin_fusioninventory']['functionalities'][53]="Количество одновременных процессов используемых скриптом сервера";

$LANG['plugin_fusioninventory']['functionalities'][60]="Очистить историю";

$LANG['plugin_fusioninventory']['functionalities'][70]="Конфигурация блокируемых полей";
$LANG['plugin_fusioninventory']['functionalities'][71]="неблокируемые поля";
$LANG['plugin_fusioninventory']['functionalities'][72]="Таблицы";
$LANG['plugin_fusioninventory']['functionalities'][73]="Поля";
$LANG['plugin_fusioninventory']['functionalities'][74]="Значения";
$LANG['plugin_fusioninventory']['functionalities'][75]="Блокировки";
$LANG['plugin_fusioninventory']['functionalities'][76]="Нет блокируемых полей";

$LANG['plugin_fusioninventory']['cron'][0]="Automatic reading meter";
$LANG['plugin_fusioninventory']['cron'][1]="Активировать запись";
$LANG['plugin_fusioninventory']['cron'][2]="";
$LANG['plugin_fusioninventory']['cron'][3]="По умолчанию";

$LANG['plugin_fusioninventory']['errors'][0]="Ошибки";
$LANG['plugin_fusioninventory']['errors'][1]="IP";
$LANG['plugin_fusioninventory']['errors'][2]="Описание";
$LANG['plugin_fusioninventory']['errors'][3]="Дата первого обнаружения";
$LANG['plugin_fusioninventory']['errors'][4]="дата последней ошибки";

$LANG['plugin_fusioninventory']['errors'][10]="Inconsistent with the basic GLPI";
$LANG['plugin_fusioninventory']['errors'][11]="Позиция не известна";
$LANG['plugin_fusioninventory']['errors'][12]="Неизвестный IP";

$LANG['plugin_fusioninventory']['errors'][20]="SNMP ошибки";
$LANG['plugin_fusioninventory']['errors'][21]="Невозможно получить информацию";
$LANG['plugin_fusioninventory']['errors'][22]="Не подключённый элемент в";
$LANG['plugin_fusioninventory']['errors'][23]="невозможно определить устройство";

$LANG['plugin_fusioninventory']['errors'][30]="Ошибка записи";
$LANG['plugin_fusioninventory']['errors'][31]="Проблема записи";

$LANG['plugin_fusioninventory']['errors'][50]="GLPI версия не совместима, требуется версия 0.78";

$LANG['plugin_fusioninventory']['errors'][101]="Таймаут";
$LANG['plugin_fusioninventory']['errors'][102]="Не определена модель SNMP";
$LANG['plugin_fusioninventory']['errors'][103]="Нет данных SNMP аутентификации";
$LANG['plugin_fusioninventory']['errors'][104]="Сообщение об ошибке";

$LANG['plugin_fusioninventory']['history'][0] = "Старый";
$LANG['plugin_fusioninventory']['history'][1] = "Новый";
$LANG['plugin_fusioninventory']['history'][2] = "Отключить";
$LANG['plugin_fusioninventory']['history'][3] = "Подключить";

$LANG['plugin_fusioninventory']['prt_history'][0]="История и статистика счётчиков принтеров";

$LANG['plugin_fusioninventory']['prt_history'][10]="Статистика принтера";
$LANG['plugin_fusioninventory']['prt_history'][11]="дня(ей)";
$LANG['plugin_fusioninventory']['prt_history'][12]="Итого отпечатано страниц";
$LANG['plugin_fusioninventory']['prt_history'][13]="Страниц / день";

$LANG['plugin_fusioninventory']['prt_history'][20]="History meter printer";
$LANG['plugin_fusioninventory']['prt_history'][21]="Дата";
$LANG['plugin_fusioninventory']['prt_history'][22]="Meter";

$LANG['plugin_fusioninventory']['prt_history'][30]="Отобразить";
$LANG['plugin_fusioninventory']['prt_history'][31]="Единитца времени";
$LANG['plugin_fusioninventory']['prt_history'][32]="Добавить принтер";
$LANG['plugin_fusioninventory']['prt_history'][33]="Удалить принтер";
$LANG['plugin_fusioninventory']['prt_history'][34]="дни";
$LANG['plugin_fusioninventory']['prt_history'][35]="недели";
$LANG['plugin_fusioninventory']['prt_history'][36]="месяцы";
$LANG['plugin_fusioninventory']['prt_history'][37]="год";

$LANG['plugin_fusioninventory']['cpt_history'][0]="Сессии"; # history sessions
$LANG['plugin_fusioninventory']['cpt_history'][1]="Контакт";
$LANG['plugin_fusioninventory']['cpt_history'][2]="Компьютер";
$LANG['plugin_fusioninventory']['cpt_history'][3]="Пользователь";
$LANG['plugin_fusioninventory']['cpt_history'][4]="Статус";
$LANG['plugin_fusioninventory']['cpt_history'][5]="Дата";

$LANG['plugin_fusioninventory']['type'][1]="Компьютер";
$LANG['plugin_fusioninventory']['type'][2]="Свич";
$LANG['plugin_fusioninventory']['type'][3]="Принтер";

$LANG['plugin_fusioninventory']['rules'][1]="Правила";

$LANG['plugin_fusioninventory']['massiveaction'][1]="Определить SNMP модель";
$LANG['plugin_fusioninventory']['massiveaction'][2]="Определить SNMP аутентификацию";

$LANG['plugin_fusioninventory']['processes'][0]="История запуска скриптов";
$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][2]="Статус";
$LANG['plugin_fusioninventory']['processes'][3]="Количество процессов";
$LANG['plugin_fusioninventory']['processes'][4]="Дата запуска";
$LANG['plugin_fusioninventory']['processes'][5]="Дата окончания";
$LANG['plugin_fusioninventory']['processes'][6]="Опрошенное сетевое устройство";
$LANG['plugin_fusioninventory']['processes'][7]="Опрошенный принтер";
$LANG['plugin_fusioninventory']['processes'][8]="Опрошенный порт";
$LANG['plugin_fusioninventory']['processes'][9]="Ошибки";
$LANG['plugin_fusioninventory']['processes'][10]="Время Скрипта";
$LANG['plugin_fusioninventory']['processes'][11]="добавлены поля";
$LANG['plugin_fusioninventory']['processes'][12]="Ошибки SNMP";
$LANG['plugin_fusioninventory']['processes'][13]="Неизвестный MAC";
$LANG['plugin_fusioninventory']['processes'][14]="Список неизвестных MAC адресов";
$LANG['plugin_fusioninventory']['processes'][15]="Первый PID";
$LANG['plugin_fusioninventory']['processes'][16]="Последний PID";
$LANG['plugin_fusioninventory']['processes'][17]="Дата первого определения";
$LANG['plugin_fusioninventory']['processes'][18]="Дата последнего определения";
$LANG['plugin_fusioninventory']['processes'][19]="История запуска агентов";
$LANG['plugin_fusioninventory']['processes'][20]="Отчёты и Статистика";
$LANG['plugin_fusioninventory']['processes'][21]="Опрошенные устройства";
$LANG['plugin_fusioninventory']['processes'][22]="Ошибки";
$LANG['plugin_fusioninventory']['processes'][23]="Общая продолжительность обнаружения";
$LANG['plugin_fusioninventory']['processes'][24]="Общая продолжительность запроса";
$LANG['plugin_fusioninventory']['processes'][25]="Агент";
$LANG['plugin_fusioninventory']['processes'][26]="Обнаружение";
$LANG['plugin_fusioninventory']['processes'][27]="Запрос";
$LANG['plugin_fusioninventory']['processes'][28]="Ядро";
$LANG['plugin_fusioninventory']['processes'][29]="Threads";
$LANG['plugin_fusioninventory']['processes'][30]="Обнаружено";
$LANG['plugin_fusioninventory']['processes'][31]="Existent";
$LANG['plugin_fusioninventory']['processes'][32]="Импортировано";
$LANG['plugin_fusioninventory']['processes'][33]="Опрошено";
$LANG['plugin_fusioninventory']['processes'][34]="С ошибкой";
$LANG['plugin_fusioninventory']['processes'][35]="Создано подключений";
$LANG['plugin_fusioninventory']['processes'][36]="Удалено подключений";
$LANG['plugin_fusioninventory']['processes'][37]="IP итого";
$LANG['plugin_fusioninventory']['processes'][38]="Process number";

$LANG['plugin_fusioninventory']['state'][0]="Компьютер запущен";
$LANG['plugin_fusioninventory']['state'][1]="Компьютер остановлен";
$LANG['plugin_fusioninventory']['state'][2]="Подключение пользователя";
$LANG['plugin_fusioninventory']['state'][3]="Отключение пользователя";

$LANG['plugin_fusioninventory']['mapping'][1]="сеть > местонахождение";
$LANG['plugin_fusioninventory']['mapping'][2]="сеть > прошивка";
$LANG['plugin_fusioninventory']['mapping'][3]="сеть > uptime";
$LANG['plugin_fusioninventory']['mapping'][4]="сеть > порт > mtu";
$LANG['plugin_fusioninventory']['mapping'][5]="сеть > порт > скорость";
$LANG['plugin_fusioninventory']['mapping'][6]="сеть > порт > internal status";
$LANG['plugin_fusioninventory']['mapping'][7]="сеть > Порты > Последнее изменение";
$LANG['plugin_fusioninventory']['mapping'][8]="сеть > порт > количество байтов принято";
$LANG['plugin_fusioninventory']['mapping'][9]="сеть > порт > количество байтов передано";
$LANG['plugin_fusioninventory']['mapping'][10]="сеть > порт > количество входящих ошибок";
$LANG['plugin_fusioninventory']['mapping'][11]="сеть > порт > количество изходящих ошибок";
$LANG['plugin_fusioninventory']['mapping'][12]="сеть > использование CPU";
$LANG['plugin_fusioninventory']['mapping'][13]="сеть > серийный номер";
$LANG['plugin_fusioninventory']['mapping'][14]="сеть > порт > статус подключения";
$LANG['plugin_fusioninventory']['mapping'][15]="сеть > порт > MAC адресс";
$LANG['plugin_fusioninventory']['mapping'][16]="сеть > порт > имя";
$LANG['plugin_fusioninventory']['mapping'][17]="сеть > модель";
$LANG['plugin_fusioninventory']['mapping'][18]="сеть > порты > тип";
$LANG['plugin_fusioninventory']['mapping'][19]="сеть > VLAN";
$LANG['plugin_fusioninventory']['mapping'][20]="сеть > имя";
$LANG['plugin_fusioninventory']['mapping'][21]="сеть > итого памяти";
$LANG['plugin_fusioninventory']['mapping'][22]="сеть > свободной памяти";
$LANG['plugin_fusioninventory']['mapping'][23]="сеть > порт > описание порта";
$LANG['plugin_fusioninventory']['mapping'][24]="принтер > имя";
$LANG['plugin_fusioninventory']['mapping'][25]="принтер > модель";
$LANG['plugin_fusioninventory']['mapping'][26]="принтер > итого памяти";
$LANG['plugin_fusioninventory']['mapping'][27]="принтер > серийный номер";
$LANG['plugin_fusioninventory']['mapping'][28]="принтер > meter > total number of printed pages";
$LANG['plugin_fusioninventory']['mapping'][29]="принтер > meter > number of printed black and white pages";
$LANG['plugin_fusioninventory']['mapping'][30]="принтер > meter > number of printed color pages";
$LANG['plugin_fusioninventory']['mapping'][31]="принтер > meter > number of printed monochrome pages";
$LANG['plugin_fusioninventory']['mapping'][32]="принтер > meter > number of printed color pages";
$LANG['plugin_fusioninventory']['mapping'][33]="сеть > порт > duplex type";
$LANG['plugin_fusioninventory']['mapping'][34]="принтер > consumables > black cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][35]="принтер > consumables > photo black cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][36]="принтер > consumables > cyan cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][37]="принтер > consumables > yellow cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][38]="принтер > consumables > magenta cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][39]="принтер > consumables > light cyan cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][40]="принтер > consumables > light magenta cartridge (%)";
$LANG['plugin_fusioninventory']['mapping'][41]="принтер > consumables > photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][42]="принтер > consumables > black photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][43]="принтер > consumables > color photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][44]="принтер > consumables > cyan photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][45]="принтер > consumables > yellow photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][46]="принтер > consumables > magenta photoconductor (%)";
$LANG['plugin_fusioninventory']['mapping'][47]="принтер > consumables > black transfer unit (%)";
$LANG['plugin_fusioninventory']['mapping'][48]="принтер > consumables > cyan transfer unit (%)";
$LANG['plugin_fusioninventory']['mapping'][49]="принтер > consumables > yellow transfer unit (%)";
$LANG['plugin_fusioninventory']['mapping'][50]="принтер > consumables > magenta transfer unit (%)";
$LANG['plugin_fusioninventory']['mapping'][51]="принтер > consumables > waste bin (%)";
$LANG['plugin_fusioninventory']['mapping'][52]="принтер > consumables > four (%)";
$LANG['plugin_fusioninventory']['mapping'][53]="принтер > consumables > cleaning module (%)";
$LANG['plugin_fusioninventory']['mapping'][54]="принтер > meter > number of printed duplex pages";
$LANG['plugin_fusioninventory']['mapping'][55]="принтер > meter > nomber of scanned pages";
$LANG['plugin_fusioninventory']['mapping'][56]="принтер > местонахождение";
$LANG['plugin_fusioninventory']['mapping'][57]="принтер > порт > name";
$LANG['plugin_fusioninventory']['mapping'][58]="принтер > порт > MAC address";
$LANG['plugin_fusioninventory']['mapping'][59]="принтер > consumables > black cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][60]="принтер > consumables > black cartridge (remaining ink )";
$LANG['plugin_fusioninventory']['mapping'][61]="принтер > consumables > cyan cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][62]="принтер > consumables > cyan cartridge (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][63]="принтер > consumables > yellow cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][64]="принтер > consumables > yellow cartridge (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][65]="принтер > consumables > magenta cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][66]="принтер > consumables > magenta cartridge (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][67]="принтер > consumables > light cyan cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][68]="принтер > consumables > light cyan cartridge (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][69]="принтер > consumables > light magenta cartridge (max ink)";
$LANG['plugin_fusioninventory']['mapping'][70]="принтер > consumables > light magenta cartridge (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][71]="принтер > consumables > photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][72]="принтер > consumables > photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][73]="принтер > consumables > black photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][74]="принтер > consumables > black photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][75]="принтер > consumables > color photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][76]="принтер > consumables > color photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][77]="принтер > consumables > cyan photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][78]="принтер > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][79]="принтер > consumables > yellow photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][80]="принтер > consumables > yellow photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][81]="принтер > consumables > magenta photoconductor (max ink)";
$LANG['plugin_fusioninventory']['mapping'][82]="принтер > consumables > magenta photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][83]="принтер > consumables > black transfer unit (max ink)";
$LANG['plugin_fusioninventory']['mapping'][84]="принтер > consumables > black transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][85]="принтер > consumables > cyan transfer unit (max ink)";
$LANG['plugin_fusioninventory']['mapping'][86]="принтер > consumables > cyan transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][87]="принтер > consumables > yellow transfer unit (max ink)";
$LANG['plugin_fusioninventory']['mapping'][88]="принтер > consumables > yellow transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][89]="принтер > consumables > magenta transfer unit (max ink)";
$LANG['plugin_fusioninventory']['mapping'][90]="принтер > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][91]="принтер > consumables > waste bin (max ink)";
$LANG['plugin_fusioninventory']['mapping'][92]="принтер > consumables > waste bin (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][93]="принтер > consumables > four (max ink)";
$LANG['plugin_fusioninventory']['mapping'][94]="принтер > consumables > four (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][95]="принтер > consumables > cleaning module (max ink)";
$LANG['plugin_fusioninventory']['mapping'][96]="принтер > consumables > cleaning module (remaining ink)";
$LANG['plugin_fusioninventory']['mapping'][97]="принтер > порт > тип";
$LANG['plugin_fusioninventory']['mapping'][98]="принтер > consumables > Maintenance kit (max)";
$LANG['plugin_fusioninventory']['mapping'][99]="принтер > consumables > Maintenance kit (remaining)";
$LANG['plugin_fusioninventory']['mapping'][400]="принтер > consumables > Maintenance kit (%)";
$LANG['plugin_fusioninventory']['mapping'][401]="сеть > CPU user";
$LANG['plugin_fusioninventory']['mapping'][402]="сеть > CPU system";
$LANG['plugin_fusioninventory']['mapping'][403]="сеть > контакт";
$LANG['plugin_fusioninventory']['mapping'][404]="сеть > коментарии";
$LANG['plugin_fusioninventory']['mapping'][405]="принтер > контакт";
$LANG['plugin_fusioninventory']['mapping'][406]="принтер > коментарии";
$LANG['plugin_fusioninventory']['mapping'][407]="принтер > порт > IP адрес";
$LANG['plugin_fusioninventory']['mapping'][408]="сеть > порт > numÃ©ro index";
$LANG['plugin_fusioninventory']['mapping'][409]="сеть > Адресс CDP";
$LANG['plugin_fusioninventory']['mapping'][410]="сеть > Порт CDP";
$LANG['plugin_fusioninventory']['mapping'][411]="сеть > порт > trunk/tagged";
$LANG['plugin_fusioninventory']['mapping'][412]="сеть > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusioninventory']['mapping'][413]="сеть > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusioninventory']['mapping'][414]="сеть > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusioninventory']['mapping'][415]="сеть > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusioninventory']['mapping'][416]="принтер > порт > index number";
$LANG['plugin_fusioninventory']['mapping'][417]="сеть > MAC адрес";
$LANG['plugin_fusioninventory']['mapping'][418]="принтер > Инвентаризационный номер";
$LANG['plugin_fusioninventory']['mapping'][419]="сеть > Инвентаризационный номер";
$LANG['plugin_fusioninventory']['mapping'][420]="принтер > manufacturer";
$LANG['plugin_fusioninventory']['mapping'][421]="сеть > IP адрес";
$LANG['plugin_fusioninventory']['mapping'][422]="сеть > ПортVlanИндекс";
$LANG['plugin_fusioninventory']['mapping'][423]="принтер > meter > total number of printed pages (print)";
$LANG['plugin_fusioninventory']['mapping'][424]="принтер > meter > number of printed black and white pages (print)";
$LANG['plugin_fusioninventory']['mapping'][425]="принтер > meter > number of printed color pages (print)";
$LANG['plugin_fusioninventory']['mapping'][426]="принтер > meter > total number of printed pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][427]="принтер > meter > number of printed black and white pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][428]="принтер > meter > number of printed color pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][429]="принтер > meter > total number of printed pages (fax)";
$LANG['plugin_fusioninventory']['mapping'][430]="сеть > порт > vlan";


$LANG['plugin_fusioninventory']['mapping'][101]="";
$LANG['plugin_fusioninventory']['mapping'][102]="";
$LANG['plugin_fusioninventory']['mapping'][103]="";
$LANG['plugin_fusioninventory']['mapping'][104]="MTU";
$LANG['plugin_fusioninventory']['mapping'][105]="Скорость";
$LANG['plugin_fusioninventory']['mapping'][106]="Внутренний статус";
$LANG['plugin_fusioninventory']['mapping'][107]="Последнее изменение";
$LANG['plugin_fusioninventory']['mapping'][108]="Количество полученных байтов";
$LANG['plugin_fusioninventory']['mapping'][109]="Количество отправленных байтов";
$LANG['plugin_fusioninventory']['mapping'][110]="Количество ошибок получения";
$LANG['plugin_fusioninventory']['mapping'][111]="Количество ошибок отправки";
$LANG['plugin_fusioninventory']['mapping'][112]="Использование CPU";
$LANG['plugin_fusioninventory']['mapping'][113]="";
$LANG['plugin_fusioninventory']['mapping'][114]="Подключение";
$LANG['plugin_fusioninventory']['mapping'][115]="Внутренний MAC адрес";
$LANG['plugin_fusioninventory']['mapping'][116]="Имя";
$LANG['plugin_fusioninventory']['mapping'][117]="Модель";
$LANG['plugin_fusioninventory']['mapping'][118]="Тип";
$LANG['plugin_fusioninventory']['mapping'][119]="VLAN";
$LANG['plugin_fusioninventory']['mapping'][128]="Общее количество отпечатанных страниц";
$LANG['plugin_fusioninventory']['mapping'][129]="Number of printed black and white pages";
$LANG['plugin_fusioninventory']['mapping'][130]="Number of printed color pages";
$LANG['plugin_fusioninventory']['mapping'][131]="Number of printed monochrome pages";
$LANG['plugin_fusioninventory']['mapping'][132]="Number of printed color pages";
$LANG['plugin_fusioninventory']['mapping'][134]="Чёрный картридж";
$LANG['plugin_fusioninventory']['mapping'][135]="Чёрный фото-картридж";
$LANG['plugin_fusioninventory']['mapping'][136]="Циановый картридж";
$LANG['plugin_fusioninventory']['mapping'][137]="Жёлтый картридж";
$LANG['plugin_fusioninventory']['mapping'][138]="Фиолетовый картридж";
$LANG['plugin_fusioninventory']['mapping'][139]="Светло-голубой картридж";
$LANG['plugin_fusioninventory']['mapping'][140]="Светло-фиолетовый картридж";
$LANG['plugin_fusioninventory']['mapping'][141]="Photoconductor";
$LANG['plugin_fusioninventory']['mapping'][142]="Black photoconductor";
$LANG['plugin_fusioninventory']['mapping'][143]="Color photoconductor";
$LANG['plugin_fusioninventory']['mapping'][144]="Cyan photoconductor";
$LANG['plugin_fusioninventory']['mapping'][145]="Yellow photoconductor";
$LANG['plugin_fusioninventory']['mapping'][146]="Magenta photoconductor";
$LANG['plugin_fusioninventory']['mapping'][147]="Black transfer unit";
$LANG['plugin_fusioninventory']['mapping'][148]="Cyan transfer unit";
$LANG['plugin_fusioninventory']['mapping'][149]="Yellow transfer unit";
$LANG['plugin_fusioninventory']['mapping'][150]="Magenta transfer unit";
$LANG['plugin_fusioninventory']['mapping'][151]="Waste bin";
$LANG['plugin_fusioninventory']['mapping'][152]="Four";
$LANG['plugin_fusioninventory']['mapping'][153]="Cleaning module";
$LANG['plugin_fusioninventory']['mapping'][154]="Number of pages printed duplex";
$LANG['plugin_fusioninventory']['mapping'][155]="Number of scanned pages";
$LANG['plugin_fusioninventory']['mapping'][156]="Maintenance kit";
$LANG['plugin_fusioninventory']['mapping'][157]="Black toner";
$LANG['plugin_fusioninventory']['mapping'][158]="Cyan toner";
$LANG['plugin_fusioninventory']['mapping'][159]="Magenta toner";
$LANG['plugin_fusioninventory']['mapping'][160]="Yellow toner";
$LANG['plugin_fusioninventory']['mapping'][161]="Black drum";
$LANG['plugin_fusioninventory']['mapping'][162]="Cyan drum";
$LANG['plugin_fusioninventory']['mapping'][163]="Magenta drum";
$LANG['plugin_fusioninventory']['mapping'][164]="Yellow drum";
$LANG['plugin_fusioninventory']['mapping'][165]="Many informations grouped";
$LANG['plugin_fusioninventory']['mapping'][166]="Black toner 2";
$LANG['plugin_fusioninventory']['mapping'][1423]="Total number of printed pages (print)";
$LANG['plugin_fusioninventory']['mapping'][1424]="Number of printed black and white pages (print)";
$LANG['plugin_fusioninventory']['mapping'][1425]="Number of printed color pages (print)";
$LANG['plugin_fusioninventory']['mapping'][1426]="Total number of printed pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][1427]="Number of printed black and white pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][1428]="Number of printed color pages (copy)";
$LANG['plugin_fusioninventory']['mapping'][1429]="Total number of printed pages (fax)";


$LANG['plugin_fusioninventory']['printer'][0]="страниц";

$LANG['plugin_fusioninventory']['menu'][0]="Информация об обнаруженных устройствах";
$LANG['plugin_fusioninventory']['menu'][1]="Управление агентами";
$LANG['plugin_fusioninventory']['menu'][2]="Настройка области IP";
$LANG['plugin_fusioninventory']['menu'][3]="Меню";
$LANG['plugin_fusioninventory']['menu'][4]="Неизвестное устройство";
$LANG['plugin_fusioninventory']['menu'][5]="История портов свича";
$LANG['plugin_fusioninventory']['menu'][6]="Неиспользуемые порты свича";
$LANG['plugin_fusioninventory']['menu'][7]="Запущенные работы";

$LANG['plugin_fusioninventory']['buttons'][0]="Обнаружить";

$LANG['plugin_fusioninventory']['discovery'][0]="Область обнаружения IP";
$LANG['plugin_fusioninventory']['discovery'][1]="Обнаруженные устройства";
$LANG['plugin_fusioninventory']['discovery'][2]="Активировать в скрипте автоматически";
$LANG['plugin_fusioninventory']['discovery'][3]="Обнаружение";
$LANG['plugin_fusioninventory']['discovery'][4]="Серийный номер";
$LANG['plugin_fusioninventory']['discovery'][5]="Количество импортированных устройств";
$LANG['plugin_fusioninventory']['discovery'][6]="Главный критерий существования";
$LANG['plugin_fusioninventory']['discovery'][7]="Вторичный критерий существования ";
$LANG['plugin_fusioninventory']['discovery'][8]="Если по первому критерию устройство возвращает пустое значение поля, будет использовано второе значение.";
$LANG['plugin_fusioninventory']['discovery'][9]="Количество устройств не импортированных по причине не определённого типа";

$LANG['plugin_fusioninventory']['agents'][0]="SNMP Агент";
$LANG['plugin_fusioninventory']['agents'][2]="Number of threads used by core for querying devices";
$LANG['plugin_fusioninventory']['agents'][3]="Number of threads used by core for network discovery";
$LANG['plugin_fusioninventory']['agents'][4]="Последняя связь";
$LANG['plugin_fusioninventory']['agents'][5]="Версия агента";
$LANG['plugin_fusioninventory']['agents'][6]="Oтключить";
$LANG['plugin_fusioninventory']['agents'][7]="Экспортировать конфигурацию агента";
$LANG['plugin_fusioninventory']['agents'][9]="Углублённые настройки";
$LANG['plugin_fusioninventory']['agents'][12]="Агент обнаружения";
$LANG['plugin_fusioninventory']['agents'][13]="Опросить Агента";
$LANG['plugin_fusioninventory']['agents'][14]="Действия Агента";
$LANG['plugin_fusioninventory']['agents'][15]="Статус Агента";
$LANG['plugin_fusioninventory']['agents'][16]="Инициализирован";
$LANG['plugin_fusioninventory']['agents'][17]="Агент запущен";
$LANG['plugin_fusioninventory']['agents'][18]="Инвенторизация прошла";
$LANG['plugin_fusioninventory']['agents'][19]="Инвенторизация была отправлена на сервер OCS";
$LANG['plugin_fusioninventory']['agents'][20]="Запущена инвенторизация между OCS и GLPI";
$LANG['plugin_fusioninventory']['agents'][21]="Инвенторизация прекращена";
$LANG['plugin_fusioninventory']['agents'][22]="Ждите";
$LANG['plugin_fusioninventory']['agents'][23]="Линк Компьютера";
$LANG['plugin_fusioninventory']['agents'][24]="Токен";
$LANG['plugin_fusioninventory']['agents'][25]="Версия";
$LANG['plugin_fusioninventory']['agents'][26]="Управление Агентами";
$LANG['plugin_fusioninventory']['agents'][27]="Модули Агентов";
$LANG['plugin_fusioninventory']['agents'][28]="Агент";
$LANG['plugin_fusioninventory']['agents'][29]="Активация модулей";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible to communicate with agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Force inventory";
$LANG['plugin_fusioninventory']['agents'][32]="Auto managenement dynamic of agents";

$LANG['plugin_fusioninventory']['unknown'][0]="DNS Имя";
$LANG['plugin_fusioninventory']['unknown'][1]="Имя сетевого порта";
$LANG['plugin_fusioninventory']['unknown'][2]="Подтверждённые устройства";
$LANG['plugin_fusioninventory']['unknown'][3]="Обнаружено агентом";
$LANG['plugin_fusioninventory']['unknown'][4]="Сетевой хаб";
$LANG['plugin_fusioninventory']['unknown'][5]="Импортировано с неизвестного устройства (FusionInventory)";

$LANG['plugin_fusioninventory']['task'][0]="Задача";
$LANG['plugin_fusioninventory']['task'][1]="Менеджмент Задач";
$LANG['plugin_fusioninventory']['task'][2]="Действие";
$LANG['plugin_fusioninventory']['task'][3]="Единица";
$LANG['plugin_fusioninventory']['task'][4]="Get now informations";
$LANG['plugin_fusioninventory']['task'][5]="Выбрать агента OCS";
$LANG['plugin_fusioninventory']['task'][6]="Получить состояние";
$LANG['plugin_fusioninventory']['task'][7]="Состояние";
$LANG['plugin_fusioninventory']['task'][8]="Готово";
$LANG['plugin_fusioninventory']['task'][9]="Не отвечает";
$LANG['plugin_fusioninventory']['task'][10]="Запуск... не доступен";
$LANG['plugin_fusioninventory']['task'][11]="Агент получил задание и начал работу";
$LANG['plugin_fusioninventory']['task'][12]="Разбудить агента";
$LANG['plugin_fusioninventory']['task'][13]="Агент(ы) не доступен";
$LANG['plugin_fusioninventory']['task'][14]="До даты";
$LANG['plugin_fusioninventory']['task'][16]="Новое действие";
$LANG['plugin_fusioninventory']['task'][17]="Частота";
$LANG['plugin_fusioninventory']['task'][18]="Задачи";
$LANG['plugin_fusioninventory']['task'][19]="Запущеные задачи";
$LANG['plugin_fusioninventory']['task'][20]="Законченые задачи";
$LANG['plugin_fusioninventory']['task'][21]="Действует на этот материал ";
$LANG['plugin_fusioninventory']['task'][22]="Only planified tasks";
$LANG['plugin_fusioninventory']['task'][23]="Run on";
$LANG['plugin_fusioninventory']['task'][24]="Number of trials";
$LANG['plugin_fusioninventory']['task'][25]="Time between 2 trials (in minutes)";
$LANG['plugin_fusioninventory']['task'][26]="Module";
$LANG['plugin_fusioninventory']['task'][27]="Definition";
$LANG['plugin_fusioninventory']['task'][28]="Action";
$LANG['plugin_fusioninventory']['task'][29]="Type";
$LANG['plugin_fusioninventory']['task'][30]="Selection";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Начато";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Ошибка / Перепланировано";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Ошибка";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="неизвестно";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Running";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Prepared";

$LANG['plugin_fusioninventory']['update'][0]="your history table have more than 300 000 entries, you must run this command to finish update : ";

$LANG['plugin_fusioninventory']['wakeonlan'][0]="Выбор компьютеров";
$LANG['plugin_fusioninventory']['wakeonlan'][1]="Выбор динамических групп";
$LANG['plugin_fusioninventory']['wakeonlan'][2]="Выбор простых групп";
$LANG['plugin_fusioninventory']['wakeonlan'][3]="Devices of another job of this task";

$LANG['plugin_fusioninventory']['xml'][0]="XML FusionInventory";

?>