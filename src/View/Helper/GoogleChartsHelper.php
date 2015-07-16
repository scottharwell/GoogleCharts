<?php

/*
  Copyright 2012 Scott Harwell

  Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 */

App::uses ('HtmlHelper', 'View/Helper');
App::uses ('GoogleCharts', 'GoogleCharts.Vendor');

class GoogleChartsHelper extends AppHelper
{

    /**
     * Path to Google Chart JS Library
     *
     * @var string
     */
    public $googleScriptPath = "https://www.google.com/jsapi";

    /**
     * Library Loaded
     *
     * @var used to determine if Google JS lib has been sent to HTML helper
     */
    protected $libraryLoaded = false;

    /**
     * Constructor
     *
     * 
     */
    public function __construct (View $View, $settings = array ())
    {
        parent::__construct ($View, $settings);
        $this->Html = new HtmlHelper ($View, $settings);

    }

    /**
     * Create Charts
     *
     * @param array - nested arrays of charts and data array('chart' => array($data, $keys, $Model, $chartDiv, $otherOptions))
     * @param String $variableId Allows one to set the js variable name for chart objects to allow reference for updates in other JS files
     * @return void
     */
    public function createJsChart ($chart, $variableId = null)
    {
        if (get_class ($chart) === "GoogleCharts")
        {
            $this->_setupChartJs ();
            $this->_buildChartJs ($chart, $variableId);
        }

    }

    /**
     * Setup JS Needed for Charts
     *
     * @return void
     */
    protected function _setupChartJs ()
    {
        if (!$this->libraryLoaded)
        {
            echo $this->Html->script (
                array ($this->googleScriptPath), array ('inline' => false)
            );
            $this->libraryLoaded = true;

            //JS to load
            $js = 'google.load("visualization", "1", {packages:["corechart", "gauge", "calendar", "table"]});';

            //create an array of charts to load more than one
            $js .= "var charts = new Array();";
            echo $this->Html->scriptBlock ($js, array ('inline' => false));
        }

    }

    /**
     * Builds JS for a chart
     *
     * @param Google Chart object
     * @param String $variableId Allows one to set the js variable name for chart objects to allow reference for updates in other JS files
     * @return void
     */
    protected function _buildChartJs (GoogleCharts $chart, $variableId = null)
    {
        //get Column keys to match against rows
        $columnKeys = array_keys ($chart->columns);

        //Make sure you are using jQuery
        $scriptOutput = "$(document).ready(function(){\n";
        
        //create a uuid for chart variables in case we have multiples
        $chartOptionsId = !empty($variableId) ? "options_{$variableId}" : uniqid ("options_");
        
        //encode chart options
        $options = json_encode ($chart->options);
        
        $scriptOutput .= "{$chartOptionsId} = chartOptions = {$options};\n";

        //create a uuid for chart variables in case we have multiples
        $chartDataId = !empty($variableId) ? "js_{$variableId}" : uniqid ("js_");

        $scriptOutput .= "{$chartDataId} = chartData = new google.visualization.arrayToDataTable(";
        
        $scriptOutput .= "[\n[";
        
        $keys = array_keys($chart->columns);
        $last_key = end($keys);
        foreach ($chart->columns as $key => $column)
        {
            $scriptOutput .= "'" . addslashes($column['label']) . "'";
            
            if($key !== $last_key){
                $scriptOutput .= ",";
            }
        }

        $scriptOutput .= "],\n";
        
        $keys = array_keys($chart->rows);
        $last_key = end($keys);
        foreach ($chart->rows as $key => $row)
        {
            $scriptOutput .= "[";
            
            $rKeys = array_keys($row);
            $last_row_key = end($rKeys);
            foreach ($row as $rKey => $val)
            {
                $jsVal = $val;
                if ($chart->columns[$columnKeys[$rKey]]['type'] === "string")
                {
                    $jsVal = "'{$val}'";
                }
                if ($val === null)
                {
                    $jsVal = 'null';
                }
                $scriptOutput .= $jsVal;
                
                if ($rKey !== $last_row_key)
                {
                    $scriptOutput .= ",";
                }
            }
            $scriptOutput .= "]";

            if ($key !== $last_key)
            {
                $scriptOutput .= ",\n";
            }
        }

        $scriptOutput .= "]);";

        $columns = array_values($chart->columns);
        foreach ($columns as $numeric_key => $column)
        {
        	if (isset($column['role']))
        	{
        		$scriptOutput .= "\nchartData.setColumnProperty($numeric_key, 'role', '{$column['role']}');";
        	}
        	if (isset($column['format']))
        	{
        		$scriptOutput .= "\nvar formatter = new google.visualization.NumberFormat({pattern: '{$column['format']}'});";
        		$scriptOutput .= "\nformatter.format(chartData, $numeric_key);";
        	}
        }
        
        $chartVarId = !empty($variableId) ? "chart_{$variableId}" : uniqid ("chart_");

        $scriptOutput .= "{$chartVarId} = chart = new google.visualization.{$chart->type}(document.getElementById('{$chart->div}'));";
        
        foreach ($chart->callbacks as $event => $function) {
          $scriptOutput .= "google.visualization.events.addListener({$chartVarId}, '{$event}', {$function});";
        }
        
        $scriptOutput .= "{$chartVarId}.draw({$chartDataId}, {$chartOptionsId});";

        $scriptOutput .= "});";

        $this->Html->scriptBlock ($scriptOutput, array ('inline' => false, 'safe'   => true));

    }

}
