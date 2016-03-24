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
   @author    Kevin Roy
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

   public $base_urls = array();

   public function __construct() {
      global $CFG_GLPI;
      parent::__construct();
      $this->base_urls = array(
         'fi.base' => $CFG_GLPI['root_doc'] . "/plugins/fusioninventory",
         'fi.ajax' => $CFG_GLPI['root_doc'] . "/plugins/fusioninventory/ajax",
         'fi.front' => $CFG_GLPI['root_doc'] . "/plugins/fusioninventory/front",
         'fi.pics' => $CFG_GLPI['root_doc'] . "/plugins/fusioninventory/pics",
         'glpi.pics' => $CFG_GLPI['root_doc'] . "/pics",
      );
   }

   /**
    * Get a specific url root by type name
    *
    * @param string $name The type of url requested (can be used for ajax call or pictures location)
    *
    * @return string Returns the requested url if found else returns empty string and trigger some
    * error message
    */
   function getBaseUrlFor($name) {
      if ( array_key_exists($name, $this->base_urls) ) {
         return $this->base_urls[$name];
      }
      trigger_error(
         "The requested url type '$name' doesn't exists. ".
         "Maybe the developper have forgotten to register it in the 'base_urls' variable.");
      return "";
   }

   public function showList() {
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

   /**
    * Basic display elements
    */

   public function showIntegerField($title, $varname, $options = array()) {

      echo "<label>".$title."&nbsp;:</label>";
      echo "<div class='input_wrap'>";
      Dropdown::showNumber($varname, $options);
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

   public function showDropdownForItemtype($title, $itemtype, $options=array()) {
      echo "<label>" . $title."&nbsp;:" . "</label>";
      echo "<div class='input_wrap'>";
      $dropdown_options = array_merge(
         array(
            'width'=>'90%',
            'display'=>true,
         ),
         $options
      );
      $rand = Dropdown::show($itemtype, $dropdown_options);
      echo "</div>";
      return $rand;
   }

   public function showDropdownFromArray($title, $varname, $values = array(), $options=array()) {
      echo "<label>" . $title."&nbsp;:" . "</label>";
      echo "<div class='input_wrap'>";
      if (!isset($options['width'])) {
         $options['width'] = '100%';
      }

      if (!is_null($varname)) {
         $options['value'] = $this->fields[$varname];
      }
      $rand = Dropdown::showFromArray(
         $varname, $values,
         $options
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
         "<textarea class='expanding' name='".$varname."' >".
         $this->fields[$varname].
         "</textarea>";
      echo "</div>";

      echo implode("\n", array(
         "<script type='text/javascript'>",
         "  $('.expanding').expanding();",
         "</script>"
      ));
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

