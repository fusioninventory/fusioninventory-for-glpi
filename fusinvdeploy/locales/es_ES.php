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
   @author    Tomas Abad 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2011
 
   ------------------------------------------------------------------------
 */

$title="FusionInventory DEPLOY";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']['title'][0]="$title";

$LANG['plugin_fusinvdeploy']['massiveactions'][0]="Target a deployment task";
$LANG['plugin_fusinvdeploy']['massiveactions'][1]="Create a job for each computer";
$LANG['plugin_fusinvdeploy']['massiveactions'][2]="Create a job for each group";

$LANG['plugin_fusinvdeploy']['package'][0]="Acciones";
$LANG['plugin_fusinvdeploy']['package'][1]="Ejecutar un comando";
$LANG['plugin_fusinvdeploy']['package'][2]="Launch (running file in package)";
$LANG['plugin_fusinvdeploy']['package'][3]="Exécuter (system executable)";
$LANG['plugin_fusinvdeploy']['package'][4]="Almacén";
$LANG['plugin_fusinvdeploy']['package'][5]="Paquetes";
$LANG['plugin_fusinvdeploy']['package'][6]="Gestión de paquetes";
$LANG['plugin_fusinvdeploy']['package'][7]="Paquete";
$LANG['plugin_fusinvdeploy']['package'][8]="Gestión de paquetes";
$LANG['plugin_fusinvdeploy']['package'][9]="Número de fragmentos";
$LANG['plugin_fusinvdeploy']['package'][10]="Módulo";
$LANG['plugin_fusinvdeploy']['package'][11]="Auditorías";
$LANG['plugin_fusinvdeploy']['package'][12]="Ficheros";
$LANG['plugin_fusinvdeploy']['package'][13]="Acciones";
$LANG['plugin_fusinvdeploy']['package'][14]="Instalación";
$LANG['plugin_fusinvdeploy']['package'][15]="Desinstalación";
$LANG['plugin_fusinvdeploy']['package'][16]="Despliegue de paquetes";
$LANG['plugin_fusinvdeploy']['package'][17]="Desinstalar paquete";
$LANG['plugin_fusinvdeploy']['package'][18]="Mover un fichero";
$LANG['plugin_fusinvdeploy']['package'][19]="partes de los ficheros";
$LANG['plugin_fusinvdeploy']['package'][20]="Borrar un fichero";
$LANG['plugin_fusinvdeploy']['package'][21]="Mostar diálogo";
$LANG['plugin_fusinvdeploy']['package'][22]="Devolver códigos";
$LANG['plugin_fusinvdeploy']['package'][23]="Una o más tareas activas (#task#) usan este paquete. Eliminación denegada.";
$LANG['plugin_fusinvdeploy']['package'][24]="Una o más tareas activas (#task#) usan este paquete. Edición denegada.";
$LANG['plugin_fusinvdeploy']['package'][25]="Nuevo nombre";
$LANG['plugin_fusinvdeploy']['package'][26]="Añadir un paquete";
$LANG['plugin_fusinvdeploy']['package'][27]="Crear un directorio";
$LANG['plugin_fusinvdeploy']['package'][28]="Copiar un fichero";

$LANG['plugin_fusinvdeploy']['files'][0]="Gestión de ficheros";
$LANG['plugin_fusinvdeploy']['files'][1]="Nombre del fichero";
$LANG['plugin_fusinvdeploy']['files'][2]="Versión";
$LANG['plugin_fusinvdeploy']['files'][3]="Sistema operativo";
$LANG['plugin_fusinvdeploy']['files'][4]="Fichero a cargar";
$LANG['plugin_fusinvdeploy']['files'][5]="Carpeta en el paquete";
$LANG['plugin_fusinvdeploy']['files'][6]="Tamaño máximo del fichero";
$LANG['plugin_fusinvdeploy']['files'][7]="Cargar desde";
$LANG['plugin_fusinvdeploy']['files'][8]="Este ordenador";
$LANG['plugin_fusinvdeploy']['files'][9]="El servidor";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Ficheros vinculados con el paquete";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Estado del despliegue";
$LANG['plugin_fusinvdeploy']['deploystatus'][1]="Ficheros de registro asociados";
$LANG['plugin_fusinvdeploy']['deploystatus'][2]="El agente recibió la petición de trabajo";
$LANG['plugin_fusinvdeploy']['deploystatus'][3]="El agente comenzó la comprobación del servidor espejo para descargar el fichero.";
$LANG['plugin_fusinvdeploy']['deploystatus'][4]="Preparando el directorio de trabajo";
$LANG['plugin_fusinvdeploy']['deploystatus'][5]="El agente está procesando el trabajo";

$LANG['plugin_fusinvdeploy']['config'][0]="Dirección del servidor GLPI (omita http(s)://)";
$LANG['plugin_fusinvdeploy']['config'][1]="Directorio raíz para el envío de ficheros desde el servidor";

$LANG['plugin_fusinvdeploy']['setup'][17]="Para instalar o activar ".$title." es necesario que FusionInventory este activado previamente.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Para desinstalar ".$title." es necesario que FusionInventory esté activado previamente.";
$LANG['plugin_fusinvdeploy']['setup'][19]="Para activar ".$title." es necesario contar con Webservices (>= 1.2.0) instalado previamente.";
$LANG['plugin_fusinvdeploy']['setup'][20]="Plugin desinstalar ".$title." es necesario contar con Webservices (>= 1.2.0) instalado previamente.";
$LANG['plugin_fusinvdeploy']['setup'][21]="Para instalar o activar ".$title." es necesario que FusionInventory INVENTORY este activado previamente.";

$LANG['plugin_fusinvdeploy']['profile'][1]="$title";
$LANG['plugin_fusinvdeploy']['profile'][2]="Gestión de paquetes";
$LANG['plugin_fusinvdeploy']['profile'][3]="Estado del despliegue";


$LANG['plugin_fusinvdeploy']['form']['label'][0] = "Tipo";
$LANG['plugin_fusinvdeploy']['form']['label'][1] = "Nombre";
$LANG['plugin_fusinvdeploy']['form']['label'][2] = "Valor";
$LANG['plugin_fusinvdeploy']['form']['label'][3] = "Unidad";
$LANG['plugin_fusinvdeploy']['form']['label'][4] = "Activo";
$LANG['plugin_fusinvdeploy']['form']['label'][5] = "Fichero";
$LANG['plugin_fusinvdeploy']['form']['label'][6] = "Despliegue P2P";
$LANG['plugin_fusinvdeploy']['form']['label'][7] = "Fecha añadida";
$LANG['plugin_fusinvdeploy']['form']['label'][8] = "Tiempo de validez";
$LANG['plugin_fusinvdeploy']['form']['label'][9] = "Tiempo de retención (en días)";
$LANG['plugin_fusinvdeploy']['form']['label'][10] = "Id";
$LANG['plugin_fusinvdeploy']['form']['label'][11] = "Comando";
$LANG['plugin_fusinvdeploy']['form']['label'][12] = "Disco o directorio";
$LANG['plugin_fusinvdeploy']['form']['label'][13] = "Clave";
$LANG['plugin_fusinvdeploy']['form']['label'][14] = "Valor de la clave";
$LANG['plugin_fusinvdeploy']['form']['label'][15] = "Fichero ausente";
$LANG['plugin_fusinvdeploy']['form']['label'][16] = "Desde";
$LANG['plugin_fusinvdeploy']['form']['label'][17] = "A";
$LANG['plugin_fusinvdeploy']['form']['label'][18] = "Eliminación";
$LANG['plugin_fusinvdeploy']['form']['label'][19] = "Descomprimir";
$LANG['plugin_fusinvdeploy']['form']['label'][20] = "Error de transmisión: el tamaño del fichero es demasiado grande";
$LANG['plugin_fusinvdeploy']['form']['label'][21] = "Tamaño del fichero";
$LANG['plugin_fusinvdeploy']['form']['label'][22] = "Error al copiar el fichero";

$LANG['plugin_fusinvdeploy']['form']['action'][0] = "Añadir";
$LANG['plugin_fusinvdeploy']['form']['action'][1] = "Eliminar";
$LANG['plugin_fusinvdeploy']['form']['action'][2] = "Correcto";
$LANG['plugin_fusinvdeploy']['form']['action'][3] = "Seleccione el fichero";
$LANG['plugin_fusinvdeploy']['form']['action'][4] = "¡Fichero salvado!";
$LANG['plugin_fusinvdeploy']['form']['action'][5] = "O URL";
$LANG['plugin_fusinvdeploy']['form']['action'][6] = "Añadir código de retorno";
$LANG['plugin_fusinvdeploy']['form']['action'][7] = "Eliminar código de retorno";

$LANG['plugin_fusinvdeploy']['form']['title'][0] = "Editar comprobación";
$LANG['plugin_fusinvdeploy']['form']['title'][1] = "Añadir comprobación";
$LANG['plugin_fusinvdeploy']['form']['title'][2] = "Lista de comprobaciones";
$LANG['plugin_fusinvdeploy']['form']['title'][3] = "Ficheros a copiar en el ordenador";
$LANG['plugin_fusinvdeploy']['form']['title'][4] = "Añadir fichero";
$LANG['plugin_fusinvdeploy']['form']['title'][5] = "Editar fichero";
$LANG['plugin_fusinvdeploy']['form']['title'][6] = "Añadir comando";
$LANG['plugin_fusinvdeploy']['form']['title'][7] = "Editar comando";
$LANG['plugin_fusinvdeploy']['form']['title'][8] = "Acciones a lograr";
$LANG['plugin_fusinvdeploy']['form']['title'][9] = "Eliminar comprobación";
$LANG['plugin_fusinvdeploy']['form']['title'][10] = "Añadir orden";
$LANG['plugin_fusinvdeploy']['form']['title'][11] = "Eliminar orden";
$LANG['plugin_fusinvdeploy']['form']['title'][12] = "Editar orden";
$LANG['plugin_fusinvdeploy']['form']['title'][13] = "Eliminar fichero";
$LANG['plugin_fusinvdeploy']['form']['title'][14] = "Eliminar comando";
$LANG['plugin_fusinvdeploy']['form']['title'][15] = "durante la instalación";
$LANG['plugin_fusinvdeploy']['form']['title'][16] = "durante la desinstalación";
$LANG['plugin_fusinvdeploy']['form']['title'][17] = "antes de la instalación";
$LANG['plugin_fusinvdeploy']['form']['title'][18] = "antes de la desinstalación";

$LANG['plugin_fusinvdeploy']['form']['message'][0] = "Formulario vacio";
$LANG['plugin_fusinvdeploy']['form']['message'][1] = "Formulario inválido";
$LANG['plugin_fusinvdeploy']['form']['message'][2] = "Cargando...";
$LANG['plugin_fusinvdeploy']['form']['message'][3] = "El fichero ya existe";

$LANG['plugin_fusinvdeploy']['form']['check'][0] = "Clave de registro existente";
$LANG['plugin_fusinvdeploy']['form']['check'][1] = "Clave de registro ausente";
$LANG['plugin_fusinvdeploy']['form']['check'][2] = "Valor de la clave de registro";
$LANG['plugin_fusinvdeploy']['form']['check'][3] = "Fichero existente";
$LANG['plugin_fusinvdeploy']['form']['check'][4] = "Fichero ausente";
$LANG['plugin_fusinvdeploy']['form']['check'][5] = "Tamaño del fichero";
$LANG['plugin_fusinvdeploy']['form']['check'][6] = "Valor HASH SHA-512";
$LANG['plugin_fusinvdeploy']['form']['check'][7] = "Espacio libre";
$LANG['plugin_fusinvdeploy']['form']['check'][8] = "Tamaño del fichero igual a";
$LANG['plugin_fusinvdeploy']['form']['check'][9] = "Tamaño del fichero menor que";

$LANG['plugin_fusinvdeploy']['form']['mirror'][1] = "Servidores espejo";
$LANG['plugin_fusinvdeploy']['form']['mirror'][2] = "Servidores espejo";
$LANG['plugin_fusinvdeploy']['form']['mirror'][3] = "Dirección del servidor espejo";

$LANG['plugin_fusinvdeploy']['form']['command_status'][0] = "Haga su elección...";
$LANG['plugin_fusinvdeploy']['form']['command_status'][1] = "Tipo";
$LANG['plugin_fusinvdeploy']['form']['command_status'][2] = "Valor";
$LANG['plugin_fusinvdeploy']['form']['command_status'][3] = "Código de retorno esperado";
$LANG['plugin_fusinvdeploy']['form']['command_status'][4] = "Código de retorno inválido";
$LANG['plugin_fusinvdeploy']['form']['command_status'][5] = "Expresión regular esperada";
$LANG['plugin_fusinvdeploy']['form']['command_status'][6] = "Expresión regular inválida";

$LANG['plugin_fusinvdeploy']['form']['command_envvariable'][1] = "Variable de entorno";

$LANG['plugin_fusinvdeploy']['form']['action_message'][1] = "Título";
$LANG['plugin_fusinvdeploy']['form']['action_message'][2] = "Contenido";
$LANG['plugin_fusinvdeploy']['form']['action_message'][3] = "Tipo";
$LANG['plugin_fusinvdeploy']['form']['action_message'][4] = "Informaciones";
$LANG['plugin_fusinvdeploy']['form']['action_message'][5] = "informe de la instalación";

$LANG['plugin_fusinvdeploy']['task'][0] = "Tareas de despliegue";
$LANG['plugin_fusinvdeploy']['task'][1] = "Tarea";
$LANG['plugin_fusinvdeploy']['task'][3] = "Añadir tarea";
$LANG['plugin_fusinvdeploy']['task'][5] = "Tarea";
$LANG['plugin_fusinvdeploy']['task'][7] = "Acciones";
$LANG['plugin_fusinvdeploy']['task'][8] = "Lista de acciones";
$LANG['plugin_fusinvdeploy']['task'][11] = "Editar tarea";
$LANG['plugin_fusinvdeploy']['task'][12] = "Añadir una tarea";
$LANG['plugin_fusinvdeploy']['task'][13] = "Lista de órdenes";
$LANG['plugin_fusinvdeploy']['task'][14] = "Opciones avanzadas";
$LANG['plugin_fusinvdeploy']['task'][15] = "Añadir orden";
$LANG['plugin_fusinvdeploy']['task'][16] = "Eliminar orden";
$LANG['plugin_fusinvdeploy']['task'][17] = "Editar orden";
$LANG['plugin_fusinvdeploy']['task'][18] = "---";
$LANG['plugin_fusinvdeploy']['task'][19] = "Esta tarea está activa. No es posible su edición.";
$LANG['plugin_fusinvdeploy']['task'][20] = "Esta tarea está activa. No es posible su eliminación.";

$LANG['plugin_fusinvdeploy']['group'][0] = "Grupos de ordenadores";
$LANG['plugin_fusinvdeploy']['group'][1] = "Grupo estático";
$LANG['plugin_fusinvdeploy']['group'][2] = "Grupo dinámico";
$LANG['plugin_fusinvdeploy']['group'][3] = "Grupo de ordenadores";
$LANG['plugin_fusinvdeploy']['group'][4] = "Añadir grupo";
$LANG['plugin_fusinvdeploy']['group'][5] = "If no line in the list is selected, the text fields on the left will be used for search.";

$LANG['plugin_fusinvdeploy']['menu'][1] = "Gestión de paquetes";
$LANG['plugin_fusinvdeploy']['menu'][2] = "Servidores espejo";
$LANG['plugin_fusinvdeploy']['menu'][3] = "Tareas de despliegue";
$LANG['plugin_fusinvdeploy']['menu'][4] = "Grupos de ordenadores";
$LANG['plugin_fusinvdeploy']['menu'][5] = "Tareas de despliegue";
?>
