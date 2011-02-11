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
$LANG['plugin_fusioninventory']['title'][5]="Bloqueios";

$LANG['plugin_fusioninventory']['config'][0] = "Frequência do inventário (em horas)";

$LANG['plugin_fusioninventory']['profile'][0]="Gerenciamento de permissões";
$LANG['plugin_fusioninventory']['profile'][2]="Agents";
$LANG['plugin_fusioninventory']['profile'][3]="Agent remote controle";
$LANG['plugin_fusioninventory']['profile'][4]="Configuration";
$LANG['plugin_fusioninventory']['profile'][5]="WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6]="Unknown devices";
$LANG['plugin_fusioninventory']['profile'][7]="Tasks";

$LANG['plugin_fusioninventory']['setup'][16]="Documentação";
$LANG['plugin_fusioninventory']['setup'][17]="Outros plugins do FusionInventory (fusinv...) deve ser desinstalado antes de desinstalar o plugin FusionInventory";

$LANG['plugin_fusioninventory']['functionalities'][0]="Funções";
$LANG['plugin_fusioninventory']['functionalities'][2]="Configuração geral";
$LANG['plugin_fusioninventory']['functionalities'][6]="Legenda";
$LANG['plugin_fusioninventory']['functionalities'][8]="Agent port";
$LANG['plugin_fusioninventory']['functionalities'][9]="Retenção em dias";
$LANG['plugin_fusioninventory']['functionalities'][16]="Autenticação SNMP";
$LANG['plugin_fusioninventory']['functionalities'][17]="Banco de dados";
$LANG['plugin_fusioninventory']['functionalities'][18]="Arquivos";
$LANG['plugin_fusioninventory']['functionalities'][19]="Por favor, configure a autenticação SNMP na configuração do plugin";
$LANG['plugin_fusioninventory']['functionalities'][27]="Somente SSL para o agente";
$LANG['plugin_fusioninventory']['functionalities'][29]="Lista de campos para o histórico";
$LANG['plugin_fusioninventory']['functionalities'][32]="Apagar tarefas depois";
$LANG['plugin_fusioninventory']['functionalities'][60]="Limpar histórico";
$LANG['plugin_fusioninventory']['functionalities'][73]="Campos";
$LANG['plugin_fusioninventory']['functionalities'][74]="Valores";
$LANG['plugin_fusioninventory']['functionalities'][75]="Bloqueios";

$LANG['plugin_fusioninventory']['errors'][22]="Elemento autônomo em";
$LANG['plugin_fusioninventory']['errors'][50]="Versão do GLPI não compatível. Necessário versão 0.78";

$LANG['plugin_fusioninventory']['rules'][2]="Equipment import and link rules";
$LANG['plugin_fusioninventory']['rules'][3]="Search GLPI equipment with the status";
$LANG['plugin_fusioninventory']['rules'][4]="Destination of equipment entity";
$LANG['plugin_fusioninventory']['rules'][5]="FusionInventory link";
$LANG['plugin_fusioninventory']['rules'][6] = "Link if possible, else import denied";
$LANG['plugin_fusioninventory']['rules'][7] = "Link if possible";
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

$LANG['plugin_fusioninventory']['menu'][1]="Configuração do agente";
$LANG['plugin_fusioninventory']['menu'][3]="Menu";
$LANG['plugin_fusioninventory']['menu'][4]="Dispositivo desconhecido";
$LANG['plugin_fusioninventory']['menu'][7]="Trabalhos em execução";

$LANG['plugin_fusioninventory']['discovery'][5]="Número de dispositivos importados";
$LANG['plugin_fusioninventory']['discovery'][9]="Número de dispositivos não importados devido ao tipo não definido";

$LANG['plugin_fusioninventory']['agents'][4]="Last contact";
$LANG['plugin_fusioninventory']['agents'][6]="desabilitar";
$LANG['plugin_fusioninventory']['agents'][15]="Estado do agente";
$LANG['plugin_fusioninventory']['agents'][17]="Agente está executado";
$LANG['plugin_fusioninventory']['agents'][22]="Espera";
$LANG['plugin_fusioninventory']['agents'][23]="Ligação de computador";
$LANG['plugin_fusioninventory']['agents'][24]="Token";
$LANG['plugin_fusioninventory']['agents'][25]="Versão";
$LANG['plugin_fusioninventory']['agents'][27]="Módulos dos agentes";
$LANG['plugin_fusioninventory']['agents'][28]="Agente";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible to communicate with agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Force inventory";
$LANG['plugin_fusioninventory']['agents'][32]="Auto managenement dynamic of agents";
$LANG['plugin_fusioninventory']['agents'][33]="Auto managenement dynamic of agents (same subnet)";
$LANG['plugin_fusioninventory']['agents'][34]="Activation (by default)";
$LANG['plugin_fusioninventory']['agents'][35]="Device_id";

$LANG['plugin_fusioninventory']['unknown'][2]="Dispositivos aprovados";
$LANG['plugin_fusioninventory']['unknown'][4]="Hub de rede";

$LANG['plugin_fusioninventory']['task'][0]="Tarefa";
$LANG['plugin_fusioninventory']['task'][1]="Gerenciamento de tarefa";
$LANG['plugin_fusioninventory']['task'][2]="Ação";
$LANG['plugin_fusioninventory']['task'][14]="Data da cobrança";
$LANG['plugin_fusioninventory']['task'][16]="Nova ação";
$LANG['plugin_fusioninventory']['task'][17]="Frequência";
$LANG['plugin_fusioninventory']['task'][18]="Tarefas";
$LANG['plugin_fusioninventory']['task'][19]="Tarefas em execução";
$LANG['plugin_fusioninventory']['task'][20]="Tarefas finalizadas";
$LANG['plugin_fusioninventory']['task'][21]="Ação sobre este material";
$LANG['plugin_fusioninventory']['task'][22]="Somente tarefas planejadas";
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

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Iniciado";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Erro / replanejado";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Erro";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="desconhecido";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Running";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Prepared";

$LANG['plugin_fusioninventory']['update'][0]="sua tabela de histórico tem mais de 300.000 entradas, você deve executar este comando para finalizar a atualização : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

?>