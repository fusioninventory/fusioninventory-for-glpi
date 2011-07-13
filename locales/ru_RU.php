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

$title="FusionInventory INVENTORY";

$LANG['plugin_fusinvinventory']['title'][0]="$title";
$LANG['plugin_fusinvinventory']['title'][1]="Local inventory";
$LANG['plugin_fusinvinventory']['title'][2]="Vmware ESX/ESXi/vCenter remote inventory";

$LANG['plugin_fusinvinventory']['setup'][17]="Плагину ".$title." требуется активный плагин FusionInventory до его активации.";
$LANG['plugin_fusinvinventory']['setup'][18]="Плагину ".$title." требуется активный плагин FusionInventory до его удаления.";
$LANG['plugin_fusinvinventory']['setup'][20]="Параметры импорта";
$LANG['plugin_fusinvinventory']['setup'][21]="Компоненты";
$LANG['plugin_fusinvinventory']['setup'][22]="Глобальный импорт";
$LANG['plugin_fusinvinventory']['setup'][23]="Не импортрировать";
$LANG['plugin_fusinvinventory']['setup'][24]="Уникальный импорт";
$LANG['plugin_fusinvinventory']['setup'][25]="Реестр";
$LANG['plugin_fusinvinventory']['setup'][26]="Processus";
$LANG['plugin_fusinvinventory']['setup'][27]="Уникальный импорт серийных номеров";
$LANG['plugin_fusinvinventory']['setup'][28]="Автоматическая передача компьютеров";
$LANG['plugin_fusinvinventory']['setup'][29]="Автоматическая модель для передачи компьютеров в другую организацию";
$LANG['plugin_fusinvinventory']['setup'][30]="Network drives";
$LANG['plugin_fusinvinventory']['setup'][31]="Virtual network card";
$LANG['plugin_fusinvinventory']['setup'][32]="This option will not import this item";
$LANG['plugin_fusinvinventory']['setup'][33]="This option will merge items with same name to 
      reduce number of items if this management isn't important";
$LANG['plugin_fusinvinventory']['setup'][34]="This option will create one item for each item found";
$LANG['plugin_fusinvinventory']['setup'][35]="This option will create one item for each item have 
      serial number";
$LANG['plugin_fusinvinventory']['setup'][36]="Статус по умолчанию";

$LANG['plugin_fusinvinventory']['menu'][0]="Импорт XML файла агента";
$LANG['plugin_fusinvinventory']['menu'][1]="Критерий привил";
$LANG['plugin_fusinvinventory']['menu'][2]="Черный список";
$LANG['plugin_fusinvinventory']['menu'][3]="Правила организации";
$LANG['plugin_fusinvinventory']['menu'][4]="Проверка целостности данных";

$LANG['plugin_fusinvinventory']['importxml'][0]="Импорт XML файла из агента";
$LANG['plugin_fusinvinventory']['importxml'][1]="Компьютер добавлен в GLPI";
$LANG['plugin_fusinvinventory']['importxml'][2]="Нет файла для импорта!";
$LANG['plugin_fusinvinventory']['importxml'][3]="XML фаил не действителен!";

$LANG['plugin_fusinvinventory']['rule'][0]="Computer existent criteria rules";
$LANG['plugin_fusinvinventory']['rule'][1]="Существующий критерий";
$LANG['plugin_fusinvinventory']['rule'][2]="Сирийный номер";
$LANG['plugin_fusinvinventory']['rule'][3]="MAC адрес";
$LANG['plugin_fusinvinventory']['rule'][4]="Microsoft product key";
$LANG['plugin_fusinvinventory']['rule'][5]="Модель компьютера";
$LANG['plugin_fusinvinventory']['rule'][6]="Серийный номер HDD";
$LANG['plugin_fusinvinventory']['rule'][7]="Серийный номер раздела";
$LANG['plugin_fusinvinventory']['rule'][8]="Тег";
$LANG['plugin_fusinvinventory']['rule'][30]="Import in asset";
$LANG['plugin_fusinvinventory']['rule'][31]="Импорт неизвестных устройств";
$LANG['plugin_fusinvinventory']['rule'][100]="Правила организации";
$LANG['plugin_fusinvinventory']['rule'][101]="Тег";
$LANG['plugin_fusinvinventory']['rule'][102]="Игнорирование импорта в FusionInventory";

$LANG['plugin_fusinvinventory']['blacklist'][0]="значение черного списка";
$LANG['plugin_fusinvinventory']['blacklist'][1]="Новое значение в черный список";

$LANG['plugin_fusinvinventory']['profile'][2]="Существующие критерии";
$LANG['plugin_fusinvinventory']['profile'][3]="Рочное импортирование XML файла";
$LANG['plugin_fusinvinventory']['profile'][4]="Области черного списка";

$LANG['plugin_fusinvinventory']['antivirus'][0]="Антивирус";
$LANG['plugin_fusinvinventory']['antivirus'][1]="Нет антивируса на этом компьютере";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Версия";
$LANG['plugin_fusinvinventory']['antivirus'][3]="Обновление";

$LANG['plugin_fusinvinventory']['computer'][0]="Последняя версия inventory";

$LANG['plugin_fusinvinventory']['vmwareesx'][0]="Vmware host";

$LANG['plugin_fusinvinventory']['integrity'][0]="Only in GLPI (check to delete)";
$LANG['plugin_fusinvinventory']['integrity'][1]="Only in last inventory (check to import)";

?>