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

trait  AutoConfig{
    
    private function setConfigs(){
        $this->loadEnv();
        $this->cert_path=$_ENV['CERT_PATH'];
        $this->wsdl_path=$_ENV['WSDL_PATH'];
         
         if ($_ENV['PRODUCTION']==="TRUE") {
            $this->url  =$this->url_prod;
            $this->cert = ($this->cert!='') ?$this->cert_path.$this->cert :$this->cert_path.$this->cert_prod;
            $this->key = ($this->key!='') ?$this->cert_path.$this->key :$this->cert_path.$this->key_prod;
            $this->wsdl  =$this->wsdl_path.$this->wsdl_prod;
            $this->env   = 'prod'; 
         } else {
            $this->url =$this->url_homo;
            $this->cert = ($this->cert!='') ?$this->cert_path.$this->cert :$this->cert_path.$this->cert_homo;
            $this->key = ($this->key!='') ?$this->cert_path.$this->key :$this->cert_path.$this->key_homo;
            $this->wsdl  =$this->wsdl_path.$this->wsdl_homo;
            $this->env   = 'homo'; 
         }
 
         $cert=openssl_x509_parse(file_get_contents($this->cert));
 
         $_ENV['CERT_CN'] = $cert['subject']['CN'];
        $this->tra_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'tra'.DIRECTORY_SEPARATOR;
        $this->wsaa_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'wsaa'.DIRECTORY_SEPARATOR;
        $this->ta_file_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.'ta'.DIRECTORY_SEPARATOR;
        $this->cbte_path=$_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'].DIRECTORY_SEPARATOR.
                                            'cbte'.DIRECTORY_SEPARATOR.$this->env.DIRECTORY_SEPARATOR;
        
        //  if(!is_dir($_ENV['XML_PATH'].DIRECTORY_SEPARATOR.$_ENV['CERT_CN'])){
        //      mkdir($this->ta_file_path,0777,true);
        //      mkdir($this->tra_path,0777,true);
        //      mkdir($this->cbte_path,0777,true);
        //  }
         if(!is_dir($this->ta_file_path)){
             mkdir($this->ta_file_path,0777,true);
         }
         if(!is_dir($this->tra_path)){
             mkdir($this->tra_path,0777,true);
         }
         if(!is_dir($this->cbte_path)){
             mkdir($this->cbte_path,0777,true);
         }
         if(!is_dir($this->wsaa_path)){
             mkdir($this->wsaa_path,0777,true);
         }

     }
 
     private function loadEnv(){
         $dir = dirname(__DIR__, 2) . '/';
 
 
         if(!empty($_ENV['afip-connector'])){
             return;
         }

         $env = parse_ini_file($dir.'.AfipConnector');
         foreach($env as $k=>$v){
             $_ENV[$k]=$v;
         }

         if(empty($_ENV['JSON'])){
            $_ENV['JSON']=false;
         }

         $_ENV['afip-connector']=true;
         
     }
}