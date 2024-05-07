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
use SoapFault;

/**
 * WSRConstanciaInscripcion
 * 
 * Clase para conectar con el servicio ws_sr_constancia_inscripcion
 * 
 * (WebServices de Consulta a Padrón Constancia de Inscripción).
 * 
 * @since 1.0.0_beta
 * @author Javier Rodriguez
 * 
 * @see https://www.afip.gob.ar/ws/WSCI/manual-ws-sr-ws-constancia-inscripcion.pdf
 * 
 */
class WSRConstanciaInscripcion extends AfipConnector{

    protected $wsdl_homo      = 'ws_sr_constancia_inscripcion_homo.wsdl';
    protected $url_homo       = 'https://awshomo.afip.gov.ar/sr-padron/webservices/personaServiceA5';
    protected $wsdl_prod      = 'ws_sr_constancia_inscripcion_prod.wsdl';
    protected $url_prod       = 'https://aws.afip.gov.ar/sr-padron/webservices/personaServiceA5';
    protected $service        = 'ws_sr_constancia_inscripcion';

    function __construct()
    {
        parent::__construct($this->service);

    }

    /**
     * dummy
     * 
     * El método dummy verifica el estado y la disponibilidad de los 
     * elementos principales del servicio (aplicación, autenticación y base de datos).
     * 
     * (dummy)
     * 
     * Ejemplo de uso:
	 * 		$constancia = new WSRConstanciaInscripcion();
	 * 		print_r($wsfe->dummy());
	 * 		echo $wsfe->json()->dummy();
     *
     * @return array|json
     * 
     */
    public function dummy()
    {
        $this->makeSoapClient();
        $result = $this->soapClient->dummy();
        return $this->handleOutput($result);
    }
    
    /**
     * getPersona_v2
     * 
     * Devuelve el detalle de todos los datos, correspondientes a la
     * constancia de inscripción, del contribuyente solicitado.
     * 
     * (getPersona_v2)
     * 
     * cuitRepresentada: Debe coincidir con alguna de las CUITS listadas en la sección
     * relations del token enviado. Debe ser en representación de que organismo se solicita la operación.
     * 
     * idPersona: Es la clave de la que se solicitan los datos.
     * 
     * Ejemplo de uso:
	 * 		$constancia = new WSRConstanciaInscripcion();
	 * 		print_r($wsfe->getPersona_v2(222222222,30506730038));
	 * 		echo $wsfe->json()->getPersona_v2(222222222,30506730038);
     * 
     * @param  int|string $cuitRepresentada
     * @param  int|string $idPersona
     * 
     * @return array|string json
     */    

    public function getPersona_v2($cuitRepresentada,$idPersona){
        try {
            $this->makeSoapClient();

            $array=array_merge(
                $this->getAuthArray(),
                ['cuitRepresentada' => $cuitRepresentada],
                ['idPersona' => $idPersona]
            );
            $result = $this->soapClient->getPersona_v2($array);
            // return $result;
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
        
    }
    
    /**
     * getPersonaList_v2
     * 
     * Devuelve idénticos datos que el método getPersona_v2, pero
     * para una lista de hasta 250 claves tributarias.
     * 
     * cuitRepresentada: Debe coincidir con alguna de las CUITS listadas en la sección
     * relations del token enviado. Debe ser en representación de que organismo se solicita la operación.
     * 
     * idsPersonas: Es un array con las claves de las personas que se solicitan los datos.
     * 
     * Ejemplo de uso:
     *          print_r($contancia->getPersonaList_v2(222222222,[30506730038,20221062583,2022222223]));
     *          echo $contancia->json()->getPersonaList_v2(222222222,[30506730038,20221062583,2022222223]);
     *
     * @param  int|string $cuitRepresentada
     * @param  array $idPersona
     * @return void
     */
    public function getPersonaList_v2($cuitRepresentada,$idsPersonas=[]){
        try {
            $this->makeSoapClient();

            $array=array_merge(
                $this->getAuthArray(),
                ['cuitRepresentada' => $cuitRepresentada],
                ['idPersona' => $idsPersonas]
            );
            $result = $this->soapClient->getPersonaList_v2($array);
            return $this->handleOutput($result);
        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
        
    }

     /**
     * getAuthArray
     *
     * @return array
     */
    protected function getAuthArray()
    {

            if(empty($this->ta->credentials->token)){
                new EvalAfipException("El token de acceso no pudo obtenerse");
            }

            if(empty($this->ta->credentials->sign)){
                new EvalAfipException("El sign del token de acceso no pudo obtenerse");
            }

            return array(
                'token' => $this->ta->credentials->token,
                'sign'  => $this->ta->credentials->sign,
            );


    }

}