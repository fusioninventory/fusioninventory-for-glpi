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
 * This file is called by ajax function and display dropdown task job
 * definition.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkCentralAccess();

// Make a select box
$type = filter_input(INPUT_POST, "type");
$actortype = filter_input(INPUT_POST, "actortype");

if (!empty($type) && !empty($actortype)) {
   $rand = mt_rand();

   $entity_restrict = filter_input(INPUT_POST, "entity_restrict");
   switch ($type) {
      case "user" :
         $right = 'all';
         /// TODO : review depending of itil object
         // Only steal or own ticket whit empty assign
         if ($actortype == 'assign') {
            $right = "own_ticket";
            if (!$item->canAssign()) {
               $right = 'id';
            }
         }

         $options = ['name'        => '_itil_'.$actortype.'[users_id]',
                          'entity'      => $entity_restrict,
                          'right'       => $right,
                          'ldap_import' => true];
         $withemail = false;
         if ($CFG_GLPI["use_mailing"]) {
            $allow_email = filter_input(INPUT_POST, "allow_email");
            $withemail = (!empty($allow_email) ? $allow_email : false);
            $paramscomment = ['value'       => '__VALUE__',
                                   'allow_email' => $withemail,
                                   'field'       => "_itil_".$actortype];
            // Fix rand value
            $options['rand']     = $rand;
            $options['toupdate'] = ['value_fieldname' => 'value',
                                         'to_update'  => "notif_user_$rand",
                                         'url'        => $CFG_GLPI["root_doc"]."/ajax/uemailUpdate.php",
                                         'moreparams' => $paramscomment];
         }
         $rand = User::dropdown($options);

         if ($CFG_GLPI["use_mailing"]==1) {
            echo "<br><span id='notif_user_$rand'>";
            if ($withemail) {
               echo __('Email followup').'&nbsp;:&nbsp;';
               $rand = Dropdown::showYesNo('_itil_'.$actortype.'[use_notification]', 1);
               echo '<br>'.__('Email').'&nbsp;:&nbsp;';
               echo "<input type='text' size='25' name='_itil_".$actortype."[alternative_email]'>";
            }
            echo "</span>";
         }
         break;

      case "group" :
         $cond = ($actortype=='assign' ? $cond = '`is_assign`' : $cond = '`is_requester`');
         Dropdown::show('Group', ['name'      => '_itil_'.$actortype.'[groups_id]',
                                       'entity'    => $entity_restrict,
                                       'condition' => $cond]);
         break;

      case "supplier" :
         Dropdown::show('Supplier', ['name'   => 'suppliers_id_assign',
                                          'entity' => $entity_restrict]);
         break;
   }
}

