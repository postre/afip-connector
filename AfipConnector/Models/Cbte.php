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

namespace AfipConnector\Models;


/**
 * Document
 * 
 * FeCabReq: La cabecera del comprobante o lote de comprobantes de ingreso está compuesta por los siguientes campos:
 *      FeCAEReq
 *      FeCabReq
 *      FeDetReq
 * 
 * FeDetReq: El detalle del comprobante o lote de comprobantes de ingreso está compuesto por lo siguientes campos:
 *      Concepto
 *      DocTipo
 *      DocNro
 *      CbteDesde
 *      CbteHasta
 *      CbteFc
 *      ImpTota
 *      ImpTotConc
 *      ImpNeto
 *      ImpOpEx
 *      ImpIVA
 *      ImpTrib
 *      FchServDesde
 *      FchServHasta
 *      FchVtoPago
 *      MonId
 *      MonCotiz
 *      CbtesAso
 *      IVA
 *      Opcionales
 *      Comprador
 *      Actividad
 * 
 */

class Cbte extends Model{

    private $CantReg;
    private $CbteTipo;
    private $PtoVta;
    private $Concepto;
    private $DocTipo;
    private $DocNro;
    private $CbteDesde;
    private $CbteHasta;
    private $CbteFch;
    private $ImpTotal=0;
    private $ImpTotConc=0;
    private $ImpNeto=0;
    private $ImpOpEx=0;
    private $ImpIVA=0;
    private $ImpTrib=0;
    private $FchServDesde=null;
    private $FchServHasta=null;
    private $FchVtoPago=null;
    private $MonId='PES';
    private $MonCotiz=1;
    private $CbtesAsoc=[];
    private $Tributos=[];
    private $Iva=[];
    private $Opcionales=[];
    private $Compradores=[];
    private $PeriodoAsoc=[];
    private $Actividades=[];

    
    /**
     * CantReg
     * 
     * Cantidad de registros del detalle del comprobante o lote de comprobantes de ingreso
     * 
     * Obligatorio
     *
     * @param  int (4) $CantReg
     * @return void
     */
    public function CantReg($CantReg){
        $this->CantReg=$CantReg;
        return $this;
    }
    
    /**
     * CbteTipo
     * 
     * Tipo de comprobante que se está informando. 
     * Si se informa más de un comprobante, todos deben ser del mismo tipo.
     * 
     * los tipos de comprobante válidos se obtienen de 
     * Wsfev1::FEParamGetTiposCbte();
     * 
     * Obligatorio
     *
     * @param  int (3) $CbteTipo
     * @return void
     */
    public function CbteTipo($CbteTipo){
        $this->CbteTipo=$CbteTipo;
        return $this;
    }
    
        
    /**
     * getCbteTipo
     *
     * @return int
     */
    public function getCbteTipo(){
        return $this->CbteTipo;
    }
    
    /**
     * PtoVta
     * 
     * Punto de Venta del comprobante que se está informando. 
     * Si se informa más de un comprobante, todos deben corresponder al mismo punto de venta.
     * 
     * los puntos de venta para el certificado relacionado se obtienen de 
     * Wsfev1::FEParamGetPtosVenta();
     * 
     * 
     * Obligatorio
     *
     * @param  int (5) $PtoVta
     * @return void
     */
    public function PtoVta($PtoVta){
        $this->PtoVta=$PtoVta;
        return $this;
    }
    
    /**
     * getPtoVta
     *
     * @return int
     */
    public function getPtoVta(){
        return $this->PtoVta;
    }
    
    /**
     * Concepto
     * 
     * los conceptos válidos se obtienen de 
     * Wsfev1::FEParamGetTiposConcepto();
     * 
     * 1 - Producto
     * 2 - Servicios
     * 3 - Productos y servicios
     * 
     * Obligatorio
     *
     * @param  int  (2) $Concepto
     * @return void
     */
    public function Concepto($Concepto){
        $this->Concepto=$Concepto;
        return $this;
    }
    
    /**
     * DocTipo
     * 
     * Código de documento identificatorio del comprador
     * 
     * los tipos de dócumento válidos se obtienen de 
     * Wsfev1::FEParamGetTiposDoc();
     * 
     * Obligatorio
     *
     * @param  int (2) $DocTipo
     * @return void
     */
    public function DocTipo($DocTipo){
        $this->DocTipo=$DocTipo;
        return $this;
    }
    
    /**
     * DocNro
     * 
     * Nro. De identificación del comprador (CUIT,DNI,etc.)
     * 
     * Obligatorio
     *
     * @param  int (11) $DocNro
     * @return void
     */
    public function DocNro($DocNro){
        $this->DocNro=$DocNro;
        return $this;
    }
    
    /**
     * CbteDesde
     * 
     * Nro. De comprobante desde Rango 1- 99999999
     * 
     * Se deberia obtener el último comprobante generado y sumarle 1 para el próximo
     * 
     * Wsfev1::FECompUltimoAutorizado($pdv, $cbteTipo);
     * 
     * Obligatorio
     *
     * @param  int (8) $CbteDesde
     * @return void
     */
    public function CbteDesde($CbteDesde){
        $this->CbteDesde=$CbteDesde;
        return $this;
    }

    
    /**
     * getCbteDesde
     *
     * @return int
     */
    public function getCbteDesde(){
        return $this->CbteDesde;
    }

    /**
     * CbteHasta
     * 
     * Nro. De comprobante registrado hasta Rango 1- 99999999
     * 
     * Si solo se va a generar un comprobante se usa el mismo valor
     * que CbteDesde. Si se desean crear varios se debe poner el valor
     * de CbteDesde + la cantidad deseada, Ej: si se desean crear 10 
     * comporbantes y CbteDesde es 25 el valor seria 25+10= 35 
     * 
     * 
     * Obligatorio
     *
     * @param  int (8) $CbteHasta
     * @return void
     */
    public function CbteHasta($CbteHasta){
        $this->CbteHasta=$CbteHasta;
        return $this;
    }

    
    /**
     * getCbteHasta
     *
     * @return void
     */
    public function getCbteHasta(){
        return $this->CbteHasta;
    }
    
    /**
     * CbteFch
     * 
     * Fecha del comprobante (yyyymmdd). Para concepto igual a 1, la fecha de
     * emisión del comprobante puede ser hasta 5 días anteriores o posteriores respecto
     * de la fecha de generación: La misma no podrá exceder el mes de presentación. Si
     * se indica Concepto igual a 2 ó 3 puede ser hasta 10 días anteriores o posteriores
     * a la fecha de generación. Si no se envía la fecha del comprobante se asignará la
     * fecha de proceso. Para comprobantes del tipo MiPyMEs
     * (FCE) del tipo Factura, la fecha de emisión del comprobante debe ser desde
     * 5 días anteriores y hasta 1 día posterior respecto de la fecha de generación. Para
     * notas de débito y crédito es hasta 5 dias anteriores y tiene que ser posterior o igual
     * anteriores y tiene que ser posterior o igual la la fecha del comprobante asociado.
     * 
     * Opcional
     *
     * @param  string (8) (yyyymmdd) $CbteFch
     * @return void
     */
    public function CbteFch($CbteFch){
        $this->CbteFch=$CbteFch;
        return $this;
    }
    
    /**
     * ImpTotal
     * 
     * Importe total del comprobante, Debe ser igual a Importe neto no gravado + Importe
     * exento + Importe neto gravado + todos los campos de IVA al XX% + Importe de tributos.
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpTotal
     * @return void
     */
    public function ImpTotal($ImpTotal){
        $this->ImpTotal=$ImpTotal;
        return $this;
    }
    
    /**
     * ImpTotConc
     * 
     * Importe neto no gravado. Debe ser menor o igual a Importe total y
     * no puede ser menor a cero. No puede ser mayor al Importe total de la
     * operación ni menor a cero (0). Para comprobantes tipo C debe ser igual a cero (0).
     * Para comprobantes tipo Bienes Usados – Emisor Monotributista este campo 
     * corresponde al importe subtotal.
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpTotConc
     * @return void
     */
    public function ImpTotConc($ImpTotConc){
        $this->ImpTotConc=$ImpTotConc;
        return $this;
    }
    
    /**
     * ImpNeto
     * 
     * Importe neto gravado. Debe ser menor o igual a Importe total y no puede ser menor
     * a cero. Para comprobantes tipo C este campo corresponde al Importe del Sub Total.
     * Para comprobantes tipo Bienes Usados – Emisor Monotributista no debe informarse
     * o debe ser igual a cero (0).
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpNeto
     * @return void
     */
    public function ImpNeto($ImpNeto){
        $this->ImpNeto=$ImpNeto;
        return $this;
    }
    
    /**
     * ImpOpEx
     * 
     * Importe exento. Debe ser menor o igual a Importe total y no puede ser menor a cero.
     * Para comprobantes tipo C debe ser igual a cero (0).
     * Para comprobantes tipo Bienes Usados – Emisor Monotributista no debe informarse 
     * o debe ser igual a cero (0).
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpOpEx
     * @return void
     */
    public function ImpOpEx($ImpOpEx){
        $this->ImpOpEx=$ImpOpEx;
        return $this;
    }
    
    /**
     * ImpIVA
     * 
     * Suma de los importes del array de IVA. Para comprobantes tipo C debe ser igual a cero (0).
     * Para comprobantes tipo Bienes Usados – Emisor Monotributista no debe informarse 
     * o debe ser igual a cero (0).
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpIVA
     * @return void
     */
    public function ImpIVA($ImpIVA){
        $this->ImpIVA=$ImpIVA;
        return $this;
    }
    
    /**
     * ImpTrib
     * 
     * Suma de los importes del array de tributos
     * 
     * Obligatorio
     *
     * @param  double|float (13+2) $ImpTrib
     * @return void
     */
    public function ImpTrib($ImpTrib){
        $this->ImpTrib=$ImpTrib;
        return $this;
    }
    
    /**
     * FchServDesde
     * 
     * Fecha de inicio del abono para el servicio a facturar. Dato obligatorio para
     * concepto 2 o 3 (Servicios / Productos y Servicios). Formato yyyymmdd
     * 
     * Opcional
     *
     * @param  string (8) (yyyymmdd) $FchServDesde
     * @return void
     */
    public function FchServDesde($FchServDesde){
        $this->FchServDesde=$FchServDesde;
        return $this;
    }
    
    /**
     * FchServHasta
     * 
     * Fecha de fin del abono para el servicio a facturar. Dato obligatorio para concepto
     * 2 o 3 (Servicios / Productos y Servicios). Formato yyyymmdd. FchServHasta no 
     * puede ser menor a FchServDesde
     * 
     * Opcional
     *
     * @param  string (8) (yyyymmdd) $FchServHasta
     * @return void
     */
    public function FchServHasta($FchServHasta){
        $this->FchServHasta=$FchServHasta;
        return $this;
    }

    /**
     * FchVtoPago
     * 
     * Fecha de vencimiento del pago servicio a facturar. Dato obligatorio para
     * concepto 2 o 3 (Servicios / Productos y Servicios) o Facturas del tipo MiPyMEs
     * (FCE). Formato yyyymmdd. Debe ser igual o posterior a la fecha del comprobante.
     * 
     * Opcional
     *
     * @param  string (8) (yyyymmdd) $FchVtoPago
     * @return void
     */
    public function FchVtoPago($FchVtoPago){
        $this->FchVtoPago=$FchVtoPago;
        return $this;
    }
    
    /**
     * MonId
     * 
     * Código de moneda del comprobante. Consultar método
     * FEParamGetTiposMonedas para valores posibles
     * 
     * Wsfev1::FEParamGetTiposMonedas();
     * 
     * Obligatorio
     *
     * @param  string (3) $MonId
     * @return void
     */
    public function MonId($MonId){
        $this->MonId=$MonId;
        return $this;
    }
    
    /**
     * MonCotiz
     * 
     * Cotización de la moneda informada. Para PES, pesos argentinos la misma debe ser 1
     * 
     * Wsfev1::FEParamGetCotizacion($currencyId)
     * 
     * Obligatorio
     *
     * @param  double|float (4+6) $MonCotiz
     * @return void
     */
    public function MonCotiz($MonCotiz){
        $this->MonCotiz=$MonCotiz;
        return $this;
    }
    
    /**
     * CbtesAsoc
     *
     * Obejeto tipo CbtesAsoc que envia a AFIP un Array para informar los comprobantes asociados <CbteAsoc>
     * 
     * Opcional
     * 
     * @param  CbtAsoc $CbtesAsoc
     * @return void
     */
    public function CbtesAsoc($CbtesAsoc){
        $this->CbtesAsoc=[...$this->CbtesAsoc,$CbtesAsoc->toArray()];
        return $this;
    }
    
    /**
     * Tributos
     * 
     * Obejeto tipo Tributos que envia a AFIP un Array para informar los tributos asociados a un comprobante <Tributo>.
     *
     * Opcional
     * 
     * @param  Tributos $Tributos 
     * @return void
     */
    public function Tributos($Tributos){
        $this->Tributos=[...$this->Tributos,$Tributos->toArray()];
        return $this;
    }
    
    /**
     * IVA
     * 
     * Obejeto tipo IVA que envia a AFIP un Array para informar las alícuotas y sus
     * importes asociados a un comprobante <AlicIva>.
     * Para comprobantes tipo C y Bienes Usados – Emisor Monotributista no debe 
     * informar el array.
     * 
     * Opcional
     *
     * @param  IVA $IVA
     * @return void
     */
    public function IVA($Iva){
        $this->Iva=[...$this->Iva,$Iva->toArray()];
        return $this;
    }
    
    /**
     * Opcionales
     * 
     * Array de campos auxiliares. Reservado usos futuros <Opcional>. Adicionales por R.G.
     * 
     * Opcional
     *
     * @param  Opcionales $Opcionales
     * @return void
     */
    public function Opcionales($Opcionales){
        $this->Opcionales=[...$this->Opcionales,$Opcionales->toArray()];
        return $this;
    }
    
    /**
     * Compradores
     * 
     * Obejeto tipo Compradores que envia a AFIP un Array para informar los múltiples compradores.
     * 
     * Opcional
     *
     * @param  Compradores $Compradores
     * @return void
     */
    public function Compradores($Compradores){
        $this->Compradores=[...$this->Compradores,$Compradores->toArray()];
        return $this;
    }

        
    /**
     * PeriodoAsoc
     * 
     * Estructura compuesta por la fecha desde y la fecha hasta del periodo que se quiere identificar
     * 
     * Opcional
     *
     * @param  mixed $PeriodoAsoc
     * @return void
     */
    public function PeriodoAsoc($PeriodoAsoc){
        $this->PeriodoAsoc=$PeriodoAsoc;
        return $this;
    }
    
    /**
     * Actividades
     * 
     * Array para informar las actividades asociadas a un comprobante.
     *
     * Opcional
     * 
     * @param  Actividad $Actividades
     * @return void
     */
    public function Actividades($Actividades){
        $this->Actividades=[...$this->Actividades,$Actividades->toArray()];
        return $this;
    }

    public function get(){
        // header('Content-Type: application/json; charset=utf-8');
        $FECAEDetRequest = [];
        $FECAEDetRequest['Concepto']    = $this->Concepto;
        $FECAEDetRequest['DocTipo']     = $this->DocTipo;
        $FECAEDetRequest['DocNro']      = $this->DocNro;
        $FECAEDetRequest['CbteDesde']   = $this->CbteDesde;
        $FECAEDetRequest['CbteHasta']   = $this->CbteHasta;
        $FECAEDetRequest['CbteFch']     = $this->CbteFch;
        $FECAEDetRequest['ImpTotal']    = $this->ImpTotal;
        $FECAEDetRequest['ImpTotConc']  = $this->ImpTotConc;
        $FECAEDetRequest['ImpNeto']     = $this->ImpNeto;
        $FECAEDetRequest['ImpOpEx']     = $this->ImpOpEx;
        $FECAEDetRequest['ImpTrib']     = $this->ImpTrib;
        $FECAEDetRequest['ImpIVA']      = $this->ImpIVA;
        $FECAEDetRequest['MonId']       = $this->MonId;
        $FECAEDetRequest['MonCotiz']    = $this->MonCotiz;

        if($this->FchServDesde){
            $FECAEDetRequest['FchServDesde'] = $this->FchServDesde;
        }
        if($this->FchServHasta){
            $FECAEDetRequest['FchServHasta'] = $this->FchServHasta;
        }
        if($this->FchVtoPago){
            $FECAEDetRequest['FchVtoPago'] = $this->FchVtoPago;
        }


        if(is_array($this->Iva) && !empty($this->Iva)){
            $FECAEDetRequest['Iva']=[];
            foreach($this->Iva as $k=>$v){
                $FECAEDetRequest['Iva'][]=$v;
            }
        }

        if(is_array($this->CbtesAsoc) && !empty($this->CbtesAsoc)){
            $FECAEDetRequest['CbtesAsoc']=[];
            foreach($this->CbtesAsoc as $k=>$v){
                $FECAEDetRequest['CbtesAsoc'][]=$v;
            }
        }

        if(is_array($this->Tributos) && !empty($this->Tributos)){
            $FECAEDetRequest['Tributos']=[];
            foreach($this->Tributos as $k=>$v){
                $FECAEDetRequest['Tributos'][]=$v;
            }
        }

        if(is_array($this->Opcionales) && !empty($this->Opcionales)){
            $FECAEDetRequest['Opcionales']=[];
            foreach($this->Opcionales as $k=>$v){
                $FECAEDetRequest['Opcionales'][]=$v;
            }
        }

        if(is_array($this->Compradores) && !empty($this->Compradores)){
            $FECAEDetRequest['Compradores']=[];
            foreach($this->Compradores as $k=>$v){
                $FECAEDetRequest['Compradores'][]=$v;
            }
        }

        if(is_array($this->Actividades) && !empty($this->Actividades)){
            $FECAEDetRequest['Actividades']=[];
            foreach($this->Actividades as $k=>$v){
                $FECAEDetRequest['Actividades'][]=$v;
            }
        }

        if(!empty($this->PeriodoAsoc)){
            $FECAEDetRequest['PeriodoAsoc']=$this->PeriodoAsoc->toArray();
        }

        $request = [
	
				'FeCabReq' => [
					'CantReg' 	=>  $this->CbteHasta-$this->CbteDesde+1,
					'PtoVta' 	=> $this->PtoVta,
					'CbteTipo' 	=>  $this->CbteTipo
                ],
				'FeDetReq' => [
					'FECAEDetRequest' => $FECAEDetRequest
                ]
        ];

        return $request;
    }

}