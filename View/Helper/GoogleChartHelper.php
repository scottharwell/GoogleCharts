<?php

App::uses('HtmlHelper', 'View/Helper');

class GoogleChartHelper extends AppHelper {
/**
 * Path to Google Chart JS Library
 *
 * @var string
 */
	public $googleScriptPath = "https://www.google.com/jsapi";
	
/**
 * Default Google Chart options
 *
 * @var array('width' => (int), 'height' => (int), 'title' => (string))
 */	
	public $options = array(
		'width' => 400,
		'height' => 300,
		'title' => 'Data Chart'
	);

	
/**
 * Library Loaded
 *
 * @var used to determine if Google JS lib has been sent to HTML helper
 */	
	private $libraryLoaded = false;
	
/**
 * JS Output
 *
 * @var variable to hold the JS that will be printed in a script tag
 */	
	private $scriptOutput = "";
	
/**
 * Create Charts
 *
 * @param array - nested arrays of charts and data array('chart' => array($data, $keys, $Model, $chartDiv, $otherOptions))
 */
	public function createCharts($charts){
		if(is_array($charts)){
			$this->setupChartJs();
			
			foreach($charts as $chart){
				//$this->buildChartJs($chart['data'], )
			}
		}
	}

/**
 * Setup JS Needed for Charts
 *
 * @param array - typically model structured data
 * @param array - Array with keys for column type (string, int, etc.), column label, and a key for the data in the model array
 * @param string key of the model used in the data array
 */
	private function setupChartJs(){
		echo $this->Html->script(
			array($googleScriptPath),
			array('inline' => false)
		);
	}

/**
 * Builds JS for a chart
 *
 * @param array - typically model structured data
 * @param array - Array with keys for column type (string, int, etc.), column label, and a key for the data in the model array
 * @param string key of the model used in the data array
 * @param array other options from the Google Chart library to include
 */
	private function buildChartJs($data, $keys, $Model, $chartDiv, $otherOptions = array()){
		$scriptOptions = json_encode(array_merge($this->options, $otherOptions));
		
		$scriptOutput = "";
		
		if(is_array($data) && is_array($keys)){
			foreach($keys as $column){
				$scriptOutput .= "data.addColumn('{$column['type']}', '{$column['label']}');\n";
			}
			
			$scriptOutput .= "data.addRows([\n";
			
			foreach($rows as $row){
				$scriptOutput .= "[";
				foreach($keys as $key => $col){
					$val = $row[$col['data_array_key']];
					$scriptOutput .=  (is_string($val) ? "{$val}" : $val) . ",";
				}
				$scriptOutput .= "]";
			}
			
			$scriptOutput .= "]);\n";
			
			
		}
		
		return $scriptOutput;
	}
}
