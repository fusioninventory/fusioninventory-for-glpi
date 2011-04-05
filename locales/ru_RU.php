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
$LANG['plugin_fusioninventory']['title'][1]="FusInv";
$LANG['plugin_fusioninventory']['title'][5]="Блокировки";

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
$LANG['plugin_fusioninventory']['functionalities'][27]="SSL только для агента";
$LANG['plugin_fusioninventory']['functionalities'][29]="Список полей для сохранения в истории";
$LANG['plugin_fusioninventory']['functionalities'][32]="Удалить задачу после";
$LANG['plugin_fusioninventory']['functionalities'][60]="Очистить историю";
$LANG['plugin_fusioninventory']['functionalities'][73]="Поля";
$LANG['plugin_fusioninventory']['functionalities'][74]="Значения";
$LANG['plugin_fusioninventory']['functionalities'][75]="Блокировки";
$LANG['plugin_fusioninventory']['functionalities'][76]="Отладка";

$LANG['plugin_fusioninventory']['errors'][22]="Не подключённый элемент в";
$LANG['plugin_fusioninventory']['errors'][50]="GLPI версия не совместима, требуется версия 0.78";

$LANG['plugin_fusioninventory']['rules'][2]="Импорт оборудования и ссылок правил";
$LANG['plugin_fusioninventory']['rules'][3]="Поиск GLPI оборудования со статусом";
$LANG['plugin_fusioninventory']['rules'][4]="Назначение оборудования организации";
$LANG['plugin_fusioninventory']['rules'][5]="Ссылка FusionInventory";
$LANG['plugin_fusioninventory']['rules'][6] = "Ссылка если возмжно, иначе отказано в импорте";
$LANG['plugin_fusioninventory']['rules'][7] = "Ссылка если возможно";
$LANG['plugin_fusioninventory']['rules'][8] = "Отправить";
$LANG['plugin_fusioninventory']['rules'][9]  = "существует";
$LANG['plugin_fusioninventory']['rules'][10]  = "не существует";
$LANG['plugin_fusioninventory']['rules'][11] = "сейчас в GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "пусто";
$LANG['plugin_fusioninventory']['rules'][13] = "Серийный номер HDD";
$LANG['plugin_fusioninventory']['rules'][14] = "Серийный номер раздела";
$LANG['plugin_fusioninventory']['rules'][15] = "uuid";
$LANG['plugin_fusioninventory']['rules'][16] = "FusionInventory tag";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Оборудование для импорта";

$LANG['plugin_fusioninventory']['choice'][0] = "Нет";
$LANG['plugin_fusioninventory']['choice'][1] = "Да";
$LANG['plugin_fusioninventory']['choice'][2] = "или";
$LANG['plugin_fusioninventory']['choice'][3] = "и";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Номер процесса";

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
$LANG['plugin_fusioninventory']['agents'][30]="Невозможно обратиться к агенту!";
$LANG['plugin_fusioninventory']['agents'][31]="Группы инвенторизации";
$LANG['plugin_fusioninventory']['agents'][32]="Автоматическое управление динамическими агентами";
$LANG['plugin_fusioninventory']['agents'][33]="Автоматическое управление динамическими агентами (так же подсети)";
$LANG['plugin_fusioninventory']['agents'][34]="Активация (по умолчанию)";
$LANG['plugin_fusioninventory']['agents'][35]="Device_id";
$LANG['plugin_fusioninventory']['agents'][36]="Модули агента";
$LANG['plugin_fusioninventory']['agents'][37]="Заблокированный";
$LANG['plugin_fusioninventory']['agents'][38]="Доступный";
$LANG['plugin_fusioninventory']['agents'][39]="Запущенный";
$LANG['plugin_fusioninventory']['agents'][40]="Компьютер с нейзвестным IP";

$LANG['plugin_fusioninventory']['unknown'][2]="Подтверждённые устройства";
$LANG['plugin_fusioninventory']['unknown'][4]="Сетевой хаб";
$LANG['plugin_fusioninventory']['unknown'][5]="Импорт не известного устройства в актив";

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
$LANG['plugin_fusioninventory']['task'][22]="Только запланированные задачи";
$LANG['plugin_fusioninventory']['task'][24]="Количество испытаний";
$LANG['plugin_fusioninventory']['task'][25]="Время между двумя испытаниями (в минутах)";
$LANG['plugin_fusioninventory']['task'][26]="Модуль";
$LANG['plugin_fusioninventory']['task'][27]="Определение";
$LANG['plugin_fusioninventory']['task'][28]="Действие";
$LANG['plugin_fusioninventory']['task'][29]="Тип";
$LANG['plugin_fusioninventory']['task'][30]="Выбор";
$LANG['plugin_fusioninventory']['task'][31]="Время между началом задания и запуском действия";
$LANG['plugin_fusioninventory']['task'][32]="Принудительное завершение";
$LANG['plugin_fusioninventory']['task'][33]="Тип связей";
$LANG['plugin_fusioninventory']['task'][34]="Постоянная";
$LANG['plugin_fusioninventory']['task'][35]="минуты";
$LANG['plugin_fusioninventory']['task'][36]="часы";
$LANG['plugin_fusioninventory']['task'][37]="дни";
$LANG['plugin_fusioninventory']['task'][38]="месяцы";
$LANG['plugin_fusioninventory']['task'][39]="Не возможно запустить задачу потому что некоторые задачи все еще запущены!";
$LANG['plugin_fusioninventory']['task'][40]="Принудительный запуск";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Начато";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Ошибка / Перепланировано";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Ошибка";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="неизвестно";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Запуск";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Подготовлен";

$LANG['plugin_fusioninventory']['update'][0]="ваша таблица истории содержит больше 300 000 записей, необходимо запустить эту команду для того что бы закончить обновление: ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Плохой маркер. не возможно запустить агент";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Агент остановлен/сломан";

?>