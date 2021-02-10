<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the differents type of network ports.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the differents type of network ports.
 */
class PluginFusioninventoryNetworkporttype extends CommonDBTM {


   /**
    * Initialize all port types
    *
    * @global object $DB
    */
   function init() {
      global $DB;

      $input = [];

      $input['1'] = 'other';
      $input['2'] = 'regular1822';
      $input['3'] = 'hdh1822';
      $input['4'] = 'ddnX25';
      $input['5'] = 'rfc877x25';
      $input['6'] = 'ethernetCsmacd';
      $input['7'] = 'iso88023Csmacd';
      $input['8'] = 'iso88024TokenBus';
      $input['9'] = 'iso88025TokenRing';
      $input['10'] = 'iso88026Man';
      $input['11'] = 'starLan';
      $input['12'] = 'proteon10Mbit';
      $input['13'] = 'proteon80Mbit';
      $input['14'] = 'hyperchannel';
      $input['15'] = 'fddi';
      $input['16'] = 'lapb';
      $input['17'] = 'sdlc';
      $input['18'] = 'ds1';
      $input['19'] = 'e1';
      $input['20'] = 'basicISDN';
      $input['21'] = 'primaryISDN';
      $input['22'] = 'propPointToPointSerial';
      $input['23'] = 'ppp';
      $input['24'] = 'softwareLoopback';
      $input['25'] = 'eon';
      $input['26'] = 'ethernet3Mbit';
      $input['27'] = 'nsip';
      $input['28'] = 'slip';
      $input['29'] = 'ultra';
      $input['30'] = 'ds3';
      $input['31'] = 'sip';
      $input['32'] = 'frameRelay';
      $input['33'] = 'rs232';
      $input['34'] = 'para';
      $input['35'] = 'arcnet';
      $input['36'] = 'arcnetPlus';
      $input['37'] = 'atm';
      $input['38'] = 'miox25';
      $input['39'] = 'sonet';
      $input['40'] = 'x25ple';
      $input['41'] = 'iso88022llc';
      $input['42'] = 'localTalk';
      $input['43'] = 'smdsDxi';
      $input['44'] = 'frameRelayService';
      $input['45'] = 'v35';
      $input['46'] = 'hssi';
      $input['47'] = 'hippi';
      $input['48'] = 'modem';
      $input['49'] = 'aal5';
      $input['50'] = 'sonetPath';
      $input['51'] = 'sonetVT';
      $input['52'] = 'smdsIcip';
      $input['53'] = 'propVirtual';
      $input['54'] = 'propMultiplexor';
      $input['55'] = 'ieee80212';
      $input['56'] = 'fibreChannel';
      $input['57'] = 'hippiInterface';
      $input['58'] = 'frameRelayInterconnect';
      $input['59'] = 'aflane8023';
      $input['60'] = 'aflane8025';
      $input['61'] = 'cctEmul';
      $input['62'] = 'fastEther';
      $input['63'] = 'isdn';
      $input['64'] = 'v11';
      $input['65'] = 'v36';
      $input['66'] = 'g703at64k';
      $input['67'] = 'g703at2mb';
      $input['68'] = 'qllc';
      $input['69'] = 'fastEtherFX';
      $input['70'] = 'channel';
      $input['71'] = 'ieee80211';
      $input['72'] = 'ibm370parChan';
      $input['73'] = 'escon';
      $input['74'] = 'dlsw';
      $input['75'] = 'isdns';
      $input['76'] = 'isdnu';
      $input['77'] = 'lapd';
      $input['78'] = 'ipSwitch';
      $input['79'] = 'rsrb';
      $input['80'] = 'atmLogical';
      $input['81'] = 'ds0';
      $input['82'] = 'ds0Bundle';
      $input['83'] = 'bsc';
      $input['84'] = 'async';
      $input['85'] = 'cnr';
      $input['86'] = 'iso88025Dtr';
      $input['87'] = 'eplrs';
      $input['88'] = 'arap';
      $input['89'] = 'propCnls';
      $input['90'] = 'hostPad';
      $input['91'] = 'termPad';
      $input['92'] = 'frameRelayMPI';
      $input['93'] = 'x213';
      $input['94'] = 'adsl';
      $input['95'] = 'radsl';
      $input['96'] = 'sdsl';
      $input['97'] = 'vdsl';
      $input['98'] = 'iso88025CRFPInt';
      $input['99'] = 'myrinet';
      $input['100'] = 'voiceEM';
      $input['101'] = 'voiceFXO';
      $input['102'] = 'voiceFXS';
      $input['103'] = 'voiceEncap';
      $input['104'] = 'voiceOverIp';
      $input['105'] = 'atmDxi';
      $input['106'] = 'atmFuni';
      $input['107'] = 'atmIma';
      $input['108'] = 'pppMultilinkBundle';
      $input['109'] = 'ipOverCdlc';
      $input['110'] = 'ipOverClaw';
      $input['111'] = 'stackToStack';
      $input['112'] = 'virtualIpAddress';
      $input['113'] = 'mpc';
      $input['114'] = 'ipOverAtm';
      $input['115'] = 'iso88025Fiber';
      $input['116'] = 'tdlc';
      $input['117'] = 'gigabitEthernet';
      $input['118'] = 'hdlc';
      $input['119'] = 'lapf';
      $input['120'] = 'v37';
      $input['121'] = 'x25mlp';
      $input['122'] = 'x25huntGroup';
      $input['123'] = 'trasnpHdlc';
      $input['124'] = 'interleave';
      $input['125'] = 'fast';
      $input['126'] = 'ip';
      $input['127'] = 'docsCableMaclayer';
      $input['128'] = 'docsCableDownstream';
      $input['129'] = 'docsCableUpstream';
      $input['130'] = 'a12MppSwitch';
      $input['131'] = 'tunnel';
      $input['132'] = 'coffee';
      $input['133'] = 'ces';
      $input['134'] = 'atmSubInterface';
      $input['135'] = 'l2vlan';
      $input['136'] = 'l3ipvlan';
      $input['137'] = 'l3ipxvlan';
      $input['138'] = 'digitalPowerline';
      $input['139'] = 'mediaMailOverIp';
      $input['140'] = 'dtm';
      $input['141'] = 'dcn';
      $input['142'] = 'ipForward';
      $input['143'] = 'msdsl';
      $input['144'] = 'ieee1394';
      $input['145'] = 'if-gsn';
      $input['146'] = 'dvbRccMacLayer';
      $input['147'] = 'dvbRccDownstream';
      $input['148'] = 'dvbRccUpstream';
      $input['149'] = 'atmVirtual';
      $input['150'] = 'mplsTunnel';
      $input['151'] = 'srp';
      $input['152'] = 'voiceOverAtm';
      $input['153'] = 'voiceOverFrameRelay';
      $input['154'] = 'idsl';
      $input['155'] = 'compositeLink';
      $input['156'] = 'ss7SigLink';
      $input['157'] = 'propWirelessP2P';
      $input['158'] = 'frForward';
      $input['159'] = 'rfc1483';
      $input['160'] = 'usb';
      $input['161'] = 'ieee8023adLag';
      $input['162'] = 'bgppolicyaccounting';
      $input['163'] = 'frf16MfrBundle';
      $input['164'] = 'h323Gatekeeper';
      $input['165'] = 'h323Proxy';
      $input['166'] = 'mpls';
      $input['167'] = 'mfSigLink';
      $input['168'] = 'hdsl2';
      $input['169'] = 'shdsl';
      $input['170'] = 'ds1FDL';
      $input['171'] = 'pos';
      $input['172'] = 'dvbAsiIn';
      $input['173'] = 'dvbAsiOut';
      $input['174'] = 'plc';
      $input['175'] = 'nfas';
      $input['176'] = 'tr008';
      $input['177'] = 'gr303RDT';
      $input['178'] = 'gr303IDT';
      $input['179'] = 'isup';
      $input['180'] = 'propDocsWirelessMaclayer';
      $input['181'] = 'propDocsWirelessDownstream';
      $input['182'] = 'propDocsWirelessUpstream';
      $input['183'] = 'hiperlan2';
      $input['184'] = 'propBWAp2Mp';
      $input['185'] = 'sonetOverheadChannel';
      $input['186'] = 'digitalWrapperOverheadChannel';
      $input['187'] = 'aal2';
      $input['188'] = 'radioMAC';
      $input['189'] = 'atmRadio';
      $input['190'] = 'imt';
      $input['191'] = 'mvl';
      $input['192'] = 'reachDSL';
      $input['193'] = 'frDlciEndPt';
      $input['194'] = 'atmVciEndPt';
      $input['195'] = 'opticalChannel';
      $input['196'] = 'opticalTransport';
      $input['197'] = 'propAtm';
      $input['198'] = 'voiceOverCable';
      $input['199'] = 'infiniband';
      $input['200'] = 'teLink';
      $input['201'] = 'q2931';
      $input['202'] = 'virtualTg';
      $input['203'] = 'sipTg';
      $input['204'] = 'sipSig';
      $input['205'] = 'docsCableUpstreamChannel';
      $input['206'] = 'econet';
      $input['207'] = 'pon155';
      $input['208'] = 'pon622';
      $input['209'] = 'bridge';
      $input['210'] = 'linegroup';
      $input['211'] = 'voiceEMFGD';
      $input['212'] = 'voiceFGDEANA';
      $input['213'] = 'voiceDID';
      $input['214'] = 'mpegTransport';
      $input['215'] = 'sixToFour';
      $input['216'] = 'gtp';
      $input['217'] = 'pdnEtherLoop1';
      $input['218'] = 'pdnEtherLoop2';
      $input['219'] = 'opticalChannelGroup';
      $input['220'] = 'homepna';
      $input['221'] = 'gfp';
      $input['222'] = 'ciscoISLvlan';
      $input['223'] = 'actelisMetaLOOP';
      $input['224'] = 'fcipLink';
      $input['225'] = 'rpr';
      $input['226'] = 'qam';
      $input['227'] = 'lmp';
      $input['228'] = 'cblVectaStar';
      $input['229'] = 'docsCableMCmtsDownstream';
      $input['230'] = 'adsl2';
      $input['231'] = 'macSecControlledIF';
      $input['232'] = 'macSecUncontrolledIF';
      $input['233'] = 'aviciOpticalEther';
      $input['234'] = 'atmbond';

      $install = 1;
      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_networkporttypes',
         'WHERE'  => ['import' => 1]
      ]);
      if (count($iterator) > 0) {
         $install = 0;
      }

      $it = new DBmysqlIterator($DB);
      $it->buildQuery([
         'FROM'   => 'glpi_plugin_fusioninventory_networkporttypes',
         'WHERE'  => ['number' => new QueryParam()]
      ]);
      $stmt_select = $DB->prepare($it->getSQL());

      $to_import = [];
      foreach ($input as $number=>$name) {
         $stmt_select->bind_param('s', $number);
         $stmt_select->execute();
         if ($DB->numrows($stmt_select) == '0') {
            $to_import[$number] = $name;
         }
      }
      mysqli_stmt_close($stmt_select);

      if (count($to_import)) {
         $qparam = new \QueryParam();
         $insert_qry = $DB->buildInsert(
            'glpi_plugin_fusioninventory_networkporttypes', [
               'name'      => $qparam,
               'number'    => $qparam,
               'othername' => $qparam,
               'import'    => $qparam
            ]
         );
         $stmt_insert = $DB->prepare($insert_qry);

         foreach ($to_import as $number=>$name) {
            $import = 0;
            $othername = "$name ($number)";

            if ($install == '1') {
               switch ($number) {

                  case '6':
                  case '7':
                  case '71':
                  case '117':
                  case '62':
                  case '169':
                  case '56':
                     $import = 1;
                     break;

               }
            }

            $stmt_insert->bind_param(
               'ssss',
               $name,
               $number,
               $othername,
               $import
            );
            $stmt_insert->execute();
         }
         mysqli_stmt_close($stmt_insert);
      }
   }


   /**
    * Check is the type yet in database
    *
    * @param string $type
    * @return boolean
    */
   function isImportType($type) {
      if (!strstr($type, 'gsn')) {
         $type = str_replace("-", "", $type);
      }

      $a_ports = $this->find(
            ['OR' =>
               ['number'    => $type,
                'name'      => $type,
                'othername' => $type],
             'import' => 1]);
      if (count($a_ports) > 0) {
         return true;
      }
      return false;
   }


   /**
    * Display the types of network and what ports (with the types) we import in
    * networkequipments
    */
   function showNetworkporttype() {

      $a_notimports = $this->find(['import' => 0]);
      $a_imports = $this->find(['import' => 1]);

      echo "<form name='form' method='post' action='".$this->getFormURL()."'>";

      echo "<table class='tab_cadre_fixe'>";
      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='3'>".
              __('Ports types to import (for network equipments)', 'fusioninventory')."</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'>";

      if (Session::haveRight('plugin_fusioninventory_configuration', UPDATE)) {

         echo "<td class='right'>";

         if (count($a_notimports) > 0) {
            echo "<select name='type_to_add[]' multiple size='5'>";

            foreach ($a_notimports as $key => $data) {
               echo "<option value='$key'>".$data['othername']."</option>";
            }

            echo "</select>";
         }

         echo "</td><td class='center'>";

         if (count($a_notimports)) {
            echo "<input type='submit' class='submit' name='type_add' value='".
                  __('Add')." >>'>";
         }
         echo "<br><br>";

         if (count($a_imports)) {
            echo "<input type='submit' class='submit' name='type_delete' value='<< ".
                  __('Delete', 'fusioninventory')."'>";
         }
         echo "</td><td>";
      } else {
         echo "<td colspan='2'></td>";
         echo "<td class='center'>";
      }
      if (count($a_imports)) {
         echo "<select name='type_to_delete[]' multiple size='5'>";
         foreach ($a_imports as $key => $data) {
            echo "<option value='$key'>".$data['othername']."</option>";
         }
         echo "</select>";
      } else {
         echo "&nbsp;";
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      Html::closeForm();
   }
}
