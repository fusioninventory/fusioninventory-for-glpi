<?php

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
