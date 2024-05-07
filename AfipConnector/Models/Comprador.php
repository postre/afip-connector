<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
 * Comprador
 * 
 * Detalle compradores vinculados al comprobante que se solicita autorizar (array).
 * 
 */
class Comprador extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $DocTipo;
    private $DocNro;
    private $Porcentaje;

    public function __construct($DocTipo = null,$DocNro = null,$Porcentaje = null){
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
     * DocTipo
     * 
     * Tipo de documento del comprador
     * 
     * los tipos de dócumento válidos se obtienen de 
     * Wsfev1::FEParamGetTiposDoc();
     * 
     * Obligatorio
     *
     * @param  int (2) $DocTipo
     * @return void
     */
    public function DocTipo($DocTipo){
        $this->DocTipo=$DocTipo;
        return $this;
    }
    
    /**
     * DocNro
     * 
     * Número de documento del comprador (CUIT,DNI, etc.)
     * 
     * Obligatorio
     *
     * @param  string (80) $DocNro
     * @return void
     */
    public function DocNro($DocNro){
        $this->DocNro=$DocNro;
        return $this;
    }
    
    /**
     * Porcentaje
     * 
     * Porcentaje de titularidad que tiene el comprador
     * 
     * Obligatorio
     *
     * @param  float|double (2+2) $Porcentaje
     * @return void
     */
    public function Porcentaje($Porcentaje){
        $this->Porcentaje=$Porcentaje;
        return $this;
    }

}