<?php

/*
  Copyright 2012 Scott Harwell

  Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
 */

class GoogleCharts extends Object
{

    /**
     * Default type of chart (from Google)
     *
     * @var string
     */
    private $type = "LineChart";

    /**
     * Default columns to display
     *
     * @var string
     */
    private $columns = array (
        'x' => array (
            'type'  => 'string', 
            'label' => 'x Axis'
        ), 
        'y'     => array (
            'type'  => 'number', 
            'label' => 'y Axis'
        )
    );

    /**
     * Default data rows for chart
     *
     * @var string
     */
    private $rows = array ();

    /**
     * Default display options
     *
     * @var string
     */
    private $options = array (
        'width'          => 400, 
        'height'         => 300, 
        'title'          => 'Chart', 
        'titleTextStyle' => array (
            'color' => 'red'
        )
    );
    
    /**
     * Default callbacks
     *
     * @var  array	$callbacks	Array of event_name => function name or anonymous function
     */
    private $callbacks = array ();

    /**
     * Default div name to place the chart
     *
     * @var string
     */
    private $div = "chart_div";
    
    /*
     * Constructor
     * 
     * @param mixed $type
     * @param mixed $columns
     * @param mixed $rows
     * @param mixed $options
     * @param mixed $div
     */

    public function __construct ($type = null, $columns = null, $rows = null, $options = null, $div = null)
    {
        parent::__construct ();

        if (!empty ($type))
        {
            $this->type = $type;
        }

        if (!empty ($columns))
        {
            $this->columns = $columns;
        }

        if (!empty ($rows))
        {
            $this->rows = $rows;
        }

        if (!empty ($options))
        {
            $this->options = $options;
        }

        if (!empty ($div))
        {
            $this->div = $div;
        }

    }
    
    /*
     * Magic Call Function
     * 
     * @param String $name name of param to set
     * @param mixed $value value to set
     * @return GoogleChart $this
     */
    public function __call($name, $args)
    {
        if($name == 'options'){
            $this->{$name} = array_merge($this->{$name}, $args[0]);
        } else {
            $this->{$name} = $args[0];
        }
        return $this;
    }
    
    /*
     * Magic Get Function
     * 
     * @param String $name name of param to set
     * @return mixed
     */
    public function __get($name)
    {
        return $this->{$name};
    }

    /**
     * Add a row of data to the chart
     *
     * @var string
     */
    public function addRow ($data)
    {
        if (is_array ($data) && !empty($data))
        {
            $row = array (sizeof ($this->columns));
            $i = 0;
            foreach ($this->columns as $key => $column)
            {
                $row[$i] = $data[$key];
                $i++;
            }
            $this->rows[] = $row;

            return true;
        }

        return false;

    }

}
