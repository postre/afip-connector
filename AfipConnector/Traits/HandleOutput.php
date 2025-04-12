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
use Exception;

trait  HandleOutput
{

    /**
     * handleOutput
     * 
     * Si esta definida como true la variable $this->json
     * retorna un json sino devuelve lo que recibe
     *
     * @param  array $output
     * @param  string $method
     * @return array|json string
     */
    /**
     * handleOutput
     *
     * @param  mixed $output

     * @return void
     */
    protected function handleOutput($input)
    {
        $method = debug_backtrace()[1]['function'];
        $output = $this->reduceOutput($input, $method);
        if ($_ENV['JSON'] === true) {
            return json_encode($output);
        }
        return (array) $output;
 
    }

    private function reduceOutput($input, $method)
    {
        $output=null;
        switch ($method) {
            // WSFE
            case 'FEParamGetTiposCbte':
                $this->handleException($input->FEParamGetTiposCbteResult);
                $output = $input->FEParamGetTiposCbteResult->ResultGet->CbteTipo;
                break;
            case 'FEParamGetTiposConcepto':
                $this->handleException($input->FEParamGetTiposConceptoResult);
                $output = $input->FEParamGetTiposConceptoResult->ResultGet->ConceptoTipo;
                break;
            case 'FEParamGetTiposDoc':
                $this->handleException($input->FEParamGetTiposDocResult);
                $output = $input->FEParamGetTiposDocResult->ResultGet->DocTipo;
                break;
            case 'FEParamGetTiposIva':
                $this->handleException($input->FEParamGetTiposIvaResult);
                $output = $input->FEParamGetTiposIvaResult->ResultGet->IvaTipo;
                break;
            case 'FEParamGetTiposMonedas':
                $this->handleException($input->FEParamGetTiposMonedasResult);
                $output = $input->FEParamGetTiposMonedasResult->ResultGet->Moneda;
                break;
            case 'FEParamGetTiposOpcional':
                $this->handleException($input->FEParamGetTiposOpcionalResult);
                $output = $input->FEParamGetTiposOpcionalResult->ResultGet->OpcionalTipo;
                break;
            case 'FEParamGetTiposTributos':
                $this->handleException($input->FEParamGetTiposTributosResult);
                $output = $input->FEParamGetTiposTributosResult->ResultGet->TributoTipo;
                break;
            case 'FEParamGetPtosVenta':
                $this->handleException($input->FEParamGetPtosVentaResult);
                $output = $input->FEParamGetPtosVentaResult->ResultGet->PtoVenta;
                break;
            case 'FEParamGetCotizacion':
                $this->handleException($input->FEParamGetCotizacionResult);
                $output = $input->FEParamGetCotizacionResult->ResultGet;
                break;
            case 'FECompUltimoAutorizado':
                $this->handleException($input->FECompUltimoAutorizadoResult);
                $output = $input->FECompUltimoAutorizadoResult;
                break;
            case 'FECompConsultar':
                $this->handleException($input->FECompConsultarResult);
                $output = $input->FECompConsultarResult->ResultGet;
                break;
            case 'FEParamGetActividades':
                $this->handleException($input->FEParamGetActividadesResult);
                $output = $input->FEParamGetActividadesResult->ResultGet->ActividadesTipo;
                break;
            case 'FECAESolicitar':
                $this->handleException($input->FECAESolicitarResult);
                $output = $input->FECAESolicitarResult;
                break;
            case 'FEParamGetCondicionIvaReceptor':
                $this->handleException($input->FEParamGetCondicionIvaReceptorResult);
                $output = $input->FEParamGetCondicionIvaReceptorResult->ResultGet->CondicionIvaReceptor;
                break;
                // WSRConstanciaInscripcion
            case 'dummy':
                $this->handleException($input);
                $output = $input->return;
                break;
            case 'getPersona_v2':
                $this->handleException($input);
                $output = $input->personaReturn;
                break;
            case 'getPersonaList_v2':
                $this->handleException($input);
                $output = $input->personaListReturn;
                break;
            default:
                $this->handleException($input);
                $output = $input;
                break;
        }

        return $output;
    }

    protected function handleException($error)
    {

        if (is_object($error) && property_exists($error, 'Errors')) {
            foreach ($error->Errors as $error) {
                new EvalAfipException($error->Msg,$error->Code);
            }
        } elseif (is_string($error)) {
            new EvalAfipException($error);
        }
    }


    protected function objectToArray($d)
    {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
            * Return array converted to object
            * Using __FUNCTION__ (Magic constant)
            * for recursive call
            */
            return array_map(__FUNCTION__, $d);
        } else {
            // Return array
            return $d;
        }
    }
}
