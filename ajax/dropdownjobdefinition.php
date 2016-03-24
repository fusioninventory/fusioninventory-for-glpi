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
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkCentralAccess();

// Make a select box
if (isset($_POST["type"]) && isset($_POST["actortype"])) {
   $rand = mt_rand();

   switch ($_POST["type"]) {
      case "user" :
         $right = 'all';
         /// TODO : review depending of itil object
         // Only steal or own ticket whit empty assign
         if ($_POST["actortype"]=='assign') {
            $right = "own_ticket";
            if (!$item->canAssign()) {
               $right = 'id';
            }
         }

         $options = array('name'        => '_itil_'.$_POST["actortype"].'[users_id]',
                          'entity'      => $_POST['entity_restrict'],
                          'right'       => $right,
                          'ldap_import' => TRUE);
         $withemail = FALSE;
         if ($CFG_GLPI["use_mailing"]) {
            $withemail = (isset($_POST["allow_email"]) ? $_POST["allow_email"] : FALSE);
            $paramscomment = array('value'       => '__VALUE__',
                                   'allow_email' => $withemail,
                                   'field'       => "_itil_".$_POST["actortype"]);
            // Fix rand value
            $options['rand']     = $rand;
            $options['toupdate'] = array('value_fieldname' => 'value',
                                         'to_update'  => "notif_user_$rand",
                                         'url'        => $CFG_GLPI["root_doc"]."/ajax/uemailUpdate.php",
                                         'moreparams' => $paramscomment);
         }
         $rand = User::dropdown($options);

         if ($CFG_GLPI["use_mailing"]==1) {
            echo "<br><span id='notif_user_$rand'>";
            if ($withemail) {
               echo __('Email followup').'&nbsp;:&nbsp;';
               $rand = Dropdown::showYesNo('_itil_'.$_POST["actortype"].'[use_notification]', 1);
               echo '<br>'.__('Email').'&nbsp;:&nbsp;';
               echo "<input type='text' size='25' name='_itil_".$_POST["actortype"]."[alternative_email]'>";
            }
            echo "</span>";
         }
         break;

      case "group" :
         $cond = ($_POST["actortype"]=='assign' ? $cond = '`is_assign`' : $cond = '`is_requester`');
         Dropdown::show('Group', array('name'      => '_itil_'.$_POST["actortype"].'[groups_id]',
                                       'entity'    => $_POST['entity_restrict'],
                                       'condition' => $cond));
         break;

      case "supplier" :
         Dropdown::show('Supplier', array('name'   => 'suppliers_id_assign',
                                          'entity' => $_POST['entity_restrict']));
         break;
   }
}

?>