<?php

namespace AfipConnector\Traits;

use AfipConnector\Exceptions\EvalAfipException;
use AfipConnector\Exceptions\EvalException;
use AfipConnector\Exceptions\WSAAException;
use Exception;
use ReflectionMethod;

trait  ModelHelpers
{

    public function toArray(){
        $vars=get_class_vars(get_class($this));
        $o=[];
        foreach($vars as $k=>$v){
            if(empty($this->excludedVars()) || !in_array($k,$this->excludedVars())){
                $o[$k]=$this->$k;
            }
            
        }
        return $o;
    }

    // protected function defineVars($class){
       

    //     foreach(func_get_args()AS $arg){
            
    //     }

    //     $reflection = new ReflectionMethod($this, '__construct');
    //     foreach($reflection->getParameters() AS $arg)
    //     {
    //         $name=$arg->getName();
    //         echo $name;
    //         echo ' : ';
    //         // echo ${$name};
    //         echo '<br>';
    //         // $name=$arg->getName();
    //         // if($$name!==null){
    //         //     $this->$name = $$name;
    //         // } 
    //     }
    // }

}