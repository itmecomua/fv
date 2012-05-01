<?php
class fvUnit
{
    public function configure( $config )
    {
        if(is_array($config))
        {
            foreach($config as $key=>$value)
                $this->$key=$value;
        }
    }
    
    public function __set( $name, $value )
    {
        $setter='set'.$name;
        if(method_exists($this,$setter))
        {
            $this->$setter($value);
        }
    }
    
}  

