<?php

namespace AfipConnector\Traits;

use AfipConnector\Exceptions\EvalAfipException;

trait  HandleStoreFiles
{

    
    /**
     * storeCbteFiles
     *
     * @param  Cbte $cbte
     * @return void
     */
    protected function storeCbteFiles($cbte){
        $filePrefix=str_pad($cbte->getCbteTipo(),3,"0",STR_PAD_LEFT).'_'.
                    str_pad($cbte->getPtoVta(),5,"0",STR_PAD_LEFT).'_'.
                    str_pad($cbte->getCbteHasta(),8,"0",STR_PAD_LEFT).'_';

        $date=date("YmdHis");

        $requestFileName = $this->cbte_path.$filePrefix."request_".$date.".xml";       
        $request = $this->soapClient->__getLastRequest();
        $requestFile = fopen($requestFileName, "w");
        if(!$requestFile){
            throw new EvalAfipException("No se pudo crear el archivo ".$requestFileName);
        }
        fwrite($requestFile, $request);
        fclose($requestFile);

        $responseFileName = $this->cbte_path.$filePrefix."response_".$date.".xml";  
        $response=$this->soapClient->__getLastResponse();
        $responseFile = fopen($responseFileName, "w");
        if(!$responseFile){
            throw new EvalAfipException("No se pudo crear el archivo ".$responseFileName);
        }
        fwrite($responseFile, $response);
        fclose($responseFile);
    }

    protected function storeLoginFiles(){

        $requestFileName = $this->wsaa_path."request-loginCms.xml";       
        $request = $this->soapClient->__getLastRequest();
        $requestFile = fopen($requestFileName, "w");
        if(!$requestFile){
            throw new EvalAfipException("No se pudo crear el archivo ".$requestFileName);
        }
        fwrite($requestFile, $request);
        fclose($requestFile);

        $responseFileName = $this->wsaa_path."response-loginCms.xml";  
        $response=$this->soapClient->__getLastResponse();
        $responseFile = fopen($responseFileName, "w");
        if(!$responseFile){
            throw new EvalAfipException("No se pudo crear el archivo ".$responseFileName);
        }
        fwrite($responseFile, html_entity_decode($response));
        fclose($responseFile);

    }

}