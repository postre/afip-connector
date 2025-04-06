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
use Exception;
use SoapClient;
use SoapFault;

/**
 * AfipConnector
 * 
 * Librería para conectarse con los servicios de Facturación electrónica de AFIP
 * 
 * @version 1.0.0_beta
 * @author Javier Rodriguez
 * @package afip-connector
 * 
 * @see https://www.afip.gob.ar/ws/documentacion/arquitectura-general.asp
 * @see https://www.afip.gob.ar/ws/documentacion/certificados.asp
 * @see https://www.afip.gob.ar/ws/documentacion/wsaa.asp
 * @see https://www.afip.gob.ar/ws/documentacion/ws-factura-electronica.asp
 * @see https://www.afip.gob.ar/ws/documentacion/catalogo.asp
 * 
 */
class AfipConnector{

    use \AfipConnector\Traits\AutoConfig;
    use \AfipConnector\Traits\HandleOutput;
    use \AfipConnector\Traits\HandleStoreFiles;


    protected $soap_version   = 'SOAP_1_1';
    protected $wsdl           = '';
    protected $url            = '';
    protected $wsdl_homo      = null;
    protected $url_homo       = null;
    protected $wsdl_prod      = null;
    protected $url_prod       = null;
    protected $service        = null;
    protected $tra;  
    protected $tra_tmp;  
    protected $ta;  
    protected $ta_file;  
    protected $cms            = '';
    protected $cert           = '';
    protected $key            = '';
    protected $cert_prod      = 'prod.crt';
    protected $key_prod       = 'prod.key';
    protected $cert_homo      = 'homo.crt';
    protected $key_homo       = 'homo.key';
    protected $key_passphrase = '';

    protected $cert_path      = '';
    protected $wsdl_path      = '';
    protected $wsaa_path      = '';
    protected $tra_path       = '';  
    protected $ta_file_path   = '';
    protected $cbte_path      = '';
    protected $env;
    protected $soapClient; 
    protected $json=false;

    /**
     * __construct
     *
     * @param  strinf nombre del servicio de afip
     * @return void
     */
    function __construct($service){
        try{
            ini_set("soap.wsdl_cache_enabled", "0");  
            $this->setConfigs();
            $this->setService($service);
        }catch(Exception $e){
            throw new Exception($e);
        }
    }

    /**
     * soapVersion
     * define la versilón de SOAP que vamos a usar por defecto es SOAP_1_1
     * 
     * @param  string $version
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion
     */
    public function soapVersion($version)
    {
        $this->soap_version = $version;
        return $this;
    }

    /**
     * cert
     * define el nombre del archivo para el certificado
     *
     * @param  string $cert
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion
     */
    public function cert($cert){
        $this->cert = $cert;
        return $this;
    }
    
    /**
     * key
     * define el nombe del archivo para la clave privada
     *
     * @param  mixed $key
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion
     */
    public function key($key){
        $this->key = $key;
        return $this;
    }
    
    /**
     * keyPassphrase
     * define la key_passphrase para el archivo key, en caso de que asi lo hayas creado
     *
     * @param  string $key_passphrase
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion
     */
    public function keyPassphrase($key_passphrase){
        $this->key_passphrase = $key_passphrase;
        return $this;
    }
    
    /**
     * existsValidTA
     * verifica si hay un archivo TA (Ticket de acceso para el servicio) 
     *
     * @return false
     * @return TA 
     */
    protected function existsValidTA(){

        if(!file_exists($this->ta_file)){
            return false;
        }
        $ta=simplexml_load_file($this->ta_file);
        $expiration=strtotime($ta->header->expirationTime);
        if($expiration<=time()){
            return false;
        }

        return $ta;
    }

    protected function validateConditions(){
        if (!file_exists($this->cert)) {throw new Exception("Failed to open ".$this->cert."\n");}
        if (!file_exists($this->key)) {throw new Exception("Failed to open ".$this->key."\n");}
        if (!file_exists($this->wsdl)) {throw new Exception("Failed to open ".$this->wsdl."\n");}
        if(empty($this->service)){      
            $this->handleException('Service name no definido');
        }
    }

    /**
     * setService
     * 
     * define el nombre del servicio de AFIP al cual nos vamos a conectar
     *
     * @param  string $service
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion 
     */
    public function setService($service){
        $this->service      = $service;
        $this->tra          = $this->tra_path.'TRA_'.$service.'_'.$this->env.'.xml';
        $this->tra_tmp      = $this->tra_path.'TRA_'.$service.'_'.$this->env.'.tmp';
        $this->ta_file      = $this->ta_file_path.'TA_'.$service.'_'.$this->env.'.xml';
        return $this;
    }
    
    /**
     * getService
     *
     * @return string
     */
    public function getService(){
        return $this->service;
    }
    
    /**
     * getTa
     *
     * @return string
     */
    public function getTa(){
        return $this->ta_file;
    }
    
    /**
     * getTra
     *
     * @return string
     */
    public function getTra(){
        return $this->tra;
    }
    
    /**
     * json
     *
     * @return AfipConnector | WSAA | Wsfev1 | WSRConstanciaInscripcion
     */
    public function json(){
        header('Content-Type: application/json; charset=utf-8');
        $_ENV['JSON']=true;
        return $this;
    }
    
    /**
     * makeSoapClient
     *
     * @return void
     */
    protected function makeSoapClient(){
        try{

            $wsaa = new WSAA($this->service);
            $this->ta = $wsaa->get();

            $opts = array(
                'ssl' => array('verify_peer' => false, 'verify_peer_name' => false, 'ciphers'=>'AES256-SHA')
              );

            $this->soapClient = new SoapClient(
                $this->wsdl,
                array(
                    'soap_version'   => $this->soap_version,
                    'location'       => $this->url,
                    'trace'          => 1,
                    'exceptions'     => 1,
                    'stream_context' => stream_context_create($opts)
                )
            );
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

            if(!isset($_ENV['CUIT'])){
                new EvalAfipException("No se especificó el valor de CUIT en el archivo .env");
            }

            if(empty($this->ta->credentials->token)){
                new EvalAfipException("El token de acceso no pudo obtenerse");
            }

            if(empty($this->ta->credentials->sign)){
                new EvalAfipException("El sign del token de acceso no pudo obtenerse");
            }

            return array(
                'Token' => $this->ta->credentials->token,
                'Sign'  => $this->ta->credentials->sign,
                'Cuit'  => floatval($_ENV['CUIT']),
            );


    }

   
    

}