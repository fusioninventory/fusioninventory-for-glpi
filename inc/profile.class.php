<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

class PluginFusioninventoryProfile extends CommonDBTM {


   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      if ($item->getID() > 0
              && $item->fields['interface'] == 'central') {
         return self::createTabEntry('FusionInventory');
      }
   }



   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
      global $CFG_GLPI;

      if ($item->getID() > 0) {
         $pfProfile = new self();
         $pfProfile->showProfileForm($item->getID(),
              $CFG_GLPI['root_doc'].'/plugins/fusioninventory/front/profile.php');
      }

      return TRUE;
   }



   /**
    * Add profile
    *
    * @param $p_type Right type ('wol', 'agents'...)
    * @param $p_right Right (NULL, r, w)
    * @param $p_profiles_id Profile id
    *
    * @return integer the new id of the added item (or FALSE if fail)
    **/
    static function addProfile($p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         if (!isset($_SESSION['glpiactiveprofile']['id'])) {
            return;
         }
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      if (!self::profileExists($p_profiles_id, $p_type)) {
         $pfp = new PluginFusioninventoryProfile();
         return $pfp->add(array('type'       => $p_type,
                                'right'      => $p_right,
                                'profiles_id'=> $p_profiles_id));
      }
   }



   /**
    * Update profile
    *
    * @param $p_id Profile id
    * @param $p_plugins_id Module plugin id
    * @param $p_type Right type ('wol', 'agents'...)
    * @param $p_right Right (NULL, r, w)
    * @param $p_profiles_id Profile id
    *
    * @return boolean : TRUE on success
    **/
   function updateProfile($p_id, $p_type, $p_right, $p_profiles_id=NULL) {
      if (is_null($p_profiles_id)) {
         $p_profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      if (self::profileExists($p_profiles_id, $p_type)) {
         return $this->update(array('id'         => $p_id,
                                    'type'       => $p_type,
                                    'right'      => $p_right,
                                    'profiles_id'=> $p_profiles_id));
      }
   }



   /**
    * Create full profile (used on install plugin)
    *
    * @param $a_profile array with Right type ('wol', 'agents'...) and Right (NULL, r, w)
    **/
   static function initProfile($pluginname) {

      if (isset($pluginname)) {
         $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($pluginname);
         if (method_exists($class, "profiles")) {
            $a_profile = call_user_func(array($class, "profiles"));
            foreach ($a_profile as $data) {
               PluginFusioninventoryProfile::addProfile($data['profil'], 'w');
            }
         }
      }
   }



   /**
    * Get info if profile exist
    *
    * @param type $profiles_id id of the profile
    * @param type $type value type of right (example : agent, remotecontrol, configuration...)
    *
    * @return TRUE or FALSE
    */
   static function profileExists($profiles_id, $type) {
      global $DB;
      $query = "SELECT `id` AS cpt FROM ".getTableForItemType('PluginFusioninventoryProfile');
      $query.= " WHERE `type`='$type'";
      $query.= " AND `profiles_id`='$profiles_id'";
      $results = $DB->query($query);
      if ($DB->numrows($results) > 0) {
         return TRUE;
      } else {
         return FALSE;
      }
   }



   /**
    * Change profile (for used connected)
    *
    **/
   static function changeprofile() {
      if (isset($_SESSION['glpiactiveprofile']['id'])) {
         $pfp = new PluginFusioninventoryProfile();
         $a_rights = $pfp->find("`profiles_id` = '".$_SESSION['glpiactiveprofile']['id']."'");
         $i = 0;
         foreach ($a_rights as $datas) {
            $i++;
            $_SESSION["glpi_plugin_fusioninventory_profile"][$datas['type']] = $datas['right'];
         }
         if ($i == '0') {
            unset($_SESSION["glpi_plugin_fusioninventory_profile"]);
         }
      }
   }



   /**
    * test if user have right
    *
    * @param $p_type Right type ('wol', 'agents'...)
    * @param $p_right Right (NULL, r, w)
    *
    * @return boolean : TRUE if right is ok
    **/
   static function haveRight($p_type, $p_right) {
      $matches=array(
            ""  => array("", "r", "w"), // ne doit pas arriver normalement
            "r" => array("r", "w"),
            "w" => array("w"),
               );
      if (isset($_SESSION["glpi_plugin_fusioninventory_profile"][$p_type])
                && in_array($_SESSION["glpi_plugin_fusioninventory_profile"][$p_type],
                            $matches[$p_right])) {
         return TRUE;
      } else {
         return FALSE;
      }
   }



   /**
    * Check right and display error if right not ok
    *
    * @param $p_type Right type ('wol', 'agents'...)
    * @param $p_right Right (NULL, r, w)
    **/
   static function checkRight($p_type, $p_right) {
      global $CFG_GLPI;

      $pfp = new PluginFusioninventoryProfile();
      if (!$pfp->haveRight($p_type, $p_right)) {
         // Gestion timeout session
         if (!isset ($_SESSION["glpiID"])) {
            Html::redirect($CFG_GLPI["root_doc"] . "/index.php");
            exit ();
         }
         Html::displayRightError();
      }
   }



   /**
    * Get right
    *
    * @param $p_type Right type ('wol', 'agents'...)
    * @param $p_profiles_id Profile id
    *
    * @return value right "NULL", "r" or "w"
    **/
   static function getRightDB($p_type, $profiles_id='') {

      if ($profiles_id == '') {
         $profiles_id = $_SESSION['glpiactiveprofile']['id'];
      }
      $pfp = new PluginFusioninventoryProfile();
      $a_rights = $pfp->find("`profiles_id` = '".$profiles_id."'
                                   AND `type`='".$p_type."' ");
      $right = "NULL";
      foreach ($a_rights as $data) {
         $right = $data['right'];
      }
      return $right;
   }



    /**
    * Show profile form
    *
    * @param $items_id integer id of the profile
    * @param $target value url of target
    *
    * @return nothing
    **/
   function showProfileForm($items_id, $target) {

      if (!Session::haveRight("profile", "r")) {
         return FALSE;
      }

      echo "<form name='form' method='post' action=\"$target\">";
      echo "<div align='center'>";
      echo "<table class='tab_cadre_fixe'>";

      $a_modules_temp = PluginFusioninventoryModule::getAll();
      $a_module = array();
      $a_module[] = 'fusioninventory';
      foreach($a_modules_temp as $num => $data) {
         $a_module[] = $data['directory'];
      }

      foreach ($a_module as $pluginname) {
         $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($pluginname);

         if (is_callable(array($class, "profiles"))) {
            $a_profil = call_user_func(array($class, "profiles"));

            echo "<tr>";
            echo "<th colspan='4'>".__('Rights management', 'fusioninventory')." ";
            echo __('FusionInventory', 'fusioninventory')." :</th>";
            echo "</tr>";

            $i = 0;
            foreach ($a_profil as $num => $data) {
               if ($i == '0') {
                  echo "<tr class='tab_bg_1'>";
               }
               echo "<td>";
               echo $data['name']."&nbsp;:";
               echo "</td>";
               echo "<td>";
               Profile::dropdownNoneReadWrite($pluginname."-".$data['profil'],
                                              $this->getRightDB($data['profil'], $items_id),
                                              1, 1, 1);
               echo "</td>";
               $i++;
               if ($i == '2') {
                  echo "</tr>";
                  $i = 0;
               }
            }
            if ($i == '1') {
               echo "<td colspan='2'></td>";
               echo "</tr>";
            }
         }
      }

      echo "<tr>";
      echo "<th colspan='4'>";
      echo "<input type='hidden' name='profile_id' value='".$items_id."'/>";
      echo "<input type='submit' value='".__s('Save')."' class='submit' >";
      echo "</td>";
      echo "</tr>";

      echo "</table>";
      echo "</div>";

      Html::closeForm();
   }



   /**
    * Udpate profiles from Profil management
    */
   function updateProfiles($profiles) {
      foreach($profiles as $key => $value) {
         if (strstr($key, "-")) {
            $profilName = explode("-", $key);
            $a_profile = $this->find("`profiles_id`='".$profiles['profile_id']."'
                                      AND `type`='".$profilName[1]."' ");
            if (count($a_profile) > 0) {
               foreach ($a_profile as $data) {
                  $this->updateProfile($data['id'],
                                       $data['type'],
                                       $value,
                                       $data['profiles_id']);
               }
            } else {
               $this->addProfile($profilName[1],
                                 $value,
                                 $profiles['profile_id']);
            }
         }
      }
   }
}

?>
