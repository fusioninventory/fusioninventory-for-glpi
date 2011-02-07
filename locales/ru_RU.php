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
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory";
$version="2.3.0";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][5]="Блокировки FusionInventory";

$LANG['plugin_fusioninventory']['config'][0] = "Интервал инвенторизации (в часах)";

$LANG['plugin_fusioninventory']['profile'][0]="Управление правами";
$LANG['plugin_fusioninventory']['profile'][2]="Агенты";
$LANG['plugin_fusioninventory']['profile'][3]="Удалённое управление агентом";
$LANG['plugin_fusioninventory']['profile'][4]="Конфигурация";
$LANG['plugin_fusioninventory']['profile'][5]="WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6]="Неизвестные устройства";
$LANG['plugin_fusioninventory']['profile'][7]="Задачи";

$LANG['plugin_fusioninventory']['setup'][16]="Документация";
$LANG['plugin_fusioninventory']['setup'][17]="Другие плагины FusionInventory (fusinv...) должныбыть удалены перед удалением FusionInventory плагина";

$LANG['plugin_fusioninventory']['functionalities'][0]="Функции";
$LANG['plugin_fusioninventory']['functionalities'][2]="Основная конфигурация";
$LANG['plugin_fusioninventory']['functionalities'][6]="Легенда";
$LANG['plugin_fusioninventory']['functionalities'][8]="Порт Агента";
$LANG['plugin_fusioninventory']['functionalities'][9]="Время хранения в днях";
$LANG['plugin_fusioninventory']['functionalities'][16]="SNMP Аутентификация";
$LANG['plugin_fusioninventory']['functionalities'][17]="База Данных";
$LANG['plugin_fusioninventory']['functionalities'][18]="Файлы";
$LANG['plugin_fusioninventory']['functionalities'][19]="Пожалуйста сконфигурируйте SNMP аутентификацию в настройках плагина";
$LANG['plugin_fusioninventory']['functionalities'][27]="SSL only для агента";
$LANG['plugin_fusioninventory']['functionalities'][29]="Список полей для сохранения в истории";
$LANG['plugin_fusioninventory']['functionalities'][32]="Удалить задачу после";
$LANG['plugin_fusioninventory']['functionalities'][60]="Очистить историю";
$LANG['plugin_fusioninventory']['functionalities'][73]="Поля";
$LANG['plugin_fusioninventory']['functionalities'][74]="Значения";
$LANG['plugin_fusioninventory']['functionalities'][75]="Блокировки";

$LANG['plugin_fusioninventory']['errors'][22]="Не подключённый элемент в";
$LANG['plugin_fusioninventory']['errors'][50]="GLPI версия не совместима, требуется версия 0.78";

$LANG['plugin_fusioninventory']['rules'][2]="Equipment import and link rules";
$LANG['plugin_fusioninventory']['rules'][3]="Search GLPI equipment with the status";
$LANG['plugin_fusioninventory']['rules'][4]="Destination of equipment entity";
$LANG['plugin_fusioninventory']['rules'][5]="FusionInventory link";
$LANG['plugin_fusioninventory']['rules'][6] = "Link if possible, else not import";
$LANG['plugin_fusioninventory']['rules'][7] = "Link if possible, else import";
$LANG['plugin_fusioninventory']['rules'][8] = "Send";
$LANG['plugin_fusioninventory']['rules'][9]  = "exist";
$LANG['plugin_fusioninventory']['rules'][10]  = "not exist";
$LANG['plugin_fusioninventory']['rules'][11] = "in present in GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "is empty";
$LANG['plugin_fusioninventory']['rules'][13] = "Hard disk serial number";
$LANG['plugin_fusioninventory']['rules'][14] = "Partition serial number";
$LANG['plugin_fusioninventory']['rules'][15] = "uuid";
$LANG['plugin_fusioninventory']['rules'][16] = "FusionInventory tag";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Equipment to import";

$LANG['plugin_fusioninventory']['choice'][0] = "No";
$LANG['plugin_fusioninventory']['choice'][1] = "Yes";
$LANG['plugin_fusioninventory']['choice'][2] = "or";
$LANG['plugin_fusioninventory']['choice'][3] = "and";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Process number";

$LANG['plugin_fusioninventory']['menu'][1]="Управление агентами";
$LANG['plugin_fusioninventory']['menu'][3]="Меню";
$LANG['plugin_fusioninventory']['menu'][4]="Неизвестное устройство";
$LANG['plugin_fusioninventory']['menu'][7]="Запущенные работы";

$LANG['plugin_fusioninventory']['discovery'][5]="Количество импортированных устройств";
$LANG['plugin_fusioninventory']['discovery'][9]="Количество устройств не импортированных по причине не определённого типа";

$LANG['plugin_fusioninventory']['agents'][4]="Последняя связь";
$LANG['plugin_fusioninventory']['agents'][6]="Oтключить";
$LANG['plugin_fusioninventory']['agents'][15]="Статус Агента";
$LANG['plugin_fusioninventory']['agents'][17]="Агент запущен";
$LANG['plugin_fusioninventory']['agents'][22]="Ждите";
$LANG['plugin_fusioninventory']['agents'][23]="Линк Компьютера";
$LANG['plugin_fusioninventory']['agents'][24]="Токен";
$LANG['plugin_fusioninventory']['agents'][25]="Версия";
$LANG['plugin_fusioninventory']['agents'][27]="Модули Агентов";
$LANG['plugin_fusioninventory']['agents'][28]="Агент";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible to communicate with agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Force inventory";
$LANG['plugin_fusioninventory']['agents'][32]="Auto managenement dynamic of agents";
$LANG['plugin_fusioninventory']['agents'][33]="Auto managenement dynamic of agents (same subnet)";
$LANG['plugin_fusioninventory']['agents'][34]="Activation (by default)";

$LANG['plugin_fusioninventory']['unknown'][2]="Подтверждённые устройства";
$LANG['plugin_fusioninventory']['unknown'][4]="Сетевой хаб";

$LANG['plugin_fusioninventory']['task'][0]="Задача";
$LANG['plugin_fusioninventory']['task'][1]="Менеджмент Задач";
$LANG['plugin_fusioninventory']['task'][2]="Действие";
$LANG['plugin_fusioninventory']['task'][14]="До даты";
$LANG['plugin_fusioninventory']['task'][16]="Новое действие";
$LANG['plugin_fusioninventory']['task'][17]="Частота";
$LANG['plugin_fusioninventory']['task'][18]="Задачи";
$LANG['plugin_fusioninventory']['task'][19]="Запущеные задачи";
$LANG['plugin_fusioninventory']['task'][20]="Законченые задачи";
$LANG['plugin_fusioninventory']['task'][21]="Действует на этот материал ";
$LANG['plugin_fusioninventory']['task'][22]="Only planified tasks";
$LANG['plugin_fusioninventory']['task'][24]="Number of trials";
$LANG['plugin_fusioninventory']['task'][25]="Time between 2 trials (in minutes)";
$LANG['plugin_fusioninventory']['task'][26]="Module";
$LANG['plugin_fusioninventory']['task'][27]="Definition";
$LANG['plugin_fusioninventory']['task'][28]="Action";
$LANG['plugin_fusioninventory']['task'][29]="Type";
$LANG['plugin_fusioninventory']['task'][30]="Selection";
$LANG['plugin_fusioninventory']['task'][31]="Time between task start and start this action";
$LANG['plugin_fusioninventory']['task'][32]="Force the end";
$LANG['plugin_fusioninventory']['task'][33]="Communication type";
$LANG['plugin_fusioninventory']['task'][34]="Permanent";
$LANG['plugin_fusioninventory']['task'][35]="minutes";
$LANG['plugin_fusioninventory']['task'][36]="hours";
$LANG['plugin_fusioninventory']['task'][37]="days";
$LANG['plugin_fusioninventory']['task'][38]="months";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Начато";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Ошибка / Перепланировано";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Ошибка";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="неизвестно";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Running";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Prepared";

$LANG['plugin_fusioninventory']['update'][0]="your history table have more than 300 000 entries, you must run this command to finish update : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML FusionInventory";

?>