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
 * Comprador
 * 
 * Detalle compradores vinculados al comprobante que se solicita autorizar (array).
 * 
 */
class Comprador extends Model{

    use \AfipConnector\Traits\ModelHelpers;

    private $DocTipo;
    private $DocNro;
    private $Porcentaje;

    public function __construct($DocTipo = null,$DocNro = null,$Porcentaje = null){
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
     * DocTipo
     * 
     * Tipo de documento del comprador
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
     * Número de documento del comprador (CUIT,DNI, etc.)
     * 
     * Obligatorio
     *
     * @param  string (80) $DocNro
     * @return void
     */
    public function DocNro($DocNro){
        $this->DocNro=$DocNro;
        return $this;
    }
    
    /**
     * Porcentaje
     * 
     * Porcentaje de titularidad que tiene el comprador
     * 
     * Obligatorio
     *
     * @param  float|double (2+2) $Porcentaje
     * @return void
     */
    public function Porcentaje($Porcentaje){
        $this->Porcentaje=$Porcentaje;
        return $this;
    }

}