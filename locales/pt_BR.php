<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$title="FusionInventory";
$version="2.3.0";

$LANG['plugin_fusioninventory']["title"][0]="$title";
$LANG['plugin_fusioninventory']["title"][1]="Informação SNMP";
$LANG['plugin_fusioninventory']["title"][2]="histórico de conexões";
$LANG['plugin_fusioninventory']["title"][3]="[Trk] Erros";
$LANG['plugin_fusioninventory']["title"][4]="[Trk] Cron";
$LANG['plugin_fusioninventory']["title"][5]="Bloqueios do FusionInventory";

$LANG['plugin_fusioninventory']['config'][0] = "Frequência do inventário (em horas)";
$LANG['plugin_fusioninventory']['config'][1] = "Módulos";
$LANG['plugin_fusioninventory']['config'][2] = "Snmp";
$LANG['plugin_fusioninventory']['config'][3] = "Inventário";
$LANG['plugin_fusioninventory']['config'][4] = "Descoberta de dispositivos";
$LANG['plugin_fusioninventory']['config'][5] = "Gerenciar agente diretamente do GLPI";
$LANG['plugin_fusioninventory']['config'][6] = "Wake On Lan";
$LANG['plugin_fusioninventory']['config'][7] = "Consulta SNMP";

$LANG['plugin_fusioninventory']["profile"][0]="Gerenciamento de permissões";
$LANG['plugin_fusioninventory']["profile"][1]="$title"; //interface

$LANG['plugin_fusioninventory']["profile"][10]="Perfis configurados";
$LANG['plugin_fusioninventory']["profile"][11]="Histórico de computador";
$LANG['plugin_fusioninventory']["profile"][12]="Histórico de impressora";
$LANG['plugin_fusioninventory']["profile"][13]="Imformação de impressora";
$LANG['plugin_fusioninventory']["profile"][14]="Informação de rede";
$LANG['plugin_fusioninventory']["profile"][15]="Erros";

$LANG['plugin_fusioninventory']["profile"][16]="Rede SNMP";
$LANG['plugin_fusioninventory']["profile"][17]="Periférico SNMP";
$LANG['plugin_fusioninventory']["profile"][18]="Impressoras SNMP";
$LANG['plugin_fusioninventory']["profile"][19]="Modelos SNMP";
$LANG['plugin_fusioninventory']["profile"][20]="Autenticação SNMP";
$LANG['plugin_fusioninventory']["profile"][21]="Informação de script";
$LANG['plugin_fusioninventory']["profile"][22]="Descoberta de rede";
$LANG['plugin_fusioninventory']["profile"][23]="Configuração geral";
$LANG['plugin_fusioninventory']["profile"][24]="Modelo SNMP";
$LANG['plugin_fusioninventory']["profile"][25]="Faixa de IP";
$LANG['plugin_fusioninventory']["profile"][26]="Agente";
$LANG['plugin_fusioninventory']["profile"][27]="Processos dos agentes";
$LANG['plugin_fusioninventory']["profile"][28]="Relatório";
$LANG['plugin_fusioninventory']["profile"][29]="Controle remoto dos agentes";
$LANG['plugin_fusioninventory']["profile"][30]="Dispositivos desconhecidos";
$LANG['plugin_fusioninventory']["profile"][31]="inventário de dispositivo FusionInventory";
$LANG['plugin_fusioninventory']["profile"][32]="Consulta SNMP";
$LANG['plugin_fusioninventory']["profile"][33]="WakeOnLan";
$LANG['plugin_fusioninventory']["profile"][34]="Ações";

$LANG['plugin_fusioninventory']["setup"][2]="Obrigado por colocar tudo na entidade raiz (ver todos)";
$LANG['plugin_fusioninventory']["setup"][3]="Configuração do plugin".$title;
$LANG['plugin_fusioninventory']["setup"][4]="Instalar plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][5]="Atualizar plugin $title to version $version";
$LANG['plugin_fusioninventory']["setup"][6]="Desinstalar plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][8]="Atenção, a desinstalação deste plugin é um passo irreversível.<br>Você perderá todos os dados.";
$LANG['plugin_fusioninventory']["setup"][11]="Instruções";
$LANG['plugin_fusioninventory']["setup"][12]="FAQ";
$LANG['plugin_fusioninventory']["setup"][13]="Verificação do módulo PHP";
$LANG['plugin_fusioninventory']["setup"][14]="A extensão SNMP do PHP não está carregada";
$LANG['plugin_fusioninventory']["setup"][15]="A extensão PHP/PECL runkit não está carregada";
$LANG['plugin_fusioninventory']["setup"][16]="Documentação";
$LANG['plugin_fusioninventory']["setup"][17]="Outros plugins do FusionInventory (fusinv...) deve ser desinstalado antes de desinstalar o plugin FusionInventory";

$LANG['plugin_fusioninventory']["functionalities"][0]="Funções";
$LANG['plugin_fusioninventory']["functionalities"][1]="Adicionar / Remover funções";
$LANG['plugin_fusioninventory']["functionalities"][2]="Configuração geral";
$LANG['plugin_fusioninventory']["functionalities"][3]="SNMP";
$LANG['plugin_fusioninventory']["functionalities"][4]="Conexões";
$LANG['plugin_fusioninventory']["functionalities"][5]="Script do servidor";
$LANG['plugin_fusioninventory']["functionalities"][6]="Legenda";
$LANG['plugin_fusioninventory']["functionalities"][7]="Campos bloqueáveis";

$LANG['plugin_fusioninventory']["functionalities"][9]="Retenção em dias";
$LANG['plugin_fusioninventory']["functionalities"][10]="Ativação do histórico";
$LANG['plugin_fusioninventory']["functionalities"][11]="Ativação do módulo de conexão";
$LANG['plugin_fusioninventory']["functionalities"][12]="Ativação do módulo de rede SNMP";
$LANG['plugin_fusioninventory']["functionalities"][13]="Ativação do módulo de periférico SNMP";
$LANG['plugin_fusioninventory']["functionalities"][14]="Ativação do módulo de telefones SNMP";
$LANG['plugin_fusioninventory']["functionalities"][15]="Ativação do módulo de impressoras SNMP";
$LANG['plugin_fusioninventory']["functionalities"][16]="Autenticação SNMP";
$LANG['plugin_fusioninventory']["functionalities"][17]="Banco de dados";
$LANG['plugin_fusioninventory']["functionalities"][18]="Arquivos";
$LANG['plugin_fusioninventory']["functionalities"][19]="Por favor, configure a autenticação SNMP na configuração do plugin";
$LANG['plugin_fusioninventory']["functionalities"][20]="Status dos dispositivos ativos";
$LANG['plugin_fusioninventory']["functionalities"][21]="Retenção do histórico das interconexões entre materiais em dia (0 = infinito)";
$LANG['plugin_fusioninventory']["functionalities"][22]="Retenção do histórico de mudanças do estado das portas (0 = infinito)";
$LANG['plugin_fusioninventory']["functionalities"][23]="Retenção do histórico de endereço MAC desconhecidos (0 = infinito)";
$LANG['plugin_fusioninventory']["functionalities"][24]="Retenção do histórico de erros SNMP (0 = infinito)";
$LANG['plugin_fusioninventory']["functionalities"][25]="Retenção do histórico dos scripts de processos (0 = infinito)";
$LANG['plugin_fusioninventory']["functionalities"][26]="URL do GLPI para o agente";
$LANG['plugin_fusioninventory']["functionalities"][27]="Somente SSL para o agente";
$LANG['plugin_fusioninventory']["functionalities"][28]="Configuração do histórico";
$LANG['plugin_fusioninventory']["functionalities"][29]="Lista de campos para o histórico";

$LANG['plugin_fusioninventory']["functionalities"][30]="Status do material ativo";
$LANG['plugin_fusioninventory']["functionalities"][31]="Gerenciamento dos cartuchos e estoques";
$LANG['plugin_fusioninventory']["functionalities"][32]="Apagar tarefas depois";
$LANG['plugin_fusioninventory']["functionalities"][36]="Frequência da leitura do contador";

$LANG['plugin_fusioninventory']["functionalities"][40]="Configuração";
$LANG['plugin_fusioninventory']["functionalities"][41]="Status do material ativo";
$LANG['plugin_fusioninventory']["functionalities"][42]="Switch";
$LANG['plugin_fusioninventory']["functionalities"][43]="Autenticação SNMP";

$LANG['plugin_fusioninventory']["functionalities"][50]="Número dos processos simultâneos para a descoberta de rede";
$LANG['plugin_fusioninventory']["functionalities"][51]="Número dos processos simultâneos para as consultas SNMP";
$LANG['plugin_fusioninventory']["functionalities"][52]="Ativação dos arquivos de log";
$LANG['plugin_fusioninventory']["functionalities"][53]="Número de processos simultâneos para serem usados pelo script do servidor";

$LANG['plugin_fusioninventory']["functionalities"][60]="Limpar histórico";

$LANG['plugin_fusioninventory']["functionalities"][70]="Configuração dos campos bloqueáveis";
$LANG['plugin_fusioninventory']["functionalities"][71]="Campos desbloqueáveis";
$LANG['plugin_fusioninventory']["functionalities"][72]="Tabela";
$LANG['plugin_fusioninventory']["functionalities"][73]="Campos";
$LANG['plugin_fusioninventory']["functionalities"][74]="Valores";
$LANG['plugin_fusioninventory']["functionalities"][75]="Bloqueios";
$LANG['plugin_fusioninventory']["functionalities"][76]="Não há nenhum campo bloqueável.";

$LANG['plugin_fusioninventory']["cron"][0]="Leitura do contador automática";
$LANG['plugin_fusioninventory']["cron"][1]="Ativar o registro";
$LANG['plugin_fusioninventory']["cron"][2]="";
$LANG['plugin_fusioninventory']["cron"][3]="Padrão";

$LANG['plugin_fusioninventory']["errors"][0]="Erros";
$LANG['plugin_fusioninventory']["errors"][1]="IP";
$LANG['plugin_fusioninventory']["errors"][2]="Descrição";
$LANG['plugin_fusioninventory']["errors"][3]="Data do primeiro problema";
$LANG['plugin_fusioninventory']["errors"][4]="Data do último problema";

$LANG['plugin_fusioninventory']["errors"][10]="Inconsistência com o GLPI básico";
$LANG['plugin_fusioninventory']["errors"][11]="Posição desconhecida";
$LANG['plugin_fusioninventory']["errors"][12]="IP desconhecido";

$LANG['plugin_fusioninventory']["errors"][20]="Erros SNMP";
$LANG['plugin_fusioninventory']["errors"][21]="Não foi possível recuperar informações";
$LANG['plugin_fusioninventory']["errors"][22]="Elemento autônomo em";
$LANG['plugin_fusioninventory']["errors"][23]="Não foi possível identificar dispositivo";

$LANG['plugin_fusioninventory']["errors"][30]="Erro no cabeamento";
$LANG['plugin_fusioninventory']["errors"][31]="Problema no cabeamento";

$LANG['plugin_fusioninventory']["errors"][50]="Versão do GLPI não compatível. Necessário versão 0.78";

$LANG['plugin_fusioninventory']["errors"][101]="Tempo esgotado";
$LANG['plugin_fusioninventory']["errors"][102]="Nenhum modelo SNMP atribuído";
$LANG['plugin_fusioninventory']["errors"][103]="Nenhuma autenticação SNMP atribuída";
$LANG['plugin_fusioninventory']["errors"][104]="Mensagem de erro";

$LANG['plugin_fusioninventory']["history"][0] = "Antigo";
$LANG['plugin_fusioninventory']["history"][1] = "Novo";
$LANG['plugin_fusioninventory']["history"][2] = "Desconectar";
$LANG['plugin_fusioninventory']["history"][3] = "Conexão";

$LANG['plugin_fusioninventory']["prt_history"][0]="Históricos e Estatísticas dos contedores de impressão";

$LANG['plugin_fusioninventory']["prt_history"][10]="Estatísticas do contador de impressão";
$LANG['plugin_fusioninventory']["prt_history"][11]="dia(s)";
$LANG['plugin_fusioninventory']["prt_history"][12]="Total de páginas impressas";
$LANG['plugin_fusioninventory']["prt_history"][13]="Páginas / dia";

$LANG['plugin_fusioninventory']["prt_history"][20]="Histórico do contador de impressão";
$LANG['plugin_fusioninventory']["prt_history"][21]="Data";
$LANG['plugin_fusioninventory']["prt_history"][22]="Contador";

$LANG['plugin_fusioninventory']["prt_history"][30]="Mostrar";
$LANG['plugin_fusioninventory']["prt_history"][31]="Unidade de tempo";
$LANG['plugin_fusioninventory']["prt_history"][32]="Adicionar uma impressora";
$LANG['plugin_fusioninventory']["prt_history"][33]="Remover uma impressora";
$LANG['plugin_fusioninventory']["prt_history"][34]="dia";
$LANG['plugin_fusioninventory']["prt_history"][35]="semana";
$LANG['plugin_fusioninventory']["prt_history"][36]="mês";
$LANG['plugin_fusioninventory']["prt_history"][37]="ano";

$LANG['plugin_fusioninventory']["cpt_history"][0]="Histórico das sessões";
$LANG['plugin_fusioninventory']["cpt_history"][1]="Contato";
$LANG['plugin_fusioninventory']["cpt_history"][2]="Computador";
$LANG['plugin_fusioninventory']["cpt_history"][3]="Usuário";
$LANG['plugin_fusioninventory']["cpt_history"][4]="Estado";
$LANG['plugin_fusioninventory']["cpt_history"][5]="Data";

$LANG['plugin_fusioninventory']["type"][1]="Computador";
$LANG['plugin_fusioninventory']["type"][2]="Switch";
$LANG['plugin_fusioninventory']["type"][3]="Impressora";

$LANG['plugin_fusioninventory']["rules"][1]="Regras";

$LANG['plugin_fusioninventory']["massiveaction"][1]="Atribuir modelo SNMP";
$LANG['plugin_fusioninventory']["massiveaction"][2]="Atribuir autenticação SNMP";

$LANG['plugin_fusioninventory']["processes"][0]="Histórico das execuções de script";
$LANG['plugin_fusioninventory']["processes"][1]="PID";
$LANG['plugin_fusioninventory']["processes"][2]="Status";
$LANG['plugin_fusioninventory']["processes"][3]="Número de processos";
$LANG['plugin_fusioninventory']["processes"][4]="Data do início da execução";
$LANG['plugin_fusioninventory']["processes"][5]="Data fo fim da execução";
$LANG['plugin_fusioninventory']["processes"][6]="Equipamentos de rede consultados";
$LANG['plugin_fusioninventory']["processes"][7]="Impressoras consultadas";
$LANG['plugin_fusioninventory']["processes"][8]="Portas consultadas";
$LANG['plugin_fusioninventory']["processes"][9]="Erros";
$LANG['plugin_fusioninventory']["processes"][10]="Tempo Script";
$LANG['plugin_fusioninventory']["processes"][11]="campos adicionados";
$LANG['plugin_fusioninventory']["processes"][12]="Erros SNMP";
$LANG['plugin_fusioninventory']["processes"][13]="MAC desconhecido";
$LANG['plugin_fusioninventory']["processes"][14]="Lista de endereços MAC desconhecidos";
$LANG['plugin_fusioninventory']["processes"][15]="Primeiro PID";
$LANG['plugin_fusioninventory']["processes"][16]="Último PID";
$LANG['plugin_fusioninventory']["processes"][17]="Data da primeira detecção";
$LANG['plugin_fusioninventory']["processes"][18]="Data da última detecção";
$LANG['plugin_fusioninventory']["processes"][19]="Histórico das execuções dos agentes";
$LANG['plugin_fusioninventory']["processes"][20]="Relatórios e Estatísticas";
$LANG['plugin_fusioninventory']["processes"][21]="Dispositivos consultados";
$LANG['plugin_fusioninventory']["processes"][22]="Erros";
$LANG['plugin_fusioninventory']["processes"][23]="Total da duração da descoberta";
$LANG['plugin_fusioninventory']["processes"][24]="Total da duração da consulta";
$LANG['plugin_fusioninventory']["processes"][25]="Agente";
$LANG['plugin_fusioninventory']["processes"][26]="Descoberta";
$LANG['plugin_fusioninventory']["processes"][27]="Consulta";
$LANG['plugin_fusioninventory']["processes"][28]="Núcleo";
$LANG['plugin_fusioninventory']["processes"][29]="Threads";
$LANG['plugin_fusioninventory']["processes"][30]="Descoberto";
$LANG['plugin_fusioninventory']["processes"][31]="Existente";
$LANG['plugin_fusioninventory']["processes"][32]="Importado";
$LANG['plugin_fusioninventory']["processes"][33]="Consultado";
$LANG['plugin_fusioninventory']["processes"][34]="Com erro";
$LANG['plugin_fusioninventory']["processes"][35]="Conexões criadas";
$LANG['plugin_fusioninventory']["processes"][36]="Conexões apagadas";
$LANG['plugin_fusioninventory']["processes"][37]="Total de IP";

$LANG['plugin_fusioninventory']["state"][0]="Iniciar computador";
$LANG['plugin_fusioninventory']["state"][1]="Parar computador";
$LANG['plugin_fusioninventory']["state"][2]="Conexão do usuário";
$LANG['plugin_fusioninventory']["state"][3]="Desconexão do usuário";

$LANG['plugin_fusioninventory']["mapping"][1]="rede > localização";
$LANG['plugin_fusioninventory']["mapping"][2]="rede > firmware";
$LANG['plugin_fusioninventory']["mapping"][3]="rede > tempo de atividade";
$LANG['plugin_fusioninventory']["mapping"][4]="rede > porta > mtu";
$LANG['plugin_fusioninventory']["mapping"][5]="rede > porta > velocidade";
$LANG['plugin_fusioninventory']["mapping"][6]="rede > porta > status interno";
$LANG['plugin_fusioninventory']["mapping"][7]="rede > portas > Última Mudança";
$LANG['plugin_fusioninventory']["mapping"][8]="rede > porta > número de bytes de entrada";
$LANG['plugin_fusioninventory']["mapping"][9]="rede > porta > número de bytes de saída";
$LANG['plugin_fusioninventory']["mapping"][10]="rede > porta > número de erros de entrada";
$LANG['plugin_fusioninventory']["mapping"][11]="rede > porta > número de erros de saída";
$LANG['plugin_fusioninventory']["mapping"][12]="rede > uso da CPU";
$LANG['plugin_fusioninventory']["mapping"][13]="rede > número de série";
$LANG['plugin_fusioninventory']["mapping"][14]="rede > porta > status da conexão";
$LANG['plugin_fusioninventory']["mapping"][15]="rede > porta > endereço MAC";
$LANG['plugin_fusioninventory']["mapping"][16]="rede > porta > nome";
$LANG['plugin_fusioninventory']["mapping"][17]="rede > modelo";
$LANG['plugin_fusioninventory']["mapping"][18]="rede > portas > tipo";
$LANG['plugin_fusioninventory']["mapping"][19]="rede > VLAN";
$LANG['plugin_fusioninventory']["mapping"][20]="rede > nome";
$LANG['plugin_fusioninventory']["mapping"][21]="rede > memória total";
$LANG['plugin_fusioninventory']["mapping"][22]="rede > memória livre";
$LANG['plugin_fusioninventory']["mapping"][23]="rede > porta > descrição da porta";
$LANG['plugin_fusioninventory']["mapping"][24]="impressora > nome";
$LANG['plugin_fusioninventory']["mapping"][25]="impressora > modelo";
$LANG['plugin_fusioninventory']["mapping"][26]="impressora > memória total";
$LANG['plugin_fusioninventory']["mapping"][27]="impressora > número de série";
$LANG['plugin_fusioninventory']["mapping"][28]="impressora > contador > número total de páginas impressas";
$LANG['plugin_fusioninventory']["mapping"][29]="impressora > contador > número de páginas preto e branco impressas";
$LANG['plugin_fusioninventory']["mapping"][30]="impressora > contador > número de páginas coloridas impressas";
$LANG['plugin_fusioninventory']["mapping"][31]="impressora > contador > número de páginas monocromáticas impressas";
$LANG['plugin_fusioninventory']["mapping"][32]="impressora > contador > número de páginas coloridas impressas";
$LANG['plugin_fusioninventory']["mapping"][33]="rede > porta > tipo de duplex";
$LANG['plugin_fusioninventory']["mapping"][34]="impressora > insumos > cartucho preto (%)";
$LANG['plugin_fusioninventory']["mapping"][35]="impressora > insumos > cartucho preto de foto (%)";
$LANG['plugin_fusioninventory']["mapping"][36]="impressora > insumos > cartucho ciano (%)";
$LANG['plugin_fusioninventory']["mapping"][37]="impressora > insumos > cartucho amarelo (%)";
$LANG['plugin_fusioninventory']["mapping"][38]="impressora > insumos > cartucho magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][39]="impressora > insumos > cartucho ciano claro (%)";
$LANG['plugin_fusioninventory']["mapping"][40]="impressora > insumos > cartucho magenta claro (%)";
$LANG['plugin_fusioninventory']["mapping"][41]="impressora > insumos > fotocondutor (%)";
$LANG['plugin_fusioninventory']["mapping"][42]="impressora > insumos > fotocondutor preto (%)";
$LANG['plugin_fusioninventory']["mapping"][43]="impressora > insumos > fotocondutor colorido (%)";
$LANG['plugin_fusioninventory']["mapping"][44]="impressora > insumos > fotocondutor ciano (%)";
$LANG['plugin_fusioninventory']["mapping"][45]="impressora > insumos > fotocondutor amarelo (%)";
$LANG['plugin_fusioninventory']["mapping"][46]="impressora > insumos > fotocondutor magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][47]="impressora > insumos > unidade de transferência preto (%)";
$LANG['plugin_fusioninventory']["mapping"][48]="impressora > insumos > unidade de transferência ciano (%)";
$LANG['plugin_fusioninventory']["mapping"][49]="impressora > insumos > unidade de transferência amarelo (%)";
$LANG['plugin_fusioninventory']["mapping"][50]="impressora > insumos > unidade de transferência magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][51]="impressora > insumos > resíduos (%)";
$LANG['plugin_fusioninventory']["mapping"][52]="impressora > insumos > quatro (%)";
$LANG['plugin_fusioninventory']["mapping"][53]="impressora > insumos > módulo de limpeza (%)";
$LANG['plugin_fusioninventory']["mapping"][54]="impressora > contador > número de páginas duplex impressas";
$LANG['plugin_fusioninventory']["mapping"][55]="impressora > contador > número de páginas escaneadas";
$LANG['plugin_fusioninventory']["mapping"][56]="impressora > localização";
$LANG['plugin_fusioninventory']["mapping"][57]="impressora > porta > nome";
$LANG['plugin_fusioninventory']["mapping"][58]="impressora > porta > endereço MAC";
$LANG['plugin_fusioninventory']["mapping"][59]="impressora > insumos > cartucho preto (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][60]="impressora > insumos > cartucho preto (tinta restante )";
$LANG['plugin_fusioninventory']["mapping"][61]="impressora > insumos > cartucho ciano (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][62]="impressora > insumos > cartucho ciano (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][63]="impressora > insumos > cartucho amarelo (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][64]="impressora > insumos > cartucho amarelo (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][65]="impressora > insumos > cartucho magenta (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][66]="impressora > insumos > cartucho magenta (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][67]="impressora > insumos > cartucho ciano claro (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][68]="impressora > insumos > cartucho ciano claro (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][69]="impressora > insumos > cartucho magenta claro (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][70]="impressora > insumos > cartucho magenta claro (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][71]="impressora > insumos > fotocondutor (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][72]="impressora > insumos > fotocondutor (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][73]="impressora > insumos > fotocondutor preto (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][74]="impressora > insumos > fotocondutor preto (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][75]="impressora > insumos > fotocondutor colorido (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][76]="impressora > insumos > fotocondutor colorido (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][77]="impressora > insumos > fotocondutor ciano (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][78]="impressora > insumos > fotocondutor ciano (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][79]="impressora > insumos > fotocondutor amarelo (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][80]="impressora > insumos > fotocondutor amarelo (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][81]="impressora > insumos > fotocondutor magenta (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][82]="impressora > insumos > fotocondutor magenta (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][83]="impressora > insumos > unidade de transferência preto (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][84]="impressora > insumos > unidade de transferência preto (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][85]="impressora > insumos > unidade de transferência ciano (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][86]="impressora > insumos > unidade de transferência ciano (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][87]="impressora > insumos > unidade de transferência amarelo (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][88]="impressora > insumos > unidade de transferência amarelo (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][89]="impressora > insumos > unidade de transferência magenta (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][90]="impressora > insumos > unidade de transferência magenta (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][91]="impressora > insumos > resíduos (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][92]="impressora > insumos > resíduos (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][93]="impressora > insumos > quatro (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][94]="impressora > insumos > quatro (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][95]="impressora > insumos > módulo de limpeza (tinta cheia)";
$LANG['plugin_fusioninventory']["mapping"][96]="impressora > insumos > módulo de limpeza (tinta restante)";
$LANG['plugin_fusioninventory']["mapping"][97]="impressora > porta > tipo";
$LANG['plugin_fusioninventory']["mapping"][98]="impressora > insumos > Kit de manutenção (cheio)";
$LANG['plugin_fusioninventory']["mapping"][99]="impressora > insumos > Kit de manutenção (restante)";
$LANG['plugin_fusioninventory']["mapping"][400]="impressora > insumos > Kit de manutenção (%)";
$LANG['plugin_fusioninventory']["mapping"][401]="rede > CPU usuário";
$LANG['plugin_fusioninventory']["mapping"][402]="rede > CPU sistema";
$LANG['plugin_fusioninventory']["mapping"][403]="rede > contato";
$LANG['plugin_fusioninventory']["mapping"][404]="rede > comentários";
$LANG['plugin_fusioninventory']["mapping"][405]="impressora > contato";
$LANG['plugin_fusioninventory']["mapping"][406]="impressora > comentários";
$LANG['plugin_fusioninventory']["mapping"][407]="impressora > porta > endereço IP";
$LANG['plugin_fusioninventory']["mapping"][408]="rede > porta > número do índice";
$LANG['plugin_fusioninventory']["mapping"][409]="rede > Endereço CDP";
$LANG['plugin_fusioninventory']["mapping"][410]="rede > Porta CDP";
$LANG['plugin_fusioninventory']["mapping"][411]="rede > porta > trunk/tagged";
$LANG['plugin_fusioninventory']["mapping"][412]="rede > filtros de endereço MAC (dot1dTpFdbAddress)";
$LANG['plugin_fusioninventory']["mapping"][413]="rede > Endereços físicos na memória (ipNetToMediaPhysAddress)";
$LANG['plugin_fusioninventory']["mapping"][414]="rede > Instâncias da porta (dot1dTpFdbPort)";
$LANG['plugin_fusioninventory']["mapping"][415]="rede > Número de portas atribuídas ao ID da porta (dot1dBasePortIfIndex)";
$LANG['plugin_fusioninventory']["mapping"][416]="impressora > porta > index number";
$LANG['plugin_fusioninventory']["mapping"][417]="rede > endereço MAC";
$LANG['plugin_fusioninventory']["mapping"][418]="impressora > Número do inventário";
$LANG['plugin_fusioninventory']["mapping"][419]="rede > Número do inventário";
$LANG['plugin_fusioninventory']["mapping"][420]="impressora > fabricante";
$LANG['plugin_fusioninventory']["mapping"][421]="rede > Endereços IP";
$LANG['plugin_fusioninventory']["mapping"][422]="rede> portVlanIndex";
$LANG['plugin_fusioninventory']["mapping"][423]="impressora > contador > número total de páginas impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][424]="impressora > contador > número de páginas preto e branco impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][425]="impressora > contador > número de páginas coloridas impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][426]="impressora > contador > número total de páginas impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][427]="impressora > contador > número de páginas preto e branco impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][428]="impressora > contador > número de páginas coloridas impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][429]="impressora > contador > número total de páginas impressas (fax)";
$LANG['plugin_fusioninventory']["mapping"][430]="rede > porta > vlan";


$LANG['plugin_fusioninventory']["mapping"][101]="";
$LANG['plugin_fusioninventory']["mapping"][102]="";
$LANG['plugin_fusioninventory']["mapping"][103]="";
$LANG['plugin_fusioninventory']["mapping"][104]="MTU";
$LANG['plugin_fusioninventory']["mapping"][105]="Velocidade";
$LANG['plugin_fusioninventory']["mapping"][106]="Status interno";
$LANG['plugin_fusioninventory']["mapping"][107]="Última mudança";
$LANG['plugin_fusioninventory']["mapping"][108]="Número de bytes recebidos";
$LANG['plugin_fusioninventory']["mapping"][109]="Número de bytes enviados";
$LANG['plugin_fusioninventory']["mapping"][110]="Número de erros de entrada";
$LANG['plugin_fusioninventory']["mapping"][111]="Número de erros de saída";
$LANG['plugin_fusioninventory']["mapping"][112]="Uso da CPU";
$LANG['plugin_fusioninventory']["mapping"][113]="";
$LANG['plugin_fusioninventory']["mapping"][114]="Conexão";
$LANG['plugin_fusioninventory']["mapping"][115]="Endereço MAC interno";
$LANG['plugin_fusioninventory']["mapping"][116]="Nome";
$LANG['plugin_fusioninventory']["mapping"][117]="Modelo";
$LANG['plugin_fusioninventory']["mapping"][118]="Tipo";
$LANG['plugin_fusioninventory']["mapping"][119]="VLAN";
$LANG['plugin_fusioninventory']["mapping"][128]="Número total de páginas impressas";
$LANG['plugin_fusioninventory']["mapping"][129]="Número de páginas preto e branco impressas";
$LANG['plugin_fusioninventory']["mapping"][130]="Número de páginas coloridas impressas";
$LANG['plugin_fusioninventory']["mapping"][131]="Número de páginas monocromáticas impressas";
$LANG['plugin_fusioninventory']["mapping"][132]="Número de páginas coloridas impressas";
$LANG['plugin_fusioninventory']["mapping"][134]="Cartucho preto";
$LANG['plugin_fusioninventory']["mapping"][135]="Cartucho preto de foto";
$LANG['plugin_fusioninventory']["mapping"][136]="Cartucho ciano";
$LANG['plugin_fusioninventory']["mapping"][137]="Cartucho amarelo";
$LANG['plugin_fusioninventory']["mapping"][138]="Cartucho magenta";
$LANG['plugin_fusioninventory']["mapping"][139]="Cartucho ciano claro";
$LANG['plugin_fusioninventory']["mapping"][140]="Cartucho magenta claro";
$LANG['plugin_fusioninventory']["mapping"][141]="Fotocondutor";
$LANG['plugin_fusioninventory']["mapping"][142]="Fotocondutor preto";
$LANG['plugin_fusioninventory']["mapping"][143]="Fotocondutor colorido";
$LANG['plugin_fusioninventory']["mapping"][144]="Fotocondutor ciano";
$LANG['plugin_fusioninventory']["mapping"][145]="Fotocondutor amarelo";
$LANG['plugin_fusioninventory']["mapping"][146]="Fotocondutor magenta";
$LANG['plugin_fusioninventory']["mapping"][147]="Unidade de transferência preto";
$LANG['plugin_fusioninventory']["mapping"][148]="Unidade de transferência ciano";
$LANG['plugin_fusioninventory']["mapping"][149]="Unidade de transferência amarelo";
$LANG['plugin_fusioninventory']["mapping"][150]="Unidade de transferência magenta";
$LANG['plugin_fusioninventory']["mapping"][151]="Resíduos";
$LANG['plugin_fusioninventory']["mapping"][152]="Quatro";
$LANG['plugin_fusioninventory']["mapping"][153]="Módulo de limpeza";
$LANG['plugin_fusioninventory']["mapping"][154]="Número de páginas duplex impressas";
$LANG['plugin_fusioninventory']["mapping"][155]="Número de páginas escaneadas";
$LANG['plugin_fusioninventory']["mapping"][156]="Kit de manutenção";
$LANG['plugin_fusioninventory']["mapping"][157]="Toner preto";
$LANG['plugin_fusioninventory']["mapping"][158]="Toner ciano";
$LANG['plugin_fusioninventory']["mapping"][159]="Toner magenta";
$LANG['plugin_fusioninventory']["mapping"][160]="Toner amarelo";
$LANG['plugin_fusioninventory']["mapping"][161]="Cilindro preto";
$LANG['plugin_fusioninventory']["mapping"][162]="Cilindro ciano";
$LANG['plugin_fusioninventory']["mapping"][163]="Cilindro magenta";
$LANG['plugin_fusioninventory']["mapping"][164]="Cilindro amarelo";
$LANG['plugin_fusioninventory']["mapping"][165]="Muitas informações agrupadas";
$LANG['plugin_fusioninventory']["mapping"][166]="Toner preto 2";
$LANG['plugin_fusioninventory']["mapping"][1423]="Número total de páginas impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][1424]="Número de páginas preto e branco impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][1425]="Número de páginas coloridas impressas (impressão)";
$LANG['plugin_fusioninventory']["mapping"][1426]="Número total de páginas impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][1427]="Número de páginas preto e branco impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][1428]="Número de páginas coloridas impressas (cópia)";
$LANG['plugin_fusioninventory']["mapping"][1429]="Número total de páginas impressas (fax)";


$LANG['plugin_fusioninventory']["printer"][0]="páginas";

$LANG['plugin_fusioninventory']["menu"][0]="Informações sobre dispositivos descobertos";
$LANG['plugin_fusioninventory']["menu"][1]="Configuração do agente";
$LANG['plugin_fusioninventory']["menu"][2]="Configuração da faixa de IP";
$LANG['plugin_fusioninventory']["menu"][3]="Menu";
$LANG['plugin_fusioninventory']["menu"][4]="Dispositivo desconhecido";
$LANG['plugin_fusioninventory']["menu"][5]="Histórico de portas dos Switchs";
$LANG['plugin_fusioninventory']["menu"][6]="Portas dos Switchs não usadas";
$LANG['plugin_fusioninventory']["menu"][7]="Trabalhos em execução";

$LANG['plugin_fusioninventory']["buttons"][0]="Descoberta";

$LANG['plugin_fusioninventory']["discovery"][0]="Faixa de IP a escanear";
$LANG['plugin_fusioninventory']["discovery"][1]="Dispositivos descobertos";
$LANG['plugin_fusioninventory']["discovery"][2]="Ativação do script automaticamente";
$LANG['plugin_fusioninventory']["discovery"][3]="Descobrir";
$LANG['plugin_fusioninventory']["discovery"][4]="Número de série";
$LANG['plugin_fusioninventory']["discovery"][5]="Número de dispositivos importados";
$LANG['plugin_fusioninventory']["discovery"][6]="Critério primário de existência";
$LANG['plugin_fusioninventory']["discovery"][7]="Critério secundário de existência";
$LANG['plugin_fusioninventory']["discovery"][8]="Se um dispositivo retornar um campo vazio no critério primário, o segundo será usado.";
$LANG['plugin_fusioninventory']["discovery"][9]="Número de dispositivos não importados devido ao tipo não definido";

$LANG['plugin_fusioninventory']["agents"][0]="Agente SNMP";
$LANG['plugin_fusioninventory']["agents"][2]="Número de segmentos usados pelo núcleo para a consulta de dispositivos";
$LANG['plugin_fusioninventory']["agents"][3]="Número de segmentos usados pelo núcleo para a descoberta de rede";
$LANG['plugin_fusioninventory']["agents"][4]="Último escaneamento";
$LANG['plugin_fusioninventory']["agents"][5]="Versão do agente";
$LANG['plugin_fusioninventory']["agents"][6]="Bloquear";
$LANG['plugin_fusioninventory']["agents"][7]="Exportar configuração do agente";
$LANG['plugin_fusioninventory']["agents"][9]="Opções avançadas";
$LANG['plugin_fusioninventory']["agents"][12]="Agente de descoberta";
$LANG['plugin_fusioninventory']["agents"][13]="Consultar agente";
$LANG['plugin_fusioninventory']["agents"][14]="Ações do agente";
$LANG['plugin_fusioninventory']["agents"][15]="Estado do agente";
$LANG['plugin_fusioninventory']["agents"][16]="Inicializado";
$LANG['plugin_fusioninventory']["agents"][17]="Agente está executado";
$LANG['plugin_fusioninventory']["agents"][18]="Inventário foi recebido";
$LANG['plugin_fusioninventory']["agents"][19]="Inventário foi enviado ao servidor OCS";
$LANG['plugin_fusioninventory']["agents"][20]="Sincronização entre o OCS e o GLPI está sendo executada";
$LANG['plugin_fusioninventory']["agents"][21]="Inventário terminado";
$LANG['plugin_fusioninventory']["agents"][22]="Espera";
$LANG['plugin_fusioninventory']["agents"][23]="Ligação de computador";
$LANG['plugin_fusioninventory']["agents"][24]="Token";
$LANG['plugin_fusioninventory']["agents"][25]="Versão";
$LANG['plugin_fusioninventory']["agents"][26]="Gerenciamento dos agentes";
$LANG['plugin_fusioninventory']["agents"][27]="Módulos dos agentes";
$LANG['plugin_fusioninventory']["agents"][28]="Agente";

$LANG['plugin_fusioninventory']["unknown"][0]="Nome DNS";
$LANG['plugin_fusioninventory']["unknown"][1]="Nome da porta de rede";
$LANG['plugin_fusioninventory']["unknown"][2]="Dispositivos aprovados";
$LANG['plugin_fusioninventory']["unknown"][3]="Descoberto pelo agente";
$LANG['plugin_fusioninventory']["unknown"][4]="Hub de rede";
$LANG['plugin_fusioninventory']["unknown"][5]="Importado de dispositivos desconhecidos (FusionInventory)";

$LANG['plugin_fusioninventory']["task"][0]="Tarefa";
$LANG['plugin_fusioninventory']["task"][1]="Gerenciamento de tarefa";
$LANG['plugin_fusioninventory']["task"][2]="Ação";
$LANG['plugin_fusioninventory']["task"][3]="Unidade";
$LANG['plugin_fusioninventory']["task"][4]="Obter informações agora";
$LANG['plugin_fusioninventory']["task"][5]="Selecionar agente OCS";
$LANG['plugin_fusioninventory']["task"][6]="Obter estado";
$LANG['plugin_fusioninventory']["task"][7]="Estado";
$LANG['plugin_fusioninventory']["task"][8]="Pronto";
$LANG['plugin_fusioninventory']["task"][9]="Não responde";
$LANG['plugin_fusioninventory']["task"][10]="Executando... não disponível";
$LANG['plugin_fusioninventory']["task"][11]="Agente foi notificado e começou a execução";
$LANG['plugin_fusioninventory']["task"][12]="Acordar agente";
$LANG['plugin_fusioninventory']["task"][13]="Agente(s) indisponível";
$LANG['plugin_fusioninventory']["task"][14]="Data da cobrança";
$LANG['plugin_fusioninventory']["task"][16]="Nova ação";
$LANG['plugin_fusioninventory']["task"][17]="Frequência";
$LANG['plugin_fusioninventory']["task"][18]="Tarefas";
$LANG['plugin_fusioninventory']["task"][19]="Tarefas em execução";
$LANG['plugin_fusioninventory']["task"][20]="Tarefas finalizadas";
$LANG['plugin_fusioninventory']["task"][21]="Ação sobre este material";
$LANG['plugin_fusioninventory']["task"][22]="Somente tarefas planejadas";

$LANG['plugin_fusioninventory']["taskjoblog"][1]="Iniciado";
$LANG['plugin_fusioninventory']["taskjoblog"][2]="Ok";
$LANG['plugin_fusioninventory']["taskjoblog"][3]="Erro / replanejado";
$LANG['plugin_fusioninventory']["taskjoblog"][4]="Erro";
$LANG['plugin_fusioninventory']["taskjoblog"][5]="desconhecido";

$LANG['plugin_fusioninventory']["update"][0]="sua tabela de histórico tem mais de 300.000 entradas, você deve executar este comando para finalizar a atualização : ";

$LANG['plugin_fusioninventory']["wakeonlan"][0]="Escolha de computadores";
$LANG['plugin_fusioninventory']["wakeonlan"][1]="Escolha de grupos dinâmicos";
$LANG['plugin_fusioninventory']["wakeonlan"][2]="Escolha de grupos simples";
$LANG['plugin_fusioninventory']["wakeonlan"][3]="Dispositivos de um outro trabalho desta tarefa";

?>