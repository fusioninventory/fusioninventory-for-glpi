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


$LANG['plugin_fusinvsnmp']['agents'][24]="Número de hilos";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agente(s)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Versión del módulo Netdiscovery";
$LANG['plugin_fusinvsnmp']['agents'][27]="Versión del módulo Snmpquery";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="dispositivos consultados";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="dispositivos encontrados";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="Las definiciones SNMP de los equipos no están actualizadas en el agente. En la próxima ejecución se actualizará la versión desde el servidor.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="añadir el elemento";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="modificar el elemento";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="Inventario iniciado";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Detalle";

$LANG['plugin_fusinvsnmp']['config'][10]="Tipo de puertos a importar (para equipos de red)";
$LANG['plugin_fusinvsnmp']['config'][3]="Inventario de red (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Descubrimiento de red";
$LANG['plugin_fusinvsnmp']['config'][8]="Nunca";
$LANG['plugin_fusinvsnmp']['config'][9]="Siempre";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestión de los MIB de dispositivos";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Creación automática de modelos";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Generar fichero de descubrimiento";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Eliminar modelos no usados";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Exportar todos los modelos";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Recrear comentarios de modelos";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Número de dispositivos importados";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Número de dispositivos no importados por no tener tipo definido";

$LANG['plugin_fusinvsnmp']['errors'][50]="Versión de GLPI no compatible, requerida la versión 0.78";

$LANG['plugin_fusinvsnmp']['legend'][0]="Conexión con un conmutador de red o un servidor en modo troncal o asociado";
$LANG['plugin_fusinvsnmp']['legend'][1]="Otras conexiones (con un ordenador, una impresora...)";

$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Velocidad";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Estado interno";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Último Cambio";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Número de bytes recibidos";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Número de bytes transmitidos";
$LANG['plugin_fusinvsnmp']['mapping'][10]="red > puerto > número de errores de entrada";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Número de errores de entrada";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Número de errores de salida";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Uso de CPU";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Conexión";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Dirección MAC interna";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Nombre";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Modelo";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Tipo";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="red > puerto > número de errores de salida";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Número total de páginas impresas";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Número de páginas B/N impresas";
$LANG['plugin_fusinvsnmp']['mapping'][12]="red > uso de CPU";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Número de páginas color impresas";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Número de páginas monocromo impresas";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Cartucho negro";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Cartucho foto negro";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Cartucho azul";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Cartucho amarillo";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Cartucho magenta";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Cartucho azul claro";
$LANG['plugin_fusinvsnmp']['mapping'][13]="red > número de serie";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Cartucho magenta claro";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Revelador";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Número total de páginas impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Número de páginas B/N impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Número de páginas color impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Número total de páginas impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Número de páginas B/N impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Número de páginas color impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Numero total de páginas impresas (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Revelador negro";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Número total de páginas grandes impresas";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Revelador Color";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Revelador azul";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Revelador amarillo";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Revelador magenta";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Negro unidad transfer";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Azul unidad transfer";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Amarillo unidad transfer";
$LANG['plugin_fusinvsnmp']['mapping'][14]="red > puerto > estado de la conexión";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Magenta unidad transfer";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Contenedor de residuos";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Cuaternidad";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Módulo de limpieza";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Número de páginas impresas duplex";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Número de páginas escaneadas";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Equipo mantenimiento";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Tóner negro";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Tóner azul";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Tóner magenta";
$LANG['plugin_fusinvsnmp']['mapping'][15]="red > puerto > dirección MAC";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Tóner amarillo";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Tambor negro";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Tambor azul";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Tambor magenta";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Tambor amarillo";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Mucha información agrupada";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Tóner negro 2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Tóner negro utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Tóner negro restante";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Tóner azul max";
$LANG['plugin_fusinvsnmp']['mapping'][16]="red > puerto > nombre";
$LANG['plugin_fusinvsnmp']['mapping'][170]="Tóner azul utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][171]="Tóner azul restante";
$LANG['plugin_fusinvsnmp']['mapping'][172]="Tóner magenta max";
$LANG['plugin_fusinvsnmp']['mapping'][173]="Tóner magenta utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][174]="Tóner magenta restante";
$LANG['plugin_fusinvsnmp']['mapping'][175]="Tóner amarillo max";
$LANG['plugin_fusinvsnmp']['mapping'][176]="Tóner amarillo utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][177]="Tóner amarillo restante";
$LANG['plugin_fusinvsnmp']['mapping'][178]="Tambor negro max";
$LANG['plugin_fusinvsnmp']['mapping'][179]="Tambor negro utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][17]="red > modelo";
$LANG['plugin_fusinvsnmp']['mapping'][180]="Tambor negro restante";
$LANG['plugin_fusinvsnmp']['mapping'][181]="Tambor azul max";
$LANG['plugin_fusinvsnmp']['mapping'][182]="Tambor azul utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][183]="Tambor azul restante";
$LANG['plugin_fusinvsnmp']['mapping'][184]="Tambor magenta max";
$LANG['plugin_fusinvsnmp']['mapping'][185]="Tambor magenta utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][186]="Tambor magenta restante";
$LANG['plugin_fusinvsnmp']['mapping'][187]="Tambor amarillo max";
$LANG['plugin_fusinvsnmp']['mapping'][188]="Tambor amarillo utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][189]="Tambor amarillo restante";
$LANG['plugin_fusinvsnmp']['mapping'][18]="red > puerto > tipo";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Contenedor de residuos max";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Contenedor de residuos utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Contenedor de residuos restante";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Equipo de mantenimiento max";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Equipo de mantenimiento utilizado";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Equipo de mantenimiento restante";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Cartucho tinta gris";
$LANG['plugin_fusinvsnmp']['mapping'][19]="red > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][1]="red > localización";
$LANG['plugin_fusinvsnmp']['mapping'][20]="red > nombre";
$LANG['plugin_fusinvsnmp']['mapping'][21]="red > memoria total";
$LANG['plugin_fusinvsnmp']['mapping'][22]="red > memoria libre";
$LANG['plugin_fusinvsnmp']['mapping'][23]="red > puerto > descripción del puerto";
$LANG['plugin_fusinvsnmp']['mapping'][24]="impresora > nombre";
$LANG['plugin_fusinvsnmp']['mapping'][25]="impresora > modelo";
$LANG['plugin_fusinvsnmp']['mapping'][26]="impresora > memoria total";
$LANG['plugin_fusinvsnmp']['mapping'][27]="impresora > número de serie";
$LANG['plugin_fusinvsnmp']['mapping'][28]="impresora > contador > número total de páginas impresas";
$LANG['plugin_fusinvsnmp']['mapping'][29]="impresora > contador > número de páginas B/N impresas";
$LANG['plugin_fusinvsnmp']['mapping'][2]="red > firmware";
$LANG['plugin_fusinvsnmp']['mapping'][30]="impresora > contador > número de páginas color impresas";
$LANG['plugin_fusinvsnmp']['mapping'][31]="impresora > contador > número de páginas monocromo impresas";
$LANG['plugin_fusinvsnmp']['mapping'][33]="red > puerto > tipo duplex";
$LANG['plugin_fusinvsnmp']['mapping'][34]="impresora > consumibles > cartucho negro (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="impresora > consumibles > cartucho foto negro (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="impresora > consumibles > cartucho azul (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="impresora > consumibles > cartucho amarillo (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="impresora > consumibles > cartucho magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="impresora > consumibles > cartucho azul claro (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="red > tiempo en funcionamiento";
$LANG['plugin_fusinvsnmp']['mapping'][400]="impresora > consumibles > equipo de mantenimiento (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="red > CPU del usuario";
$LANG['plugin_fusinvsnmp']['mapping'][402]="red > CPU del sistema";
$LANG['plugin_fusinvsnmp']['mapping'][403]="red > contacto";
$LANG['plugin_fusinvsnmp']['mapping'][404]="red > comentarios";
$LANG['plugin_fusinvsnmp']['mapping'][405]="impresora > contacto";
$LANG['plugin_fusinvsnmp']['mapping'][406]="impresora > comentarios";
$LANG['plugin_fusinvsnmp']['mapping'][407]="impresora > puerto > dirección IP";
$LANG['plugin_fusinvsnmp']['mapping'][408]="red > puerto > número de índice";
$LANG['plugin_fusinvsnmp']['mapping'][409]="red > dirección CDP";
$LANG['plugin_fusinvsnmp']['mapping'][40]="impresora > consumibles > cartucho magenta claro (%)";
$LANG['plugin_fusinvsnmp']['mapping'][410]="red > puerto CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="red > puerto > troncal/asociado";
$LANG['plugin_fusinvsnmp']['mapping'][412]="red > filtros de dirección MAC (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="red > direcciones físicas en memoria (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="red > instancias de puertos (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="red > número de puertos asociados al id del puerto (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="impresora > puerto > número de índice";
$LANG['plugin_fusinvsnmp']['mapping'][417]="red > dirección MAC";
$LANG['plugin_fusinvsnmp']['mapping'][418]="impresora > número de inventario";
$LANG['plugin_fusinvsnmp']['mapping'][419]="red > número de inventario";
$LANG['plugin_fusinvsnmp']['mapping'][41]="impresora > consumibles > revelador (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="impresora > fabricante";
$LANG['plugin_fusinvsnmp']['mapping'][421]="red > direcciones IP";
$LANG['plugin_fusinvsnmp']['mapping'][422]="red > PVID (puerto VLAN ID)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="impresora > contador > número total de páginas impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="impresora > contador > número de páginas B/N impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="impresora > contador > número de páginas color impresas (impresión)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="impresora > contador > número total de páginas impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="impresora > contador > número de páginas B/N impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="impresora > contador > número de páginas color impresas (copia)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="impresora > contador > número total de páginas impresas (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="impresora > consumibles > revelador negro (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="red > puerto > vlan";
$LANG['plugin_fusinvsnmp']['mapping'][435]="red > descripción del sistema remoto CDP";
$LANG['plugin_fusinvsnmp']['mapping'][436]="red > id remoto CDP";
$LANG['plugin_fusinvsnmp']['mapping'][437]="red > modelo de dispositivo remoto CDP";
$LANG['plugin_fusinvsnmp']['mapping'][438]="red > descripción del sistema remoto LLDP";
$LANG['plugin_fusinvsnmp']['mapping'][439]="red > id remoto LLDP";
$LANG['plugin_fusinvsnmp']['mapping'][43]="impresora > consumibles > revelador color (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="red > descripción del puerto remoto LLDP";
$LANG['plugin_fusinvsnmp']['mapping'][44]="impresora > consumibles > revelador azul (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="impresora > consumibles > revelador amarillo (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="impresora > consumibles > revelador magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="impresora > consumibles > negro unidad transfer (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="impresora > consumibles > azul unidad transfer (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="impresora > consumibles > amarillo unidad transfer (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="red > puerto > mtu";
$LANG['plugin_fusinvsnmp']['mapping'][50]="impresora > consumibles > magenta unidad transfer (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="impresora > consumibles > contenedor de residuos (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="impresora > consumibles > cuaternidad (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="impresora > consumibles > módulo de limpieza (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="impresora > contador > número de páginas duplex impresas";
$LANG['plugin_fusinvsnmp']['mapping'][55]="impresora > contador > número of páginas escaneadas";
$LANG['plugin_fusinvsnmp']['mapping'][56]="impresora > localización";
$LANG['plugin_fusinvsnmp']['mapping'][57]="impresora > puerto > nombre";
$LANG['plugin_fusinvsnmp']['mapping'][58]="impresora > puerto > dirección MAC";
$LANG['plugin_fusinvsnmp']['mapping'][59]="impresora > consumibles > cartucho negro (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][5]="red > puerto > velocidad";
$LANG['plugin_fusinvsnmp']['mapping'][60]="impresora > consumibles > cartucho negro (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="impresora > consumibles > cartucho azul (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="impresora > consumibles > cartucho azul (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="impresora > consumibles > cartucho amarillo (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="impresora > consumibles > cartucho amarillo (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="impresora > consumibles > cartucho magenta (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="impresora > consumibles > cartucho magenta (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="impresora > consumibles > cartucho azul claro (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="impresora > consumibles > cartucho azul claro (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="impresora > consumibles > cartucho magenta claro (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][6]="red > puerto > estado interno";
$LANG['plugin_fusinvsnmp']['mapping'][70]="impresora > consumibles > cartucho magenta claro (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="impresora > consumibles > fotoconductor (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="impresora > consumibles > fotoconductor (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="impresora > consumibles > revelador negro (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="impresora > consumibles > revelador negro (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="impresora > consumibles > revelador color (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="impresora > consumibles > revelador color (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="impresora > consumibles > revelador azul (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="impresora > consumibles > revelador azul (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="impresora > consumibles > revelador amarillo (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][7]="red > puertos > último cambio";
$LANG['plugin_fusinvsnmp']['mapping'][80]="impresora > consumibles > revelador amarillo (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="impresora > consumibles > revelador magenta (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="impresora > consumibles > revelador magenta (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="impresora > consumibles > negro unidad transfer (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="impresora > consumibles > negro unidad transfer (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="impresora > consumibles > azul unidad transfer (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="impresora > consumibles > azul unidad transfer (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="impresora > consumibles > amarillo unidad transfer (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="impresora > consumibles > amarillo unidad transfer (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="impresora > consumibles > magenta unidad transfer (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][8]="red > puerto > número de bytes entrantes";
$LANG['plugin_fusinvsnmp']['mapping'][90]="impresora > consumibles > magenta unidad transfer (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="impresora > consumibles > contenedor de residuos (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="impresora > consumibles > contenedor de residuos (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="impresora > consumibles > cuaternidad (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="impresora > consumibles > cuaternidad (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="impresora > consumibles > módulo de limpieza (tinta max)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="impresora > consumibles > módulo de limpieza (tinta restante)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="impresora > puerto > tipo";
$LANG['plugin_fusinvsnmp']['mapping'][98]="impresora > consumibles > equipo de mantenimiento (max)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="impresora > consumibles > equipo de mantenimiento (restante)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="red > puerto > número de bytes salientes";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Asignar modelo SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Asignar autenticación SNMP";

$LANG['plugin_fusinvsnmp']['menu'][10]="Estado del inventario de red";
$LANG['plugin_fusinvsnmp']['menu'][2]="Configuración rango direcciones IP";
$LANG['plugin_fusinvsnmp']['menu'][5]="Historial de puertos del conmutador de red";
$LANG['plugin_fusinvsnmp']['menu'][6]="Puertos no usados en conmutadores de red";
$LANG['plugin_fusinvsnmp']['menu'][9]="Estado del descubrimiento de red";

$LANG['plugin_fusinvsnmp']['mib'][1]="Etiqueta MIB";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objeto";
$LANG['plugin_fusinvsnmp']['mib'][3]="OID";
$LANG['plugin_fusinvsnmp']['mib'][4]="añadir un OID...";
$LANG['plugin_fusinvsnmp']['mib'][5]="Lista de los OID";
$LANG['plugin_fusinvsnmp']['mib'][6]="Contadores de Puerto";
$LANG['plugin_fusinvsnmp']['mib'][7]="Puerto dinámico (.x)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Campos conectados";

$LANG['plugin_fusinvsnmp']['model_info'][10]="Importación de modelo SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][11]="está activo";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Clave del modelo de descubrimiento";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Cargar el modelo correcto";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Cargar el modelo SNMP correcto";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Importación masiva de modelos";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Importación masiva de modelos en la carpeta plugins/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Versión SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="Autenticación SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Modelos SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Editar modelo SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Crear modelo SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Importación completada con éxito";

$LANG['plugin_fusinvsnmp']['portlogs'][0]="Historial de la configuración";
$LANG['plugin_fusinvsnmp']['portlogs'][1]="Lista de campos de los que guardar historial";
$LANG['plugin_fusinvsnmp']['portlogs'][2]="Retención (en días)";

$LANG['plugin_fusinvsnmp']['printhistory'][1]="Demasiados datos para mostrar";

$LANG['plugin_fusinvsnmp']['processes'][37]="Total de direcciones IP";

$LANG['plugin_fusinvsnmp']['profile'][2]="Configuración";
$LANG['plugin_fusinvsnmp']['profile'][4]="Rango de direcciones IP";
$LANG['plugin_fusinvsnmp']['profile'][5]="Equipo de red SNMP";
$LANG['plugin_fusinvsnmp']['profile'][6]="Impresora SNMP";
$LANG['plugin_fusinvsnmp']['profile'][7]="Modelo SNMP";
$LANG['plugin_fusinvsnmp']['profile'][8]="Informe de impresoras";
$LANG['plugin_fusinvsnmp']['profile'][9]="Informe de red";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Historial y estadísticas de contadores de impresoras";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Total de páginas impresas";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Páginas / día";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="Historal del contador de impresora";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Fecha";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Contador";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Unidad de tiempo";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Añadir una impresora";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Quitar una impresora";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="día";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="semana";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="mes";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="año";
$LANG['plugin_fusinvsnmp']['prt_history'][38]="Impresoras a comparar";

$LANG['plugin_fusinvsnmp']['report'][0]="Número de días desde último inventario";
$LANG['plugin_fusinvsnmp']['report'][1]="Contador de páginas impresas";

$LANG['plugin_fusinvsnmp']['setup'][17]="El plugin FusionInventory SNMP requiere que el plugin FusionInventory esté activado previamente.";
$LANG['plugin_fusinvsnmp']['setup'][18]="El plugin FusionInventory SNMP requiere que el plugin FusionInventory esté activado previamente.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Convirtiendo historial del puerto";
$LANG['plugin_fusinvsnmp']['setup'][20]="Moviendo el historial de conexiones creadas";
$LANG['plugin_fusinvsnmp']['setup'][21]="Moviendo el historial de conexiones eliminadas";

$LANG['plugin_fusinvsnmp']['snmp'][12]="Tiempo en funcionamento";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Uso de CPU (en %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Uso de memoria (en %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Array de puertos";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Descripción del puerto";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Número de bytes recibidos";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Número de bytes enviados";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Número de errores en recepción";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Descripción del dispositivo";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Duplex";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Último inventario";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Datos no disponibles";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Consultas por segundo";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Comunidad";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Usuario";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Protocolo de cifrado para autenticación";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Contraseña";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Protocolo de cifrado para datos";

$LANG['plugin_fusinvsnmp']['state'][10]="Dispositivos importados";
$LANG['plugin_fusinvsnmp']['state'][4]="Fecha de inicio";
$LANG['plugin_fusinvsnmp']['state'][5]="Fecha de fin";
$LANG['plugin_fusinvsnmp']['state'][6]="Dispositivos descubiertos";
$LANG['plugin_fusinvsnmp']['state'][7]="Total con error";
$LANG['plugin_fusinvsnmp']['state'][8]="Dispositivos no importados";
$LANG['plugin_fusinvsnmp']['state'][9]="Dispositivos actualizados";

$LANG['plugin_fusinvsnmp']['stats'][0]="Contador total";
$LANG['plugin_fusinvsnmp']['stats'][1]="páginas por día";
$LANG['plugin_fusinvsnmp']['stats'][2]="Mostrar";

$LANG['plugin_fusinvsnmp']['task'][15]="Tarea permanente";
$LANG['plugin_fusinvsnmp']['task'][17]="Tipo de comunicación";
$LANG['plugin_fusinvsnmp']['task'][18]="Crear tarea fácilmente";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="Información SNMP";
$LANG['plugin_fusinvsnmp']['title'][2]="historial de conexiones";
$LANG['plugin_fusinvsnmp']['title'][5]="Bloqueos de FusionInventory";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>