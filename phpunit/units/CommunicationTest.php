<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */
if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', $_ENV['GLPI_ROOT']);
}

include_once ('../../inc/communication.class.php');
include_once ('../../inc/toolbox.class.php');
include_once (GLPI_ROOT . '/inc/toolbox.class.php');

ob_start();

class CommunicationTest extends PHPUnit_Framework_TestCase {

   private $output = '<?xml version="1.0"?>
<foo>
   <bar/>
</foo>
';


   public function testNew() {
      $communication = new PluginFusioninventoryCommunication();
      $this->assertInstanceOf(
         'PluginFusioninventoryCommunication', $communication
      );

      return $communication;
   }


   public function testGetMessage() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');
      $message = $communication->getMessage();
      $this->assertInstanceOf('SimpleXMLElement', $message);
      $this->assertXMLStringEqualsXMLString('<foo><bar/></foo>', $message->asXML());
   }


   public function testSendMessage() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');

      $this->expectOutputString($this->output);
      $communication->sendMessage();
      $headers = xdebug_get_headers();
      $this->assertContains('Content-Type: application/xml', $headers);
   }


   public function testSendMessageNoCompression() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');

      $this->expectOutputString($this->output);
      $communication->sendMessage('none');
      $headers = xdebug_get_headers();
      $this->assertContains('Content-Type: application/xml', $headers);
   }


   public function testSendMessageZlibCompression() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');

      $this->expectOutputString(gzcompress($this->output));
      $communication->sendMessage('zlib');
      $headers = xdebug_get_headers();
      $this->assertContains('Content-Type: application/x-compress-zlib', $headers);
   }


   public function testSendMessageDeflate() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');

      $this->expectOutputString(gzdeflate($this->output));
      $communication->sendMessage('deflate');
      $headers = xdebug_get_headers();
      $this->assertContains('Content-Type: application/x-compress-deflate', $headers);
   }


   public function testSendMessageGzipCompression() {
      $communication = new PluginFusioninventoryCommunication();
      $communication->setMessage('<foo><bar/></foo>');

      $this->expectOutputString(gzencode($this->output));
      $communication->sendMessage('gzip');
      $headers = xdebug_get_headers();
      $this->assertContains('Content-Type: application/x-compress-gzip', $headers);
   }


}
