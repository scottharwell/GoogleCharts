<?php

/*
Copyright 2012 Scott Harwell

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
*/

class GoogleChart extends Object{
/**
 * Default type of chart (from Google)
 *
 * @var string
 */
	public $type = "LineChart";

/**
 * Default columns to display
 *
 * @var string
 */
	public $columns = array('x' => array('type' => 'string', 'label' => 'x Axis'), 'y' => array('type' => 'number', 'label' => 'y Axis'));
	
/**
 * Default data rows for chart
 *
 * @var string
 */
	public $rows = array();

/**
 * Default display options
 *
 * @var string
 */
	public $options = array('width' => 400, 'height' => 300, 'title' => 'Chart', 'hAxis.minValue' => 0, 'titleTextStyle' => array('color' => 'red'));

/**
 * Default div name to place the chart
 *
 * @var string
 */
	public $div = "chart_div";
	
	public function __construct($type = null, $columns = null, $rows = null, $options = null, $div = null){
		parent::__construct();
		
		if(!empty($type)){
			$this->type = $type;
		}
		
		if(!empty($columns)){
			$this->columns = $columns;
		}
		
		if(!empty($rows)){
			$this->rows = $rows;
		}
		
		if(!empty($options)){
			$this->options = $options;
		}
		
		if(!empty($div)){
			$this->div = $div;
		}
	}

/**
 * Add a row of data to the chart
 *
 * @var string
 */	
	public function addRow($data){
		if(is_array($data)){
			$row = array(sizeof($this->columns));
			$i = 0;
			foreach($this->columns as $key => $column){
				$row[$i] = $data[$key];
				$i++;
			}
			$this->rows[] = $row;
			
			return true;
		}
		
		return false;
	}
}

?>