<?php
// Copyright (C) 2024 Javier Rodriguez (Postre)
// 
// This file is part of AFIP-CONNECTOR.
// 
// AFIP-CONNECTOR is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// AFIP-CONNECTOR is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with AFIP-CONNECTOR.  If not, see LICENCE.txt <https://www.gnu.org/licenses/gpl-3.0.html>.

// Limitación de Responsabilidad:
// EN NINGÚN CASO, EL AUTOR O TITULAR DE LOS DERECHOS DE AUTOR SERÁ RESPONSABLE 
// ANTE NINGUNA PARTE POR DAÑOS DIRECTOS, INDIRECTOS, INCIDENTALES, ESPECIALES, 
// EJEMPLARES O CONSECUENTES (INCLUYENDO, PERO NO LIMITADO A, LA ADQUISICIÓN DE 
// BIENES O SERVICIOS SUSTITUTOS, LA PÉRDIDA DE USO, LOS DATOS O LAS UTILIDADES,
// LA INTERRUPCIÓN DEL NEGOCIO, LA PÉRDIDA DE BENEFICIOS O LA INFORMACIÓN COMERCIAL), 
// SIN IMPORTAR LA FORMA DE LA ACCIÓN, YA SEA POR CONTRATO, AGRAVIO (INCLUYENDO 
// NEGLIGENCIA) O DE OTRO MODO, QUE SURJA DE O EN RELACIÓN CON EL USO O EL RENDIMIENTO 
// DE ESTE SOFTWARE, INCLUSO SI SE HA ADVERTIDO DE LA POSIBILIDAD DE TALES DAÑOS.

namespace AfipConnector\Afip;

use AfipConnector\Exceptions\AfipException;
use AfipConnector\Exceptions\EvalAfipException;
use AfipConnector\Models\Cbte;
use Exception;
use SoapFault;

/**
 * Wsfev1
 * 
 * Clase para conectar con el servicio WSFEv1 (WebServices de factura electrónica).
 * 
 * @since 1.0.0_beta
 * @author Javier Rodriguez
 * 
 * @see https://www.afip.gob.ar/ws/documentacion/ws-factura-electronica.asp
 * @see https://www.afip.gob.ar/ws/documentacion/manuales/manual-desarrollador-ARCA-COMPG-v4-0.pdf
 * 
 */
class Wsfev1 extends AfipConnector
{

    /**
     *  TODO
     * FECAESolicitar: hacer todo el metodo y los necesarios
     * 
     * getEmittedVouchers: revisar si cuando podamos hacer una factura podemos consultarla.
     * 
     * ver en la documentracion de afip los errores de los servicios y ver como los manejamos.
     */

    protected $wsdl_homo      = 'wsfev1_homo.wsdl';
    protected $url_homo       = 'https://wswhomo.afip.gov.ar/wsfev1/service.asmx';
    protected $wsdl_prod      = 'wsfev1_prod.wsdl';
    protected $url_prod       = 'https://servicios1.afip.gov.ar/wsfev1/service.asmx';
    protected $service        = 'wsfe';


    function __construct()
    {
        parent::__construct($this->service);

    }

    /**
     * FEDummy
     * 
     * Método Dummy para verificación de funcionamiento de infraestructura
     * (FEDummy)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEDummy();
     *
     * @return array|json
     */
    public function FEDummy()
    {
        $this->makeSoapClient();

        $result = $this->soapClient->FEDummy();
        return $this->handleOutput($result->FEDummyResult);
    }
    
    /**
     * FECAESolicitar
     * 
     * Para “CAE – RG 4291” aplican los siguientes métodos: Método de autorización de comprobantes electrónicos por CAE 
     * (FECAESolicitar)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
     *      $cbte = new Cbte();
     *      $cbte->.....
	 * 		echo $wsfe->json()->FECAESolicitar($cbte);
     *
     * @param  Cbte $cbte
     * 
     * @return array|string json
     */    

    public function FECAESolicitar($cbte){
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FECAESolicitar(
                array(
                    'Auth' => $this->getAuthArray(),
                    'FeCAEReq' => $cbte->get()
                )
            );

            $this->storeCbteFiles($cbte);

            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposCbte
     * 
     * Recuperador de valores referenciales de códigos de Tipos de comprobante
     * (FEParamGetTiposCbte)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposCbte();
     *
     * @return array|string json
     */
    public function FEParamGetTiposCbte()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposCbte(
                array('Auth' => $this->getAuthArray())
            );

            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            return new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            return new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposConcepto
     * 
     * Recuperador de valores referenciales de códigos de Tipos de Conceptos
     * (FEParamGetTiposConcepto)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposConcepto();
     *
     * @return array|string json
     */
    public function FEParamGetTiposConcepto()
    {
       
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposConcepto(
                array('Auth' => $this->getAuthArray())
            );

            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }


    /**
     * FEParamGetTiposDoc
     * 
     * Recuperador de valores referenciales de códigos de Tipos de Documentos
     * (FEParamGetTiposDoc)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposDoc();
     *
     * @return array|string json
     */
    public function FEParamGetTiposDoc()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposDoc(
                array('Auth' => $this->getAuthArray())
            );

            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposIva
     * 
     * Recuperador de valores referenciales de códigos de Tipos de Alícuotas
     * (FEParamGetTiposIva)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposIva();
     *
     * @return array|string json
     */
    public function FEParamGetTiposIva()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposIva(
                array('Auth' => $this->getAuthArray())
            );

            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposMonedas
     * 
     * Recuperador de valores referenciales de códigos de Tipos de Monedas
     * (FEParamGetTiposMonedas)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposMonedas();
     *
     * @return array|string json
     */
    public function FEParamGetTiposMonedas()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposMonedas(
                array('Auth' => $this->getAuthArray())
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposOpcional
     * 
     * Recuperador de valores referenciales de códigos de Tipos de datos Opcionales
     * (FEParamGetTiposOpcional)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposOpcional();
     *
     * @return array|string json
     */
    public function FEParamGetTiposOpcional()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposOpcional(
                array('Auth' => $this->getAuthArray())
            );
           
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetTiposTributos
     * 
     * Recuperador de valores referenciales de códigos de Tipos de Tributos
     * (FEParamGetTiposTributos)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetTiposTributos();
     *
     * @return array|string json
     */
    public function FEParamGetTiposTributos()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetTiposTributos(
                array('Auth' => $this->getAuthArray())
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetPtosVenta
     * 
     * Recuperador de los puntos de venta asignados a Facturación Electrónica que soporten CAE y CAEA vía Web Services
     * (FEParamGetPtosVenta)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetPtosVenta();
     *
     * @return array|string json
     */
    public function FEParamGetPtosVenta()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetPtosVenta(
                array('Auth' => $this->getAuthArray())
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetCotizacion
     * 
     * Recuperador de cotización de moneda 
     * (FEParamGetCotizacion)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetCotizacion('011');
     * 
     * @param  string $currencyId
     * @return array|string json
     */
    public function FEParamGetCotizacion($currencyId)
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetCotizacion(
                array(
                    'Auth' => $this->getAuthArray(),
                    'MonId' => $currencyId
                )
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FECompUltimoAutorizado
     * 
     * Recuperador de ultimo valor de comprobante registrado 
     * (FECompUltimoAutorizado)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->FECompUltimoAutorizado(4,6);
     *
     * @param  int $pos
     * @param  int $voucherType
     * @return array|string json
     */
    public function FECompUltimoAutorizado($pos, $voucherType)
    {
        try {
            
            $this->makeSoapClient();
            $result = $this->soapClient->FECompUltimoAutorizado(
                array(
                    'Auth' => $this->getAuthArray(),
                    'PtoVta'     => $pos,
                    'CbteTipo'   => $voucherType
                )
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FECompConsultar
     * 
     * Método para consultar Comprobantes Emitidos y su código 
     * (FECompConsultar)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FECompConsultar(4,6,12);
     * 
     * @param  int $pos
     * @param  int $voucherType
     * @param  int $voucherNumber
     * @return array|string json
     */
    public function FECompConsultar($pos, $voucherType, $voucherNumber)
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FECompConsultar(
                array(
                    'Auth'          => $this->getAuthArray(),
                    'FeCompConsReq' => array(
                        'CbteTipo'  => $voucherType,
                        'CbteNro'   => $voucherNumber,
                        'PtoVta'    => $pos

                    )
                )
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * FEParamGetActividades
     * 
     * Método para consultar las actividades vigentes del emisor
     * (FEParamGetActividades)
     * 
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetActividades();
     *
     * @return array|string json
     */
    public function FEParamGetActividades()
    {
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetActividades(
                array('Auth' => $this->getAuthArray())
            );
            
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }
    
    /**
     * FEParamGetCondicionIvaReceptor
     * 
     * Método para consultar valores referenciales de los identificadores de la condición frente
     * al IVA del receptor (FEParamGetCondicionIvaReceptor)
     * Esta operación permite consultar los identificadores de la condicion frente al IVA del receptor,
     * su descripción y a la clase de comprobante que corresponde.
     *
     * Ejemplo de uso:
	 * 		$wsfe = new Wsfev1();
	 * 		echo $wsfe->json()->FEParamGetCondicionIvaReceptor(1); 
     * 
     * @param  integer (2) $claseCmp
     * @return array|string json
     */
    public function FEParamGetCondicionIvaReceptor($claseCmp){
        try {
            $this->makeSoapClient();
            $result = $this->soapClient->FEParamGetCondicionIvaReceptor(
                array(
                    'Auth'          => $this->getAuthArray(),
                    'ClaseCmp'      => $claseCmp
                )
            );
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }

    }


    
}
