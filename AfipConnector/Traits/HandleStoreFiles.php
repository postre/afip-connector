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