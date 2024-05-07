<?php

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
        try {

            $method = debug_backtrace()[1]['function'];
            $output = $this->reduceOutput($input, $method);
            if ($_ENV['JSON'] === true) {
                return json_encode($output);
            }
            return (array) $output;
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function reduceOutput($input, $method)
    {

        switch ($method) {
            // WSFE
            case 'FEDummy':
                $this->handleException($input);
                return $input;
                break;
            case 'FEParamGetTiposCbte':
                $this->handleException($input->FEParamGetTiposCbteResult);
                return $input->FEParamGetTiposCbteResult->ResultGet->CbteTipo;
                break;
            case 'FEParamGetTiposConcepto':
                $this->handleException($input->FEParamGetTiposConceptoResult);
                return $input->FEParamGetTiposConceptoResult->ResultGet->ConceptoTipo;
                break;
            case 'FEParamGetTiposDoc':
                $this->handleException($input->FEParamGetTiposDocResult);
                return $input->FEParamGetTiposDocResult->ResultGet->DocTipo;
                break;
            case 'FEParamGetTiposIva':
                $this->handleException($input->FEParamGetTiposIvaResult);
                return $input->FEParamGetTiposIvaResult->ResultGet->IvaTipo;
                break;
            case 'FEParamGetTiposMonedas':
                $this->handleException($input->FEParamGetTiposMonedasResult);
                return $input->FEParamGetTiposMonedasResult->ResultGet->Moneda;
                break;
            case 'FEParamGetTiposOpcional':
                $this->handleException($input->FEParamGetTiposOpcionalResult);
                return $input->FEParamGetTiposOpcionalResult->ResultGet->OpcionalTipo;
                break;
            case 'FEParamGetTiposTributos':
                $this->handleException($input->FEParamGetTiposTributosResult);
                return $input->FEParamGetTiposTributosResult->ResultGet->TributoTipo;
                break;
            case 'FEParamGetPtosVenta':
                $this->handleException($input->FEParamGetPtosVentaResult);
                return $input->FEParamGetPtosVentaResult->ResultGet->PtoVenta;
                break;
            case 'FEParamGetCotizacion':
                $this->handleException($input->FEParamGetCotizacionResult);
                return $input->FEParamGetCotizacionResult->ResultGet;
                break;
            case 'FECompUltimoAutorizado':
                $this->handleException($input->FECompUltimoAutorizadoResult);
                return $input->FECompUltimoAutorizadoResult;
                break;
            case 'FECompConsultar':
                $this->handleException($input->FECompConsultarResult);
                return $input->FECompConsultarResult->ResultGet;
                break;
            case 'FEParamGetActividades':
                $this->handleException($input->FEParamGetActividadesResult);
                return $input->FEParamGetActividadesResult->ResultGet->ActividadesTipo;
                break;
            case 'FECAESolicitar':
                $this->handleException($input->FECAESolicitarResult);
                return $input->FECAESolicitarResult;
                break;
                // WSRConstanciaInscripcion
            case 'dummy':
                $this->handleException($input);
                return $input->return;
                break;
            case 'getPersona_v2':
                $this->handleException($input);
                return $input->personaReturn;
                break;
            case 'getPersonaList_v2':
                $this->handleException($input);
                return $input->personaListReturn;
                break;
  
        }
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
