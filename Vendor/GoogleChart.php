<?php

class GoogleChart extends Object{
	public $type = "LineChart";
	public $columns = array('x' => array('type' => 'string', 'label' => 'x Axis', 'array_key' => 'x'), 'y' => array('type' => 'number', 'label' => 'y Axis', 'array_key' => 'y'));
	public $rows = array();
	public $options = array('width' => 400, 'height' => 300, 'title' => 'Chart');
	
	public function __construct($type = null, $columns = null, $rows = null, $options = null){
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
	}
}

?>