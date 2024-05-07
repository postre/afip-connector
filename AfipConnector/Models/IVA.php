<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * IVA
 * 
 * Detalle de alícuotas relacionadas con el comprobante que se solicita autorizar (array).
 * 
 */
class IVA extends Model{

    use \AfipConnector\Traits\ModelHelpers;
    
    private $Id;
    private $BaseImp;
    private $Importe;

    public function __construct($Id = null, $BaseImp = null, $Importe = null)
    {
           
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
     * Código de tipo de iva. Consultar método FEParamGetTiposIva
     * 
     * Wsfev1::FEParamGetTiposIva()->Id;
     * 
     * Oblilgatorio
     * 
     * @param  int (2) $Id
     * @return void
     */
    public function Id($Id){
        $this->Id=$Id;
        return $this;
    }
    
    /**
     * BaseImp
     * 
     * Base imponible para la determinación de la alícuota.
     * 
     * Obligatorio
     *
     * @param  float|double (13+2) $BaseImp
     * @return void
     */
    public function BaseImp($BaseImp){
        $this->BaseImp=$BaseImp;
        return $this;
    }  
    
    /**
     * Importe
     * 
     * Importe
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