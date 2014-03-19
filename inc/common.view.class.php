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
   @author    Kevin Roy
   @co-author
   @copyright Copyright (c) 2010-2014 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

class PluginFusioninventoryCommonView extends CommonDBTM {

   const MSG_INFO    = 0;
   const MSG_WARNING = 1;
   const MSG_ERROR   = 2;

   public function showList() {
      Toolbox::logDebug(get_class($this));
      Search::show(get_class($this));
   }

   /**
    * Basic display elements
    */

   public function showTextField($title, $varname) {

      echo "<label>".$title."&nbsp;:</label>";
      echo "<div class='input_wrap'>";
      Html::autocompletionTextField ($this, $varname, $this->fields['name']);
      echo "</div>";

   }

   public function showCheckboxField($title, $varname, $options = array()) {
      echo "<label>" . $title."&nbsp;:" . "</label>";
      $options['name'] = $varname;
      $options['checked'] = $this->fields[$varname];
      $options['zero_on_empty']= true;

      echo "<div class='input_wrap'>";
      Html::showCheckbox($options);
      echo "</div>";

   }

   public function showDropdownFromArray($title, $varname, $values = array()) {
      echo "<label>" . $title."&nbsp;:" . "</label>";
      echo "<div class='input_wrap'>";
      $rand = Dropdown::showFromArray(
         $varname, $values,
         array(
            'value'=>$this->fields[$varname],
            'width'=>'100%'
         )
      );
      echo "</div>";
      return $rand;
   }

   public function showDateTimeField($title, $varname, $options = array()) {

      // Get datetime value if the object is defined
      if ( $this->fields['id'] > 0 ) {
         $value = $this->fields[$varname];
      } else {
         // Else set default value to current date and time
         if (  array_key_exists('maybeempty', $options)
               and $options['maybeempty']
         ) {
            $value = "";
         } else {
            $value = date("Y-m-d H:i:s");
         }
      }
      $options['value'] = $value;

      echo "<label>".$title."&nbsp;:</label>";
      echo "<div class='input_wrap datetime'>";
      Html::showDateTimeField(
         $varname,
         $options
      );
      echo "</div>";
   }

   public function showTextArea($title, $varname) {
      echo "<label>".$title."&nbsp;:</label>";
      echo "<div class='input_wrap'>";
      echo
         "<textarea rows='4' name='".$varname."' >".
         $this->fields[$varname].
         "</textarea>";
      echo "</div>";
   }

   public function getMessage($msg,$type=self::MSG_INFO) {
      switch ($type) {
         case self::MSG_WARNING:
            $msg = __('Warning:', 'fusioninventory') . " $msg";
            $class_msg = 'warning';
            break;
         case self::MSG_ERROR:
            $msg = __('Error:', 'fusioninventory') . " $msg";
            $class_msg = 'error';
            break;
         case self::MSG_INFO:
         default:
            $class_msg = '';
            break;
      }

      return implode("\n", array(
         "<div class='box' style='margin-bottom:20px;'>",
         "<div class='box-tleft'><div class='box-tright'><div class='box-tcenter'>",
         "</div></div></div>",
         "<div class='box-mleft'><div class='box-mright'><div class='box-mcenter'>",
         "<span class='b $class_msg'>$msg</span>",
         "</div></div></div>",
         "<div class='box-bleft'><div class='box-bright'><div class='box-bcenter'>",
         "</div></div></div>",
         "</div>",
      ));
   }
}

