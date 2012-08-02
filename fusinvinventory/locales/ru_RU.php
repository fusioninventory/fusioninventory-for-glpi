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


$LANG['plugin_fusinvinventory']['antivirus'][0]="Антивирус";
$LANG['plugin_fusinvinventory']['antivirus'][1]="This computer is not a Windows XP and later or no antivirus is installed";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Версия";
$LANG['plugin_fusinvinventory']['antivirus'][3]="Обновление";

$LANG['plugin_fusinvinventory']['bios'][0]="BIOS";

$LANG['plugin_fusinvinventory']['blacklist'][0]="значение черного списка";
$LANG['plugin_fusinvinventory']['blacklist'][1]="Новое значение в черный список";

$LANG['plugin_fusinvinventory']['computer'][0]="Последняя версия inventory";
$LANG['plugin_fusinvinventory']['computer'][1]="Владелец";
$LANG['plugin_fusinvinventory']['computer'][2]="Компания";

$LANG['plugin_fusinvinventory']['importxml'][0]="Импорт XML файла из агента";
$LANG['plugin_fusinvinventory']['importxml'][1]="Компьютер добавлен в GLPI";
$LANG['plugin_fusinvinventory']['importxml'][2]="Нет файла для импорта!";
$LANG['plugin_fusinvinventory']['importxml'][3]="XML файл не действителен!";

$LANG['plugin_fusinvinventory']['integrity'][0]="Только в GLPI (проверьте перед удалением)";
$LANG['plugin_fusinvinventory']['integrity'][1]="Только в последней inventory (проверьте для импорта)";

$LANG['plugin_fusinvinventory']['menu'][0]="Импорт XML файла агента";
$LANG['plugin_fusinvinventory']['menu'][1]="Критерий привил";
$LANG['plugin_fusinvinventory']['menu'][2]="Черный список";
$LANG['plugin_fusinvinventory']['menu'][4]="Проверка целостности данных";

$LANG['plugin_fusinvinventory']['profile'][2]="Существующие критерии";
$LANG['plugin_fusinvinventory']['profile'][3]="Ручной импорт XML файла";
$LANG['plugin_fusinvinventory']['profile'][4]="Области черного списка";

$LANG['plugin_fusinvinventory']['rule'][0]="Критерии правил компьютера";
$LANG['plugin_fusinvinventory']['rule'][100]="Правила организации";
$LANG['plugin_fusinvinventory']['rule'][102]="Игнорирование импорта в FusionInventory";
$LANG['plugin_fusinvinventory']['rule'][1]="Существующий критерий";
$LANG['plugin_fusinvinventory']['rule'][2]="Серийный номер";
$LANG['plugin_fusinvinventory']['rule'][30]="Импорт в избранное";
$LANG['plugin_fusinvinventory']['rule'][31]="Импорт неизвестных устройств";
$LANG['plugin_fusinvinventory']['rule'][3]="MAC адрес";
$LANG['plugin_fusinvinventory']['rule'][4]="Microsoft product key";
$LANG['plugin_fusinvinventory']['rule'][5]="Модель компьютера";
$LANG['plugin_fusinvinventory']['rule'][6]="Серийный номер HDD";
$LANG['plugin_fusinvinventory']['rule'][7]="Серийный номер раздела";
$LANG['plugin_fusinvinventory']['rule'][8]="Тег";

$LANG['plugin_fusinvinventory']['setup'][17]="Плагину FusionInventory INVENTORY требуется активный плагин FusionInventory до его активации.";
$LANG['plugin_fusinvinventory']['setup'][18]="Плагину FusionInventory INVENTORY требуется активный плагин FusionInventory до его удаления.";
$LANG['plugin_fusinvinventory']['setup'][20]="Параметры импорта";
$LANG['plugin_fusinvinventory']['setup'][21]="Компоненты";
$LANG['plugin_fusinvinventory']['setup'][22]="Глобальный импорт";
$LANG['plugin_fusinvinventory']['setup'][23]="Не импортировать";
$LANG['plugin_fusinvinventory']['setup'][24]="Уникальный импорт";
$LANG['plugin_fusinvinventory']['setup'][25]="Реестр";
$LANG['plugin_fusinvinventory']['setup'][26]="Процессы";
$LANG['plugin_fusinvinventory']['setup'][27]="Уникальный импорт серийных номеров";
$LANG['plugin_fusinvinventory']['setup'][28]="Автоматическая передача компьютеров";
$LANG['plugin_fusinvinventory']['setup'][29]="Автоматическая модель для передачи компьютеров в другую организацию";
$LANG['plugin_fusinvinventory']['setup'][30]="Сетевые диски";
$LANG['plugin_fusinvinventory']['setup'][31]="Виртуальный сетевой интерфейс";
$LANG['plugin_fusinvinventory']['setup'][32]="Этот параметр не импортирует данный элемент";
$LANG['plugin_fusinvinventory']['setup'][33]="Этот параметр объединит элементы с одинаковым именем для уменьшения количества элементов, если управление ими не актуально";
$LANG['plugin_fusinvinventory']['setup'][34]="Этот параметр создает один элемент для каждого найденного элемента";
$LANG['plugin_fusinvinventory']['setup'][35]="Этот параметр создает один элемент для каждого элемента с серийным номером";
$LANG['plugin_fusinvinventory']['setup'][36]="Статус по умолчанию";

$LANG['plugin_fusinvinventory']['title'][0]="FusionInventory INVENTORY";
$LANG['plugin_fusinvinventory']['title'][1]="Локальная инвентаризация";
$LANG['plugin_fusinvinventory']['title'][2]="VMware хост remote inventory";

$LANG['plugin_fusinvinventory']['vmwareesx'][0]="VMware хост";
?>