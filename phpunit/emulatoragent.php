<?php

class emulatorAgent {
   var $socket=NULL;
   var $client=NULL;
   var $server_ip="127.0.0.1";
   var $server_urlpath = "/plugins/fusioninventory/front/plugin_fusioninventory.communication.php";


   function Start($adresse,$port, $function_prolog) {
      echo "Agent running in daemon\n";
      $this->clients=array();
      //Création de la socket
      $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
      //on lie la ressource sur laquelle le serveur va écouter
      @socket_bind($this->socket, $adresse, $port) or die("\nPort déja utilise");
      //On prépare l'écoute
      socket_listen($this->socket);

      //Boucle infinie, car le serveur ne doit s'arrêter que si on lui demande
      while (TRUE) {
         //Le code se bloque jusqu'à ce qu'une nouvelle connexion client est établie
         $this->client = socket_accept($this->socket);

         //Cette méthode lit les données reçues par un client et les redistribue
         $reception = socket_read($this->client, 255);

         if (strstr($reception, "GET /now")) {
            call_user_func($function_prolog);
         }
         echo "======== received ==========\n";
         print_r($reception);


      }
   }


   function sendProlog($input) {
      $input = gzcompress($input);

      $fp = fsockopen($this->server_ip, 80, $errno, $errstr, 30);
      if (!$fp) {
          echo "$errstr ($errno)\n";
      } else {
         $out = "POST ".$this->server_urlpath." HTTP/1.1\r\n";
         $out .= "Host: ".$this->server_ip." \r\n";
         $out .= "Content-Length: ".strlen($input)."\r\n";
         $out .= "Connection: close\r\n\r\n";
         fputs($fp, $out.$input);

         $zipped = "";
         while (!feof($fp)) {
            $line = fgets($fp, 4096);
            $zipped .= $line;
            if ($line == "\r\n") {
               $zipped = "";
            }
         }

         fclose($fp);
         Toolbox::logInFile("RETSERV", $zipped."\n");
         if (!gzuncompress($zipped)) {
            echo $zipped;
         }
         return gzuncompress($zipped);
      }
   }

}

?>