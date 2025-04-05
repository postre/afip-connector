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
use SimpleXMLElement;
use SoapClient;
use SoapFault;

/**
 * WSAA
 * 
 * Clase para conectar con el servicio WSAA (Webservice de Autenticación y Autorización).
 * 
 * @since 1.0.0_beta
 * @author Javier Rodriguez
 * 
 * @see https://www.afip.gob.ar/ws/documentacion/wsaa.asp
 * @see https://www.afip.gob.ar/ws/WSAA/WSAAmanualDev.pdf
 * 
 */
class WSAA extends AfipConnector
{

    protected $wsdl_homo      = 'wsaa_homo.wsdl';
    protected $url_homo       = 'https://wsaahomo.afip.gov.ar/ws/services/LoginCms';
    protected $wsdl_prod      = 'wsaa_prod.wsdl';
    protected $url_prod       = 'https://wsaa.afip.gov.ar/ws/services/LoginCms';
    

    
    private function CreateTRA(){
            $TRA = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>' .
            '<loginTicketRequest version="1.0">'.
            '</loginTicketRequest>');
            $TRA->addChild('header');
            $TRA->header->addChild('uniqueId',date('U'));
            $TRA->header->addChild('generationTime',date('c',date('U')-60));
            $TRA->header->addChild('expirationTime',date('c',date('U')+60));
            $TRA->addChild('service',$this->service);
            $TRA->asXML($this->tra);
    }

    private function SignTRA(){
        $STATUS=openssl_pkcs7_sign(
            $this->tra, 
            $this->tra_tmp, 
            "file://".$this->cert,
            array("file://".$this->key, $this->key_passphrase),
            array(),
            !PKCS7_DETACHED
            );

        if (!$STATUS) {
            throw new AfipException("ERROR generating PKCS#7 signature");
        }
        $inf=fopen($this->tra_tmp, "r");
        $i=0;
        $CMS="";
        while (!feof($inf)) { 
            $buffer=fgets($inf);
            if ( $i++ >= 4 ) {$CMS.=$buffer;}
        }
        fclose($inf);
        unlink($this->tra_tmp);
        $this->cms=$CMS;
    }

    private function CallWSAA(){
        try{
            
            $this->soapClient=new SoapClient($this->wsdl, array(
                    'soap_version'   => $this->soap_version,
                    'location'       => $this->url,
                    'trace'          => 1,
                    'exceptions'     => 0
                    )); 
            $results=$this->soapClient->loginCms(array('in0'=>$this->cms));

            $this->storeLoginFiles();
            
            if (is_soap_fault($results)){
                throw new AfipException($results->faultstring,$results->faultcode);
            }
            if (!file_put_contents($this->ta_file, $results->loginCmsReturn)) {
                throw new AfipException('No se pudo crear el archivo TA.xml (Ticket de acceso)');
            }
            $this->ta=simplexml_load_file($this->ta_file);

        } catch (SoapFault $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        } catch (AfipException $e) {
            new EvalAfipException($e->getMessage(), $e->getCode());
        }
    }


    public function get(){
        try{

            $existsValidTa=$this->existsValidTA();
            if($existsValidTa!==false){
                return $existsValidTa;
            }
            $this->validateConditions();
            $this->CreateTRA();
            $this->SignTRA();
            $this->CallWSAA();
            return $this->ta;
        }catch(AfipException $e){
            throw $e;
        }
    }

   
}
