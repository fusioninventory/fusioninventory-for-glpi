<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: MAZZONI Vincent
// Purpose of file: graph generation
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

/**
 * Class to generate graphs using pChart
 **/
class PluginTrackerGraph {
   private $pData;
   private $timeUnit;
   private $timeUnitName;
   private $field;
   private $printers;
   private $title;
   private $maxValue=0;
   private $divisionsY=0;
   private $pChartPath;
   private $fontsPath;
   private $tmpPath;

   /**
	 * Constructor
	**/
   function __construct($p_query='', $p_field='pages_total', $p_timeUnit='date',
                        $p_printers=array(), $p_title='') {
      global $LANG;
      
      $this->pChartPath = GLPI_ROOT.'/plugins/tracker/lib/pChart/';
      $this->fontsPath = GLPI_ROOT.'/plugins/tracker/lib/fonts/';
      $this->tmpPath = GLPI_ROOT.'/files/_plugins/tracker/tmp/';
      include($this->pChartPath."pData.class");
      include($this->pChartPath."pChart.class");

      $this->pData = new pData;
      $this->timeUnit = $p_timeUnit;
      $this->field = $p_field;
      $this->printers = $p_printers;
      $this->title = $p_title;
      $group='';
      switch ($this->timeUnit) {
         case 'date': // day
            $this->timeUnitName = $LANG['plugin_tracker']["prt_history"][34];
            break;
         case 'week':
            $this->timeUnitName = $LANG['plugin_tracker']["prt_history"][35];
            break;
         case 'month':
            $this->timeUnitName = $LANG['plugin_tracker']["prt_history"][36];
            break;
         case 'year':
            $this->timeUnitName = $LANG['plugin_tracker']["prt_history"][37];
            break;
      }

      if ($p_query!='') {
         $this->fromDB($p_query);
      }
   }

   /**
    * Use DB query for graph generation
    *
    *@param $p_query Query to DB
    *@return nothing
    **/
   function fromDB($p_query, $p_multi=TRUE) {
      global $DB, $LANG;

      $result  = $DB->query($p_query);
      $i=1;
      $year='';$month='';
      if ($DB->numrows($result)!=0) {
         while ($row = $DB->fetch_array($result)) {
            switch ($this->timeUnit) {
               case 'date': // day
                  $timeKey = $row["date"].'/'.$row["month"].'/'.$row["year"];
                  break;
               case 'week':
                  $timeKey = $row["week"].'/'.$row["year"];
                  break;
               case 'month':
                  $timeKey = $row["month"].'/'.$row["year"];
                  break;
               case 'year':
                  $timeKey = $row["year"];
                  break;
            }

            if (!$p_multi) {
               $series = array("pages_n_b", "pages_color");
               $i=1;
               foreach ($series as $serieVals) {
                  $serie[$i][] = $row[$serieVals];
                  $i++;
               }
               $serie[$i][] = $this->getDateSerie($row, $year, $month);
            } else {
               $i=1;
               foreach ($this->printers as $printerId=>$printerDesc) {
                  $series[$printerId]=$i;
                  $i++;
               }
               foreach ($series as $index=>$serieVals) {
                  if ($index==$row["FK_printers"]) {
                     $serie[$row["FK_printers"]][$timeKey] = $row[$this->field];
                  } else {
                     if (!isset($serie[$index][$timeKey])) {
                        $serie[$index][$timeKey] = 0;
                     }
                  }
               }
               if (!isset($serie['Date'][$timeKey])) {
                  $serie['Date'][$timeKey] = $this->getDateSerie($row, $year, $month);
               }
            }
         }
         foreach ($series as $index=>$val) {
            $serieAssoc[$this->printers[$index]] = $serie[$index];
         }
         $serieAssoc['Date'] = $serie['Date'];
         $this->maxValue = $this->getMaxValue($serieAssoc, array("Date"));
         $this->setSeries($serieAssoc, "Date");
         $this->setAxisNames("Pages", $this->timeUnitName);
         $this->render();
      }
   }

   /**
    * Set axis names
    *
    *@param $p_XAxis Name of X axis
    *@param $p_YAxis Name of Y axis
    *@return nothing
    **/
   function setAxisNames($p_XAxis, $p_YAxis) {
      $this->pData->SetYAxisName($p_XAxis);
      $this->pData->SetXAxisName("\n\n".ucfirst($p_YAxis));
   }

   /**
    * Get date serie
    *
    *@param $p_row Array of date elements (date, week, month, year)
    *@param &$p_year Previous year
    *@param &$p_month Previous month
    *@return nothing
    **/
   function getDateSerie($p_row, &$p_year, &$p_month) {
      global $LANG;
      
      switch ($this->timeUnit) {
         case 'date':
            $comment = substr($p_row["date"], 8, 2);
            $this->datePrecise($comment, $p_month, $p_row["month"]);
            $this->datePrecise($comment, $p_year, $p_row["year"]);
            break;
         case 'week':
            $comment = $p_row['week'];
            $this->datePrecise($comment, $p_year, $p_row["year"]);
            break;
         case 'month':
            $comment = $LANG['calendarM'][$p_row["month"]-1];
            $this->datePrecise($comment, $p_year, $p_row["year"]);
            break;
         default:
            $comment = $p_row[$this->timeUnit];
      }
      return $comment;
   }

   /**
    * Precise date
    *
    *@param $p_comment Comment to precise
    *@param $p_timeVar Time var
    *@param $p_timeRow Time row
    *@return nothing
    **/
   function datePrecise(&$p_comment, &$p_timeVar, $p_timeRow) {
      if ($p_timeVar!=$p_timeRow) {
         $p_timeVar = $p_timeRow;
         $p_comment .= "\n".$p_timeVar;
      }
   }

   /**
    * Set series
    *
    *@param $p_series Array of series
    *@param $p_absciseSerie Name of abscise serie
    *@return nothing
    **/
   function setSeries($p_series, $p_absciseSerie) {
      $i=1;
      $absciseSerie='';
      foreach ($p_series as $serieName=>$serie) {
         $serie = array_values($serie);
         $this->pData->AddPoint($serie, "Serie".$i);
         if ($serieName != $p_absciseSerie) {
            $this->pData->SetSerieName($serieName, "Serie".$i);
         } else {
            $absciseSerie = "Serie".$i;
         }
         $i++;
      }
      $this->pData->AddAllSeries();
      $this->pData->RemoveSerie($absciseSerie);
      $this->pData->SetAbsciseLabelSerie($absciseSerie);
   }

   /**
    * Render graph
    *
    *@return nothing
    **/
   function render($p_stack=FALSE) {
      // declare the graph
      $Test = new pChart(800,250);
      $Test->tmpFolder=$this->tmpPath;
      $fileId = time().'_'.rand(1,1000);
      $fontFile = "tahoma.ttf";
      // prepare the map
      echo '<SCRIPT TYPE="text/javascript" SRC="'.$this->pChartPath.'overlib.js"></SCRIPT>
      <SCRIPT TYPE="text/javascript" SRC="'.$this->pChartPath.'pMap.js"></SCRIPT>';
      $MapID = "map_".$fileId.".map";
      $Test->setImageMap(TRUE,$MapID);
      $Map = new pChart(800,250);
      $Map->tmpFolder=$this->tmpPath;
      $img = $this->tmpPath."img_".$fileId.".png";
      echo '<DIV ID="overDiv" STYLE="position:absolute; visibility:hidden; z-index:1000;"></DIV>';
      echo "<IMG ID='tracker_graph_$fileId' SRC='$img' WIDTH=800 HEIGHT=250 BORDER=0 OnMouseMove='tracker_graph(event);' OnMouseOut='nd();'>";
      echo '<SCRIPT>
              function tracker_graph(event) {
                 LoadImageMap("tracker_graph_'.$fileId.'","'.$Map->tmpFolder.$MapID.'");
                 getMousePosition(event);
              }
            </SCRIPT>';
      // configure the graph
      $Test->setFontProperties($this->fontsPath.$fontFile,8);
      $Test->setGraphArea(80,30,580,185); // graph size : keep place for titles on X and Y axes
      $Test->drawFilledRoundedRectangle(7,7,793,243,5,240,240,240); // background rectangle
      $Test->drawRoundedRectangle(5,5,795,245,5,230,230,230); // 3D effect
      $Test->drawGraphArea(255,255,255,TRUE);
      $Test->setFixedScale(0,$this->getMaxY($this->maxValue), $this->divisionsY); // to see values from 0
      $Test->drawScale($this->pData->GetData(),$this->pData->GetDataDescription(),
                       SCALE_ADDALL,150,150,150,TRUE,0,2,TRUE);
      $Test->drawGrid(4,TRUE,230,230,230,50);
      // Draw the 0 line
      $Test->setFontProperties($this->fontsPath.$fontFile,6);
      // Draw the bar graph
      if ($p_stack) {
         $Test->drawStackedBarGraph($this->pData->GetData(),$this->pData->GetDataDescription(),100);
      } else {
         $Test->drawBarGraph($this->pData->GetData(),$this->pData->GetDataDescription(),FALSE, 100);
      }
      // Finish the graph
      $Test->setFontProperties($this->fontsPath.$fontFile,8);
      $Test->drawLegend(590,30,$this->pData->GetDataDescription(),255,255,255); // take care of legend text size
      $Test->setFontProperties($this->fontsPath.$fontFile,10);
      $Test->drawTitle(50,22,$this->title.' (/ '.$this->timeUnitName.')',50,50,50,585);
      $Test->Render($img);
   }

   /**
    * Get max value of an array of arrays
    *
    *@param $p_arrays Array of arrays
    *@param $p_noCount Names of arrays to not count (array)
    *@return Max value
    **/
   function getMaxValue($p_arrays, $p_noCount=array()) {
      $maxValue = 0;
      foreach($p_arrays as $arrayName=>$array) {
         if (!in_array($arrayName, $p_noCount)) {
            foreach($array as $key=>$value) {
               if ($value > $maxValue) $maxValue=$value;
            }
         }
      }
      return $maxValue;
   }

   /**
    * Get max Y
    *
    *@param $p_value Value to maximize
    *@param $p_multiple=5 Multiple to respect
    *@param $p_margin=1.1 Maximize factor
    *@return nothing
    **/
   function getMaxY($p_value, $p_multiple=5, $p_margin=1.1) {
      $this->divisionsY = $p_multiple;
      $div = ($p_value*$p_margin) / $p_multiple;
      $divInt = intval($div);
      if (($div-$divInt)<=0.5) {
         $max = $divInt*$p_multiple;
      } else {
         $max = ($divInt+1)*$p_multiple;
      }
      $max = max($max,$p_multiple);
      if ($max<=$p_value AND $p_multiple>0) {
         // multiple too big compare to value, do it again with a littler one
         $max = $this->getMaxY($p_value, $p_multiple-1, $p_margin);
      }
      return $max;
   }
}
?>
