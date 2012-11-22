<?php
function format_permissions($perms) {
   if (($perms & 0xC000) == 0xC000) {
      // Socket
      $return = 's';
   } elseif (($perms & 0xA000) == 0xA000) {
      // Symbolic Link
      $return = 'l';
   } elseif (($perms & 0x8000) == 0x8000) {
      // Regular
      $return = '-';
   } elseif (($perms & 0x6000) == 0x6000) {
      // Block special
      $return = 'b';
   } elseif (($perms & 0x4000) == 0x4000) {
      // Directory
      $return = 'd';
   } elseif (($perms & 0x2000) == 0x2000) {
      // Character special
      $return = 'c';
   } elseif (($perms & 0x1000) == 0x1000) {
      // FIFO pipe
      $return = 'p';
   } else {
      // Unknown
      $return = 'u';
   }

   // Owner
   $return .= (($perms & 0x0100) ? 'r' : '-');
   $return .= (($perms & 0x0080) ? 'w' : '-');
   $return .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x' ) : (($perms & 0x0800) ? 'S' : '-'));

   // Group
   $return .= (($perms & 0x0020) ? 'r' : '-');
   $return .= (($perms & 0x0010) ? 'w' : '-');
   $return .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x' ) : (($perms & 0x0400) ? 'S' : '-'));

   // World
   $return .= (($perms & 0x0004) ? 'r' : '-');
   $return .= (($perms & 0x0002) ? 'w' : '-');
   $return .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x' ) : (($perms & 0x0200) ? 'T' : '-'));

   return $return;
}

function get_directory_contents($directory) {
   if (!($dir = opendir($directory))) {
      return false;
   }

   $data = array();

   // Get a list of all the folders and files in the directory
   while ($temp = readdir($dir)) {
      if ($temp[0] == ".") {
         continue;
      }
      if (is_dir($directory . "/" . $temp)) {
         $children = get_directory_contents($directory . "/" . $temp);
         $tmp = array(
               'id' => str_replace(DIRECTORY, "", $directory . "/" . $temp),
               'text' => $temp,
               'url' => str_replace(DIRECTORY, "", $directory . "/" . $temp)
               );
         if (count($children) > 0) {
            $tmp['children'] = $children;
         } else {
            $tmp['leaf'] = 'true';
            $tmp['children'] = array();
         }
         $data[] = $tmp;
      }
   }

   closedir($dir);

   return $data;
}

// Function to recursively remove a directory
function rmdir_r($dir) {
   if (!is_writable($dir )) {
      if (!@chmod($dir, 0777)) {
         return false;
      }
   }

   $d = dir($dir);
   while (false !== ($entry = $d->read())) {
      if ($entry == '.' || $entry == '..') {
         continue;
      }
      $entry = $dir . '/' . $entry;
      if ( is_dir($entry )) {
         if (!$this->rmdir_r($entry)) {
            return false;
         }
         continue;
      }
      if (!@unlink($entry)) {
         $d->close();
         return false;
      }
   }

   $d->close();
   rmdir($dir);
   return true;
}

// Function to convert an array to a json string
if (!function_exists('json_encode')) {
   function json_encode($input = false) {
      require_once('includes/classes/json.class.php');
      $json = new Services_JSON();
      return $json->encode($input);
   }

   function json_decode($input = false) {
      require_once('includes/classes/json.class.php');
      $json = new Services_JSON();
      return $json->decode($input);
   }
}

?>