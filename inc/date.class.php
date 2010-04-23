<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryDate extends CommonDBTM {
   static function date($number_dates,$frequence,$today='') {
      if ($today == "") {
         $today = time();
      } else {
         $explode = explode("-",$today);
         $today = mktime(0, 0, 0, $explode[1], $explode[2], $explode[0]);
      }

      // ** For weekly stats
      switch ($frequence) {
         case "day":
            // Get days
            for ($i = 0 ; $i < $number_dates ; $i++) {
               $dates[] = strftime("%Y-%m-%d", ($today - (86400 * ($number_dates - 1))  + (86400 * $i)));
            }
            break;

         case "week":
            // Get Last Monday
            if (gmdate("w", $today) == 0) $today -= (86400 * 6);
            if (gmdate("w", $today) > 1) $today -= (86400 * (gmdate("w", $today) - 1));
            for ($i = 0 ; $i < $number_dates ; $i++) {
               $dates[$i] = strftime("%Y-%m-%d", (($today - (86400 * 7 * ($number_dates - 1)))+(86400 * 7 *  $i)));
            }
            break;

         case "month":
            // ** For monthly stats
            $month = (gmdate("n", $today));
            $year = (gmdate("Y", $today));
            $dates[($number_dates - 1)] = strftime("%Y-%m-%d", mktime(0, 0, 0, $month, 1, $year));
            for ($i = 0 ; $i < ($number_dates - 1) ; $i++) {
               $dates[$i] = strftime("%Y-%m-%d", mktime(0, 0, 0, ($month - ($number_dates - 1) + $i), 1, $year));
            }
            break;

         case "year":
            // ** For yearly stats
            $year = (gmdate("Y", $today));
            $dates[($number_dates - 1)] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, 1, $year));
            for ($i = 0 ; $i < ($number_dates - 1) ; $i++) {
               $dates[$i] = strftime("%Y-%m-%d", mktime(0, 0, 0, 1, 1, ($year - ($number_dates - 1) + $i)));
            }
            break;
      }
      return $dates;
   }

   static function printer_calendar($getvalue,$field,$target) {
      global $DB,$LANG;

      echo "<div align='center'><form method='post' action='".$target."'>";
      echo "<table class='tab_cadre'><tr class='tab_bg_2'><td align='right'>";
      echo $LANG["search"][9]." :</td><td>";
      showDateFormItem($field,$getvalue,false);
      echo "</td><td rowspan='2' align='center'><input type=\"submit\" class='button' name=\"submit\" Value=\"". $LANG["buttons"][7] ."\" /></td></tr>";
      echo "</table></form></div>";
   }
}

?>