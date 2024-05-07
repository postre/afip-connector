<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * Tributos
 * 
 * Detalle de tributos relacionados con el comprobante que se solicita autorizar (array).
 * 
 */
class Tributo extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $Id;
    private $Desc;
    private $BaseImp;
    private $Alic;
    private $Importe;

    public function __construct($Id = null, $Desc = null, $BaseImp = null, $Alic = null, $Importe = null){
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
     * Código tributo según método FEParamGetTiposTributos
     * 
     * Wsfev1::FEParamGetTiposTributos()->Id;
     * 
     * Obligatorio
     *
     * @param  int (2) $Id
     * @return void
     */
    public function Id($Id){
        $this->Id=$Id;
        return $this;
    }      
    /**
     * Desc
     * 
     * Descripción del tributo según método FEParamGetTiposTributos
     * 
     * Wsfev1::FEParamGetTiposTributos()->Desc;
     * 
     * Opcional
     *
     * @param  string (80) $Desc
     * @return void
     */
    public function Desc($Desc){
        $this->Desc=$Desc;
        return $this;
    }    

    /**
     * BaseImp
     * 
     * Base imponible para la determinación del tributo
     *
     * Obligatorio
     * 
     * @param  float|double (12+2) $BaseImp
     * @return void
     */
    public function BaseImp($BaseImp){
        $this->BaseImp=$BaseImp;
        return $this;
    }  
    
    /**
     * Alic
     * 
     * Alícuota
     * 
     * Obligatorio
     *
     * @param  float|double (3+2) $Alic
     * @return void
     */
    public function Alic($Alic){
        $this->Alic=$Alic;
        return $this;
    }  
    
    /**
     * Importe
     * 
     * Importe del tributo
     * 
     * Obligatorio
     *
     * @param  float|double (13+2) $Importe
     * @return void
     */
    public function Importe($Importe){
        $this->Importe=$Importe;
        return $this;
    }  


}