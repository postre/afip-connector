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

use ReflectionMethod;

/**
 * Tributos
 * 
 * Detalle de tributos relacionados con el comprobante que se solicita autorizar (array).
 * 
 */
class Tributo extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $Id;
    private $Desc;
    private $BaseImp;
    private $Alic;
    private $Importe;

    public function __construct($Id = null, $Desc = null, $BaseImp = null, $Alic = null, $Importe = null){
        $reflection = new ReflectionMethod($this, '__construct');
        foreach($reflection->getParameters() AS $arg)
        {
            $name=$arg->getName();
            if($$name!==null){
                $this->$name = ${$name};
            } 
        }
    }
    
    
    /**
     * Id
     * 
     * Código tributo según método FEParamGetTiposTributos
     * 
     * Wsfev1::FEParamGetTiposTributos()->Id;
     * 
     * Obligatorio
     *
     * @param  int (2) $Id
     * @return void
     */
    public function Id($Id){
        $this->Id=$Id;
        return $this;
    }      
    /**
     * Desc
     * 
     * Descripción del tributo según método FEParamGetTiposTributos
     * 
     * Wsfev1::FEParamGetTiposTributos()->Desc;
     * 
     * Opcional
     *
     * @param  string (80) $Desc
     * @return void
     */
    public function Desc($Desc){
        $this->Desc=$Desc;
        return $this;
    }    

    /**
     * BaseImp
     * 
     * Base imponible para la determinación del tributo
     *
     * Obligatorio
     * 
     * @param  float|double (12+2) $BaseImp
     * @return void
     */
    public function BaseImp($BaseImp){
        $this->BaseImp=$BaseImp;
        return $this;
    }  
    
    /**
     * Alic
     * 
     * Alícuota
     * 
     * Obligatorio
     *
     * @param  float|double (3+2) $Alic
     * @return void
     */
    public function Alic($Alic){
        $this->Alic=$Alic;
        return $this;
    }  
    
    /**
     * Importe
     * 
     * Importe del tributo
     * 
     * Obligatorio
     *
     * @param  float|double (13+2) $Importe
     * @return void
     */
    public function Importe($Importe){
        $this->Importe=$Importe;
        return $this;
    }  


}