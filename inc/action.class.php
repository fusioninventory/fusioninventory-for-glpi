<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployAction extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][0];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   static function getForOrder($orders_id) {
      $results = getAllDatasFromTable('glpi_plugin_fusinvdeploy_actions',
                                      "`plugin_fusinvdeploy_orders_id`='$orders_id'");
      $actions = array();

      foreach ($results as $result) {
         $actions = call_user_func(array($result['itemtype'], 'getActions'),$result['items_id'],
                                   $actions);
      }
      return $actions;
   }

   public function getAllDatas($params) {
      global $DB, $LANG;

      $package_id = $params['package_id'];
      $render = $params['render'];

      $render_type   = PluginFusinvdeployOrder::getRender($render);
      $order_id      = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

      $sql = " SELECT id as {$render}id,
                      itemtype as {$render}itemtype,
                      items_id as {$render}items_id,
                      ranking as {$render}ranking
               FROM `glpi_plugin_fusinvdeploy_actions`
               WHERE `plugin_fusinvdeploy_orders_id` = '$order_id'";

      $qry  = $DB->query($sql);

      $nb   = $DB->numrows($qry);
      $res  = array();
      while($row = $DB->fetch_array($qry)) {

         $itemtype = $row[$render.'itemtype'];
         $action   = new $itemtype();
         $action->getFromDB($row[$render.'items_id']);

         if($action instanceof PluginFusinvdeployAction_Command) {
            $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
            $row[$render.'value'].= $action->getField('exec');

            $row[$render.'exec'] = $action->getField('exec');

         } else if($action instanceof PluginFusinvdeployAction_Move) {
            $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][16]." : </b> ";
            $row[$render.'value'].= $action->getField('from');
            $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['label'][17]." : </b> ";
            $row[$render.'value'].= $action->getField('to');

            $row[$render.'from'] = $action->getField('from');
            $row[$render.'to']   = $action->getField('to');

         }  else if($action instanceof PluginFusinvdeployAction_Delete) {
            $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
            $row[$render.'value'].= $action->getField('path');
            $row[$render.'path']  = $action->getField('path');

         }  else if($action instanceof PluginFusinvdeployAction_Message) {
            $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][1].
               " : </b> ";
            $row[$render.'value'].= $action->getField('name');
            $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][2].
               " : </b> ";
            $row[$render.'value'].= $action->getField('message');
            $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][3].
               " : </b> ";
            $row[$render.'value'].= $action->getField('type');

            $row[$render.'messagename']   = $action->getField('name');
            $row[$render.'messagevalue']  = $action->getField('message');
            $row[$render.'messagetype']   = $action->getField('type');
         }

         $res[$render.'actions'][] = $row;
      }

      return json_encode($res);
   }

   public function getData($params) {
      global $DB, $LANG;

      $id = $params['id'];
      $render = $params['render'];

      $sql = " SELECT id as {$render}id,
                      itemtype as {$render}itemtype,
                      items_id as {$render}items_id,
                      ranking as {$render}ranking
               FROM `glpi_plugin_fusinvdeploy_actions`
               WHERE `id` = '$id'";
      $qry  = $DB->query($sql);

      $nb   = $DB->numrows($qry);
      if ($nb == 0) return false;

      $row = $DB->fetch_array($qry);

      $itemtype = $row[$render.'itemtype'];
      $action   = new $itemtype();
      $action->getFromDB($row[$render.'items_id']);

      if($action instanceof PluginFusinvdeployAction_Command) {
         $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
         $row[$render.'value'].= $action->getField('exec');

         $row[$render.'exec'] = $action->getField('exec');

      } else if($action instanceof PluginFusinvdeployAction_Move) {
         $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][16]." : </b> ";
         $row[$render.'value'].= $action->getField('from');
         $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['label'][17]." : </b> ";
         $row[$render.'value'].= $action->getField('to');

         $row[$render.'from'] = $action->getField('from');
         $row[$render.'to']   = $action->getField('to');

      }  else if($action instanceof PluginFusinvdeployAction_Delete) {
         $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['label'][2]." : </b> ";
         $row[$render.'value'].= $action->getField('path');
         $row[$render.'path']  = $action->getField('path');

      }  else if($action instanceof PluginFusinvdeployAction_Message) {
         $row[$render.'value'] = "<b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][1].
            " : </b> ";
         $row[$render.'value'].= $action->getField('name');
         $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][2].
            " : </b> ";
         $row[$render.'value'].= $action->getField('message');
         $row[$render.'value'].= " <b>".$LANG['plugin_fusinvdeploy']['form']['action_message'][3].
            " : </b> ";
         $row[$render.'value'].= $action->getField('type');

         $row[$render.'messagename']   = $action->getField('name');
         $row[$render.'messagevalue']  = $action->getField('message');
         $row[$render.'messagetype']   = $action->getField('type');
      }

      return json_encode(array('data' => $row));
   }

   public function createData($params) {
      $package_id = $params['package_id'];
      $render = $params['render'];

      $render_type   = PluginFusinvdeployOrder::getRender($render);
      $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);

      foreach($params as $param_key => $param_value) {
         $new_key         = preg_replace('#^'.$render.'#','',$param_key);
         $params[$new_key] = $param_value;
      }

      // Adding Sub-ACTION
      $itemtype = new $params['itemtype']();

      if($itemtype instanceof PluginFusinvdeployAction_Command) {
         $data = array( 'exec'   => $params['exec']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Move){
         $data = array( 'from'   => $params['from'],
                        'to'     => $params['to']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
         $data = array( 'path'   => $params['path']);

      } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
         $data = array( 'name'      => $params['messagename'],
                        'message'   => $params['messagevalue'],
                        'type'      => $params['messagetype']);
      }

      $items_id = $itemtype->add($data);

      // Adding ACTION
      $data   = array('itemtype'                       => $params['itemtype'],
                      'items_id'                       => $items_id,
                      'plugin_fusinvdeploy_orders_id'  => $order_id);

      $newId = $this->add($data);


      //get all new data
      $res_newData = $this->getData(array('id' => $newId, 'render' => $render));

      $res = "{success:true, newId:$newId, rec:$res_newData}";

      return $res;
   }

   public function saveData($params) {
      global $DB, $LANG;

      $package_id = $params['package_id'];
      $render = $params['render'];

      $res = "";

      foreach($params as $param_key => $param_value) {
         $new_key         = preg_replace('#^'.$render.'#','',$param_key);
         $params[$new_key] = $param_value;
      }

      $render_type   = PluginFusinvdeployOrder::getRender($render);
      $order_id = PluginFusinvdeployOrder::getIdForPackage($package_id,$render_type);


      if (isset ($params["id"]) && !$params['id']) {
         $res = $this->createData($params);
      } else if (isset ($params["id"]) && $params['id']) {

         $action = new PluginFusinvdeployAction();
         $action->getFromDB($params['id']);

         $items_id = $action->getField('items_id');
         $itemtype = $action->getField('itemtype');

         if ($params['itemtype'] == $itemtype) {
            $itemtype = new $params['itemtype']();
            $itemtype->getFromDB($items_id);

            if($itemtype instanceof PluginFusinvdeployAction_Command) {
               $data = array( 'exec'   => $params['exec']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Move){
               $data = array( 'from'   => $params['from'],
                              'to'     => $params['to']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
               $data = array( 'path'   => $params['path']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
               $data = array( 'name'      => $params['messagename'],
                              'message'   => $params['messagevalue'],
                              'type'      => $params['messagetype']);
            }

            $data['id'] = $items_id;
            $itemtype->update($data);
            $res = "{success:true}";
         } else {
            $itemtype = new $itemtype;
            $itemtype->delete(array('id'=>$items_id));

            $itemtype = new $params['itemtype']();

            if($itemtype instanceof PluginFusinvdeployAction_Command) {
               $data = array( 'exec'   => $params['exec']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Move){
               $data = array( 'from'   => $params['from'],
                              'to'     => $params['to']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Delete) {
               $data = array( 'path'   => $params['path']);

            } else if($itemtype instanceof PluginFusinvdeployAction_Message) {
               $data = array( 'name'      => $params['messagename'],
                              'message'   => $params['messagevalue'],
                              'type'      => $params['messagetype']);
            }

            $items_id = $itemtype->add($data);

            $data   = array('id'                         =>  $params["id"],
                         'itemtype'                       => $params['itemtype'],
                         'items_id'                       => $items_id,
                         'plugin_fusinvdeploy_orders_id'  => $order_id);
            $action->update($data);

            $res = "{success:true}";
         }
      }

      return $res;
   }
}

?>
