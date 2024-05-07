<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * Actividad
 * 
 * Detalle de la actividad relacionada con las actividades (array) que se indican en el comprobante a autorizar.
 * 
 */
class Actividad extends Model{

    use \AfipConnector\Traits\ModelHelpers;
    
    private $Id;

    public function __construct($Id = null){
        $reflection = new ReflectionMethod($this, '__construct');
        foreach($reflection->getParameters() AS $arg)
        {
            $name=$arg->getName();
            if($$name!==null){
                $this->$name = ${$name};
            } 
        }
    }
    
    /**
     * Id
     * 
     * Código actividad según método FEParamGetActividades
     * 
     * Wsfev1::FEParamGetActividades()->Id;
     * 
     * Obligatorio
     *
     * @param  int (6) $Id
     * @return void
     */
    public function Id($Id){
        $this->Id=$Id;
        return $this;
    }

}