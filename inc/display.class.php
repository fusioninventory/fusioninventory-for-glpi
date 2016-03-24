<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDisplay extends CommonDBTM {

   /**
   * Display static progress bar (used for SNMP cartridge state)
   *
   *@param $pourcentage integer pourcentage to display
   *@param $message value message to display on this bar
   *@param $order if empty <20% display in red, if not empty, >80% display in red
   *
   *@return nothing
   **/
   static function bar($pourcentage, $message='', $order='', $width='400', $height='20') {
      if ((!empty($pourcentage)) AND ($pourcentage < 0)) {
         $pourcentage = "";
      } else if ((!empty($pourcentage)) AND ($pourcentage > 100)) {
         $pourcentage = "";
      }
      echo "<div>
               <table class='tab_cadre' width='".$width."'>
                     <tr>
                        <td align='center' width='".$width."'>";

      if ((!empty($pourcentage))
              || ($pourcentage == "0")) {
         echo $pourcentage."% ".$message;
      }

      echo                  "</td>
                     </tr>
                     <tr>
                        <td>
                           <table cellpadding='0' cellspacing='0'>
                                 <tr>
                                    <td width='".$width."' height='0' colspan='2'></td>
                                 </tr>
                                 <tr>";
      if (empty($pourcentage)) {
         echo "<td></td>";
      } else {
         echo "                              <td bgcolor='";
         if ($order!= '') {
            if ($pourcentage > 80) {
               echo "red";
            } else if($pourcentage > 60) {
               echo "orange";
            } else {
               echo "green";
            }
         } else {
            if ($pourcentage < 20) {
               echo "red";
            } else if($pourcentage < 40) {
               echo "orange";
            } else {
               echo "green";
            }
         }
         if ($pourcentage == 0) {
            echo "' height='".$height."' width='1'>&nbsp;</td>";
         } else {
            echo "' height='".$height."' width='".(($width * $pourcentage) / 100)."'>&nbsp;</td>";
         }
      }
      if ($pourcentage == 0) {
         echo "                           <td height='".$height."' width='1'></td>";
      } else {
         echo "                           <td height='".$height."' width='".
                 ($width - (($width * $pourcentage) / 100))."'></td>";
      }
      echo "                        </tr>
                           </table>
                        </td>
                     </tr>
               </table>
            </div>";
   }



   /**
   * Disable debug mode to not see php errors
   *
   **/
   static function disableDebug() {
      error_reporting(0);
      set_error_handler(array('PluginFusioninventoryDisplay', 'error_handler'));
   }



   /**
   * Enable debug mode if user is in debug mode
   *
   **/
   static function reenableusemode() {
      if ($_SESSION['glpi_use_mode']==Session::DEBUG_MODE){
         ini_set('display_errors', 'On');
         error_reporting(E_ALL | E_STRICT);
         set_error_handler("userErrorHandler");
      }
   }



   static function error_handler($errno, $errstr, $errfile, $errline) {
//   echo 'ca marche';
//             return true;
   }



   /**
   * Display progress bar
   *
   *@param $width integer width of the html array/bar
   *@param $percent interger pourcentage of the bar
   *@param $options array
   *     - title value title of the progressbar to display
   *     - simple bool simple display or not
   *     - forcepadding bool
   *
   *@return value code of this bar
   **/
   static function getProgressBar($width, $percent, $options=array()) {
      global $CFG_GLPI;

      $param = array();
      $param['title']=__('Progress', 'fusioninventory');
      $param['simple']=FALSE;
      $param['forcepadding']=FALSE;

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $param[$key]=$val;
         }
      }

      $percentwidth=floor($percent*$width/100);
      $output="<div class='center'><table class='tab_cadre' width='".($width+20)."px'>";
      if (!$param['simple']) {
         $output.="<tr><th class='center'>".$param['title']."&nbsp;".$percent."%</th></tr>";
      }
      $output.="<tr><td>
                <table><tr><td class='center' style='background:url(".$CFG_GLPI["root_doc"].
                "/pics/loader.png) repeat-x;' width='.$percentwidth' height='12'>";
      if ($param['simple']) {
         $output.=$percent."%";
      } else {
         $output.='&nbsp;';
      }
      $output.="</td></tr></table></td>";
      $output.="</tr></table>";
      $output.="</div>";
      return $output;
   }
}

?>
