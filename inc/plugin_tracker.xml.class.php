<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------


if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

/// XML class
class plugin_tracker_XML {

	/**
	 * Constructor
	**/
	function XML()
	{
		// Initialize the values with DEFAULT value
		$this->element0="dataxml";
		
		$this->FilePath='';

		
		
		
		$this->IsError=0;
		$this->Type=1;
		$this->ErrorString="NO errors ;)";
		$this->SqlString="";
	}


	function DoXML($writed=array()){
		global $DB;

		$xmlclass = new plugin_tracker_XML;
		
		$xml = "<?xml version='1.0' encoding='UTF-8' ?>\n";
		$xmlclass->element = $this->element;
		$xml .= $xmlclass->writelement(0,'','',$writed);
		if (empty($this->FilePath))
			return $xml;
		else
		{
			$fp = fopen($this->FilePath,'wb');
			fputs($fp, $xml);
			fclose($fp);
		}
	}



	function writelement($level,$prec_level_name='',$sql_data='',$writed=array())
	{
		global $DB;

		$xmlclass = new plugin_tracker_XML;
		$xmlclass->element = $this->element;
		$xml = "";
		$tab = "";
		for($i=0; $i < $level;$i++){
			$tab .= "	";
		}

		if (isset($writed[$level]))
		{
			$xml .= $writed[$level];
			unset($writed[$level]);
		}
		
		foreach ($this->element[$level] AS $element=>$value)
		{
		
			if ((!empty($this->element[$level][$element]['SQL']))
				AND ($prec_level_name == $this->element[$level][$element]['element']))
			{
				$query = $this->element[$level][$element]['SQL'];
				// Detect if query has variable from precedent sql query. If yes we replace it
					if (ereg('\[', $query))
						$query = preg_replace("/\[(.*?)\]/", $sql_data[1],$query);

				$result=$DB->query($query);
				while ( $data=$DB->fetch_array($result) )
				{
					$xml .= $tab."<".$element.">\n";
					if (isset($this->element[$level][$element]['linkfield']))
						foreach($this->element[$level][$element]['linkfield'] AS $field=>$linkfield)
						{
							if ((is_numeric($data[$field])) OR (empty($data[$field])))
								$xml .= $tab."	<".$linkfield.">".$data[$field]."</".$linkfield.">\n";
							else
								$xml .= $tab."	<".$linkfield."><![CDATA[".$data[$field]."]]></".$linkfield.">\n";
						}						
					
					// Boucle pour les éléments déclaré dans les variables (fieldvalue)
					if (isset($this->element[$level][$element]['fieldvalue']))
						foreach($this->element[$level][$element]['fieldvalue'] AS $field=>$value){
							if ((is_numeric($value)) OR (empty($value)))
								$xml .= $tab."	<".$field.">".$value."</".$field.">\n";
							else
								$xml .= $tab."	<".$field."><![CDATA[".$value."]]></".$field.">\n";
						}
					
					if (!empty($this->element[($level+1)]))
						$xml .= $xmlclass->writelement($level+1,$element,$data,$writed);
					
					$xml .= $tab."</".$element.">\n";
				}
			}elseif ($prec_level_name == $this->element[$level][$element]['element']){
			
				$xml .= $tab."<".$element.">\n";
	
				// Boucle pour les éléments déclaré dans les variables (fieldvalue)
				if (isset($this->element[$level][$element]['fieldvalue']))
					foreach($this->element[$level][$element]['fieldvalue'] AS $field=>$value){
						if ((is_numeric($value)) OR (empty($value)))
							$xml .= $tab."	<".$field.">".$value."</".$field.">\n";
						else
							$xml .= $tab."	<".$field."><![CDATA[".$value."]]></".$field.">\n";
					}
				
				if (!empty($this->element[($level+1)]))
					$xml .= $xmlclass->writelement($level+1,$element,'',$writed);
				
				$xml .= $tab."</".$element.">\n";
			}
		}
		return $xml;
	}

}

?>
