<?php
namespace AfipConnector\Afip;

use AfipConnector\Exceptions\AfipException;
use AfipConnector\Exceptions\EvalAfipException;
use Exception;
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
