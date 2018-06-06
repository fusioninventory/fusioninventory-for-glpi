<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */

require_once 'vendor/autoload.php';

class RoboFile extends Glpi\Tools\RoboFile
{
   protected $csignore = ['/vendor/', '/lib/', '/prototype.js', '/effects.js'];
   //Own plugin's robo stuff
}
