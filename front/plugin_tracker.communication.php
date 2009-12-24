<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: test of communication class
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}
$NEEDED_ITEMS=array("computer","device","printer","networking","peripheral","monitor","software","infocom",
	"phone","tracking","enterprise","reservation","setup","group","registry","rulesengine","ocsng","admininfo");
include (GLPI_ROOT."/inc/includes.php");
//include("agent_communication.php");
$ptc = new PluginTrackerCommunication();
$res='';
$errors='';
file_put_contents(GLPI_PLUGIN_DOC_DIR."/tracker/dial.log".rand(), gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]));
$top0 = gettimeofday();
if (!$ptc->import(gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]))) {
   //if ($ac->connectionOK($errors)) {
   if (1) {
      $res .= "1'".$errors."'";

      $ptc->setXML("<?xml version='1.0' encoding='ISO-8859-1'?>
            <REPLY>
   <OPTION><NAME>DOWNLOAD</NAME>
   <PARAM FRAG_LATENCY=\"10\" PERIOD_LATENCY=\"10\" TIMEOUT=\"30\" ON=\"1\" TYPE=\"CONF\" CYCLE_LATENCY=\"60\" PERIOD_LENGTH=\"10\" /></OPTION>
               <RESPONSE>SEND</RESPONSE>
               <PROLOG_FREQ>24</PROLOG_FREQ>
   </REPLY>");

   // ******** NETDISCOVERY
      $ptc->addDiscovery();

   // ******** SNMPQUERY
//      $ptc->addQuery();

   // ******** Send XML
      $ptc->setXML($ptc->getXML());
      echo $ptc->getSend(); // echo response for the agent
   } else {
      $res .= "0'".$errors."'";
   }
} else {
   $top1 = gettimeofday();
   $duree["sec"]  = $top1["sec"]-$top0["sec"];
  $duree["usec"] = $top1["usec"]-$top0["usec"];

  if ($duree["usec"]<0) {
   $duree["sec"]--;
   $duree["usec"]+=1000000;
  }

 #  file_put_contents(GLPI_PLUGIN_DOC_DIR."/tracker/import.log", "Il a fallu ".$duree["sec"]." secondes et ". $duree["usec"]." microsecondes");
}
?>
