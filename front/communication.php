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

ini_set("memory_limit", "-1");
ini_set("max_execution_time", "0");

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
}
session_start();
include (GLPI_ROOT."/inc/includes.php");

if (!isset($_SESSION['glpilanguage'])) {
   $_SESSION['glpilanguage'] = 'en_GB';
}

$_SESSION["glpi_use_mode"] = 2;

// Load all plugin files
   call_user_func("plugin_init_fusinvinventory");
   $a_modules = PluginFusioninventoryModule::getAll();
   foreach ($a_modules as $id => $datas) {
      call_user_func("plugin_init_".$datas['directory']);
   }



$PluginFusioninventoryCommunication  = new PluginFusioninventoryCommunication;
$pta  = new PluginFusioninventoryAgent;

$res='';
$errors='';

// ***** For debug only ***** //
$GLOBALS["HTTP_RAW_POST_DATA"] = gzcompress('<?xml version="1.0" encoding="UTF-8"?>
<REQUEST>
  <CONTENT>
    <ACCESSLOG>
      <LOGDATE>2010-08-29 00:16:34</LOGDATE>
      <USERID>N/A</USERID>
    </ACCESSLOG>
    <BATTERIES>
      <CHEMISTRY>Lithium </CHEMISTRY>
      <DATE>10/12/2020</DATE>
      <MANUFACTURER>-Virtual Battery 0-</MANUFACTURER>
      <NAME>Li-lon Battery</NAME>
      <SERIAL>Battery 0</SERIAL>
    </BATTERIES>
    <BIOS>
      <BDATE>11/30/2009</BDATE>
      <BMANUFACTURER>eMachines</BMANUFACTURER>
      <BVERSION>V3.03</BVERSION>
      <SMANUFACTURER>eMachines</SMANUFACTURER>
      <SMODEL>eMachines G525</SMODEL>
      <SSN>LXN8302045951081611601</SSN>
    </BIOS>
    <CPUS>
      <MANUFACTURER>Intel(R) Corporation</MANUFACTURER>
      <NAME>Intel(R) Celeron(R) CPU          900  @ 2.20GHz</NAME>
      <SERIAL>7A060100FFFBEBAF</SERIAL>
      <SPEED>2200</SPEED>
      <THREAD>1</THREAD>
    </CPUS>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>1022</FREE>
      <TOTAL>1447</TOTAL>
      <TYPE>/</TYPE>
      <VOLUMN>/dev/ad4s1a</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>24226</FREE>
      <TOTAL>138968</TOTAL>
      <TYPE>/Donnees</TYPE>
      <VOLUMN>/dev/ad4s1g</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>154</FREE>
      <TOTAL>495</TOTAL>
      <TYPE>/tmp</TYPE>
      <VOLUMN>/dev/ad4s1e</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>75</FREE>
      <TOTAL>19832</TOTAL>
      <TYPE>/usr</TYPE>
      <VOLUMN>/dev/ad4s1f</VOLUMN>
    </DRIVES>
    <DRIVES>
      <FILESYSTEM>ufs</FILESYSTEM>
      <FREE>3143</FREE>
      <TOTAL>3880</TOTAL>
      <TYPE>/var</TYPE>
      <VOLUMN>/dev/ad4s1d</VOLUMN>
    </DRIVES>
    <ENVS>
      <KEY>HOME</KEY>
      <VAL>/root</VAL>
    </ENVS>
    <ENVS>
      <KEY>HOST</KEY>
      <VAL>port003</VAL>
    </ENVS>
    <ENVS>
      <KEY>XTERM_VERSION</KEY>
      <VAL>X.Org 6.8.99.903(261)</VAL>
    </ENVS>
    <ENVS>
      <KEY>XAUTHORITY</KEY>
      <VAL>/home/ddurieux/.Xauthority</VAL>
    </ENVS>
    <ENVS>
      <KEY>WINDOWPATH</KEY>
      <VAL>9</VAL>
    </ENVS>
    <ENVS>
      <KEY>DISPLAY</KEY>
      <VAL>:0.0</VAL>
    </ENVS>
    <ENVS>
      <KEY>LC_ALL</KEY>
      <VAL>C</VAL>
    </ENVS>
    <ENVS>
      <KEY>REMOTEHOST</KEY>
      <VAL></VAL>
    </ENVS>
    <ENVS>
      <KEY>BLOCKSIZE</KEY>
      <VAL>K</VAL>
    </ENVS>
    <ENVS>
      <KEY>OSTYPE</KEY>
      <VAL>FreeBSD</VAL>
    </ENVS>
    <ENVS>
      <KEY>EDITOR</KEY>
      <VAL>vi</VAL>
    </ENVS>
    <ENVS>
      <KEY>MAIL</KEY>
      <VAL>/var/mail/ddurieux</VAL>
    </ENVS>
    <ENVS>
      <KEY>PWD</KEY>
      <VAL>/Donnees/developpement/sources/fusioninventory/agent</VAL>
    </ENVS>
    <ENVS>
      <KEY>VENDOR</KEY>
      <VAL>unknown</VAL>
    </ENVS>
    <ENVS>
      <KEY>LANG</KEY>
      <VAL>C</VAL>
    </ENVS>
    <ENVS>
      <KEY>USER</KEY>
      <VAL>ddurieux</VAL>
    </ENVS>
    <ENVS>
      <KEY>GROUP</KEY>
      <VAL>wheel</VAL>
    </ENVS>
    <ENVS>
      <KEY>LOGNAME</KEY>
      <VAL>ddurieux</VAL>
    </ENVS>
    <ENVS>
      <KEY>SHLVL</KEY>
      <VAL>3</VAL>
    </ENVS>
    <ENVS>
      <KEY>PATH</KEY>
      <VAL>/sbin:/bin:/usr/sbin:/usr/bin:/usr/games:/usr/local/sbin:/usr/local/bin:/root/bin</VAL>
    </ENVS>
    <ENVS>
      <KEY>FTP_PASSIVE_MODE</KEY>
      <VAL>YES</VAL>
    </ENVS>
    <ENVS>
      <KEY>WINDOWID</KEY>
      <VAL>27262989</VAL>
    </ENVS>
    <ENVS>
      <KEY>SHELL</KEY>
      <VAL>/bin/csh</VAL>
    </ENVS>
    <ENVS>
      <KEY>MACHTYPE</KEY>
      <VAL>unknown</VAL>
    </ENVS>
    <ENVS>
      <KEY>HOSTTYPE</KEY>
      <VAL>FreeBSD</VAL>
    </ENVS>
    <ENVS>
      <KEY>XTERM_LOCALE</KEY>
      <VAL>C</VAL>
    </ENVS>
    <ENVS>
      <KEY>XTERM_SHELL</KEY>
      <VAL>/bin/csh</VAL>
    </ENVS>
    <ENVS>
      <KEY>TERM</KEY>
      <VAL>xterm</VAL>
    </ENVS>
    <ENVS>
      <KEY>TERMCAP</KEY>
      <VAL>xterm|xterm-color|X11 terminal emulator:ti@:te@:k1=\EOP:k2=\EOQ:k3=\EOR:k4=\EOS:k5=\E[15~:k6=\E[17~:k7=\E[18~:k8=\E[19~:k9=\E[20~:k;=\E[21~:F1=\E[23~:F2=\E[24~:@7=\EOF:@8=\EOM:kI=\E[2~:kh=\EOH:kP=\E[5~:kN=\E[6~:ku=\EOA:kd=\EOB:kr=\EOC:kl=\EOD:Km=\E[M:li#43:co#201:am:kn#12:km:mi:ms:xn:AX:bl=^G:is=\E[!p\E[?3;4l\E[4l\E&gt;:rs=\E[!p\E[?3;4l\E[4l\E&gt;:le=^H:AL=\E[%dL:DL=\E[%dM:DC=\E[%dP:al=\E[L:dc=\E[P:dl=\E[M:UP=\E[%dA:DO=\E[%dB:LE=\E[%dD:RI=\E[%dC:ho=\E[H:cd=\E[J:ce=\E[K:cl=\E[H\E[2J:cm=\E[%i%d;%dH:cs=\E[%i%d;%dr:im=\E[4h:ei=\E[4l:ks=\E[?1h\E=:ke=\E[?1l\E&gt;:kD=\E[3~:sf=\n:sr=\EM:st=\EH:ct=\E[3g:sc=\E7:rc=\E8:eA=\E(B\E)0:as=\E(0:ae=\E(B:ml=\El:mu=\Em:up=\E[A:nd=\E[C:md=\E[1m:me=\E[m:mr=\E[7m:so=\E[7m:se=\E[27m:us=\E[4m:ue=\E[24m:vi=\E[?25l:ve=\E[?25h:ut:Co#8:pa#64:op=\E[39;49m:AB=\E[4%dm:AF=\E[3%dm:kb=\010:</VAL>
    </ENVS>
    <ENVS>
      <KEY>PAGER</KEY>
      <VAL>more</VAL>
    </ENVS>
    <HARDWARE>
      <ARCHNAME>amd64-freebsd-thread-multi</ARCHNAME>
      <CHECKSUM>201217</CHECKSUM>
      <DESCRIPTION>amd64/00-00-01 05:03:47</DESCRIPTION>
      <DNS>212.27.40.240/212.27.40.241</DNS>
      <ETIME>3</ETIME>
      <IPADDR>192.168.1.11/192.168.200.6</IPADDR>
      <MEMORY>2987</MEMORY>
      <NAME>port003</NAME>
      <OSCOMMENTS>GENERIC (Tue May 25 20:54:11 UTC 2010)root@amd64-builder.daemonology.net</OSCOMMENTS>
      <OSNAME>freebsd</OSNAME>
      <OSVERSION>8.0-RELEASE-p3</OSVERSION>
      <PROCESSORN>1</PROCESSORN>
      <PROCESSORS>2200</PROCESSORS>
      <PROCESSORT>Intel(R) Celeron(R) CPU          900  @ 2.20GHz</PROCESSORT>
      <SWAP>4096</SWAP>
      <USERDOMAIN></USERDOMAIN>
      <USERID>ddurieux</USERID>
      <UUID>65356431-3038-3139-3036-0026228CD1A2</UUID>
      <VMSYSTEM>Physical</VMSYSTEM>
      <WORKGROUP></WORKGROUP>
    </HARDWARE>
    <MEMORIES>
      <CAPACITY>2048</CAPACITY>
      <CAPTION>DIMM0</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>1</NUMSLOTS>
      <SERIALNUMBER>00000000</SERIALNUMBER>
      <SPEED>800</SPEED>
      <TYPE>Unknown</TYPE>
    </MEMORIES>
    <MEMORIES>
      <CAPACITY>1024</CAPACITY>
      <CAPTION>DIMM2</CAPTION>
      <DESCRIPTION>SODIMM</DESCRIPTION>
      <NUMSLOTS>2</NUMSLOTS>
      <SERIALNUMBER>00000000</SERIALNUMBER>
      <SPEED>800</SPEED>
      <TYPE>Unknown</TYPE>
    </MEMORIES>
    <NETWORKS>
      <DESCRIPTION>alc0</DESCRIPTION>
      <IPADDRESS>192.168.1.11</IPADDRESS>
      <IPDHCP>192.168.1.254</IPDHCP>
      <IPGATEWAY>192.168.1.254</IPGATEWAY>
      <IPMASK>0xffffff00</IPMASK>
      <IPSUBNET>192.168.1.0</IPSUBNET>
      <MACADDR>00:26:22:8c:d1:a2</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE>Ethernet</TYPE>
      <VIRTUALDEV>no</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>lo0</DESCRIPTION>
      <IPADDRESS>127.0.0.1</IPADDRESS>
      <IPGATEWAY>192.168.1.254</IPGATEWAY>
      <IPMASK>0xff000000</IPMASK>
      <IPSUBNET>127.0.0.0</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>16384</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>yes</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>vboxnet0</DESCRIPTION>
      <IPADDRESS></IPADDRESS>
      <IPMASK></IPMASK>
      <MACADDR>0a:00:27:00:00:00</MACADDR>
      <MTU>1500</MTU>
      <STATUS>Down</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>yes</VIRTUALDEV>
    </NETWORKS>
    <NETWORKS>
      <DESCRIPTION>tun0</DESCRIPTION>
      <IPADDRESS>192.168.200.6</IPADDRESS>
      <IPGATEWAY>192.168.1.254</IPGATEWAY>
      <IPMASK>0xffffffff</IPMASK>
      <IPSUBNET>192.168.200.6</IPSUBNET>
      <MACADDR></MACADDR>
      <MTU>1500</MTU>
      <STATUS>Up</STATUS>
      <TYPE></TYPE>
      <VIRTUALDEV>yes</VIRTUALDEV>
    </NETWORKS>
    <PROCESSES>
      <CMD>/usr/local/lib/virtualbox/VirtualBox --comment debian10023 --startvm ec43b1fc-0efc-487f-8188-a104473db0d5 --no-startvm-errormsgbox</CMD>
      <CPUUSAGE>73.2</CPUUSAGE>
      <MEM>3.0</MEM>
      <PID>36612</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>218024</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>perl -Ilib -MFusionInventory::Agent::Task::Inventory -e FusionInventory::Agent::Task::Inventory::main(); -- /var/lib/fusioninventory-ag</CMD>
      <CPUUSAGE>14.4</CPUUSAGE>
      <MEM>0.6</MEM>
      <PID>37155</PID>
      <TTY>9</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>39744</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/lib/seamonkey/seamonkey-bin</CMD>
      <CPUUSAGE>2.7</CPUUSAGE>
      <MEM>7.9</MEM>
      <PID>33833</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>439652</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>perl fusioninventory-agent --devlib --debug --server=http://127.0.0.1/glpi078/plugins/fusioninventory/front/communication.php --scan-ho</CMD>
      <CPUUSAGE>2.6</CPUUSAGE>
      <MEM>0.7</MEM>
      <PID>37153</PID>
      <TTY>9</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>48396</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/bin/X :0 -auth /home/ddurieux/.serverauth.1222 (Xorg)</CMD>
      <CPUUSAGE>1.6</CPUUSAGE>
      <MEM>10.9</MEM>
      <PID>1241</PID>
      <TTY>v0</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>450588</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.9</CPUUSAGE>
      <MEM>1.0</MEM>
      <PID>1838</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>203796</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[kernel]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>0</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/sbin/init --</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>2176</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[g_event]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>2</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[g_up]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>3</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[g_down]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>4</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[xpt_thrd]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>5</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[sctp_iterator]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>6</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[pagedaemon]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>7</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[vmdaemon]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>8</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[pagezero]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>9</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[audit]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>10</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[idle]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>11</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[intr]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>12</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[yarrow]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>13</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[acpi_thermal]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>14</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[usb]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>15</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[bufdaemon]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>16</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[vnlru]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>17</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[syncer]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>18</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[softdepflush]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>19</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[flowcleaner]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>20</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[TIMER]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>126</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[ng_queue]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>127</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>0</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/moused -p /dev/ums0 -t auto -I /var/run/moused.ums0.pid</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>470</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7012</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/sbin/devd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>550</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>2180</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/syslogd -s</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>677</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5992</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/rpcbind</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>695</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>6924</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/mountd -r</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>790</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>nfsd: master (nfsd)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>792</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>4772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>nfsd: server (nfsd)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>794</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>4772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/webcamd -B</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>863</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>11288</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/powerd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>932</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5864</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/moused -p /dev/psm0 -t auto</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1076</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7012</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/cupsd -C /usr/local/etc/cups/cupsd.conf</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1115</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>12664</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.8</MEM>
      <PID>1127</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>200724</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/sshd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1146</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>25108</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/cron -s</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1155</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>6920</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/sbin/inetd -wW -C 60</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1181</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7976</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.0</MEM>
      <PID>1215</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>204820</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.0</MEM>
      <PID>1216</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>203796</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xbindkeys</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1247</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>12308</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmcpuload</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1250</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>18748</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmnetload</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1251</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>19856</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmbsdbatt</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1252</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>17660</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmclockmon</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1253</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>17656</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmfmixer</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1254</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>17660</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmmemmon</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1255</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>18748</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>1258</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>dhclient: alc0 [priv] (dhclient)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1288</PID>
      <TTY>??</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>4776</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>dhclient: alc0 (dhclient)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1316</PID>
      <TTY>??</TTY>
      <USER>_dhcp</USER>
      <VIRTUALMEMORY>4776</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1319</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>claws-mail</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.4</MEM>
      <PID>1337</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>194380</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>dbus-launch --autolaunch 11bb16c7c113d369f6e0cfe200001462 --binary-syntax --close-stderr</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1351</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>15616</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/bin/dbus-daemon --fork --print-pid 5 --print-address 7 --session</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1352</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7060</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.9</MEM>
      <PID>1839</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>202772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.9</MEM>
      <PID>1840</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>202772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.1</MEM>
      <PID>1841</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>208916</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/libexec/gam_server</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1845</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34288</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>1870</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>1903</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>35492</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>5345</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>11331</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>11982</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>32852</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>33444</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh /usr/local/bin/seamonkey</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>33825</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh /usr/local/lib/seamonkey/run-mozilla.sh /usr/local/lib/seamonkey/seamonkey-bin</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>33829</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/libexec/gconfd-2</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>33836</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>42936</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.0</MEM>
      <PID>34157</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>204820</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/lib/nspluginwrapper/i386/linux/npviewer.bin --plugin /usr/local/lib/linux-mozilla/plugins/libflashplayer.so --connection /or</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.7</MEM>
      <PID>34280</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>74108</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.0</MEM>
      <PID>34636</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>204820</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.9</MEM>
      <PID>34637</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>202772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[sh]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>34638</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>[java]</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>10.1</MEM>
      <PID>34863</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>535848</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>36509</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>35492</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>36574</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>33444</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xterm -sb</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>36589</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>34468</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/lib/virtualbox/VBoxSVC --pipe 8 --auto-shutdown</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.3</MEM>
      <PID>36609</PID>
      <TTY>??</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>33640</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/sbin/httpd</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.9</MEM>
      <PID>37135</PID>
      <TTY>??</TTY>
      <USER>www</USER>
      <VIRTUALMEMORY>202772</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh /usr/local/bin/mysqld_safe --defaults-extra-file=/var/db/mysql/my.cnf --user=mysql --datadir=/var/db/mysql --pid-file=/var/db/m</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1007</PID>
      <TTY>v0-</TTY>
      <USER>mysql</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/libexec/mysqld --defaults-extra-file=/var/db/mysql/my.cnf --basedir=/usr/local --datadir=/var/db/mysql --log-error=/var/db/m</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.2</MEM>
      <PID>1056</PID>
      <TTY>v0-</TTY>
      <USER>mysql</USER>
      <VIRTUALMEMORY>68836</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>login [pam] (login)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1206</PID>
      <TTY>v0</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20644</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>-csh (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1219</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh /usr/local/bin/startx</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1222</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>xinit /home/ddurieux/.xinitrc -- /usr/local/bin/X :0 -auth /home/ddurieux/.serverauth.1222</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1240</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>12308</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmaker</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1244</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>36252</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmaker --for-real=</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>1248</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>41616</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>wmsetbg -helper -d</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1269</PID>
      <TTY>v0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>36256</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv1</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1207</PID>
      <TTY>v1</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv2</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1208</PID>
      <TTY>v2</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv3</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1209</PID>
      <TTY>v3</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv4</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1210</PID>
      <TTY>v4</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv5</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1211</PID>
      <TTY>v5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv6</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1212</PID>
      <TTY>v6</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/libexec/getty Pc ttyv7</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1213</PID>
      <TTY>v7</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>5860</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1260</PID>
      <TTY>0</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1282</PID>
      <TTY>0</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1283</PID>
      <TTY>0</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>openvpn openvpn-bureau.conf</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1317</PID>
      <TTY>0</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10044</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1321</PID>
      <TTY>1</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>ssh 192.168.0.200 -l weechat</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1336</PID>
      <TTY>1</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>20564</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>pidgin</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>1.2</MEM>
      <PID>31820</PID>
      <TTY>2-</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>280284</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36511</PID>
      <TTY>9</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36568</PID>
      <TTY>9</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36569</PID>
      <TTY>9</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>ps aux</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>37248</PID>
      <TTY>9</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>6976</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36576</PID>
      <TTY>11</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36580</PID>
      <TTY>11</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36581</PID>
      <TTY>11</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1872</PID>
      <TTY>3</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>32854</PID>
      <TTY>10</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>ee</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>32856</PID>
      <TTY>10</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>6104</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1905</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1922</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1923</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1927</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1928</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>1934</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>1935</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>5301</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>5302</PID>
      <TTY>4</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>11333</PID>
      <TTY>5</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>12010</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>12011</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>27442</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>27443</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>28188</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>28189</PID>
      <TTY>5</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>5347</PID>
      <TTY>6</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>27985</PID>
      <TTY>6</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>27987</PID>
      <TTY>6</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>31415</PID>
      <TTY>6</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>31416</PID>
      <TTY>6</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/usr/local/lib/virtualbox/VBoxXPCOMIPCD</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.2</MEM>
      <PID>36606</PID>
      <TTY>7-</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>26148</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>11984</PID>
      <TTY>8</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>31040</PID>
      <TTY>8</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>31041</PID>
      <TTY>8</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/sh ./startcmd.sh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.0</MEM>
      <PID>31144</PID>
      <TTY>8</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>7232</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>/bin/csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>31145</PID>
      <TTY>8</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10404</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>csh</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36591</PID>
      <TTY>12</TTY>
      <USER>ddurieux</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>su</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36595</PID>
      <TTY>12</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>20764</VIRTUALMEMORY>
    </PROCESSES>
    <PROCESSES>
      <CMD>_su (csh)</CMD>
      <CPUUSAGE>0.0</CPUUSAGE>
      <MEM>0.1</MEM>
      <PID>36596</PID>
      <TTY>12</TTY>
      <USER>root</USER>
      <VIRTUALMEMORY>10276</VIRTUALMEMORY>
    </PROCESSES>
    <SLOTS>
      <DESCRIPTION>x16 PCI Express</DESCRIPTION>
      <DESIGNATION>0</DESIGNATION>
      <NAME>J6B2</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>0</DESIGNATION>
      <NAME>J6B1</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>1</DESIGNATION>
      <NAME>J6C2</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>2</DESIGNATION>
      <NAME>J7B1</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>3</DESIGNATION>
      <NAME>J8B3</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SLOTS>
      <DESCRIPTION>x1 PCI Express</DESCRIPTION>
      <DESIGNATION>4</DESIGNATION>
      <NAME>J8D1</NAME>
      <STATUS>Available</STATUS>
    </SLOTS>
    <SOFTWARES>
      <COMMENTS>Find the country that any IP address or hostname originates</COMMENTS>
      <NAME>GeoIP</NAME>
      <VERSION>1.4.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Image processing tools</COMMENTS>
      <NAME>ImageMagick</NAME>
      <VERSION>6.6.2.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High-performance CORBA ORB with support for the C language</COMMENTS>
      <NAME>ORBit</NAME>
      <VERSION>0.5.17_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High-performance CORBA ORB with support for the C language</COMMENTS>
      <NAME>ORBit2</NAME>
      <VERSION>2.14.18_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A high dynamic-range (HDR) image file format</COMMENTS>
      <NAME>OpenEXR</NAME>
      <VERSION>1.6.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Formats an ascii file for printing on a postscript printer</COMMENTS>
      <NAME>a2ps-a4</NAME>
      <VERSION>4.13b_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An ascii art library</COMMENTS>
      <NAME>aalib</NAME>
      <VERSION>1.4.r5_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Adobe Reader for view, print, and search PDF documents (ENU</COMMENTS>
      <NAME>acroread8</NAME>
      <VERSION>8.1.7_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Wrapper script for Adobe Reader</COMMENTS>
      <NAME>acroreadwrapper</NAME>
      <VERSION>0.0.20100806</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Easy to use, asynchronous-capable DNS client library and ut</COMMENTS>
      <NAME>adns</NAME>
      <VERSION>1.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ICCCM-compliant window manager based on 9wm</COMMENTS>
      <NAME>aewm</NAME>
      <VERSION>1.2.7_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>AMSFonts PostScript Fonts (Adobe Type 1 format)</COMMENTS>
      <NAME>amspsfnt</NAME>
      <VERSION>1.0_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Embeds a Perl interpreter in the Apache2 server</COMMENTS>
      <NAME>ap22-mod_perl2</NAME>
      <VERSION>2.0.4_2,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Version 2.2.x of Apache web server with prefork MPM.</COMMENTS>
      <NAME>apache</NAME>
      <VERSION>2.2.15_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Java- and XML-based build tool, conceptually similar to mak</COMMENTS>
      <NAME>apache-ant</NAME>
      <VERSION>1.7.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Program to list application\'s resources</COMMENTS>
      <NAME>appres</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Apache Portability Library</COMMENTS>
      <NAME>apr-ipv6-devrandom-gdbm-db42</NAME>
      <VERSION>1.4.2.1.3.9_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A UML design tool with cognitive support</COMMENTS>
      <NAME>argouml</NAME>
      <VERSION>0.30.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Audio system for the KDE integrated X11 desktop</COMMENTS>
      <NAME>arts</NAME>
      <VERSION>1.5.10_5,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Spelling checker with better suggestion logic than ispell</COMMENTS>
      <NAME>aspell</NAME>
      <VERSION>0.60.6_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A GNOME accessibility toolkit (ATK)</COMMENTS>
      <NAME>atk</NAME>
      <VERSION>1.30.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Audacity is a GUI editor for digital audio waveforms</COMMENTS>
      <NAME>audacity</NAME>
      <VERSION>1.2.4b_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Automatically configure source code on many Un*x platforms </COMMENTS>
      <NAME>autoconf</NAME>
      <VERSION>2.13.000227_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Automatically configure source code on many Un*x platforms </COMMENTS>
      <NAME>autoconf</NAME>
      <VERSION>2.62</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Wrapper script for GNU autoconf</COMMENTS>
      <NAME>autoconf-wrapper</NAME>
      <VERSION>20071109</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Standards-compliant Makefile generator (1.10)</COMMENTS>
      <NAME>automake</NAME>
      <VERSION>1.10.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Standards-compliant Makefile generator (1.4)</COMMENTS>
      <NAME>automake</NAME>
      <VERSION>1.4.6_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Standards-compliant Makefile generator (1.5)</COMMENTS>
      <NAME>automake</NAME>
      <VERSION>1.5_5,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Standards-compliant Makefile generator (1.6)</COMMENTS>
      <NAME>automake</NAME>
      <VERSION>1.6.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Standards-compliant Makefile generator (1.9)</COMMENTS>
      <NAME>automake</NAME>
      <VERSION>1.9.6_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Wrapper script for GNU automake</COMMENTS>
      <NAME>automake-wrapper</NAME>
      <VERSION>20071109</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Automatic moc for Qt 4 packages</COMMENTS>
      <NAME>automoc4</NAME>
      <VERSION>0.9.88_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Service discovery on a local network</COMMENTS>
      <NAME>avahi-app</NAME>
      <VERSION>0.6.25_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Dynamic pixel format conversion library</COMMENTS>
      <NAME>babl</NAME>
      <VERSION>0.1.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The GNU Project\'s Bourne Again SHell</COMMENTS>
      <NAME>bash</NAME>
      <VERSION>4.1.7_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Convert X font from BDF to PCF</COMMENTS>
      <NAME>bdftopcf</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>BigReqs extension headers</COMMENTS>
      <NAME>bigreqsproto</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU binary tools</COMMENTS>
      <NAME>binutils</NAME>
      <VERSION>2.20.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A parser generator from FSF, (mostly) compatible with Yacc</COMMENTS>
      <NAME>bison</NAME>
      <VERSION>2.4.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Bitmap editor and converter utilities for X</COMMENTS>
      <NAME>bitmap</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Bitstream Vera TrueType font collection</COMMENTS>
      <NAME>bitstream-vera</NAME>
      <VERSION>1.10_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>HTML editor designed for the experienced web designer</COMMENTS>
      <NAME>bluefish</NAME>
      <VERSION>2.0.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Garbage collection and memory leak detection for C and C++</COMMENTS>
      <NAME>boehm-gc</NAME>
      <VERSION>7.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fast, teachable, learning spam detector</COMMENTS>
      <NAME>bogofilter</NAME>
      <VERSION>1.2.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Build tool from the boost.org</COMMENTS>
      <NAME>boost-jam</NAME>
      <VERSION>1.43.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Free portable C++ libraries (without Boost.Python)</COMMENTS>
      <NAME>boost-libs</NAME>
      <VERSION>1.43.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Unknown perl module</COMMENTS>
      <NAME>bsdpan-Apache-Ocsinventory</NAME>
      <VERSION>1.02</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The root certificate bundle from the Mozilla Project</COMMENTS>
      <NAME>ca_root_nss</NAME>
      <VERSION>3.12.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Vector graphics library with cross-device output support</COMMENTS>
      <NAME>cairo</NAME>
      <VERSION>1.8.10_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>C++ interface to cairo</COMMENTS>
      <NAME>cairomm</NAME>
      <VERSION>1.8.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Mark Crispin\'s C-client mail access routines</COMMENTS>
      <NAME>cclient</NAME>
      <VERSION>2007e,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A CDDA extraction tool (also known as ripper)</COMMENTS>
      <NAME>cdparanoia</NAME>
      <VERSION>3.9.8_8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>CD/CD-R[W] and ISO-9660 image creation and extraction tools</COMMENTS>
      <NAME>cdrtools</NAME>
      <VERSION>2.01_8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The CELT ultra-low delay audio codec</COMMENTS>
      <NAME>celt</NAME>
      <VERSION>0.7.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A lightweight and very featureful GTK+ based e-mail and new</COMMENTS>
      <NAME>claws-mail</NAME>
      <VERSION>3.7.6_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Claws-Mail Themes</COMMENTS>
      <NAME>claws-mail-themes</NAME>
      <VERSION>20100514</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A cross-platform Makefile generator</COMMENTS>
      <NAME>cmake</NAME>
      <VERSION>2.8.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Computer Modern PostScript Fonts (Adobe Type 1 format)</COMMENTS>
      <NAME>cmpsfont</NAME>
      <VERSION>1.0_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU project portable class framework for C++</COMMENTS>
      <NAME>commoncpp</NAME>
      <VERSION>1.7.3,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A convenience package to install the compat7x libraries</COMMENTS>
      <NAME>compat7x-amd64</NAME>
      <VERSION>7.2.702000.200906.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Composite extension headers</COMMENTS>
      <NAME>compositeproto</NAME>
      <VERSION>0.4.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Framework for defining and tracking users</COMMENTS>
      <NAME>consolekit</NAME>
      <VERSION>0.4.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Common UNIX Printing System: Server</COMMENTS>
      <NAME>cups-base</NAME>
      <VERSION>1.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Common UNIX Printing System: Library cups</COMMENTS>
      <NAME>cups-client</NAME>
      <VERSION>1.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Common UNIX Printing System: Library cupsimage</COMMENTS>
      <NAME>cups-image</NAME>
      <VERSION>1.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Postscript interpreter for CUPS printing to non-PS printers</COMMENTS>
      <NAME>cups-pstoraster</NAME>
      <VERSION>8.15.4_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Non-interactive tool to get files from FTP, GOPHER, HTTP(S)</COMMENTS>
      <NAME>curl</NAME>
      <VERSION>7.20.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Cuse4BSD character device loopback driver for userspace</COMMENTS>
      <NAME>cuse4bsd-kmod</NAME>
      <VERSION>0.1.12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create patchset information from CVS</COMMENTS>
      <NAME>cvsps</NAME>
      <VERSION>2.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RFC 2222 SASL (Simple Authentication and Security Layer)</COMMENTS>
      <NAME>cyrus-sasl</NAME>
      <VERSION>2.1.23</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Damage extension headers</COMMENTS>
      <NAME>damageproto</NAME>
      <VERSION>1.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Berkeley DB package, revision 4.2</COMMENTS>
      <NAME>db42</NAME>
      <VERSION>4.2.52_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A message bus system for inter-application communication</COMMENTS>
      <NAME>dbus</NAME>
      <VERSION>1.2.24_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GLib bindings for the D-BUS messaging system</COMMENTS>
      <NAME>dbus-glib</NAME>
      <VERSION>0.86_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A couple of command line utilities for working with desktop</COMMENTS>
      <NAME>desktop-file-utils</NAME>
      <VERSION>0.15_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Robert de Bath\'s 8086 development tools</COMMENTS>
      <NAME>dev86</NAME>
      <VERSION>0.16.17</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Java Development Kit 1.6.0_07.02</COMMENTS>
      <NAME>diablo-jdk</NAME>
      <VERSION>1.6.0.07.02_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A fast, small graphical Web browser built upon GTK+</COMMENTS>
      <NAME>dillo</NAME>
      <VERSION>0.8.6_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Open source video codec from the BBC</COMMENTS>
      <NAME>dirac</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An extremely fast library for floating-point convolution</COMMENTS>
      <NAME>djbfft</NAME>
      <VERSION>0.76_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A tool for dumping DMI (SMBIOS) contents in human-readable </COMMENTS>
      <NAME>dmidecode</NAME>
      <VERSION>2.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DMX extension headers</COMMENTS>
      <NAME>dmxproto</NAME>
      <VERSION>2.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>V4.1 of the DocBook DTD, designed for technical documentati</COMMENTS>
      <NAME>docbook</NAME>
      <VERSION>4.1_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML version of the DocBook DTD version controlled for Scrol</COMMENTS>
      <NAME>docbook-sk</NAME>
      <VERSION>4.1.2_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML version of the DocBook DTD</COMMENTS>
      <NAME>docbook-xml</NAME>
      <VERSION>4.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DocBook/XML DTD V4.3, designed for technical documentation</COMMENTS>
      <NAME>docbook-xml</NAME>
      <VERSION>4.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DocBook/XML DTD V4.4, designed for technical documentation</COMMENTS>
      <NAME>docbook-xml</NAME>
      <VERSION>4.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XSL DocBook stylesheets</COMMENTS>
      <NAME>docbook-xsl</NAME>
      <VERSION>1.75.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A documentation system for C, C++ and other languages</COMMENTS>
      <NAME>doxygen</NAME>
      <VERSION>1.6.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenGL hardware acceleration drivers for the DRI</COMMENTS>
      <NAME>dri</NAME>
      <VERSION>7.4.4,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DRI2 prototype headers</COMMENTS>
      <NAME>dri2proto</NAME>
      <VERSION>2.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A database driven web content management system (CMS)</COMMENTS>
      <NAME>drupal6</NAME>
      <VERSION>6.19</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Convert a TeX DVI file to PostScript</COMMENTS>
      <NAME>dvipsk-tetex</NAME>
      <VERSION>5.95a_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An open extensible IDE for anything and nothing in particul</COMMENTS>
      <NAME>eclipse</NAME>
      <VERSION>3.4.2_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>D-Bus bindings for GObject</COMMENTS>
      <NAME>eggdbus</NAME>
      <VERSION>0.6_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Dictionary/spellchecking framework</COMMENTS>
      <NAME>enchant</NAME>
      <VERSION>1.4.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Encoding fonts</COMMENTS>
      <NAME>encodings</NAME>
      <VERSION>1.0.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ASCII to PostScript filter</COMMENTS>
      <NAME>enscript-a4</NAME>
      <VERSION>1.6.4_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A sound library for enlightenment package</COMMENTS>
      <NAME>esound</NAME>
      <VERSION>0.2.41</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XEVIE extension headers</COMMENTS>
      <NAME>evieext</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML 1.0 parser written in C</COMMENTS>
      <NAME>expat</NAME>
      <VERSION>2.0.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MPEG-2 and MPEG-4 AAC audio encoder</COMMENTS>
      <NAME>faac</NAME>
      <VERSION>1.28_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MPEG-2 and MPEG-4 AAC audio decoder</COMMENTS>
      <NAME>faad2</NAME>
      <VERSION>2.7_2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Visual mail, user and print face server</COMMENTS>
      <NAME>faces</NAME>
      <VERSION>1.7.7_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Collection of GStreamer modules and libraries for videoconf</COMMENTS>
      <NAME>farsight2</NAME>
      <VERSION>0.0.19_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Realtime audio/video encoder/converter and streaming server</COMMENTS>
      <NAME>ffmpeg</NAME>
      <VERSION>0.6_3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Reencode many media file formats to Ogg Theora</COMMENTS>
      <NAME>ffmpeg2theora</NAME>
      <VERSION>0.27</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fast C routines to compute the Discrete Fourier Transform</COMMENTS>
      <NAME>fftw3</NAME>
      <VERSION>3.2.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An archive manager for zip files, tar, etc</COMMENTS>
      <NAME>file-roller</NAME>
      <VERSION>2.30.1.1_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fast and reliable cross-platform FTP, FTPS and SFTP client</COMMENTS>
      <NAME>filezilla</NAME>
      <VERSION>3.3.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Web browser based on the browser portion of Mozilla</COMMENTS>
      <NAME>firefox</NAME>
      <VERSION>3.5.11,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fixes extension headers</COMMENTS>
      <NAME>fixesproto</NAME>
      <VERSION>4.1.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Free lossless audio codec</COMMENTS>
      <NAME>flac</NAME>
      <VERSION>1.2.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fast lexical analyzer generator</COMMENTS>
      <NAME>flex</NAME>
      <VERSION>2.5.35_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Adobe 100dpi font</COMMENTS>
      <NAME>font-adobe-100dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Adobe 75dpi font</COMMENTS>
      <NAME>font-adobe-75dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Adobe Utopia 100dpi font</COMMENTS>
      <NAME>font-adobe-utopia-100dpi</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Adobe Utopia 75dpi font</COMMENTS>
      <NAME>font-adobe-utopia-75dpi</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Adobe Utopia Type1 font</COMMENTS>
      <NAME>font-adobe-utopia-type1</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Font aliases</COMMENTS>
      <NAME>font-alias</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Arabic fonts</COMMENTS>
      <NAME>font-arabic-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow Holmes 100dpi font</COMMENTS>
      <NAME>font-bh-100dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow Holmes 75dpi font</COMMENTS>
      <NAME>font-bh-75dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow Holmes Lucida TypeWriter 100dpi font</COMMENTS>
      <NAME>font-bh-lucidatypewriter-100dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow Holmes Lucida TypeWriter 75dpi font</COMMENTS>
      <NAME>font-bh-lucidatypewriter-75dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow &amp; Holmes TTF font</COMMENTS>
      <NAME>font-bh-ttf</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bigelow Holmes Type1 font</COMMENTS>
      <NAME>font-bh-type1</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bitstream Vera 100dpi font</COMMENTS>
      <NAME>font-bitstream-100dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bitstream Vera 75dpi font</COMMENTS>
      <NAME>font-bitstream-75dpi</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Bitstream Vera Type1 font</COMMENTS>
      <NAME>font-bitstream-type1</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Cronyx Cyrillic font</COMMENTS>
      <NAME>font-cronyx-cyrillic</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Cursor fonts</COMMENTS>
      <NAME>font-cursor-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Daewoo fonts</COMMENTS>
      <NAME>font-daewoo-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Dec fonts</COMMENTS>
      <NAME>font-dec-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org IBM Type1 font</COMMENTS>
      <NAME>font-ibm-type1</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous ISAS fonts</COMMENTS>
      <NAME>font-isas-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous JIS fonts</COMMENTS>
      <NAME>font-jis-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Micro fonts</COMMENTS>
      <NAME>font-micro-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Cyrillic font</COMMENTS>
      <NAME>font-misc-cyrillic</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Ethiopic font</COMMENTS>
      <NAME>font-misc-ethiopic</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Meltho font</COMMENTS>
      <NAME>font-misc-meltho</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Misc fonts</COMMENTS>
      <NAME>font-misc-misc</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Mutt fonts</COMMENTS>
      <NAME>font-mutt-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Schumacher fonts</COMMENTS>
      <NAME>font-schumacher-misc</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Screen Cyrillic font</COMMENTS>
      <NAME>font-screen-cyrillic</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Sony fonts</COMMENTS>
      <NAME>font-sony-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous Sun fonts</COMMENTS>
      <NAME>font-sun-misc</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create an index of X font files in a directory</COMMENTS>
      <NAME>font-util</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Winitzki Cyrillic font</COMMENTS>
      <NAME>font-winitzki-cyrillic</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org XFree86 Type1 font</COMMENTS>
      <NAME>font-xfree86-type1</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fontcache extension headers</COMMENTS>
      <NAME>fontcacheproto</NAME>
      <VERSION>0.1.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An XML-based font configuration API for X Windows</COMMENTS>
      <NAME>fontconfig</NAME>
      <VERSION>2.8.0,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fonts extension headers</COMMENTS>
      <NAME>fontsproto</NAME>
      <VERSION>2.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Foomatic database</COMMENTS>
      <NAME>foomatic-db</NAME>
      <VERSION>20090530_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Foomatic database engine</COMMENTS>
      <NAME>foomatic-db-engine</NAME>
      <VERSION>4.0.1,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Foomatic wrapper scripts</COMMENTS>
      <NAME>foomatic-filters</NAME>
      <VERSION>4.0.1_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Print formatter driven by XSL formatting</COMMENTS>
      <NAME>fop</NAME>
      <VERSION>0.95</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Integrated wordprocessor/dbase/spreadsheet/drawing/chart/br</COMMENTS>
      <NAME>fr-openoffice.org</NAME>
      <VERSION>3.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A free and portable TrueType font rendering engine</COMMENTS>
      <NAME>freetype2</NAME>
      <VERSION>2.4.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A file and directory monitoring system</COMMENTS>
      <NAME>gamin</NAME>
      <VERSION>0.1.10_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Plan projects using a Gantt chart</COMMENTS>
      <NAME>ganttproject</NAME>
      <VERSION>2.1.m3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The GNU version of Awk</COMMENTS>
      <NAME>gawk</NAME>
      <VERSION>3.1.7_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Compiler Collection 4.6</COMMENTS>
      <NAME>gcc</NAME>
      <VERSION>4.6.0.20100710</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create dependencies in makefiles using \'gcc -M\'</COMMENTS>
      <NAME>gccmakedep</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A configuration database system for GNOME</COMMENTS>
      <NAME>gconf2</NAME>
      <VERSION>2.28.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A graphics library for fast creation of images</COMMENTS>
      <NAME>gd</NAME>
      <VERSION>2.0.35_7,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The GNU database manager</COMMENTS>
      <NAME>gdbm</NAME>
      <VERSION>1.8.3_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DV Tools for FreeBSD</COMMENTS>
      <NAME>gdvrecv</NAME>
      <VERSION>1.2_7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A graph based image processing framework</COMMENTS>
      <NAME>gegl</NAME>
      <VERSION>0.1.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A getopt(1) replacement that supports GNU-style long option</COMMENTS>
      <NAME>getopt</NAME>
      <VERSION>1.1.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU gettext package</COMMENTS>
      <NAME>gettext</NAME>
      <VERSION>0.18_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ghostscript 8.x PostScript interpreter</COMMENTS>
      <NAME>ghostscript8</NAME>
      <VERSION>8.71_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The &quot;meta-port&quot; for The Gimp</COMMENTS>
      <NAME>gimp</NAME>
      <VERSION>2.6.8_2,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A GNU Image Manipulation Program</COMMENTS>
      <NAME>gimp-app</NAME>
      <VERSION>2.6.8_4,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GutenPrint Printer Driver</COMMENTS>
      <NAME>gimp-gutenprint</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gimp plug-in for texture synthesis</COMMENTS>
      <NAME>gimp-resynthesizer</NAME>
      <VERSION>0.16_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>FAM backend for GLib\'s GIO library</COMMENTS>
      <NAME>gio-fam-backend</NAME>
      <VERSION>2.24.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Introspection information for libsoup</COMMENTS>
      <NAME>gir-repository-libsoup</NAME>
      <VERSION>0.6.5_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Distributed source code management tool</COMMENTS>
      <NAME>git</NAME>
      <VERSION>1.7.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Some useful routines of C programming (previous stable vers</COMMENTS>
      <NAME>glib</NAME>
      <VERSION>1.2.10_13</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Some useful routines of C programming (current stable versi</COMMENTS>
      <NAME>glib</NAME>
      <VERSION>2.24.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>C++ interfaces for glib2</COMMENTS>
      <NAME>glibmm</NAME>
      <VERSION>2.24.2_2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenGL image compositing library</COMMENTS>
      <NAME>glitz</NAME>
      <VERSION>0.5.6_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GLX extension headers</COMMENTS>
      <NAME>glproto</NAME>
      <VERSION>1.4.11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU version of \'make\' utility</COMMENTS>
      <NAME>gmake</NAME>
      <VERSION>3.81_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A free library for arbitrary precision arithmetic</COMMENTS>
      <NAME>gmp</NAME>
      <VERSION>5.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNOME doc utils</COMMENTS>
      <NAME>gnome-doc-utils</NAME>
      <VERSION>0.20.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A collection of icons for the GNOME 2 desktop</COMMENTS>
      <NAME>gnome-icon-theme</NAME>
      <VERSION>2.30.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A program that keeps passwords and other secrets</COMMENTS>
      <NAME>gnome-keyring</NAME>
      <VERSION>2.30.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libraries for GNOME, a GNU desktop environment</COMMENTS>
      <NAME>gnome-libs</NAME>
      <VERSION>1.4.2_16</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A MIME and Application database for GNOME</COMMENTS>
      <NAME>gnome-mime-data</NAME>
      <VERSION>2.18.0_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A front-end to mount, umount, and eject using HAL</COMMENTS>
      <NAME>gnome-mount</NAME>
      <VERSION>0.8_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNOME Virtual File System</COMMENTS>
      <NAME>gnome-vfs</NAME>
      <VERSION>2.24.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Common startup and shutdown subroutines used by GNOME scrip</COMMENTS>
      <NAME>gnome_subr</NAME>
      <VERSION>1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A utility port that creates the GNOME directory tree</COMMENTS>
      <NAME>gnomehier</NAME>
      <VERSION>2.3_12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The GNU Privacy Guard</COMMENTS>
      <NAME>gnupg</NAME>
      <VERSION>2.0.16_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Transport Layer Security library</COMMENTS>
      <NAME>gnutls</NAME>
      <VERSION>2.8.6_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Generate interface introspection data for GObject libraries</COMMENTS>
      <NAME>gobject-introspection</NAME>
      <VERSION>0.6.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gpac MPEG-4 Systems library and headers</COMMENTS>
      <NAME>gpac-libgpac</NAME>
      <VERSION>0.4.5_4,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Generates perfect hash functions for sets of keywords</COMMENTS>
      <NAME>gperf</NAME>
      <VERSION>3.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Graph Visualization Software from AT&amp;T and Bell Labs</COMMENTS>
      <NAME>graphviz</NAME>
      <VERSION>2.26.3_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GTK frontend for rsync</COMMENTS>
      <NAME>grsync</NAME>
      <VERSION>1.1.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Standard Fonts for Ghostscript</COMMENTS>
      <NAME>gsfonts</NAME>
      <VERSION>8.11_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The GNU Scientific Library - mathematical libs</COMMENTS>
      <NAME>gsl</NAME>
      <VERSION>1.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Generator Tools for Coding SOAP/XML Web Services in C and C</COMMENTS>
      <NAME>gsoap</NAME>
      <VERSION>2.7.15</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Development framework for creating media applications</COMMENTS>
      <NAME>gstreamer</NAME>
      <VERSION>0.10.29_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GStreamer plug-in for manipulating MPEG video streams</COMMENTS>
      <NAME>gstreamer-ffmpeg</NAME>
      <VERSION>0.10.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GStreamer written collection of plugins handling several me</COMMENTS>
      <NAME>gstreamer-plugins</NAME>
      <VERSION>0.10.29,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer ATSC A/52 stream aka AC-3 (dvd audio) plugin</COMMENTS>
      <NAME>gstreamer-plugins-a52dec</NAME>
      <VERSION>0.10.15,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Bad gstreamer-plugins</COMMENTS>
      <NAME>gstreamer-plugins-bad</NAME>
      <VERSION>0.10.19,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Core set of typical audio and video gstreamer-plugins</COMMENTS>
      <NAME>gstreamer-plugins-core</NAME>
      <VERSION>0.10_12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer dts plugin</COMMENTS>
      <NAME>gstreamer-plugins-dts</NAME>
      <VERSION>0.10.19,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer dvd plugin set</COMMENTS>
      <NAME>gstreamer-plugins-dvd</NAME>
      <VERSION>0.10.15,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Good gstreamer-plugins</COMMENTS>
      <NAME>gstreamer-plugins-good</NAME>
      <VERSION>0.10.23,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer png plugin</COMMENTS>
      <NAME>gstreamer-plugins-libpng</NAME>
      <VERSION>0.10.23,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer mp3 decoder plugin</COMMENTS>
      <NAME>gstreamer-plugins-mad</NAME>
      <VERSION>0.10.15,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer Plugins Mp3 decoder meta-port</COMMENTS>
      <NAME>gstreamer-plugins-mp3</NAME>
      <VERSION>0.10.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer Ogg bitstream plugin</COMMENTS>
      <NAME>gstreamer-plugins-ogg</NAME>
      <VERSION>0.10.29_1,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer pango textoverlay plugin</COMMENTS>
      <NAME>gstreamer-plugins-pango</NAME>
      <VERSION>0.10.29,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer theora plugin</COMMENTS>
      <NAME>gstreamer-plugins-theora</NAME>
      <VERSION>0.10.29,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ugly gstreamer-plugins</COMMENTS>
      <NAME>gstreamer-plugins-ugly</NAME>
      <VERSION>0.10.15,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer vorbis encoder/decoder plugin</COMMENTS>
      <NAME>gstreamer-plugins-vorbis</NAME>
      <VERSION>0.10.29,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gstreamer xvid plugin</COMMENTS>
      <NAME>gstreamer-plugins-xvid</NAME>
      <VERSION>0.10.19,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU version of the traditional tape archiver</COMMENTS>
      <NAME>gtar</NAME>
      <VERSION>1.23_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gimp Toolkit for X11 GUI (previous stable version)</COMMENTS>
      <NAME>gtk</NAME>
      <VERSION>1.2.10_22</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gimp Toolkit for X11 GUI (current stable version)</COMMENTS>
      <NAME>gtk</NAME>
      <VERSION>2.20.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Theme engine for the GTK+-2.0 toolkit</COMMENTS>
      <NAME>gtk-engines2</NAME>
      <VERSION>2.20.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Download and view files from various digital cameras</COMMENTS>
      <NAME>gtkam-gnome</NAME>
      <VERSION>0.1.17</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An OpenGL widget for the GTK+2 GUI toolkit</COMMENTS>
      <NAME>gtkglarea</NAME>
      <VERSION>2.0.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An OpenGL extension to GTK</COMMENTS>
      <NAME>gtkglext</NAME>
      <VERSION>1.2.0_8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>C++ wrapper for Gtk+, Pango, Atk</COMMENTS>
      <NAME>gtkmm</NAME>
      <VERSION>2.20.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A GTK+ 2 spell checking component</COMMENTS>
      <NAME>gtkspell</NAME>
      <VERSION>2.0.16_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Triangulated Surface Library</COMMENTS>
      <NAME>gts</NAME>
      <VERSION>0.7.6_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The &quot;meta-port&quot; for GutenPrint</COMMENTS>
      <NAME>gutenprint</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GutenPrint Printer Driver</COMMENTS>
      <NAME>gutenprint-base</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GutenPrint Printer Driver</COMMENTS>
      <NAME>gutenprint-cups</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GutenPrint Printer Driver</COMMENTS>
      <NAME>gutenprint-foomatic</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GutenPrint Printer Driver</COMMENTS>
      <NAME>gutenprint-ijs</NAME>
      <VERSION>5.2.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNOME virtual file system</COMMENTS>
      <NAME>gvfs</NAME>
      <VERSION>1.6.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Hardware Abstraction Layer for simplifying device access</COMMENTS>
      <NAME>hal</NAME>
      <VERSION>0.5.14_8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Automatically generating simple manual pages from program o</COMMENTS>
      <NAME>help2man</NAME>
      <VERSION>1.38.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A high-color icon theme shell from the FreeDesktop project</COMMENTS>
      <NAME>hicolor-icon-theme</NAME>
      <VERSION>0.12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Drivers and utilities for HP Printers and All-in-One device</COMMENTS>
      <NAME>hplip</NAME>
      <VERSION>3.10.5_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ICE authority file utility for X</COMMENTS>
      <NAME>iceauth</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utilities of the Tango project</COMMENTS>
      <NAME>icon-naming-utils</NAME>
      <VERSION>0.8.90</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>International Components for Unicode (from IBM)</COMMENTS>
      <NAME>icu</NAME>
      <VERSION>3.8.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ILM Base libraries a.k.a. Half, IlmThread, Imath and Iex</COMMENTS>
      <NAME>ilmbase</NAME>
      <VERSION>1.0.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Imake and other utilities from X.Org</COMMENTS>
      <NAME>imake</NAME>
      <VERSION>1.0.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A graphic library for enlightenment package</COMMENTS>
      <NAME>imlib</NAME>
      <VERSION>1.9.15_11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A program seeks to become a full featured open source SVG e</COMMENTS>
      <NAME>inkscape</NAME>
      <VERSION>0.47_8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Input extension headers</COMMENTS>
      <NAME>inputproto</NAME>
      <VERSION>2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools to internationalize various kinds of data files</COMMENTS>
      <NAME>intltool</NAME>
      <VERSION>0.40.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Lists of the country, language and currency iso names</COMMENTS>
      <NAME>iso-codes</NAME>
      <VERSION>3.19</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Character entity sets from ISO 8879:1986 (SGML)</COMMENTS>
      <NAME>iso8879</NAME>
      <VERSION>1986_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A low-latency audio server</COMMENTS>
      <NAME>jackit</NAME>
      <VERSION>0.118.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An implementation of the codec specified in the JPEG-2000 s</COMMENTS>
      <NAME>jasper</NAME>
      <VERSION>1.900.1_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Wrapper script for various Java Virtual Machines</COMMENTS>
      <NAME>javavmwrapper</NAME>
      <VERSION>2.3.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Java XPath Engine</COMMENTS>
      <NAME>jaxen</NAME>
      <VERSION>1.0_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Lossless compression for bi-level images such as scanned pa</COMMENTS>
      <NAME>jbigkit</NAME>
      <VERSION>1.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Java Development Kit 1.6.0</COMMENTS>
      <NAME>jdk</NAME>
      <VERSION>1.6.0.3p4_15</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Java library for accessing and manipulating XML documents</COMMENTS>
      <NAME>jdom</NAME>
      <VERSION>1.1.1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>IJG\'s jpeg compression utilities</COMMENTS>
      <NAME>jpeg</NAME>
      <VERSION>8_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Makefile framework</COMMENTS>
      <NAME>kBuild</NAME>
      <VERSION>0.1.5.p2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>KB extension headers</COMMENTS>
      <NAME>kbproto</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utility port which installs a hierarchy of shared KDE direc</COMMENTS>
      <NAME>kdehier</NAME>
      <VERSION>1.0_11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utility port that creates hierarchy of shared KDE4 director</COMMENTS>
      <NAME>kdehier4</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Base set of libraries needed by KDE programs</COMMENTS>
      <NAME>kdelibs</NAME>
      <VERSION>3.5.10_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Fast MP3 encoder kit</COMMENTS>
      <NAME>lame</NAME>
      <VERSION>3.98.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Light Color Management System -- a color management library</COMMENTS>
      <NAME>lcms</NAME>
      <VERSION>1.19_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The FS library</COMMENTS>
      <NAME>libFS</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenGL library that renders using GLX or DRI</COMMENTS>
      <NAME>libGL</NAME>
      <VERSION>7.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenGL utility library</COMMENTS>
      <NAME>libGLU</NAME>
      <VERSION>7.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Inter Client Exchange library for X11</COMMENTS>
      <NAME>libICE</NAME>
      <VERSION>1.0.6,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for creating trees of CORBA IDL files</COMMENTS>
      <NAME>libIDL</NAME>
      <VERSION>0.8.14_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Session Management library for X11</COMMENTS>
      <NAME>libSM</NAME>
      <VERSION>1.1.1_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X11 library</COMMENTS>
      <NAME>libX11</NAME>
      <VERSION>1.3.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The XScrnSaver library</COMMENTS>
      <NAME>libXScrnSaver</NAME>
      <VERSION>1.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The XTrap library</COMMENTS>
      <NAME>libXTrap</NAME>
      <VERSION>1.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Authentication Protocol library for X11</COMMENTS>
      <NAME>libXau</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Athena Widgets library</COMMENTS>
      <NAME>libXaw</NAME>
      <VERSION>1.0.7,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Composite extension library</COMMENTS>
      <NAME>libXcomposite</NAME>
      <VERSION>0.4.1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X client-side cursor loading library</COMMENTS>
      <NAME>libXcursor</NAME>
      <VERSION>1.1.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Damage extension library</COMMENTS>
      <NAME>libXdamage</NAME>
      <VERSION>1.1.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Display Manager Control Protocol library</COMMENTS>
      <NAME>libXdmcp</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Xevie library</COMMENTS>
      <NAME>libXevie</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X11 Extension library</COMMENTS>
      <NAME>libXext</NAME>
      <VERSION>1.1.1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Fixes extension library</COMMENTS>
      <NAME>libXfixes</NAME>
      <VERSION>4.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X font libary</COMMENTS>
      <NAME>libXfont</NAME>
      <VERSION>1.4.0,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Xfontcache library</COMMENTS>
      <NAME>libXfontcache</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A client-sided font API for X applications</COMMENTS>
      <NAME>libXft</NAME>
      <VERSION>2.1.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Input extension library</COMMENTS>
      <NAME>libXi</NAME>
      <VERSION>1.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X11 Xinerama library</COMMENTS>
      <NAME>libXinerama</NAME>
      <VERSION>1.1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Miscellaneous Utilities libraries</COMMENTS>
      <NAME>libXmu</NAME>
      <VERSION>1.0.5,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X print library</COMMENTS>
      <NAME>libXp</NAME>
      <VERSION>1.0.0,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Pixmap library</COMMENTS>
      <NAME>libXpm</NAME>
      <VERSION>3.5.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Resize and Rotate extension library</COMMENTS>
      <NAME>libXrandr</NAME>
      <VERSION>1.3.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Render extension library</COMMENTS>
      <NAME>libXrender</NAME>
      <VERSION>0.9.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Resource usage library</COMMENTS>
      <NAME>libXres</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Toolkit library</COMMENTS>
      <NAME>libXt</NAME>
      <VERSION>1.0.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Test extension</COMMENTS>
      <NAME>libXtst</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Video Extension library</COMMENTS>
      <NAME>libXv</NAME>
      <VERSION>1.0.5,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Video Extension Motion Compensation library</COMMENTS>
      <NAME>libXvMC</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X DGA Extension</COMMENTS>
      <NAME>libXxf86dga</NAME>
      <VERSION>1.1.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X XF86-Misc Extension</COMMENTS>
      <NAME>libXxf86misc</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Vidmode Extension</COMMENTS>
      <NAME>libXxf86vm</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A free library for decoding ATSC A/52 streams, aka AC-3</COMMENTS>
      <NAME>liba52</NAME>
      <VERSION>0.7.4_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for high-performance 2D graphics</COMMENTS>
      <NAME>libart_lgpl</NAME>
      <VERSION>2.3.21,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>IPC library used by GnuPG and gpgme</COMMENTS>
      <NAME>libassuan</NAME>
      <VERSION>2.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A sound library for SGI audio file</COMMENTS>
      <NAME>libaudiofile</NAME>
      <VERSION>0.2.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A component and compound document system for GNOME2</COMMENTS>
      <NAME>libbonobo</NAME>
      <VERSION>2.24.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GUI frontend to the libbonobo component of GNOME 2</COMMENTS>
      <NAME>libbonoboui</NAME>
      <VERSION>2.24.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library to access data on a CDDB server</COMMENTS>
      <NAME>libcddb</NAME>
      <VERSION>1.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Compact Disc Input and Control Library</COMMENTS>
      <NAME>libcdio</NAME>
      <VERSION>0.82_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A unit test framework for C</COMMENTS>
      <NAME>libcheck</NAME>
      <VERSION>0.9.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>CSS2 parsing library</COMMENTS>
      <NAME>libcroco</NAME>
      <VERSION>0.6.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Lightweight C library that eases the writing of UNIX daemon</COMMENTS>
      <NAME>libdaemon</NAME>
      <VERSION>0.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Free DTS Coherent Acoustics decoder</COMMENTS>
      <NAME>libdca</NAME>
      <VERSION>0.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DMX extension library</COMMENTS>
      <NAME>libdmx</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Standard library for Window Maker dock apps</COMMENTS>
      <NAME>libdockapp</NAME>
      <VERSION>0.6.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Userspace interface to kernel Direct Rendering Module servi</COMMENTS>
      <NAME>libdrm</NAME>
      <VERSION>2.4.12_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Quasar DV codec (libdv): software codec for DV video encodi</COMMENTS>
      <NAME>libdv</NAME>
      <VERSION>1.0.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for MPEG TS and DVB PSI tables decoding and gener</COMMENTS>
      <NAME>libdvbpsi</NAME>
      <VERSION>0.1.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Portable abstraction library for DVD decryption</COMMENTS>
      <NAME>libdvdcss</NAME>
      <VERSION>1.2.10_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The library for the xine-dvdnav plugin</COMMENTS>
      <NAME>libdvdnav</NAME>
      <VERSION>0.1.10_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MPlayer version of the libdvdread project</COMMENTS>
      <NAME>libdvdread</NAME>
      <VERSION>4.1.3_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>EBML (Extensible Binary Meta Language), sort of binary vers</COMMENTS>
      <NAME>libebml</NAME>
      <VERSION>1.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A mail library</COMMENTS>
      <NAME>libetpan</NAME>
      <VERSION>1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for inspecting program\'s backtrace</COMMENTS>
      <NAME>libexecinfo</NAME>
      <VERSION>1.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library to read digital camera file meta-data</COMMENTS>
      <NAME>libexif</NAME>
      <VERSION>0.6.18_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GTK+ widgets to display/edit EXIF tags</COMMENTS>
      <NAME>libexif-gtk</NAME>
      <VERSION>0.3.5_7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A video encoding library</COMMENTS>
      <NAME>libfame</NAME>
      <VERSION>0.9.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Foreign Function Interface</COMMENTS>
      <NAME>libffi</NAME>
      <VERSION>3.0.9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The fontenc Library</COMMENTS>
      <NAME>libfontenc</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library routines for working with Flashpix images</COMMENTS>
      <NAME>libfpx</NAME>
      <VERSION>1.2.0.12_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>General purpose crypto library based on code used in GnuPG</COMMENTS>
      <NAME>libgcrypt</NAME>
      <VERSION>1.4.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNOME glade library</COMMENTS>
      <NAME>libglade2</NAME>
      <VERSION>2.6.4_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenGL utility toolkit</COMMENTS>
      <NAME>libglut</NAME>
      <VERSION>7.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libraries for GNOME, a GNU desktop environment</COMMENTS>
      <NAME>libgnome</NAME>
      <VERSION>2.30.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A program that keeps passwords and other secrets</COMMENTS>
      <NAME>libgnome-keyring</NAME>
      <VERSION>2.30.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A graphics library for GNOME</COMMENTS>
      <NAME>libgnomecanvas</NAME>
      <VERSION>2.30.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libraries for the GNOME GUI, a GNU desktop environment</COMMENTS>
      <NAME>libgnomeui</NAME>
      <VERSION>2.24.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Common error values for all GnuPG components</COMMENTS>
      <NAME>libgpg-error</NAME>
      <VERSION>1.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A universal digital camera picture control tool</COMMENTS>
      <NAME>libgphoto2</NAME>
      <VERSION>2.4.9.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An extensible i/o abstraction for dealing with structured f</COMMENTS>
      <NAME>libgsf</NAME>
      <VERSION>1.14.18_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A character set conversion library</COMMENTS>
      <NAME>libiconv</NAME>
      <VERSION>1.13.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ID3 tags library (part of MAD project)</COMMENTS>
      <NAME>libid3tag</NAME>
      <VERSION>0.15.1b</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Internationalized Domain Names command line tool</COMMENTS>
      <NAME>libidn</NAME>
      <VERSION>1.15_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>C library that supports plugin printer driver for Ghostscri</COMMENTS>
      <NAME>libijs</NAME>
      <VERSION>0.35_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for Evation\'s Irman infrared reciever</COMMENTS>
      <NAME>libirman</NAME>
      <VERSION>0.4.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Codec for karaoke and text encapsulation for Ogg</COMMENTS>
      <NAME>libkate</NAME>
      <VERSION>0.3.7_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>KDE Image Plugin Interface</COMMENTS>
      <NAME>libkipi</NAME>
      <VERSION>0.1.6_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>KSBA is an X.509 Library</COMMENTS>
      <NAME>libksba</NAME>
      <VERSION>1.0.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An easy to use C/C++ seam carving library</COMMENTS>
      <NAME>liblqr-1</NAME>
      <VERSION>0.4.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>System independent dlopen wrapper</COMMENTS>
      <NAME>libltdl</NAME>
      <VERSION>2.2.6b</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libmad library (part of MAD project)</COMMENTS>
      <NAME>libmad</NAME>
      <VERSION>0.15.1b_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Extensible Multimedia Container Format</COMMENTS>
      <NAME>libmatroska</NAME>
      <VERSION>1.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multi-cipher cryptographic library (used in PHP)</COMMENTS>
      <NAME>libmcrypt</NAME>
      <VERSION>2.5.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multiple-image Network Graphics (MNG) reference library</COMMENTS>
      <NAME>libmng</NAME>
      <VERSION>1.0.10_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ModPlug mod-like music shared libraries</COMMENTS>
      <NAME>libmodplug</NAME>
      <VERSION>0.8.8.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High quality audio compression format</COMMENTS>
      <NAME>libmpcdec</NAME>
      <VERSION>1.2.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A free library for decoding mpeg-2 and mpeg-1 video streams</COMMENTS>
      <NAME>libmpeg2</NAME>
      <VERSION>0.5.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for Microsoft compression formats</COMMENTS>
      <NAME>libmspack</NAME>
      <VERSION>0.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library and transmitter that implements ICE-19</COMMENTS>
      <NAME>libnice</NAME>
      <VERSION>0.0.12_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for desktop notifications</COMMENTS>
      <NAME>libnotify</NAME>
      <VERSION>0.4.5_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ogg bitstream library</COMMENTS>
      <NAME>libogg</NAME>
      <VERSION>1.2.0,4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library of optimized inner loops</COMMENTS>
      <NAME>liboil</NAME>
      <VERSION>0.3.17</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Old X library</COMMENTS>
      <NAME>liboldX</NAME>
      <VERSION>1.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for camera RAW files decoding</COMMENTS>
      <NAME>libopenraw</NAME>
      <VERSION>0.0.8_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Generic PCI access library</COMMENTS>
      <NAME>libpciaccess</NAME>
      <VERSION>0.11.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library that provides automatic proxy configuration managem</COMMENTS>
      <NAME>libproxy</NAME>
      <VERSION>0.2.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>This library provides weak aliases for pthread functions</COMMENTS>
      <NAME>libpthread-stubs</NAME>
      <VERSION>0.3_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Backend library for the Pidgin multi-protocol messaging cli</COMMENTS>
      <NAME>libpurple</NAME>
      <VERSION>2.7.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for parsing and rendering SVG vector-graphic files</COMMENTS>
      <NAME>librsvg2</NAME>
      <VERSION>2.26.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Secret Rabbit Code: a Sample Rate Converter for audio</COMMENTS>
      <NAME>libsamplerate</NAME>
      <VERSION>0.1.7_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Callback Framework for C++</COMMENTS>
      <NAME>libsigc++</NAME>
      <VERSION>2.2.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Handling page faults in user mode</COMMENTS>
      <NAME>libsigsegv</NAME>
      <VERSION>2.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library to access SMI MIB information</COMMENTS>
      <NAME>libsmi</NAME>
      <VERSION>0.4.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Reading and writing files containing sampled sound (like WA</COMMENTS>
      <NAME>libsndfile</NAME>
      <VERSION>1.0.21_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A SOAP (Simple Object Access Protocol) implementation in C</COMMENTS>
      <NAME>libsoup</NAME>
      <VERSION>2.30.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library to convert clothoid splines into bezier splines</COMMENTS>
      <NAME>libspiro</NAME>
      <VERSION>20071029</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for manipulating POSIX and GNU tar files</COMMENTS>
      <NAME>libtar</NAME>
      <VERSION>1.2.11_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ASN.1 structure parser library</COMMENTS>
      <NAME>libtasn1</NAME>
      <VERSION>2.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Thai language support library</COMMENTS>
      <NAME>libthai</NAME>
      <VERSION>0.1.5_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Theora video codec for the Ogg multimedia streaming system</COMMENTS>
      <NAME>libtheora</NAME>
      <VERSION>1.1.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Generic shared library support script</COMMENTS>
      <NAME>libtool</NAME>
      <VERSION>2.2.6b</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A C++ library implementing a BitTorrent client</COMMENTS>
      <NAME>libtorrent-rasterbar</NAME>
      <VERSION>0.15.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools and library routines for working with GIF images</COMMENTS>
      <NAME>libungif</NAME>
      <VERSION>4.1.4_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for manipulating Unicode characters and strings</COMMENTS>
      <NAME>libunicode</NAME>
      <VERSION>0.4_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Interface to record user sessions to utmp and wtmp files</COMMENTS>
      <NAME>libutempter</NAME>
      <VERSION>1.1.5_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Video4Linux library</COMMENTS>
      <NAME>libv4l</NAME>
      <VERSION>0.6.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Provide an easy API to write one\'s own vnc server</COMMENTS>
      <NAME>libvncserver</NAME>
      <VERSION>0.9.7_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library to provide file system type information</COMMENTS>
      <NAME>libvolume_id</NAME>
      <VERSION>0.81.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Audio compression codec library</COMMENTS>
      <NAME>libvorbis</NAME>
      <VERSION>1.3.1,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>VP8 Codec SDK</COMMENTS>
      <NAME>libvpx</NAME>
      <VERSION>0.9.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools and library for converting Microsoft WMF (windows met</COMMENTS>
      <NAME>libwmf</NAME>
      <VERSION>0.2.8.4_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools for importing and exporting WordPerfect(tm) documents</COMMENTS>
      <NAME>libwpd</NAME>
      <VERSION>0.8.14_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for importing and converting Corel WordPerfect(tm) </COMMENTS>
      <NAME>libwpg</NAME>
      <VERSION>0.1.3_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The W3C Reference Library</COMMENTS>
      <NAME>libwww</NAME>
      <VERSION>5.4.0_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The X protocol C-language Binding (XCB) library</COMMENTS>
      <NAME>libxcb</NAME>
      <VERSION>1.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libraries for xine multimedia player</COMMENTS>
      <NAME>libxine</NAME>
      <VERSION>1.1.19</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XKB file library</COMMENTS>
      <NAME>libxkbfile</NAME>
      <VERSION>1.0.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xkbui library</COMMENTS>
      <NAME>libxkbui</NAME>
      <VERSION>1.0.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML API for C++</COMMENTS>
      <NAME>libxml++</NAME>
      <VERSION>2.30.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML parser library for GNOME</COMMENTS>
      <NAME>libxml</NAME>
      <VERSION>1.8.17_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML parser library for GNOME</COMMENTS>
      <NAME>libxml2</NAME>
      <VERSION>2.7.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The XSLT C library for GNOME</COMMENTS>
      <NAME>libxslt</NAME>
      <VERSION>1.1.26_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Mozilla runtime package that can be used to bootstrap XUL+X</COMMENTS>
      <NAME>libxul</NAME>
      <VERSION>1.9.0.17_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Routines to access raw VBI capture devices</COMMENTS>
      <NAME>libzvbi</NAME>
      <VERSION>0.2.33_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Simple RSS/RDF feed reader</COMMENTS>
      <NAME>liferea</NAME>
      <VERSION>1.6.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Accessibility Toolkit, Linux/i386 binary (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-atk</NAME>
      <VERSION>1.24.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Vector graphics library Cairo (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-cairo</NAME>
      <VERSION>1.8.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The command line tool for transferring files with URL synta</COMMENTS>
      <NAME>linux-f10-curl</NAME>
      <VERSION>7.19.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RFC 2222 SASL (Simple Authentication and Security Layer) (L</COMMENTS>
      <NAME>linux-f10-cyrus-sasl2</NAME>
      <VERSION>2.1.22</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Linux/i386 binary port of Expat XML-parsing library (Linux </COMMENTS>
      <NAME>linux-f10-expat</NAME>
      <VERSION>2.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Adobe Flash Player NPAPI Plugin</COMMENTS>
      <NAME>linux-f10-flashplugin</NAME>
      <VERSION>10.1r82</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An XML-based font configuration API for X Windows (Linux Fe</COMMENTS>
      <NAME>linux-f10-fontconfig</NAME>
      <VERSION>2.6.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Image loading library for GTK+ (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-gdk-pixbuf</NAME>
      <VERSION>0.22.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GTK+ library, version 2.X (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-gtk2</NAME>
      <VERSION>2.14.7_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A high-color icon theme shell from the FreeDesktop project</COMMENTS>
      <NAME>linux-f10-hicolor-icon-theme</NAME>
      <VERSION>0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RPM of the JPEG lib (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-jpeg</NAME>
      <VERSION>6b</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The library implementing the SSH2 protocol (Linux Fedora 10</COMMENTS>
      <NAME>linux-f10-libssh2</NAME>
      <VERSION>0.18</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library providing XML and HTML support (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-libxml2</NAME>
      <VERSION>2.7.3_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Netscape Portable Runtime (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-nspr</NAME>
      <VERSION>4.7.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Network Security Services (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-nss</NAME>
      <VERSION>3.12.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Lightweight Directory Access Protocol libraries (Linux Fedo</COMMENTS>
      <NAME>linux-f10-openldap</NAME>
      <VERSION>2.4.12_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The OpenSSL toolkit (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-openssl</NAME>
      <VERSION>0.9.8g</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The pango library (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-pango</NAME>
      <VERSION>1.22.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RPM of the PNG lib (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-png</NAME>
      <VERSION>1.2.37_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The library that implements an embeddable SQL database engi</COMMENTS>
      <NAME>linux-f10-sqlite3</NAME>
      <VERSION>3.5.9_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The TIFF library, Linux/i386 binary (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-tiff</NAME>
      <VERSION>3.8.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Xorg libraries (Linux Fedora 10)</COMMENTS>
      <NAME>linux-f10-xorg-libs</NAME>
      <VERSION>7.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>HTML rendering library</COMMENTS>
      <NAME>linux-libgtkembedmoz</NAME>
      <VERSION>0.0.20100806</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A complete Web Authoring System</COMMENTS>
      <NAME>linux-nvu</NAME>
      <VERSION>1.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Linux RealPlayer 10 from RealNetworks</COMMENTS>
      <NAME>linux-realplayer</NAME>
      <VERSION>10.0.9.809.20070726_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Base set of packages needed in Linux mode for i386/amd64 (L</COMMENTS>
      <NAME>linux_base-f10</NAME>
      <VERSION>10_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Linux Infared Remote Control</COMMENTS>
      <NAME>lirc</NAME>
      <VERSION>0.8.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>LIVE.COM Streaming Media</COMMENTS>
      <NAME>liveMedia</NAME>
      <VERSION>2010.06.22,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Small, compilable scripting language providing easy access </COMMENTS>
      <NAME>lua</NAME>
      <VERSION>5.1.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Locale and ISO 2022 support for Unicode terminals</COMMENTS>
      <NAME>luit</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Portable speedy, lossless data compression library</COMMENTS>
      <NAME>lzo2</NAME>
      <VERSION>2.03_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU m4</COMMENTS>
      <NAME>m4</NAME>
      <VERSION>1.4.14_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Apple\'s mDNSResponder</COMMENTS>
      <NAME>mDNSResponder</NAME>
      <VERSION>214.3.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A dependency generator for makefiles</COMMENTS>
      <NAME>makedepend</NAME>
      <VERSION>1.0.2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Convenient video file and movie encoder</COMMENTS>
      <NAME>mencoder</NAME>
      <VERSION>1.0.r20100117_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MIME Media Types list</COMMENTS>
      <NAME>mime-support</NAME>
      <VERSION>3.48.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create an index of X font files in a directory</COMMENTS>
      <NAME>mkfontdir</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Creates an index of scalable font files for X</COMMENTS>
      <NAME>mkfontscale</NAME>
      <VERSION>1.0.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library and tools to read, create, and modify mp4 files</COMMENTS>
      <NAME>mp4v2</NAME>
      <VERSION>1.9.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Print multiple pages per sheet of paper</COMMENTS>
      <NAME>mpage</NAME>
      <VERSION>2.5.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library of complex numbers with arbitrarily high precision</COMMENTS>
      <NAME>mpc</NAME>
      <VERSION>0.8.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library for multiple-precision floating-point computation</COMMENTS>
      <NAME>mpfr</NAME>
      <VERSION>3.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High performance media player supporting many formats</COMMENTS>
      <NAME>mplayer</NAME>
      <VERSION>1.0.r20100117_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Skins for MPlayer\'s Graphical User Interface (GUI)</COMMENTS>
      <NAME>mplayer-skins</NAME>
      <VERSION>1.1.2_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multithreaded SQL database (client)</COMMENTS>
      <NAME>mysql-client</NAME>
      <VERSION>5.1.47</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multithreaded SQL database (server)</COMMENTS>
      <NAME>mysql-server</NAME>
      <VERSION>5.1.47</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Network Audio System</COMMENTS>
      <NAME>nas</NAME>
      <VERSION>1.9.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>General-purpose multi-platform x86 and amd64 assembler</COMMENTS>
      <NAME>nasm</NAME>
      <VERSION>2.08.02,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An HTTP and WebDAV client library for Unix systems</COMMENTS>
      <NAME>neon29</NAME>
      <VERSION>0.29.3_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An extendable SNMP implementation</COMMENTS>
      <NAME>net-snmp</NAME>
      <VERSION>5.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A full-featured integrated environment for Java</COMMENTS>
      <NAME>netbeans</NAME>
      <VERSION>6.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A toolkit for conversion of images between different format</COMMENTS>
      <NAME>netpbm</NAME>
      <VERSION>10.26.64_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Console application which monitors network traffic in real </COMMENTS>
      <NAME>nload</NAME>
      <VERSION>0.7.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Port scanning utility for large networks</COMMENTS>
      <NAME>nmap</NAME>
      <VERSION>5.21_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A compatibility plugin for Netscape 4 (NPAPI) plugins</COMMENTS>
      <NAME>nspluginwrapper</NAME>
      <VERSION>1.2.2_7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A platform-neutral API for system level and libc like funct</COMMENTS>
      <NAME>nspr</NAME>
      <VERSION>4.8.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Libraries to support development of security-enabled applic</COMMENTS>
      <NAME>nss</NAME>
      <VERSION>3.12.6_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Open Computer and Software Inventory Next Generation</COMMENTS>
      <NAME>ocsinventory-ng</NAME>
      <VERSION>1.02.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A BSDL Regular Expressions library compatible with POSIX/GN</COMMENTS>
      <NAME>oniguruma</NAME>
      <VERSION>4.7.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Motif X11 Toolkit (industry standard GUI (IEEE 1295))</COMMENTS>
      <NAME>open-motif</NAME>
      <VERSION>2.2.3_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenCORE implementation of AMR Narrowband &amp; Wideband speech</COMMENTS>
      <NAME>opencore-amr</NAME>
      <VERSION>0.1.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An open-source JPEG 2000 codec</COMMENTS>
      <NAME>openjpeg</NAME>
      <VERSION>1.3_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Open source LDAP client implementation</COMMENTS>
      <NAME>openldap-client</NAME>
      <VERSION>2.4.23</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Secure IP/Ethernet tunnel daemon</COMMENTS>
      <NAME>openvpn</NAME>
      <VERSION>2.1.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library and toolset to operate arrays of data</COMMENTS>
      <NAME>orc</NAME>
      <VERSION>0.4.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl interface to compute differences between two objects</COMMENTS>
      <NAME>p5-Algorithm-Diff</NAME>
      <VERSION>1.1902</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DBI persistent connection, authentication and authorization</COMMENTS>
      <NAME>p5-Apache-DBI-mp2</NAME>
      <VERSION>1.08</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module for creation and manipulation of tar files</COMMENTS>
      <NAME>p5-Archive-Tar</NAME>
      <VERSION>1.60</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module to access BSD resource limit and priority funct</COMMENTS>
      <NAME>p5-BSD-Resource</NAME>
      <VERSION>1.2904</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Provides information about classes</COMMENTS>
      <NAME>p5-Class-Inspector</NAME>
      <VERSION>1.24</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Low-Level Interface to bzip2 compression library</COMMENTS>
      <NAME>p5-Compress-Raw-Bzip2</NAME>
      <VERSION>2.027</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Low-Level Interface to zlib compression library</COMMENTS>
      <NAME>p5-Compress-Raw-Zlib</NAME>
      <VERSION>2.027</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to zlib compression library</COMMENTS>
      <NAME>p5-Compress-Zlib</NAME>
      <VERSION>2.015</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module to extract data from Macintosh BinHex files</COMMENTS>
      <NAME>p5-Convert-BinHex</NAME>
      <VERSION>1.119</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to Cipher Block Chaining with DES and IDEA</COMMENTS>
      <NAME>p5-Crypt-CBC</NAME>
      <VERSION>2.30</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to DES block cipher</COMMENTS>
      <NAME>p5-Crypt-DES</NAME>
      <VERSION>2.05</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>OpenSSL\'s multiprecision integer arithmetic</COMMENTS>
      <NAME>p5-Crypt-OpenSSL-Bignum</NAME>
      <VERSION>0.04</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module to RSA encode and decode strings using OpenSSL</COMMENTS>
      <NAME>p5-Crypt-OpenSSL-RSA</NAME>
      <VERSION>0.26</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to the OpenSSL pseudo-random number generat</COMMENTS>
      <NAME>p5-Crypt-OpenSSL-Random</NAME>
      <VERSION>0.04</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to allow p5-libwww LWP to make https connec</COMMENTS>
      <NAME>p5-Crypt-SSLeay</NAME>
      <VERSION>0.57_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MySQL 5.1 driver for the Perl5 Database Interface (DBI)</COMMENTS>
      <NAME>p5-DBD-mysql51</NAME>
      <VERSION>4.013</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The perl5 Database Interface.  Required for DBD::* modules</COMMENTS>
      <NAME>p5-DBI</NAME>
      <VERSION>1.61.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module containing date manipulation routines</COMMENTS>
      <NAME>p5-Date-Manip</NAME>
      <VERSION>5.56</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to HMAC Message-Digest Algorithms</COMMENTS>
      <NAME>p5-Digest-HMAC</NAME>
      <VERSION>1.02</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension for SHA-1/224/256/384/512</COMMENTS>
      <NAME>p5-Digest-SHA</NAME>
      <VERSION>5.48</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl interface to the SHA-1 Algorithm</COMMENTS>
      <NAME>p5-Digest-SHA1</NAME>
      <VERSION>2.12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Produce RFC 2822 date strings</COMMENTS>
      <NAME>p5-Email-Date-Format</NAME>
      <VERSION>1.002</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An Encode::Encoding subclass that detects the encoding of d</COMMENTS>
      <NAME>p5-Encode-Detect</NAME>
      <VERSION>1.01</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module to provide Error/exception support for perl: Er</COMMENTS>
      <NAME>p5-Error</NAME>
      <VERSION>0.17016</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Compile and link C code for Perl modules</COMMENTS>
      <NAME>p5-ExtUtils-CBuilder</NAME>
      <VERSION>0.2703,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension to install files from here to there</COMMENTS>
      <NAME>p5-ExtUtils-Install</NAME>
      <VERSION>1.54</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Converts Perl XS code into C code</COMMENTS>
      <NAME>p5-ExtUtils-ParseXS</NAME>
      <VERSION>2.22.03</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module for parsing HTML documents</COMMENTS>
      <NAME>p5-HTML-Parser</NAME>
      <VERSION>3.65</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Some useful data table in parsing HTML</COMMENTS>
      <NAME>p5-HTML-Tagset</NAME>
      <VERSION>3.20</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A collection of modules to manupulate HTML syntax trees</COMMENTS>
      <NAME>p5-HTML-Tree</NAME>
      <VERSION>3.23</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Base Class for IO::Uncompress modules</COMMENTS>
      <NAME>p5-IO-Compress-Base</NAME>
      <VERSION>2.015</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An interface to allow writing bzip2 compressed data to file</COMMENTS>
      <NAME>p5-IO-Compress-Bzip2</NAME>
      <VERSION>2.015</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface for reading and writing of (g)zip files</COMMENTS>
      <NAME>p5-IO-Compress-Zlib</NAME>
      <VERSION>2.015_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module with object interface to AF_INET6 domain socket</COMMENTS>
      <NAME>p5-IO-Socket-INET6</NAME>
      <VERSION>2.63</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to SSL sockets</COMMENTS>
      <NAME>p5-IO-Socket-SSL</NAME>
      <VERSION>1.33</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Simplified Perl5 module to handle I/O on in-core strings</COMMENTS>
      <NAME>p5-IO-String</NAME>
      <VERSION>1.08</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>IO:: style interface to Compress::Zlib</COMMENTS>
      <NAME>p5-IO-Zlib</NAME>
      <VERSION>1.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module for using IO handles with non-file objects</COMMENTS>
      <NAME>p5-IO-stringy</NAME>
      <VERSION>2.110</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A simple interface for creating (not parsing!) MIME message</COMMENTS>
      <NAME>p5-MIME-Lite</NAME>
      <VERSION>3.02.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A set of perl5 modules for MIME</COMMENTS>
      <NAME>p5-MIME-Tools</NAME>
      <VERSION>5.428,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension for determining MIME types</COMMENTS>
      <NAME>p5-MIME-Types</NAME>
      <VERSION>1.29</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module to process and/or create DKIM email</COMMENTS>
      <NAME>p5-Mail-DKIM</NAME>
      <VERSION>0.38</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A highly efficient mail filter for identifying spam</COMMENTS>
      <NAME>p5-Mail-SpamAssassin</NAME>
      <VERSION>3.3.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 modules for dealing with Internet e-mail messages</COMMENTS>
      <NAME>p5-Mail-Tools</NAME>
      <VERSION>2.06</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Math::BigInt - Arbitrary size integer math package</COMMENTS>
      <NAME>p5-Math-BigInt</NAME>
      <VERSION>1.89</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Build and install Perl modules</COMMENTS>
      <NAME>p5-Module-Build</NAME>
      <VERSION>0.3607</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to the DNS resolver, and dynamic updates</COMMENTS>
      <NAME>p5-Net-DNS</NAME>
      <VERSION>0.66</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension for manipulating IPv4/IPv6 addresses</COMMENTS>
      <NAME>p5-Net-IP</NAME>
      <VERSION>1.25_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Performs simple NetBIOS Name Service Requests</COMMENTS>
      <NAME>p5-Net-NBName</NAME>
      <VERSION>0.26</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An SMTP client supporting SSL</COMMENTS>
      <NAME>p5-Net-SMTP-SSL</NAME>
      <VERSION>1.01</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A perl module for SNMP... Net::SNMP</COMMENTS>
      <NAME>p5-Net-SNMP</NAME>
      <VERSION>5.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to SSL</COMMENTS>
      <NAME>p5-Net-SSLeay</NAME>
      <VERSION>1.36</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module for working with IP addresses and blocks thereo</COMMENTS>
      <NAME>p5-NetAddr-IP</NAME>
      <VERSION>4.02.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Parse nmap scan data with perl</COMMENTS>
      <NAME>p5-Nmap-Parser</NAME>
      <VERSION>1.19_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension for generating and using LALR parsers</COMMENTS>
      <NAME>p5-Parse-Yapp</NAME>
      <VERSION>1.05</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>SOAP::Lite - Client and server side SOAP implementation</COMMENTS>
      <NAME>p5-SOAP-Lite</NAME>
      <VERSION>0.712_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>IPv6 related part of the C socket.h defines and structure m</COMMENTS>
      <NAME>p5-Socket6</NAME>
      <VERSION>0.23</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Persistency for perl data structures</COMMENTS>
      <NAME>p5-Storable</NAME>
      <VERSION>2.21</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ensure that a platform has weaken support</COMMENTS>
      <NAME>p5-Task-Weaken</NAME>
      <VERSION>1.03</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Run perl standard test scripts with statistics</COMMENTS>
      <NAME>p5-Test-Harness</NAME>
      <VERSION>3.21</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Text::Diff - Perform diffs on files and record sets</COMMENTS>
      <NAME>p5-Text-Diff</NAME>
      <VERSION>1.37</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl interface to iconv() codeset conversion function</COMMENTS>
      <NAME>p5-Text-Iconv</NAME>
      <VERSION>1.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module implementing ordered in-memory associative arra</COMMENTS>
      <NAME>p5-Tie-IxHash</NAME>
      <VERSION>1.22</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A perl5 module implementing High resolution time, sleep, an</COMMENTS>
      <NAME>p5-Time-HiRes</NAME>
      <VERSION>1.9721,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 module containing a better/faster date parser for abs</COMMENTS>
      <NAME>p5-TimeDate</NAME>
      <VERSION>1.20,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A re-port of a perl5 interface to Tk8.4</COMMENTS>
      <NAME>p5-Tk</NAME>
      <VERSION>804.028.502_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module to require() from a variable</COMMENTS>
      <NAME>p5-UNIVERSAL-require</NAME>
      <VERSION>0.13</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 interface to Uniform Resource Identifier (URI) refere</COMMENTS>
      <NAME>p5-URI</NAME>
      <VERSION>1.54</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module for building DOM Level 1 compliant document str</COMMENTS>
      <NAME>p5-XML-DOM</NAME>
      <VERSION>1.44</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Filter to put all characters() in one event</COMMENTS>
      <NAME>p5-XML-Filter-BufferText</NAME>
      <VERSION>1.01</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Yet another Perl SAX XML Writer</COMMENTS>
      <NAME>p5-XML-Handler-YAWriter</NAME>
      <VERSION>0.23</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A simple generic namespace support class</COMMENTS>
      <NAME>p5-XML-NamespaceSupport</NAME>
      <VERSION>1.11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl extension interface to James Clark\'s XML parser, expat</COMMENTS>
      <NAME>p5-XML-Parser</NAME>
      <VERSION>2.36_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Regular expressions for XML tokens</COMMENTS>
      <NAME>p5-XML-RegExp</NAME>
      <VERSION>0.03</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Simple API for XML</COMMENTS>
      <NAME>p5-XML-SAX</NAME>
      <VERSION>0.96</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Simple API for XML</COMMENTS>
      <NAME>p5-XML-SAX-Expat</NAME>
      <VERSION>0.40</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>SAX2 XML Writer</COMMENTS>
      <NAME>p5-XML-SAX-Writer</NAME>
      <VERSION>0.52</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Trivial API for reading and writing XML (esp config files)</COMMENTS>
      <NAME>p5-XML-Simple</NAME>
      <VERSION>2.18</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Process huge XML documents by chunks via a tree interface</COMMENTS>
      <NAME>p5-XML-Twig</NAME>
      <VERSION>3.35</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Modules for parsing and evaluating XPath statements</COMMENTS>
      <NAME>p5-XML-XPath</NAME>
      <VERSION>1.13</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl module for querying XML tree structures with XQL</COMMENTS>
      <NAME>p5-XML-XQL</NAME>
      <VERSION>0.68</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>YAML implementation in Perl</COMMENTS>
      <NAME>p5-YAML</NAME>
      <VERSION>0.71</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Message handling functions</COMMENTS>
      <NAME>p5-gettext</NAME>
      <VERSION>1.05_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl5 library for WWW access</COMMENTS>
      <NAME>p5-libwww</NAME>
      <VERSION>5.836</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Collection of Perl5 modules for working with XML</COMMENTS>
      <NAME>p5-libxml</NAME>
      <VERSION>0.08</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A script that helps install Postscript fonts in X Window Sy</COMMENTS>
      <NAME>p5-type1inst</NAME>
      <VERSION>0.6.1_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>File archiver with high compression ratio</COMMENTS>
      <NAME>p7zip</NAME>
      <VERSION>9.13</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An open-source framework for the layout and rendering of i1</COMMENTS>
      <NAME>pango</NAME>
      <VERSION>1.28.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>C++ wrapper for Pango</COMMENTS>
      <NAME>pangomm</NAME>
      <VERSION>2.26.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Database of all known ID\'s used in PCI devices</COMMENTS>
      <NAME>pciids</NAME>
      <VERSION>20091229</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Perl Compatible Regular Expressions library</COMMENTS>
      <NAME>pcre</NAME>
      <VERSION>8.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A C library for dynamically generating PDF</COMMENTS>
      <NAME>pdflib</NAME>
      <VERSION>7.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>PEAR framework for PHP</COMMENTS>
      <NAME>pear</NAME>
      <VERSION>1.9.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An interface to AT&amp;T\'s GraphViz tools</COMMENTS>
      <NAME>pear-Image_GraphViz</NAME>
      <VERSION>1.2.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>PEAR regression testing framework for unit tests</COMMENTS>
      <NAME>pear-PHPUnit</NAME>
      <VERSION>3.4.12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A PHP Script Tokenises and Sniffs PHP and JavaScript code</COMMENTS>
      <NAME>pear-PHP_CodeSniffer</NAME>
      <VERSION>1.2.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A PECL extension to retrieve info about files</COMMENTS>
      <NAME>pecl-fileinfo</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A PECL extension to create PDF on the fly</COMMENTS>
      <NAME>pecl-pdflib</NAME>
      <VERSION>2.1.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Practical Extraction and Report Language</COMMENTS>
      <NAME>perl-threaded</NAME>
      <VERSION>5.10.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multimedia framework for KDE4</COMMENTS>
      <NAME>phonon</NAME>
      <VERSION>4.4.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Phonon gstreamer backend</COMMENTS>
      <NAME>phonon-gstreamer</NAME>
      <VERSION>4.4.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>PHP Scripting Language</COMMENTS>
      <NAME>php5</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The bz2 shared extension for php</COMMENTS>
      <NAME>php5-bz2</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The ctype shared extension for php</COMMENTS>
      <NAME>php5-ctype</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The curl shared extension for php</COMMENTS>
      <NAME>php5-curl</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The dom shared extension for php</COMMENTS>
      <NAME>php5-dom</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The exif shared extension for php</COMMENTS>
      <NAME>php5-exif</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A &quot;meta-port&quot; to install PHP extensions</COMMENTS>
      <NAME>php5-extensions</NAME>
      <VERSION>1.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The filter shared extension for php</COMMENTS>
      <NAME>php5-filter</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The ftp shared extension for php</COMMENTS>
      <NAME>php5-ftp</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The gd shared extension for php</COMMENTS>
      <NAME>php5-gd</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The gettext shared extension for php</COMMENTS>
      <NAME>php5-gettext</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The hash shared extension for php</COMMENTS>
      <NAME>php5-hash</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The iconv shared extension for php</COMMENTS>
      <NAME>php5-iconv</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The imap shared extension for php</COMMENTS>
      <NAME>php5-imap</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The json shared extension for php</COMMENTS>
      <NAME>php5-json</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The ldap shared extension for php</COMMENTS>
      <NAME>php5-ldap</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The mbstring shared extension for php</COMMENTS>
      <NAME>php5-mbstring</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The mcrypt shared extension for php</COMMENTS>
      <NAME>php5-mcrypt</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The mysql shared extension for php</COMMENTS>
      <NAME>php5-mysql</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The mysqli shared extension for php</COMMENTS>
      <NAME>php5-mysqli</NAME>
      <VERSION>5.3.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The openssl shared extension for php</COMMENTS>
      <NAME>php5-openssl</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The pdo shared extension for php</COMMENTS>
      <NAME>php5-pdo</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The pdo_mysql shared extension for php</COMMENTS>
      <NAME>php5-pdo_mysql</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The pdo_sqlite shared extension for php</COMMENTS>
      <NAME>php5-pdo_sqlite</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The posix shared extension for php</COMMENTS>
      <NAME>php5-posix</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The session shared extension for php</COMMENTS>
      <NAME>php5-session</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The simplexml shared extension for php</COMMENTS>
      <NAME>php5-simplexml</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The soap shared extension for php</COMMENTS>
      <NAME>php5-soap</NAME>
      <VERSION>5.3.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The sockets shared extension for php</COMMENTS>
      <NAME>php5-sockets</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The sqlite shared extension for php</COMMENTS>
      <NAME>php5-sqlite</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The tokenizer shared extension for php</COMMENTS>
      <NAME>php5-tokenizer</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xml shared extension for php</COMMENTS>
      <NAME>php5-xml</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xmlreader shared extension for php</COMMENTS>
      <NAME>php5-xmlreader</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xmlrpc shared extension for php</COMMENTS>
      <NAME>php5-xmlrpc</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xmlwriter shared extension for php</COMMENTS>
      <NAME>php5-xmlwriter</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The xsl shared extension for php</COMMENTS>
      <NAME>php5-xsl</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The zip shared extension for php</COMMENTS>
      <NAME>php5-zip</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The zlib shared extension for php</COMMENTS>
      <NAME>php5-zlib</NAME>
      <VERSION>5.3.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A set of PHP-scripts to manage MySQL over the web</COMMENTS>
      <NAME>phpMyAdmin</NAME>
      <VERSION>3.3.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Pidgin multi-protocol messaging client (GTK+ UI)</COMMENTS>
      <NAME>pidgin</NAME>
      <VERSION>2.7.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A collection of simple PIN or passphrase entry dialogs</COMMENTS>
      <NAME>pinentry</NAME>
      <VERSION>0.8.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Low-level pixel manipulation library</COMMENTS>
      <NAME>pixman</NAME>
      <VERSION>0.16.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A utility to retrieve information about installed libraries</COMMENTS>
      <NAME>pkg-config</NAME>
      <VERSION>0.25</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for manipulating PNG images</COMMENTS>
      <NAME>png</NAME>
      <VERSION>1.4.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Framework for controlling access to system-wide components</COMMENTS>
      <NAME>policykit</NAME>
      <VERSION>0.9_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNOME frontend to the PolicKit framework</COMMENTS>
      <NAME>policykit-gnome</NAME>
      <VERSION>0.9.2_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Framework for controlling access to system-wide components</COMMENTS>
      <NAME>polkit</NAME>
      <VERSION>0.96_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A PDF rendering library</COMMENTS>
      <NAME>poppler</NAME>
      <VERSION>0.12.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Poppler encoding data</COMMENTS>
      <NAME>poppler-data</NAME>
      <VERSION>0.4.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gtk bindings to poppler</COMMENTS>
      <NAME>poppler-gtk</NAME>
      <VERSION>0.12.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A getopt(3) like library with a number of enhancements, fro</COMMENTS>
      <NAME>popt</NAME>
      <VERSION>1.14_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Portable cross-platform Audio API</COMMENTS>
      <NAME>portaudio</NAME>
      <VERSION>18.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>FreeBSD ports/packages administration and management tool s</COMMENTS>
      <NAME>portupgrade</NAME>
      <VERSION>2.4.6_4,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Print extension headers</COMMENTS>
      <NAME>printproto</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU Portable Threads</COMMENTS>
      <NAME>pth</NAME>
      <VERSION>2.0.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Video4Linux PWC webcam viewer</COMMENTS>
      <NAME>pwcview</NAME>
      <VERSION>1.4.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings for Cairo</COMMENTS>
      <NAME>py26-cairo</NAME>
      <VERSION>1.8.8_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings for the D-BUS messaging system</COMMENTS>
      <NAME>py26-dbus</NAME>
      <VERSION>0.83.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python Documentation Utilities</COMMENTS>
      <NAME>py26-docutils</NAME>
      <VERSION>0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A GNU Image Manipulation Program</COMMENTS>
      <NAME>py26-gimp-app</NAME>
      <VERSION>2.6.8_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings for GObject</COMMENTS>
      <NAME>py26-gobject</NAME>
      <VERSION>2.21.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A set of Python bindings for gstreamer</COMMENTS>
      <NAME>py26-gstreamer</NAME>
      <VERSION>0.10.18</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A set of Python bindings for GTK+</COMMENTS>
      <NAME>py26-gtk</NAME>
      <VERSION>2.17.0_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Python Imaging Library</COMMENTS>
      <NAME>py26-imaging</NAME>
      <VERSION>1.1.6_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python interface for XML parser library for GNOME</COMMENTS>
      <NAME>py26-libxml2</NAME>
      <VERSION>2.7.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The eGenix mx-Extension Series for Python</COMMENTS>
      <NAME>py26-mx-base</NAME>
      <VERSION>3.1.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>World Timezone Definitions for Python</COMMENTS>
      <NAME>py26-pytz</NAME>
      <VERSION>2010k</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings for the Qt4 toolkit, QtCore module</COMMENTS>
      <NAME>py26-qt4-core</NAME>
      <VERSION>4.7.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings for the Qt4 toolkit, QtGui module</COMMENTS>
      <NAME>py26-qt4-gui</NAME>
      <VERSION>4.7.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library to create PDF documents using the Python language</COMMENTS>
      <NAME>py26-reportlab2</NAME>
      <VERSION>2.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Download, build, install, upgrade, and uninstall Python pac</COMMENTS>
      <NAME>py26-setuptools</NAME>
      <VERSION>0.6c11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python to C and C++ bindings generator</COMMENTS>
      <NAME>py26-sip</NAME>
      <VERSION>4.10.2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Python bindings to the Tk widget set</COMMENTS>
      <NAME>py26-tkinter</NAME>
      <VERSION>2.6.5_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An interpreted object-oriented programming language</COMMENTS>
      <NAME>python26</NAME>
      <VERSION>2.6.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Bittorrent client using Qt4 and libtorrent-rasterbar</COMMENTS>
      <NAME>qbittorrent</NAME>
      <VERSION>2.2.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The build utility of the Qt project</COMMENTS>
      <NAME>qmake</NAME>
      <VERSION>3.3.8_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt4 port of the Scintilla C++ editor class</COMMENTS>
      <NAME>qscintilla2</NAME>
      <VERSION>2.4.3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multiplatform C++ application framework</COMMENTS>
      <NAME>qt</NAME>
      <VERSION>3.3.8_12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt documentation browser</COMMENTS>
      <NAME>qt4-assistant</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>QtCLucene full text search library wrapper</COMMENTS>
      <NAME>qt4-clucene</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt core library</COMMENTS>
      <NAME>qt4-corelib</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt4 bindings for the D-BUS messaging system</COMMENTS>
      <NAME>qt4-dbus</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt ui editor</COMMENTS>
      <NAME>qt4-designer</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Multiplatform C++ application framework</COMMENTS>
      <NAME>qt4-doc</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt graphical user interface library</COMMENTS>
      <NAME>qt4-gui</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>QtHelp module provides QHelpEngine API and is used by Assis</COMMENTS>
      <NAME>qt4-help</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt SVG icon engine plugin</COMMENTS>
      <NAME>qt4-iconengines</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt imageformat plugins for GIF, JPEG, MNG and SVG</COMMENTS>
      <NAME>qt4-imageformats</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt input method plugins</COMMENTS>
      <NAME>qt4-inputmethods</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt localisation tool</COMMENTS>
      <NAME>qt4-linguist</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt meta object compiler</COMMENTS>
      <NAME>qt4-moc</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt network library</COMMENTS>
      <NAME>qt4-network</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt OpenGL library</COMMENTS>
      <NAME>qt4-opengl</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The build utility of the Qt project</COMMENTS>
      <NAME>qt4-qmake</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt3 compatibility library</COMMENTS>
      <NAME>qt4-qt3support</NAME>
      <VERSION>4.6.3_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt unit testing library</COMMENTS>
      <NAME>qt4-qtestlib</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt resource compiler</COMMENTS>
      <NAME>qt4-rcc</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt script</COMMENTS>
      <NAME>qt4-script</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt script</COMMENTS>
      <NAME>qt4-scripttools</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt SQL library</COMMENTS>
      <NAME>qt4-sql</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt SQLite 3.x database plugin</COMMENTS>
      <NAME>qt4-sqlite-plugin</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt SVG library</COMMENTS>
      <NAME>qt4-svg</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt user interface compiler</COMMENTS>
      <NAME>qt4-uic</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt4 WebKit engine</COMMENTS>
      <NAME>qt4-webkit</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt XML library</COMMENTS>
      <NAME>qt4-xml</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XQuery 1.0 and XPath 2.0 support for Qt4</COMMENTS>
      <NAME>qt4-xmlpatterns</NAME>
      <VERSION>4.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Randr extension headers</COMMENTS>
      <NAME>randrproto</NAME>
      <VERSION>1.3.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An OMF help system based on the Freedesktop specification</COMMENTS>
      <NAME>rarian</NAME>
      <VERSION>0.8.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A distributed, collaborative, spam detection and filtering </COMMENTS>
      <NAME>razor-agents</NAME>
      <VERSION>2.84</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A C++ library implementing a BitTorrent client (devel versi</COMMENTS>
      <NAME>rblibtorrent-devel</NAME>
      <VERSION>0.14.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RDP client for Windows NT/2000/2003 Terminal Server</COMMENTS>
      <NAME>rdesktop</NAME>
      <VERSION>1.6.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Record desktop sessions to an Ogg-Theora-Vorbis file</COMMENTS>
      <NAME>recordmydesktop</NAME>
      <VERSION>0.3.8.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RECORD extension headers</COMMENTS>
      <NAME>recordproto</NAME>
      <VERSION>1.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A flexible project management web application</COMMENTS>
      <NAME>redmine</NAME>
      <VERSION>0.9.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>RenderProto protocol headers</COMMENTS>
      <NAME>renderproto</NAME>
      <VERSION>0.11</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Resource extension headers</COMMENTS>
      <NAME>resourceproto</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A simple and easy to use graphical file manager</COMMENTS>
      <NAME>rox-filer</NAME>
      <VERSION>2.10_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The Red Hat Package Manager</COMMENTS>
      <NAME>rpm</NAME>
      <VERSION>3.0.6_15</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Convert .rpm files for extraction with /usr/bin/cpio, needs</COMMENTS>
      <NAME>rpm2cpio</NAME>
      <VERSION>1.2_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A network file distribution/synchronization utility</COMMENTS>
      <NAME>rsync</NAME>
      <VERSION>3.0.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An object-oriented interpreted scripting language</COMMENTS>
      <NAME>ruby</NAME>
      <VERSION>1.8.7.248_2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ruby interface to Sleepycat\'s Berkeley DB revision 2 or lat</COMMENTS>
      <NAME>ruby18-bdb</NAME>
      <VERSION>0.6.5_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Package management framework for the Ruby language</COMMENTS>
      <NAME>ruby18-gems</NAME>
      <VERSION>1.3.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An iconv wrapper class for Ruby</COMMENTS>
      <NAME>ruby18-iconv</NAME>
      <VERSION>1.8.7.248,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ruby module for accessing MySQL databases with a C API like</COMMENTS>
      <NAME>ruby18-mysql</NAME>
      <VERSION>2.8.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A Ruby binding for ImageMagick</COMMENTS>
      <NAME>ruby18-rmagick</NAME>
      <VERSION>2.13.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A toolkit to convert your script to a controllable daemon</COMMENTS>
      <NAME>rubygem-daemons</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A fast, simple event-processing library for Ruby programs</COMMENTS>
      <NAME>rubygem-eventmachine</NAME>
      <VERSION>0.12.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Rack, a Ruby Webserver Interface</COMMENTS>
      <NAME>rubygem-rack</NAME>
      <VERSION>1.1.0,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Ruby Make</COMMENTS>
      <NAME>rubygem-rake</NAME>
      <VERSION>0.8.7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A fast and very simple Ruby web server</COMMENTS>
      <NAME>rubygem-thin</NAME>
      <VERSION>1.2.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Shared libs from the samba package</COMMENTS>
      <NAME>samba34-libsmbclient</NAME>
      <VERSION>3.4.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>API for access to scanners, digitals camera, frame grabbers</COMMENTS>
      <NAME>sane-backends</NAME>
      <VERSION>1.0.21_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An XSLT 2.0 / XPath 2.0 / XQuery 1.0 processor for Java</COMMENTS>
      <NAME>saxon-devel</NAME>
      <VERSION>8.9.0.4_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>High-speed Dirac codec</COMMENTS>
      <NAME>schroedinger</NAME>
      <VERSION>1.0.9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A build tool alternative to make</COMMENTS>
      <NAME>scons</NAME>
      <VERSION>1.3.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A comprehensive desktop publishing program</COMMENTS>
      <NAME>scribus</NAME>
      <VERSION>1.3.3.14</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>ScrnSaver extension headers</COMMENTS>
      <NAME>scrnsaverproto</NAME>
      <VERSION>1.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Cross-platform multimedia development API</COMMENTS>
      <NAME>sdl</NAME>
      <VERSION>1.2.14_1,2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A simple library to load images of various formats as SDL s</COMMENTS>
      <NAME>sdl_image</NAME>
      <VERSION>1.2.10_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The open source, standards compliant web browser</COMMENTS>
      <NAME>seamonkey</NAME>
      <VERSION>2.0.4_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Manage utmp/wtmp entries for non-init X clients</COMMENTS>
      <NAME>sessreg</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Set the keyboard using the X Keyboard Extension</COMMENTS>
      <NAME>setxkbmap</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A MIME type database from the FreeDesktop project</COMMENTS>
      <NAME>shared-mime-info</NAME>
      <VERSION>0.71_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Secure Internet Live Conferencing (SILC) network toolkit</COMMENTS>
      <NAME>silc-toolkit</NAME>
      <VERSION>1.1.10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A fully automated, active web application security reconnai</COMMENTS>
      <NAME>skipfish</NAME>
      <VERSION>1.56b</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Session Manager Proxy</COMMENTS>
      <NAME>smproxy</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An open-source patent-free voice codec</COMMENTS>
      <NAME>speex</NAME>
      <VERSION>1.2.r1_3,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An SQL database engine in a C library</COMMENTS>
      <NAME>sqlite3</NAME>
      <VERSION>3.6.23.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>HTTP Caching Proxy</COMMENTS>
      <NAME>squid</NAME>
      <VERSION>2.7.9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library that supports startup notification spec from freede</COMMENTS>
      <NAME>startup-notification</NAME>
      <VERSION>0.10_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Version control system</COMMENTS>
      <NAME>subversion</NAME>
      <VERSION>1.6.11_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Standard Widget Toolkit for Java</COMMENTS>
      <NAME>swt</NAME>
      <VERSION>3.5.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A Type 1 Rasterizer Library for UNIX/X11</COMMENTS>
      <NAME>t1lib</NAME>
      <VERSION>5.1.2_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library for manipulating ID3 tags and Ogg comments</COMMENTS>
      <NAME>taglib</NAME>
      <VERSION>1.6.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Hierarchical pool based memory allocator</COMMENTS>
      <NAME>talloc</NAME>
      <VERSION>2.0.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tool Command Language</COMMENTS>
      <NAME>tcl</NAME>
      <VERSION>8.5.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tcl common modules</COMMENTS>
      <NAME>tcl-modules</NAME>
      <VERSION>8.5.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Thomas Esser\'s distribution of TeX &amp; friends (binaries)</COMMENTS>
      <NAME>teTeX-base</NAME>
      <VERSION>3.0_20</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Thomas Esser\'s distribution of TeX &amp; friends (texmf tree)</COMMENTS>
      <NAME>teTeX-texmf</NAME>
      <VERSION>3.0_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Meta-port that creates a site-local $TEXMF directory</COMMENTS>
      <NAME>tex-texmflocal</NAME>
      <VERSION>1.9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Texinfo to HTML converter</COMMENTS>
      <NAME>texi2html</NAME>
      <VERSION>1.82,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools and library routines for working with TIFF images</COMMENTS>
      <NAME>tiff</NAME>
      <VERSION>3.9.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Graphical toolkit for Tcl</COMMENTS>
      <NAME>tk</NAME>
      <VERSION>8.5.8_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Extremely portable perl-based make utility</COMMENTS>
      <NAME>tmake</NAME>
      <VERSION>1.7_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A text-console utility for video stream processing</COMMENTS>
      <NAME>transcode</NAME>
      <VERSION>1.1.5_10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>DEC-XTRAP extension headers</COMMENTS>
      <NAME>trapproto</NAME>
      <VERSION>3.4.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tab Window Manager for the X Window System</COMMENTS>
      <NAME>twm</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>MPEG Audio Layer 2 encoder</COMMENTS>
      <NAME>twolame</NAME>
      <VERSION>0.3.12</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Extract, view &amp; test RAR archives</COMMENTS>
      <NAME>unrar</NAME>
      <VERSION>3.93,5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>List, test and extract compressed files in a ZIP archive</COMMENTS>
      <NAME>unzip</NAME>
      <VERSION>6.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An Eclipse Plugin for Designing Java Swing &amp; SWT GUIs</COMMENTS>
      <NAME>v4all</NAME>
      <VERSION>2.1.1.9_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Video4Linux IOCTL header files</COMMENTS>
      <NAME>v4l_compat</NAME>
      <VERSION>1.0.20100403_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNU VCDImager/VCDRip -- The GNU VideoCD Image Maker/Ripping</COMMENTS>
      <NAME>vcdimager</NAME>
      <VERSION>0.7.23_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Video extension headers</COMMENTS>
      <NAME>videoproto</NAME>
      <VERSION>2.3.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Vi &quot;workalike&quot;, with many additional features</COMMENTS>
      <NAME>vim</NAME>
      <VERSION>7.2.411</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A general-purpose full virtualizer for x86 hardware</COMMENTS>
      <NAME>virtualbox-ose</NAME>
      <VERSION>3.2.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>VirtualBox kernel module for FreeBSD</COMMENTS>
      <NAME>virtualbox-ose-kmod</NAME>
      <VERSION>3.2.8</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Qt4 based multimedia player and streaming server</COMMENTS>
      <NAME>vlc</NAME>
      <VERSION>1.1.3,3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Display X and Win32 desktops on remote X/Win32/Java display</COMMENTS>
      <NAME>vnc</NAME>
      <VERSION>4.1.3_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Audio codec for lossless, lossy and hybrid compression</COMMENTS>
      <NAME>wavpack</NAME>
      <VERSION>4.60.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A port of Linux USB webcam and DVB drivers into userspace</COMMENTS>
      <NAME>webcamd</NAME>
      <VERSION>0.1.14_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An opensource browser engine</COMMENTS>
      <NAME>webkit-gtk2</NAME>
      <VERSION>1.2.1_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>HTML validator and sanity checker</COMMENTS>
      <NAME>weblint</NAME>
      <VERSION>1.020</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Retrieve files from the Net via HTTP(S) and FTP</COMMENTS>
      <NAME>wget</NAME>
      <VERSION>1.12_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>GNUstep-compliant NeXTstep window manager clone</COMMENTS>
      <NAME>windowmaker</NAME>
      <VERSION>0.92.0_10</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A powerful network analyzer/capture tool</COMMENTS>
      <NAME>wireshark</NAME>
      <VERSION>1.2.9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A configuration tool for Window Maker</COMMENTS>
      <NAME>wmakerconf</NAME>
      <VERSION>2.12_6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Dockapp for battery &amp; temperature monitoring through ACPI</COMMENTS>
      <NAME>wmbsdbatt</NAME>
      <VERSION>0.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A digital clock dockapp with a similar look to wmcpuload</COMMENTS>
      <NAME>wmclockmon</NAME>
      <VERSION>0.8.1_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An &quot;lcd&quot; dockapp for windowmaker, which displays the curren</COMMENTS>
      <NAME>wmcpuload</NAME>
      <VERSION>1.0.1_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An audio mixer for the WindowMaker dock</COMMENTS>
      <NAME>wmfmixer</NAME>
      <VERSION>0.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Icons mainly for use in Window Maker</COMMENTS>
      <NAME>wmicons</NAME>
      <VERSION>1.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Mem/Swap monitoring dockapp for WindowMaker</COMMENTS>
      <NAME>wmmemmon</NAME>
      <VERSION>1.0.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Network load monitor dockapp</COMMENTS>
      <NAME>wmnetload</NAME>
      <VERSION>1.3_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A neat theme manager for WindowMaker</COMMENTS>
      <NAME>wmthemeinstall</NAME>
      <VERSION>0.62_7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The wxWidgets GUI toolkit with GTK+ bindings</COMMENTS>
      <NAME>wxgtk2</NAME>
      <VERSION>2.6.4_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The wxWidgets GUI toolkit (common files)</COMMENTS>
      <NAME>wxgtk2-common</NAME>
      <VERSION>2.6.4_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The wxWidgets GUI toolkit (common files)</COMMENTS>
      <NAME>wxgtk2-common</NAME>
      <VERSION>2.8.10_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The wxWidgets GUI toolkit (Unicode)</COMMENTS>
      <NAME>wxgtk2-unicode</NAME>
      <VERSION>2.8.10_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X11 server performance test program</COMMENTS>
      <NAME>x11perf</NAME>
      <VERSION>1.5.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Library and tool for encoding H.264/AVC video streams</COMMENTS>
      <NAME>x264</NAME>
      <VERSION>0.0.20100624</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Apache XSLT processor for transforming XML documents</COMMENTS>
      <NAME>xalan-j</NAME>
      <VERSION>2.7.0_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X authority file utility</COMMENTS>
      <NAME>xauth</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Program to adjust backlight brightness</COMMENTS>
      <NAME>xbacklight</NAME>
      <VERSION>1.1.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Allows you to launch shell commands under X with your keybo</COMMENTS>
      <NAME>xbindkeys</NAME>
      <VERSION>1.8.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An easy to use gtk program for configuring Xbindkeys</COMMENTS>
      <NAME>xbindkeys_config</NAME>
      <VERSION>0.1.3_9</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org bitmaps data</COMMENTS>
      <NAME>xbitmaps</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Brightness and gamma correction through the X server</COMMENTS>
      <NAME>xbrightness</NAME>
      <VERSION>0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Scientific calculator for X</COMMENTS>
      <NAME>xcalc</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>The X protocol C-language Binding (XCB) protocol</COMMENTS>
      <NAME>xcb-proto</NAME>
      <VERSION>1.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A module with libxcb/libX11 extension/replacement libraries</COMMENTS>
      <NAME>xcb-util</NAME>
      <VERSION>0.3.6_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Another X11 frontend to mkisofs/cdrecord</COMMENTS>
      <NAME>xcdroast</NAME>
      <VERSION>0.98.a.16_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An X11 IRC client using the GTK+ 2 toolkit</COMMENTS>
      <NAME>xchat</NAME>
      <VERSION>2.8.6_7</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XCMisc extension headers</COMMENTS>
      <NAME>xcmiscproto</NAME>
      <VERSION>1.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Device Color Characterization utility for X</COMMENTS>
      <NAME>xcmsdb</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org cursors themes</COMMENTS>
      <NAME>xcursor-themes</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create an X cursor file from a collection of PNG images</COMMENTS>
      <NAME>xcursorgen</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Tools to allow all applications to integrate with the free </COMMENTS>
      <NAME>xdg-utils</NAME>
      <VERSION>1.0.2_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Display information utility for X</COMMENTS>
      <NAME>xdpyinfo</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Query configuration information of DRI drivers</COMMENTS>
      <NAME>xdriinfo</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XML parser for Java</COMMENTS>
      <NAME>xerces-j</NAME>
      <VERSION>2.9.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Print contents of X events</COMMENTS>
      <NAME>xev</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XExt extension headers</COMMENTS>
      <NAME>xextproto</NAME>
      <VERSION>7.1.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org keyboard input driver</COMMENTS>
      <NAME>xf86-input-keyboard</NAME>
      <VERSION>1.4.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org mouse input driver</COMMENTS>
      <NAME>xf86-input-mouse</NAME>
      <VERSION>1.5.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org ati display driver</COMMENTS>
      <NAME>xf86-video-ati</NAME>
      <VERSION>6.13.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Driver for Intel integrated graphics chipsets</COMMENTS>
      <NAME>xf86-video-intel</NAME>
      <VERSION>2.7.1_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org mach64 display driver</COMMENTS>
      <NAME>xf86-video-mach64</NAME>
      <VERSION>6.8.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org nv display driver</COMMENTS>
      <NAME>xf86-video-nv</NAME>
      <VERSION>2.1.17</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org openChrome display driver</COMMENTS>
      <NAME>xf86-video-openchrome</NAME>
      <VERSION>0.2.904_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org r128 display driver</COMMENTS>
      <NAME>xf86-video-r128</NAME>
      <VERSION>6.8.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org ati RadeonHD display driver</COMMENTS>
      <NAME>xf86-video-radeonhd</NAME>
      <VERSION>1.3.0_3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org vesa display driver</COMMENTS>
      <NAME>xf86-video-vesa</NAME>
      <VERSION>2.3.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XFree86-Bigfont extension headers</COMMENTS>
      <NAME>xf86bigfontproto</NAME>
      <VERSION>1.2.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Test program for the XFree86-DGA extension</COMMENTS>
      <NAME>xf86dga</NAME>
      <VERSION>1.0.2_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XFree86-DGA extension headers</COMMENTS>
      <NAME>xf86dgaproto</NAME>
      <VERSION>2.1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XFree86-DRI extension headers</COMMENTS>
      <NAME>xf86driproto</NAME>
      <VERSION>2.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XFree86-Misc extension headers</COMMENTS>
      <NAME>xf86miscproto</NAME>
      <VERSION>0.9.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XFree86-VidModeExtension extension headers</COMMENTS>
      <NAME>xf86vidmodeproto</NAME>
      <VERSION>2.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gamma correction through the X server.</COMMENTS>
      <NAME>xgamma</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X graphics demo</COMMENTS>
      <NAME>xgc</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Server access control program for X</COMMENTS>
      <NAME>xhost</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Xinerama extension headers</COMMENTS>
      <NAME>xineramaproto</NAME>
      <VERSION>1.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Window System initializer</COMMENTS>
      <NAME>xinit</NAME>
      <VERSION>1.2.0_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Very useful utility for configuring and testing XInput devi</COMMENTS>
      <NAME>xinput</NAME>
      <VERSION>1.5.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Compile XKB keyboard description</COMMENTS>
      <NAME>xkbcomp</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XKB event daemon</COMMENTS>
      <NAME>xkbevd</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XKB utility demos</COMMENTS>
      <NAME>xkbutils</NAME>
      <VERSION>1.0.1_2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X Keyboard Configuration Database</COMMENTS>
      <NAME>xkeyboard-config</NAME>
      <VERSION>1.8_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utility for killing a client by its X resource</COMMENTS>
      <NAME>xkill</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Like XLock session locker/screen saver, but just more</COMMENTS>
      <NAME>xlockmore</NAME>
      <VERSION>5.31</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>List interned atoms defined on a server</COMMENTS>
      <NAME>xlsatoms</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>List client applications running on a display</COMMENTS>
      <NAME>xlsclients</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>SGML and XML catalog manager</COMMENTS>
      <NAME>xmlcatmgr</NAME>
      <VERSION>2.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utility for modifying keymaps and pointer button mappings i</COMMENTS>
      <NAME>xmodmap</NAME>
      <VERSION>1.0.4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Open source (LGPL), tree-based API for processing XML with </COMMENTS>
      <NAME>xom</NAME>
      <VERSION>1.1_2,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org complete distribution metaport</COMMENTS>
      <NAME>xorg</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org apps meta-port</COMMENTS>
      <NAME>xorg-apps</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org cf files for use with imake builds</COMMENTS>
      <NAME>xorg-cf-files</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org documentation files</COMMENTS>
      <NAME>xorg-docs</NAME>
      <VERSION>1.4,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org drivers meta-port</COMMENTS>
      <NAME>xorg-drivers</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org 100dpi bitmap fonts</COMMENTS>
      <NAME>xorg-fonts-100dpi</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org fonts meta-port</COMMENTS>
      <NAME>xorg-fonts</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org 75dpi bitmap fonts</COMMENTS>
      <NAME>xorg-fonts-75dpi</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Cyrillic bitmap fonts</COMMENTS>
      <NAME>xorg-fonts-cyrillic</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org miscellaneous bitmap fonts</COMMENTS>
      <NAME>xorg-fonts-miscbitmaps</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org TrueType fonts</COMMENTS>
      <NAME>xorg-fonts-truetype</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org Type1 fonts</COMMENTS>
      <NAME>xorg-fonts-type1</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.org libraries meta-port</COMMENTS>
      <NAME>xorg-libraries</NAME>
      <VERSION>7.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org development aclocal macros</COMMENTS>
      <NAME>xorg-macros</NAME>
      <VERSION>1.6.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X.Org X server and related programs</COMMENTS>
      <NAME>xorg-server</NAME>
      <VERSION>1.7.5,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Utility for printing an X window dump</COMMENTS>
      <NAME>xpr</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Property displayer for X</COMMENTS>
      <NAME>xprop</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X11 protocol headers</COMMENTS>
      <NAME>xproto</NAME>
      <VERSION>7.0.16</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Primitive command line interface to the RandR extension</COMMENTS>
      <NAME>xrandr</NAME>
      <VERSION>1.3.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>X server resource database utility</COMMENTS>
      <NAME>xrdb</NAME>
      <VERSION>1.0.6</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Refresh all or part of an X screen</COMMENTS>
      <NAME>xrefresh</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Gtk-based X11 frontend for SANE (Scanner Access Now Easy)</COMMENTS>
      <NAME>xsane</NAME>
      <VERSION>0.996_4</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>User preference utility for X</COMMENTS>
      <NAME>xset</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Set the mode for an X Input Device</COMMENTS>
      <NAME>xsetmode</NAME>
      <VERSION>1.0.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>root window parameter setting utility for X</COMMENTS>
      <NAME>xsetroot</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Terminal emulator for the X Window System</COMMENTS>
      <NAME>xterm</NAME>
      <VERSION>261</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Abstract network code for X</COMMENTS>
      <NAME>xtrans</NAME>
      <VERSION>1.2.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>An opensource MPEG-4 codec, based on OpenDivx</COMMENTS>
      <NAME>xvid</NAME>
      <VERSION>1.2.2_1,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>XviD configuration panel for transcode</COMMENTS>
      <NAME>xvid4conf</NAME>
      <VERSION>1.12_5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Capture your X display to individual frames or MPEG video</COMMENTS>
      <NAME>xvidcap</NAME>
      <VERSION>1.1.4.p1_8,1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Print out X-Video extension adaptor information</COMMENTS>
      <NAME>xvinfo</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Dump an image of an X window</COMMENTS>
      <NAME>xwd</NAME>
      <VERSION>1.0.3</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Window information utility for X</COMMENTS>
      <NAME>xwininfo</NAME>
      <VERSION>1.0.5</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Image displayer for X</COMMENTS>
      <NAME>xwud</NAME>
      <VERSION>1.0.2</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>LZMA compression and decompression tools</COMMENTS>
      <NAME>xz</NAME>
      <VERSION>4.999.9_1</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A complete rewrite of the NASM assembler</COMMENTS>
      <NAME>yasm</NAME>
      <VERSION>1.1.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A program for downloading videos from YouTube.com</COMMENTS>
      <NAME>youtube_dl</NAME>
      <VERSION>2010.04.04</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>Create/update ZIP files compatible with pkzip</COMMENTS>
      <NAME>zip</NAME>
      <VERSION>3.0</VERSION>
    </SOFTWARES>
    <SOFTWARES>
      <COMMENTS>A library to provide transparent read access to zipped file</COMMENTS>
      <NAME>zziplib</NAME>
      <VERSION>0.13.59</VERSION>
    </SOFTWARES>
    <STORAGES>
      <DESCRIPTION>ad4</DESCRIPTION>
      <DISKSIZE>305245</DISKSIZE>
      <MANUFACTURER>Hitachi</MANUFACTURER>
      <MODEL>HTS545032B9A300 PB3OC60F</MODEL>
      <TYPE></TYPE>
    </STORAGES>
    <STORAGES>
      <DESCRIPTION>acd0</DESCRIPTION>
      <MODEL>MATSHITADVD-RAM UJ890AS/1.00</MODEL>
      <TYPE></TYPE>
    </STORAGES>
    <USERS>
      <LOGIN>ddurieux</LOGIN>
    </USERS>
    <VERSIONCLIENT>FusionInventory-Agent_v2.1.2</VERSIONCLIENT>
    <VIRTUALMACHINES>
      <MEMORY>370</MEMORY>
      <NAME>Debian</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>f6a66daa-6500-4f2d-981e-d2894af48756</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>198</MEMORY>
      <NAME>Windows2000</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>a98d0732-029b-4b7a-ae27-d0ae9f12b180</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>740</MEMORY>
      <NAME>Windows7</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>f6a7a2b4-b147-4326-8200-1e3edd068463</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>449</MEMORY>
      <NAME>Xen</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>fc9e187d-1a76-49fe-ac27-f3249e1328a5</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>776</MEMORY>
      <NAME>Centos</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>2cbb51b4-503c-4b5e-a6a5-878851a04658</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>512</MEMORY>
      <NAME>Debian</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>6718c8a5-90ce-4dc2-8869-29853023f821</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>200</MEMORY>
      <NAME>debian10023</NAME>
      <STATUS>running</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>ec43b1fc-0efc-487f-8188-a104473db0d5</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>200</MEMORY>
      <NAME>debian10024</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>d410a9a0-0b31-4bad-9a51-80ab37701d1d</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>512</MEMORY>
      <NAME>FreeBSD8 i386</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>d1857d13-a67f-4ba9-a3e6-101e42f59268</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>1024</MEMORY>
      <NAME>MacOSX 10.6</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>85b780f6-b3fd-44d3-bb94-d8583cd78770</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>512</MEMORY>
      <NAME>windows 7 RU</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>c404fff7-67bf-4b9c-95cc-19c6b27bcd5f</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>306</MEMORY>
      <NAME>windows xp RU</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>fbc00535-0e8d-4955-8026-2bd50bf606e9</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>512</MEMORY>
      <NAME>Windows XP</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>3fd80a8f-ed78-4421-9192-c0af6f5f66d5</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>512</MEMORY>
      <NAME>Windows2000 (fusion agent)</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>f8d4f838-aaa4-4f9b-b756-f95bc8d9cceb</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>256</MEMORY>
      <NAME>windowsxp01</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>c7608d9d-c76c-4dab-8411-2784deb4eb1f</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
    <VIRTUALMACHINES>
      <MEMORY>256</MEMORY>
      <NAME>windowsxp02</NAME>
      <STATUS>off</STATUS>
      <SUBSYSTEM>Sun xVM VirtualBox</SUBSYSTEM>
      <UUID>cd033191-26c3-49c9-afee-1e3affc933ee</UUID>
      <VCPU>1</VCPU>
      <VMTYPE>VirtualBox</VMTYPE>
    </VIRTUALMACHINES>
  </CONTENT>
  <DEVICEID>port003-2010-06-08-08-13-45</DEVICEID>
  <QUERY>INVENTORY</QUERY>
</REQUEST>
');
// ********** End ********** //

if (isset($GLOBALS["HTTP_RAW_POST_DATA"])) {
   // Get conf tu know if SSL is only
   $fusioninventory_config = new PluginFusionInventoryConfig;
   $ssl = $fusioninventory_config->getValue(19, 'ssl_only');
   if (((isset($_SERVER["HTTPS"])) AND ($_SERVER["HTTPS"] == "on") AND ($ssl == "1"))
       OR ($ssl == "0")) {
      // echo "On continue";
   } else {
      $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='ISO-8859-1'?>
<REPLY>
</REPLY>");
      $PluginFusioninventoryCommunication->noSSL();
      exit();
   }
   $ocsinventory = '0';
   file_put_contents(GLPI_PLUGIN_DOC_DIR."/fusioninventory/dial.log".rand(), gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]));
   $state = $pta->importToken(gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]));
   if ($state == '2') { // agent created
      $ocsinventory = '1';
   }
   $top0 = gettimeofday();
   if (!$PluginFusioninventoryCommunication->import(gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]))) {
      //if ($ac->connectionOK($errors)) {
      if (1) {
         $res .= "1'".$errors."'";

         $p_xml = gzuncompress($GLOBALS["HTTP_RAW_POST_DATA"]);
         $pxml = @simplexml_load_string($p_xml);

         if (isset($pxml->DEVICEID)) {

            $PluginFusioninventoryCommunication->setXML("<?xml version='1.0' encoding='UTF-8'?>
<REPLY>
</REPLY>");


            $PluginFusionInventoryConfig        = new PluginFusionInventoryConfig;
            $PluginFusioninventoryTaskjobstatus = new PluginFusioninventoryTaskjobstatus;

            $a_agent = $pta->InfosByKey($pxml->DEVICEID);

            $single = 0;

            // Get taskjob in waiting
            $PluginFusioninventoryCommunication->getTaskAgent($a_agent['id']);

            // ******** Send XML
            
            $PluginFusioninventoryCommunication->addInventory();
            $PluginFusioninventoryCommunication->addProlog();
            $PluginFusioninventoryCommunication->setXML($PluginFusioninventoryCommunication->getXML());
            //echo $PluginFusioninventoryCommunication->getSend();
         }
      } else {
         $res .= "0'".$errors."'";
      }
   }
}

?>
