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
$version="2.3.6";

$LANG['plugin_fusioninventory']['title'][0] ="$title";
$LANG['plugin_fusioninventory']['title'][1] ="FusInv";
$LANG['plugin_fusioninventory']['title'][5] ="Bloqueos";

$LANG['plugin_fusioninventory']['config'][0] = "Frecuencia de inventarios (en horas)";

$LANG['plugin_fusioninventory']['profile'][0] = "Gestión de derechos";
$LANG['plugin_fusioninventory']['profile'][2] = "Agentes";
$LANG['plugin_fusioninventory']['profile'][3] = "Control remoto de los agentes";
$LANG['plugin_fusioninventory']['profile'][4] = "Configuración";
$LANG['plugin_fusioninventory']['profile'][5] = "WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6] = "Material desconocido";
$LANG['plugin_fusioninventory']['profile'][7] = "Tareas";

$LANG['plugin_fusioninventory']['setup'][16] = "Documentación";
$LANG['plugin_fusioninventory']['setup'][17] = "El resto de plugins FusionInventory (fusinv...) deben ser desinstalados antes de desinstalar el plugin FusionInventory.";

$LANG['plugin_fusioninventory']['functionalities'][0]  = "Funcilonalidades";
$LANG['plugin_fusioninventory']['functionalities'][2]  = "Configuración general";
$LANG['plugin_fusioninventory']['functionalities'][6]  = "Leyenda";
$LANG['plugin_fusioninventory']['functionalities'][8]  = "Puerto del agente";
$LANG['plugin_fusioninventory']['functionalities'][9]  = "Retención en días";
$LANG['plugin_fusioninventory']['functionalities'][16] = "Almacén de la autentificación SNMP";
$LANG['plugin_fusioninventory']['functionalities'][17] = "Base de datos";
$LANG['plugin_fusioninventory']['functionalities'][18] = "Archivos";
$LANG['plugin_fusioninventory']['functionalities'][19] = "Por favor, configure el almacenamiento de autenticación en el plugin de configuración SNMP";
$LANG['plugin_fusioninventory']['functionalities'][27] = "SSL sólo para el agente";
$LANG['plugin_fusioninventory']['functionalities'][29] = "Lista de campos con histórico";
$LANG['plugin_fusioninventory']['functionalities'][32] = "Borrar las tareas termandas después de";
$LANG['plugin_fusioninventory']['functionalities'][60] = "Limpiar el histórico";
$LANG['plugin_fusioninventory']['functionalities'][73] = "Campos";
$LANG['plugin_fusioninventory']['functionalities'][74] = "Valores";
$LANG['plugin_fusioninventory']['functionalities'][75] = "Bloqueos";
$LANG['plugin_fusioninventory']['functionalities'][76] = "Extra-debug";

$LANG['plugin_fusioninventory']['errors'][22] = "Elemento desatentido en";
$LANG['plugin_fusioninventory']['errors'][50] = "La versión de GLPI no es compatible, necesita la versión 0.78";
$LANG['plugin_fusioninventory']['errors'][1] = "PHP allow_url_fopen está desactivado, imposible llamar al agente para hacer el inventario";
$LANG['plugin_fusioninventory']['errors'][2] = "PHP allow_url_fopen está desactivado, el modo push no puede funcionar";

$LANG['plugin_fusioninventory']['rules'][2]  = "Reglas de importación y enlace de los materiales";
$LANG['plugin_fusioninventory']['rules'][3]  = "Buscar materiales GLPI según el estado";
$LANG['plugin_fusioninventory']['rules'][4]  = "Entidad de destino de la maquina";
$LANG['plugin_fusioninventory']['rules'][5]  = "Enlace FusionInventory";
$LANG['plugin_fusioninventory']['rules'][6]  = "Enlazar si es posible, si no, importación rechazada";
$LANG['plugin_fusioninventory']['rules'][7]  = "Enlazar si es posible";
$LANG['plugin_fusioninventory']['rules'][8]  = "Enviar";
$LANG['plugin_fusioninventory']['rules'][9]  = "existe";
$LANG['plugin_fusioninventory']['rules'][10] = "no existe";
$LANG['plugin_fusioninventory']['rules'][11] = "ya existe en GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "está vacío";
$LANG['plugin_fusioninventory']['rules'][13] = "Número de serie del disco duro";
$LANG['plugin_fusioninventory']['rules'][14] = "Número de serie de la partición del disco";
$LANG['plugin_fusioninventory']['rules'][15] = "uuid";
$LANG['plugin_fusioninventory']['rules'][16] = "Etiqueta FusionInventory";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Material a importar";

$LANG['plugin_fusioninventory']['choice'][0] = "No";
$LANG['plugin_fusioninventory']['choice'][1] = "Sí";
$LANG['plugin_fusioninventory']['choice'][2] = "o";
$LANG['plugin_fusioninventory']['choice'][3] = "y";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Número de proceso";

$LANG['plugin_fusioninventory']['menu'][1]="Gestión de agentes";
$LANG['plugin_fusioninventory']['menu'][3]="Menú";
$LANG['plugin_fusioninventory']['menu'][4]="Material desconocido";
$LANG['plugin_fusioninventory']['menu'][7]="Acciones en curso";

$LANG['plugin_fusioninventory']['discovery'][5]="Número de materiales importados";
$LANG['plugin_fusioninventory']['discovery'][9]="Número de materiales no importados por no tener un tipo definido";

$LANG['plugin_fusioninventory']['agents'][4]="Último contacto del agente";
$LANG['plugin_fusioninventory']['agents'][6]="Desactivado";
$LANG['plugin_fusioninventory']['agents'][15]="Estado del agente";
$LANG['plugin_fusioninventory']['agents'][17]="El agente se ejecuta";
$LANG['plugin_fusioninventory']['agents'][22]="Pendiente";
$LANG['plugin_fusioninventory']['agents'][23]="Enlazado al ordenador";
$LANG['plugin_fusioninventory']['agents'][24]="Muestra";
$LANG['plugin_fusioninventory']['agents'][25]="Versión";
$LANG['plugin_fusioninventory']['agents'][27]="Módulos de agentes";
$LANG['plugin_fusioninventory']['agents'][28]="Agente";
$LANG['plugin_fusioninventory']['agents'][30]="¡Imposible llegar al agente!";
$LANG['plugin_fusioninventory']['agents'][31]="Forzar el inventario";
$LANG['plugin_fusioninventory']['agents'][32]="Auto gestión dinámica de agentes";
$LANG['plugin_fusioninventory']['agents'][33]="Auto gestión dinámica de agentes (misma subred)";
$LANG['plugin_fusioninventory']['agents'][34]="Activación (por defecto)";
$LANG['plugin_fusioninventory']['agents'][35]="Identificador";
$LANG['plugin_fusioninventory']['agents'][36]="Módulos del agente";
$LANG['plugin_fusioninventory']['agents'][37]="Bloqueado";
$LANG['plugin_fusioninventory']['agents'][38]="Disponible";
$LANG['plugin_fusioninventory']['agents'][39]="En funcionamiento";
$LANG['plugin_fusioninventory']['agents'][40]="Ordedandor sin IP conocida";

$LANG['plugin_fusioninventory']['unknown'][2]="Material aprobado";
$LANG['plugin_fusioninventory']['unknown'][4]="Hub de red";
$LANG['plugin_fusioninventory']['unknown'][5]="Matetiral desconocido a importar en el inventario";

$LANG['plugin_fusioninventory']['task'][0]="Tarea";
$LANG['plugin_fusioninventory']['task'][1]="Gestión de tareas";
$LANG['plugin_fusioninventory']['task'][2]="Acción";
$LANG['plugin_fusioninventory']['task'][14]="Fecha de ejecución";
$LANG['plugin_fusioninventory']['task'][16]="Nueva acción";
$LANG['plugin_fusioninventory']['task'][17]="Periodicidad"; 
$LANG['plugin_fusioninventory']['task'][18]="Tareas";
$LANG['plugin_fusioninventory']['task'][19]="Tareas en curso";
$LANG['plugin_fusioninventory']['task'][20]="Tareas terminadas";
$LANG['plugin_fusioninventory']['task'][21]="Acción sobre este material";
$LANG['plugin_fusioninventory']['task'][22]="Tareas sólo planificadas";
$LANG['plugin_fusioninventory']['task'][24]="Número de intentos";
$LANG['plugin_fusioninventory']['task'][25]="Tiempo entre 2 intentos (en minutos)";
$LANG['plugin_fusioninventory']['task'][26]="Módulo";
$LANG['plugin_fusioninventory']['task'][27]="Definición";
$LANG['plugin_fusioninventory']['task'][28]="Acción";
$LANG['plugin_fusioninventory']['task'][29]="Tipo";
$LANG['plugin_fusioninventory']['task'][30]="Selección";
$LANG['plugin_fusioninventory']['task'][31]="Tiempo entre el comienzo de la tarea y el comienzo de esta acción";
$LANG['plugin_fusioninventory']['task'][32]="Forzar la parada";
$LANG['plugin_fusioninventory']['task'][33]="Comunicación";
$LANG['plugin_fusioninventory']['task'][34]="Permanente";
$LANG['plugin_fusioninventory']['task'][35]="minutos";
$LANG['plugin_fusioninventory']['task'][36]="horas";
$LANG['plugin_fusioninventory']['task'][37]="días";
$LANG['plugin_fusioninventory']['task'][38]="mes";
$LANG['plugin_fusioninventory']['task'][39]="¡Imposible lanzar la tarea porque quedan acciones en curso!";
$LANG['plugin_fusioninventory']['task'][40]="Forzar la ejecución";
$LANG['plugin_fusioninventory']['task'][41]="El servidor inicia el contacto con el agente (push)";
$LANG['plugin_fusioninventory']['task'][42]="El agente inicia el contacto con el servidor (pull)";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Iniciado";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Error / replanificado";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Error";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="Desconocido";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="En curso";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Preparado";

$LANG['plugin_fusioninventory']['update'][0]="El histórico tiene más de 300.000 líneas, es necesario ejecutar esta orden en línea de comandos para terminar la actualización : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Muestra mala, imposible actuar sobre el agente";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Agente parado o fallido";
$LANG['plugin_fusioninventory']['codetasklog'][3]=$LANG['ocsconfig'][11];

$LANG['plugin_fusioninventory']['locks'][0]="Suprimir los bloqueos";
$LANG['plugin_fusioninventory']['locks'][1]="Añadir bloqueos";

?>
