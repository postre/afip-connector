<?php 
namespace AfipConnector\Models;

use ReflectionMethod;

/**
     * Opcionales: Campos auxiliares (array). Adicionales por R.G.
     * Los datos opcionales sólo deberán ser incluidos si el emisor pertenece al conjunto de emisores
     * habilitados a informar opcionales. En ese caso podrá incluir el o los datos opcionales que
     * correspondan, especificando el identificador de dato opcional de acuerdo a la situación del emisor.
     * El listado de tipos de datos opcionales se puede consultar con el método
     * 
     * FEParamGetTiposOpcional.
     * 
     * Ejemplo: si el emisor está incluido en el “Régimen de Promoción Industrial”, deberá incluir un array
     * de opcionales con un registro como el sig.
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>2</ar:Id>
     *          <ar:Valor>12345678</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar corresponde a Establecimientos de educación pública de
     * gestión privada según Resolución General N° 3.368 deberá incluir un array de opcionales con
     * registros como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *           <ar:Id>10</ar:Id>
     *            <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     *      <ar:Opcional>
     *           <ar:Id>1011</ar:Id>
     *           <ar:Valor>80</ar:Valor>
     *      </ar:Opcional>
     *      <ar:Opcional>
     *           <ar:Id>1012</ar:Id>
     *           <ar:Valor>30000000007</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar corresponde a Operaciones económicas vinculadas
     * con bienes inmuebles según RG N° 2.820 deberá incluir un array de opcionales con un
     * registro como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>11</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar corresponde a Locación temporaria de inmuebles
     * con fines turísticos según RG N° 3.687 deberá incluir un array de opcionales con un registro
     * como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>12</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar corresponde a Representantes de Modelos según
     * RG N° 2.863 deberá incluir un array de opcionales con un registro como el siguiente
     * ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>13</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * Si el comprobante que intenta autorizar corresponde a Agencias de publicidad según RG N°
     * 2.863 deberá incluir un array de opcionales con un registro como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>14</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar corresponde a Personas físicas que desarrollen
     * actividad de modelaje según RG N° 2.863 deberá incluir un array de opcionales con un
     * registro como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>15</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar es del tipo B o C con locación de inmuebles destino
     * "casa-habitación" facturación directa según RG N°
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *         <ar:Id>17</ar:Id>
     *         <ar:Valor>2</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar es del tipo B o C con locación de inmuebles destino
     * "casa-habitación" facturación a través de intermediario según RG N° 4004-E deberá incluir
     * un array de opcionales con un registro como el siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>17</ar:Id>
     *          <ar:Valor>1</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     * Si el comprobante que intenta autorizar es del tipo B o C con locación de inmuebles destino
     * "casa-habitación" con facturación directa con cotitulares o indirecta con los datos de el/los
     * titular/es según RG N° 4004-E deberá incluir opcionales con al menos 2 registros como el
     * siguiente ejemplo:
     * 
     * <ar:Opcionales>
     *      <ar:Opcional>
     *          <ar:Id>1801</ar:Id>
     *          <ar:Valor>30000000007</ar:Valor>
     *      </ar:Opcional>
     *          <ar:Opcional>
     *          <ar:Id>1802</ar:Id>
     *          <ar:Valor>DENOMINACION EJEMPLO</ar:Valor>
     *      </ar:Opcional>
     * </ar:Opcionales>
     * 
     */

/**
 * Opcionales
 * 
 * Campos auxiliares (array). Adicionales por R.G.
 * 
 */
class Opcional extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $Id;
    private $Valor;

    public function __construct($Id = null,$Valor = null){
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
      * Código de Opcional, consultar método FEParamGetTiposOpcional
      *
      * Wsfev1::FEParamGetTiposOpcional()->Id;
      *
      * Obligatorio
      *
      * @param  string (4) $Id
      * @return void
      */
     public function Id($Id){
        $this->Id=$Id;
        return $this;
    }

    /**
      * Valor
      *
      * Valor
      *
      * Obligatorio
      *
      * @param  string (250) $Valor
      * @return void
      */
     public function Valor($Valor){
        $this->Valor=$Valor;
        return $this;
    }

}