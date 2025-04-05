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

use AfipConnector\Afip\Wsfev1;
use AfipConnector\Models\Cbte;
use AfipConnector\Models\IVA;

require '..\..\AfipConnector\autoload.php';

$wsfe = new Wsfev1();
$ultimoCbte=$wsfe->FECompUltimoAutorizado(4,6);
$proximoCbte=$ultimoCbte['CbteNro']+1;

$cbte = new Cbte();

// Cantidad de Comprobantes a autorizar
$cbte->CantReg(1);

// los puntos de venta para el certificado relacionado se obtienen de Wsfev1::FEParamGetPtosVenta();
$cbte->PtoVta(4);

// 6=Factura B -  los tipos de documento válidos se obtienen de  Wsfev1::FEParamGetTiposCbte()
$cbte->CbteTipo(6);

// 1 - Producto | 2 - Servicios | 3 - Productos y servicios
$cbte->Concepto(1);

// 80=CUIT - los tipos de documento válidos se obtienen de Wsfev1::FEParamGetTiposDoc();
$cbte->DocTipo(80);

// Documento del comprador, en este caso CUIT
$cbte->DocNro(20221064233);

// ver si dar método para que devuelva el próximo comprobante
$cbte->CbteDesde($proximoCbte);

// ver si dar método para que devuelva el próximo comprobante
$cbte->CbteHasta($proximoCbte);

// fecha en formato yyyymmdd
$cbte->CbteFch(date("Ymd"));

// Importe total
$cbte->ImpTotal(12100.00);

// importe neto no gravado
$cbte->ImpTotConc(0);

// importe neto
$cbte->ImpNeto(10000.00);

// Importe exento de IVA
$cbte->ImpOpEx(0);

// Importe IVA
$cbte->ImpIVA(2100.00);

// Moneda usada para el comprobante (ver listado en Wsfev1::FEParamGetTiposMonedas)
$cbte->MonId('PES');

// Cotización de la moneda utilizada, 1 para PES, para ver otras Wsfev1::FEParamGetCotizacion('011')
$cbte->MonCotiz(1);

// Alicuotas asociadas al comprobante, objeto IVA
$cbte->IVA(new IVA(5,10000.00,2100.00));

echo $wsfe->json()->FECAESolicitar($cbte);


/**
 * OTRAS CONFIGURACIONES OPCIONALES SEGÚN: 
 *      1 - EL COMPROBANTE A EMITIR
 *      2 - LA CONDICIÓN DEL EMISOR
 *      3 - LA CONDICIÓN DEL DESTINATARIO
 * 
 */

// Importe total de los tributos Añadidos
// $cbte->ImpTrib();  

// Fecha de inicio del servicio (yyyymmdd), obligatorio para Conceptos 2 y 3
// $cbte->FchServDesde();                                           

// Fecha de finalización del servicio (yyyymmdd), obligatorio para Conceptos 2 y 3
// $cbte->FchServHasta();                                           

// Fecha de Vencimiento del servicio (yyyymmdd), obligatorio para Conceptos 2 y 3
// $cbte->FchVtoPago();  

// Comprobantes asociados, obligatorio para notas de crédito
// $cbte->CbtesAsoc(new CbtesAsoc(1,4,2,20317236965,date("Ymd")));  

// Comprobantes asociados, obligatorio para notas de crédito
// $cbte->CbtesAsoc(new CbtesAsoc(1,4,3,20317236965,date("Ymd")));  

// Tributos asociados al comprobante
// $cbte->Tributos(new Tributo(5,'IIBB',500,5.2,26));               

// Opcionales asociados al comprobante    
// $cbte->Opcionales(new Opcional(2,12345678));                     

// Opcionales asociados al comprobante
// $cbte->Opcionales(new Opcional(10,1));                           

// Compradores asociados al comprobante
// $cbte->Compradores(new Comprador(80,20268261053,50));            

// Compradores asociados al comprobante
// $cbte->Compradores(new Comprador(96,27687243,50));               

// Actividades asociadas al comprobante (Wsfev1::FEParamGetActividades())
// $cbte->Actividades(new Actividad(259999));                       

// Actividades asociadas al comprobante (Wsfev1::FEParamGetActividades())
// $cbte->Actividades(new Actividad(731009));                       

// Período asociado al comprobante
// $cbte->PeriodoAsoc(new Periodo('20240504','20240505'));           


