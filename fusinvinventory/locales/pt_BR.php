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


$LANG['plugin_fusinvinventory']['antivirus'][0]="Antivirus";
$LANG['plugin_fusinvinventory']['antivirus'][1]="Este computador não é um Windows XP ou mais recente ou nenhum antivirus está instalado";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Versão";
$LANG['plugin_fusinvinventory']['antivirus'][3]="Atualizado";

$LANG['plugin_fusinvinventory']['bios'][0]="BIOS";

$LANG['plugin_fusinvinventory']['blacklist'][0]="Valor na lista negra";
$LANG['plugin_fusinvinventory']['blacklist'][1]="Novo valor para lista negra";

$LANG['plugin_fusinvinventory']['computer'][0]="Último inventário";
$LANG['plugin_fusinvinventory']['computer'][1]="Dono";
$LANG['plugin_fusinvinventory']['computer'][2]="Empresa";

$LANG['plugin_fusinvinventory']['importxml'][0]="Importação de arquivos XML de um agente";
$LANG['plugin_fusinvinventory']['importxml'][1]="Computador injetado GLPI";
$LANG['plugin_fusinvinventory']['importxml'][2]="Nenhum arquivo para importar!";
$LANG['plugin_fusinvinventory']['importxml'][3]="Arquivo XML não é válido!";

$LANG['plugin_fusinvinventory']['integrity'][0]="Somente em GLPI (seleciona para excluir)";
$LANG['plugin_fusinvinventory']['integrity'][1]="Somente em último inventário (seleciona para importação)";

$LANG['plugin_fusinvinventory']['menu'][0]="Importação agente de arquivos XML";
$LANG['plugin_fusinvinventory']['menu'][1]="Regras de critérios";
$LANG['plugin_fusinvinventory']['menu'][2]="Lista negra";
$LANG['plugin_fusinvinventory']['menu'][4]="Verificação de integridade de dados";

$LANG['plugin_fusinvinventory']['profile'][2]="Critérios de existência";
$LANG['plugin_fusinvinventory']['profile'][3]="XML importação manual de arquivo";
$LANG['plugin_fusinvinventory']['profile'][4]="Campos na lista negra";

$LANG['plugin_fusinvinventory']['rule'][0]="Regras para critério de existência de computador";
$LANG['plugin_fusinvinventory']['rule'][100]="Regras de entidade";
$LANG['plugin_fusinvinventory']['rule'][102]="Ignore na importação FusionInventory";
$LANG['plugin_fusinvinventory']['rule'][1]="Critério existente";
$LANG['plugin_fusinvinventory']['rule'][2]="Número de série";
$LANG['plugin_fusinvinventory']['rule'][30]="Importação em ativos";
$LANG['plugin_fusinvinventory']['rule'][31]="Importação em dispositivos desconhecidos";
$LANG['plugin_fusinvinventory']['rule'][3]="Endereço MAC";
$LANG['plugin_fusinvinventory']['rule'][4]="Microsoft produto-chave";
$LANG['plugin_fusinvinventory']['rule'][5]="Modelo de computador";
$LANG['plugin_fusinvinventory']['rule'][6]="Número de série do disco rígido";
$LANG['plugin_fusinvinventory']['rule'][7]="Número de série de partição ";
$LANG['plugin_fusinvinventory']['rule'][8]="Etiqueta";

$LANG['plugin_fusinvinventory']['setup'][17]="O plugin \"FusionInventory INVENTÁRIO\" precisa o plugin \"FusionInventory\" ativado antes da ativação.";
$LANG['plugin_fusinvinventory']['setup'][18]="O plugin \"FusionInventory INVENTÁRIO\" precisa o plugin \"FusionInventory\" ativado antes de desinstalar.";
$LANG['plugin_fusinvinventory']['setup'][20]="Opções de importação";
$LANG['plugin_fusinvinventory']['setup'][21]="Componentes";
$LANG['plugin_fusinvinventory']['setup'][22]="Importação mundial";
$LANG['plugin_fusinvinventory']['setup'][23]="Nenhuma importação";
$LANG['plugin_fusinvinventory']['setup'][24]="Importação única";
$LANG['plugin_fusinvinventory']['setup'][25]="Registro";
$LANG['plugin_fusinvinventory']['setup'][26]="Processos";
$LANG['plugin_fusinvinventory']['setup'][27]="Importação única sobre o número de série";
$LANG['plugin_fusinvinventory']['setup'][28]="Transferência automática de computadores";
$LANG['plugin_fusinvinventory']['setup'][29]="Modelo para a transferência automática de computadores em uma outra entidade";
$LANG['plugin_fusinvinventory']['setup'][30]="Unidades de rede";
$LANG['plugin_fusinvinventory']['setup'][31]="Placa de rede virtual";
$LANG['plugin_fusinvinventory']['setup'][32]="Esta opção não irá importar este item";
$LANG['plugin_fusinvinventory']['setup'][33]="Esta opção irá mesclar os itens com o mesmo nome para reduzir o número de itens, se essa gestão não é importante";
$LANG['plugin_fusinvinventory']['setup'][34]="Esta opção irá criar um item para cada item encontrado";
$LANG['plugin_fusinvinventory']['setup'][35]="Esta opção irá criar um item para cada item que tem um número de série";
$LANG['plugin_fusinvinventory']['setup'][36]="Status padrão";

$LANG['plugin_fusinvinventory']['title'][0]="FusionInventory INVENTÁRIO ";
$LANG['plugin_fusinvinventory']['title'][1]="Inventário local";
$LANG['plugin_fusinvinventory']['title'][2]="Inventário remoto do VMware host ";

$LANG['plugin_fusinvinventory']['vmwareesx'][0]="VMware host";
?>