About
=====

This is an attempt to build a multipurpose CakePHP plugin to interface with the Google Charts Javascript API.

Requirements
------------

* CakePHP 2.0+

Installation
------------

Clone this repository or download a copy to the CakePHP or your application's `Plugins` directory. Be sure to name the folder `GoogleCharts`.

Be sure that you load plugins in your application's bootstrap file:

    CakePlugin::loadAll();
    
Usage
-----

There are two phases to using this plugin:

1) Building the data.
2) Building the Javascript for display.

##Controller Setup & Actions##

Include the `GoogleChart` class in your controller: `App::uses('GoogleChart', 'GoogleChart.Vendor');`. This class will help build the data for your charts.

The GoogleChart class is meant to mimic the properties needed per the Google Chart API.  Each chart that you want to display on your page needs it's own instance of this class.  Once you have prepared the class with settings and data, then set for your view to pass to the View Helper.

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
	$chart = new GoogleChart();
	$chart->type = "LineChart"; 				//Only tested with Line and Bar charts -- Needs testing with other charts
	$chart->options['title'] = "Recent Scores"; //Options array holds all options for Chart API
	$chart->columns = array(
		'event_date' => array( 					//Each column key should correspond to a field in your data array
			'type' => 'string',					//Tells the chart what type of data this is
			'label' => 'Date'					//The chart label for this column
		),
		'score' => array(
			'type' => 'number',
			'label' => 'Score'
		)
	);
	
	//Loop through our data and creates data rows
	//Data will be added to rows based on the column keys above.
	//If there are missing fields in your data or the keys do not match, then this will not work.
	foreach($rounds as $round){
		$chart->addRow($round['Round']);
	}
	
	//Set the chart for your view
	$this->set(compact('chart'));


##View##

1) Create a div for the chart.
	* You can use the default `chart_div` as the id or set your own.
	* If you set your own div ID (or need to for more than one chart) then update your chart object: `<?php $chart->div = 'div_id';?>`
2) Use the `GoogleChartHelper` to display your chart(s).
	* `<div id="chart_div"><?php $this->GoogleChart->createJsChart($chart);?></div>`

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