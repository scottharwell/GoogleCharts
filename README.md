About
=====

This is an attempt to build a multipurpose CakePHP plugin to interface with the Google Charts Javascript API.

I welcome any assistance for enhancing / fixing issues with this plugin!

Requirements
------------

* CakePHP 2.0+
* PHP 5.3+

Currently, this plugin only works with 2D charts (line, bar, pie, etc.) 

Installation
------------

Clone this repository or download a copy to the CakePHP or your application's `Plugins` directory. Be sure to name the folder `GoogleCharts`.

Be sure that you load plugins in your application's bootstrap file:

    CakePlugin::loadAll();
    
Usage
-----

There are two phases to using this plugin:

1. Building the data.
2. Building the Javascript for display.

##Controller Setup & Actions##

Include the `GoogleCharts` class in your controller: `App::uses('GoogleCharts', 'GoogleCharts.Lib');`. This class will help build the data for your charts.

Also, include the GoogleChartsHelper class in your controller so we can use it in the view:  `public $helpers = array('GoogleCharts.GoogleCharts');`

The GoogleCharts class is meant to mimic the properties needed per the Google Chart API.  Each chart that you want to display on your page needs it's own instance of this class.  Once you have prepared the class with settings and data, then set for your view to pass to the View Helper.

	//Get data from model
	//Get the last 10 rounds for score graph
	$rounds = $this->Round->find(
		'all',
		array(
			'conditions' => array(
				'Round.user_id' => $this->Auth->user('id')
			),
			'order' => array('Round.event_date' => 'ASC'),
			'limit' => 10,
			'fields' => array(
				'Round.score',
				'Round.event_date'
			)
		)
	);

	//Setup data for chart
	$chart = new GoogleCharts();
	
	$chart->type("LineChart");	
		//Options array holds all options for Chart API
		$chart->options(array('title' => "Recent Scores")); 
		$chart->columns(array(
			//Each column key should correspond to a field in your data array
			'event_date' => array(
				//Tells the chart what type of data this is
				'type' => 'string',		
				//The chart label for this column			
				'label' => 'Date'
			),
			'score' => array(
				'type' => 'number',
				'label' => 'Score',
				//Optional NumberFormat pattern
				'format' => '#,###'
			)
		));
	
	//Loop through our data and creates data rows
	//Data will be added to rows based on the column keys above (event_date, score).
	//If there are missing fields in your data or the keys do not match, then this will not work.
	foreach($model as $round){
		$chart->addRow($round['Round']);
	}

	//You can also use this way to loop through data and creates data rows: 
	foreach($rounds as $row){
		//$chart->addRow(array('event_date' => $row['Model']['field1'], 'score' => $row['Model']['field2']));
		$chart->addRow(array('event_date' => $row['Round']['event_date'], 'score' => $row['Round']['score']));
	}
	
	
	//You can also manually add rows: 
	$chart->addRow(array('event_date' => '1/1/2012', 'score' => 55));
	
	//Set the chart for your view
	$this->set(compact('chart'));


##View##

1. Create a div for the chart.
	* You can use the default `chart_div` as the id or set your own.
	* If you set your own div ID (or need to for more than one chart) then update your chart object: `<?php $chart->div('div_id');?>`
2. Use the `GoogleChartsHelper` to display your chart(s).
	* `<div id="chart_div"><?php $this->GoogleCharts->createJsChart($chart);?></div>`

License
=======

Copyright 2012 Scott Harwell

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
