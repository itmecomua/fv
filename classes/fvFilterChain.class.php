<?php

class fvFilterChain {

    private $filters;

    protected function __construct($params = null) {
        foreach (fvSite::$fvConfig->get("filters") as $filterClass) {
            if (!isset($this->filters[$filterClass])) {
           // echo  var_dump($filterClass)."<br>";
                $this->filters[$filterClass] = new $filterClass($params);
            }
        }
    }

    public function getInstance() {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    public function execute() {
    //die ('Kill Steal!');
        foreach ($this->filters as $filter) {
        //echo var_dump($filter);
          if ($filter->execute() === false)  return false;
        }
    }
}
