<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * Periodo
 * 
 * Estructura que permite soportar un rango de fechas
 * 
 */
class Periodo extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $FchDesde;
    private $FchHasta;

    
    public function __construct($FchDesde = null, $FchHasta = null){
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
     * FchDesde
     * 
     * Fecha correspondiente al inicio del periodo de los 
     * comprobantes que se quiere identiricar
     * 
     * Obligatorio
     *
     * @param  string (8) (yyyymmdd) $FchDesde
     * @return void
     */
    public function FchDesde($FchDesde){
        $this->FchDesde=$FchDesde;
        return $this;
    }
    
    /**
     * FchHasta
     * 
     * Fecha correspondiente al fin del periodo de los 
     * comprobantes que se quiere identificar
     * 
     * Obligatorio
     *
     * @param  string (8) (yyyymmdd) $FchHasta
     * @return void
     */
    public function FchHasta($FchHasta){
        $this->FchHasta=$FchHasta;
        return $this;
    }

}