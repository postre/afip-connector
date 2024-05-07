<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * CbtesAsoc
 * 
 * Detalle de los comprobantes relacionados con el comprobante que se solicita autorizar (array).
 * 
 */
class CbtesAsoc extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $Tipo;
    private $PtoVta;
    private $Nro;
    private $Cuit;
    private $CbteFch;

    public function __construct($Tipo = null, $PtoVta = null, $Nro = null, $Cuit = null, $CbteFch = null){
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
     * Tipo
     * 
     * Código de tipo de comprobante. Consultar método FEParamGetTiposCbte.
     * 
     * Obligatorio
     *
     * @param  int (3) $Tipo
     * @return void
     */
    public function Tipo($Tipo){
        $this->Tipo=$Tipo;
        return $this;
    }    
    /**
     * PtoVta
     * 
     * Punto de venta del comprobante asociado
     * 
     * Obligatorio
     *
     * @param  int (5) $PtoVta
     * @return void
     */
    public function PtoVta($PtoVta){
        $this->PtoVta=$PtoVta;
        return $this;
    }
        
    /**
     * Nro
     * 
     * Numero de comprobante asociado
     * 
     * Obligatorio
     *
     * @param  int (8) $Nro
     * @return void
     */
    public function Nro($Nro){
        $this->Nro=$Nro;
        return $this;
    }
    
    /**
     * Cuit
     * 
     * Cuit emisor del comprobante asociado
     * 
     * Opcional
     *
     * @param  string (11) $Cuit
     * @return void
     */
    public function Cuit($Cuit){
        $this->Cuit=$Cuit;
        return $this;
    }    
    /**
     * CbteFch
     * 
     * Fecha del comprobante asociado
     *
     * Opcional
     * 
     * @param  string (8) (yyyymmdd) $CbteFch
     * @return void
     */
    public function CbteFch($CbteFch){
        $this->CbteFch=$CbteFch;
        return $this;
    }

}