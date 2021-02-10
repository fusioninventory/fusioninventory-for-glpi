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
 * This file is used to manage the general display in plugin.
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
 * Manage the general display in plugin.
 */
class PluginFusioninventoryDisplay extends CommonDBTM {


   /**
    * Display static progress bar (used for SNMP cartridge state)
    *
    * @param integer $percentage
    * @param string $message
    * @param string $order
    * @param integer $width
    * @param integer $height
    */
   static function bar($percentage, $message = '', $order = '', $width = '400', $height = '20') {
      if ((!empty($percentage)) AND ($percentage < 0)) {
         $percentage = "";
      } else if ((!empty($percentage)) AND ($percentage > 100)) {
         $percentage = "";
      }
      echo "<div>
               <table class='tab_cadre' width='".$width."'>
                     <tr>
                        <td align='center' width='".$width."'>";

      if ((!empty($percentage))
              || ($percentage == "0")) {
         echo $percentage."% ".$message;
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
      if (empty($percentage)) {
         echo "<td></td>";
      } else {
         echo "                              <td bgcolor='";
         if ($order!= '') {
            if ($percentage > 80) {
               echo "red";
            } else if ($percentage > 60) {
               echo "orange";
            } else {
               echo "green";
            }
         } else {
            if ($percentage < 20) {
               echo "red";
            } else if ($percentage < 40) {
               echo "orange";
            } else {
               echo "green";
            }
         }
         if ($percentage == 0) {
            echo "' height='".$height."' width='1'>&nbsp;</td>";
         } else {
            echo "' height='".$height."' width='".(($width * $percentage) / 100)."'>&nbsp;</td>";
         }
      }
      if ($percentage == 0) {
         echo "                           <td height='".$height."' width='1'></td>";
      } else {
         echo "                           <td height='".$height."' width='".
                 ($width - (($width * $percentage) / 100))."'></td>";
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
    */
   static function disableDebug() {
      error_reporting(0);
      set_error_handler(['PluginFusioninventoryDisplay', 'error_handler']);
   }


   /**
   * Enable debug mode if user is in debug mode
   **/
   static function reenableusemode() {
      Toolbox::setDebugMode();
   }


   /**
    * When debug is disabled, we transfer every errors in this emtpy function.
    *
    * @param integer $errno
    * @param string $errstr
    * @param string $errfile
    * @param integer $errline
    */
   static function error_handler($errno, $errstr, $errfile, $errline) {
   }


   /**
    * Display progress bar
    *
    * @global array $CFG_GLPI
    * @param integer $width
    * @param integer $percent
    * @param array $options
    * @return string
    */
   static function getProgressBar($width, $percent, $options = []) {
      global $CFG_GLPI;

      $param = [];
      $param['title']=__('Progress', 'fusioninventory');
      $param['simple']=false;
      $param['forcepadding']=false;

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
